# Quick Testing Guide - Shop Payment System

## ✅ What's Fixed

1. **"Pay Securely" button now responds** and opens the correct payment gateway
2. **Shop order payments are tracked** in admin/payments dashboard
3. **Order details visible** with cart items, address, and customer info
4. **Payment status updates automatically** from pending to completed

---

## 🧪 Test It Now

### Step 1: Add Product to Cart
```
1. Go to /shop
2. Select any portfolio
3. Choose license duration (6 months, 1 year, or 2 years)
4. Click "Buy Now" or "Add to Cart"
```

### Step 2: Checkout
```
1. Proceed to checkout
2. Fill billing information:
   - Customer Name
   - Email
   - Phone
   - Address, City, State, ZIP, Country
3. Select payment method (Flutterwave or Bank Transfer)
4. Accept terms & click "Proceed to Payment"
```

### Step 3: Payment Form
```
1. Payment page should show:
   ✓ Order details (Order #, Customer, Address)
   ✓ Cart items with license durations
   ✓ Total amount in NGN (or other currency)
   ✓ "Pay Securely" button (should be clickable)
   ✓ Active processor name (Paystack or Flutterwave)
```

### Step 4: Make Payment
```
1. Click "Pay Securely" button
2. Payment modal should open (Flutterwave or Paystack)
3. Complete test payment:
   - Use test card: 4111 1111 1111 1111
   - Any future expiry date
   - Any CVC
```

### Step 5: Verify in Admin
```
1. Go to Admin Dashboard
2. Click Payments
3. Look for your new payment:
   ✓ Type: "Shop Order" (green badge)
   ✓ Status: "Completed" (if payment succeeded)
   ✓ Processor: "Flutterwave" or "Paystack"
   ✓ Related To: "Order #X" (clickable link)
```

### Step 6: View Order Details
```
1. Click the "Order #X" link
2. Modal opens showing:
   ✓ Order ID and status
   ✓ Customer name, email, phone
   ✓ Delivery address (complete)
   ✓ Cart items with prices
   ✓ Payment method and processor
   ✓ Order date and completion date
```

---

## 🔍 What to Check

### Payment Button
- [ ] Button responds when clicked
- [ ] No errors in browser console
- [ ] Payment modal opens (correct gateway)

### Payment Modal
- [ ] Displays correct customer info
- [ ] Shows correct amount
- [ ] Shows correct currency
- [ ] Test card accepted (if using test gateway)

### Admin Dashboard - Payments List
- [ ] New payment appears immediately after completion
- [ ] Shows correct payment type ("Shop Order")
- [ ] Shows correct processor (Flutterwave/Paystack)
- [ ] Shows correct status ("Completed")
- [ ] Related To shows "Order #X"

### Order Details Modal
- [ ] Shows order ID
- [ ] Shows customer information
- [ ] Shows delivery address (address, city, state, zip, country)
- [ ] Shows cart items with correct prices
- [ ] Shows payment processor
- [ ] Shows order dates

### Filtering
- [ ] Filter by Status: "completed" - shows your order
- [ ] Filter by Source: "Shop Orders" - shows only shop orders
- [ ] Filter by Source: "Flutterwave" or "Paystack" - filters by processor
- [ ] Search by: email, customer name - finds your order

---

## 🛠️ Troubleshooting

### Button Doesn't Respond
**Check**:
1. Browser console for JavaScript errors
2. Network tab - is payment modal loading?
3. Check that payment.flutterwave.pay route exists

**Try**:
- Clear browser cache (Ctrl+Shift+Del)
- Hard refresh (Ctrl+Shift+R)
- Try different browser
- Check Laravel logs: `storage/logs/laravel.log`

### Payment Not Appearing in Admin
**Check**:
1. Payment status in database: 
   ```sql
   SELECT * FROM payments ORDER BY created_at DESC LIMIT 1;
   ```
2. Order status updated:
   ```sql
   SELECT * FROM orders ORDER BY created_at DESC LIMIT 1;
   ```

**Try**:
- Refresh admin page
- Check if filtering is hiding the payment
- Check payment status filter isn't set to "pending"

### Order Details Don't Show
**Check**:
1. Order record exists in database
2. Order relationships are set up correctly
3. Browser console for JavaScript errors

**Try**:
- Click different order payment
- Manually create test order in database
- Check showOrderDetails() JavaScript function in admin-list.blade.php

---

## 📊 Database Queries

### Check if Payment Created
```sql
SELECT id, order_id, status, amount, processor 
FROM payments 
WHERE order_id IS NOT NULL 
ORDER BY created_at DESC 
LIMIT 1;
```

### Check Order Updated
```sql
SELECT id, customer_name, status, payment_processor 
FROM orders 
ORDER BY created_at DESC 
LIMIT 1;
```

### Count Shop Order Payments
```sql
SELECT COUNT(*) as total_shop_payments 
FROM payments 
WHERE order_id IS NOT NULL;
```

### Get Payment with Order Details
```sql
SELECT 
    p.id as payment_id,
    p.status,
    p.amount,
    p.processor,
    o.id as order_id,
    o.customer_name,
    o.customer_email,
    o.status as order_status
FROM payments p
LEFT JOIN orders o ON p.order_id = o.id
WHERE p.order_id IS NOT NULL
ORDER BY p.created_at DESC
LIMIT 10;
```

---

## 📱 Test Payment Cards

### Flutterwave Test Cards
```
Success: 4111 1111 1111 1111
Decline: 5399 0000 0000 0005
```

### Paystack Test Cards
```
Success: 4084029919402541
Decline: 4012345678901111
```

Any future expiry date and any CVC work for test mode.

---

## ✅ Success Checklist

After testing, verify:

- [ ] Payment button responds without errors
- [ ] Payment modal opens and accepts test card
- [ ] Payment record created in database with order_id
- [ ] Order status changed to "completed"
- [ ] Payment appears in admin/payments within seconds
- [ ] Payment shows correct type ("Shop Order")
- [ ] Payment shows correct processor
- [ ] Clicking order link shows detailed modal
- [ ] Order details show complete information
- [ ] Can filter payments by type and processor
- [ ] Can search payments by customer email

---

## 🚀 Going Live

Before going to production:

1. **Switch to Live Credentials**
   - Update `.env` with live Flutterwave/Paystack keys
   - Update `config/payment.php` if needed

2. **Test with Real Cards**
   - Make test purchase with real (low amount) card
   - Verify payment completes and order status updates

3. **Email Configuration**
   - Ensure payment confirmation emails send
   - Test with real customer email

4. **Monitor Logs**
   - Check `storage/logs/laravel.log` for any errors
   - Monitor payment callbacks in real-time

5. **Backup & Restore**
   - Backup database before going live
   - Test restore procedure

---

## 📞 Support

If issues persist:

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check browser console: F12 → Console tab
3. Check network requests: F12 → Network tab
4. Check database records directly
5. Review this guide's troubleshooting section

---

**Status**: ✅ All fixes applied and ready for testing
