@extends('layouts.staradmin_auth')

@section('extra-css')
<style>
  body {
    background: linear-gradient(135deg, #5768f3 0%, #1c45ef 100%);
    min-height: 100vh;
  }
  .card {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  }
  .card-header {
    background: linear-gradient(135deg, #5768f3 0%, #1c45ef 100%);
    color: white;
    font-weight: 600;
    border: none;
  }
</style>
@endsection

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verify Your Email</div>
                <div class="card-body">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">A new verification link has been sent to your email address.</div>
                    @endif

                    <p>Please verify your email address by clicking the link we emailed to you. If you didn't receive it, you can request another below.</p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Resend Verification Email</button>
                        <a href="{{ route('profile.show') }}" class="btn btn-link ms-2">Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                            @csrf
                            <button class="btn btn-link">Log Out</button>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
