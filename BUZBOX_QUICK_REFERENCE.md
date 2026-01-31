# Buzbox Components Quick Reference

## 📁 Component Files Created

| File | Location | Purpose |
|------|----------|---------|
| `topnav.blade.php` | `resources/views/components/` | Top navigation bar |
| `sidenav.blade.php` | `resources/views/components/` | Sidebar navigation |
| `footer.blade.php` | `resources/views/components/` | Footer section |
| `app-buzbox.blade.php` | `resources/views/layouts/` | Main layout template |

## 🚀 Quick Start

### Step 1: Create a New View
Create a file in `resources/views/yourpage.blade.php`:

```blade
@extends('layouts.app-buzbox')

@section('title', 'Your Page Title')

@section('content')
    <div class="container-fluid">
        <!-- Your content here -->
    </div>
@endsection
```

### Step 2: Access Your Page
Add route in `routes/web.php`:
```php
Route::get('/yourpage', function() {
    return view('yourpage');
})->middleware('auth');
```

## 🎨 Available CSS Classes

### Card Styling
```blade
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Title</h3>
    </div>
    <div class="card-block">
        Content here
    </div>
</div>
```

### Progress Bars
```blade
<div class="progress">
    <div class="progress-bar bg-success" style="width: 80%"></div>
</div>
```

### Badges
```blade
<span class="badge badge-success">Open</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-secondary">Closed</span>
```

### Buttons
```blade
<a href="#" class="btn btn-primary">Primary</a>
<a href="#" class="btn btn-success">Success</a>
<a href="#" class="btn btn-info">Info</a>
<a href="#" class="btn btn-warning">Warning</a>
<a href="#" class="btn btn-danger">Danger</a>
```

## 📱 Responsive Grid

Use Bootstrap's grid system:
```blade
<div class="row">
    <div class="col-md-6">Left column</div>
    <div class="col-md-6">Right column</div>
</div>

<div class="row">
    <div class="col-sm-3">25%</div>
    <div class="col-sm-3">25%</div>
    <div class="col-sm-3">25%</div>
    <div class="col-sm-3">25%</div>
</div>
```

## 🎯 Font Awesome Icons

```blade
<i class="fa fa-home"></i>        <!-- Home -->
<i class="fa fa-user"></i>        <!-- User -->
<i class="fa fa-envelope"></i>    <!-- Email -->
<i class="fa fa-bell"></i>        <!-- Bell -->
<i class="fa fa-cog"></i>         <!-- Settings -->
<i class="fa fa-sign-out"></i>    <!-- Logout -->
<i class="fa fa-check"></i>       <!-- Check -->
<i class="fa fa-times"></i>       <!-- Close -->
<i class="fa fa-edit"></i>        <!-- Edit -->
<i class="fa fa-trash"></i>       <!-- Delete -->
<i class="fa fa-download"></i>    <!-- Download -->
<i class="fa fa-upload"></i>      <!-- Upload -->
```

## 💡 Tips & Tricks

### 1. Add Custom CSS
In your blade file:
```blade
@section('additional_css')
    <style>
        .custom-class {
            color: #333;
        }
    </style>
@endsection
```

### 2. Add Custom JavaScript
```blade
@section('additional_js')
    <script>
        // Your JS code
    </script>
@endsection
```

### 3. Use User Data
```blade
{{ auth()->user()->fname }}
{{ auth()->user()->lname }}
{{ auth()->user()->email }}
{{ auth()->user()->created_at->format('M d, Y') }}
```

### 4. Create Reusable Sub-Components
Create new blade files in `resources/views/components/` and include them:
```blade
@include('components.your-component')
```

### 5. Pass Data to Components
```blade
@include('components.your-component', ['variable' => $value])
```

## 📋 Component Methods

### Navigation Links
```blade
<!-- In sidenav.blade.php, add new menu items -->
<li>
    <a href="{{ route('your.route') }}">
        <i class="fa fa-icon"></i> Menu Item
    </a>
</li>
```

### Collapsible Menus
```blade
<li>
    <a href="#section" data-toggle="collapse">
        <i class="fa fa-icon"></i> Parent Menu
    </a>
    <ul id="section" class="collapse list-unstyled">
        <li><a href="{{ route('route') }}">Sub Item 1</a></li>
        <li><a href="{{ route('route') }}">Sub Item 2</a></li>
    </ul>
</li>
```

## ✅ Common Patterns

### User Profile Card
```blade
<div class="card">
    <div class="card-block text-center">
        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->fname }}+{{ auth()->user()->lname }}" alt="..." class="img-fluid rounded-circle" style="width: 100px;">
        <h4 class="mt-3">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h4>
        <p class="text-muted">{{ auth()->user()->email }}</p>
    </div>
</div>
```

### Statistics Card
```blade
<div class="card">
    <div class="card-block">
        <div class="text-left">
            <h2 class="font-light">{{ $number }}</h2>
            <span class="text-muted">Label</span>
        </div>
        <div class="progress">
            <div class="progress-bar bg-success" style="width: 75%"></div>
        </div>
    </div>
</div>
```

### Data Table
```blade
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->value }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

## 🔗 Useful Links

- Bootstrap 4 Docs: https://getbootstrap.com/docs/4.0/
- Font Awesome: https://fontawesome.com/icons
- Laravel Blade: https://laravel.com/docs/blade
- Buzbox Template: Check `/public/Buzbox/admin/`

## 📞 Support

For issues or questions about the components:
1. Check the `BUZBOX_COMPONENTS.md` file
2. Review existing component files
3. Check original Buzbox files in `/public/Buzbox/admin/`

---

**Last Updated**: January 7, 2026
**Layout**: Buzbox Admin Template
**Framework**: Laravel 8+
