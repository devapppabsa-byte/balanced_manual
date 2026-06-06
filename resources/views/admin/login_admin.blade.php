@extends('plantilla')
@section('title')
@section('contenido')
@php
    Auth::logout();
@endphp
<div class="container-fluid bg-primary py-3 px-4 text-white">
    <div class="row d-flex align-items-center">
        <div class="col-6 text-start ">
            <h3>
                Inicio de sesión administrador
            </h3>
        </div>
        <div class="col-6 text-end">
            <a href="{{route('login')}}" class="btn btn-primary btn-lg">
                <i  class="fa fa-users mx-1"></i>
                Usuarios
            </a>
        </div>
    </div>
</div>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-9 col-lg-5 mt-5 shadow-sm bg-white border p-5">
            <div class="row justify-content-center">
                <div class="col-12 mb-2 mb-4 text-center">
                    <h3>
                        <i class="fa-solid fa-user-tie"></i>
                        Inicio de sesión Admin
                    </h3>
                    @if (session('error'))
                        <span class="text-danger text-center fw-bold">
                            {{session('error')}}
                        </span>
                    @endif

                    

                </div>

                <form action="{{route('admin.login.entrar')}}" method="POST">
                    @csrf
                    <div class="col-12">
    
                        <div class="form-group">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" id="email" name="email" class="form-control form-control-lg" />
                                <label class="form-label" for="email">Correo Eléctronico </label>
                            </div>
                            @if (  $errors->first('email')) 
                                <span class="text-danger fw-bold">  
                                    {{$errors->first('email')}} 
                                </span> 
                            @endif

                        </div>


    
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="password" class="form-control form-control-lg" id="password" name="password">
                                <label class="form-label" for="password" >Contraseña </label>
                            </div>

                            @if ($errors->first('password'))
                                <span class="text-danger fw-bold">
                                    {{$errors->first('password')}}
                                </span>
                            @endif


                        </div>
    
                        <div class="form-group mt-4">
                            <button class="btn btn-primary w-lg-25 w-100  btn-lg">
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