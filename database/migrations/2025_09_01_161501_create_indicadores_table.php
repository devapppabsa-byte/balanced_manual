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
        Schema::create('indicadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('meta_esperada')->nullable()->default('Esperando meta');
            $table->string('meta_minima')->nullable()->default('Esperando meta minima');
            $table->text('descripcion')->nullable()->default('Sin descripción disponible.');
            $table->string('ponderacion');
            $table->string('tipo_indicador');
            $table->string('creador');
            $table->string('id_objetivo_perspectiva')->nullable();
            $table->string('ponderacion_indicador')->nullable();
            $table->string('unidad_medida');
            $table->string('variacion');
            $table->string('planta')->nullable();
            
            $table->unsignedBigInteger('id_departamento');
            $table->foreign('id_departamento')
                  ->references('id')
                  ->on('departamentos')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicadores');
    }
};
