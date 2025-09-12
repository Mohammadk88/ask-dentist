<?php

namespace App\Livewire;

use App\Infrastructure\Models\Doctor;
use App\Infrastructure\Models\Service;
use App\Infrastructure\Models\TreatmentRequest;
use App\Infrastructure\Models\TreatmentPlan;
use App\Infrastructure\Models\Pricing;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DentalPlanBuilder extends Component
{
    // Component State
    public $treatmentRequestId;
    public $treatmentRequest;
    public $doctor;
    public $clinic;
    
    // Tooth Selection State
    public array $selectedTeeth = [];
    
    // Available Data
    public $toothSpecificServices = [];
    public $generalServices = [];
    public $pricingData = [];
    
    // Plan Building State
    public array $planItems = [];
    public array $currentItem = [
        'stage' => 1,
        'service_id' => '',
        'tooth_codes' => [],
        'quantity' => 1,
        'unit_price' => 0,
        'total_price' => 0,
        'duration_days' => 0,
        'notes' => ''
    ];
    
    // Plan Details
    #[Rule('required|string|max:255')]
    public string $planTitle = '';
    
    #[Rule('required|string')]
    public string $planDescription = '';
    
    #[Rule('required|string')]
    public string $diagnosis = '';
    
    #[Rule('numeric|min:0')]
    public float $discountAmount = 0;
    
    #[Rule('string|nullable')]
    public string $discountReason = '';
    
    // Computed Totals
    public float $subtotal = 0;
    public float $finalTotal = 0;
    public int $totalDays = 0;
    public int $totalVisits = 0;
    
    // UI State
    public bool $isDraft = true;
    public string $activeTab = 'teeth'; // teeth, services, stages, summary
    
    public function mount($treatmentRequestId)
    {
        $this->treatmentRequestId = $treatmentRequestId;
        
        // Load treatment request
        $this->treatmentRequest = TreatmentRequest::with('patient.user')->findOrFail($treatmentRequestId);
        
        // Get authenticated doctor
        $this->doctor = Auth::user()->doctor;
        $this->clinic = $this->doctor->doctorClinics()->with('clinic')->first()?->clinic;
        
        if (!$this->clinic) {
            abort(403, 'Doctor must be associated with a clinic');
        }
        
        // Load available services and pricing
        $this->loadServicesAndPricing();
        
        // Initialize autosave
        $this->loadDraftFromStorage();
    }
    
    public function render()
    {
        return view('livewire.dental-plan-builder', [
            'fdiTeeth' => $this->getFdiTeethStructure(),
        ]);
    }
    
    // Tooth Selection Methods
    public function toggleTooth($toothCode)
    {
        if (in_array($toothCode, $this->selectedTeeth)) {
            $this->selectedTeeth = array_values(array_diff($this->selectedTeeth, [$toothCode]));
        } else {
            $this->selectedTeeth[] = $toothCode;
        }
        
        $this->dispatch('teeth-updated', $this->selectedTeeth);
        $this->saveDraftToStorage();
    }
    
    public function clearSelectedTeeth()
    {
        $this->selectedTeeth = [];
        $this->dispatch('teeth-updated', $this->selectedTeeth);
        $this->saveDraftToStorage();
    }
    
    public function selectAllTeeth()
    {
        $this->selectedTeeth = collect($this->getFdiTeethStructure())
            ->flatten(1)
            ->pluck('code')
            ->toArray();
        $this->dispatch('teeth-updated', $this->selectedTeeth);
        $this->saveDraftToStorage();
    }
    
    // Service Selection Methods
    public function selectService($serviceId)
    {
        $service = Service::find($serviceId);
        if (!$service) return;
        
        $this->currentItem['service_id'] = $serviceId;
        $this->currentItem['duration_days'] = ceil($service->duration_minutes / (8 * 60)); // Assume 8-hour workdays
        
        // Set tooth codes based on service type
        if ($service->is_tooth_specific) {
            $this->currentItem['tooth_codes'] = $this->selectedTeeth;
        } else {
            $this->currentItem['tooth_codes'] = [];
        }
        
        $this->calculateItemPrice();
        $this->activeTab = 'stages';
    }
    
    public function calculateItemPrice()
    {
        $serviceId = $this->currentItem['service_id'];
        $toothCodes = $this->currentItem['tooth_codes'];
        $quantity = $this->currentItem['quantity'];
        
        if (!$serviceId) {
            $this->currentItem['unit_price'] = 0;
            $this->currentItem['total_price'] = 0;
            return;
        }
        
        $service = Service::find($serviceId);
        $pricing = $this->getPricingForService($serviceId);
        
        if (!$pricing) {
            $this->currentItem['unit_price'] = 0;
            $this->currentItem['total_price'] = 0;
            return;
        }
        
        if ($service->is_tooth_specific && !empty($toothCodes)) {
            $toothPrice = $pricing->calculateToothPrice($toothCodes);
            $this->currentItem['unit_price'] = $toothPrice / count($toothCodes);
            $this->currentItem['total_price'] = $toothPrice * $quantity;
        } else {
            $this->currentItem['unit_price'] = $pricing->final_price;
            $this->currentItem['total_price'] = $pricing->final_price * $quantity;
        }
    }
    
    // Plan Building Methods
    public function addPlanItem()
    {
        $this->validate([
            'currentItem.service_id' => 'required|exists:services,id',
            'currentItem.quantity' => 'required|integer|min:1',
            'currentItem.stage' => 'required|integer|min:1',
        ]);
        
        if (empty($this->currentItem['service_id'])) {
            $this->addError('currentItem.service_id', 'Please select a service');
            return;
        }
        
        $service = Service::find($this->currentItem['service_id']);
        
        // Validate tooth selection for tooth-specific services
        if ($service->is_tooth_specific && empty($this->currentItem['tooth_codes'])) {
            $this->addError('currentItem.tooth_codes', 'Please select teeth for this service');
            return;
        }
        
        $this->planItems[] = [
            'id' => uniqid(),
            'stage' => $this->currentItem['stage'],
            'service_id' => $this->currentItem['service_id'],
            'service_name' => $service->name,
            'tooth_codes' => $this->currentItem['tooth_codes'],
            'quantity' => $this->currentItem['quantity'],
            'unit_price' => $this->currentItem['unit_price'],
            'total_price' => $this->currentItem['total_price'],
            'duration_days' => $this->currentItem['duration_days'],
            'notes' => $this->currentItem['notes'],
        ];
        
        // Reset current item
        $this->resetCurrentItem();
        $this->calculateTotals();
        $this->saveDraftToStorage();
        
        $this->dispatch('item-added');
    }
    
    public function removePlanItem($itemId)
    {
        $this->planItems = array_filter($this->planItems, fn($item) => $item['id'] !== $itemId);
        $this->planItems = array_values($this->planItems); // Re-index
        
        $this->calculateTotals();
        $this->saveDraftToStorage();
    }
    
    public function updatePlanItem($itemId, $field, $value)
    {
        foreach ($this->planItems as &$item) {
            if ($item['id'] === $itemId) {
                $item[$field] = $value;
                
                // Recalculate prices if quantity changed
                if ($field === 'quantity') {
                    $pricing = $this->getPricingForService($item['service_id']);
                    if ($pricing) {
                        $service = Service::find($item['service_id']);
                        if ($service->is_tooth_specific && !empty($item['tooth_codes'])) {
                            $toothPrice = $pricing->calculateToothPrice($item['tooth_codes']);
                            $item['total_price'] = $toothPrice * $value;
                        } else {
                            $item['total_price'] = $pricing->final_price * $value;
                        }
                    }
                }
                break;
            }
        }
        
        $this->calculateTotals();
        $this->saveDraftToStorage();
    }
    
    // Plan Management Methods
    public function saveDraft()
    {
        $this->validate();
        
        if (empty($this->planItems)) {
            $this->addError('planItems', 'Please add at least one treatment item');
            return;
        }
        
        $plan = $this->saveOrUpdatePlan('draft');
        
        $this->dispatch('draft-saved', $plan->id);
        session()->flash('success', 'Draft saved successfully');
    }
    
    public function submitPlan()
    {
        $this->validate();
        
        if (empty($this->planItems)) {
            $this->addError('planItems', 'Please add at least one treatment item');
            return;
        }
        
        $plan = $this->saveOrUpdatePlan('submitted');
        
        $this->clearDraftFromStorage();
        $this->dispatch('plan-submitted', $plan->id);
        
        return redirect()->route('doctor.plans.show', $plan->id)
            ->with('success', 'Treatment plan submitted successfully');
    }
    
    // Helper Methods
    private function loadServicesAndPricing()
    {
        $this->toothSpecificServices = Service::toothSpecific()
            ->orderBy('category')
            ->orderBy('name')
            ->get();
            
        $this->generalServices = Service::where('is_tooth_specific', false)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        
        // Load pricing for this clinic
        $this->pricingData = Pricing::where('clinic_id', $this->clinic->id)
            ->with('service')
            ->valid()
            ->get()
            ->keyBy('service_id');
    }
    
    private function getPricingForService($serviceId)
    {
        return $this->pricingData->get($serviceId);
    }
    
    private function resetCurrentItem()
    {
        $this->currentItem = [
            'stage' => $this->getNextStage(),
            'service_id' => '',
            'tooth_codes' => [],
            'quantity' => 1,
            'unit_price' => 0,
            'total_price' => 0,
            'duration_days' => 0,
            'notes' => ''
        ];
    }
    
    private function getNextStage()
    {
        if (empty($this->planItems)) {
            return 1;
        }
        
        return max(array_column($this->planItems, 'stage')) + 1;
    }
    
    private function calculateTotals()
    {
        $this->subtotal = array_sum(array_column($this->planItems, 'total_price'));
        $this->finalTotal = $this->subtotal - $this->discountAmount;
        $this->totalDays = array_sum(array_column($this->planItems, 'duration_days'));
        
        // Calculate visits (assuming each stage is a visit)
        $stages = array_unique(array_column($this->planItems, 'stage'));
        $this->totalVisits = count($stages);
    }
    
    private function saveOrUpdatePlan($status)
    {
        return DB::transaction(function () use ($status) {
            return TreatmentPlan::create([
                'treatment_request_id' => $this->treatmentRequestId,
                'doctor_id' => $this->doctor->id,
                'clinic_id' => $this->clinic->id,
                'title' => $this->planTitle,
                'description' => $this->planDescription,
                'diagnosis' => $this->diagnosis,
                'services' => $this->planItems,
                'total_cost' => $this->finalTotal,
                'currency' => 'USD', // TODO: Get from clinic settings
                'estimated_duration_days' => $this->totalDays,
                'number_of_visits' => $this->totalVisits,
                'timeline' => $this->generateTimeline(),
                'status' => $status,
                'expires_at' => now()->addDays(30),
                'notes' => "Discount applied: {$this->discountAmount} ({$this->discountReason})",
            ]);
        });
    }
    
    private function generateTimeline()
    {
        $timeline = [];
        $stages = collect($this->planItems)->groupBy('stage');
        
        foreach ($stages as $stage => $items) {
            $timeline["stage_{$stage}"] = [
                'stage' => $stage,
                'services' => $items->pluck('service_name')->toArray(),
                'duration_days' => $items->sum('duration_days'),
                'estimated_cost' => $items->sum('total_price'),
            ];
        }
        
        return $timeline;
    }
    
    private function getFdiTeethStructure()
    {
        return [
            'upper' => [
                ['code' => '18', 'name' => 'Upper Right 3rd Molar'],
                ['code' => '17', 'name' => 'Upper Right 2nd Molar'],
                ['code' => '16', 'name' => 'Upper Right 1st Molar'],
                ['code' => '15', 'name' => 'Upper Right 2nd Premolar'],
                ['code' => '14', 'name' => 'Upper Right 1st Premolar'],
                ['code' => '13', 'name' => 'Upper Right Canine'],
                ['code' => '12', 'name' => 'Upper Right Lateral Incisor'],
                ['code' => '11', 'name' => 'Upper Right Central Incisor'],
                ['code' => '21', 'name' => 'Upper Left Central Incisor'],
                ['code' => '22', 'name' => 'Upper Left Lateral Incisor'],
                ['code' => '23', 'name' => 'Upper Left Canine'],
                ['code' => '24', 'name' => 'Upper Left 1st Premolar'],
                ['code' => '25', 'name' => 'Upper Left 2nd Premolar'],
                ['code' => '26', 'name' => 'Upper Left 1st Molar'],
                ['code' => '27', 'name' => 'Upper Left 2nd Molar'],
                ['code' => '28', 'name' => 'Upper Left 3rd Molar'],
            ],
            'lower' => [
                ['code' => '48', 'name' => 'Lower Right 3rd Molar'],
                ['code' => '47', 'name' => 'Lower Right 2nd Molar'],
                ['code' => '46', 'name' => 'Lower Right 1st Molar'],
                ['code' => '45', 'name' => 'Lower Right 2nd Premolar'],
                ['code' => '44', 'name' => 'Lower Right 1st Premolar'],
                ['code' => '43', 'name' => 'Lower Right Canine'],
                ['code' => '42', 'name' => 'Lower Right Lateral Incisor'],
                ['code' => '41', 'name' => 'Lower Right Central Incisor'],
                ['code' => '31', 'name' => 'Lower Left Central Incisor'],
                ['code' => '32', 'name' => 'Lower Left Lateral Incisor'],
                ['code' => '33', 'name' => 'Lower Left Canine'],
                ['code' => '34', 'name' => 'Lower Left 1st Premolar'],
                ['code' => '35', 'name' => 'Lower Left 2nd Premolar'],
                ['code' => '36', 'name' => 'Lower Left 1st Molar'],
                ['code' => '37', 'name' => 'Lower Left 2nd Molar'],
                ['code' => '38', 'name' => 'Lower Left 3rd Molar'],
            ],
        ];
    }
    
    // Autosave methods
    private function saveDraftToStorage()
    {
        $this->dispatch('save-to-storage', [
            'selectedTeeth' => $this->selectedTeeth,
            'planItems' => $this->planItems,
            'planTitle' => $this->planTitle,
            'planDescription' => $this->planDescription,
            'diagnosis' => $this->diagnosis,
            'discountAmount' => $this->discountAmount,
            'discountReason' => $this->discountReason,
        ]);
    }
    
    private function loadDraftFromStorage()
    {
        // This will be handled by Alpine.js on the frontend
        $this->dispatch('load-from-storage');
    }
    
    private function clearDraftFromStorage()
    {
        $this->dispatch('clear-storage');
    }
    
    // Lifecycle hooks
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['currentItem.quantity', 'currentItem.tooth_codes'])) {
            $this->calculateItemPrice();
        }
        
        if (in_array($propertyName, ['discountAmount'])) {
            $this->calculateTotals();
        }
        
        // Auto-save on most updates
        if (!str_starts_with($propertyName, 'currentItem')) {
            $this->saveDraftToStorage();
        }
    }
}
