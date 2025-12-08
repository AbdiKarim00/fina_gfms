# 🥄 SPOON-FED SMS OTP Testing Guide

## 🎯 Goal: See SMS OTPs Like MailHog

I've created a **web interface** to view OTPs just like MailHog!

---

## ✅ Step 1: Open OTP Viewer

**Open in your browser:**
```
http://localhost:8000/otp-viewer.html
```

This page shows ALL OTPs (Email + SMS) in real-time! 🎉

---

## ✅ Step 2: Test SMS OTP (3 Simple Commands)

### Command 1: Reseed Database (Add Test User)
```bash
docker exec gfms_app php artisan migrate:fresh --seed
```

**What this does:** Creates test user with your phone number (+254113334370)

### Command 2: Login with SMS OTP
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"999999","password":"password","otp_channel":"sms"}'
```

**What this does:** Sends SMS OTP to +254113334370

### Command 3: Check OTP Viewer
Go to: http://localhost:8000/otp-viewer.html

**You'll see:**
- The 6-digit OTP code
- User ID
- Channel (SMS/Email)
- Expiry countdown

---

## 🔍 Alternative Ways to See OTPs

### Method 1: OTP Viewer (Easiest) ⭐
```
http://localhost:8000/otp-viewer.html
```

### Method 2: API Endpoint
```bash
# Get all OTPs
curl http://localhost:8000/api/v1/dev/otps

# Get OTP for specific user
curl http://localhost:8000/api/v1/dev/otps/6/sms
```

### Method 3: Check Your Phone
If SMS is enabled, you'll receive real SMS on +254113334370

### Method 4: Check Logs
```bash
docker exec gfms_app tail -f storage/logs/laravel.log | grep SMS
```

---

## 📱 Africa's Talking Setup (Step-by-Step)

### Step 1: Login to Dashboard
Go to: https://account.africastalking.com/apps/sandbox

**Your credentials:**
- Username: sandbox
- API Key: atsk_7739bb50dbac1f65e1b42d928ceba595c2c19c1ad95ce2174ca6ceee0819a44bfb301213

### Step 2: Add Test Phone Number
1. Click "Sandbox" in left menu
2. Click "Phone Numbers"
3. Click "Add Phone Number"
4. Enter: `+254113334370`
5. Click "Add"
6. You'll receive OTP on your phone to verify
7. Enter the OTP to confirm

### Step 3: Check Sent Messages
1. Click "SMS" in left menu
2. Click "Sent Messages"
3. You'll see all SMS sent with delivery status

---

## 🧪 Complete Test Flow

### Test 1: Email OTP (Free, Always Works)
```bash
# 1. Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password"}'

# 2. Check OTP Viewer
# Open: http://localhost:8000/otp-viewer.html

# 3. Copy OTP and verify
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"user_id":2,"code":"YOUR_OTP_HERE"}'
```

### Test 2: SMS OTP (Your Phone)
```bash
# 1. Login with SMS
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"999999","password":"password","otp_channel":"sms"}'

# 2. Check OTP Viewer OR your phone
# Open: http://localhost:8000/otp-viewer.html

# 3. Verify OTP
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"user_id":6,"code":"YOUR_OTP_HERE","otp_channel":"sms"}'
```

---

## 🎨 OTP Viewer Features

### What You See:
- ✅ All active OTPs (Email + SMS)
- ✅ 6-digit OTP codes
- ✅ User IDs
- ✅ Channel (Email/SMS)
- ✅ Expiry countdown
- ✅ Copy button (one-click copy)
- ✅ Auto-refresh option
- ✅ Real-time stats

### Buttons:
- **🔄 Refresh OTPs** - Reload OTPs manually
- **⏱️ Auto-Refresh** - Auto-reload every 5 seconds
- **📋 Copy OTP** - Copy OTP to clipboard

---

## 🐛 Troubleshooting

### Problem: OTP Viewer shows "Error"
**Solution:** Make sure `APP_DEBUG=true` in `.env`

### Problem: No OTPs showing
**Solution:** Try logging in first to generate an OTP

### Problem: SMS not received on phone
**Solutions:**
1. Check OTP Viewer first (OTP is there even if SMS fails)
2. Add +254113334370 to Africa's Talking sandbox
3. Check Africa's Talking dashboard for delivery status
4. Verify `AFRICASTALKING_ENABLED=true` in `.env`

### Problem: "Connection Error"
**Solution:** Make sure backend is running:
```bash
docker ps | grep gfms_app
```

---

## 📊 Test Users

| Personal Number | Password | Phone | Role |
|----------------|----------|-------|------|
| 100000 | password | +254700000000 | Super Admin |
| 123456 | password | +254700000001 | Admin |
| 234567 | password | +254700000002 | Fleet Manager |
| 999999 | password | +254113334370 | Admin (YOUR PHONE) |

---

## 🎯 Quick Test (Copy-Paste)

```bash
# 1. Reseed
docker exec gfms_app php artisan migrate:fresh --seed

# 2. Login with YOUR phone number
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"999999","password":"password","otp_channel":"sms"}'

# 3. Open OTP Viewer
# http://localhost:8000/otp-viewer.html

# 4. Copy OTP from viewer and verify
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"user_id":6,"code":"PASTE_OTP_HERE","otp_channel":"sms"}'
```

---

## 🎉 Success Looks Like:

### OTP Viewer:
```
┌─────────────────────────┐
│   SMS                   │
│   123456                │
│   User ID: 6            │
│   ⏱️ Expires in 4.8 min │
│   [📋 Copy OTP]         │
└─────────────────────────┘
```

### Your Phone:
```
Your GFMS verification code is: 123456. 
Valid for 5 minutes. 
Do not share this code with anyone.
```

### API Response:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "6|abc123...",
    "user": {...}
  }
}
```

---

## 💡 Pro Tips

1. **Use OTP Viewer** - Easier than checking logs
2. **Auto-Refresh** - Enable for continuous monitoring
3. **Copy Button** - One-click copy OTP
4. **Test Email First** - Free and always works
5. **Check Dashboard** - Africa's Talking shows delivery status

---

## 🔗 Quick Links

- **OTP Viewer:** http://localhost:8000/otp-viewer.html
- **MailHog:** http://localhost:8025
- **API Health:** http://localhost:8000/api/health
- **Africa's Talking:** https://account.africastalking.com/apps/sandbox

---

## ✅ Summary

**To see SMS OTPs:**
1. Open http://localhost:8000/otp-viewer.html
2. Login with SMS OTP
3. See OTP in viewer (just like MailHog!)
4. Copy and verify

**That's it!** 🎉
