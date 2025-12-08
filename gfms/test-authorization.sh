#!/bin/bash

echo "═══════════════════════════════════════════════════════════════"
echo "  GFMS Authorization Testing"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Step 1: Login as Admin
echo "Step 1: Login as Admin (has view_vehicles permission)"
echo "────────────────────────────────────────────────────────────────"
LOGIN_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"personal_number":"123456","password":"password"}')

echo "$LOGIN_RESPONSE"
echo ""

USER_ID=$(echo $LOGIN_RESPONSE | grep -o '"user_id":[0-9]*' | grep -o '[0-9]*')

if [ -z "$USER_ID" ]; then
    echo "❌ Login failed!"
    exit 1
fi

# Step 2: Get OTP
echo "Step 2: Getting OTP..."
echo "────────────────────────────────────────────────────────────────"
sleep 1
OTP=$(curl -s http://localhost:8000/api/v1/dev/otps/$USER_ID/email | grep -o '"otp":"[0-9]*"' | grep -o '[0-9]*')

if [ -z "$OTP" ]; then
    echo "❌ Could not get OTP!"
    exit 1
fi

echo "✅ OTP: $OTP"
echo ""

# Step 3: Verify OTP and get token
echo "Step 3: Verifying OTP..."
echo "────────────────────────────────────────────────────────────────"
VERIFY_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":$USER_ID,\"code\":\"$OTP\"}")

TOKEN=$(echo $VERIFY_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo "❌ OTP verification failed!"
    exit 1
fi

echo "✅ Token obtained: ${TOKEN:0:50}..."
echo ""

# Step 4: Test permission-based route (should succeed)
echo "Step 4: Testing Permission-Based Route (view_vehicles)"
echo "────────────────────────────────────────────────────────────────"
VEHICLES_RESPONSE=$(curl -s http://localhost:8000/api/v1/vehicles \
  -H "Authorization: Bearer $TOKEN")

echo "$VEHICLES_RESPONSE"
echo ""

if echo "$VEHICLES_RESPONSE" | grep -q '"success":true'; then
    echo "✅ Permission check passed!"
else
    echo "❌ Permission check failed!"
fi
echo ""

# Step 5: Test role-based route (should succeed for Admin)
echo "Step 5: Testing Role-Based Route (Admin)"
echo "────────────────────────────────────────────────────────────────"
ADMIN_RESPONSE=$(curl -s http://localhost:8000/api/v1/admin/dashboard \
  -H "Authorization: Bearer $TOKEN")

echo "$ADMIN_RESPONSE"
echo ""

if echo "$ADMIN_RESPONSE" | grep -q '"success":true'; then
    echo "✅ Role check passed!"
else
    echo "❌ Role check failed!"
fi
echo ""

# Step 6: Test Super Admin route (should fail for Admin)
echo "Step 6: Testing Super Admin Route (should fail)"
echo "────────────────────────────────────────────────────────────────"
SUPER_ADMIN_RESPONSE=$(curl -s http://localhost:8000/api/v1/admin/system-settings \
  -H "Authorization: Bearer $TOKEN")

echo "$SUPER_ADMIN_RESPONSE"
echo ""

if echo "$SUPER_ADMIN_RESPONSE" | grep -q '"success":false'; then
    echo "✅ Authorization correctly denied!"
else
    echo "❌ Authorization should have been denied!"
fi
echo ""

# Step 7: Test without token (should fail)
echo "Step 7: Testing Without Token (should fail)"
echo "────────────────────────────────────────────────────────────────"
NO_TOKEN_RESPONSE=$(curl -s http://localhost:8000/api/v1/vehicles)

echo "$NO_TOKEN_RESPONSE"
echo ""

if echo "$NO_TOKEN_RESPONSE" | grep -q 'Unauthenticated'; then
    echo "✅ Correctly requires authentication!"
else
    echo "❌ Should require authentication!"
fi
echo ""

echo "═══════════════════════════════════════════════════════════════"
echo "  Authorization Testing Complete!"
echo "═══════════════════════════════════════════════════════════════"
