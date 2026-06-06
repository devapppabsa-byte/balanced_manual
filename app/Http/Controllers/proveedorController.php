<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\LogBalanced;

class proveedorController extends Controller
{
    
    public function proveedores_show_admin(){

        $proveedores = Proveedor::with('evaluacion_proveedores')->get();

        return view('admin.gestionar_proveedores', compact('proveedores'));

    }


    public function proveedor_store(Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([

            'nombre_proveedor' => 'required|unique:proveedores,nombre',
            'descripcion_proveedor' => 'required' 

        ]);


        $proveedor = Proveedor::create([
            'nombre' => $request->nombre_proveedor,
            'descripcion' => $request->descripcion_proveedor
        ]);

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego el proveedor: {$proveedor->nombre} (ID: {$proveedor->id})",
            'ip' => request()->ip() 
        ]);

        return back()->with('success', 'El proveedor fue agregado!');


    }



    public function proveedor_delete(Proveedor $proveedor){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        try{

            $nombre_proveedor = $proveedor->nombre;
            $id_proveedor = $proveedor->id;
            
            $proveedor->delete();
            
            LogBalanced::create([
                'autor' => $autor,
                'accion' => "deleted",
                'descripcion' => "Se elimino el proveedor: {$nombre_proveedor} (ID: {$id_proveedor})",
                'ip' => request()->ip() 
            ]);
            
             return back()->with('eliminado', 'El proveedor '.$nombre_proveedor.' fue eliminado');
        
        } catch (\Illuminate\Database\QueryException $e) {
            
            if ($e->getCode() == '23000') {
                return redirect()->back()->with('error', 'No se puede eliminar este proveedor porque está siendo utilizado en una evaluación de proveedores.');
        
        }

        return redirect()->back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el proveedor.');
    }


        


       


    }



}
