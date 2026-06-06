<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaQuejas extends Model
{

    protected $table = 'evidencias_quejas';
    protected $fillable = ['nombre_archivo', 'evidencia', 'id_queja'];


    public function queja(){

        return $this->belongsTo(Queja::class, 'id_queja');
   
    }



}
