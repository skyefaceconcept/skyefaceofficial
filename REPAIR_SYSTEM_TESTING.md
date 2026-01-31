# 🧪 Device Repair System - Testing Guide

## Quick Test Steps

### 1. Test Booking Form (Frontend)

**URL:** `http://localhost/skyeface` (Services page)

**Steps:**
1. Scroll down to "Device Repair Booking" section
2. Select "Laptop" from Device Type dropdown
3. ✅ Should see price display: **$35.00** with green background
4. Try different device types:
   - Mobile Phone → $25.00
   - Desktop Computer → $30.00
   - Tablet → $28.00
   - Printer → $40.00
   - Other → $50.00
5. Fill in the form completely:
   - Name: Test Customer
   - Email: test@example.com
   - Phone: +1 (555) 123-4567
   - Device Type: Laptop
   - Brand: Apple
   - Model: MacBook Pro
   - Issue: Screen flickering intermittently
   - Urgency: Express
6. Click "Submit Repair Request"
7. ✅ Should see green success message with tracking number

**Expected Response:**
```json
{
  "success": true,
  "message": "Repair booking submitted successfully",
  "invoice_number": "REP-ABC-20260121-0001",
  "tracking_number": "REP-ABC-20260121-0001",
  "repair_id": 1
}
```

---

### 2. Test Tracking Page (Frontend)

**URL:** `http://localhost/skyeface/repairs/track`

**Steps:**
1. Enter the tracking number from step 1 (e.g., REP-ABC-20260121-0001)
2. Click "Track Status"
3. ✅ Should see:





















































































































































































































































































































Start with step 1 above and work through each test to verify everything is working correctly.**Your system is ready to test!** 🚀---- ✅ Progress bar shows correct percentage- ✅ Timeline displays current status- ✅ Estimated cost shows on tracking page- ✅ Tracking page displays all repair details- ✅ Tracking number appears in success message- ✅ Booking form submits successfully- ✅ Price displays/updates instantly on device type changeWhen everything is working:## Success Indicators---- ✅ No console errors on page load- ✅ Images/assets loading quickly- ✅ Database queries optimized with indexes- ✅ Tracking page load < 1 second- ✅ Form submission < 2 seconds## Performance Checklist---5. ✅ Progress bar should display correctly4. ✅ Timeline should be readable3. ✅ Results should stack vertically2. ✅ Search box should be usable1. Visit /repairs/track on mobile**Test tracking on mobile:**6. ✅ Input fields should be touch-friendly5. ✅ Buttons should be clickable4. ✅ Price box should resize properly3. ✅ Form should be fully responsive2. Scroll to repair section1. Visit services page on mobile**Test on mobile browser:**## Mobile Testing---```// Should fail with: "Issue Description must be at least 10 characters"// Try submitting with empty issue description```javascript### Test Invalid Submission- ✅ Issue Description: Required, min 10 chars, max 1000 chars- ✅ Model: Required, max 100 chars- ✅ Brand: Required, max 100 chars- ✅ Device Type: Required, must be from dropdown- ✅ Phone: Required, max 20 chars- ✅ Email: Required, valid email format- ✅ Name: Required, max 255 chars### Required Fields## Validation Tests---- Verify token matches between form and submission- Check token is valid (not expired)- Ensure form includes CSRF token**Solution:****Cause:** Missing or invalid CSRF token### Issue: CSRF token mismatch```php artisan migrate# Or manually run:php artisan migrate:refresh```bash**Solution:****Cause:** Migrations didn't run properly### Issue: Database error "repairs table not found"- Try selecting a different device type- Ensure device type dropdown has a value- Check browser console for errors**Solution:****Cause:** JavaScript not running or device type not selected### Issue: Price not showing- Format should be: REP-XXX-YYYYMMDD-0000- Verify repair was created successfully- Check tracking number matches exactly (case-sensitive)**Solution:** **Cause:** Wrong tracking number format or repair doesn't exist### Issue: "No Repair Found"## Common Issues & Solutions---```console.log(document.getElementById('repair_cost_estimate').value); // Should show 28console.log(document.getElementById('priceAmount').textContent);  // Should show $28.00// Check if price updatesupdateRepairPrice();document.getElementById('repair_device_type').value = 'Tablet';// Simulate device type change```javascript**Test price update:**```// }//   Other: 50//   Printer: 40,//   Tablet: 28,//   Mobile Phone: 25,//   Desktop Computer: 30,//   Laptop: 35,// {// Output should be:console.log(repairPricing);// This should show the pricing object```javascript**Open browser console:**### Verify prices in JavaScript## Price Display Testing---```SELECT repair_id, status, stage, created_at FROM repair_statuses;-- Check statusesSELECT invoice_number, customer_name, device_type, status, cost_estimate FROM repairs;-- Check repairs```sql**Direct SQL:**```// Should show related status recordsApp\Models\Repair::first()->repairStatuses;// Should show records with cost_estimate field populatedApp\Models\Repair::all();php artisan tinker```php**Check repairs table:**## Database Verification---```    Est. Completion: 01/23/2026    Issue identified: Battery is defective...  ✓ Diagnosed (Jan 21)  ✓ Received (Jan 21)Timeline:Progress: 40%```**Then tracking will show:**```$repair->repairStatuses()->latest()->first();// View result$repair->update(['status' => 'Diagnosed']);]);    'estimated_completion' => now()->addDays(2)    'stage' => 2,    'description' => 'Issue identified: Battery is defective and needs replacement. Estimated parts: $85',    'status' => 'Diagnosed',$repair->repairStatuses()->create([$repair = App\Models\Repair::first();php artisan tinker```php**Using Tinker:**## 5. Test Admin Status Update (Backend)---```}  "repair_id": 2  "tracking_number": "REP-XYZ-20260121-0002",  "invoice_number": "REP-XYZ-20260121-0002",  "message": "Repair booking submitted successfully",  "success": true,{```json**Expected Response:**```$response->json();]);    'cost_estimate' => 25.00    'urgency' => 'Normal',    'issue_description' => 'Battery not holding charge',    'model' => 'iPhone 14',    'brand' => 'Apple',    'device_type' => 'Mobile Phone',    'phone' => '+1 (555) 123-4567',    'email' => 'john@example.com',    'name' => 'John Doe',$response = \Http::post('http://localhost/skyeface/repairs', [php artisan tinker```php**Using Tinker:****Auth:** CSRF token required**Content-Type:** application/json**Method:** POST**Endpoint:** `POST /repairs`### 4. Test Booking API (Backend)---```}  "progress_percentage": 20  "current_status": "Received",  ],    }      "updated_at": "2026-01-21T15:45:00.000000Z"      "created_at": "2026-01-21T15:45:00.000000Z",      "estimated_completion": null,      "stage": 1,      "description": "Your device repair request has been received and registered.",      "status": "Received",      "repair_id": 1,      "id": 1,    {  "statuses": [  },    "updated_at": "2026-01-21T15:45:00.000000Z"    "created_at": "2026-01-21T15:45:00.000000Z",    "notes": null,    "cost_actual": null,    "cost_estimate": "35.00",    "status": "Received",    "urgency": "Express",    "issue_description": "Screen flickering intermittently",    "device_model": "MacBook Pro",    "device_brand": "Apple",    "device_type": "Laptop",    "customer_phone": "+1 (555) 123-4567",    "customer_email": "test@example.com",    "customer_name": "Test Customer",    "invoice_number": "REP-ABC-20260121-0001",    "id": 1,  "repair": {  "success": true,{```json**Expected Response:**```curl http://localhost/skyeface/repairs/track/REP-ABC-20260121-0001```bash**Command (using curl):****Auth:** None (public)**Method:** GET**Endpoint:** `GET /repairs/track/{invoiceNumber}`### 3. Test API Endpoint (Backend)---```    1/21/2026 3:45 PM    Your device repair request has been received and registered.  ✓ ReceivedRepair Timeline:Overall Progress: 20% [████░░░░░░░]Estimated Diagnosis Fee: $35.00    ← Shows pricing!Phone: +1 (555) 123-4567Email: test@example.comCustomer Name: Test CustomerUrgency: Express
Status: ReceivedDevice: Apple MacBook Pro (Laptop)Tracking Number: REP-ABC-20260121-0001```**Expected Display:**   - Timeline with 1 entry: "Received"   - Progress bar at 20% (Received status)   - **Estimated Cost: $35.00** (based on device type)   - Customer details   - Device info (Apple MacBook Pro - Laptop)   - Tracking number
