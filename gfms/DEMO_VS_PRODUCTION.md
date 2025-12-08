# 🎭 Demo vs Production Setup Guide

## Perfect Setup for Both Scenarios

Your GFMS system now supports **two modes**:

1. **Demo Mode** - For presentations, testing, stakeholder demos
2. **Production Mode** - For real users with real SMS

---

## 🎬 Demo Mode (Current Setup)

### What It Does:
- ✅ Generates real OTPs
- ✅ Stores in Redis (5-minute expiry)
- ✅ Shows in OTP Viewer
- ❌ **Does NOT send real SMS** (no costs!)
- ✅ Perfect for demos

### Configuration:
```env
# .env file
SMS_DEMO_MODE=true
AFRICASTALKING_ENABLED=true
```

### How to Use:

#### Step 1: Open OTP Viewer
```
http://localhost:8000/otp-viewer.html
```
Keep this open on a second screen or tab.

#### Step 2: Login with SMS
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password","otp_channel":"sms"}'
```

#### Step 3: Show OTP Viewer
The OTP appears instantly in the viewer:
```
┌─────────────────────────┐
│   SMS                   │
│   795400                │
│   User ID: 2            │
│   ⏱️ Expires in 4.8 min │
│   [📋 Copy OTP]         │
└─────────────────────────┘
```

#### Step 4: Verify OTP
Copy from viewer and verify.

### Perfect For:
- 🎤 **Stakeholder presentations**
- 👥 **Client demos**
- 🧪 **Testing & QA**
- 📚 **Training sessions**
- 💻 **Development**
- 🎓 **User onboarding demos**

### Benefits:
- ✅ **Zero SMS costs**
- ✅ **Instant OTP display**
- ✅ **Works offline**
- ✅ **No phone needed**
- ✅ **Beautiful UI for demos**
- ✅ **Repeatable tests**

---

## 🚀 Production Mode

### What It Does:
- ✅ Generates real OTPs
- ✅ Stores in Redis
- ✅ **Sends real SMS to phones**
- ✅ Users receive SMS
- 💰 Costs KES 0.80 per SMS

### Configuration:
```env
# .env file
SMS_DEMO_MODE=false
AFRICASTALKING_ENABLED=true
AFRICASTALKING_USERNAME=YourCompanyName
AFRICASTALKING_API_KEY=your_live_api_key
```

### Setup Steps:

#### 1. Upgrade Africa's Talking (1-2 days)
- Go to https://account.africastalking.com
- Click "Go Live"
- Submit business documents
- Wait for approval

#### 2. Top Up Account
- Add KES 5,000+ via M-Pesa
- Cost: KES 0.80 per SMS

#### 3. Update `.env`
```env
SMS_DEMO_MODE=false
AFRICASTALKING_USERNAME=YourCompanyName
AFRICASTALKING_API_KEY=live_key_here
AFRICASTALKING_ENABLED=true
```

#### 4. Restart Services
```bash
docker restart gfms_app gfms_queue
```

### Perfect For:
- 🏢 **Production deployment**
- 👤 **Real users**
- 🔐 **Actual authentication**
- 📱 **Mobile users**

---

## 🔄 Switching Between Modes

### Switch to Demo Mode:
```env
SMS_DEMO_MODE=true
```
```bash
docker restart gfms_app gfms_queue
```

### Switch to Production Mode:
```env
SMS_DEMO_MODE=false
```
```bash
docker restart gfms_app gfms_queue
```

**That's it!** No code changes needed.

---

## 📊 Comparison Table

| Feature | Demo Mode | Production Mode |
|---------|-----------|-----------------|
| **OTP Generation** | ✅ Yes | ✅ Yes |
| **Redis Storage** | ✅ Yes | ✅ Yes |
| **OTP Viewer** | ✅ Works | ✅ Works |
| **Real SMS Sent** | ❌ No | ✅ Yes |
| **Cost** | 💰 FREE | 💰 KES 0.80/SMS |
| **Phone Required** | ❌ No | ✅ Yes |
| **Internet Required** | ✅ Yes | ✅ Yes |
| **Best For** | Demos, Testing | Real Users |

---

## 🎯 Recommended Workflow

### Phase 1: Development (Demo Mode)
```env
SMS_DEMO_MODE=true
```
- Build features
- Test authentication
- Use OTP Viewer
- Zero costs

### Phase 2: Stakeholder Demo (Demo Mode)
```env
SMS_DEMO_MODE=true
```
- Present to stakeholders
- Show OTP Viewer on screen
- Demonstrate full flow
- No SMS costs

### Phase 3: UAT Testing (Demo Mode)
```env
SMS_DEMO_MODE=true
```
- User acceptance testing
- QA team testing
- Use OTP Viewer
- Repeatable tests

### Phase 4: Production (Production Mode)
```env
SMS_DEMO_MODE=false
```
- Deploy to production
- Real SMS to users
- Monitor costs
- Track delivery

---

## 🎬 Demo Presentation Tips

### Setup Before Demo:

1. **Open OTP Viewer** in browser
   ```
   http://localhost:8000/otp-viewer.html
   ```

2. **Enable Demo Mode**
   ```env
   SMS_DEMO_MODE=true
   ```

3. **Test Login** to verify it works

### During Demo:

1. **Show Login Screen**
   - User enters Personal Number
   - User enters Password
   - User selects "SMS" option

2. **Show OTP Viewer** (on second screen/projector)
   - OTP appears instantly
   - Show expiry countdown
   - Highlight security features

3. **Copy & Verify**
   - Click "Copy OTP" button
   - Paste in verification screen
   - Show successful login

4. **Show Dashboard**
   - User authenticated
   - Show roles & permissions
   - Demonstrate features

### Talking Points:

- ✅ "OTP sent to user's phone"
- ✅ "6-digit code, expires in 5 minutes"
- ✅ "Secure two-factor authentication"
- ✅ "Works with Email or SMS"
- ✅ "User can choose preferred method"

---

## 💰 Cost Estimates

### Demo Mode:
```
Unlimited logins: FREE
Unlimited tests: FREE
Perfect for development: FREE
```

### Production Mode:
```
1000 daily logins × KES 0.80 = KES 800/day
Monthly: KES 24,000 (~$185)
Yearly: KES 288,000 (~$2,220)
```

**Recommendation:** Use Email OTP as default (free), SMS as optional.

---

## 🔍 Monitoring

### Demo Mode:
- Check OTP Viewer
- Check Laravel logs
- No delivery tracking needed

### Production Mode:
- Check Africa's Talking dashboard
- Monitor delivery rates
- Track costs
- Set spending alerts

---

## ✅ Quick Reference

### Demo Mode Commands:
```bash
# Enable demo mode
echo "SMS_DEMO_MODE=true" >> .env
docker restart gfms_app gfms_queue

# Open OTP Viewer
open http://localhost:8000/otp-viewer.html

# Test login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password","otp_channel":"sms"}'
```

### Production Mode Commands:
```bash
# Enable production mode
echo "SMS_DEMO_MODE=false" >> .env
docker restart gfms_app gfms_queue

# Monitor logs
docker logs gfms_queue --tail 50 -f
```

---

## 🎉 Summary

**You have the perfect setup!**

- ✅ **Demo Mode** - Free, instant, perfect for presentations
- ✅ **Production Mode** - Real SMS, reliable, affordable
- ✅ **OTP Viewer** - Beautiful UI like MailHog
- ✅ **One Toggle** - Switch between modes easily
- ✅ **Africa's Talking** - Best for Kenya production

**Current Status:** Demo Mode (perfect for testing & demos)
**Next Step:** When ready for production, switch to Production Mode

🎯 **You're all set for both demos and production!**
