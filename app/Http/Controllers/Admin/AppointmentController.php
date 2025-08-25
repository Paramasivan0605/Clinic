<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search','status','service','from','to']);
        $appointments = Appointment::query()
            ->latest('date')
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
        // Optional date window filter for FullCalendar
        $start = $request->query('start');
        $end   = $request->query('end');

        $q = Appointment::query();
        if ($start) $q->whereDate('date', '>=', $start);
        if ($end)   $q->whereDate('date', '<=', $end);

        return response()->json(
            $q->get()->map->toFullCalendarEvent()
        );
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,cancelled'
        ]);
        $appointment->update(['status' => $request->status]);

        return back()->with('ok','Status updated');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('ok','Appointment deleted');
    }
}
