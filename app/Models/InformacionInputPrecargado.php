<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionInputPrecargado extends Model
{

    protected $table = "informacion_input_precargado";
    
    protected $fillable = 
    [
        'informacion', 
        'id_input_precargado', 
        "mes", 
        "year"
    ];



    public function input_precargado(){

        return $this->belongsTo(CampoPrecargado::class, 'id_input_precargado');

    }


}
