@extends('plantilla')
@section('title', 'Gestionar usuarios de la empresa')

@section('contenido')
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 league-spartan">Usuarios de la empresa</h3>
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
            @if (session('eliminado_user'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado_user')}}
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
                            <i class="fa-solid fa-users-gear text-primary me-2"></i>
                            Usuarios
                        </h2>
                        <p class="text-muted mb-0">
                            <small>Gestión de usuarios del sistema</small>
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_usuario">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Usuario
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
            @if (!$usuarios->isEmpty())
                <!-- Usuarios Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="ps-4" style="min-width: 250px;">
                                            <small class="text-muted fw-semibold text-uppercase">Usuario</small>
                                        </th>
                                        <th style="min-width: 200px;">
                                            <small class="text-muted fw-semibold text-uppercase">Departamento y Puesto</small>
                                        </th>
                                        <th class="text-center pe-4" style="width: 120px;">
                                            <small class="text-muted fw-semibold text-uppercase">Acciones</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fa-solid fa-user text-primary" style="font-size: 0.875rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold text-dark mb-1">
                                                            {{$usuario->name}}
                                                        </div>
                                                        <a href="mailto:{{$usuario->email}}" 
                                                           class="text-decoration-none text-muted d-flex align-items-center" 
                                                           data-mdb-tooltip-init 
                                                           title="Enviar correo a {{$usuario->email}}"
                                                           target="_blank">
                                                            <i class="fa-solid fa-envelope me-2 text-primary" style="font-size: 0.75rem;"></i>
                                                            <small>{{$usuario->email}}</small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 mb-1">
                                                        <i class="fa-solid fa-briefcase me-1"></i>
                                                        {{$usuario->puesto}}
                                                    </span>
                                                    <div class="mt-1">
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="fa-solid fa-building me-1"></i>
                                                            {{$usuario->departamento->nombre}}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            data-mdb-tooltip-init 
                                                            title="Editar {{$usuario->name}}" 
                                                            data-mdb-ripple-init 
                                                            data-mdb-modal-init 
                                                            data-mdb-target="#edit_user{{$usuario->id}}">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            data-mdb-tooltip-init 
                                                            title="Eliminar {{$usuario->name}}" 
                                                            data-mdb-ripple-init 
                                                            data-mdb-modal-init 
                                                            data-mdb-target="#del_user{{$usuario->id}}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
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
                            <i class="fa-solid fa-users-gear text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay usuarios registrados</h5>
                        <p class="text-muted mb-4">
                            <small>Comienza agregando tu primer usuario al sistema.</small>
                        </p>
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_usuario">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Usuario
                        </button>
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
            width: 32px;
            height: 32px;
        }
    }
</style>





<!-- Modal Agregar Usuario -->
<div class="modal fade" id="agregar_usuario" tabindex="-1" aria-labelledby="agregarUsuarioLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarUsuarioLabel">
                    <i class="fa-solid fa-user-plus me-2"></i>
                    Agregar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('agregar.usuario')}}" method="post" onkeydown="return event.key !='Enter';">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('nombre_usuario') ? 'is-invalid' : '' }}" 
                                       id="nombre_usuario" 
                                       name="nombre_usuario"
                                       value="{{old('nombre_usuario')}}"
                                       required>
                                <label class="form-label" for="nombre_usuario">
                                    Nombre
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_usuario'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_usuario') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="email" 
                                       class="form-control {{ $errors->first('correo_usuario') ? 'is-invalid' : '' }}" 
                                       name="correo_usuario"
                                       value="{{old('correo_usuario')}}"
                                       required>
                                <label class="form-label" for="correo_usuario">
                                    Correo Electrónico
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('correo_usuario'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('correo_usuario') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="password" 
                                       class="form-control {{ $errors->first('password_usuario') ? 'is-invalid' : '' }}" 
                                       name="password_usuario"
                                       value="{{old('password_usuario')}}"
                                       required>
                                <label class="form-label" for="password_usuario">
                                    Contraseña
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('password_usuario'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password_usuario') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('puesto_usuario') ? 'is-invalid' : '' }}" 
                                       name="puesto_usuario"
                                       value="{{old('puesto_usuario')}}"
                                       required>
                                <label class="form-label" for="puesto_usuario">
                                    Puesto
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('puesto_usuario'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('puesto_usuario') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="tipo_usuario" class="form-label fw-semibold">
                                Tipo de Usuario
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select {{ $errors->first('tipo_usuario') ? 'is-invalid' : '' }}" 
                                    name="tipo_usuario" 
                                    id="tipo_usuario"
                                    required>
                                <option value="" disabled selected>Selecciona el tipo</option>
                                <option value="principal" {{old('tipo_usuario') == 'principal' ? 'selected' : ''}}>Principal</option>
                                <option value="lecura" {{old('tipo_usuario') == 'lecura' ? 'selected' : ''}}>Solo lectura</option>
                            </select>
                            @if ($errors->first('tipo_usuario'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('tipo_usuario') }}
                                </div>
                            @endif
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="departamentoSelect" class="form-label fw-semibold">
                                Departamento
                                <span class="text-danger">*</span>
                            </label>
                            <select id="departamentoSelect" 
                                    name="departamento" 
                                    class="form-select {{$errors->first('departamento') ? 'is-invalid' : ''}}" 
                                    data-mdb-select-init 
                                    data-mdb-filter="true" 
                                    data-mdb-clear-button="true"
                                    required>
                                <option value="" disabled selected>Selecciona el departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{$departamento->id}}" {{old('departamento') == $departamento->id ? 'selected' : ''}}>
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
                    </div>
                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                            <i class="fa-solid fa-save me-2"></i>
                            Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>









{{-- Modales de Eliminación y Edición --}}
@foreach ($usuarios as $usuario)
    <!-- Modal Eliminar Usuario -->
    <div class="modal fade" id="del_user{{$usuario->id}}" tabindex="-1" aria-labelledby="eliminarUsuarioLabel{{$usuario->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarUsuarioLabel{{$usuario->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar este usuario?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$usuario->name}}</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            {{$usuario->email}}
                        </small>
                        <small class="text-muted d-block">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('eliminar.usuario', $usuario->id)}}" method="POST">
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

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="edit_user{{$usuario->id}}" tabindex="-1" aria-labelledby="editarUsuarioLabel{{$usuario->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarUsuarioLabel{{$usuario->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('editar.usuario', $usuario->id)}}" method="post">
                        @csrf 
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('nombre_usuario') ? 'is-invalid' : '' }}" 
                                           id="nombre_usuario_edit{{$usuario->id}}" 
                                           name="nombre_usuario" 
                                           value="{{old('nombre_usuario', $usuario->name)}}"
                                           required>
                                    <label class="form-label" for="nombre_usuario_edit{{$usuario->id}}">
                                        Nombre
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_usuario'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_usuario') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="email" 
                                           class="form-control {{ $errors->first('correo_usuario') ? 'is-invalid' : '' }}" 
                                           id="correo_usuario_edit{{$usuario->id}}" 
                                           name="correo_usuario" 
                                           value="{{old('correo_usuario', $usuario->email)}}"
                                           required>
                                    <label class="form-label" for="correo_usuario_edit{{$usuario->id}}">
                                        Correo Electrónico
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('correo_usuario'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('correo_usuario') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password" 
                                           class="form-control {{ $errors->first('password_usuario') ? 'is-invalid' : '' }}" 
                                           id="password_usuario_edit{{$usuario->id}}" 
                                           name="password_usuario"
                                           placeholder="Dejar vacío para mantener la actual">
                                    <label class="form-label" for="password_usuario_edit{{$usuario->id}}">
                                        Nueva Contraseña
                                        <small class="text-muted">(Opcional)</small>
                                    </label>
                                    @if ($errors->first('password_usuario'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password_usuario') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('puesto_usuario') ? 'is-invalid' : '' }}" 
                                           id="puesto_usuario_edit{{$usuario->id}}" 
                                           value="{{old('puesto_usuario', $usuario->puesto)}}" 
                                           name="puesto_usuario"
                                           required>
                                    <label class="form-label" for="puesto_usuario_edit{{$usuario->id}}">
                                        Puesto
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('puesto_usuario'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('puesto_usuario') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="departamento_edit{{$usuario->id}}" class="form-label fw-semibold">
                                    Departamento
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="departamento_edit{{$usuario->id}}" 
                                        name="departamento" 
                                        class="form-select {{$errors->first('departamento') ? 'is-invalid' : ''}}" 
                                        data-mdb-select-init 
                                        data-mdb-filter="true" 
                                        data-mdb-clear-button="true"
                                        required>
                                    <option value="" disabled>Selecciona el departamento</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ $departamento->id == $usuario->id_departamento ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->first('departamento'))
                                    <div class="invalid-feedback d-block">
                                        {{ $errors->first('departamento') }}
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



@endsection
    