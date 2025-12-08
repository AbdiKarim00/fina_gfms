#!/bin/bash

echo "🔍 Testing Audit Logging System"
echo "================================"
echo ""

BASE_URL="http://localhost:8000/api/v1"

echo "Test 1: Failed login (invalid personal number)"
echo "----------------------------------------------"
curl -s -X POST $BASE_URL/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"000000","password":"wrongpass","otp_channel":"email"}'
echo ""
echo ""

echo "Test 2: Failed login (wrong password)"
echo "-------------------------------------"
curl -s -X POST $BASE_URL/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"wrongpass","otp_channel":"email"}'
echo ""
echo ""

echo "Test 3: Successful login (generates OTP)"
echo "----------------------------------------"
LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password","otp_channel":"email"}')
echo $LOGIN_RESPONSE
USER_ID=$(echo $LOGIN_RESPONSE | grep -o '"user_id":[0-9]*' | grep -o '[0-9]*')
echo ""
echo "User ID: $USER_ID"
echo ""

echo "Test 4: Get OTP from system"
echo "---------------------------"
OTP_RESPONSE=$(curl -s $BASE_URL/dev/otps/$USER_ID/email)
echo $OTP_RESPONSE
OTP_CODE=$(echo $OTP_RESPONSE | grep -o '"otp":"[0-9]*"' | grep -o '[0-9]*')
echo ""
echo "OTP Code: $OTP_CODE"
echo ""

echo "Test 5: Failed OTP verification (wrong code)"
echo "--------------------------------------------"
curl -s -X POST $BASE_URL/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":$USER_ID,\"code\":\"000000\",\"otp_channel\":\"email\"}"
echo ""
echo ""

echo "Test 6: Successful OTP verification"
echo "-----------------------------------"
VERIFY_RESPONSE=$(curl -s -X POST $BASE_URL/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":$USER_ID,\"code\":\"$OTP_CODE\",\"otp_channel\":\"email\"}")
echo $VERIFY_RESPONSE
TOKEN=$(echo $VERIFY_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
echo ""
echo "Token: ${TOKEN:0:50}..."
echo ""

echo "Test 7: Access protected route without permission"
echo "-------------------------------------------------"
curl -s -X POST $BASE_URL/vehicles \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN"
echo ""
echo ""

echo "Test 8: Logout"
echo "-------------"
curl -s -X POST $BASE_URL/auth/logout \
  -H "Authorization: Bearer $TOKEN"
echo ""
echo ""

echo "Test 9: View Activity Logs (last 10)"
echo "------------------------------------"
curl -s $BASE_URL/dev/activity-logs
echo ""
echo ""

echo "✅ Audit logging tests complete!"
echo ""
echo "Summary of logged activities:"
echo "- Failed login attempts"
echo "- Successful login with OTP generation"
echo "- Failed OTP verification"
echo "- Successful OTP verification"
echo "- Unauthorized access attempts"
echo "- User logout"
