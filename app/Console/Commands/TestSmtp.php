<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSmtp extends Command
{
    protected $signature = 'test:smtp';
    protected $description = 'Test SMTP connection';

    public function handle()
    {
        $this->info('Testing SMTP connection...');
        $this->info('SMTP Host: ' . env('MAIL_HOST'));
        $this->info('SMTP Port: ' . env('MAIL_PORT'));
        $this->info('SMTP Encryption: ' . env('MAIL_ENCRYPTION'));
        $this->info('From: ' . config('mail.from.address'));

        try {
            Mail::raw('This is a test email from Skyeface SMTP test.', function ($message) {
                $message->to('skyefacecon@gmail.com')
                        ->subject('SMTP Test from Skyeface - ' . now());
            });

            $this->info('✓ Email sent successfully!');
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            $this->error('Class: ' . get_class($e));
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
