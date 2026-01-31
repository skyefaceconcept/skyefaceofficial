<?php

namespace App\Http\Controllers\Admin;

use App\Models\License;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class LicenseController extends Controller
{
    /**
     * Display all licenses
     */
    public function index(Request $request)
    {
        $query = License::with('order', 'order.user');

        // Filter by status
        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }

        // Filter by expiry status
        if ($request->has('expiry_status') && $request->input('expiry_status') !== '') {
            $expiryStatus = $request->input('expiry_status');
            if ($expiryStatus === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($expiryStatus === 'expiring_soon') {
                // Expiring within 7 days
                $query->whereBetween('expiry_date', [now(), now()->addDays(7)]);
            } elseif ($expiryStatus === 'active') {
                $query->where('expiry_date', '>', now());
            }
        }

        // Search by license code or customer email
        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('license_code', 'like', "%{$search}%")
                  ->orWhere('application_name', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($q) use ($search) {
                      $q->where('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        if (in_array($sortBy, ['created_at', 'expiry_date', 'status'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $licenses = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => License::count(),
            'active' => License::where('status', License::STATUS_ACTIVE)->count(),
            'inactive' => License::where('status', License::STATUS_INACTIVE)->count(),
            'expired' => License::where('status', License::STATUS_EXPIRED)->count(),
            'revoked' => License::where('status', License::STATUS_REVOKED)->count(),
            'expiring_soon' => License::whereBetween('expiry_date', [now(), now()->addDays(7)])->count(),
        ];

        return view('admin.licenses.index', compact('licenses', 'stats'));
    }

    /**
     * Display a specific license
     */
    public function show(License $license)
    {
        $license->load('order', 'order.user');
        
        // Calculate days remaining
        $daysRemaining = $license->expiry_date ? $license->expiry_date->diffInDays(now()) : null;
        
        return view('admin.licenses.show', compact('license', 'daysRemaining'));
    }

    /**
     * Revoke a license
     */
    public function revoke(Request $request, License $license)
    {
        try {
            $license->update([
                'status' => License::STATUS_REVOKED,
            ]);

            Log::info('License revoked by admin', [
                'license_id' => $license->id,
                'admin_id' => auth()->id(),
                'reason' => $request->input('reason'),
            ]);

            return back()->with('success', 'License revoked successfully!');
        } catch (\Exception $e) {
            Log::error('Error revoking license', [
                'license_id' => $license->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to revoke license: ' . $e->getMessage());
        }
    }

    /**
     * Reactivate a revoked or expired license
     */
    public function reactivate(Request $request, License $license)
    {
        try {
            $validated = $request->validate([
                'extend_days' => 'nullable|integer|min:0|max:365',
            ]);

            $newExpiryDate = $license->expiry_date;
            
            // If license is expired or extend requested, extend the expiry
            if ($validated['extend_days'] ?? 0 > 0) {
                $newExpiryDate = now()->addDays($validated['extend_days']);
            } elseif ($license->expiry_date->isPast()) {
                // If expired, extend by original duration (30 days)
                $newExpiryDate = now()->addDays(30);
            }

            $license->update([
                'status' => License::STATUS_ACTIVE,
                'expiry_date' => $newExpiryDate,
            ]);

            Log::info('License reactivated by admin', [
                'license_id' => $license->id,
                'admin_id' => auth()->id(),
                'new_expiry' => $newExpiryDate,
                'extended_days' => $validated['extend_days'] ?? 0,
            ]);

            return back()->with('success', 'License reactivated successfully!');
        } catch (\Exception $e) {
            Log::error('Error reactivating license', [
                'license_id' => $license->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to reactivate license: ' . $e->getMessage());
        }
    }

    /**
     * Export licenses to CSV
     */
    public function export(Request $request)
    {
        $query = License::with('order');

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $licenses = $query->get();

        $filename = 'licenses_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->stream(function () use ($licenses) {
            $handle = fopen('php://output', 'w');
            
            // Header row
            fputcsv($handle, [
                'License ID',
                'License Code',
                'Product/Application',
                'Customer Name',
                'Customer Email',
                'Status',
                'Created Date',
                'Expiry Date',
                'Days Remaining',
                'Activation Count',
                'Last Activated',
            ]);

            // Data rows
            foreach ($licenses as $license) {
                $daysRemaining = $license->expiry_date ? $license->expiry_date->diffInDays(now()) : 'N/A';
                
                fputcsv($handle, [
                    $license->id,
                    $license->license_code,
                    $license->application_name,
                    $license->order->customer_name ?? 'N/A',
                    $license->order->customer_email ?? 'N/A',
                    $license->status,
                    $license->created_at->format('Y-m-d H:i'),
                    $license->expiry_date->format('Y-m-d'),
                    $daysRemaining,
                    $license->activation_count,
                    $license->last_activated_at ? $license->last_activated_at->format('Y-m-d H:i') : 'Never',
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get license data as JSON (for AJAX requests)
     */
    public function getData(License $license)
    {
        $license->load('order');
        
        return response()->json([
            'id' => $license->id,
            'code' => $license->license_code,
            'product' => $license->application_name,
            'status' => $license->status,
            'created_at' => $license->created_at->format('Y-m-d H:i'),
            'expiry_date' => $license->expiry_date->format('Y-m-d'),
            'days_remaining' => $license->expiry_date ? $license->expiry_date->diffInDays(now()) : 0,
            'activation_count' => $license->activation_count,
            'last_activated' => $license->last_activated_at ? $license->last_activated_at->format('Y-m-d H:i') : 'Never',
            'customer' => $license->order ? [
                'name' => $license->order->customer_name,
                'email' => $license->order->customer_email,
                'phone' => $license->order->customer_phone,
            ] : null,
        ]);
    }
}
