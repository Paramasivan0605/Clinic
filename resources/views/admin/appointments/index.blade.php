@extends('layouts.app')

@section('content')
<div class="row g-4">
  <!-- Calendar -->
  <div class="col-lg-8">
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between rounded-top-4">
        <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i> Calendar</h5>
      </div>
      <div class="card-body">
        <div id="calendar"></div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="col-lg-4">
    <div class="accordion shadow rounded-4" id="filterAccordion">
      <div class="accordion-item border-0">
        <h2 class="accordion-header">
          <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#filters">
            <i class="bi bi-funnel me-2"></i> Filters
          </button>
        </h2>
        <div id="filters" class="accordion-collapse collapse show">
          <div class="accordion-body">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3">
              <div class="col-12">
               <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                   placeholder="Search by appointment no, client, email, service..." class="form-control">

              </div>
              <div class="col-md-6">
                <select name="status" class="form-select">
                  <option value="">Status</option>
                  @foreach(['pending','approved','cancelled'] as $st)
                    <option value="{{ $st }}" @selected(($filters['status'] ?? '')===$st)>{{ ucfirst($st) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <select name="service" class="form-select">
                  <option value="">Service</option>
                  @foreach(['OPD','General Checkup','Consultation'] as $s)
                    <option value="{{ $s }}" @selected(($filters['service'] ?? '')===$s)>{{ $s }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" placeholder="From">
              </div>
              <div class="col-md-6">
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" placeholder="To">
              </div>
              <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary w-50"><i class="bi bi-search me-1"></i> Apply</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary w-50">Reset</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Appointments Table -->
  <div class="col-12">
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-dark text-white rounded-top-4">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i> Appointments</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th><th>Client</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($appointments as $a)
                <tr>
                  <td>
  <span class="fw-bold text-primary">{{ $a->appointment_no }}</span>
  <div class="small text-muted">{{ $a->id }}</div> <!-- optional: still show DB id -->
</td>
                  <td>
                    <div class="fw-semibold">{{ $a->client_name }}</div>
                    <div class="small text-muted">{{ $a->client_email }} {{ $a->client_phone ? '• '.$a->client_phone : '' }}</div>
                  </td>
                  <td>{{ $a->service ?: '-' }}</td>
                  <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                  <td>
                    @php
                      $st = $a->start_time ? \Carbon\Carbon::parse($a->start_time)->format('H:i') : '—';
                      $et = $a->end_time ? \Carbon\Carbon::parse($a->end_time)->format('H:i') : '—';
                    @endphp
                    {{ $st }} - {{ $et }}
                  </td>
                  <td>
                    <span class="badge rounded-pill 
                      @if($a->status==='approved') bg-success 
                      @elseif($a->status==='cancelled') bg-danger 
                      @else bg-warning text-dark @endif">
                      {{ ucfirst($a->status) }}
                    </span>
                  </td>
                  <td class="text-nowrap">
                    <div class="d-flex gap-2">
                      <form method="POST" action="{{ route('admin.appointments.destroy',$a) }}" 
                            onsubmit="return confirm('Delete appointment #{{ $a->id }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No appointments found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="p-3">
          {{ $appointments->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(el, {
    themeSystem: 'bootstrap5', 
    initialView: 'dayGridMonth',
    height: 600,
    events: '{{ route('admin.calendar.events') }}',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    }
  });
  calendar.render();
});
</script>
@endpush
