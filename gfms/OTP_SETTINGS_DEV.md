# OTP Settings - Development vs Production

## ✅ Updated: Less Strict OTP for Development

**Date**: December 9, 2025  
**Status**: COMPLETE

---

## 🔧 What Changed

The OTP verification system now has different settings based on the environment to make development testing easier while maintaining security in production.

### Before (Too Strict for Dev)
- ❌ OTP expires in 5 minutes
- ❌ Only 3 attempts allowed
- ❌ No feedback on remaining attempts

### After (Development Friendly)
- ✅ OTP expires in **15 minutes** (dev) / 5 minutes (prod)
- ✅ **10 attempts** allowed (dev) / 3 attempts (prod)
- ✅ Shows remaining attempts in error message

---

## 📊 Settings Comparison

| Setting | Development | Production |
|---------|-------------|------------|
| **OTP Expiry** | 15 minutes | 5 minutes |
| **Max Attempts** | 10 attempts | 3 attempts |
| **Error Messages** | Shows remaining attempts | Generic message |
| **Environment** | `APP_ENV=local` | `APP_ENV=production` |

---

## 🎯 How It Works

### Environment Detection
The system automatically detects the environment from `.env`:

```php
// In OtpService.php
private function getOtpExpiryMinutes(): int
{
    return config('app.env') === 'production' 
        ? 5   // Production: 5 minutes
        : 15; // Development: 15 minutes
}

private function getMaxOtpAttempts(): int
{
    return config('app.env') === 'production' 
        ? 3   // Production: 3 attempts
        : 10; // Development: 10 attempts
}
```

### Current Environment
Check your `.env` file:
```bash
APP_ENV=local  # Development mode (lenient)
```

For production:
```bash
APP_ENV=production  # Production mode (strict)
```

---

## 🧪 Testing the Changes

### Test OTP Expiry (15 minutes in dev)
1. Login with any test user
2. Get OTP code
3. Wait 6 minutes (would expire in old system)
4. Enter OTP - should still work! ✅
5. Wait 16 minutes - should expire ❌

### Test Multiple Attempts (10 attempts in dev)
1. Login with any test user
2. Get OTP code
3. Enter wrong code 3 times
4. Old system: "Too Many Attempts" ❌
5. New system: Still have 7 attempts left! ✅
6. Error shows: "Invalid OTP code. 7 attempts remaining."

### Test Remaining Attempts Feedback
```
Attempt 1: Invalid OTP code. 9 attempts remaining.
Attempt 2: Invalid OTP code. 8 attempts remaining.
Attempt 3: Invalid OTP code. 7 attempts remaining.
...
Attempt 10: Maximum OTP verification attempts exceeded
```

---

## 🔒 Security Notes

### Development (Current)
- **Lenient settings** for easier testing
- More time to enter OTP (15 min)
- More attempts allowed (10)
- Helpful error messages

### Production (When Deployed)
- **Strict security** settings
- Quick expiry (5 min)
- Limited attempts (3)
- Generic error messages
- Prevents brute force attacks

### Why This Approach?
- ✅ Makes development testing easier
- ✅ Maintains production security
- ✅ No code changes needed for deployment
- ✅ Environment-based configuration
- ✅ Best of both worlds

---

## 📝 Error Messages

### Development Mode
```
Invalid OTP code. 9 attempts remaining.
Invalid OTP code. 8 attempts remaining.
...
Maximum OTP verification attempts exceeded
OTP has expired or does not exist (after 15 minutes)
```

### Production Mode
```
Invalid OTP code. 2 attempts remaining.
Invalid OTP code. 1 attempts remaining.
Maximum OTP verification attempts exceeded
OTP has expired or does not exist (after 5 minutes)
```

---

## 🚀 Quick Test

### Test the New Settings
```bash
# 1. Start backend
cd gfms && make up

# 2. Login with test user
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password"}'

# 3. Get OTP from viewer
open http://localhost:8000/dev/otp-viewer

# 4. Try wrong OTP multiple times
curl -X POST http://localhost:8000/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"user_id":2,"code":"000000","otp_channel":"email"}'

# Should see: "Invalid OTP code. 9 attempts remaining."
```

---

## 🔄 Switching Environments

### For Development (Current)
```bash
# In .env
APP_ENV=local
APP_DEBUG=true
```

### For Production (Deployment)
```bash
# In .env
APP_ENV=production
APP_DEBUG=false
```

No code changes needed! The system automatically adjusts.

---

## 📋 Files Modified

```
✅ app/Services/OtpService.php
   - Added getOtpExpiryMinutes() method
   - Added getMaxOtpAttempts() method
   - Updated generate() to use dynamic expiry
   - Updated verify() to use dynamic attempts
   - Added remaining attempts to error message
```

---

## ✅ Benefits

### For Developers
- ✅ More time to test (15 min vs 5 min)
- ✅ More attempts (10 vs 3)
- ✅ Clear feedback on remaining attempts
- ✅ Less frustration during testing
- ✅ No need to regenerate OTP frequently

### For Production
- ✅ Maintains strict security (5 min, 3 attempts)
- ✅ Prevents brute force attacks
- ✅ Industry-standard security practices
- ✅ No compromise on security

### For DevOps
- ✅ Environment-based configuration
- ✅ No code changes for deployment
- ✅ Easy to switch between modes
- ✅ Configuration in .env file

---

## 🎉 Summary

The OTP system is now **development-friendly** while maintaining **production security**:

- **Development**: 15 minutes, 10 attempts, helpful messages
- **Production**: 5 minutes, 3 attempts, secure defaults
- **Automatic**: Based on `APP_ENV` in `.env`
- **No Changes**: Works automatically in both environments

You can now test OTP functionality without the frustration of "Too Many Attempts" errors!

---

## 📚 Related Documentation

- `START_RBAC_TESTING.md` - Quick start guide
- `RBAC_TESTING_GUIDE.md` - Full testing checklist
- `OTP_VIEWER_GUIDE.txt` - How to use OTP viewer

---

**Status**: ✅ COMPLETE  
**Environment**: Development (lenient)  
**OTP Expiry**: 15 minutes  
**Max Attempts**: 10  
**Ready**: Yes
