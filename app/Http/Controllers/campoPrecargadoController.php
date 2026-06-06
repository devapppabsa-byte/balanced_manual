<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CampoPrecargado;
use App\Models\Indicador;
use App\Models\InformacionInputPrecargado;
use App\Models\LogBalanced;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CampoForaneoInformacion;

class campoPrecargadoController extends Controller
{
 

    //este es para el campo de prueba
    public function agregar_campo_precargado(Indicador $indicador, Request $request){

       $autor_log = 'Id: '.auth()->guard('admin')->user()->id.' - '.auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;
       $autor = auth()->guard('admin')->user()->nombre .' - '. $puesto_autor = auth()->guard('admin')->user()->puesto;


        //Se separan los datos para poder  
            $datos = [];
            $datos = explode('|', $request->campo_precargado);
            $id_input_informacion = $datos[0];
            $id = $datos[1];
            $nombre = $datos[2];
            $descripcion = $datos[3];
        //Se separan los datos

        //tomo la informacion de la base de datos en base al id 
        $informacion = CampoForaneoInformacion::where('id_campo_foraneo', $id)->latest()->first();

          
             

            $request->validate([

                'campo_precargado' => 'required'

            ]);

            //Aqui tengo que poner la logica, ahora, se supone que en el request vienen los datos....
            
            //se toma el id del input precargado  y el id del indicador para saber donde ponerlo...
            $id_input = date('YmdHis').rand(0,5000);



            //Aqui voy a crear el input en el perfil del departamento, lo qu
            // al momento de guardar este input seria bueno guardar la informacion, pero algo me falta, como voy a actualizar 
            // la informacion mes con mes, puedo agregar la informacion aqui y despues al momento de cagar os nuevs datos, pero como
            //los identifico.
         $input_precargado =  CampoPrecargado::create([

                'id_input' => $id_input,
                'id_input_foraneo' => $id_input_informacion,
                'nombre' => $nombre,
                "autor" => $autor,
                'id_indicador' => $indicador->id,
                'descripcion' => $datos[3]
                
            ]);


            $year = Carbon::now()->year;
            $mes = Carbon::now()->month;

            //Aqui hacemos la insercion de la informacion al campo de informacion input_precargado..

            InformacionInputPrecargado::create([

                'informacion' => $informacion->informacion,
                'id_input_precargado' => $input_precargado->id,
                'mes' => $mes,
                'year' => $year

            ]);






        return back()->with('success', 'El campo fue agregado!');


    }//este es para el campo de prueba








    public function crear_campo_precargado(Request $request){


        $request->validate([

            "nombre_precargado" => 'required',
            "descripcion_precargado" => 'required'

        ]);

         $id_input = date('YmdHis').rand(0,5000); 

         
        CampoPrecargado::create([

            'id_input' => $id_input,
            'nombre' => $request->nombre_precargado,
            'descripcion' => $request->descripcion_precargado,
            ''
            
        ]);


    }



}
