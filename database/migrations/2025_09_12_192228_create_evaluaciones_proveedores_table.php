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
        Schema::create('evaluaciones_proveedores', function (Blueprint $table) {
            $table->id();
            $table->string("fecha");
            $table->string("calificacion");
            $table->text("descripcion");
            $table->text('observaciones')->nullable();
            
            $table->unsignedBigInteger("id_departamento");
            $table->foreign('id_departamento')
                  ->references("id")
                  ->on('departamentos');

            $table->unsignedBigInteger("id_proveedor");
            $table->foreign("id_proveedor")
                  ->references('id')
                  ->on("proveedores");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_proveedores');
    }
};
