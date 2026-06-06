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

<div class="container">
    
    <div class="row">
        <div class="col-12 text-center my-3 bg-white rounded-3 py-3">
            <h2 class="fw-bold">
                <i class="fa fa-list"></i>
                Lista de Encuestas
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="list-group shadow-lg rounded-4 bg-white">

            @forelse ($encuestas as $encuesta)
                <a href="{{ route('encuesta.contestar.user', $encuesta->id) }}" class="list-group-item list-group-item-action p-3 border-0">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 fw-bold">{{ $encuesta->nombre }}</h5>
                    </div>
                    <p class="mb-1 text-muted">{{ $encuesta->descripcion }}.</p>
                </a>

                {{-- <button class="btn btn-sm">Lista de clientes que ya contestaron</button> --}}
            @empty
                <a href="#" class="list-group-item list-group-item-action p-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 fw-bold">No hay encuestas</h5>
                    </div>
                </a>
            @endforelse
        </div>
    </div>
</div>