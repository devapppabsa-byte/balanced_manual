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
        Schema::create('aux_indicadores_foraneos', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_indicador');
            $table->foreign('id_indicador')
                  ->references('id')
                  ->on('indicadores')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_departamento');
            $table->foreign('id_departamento')
                  ->references('id')
                  ->on('departamentos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aux_indicadores_foraneos');
    }
};
