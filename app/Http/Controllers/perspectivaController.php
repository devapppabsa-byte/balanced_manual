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


        $request->validate([
            'nombre_perspectiva' => 'required|unique:perspectivas,nombre',
            'ponderacion' => 'required|numeric|max:100|min:1'
        ]);

        Perspectiva::create([
            'nombre' => $request->nombre_perspectiva,
            'ponderacion' => $request->ponderacion
        ]);


        return back()->with('success', 'La perspectiva fue agregada!');

    }




    public function perspectiva_delete(Perspectiva $perspectiva){

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



         return back()->with('deleted', 'Perspectiva eliminada!.');

    }


    public function edit_perspectiva(Perspectiva $perspectiva, Request $request){


        $perspectiva_edit = Perspectiva::findOrFail($perspectiva->id);

    

        $perspectiva_edit->nombre = $request->nombre_perspectiva;
        $perspectiva_edit->ponderacion = $request->ponderacion_perspectiva;

        $perspectiva_edit->save();
        
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
        

        return back()->with('deleted', 'El objetivo fue borrado!');

    }



    public function indicador_objetivo_delete(Objetivo $objetivo, Indicador $indicador){
    
        $indicador->id_objetivo_perspectiva = null;
        $indicador->ponderacion_indicador = null;
        $indicador->save();
    
        return back()->with('success','El indicador se elimino del Objetivo!');
    
    }

    public function encuesta_objetivo_delete(Objetivo $objetivo, Encuesta $encuesta){

        $encuesta->id_objetivo_perspectiva = null;
        $encuesta->ponderacion_encuesta = null;
        $encuesta->save();
        return back()->with('success', 'La encuesta se elimino del Objetivo!');

    }


    public function norma_objetivo_delete(Objetivo $objetivo, Norma $norma){

        $norma->id_objetivo_perspectiva = null;
        $norma->ponderacion_norma = null;
        $norma->save();
        return back()->with('success', 'La norma se elimino del Objetivo!');


    }






    public function objetivo_update(Request $request, Objetivo $objetivo){

        $request->validate([

            "nombre_objetivo_edit" => "required",
            "ponderacion_objetivo_edit" => "required",
            "meta_objetivo_edit" => "required"

        ]);


        $objetivo->nombre = $request->nombre_objetivo_edit;
        $objetivo->ponderacion = $request->ponderacion_objetivo_edit;
        $objetivo->meta = $request->meta_objetivo_edit;
        $objetivo->save();



        return back()->with('actualizado', 'El objetivo fue actualizado');
    
    }




    public function objetivo_store(Request $request, Perspectiva $perspectiva){
        

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



        return back()->with('success', 'Se agrego el objetivo!');

    }




    public function add_indicador_objetivo(Request $request, Objetivo $objetivo){



        //para agregar los indicadores
        if($request->indicadores){

            $idsIndicadores = $request->indicadores;
            Indicador::whereIn('id', $idsIndicadores)->update(['id_objetivo_perspectiva' => $objetivo->id
            ]);

        }


        //para agregar las encuestas
        if($request->encuestas){

            $idsEncuestas = $request->encuestas;
            Encuesta::whereIn('id', $idsEncuestas)->update(['id_objetivo_perspectiva' => $objetivo->id]);
        
        }


        if($request->normas){

            $idsNormas = $request->normas;
            Norma::whereIn('id', $idsNormas)->update(['id_objetivo_perspectiva' => $objetivo->id]);

        }
        



        return back()->with('success', 'Indicadores asignados correctamente.');



    }

    public function agregar_ponderacion_indicador_objetivo(Indicador $indicador, Request $request ){

        
        $request->validate([

            "ponderacion_indicador" => "required"

        ]);

        $indicador->ponderacion_indicador = $request->ponderacion_indicador;
        $indicador->save();


        return back()->with('success', 'La ponderación fue guardada!');

    }


    public function agregar_ponderacion_encuesta_objetivo(Encuesta $encuesta, Request $request){


        $request->validate([
            "ponderacion_encuesta" => "required"
        ]);

        $encuesta->ponderacion_encuesta = $request->ponderacion_encuesta;
        $encuesta->save();

        return back()->with('success', 'La ponderación fue guardada!');


    }



    public function agregar_ponderacion_norma_objetivo(Norma $norma, Request $request){

        $request->validate([
            "ponderacion_norma" => "required"
        ]);


        $norma->ponderacion_norma = $request->ponderacion_norma;
        $norma->save();

        return back()->with('success', 'La ponderación fue guardada!');

        
    }



}
