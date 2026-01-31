# Contact Ticket System - Setup and Usage Guide

## Overview
A complete contact ticket system has been implemented that allows:
- ✅ Clients to send messages through the contact form on the home page
- ✅ Messages are stored in a ticket system for organization
- ✅ Admin users to view all tickets in the admin panel
- ✅ Admin users to reply to messages and manage conversations
- ✅ Automatic ticket closure after 2 days of inactivity (no client response)
- ✅ Ticket status tracking (Open, Pending Reply, Closed)

## Files Created/Modified

### Models
1. **app/Models/ContactTicket.php** - Main ticket model with relationships and scopes
2. **app/Models/ContactMessage.php** - Individual message model for ticket conversations

### Controllers
1. **app/Http/Controllers/ContactController.php** - Handles form submissions and ticket creation
2. **app/Http/Controllers/Admin/ContactTicketController.php** - Admin panel for managing tickets

### Migrations
1. **database/migrations/2026_01_04_000001_create_contact_tickets_table.php** - Contact tickets table
2. **database/migrations/2026_01_04_000002_create_contact_messages_table.php** - Contact messages table

### Views
1. **resources/views/admin/contact-tickets/index.blade.php** - List all tickets with filters and stats
2. **resources/views/admin/contact-tickets/show.blade.php** - View and reply to individual tickets
3. **resources/views/home.blade.php** - Updated contact form (modified)

### Commands & Scheduler
1. **app/Console/Commands/AutoCloseInactiveTickets.php** - Command to auto-close inactive tickets
2. **app/Console/Kernel.php** - Console kernel with scheduled task (created)

### Routes
- **routes/web.php** - Added public route and admin routes (modified)

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

This will create two new tables:
- `contact_tickets` - Stores ticket information
- `contact_messages` - Stores individual messages in the conversation

### 2. Set Up Task Scheduler (Important for Auto-Closing)
For automatic ticket closure to work, add the Laravel scheduler to your system crontab:

**On Linux/Mac:**
```bash
crontab -e
```

Add this line:
```
* * * * * cd /path/to/skyeface && php artisan schedule:run >> /dev/null 2>&1
```

**On Windows with Task Scheduler:**
Create a scheduled task that runs every minute:
```
php artisan schedule:run
```

This ensures the `tickets:auto-close` command runs hourly to close inactive tickets.

### 3. Manual Command (Optional)
You can manually run the auto-close command anytime:
```bash
php artisan tickets:auto-close
```

## Features & Workflow

### Client Side (Home Page)
1. **Contact Form** - Located at `#contact-h` section
   - Client enters: Name, Email, Phone (optional), Subject, Message
   - Form validates and submits via AJAX
   - On success, displays ticket number for reference
   - If email + subject match an existing open ticket, adds message to that ticket instead of creating a new one

### Admin Side (Admin Panel)
1. **Tickets Dashboard** - `/admin/contact-tickets`
   - View all tickets with status badges
   - Filter by status (Open, Pending Reply, Closed)
   - See statistics: Total Open, Pending Reply, Closed, Assigned to Me
   - Click on any ticket to view the conversation

2. **Individual Ticket View** - `/admin/contact-tickets/{id}`
   - View complete message thread
   - See client information (name, email, phone)
   - Send replies using the reply form
   - Assign ticket to yourself
   - Close ticket when resolved
   - All messages show timestamps and sender type

### Automatic Features
1. **Inactivity Tracking**
   - `last_reply_date` tracks the last message (from either side)
   - After 2 days of no new messages, ticket is automatically closed
   - Runs automatically via Laravel's task scheduler

2. **Ticket Numbering**
   - Format: `TKT-000001`, `TKT-000002`, etc.
   - Auto-generated and unique for each ticket

3. **Status Management**
   - **Open**: Initial status when ticket is created
   - **Pending Reply**: Status when admin replies (waiting for client response)
   - **Closed**: Final status (either manually closed or auto-closed after 2 days)

## Database Schema

### contact_tickets Table
```
- id (primary key)
- ticket_number (unique)
- user_email
- user_name
- phone (nullable)
- subject
- status (enum: open, pending_reply, closed)
- assigned_to (foreign key to users, nullable)
- last_reply_date (tracks inactivity)
- auto_closed_at (timestamp when auto-closed)
- created_at, updated_at
```

### contact_messages Table
```
- id (primary key)
- ticket_id (foreign key)
- sender_type (enum: client, admin)
- sender_id (nullable, user_id if admin)
- message (longText)
- attachments (json, for future use)
- created_at, updated_at
```

## API Endpoints

### Public Routes
- **POST /contact/send** - Submit a contact form message
  - Parameters: `name`, `email`, `phone` (optional), `subject`, `message`
  - Returns: `{ success: true/false, message: string, ticket_number: string }`

### Admin Routes (Protected - SuperAdmin Only)
- **GET /admin/contact-tickets** - List all tickets
- **GET /admin/contact-tickets/{id}** - View single ticket and messages
- **POST /admin/contact-tickets/{id}/assign** - Assign ticket to current admin
- **POST /admin/contact-tickets/{id}/reply** - Add admin reply to ticket
  - Parameters: `message` (required)
- **PUT /admin/contact-tickets/{id}/close** - Close a ticket
- **PUT /admin/contact-tickets/{id}/status** - Update ticket status
  - Parameters: `status` (enum: open, pending_reply, closed)

## Testing the System

### Test Client Submission
1. Go to home page
2. Scroll to "Continue The Conversation" section
3. Fill out form and submit
4. Check that ticket number is displayed

### Test Admin Panel
1. Login as superadmin
2. Go to `/admin/contact-tickets`
3. Click on a ticket
4. Reply to the message
5. Verify status changes to "Pending Reply"
6. Test closing a ticket

### Test Auto-Close
1. Create a ticket
2. Manually run: `php artisan tickets:auto-close`
3. Check if ticket with no replies for 2+ days is closed
4. Or wait for scheduler to run (every hour)

## Customization Options

### Change Auto-Close Duration
Edit `app/Models/ContactTicket.php` and modify the `inactiveForAutoClose` scope:
```php
public function scopeInactiveForAutoClose($query)
{
    return $query->where('status', 'open')
                ->where('last_reply_date', '<=', now()->subDays(3)); // Change 3 to desired days
}
```

### Change Scheduler Frequency
Edit `app/Console/Kernel.php` and modify the schedule:
```php
$schedule->command('tickets:auto-close')
         ->dailyAt('02:00') // Run at 2 AM daily instead of hourly
         ->withoutOverlapping();
```

### Customize Admin Views
Views are located in `resources/views/admin/contact-tickets/`
- Modify colors, layouts, and styling as needed
- Add additional fields to the message display
- Customize the statistics dashboard

## Troubleshooting

### Migrations Not Running
- Ensure database is properly configured in `.env`
- Check file permissions in `database/migrations/` directory
- Run: `php artisan migrate:fresh` (caution: deletes all data)

### Auto-Close Not Working
- Verify cron job is set up correctly
- Check if Laravel scheduler is running: `php artisan schedule:work` (development)
- Verify `last_reply_date` is being updated in database
- Check logs: `tail storage/logs/laravel.log`

### Form Submission Not Working
- Check browser console for JavaScript errors
- Verify CSRF token is present in the form
- Check if route `contact.store` is properly registered
- Inspect network tab to see request/response

### Admin Can't See Tickets
- Verify user has SuperAdmin role
- Check `is.superadmin` middleware is applied to admin routes
- Verify `assigned_to` foreign key relationship is correct

## Future Enhancements

Potential features to add:
1. Email notifications when ticket is created/replied
2. File attachments support
3. Ticket priority levels
4. Admin notes that clients can't see
5. Ticket categories/types
6. Bulk operations (close multiple tickets)
7. Ticket assignment to specific admin roles
8. Search and advanced filtering
9. Export tickets to CSV/PDF
10. Client portal to view their own tickets

