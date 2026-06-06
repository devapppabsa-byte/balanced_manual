@extends('plantilla')
@section('title', 'Quejas presentada')

@section('contenido')
<div class="container-fluid sticky-top">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h3 class="text-white league-spartan">Administrador</h3>

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

    @include('admin.assets.nav')
</div>


<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-11 col-lg-10 shadow-sm rounded-5 border  bg-white p-5">
            <div class="row ">
                <div class="col-12 text-start">
                    <h2 class="mb-1 fw-bold">
                        <i class="fa-solid fa-comments text-primary"></i>
                        Quejas o Sugerencias
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="row   table-responsive">
                        @forelse ($clientes as $cliente)

                            <div class="accordion mt-3" id="accordionExample">
                                <div class="accordion-item border-2 border-primary">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button data-mdb-collapse-init class="accordion-button fw-bold  collapsed" type="button" data-mdb-target="#cli{{$cliente->id}}" aria-expanded="false" aria-controls="collapseTwo">
                                            {{$cliente->nombre}} - {{$cliente->linea}}
                                        </button>
                                    </h2>
                                    <div id="cli{{$cliente->id}}" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row align-items-center">
                                                @forelse ($cliente->quejas as $quejas)
                                                    <div class="col-12 border shadow-sm m-2 py-4">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h3>{{$quejas->titulo}}</h3>
                                                            </div>
                                                            <div class="col-12">
                                                                {!! $quejas->queja !!}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <a href="{{route('seguimiento_quejas.admin', $quejas->id)}}" class="btn btn-primary btn-sm ">
                                                                    <i class="fa fa-eye"></i>
                                                                    Ver seguimiento
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <i class="fa-regular fa-comment"> No hay quejas ni sugerencias.</i>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        @empty
                            <div class="col-12 p-5 text-center p-5 border">

                                <div class="row">
                                    
                                    <div class="col-12">
                                        <i class="fa fa-exclamation-circle text-danger"></i>
                                        No cuenta hay contenido para mostrar
                                    </div>
                                    
                                </div>
                                <h5>
                                </h5>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection