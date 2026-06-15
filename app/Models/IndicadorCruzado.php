<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicadorCruzado extends Model
{
    protected $table = 'indicador_cruzado';

    protected $fillable = [
        'id_indicador_padre',
        'id_indicador_hijo',
    ];

    public function indicadorPadre()
    {
        return $this->belongsTo(Indicador::class, 'id_indicador_padre');
    }

    public function indicadorHijo()
    {
        return $this->belongsTo(Indicador::class, 'id_indicador_hijo');
    }
}
