<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EvaluacionProveedor;
use App\Models\Proveedor;
use Carbon\Carbon;
use App\Models\Departamento;

use Illuminate\Support\Facades\Auth;

class evaluacionProveedorController extends Controller
{
    
    public function  evaluaciones_show_user(){

            
        $evaluaciones = EvaluacionProveedor::with('proveedor')->where('id_departamento', Auth::user()->departamento->id )->Simplepaginate();
        $proveedores = Proveedor::get();
        

        return view('user.evaluaciones_proveedores', compact('evaluaciones', 'proveedores'));

    }



    public function detalle_evaluacion_proveedor(Proveedor $proveedor){


    $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : Carbon::now(config('app.timezone'))
            ->startOfYear()
            ->utc();

    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            ->endOfDay()
            ->utc()
        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();



        
       $evaluaciones = EvaluacionProveedor::where('id_proveedor', $proveedor->id)->whereBetween('created_at', [$inicio, $fin])->get();
      
   
        return view('admin.detalle_evaluacion_proveedores', compact('proveedor', 'evaluaciones'));
   
    }




}
