<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeQueja extends Model
{
    protected $table = 'mensajes_quejas';
    protected $fillable = ['mensaje', 'remitente', 'id_queja'];


    public function queja(){

        return $this->belongsTo(Queja::class);
    
    }


}
