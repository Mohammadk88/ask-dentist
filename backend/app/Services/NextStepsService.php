<?php

namespace App\Services;

use App\Models\TreatmentPlan;

class NextStepsService
{
    /**
     * Get next steps for patient after treatment plan acceptance
     */
    public function getNextSteps(TreatmentPlan $treatmentPlan): array
    {
        $clinic = $treatmentPlan->clinic;
        $isInternational = $this->isInternationalTreatment($treatmentPlan);
        
        return [
            'travel_booking' => $this->getTravelSteps($treatmentPlan, $isInternational),
            'accommodation' => $this->getAccommodationSteps($treatmentPlan, $isInternational),
            'insurance' => $this->getInsuranceSteps($treatmentPlan, $isInternational),
            'appointment_scheduling' => $this->getAppointmentSteps($treatmentPlan),
            'pre_treatment' => $this->getPreTreatmentSteps($treatmentPlan),
            'documentation' => $this->getDocumentationSteps($treatmentPlan, $isInternational),
            'payment' => $this->getPaymentSteps($treatmentPlan),
        ];
    }

    /**
     * Check if treatment requires international travel
     */
    private function isInternationalTreatment(TreatmentPlan $treatmentPlan): bool
    {
        // This would typically check patient's location vs clinic location
        // For now, we'll assume international if clinic is in Turkey (dental tourism)
        return in_array($treatmentPlan->clinic->country, ['TR', 'Turkey']);
    }

    /**
     * Get travel booking steps
     */
    private function getTravelSteps(TreatmentPlan $treatmentPlan, bool $isInternational): array
    {
        $clinic = $treatmentPlan->clinic;
        
        if (!$isInternational) {
            return [
                'required' => false,
                'message' => 'Local treatment - no travel booking required',
                'action' => null
            ];
        }

        return [
            'required' => true,
            'priority' => 'high',
            'message' => "Book your flight to {$clinic->city}, {$clinic->country}",
            'details' => [
                'destination_city' => $clinic->city,
                'destination_country' => $clinic->country,
                'estimated_duration' => $treatmentPlan->estimated_duration_days . ' days',
                'buffer_days' => 2, // Recommend arriving 1 day early, leaving 1 day late
                'nearest_airport' => $this->getNearestAirport($clinic->city),
            ],
            'tips' => [
                'Book flexible tickets in case treatment extends',
                'Consider travel insurance',
                'Check visa requirements if applicable'
            ],
            'action' => 'book_travel',
            'status' => 'pending'
        ];
    }

    /**
     * Get accommodation steps
     */
    private function getAccommodationSteps(TreatmentPlan $treatmentPlan, bool $isInternational): array
    {
        $clinic = $treatmentPlan->clinic;
        $duration = $treatmentPlan->estimated_duration_days;

        if (!$isInternational) {
            return [
                'required' => false,
                'message' => 'Local treatment - accommodation may not be necessary',
                'action' => null
            ];
        }

        return [
            'required' => true,
            'priority' => 'high',
            'message' => "Arrange accommodation near {$clinic->name}",
            'details' => [
                'duration' => $duration . ' nights (minimum)',
                'recommended_area' => $clinic->city,
                'clinic_address' => $clinic->address,
                'budget_options' => [
                    'budget' => 'Hostels and budget hotels ($20-50/night)',
                    'mid_range' => 'Mid-range hotels ($50-100/night)',
                    'luxury' => 'Luxury hotels and resorts ($100+/night)'
                ],
                'special_requirements' => [
                    'Close to clinic (max 30 minutes)',
                    'Good internet for follow-up consultations',
                    'Room service for recovery days'
                ]
            ],
            'recommendations' => [
                'Book cancellable reservations',
                'Ask clinic for partner hotel discounts',
                'Consider extended stay if complications arise'
            ],
            'action' => 'book_accommodation',
            'status' => 'pending'
        ];
    }

    /**
     * Get insurance steps
     */
    private function getInsuranceSteps(TreatmentPlan $treatmentPlan, bool $isInternational): array
    {
        return [
            'required' => $isInternational,
            'priority' => 'medium',
            'message' => 'Review insurance coverage for your treatment',
            'details' => [
                'travel_insurance' => $isInternational ? 'Recommended for international treatment' : 'Not required',
                'medical_insurance' => 'Check if dental treatment is covered',
                'coverage_amount' => 'Minimum $' . number_format($treatmentPlan->total_cost * 1.5),
                'emergency_coverage' => $isInternational ? 'Essential for complications' : 'Standard coverage'
            ],
            'checklist' => [
                'Verify current insurance covers dental procedures',
                'Check international coverage if traveling',
                'Consider additional travel medical insurance',
                'Review emergency evacuation coverage',
                'Get pre-authorization if required'
            ],
            'action' => 'review_insurance',
            'status' => 'pending'
        ];
    }

    /**
     * Get appointment scheduling steps
     */
    private function getAppointmentSteps(TreatmentPlan $treatmentPlan): array
    {
        return [
            'required' => true,
            'priority' => 'high',
            'message' => 'Schedule your treatment appointments',
            'details' => [
                'visits_required' => $treatmentPlan->number_of_visits,
                'estimated_duration' => $treatmentPlan->estimated_duration_days . ' days',
                'doctor' => $treatmentPlan->doctor->user->name,
                'clinic' => $treatmentPlan->clinic->name,
                'total_cost' => $treatmentPlan->total_cost . ' ' . $treatmentPlan->currency
            ],
            'next_steps' => [
                'Contact clinic to schedule first appointment',
                'Confirm availability for entire treatment period',
                'Discuss payment schedule',
                'Review pre-treatment requirements'
            ],
            'action' => 'schedule_appointments',
            'status' => 'pending'
        ];
    }

    /**
     * Get pre-treatment steps
     */
    private function getPreTreatmentSteps(TreatmentPlan $treatmentPlan): array
    {
        $hasInstructions = !empty($treatmentPlan->pre_treatment_instructions);

        return [
            'required' => $hasInstructions,
            'priority' => $hasInstructions ? 'high' : 'low',
            'message' => $hasInstructions ? 'Follow pre-treatment instructions' : 'No specific pre-treatment requirements',
            'instructions' => $treatmentPlan->pre_treatment_instructions,
            'general_guidelines' => [
                'Maintain good oral hygiene',
                'Avoid alcohol 48 hours before treatment',
                'Get adequate rest before procedures',
                'Inform doctor of any medications',
                'Bring updated medical history'
            ],
            'action' => $hasInstructions ? 'follow_instructions' : null,
            'status' => 'pending'
        ];
    }

    /**
     * Get documentation steps
     */
    private function getDocumentationSteps(TreatmentPlan $treatmentPlan, bool $isInternational): array
    {
        $documents = [
            'Passport (valid for 6+ months)',
            'Medical records and X-rays',
            'Insurance documentation',
            'Emergency contact information'
        ];

        if ($isInternational) {
            $documents = array_merge($documents, [
                'Visa (if required)',
                'Travel itinerary',
                'Hotel booking confirmation',
                'Return flight tickets'
            ]);
        }

        return [
            'required' => true,
            'priority' => 'medium',
            'message' => 'Prepare required documentation',
            'required_documents' => $documents,
            'recommendations' => [
                'Keep copies of all documents',
                'Store digital copies in cloud',
                'Share emergency contacts with clinic',
                'Translate medical records if needed'
            ],
            'action' => 'prepare_documents',
            'status' => 'pending'
        ];
    }

    /**
     * Get payment steps
     */
    private function getPaymentSteps(TreatmentPlan $treatmentPlan): array
    {
        return [
            'required' => true,
            'priority' => 'high',
            'message' => 'Arrange payment for treatment',
            'details' => [
                'total_amount' => $treatmentPlan->total_cost,
                'currency' => $treatmentPlan->currency,
                'payment_methods' => ['Credit Card', 'Bank Transfer', 'Payment Plan'],
                'deposit_required' => '30% of total cost',
                'final_payment' => 'Before treatment completion'
            ],
            'options' => [
                'full_payment' => 'Pay entire amount upfront (may get discount)',
                'deposit_balance' => 'Pay deposit now, balance before treatment',
                'installments' => 'Payment plan over multiple visits'
            ],
            'action' => 'arrange_payment',
            'status' => 'pending'
        ];
    }

    /**
     * Get nearest airport for a city (placeholder implementation)
     */
    private function getNearestAirport(string $city): string
    {
        $airports = [
            'Istanbul' => 'IST (Istanbul Airport)',
            'Ankara' => 'ESB (Esenboğa Airport)',
            'Izmir' => 'ADB (Adnan Menderes Airport)',
            'Antalya' => 'AYT (Antalya Airport)',
            'London' => 'LHR (Heathrow Airport)',
            'New York' => 'JFK (John F. Kennedy Airport)',
            'Dubai' => 'DXB (Dubai International)',
        ];

        return $airports[$city] ?? $city . ' Airport';
    }
}