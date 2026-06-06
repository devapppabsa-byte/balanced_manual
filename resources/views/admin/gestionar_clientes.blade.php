@extends('plantilla')
@section('title', 'Gestionar clientes de la empresa')

@section('contenido')
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 league-spartan">Clientes de la empresa</h3>
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
            <!-- Header Card -->
    <div class="row">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <i class="fa-solid fa-users text-primary me-2"></i>
                            Lista de Clientes
                        </h2>
                        <p class="text-muted mb-0">
                            <small>Gestión de clientes de la empresa</small>
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_cliente">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Cliente
                        </button>
                    </div>
                </div>
                
                <!-- Campo de búsqueda -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" 
                                    class="form-control" 
                                    id="busquedaCliente" 
                                    placeholder="Buscar por nombre, email, teléfono, línea o ID...">
                            <label class="form-label" for="busquedaCliente">
                                <i class="fa-solid fa-search me-2"></i>
                                Buscar Cliente
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-end">
                        <small class="text-muted">
                            <span id="resultadosCount">0</span> resultados encontrados
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            @if (!$clientes->isEmpty())
                <!-- Clientes Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="ps-4" style="min-width: 200px;">
                                            <small class="text-muted fw-semibold text-uppercase">Cliente</small>
                                        </th>
                                        <th style="min-width: 150px;">
                                            <small class="text-muted fw-semibold text-uppercase">Línea</small>
                                        </th>
                                        <th style="min-width: 200px;">
                                            <small class="text-muted fw-semibold text-uppercase">Correo</small>
                                        </th>
                                        <th style="min-width: 150px;">
                                            <small class="text-muted fw-semibold text-uppercase">Teléfono</small>
                                        </th>
                                        <th class="text-center pe-4" style="width: 120px;">
                                            <small class="text-muted fw-semibold text-uppercase">Acciones</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tablaClientes">
                                    @foreach ($clientes as $cliente)
                                        <tr class="border-bottom cliente-row" 
                                            data-nombre="{{$cliente->nombre}}" 
                                            data-email="{{$cliente->email}}" 
                                            data-telefono="{{$cliente->telefono}}" 
                                            data-linea="{{$cliente->linea}}" 
                                            data-id="{{$cliente->id_interno}}">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fa-solid fa-user text-primary" style="font-size: 0.875rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary mb-1">
                                                            #{{$cliente->id_interno}}
                                                        </span>
                                                        <div class="fw-semibold text-dark">
                                                            {{$cliente->nombre}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                    {{$cliente->linea}}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="mailto:{{$cliente->email}}" 
                                                   class="text-decoration-none text-dark d-flex align-items-center" 
                                                   data-mdb-tooltip-init 
                                                   title="Enviar correo a {{$cliente->email}}">
                                                    <i class="fa-solid fa-envelope me-2 text-primary"></i>
                                                    <small>{{$cliente->email}}</small>
                                                </a>
                                            </td>
                                            <td>
                                                @if(!empty($cliente->telefono))
                                                    <a href="tel:+52{{$cliente->telefono}}" 
                                                    class="text-decoration-none text-dark d-flex align-items-center" 
                                                    data-mdb-tooltip-init 
                                                    title="Llamar a {{$cliente->telefono}}">
                                                        <i class="fa-solid fa-phone me-2 text-success"></i>
                                                        <small>{{$cliente->telefono}}</small>
                                                    </a>
                                                @else
                                                    <span class="text-muted">No hay teléfono</span>
                                                @endif
                                            </td>

                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            data-mdb-tooltip-init 
                                                            title="Editar {{$cliente->nombre}}" 
                                                            data-mdb-ripple-init 
                                                            data-mdb-modal-init 
                                                            data-mdb-target="#edit_client{{$cliente->id}}">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            data-mdb-tooltip-init 
                                                            title="Eliminar {{$cliente->nombre}}" 
                                                            data-mdb-ripple-init 
                                                            data-mdb-modal-init 
                                                            data-mdb-target="#del_client{{$cliente->id}}">
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
                <div class="card border-0 shadow-sm" id="emptyState">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-users text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay clientes registrados</h5>
                        <p class="text-muted mb-4">
                            <small>Comienza agregando tu primer cliente al sistema.</small>
                        </p>
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_cliente">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Cliente
                        </button>
                    </div>
                </div>
                
                <!-- No Results State -->
                <div class="card border-0 shadow-sm d-none" id="noResultsState">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-search text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No se encontraron resultados</h5>
                        <p class="text-muted mb-4">
                            <small>No hay clientes que coincidan con tu búsqueda.</small>
                        </p>
                        <button class="btn btn-outline-secondary" onclick="limpiarBusqueda()">
                            <i class="fa-solid fa-times me-2"></i>
                            Limpiar Búsqueda
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
    
    .cliente-row.hidden {
        display: none;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const busquedaInput = document.getElementById('busquedaCliente');
    const resultadosCount = document.getElementById('resultadosCount');
    const clientesTable = document.getElementById('tablaClientes');
    const emptyState = document.getElementById('emptyState');
    const noResultsState = document.getElementById('noResultsState');
    const clientesRows = document.querySelectorAll('.cliente-row');
    
    // Inicializar contador
    actualizarContador();
    
    // Evento de búsqueda
    busquedaInput.addEventListener('input', function() {
        const terminoBusqueda = this.value.toLowerCase().trim();
        let resultadosEncontrados = 0;
        
        clientesRows.forEach(function(row) {
            const nombre = row.dataset.nombre.toLowerCase();
            const email = row.dataset.email.toLowerCase();
            const telefono = row.dataset.telefono.toLowerCase();
            const linea = row.dataset.linea.toLowerCase();
            const id = row.dataset.id.toLowerCase();
            
            if (nombre.includes(terminoBusqueda) || 
                email.includes(terminoBusqueda) || 
                telefono.includes(terminoBusqueda) || 
                linea.includes(terminoBusqueda) || 
                id.includes(terminoBusqueda)) {
                
                row.classList.remove('hidden');
                resultadosEncontrados++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        actualizarContador(resultadosEncontrados);
        gestionarEstadosVacios(resultadosEncontrados);
    });
    
    function actualizarContador(count = null) {
        if (count === null) {
            count = document.querySelectorAll('.cliente-row:not(.hidden)').length;
        }
        resultadosCount.textContent = count;
    }
    
    function gestionarEstadosVacios(resultados) {
        const clientesTableCard = clientesTable.closest('.card');
        
        if (resultados === 0 && busquedaInput.value.trim() !== '') {
            // Mostrar estado de "no resultados" cuando hay búsqueda pero no hay resultados
            if (clientesTableCard) {
                clientesTableCard.style.display = 'none';
            }
            if (emptyState) {
                emptyState.style.display = 'none';
            }
            if (noResultsState) {
                noResultsState.classList.remove('d-none');
            }
        } else if (resultados === 0 && busquedaInput.value.trim() === '') {
            // Mostrar estado vacío original cuando no hay clientes y no hay búsqueda
            if (clientesTableCard) {
                clientesTableCard.style.display = 'none';
            }
            if (emptyState) {
                emptyState.style.display = 'block';
            }
            if (noResultsState) {
                noResultsState.classList.add('d-none');
            }
        } else {
            // Mostrar tabla cuando hay resultados
            if (clientesTableCard) {
                clientesTableCard.style.display = 'block';
            }
            if (emptyState) {
                emptyState.style.display = 'none';
            }
            if (noResultsState) {
                noResultsState.classList.add('d-none');
            }
        }
    }
    
    // Función global para limpiar búsqueda
    window.limpiarBusqueda = function() {
        busquedaInput.value = '';
        busquedaInput.dispatchEvent(new Event('input'));
    };
});
</script>




{{-- Modales de Eliminación y Edición --}}
@foreach ($clientes as $cliente)
    <!-- Modal Eliminar Cliente -->
    <div class="modal fade" id="del_client{{$cliente->id}}" tabindex="-1" aria-labelledby="eliminarClienteLabel{{$cliente->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarClienteLabel{{$cliente->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar este cliente?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$cliente->nombre}}</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            ID Interno: #{{$cliente->id_interno}}
                        </small>
                        <small class="text-muted d-block">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('eliminar.cliente', $cliente->id)}}" method="POST">
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

    <!-- Modal Editar Cliente -->
    <div class="modal fade" id="edit_client{{$cliente->id}}" tabindex="-1" aria-labelledby="editarClienteLabel{{$cliente->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarClienteLabel{{$cliente->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('editar.cliente', $cliente->id)}}" method="post" onkeydown="return event.key !='Enter';">
                        @csrf 
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('id_cliente_edit') ? 'is-invalid' : '' }}" 
                                           value="{{old('id_cliente_edit', $cliente->id_interno)}}" 
                                           name="id_cliente_edit"
                                           id="id_cliente_edit{{$cliente->id}}"
                                           required>
                                    <label class="form-label" for="id_cliente_edit{{$cliente->id}}">
                                        ID Interno
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('id_cliente_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('id_cliente_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" 
                                           class="form-control {{ $errors->first('nombre_cliente_edit') ? 'is-invalid' : '' }}" 
                                           value="{{old('nombre_cliente_edit', $cliente->nombre)}}" 
                                           name="nombre_cliente_edit"
                                           id="nombre_cliente_edit{{$cliente->id}}"
                                           required>
                                    <label class="form-label" for="nombre_cliente_edit{{$cliente->id}}">
                                        Nombre del Cliente
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_cliente_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_cliente_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="email" 
                                           class="form-control {{ $errors->first('correo_cliente_edit') ? 'is-invalid' : '' }}" 
                                           name="correo_cliente_edit" 
                                           value="{{old('correo_cliente_edit', $cliente->email)}}"
                                           id="correo_cliente_edit{{$cliente->id}}"
                                           required>
                                    <label class="form-label" for="correo_cliente_edit{{$cliente->id}}">
                                        Correo Electrónico
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('correo_cliente_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('correo_cliente_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="tel" 
                                           class="form-control {{ $errors->first('telefono_cliente_edit') ? 'is-invalid' : '' }}" 
                                           name="telefono_cliente_edit" 
                                           value="{{old('telefono_cliente_edit', $cliente->telefono)}}"
                                           id="telefono_cliente_edit{{$cliente->id}}"
                                           required>
                                    <label class="form-label" for="telefono_cliente_edit{{$cliente->id}}">
                                        Teléfono
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('telefono_cliente_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('telefono_cliente_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password" 
                                           class="form-control {{ $errors->first('password_cliente_edit') ? 'is-invalid' : '' }}" 
                                           name="password_cliente_edit" 
                                           value="{{old('password_cliente_edit')}}"
                                           id="password_cliente_edit{{$cliente->id}}"
                                           placeholder="Dejar vacío para mantener la actual">
                                    <label class="form-label" for="password_cliente_edit{{$cliente->id}}">
                                        Nueva Contraseña
                                        <small class="text-muted">(Opcional)</small>
                                    </label>
                                    @if ($errors->first('password_cliente_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password_cliente_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="linea_edit{{$cliente->id}}" class="form-label fw-semibold">
                                    Línea
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="linea_edit" 
                                        class="form-select {{ $errors->first('linea_edit') ? 'is-invalid' : '' }}" 
                                        id="linea_edit{{$cliente->id}}"
                                        required>
                                    <option value="" disabled>Selecciona una línea</option>
                                    <option value="Mascotas" {{old('linea_edit', $cliente->linea) == 'Mascotas' ? 'selected' : ''}}>Mascotas</option>
                                    <option value="Pecuarios" {{old('linea_edit', $cliente->linea) == 'Pecuarios' ? 'selected' : ''}}>Pecuarios</option>
                                    <option value="Pecuarios y Mascotas" {{old('linea_edit', $cliente->linea) == 'Pecuarios y Mascotas' ? 'selected' : ''}}>Pecuarios y Mascotas</option>
                                </select>
                                @if ($errors->first('linea_edit'))
                                    <div class="invalid-feedback d-block">
                                        {{ $errors->first('linea_edit') }}
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









<!-- Modal Agregar Cliente -->
<div class="modal fade" id="agregar_cliente" tabindex="-1" aria-labelledby="agregarClienteLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarClienteLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('agregar.cliente')}}" method="post" onkeydown="return event.key !='Enter';">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('id_cliente') ? 'is-invalid' : '' }}" 
                                       value="{{old('id_cliente')}}" 
                                       id="id_cliente" 
                                       name="id_cliente"
                                       required>
                                <label class="form-label" for="id_cliente">
                                    ID Interno
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('id_cliente'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('id_cliente') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('nombre_cliente') ? 'is-invalid' : '' }}" 
                                       id="nombre_cliente" 
                                       value="{{old('nombre_cliente')}}" 
                                       name="nombre_cliente"
                                       required>
                                <label class="form-label" for="nombre_cliente">
                                    Nombre del Cliente
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_cliente'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_cliente') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="email" 
                                       class="form-control {{ $errors->first('correo_cliente') ? 'is-invalid' : '' }}" 
                                       name="correo_cliente" 
                                       value="{{old('correo_cliente')}}"
                                       required>
                                <label class="form-label" for="correo_cliente">
                                    Correo Electrónico
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('correo_cliente'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('correo_cliente') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="tel" 
                                       class="form-control {{ $errors->first('telefono_cliente') ? 'is-invalid' : '' }}" 
                                       name="telefono_cliente" 
                                       value="{{old('telefono_cliente')}}"
                                       >
                                <label class="form-label" for="telefono_cliente">
                                    Teléfono
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('telefono_cliente'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('telefono_cliente') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="password" 
                                       class="form-control {{ $errors->first('password_cliente') ? 'is-invalid' : '' }}" 
                                       name="password_cliente" 
                                       value="{{old('password_cliente')}}"
                                       required>
                                <label class="form-label" for="password_cliente">
                                    Contraseña
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('password_cliente'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password_cliente') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="linea" class="form-label fw-semibold">
                                Línea
                                <span class="text-danger">*</span>
                            </label>
                            <select name="linea" 
                                    class="form-select {{ $errors->first('linea') ? 'is-invalid' : '' }}" 
                                    id="linea"
                                    required>
                                <option value="" disabled selected>Selecciona una línea</option>
                                <option value="Mascotas" {{old('linea') == 'Mascotas' ? 'selected' : ''}}>Mascotas</option>
                                <option value="Pecuarios" {{old('linea') == 'Pecuarios' ? 'selected' : ''}}>Pecuarios</option>
                                <option value="Pecuarios y Mascotas" {{old('linea') == 'Pecuarios y Mascotas' ? 'selected' : ''}}>Pecuarios y Mascotas</option>
                            </select>
                            @if ($errors->first('linea'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('linea') }}
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
                            Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>








@endsection