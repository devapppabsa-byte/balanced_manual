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
        Schema::create('indicadores_perspectiva', function (Blueprint $table) {
            
            $table->id();
            $table->string('nombre');
            $table->string('meta');
            $table->string('promedio_indicador');

            $table->unsignedBigInteger('id_objetivo_perspectiva');
            
            $table->foreign("id_objetivo_perspectiva")
            ->references('id')
            ->on('objetivos_perspectiva')
            ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicadores_perspectiva');
    }
};
