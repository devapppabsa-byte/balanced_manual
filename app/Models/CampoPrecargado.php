<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoPrecargado extends Model
{
    

    protected $table = "input_precargado";
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'id_indicador', 'id_input', 'id_input_foraneo',
        'autor'
    ];



    public function indicador(){

        return $this->belongsTo(Indicador::class, 'id_indicador');

    }

    public function InformacionInputPrecargado(){
        
        return $this->hasMany(InformacionInputPrecargado::class, 'id_input_precargado');

    }


}
