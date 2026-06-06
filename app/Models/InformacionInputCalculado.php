<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionInputCalculado extends Model
{
    protected $table = "informacion_input_calculado";
    protected $fillable = ['informacion', 'id_input_calculado', "mes", "year"];




    public function campo_calculado(){

        return $this->belongsTo(CampoCalculado::class, 'id_input_calculado');

    }

}
