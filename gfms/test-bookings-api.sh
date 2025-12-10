#!/bin/bash

echo "🧪 Testing Bookings API Endpoints"
echo "=================================="
echo ""

# Get auth token (you'll need to replace with actual token)
TOKEN="your_token_here"

echo "1️⃣ Testing GET /api/v1/bookings"
curl -X GET "http://localhost:8000/api/v1/bookings" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer ${TOKEN}" \
  -v

echo ""
echo ""
echo "2️⃣ Testing GET /api/v1/bookings/statistics"
curl -X GET "http://localhost:8000/api/v1/bookings/statistics" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer ${TOKEN}" \
  -v

echo ""
echo ""
echo "✅ Test complete. Check the responses above."
