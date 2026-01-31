@php
    use App\Services\PaymentProcessorService;
    $activeProcessor = $processor ?? PaymentProcessorService::getActiveProcessor();
    $processorName = ucfirst($activeProcessor);
@endphp

@extends('layouts.app-buzbox')

@section('title', 'Payment - Order #' . $order->id)

@section('content')
<style>
    .payment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
    }
    
    .order-card {
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .order-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 25px;
    }
    
    .order-card .card-header .card-title {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .order-summary-item {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-summary-item:last-child {
        border-bottom: none;
    }
    
    .order-summary-item label {
        color: #666;
        font-size: 0.95rem;
        margin: 0;
    }
    
    .order-summary-item .value {
        font-weight: 600;
        color: #333;
        font-size: 1.05rem;
    }
    
    .payment-method-card {
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .payment-method-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }
    
    .payment-method-card i {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #00d4aa 0%, #0099ff 100%);
        border: none;
        padding: 15px 30px;
        font-weight: 600;
        border-radius: 8px;
        font-size: 1.1rem;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
        color: white;
    }
    
    .security-badge {
        background: #f0f7ff;
        border-left: 4px solid #28a745;
        padding: 15px;
        border-radius: 8px;
        color: #333;
        margin-top: 20px;
    }
    
    .amount-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 25px;
    }
    
    .amount-display .label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }
    
    .amount-display .amount {
        font-size: 2.5rem;
        font-weight: bold;
    }
</style>

<div class="payment-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 style="margin: 0; font-size: 2.2rem;">
                    <i class="fa fa-lock mr-3"></i>Secure Payment
                </h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Order #{{ $order->id }}</p>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('checkout.show') }}" class="btn btn-outline-light">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Main Payment Content -->
        <div class="col-lg-8">
            <!-- Order Details Card -->
            <div class="order-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-receipt mr-2"></i>Order Details
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="order-summary-item">
                        <label>Order Number</label>
                        <span class="value">#{{ $order->id }}</span>
                    </div>
                    <div class="order-summary-item">
                        <label>Order Date</label>
                        <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="order-summary-item">
                        <label>Status</label>
                        <span class="value">
                            @if($order->status === 'pending')
                                <span class="badge badge-warning">Pending Payment</span>
                            @elseif($order->status === 'completed')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($order->status) }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="order-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-user mr-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="order-summary-item">
                        <label>Full Name</label>
                        <span class="value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="order-summary-item">
                        <label>Email Address</label>
                        <span class="value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="order-summary-item">
                        <label>Phone Number</label>
                        <span class="value">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="order-summary-item">
                        <label>Delivery Address</label>
                        <span class="value">{{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="order-card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-credit-card mr-2"></i>Payment Method
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="payment-method-card">
                                <i class="fa fa-credit-card text-primary"></i>
                                <p class="mb-0 font-weight-bold">Debit/Credit Card</p>
                                <small class="text-muted">Visa, Mastercard</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="payment-method-card">
                                <i class="fa fa-mobile text-success"></i>
                                <p class="mb-0 font-weight-bold">Mobile Money</p>
                                <small class="text-muted">All providers supported</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="security-badge">
                <i class="fa fa-shield-alt text-success mr-2"></i>
                <strong>Secure & Encrypted</strong>
                <p class="mb-0 small mt-1">
                    Your payment is protected by 
                    <strong>{{ $activeProcessor === 'paystack' ? 'Paystack' : 'Flutterwave' }}</strong>,
                    a PCI DSS Level 1 certified payment processor.
                </p>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <!-- Amount Card -->
            <div class="amount-display">
                <div class="label">Amount to Pay</div>
                <div class="amount">₦{{ number_format($order->amount, 2) }}</div>
                <div style="font-size: 0.85rem; margin-top: 10px; opacity: 0.9;">
                    {{ $order->currency }}
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="order-card">
                <div class="card-body">
                    <button type="button" id="openPaymentModalBtn" class="btn btn-pay btn-block btn-lg mb-3" onclick="processPayment()">
                        <i class="fa fa-lock mr-2"></i>Proceed to Payment
                    </button>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="fa fa-info-circle mr-1"></i>
                            You will be redirected to the payment gateway
                        </p>
                    </div>
                </div>
            </div>

            <script>
                // Inline payment processor function
                function processPayment() {
                    console.log('processPayment() called');
                    
                    const processor = '{{ $activeProcessor }}';
                    const order = {
                        id: {{ $order->id }},
                        amount: {{ $order->amount }},
                        customer_name: '{{ $order->customer_name }}',
                        customer_email: '{{ $order->customer_email }}',
                        customer_phone: '{{ $order->customer_phone }}',
                        currency: '{{ $order->currency }}'
                    };

                    console.log('Order:', order, 'Processor:', processor);
                    
                    try {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = processor === 'paystack' 
                            ? '{{ route("payment.paystack.pay") }}'
                            : '{{ route("payment.flutterwave.pay") }}';
                        
                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);
                        
                        // Add order ID
                        const orderInput = document.createElement('input');
                        orderInput.type = 'hidden';
                        orderInput.name = 'order_id';
                        orderInput.value = order.id;
                        form.appendChild(orderInput);
                        
                        // Add amount
                        const amountInput = document.createElement('input');
                        amountInput.type = 'hidden';
                        amountInput.name = 'amount';
                        if (processor === 'paystack') {
                            amountInput.value = Math.round(order.amount * 100);
                        } else {
                            amountInput.value = order.amount;
                        }
                        form.appendChild(amountInput);
                        
                        // Add email
                        const emailInput = document.createElement('input');
                        emailInput.type = 'hidden';
                        emailInput.name = 'email';
                        emailInput.value = order.customer_email;
                        form.appendChild(emailInput);
                        
                        // For Flutterwave, add additional fields
                        if (processor === 'flutterwave') {
                            const currencyInput = document.createElement('input');
                            currencyInput.type = 'hidden';
                            currencyInput.name = 'currency';
                            currencyInput.value = order.currency || 'NGN';
                            form.appendChild(currencyInput);
                            
                            const phoneInput = document.createElement('input');
                            phoneInput.type = 'hidden';
                            phoneInput.name = 'phone_number';
                            phoneInput.value = order.customer_phone;
                            form.appendChild(phoneInput);
                            
                            const nameInput = document.createElement('input');
                            nameInput.type = 'hidden';
                            nameInput.name = 'customer_name';
                            nameInput.value = order.customer_name;
                            form.appendChild(nameInput);
                        }
                        
                        console.log('Submitting to:', form.action);
                        document.body.appendChild(form);
                        form.submit();
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    }
                }
            </script>

            <!-- Order Summary -->
            <div class="order-card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-shopping-bag mr-2"></i>Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>₦{{ number_format($order->amount * 0.95, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Processing Fee:</span>
                        <strong>₦{{ number_format($order->amount * 0.05, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="font-weight-bold">Total Amount:</span>
                        <strong class="text-primary" style="font-size: 1.2rem;">₦{{ number_format($order->amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="order-card mt-4">
                <div class="card-body text-center">
                    <i class="fa fa-headset text-primary" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p class="font-weight-bold">Need Help?</p>
                    <p class="text-muted small mb-0">
                        Contact our support team if you have any questions about your payment.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .payment-header {
            padding: 20px 0;
            margin-bottom: 20px;
        }
        
        .payment-header h1 {
            font-size: 1.5rem !important;
        }
        
        .amount-display .amount {
            font-size: 1.8rem !important;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing payment button');
        
        const paymentBtn = document.getElementById('openPaymentModalBtn');
        console.log('Payment button found:', paymentBtn);
        
        if (!paymentBtn) {
            console.error('Payment button with ID openPaymentModalBtn not found');
            return;
        }

        paymentBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Payment button clicked');
            
            try {
                const processor = '{{ $activeProcessor }}';
                const order = {
                    id: {{ $order->id }},
                    amount: {{ $order->amount }},
                    customer_name: '{{ $order->customer_name }}',
                    customer_email: '{{ $order->customer_email }}',
                    customer_phone: '{{ $order->customer_phone }}',
                    currency: '{{ $order->currency }}'
                };

                console.log('Order data:', order);
                console.log('Processor:', processor);
                
                // Create form and submit to payment processor
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = processor === 'paystack' 
                    ? '{{ route("payment.paystack.pay") }}'
                    : '{{ route("payment.flutterwave.pay") }}';
                
                console.log('Form action:', form.action);
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Add order ID
                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'order_id';
                orderInput.value = order.id;
                form.appendChild(orderInput);
                
                // Add amount
                const amountInput = document.createElement('input');
                amountInput.type = 'hidden';
                amountInput.name = 'amount';
                if (processor === 'paystack') {
                    amountInput.value = Math.round(order.amount * 100);
                } else {
                    amountInput.value = order.amount;
                }
                form.appendChild(amountInput);
                
                // Add email
                const emailInput = document.createElement('input');
                emailInput.type = 'hidden';
                emailInput.name = 'email';
                emailInput.value = order.customer_email;
                form.appendChild(emailInput);
                
                // For Flutterwave, add additional required fields
                if (processor === 'flutterwave') {
                    const currencyInput = document.createElement('input');
                    currencyInput.type = 'hidden';
                    currencyInput.name = 'currency';
                    currencyInput.value = order.currency || 'NGN';
                    form.appendChild(currencyInput);
                    
                    const phoneInput = document.createElement('input');
                    phoneInput.type = 'hidden';
                    phoneInput.name = 'phone_number';
                    phoneInput.value = order.customer_phone;
                    form.appendChild(phoneInput);
                    
                    const nameInput = document.createElement('input');
                    nameInput.type = 'hidden';
                    nameInput.name = 'customer_name';
                    nameInput.value = order.customer_name;
                    form.appendChild(nameInput);
                }
                
                console.log('Submitting form...');
                document.body.appendChild(form);
                form.submit();
            } catch (error) {
                console.error('Payment submission error:', error);
                alert('Error processing payment: ' + error.message);
            }
        });
    });
</script>
@endpush

@endsection
