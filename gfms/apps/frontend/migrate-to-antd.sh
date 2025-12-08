#!/bin/bash

echo "════════════════════════════════════════════════════════════"
echo "  Ant Design Migration Script"
echo "════════════════════════════════════════════════════════════"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Backup
echo -e "${YELLOW}Step 1: Creating backup...${NC}"
git add .
git commit -m "Backup before Ant Design migration" || echo "No changes to commit"
git branch antd-migration
echo -e "${GREEN}✓ Backup created on branch 'antd-migration'${NC}"
echo ""

# Step 2: Check for unused packages
echo -e "${YELLOW}Step 2: Checking for unused packages...${NC}"
if grep -q "@inertiajs/react" package.json; then
  echo -e "${YELLOW}⚠ Found @inertiajs/react - checking usage...${NC}"
  if ! grep -r "inertia" src/ --include="*.tsx" --include="*.ts" > /dev/null 2>&1; then
    echo -e "${RED}  Not used in codebase - will remove${NC}"
    REMOVE_INERTIA=true
  else
    echo -e "${GREEN}  Used in codebase - will keep${NC}"
    REMOVE_INERTIA=false
  fi
fi
echo ""

# Step 3: Remove redundant packages
echo -e "${YELLOW}Step 3: Removing redundant packages...${NC}"
npm uninstall @headlessui/react @heroicons/react @tailwindcss/forms
if [ "$REMOVE_INERTIA" = true ]; then
  npm uninstall @inertiajs/react
fi
echo -e "${GREEN}✓ Removed redundant packages${NC}"
echo ""

# Step 4: Install Ant Design
echo -e "${YELLOW}Step 4: Installing Ant Design...${NC}"
npm install antd @ant-design/icons dayjs
echo -e "${GREEN}✓ Ant Design installed${NC}"
echo ""

# Step 5: Optional packages
echo -e "${YELLOW}Step 5: Optional packages${NC}"
read -p "Install @ant-design/charts for dashboards? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
  npm install @ant-design/charts
  echo -e "${GREEN}✓ Charts installed${NC}"
fi
echo ""

read -p "Install @ant-design/pro-components for advanced tables? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
  npm install @ant-design/pro-components
  echo -e "${GREEN}✓ Pro components installed${NC}"
fi
echo ""

# Step 6: Summary
echo "════════════════════════════════════════════════════════════"
echo -e "${GREEN}  Migration Complete!${NC}"
echo "════════════════════════════════════════════════════════════"
echo ""
echo "Next steps:"
echo "1. Update tailwind.config.js (see ANTD_INTEGRATION_PLAN.md)"
echo "2. Create src/theme/antd-theme.ts"
echo "3. Update src/App.tsx with ConfigProvider"
echo "4. Update src/index.css import order"
echo "5. Start migrating components"
echo ""
echo "Documentation: See ANTD_INTEGRATION_PLAN.md"
echo ""
