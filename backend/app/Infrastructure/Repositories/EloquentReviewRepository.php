<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Review;
use App\Domain\Repositories\ReviewRepositoryInterface;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ClinicId;
use App\Domain\ValueObjects\Rating;
use App\Models\Review as EloquentReview;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function findById(int $id): ?Review
    {
        $eloquentReview = EloquentReview::find($id);

        if (!$eloquentReview) {
            return null;
        }

        return $this->toDomainEntity($eloquentReview);
    }

    public function findByPatientId(UserId $patientId): array
    {
        $eloquentReviews = EloquentReview::where('patient_id', $patientId->value)->get();

        return $eloquentReviews->map(fn($review) => $this->toDomainEntity($review))->toArray();
    }

    public function findByDoctorId(UserId $doctorId): array
    {
        $eloquentReviews = EloquentReview::where('doctor_id', $doctorId->value)->get();

        return $eloquentReviews->map(fn($review) => $this->toDomainEntity($review))->toArray();
    }

    public function findByClinicId(ClinicId $clinicId): array
    {
        $eloquentReviews = EloquentReview::where('clinic_id', $clinicId->value)->get();

        return $eloquentReviews->map(fn($review) => $this->toDomainEntity($review))->toArray();
    }

    public function findByPatientAndDoctor(UserId $patientId, UserId $doctorId): ?Review
    {
        $eloquentReview = EloquentReview::where('patient_id', $patientId->value)
            ->where('doctor_id', $doctorId->value)
            ->first();

        if (!$eloquentReview) {
            return null;
        }

        return $this->toDomainEntity($eloquentReview);
    }

    public function save(Review $review): Review
    {
        $eloquentReview = $review->getId() > 0
            ? EloquentReview::find($review->getId())
            : new EloquentReview();

        $eloquentReview->fill([
            'patient_id' => $review->getPatientId()->value,
            'doctor_id' => $review->getDoctorId()->value,
            'clinic_id' => $review->getClinicId()?->value,
            'rating' => $review->getRating()->toStars(),
            'answers_json' => $review->getAnswers(),
            'comment' => $review->getComment(),
            'published_at' => $review->getPublishedAt(),
        ]);

        $eloquentReview->save();

        return $this->toDomainEntity($eloquentReview);
    }

    public function delete(int $id): bool
    {
        $eloquentReview = EloquentReview::find($id);

        if (!$eloquentReview) {
            return false;
        }

        return $eloquentReview->delete();
    }

    public function getPublishedReviews(): array
    {
        $eloquentReviews = EloquentReview::published()->get();

        return $eloquentReviews->map(fn($review) => $this->toDomainEntity($review))->toArray();
    }

    public function getDoctorAverageRating(UserId $doctorId): ?Rating
    {
        $average = EloquentReview::where('doctor_id', $doctorId->value)
            ->published()
            ->avg('rating');

        return $average ? new Rating((float) $average) : null;
    }

    public function getClinicAverageRating(ClinicId $clinicId): ?Rating
    {
        $average = EloquentReview::where('clinic_id', $clinicId->value)
            ->published()
            ->avg('rating');

        return $average ? new Rating((float) $average) : null;
    }

    public function getRecentReviews(int $limit = 10): array
    {
        $eloquentReviews = EloquentReview::recent()
            ->published()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $eloquentReviews->map(fn($review) => $this->toDomainEntity($review))->toArray();
    }

    private function toDomainEntity(EloquentReview $eloquentReview): Review
    {
        return new Review(
            id: $eloquentReview->id,
            patientId: new UserId($eloquentReview->patient_id),
            doctorId: new UserId($eloquentReview->doctor_id),
            clinicId: $eloquentReview->clinic_id ? new ClinicId($eloquentReview->clinic_id) : null,
            rating: Rating::fromInteger($eloquentReview->rating),
            answers: $eloquentReview->answers_json,
            comment: $eloquentReview->comment,
            publishedAt: $eloquentReview->published_at?->toDateTime(),
            createdAt: $eloquentReview->created_at?->toDateTime(),
            updatedAt: $eloquentReview->updated_at?->toDateTime()
        );
    }
}
