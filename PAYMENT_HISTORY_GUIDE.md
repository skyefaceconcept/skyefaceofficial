# Payment History & Admin Features

## Overview
Two new pages have been created for managing payments:
1. **Client Payment History** - For customers to see their payments
2. **Admin Payments List** - For admins to manage and query all payments

---

## Client Payment History

### URL
```
http://127.0.0.1:8000/payment-history
```

### Features
✅ **View all personal payments** - List of all transactions for the logged-in customer
✅ **Payment statistics** - Quick overview cards showing:
   - Total payments
   - Completed payments
   - Pending payments
   - Failed payments

✅ **Payment details table** with:
   - Reference number
   - Quote ID (linked to quote)
   - Amount with currency
   - Payment status (badge colored by status)
   - Transaction date
   - Actions dropdown

✅ **Responsive design** - Works on mobile and desktop
✅ **Pagination** - Shows 15 payments per page

### Accessing
- Logged-in clients can access at `/payment-history`
- Requires authentication

---

## Admin Payments Management

### URL
```
http://127.0.0.1:8000/admin/payments
```

### Features

#### 📊 Dashboard Statistics
Shows at the top:
- **Total Transactions** - Count of all payments
- **Revenue** - Sum of all completed payments
- **Pending** - Count of pending payments
- **Failed** - Count of failed payments

#### 🔍 Advanced Filters
Search and filter payments by:
- **Search** - Find by reference, email, name, or transaction ID
- **Status** - Filter by (Pending, Completed, Failed, Cancelled)
- **Date Range** - Filter by creation date (From/To)
- **Reset** - Clear all filters

#### 📋 Payments Table
Displays all payments with:
- Reference number
- Customer name & email
- Amount & currency
- Current status (with badge)
- Quote ID (linked to admin quote view)
- Date & time
- Action buttons

#### ⚡ Refresh Button
**Most Important Feature!**
- Click "Refresh Status" for any payment
- Automatically queries Flutterwave API to check current status
- Updates payment record if status has changed
- Shows success/error messages
- Useful when clients report false failures due to network issues

### Access Control
- Admin only (`is.superadmin` middleware)
- Protected route at `/admin/payments`

---

## How to Access

### For Clients
1. Login to your account
2. Navigate to `/payment-history`
3. Or look for "Payment History" in the main menu

### For Admins
1. Login as admin
2. Navigate to `/admin/payments`
3. Or access from admin dashboard

---

## Database Requirements

### Payment Model
The `Payment` model should have these fields:
- `id` - Primary key
- `quote_id` - Foreign key to Quote
- `reference` - Payment reference
- `transaction_id` - Flutterwave transaction ID
- `customer_name` - Customer name
- `customer_email` - Customer email
- `amount` - Payment amount
- `currency` - Currency code (NGN, USD, etc.)
- `status` - Payment status (pending, completed, failed, cancelled)
- `paid_at` - When payment was completed
- `created_at` - When record was created
- `updated_at` - Last update

---

## API Routes

### Client Payment History
```
GET /payment-history
```
Returns: Payment history view for logged-in user

### Admin Payment List
```
GET /admin/payments
```
Query Parameters:
- `search` - Search string (optional)
- `status` - Filter by status (optional)
- `from_date` - Start date (optional)
- `to_date` - End date (optional)

Returns: Admin payments list with filters

### Refresh Payment Status
```
POST /admin/payments/{payment}/refresh
```
Body: None (CSRF token in header)
Returns: 
```json
{
    "success": true,
    "message": "Payment status updated to COMPLETED",
    "payment": { ... }
}
```

---

## Testing

### Test Client Payment History
1. Login as a regular user
2. Make a payment for a quote
3. Visit `/payment-history`
4. Should see the payment in the list

### Test Admin Payments List
1. Login as admin
2. Visit `/admin/payments`
3. See all payments from all customers
4. Try different filters (status, date, search)

### Test Refresh Button
1. Go to `/admin/payments`
2. Find a payment with "Pending" status
3. Click the dropdown menu → "Refresh Status"
4. Confirm the dialog
5. Button will show "Refreshing..."
6. Page reloads with updated status

---

## Styling

Both pages use the **BUZBOX template** for consistency:
- Header with navigation
- Card-based layout
- Bootstrap 4 styles
- Responsive tables
- Status badges with colors:
  - 🟢 Green for "Completed"
  - 🟡 Yellow for "Pending"
  - 🔴 Red for "Failed"

---

## Security Notes

✅ **Client History** - Users can only see their own payments
✅ **Admin List** - Only admin users can access
✅ **Refresh Action** - Protected by CSRF token and admin authorization
✅ **CSRF Protection** - All POST requests require valid CSRF token

---

## Future Enhancements

Possible additions:
- Export payments to CSV/PDF
- Payment receipt generation
- Email payment receipts
- Batch status updates
- Payment reconciliation report
- Webhook logs viewer
- Payment analytics dashboard

---

## Troubleshooting

### Client History Not Showing Payments
- Check if user's email matches payment's customer_email
- Verify payments exist in database for that user
- Check pagination (payments shown 15 per page)

### Admin Refresh Not Working
- Verify Flutterwave API is configured correctly
- Check Laravel logs for errors
- Ensure payment has a transaction_id
- Verify admin user has superadmin role

### Filters Not Working
- Clear cache: `php artisan cache:clear`
- Check if database has payment records
- Verify date format (YYYY-MM-DD)

---

## File Locations

- **Controller Methods**: `app/Http/Controllers/PaymentController.php`
- **Client View**: `resources/views/payment/client-history.blade.php`
- **Admin View**: `resources/views/payment/admin-list.blade.php`
- **Routes**: `routes/web.php`

---

## Questions?

For more details, check:
- PaymentController.php for business logic
- Blade templates for UI/styling
- routes/web.php for endpoint definitions
