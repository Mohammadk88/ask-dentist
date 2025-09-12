# API Routes Structure Update Summary

## Overview
Updated the `routes/api.php` file to implement a clear separation between public (guest-accessible) and protected (authentication-required) endpoints according to the specifications.

## Route Structure Changes

### ✅ Public Routes (Using `opt.auth` middleware)
These routes support guest browsing with optional authentication:

- `GET /api/v1/home` - Main home feed
- `GET /api/v1/stories` - Success stories
- `GET /api/v1/clinics/top` - Top clinics
- `GET /api/v1/doctors/top` - Top doctors  
- `GET /api/v1/before-after` - Before/after content
- `GET /api/v1/doctors/{id}` - Individual doctor details
- `GET /api/v1/clinics/{id}` - Individual clinic details
- `GET /api/v1/search` - Search functionality
- `GET /api/v1/specializations` - Reference data (no auth)

### 🔐 Protected Routes (Using `auth:api` middleware)
These routes require authentication and return standardized 401 responses:

#### Favorites Management
- `POST /api/v1/favorites/doctors/{id}` - Toggle doctor favorite
- `GET /api/v1/favorites/doctors` - Get favorite doctors

#### Appointments
- `POST /api/v1/appointments` - Book consultation

#### Communication
- `POST /api/v1/messages` - Start chat conversation
- `POST /api/v1/calls` - Initiate voice call (placeholder)
- `POST /api/v1/videos` - Initiate video call (placeholder)

#### Treatment Management
- `POST /api/v1/treatment-requests` - Submit treatment case
- `POST /api/v1/plans/{id}/accept` - Accept treatment plan
- `POST /api/v1/plans/{id}/reject` - Reject treatment plan

## Controller Updates

### ✅ New Controllers Created
1. **AppointmentController** - Handles consultation booking
2. **MessageController** - Manages chat conversations
3. **CallController** - Voice call initiation (placeholder)
4. **VideoController** - Video call initiation (placeholder)

### ✅ Updated Existing Controllers
1. **TreatmentRequestController** - Added `store()` method for submitting requests
2. **TreatmentPlanController** - Added authentication checks
3. **FavoritesController** - Added authentication checks

## Standardized 401 Response
All protected endpoints now return this response when authentication is missing:

```json
{
  "error": "auth_required",
  "message": "Login required."
}
```

**HTTP Status Code**: 401 Unauthorized

## Implementation Details

### Middleware Configuration
- **Public routes**: Use `['opt.auth', 'throttle:120,1']` middleware
- **Protected routes**: Use `['auth:api', 'throttle:30,1|60,1']` middleware
- **Role restrictions**: Patient-only actions use `'custom.role:Patient'`
- **Audit logging**: Sensitive actions include `'audit'` middleware

### Rate Limiting
- Public browsing: 120 requests/minute
- Protected actions: 30-60 requests/minute
- File operations: Up to 300 requests/minute

### Route Organization
Routes are now organized in clear sections:
1. **Public Routes** - Guest mode with optional authentication
2. **Protected Routes** - Authentication required sections
3. **Legacy routes** - Existing authentication, profile, FCM, etc.

## Validation & Testing

### ✅ Route Registration Verified
- All new endpoints properly registered
- Middleware correctly applied
- Route names assigned for reference

### ✅ Controller Methods Implemented
- All required endpoints have corresponding controller methods
- Proper validation rules implemented
- Standardized response formats

### ✅ Authentication Handling
- Double-check authentication in controller methods
- Proper 401 responses for unauthenticated requests
- Role-based access control maintained

## Next Steps for Frontend Integration

### 1. Update API Service Layer
- Modify existing API services to use new endpoint structure
- Implement proper error handling for 401 responses
- Add support for optional authentication headers

### 2. State Management
- Update Riverpod providers to handle auth requirement states
- Implement authentication prompt triggers
- Manage guest/authenticated user flow

### 3. UI Components
- Create authentication requirement dialogs
- Add "Login Required" indicators for protected actions
- Implement seamless auth flow transitions

### 4. Testing
- Update integration tests for new endpoint structure
- Test guest mode browsing scenarios
- Verify authentication requirement handling

## Security Considerations

### ✅ Implemented
- Optional authentication prevents unauthorized access to protected data
- Role-based access control for patient-specific actions
- Rate limiting prevents API abuse
- Audit logging for sensitive operations
- Proper token validation with Laravel Passport

### ✅ Route Security
- Clear separation between public and protected endpoints
- No sensitive data exposed in guest-accessible routes
- Proper middleware stacking for security

## Backward Compatibility

The update maintains backward compatibility by:
- Keeping existing authentication routes unchanged
- Preserving existing controller interfaces
- Maintaining existing middleware functionality
- Not breaking existing API consumers

## Documentation

### API Endpoints Summary
- **Public**: 9 endpoints for guest browsing
- **Protected**: 8 endpoints requiring authentication
- **Legacy**: Existing auth, profile, FCM, etc. routes maintained

### Response Formats
- Consistent JSON response structure
- Standardized error responses
- Clear authentication requirement indicators

The API structure now provides a clean separation between guest-accessible content and protected user actions, enabling a smooth guest mode experience while maintaining security for authenticated features.