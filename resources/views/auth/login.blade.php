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
                <div class="card-header">Log In</div>
                <div class="card-body">
                    <x-validation-errors class="mb-3" />

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" value="{{ old('username') }}" required autofocus class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password" required class="form-control" />
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Forgot your password?</a>
                            @endif

                            <button class="btn btn-primary" type="submit">Log in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
