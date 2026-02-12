<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\ClosedDay;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'API werkt 🎉'
    ]);
});

Route::middleware(\App\Http\Middleware\AdminTokenMiddleware::class)->group(function () {

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

    Route::get('/admin/closed-days', function () {
        return ClosedDay::orderBy('date')->get();
    });
    Route::post('/admin/closed-days', function (Request $request) {

        if (ClosedDay::where('date', $request->date)->exists()) {
            return response()->json([
                'message' => 'Deze dag is al gesloten.'
            ], 422);
        }

        ClosedDay::create([
            'date' => $request->date
        ]);

        return response()->json([
            'message' => 'Dag gesloten.'
        ]);
    });
    Route::delete('/admin/closed-days/{date}', function ($date) {
        ClosedDay::where('date', $date)->delete();

        return response()->json([
            'message' => 'Dag heropend.'
        ]);
    });
});

Route::post('/appointments', [AppointmentController::class, 'store']);





Route::get('/closed-days', function () {
    return \App\Models\ClosedDay::pluck('date');
});
