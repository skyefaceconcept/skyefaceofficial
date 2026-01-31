<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactTicket;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;
use App\Mail\TicketClosedMail;
use App\Events\ContactMessageCreated;

class ContactTicketController extends Controller
{
    /**
     * Display all contact tickets
     */
    public function index()
    {
        $tickets = ContactTicket::with('messages')
                                ->orderBy('last_reply_date', 'desc')
                                ->paginate(15);

        return view('admin.contact-tickets.index', compact('tickets'));
    }

    /**
     * Display a single ticket with all messages
     */
    public function show(ContactTicket $contactTicket)
    {
        $contactTicket->load('messages', 'assignedAdmin');

        return view('admin.contact-tickets.show', [
            'ticket' => $contactTicket,
            // show messages in chronological order so the latest appears last
            'messages' => $contactTicket->messages()->orderBy('id', 'asc')->get(),
        ]);
    }

    /**
     * Return messages for a ticket as JSON (for AJAX fetching)
     */
    public function messages(ContactTicket $contactTicket)
    {
        $messages = $contactTicket->messages()->orderBy('id', 'asc')->get()->map(function ($m) {
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

    /**
     * Mark ticket as assigned to current admin
     */
    public function assign(ContactTicket $contactTicket)
    {
        $contactTicket->update([
            'assigned_to' => auth()->id(),
        ]);

        return back()->with('success', 'Ticket assigned to you successfully.');
    }

    /**
     * Store a reply message to a ticket
     */
    public function reply(Request $request, ContactTicket $contactTicket)
    {
        \Log::info('Reply endpoint called', [
            'ticket_id' => $contactTicket->id,
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if ($contactTicket->status === 'closed') {
            \Log::warning('Attempt to reply to closed ticket', ['ticket_id' => $contactTicket->id]);
            return response()->json([
                'success' => false,
                'message' => 'Cannot reply to a closed ticket.',
            ], 403);
        }

        try {
            $message = ContactMessage::create([
                'ticket_id' => $contactTicket->id,
                'sender_type' => 'admin',
                'sender_id' => auth()->id(),
                'message' => $validated['message'],
            ]);

            \Log::info('Message created successfully', [
                'message_id' => $message->id,
                'ticket_id' => $contactTicket->id,
            ]);

            // Update ticket status to pending_reply (waiting for client response)
            $contactTicket->update([
                'status' => 'pending_reply',
                'last_reply_date' => now(),
            ]);

            // Attempt to email the client a copy of the reply
            try {
                if (!empty($contactTicket->user_email)) {
                    Mail::to($contactTicket->user_email)->send(new ContactReplyMail($contactTicket, $message));
                    \Log::info('Contact reply email sent', ['ticket_id' => $contactTicket->id, 'email' => $contactTicket->user_email]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send contact reply email', ['ticket_id' => $contactTicket->id, 'error' => $e->getMessage()]);
            }

            // Broadcast the new message to real-time listeners
            try {
                event(new ContactMessageCreated($message));
            } catch (\Exception $e) {
                \Log::error('Broadcast error for contact message', ['ticket_id' => $contactTicket->id, 'error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'data' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toDateTimeString(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create message', [
                'ticket_id' => $contactTicket->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change ticket status
     */
    public function updateStatus(Request $request, ContactTicket $contactTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending_reply,closed',
        ]);

        $contactTicket->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Close a ticket
     */
    public function close(ContactTicket $contactTicket)
    {
        $contactTicket->update([
            'status' => 'closed',
        ]);

        // Send ticket closed email to client
        try {
            if (!empty($contactTicket->user_email)) {
                Mail::to($contactTicket->user_email)->send(new TicketClosedMail($contactTicket));
                \Log::info('Ticket closed email sent', ['ticket_id' => $contactTicket->id, 'email' => $contactTicket->user_email]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket closed email', ['ticket_id' => $contactTicket->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'Ticket closed successfully.');
    }

    /**
     * Get stats for admin dashboard
     */
    public function stats()
    {
        return [
            'total_open' => ContactTicket::where('status', 'open')->count(),
            'total_pending' => ContactTicket::where('status', 'pending_reply')->count(),
            'total_closed' => ContactTicket::where('status', 'closed')->count(),
            'assigned_to_me' => ContactTicket::where('assigned_to', auth()->id())
                                            ->whereIn('status', ['open', 'pending_reply'])
                                            ->count(),
        ];
    }
}
