# Authentication & Authorization System Audit
**Date:** December 8, 2025  
**Feature:** Complete Auth System with RBAC + Email OTP

## Current State Analysis

### ✅ What's Working
1. **Basic Authentication**
   - Laravel Sanctum installed and configured
   - Personal access tokens table created
   - Basic login endpoint (`/api/v1/auth/login`)
   - Token-based authentication working
   - User model with HasApiTokens trait

2. **Database Structure**
   - Users table with: id, name, email, phone, id_number, password
   - Soft deletes enabled
   - Email verification field present

3. **Frontend Integration**
   - Login page styled and functional
   - Auth context with token management
   - Protected routes working
   - API client with token interceptors

### ❌ What's Missing

#### 1. **Personal Number (UPN) Authentication**
- **Current:** Login uses email + password
- **Required:** Login with Personal Number (UPN) + password
- **Note:** UPN = Unique Personal Number for government public servants
- **Gap:** No validation for UPN format
- **Gap:** No unique constraint enforcement on personal_number field

#### 2. **Email OTP Verification**
- **Current:** No OTP system
- **Required:** Email OTP after successful password validation
- **Gap:** No OTP generation logic
- **Gap:** No OTP storage (table/cache)
- **Gap:** No OTP expiration handling
- **Gap:** No email sending service configured

#### 3. **RBAC (Role-Based Access Control)**
- **Current:** No roles or permissions system
- **Required:** Full RBAC with Spatie Laravel Permission
- **Gap:** No roles table
- **Gap:** No permissions table
- **Gap:** No role-permission pivot tables
- **Gap:** No user-role assignments
- **Gap:** No middleware for permission checking

#### 4. **Organization/MDAC Structure**
- **Current:** No organization linkage
- **Required:** Users belong to Ministries/Departments/Counties
- **Gap:** No organizations table
- **Gap:** No user-organization relationship

#### 5. **Security Features**
- **Gap:** No rate limiting on login attempts
- **Gap:** No account lockout after failed attempts
- **Gap:** No password reset flow
- **Gap:** No session management
- **Gap:** No audit logging for auth events

## Multi-Tenancy Approach

**Decision: Organization-Scoped Data (Soft Multi-Tenancy)**

This system uses **organization-scoped data access** rather than full database-level multi-tenancy:

### Why This Approach?
1. **Shared Database:** All organizations share the same database for easier maintenance and reporting
2. **Data Isolation:** Users can only access data from their assigned organization (unless Super Admin)
3. **Cross-Organization Reporting:** GFMD officials can generate national reports across all organizations
4. **Simpler Architecture:** No need for separate databases or schemas per organization
5. **Cost-Effective:** Single infrastructure for all government entities

### Implementation Strategy
- Every data table has an `organization_id` foreign key
- Query scopes automatically filter by user's organization
- Global scopes on models enforce organization boundaries
- Super Admins bypass organization filters
- Audit logs track cross-organization access

### Example:
```php
// Automatic organization scoping
Vehicle::all(); // Returns only vehicles from user's organization

// Super Admin can access all
Vehicle::withoutGlobalScope('organization')->get();
```

## Required System Architecture

### Authentication Flow (Personal Number/UPN + Password + Email OTP)

```
1. User enters Personal Number (UPN) + Password
   ↓
2. System validates Personal Number exists and is unique
   ↓
3. System checks credentials against database
   ↓
4. If valid → Generate 6-digit OTP
   ↓
5. Store OTP in cache (Redis) with 5-minute expiration
   ↓
6. Send OTP to user's email
   ↓
7. User enters OTP
   ↓
8. System validates OTP
   ↓
9. If valid → Issue Sanctum token
   ↓
10. Return user data + token + roles/permissions
```

### RBAC Structure

**Roles:**
1. **Super Admin** - Full system access (GFMD officials)
2. **Admin** - Ministry/County admin
3. **Fleet Manager** - Manage vehicles & drivers
4. **Transport Officer** - Create work tickets, manage bookings
5. **Finance Officer** - View budgets, approve expenses
6. **Driver** - Mobile app access, submit GP55
7. **CMTE Inspector** - View inspection data
8. **Viewer** - Read-only access

**Permission Categories:**
- **Vehicles:** view, create, edit, delete, assign
- **Drivers:** view, create, edit, delete, suspend
- **Bookings:** view, create, approve, cancel
- **Maintenance:** view, create, approve
- **Fuel:** view, create, approve
- **Reports:** view, export, generate
- **Users:** view, create, edit, delete
- **Organizations:** view, create, edit

### Database Schema Required

```sql
-- Organizations (MDACs)
CREATE TABLE organizations (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('ministry', 'department', 'agency', 'county'),
    parent_id UUID REFERENCES organizations(id),
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Update users table
ALTER TABLE users ADD COLUMN personal_number VARCHAR(50) UNIQUE NOT NULL; -- UPN
ALTER TABLE users ADD COLUMN organization_id UUID REFERENCES organizations(id);
ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT true;
ALTER TABLE users ADD COLUMN last_login_at TIMESTAMP;
ALTER TABLE users ADD COLUMN failed_login_attempts INT DEFAULT 0;
ALTER TABLE users ADD COLUMN locked_until TIMESTAMP;
ALTER TABLE users DROP COLUMN id_number; -- Replace with personal_number

-- OTP Codes (or use Redis)
CREATE TABLE otp_codes (
    id UUID PRIMARY KEY,
    user_id BIGINT REFERENCES users(id),
    code VARCHAR(6) NOT NULL,
    type ENUM('login', 'password_reset'),
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP,
    created_at TIMESTAMP
);

-- Spatie Permission Tables (auto-generated)
-- roles, permissions, model_has_roles, model_has_permissions, role_has_permissions
```

## Recommended Approach

### Phase 1: Database & Models (Priority 1)
1. Create organizations migration & model
2. Update users migration (add organization_id, is_active, etc.)
3. Install & configure Spatie Laravel Permission
4. Create roles & permissions seeder
5. Create OTP codes table/cache structure

### Phase 2: Authentication Logic (Priority 1)
1. Update AuthController for ID number login
2. Implement OTP generation & validation
3. Configure email service (MailHog for dev)
4. Add rate limiting middleware
5. Add account lockout logic

### Phase 3: Authorization (RBAC) (Priority 2)
1. Create role & permission management endpoints
2. Add middleware for permission checking
3. Update User model with role relationships
4. Create authorization policies

### Phase 4: Frontend Integration (Priority 2)
1. Update login form (ID number instead of email)
2. Create OTP verification page
3. Add role-based UI rendering
4. Add permission-based feature flags

### Phase 5: Security Hardening (Priority 3)
1. Add audit logging (Spatie Activity Log)
2. Implement password reset flow
3. Add session management
4. Add security headers
5. Implement CSRF protection

## Tech Stack Confirmation

✅ **Laravel Sanctum** - API token authentication  
✅ **Spatie Laravel Permission** - RBAC system  
✅ **Redis** - OTP storage & caching  
✅ **MailHog** - Email testing (dev)  
✅ **Spatie Activity Log** - Audit trails  

## Next Steps

1. **Create spec file** with detailed requirements
2. **Build one feature at a time** starting with:
   - Organizations & user-organization relationship
   - ID number authentication
   - Email OTP system
   - RBAC implementation

## Compliance Notes

- **Data Protection Act:** Secure storage of personal ID numbers
- **PFM Act:** Audit trails for all auth events
- **GFMD Policy:** Role-based access aligned with government hierarchy
