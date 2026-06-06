<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicadorPerspectiva extends Model
{
    protected $table = "indicadores_perspectiva";
    protected $fillable = ["nombre", "meta", "ponderacion", "id_objetivo_perspectiva"];



    public function  objetivo(){

        return $this->belongsTo(Objetivo::class, 'id_objetivo_perspectiva');
    
    }


    public function  indicadores_perspectiva(){

        return $this->belongsTo(Objetivo::class, 'id_objetivo_perspectiva');
    
    }

}
