-- PostgreSQL initialization script for Ask.Dentist

-- Create additional databases if needed
-- CREATE DATABASE askdentist_test;

-- Create extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- Create indexes for better performance
-- These will be created by Laravel migrations, but kept here for reference

-- Set timezone
SET timezone = 'UTC';
