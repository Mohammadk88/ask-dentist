# Ask.Dentist Patient App

Flutter mobile application for patients to book dental appointments and consultations.

## Features

- **User Registration & Login**: Secure authentication
- **Doctor Search**: Find dental professionals by location and specialty
- **Appointment Booking**: Schedule appointments with available time slots
- **Video Consultations**: Real-time video calls with dentists
- **Medical Records**: View past consultations and prescriptions
- **Payment Integration**: Secure payment processing
- **Push Notifications**: Appointment reminders and updates
- **Chat Support**: In-app messaging with doctors

## Getting Started

### Prerequisites

- Flutter SDK (3.24+)
- Dart SDK (3.4+)
- Android Studio / Xcode
- VS Code with Flutter extension

### Installation

1. Get dependencies:

```bash
flutter pub get
```

2. Generate code:

```bash
flutter packages pub run build_runner build
```

3. Run the app:

```bash
# Debug mode
flutter run

# Release mode
flutter run --release
```

## Project Structure

```text
lib/
├── core/                    # Core functionality
│   ├── constants/          # App constants
│   ├── errors/             # Error handling
│   ├── network/            # API client
│   ├── utils/              # Utility functions
│   └── widgets/            # Reusable widgets
├── features/               # Feature modules
│   ├── auth/              # Authentication
│   ├── appointments/       # Appointment booking
│   ├── consultations/      # Video consultations
│   ├── doctors/           # Doctor listings
│   ├── profile/           # User profile
│   └── home/              # Home dashboard
└── main.dart              # App entry point
```

## Architecture

The app follows Clean Architecture principles:

- **Presentation Layer**: UI widgets and state management
- **Domain Layer**: Business logic and entities
- **Data Layer**: API integration and local storage

### State Management

Using Riverpod for state management:

- `StateNotifier` for complex state
- `FutureProvider` for async operations
- `StateProvider` for simple state

### API Integration

- HTTP client with Dio
- JWT token authentication
- Automatic token refresh
- Error handling and retry logic

## Features Implementation

### Authentication

```dart
// Login example
final authController = ref.read(authControllerProvider.notifier);
await authController.login(email, password);
```

### Doctor Search

```dart
// Search doctors
final doctors = ref.watch(doctorsProvider);
final filteredDoctors = ref.watch(
  doctorsProvider.select((doctors) => 
    doctors.where((d) => d.specialty == 'Orthodontist')
  )
);
```

### Appointment Booking

```dart
// Book appointment
final appointment = Appointment(
  doctorId: doctor.id,
  dateTime: selectedDateTime,
  type: AppointmentType.consultation,
);
await ref.read(appointmentsProvider.notifier).bookAppointment(appointment);
```

## Configuration

### Environment Setup

Create environment-specific config files:

```dart
// lib/core/config/app_config.dart
class AppConfig {
  static const String baseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: 'http://localhost:8080/api/v1',
  );
  
  static const String pusherKey = String.fromEnvironment(
    'PUSHER_KEY',
    defaultValue: 'askdentist-key',
  );
}
```

### Build Variants

```bash
# Development
flutter run --dart-define=BASE_URL=http://localhost:8080/api/v1

# Staging
flutter run --dart-define=BASE_URL=https://staging.askdentist.com/api/v1

# Production
flutter run --dart-define=BASE_URL=https://api.askdentist.com/api/v1
```

## Testing

### Unit Tests

```bash
flutter test
```

### Integration Tests

```bash
flutter test integration_test/
```

### Widget Tests

```bash
flutter test test/widget_test/
```

## Deployment

### Android

1. Build APK:

```bash
flutter build apk --release
```

2. Build App Bundle:

```bash
flutter build appbundle --release
```

### iOS

1. Build IPA:

```bash
flutter build ipa --release
```

2. Upload to App Store Connect

## Dependencies

### Core

- `flutter_riverpod`: State management
- `go_router`: Navigation
- `dio`: HTTP client
- `shared_preferences`: Local storage
- `flutter_secure_storage`: Secure storage

### UI

- `flutter_screenutil`: Responsive design
- `cached_network_image`: Image caching
- `shimmer`: Loading animations
- `flutter_svg`: SVG support

### Features

- `image_picker`: Camera/gallery access
- `permission_handler`: Permissions
- `pusher_channels_flutter`: Real-time updates
- `agora_rtc_engine`: Video calling
- `stripe_payment`: Payment processing

## Contributing

1. Follow Flutter style guide
2. Write tests for new features
3. Update documentation
4. Use conventional commits

## License

MIT License
