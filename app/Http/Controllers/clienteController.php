<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\MensajeQuejas;
use App\Models\Queja;
use Carbon\Carbon;
use App\Models\ClienteEncuesta;
use App\Models\Encuesta;
use App\Models\Cliente;
use App\Models\MensajeQueja;
use App\Models\EvidenciaQuejas;
use App\Models\LogBalanced;
use Illuminate\Support\Facades\DB;

class clienteController extends Controller
{


    public function login(){

        return view('client.login_cliente');

    }



    public function perfil_cliente(){

        $encuestas = Encuesta::get();  //
        $id_cliente = Auth::guard('cliente')->user()->id;

        //aqui mero van las graficas del cliente:

        $datos = Respuesta::query()
            ->join('preguntas as p', 'p.id', '=', 'respuestas.id_pregunta')
            ->where('respuestas.id_cliente', $id_cliente)
            ->where('p.cuantificable', '1')
            ->select(
                DB::raw("DATE_FORMAT(respuestas.created_at, '%Y-%m') as mes"),
                DB::raw('AVG(respuestas.respuesta) as promedio')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

            $labels = $datos->pluck('mes')->map(function ($mes) {
                return ucfirst(
                    Carbon::createFromFormat('Y-m', $mes)
                        ->locale('es')
                        ->monthName
                );
            });

            $data = $datos->pluck('promedio')->map(fn ($v) => round($v, 2));

            // Líneas de referencia (1–10)
            $minimo = array_fill(0, count($data), 5);
            $maximo = array_fill(0, count($data), 10);


        

        return view("client.perfil_cliente", compact('encuestas', 'labels', 'data', 'minimo', 'maximo'));
    }



    public function index_cliente(Request $request){

    

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        
        
        $credentials = $request->only('email', 'password');

        if(Auth::guard('cliente')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('perfil.cliente');
        }

        else{
            return back()->with('error', 'Las credenciales no coinciden con los registros');
        }


    }


    public function index_encuesta(Encuesta $encuesta){

        //protegiendo la URL de las encuestas, la consulta esta es para verificar si el cliente aun no contesta 
        //la encuesta o si ya la contesto. Todo eso sobre la tabla Aux. 

        $existe = ClienteEncuesta::where('id_cliente', Auth::guard('cliente')->user()->id)
        ->where('id_encuesta', $encuesta->id)
        ->exists();

        if($existe){

            return back()->with('contestada','Esta encuesta ya fue contestada por el usuario!');

        }

        
        $preguntas = Pregunta::where('id_encuesta', $encuesta->id)->get();

        return view('client.encuesta_contestar', compact("preguntas", "encuesta"));


    }


    public function index_encuesta_contestada(Encuesta $encuesta){

        $id_cliente = Auth::guard('cliente')->user()->id;
                //checo si la encuesta ya fue contestada
        $existe = ClienteEncuesta::where('id_encuesta', $encuesta->id)->get();
        
        //Esto me trae todas las preguntas de la encuesta con sus respuestas 
       //return $preguntas = Pregunta::with('respuestas')->where('id_encuesta', $encuesta->id)->get();

        $preguntas = Pregunta::with(['respuestas' => function ($q) use ($id_cliente) {
                $q->where('id_cliente', $id_cliente);
            }])
            ->where('id_encuesta', $encuesta->id)
            ->get();





        return view('client.encuesta_contestada', compact('encuesta', 'preguntas'));
    
    }


    public function clientes_show_admin(){

        $clientes = Cliente::orderBy('created_at', 'desc')->get();

        return view('admin.gestionar_clientes', compact('clientes'));

    }







public function contestar_encuesta(Request $request, Encuesta $encuesta){

        $cliente = Auth::guard('cliente')->user();
        $autor = 'Cliente ID: '.$cliente->id.' - '.$cliente->nombre;
   
        $respuestas = $request->input('respuestas');


        //Guardando los ID en las tabla auxiliar
        ClienteEncuesta::create([
            "id_cliente" => $cliente->id,
            "id_encuesta" => $encuesta->id
        ]);

        $contador=0;
        foreach($respuestas as $respuesta){

            Respuesta::create([

                "respuesta" => $respuesta,
                "id_pregunta" => $request->input('id')[$contador],
                "id_cliente" => $cliente->id

            ]);

            $contador++;

        }
        
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "El cliente {$cliente->nombre} contesto la encuesta: {$encuesta->nombre}",
            'ip' => request()->ip() 
        ]);
        
        return redirect()->route('perfil.cliente')->with("contestado", 'El cuestionario fue contestado');


    }


public function contestando_encuesta_user(Request $request, Encuesta $encuesta ){

        $cliente = Cliente::find($request->cliente);
        $autor = 'Cliente ID: '.$cliente->id.' - '.$cliente->nombre;
   
        $respuestas = $request->input('respuestas');


        //Guardando los ID en las tabla auxiliar
        ClienteEncuesta::create([
            "id_cliente" => $cliente->id,
            "id_encuesta" => $encuesta->id
        ]);

        $contador=0;
        foreach($respuestas as $respuesta){

            Respuesta::create([

                "respuesta" => $respuesta,
                "id_pregunta" => $request->input('id')[$contador],
                "id_cliente" => $cliente->id

            ]);

            $contador++;

        }
        
        LogBalanced::create([
            'autor' => $autor,
            'accion' => "add",
            'descripcion' => "El cliente {$cliente->nombre} contesto la encuesta: {$encuesta->nombre}",
            'ip' => request()->ip() 
        ]);


        return back()->with('success', 'La encuesta fue contestada por '. $cliente->nombre.' ');

}






public function show_respuestas(Cliente $cliente, Encuesta $encuesta){

        $clienteId = $cliente->id;
        $encuestaId = $encuesta->id;


        $preguntas = Pregunta::with(['respuestas' => function ($q) use ($clienteId) {
                $q->where('id_cliente', $clienteId);
            }])
            ->where('id_encuesta', $encuestaId)
            ->get();


        //se necesitan las respuestas de las encuestas, es decir, consultar las preguntas con su respuesta, todo estom vendra de la tabla auxiliar.
        return view("admin.respuestas_cliente_encuestas", compact('preguntas', 'cliente'));
    
}

public function queja_cliente(Request $request){



    $cliente = Auth::guard('cliente')->user();
    $autor = 'Cliente ID: '.$cliente->id.' - '.$cliente->nombre;

    $request->validate([
        
        'queja' => 'required',
        'titulo' => 'required'
    
    ]);

    $queja = Queja::create([
        "queja" => $request->queja,
        "titulo" => $request->titulo,
        'id_cliente' => $cliente->id
    ]);



    if($request->hasFile('evidencia')){
        
        foreach($request->file('evidencia') as $file){

            //vamos a guardaros en una carpeta diferente
            $ruta = $file->store('evidencias_reclamaciones', 'public');

            EvidenciaQuejas::create([

                'nombre_archivo' => $file->getClientOriginalName(),
                'evidencia' => $ruta,
                'id_queja' => $queja->id

            ]);

        }

    }

    LogBalanced::create([
        'autor' => $autor,
        'accion' => "add",
        'descripcion' => "El cliente {$cliente->nombre} creo una queja: '{$request->titulo}' (ID: {$queja->id})",
        'ip' => request()->ip() 
    ]);

    return back()->with('success', 'La información fue enviada con exito!');
    
}



public function seguimiento_quejas_cliente(Queja $queja){

    $evidencias = EvidenciaQuejas::where('id_queja', $queja->id)->get();
    $comentarios = MensajeQueja::where('id_queja', $queja->id)->get();

    return view('client.seguimiento_quejas', compact('queja', 'evidencias', 'comentarios'));

}



public function lista_quejas_clientes(){

    $quejas = Queja::where('id_cliente', Auth::guard('cliente')->user()->id)->get();
    return view('client.lista_quejas', compact('quejas'));

}


public function comentario_user_reclamo(Queja $queja, Request $request){

    $request->validate([

        'comentario' => 'required'

    ]);

    if(isset(Auth::guard('cliente')->user()->nombre)){
        $autor_nombre = Auth::guard('cliente')->user()->nombre;
        $autor = 'Cliente ID: '.Auth::guard('cliente')->user()->id.' - '.$autor_nombre;
    }
    
    if(isset(Auth::guard('admin')->user()->nombre)){
        $autor_nombre = Auth::guard('admin')->user()->nombre;
        $autor = 'Id: '.Auth::guard('admin')->user()->id.' - '.$autor_nombre.' - '.Auth::guard('admin')->user()->puesto;
    }


    MensajeQueja::create([

        'mensaje' => $request->comentario,
        'remitente' => $autor_nombre,
        'id_queja' => $queja->id

    ]);

    LogBalanced::create([
        'autor' => $autor,
        'accion' => "add",
        'descripcion' => "Se agrego un comentario a la queja ID: {$queja->id} - Título: '{$queja->titulo}'",
        'ip' => request()->ip() 
    ]);

    return back()->with('success', 'El comentario fue enviado');




}


    



}
