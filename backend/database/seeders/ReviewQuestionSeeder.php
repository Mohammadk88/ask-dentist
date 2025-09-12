<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReviewQuestion;

class ReviewQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question_text' => 'How would you rate the overall communication with your doctor?',
                'question_type' => 'rating',
                'category' => 'communication',
                'weights' => [
                    '1' => '1.0',
                    '2' => '2.0',
                    '3' => '3.0',
                    '4' => '4.0',
                    '5' => '5.0',
                ],
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'question_text' => 'How satisfied are you with the treatment quality?',
                'question_type' => 'rating',
                'category' => 'treatment',
                'weights' => [
                    '1' => '1.0',
                    '2' => '2.0',
                    '3' => '3.0',
                    '4' => '4.0',
                    '5' => '5.0',
                ],
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'question_text' => 'Was the clinic facility clean and well-maintained?',
                'question_type' => 'boolean',
                'category' => 'facility',
                'weights' => [
                    'yes' => '5.0',
                    'no' => '1.0',
                ],
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'question_text' => 'How would you rate the booking and scheduling process?',
                'question_type' => 'rating',
                'category' => 'booking',
                'weights' => [
                    '1' => '1.0',
                    '2' => '2.0',
                    '3' => '3.0',
                    '4' => '4.0',
                    '5' => '5.0',
                ],
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'question_text' => 'What was your main reason for choosing this doctor?',
                'question_type' => 'multiple_choice',
                'category' => 'overall',
                'options' => [
                    'expertise' => 'Doctor expertise and qualifications',
                    'location' => 'Convenient location',
                    'price' => 'Competitive pricing',
                    'reviews' => 'Positive reviews from other patients',
                    'recommendation' => 'Recommendation from friend/family',
                    'technology' => 'Modern equipment and technology',
                ],
                'weights' => [
                    'expertise' => '5.0',
                    'location' => '3.0',
                    'price' => '3.0',
                    'reviews' => '4.0',
                    'recommendation' => '4.0',
                    'technology' => '4.0',
                ],
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'question_text' => 'Would you recommend this doctor to friends and family?',
                'question_type' => 'boolean',
                'category' => 'overall',
                'weights' => [
                    'yes' => '5.0',
                    'no' => '1.0',
                ],
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'question_text' => 'Please share any additional comments about your experience',
                'question_type' => 'text',
                'category' => 'overall',
                'weights' => [
                    'positive' => '5.0',
                    'neutral' => '3.0',
                    'negative' => '1.0',
                ],
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'question_text' => 'How did you hear about our platform?',
                'question_type' => 'multiple_choice',
                'category' => 'overall',
                'options' => [
                    'search' => 'Google search',
                    'social' => 'Social media',
                    'recommendation' => 'Friend/family recommendation',
                    'advertisement' => 'Online advertisement',
                    'medical_referral' => 'Medical professional referral',
                    'other' => 'Other',
                ],
                'weights' => [
                    'search' => '3.0',
                    'social' => '3.0',
                    'recommendation' => '5.0',
                    'advertisement' => '2.0',
                    'medical_referral' => '4.0',
                    'other' => '2.0',
                ],
                'is_required' => false,
                'is_active' => false, // Optional question, disabled by default
                'sort_order' => 8,
            ],
        ];

        foreach ($questions as $question) {
            ReviewQuestion::create($question);
        }

        $this->command->info('Review questions seeded successfully');
        $this->command->info('Created ' . count($questions) . ' review questions across different categories');
    }
}
