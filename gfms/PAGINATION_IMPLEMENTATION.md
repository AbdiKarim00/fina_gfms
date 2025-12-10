# Pagination Implementation

## Overview
Added reusable pagination component to the bookings module with 12 items per page default.

---

## Component: Pagination.tsx

### Location
`gfms/apps/frontend/src/components/shared/Pagination.tsx`

### Features
- ✅ Reusable across the application
- ✅ Customizable page size (default: 12)
- ✅ Optional size changer
- ✅ Shows item range (e.g., "1-12 of 45 items")
- ✅ Auto-hides when all items fit on one page
- ✅ Centered layout
- ✅ TypeScript support

### Props

```typescript
interface CustomPaginationProps {
  currentPage: number;           // Current active page
  totalItems: number;            // Total number of items
  pageSize?: number;             // Items per page (default: 12)
  onPageChange: (page: number, pageSize: number) => void;
  showSizeChanger?: boolean;     // Show page size selector
  pageSizeOptions?: string[];    // Available page sizes
}
```

### Usage Example

```typescript
import { Pagination } from '../components/shared/Pagination';

const [currentPage, setCurrentPage] = useState(1);
const [pageSize, setPageSize] = useState(12);

const handlePageChange = (page: number, newPageSize: number) => {
  setCurrentPage(page);
  if (newPageSize !== pageSize) {
    setPageSize(newPageSize);
    setCurrentPage(1); // Reset to first page
  }
};

<Pagination
  currentPage={currentPage}
  totalItems={totalItems}
  pageSize={pageSize}
  onPageChange={handlePageChange}
  showSizeChanger
/>
```

---

## Integration in BookingsPage

### State Management

```typescript
const [currentPage, setCurrentPage] = useState(1);
const [pageSize, setPageSize] = useState(12);
```

### Pagination Logic

```typescript
// Filter bookings based on search
const filteredBookings = bookings.filter((booking) => {
  if (!searchText) return true;
  const search = searchText.toLowerCase();
  return (
    booking.destination.toLowerCase().includes(search) ||
    booking.purpose.toLowerCase().includes(search) ||
    booking.vehicle?.registration_number.toLowerCase().includes(search) ||
    booking.requester?.name.toLowerCase().includes(search)
  );
});

// Paginate filtered results
const startIndex = (currentPage - 1) * pageSize;
const endIndex = startIndex + pageSize;
const paginatedBookings = filteredBookings.slice(startIndex, endIndex);
```

### Page Change Handler

```typescript
const handlePageChange = (page: number, newPageSize: number) => {
  setCurrentPage(page);
  if (newPageSize !== pageSize) {
    setPageSize(newPageSize);
    setCurrentPage(1); // Reset to first page when page size changes
  }
};
```

### Reset on Filter Change

```typescript
useEffect(() => {
  fetchBookings();
  fetchStatistics();
  setCurrentPage(1); // Reset to first page when filters change
}, [filters]);
```

---

## Page Size Options

Default options: `['12', '24', '48', '96']`

- **12** - Default, good for quick scanning
- **24** - Medium view
- **48** - Large view
- **96** - Show almost all (for printing/exporting)

---

## UI Features

### Display Format
```
[< 1 2 3 4 5 >]  1-12 of 45 items
```

### Auto-Hide
Pagination automatically hides when:
```typescript
if (totalItems <= pageSize) {
  return null; // Don't show pagination
}
```

### Centered Layout
```typescript
<div style={{ 
  display: 'flex', 
  justifyContent: 'center', 
  marginTop: '24px',
  padding: '16px 0'
}}>
```

---

## Benefits

### 1. **Performance**
- Only renders visible items
- Reduces DOM nodes
- Faster initial load

### 2. **User Experience**
- Easy navigation
- Clear item count
- Flexible page sizes
- Responsive design

### 3. **Maintainability**
- Reusable component
- Consistent behavior
- TypeScript safety
- Easy to customize

---

## Reusability

The component can be used in other modules:

### Vehicles Page
```typescript
<Pagination
  currentPage={currentPage}
  totalItems={vehicles.length}
  pageSize={12}
  onPageChange={handlePageChange}
/>
```

### Users Page
```typescript
<Pagination
  currentPage={currentPage}
  totalItems={users.length}
  pageSize={20}
  onPageChange={handlePageChange}
  showSizeChanger
  pageSizeOptions={['10', '20', '50', '100']}
/>
```

### Reports Page
```typescript
<Pagination
  currentPage={currentPage}
  totalItems={reports.length}
  pageSize={25}
  onPageChange={handlePageChange}
/>
```

---

## Customization Options

### Change Default Page Size
```typescript
<Pagination
  pageSize={24}  // Show 24 items per page
  ...
/>
```

### Custom Page Size Options
```typescript
<Pagination
  pageSizeOptions={['10', '20', '30', '50']}
  ...
/>
```

### Disable Size Changer
```typescript
<Pagination
  showSizeChanger={false}  // Hide page size selector
  ...
/>
```

### Custom Styling
```typescript
<Pagination
  style={{ background: '#f0f0f0', padding: '20px' }}
  ...
/>
```

---

## Testing Scenarios

### Test Case 1: Less than 12 items
```
Items: 8
Expected: No pagination shown
```

### Test Case 2: Exactly 12 items
```
Items: 12
Expected: No pagination shown
```

### Test Case 3: 13 items
```
Items: 13
Expected: 2 pages (12 + 1)
Display: "1-12 of 13 items" on page 1
Display: "13-13 of 13 items" on page 2
```

### Test Case 4: Change page size
```
Action: Change from 12 to 24
Expected: Reset to page 1, show 24 items
```

### Test Case 5: Filter changes
```
Action: Apply status filter
Expected: Reset to page 1, recalculate pagination
```

---

## Future Enhancements

### 1. **Server-Side Pagination**
Currently using client-side pagination. For large datasets:

```typescript
const fetchBookings = async (page: number, pageSize: number) => {
  const response = await apiClient.get(
    `/bookings?page=${page}&per_page=${pageSize}`
  );
  setBookings(response.data);
  setTotalItems(response.meta.total);
};
```

### 2. **URL State Sync**
Sync pagination with URL:

```typescript
const [searchParams, setSearchParams] = useSearchParams();
const currentPage = parseInt(searchParams.get('page') || '1');

const handlePageChange = (page: number) => {
  setSearchParams({ page: page.toString() });
};
```

### 3. **Keyboard Navigation**
Add keyboard shortcuts:
- `←` Previous page
- `→` Next page
- `Home` First page
- `End` Last page

### 4. **Jump to Page**
Add quick jump input:
```
Go to page: [___] [Go]
```

---

## Summary

✅ **Reusable pagination component** created
✅ **12 items per page** default
✅ **Integrated into bookings** module
✅ **Auto-hides** when not needed
✅ **Page size selector** included
✅ **Resets on filter change**
✅ **TypeScript support**
✅ **Centered, clean UI**

The pagination component is production-ready and can be used across the entire application!
