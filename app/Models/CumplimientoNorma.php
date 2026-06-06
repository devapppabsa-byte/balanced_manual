<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CumplimientoNorma extends Model
{
    
    protected $table = "cumplimiento_norma";
    protected $fillable = ["mes", "descripcion", "id_apartado_norma"];



    public function norma(){
        
        return $this->belongsTo(Norma::class, 'id_apartado_norma');
    }

    public function evidencia_cumplimiento_norma(){
        return $this->hasMany(EvidenciaCumplimientoNorma::class, 'id_cumplimiento_norma');
    }


    public function apartado(){
        return $this->belongsTo(ApartadoNorma::class, 'id_apartado_norma');
    }

}
