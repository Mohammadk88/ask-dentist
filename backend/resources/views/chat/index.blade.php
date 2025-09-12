<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Chat - AskDentist</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Pusher -->
    <script src="https://js.pusher.io/8.2.0/pusher.min.js"></script>
    
    <style>
        .chat-container { height: 100vh; display: flex; }
        .conversations-sidebar { width: 300px; border-right: 1px solid #e5e7eb; }
        .chat-main { flex: 1; display: flex; flex-direction: column; }
        .chat-header { padding: 1rem; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
        .messages-container { flex: 1; overflow-y: auto; padding: 1rem; }
        .message-input { padding: 1rem; border-top: 1px solid #e5e7eb; background: #f9fafb; }
        .message { margin-bottom: 1rem; padding: 0.75rem; border-radius: 0.5rem; max-width: 70%; }
        .message.own { background: #3b82f6; color: white; margin-left: auto; }
        .message.other { background: #f3f4f6; color: #374151; }
        .conversation-item { padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; }
        .conversation-item:hover { background: #f3f4f6; }
        .conversation-item.active { background: #dbeafe; border-left: 3px solid #3b82f6; }
        .typing-indicator { padding: 0.5rem; font-style: italic; color: #6b7280; }
        .unread-badge { background: #ef4444; color: white; border-radius: 50%; padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="p-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold">Conversations</h2>
                <p class="text-sm text-gray-600">{{ $user->name }} ({{ ucfirst($user->role) }})</p>
            </div>
            
            <div id="conversations-list">
                @foreach($consultations as $consultation)
                    <div class="conversation-item" 
                         data-consultation-id="{{ $consultation->id }}"
                         onclick="loadConversation({{ $consultation->id }})">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">
                                    @if($user->hasRole('Doctor'))
                                        {{ $consultation->patient->user->name }}
                                    @else
                                        Dr. {{ $consultation->doctor->user->name }}
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600">{{ $consultation->status }}</p>
                                <p class="text-xs text-gray-500">{{ $consultation->updated_at->diffForHumans() }}</p>
                            </div>
                            @if($consultation->unread_messages_count > 0)
                                <span class="unread-badge">{{ $consultation->unread_messages_count }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-header">
                <div id="chat-header-content">
                    <h3 class="text-lg font-semibold">Select a conversation</h3>
                    <p class="text-sm text-gray-600">Choose from the conversations on the left</p>
                </div>
            </div>

            <div class="messages-container" id="messages-container">
                <div class="text-center text-gray-500 mt-10">
                    <p>Select a conversation to start chatting</p>
                </div>
            </div>

            <div class="typing-indicator" id="typing-indicator" style="display: none;">
                <span id="typing-text"></span>
            </div>

            <div class="message-input" id="message-input-container" style="display: none;">
                <form id="message-form" class="flex gap-2">
                    <input 
                        type="text" 
                        id="message-input" 
                        placeholder="Type your message..." 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <input 
                        type="file" 
                        id="attachment-input" 
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

    <script>
        // Chat application JavaScript
        class LiveChat {
            constructor() {
                this.currentConsultationId = null;
                this.pusher = null;
                this.conversationChannel = null;
                this.userChannel = null;
                this.typingTimer = null;
                this.isTyping = false;
                
                this.initializePusher();
                this.bindEvents();
            }

            initializePusher() {
                this.pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
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

                // Subscribe to user's personal channel
                this.userChannel = this.pusher.subscribe('private-user.{{ $user->id }}');
                this.userChannel.bind('plan.submitted', (data) => {
                    this.handlePlanNotification(data, 'submitted');
                });
                this.userChannel.bind('plan.accepted', (data) => {
                    this.handlePlanNotification(data, 'accepted');
                });
            }

            bindEvents() {
                // Message form submission
                document.getElementById('message-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.sendMessage();
                });

                // Typing indicator
                document.getElementById('message-input').addEventListener('input', () => {
                    this.handleTyping();
                });

                // Attachment button
                document.getElementById('attachment-btn').addEventListener('click', () => {
                    document.getElementById('attachment-input').click();
                });

                // File attachment
                document.getElementById('attachment-input').addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        this.sendMessage();
                    }
                });
            }

            loadConversation(consultationId) {
                if (this.currentConsultationId === consultationId) return;

                // Unsubscribe from previous conversation channel
                if (this.conversationChannel) {
                    this.pusher.unsubscribe(this.conversationChannel.name);
                }

                this.currentConsultationId = consultationId;

                // Subscribe to new conversation channel
                this.conversationChannel = this.pusher.subscribe(`private-conversation.${consultationId}`);
                this.conversationChannel.bind('message.sent', (data) => {
                    this.addMessage(data.message, false);
                });
                this.conversationChannel.bind('user.typing', (data) => {
                    this.showTypingIndicator(data);
                });

                // Load conversation data
                fetch(`/chat/conversation/${consultationId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.displayConversation(data);
                    this.markAsActive(consultationId);
                    this.markAsRead();
                });
            }

            displayConversation(data) {
                const { consultation, messages } = data;
                
                // Update header
                const headerContent = document.getElementById('chat-header-content');
                const participantName = {{ $user->hasRole('Doctor') ? 'consultation.patient.user.name' : 'consultation.doctor.user.name' }};
                headerContent.innerHTML = `
                    <h3 class="text-lg font-semibold">${participantName}</h3>
                    <p class="text-sm text-gray-600">Consultation #${consultation.id} - ${consultation.status}</p>
                `;

                // Display messages
                const messagesContainer = document.getElementById('messages-container');
                messagesContainer.innerHTML = '';
                
                messages.data.reverse().forEach(message => {
                    this.addMessage(message, false);
                });

                // Show message input
                document.getElementById('message-input-container').style.display = 'block';
                
                // Scroll to bottom
                this.scrollToBottom();
            }

            addMessage(message, isOwn = null) {
                if (isOwn === null) {
                    isOwn = message.user_id === {{ $user->id }};
                }

                const messagesContainer = document.getElementById('messages-container');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${isOwn ? 'own' : 'other'}`;
                
                const time = new Date(message.created_at).toLocaleTimeString();
                messageDiv.innerHTML = `
                    <div class="message-content">
                        ${message.content}
                    </div>
                    <div class="text-xs opacity-75 mt-1">
                        ${isOwn ? 'You' : message.user.name} • ${time}
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);
                this.scrollToBottom();
            }

            sendMessage() {
                const messageInput = document.getElementById('message-input');
                const attachmentInput = document.getElementById('attachment-input');
                const message = messageInput.value.trim();
                
                if (!message && !attachmentInput.files.length) return;
                if (!this.currentConsultationId) return;

                const formData = new FormData();
                formData.append('message', message);
                
                if (attachmentInput.files.length > 0) {
                    formData.append('attachment', attachmentInput.files[0]);
                    formData.append('type', 'file');
                }

                fetch(`/chat/conversation/${this.currentConsultationId}/message`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        this.addMessage(data.message, true);
                        messageInput.value = '';
                        attachmentInput.value = '';
                    }
                });
            }

            handleTyping() {
                if (!this.currentConsultationId) return;

                if (!this.isTyping) {
                    this.isTyping = true;
                    this.sendTypingStatus(true);
                }

                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.isTyping = false;
                    this.sendTypingStatus(false);
                }, 1000);
            }

            sendTypingStatus(typing) {
                fetch(`/chat/conversation/${this.currentConsultationId}/typing`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ typing })
                });
            }

            showTypingIndicator(data) {
                const indicator = document.getElementById('typing-indicator');
                const text = document.getElementById('typing-text');
                
                if (data.is_typing) {
                    text.textContent = `${data.user.name} is typing...`;
                    indicator.style.display = 'block';
                } else {
                    indicator.style.display = 'none';
                }
            }

            markAsActive(consultationId) {
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector(`[data-consultation-id="${consultationId}"]`).classList.add('active');
            }

            markAsRead() {
                if (!this.currentConsultationId) return;
                
                fetch(`/chat/conversation/${this.currentConsultationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            }

            handlePlanNotification(data, type) {
                // Show notification toast
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-blue-500 text-white p-4 rounded-lg z-50';
                toast.innerHTML = `
                    <h4 class="font-semibold">Treatment Plan ${type === 'submitted' ? 'Submitted' : 'Accepted'}</h4>
                    <p class="text-sm">${data.treatment_plan.title}</p>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }

            scrollToBottom() {
                const container = document.getElementById('messages-container');
                container.scrollTop = container.scrollHeight;
            }
        }

        // Global functions
        function loadConversation(consultationId) {
            window.liveChat.loadConversation(consultationId);
        }

        // Initialize chat when page loads
        document.addEventListener('DOMContentLoaded', () => {
            window.liveChat = new LiveChat();
        });
    </script>
</body>
</html>