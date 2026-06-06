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
        Schema::create('input_calculado', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('id_input');
            $table->string('autor')->nullable();
            $table->string('descripcion')->nullable()->default('Sin descripción');
            $table->string('tipo');
            $table->string('operacion');
            $table->string('resultado_final')->nullable();
            $table->string('referencia')->nullable();
            $table->string('unidad_medida');
            $table->unsignedBigInteger('id_indicador');
            $table->foreign('id_indicador')
                  ->references('id')
                  ->on('indicadores')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_calculado');
    }
};
