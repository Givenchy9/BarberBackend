<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'API werkt 🎉'
    ]);
});

Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/admin/appointments', function () {
    return Appointment::orderBy('date')
        ->orderBy('time')
        ->get();
});

Route::put('/admin/appointments/{id}', function ($id, Request $request) {

    $appointment = Appointment::findOrFail($id);

    // 🔒 Check of dit tijdslot al bezet is (behalve huidige afspraak)
    $exists = Appointment::where('date', $request->date)
        ->where('time', $request->time)
        ->where('id', '!=', $id)
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Dit tijdslot is al bezet.'
        ], 422);
    }

    $appointment->update([
        'date' => $request->date,
        'time' => $request->time,
        'service' => $request->service,
        'notes' => $request->notes,
    ]);

    return response()->json([
        'message' => 'Afspraak bijgewerkt'
    ]);
});

Route::delete('/admin/appointments/{id}', function ($id) {
    Appointment::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Afspraak verwijderd'
    ]);
});
