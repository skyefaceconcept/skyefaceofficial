<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by payment method if provided
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by customer name or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        $query->orderBy($sortBy, $sortDir);

        // Paginate results
        $orders = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'failed' => Order::where('status', 'failed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display a specific order
     */
    public function show(Order $order)
    {
        $cartItems = $order->cart_items ? json_decode($order->cart_items, true) : [];
        
        return view('admin.orders.show', compact('order', 'cartItems'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Delete an order
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return redirect()->route('admin.orders.index')
                       ->with('success', 'Order deleted successfully!');
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::query();

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // Header row
            fputcsv($handle, [
                'Order ID',
                'Customer Name',
                'Email',
                'Phone',
                'Amount',
                'Status',
                'Payment Method',
                'Payment Processor',
                'Address',
                'City',
                'State',
                'Country',
                'Order Date',
                'Completed Date',
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    number_format($order->amount, 2),
                    $order->status,
                    $order->payment_method,
                    $order->payment_processor,
                    $order->address,
                    $order->city,
                    $order->state,
                    $order->country,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
