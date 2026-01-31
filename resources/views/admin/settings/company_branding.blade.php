@extends('layouts.admin.app')

@section('title', 'Company Branding')

@section('content')
<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Company Branding</h4>

        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <form action="{{ route('admin.settings.storeCompanyBranding') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <!-- Logo Upload -->
          <div class="form-group">
            <label for="logo">Company Logo</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, GIF, SVG. Max size: 2MB</p>

            @if (!empty($branding) && $branding->logo)
              @if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->logo))
                <div class="alert alert-warning">Stored logo file not found in storage: <strong>{{ $branding->logo }}</strong>. Please re-upload the logo.</div>
              @endif
              <div class="mb-3">
                <p class="text-muted">Current Logo:</p>
                <img src="{{ route('branding.asset', ['path' => $branding->logo]) }}" alt="Company Logo" class="img-fluid" style="max-width: 200px; max-height: 100px;">
              </div>
            @endif

            <div class="custom-file">
              <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
              <label class="custom-file-label" for="logo">Choose logo file</label>
            </div>
            @error('logo')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Favicon Upload -->
          <div class="form-group">
            <label for="favicon">Favicon</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, ICO, GIF. Max size: 512KB</p>

            @if (!empty($branding) && $branding->favicon)
              @if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->favicon))
                <div class="alert alert-warning">Stored favicon file not found in storage: <strong>{{ $branding->favicon }}</strong>. Please re-upload the favicon.</div>
              @endif
              <div class="mb-3">
                <p class="text-muted">Current Favicon:</p>
                <img src="{{ route('branding.asset', ['path' => $branding->favicon]) }}" alt="Favicon" class="img-fluid" style="max-width: 100px; max-height: 100px;">
              </div>
            @endif

            <div class="custom-file">
              <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" id="favicon" name="favicon" accept="image/*">
              <label class="custom-file-label" for="favicon">Choose favicon file</label>
            </div>
            @error('favicon')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Name Logo Upload -->
          <div class="form-group">
            <label for="name_logo">Logo Name/Text Image</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, GIF, SVG. Max size: 1MB</p>

            @if (!empty($branding) && $branding->name_logo)
              @if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->name_logo))
                <div class="alert alert-warning">Stored logo name file not found in storage: <strong>{{ $branding->name_logo }}</strong>. Please re-upload the file.</div>
              @endif
              <div class="mb-3">
                <p class="text-muted">Current Logo Name/Text:</p>
                <img src="{{ route('branding.asset', ['path' => $branding->name_logo]) }}" alt="Logo Name" class="img-fluid" style="max-width: 200px; max-height: 60px;">
              </div>
            @endif

            <div class="custom-file">
              <input type="file" class="custom-file-input @error('name_logo') is-invalid @enderror" id="name_logo" name="name_logo" accept="image/*">
              <label class="custom-file-label" for="name_logo">Choose logo name/text image</label>
            </div>
            @error('name_logo')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- Menu Offer Image Toggle -->
          <div class="form-group mt-4 pt-3 border-top">
            <label for="show_menu_offer_image" class="form-label">Shop Menu Offer Image</label>
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="show_menu_offer_image" name="show_menu_offer_image" value="1" {{ (!empty($branding) && $branding->show_menu_offer_image) ? 'checked' : '' }}>
              <label class="form-check-label" for="show_menu_offer_image">
                Show offer image in Shop dropdown menu
              </label>
            </div>
            <small class="text-muted d-block mt-2">
              Enable this to display the offer icon image in the Shop menu dropdown. Disable to hide it during non-festive periods.
            </small>
          </div>

          <!-- Company Information -->
          <div class="form-group mt-4 pt-3 border-top">
            <h6 class="mb-3">Company Registration Information</h6>
            
            <!-- Company Name -->
            <div class="form-group">
              <label for="company_name">Company Name</label>
              <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" placeholder="e.g., SKYEFACE DIGITAL LIMITED" value="{{ (!empty($branding) && $branding->company_name) ? $branding->company_name : '' }}">
              <small class="text-muted d-block mt-2">Your full registered company name as per CAC registration.</small>
              @error('company_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <!-- CAC Registration Number -->
            <div class="form-group">
              <label for="cac_number">CAC Registration Number</label>
              <input type="text" class="form-control @error('cac_number') is-invalid @enderror" id="cac_number" name="cac_number" placeholder="e.g., 12345678" value="{{ (!empty($branding) && $branding->cac_number) ? $branding->cac_number : '' }}">
              <small class="text-muted d-block mt-2">Your Corporate Affairs Commission registration number (typically 8 digits).</small>
              @error('cac_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <!-- RC Number -->
            <div class="form-group">
              <label for="rc_number">RC Number</label>
              <input type="text" class="form-control @error('rc_number') is-invalid @enderror" id="rc_number" name="rc_number" placeholder="e.g., RC 9053242" value="{{ (!empty($branding) && $branding->rc_number) ? $branding->rc_number : '' }}">
              <small class="text-muted d-block mt-2">Your RC (Registration Certificate) number as per CAC.</small>
              @error('rc_number')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-4">
            <i class="mdi mdi-content-save"></i> Save Branding
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">How to Use</h5>
        <div class="alert alert-info" role="alert">
          <h6>Logo</h6>
          <p>Upload your company logo here. It will be displayed in emails, headers, and other branding areas. Recommended size: 200x100px or wider aspect ratio.</p>

          <h6 class="mt-3">Favicon</h6>
          <p>Upload a favicon (the small icon shown in browser tabs). Recommended size: 32x32px or square. You can use .ico or .png format.</p>

          <h6 class="mt-3">Supported Formats</h6>
          <ul class="mb-0">
            <li>Logo: JPEG, PNG, GIF, SVG (max 2MB)</li>
            <li>Favicon: JPEG, PNG, ICO, GIF (max 512KB)</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
      var label = this.nextElementSibling;
      if (label) {
        label.textContent = this.files && this.files[0] ? this.files[0].name : 'Choose file';
      }
    });
  });
});
</script>
@endsection

