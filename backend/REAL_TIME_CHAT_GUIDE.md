# Real-time Live Chat with Soketi Setup Guide

## 🚀 **Complete Implementation Summary**

Your AskDentist platform now has a complete real-time chat system with Pusher-compatible Soketi WebSocket server!

## 📋 **Features Implemented**

### ✅ **Real-time Events**
- **MessageSent**: Broadcasts when new messages are sent
- **PlanSubmitted**: Notifies when treatment plans are submitted  
- **PlanAccepted**: Alerts when treatment plans are accepted
- **UserTyping**: Shows typing indicators in real-time

### ✅ **Private Channels**
- `private-user.{id}`: Personal notifications for each user
- `private-conversation.{id}`: Chat messages for specific consultations

### ✅ **Web Interface**
- Complete live chat dashboard at `/chat`
- Individual conversation views at `/chat/conversation/{id}`
- Real-time message delivery and typing indicators
- File attachment support
- Read receipt tracking

### ✅ **API Integration**
- Message sending: `POST /chat/conversation/{id}/message`
- Message history: `GET /chat/conversation/{id}/messages`
- Mark as read: `POST /chat/conversation/{id}/read`
- Typing status: `POST /chat/conversation/{id}/typing`

## 🛠 **Setup Instructions**

### 1. **Start Soketi WebSocket Server**

Using Docker Compose:
```bash
cd backend
docker-compose -f docker-compose.soketi.yml up -d
```

Or install globally:
```bash
npm install -g @soketi/soketi
soketi start --config=soketi.json
```

### 2. **Queue Worker for Broadcasting**
```bash
cd backend
php artisan queue:work
```

### 3. **Access Live Chat**
- Web Interface: `http://localhost/chat`
- Must be authenticated (patient/doctor/admin)

## 🌐 **API Endpoints for Mobile**

### **Authentication Required**
All endpoints require `Authorization: Bearer {token}` header.

### **Get Conversations**
```http
GET /api/v2/consultations/{doctorId}
GET /api/v2/consultations/patient/{patientId}
```

### **Send Message**
```http
POST /chat/conversation/{consultationId}/message
Content-Type: multipart/form-data

{
  "message": "Hello doctor",
  "type": "text",
  "attachment": [file] // optional
}
```

### **Get Message History**
```http
GET /chat/conversation/{consultationId}/messages?page=1&per_page=20
```

### **Mark Messages as Read**
```http
POST /chat/conversation/{consultationId}/read
Content-Type: application/json

{
  "message_ids": [1, 2, 3] // optional - marks all if empty
}
```

### **Typing Indicator**
```http
POST /chat/conversation/{consultationId}/typing
Content-Type: application/json

{
  "typing": true
}
```

## 📱 **Mobile Integration**

### **Real-time Connection**
Mobile apps should connect to Soketi WebSocket server:
- **Host**: `ws://localhost:6001` (development)
- **App Key**: `ask-dentist-key`
- **Channels**: 
  - `private-user.{user_id}` for personal notifications
  - `private-conversation.{consultation_id}` for chat messages

### **Background Push Notifications**
The system will automatically send:
- **Email notifications** via SendGrid
- **Push notifications** via FCM
- **Real-time updates** via Soketi

When app is in background, users get push notifications. When active, they get real-time updates.

## 🔧 **Configuration**

### **Environment Variables**
```env
# Broadcasting
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=ask-dentist
PUSHER_APP_KEY=ask-dentist-key
PUSHER_APP_SECRET=ask-dentist-secret
PUSHER_HOST=localhost
PUSHER_PORT=6001
PUSHER_SCHEME=http

# Queue for broadcasting
QUEUE_CONNECTION=redis
```

### **Soketi Configuration**
The `docker-compose.soketi.yml` includes all necessary configuration:
- **Port 6001**: WebSocket connections
- **Port 9601**: Metrics and monitoring
- **Authentication**: Compatible with Laravel Broadcasting

## 🎯 **Event Flow**

### **Message Flow**
1. User sends message via web/API
2. Message stored in database
3. `MessageSent` event broadcasted
4. Real-time delivery to conversation participants
5. Email/push notifications sent if users offline

### **Treatment Plan Flow**
1. Doctor submits plan → `PlanSubmitted` event → Patient notified
2. Patient accepts plan → `PlanAccepted` event → Doctor notified  
3. Both events trigger real-time updates + background notifications

## 🧪 **Testing**

### **Test WebSocket Connection**
```javascript
// Browser console test
const pusher = new Pusher('ask-dentist-key', {
  cluster: 'mt1',
  host: 'localhost',
  port: 6001,
  forceTLS: false
});

const channel = pusher.subscribe('private-user.1');
channel.bind('plan.submitted', function(data) {
  console.log('Plan submitted:', data);
});
```

### **Test API Endpoints**
```bash
# Send a message
curl -X POST http://localhost/chat/conversation/1/message \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello from API"}'

# Get messages
curl -X GET http://localhost/chat/conversation/1/messages \
  -H "Authorization: Bearer {token}"
```

## 🔒 **Security Features**

- **Channel Authentication**: Users can only join their own conversations
- **Role-based Access**: Patients/doctors see only their consultations  
- **File Upload Validation**: Size limits and type restrictions
- **CSRF Protection**: All forms protected with tokens
- **Rate Limiting**: API endpoints throttled to prevent abuse

## 📊 **Monitoring**

- **Soketi Metrics**: Available at `http://localhost:9601/metrics`
- **Laravel Logs**: Check `storage/logs/laravel.log`
- **Queue Monitoring**: Use `php artisan queue:monitor`

## 🚀 **Production Deployment**

1. **Soketi Server**: Deploy on separate server/container
2. **SSL/TLS**: Use secure WebSocket (wss://)
3. **Redis**: Required for queue and broadcasting
4. **File Storage**: Configure cloud storage for attachments
5. **Monitoring**: Set up health checks and alerts

Your real-time chat system is now complete and ready for both web and mobile use! 🎉