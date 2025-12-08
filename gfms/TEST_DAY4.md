# Test Day 4 - VehiclesPageV2 & Sidebar

## Quick Test (5 minutes)

### Step 1: Start Dev Server
```bash
cd gfms/apps/frontend
npm run dev
```

### Step 2: Login
- Navigate to http://localhost:3000
- Personal Number: `123456`
- Password: `password`
- Enter OTP from backend terminal or http://localhost:8000/dev/otp-viewer

### Step 3: Test New Sidebar
- ✅ Check sidebar is visible on left
- ✅ Click collapse/expand button (top left)
- ✅ Verify sidebar collapses to icons only
- ✅ Click Dashboard menu item
- ✅ Click Vehicles menu item
- ✅ Check active menu item is highlighted

### Step 4: Test User Dropdown
- ✅ Click on user avatar (top right)
- ✅ Verify dropdown shows Profile and Logout
- ✅ Check avatar has Kenya green color (#006600)

### Step 5: Test VehiclesPageV2
- ✅ Navigate to Vehicles page
- ✅ Check table displays vehicles
- ✅ Test search box (type registration number)
- ✅ Test status filter dropdown
- ✅ Test fuel type filter dropdown
- ✅ Click "Reset Filters" button
- ✅ Click column headers to sort
- ✅ Test pagination (if more than 10 vehicles)
- ✅ Hover over action buttons (view, edit, delete)

### Step 6: Test Responsive Design
- ✅ Resize browser window
- ✅ Check sidebar on mobile (should collapse)
- ✅ Check table on mobile (should scroll horizontally)
- ✅ Check filters on mobile (should stack vertically)

---

## What to Look For

### ✅ Good Signs
- Sidebar visible and collapsible
- Menu items clickable and highlighted
- User dropdown works
- Table displays vehicles
- Search filters results
- Status/fuel filters work
- Reset button clears filters
- Sorting works on columns
- Pagination works
- Action buttons have tooltips
- Kenya green color on avatar and icons

### ❌ Bad Signs
- Sidebar not visible
- Menu items not clickable
- Table not displaying
- Filters not working
- Console errors
- Layout broken on mobile

---

## If Everything Works

### Delete Old VehiclesPage
```bash
cd gfms/apps/frontend/src/pages
rm VehiclesPage.tsx
cd ../../../
git add -A
git commit -m "Remove old VehiclesPage - new one working perfectly"
```

---

## If Something Breaks

### Rollback
```bash
cd gfms
git reset --hard HEAD~2
npm run dev
```

---

## Expected Results

### Sidebar
- Collapsible sidebar with menu items
- Active route highlighted
- User dropdown with avatar
- Kenya green branding

### Vehicles Page
- Modern Ant Design table
- Search by registration/make/model
- Filter by status and fuel type
- Sortable columns
- Pagination
- Action buttons with tooltips
- Responsive design

---

**Time:** 5 minutes  
**Risk:** 🟢 ZERO (easy rollback)
