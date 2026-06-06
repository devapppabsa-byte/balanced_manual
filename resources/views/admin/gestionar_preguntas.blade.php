@extends('plantilla')
@section('title', 'Encuesta')
    
@section('contenido')
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 league-spartan">{{$encuesta->nombre}}</h3>
            @if (session('success'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif
            @if (session('editado'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('editado')}}
                </div>
            @endif
            @if (session('deleted'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('deleted')}}
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <i class="fa-solid fa-clipboard-question text-primary me-2"></i>
                            {{$encuesta->nombre}}
                        </h2>
                        <p class="text-muted mb-0">
                            <small>{{$encuesta->descripcion}}</small>
                        </p>
                    </div>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button class="btn btn-info text-white" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico">
                            <i class="fa-solid fa-chart-line me-2"></i>
                            Ver Gráficas
                        </button>
                        @if ($existe->isEmpty())
                            <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_pregunta">
                                <i class="fa-solid fa-plus-circle me-2"></i>
                                Agregar Pregunta
                            </button>
                        @else
                            <button class="btn btn-secondary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#encuesta_contestada">
                                <i class="fa-solid fa-lock me-2"></i>
                                Encuesta Contestada
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <!-- Header Card -->
            <!-- Two Column Layout -->
            <div class="row g-4">
                <!-- Columna 1: Preguntas -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fa-solid fa-clipboard-question text-primary me-2"></i>
                                Preguntas de la Encuesta
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $numero_preguntas_cuantificables = 0;
                                $total_obtenido = 0;
                            @endphp

                            @if (!$preguntas->isEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light border-bottom">
                                            <tr>
                                                <th class="ps-4" style="min-width: 200px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Pregunta</small>
                                                </th>
                                                <th class="text-center" style="width: 120px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Tipo</small>
                                                </th>
                                                <th class="text-center pe-4" style="width: 80px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Acción</small>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($preguntas as $pregunta)
                                                <tr class="border-bottom">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                                    <i class="fa-solid fa-question text-primary" style="font-size: 0.75rem;"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <small class="text-dark fw-medium">
                                                                    {{$pregunta->pregunta}}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($pregunta->cuantificable === 1 || $pregunta->cuantificable === "on")
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                                <i class="fa-solid fa-check-circle me-1"></i>
                                                                Cuantificable
                                                            </span>
                                                            @foreach ($pregunta->respuestas as $respuesta)
                                                                @php
                                                                    $numero_preguntas_cuantificables = $numero_preguntas_cuantificables + 1;
                                                                    $total_obtenido = $total_obtenido + $respuesta->respuesta;
                                                                @endphp
                                                            @endforeach
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                                                <i class="fa-solid fa-minus me-1"></i>
                                                                No cuantificable
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        @if ($existe->isEmpty())
                                                            <button class="btn btn-sm btn-outline-danger" 
                                                                    data-mdb-tooltip-init 
                                                                    title="Eliminar pregunta" 
                                                                    data-mdb-ripple-init 
                                                                    data-mdb-modal-init 
                                                                    data-mdb-target="#elim{{$pregunta->id}}">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-secondary" 
                                                                    disabled
                                                                    data-mdb-tooltip-init 
                                                                    title="La encuesta ya fue contestada">
                                                                <i class="fa-solid fa-lock"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fa-solid fa-clipboard-question text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No hay preguntas registradas</h6>
                                    <p class="text-muted mb-4">
                                        <small>Comienza agregando preguntas a esta encuesta.</small>
                                    </p>
                                    <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_pregunta">
                                        <i class="fa-solid fa-plus-circle me-2"></i>
                                        Agregar Pregunta
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Clientes y Respuestas -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fa-solid fa-users text-primary me-2"></i>
                                Respuestas de Clientes
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if (!$clientes->isEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light border-bottom">
                                            <tr>
                                                <th class="ps-4" style="min-width: 150px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Cliente</small>
                                                </th>
                                                <th style="min-width: 120px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Línea</small>
                                                </th>
                                                <th class="text-center pe-4" style="width: 140px;">
                                                    <small class="text-muted fw-semibold text-uppercase">Acción</small>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clientes as $cliente)
                                                <tr class="border-bottom">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                                    <i class="fa-solid fa-user text-info" style="font-size: 0.75rem;"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <small class="fw-semibold text-dark d-block">
                                                                    {{$cliente->nombre}}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                            {{$cliente->linea}}
                                                        </span>
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        <a class="btn btn-sm btn-outline-primary" 
                                                           href="{{route('show.respuestas', ['cliente' => $cliente->id, 'encuesta' => $encuesta->id])}}"
                                                           data-mdb-tooltip-init 
                                                           title="Ver respuestas de {{$cliente->nombre}}">
                                                            <i class="fa-solid fa-eye me-1"></i>
                                                            Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fa-solid fa-users text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Aún no hay respuestas</h6>
                                    <p class="text-muted mb-0">
                                        <small>Los clientes aún no han respondido esta encuesta.</small>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados Card -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fa-solid fa-medal text-primary me-2"></i>
                                Resultados de la Encuesta
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 justify-content-center">
                                <div class="col-12 col-md-4">
                                    <div class="text-center p-4 bg-light rounded">
                                        <div class="mb-2">
                                            <i class="fa-solid fa-trophy text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6 class="text-muted fw-semibold mb-2">Puntuación Máxima</h6>
                                        @if ($numero_preguntas_cuantificables != 0)
                                            <h3 class="fw-bold text-primary mb-0">
                                                {{$numero_preguntas_cuantificables * 10}}
                                            </h3>
                                        @else
                                            <small class="text-muted">
                                                <i class="fa-solid fa-exclamation-circle me-1"></i>
                                                Sin respuestas
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <div class="text-center p-4 bg-light rounded">
                                        <div class="mb-2">
                                            <i class="fa-solid fa-star text-success" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6 class="text-muted fw-semibold mb-2">Puntuación Obtenida</h6>
                                        @if ($total_obtenido !== null && $total_obtenido > 0)
                                            <h3 class="fw-bold text-success mb-0">
                                                {{$total_obtenido}}
                                            </h3>
                                        @else
                                            <small class="text-muted">
                                                <i class="fa-solid fa-exclamation-circle me-1"></i>
                                                Sin respuestas
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="text-center p-4 bg-light rounded">
                                        <div class="mb-2">
                                            <i class="fa-solid fa-percent text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6 class="text-muted fw-semibold mb-2">% Cumplimiento</h6>
                                        @if ($total_obtenido !== null && $total_obtenido > 0 && $numero_preguntas_cuantificables > 0)
                                            @php
                                                $porcentaje = round((($total_obtenido) / ($numero_preguntas_cuantificables * 10)) * 100, 2);
                                                $esCumplido = $porcentaje >= ($encuesta->meta_minima ?? 0);
                                            @endphp
                                            <h3 class="fw-bold {{ $esCumplido ? 'text-success' : 'text-danger' }} mb-0">
                                                {{ $porcentaje }}%
                                            </h3>
                                        @else
                                            <small class="text-muted">
                                                <i class="fa-solid fa-exclamation-circle me-1"></i>
                                                Sin respuestas
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    
    .btn-sm {
        padding: 0.35rem 0.65rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .avatar-sm {
            width: 28px;
            height: 28px;
        }
    }
</style>



<!-- Modal Encuesta Contestada -->
<div class="modal fade" id="encuesta_contestada" tabindex="-1" aria-labelledby="encuestaContestadaLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark border-0 py-3">
                <h5 class="modal-title fw-bold" id="encuestaContestadaLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Encuesta Bloqueada
                </h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fa-solid fa-lock text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="fw-semibold">La encuesta ya tiene respuestas</h6>
                    <p class="text-muted mb-0 mt-3">
                        La encuesta ya fue respondida por clientes. Para mantener la integridad de los datos, no se pueden agregar, editar o eliminar preguntas.
                    </p>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-dismiss="modal">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal de Gráficas -->
<div class="modal fade" id="grafico" tabindex="-1" aria-labelledby="graficasModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-fullscreen-sm-down modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="graficasModalLabel">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Gráficas por Cliente
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
                            <canvas id="chartBarras"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-lineas" role="tabpanel" aria-labelledby="tab-lineas">
                        <div class="p-3">
                            <canvas id="chartLinea"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-pie" role="tabpanel" aria-labelledby="tab-pie">
                        <div class="p-3">
                            <canvas id="chartPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











{{-- Modales de Eliminación --}}
@foreach ($preguntas as $pregunta)
    <div class="modal fade" id="elim{{$pregunta->id}}" tabindex="-1" aria-labelledby="eliminarPreguntaLabel{{$pregunta->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarPreguntaLabel{{$pregunta->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar esta pregunta?</h6>
                        <p class="text-muted mb-0 mt-3">
                            <strong>"{{$pregunta->pregunta}}"</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('pregunta.delete', $pregunta->id)}}" method="POST">
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
@endforeach







<!-- Modal Agregar Pregunta -->
<div class="modal fade" id="agregar_pregunta" tabindex="-1" aria-labelledby="agregarPreguntaLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarPreguntaLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('pregunta.store', $encuesta->id)}}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control {{ $errors->first('pregunta') ? 'is-invalid' : '' }}" 
                                          id="pregunta" 
                                          name="pregunta" 
                                          rows="3"
                                          required>{{old('pregunta')}}</textarea>
                                <label class="form-label" for="pregunta">
                                    Escribe tu pregunta
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('pregunta'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('pregunta') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check p-3 bg-light rounded">
                                <input type="checkbox" 
                                       name="cuantificable" 
                                       class="form-check-input" 
                                       id="cuantificable"
                                       value="1"
                                       {{old('cuantificable') ? 'checked' : ''}}>
                                <label class="form-check-label fw-semibold" for="cuantificable">
                                    <i class="fa-solid fa-calculator me-2"></i>
                                    Es cuantificable
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Las preguntas cuantificables se evalúan numéricamente (1-10)
                                </small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <small>
                                    <strong>Nota:</strong> Las preguntas de las encuestas para los clientes se evaluarán con: 
                                    <strong>una puntuación del 1 al 10.</strong>
                                    Por lo que se deben poner preguntas que se puedan contestar.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                            <i class="fa-solid fa-save me-2"></i>
                            Guardar Pregunta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection


@section('scripts')

<script>
const etiquetas = @json($labels);
const valores   = @json($valores);

const minimo = 5;

const colores = valores.map(v =>
    v < minimo ? '#e74c3c' : '#2ecc71'
);

const ctx = document.getElementById('chartBarras').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: etiquetas,
        datasets: [{
            label: 'Puntuación promedio por cliente',
            data: valores,
            backgroundColor: colores,
            borderColor: '#2c3e50',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Resultados de la encuesta por cliente'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 10,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>






<script>




const coloresPuntos = valores.map(v =>
    v < minimo ? '#e74c3c' : '#2ecc71'
);

const ctxlinea = document.getElementById('chartLinea').getContext('2d');

new Chart(ctxlinea, {
    type: 'line',
    data: {
        labels: etiquetas,
        datasets: [{
            label: 'Puntuación promedio por cliente',
            data: valores,
            borderColor: '#2980b9',
            backgroundColor: 'rgba(52, 152, 219, 0.15)',
            fill: true,
            tension: 0.3,
            pointBackgroundColor: coloresPuntos,
            pointBorderColor: '#2c3e50',
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Resultados de la encuesta por cliente'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 10,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<script>
    // Colores automáticos (uno por cliente)
    const coloresPie = etiquetas.map((_, i) => {
        const paleta = [
            '#3498db', '#2ecc71', '#e74c3c', '#f1c40f',
            '#9b59b6', '#1abc9c', '#e67e22', '#34495e'
        ];
        return paleta[i % paleta.length];
    });

    const ctxPie = document.getElementById('chartPie').getContext('2d');

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Puntuación por cliente',
                data: valores,
                backgroundColor: coloresPie,
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribución de puntuación por cliente'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const valor = context.parsed;
                            const porcentaje = ((valor / total) * 100).toFixed(1);
                            return `${context.label}: ${valor} (${porcentaje}%)`;
                        }
                    }
                }
            }
        }
    });
</script>






{{-- AQUI COMIENZAN LOS RAFICOS POR MES --}}







{{-- AQUI COMIENZAN LOS RAFICOS POR MES --}}














 
@endsection