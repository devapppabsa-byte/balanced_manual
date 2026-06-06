@extends('plantilla')
@section('title', 'Seguimiento a quejas')
@section('contenido')

<div class="container-fluid">
    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h3 class="text-white league-spartan">Seguimiento a quejas</h3>

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
                    Cerrar Sesiónss
                </button>
            </form>
        </div>
    </div>

    @include('admin.assets.nav')
</div>




    <div class="container-fluid mt-4 fade-out" id="content">
        <div class="row justify-content-center">
            <div class="col-10 col-sm-10 col-md-10 col-lg-8 bg-white shadow rounded-5">
                <h3 class="p-4 text-center border-bottom">{{$queja->titulo}}</h3>
                <div class="row p-2">

                    <div class="col-6 p-3">
                        <div class="row">
                            <div class="col-12 text-center m-2">
                                <h2 class="">
                                    <i class="fa fa-camera text-primary"></i>
                                    Evidencias
                                </h2>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            @forelse ($evidencias as $evidencia)

                                
                            @if (substr($evidencia->evidencia, -4) === ".pdf")
                                <div class="col-3 m-2" data-mdb-tooltip-init data-mdb-tooltip-initialized="true" data-mdb-original-title="{{$evidencia->nombre_archivo}}">
                                    <a href="{{Storage::url($evidencia->evidencia)}}" target="_blank" > 
                                        <img src="{{asset('/img/iconos/pdf.png')}}" alt="" style="width: 50px; height: 60px;">
                                        <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}}</p>
                                    </a>
                                </div>
                            @endif
                                

                            @if (substr($evidencia->evidencia, -4) === ".jpg" ||  substr($evidencia->evidencia, -4) === ".png" ||  substr($evidencia->evidencia, -5) === ".jpeg" ||  substr($evidencia->evidencia, -4) === ".bmp")

                                <div class="col-3 m-2">
                                    <a href="{{Storage::url($evidencia->evidencia)}}" target="_blank" data-mdb-tooltip-init title="{{$evidencia->nombre_archivo}}"> 
                                        <img src="{{asset('/img/iconos/jpg.png')}}" alt="" style="width: 60px; height: 60px;">
                                        <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}}</p>
                                    </a>
                                </div>
                            @endif

                            @if (substr($evidencia->evidencia, -4) === "docx")
                                <div class="col-3 m-2">
                                    <a href="{{Storage::url($evidencia->evidencia)}}" download="{{$evidencia->nombre_archivo}}" data-mdb-tooltip-init title="{{$evidencia->nombre_archivo}}" > 
                                        <img src="{{asset('/img/iconos/docx.png')}}"  alt="" style="width: 50px; height: 60px;">
                                        <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}} </p>
                                    </a>
                                </div>
                            @endif

                            @if (substr($evidencia->evidencia, -4) === ".mp4")
                                <div class="col-3 m-2">
                                    <a href="#" target="_blank" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#id_vi{{$evidencia->id}}" data-mdb-tooltip-init title="{{$evidencia->nombre_archivo}}"> 
                                        <img src="{{asset('/img/iconos/mp4.png')}}" alt="" style="width: 60px; height: 60px;">
                                        <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}}</p>
                                    </a>
                                </div>

                                <div class="modal fade" id="id_vi{{$evidencia->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
                                    <div class="modal-dialog modal-centered">
                                        <div class="modal-content">

                                            <div class="modal-body text-center">
                                                <video src="{{Storage::url($evidencia->evidencia)}}" class="img-fluid" controls></video>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                                
                            @empty
                                
                            @endforelse
                        </div>
                    </div>


                    <div class="col-6 cascadia bg-light p-4 reportes-scroll border-0">
                        <h3>Seguimiento: </h3>

                        <div class="row">
                            @forelse ($comentarios as $comentario)
                                
                                <div class="col-12 my-3 border-bottom bg-white rounded-4">
                                    <b class="font-size-18">{{$comentario->remitente}}: </b>
                                    <p class="mb-0">{{$comentario->mensaje}}</p>
                                    <small class="font-weight-bold bd-highlight i">{{$comentario->created_at}}</small>
                                </div>
                                
                            @empty
                                
                            @endforelse



                                <div class="col-12 mt-5 ">
                                    <form action="{{route('comentario.user.reclamo', $queja->id)}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <i class="fa fa-comment text-primary"></i>
                                            <b>{{Auth::guard('admin')->user()->nombre}}: </b>
                                            <textarea name="comentario" class="form-control w-100 h-25" autofocus></textarea>
                                        </div>
                                        <div class="from-group mt-2">
                                            <button class="btn btn-primary btn-sm">Enviar</button>
                                        </div>
                                    </form>
                                </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection