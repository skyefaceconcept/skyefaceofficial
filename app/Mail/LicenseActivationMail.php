<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LicenseActivationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $license;
    public $order;
    public $activationInstructions;

    /**
     * Create a new message instance.
     */
    public function __construct(License $license)
    {
        $this->license = $license;
        $this->order = $license->order ?? null;
        $this->activationInstructions = $this->order ? $this->getActivationInstructions() : $this->getDefaultActivationInstructions();
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        $appName = $this->license->application_name ?? 'License';
        $recipientEmail = $this->order?->customer_email ?? ($this->license->customer_email ?? 'noreply@skyeface.com');
        return new \Illuminate\Mail\Mailables\Envelope(
            to: $recipientEmail,
            subject: 'Your License Code - ' . $appName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.license-activation-simple',
            with: [
                'license' => $this->license,
                'order' => $this->order,
                'activationInstructions' => $this->activationInstructions,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }

    /**
     * Get activation instructions based on application type
     */
    private function getActivationInstructions()
    {
        if (!$this->order || !$this->order->portfolio) {
            return $this->getDefaultActivationInstructions();
        }
        $portfolio = $this->order->portfolio;
        $category = $portfolio->category;

        $instructions = [
            'web' => [
                'title' => 'Activating Your Web Application',
                'steps' => [
                    '1. Extract the downloaded files to your web server',
                    '2. Navigate to the application settings/configuration page',
                    '3. Enter your license code: <strong>' . $this->license->license_code . '</strong>',
                    '4. Click "Activate License"',
                    '5. Your application will be fully activated',
                ],
            ],
            'mobile' => [
                'title' => 'Activating Your Mobile Application',
                'steps' => [
                    '1. Install the application on your device',
                    '2. Open the app and go to Settings > License',
                    '3. Tap "Activate License"',
                    '4. Enter your license code: <strong>' . $this->license->license_code . '</strong>',
                    '5. Tap "Confirm" to activate',
                ],
            ],
            'design' => [
                'title' => 'Using Your Design Package',
                'steps' => [
                    '1. Extract the downloaded design files',
                    '2. All design assets are ready to use',
                    '3. License code for reference: <strong>' . $this->license->license_code . '</strong>',
                    '4. Review the included documentation for usage guidelines',
                    '5. Enjoy your design assets!',
                ],
            ],
        ];

        return $instructions[$category] ?? $instructions['web'];
    }

    /**
     * Get default activation instructions when order/portfolio unavailable
     */
    private function getDefaultActivationInstructions()
    {
        return [
            'title' => 'Activating Your License',
            'steps' => [
                '1. Your license code is: <strong>' . $this->license->license_code . '</strong>',
                '2. Keep this code safe and secure',
                '3. Use this code to activate your application',
                '4. Contact support if you need assistance',
                '5. Enjoy your purchase!',
            ],
        ];
    }
}
