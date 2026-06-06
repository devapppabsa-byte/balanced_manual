<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EvaluacionProveedor;
use App\Models\Departamento;
use App\Models\Indicador;
use App\Models\Proveedor;
use App\Models\Norma;
use App\Models\ClienteEncuesta;
use App\Models\Pregunta;
use App\Models\LogBalanced;
use App\Models\Cliente;
use App\Models\Respuesta;
use App\Models\Encuesta;
use App\Models\IndicadorLleno;
use App\Models\ApartadoNorma;
use App\Models\CumplimientoNorma;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{

    public function index(){
        return view('inicio');
    }


    public function login_user(Request $request){

        $request->validate([

            'email' => 'required',
            'password' => 'required'

        ]);


        $credentials = $request->only('email', 'password');
        

        //se intenta la utenticacion con attemp
        if(Auth::attempt($credentials)){

            $request->session()->regenerate();


            $autor = 'Id: '.auth()->user()->id.' - '.auth()->user()->name .' - '. $puesto_autor = auth()->user()->puesto;

            LogBalanced::create([
                'autor' => $autor,
                'accion' => "start_session",
                'descripcion' => "El usuario ".$request->email. " inicio sesión como usuario" ,
                'ip' => request()->ip() 
            ]);


            return redirect()->route('perfil.usuario') ;

        }

        else{
            return back()->with("error", 'Error de usuario o contraseña');
        }

    }




    public function perfil_user(){   


        $year = Carbon::now()->year;

        $id_dep = Auth::user()->departamento->id;
        $planta_user = auth()->user()->planta;
        $indicadores_list = Indicador::where('id_departamento', $id_dep)->get();

    

        //vamos a consultar los indicadores de acuerdo a la planta a donde fueron asignados..., ya se tiene los indicadores del departamento.
        //ahora vamos a sacar los indicadores que perteneces al departamento del usuario
      

        


        // EL DESMADRE DE ABAJO ES PARA PODER OBTENER LA PONDERACION Y QUE NO SE LLENEN INDICADORES SI LA PONDERACION CAMBIA
    
        //Teniendo el departamento tomo los indicadores que se le estan registrando.
        $indicadores_ponderaciones = Indicador::where('id_departamento', $id_dep)->get();


        $ponderacion_indicadores = [];
        foreach($indicadores_ponderaciones as $indicador_ponderacion){
            array_push($ponderacion_indicadores, $indicador_ponderacion->ponderacion);
        }



        $normas_ponderaciones = Norma::where('id_departamento', $id_dep)->get();
        $ponderacion_normas = [];
        foreach($normas_ponderaciones as $norma_ponderacion){
            array_push($ponderacion_normas, $norma_ponderacion->ponderacion);
        }



        $encuestas_ponderaciones = Encuesta::where('id_departamento', $id_dep)->get();
        $ponderacion_encuestas = [];
        foreach($encuestas_ponderaciones as $encuesta_ponderacion){
            array_push($ponderacion_encuestas, $encuesta_ponderacion->ponderacion);
        }

        $ponderacion = array_sum(array_merge($ponderacion_indicadores, $ponderacion_normas, $ponderacion_encuestas));




    //TODO EL DESMADRE DE ARRIBA ES PARA PODER OBTENER LA PONDERACION Y QUE NO SE LLENEN INDICADORES SI LA PONDERACION CAMBIA




//Filtro de fechas para los indicadores
$inicio = Carbon::now(config('app.timezone'))
        ->startOfYear()
        ->utc();

$fin =  Carbon::now(config('app.timezone'))
        ->endOfYear()
        ->utc();



$mesActual = now()->format('Y-m');

//GRAFICAS DE CUMPLIMIENTO GENERAL


$normas = Norma::where('id_departamento', $id_dep)->get();

$resultado_normas = [];

foreach ($normas as $norma) {

    // Total de apartados de la norma
    $totalApartados = DB::table('apartado_norma')
        ->where('id_norma', $norma->id)
        ->count();

    // Apartados cumplidos con evidencia en el mes
    $apartadosCumplidos = DB::table('apartado_norma')
        ->join('cumplimiento_norma', 'cumplimiento_norma.id_apartado_norma', '=', 'apartado_norma.id')
        ->join(
            'evidencia_cumplimiento_norma',
            'evidencia_cumplimiento_norma.id_cumplimiento_norma',
            '=',
            'cumplimiento_norma.id'
        )
        ->where('apartado_norma.id_norma', $norma->id)
        ->whereRaw("DATE_FORMAT(cumplimiento_norma.created_at, '%Y-%m') = ?", [$mesActual])
        ->distinct('apartado_norma.id')
        ->count('apartado_norma.id');

    // Porcentaje de cumplimiento
    $porcentaje = $totalApartados > 0
        ? round(($apartadosCumplidos / $totalApartados) * 100, 2)
        : 0;

    // Evaluación contra metas
    if ($porcentaje < $norma->meta_minima) {
        $estatus = 'bajo';
    } elseif ($porcentaje < $norma->meta_esperada) {
        $estatus = 'riesgo';
    } else {
        $estatus = 'cumple';
    }

    $resultado_normas[] = [
        'id_norma'        => $norma->id,
        'norma'           => $norma->nombre,
        'total_apartados' => $totalApartados,
        'cumplimiento'       => $apartadosCumplidos,
        'porcentaje'      => $porcentaje,
        'meta_minima'     => $norma->meta_minima,
        'meta_esperada'   => $norma->meta_esperada,
        'estatus'         => $estatus,
    ];
}










//Consulta SQL que me tra el cumplimiento  de los indicadores multiplicados por su ponderacion.
 $subQuery = DB::table('indicadores_llenos as il')
    ->join('indicadores as i', 'i.id', '=', 'il.id_indicador')
    ->where('il.final', 'on')
    ->where('i.id_departamento', $id_dep)
    ->whereBetween('il.fecha_periodo', [$inicio, $fin])
    ->selectRaw("
        il.id_indicador,
        i.ponderacion,
        DATE_FORMAT(il.created_at, '%Y-%m') as mes,
        AVG(CAST(il.informacion_campo AS DECIMAL(10,2))) as promedio_indicador
    ")
    ->groupBy('il.id_indicador', 'mes', 'i.ponderacion');

$cumplimientoIndicadoresMensual = DB::query()
    ->fromSub($subQuery, 't')
    ->selectRaw("
        mes,
        SUM(ROUND((promedio_indicador * ponderacion)/100, 2)) as cumplimiento_total
    ")
    ->groupBy('mes')
    ->orderBy('mes')
    ->get();





//CONSULTA SQL DE LAS ENCUESTAS..
 $resultado_encuestas = DB::table(DB::raw('
    (
        SELECT 
            DATE_FORMAT(r.created_at, "%Y-%m") AS mes,
            e.id AS encuesta_id,
            e.ponderacion,
            AVG(r.respuesta) AS promedio
        FROM encuestas e
        JOIN preguntas p ON p.id_encuesta = e.id
        JOIN respuestas r ON r.id_pregunta = p.id
        WHERE 
            e.id_departamento = ?
            AND p.cuantificable = 1
            AND r.created_at BETWEEN ? AND ?
        GROUP BY 
            e.id,
            e.ponderacion,
            mes
    ) as sub
'))
->setBindings([
    $id_dep,
    $inicio,
    $fin
])
->select(
    'mes',
    DB::raw('SUM((promedio * (ponderacion / 10))) AS cumplimiento_total')
)
->groupBy('mes')
->orderBy('mes')
->get();






/*
 |------------------------------------------------------------
 | Subconsulta: meses donde hay cumplimiento
 |------------------------------------------------------------
 */
$meses = DB::table('cumplimiento_norma')
    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes")
    ->whereBetween('created_at', [$inicio, $fin])
    ->distinct();

/*
 |------------------------------------------------------------
 | Consulta principal
 |------------------------------------------------------------
 */
 $resultado_norma = DB::table('norma as n')
    ->joinSub($meses, 'm', function ($join) {
        // cross join lógico para evaluar cada norma por mes
        $join->on(DB::raw('1'), '=', DB::raw('1'));
    })
    ->where('n.id_departamento', $id_dep)
    ->select(
        'm.mes',
        DB::raw('
            ROUND(
                SUM(
                    (
                        (
                            SELECT COUNT(*)
                            FROM apartado_norma an2
                            WHERE an2.id_norma = n.id
                              AND EXISTS (
                                  SELECT 1
                                  FROM cumplimiento_norma cn2
                                  WHERE cn2.id_apartado_norma = an2.id
                                    AND DATE_FORMAT(cn2.created_at, "%Y-%m") = m.mes
                              )
                        )
                        /
                        (
                            SELECT COUNT(*)
                            FROM apartado_norma an3
                            WHERE an3.id_norma = n.id
                        )
                    ) * 100 * (n.ponderacion / 100)
                ),
            2) AS cumplimiento_total
        ')
    )
    ->groupBy('m.mes')
    ->orderBy('m.mes')
    ->get();





//CONSULTA SQL DEL CUMPLIMIENTO NORMATIVO..


//Uniendo los campos para generar la grafica del cumplimiento general
 $indicadores = $cumplimientoIndicadoresMensual
    ->pluck('cumplimiento_total', 'mes');

 $encuestas = $resultado_encuestas
    ->pluck('cumplimiento_total', 'mes');

 $normas = $resultado_norma
    ->pluck('cumplimiento_total', 'mes');


$meses = collect()
    ->merge($indicadores->keys())
    ->merge($encuestas->keys())
    ->merge($normas->keys())
    ->unique()
    ->sort()
    ->values();


    $cumplimiento_general = $meses->map(function ($mes) use ($indicadores, $encuestas, $normas) {
        return [
            'mes' => $mes,
            'total' =>
                ($indicadores[$mes] ?? 0) +
                ($encuestas[$mes] ?? 0) +
                ($normas[$mes] ?? 0),
        ];
    });



//gRAFIUCAS DE CUMPLIMIENTO GENRAL.


        

    


        return view('user.perfil_user', compact('indicadores_list', 'ponderacion', 'encuestas', 'resultado_normas', 'cumplimiento_general', 'indicadores'));

    }




    public function eliminar_usuario(User $usuario){

        $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;



        $usuario_eliminado = User::findOrFail($usuario->id);

        $usuario_eliminado->delete();


        //registro del log
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "deleted",
            'descripcion' => "Se elimino el usuario : ".$usuario->name . " con el id: ". $usuario->id,
            'ip' => request()->ip() 
        ]);
        //registro del log


        return back()->with('eliminado_user', 'El usuario ' .$usuario->name. ' fue eliminado');

    }


    public function editar_usuario(User $usuario, Request $request){

    $autor = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        $request->validate([

            'nombre_usuario' => 'required',
            'puesto_usuario' => 'required',
            'correo_usuario' => 'required',
            'departamento' => 'required'

        ]);

        
        $usuario_editar = User::findOrFail($usuario->id);
        
        // Capturar estado anterior para el log
        $cambios = [];
        if($usuario_editar->name != $request->nombre_usuario) {
            $cambios[] = "Nombre: '{$usuario_editar->name}' -> '{$request->nombre_usuario}'";
        }
        if($usuario_editar->email != $request->correo_usuario) {
            $cambios[] = "Email: '{$usuario_editar->email}' -> '{$request->correo_usuario}'";
        }
        if($usuario_editar->puesto != $request->puesto_usuario) {
            $cambios[] = "Puesto: '{$usuario_editar->puesto}' -> '{$request->puesto_usuario}'";
        }
        if($usuario_editar->id_departamento != $request->departamento) {
            $departamento_anterior = Departamento::find($usuario_editar->id_departamento);
            $departamento_nuevo = Departamento::find($request->departamento);
            $cambios[] = "Departamento: '".($departamento_anterior ? $departamento_anterior->nombre : 'N/A')."' -> '".($departamento_nuevo ? $departamento_nuevo->nombre : 'N/A')."'";
        }
        if($request->password_usuario) {
            $cambios[] = "Contraseña: [Actualizada]";
        }
        
        $usuario_editar->name = $request->nombre_usuario;
        $usuario_editar->email = $request->correo_usuario;
        $usuario_editar->puesto = $request->puesto_usuario;
        $usuario_editar->id_departamento = $request->departamento;


        if($request->password_usuario){

            $usuario_editar->password = $request->password_usuario;

        }


        $usuario_editar->save();



        //registro del log
        $descripcion = "Se edito el usuario: ".$usuario_editar->name." (ID: ".$usuario_editar->id.")";
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

        
        return back()->with('editado', 'El usuario fue actualizado!');

    }


    public function usuarios_show_admin(){




        $usuarios = User::orderBy('created_at', 'desc' )->get();
        $departamentos = Departamento::get();

        return view('admin.gestionar_usuarios', compact('usuarios', 'departamentos'));

    }


    public function evaluacion_servicio_store(User $user, Request $request){


        $request->validate([
            'descripcion_servicio' => 'required',
            'proveedor' => 'required',
            'calificacion' => 'required'
        ]);

        $fecha = Carbon::now()->locale('es')->translatedFormat('l j \\d\\e F \\d\\e Y');

        EvaluacionProveedor::create([

            'fecha' => $fecha,
            'calificacion' => $request->calificacion,
            'descripcion' => $request->descripcion_servicio,
            'id_departamento' => $user->departamento->id,
            'observaciones' => $request->observaciones_servicio,
            'id_proveedor' => $request->proveedor

        ]);


        return back()->with('success', 'La evaluación fue agregada');


    }




    public function encuesta_clientes_user(){
        
         $id_user = Auth::user()->id;
         $id_depto = Auth::user()->departamento->id;
         $encuestas = Encuesta::where('id_departamento', $id_depto)->get();



         return view('user.encuestas_clientes', compact('encuestas'));


    }


    public function encuesta_index_user(Encuesta $encuesta){

        
         
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



        return view('user.detalle_encuesta', compact('resultados', 'existe', 'encuesta', 'preguntas', 'clientes', 'labels', 'valores'));


    }


    // public function show_respuestas_cliente(Cliente $cliente, Encuesta $encuesta){


    //     $existe = ClienteEncuesta::where('id_encuesta', $encuesta->id)->get();
        
    //     //Esto me trae todas las preguntas de la encuesta con sus respuestas 
    //     $preguntas = Pregunta::with('respuestas')->where('id_encuesta', $encuesta->id)->get();


    //     //me ayuda a agregar los clientes que ya respondieron las preguntas
    //     $cliente_arr = [];
    //     foreach($existe as $cliente ){
    //         array_push($cliente_arr, $cliente->id_cliente);
    //     }
    //     //los clientes que ya contestaron la encuesta.
    //     $clientes = Cliente::whereIn('id', $cliente_arr)->get();




    //     //DATOS PARA LA GRAFICA DE LA ENCUESTA
    //     $resultados = Respuesta::join('preguntas', 'respuestas.id_pregunta', '=', 'preguntas.id')
    //             ->join('clientes', 'respuestas.id_cliente', '=', 'clientes.id')
    //             ->where('preguntas.id_encuesta', $encuesta->id)
    //             ->where('preguntas.cuantificable', 1)
    //             ->groupBy('clientes.id', 'clientes.nombre')
    //             ->select(
    //                 'clientes.nombre as cliente',
    //                 DB::raw('AVG(respuestas.respuesta) as puntuacion')
    //             )
    //             ->get();

    //         $labels  = $resultados->pluck('cliente');
    //         $valores = $resultados->pluck('puntuacion')->map(fn($v) => round($v, 2));
    //     //DATOS PARA LA HGRAFICA DE LA ENCUESTA
                



    //     return view('user.detalle_encuesta', compact('encuesta', 'preguntas', 'existe', 'clientes', 'labels', 'valores'));


    // }


    public function show_respuestas_usuario(Cliente $cliente, Encuesta $encuesta){

        $clienteId = $cliente->id;
        $encuestaId = $encuesta->id;


        $preguntas = Pregunta::with(['respuestas' => function ($q) use ($clienteId) {
                $q->where('id_cliente', $clienteId);
            }])
            ->where('id_encuesta', $encuestaId)
            ->where('cuantificable', 1)
            ->get();


        //se necesitan las respuestas de las encuestas, es decir, consultar las preguntas con su respuesta, todo estom vendra de la tabla auxiliar.
        return view("user.respuestas_cliente_encuestas", compact('preguntas', 'cliente'));


    }





    public function cerrar_session(Request $request){

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');


    }


    public function cerrar_session_cliente(Request $request){

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login_cliente');


}



}
