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
        Schema::create('campos_foraneos_informacion', function (Blueprint $table) {
            $table->id();
            $table->string('informacion');
            $table->string('mes');
            $table->string('year');
            $table->unsignedBigInteger('id_campo_foraneo');
            $table->foreign('id_campo_foraneo')
                  ->references('id')
                  ->on('campos_foraneos')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campos_foraneos_informacion');
    }
};
