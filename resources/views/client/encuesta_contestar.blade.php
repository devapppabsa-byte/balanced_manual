@extends('plantilla')
@section('title', 'Contestando Cuestionario')

@section('contenido')
    

<div class="container-fluid">
    <div class="row bg-primary  d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-8 col-lg-10 pt-2 text-white  text-white">
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

        <div class="col-12 cl-sm-12 col-md-4 col-lg-2 text-center ">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf 
                <button  class="btn btn-primary text-danger text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    
    @include('client.assets.nav')

    <div class="row bg-white shadow-sm border-bottom">
        <div class="col-12 col-sm-12 col-md-4 col-lg-auto m-1 p-2">
            <a class="btn btn-danger btn-sm w-100 px-3 py-1" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#sugerencia">
                <i class="fa-solid fa-comments"></i>
                Queja o sugerencia
            </a>
        </div>
    </div>

</div>


<div class="container-fluid">

    <div class="row justify-content-center mt-3">
        @if (!$preguntas->isEmpty())
            <div class="col-8 p-4 bg-white border shadow-sm rounded-5" style="min-height: 800px">
                <div class="row mb-5">
                    <div class="col-12 text-center my-2 border-bottom">
                        <h2>
                            <i class="fa fa-exclamation-circle"></i>
                            {{$encuesta->nombre}}
                        </h2>
                    </div>  
                </div>
                <form action="{{route('contestar.encuesta', $encuesta->id)}}" method="POST" class="row mt-2 d-flex  justify-content-center">
                    @csrf
                    
                    @php
                        $contador_preguntas = 0;
                    @endphp

                    @forelse ($preguntas as $pregunta) 
                    @php
                        $contador_preguntas ++;
                    @endphp
                        <div class="col-11  p-2 gap-2 my-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>{{$pregunta->pregunta}}</h4>
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
                            <button class="btn btn-primary btn-lg py-3">
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



@endsection