<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampoForaneoInformacion extends Model
{
    protected $table = "campos_foraneos_informacion";
    protected $fillable =['id_campo_foraneo', 'mes', 'year', 'informacion'];



 

}
