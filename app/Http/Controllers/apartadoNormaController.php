<?php

namespace App\Http\Controllers;

//use App\Models\Apartado;
use App\Models\EvidenciaCumplimientoNorma;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Norma;
use Illuminate\Support\Facades\DB;
use App\Models\CumplimientoNorma;
use App\Models\ApartadoNorma;
use App\Models\LogBalanced;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class apartadoNormaController extends Controller
{


 public function apartado_norma_store(Request $request, Norma $norma){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([

            "titulo_apartado_norma" => "required",
            "descripcion_apartado_norma" => "required"
            
        ]);


        $apartado = ApartadoNorma::create([
            "apartado" => $request->titulo_apartado_norma,
            "descripcion" => $request->descripcion_apartado_norma,
            "id_norma" => $norma->id
        ]);

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego el apartado: '{$request->titulo_apartado_norma}' (ID: {$apartado->id}) a la norma: {$norma->nombre}",
            'ip' => request()->ip() 
        ]);

        return back()->with('success', 'El apartado fue agregado!');

    }

    
    public function registro_cumplimiento_normativa_index(Norma $norma){


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


        return view('user.registro_cumplimiento_normativo', compact('norma', 'apartados', 'labels', 'valores', 'metaMinima', 'metaEsperada'));


    }

    

    public function delete_apartado_norma(ApartadoNorma $apartado){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $apartado_nombre = $apartado->apartado;
        $norma_id = $apartado->id_norma;
        $norma = Norma::find($norma_id);
        $norma_nombre = $norma ? $norma->nombre : 'N/A';

        $apartado->delete();

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "deleted",
            'descripcion' => "Se elimino el apartado: '{$apartado_nombre}' (ID: {$apartado->id}) de la norma: {$norma_nombre}",
            'ip' => request()->ip() 
        ]);

        return back()->with('eliminado', 'El apartado fue eliminado de la norma!');


    }


    public function edit_apartado_norma(ApartadoNorma $apartado, Request $request){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

        $request->validate([
            'nombre_apartado_edit' => Rule::unique('apartado_norma', 'apartado')->ignore($apartado->id),
            'descripcion_apartado_edit' => 'required'
        ]);

        // Capturar estado anterior para el log
        $cambios = [];
        if($apartado->apartado != $request->nombre_apartado_edit) {
            $cambios[] = "Título: '{$apartado->apartado}' -> '{$request->nombre_apartado_edit}'";
        }
        if($apartado->descripcion != $request->descripcion_apartado_edit) {
            $cambios[] = "Descripción: [Modificada]";
        }

        $norma_id = $apartado->id_norma;
        $norma = Norma::find($norma_id);
        $norma_nombre = $norma ? $norma->nombre : 'N/A';

        $apartado->update([

            "apartado" => $request->nombre_apartado_edit,
            "descripcion" => $request->descripcion_apartado_edit

        ]);

        $descripcion = "Se edito el apartado: '{$request->nombre_apartado_edit}' (ID: {$apartado->id}) de la norma: {$norma_nombre}";
        if(!empty($cambios)) {
            $descripcion .= ". Cambios: ".implode(", ", $cambios);
        }

        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => $descripcion,
            'ip' => request()->ip() 
        ]);

        return back()->with('actualizado', 'El apartado fue editado');


    }


public function registro_actividad_cumplimiento_norma(Request $request){
    
    
    //Esta variable se usa para el LOG
    $autor = 'Id: '.auth()->user()->id.' - '.auth()->user()->name.' - '.auth()->user()->puesto;
    
    
    Carbon::setLocale('es'); // Establece el idioma a español
    setlocale(LC_TIME, 'es_ES.UTF-8'); // Asegura que PHP use el locale correcto (depende del servidor)
    
    $mes = Carbon::now()->translatedFormat('F Y');
    $mes = Carbon::parse($request->fecha)->translatedFormat('m-y');

    //esta es la lista de a´partados, apartir de esta lista se creara el ciclo.    
    $lista_apartados = array_keys($request->realizada);
    $keys_descripciones = array_keys($request->descripcion);
    $keys_realizada = array_keys($request->realizada);
    if($request->evidencias != null) $keys_evidencias = array_keys($request->evidencias);

    //y si solo recorro los apartados que sten marcados como realizados

    
    foreach($lista_apartados as $apartado){

            //si el apartado es marcado como "Realizado"  busco a ver si tiene descripcion
            if(in_array($apartado, $keys_descripciones)){

                 $descripcion = $request->descripcion[$apartado];
                 $descripcion == null ? $descripcion = " No se cargo descripción de la actividad " : "";



                if($request->evidencias != null){
                    
                    if(in_array($apartado, $keys_evidencias)){
                        //se obtiene el el del cumplimiento creado
                       $cumplimiento_norma = CumplimientoNorma::create([
                       'mes'=> $mes,
                       'descripcion' => $descripcion,
                       'id_apartado_norma' => $apartado,
                       'check' => $request->realizada[$apartado]
                       ]);
    
                    }
                }



                else {
                   CumplimientoNorma::create([
                   'mes'=> $mes,
                   'descripcion' => $descripcion,
                   'id_apartado_norma' => $apartado,
                   'check' => $request->realizada[$apartado]
                   ]);
                }







                if($request->evidencias != null){

                    if(in_array($apartado, $keys_evidencias)){

                        if($request->hasFile('evidencias')){
                            
                            foreach($request->file('evidencias') as $id => $archivo){
                                
                                if($archivo){
                                $nombre = $archivo->getClientOriginalName(); 
                                $path = $archivo->store('evidencias', 'public');

                                    EvidenciaCumplimientoNorma::create([
                                        'evidencia' => $path,
                                        'nombre_archivo' => $nombre,
                                        'id_cumplimiento_norma' => $cumplimiento_norma->id
                                    ]);
                                }

                            }
                        }
                    }
                }
                
            }
        }


        // LogBalanced::create([
        //     'autor' => $autor,
        //     'accion' => "add",
        //     'descripcion' => "Se registro cumplimiento normativo para el apartado: '{$apartado->apartado}' de la norma: {$norma_nombre} (ID cumplimiento: {$cumplimiento_norma->id})",
        //     'ip' => request()->ip() 
        // ]);




        return back()->with('success', 'La actividad fue registrada');
    }


    public function ver_evidencia_cumplimiento_normativo(ApartadoNorma $apartado){


        $cumplimientos = CumplimientoNorma::with('evidencia_cumplimiento_norma')
        ->where('id_apartado_norma', $apartado->id)
        ->get()
        ->sortByDesc(function ($item) {
            return Carbon::createFromFormat('m-y', $item->mes);
        })
        ->unique('mes')
        ->values();
       
       

        return view('user.evidencia_cumplimiento_normativo', compact('apartado', 'cumplimientos'));



    }

    public function ver_evidencia_cumplimiento_normativo_admin(ApartadoNorma $apartado){


        
    $cumplimientos = CumplimientoNorma::with('evidencia_cumplimiento_norma')
        ->where('id_apartado_norma', $apartado->id)
        ->get()
        ->sortByDesc(function ($item) {
            return Carbon::createFromFormat('m-y', $item->mes);
        })
        ->unique('mes')
        ->values();

        

        return view('admin.evidencia_cumplimiento_normativo', compact('apartado', 'cumplimientos'));


    }


}
