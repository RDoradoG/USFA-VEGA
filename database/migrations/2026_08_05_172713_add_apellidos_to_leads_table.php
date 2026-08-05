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
        // 1. Add new column
        Schema::table('leads', function (Blueprint $table) {
            $table->string('apellidos')->nullable()->after('nombre');
        });

        // 2. Merge data
        DB::statement("
            UPDATE leads 
            SET apellidos = CONCAT(
                COALESCE(apellido_paterno, ''), 
                ' ', 
                COALESCE(apellido_materno, '')
            )
        ");

        // 3. Drop old columns
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['apellido_paterno', 'apellido_materno']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recreate old columns
        Schema::table('leads', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
        });

        // 2. Split apellidos back (basic split by first space)
        DB::statement("
            UPDATE leads 
            SET 
                apellido_paterno = SUBSTRING_INDEX(apellidos, ' ', 1),
                apellido_materno = SUBSTRING_INDEX(apellidos, ' ', -1)
        ");

        // 3. Drop apellidos
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('apellidos');
        });
    }
};
