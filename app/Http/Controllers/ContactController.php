<?php

namespace App\Http\Controllers;

use App\Models\ContactTicket;
use App\Models\ContactMessage;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactFormNotifyAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a new contact message (create ticket if doesn't exist)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Check if there's an open ticket for this email with the same subject
            $ticket = ContactTicket::where('user_email', $validated['email'])
                                    ->where('subject', $validated['subject'])
                                    ->whereIn('status', ['open', 'pending_reply'])
                                    ->first();

            // If no existing open ticket, create a new one
            if (!$ticket) {
                $ticket = ContactTicket::create([
                    'ticket_number' => $this->generateTicketNumber(),
                    'user_email' => $validated['email'],
                    'user_name' => $validated['name'],
                    'phone' => $validated['phone'] ?? null,
                    'subject' => $validated['subject'],
                    'status' => 'open',
                    'last_reply_date' => now(),
                ]);
            } else {
                // Update last reply date to track inactivity
                $ticket->update(['last_reply_date' => now()]);
            }

            // Add the message to the ticket
            ContactMessage::create([
                'ticket_id' => $ticket->id,
                'sender_type' => 'client',
                'sender_id' => null,
                'message' => $validated['message'],
            ]);

            // Send confirmation email to user
            try {
                Mail::to($validated['email'])->send(new ContactFormSubmitted($ticket));
            } catch (\Exception $mailException) {
                \Log::error('Failed to send confirmation email: ' . $mailException->getMessage());
            }

            // Send notification email to admin
            try {
                $adminEmail = config('company.email', config('mail.from.address'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new ContactFormNotifyAdmin($ticket));
                }
            } catch (\Exception $mailException) {
                \Log::error('Failed to send admin notification email: ' . $mailException->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
                'ticket_number' => $ticket->ticket_number,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate unique ticket number
     */
    private function generateTicketNumber()
    {
        $prefix = 'TKT';
        $count = ContactTicket::count() + 1;
        return $prefix . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    /**
     * View ticket by ticket number (public client view)
     */
    public function viewTicket($ticket_number)
    {
        $ticket = ContactTicket::where('ticket_number', $ticket_number)->first();

        if (!$ticket) {
            return view('client.tickets.show', ['ticket' => null, 'error' => 'Ticket not found.']);
        }

        $messages = $ticket->messages()->orderBy('id')->get();

        return view('client.tickets.show', compact('ticket', 'messages'));
    }

        /**
         * Store a client reply to a ticket
         */
        public function clientReply(Request $request, $ticket_number)
        {
            $ticket = ContactTicket::where('ticket_number', $ticket_number)->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found.',
                ], 404);
            }

            // Verify email matches (security check)
            $email = $request->input('email');
            if ($ticket->user_email !== $email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email does not match ticket.',
                ], 403);
            }

            $validated = $request->validate([
                'message' => 'required|string|max:5000',
                'email' => 'required|email',
            ]);

            if ($ticket->status === 'closed') {
                return response()->json([
                    'success' => false,
                    'message' => 'This ticket is closed. No further replies can be sent.',
                ], 403);
            }

            try {
                ContactMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_type' => 'client',
                    'sender_id' => null,
                    'message' => $validated['message'],
                ]);

                // Update ticket status back to open (client replied)
                $ticket->update([
                    'status' => 'open',
                    'last_reply_date' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Your reply has been sent successfully!',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send reply.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        /**
         * Fetch messages for a ticket (public client access - no auth required)
         */
        public function getMessages($ticket_number)
        {
            $ticket = ContactTicket::where('ticket_number', $ticket_number)->first();

            if (!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found.',
                ], 404);
            }

            $messages = $ticket->messages()->orderBy('id', 'asc')->get()->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('M d, Y H:i'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $messages,
            ]);
        }
}
