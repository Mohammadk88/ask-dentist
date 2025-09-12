@extends('layouts.app')

@section('title', 'Chat Conversation')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg h-96">
        <!-- Chat Header -->
        <div class="border-b p-4 bg-gray-50 rounded-t-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold">
                        @if($user->hasRole('Doctor'))
                            {{ $consultation->patient->user->name }}
                        @else
                            Dr. {{ $consultation->doctor->user->name }}
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600">
                        Consultation #{{ $consultation->id }} - {{ ucfirst($consultation->status) }}
                    </p>
                </div>
                <a href="{{ route('chat.index') }}" class="text-blue-500 hover:text-blue-700">
                    ← Back to Conversations
                </a>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 h-64" id="messages-area">
            @foreach($messages->reverse() as $message)
                <div class="message mb-4 {{ $message->user_id === $user->id ? 'text-right' : 'text-left' }}">
                    <div class="inline-block max-w-xs lg:max-w-md p-3 rounded-lg {{ $message->user_id === $user->id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                        <p>{{ $message->content }}</p>
                        @if($message->attachment_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($message->attachment_path) }}" target="_blank" class="underline">
                                    📎 Attachment
                                </a>
                            </div>
                        @endif
                        <div class="text-xs mt-1 opacity-75">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $message->user_id === $user->id ? 'You' : $message->user->name }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="px-4 py-2 text-sm text-gray-500 italic" style="display: none;">
            <span id="typing-text"></span>
        </div>

        <!-- Message Input -->
        <div class="border-t p-4 bg-gray-50 rounded-b-lg">
            <form id="message-form" class="flex gap-2">
                @csrf
                <input 
                    type="text" 
                    id="message-input" 
                    name="message"
                    placeholder="Type your message..." 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <input 
                    type="file" 
                    id="attachment-input" 
                    name="attachment"
                    accept="image/*,.pdf,.doc,.docx" 
                    style="display: none;"
                >
                <button 
                    type="button" 
                    id="attachment-btn"
                    class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                >
                    📎
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                >
                    Send
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.io/8.2.0/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const consultationId = {{ $consultation->id }};
    const userId = {{ $user->id }};
    
    // Initialize Pusher
    const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        host: '{{ config("broadcasting.connections.pusher.options.host") }}',
        port: {{ config("broadcasting.connections.pusher.options.port") }},
        forceTLS: false,
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        authEndpoint: '/broadcasting/auth'
    });

    // Subscribe to conversation channel
    const channel = pusher.subscribe(`private-conversation.${consultationId}`);
    
    // Listen for new messages
    channel.bind('message.sent', function(data) {
        if (data.message.user_id !== userId) {
            addMessage(data.message);
        }
    });

    // Listen for typing indicators
    channel.bind('user.typing', function(data) {
        if (data.user.id !== userId) {
            showTypingIndicator(data);
        }
    });

    // Message form handling
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    // Attachment button
    document.getElementById('attachment-btn').addEventListener('click', function() {
        document.getElementById('attachment-input').click();
    });

    // File attachment
    document.getElementById('attachment-input').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            sendMessage();
        }
    });

    // Typing indicator
    let typingTimer;
    let isTyping = false;
    document.getElementById('message-input').addEventListener('input', function() {
        if (!isTyping) {
            isTyping = true;
            sendTypingStatus(true);
        }

        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
            sendTypingStatus(false);
        }, 1000);
    });

    function sendMessage() {
        const form = document.getElementById('message-form');
        const formData = new FormData(form);

        fetch(`/chat/conversation/${consultationId}/message`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                addMessage(data.message, true);
                form.reset();
            }
        });
    }

    function addMessage(message, isOwn = false) {
        const messagesArea = document.getElementById('messages-area');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message mb-4 ${isOwn || message.user_id === userId ? 'text-right' : 'text-left'}`;
        
        const time = new Date(message.created_at).toLocaleTimeString();
        const bgClass = isOwn || message.user_id === userId ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800';
        
        messageDiv.innerHTML = `
            <div class="inline-block max-w-xs lg:max-w-md p-3 rounded-lg ${bgClass}">
                <p>${message.content}</p>
                <div class="text-xs mt-1 opacity-75">${time}</div>
            </div>
            <div class="text-xs text-gray-500 mt-1">
                ${isOwn || message.user_id === userId ? 'You' : message.user.name}
            </div>
        `;

        messagesArea.appendChild(messageDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function sendTypingStatus(typing) {
        fetch(`/chat/conversation/${consultationId}/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ typing })
        });
    }

    function showTypingIndicator(data) {
        const indicator = document.getElementById('typing-indicator');
        const text = document.getElementById('typing-text');
        
        if (data.is_typing) {
            text.textContent = `${data.user.name} is typing...`;
            indicator.style.display = 'block';
        } else {
            indicator.style.display = 'none';
        }
    }

    // Mark messages as read on page load
    fetch(`/chat/conversation/${consultationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    // Auto-scroll to bottom
    document.getElementById('messages-area').scrollTop = document.getElementById('messages-area').scrollHeight;
});
</script>
@endpush
@endsection