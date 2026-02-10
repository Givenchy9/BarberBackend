<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'API werkt 🎉'
    ]);
});

Route::post('/appointments', [AppointmentController::class, 'store']);
