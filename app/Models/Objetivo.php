<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    protected $table = "objetivos_perspectiva";
    protected $fillable = ['nombre', 'ponderacion','meta', 'id_perspectiva', 'id_objetivo_encuesta'];



    public function perspectiva(){
        
        return $this->belongsTo(Perspectiva::class, 'id_perspectiva');

    }

    public function indicadores_perspectiva(){
        return $this->hasMany(Indicador::class, 'id_objetivo_perspectiva');
    }


    //metodos lpra las relaciones que se necesitan para las perspectivas y todo eso.
    public function encuestas_perspectiva(){
        return $this->hasMany(Encuesta::class, 'id_objetivo_perspectiva');
    }

    public function normas_perspectiva(){
        return $this->hasMany(Norma::class, 'id_objetivo_perspectiva');
    }

}
