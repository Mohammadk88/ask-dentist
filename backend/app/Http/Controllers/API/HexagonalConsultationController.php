<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\UseCases\CreateConsultationUseCase;
use App\Application\UseCases\SendMessageUseCase;
use App\Application\DTOs\CreateConsultationDTO;
use App\Application\DTOs\SendMessageDTO;
use App\Domain\Repositories\ConsultationRepositoryInterface;
use App\Domain\Repositories\DoctorRepositoryInterface;
use App\Domain\Repositories\PatientRepositoryInterface;
use App\Domain\ValueObjects\ConsultationId;
use App\Domain\ValueObjects\DoctorId;
use App\Domain\ValueObjects\PatientId;

/**
 * @group Consultations (Hexagonal)
 *
 * Clean architecture implementation for consultation management.
 * Demonstrates complete separation from Eloquent models - uses only:
 * - Domain entities and value objects
 * - Application use cases and DTOs
 * - Repository interfaces (no direct model access)
 */
class HexagonalConsultationController extends Controller
{
    public function __construct(
        private CreateConsultationUseCase $createConsultationUseCase,
        private SendMessageUseCase $sendMessageUseCase,
        private ConsultationRepositoryInterface $consultationRepository,
        private DoctorRepositoryInterface $doctorRepository,
        private PatientRepositoryInterface $patientRepository
    ) {}

    /**
     * Create a new consultation
     *
     * @bodyParam patient_id integer required The patient's ID. Example: 1
     * @bodyParam doctor_id integer required The doctor's ID. Example: 1
     * @bodyParam chief_complaint string required Primary reason for consultation. Example: Tooth pain
     * @bodyParam type string required Type of consultation. Example: regular
     * @bodyParam scheduled_at string required Scheduled date and time. Example: 2025-09-10 14:00:00
     * @bodyParam symptoms array optional Array of symptoms. Example: ["pain", "swelling"]
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'chief_complaint' => 'required|string|max:500',
            'type' => 'required|string|in:regular,emergency,followup',
            'scheduled_at' => 'required|date|after:now',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'string|max:100',
        ]);

        try {
            $dto = new CreateConsultationDTO(
                patientId: $request->patient_id,
                doctorId: $request->doctor_id,
                chiefComplaint: $request->chief_complaint,
                type: $request->type,
                scheduledAt: $request->scheduled_at,
                symptoms: $request->symptoms ?? []
            );

            $consultation = $this->createConsultationUseCase->execute($dto);

            return response()->json([
                'message' => 'Consultation created successfully',
                'consultation' => [
                    'id' => $consultation->getId()->getValue(),
                    'patient_id' => $consultation->getPatientId()->getValue(),
                    'doctor_id' => $consultation->getDoctorId()->getValue(),
                    'chief_complaint' => $consultation->getChiefComplaint(),
                    'status' => $consultation->getStatus(),
                    'type' => $consultation->getType(),
                    'fee' => $consultation->getFee()->format(),
                    'scheduled_at' => $consultation->getScheduledAt()->format('Y-m-d H:i:s'),
                    'symptoms' => $consultation->getSymptoms(),
                ]
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create consultation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get consultation details
     *
     * @urlParam id integer required The consultation ID. Example: 1
     */
    public function show(int $id): JsonResponse
    {
        try {
            $consultationId = new ConsultationId($id);
            $consultation = $this->consultationRepository->findById($consultationId);

            if (!$consultation) {
                return response()->json([
                    'message' => 'Consultation not found'
                ], 404);
            }

            return response()->json([
                'consultation' => [
                    'id' => $consultation->getId()->getValue(),
                    'patient_id' => $consultation->getPatientId()->getValue(),
                    'doctor_id' => $consultation->getDoctorId()->getValue(),
                    'chief_complaint' => $consultation->getChiefComplaint(),
                    'status' => $consultation->getStatus(),
                    'type' => $consultation->getType(),
                    'fee' => $consultation->getFee()->format(),
                    'scheduled_at' => $consultation->getScheduledAt()->format('Y-m-d H:i:s'),
                    'started_at' => $consultation->getStartedAt()?->format('Y-m-d H:i:s'),
                    'completed_at' => $consultation->getCompletedAt()?->format('Y-m-d H:i:s'),
                    'diagnosis' => $consultation->getDiagnosis(),
                    'treatment_plan' => $consultation->getTreatmentPlan(),
                    'notes' => $consultation->getNotes(),
                    'symptoms' => $consultation->getSymptoms(),
                    'attachments' => $consultation->getAttachments(),
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Invalid consultation ID'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve consultation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get doctor's consultations
     *
     * @urlParam doctorId integer required The doctor's ID. Example: 1
     */
    public function getDoctorConsultations(int $doctorId): JsonResponse
    {
        try {
            $doctorIdVO = new DoctorId($doctorId);

            // Verify doctor exists
            if (!$this->doctorRepository->exists($doctorIdVO)) {
                return response()->json([
                    'message' => 'Doctor not found'
                ], 404);
            }

            $consultations = $this->consultationRepository->getConsultationsByDoctor($doctorIdVO);

            $consultationData = array_map(function ($consultation) {
                return [
                    'id' => $consultation->getId()->getValue(),
                    'patient_id' => $consultation->getPatientId()->getValue(),
                    'chief_complaint' => $consultation->getChiefComplaint(),
                    'status' => $consultation->getStatus(),
                    'type' => $consultation->getType(),
                    'scheduled_at' => $consultation->getScheduledAt()->format('Y-m-d H:i:s'),
                    'fee' => $consultation->getFee()->format(),
                ];
            }, $consultations);

            return response()->json([
                'consultations' => $consultationData,
                'total' => count($consultationData)
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Invalid doctor ID'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve consultations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get patient's consultations
     *
     * @urlParam patientId integer required The patient's ID. Example: 1
     */
    public function getPatientConsultations(int $patientId): JsonResponse
    {
        try {
            $patientIdVO = new PatientId($patientId);

            // Verify patient exists
            if (!$this->patientRepository->exists($patientIdVO)) {
                return response()->json([
                    'message' => 'Patient not found'
                ], 404);
            }

            $consultations = $this->consultationRepository->getConsultationsByPatient($patientIdVO);

            $consultationData = array_map(function ($consultation) {
                return [
                    'id' => $consultation->getId()->getValue(),
                    'doctor_id' => $consultation->getDoctorId()->getValue(),
                    'chief_complaint' => $consultation->getChiefComplaint(),
                    'status' => $consultation->getStatus(),
                    'type' => $consultation->getType(),
                    'scheduled_at' => $consultation->getScheduledAt()->format('Y-m-d H:i:s'),
                    'fee' => $consultation->getFee()->format(),
                ];
            }, $consultations);

            return response()->json([
                'consultations' => $consultationData,
                'total' => count($consultationData)
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Invalid patient ID'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve consultations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send message in consultation
     *
     * @urlParam id integer required The consultation ID. Example: 1
     * @bodyParam sender_id integer required The sender's user ID. Example: 1
     * @bodyParam content string required Message content. Example: Hello, how are you feeling?
     * @bodyParam type string optional Message type. Example: text
     * @bodyParam attachments array optional Array of attachment paths. Example: ["path/to/image.jpg"]
     */
    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'sender_id' => 'required|integer|exists:users,id',
            'content' => 'required|string|max:1000',
            'type' => 'nullable|string|in:text,image,file,system',
            'attachments' => 'nullable|array',
            'attachments.*' => 'string|max:255',
        ]);

        try {
            $dto = new SendMessageDTO(
                consultationId: $id,
                senderId: $request->sender_id,
                content: $request->content,
                type: $request->type ?? 'text',
                attachments: $request->attachments ?? []
            );

            $message = $this->sendMessageUseCase->execute($dto);

            return response()->json([
                'message' => 'Message sent successfully',
                'message_data' => [
                    'id' => $message->getId()->getValue(),
                    'consultation_id' => $message->getConsultationId()->getValue(),
                    'sender_id' => $message->getSenderId()->getValue(),
                    'content' => $message->getContent(),
                    'type' => $message->getType(),
                    'sent_at' => $message->getSentAt()->format('Y-m-d H:i:s'),
                    'attachments' => $message->getAttachments(),
                ]
            ], 201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
