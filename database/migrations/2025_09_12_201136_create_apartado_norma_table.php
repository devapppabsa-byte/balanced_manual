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
        Schema::create('apartado_norma', function (Blueprint $table) {

            $table->id();
            $table->text("apartado");
            $table->text("descripcion");
            $table->unsignedBigInteger("id_norma");
            $table->foreign("id_norma")
                  ->references("id")
                  ->on("norma")
                  ->onDelete("cascade");
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartado_norma');
    }
};
