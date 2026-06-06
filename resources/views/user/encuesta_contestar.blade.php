@extends('plantilla')
@section('title', 'Perfil del usuario')
@section('contenido')
<div class="container-fluid sticky-top ">
    <div class="row bg-primary d-flex align-items-center justify-content-start ">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-3">
            <h5 class="text-white">Balance General de {{Auth::user()->departamento->nombre}}</h5>
            {{-- <h6 class="text-white fw-bold" id="fecha"></h6> --}}
            <span class="cascadia-code text-white">Bienvenido(a):  {{ Auth::user()->name }} - {{ Auth::user()->puesto }}</span>
            @if (session('success'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif

            @if (session('actualizado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('actualizado')}}
                </div>
            @endif

            @if (session('eliminado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado')}}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-white  fw-bold p-2 rounded">
                    <i class="fa fa-xmark-circle mx-2  text-danger"></i>
                        No se agrego! <br> 
                    <i class="fa fa-exclamation-circle mx-2  text-danger"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        
        <div class="col-12 cl-sm-12 col-md-6 col-lg-2 text-center ">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf 
                <button  class="btn btn-primary text-danger text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    @include('user.assets.nav')

</div>


<div class="container-fluid">

    <div class="row justify-content-center ">
        <div class="col-8 bg-white p-4 mt-2 rounded-4">
            <h4>
                <i class="fa fa-users"></i>
                Contestar como:
            </h4>
            <select class="form-select" name="cliente" form="encuesta" id="">
                <option value="" selected disabled>Selecciona un cliente</option>
                @forelse ($clientes as $index => $cliente)
                    <option value="{{ $cliente->id }}">{{ $index + 1 }}.- {{ $cliente->nombre }}</option>
                @empty
                    
                @endforelse
            </select>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        @if (!$encuesta->preguntas->isEmpty())
            <div class="col-8 p-4 bg-white border shadow-sm rounded-5">
                <div class="row mb-1">
                    <div class="col-12 text-center my-2 border-bottom">
                        <h4>
                            <i class="fa fa-exclamation-circle"></i>
                            {{$encuesta->nombre}}
                        </h4>
                    </div>  
                </div>
                <form action="{{route('contestando.encuesta.user', $encuesta->id)}}" id="encuesta" method="POST" class="row d-flex  justify-content-around">
                    @csrf
                        @php
                            $contador_preguntas = 0;
                        @endphp
                    @forelse ($encuesta->preguntas as $index_pregunta => $pregunta) 
                    @php
                        $contador_preguntas ++;
                    @endphp
                        <div class="col-5  p-2 gap-2 my-1">
                            <div class="row">
                                <div class="col-12">
                                    <span class="fw-bold">{{ $index_pregunta + 1 }}.- {{$pregunta->pregunta}}</span>
                                </div>
                                @if ($pregunta->cuantificable)
                                    <div class="col-9">
                                        <select name="respuestas[]" class="form-select" id="" required>
                                            <option value="" disabled selected>Calificación del 1 al 10</option>
                                            <option value="0" >0 Puntos</option>
                                            <option value="1" >1 Puntos</option>
                                            <option value="2" >2 Puntos</option>
                                            <option value="3" >3 Puntos</option>
                                            <option value="4" >4 Puntos</option>
                                            <option value="5" >5 Puntos</option>
                                            <option value="6" >6 Puntos</option>
                                            <option value="7" >7 Puntos</option>
                                            <option value="8" >8 Puntos</option>
                                            <option value="9" >9 Puntos</option>
                                            <option value="10" >10 Puntos</option>
                                        </select>
                                    </div>
                                @endif
                                @if(!$pregunta->cuantificable)
                                    <div class="col-9">
                                        <input type="text" value="{{old($contador_preguntas)}}" name="respuestas[]" placeholder="Por favor anote su respuesta" class="form-control" name="{{$pregunta->id}}" required>
                                    </div>
                                @endif
                                    <input type="hidden" name="id[]" value="{{$pregunta->id}}">
                            </div>            
                        </div>
                    @empty
                        
                    @endforelse
                        <div class="col-4 text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class=" fa fa-paper-plane"></i>
                                Enviar
                            </button>
                        </div>
                </form>
            </div>
        @else
            <div class="col-12">
                <div class="row justify-content-center p-5 ">
                    <div class="col-10 border text-center p-5 border-2 rounded-7 bg-white">
                        <img src="{{asset('/img/empty.svg')}}" class="img-fluid w-50" alt="">
                        <h5> <i class="fa fa-exclamation-circle text-danger"></i> Aún no se agregan preguntas a esta encuesta.</h5>
                    </div>
                </div>
            </div>
        @endif


    </div>


</div>
