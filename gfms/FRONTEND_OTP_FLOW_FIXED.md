# Frontend OTP Authentication Flow - Fixed

## Problem
The frontend was treating authentication as a single-step process (login → token), but the backend implements a **two-step OTP authentication**:
1. Login with personal_number + password → Returns user_id and sends OTP
2. Verify OTP → Returns token and user data

This caused the login to "succeed" momentarily but then fail because no token was actually received.

## Solution Implemented

### 1. Updated Types (`src/types/index.ts`)
- Added `LoginResponse` interface for step 1 (login)
- Added `VerifyOtpResponse` interface for step 2 (OTP verification)
- Updated `LoginCredentials` to use `personal_number` instead of `email`

### 2. Updated AuthContext (`src/contexts/AuthContext.tsx`)
- Split authentication into two methods:
  - `login()` - Returns user_id and OTP channel info
  - `verifyOtp()` - Accepts OTP code and completes authentication
- Fixed `/auth/me` endpoint response handling

### 3. Created OTP Verification Page (`src/pages/VerifyOtpPage.tsx`)
- New page for entering the 6-digit OTP code
- Shows which channel (email/SMS) the OTP was sent to
- Includes link to OTP viewer for development
- Auto-formats input to accept only numbers
- Validates 6-digit code before submission

### 4. Updated Login Page (`src/pages/LoginPage.tsx`)
- Changed from email to personal number input
- Updated demo credentials (123456 instead of admin@gfms.go.ke)
- After successful login, navigates to OTP verification page with:
  - user_id
  - otp_channel
  - success message

### 5. Updated App Routes (`src/App.tsx`)
- Added `/verify-otp` route

## Authentication Flow

```
┌─────────────┐
│ Login Page  │
│ (Personal # │
│  + Password)│
└──────┬──────┘
       │
       ▼
┌─────────────────┐
│ Backend sends   │
│ OTP via email   │
│ or SMS          │
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│ OTP Verify Page │
│ (Enter 6-digit  │
│  code)          │
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│ Dashboard       │
│ (Authenticated) │
└─────────────────┘
```

## Testing

### Manual Testing (Browser)
1. **Login**: Enter personal number `123456` and password `password`
2. **Get OTP**: Open http://localhost:8000/otp-viewer.html in a new tab
3. **Verify**: Enter the 6-digit OTP code
4. **Success**: You'll be redirected to the dashboard

### Automated Testing (CLI)
```bash
cd gfms
./test-frontend-auth.sh
```

This script tests the complete authentication flow and displays user roles/permissions.

## Dev Tools

- **OTP Viewer**: http://localhost:8000/otp-viewer.html
- **MailHog**: http://localhost:8025
- **Test Credentials**: See `TEST_CREDENTIALS.txt`

## RBAC Support

The backend returns roles and permissions in the verify-otp response:
```json
{
  "token": "...",
  "user": {
    "id": 2,
    "name": "Admin User",
    "roles": ["Admin"],
    "permissions": ["view-vehicles", "create-vehicles", ...]
  }
}
```

These are stored in the AuthContext and can be used for frontend authorization checks.
