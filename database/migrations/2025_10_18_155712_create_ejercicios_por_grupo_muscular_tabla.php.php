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
        Schema::create('ejercicios_por_grupo_muscular', function (Blueprint $table) {
            $table->id(); // PK
            // FK al id de grupos_musculares
            $table->foreignId('grupo_muscular_id')
                  ->constrained('grupos_musculares') // referencia a grupos_musculares.id
                  ->cascadeOnDelete();               // si se borra el grupo, borra sus ejercicios
            $table->string('nombre_ejercicio');      // nombre del ejercicio
            $table->timestamps();                    // created_at, updated_at
            // $table->softDeletes();                // opcional
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
