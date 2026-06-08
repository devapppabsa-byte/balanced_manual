<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InformacionForanea;
use App\Models\CampoForaneo;
use App\Models\CampoForaneoInformacion;
use App\Models\LogBalanced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class informacionForaneaController extends Controller
{



    public function informacion_foranea_show_admin(){

        $informacion_foranea = CampoForaneo::with('campo_foraneo_informacion')->orderBy('updated_at', 'ASC')->get();

        

        return view('admin.gestionar_informacion_foranea', compact('informacion_foranea'));

    }
    




    public function destroy(CampoForaneo $campoForaneo){
        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;
        $nombre = $campoForaneo->nombre;

        $campoForaneo->campo_foraneo_informacion()->delete();
        $campoForaneo->delete();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "delete",
            'descripcion' => "Se eliminó la información foránea: '{$nombre}' (ID: {$campoForaneo->id})",
            'ip' => request()->ip()
        ]);

        return back()->with('eliminado', 'La información foránea fue eliminada correctamente!');
    }

    public function destroyAll(){
        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $total = CampoForaneo::count();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        CampoForaneoInformacion::truncate();
        CampoForaneo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "delete",
            'descripcion' => "Se eliminó toda la información foránea ({$total} campos)",
            'ip' => request()->ip()
        ]);

        return back()->with('eliminado', "Toda la información foránea fue eliminada correctamente!");
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
