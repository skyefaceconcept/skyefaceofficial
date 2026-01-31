@extends('layouts.admin.app')

@section('title', 'Repair - ' . $repair->invoice_number)

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Repair Booking Details</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <!-- Left Column: Repair Details -->
  <div class="col-md-8">
    <!-- Customer Information -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Customer Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Name:</strong></p>
            <p>{{ $repair->customer_name }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Email:</strong></p>
            <p><a href="mailto:{{ $repair->customer_email }}">{{ $repair->customer_email }}</a></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Phone:</strong></p>
            <p><a href="tel:{{ $repair->customer_phone }}">{{ $repair->customer_phone }}</a></p>
          </div>
          <div class="col-md-6">
            <p><strong>Invoice #:</strong></p>
            <p><strong style="color: #28a745;">{{ $repair->invoice_number }}</strong></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Device Information -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Device Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Device Type:</strong></p>
            <p>{{ $repair->device_type }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Brand:</strong></p>
            <p>{{ $repair->device_brand }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <p><strong>Model:</strong></p>
            <p>{{ $repair->device_model }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Urgency:</strong></p>
            <p>
              @php
                $urgencyColor = match($repair->urgency) {
                  'Urgent' => 'danger',
                  'Express' => 'warning',
                  'Normal' => 'info',
                  default => 'secondary'
                };
              @endphp
              <span class="badge badge-{{ $urgencyColor }}">{{ $repair->urgency }}</span>
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <p><strong>Issue Description:</strong></p>
            <p style="background: #f5f5f5; padding: 10px; border-radius: 4px;">{{ $repair->issue_description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Repair Timeline/History -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Repair History</h5>
      </div>
      <div class="card-body">
        @if($statuses->count())
          <div class="timeline">
            @foreach($statuses as $status)
              <div style="display: flex; margin-bottom: 20px;">
                <div style="min-width: 120px;">
                  <small class="text-muted">{{ $status->created_at->format('M d, Y H:i') }}</small>
                </div>
                <div style="flex: 1;">
                  <strong>{{ $status->status }}</strong>
                  @if($status->notes)
                    <div style="background: #f9f9f9; padding: 8px; margin-top: 5px; border-left: 3px solid #28a745; border-radius: 2px;">
                      {{ $status->notes }}
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="alert alert-info">No history yet.</div>
        @endif
      </div>
    </div>
  </div>

  <!-- Right Column: Status & Actions -->
  <div class="col-md-4">
    <!-- Current Status -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Current Status</h5>
      </div>
      <div class="card-body">
        @php
          $statusColor = match($repair->status) {
            'Pending' => 'secondary',
            'Received' => 'warning',
            'Diagnosed' => 'info',
            'In Progress' => 'primary',
            'Quality Check' => 'info',
            'Quality Checked' => 'warning',
            'Cost Approval' => 'warning',
            'Ready for Pickup' => 'success',
            'Completed' => 'success',
            default => 'secondary'
          };
          $progress = match($repair->status) {
            'Pending' => 10,
            'Received' => 20,
            'Diagnosed' => 40,
            'In Progress' => 60,
            'Quality Check' => 80,
            'Quality Checked' => 82,
            'Cost Approval' => 85,
            'Ready for Pickup' => 95,
            'Completed' => 100,
            default => 0
          };
        @endphp

        <div class="mb-3">
          <span class="badge badge-{{ $statusColor }}" style="font-size: 14px; padding: 8px 12px;">{{ $repair->status }}</span>
        </div>

        <!-- Progress Bar -->
        <div class="progress">
          <div class="progress-bar bg-{{ $statusColor }}" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <small class="text-muted">{{ $progress }}% Complete</small>
      </div>
    </div>

    <!-- Repair Cost -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Payment Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Payment Status:</strong></p>
            <p>
              @if($repair->payment_status === 'completed')
                <span class="badge badge-success">Completed</span>
              @elseif($repair->payment_status === 'pending')
                <span class="badge badge-warning">Pending</span>
              @else
                <span class="badge badge-secondary">{{ $repair->payment_status ?? 'Unknown' }}</span>
              @endif
            </p>
          </div>
          <div class="col-md-6">
            <p><strong>Payment Processor:</strong></p>
            <p>{{ ucfirst($repair->payment_processor ?? 'N/A') }}</p>
          </div>
        </div>
        @if($repair->payment_reference)
          <div class="row">
            <div class="col-md-6">
              <p><strong>Payment Reference:</strong></p>
              <p><code>{{ $repair->payment_reference }}</code></p>
            </div>
            @if($repair->payment_verified_at)
              <div class="col-md-6">
                <p><strong>Verified At:</strong></p>
                <p>{{ $repair->payment_verified_at->format('M d, Y h:i A') }}</p>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>

    <!-- Repair Cost -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Repair Cost</h5>
      </div>
      <div class="card-body">
        <!-- Consultation Fee -->
        <div style="font-size: 18px; margin-bottom: 20px;">
          <strong>Consultation Fee:</strong> <span style="color: #28a745; font-weight: bold;">₦{{ number_format($repair->cost_estimate, 2) }}</span>
          <div style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px; padding: 8px; margin-top: 8px; font-size: 12px; color: #1565c0;">
            <strong><i class="fa fa-info-circle mr-2"></i>Note:</strong>
            @if($repair->status === 'Pending')
              Charged when device is received. Mark status as "Received" when device arrives.
            @else
              Charged on {{ $repair->payment_status === 'completed' ? ($repair->payment_verified_at ? $repair->payment_verified_at->format('M d, Y') : 'receipt') : 'receipt of device' }}
            @endif
          </div>
        </div>

        <!-- Total Repair Cost (controlled by JavaScript) -->
        <div id="costFormWrapper" style="margin-top: 20px; display: none;">
          <form id="costForm">
            @csrf
            <div class="form-group" style="margin-bottom: 0;">
              <div style="display: flex; gap: 8px; align-items: flex-end;">
                <div style="flex: 1;">
                  <label for="costActual" style="font-weight: 600; color: #222; margin-bottom: 8px; display: block;">Total Repair Cost</label>
                  <div class="input-group">
                    <span class="input-group-text">₦</span>
                    <input type="number" class="form-control" id="costActual" name="cost_actual" value="{{ $repair->cost_actual ?? '' }}" step="0.01" min="0" placeholder="0.00" style="border: 1px solid #ddd; font-size: 16px; padding: 10px; font-weight: 600;">
                  </div>
                </div>
                <button type="submit" class="btn btn-success" style="white-space: nowrap; padding: 10px 20px; height: 38px;">
                  <i class="mdi mdi-check mr-2"></i>Update
                </button>
              </div>
              <small class="text-muted d-block mt-2" style="font-size: 12px;">Total amount customer will pay (consultation fee + parts + labor)</small>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Update Status -->
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">Update Status</h5>
      </div>
      <div class="card-body">
        <form id="statusForm">
          @csrf
          <div class="form-group">
            <label for="status">New Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="">-- Select Status --</option>
              <option value="Pending" @if($repair->status === 'Pending') selected @endif>Pending</option>
              <option value="Received" @if($repair->status === 'Received') selected @endif>Received</option>
              <option value="Diagnosed" @if($repair->status === 'Diagnosed') selected @endif>Diagnosed</option>
              <option value="In Progress" @if($repair->status === 'In Progress') selected @endif>In Progress</option>
              <option value="Quality Check" @if($repair->status === 'Quality Check') selected @endif>Quality Check</option>
              <option value="Quality Checked" @if($repair->status === 'Quality Checked') selected @endif>Quality Checked</option>
              <option value="Cost Approval" @if($repair->status === 'Cost Approval') selected @endif>Cost Approval</option>
              <option value="Ready for Pickup" @if($repair->status === 'Ready for Pickup') selected @endif>Ready for Pickup</option>
              <option value="Completed" @if($repair->status === 'Completed') selected @endif>Completed</option>
            </select>
          </div>

          <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes..."></textarea>

            <!-- Suggestion Box -->
            <div id="suggestionBox" style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 4px; padding: 10px; margin-top: 10px; display: none;">
              <small style="color: #0066cc; font-weight: 600;">Suggested message:</small>
              <p id="suggestionText" style="margin: 5px 0 0 0; color: #004d99; font-size: 13px; cursor: pointer;">
                Click to insert
              </p>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Update Status</button>
        </form>
      </div>
    </div>

    <!-- Dates -->
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Dates</h5>
      </div>
      <div class="card-body">
        <p>
          <strong>Booked:</strong><br>
          <small>{{ $repair->created_at->format('M d, Y h:i A') }}</small>
        </p>
        <p>
          <strong>Last Updated:</strong><br>
          <small>{{ $repair->updated_at->format('M d, Y h:i A') }}</small>
        </p>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra-js')
<script>
// Handle cost form submission
const costForm = document.getElementById('costForm');
if (costForm) {
  costForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const costActual = document.getElementById('costActual').value;

    if (!costActual || parseFloat(costActual) < 0) {
      alert('Please enter a valid cost');
      return;
    }

    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Saving...';

    fetch('{{ route("admin.repairs.updateCost", $repair->id) }}', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ cost_actual: parseFloat(costActual) })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Cost updated successfully');
        location.reload();
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while updating cost');
    })
    .finally(() => {
      button.disabled = false;
      button.innerHTML = '<i class="mdi mdi-check mr-2"></i>Save Cost';
    });
  });
}

// Handle status form submission
const statusForm = document.getElementById('statusForm');
const statusSelect = document.getElementById('status');
const notesTextarea = document.getElementById('notes');
const suggestionBox = document.getElementById('suggestionBox');
const suggestionText = document.getElementById('suggestionText');

// Suggestion messages for each status
const statusSuggestions = {
  'Pending': 'Waiting for device to be brought to our office for inspection and diagnosis.',
  'Received': 'We have received your device and payment of ₦{{ $repair->cost_estimate }} (consultation fee). Device diagnosis will start immediately.',
  'Diagnosed': 'Initial diagnosis completed. Issues identified and documented. Proceeding with repairs.',
  'In Progress': 'Repair work has begun on the identified issues. Estimated completion time: 2-3 days.',
  'Quality Check': 'Device is undergoing comprehensive quality check. Checking all functions and repairs.',
  'Quality Checked': 'Quality check completed successfully. Total repair cost has been set at ₦{{ $repair->cost_actual ?? 0 }}. Awaiting customer approval before final delivery.',
  'Cost Approval': 'Customer has approved the repair cost. Device is ready to be delivered.',
  'Ready for Pickup': 'All approvals completed. Device is ready for customer pickup.',
  'Completed': 'Device has been handed over to the customer. Thank you for choosing our service!'
};

// Show suggestions when status changes
statusSelect.addEventListener('change', function() {
  const selectedStatus = this.value;

  // Show/hide cost form based on status
  const costFormWrapper = document.getElementById('costFormWrapper');
  if (costFormWrapper) {
    const validStatuses = ['Quality Checked', 'Cost Approval', 'Ready for Pickup', 'Completed'];
    if (validStatuses.includes(selectedStatus)) {
      costFormWrapper.style.display = 'block';
    } else {
      costFormWrapper.style.display = 'none';
    }
  }

  if (selectedStatus && statusSuggestions[selectedStatus]) {
    let suggestion = statusSuggestions[selectedStatus];

    // Replace placeholders if needed
    suggestion = suggestion.replace('{cost}', '{{ $repair->cost_estimate }}');
    suggestion = suggestion.replace('{days}', '2-3');

    suggestionText.textContent = suggestion;
    suggestionBox.style.display = 'block';

    // Make suggestion clickable to insert into textarea
    suggestionBox.style.cursor = 'pointer';
    suggestionBox.onclick = function() {
      if (notesTextarea.value) {
        notesTextarea.value += '\n\n' + suggestion;
      } else {
        notesTextarea.value = suggestion;
      }
      notesTextarea.focus();
    };
  } else {
    suggestionBox.style.display = 'none';
  }
});

// Trigger suggestion on page load if status is already selected
if (statusSelect.value) {
  statusSelect.dispatchEvent(new Event('change'));
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const status = document.getElementById('status').value;
  const notes = document.getElementById('notes').value;
  const button = this.querySelector('button[type="submit"]');

  if (!status) {
    alert('Please select a status');
    return;
  }

  button.disabled = true;
  button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Updating...';

  fetch('{{ route("admin.repairs.updateStatus", $repair->id) }}', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ status, notes })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Status updated successfully');
      document.getElementById('notes').value = '';
      location.reload();
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred while updating status');
  })
  .finally(() => {
    button.disabled = false;
    button.innerHTML = 'Update Status';
  });
});
</script>
@endsection
