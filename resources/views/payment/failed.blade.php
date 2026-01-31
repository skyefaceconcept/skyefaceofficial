@extends('layouts.app-buzbox')

@section('title', 'Payment Failed')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fa fa-times-circle text-danger mr-2"></i>Payment Failed
        </h1>
        <div class="page-nav">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fa fa-home"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">
                        <i class="fa fa-exclamation-circle mr-2"></i>Payment Could Not Be Completed
                    </h3>
                </div>
                <div class="card-block p-4">
                    <!-- Error Alert -->
                    <div class="alert alert-danger mb-4">
                        <i class="fa fa-exclamation-circle mr-2"></i>
                        <strong>Payment Unsuccessful!</strong> Unfortunately, your payment could not be processed. Please try again or contact support for assistance.
                    </div>

                    <!-- Possible Reasons -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Why Did This Happen?</h5>
                        </div>
                        <div class="card-block">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <i class="fa fa-circle text-muted mr-2"></i>
                                    <span>Insufficient funds in your account or card limit exceeded</span>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-circle text-muted mr-2"></i>
                                    <span>Incorrect card details or expiration date</span>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-circle text-muted mr-2"></i>
                                    <span>Your bank or card issuer declined the transaction</span>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-circle text-muted mr-2"></i>
                                    <span>Network connection was interrupted during processing</span>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-circle text-muted mr-2"></i>
                                    <span>Your account may have security restrictions</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- What To Do -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">What Can You Do?</h5>
                        </div>
                        <div class="card-block">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <i class="fa fa-check-circle text-warning mr-2"></i>
                                    <div>
                                        <strong class="text-dark d-block">Retry Payment</strong>
                                        <small class="text-muted">Verify your details and try the payment again</small>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-check-circle text-warning mr-2"></i>
                                    <div>
                                        <strong class="text-dark d-block">Use Different Card</strong>
                                        <small class="text-muted">Try another payment method or card</small>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-check-circle text-warning mr-2"></i>
                                    <div>
                                        <strong class="text-dark d-block">Contact Your Bank</strong>
                                        <small class="text-muted">Your bank may have blocked the transaction for security</small>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <i class="fa fa-check-circle text-warning mr-2"></i>
                                    <div>
                                        <strong class="text-dark d-block">Get Support</strong>
                                        <small class="text-muted">Contact our support team for additional help</small>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" onclick="window.history.back()" class="btn btn-danger btn-block">
                                <i class="fa fa-arrow-left mr-2"></i>Try Again
                            </button>
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
                    <i class="fa fa-headphones fa-2x text-danger mb-3"></i>
                    <h6 class="card-title">Need Assistance?</h6>
                    <p class="text-muted small mb-3">Our support team is ready to help you resolve this issue and get your payment processed.</p>
                    <a href="mailto:{{ config('company.support_email', 'support@skyeface.com') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-envelope mr-1"></i>Contact Support
                    </a>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card mt-4">
                <div class="card-block p-4">
                    <h6 class="card-title mb-3">Quick Tips</h6>
                    <div class="small text-muted">
                        <p class="mb-3">
                            <i class="fa fa-lightbulb-o text-warning mr-2"></i>
                            Check your card balance and limits
                        </p>
                        <p class="mb-3">
                            <i class="fa fa-lightbulb-o text-warning mr-2"></i>
                            Verify all card details are correct
                        </p>
                        <p>
                            <i class="fa fa-lightbulb-o text-warning mr-2"></i>
                            Contact your bank to verify the transaction
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
