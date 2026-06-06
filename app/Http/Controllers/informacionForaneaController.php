<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InformacionForanea;
use App\Models\CampoForaneo;
use App\Models\LogBalanced;
use Illuminate\Http\Request;

class informacionForaneaController extends Controller
{



    public function informacion_foranea_show_admin(){

        $informacion_foranea = CampoForaneo::with('campo_foraneo_informacion')->orderBy('updated_at', 'ASC')->get();

        

        return view('admin.gestionar_informacion_foranea', compact('informacion_foranea'));

    }
    




    public function agregar_informacion_foranea(Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([
            'nombre_info' => 'required',
            'informacion' => 'required',
            'tipo_info' => 'required'
        ]);


        $informacion_foranea = InformacionForanea::create([

            'nombre_info' => $request->nombre_info,
            'contenido' => $request->informacion,
            'tipo_dato'  => $request->tipo_info

        ]);

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego la información foránea: '{$request->nombre_info}' (ID: {$informacion_foranea->id})",
            'ip' => request()->ip() 
        ]);

        return back()->with('success', 'La información fue agregada!');


    }



}
