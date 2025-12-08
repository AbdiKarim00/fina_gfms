# Requirements Document: Authentication & Authorization System

## Introduction

This document outlines the requirements for implementing a comprehensive authentication and authorization system for the Kenya Government Fleet Management System (GFMS). The system will use ID number (personal number) + password + email OTP for authentication, with full Role-Based Access Control (RBAC) for authorization.

## Glossary

- **GFMS**: Kenya Government Fleet Management System
- **MDAC**: Ministry, Department, Agency, or County Government
- **Personal Number/UPN**: Unique Personal Number - government-issued identifier for public servants
- **OTP**: One-Time Password (6-digit code sent via email)
- **RBAC**: Role-Based Access Control
- **Sanctum**: Laravel's API authentication system
- **Spatie Permission**: Laravel package for managing roles and permissions

## Requirements

### Requirement 1: Organizations (MDACs) Management

**User Story:** As a system administrator, I want to manage government organizations (ministries, departments, agencies, counties), so that users can be properly assigned to their respective entities.

#### Acceptance Criteria

1. THE System SHALL store organization details including name, code, type, parent organization, contact information, and active status
2. WHEN an organization is created, THE System SHALL generate a unique organization code
3. THE System SHALL support hierarchical organization structures with parent-child relationships
4. THE System SHALL categorize organizations as ministry, department, agency, or county
5. THE System SHALL allow organizations to be activated or deactivated without deletion

### Requirement 2: Personal Number (UPN) Authentication

**User Story:** As a government public servant, I want to log in using my Personal Number (UPN) and password, so that I can access the system securely using my official government-issued identifier.

#### Acceptance Criteria

1. WHEN a user attempts to login, THE System SHALL accept a Personal Number (UPN) as the username
2. THE System SHALL validate that the Personal Number is unique and exists in the system
3. WHEN invalid Personal Number format is provided, THE System SHALL reject the login attempt with a clear error message
4. THE System SHALL verify the Personal Number and password combination against stored credentials
5. WHEN credentials are invalid, THE System SHALL increment the failed login attempt counter

### Requirement 3: Email OTP Verification

**User Story:** As a security-conscious user, I want to verify my identity with an email OTP after entering my password, so that my account has an additional layer of security.

#### Acceptance Criteria

1. WHEN valid credentials are provided, THE System SHALL generate a random 6-digit numeric OTP
2. THE System SHALL store the OTP with a 5-minute expiration time
3. THE System SHALL send the OTP to the user's registered email address
4. WHEN a user enters an OTP, THE System SHALL validate it against the stored code and expiration time
5. WHEN an OTP expires, THE System SHALL reject it and require a new login attempt
6. WHEN an OTP is successfully validated, THE System SHALL mark it as used to prevent reuse
7. THE System SHALL allow a maximum of 3 OTP verification attempts before requiring re-authentication

### Requirement 4: Account Security

**User Story:** As a system administrator, I want accounts to be automatically locked after multiple failed login attempts, so that the system is protected from brute force attacks.

#### Acceptance Criteria

1. THE System SHALL track the number of consecutive failed login attempts for each user
2. WHEN a user exceeds 5 failed login attempts, THE System SHALL lock the account for 30 minutes
3. WHEN an account is locked, THE System SHALL reject all login attempts with a clear message indicating the lockout duration
4. WHEN the lockout period expires, THE System SHALL automatically unlock the account and reset the failed attempt counter
5. WHEN a successful login occurs, THE System SHALL reset the failed login attempt counter to zero

### Requirement 5: Role-Based Access Control (RBAC)

**User Story:** As a system administrator, I want to assign roles and permissions to users, so that access to system features is controlled based on job responsibilities.

#### Acceptance Criteria

1. THE System SHALL support the following predefined roles: Super Admin, Admin, Fleet Manager, Transport Officer, Finance Officer, Driver, CMTE Inspector, and Viewer
2. THE System SHALL allow users to be assigned one or multiple roles
3. THE System SHALL define permissions for each system resource (vehicles, drivers, bookings, maintenance, fuel, reports, users, organizations)
4. THE System SHALL enforce permission checks before allowing access to protected resources
5. WHEN a user lacks required permissions, THE System SHALL deny access with an appropriate error message

### Requirement 6: User-Organization Assignment

**User Story:** As a system administrator, I want to assign users to their respective organizations, so that data access can be scoped to organizational boundaries.

#### Acceptance Criteria

1. THE System SHALL require every user to be assigned to exactly one organization
2. THE System SHALL allow users to view data only from their assigned organization unless they have cross-organizational permissions
3. WHEN a user is created, THE System SHALL validate that the specified organization exists and is active
4. THE System SHALL allow Super Admins to access data across all organizations
5. THE System SHALL prevent users from being assigned to inactive organizations

### Requirement 7: Session Management

**User Story:** As a user, I want my session to remain active while I'm using the system, so that I don't have to repeatedly log in.

#### Acceptance Criteria

1. WHEN a user successfully authenticates, THE System SHALL issue a Sanctum API token with a 24-hour expiration
2. THE System SHALL accept the token in the Authorization header for subsequent API requests
3. WHEN a token expires, THE System SHALL reject requests with a 401 Unauthorized status
4. THE System SHALL allow users to explicitly log out, which invalidates their current token
5. THE System SHALL record the last login timestamp for each user

### Requirement 8: Audit Logging

**User Story:** As a compliance officer, I want all authentication and authorization events to be logged, so that security incidents can be investigated.

#### Acceptance Criteria

1. THE System SHALL log all login attempts (successful and failed) with timestamp, IP address, and user agent
2. THE System SHALL log all OTP generation and validation events
3. THE System SHALL log all account lockout and unlock events
4. THE System SHALL log all role and permission changes
5. THE System SHALL retain audit logs for a minimum of 7 years

### Requirement 9: Password Management

**User Story:** As a user, I want to be able to reset my password securely, so that I can regain access if I forget my credentials.

#### Acceptance Criteria

1. WHEN a user requests a password reset, THE System SHALL send a password reset link to their registered email
2. THE System SHALL generate a unique, time-limited (1 hour) password reset token
3. WHEN a user clicks the reset link, THE System SHALL validate the token and allow password change
4. THE System SHALL enforce password complexity requirements (minimum 8 characters, including uppercase, lowercase, number, and special character)
5. THE System SHALL prevent reuse of the last 5 passwords

### Requirement 10: Email Notifications

**User Story:** As a user, I want to receive email notifications for important security events, so that I'm aware of account activity.

#### Acceptance Criteria

1. WHEN a user successfully logs in from a new device or location, THE System SHALL send an email notification
2. WHEN a user's account is locked due to failed attempts, THE System SHALL send an email notification
3. WHEN a user's password is changed, THE System SHALL send a confirmation email
4. WHEN a user's role or permissions are modified, THE System SHALL send an email notification
5. THE System SHALL include relevant details (timestamp, IP address, action) in all security notification emails
