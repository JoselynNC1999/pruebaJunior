<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para el sistema de fichaje
Route::post('/asistencia/fichar', [AsistenciaController::class, 'fichar']);
Route::get('/asistencia/historial/{empleadoId}', [AsistenciaController::class, 'historial']);
Route::get('/asistencia/resumen/{empleadoId}', [AsistenciaController::class, 'resumen']);