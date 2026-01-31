@extends('layouts.app-buzbox')

@section('title', 'Paystack Payment - Order #' . $order->id)

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fa fa-lock mr-2"></i>Secure Payment Processing
                    </h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-success mb-3" role="status">
                        <span class="sr-only">Processing...</span>
                    </div>
                    <h6>Processing your payment...</h6>
                    <p class="text-muted small">Order #{{ $order->id }} - ₦{{ number_format($order->amount, 2) }}</p>
                    <p class="text-muted small mt-3">You will be redirected to Paystack shortly.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Paystack payment
        var handler = PaystackPop.setup({
            key: '{{ $publicKey }}',
            email: '{{ $payment->customer_email }}',
            amount: {{ $payment->amount * 100 }},
            ref: '{{ $payment->transaction_reference }}',
            onClose: function() {
                console.log('Payment modal closed');
                window.location.href = '{{ route("payment.show", $order->id) }}';
            },
            callback: function(response) {
                console.log('Paystack response:', response);
                // Payment successful - redirect to callback
                window.location.href = '{{ route("payment.callback") }}?reference=' + response.reference + '&status=success&payment_id={{ $payment->id }}';
            }
        });
        handler.openIframe();
    });
</script>
@endsection
