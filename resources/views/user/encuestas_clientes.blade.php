@extends('plantilla')
@section('title', 'Encuestas a clientes')


@section('contenido')
<div class="container-fluid">
    <div class="row bg-primary d-flex align-items-center justify-content-start ">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-4">
            <h2 class="text-white">
                <i class="fa-regular fa-file-lines"></i>
                {{Auth::user()->departamento->nombre}} - Cumplimiento Normativo
            </h2>
            <h5 class="text-white fw-bold" id="fecha"></h5>

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





<div class="container-fluid mt-3">
    <div class="row justify-content-center ">
        <div class="col-12 col-sm-12 col-md-11 col-lg-10  p-5">
            <div class="row card py-4">
                <div class="col-12  text-center">
                    <h2>
                        <i class="fa-regular fa-newspaper"></i>
                        Encuestas
                    </h2>
                </div>
            </div>







<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            
            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-clipboard-list text-primary me-2"></i>
                    Encuestas asignadas
                </h5>
            </div>

            {{-- Body --}}
            <div class="card-body p-0">

                @if (!$encuestas->isEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="ps-4" style="min-width: 280px;">
                                        <small class="text-muted fw-semibold text-uppercase">Encuesta</small>
                                    </th>
                                    <th style="min-width: 160px;">
                                        <small class="text-muted fw-semibold text-uppercase">Cumplimiento</small>
                                    </th>
                                    <th class="text-center" style="width: 130px;">
                                        <small class="text-muted fw-semibold text-uppercase">Ponderación</small>
                                    </th>
                                    <th class="text-center pe-4" style="width: 150px;">
                                        <small class="text-muted fw-semibold text-uppercase">Min - Max</small>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($encuestas as $encuesta)
                                    <tr class="border-bottom">

                                        {{-- Nombre + descripción --}}
                                        <td class="ps-4">
                                            <a href="{{route('encuesta.index.user', $encuesta->id)}}"
                                               class="fw-semibold text-dark text-decoration-none"
                                               data-mdb-tooltip-init
                                               title="Ver detalles de {{$encuesta->nombre}}">
                                                {{$encuesta->nombre}}
                                            </a>

                                            <p class="text-muted mb-0">
                                                <small>{{$encuesta->descripcion}}</small>
                                            </p>
                                        </td>

                                        {{-- Cumplimiento --}}
                                        <td>
                                            @php
                                                $suma = 0;
                                                $contador = 0;
                                            @endphp

                                            @foreach ($encuesta->preguntas as $pregunta)
                                                @if ($pregunta->cuantificable === 1)
                                                    @foreach ($pregunta->respuestas as $respuesta)
                                                        @php
                                                            $suma += $respuesta->respuesta;
                                                            $contador++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            @if ($suma > 0 && $contador > 0)
                                                @php
                                                    $porcentaje = round(($suma / ($contador * 10)) * 100, 2);
                                                @endphp

                                                <span class="badge fs-6 px-3 py-2
                                                    {{$porcentaje >= $encuesta->meta_minima
                                                        ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'
                                                        : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'}}"
                                                    data-mdb-tooltip-init
                                                    title="{{$porcentaje}}%">

                                                    {{$porcentaje}}%
                                                    <i class="fa-solid {{$porcentaje >= 50 ? 'fa-circle-check' : 'fa-circle-xmark'}} ms-1"></i>
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <small>Sin respuestas</small>
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Ponderación --}}
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                {{$encuesta->ponderacion}}
                                            </span>
                                        </td>

                                        {{-- Meta --}}
                                        <td class="text-center pe-4">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                                {{$encuesta->meta_minima}} – {{$encuesta->meta_esperada}}
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fa-solid fa-clipboard-list text-muted"
                               style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h6 class="text-muted mb-2">No hay encuestas asignadas</h6>
                        <p class="text-muted mb-0">
                            <small>Aún no se te han asignado encuestas.</small>
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>





        </div>
    </div>
</div>

@endsection