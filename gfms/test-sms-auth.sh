#!/bin/bash

echo "=== Testing GFMS SMS Authentication Flow ==="
echo ""

# Step 1: Login with SMS OTP
echo "Step 1: Login with Personal Number and Password (SMS OTP)"
RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password","otp_channel":"sms"}')

echo "Response: $RESPONSE"
echo ""

# Extract user_id
USER_ID=$(echo $RESPONSE | grep -o '"user_id":[0-9]*' | grep -o '[0-9]*')
echo "User ID: $USER_ID"
echo ""

# Step 2: Get OTP from Redis
echo "Step 2: Fetching OTP from Redis (SMS channel)..."
OTP=$(docker exec gfms_redis redis-cli GET "gfms_backend_database_otp:sms:$USER_ID")
echo "OTP: $OTP"
echo ""

# Step 3: Check Laravel logs for SMS
echo "Step 3: Checking Laravel logs for SMS..."
docker exec gfms_app tail -5 storage/logs/laravel.log | grep "SMS"
echo ""

# Step 4: Verify OTP
if [ ! -z "$OTP" ]; then
    echo "Step 4: Verifying SMS OTP..."
    VERIFY_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/verify-otp \
      -H "Content-Type: application/json" \
      -d "{\"user_id\":$USER_ID,\"code\":\"$OTP\",\"otp_channel\":\"sms\"}")
    
    echo "Verify Response: $VERIFY_RESPONSE"
    echo ""
    
    # Extract token
    TOKEN=$(echo $VERIFY_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
    
    if [ ! -z "$TOKEN" ]; then
        echo "✓ SMS Authentication successful!"
        echo "Token: ${TOKEN:0:50}..."
    else
        echo "✗ OTP verification failed"
    fi
else
    echo "✗ No OTP found in Redis"
    echo ""
    echo "Note: SMS is currently disabled. Check logs:"
    echo "docker exec gfms_app tail -f storage/logs/laravel.log | grep SMS"
fi

echo ""
echo "=== SMS Setup Instructions ==="
echo "1. Sign up at https://africastalking.com"
echo "2. Get sandbox API key (free 100 SMS)"
echo "3. Update .env:"
echo "   AFRICASTALKING_API_KEY=your_key_here"
echo "   AFRICASTALKING_ENABLED=true"
echo "4. Restart: docker restart gfms_app gfms_queue"
echo ""
echo "See SMS_SETUP_GUIDE.md for detailed instructions"
