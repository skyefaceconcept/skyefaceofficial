@extends('layouts.admin.app')

@section('title', 'Repair Pricing Settings')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Repair Pricing Settings</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Device Repair Diagnosis Fees</h5>
      </div>
      <div class="card-body">
        @if($pricing->count())
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th>Device Type</th>
                  <th>Diagnosis Fee</th>
                  <th>Description</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pricing as $item)
                  <tr id="row-{{ $item->id }}">
                    <td>
                      <strong>{{ $item->device_type }}</strong>
                    </td>
                    <td>
                      <div class="input-group" style="width: 150px;">
                        <span class="input-group-text">₦</span>
                        <input type="number" class="form-control price-input" data-id="{{ $item->id }}" value="{{ $item->price }}" step="0.01" min="0" placeholder="0.00">
                      </div>
                    </td>
                    <td>
                      <small style="color: #666;">{{ $item->description ?? 'No description' }}</small>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-primary save-price-btn" data-id="{{ $item->id }}" style="display: none;">
                        <i class="mdi mdi-check"></i> Save
                      </button>
                      <span class="badge badge-success" style="display: none;" id="success-{{ $item->id }}">Saved</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="alert alert-info">
            <i class="mdi mdi-information"></i> No pricing data found.
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Pricing Information</h5>
      </div>
      <div class="card-body">
        <p><strong>What is a Diagnosis Fee?</strong></p>
        <p style="font-size: 13px; color: #666;">
          The diagnosis fee is the initial charge customers pay when booking a device repair. This covers the cost of inspecting and diagnosing the device issue. Additional charges may apply for parts and labor depending on the diagnosis.
        </p>

        <hr>

        <p><strong>Device Types Available:</strong></p>
        <ul style="font-size: 13px; color: #666; padding-left: 15px;">
          <li>Laptop</li>
          <li>Desktop Computer</li>
          <li>Mobile Phone</li>
          <li>Tablet</li>
          <li>Printer</li>
          <li>Other</li>
        </ul>

        <hr>

        <p><strong>Tips:</strong></p>
        <ul style="font-size: 12px; color: #666; padding-left: 15px;">
          <li>Higher fees for complex devices</li>
          <li>Lower fees for simple devices</li>
          <li>Make sure fees are competitive</li>
          <li>Changes apply immediately</li>
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra-js')
<script>
// Show save button when price changes
document.querySelectorAll('.price-input').forEach(input => {
  const originalValue = input.value;
  
  input.addEventListener('change', function() {
    const id = this.getAttribute('data-id');
    const saveBtn = document.querySelector(`.save-price-btn[data-id="${id}"]`);
    saveBtn.style.display = 'inline-block';
    document.getElementById(`success-${id}`).style.display = 'none';
  });
});

// Save price
document.querySelectorAll('.save-price-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.getAttribute('data-id');
    const priceInput = document.querySelector(`.price-input[data-id="${id}"]`);
    const price = parseFloat(priceInput.value);

    if (price < 0) {
      alert('Price cannot be negative');
      return;
    }

    this.disabled = true;
    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Saving...';

    fetch(`/admin/repairs-pricing/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ price: price })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        this.style.display = 'none';
        document.getElementById(`success-${id}`).style.display = 'inline-block';
        
        setTimeout(() => {
          document.getElementById(`success-${id}`).style.display = 'none';
        }, 3000);
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while saving');
    })
    .finally(() => {
      this.disabled = false;
      this.innerHTML = '<i class="mdi mdi-check"></i> Save';
    });
  });
});
</script>
@endsection
