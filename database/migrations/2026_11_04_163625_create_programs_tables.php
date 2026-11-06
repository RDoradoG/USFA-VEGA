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
        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });

        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('anio');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('carrera_simbolo', function (Blueprint $table) {
            $table->id();
            $table->string('simbolo');
            $table->foreignId('carrera_id')->constrained('carreras')->cascadeOnDelete();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->string('external_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrera_simbolo');
        Schema::dropIfExists('programas');
        Schema::table('carreras', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('activo');
        });
    }
};
