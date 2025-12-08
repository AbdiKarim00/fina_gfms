# Implementation Plan: Authentication & Authorization System

## Task 1: Database Foundation - Organizations & User Updates

- [ ] 1.1 Create organizations migration
  - Create `organizations` table with all fields (name, code, type, parent_id, etc.)
  - Add indexes for performance
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [ ] 1.2 Update users migration
  - Add `personal_number` field (unique, not null)
  - Add `organization_id` foreign key
  - Add `is_active`, `last_login_at`, `failed_login_attempts`, `locked_until`
  - Remove or rename `id_number` field
  - _Requirements: 2.1, 2.2, 4.1, 4.2, 6.1, 6.3_

- [ ] 1.3 Create Organization model
  - Define fillable fields and casts
  - Add parent/children relationships
  - Add users relationship
  - Add active scope
  - _Requirements: 1.1, 1.3_

- [ ] 1.4 Update User model
  - Add organization relationship
  - Add global scope for organization filtering
  - Add `isLocked()`, `incrementFailedAttempts()`, `resetFailedAttempts()` methods
  - Update fillable and hidden fields
  - _Requirements: 4.2, 4.3, 4.4, 6.1, 6.2_

- [ ] 1.5 Run migrations
  - Execute migrations
  - Verify tables created correctly
  - _Requirements: All database requirements_

## Task 2: RBAC Setup with Spatie Permission ✅ COMPLETED

- [x] 2.1 Install Spatie Laravel Permission
  - Run `composer require spatie/laravel-permission`
  - Publish configuration and migrations
  - Run permission migrations
  - _Requirements: 5.1, 5.3_

- [x] 2.2 Configure User model for RBAC
  - Add `HasRoles` trait to User model
  - Configure guard name if needed
  - _Requirements: 5.1, 5.2_

- [x] 2.3 Create RolePermissionSeeder
  - Define 8 roles (Super Admin, Admin, Fleet Manager, etc.)
  - Define permissions for each resource
  - Assign permissions to roles
  - _Requirements: 5.1, 5.3, 5.4_

- [x] 2.4 Create OrganizationSeeder
  - Seed 47 counties
  - Seed key ministries (Transport, Health, Education, etc.)
  - Set up hierarchical relationships
  - _Requirements: 1.1, 1.3, 6.3_

- [x] 2.5 Run seeders
  - Execute role/permission seeder
  - Execute organization seeder
  - Verify data in database
  - _Requirements: 5.1, 6.3_

## Task 3: Authentication Services - Personal Number Login ✅ COMPLETED

- [x] 3.1 Create custom exceptions
  - `AuthenticationException`
  - `AccountLockedException`
  - `InactiveAccountException`
  - `InvalidOtpException`
  - _Requirements: 2.4, 4.3, 4.4_

- [x] 3.2 Create UserRepository
  - `findByPersonalNumber()` method
  - `find()` method with organization scoping
  - _Requirements: 2.1, 2.4, 6.2_

- [x] 3.3 Create OtpService
  - `generate()` - create 6-digit OTP, store in Redis with 5-min expiry
  - `verify()` - validate OTP with 3-attempt limit
  - `sendEmail()` - queue email with OTP
  - `sendSms()` - prepared for future SMS implementation
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

- [x] 3.4 Create AuthService
  - `attemptLogin()` - validate personal number + password
  - Check account status (locked, inactive)
  - Increment failed attempts on failure
  - Generate and send OTP on success
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 4.1, 4.2, 4.3_

- [x] 3.5 Add verifyOtp method to AuthService
  - Validate OTP code
  - Reset failed attempts on success
  - Issue Sanctum token
  - Return user with roles/permissions
  - _Requirements: 3.4, 3.5, 3.6, 4.5, 7.1_

- [x] 3.6 Add logout method to AuthService
  - Revoke current token
  - _Requirements: 7.4_

## Task 4: API Controllers & Routes ✅ COMPLETED

- [x] 4.1 Create LoginRequest validation
  - Validate `personal_number` (required, exists in users)
  - Validate `password` (required, min:8)
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 4.2 Create VerifyOtpRequest validation
  - Validate `user_id` (required, exists)
  - Validate `code` (required, digits:6)
  - _Requirements: 3.4_

- [x] 4.3 Update AuthController
  - `login()` - call AuthService->attemptLogin()
  - `verifyOtp()` - call AuthService->verifyOtp()
  - `logout()` - call AuthService->logout()
  - `me()` - return authenticated user with roles/permissions
  - Handle exceptions with appropriate HTTP status codes
  - _Requirements: 2.1-2.5, 3.1-3.7, 7.1-7.4_

- [x] 4.4 Update API routes
  - POST `/api/v1/auth/login` (public)
  - POST `/api/v1/auth/verify-otp` (public)
  - POST `/api/v1/auth/logout` (protected)
  - GET `/api/v1/auth/me` (protected)
  - _Requirements: All auth requirements_

## Task 5: Authorization Middleware & Policies ✅ COMPLETED

- [x] 5.1 Create CheckPermission middleware
  - Verify user has required permission
  - Return 403 if unauthorized
  - _Requirements: 5.4, 5.5_

- [x] 5.2 Create CheckRole middleware
  - Verify user has required role
  - Return 403 if unauthorized
  - _Requirements: 5.1, 5.2_

- [x] 5.3 Register middleware in Kernel
  - Add to `$middlewareAliases`
  - _Requirements: 5.4_

- [x] 5.4 Create example protected route
  - Add permission middleware to a test route
  - Verify it works correctly
  - _Requirements: 5.4, 5.5_

## Task 6: Email Configuration & OTP Mailing ✅ COMPLETED

- [x] 6.1 Create OtpMail mailable
  - Design email template with OTP code
  - Include expiry time (5 minutes)
  - Add security notice
  - _Requirements: 3.3_

- [x] 6.2 Configure mail settings
  - Verify MailHog configuration in .env
  - Test email sending
  - _Requirements: 3.3, 10.1-10.5_

- [x] 6.3 Queue OTP emails
  - Configure queue for email sending
  - Ensure emails are sent asynchronously
  - _Requirements: 3.3_

## Task 7: Security Features ✅ COMPLETED

- [x] 7.1 Add rate limiting to login endpoint
  - Limit to 5 attempts per 15 minutes per IP
  - _Requirements: 4.1, 4.2_

- [x] 7.2 Implement account lockout logic
  - Lock account for 30 minutes after 5 failed attempts
  - Auto-unlock after timeout
  - _Requirements: 4.2, 4.3, 4.4_

- [x] 7.3 Add audit logging with Spatie Activity Log
  - Install package: `composer require spatie/laravel-activitylog`
  - Log all login attempts (success/failure)
  - Log OTP generation and validation
  - Log account lockouts
  - Log role/permission changes
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

## Task 8: Frontend Integration

- [ ] 8.1 Update login form
  - Change email field to personal_number
  - Update labels and placeholders
  - Update validation
  - _Requirements: 2.1, 2.2_

- [ ] 8.2 Create OTP verification page
  - 6-digit input field
  - Resend OTP button
  - Timer showing expiry countdown
  - _Requirements: 3.4, 3.5, 3.6, 3.7_

- [ ] 8.3 Update AuthContext
  - Handle two-step login flow
  - Store user roles/permissions
  - Update API calls for new endpoints
  - _Requirements: 7.1, 7.2, 7.3_

- [ ] 8.4 Add role-based UI rendering
  - Create usePermission hook
  - Create useRole hook
  - Hide/show features based on permissions
  - _Requirements: 5.1, 5.2, 5.4, 5.5_

## Task 9: Testing & Validation

- [ ] 9.1 Create test users
  - Create users for each role
  - Assign to different organizations
  - Seed with test data
  - _Requirements: 5.1, 6.1_

- [ ] 9.2 Test complete auth flow
  - Test login with personal number
  - Test OTP generation and verification
  - Test token issuance
  - Test protected routes
  - _Requirements: All auth requirements_

- [ ] 9.3 Test RBAC
  - Test permission checks
  - Test role checks
  - Test organization scoping
  - Test Super Admin cross-organization access
  - _Requirements: 5.1-5.5, 6.1-6.5_

- [ ] 9.4 Test security features
  - Test account lockout
  - Test rate limiting
  - Test OTP expiry
  - Test OTP attempt limits
  - _Requirements: 3.5, 3.7, 4.1-4.5_

## Task 10: Documentation & Cleanup

- [ ] 10.1 Update API documentation
  - Document new auth endpoints
  - Document request/response formats
  - Document error codes
  - _Requirements: All_

- [ ] 10.2 Create user guide
  - Document login process
  - Document OTP verification
  - Document role/permission structure
  - _Requirements: All_

- [ ] 10.3 Clean up old code
  - Remove email-based login code
  - Remove unused migrations
  - Update comments and documentation
  - _Requirements: All_

---

**Implementation Order:**
1. Database & Models (Tasks 1-2)
2. Core Auth Logic (Task 3)
3. API Layer (Task 4)
4. Authorization (Task 5)
5. Email & Security (Tasks 6-7)
6. Frontend (Task 8)
7. Testing & Docs (Tasks 9-10)

**Estimated Time:** 3-4 days for complete implementation
