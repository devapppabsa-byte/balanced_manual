<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\LogBalanced;
use Illuminate\Http\Request;

class departamentoController extends Controller
{



    public function eliminar_departamento(Departamento $departamento){

        try{

            $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



            $departamento->delete();


     //registro del log
        LogBalanced::create([
            'autor' => $autor,
            'descripcion' => "Se elimino el departamento : ".$departamento->nombre . "con el id: ". $departamento->id,
            'accion'=> "deleted",
            'ip' => request()->ip() 
        ]);
     //registro del log



        return back()->with('eliminado', 'El departamento fue eliminado!');





        
        } catch (\Illuminate\Database\QueryException $e) {
            
            if ($e->getCode() == '23000') {
                return redirect()->back()->with('error', 'No se puede eliminar este departamento porque está siendo utilizado en otra operación.');
        
        }

        return redirect()->back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el departamento.');
    }



}

    



    public function actuliza_departamento(Departamento $departamento, Request $request){



        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        // Capturar estado anterior para el log
        $nombre_anterior = $departamento->nombre;
        $cambios = [];
        if($departamento->nombre != $request->nombre_departamento) {
            $cambios[] = "Nombre: '{$nombre_anterior}' -> '{$request->nombre_departamento}'";
        }
        if($departamento->planta != $request->planta) {
            $cambios[] = "Planta: '".($departamento->planta ?? 'N/A')."' -> '".($request->planta ?? 'N/A')."'";
        }

        $departamento->nombre = $request->nombre_departamento;
        if($request->planta) {
            $departamento->planta = $request->planta;
        }
        $departamento->update();

        
            //registro del log
            $descripcion = "Se actualizo el departamento: ".$departamento->nombre." (ID: ".$departamento->id.")";
            if(!empty($cambios)) {
                $descripcion .= ". Cambios: ".implode(", ", $cambios);
            }
            
            LogBalanced::create([
                'autor' => $autor,
                'accion' => "update",
                'descripcion' => $descripcion,
                'ip' => $request->ip()
            ]);
            //registro del log



        return back()->with('actualizado', 'El departamento '.$departamento->nombre. ' fue actualizado ');

    }


    public function departamentos_show_admin(){
        
        $departamentos = Departamento::get();


        return view('admin.gestionar_departamentos', compact("departamentos"));
    }


}
