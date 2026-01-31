# Device Repair System - Email Communication Guide

## Email Templates Created

### 1. **Booking Confirmation Email**
**File:** `resources/views/emails/repairs/booking-confirmation.blade.php`
**Sent:** When customer creates a booking
**Triggered:** After repair record is created in `RepairController@store()`

**Contents:**
- ✓ Booking confirmation
- ✓ Device details
- ✓ Consultation fee (non-refundable)
- ✓ Link to pay online
- ✓ Tracking number
- ✓ Contact information

---

### 2. **Payment Confirmation Email**
**File:** `resources/views/emails/repairs/payment-confirmation.blade.php`
**Sent:** After successful payment verification
**Triggered:** In `RepairController@paymentCallback()`, `flutterwaveCallback()`, or `paystackCallback()`

**Contents:**
- ✓ Payment confirmation
- ✓ Amount paid
- ✓ Device information
- ✓ What happens next (repair timeline)
- ✓ Link to track repair
- ✓ Estimated timeline

---

### 3. **Status: Device Received**
**File:** `resources/views/emails/repairs/status-received.blade.php`
**Sent:** When admin updates status to "Received"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Device received confirmation
- ✓ Estimated diagnosis time (24-48 hours)
- ✓ What to expect next
- ✓ Technician notes (if any)
- ✓ Tracking link
- ✓ Real-time status tracking info

---

### 4. **Status: Diagnosis Complete**
**File:** `resources/views/emails/repairs/status-diagnosed.blade.php`
**Sent:** When admin updates status to "Diagnosed"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Diagnosis results
- ✓ Estimated repair cost
- ✓ What was found (technician notes)
- ✓ Action required (approve/decline)
- ✓ How to proceed (approval link)
- ✓ Timeline if approved

---

### 5. **Status: Repair In Progress**
**File:** `resources/views/emails/repairs/status-in-progress.blade.php`
**Sent:** When admin updates status to "In Progress"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Repair started confirmation
- ✓ Work being performed
- ✓ Estimated ready time
- ✓ Progress notes (if any)
- ✓ Next milestone: Quality Check
- ✓ Tracking link

---

### 6. **Status: Quality Check**
**File:** `resources/views/emails/repairs/status-quality-check.blade.php`
**Sent:** When admin updates status to "Quality Check"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Final verification started
- ✓ What's being checked (5-point checklist)
- ✓ Expected timeline (24 hours)
- ✓ Next status: Ready for Pickup
- ✓ Encouraging message
- ✓ Tracking link

---

### 7. **Status: Ready for Pickup**
**File:** `resources/views/emails/repairs/status-ready-for-pickup.blade.php`
**Sent:** When admin updates status to "Ready for Pickup"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Ready for pickup notification
- ✓ Pickup instructions (2 options: in-person or delivery)
- ✓ What to bring (ID/tracking number)
- ✓ Location and hours
- ✓ What's included (warranty, receipt, care guide)
- ✓ Verification steps
- ✓ Contact info for rescheduling

---

### 8. **Status: Repair Completed**
**File:** `resources/views/emails/repairs/status-completed.blade.php`
**Sent:** When admin updates status to "Completed"
**Triggered:** In `RepairController@adminUpdateStatus()`

**Contents:**
- ✓ Repair completion confirmation
- ✓ Final summary (costs breakdown)
- ✓ Warranty information (30-day warranty)
- ✓ Care tips (5 maintenance tips)
- ✓ Support info
- ✓ Request for review
- ✓ Referral encouragement

---

## Mail Classes Created

### 1. **RepairBookingConfirmation.php**
- **Purpose:** Send booking confirmation
- **Location:** `app/Mail/RepairBookingConfirmation.php`
- **Triggered by:** `RepairController@store()`

### 2. **RepairPaymentConfirmation.php**
- **Purpose:** Send payment confirmation
- **Location:** `app/Mail/RepairPaymentConfirmation.php`
- **Triggered by:** Payment callback methods

### 3. **RepairStatusUpdate.php**
- **Purpose:** Send status updates for all 6 statuses
- **Location:** `app/Mail/RepairStatusUpdate.php`
- **Triggered by:** `RepairController@adminUpdateStatus()`
- **Smart routing:** Automatically selects correct template based on status

---

## Email Sending Timeline

```
Customer Flow:
1. Customer books repair
   ↓
   📧 Booking Confirmation Email
   ↓
2. Customer pays (Flutterwave/Paystack)
   ↓
   📧 Payment Confirmation Email
   ↓
3. Admin updates to "Received"
   ↓
   📧 Device Received Email
   ↓
4. Admin updates to "Diagnosed"
   ↓
   📧 Diagnosis Complete Email (with approval needed)
   ↓
5. Admin updates to "In Progress"
   ↓
   📧 Repair In Progress Email
   ↓
6. Admin updates to "Quality Check"
   ↓
   📧 Quality Check Email
   ↓
7. Admin updates to "Ready for Pickup"
   ↓
   📧 Ready for Pickup Email
   ↓
8. Admin updates to "Completed"
   ↓
   📧 Repair Completed Email (with review request)
```

---

## Configuration Required

Add to `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourcompany.com"
MAIL_FROM_NAME="{{ config('company.name') }}"
```

Or use queue:
```
QUEUE_CONNECTION=database  # or redis, sync for testing
```

---

## Features

✅ **Personalized:** All emails use customer name and device details
✅ **Professional:** Branded with company info (email, phone, address)
✅ **Actionable:** Direct links to track repair or approve diagnosis
✅ **Informative:** Progress updates with realistic timelines
✅ **Secure:** No sensitive data in email body (only tracking number)
✅ **Mobile-friendly:** Responsive design using Mailables
✅ **Consistent:** 30-day warranty mention in completion email
✅ **Engagement:** Review request and referral encouragement in final email

---

## Error Handling

All email sends are wrapped in try-catch blocks to:
- Prevent email failures from breaking repair flow
- Log email sending errors to storage/logs/laravel.log
- Allow repair processing to continue even if email fails

---

## Testing Emails

To test emails locally without sending:

```php
// In .env
MAIL_MAILER=log  // Logs to storage/logs/laravel.log

// Or use Mailtrap/Mailhog for preview
```

---

## Next Steps

1. ✅ Mail classes created and linked
2. ✅ Email templates created
3. ✅ Email sending integrated into controller
4. ✅ Payment callbacks trigger emails
5. ✅ Error handling implemented

**Ready to deploy!** Test in development first, then enable in production.
