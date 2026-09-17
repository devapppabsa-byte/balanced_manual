<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatIaMensaje extends Model
{
    protected $table = 'chat_ia_mensajes';

    protected $fillable = [
        'id_indicador',
        'id_user',
        'chat_id',
        'role',
        'content',
    ];

    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'id_indicador');
    }
}
