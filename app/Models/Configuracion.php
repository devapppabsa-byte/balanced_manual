<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor'];

    public static function valor(string $clave, $default = null)
    {
        $configuracion = static::where('clave', $clave)->first();

        return $configuracion ? $configuracion->valor : $default;
    }
}