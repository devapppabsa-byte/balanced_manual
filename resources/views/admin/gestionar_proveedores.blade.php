@extends('plantilla')
@section('title', 'Gestionar Proveedores')

@section('contenido')
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 league-spartan">Proveedores de la Empresa</h3>
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

    <div class="row">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <i class="fa-solid fa-truck text-primary me-2"></i>
                            Proveedores
                        </h2>
                        <p class="text-muted mb-0">
                            <small>Gestión y evaluación de proveedores de la empresa</small>
                        </p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_proveedor">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Proveedor
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

            @if (!$proveedores->isEmpty())
                <!-- Proveedores Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="ps-4" style="min-width: 250px;">
                                            <small class="text-muted fw-semibold text-uppercase">Proveedor</small>
                                        </th>
                                        <th style="min-width: 200px;">
                                            <small class="text-muted fw-semibold text-uppercase">Descripción</small>
                                        </th>
                                        <th class="text-center" style="width: 180px;">
                                            <small class="text-muted fw-semibold text-uppercase">Cumplimiento</small>
                                        </th>
                                        <th class="text-center pe-4" style="width: 120px;">
                                            <small class="text-muted fw-semibold text-uppercase">Acciones</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proveedores as $proveedor)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fa-solid fa-building text-primary" style="font-size: 0.875rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a class="fw-semibold text-dark text-decoration-none" 
                                                           href="{{ route('detalle.evaluacion.proveedor', $proveedor->id) }}"
                                                           data-mdb-tooltip-init 
                                                           title="Ver detalles de {{$proveedor->nombre}}">
                                                            {{$proveedor->nombre}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ Str::limit($proveedor->descripcion, 80) }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                @if (!$proveedor->evaluacion_proveedores->isEmpty())
                                                    @php
                                                        $contador = 0;
                                                        $suma = 0; 
                                                    @endphp
                                                    @foreach ($proveedor->evaluacion_proveedores as $evaluacion)
                                                        @php
                                                            $suma = $suma + $evaluacion->calificacion;
                                                            $contador++;
                                                        @endphp
                                                    @endforeach
                                                    @php
                                                        $promedio = round($suma/$contador, 2);
                                                        $esBajo = $promedio < 80;
                                                    @endphp
                                                    <span class="badge fs-6 border border-2 fw-semibold 
                                                        {{ $esBajo ? 'bg-danger bg-opacity-10 text-danger border-danger' : 'bg-success bg-opacity-10 text-success border-success' }}" 
                                                        data-mdb-tooltip-init 
                                                        title="Promedio: {{ $promedio }}%">
                                                        <i class="fa-solid {{ $esBajo ? 'fa-triangle-exclamation' : 'fa-check-circle' }} me-1"></i>
                                                        {{ $promedio }}%
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border">
                                                        <i class="fa-solid fa-minus me-1"></i>
                                                        Sin evaluaciones
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ route('detalle.evaluacion.proveedor', $proveedor->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-mdb-tooltip-init 
                                                       title="Ver detalles y gráficas">
                                                        <i class="fa-solid fa-chart-pie"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            data-mdb-tooltip-init 
                                                            title="Eliminar {{$proveedor->nombre}}" 
                                                            data-mdb-ripple-init 
                                                            data-mdb-modal-init 
                                                            data-mdb-target="#del_pro{{$proveedor->id}}">
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
                            <i class="fa-solid fa-truck text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay proveedores registrados</h5>
                        <p class="text-muted mb-4">
                            <small>Comienza agregando tu primer proveedor al sistema.</small>
                        </p>
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_proveedor">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Proveedor
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






<!-- Modal Agregar Proveedor -->
<div class="modal fade" id="agregar_proveedor" tabindex="-1" aria-labelledby="agregarProveedorLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarProveedorLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Proveedor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('proveedor.store')}}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" 
                                       class="form-control {{ $errors->first('nombre_proveedor') ? 'is-invalid' : '' }}" 
                                       id="nombre_proveedor" 
                                       name="nombre_proveedor" 
                                       value="{{ old('nombre_proveedor') }}"
                                       required>
                                <label class="form-label" for="nombre_proveedor">
                                    Nombre del Proveedor
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_proveedor'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_proveedor') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control {{ $errors->first('descripcion_proveedor') ? 'is-invalid' : '' }}" 
                                          id="descripcion_proveedor" 
                                          name="descripcion_proveedor" 
                                          rows="4"
                                          required>{{ old('descripcion_proveedor') }}</textarea>
                                <label class="form-label" for="descripcion_proveedor">
                                    Descripción
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('descripcion_proveedor'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('descripcion_proveedor') }}
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
                            Guardar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




{{-- Modales de Eliminación --}}
@foreach ($proveedores as $proveedor)
    <div class="modal fade" id="del_pro{{$proveedor->id}}" tabindex="-1" aria-labelledby="eliminarProveedorLabel{{$proveedor->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarProveedorLabel{{$proveedor->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar este proveedor?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$proveedor->nombre}}</strong>
                        </p>
                        <small class="text-muted d-block mt-2">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('proveedor.delete', $proveedor->id)}}" method="POST">
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




@endsection