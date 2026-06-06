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
        Schema::create('input_involucrado', function (Blueprint $table) {
            $table->id();
            $table->string("id_input");
            $table->string("tipo");
            $table->string("posicion")->nullable();

            $table->unsignedBigInteger("id_input_calculado");
            $table->foreign("id_input_calculado")
                  ->references('id')->on('input_calculado')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_involucrado');
    }
};
