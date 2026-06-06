<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{

    protected $table = "respuestas";
    protected $fillable = ["respuesta", "id_pregunta", "id_cliente"];



    public function pregunta(){
        $this->belongsTo(Pregunta::class, 'id_pregunta');
    }


}
