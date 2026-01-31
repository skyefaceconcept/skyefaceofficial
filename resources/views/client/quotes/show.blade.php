@extends('layouts.app-buzbox')

@section('title', 'Quote Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Quote #{{ $quote->id }}</h1>
        <div class="page-nav">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Quote Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quote Information</h3>
                </div>
                <div class="card-block">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Service Package</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->package ?? 'N/A' }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Status</label>
                                <p class="form-control-plaintext">
                                    @if($quote->status === 'new')
                                        <span class="badge badge-warning">New</span>
                                    @elseif($quote->status === 'reviewed')
                                        <span class="badge badge-info">Reviewed</span>
                                    @elseif($quote->status === 'quoted')
                                        <span class="badge badge-success">Quoted</span>
                                    @elseif($quote->status === 'accepted')
                                        <span class="badge badge-success">Accepted</span>
                                    @elseif($quote->status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($quote->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Full Name</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->name }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Email</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->email }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Phone</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->phone }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Submitted Date</label>
                                <p class="form-control-plaintext"><strong>{{ $quote->created_at->format('M d, Y H:i') }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-muted">Service Details</label>
                        <div class="form-control-plaintext p-3 border rounded" style="background-color: #f9f9f9; min-height: 100px;">
                            {{ $quote->details ?? 'No details provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quote Price & Response -->
        <div class="col-md-4">
            <!-- Price Card -->
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Quote Price</h3>
                </div>
                <div class="card-block text-center py-4">
                    @if($quote->quoted_price)
                        <h2 class="text-primary mb-0">₦{{ number_format($quote->quoted_price, 2) }}</h2>
                        <p class="text-muted mt-2">Quoted Price</p>

                        <!-- Payment Button -->
                        @if($quote->status === 'quoted')
                            <a href="{{ route('payment.form', $quote->id) }}" class="btn btn-success btn-lg btn-block mt-3">
                                <i class="fa fa-credit-card"></i> Proceed to Payment
                            </a>
                            <p class="text-muted text-small mt-2">Click to proceed with payment</p>
                        @elseif($quote->status === 'accepted')
                            <span class="badge badge-success">Payment Accepted</span>
                        @endif
                    @else
                        <p class="text-muted">Price pending</p>
                    @endif
                </div>
            </div>

            <!-- Response Card -->
            @if($quote->response)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Admin Response</h3>
                    </div>
                    <div class="card-block">
                        <div class="p-3 border rounded" style="background-color: #f9f9f9;">
                            {{ $quote->response }}
                        </div>
                        @if($quote->responded_at)
                            <p class="text-muted text-small mt-2 mb-0">
                                <i class="fa fa-clock-o"></i> Responded: {{ $quote->responded_at->format('M d, Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Admin Notes Card -->
            @if($quote->admin_notes)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Notes</h3>
                    </div>
                    <div class="card-block">
                        <div class="p-3 border rounded" style="background-color: #f9f9f9;">
                            {{ $quote->admin_notes }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-block">
                    <div class="btn-group d-flex" role="group">
                        <a href="{{ route('dashboard') }}#quotes" class="btn btn-secondary flex-grow-1">
                            <i class="fa fa-list"></i> View All Quotes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
