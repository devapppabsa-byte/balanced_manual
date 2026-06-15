<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicador_cruzado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_indicador_padre');
            $table->unsignedBigInteger('id_indicador_hijo');
            $table->timestamps();

            $table->foreign('id_indicador_padre')->references('id')->on('indicadores')->onDelete('cascade');
            $table->foreign('id_indicador_hijo')->references('id')->on('indicadores')->onDelete('cascade');
            $table->unique(['id_indicador_padre', 'id_indicador_hijo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicador_cruzado');
    }
};
