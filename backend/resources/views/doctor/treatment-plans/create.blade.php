@extends('layouts.app')

@section('title', 'Create Treatment Plan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Create Treatment Plan</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Design a comprehensive treatment plan for {{ $treatmentRequest->patient->user->name }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($existingPlan)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Draft Exists
                            </span>
                        @endif
                        <a href="{{ route('doctor.plans.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Plans
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Patient Information -->
            <div class="px-6 py-4 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Patient</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $treatmentRequest->patient->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Request Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $treatmentRequest->created_at->format('M j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                {{ $treatmentRequest->status }}
                            </span>
                        </dd>
                    </div>
                </div>
                
                @if($treatmentRequest->description)
                    <div class="mt-4">
                        <dt class="text-sm font-medium text-gray-500">Patient's Request</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $treatmentRequest->description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dental Plan Builder Component -->
        <div class="bg-white shadow rounded-lg">
            @livewire('dental-plan-builder', [
                'treatmentRequest' => $treatmentRequest,
                'existingPlan' => $existingPlan
            ])
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="notification-area" class="fixed top-4 right-4 z-50 space-y-2"></div>

@push('scripts')
<script>
// Global notification system for AJAX responses
window.showNotification = function(message, type = 'success') {
    const area = document.getElementById('notification-area');
    const notification = document.createElement('div');
    
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    
    notification.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    area.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
};

// Listen for Livewire events
document.addEventListener('livewire:init', () => {
    Livewire.on('plan-saved', (event) => {
        showNotification(event.message || 'Plan saved successfully', 'success');
    });
    
    Livewire.on('plan-submitted', (event) => {
        showNotification(event.message || 'Plan submitted successfully', 'success');
        // Optionally redirect after submission
        setTimeout(() => {
            window.location.href = "{{ route('doctor.plans.index') }}";
        }, 2000);
    });
    
    Livewire.on('plan-error', (event) => {
        showNotification(event.message || 'An error occurred', 'error');
    });
});
</script>
@endpush
@endsection