@extends('layouts.app-buzbox')

@section('title', 'Payment Successful')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fa fa-check-circle text-success mr-2"></i>Payment Successful
        </h1>
        <div class="page-nav">
            @if(!empty($payment->quote_id))
                <a href="{{ route('quotes.show', $payment->quote_id) }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Quote
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Dashboard
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fa fa-check-circle mr-2"></i>Payment Completed
                    </h3>
                </div>
                <div class="card-block p-4">
                    <!-- Success Alert -->
                    <div class="alert alert-success mb-4">
                        <i class="fa fa-check-circle mr-2"></i>
                        <strong>Thank you for your payment!</strong> Your transaction has been successfully processed and verified.
                    </div>

                    <!-- Payment Details Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Payment Details</h5>
                        </div>
                        <div class="card-block">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted small text-uppercase mb-1">Reference</p>
                                    <p class="text-dark font-weight-600 mb-0">{{ $payment->reference }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small text-uppercase mb-1">Transaction ID</p>
                                    <p class="text-dark font-weight-600 mb-0">{{ $payment->transaction_id }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted small text-uppercase mb-1">Amount Paid</p>
                                    <h4 class="text-success mb-0">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small text-uppercase mb-1">Date & Time</p>
                                    <p class="text-dark font-weight-600 mb-0">{{ $payment->paid_at ? $payment->paid_at->format('F d, Y \a\t g:i A') : 'Just now' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Number -->
                    <div class="alert alert-light border mb-4 text-center">
                        <p class="text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Invoice Number</p>
                        <h3 class="text-dark mb-0">#{{ $payment->quote_id ?? $payment->order_id ?? $payment->id }}</h3>
                    </div>

                    <!-- Next Steps -->
                    <div class="mb-4">
                        <h5 class="card-title mb-3">Next Steps</h5>
                        <div class="list-group">
                            <div class="list-group-item border-left-4 border-left-success pl-3">
                                <i class="fa fa-check text-success mr-2"></i>
                                <strong>Confirmation Sent</strong>
                                <p class="text-muted small mb-0 mt-1">A detailed invoice receipt has been sent to <strong>{{ $payment->customer_email }}</strong></p>
                            </div>
                            <div class="list-group-item border-left-4 border-left-success pl-3">
                                <i class="fa fa-check text-success mr-2"></i>
                                <strong>Payment Verified</strong>
                                <p class="text-muted small mb-0 mt-1">Your payment has been confirmed with Flutterwave and is now recorded in our system</p>
                            </div>
                            <div class="list-group-item border-left-4 border-left-success pl-3">
                                <i class="fa fa-check text-success mr-2"></i>
                                <strong>Project Begins</strong>
                                <p class="text-muted small mb-0 mt-1">Our team will begin work on your project shortly</p>
                            </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            @if(!empty($payment->quote_id))
                                <a href="{{ route('quotes.show', $payment->quote_id) }}" class="btn btn-success btn-block">
                                    <i class="fa fa-arrow-left mr-2"></i>Back to Quote
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-success btn-block">
                                    <i class="fa fa-arrow-left mr-2"></i>Go to Dashboard
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-home mr-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Support Card -->
            <div class="card bg-light">
                <div class="card-block text-center p-4">
                    <i class="fa fa-headphones fa-2x text-success mb-3"></i>
                    <h6 class="card-title">Need Help?</h6>
                    <p class="text-muted small mb-3">If you have any questions about your payment, our support team is here to help.</p>
                    <a href="mailto:{{ config('company.support_email', 'support@skyeface.com') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-envelope mr-1"></i>Contact Support
                    </a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-block p-4">
                    <h6 class="card-title mb-3">What's Next?</h6>
                    <div class="small text-muted">
                        <p class="mb-3">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            You will receive a confirmation email shortly
                        </p>
                        <p class="mb-3">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Our team will review your project details
                        </p>
                        <p>
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Work will begin on your timeline
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
