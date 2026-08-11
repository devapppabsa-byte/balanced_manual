<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_ia_mensajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_indicador');
            $table->unsignedBigInteger('chat_id');
            $table->string('role');
            $table->longText('content');
            $table->timestamps();

            $table->foreign('id_indicador')->references('id')->on('indicadores')->onDelete('cascade');
            $table->index(['id_indicador', 'chat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_ia_mensajes');
    }
};
