@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-lg rounded-4">
      <div class="card-body">
        <h4 class="mb-3">Book an Appointment</h4>
        <form method="POST" action="{{ route('booking.store') }}" class="row g-3">
          @csrf
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input name="client_name" value="{{ old('client_name') }}" class="form-control" required>
            @error('client_name')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input name="client_email" value="{{ old('client_email') }}" class="form-control" type="email">
            @error('client_email')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="client_phone" value="{{ old('client_phone') }}" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Service</label>
            <select name="service" class="form-select">
              <option value="">Select</option>
              @foreach(['OPD','General Checkup','Consultation'] as $s)
                <option value="{{ $s }}" @selected(old('service')===$s)>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Date</label>
            <input name="date" value="{{ old('date') }}" class="form-control" type="date" required>
            @error('date')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Start Time</label>
            <input name="start_time" value="{{ old('start_time') }}" class="form-control" type="time">
            @error('start_time')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">End Time</label>
            <input name="end_time" value="{{ old('end_time') }}" class="form-control" type="time">
            @error('end_time')<small class="text-danger">{{ $message }}</small>@enderror
          </div>
          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
          </div>
          <div class="col-12">
            <button class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
