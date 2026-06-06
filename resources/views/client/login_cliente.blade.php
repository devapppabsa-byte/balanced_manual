@extends('plantilla')
@section('title', 'Cuestionario del cliente')
@section('contenido')
@php
    Auth::logout();
@endphp
<div class="container-fluid">
    <div class="row   d-flex align-items-center py-2 bg-primary" >
        <div class="col-auto pt-2 text-white">
            <p>
                <i class="fa fa-comment mx-2"></i>
                <span id="titulo" class="h4"></span>
            </p>

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
    </div>
</div>



<div class="container ">
    <div class="row justify-content-center">
        <div class="col-8 col-sm-8 col-md-7 col-lg-4 mt-5 shadow px-4 py-4 rounded border bg-white">
            <div class="row justify-content-center">

                <div class="col-12 mb-2 mb-4 text-center">
                    <div class="row">
                        <div class="col-12">
                            <h5><i class="fa fa-users"></i></h5>
                            <h4 class="">Inicio de Sesión Cliente</h4>
                        </div>
                        <div class="col-12">
                            @if (session("error"))
                                <span class="text-danger fw-bold">
                                    <i class="fa fa-exclamation-circle"></i>
                                    {{session("error")}}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                </div>

                <form action="{{route("index.cliente")}}" method="POST">
                    @csrf
                    <div class="col-12">
    
                        <div class="form-group">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" id="email" name="email" class="form-control form-control-sm" />
                                <label class="form-label" for="email">Correo Eléctronico </label>
                            </div>
                        </div>
    
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="password" class="form-control form-control-sm" id="password" name="password">
                                <label class="form-label" for="password" >Contraseña </label>
                            </div>
                        </div>
    
                        <div class="form-group mt-4">
                            <button class="btn w-100 w-lg-25 btn-primary">
                                Ingresar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
<script>
    var typed = new Typed("#titulo", {
        strings: ['Hola ', 'Ayúdanos a mejorar', 'Queremos conocer tu perspectiva', '¿Qué tal lo hicimos? Tu opinión es clave!', 'Tu opinión nos importa: Cuéntanos sobre tu experiencia' ],
        typeSpeed: 30,
        backSpeed:30,
        loop: true,
        smartBackspace: true
    });
</script>
@endsection