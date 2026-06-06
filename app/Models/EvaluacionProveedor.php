<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionProveedor extends Model
{


    protected $table = "evaluaciones_proveedores";
    protected $fillable = ["fecha", "calificacion", "descripcion", "id_departamento", "id_proveedor", "observaciones"];


    public function departamento(){
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }


    


}
