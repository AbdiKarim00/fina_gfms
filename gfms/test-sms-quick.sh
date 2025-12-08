#!/bin/bash

echo "🚀 Quick SMS OTP Test"
echo "===================="
echo ""

# Step 1: Login
echo "Step 1: Logging in..."
LOGIN_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"999999","password":"password","otp_channel":"sms"}')

echo "$LOGIN_RESPONSE"
echo ""

# Extract user_id
USER_ID=$(echo $LOGIN_RESPONSE | grep -o '"user_id":[0-9]*' | grep -o '[0-9]*')

if [ -z "$USER_ID" ]; then
    echo "❌ Login failed!"
    exit 1
fi

echo "✅ Login successful! User ID: $USER_ID"
echo ""

# Step 2: Get OTP immediately
echo "Step 2: Getting OTP from Redis..."
sleep 1
OTP_RESPONSE=$(curl -s http://localhost:8000/api/v1/dev/otps/$USER_ID/sms)
echo "$OTP_RESPONSE"
echo ""

# Extract OTP
OTP=$(echo $OTP_RESPONSE | grep -o '"otp":"[0-9]*"' | grep -o '[0-9]*')

if [ -z "$OTP" ]; then
    echo "❌ Could not get OTP!"
    echo "Try opening: http://localhost:8000/otp-viewer.html"
    exit 1
fi

echo "✅ OTP Retrieved: $OTP"
echo ""

# Step 3: Verify OTP
echo "Step 3: Verifying OTP..."
VERIFY_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":$USER_ID,\"code\":\"$OTP\",\"otp_channel\":\"sms\"}")

echo "$VERIFY_RESPONSE"
echo ""

# Check if successful
if echo "$VERIFY_RESPONSE" | grep -q '"success":true'; then
    echo "🎉 SUCCESS! Authentication complete!"
    echo ""
    
    # Extract token
    TOKEN=$(echo $VERIFY_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
    echo "Token: ${TOKEN:0:50}..."
    echo ""
    
    # Test /me endpoint
    echo "Testing /me endpoint..."
    ME_RESPONSE=$(curl -s http://localhost:8000/api/v1/auth/me \
      -H "Authorization: Bearer $TOKEN")
    
    echo "$ME_RESPONSE" | grep -o '"name":"[^"]*"'
    echo ""
    echo "✅ Full authentication flow working!"
else
    echo "❌ OTP verification failed!"
fi
