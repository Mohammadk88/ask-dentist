<div x-data="dentalPlanBuilder()" 
     x-init="initComponent()"
     class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-lg">
     
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Treatment Plan</h1>
        <p class="text-gray-600">
            Patient: {{ $treatmentRequest->patient->user->name }} | 
            Request: {{ $treatmentRequest->title }}
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" role="tablist">
            <button @click="$wire.activeTab = 'teeth'" 
                    :class="$wire.activeTab === 'teeth' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none focus:text-blue-600"
                    role="tab">
                1. Select Teeth
            </button>
            <button @click="$wire.activeTab = 'services'" 
                    :class="$wire.activeTab === 'services' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none focus:text-blue-600"
                    role="tab">
                2. Add Services
            </button>
            <button @click="$wire.activeTab = 'stages'" 
                    :class="$wire.activeTab === 'stages' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none focus:text-blue-600"
                    role="tab">
                3. Build Plan
            </button>
            <button @click="$wire.activeTab = 'summary'" 
                    :class="$wire.activeTab === 'summary' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none focus:text-blue-600"
                    role="tab">
                4. Review & Submit
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        
        <!-- Teeth Selection Tab -->
        <div x-show="$wire.activeTab === 'teeth'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- SVG Dental Chart -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Select Teeth (FDI Numbering)</h3>
                    
                    <!-- Tooth Selection Controls -->
                    <div class="mb-4 flex gap-2 flex-wrap">
                        <button wire:click="selectAllTeeth" 
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Select All
                        </button>
                        <button wire:click="clearSelectedTeeth" 
                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Clear All
                        </button>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-md text-sm">
                            Selected: {{ count($selectedTeeth) }} teeth
                        </span>
                    </div>

                    <!-- SVG Dental Chart -->
                    <div class="dental-chart bg-white p-4 rounded border">
                        <svg viewBox="0 0 800 600" class="w-full h-auto max-h-96" role="img" aria-label="Dental chart for tooth selection">
                            <!-- Upper Arch -->
                            <g class="upper-arch">
                                <text x="400" y="30" text-anchor="middle" class="text-sm font-semibold fill-gray-700">Upper Arch</text>
                                
                                <!-- Upper Right -->
                                @foreach($fdiTeeth['upper'] as $index => $tooth)
                                    @if($index < 8)
                                        <g class="tooth-group" data-tooth="{{ $tooth['code'] }}">
                                            <path d="M {{ 400 - ($index * 45) }} 60 
                                                   Q {{ 400 - ($index * 45) - 20 }} 40 
                                                     {{ 400 - ($index * 45) - 40 }} 60
                                                   Q {{ 400 - ($index * 45) - 35 }} 90
                                                     {{ 400 - ($index * 45) - 20 }} 100
                                                   Q {{ 400 - ($index * 45) }} 90
                                                     {{ 400 - ($index * 45) }} 60 Z"
                                                  :class="$wire.selectedTeeth.includes('{{ $tooth['code'] }}') ? 'fill-blue-500 stroke-blue-700' : 'fill-white stroke-gray-400 hover:fill-blue-100'"
                                                  class="tooth cursor-pointer stroke-2 transition-colors duration-200"
                                                  wire:click="toggleTooth('{{ $tooth['code'] }}')"
                                                  role="button"
                                                  tabindex="0"
                                                  :aria-pressed="$wire.selectedTeeth.includes('{{ $tooth['code'] }}')"
                                                  aria-label="Tooth {{ $tooth['code'] }} - {{ $tooth['name'] }}"
                                                  @keydown.enter="$wire.toggleTooth('{{ $tooth['code'] }}')"
                                                  @keydown.space.prevent="$wire.toggleTooth('{{ $tooth['code'] }}')">
                                            </path>
                                            <text x="{{ 400 - ($index * 45) - 20 }}" y="85" 
                                                  text-anchor="middle" 
                                                  class="text-xs fill-gray-700 pointer-events-none select-none">
                                                {{ $tooth['code'] }}
                                            </text>
                                        </g>
                                    @endif
                                @endforeach
                                
                                <!-- Upper Left -->
                                @foreach($fdiTeeth['upper'] as $index => $tooth)
                                    @if($index >= 8)
                                        <g class="tooth-group" data-tooth="{{ $tooth['code'] }}">
                                            <path d="M {{ 400 + (($index - 8) * 45) }} 60 
                                                   Q {{ 400 + (($index - 8) * 45) + 20 }} 40 
                                                     {{ 400 + (($index - 8) * 45) + 40 }} 60
                                                   Q {{ 400 + (($index - 8) * 45) + 35 }} 90
                                                     {{ 400 + (($index - 8) * 45) + 20 }} 100
                                                   Q {{ 400 + (($index - 8) * 45) }} 90
                                                     {{ 400 + (($index - 8) * 45) }} 60 Z"
                                                  :class="$wire.selectedTeeth.includes('{{ $tooth['code'] }}') ? 'fill-blue-500 stroke-blue-700' : 'fill-white stroke-gray-400 hover:fill-blue-100'"
                                                  class="tooth cursor-pointer stroke-2 transition-colors duration-200"
                                                  wire:click="toggleTooth('{{ $tooth['code'] }}')"
                                                  role="button"
                                                  tabindex="0"
                                                  :aria-pressed="$wire.selectedTeeth.includes('{{ $tooth['code'] }}')"
                                                  aria-label="Tooth {{ $tooth['code'] }} - {{ $tooth['name'] }}"
                                                  @keydown.enter="$wire.toggleTooth('{{ $tooth['code'] }}')"
                                                  @keydown.space.prevent="$wire.toggleTooth('{{ $tooth['code'] }}')">
                                            </path>
                                            <text x="{{ 400 + (($index - 8) * 45) + 20 }}" y="85" 
                                                  text-anchor="middle" 
                                                  class="text-xs fill-gray-700 pointer-events-none select-none">
                                                {{ $tooth['code'] }}
                                            </text>
                                        </g>
                                    @endif
                                @endforeach
                            </g>

                            <!-- Lower Arch -->
                            <g class="lower-arch">
                                <text x="400" y="180" text-anchor="middle" class="text-sm font-semibold fill-gray-700">Lower Arch</text>
                                
                                <!-- Lower Right -->
                                @foreach($fdiTeeth['lower'] as $index => $tooth)
                                    @if($index < 8)
                                        <g class="tooth-group" data-tooth="{{ $tooth['code'] }}">
                                            <path d="M {{ 400 - ($index * 45) }} 200 
                                                   Q {{ 400 - ($index * 45) - 20 }} 180
                                                     {{ 400 - ($index * 45) - 40 }} 200
                                                   Q {{ 400 - ($index * 45) - 35 }} 230
                                                     {{ 400 - ($index * 45) - 20 }} 240
                                                   Q {{ 400 - ($index * 45) }} 230
                                                     {{ 400 - ($index * 45) }} 200 Z"
                                                  :class="$wire.selectedTeeth.includes('{{ $tooth['code'] }}') ? 'fill-blue-500 stroke-blue-700' : 'fill-white stroke-gray-400 hover:fill-blue-100'"
                                                  class="tooth cursor-pointer stroke-2 transition-colors duration-200"
                                                  wire:click="toggleTooth('{{ $tooth['code'] }}')"
                                                  role="button"
                                                  tabindex="0"
                                                  :aria-pressed="$wire.selectedTeeth.includes('{{ $tooth['code'] }}')"
                                                  aria-label="Tooth {{ $tooth['code'] }} - {{ $tooth['name'] }}"
                                                  @keydown.enter="$wire.toggleTooth('{{ $tooth['code'] }}')"
                                                  @keydown.space.prevent="$wire.toggleTooth('{{ $tooth['code'] }}')">
                                            </path>
                                            <text x="{{ 400 - ($index * 45) - 20 }}" y="225" 
                                                  text-anchor="middle" 
                                                  class="text-xs fill-gray-700 pointer-events-none select-none">
                                                {{ $tooth['code'] }}
                                            </text>
                                        </g>
                                    @endif
                                @endforeach
                                
                                <!-- Lower Left -->
                                @foreach($fdiTeeth['lower'] as $index => $tooth)
                                    @if($index >= 8)
                                        <g class="tooth-group" data-tooth="{{ $tooth['code'] }}">
                                            <path d="M {{ 400 + (($index - 8) * 45) }} 200 
                                                   Q {{ 400 + (($index - 8) * 45) + 20 }} 180 
                                                     {{ 400 + (($index - 8) * 45) + 40 }} 200
                                                   Q {{ 400 + (($index - 8) * 45) + 35 }} 230
                                                     {{ 400 + (($index - 8) * 45) + 20 }} 240
                                                   Q {{ 400 + (($index - 8) * 45) }} 230
                                                     {{ 400 + (($index - 8) * 45) }} 200 Z"
                                                  :class="$wire.selectedTeeth.includes('{{ $tooth['code'] }}') ? 'fill-blue-500 stroke-blue-700' : 'fill-white stroke-gray-400 hover:fill-blue-100'"
                                                  class="tooth cursor-pointer stroke-2 transition-colors duration-200"
                                                  wire:click="toggleTooth('{{ $tooth['code'] }}')"
                                                  role="button"
                                                  tabindex="0"
                                                  :aria-pressed="$wire.selectedTeeth.includes('{{ $tooth['code'] }}')"
                                                  aria-label="Tooth {{ $tooth['code'] }} - {{ $tooth['name'] }}"
                                                  @keydown.enter="$wire.toggleTooth('{{ $tooth['code'] }}')"
                                                  @keydown.space.prevent="$wire.toggleTooth('{{ $tooth['code'] }}')">
                                            </path>
                                            <text x="{{ 400 + (($index - 8) * 45) + 20 }}" y="225" 
                                                  text-anchor="middle" 
                                                  class="text-xs fill-gray-700 pointer-events-none select-none">
                                                {{ $tooth['code'] }}
                                            </text>
                                        </g>
                                    @endif
                                @endforeach
                            </g>

                            <!-- Legend -->
                            <g class="legend">
                                <text x="50" y="300" class="text-sm font-semibold fill-gray-700">Legend:</text>
                                <circle cx="60" cy="320" r="8" class="fill-white stroke-gray-400 stroke-2"></circle>
                                <text x="80" y="325" class="text-sm fill-gray-600">Available</text>
                                <circle cx="60" cy="340" r="8" class="fill-blue-500 stroke-blue-700 stroke-2"></circle>
                                <text x="80" y="345" class="text-sm fill-gray-600">Selected</text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Selected Teeth Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white border rounded-lg p-4 sticky top-4">
                    <h4 class="font-medium mb-3">Selected Teeth</h4>
                    @if(empty($selectedTeeth))
                        <p class="text-gray-500 text-sm">No teeth selected</p>
                    @else
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($selectedTeeth as $toothCode)
                                <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded px-2 py-1">
                                    <span class="text-sm font-medium text-blue-800">{{ $toothCode }}</span>
                                    <button wire:click="toggleTooth('{{ $toothCode }}')" 
                                            class="text-blue-600 hover:text-blue-800 focus:outline-none"
                                            aria-label="Remove tooth {{ $toothCode }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t">
                        <button @click="$wire.activeTab = 'services'" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Continue to Services
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Selection Tab -->
        <div x-show="$wire.activeTab === 'services'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Service Categories -->
            <div>
                <h3 class="text-lg font-medium mb-4">Available Services</h3>
                
                <!-- Tooth-Specific Services -->
                @if(!empty($toothSpecificServices))
                    <div class="mb-6">
                        <h4 class="font-medium text-green-700 mb-3">Tooth-Specific Services</h4>
                        <div class="space-y-2">
                            @foreach($toothSpecificServices as $service)
                                <div class="border rounded-lg p-3 hover:bg-gray-50 cursor-pointer transition-colors"
                                     wire:click="selectService('{{ $service->id }}')"
                                     role="button"
                                     tabindex="0"
                                     @keydown.enter="$wire.selectService('{{ $service->id }}')"
                                     aria-label="Select service: {{ $service->name }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900">{{ $service->name }}</h5>
                                            <p class="text-sm text-gray-600 mt-1">{{ $service->description }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                <span class="bg-{{ $service->category === 'emergency' ? 'red' : 'blue' }}-100 text-{{ $service->category === 'emergency' ? 'red' : 'blue' }}-800 px-2 py-1 rounded">
                                                    {{ ucfirst($service->category) }}
                                                </span>
                                                <span>⏱ {{ $service->formatted_duration }}</span>
                                                @if($service->requires_anesthesia)
                                                    <span>💉 Anesthesia</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            @if(isset($pricingData[$service->id]))
                                                <span class="font-medium text-green-600">
                                                    ${{ number_format($pricingData[$service->id]->final_price, 2) }}
                                                </span>
                                                <div class="text-xs text-gray-500">per tooth</div>
                                            @else
                                                <span class="text-gray-400">Price TBD</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- General Services -->
                @if(!empty($generalServices))
                    <div>
                        <h4 class="font-medium text-blue-700 mb-3">General Services</h4>
                        <div class="space-y-2">
                            @foreach($generalServices as $service)
                                <div class="border rounded-lg p-3 hover:bg-gray-50 cursor-pointer transition-colors"
                                     wire:click="selectService('{{ $service->id }}')"
                                     role="button"
                                     tabindex="0"
                                     @keydown.enter="$wire.selectService('{{ $service->id }}')"
                                     aria-label="Select service: {{ $service->name }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-gray-900">{{ $service->name }}</h5>
                                            <p class="text-sm text-gray-600 mt-1">{{ $service->description }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                <span class="bg-{{ $service->category === 'emergency' ? 'red' : 'blue' }}-100 text-{{ $service->category === 'emergency' ? 'red' : 'blue' }}-800 px-2 py-1 rounded">
                                                    {{ ucfirst($service->category) }}
                                                </span>
                                                <span>⏱ {{ $service->formatted_duration }}</span>
                                                @if($service->requires_anesthesia)
                                                    <span>💉 Anesthesia</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            @if(isset($pricingData[$service->id]))
                                                <span class="font-medium text-green-600">
                                                    ${{ number_format($pricingData[$service->id]->final_price, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">Price TBD</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Service Configuration -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium mb-4">Configure Service</h4>
                
                @if($currentItem['service_id'])
                    @php
                        $selectedService = collect($toothSpecificServices)->merge($generalServices)->firstWhere('id', $currentItem['service_id']);
                    @endphp
                    
                    <div class="bg-white rounded border p-4 mb-4">
                        <h5 class="font-medium">{{ $selectedService->name ?? 'Unknown Service' }}</h5>
                        <p class="text-sm text-gray-600 mt-1">{{ $selectedService->description ?? '' }}</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Stage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Stage</label>
                            <input type="number" min="1" wire:model.live="currentItem.stage" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Tooth Selection for Tooth-Specific Services -->
                        @if($selectedService && $selectedService->is_tooth_specific)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Teeth</label>
                                @if(empty($selectedTeeth))
                                    <p class="text-red-600 text-sm">Please select teeth first</p>
                                    <button @click="$wire.activeTab = 'teeth'" 
                                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                        ← Go back to select teeth
                                    </button>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($selectedTeeth as $toothCode)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $toothCode }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" min="1" wire:model.live="currentItem.quantity" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Price Information -->
                        <div class="bg-green-50 p-3 rounded">
                            <div class="text-sm">
                                <div class="flex justify-between">
                                    <span>Unit Price:</span>
                                    <span class="font-medium">${{ number_format($currentItem['unit_price'], 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Quantity:</span>
                                    <span>{{ $currentItem['quantity'] }}</span>
                                </div>
                                <div class="flex justify-between font-medium text-green-700 border-t pt-1 mt-1">
                                    <span>Total:</span>
                                    <span>${{ number_format($currentItem['total_price'], 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration (days)</label>
                            <input type="number" min="0" wire:model.live="currentItem.duration_days" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea wire:model.live="currentItem.notes" rows="3"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Additional notes for this service..."></textarea>
                        </div>

                        <!-- Add Button -->
                        <button wire:click="addPlanItem" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Add to Plan
                        </button>
                    </div>
                @else
                    <p class="text-gray-500">Select a service from the left to configure it</p>
                @endif
            </div>
        </div>

        <!-- Plan Building Tab -->
        <div x-show="$wire.activeTab === 'stages'" class="space-y-6">
            <!-- Plan Items -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Treatment Plan Items</h3>
                    <button @click="$wire.activeTab = 'services'" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add More Services
                    </button>
                </div>

                @if(empty($planItems))
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-4">No treatment items added yet</p>
                        <button @click="$wire.activeTab = 'services'" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Add Your First Service
                        </button>
                    </div>
                @else
                    <!-- Plan Items by Stage -->
                    @php
                        $itemsByStage = collect($planItems)->groupBy('stage');
                    @endphp
                    
                    @foreach($itemsByStage as $stage => $items)
                        <div class="border rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-lg mb-3 text-blue-700">Stage {{ $stage }}</h4>
                            
                            <div class="space-y-3">
                                @foreach($items as $index => $item)
                                    <div class="bg-white border rounded p-3">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h5 class="font-medium">{{ $item['service_name'] }}</h5>
                                                
                                                @if(!empty($item['tooth_codes']))
                                                    <div class="mt-1">
                                                        <span class="text-sm text-gray-600">Teeth: </span>
                                                        @foreach($item['tooth_codes'] as $toothCode)
                                                            <span class="bg-blue-100 text-blue-800 px-1 py-0.5 rounded text-xs mr-1">{{ $toothCode }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                
                                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                                    <span>Qty: {{ $item['quantity'] }}</span>
                                                    <span>Duration: {{ $item['duration_days'] }} days</span>
                                                    <span class="font-medium text-green-600">${{ number_format($item['total_price'], 2) }}</span>
                                                </div>
                                                
                                                @if($item['notes'])
                                                    <p class="text-sm text-gray-600 mt-2">{{ $item['notes'] }}</p>
                                                @endif
                                            </div>
                                            
                                            <div class="flex gap-2 ml-4">
                                                <button wire:click="removePlanItem('{{ $item['id'] }}')" 
                                                        class="text-red-600 hover:text-red-800 focus:outline-none"
                                                        aria-label="Remove item">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 112 0v2a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v2a1 1 0 11-2 0V9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Plan Totals -->
            @if(!empty($planItems))
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-3">Plan Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">${{ number_format($subtotal, 2) }}</div>
                            <div class="text-sm text-gray-600">Subtotal</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-700">{{ $totalDays }}</div>
                            <div class="text-sm text-gray-600">Total Days</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-700">{{ $totalVisits }}</div>
                            <div class="text-sm text-gray-600">Visits</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">${{ number_format($finalTotal, 2) }}</div>
                            <div class="text-sm text-gray-600">Final Total</div>
                        </div>
                    </div>
                </div>
                
                <!-- Continue Button -->
                <div class="text-center">
                    <button @click="$wire.activeTab = 'summary'" 
                            class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Continue to Summary
                    </button>
                </div>
            @endif
        </div>

        <!-- Summary & Submit Tab -->
        <div x-show="$wire.activeTab === 'summary'" class="space-y-6">
            <h3 class="text-lg font-medium">Review & Submit Treatment Plan</h3>

            <!-- Plan Details Form -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plan Title *</label>
                        <input type="text" wire:model.live="planTitle" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="e.g., Comprehensive Dental Restoration">
                        @error('planTitle') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea wire:model.live="planDescription" rows="4"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Describe the overall treatment approach..."></textarea>
                        @error('planDescription') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis *</label>
                        <textarea wire:model.live="diagnosis" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Clinical diagnosis and findings..."></textarea>
                        @error('diagnosis') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Discount Section -->
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium mb-3">Discount (Optional)</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" min="0" step="0.01" wire:model.live="discountAmount" 
                                           class="pl-7 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Discount Reason</label>
                                <input type="text" wire:model.live="discountReason" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="e.g., Insurance coverage, loyalty discount...">
                            </div>
                        </div>
                    </div>

                    <!-- Final Totals -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium mb-3">Final Cost Breakdown</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discountAmount > 0)
                                <div class="flex justify-between text-red-600">
                                    <span>Discount:</span>
                                    <span>-${{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif
                            <div class="border-t pt-2 flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span class="text-green-600">${{ number_format($finalTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Items Summary -->
            @if(!empty($planItems))
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-medium mb-4">Treatment Items Summary</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stage</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Teeth</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($planItems as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $item['stage'] }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $item['service_name'] }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            @if(!empty($item['tooth_codes']))
                                                {{ implode(', ', $item['tooth_codes']) }}
                                            @else
                                                <span class="text-gray-400">General</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $item['quantity'] }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $item['duration_days'] }} days</td>
                                        <td class="px-4 py-2 text-sm font-medium">${{ number_format($item['total_price'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end">
                <button wire:click="saveDraft" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save as Draft
                </button>
                <button wire:click="submitPlan" 
                        class="px-8 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Submit Plan
                </button>
            </div>
        </div>
    </div>

    <!-- Auto-save indicator -->
    <div x-show="autoSaving" 
         x-transition
         class="fixed bottom-4 right-4 bg-blue-100 text-blue-800 px-4 py-2 rounded-lg shadow">
        Auto-saving...
    </div>
</div>

<!-- Alpine.js Component Script -->
<script>
    function dentalPlanBuilder() {
        return {
            autoSaving: false,
            
            initComponent() {
                // Listen for Livewire events
                Livewire.on('teeth-updated', (teeth) => {
                    this.saveToStorage();
                });
                
                Livewire.on('item-added', () => {
                    this.saveToStorage();
                });
                
                Livewire.on('save-to-storage', (data) => {
                    this.saveToStorage(data);
                });
                
                Livewire.on('load-from-storage', () => {
                    this.loadFromStorage();
                });
                
                Livewire.on('clear-storage', () => {
                    this.clearStorage();
                });
                
                // Load existing draft
                this.loadFromStorage();
            },
            
            saveToStorage(data = null) {
                this.autoSaving = true;
                
                const storageKey = `dental_plan_draft_${@this.treatmentRequestId}`;
                const draftData = data || {
                    selectedTeeth: @this.selectedTeeth,
                    planItems: @this.planItems,
                    planTitle: @this.planTitle,
                    planDescription: @this.planDescription,
                    diagnosis: @this.diagnosis,
                    discountAmount: @this.discountAmount,
                    discountReason: @this.discountReason,
                    activeTab: @this.activeTab,
                    timestamp: Date.now()
                };
                
                try {
                    localStorage.setItem(storageKey, JSON.stringify(draftData));
                } catch (e) {
                    console.warn('Could not save to localStorage:', e);
                }
                
                setTimeout(() => {
                    this.autoSaving = false;
                }, 1000);
            },
            
            loadFromStorage() {
                const storageKey = `dental_plan_draft_${@this.treatmentRequestId}`;
                
                try {
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        const data = JSON.parse(saved);
                        
                        // Only load if saved within last 24 hours
                        if (Date.now() - data.timestamp < 24 * 60 * 60 * 1000) {
                            @this.selectedTeeth = data.selectedTeeth || [];
                            @this.planItems = data.planItems || [];
                            @this.planTitle = data.planTitle || '';
                            @this.planDescription = data.planDescription || '';
                            @this.diagnosis = data.diagnosis || '';
                            @this.discountAmount = data.discountAmount || 0;
                            @this.discountReason = data.discountReason || '';
                            
                            if (data.activeTab) {
                                @this.activeTab = data.activeTab;
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Could not load from localStorage:', e);
                }
            },
            
            clearStorage() {
                const storageKey = `dental_plan_draft_${@this.treatmentRequestId}`;
                try {
                    localStorage.removeItem(storageKey);
                } catch (e) {
                    console.warn('Could not clear localStorage:', e);
                }
            }
        }
    }
</script>
