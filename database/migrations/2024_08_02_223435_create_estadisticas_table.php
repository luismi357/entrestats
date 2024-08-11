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
            $table->unsignedBigInteger('id_user'); // Cambié esto de $table->id_user() a $table->unsignedBigInteger('id_user')
            $table->float('pecho');
            $table->float('biceps');
            $table->float('pierna');
            $table->float('hombro');
            $table->dateTime('dia');
            $table->timestamps();

            // Si estás referenciando a una tabla users, añade la clave foránea
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};