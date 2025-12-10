#!/bin/bash

# Quick RBAC Testing Script
# Tests login for all 5 roles

API_URL="http://localhost:8000/api"
BOLD='\033[1m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BOLD}${BLUE}================================${NC}"
echo -e "${BOLD}${BLUE}   RBAC Quick Test Script${NC}"
echo -e "${BOLD}${BLUE}================================${NC}\n"

# Test users
declare -A USERS=(
    ["Super Admin"]="100000"
    ["Admin"]="123456"
    ["Fleet Manager"]="234567"
    ["Transport Officer"]="345678"
    ["Driver"]="654321"
)

test_login() {
    local role=$1
    local personal_number=$2
    
    echo -e "${BOLD}Testing: ${role}${NC}"
    echo -e "Personal Number: ${personal_number}"
    
    # Step 1: Login
    response=$(curl -s -X POST "${API_URL}/auth/login" \
        -H "Content-Type: application/json" \
        -d "{\"personal_number\":\"${personal_number}\",\"password\":\"password\"}")
    
    user_id=$(echo $response | jq -r '.data.user_id')
    
    if [ "$user_id" != "null" ] && [ -n "$user_id" ]; then
        echo -e "${GREEN}✓ Login successful (User ID: ${user_id})${NC}"
        
        # Get OTP from logs (last OTP generated)
        echo -e "${YELLOW}→ Check backend logs for OTP code${NC}"
        echo -e "${YELLOW}→ Or use OTP viewer: http://localhost:8000/dev/otp-viewer${NC}"
        
    else
        echo -e "${RED}✗ Login failed${NC}"
        echo "Response: $response"
    fi
    
    echo ""
}

# Test all users
for role in "${!USERS[@]}"; do
    test_login "$role" "${USERS[$role]}"
done

echo -e "${BOLD}${BLUE}================================${NC}"
echo -e "${BOLD}${GREEN}Testing Complete!${NC}"
echo -e "${BOLD}${BLUE}================================${NC}\n"

echo -e "${BOLD}Next Steps:${NC}"
echo "1. Open frontend: http://localhost:5173"
echo "2. Login with any personal number above"
echo "3. Get OTP from: http://localhost:8000/dev/otp-viewer"
echo "4. Verify you see the correct dashboard for each role"
echo ""
echo -e "${BOLD}Expected Dashboards:${NC}"
echo "  100000 → Super Admin Dashboard"
echo "  123456 → Admin Dashboard"
echo "  234567 → Fleet Manager Dashboard"
echo "  345678 → Transport Officer Dashboard"
echo "  654321 → Driver Dashboard"
