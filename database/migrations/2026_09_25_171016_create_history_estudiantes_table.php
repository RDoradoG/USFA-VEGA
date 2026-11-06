<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->foreignId('seguimiento_estudiante_id')->nullable()->constrained('seguimiento_estudiantes');
            $table->enum('type', ['Lead', 'Dropoff'])->default('Lead');
            $table->foreignId('lead_id')->nullable()->onDelete('no action')->change();
        });

        Schema::table('history_leads', function (Blueprint $table) {
            $table->foreignId('seguimiento_estudiante_id')->nullable()->constrained('seguimiento_estudiantes');
            $table->enum('type', ['Lead', 'Dropoff'])->default('Lead');
            $table->foreignId('lead_id')->nullable()->onDelete('no action')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->dropForeign(['seguimiento_estudiante_id']);
            $table->dropColumn('seguimiento_estudiante_id');
            $table->dropColumn('type');
            $table->foreignId('lead_id')->nullable(false)->change();
        });

        Schema::table('history_leads', function (Blueprint $table) {
            $table->dropForeign(['seguimiento_estudiante_id']);
            $table->dropColumn('seguimiento_estudiante_id');
            $table->dropColumn('type');
            $table->foreignId('lead_id')->nullable(false)->change();
        });
    }
};
