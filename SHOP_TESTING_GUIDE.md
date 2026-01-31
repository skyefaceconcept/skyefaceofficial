# SHOP TESTING QUICK START

## Quick Test URLs

Replace `yoursite.local` with your actual domain.

### 1. Shop Index (Product Listing)
```
http://yoursite.local/shop
```
- Should show 12 products per page
- Pagination working at bottom
- Category buttons should work
- Search box accepts text

### 2. Test Filters
```
# Filter by Web category
http://yoursite.local/shop?category=web

# Filter by Mobile category  
http://yoursite.local/shop?category=mobile

# Filter by Design category
http://yoursite.local/shop?category=design

# Search for product
http://yoursite.local/shop?search=portfolio

# Sort by price low to high
http://yoursite.local/shop?sort=price-low

# Sort by price high to low
http://yoursite.local/shop?sort=price-high

# Sort by popular (most viewed)
http://yoursite.local/shop?sort=popular

# Combination: Web category + search
http://yoursite.local/shop?category=web&search=design
```

### 3. Product Details
Click any product from the shop index
```
http://yoursite.local/shop/{product-id}
```
- Should increment view count (check console)
- Shows 4 related products below
- Displays festive discount if enabled
- Can select license duration

### 4. Cart
```
http://yoursite.local/cart
```
- Shows all added products
- Can modify quantities
- Can remove items
- Shows total price

### 5. Checkout - Single Product
```
http://yoursite.local/checkout?portfolio_id={id}&license_duration=1year
```
- Shows order summary
- Billing form required
- Payment method selection
- Submit button works

### 6. Checkout - Cart
```
http://yoursite.local/cart → "Proceed to Checkout" button
```
- Shows all cart items
- Shows total with all licenses
- Form validation works

---

## Manual Testing Steps

### Test 1: Product Filtering
1. Go to `/shop`
2. Click "Web" category button
3. Should show only web portfolio products
4. Click "Mobile" - should change to mobile products
5. Click "All Categories" - should reset

**Expected:** Category correctly filters products

---

### Test 2: Search Functionality
1. Go to `/shop` with category filter: `/shop?category=web`
2. Type product name in search box
3. Click "Search" or press Enter
4. Products should filter by search term BUT still be in Web category

**Expected:** Search works while preserving category

---

### Test 3: Sorting
1. Go to `/shop`
2. Open dropdown "Sort by Latest"
3. Select "Price: Low → High"
4. Page should reload with products sorted by price ascending
5. Check product prices are in correct order

**Expected:** Products sorted correctly

---

### Test 4: Related Products
1. Click any product to view details (`/shop/{id}`)
2. Scroll down to "Related Products" section
3. Should show 4 products from same category
4. Most viewed products shown first (higher view counts)

**Expected:** 4 related products visible, sorted by popularity

---

### Test 5: Single Product Checkout
1. Click "Buy Now" on any product
2. Select license duration (6 months, 1 year, 2 years)
3. Click "Add to Cart" or "Buy Now"
4. Should go to checkout page
5. Fill in billing info and payment method
6. Click "Complete Order"
7. Should redirect to payment page

**Expected:** Order created, payment page shows

---

### Test 6: Cart Checkout
1. Go to `/shop`
2. Add 2-3 different products to cart (different categories)
3. Go to `/cart`
4. Verify all products shown with correct prices
5. Click "Proceed to Checkout"
6. Fill in billing and payment info
7. Click "Complete Order"

**Expected:** Multi-item order created correctly

---

### Test 7: Festive Discount
1. Log in as Admin
2. Go to Portfolio Edit for any product
3. Enable "Festive Discount" toggle
4. Set discount percentage (e.g., 20%)
5. Save
6. Go to `/shop` and find that product
7. Should show red discount badge with percentage
8. Original price should be strikethrough
9. New price should be calculated

**Expected:** Discount display working, price calculated correctly

---

### Test 8: Navbar Offer Image
1. Log in as Admin
2. Go to Settings → Company Branding
3. Toggle "Show Menu Offer Image" ON
4. Go to shop, check navbar dropdown - image visible
5. Toggle "Show Menu Offer Image" OFF
6. Refresh page, check navbar - image gone, no empty space

**Expected:** Image hides/shows, layout adjusts

---

### Test 9: View Count
1. Open product details page in browser
2. Check browser console (F12) for any errors
3. Refresh the page 3 times
4. View count should increment (check product edit in admin)

**Expected:** View count increases each page load

---

### Test 10: Full Order Flow
1. **Shop Index:** Browse and filter products (`/shop`)
2. **Product Details:** Click product, check related items
3. **Add to Cart:** Select license, add to cart
4. **View Cart:** Check totals correct (`/cart`)
5. **Checkout:** Fill billing info and payment method (`/checkout`)
6. **Order Creation:** Click "Complete Order"
7. **Payment Page:** Should show order summary (`/payment/{order-id}`)

**Expected:** Complete flow works without errors

---

## Performance Testing

### Check Database Queries
In **development environment** with DebugBar:

1. Go to `/shop`
2. Open DebugBar (bottom right)
3. Check "Database" tab
4. Should see **2-3 queries max**:
   - 1 for portfolios list
   - 1 for eager loading footages
   - 1 for pagination

Before fix: 13+ queries (one per item)

### Check Page Load Time
1. Open Browser DevTools (F12)
2. Go to Network tab
3. Load `/shop`
4. Check "Time to First Byte" (TTFB)
5. Should be < 500ms

### Monitor API Response
1. Open DevTools Network tab
2. Click a product
3. Check response time < 200ms

---

## Common Issues & Solutions

### Issue: Filters Not Working
**Solution:** Clear browser cache and cookies for the site

### Issue: Search Removing Category Filter
**Solution:** Upgrade ShoppingController to latest version

### Issue: Sorting Not Applied
**Solution:** Check database indexes were created with: `php artisan migrate`

### Issue: View Count Not Incrementing
**Solution:** Check database has view_count column and is not read-only

### Issue: Related Products Not Showing
**Solution:** Ensure products have category assigned and published status

### Issue: Checkout Slow
**Solution:** Check PaymentProcessorService isn't logging excessively

### Issue: Payment Not Processing
**Solution:** Verify Flutterwave/Paystack API keys in `.env` file

---

## Quick Commands

### Reset Everything (Testing Only)
```bash
# Clear cache
php artisan cache:clear

# Clear config
php artisan config:clear

# Migrate fresh (deletes all data!)
php artisan migrate:fresh --seed

# Rebuild indexes
php artisan migrate
```

### Check Logs
```bash
# View recent errors
tail -f storage/logs/laravel.log

# Clear logs
rm storage/logs/laravel.log
```

### Database Check
```bash
# Open MySQL console
mysql -u root

# Use your database
USE skyeface;

# Check portfolio indexes
SHOW INDEXES FROM portfolios;

# Check product count
SELECT COUNT(*) FROM portfolios WHERE status = 'published';

# Check view counts
SELECT title, view_count FROM portfolios ORDER BY view_count DESC LIMIT 5;
```

---

## Expected Test Results

| Test | Expected Result | Status |
|------|-----------------|--------|
| Shop Index | Shows 12 products per page | ✅ |
| Category Filter | Only shows selected category | ✅ |
| Search | Filters by title/description | ✅ |
| Sort | Orders by selected criteria | ✅ |
| Related Products | Shows 4 from same category | ✅ |
| View Count | Increments on page view | ✅ |
| Single Checkout | Creates order, shows payment | ✅ |
| Cart Checkout | Multiple items work | ✅ |
| Festive Discount | Shows badge and price | ✅ |
| Navbar Image | Hides/shows correctly | ✅ |
| Payment | Processes successfully | ✅ |
| Order Complete | Generates order ID | ✅ |

---

**Start Testing:** Begin with Test 1-3 for filters, then Test 5-6 for checkout
**Estimated Time:** 30-45 minutes for full testing

Report any issues immediately - the shop should be noticeably faster after optimization!
