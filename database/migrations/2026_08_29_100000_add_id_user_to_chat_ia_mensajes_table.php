<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_ia_mensajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('id_indicador');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->index('id_user');
        });
    }

    public function down(): void
    {
        Schema::table('chat_ia_mensajes', function (Blueprint $table) {
            $table->dropIndex(['id_user']);
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};
