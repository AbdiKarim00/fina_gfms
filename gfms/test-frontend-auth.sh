#!/bin/bash

# Test Frontend Authentication Flow
# This script tests the complete two-step OTP authentication

echo "════════════════════════════════════════════════════════════"
echo "  Testing Frontend Authentication Flow"
echo "════════════════════════════════════════════════════════════"
echo ""

API_URL="http://localhost:8000/api/v1"

# Step 1: Login with personal number and password
echo "Step 1: Login with personal number..."
LOGIN_RESPONSE=$(curl -s -X POST "$API_URL/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "personal_number": "123456",
    "password": "password"
  }')

echo "$LOGIN_RESPONSE" | jq '.'
echo ""

# Extract user_id
USER_ID=$(echo "$LOGIN_RESPONSE" | jq -r '.data.user_id')
OTP_CHANNEL=$(echo "$LOGIN_RESPONSE" | jq -r '.data.otp_channel')

if [ "$USER_ID" == "null" ] || [ -z "$USER_ID" ]; then
  echo "❌ Login failed!"
  exit 1
fi

echo "✅ Login successful! User ID: $USER_ID, Channel: $OTP_CHANNEL"
echo ""

# Step 2: Get OTP from dev endpoint
echo "Step 2: Fetching OTP from dev endpoint..."
OTP_RESPONSE=$(curl -s "$API_URL/dev/otps/$USER_ID/$OTP_CHANNEL")
echo "$OTP_RESPONSE" | jq '.'
echo ""

OTP_CODE=$(echo "$OTP_RESPONSE" | jq -r '.data.code')

if [ "$OTP_CODE" == "null" ] || [ -z "$OTP_CODE" ]; then
  echo "❌ Failed to get OTP!"
  exit 1
fi

echo "✅ OTP retrieved: $OTP_CODE"
echo ""

# Step 3: Verify OTP
echo "Step 3: Verifying OTP..."
VERIFY_RESPONSE=$(curl -s -X POST "$API_URL/auth/verify-otp" \
  -H "Content-Type: application/json" \
  -d "{
    \"user_id\": $USER_ID,
    \"code\": \"$OTP_CODE\",
    \"otp_channel\": \"$OTP_CHANNEL\"
  }")

echo "$VERIFY_RESPONSE" | jq '.'
echo ""

TOKEN=$(echo "$VERIFY_RESPONSE" | jq -r '.data.token')

if [ "$TOKEN" == "null" ] || [ -z "$TOKEN" ]; then
  echo "❌ OTP verification failed!"
  exit 1
fi

echo "✅ OTP verified! Token received."
echo ""

# Step 4: Test authenticated endpoint
echo "Step 4: Testing authenticated endpoint (/auth/me)..."
ME_RESPONSE=$(curl -s "$API_URL/auth/me" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$ME_RESPONSE" | jq '.'
echo ""

USER_NAME=$(echo "$ME_RESPONSE" | jq -r '.data.name')

if [ "$USER_NAME" == "null" ] || [ -z "$USER_NAME" ]; then
  echo "❌ Failed to get user data!"
  exit 1
fi

echo "✅ Authenticated! User: $USER_NAME"
echo ""

# Display user roles and permissions
echo "════════════════════════════════════════════════════════════"
echo "  User Details"
echo "════════════════════════════════════════════════════════════"
echo "Name: $(echo "$ME_RESPONSE" | jq -r '.data.name')"
echo "Email: $(echo "$ME_RESPONSE" | jq -r '.data.email')"
echo "Personal Number: $(echo "$ME_RESPONSE" | jq -r '.data.personal_number')"
echo "Organization: $(echo "$ME_RESPONSE" | jq -r '.data.organization.name')"
echo ""
echo "Roles:"
echo "$ME_RESPONSE" | jq -r '.data.roles[]' | sed 's/^/  - /'
echo ""
echo "Permissions (first 10):"
echo "$ME_RESPONSE" | jq -r '.data.permissions[:10][]' | sed 's/^/  - /'
echo "  ... and more"
echo ""

echo "════════════════════════════════════════════════════════════"
echo "  ✅ All tests passed!"
echo "════════════════════════════════════════════════════════════"
echo ""
echo "Frontend Flow:"
echo "  1. User enters personal number (123456) and password"
echo "  2. Frontend navigates to /verify-otp page"
echo "  3. User enters OTP from email/OTP viewer"
echo "  4. Frontend receives token and user data with RBAC"
echo "  5. User is redirected to dashboard"
echo ""
echo "Dev Tools:"
echo "  - OTP Viewer: http://localhost:8000/otp-viewer.html"
echo "  - MailHog: http://localhost:8025"
echo ""
