<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoVacio extends Model
{
    

    protected $table = 'input_vacio';
    protected $fillable = ['nombre', 'id_input', 'autor', 'id_indicador', 'descripcion', 'unidad_medida'];


    public function indicador(){
        
        return $this->belongsTo(Indicador::class, 'id_indicador');

    }

    public function informacion_input_vacio(){

        return $this->hasMany(InformacionInputVacio::class, 'id_input');
    
    }






}
