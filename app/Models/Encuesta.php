<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = "encuestas";
    protected $fillable = 
        ["nombre", 
        "descripcion", 
        "id_departamento", 
        'contestado', 
        'ponderacion', 
        'meta_minima', 
       'meta_esperada',
       'autor'
    ];




    public function preguntas(){

        return $this->hasMany(Pregunta::class, 'id_encuesta');
    
    }

    //relacion con las respuestas a traves de las preguntas
    public function respuestas(){
        return $this->hasManyThrough(Respuesta::class, Pregunta::class, 'id_encuesta', 'id_pregunta');
    }



    public function departamento(){

        return $this->belongsTo(Departamento::class, "id_departamento");
    
    }


}
