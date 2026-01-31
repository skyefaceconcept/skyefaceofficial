# How to Test Payment Pages

## Testing the Failed Payment Page

### Method 1: Direct URL (For Quick Testing)
Simply visit this URL in your browser:
```
http://127.0.0.1:8000/payment/failed
```

This will show the failed page with placeholder data.

### Method 2: With a Specific Quote (Better Testing)
If you want to test with a real quote's payment data:
```
http://127.0.0.1:8000/payment/failed?quote_id=7
```
(Replace `7` with your actual quote ID)

This will:
1. Look up the most recent payment for that quote
2. Display its actual reference, transaction ID, amount, etc.

### Method 3: Testing the Full Payment Flow

#### Simulate a Failed Payment:
1. Go to a quote payment page: `http://127.0.0.1:8000/payment/7`
2. Click "Pay Securely"
3. On Flutterwave test page, enter invalid card details
4. Complete the payment (it will fail)
5. You should be redirected to the failed page

## Testing the Success Payment Page

### Method 1: Direct URL (With Payment ID)
```
http://127.0.0.1:8000/payment/success/1
```
(Replace `1` with a real payment ID)

This will show the success page with that payment's details.

### Method 2: Check Database for Real Payment IDs
Open your database (e.g., PHPMyAdmin) and check the `payments` table to find real payment IDs.

## Database Queries to Check Payments

In your database, you can run:

```sql
-- Find all payments
SELECT * FROM payments ORDER BY created_at DESC;

-- Find failed payments
SELECT * FROM payments WHERE status = 'failed';

-- Find payments for a specific quote
SELECT * FROM payments WHERE quote_id = 7;

-- Find the most recent payment
SELECT * FROM payments ORDER BY created_at DESC LIMIT 1;
```

## Frontend Display Test

### What You Should See on Failed Page:
- ❌ Red "Payment Failed" header
- ❌ Error message with red alert
- ❌ List of possible reasons
- ❌ "What You Can Do" section with action items
- ❌ Two action buttons: "Try Again" and "Dashboard"
- ❌ Support sidebar with contact options

### What You Should See on Success Page:
- ✅ Green "Payment Successful" header
- ✅ Success message with green alert
- ✅ Payment details card (Reference, Transaction ID, Amount, Date)
- ✅ Invoice number
- ✅ "Next Steps" section
- ✅ Two action buttons: "Back to Quote" and "Dashboard"
- ✅ Support sidebar with help options

## Browser Console Check

After visiting either page, check browser console (F12 → Console) for:
- ✅ No JavaScript errors
- ✅ No missing CSRF token errors
- ✅ Clean console output

## Debugging Tips

### If Payment Data is Not Showing:
1. Check the quote ID exists in the database
2. Check there's a payment record for that quote
3. Use the URL parameter: `?quote_id=X`

### If Page Shows Placeholder Data:
- You visited `/payment/failed` without a quote ID
- This is intentional - shows placeholder when no real payment exists
- Use `/payment/failed?quote_id=7` to load real data

### If Buttons Don't Work:
1. Press F12 to open console
2. Check for JavaScript errors
3. Verify CSRF token is present in page source (Ctrl+U)

## Quick Test Checklist

- [ ] Failed page loads at `/payment/failed`
- [ ] Failed page with quote loads at `/payment/failed?quote_id=7`
- [ ] Success page loads at `/payment/success/1` (with real payment ID)
- [ ] Payment details display correctly
- [ ] Buttons are clickable
- [ ] No console errors
- [ ] Responsive on mobile (F12 → toggle device toolbar)
- [ ] Links work (Back to Quote, Dashboard, Contact Support)

