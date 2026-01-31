# Email System Quick Reference

## Files Created

### Mail Classes (3 files)
```
app/Mail/RepairBookingConfirmation.php      - Booking confirmation
app/Mail/RepairPaymentConfirmation.php      - Payment received
app/Mail/RepairStatusUpdate.php             - Status updates (all 6 statuses)
```

### Email Templates (8 files)
```
resources/views/emails/repairs/booking-confirmation.blade.php
resources/views/emails/repairs/payment-confirmation.blade.php
resources/views/emails/repairs/status-received.blade.php
resources/views/emails/repairs/status-diagnosed.blade.php
resources/views/emails/repairs/status-in-progress.blade.php
resources/views/emails/repairs/status-quality-check.blade.php
resources/views/emails/repairs/status-ready-for-pickup.blade.php
resources/views/emails/repairs/status-completed.blade.php
```

### Code Updates
- `app/Http/Controllers/RepairController.php` - Added email sending logic
- Booking confirmation sent on store()
- Payment confirmations sent on callback methods
- Status update emails sent on adminUpdateStatus()

---

## Email Flow

```
BOOKING → EMAIL 1: Booking Confirmation
         ↓
PAYMENT → EMAIL 2: Payment Confirmation
         ↓
ADMIN UPDATE: Received → EMAIL 3: Device Received
         ↓
ADMIN UPDATE: Diagnosed → EMAIL 4: Diagnosis Complete (approval needed)
         ↓
ADMIN UPDATE: In Progress → EMAIL 5: Repair In Progress
         ↓
ADMIN UPDATE: Quality Check → EMAIL 6: Quality Check
         ↓
ADMIN UPDATE: Ready for Pickup → EMAIL 7: Ready for Pickup
         ↓
ADMIN UPDATE: Completed → EMAIL 8: Repair Completed
```

---

## Configuration

### .env File
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="support@yourcompany.com"
MAIL_FROM_NAME="TechRepair Services"
```

### Queue Processing (Optional but Recommended)
```env
QUEUE_CONNECTION=database
```

Then run:
```bash
php artisan queue:work
```

---

## Testing Emails

### Option 1: Local Testing (Log)
```env
MAIL_MAILER=log
```
Emails will be logged to `storage/logs/laravel.log`

### Option 2: Mailtrap
1. Create account at mailtrap.io
2. Get SMTP credentials
3. Add to .env

### Option 3: Gmail (Recommended for Testing)
1. Enable 2-factor authentication on Gmail
2. Generate app password at myaccount.google.com/apppasswords
3. Use app password in .env

---

## Email Sending Summary

### Automatic (No Admin Action Needed)
- ✅ Booking Confirmation (when user books)
- ✅ Payment Confirmation (when payment verified)

### Admin-Triggered
- ✅ Device Received (admin sets status)
- ✅ Diagnosis Complete (admin sets status + notes)
- ✅ Repair In Progress (admin sets status)
- ✅ Quality Check (admin sets status)
- ✅ Ready for Pickup (admin sets status)
- ✅ Repair Completed (admin sets status)

---

## Error Handling

All email sends are wrapped in try-catch:
- ✅ Errors logged to Laravel log
- ✅ Email failures don't break repairs
- ✅ Admin still sees success response
- ✅ Can retry later via queue

---

## Customization Guide

### Add Company Info
Edit `config/company.php`:
```php
'name' => 'Your Company Name',
'email' => 'support@company.com',
'phone' => '01-2345-6789',
'address' => '123 Tech Street, Lagos',
```

### Change Email Template
Edit any file in `resources/views/emails/repairs/`

### Add Custom Fields
Modify mail classes in `app/Mail/` to pass additional data

---

## Monitoring & Logging

Check email sending logs:
```bash
tail -f storage/logs/laravel.log | grep "RepairMail\|sent to"
```

View sent emails (if using Mailtrap):
Visit dashboard at mailtrap.io

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Emails not sending | Check MAIL_* settings in .env |
| SMTP errors | Verify credentials, enable less secure apps |
| Wrong recipient | Check customer_email in database |
| Template errors | Check Blade syntax in view files |
| Queue issues | Run `php artisan queue:work` |

---

## Performance Tips

1. **Use Queue** - Don't send sync in production
   ```bash
   php artisan queue:work
   ```

2. **Batch Emails** - Use jobs for bulk notifications
   ```php
   dispatch(new SendBulkEmails($repairs));
   ```

3. **Cache** - Cache template partials
   ```blade
   @cache
     {{ expensive_operation() }}
   @endcache
   ```

4. **Throttling** - Limit emails per minute
   ```php
   // In config/queue.php
   'driver' => 'sync',
   'retry' => 5,
   ```

---

## Security

✅ **No Passwords in Email** - Only tracking numbers
✅ **No Payment Details** - Only amounts, not card info
✅ **Encrypted Links** - Signed URLs for tracking
✅ **Rate Limiting** - Only one email per action
✅ **Validation** - All data validated before sending

---

## Testing Checklist

- [ ] Booking confirmation email received
- [ ] Payment confirmation email received
- [ ] Device received email sent (admin trigger)
- [ ] Diagnosis email with cost quote received
- [ ] Repair progress email sent
- [ ] Quality check email sent
- [ ] Ready for pickup email with instructions
- [ ] Completion email with warranty info
- [ ] All links in emails work
- [ ] Company info correct in emails
- [ ] Formatting looks good on mobile
- [ ] Tech notes display correctly

---

## Next Steps

1. ✅ Configure .env with email settings
2. ✅ Test email sending (use Mailtrap/Gmail)
3. ✅ Deploy to staging
4. ✅ Test full flow: booking → payment → status updates
5. ✅ Deploy to production
6. ✅ Monitor first week of emails
7. ✅ Collect customer feedback

---

## Support

All emails include:
- ✅ Support email address
- ✅ Support phone number
- ✅ Operating hours
- ✅ Tracking number for reference

Customers can reply to any email for support.
