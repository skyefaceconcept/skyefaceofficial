@extends('layouts.app-buzbox')

@section('title', 'My Orders & Payments')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fa fa-history mr-2"></i>My Orders & Payments
        </h1>
        <div class="page-nav">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="shop-orders-tab" data-toggle="tab" href="#shop-orders" role="tab" aria-controls="shop-orders" aria-selected="true">
                        <i class="fa fa-shopping-bag mr-2"></i>Shop Orders
                        <span class="badge badge-primary ml-2">{{ $orders->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments-history" role="tab" aria-controls="payments-history" aria-selected="false">
                        <i class="fa fa-credit-card mr-2"></i>Quote Payments
                        <span class="badge badge-info ml-2">{{ $payments->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Shop Orders Tab -->
        <div class="tab-pane fade show active" id="shop-orders" role="tabpanel" aria-labelledby="shop-orders-tab">
            <!-- Shop Orders Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-shopping-bag fa-2x text-primary mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Total Orders</h6>
                            <h3 class="text-primary mb-0">{{ $orders->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-check-circle fa-2x text-success mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Completed</h6>
                            <h3 class="text-success mb-0">{{ $orders->getCollection()->where('status', 'completed')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-clock-o fa-2x text-warning mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Pending</h6>
                            <h3 class="text-warning mb-0">{{ $orders->getCollection()->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-times-circle fa-2x text-danger mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Failed/Cancelled</h6>
                            <h3 class="text-danger mb-0">{{ $orders->getCollection()->whereIn('status', ['failed', 'cancelled'])->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Orders Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-list mr-2"></i>Your Shop Orders
                    </h3>
                </div>
                <div class="card-block">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr id="order-row-{{ $order->id }}">
                                            <td>
                                                <strong>#{{ $order->id }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $items = $order->cart_items ? json_decode($order->cart_items, true) : [];
                                                @endphp
                                                @if(count($items) > 0)
                                                    <small>
                                                        @foreach($items as $index => $item)
                                                            {{ $item['name'] ?? 'Product' }}{{ !$loop->last ? ', ' : '' }}
                                                            @if($index >= 1)
                                                                <br><strong>+{{ count($items) - 2 }} more</strong>
                                                                @php break; @endphp
                                                            @endif
                                                        @endforeach
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $order->currency ?? 'NGN' }} {{ number_format($order->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                @if($order->payment)
                                                    <span class="badge badge-{{ $order->payment->status === 'completed' ? 'success' : ($order->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                        @if($order->payment->status === 'completed')
                                                            <i class="fa fa-check-circle mr-1"></i>Paid
                                                        @elseif($order->payment->status === 'pending')
                                                            <i class="fa fa-clock-o mr-1"></i>Pending
                                                        @else
                                                            <i class="fa fa-times-circle mr-1"></i>Failed
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Not Paid</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                    @if($order->status === 'completed')
                                                        <i class="fa fa-check-circle mr-1"></i>Completed
                                                    @elseif($order->status === 'pending')
                                                        <i class="fa fa-clock-o mr-1"></i>Processing
                                                    @else
                                                        {{ ucfirst($order->status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-order='{{ json_encode($order) }}' onclick="showOrderDetailsFromButton(this)">
                                                    <i class="fa fa-info-circle mr-1"></i>Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No shop orders found. <a href="{{ route('shop.index') }}" class="text-primary">Start shopping</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quote Payments Tab -->
        <div class="tab-pane fade" id="payments-history" role="tabpanel" aria-labelledby="payments-tab">
            <!-- Payment Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-credit-card fa-2x text-primary mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Total Payments</h6>
                            <h3 class="text-primary mb-0">{{ $payments->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-check-circle fa-2x text-success mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Completed</h6>
                            <h3 class="text-success mb-0">{{ $payments->getCollection()->where('status', 'completed')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-clock-o fa-2x text-warning mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Pending</h6>
                            <h3 class="text-warning mb-0">{{ $payments->getCollection()->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-block p-4 text-center">
                            <i class="fa fa-times-circle fa-2x text-danger mb-3"></i>
                            <h6 class="text-muted small text-uppercase">Failed</h6>
                            <h3 class="text-danger mb-0">{{ $payments->getCollection()->where('status', 'failed')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-list mr-2"></i>Payment Transactions
                    </h3>
                </div>
                <div class="card-block">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Quote</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr id="payment-row-{{ $payment->id }}">
                                            <td>
                                                <strong>{{ $payment->reference }}</strong>
                                                <br>
                                                <small class="text-muted">{{ substr($payment->transaction_id ?? '-', 0, 20) }}...</small>
                                            </td>
                                            <td>
                                                <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
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
                                                    <a href="{{ route('quotes.show', $payment->quote->id) }}" class="text-primary">
                                                        #{{ $payment->quote->id }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $payment->paid_at ? $payment->paid_at->format('M d, Y g:i A') : $payment->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-payment='{{ json_encode($payment) }}' onclick="showPaymentDetailsFromButton(this)">
                                                    <i class="fa fa-info-circle mr-1"></i>Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No payment history found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsTitle">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeOrderModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Content loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeOrderModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="paymentDetailsTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailsTitle">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePaymentModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Content loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closePaymentModal()">Close</button>
            </div>
        </div>
    </div>
</div>

@section('additional_js')
<script>
function closeOrderModal() {
    const modal = document.getElementById('orderDetailsModal');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        document.body.classList.remove('modal-open');
    }
}

function showOrderDetailsFromButton(button) {
    try {
        const orderJson = button.getAttribute('data-order');
        if (!orderJson) {
            alert('Error: Order data not found');
            return;
        }

        let order = {};
        try {
            order = JSON.parse(orderJson);
        } catch(e) {
            console.error('Failed to parse order data:', e);
            alert('Error: Invalid order data format');
            return;
        }

        showOrderDetails(order);
    } catch (error) {
        console.error('Error in showOrderDetailsFromButton:', error);
        alert('Error: ' + error.message);
    }
}

function showOrderDetails(order) {
    try {
        // Parse cart items safely
        let cartItems = [];
        try {
            if (order.cart_items && typeof order.cart_items === 'string') {
                cartItems = JSON.parse(order.cart_items);
            } else if (Array.isArray(order.cart_items)) {
                cartItems = order.cart_items;
            }
        } catch(e) {
            console.warn('Could not parse cart items:', e);
        }

        // Build cart items HTML
        let cartItemsHtml = '';
        if (cartItems && cartItems.length > 0) {
            cartItemsHtml = '<div class="row mb-3"><div class="col-12"><p class="text-muted small text-uppercase mb-2">Items Ordered</p><table class="table table-sm table-borderless"><tbody>';
            cartItems.forEach(item => {
                cartItemsHtml += `<tr><td>${item.name || 'Product'}</td><td class="text-right">${item.quantity || 1} x ${item.currency || 'NGN'} ${(item.price || 0).toLocaleString()}</td></tr>`;
            });
            cartItemsHtml += '</tbody></table></div></div>';
        }

        const html = `
            <div class="order-details">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Order ID</p>
                        <p class="text-dark"><strong>#${order.id}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Order Status</p>
                        <p class="text-dark"><strong>${(order.status || 'N/A').toUpperCase()}</strong></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Customer Name</p>
                        <p class="text-dark"><strong>${order.customer_name || 'N/A'}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Email</p>
                        <p class="text-dark"><strong>${order.customer_email || 'N/A'}</strong></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Phone</p>
                        <p class="text-dark"><strong>${order.customer_phone || 'N/A'}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Payment Method</p>
                        <p class="text-dark"><strong>${order.payment_method || 'N/A'}</strong></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <p class="text-muted small text-uppercase mb-1">Delivery Address</p>
                        <p class="text-dark"><strong>${order.address || 'N/A'}, ${order.city || ''}, ${order.state || ''}, ${order.zip || ''}, ${order.country || ''}</strong></p>
                    </div>
                </div>
                ${cartItemsHtml}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Total Amount</p>
                        <p class="text-dark"><strong>${order.currency || 'NGN'} ${parseFloat(order.amount || 0).toLocaleString('en-NG', { minimumFractionDigits: 2 })}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Payment Processor</p>
                        <p class="text-dark"><strong>${(order.payment_processor || 'N/A').toUpperCase()}</strong></p>
                    </div>
                </div>
                <div class="row mb-3 pt-3 border-top">
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

        document.getElementById('orderDetailsContent').innerHTML = html;
        openOrderModal();
    } catch (error) {
        console.error('Error in showOrderDetails:', error);
        alert('Error: ' + error.message);
    }
}

function openOrderModal() {
    const modal = document.getElementById('orderDetailsModal');
    if (!modal) {
        alert('Error: Modal element not found');
        return;
    }

    const existingBackdrop = document.querySelector('.modal-backdrop');
    if (existingBackdrop) existingBackdrop.remove();

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const bsModal = new bootstrap.Modal(modal, { backdrop: true });
        bsModal.show();
        return;
    }

    if (typeof jQuery !== 'undefined' && jQuery(modal).modal) {
        jQuery(modal).modal('show');
        return;
    }

    modal.classList.add('show');
    modal.style.display = 'block';
    modal.setAttribute('aria-hidden', 'false');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
    document.body.classList.add('modal-open');
}

function closePaymentModal() {
    const modal = document.getElementById('paymentDetailsModal');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        document.body.classList.remove('modal-open');
    }
}

function showPaymentDetailsFromButton(button) {
    try {
        const paymentJson = button.getAttribute('data-payment');
        if (!paymentJson) {
            alert('Error: Payment data not found');
            return;
        }

        let payment = {};
        try {
            payment = JSON.parse(paymentJson);
        } catch(e) {
            console.error('Failed to parse payment data:', e);
            alert('Error: Invalid payment data format');
            return;
        }

        showPaymentDetails(payment);
    } catch (error) {
        console.error('Error in showPaymentDetailsFromButton:', error);
        alert('Error: ' + error.message);
    }
}

function showPaymentDetails(payment) {
    try {
        const html = `
            <div class="payment-details">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Reference</p>
                        <p class="text-dark"><strong>${payment.reference || 'N/A'}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Transaction ID</p>
                        <p class="text-dark"><strong>${payment.transaction_id || 'N/A'}</strong></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Amount</p>
                        <p class="text-dark"><strong>${payment.currency || ''} ${parseFloat(payment.amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Payment Source</p>
                        <p class="text-dark"><strong>${payment.payment_source || 'Flutterwave'}</strong></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small text-uppercase mb-1">Status</p>
                        <p class="text-dark"><strong>${(payment.status || 'N/A').toUpperCase()}</strong></p>
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
                <div class="row mb-3 pt-3 border-top">
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-success" onclick="downloadReceipt(${payment.id})">
                            <i class="fa fa-download mr-1"></i>Download Receipt
                        </button>
                        <button type="button" class="btn btn-sm btn-info" onclick="copyToClipboard('${payment.reference}')">
                            <i class="fa fa-copy mr-1"></i>Copy Reference
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('paymentDetailsContent').innerHTML = html;
        openPaymentModal();
    } catch (error) {
        console.error('Error in showPaymentDetails:', error);
        alert('Error: ' + error.message);
    }
}

function openPaymentModal() {
    const modal = document.getElementById('paymentDetailsModal');
    if (!modal) {
        alert('Error: Modal element not found');
        return;
    }

    const existingBackdrop = document.querySelector('.modal-backdrop');
    if (existingBackdrop) existingBackdrop.remove();

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const bsModal = new bootstrap.Modal(modal, { backdrop: true });
        bsModal.show();
        return;
    }

    if (typeof jQuery !== 'undefined' && jQuery(modal).modal) {
        jQuery(modal).modal('show');
        return;
    }

    modal.classList.add('show');
    modal.style.display = 'block';
    modal.setAttribute('aria-hidden', 'false');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
    document.body.classList.add('modal-open');
}

function downloadReceipt(paymentId) {
    const url = `/payment/${paymentId}/receipt`;
    window.open(url, '_blank', 'width=900,height=700');
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Reference copied!');
        }).catch(() => {
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        alert('Reference copied!');
    } catch (err) {
        alert('Failed to copy to clipboard');
    }
    document.body.removeChild(textarea);
}
</script>
@endsection
@endsection
