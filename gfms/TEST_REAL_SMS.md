# Test Real SMS with Africa's Talking

## Setup Complete ✅
- Africa's Talking configured with sandbox credentials
- Test user created with real phone number: **+254113334370**

## Step 1: Reseed Database

Run this command to add the test user:

```bash
docker exec gfms_app php artisan migrate:fresh --seed
```

This will create a test user:
- **Personal Number**: `999999`
- **Password**: `password`
- **Phone**: `+254113334370`
- **Role**: Admin

## Step 2: Test SMS Login

### Option A: Using curl

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "personal_number": "999999",
    "password": "password",
    "otp_channel": "sms"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "OTP sent to your phone: +254113334370",
  "data": {
    "user_id": 6,
    "otp_channel": "sms"
  }
}
```

### Option B: Using Postman/Insomnia

1. **Method**: POST
2. **URL**: `http://localhost:8000/api/v1/auth/login`
3. **Headers**: 
   - `Content-Type: application/json`
4. **Body** (raw JSON):
```json
{
  "personal_number": "999999",
  "password": "password",
  "otp_channel": "sms"
}
```

## Step 3: Check Your Phone

You should receive an SMS on **+254113334370** with:
```
Your GFMS verification code is: 123456. Valid for 5 minutes. Do not share this code with anyone.
```

## Step 4: Verify OTP

Once you receive the SMS, verify it:

```bash
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 6,
    "code": "YOUR_OTP_HERE",
    "otp_channel": "sms"
  }'
```

Replace `YOUR_OTP_HERE` with the actual OTP from the SMS.

**Expected Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "6|abc123...",
    "user": {
      "id": 6,
      "personal_number": "999999",
      "name": "SMS Test User",
      "phone": "+254113334370",
      "roles": ["Admin"],
      "permissions": [...]
    }
  }
}
```

## Troubleshooting

### SMS Not Received?

1. **Check Africa's Talking Dashboard**
   - Login to https://account.africastalking.com/apps/sandbox
   - Go to "SMS" → "Sent Messages"
   - Check delivery status

2. **Check Laravel Logs**
```bash
docker exec gfms_app tail -100 storage/logs/laravel.log | grep -i sms
```

3. **Verify Phone Number in Sandbox**
   - In Africa's Talking sandbox, you need to add test phone numbers
   - Go to "Sandbox" → "Phone Numbers"
   - Add `+254113334370` if not already added

4. **Check SMS Service Status**
```bash
docker exec gfms_app php artisan tinker
```
Then run:
```php
$service = app(\App\Services\SmsService::class);
echo $service->isEnabled() ? 'Enabled' : 'Disabled';
```

### Common Issues

**Issue**: "SMS (disabled)" in logs
- **Solution**: Check `.env` has `AFRICASTALKING_ENABLED=true`

**Issue**: API returns 401 Unauthorized
- **Solution**: Check API key in `.env` is correct

**Issue**: Phone number format error
- **Solution**: Phone number is automatically formatted, but ensure it's a valid Kenyan number

**Issue**: Sandbox limitations
- **Solution**: Sandbox has limited test numbers. For production testing, upgrade to live account.

## Alternative: Test with Email OTP

If SMS doesn't work immediately, test with email first:

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "personal_number": "999999",
    "password": "password",
    "otp_channel": "email"
  }'
```

Then check MailHog at http://localhost:8025

## Africa's Talking Sandbox Notes

### Free Credits
- 100 free SMS for testing
- No credit card required
- Valid for 30 days

### Test Phone Numbers
In sandbox mode, you can only send SMS to phone numbers you've added in the dashboard:
1. Login to https://account.africastalking.com/apps/sandbox
2. Go to "Sandbox" → "Phone Numbers"
3. Click "Add Phone Number"
4. Enter `+254113334370`
5. Verify with OTP sent to that number

### Going Live
When ready for production:
1. Click "Go Live" in dashboard
2. Submit business documents
3. Top up account (minimum KES 500)
4. Update `.env` with live credentials

## Cost Estimate

**Sandbox**: Free (100 SMS)
**Production**: KES 0.80 per SMS

For 1000 daily logins with SMS OTP:
- Daily: KES 800 (~$6)
- Monthly: KES 24,000 (~$185)

**Recommendation**: Use email OTP as default, SMS as optional for high-security actions.
