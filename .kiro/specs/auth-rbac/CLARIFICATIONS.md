# Authentication System Clarifications

## 1. Multi-Tenancy Approach ✅

**Approach:** Organization-Scoped Data (Soft Multi-Tenancy)

### What This Means:
- ✅ **Single Database** - All organizations share one database
- ✅ **Data Isolation** - Users see only their organization's data
- ✅ **Organization Scoping** - Every table has `organization_id`
- ✅ **Global Scopes** - Automatic filtering by organization
- ✅ **Super Admin Override** - GFMD can access all data
- ✅ **National Reporting** - Cross-organization analytics possible

### NOT Using:
- ❌ Separate databases per organization
- ❌ Separate schemas per organization
- ❌ Complete data isolation (need national reporting)

### Benefits:
1. Easier maintenance (one database)
2. Simpler backups and migrations
3. Cross-organization reporting for GFMD
4. Cost-effective infrastructure
5. Easier to scale

## 2. Personal Number (UPN) ✅

**Identifier:** Personal Number / UPN (Unique Personal Number)

### What This Means:
- ✅ Government-issued identifier for public servants
- ✅ Unique across the entire system
- ✅ Used as primary login credential (instead of email)
- ✅ Stored in `personal_number` field (not `id_number`)

### Login Flow:
```
Personal Number (UPN) + Password → Email OTP → Access Granted
```

### Database Field:
```sql
personal_number VARCHAR(50) UNIQUE NOT NULL
```

### Validation:
- Must be unique
- Required for all users
- Format validation (to be defined based on government standards)

## Updated Requirements Summary

### Authentication Requirements:
1. ✅ Login with Personal Number (UPN) + Password
2. ✅ Email OTP verification (6-digit, 5-minute expiry)
3. ✅ Account lockout after 5 failed attempts (30-minute lock)
4. ✅ Sanctum token-based sessions (24-hour expiry)

### Authorization Requirements:
1. ✅ Organization-scoped data access
2. ✅ RBAC with 8 predefined roles
3. ✅ Permission-based feature access
4. ✅ Super Admin can access all organizations

### Data Structure:
```
Organizations (MDACs)
    ↓
Users (with personal_number)
    ↓
Roles & Permissions
    ↓
Organization-Scoped Data (Vehicles, Drivers, etc.)
```

## Next Steps

Ready to proceed with implementation? The spec is updated with:
- ✅ Personal Number (UPN) authentication
- ✅ Organization-scoped multi-tenancy approach
- ✅ Clear RBAC structure
- ✅ Security requirements

**Please review the requirements.md file and confirm if everything looks good!**
