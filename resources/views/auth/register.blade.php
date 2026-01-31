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
                <div class="card-header">Create Account</div>
                <div class="card-body">
                    <x-validation-errors class="mb-3" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input id="fname" name="fname" value="{{ old('fname') }}" required class="form-control" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="mname" class="form-label">Middle Name</label>
                                <input id="mname" name="mname" value="{{ old('mname') }}" class="form-control" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input id="lname" name="lname" value="{{ old('lname') }}" required class="form-control" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" value="{{ old('username') }}" required class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-control" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" name="password" type="password" required class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control" />
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">{!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                                    ]) !!}</label>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}">Already registered?</a>
                            <button class="btn btn-primary" type="submit">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
