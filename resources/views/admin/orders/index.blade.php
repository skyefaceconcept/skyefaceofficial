@extends('layouts.admin.app')

@section('title', 'Orders Management')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-receipt"></i>
    </span>
    Orders Management
  </h3>
</div>

<div class="row">
  <div class="col-md-12">
    <!-- Statistics Cards -->
    <div class="row mb-3">
      <div class="col-md-2">
        <div class="card card-body bg-light border-0">
          <h6 class="card-title font-weight-bold">Total Orders</h6>
          <h2 class="text-primary">{{ $stats['total'] }}</h2>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card card-body bg-light border-0">
          <h6 class="card-title font-weight-bold">Pending</h6>
          <h2 class="text-warning">{{ $stats['pending'] }}</h2>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card card-body bg-light border-0">
          <h6 class="card-title font-weight-bold">Completed</h6>
          <h2 class="text-success">{{ $stats['completed'] }}</h2>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card card-body bg-light border-0">
          <h6 class="card-title font-weight-bold">Failed</h6>
          <h2 class="text-danger">{{ $stats['failed'] }}</h2>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card card-body bg-light border-0">
          <h6 class="card-title font-weight-bold">Cancelled</h6>
          <h2 class="text-secondary">{{ $stats['cancelled'] }}</h2>
        </div>
      </div>
      <div class="col-md-2">
        <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-primary btn-block">
          <i class="mdi mdi-download"></i> Export CSV
        </a>
      </div>
    </div>

    <!-- Filters -->
    <div class="card card-body mb-3">
      <form method="GET" action="{{ route('admin.orders.index') }}" class="form-inline" style="gap: 10px;">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email or phone..." 
               value="{{ request('search') }}" style="min-width: 250px;">
        
        <select name="status" class="form-control">
          <option value="">All Statuses</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <select name="payment_method" class="form-control">
          <option value="">All Payment Methods</option>
          <option value="flutterwave" {{ request('payment_method') === 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
          <option value="paystack" {{ request('payment_method') === 'paystack' ? 'selected' : '' }}>Paystack</option>
          <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
        </select>

        <button type="submit" class="btn btn-primary">
          <i class="mdi mdi-magnify"></i> Filter
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
          <i class="mdi mdi-refresh"></i> Reset
        </a>
      </form>
    </div>

    <!-- Orders Table -->
    <div class="card">
      <div class="card-body">
        @if($orders->isEmpty())
          <div class="alert alert-info">
            <i class="mdi mdi-information"></i> No orders found.
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr class="table-header-bg">
                  <th>Order ID</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Amount (₦)</th>
                  <th>Status</th>
                  <th>Payment Method</th>
                  <th>Order Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($orders as $order)
                  <tr>
                    <td>
                      <strong>#{{ $order->id }}</strong>
                    </td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>
                      <strong>{{ number_format($order->amount, 2) }}</strong>
                    </td>
                    <td>
                      @if($order->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                      @elseif($order->status === 'completed')
                        <span class="badge badge-success">Completed</span>
                      @elseif($order->status === 'failed')
                        <span class="badge badge-danger">Failed</span>
                      @elseif($order->status === 'cancelled')
                        <span class="badge badge-secondary">Cancelled</span>
                      @endif
                    </td>
                    <td>
                      @if($order->payment_method === 'flutterwave')
                        <span class="badge badge-info"><i class="mdi mdi-credit-card"></i> Flutterwave</span>
                      @elseif($order->payment_method === 'paystack')
                        <span class="badge badge-info"><i class="mdi mdi-credit-card"></i> Paystack</span>
                      @elseif($order->payment_method === 'bank_transfer')
                        <span class="badge badge-secondary"><i class="mdi mdi-bank"></i> Bank Transfer</span>
                      @endif
                    </td>
                    <td>
                      <small>{{ $order->created_at->format('M d, Y') }}</small>
                      <br>
                      <small class="text-muted">{{ $order->created_at->format('H:i A') }}</small>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary" title="View">
                          <i class="mdi mdi-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" 
                                data-target="#statusModal{{ $order->id }}" title="Change Status">
                          <i class="mdi mdi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display: inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" 
                                  onclick="return confirm('Are you sure?')" title="Delete">
                            <i class="mdi mdi-delete"></i>
                          </button>
                        </form>
                      </div>

                      <!-- Status Update Modal -->
                      <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1" role="dialog">
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
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-3">
            {{ $orders->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
  .table-header-bg {
    background-color: #f5f5f5;
    font-weight: 600;
  }
  .badge {
    padding: 5px 10px;
    border-radius: 4px;
  }
</style>
@endsection
