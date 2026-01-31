# Email Deliverability Setup Guide

To ensure support ticket emails reach the inbox instead of spam, follow these steps:

## 1. Configure `.env` File

Add or update the following in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@your-domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="Your Company Name"
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

**Important:** Use an email address from your actual domain (e.g., noreply@yourdomain.com) for `MAIL_FROM_ADDRESS`.

## 2. SMTP Provider Options

Choose one of these reliable SMTP providers:

### Option A: Gmail (for testing/small volume)
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Option B: Mailtrap (for testing)
```env
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

### Option C: SendGrid (recommended for production)
```env
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Option D: Your Domain's Email Server
Use your hosting provider's SMTP details (e.g., cPanel, Plesk)

## 3. Enable SPF, DKIM, and DMARC (Critical for Production)

These DNS records prevent your emails from going to spam. Configure these with your domain registrar:

### SPF Record
Add a TXT record:
```
v=spf1 include:smtp.sendgrid.net ~all
```
(Replace with your SMTP provider)

### DKIM Record
Generate DKIM keys in your SMTP provider's settings and add the TXT record they provide.

### DMARC Record
```
v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com
```

## 4. Test Email Deliverability

1. Send a test email from your application
2. Visit [mail-tester.com](https://mail-tester.com)
3. Get a unique email address from the site
4. Send a ticket reply to that email address
5. Check your score - aim for 8+/10

## 5. Verify in Laravel

Test sending an email manually:

```php
// In Laravel Tinker
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('your-email@gmail.com')->subject('Test'); });
```

## Why Emails Go to Spam

Common reasons:
- ❌ Missing SPF/DKIM/DMARC records
- ❌ Using noreply@example.com instead of real domain
- ❌ Too many links in email
- ❌ Poor HTML formatting
- ❌ Missing Text Alternative (now included ✅)
- ❌ Weak headers (now added ✅)

## Current Implementation

Your emails now include:
✅ HTML and Plain Text versions
✅ Proper X-Priority headers
✅ List-Unsubscribe headers
✅ Proper sender identification
✅ Descriptive subject lines

Next step: Configure SMTP and test!
