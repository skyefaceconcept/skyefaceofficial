# PAGE IMPRESSIONS ANALYTICS SYSTEM - USER GUIDE

## Overview
The Page Impressions Analytics System is a professional website traffic tracking and analysis platform that monitors page views, unique visitors, device types, browsers, and traffic sources across your entire website.

---

## SETUP & INSTALLATION

### Step 1: Run Database Migration
The system requires two new database tables. Run the following command:

```bash
php artisan migrate
```

This creates:
- `page_impressions` - Stores individual page view records
- `page_impression_daily_summaries` - Stores daily aggregated data for faster queries

### Step 2: Verify Middleware Registration
The tracking middleware has been registered in `bootstrap/app.php`. It automatically tracks page views for:
- All public pages
- Excludes admin routes, APIs, AJAX requests, and static assets

### Step 3: Access the Dashboard
Once migration is complete, visit:
```
https://yoursite.com/admin/analytics/page-impressions
```

---

## FEATURES

### 📊 Main Dashboard
Displays:
- **Total Impressions** - Overall page views in selected period
- **Unique Visitors** - Count of distinct visitors (by IP)
- **Average Impressions/Day** - Daily average
- **Bounce Rate** - Estimated bounce percentage

**Time Period Filter:**
Select 7, 30, or 90 days of data. Custom date range support can be added.

### 📈 Charts
1. **Impressions Trend** (Line Chart)
   - Shows daily impressions and unique visitors over time
   - Helps identify traffic patterns and spikes

2. **Traffic by Device** (Doughnut Chart)
   - Desktop vs Mobile vs Tablet breakdown
   - Know your audience's device preferences

### 📄 Top Pages
Table showing:
- Page URL and title
- Total impressions
- Unique visitors count
- Quick action to view detailed analytics

### 🔗 Top Referrers
Sources driving traffic to your site:
- External referrer URLs
- Number of visits from each source
- Visual percentage bars

---

## PAGE DETAIL ANALYTICS

Click "View Details" on any page in the Top Pages table to access:

### Key Metrics
- **Total Impressions** - Page views
- **Unique Visitors** - Distinct visitors
- **Days Visited** - How many days the page was viewed
- **Average per Day** - Daily average

### Daily Traffic Chart
Shows daily impressions and unique visitors for that specific page.

### Device Breakdown
Pie chart showing which devices viewed the page (desktop/mobile/tablet).

### Referrers for This Page
Top sources that brought people to this specific page.

### Recent Views Table
Last 50 visitors with:
- Date & Time of view
- IP Address
- Device Type (with icon)
- Browser (Chrome, Safari, Firefox, Edge, Opera)
- Operating System (Windows, macOS, Linux, Android, iOS)
- User (if logged in, shows username)

---

## TRACKED DATA

For each page view, the system tracks:

| Field | Description | Purpose |
|-------|-------------|---------|
| page_url | Full URL path | Identify pages |
| page_title | HTML title from page | Display friendly names |
| page_route | Named route | Analytics grouping |
| ip_address | Visitor's IP | Unique visitor identification |
| user_agent | Browser string | Device/browser detection |
| device_type | desktop/mobile/tablet | Device analytics |
| browser | Chrome, Safari, Firefox, etc. | Browser market share |
| os | Windows, macOS, iOS, Android, Linux | OS analytics |
| referer | Source URL | Traffic source tracking |
| user_id | Logged-in user | Authenticated user tracking |
| viewed_at | Timestamp | Time-based analytics |

---

## EXPORT DATA

### Export to CSV
- Click **"Export CSV"** button on dashboard
- Downloads all impressions for selected period
- Includes all tracked fields
- Filename: `page-impressions-YYYY-MM-DD-HH-MM-SS.csv`

---

## DATA CLEANUP & MAINTENANCE

### Automatic Data Management
The system is optimized for large datasets with:
- Indexed database columns
- Query optimization with grouping and aggregation
- Daily summary tables for faster reporting

### Manual Cleanup
Remove old impressions to save database space:

```bash
# Via artisan command (coming soon)
php artisan app:cleanup-impressions --days=90
```

Or manually in the admin panel:
- Navigate to Analytics Settings (Future feature)
- Set retention period
- Click "Remove Old Data"

---

## TROUBLESHOOTING

### No data showing
1. Ensure migration was run: `php artisan migrate`
2. Visit website pages in browser to generate impressions
3. Wait 5-10 seconds for first data to appear
4. Check browser console for JavaScript errors

### Middleware not tracking
1. Verify middleware registered in `bootstrap/app.php`
2. Clear application cache: `php artisan cache:clear`
3. Ensure routes aren't excluded by middleware rules

### Incorrect device detection
Device detection uses User-Agent string parsing. Some uncommon browsers may be marked as "Unknown".

### Duplicate entries
IP-based unique visitor detection can show false duplicates if multiple users share one IP (corporate networks, WiFi). Consider adding additional identifiers.

---

## PERFORMANCE CONSIDERATIONS

### Database Optimization
- Tables have indexes on frequently queried columns
- Daily summaries reduce query time for decade-old data
- Older records can be archived to separate table

### Recommended Cleanup Schedule
```
Data Age      | Action
30 days       | Active dashboard
31-90 days    | Archive to summary table
90+ days      | Archive or delete
```

### Query Performance Tips
- Use date filters to reduce dataset
- Device/browser grouping is efficient
- Full URL list queries may be slow with millions of records

---

## API ENDPOINTS

### Chart Data (JSON)
```
GET /admin/analytics/page-impressions/chart-data?days=30
```

Returns JSON with labels and datasets for charts:
```json
{
  "labels": ["2026-02-01", "2026-02-02", ...],
  "datasets": [
    { "label": "Total Impressions", "data": [...] },
    { "label": "Unique Visitors", "data": [...] }
  ]
}
```

---

## FUTURE ENHANCEMENTS

Recommended features to add:
1. Custom date range picker
2. Goal/conversion tracking
3. Exit page analysis
4. Scroll depth tracking
5. User session/behavior recording
6. Comparison reports (period vs period)
7. Scheduled email reports
8. Segment audience by criteria
9. Real-time visitor stats
10. Google Analytics integration

---

## SECURITY & PRIVACY

### Admin Access Only
All analytics pages require superadmin authentication.

### IP Anonymization
Consider implementing IP masking for privacy compliance:
```php
// Mask last octet: 192.168.1.123 → 192.168.1.0
$ip = long2ip(ip2long($ip) & 0xFFFFFF00);
```

### GDPR Compliance
- Impressions are not linked to personally identifiable information
- No tracking of authenticated user browsing (unless specifically enabled)
- 90-day default retention for automated cleanup

---

## MODEL METHODS

The `PageImpression` model includes useful query methods:

```php
// Get statistics for date range
$stats = PageImpression::getPageStats($startDate, $endDate);

// Get traffic by device
$devices = PageImpression::getTrafficByDevice($startDate, $endDate);

// Get top pages
$topPages = PageImpression::getTopPages(10, $startDate, $endDate);

// Get unique visitors
$visitors = PageImpression::getUniqueVisitors($pageRoute, $startDate, $endDate);

// Get referrer stats
$referrers = PageImpression::getReferrerStats($startDate, $endDate);

// Get daily impressions for charts
$daily = PageImpression::getDailyImpressions(30); // Last 30 days
```

---

## SUPPORT

For issues or feature requests, refer to the main documentation or contact support.

**Last Updated:** February 6, 2026
