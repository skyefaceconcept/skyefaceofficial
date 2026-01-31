@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3">Edit Portfolio: {{ $portfolio->title }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.portfolio.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Portfolio Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Portfolio Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                   value="{{ old('title', $portfolio->title) }}" required>
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                      rows="3" required>{{ old('description', $portfolio->description) }}</textarea>
                            <small class="text-muted">Brief description for listing pages</small>
                            @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="detailed_description" class="form-label">Detailed Description</label>
                            <textarea class="form-control @error('detailed_description') is-invalid @enderror" id="detailed_description"
                                      name="detailed_description" rows="5">{{ old('detailed_description', $portfolio->detailed_description) }}</textarea>
                            <small class="text-muted">Full details shown on product page</small>
                            @error('detailed_description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Base Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $portfolio->price) }}" required>
                                    @error('price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $portfolio->category) === $cat ? 'selected' : '' }}>
                                                {{ ucfirst($cat) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- License Pricing (Hidden by default, enabled for Web category) -->
                        <div class="card mb-3 bg-info bg-opacity-10 border-info" id="licensePricingCard" style="{{ strtolower($portfolio->category) === 'web' ? '' : 'display: none;' }}">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-tag"></i> License Pricing Options</h6>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="enableLicensePricing" role="switch" {{ ($portfolio->price_6months || $portfolio->price_1year || $portfolio->price_2years) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="enableLicensePricing">Enable</label>
                                </div>
                            </div>
                            <div class="card-body" id="licensePricingBody" style="{{ ($portfolio->price_6months || $portfolio->price_1year || $portfolio->price_2years) ? '' : 'display: none;' }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_6months" class="form-label">6 Months Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_6months') is-invalid @enderror"
                                                   id="price_6months" name="price_6months" value="{{ old('price_6months', $portfolio->price_6months) }}" placeholder="Optional" {{ ($portfolio->price_6months || $portfolio->price_1year || $portfolio->price_2years) ? '' : 'disabled' }}>
                                            @error('price_6months')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_1year" class="form-label">1 Year Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_1year') is-invalid @enderror"
                                                   id="price_1year" name="price_1year" value="{{ old('price_1year', $portfolio->price_1year) }}" placeholder="Optional" {{ ($portfolio->price_6months || $portfolio->price_1year || $portfolio->price_2years) ? '' : 'disabled' }}>
                                            @error('price_1year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_2years" class="form-label">2 Years Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_2years') is-invalid @enderror"
                                                   id="price_2years" name="price_2years" value="{{ old('price_2years', $portfolio->price_2years) }}" placeholder="Optional" {{ ($portfolio->price_6months || $portfolio->price_1year || $portfolio->price_2years) ? '' : 'disabled' }}>
                                            @error('price_2years')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Leave empty to disable that pricing tier</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                            @if($portfolio->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                                         style="max-width: 200px; max-height: 150px; border-radius: 4px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail"
                                   name="thumbnail" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            @error('thumbnail')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', $portfolio->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $portfolio->status) === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $portfolio->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <!-- Festive Discount Section -->
                        <div class="card mb-3 bg-success bg-opacity-10 border-success">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-gift"></i> Festive Discount (Holiday Sales)</h6>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="festive_discount_enabled" name="festive_discount_enabled" role="switch" value="1" {{ old('festive_discount_enabled', $portfolio->festive_discount_enabled) ? 'checked' : '' }} onchange="toggleFestiveDivContent()">
                                    <label class="form-check-label text-white" for="festive_discount_enabled">Enable Discount</label>
                                </div>
                            </div>
                            <div class="card-body" id="festiveDivContent" style="{{ old('festive_discount_enabled', $portfolio->festive_discount_enabled) ? '' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="festive_discount_percentage" class="form-label">Discount Percentage (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('festive_discount_percentage') is-invalid @enderror"
                                               id="festive_discount_percentage" name="festive_discount_percentage"
                                               value="{{ old('festive_discount_percentage', $portfolio->festive_discount_percentage ?? 0) }}"
                                               placeholder="e.g., 50"
                                               {{ old('festive_discount_enabled', $portfolio->festive_discount_enabled) ? '' : 'disabled' }}>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter discount percentage (e.g., 50 for 50% off)</small>
                                    @error('festive_discount_percentage')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                </div>
                                <div class="alert alert-info alert-sm mb-0" id="discountPreview">
                                    <small id="discountPreviewText"></small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Portfolio Info</h5>
                    <ul class="small mb-0">
                        <li><strong>Created:</strong> {{ $portfolio->created_at->format('M d, Y') }}</li>
                        <li><strong>Views:</strong> {{ $portfolio->view_count }}</li>
                        <li><strong>Status:</strong> {{ ucfirst($portfolio->status) }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footages Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Photos & Videos ({{ $footages->count() }})</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addFootageModal">
                        <i class="fas fa-plus"></i> Add Footage
                    </button>
                </div>
                <div class="card-body">
                    @if($footages->count() > 0)
                        <div id="footages-container" class="row">
                            @foreach($footages as $footage)
                                <div class="col-md-6 mb-3" data-footage-id="{{ $footage->id }}">
                                    <div class="card h-100">
                                        <div style="background: #ddd; height: 150px; overflow: hidden; border-radius: 4px 4px 0 0; position: relative;">
                                            @if($footage->isPhoto())
                                                <img src="{{ asset('storage/' . $footage->media_path) }}" alt="{{ $footage->title }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <video style="width: 100%; height: 100%; object-fit: cover;">
                                                    <source src="{{ asset('storage/' . $footage->media_path) }}" type="video/mp4">
                                                </video>
                                                <span class="badge bg-primary position-absolute top-2 start-2">
                                                    <i class="fas fa-video"></i> Video
                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $footage->title ?? 'Untitled' }}</h6>
                                            <p class="card-text small">{{ Str::limit($footage->description ?? 'No description', 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Order: {{ $footage->display_order }}</small>
                                                <form action="{{ route('admin.portfolio.footage.delete', $footage) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this footage?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No footages yet. Add your first photo or video!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Footage Modal -->
<div class="modal fade" id="addFootageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Footage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.portfolio.footage.add', $portfolio) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="photo">Photo</option>
                            <option value="video">Video</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="media" class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="media" name="media" required
                               accept="image/*,video/*">
                        <small class="text-muted">Max 20MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Homepage Design">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Brief description of this footage"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Footage</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Make footages draggable for reordering
@if($footages->count() > 1)
    new Sortable(document.getElementById('footages-container'), {
        animation: 150,
        ghostClass: 'bg-light',
        onEnd: function() {
            const order = Array.from(document.querySelectorAll('[data-footage-id]')).map(el => el.dataset.footageId);

            fetch('{{ route("admin.portfolio.reorder", $portfolio) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ order: order })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    console.log('Footages reordered');
                }
            });
        }
    });
@endif

// Handle modal for Bootstrap 4
document.getElementById('addFootageModal').addEventListener('show.bs.modal', function() {
    console.log('Modal opened');
});

// Handle license pricing for Web category
const categorySelect = document.getElementById('category');
const licensePricingCard = document.getElementById('licensePricingCard');
const licensePricingBody = document.getElementById('licensePricingBody');
const enableLicensePricingCheckbox = document.getElementById('enableLicensePricing');
const licenseInputs = document.querySelectorAll('#price_6months, #price_1year, #price_2years');

categorySelect.addEventListener('change', function() {
    const isWebCategory = this.value.toLowerCase() === 'web';

    if (isWebCategory) {
        licensePricingCard.style.display = 'block';
        if (!enableLicensePricingCheckbox.checked) {
            licensePricingBody.style.display = 'none';
            disableAllLicenseInputs();
        }
    } else {
        licensePricingCard.style.display = 'none';
        enableLicensePricingCheckbox.checked = false;
        licensePricingBody.style.display = 'none';
        disableAllLicenseInputs();
    }
});

enableLicensePricingCheckbox.addEventListener('change', function() {
    if (this.checked) {
        licensePricingBody.style.display = 'block';
        enableAllLicenseInputs();
    } else {
        licensePricingBody.style.display = 'none';
        disableAllLicenseInputs();
    }
});

function enableAllLicenseInputs() {
    licenseInputs.forEach(input => {
        input.disabled = false;
    });
}

function disableAllLicenseInputs() {
    licenseInputs.forEach(input => {
        input.disabled = true;
    });
}

// Handle festive discount toggle
const festiveDivToggle = document.getElementById('festive_discount_enabled');
const festiveDivContent = document.getElementById('festiveDivContent');
const festivePercentageInput = document.getElementById('festive_discount_percentage');
const priceInput = document.getElementById('price');
const discountPreviewText = document.getElementById('discountPreviewText');

function toggleFestiveDivContent() {
    if (festiveDivToggle.checked) {
        festiveDivContent.style.display = 'block';
        festivePercentageInput.disabled = false;
        updateDiscountPreview();
    } else {
        festiveDivContent.style.display = 'none';
        festivePercentageInput.disabled = true;
        festivePercentageInput.value = '0';
        discountPreviewText.textContent = '';
    }
}

festiveDivToggle.addEventListener('change', toggleFestiveDivContent);

// Update discount preview when percentage changes
festivePercentageInput.addEventListener('input', function() {
    updateDiscountPreview();
});

// Update discount preview when price changes
priceInput.addEventListener('input', function() {
    if (festiveDivToggle.checked) {
        updateDiscountPreview();
    }
});

function updateDiscountPreview() {
    const price = parseFloat(priceInput.value) || 0;
    const discountPercentage = parseFloat(festivePercentageInput.value) || 0;

    if (price > 0 && discountPercentage > 0) {
        const discountAmount = (price * discountPercentage) / 100;
        const finalPrice = price - discountAmount;
        discountPreviewText.innerHTML = `<strong>Preview:</strong> Original Price: ₦${price.toLocaleString()} → Final Price: ₦${finalPrice.toLocaleString()} (Save ₦${discountAmount.toLocaleString()})`;
    } else if (discountPercentage > 0) {
        discountPreviewText.textContent = `${discountPercentage}% discount will be applied to the base price`;
    } else {
        discountPreviewText.textContent = '';
    }
}

// Initial preview on page load if discount is enabled
if (festiveDivToggle && festiveDivToggle.checked) {
    updateDiscountPreview();
}
</script>
@endpush
@endsection
