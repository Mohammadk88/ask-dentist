# Ask.Dentist Doctor App

Flutter mobile application for dental professionals to manage appointments and consultations.

## Features

- **Professional Dashboard**: Overview of daily schedule and earnings
- **Appointment Management**: View, accept, and reschedule appointments
- **Video Consultations**: Conduct video calls with patients
- **Patient Records**: Access patient history and medical records
- **Schedule Management**: Set availability and working hours
- **Prescription Writing**: Digital prescription creation
- **Payment Tracking**: View earnings and payment history
- **Professional Profile**: Manage qualifications and specialties

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
│   ├── dashboard/         # Doctor dashboard
│   ├── appointments/       # Appointment management
│   ├── consultations/      # Video consultations
│   ├── patients/          # Patient management
│   ├── schedule/          # Schedule management
│   ├── prescriptions/     # Prescription writing
│   └── profile/           # Doctor profile
└── main.dart              # App entry point
```

## Architecture

The app follows Clean Architecture principles with:

- **Presentation Layer**: UI widgets and state management
- **Domain Layer**: Business logic and entities
- **Data Layer**: API integration and local storage

### Key Features Implementation

#### Schedule Management

```dart
// Set availability
final scheduleController = ref.read(scheduleControllerProvider.notifier);
await scheduleController.updateAvailability(
  date: DateTime.now(),
  timeSlots: [
    TimeSlot(start: '09:00', end: '12:00'),
    TimeSlot(start: '14:00', end: '17:00'),
  ],
);
```

#### Appointment Handling

```dart
// Accept appointment
await ref.read(appointmentsProvider.notifier).acceptAppointment(
  appointmentId: appointment.id,
);

// Start consultation
await ref.read(consultationsProvider.notifier).startConsultation(
  appointmentId: appointment.id,
);
```

#### Patient Records

```dart
// View patient history
final patientHistory = ref.watch(
  patientHistoryProvider(patientId)
);
```

## Professional Features

### Appointment Management

- **Daily Schedule**: Overview of today's appointments
- **Appointment Details**: Patient info, symptoms, medical history
- **Quick Actions**: Accept, reschedule, or cancel appointments
- **Time Blocking**: Block time slots for breaks or procedures

### Video Consultations

- **HD Video Calls**: High-quality video consultations
- **Screen Sharing**: Share educational materials
- **Recording**: Record consultations (with consent)
- **Digital Tools**: Draw on screen for explanations

### Prescription Management

- **Digital Prescriptions**: Create and send prescriptions
- **Drug Database**: Search medications with dosages
- **Templates**: Save common prescription templates
- **E-Signatures**: Digital signature for prescriptions

### Patient Management

- **Patient Profiles**: Complete medical history
- **Treatment Plans**: Create and track treatment plans
- **Progress Notes**: Add consultation notes
- **Image Gallery**: Store dental photos and X-rays

## Configuration

### Environment Setup

```dart
// lib/core/config/doctor_config.dart
class DoctorConfig {
  static const String baseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: 'http://localhost:8080/api/v1',
  );
  
  static const bool enableVideoRecording = bool.fromEnvironment(
    'ENABLE_RECORDING',
    defaultValue: false,
  );
}
```

## Professional Verification

The app includes verification features for dental professionals:

### License Verification

```dart
// Upload professional license
final licenseController = ref.read(licenseControllerProvider.notifier);
await licenseController.uploadLicense(
  licenseNumber: '12345',
  issuingBoard: 'State Dental Board',
  expirationDate: DateTime(2025, 12, 31),
  documentFile: licenseFile,
);
```

### Specialization Management

```dart
// Add specialization
await ref.read(profileProvider.notifier).addSpecialization(
  Specialization(
    name: 'Orthodontics',
    certificationDate: DateTime(2020, 6, 15),
    certifyingBody: 'American Board of Orthodontics',
  ),
);
```

## Payment Integration

### Earnings Dashboard

- **Daily Earnings**: Track today's consultations
- **Monthly Reports**: Detailed earning reports
- **Payment Methods**: Manage payout preferences
- **Tax Documents**: Download tax forms

### Consultation Pricing

```dart
// Set consultation rates
await ref.read(pricingProvider.notifier).updateRates(
  consultationRate: 50.00,
  followUpRate: 30.00,
  emergencyRate: 75.00,
);
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

## Deployment

### Android

```bash
flutter build apk --release
```

### iOS

```bash
flutter build ipa --release
```

## Dependencies

### Core Dependencies

- `flutter_riverpod`: State management
- `go_router`: Navigation
- `dio`: HTTP client
- `shared_preferences`: Local storage

### Professional Tools

- `agora_rtc_engine`: Video consultations
- `signature`: Digital signatures
- `pdf`: Generate prescription PDFs
- `image_picker`: Medical image capture

### UI Components

- `flutter_screenutil`: Responsive design
- `calendar_view`: Schedule calendar
- `charts_flutter`: Earnings charts

## Contributing

1. Follow Flutter best practices
2. Maintain HIPAA compliance
3. Write comprehensive tests
4. Document medical features

## License

MIT License
