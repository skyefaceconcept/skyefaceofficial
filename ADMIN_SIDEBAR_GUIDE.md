# Admin Sidebar Troubleshooting & Testing Guide

## Quick Checklist for Sidebar Issues

### 1. **Sidebar Not Showing At All**
- [ ] Check browser console for JavaScript errors (F12 > Console)
- [ ] Verify the sidebar HTML exists in the page (F12 > Inspector, search for `id="sidebar"`)
- [ ] Check that Bootstrap CSS is loaded
- [ ] Verify StarAdmin CSS files are loading (check Network tab)

### 2. **Toggle Buttons Not Working**
- [ ] Check if `sidebarToggle` element exists: `document.getElementById('sidebarToggle')`
- [ ] Check if `mobileSidebarToggle` element exists: `document.getElementById('mobileSidebarToggle')`
- [ ] Verify event listeners are attached (admin-responsive.js is loaded)
- [ ] Check for JavaScript errors in console

### 3. **Submenu (Collapse) Not Working**
- [ ] Ensure `.collapse` elements have proper IDs matching `data-toggle="collapse"` href
- [ ] Check that Bootstrap JS is included (for collapse functionality)
- [ ] Verify `aria-controls` attributes match collapse element IDs
- [ ] Test Bootstrap collapse directly: `var myCollapse = new bootstrap.Collapse(element)`

### 4. **Mobile Sidebar Not Slide Out**
- [ ] Test on actual mobile device or DevTools mobile mode
- [ ] Check window width breakpoints in CSS (should be 768px)
- [ ] Verify `z-index` values for sidebar overlay
- [ ] Check `body { overflow: hidden }` is applied when sidebar shows

## Testing Steps

### Desktop Testing
```javascript
// In browser console:
// 1. Check sidebar element
console.log(document.getElementById('sidebar'));

// 2. Check toggles
console.log(document.getElementById('sidebarToggle'));
console.log(document.getElementById('mobileSidebarToggle'));

// 3. Manually toggle sidebar
document.getElementById('sidebar').classList.toggle('sidebar-collapsed');

// 4. Test mobile toggle
document.getElementById('sidebar').classList.toggle('show');
```

### Mobile Testing
1. Open DevTools (F12)
2. Toggle mobile device mode (Ctrl+Shift+M)
3. Resize to 375px width (mobile)
4. Click hamburger menu button
5. Sidebar should slide in from left
6. Click menu items to verify they work
7. Click outside sidebar to close

### Submenu Testing
```javascript
// Test collapse element
var collapseEl = document.getElementById('repair-settings');
var collapse = new bootstrap.Collapse(collapseEl, { toggle: false });
collapse.toggle(); // Should toggle submenu
```

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Sidebar hidden behind content | Wrong z-index | Check z-index: 999 in CSS |
| Toggle button not visible | CSS display: none | Check @media query for display |
| Submenu not expanding | Missing Bootstrap JS | Ensure bootstrap.js is loaded |
| Sidebar won't close on mobile | Event listener not attached | Reload page or check admin-responsive.js |
| Classes not applying | View cache | Run `php artisan view:clear` |
| Sidebar styles broken | CSS not compiled | Run `npm run build` |

## Files Modified
- `resources/views/layouts/admin/app.blade.php` — Main layout with CSS
- `resources/views/admin/components/navbar.blade.php` — Navbar with toggle buttons
- `resources/views/admin/components/sidebar.blade.php` — Sidebar menu structure
- `resources/js/admin-responsive.js` — JavaScript for toggle functionality

## Key CSS Classes
- `.sidebar` — Main sidebar container
- `.sidebar-collapsed` — Collapsed state (desktop)
- `.sidebar.show` — Open state (mobile)
- `.sidebar-toggle` — Toggle button (mobile only)
- `.sidebar-offcanvas` — Bootstrap class for off-canvas behavior
- `.collapse` — Bootstrap collapse class for submenus
- `.collapse.show` — Expanded submenu state

## Required Dependencies
- Bootstrap 4 or 5
- Bootstrap JavaScript (for collapse plugin)
- Font Awesome / MDI icons
- Custom admin-responsive.js

## Browser Console Commands for Debugging

```javascript
// Check if sidebar exists
var sidebar = document.getElementById('sidebar');
console.log('Sidebar exists:', !!sidebar);

// Check if script is loaded
console.log('Window has sidebar functions:', typeof window.setupAdminResponsive);

// Manually trigger sidebar toggle
sidebar.classList.toggle('show');

// Check localStorage
console.log('Saved collapsed state:', localStorage.getItem('adminSidebarCollapsed'));

// Clear localStorage
localStorage.removeItem('adminSidebarCollapsed');
location.reload();

// Test Bootstrap collapse
var testCollapse = document.querySelector('.collapse');
console.log('Collapse element:', testCollapse);
if (testCollapse) {
    var collapse = new bootstrap.Collapse(testCollapse);
    collapse.toggle();
}
```

## After Deploying to Production

1. **Clear All Caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

2. **Rebuild Frontend Assets:**
   ```bash
   npm run build
   ```

3. **Test on Mobile:**
   - Use Chrome DevTools mobile mode
   - Test on real device if possible
   - Check sidebar toggle, submenu expand/collapse
   - Verify no console errors

4. **Check Network Tab:**
   - Ensure admin-responsive.js loads
   - Verify CSS/JS files load with 200 status
   - Check no 404 errors

