<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Norma;
use Illuminate\Http\Request;
use App\Models\Perspectiva;
use App\Models\Indicador;
use App\Models\IndicadorLleno;
use App\Models\Objetivo;
use Carbon\Carbon;
use App\Services\CumplimientoService;
use App\Models\LogBalanced;
class perspectivaController extends Controller
{

    public function perspectivas_show(){


        $inicio = request()->filled('fecha_inicio')
            ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
                ->startOfDay()
                ->utc()
            : Carbon::parse("2026-01-01T06:00:00.000000Z");
        //$inicio = "2025-01-01T06:00:00.000000Z";

        $fin = request()->filled('fecha_fin')
            ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
                //->subMonth()    
                ->endOfDay()
                ->utc()

            : Carbon::now(config('app.timezone'))
                ->endOfYear()
                ->utc();



     $perspectivas = Perspectiva::with([
            'objetivos.indicadores_perspectiva.indicadorLleno',
            'objetivos.encuestas_perspectiva',
            'objetivos.normas_perspectiva'
        ])->get();


        foreach ($perspectivas as $perspectiva) {
            $perspectiva->cumplimiento = CumplimientoService::calcularPerspectiva(
                $perspectiva,
                $inicio,
                $fin
            );

            $perspectiva->aporte = round(
                ($perspectiva->cumplimiento * $perspectiva->ponderacion) / 100,
                2
            );
        }









        return view('admin.agregar_perspectivas', compact('perspectivas', 'inicio', 'fin'));
    
    }


    public function perspectiva_store(Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([
            'nombre_perspectiva' => 'required|unique:perspectivas,nombre',
            'ponderacion' => 'required|numeric|max:100|min:1'
        ]);

        Perspectiva::create([
            'nombre' => $request->nombre_perspectiva,
            'ponderacion' => $request->ponderacion
        ]);

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego la perspectiva: '{$request->nombre_perspectiva}' con ponderacion: {$request->ponderacion}%",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La perspectiva fue agregada!');

    }




    public function perspectiva_delete(Perspectiva $perspectiva){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;
        $nombre_perspectiva = $perspectiva->nombre;

        //listando objetivos
        $objetivos = Objetivo::where('id_perspectiva', $perspectiva->id)->get();        


        foreach($objetivos as $objetivo){


                Indicador::where('id_objetivo_perspectiva', $objetivo->id)
                    ->update([
                        'id_objetivo_perspectiva' => null, 
                        'ponderacion_indicador' => null
                    ]);


                Encuesta::where('id_objetivo_perspectiva', $objetivo->id)
                    ->update([
                        'id_objetivo_perspectiva' => null,
                        'ponderacion_encuesta' => null
                    ]);

                Norma::where('id_objetivo_perspectiva', $objetivo->id)
                    ->update([
                        'id_objetivo_perspectiva' => null,
                        'ponderacion_norma' => null
                    ]);


        }

         $perspectiva->delete();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "deleted",
            'descripcion' => "Se elimino la perspectiva: '{$nombre_perspectiva}' (ID: {$perspectiva->id})",
            'ip' => request()->ip()
        ]);

         return back()->with('deleted', 'Perspectiva eliminada!.');

    }


    public function edit_perspectiva(Perspectiva $perspectiva, Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $perspectiva_edit = Perspectiva::findOrFail($perspectiva->id);

        $cambios = [];
        if($perspectiva_edit->nombre != $request->nombre_perspectiva) {
            $cambios[] = "Nombre: '{$perspectiva_edit->nombre}' -> '{$request->nombre_perspectiva}'";
        }
        if($perspectiva_edit->ponderacion != $request->ponderacion_perspectiva) {
            $cambios[] = "Ponderacion: {$perspectiva_edit->ponderacion}% -> {$request->ponderacion_perspectiva}%";
        }

        $perspectiva_edit->nombre = $request->nombre_perspectiva;
        $perspectiva_edit->ponderacion = $request->ponderacion_perspectiva;

        $perspectiva_edit->save();

        $descripcion = "Se edito la perspectiva: '{$request->nombre_perspectiva}' (ID: {$perspectiva->id})";
        if(!empty($cambios)) {
            $descripcion .= ". Cambios: ".implode(", ", $cambios);
        }

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => $descripcion,
            'ip' => request()->ip()
        ]);
        
        return back()->with('edit', 'La perspectiva fue editada');


    }






    public function detalle_perspectiva(Perspectiva $perspectiva){

         $inicio = request()->filled('fecha_inicio')
             ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
                 ->startOfDay()
                 ->utc()
             : Carbon::parse("2026-01-01T06:00:00.000000Z");

             

         $fin = request()->filled('fecha_fin')
             ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
                 //->subMonth()    
                 ->endOfDay()
                 ->utc()
             : Carbon::now(config('app.timezone'))
                 ->endOfYear()
                 ->utc();

        $fechas_seleccionar = IndicadorLleno::where('final', 'on')
            ->selectRaw("DATE_FORMAT(fecha_periodo, '%Y-%m') as periodo")
            ->distinct()
            ->orderBy('periodo')
            ->pluck('periodo');


        $fecha_filtro = request('fecha_filtro');
        


        $objetivos = Objetivo::where('id_perspectiva', $perspectiva->id)->get();
        $indicadores = Indicador::get();
        $encuestas = Encuesta::get();
        $normas = Norma::get();



        return view('admin.agregar_objetivos_perspectiva', compact('perspectiva', 'objetivos', 'indicadores', 'inicio', 'fin', 'encuestas', 'normas', 'fechas_seleccionar', 'fecha_filtro'));

    }



    public function objetivo_delete(Objetivo $objetivo){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;
        $nombre_objetivo = $objetivo->nombre;
        $id_objetivo = $objetivo->id;

        Indicador::where('id_objetivo_perspectiva', $objetivo->id)
            ->update([
                'id_objetivo_perspectiva' => null, 
                'ponderacion_indicador' => null
            ]);


        Encuesta::where('id_objetivo_perspectiva', $objetivo->id)
            ->update([
                'id_objetivo_perspectiva' => null,
                'ponderacion_encuesta' => null
            ]);

        Norma::where('id_objetivo_perspectiva', $objetivo->id)
            ->update([
                'id_objetivo_perspectiva' => null,
                'ponderacion_norma' => null
            ]);


        $objetivo->delete();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "deleted",
            'descripcion' => "Se elimino el objetivo: '{$nombre_objetivo}' (ID: {$id_objetivo}) de la perspectiva",
            'ip' => request()->ip()
        ]);

        return back()->with('deleted', 'El objetivo fue borrado!');

    }



    public function indicador_objetivo_delete(Objetivo $objetivo, Indicador $indicador){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;
    
        $indicador->id_objetivo_perspectiva = null;
        $indicador->ponderacion_indicador = null;
        $indicador->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se elimino el indicador: '{$indicador->nombre}' (ID: {$indicador->id}) del objetivo: {$objetivo->nombre}",
            'ip' => request()->ip()
        ]);
    
        return back()->with('success','El indicador se elimino del Objetivo!');
    
    }

    public function encuesta_objetivo_delete(Objetivo $objetivo, Encuesta $encuesta){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $encuesta->id_objetivo_perspectiva = null;
        $encuesta->ponderacion_encuesta = null;
        $encuesta->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se elimino la encuesta: '{$encuesta->nombre}' (ID: {$encuesta->id}) del objetivo: {$objetivo->nombre}",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La encuesta se elimino del Objetivo!');

    }


    public function norma_objetivo_delete(Objetivo $objetivo, Norma $norma){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $norma->id_objetivo_perspectiva = null;
        $norma->ponderacion_norma = null;
        $norma->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se elimino la norma: '{$norma->nombre}' (ID: {$norma->id}) del objetivo: {$objetivo->nombre}",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La norma se elimino del Objetivo!');


    }






    public function objetivo_update(Request $request, Objetivo $objetivo){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([

            "nombre_objetivo_edit" => "required",
            "ponderacion_objetivo_edit" => "required",
            "meta_objetivo_edit" => "required"

        ]);

        $cambios = [];
        if($objetivo->nombre != $request->nombre_objetivo_edit) {
            $cambios[] = "Nombre: '{$objetivo->nombre}' -> '{$request->nombre_objetivo_edit}'";
        }
        if($objetivo->ponderacion != $request->ponderacion_objetivo_edit) {
            $cambios[] = "Ponderacion: {$objetivo->ponderacion}% -> {$request->ponderacion_objetivo_edit}%";
        }
        if($objetivo->meta != $request->meta_objetivo_edit) {
            $cambios[] = "Meta: {$objetivo->meta} -> {$request->meta_objetivo_edit}";
        }

        $objetivo->nombre = $request->nombre_objetivo_edit;
        $objetivo->ponderacion = $request->ponderacion_objetivo_edit;
        $objetivo->meta = $request->meta_objetivo_edit;
        $objetivo->save();

        $descripcion = "Se edito el objetivo: '{$request->nombre_objetivo_edit}' (ID: {$objetivo->id})";
        if(!empty($cambios)) {
            $descripcion .= ". Cambios: ".implode(", ", $cambios);
        }

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => $descripcion,
            'ip' => request()->ip()
        ]);

        return back()->with('actualizado', 'El objetivo fue actualizado');
    
    }




    public function objetivo_store(Request $request, Perspectiva $perspectiva){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([

            "nombre_objetivo"  => "required",
            "ponderacion_objetivo" => "required",
            "meta_objetivo" => "required"
            
        ]);


        Objetivo::create([
            "nombre" => $request->nombre_objetivo,
            "ponderacion" => $request->ponderacion_objetivo,
            "meta" => $request->meta_objetivo,
            "id_perspectiva" => $perspectiva->id
        ]);

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego el objetivo: '{$request->nombre_objetivo}' a la perspectiva: {$perspectiva->nombre}",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'Se agrego el objetivo!');

    }




    public function add_indicador_objetivo(Request $request, Objetivo $objetivo){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $detalles = [];

        //para agregar los indicadores
        if($request->indicadores){

            $idsIndicadores = $request->indicadores;
            $nombres = Indicador::whereIn('id', $idsIndicadores)->pluck('nombre')->implode(', ');
            Indicador::whereIn('id', $idsIndicadores)->update(['id_objetivo_perspectiva' => $objetivo->id
            ]);
            $detalles[] = "Indicadores: {$nombres}";

        }


        //para agregar las encuestas
        if($request->encuestas){

            $idsEncuestas = $request->encuestas;
            $nombres = Encuesta::whereIn('id', $idsEncuestas)->pluck('nombre')->implode(', ');
            Encuesta::whereIn('id', $idsEncuestas)->update(['id_objetivo_perspectiva' => $objetivo->id]);
            $detalles[] = "Encuestas: {$nombres}";
        
        }


        if($request->normas){

            $idsNormas = $request->normas;
            $nombres = Norma::whereIn('id', $idsNormas)->pluck('nombre')->implode(', ');
            Norma::whereIn('id', $idsNormas)->update(['id_objetivo_perspectiva' => $objetivo->id]);
            $detalles[] = "Normas: {$nombres}";

        }

        if(!empty($detalles)) {
            LogBalanced::create([
                'autor' => $autor,
                'accion' => "update",
                'descripcion' => "Se asignaron elementos al objetivo '{$objetivo->nombre}': ".implode(" | ", $detalles),
                'ip' => request()->ip()
            ]);
        }

        return back()->with('success', 'Indicadores asignados correctamente.');



    }

    public function agregar_ponderacion_indicador_objetivo(Indicador $indicador, Request $request ){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([

            "ponderacion_indicador" => "required"

        ]);

        $indicador->ponderacion_indicador = $request->ponderacion_indicador;
        $indicador->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se agrego ponderacion del {$request->ponderacion_indicador}% al indicador '{$indicador->nombre}' en el objetivo",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La ponderación fue guardada!');

    }


    public function agregar_ponderacion_encuesta_objetivo(Encuesta $encuesta, Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([
            "ponderacion_encuesta" => "required"
        ]);

        $encuesta->ponderacion_encuesta = $request->ponderacion_encuesta;
        $encuesta->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se agrego ponderacion del {$request->ponderacion_encuesta}% a la encuesta '{$encuesta->nombre}' en el objetivo",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La ponderación fue guardada!');


    }



    public function agregar_ponderacion_norma_objetivo(Norma $norma, Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. auth()->guard('admin')->user()->puesto;

        $request->validate([
            "ponderacion_norma" => "required"
        ]);


        $norma->ponderacion_norma = $request->ponderacion_norma;
        $norma->save();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => "Se agrego ponderacion del {$request->ponderacion_norma}% a la norma '{$norma->nombre}' en el objetivo",
            'ip' => request()->ip()
        ]);

        return back()->with('success', 'La ponderación fue guardada!');

        
    }



}
