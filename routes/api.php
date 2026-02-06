<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment API endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/payments/{payment}', [PaymentController::class, 'getPayment']);
});

// Page Impression Time Tracking (no auth required - sent on page unload)
Route::post('/impression-time', function (Request $request) {
    try {
        $pageUrl = $request->input('page_url');
        $timeSpent = (float) $request->input('time_spent_seconds', 0);

        if ($pageUrl && $timeSpent > 0) {
            // Update the most recent impression for this URL to add time spent
            \App\Models\PageImpression::where('page_url', $pageUrl)
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->latest('id')
                ->first()
                ?->update([
                    'time_spent_seconds' => \DB::raw('time_spent_seconds + ' . $timeSpent)
                ]);
        }

        return response()->json(['status' => 'ok']);
    } catch (\Exception $e) {
        \Log::warning('Failed to track impression time: ' . $e->getMessage());
        return response()->json(['status' => 'error'], 500);
    }
});
