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
        Schema::create('grupos_musculares', function (Blueprint $table) {
            $table->id();                         // PK autoincrement BIGINT
            $table->string('nombre_grupo');       // Nombre del grupo muscular
            $table->timestamps();                 // created_at, updated_at
            // $table->softDeletes();              // (Opcional) deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
