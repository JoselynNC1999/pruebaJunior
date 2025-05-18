<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\RegistroAsistencia;


class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empleado::create([
            'nombre' => 'Joselyn Ninahuaman',
            'correo_electronico' => 'joselyn@gmail.com',
            'puesto' => 'Desarrolladora',
        ]);
        
        Empleado::create([
            'nombre' => 'Aaron Garcia',
            'correo_electronico' => 'aaronGarcia@test.com',
            'puesto' => 'Desarrollador Senior',
        ]);
        
        Empleado::create([
            'nombre' => 'Carlos Rodríguez',
            'correo_electronico' => 'carlos.rodriguez@gmail.com',
            'puesto' => 'Project Manager',
        ]);
    }
}
