<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Authenticatable
{

    protected $table = "clientes";
    protected $fillable = ["nombre", 'password', 'email', 'linea', 'telefono', 'id_interno'];




    public function quejas(){
        return $this->hasMany(Queja::class, 'id_cliente');
    }


}
