<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // User Management Permissions
        Permission::create([
            'name' => 'View Users',
            'slug' => 'view_users',
            'description' => 'Can view list of users',
            'route' => 'admin.users.index',
        ]);
        Permission::create([
            'name' => 'Create User',
            'slug' => 'create_user',
            'description' => 'Can create new users',
            'route' => 'admin.users.create',
        ]);
        Permission::create([
            'name' => 'Edit User',
            'slug' => 'edit_user',
            'description' => 'Can edit user details',
            'route' => 'admin.users.edit',
        ]);
        Permission::create([
            'name' => 'Delete User',
            'slug' => 'delete_user',
            'description' => 'Can delete users',
            'route' => 'admin.users.destroy',
        ]);

        // Role Management Permissions
        Permission::create([
            'name' => 'View Roles',
            'slug' => 'view_roles',
            'description' => 'Can view list of roles',
            'route' => 'admin.roles.index',
        ]);
        Permission::create([
            'name' => 'Create Role',
            'slug' => 'create_role',
            'description' => 'Can create new roles',
            'route' => 'admin.roles.create',
        ]);
        Permission::create([
            'name' => 'Edit Role',
            'slug' => 'edit_role',
            'description' => 'Can edit roles',
            'route' => 'admin.roles.edit',
        ]);
        Permission::create([
            'name' => 'Delete Role',
            'slug' => 'delete_role',
            'description' => 'Can delete roles',
            'route' => 'admin.roles.destroy',
        ]);

        // Permission Management Permissions
        Permission::create([
            'name' => 'View Permissions',
            'slug' => 'view_permissions',
            'description' => 'Can view list of permissions',
            'route' => 'admin.permissions.index',
        ]);
        Permission::create([
            'name' => 'Create Permission',
            'slug' => 'create_permission',
            'description' => 'Can create new permissions',
            'route' => 'admin.permissions.create',
        ]);
        Permission::create([
            'name' => 'Edit Permission',
            'slug' => 'edit_permission',
            'description' => 'Can edit permissions',
            'route' => 'admin.permissions.edit',
        ]);
        Permission::create([
            'name' => 'Delete Permission',
            'slug' => 'delete_permission',
            'description' => 'Can delete permissions',
            'route' => 'admin.permissions.destroy',
        ]);

        // Dashboard Permissions
        Permission::create([
            'name' => 'View Dashboard',
            'slug' => 'view_dashboard',
            'description' => 'Can view admin dashboard',
            'route' => 'admin.dashboard',
        ]);

        // System Settings Permissions
        Permission::create([
            'name' => 'View Settings',
            'slug' => 'view_settings',
            'description' => 'Can view system settings',
            'route' => 'admin.settings.index',
        ]);
        Permission::create([
            'name' => 'Edit Settings',
            'slug' => 'edit_settings',
            'description' => 'Can edit system settings',
            'route' => 'admin.settings.index',
        ]);
        Permission::create([
            'name' => 'Backup System',
            'slug' => 'backup_system',
            'description' => 'Can backup the system',
            'route' => 'admin.settings.backup',
        ]);
        Permission::create([
            'name' => 'Restore System',
            'slug' => 'restore_system',
            'description' => 'Can restore from backup',
            'route' => 'admin.settings.backup',
        ]);

        // Company Branding Permissions
        Permission::create([
            'name' => 'View Company Branding',
            'slug' => 'view_company_branding',
            'description' => 'Can view company branding settings',
            'route' => 'admin.settings.company_branding',
        ]);
        Permission::create([
            'name' => 'Edit Company Branding',
            'slug' => 'edit_company_branding',
            'description' => 'Can edit company branding, logo, favicon, and CAC registration information',
            'route' => 'admin.settings.company_branding',
        ]);

        // Quote Management Permissions
        Permission::create([
            'name' => 'View Quotes',
            'slug' => 'view_quotes',
            'description' => 'Can view all quotes submitted by clients',
            'route' => 'admin.quotes.index',
        ]);
        Permission::create([
            'name' => 'View Quote Details',
            'slug' => 'view_quote_details',
            'description' => 'Can view detailed quote information',
            'route' => 'admin.quotes.show',
        ]);
        Permission::create([
            'name' => 'Respond to Quotes',
            'slug' => 'respond_to_quotes',
            'description' => 'Can send quote responses to clients',
            'route' => 'admin.quotes.update',
        ]);
        Permission::create([
            'name' => 'Manage Quote Status',
            'slug' => 'manage_quote_status',
            'description' => 'Can change quote status (pending, quoted, rejected, accepted)',
            'route' => 'admin.quotes.update',
        ]);
        Permission::create([
            'name' => 'Delete Quotes',
            'slug' => 'delete_quotes',
            'description' => 'Can delete quote submissions',
            'route' => 'admin.quotes.destroy',
        ]);
        Permission::create([
            'name' => 'Email Quote Responses',
            'slug' => 'email_quote_responses',
            'description' => 'Can send quote response emails to clients',
            'route' => 'admin.quotes.email',
        ]);

        // Orders Management Permissions
        Permission::create([
            'name' => 'View Orders',
            'slug' => 'view_orders',
            'description' => 'Can view all shop orders',
            'route' => 'admin.orders.index',
        ]);
        Permission::create([
            'name' => 'View Order Details',
            'slug' => 'view_order_details',
            'description' => 'Can view detailed order information',
            'route' => 'admin.orders.show',
        ]);
        Permission::create([
            'name' => 'Update Order Status',
            'slug' => 'update_order_status',
            'description' => 'Can change order status (pending, completed, failed, cancelled)',
            'route' => 'admin.orders.updateStatus',
        ]);
        Permission::create([
            'name' => 'Delete Orders',
            'slug' => 'delete_orders',
            'description' => 'Can delete shop orders',
            'route' => 'admin.orders.destroy',
        ]);
        Permission::create([
            'name' => 'Export Orders',
            'slug' => 'export_orders',
            'description' => 'Can export orders to CSV',
            'route' => 'admin.orders.export',
        ]);

        // Contact Ticket Management Permissions
        Permission::create([
            'name' => 'View Contact Tickets',
            'slug' => 'view_contact_tickets',
            'description' => 'Can view all contact support tickets',
            'route' => 'admin.contact-tickets.index',
        ]);
        Permission::create([
            'name' => 'View Ticket Details',
            'slug' => 'view_ticket_details',
            'description' => 'Can view detailed ticket information and messages',
            'route' => 'admin.contact-tickets.show',
        ]);
        Permission::create([
            'name' => 'Reply to Tickets',
            'slug' => 'reply_to_tickets',
            'description' => 'Can reply to customer support tickets',
            'route' => 'admin.contact-tickets.reply',
        ]);
        Permission::create([
            'name' => 'Manage Ticket Status',
            'slug' => 'manage_ticket_status',
            'description' => 'Can change ticket status (open, pending_reply, closed)',
            'route' => 'admin.contact-tickets.update',
        ]);
        Permission::create([
            'name' => 'Assign Tickets',
            'slug' => 'assign_tickets',
            'description' => 'Can assign tickets to support team members',
            'route' => 'admin.contact-tickets.assign',
        ]);
        Permission::create([
            'name' => 'Close Tickets',
            'slug' => 'close_tickets',
            'description' => 'Can close resolved support tickets',
            'route' => 'admin.contact-tickets.close',
        ]);

        // Client Dashboard & Profile Permissions
        Permission::create([
            'name' => 'View Client Dashboard',
            'slug' => 'view_client_dashboard',
            'description' => 'Can access client dashboard',
            'route' => 'client.dashboard',
        ]);
        Permission::create([
            'name' => 'Request Quote',
            'slug' => 'request_quote',
            'description' => 'Can submit quote requests',
            'route' => 'client.quotes.create',
        ]);
        Permission::create([
            'name' => 'View My Quotes',
            'slug' => 'view_my_quotes',
            'description' => 'Can view own submitted quotes',
            'route' => 'client.quotes.index',
        ]);
        Permission::create([
            'name' => 'Submit Support Tickets',
            'slug' => 'submit_support_tickets',
            'description' => 'Can submit support/contact tickets',
            'route' => 'client.tickets.create',
        ]);
        Permission::create([
            'name' => 'View My Support Tickets',
            'slug' => 'view_my_support_tickets',
            'description' => 'Can view own support ticket conversations',
            'route' => 'client.tickets.index',
        ]);
        Permission::create([
            'name' => 'Edit Client Profile',
            'slug' => 'edit_client_profile',
            'description' => 'Can edit own profile information',
            'route' => 'client.profile.edit',
        ]);

        // Payment & Flutterwave Permissions
        Permission::create([
            'name' => 'Manage Payment Methods',
            'slug' => 'manage_payment_methods',
            'description' => 'Can manage payment configurations',
            'route' => 'admin.payments.index',
        ]);
        Permission::create([
            'name' => 'View Payment Transactions',
            'slug' => 'view_payment_transactions',
            'description' => 'Can view all payment transactions',
            'route' => 'admin.payments.transactions',
        ]);
        Permission::create([
            'name' => 'Process Refunds',
            'slug' => 'process_refunds',
            'description' => 'Can process payment refunds',
            'route' => 'admin.payments.refunds',
        ]);

        // Reporting & Analytics Permissions
        Permission::create([
            'name' => 'View Analytics',
            'slug' => 'view_analytics',
            'description' => 'Can view system analytics and reports',
            'route' => 'admin.analytics.index',
        ]);
        Permission::create([
            'name' => 'Export Reports',
            'slug' => 'export_reports',
            'description' => 'Can export data and reports',
            'route' => 'admin.reports.export',
        ]);

        // Page Impressions Analytics Permissions
        Permission::create([
            'name' => 'View Page Impressions',
            'slug' => 'view_page_impressions',
            'description' => 'Can view page impressions analytics dashboard',
            'route' => 'admin.analytics.impressions.index',
        ]);
        Permission::create([
            'name' => 'View Impression Details',
            'slug' => 'view_impression_details',
            'description' => 'Can view detailed page impression data and metrics',
            'route' => 'admin.analytics.impressions.show',
        ]);
        Permission::create([
            'name' => 'View Impression Statistics',
            'slug' => 'view_impression_statistics',
            'description' => 'Can view impression statistics and trends',
            'route' => 'admin.analytics.impressions.stats',
        ]);
        Permission::create([
            'name' => 'Export Impressions Data',
            'slug' => 'export_impressions_data',
            'description' => 'Can export page impressions data to CSV/Excel',
            'route' => 'admin.analytics.impressions.export',
        ]);
        Permission::create([
            'name' => 'Filter Impressions by Date',
            'slug' => 'filter_impressions_date',
            'description' => 'Can filter page impressions by date range',
            'route' => 'admin.analytics.impressions.filter',
        ]);
        Permission::create([
            'name' => 'View Device Analytics',
            'slug' => 'view_device_analytics',
            'description' => 'Can view impressions breakdown by device type (desktop, mobile, tablet)',
            'route' => 'admin.analytics.impressions.devices',
        ]);
        Permission::create([
            'name' => 'View Browser Analytics',
            'slug' => 'view_browser_analytics',
            'description' => 'Can view impressions breakdown by browser',
            'route' => 'admin.analytics.impressions.browsers',
        ]);
        Permission::create([
            'name' => 'View OS Analytics',
            'slug' => 'view_os_analytics',
            'description' => 'Can view impressions breakdown by operating system',
            'route' => 'admin.analytics.impressions.os',
        ]);
        Permission::create([
            'name' => 'View Visitor Analytics',
            'slug' => 'view_visitor_analytics',
            'description' => 'Can view visitor/user analytics and tracking',
            'route' => 'admin.analytics.impressions.visitors',
        ]);
        Permission::create([
            'name' => 'Clear Impression Data',
            'slug' => 'clear_impression_data',
            'description' => 'Can clear/delete older page impression records',
            'route' => 'admin.analytics.impressions.clear',
        ]);

        // Portfolio Management Permissions
        Permission::create([
            'name' => 'View Portfolio',
            'slug' => 'view_portfolio',
            'description' => 'Can view portfolio items',
            'route' => 'admin.portfolio.index',
        ]);
        Permission::create([
            'name' => 'Create Portfolio',
            'slug' => 'create_portfolio',
            'description' => 'Can create new portfolio items',
            'route' => 'admin.portfolio.create',
        ]);
        Permission::create([
            'name' => 'Edit Portfolio',
            'slug' => 'edit_portfolio',
            'description' => 'Can edit portfolio items',
            'route' => 'admin.portfolio.edit',
        ]);
        Permission::create([
            'name' => 'Delete Portfolio',
            'slug' => 'delete_portfolio',
            'description' => 'Can delete portfolio items',
            'route' => 'admin.portfolio.destroy',
        ]);
        Permission::create([
            'name' => 'Manage Portfolio Footage',
            'slug' => 'manage_portfolio_footage',
            'description' => 'Can add and delete portfolio footage/media',
            'route' => 'admin.portfolio.footage.add',
        ]);
        Permission::create([
            'name' => 'Reorder Portfolio Footage',
            'slug' => 'reorder_portfolio_footage',
            'description' => 'Can reorder portfolio footage items',
            'route' => 'admin.portfolio.reorder',
        ]);

        // Device Repair Management Permissions
        Permission::create([
            'name' => 'View Repairs',
            'slug' => 'view_repairs',
            'description' => 'Can view all device repair requests',
            'route' => 'admin.repairs.index',
        ]);
        Permission::create([
            'name' => 'View Repair Details',
            'slug' => 'view_repair_details',
            'description' => 'Can view detailed repair information',
            'route' => 'admin.repairs.show',
        ]);
        Permission::create([
            'name' => 'Update Repair Status',
            'slug' => 'update_repair_status',
            'description' => 'Can update repair status',
            'route' => 'admin.repairs.updateStatus',
        ]);
        Permission::create([
            'name' => 'Update Repair Cost',
            'slug' => 'update_repair_cost',
            'description' => 'Can update repair cost/pricing',
            'route' => 'admin.repairs.updateCost',
        ]);
        Permission::create([
            'name' => 'Add Repair Notes',
            'slug' => 'add_repair_notes',
            'description' => 'Can add notes to repair requests',
            'route' => 'admin.repairs.addNotes',
        ]);
        Permission::create([
            'name' => 'Manage Repair Pricing',
            'slug' => 'manage_repair_pricing',
            'description' => 'Can view and update device repair pricing',
            'route' => 'admin.repairs.pricing.index',
        ]);

        // Payment Management Permissions
        Permission::create([
            'name' => 'View Payments',
            'slug' => 'view_payments',
            'description' => 'Can view all system payments',
            'route' => 'admin.payments.index',
        ]);
        Permission::create([
            'name' => 'Refresh Payment',
            'slug' => 'refresh_payment',
            'description' => 'Can refresh payment status from payment gateway',
            'route' => 'admin.payments.refresh',
        ]);
        Permission::create([
            'name' => 'Update Payment Status',
            'slug' => 'update_payment_status',
            'description' => 'Can manually update payment status',
            'route' => 'admin.payments.updateStatus',
        ]);
    }

    public function down(): void
    {
        // Delete all permissions if rolling back
        Permission::whereIn('slug', [
            // User Management
            'view_users', 'create_user', 'edit_user', 'delete_user',
            // Role Management
            'view_roles', 'create_role', 'edit_role', 'delete_role',
            // Permission Management
            'view_permissions', 'create_permission', 'edit_permission', 'delete_permission',
            // Dashboard
            'view_dashboard',
            // System Settings
            'view_settings', 'edit_settings', 'backup_system', 'restore_system',
            // Company Branding
            'view_company_branding', 'edit_company_branding',
            // Quote Management
            'view_quotes', 'view_quote_details', 'respond_to_quotes', 'manage_quote_status', 'delete_quotes', 'email_quote_responses',
            // Orders Management
            'view_orders', 'view_order_details', 'update_order_status', 'delete_orders', 'export_orders',
            // Contact Ticket Management
            'view_contact_tickets', 'view_ticket_details', 'reply_to_tickets', 'manage_ticket_status', 'assign_tickets', 'close_tickets',
            // Client Dashboard & Profile
            'view_client_dashboard', 'request_quote', 'view_my_quotes', 'submit_support_tickets', 'view_my_support_tickets', 'edit_client_profile',
            // Payment & Flutterwave
            'manage_payment_methods', 'view_payment_transactions', 'process_refunds',
            // Reporting & Analytics
            'view_analytics', 'export_reports',
            // Page Impressions Analytics
            'view_page_impressions', 'view_impression_details', 'view_impression_statistics', 'export_impressions_data', 'filter_impressions_date', 'view_device_analytics', 'view_browser_analytics', 'view_os_analytics', 'view_visitor_analytics', 'clear_impression_data',
            // Portfolio Management
            'view_portfolio', 'create_portfolio', 'edit_portfolio', 'delete_portfolio', 'manage_portfolio_footage', 'reorder_portfolio_footage',
            // Device Repair Management
            'view_repairs', 'view_repair_details', 'update_repair_status', 'update_repair_cost', 'add_repair_notes', 'manage_repair_pricing',
            // Payment Management
            'view_payments', 'refresh_payment', 'update_payment_status',
        ])->delete();
    }
};
