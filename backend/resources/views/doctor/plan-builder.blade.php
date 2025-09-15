<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Plan Builder - Ask Dentist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('doctor.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 mr-4">
                        ← Back to Dashboard
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Dental Plan Builder</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Dr. {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Patient Info Card -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Patient Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $consultation->patient->name ?? 'Unknown Patient' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Request Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $consultation->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="mt-1 inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ ucfirst($consultation->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dental Chart -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Dental Chart</h2>
                <p class="text-sm text-gray-600">Click on teeth to select them for treatment</p>
            </div>
            <div class="p-6">
                <!-- Teeth Chart Grid -->
                <div class="grid grid-cols-8 gap-2 mb-6">
                    <!-- Upper Teeth Row 1 (18-11) -->
                    @for($i = 18; $i >= 11; $i--)
                        <div 
                            class="teeth-selector w-12 h-12 border-2 border-gray-300 rounded cursor-pointer flex items-center justify-center text-xs font-medium hover:bg-blue-100 transition-colors"
                            data-tooth="t{{ $i }}"
                            onclick="toggleTooth('t{{ $i }}')"
                        >
                            {{ $i }}
                        </div>
                    @endfor
                </div>
                
                <div class="grid grid-cols-8 gap-2 mb-6">
                    <!-- Upper Teeth Row 2 (21-28) -->
                    @for($i = 21; $i <= 28; $i++)
                        <div 
                            class="teeth-selector w-12 h-12 border-2 border-gray-300 rounded cursor-pointer flex items-center justify-center text-xs font-medium hover:bg-blue-100 transition-colors"
                            data-tooth="t{{ $i }}"
                            onclick="toggleTooth('t{{ $i }}')"
                        >
                            {{ $i }}
                        </div>
                    @endfor
                </div>

                <!-- Separator -->
                <div class="border-t border-gray-300 my-6"></div>

                <div class="grid grid-cols-8 gap-2 mb-6">
                    <!-- Lower Teeth Row 1 (41-48) -->
                    @for($i = 41; $i <= 48; $i++)
                        <div 
                            class="teeth-selector w-12 h-12 border-2 border-gray-300 rounded cursor-pointer flex items-center justify-center text-xs font-medium hover:bg-blue-100 transition-colors"
                            data-tooth="t{{ $i }}"
                            onclick="toggleTooth('t{{ $i }}')"
                        >
                            {{ $i }}
                        </div>
                    @endfor
                </div>

                <div class="grid grid-cols-8 gap-2">
                    <!-- Lower Teeth Row 2 (38-31) -->
                    @for($i = 38; $i >= 31; $i--)
                        <div 
                            class="teeth-selector w-12 h-12 border-2 border-gray-300 rounded cursor-pointer flex items-center justify-center text-xs font-medium hover:bg-blue-100 transition-colors"
                            data-tooth="t{{ $i }}"
                            onclick="toggleTooth('t{{ $i }}')"
                        >
                            {{ $i }}
                        </div>
                    @endfor
                </div>

                <!-- Selected Teeth Display -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selected Teeth:</label>
                    <div id="selected-teeth" class="text-sm text-gray-600">None selected</div>
                </div>
            </div>
        </div>

        <!-- Treatment Plan Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Treatment Plan</h2>
            </div>
            <div class="p-6">
                <form id="treatment-plan-form">
                    <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
                    <input type="hidden" id="teeth-selection" name="teeth_selection" value="">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Treatment Plan Details -->
                        <div>
                            <label for="treatment_plan" class="block text-sm font-medium text-gray-700 mb-2">
                                Treatment Plan
                            </label>
                            <textarea 
                                id="treatment_plan" 
                                name="treatment_plan" 
                                rows="6"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Describe the recommended treatment plan..."
                                required
                            ></textarea>
                        </div>
                        
                        <!-- Notes and Cost -->
                        <div class="space-y-4">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Doctor Notes
                                </label>
                                <textarea 
                                    id="notes" 
                                    name="notes" 
                                    rows="3"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Additional notes for the patient..."
                                ></textarea>
                            </div>
                            
                            <div>
                                <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estimated Cost (EGP)
                                </label>
                                <input 
                                    type="number" 
                                    id="estimated_cost" 
                                    name="estimated_cost" 
                                    min="0" 
                                    step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="0.00"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <button 
                            type="button" 
                            onclick="savePlan()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Save Draft
                        </button>
                        <button 
                            type="button" 
                            onclick="submitPlan()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Submit to Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @livewireScripts

    <script>
        let selectedTeeth = [];

        function toggleTooth(toothId) {
            const element = document.querySelector(`[data-tooth="${toothId}"]`);
            
            if (selectedTeeth.includes(toothId)) {
                // Remove from selection
                selectedTeeth = selectedTeeth.filter(t => t !== toothId);
                element.classList.remove('bg-blue-500', 'text-white');
                element.classList.add('border-gray-300');
            } else {
                // Add to selection
                selectedTeeth.push(toothId);
                element.classList.add('bg-blue-500', 'text-white');
                element.classList.remove('border-gray-300');
            }
            
            updateSelectedTeethDisplay();
            document.getElementById('teeth-selection').value = JSON.stringify(selectedTeeth);
        }

        function updateSelectedTeethDisplay() {
            const display = document.getElementById('selected-teeth');
            if (selectedTeeth.length === 0) {
                display.textContent = 'None selected';
                display.className = 'text-sm text-gray-600';
            } else {
                display.textContent = selectedTeeth.map(t => t.replace('t', '')).join(', ');
                display.className = 'text-sm text-blue-600 font-medium';
            }
        }

        async function savePlan() {
            const formData = new FormData(document.getElementById('treatment-plan-form'));
            const data = Object.fromEntries(formData.entries());
            data.treatment_plan = JSON.stringify(data.treatment_plan);
            
            try {
                const response = await fetch('{{ route("doctor.plans.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Treatment plan saved successfully!');
                } else {
                    alert('Error saving plan: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error saving plan');
            }
        }

        async function submitPlan() {
            const formData = new FormData(document.getElementById('treatment-plan-form'));
            const data = Object.fromEntries(formData.entries());
            data.treatment_plan = JSON.stringify(data.treatment_plan);
            data.send_notification = true;
            
            if (!data.treatment_plan || data.treatment_plan === '""') {
                alert('Please enter a treatment plan before submitting.');
                return;
            }
            
            try {
                // First save the plan
                await savePlan();
                
                // Then submit it
                const response = await fetch(`{{ route("doctor.plans.submit", ":id") }}`.replace(':id', data.consultation_id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        final_notes: data.notes,
                        send_notification: true
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Treatment plan submitted successfully!');
                    window.location.href = '{{ route("doctor.dashboard") }}';
                } else {
                    alert('Error submitting plan: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error submitting plan');
            }
        }
    </script>
</body>
</html>