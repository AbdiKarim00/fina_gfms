# 🚀 Quick RBAC Reference Card

## Start Testing (3 Commands)
```bash
cd gfms && make up                    # Start backend
cd gfms/apps/frontend && npm run dev  # Start frontend
open http://localhost:5173            # Open browser
```

## Test Credentials (Password: `password`)
```
Super Admin:       100000
Admin:             123456
Fleet Manager:     234567
Transport Officer: 345678
Driver:            654321
```

## Get OTP
http://localhost:8000/dev/otp-viewer

**Dev Settings**: 15 min expiry, 10 attempts ✅

## Expected Dashboards
- 100000 → Super Admin Dashboard (System Management)
- 123456 → Admin Dashboard (Organization Management)
- 234567 → Fleet Manager Dashboard (Fleet Operations)
- 345678 → Transport Officer Dashboard (Booking Management)
- 654321 → Driver Dashboard (Assignments & Trips)

## Quick Test
```bash
cd gfms && ./test-rbac-quick.sh
```

## Documentation
- `START_RBAC_TESTING.md` - Quick start guide
- `RBAC_TESTING_GUIDE.md` - Full testing checklist
- `RBAC_IMPLEMENTATION_COMPLETE.md` - Complete summary

## Status
✅ Build: 51 seconds  
✅ TypeScript: No errors  
✅ Ready: Production ready  
✅ Docs: Complete  
✅ Vehicle RBAC: Implemented

## Vehicle Module Permissions
- Super Admin/Admin: Full access (add, edit, delete)
- Fleet Manager: Manage (add, edit, no delete)
- Transport Officer: Limited edit (status/notes only)
- Driver: No access (future: read-only assigned vehicles)

## Troubleshooting
```bash
make down && make up     # Restart backend
make fresh               # Reset database
make logs service=app    # View logs
```

---
**Last Updated**: December 9, 2025  
**Status**: READY FOR TESTING ✅
