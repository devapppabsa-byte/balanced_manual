<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteEncuesta extends Model
{
    
    protected $table = "aux_cliente_encuesta";
    protected $fillable = ['id_cliente', 'id_encuesta'];




    public function encuesta(){
    
        return $this->belongsTo(Encuesta::class, 'id_encuesta');
    
    }

    public function cliente(){

        return $this->belongsTo(Cliente::class, 'id_cliente');
    
    }




}
