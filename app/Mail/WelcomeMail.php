<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $plainPassword;

    /**
     * Create a new message instance.
     *
     * @param $user The user object
     * @param $plainPassword Optional plaintext password (only for new user creation; highly discouraged for security)
     */
    public function __construct($user, $plainPassword = null)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $appName = config('app.name', 'Application');
        return $this->subject("Welcome to {$appName}")
                    ->view('emails.welcome')
                    ->with([
                        'user' => $this->user,
                        'plainPassword' => $this->plainPassword,
                    ]);
    }
}
