<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Norma;
use App\Models\CumplimientoNorma;
use App\Models\Departamento;
use App\Models\ApartadoNorma;
use App\Models\LogBalanced;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class normaController extends Controller
{
    public function cumplimiento_norma_show_admin(){

        $normas = Norma::get();

        return view('admin.gestionar_normas', compact('normas'));


    }




    public function norma_store(Request $request, Departamento $departamento){


        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        $autor = Auth::guard('admin')->user()->nombre;
        $puesto = Auth::guard('admin')->user()->puesto;

        $autor_log = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
        $ip = $request->ip();


        

        $request->validate([

            "titulo_norma" => 'required',
            "descripcion_norma" => 'required',
            "ponderacion_norma" => 'required',
            "meta_minima_norma" => 'required',
            "meta_esperada_norma" => 'required'
        ]);


        $norma = Norma::create([

            "nombre" => $request->titulo_norma,
            "descripcion" => $request->descripcion_norma,
            "id_departamento" => $departamento->id,
            "meta_minima" => $request->meta_minima_norma,
            "meta_esperada" => $request->meta_esperada_norma,
            "ponderacion" => $request->ponderacion_norma,
            "autor" => $autor." - ".$puesto,

        ]);



        //registro del log
        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "add",
            'descripcion' => "Se  agrego la norma : ".$norma->nombre . " con el id: ". $norma->id,
            'ip' => request()->ip() 
        ]);
        //registro del log



        return back()->with('success', 'La norma fue agregada!');
            
    }









    public function norma_delete(Request $request, Norma $norma){

        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        $norma->delete();

        //registro del log
        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "deleted",
            'descripcion' => "Se  elimino la norma : ".$norma->nombre . " con el id: ". $norma->id,
            'ip' => request()->ip() 
        ]);
        //registro del log


        return back()->with('eliminado', 'La norma fue eliminada!');

    }






    public function norma_update(Norma $norma, Request $request){


    $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



    $request->validate([

        "nombre_norma_edit" => 'required',
        "descripcion_norma_edit" => 'required',
        "ponderacion_norma_edit" => 'required',
        "meta_minima_norma_edit" => 'required',
        "meta_esperada_norma_edit" => 'required',
        "tipo_regulacion_edit" => 'required'

    ]);



    // Capturar estado anterior para el log
    $cambios = [];
    if($norma->nombre != $request->nombre_norma_edit) {
        $cambios[] = "Nombre: '{$norma->nombre}' -> '{$request->nombre_norma_edit}'";
    }
    if($norma->descripcion != $request->descripcion_norma_edit) {
        $cambios[] = "Descripción: [Modificada]";
    }
    if($norma->ponderacion != $request->ponderacion_norma_edit) {
        $cambios[] = "Ponderación: '{$norma->ponderacion}' -> '{$request->ponderacion_norma_edit}'";
    }

    $norma->nombre = $request->nombre_norma_edit;
    $norma->descripcion = $request->descripcion_norma_edit;
    $norma->ponderacion = $request->ponderacion_norma_edit;
    $norma->meta_esperada = $request->meta_esperada_norma_edit;
    $norma->meta_minima = $request->meta_minima_norma_edit;
    $norma->tipo_regulacion = $request->tipo_regulacion_edit;


    $norma->update();



    //registro del log
    $descripcion = "Se actualizo la norma: ".$norma->nombre." (ID: ".$norma->id.")";
    if(!empty($cambios)) {
        $descripcion .= ". Cambios: ".implode(", ", $cambios);
    }
    
    LogBalanced::create([
        'autor' => $autor_log,
        'accion' => "update",
        'descripcion' => $descripcion,
        'ip' => request()->ip() 
    ]);
    //registro del log






    return back()->with('actualizado', 'La norma fue actualizada');
        

    }


    public function apartado_norma(Norma $norma){

        $apartados = ApartadoNorma::where('id_norma',$norma->id)->get();

        //mega grafica
        $totalesApartados = ApartadoNorma::select('id_norma')
            ->selectRaw('COUNT(*) as total_apartados')
            ->where('id_norma', $norma->id)
            ->groupBy('id_norma');


        $cumplimientos = CumplimientoNorma::join(
                'apartado_norma',
                'cumplimiento_norma.id_apartado_norma',
                '=',
                'apartado_norma.id'
            )
            ->where('apartado_norma.id_norma', $norma->id)
            ->select(
                'apartado_norma.id_norma',
                'cumplimiento_norma.mes',
                DB::raw('COUNT(DISTINCT cumplimiento_norma.id_apartado_norma) as cumplidos')
            )
            ->groupBy('apartado_norma.id_norma', 'cumplimiento_norma.mes');




         $grafica = DB::table('norma')
            ->where('norma.id', $norma->id)
            ->joinSub($totalesApartados, 'totales', function ($join) {
                $join->on('norma.id', '=', 'totales.id_norma');
            })
            ->leftJoinSub($cumplimientos, 'cumple', function ($join) {
                $join->on('norma.id', '=', 'cumple.id_norma');
            })
            ->select(
                'norma.id',
                'norma.nombre',
                'norma.meta_minima',
                'norma.meta_esperada',
                'cumple.mes',
                DB::raw('IFNULL(ROUND((cumple.cumplidos / totales.total_apartados) * 100, 2), 0) as porcentaje')
            )
            ->orderByRaw("
                FIELD(cumple.mes, 
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                )
            ")
            ->get();


            $labels = $grafica->pluck('mes');
            $valores = $grafica->pluck('porcentaje');

            $metaMinima   = optional($grafica->first())->meta_minima ?? 0;
            $metaEsperada = optional($grafica->first())->meta_esperada ?? 0;


        //mega grafica


        return view('admin.apartados_norma', compact('norma', 'apartados', 'labels', 'valores', 'metaMinima', 'metaEsperada'));
   
    }


    public function cumplimiento_normativo_user(){


        $normas = Norma::where('id_departamento', Auth::user()->id_departamento)->get();


        return view('user.cumplimiento_normativo', compact('normas'));
    }


   



}
