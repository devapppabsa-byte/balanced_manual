@extends('plantilla')
@section('title', 'Gestión de los Departamentos de la Empresa')

@section("contenido")

<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-10 col-lg-10 col-md-6 pt-2 text-white">
            <h3 class="mt-1 league-spartan">Departamentos de la empresa</h3>
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
            @if (session('editado'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('editado')}}
                </div>
            @endif
            @if (session('eliminado'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado')}}
                </div>
            @endif
            @if ($errors->any())
                <div class="text-white fw-bold bad_notifications">
                    <i class="fa fa-xmark-circle mx-2"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-2 text-center">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf 
                <button class="btn btn-primary text-danger text-white fw-bold">
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
                            <i class="fa-solid fa-building text-primary me-2"></i>
                            Departamentos
                        </h2>
                        <p class="text-muted mb-0">
                            <small>Gestión de departamentos de la empresa</small>
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_departamento">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Departamento
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
            <!-- Header Card -->
            @if (!$departamentos->isEmpty())
                <!-- Departamentos Grid -->
                <div class="row g-4">
                    @foreach ($departamentos as $departamento)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="card border-0 shadow-sm h-100 department-card">
                                <div class="card-body p-4 d-flex flex-column">
                                    <!-- Planta Badge -->
                                    <div class="mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                            <i class="fa-solid fa-industry me-1"></i>
                                            {{ $departamento->planta == "1" ? 'Planta 1' : ($departamento->planta == "2" ? 'Planta 2' : ($departamento->planta == "3" ? 'Planta 3' : 'N/A')) }}
                                        </span>
                                    </div>

                                    <!-- Nombre del Departamento -->
                                    <div class="flex-grow-1 mb-3">
                                        <a href="{{route('agregar.indicadores.index', $departamento->id)}}" 
                                           class="text-decoration-none text-dark">
                                            <h5 class="fw-bold mb-0 department-name">
                                                <i class="fa-solid fa-building me-2 text-primary"></i>
                                                {{$departamento->nombre}}
                                            </h5>
                                        </a>
                                    </div>

                                    <!-- Botón Principal -->
                                    <div class="mb-3 text-center">
                                        <a href="{{route('agregar.indicadores.index', $departamento->id)}}" 
                                           class=" w-100 fw-semibold">
                                            <i class="fa-solid fa-chart-line me-2"></i>
                                            Ver Indicadores
                                        </a>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="d-flex justify-content-end align-items-center pt-2 border-top">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-mdb-tooltip-init 
                                                    title="Editar {{$departamento->nombre}}" 
                                                    data-mdb-ripple-init 
                                                    data-mdb-modal-init 
                                                    data-mdb-target="#edit_dep{{$departamento->id}}">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-mdb-tooltip-init 
                                                    title="Eliminar {{$departamento->nombre}}" 
                                                    data-mdb-ripple-init 
                                                    data-mdb-modal-init 
                                                    data-mdb-target="#del_dep{{$departamento->id}}"
                                                    disabled
                                                    >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-building text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay departamentos registrados</h5>
                        <p class="text-muted mb-4">
                            <small>Comienza agregando tu primer departamento al sistema.</small>
                        </p>
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_departamento">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Departamento
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .department-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .department-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .department-name {
        transition: color 0.2s ease;
    }
    
    .department-name:hover {
        color: var(--bs-primary) !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.75rem;
    }
    
    @media (max-width: 768px) {
        .department-card {
            margin-bottom: 1rem;
        }
    }
</style>







{{-- Modales de Eliminación y Edición --}}
@foreach ($departamentos as $departamento)
    <!-- Modal Eliminar Departamento -->
    <div class="modal fade" id="del_dep{{$departamento->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$departamento->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarDepartamentoLabel{{$departamento->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar este departamento?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$departamento->nombre}}</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            {{ $departamento->planta == "1" ? 'Planta 1' : ($departamento->planta == "2" ? 'Planta 2' : ($departamento->planta == "3" ? 'Planta 3' : 'N/A')) }}
                        </small>
                        <small class="text-muted d-block">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('eliminar.departamento', $departamento->id)}}" method="POST">
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

    <!-- Modal Editar Departamento -->
    <div class="modal fade" id="edit_dep{{$departamento->id}}" tabindex="-1" aria-labelledby="editarDepartamentoLabel{{$departamento->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarDepartamentoLabel{{$departamento->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Departamento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('actualizar.departamento', $departamento->id)}}" method="POST">
                        @csrf 
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('nombre_departamento') ? 'is-invalid' : '' }}" 
                                           id="nombre_dep{{$departamento->id}}" 
                                           name="nombre_departamento" 
                                           value="{{old('nombre_departamento', $departamento->nombre)}}"
                                           required>
                                    <label class="form-label" for="nombre_dep{{$departamento->id}}">
                                        Nombre del Departamento
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_departamento'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_departamento') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="planta_edit{{$departamento->id}}" class="form-label fw-semibold">
                                    Planta
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="planta" 
                                        class="form-select {{ $errors->first('planta') ? 'is-invalid' : '' }}" 
                                        id="planta_edit{{$departamento->id}}"
                                        required>
                                    <option value="1" {{old('planta', $departamento->planta) == "1" ? 'selected' : ''}}>Planta 1</option>
                                    <option value="2" {{old('planta', $departamento->planta) == "2" ? 'selected' : ''}}>Planta 2</option>
                                    <option value="3" {{old('planta', $departamento->planta) == "3" ? 'selected' : ''}}>Planta 3</option>
                                </select>
                                @if ($errors->first('planta'))
                                    <div class="invalid-feedback d-block">
                                        {{ $errors->first('planta') }}
                                    </div>
                                @endif
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




<!-- Modal Agregar Departamento -->
<div class="modal fade" id="agregar_departamento" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarDepartamentoLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Departamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('agregar.departamento')}}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('nombre_departamento') ? 'is-invalid' : '' }}" 
                                       id="nombre_departamento" 
                                       name="nombre_departamento"
                                       value="{{old('nombre_departamento')}}"
                                       required>
                                <label class="form-label" for="nombre_departamento">
                                    Nombre del Departamento
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_departamento'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_departamento') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="planta" class="form-label fw-semibold">
                                Planta
                                <span class="text-danger">*</span>
                            </label>
                            <select name="planta" 
                                    class="form-select {{ $errors->first('planta') ? 'is-invalid' : '' }}" 
                                    id="planta"
                                    required>
                                <option value="" disabled selected>Selecciona la planta</option>
                                <option value="1" {{old('planta') == "1" ? 'selected' : ''}}>Planta 1</option>
                                <option value="2" {{old('planta') == "2" ? 'selected' : ''}}>Planta 2</option>
                                <option value="3" {{old('planta') == "3" ? 'selected' : ''}}>Planta 3</option>
                            </select>
                            @if ($errors->first('planta'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('planta') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                            <i class="fa-solid fa-save me-2"></i>
                            Guardar Departamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection