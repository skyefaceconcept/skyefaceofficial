<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailDeliveryTest;
use Exception;

class TestEmailProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-providers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email delivery to multiple providers (Gmail, Outlook, Yahoo, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email delivery to providers...');

        // Define test providers with their test email addresses
        $providers = [
            ['name' => 'Gmail', 'email' => 'deiqonfx@gmail.com'],
            ['name' => 'Outlook', 'email' => 'test@outlook.com'],
            ['name' => 'Live.com', 'email' => 'skyeface@live.com'],
            ['name' => 'Yahoo', 'email' => 'nochyemish22@yahoo.com'],
        ];

        foreach ($providers as $provider) {
            $this->testProvider($provider['name'], $provider['email']);
        }

        $this->info('Email delivery tests completed!');
    }

    /**
     * Test email delivery to a specific provider.
     */
    private function testProvider(string $providerName, string $testEmail)
    {
        $this->info("Testing {$providerName} ({$testEmail})...");

        try {
            // Send test email
            Mail::raw(
                "This is a test email from Skyeface.\n\nIf you received this, {$providerName} delivery is working.",
                function ($message) use ($testEmail, $providerName) {
                    $message->to($testEmail)
                            ->subject("Skyeface Delivery Test - {$providerName}")
                            ->from(config('mail.from.address'), config('mail.from.name'));
                }
            );

            // Record success
            $this->recordTestResult(
                $providerName,
                $testEmail,
                'sent',
                null,
                250
            );

            $this->line("  ✓ {$providerName} - Sent successfully");
        } catch (Exception $e) {
            // Record failure
            $this->recordTestResult(
                $providerName,
                $testEmail,
                'failed',
                $e->getMessage(),
                null
            );

            $this->error("  ✗ {$providerName} - Failed: " . $e->getMessage());
        }
    }

    /**
     * Record test result in database.
     */
    private function recordTestResult(
        string $provider,
        string $testEmail,
        string $status,
        ?string $errorMessage = null,
        ?int $responseCode = null
    ) {
        EmailDeliveryTest::updateOrCreate(
            ['provider' => $provider],
            [
                'test_email' => $testEmail,
                'status' => $status,
                'error_message' => $errorMessage,
                'response_code' => $responseCode,
                'last_tested_at' => now(),
            ]
        );
    }
}
