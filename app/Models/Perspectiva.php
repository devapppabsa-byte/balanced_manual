<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perspectiva extends Model
{
    protected $table = "perspectivas";
    protected $fillable = ["nombre", "ponderacion"];



    public function objetivos(){
        return $this->hasMany(Objetivo::class, 'id_perspectiva');
    }


}
