# Buzbox Template Components Documentation

This documentation explains the modular Buzbox template structure for Skyeface.

## File Structure

```
resources/views/
├── layouts/
│   ├── app-buzbox.blade.php          # Main layout template
│   ├── buzbox-admin.blade.php        # Original buzbox layout (kept for reference)
│   └── ... other layouts
│
└── components/
    ├── topnav.blade.php              # Top navigation bar component
    ├── sidenav.blade.php             # Sidebar navigation component
    └── footer.blade.php              # Footer component
```

## Components Overview

### 1. **Top Navigation (topnav.blade.php)**
- **Location**: `resources/views/components/topnav.blade.php`
- **Features**:
  - Search box with full-screen search
  - Expand/fullscreen button
  - Notifications dropdown
  - Messages dropdown
  - User profile dropdown with settings and logout
  - Logo and brand display
  - Mobile responsive hamburger menu

- **Included in**: Main layout
- **Usage**: Automatically included via `@include('components.topnav')`

### 2. **Sidebar Navigation (sidenav.blade.php)**
- **Location**: `resources/views/components/sidenav.blade.php`
- **Features**:
  - User avatar and name
  - Dashboard link
  - Support Tickets with collapsible submenu
  - My Profile link
  - Change Password link
  - Logout button
  - Account section

- **Included in**: Main layout
- **Usage**: Automatically included via `@include('components.sidenav')`

### 3. **Footer (footer.blade.php)**
- **Location**: `resources/views/components/footer.blade.php`
- **Features**:
  - Email card with user email
  - Name card with user full name
  - Member Since card with join date
  - Status card showing active status
  - Professional card-based layout

- **Included in**: Main layout (inside content-inner)
- **Usage**: Automatically included via `@include('components.footer')`

### 4. **Main Layout (app-buzbox.blade.php)**
- **Location**: `resources/views/layouts/app-buzbox.blade.php`
- **Purpose**: Master template that ties all components together
- **Includes**:
  - HTML structure
  - CSS imports (Buzbox styles)
  - Top navigation component
  - Sidebar component
  - Content yield area
  - Footer component
  - JS scripts
  - Logout form

## How to Use

### Using the New Layout in Your Views

**Example: Dashboard**
```blade
@extends('layouts.app-buzbox')

@section('title', 'Client Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Your page content here -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Your Title</h3>
                    </div>
                    <div class="card-block">
                        <!-- Page content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

### Creating a New Page with the Layout

1. Create a new blade file in `resources/views/`
2. Extend the layout: `@extends('layouts.app-buzbox')`
3. Set the title: `@section('title', 'Page Title')`
4. Add your content in: `@section('content')`
5. Sidebar and footer will be included automatically

## Customizing Components

### Customizing the Sidebar
Edit `resources/views/components/sidenav.blade.php` to:
- Add new menu items
- Change icons
- Modify routes
- Add new submenu sections

### Customizing the Top Navigation
Edit `resources/views/components/topnav.blade.php` to:
- Add new navbar items
- Change logo
- Modify dropdown menus
- Add new notifications/messages

### Customizing the Footer
Edit `resources/views/components/footer.blade.php` to:
- Change footer cards
- Add different user information
- Modify card styling
- Add more footer sections

## Modifying the Main Layout

Edit `resources/views/layouts/app-buzbox.blade.php` to:
- Add global CSS
- Add global JavaScript
- Modify page structure
- Add additional sections

## Current Usage

The following views are using this new modular layout:
- `dashboard.blade.php` - Client dashboard

## Features

✅ Modular and reusable components
✅ Easy to customize
✅ Professional Buzbox design
✅ Responsive layout
✅ Bootstrap integration
✅ Font Awesome icons
✅ User-friendly navigation
✅ Mobile support

## Tips

1. **Keep components focused** - Each component has a single responsibility
2. **Use Blade's @include** - To include components in your views
3. **Leverage Bootstrap classes** - The layout uses Bootstrap 4
4. **Customize gradually** - Start with default components, then customize as needed
5. **Test on mobile** - Always test responsive behavior

## Dependencies

- Bootstrap 4
- Font Awesome 4.7.0
- jQuery
- Popper.js
- Laravel Blade

## Notes

- The old `buzbox-admin.blade.php` is kept for reference and can be deleted if not needed
- All components are Laravel 8+ compatible
- Components use authenticated user data via `auth()->user()`

---

**Last Updated**: January 7, 2026
