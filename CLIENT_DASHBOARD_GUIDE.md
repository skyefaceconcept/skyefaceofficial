# Client Dashboard - Complete Guide

## Overview
The Client Dashboard provides authenticated users with a comprehensive interface to manage their support tickets, track quote requests, and interact with the support team.

## Features

### 1. Dashboard Overview
When clients log in, they see:
- **Statistics Cards** - Quick overview of:
  - Total Tickets
  - Open Tickets
  - Awaiting Response (Pending Reply)
  - Resolved Tickets
- **Quick Action Buttons**
  - Request Quote (links to Services page)
  - Contact Support (links to Contact form)
  - View Latest Ticket (quick access)
- **Support Tickets Table** - Paginated list of all client tickets

### 2. Ticket Management
- **View All Tickets** - Paginated list with:
  - Ticket number (unique identifier)
  - Subject line
  - Current status with color-coded badges
  - Last update timestamp
  - Quick view link
- **Ticket Details Page** - Shows:
  - Complete ticket information
  - Full conversation history (scrollable)
  - Messages from both client and support team
  - Timestamps and sender identification
  - Status badge

### 3. Communication
- **Reply to Tickets** - Clients can:
  - Send new messages to support team
  - Automatically update ticket status to "Pending Reply"
  - See conversation history before replying
- **Close Tickets** - Clients can:
  - Mark tickets as closed when resolved
  - Reopen by contacting support again

## Routes

### Dashboard Routes (Protected - Auth Required)
- `GET /dashboard` - Main client dashboard
- `GET /tickets/{ticket_number}` - View ticket details
- `POST /tickets/{ticket_number}/reply` - Send reply to ticket
- `POST /tickets/{ticket_number}/close` - Close a ticket

### Route Middleware
All routes use these middlewares:
- `auth:sanctum` - User must be authenticated
- `verified` - Email must be verified
- `redirect.superadmin` - Admins redirected to admin dashboard

## Controllers

### ClientDashboardController
**Location:** `app/Http/Controllers/ClientDashboardController.php`

**Methods:**
- `index()` - Display main dashboard
- `showTicket($ticket_number)` - Display ticket details
- `replyTicket($ticket_number)` - Handle ticket replies
- `closeTicket($ticket_number)` - Close a ticket

**Features:**
- Automatic user authentication check
- Email-based ticket association (matches user email)
- Statistics calculations
- Pagination (10 tickets per page)

## Database Queries

### Get User's Tickets
```php
$tickets = ContactTicket::where('user_email', $user->email)
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

### Get Ticket Statistics
- **Open Tickets:** `where('status', 'open')`
- **Pending Reply:** `where('status', 'pending_reply')`
- **Resolved:** `where('status', 'resolved')`
- **Total Messages:** Count all messages in user's tickets

## Ticket Status Flow

```
open
  ↓
pending_reply (when support replies or client sends new message)
  ↓
resolved (when support marks as resolved)
  ↓
closed (client closes or system auto-closes)
```

## Views

### 1. Client Dashboard (`resources/views/client/dashboard.blade.php`)
- Statistics cards with icons
- Quick actions section
- Responsive ticket table
- Empty state message
- Pagination support

### 2. Ticket Detail (`resources/views/client/ticket-detail.blade.php`)
- Ticket header information
- Message conversation thread
- Reply form with textarea
- Close ticket button
- Status-based conditional rendering

## Styling & Design

### Color Scheme (Bootstrap/Tailwind)
- **Blue** - Open tickets, Primary actions
- **Yellow** - Awaiting response/Pending
- **Green** - Resolved/Success
- **Orange** - In progress
- **Gray** - Closed/Inactive

### Responsive Design
- Mobile-first approach
- Responsive grid (1 column mobile, 4 columns desktop)
- Scrollable message container
- Touch-friendly buttons

## Security

### Authorization
- Users can only see their own tickets (email-based association)
- Routes protected by authentication middleware
- Email verification required
- CSRF protection on all forms

### Data Validation
- Message length limited to 5000 characters
- Email and ticket number validation
- HTTP 404 if ticket doesn't belong to user

## Integration Points

### Contact Form
Clients create tickets via the contact form on the home/services page:
- Form posts to `POST /contact/send`
- Creates or updates `ContactTicket`
- Adds `ContactMessage` with sender_type = 'client'

### Quote System
Clients can request quotes while logged in:
- Quote requests tracked separately in `quotes` table
- Visible in admin panel
- Can be referenced in support tickets

### Admin Side
Support team manages tickets from `/admin/contact-tickets`:
- View all client tickets
- Assign to staff members
- Send replies
- Update ticket status

## File Structure
```
app/Http/Controllers/
  └── ClientDashboardController.php

resources/views/
  └── client/
      ├── dashboard.blade.php
      └── ticket-detail.blade.php
```

## Configuration

### Pagination
- Default: 10 tickets per page
- Configurable in `ClientDashboardController@index()`

### Message Limit
- Maximum message length: 5000 characters
- Validated in `ClientDashboardController@replyTicket()`

## Workflow Example

### Client Creates Ticket
1. Client fills contact form on website
2. POST to `/contact/send`
3. `ContactTicket` created
4. `ContactMessage` added with client message
5. Client receives ticket number

### Client Views Dashboard
1. Client logs in
2. Navigate to `/dashboard`
3. Sees list of all their tickets
4. Stats show open/pending/resolved counts

### Client Views Ticket Details
1. Click "View" on a ticket
2. Navigate to `/tickets/{ticket_number}`
3. See full conversation history
4. Can send replies or close ticket

### Support Team Responds
1. Support staff sees ticket in admin panel
2. Sends reply via admin interface
3. Ticket status updates to "pending_reply"
4. Client logs in and sees new message
5. Client can reply again or close ticket

## Future Enhancements

### Planned Features
- [ ] Email notifications when support replies
- [ ] File/attachment support in messages
- [ ] Ticket priority levels
- [ ] SLA tracking (response time)
- [ ] Ticket search and filtering
- [ ] Export ticket history as PDF
- [ ] Rating/feedback on resolved tickets
- [ ] Knowledge base / FAQ search
- [ ] Live chat integration
- [ ] Ticket categories/departments

### Possible Customizations
- Add custom ticket fields
- Implement ticket templates
- Add team assignment
- Create ticket automation rules
- Add satisfaction surveys
- Implement ticket merging

## Troubleshooting

### Ticket Not Showing
**Issue:** Client doesn't see their ticket in dashboard
**Solution:** Verify ticket's `user_email` matches user's email in `users` table

### Reply Not Sending
**Issue:** 422 validation error on reply
**Solution:** Check message field is not empty and under 5000 characters

### Middleware Issues
**Issue:** 403 Unauthorized error
**Solution:** Ensure user is authenticated and email is verified

## Testing

### Test Scenarios
1. **Create Ticket**
   - Submit contact form
   - Verify ticket appears in dashboard
   - Check ticket number matches email notification

2. **View Ticket**
   - Click ticket in list
   - Verify all messages display correctly
   - Check timestamps and sender info

3. **Reply to Ticket**
   - Add message in reply form
   - Submit reply
   - Verify new message appears in conversation
   - Check status updates to "pending_reply"

4. **Close Ticket**
   - Click close button
   - Verify status changes to "closed"
   - Verify reply form disappears

---

**Created:** 2026-01-06
**Status:** ✅ COMPLETE
**Last Updated:** 2026-01-06
