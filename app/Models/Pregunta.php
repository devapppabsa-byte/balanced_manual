<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = "preguntas";
    protected $fillable = ['pregunta', 'cuantificable', 'id_encuesta'];




    public function encuesta(){
    
       return  $this->belongsTo(Encuesta::class, 'id_encuesta');

    }

    public function respuestas(){

       return  $this->hasMany(Respuesta::class, 'id_pregunta');
    
    }



}
