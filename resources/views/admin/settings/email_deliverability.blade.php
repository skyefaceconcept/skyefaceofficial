@extends('layouts.admin.app')

@section('title', 'Email Deliverability')

@section('content')
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="card-title">Email Deliverability Dashboard</h4>
            <p class="card-description">Monitor email delivery status across major providers (Gmail, Outlook, Yahoo, Live.com)</p>
          </div>
          <form action="{{ route('admin.settings.runEmailTests') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">
              <i class="mdi mdi-refresh"></i> Run Tests
            </button>
          </form>
        </div>

        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ $message }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $message }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Provider</th>
                <th>Test Email</th>
                <th>Status</th>
                <th>Response Code</th>
                <th>Error / Message</th>
                <th>Last Tested</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($tests as $test)
              <tr>
                <td>
                  <strong>{{ $test->provider }}</strong>
                </td>
                <td>
                  <code>{{ $test->test_email }}</code>
                </td>
                <td>
                  @if ($test->status === 'sent')
                    <span class="badge badge-success">Sent</span>
                  @elseif ($test->status === 'delivered')
                    <span class="badge badge-success">Delivered</span>
                  @elseif ($test->status === 'failed')
                    <span class="badge badge-danger">Failed</span>
                  @elseif ($test->status === 'bounced')
                    <span class="badge badge-warning">Bounced</span>
                  @else
                    <span class="badge badge-secondary">{{ ucfirst($test->status) }}</span>
                  @endif
                </td>
                <td>
                  @if ($test->response_code)
                    <code>{{ $test->response_code }}</code>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @if ($test->error_message)
                    <small class="text-danger">{{ Str::limit($test->error_message, 100) }}</small>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @if ($test->last_tested_at)
                    <small>{{ $test->last_tested_at->format('M d, Y H:i') }}</small>
                    <br>
                    <small class="text-muted">({{ $test->last_tested_at->diffForHumans() }})</small>
                  @else
                    <span class="text-muted">Never</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted">
                  No tests run yet. Click "Run Tests" to start monitoring email delivery.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="alert alert-info mt-4" role="alert">
          <h5 class="alert-heading">How it works:</h5>
          <ul class="mb-0">
            <li>Click <strong>"Run Tests"</strong> to send test emails to each major email provider.</li>
            <li>The system records the SMTP response code and delivery status.</li>
            <li>A status of <strong>"Sent"</strong> (code 250) means the email was accepted by that provider.</li>
            <li>Monitor this dashboard regularly to identify delivery issues with specific providers like Outlook/Live.com.</li>
            <li>Tests can be scheduled daily via a background job for continuous monitoring.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
