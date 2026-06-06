<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CampoVacio;
use App\Models\Indicador;
use App\Models\LogBalanced;
use Illuminate\Http\Request;

class campoVacioController extends Controller
{

public function agregar_campo_vacio(Request $request,Indicador $indicador){





    $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

    $request->validate([
        'nombre_campo_vacio' => 'required',
    ]);



//Sacando el ID para el campo y se pueda gestionar el el combinados de campos al crear nuevos
    $id_input = date('YmdHis').rand(0,5000); 
//Sacando el ID para el campo y se pueda gestionar el el combinados de campos al crear nuevos




    CampoVacio::create([

        'nombre' => $request->nombre_campo_vacio,
        'id_input' => $id_input,
        'autor' => $autor,
        'id_indicador' => $indicador->id,
        'unidad_medida' => $request->unidad_medida,
        'descripcion' => $request->descripcion
        
    ]);

    
    return back()->with('success', 'El campo vacio fue agregado al indicador');        

}
 

}
