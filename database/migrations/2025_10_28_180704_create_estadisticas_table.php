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
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('grupo_muscular_id')->constrained('grupos_musculares')->onDelete('cascade');
            $table->foreignId('ejercicio_id')->constrained('ejercicios_por_grupo_muscular')->onDelete('cascade');
            $table->float('peso');
            $table->dateTime('dia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
