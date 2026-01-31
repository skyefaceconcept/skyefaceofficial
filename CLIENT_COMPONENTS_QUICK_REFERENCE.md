# 🚀 Client Dashboard Components - Quick Reference

## Components Created

### 1️⃣ Top Navigation (`client/components/topnav.blade.php`)
**Location:** `/dashboard`, `/tickets/*`, `/user/profile`

```blade
@include('client.components.topnav')
```

**Contains:**
- Skyeface logo
- Search box
- Fullscreen toggle
- User profile dropdown
- Logout link

---

### 2️⃣ Sidebar Navigation (`client/components/sidenav.blade.php`)
**Location:** Left side of all client pages

```blade
@include('client.components.sidenav')
```

**Contains:**
- User profile section
- Dashboard link
- Support Tickets menu (expandable)
- My Profile link
- Request Quote link
- Logout button

**Active Route Highlighting:**
```blade
@if(request()->routeIs('dashboard')) active @endif
@if(request()->routeIs('tickets.*')) active @endif
@if(request()->routeIs('profile.*')) active @endif
```

---

### 3️⃣ Footer (`client/components/footer.blade.php`)
**Location:** Bottom of all client pages

```blade
@include('client.components.footer')
```

**Contains:**
- Email card
- Full name card
- Member since card
- Active status card
- Copyright footer
- Quick links

---

## Layout Structure

```
┌─────────────────────────────────────────┐
│       Header (topnav.blade.php)         │
├──────────────┬──────────────────────────┤
│              │                          │
│  Sidebar     │  Page Content            │
│  (sidenav)   │  (@yield('content'))     │
│              │                          │
│              │  Footer (footer.php)     │
│              │                          │
└──────────────┴──────────────────────────┘
```

---

## Using in Views

### Basic Template:
```blade
@extends('layouts.app-buzbox')

@section('title', 'Page Title')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Title</h3>
                    </div>
                    <div class="card-block">
                        <!-- Your content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

---

## Routes Using These Components

| Route | Component | View File |
|-------|-----------|-----------|
| `/dashboard` | All 3 | `client/dashboard/index.blade.php` |
| `/user/profile` | All 3 | `client/profile/show.blade.php` |
| `/tickets/{id}` | All 3 | `client/tickets/show.blade.php` |

---

## Customization Quick Tips

### Add Sidebar Menu Item:
```blade
<!-- In sidenav.blade.php -->
<li class="sidebar-item">
    <a href="{{ route('your.route') }}" class="sidebar-link">
        <i class="fa fa-icon mr-2"></i>
        <span>Label</span>
    </a>
</li>
```

### Add User Data in Sidebar:
```blade
{{ auth()->user()->fname }}
{{ auth()->user()->email }}
{{ auth()->user()->profile_photo_path }}
```

### Change Card Style in Footer:
```blade
<!-- Edit the four cards in footer.blade.php -->
<div class="col-md-3">
    <div class="card">
        <i class="fa fa-icon"></i>
        <h6>Label</h6>
        <p>{{ auth()->user()->attribute }}</p>
    </div>
</div>
```

---

## CSS Classes Available

### Badges:
```blade
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-info">Info</span>
```

### Buttons:
```blade
<a href="#" class="btn btn-primary">Primary</a>
<a href="#" class="btn btn-success">Success</a>
<a href="#" class="btn btn-danger">Danger</a>
```

### Cards:
```blade
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Title</h3>
    </div>
    <div class="card-block">Content</div>
</div>
```

---

## Icon Libraries

### Font Awesome (Client Components):
```blade
<i class="fa fa-dashboard"></i>
<i class="fa fa-ticket"></i>
<i class="fa fa-user"></i>
<i class="fa fa-home"></i>
```

**Full List:** https://fontawesome.com/icons

---

## Mobile Responsive

### Desktop (1024px+):
- Sidebar visible
- Full layout

### Tablet (768px - 1023px):
- Sidebar slides in/out
- Hamburger menu visible

### Mobile (< 768px):
- Sidebar hidden by default
- Hamburger menu required
- Cards stack vertically

---

## Testing URLs

| Page | URL | Route |
|------|-----|-------|
| Dashboard | `/dashboard` | `dashboard` |
| Profile | `/user/profile` | `profile.show` |
| Ticket | `/tickets/{id}` | `tickets.show` |
| Admin Profile | `/admin/profile` | `admin.profile.show` |

---

## Troubleshooting

### Sidebar Not Showing:
- Check `side-navbar` CSS is loaded
- Verify component path: `client/components/sidenav.blade.php`
- Clear cache: `php artisan view:clear`

### Icons Not Displaying:
- Verify Font Awesome 4.7 is loaded
- Check icon class names (e.g., `fa-dashboard`)
- Ensure stylesheet link in layout

### Layout Issues:
- Check Bootstrap classes
- Verify CSS specificity
- Test with browser dev tools

### Mobile Not Responsive:
- Add `<meta name="viewport">` tag (already in layout)
- Check media queries in CSS
- Test in browser dev tools

---

## File Locations

```
resources/views/
├── client/components/
│   ├── topnav.blade.php
│   ├── sidenav.blade.php
│   └── footer.blade.php
├── client/profile/
│   └── show.blade.php
├── client/dashboard/
│   └── index.blade.php
├── client/tickets/
│   └── show.blade.php
├── admin/profile/
│   └── show.blade.php
└── layouts/
    └── app-buzbox.blade.php
```

---

## Admin Profile

**Location:** `admin/profile/show.blade.php`

**Features:**
- ✅ Profile photo upload
- ✅ Live preview
- ✅ Edit name, email
- ✅ Change password
- ✅ Form validation
- ✅ Dashboard style

**Route:** `/admin/profile`

---

## Need Help?

Check these files for examples:
- `client/dashboard/index.blade.php` - Dashboard layout
- `client/profile/show.blade.php` - Profile layout
- `client/tickets/show.blade.php` - Ticket layout

Or see: `ADMIN_PROFILE_AND_CLIENT_COMPONENTS.md`

---

**Last Updated:** January 9, 2026  
**Status:** ✅ Ready for Use
