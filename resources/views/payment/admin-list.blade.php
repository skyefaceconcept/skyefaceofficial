@extends('layouts.admin.app')

@section('content')
<style>
.badge-outline-primary {
    background-color: #e7f3ff;
    color: #0051ba;
    border: 1px solid #0051ba;
    padding: 0.35rem 0.65rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

table th {
    font-weight: 600;
    font-size: 0.85rem;
}
</style>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fa fa-credit-card mr-2"></i>Payments Management</h1>
        </div>
        <div class="text-right">
            <span class="badge badge-pill badge-info mr-2" title="Total Payments">Total: {{ $stats['total_payments'] }}</span>
            <span class="badge badge-pill badge-success mr-2" title="Completed">Completed: {{ $stats['completed_count'] }}</span>
            <span class="badge badge-pill badge-warning mr-2" title="Pending">Pending: {{ $stats['pending_payments'] }}</span>
            <span class="badge badge-pill badge-danger mr-2" title="Failed">Failed: {{ $stats['failed_payments'] }}</span>
            <span class="badge badge-pill badge-secondary" title="Cancelled">Cancelled: {{ $stats['cancelled_payments'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                    <h3 class="text-success"><i class="fa fa-money mr-2"></i>₦{{ number_format($stats['total_revenue'], 2) }}</h3>
                    <small class="text-muted">{{ $stats['completed_count'] }} completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">By Payment Type</h6>
                    <small class="text-primary"><strong>Quotes:</strong> {{ $stats['quote_payments'] }}</small><br>
                    <small class="text-info"><strong>Repairs:</strong> {{ $stats['repair_payments'] }}</small><br>
                    <small class="text-success"><strong>Shop Orders:</strong> {{ $stats['order_payments'] ?? 0 }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">By Processor</h6>
                    <small class="text-secondary"><strong>Flutterwave:</strong> {{ $stats['flutterwave_payments'] }}</small><br>
                    <small class="text-secondary"><strong>Paystack:</strong> {{ $stats['paystack_payments'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">By Status</h6>
                    <small class="text-success"><strong>Completed:</strong> {{ $stats['completed_count'] }}</small><br>
                    <small class="text-warning"><strong>Pending:</strong> {{ $stats['pending_payments'] }}</small><br>
                    <small class="text-danger"><strong>Failed:</strong> {{ $stats['failed_payments'] }}</small>
                </div>
            </div>
        </div>
    </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-filter mr-2"></i>Filter Payments</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="form-row">
                    <div class="form-group col-md-2">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Reference, Email, Name" value="{{ request('search') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="source">Payment Source</label>
                        <select class="form-control" id="source" name="source">
                            <option value="">All Sources</option>
                            <option value="flutterwave" {{ request('source') === 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                            <option value="paystack" {{ request('source') === 'paystack' ? 'selected' : '' }}>Paystack</option>
                            <option value="quote" {{ request('source') === 'quote' ? 'selected' : '' }}>Quotes</option>
                            <option value="repair" {{ request('source') === 'repair' ? 'selected' : '' }}>Repairs</option>
                            <option value="order" {{ request('source') === 'order' ? 'selected' : '' }}>Shop Orders</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="from_date">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="to_date">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search mr-1"></i>Search
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh mr-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

    <!-- Payments Table -->
    @if($payments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-list mr-2"></i>All Payments</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Processor</th>
                            <th>Status</th>
                            <th>Related To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr id="payment-row-{{ $payment->id }}">
                                <td>
                                    <strong>{{ $payment->reference }}</strong>
                                    <br>
                                    <small class="text-muted">{{ substr($payment->transaction_id ?? 'N/A', 0, 20) }}...</small>
                                </td>
                                <td>
                                    <strong>
                                        <a href="{{ route('admin.payments.index', ['search' => $payment->customer_email]) }}" class="text-dark" title="View customer payment history">
                                            {{ $payment->customer_name }}
                                        </a>
                                    </strong>
                                    <br>
                                    <small class="text-muted"><a href="mailto:{{ $payment->customer_email }}">{{ $payment->customer_email }}</a></small>
                                </td>
                                <td>
                                    <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($payment->quote && !$payment->repair && !$payment->order)
                                        <span class="badge badge-primary">Quote Payment</span>
                                    @elseif($payment->repair && !$payment->quote && !$payment->order)
                                        <span class="badge badge-info">Repair Payment</span>
                                    @elseif($payment->order && !$payment->quote && !$payment->repair)
                                        <span class="badge badge-success">Shop Order</span>
                                    @else
                                        <span class="badge badge-secondary">Direct Payment</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-outline-primary" title="{{ $payment->payment_source }}">
                                        @if(strpos(strtolower($payment->payment_source ?? ''), 'flutterwave') !== false)
                                            <i class="fa fa-cc-stripe mr-1"></i>Flutterwave
                                        @elseif(strpos(strtolower($payment->payment_source ?? ''), 'paystack') !== false)
                                            <i class="fa fa-credit-card mr-1"></i>Paystack
                                        @else
                                            {{ $payment->payment_source ?? 'Unknown' }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="payment-status badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'failed' ? 'danger' : 'secondary')) }}">
                                        @if($payment->status === 'completed')
                                            <i class="fa fa-check-circle mr-1"></i>Completed
                                        @elseif($payment->status === 'pending')
                                            <i class="fa fa-clock-o mr-1"></i>Pending
                                        @elseif($payment->status === 'failed')
                                            <i class="fa fa-times-circle mr-1"></i>Failed
                                        @else
                                            {{ ucfirst($payment->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($payment->quote)
                                        <a href="{{ route('admin.quotes.show', $payment->quote->id) }}" class="text-primary" title="View Quote">
                                            <i class="fa fa-file-text mr-1"></i>Quote #{{ $payment->quote->id }}
                                        </a>
                                    @elseif($payment->repair)
                                        <a href="{{ route('admin.repairs.show', $payment->repair->id) }}" class="text-primary" title="View Repair">
                                            <i class="fa fa-wrench mr-1"></i>{{ $payment->repair->invoice_number }}
                                        </a>
                                    @elseif($payment->order)
                                        <a href="javascript:void(0)" class="text-primary" title="View Order" onclick="showOrderDetails({{ json_encode($payment->order) }})">
                                            <i class="fa fa-shopping-cart mr-1"></i>Order #{{ $payment->order->id }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($payment->paid_at)
                                            {{ $payment->paid_at->format('M d, Y g:i A') }}
                                        @else
                                            {{ $payment->created_at->format('M d, Y') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <h6 class="dropdown-header">Status Actions</h6>
                                            <button type="button" class="dropdown-item" onclick="updatePaymentStatus({{ $payment->id }}, 'completed')">
                                                <i class="fa fa-check-circle mr-2 text-success"></i>Mark Completed
                                            </button>
                                            <button type="button" class="dropdown-item" onclick="updatePaymentStatus({{ $payment->id }}, 'pending')">
                                                <i class="fa fa-clock-o mr-2 text-warning"></i>Mark Pending
                                            </button>
                                            <button type="button" class="dropdown-item" onclick="updatePaymentStatus({{ $payment->id }}, 'failed')">
                                                <i class="fa fa-times-circle mr-2 text-danger"></i>Mark Failed
                                            </button>
                                            <button type="button" class="dropdown-item" onclick="updatePaymentStatus({{ $payment->id }}, 'cancelled')">
                                                <i class="fa fa-ban mr-2 text-secondary"></i>Mark Cancelled
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <h6 class="dropdown-header">Other Actions</h6>
                                            <button type="button" class="dropdown-item" onclick="refreshPaymentStatus({{ $payment->id }}, '{{ $payment->payment_source ?? 'Flutterwave' }}')">
                                                <i class="fa fa-refresh mr-2"></i>Refresh Status
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('admin.payments.index', ['search' => $payment->customer_email]) }}">
                                                <i class="fa fa-history mr-2"></i>Customer History
                                            </a>
                                            @if($payment->quote)
                                                <a class="dropdown-item" href="{{ route('admin.quotes.show', $payment->quote_id) }}">
                                                    <i class="fa fa-file-text mr-2"></i>View Quote
                                                </a>
                                            @endif
                                            @if($payment->repair)
                                                <a class="dropdown-item" href="{{ route('admin.repairs.show', $payment->repair->id) }}">
                                                    <i class="fa fa-wrench mr-2"></i>View Repair
                                                </a>
                                            @endif
                                            @if($payment->order)
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="showOrderDetails({{ json_encode($payment->order) }})">
                                                    <i class="fa fa-shopping-cart mr-2"></i>View Order
                                                </a>
                                            @endif
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="showPaymentDetails({{ json_encode($payment) }})">
                                                <i class="fa fa-info-circle mr-2"></i>Payment Details
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fa fa-info-circle mr-2"></i><strong>No payments found.</strong> Payment transactions will appear here once customers make payments.
        </div>
    @endif
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Content loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Base URL for payment refresh endpoint
const paymentRefreshBaseUrl = '{{ route("admin.payments.refresh", ["payment" => 0]) }}'.replace('/0', '');
const paymentUpdateBaseUrl = '{{ route("admin.payments.updateStatus", ["payment" => 0]) }}'.replace('/0', '');

function updatePaymentStatus(paymentId, newStatus) {
    const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    
    if (!confirm(`Update payment status to ${statusText}?`)) {
        return;
    }

    const btn = event.target.closest('.dropdown-item');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Updating...';
    btn.disabled = true;

    const url = `${paymentUpdateBaseUrl}/${paymentId}/update-status`;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;

        if (data.success) {
            alert('Payment status updated successfully');
            location.reload(); // Reload to get fresh data
        } else {
            alert('Error: ' + (data.error || 'Could not update payment status'));
        }
    })
    .catch(error => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        alert('Error: ' + error.message);
    });
}

function refreshPaymentStatus(paymentId, processor) {
    if (!confirm(`Refresh payment status with ${processor}?`)) {
        return;
    }

    const btn = event.target.closest('.dropdown-item');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Refreshing...';
    btn.disabled = true;

    const url = `${paymentRefreshBaseUrl}/${paymentId}/refresh`;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;

        if (data.success) {
            alert('Payment status updated: ' + data.message);

            // Update the status badge
            const statusBadge = document.querySelector(`#payment-row-${paymentId} .payment-status`);
            if (statusBadge) {
                location.reload(); // Reload to get fresh data
            }
        } else {
            alert('Error: ' + (data.error || 'Could not update payment status'));
        }
    })
    .catch(error => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        alert('Error: ' + error.message);
    });
}

function showPaymentDetails(payment) {
    const html = `
        <div class="payment-details">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Reference</p>
                    <p class="text-dark"><strong>${payment.reference}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Transaction ID</p>
                    <p class="text-dark"><strong>${payment.transaction_id || 'N/A'}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Customer Name</p>
                    <p class="text-dark"><strong>${payment.customer_name}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Customer Email</p>
                    <p class="text-dark"><strong>${payment.customer_email}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Amount</p>
                    <p class="text-dark"><strong>${payment.currency} ${parseFloat(payment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Payment Source</p>
                    <p class="text-dark"><strong>${payment.payment_source || 'Flutterwave'}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Status</p>
                    <p class="text-dark"><strong>${payment.status.toUpperCase()}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Payment Method</p>
                    <p class="text-dark"><strong>${payment.payment_method || 'N/A'}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Created At</p>
                    <p class="text-dark"><strong>${new Date(payment.created_at).toLocaleString()}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Paid At</p>
                    <p class="text-dark"><strong>${payment.paid_at ? new Date(payment.paid_at).toLocaleString() : 'N/A'}</strong></p>
                </div>
            </div>
        </div>
    `;

    document.getElementById('paymentDetailsContent').innerHTML = html;
    const modal = new (window.bootstrap?.Modal || jQuery.fn.modal)(document.getElementById('paymentDetailsModal'));
    if (window.bootstrap?.Modal) {
        modal.show();
    } else if (jQuery && jQuery.fn.modal) {
        jQuery('#paymentDetailsModal').modal('show');
    }
}

function showOrderDetails(order) {
    // Decode cart items if present
    let cartItemsHtml = '';
    if (order.cart_items) {
        try {
            const cartItems = typeof order.cart_items === 'string' ? JSON.parse(order.cart_items) : order.cart_items;
            if (Array.isArray(cartItems) && cartItems.length > 0) {
                cartItemsHtml = '<div class="mb-3"><p class="text-muted small text-uppercase mb-2">Cart Items:</p>';
                cartItems.forEach(item => {
                    cartItemsHtml += `
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                            <span><strong>${item.name}</strong><br><small class="text-muted">License: ${item.licenseDuration}</small></span>
                            <span>₦${parseFloat(item.totalPrice).toLocaleString('en-NG', { minimumFractionDigits: 2 })}</span>
                        </div>
                    `;
                });
                cartItemsHtml += '</div>';
            }
        } catch (e) {
            console.error('Error parsing cart items:', e);
        }
    }

    const html = `
        <div class="order-details">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Order #</p>
                    <p class="text-dark"><strong>${order.id}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Status</p>
                    <p class="text-dark">
                        <span class="badge badge-${order.status === 'completed' ? 'success' : (order.status === 'pending' ? 'warning' : 'danger')}">
                            ${order.status.toUpperCase()}
                        </span>
                    </p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Customer Name</p>
                    <p class="text-dark"><strong>${order.customer_name}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Email</p>
                    <p class="text-dark"><strong>${order.customer_email}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Phone</p>
                    <p class="text-dark"><strong>${order.customer_phone}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Payment Processor</p>
                    <p class="text-dark"><strong>${order.payment_processor ? order.payment_processor.toUpperCase() : 'N/A'}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-muted small text-uppercase mb-1">Delivery Address</p>
                    <p class="text-dark"><strong>${order.address}, ${order.city}, ${order.state}, ${order.zip}, ${order.country}</strong></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Amount</p>
                    <p class="text-dark"><strong>${order.currency} ${parseFloat(order.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Payment Method</p>
                    <p class="text-dark"><strong>${order.payment_method || 'N/A'}</strong></p>
                </div>
            </div>
            ${cartItemsHtml}
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Order Date</p>
                    <p class="text-dark"><strong>${new Date(order.created_at).toLocaleString()}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase mb-1">Completed At</p>
                    <p class="text-dark"><strong>${order.completed_at ? new Date(order.completed_at).toLocaleString() : 'N/A'}</strong></p>
                </div>
            </div>
        </div>
    `;

    document.getElementById('paymentDetailsContent').innerHTML = html;
    const modal = new (window.bootstrap?.Modal || jQuery.fn.modal)(document.getElementById('paymentDetailsModal'));
    if (window.bootstrap?.Modal) {
        modal.show();
    } else if (jQuery && jQuery.fn.modal) {
        jQuery('#paymentDetailsModal').modal('show');
    }
}
</script>
@endpush
@endsection
