@extends('plantilla')
@section('title', 'Encuesta contestada')
@section('contenido')
@php
    use App\Models\Respuesta;
@endphp

<div class="container-fluid">
    <div class="row bg-primary  d-flex align-items-center">
        <div class="col-9 col-sm-9 col-md-8 col-lg-10 pt-2 text-white  text-white">
            <h3 class="mt-1 league-spartan mb-0">
                Hola {{strtok(Auth::guard("cliente")->user()->nombre, " ");}}
            </h3>
            <span>{{ $encuesta->nombre }}</span>
            @if (session('success'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif
            @if (session('editado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('editado')}}
                </div>
            @endif
            @if ($errors->any())
                <div class="text-white fw-bold bad_notifications">
                    <i class="fa fa-xmark-circle mx-2"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        <div class="col-3 cl-sm-3 col-md-4 col-lg-2 text-center ">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf 
                <button  class="btn btn-primary text-danger text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>


    </div>
</div>

<div class="container-fluid">

    <div class="row justify-content-center mt-3">

        <div class="col-12 col-sm-12 col-md-11 col-lg-9 p-4 bg-white border shadow-sm rounded-5" style="min-height: 800px">
            <div class="row mb-5">
                <div class="col-12 text-center my-2 border-bottom">
                    <h4>
                        <i class="fa fa-check-circle"></i>
                        {{$encuesta->nombre}}
                    </h4>
                </div>  
            </div>

            <div class="col-12  p-5 gap-2 my-3 bg-light rounded-7 ">
                <div class="row">
                @foreach($preguntas as $pregunta)
                    <h4>{{ $pregunta->pregunta }}</h4>

                    @if($pregunta->respuestas->isNotEmpty())
                    <p class="mb-3">
                            Calificación:
                            <b>
                                {{ $pregunta->respuestas->first()->respuesta }} 
                            </b>
                            Puntos 
                    </p>
                    @else
                        <em>Sin respuesta</em>
                    @endif
                @endforeach

                </div>            
            </div>

        </div>


    </div>


</div>

@endsection