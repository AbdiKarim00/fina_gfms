# Quick Reference - Modular Build

## 🚀 Quick Start

```bash
# 1. Backup
git checkout -b backup-before-modular
git add . && git commit -m "Backup"

# 2. Create working branch
git checkout -b feature/modular-architecture

# 3. Start building
# Follow START_TODAY_GUIDE.md
```

---

## 📁 Folder Structure

```
src/
├── components/
│   └── shared/          # Shared components
│       ├── PageLoader.tsx
│       └── IconRegistry.tsx
├── hooks/               # Custom hooks
├── utils/               # Utility functions
├── pages/               # Page components
│   ├── DashboardPage.tsx
│   ├── DashboardPageV2.tsx  # New Ant Design version
│   ├── VehiclesPage.tsx
│   └── VehiclesPageV2.tsx   # New Ant Design version
├── layouts/             # Layout components
├── contexts/            # React contexts
├── services/            # API services
└── types/               # TypeScript types
```

---

## 🔧 Common Commands

### Development
```bash
npm run dev              # Start dev server
npm run build            # Production build
npm run preview          # Preview production build
```

### Testing
```bash
# After each change:
npm run dev              # Check if it works
# Open browser, test feature
# Check console for errors
```

### Git
```bash
git status               # Check changes
git add .                # Stage all changes
git commit -m "message"  # Commit
git push                 # Push to remote

# Rollback
git reset --hard HEAD~1  # Undo last commit
git checkout HEAD -- file.tsx  # Revert file
```

---

## 📊 Progress Tracking

Check: `MODULAR_BUILD_PROGRESS.md`

Update after each step:
- Mark checkbox as complete
- Note any issues
- Update status

---

## 🎯 Daily Goals

### Day 1 (2-3 hours)
✅ Create folder structure  
✅ Create shared components  
✅ Create new dashboard  
✅ Test & commit

### Day 2 (2-3 hours)
✅ Switch to new dashboard  
✅ Add lazy loading  
✅ Test & commit

### Day 3-4 (4 hours)
✅ Optimize Vite config  
✅ Lazy load all routes  
✅ Measure improvements

### Day 5-7 (6 hours)
✅ Migrate vehicles page  
✅ Polish & test  
✅ Production build

---

## ⚠️ Safety Rules

1. **Always commit before changes**
2. **Test after every change**
3. **One change at a time**
4. **Keep old code until new works**
5. **Easy rollback always available**

---

## 🐛 Troubleshooting

### Build fails
```bash
# Check error message
npm run build

# Common fixes:
# - Missing import
# - Typo in component name
# - Wrong file path
```

### Page doesn't load
```bash
# Check browser console
# Check network tab
# Check if route is correct
```

### Lazy loading breaks
```bash
# Remove Suspense wrapper
# Test without lazy loading
# Check import path
```

---

## 📈 Expected Results

### Build Time
- Before: 3-5 minutes
- After: 1-2 minutes ✅
- Improvement: 60% faster

### Bundle Size
- Before: 280KB initial
- After: 150KB initial ✅
- Improvement: 46% smaller

### Load Time
- Before: 3-4 seconds
- After: 1-2 seconds ✅
- Improvement: 50% faster

---

## 🎨 Ant Design Quick Reference

### Common Components
```typescript
import { 
  Button, 
  Card, 
  Table, 
  Form, 
  Input,
  Select,
  Modal,
  Drawer,
  Tag,
  Statistic,
  Row,
  Col,
  Space,
} from 'antd';
```

### Common Icons
```typescript
import {
  PlusOutlined,
  EditOutlined,
  DeleteOutlined,
  EyeOutlined,
  CarOutlined,
  UserOutlined,
} from '@ant-design/icons';
```

### Basic Usage
```typescript
// Button
<Button type="primary">Click</Button>

// Card
<Card title="Title">Content</Card>

// Table
<Table dataSource={data} columns={columns} />

// Form
<Form onFinish={handleSubmit}>
  <Form.Item name="field">
    <Input />
  </Form.Item>
</Form>
```

---

## 📚 Documentation

- **Main Plan:** `FLEET_MANAGER_MODULAR_ARCHITECTURE.md`
- **Safe Strategy:** `SAFE_MODULAR_BUILD_STRATEGY.md`
- **Start Guide:** `START_TODAY_GUIDE.md`
- **Progress:** `MODULAR_BUILD_PROGRESS.md`
- **This File:** `QUICK_REFERENCE_MODULAR.md`

---

## 🆘 Need Help?

1. Check `SAFE_MODULAR_BUILD_STRATEGY.md`
2. Check `START_TODAY_GUIDE.md`
3. Check Ant Design docs: https://ant.design
4. Rollback and try again

---

## ✅ Success Checklist

Before moving to next step:

- [ ] Code compiles without errors
- [ ] App runs in browser
- [ ] Feature works as expected
- [ ] No console errors
- [ ] Changes committed
- [ ] Progress documented

---

**Remember: Slow and steady wins the race! 🐢**
