# Database Schema Documentation

## Overview

The Ask.Dentist platform uses PostgreSQL 17 as the primary database with a normalized relational schema designed for scalability and data integrity.

## Core Tables

### Users Table

The central user authentication and profile table.

```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'patient',
    avatar_url VARCHAR(500) NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_active ON users(is_active);
```

### Doctors Table

Extended profile information for medical professionals.

```sql
CREATE TABLE doctors (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    license_number VARCHAR(100) UNIQUE NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    sub_specialty VARCHAR(100) NULL,
    years_of_experience INTEGER NOT NULL DEFAULT 0,
    consultation_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    follow_up_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    emergency_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    education TEXT NULL,
    certifications TEXT NULL,
    languages JSON NULL,
    clinic_name VARCHAR(255) NULL,
    clinic_address TEXT NULL,
    clinic_phone VARCHAR(20) NULL,
    bio TEXT NULL,
    rating DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    total_reviews INTEGER NOT NULL DEFAULT 0,
    total_consultations INTEGER NOT NULL DEFAULT 0,
    is_verified BOOLEAN NOT NULL DEFAULT false,
    is_available BOOLEAN NOT NULL DEFAULT true,
    verification_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    verification_documents JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_doctors_user_id ON doctors(user_id);
CREATE INDEX idx_doctors_specialty ON doctors(specialty);
CREATE INDEX idx_doctors_verified ON doctors(is_verified);
CREATE INDEX idx_doctors_available ON doctors(is_available);
CREATE INDEX idx_doctors_rating ON doctors(rating);
```

### Patients Table

Additional patient-specific information.

```sql
CREATE TABLE patients (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    date_of_birth DATE NULL,
    gender VARCHAR(20) NULL,
    blood_type VARCHAR(10) NULL,
    allergies TEXT NULL,
    medical_history TEXT NULL,
    emergency_contact_name VARCHAR(255) NULL,
    emergency_contact_phone VARCHAR(20) NULL,
    insurance_provider VARCHAR(255) NULL,
    insurance_number VARCHAR(100) NULL,
    preferred_language VARCHAR(50) NULL DEFAULT 'en',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_patients_user_id ON patients(user_id);
CREATE INDEX idx_patients_gender ON patients(gender);
```

### Doctor Schedules Table

Doctor availability and working hours.

```sql
CREATE TABLE doctor_schedules (
    id BIGSERIAL PRIMARY KEY,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    day_of_week INTEGER NOT NULL, -- 0 = Sunday, 1 = Monday, etc.
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    break_start_time TIME NULL,
    break_end_time TIME NULL,
    slot_duration INTEGER NOT NULL DEFAULT 30, -- minutes
    is_available BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_doctor_schedules_doctor_id ON doctor_schedules(doctor_id);
CREATE INDEX idx_doctor_schedules_day ON doctor_schedules(day_of_week);
CREATE UNIQUE INDEX idx_doctor_schedules_unique ON doctor_schedules(doctor_id, day_of_week);
```

### Doctor Schedule Exceptions Table

Special dates when doctors are not available or have different hours.

```sql
CREATE TABLE doctor_schedule_exceptions (
    id BIGSERIAL PRIMARY KEY,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    exception_date DATE NOT NULL,
    is_available BOOLEAN NOT NULL DEFAULT false,
    start_time TIME NULL,
    end_time TIME NULL,
    reason VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_doctor_exceptions_doctor_id ON doctor_schedule_exceptions(doctor_id);
CREATE INDEX idx_doctor_exceptions_date ON doctor_schedule_exceptions(exception_date);
CREATE UNIQUE INDEX idx_doctor_exceptions_unique ON doctor_schedule_exceptions(doctor_id, exception_date);
```

### Appointments Table

Main appointments table linking patients and doctors.

```sql
CREATE TABLE appointments (
    id BIGSERIAL PRIMARY KEY,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INTEGER NOT NULL DEFAULT 30, -- minutes
    type VARCHAR(50) NOT NULL, -- consultation, follow_up, emergency
    status VARCHAR(50) NOT NULL DEFAULT 'pending', -- pending, confirmed, in_progress, completed, cancelled, no_show
    symptoms TEXT NULL,
    notes TEXT NULL,
    patient_notes TEXT NULL,
    consultation_fee DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    payment_transaction_id VARCHAR(255) NULL,
    cancelled_by VARCHAR(50) NULL, -- patient, doctor, system
    cancellation_reason TEXT NULL,
    cancelled_at TIMESTAMP NULL,
    reminder_sent_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_appointments_patient_id ON appointments(patient_id);
CREATE INDEX idx_appointments_doctor_id ON appointments(doctor_id);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);
CREATE INDEX idx_appointments_payment_status ON appointments(payment_status);
CREATE INDEX idx_appointments_datetime ON appointments(appointment_date, appointment_time);
```

### Consultations Table

Video consultation sessions and details.

```sql
CREATE TABLE consultations (
    id BIGSERIAL PRIMARY KEY,
    appointment_id BIGINT NOT NULL REFERENCES appointments(id) ON DELETE CASCADE,
    agora_channel_name VARCHAR(255) NULL,
    agora_token VARCHAR(500) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'waiting', -- waiting, in_progress, completed, cancelled
    started_at TIMESTAMP NULL,
    ended_at TIMESTAMP NULL,
    duration_minutes INTEGER NULL,
    recording_enabled BOOLEAN NOT NULL DEFAULT false,
    recording_url VARCHAR(500) NULL,
    patient_joined_at TIMESTAMP NULL,
    doctor_joined_at TIMESTAMP NULL,
    connection_quality JSON NULL,
    technical_issues TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_consultations_appointment_id ON consultations(appointment_id);
CREATE INDEX idx_consultations_status ON consultations(status);
CREATE INDEX idx_consultations_channel ON consultations(agora_channel_name);
```

### Medical Records Table

Patient medical records and consultation outcomes.

```sql
CREATE TABLE medical_records (
    id BIGSERIAL PRIMARY KEY,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    consultation_id BIGINT NULL REFERENCES consultations(id) ON DELETE SET NULL,
    record_type VARCHAR(50) NOT NULL, -- consultation, prescription, lab_result, image
    diagnosis TEXT NULL,
    symptoms TEXT NULL,
    treatment_plan TEXT NULL,
    medications JSON NULL,
    follow_up_required BOOLEAN NOT NULL DEFAULT false,
    follow_up_date DATE NULL,
    doctor_notes TEXT NULL,
    patient_notes TEXT NULL,
    attachments JSON NULL,
    is_confidential BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_medical_records_patient_id ON medical_records(patient_id);
CREATE INDEX idx_medical_records_doctor_id ON medical_records(doctor_id);
CREATE INDEX idx_medical_records_consultation_id ON medical_records(consultation_id);
CREATE INDEX idx_medical_records_type ON medical_records(record_type);
CREATE INDEX idx_medical_records_date ON medical_records(created_at);
```

### Prescriptions Table

Digital prescriptions issued by doctors.

```sql
CREATE TABLE prescriptions (
    id BIGSERIAL PRIMARY KEY,
    medical_record_id BIGINT NOT NULL REFERENCES medical_records(id) ON DELETE CASCADE,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    prescription_number VARCHAR(100) UNIQUE NOT NULL,
    medications JSON NOT NULL, -- Array of medication objects
    instructions TEXT NULL,
    pharmacy_notes TEXT NULL,
    validity_days INTEGER NOT NULL DEFAULT 30,
    expires_at TIMESTAMP NOT NULL,
    is_filled BOOLEAN NOT NULL DEFAULT false,
    filled_at TIMESTAMP NULL,
    pharmacy_name VARCHAR(255) NULL,
    digital_signature TEXT NULL,
    qr_code_url VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_prescriptions_medical_record_id ON prescriptions(medical_record_id);
CREATE INDEX idx_prescriptions_patient_id ON prescriptions(patient_id);
CREATE INDEX idx_prescriptions_doctor_id ON prescriptions(doctor_id);
CREATE INDEX idx_prescriptions_number ON prescriptions(prescription_number);
CREATE INDEX idx_prescriptions_expires ON prescriptions(expires_at);
```

### Reviews and Ratings Table

Patient reviews and ratings for doctors.

```sql
CREATE TABLE reviews (
    id BIGSERIAL PRIMARY KEY,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    appointment_id BIGINT NOT NULL REFERENCES appointments(id) ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NULL,
    communication_rating INTEGER NULL CHECK (communication_rating >= 1 AND communication_rating <= 5),
    punctuality_rating INTEGER NULL CHECK (punctuality_rating >= 1 AND punctuality_rating <= 5),
    professionalism_rating INTEGER NULL CHECK (professionalism_rating >= 1 AND professionalism_rating <= 5),
    would_recommend BOOLEAN NOT NULL DEFAULT true,
    is_anonymous BOOLEAN NOT NULL DEFAULT false,
    is_verified BOOLEAN NOT NULL DEFAULT false,
    response_text TEXT NULL,
    response_date TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_reviews_patient_id ON reviews(patient_id);
CREATE INDEX idx_reviews_doctor_id ON reviews(doctor_id);
CREATE INDEX idx_reviews_appointment_id ON reviews(appointment_id);
CREATE INDEX idx_reviews_rating ON reviews(rating);
CREATE UNIQUE INDEX idx_reviews_unique ON reviews(patient_id, appointment_id);
```

### Notifications Table

System notifications for users.

```sql
CREATE TABLE notifications (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL,
    read_at TIMESTAMP NULL,
    action_url VARCHAR(500) NULL,
    priority VARCHAR(20) NOT NULL DEFAULT 'normal', -- low, normal, high, urgent
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_type ON notifications(type);
CREATE INDEX idx_notifications_read ON notifications(read_at);
CREATE INDEX idx_notifications_priority ON notifications(priority);
CREATE INDEX idx_notifications_expires ON notifications(expires_at);
```

### Payments Table

Payment transactions and billing information.

```sql
CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    appointment_id BIGINT NOT NULL REFERENCES appointments(id) ON DELETE CASCADE,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id BIGINT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) NOT NULL DEFAULT 'USD',
    payment_method VARCHAR(50) NOT NULL, -- stripe, paypal, apple_pay, google_pay
    payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(255) NULL,
    payment_intent_id VARCHAR(255) NULL,
    stripe_session_id VARCHAR(255) NULL,
    payment_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    net_amount DECIMAL(10,2) NOT NULL,
    refund_amount DECIMAL(10,2) NULL,
    refund_reason TEXT NULL,
    refunded_at TIMESTAMP NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_payments_appointment_id ON payments(appointment_id);
CREATE INDEX idx_payments_patient_id ON payments(patient_id);
CREATE INDEX idx_payments_doctor_id ON payments(doctor_id);
CREATE INDEX idx_payments_status ON payments(payment_status);
CREATE INDEX idx_payments_transaction_id ON payments(transaction_id);
```

## Supporting Tables

### OAuth Tables (Laravel Passport)

```sql
-- OAuth clients
CREATE TABLE oauth_clients (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL,
    name VARCHAR(255) NOT NULL,
    secret VARCHAR(100) NULL,
    provider VARCHAR(255) NULL,
    redirect TEXT NOT NULL,
    personal_access_client BOOLEAN NOT NULL DEFAULT false,
    password_client BOOLEAN NOT NULL DEFAULT false,
    revoked BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- OAuth access tokens
CREATE TABLE oauth_access_tokens (
    id VARCHAR(100) PRIMARY KEY,
    user_id BIGINT NULL,
    client_id BIGINT NOT NULL,
    name VARCHAR(255) NULL,
    scopes TEXT NULL,
    revoked BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL
);

-- OAuth refresh tokens
CREATE TABLE oauth_refresh_tokens (
    id VARCHAR(100) PRIMARY KEY,
    access_token_id VARCHAR(100) NOT NULL,
    revoked BOOLEAN NOT NULL DEFAULT false,
    expires_at TIMESTAMP NULL
);
```

### Activity Log Table (Spatie/ActivityLog)

```sql
CREATE TABLE activity_log (
    id BIGSERIAL PRIMARY KEY,
    log_name VARCHAR(255) NULL,
    description TEXT NOT NULL,
    subject_type VARCHAR(255) NULL,
    event VARCHAR(255) NULL,
    subject_id BIGINT NULL,
    causer_type VARCHAR(255) NULL,
    causer_id BIGINT NULL,
    properties JSON NULL,
    batch_uuid UUID NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_activity_log_name ON activity_log(log_name);
CREATE INDEX idx_activity_log_subject ON activity_log(subject_type, subject_id);
CREATE INDEX idx_activity_log_causer ON activity_log(causer_type, causer_id);
```

## Database Triggers and Functions

### Update Rating Trigger

Automatically update doctor rating when a new review is added.

```sql
CREATE OR REPLACE FUNCTION update_doctor_rating()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE doctors 
    SET 
        rating = (
            SELECT ROUND(AVG(rating::DECIMAL), 2)
            FROM reviews 
            WHERE doctor_id = NEW.doctor_id
        ),
        total_reviews = (
            SELECT COUNT(*)
            FROM reviews 
            WHERE doctor_id = NEW.doctor_id
        )
    WHERE id = NEW.doctor_id;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_doctor_rating
    AFTER INSERT OR UPDATE ON reviews
    FOR EACH ROW
    EXECUTE FUNCTION update_doctor_rating();
```

### Appointment Slot Validation

Ensure appointment slots don't overlap.

```sql
CREATE OR REPLACE FUNCTION validate_appointment_slot()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM appointments 
        WHERE doctor_id = NEW.doctor_id 
        AND appointment_date = NEW.appointment_date
        AND status NOT IN ('cancelled', 'no_show')
        AND (
            (appointment_time, appointment_time + (duration * INTERVAL '1 minute')) 
            OVERLAPS 
            (NEW.appointment_time, NEW.appointment_time + (NEW.duration * INTERVAL '1 minute'))
        )
        AND (TG_OP = 'INSERT' OR id != NEW.id)
    ) THEN
        RAISE EXCEPTION 'Appointment slot conflicts with existing appointment';
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_validate_appointment_slot
    BEFORE INSERT OR UPDATE ON appointments
    FOR EACH ROW
    EXECUTE FUNCTION validate_appointment_slot();
```

## Data Relationships

### Primary Relationships

1. **Users → Doctors**: One-to-One (optional)
2. **Users → Patients**: One-to-One (optional)
3. **Doctors → Appointments**: One-to-Many
4. **Patients → Appointments**: One-to-Many
5. **Appointments → Consultations**: One-to-One (optional)
6. **Consultations → Medical Records**: One-to-Many
7. **Medical Records → Prescriptions**: One-to-Many
8. **Appointments → Reviews**: One-to-One (optional)
9. **Appointments → Payments**: One-to-Many

### Foreign Key Constraints

All foreign key relationships include appropriate CASCADE or SET NULL actions to maintain referential integrity while allowing for safe data deletion.

## Indexing Strategy

### Primary Indexes

- Primary keys (automatic)
- Foreign keys for join performance
- Frequently queried columns (status, dates, ratings)

### Composite Indexes

- Doctor availability: `(doctor_id, appointment_date, status)`
- User lookup: `(email, role, is_active)`
- Appointment scheduling: `(appointment_date, appointment_time, doctor_id)`

### Full-Text Search

```sql
-- Add full-text search for doctor profiles
ALTER TABLE doctors ADD COLUMN search_vector tsvector;

CREATE INDEX idx_doctors_search ON doctors USING gin(search_vector);

-- Update trigger for search vector
CREATE OR REPLACE FUNCTION update_doctor_search_vector()
RETURNS TRIGGER AS $$
BEGIN
    NEW.search_vector := 
        to_tsvector('english', COALESCE(NEW.specialty, '')) ||
        to_tsvector('english', COALESCE(NEW.sub_specialty, '')) ||
        to_tsvector('english', COALESCE(NEW.bio, '')) ||
        to_tsvector('english', COALESCE(NEW.clinic_name, ''));
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_doctor_search_vector
    BEFORE INSERT OR UPDATE ON doctors
    FOR EACH ROW
    EXECUTE FUNCTION update_doctor_search_vector();
```

## Data Migration Strategy

### Version Control

- All schema changes through Laravel migrations
- Rollback capabilities for each migration
- Seeder files for test data

### Data Integrity

- Foreign key constraints
- Check constraints for data validation
- Unique constraints where appropriate
- NOT NULL constraints for required fields

### Performance Considerations

- Appropriate indexing for query patterns
- Partitioning for large tables (future consideration)
- Connection pooling configuration
- Query optimization monitoring
