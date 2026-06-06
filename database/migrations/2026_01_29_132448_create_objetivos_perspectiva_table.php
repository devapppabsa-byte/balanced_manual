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
        Schema::create('objetivos_perspectiva', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ponderacion');
            $table->string('meta');
            $table->unsignedBigInteger('id_perspectiva');
            $table->foreign("id_perspectiva")
            ->references('id')
            ->on('perspectivas')
            ->onDelete("cascade");
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objetivos_perspectiva');
    }
};
