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
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->unsignedBigInteger("id_departamento");
            $table->string("ponderacion");
            $table->string("meta_minima");
            $table->string("meta_esperada");
            $table->string("autor");
            $table->string("id_objetivo_encuesta");
            $table->string("ponderacion_encuesta");

            $table->foreign("id_departamento")
                  ->references('id')
                  ->on("departamentos")
                  ->onDelete("cascade");

            $table->text("descripcion")->nullable();
            $table->string("contestado")->default('no_contestado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
