<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable= ['nombre', 'descripcion'];


    public function evaluacion_proveedores(){
        return $this->hasMany(EvaluacionProveedor::class, 'id_proveedor');
    }



}
