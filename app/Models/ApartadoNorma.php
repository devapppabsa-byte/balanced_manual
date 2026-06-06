<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartadoNorma extends Model
{
    protected $table = "apartado_norma";
    protected $fillable = ["apartado", "descripcion", "id_norma"];

    public function norma(){


        return $this->belongsTo(Norma::class, 'id_norma');


    } 


    public function cumplimientos(){

        return $this->hasMany(CumplimientoNorma::class, 'id_apartado_norma');
    
    }


}
