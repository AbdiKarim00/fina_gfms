#!/bin/bash

echo "=== Testing GFMS Authentication Flow ==="
echo ""

# Step 1: Login
echo "Step 1: Login with Personal Number and Password"
RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password"}')

echo "Response: $RESPONSE"
echo ""

# Extract user_id
USER_ID=$(echo $RESPONSE | grep -o '"user_id":[0-9]*' | grep -o '[0-9]*')
echo "User ID: $USER_ID"
echo ""

# Step 2: Get OTP from Redis
echo "Step 2: Fetching OTP from Redis..."
OTP=$(docker exec gfms_redis redis-cli GET "gfms_backend_database_otp:email:$USER_ID")
echo "OTP: $OTP"
echo ""

# Step 3: Verify OTP
if [ ! -z "$OTP" ]; then
    echo "Step 3: Verifying OTP..."
    VERIFY_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/verify-otp \
      -H "Content-Type: application/json" \
      -d "{\"user_id\":$USER_ID,\"code\":\"$OTP\"}")
    
    echo "Verify Response: $VERIFY_RESPONSE"
    echo ""
    
    # Extract token
    TOKEN=$(echo $VERIFY_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
    
    if [ ! -z "$TOKEN" ]; then
        echo "✓ Authentication successful!"
        echo "Token: ${TOKEN:0:50}..."
        echo ""
        
        # Step 4: Test /me endpoint
        echo "Step 4: Testing /me endpoint..."
        ME_RESPONSE=$(curl -s -X GET http://localhost:8000/api/v1/auth/me \
          -H "Authorization: Bearer $TOKEN")
        echo "Me Response: $ME_RESPONSE"
    else
        echo "✗ OTP verification failed"
    fi
else
    echo "✗ No OTP found in Redis"
    echo "Check MailHog at http://localhost:8025 for the OTP email"
fi
