@extends('layouts.app')
@section('content')
<div class="text-center">
  <h3 class="mb-3">Thank you! 🎉</h3>
  <p>Your appointment request has been submitted. We’ll confirm soon.</p>
  <a href="{{ route('home') }}" class="btn btn-outline-light mt-3">Book another</a>
</div>
@endsection
