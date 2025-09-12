# Flutter Authentication System Documentation

This document explains how to use the comprehensive authentication system for the Ask Dentist MVP Flutter patient app.

## Overview

The authentication system provides:
- **Guest Mode**: Users can browse without authentication
- **Protected Actions**: Seamless login when authentication is required
- **State Management**: Riverpod-based authentication state
- **Auto-logout**: Handles token expiration and 401 responses
- **Error Handling**: Displays `auth_required` login modal automatically

## Components

### 1. AuthController (`auth_controller.dart`)
The main authentication state manager built with Riverpod.

**Key Features:**
- Manages `AuthState` (guest/authenticated/loading)
- Handles login, register, logout operations
- Cold start authentication check
- Token refresh management

**Usage:**
```dart
// Watch auth state
final authState = ref.watch(authControllerProvider);

// Access auth controller
final authController = ref.read(authControllerProvider.notifier);

// Login
await authController.login(email, password);

// Logout
await authController.logout();

// Check auth status
final isAuthenticated = ref.isAuthenticated;
final currentUser = ref.currentUser;
```

### 2. AuthState (`auth_controller.dart`)
Freezed union type representing authentication states.

**States:**
- `AuthState.guest()` - User not authenticated
- `AuthState.authenticated(User user)` - User logged in
- `AuthState.loading()` - Authentication in progress

**Extensions:**
```dart
final state = AuthState.authenticated(user);
print(state.isAuthenticated); // true
print(state.user?.firstName); // User's first name
```

### 3. RequireAuth Helper
Forces authentication before executing protected actions.

**Method 1: WidgetRef Extension (Recommended)**
```dart
await ref.requireAuth(context, () async {
  // This code only runs if user is authenticated
  // If guest, shows login modal first
  await callProtectedAPI();
});
```

**Method 2: Global Function**
```dart
await requireAuth(context, ref, () async {
  await callProtectedAPI();
});
```

### 4. LoginBottomSheet (`login_bottom_sheet.dart`)
Modal bottom sheet for authentication.

**Features:**
- Login and register in one modal
- Form validation
- Password visibility toggle
- Responsive design with drag handle
- Returns `true` if authentication succeeds

**Usage:**
```dart
final success = await showLoginBottomSheet(context);
if (success == true) {
  // User authenticated successfully
}
```

### 5. API Integration

#### AuthApi (`auth_api.dart`)
Retrofit-based API client for authentication endpoints.

**Endpoints:**
- `POST /auth/login` - User login
- `POST /auth/register` - User registration
- `POST /auth/logout` - User logout
- `POST /auth/refresh` - Refresh tokens
- `GET /auth/profile` - Get user profile
- `POST /auth/forgot-password` - Request password reset
- `POST /auth/reset-password` - Reset password
- `POST /auth/verify-email` - Verify email
- `POST /auth/resend-verification` - Resend verification

#### Request/Response Models
**Request Models:**
- `LoginRequest(email, password, rememberMe)`
- `RegisterRequest(firstName, lastName, email, password, passwordConfirmation)`
- `ForgotPasswordRequest(email)`
- `ResetPasswordRequest(email, token, password, passwordConfirmation)`
- `VerifyEmailRequest(token)`

**Response Models:**
- `LoginResponse(user, tokens, message)`
- `RegisterResponse(user, tokens, message, emailVerificationRequired)`
- `RefreshResponse(tokens, message)`
- `AuthTokens(accessToken, refreshToken, tokenType, expiresIn)`

### 6. Error Handling

#### AuthInterceptor (`dio_config.dart`)
Automatically handles authentication in API requests.

**Features:**
- Adds `Authorization: Bearer <token>` header
- Clears tokens on 401 responses
- Detects `auth_required` error codes

#### ApiErrorHandler (`api_error_handler.dart`)
Global error handling for API responses.

**Usage:**
```dart
try {
  await apiCall();
} catch (e) {
  await Future.error(e).handleApiErrors(context);
}
```

**Automatic Login Modal:**
When API returns 401 with `error_code: 'auth_required'`, the error handler automatically shows the login modal.

## Integration Guide

### 1. App Initialization
Update your `main.dart`:

```dart
void main() {
  runApp(
    ProviderScope(
      child: MyApp(),
    ),
  );
}

class MyApp extends ConsumerWidget {
  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return MaterialApp(
      home: const MainScreen(),
    );
  }
}
```

### 2. Main Screen Structure
```dart
class MainScreen extends ConsumerWidget {
  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authControllerProvider);
    
    return authState.when(
      data: (state) => state.when(
        guest: () => GuestHomeScreen(),
        authenticated: (user) => AuthenticatedHomeScreen(user: user),
        loading: () => LoadingScreen(),
      ),
      loading: () => LoadingScreen(),
      error: (error, stack) => ErrorScreen(error: error),
    );
  }
}
```

### 3. Protected Actions Implementation
```dart
class DoctorProfileScreen extends ConsumerWidget {
  final Doctor doctor;
  
  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Scaffold(
      appBar: AppBar(title: Text(doctor.name)),
      body: Column(
        children: [
          // Doctor info (public)
          DoctorInfoCard(doctor: doctor),
          
          // Protected actions
          ElevatedButton(
            onPressed: () => _addToFavorites(context, ref),
            child: Text('Add to Favorites'),
          ),
          ElevatedButton(
            onPressed: () => _sendMessage(context, ref),
            child: Text('Send Message'),
          ),
          ElevatedButton(
            onPressed: () => _bookAppointment(context, ref),
            child: Text('Book Appointment'),
          ),
        ],
      ),
    );
  }
  
  Future<void> _addToFavorites(BuildContext context, WidgetRef ref) async {
    await ref.requireAuth(context, () async {
      try {
        await ref.read(favoritesRepositoryProvider).addFavorite(doctor.id);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Added to favorites!')),
        );
      } catch (e) {
        await Future.error(e).handleApiErrors(context);
      }
    });
  }
  
  // Similar for other protected actions...
}
```

### 4. Auth State Listening
```dart
class AppWithAuthListener extends ConsumerWidget {
  @override
  Widget build(BuildContext context, WidgetRef ref) {
    // Listen to auth state changes
    ref.listen<AsyncValue<AuthState>>(authControllerProvider, (previous, next) {
      next.when(
        data: (state) => state.when(
          guest: () {
            // Handle logout or app start
            print('User is in guest mode');
          },
          authenticated: (user) {
            // Handle successful login
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Welcome back, ${user.firstName}!')),
            );
          },
          loading: () => {},
        ),
        loading: () => {},
        error: (error, stack) => {
          // Handle auth errors
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Authentication error: $error')),
          );
        },
      );
    });
    
    return YourAppContent();
  }
}
```

## Backend Integration

The Flutter app expects the Laravel backend to return specific error responses for proper integration:

### Expected 401 Response Format
```json
{
  "error_code": "auth_required",
  "message": "Authentication required to access this resource",
  "action": "favorites"
}
```

### Laravel Controller Implementation
```php
// In your Laravel controllers, use the RequiresAuthentication trait
class FavoritesController extends Controller
{
    use RequiresAuthentication;
    
    public function store(Request $request)
    {
        // This will automatically return proper 401 with auth_required error_code
        $this->requireAuth();
        
        // Your protected logic here...
    }
}
```

## Testing

Use the provided example file (`auth_usage_example.dart`) to test the authentication flow:

1. Run the app in guest mode
2. Try to perform protected actions
3. Verify login modal appears
4. Complete authentication
5. Verify protected actions work
6. Test logout functionality

## File Structure

```
lib/core/auth/
├── controllers/
│   └── auth_controller.dart          # Main auth state management
├── models/
│   ├── user.dart                     # User model (existing)
│   ├── auth_requests.dart            # Request DTOs
│   └── auth_responses.dart           # Response DTOs
├── api/
│   └── auth_api.dart                 # API client
├── examples/
│   └── auth_usage_example.dart       # Usage examples
└── token_storage.dart                # Token storage (existing)

lib/core/widgets/
└── login_bottom_sheet.dart           # Login modal

lib/core/network/
├── dio_config.dart                   # Updated with auth interceptor
├── dio_provider.dart                 # Riverpod Dio provider
└── api_error_handler.dart            # Global error handling
```

## Key Benefits

1. **Seamless UX**: Users can browse as guests and authenticate only when needed
2. **Consistent API**: All protected actions use the same `requireAuth` pattern
3. **Error Handling**: Automatic login modal on `auth_required` responses
4. **State Management**: Reactive UI based on authentication state
5. **Token Management**: Automatic token refresh and cleanup
6. **Type Safety**: Full type safety with Freezed and Riverpod
7. **Scalable**: Easy to add new protected actions using the same pattern

## Next Steps

1. Update your app's main.dart to use ProviderScope
2. Replace existing authentication logic with AuthController
3. Update all protected actions to use `requireAuth`
4. Test the complete flow from guest to authenticated user
5. Verify backend integration with expected error responses