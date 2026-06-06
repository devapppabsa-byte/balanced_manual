@extends('plantilla')
@section('title', 'Evidencia del cumplimiento a la norma')
@section('contenido')
@php
    use Carbon\Carbon;
@endphp
<div class="container-fluid">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-4">
            <h1 class="text-white">Administrador</h1>

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
        <div class="col-10 py-3 text-center bg-white card">
            <h2>
                <i class="fa-solid fa-photo-film"></i>
                Registro del cumplimiento Normativo.
            </h2>
        </div>
    </div>
    <div class="row justify-content-center">
        @forelse ($cumplimientos as $cumplimiento)    
        @php
        Carbon::setLocale('es');
        $mes = Carbon::createFromFormat('m-y-d', $cumplimiento->mes . '-01')
            ->translatedFormat('F Y');
        @endphp
            <div class="col-9 my-1">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item border-2 shadow-sm">
                        <h5 class="accordion-header" id="headingTwo">
                             <div class="badge badge-primary w-100"> {{$mes}}</div>
                            <button  data-mdb-collapse-init class="accordion-button fw-bold  collapsed" type="button" data-mdb-target="#cump{{$cumplimiento->id}}" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fa fa-check-circle me-1 text-primary"></i> 
                                Se marco el mes de como cumplido - {{$cumplimiento->descripcion}}

                     
                        
                            </button>
                        </h5>
                        <div id="cump{{$cumplimiento->id}}" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row">
                                    @forelse ($cumplimiento->evidencia_cumplimiento_norma as $evidencia)
                                        
                                        @if (substr($evidencia->evidencia, -4) === ".pdf")
                                            <div class="col-auto m-2">
                                                <a href="{{Storage::url($evidencia->evidencia)}}" target="_blank" > 
                                                    <img src="{{asset('/img/iconos/pdf.png')}}" alt="" style="width: 50px; height: 60px;">
                                                    <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}}</p>
                                                </a>
                                            </div>
                                        @endif
                                            

                                        @if (substr($evidencia->evidencia, -4) === ".jpg" ||  substr($evidencia->evidencia, -4) === ".png" ||  substr($evidencia->evidencia, -5) === ".jpeg" ||  substr($evidencia->evidencia, -4) === ".bmp")

                                            <div class="col-auto m-2">
                                                <a href="{{Storage::url($evidencia->evidencia)}}" target="_blank" data-mdb-tooltip-init title="{{$evidencia->nombre_archivo}}"> 
                                                    <img src="{{asset('/img/iconos/jpg.png')}}" alt="" style="width: 60px; height: 60px;">
                                                    <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}}</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (substr($evidencia->evidencia, -4) === "docx")
                                            <div class="col-auto m-2">
                                                <a href="{{Storage::url($evidencia->evidencia)}}" download="{{$evidencia->nombre_archivo}}" data-mdb-tooltip-init title="{{$evidencia->nombre_archivo}}" > 
                                                    <img src="{{asset('/img/iconos/docx.png')}}"  alt="" style="width: 50px; height: 60px;">
                                                    <p class="text-truncate" style="max-width: 200px">{{$evidencia->nombre_archivo}} </p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (substr($evidencia->evidencia, -4) === ".mp4")
                                            <div class="col-auto m-2">
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
                                        <div class="col-12 p-5 text-center">
                                            <h2>
                                                <i class="fa fa-exclamation-circle"></i>
                                                No se agrego evidencia
                                            </h2>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            
        @endforelse
    </div>
</div>


@endsection