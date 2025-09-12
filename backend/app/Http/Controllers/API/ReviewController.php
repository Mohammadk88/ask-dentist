<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewQuestion;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Get review questions for a specific doctor
     */
    public function getQuestions(Request $request): JsonResponse
    {
        $questions = ReviewQuestion::active()
            ->ordered()
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'options' => $question->options,
                    'is_required' => $question->is_required,
                    'category' => $question->category,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'questions' => $questions
            ]
        ]);
    }

    /**
     * Submit a review for a doctor
     */
    public function submitReview(Request $request, string $doctorId): JsonResponse
    {
        try {
            // Validate the doctor exists
            $doctor = Doctor::findOrFail($doctorId);
            
            // Check if user is authenticated and is a patient
            if (!Auth::check() || !Auth::user()->isPatient()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only authenticated patients can submit reviews'
                ], 403);
            }

            $patient = Auth::user();

            // Check if patient has already reviewed this doctor
            $existingReview = Review::where('patient_id', $patient->id)
                                   ->where('doctor_id', $doctorId)
                                   ->first();

            if ($existingReview) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already reviewed this doctor'
                ], 422);
            }

            // Get active questions for validation
            $questions = ReviewQuestion::active()->get();
            
            // Build validation rules
            $rules = [
                'rating' => 'required|integer|min:1|max:5',
                'answers' => 'required|array',
                'comment' => 'nullable|string|max:1000',
            ];

            // Add validation for each required question
            foreach ($questions as $question) {
                if ($question->is_required) {
                    $answerKey = "answers.{$question->id}";
                    
                    switch ($question->question_type) {
                        case 'rating':
                            $rules[$answerKey] = 'required|integer|min:1|max:5';
                            break;
                        case 'text':
                            $rules[$answerKey] = 'required|string|max:500';
                            break;
                        case 'boolean':
                            $rules[$answerKey] = 'required|boolean';
                            break;
                        case 'multiple_choice':
                            if ($question->options) {
                                $validOptions = array_keys($question->options);
                                $rules[$answerKey] = ['required', Rule::in($validOptions)];
                            }
                            break;
                    }
                }
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Calculate weighted score if weights are configured
            $weightedScore = $this->calculateWeightedScore(
                $request->input('answers', []),
                $questions
            );

            // Create the review
            $review = Review::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctorId,
                'clinic_id' => $doctor->clinics->first()?->id, // Get primary clinic
                'rating' => $request->input('rating'),
                'answers_json' => $request->input('answers', []),
                'comment' => $request->input('comment'),
                'published_at' => now(), // Auto-publish for now
            ]);

            // Update doctor's aggregate rating
            $this->updateDoctorRating($doctorId);

            return response()->json([
                'status' => 'success',
                'message' => 'Review submitted successfully',
                'data' => [
                    'review_id' => $review->id,
                    'weighted_score' => $weightedScore,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews for a doctor
     */
    public function getDoctorReviews(string $doctorId): JsonResponse
    {
        $doctor = Doctor::findOrFail($doctorId);
        
        $reviews = Review::with(['patient'])
            ->where('doctor_id', $doctorId)
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $reviewsData = $reviews->getCollection()->map(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'patient_name' => $review->patient->name ?? 'Anonymous',
                'created_at' => $review->created_at->format('Y-m-d'),
                'answers' => $review->answers_json,
            ];
        });

        // Get rating distribution
        $ratingDistribution = Review::where('doctor_id', $doctorId)
            ->published()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        return response()->json([
            'status' => 'success',
            'data' => [
                'reviews' => $reviewsData,
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
                'summary' => [
                    'average_rating' => $doctor->rating,
                    'total_reviews' => $doctor->total_reviews,
                    'rating_distribution' => $ratingDistribution,
                ]
            ]
        ]);
    }

    /**
     * Calculate weighted score based on question weights
     */
    private function calculateWeightedScore(array $answers, $questions): float
    {
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($questions as $question) {
            if (!isset($answers[$question->id])) {
                continue;
            }

            $answer = $answers[$question->id];
            $weight = 1; // Default weight

            // Get weight from question configuration
            if ($question->weights && is_array($question->weights)) {
                $weight = $question->weights['weight'] ?? 1;
            }

            // Convert answer to numeric score
            $score = 0;
            switch ($question->question_type) {
                case 'rating':
                    $score = (int) $answer;
                    break;
                case 'boolean':
                    $score = $answer ? 5 : 1; // True = 5, False = 1
                    break;
                case 'multiple_choice':
                    // If options have scores defined
                    if ($question->options && isset($question->options[$answer])) {
                        $score = $question->options[$answer]['score'] ?? 3;
                    } else {
                        $score = 3; // Default neutral score
                    }
                    break;
                case 'text':
                    $score = 3; // Neutral score for text responses
                    break;
            }

            $totalScore += $score * $weight;
            $totalWeight += $weight;
        }

        return $totalWeight > 0 ? round($totalScore / $totalWeight, 2) : 0;
    }

    /**
     * Update doctor's aggregate rating
     */
    private function updateDoctorRating(string $doctorId): void
    {
        $stats = Review::where('doctor_id', $doctorId)
            ->published()
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        Doctor::where('id', $doctorId)->update([
            'rating' => round($stats->average_rating ?? 0, 2),
            'total_reviews' => $stats->total_reviews ?? 0,
        ]);
    }
}
