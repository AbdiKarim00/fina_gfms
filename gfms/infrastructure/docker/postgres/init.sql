-- PostgreSQL Initialization Script for GFMS
-- Creates schemas, enables extensions, and sets up initial structure

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "postgis";
CREATE EXTENSION IF NOT EXISTS "pg_stat_statements";

-- Create application schemas
CREATE SCHEMA IF NOT EXISTS auth;
CREATE SCHEMA IF NOT EXISTS fleet;
CREATE SCHEMA IF NOT EXISTS maintenance;
CREATE SCHEMA IF NOT EXISTS tracking;
CREATE SCHEMA IF NOT EXISTS finance;
CREATE SCHEMA IF NOT EXISTS integrations;
CREATE SCHEMA IF NOT EXISTS audit;

-- Grant permissions to application user
GRANT ALL PRIVILEGES ON SCHEMA auth TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA fleet TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA maintenance TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA tracking TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA finance TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA integrations TO gfms;
GRANT ALL PRIVILEGES ON SCHEMA audit TO gfms;

-- Set search path to include all schemas
ALTER DATABASE gfms SET search_path TO public, auth, fleet, maintenance, tracking, finance, integrations, audit;

-- Create function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Log initialization
DO $$
BEGIN
    RAISE NOTICE 'GFMS Database initialized successfully';
    RAISE NOTICE 'PostGIS version: %', PostGIS_Version();
    RAISE NOTICE 'Schemas created: auth, fleet, maintenance, tracking, finance, integrations, audit';
END $$;
