@extends('plantilla')
@section('title', 'Lista de reclamos')

@section('contenido')
    <div class="container-fluid">
        <div class="row bg-primary  d-flex align-items-center ">
            <div class="col-9 col-sm-9 col-md-8 col-lg-10 pt-2 text-white">
                <h3 class="mt-1 mb-0">
                    {{strtok(Auth::guard("cliente")->user()->nombre, " ")}}, 
                </h3>
                <span>Bienvenido a tus encuestas</span>
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
                @if (session('contestado'))
                    <div class="text-white fw-bold ">
                        <i class="fa fa-check-circle mx-2"></i>
                        {{session('contestado')}}
                    </div>
                @endif

                @if(session('contestada'))
                    <div class="text-white fw-bold ">
                        <i class="fa fa-check-circle mx-2"></i>
                        {{session('contestada')}}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="text-white fw-bold bad_notifications">
                        <i class="fa fa-xmark-circle mx-2"></i>
                        {{$errors->first()}}
                    </div>
                @endif
            </div>

            <div class="col-3 col-sm-3 col-md-4 col-lg-2 text-center ">
                <form action="{{route('cerrar.session')}}" method="POST">
                    @csrf 
                    <button  class="btn  btn-sm text-danger text-white fw-bold">
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



    <div class="container-fluid mt-4">
        <div class="row justify-content-center">

            @forelse ($quejas as $queja)
                <div class="col-10 col-sm-10 col-md-5 col-lg-3 bg-white m-2 p-3 border border-3 rounded-3 shadow">
                        <h4>{{$queja->titulo}}</h4>
                        <div class="text-justify p-2 ck-content">
                            {!!$queja->queja!!}
                        </div>
                        <a href="{{route('seguimiento.quejas.cliente', $queja->id)}}" class="btn btn-success btn btn-sm">
                            <i class="fa fa-paper-plane mx-1"></i>
                            Seguimiento
                        </a>
                </div>    
            @empty

                <div class="col-10 col-sm-10 col-md-10 col-lg-9 py-4 border  bg-white text-center">

                    <img src="{{asset('/img/iconos/empty.png')}}" alt="">
                    <h3>
                        <i class="fa fa-exclamation-circle text-danger"></i>
                        No se encontraron datos
                    </h3>

                </div>


                
            @endforelse
            

            {{-- <div class="col-2 bg-white m-2 p-3 border border-3 rounded-3 shadow">
                <h4>Titulo de queja</h4>
                <p class="text-justify">Ipsum dolor sit amet consectetur adipisicing elit. Dicta similique mollitia repellendus perferendis cumque beatae, voluptas minus recusandae aspernatur molestiae blanditiis, et officiis sed ipsa deserunt accusantium architecto! Nihil, quaerat?</p>
                <button class="btn btn-success btn btn-sm">
                <i class="fa fa-paper-plane mx-1"></i>
                    Seguimiento
                </button>
            </div>

            <div class="col-2 bg-white m-2 p-3 border border-3 rounded-3 shadow">
                <h4>Titulo de queja</h4>
                <p class="text-justify">Ipsum dolor sit amet consectetur adipisicing elit. Dicta similique mollitia repellendus perferendis cumque beatae, voluptas minus recusandae aspernatur molestiae blanditiis, et officiis sed ipsa deserunt accusantium architecto! Nihil, quaerat?</p>
                <button class="btn btn-success btn btn-sm">
                <i class="fa fa-paper-plane mx-1"></i>
                    Seguimiento
                </button>
            </div> --}}


        </div>
    </div>



@endsection