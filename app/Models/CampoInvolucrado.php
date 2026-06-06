<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoInvolucrado extends Model
{
    protected $table = "input_involucrado";
    protected $fillable = ["id_input", "tipo", "id_input_calculado", "posicion"];


    public function campo_calculado(){
        $this->belongsTo(CampoCalculado::class, "id_calculado");
    }

    public function campo_vacio(){
    
        return $this->belongsTo(CampoVacio::class, "id_input_vacio");
    
    }


}
