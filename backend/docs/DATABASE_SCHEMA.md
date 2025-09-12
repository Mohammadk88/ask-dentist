# Database Schema Documentation

## Overview

This document describes the comprehensive database schema for the Ask.Dentist MVP platform, implementing a hexagonal architecture with PostgreSQL as the persistence layer.

## Architecture Principles

- **Hexagonal Architecture**: Eloquent models are isolated in the Infrastructure layer
- **Domain Separation**: Clean separation between Domain, Application, and Infrastructure layers
- **UUID Primary Keys**: All entities use UUID for primary keys (except reference tables)
- **Soft Deletes**: Critical entities support soft deletion for data integrity
- **JSONB Support**: Complex data structures stored as JSONB with GIN indexing
- **Comprehensive Indexing**: Strategic indexing for query performance

## Schema Overview

### Core Entities

1. **Users** - Authentication and basic user information
2. **Clinics** - Dental practice information and verification
3. **Doctors** - Doctor profiles with specialties and qualifications
4. **Patients** - Patient profiles with medical history
5. **Services** - Catalog of dental services and treatments
6. **Pricing** - Service pricing by clinic with currency support

### Workflow Entities

7. **Treatment Requests** - Patient treatment requests with symptoms
8. **Treatment Plans** - Doctor-proposed treatment plans with costs
9. **Appointments** - Scheduled appointments with status tracking
10. **Reviews** - Patient reviews and ratings for doctors/clinics

### Supporting Entities

11. **Doctor Clinics** - Many-to-many relationship for doctor-clinic associations
12. **Teeth Reference** - FDI tooth coding system reference data
13. **Notifications** - System notifications for users

---

## Detailed Schema

### 1. Users Table

**Purpose**: Core authentication and user management

```sql
CREATE TABLE users (
    id UUID PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    locale VARCHAR(5) DEFAULT 'en',
    timezone VARCHAR(50) DEFAULT 'UTC',
    role ENUM('admin', 'clinic_manager', 'doctor', 'patient') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Unique: `email`
- Composite: `(role, status)`, `(email, role)`

**Relationships**:
- One-to-One: `doctors`, `patients`

---

### 2. Clinics Table

**Purpose**: Dental practice management and verification

```sql
CREATE TABLE clinics (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    country CHAR(2) NOT NULL, -- ISO 3166-1 alpha-2
    city VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    website VARCHAR(255),
    description TEXT,
    operating_hours JSONB, -- Store opening hours
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Composite: `(country, city)`, `(country, city, verified_at)`
- Single: `verified_at`

**JSONB Structure** (`operating_hours`):
```json
{
  "monday": {"open": "09:00", "close": "17:00"},
  "tuesday": {"open": "09:00", "close": "17:00"},
  "wednesday": {"open": "09:00", "close": "17:00"},
  "thursday": {"open": "09:00", "close": "17:00"},
  "friday": {"open": "09:00", "close": "17:00"},
  "saturday": {"open": "09:00", "close": "13:00"},
  "sunday": "closed"
}
```

---

### 3. Doctors Table

**Purpose**: Doctor profiles with specialties and qualifications

```sql
CREATE TABLE doctors (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    license_number VARCHAR(255) UNIQUE NOT NULL,
    specialty ENUM('general', 'orthodontics', 'oral_surgery', 'endodontics', 'periodontics', 'prosthodontics', 'pediatric', 'cosmetic', 'implantology') NOT NULL,
    bio TEXT,
    qualifications JSONB, -- Education, certifications
    years_experience INTEGER DEFAULT 0,
    languages JSONB, -- Languages spoken
    rating DECIMAL(3,2) DEFAULT 0, -- 0.00 to 5.00
    total_reviews INTEGER DEFAULT 0,
    accepts_emergency BOOLEAN DEFAULT FALSE,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `user_id`
- Unique: `license_number`
- Composite: `(specialty, verified_at)`, `(rating, total_reviews)`
- Single: `specialty`, `verified_at`

**JSONB Structures**:

`qualifications`:
```json
{
  "education": [
    {
      "degree": "DDS",
      "institution": "University of Dentistry",
      "year": 2015
    }
  ],
  "certifications": [
    {
      "name": "Oral Surgery Certification",
      "issuer": "Board of Dentistry",
      "year": 2018
    }
  ]
}
```

`languages`:
```json
["en", "ar", "fr"]
```

---

### 4. Doctor Clinics Table

**Purpose**: Many-to-many relationship between doctors and clinics

```sql
CREATE TABLE doctor_clinics (
    id UUID PRIMARY KEY,
    doctor_id UUID NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    clinic_id UUID NOT NULL REFERENCES clinics(id) ON DELETE CASCADE,
    role ENUM('associate', 'partner', 'owner') NOT NULL,
    schedule JSONB, -- Doctor's schedule at this clinic
    started_at TIMESTAMP NOT NULL,
    ended_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    UNIQUE(doctor_id, clinic_id)
);
```

**Indexes**:
- Primary: `id` (UUID)
- Unique: `(doctor_id, clinic_id)`
- Composite: `(doctor_id, started_at, ended_at)`
- Foreign: `doctor_id`, `clinic_id`

---

### 5. Patients Table

**Purpose**: Patient profiles with medical history

```sql
CREATE TABLE patients (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other', 'prefer_not_to_say') NOT NULL,
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(20),
    insurance_provider VARCHAR(255),
    insurance_number VARCHAR(255),
    medical_history JSONB, -- Allergies, medications, conditions
    dental_history JSONB, -- Previous treatments, preferences
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `user_id`
- Single: `date_of_birth`

**JSONB Structures**:

`medical_history`:
```json
{
  "allergies": ["penicillin", "latex"],
  "medications": ["aspirin", "vitamins"],
  "conditions": ["diabetes", "hypertension"],
  "blood_type": "O+"
}
```

`dental_history`:
```json
{
  "previous_treatments": [
    {
      "type": "filling",
      "teeth": ["16", "17"],
      "date": "2023-01-15"
    }
  ],
  "preferences": {
    "anesthesia_preference": "local",
    "appointment_time": "morning"
  }
}
```

---

### 6. Services Table

**Purpose**: Catalog of dental services and treatments

```sql
CREATE TABLE services (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    category ENUM('general', 'preventive', 'restorative', 'cosmetic', 'orthodontic', 'surgical', 'endodontic', 'periodontal', 'pediatric', 'emergency') NOT NULL,
    duration_minutes INTEGER NOT NULL, -- Estimated duration
    requires_anesthesia BOOLEAN DEFAULT FALSE,
    requires_followup BOOLEAN DEFAULT FALSE,
    is_emergency BOOLEAN DEFAULT FALSE,
    prerequisites JSONB, -- Required examinations, tests
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Unique: `slug`
- Composite: `(category, is_emergency)`
- Single: `category`, `duration_minutes`

---

### 7. Pricing Table

**Purpose**: Service pricing by clinic with currency support

```sql
CREATE TABLE pricing (
    id UUID PRIMARY KEY,
    clinic_id UUID NOT NULL REFERENCES clinics(id) ON DELETE CASCADE,
    service_id UUID NOT NULL REFERENCES services(id) ON DELETE CASCADE,
    base_price DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL, -- ISO 4217 currency code
    discount_percentage DECIMAL(5,2) DEFAULT 0,
    valid_from TIMESTAMP NOT NULL,
    valid_until TIMESTAMP NULL,
    conditions JSONB, -- Special conditions, requirements
    is_negotiable BOOLEAN DEFAULT FALSE,
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    UNIQUE(clinic_id, service_id, valid_from)
);
```

**Indexes**:
- Primary: `id` (UUID)
- Unique: `(clinic_id, service_id, valid_from)`
- Composite: `(clinic_id, service_id)`, `(valid_from, valid_until)`
- Foreign: `clinic_id`, `service_id`
- Single: `currency`

---

### 8. Treatment Requests Table

**Purpose**: Patient treatment requests with symptoms and urgency

```sql
CREATE TABLE treatment_requests (
    id UUID PRIMARY KEY,
    patient_id UUID NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    urgency ENUM('low', 'medium', 'high', 'emergency') NOT NULL,
    symptoms JSONB, -- Pain level, duration, location
    affected_teeth JSONB, -- FDI notation
    photos JSONB, -- URLs to uploaded photos
    status ENUM('pending', 'reviewing', 'quote_requested', 'quoted', 'accepted', 'scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    preferred_date TIMESTAMP,
    preferred_times JSONB, -- Preferred time slots
    is_emergency BOOLEAN DEFAULT FALSE,
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `patient_id`
- Composite: `(status, urgency)`, `(patient_id, status)`
- Single: `status`, `urgency`, `is_emergency`, `preferred_date`

**JSONB Structures**:

`symptoms`:
```json
{
  "pain_level": 7,
  "duration": "3 days",
  "location": "upper right",
  "triggers": ["hot drinks", "cold air"],
  "description": "Sharp pain when chewing"
}
```

`affected_teeth`:
```json
["16", "17"]
```

`photos`:
```json
[
  {
    "url": "https://storage.example.com/photos/abc123.jpg",
    "uploaded_at": "2024-01-15T10:30:00Z"
  }
]
```

---

### 9. Treatment Plans Table

**Purpose**: Doctor-proposed treatment plans with costs and timeline

```sql
CREATE TABLE treatment_plans (
    id UUID PRIMARY KEY,
    treatment_request_id UUID NOT NULL REFERENCES treatment_requests(id) ON DELETE CASCADE,
    doctor_id UUID NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    clinic_id UUID NOT NULL REFERENCES clinics(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    diagnosis TEXT NOT NULL,
    services JSONB, -- Array of service IDs with quantities
    total_cost DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL,
    estimated_duration_days INTEGER NOT NULL,
    number_of_visits INTEGER NOT NULL,
    timeline JSONB, -- Detailed treatment timeline
    pre_treatment_instructions TEXT,
    post_treatment_instructions TEXT,
    risks_and_complications JSONB,
    alternatives JSONB, -- Alternative treatment options
    status ENUM('draft', 'submitted', 'accepted', 'rejected', 'expired') DEFAULT 'draft',
    expires_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `treatment_request_id`, `doctor_id`, `clinic_id`
- Composite: `(doctor_id, status)`, `(clinic_id, status)`
- Single: `status`, `expires_at`

**JSONB Structures**:

`services`:
```json
[
  {
    "service_id": "uuid-here",
    "quantity": 1,
    "price": 150.00,
    "notes": "Upper right molar"
  }
]
```

`timeline`:
```json
{
  "phases": [
    {
      "phase": 1,
      "description": "Initial consultation and x-rays",
      "duration_days": 1,
      "services": ["consultation", "x-ray"]
    },
    {
      "phase": 2,
      "description": "Root canal treatment",
      "duration_days": 7,
      "services": ["root_canal"]
    }
  ]
}
```

---

### 10. Appointments Table

**Purpose**: Scheduled appointments with comprehensive status tracking

```sql
CREATE TABLE appointments (
    id UUID PRIMARY KEY,
    treatment_plan_id UUID NOT NULL REFERENCES treatment_plans(id) ON DELETE CASCADE,
    patient_id UUID NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id UUID NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    clinic_id UUID NOT NULL REFERENCES clinics(id) ON DELETE CASCADE,
    scheduled_at TIMESTAMP NOT NULL,
    duration_minutes INTEGER NOT NULL,
    type ENUM('consultation', 'treatment', 'followup', 'emergency', 'checkup') NOT NULL,
    status ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    preparation_instructions TEXT,
    notes TEXT,
    checked_in_at TIMESTAMP,
    started_at TIMESTAMP,
    completed_at TIMESTAMP,
    cancellation_reason TEXT,
    cancelled_by UUID REFERENCES users(id),
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `treatment_plan_id`, `patient_id`, `doctor_id`, `clinic_id`
- Composite: `(doctor_id, scheduled_at)`, `(clinic_id, scheduled_at)`, `(patient_id, status)`
- Single: `scheduled_at`, `status`

---

### 11. Reviews Table

**Purpose**: Patient reviews and ratings for doctors and clinics

```sql
CREATE TABLE reviews (
    id UUID PRIMARY KEY,
    patient_id UUID NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id UUID NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    clinic_id UUID NOT NULL REFERENCES clinics(id) ON DELETE CASCADE,
    appointment_id UUID REFERENCES appointments(id) ON DELETE SET NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    criteria_ratings JSONB, -- Individual ratings for different aspects
    is_verified BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    published_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL,
    UNIQUE(patient_id, appointment_id)
);
```

**Indexes**:
- Primary: `id` (UUID)
- Unique: `(patient_id, appointment_id)`
- Foreign: `patient_id`, `doctor_id`, `clinic_id`, `appointment_id`
- Composite: `(doctor_id, is_published)`, `(clinic_id, is_published)`
- Single: `rating`, `published_at`

---

### 12. Teeth Reference Table

**Purpose**: FDI tooth coding system reference data

```sql
CREATE TABLE teeth_reference (
    id SERIAL PRIMARY KEY,
    fdi_code CHAR(2) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('incisor', 'canine', 'premolar', 'molar') NOT NULL,
    quadrant ENUM('upper_right', 'upper_left', 'lower_left', 'lower_right') NOT NULL,
    position_in_quadrant INTEGER NOT NULL,
    is_permanent BOOLEAN DEFAULT TRUE,
    description TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

**Indexes**:
- Primary: `id` (Serial)
- Unique: `fdi_code`
- Composite: `(quadrant, position_in_quadrant)`
- Single: `type`, `quadrant`

**FDI Codes**: 11-18, 21-28, 31-38, 41-48 (32 permanent teeth)

---

### 13. Notifications Table

**Purpose**: System notifications for users

```sql
CREATE TABLE notifications (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(255) NOT NULL, -- App\Notifications\* class name
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id UUID NOT NULL,
    data JSONB NOT NULL,
    read_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

**Indexes**:
- Primary: `id` (UUID)
- Foreign: `user_id`
- Composite: `(notifiable_type, notifiable_id)`, `(user_id, read_at)`
- Single: `read_at`

---

## Constraints and Business Rules

### Data Integrity Constraints

1. **Email Uniqueness**: Users must have unique email addresses
2. **License Uniqueness**: Doctors must have unique license numbers
3. **FDI Code Uniqueness**: Teeth reference codes must be unique
4. **Review Limitation**: One review per patient per appointment
5. **Doctor-Clinic Relationship**: Unique doctor-clinic pairs
6. **Price Validity**: Pricing valid_from must be before valid_until

### Business Rules

1. **User Roles**: Users can only have one role (admin, clinic_manager, doctor, patient)
2. **Doctor Verification**: Only verified doctors can create treatment plans
3. **Clinic Verification**: Only verified clinics can receive bookings
4. **Emergency Handling**: Emergency requests bypass normal workflow
5. **Treatment Plan Expiry**: Plans expire automatically if not accepted
6. **Appointment Scheduling**: No double-booking for doctors/clinics

---

## Performance Considerations

### JSONB Indexing

All JSONB columns use GIN indexes for efficient querying:

```sql
CREATE INDEX idx_clinics_operating_hours ON clinics USING GIN (operating_hours);
CREATE INDEX idx_doctors_qualifications ON doctors USING GIN (qualifications);
CREATE INDEX idx_doctors_languages ON doctors USING GIN (languages);
CREATE INDEX idx_patients_medical_history ON patients USING GIN (medical_history);
CREATE INDEX idx_patients_dental_history ON patients USING GIN (dental_history);
CREATE INDEX idx_services_prerequisites ON services USING GIN (prerequisites);
CREATE INDEX idx_pricing_conditions ON pricing USING GIN (conditions);
CREATE INDEX idx_treatment_requests_symptoms ON treatment_requests USING GIN (symptoms);
CREATE INDEX idx_treatment_requests_affected_teeth ON treatment_requests USING GIN (affected_teeth);
CREATE INDEX idx_treatment_requests_photos ON treatment_requests USING GIN (photos);
CREATE INDEX idx_treatment_plans_services ON treatment_plans USING GIN (services);
CREATE INDEX idx_treatment_plans_timeline ON treatment_plans USING GIN (timeline);
CREATE INDEX idx_reviews_criteria_ratings ON reviews USING GIN (criteria_ratings);
CREATE INDEX idx_notifications_data ON notifications USING GIN (data);
```

### Query Optimization

1. **Compound Indexes**: Strategic multi-column indexes for common query patterns
2. **Partial Indexes**: For filtered queries (e.g., active records only)
3. **Foreign Key Indexes**: All foreign keys are indexed for join performance
4. **Timestamp Indexing**: Date-based queries are optimized

---

## Migration Strategy

### Order of Execution

1. `create_users_table` - Base authentication
2. `create_clinics_table` - Clinic management
3. `create_doctors_table` - Doctor profiles
4. `create_doctor_clinics_table` - Doctor-clinic relationships
5. `create_patients_table` - Patient profiles
6. `create_services_table` - Service catalog
7. `create_pricing_table` - Pricing management
8. `create_treatment_requests_table` - Treatment workflow start
9. `create_treatment_plans_table` - Treatment proposals
10. `create_appointments_table` - Appointment scheduling
11. `create_teeth_reference_table` - FDI reference data
12. `create_notifications_table` - System notifications
13. `create_reviews_table` - Review system

### Seeding Strategy

1. **Teeth Reference**: Seed FDI tooth coding system (32 permanent teeth)
2. **Services**: Seed common dental services by category
3. **Test Data**: Create sample users, clinics, and doctors for development

---

## Security Considerations

1. **UUID Primary Keys**: Prevent enumeration attacks
2. **Soft Deletes**: Maintain data integrity for auditing
3. **Email Verification**: Required for account activation
4. **Role-Based Access**: Strict role enforcement at database level
5. **Data Encryption**: Sensitive fields encrypted at application level
6. **Audit Trail**: Activity logging for critical operations

---

This schema supports the complete Ask.Dentist MVP workflow while maintaining clean hexagonal architecture principles and ensuring scalability for future enhancements.
