<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBalanced extends Model
{
    
    protected $table = "logs_balanced";
    protected $fillable = [
        'autor',
        'accion',
        'ip',
        'descripcion'
    ];



    



}
