<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_ia_mensajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_indicador')->nullable()->change();

            $table->unsignedBigInteger('id_departamento')->nullable()->after('id_user');

            $table->foreign('id_departamento')->references('id')->on('departamentos')->onDelete('cascade');

            $table->index('id_departamento');
        });
    }

    public function down(): void
    {
        Schema::table('chat_ia_mensajes', function (Blueprint $table) {
            $table->dropForeign(['id_departamento']);
            $table->dropIndex(['id_departamento']);
            $table->dropColumn('id_departamento');

            $table->unsignedBigInteger('id_indicador')->nullable(false)->change();
        });
    }
};
