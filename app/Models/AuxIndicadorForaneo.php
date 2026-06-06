<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuxIndicadorForaneo extends Model
{
    protected $table = "aux_indicadores_foraneos";
    protected $fillable = ['id_indicador', 'id_departamento'];


    public function departamento(){
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function indicador(){
        return $this->belongsTo(Indicador::class, 'id_indicador');
    }






}
