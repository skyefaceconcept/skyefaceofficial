@extends('layouts.admin.app')

@section('title', 'Device Repairs')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Device Repair Bookings</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Repairs</h5>
      </div>
      <div class="card-body">
        @if($repairs->count())
          <div class="table-responsive">
            <table class="table table-hover table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Invoice #</th>
                  <th>Customer</th>
                  <th>Device</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th>Date</th>
                  <th>Cost</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($repairs as $repair)
                  <tr>
                    <td>
                      <strong>{{ $repair->invoice_number }}</strong>
                    </td>
                    <td>
                      <div>{{ $repair->customer_name }}</div>
                      <small class="text-muted">{{ $repair->customer_email }}</small>
                    </td>
                    <td>
                      <div>{{ $repair->device_type }}</div>
                      <small class="text-muted">{{ $repair->device_brand }} {{ $repair->device_model }}</small>
                    </td>
                    <td>
                      @php
                        $statusColor = match($repair->status) {
                          'Received' => 'primary',
                          'Diagnosed' => 'info',
                          'In Progress' => 'warning',
                          'Quality Check' => 'secondary',
                          'Ready for Pickup' => 'success',
                          'Completed' => 'success',
                          default => 'light'
                        };
                      @endphp
                      <span class="badge badge-{{ $statusColor }}">{{ $repair->status }}</span>
                    </td>
                    <td>
                      @if($repair->payment_status === 'completed')
                        <span class="badge badge-success">Paid</span>
                      @elseif($repair->payment_status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                      @else
                        <span class="badge badge-secondary">N/A</span>
                      @endif
                    </td>
                    <td>
                      <small>{{ $repair->created_at->format('M d, Y') }}</small>
                    </td>
                    <td>
                      <strong>₦{{ number_format($repair->cost_estimate, 2) }}</strong>
                    </td>
                    <td>
                      <a href="{{ route('admin.repairs.show', $repair->id) }}" class="btn btn-sm btn-info" title="View Details">
                        <i class="mdi mdi-eye"></i> View
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-3">
            {{ $repairs->links() }}
          </div>
        @else
          <div class="alert alert-info">
            <i class="mdi mdi-information"></i> No repairs found.
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra-css')
<style>
  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
  }
</style>
@endsection
