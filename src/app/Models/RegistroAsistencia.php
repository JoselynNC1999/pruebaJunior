<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroAsistencia extends Model
{
    use HasFactory;
    
    protected $table = 'registros_asistencia';
    
    protected $fillable = [
        'empleado_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'total_horas',
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
        'total_horas' => 'decimal:2',
    ];
    
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
    
    // Método para calcular las horas trabajadas
    public function calcularHorasTrabajadas(): float
    {
        if ($this->hora_entrada && $this->hora_salida) {
            $entrada = \Carbon\Carbon::parse($this->hora_entrada);
            $salida = \Carbon\Carbon::parse($this->hora_salida);
            
            // Cálculo en horas con 2 decimales
            return round($entrada->diffInMinutes($salida) / 60, 2);
        }
        
        return 0;
    }
}