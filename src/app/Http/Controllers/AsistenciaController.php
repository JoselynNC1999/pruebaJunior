<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\RegistroAsistencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AsistenciaController extends Controller
{
    /**
     * Registrar la entrada o salida de un empleado
     */
    public function fichar(Request $request): JsonResponse
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
        ]);
        
        $empleadoId = $request->empleado_id;
        $fechaActual = Carbon::now()->toDateString();
        $horaActual = Carbon::now()->toTimeString();
        
        // Verificar si ya existe un registro para hoy
        $registro = RegistroAsistencia::where('empleado_id', $empleadoId)
            ->where('fecha', $fechaActual)
            ->first();
        
        // Si no existe registro, crear uno nuevo para entrada
        if (!$registro) {
            $registro = RegistroAsistencia::create([
                'empleado_id' => $empleadoId,
                'fecha' => $fechaActual,
                'hora_entrada' => $horaActual,
            ]);
            
            return response()->json([
                'message' => 'Entrada registrada correctamente',
                'registro' => $registro,
            ], 201);
        }
        
        // Si ya existe y tiene hora de entrada pero no de salida, registrar salida
        if ($registro->hora_entrada && !$registro->hora_salida) {
            // Validar que la salida no sea antes que la entrada
            $horaEntrada = Carbon::parse($registro->hora_entrada);
            $horaSalida = Carbon::parse($horaActual);
            
            if ($horaSalida->lt($horaEntrada)) {
                return response()->json([
                    'message' => 'Error: La hora de salida no puede ser anterior a la hora de entrada',
                ], 400);
            }
            
            // Actualizar registro con la salida y calcular horas
            $registro->hora_salida = $horaActual;
            $registro->total_horas = $registro->calcularHorasTrabajadas();
            $registro->save();
            
            return response()->json([
                'message' => 'Salida registrada correctamente',
                'registro' => $registro,
            ]);
        }
        
        // Si ya fichó entrada y salida hoy
        return response()->json([
            'message' => 'Ya has completado tu jornada de hoy',
            'registro' => $registro,
        ], 400);
    }
    
    /**
     * Obtener historial de asistencia de un empleado
     */
    public function historial(Request $request, $empleadoId): JsonResponse
    {
        // Validar que el empleado existe
        if (!Empleado::find($empleadoId)) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }
        
        // Filtrar por rango de fechas si se especifica
        $query = RegistroAsistencia::where('empleado_id', $empleadoId);
        
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $request->validate([
                'fecha_inicio' => 'date',
                'fecha_fin' => 'date|after_or_equal:fecha_inicio',
            ]);
            
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }
        
        $registros = $query->orderBy('fecha', 'desc')->get();
        
        return response()->json([
            'empleado_id' => $empleadoId,
            'registros' => $registros,
        ]);
    }
    
    /**
     * Calcular el total de horas trabajadas en un mes
     */
    public function resumen(Request $request, $empleadoId): JsonResponse
    {
        $request->validate([
            'año' => 'required|integer|min:2000|max:2100',
            'mes' => 'required|integer|min:1|max:12',
        ]);
        
        // Validar que el empleado existe
        if (!Empleado::find($empleadoId)) {
            return response()->json(['message' => 'Empleado no encontrado'], 404);
        }
        
        $año = $request->año;
        $mes = $request->mes;
        
        // Construir fechas de inicio y fin del mes
        $fechaInicio = Carbon::createFromDate($año, $mes, 1)->startOfMonth()->toDateString();
        $fechaFin = Carbon::createFromDate($año, $mes, 1)->endOfMonth()->toDateString();
        
        // Obtener todos los registros del mes que tengan hora de salida
        $registros = RegistroAsistencia::where('empleado_id', $empleadoId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->whereNotNull('hora_salida')
            ->get();
        
        // Calcular el total de horas
        $totalHoras = $registros->sum('total_horas');
        $diasTrabajados = $registros->count();
        
        return response()->json([
            'empleado_id' => $empleadoId,
            'año' => $año,
            'mes' => $mes,
            'dias_trabajados' => $diasTrabajados,
            'total_horas' => $totalHoras,
            'registros' => $registros,
        ]);
    }
}