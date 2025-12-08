# Design Document: Authentication & Authorization System

## Overview

This document outlines the technical design for implementing a comprehensive authentication and authorization system for GFMS. The system uses Personal Number (UPN) + Password + Email OTP for authentication, with organization-scoped RBAC for authorization.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Frontend (React)                         │
│  Login Form → OTP Verification → Protected Routes           │
└────────────────────┬────────────────────────────────────────┘
                     │ HTTPS/API
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                  Laravel API (Backend)                       │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │ Auth         │  │ OTP          │  │ RBAC         │     │
│  │ Controller   │  │ Service      │  │ Middleware   │     │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘     │
│         │                 │                 │              │
│         └─────────────────┼─────────────────┘              │
│                           ▼                                 │
│  ┌──────────────────────────────────────────────────┐     │
│  │           Service Layer                          │     │
│  │  - AuthService                                   │     │
│  │  - OtpService                                    │     │
│  │  - OrganizationService                           │     │
│  └──────────────────┬───────────────────────────────┘     │
│                     ▼                                       │
│  ┌──────────────────────────────────────────────────┐     │
│  │           Repository Layer                        │     │
│  │  - UserRepository                                │     │
│  │  - OrganizationRepository                        │     │
│  └──────────────────┬───────────────────────────────┘     │
└────────────────────┬────────────────────────────────────────┘
                     │
         ┌───────────┼───────────┐
         ▼           ▼           ▼
    ┌────────┐  ┌────────┐  ┌────────┐
    │PostgreSQL│ │ Redis  │  │MailHog│
    │  Users   │ │  OTP   │  │ Email │
    │  Orgs    │ │ Cache  │  │       │
    │  Roles   │ └────────┘  └────────┘
    └──────────┘
```

### Authentication Flow

```
┌──────────┐
│  User    │
└────┬─────┘
     │ 1. Enter UPN + Password
     ▼
┌─────────────────┐
│ POST /auth/login│
└────┬────────────┘
     │ 2. Validate credentials
     ▼
┌─────────────────┐
│ Generate OTP    │
│ Store in Redis  │
│ (5 min expiry)  │
└────┬────────────┘
     │ 3. Send email
     ▼
┌─────────────────┐
│ Email OTP       │
└────┬────────────┘
     │ 4. User enters OTP
     ▼
┌─────────────────┐
│POST /auth/verify│
└────┬────────────┘
     │ 5. Validate OTP
     ▼
┌─────────────────┐
│ Issue Sanctum   │
│ Token (24h)     │
└────┬────────────┘
     │ 6. Return user + token + roles
     ▼
┌─────────────────┐
│ Access Granted  │
└─────────────────┘
```

## Components and Interfaces

### 1. Database Models

#### Organization Model
```php
class Organization extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'parent_id',
        'email', 'phone', 'address', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
```

#### User Model (Updated)
```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'personal_number', 'name', 'email', 'phone',
        'password', 'organization_id', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Global scope for organization filtering
    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            if (auth()->check() && !auth()->user()->hasRole('super_admin')) {
                $query->where('organization_id', auth()->user()->organization_id);
            }
        });
    }

    // Methods
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        
        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(30)]);
        }
    }

    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }
}
```

### 2. Services

#### AuthService
```php
class AuthService
{
    public function __construct(
        private OtpService $otpService,
        private UserRepository $userRepository
    ) {}

    public function attemptLogin(string $personalNumber, string $password): array
    {
        $user = $this->userRepository->findByPersonalNumber($personalNumber);

        if (!$user || !Hash::check($password, $user->password)) {
            if ($user) {
                $user->incrementFailedAttempts();
            }
            throw new AuthenticationException('Invalid credentials');
        }

        if ($user->isLocked()) {
            throw new AccountLockedException('Account locked until ' . $user->locked_until);
        }

        if (!$user->is_active) {
            throw new InactiveAccountException('Account is inactive');
        }

        // Generate and send OTP
        $otp = $this->otpService->generate($user);
        $this->otpService->sendEmail($user, $otp);

        return [
            'message' => 'OTP sent to your email',
            'user_id' => $user->id,
        ];
    }

    public function verifyOtp(int $userId, string $code): array
    {
        $user = $this->userRepository->find($userId);

        if (!$this->otpService->verify($user, $code)) {
            throw new InvalidOtpException('Invalid or expired OTP');
        }

        // Reset failed attempts
        $user->resetFailedAttempts();
        $user->update(['last_login_at' => now()]);

        // Issue token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Load roles and permissions
        $user->load('roles.permissions', 'organization');

        return [
            'user' => $user,
            'token' => $token,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
```

#### OtpService
```php
class OtpService
{
    private const OTP_LENGTH = 6;
    private const OTP_EXPIRY = 300; // 5 minutes
    private const MAX_ATTEMPTS = 3;

    public function generate(User $user): string
    {
        $code = str_pad(random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);
        
        $key = "otp:{$user->id}";
        $attemptsKey = "otp_attempts:{$user->id}";

        Redis::setex($key, self::OTP_EXPIRY, $code);
        Redis::setex($attemptsKey, self::OTP_EXPIRY, 0);

        return $code;
    }

    public function verify(User $user, string $code): bool
    {
        $key = "otp:{$user->id}";
        $attemptsKey = "otp_attempts:{$user->id}";

        $storedCode = Redis::get($key);
        $attempts = (int) Redis::get($attemptsKey);

        if ($attempts >= self::MAX_ATTEMPTS) {
            Redis::del($key, $attemptsKey);
            return false;
        }

        if ($storedCode !== $code) {
            Redis::incr($attemptsKey);
            return false;
        }

        // Valid OTP - clean up
        Redis::del($key, $attemptsKey);
        return true;
    }

    public function sendEmail(User $user, string $code): void
    {
        Mail::to($user->email)->send(new OtpMail($code));
    }
}
```

### 3. Controllers

#### AuthController (Updated)
```php
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->attemptLogin(
                $request->personal_number,
                $request->password
            );

            return response()->json($result);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (AccountLockedException $e) {
            return response()->json(['message' => $e->getMessage()], 423);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $result = $this->authService->verifyOtp(
                $request->user_id,
                $request->code
            );

            return response()->json($result);
        } catch (InvalidOtpException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('roles.permissions', 'organization'),
        ]);
    }
}
```

### 4. Middleware

#### CheckPermission Middleware
```php
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!$request->user()->can($permission)) {
            return response()->json([
                'message' => 'Insufficient permissions'
            ], 403);
        }

        return $next($request);
    }
}
```

#### CheckRole Middleware
```php
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()->hasRole($role)) {
            return response()->json([
                'message' => 'Insufficient role'
            ], 403);
        }

        return $next($request);
    }
}
```

## Data Models

### Database Schema

```sql
-- Organizations
CREATE TABLE organizations (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('ministry', 'department', 'agency', 'county')),
    parent_id BIGINT REFERENCES organizations(id),
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Users (updated)
ALTER TABLE users 
    ADD COLUMN personal_number VARCHAR(50) UNIQUE NOT NULL,
    ADD COLUMN organization_id BIGINT REFERENCES organizations(id),
    ADD COLUMN is_active BOOLEAN DEFAULT true,
    ADD COLUMN last_login_at TIMESTAMP,
    ADD COLUMN failed_login_attempts INT DEFAULT 0,
    ADD COLUMN locked_until TIMESTAMP;

-- Spatie Permission Tables (auto-generated by package)
-- roles, permissions, model_has_roles, model_has_permissions, role_has_permissions

-- Indexes
CREATE INDEX idx_users_personal_number ON users(personal_number);
CREATE INDEX idx_users_organization ON users(organization_id);
CREATE INDEX idx_organizations_code ON organizations(code);
CREATE INDEX idx_organizations_type ON organizations(type);
```

## Error Handling

### Custom Exceptions

```php
class AuthenticationException extends Exception {}
class AccountLockedException extends Exception {}
class InactiveAccountException extends Exception {}
class InvalidOtpException extends Exception {}
class InsufficientPermissionsException extends Exception {}
```

### Error Responses

```json
{
    "message": "Error description",
    "errors": {
        "field": ["Validation error"]
    }
}
```

## Testing Strategy

### Unit Tests
- AuthService login logic
- OtpService generation and verification
- User model methods (isLocked, incrementFailedAttempts)
- Organization scoping logic

### Integration Tests
- Complete login flow (credentials → OTP → token)
- Account lockout after failed attempts
- Permission checking middleware
- Organization data isolation

### Feature Tests
- API endpoint responses
- Token authentication
- Role-based access
- Cross-organization access for Super Admin

## Security Considerations

1. **Password Security**
   - Bcrypt hashing
   - Minimum complexity requirements
   - Password history (prevent reuse)

2. **OTP Security**
   - 6-digit random code
   - 5-minute expiration
   - 3 attempt limit
   - Stored in Redis (not database)

3. **Token Security**
   - Sanctum tokens
   - 24-hour expiration
   - Revocable on logout
   - HTTPS only

4. **Rate Limiting**
   - 5 login attempts per 15 minutes per IP
   - 3 OTP attempts per session
   - Account lockout after 5 failed logins

5. **Audit Logging**
   - All authentication events
   - Permission changes
   - Cross-organization access
   - Failed login attempts

## Performance Considerations

1. **Caching**
   - User roles/permissions cached in Redis
   - Organization hierarchy cached
   - OTP codes in Redis (not database)

2. **Database Optimization**
   - Indexes on personal_number, organization_id
   - Eager loading of relationships
   - Query optimization for organization scoping

3. **API Response Times**
   - Target: <200ms for auth endpoints
   - <100ms for permission checks
   - Async email sending (queued)

## Deployment Notes

1. **Environment Variables**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=mailhog
   MAIL_PORT=1025
   
   REDIS_HOST=redis
   REDIS_PORT=6379
   
   OTP_EXPIRY=300
   OTP_LENGTH=6
   MAX_LOGIN_ATTEMPTS=5
   ACCOUNT_LOCKOUT_MINUTES=30
   ```

2. **Package Installation**
   ```bash
   composer require spatie/laravel-permission
   composer require spatie/laravel-activitylog
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan migrate
   ```

3. **Seeding**
   - Organizations (47 counties + ministries)
   - Roles and permissions
   - Test users for each role

---

This design provides a secure, scalable authentication and authorization system aligned with government requirements and best practices.
