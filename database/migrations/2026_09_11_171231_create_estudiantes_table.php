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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('id_persona');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefonos')->nullable();
            $table->string('email')->nullable();
            $table->string('genero')->nullable();
            $table->string('codigo_pais')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();
        });

        Schema::create('estudiante_carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('desercion_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
        });

        Schema::create('seguimiento_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
        });

        Schema::create('seguimiento_estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color')->default('gray');
            $table->integer('orden')->default(0);
        });

        Schema::create('seguimiento_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('seguimiento_estado_id')->constrained('seguimiento_estados')->cascadeOnDelete();
            $table->text('motivo')->nullable();
            $table->timestamp('ultimo_contacto')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('abandonos_deserciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seguimiento_estudiante_id')->constrained('seguimiento_estudiantes')->cascadeOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->foreignId('seguimiento_tipo_id')->constrained('seguimiento_tipos')->cascadeOnDelete();
            //$table->foreignId('seguimiento_estado_id')->constrained('seguimiento_estados')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('seguimiento_deserciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abandono_desercion_id')->constrained('abandonos_deserciones')->cascadeOnDelete();
            $table->foreignId('desercion_tipo_id')->nullable()->constrained('desercion_tipos')->nullOnDelete();
            $table->string('materia');
            $table->integer('nota')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_deserciones');
        Schema::dropIfExists('abandonos_deserciones');
        Schema::dropIfExists('seguimiento_estudiantes');
        Schema::dropIfExists('seguimiento_estados');
        Schema::dropIfExists('seguimiento_tipos');
        Schema::dropIfExists('desercion_tipos');
        Schema::dropIfExists('estudiante_carreras');
        Schema::dropIfExists('estudiantes');
    }
};
