<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionForanea extends Model
{

    protected $table = "informacion_foranea";
    protected $fillable = ['nombre_info', 'contenido', 'tipo_dato', 'descripcion'];



    

}
