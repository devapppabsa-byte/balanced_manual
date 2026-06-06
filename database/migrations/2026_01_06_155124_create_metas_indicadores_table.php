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
        Schema::create('metas_indicadores_llenos', function (Blueprint $table) {
            $table->id();
            $table->string('meta_minima');
            $table->string("meta_maxima");
            $table->string('id_movimiento_indicador_lleno');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas_indicadores');
    }
};
