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

        Schema::create('sedes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Normal / Ejecutivo
            $table->timestamps();
        });

        Schema::create('fuentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color')->default('gray');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('celular')->index();
            $table->string('genero')->nullable();
            $table->string('ciudad')->nullable();

            $table->foreignId('sede_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrera_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('horario_id')->nullable()->constrained('horarios')->nullOnDelete();

            $table->foreignId('estado_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();

            $table->foreignId('fuente_id')->constrained()->cascadeOnDelete();

            $table->enum('interes_nivel', ['Alto', 'Medio', 'Bajo']);

            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('ultimo_contacto')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });

        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();

            $table->enum('tipo', ['LLAMADA', 'WHATSAPP', 'EMAIL', 'OTRO']);
            $table->enum('resultado', [
                'NO_RESPONDE',
                'INTERESADO',
                'NO_INTERESADO',
                'SEGUIMIENTO'
            ]);

            $table->text('observacion')->nullable();

            $table->timestamp('fecha_contacto')->useCurrent();

            $table->timestamps();
        });

        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();

            $table->timestamp('fecha_recordatorio');
            $table->string('descripcion')->nullable();

            $table->boolean('completado')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
        Schema::dropIfExists('seguimientos');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('estados');
        Schema::dropIfExists('fuentes');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('sedes');

    }
};
