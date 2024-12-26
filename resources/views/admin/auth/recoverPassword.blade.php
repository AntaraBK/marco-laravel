@extends('admin.auth.layouts.app')
@section('content')
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

      <form action="{{route('reset.password.post')}}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        @session('error')
            <div class="alert alert-danger" role="alert"> 
                {{ $value }}
            </div>
        @endsession
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
          <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
         @error('password_confirmation')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-info btn-block">Change password</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1 text-center">
        <a class="primary-text" href="{{ url('/') }}">Login</a>
      </p>
    </div>
  </div>
@endsection