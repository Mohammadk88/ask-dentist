<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->updateDoctorRating($review->doctor_id);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        // Only update if rating or published status changed
        if ($review->isDirty(['rating', 'published_at'])) {
            $this->updateDoctorRating($review->doctor_id);
        }
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->updateDoctorRating($review->doctor_id);
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        $this->updateDoctorRating($review->doctor_id);
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        $this->updateDoctorRating($review->doctor_id);
    }

    /**
     * Update doctor's aggregate rating
     */
    private function updateDoctorRating(string $doctorId): void
    {
        try {
            $stats = Review::where('doctor_id', $doctorId)
                ->published()
                ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
                ->first();

            Doctor::where('id', $doctorId)->update([
                'rating' => round($stats->average_rating ?? 0, 2),
                'total_reviews' => $stats->total_reviews ?? 0,
            ]);

        } catch (\Exception $e) {
            // Log the error but don't throw exception to avoid breaking the main flow
            \Log::error('Failed to update doctor rating', [
                'doctor_id' => $doctorId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
