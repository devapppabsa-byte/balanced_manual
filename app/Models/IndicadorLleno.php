<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class IndicadorLleno extends Model
{
    //public $timestamps = false;

    protected $table = "indicadores_llenos";
    protected $fillable = ["nombre_campo", "informacion_campo", "id_indicador", "id_movimiento", "final", "planta", 'referencia', 'fecha_periodo', 'unidad_medida' ];


    // protected $casts = [
    // 'created_at' => 'datetime',
    // 'fecha_periodo' => 'datetime',
    // ];

    //creando la relacion 
    public function indicador(){

        $this->belongsTo(Indicador::class, "id_indicador");
    
    }


    
}
