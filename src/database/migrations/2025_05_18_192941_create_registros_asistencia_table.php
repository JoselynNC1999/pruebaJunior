<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registros_asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados');
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->decimal('total_horas', 5, 2)->nullable();
            $table->timestamps();
            
            // Índice compuesto para evitar duplicados de asistencia en el mismo día
            $table->unique(['empleado_id', 'fecha']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_asistencia');
    }
};
