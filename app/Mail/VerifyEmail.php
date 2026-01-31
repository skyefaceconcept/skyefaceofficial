<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyEmail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;
    public $expiresAtIso;
    public $countdownUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        // Generate the verification URL
        $expiresAt = now()->addMinutes(60);
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            $expiresAt,
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );

        // Expires timestamp for display and countdown link
        $this->expiresAtIso = $expiresAt->toIso8601String();
        $this->countdownUrl = url('/email/verify/countdown') . '?expires=' . urlencode($this->expiresAtIso) . '&url=' . urlencode($this->verificationUrl);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify Your Email Address')
                    ->view('emails.verify')
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                        'expiresAtIso' => $this->expiresAtIso,
                        'countdownUrl' => $this->countdownUrl,
                    ]);
    }
}
