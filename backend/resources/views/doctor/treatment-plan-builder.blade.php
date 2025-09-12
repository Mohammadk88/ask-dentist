@extends('doctor.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Treatment Plan Builder</h1>
                    <p class="mt-1 text-sm text-gray-500">Create comprehensive treatment plans for your patients</p>
                </div>
                <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Treatment Plan Builder Interface -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Patient Selection -->
            <div class="mb-6">
                <label for="patient-select" class="block text-sm font-medium text-gray-700 mb-2">
                    Select Patient
                </label>
                <select id="patient-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Choose a patient...</option>
                    <option value="1">Ahmed Hassan - Age: 32</option>
                    <option value="2">Fatima Al-Zahra - Age: 28</option>
                    <option value="3">Mohammad Ali - Age: 45</option>
                    <option value="4">Nour El-Din - Age: 38</option>
                    <option value="5">Sarah Ibrahim - Age: 26</option>
                </select>
            </div>

            <!-- Treatment Plan Form -->
            <div class="space-y-6">
                <!-- Diagnosis Section -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Diagnosis</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="chief-complaint" class="block text-sm font-medium text-gray-700">
                                Chief Complaint
                            </label>
                            <textarea id="chief-complaint" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Patient's main concern..."></textarea>
                        </div>
                        <div>
                            <label for="clinical-findings" class="block text-sm font-medium text-gray-700">
                                Clinical Findings
                            </label>
                            <textarea id="clinical-findings" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Examination findings..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Affected Teeth -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Affected Teeth</h3>
                    <div class="grid grid-cols-8 gap-2 mb-4">
                        <!-- Upper teeth -->
                        <div class="col-span-8 text-center text-sm font-medium text-gray-500 mb-2">Upper Jaw</div>
                        @for($i = 18; $i >= 11; $i--)
                            <button type="button" class="tooth-selector w-10 h-10 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-indigo-500 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-tooth="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                        @for($i = 21; $i <= 28; $i++)
                            <button type="button" class="tooth-selector w-10 h-10 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-indigo-500 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-tooth="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                    </div>
                    <div class="grid grid-cols-8 gap-2">
                        <!-- Lower teeth -->
                        @for($i = 48; $i >= 41; $i--)
                            <button type="button" class="tooth-selector w-10 h-10 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-indigo-500 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-tooth="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                        @for($i = 31; $i <= 38; $i++)
                            <button type="button" class="tooth-selector w-10 h-10 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:border-indigo-500 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-tooth="{{ $i }}">
                                {{ $i }}
                            </button>
                        @endfor
                        <div class="col-span-8 text-center text-sm font-medium text-gray-500 mt-2">Lower Jaw</div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Click to select affected teeth (FDI numbering system)</p>
                </div>

                <!-- Treatment Steps -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Treatment Steps</h3>
                    <div id="treatment-steps">
                        <div class="treatment-step border border-gray-200 rounded-lg p-4 mb-3">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-medium text-gray-800">Step 1</h4>
                                <button type="button" class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Service
                                    </label>
                                    <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="">Select service...</option>
                                        <option value="cleaning">Professional Cleaning</option>
                                        <option value="filling">Composite Filling</option>
                                        <option value="crown">Crown Restoration</option>
                                        <option value="root-canal">Root Canal Treatment</option>
                                        <option value="extraction">Tooth Extraction</option>
                                        <option value="whitening">Teeth Whitening</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Duration (minutes)
                                    </label>
                                    <input type="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="60">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Cost (EGP)
                                    </label>
                                    <input type="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="500">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Notes
                                </label>
                                <textarea rows="2" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Treatment notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-step" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Treatment Step
                    </button>
                </div>

                <!-- Total Cost Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total Estimated Cost:</span>
                        <span class="text-2xl font-bold text-indigo-600" id="total-cost">500 EGP</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">*Final cost may vary based on actual treatment complexity</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save as Draft
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Submit Treatment Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooth selection functionality
    document.querySelectorAll('.tooth-selector').forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('bg-indigo-100');
            this.classList.toggle('border-indigo-500');
            this.classList.toggle('text-indigo-700');
        });
    });

    // Add treatment step functionality
    let stepCounter = 1;
    document.getElementById('add-step').addEventListener('click', function() {
        stepCounter++;
        const stepContainer = document.getElementById('treatment-steps');
        const newStep = document.querySelector('.treatment-step').cloneNode(true);

        // Update step number
        newStep.querySelector('h4').textContent = `Step ${stepCounter}`;

        // Clear form values
        newStep.querySelectorAll('input, textarea, select').forEach(input => {
            input.value = '';
        });

        stepContainer.appendChild(newStep);

        // Add remove functionality to new step
        newStep.querySelector('.text-red-600').addEventListener('click', function() {
            newStep.remove();
            updateTotalCost();
        });

        // Add cost calculation to new step
        newStep.querySelector('input[placeholder="500"]').addEventListener('input', updateTotalCost);
    });

    // Cost calculation
    function updateTotalCost() {
        let total = 0;
        document.querySelectorAll('input[placeholder="500"]').forEach(input => {
            if (input.value) {
                total += parseFloat(input.value) || 0;
            }
        });
        document.getElementById('total-cost').textContent = `${total} EGP`;
    }

    // Add cost calculation to initial step
    document.querySelector('input[placeholder="500"]').addEventListener('input', updateTotalCost);
});
</script>
@endsection
