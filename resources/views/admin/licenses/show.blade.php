@extends('layouts.staradmin')

@section('content')
<div class="container-scoped">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>License Details</h3>
                    <p class="text-muted">View and manage license information</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.licenses.index') }}">Licenses</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <!-- License Information Card -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">License Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">License Code</h6>
                                    <p class="font-monospace bg-light p-2 rounded" style="word-break: break-all;">
                                        <strong>{{ $license->license_code }}</strong>
                                        <button onclick="copyToClipboard('{{ $license->license_code }}')" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Product/Application</h6>
                                    <p><strong>{{ $license->application_name }}</strong></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Status</h6>
                                    <p>
                                        @if($license->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($license->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @elseif($license->status === 'expired')
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            <span class="badge bg-dark">Revoked</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">License ID</h6>
                                    <p><strong>#{{ $license->id }}</strong></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Created Date</h6>
                                    <p>
                                        <strong>{{ $license->created_at->format('F d, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $license->created_at->format('H:i A') }}</small>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Expiry Date</h6>
                                    <p>
                                        <strong class="{{ $license->expiry_date->isPast() ? 'text-danger' : ($daysRemaining <= 7 ? 'text-warning' : 'text-success') }}">
                                            {{ $license->expiry_date->format('F d, Y') }}
                                        </strong>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Days Remaining</h6>
                                    <p>
                                        @if($license->expiry_date->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($daysRemaining <= 7)
                                            <span class="badge bg-warning text-dark">{{ $daysRemaining }} days</span>
                                        @else
                                            <span class="badge bg-info">{{ $daysRemaining }} days</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Validity Period</h6>
                                    <p id="countdown" class="countdown-timer">
                                        <strong id="countdown-text">--</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activation Information Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Activation Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Total Activations</h6>
                                    <p><h4 class="text-primary">{{ $license->activation_count }}</h4></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Last Activated</h6>
                                    <p>
                                        @if($license->last_activated_at)
                                            <strong>{{ $license->last_activated_at->format('F d, Y H:i A') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $license->last_activated_at->diffForHumans() }}</small>
                                        @else
                                            <em class="text-muted">Never activated</em>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($license->last_activated_ip)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="text-muted">Last Activation IP</h6>
                                        <p><code>{{ $license->last_activated_ip }}</code></p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            @if($license->order)
                                <div class="mb-3">
                                    <h6 class="text-muted">Name</h6>
                                    <p><strong>{{ $license->order->customer_name }}</strong></p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-muted">Email</h6>
                                    <p><a href="mailto:{{ $license->order->customer_email }}">{{ $license->order->customer_email }}</a></p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-muted">Phone</h6>
                                    <p>{{ $license->order->customer_phone ?? 'N/A' }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-muted">Delivery Address</h6>
                                    <p>
                                        {{ $license->order->address }}<br>
                                        {{ $license->order->city }}, {{ $license->order->state }} {{ $license->order->zip }}<br>
                                        {{ $license->order->country }}
                                    </p>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6 class="text-muted">Related Order</h6>
                                    <p>
                                        <a href="{{ route('admin.orders.show', $license->order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-box-seam"></i> View Order #{{ $license->order->id }}
                                        </a>
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-warning">No associated order found</div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Actions</h5>
                        </div>
                        <div class="card-body">
                            @if($license->status !== 'revoked')
                                <button type="button" class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#revokeModal">
                                    <i class="bi bi-x-circle"></i> Revoke License
                                </button>
                            @else
                                <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#reactivateModal">
                                    <i class="bi bi-arrow-clockwise"></i> Reactivate License
                                </button>
                            @endif

                            <a href="{{ route('admin.licenses.index') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Back to Licenses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Revoke Modal -->
<div class="modal fade" id="revokeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revoke License</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.licenses.revoke', $license) }}">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to revoke this license?</p>
                    <p class="font-monospace bg-light p-2 rounded">{{ $license->license_code }}</p>
                    <div class="form-group">
                        <label for="reason">Reason (optional)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for revocation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Revoke License</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reactivate Modal -->
<div class="modal fade" id="reactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reactivate License</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.licenses.reactivate', $license) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Reactivate this license?</p>
                    <p class="font-monospace bg-light p-2 rounded">{{ $license->license_code }}</p>
                    <div class="form-group">
                        <label for="extend_days">Extend Validity (days)</label>
                        <input type="number" name="extend_days" class="form-control" value="30" min="0" max="365">
                        <small class="text-muted">Leave at 0 to use original expiry date if available</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Reactivate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('License code copied to clipboard!');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const expiryDate = new Date('{{ $license->expiry_date->toIso8601String() }}');
    
    function updateCountdown() {
        const now = new Date();
        const diff = expiryDate - now;
        
        if (diff <= 0) {
            document.getElementById('countdown-text').textContent = 'Expired';
            document.getElementById('countdown').className = 'countdown-timer text-danger';
            return;
        }
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        let text;
        let color = 'text-success';
        
        if (days > 0) {
            text = `${days} days, ${hours} hours, ${minutes} minutes`;
        } else if (hours > 0) {
            text = `${hours} hours, ${minutes} minutes, ${seconds} seconds`;
            color = 'text-warning';
        } else {
            text = `${minutes} minutes, ${seconds} seconds`;
            color = 'text-danger';
        }
        
        document.getElementById('countdown-text').textContent = text;
        document.getElementById('countdown').className = `countdown-timer ${color}`;
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>

<style>
.countdown-timer {
    font-size: 1.1em;
    font-weight: 500;
}

.font-monospace {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}
</style>
@endsection
