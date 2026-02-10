<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $today = now()->startOfDay();
        $appointmentDate = Carbon::parse($request->date);

        if ($appointmentDate->lessThanOrEqualTo($today)) {
            return response()->json([
                'message' => 'Afspraken moeten minstens 1 dag op voorhand gemaakt worden'
            ], 422);
        }

        $time = date('H:00', strtotime($request->time));

        $exists = Appointment::where('date', $request->date)
            ->where('time', $time)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Dit tijdstip is al bezet'
            ], 409);
        }

        $appointment = Appointment::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => $request->date,
            'time' => $time,
            'service' => $request->service,
            'notes' => $request->notes,
        ]);

        return response()->json($appointment, 201);
    }
}
