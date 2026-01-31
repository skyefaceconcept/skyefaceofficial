<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Mail\QuoteResponseEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::orderBy('created_at', 'desc')->paginate(25);
        $statuses = Quote::getStatuses();
        $stats = [
            'total' => Quote::count(),
            'new' => Quote::where('status', Quote::STATUS_NEW)->count(),
            'reviewed' => Quote::where('status', Quote::STATUS_REVIEWED)->count(),
            'quoted' => Quote::where('status', Quote::STATUS_QUOTED)->count(),
            'rejected' => Quote::where('status', Quote::STATUS_REJECTED)->count(),
            'accepted' => Quote::where('status', Quote::STATUS_ACCEPTED)->count(),
        ];
        return view('admin.quotes.index', compact('quotes', 'statuses', 'stats'));
    }

    public function show($id)
    {
        $quote = Quote::findOrFail($id);
        $statuses = Quote::getStatuses();
        return view('admin.quotes.show', compact('quote', 'statuses'));
    }

    /**
     * Update quote status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Quote::getStatuses())),
        ]);

        $quote = Quote::findOrFail($id);
        $quote->status = $validated['status'];
        $quote->save();

        return redirect()->route('admin.quotes.show', $id)
            ->with('success', 'Quote status updated to ' . Quote::getStatuses()[$validated['status']]);
    }

    /**
     * Send quote response to client
     */
    public function respond(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Quote::getStatuses())),
            'quoted_price' => 'nullable|numeric|min:0',
            'response' => 'required|string|min:10|max:5000',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->status = $validated['status'];
        $quote->quoted_price = $validated['quoted_price'] ?? null;
        $quote->response = $validated['response'];
        $quote->responded_at = now();
        $quote->notified = true;
        $quote->save();

        // Send email to client with response
        try {
            \Log::info('Attempting to send quote response email to: ' . $quote->email);
            // Send synchronously instead of queued to ensure immediate logging
            $responseMailable = new QuoteResponseEmail($quote);
            Mail::to($quote->email)->send($responseMailable);

            // Log to database manually with detailed receipt info
            \Log::info('DEBUG: About to insert quote response email log');
            try {
                $emailBody = 'Quote Status: ' . Quote::getStatuses()[$quote->status];
                
                if ($quote->status === 'quoted' && $quote->quoted_price) {
                    $emailBody .= ' | Quote Reference: QT-' . str_pad($quote->id, 5, '0', STR_PAD_LEFT);
                    $emailBody .= ' | Amount: ₦' . number_format($quote->quoted_price, 2);
                    $emailBody .= ' | Receipt Issued: ' . now()->format('M d, Y');
                }
                
                DB::table('mail_logs')->insert([
                    'to' => $quote->email,
                    'subject' => 'Re: Your Quote Request - #' . $quote->id . ' - ' . Quote::getStatuses()[$quote->status],
                    'body' => $emailBody,
                    'type' => 'quote_response',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info('SUCCESS: Quote response email logged to database with receipt details', [
                    'quote_id' => $quote->id,
                    'to' => $quote->email,
                    'status' => $quote->status,
                    'price' => $quote->quoted_price,
                ]);
            } catch (\Exception $dbEx) {
                \Log::error('ERROR logging quote response email: ' . $dbEx->getMessage(), ['trace' => $dbEx->getTraceAsString()]);
                throw $dbEx;
            }

            \Log::info('✓ Quote response email sent successfully to: ' . $quote->email, [
                'quote_id' => $quote->id,
                'status' => $quote->status,
                'amount' => $quote->quoted_price,
            ]);
        } catch (\Exception $e) {
            \Log::error('✗ Quote response email failed - Exception: ' . $e->getMessage(), [
                'quote_id' => $id,
                'recipient' => $quote->email,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't fail the response update if email fails
        }

        return redirect()->route('admin.quotes.show', $id)
            ->with('success', 'Quote response sent to ' . $quote->email);
    }

    /**
     * Add admin notes to quote
     */
    public function addNotes(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:5|max:1000',
        ]);

        $quote = Quote::findOrFail($id);
        $quote->admin_notes = $validated['admin_notes'];
        $quote->save();

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
        return redirect()->route('admin.quotes.index')->with('status', 'Quote deleted');
    }
}
