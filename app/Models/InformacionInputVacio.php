<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionInputVacio extends Model
{
    protected $table = "informacion_input_vacio";
    protected $fillable = ["id_input_vacio","id_input", "informacion", "tipo", "mes", "year"];




    public function input_vacio(){

        return $this->belongsTo(CampoVacio::class, 'id_input');

    }




}
