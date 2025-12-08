# 🚀 Production SMS Setup & User Preference Guide

## Part 1: Going Live with Africa's Talking

### Current Status: ✅ Sandbox Working
- Sandbox API Key configured
- SMS OTP tested and working
- Ready for production

### Step-by-Step Production Setup

#### 1️⃣ Upgrade to Live Account (5 mins)

**Go to:** https://account.africastalking.com

**Click:** "Go Live" button (top right corner)

**Submit Documents:**
```
✅ Business Registration Certificate (PDF/Image)
✅ KRA PIN Certificate (PDF/Image)
✅ Director/Owner ID (PDF/Image)
✅ Proof of Address - Utility bill (PDF/Image)
```

**Approval Time:** 1-2 business days

---

#### 2️⃣ Top Up Account (2 mins)

**After Approval:**
1. Go to "Billing" → "Top Up"
2. Choose amount:
   - **Minimum:** KES 500
   - **Recommended:** KES 5,000 (for testing)
   - **Production:** KES 50,000+ (depends on usage)

**Payment Methods:**
- M-Pesa (instant)
- Bank Transfer (1-2 hours)
- Credit Card (instant)

**Cost:** KES 0.80 per SMS

---

#### 3️⃣ Get Live Credentials (1 min)

**Go to:** Settings → API Keys

**Copy:**
- **Live Username:** (your company name, e.g., "GFMS" or "TransportMinistry")
- **Live API Key:** (starts with "atsk_")

---

#### 4️⃣ Update Production `.env` (1 min)

**File:** `gfms/apps/backend/.env`

```env
# SMS Service (Africa's Talking - PRODUCTION)
AFRICASTALKING_USERNAME=YourCompanyName
AFRICASTALKING_API_KEY=your_live_api_key_here
AFRICASTALKING_FROM=GFMS
AFRICASTALKING_ENABLED=true
```

**Restart Services:**
```bash
docker restart gfms_app gfms_queue
```

**That's it!** SMS now goes to real phones! 📱

---

## Part 2: User OTP Preference System

### Current Implementation
✅ Backend supports both Email and SMS
✅ User can choose via `otp_channel` parameter
✅ API: `{"otp_channel": "email"}` or `{"otp_channel": "sms"}`

### What We Need to Add

#### Option A: User Preference in Database (Recommended)

**Add to users table:**
```sql
ALTER TABLE users ADD COLUMN preferred_otp_channel VARCHAR(10) DEFAULT 'email';
```

**User can set preference in profile:**
- Email (default, free)
- SMS (costs money, faster)

**Backend uses preference automatically:**
```php
$channel = $user->preferred_otp_channel ?? 'email';
$otpService->send($user, $otp, $channel);
```

#### Option B: Ask Every Time (Your Suggestion)

**Show modal on login:**
```
┌─────────────────────────────────────┐
│  How would you like to receive     │
│  your verification code?           │
│                                    │
│  ○ Email (Recommended)             │
│    Free, sent to: admin@gfms.go.ke │
│                                    │
│  ○ SMS                             │
│    Sent to: +254700000001          │
│                                    │
│  [Continue]                        │
└─────────────────────────────────────┘
```

**Frontend sends choice:**
```javascript
{
  "personal_number": "123456",
  "password": "password",
  "otp_channel": "sms" // or "email"
}
```

---

## Part 3: Recommended Production Strategy

### Strategy: Hybrid Approach 🎯

#### Default Behavior:
1. **Email OTP** - Default for all users (free)
2. **SMS OTP** - Optional, user can enable in settings

#### When to Use SMS:
- High-security actions (approve large expenses)
- First-time login
- Password reset
- Admin actions
- User preference

#### Cost Optimization:
```
Email OTP (Default):
- Free
- Reliable
- Good for 90% of logins

SMS OTP (Optional):
- KES 0.80 per SMS
- Faster delivery
- Better for urgent actions
```

**Estimated Costs:**
```
Scenario 1: Email Only
- 1000 daily logins
- Cost: FREE

Scenario 2: 10% SMS, 90% Email
- 100 SMS/day × KES 0.80 = KES 80/day
- Monthly: KES 2,400 (~$18)

Scenario 3: All SMS
- 1000 SMS/day × KES 0.80 = KES 800/day
- Monthly: KES 24,000 (~$185)
```

---

## Part 4: Implementation Plan

### Phase 1: Add User Preference (30 mins)

**Migration:**
```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('preferred_otp_channel', ['email', 'sms'])
          ->default('email')
          ->after('phone');
});
```

**Update User Model:**
```php
protected $fillable = [
    // ... existing fields
    'preferred_otp_channel',
];
```

**Update AuthService:**
```php
public function attemptLogin(string $personalNumber, string $password, ?string $otpChannel = null): array
{
    $user = $this->userRepository->findByPersonalNumber($personalNumber);
    
    // Use provided channel or user preference
    $channel = $otpChannel ?? $user->preferred_otp_channel ?? 'email';
    
    // ... rest of logic
}
```

### Phase 2: Frontend Modal (1 hour)

**Create OTP Channel Selector:**
```tsx
// components/OtpChannelModal.tsx
<Modal>
  <h2>Choose Verification Method</h2>
  
  <RadioGroup value={channel} onChange={setChannel}>
    <Radio value="email">
      📧 Email
      <span>Free • Sent to: {user.email}</span>
    </Radio>
    
    <Radio value="sms">
      📱 SMS
      <span>Sent to: {user.phone}</span>
    </Radio>
  </RadioGroup>
  
  <Button onClick={handleContinue}>Continue</Button>
</Modal>
```

### Phase 3: User Settings (30 mins)

**Add to Profile Page:**
```tsx
<Setting>
  <Label>Default OTP Method</Label>
  <Select value={user.preferred_otp_channel}>
    <option value="email">Email (Recommended)</option>
    <option value="sms">SMS</option>
  </Select>
</Setting>
```

---

## Part 5: Testing Production SMS

### Test with Real Phone Numbers

**Test User Setup:**
```bash
# Create test user with real phone
docker exec gfms_app php artisan tinker

$user = User::create([
    'personal_number' => '888888',
    'name' => 'Production Test',
    'email' => 'test@gfms.go.ke',
    'phone' => '+254712345678', // Real phone
    'password' => Hash::make('password'),
    'organization_id' => 1,
    'is_active' => true,
]);
```

**Test SMS:**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"888888","password":"password","otp_channel":"sms"}'
```

**Check Phone:** You should receive real SMS!

---

## Part 6: Monitoring & Cost Control

### Africa's Talking Dashboard

**Monitor:**
1. **SMS Sent** - Total messages sent
2. **Delivery Rate** - Success percentage
3. **Balance** - Remaining credit
4. **Cost** - Daily/monthly spending

**Set Alerts:**
1. Go to "Settings" → "Notifications"
2. Set low balance alert (e.g., KES 1,000)
3. Set daily spending limit

### Application Monitoring

**Track in Database:**
```sql
-- Add to audit log
CREATE TABLE otp_logs (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    channel VARCHAR(10), -- 'email' or 'sms'
    status VARCHAR(20), -- 'sent', 'delivered', 'failed'
    cost DECIMAL(10,2), -- KES 0.80 for SMS, 0 for email
    created_at TIMESTAMP
);
```

**Monthly Report:**
```sql
SELECT 
    channel,
    COUNT(*) as total_sent,
    SUM(CASE WHEN channel = 'sms' THEN 0.80 ELSE 0 END) as total_cost
FROM otp_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
GROUP BY channel;
```

---

## Part 7: Production Checklist

### Before Going Live:

- [ ] Africa's Talking account approved
- [ ] Account topped up (minimum KES 5,000)
- [ ] Live credentials in `.env`
- [ ] Test with real phone numbers
- [ ] Set spending alerts
- [ ] Monitor delivery rates
- [ ] User preference system implemented
- [ ] Cost tracking in place
- [ ] Fallback to email if SMS fails

### Security:

- [ ] Rate limiting enabled (5 attempts per 15 min)
- [ ] OTP expiry working (5 minutes)
- [ ] Account lockout working (5 failed attempts)
- [ ] Audit logging enabled
- [ ] SMS costs monitored

### User Experience:

- [ ] Clear messaging about SMS costs (if applicable)
- [ ] Email as default (free)
- [ ] SMS as optional (faster)
- [ ] User can change preference
- [ ] Fallback if SMS fails

---

## Part 8: Recommended Configuration

### For Government Project (GFMS):

**Default:** Email OTP (free, reliable)
**Optional:** SMS OTP (user can enable)

**When to Force SMS:**
- First-time login (verify phone)
- High-value transactions (>KES 100,000)
- Admin actions (delete, approve)
- Password reset

**Cost Estimate:**
```
1000 users × 2 logins/day = 2000 logins/day

Scenario: 90% Email, 10% SMS
- Email: 1800 logins × FREE = FREE
- SMS: 200 logins × KES 0.80 = KES 160/day
- Monthly: KES 4,800 (~$37)

Very affordable! ✅
```

---

## Summary

### To Go Live:
1. ✅ Upgrade Africa's Talking account (1-2 days)
2. ✅ Top up KES 5,000
3. ✅ Update `.env` with live credentials
4. ✅ Restart services
5. ✅ Test with real phone

### User Preference:
- **Option A:** Store in database (recommended)
- **Option B:** Ask every time (your suggestion)
- **Option C:** Hybrid (default email, optional SMS)

### Cost Control:
- Email default (free)
- SMS optional (KES 0.80)
- Monitor dashboard
- Set spending alerts

**You're ready for production!** 🚀
