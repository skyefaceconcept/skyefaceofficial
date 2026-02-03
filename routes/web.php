<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactTicketController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\InstallController;

// Installer routes (re-enabled when not already installed). Routes that accept POST
// requests bypass CSRF to avoid a bootstrap chicken-and-egg when sessions DB is absent.
if (! file_exists(storage_path('app/installed'))) {
    Route::get('/install', [InstallController::class, 'show'])->name('install.show');
    Route::post('/install/db-test', [InstallController::class, 'dbTest'])->name('install.db.test')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/install/db-create', [InstallController::class, 'dbCreate'])->name('install.db.create')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/install/db-migrate', [InstallController::class, 'dbMigrate'])->name('install.db.migrate')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/install/db-migrate-start', [InstallController::class, 'dbMigrateStart'])->name('install.db.migrate-start')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/install/db-migrate-files', [InstallController::class, 'dbMigrateFiles'])->name('install.db.migrate-files')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::get('/install/db-migrate-status', [InstallController::class, 'dbMigrateStatus'])->name('install.db.migrate-status');
    Route::get('/install/list-migrations', [InstallController::class, 'listMigrations'])->name('install.list-migrations');
    Route::post('/install/queue-work-once', [InstallController::class, 'queueWorkOnce'])->name('install.queue-work-once')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/install', [InstallController::class, 'install'])->name('install.perform')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
} else {
    // Return a simple page informing installer is removed
    Route::get('/install', function () { return response()->view('install')->header('Content-Type', 'text/html'); });
}


Route::get('/', [HomeController::class, 'index'])->name('home');

// DEVICE REPAIR BOOKING
Route::get('/device-repair-booking', function () { return view('device-repair-booking'); })->name('repairs.booking');

// PUBLIC SHOP ROUTES
Route::get('/shop', [ShoppingController::class, 'index'])->name('shop.index');
Route::get('/shop/{portfolio:slug}', [ShoppingController::class, 'show'])->name('shop.show');

// CART ROUTES
Route::get('/cart', function () { return view('shop.cart'); })->name('cart.show');
Route::get('/checkout', function () { return view('shop.checkout'); })->name('cart.checkout');

// CHECKOUT ROUTES
Route::post('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/place-order', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/saved-addresses', [CheckoutController::class, 'getSavedAddresses'])->name('checkout.getSavedAddresses');

// DEBUG ENDPOINT - Remove after testing
Route::post('/debug/checkout-test', function (\Illuminate\Http\Request $request) {
    \Log::info('DEBUG: Checkout test hit', $request->all());
    return response()->json([
        'success' => true,
        'message' => 'Checkout endpoint is working',
        'received_data' => [
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'cart_length' => strlen($request->input('cart', '')),
            'total' => $request->input('total'),
        ]
    ]);
})->name('debug.checkout-test');

// Public Contact Form Route
Route::get('/contact', function () { return view('contact'); })->name('contact.show');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.store');

// Public Legal Pages Routes
Route::get('/terms', function () { return view('terms'); })->name('terms');
Route::get('/policy', function () { return view('policy'); })->name('policy');

// Public Quote Submission Route
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');

// Branding asset route - serve images from storage through Laravel to avoid symlink/permission issues
use App\Http\Controllers\BrandingAssetController;
Route::get('/branding/{path}', [BrandingAssetController::class, 'asset'])->where('path', '.*')->name('branding.asset');
Route::get('/quotes/track', function () { return view('quotes.track'); })->name('quotes.track.page');
Route::post('/quotes/track', [QuoteController::class, 'track'])->name('quotes.track');

// Public Device Repair Routes
Route::get('/repairs/track', function () { return view('repairs.track'); })->name('repairs.track.page');
Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
Route::post('/repairs/status', [RepairController::class, 'searchStatus'])->name('repairs.status');
Route::get('/repairs/track/{invoiceNumber}', [RepairController::class, 'getStatus'])->name('repairs.status');
Route::get('/api/repairs/pricing', [RepairController::class, 'getPricing'])->name('api.repairs.pricing');
Route::get('/api/payment/processor', [RepairController::class, 'getActivePaymentProcessor'])->name('api.payment.processor');
Route::get('/repairs/{repair}/payment', [RepairController::class, 'showRepairPaymentForm'])->name('repairs.payment-form');
Route::post('/repairs/{repair}/initiate-payment', [RepairController::class, 'initiateRepairPayment'])->name('repairs.initiate-payment');
Route::get('/repairs/payment-callback', [RepairController::class, 'paymentCallback'])->name('repairs.payment-callback');
Route::get('/repairs/flutterwave-callback', [RepairController::class, 'flutterwaveCallback'])->name('repairs.flutterwave-callback');
Route::get('/repairs/paystack-callback', [RepairController::class, 'paystackCallback'])->name('repairs.paystack-callback');


// Client Ticket Viewing Route
Route::get('/ticket/{ticket_number}', [ContactController::class, 'viewTicket'])->name('ticket.view');
Route::post('/ticket/{ticket_number}/reply', [ContactController::class, 'clientReply'])->name('ticket.clientReply');
Route::get('/ticket/{ticket_number}/messages', [ContactController::class, 'getMessages'])->name('ticket.messages');

// Email verification routes (named routes expected by Laravel notifications)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    Mail::to($request->user()->email)->send(new VerifyEmail($request->user()));
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Live countdown page for verification (opens in browser; email clients typically block JS)
Route::get('/email/verify/countdown', function (Request $request) {
    $expires = $request->query('expires');
    $url = $request->query('url');
    return view('verification.countdown', ['expiresAt' => $expires, 'verificationUrl' => $url]);
})->name('verification.countdown');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'redirect.superadmin',
])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // User Profile Routes
    Route::get('/user/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/user/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/user/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update.post');
    Route::post('/user/profile-information', [App\Http\Controllers\ProfileController::class, 'updateProfileInformation'])->name('user-profile-information.update');
    Route::post('/user/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('user-password.update');

    // Two-Factor Authentication Routes (Client)
    Route::post('/user/profile/2fa/enable', [App\Http\Controllers\ProfileController::class, 'enableTwoFactor'])->name('profile.2fa.enable');
    Route::post('/user/profile/2fa/disable', [App\Http\Controllers\ProfileController::class, 'disableTwoFactor'])->name('profile.2fa.disable');

    // Session Management Routes (Client)
    Route::post('/user/profile/sessions/logout/{sessionId?}', [App\Http\Controllers\ProfileController::class, 'logoutSession'])->name('profile.logout-session');
    Route::post('/user/profile/sessions/logout-others', [App\Http\Controllers\ProfileController::class, 'logoutOtherSessions'])->name('profile.logout-other-sessions');

    // Client Ticket Management Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/{ticket_number}', [ClientDashboardController::class, 'showTicket'])->name('show');
        Route::post('/{ticket_number}/reply', [ClientDashboardController::class, 'replyTicket'])->name('reply');
        Route::post('/{ticket_number}/close', [ClientDashboardController::class, 'closeTicket'])->name('close');
    });

    // Client Quotes Routes
    Route::prefix('quotes')->name('quotes.')->group(function () {
        Route::get('/{quote_id}', [ClientDashboardController::class, 'showQuote'])->name('show');
    });
});

// Admin Routes - Only accessible by SuperAdmin
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'is.superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'adminShow'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'adminUpdate'])->name('profile.update');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'adminUpdate'])->name('profile.update.post');
    // Admin Password Update (uses same controller method)
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    // Two-Factor Authentication Routes
    Route::post('/profile/2fa/enable', [App\Http\Controllers\ProfileController::class, 'enableTwoFactor'])->name('profile.enableTwoFactor');
    Route::post('/profile/2fa/disable', [App\Http\Controllers\ProfileController::class, 'disableTwoFactor'])->name('profile.disableTwoFactor');
    // Session Management Routes
    Route::post('/profile/sessions/logout/{sessionId?}', [App\Http\Controllers\ProfileController::class, 'logoutSession'])->name('profile.logoutSession');
    Route::post('/profile/sessions/logout-others', [App\Http\Controllers\ProfileController::class, 'logoutOtherSessions'])->name('profile.logoutOtherSessions');

    // User Management Routes
    Route::resource('users', UserController::class);
    Route::post('users/{user}/resend-verification', [UserController::class, 'resendVerification'])->name('users.resendVerification');
    Route::resource('roles', RoleController::class);
    Route::post('permissions/generate-from-route', [PermissionController::class, 'generateFromRoute'])->name('permissions.generateFromRoute');
    Route::resource('permissions', PermissionController::class);

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/backup', [SettingController::class, 'performBackup'])->name('settings.performBackup');
    Route::get('/settings/backup/download', [SettingController::class, 'downloadBackup'])->name('settings.downloadBackup');
    Route::get('/settings/email-deliverability', [SettingController::class, 'emailDeliverability'])->name('settings.email_deliverability');
    Route::post('/settings/email-deliverability/test', [SettingController::class, 'runEmailTests'])->name('settings.runEmailTests');
    Route::get('/settings/company-branding', [SettingController::class, 'companyBranding'])->name('settings.company_branding');
    Route::post('/settings/company-branding', [SettingController::class, 'storeCompanyBranding'])->name('settings.storeCompanyBranding');

    // Migration management (Admin only)
    Route::get('/settings/migrations', [SettingController::class, 'migrations'])->name('settings.migrations');
    Route::post('/settings/migrations/run', [SettingController::class, 'runMigration'])->name('settings.migrations.run');
    Route::post('/settings/migrations/refresh', [SettingController::class, 'refreshMigration'])->name('settings.migrations.refresh');
    Route::post('/settings/migrations/rollback', [SettingController::class, 'rollbackMigrations'])->name('settings.migrations.rollback');
    Route::get('/settings/payment-processors', [SettingController::class, 'paymentProcessors'])->name('settings.payment_processors');
    Route::get('/settings/payment-processors/set-active', function () {
        return redirect()->route('admin.settings.payment_processors');
    });
    Route::put('/settings/payment-processors/set-active', [SettingController::class, 'setActivePaymentProcessor'])->name('settings.setActivePaymentProcessor');
    Route::get('/settings/payment-processors/global/settings', function () {
        return redirect()->route('admin.settings.payment_processors');
    });
    Route::put('/settings/payment-processors/global/settings', [SettingController::class, 'savePaymentGlobalSettings'])->name('settings.savePaymentGlobalSettings');
    Route::put('/settings/payment-processors/{processor}', [SettingController::class, 'savePaymentProcessor'])->name('settings.savePaymentProcessor');
    // Redirect GET requests to payment processor endpoints to the main page
    Route::get('/settings/payment-processors/{processor}', function ($processor) {
        return redirect()->route('admin.settings.payment_processors');
    });

    // Admin Quote Management
    Route::resource('quotes', AdminQuoteController::class)->only(['index', 'show', 'destroy']);
    Route::put('quotes/{quote}/status', [AdminQuoteController::class, 'updateStatus'])->name('quotes.updateStatus');
    Route::post('quotes/{quote}/respond', [AdminQuoteController::class, 'respond'])->name('quotes.respond');
    Route::post('quotes/{quote}/notes', [AdminQuoteController::class, 'addNotes'])->name('quotes.addNotes');

    // Contact Ticket Routes
    Route::resource('contact-tickets', ContactTicketController::class);
    Route::post('contact-tickets/{contactTicket}/assign', [ContactTicketController::class, 'assign'])->name('contact-tickets.assign');
    Route::post('contact-tickets/{contactTicket}/reply', [ContactTicketController::class, 'reply'])->name('contact-tickets.reply');
    Route::get('contact-tickets/{contactTicket}/messages', [ContactTicketController::class, 'messages'])->name('contact-tickets.messages');
    Route::put('contact-tickets/{contactTicket}/status', [ContactTicketController::class, 'updateStatus'])->name('contact-tickets.updateStatus');
    Route::put('contact-tickets/{contactTicket}/close', [ContactTicketController::class, 'close'])->name('contact-tickets.close');

    // Admin Portfolio Management
    Route::resource('portfolio', PortfolioController::class);
    Route::post('portfolio/{portfolio}/footage', [PortfolioController::class, 'addFootage'])->name('portfolio.footage.add');
    Route::delete('portfolio-footage/{footage}', [PortfolioController::class, 'deleteFootage'])->name('portfolio.footage.delete');
    Route::post('portfolio/{portfolio}/reorder-footages', [PortfolioController::class, 'reorderFootages'])->name('portfolio.reorder');

    // Admin Orders Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');

    // Admin License Management
    Route::get('licenses', [App\Http\Controllers\Admin\LicenseController::class, 'index'])->name('licenses.index');
    Route::get('licenses/{license}', [App\Http\Controllers\Admin\LicenseController::class, 'show'])->name('licenses.show');
    Route::post('licenses/{license}/revoke', [App\Http\Controllers\Admin\LicenseController::class, 'revoke'])->name('licenses.revoke');
    Route::put('licenses/{license}/reactivate', [App\Http\Controllers\Admin\LicenseController::class, 'reactivate'])->name('licenses.reactivate');
    Route::get('licenses/export', [App\Http\Controllers\Admin\LicenseController::class, 'export'])->name('licenses.export');
    Route::get('api/licenses/{license}', [App\Http\Controllers\Admin\LicenseController::class, 'getData'])->name('api.licenses.data');

    // Admin Repairs Management
    Route::get('repairs', [RepairController::class, 'adminIndex'])->name('repairs.index');
    Route::get('repairs/{repair}', [RepairController::class, 'adminShow'])->name('repairs.show');
    Route::put('repairs/{repair}/status', [RepairController::class, 'adminUpdateStatus'])->name('repairs.updateStatus');
    Route::put('repairs/{repair}/cost', [RepairController::class, 'adminUpdateCost'])->name('repairs.updateCost');
    Route::post('repairs/{repair}/notes', [RepairController::class, 'adminAddNotes'])->name('repairs.addNotes');
    Route::get('repairs-pricing', [RepairController::class, 'adminPricingIndex'])->name('repairs.pricing.index');
    Route::put('repairs-pricing/{repairPricing}', [RepairController::class, 'adminUpdatePricing'])->name('repairs.pricing.update');

    // Payment Admin Routes
    Route::get('payments', [PaymentController::class, 'adminList'])->name('payments.index');
    Route::post('payments/{payment}/refresh', [PaymentController::class, 'adminRefreshPayment'])->name('payments.refresh');
    Route::post('payments/{payment}/update-status', [PaymentController::class, 'adminUpdatePaymentStatus'])->name('payments.updateStatus');

});

// Payment routes - PUBLIC (no authentication required for callback/success/failed)
Route::get('/payment/callback-test', function() {
    return response()->json(['success' => true, 'message' => 'Callback test route is working']);
})->name('payment.callback-test');

Route::get('/payment/callback-debug', function(Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Callback debug route is working',
        'all_params' => $request->all(),
        'query_params' => $request->query(),
    ]);
})->name('payment.callback-debug');

Route::match(['get', 'post'], '/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/order/{order}', [PaymentController::class, 'showOrder'])->name('payment.show');
Route::match(['get', 'post'], '/payment/flutterwave/pay', [PaymentController::class, 'payWithFlutterwave'])->name('payment.flutterwave.pay');
Route::match(['get', 'post'], '/payment/paystack/pay', [PaymentController::class, 'payWithPaystack'])->name('payment.paystack.pay');
Route::get('/payment/success/{payment}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
Route::post('/payment/check-status/{quote}', [PaymentController::class, 'checkStatus'])->name('payment.checkStatus');

// Payment form routes - requires authentication
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/payment/{quote}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment/{quote}', [PaymentController::class, 'createPayment'])->name('payment.create');

    // Client payment history
    Route::get('/payment-history', [PaymentController::class, 'clientHistory'])->name('payment.history');

    // Payment receipt download
    Route::get('/payment/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payment.receipt');
});

Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// About page
Route::view('/about', 'about')->name('about');

// Services page
Route::view('/services', 'services')->name('services');
// DEBUG: Test email route (remove in production)
Route::get('/test-email', function () {
    try {
        $testEmail = 'info@skyeface.com.ng';
        \Log::info('Test email starting to: ' . $testEmail);

        Mail::raw('This is a test email from Skyeface', function ($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('Test Email - ' . now());
        });

        \Log::info('Test email sent successfully');

        // Also log to database
        \DB::table('mail_logs')->insert([
            'to' => $testEmail,
            'subject' => 'Test Email - ' . now(),
            'body' => 'This is a test email',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return 'Test email sent to ' . $testEmail . '. Check mail_logs table and storage/logs/laravel.log';
    } catch (\Exception $e) {
        \Log::error('Test email failed: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return 'Test email FAILED: ' . $e->getMessage() . '<br>Check storage/logs/laravel.log for details';
    }
});
