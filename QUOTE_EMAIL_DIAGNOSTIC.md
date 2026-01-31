# Quote Email Sending - Diagnostic & Fix Guide

## Issue: Quote Emails Not Sending

Your SMTP mail configuration is set up correctly in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=mail.skyeface.com.ng
MAIL_PORT=465
MAIL_USERNAME=info@skyeface.com.ng
MAIL_FROM_ADDRESS=info@skyeface.com.ng
MAIL_ENCRYPTION=ssl
```

## Diagnostic Steps

### Step 1: Check Laravel Logs
```bash
# View the latest logs
Get-Content storage\logs\laravel.log -Tail 100

# Or search for quote-related logs
Get-Content storage\logs\laravel.log -Tail 200 | Select-String "quote|mail|error"
```

### Step 2: Look for These Log Messages
When a quote is submitted or responded to, you should see:

**Success Messages:**
```
✓ Admin notification email sent successfully for quote: 1
✓ Customer confirmation email sent successfully for quote: 1
✓ Quote response email sent successfully to: customer@example.com
Mail logged successfully
```

**Error Messages (if any):**
```
✗ Quote notification emails failed - Exception: [error details]
✗ Quote response email failed - Exception: [error details]
```

### Step 3: Test Email Configuration

Use tinker to test SMTP connection:

```bash
php artisan tinker
```

Then run:

```php
// Test 1: Check mail config
config('mail')

// Test 2: Check SMTP credentials
echo config('mail.mailers.smtp.host');      // Should be: mail.skyeface.com.ng
echo config('mail.mailers.smtp.port');      // Should be: 465
echo config('mail.mailers.smtp.username');  // Should be: info@skyeface.com.ng

// Test 3: Send test email
Mail::to('test@example.com')->send(
    new \App\Mail\QuoteReceivedConfirmation(\App\Models\Quote::first())
);

// Check logs after sending
exit
```

## Common Issues & Solutions

### Issue 1: SMTP Authentication Failed
**Error in logs:** "Swift_TransportException" or "authentication failed"

**Solutions:**
1. Verify credentials in `.env`:
   - Username: `info@skyeface.com.ng`
   - Password: Check if it's correct and has special characters escaped
   
2. Check port:
   - Port 465: Use SSL encryption
   - Port 587: Use TLS encryption (MAIL_SCHEME=tls)
   
3. Try different encryption:
   ```env
   MAIL_SCHEME=tls
   MAIL_PORT=587
   ```

### Issue 2: Connection Refused
**Error in logs:** "Connection refused" or "Unable to connect"

**Solutions:**
1. Verify SMTP server is running
2. Check firewall isn't blocking port 465/587
3. Verify hostname is correct: `mail.skyeface.com.ng`

### Issue 3: Emails Sent but Not Received
**Error in logs:** No errors but customer doesn't receive email

**Solutions:**
1. Check customer email address is correct
2. Check spam/junk folder
3. Verify DKIM/SPF records are configured on mail server

## New Enhanced Error Logging

I've added detailed error logging to help diagnose issues:

### When Quote is Submitted:
```
[INFO] Attempting to send admin notification to: info@skyeface.com.ng
[INFO] ✓ Admin notification email sent successfully for quote: 1
[INFO] Attempting to send customer confirmation to: customer@email.com
[INFO] ✓ Customer confirmation email sent successfully for quote: 1
```

### When Admin Responds to Quote:
```
[INFO] Attempting to send quote response email to: customer@email.com
[INFO] ✓ Quote response email sent successfully to: customer@email.com
```

### If There's an Error:
```
[ERROR] ✗ Quote notification emails failed - Exception: [Specific error message]
[ERROR] Exception Type: [Class name]
[ERROR] Trace: [Full stack trace]
```

## Step-by-Step Testing

### Test 1: Submit a Quote
1. Go to website quote form
2. Fill out and submit
3. Check logs immediately:
   ```bash
   Get-Content storage\logs\laravel.log -Tail 20
   ```
4. Look for success or error messages

### Test 2: Send Quote Response
1. Go to admin panel → Quotes
2. Open a quote
3. Fill "Send Response" form
4. Click "Send Response"
5. Check logs immediately for success/error

### Test 3: Check Mail Logger
```bash
php artisan tinker

# View logged emails
App\Models\MailLog::latest()->take(5)->get()

# View specific quote email
App\Models\MailLog::where('subject', 'like', '%Quote%')->latest()->first()
```

## Database Check

```bash
mysql -u root skyeappdb

# View all quote emails
SELECT id, to, subject, created_at FROM mail_logs 
WHERE subject LIKE '%Quote%' 
ORDER BY created_at DESC 
LIMIT 10;

# Check if any recent emails
SELECT id, to, subject, created_at FROM mail_logs 
ORDER BY created_at DESC 
LIMIT 10;
```

## Configuration Files

All mail configuration is in:
- **Mail Config:** `config/mail.php`
- **Environment:** `.env`
- **Credentials:** Stored securely in `.env` (not in version control)

## If Emails Still Don't Work

### Option 1: Use Log Driver (Development Only)
```env
MAIL_MAILER=log
```
This logs all emails to `storage/logs/laravel.log` instead of sending via SMTP.

### Option 2: Use Array Driver (Development Only)
```env
MAIL_MAILER=array
```
This stores emails in memory for testing.

### Option 3: Contact SMTP Provider
If SMTP isn't working:
1. Verify credentials with mail server provider
2. Check if SMTP server is accessible from your IP
3. Request support from mail hosting provider

## Summary

✅ **Configuration**: Correctly set in `.env` and `config/mail.php`
✅ **Error Logging**: Enhanced with detailed error messages
✅ **Mail Logger**: Logs all sent emails to database
✅ **Debugging**: Can check logs for exact error details

**Next Steps:**
1. Submit a test quote
2. Check `storage/logs/laravel.log` for error messages
3. Share any error messages you find
4. I'll help fix the specific issue
