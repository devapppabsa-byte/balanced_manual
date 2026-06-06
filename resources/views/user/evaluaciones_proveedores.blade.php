@extends('plantilla')
@section('title', 'Evaluaciones a proveedores')

@section('contenido')
<div class="container-fluid">
    <div class="row bg-primary d-flex align-items-center">

        <div class="col-8 col-sm-8 col-md-6 col-lg-9  py-4  py-4 ">
            <h2 class="text-white"> Evaluaciones Proveedores</h2>
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

            @if ($errors->any())
                <div class="bg-white  fw-bold p-2 rounded">
                    <i class="fa fa-xmark-circle mx-2  text-danger"></i>
                        No se agrego! <br> 
                    <i class="fa fa-exclamation-circle mx-2  text-danger"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        <div class="col-4 col-sm-4 col-md-6 col-lg-3 text-end ">
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


<div class="container-fliud">
    <div class="row border-bottom py-2 bg-white">
        <div class="col-12 col-sm-12 col-md-6 col-lg-auto my-1">
            <button class="btn btn-outline-primary btn-sm w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_servicio" {{(Auth::user()->tipo_usuario != "principal") ? 'disabled' : ''  }} >
            <i class="fa-solid fa-edit"></i>
                Evaluar Servicio / Entrega de Porveedor
            </button>
        </div>
    </div>
</div>




<div class="container-fluid py-4">

<div class="row justify-content-center mt-4">
    <div class="col-12 col-lg-11">

        <div class="card border-0 shadow-sm">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-check-to-slot text-primary me-2"></i>
                    Evaluaciones realizadas por
                    <span class="text-muted">
                        {{ Auth::user()->departamento->nombre }}
                    </span>
                </h5>
            </div>

            {{-- Body --}}
            <div class="card-body p-0">

                @if (!$evaluaciones->isEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="ps-4" style="min-width: 130px;">
                                        <small class="text-muted fw-semibold text-uppercase">Fecha</small>
                                    </th>
                                    <th style="min-width: 200px;">
                                        <small class="text-muted fw-semibold text-uppercase">Proveedor</small>
                                    </th>
                                    <th style="min-width: 220px;">
                                        <small class="text-muted fw-semibold text-uppercase">Servicio / Entrega</small>
                                    </th>
                                    <th style="min-width: 240px;">
                                        <small class="text-muted fw-semibold text-uppercase">Observaciones</small>
                                    </th>
                                    <th class="text-center pe-4" style="width: 160px;">
                                        <small class="text-muted fw-semibold text-uppercase">Calificación</small>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($evaluaciones as $evaluacion)
                                    <tr class="border-bottom">

                                        {{-- Fecha --}}
                                        <td class="ps-4">
                                            <i class="fa-solid fa-calendar-day text-muted me-1"></i>
                                            <small class="fw-semibold">
                                                {{$evaluacion->fecha}}
                                            </small>
                                        </td>

                                        {{-- Proveedor --}}
                                        <td>
                                            <span class="fw-semibold text-dark">
                                                {{$evaluacion->proveedor->nombre}}
                                            </span>
                                        </td>

                                        {{-- Servicio --}}
                                        <td>
                                            <span class="text-muted">
                                                {{$evaluacion->descripcion}}
                                            </span>
                                        </td>

                                        {{-- Observaciones --}}
                                        <td>
                                            <small class="text-muted">
                                                {{$evaluacion->observaciones ?: '—'}}
                                            </small>
                                        </td>

                                        {{-- Calificación --}}
                                        <td class="text-center pe-4">
                                            <span class="badge fs-6 px-3 py-2
                                                {{$evaluacion->calificacion >= 80
                                                    ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'
                                                    : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'}}">

                                                <i class="fa-solid {{$evaluacion->calificacion >= 80
                                                    ? 'fa-circle-check'
                                                    : 'fa-circle-xmark'}} me-1"></i>

                                                {{$evaluacion->calificacion}} pts
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center py-4">
                        {{$evaluaciones->links()}}
                    </div>

                @else
                    {{-- Empty state --}}
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <img src="{{asset('/img/iconos/empty.png')}}"
                                 class="img-fluid"
                                 style="max-width: 180px; opacity: .6;"
                                 alt="Sin evaluaciones">
                        </div>
                        <h6 class="text-muted mb-2">
                            No hay evaluaciones aún
                        </h6>
                        <p class="text-muted mb-0">
                            <small>Cuando se registren evaluaciones aparecerán aquí.</small>
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>




</div>












<div class="modal fade" id="agregar_servicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Registrar entrega y/o servicio</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('evaluacion.servicio.store', Auth::user()->id)}}" method="post">
            @csrf
            <div class="row">

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('descrpcion_servicio') ? 'is-invalid' : '' }}" id="descrpcion_servicio" name="descripcion_servicio" required ></textarea>
                                <label class="form-label" for="descrpcion_servicio">Descripción del servicio y/o entrega. <span class="text-danger">*</span> </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 text-center">
                    <div class="form-group mt-2">
                        <div class="form-group" >
                            <select name="proveedor" id="" class="form-control" required>
                                <option value="" disabled selected>Selecciona al proveedor <span class="text-danger">*</span></option>
                                @forelse ($proveedores as $proveedor)
                                    <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                @empty
                                <option value="" disabled selected>No hay Datos</option>                                    
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('observaciones_servicio') ? 'is-invalid' : '' }}" id="observaciones_servicio" name="observaciones_servicio" required ></textarea>
                                <label class="form-label" for="observaciones_servicio">Observaciones <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" min="0" max="100" class="form-control w-100 {{ $errors->first('calificacion') ? 'is-invalid' : '' }} " id="calificacion" name="calificacion" required>
                            <label class="form-label" for="calificacion" >Calificacion del 1 al 100 <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button  class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                <h6>Guardar</h6>
            </button>
        </form>

      </div>
    </div>
  </div>
</div>



@endsection