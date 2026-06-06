@extends('plantilla')
@section('title', 'Logs del sistema')
@section('contenido')
<style>
    .avatar-sm {
        width: 28px;
        height: 28px;
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
    
    code {
        font-size: 0.8rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .avatar-sm {
            width: 24px;
            height: 24px;
        }
    }
</style>
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 league-spartan">Historial de movimientos de la aplicación</h3>
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
    <div class="row justify-content-center">
            <!-- Header Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <h2 class="mb-1 fw-bold">
                                <i class="fa-solid fa-book text-primary me-2"></i>
                                Logs del Sistema
                            </h2>
                            <p class="text-muted mb-0">
                                <small>Registro de actividades y movimientos del sistema</small>
                            </p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge bg-light text-dark border">
                                <i class="fa-solid fa-list me-1"></i>
                                {{ $logs->count() }} registros en esta página
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if (!$logs->isEmpty())
                <!-- Logs Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="ps-4" style="width: 80px;">
                                            <small class="text-muted fw-semibold text-uppercase">ID</small>
                                        </th>
                                        <th>
                                            <small class="text-muted fw-semibold text-uppercase">Autor</small>
                                        </th>
                                        <th style="min-width: 300px;">
                                            <small class="text-muted fw-semibold text-uppercase">Descripción</small>
                                        </th>
                                        <th>
                                            <small class="text-muted fw-semibold text-uppercase">IP</small>
                                        </th>
                                        <th class="text-center" style="width: 120px;">
                                            <small class="text-muted fw-semibold text-uppercase">Acción</small>
                                        </th>
                                        <th class="text-end pe-4" style="width: 180px;">
                                            <small class="text-muted fw-semibold text-uppercase">Fecha y Hora</small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">
                                                    #{{ $log->id }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fa-solid fa-user text-primary" style="font-size: 0.75rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <small class="text-dark fw-medium d-block">
                                                            {{ $log->autor }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-wrap" style="max-width: 400px;">
                                                    <small class="text-dark">
                                                        {{ $log->descripcion }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <code class="text-muted small bg-light px-2 py-1 rounded">
                                                    {{ $log->ip }}
                                                </code>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $accionConfig = [
                                                        'add' => ['icon' => 'fa-plus-circle', 'color' => 'success', 'text' => 'Crear'],
                                                        'update' => ['icon' => 'fa-edit', 'color' => 'warning', 'text' => 'Editar'],
                                                        'deleted' => ['icon' => 'fa-trash', 'color' => 'danger', 'text' => 'Eliminar'],
                                                        'start_session' => ['icon' => 'fa-right-to-bracket', 'color' => 'info', 'text' => 'Sesión'],
                                                        'excel' => ['icon' => 'fa-file-excel', 'color' => 'success', 'text' => 'Excel'],
                                                    ];
                                                    $config = $accionConfig[$log->accion] ?? ['icon' => 'fa-circle', 'color' => 'secondary', 'text' => ucfirst($log->accion)];
                                                @endphp
                                                <span class="badge bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }} border border-{{ $config['color'] }} border-opacity-25">
                                                    <i class="fa-solid {{ $config['icon'] }} me-1"></i>
                                                    {{ $config['text'] }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div>
                                                    <small class="text-dark fw-medium d-block">
                                                        {{ $log->created_at->locale('es')->translatedFormat('d M Y') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $log->created_at->format('H:i:s') }}
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay logs disponibles</h5>
                        <p class="text-muted mb-0">
                            <small>No se han registrado actividades en el sistema aún.</small>
                        </p>
                    </div>
                </div>
            @endif
      
    </div>

</div>

@endsection