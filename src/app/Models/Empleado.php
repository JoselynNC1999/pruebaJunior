<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;
    
    protected $table = 'empleados';
    
    protected $fillable = [
        'nombre',
        'correo_electronico',
        'puesto',
    ];
    
    public function registrosAsistencia(): HasMany
    {
        return $this->hasMany(RegistroAsistencia::class);
    }
}