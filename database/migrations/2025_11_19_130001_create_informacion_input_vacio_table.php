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
        Schema::create('informacion_input_vacio', function (Blueprint $table) {

            $table->id();
            $table->string("id_input_vacio");
            $table->unsignedBigInteger('id_input');

            $table->foreign("id_input")
            ->references('id')
            ->on('input_vacio')
            ->onDelete("cascade");

            $table->string("tipo");
            $table->string("informacion");
            $table->string("mes");
            $table->string("year");
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacion_input_vacio');
    }
};
