@extends('plantilla')
@section('title', 'Encuestas a los clientes')

@section('contenido')
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h1 class="mt-1 league-spartan">
                <i class="fa-solid fa-clipboard-list"></i>
                ENCUESTAS
            </h1>
            @if (session('success'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif
            @if (session('actualizado'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('actualizado')}}
                </div>
            @endif
            @if (session('eliminado'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado')}}
                </div>
            @endif
            @if (session('editado'))
                <div class="text-white fw-bold">
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

    <div class="row">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3 px-4">

                <form action="{{ route('encuestas.show.admin') }}" method="GET">

                    <div class="d-flex flex-wrap align-items-end gap-3">

                        <!-- Fecha inicio -->
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Desde</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-calendar-days text-primary"></i>
                                </span>
                                <input type="date"
                                    name="fecha_inicio"
                                    value="{{ request('fecha_inicio') }}"
                                    class="form-control border-0 bg-light datepicker"
                                    onchange="this.form.submit()">
                            </div>
                        </div>

                        <!-- Fecha fin -->
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Hasta</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-calendar-days text-danger"></i>
                                </span>
                                <input type="date"
                                    name="fecha_fin"
                                    value="{{ request('fecha_fin') }}"
                                    class="form-control border-0 bg-light datepicker"
                                    onchange="this.form.submit()">
                            </div>
                        </div>

                        <!-- Buscador -->
                        <div class="flex-grow-1">
                            <label class="form-label small text-muted fw-semibold mb-1">Buscar</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-search text-dark"></i>
                                </span>
                                <input type="search"
                                    id="buscador"
                                    class="form-control border-0 bg-light"
                                    placeholder="Buscar encuesta...">
                            </div>
                        </div>

                    </div>

                </form>

                <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button class="btn btn-info text-white btn-sm"
                                data-mdb-ripple-init
                                data-mdb-modal-init
                                data-mdb-target="#grafico_mes">
                            <i class="fa-solid fa-chart-line me-1"></i>
                            Gráficas
                        </button>

                        <button class="btn btn-primary btn-sm"
                                data-mdb-ripple-init
                                data-mdb-modal-init
                                data-mdb-target="#agregar_cuestionario">
                            <i class="fa-solid fa-plus-circle me-1"></i>
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <!-- Filtros Card -->
            @if (!$encuestas->isEmpty())
                <!-- Encuestas Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="ps-4" style="min-width: 250px;">
                                            <small class="text-muted fw-semibold text-uppercase">Encuesta</small>
                                        </th>
                                        <th style="min-width: 150px;">
                                            <small class="text-muted fw-semibold text-uppercase">Departamento</small>
                                        </th>
                                        <th class="text-center" style="width: 150px;">
                                            <small class="text-muted fw-semibold text-uppercase">Cumplimiento</small>
                                        </th>
                                        <th style="width: 130px;">
                                            <small class="text-muted fw-semibold text-uppercase">Creada</small>
                                        </th>
                                        <th class="text-center pe-4" style="width: 150px;">
                                            <small class="text-muted fw-semibold text-uppercase">Acciones</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encuestas as $encuesta)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fa-regular fa-newspaper text-primary" style="font-size: 0.875rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{route('encuesta.index', $encuesta->id)}}" 
                                                           class="fw-semibold text-dark text-decoration-none d-block"
                                                           data-mdb-tooltip-init 
                                                           title="Ver detalles de {{$encuesta->nombre}}">
                                                            {{$encuesta->nombre}}
                                                        </a>
                                                        <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">
                                                            {{ Str::limit($encuesta->descripcion, 60) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    {{$encuesta->departamento->nombre}}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php  
                                                    $suma = 0; 
                                                    $contador = 0;    
                                                @endphp

                                                @foreach ($encuesta->preguntas as $pregunta)
                                                    @if ($pregunta->cuantificable === 1)
                                                        @foreach ($pregunta->respuestas as $respuesta)
                                                            @php
                                                                $suma = $suma + $respuesta->respuesta;
                                                                $contador++;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                                @if ($suma > 0 && $contador > 0)
                                                    @php
                                                        $porcentaje = round(($suma/($contador*10))*100, 2);
                                                        $esCumplido = $porcentaje > $encuesta->meta_minima;
                                                    @endphp
                                                    <span class="badge fs-6 border border-2 fw-semibold 
                                                        {{ $esCumplido ? 'bg-success bg-opacity-10 text-success border-success' : 'bg-danger bg-opacity-10 text-danger border-danger' }}" 
                                                        data-mdb-tooltip-init 
                                                        title="Cumplimiento: {{ $porcentaje }}%">
                                                        <i class="fa-solid {{ $esCumplido ? 'fa-check-circle' : 'fa-xmark-circle' }} me-1"></i>
                                                        {{ $porcentaje }}%
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border">
                                                        <i class="fa-solid fa-minus me-1"></i>
                                                        Sin datos
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{$encuesta->created_at->locale('es')->translatedFormat('d M Y')}}
                                                </small>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{route('encuesta.index', $encuesta->id)}}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-mdb-tooltip-init 
                                                       title="Ver detalles">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    @if (!$encuesta->tiene_respuestas)
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                data-mdb-tooltip-init 
                                                                title="Editar {{$encuesta->nombre}}" 
                                                                data-mdb-ripple-init 
                                                                data-mdb-modal-init 
                                                                data-mdb-target="#edit_en{{$encuesta->id}}">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                data-mdb-tooltip-init 
                                                                title="Eliminar {{$encuesta->nombre}}" 
                                                                data-mdb-ripple-init 
                                                                data-mdb-modal-init 
                                                                data-mdb-target="#del_en{{$encuesta->id}}">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" 
                                                                disabled
                                                                data-mdb-tooltip-init 
                                                                title="La encuesta ya fue contestada, no se puede editar ni eliminar.">
                                                            <i class="fa-solid fa-lock"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-regular fa-newspaper text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay encuestas registradas</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    .badge {
        font-size: 0.875rem;
        padding: 0.4rem 0.75rem;
    }
    
    .btn-sm {
        padding: 0.35rem 0.65rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
    }
</style>

<!-- Modal Agregar Encuesta -->
<div class="modal fade" id="agregar_cuestionario" tabindex="-1" aria-labelledby="agregarEncuestaLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarEncuestaLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Encuesta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('encuesta.store.two')}}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" class="form-control {{ $errors->first('nombre_encuesta') ? 'is-invalid' : '' }}" id="nombre_encuesta" value="{{old('nombre_encuesta')}}" name="nombre_encuesta" required>
                                <label class="form-label" for="nombre_encuesta">
                                    Nombre de la Encuesta
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_encuesta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_encuesta') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control {{ $errors->first('descripcion_encuesta') ? 'is-invalid' : '' }}" 
                                          id="descrpcion_encuesta" 
                                          name="descripcion_encuesta" 
                                          rows="4"
                                          required>{{old('descripcion_encuesta')}}</textarea>
                                <label class="form-label" for="descrpcion_encuesta">
                                    Descripción
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('descripcion_encuesta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('descripcion_encuesta') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="departamento" class="form-label fw-semibold">
                                Departamento
                                <span class="text-danger">*</span>
                            </label>
                            <select name="departamento" id="departamento" class="form-select {{ $errors->first('departamento') ? 'is-invalid' : '' }}" required>
                                <option value="" selected disabled>Selecciona un Departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{$departamento->id}}" {{ old('departamento') == $departamento->id ? 'selected' : '' }}>
                                        {{$departamento->nombre}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->first('departamento'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('departamento') }}
                                </div>
                            @endif
                        </div>
                        

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" 
                                       min="0" 
                                       max="100" 
                                       class="form-control {{ $errors->first('meta_minima_encuesta') ? 'is-invalid' : '' }}" 
                                       id="meta_minima_encuesta" 
                                       name="meta_minima_encuesta" 
                                       value="{{old('meta_minima_encuesta')}}" 
                                       required>
                                <label class="form-label" for="meta_minima_encuesta">
                                    Meta Mínima (%)
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('meta_minima_encuesta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('meta_minima_encuesta') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" 
                                       min="0" 
                                       max="100" 
                                       class="form-control {{ $errors->first('meta_esperada_encuesta') ? 'is-invalid' : '' }}" 
                                       id="meta_esperada_encuesta" 
                                       name="meta_esperada_encuesta" 
                                       value="{{old('meta_esperada_encuesta')}}" 
                                       required>
                                <label class="form-label" for="meta_esperada_encuesta">
                                    Meta Esperada (%)
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('meta_esperada_encuesta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('meta_esperada_encuesta') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" 
                                       min="0" 
                                       max="100" 
                                       class="form-control {{ $errors->first('ponderacion_encuesta') ? 'is-invalid' : '' }}" 
                                       id="ponderacion_encuesta" 
                                       name="ponderacion_encuesta" 
                                       value="{{old('ponderacion_encuesta')}}"
                                       required>
                                <label class="form-label" for="ponderacion_encuesta">
                                    Ponderación
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('ponderacion_encuesta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('ponderacion_encuesta') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                            <i class="fa-solid fa-save me-2"></i>
                            Guardar Encuesta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




{{-- Modales de Eliminación y Edición --}}
@foreach ($encuestas as $encuesta)
    <!-- Modal Eliminar Encuesta -->
    <div class="modal fade" id="del_en{{$encuesta->id}}" tabindex="-1" aria-labelledby="eliminarEncuestaLabel{{$encuesta->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarEncuestaLabel{{$encuesta->id}}">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-trash text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="fw-semibold">¿Estás seguro de eliminar esta encuesta?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$encuesta->nombre}}</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('encuesta.delete', $encuesta->id)}}" method="POST">
                        @csrf 
                        @method('DELETE')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary flex-fill" data-mdb-ripple-init data-mdb-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-danger flex-fill" data-mdb-ripple-init>
                                <i class="fa-solid fa-trash me-2"></i>
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Encuesta -->
    <div class="modal fade" id="edit_en{{$encuesta->id}}" tabindex="-1" aria-labelledby="editarEncuestaLabel{{$encuesta->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarEncuestaLabel{{$encuesta->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Encuesta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('encuesta.edit', $encuesta->id)}}" method="post">
                        @csrf 
                        @method("PATCH")
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('nombre_encuesta_edit') ? 'is-invalid' : '' }}" 
                                           id="nombre_encuesta_edit{{$encuesta->id}}" 
                                           value="{{$encuesta->nombre}}" 
                                           name="nombre_encuesta_edit" 
                                           required>
                                    <label class="form-label" for="nombre_encuesta_edit{{$encuesta->id}}">
                                        Nombre de la Encuesta
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_encuesta_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_encuesta_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" 
                                           value="{{$encuesta->meta_minima}}" 
                                           min="0" 
                                           max="100" 
                                           class="form-control {{ $errors->first('meta_minima_encuesta_edit') ? 'is-invalid' : '' }}" 
                                           id="meta_minima_encuesta_edit{{$encuesta->id}}" 
                                           name="meta_minima_encuesta_edit" 
                                           required>
                                    <label class="form-label" for="meta_minima_encuesta_edit{{$encuesta->id}}">
                                        Meta Mínima (%)
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('meta_minima_encuesta_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_minima_encuesta_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" 
                                           value="{{$encuesta->meta_esperada}}" 
                                           min="0" 
                                           max="100" 
                                           class="form-control {{ $errors->first('meta_esperada_encuesta_edit') ? 'is-invalid' : '' }}" 
                                           id="meta_esperada_encuesta_edit{{$encuesta->id}}" 
                                           name="meta_esperada_encuesta_edit" 
                                           required>
                                    <label class="form-label" for="meta_esperada_encuesta_edit{{$encuesta->id}}">
                                        Meta Esperada (%)
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('meta_esperada_encuesta_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_esperada_encuesta_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" 
                                           value="{{$encuesta->ponderacion}}" 
                                           min="0" 
                                           max="100" 
                                           class="form-control {{ $errors->first('ponderacion_encuesta_edit') ? 'is-invalid' : '' }}" 
                                           id="ponderacion_encuesta_edit{{$encuesta->id}}" 
                                           name="ponderacion_encuesta_edit" 
                                           required>
                                    <label class="form-label" for="ponderacion_encuesta_edit{{$encuesta->id}}">
                                        Ponderación
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('ponderacion_encuesta_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('ponderacion_encuesta_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <textarea class="form-control {{ $errors->first('descripcion_encuesta_edit') ? 'is-invalid' : '' }}" 
                                              id="descrpcion_encuesta_edit{{$encuesta->id}}" 
                                              name="descripcion_encuesta_edit" 
                                              rows="4"
                                              required>{{$encuesta->descripcion}}</textarea>
                                    <label class="form-label" for="descrpcion_encuesta_edit{{$encuesta->id}}">
                                        Descripción
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('descripcion_encuesta_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('descripcion_encuesta_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-4">
                            <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                                <i class="fa-solid fa-save me-2"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach








{{-- Modal de Gráficas --}}
<div class="modal fade" id="grafico_mes" tabindex="-1" aria-labelledby="graficasModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-fullscreen-sm-down modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="graficasModalLabel">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Gráficas de Cumplimiento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Tabs navs -->
                <ul class="nav nav-tabs nav-justified mb-4 border-bottom" id="graficasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init 
                           class="nav-link fw-semibold active" 
                           id="tab-barras" 
                           href="#tabs-barras" 
                           role="tab" 
                           aria-controls="tabs-barras" 
                           aria-selected="true">
                            <i class="fa-solid fa-chart-column me-2"></i>
                            Gráfico de Barras
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init 
                           class="nav-link fw-semibold" 
                           id="tab-lineas" 
                           href="#tabs-lineas" 
                           role="tab" 
                           aria-controls="tabs-lineas" 
                           aria-selected="false">
                            <i class="fa-solid fa-chart-line me-2"></i>
                            Gráfico de Línea
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init 
                           class="nav-link fw-semibold" 
                           id="tab-pie" 
                           href="#tabs-pie" 
                           role="tab" 
                           aria-controls="tabs-pie" 
                           aria-selected="false">
                            <i class="fa-solid fa-chart-pie me-2"></i>
                            Gráfico de Pie
                        </a>
                    </li>
                </ul>
                <!-- Tabs content -->
                <div class="tab-content" id="graficasContent">
                    <div class="tab-pane fade show active" id="tabs-barras" role="tabpanel" aria-labelledby="tab-barras">
                        <div class="p-3">
                            <canvas id="grafico_encuestas_barras_"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-lineas" role="tabpanel" aria-labelledby="tab-lineas">
                        <div class="p-3">
                            <canvas id="grafico_encuestas_lineas"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-pie" role="tabpanel" aria-labelledby="tab-pie">
                        <div class="p-3">
                            <canvas id="grafico_encuestas_pie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection






@section('scripts')

<script>
const graficas_encuestas = @json($resultado_encuestas);

if (!graficas_encuestas || graficas_encuestas.length === 0) {
  console.warn('No hay datos para graficar');
} else {

  const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
  ];

  // 🔹 Labels globales
  const labelsRaw = [...new Set(
    graficas_encuestas.flatMap(g => g.labels)
  )].sort();

  const labels = labelsRaw.map(fecha => {
    const [year, month] = fecha.split("-");
    return `${meses[month - 1]} ${year}`;
  });

  const datasets = [];

  // 🔹 Barras por encuesta
  graficas_encuestas.forEach(g => {

    const dataAlineada = labelsRaw.map(mes => {
      const pos = g.labels.indexOf(mes);
      return pos !== -1 ? g.data[pos] * 10 : 0;
    });

    const backgroundColors = dataAlineada.map(valor => {
      if (valor < g.meta_minima) {
        return 'rgba(231, 76, 60, 0.7)';   // rojo
      } else if (valor < g.meta_esperada) {
        return 'rgba(241, 196, 15, 0.7)';  // amarillo
      }
      return 'rgba(46, 204, 113, 0.7)';    // verde
    });

    datasets.push({
      type: 'bar',
      label: g.encuesta,
      data: dataAlineada,
      backgroundColor: backgroundColors,
      borderColor: backgroundColors.map(c => c.replace('0.7','1')),
      borderWidth: 1
    });

    // 🔴 Línea meta mínima
    datasets.push({
      type: 'line',
      label: `Meta mínima (${g.encuesta})`,
      data: labelsRaw.map(() => g.meta_minima),
      borderColor: 'rgba(231, 76, 60, 1)',
      backgroundColor: 'rgba(231, 76, 60, 0.1)',
      borderWidth: 2,
      pointRadius: 0,
      tension: 0,
      fill: false
    });

    // 🟢 Línea meta esperada
    datasets.push({
      type: 'line',
      label: `Meta esperada (${g.encuesta})`,
      data: labelsRaw.map(() => g.meta_esperada),
      borderColor: 'rgba(46, 204, 113, 1)',
      backgroundColor: 'rgba(46, 204, 113, 0.1)',
      borderWidth: 2,
      pointRadius: 0,
      tension: 0,
      fill: false
    });

  });

  new Chart(
    document.getElementById('grafico_encuestas_barras_'),
    {
      type: 'bar',
      data: {
        labels,
        datasets
      },
      options: {
        responsive: true,
        interaction: {
          mode: 'index',
          intersect: false
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: v => v + '%'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              filter: item => !item.text.includes('Meta') || item.text.includes('esperada')
            }
          },
          tooltip: {
            callbacks: {
              label: ctx => `${ctx.dataset.label}: ${ctx.raw}%`
            }
          }
        }
      }
    }
  );
}
</script>

    

<script>
if (!graficas_encuestas || graficas_encuestas.length === 0) {
  console.warn('No hay datos para graficar');
} else {

  const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
  ];

  const labelsRaw = [...new Set(
    graficas_encuestas.flatMap(g => g.labels)
  )].sort();

  const labels = labelsRaw.map(fecha => {
    const [year, month] = fecha.split("-");
    return `${meses[month - 1]} ${year}`;
  });

  const datasets = graficas_encuestas.map(g => {

    const dataAlineada = labelsRaw.map(mes => {
      const pos = g.labels.indexOf(mes);
      return pos !== -1 ? g.data[pos] * 10 : null;
    });

    const pointColors = dataAlineada.map(valor => {
      if (valor === null) return 'rgba(0,0,0,0)';
      if (valor < g.meta_minima) return 'rgba(231, 76, 60, 1)';
      if (valor < g.meta_esperada) return 'rgba(241, 196, 15, 1)';
      return 'rgba(46, 204, 113, 1)';
    });

    return {
      label: g.encuesta,
      data: dataAlineada,
      borderColor: 'rgba(52, 152, 219, 0.8)',
      backgroundColor: 'rgba(52, 152, 219, 0.2)',
      pointBackgroundColor: pointColors,
      pointBorderColor: pointColors,
      borderWidth: 2,
      tension: 0.3,
      fill: false,
      pointRadius: 4,
      pointHoverRadius: 6
    };
  });

  new Chart(
    document.getElementById('grafico_encuestas_lineas'),
    {
      type: 'line',
      data: { labels, datasets },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: v => v + '%'
            }
          }
        }
      }
    }
  );
}
</script>



<script>
if (!graficas_encuestas || graficas_encuestas.length === 0) {
  console.warn('No hay datos para graficar');
} else {

  const labelsRaw = [...new Set(
    graficas_encuestas.flatMap(g => g.labels)
  )].sort();

  const ultimoMes = labelsRaw[labelsRaw.length - 1];

  const labelsPie = [];
  const dataPie = [];
  const colorsPie = [];

  graficas_encuestas.forEach(g => {
    const pos = g.labels.indexOf(ultimoMes);
    if (pos !== -1) {
      const valor = g.data[pos] * 10;

      labelsPie.push(g.encuesta);
      dataPie.push(valor);

      if (valor < g.meta_minima) {
        colorsPie.push('rgba(231, 76, 60, 0.8)');
      } else if (valor < g.meta_esperada) {
        colorsPie.push('rgba(241, 196, 15, 0.8)');
      } else {
        colorsPie.push('rgba(46, 204, 113, 0.8)');
      }
    }
  });

  new Chart(
    document.getElementById('grafico_encuestas_pie'),
    {
      type: 'pie',
      data: {
        labels: labelsPie,
        datasets: [{
          data: dataPie,
          backgroundColor: colorsPie,
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            callbacks: {
              label: ctx => `${ctx.label}: ${ctx.raw}%`
            }
          }
        }
      }
    }
  );
}
</script>







@endsection
    