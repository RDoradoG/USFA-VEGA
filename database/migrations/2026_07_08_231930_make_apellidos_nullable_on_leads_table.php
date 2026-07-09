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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable()->change();
            $table->string('apellido_materno')->nullable()->change();
            $table->string('id_reserva')->nullable()->after('monto_inscripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable(false)->change();
            $table->string('apellido_materno')->nullable(false)->change();
            $table->dropColumn('id_reserva');
        });
    }
};
