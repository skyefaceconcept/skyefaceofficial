@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3">Create Portfolio</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.portfolio.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Portfolio Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                   value="{{ old('title') }}" required>
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                      rows="3" required>{{ old('description') }}</textarea>
                            <small class="text-muted">Brief description for listing pages</small>
                            @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="detailed_description" class="form-label">Detailed Description</label>
                            <textarea class="form-control @error('detailed_description') is-invalid @enderror" id="detailed_description"
                                      name="detailed_description" rows="5">{{ old('detailed_description') }}</textarea>
                            <small class="text-muted">Full details shown on product page</small>
                            @error('detailed_description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Base Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                                {{ ucfirst($cat) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- License Pricing (Hidden by default, enabled for Web category) -->
                        <div class="card mb-3 bg-info bg-opacity-10 border-info" id="licensePricingCard" style="display: none;">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-tag"></i> License Pricing Options</h6>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="enableLicensePricing" role="switch">
                                    <label class="form-check-label text-white" for="enableLicensePricing">Enable</label>
                                </div>
                            </div>
                            <div class="card-body" id="licensePricingBody" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_6months" class="form-label">6 Months Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_6months') is-invalid @enderror"
                                                   id="price_6months" name="price_6months" value="{{ old('price_6months') }}" placeholder="Optional" disabled>
                                            @error('price_6months')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_1year" class="form-label">1 Year Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_1year') is-invalid @enderror"
                                                   id="price_1year" name="price_1year" value="{{ old('price_1year') }}" placeholder="Optional" disabled>
                                            @error('price_1year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price_2years" class="form-label">2 Years Price</label>
                                            <input type="number" step="0.01" class="form-control @error('price_2years') is-invalid @enderror"
                                                   id="price_2years" name="price_2years" value="{{ old('price_2years') }}" placeholder="Optional" disabled>
                                            @error('price_2years')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Leave empty to disable that pricing tier</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail"
                                   name="thumbnail" accept="image/*">
                            <small class="text-muted">Recommended: 500x300px, max 2MB</small>
                            @error('thumbnail')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Create Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Tips</h5>
                    <ul class="small">
                        <li>Use clear, descriptive titles</li>
                        <li>Keep descriptions concise</li>
                        <li>Add high-quality thumbnail image</li>
                        <li>You'll add photos/videos after creation</li>
                        <li>Publish when ready to sell</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const licensePricingCard = document.getElementById('licensePricingCard');
    const licensePricingBody = document.getElementById('licensePricingBody');
    const enableLicensePricingCheckbox = document.getElementById('enableLicensePricing');
    const licenseInputs = document.querySelectorAll('#price_6months, #price_1year, #price_2years');

    // Handle category change
    categorySelect.addEventListener('change', function() {
        const isWebCategory = this.value.toLowerCase() === 'web';

        if (isWebCategory) {
            // Show license pricing card for Web category
            licensePricingCard.style.display = 'block';
            enableLicensePricingCheckbox.checked = false;
            licensePricingBody.style.display = 'none';
            disableAllLicenseInputs();
        } else {
            // Hide license pricing card for other categories
            licensePricingCard.style.display = 'none';
            enableLicensePricingCheckbox.checked = false;
            licensePricingBody.style.display = 'none';
            disableAllLicenseInputs();
        }
    });

    // Handle license pricing checkbox
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

    // Trigger change event on page load if category is already selected
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
