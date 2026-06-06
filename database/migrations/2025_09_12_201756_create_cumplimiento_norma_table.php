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
        Schema::create('cumplimiento_norma', function (Blueprint $table) {
            $table->id();
            $table->string("mes");
            $table->text("descripcion"); //si / no / no aplica
            $table->string("check");

            $table->unsignedBigInteger("id_apartado_norma");
            $table->foreign("id_apartado_norma")
                  ->references("id")
                  ->on("apartado_norma")
                  ->onDelete('cascade');
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplimiento_norma');
    }
};
