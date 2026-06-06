<?php

namespace App\Http\Controllers;

use App\Models\Queja;
use App\Models\EvidenciaQuejas;
use App\Models\MensajeQueja;
use App\Models\Cliente;
use Illuminate\Http\Request;

class quejasController extends Controller
{
    
    public function index_quejas(){

        $clientes = Cliente::with('quejas')->get();

        return view('admin.quejas', compact('clientes'));

    }


    public function seguimiento_quejas_admin(Queja $queja){

        $evidencias = EvidenciaQuejas::where('id_queja', $queja->id)->get();
        $comentarios = MensajeQueja::where('id_queja', $queja->id)->get(); 

        return view('admin.seguimiento_quejas', compact('queja', 'evidencias', 'comentarios'));


    }




}
