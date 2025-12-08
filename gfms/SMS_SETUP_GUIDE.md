# SMS OTP Setup Guide

## Option 1: Africa's Talking (Recommended for Kenya) 🇰🇪

### Why Africa's Talking?
- **Local**: Kenyan company with direct Safaricom integration
- **Affordable**: ~KES 0.80 per SMS
- **Reliable**: Used by M-KOPA, Twiga Foods, and many Kenyan govt projects
- **Easy**: Simple REST API

### Setup Steps

#### 1. Create Account
1. Go to [https://africastalking.com](https://africastalking.com)
2. Click "Sign Up" → Choose "Kenya"
3. Verify your email and phone number

#### 2. Get Sandbox Credentials (For Testing)
1. Login to dashboard
2. Go to "Sandbox" section
3. Copy your **API Key**
4. Username is: `sandbox`
5. You get **FREE 100 SMS** for testing

#### 3. Configure in `.env`
```env
AFRICASTALKING_USERNAME=sandbox
AFRICASTALKING_API_KEY=your_api_key_here
AFRICASTALKING_FROM=GFMS
AFRICASTALKING_ENABLED=true
```

#### 4. Test SMS
```bash
# Test with personal number 123456
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password","otp_channel":"sms"}'
```

#### 5. Go Live (Production)
1. In dashboard, click "Go Live"
2. Submit business documents (KRA PIN, Business Registration)
3. Wait for approval (1-2 days)
4. Top up your account (minimum KES 500)
5. Update `.env`:
```env
AFRICASTALKING_USERNAME=your_live_username
AFRICASTALKING_API_KEY=your_live_api_key
```

### Pricing (Production)
- **Kenya SMS**: KES 0.80 per SMS
- **Bulk discounts**: Available for >100K SMS/month
- **No monthly fees**: Pay as you go

### Phone Number Format
The system automatically handles these formats:
- `0712345678` → `+254712345678`
- `712345678` → `+254712345678`
- `+254712345678` → `+254712345678`
- `254712345678` → `+254712345678`

---

## Option 2: Twilio (International Alternative)

### Setup Steps

#### 1. Create Account
1. Go to [https://www.twilio.com](https://www.twilio.com)
2. Sign up and verify your email
3. Get **$15 free trial credit**

#### 2. Get Credentials
1. Go to Console Dashboard
2. Copy **Account SID**
3. Copy **Auth Token**
4. Get a phone number (or use trial number)

#### 3. Configure in `.env`
```env
TWILIO_SID=your_account_sid
TWILIO_TOKEN=your_auth_token
TWILIO_FROM=+1234567890
TWILIO_ENABLED=true
AFRICASTALKING_ENABLED=false
```

#### 4. Create Twilio Service (Optional)
If you want to use Twilio instead of Africa's Talking, create:

`app/Services/TwilioSmsService.php`:
```php
<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private Client $client;
    private string $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->from = config('services.twilio.from');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Twilio SMS failed: ' . $e->getMessage());
            return false;
        }
    }
}
```

### Pricing
- **Kenya SMS**: ~$0.05 per SMS (more expensive than Africa's Talking)
- **Trial**: $15 free credit
- **Monthly fees**: None (pay as you go)

---

## Testing Without Real SMS

### Option 1: Disabled Mode (Current)
SMS is logged to `storage/logs/laravel.log`:
```env
AFRICASTALKING_ENABLED=false
```

Check logs:
```bash
docker exec gfms_app tail -f storage/logs/laravel.log | grep SMS
```

### Option 2: Test Phone Numbers
Africa's Talking sandbox allows testing with specific numbers without sending real SMS.

---

## Usage in Application

### Login with SMS OTP
```bash
# Step 1: Login (sends SMS)
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "personal_number":"123456",
    "password":"password",
    "otp_channel":"sms"
  }'

# Response:
# {
#   "success": true,
#   "message": "OTP sent to your phone: +254700000001",
#   "data": {
#     "user_id": 2,
#     "otp_channel": "sms"
#   }
# }

# Step 2: Verify OTP
curl -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 2,
    "code": "123456",
    "otp_channel": "sms"
  }'
```

### Login with Email OTP (Default)
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "personal_number":"123456",
    "password":"password"
  }'
# or explicitly:
# "otp_channel":"email"
```

---

## Security Best Practices

1. **Rate Limiting**: Already implemented (5 attempts per 15 minutes)
2. **OTP Expiry**: 5 minutes
3. **Attempt Limit**: 3 OTP verification attempts
4. **Phone Verification**: Ensure users verify their phone numbers
5. **Cost Control**: Set spending limits in Africa's Talking dashboard
6. **Monitoring**: Monitor SMS delivery rates and costs

---

## Troubleshooting

### SMS Not Received
1. Check logs: `docker exec gfms_app tail -f storage/logs/laravel.log`
2. Verify phone number format
3. Check Africa's Talking dashboard for delivery status
4. Ensure account has credit (production)
5. Check if number is in DND (Do Not Disturb) list

### API Errors
- **401 Unauthorized**: Check API key
- **403 Forbidden**: Account not activated or insufficient credit
- **400 Bad Request**: Invalid phone number format

### Cost Concerns
- Start with sandbox (free 100 SMS)
- Monitor usage in dashboard
- Set up spending alerts
- Consider email OTP as default, SMS as optional

---

## Recommended Setup for GFMS

### Development
```env
AFRICASTALKING_USERNAME=sandbox
AFRICASTALKING_API_KEY=your_sandbox_key
AFRICASTALKING_ENABLED=true
```

### Production
```env
AFRICASTALKING_USERNAME=your_live_username
AFRICASTALKING_API_KEY=your_live_key
AFRICASTALKING_FROM=GFMS
AFRICASTALKING_ENABLED=true
```

### Cost Estimate
- **1000 users** logging in daily with SMS OTP
- **1000 SMS/day** × KES 0.80 = **KES 800/day**
- **Monthly**: ~KES 24,000 (~$185)

**Recommendation**: Use email OTP as default, SMS as optional or for high-security actions.
