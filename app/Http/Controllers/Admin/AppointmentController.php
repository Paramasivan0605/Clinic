<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
  public function index(Request $request)
{
    $filters = $request->only(['search','status','service','branch','from','to']);

    $appointments = Appointment::query()
        ->orderBy('date', 'asc')
        ->filter($filters)
        ->paginate(10)
        ->withQueryString();

    return view('admin.appointments.index', [
        'appointments' => $appointments,
        'filters' => $filters,
    ]);
}


    public function events(Request $request)
{
    $start  = $request->query('start');
    $end    = $request->query('end');
    $filters = $request->only(['search', 'status', 'service', 'branch', 'from', 'to']);

    $q = Appointment::query();

    // Optional date range filter for FullCalendar
    if ($start) $q->whereDate('date', '>=', $start);
    if ($end)   $q->whereDate('date', '<=', $end);

    // Apply other filters
    $q->filter($filters);

    return response()->json($q->get()->map->toFullCalendarEvent());
}


    // public function updateStatus(Request $request, Appointment $appointment)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,approved,cancelled'
    //     ]);
    //     $appointment->update(['status' => $request->status]);

    //     return back()->with('ok','Status updated');
    // }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('ok','Appointment deleted');
    }
}
