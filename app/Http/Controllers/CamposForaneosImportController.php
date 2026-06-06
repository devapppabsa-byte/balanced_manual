<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\CamposForaneosImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LogBalanced;
use App\Imports\InputPrecargadoImport;

class CamposForaneosImportController extends Controller
{
    
    public function importar(Request $request){


        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([
 
            'archivo' => 'required'

        ]);



        Excel::import(new InputPrecargadoImport, $request->file('archivo'));


        LogBalanced::create([
            'autor' => $autor,
            'accion' => "excel",
            'descripcion' => "Se cargo el archivo de excel",
            'ip' => request()->ip() 
        ]);


        return back()->with('success', 'El archivo fue cargado!');


    }



}
