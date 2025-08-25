@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-lg rounded-4">
      <div class="card-body">
        <h4 class="mb-3">Admin Login</h4>
        <form method="POST" action="{{ route('admin.login.submit') }}" class="row g-3">
          @csrf
          <div class="col-12">
            <label class="form-label">Email</label>
            <input name="email" value="{{ old('email') }}" type="email" class="form-control" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-12">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="col-12 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rem">
            <label class="form-check-label" for="rem">Remember me</label>
          </div>
          <div class="col-12">
            <button class="btn btn-primary w-100">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
