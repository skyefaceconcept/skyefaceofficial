<?php

namespace App\Http\Controllers;

use App\Models\ContactTicket;
use App\Models\ContactMessage;
use App\Models\Quote;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Require authentication and user role (not admin)
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('admin'))) {
                return redirect()->route('admin.dashboard');
            }
            return $next($request);
        });
    }

    /**
     * Show the client dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's tickets (both from contact form submissions and account-related)
        $tickets = ContactTicket::where('user_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get user's quotes
        $quotes = Quote::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get quote statistics
        $totalQuotes = $quotes->count();
        $quotedQuotes = $quotes->where('status', Quote::STATUS_QUOTED)->count();
        $rejectedQuotes = $quotes->where('status', Quote::STATUS_REJECTED)->count();
        $acceptedQuotes = $quotes->where('status', Quote::STATUS_ACCEPTED)->count();

        // Get statistics
        $openTickets = ContactTicket::where('user_email', $user->email)
            ->where('status', 'open')
            ->count();

        $pendingReplyTickets = ContactTicket::where('user_email', $user->email)
            ->where('status', 'pending_reply')
            ->count();

        $resolvedTickets = ContactTicket::where('user_email', $user->email)
            ->where('status', 'resolved')
            ->count();

        $totalMessages = ContactMessage::whereHas('ticket', function($q) use ($user) {
            $q->where('user_email', $user->email);
        })->count();

        return view('client.dashboard.index', compact(
            'user',
            'tickets',
            'quotes',
            'totalQuotes',
            'quotedQuotes',
            'rejectedQuotes',
            'acceptedQuotes',
            'openTickets',
            'pendingReplyTickets',
            'resolvedTickets',
            'totalMessages'
        ));
    }

    /**
     * Show client's ticket details
     */
    public function showTicket($ticket_number)
    {
        $user = auth()->user();

        $ticket = ContactTicket::where('ticket_number', $ticket_number)
            ->where('user_email', $user->email)
            ->firstOrFail();

        $messages = $ticket->messages()->orderBy('created_at', 'asc')->get();

        return view('client.tickets.show', compact('ticket', 'messages'));
    }

    /**
     * Reply to a ticket
     */
    public function replyTicket(Request $request, $ticket_number)
    {
        $user = auth()->user();

        $ticket = ContactTicket::where('ticket_number', $ticket_number)
            ->where('user_email', $user->email)
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Add message to ticket
            ContactMessage::create([
                'ticket_id' => $ticket->id,
                'sender_type' => 'client',
                'sender_id' => $user->id,
                'message' => $validated['message'],
            ]);

            // Update ticket status
            $ticket->update([
                'status' => 'pending_reply',
                'last_reply_date' => now(),
            ]);

            return back()->with('success', 'Your reply has been sent. We will respond soon!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send your reply. Please try again.');
        }
    }

    /**
     * Close a ticket
     */
    public function closeTicket($ticket_number)
    {
        $user = auth()->user();

        $ticket = ContactTicket::where('ticket_number', $ticket_number)
            ->where('user_email', $user->email)
            ->firstOrFail();

        $ticket->update(['status' => 'closed']);

        return back()->with('success', 'Ticket has been closed.');
    }

    /**
     * Show quote details
     */
    public function showQuote($quote_id)
    {
        $user = auth()->user();

        $quote = Quote::where('id', $quote_id)
            ->where('email', $user->email)
            ->firstOrFail();

        return view('client.quotes.show', compact('quote'));
    }
}
