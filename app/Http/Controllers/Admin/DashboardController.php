<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Repair;
use App\Models\ContactTicket;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // User Statistics
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $activeAdmins = User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['admin', 'superadmin']);
        })->count();

        // Payment Statistics
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $totalPayments = Payment::count();

        // Quote Statistics
        $totalQuotes = Quote::count();
        $acceptedQuotes = Quote::where('status', 'accepted')->count();
        $pendingQuotes = Quote::where('status', 'pending')->count();

        // Repair Statistics
        $totalRepairs = Repair::count();
        $completedRepairs = Repair::where('status', 'completed')->count();
        $pendingRepairs = Repair::whereIn('status', ['pending', 'in_progress'])->count();

        // Contact/Ticket Statistics
        $totalTickets = ContactTicket::count();
        $openTickets = ContactTicket::where('status', 'open')->count();
        $closedTickets = ContactTicket::where('status', 'closed')->count();

        // Orders
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();

        return view('admin.dashboard.index', [
            // Users
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'activeAdmins' => $activeAdmins,
            // Payments
            'totalRevenue' => $totalRevenue,
            'pendingPayments' => $pendingPayments,
            'totalPayments' => $totalPayments,
            // Quotes
            'totalQuotes' => $totalQuotes,
            'acceptedQuotes' => $acceptedQuotes,
            'pendingQuotes' => $pendingQuotes,
            // Repairs
            'totalRepairs' => $totalRepairs,
            'completedRepairs' => $completedRepairs,
            'pendingRepairs' => $pendingRepairs,
            // Tickets
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
            // Orders
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
        ]);
    }
}
