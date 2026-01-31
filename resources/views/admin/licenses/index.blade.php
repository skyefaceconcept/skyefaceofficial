@extends('layouts.admin.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-8">
                <h3 class="fw-bold mb-2">📜 License Management</h3>
                <p class="text-muted mb-0">Monitor and manage all customer licenses in real-time</p>
            </div>
            <div class="col-12 col-md-4">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Licenses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <section class="section">
        <div class="row g-4">
            <!-- Total Licenses Card -->
            <div class="col-12 col-sm-6 col-lg-2.4">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Total Licenses</p>
                                <h4 class="font-weight-bold mb-0">{{ $stats['total'] }}</h4>
                            </div>
                            <div class="stat-icon bg-primary-light">
                                <i class="bi bi-file-earmark-certificate text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Licenses Card -->
            <div class="col-12 col-sm-6 col-lg-2.4">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Active</p>
                                <h4 class="font-weight-bold mb-0 text-success">{{ $stats['active'] }}</h4>
                            </div>
                            <div class="stat-icon bg-success-light">
                                <i class="bi bi-check-circle text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expiring Soon Card -->
            <div class="col-12 col-sm-6 col-lg-2.4">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Expiring Soon</p>
                                <h4 class="font-weight-bold mb-0 text-warning">{{ $stats['expiring_soon'] }}</h4>
                            </div>
                            <div class="stat-icon bg-warning-light">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expired Card -->
            <div class="col-12 col-sm-6 col-lg-2.4">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Expired</p>
                                <h4 class="font-weight-bold mb-0 text-danger">{{ $stats['expired'] }}</h4>
                            </div>
                            <div class="stat-icon bg-danger-light">
                                <i class="bi bi-x-circle text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revoked Card -->
            <div class="col-12 col-sm-6 col-lg-2.4">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body px-3 py-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small">Revoked</p>
                                <h4 class="font-weight-bold mb-0 text-secondary">{{ $stats['revoked'] }}</h4>
                            </div>
                            <div class="stat-icon bg-secondary-light">
                                <i class="bi bi-lock text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters and Search -->
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h6 class="card-title mb-0">
                    <i class="bi bi-funnel"></i> Filter & Search
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.licenses.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="License code, product, email..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select border-0 bg-light">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted">Expiry Status</label>
                        <select name="expiry_status" class="form-select border-0 bg-light">
                            <option value="">All Status</option>
                            <option value="active" {{ request('expiry_status') === 'active' ? 'selected' : '' }}>Valid</option>
                            <option value="expiring_soon" {{ request('expiry_status') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('expiry_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted">Sort By</label>
                        <select name="sort_by" class="form-select border-0 bg-light">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Newest</option>
                            <option value="expiry_date" {{ request('sort_by') === 'expiry_date' ? 'selected' : '' }}>Expiry Date</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        <a href="{{ route('admin.licenses.export', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Licenses Table -->
    <section class="section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h6 class="card-title mb-0">
                    <i class="bi bi-table"></i> License Records
                </h6>
            </div>
            <div class="card-body p-0">
                @if($licenses->isEmpty())
                    <div class="alert alert-info m-4 mb-0" role="alert">
                        <i class="bi bi-info-circle"></i>
                        <strong>No licenses found.</strong> Try adjusting your filters or search criteria.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">License Code</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Expires</th>
                                    <th class="text-center">Countdown</th>
                                    <th class="text-center">Activations</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($licenses as $license)
                                    <tr class="align-middle">
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-primary fw-bold" style="font-family: 'Monaco', 'Courier New', monospace; letter-spacing: 0.5px;">
                                                {{ substr($license->license_code, 0, 12) }}...
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $license->application_name }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong class="text-dark">{{ $license->order->customer_name ?? 'N/A' }}</strong>
                                                <small class="text-muted">{{ $license->order->customer_email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($license->status === 'active')
                                                <span class="badge bg-success-light text-success">Active</span>
                                            @elseif($license->status === 'inactive')
                                                <span class="badge bg-secondary-light text-secondary">Inactive</span>
                                            @elseif($license->status === 'expired')
                                                <span class="badge bg-danger-light text-danger">Expired</span>
                                            @else
                                                <span class="badge bg-dark-light text-dark">Revoked</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $license->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <small class="fw-500 {{ $license->expiry_date->isPast() ? 'text-danger' : ($license->expiry_date->diffInDays(now()) <= 7 ? 'text-warning' : 'text-success') }}">
                                                {{ $license->expiry_date->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $daysLeft = $license->expiry_date->diffInDays(now(), false);
                                                $hoursLeft = $license->expiry_date->diffInHours(now(), false) % 24;
                                                $minutesLeft = $license->expiry_date->diffInMinutes(now(), false) % 60;
                                            @endphp
                                            <div class="countdown" data-date="{{ $license->expiry_date->toIso8601String() }}">
                                                @if($license->expiry_date->isPast())
                                                    <span class="badge bg-danger">Expired</span>
                                                @elseif($daysLeft <= 0)
                                                    <span class="badge bg-warning text-dark" data-hours="{{ $hoursLeft }}" data-minutes="{{ $minutesLeft }}">
                                                        {{ str_pad($hoursLeft, 2, '0', STR_PAD_LEFT) }}h : {{ str_pad($minutesLeft, 2, '0', STR_PAD_LEFT) }}m
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">{{ $daysLeft }}d : {{ str_pad($hoursLeft, 2, '0', STR_PAD_LEFT) }}h</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <strong class="text-dark">{{ $license->activation_count }}</strong>
                                                @if($license->last_activated_at)
                                                    <small class="text-muted">{{ $license->last_activated_at->diffForHumans() }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-outline-info" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($license->status !== 'revoked')
                                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revokeModal{{ $license->id }}" title="Revoke License">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#reactivateModal{{ $license->id }}" title="Reactivate License">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Revoke Modal -->
                                    <div class="modal fade" id="revokeModal{{ $license->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-light border-bottom">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle text-danger"></i> Revoke License
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.licenses.revoke', $license) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="mb-3">Are you sure you want to revoke this license? This action will prevent the customer from using it.</p>
                                                        <div class="alert alert-warning mb-3" role="alert">
                                                            <strong>License Code:</strong>
                                                            <br>
                                                            <code>{{ $license->license_code }}</code>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="reason" class="form-label">Reason for Revocation <span class="text-muted">(optional)</span></label>
                                                            <textarea name="reason" class="form-control" rows="3" placeholder="E.g., Customer requested, Violation of terms, etc."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-x-circle"></i> Revoke License
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reactivate Modal -->
                                    <div class="modal fade" id="reactivateModal{{ $license->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-light border-bottom">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-arrow-clockwise text-success"></i> Reactivate License
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.licenses.reactivate', $license) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <p class="mb-3">Reactivate this license and optionally extend its validity period.</p>
                                                        <div class="alert alert-info mb-3" role="alert">
                                                            <strong>License Code:</strong>
                                                            <br>
                                                            <code>{{ $license->license_code }}</code>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="extend_days" class="form-label">Extend Validity <span class="text-muted">(days)</span></label>
                                                            <input type="number" name="extend_days" class="form-control" value="30" min="0" max="365">
                                                            <small class="text-muted">Leave at 0 to restore original expiry date</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-arrow-clockwise"></i> Reactivate
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-light d-flex justify-content-center border-top">
                        {{ $licenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(function(el) {
            const expiryDate = new Date(el.getAttribute('data-date'));
            const now = new Date();
            const diff = expiryDate - now;
            
            if (diff <= 0) {
                el.innerHTML = '<span class="badge bg-danger">Expired</span>';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            let badgeClass = 'bg-info';
            if (days === 0) {
                badgeClass = 'bg-warning text-dark';
            }
            
            let countdownText;
            if (days > 0) {
                countdownText = `${days}d : ${String(hours).padStart(2, '0')}h`;
            } else {
                countdownText = `${String(hours).padStart(2, '0')}h : ${String(minutes).padStart(2, '0')}m : ${String(seconds).padStart(2, '0')}s`;
            }
            
            el.innerHTML = `<span class="badge ${badgeClass}">${countdownText}</span>`;
        });
    }
    
    // Initial update
    updateCountdowns();
});
</script>

<style>
/* Page Styling */
.page-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 0;
    margin: -24px -24px 32px -24px;
    border-radius: 0;
}

.page-heading .page-title {
    padding: 32px 24px;
}

.page-heading h3 {
    color: #ffffff;
    font-size: 28px;
    margin-bottom: 8px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-heading .text-muted {
    color: rgba(255, 255, 255, 0.9) !important;
    font-size: 14px;
}

.page-heading .breadcrumb {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 6px;
}

.page-heading .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
}

.page-heading .breadcrumb-item.active {
    color: #ffffff;
}

/* Stat Cards */
.stat-card {
    border: none !important;
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12) !important;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.bg-primary-light {
    background-color: rgba(102, 126, 234, 0.15);
}

.bg-success-light {
    background-color: rgba(76, 175, 80, 0.15);
}

.bg-warning-light {
    background-color: rgba(255, 152, 0, 0.15);
}

.bg-danger-light {
    background-color: rgba(244, 67, 54, 0.15);
}

.bg-secondary-light {
    background-color: rgba(108, 117, 125, 0.15);
}

.bg-dark-light {
    background-color: rgba(33, 37, 41, 0.15);
}

.bg-success-light {
    color: #28a745;
}

.bg-warning-light {
    color: #ffc107;
}

.bg-danger-light {
    color: #dc3545;
}

/* Cards & Sections */
.section {
    margin-bottom: 28px;
}

.card {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    padding: 18px 24px;
    border-radius: 12px 12px 0 0 !important;
}

.card-body {
    padding: 24px;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #5a6c7d;
    border: none;
    padding: 16px 12px;
}

.table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
    border-color: #f0f2f5;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table-light {
    background-color: #f8f9fa;
}

/* Badges */
.badge {
    padding: 4px 10px;
    font-weight: 500;
    border-radius: 4px;
    font-size: 11px;
    white-space: nowrap;
    display: inline-block;
}

.bg-success-light {
    background-color: rgba(76, 175, 80, 0.1) !important;
    color: #28a745 !important;
}

.bg-warning-light {
    background-color: rgba(255, 152, 0, 0.1) !important;
}

.bg-danger-light {
    background-color: rgba(244, 67, 54, 0.1) !important;
    color: #dc3545 !important;
}

.bg-dark-light {
    background-color: rgba(33, 37, 41, 0.1) !important;
    color: #212529 !important;
}

/* Button Groups */
.btn-group-sm > .btn {
    padding: 6px 10px;
    font-size: 12px;
}

.btn-group-sm > .btn i {
    margin-right: 4px;
}

/* Form Styling */
.form-control,
.form-select {
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 8px;
}

.input-group-text {
    border-color: #e9ecef;
}

/* Countdown Timer */
.countdown {
    min-width: 140px;
}

.countdown .badge {
    font-family: 'Monaco', 'Courier New', monospace;
    letter-spacing: 1px;
}

/* Modals */
.modal-content {
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.modal-header {
    border-radius: 12px 12px 0 0 !important;
    border-color: #e9ecef;
}

.modal-header h5 {
    font-weight: 600;
    color: #2c3e50;
}

.modal-footer {
    border-radius: 0 0 12px 12px;
    border-color: #e9ecef;
}

/* Alert Styling */
.alert {
    border-radius: 8px;
    border: none;
}

.alert-info {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ff9800;
}

/* Typography */
h6.font-weight-bold,
.font-weight-bold {
    font-weight: 600 !important;
}

.fw-500 {
    font-weight: 500;
}

/* Pagination */
.pagination {
    gap: 4px;
}

.page-link {
    border-radius: 6px;
    border: none;
    color: #667eea;
    font-weight: 500;
}

.page-link:hover {
    background-color: #667eea;
    color: white;
}

.page-link.active {
    background-color: #667eea;
    border-color: #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .page-heading .page-title {
        padding: 24px 16px;
    }

    .page-heading h3 {
        font-size: 22px;
    }

    .table-responsive {
        font-size: 13px;
    }

    .table thead th,
    .table tbody td {
        padding: 12px 8px;
    }

    .btn-group-sm > .btn {
        padding: 4px 8px;
        font-size: 11px;
    }

    .stat-card {
        margin-bottom: 12px;
    }

    .card-body {
        padding: 16px;
    }
}

/* Print Styling */
@media print {
    .page-heading,
    .section:first-of-type {
        page-break-after: avoid;
    }
}
</style>
@endsection
