<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaCumplimientoNorma extends Model
{

    protected $table = 'evidencia_cumplimiento_norma';
    protected $fillable = ['evidencia', 'id_cumplimiento_norma', 'nombre_archivo'];


    public function cumplimiento_norma(){

        return $this->belongsTo(CumplimientoNorma::class, 'id_cumplimiento_norma');
    
    }

}
