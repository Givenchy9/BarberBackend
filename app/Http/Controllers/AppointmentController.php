<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $appointment = Appointment::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'date' => $request->date,
            'time' => $request->time,
            'service' => $request->service,
            'notes' => $request->notes,
        ]);

        return response()->json($appointment, 201);
    }
}
