<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuxIndicadorForaneo;
use App\Models\CampoForaneoInformacion;
use Illuminate\Support\Str;
use App\Models\CampoCalculado;
use App\Models\CampoForaneo;
use App\Models\CumplimientoNorma;
use App\Models\CampoInvolucrado;
use App\Models\CampoPrecargado;
use App\Models\CampoVacio;
use App\Models\InformacionInputVacio;
use App\Models\InformacionForanea;
use App\Models\InformacionInputPrecargado;
use App\Models\InformacionInputCalculado;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Norma;
use App\Models\Indicador;
use App\Models\IndicadorLleno;
use App\Models\Encuesta;
use App\Models\User;
use App\Models\LogBalanced;
use App\Models\MetaIndicador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class indicadorController extends Controller
{


    public function agregar_indicadores_index(Departamento $departamento){

        
        $indicadores = Indicador::where('id_departamento', $departamento->id)->get();
        $usuarios = User::with('departamento')->where('id_departamento', $departamento->id)->get();
        $encuestas = Encuesta::where("id_departamento", $departamento->id)->get();
        $normas = Norma::where("id_departamento", $departamento->id)->get();
        $departamentos = Departamento::get();

       // $indicadores_foraneos = Indicador::with('departamento')->where('id_departamento', '!=', $departamento->id)->get();
        $id_departamento = $departamento->id;

        $indicadores_foraneos = Indicador::with('departamento')
            ->where(function ($query) use ($id_departamento) {
                $query->where('id_departamento', '!=', $id_departamento)
                    ->orWhereNull('id_departamento');
            })
            ->whereDoesntHave('departamentosForaneos', function ($query) use ($id_departamento) {
                $query->where('departamentos.id', $id_departamento);
            })
            ->get();


        $indicadores_foraneos_agregados = Indicador::whereHas('departamentosForaneos', function ($query) use ($id_departamento) {
            $query->where('departamentos.id', $id_departamento);
        })->get();


    
        //Teniendo el departamento tomo los indicadores que se le estan registrando.
        $indicadores_ponderaciones = Indicador::where('id_departamento', $departamento->id)->get();
        $ponderacion_indicadores = [];
        foreach($indicadores_ponderaciones as $indicador_ponderacion){
            array_push($ponderacion_indicadores, $indicador_ponderacion->ponderacion);
        }



        $normas_ponderaciones = Norma::where('id_departamento', $departamento->id)->get();
        $ponderacion_normas = [];
        foreach($normas_ponderaciones as $norma_ponderacion){
            array_push($ponderacion_normas, $norma_ponderacion->ponderacion);
        }



        $encuestas_ponderaciones = Encuesta::where('id_departamento', $departamento->id)->get();
        $ponderacion_encuestas = [];
        foreach($encuestas_ponderaciones as $encuesta_ponderacion){
            array_push($ponderacion_encuestas, $encuesta_ponderacion->ponderacion);
        }


        $ponderacion = array_sum(array_merge($ponderacion_indicadores, $ponderacion_normas, $ponderacion_encuestas));





        

        return view('admin.agregar_indicadores', compact('departamento','indicadores', 'usuarios', 'departamentos', 'encuestas', 'normas', 'ponderacion', 'indicadores_foraneos', 'indicadores_foraneos_agregados' ));

    }




    public function agregar_indicadores_store(Request $request, Departamento $departamento){

    

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

         
        $nombre_admin = Auth::guard('admin')->user()->nombre;
        $puesto = Auth::guard('admin')->user()->puesto;


        $request->validate([

            'nombre_indicador' => 'required'

        ]);



        $indicador = new Indicador();
        $indicador->nombre = $request->nombre_indicador;
        $indicador->id_departamento = $departamento->id;
        $indicador->descripcion = $request->descripcion;
        $indicador->meta_esperada = $request->meta_esperada;
        $indicador->meta_minima = $request->meta_minima;
        $indicador->unidad_medida = $request->unidad_medida;
        $indicador->ponderacion = $request->ponderacion_indicador;
        $indicador->tipo_indicador = $request->tipo_indicador;
        $indicador->variacion = $request->indicador_variacion;
        $indicador->planta = $request->planta;

        // if ($request->planta_1 == "active") $indicador->planta_1 = $request->planta_1;
        // if ($request->planta_2 == "active") $indicador->planta_2 =  $request->planta_2;
        // if ($request->planta_3 == "active") $indicador->planta_3 = $request->planta_3;

        $indicador->creador = $nombre_admin . ' - ' . $puesto;
        $indicador->save();


        //registro del log
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "Se agrego el indicador : ".$indicador->nombre . " con el id: ". $indicador->id,
            'ip' => $request->ip() 
        ]);
        //registro del log

        
        return back()->with('success', 'El indicador fue creado!');

    }


    public function borrar_indicador(Indicador $indicador){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



        $indicador->delete();


        //registro del log
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "deleted",
            'descripcion' => "Se elimino el indicador : ".$indicador->nombre . " con el id: ". $indicador->id,
            'ip' => request()->ip() 
        ]);
        //registro del log



        return back()->with('eliminado', 'El indicador fue eliminado');


    }


    public function indicador_edit(Request $request, Indicador $indicador){

        
        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        $request->validate([

            "nombre_indicador_edit" => "required",
            "meta_minima" => "required",
            "meta_esperada" => "required",
            "ponderacion_indicador_edit" => "required",
            "unidad_medida" => "required",
            "planta_indicador" => "required"

        ]);

        // Capturar estado anterior para el log
        $cambios = [];
        if($indicador->meta_esperada != $request->meta_esperada) {
            $cambios[] = "Meta Esperada: '{$indicador->meta_esperada}' -> '{$request->meta_esperada}'";
        }
        if($indicador->meta_minima != $request->meta_minima) {
            $cambios[] = "Meta Mínima: '{$indicador->meta_minima}' -> '{$request->meta_minima}'";
        }
        
        if($indicador->ponderacion != $request->ponderacion_indicador_edit) {
            $cambios[] = "Ponderación: '{$indicador->ponderacion}' -> '{$request->ponderacion_indicador_edit}'";
        }

        if($indicador->tipo_indicador != $request->tipo_indicador) {
            $cambios[] = "Tipo Indicador: '{$indicador->tipo_indicador}' -> '{$request->tipo_indicador}'";
        }



        $indicador->nombre = $request->nombre_indicador_edit;
        $indicador->meta_esperada = $request->meta_esperada;
        $indicador->meta_minima = $request->meta_minima;
        $indicador->ponderacion = $request->ponderacion_indicador_edit;
        $indicador->tipo_indicador = $request->tipo_indicador;
        $indicador->unidad_medida = $request->unidad_medida;
        $indicador->variacion = $request->indicador_variacion_edit;
        $indicador->planta = $request->planta_indicador;
        $indicador->update();


        //registro del log
        $descripcion = "Se actualizo el indicador: ".$indicador->nombre." (ID: ".$indicador->id.")";
        if(!empty($cambios)) {
            $descripcion .= ". Cambios: ".implode(", ", $cambios);
        }
        
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "update",
            'descripcion' => $descripcion,
            'ip' => request()->ip() 
        ]);
        //registro del log



        return back()->with('success', 'El indicador fue actualizado!');

    }



public function indicador_index(Indicador $indicador){

    //esta linea me ayuda a cargar las relaciones que tiene el Inidcador, por que inyecte el indicador directo en el metodo y no lo consulte con su respectiva query
    $indicador->load('departamento');


    //verificar si ya hay un campo_final en este indicador.
    $campo_final = CampoCalculado::where('id_indicador', $indicador->id)->where('resultado_final', 'on')->get();

    //consultar el campo de referencia.
    $campo_referencia = CampoCalculado::where('id_indicador', $indicador->id)->where('referencia', 'on')->get();




    $campos_vacios = CampoVacio::where('id_indicador', $indicador->id)->get();
    $campos_precargados = CampoPrecargado::where('id_indicador', $indicador->id)->get();
    $campos_calculados = CampoCalculado::where('id_indicador', $indicador->id)->get();
    
    //DEspues de obtener los capos calculados correspondientes a este Indicador
    //Se procedera a obtener los id de los campos involucrados con el campo calculado.
    //se optienen para saber como van a ser las opereaciones, aunque en esta seccion podria
    //no obtenerlos ya que esas operaciones se van a hacer en el lado del usuario 





    // $campos_unidos = $campos_vacios->union($campos_precargados)->orderBy('created_at', 'desc')->get();
    $campos_unidos = $campos_vacios
    ->concat($campos_precargados)
    ->concat($campos_calculados)
    ->sortBy('created_at')
    ->values()
    ->map(function($item, $index){
        $item->id_nuevo = $index + 1; //con esto empezara en 1
        return $item;
    });




    //Llamar a la informacion 
    $informacion_foranea = CampoForaneo::get();

    return view('admin.indicador', compact('indicador', 'campos_vacios','campos_precargados','campos_calculados', 'informacion_foranea', 'campos_unidos', 'campo_final', 'campo_referencia'));

}






public function borrar_campo(Request $request, $campo, $tipo_campo){

        
        //vamos a buscar el id_input en la base de datos de los campos involucrados.
        $id_indicador = CampoInvolucrado::where('id_input',$request->id_input)->first();

        //  return $id_indicador;

        if($id_indicador){

            return back()->with('error_input', 'Este campo esta siendo utilizado como parte de otro campo, por lo que no puede ser eliminado');

        }


    
        if($tipo_campo == "calculado"){
            
            $campo_delete = CampoCalculado::findOrFail($campo);
            $nombre_campo = $campo_delete->nombre;
            $campo_delete->delete();
            

            return back()->with("deleted", "El campo fue eliminado del indicador!.");
        }
        

   
        if($tipo_campo == "vacio"){

           $campo_delete = CampoVacio::findOrFail($campo);
           $nombre_campo = $campo_delete->nombre;
           $campo_delete->delete();
           
           
           return back()->with("deleted", "El campo fue eliminado del indicador!.");
 
        }



        if($tipo_campo == "precargado"){

            $campo_delete = CampoPrecargado::findOrFail($campo);
            $nombre_campo = $campo_delete->nombre;
            $campo_delete->delete();
            
            
            return back()->with("deleted", "El campo fue eliminado del indicador");

        }


        if($request->id_input){
    
            $campo_delete = CampoVacio::where('id_input', $request->id_input)->first();
            $nombre_campo = $campo_delete->nombre;
            $campo_delete->delete();

            
            return back()->with("deleted", "El campo fue eliminado del indicador");         


        }




        return back()->with('Error', 'Ocurrio un error inesperado!');


}




public function editar_campo(Request $request, $campo, $tipo_campo){
    
    if($tipo_campo == "precargado"){

        $precargado = CampoPrecargado::findOrFail($campo);
        $precargado->unidad_medida = $request->unidad_medida;
        $precargado->update();


        $existe = DB::table('indicadores_llenos')
            ->where('nombre_campo', $precargado->nombre)
            ->exists();

        if (!$existe) {
            return back()->with("error", "No se encontró el campo en Indicadores Llenos");
        }


        $updated = DB::table('indicadores_llenos')
            ->where('nombre_campo', $precargado->nombre)
            ->update([
                'unidad_medida' => $request->unidad_medida
        ]);


        return back()->with('editado', 'El campo fue editado');

    }





    if($tipo_campo == "vacio"){

        $vacio = CampoVacio::findOrFail($campo);
        $vacio->unidad_medida = $request->unidad_medida;
        $vacio->update();
        
        
        $existe = DB::table('indicadores_llenos')
            ->where('nombre_campo', $vacio->nombre)
            ->exists();

        if (!$existe) {
            return back()->with("error", "No se encontró el campo en Indicadores Llenos");
        }

        DB::table('indicadores_llenos')
            ->where('nombre_campo', $vacio->nombre)
            ->update([
                'unidad_medida' => $request->unidad_medida
        ]);

        
        return back()->with('editado', 'El campo fue editado');

    }




    if($tipo_campo == "calculado"){


        
        $calculado = CampoCalculado::findOrFail($campo);
        $calculado->unidad_medida = $request->unidad_medida;
        $calculado->update();


        $existe = DB::table('indicadores_llenos')
            ->where('nombre_campo', $calculado->nombre)
            ->exists();

        if (!$existe) {
            return back()->with("error", "No se encontró el campo en Indicadores Llenos");
        }

        DB::table('indicadores_llenos')
            ->where('nombre_campo', $calculado->nombre)
            ->update([
                'unidad_medida' => $request->unidad_medida
        ]);




        return back()->with('editado', 'El campo fue editado');
    
    }





    return back()->with('error', 'Ocurrio un error desconocido');



}











public function show_indicador_robusto_user(Request $request, Indicador $indicador){

    //este es para mostrar los datos en el select
   $campos_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->distinct()->pluck('nombre_campo');
  

   $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : "2025-01-01T06:00:00.000000Z";



    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();



    //datos para graficar...
    $campo_graficar = $request->campos_a_graficar;
    

    $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->first();

    if(!$datos_campo_graficar){
        $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();

    }


    $campo_final = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();
    $campos_referencia = IndicadorLleno::where('id_indicador', $indicador->id)->where('referencia', 'on')->pluck('nombre_campo');



    // De aqui se obtienen los datos del analisis de datos
    $resultado = $this->calcularTendenciaKPI( $indicador->id, $indicador->meta_esperada, $indicador->tipo_indicador, $inicio, $fin); // o 'mayor_mejor'


    



$graficar = IndicadorLleno::where('id_indicador', $indicador->id)
    ->whereBetween('fecha_periodo', [$inicio, $fin])
    ->where(function ($q) use ($campo_graficar, $campo_final, $campos_referencia) {

        //si el usuario hizo un cambio de campo 
        if (!empty($campo_graficar)) {

            //si el usuario seleccion el campo que se grafica por default
            if($campo_graficar === $campo_final->nombre_campo){

                $q->where('final', 'on')
                ->orWhere('referencia', 'on');

            }

            else{

                if(in_array($campo_graficar, json_decode($campos_referencia))){
                    $q->where('nombre_campo', $campo_graficar);
                }
    
                //si el usuario selecciona un campo diferente
                else{
                    $q->where('nombre_campo', $campo_graficar);
                    
                }

            }
            
        }
         
        //si no hay campo seleccionado, es decir,, lo que se muestra desde el inicio.
        else {

            $q->where('final', 'on')
            ->orWhere('referencia', 'on');

        }

    })
->orderBy('fecha_periodo', 'asc')
->get();






//AQUI VA LA CONDICIONAL QUE VA A CAMBIAR TODO LOS DATOS JOJOJOJ

if(!empty($campo_graficar)){

    
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();


    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('nombre_campo', $campo_graficar)
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();




    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('nombre_campo', $campo_graficar)
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('nombre_campo', $campo_graficar)
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        
        
        //para sacar el mejor y el peor mes



// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidad

}



//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA
else{

    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();

    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();

    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('final', 'on')
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        




// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidaD

}
//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA






//Aqui va a ir el codigo que me permite consultar los datos del ultimo mes
 $fechas_seleccionar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->pluck('fecha_periodo');
 

 if($request->mostrar_mes){
     $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('fecha_periodo', $request->mostrar_mes)->where('final', 'on')->first();   
 }
 else{
     $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->latest()->first();
    
 }



if(!empty($ultimo_mes)){
    $campos_llenos = IndicadorLleno::where('id_movimiento', $ultimo_mes->id_movimiento)->get();
}
else{
    $campos_llenos = [];
}


//Aqui va a ir el codigo que me permite consultar los datos del ultimo mes




//parece que no hace nda
   $registros_tendencia = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'asc')
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->get();
//parece que no hace nda







//variables para el llenado y borrado de los indicadores

    $campos_vacios = CampoVacio::where('id_indicador', $indicador->id)->get();

    //obtenemos la ultima fecha en la que se cargo el excel
    $ultima_carga_excel = CampoForaneoInformacion::latest()->first();
    $ultima_carga_indicador = IndicadorLleno::where('id_indicador', $indicador->id)->latest()->first();


//variuables para el llenado y borrado de los indicadores





    return view('user.indicador_robusto', compact('indicador', 'info_meses', 'promedios', 'graficar', 'historico', 'resultado', 'mejor_mes', 'peor_mes', 'campos_graficar', 'campo_graficar','datos_campo_graficar', 'ultimo_mes', 'fechas_seleccionar', 'campos_llenos','campos_vacios', 'ultima_carga_excel', 'ultima_carga_indicador' ));

}
















    //retornando los indicadores a la 
    
    
public function show_indicador_user(Request $request,  Indicador $indicador){


    $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : Carbon::now(config('app.timezone'))
            //->subMonth()
            ->startOfYear()
            ->utc();

    //$inicio = "2025-01-01T06:00:00.000000Z";

    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();


    
    //obtenemos la ultima fecha en la que se cargo el excel
    $ultima_carga_excel = CampoForaneoInformacion::latest()->first();
    $ultima_carga_indicador = IndicadorLleno::where('id_indicador', $indicador->id)->latest()->first();


    //le mandamos los admins para que les envie el correo de que ya se lleno el indicador
    //$correos = Admin::pluck('email')->toArray();
    
    //array_push($correos, Auth::user()->email);

    //CONSULTA DE LOS CAMPOS
    //se consultan en la vista para que el usuario los rellene
   $campos_vacios = CampoVacio::where('id_indicador', $indicador->id)->get();

    //se consultan en la vista para que el usuario los vea
   $campos_llenos = CampoPrecargado::with('InformacionInputPrecargado')->where('id_indicador', $indicador->id)->get();

    //esta es la consulta que me va a dar el arreglo para hacer los iclos y realizar las operaciones.
    $campos_calculados = CampoCalculado::with('campo_involucrado')->where('id_indicador', $indicador->id)->where(function ($q) {
            $q->whereNull('resultado_final')
            ->orWhere('resultado_final', '');
        })->orderBy('created_at', 'ASC')->get();

    


    //aqui hay un desmadre, combino todos los campos y les asigno un ID
    $campos_unidos = $campos_vacios->concat($campos_llenos)
                                    ->concat($campos_calculados)
                                    ->sortBy('created_at')
                                    ->values()
                                    ->map(function($item, $index){
                                    $item->id_nuevo = $index + 1; //con esto empezara en 1
                                    return $item;
    });
    //para poder hacer las opreaciones tengo que consultar todo.





   $datos = IndicadorLleno::where('id_indicador', $indicador->id)->whereBetween('fecha_periodo', [$inicio, $fin])->get();

    $grupos = $datos->groupBy('id_movimiento')->sortKeysDesc();

    
    //se consultan las metas directo de la tabla del indicador para tenerlas actualizadas
    $meta_minima_general = $indicador->meta_minima;
    $meta_maxima_general = $indicador->meta_esperada;
    
    
    
    
    //Se consulta el campo de resultado final.
    $campo_resultado_final = CampoCalculado::where('id_indicador', $indicador->id)->whereNotNull('resultado_final')->where('resultado_final', '!=', '')->first();

    //datos para graficar...
    $graficar = IndicadorLleno::where('id_indicador', $indicador->id)
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->where(function ($q) {
            $q->where('final', 'on')
            ->orWhere('referencia', 'on');
        })
        ->orderBy('created_at')
        ->get();

    $tipo_indicador = $indicador->tipo_indicador;

    
    $promedios = IndicadorLleno::select(
    DB::raw('YEAR(fecha_periodo) as anio'),
    DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();


    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();
        

    return view('user.indicador', compact('indicador', 'campos_calculados', 'campos_llenos', 'campos_unidos', 'campo_resultado_final', 'campos_vacios', 'grupos', 'graficar', 'meta_minima_general', 'meta_maxima_general', 'tipo_indicador', 'ultima_carga_excel', 'ultima_carga_indicador', 'promedios', 'info_meses'));

    
}//cierra el metodo de show_indicador_user








public function input_porcentaje_guardar(Request $request, Indicador $indicador){

   $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
   $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
    


    if($request->resultado_final){

        $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
            ->whereNotNull('resultado_final')
            ->where('resultado_final', '!=', '')
            ->get();
            
        
        if(!$comprobacion->isEmpty()){
            return back()->with('error_input', 'Ya extiste un campo de resultado final en este indicador, por lo que no se puede crear otro. Thanks!');
        }

    }






    if(count($request->input_porcentaje) < 2 ) return back()->with("error", "Debe agregregar un par de campos");


    $contador = count($request->input_porcentaje);
    $id_input = Date('ydmHis').rand(0,100);





    $campo_calculado = CampoCalculado::create([

        "nombre" => $request->nombre,
        "id_input" => $id_input,
        "autor" => $autor,
        "tipo" => "number",
        "operacion" => "porcentaje",
        "resultado_final" => $request->resultado_final,
        "referencia" => $request->referencia,
        "id_indicador" => $indicador->id,
        "descripcion" => $request->descripcion,
        "unidad_medida" => 'porcentaje'

    ]);




    for($i=0; $i < $contador; $i++){
        
        CampoInvolucrado::create([
            "id_input" => $request->input_porcentaje[$i],
            "tipo" => "number",
            "posicion" => $i,
            "id_input_calculado" => $campo_calculado->id
        ]);

    }



    return back()->with("success", "El campo de porcentaje ha sido creado");


}



public function input_resta_guardar(Request $request, Indicador $indicador){



    $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
    $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


    if($request->resultado_final){

        $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
                        ->whereNotNull('resultado_final')
                        ->where('resultado_final', '!=', '')
                        ->get();

        if(!$comprobacion->isEmpty()){

            return back()->with("error_input", "Ya existe un campo final en este indicador!");
        
        }

    }

    if(count($request->input_resta) < 2 ) return back()->with("error", "Se deben agregar dos campos!");


    $id_input = Date('ydmHis').rand(0,100);

    $campo_calculado = CampoCalculado::create([

            "nombre" => $request->nombre_campo_resta,
            "id_input" => $id_input,
            "autor" => $autor,
            "tipo" => "number",
            "operacion" => "resta",
            "resultado_final" => $request->resultado_final,
            "referencia" => $request->referencia,
            "id_indicador" => $indicador->id,
            "unidad_medida" => $request->unidad_medida,
            "descripcion" => $request->descripcion

    ]);


    CampoInvolucrado::create([

        "id_input" => $request->input_resta[0],
        "tipo" => "number",
        "id_input_calculado" => $campo_calculado->id,
        "posicion" => "0"

    ]);


    CampoInvolucrado::create([

        "id_input" => $request->input_resta[1],
        "tipo" => "number",
        "id_input_calculado" => $campo_calculado->id,
        "posicion" => "1"

    ]);

    LogBalanced::create([
        'autor' => $autor_log,
        'accion' => "add",
        'descripcion' => "Se creo el campo calculado (resta): '{$request->nombre_campo_resta}' (ID: {$campo_calculado->id}) en el indicador: {$indicador->nombre}",
        'ip' => request()->ip() 
    ]);

    return back()->with("success", "Se agrego el campo de resta!");






}









public function input_division_guardar(Request $request, Indicador $indicador){



       $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
       $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


    //COMPROBACION DEL CAMPO FINAL, SI YA HAY UN CAMPO FINAL NO SE PODRA AGREGAR OTRO
    if($request->resultado_final){

        $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
                        ->whereNotNull('resultado_final')
                        ->where('resultado_final', '!=', '')
                        ->get();

        if(!$comprobacion->isEmpty()){

            return back()->with('error_input', 'Ya existe un campo final en este indicador!');

        }

    }


    //COMPROBACION PARA QUE VENGAN DOS CAMPOS POR LO MENOS
    if(count($request->input_division) < 2 ) return back()->with('error', 'Se deben agregar un par de campos');

    $id_input  = Date('ydmHis').rand(0,100);

    
    $campo_calculado = CampoCalculado::create([

        "nombre" => $request->nombre_campo_division,
        "id_input" => $id_input,
        "autor" => $autor,
        "tipo" => "number",
        "operacion" => "division",
        "referencia" => $request->referencia,
        "resultado_final" => $request->resultado_final,
        "id_indicador" => $indicador->id,
        "unidad_medida" => $request->unidad_medida,
        "descripcion" => $request->descripcion

    ]);


    //En lugar del for voy a hacerlo a mano ya que sol son dos campos y necesito diferenciar entre el primero y el segundo

    
    CampoInvolucrado::create([

        "id_input" => $request->input_division[0],
        "tipo" => "number",
        "id_input_calculado" => $campo_calculado->id,
        "posicion" => "0"

    ]);


    CampoInvolucrado::create([

        "id_input" => $request->input_division[1],
        "tipo" => "number",
        "id_input_calculado" => $campo_calculado->id,
        "posicion" => "1"

    ]);

    LogBalanced::create([
        'autor' => $autor_log,
        'accion' => "add",
        'descripcion' => "Se creo el campo calculado (división): '{$request->nombre_campo_division}' (ID: {$campo_calculado->id}) en el indicador: {$indicador->nombre}",
        'ip' => request()->ip() 
    ]);

    return back()->with("success", "Se a creado el nuevo campo de división");

}




public function input_suma_guardar(Request $request, Indicador $indicador){



    $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
    $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



if($request->resultado_final){

    $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
                    ->whereNotNull('resultado_final')
                    ->where('resultado_final', '!=', '')
                    ->get();

    if(!$comprobacion->isEmpty()){
        
        return back()->with('error_input', 'Ya existe un campo final en este indicador.');
        
    } 
}



if( count($request->input_suma) < 2) return back()->with('error', 'Se debe agregar por lo menos dos campos!');

    $contador = count($request->input_suma);
    $id_input = Date('ydmHis').rand(0,100);



    $campo_calculado = CampoCalculado::create([

        "nombre" => $request->nombre_campo_suma,
        "id_input" => $id_input,
        "tipo" => "number",
        "autor" => $autor,
        "operacion" => "suma",
        "referencia" => $request->referencia,
        "resultado_final" => $request->resultado_final,
        "id_indicador" => $indicador->id,
        "unidad_medida" => $request->unidad_medida,
        "descripcion" => $request->descripcion


    ]);


    for($i=0; $i < $contador; $i++){

        CampoInvolucrado::create([

            "id_input" => $request->input_suma[$i],
            "tipo" => "number",
            "id_input_calculado" => $campo_calculado->id

        ]);

    }

    LogBalanced::create([
        'autor' => $autor_log,
        'accion' => "add",
        'descripcion' => "Se creo el campo calculado (suma): '{$request->nombre_campo_suma}' (ID: {$campo_calculado->id}) en el indicador: {$indicador->nombre}",
        'ip' => request()->ip() 
    ]);


    return back()->with("success", "Se a creado el nuevo campo de suma");



}


public function input_multiplicacion_guardar(Request $request, Indicador $indicador){


       $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
       $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



    if($request->resultado_final){

        $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
                        ->whereNotNull('resultado_final')
                        ->where('resultado_final', '!=','')
                        ->get();


        if(!$comprobacion->isEmpty()){
    
            return back()->with('error_input', 'Ya existe un campo resultado en este indicador.');
    
        }

    }


    //validando que no venga vacio
    if(count($request->input_multiplicado) < 2) return back()->with("error", 'Se debe poner por lo menos un par de campos');

    $contador = count($request->input_multiplicado);

    $id_input = Date('ydmHis').rand(0,100);


    //se crea el nuevo campo calculado, este campo contendra la informacion de todos los campos que los componen, es decir, despues de crear este campo vamos a  crear los registros de los campos involucrados con este campo calculado.

    
    $campo_calculado = CampoCalculado::create([

        "nombre" => $request->nombre_campo_multiplicacion,
        "id_input" => $id_input,
        "tipo" => "number",
        "autor" => $autor,
        "operacion" => "multiplicacion",
        "referencia" => $request->referencia,
        "resultado_final" => $request->resultado_final,
        "id_indicador" => $indicador->id,
        "unidad_medida" => $request->unidad_medida,
        "descripcion" => $request->descripcion

    ]);

    


    for($i=0; $i<$contador; $i++){

        CampoInvolucrado::create([

            "id_input" => $request->input_multiplicado[$i],
            "tipo" => "number",
            "id_input_calculado" => $campo_calculado->id

        ]);

    }

    LogBalanced::create([
        'autor' => $autor_log,
        'accion' => "add",
        'descripcion' => "Se creo el campo calculado (multiplicación): '{$request->nombre_campo_multiplicacion}' (ID: {$campo_calculado->id}) en el indicador: {$indicador->nombre}",
        'ip' => request()->ip() 
    ]);

    return back()->with("success", "Se a creado el nuevo campo de multiplicación");


}





public function input_promedio_guardar(Request $request, Indicador $indicador){

    $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
    $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;

    //aqui se hace la verificación si el campo combinado de promedio qued marcado como campo final
    if($request->resultado_final){

       $comprobacion = CampoCalculado::where('id_indicador', $indicador->id)
            ->whereNotNull('resultado_final')
            ->where('resultado_final', '!=', '')
            ->get();
   

        //Se necesita comprobar que en el indicador no haya mas Campos que sean Resultado Final
        //creo que aqui ya hice esa validación, soy un crack ya habia avanzado algo
        if(!$comprobacion->isEmpty() ){

            return back()->with('error_input', 'Ya existe un campo de resultado final en este indicador, por lo que no se puede crear otro.' );
        
        }

    }


    if(count($request->input_promedio) < 2) return back()->with("error", 'Debe agregar almenos un par de campos');
    //termina el bloque de comprobacion del campo final


    // Tremendo, parece que ya tengo un poco de logic implementada
       //contara los inputs que vienen y creara el ciclo para mandarlos a CamposInvolucrados.
       $contador = count($request->input_promedio);
        

       //Sacando el ID para el campo y se pueda gestionar el el combinados de campos al crear nuevos
       $id_input = Date('ydmHis').rand(0,100);
       //Sacando el ID para el campo y se pueda gestionar el el combinados de campos al crear nuevos
        
        
        $campo_calculado = CampoCalculado::create([
            
            "nombre" => $request->nombre,
            "id_input" => $id_input,
            "tipo" => "number",
            "autor" => $autor,
            "operacion" => "promedio",
            "resultado_final" => $request->resultado_final, 
            "referencia" => $request->referencia,
            "id_indicador" => $indicador->id,
            "unidad_medida" => $request->unidad_medida,
            "descripcion" => $request->descripcion,
        ]);

        
        //Este ciclo manda todos los datos de los campos involucrados.
        for($i=0; $i < $contador; $i++){
            
            CampoInvolucrado::create([
                
                "id_input" => $request->input_promedio[$i],
                "tipo" => "number",
                "id_input_calculado" => $campo_calculado->id,
                "posicion" => $i
            ]);
            
        }

        LogBalanced::create([
            'autor' => $autor_log,
            'accion' => "add",
            'descripcion' => "Se creo el campo calculado (promedio): '{$request->nombre}' (ID: {$campo_calculado->id}) en el indicador: {$indicador->nombre}",
            'ip' => request()->ip() 
        ]);

        return back()->with('success', 'El nuevo campo de promedio a sido creado!');
        
}






public function lista_indicadores_admin(Departamento $departamento){



    //manda la fechas a el select que esta en la viues lista_indicadores
    $fechas_seleccionar = IndicadorLleno::where('final', 'on')
        ->selectRaw("DATE_FORMAT(fecha_periodo, '%Y-%m') as periodo")
        ->distinct()
        ->orderBy('periodo')
        ->pluck('periodo');




    //FINALIZA PRUEBAS  PARA LO DE LAS GRAFICAS
    $indicadores = Indicador::with('indicadorLleno')->where('id_departamento', $departamento->id)->get();
    //Este codigo es para sacar el cumplimiento normativo




$ultimoMes = DB::table('cumplimiento_norma')
    ->select('mes')
    ->orderByRaw("STR_TO_DATE(CONCAT('01-', mes), '%d-%m-%y') DESC")
    ->value('mes');

$mes = substr($ultimoMes, 0, 2);
$anio = substr($ultimoMes, 3, 2);

$normas = DB::table('apartado_norma as an')
    ->join('norma as n', 'an.id_norma', '=', 'n.id')
    ->join('departamentos as d', 'n.id_departamento', '=', 'd.id')

    ->leftJoin('cumplimiento_norma as cn', function ($join) use ($mes, $anio) {
        $join->on('cn.id_apartado_norma', '=', 'an.id')
            ->whereRaw("SUBSTRING(cn.mes, 1, 2) = ?", [$mes])
            ->whereRaw("SUBSTRING(cn.mes, 4, 2) = ?", [$anio]);
    })

    ->where('d.id', $departamento->id)

    ->select(
        'n.*',
        DB::raw('COUNT(DISTINCT an.id) as total_apartados'),
        DB::raw('
            (
                SUM(
                    CASE
                        WHEN cn.id_apartado_norma IS NOT NULL THEN 1
                        ELSE 0
                    END
                ) / COUNT(DISTINCT an.id)
            ) * 100 as porcentaje_mes
        ')
    )

    ->groupBy('n.id')
    ->get();





//CODIGO QUE ME AYUDA A MOSTRAR EL CUMPLIMIENTO DE LAS ENCUESTAS
 $encuestas = DB::table('encuestas as e')
    ->leftJoin('preguntas as p', function ($join) {
        $join->on('p.id_encuesta', '=', 'e.id')
             ->where('p.cuantificable', 1);
    })
    ->leftJoin('respuestas as r', 'r.id_pregunta', '=', 'p.id')
    ->where('e.id_departamento', $departamento->id)
    ->select(
        'e.id',
        'e.nombre',
        DB::raw('MAX(e.meta_minima) as meta_minima'),
        DB::raw('MAX(e.meta_esperada) as meta_esperada'),
        DB::raw('COALESCE(ROUND((AVG(r.respuesta) / 10) * 100, 2), 0) as porcentaje_cumplimiento')
    )
    ->groupBy('e.id', 'e.nombre')
    ->get();

//CODIGO QUE ME AYUDA A MOSTRAR EL CUMPLIMIENTO DE LAS ENCUESTAS





 return view('admin.lista_indicadores', compact('indicadores', 'departamento', 'encuestas', 'normas', 'fechas_seleccionar'));

}









public function indicador_lleno_show_admin(Indicador $indicador){

        
    $tipo_indicador = $indicador->tipo_indicador;

    //fehcas de filtrado, si request->fecha_inicio trae algo lo pone en la variable inicio si no deja aa inicio como el inicio del año.
    //se convirtieron las fechas a UTC para que coincidieran con el registro de busqueda.
    $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : Carbon::now(config('app.timezone'))
            //->subMonth()
            ->startOfYear()
            ->utc();

    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();


    //Para lgraficar los datos del indicador
    IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->get();




    $graficar = IndicadorLleno::where('id_indicador', $indicador->id)
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->where(function ($q) {
            $q->where('final', 'on')
            ->orWhere('referencia', 'on');
        })
        ->orderBy('created_at')
        ->get();





    //para graficar os datos del indicaor


    //Para mostrar los datos del indicador
     $datos = IndicadorLleno::where('id_indicador', $indicador->id)->whereBetween('fecha_periodo', [$inicio, $fin])->get();

     $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'asc')->get();



    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();
    
    $grupos = $datos->groupBy('id_movimiento')->sortKeysDesc();

 
    //Se consulta el campo de resultado final.

    $campo_resultado_final = CampoCalculado::where('id_indicador', $indicador->id)->whereNotNull('resultado_final')->where('resultado_final', '!=', '')->first();
    //Para mostrar los datos el indicador

    $campos_llenos = CampoPrecargado::where('id_indicador', $indicador->id)->get();


    return view('admin.indicador_lleno_detalle', compact('indicador', 'campos_llenos', 'graficar', 'datos', 'grupos', 'indicador', 'tipo_indicador', 'promedios', 'info_meses'));


}












//aui empieza el codigo para el llenado de indicadores
public function llenado_informacion_indicadores(Indicador $indicador, Request $request){

    
    //fechas usadas para el llenado de indicadores del año ´pasado:
    $fecha_periodo = Carbon::now()->subMonth();
   // $created_at = Carbon::parse($request->fecha_periodo)->addMonth();


    $nombre_usuario = auth()->user()->name;
    $year = Carbon::now()->year;
    $mes = Carbon::now()->subMonth()->translatedFormat('F');

    //esta es la fechsa periodo que se debe usar en el llenado original de los indicadores.
    $fecha_periodo_original = now()->timezone('America/Mexico_City')->subMonth();

    //$id_movimiento = (string) Str::ulid();

     $id_movimiento = str_replace(':', '',str_replace(' ','',Carbon::now().'-'.random_int(0, 5000000))); //exagere un poquis, pero poatra que no de error


    //validando la insercion de datos

        $request->validate([

            "informacion_indicador" => "required",
            "id_input" => "required",
            "id_input_vacio" => "required",
            "tipo_input" => "required"
        
        ]);


//EN ESTA PARTE SE CARGAN LOS CAMPOS VACIOS AL INDICADORE LLENO....
        for($i=0 ; $i < count($request->informacion_indicador) ; $i++ ){
        
            InformacionInputVacio::create([

                "id_input_vacio" => $request->id_input_vacio[$i],
                "informacion" => $request->informacion_indicador[$i],
                "id_input" => $request->id_input[$i],
                "tipo" => 'number',
                "mes" => $mes,
                "year" => $year

            ]);

            //creo que es aqui, es u for que de acuerdo al numero de campos vacios 
            // 
            IndicadorLleno::create([

                "nombre_campo" => $request->nombre_input_vacio[$i],
                "informacion_campo" => $request->informacion_indicador[$i], 
                "id_indicador" =>$indicador->id,
                "id_movimiento" => $id_movimiento,
                'fecha_periodo' => $fecha_periodo,
                'unidad_medida' => $request->tipo_input[0]
                //'created_at' => $created_at 
            ]);


        }

//EN ESTA PARTE SE CARGAN LOS CAMPOS VACIOS AL INDICADORE LLENO....


//SE AGREGAN LOS INPUTS PRECARGADOS AL INDICADOR LLENO

$inputs_precargados = CampoPrecargado::where('id_indicador', $indicador->id)->get();



foreach($inputs_precargados as $index_precargados => $precargado){


       

     $informacion = InformacionInputPrecargado::where('id_input_precargado', $precargado->id)->latest()->first();


     
        IndicadorLleno::create([
            "nombre_campo" => $precargado->nombre,
            "informacion_campo" => $informacion->informacion,
            "id_indicador" =>$indicador->id,
            "id_movimiento" => $id_movimiento,
            'fecha_periodo' => $fecha_periodo,
            'unidad_medida' => $informacion->unidad_medida
            //'created_at' => $created_at 
        ]);  

}

//SE AGREGAN LOS INPUTS PRECARGADOS AL INDICADOR LLENO



        //Aqui esta la logica nueva 03 de diciembre del 2025
        //Se toman los inputs calculados segun el ID del indicador:

         // $campos_calculados_indicador = CampoCalculado::with('campo_involucrado')->where('id_indicador', $indicador->id)->get();

       $campos_calculados_indicador = CampoCalculado::with(['campo_involucrado' => function ($q) {
                                                            $q->orderBy('posicion', 'asc');
                                                           }])
                                                             ->where('id_indicador', $indicador->id)
                                                            ->get();



        //Aqui es donde se desarrollara la logica, vamos a ver como.
        //se recorren los campos_calculados encontrados en el indicador
        foreach($campos_calculados_indicador as $index_calculado => $campo_calculado) {

            
            $informacion_campos_vacios_encontrados = [];
            $informacion_campos_precargados_encontrados = [];
            $informacion_campos_calculados_encontrados = [];

            $campos_vacios_encontrados = [];
            $campos_precargados_encontrados = [];
            $campos_calculados_encontrados = [];
                    
            


            //arrays que me ayudan a guardar la info de los campos encontrados
                $datos = [];

                foreach ($campo_calculado->campo_involucrado as $index_involucrado => $campo_involucrado) {

                    $existe_en_vacios = CampoVacio::where('id_input', $campo_involucrado->id_input)->latest()->first();
                    $existe_en_precargados = CampoPrecargado::where('id_input', $campo_involucrado->id_input)->latest()->first();
                    $existe_en_calculados = CampoCalculado::where("id_input", $campo_involucrado->id_input)->latest()->first();


                    // Vacíos
                    if ($existe_en_vacios) {

                        $info = InformacionInputVacio::where('id_input', $existe_en_vacios->id)->latest()->first();
                        if ($info) {
                            $datos[$index_involucrado] = $info->informacion;
                        }

                    } elseif ($existe_en_precargados) {

                        $info = InformacionInputPrecargado::where('id_input_precargado', $existe_en_precargados->id)->latest()->first();
                        if ($info) {
                            $datos[$index_involucrado] = $info->informacion;
                        }

                    } elseif ($existe_en_calculados) {

                        $info = InformacionInputCalculado::where('id_input_calculado', $existe_en_calculados->id)->latest()->first();
                        if ($info) {
                            $datos[$index_involucrado] = $info->informacion;
                        }

                    }
                }


               

                // if($index_calculado===0){
                //     return $datos;
                // }
                
                
                  

                    //aqui va la insercion de la info en el campo calculado
                    if($campo_calculado->operacion === "suma"){
                        
                        
                        InformacionInputCalculado::create([
                            
                            'id_input_calculado' => $campo_calculado->id,
                            'tipo' => $campo_involucrado->tipo,
                            'informacion' => round(array_sum($datos), 4),
                            'mes' => $mes,
                            'year' => $year
                            
                            ]);
                            
                            
                            
                            //paso la informacion a la tabla de indicadores llenos
                            
                            IndicadorLleno::create([
                                
                            'nombre_campo' => $campo_calculado->nombre,
                            'informacion_campo' => round(array_sum($datos), 4),
                            'id_indicador' => $indicador->id,
                            'id_movimiento' => $id_movimiento,
                            'final' => $campo_calculado->resultado_final,
                            'referencia' => $campo_calculado->referencia,
                            'fecha_periodo' => $fecha_periodo,
                            'unidad_medida' => $campo_calculado->unidad_medida
                            //'created_at' => $created_at 
                            
                            ]);
                            
                            
                        }
                            
                            

                    if($campo_calculado->operacion === "promedio"){
                                           
    
                        InformacionInputCalculado::create([

                            'id_input_calculado' => $campo_calculado->id,
                            'tipo' => $campo_involucrado->tipo,
                            'informacion' => round(array_sum($datos) / count($datos), 4),
                            'mes' => $mes,
                            'year' => $year

                        ]);



                        IndicadorLleno::create([

                            'nombre_campo' => $campo_calculado->nombre,
                            'informacion_campo' => round(array_sum($datos) / count($datos), 4),
                            'id_indicador' => $indicador->id,
                            'id_movimiento' => $id_movimiento,
                            'final' => $campo_calculado->resultado_final,
                            'referencia' => $campo_calculado->referencia,
                            'fecha_periodo' => $fecha_periodo,
                            'unidad_medida' => $campo_calculado->unidad_medida
                            //'created_at' => $created_at 

                        ]);



                    }

 



                    if($campo_calculado->operacion === "porcentaje"){
                        
                       // return $datos;
                        $porcentaje = ($datos[1] == 0)
                            ? (($datos[0] == 0) ? 100 : 0)
                            : round(($datos[0] / $datos[1]) * 100, 4);


                        InformacionInputCalculado::create([

                            'id_input_calculado' => $campo_calculado->id,
                            'tipo' => $campo_involucrado->tipo,
                            'informacion' => $porcentaje,  
                            'mes' => $mes,
                            'year' => $year

                        ]);



                        IndicadorLleno::create([
                            'nombre_campo' => $campo_calculado->nombre,
                            'informacion_campo' => $porcentaje,
                            'id_indicador' => $indicador->id,
                            'id_movimiento' => $id_movimiento,
                            'final' => $campo_calculado->resultado_final,
                            'referencia' => $campo_calculado->referencia,                            
                            'fecha_periodo' => $fecha_periodo,
                          //  'unidad_medida' => $request->tipo_input[0],
                            'unidad_medida' => $campo_calculado->unidad_medida
                            //'created_at' => $created_at 
                        ]);

                        
                        }

                     
                        // if($index_calculado === 2){

                        //     return $datos;       
                        // }
                        
                        
                    if($campo_calculado->operacion === "division"){
                            
             
                    InformacionInputCalculado::create([

                        'id_input_calculado' => $campo_calculado->id,
                        'tipo' => $campo_involucrado->tipo,
                        'informacion' => round($datos[1] / $datos[0], 4),
                        'mes' => $mes,
                        'year' => $year

                    ]);



                    IndicadorLleno::create([

                        'nombre_campo' => $campo_calculado->nombre,
                        'informacion_campo' => round($datos[1] / $datos[0], 4),
                        'id_indicador' => $indicador->id,
                        'id_movimiento' => $id_movimiento,
                        'final' => $campo_calculado->resultado_final,
                        'referencia' => $campo_calculado->referencia,
                        'fecha_periodo' => $fecha_periodo,
                        //'unidad_medida' => $request->tipo_input[0]
                        'unidad_medida' => $campo_calculado->unidad_medida                        
                        //'created_at' => $created_at 

                    ]);

                }







                    if($campo_calculado->operacion === "resta"){

                        InformacionInputCalculado::create([
                            'id_input_calculado' => $campo_calculado->id,
                            'tipo' => $campo_involucrado->tipo,
                            'informacion' => round(($datos[0] - $datos[1]), 4),
                            'mes' => $mes,
                            'year' => $year
                        ]);


                        IndicadorLleno::create([

                            'nombre_campo' => $campo_calculado->nombre,
                            'informacion_campo' => round(($datos[0] - $datos[1]), 4),
                            'id_indicador' => $indicador->id,
                            'id_movimiento' => $id_movimiento,
                            'final' => $campo_calculado->resultado_final,
                            'referencia' => $campo_calculado->referencia,
                            'fecha_periodo' => $fecha_periodo,
                            //'unidad_medida' => $request->tipo_input[0]
                            'unidad_medida' => $campo_calculado->unidad_medida                            
                            //'created_at' => $created_at 

                        ]);



                    }





                    if($campo_calculado->operacion === "multiplicacion"){



                        InformacionInputCalculado::create([

                            'id_input_calculado' => $campo_calculado->id,
                            'tipo' => $campo_involucrado->tipo,
                            'informacion' => round(array_product($datos), 4),
                            'mes' => $mes,
                            'year' => $year

                        ]);

            

                        IndicadorLleno::create([

                            'nombre_campo' => $campo_calculado->nombre,
                            'informacion_campo' => round(array_product($datos), 4),
                            'id_indicador' => $indicador->id,
                            'id_movimiento' => $id_movimiento,
                            'final' => $campo_calculado->resultado_final,
                            'referencia' => $campo_calculado->referencia,
                            'fecha_periodo' => $fecha_periodo,
                            //'unidad_medida' => $request->tipo_input[0]
                            'unidad_medida' => $campo_calculado->unidad_medida
                            //'created_at' => $created_at 
                        ]);



                    }


   
        }


                //AQUI ESTA EL CODIGO De la persona que gusrada el indicador

                IndicadorLleno::create([

                        "nombre_campo" => 'Registro',
                        "informacion_campo" => $nombre_usuario,
                        "id_indicador" =>$indicador->id,
                        "id_movimiento" => $id_movimiento,
                        "final" => 'registro',
                        'fecha_periodo' => $fecha_periodo,
                        //'created_at' => $created_at 


                ]);
                //AQUI ESTA EL CODIGO De la persona que gusrada el indicador        

        //DEsde aqui se guarda el campo del comenatario
        if($request->info_extra != null){

            IndicadorLleno::create([

                'nombre_campo' => "comentario",
                'informacion_campo' => $request->info_extra,
                'id_indicador' => $indicador->id,
                'id_movimiento' => $id_movimiento,
                'final' => 'comentario',
                'fecha_periodo' => $fecha_periodo,
                //'created_at' => $created_at 

            ]);
        }
        //Desde aqui se guarda el campo del comentario



        //Se agregan las metas del indicador que se tienen al momento de rellenar este mismo


        MetaIndicador::create([

            'meta_minima' => $indicador->meta_minima,
            'meta_maxima' => $indicador->meta_esperada,
            'id_movimiento_indicador_lleno' => $id_movimiento

        ]);


        
        return back()->with('success', 'El indicador fue rellenado ');


}



public function borrar_info_indicador($id){

    IndicadorLleno::where('id_movimiento', $id)->delete();

    return back()->with('deleted', 'La información fue removida.');

}




public function indicador_foraneo_store(Departamento $departamento, Request $request){



    foreach($request->indicador_foraneo as $indicador_foraneo){

        AuxIndicadorForaneo::create([
            "id_departamento" => $departamento->id,
            "id_indicador" => $indicador_foraneo
        ]);

    }


    return back()->with('success', 'Se agrego el indicador foraneo correctamen!');


}


public function eliminar_indicador_foraneo(Departamento $departamento, Indicador $indicador){
    


    AuxIndicadorForaneo::where('id_departamento', $departamento->id)->where('id_indicador', $indicador->id)->delete();


    return back()->with('success', 'Indicador de solo lectura fue eliminado!');


}


public function indicadores_foraneos_user(){

    $id_departamento = Auth::user()->departamento->id;

    $indicadores_foraneos_agregados = Indicador::whereHas('departamentosForaneos', function ($query) use ($id_departamento) {
        $query->where('departamentos.id', $id_departamento);
    })->get();


    return view('user.indicadores_foraneos', compact('indicadores_foraneos_agregados'));
}


public function indicador_lleno_show_user_foraneo(Indicador $indicador){

    $tipo_indicador = $indicador->tipo_indicador;

    //fehcas de filtrado, si request->fecha_inicio trae algo lo pone en la variable inicio si no deja aa inicio como el inicio del año.
    //se convirtieron las fechas a UTC para que coincidieran con el registro de busqueda.
    $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : Carbon::now(config('app.timezone'))
            //->subMonth()
            ->startOfYear()
            ->utc();

    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();


    //Para lgraficar los datos del indicador
    IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->get();

    $promedios = IndicadorLleno::select(
    DB::raw('YEAR(fecha_periodo) as anio'),
    DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();


    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();
        



    $graficar = IndicadorLleno::where('id_indicador', $indicador->id)
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->where(function ($q) {
            $q->where('final', 'on')
            ->orWhere('referencia', 'on');
        })
        ->orderBy('created_at')
        ->get();



    //para graficar os datos del indicaor


    //Para mostrar los datos del indicador
     $datos = IndicadorLleno::where('id_indicador', $indicador->id)->whereBetween('fecha_periodo', [$inicio, $fin])->get();
    
    $grupos = $datos->groupBy('id_movimiento')->sortKeysDesc();

 
    //Se consulta el campo de resultado final.

    $campo_resultado_final = CampoCalculado::where('id_indicador', $indicador->id)->whereNotNull('resultado_final')->where('resultado_final', '!=', '')->first();
    //Para mostrar los datos el indicador

    $campos_llenos = CampoPrecargado::where('id_indicador', $indicador->id)->get();


    return view('user.indicador_foraneo_lleno_detalle', compact('indicador', 'campos_llenos', 'graficar', 'datos', 'grupos', 'indicador', 'tipo_indicador', 'promedios', 'info_meses'));


}











public function analizar_indicador_usuario(Request $request, Indicador $indicador){

    //este es para mostrar los datos en el select
   $campos_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->distinct()->pluck('nombre_campo');
  

   $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : "2025-01-01T06:00:00.000000Z";



    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();



    //datos para graficar...
    $campo_graficar = $request->campos_a_graficar;
    

    $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->first();

    if(!$datos_campo_graficar){
        $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();

    }


    $campo_final = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();
    $campos_referencia = IndicadorLleno::where('id_indicador', $indicador->id)->where('referencia', 'on')->pluck('nombre_campo');



    // De aqui se obtienen los datos del analisis de datos
    $resultado = $this->calcularTendenciaKPI( $indicador->id, $indicador->meta_esperada, $indicador->tipo_indicador, $inicio, $fin); // o 'mayor_mejor'


    



$graficar = IndicadorLleno::where('id_indicador', $indicador->id)
    ->whereBetween('fecha_periodo', [$inicio, $fin])
    ->where(function ($q) use ($campo_graficar, $campo_final, $campos_referencia) {

        //si el usuario hizo un cambio de campo 
        if (!empty($campo_graficar)) {

            //si el usuario seleccion el campo que se grafica por default
            if($campo_graficar === $campo_final->nombre_campo){

                $q->where('final', 'on')
                ->orWhere('referencia', 'on');

            }

            else{

                if(in_array($campo_graficar, json_decode($campos_referencia))){
                    $q->where('nombre_campo', $campo_graficar);
                }
    
                //si el usuario selecciona un campo diferente
                else{
                    $q->where('nombre_campo', $campo_graficar);
                    
                }

            }
            
        }
         
        //si no hay campo seleccionado, es decir,, lo que se muestra desde el inicio.
        else {

            $q->where('final', 'on')
            ->orWhere('referencia', 'on');

        }

    })
->orderBy('fecha_periodo', 'asc')
->get();






//AQUI VA LA CONDICIONAL QUE VA A CAMBIAR TODO LOS DATOS JOJOJOJ

if(!empty($campo_graficar)){

    
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();


    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('nombre_campo', $campo_graficar)
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();




    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('nombre_campo', $campo_graficar)
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('nombre_campo', $campo_graficar)
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        
        
        //para sacar el mejor y el peor mes



// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidad

}



//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA
else{

    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();

    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();

    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('final', 'on')
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        




// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidaD

}
//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA






//Aqui va a ir el codigo que me permite consultar los datos del ultimo mes
 $fechas_seleccionar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->pluck('fecha_periodo');
 

 if($request->mostrar_mes){
     $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('fecha_periodo', $request->mostrar_mes)->where('final', 'on')->first();   
 }
 else{
     $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->latest('fecha_periodo')->first();    
 }

 $campos_llenos = IndicadorLleno::where('id_movimiento', $ultimo_mes->id_movimiento)->get();


//Aqui va a ir el codigo que me permite consultar los datos del ultimo mes




//parece que no hace nda
   $registros_tendencia = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'asc')
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->get();
//parece que no hace nda

    //return view('user.analizando_indicador_user', compact('indicador', 'info_meses', 'promedios', 'graficar', 'historico', 'resultado', 'mejor_mes', 'peor_mes'));


    return view('user.analizando_indicador_user', compact('indicador', 'info_meses', 'promedios', 'graficar', 'historico', 'resultado', 'mejor_mes', 'peor_mes', 'campos_graficar', 'campo_graficar','datos_campo_graficar', 'ultimo_mes', 'fechas_seleccionar', 'campos_llenos')); 

}







public function ver_indicador_desde_perspectiva(Request $request, Indicador $indicador, $inicio, $fin){

  //este es para mostrar los datos en el select
   $campos_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->distinct()->pluck('nombre_campo');
  

//    $inicio = request()->filled('fecha_inicio')
//         ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
//             ->startOfDay()
//             ->utc()
//         : "2025-01-01T06:00:00.000000Z";



//     $fin = request()->filled('fecha_fin')
//         ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
//             //->subMonth()    
//             ->endOfDay()
//             ->utc()

//         : Carbon::now(config('app.timezone'))
//             ->endOfYear()
//             ->utc();



    //datos para graficar...
    $campo_graficar = $request->campos_a_graficar;
    

    $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->first();

    if(!$datos_campo_graficar){
        $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();

    }


    $campo_final = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();
    $campos_referencia = IndicadorLleno::where('id_indicador', $indicador->id)->where('referencia', 'on')->pluck('nombre_campo');



    // De aqui se obtienen los datos del analisis de datos
    $resultado = $this->calcularTendenciaKPI( $indicador->id, $indicador->meta_esperada, $indicador->tipo_indicador, $inicio, $fin); // o 'mayor_mejor'


    



$graficar = IndicadorLleno::where('id_indicador', $indicador->id)
    ->whereBetween('fecha_periodo', [$inicio, $fin])
    ->where(function ($q) use ($campo_graficar, $campo_final, $campos_referencia) {

        //si el usuario hizo un cambio de campo 
        if (!empty($campo_graficar)) {

            //si el usuario seleccion el campo que se grafica por default
            if($campo_graficar === $campo_final->nombre_campo){

                $q->where('final', 'on')
                ->orWhere('referencia', 'on');

            }

            else{

                if(in_array($campo_graficar, json_decode($campos_referencia))){
                    $q->where('nombre_campo', $campo_graficar);
                }
    
                //si el usuario selecciona un campo diferente
                else{
                    $q->where('nombre_campo', $campo_graficar);
                    
                }

            }
            
        }
         
        //si no hay campo seleccionado, es decir,, lo que se muestra desde el inicio.
        else {

            $q->where('final', 'on')
            ->orWhere('referencia', 'on');

        }

    })
->orderBy('fecha_periodo', 'asc')
->get();






//AQUI VA LA CONDICIONAL QUE VA A CAMBIAR TODO LOS DATOS JOJOJOJ

if(!empty($campo_graficar)){

    
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();


    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('nombre_campo', $campo_graficar)
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();




    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('nombre_campo', $campo_graficar)
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('nombre_campo', $campo_graficar)
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        
        
        //para sacar el mejor y el peor mes



// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidad



        if($request->mostrar_mes){
            $ultimo_mes = IndicadorLleno::where('nombre_campo', $campo_graficar)->where('fecha_periodo', $request->mostrar_mes)->first();   
        }
        else{
            $ultimo_mes = IndicadorLleno::where('nombre_campo', $campo_graficar)->latest('fecha_periodo')->first();
            
        }

        $campos_llenos = 'personalizado';
        //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes



}



//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA
else{

    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();

    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();

    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('final', 'on')
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        




// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidaD






    
        if($request->mostrar_mes){
            $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('fecha_periodo', $request->mostrar_mes)->where('final', 'on')->first();   
        }
        else{
            $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->latest()->first();
            
        }

        $campos_llenos = IndicadorLleno::where('id_movimiento', $ultimo_mes->id_movimiento)->get();
        //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes




}
//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA







    //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes
    $fechas_seleccionar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->pluck('fecha_periodo');







//parece que no hace nda
   $registros_tendencia = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'asc')
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->get();
//parece que no hace nda




    return view('admin.analizando_indicador', compact('indicador', 'info_meses', 'promedios', 'graficar', 'historico', 'resultado', 'mejor_mes', 'peor_mes', 'campos_graficar', 'campo_graficar','datos_campo_graficar', 'ultimo_mes', 'fechas_seleccionar', 'campos_llenos', 'inicio', 'fin')); 




}






public function analizar_indicador(Request $request, Indicador $indicador){


    //este es para mostrar los datos en el select
   $campos_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->distinct()->pluck('nombre_campo');
  

   $inicio = request()->filled('fecha_inicio')
        ? Carbon::parse(request('fecha_inicio'), config('app.timezone'))
            ->startOfDay()
            ->utc()
        : "2025-01-01T06:00:00.000000Z";



    $fin = request()->filled('fecha_fin')
        ? Carbon::parse(request('fecha_fin'), config('app.timezone'))
            //->subMonth()    
            ->endOfDay()
            ->utc()

        : Carbon::now(config('app.timezone'))
            ->endOfYear()
            ->utc();



    //datos para graficar...
    $campo_graficar = $request->campos_a_graficar;
    

    $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->first();

    if(!$datos_campo_graficar){
        $datos_campo_graficar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();

    }


    $campo_final = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->first();
    $campos_referencia = IndicadorLleno::where('id_indicador', $indicador->id)->where('referencia', 'on')->pluck('nombre_campo');



    // De aqui se obtienen los datos del analisis de datos
    $resultado = $this->calcularTendenciaKPI( $indicador->id, $indicador->meta_esperada, $indicador->tipo_indicador, $inicio, $fin); // o 'mayor_mejor'


    



$graficar = IndicadorLleno::where('id_indicador', $indicador->id)
    ->whereBetween('fecha_periodo', [$inicio, $fin])
    ->where(function ($q) use ($campo_graficar, $campo_final, $campos_referencia) {

        //si el usuario hizo un cambio de campo 
        if (!empty($campo_graficar)) {

            //si el usuario seleccion el campo que se grafica por default
            if($campo_graficar === $campo_final->nombre_campo){

                $q->where('final', 'on')
                ->orWhere('referencia', 'on');

            }

            else{

                if(in_array($campo_graficar, json_decode($campos_referencia))){
                    $q->where('nombre_campo', $campo_graficar);
                }
    
                //si el usuario selecciona un campo diferente
                else{
                    $q->where('nombre_campo', $campo_graficar);
                    
                }

            }
            
        }
         
        //si no hay campo seleccionado, es decir,, lo que se muestra desde el inicio.
        else {

            $q->where('final', 'on')
            ->orWhere('referencia', 'on');

        }

    })
->orderBy('fecha_periodo', 'asc')
->get();






//AQUI VA LA CONDICIONAL QUE VA A CAMBIAR TODO LOS DATOS JOJOJOJ

if(!empty($campo_graficar)){

    
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('nombre_campo', $campo_graficar)->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();


    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('nombre_campo', $campo_graficar)
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();




    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('nombre_campo', $campo_graficar)
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('nombre_campo', $campo_graficar)
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        
        
        //para sacar el mejor y el peor mes



// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidad



        if($request->mostrar_mes){
            $ultimo_mes = IndicadorLleno::where('nombre_campo', $campo_graficar)->where('fecha_periodo', $request->mostrar_mes)->first();   
        }
        else{
            $ultimo_mes = IndicadorLleno::where('nombre_campo', $campo_graficar)->latest()->first();
            
        }

        $campos_llenos = 'personalizado';
        //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes



}



//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA
else{

    //Para mostrar los datos del indicador
    $info_meses = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->orderBy('fecha_periodo', 'desc')->get();

    $promedios = IndicadorLleno::select(
        DB::raw('YEAR(fecha_periodo) as anio'),
        DB::raw('AVG(informacion_campo) as promedio')
    )
    ->where('final', 'on')
    ->where('id_indicador', $indicador->id)
    ->groupBy(DB::raw('YEAR(fecha_periodo)'))
    ->orderBy('anio')
    ->get();

    //Para sacar la estacionalidad
    $registros = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'desc')
        ->get();


    $historico = $registros->map(function ($item) {
            return [
                'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                'valor' => (float) $item->informacion_campo
            ];
        })
        ->sortBy('mes_num')
        ->sortBy('anio')
        ->values();


        //para sacar el mejor y el peor mes

        $registros_mejor_peor_mes = IndicadorLleno::where('id_indicador', $indicador->id)
            ->where('final', 'on')
            ->orderBy('fecha_periodo', 'desc')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->get();


        $historico_mejor_peor_mes = $registros_mejor_peor_mes->map(function ($item) {
                return [
                    'mes_num' => Carbon::parse($item->fecha_periodo)->format('m'),
                    'mes' => Carbon::parse($item->fecha_periodo)->locale('es')->translatedFormat('F'),
                    'anio' => (int) Carbon::parse($item->fecha_periodo)->format('Y'),
                    'valor' => (float) $item->informacion_campo
                ];
            })
            ->sortBy('mes_num')
            ->sortBy('anio')
            ->values();

        if($indicador->tipo_indicador == "riesgo"){

            $peor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $mejor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  
        }

        if($indicador->tipo_indicador == "normal"){

            $mejor_mes = $historico_mejor_peor_mes->sortByDesc('valor')->first();
            $peor_mes = $historico_mejor_peor_mes->sortBy('valor')->first();  

        }

        




// Para sacar la estacionalidad
        $historico = $historico->map(function ($item) use ($historico) {

            $prev = $historico->first(function ($i) use ($item) {
                return $i['mes_num'] === $item['mes_num']
                    && $i['anio'] === ($item['anio'] - 1);
            });

            $item['valor_anterior'] = $prev['valor'] ?? null;

            $item['diferencia'] = $prev
                ? round($item['valor'] - $prev['valor'], 2)
                : null;

            return $item;
        });

        $historico = $historico->sortBy([
            ['anio', 'desc'],
            ['mes_num', 'desc']
        ])->values();
//Para sacar la estacionalidaD






    
        if($request->mostrar_mes){
            $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('fecha_periodo', $request->mostrar_mes)->where('final', 'on')->first();   
        }
        else{
            $ultimo_mes = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->latest()->first();
            
        }

        $campos_llenos = IndicadorLleno::where('id_movimiento', $ultimo_mes->id_movimiento)->get();
        //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes




}
//CARGA TODOS LOS DATOS POR DEFAULT CON EL CAMPO FINAL COMO REFERENCIA







    //Aqui va a ir el codigo que me permite consultar los datos del ultimo mes
    $fechas_seleccionar = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->pluck('fecha_periodo');







//parece que no hace nda
   $registros_tendencia = IndicadorLleno::where('id_indicador', $indicador->id)
        ->where('final', 'on')
        ->orderBy('fecha_periodo', 'asc')
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->get();
//parece que no hace nda




    return view('admin.analizando_indicador', compact('indicador', 'info_meses', 'promedios', 'graficar', 'historico', 'resultado', 'mejor_mes', 'peor_mes', 'campos_graficar', 'campo_graficar','datos_campo_graficar', 'ultimo_mes', 'fechas_seleccionar', 'campos_llenos')); 

}






function calcularTendenciaKPI( $indicadorId, $meta, $tipo = 'normal', $inicio, $fin)
{
    // =========================
    // 1. OBTENER DATOS
    // =========================

    $registros = IndicadorLleno::where('id_indicador', $indicadorId)
        ->where('final', 'on')
        ->whereBetween('fecha_periodo', [$inicio, $fin])
        ->orderBy('fecha_periodo', 'asc')
        ->get();






    $valores = $registros->pluck('informacion_campo')
        ->map(fn($v) => (float) $v)
        ->toArray();

    $n = count($valores);

    if ($n < 2) {
        return ['mensaje' => 'Sin suficiente información'];
    }

    // =========================
    // 2. EJE X
    // =========================
    $x = range(1, $n);

    $sumX = array_sum($x);
    $sumY = array_sum($valores);

    $sumXY = 0;
    $sumX2 = 0;

    for ($i = 0; $i < $n; $i++) {
        $sumXY += $x[$i] * $valores[$i];
        $sumX2 += $x[$i] * $x[$i];
    }

    // =========================
    // 3. PENDIENTE
    // =========================
    $m = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - pow($sumX, 2));

    // =========================
    // 4. PROMEDIO
    // =========================
    $promedio = $sumY / $n;

    // =========================
    // 5. DESVIACIÓN
    // =========================
    $suma = 0;
    foreach ($valores as $v) {
        $suma += pow($v - $promedio, 2);
    }

    $varianza = $suma / $n;
    $desviacion = sqrt($varianza);

    // =========================
    // 6. UMBRAL
    // =========================
    $umbral = $desviacion * 0.5;

    // =========================
    // 7. TENDENCIA
    // =========================
    if ($tipo == 'normal') {
        if ($m > $umbral) $tendencia = 'mejora';
        elseif ($m < -$umbral) $tendencia = 'deterioro';
        else $tendencia = 'estable';
    } else {
        if ($m < -$umbral) $tendencia = 'mejora';
        elseif ($m > $umbral) $tendencia = 'deterioro';
        else $tendencia = 'estable';
    }

    // =========================
    // 8. CAMBIO
    // =========================
    $inicioVal = $valores[0];
    $ultimo = end($valores);

    $cambio = $ultimo - $inicioVal;

    $cambioPorcentual = ($inicioVal != 0)
        ? (($cambio / $inicioVal) * 100)
        : 0;

    // =========================
    // 9. ESTABILIDAD
    // =========================
    if ($desviacion < ($promedio * 0.05)) {
        $estabilidad = 'muy estable';
    } elseif ($desviacion < ($promedio * 0.15)) {
        $estabilidad = 'moderado';
    } else {
        $estabilidad = 'volatil';
    }

    // =========================
    // 10. CUMPLIMIENTO ACTUAL
    // =========================
    if ($tipo === 'normal') {
        $cumplimiento = ($ultimo >= $meta) ? 'en meta' : 'fuera de meta';
    } else {
        $cumplimiento = ($ultimo <= $meta) ? 'en meta' : 'fuera de meta';
    }

    // =========================
    // 10.1 CUMPLIMIENTO HISTÓRICO
    // =========================
    $cumplen = 0;

    foreach ($valores as $v) {
        if ($tipo === 'normal') {
            if ($v >= $meta) $cumplen++;
        } else {
            if ($v <= $meta) $cumplen++;
        }
    }

    $porcentajeCumplimiento = ($cumplen / $n) * 100;

    if ($porcentajeCumplimiento >= 80) {
        $estadoHistorico = 'historicamente en meta';
    } elseif ($porcentajeCumplimiento >= 50) {
        $estadoHistorico = 'cumplimiento irregular';
    } else {
        $estadoHistorico = 'mayormente fuera de meta';
    }

    // =========================
    // 10.2 Cambio brusco
    // =========================
        $caidaBrusca = false;
        $subidaBrusca = false;

        if ($n >= 2) {
            $ultimoCambio = $valores[$n - 1] - $valores[$n - 2];

            if ($ultimoCambio < 0 && abs($ultimoCambio) > (2 * $desviacion)) {
                $caidaBrusca = true;
            }

            if ($ultimoCambio > 0 && abs($ultimoCambio) > (2 * $desviacion)) {
                $subidaBrusca = true;
            }
        }

    // =========================
    // 11. R²
    // =========================
    $mediaX = $sumX / $n;
    $mediaY = $promedio;

    $ssTot = 0;
    $ssRes = 0;

    for ($i = 0; $i < $n; $i++) {
        $yPred = $m * $x[$i] + ($mediaY - $m * $mediaX);

        $ssTot += pow($valores[$i] - $mediaY, 2);
        $ssRes += pow($valores[$i] - $yPred, 2);
    }

    $r2 = ($ssTot != 0) ? (1 - ($ssRes / $ssTot)) : 0;

    if ($r2 > 0.8) $fuerza = 'fuerte';
    elseif ($r2 > 0.5) $fuerza = 'media';
    else $fuerza = 'debil';

    // =========================
    // 12. ANOMALÍAS
    // =========================
    $anomalias = [];

    foreach ($valores as $i => $v) {
        if (abs($v - $promedio) > (2 * $desviacion)) {
            $anomalias[] = ['indice' => $i, 'valor' => $v];
        }
    }

    // =========================
    // 13. VELOCIDAD
    // =========================
    $velocidades = [];

    for ($i = 1; $i < $n; $i++) {
        $velocidades[] = $valores[$i] - $valores[$i - 1];
    }

    $velocidadPromedio = count($velocidades) > 0
        ? array_sum($velocidades) / count($velocidades)
        : 0;

    // =========================
    // 14. DISTANCIA META
    // =========================
    $meta = (float) $meta;

    $distanciaMeta = $ultimo - $meta;

    $distanciaPorcentual = ($meta != 0)
        ? (($distanciaMeta / $meta) * 100)
        : 0;

    // =========================
    // 15. PROYECCIÓN
    // =========================
    $siguiente = $m * ($n + 1) + ($mediaY - $m * $mediaX);

    // =========================
    // 16. MENSAJE INTELIGENTE
    // =========================
    $mensajes = [];

    if ($tendencia == 'mejora') $mensajes[] = 'Tendencia positiva';
    if ($tendencia == 'deterioro') $mensajes[] = 'Tendencia negativa';
    if ($fuerza == 'debil') $mensajes[] = 'Comportamiento inestable';
    if ($cumplimiento == 'fuera de meta') $mensajes[] = 'No cumple meta';

    if ($cumplimiento == 'fuera de meta' && $porcentajeCumplimiento > 70) {
        $mensajes[] = 'Históricamente cumplía, pero cayó recientemente';
    }

    
    if ($caidaBrusca) {
        $mensajes[] = 'Caída brusca detectada';
    }


    if (count($anomalias) > 0) $mensajes[] = 'Hay anomalías';

    $mensaje = implode(' | ', $mensajes);

    // =========================
    // RETURN FINAL
    // =========================
    return [

        'tendencia' => $tendencia,
        'pendiente' => $m,
        'umbral' => $umbral,
        'desviacion' => $desviacion,
        'promedio' => $promedio,
        'valores' => $valores,
        'cambio' => $cambio,
        'cambio_porcentual' => $cambioPorcentual,
        'estabilidad' => $estabilidad,
        'cumplimiento' => $cumplimiento,
        'mensaje' => $mensaje,
        'ultimo_valor' => $ultimo,
        'subida_brusca' => $subidaBrusca,

        'fuerza_tendencia' => $fuerza,
        'r2' => $r2,
        'anomalias' => $anomalias,
        'velocidad_promedio' => $velocidadPromedio,
        'distancia_meta' => $distanciaMeta,
        'distancia_meta_porcentual' => $distanciaPorcentual,
        'proyeccion_siguiente' => $siguiente,

        'estado_historico' => $estadoHistorico,
        'porcentaje_cumplimiento' => $porcentajeCumplimiento,
        'caida_brusca' => $caidaBrusca,

    
    ];
}







public function indicadores_revision(){


    $inicio = now()->subMonth()->startOfMonth();
    $fin = now()->startOfMonth();

    $indicadores = Indicador::with(['indicadorLleno' => function ($query) use ($inicio, $fin) {
        $query->select('id', 'id_indicador', 'final', 'informacion_campo')
            ->whereBetween('fecha_periodo', [$inicio, $fin])
            ->where('final', 'on');
    }])->get();


    return view('admin.revision_mes_indicadores', compact('indicadores'));

}













}
