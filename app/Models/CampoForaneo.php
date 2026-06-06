<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoForaneo extends Model
{
    protected $table = 'campos_foraneos';
    protected $fillable = ['id_input', 'nombre', 'descripcion'];



    public function inputPrecargados(){
        return $this->hasMany(InputPrecargado::class, 'id_input', 'id_input');
        
    }

    public function campo_foraneo_informacion(){
        return $this->hasMany(CampoForaneoInformacion::class, 'id_campo_foraneo');
    }
}
