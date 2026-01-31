<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use App\Models\MailLog;
use Illuminate\Support\Facades\Log;

class LogSentMessage
{
    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->message;
            Log::debug('MessageSent event triggered', ['class' => get_class($message)]);

            // Subject
            $subject = method_exists($message, 'getSubject') ? $message->getSubject() : null;

            // To recipients
            $to = null;
            if (method_exists($message, 'getTo')) {
                $toArr = $message->getTo();
                if (is_array($toArr) || $toArr instanceof \Traversable) {
                    $items = [];
                    foreach ($toArr as $recipient) {
                        // Handle Symfony\Component\Mime\Address objects
                        if (is_object($recipient) && method_exists($recipient, 'getAddress')) {
                            $email = $recipient->getAddress();
                            $name = method_exists($recipient, 'getName') ? $recipient->getName() : null;
                            if ($name) {
                                $items[] = $name . ' <' . $email . '>';
                            } else {
                                $items[] = $email;
                            }
                        } elseif (is_string($recipient)) {
                            $items[] = $recipient;
                        } else {
                            $items[] = (string)$recipient;
                        }
                    }
                    $to = implode(', ', $items);
                } else {
                    // Handle single Address object
                    if (is_object($toArr) && method_exists($toArr, 'getAddress')) {
                        $to = $toArr->getAddress();
                    } else {
                        $to = (string)$toArr;
                    }
                }
            }

            // Body - try multiple methods
            $body = null;
            try {
                // For Symfony\Component\Mime\Email, use getHtmlBody() which returns a DataProvider/stream
                if (method_exists($message, 'getHtmlBody')) {
                    $htmlBody = $message->getHtmlBody();
                    // DataProvider has a toString() method or getSource()
                    if (is_object($htmlBody)) {
                        if (method_exists($htmlBody, 'toString')) {
                            $body = $htmlBody->toString();
                        } elseif (method_exists($htmlBody, '__toString')) {
                            $body = (string)$htmlBody;
                        } elseif (method_exists($htmlBody, 'getSource')) {
                            $body = $htmlBody->getSource();
                        }
                    } else {
                        $body = $htmlBody;
                    }
                }
                // Fallback to text body
                if (!$body && method_exists($message, 'getTextBody')) {
                    $textBody = $message->getTextBody();
                    if (is_object($textBody) && method_exists($textBody, 'toString')) {
                        $body = $textBody->toString();
                    } elseif (is_object($textBody) && method_exists($textBody, '__toString')) {
                        $body = (string)$textBody;
                    } else {
                        $body = $textBody;
                    }
                }
                // Ensure it's a string
                if (!is_string($body)) {
                    $body = null;
                }
            } catch (\Throwable $ex) {
                Log::debug('Error extracting body from email', ['error' => $ex->getMessage()]);
                $body = null;
            }

            // Headers
            $headers = null;
            if (method_exists($message, 'getHeaders')) {
                try {
                    $hdrs = $message->getHeaders();
                    if (is_object($hdrs) && method_exists($hdrs, 'toString')) {
                        $headers = $hdrs->toString();
                    } elseif (is_string($hdrs)) {
                        $headers = $hdrs;
                    }
                } catch (\Throwable $ex) {
                    $headers = null;
                }
            }

            MailLog::create([
                'to' => $to,
                'subject' => $subject,
                'body' => is_string($body) ? $body : null,
                'headers' => is_string($headers) ? $headers : null,
            ]);
            Log::info('Mail logged successfully', ['to' => $to, 'subject' => $subject]);
        } catch (\Throwable $e) {
            Log::error('Failed to log email to mail_logs', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }
}
