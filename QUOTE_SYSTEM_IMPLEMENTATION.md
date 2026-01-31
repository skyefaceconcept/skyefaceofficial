# Quote System Implementation Guide

## 🎯 Overview

A comprehensive, unique quote management system with:
- Spam protection (quota limits per IP/email)
- Client quote tracking
- Admin quote management & responses
- Email notifications (ready to implement)
- Status tracking (new → reviewed → quoted → rejected/accepted)

---

## ✅ Components Built

### 1. **Enhanced Quote Model** (`app/Models/Quote.php`)
```
New Fields:
- ip_address: Track request origin
- admin_notes: Internal notes for admin
- quoted_price: Price to quote to client
- response: Admin's response message
- responded_at: When admin responded

Status Constants:
- STATUS_NEW (new)
- STATUS_REVIEWED (reviewed)
- STATUS_QUOTED (quoted)
- STATUS_REJECTED (rejected)
- STATUS_ACCEPTED (accepted)

Methods:
- getStatuses(): Get all available statuses
- getStatusBadgeClass(): Bootstrap badge CSS class per status
```

### 2. **Public Quote Controller** (`app/Http/Controllers/QuoteController.php`)
**Methods:**

#### `store(Request $request)`
- Quota limiting: 3 quotes per IP/day, 10 per email/day
- Enhanced validation with custom rules
- IP address tracking
- Returns: Quote ID + tracking URL
- Status codes: 200 (success), 422 (validation error), 429 (quota exceeded)

#### `track(Request $request)`
- Public endpoint to track quote status
- Input: email + quote ID
- Returns: Status, price, response, timeline
- Usage: Clients can check quote progress anytime

### 3. **Admin Quote Controller** (`app/Http/Controllers/Admin/QuoteController.php`)
**Methods:**

#### `index()`
- List all quotes (paginated)
- Show statistics: total, new, reviewed, quoted, rejected, accepted
- Display available statuses

#### `show($id)`
- View detailed quote
- Access status change form
- Access response form

#### `updateStatus(Request $request, $id)`
- Update quote status only
- Validates against valid statuses

#### `respond(Request $request, $id)`
- Send complete response to client
- Update: status, price, response message
- Set responded_at timestamp
- Ready to send email notification

#### `addNotes(Request $request, $id)`
- Add internal admin notes (AJAX)
- Helpful for team collaboration

#### `destroy($id)`
- Delete quote

### 4. **Database Migration** (`2026_01_09_000001_enhance_quotes_table.php`)
```
New Columns:
- ip_address VARCHAR(45) - IPv4/IPv6
- admin_notes TEXT - Internal notes
- quoted_price DECIMAL(10,2) - Price amount
- response TEXT - Response to client
- responded_at TIMESTAMP - Response datetime
```

### 5. **Routes Added** (`routes/web.php`)
**Public Routes:**
```php
POST   /quotes              → quotes.store    (Submit quote)
POST   /quotes/track        → quotes.track    (Track quote status)
```

**Admin Routes:**
```php
GET    /admin/quotes                      → quotes.index       (List quotes)
GET    /admin/quotes/{id}                 → quotes.show        (View quote)
DELETE /admin/quotes/{id}                 → quotes.destroy     (Delete quote)
PUT    /admin/quotes/{id}/status          → quotes.updateStatus (Change status)
POST   /admin/quotes/{id}/respond         → quotes.respond     (Send response)
POST   /admin/quotes/{id}/notes           → quotes.addNotes    (Add notes)
```

### 6. **Enhanced Services Form** (`resources/views/services.blade.php`)
**Improvements:**
- Better visual design with icons
- Scrollable modal (70vh max-height)
- Placeholders & hints for each field
- Field validation feedback
- Spinner on submit button
- Success message with quote ID
- Tracking info display
- Auto-close after success
- Improved error messages with icons

---

## 🚀 Next Steps to Complete

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Create Email Notifications
Create these Mailable classes:
- `app/Mail/QuoteReceivedConfirmation.php` - To client
- `app/Mail/NewQuoteNotification.php` - To admin
- `app/Mail/QuoteResponseEmail.php` - To client with quote details

### 3. Create Admin Quote Views
Update these existing views:
- `resources/views/admin/quotes/index.blade.php` - List with stats
- `resources/views/admin/quotes/show.blade.php` - Detail page with forms

**Sample show.blade.php:**
```blade
@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Quote #{{ $quote->id }}</h2>
        </div>
        <div class="col-md-4 text-right">
            <span class="badge {{ $quote->getStatusBadgeClass() }}">
                {{ $statuses[$quote->status] ?? 'Unknown' }}
            </span>
        </div>
    </div>

    <div class="row">
        <!-- Quote Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Quote Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $quote->name }}</p>
                    <p><strong>Email:</strong> {{ $quote->email }}</p>
                    <p><strong>Phone:</strong> {{ $quote->phone ?? 'N/A' }}</p>
                    <p><strong>Package:</strong> {{ $quote->package ?? 'N/A' }}</p>
                    <p><strong>Submitted:</strong> {{ $quote->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Details:</strong></p>
                    <p style="background: #f5f5f5; padding: 10px; border-radius: 4px;">{{ $quote->details }}</p>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Update Status</h5>
                </div>
                <div class="card-body">
                    <form method="PUT" action="{{ route('admin.quotes.updateStatus', $quote->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $quote->status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Internal Notes</h5>
                </div>
                <div class="card-body">
                    <textarea id="adminNotes" class="form-control" rows="3" placeholder="Add notes for your team...">{{ $quote->admin_notes }}</textarea>
                    <button onclick="saveNotes({{ $quote->id }})" class="btn btn-sm btn-secondary mt-2">Save Notes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Response Form -->
    @if($quote->status !== 'rejected' && !$quote->responded_at)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>Send Quote Response</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.quotes.respond', $quote->id) }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>New Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="quoted">Send Quote</option>
                                    <option value="rejected">Reject Quote</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Price (Optional)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="quoted_price" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Your Response Message</label>
                            <textarea name="response" class="form-control" rows="4" placeholder="Dear {{ $quote->name }}, We have reviewed your quote request..." required minlength="10"></textarea>
                            <small class="form-text text-muted">This will be sent to {{ $quote->email }}</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Send Response to Client</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Button -->
    <div class="row mt-4">
        <div class="col-md-12">
            <form method="POST" action="{{ route('admin.quotes.destroy', $quote->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this quote?');">Delete Quote</button>
            </form>
        </div>
    </div>
</div>

<script>
function saveNotes(quoteId) {
    const notes = document.getElementById('adminNotes').value;
    fetch(`/admin/quotes/${quoteId}/notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ admin_notes: notes })
    }).then(r => r.json()).then(d => {
        alert(d.message);
    });
}
</script>
@endsection
```

### 4. Create Quote Tracking Page (Optional)
Create `resources/views/quotes/track.blade.php` - A public page where clients can check their quote:

```blade
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Track Your Quote</h2>
            <form id="trackForm" onsubmit="trackQuote(event)">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Quote ID</label>
                    <input type="number" name="id" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Track Quote</button>
            </form>
            <div id="trackResult" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>

<script>
async function trackQuote(event) {
    event.preventDefault();
    const form = document.getElementById('trackForm');
    const resultDiv = document.getElementById('trackResult');
    
    const email = form.email.value;
    const id = form.id.value;
    
    try {
        const response = await fetch('{{ route("quotes.track") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email, id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const quote = data.quote;
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h5>Quote #${quote.id}</h5>
                    <p><strong>Status:</strong> ${quote.status_label}</p>
                    <p><strong>Submitted:</strong> ${quote.created_at}</p>
                    ${quote.quoted_price ? `<p><strong>Price:</strong> $${quote.quoted_price}</p>` : ''}
                    ${quote.responded_at ? `<p><strong>Responded:</strong> ${quote.responded_at}</p>` : ''}
                    ${quote.response ? `<hr><p><strong>Message:</strong></p><p>${quote.response}</p>` : ''}
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    } catch(e) {
        resultDiv.innerHTML = `<div class="alert alert-danger">Error tracking quote</div>`;
    }
}
</script>
@endsection
```

### 5. Setup Email Notifications (Important!)
In `app/Mail/NewQuoteNotification.php`:
```php
<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewQuoteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote)
    {
    }

    public function envelope()
    {
        return new Envelope(
            subject: "New Quote Request - #{$this->quote->id}",
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.quotes.admin-notification',
        );
    }
}
```

### 6. Test the System
```bash
# Run migrations
php artisan migrate

# Test quote submission
# Go to /services page, click Request Quote
# Check the success message with quote ID

# Login to admin
# Go to /admin/quotes
# See the new quote in the list
# Click to view details
# Update status or send response

# Test quota limits
# Try submitting 4+ quotes from same IP in one day (should fail)
```

---

## 🔒 Security Features

1. **Quota Limiting**: Prevents spam/abuse
   - 3 quotes per IP per day
   - 10 quotes per email per day
   - Returns 429 (Too Many Requests) on limit

2. **Input Validation**:
   - Name: Letters, spaces, apostrophes only
   - Phone: Valid phone number format
   - Email: RFC & DNS validation
   - Details: Min 10 characters, max 5000

3. **CSRF Protection**: All forms protected

4. **IP Tracking**: Know where quotes come from

---

## 📊 Status Flow

```
new
  ↓
reviewed (admin reviews details)
  ├→ quoted (admin sends price + response)
  └→ rejected (admin declines)
      ↓
    accepted (client confirms order)
```

---

## 🎨 Features Summary

| Feature | Status | Notes |
|---------|--------|-------|
| Quote submission form | ✅ Enhanced | Better UX, validation |
| Quota limits | ✅ Built | Spam protection |
| Status tracking | ✅ Built | 5 status types |
| Admin management | ✅ Built | Full CRUD + respond |
| Quote tracking API | ✅ Built | Public endpoint |
| Email notifications | 🔄 Ready | Just needs templates |
| Client tracking page | 🔄 Optional | Template provided |

---

## 📋 Testing Checklist

- [ ] Run `php artisan migrate`
- [ ] Test quote form submission
- [ ] Verify quota limit (try 4+ in same day)
- [ ] Admin can view quotes in `/admin/quotes`
- [ ] Admin can update status
- [ ] Admin can send response
- [ ] Admin can add notes
- [ ] Test quota tracking API
- [ ] Create email templates
- [ ] Send test emails to admin & client

---

Generated: 2026-01-09 | Skyeface Quote System v1.0
