@extends('layouts.app-buzbox')

@section('title', 'Flutterwave Payment - Order #' . $order->id)

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fa fa-lock mr-2"></i>Secure Payment Processing
                    </h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Processing...</span>
                    </div>
                    <h6>Processing your payment...</h6>
                    <p class="text-muted small">Order #{{ $order->id }} - ₦{{ number_format($order->amount, 2) }}</p>
                    <p class="text-muted small mt-3">You will be redirected to Flutterwave shortly.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flutterwave payment
        FlutterwaveCheckout({
            public_key: '{{ $publicKey }}',
            tx_ref: '{{ $payment->transaction_reference }}',
            amount: {{ $payment->amount }},
            currency: '{{ $payment->currency }}',
            payment_options: 'card, banktransfer, ussd, barter, bank_transfer, apple_pay, google_pay',
            customer: {
                email: '{{ $payment->customer_email }}',
                phonenumber: '{{ $order->customer_phone ?? "" }}',
                name: '{{ $payment->customer_name }}'
            },
            customizations: {
                title: 'Skyeface Payment',
                description: 'Order #{{ $order->id }}',
                logo: '{{ asset('images/logo.png') }}'
            },
            callback: function(response) {
                console.log('Flutterwave response:', response);
                
                if (response.status === 'successful') {
                    // Payment successful
                    window.location.href = '{{ route("payment.callback") }}?status=successful&transaction_id=' + response.transaction_id + '&tx_ref=' + response.tx_ref + '&payment_id={{ $payment->id }}';
                } else {
                    // Payment failed
                    window.location.href = '{{ route("payment.failed") }}?payment_id={{ $payment->id }}';
                }
            },
            onclose: function() {
                console.log('Payment modal closed');
                window.location.href = '{{ route("payment.show", $order->id) }}';
            }
        });
    });
</script>
@endsection
