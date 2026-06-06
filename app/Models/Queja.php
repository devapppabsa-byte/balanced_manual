<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queja extends Model
{

    protected $table = 'quejas';
    protected $fillable = ['titulo', 'queja', 'id_cliente'];



    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }


    public function evidencias_quejas(){

        return $this->hasMany(EvidenciaQuejas::class, 'id_cliente');
    
    }


}
