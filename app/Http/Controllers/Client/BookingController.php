<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        return view('client.booking');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name'  => 'required|string|max:120',
            'client_email' => 'nullable|email|max:120',
            'client_phone' => 'nullable|string|max:20',
            'service'      => 'nullable|string|max:120',
            'date'         => 'required|date|after_or_equal:today',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i|after:start_time',
            'notes'        => 'nullable|string|max:1000',
        ]);

        // Optional: prevent double-booking on exact slot
        $exists = Appointment::whereDate('date', $data['date'])
            ->when($data['start_time'] ?? null, fn($q) => $q->where('start_time', $data['start_time']))
            ->exists();
        if ($exists) {
            return back()->withErrors(['date' => 'Selected slot is already booked. Choose another.'])->withInput();
        }

        Appointment::create($data);

        return redirect()->route('booking.thanks');
    }

    public function thanks()
    {
        return view('client.thanks');
    }
}
