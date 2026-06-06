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
        Schema::create('mensajes_quejas', function (Blueprint $table) {
            $table->id();
            $table->text('mensaje');
            $table->string('remitente');
            $table->unsignedBigInteger('id_queja');
            $table->foreign('id_queja')
                  ->on('quejas')
                  ->references('id')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_quejas');
    }
};
