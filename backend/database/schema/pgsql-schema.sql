--
-- PostgreSQL database dump
--

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.4 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: activity_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_log (
    id bigint NOT NULL,
    log_name character varying(255),
    description text NOT NULL,
    subject_type character varying(255),
    subject_id uuid,
    causer_type character varying(255),
    causer_id uuid,
    properties json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event character varying(255),
    batch_uuid uuid
);


--
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- Name: appointments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.appointments (
    id uuid NOT NULL,
    treatment_plan_id uuid NOT NULL,
    patient_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    scheduled_at timestamp(0) without time zone NOT NULL,
    duration_minutes integer NOT NULL,
    type character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'scheduled'::character varying NOT NULL,
    preparation_instructions text,
    notes text,
    checked_in_at timestamp(0) without time zone,
    started_at timestamp(0) without time zone,
    completed_at timestamp(0) without time zone,
    cancellation_reason text,
    cancelled_by uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT appointments_status_check CHECK (((status)::text = ANY ((ARRAY['scheduled'::character varying, 'confirmed'::character varying, 'in_progress'::character varying, 'completed'::character varying, 'cancelled'::character varying, 'no_show'::character varying])::text[]))),
    CONSTRAINT appointments_type_check CHECK (((type)::text = ANY ((ARRAY['consultation'::character varying, 'treatment'::character varying, 'followup'::character varying, 'emergency'::character varying, 'checkup'::character varying])::text[])))
);


--
-- Name: audit_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.audit_logs (
    id uuid NOT NULL,
    user_id uuid,
    action character varying(255) NOT NULL,
    model_type character varying(255),
    model_id uuid,
    ip_address inet NOT NULL,
    user_agent text NOT NULL,
    old_values jsonb,
    new_values jsonb,
    metadata jsonb,
    request_method character varying(255),
    request_url character varying(255),
    session_id character varying(255),
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: before_after_cases; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.before_after_cases (
    id bigint NOT NULL,
    doctor_id uuid NOT NULL,
    clinic_id uuid,
    title character varying(255) NOT NULL,
    description text,
    before_path character varying(255) NOT NULL,
    after_path character varying(255) NOT NULL,
    tags json,
    treatment_type character varying(255),
    duration_days integer,
    procedure_details text,
    cost_range character varying(255),
    is_featured boolean DEFAULT false NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    is_approved boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT before_after_cases_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'published'::character varying, 'archived'::character varying])::text[])))
);


--
-- Name: before_after_cases_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.before_after_cases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: before_after_cases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.before_after_cases_id_seq OWNED BY public.before_after_cases.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: clinic_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clinic_documents (
    id bigint NOT NULL,
    clinic_id uuid NOT NULL,
    document_type character varying(255) NOT NULL,
    document_name character varying(255) NOT NULL,
    file_path character varying(255) NOT NULL,
    file_type character varying(255) NOT NULL,
    file_size integer NOT NULL,
    issue_date date,
    expiry_date date,
    verification_status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    verification_notes text,
    verified_at timestamp(0) without time zone,
    verified_by uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT clinic_documents_verification_status_check CHECK (((verification_status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: clinic_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clinic_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clinic_documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clinic_documents_id_seq OWNED BY public.clinic_documents.id;


--
-- Name: clinic_working_hours; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clinic_working_hours (
    id bigint NOT NULL,
    clinic_id uuid NOT NULL,
    day_of_week character varying(255) NOT NULL,
    opening_time time(0) without time zone NOT NULL,
    closing_time time(0) without time zone NOT NULL,
    break_start time(0) without time zone,
    break_end time(0) without time zone,
    is_closed boolean DEFAULT false NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT clinic_working_hours_day_of_week_check CHECK (((day_of_week)::text = ANY ((ARRAY['monday'::character varying, 'tuesday'::character varying, 'wednesday'::character varying, 'thursday'::character varying, 'friday'::character varying, 'saturday'::character varying, 'sunday'::character varying])::text[])))
);


--
-- Name: clinic_working_hours_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clinic_working_hours_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clinic_working_hours_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clinic_working_hours_id_seq OWNED BY public.clinic_working_hours.id;


--
-- Name: clinics; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clinics (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    country character varying(2) NOT NULL,
    city character varying(255) NOT NULL,
    address text NOT NULL,
    phone character varying(255) NOT NULL,
    email character varying(255),
    website character varying(255),
    description text,
    operating_hours jsonb,
    latitude numeric(10,8),
    longitude numeric(11,8),
    verified_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    verification_notes text,
    is_promoted boolean DEFAULT false NOT NULL,
    promoted_until timestamp(0) without time zone,
    rating_avg numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    rating_count integer DEFAULT 0 NOT NULL,
    cover_path character varying(255)
);


--
-- Name: doctor_clinics; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctor_clinics (
    id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    role character varying(255) NOT NULL,
    schedule jsonb,
    started_at timestamp(0) without time zone NOT NULL,
    ended_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT doctor_clinics_role_check CHECK (((role)::text = ANY ((ARRAY['associate'::character varying, 'partner'::character varying, 'owner'::character varying])::text[])))
);


--
-- Name: doctors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctors (
    id uuid NOT NULL,
    user_id uuid NOT NULL,
    license_number character varying(255) NOT NULL,
    specialty character varying(255) NOT NULL,
    bio text,
    qualifications jsonb,
    years_experience integer DEFAULT 0 NOT NULL,
    languages jsonb,
    rating numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    total_reviews integer DEFAULT 0 NOT NULL,
    accepts_emergency boolean DEFAULT false NOT NULL,
    verified_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    verification_notes text,
    is_promoted boolean DEFAULT false NOT NULL,
    promoted_until timestamp(0) without time zone,
    rating_avg numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    rating_count integer DEFAULT 0 NOT NULL,
    cover_path character varying(255),
    CONSTRAINT doctors_specialty_check CHECK (((specialty)::text = ANY ((ARRAY['general'::character varying, 'orthodontics'::character varying, 'oral_surgery'::character varying, 'endodontics'::character varying, 'periodontics'::character varying, 'prosthodontics'::character varying, 'pediatric'::character varying, 'cosmetic'::character varying, 'implantology'::character varying])::text[])))
);


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: favorite_clinics; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.favorite_clinics (
    id bigint NOT NULL,
    patient_id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: favorite_clinics_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.favorite_clinics_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: favorite_clinics_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.favorite_clinics_id_seq OWNED BY public.favorite_clinics.id;


--
-- Name: favorite_doctors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.favorite_doctors (
    id bigint NOT NULL,
    patient_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: favorite_doctors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.favorite_doctors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: favorite_doctors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.favorite_doctors_id_seq OWNED BY public.favorite_doctors.id;


--
-- Name: favorites; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.favorites (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    favorable_type character varying(255) NOT NULL,
    favorable_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: favorites_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.favorites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: favorites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.favorites_id_seq OWNED BY public.favorites.id;


--
-- Name: files; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.files (
    id bigint NOT NULL,
    uploader_id uuid NOT NULL,
    path character varying(255) NOT NULL,
    disk character varying(50) DEFAULT 'public'::character varying NOT NULL,
    mime_type character varying(255) NOT NULL,
    size bigint NOT NULL,
    signed_expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: files_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.files_id_seq OWNED BY public.files.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: medical_files; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.medical_files (
    id uuid NOT NULL,
    original_name character varying(255) NOT NULL,
    filename character varying(255) NOT NULL,
    file_path character varying(255) NOT NULL,
    file_size bigint NOT NULL,
    mime_type character varying(255) NOT NULL,
    file_hash character varying(64),
    uploaded_by uuid NOT NULL,
    related_to_type character varying(255),
    related_to_id uuid,
    file_category character varying(255) NOT NULL,
    access_level character varying(255) NOT NULL,
    virus_scan_status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    virus_scan_result text,
    expiry_date timestamp(0) without time zone,
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT medical_files_access_level_check CHECK (((access_level)::text = ANY ((ARRAY['private'::character varying, 'clinic'::character varying, 'doctor'::character varying, 'patient'::character varying])::text[]))),
    CONSTRAINT medical_files_file_category_check CHECK (((file_category)::text = ANY ((ARRAY['xray'::character varying, 'photo'::character varying, 'document'::character varying, 'report'::character varying, 'treatment_plan'::character varying, 'prescription'::character varying])::text[]))),
    CONSTRAINT medical_files_virus_scan_status_check CHECK (((virus_scan_status)::text = ANY ((ARRAY['pending'::character varying, 'scanning'::character varying, 'clean'::character varying, 'infected'::character varying, 'failed'::character varying])::text[])))
);


--
-- Name: messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.messages (
    id bigint NOT NULL,
    from_user_id uuid NOT NULL,
    to_user_id uuid NOT NULL,
    request_id uuid,
    body text NOT NULL,
    attachments_json json,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    type character varying(255) DEFAULT 'text'::character varying NOT NULL,
    attachment_path character varying(255)
);


--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.messages_id_seq OWNED BY public.messages.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- Name: module_toggles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.module_toggles (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    enabled boolean DEFAULT true NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: module_toggles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.module_toggles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: module_toggles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.module_toggles_id_seq OWNED BY public.module_toggles.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    user_id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id uuid NOT NULL,
    data jsonb NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_access_tokens (
    id character(80) NOT NULL,
    user_id uuid,
    client_id uuid NOT NULL,
    name character varying(255),
    scopes text,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_auth_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_auth_codes (
    id character(80) NOT NULL,
    user_id uuid NOT NULL,
    client_id uuid NOT NULL,
    scopes text,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_clients (
    id uuid NOT NULL,
    owner_type character varying(255),
    owner_id bigint,
    name character varying(255) NOT NULL,
    secret character varying(255),
    provider character varying(255),
    redirect_uris text NOT NULL,
    grant_types text NOT NULL,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: oauth_device_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_device_codes (
    id character(80) NOT NULL,
    user_id uuid,
    client_id uuid NOT NULL,
    user_code character(8) NOT NULL,
    scopes text NOT NULL,
    revoked boolean NOT NULL,
    user_approved_at timestamp(0) without time zone,
    last_polled_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_refresh_tokens (
    id character(80) NOT NULL,
    access_token_id character(80) NOT NULL,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: patients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.patients (
    id uuid NOT NULL,
    user_id uuid NOT NULL,
    date_of_birth date NOT NULL,
    gender character varying(255) NOT NULL,
    emergency_contact_name character varying(255),
    emergency_contact_phone character varying(255),
    insurance_provider character varying(255),
    insurance_number character varying(255),
    medical_history jsonb,
    dental_history jsonb,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT patients_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying, 'other'::character varying, 'prefer_not_to_say'::character varying])::text[])))
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: pricing; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pricing (
    id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    service_id uuid NOT NULL,
    base_price numeric(10,2) NOT NULL,
    currency character varying(3) NOT NULL,
    discount_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    valid_from timestamp(0) without time zone NOT NULL,
    valid_until timestamp(0) without time zone,
    conditions jsonb,
    is_negotiable boolean DEFAULT false NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    tooth_modifier jsonb
);


--
-- Name: profiles_doctor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profiles_doctor (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    clinic_id uuid,
    specialty character varying(255) NOT NULL,
    bio text,
    licenses_json json,
    rating numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    response_time_sec integer DEFAULT 3600 NOT NULL,
    active_patients_count integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: profiles_doctor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.profiles_doctor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: profiles_doctor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.profiles_doctor_id_seq OWNED BY public.profiles_doctor.id;


--
-- Name: profiles_patient; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.profiles_patient (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    dob date,
    gender character varying(255),
    address_json json,
    medical_conditions_json json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT profiles_patient_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying, 'other'::character varying, 'prefer_not_to_say'::character varying])::text[])))
);


--
-- Name: profiles_patient_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.profiles_patient_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: profiles_patient_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.profiles_patient_id_seq OWNED BY public.profiles_patient.id;


--
-- Name: request_doctor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.request_doctor (
    id bigint NOT NULL,
    request_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    sent_at timestamp(0) without time zone NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT request_doctor_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'responded'::character varying, 'declined'::character varying])::text[])))
);


--
-- Name: request_doctor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.request_doctor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: request_doctor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.request_doctor_id_seq OWNED BY public.request_doctor.id;


--
-- Name: review_questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.review_questions (
    id bigint NOT NULL,
    question_text character varying(255) NOT NULL,
    question_type character varying(255) DEFAULT 'rating'::character varying NOT NULL,
    options json,
    weights json,
    is_required boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    category character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: review_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.review_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: review_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.review_questions_id_seq OWNED BY public.review_questions.id;


--
-- Name: reviews; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.reviews (
    id uuid NOT NULL,
    patient_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    appointment_id uuid,
    rating integer NOT NULL,
    comment text,
    criteria_ratings jsonb,
    is_verified boolean DEFAULT false NOT NULL,
    is_published boolean DEFAULT true NOT NULL,
    published_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.services (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text NOT NULL,
    category character varying(255) NOT NULL,
    duration_minutes integer NOT NULL,
    requires_anesthesia boolean DEFAULT false NOT NULL,
    requires_followup boolean DEFAULT false NOT NULL,
    is_emergency boolean DEFAULT false NOT NULL,
    prerequisites jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    is_tooth_specific boolean DEFAULT false NOT NULL,
    CONSTRAINT services_category_check CHECK (((category)::text = ANY ((ARRAY['general'::character varying, 'preventive'::character varying, 'restorative'::character varying, 'cosmetic'::character varying, 'orthodontic'::character varying, 'surgical'::character varying, 'endodontic'::character varying, 'periodontal'::character varying, 'pediatric'::character varying, 'emergency'::character varying])::text[])))
);


--
-- Name: stories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.stories (
    id bigint NOT NULL,
    owner_type character varying(255) NOT NULL,
    owner_id uuid NOT NULL,
    media json NOT NULL,
    caption text,
    lang character varying(5) DEFAULT 'en'::character varying NOT NULL,
    starts_at timestamp(0) without time zone NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL,
    is_ad boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT stories_owner_type_check CHECK (((owner_type)::text = ANY ((ARRAY['clinic'::character varying, 'doctor'::character varying])::text[])))
);


--
-- Name: stories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.stories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: stories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.stories_id_seq OWNED BY public.stories.id;


--
-- Name: teeth_reference; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teeth_reference (
    id bigint NOT NULL,
    fdi_code character varying(2) NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    quadrant character varying(255) NOT NULL,
    position_in_quadrant integer NOT NULL,
    is_permanent boolean DEFAULT true NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT teeth_reference_quadrant_check CHECK (((quadrant)::text = ANY ((ARRAY['upper_right'::character varying, 'upper_left'::character varying, 'lower_left'::character varying, 'lower_right'::character varying])::text[]))),
    CONSTRAINT teeth_reference_type_check CHECK (((type)::text = ANY ((ARRAY['incisor'::character varying, 'canine'::character varying, 'premolar'::character varying, 'molar'::character varying])::text[])))
);


--
-- Name: teeth_reference_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teeth_reference_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teeth_reference_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teeth_reference_id_seq OWNED BY public.teeth_reference.id;


--
-- Name: treatment_plans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.treatment_plans (
    id uuid NOT NULL,
    treatment_request_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    clinic_id uuid NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    diagnosis text NOT NULL,
    services jsonb,
    total_cost numeric(10,2) NOT NULL,
    currency character varying(3) NOT NULL,
    estimated_duration_days integer NOT NULL,
    number_of_visits integer NOT NULL,
    timeline jsonb,
    pre_treatment_instructions text,
    post_treatment_instructions text,
    risks_and_complications jsonb,
    alternatives jsonb,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    expires_at timestamp(0) without time zone,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    cancelled_at timestamp(0) without time zone,
    cancellation_reason character varying(255),
    CONSTRAINT treatment_plans_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'accepted'::character varying, 'rejected'::character varying, 'expired'::character varying])::text[])))
);


--
-- Name: treatment_request_doctors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.treatment_request_doctors (
    id bigint NOT NULL,
    treatment_request_id uuid NOT NULL,
    doctor_id uuid NOT NULL,
    dispatch_order integer NOT NULL,
    dispatch_score real NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notified_at timestamp(0) without time zone,
    responded_at timestamp(0) without time zone,
    decline_reason text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT treatment_request_doctors_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'accepted'::character varying, 'declined'::character varying, 'expired'::character varying])::text[])))
);


--
-- Name: COLUMN treatment_request_doctors.dispatch_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.treatment_request_doctors.dispatch_order IS 'Order in which doctor was selected (1-5)';


--
-- Name: COLUMN treatment_request_doctors.dispatch_score; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.treatment_request_doctors.dispatch_score IS 'Score used for selection';


--
-- Name: treatment_request_doctors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.treatment_request_doctors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: treatment_request_doctors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.treatment_request_doctors_id_seq OWNED BY public.treatment_request_doctors.id;


--
-- Name: treatment_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.treatment_requests (
    id uuid NOT NULL,
    patient_id uuid NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    urgency character varying(255) NOT NULL,
    symptoms jsonb,
    affected_teeth jsonb,
    photos jsonb,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    preferred_date timestamp(0) without time zone,
    preferred_times jsonb,
    is_emergency boolean DEFAULT false NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT treatment_requests_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'reviewing'::character varying, 'quote_requested'::character varying, 'quoted'::character varying, 'accepted'::character varying, 'scheduled'::character varying, 'in_progress'::character varying, 'completed'::character varying, 'cancelled'::character varying])::text[]))),
    CONSTRAINT treatment_requests_urgency_check CHECK (((urgency)::text = ANY ((ARRAY['low'::character varying, 'medium'::character varying, 'high'::character varying, 'emergency'::character varying])::text[])))
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    phone character varying(255),
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'patient'::character varying NOT NULL,
    locale character varying(5) DEFAULT 'en'::character varying NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    phone_verified_at timestamp(0) without time zone,
    fcm_tokens json,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'clinic_manager'::character varying, 'doctor'::character varying, 'patient'::character varying])::text[]))),
    CONSTRAINT users_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'active'::character varying, 'suspended'::character varying, 'inactive'::character varying])::text[])))
);


--
-- Name: verification_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.verification_tokens (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    type character varying(255) NOT NULL,
    token character varying(6) NOT NULL,
    contact character varying(255) NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL,
    verified_at timestamp(0) without time zone,
    attempts integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT verification_tokens_type_check CHECK (((type)::text = ANY ((ARRAY['email'::character varying, 'phone'::character varying])::text[])))
);


--
-- Name: verification_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.verification_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: verification_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.verification_tokens_id_seq OWNED BY public.verification_tokens.id;


--
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- Name: before_after_cases id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.before_after_cases ALTER COLUMN id SET DEFAULT nextval('public.before_after_cases_id_seq'::regclass);


--
-- Name: clinic_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_documents ALTER COLUMN id SET DEFAULT nextval('public.clinic_documents_id_seq'::regclass);


--
-- Name: clinic_working_hours id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_working_hours ALTER COLUMN id SET DEFAULT nextval('public.clinic_working_hours_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: favorite_clinics id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_clinics ALTER COLUMN id SET DEFAULT nextval('public.favorite_clinics_id_seq'::regclass);


--
-- Name: favorite_doctors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_doctors ALTER COLUMN id SET DEFAULT nextval('public.favorite_doctors_id_seq'::regclass);


--
-- Name: favorites id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorites ALTER COLUMN id SET DEFAULT nextval('public.favorites_id_seq'::regclass);


--
-- Name: files id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.files ALTER COLUMN id SET DEFAULT nextval('public.files_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messages ALTER COLUMN id SET DEFAULT nextval('public.messages_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: module_toggles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.module_toggles ALTER COLUMN id SET DEFAULT nextval('public.module_toggles_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: profiles_doctor id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_doctor ALTER COLUMN id SET DEFAULT nextval('public.profiles_doctor_id_seq'::regclass);


--
-- Name: profiles_patient id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_patient ALTER COLUMN id SET DEFAULT nextval('public.profiles_patient_id_seq'::regclass);


--
-- Name: request_doctor id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.request_doctor ALTER COLUMN id SET DEFAULT nextval('public.request_doctor_id_seq'::regclass);


--
-- Name: review_questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.review_questions ALTER COLUMN id SET DEFAULT nextval('public.review_questions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: stories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stories ALTER COLUMN id SET DEFAULT nextval('public.stories_id_seq'::regclass);


--
-- Name: teeth_reference id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teeth_reference ALTER COLUMN id SET DEFAULT nextval('public.teeth_reference_id_seq'::regclass);


--
-- Name: treatment_request_doctors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_request_doctors ALTER COLUMN id SET DEFAULT nextval('public.treatment_request_doctors_id_seq'::regclass);


--
-- Name: verification_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_tokens ALTER COLUMN id SET DEFAULT nextval('public.verification_tokens_id_seq'::regclass);


--
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- Name: appointments appointments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_pkey PRIMARY KEY (id);


--
-- Name: audit_logs audit_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_pkey PRIMARY KEY (id);


--
-- Name: before_after_cases before_after_cases_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.before_after_cases
    ADD CONSTRAINT before_after_cases_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: clinic_documents clinic_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_documents
    ADD CONSTRAINT clinic_documents_pkey PRIMARY KEY (id);


--
-- Name: clinic_working_hours clinic_working_hours_clinic_id_day_of_week_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_working_hours
    ADD CONSTRAINT clinic_working_hours_clinic_id_day_of_week_unique UNIQUE (clinic_id, day_of_week);


--
-- Name: clinic_working_hours clinic_working_hours_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_working_hours
    ADD CONSTRAINT clinic_working_hours_pkey PRIMARY KEY (id);


--
-- Name: clinics clinics_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinics
    ADD CONSTRAINT clinics_pkey PRIMARY KEY (id);


--
-- Name: doctor_clinics doctor_clinics_doctor_id_clinic_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctor_clinics
    ADD CONSTRAINT doctor_clinics_doctor_id_clinic_id_unique UNIQUE (doctor_id, clinic_id);


--
-- Name: doctor_clinics doctor_clinics_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctor_clinics
    ADD CONSTRAINT doctor_clinics_pkey PRIMARY KEY (id);


--
-- Name: doctors doctors_license_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctors
    ADD CONSTRAINT doctors_license_number_unique UNIQUE (license_number);


--
-- Name: doctors doctors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctors
    ADD CONSTRAINT doctors_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: favorite_clinics favorite_clinics_patient_id_clinic_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_clinics
    ADD CONSTRAINT favorite_clinics_patient_id_clinic_id_unique UNIQUE (patient_id, clinic_id);


--
-- Name: favorite_clinics favorite_clinics_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_clinics
    ADD CONSTRAINT favorite_clinics_pkey PRIMARY KEY (id);


--
-- Name: favorite_doctors favorite_doctors_patient_id_doctor_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_doctors
    ADD CONSTRAINT favorite_doctors_patient_id_doctor_id_unique UNIQUE (patient_id, doctor_id);


--
-- Name: favorite_doctors favorite_doctors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_doctors
    ADD CONSTRAINT favorite_doctors_pkey PRIMARY KEY (id);


--
-- Name: favorites favorites_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_pkey PRIMARY KEY (id);


--
-- Name: favorites favorites_user_id_favorable_type_favorable_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_user_id_favorable_type_favorable_id_unique UNIQUE (user_id, favorable_type, favorable_id);


--
-- Name: files files_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: medical_files medical_files_filename_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medical_files
    ADD CONSTRAINT medical_files_filename_unique UNIQUE (filename);


--
-- Name: medical_files medical_files_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medical_files
    ADD CONSTRAINT medical_files_pkey PRIMARY KEY (id);


--
-- Name: messages messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: module_toggles module_toggles_key_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.module_toggles
    ADD CONSTRAINT module_toggles_key_unique UNIQUE (key);


--
-- Name: module_toggles module_toggles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.module_toggles
    ADD CONSTRAINT module_toggles_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_device_codes oauth_device_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_device_codes
    ADD CONSTRAINT oauth_device_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_device_codes oauth_device_codes_user_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_device_codes
    ADD CONSTRAINT oauth_device_codes_user_code_unique UNIQUE (user_code);


--
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: patients patients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.patients
    ADD CONSTRAINT patients_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: pricing pricing_clinic_id_service_id_valid_from_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pricing
    ADD CONSTRAINT pricing_clinic_id_service_id_valid_from_unique UNIQUE (clinic_id, service_id, valid_from);


--
-- Name: pricing pricing_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pricing
    ADD CONSTRAINT pricing_pkey PRIMARY KEY (id);


--
-- Name: profiles_doctor profiles_doctor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_doctor
    ADD CONSTRAINT profiles_doctor_pkey PRIMARY KEY (id);


--
-- Name: profiles_doctor profiles_doctor_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_doctor
    ADD CONSTRAINT profiles_doctor_user_id_unique UNIQUE (user_id);


--
-- Name: profiles_patient profiles_patient_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_patient
    ADD CONSTRAINT profiles_patient_pkey PRIMARY KEY (id);


--
-- Name: profiles_patient profiles_patient_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_patient
    ADD CONSTRAINT profiles_patient_user_id_unique UNIQUE (user_id);


--
-- Name: request_doctor request_doctor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.request_doctor
    ADD CONSTRAINT request_doctor_pkey PRIMARY KEY (id);


--
-- Name: request_doctor request_doctor_request_id_doctor_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.request_doctor
    ADD CONSTRAINT request_doctor_request_id_doctor_id_unique UNIQUE (request_id, doctor_id);


--
-- Name: review_questions review_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.review_questions
    ADD CONSTRAINT review_questions_pkey PRIMARY KEY (id);


--
-- Name: reviews reviews_patient_id_appointment_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_patient_id_appointment_id_unique UNIQUE (patient_id, appointment_id);


--
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: services services_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_slug_unique UNIQUE (slug);


--
-- Name: stories stories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stories
    ADD CONSTRAINT stories_pkey PRIMARY KEY (id);


--
-- Name: teeth_reference teeth_reference_fdi_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teeth_reference
    ADD CONSTRAINT teeth_reference_fdi_code_unique UNIQUE (fdi_code);


--
-- Name: teeth_reference teeth_reference_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teeth_reference
    ADD CONSTRAINT teeth_reference_pkey PRIMARY KEY (id);


--
-- Name: treatment_plans treatment_plans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_plans
    ADD CONSTRAINT treatment_plans_pkey PRIMARY KEY (id);


--
-- Name: treatment_request_doctors treatment_request_doctors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_request_doctors
    ADD CONSTRAINT treatment_request_doctors_pkey PRIMARY KEY (id);


--
-- Name: treatment_request_doctors treatment_request_doctors_treatment_request_id_doctor_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_request_doctors
    ADD CONSTRAINT treatment_request_doctors_treatment_request_id_doctor_id_unique UNIQUE (treatment_request_id, doctor_id);


--
-- Name: treatment_requests treatment_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_requests
    ADD CONSTRAINT treatment_requests_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: verification_tokens verification_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_tokens
    ADD CONSTRAINT verification_tokens_pkey PRIMARY KEY (id);


--
-- Name: activity_log_causer_type_causer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_log_causer_type_causer_id_index ON public.activity_log USING btree (causer_type, causer_id);


--
-- Name: activity_log_log_name_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_log_log_name_index ON public.activity_log USING btree (log_name);


--
-- Name: activity_log_subject_type_subject_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_log_subject_type_subject_id_index ON public.activity_log USING btree (subject_type, subject_id);


--
-- Name: appointments_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_clinic_id_index ON public.appointments USING btree (clinic_id);


--
-- Name: appointments_clinic_id_scheduled_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_clinic_id_scheduled_at_index ON public.appointments USING btree (clinic_id, scheduled_at);


--
-- Name: appointments_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_doctor_id_index ON public.appointments USING btree (doctor_id);


--
-- Name: appointments_doctor_id_scheduled_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_doctor_id_scheduled_at_index ON public.appointments USING btree (doctor_id, scheduled_at);


--
-- Name: appointments_patient_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_patient_id_index ON public.appointments USING btree (patient_id);


--
-- Name: appointments_patient_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_patient_id_status_index ON public.appointments USING btree (patient_id, status);


--
-- Name: appointments_scheduled_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_scheduled_at_index ON public.appointments USING btree (scheduled_at);


--
-- Name: appointments_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_status_index ON public.appointments USING btree (status);


--
-- Name: appointments_treatment_plan_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX appointments_treatment_plan_id_index ON public.appointments USING btree (treatment_plan_id);


--
-- Name: audit_logs_action_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_action_index ON public.audit_logs USING btree (action);


--
-- Name: audit_logs_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_created_at_index ON public.audit_logs USING btree (created_at);


--
-- Name: audit_logs_ip_address_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_ip_address_index ON public.audit_logs USING btree (ip_address);


--
-- Name: audit_logs_model_type_model_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_model_type_model_id_index ON public.audit_logs USING btree (model_type, model_id);


--
-- Name: audit_logs_user_id_action_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_user_id_action_index ON public.audit_logs USING btree (user_id, action);


--
-- Name: audit_logs_user_id_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_user_id_created_at_index ON public.audit_logs USING btree (user_id, created_at);


--
-- Name: audit_logs_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_user_id_index ON public.audit_logs USING btree (user_id);


--
-- Name: before_after_cases_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_clinic_id_index ON public.before_after_cases USING btree (clinic_id);


--
-- Name: before_after_cases_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_doctor_id_index ON public.before_after_cases USING btree (doctor_id);


--
-- Name: before_after_cases_is_featured_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_is_featured_index ON public.before_after_cases USING btree (is_featured);


--
-- Name: before_after_cases_is_featured_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_is_featured_status_index ON public.before_after_cases USING btree (is_featured, status);


--
-- Name: before_after_cases_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_status_index ON public.before_after_cases USING btree (status);


--
-- Name: before_after_cases_status_is_approved_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX before_after_cases_status_is_approved_index ON public.before_after_cases USING btree (status, is_approved);


--
-- Name: clinic_documents_clinic_id_document_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_documents_clinic_id_document_type_index ON public.clinic_documents USING btree (clinic_id, document_type);


--
-- Name: clinic_documents_verification_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_documents_verification_status_index ON public.clinic_documents USING btree (verification_status);


--
-- Name: clinic_working_hours_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_working_hours_clinic_id_index ON public.clinic_working_hours USING btree (clinic_id);


--
-- Name: clinics_active_promotions_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_active_promotions_idx ON public.clinics USING btree (promoted_until) WHERE (promoted_until IS NOT NULL);


--
-- Name: clinics_country_city_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_country_city_index ON public.clinics USING btree (country, city);


--
-- Name: clinics_country_city_verified_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_country_city_verified_at_index ON public.clinics USING btree (country, city, verified_at);


--
-- Name: clinics_is_promoted_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_is_promoted_index ON public.clinics USING btree (is_promoted);


--
-- Name: clinics_is_promoted_promoted_until_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_is_promoted_promoted_until_index ON public.clinics USING btree (is_promoted, promoted_until);


--
-- Name: clinics_promoted_until_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_promoted_until_index ON public.clinics USING btree (promoted_until);


--
-- Name: clinics_rating_avg_rating_count_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_rating_avg_rating_count_index ON public.clinics USING btree (rating_avg, rating_count);


--
-- Name: clinics_verified_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinics_verified_at_index ON public.clinics USING btree (verified_at);


--
-- Name: doctor_clinics_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctor_clinics_clinic_id_index ON public.doctor_clinics USING btree (clinic_id);


--
-- Name: doctor_clinics_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctor_clinics_doctor_id_index ON public.doctor_clinics USING btree (doctor_id);


--
-- Name: doctor_clinics_doctor_id_started_at_ended_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctor_clinics_doctor_id_started_at_ended_at_index ON public.doctor_clinics USING btree (doctor_id, started_at, ended_at);


--
-- Name: doctors_active_promotions_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_active_promotions_idx ON public.doctors USING btree (promoted_until) WHERE (promoted_until IS NOT NULL);


--
-- Name: doctors_is_promoted_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_is_promoted_index ON public.doctors USING btree (is_promoted);


--
-- Name: doctors_is_promoted_promoted_until_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_is_promoted_promoted_until_index ON public.doctors USING btree (is_promoted, promoted_until);


--
-- Name: doctors_promoted_until_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_promoted_until_index ON public.doctors USING btree (promoted_until);


--
-- Name: doctors_rating_avg_rating_count_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_rating_avg_rating_count_index ON public.doctors USING btree (rating_avg, rating_count);


--
-- Name: doctors_rating_total_reviews_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_rating_total_reviews_index ON public.doctors USING btree (rating, total_reviews);


--
-- Name: doctors_specialty_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_specialty_index ON public.doctors USING btree (specialty);


--
-- Name: doctors_specialty_verified_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_specialty_verified_at_index ON public.doctors USING btree (specialty, verified_at);


--
-- Name: doctors_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_user_id_index ON public.doctors USING btree (user_id);


--
-- Name: doctors_verified_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX doctors_verified_at_index ON public.doctors USING btree (verified_at);


--
-- Name: favorite_clinics_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX favorite_clinics_clinic_id_index ON public.favorite_clinics USING btree (clinic_id);


--
-- Name: favorite_clinics_patient_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX favorite_clinics_patient_id_index ON public.favorite_clinics USING btree (patient_id);


--
-- Name: favorite_doctors_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX favorite_doctors_doctor_id_index ON public.favorite_doctors USING btree (doctor_id);


--
-- Name: favorite_doctors_patient_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX favorite_doctors_patient_id_index ON public.favorite_doctors USING btree (patient_id);


--
-- Name: favorites_favorable_type_favorable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX favorites_favorable_type_favorable_id_index ON public.favorites USING btree (favorable_type, favorable_id);


--
-- Name: files_disk_path_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX files_disk_path_index ON public.files USING btree (disk, path);


--
-- Name: files_mime_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX files_mime_type_index ON public.files USING btree (mime_type);


--
-- Name: files_signed_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX files_signed_expires_at_index ON public.files USING btree (signed_expires_at);


--
-- Name: files_uploader_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX files_uploader_id_index ON public.files USING btree (uploader_id);


--
-- Name: idx_treatment_request_status; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_treatment_request_status ON public.treatment_plans USING btree (treatment_request_id, status);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: medical_files_access_level_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_access_level_index ON public.medical_files USING btree (access_level);


--
-- Name: medical_files_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_created_at_index ON public.medical_files USING btree (created_at);


--
-- Name: medical_files_expiry_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_expiry_date_index ON public.medical_files USING btree (expiry_date);


--
-- Name: medical_files_file_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_file_category_index ON public.medical_files USING btree (file_category);


--
-- Name: medical_files_related_to_type_related_to_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_related_to_type_related_to_id_index ON public.medical_files USING btree (related_to_type, related_to_id);


--
-- Name: medical_files_uploaded_by_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_uploaded_by_index ON public.medical_files USING btree (uploaded_by);


--
-- Name: medical_files_virus_scan_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX medical_files_virus_scan_status_index ON public.medical_files USING btree (virus_scan_status);


--
-- Name: messages_from_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_from_user_id_index ON public.messages USING btree (from_user_id);


--
-- Name: messages_from_user_id_to_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_from_user_id_to_user_id_index ON public.messages USING btree (from_user_id, to_user_id);


--
-- Name: messages_read_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_read_at_index ON public.messages USING btree (read_at);


--
-- Name: messages_request_id_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_request_id_created_at_index ON public.messages USING btree (request_id, created_at);


--
-- Name: messages_request_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_request_id_index ON public.messages USING btree (request_id);


--
-- Name: messages_to_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX messages_to_user_id_index ON public.messages USING btree (to_user_id);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: module_toggles_enabled_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX module_toggles_enabled_index ON public.module_toggles USING btree (enabled);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: notifications_read_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_read_at_index ON public.notifications USING btree (read_at);


--
-- Name: notifications_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_user_id_index ON public.notifications USING btree (user_id);


--
-- Name: notifications_user_id_read_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_user_id_read_at_index ON public.notifications USING btree (user_id, read_at);


--
-- Name: oauth_access_tokens_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_access_tokens_user_id_index ON public.oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_auth_codes_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_auth_codes_user_id_index ON public.oauth_auth_codes USING btree (user_id);


--
-- Name: oauth_clients_owner_type_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_clients_owner_type_owner_id_index ON public.oauth_clients USING btree (owner_type, owner_id);


--
-- Name: oauth_device_codes_client_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_device_codes_client_id_index ON public.oauth_device_codes USING btree (client_id);


--
-- Name: oauth_device_codes_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_device_codes_user_id_index ON public.oauth_device_codes USING btree (user_id);


--
-- Name: oauth_refresh_tokens_access_token_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_refresh_tokens_access_token_id_index ON public.oauth_refresh_tokens USING btree (access_token_id);


--
-- Name: password_reset_tokens_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_reset_tokens_email_index ON public.password_reset_tokens USING btree (email);


--
-- Name: password_reset_tokens_email_token_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_reset_tokens_email_token_index ON public.password_reset_tokens USING btree (email, token);


--
-- Name: patients_date_of_birth_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX patients_date_of_birth_index ON public.patients USING btree (date_of_birth);


--
-- Name: patients_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX patients_user_id_index ON public.patients USING btree (user_id);


--
-- Name: pricing_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pricing_clinic_id_index ON public.pricing USING btree (clinic_id);


--
-- Name: pricing_clinic_id_service_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pricing_clinic_id_service_id_index ON public.pricing USING btree (clinic_id, service_id);


--
-- Name: pricing_currency_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pricing_currency_index ON public.pricing USING btree (currency);


--
-- Name: pricing_service_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pricing_service_id_index ON public.pricing USING btree (service_id);


--
-- Name: pricing_valid_from_valid_until_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pricing_valid_from_valid_until_index ON public.pricing USING btree (valid_from, valid_until);


--
-- Name: profiles_doctor_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_doctor_clinic_id_index ON public.profiles_doctor USING btree (clinic_id);


--
-- Name: profiles_doctor_rating_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_doctor_rating_index ON public.profiles_doctor USING btree (rating);


--
-- Name: profiles_doctor_response_time_sec_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_doctor_response_time_sec_index ON public.profiles_doctor USING btree (response_time_sec);


--
-- Name: profiles_doctor_specialty_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_doctor_specialty_index ON public.profiles_doctor USING btree (specialty);


--
-- Name: profiles_patient_dob_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_patient_dob_index ON public.profiles_patient USING btree (dob);


--
-- Name: profiles_patient_gender_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX profiles_patient_gender_index ON public.profiles_patient USING btree (gender);


--
-- Name: request_doctor_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX request_doctor_doctor_id_index ON public.request_doctor USING btree (doctor_id);


--
-- Name: request_doctor_sent_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX request_doctor_sent_at_index ON public.request_doctor USING btree (sent_at);


--
-- Name: request_doctor_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX request_doctor_status_index ON public.request_doctor USING btree (status);


--
-- Name: review_questions_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX review_questions_category_index ON public.review_questions USING btree (category);


--
-- Name: review_questions_is_active_sort_order_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX review_questions_is_active_sort_order_index ON public.review_questions USING btree (is_active, sort_order);


--
-- Name: reviews_appointment_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_appointment_id_index ON public.reviews USING btree (appointment_id);


--
-- Name: reviews_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_clinic_id_index ON public.reviews USING btree (clinic_id);


--
-- Name: reviews_clinic_id_is_published_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_clinic_id_is_published_index ON public.reviews USING btree (clinic_id, is_published);


--
-- Name: reviews_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_doctor_id_index ON public.reviews USING btree (doctor_id);


--
-- Name: reviews_doctor_id_is_published_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_doctor_id_is_published_index ON public.reviews USING btree (doctor_id, is_published);


--
-- Name: reviews_patient_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_patient_id_index ON public.reviews USING btree (patient_id);


--
-- Name: reviews_published_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_published_at_index ON public.reviews USING btree (published_at);


--
-- Name: reviews_rating_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX reviews_rating_index ON public.reviews USING btree (rating);


--
-- Name: services_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX services_category_index ON public.services USING btree (category);


--
-- Name: services_category_is_emergency_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX services_category_is_emergency_index ON public.services USING btree (category, is_emergency);


--
-- Name: services_duration_minutes_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX services_duration_minutes_index ON public.services USING btree (duration_minutes);


--
-- Name: services_slug_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX services_slug_index ON public.services USING btree (slug);


--
-- Name: stories_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stories_expires_at_index ON public.stories USING btree (expires_at);


--
-- Name: stories_is_ad_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stories_is_ad_index ON public.stories USING btree (is_ad);


--
-- Name: stories_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stories_owner_id_index ON public.stories USING btree (owner_id);


--
-- Name: stories_owner_type_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stories_owner_type_owner_id_index ON public.stories USING btree (owner_type, owner_id);


--
-- Name: stories_starts_at_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stories_starts_at_expires_at_index ON public.stories USING btree (starts_at, expires_at);


--
-- Name: teeth_reference_fdi_code_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teeth_reference_fdi_code_index ON public.teeth_reference USING btree (fdi_code);


--
-- Name: teeth_reference_quadrant_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teeth_reference_quadrant_index ON public.teeth_reference USING btree (quadrant);


--
-- Name: teeth_reference_quadrant_position_in_quadrant_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teeth_reference_quadrant_position_in_quadrant_index ON public.teeth_reference USING btree (quadrant, position_in_quadrant);


--
-- Name: teeth_reference_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teeth_reference_type_index ON public.teeth_reference USING btree (type);


--
-- Name: treatment_plans_clinic_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_clinic_id_index ON public.treatment_plans USING btree (clinic_id);


--
-- Name: treatment_plans_clinic_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_clinic_id_status_index ON public.treatment_plans USING btree (clinic_id, status);


--
-- Name: treatment_plans_doctor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_doctor_id_index ON public.treatment_plans USING btree (doctor_id);


--
-- Name: treatment_plans_doctor_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_doctor_id_status_index ON public.treatment_plans USING btree (doctor_id, status);


--
-- Name: treatment_plans_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_expires_at_index ON public.treatment_plans USING btree (expires_at);


--
-- Name: treatment_plans_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_status_index ON public.treatment_plans USING btree (status);


--
-- Name: treatment_plans_treatment_request_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_plans_treatment_request_id_index ON public.treatment_plans USING btree (treatment_request_id);


--
-- Name: treatment_request_doctors_doctor_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_request_doctors_doctor_id_status_index ON public.treatment_request_doctors USING btree (doctor_id, status);


--
-- Name: treatment_request_doctors_status_notified_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_request_doctors_status_notified_at_index ON public.treatment_request_doctors USING btree (status, notified_at);


--
-- Name: treatment_request_doctors_treatment_request_id_dispatch_order_i; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_request_doctors_treatment_request_id_dispatch_order_i ON public.treatment_request_doctors USING btree (treatment_request_id, dispatch_order);


--
-- Name: treatment_requests_is_emergency_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_is_emergency_index ON public.treatment_requests USING btree (is_emergency);


--
-- Name: treatment_requests_patient_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_patient_id_index ON public.treatment_requests USING btree (patient_id);


--
-- Name: treatment_requests_patient_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_patient_id_status_index ON public.treatment_requests USING btree (patient_id, status);


--
-- Name: treatment_requests_preferred_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_preferred_date_index ON public.treatment_requests USING btree (preferred_date);


--
-- Name: treatment_requests_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_status_index ON public.treatment_requests USING btree (status);


--
-- Name: treatment_requests_status_urgency_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_status_urgency_index ON public.treatment_requests USING btree (status, urgency);


--
-- Name: treatment_requests_urgency_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX treatment_requests_urgency_index ON public.treatment_requests USING btree (urgency);


--
-- Name: unique_accepted_plan_per_request; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX unique_accepted_plan_per_request ON public.treatment_plans USING btree (treatment_request_id) WHERE (((status)::text = 'accepted'::text) AND (deleted_at IS NULL));


--
-- Name: users_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_email_index ON public.users USING btree (email);


--
-- Name: users_role_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_role_index ON public.users USING btree (role);


--
-- Name: users_role_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_role_status_index ON public.users USING btree (role, status);


--
-- Name: users_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_status_index ON public.users USING btree (status);


--
-- Name: verification_tokens_contact_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX verification_tokens_contact_type_index ON public.verification_tokens USING btree (contact, type);


--
-- Name: verification_tokens_user_id_type_token_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX verification_tokens_user_id_type_token_index ON public.verification_tokens USING btree (user_id, type, token);


--
-- Name: appointments appointments_cancelled_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_cancelled_by_foreign FOREIGN KEY (cancelled_by) REFERENCES public.users(id);


--
-- Name: appointments appointments_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: appointments appointments_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: appointments appointments_patient_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES public.patients(id) ON DELETE CASCADE;


--
-- Name: appointments appointments_treatment_plan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.appointments
    ADD CONSTRAINT appointments_treatment_plan_id_foreign FOREIGN KEY (treatment_plan_id) REFERENCES public.treatment_plans(id) ON DELETE CASCADE;


--
-- Name: audit_logs audit_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: before_after_cases before_after_cases_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.before_after_cases
    ADD CONSTRAINT before_after_cases_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE SET NULL;


--
-- Name: before_after_cases before_after_cases_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.before_after_cases
    ADD CONSTRAINT before_after_cases_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: clinic_documents clinic_documents_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_documents
    ADD CONSTRAINT clinic_documents_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: clinic_documents clinic_documents_verified_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_documents
    ADD CONSTRAINT clinic_documents_verified_by_foreign FOREIGN KEY (verified_by) REFERENCES public.users(id);


--
-- Name: clinic_working_hours clinic_working_hours_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_working_hours
    ADD CONSTRAINT clinic_working_hours_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: doctor_clinics doctor_clinics_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctor_clinics
    ADD CONSTRAINT doctor_clinics_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: doctor_clinics doctor_clinics_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctor_clinics
    ADD CONSTRAINT doctor_clinics_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: doctors doctors_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctors
    ADD CONSTRAINT doctors_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: favorite_clinics favorite_clinics_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_clinics
    ADD CONSTRAINT favorite_clinics_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: favorite_clinics favorite_clinics_patient_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_clinics
    ADD CONSTRAINT favorite_clinics_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: favorite_doctors favorite_doctors_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_doctors
    ADD CONSTRAINT favorite_doctors_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: favorite_doctors favorite_doctors_patient_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorite_doctors
    ADD CONSTRAINT favorite_doctors_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: favorites favorites_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.favorites
    ADD CONSTRAINT favorites_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: files files_uploader_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT files_uploader_id_foreign FOREIGN KEY (uploader_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: medical_files medical_files_uploaded_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medical_files
    ADD CONSTRAINT medical_files_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: messages messages_from_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_from_user_id_foreign FOREIGN KEY (from_user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: messages messages_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_request_id_foreign FOREIGN KEY (request_id) REFERENCES public.treatment_requests(id) ON DELETE CASCADE;


--
-- Name: messages messages_to_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_to_user_id_foreign FOREIGN KEY (to_user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: notifications notifications_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: patients patients_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.patients
    ADD CONSTRAINT patients_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: pricing pricing_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pricing
    ADD CONSTRAINT pricing_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: pricing pricing_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pricing
    ADD CONSTRAINT pricing_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id) ON DELETE CASCADE;


--
-- Name: profiles_doctor profiles_doctor_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_doctor
    ADD CONSTRAINT profiles_doctor_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE SET NULL;


--
-- Name: profiles_doctor profiles_doctor_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_doctor
    ADD CONSTRAINT profiles_doctor_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: profiles_patient profiles_patient_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.profiles_patient
    ADD CONSTRAINT profiles_patient_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: request_doctor request_doctor_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.request_doctor
    ADD CONSTRAINT request_doctor_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: request_doctor request_doctor_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.request_doctor
    ADD CONSTRAINT request_doctor_request_id_foreign FOREIGN KEY (request_id) REFERENCES public.treatment_requests(id) ON DELETE CASCADE;


--
-- Name: reviews reviews_appointment_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_appointment_id_foreign FOREIGN KEY (appointment_id) REFERENCES public.appointments(id) ON DELETE SET NULL;


--
-- Name: reviews reviews_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: reviews reviews_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: reviews reviews_patient_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES public.patients(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: treatment_plans treatment_plans_clinic_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_plans
    ADD CONSTRAINT treatment_plans_clinic_id_foreign FOREIGN KEY (clinic_id) REFERENCES public.clinics(id) ON DELETE CASCADE;


--
-- Name: treatment_plans treatment_plans_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_plans
    ADD CONSTRAINT treatment_plans_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: treatment_plans treatment_plans_treatment_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_plans
    ADD CONSTRAINT treatment_plans_treatment_request_id_foreign FOREIGN KEY (treatment_request_id) REFERENCES public.treatment_requests(id) ON DELETE CASCADE;


--
-- Name: treatment_request_doctors treatment_request_doctors_doctor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_request_doctors
    ADD CONSTRAINT treatment_request_doctors_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES public.doctors(id) ON DELETE CASCADE;


--
-- Name: treatment_request_doctors treatment_request_doctors_treatment_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_request_doctors
    ADD CONSTRAINT treatment_request_doctors_treatment_request_id_foreign FOREIGN KEY (treatment_request_id) REFERENCES public.treatment_requests(id) ON DELETE CASCADE;


--
-- Name: treatment_requests treatment_requests_patient_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.treatment_requests
    ADD CONSTRAINT treatment_requests_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES public.patients(id) ON DELETE CASCADE;


--
-- Name: verification_tokens verification_tokens_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.verification_tokens
    ADD CONSTRAINT verification_tokens_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.4 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000001_create_cache_table	1
2	0001_01_01_000002_create_jobs_table	1
3	2025_01_01_000001_create_users_table	1
4	2025_01_01_000002_create_clinics_table	1
5	2025_01_01_000003_create_doctors_table	1
6	2025_01_01_000004_create_doctor_clinics_table	1
7	2025_01_01_000005_create_patients_table	1
8	2025_01_01_000006_create_services_table	1
9	2025_01_01_000007_create_pricing_table	1
10	2025_01_01_000008_create_treatment_requests_table	1
11	2025_01_01_000009_create_treatment_plans_table	1
12	2025_01_01_000010_create_appointments_table	1
13	2025_01_01_000011_create_teeth_reference_table	1
14	2025_01_01_000012_create_notifications_table	1
15	2025_01_01_000013_create_reviews_table	1
16	2025_01_01_000014_create_audit_logs_table	1
17	2025_09_04_171457_create_oauth_auth_codes_table	1
18	2025_09_04_171458_create_oauth_access_tokens_table	1
19	2025_09_04_171459_create_oauth_refresh_tokens_table	1
20	2025_09_04_171500_create_oauth_clients_table	1
21	2025_09_04_171501_create_oauth_device_codes_table	1
22	2025_09_04_174741_create_permission_tables	1
23	2025_09_04_222916_create_profiles_patient_table	1
24	2025_09_04_222932_create_profiles_doctor_table	1
25	2025_09_04_222949_create_files_table	1
26	2025_09_04_223029_create_request_doctor_table	1
27	2025_09_04_224414_create_messages_table	1
28	2025_09_04_224518_create_module_toggles_table	1
29	2025_09_04_230644_create_password_reset_tokens_table	1
30	2025_09_04_230658_create_verification_tokens_table	1
31	2025_09_04_230710_add_verification_fields_to_users_table	1
32	2025_09_05_012651_create_medical_files_table	1
33	2025_09_05_095443_create_treatment_request_doctors_table	1
34	2025_09_05_212146_add_is_tooth_specific_to_services_table	1
35	2025_09_05_212150_add_tooth_modifier_to_pricing_table	1
36	2025_09_05_213524_add_cancellation_fields_to_treatment_plans_table	1
37	2025_09_06_123600_create_review_questions_table	1
38	2025_09_06_123607_create_clinic_working_hours_table	1
39	2025_09_06_123611_create_clinic_documents_table	1
40	2025_09_06_204948_add_verification_notes_to_doctors_and_clinics_tables	1
41	2025_09_06_212430_add_unique_accepted_plan_constraint_to_treatment_plans_table	1
42	2025_09_06_230444_add_fcm_tokens_to_users_table	1
43	2025_09_07_101206_add_real_time_fields_to_messages_table	1
44	2025_09_07_230958_create_stories_table	1
45	2025_09_07_231002_create_before_after_cases_table	1
46	2025_09_07_231006_create_favorite_doctors_table	1
47	2025_09_07_231011_create_favorite_clinics_table	1
48	2025_09_07_231015_add_promotion_fields_to_doctors_table	1
49	2025_09_07_231019_add_promotion_fields_to_clinics_table	1
50	2025_09_08_002615_create_favorites_table	1
54	2025_09_09_013319_create_activity_log_table	2
55	2025_09_09_013320_add_event_column_to_activity_log_table	2
56	2025_09_09_013321_add_batch_uuid_column_to_activity_log_table	2
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 56, true);


--
-- PostgreSQL database dump complete
--

