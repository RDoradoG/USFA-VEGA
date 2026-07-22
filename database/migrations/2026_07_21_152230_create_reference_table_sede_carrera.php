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
        Schema::table('sedes', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('activo');
        });

        Schema::table('carreras', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });

        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
