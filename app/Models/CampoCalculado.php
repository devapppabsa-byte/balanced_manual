<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoCalculado extends Model
{
    
    protected $table = "input_calculado";
    protected $fillable = ['nombre', 'autor', 'tipo', 'operacion', 'tipo', 'id_indicador', 'id_input', 'resultado_final', 'referencia', 'descripcion', 'unidad_medida'];



    public function indicador(){
        return $this->belongsTo(Indicador::class, "id_indicador");
    }


    public function campo_involucrado(){
        return $this->hasMany(CampoInvolucrado::class, 'id_input_calculado');
    }


    public function informacion_input_calculado(){

        return $this->hasMany(InformacionInputCalculado::class, 'id_input_calculado');

    }


}
