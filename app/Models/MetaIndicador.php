<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaIndicador extends Model
{
    
    protected $table = 'metas_indicadores_llenos';
    protected $fillable = ['meta_maxima', 'meta_minima', 'id_movimiento_indicador_lleno']; 



}
