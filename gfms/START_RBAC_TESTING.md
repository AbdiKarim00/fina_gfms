# 🚀 Start RBAC Testing - Quick Guide

**Status**: ✅ READY TO TEST  
**Date**: December 9, 2025

---

## ⚡ Quick Start (3 Steps)

### Step 1: Start Backend
```bash
cd gfms
make up
```

Wait for: `✓ Services started`

### Step 2: Start Frontend
```bash
cd gfms/apps/frontend
npm run dev
```

Wait for: `Local: http://localhost:5173/`

### Step 3: Test Login
Open browser: http://localhost:5173

---

## 🔑 Test Credentials

| Role | Personal Number | Password | Expected Dashboard |
|------|----------------|----------|-------------------|
| Super Admin | `100000` | `password` | Super Admin Dashboard |
| Admin | `123456` | `password` | Admin Dashboard |
| Fleet Manager | `234567` | `password` | Fleet Manager Dashboard |
| Transport Officer | `345678` | `password` | Transport Officer Dashboard |
| Driver | `654321` | `password` | Driver Dashboard |

---

## 📱 OTP Verification

After entering credentials, you'll need an OTP code.

### Development Settings (Lenient)
- ✅ OTP valid for **15 minutes** (plenty of time!)
- ✅ **10 attempts** allowed (no more "Too Many Attempts"!)
- ✅ Shows remaining attempts in error message

### Option 1: OTP Viewer (Easiest)
Open: http://localhost:8000/dev/otp-viewer

### Option 2: Backend Logs
```bash
cd gfms
make logs service=app
```

Look for: `OTP Code: XXXXXX`

---

## ✅ What to Verify

### For Each Role:

1. **Login Screen**
   - [ ] Personal number field (not email)
   - [ ] Password field
   - [ ] Demo credentials shown

2. **OTP Screen**
   - [ ] OTP input field
   - [ ] Verify button
   - [ ] Can get OTP from viewer

3. **Dashboard**
   - [ ] Correct dashboard loads for role
   - [ ] Role name shows under "Kenya GFMS" logo
   - [ ] Statistics cards display correctly
   - [ ] No console errors

4. **Sidebar Navigation**
   - [ ] Correct menu items for role
   - [ ] "Dev" badges on incomplete features
   - [ ] Active route highlighted
   - [ ] Sidebar can collapse/expand

5. **Navigation**
   - [ ] Click "Vehicles" - should work
   - [ ] Click "Dev" items - should show "Under Development"
   - [ ] Back button works on dev pages

6. **Logout**
   - [ ] Click user avatar → Logout
   - [ ] Redirects to login page
   - [ ] Can't access dashboard without login

---

## 🎯 Expected Behavior by Role

### Super Admin (100000)
**Menu Items**: Dashboard, User Management (Dev), Organizations (Dev), Roles & Permissions (Dev), Vehicles, System Reports (Dev), System Settings (Dev)

**Dashboard Stats**:
- Total Users: 5
- Organizations: 3
- Total Vehicles: 6
- Roles: 5

### Admin (123456)
**Menu Items**: Dashboard, Vehicles, Users (Dev), Bookings (Dev), Maintenance (Dev), Reports (Dev)

**Dashboard Stats**:
- Total Vehicles: 6
- Active Bookings: 0
- Maintenance Due: 1
- Users: 5

### Fleet Manager (234567)
**Menu Items**: Dashboard, Vehicles, Bookings (Dev), Maintenance (Dev), Fuel Management (Dev), Reports (Dev)

**Dashboard**: Comprehensive fleet management dashboard with vehicle stats and charts

### Transport Officer (345678)
**Menu Items**: Dashboard, My Bookings (Dev), Available Vehicles, Reports (Dev)

**Dashboard Stats**:
- My Bookings: 0
- Pending Approvals: 0
- Available Vehicles: 4

### Driver (654321)
**Menu Items**: Dashboard, My Assignments (Dev), Trip Logs (Dev), Fuel Records (Dev)

**Dashboard Stats**:
- Current Assignment: None
- Trips This Month: 0
- Total Distance: 0 km

---

## 🐛 Troubleshooting

### Backend Not Starting
```bash
cd gfms
make down
make up
```

### Frontend Not Starting
```bash
cd gfms/apps/frontend
rm -rf node_modules
npm install
npm run dev
```

### Database Issues
```bash
cd gfms
make fresh  # Resets database with fresh data
```

### Can't Get OTP
1. Check backend is running: http://localhost:8000
2. Open OTP viewer: http://localhost:8000/dev/otp-viewer
3. Check backend logs: `make logs service=app`

### "Too Many Attempts" Error
- **Fixed!** Development now allows 10 attempts (was 3)
- OTP valid for 15 minutes (was 5)
- See `OTP_SETTINGS_DEV.md` for details

### Wrong Dashboard Loads
1. Check browser console for errors
2. Verify user role in backend logs
3. Clear browser cache and try again

---

## 🧪 Quick Test Script

Run automated login tests:
```bash
cd gfms
./test-rbac-quick.sh
```

This will test login for all 5 roles and show you the results.

---

## 📊 Success Criteria

- [ ] All 5 roles can login
- [ ] Each role sees their specific dashboard
- [ ] Each role sees only their menu items
- [ ] Vehicles page works for all roles
- [ ] Under development pages display correctly
- [ ] Navigation is smooth
- [ ] Logout works
- [ ] No console errors

---

## 📚 Detailed Documentation

For comprehensive testing instructions, see:
- `RBAC_TESTING_GUIDE.md` - Full testing checklist
- `WEEK1_RBAC_COMPLETE.md` - Implementation summary
- `BACKEND_COMPLETE.md` - Backend details

---

## 🎉 Ready to Test!

Everything is set up and ready. Just follow the 3 steps above and start testing each role. The system should work smoothly with proper role-based access control.

**Questions?** Check the documentation files or review the implementation in:
- Frontend: `gfms/apps/frontend/src/pages/dashboards/`
- Backend: `gfms/apps/backend/database/seeders/`

Good luck with testing! 🚀
