@extends('layouts.admin.app')

@section('title', 'System Settings')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>System Settings</h1>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<div class="row mt-3">
  <!-- Settings Sidebar -->
  <div class="col-md-3">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Settings Menu</h5>
      </div>
      <div class="list-group list-group-flush">
        <a href="#application-settings" class="list-group-item list-group-item-action active" data-toggle="list">
          <i class="fas fa-cog mr-2"></i> Application Settings
        </a>
        <a href="{{ route('admin.settings.company_branding') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-paint-brush mr-2"></i> Company Branding
        </a>
        <a href="{{ route('admin.settings.payment_processors') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-credit-card mr-2"></i> Payment Processors
        </a>
        <a href="{{ route('admin.settings.email_deliverability') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-envelope mr-2"></i> Email Configuration
        </a>
        <a href="{{ route('admin.settings.migrations') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-database mr-2"></i> Migrations
        </a>
        @permission('backup_system')
        <a href="{{ route('admin.settings.backup') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-download mr-2"></i> Backup & Restore
        </a>
        @endpermission
      </div>
    </div>

    <!-- Quick Info Card -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">System Info</h5>
      </div>
      <div class="card-body small">
        <p class="mb-2">
          <strong>Laravel:</strong> <br>
          <span class="text-muted">{{ app()->version() }}</span>
        </p>
        <p class="mb-2">
          <strong>PHP:</strong> <br>
          <span class="text-muted">{{ phpversion() }}</span>
        </p>
        <p class="mb-2">
          <strong>Environment:</strong> <br>
          <span class="badge badge-{{ config('app.env') === 'production' ? 'danger' : 'warning' }}">
            {{ strtoupper(config('app.env')) }}
          </span>
        </p>
        <p class="mb-0">
          <strong>Debug:</strong> <br>
          <span class="badge badge-{{ config('app.debug') ? 'danger' : 'success' }}">
            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
          </span>
        </p>
      </div>
    </div>
  </div>

  <div class="col-md-9">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Application Settings</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.settings.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ $settings['company_name'] ?? config('company.name') }}" required>
            @error('company_name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="app_name">Application Name</label>
            <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? '' }}" required>
            @error('app_name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="app_url">Application URL</label>
            <input type="url" class="form-control @error('app_url') is-invalid @enderror" id="app_url" name="app_url" value="{{ $settings['app_url'] ?? '' }}" required>
            @error('app_url')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="site_mode">Site Mode</label>
            <select class="form-control @error('site_mode') is-invalid @enderror" id="site_mode" name="site_mode">
              <option value="live" {{ ($settings['site_mode'] ?? 'live') === 'live' ? 'selected' : '' }}>Live</option>
              <option value="maintenance" {{ ($settings['site_mode'] ?? '') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
              <option value="under_construction" {{ ($settings['site_mode'] ?? '') === 'under_construction' ? 'selected' : '' }}>Under Construction</option>
            </select>
            @error('site_mode')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="site_title">Site Banner Title</label>
            <input type="text" class="form-control @error('site_title') is-invalid @enderror" id="site_title" name="site_title" value="{{ $settings['site_title'] ?? '' }}">
            <small class="form-text text-muted">Short title shown on the maintenance/under construction pages.</small>
            @error('site_title')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="site_message">Site Banner Message</label>
            <textarea class="form-control @error('site_message') is-invalid @enderror" id="site_message" name="site_message" rows="3">{{ $settings['site_message'] ?? '' }}</textarea>
            <small class="form-text text-muted">A brief message displayed to visitors when the site is not live.</small>
            @error('site_message')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="app_timezone">Timezone</label>
            <select class="form-control @error('app_timezone') is-invalid @enderror" id="app_timezone" name="app_timezone" required>
              <option value="">-- Select Timezone --</option>
              @foreach($timezones as $tz)
                <option value="{{ $tz }}" {{ ($settings['app_timezone'] ?? '') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
              @endforeach
            </select>
            @error('app_timezone')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="app_locale">Locale</label>
            <select class="form-control @error('app_locale') is-invalid @enderror" id="app_locale" name="app_locale" required>
              <option value="en" {{ ($settings['app_locale'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
              <option value="es" {{ ($settings['app_locale'] ?? '') === 'es' ? 'selected' : '' }}>Spanish</option>
              <option value="fr" {{ ($settings['app_locale'] ?? '') === 'fr' ? 'selected' : '' }}>French</option>
              <option value="de" {{ ($settings['app_locale'] ?? '') === 'de' ? 'selected' : '' }}>German</option>
              <option value="it" {{ ($settings['app_locale'] ?? '') === 'it' ? 'selected' : '' }}>Italian</option>
              <option value="pt" {{ ($settings['app_locale'] ?? '') === 'pt' ? 'selected' : '' }}>Portuguese</option>
            </select>
            @error('app_locale')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="support_email">Support Email</label>
            <input type="email" class="form-control @error('support_email') is-invalid @enderror" id="support_email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" required>
            @error('support_email')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="support_phone">Support Phone</label>
            <input type="text" class="form-control @error('support_phone') is-invalid @enderror" id="support_phone" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}" required>
            @error('support_phone')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <hr class="my-4">
          <h6>Mail Configuration</h6>

          <div class="form-group">
            <label for="mail_mailer">Mail Driver</label>
            <select class="form-control @error('mail_mailer') is-invalid @enderror" id="mail_mailer" name="mail_mailer" required>
              <option value="">-- Select Driver --</option>
              <option value="smtp" {{ ($settings['mail_mailer'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
              <option value="mailgun" {{ ($settings['mail_mailer'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
              <option value="postmark" {{ ($settings['mail_mailer'] ?? '') === 'postmark' ? 'selected' : '' }}>Postmark</option>
              <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
              <option value="log" {{ ($settings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log</option>
            </select>
            @error('mail_mailer')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_host">Mail Host</label>
            <input type="text" class="form-control @error('mail_host') is-invalid @enderror" id="mail_host" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}">
            @error('mail_host')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_port">Mail Port</label>
            <input type="number" class="form-control @error('mail_port') is-invalid @enderror" id="mail_port" name="mail_port" value="{{ $settings['mail_port'] ?? '' }}">
            @error('mail_port')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_username">Mail Username</label>
            <input type="text" class="form-control @error('mail_username') is-invalid @enderror" id="mail_username" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}">
            @error('mail_username')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_password">Mail Password</label>
            <input type="password" class="form-control @error('mail_password') is-invalid @enderror" id="mail_password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}">
            @error('mail_password')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_encryption">Encryption</label>
            <select class="form-control @error('mail_encryption') is-invalid @enderror" id="mail_encryption" name="mail_encryption">
              <option value="">-- None --</option>
              <option value="tls" {{ ($settings['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
              <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
            </select>
            @error('mail_encryption')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_from_address">From Address</label>
            <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" required>
            @error('mail_from_address')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="mail_from_name">From Name</label>
            <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" id="mail_from_name" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" required>
            @error('mail_from_name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          @permission('edit_settings')
          <button type="submit" class="btn btn-primary">Save Settings</button>
          @endpermission
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  try {
    // Disable duplicate submissions and show saving state
    var form = document.querySelector('form[action="{{ route('admin.settings.store') }}"]');
    if (form) {
      var saveBtn = form.querySelector('button[type="submit"]');
      form.addEventListener('submit', function (e) {
        if (saveBtn && saveBtn.dataset.submitted === '1') {
          e.preventDefault();
          return false;
        }
        if (saveBtn) {
          saveBtn.dataset.submitted = '1';
          saveBtn.disabled = true;
          // preserve original text
          saveBtn.dataset.original = saveBtn.innerHTML;
          saveBtn.innerHTML = 'Saving...';
        }
      });
    }

    // If a success alert is present, reload the page after 10 seconds
    var successAlert = document.querySelector('.alert-success');
    if (successAlert) {
      // show countdown in the alert (optional)
      var countdown = 10;
      var countdownNode = document.createElement('span');
      countdownNode.style.marginLeft = '8px';
      countdownNode.style.fontWeight = '600';
      countdownNode.textContent = 'Reloading in ' + countdown + 's...';
      successAlert.appendChild(countdownNode);

      var timer = setInterval(function () {
        countdown -= 1;
        if (countdownNode) countdownNode.textContent = 'Reloading in ' + countdown + 's...';
        if (countdown <= 0) {
          clearInterval(timer);
          location.reload();
        }
      }, 1000);
    }
  } catch (err) {
    console.error('Settings page script error', err);
  }
});
</script>
@endpush
