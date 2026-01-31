<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailDeliveryTest;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Intervention\Image\ImageManagerStatic as Image;

class SettingController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        $settings = [
            'company_name' => config('company.name'),
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'app_debug' => config('app.debug'),
            // Prefer env APP_TIMEZONE if present (helps after writing .env at runtime)
            'app_timezone' => env('APP_TIMEZONE', config('app.timezone')),
            'app_locale' => config('app.locale'),
            'support_phone' => config('app.support_phone', '+1 (555) 123-4567'),
            'support_email' => config('app.support_email', 'support@example.com'),
            'site_mode' => env('APP_MODE', config('app.mode', 'live')),
            'site_title' => env('SITE_TITLE', config('site.title', config('app.name'))),
            'site_message' => env('SITE_MESSAGE', config('site.message', '')),
            'mail_mailer' => env('MAIL_MAILER', 'smtp'),
            'mail_host' => env('MAIL_HOST'),
            'mail_port' => env('MAIL_PORT'),
            'mail_username' => env('MAIL_USERNAME'),
            'mail_password' => env('MAIL_PASSWORD'),
            'mail_encryption' => env('MAIL_ENCRYPTION'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS'),
            'mail_from_name' => env('MAIL_FROM_NAME'),
        ];
        // Provide a full list of PHP-supported timezones to the view
        $timezones = \DateTimeZone::listIdentifiers();

        return view('admin.settings.index', ['settings' => $settings, 'timezones' => $timezones]);
    }

    /**
     * Email Deliverability dashboard.
     */
    public function emailDeliverability()
    {
        $tests = EmailDeliveryTest::orderBy('provider')->get();
        return view('admin.settings.email_deliverability', ['tests' => $tests]);
    }

    /**
     * Manually trigger email provider tests.
     */
    public function runEmailTests()
    {
        try {
            Artisan::call('email:test-providers');
            return redirect()->route('admin.settings.email_deliverability')
                            ->with('success', 'Email delivery tests completed!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.email_deliverability')
                            ->with('error', 'Test failed: ' . $e->getMessage());
        }
    }

    /**
     * Store the updated settings.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'site_mode' => 'nullable|string|in:live,maintenance,under_construction',
            'site_title' => 'nullable|string|max:255',
            'site_message' => 'nullable|string',
            'app_timezone' => 'required|timezone',
            'app_locale' => 'required|string|max:10',
            'support_phone' => 'required|string|max:20',
            'support_email' => 'required|email',
            'mail_mailer' => 'required|string|in:smtp,mailgun,postmark,sendmail,log',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|numeric|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);
        // Persist to .env and runtime config so changes take effect immediately
        $envMap = [
            'COMPANY_NAME' => $validated['company_name'],
            'APP_NAME' => $validated['app_name'],
            'APP_MODE' => $validated['site_mode'] ?? 'live',
            'SITE_TITLE' => $validated['site_title'] ?? $validated['app_name'],
            'SITE_MESSAGE' => $validated['site_message'] ?? '',
            'APP_URL' => $validated['app_url'],
            'APP_TIMEZONE' => $validated['app_timezone'],
            'APP_LOCALE' => $validated['app_locale'],
            'SUPPORT_PHONE' => $validated['support_phone'],
            'SUPPORT_EMAIL' => $validated['support_email'],
            'MAIL_MAILER' => $validated['mail_mailer'],
            'MAIL_HOST' => $validated['mail_host'] ?? '',
            'MAIL_PORT' => $validated['mail_port'] ?? '',
            'MAIL_USERNAME' => $validated['mail_username'] ?? '',
            'MAIL_PASSWORD' => $validated['mail_password'] ?? '',
            'MAIL_ENCRYPTION' => $validated['mail_encryption'] ?? '',
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => $validated['mail_from_name'],
        ];

        foreach ($envMap as $key => $value) {
            $this->setEnvironmentValue($key, $value);
        }

        // Update runtime config so values are immediately available
        config(['company.name' => $validated['company_name']]);
        config(['app.name' => $validated['app_name']]);
        // Apply site mode/title/message to runtime config so middleware can read them
        config(['app.mode' => $validated['site_mode'] ?? 'live']);
        config(['site.title' => $validated['site_title'] ?? $validated['app_name']]);
        config(['site.message' => $validated['site_message'] ?? '']);
        config(['app.url' => $validated['app_url']]);
        config(['app.timezone' => $validated['app_timezone']]);
        config(['app.locale' => $validated['app_locale']]);
        config(['company.support_phone' => $validated['support_phone']]);
        config(['company.support_email' => $validated['support_email']]);
        // Backwards-compat: keep app.* values for code expecting them
        config(['app.support_phone' => $validated['support_phone']]);
        config(['app.support_email' => $validated['support_email']]);
        config(['mail.mailer' => $validated['mail_mailer']]);
        config(['mail.host' => $validated['mail_host']]);
        config(['mail.port' => $validated['mail_port']]);
        config(['mail.username' => $validated['mail_username']]);
        config(['mail.password' => $validated['mail_password']]);
        config(['mail.encryption' => $validated['mail_encryption']]);
        config(['mail.from.address' => $validated['mail_from_address']]);
        config(['mail.from.name' => $validated['mail_from_name']]);

        // Clear config cache if present to ensure persistence on next request
        try {
            Artisan::call('config:clear');
        } catch (\Exception $e) {
            // ignore
        }

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully!');
    }

    /**
     * Set or update an environment variable in the .env file.
     */
    protected function setEnvironmentValue(string $key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return;
        }

        $escaped = str_replace('\"', '"', addslashes($value));
        $valueFormatted = strpos($value, ' ') !== false ? '"' . $escaped . '"' : $escaped;

        $contents = file_get_contents($path);

        if (strpos($contents, $key . '=') !== false) {
            $contents = preg_replace(
                '/^' . preg_quote($key, '/') . '=.*/m',
                $key . '=' . $valueFormatted,
                $contents
            );
        } else {
            $contents .= "\n" . $key . '=' . $valueFormatted;
        }

        file_put_contents($path, $contents);
    }

    /**
     * Show backup page.
     */
    public function backup()
    {
        // List backup files in storage/app/backups
        $files = [];
        if (Storage::disk('local')->exists('backups')) {
            $files = collect(Storage::disk('local')->files('backups'))
                ->map(function ($path) {
                    return [
                        'path' => $path,
                        'name' => basename($path),
                        'size' => Storage::disk('local')->size($path),
                        'modified' => Storage::disk('local')->lastModified($path),
                    ];
                })->sortByDesc('modified')->values()->all();
        }

        return view('admin.settings.backup', ['backups' => $files]);
    }

    /**
     * Perform backup.
     */
    public function performBackup(Request $request)
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');

        $timestamp = now()->format('Ymd_His');
        $filesCreated = [];

        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        try {
            if ($connection === 'sqlite') {
                $databasePath = database_path($dbConfig['database']);
                if (file_exists($databasePath)) {
                    $backupName = "backups/sqlite_{$timestamp}.sqlite";
                    copy($databasePath, storage_path('app/' . $backupName));
                    $filesCreated[] = $backupName;
                }
            } elseif ($dbConfig['driver'] === 'mysql') {
                $user = $dbConfig['username'] ?? env('DB_USERNAME');
                $pass = $dbConfig['password'] ?? env('DB_PASSWORD');
                $host = $dbConfig['host'] ?? env('DB_HOST', '127.0.0.1');
                $port = $dbConfig['port'] ?? env('DB_PORT', '3306');
                $database = $dbConfig['database'] ?? env('DB_DATABASE');

                $dumpPath = storage_path('app/backups/mysql_' . $timestamp . '.sql');

                // Build mysqldump command. Note: requires mysqldump available on server.
                $command = [
                    'mysqldump',
                    '--user=' . $user,
                    '--password=' . $pass,
                    '--host=' . $host,
                    '--port=' . $port,
                    $database,
                ];

                $process = new Process($command);
                $process->setTimeout(300);
                $process->run();

                if ($process->isSuccessful()) {
                    // Save output to file
                    file_put_contents($dumpPath, $process->getOutput());
                    $filesCreated[] = 'backups/' . basename($dumpPath);
                } else {
                    // capture error
                    $error = $process->getErrorOutput();
                    return redirect()->route('admin.settings.backup')
                        ->with('error', 'Failed to create MySQL dump: ' . Str::limit($error, 200));
                }
            } else {
                // Unsupported driver: create .env and config dump as fallback
                $envCopy = storage_path('app/backups/env_' . $timestamp . '.env');
                copy(base_path('.env'), $envCopy);
                $filesCreated[] = 'backups/' . basename($envCopy);
            }

            // Optionally create a zip that includes .env and db dump
            $zipName = 'backups/backup_package_' . $timestamp . '.zip';
            $zipPath = storage_path('app/' . $zipName);
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                // add created files
                foreach ($filesCreated as $f) {
                    $zip->addFile(storage_path('app/' . $f), basename($f));
                }
                // include .env
                if (file_exists(base_path('.env'))) {
                    $zip->addFile(base_path('.env'), '.env');
                }
                $zip->close();
                $filesCreated[] = $zipName;
            }

            return redirect()->route('admin.settings.backup')
                        ->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.backup')
                        ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return redirect()->route('admin.settings.backup')->with('error', 'Invalid backup path');
        }

        $path = rawurldecode($path);
        if (!Storage::disk('local')->exists($path)) {
            return redirect()->route('admin.settings.backup')->with('error', 'Backup not found');
        }

        $full = storage_path('app/' . $path);
        return response()->download($full);
    }

    /**
     * Show company branding page (logo & favicon).
     */
    public function companyBranding()
    {
        $branding = \App\Models\Branding::first() ?? new \App\Models\Branding();
        return view('admin.settings.company_branding', ['branding' => $branding]);
    }

    /**
     * Store company branding (logo & favicon upload).
     */
    public function storeCompanyBranding(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,ico,gif|max:512',
            'name_logo' => 'nullable|image|mimes:jpeg,png,gif,svg|max:1024',
            'show_menu_offer_image' => 'nullable|boolean',
            'company_name' => 'nullable|string|max:255',
            'cac_number' => 'nullable|string|max:50',
            'rc_number' => 'nullable|string|max:50',
        ]);

        // Get or create the branding record
        $branding = \App\Models\Branding::firstOrCreate([]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($branding->logo && Storage::disk('public')->exists($branding->logo)) {
                Storage::disk('public')->delete($branding->logo);
            }
            // Store new logo in public/assets/branding/
            $logoPath = $request->file('logo')->store('assets/branding', 'public');
            $branding->logo = $logoPath;
            // Debug log: record logo path
            Log::info('Company branding - logo stored', ['logo' => $logoPath]);

            // If no favicon uploaded, attempt to generate one from the logo
            if (! $request->hasFile('favicon')) {
                try {
                    // Use Intervention Image if available to resize and create a 32x32 PNG favicon
                    if (class_exists('\Intervention\Image\ImageManagerStatic')) {
                        $img = Image::make(Storage::disk('public')->path($logoPath));
                        $img->fit(32, 32, function ($constraint) {
                            $constraint->upsize();
                        });
                        $faviconName = 'favicon_' . time() . '.png';
                        $faviconPath = 'assets/branding/' . $faviconName;
                        // Ensure directory exists
                        $dir = dirname(Storage::disk('public')->path($faviconPath));
                        if (! is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $img->save(Storage::disk('public')->path($faviconPath));
                        $branding->favicon = $faviconPath;
                    } else {
                        // Fallback: if Intervention not available, copy the logo as favicon (no resize)
                        $branding->favicon = $logoPath;
                    }
                } catch (\Exception $e) {
                    // If favicon generation fails, leave favicon unchanged and continue
                }
            }
        }

        // Handle favicon upload (explicit upload overrides generated one)
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($branding->favicon && Storage::disk('public')->exists($branding->favicon)) {
                Storage::disk('public')->delete($branding->favicon);
            }
            // Store new favicon in public/assets/branding/
            $faviconPath = $request->file('favicon')->store('assets/branding', 'public');
            $branding->favicon = $faviconPath;
            // Debug log: record favicon path
            Log::info('Company branding - favicon stored', ['favicon' => $faviconPath]);
        }

        // Handle name logo image upload
        if ($request->hasFile('name_logo')) {
            // Delete old name logo if exists
            if ($branding->name_logo && Storage::disk('public')->exists($branding->name_logo)) {
                Storage::disk('public')->delete($branding->name_logo);
            }
            // Store new name logo in public/assets/branding/
            $nameLogoPath = $request->file('name_logo')->store('assets/branding', 'public');
            $branding->name_logo = $nameLogoPath;
            // Debug log: record name logo path
            Log::info('Company branding - name_logo stored', ['name_logo' => $nameLogoPath]);
        }

        // Handle menu offer image visibility toggle
        $branding->show_menu_offer_image = $request->has('show_menu_offer_image');

        // Store company information
        $branding->company_name = $validated['company_name'] ?? null;
        $branding->cac_number = $validated['cac_number'] ?? null;
        $branding->rc_number = $validated['rc_number'] ?? null;

        // Debug log: show branding attributes before save
        Log::info('Company branding - attributes before save', $branding->getAttributes());

        try {
            $branding->save();

            // Sync to CompanySetting so older parts of the app that read CompanySetting() see the new assets
            try {
                $company = \App\Models\CompanySetting::firstOrCreate([]);
                $company->logo = $branding->logo;
                $company->favicon = $branding->favicon;
                $company->name_logo = $branding->name_logo;
                $company->show_menu_offer_image = $branding->show_menu_offer_image;
                $company->company_name = $branding->company_name;
                $company->cac_number = $branding->cac_number;
                $company->rc_number = $branding->rc_number;
                $company->save();
                \Log::info('CompanySetting synced from Branding', ['company_id' => $company->id]);
            } catch (\Exception $e) {
                \Log::error('Failed to sync CompanySetting from Branding: ' . $e->getMessage(), ['exception' => $e]);
            }

        } catch (\Exception $e) {
            // Log and return friendly message to admin
            \Log::error('Failed to save company branding: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.settings.company_branding')
                            ->with('error', 'Failed to save branding: ' . \Str::limit($e->getMessage(), 200));
        }

        return redirect()->route('admin.settings.company_branding')
                        ->with('success', 'Company branding updated successfully!');
    }

    /**
     * Display payment processors settings page.
     */
    public function paymentProcessors()
    {
        // Get active processor
        $activeProcessor = config('payment.active_processor', 'flutterwave');

        // Get payment processor settings
        $paymentSettings = [
            'flutterwave' => [
                'public_key' => config('payment.flutterwave.public_key', ''),
                'secret_key' => config('payment.flutterwave.secret_key', ''),
                'encrypt_key' => config('payment.flutterwave.encrypt_key', ''),
                'environment' => config('payment.flutterwave.environment', 'live'),
                'enabled' => config('payment.flutterwave.enabled', false),
            ],
            'paystack' => [
                'public_key' => config('payment.paystack.public_key', ''),
                'secret_key' => config('payment.paystack.secret_key', ''),
                'environment' => config('payment.paystack.environment', 'live'),
                'currency' => config('payment.paystack.currency', 'NGN'),
                'enabled' => config('payment.paystack.enabled', false),
            ],
        ];

        // Global payment settings
        $globalSettings = [
            'payment_currency' => config('payment.currency', 'NGN'),
            'payment_timeout' => config('payment.timeout', 30),
            'payment_webhook_secret' => config('payment.webhook_secret', ''),
            'test_mode' => config('payment.test_mode', false),
        ];

        // Check which processors are configured
        $flutterwaveConfigured = !empty($paymentSettings['flutterwave']['public_key']) && !empty($paymentSettings['flutterwave']['secret_key']);
        $paystackConfigured = !empty($paymentSettings['paystack']['public_key']) && !empty($paymentSettings['paystack']['secret_key']);

        return view('admin.settings.payment_processors', [
            'activeProcessor' => $activeProcessor,
            'paymentSettings' => $paymentSettings,
            'globalSettings' => $globalSettings,
            'flutterwaveConfigured' => $flutterwaveConfigured,
            'paystackConfigured' => $paystackConfigured,
        ]);
    }

    /**
     * Set the active payment processor.
     */
    public function setActivePaymentProcessor(Request $request)
    {
        $request->validate([
            'active_processor' => ['required', 'in:flutterwave,paystack'],
        ]);

        // Update .env file
        $this->updateEnv('PAYMENT_ACTIVE_PROCESSOR', $request->active_processor);

        return redirect()->route('admin.settings.payment_processors')
                        ->with('success', 'Payment processor switched to ' . ucfirst($request->active_processor) . ' successfully!');
    }

    /**
     * Save payment processor configuration.
     */
    public function savePaymentProcessor(Request $request, $processor)
    {
        // Validate processor type
        if (!in_array($processor, ['flutterwave', 'paystack'])) {
            return redirect()->route('admin.settings.payment_processors')
                            ->with('error', 'Invalid payment processor specified.');
        }

        // Validate based on processor
        if ($processor === 'flutterwave') {
            $request->validate([
                'public_key' => ['required', 'string'],
                'secret_key' => ['required', 'string'],
                'encrypt_key' => ['nullable', 'string'],
                'environment' => ['required', 'in:live,sandbox'],
                'enabled' => ['nullable', 'boolean'],
            ]);

            // Update .env
            $this->updateEnv('FLUTTERWAVE_PUBLIC_KEY', $request->public_key);
            $this->updateEnv('FLUTTERWAVE_SECRET_KEY', $request->secret_key);
            $this->updateEnv('FLUTTERWAVE_ENCRYPT_KEY', $request->encrypt_key ?? '');
            $this->updateEnv('FLUTTERWAVE_ENVIRONMENT', $request->environment);
            $this->updateEnv('FLUTTERWAVE_ENABLED', $request->has('enabled') ? '1' : '0');

            $message = 'Flutterwave configuration saved successfully!';
        } else {
            $request->validate([
                'public_key' => ['required', 'string'],
                'secret_key' => ['required', 'string'],
                'environment' => ['required', 'in:live,test'],
                'currency' => ['required', 'string'],
                'enabled' => ['nullable', 'boolean'],
            ]);

            // Update .env
            $this->updateEnv('PAYSTACK_PUBLIC_KEY', $request->public_key);
            $this->updateEnv('PAYSTACK_SECRET_KEY', $request->secret_key);
            $this->updateEnv('PAYSTACK_ENVIRONMENT', $request->environment);
            $this->updateEnv('PAYSTACK_CURRENCY', $request->currency);
            $this->updateEnv('PAYSTACK_ENABLED', $request->has('enabled') ? '1' : '0');

            $message = 'Paystack configuration saved successfully!';
        }

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()->route('admin.settings.payment_processors')
                        ->with('success', $message);
    }

    /**
     * Save global payment settings.
     */
    public function savePaymentGlobalSettings(Request $request)
    {
        $request->validate([
            'payment_currency' => ['required', 'string'],
            'payment_timeout' => ['required', 'integer', 'min:5', 'max:120'],
            'payment_webhook_secret' => ['nullable', 'string'],
            'test_mode' => ['nullable', 'boolean'],
        ]);

        // Update .env
        $this->updateEnv('PAYMENT_CURRENCY', $request->payment_currency);
        $this->updateEnv('PAYMENT_TIMEOUT', $request->payment_timeout);
        $this->updateEnv('PAYMENT_WEBHOOK_SECRET', $request->payment_webhook_secret ?? '');
        $this->updateEnv('PAYMENT_TEST_MODE', $request->has('test_mode') ? '1' : '0');

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()->route('admin.settings.payment_processors')
                        ->with('success', 'Global payment settings saved successfully!');
    }

    /**
     * Helper method to update .env file.
     */
    private function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $env = file_get_contents($path);

            // Check if key exists
            if (strpos($env, $key . '=') !== false) {
                // Replace existing key
                $env = preg_replace('/^' . preg_quote($key) . '=.*/m', $key . '=' . $value, $env);
            } else {
                // Add new key
                $env .= "\n" . $key . '=' . $value;
            }

            file_put_contents($path, $env);
        }
    }
}
