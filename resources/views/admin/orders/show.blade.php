@extends('layouts.admin.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-receipt"></i>
    </span>
    Order #{{ $order->id }}
  </h3>
  <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
    <i class="mdi mdi-arrow-left"></i> Back to Orders
  </a>
</div>

<div class="row">
  <!-- Order Details -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-information"></i> Order Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Order ID</p>
            <p class="font-weight-bold">#{{ $order->id }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-muted mb-1">Order Date</p>
            <p class="font-weight-bold">{{ $order->created_at->format('M d, Y H:i A') }}</p>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Status</p>
            <p>
              @if($order->status === 'pending')
                <span class="badge badge-warning">Pending</span>
              @elseif($order->status === 'completed')
                <span class="badge badge-success">Completed</span>
              @elseif($order->status === 'failed')
                <span class="badge badge-danger">Failed</span>
              @elseif($order->status === 'cancelled')
                <span class="badge badge-secondary">Cancelled</span>
              @endif
            </p>
          </div>
          <div class="col-md-6">
            <p class="text-muted mb-1">Currency</p>
            <p class="font-weight-bold">{{ $order->currency }}</p>
          </div>
        </div>

        @if($order->completed_at)
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Completed Date</p>
            <p class="font-weight-bold">{{ $order->completed_at->format('M d, Y H:i A') }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Customer Details -->
    <div class="card mt-3">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-account"></i> Customer Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Name</p>
            <p class="font-weight-bold">{{ $order->customer_name }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-muted mb-1">Email</p>
            <p class="font-weight-bold">{{ $order->customer_email }}</p>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Phone</p>
            <p class="font-weight-bold">{{ $order->customer_phone }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-muted mb-1">Country</p>
            <p class="font-weight-bold">{{ $order->country }}</p>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-12">
            <p class="text-muted mb-1">Address</p>
            <p class="font-weight-bold">{{ $order->address }}</p>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <p class="text-muted mb-1">City</p>
            <p class="font-weight-bold">{{ $order->city }}</p>
          </div>
          <div class="col-md-4">
            <p class="text-muted mb-1">State</p>
            <p class="font-weight-bold">{{ $order->state }}</p>
          </div>
          <div class="col-md-4">
            <p class="text-muted mb-1">ZIP Code</p>
            <p class="font-weight-bold">{{ $order->zip }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Details -->
    <div class="card mt-3">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-credit-card"></i> Payment Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="text-muted mb-1">Payment Method</p>
            <p class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-muted mb-1">Payment Processor</p>
            <p class="font-weight-bold">{{ ucfirst($order->payment_processor) }}</p>
          </div>
        </div>

        @if($order->transaction_reference)
        <div class="row mb-3">
          <div class="col-md-12">
            <p class="text-muted mb-1">Transaction Reference</p>
            <p class="font-weight-bold">{{ $order->transaction_reference }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Order Items -->
    @if(!empty($cartItems))
    <div class="card mt-3">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-shopping-cart"></i> Order Items
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>License Duration</th>
                <th>Base Price</th>
                <th>License Price</th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cartItems as $item)
              <tr>
                <td>{{ $item['name'] ?? 'N/A' }}</td>
                <td>
                  <span class="badge badge-primary">{{ ucfirst(str_replace('months', ' months', $item['licenseDuration'] ?? 'N/A')) }}</span>
                </td>
                <td>₦{{ number_format($item['basePrice'] ?? 0, 2) }}</td>
                <td>₦{{ number_format($item['licensePrice'] ?? 0, 2) }}</td>
                <td>{{ $item['quantity'] ?? 1 }}</td>
                <td><strong>₦{{ number_format($item['totalPrice'] ?? 0, 2) }}</strong></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

    @if($order->notes)
    <div class="card mt-3">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">
          <i class="mdi mdi-note"></i> Notes
        </h5>
      </div>
      <div class="card-body">
        <p>{{ $order->notes }}</p>
      </div>
    </div>
    @endif
  </div>

  <!-- Sidebar Summary -->
  <div class="col-md-4">
    <!-- Amount Summary -->
    <div class="card">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">Order Summary</h5>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <span>Subtotal:</span>
          <strong>₦{{ number_format($order->amount, 2) }}</strong>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
          <span class="h5">Total Amount:</span>
          <h5 class="text-primary">₦{{ number_format($order->amount, 2) }}</h5>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="card mt-3">
      <div class="card-header bg-light">
        <h5 class="card-title mb-0">Actions</h5>
      </div>
      <div class="card-body">
        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#statusModal">
          <i class="mdi mdi-pencil"></i> Change Status
        </button>
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="margin-top: 10px;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-block btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
            <i class="mdi mdi-delete"></i> Delete Order
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Order Status</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
              <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>Failed</option>
              <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .badge {
    padding: 5px 10px;
    border-radius: 4px;
  }
</style>
@endsection
