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
        Schema::create('informacion_input_precargado', function (Blueprint $table) {
            $table->id();
            $table->string('informacion');
            $table->string('mes');
            $table->string('year');
            $table->unsignedBigInteger('id_input_precargado');
            $table->foreign('id_input_precargado')
                  ->references('id')
                  ->on('input_precargado')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacion_input_precargado');
    }
};
