<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Departamento;
use App\Models\Pregunta;
use App\Models\Cliente;
use App\Models\LogBalanced;
use App\Models\Respuesta;
use App\Models\ClienteEncuesta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class encuestaController extends Controller
{

    public function encuesta_index(Encuesta $encuesta){
        

        //checo si la encuesta ya fue contestada
        $existe = ClienteEncuesta::where('id_encuesta', $encuesta->id)->get();
        
        //Esto me trae todas las preguntas de la encuesta con sus respuestas 
        $preguntas = Pregunta::with('respuestas')->where('id_encuesta', $encuesta->id)->get();


        //me ayuda a agregar los clientes que ya respondieron las preguntas
        $cliente_arr = [];
        foreach($existe as $cliente ){
            array_push($cliente_arr, $cliente->id_cliente);
        }
        //los clientes que ya contestaron la encuesta.
        $clientes = Cliente::whereIn('id', $cliente_arr)->get();




        //DATOS PARA LA GRAFICA DE LA ENCUESTA
        $resultados = Respuesta::join('preguntas', 'respuestas.id_pregunta', '=', 'preguntas.id')
                ->join('clientes', 'respuestas.id_cliente', '=', 'clientes.id')
                ->where('preguntas.id_encuesta', $encuesta->id)
                ->where('preguntas.cuantificable', 1)
                ->groupBy('clientes.id', 'clientes.nombre')
                ->select(
                    'clientes.nombre as cliente',
                    DB::raw('AVG(respuestas.respuesta) as puntuacion')
                )
                ->get();

            $labels  = $resultados->pluck('cliente');
            $valores = $resultados->pluck('puntuacion')->map(fn($v) => round($v, 2));
        //DATOS PARA LA HGRAFICA DE LA ENCUESTA
                



        return view('admin.gestionar_preguntas', compact('encuesta', 'preguntas', 'existe', 'clientes', 'labels', 'valores'));

    }



    public function encuesta_store(Request $request, Departamento $departamento){



        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $autor = auth()->guard('admin')->user()->nombre;
        $puesto = auth()->guard('admin')->user()->puesto;

        
        $request->validate([
            "nombre_encuesta" => "required|unique:encuestas,nombre",
            "descripcion_encuesta" => "required",
            "ponderacion_encuesta" => "required",
            "meta_esperada_encuesta" => "required",
            "meta_minima_encuesta" => "required"

        ]);


       $encuesta =  Encuesta::create([
            "nombre" => $request->nombre_encuesta,
            "descripcion" => $request->descripcion_encuesta,
            "id_departamento" => $departamento->id,
            "ponderacion" => $request->ponderacion_encuesta,
            "meta_minima" => $request->meta_minima_encuesta,
            "meta_esperada" => $request->meta_esperada_encuesta,
            "autor" => $autor." - ".$puesto      
        ]);



        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "add",
            'descripcion' => "Se agrego la encuesta : ".$encuesta->nombre . " con el id: ". $encuesta->id,
            'ip' => request()->ip() 
        ]);


        return back()->with("success", "La encuesta fue agregada!");


    
    }



    
    public function encuesta_store_two(Request $request){



        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $autor = auth()->guard('admin')->user()->nombre;
        $puesto = auth()->guard('admin')->user()->puesto;




     
        $request->validate([

            "nombre_encuesta" => "required|unique:encuestas,nombre",
            "descripcion_encuesta" => "required",
            "ponderacion_encuesta" => "required",
            "meta_esperada_encuesta" => "required",
            "meta_minima_encuesta" => "required"

        ]);


       $encuesta = Encuesta::create([

            "nombre" => $request->nombre_encuesta,
            "descripcion" => $request->descripcion_encuesta,
            "id_departamento" => $request->departamento,
            "ponderacion" => $request->ponderacion_encuesta,
            "meta_minima" => $request->meta_minima_encuesta,
            "meta_esperada" => $request->meta_esperada_encuesta,
            "autor" => $autor." - ".$puesto     

        ]);


        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "add",
            'descripcion' => "Se agrego la encuesta : ".$encuesta->nombre . " con el id: ". $encuesta->id,
            'ip' => request()->ip() 
        ]);

        return back()->with('success', 'La encuesta fue agregada!');


    }




    public function encuesta_delete(Encuesta $encuesta){

        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
    

        $encuesta->delete();


        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "deleted",
            'descripcion' => "Se elimino la encuesta : ".$encuesta->nombre . " con el id: ". $encuesta->id,
            'ip' => request()->ip() 
        ]);


        return back()->with('eliminado', 'La encuesta fue eliminada!');


    }


    public function encuesta_edit(Encuesta $encuesta, Request $request){


        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        $request->validate([

            "nombre_encuesta_edit" => "required|unique:encuestas,nombre,".$encuesta->id,
            "descripcion_encuesta_edit" => "required",
            "ponderacion_encuesta_edit" => "required",
            'meta_minima_encuesta_edit' => "required",
            'meta_esperada_encuesta_edit' => "required"

        ]);

        // Capturar estado anterior para el log
        $cambios = [];
        if($encuesta->nombre != $request->nombre_encuesta_edit) {
            $cambios[] = "Nombre: '{$encuesta->nombre}' -> '{$request->nombre_encuesta_edit}'";
        }
        if($encuesta->descripcion != $request->descripcion_encuesta_edit) {
            $cambios[] = "Descripción: [Modificada]";
        }
        if($encuesta->ponderacion != $request->ponderacion_encuesta_edit) {
            $cambios[] = "Ponderación: '{$encuesta->ponderacion}' -> '{$request->ponderacion_encuesta_edit}'";
        }
        if($encuesta->meta_minima != $request->meta_minima_encuesta_edit) {
            $cambios[] = "Meta Mínima: '{$encuesta->meta_minima}' -> '{$request->meta_minima_encuesta_edit}'";
        }
        if($encuesta->meta_esperada != $request->meta_esperada_encuesta_edit) {
            $cambios[] = "Meta Esperada: '{$encuesta->meta_esperada}' -> '{$request->meta_esperada_encuesta_edit}'";
        }

        $encuesta->update([

            "nombre" => $request->nombre_encuesta_edit,
            "descripcion" => $request->descripcion_encuesta_edit,
            "ponderacion" => $request->ponderacion_encuesta_edit,
            "meta_minima" => $request->meta_minima_encuesta_edit,
            "meta_esperada" => $request->meta_esperada_encuesta_edit

        ]);



        $descripcion = "Se edito la encuesta: ".$encuesta->nombre." (ID: ".$encuesta->id.")";
        if(!empty($cambios)) {
            $descripcion .= ". Cambios: ".implode(", ", $cambios);
        }

        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "update",
            'descripcion' => $descripcion,
            'ip' => request()->ip() 
        ]);




        return back()->with("actualizado", "La encuesta fue editada");

    }

    public function pregunta_store(Encuesta $encuesta, Request $request){

        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([
            'pregunta' => 'required'
        ]);

        if($request->cuantificable) $cuantificable = true;
        if(!$request->cuantificable) $cuantificable = false;


        $pregunta = Pregunta::create([

            "pregunta" => $request->pregunta,
            "id_encuesta" => $encuesta->id,
            "cuantificable" => $cuantificable
 
        ]);

        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "add",
            'descripcion' => "Se agrego la pregunta: '{$request->pregunta}' (ID: {$pregunta->id}) a la encuesta: {$encuesta->nombre}",
            'ip' => request()->ip() 
        ]);
        
        return back()->with('success', 'La pregunta fue agregada al cuestionario!');

    }


    
    public function pregunta_delete(Pregunta $pregunta){

        $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $pregunta_texto = $pregunta->pregunta;
        $encuesta_id = $pregunta->id_encuesta;
        $encuesta = Encuesta::find($encuesta_id);
        $encuesta_nombre = $encuesta ? $encuesta->nombre : 'N/A';

        $pregunta->delete();

        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "deleted",
            'descripcion' => "Se elimino la pregunta: '{$pregunta_texto}' (ID: {$pregunta->id}) de la encuesta: {$encuesta_nombre}",
            'ip' => request()->ip() 
        ]);

        return back()->with("deleted", "La pregunta fue eliminada");

    }





    public function encuestas_show_admin(){
        
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




    $encuestas = Encuesta::with(['departamento', 'preguntas', 'respuestas'])->whereBetween('created_at', [$inicio, $fin])->withExists('respuestas as tiene_respuestas')->get();


    $departamentos = Departamento::get();


    $subquery = DB::table('respuestas as r')
    ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
    ->join('encuestas as e', 'e.id', '=', 'p.id_encuesta')
    ->where(function ($q) {
        $q->where('p.cuantificable', 1)
          ->orWhere('p.cuantificable', 'on');
    })
    ->whereBetween('r.created_at', [$inicio, $fin])
    ->selectRaw('
        e.id AS encuesta_id,
        e.nombre AS encuesta,
        e.meta_minima,
        e.meta_esperada,
        DATE_FORMAT(r.created_at, "%Y-%m") AS mes,
        r.id_cliente,
        AVG(r.respuesta) AS promedio_cliente
    ')
    ->groupBy(
        'e.id',
        'e.nombre',
        'e.meta_minima',
        'e.meta_esperada',
        'r.id_cliente',
        'mes'
    );



    $resultado_encuestas = DB::query()
    ->fromSub($subquery, 't')
    ->select(
        'encuesta_id',
        'encuesta',
        'meta_minima',
        'meta_esperada',
        'mes',
        DB::raw('ROUND(AVG(promedio_cliente),2) AS total')
    )
    ->groupBy(
        'encuesta_id',
        'encuesta',
        'meta_minima',
        'meta_esperada',
        'mes'
    )
    ->orderBy('mes')
    ->get()
    ->groupBy('encuesta')
    ->map(function ($items, $encuesta) {
        return [
            'encuesta'      => $encuesta,
            'meta_minima'   => $items->first()->meta_minima,
            'meta_esperada' => $items->first()->meta_esperada,
            'labels'        => $items->pluck('mes')->values(),
            'data'          => $items->pluck('total')->values(),
        ];
    })
    ->values();

//ESTO ME DA LAS GRAFICAS POR MES DE LAS ENCUESTAS






        return view ('admin.gestionar_encuestas', compact('encuestas', 'departamentos', 'resultado_encuestas'));

    }


//muestra las 
    public function encuesta_llena_show_admin(Encuesta $encuesta){

        return view('admin.seguimiento_encuesta_detalle', compact('encuesta'));

    }







    //aqui va el codigo de la contestació que va a hacer el usuario en vez del cliente

    public function ver_encuestas_user(){

        $encuestas = Encuesta::get();

        return view('user.lista_encuestas_contestar', compact('encuestas'));
    
    }



    public function encuesta_contestar_user(Encuesta $encuesta){

        $encuesta->load('preguntas');
        $idEncuesta = $encuesta->id;


        //me trae a los clientes que no han contestado el mes en curso 
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $clientes = DB::table('clientes as c')
            ->leftJoin('aux_cliente_encuesta as ace', function ($join) use ($idEncuesta, $inicioMes, $finMes) {
                $join->on('ace.id_cliente', '=', 'c.id')
                    ->where('ace.id_encuesta', $idEncuesta)
                    ->whereBetween('ace.created_at', [$inicioMes, $finMes]);
            })
            ->whereNull('ace.id')
            ->select('c.*')
            ->get();
        //me trae a los clientes que no han contestado el mes en curso 

        

        return view('user.encuesta_contestar', compact('encuesta', 'clientes'));

    }









}
