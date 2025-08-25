@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
  <!-- Calendar -->
  <div class="lg:col-span-8">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
        <div class="flex items-center justify-between">
          <h5 class="text-lg font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Calendar
          </h5>
        </div>
      </div>
      <div class="p-6">
        <div id="calendar" class="rounded-lg"></div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="lg:col-span-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-4">
        <h6 class="text-lg font-semibold flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          Filters
        </h6>
      </div>
      <div class="p-6">
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="space-y-4">
          <div>
            <input type="text" 
                   name="search" 
                   value="{{ $filters['search'] ?? '' }}" 
                   placeholder="Search by appointment no, client, email, service..." 
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                <option value="">All Status</option>
                @foreach(['pending','approved','cancelled'] as $st)
                  <option value="{{ $st }}" @selected(($filters['status'] ?? '')===$st)>{{ ucfirst($st) }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <select name="service" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                <option value="">All Services</option>
                @foreach(['OPD','General Checkup','Consultation'] as $s)
                  <option value="{{ $s }}" @selected(($filters['service'] ?? '')===$s)>{{ $s }}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <input type="date" 
                     name="from" 
                     value="{{ $filters['from'] ?? '' }}" 
                     class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
            </div>
            <div>
              <input type="date" 
                     name="to" 
                     value="{{ $filters['to'] ?? '' }}" 
                     class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
            </div>
          </div>
          
          <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-200 transition-all duration-200 flex items-center justify-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              Apply
            </button>
            <a href="{{ route('admin.appointments.index') }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all duration-200 text-center">
              Reset
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Appointments Table -->
  <div class="col-span-12">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-4">
        <h5 class="text-lg font-semibold flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          Appointments
        </h5>
      </div>
      <div class="overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @forelse($appointments as $a)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-bold text-blue-600 text-lg">{{ $a->appointment_no }}</span>
                    <div class="text-xs text-gray-400 mt-1">ID: {{ $a->id }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="font-semibold text-gray-900">{{ $a->client_name }}</div>
                    <div class="text-sm text-gray-500 mt-1">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        {{ $a->client_email }}
                      </div>
                      @if($a->client_phone)
                        <div class="flex items-center mt-1">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                          </svg>
                          {{ $a->client_phone }}
                        </div>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                      {{ $a->service ?: 'Not specified' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <span class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    @php
                      $st = $a->start_time ? \Carbon\Carbon::parse($a->start_time)->format('H:i') : '—';
                      $et = $a->end_time ? \Carbon\Carbon::parse($a->end_time)->format('H:i') : '—';
                    @endphp
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span class="text-gray-900 font-medium">{{ $st }} - {{ $et }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                      @if($a->status==='approved') bg-green-100 text-green-800 
                      @elseif($a->status==='cancelled') bg-red-100 text-red-800 
                      @else bg-yellow-100 text-yellow-800 @endif">
                      <span class="w-2 h-2 mr-2 rounded-full
                        @if($a->status==='approved') bg-green-400 
                        @elseif($a->status==='cancelled') bg-red-400 
                        @else bg-yellow-400 @endif">
                      </span>
                      {{ ucfirst($a->status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <form method="POST" action="{{ route('admin.appointments.destroy',$a) }}" 
                            onsubmit="return confirm('Delete appointment #{{ $a->id }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-red-200">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                      <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                      </svg>
                      <p class="text-gray-500 text-lg font-medium">No appointments found</p>
                      <p class="text-gray-400 text-sm mt-1">Try adjusting your search filters</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($appointments->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
              Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $appointments->total() }} results
            </div>
            <div class="flex items-center space-x-2">
              {{ $appointments->links() }}
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tailwindcss.com"></script>
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
    },
    eventContent: function(arg) {
      // Only show the title (name), no dot or extra color box
      return { html: `<span>${arg.event.title}</span>` };
    }
  });
  calendar.render();
});
</script>

@endpush