@extends('plantilla')
@section('title', 'Indicadores ')



@section('contenido')

<div class="container-fluid sticky-top">
    <div class="row bg-primary  d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 mb-0 league-spartan">{{$departamento->nombre}}</h3>
            <span>Asignación de Indicadores a {{$departamento->nombre}}</span>
            @if (session('success'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif
            @if (session('editado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('editado')}}
                </div>
            @endif

            @if (session('eliminado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado')}}
                </div>
            @endif
        
            @if (session('encuesta_eliminada'))
                <div class="text-danger fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('encuesta_eliminada')}}
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

            <!-- Tabs Navigation - MDBootstrap -->
    <div class="row">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-0" id="departamentoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active py-3" 
                            id="ex1-tab-1" 
                            data-mdb-tab-init
                            href="#ex1-tabs-1" 
                            role="tab" 
                            aria-controls="ex1-tabs-1" 
                            aria-selected="true">
                            <i class="fas fa-users me-2"></i>
                            Usuarios de {{ $departamento->nombre }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" 
                            id="ex1-tab-2" 
                            data-mdb-tab-init
                            href="#ex1-tabs-2" 
                            role="tab" 
                            aria-controls="ex1-tabs-2" 
                            aria-selected="false">
                            <i class="fas fa-chart-line me-2"></i>
                            Indicadores
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" 
                            id="ex1-tab-3" 
                            data-mdb-tab-init
                            href="#ex1-tabs-3" 
                            role="tab" 
                            aria-controls="ex1-tabs-3" 
                            aria-selected="false">
                            <i class="fas fa-file-alt me-2"></i>
                            Seguimiento a Normas y Legislaciónes
                        </a>
                    </li>

                    @if ($departamento->nombre === "Ventas")
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" 
                            id="ex1-tab-4" 
                            data-mdb-tab-init
                            href="#ex1-tabs-4" 
                            role="tab" 
                            aria-controls="ex1-tabs-4" 
                            aria-selected="false">
                            <i class="fas fa-poll me-2"></i>
                            Encuestas
                        </a>
                    </li>                            
                    @endif

                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" 
                            id="ex1-tab-5" 
                            data-mdb-tab-init
                            href="#ex1-tabs-5" 
                            role="tab" 
                            aria-controls="ex1-tabs-3" 
                            aria-selected="false">
                            <i class="fas fa-file-alt me-2"></i>
                            Indicadores de Otras Areas
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    @if ($ponderacion != 100)
        <div class="row"  style="background-color: rgb(199, 53, 53)">
            <div class="col-12 text-white ">
                <span>    
                    <i class="fa fa-warning text-white"></i>
                    La suma de la ponderación de los indicadores es: <b class="badge badge-warning text-danger rounded-pill ">%{{$ponderacion}}</b>
                </span>
            </div>
        </div>
    @endif

</div>



<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10 mt-1">
                        <!-- Tabs Content (oculto inicialmente para JS) -->
            <div class="tab-content " id="ex1-content">
                
                <!-- Usuarios Tab -->
                <div class="tab-pane fade show active" 
                     id="ex1-tabs-1" 
                     role="tabpanel" 
                     aria-labelledby="ex1-tab-1">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pb-0 pt-4 ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    Usuarios de {{ $departamento->nombre }}
                                </h4>
                                <button type="button" 
                                        class="btn btn-primary btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#agregar_usuario">
                                    <i class="fas fa-plus me-1"></i>
                                    Agregar Usuario
                                </button>
                            </div>
                            
                        </div>
                        
                        <div class="card-body p-4">
                            @if($usuarios->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-users-slash text-muted fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No hay usuarios registrados</h5>
                                    <p class="text-muted mb-4">Agrega usuarios para gestionar el departamento</p>
                                    <button type="button" 
                                            class="btn btn-primary"
                                            data-mdb-ripple-init
                                            data-mdb-modal-init
                                            data-mdb-target="#agregar_usuario">
                                        <i class="fas fa-plus me-1"></i>
                                        Agregar Primer Usuario
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="border-0">Nombre</th>
                                                <th scope="col" class="border-0">Correo</th>
                                                <th scope="col" class="border-0">Puesto</th>
                                                <th scope="col" class="border-0">Tipo</th>
                                                <th scope="col" class="border-0 text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($usuarios as $usuario)
                                            <tr class="border-bottom">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $usuario->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $usuario->puesto }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($usuario->tipo_usuario == "principal")
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-user-tie me-1"></i> Emisor
                                                    </span>
                                                    @else
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-user me-1"></i> Lector
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#edit_user{{ $usuario->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#del_user{{ $usuario->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Indicadores Tab -->
                <div class="tab-pane fade" 
                     id="ex1-tabs-2" 
                     role="tabpanel" 
                     aria-labelledby="ex1-tab-2">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pb-0 pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-chart-line text-success me-2"></i>
                                    Indicadores de {{ $departamento->nombre }}
                                </h4>
                                <button type="button" 
                                        class="btn btn-success btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#agregar_indicador">
                                    <i class="fas fa-plus me-1"></i>
                                    Nuevo Indicador
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            @if($indicadores->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-chart-bar text-muted fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No hay indicadores registrados</h5>
                                    <button type="button" 
                                            class="btn btn-success"
                                            data-mdb-ripple-init
                                            data-mdb-modal-init
                                            data-mdb-target="#agregar_indicador">
                                        <i class="fas fa-plus me-1"></i>
                                        Crear Primer Indicador
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="border-0">Indicador</th>
                                                <th scope="col" class="border-0">Ponderación</th>
                                                <th scope="col" class="border-0">Min - Max</th>
                                                <th scope="col" class="border-0">Tipo de Indicador</th>
                                                <th scope="col" class="border-0">Unidad</th>
                                                <th scope="col" class="border-0">Planta</th>
                                                <th scope="col" class="border-0 text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($indicadores as $indicador)
                                            <tr class="border-bottom">
                                                <td>
                                                    <a href="{{ route('indicador.index', $indicador->id) }}" 
                                                       class="text-decoration-none text-dark fw-semibold">
                                                        {{ $indicador->nombre }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{$indicador->ponderacion}} %
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                           {{$indicador->meta_minima}}  --  {{$indicador->meta_esperada}}
                                                    
                                                    </small>
                                                </td>
                                                <td>
                                                    <small class="text-muted text-capitalize">{{ $indicador->tipo_indicador }}</small>
                                                </td>

                                                <td>
                                                    <small class="text-muted text-capitalize">
                                                        {{ $indicador->unidad_medida }}

                                                    </small>
                                                </td>

                                                <td>
                                                    <small class="text-muted text-capitalize">
                                                        {{
                                                        $indicador->planta == '1' ? 'Planta 1' :
                                                        ($indicador->planta == '2' ? 'Planta 2' :
                                                        ($indicador->planta == '3' ? 'Planta 3' :
                                                        ($indicador->planta == 'm' ? 'Mascotas' :
                                                        ($indicador->planta == 'p' ? 'Pecuarios' :
                                                        ($indicador->planta == 'g' ? 'General' : 'Sin definir')))))
                                                        }}
                                                    </small>
                                                </td>

                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('indicador.index', $indicador->id) }}" 
                                                           class="btn btn-sm btn-outline-dark">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#upd_indi{{ $indicador->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#bo{{ $indicador->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                

                <!-- Normas Tab -->
                <div class="tab-pane fade" 
                     id="ex1-tabs-3" 
                     role="tabpanel" 
                     aria-labelledby="ex1-tab-3">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pb-0 pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-file-alt text-warning me-2"></i>
                                    Seguimiento a Normas y Legislaciones.
                                </h4>
                                <button type="button" 
                                        class="btn btn-warning btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#agregar_norma">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Norma
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            @if($normas->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-file-alt text-muted fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No hay normas registradas</h5>
                                    <button type="button" 
                                            class="btn btn-warning"
                                            data-mdb-ripple-init
                                            data-mdb-modal-init
                                            data-mdb-target="#agregar_norma">
                                        <i class="fas fa-plus me-1"></i>
                                        Agregar Primera Norma
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="border-0">Norma</th>
                                                <th scope="col" class="border-0">Tipo Regulación</th>
                                                <th scope="col" class="border-0">Ponderación</th>
                                                <th scope="col" class="border-0">Fecha Creación</th>
                                                <th scope="col" class="border-0 text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($normas as $norma)
                                            <tr class="border-bottom">
                                                <td>
                                                    <a href="{{ route('apartado.norma', $norma->id) }}" 
                                                       class="text-decoration-none text-dark fw-semibold">
                                                        {{ $norma->nombre }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <small class="text-muted text-capitalize">{{ $norma->tipo_regulacion }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-dark">{{ $norma->ponderacion }}%</small>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $norma->created_at->locale('es')->translatedFormat('l j \\d\\e F Y h:i:s A')}}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('apartado.norma', $norma->id) }}" 
                                                           class="btn btn-sm btn-outline-dark">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#edit_norm{{ $norma->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#del_norm{{ $norma->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Encuestas Tab -->
                <div class="tab-pane fade" 
                     id="ex1-tabs-4" 
                     role="tabpanel" 
                     aria-labelledby="ex1-tab-4">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pb-0 pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-poll text-info me-2"></i>
                                    Encuestas de {{ $departamento->nombre }}
                                </h4>
                                <button type="button" 
                                        class="btn btn-info btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#agregar_cuestionario">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Encuesta
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            @if($encuestas->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-poll text-muted fa-4x"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No hay encuestas registradas</h5>
                                    <button type="button" 
                                            class="btn btn-info"
                                            data-mdb-ripple-init
                                            data-mdb-modal-init
                                            data-mdb-target="#agregar_cuestionario">
                                        <i class="fas fa-plus me-1"></i>
                                        Crear Primera Encuesta
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="border-0">Encuesta</th>
                                                <th scope="col" class="border-0">Descripción</th>
                                                <th scope="col" class="border-0">Autor</th>
                                                <th scope="col" class="border-0">Fecha Creación</th>
                                                <th scope="col" class="border-0 text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($encuestas as $encuesta)
                                            <tr class="border-bottom">
                                                <td>
                                                    <a href="{{ route('encuesta.index', $encuesta->id) }}" 
                                                       class="text-decoration-none text-dark fw-semibold">
                                                        {{ $encuesta->nombre }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($encuesta->descripcion, 50) }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $encuesta->autor }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($encuesta->created_at)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('encuesta.index', $encuesta->id) }}" 
                                                           class="btn btn-sm btn-outline-dark">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#edit_en{{ $encuesta->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#del_en{{ $encuesta->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" 
                     id="ex1-tabs-5" 
                     role="tabpanel" 
                     aria-labelledby="ex1-tab-5">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 pb-0 pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-list-check text-info me-2"></i>
                                   Agregar Indicadores Foraneos
                                </h4>
                                <button type="button" 
                                        class="btn btn-dark btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#agregar_indicador_foraneo">
                                    <i class="fas fa-plus me-1"></i>
                                    Agregar Indicadores Foraneos
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="border-0">Nombre</th>
                                                <th scope="col" class="border-0">Departamento</th>
                                                <th scope="col" class="border-0">Planta</th>
                                                <th scope="col" class="border-0 text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($indicadores_foraneos_agregados as $indicador_foraneo_agregado)
                                            <tr class="border-bottom">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-list-check"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $indicador_foraneo_agregado->nombre }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary p-2">
                                                        <i class="fa-regular fa-building"></i>
                                                        {{ $indicador_foraneo_agregado->departamento->nombre }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        
                                                        {{ ($indicador_foraneo_agregado->planta) ? 'Planta '. $indicador_foraneo_agregado->planta : 'No asignado' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-mdb-ripple-init
                                                                data-mdb-modal-init
                                                                data-mdb-target="#del_indfor{{ $indicador_foraneo_agregado->id }}">
                                                            <i class="fas fa-trash"></i>
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
                </div>

            </div>
        </div>
    </div>
</div>











{{-- Modales de los indicadores --}}
<div class="modal fade" id="agregar_indicador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregar Indicador</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('agregar.indicadores.store', $departamento->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h6>Datos Generales</h6>
                </div>
                <div class="col-12 text-center">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg {{ $errors->first('nombre_usuario') ? 'is-invalid' : '' }} " id="nombre_indicador" name="nombre_indicador" required>
                            <label class="form-label" for="nombre_usuario" >Nombre del Indicador  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('descripcion') ? 'is-invalid' : '' }}" id="descrpcion" name="descripcion" required ></textarea>
                                <label class="form-label" for="descrpcion">Descripción del indicador  <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-4 border-left">
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-12">
                            <h6>Metas  <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="number" step="any"  class="form-control" name="meta_minima" placeholder="Minimo" required>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <input type="number" step="any" class="form-control" name="meta_esperada" placeholder="Máximo"  required>
                            </div>
                        </div>

                    </div>
                </div>



                <div class="col-12 mt-3 ">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" step="any" min="1" max="100" class="form-control form-control-lg w-100 {{ $errors->first('ponderacion_indicador') ? 'is-invalid' : '' }}" id="ponderacion_indicador1" name="ponderacion_indicador" required ></textarea>
                            <label class="form-label" for="ponderacion_indicador">Ponderación Indicador dentro de la evaluación total  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">

                    <div class="form-group mt-3">
                        <select name="tipo_indicador" class="form-control form-select" id="tipo_indicador">
                            <option value="normal">Indicador Normal</option>
                            <option value="riesgo">Indicador de Riesgo</option>
                        </select>
                    </div>

                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
                    <div class="form-group mt-3">

                        <select name="unidad_medida" class="form-select w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}" id="unidad_medida{{$departamento->id}}" required>
                        
                            <option value="" disabled  selected>Selecciona la Unidad de Medida</option>
                            <option value="dias">Días</option>
                            <option value="toneladas">Toneladas</option>
                            <option value="porcentaje">Porcentaje</option>
                            <option value="pesos">Pesos</option>
                            <option value="unidad">Unidad</option>
                        
                        </select>
                    </div>
                    
                </div>

                <div class="col-12">
                    <div class="row  mt-4 justify-content-around">

                        <div class="col-12 col-sm-8 col-md-8 col-lg-8">
                            <div class="form-group">
                                <select class="form-select" name="planta" id="">
                                    <option value="" disabled selected>Planta a la que pertenece</option>
                                    <option value="1">Planta 1</option>
                                    <option value="2">Planta 2</option>
                                    <option value="3">Planta 3</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 ">
                            <div class="form-check form-switch mt-2">
                            <input class="form-check-input" name="indicador_variacion" type="checkbox" role="switch" id="flexSwitchCheckDefault" />
                            <label class="form-check-label" for="flexSwitchCheckDefault">Variación.</label>
                            </div>                                
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





{{-- //indicadores_foraneos_agregados --}}
@forelse ($indicadores_foraneos_agregados as $indicador_foraneo_agregado)
    <div class="modal fade" id="del_indfor{{ $indicador_foraneo_agregado->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">¿Eliminar Indicador de solo Lectura ?</h3>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{ route('eliminar.indicador.foraneo', [$departamento->id , $indicador_foraneo_agregado->id]) }}" method="POST">
                    @csrf @method('DELETE')
                    
                    <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                        <h6>Eliminar</h6>
                    </button>
                </form>
            </div>
            {{-- <div class="modal-footer">
            </div> --}}
            </div>
        </div>
    </div>

    
@empty
@endforelse











@foreach ($indicadores as $indicador)

<div class="modal fade" id="upd_indi{{$indicador->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary ">
        <h3 class="text-white" id="exampleModalLabel">Actualizando Inidicador - {{ $indicador->nombre }}</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('indicador.edit', $indicador->id)}}" method="post">
            @csrf @method('PATCH')
            <div class="row">

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 border-left">
                    <div class="row d-flex align-items-center justify-content-center">
                        
                        <div class="col-12 mb-4">
                            <div class="form-group">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" id="nombre_indicador_edit" value="{{$indicador->nombre}}" class="form-control" name="nombre_indicador_edit" placeholder="Nombre" required>
                                    <label class="form-label" for="nombre_indicador_edit">Nombre  <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">

                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" id="meta_minima_edit" value="{{$indicador->meta_minima}}" class="form-control" name="meta_minima" placeholder="Meta Minima" required>
                                    <label class="form-label" for="meta_minima_edit">Meta Minima  <span class="text-danger">*</span></label>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="col-4">
                            <div class="form-group">

                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" id="meta_maxima_edit"  class="form-control" value="{{$indicador->meta_esperada}}" name="meta_esperada" placeholder=" Meta Maxima"  required>
                                    <label class="form-label" for="meta_maxima_edit">Meta esperada  <span class="text-danger">*</span></label>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" step="0.0001"  min="1" max="100" value="{{$indicador->ponderacion}}" class="form-control {{ $errors->first('ponderacion_indicador') ? 'is-invalid' : '' }}" id="ponderacion_indicador_edit" name="ponderacion_indicador_edit" required />
                                    <label class="form-label" for="ponderacion_indicador_edit">Ponderación % Indicador dentro de la evaluación total  <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row  mt-4 justify-content-around">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-2">
                                    <label for="tipo_ind{{$indicador->id}}" class="form-label fw-semibold">
                                        Tipo de Indicador
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipo_indicador"  class="form-select w-100 {{ $errors->first('tipo_indicador') ? 'is-invalid' : '' }}"  id="tipo_ind{{$indicador->id}}" required>
                                        <option value="" disabled>Selecciona el tipo de Indicador</option>
                                        <option value="normal" {{old('tipo_indicador', $indicador->tipo_indicador) == 'normal' ? 'selected' : ''}}>Normal</option>
                                        <option value="riesgo" {{old('tipo_indicador', $indicador->tipo_indicador) == 'riesgo' ? 'selected' : ''}}>Riesgo</option>
                                    </select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-2">
                                    <label for="tipo_ind{{$indicador->id}}" class="form-label fw-semibold">
                                        Planta:
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="planta_indicador"  class="form-select w-100 {{ $errors->first('planta_indicador') ? 'is-invalid' : '' }}"  id="" required>
                                        <option value="" {{old('planta_indicador', $indicador->planta) == null ? 'selected' : ''}} disabled>Selecciona una Planta</option>
                                        <option value="1" {{old('planta_indicador', $indicador->planta) == '1' ? 'selected' : ''}}>Planta 1</option>
                                        <option value="2" {{old('planta_indicador', $indicador->planta) == '2' ? 'selected' : ''}}>Planta 2</option>
                                        <option value="3" {{old('planta_indicador', $indicador->planta) == '3' ? 'selected' : ''}}>Planta 3</option>
                                        <option value="m" {{old('planta_indicador', $indicador->planta) == 'm' ? 'selected' : ''}}>Mascotas</option>
                                        <option value="p" {{old('planta_indicador', $indicador->planta) == 'p' ? 'selected' : ''}}>Pecuarios</option>
                                        <option value="g" {{old('planta_indicador', $indicador->planta) == 'g' ? 'selected' : ''}}>Indicador General</option>
                                    </select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-2">
                                <label for="unidad_medida{{$indicador->id}}" class="form-label fw-semibold">
                                    Unidad de Medida
                                    <span class="text-danger">*</span>
                                </label>
    
                                <select name="unidad_medida" 
                                        class="form-select w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                        id="unidad_medida{{$indicador->id}}" 
                                        required>
    
                                    <option value="" disabled {{ old('unidad_medida', $indicador->unidad_medida) ? '' : 'selected' }}>
                                        Selecciona la Unidad de Medida
                                    </option>
    
                                    <option value="dias" 
                                        {{ old('unidad_medida', $indicador->unidad_medida) == 'dias' ? 'selected' : '' }}>
                                        Días
                                    </option>
    
                                    <option value="toneladas" 
                                        {{ old('unidad_medida', $indicador->unidad_medida) == 'toneladas' ? 'selected' : '' }}>
                                        Toneladas
                                    </option>
    
                                    <option value="porcentaje" 
                                        {{ old('unidad_medida', $indicador->unidad_medida) == 'porcentaje' ? 'selected' : '' }}>
                                        % Porcentaje
                                    </option>
    
                                    <option value="pesos" 
                                        {{ old('unidad_medida', $indicador->unidad_medida) == 'pesos' ? 'selected' : '' }}>
                                        $ Pesos
                                    </option>
                                    <option value="unidad" 
                                        {{ old('unidad_medida', $indicador->unidad_medida) == 'unidad' ? 'selected' : '' }}>
                                        Unidad
                                    </option>
    
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 d-flex align-items-center">
                                <div class="form-check form-switch mt-4 pt-4">
                                <input class="form-check-input" name="indicador_variacion_edit" type="checkbox" role="switch" id="flexSwitchCheckDefault" {{ $indicador->variacion === "on" ? 'checked' : '' }} />
                                <label class="form-check-label" for="flexSwitchCheckDefault">Variación</label>
                                </div>                                
                            </div>
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
@endforeach





<div class="modal fade" id="agregar_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregar Usuarios</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('agregar.usuario')}}"  method="post" onkeydown="return event.key !='Enter';">
            @csrf

            <div class="row">

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" value="{{old('nombre_usuario')}}" class="form-control form-control-lg {{ $errors->first('nombre_usuario') ? 'is-invalid' : '' }} " id="nombre_usuario" name="nombre_usuario" required>
                            <label class="form-label" for="nombre_usuario" >Nombre <span class="text-danger">*</span> </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" value="{{old('correo_usuario')}}" class="form-control form-control-lg {{ $errors->first('correo_usuario') ? 'is-invalid' : '' }} "  name="correo_usuario" required>
                            <label class="form-label" for="correo_usuario" >Correo <span class="text-danger">*</span> </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="password" value="{{old('password_usuario')}}" class="form-control form-control-lg {{ $errors->first('password_usuario') ? 'is-invalid' : '' }}" name="password_usuario" required>
                            <label class="form-label" for="password" >Contraseña  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="puesto" value="{{old('puesto_usuario')}}" class="form-control form-control-lg {{ $errors->first('puesto_usuario') ? 'is-invalid' : '' }}" name="puesto_usuario" required>
                            <label class="form-label" for="puesto" >Puesto  <span class="text-danger">*</span> </label>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <select name="planta" class="form-control form-control-lg" id="" required>
                            <option value="" disabled selected>Selecciona la planta a la que pertenece</option>
                            <option value="Planta 1"{{old('planta') == 'Planta 1' ? 'selected': ''}}>Planta 1</option>
                            <option value="Planta 2"{{old('planta') == 'Planta 2' ? 'selected': ''}} >Planta 2</option>
                            <option value="Planta 3"{{old('planta') == 'Planta 3' ? 'selected' : ''}} >Planta 3</option>
                        </select>
                    </div>
                </div> --}}

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <select class="form-control form-control-lg" name="tipo_usuario" id="" required>
                            <option value="" disabled selected>Tipo de usuario <span class="text-danger">*</span></option>
                            <option value="principal">Principal</option>
                            <option value="lecura">Solo lectura</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 ">
                    <div class="form-group mt-3">
                        <input type="hidden" value="{{$departamento->id}}" class="form-control" name="departamento">
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


<div class="modal fade" id="agregar_cuestionario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregar Encuesta</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('encuesta.store', $departamento->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="col-12 text-center">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg {{ $errors->first('nombre_encuesta') ? 'is-invalid' : '' }} " id="nombre_encuesta" value="{{old('nombre_encuesta')}}" name="nombre_encuesta" required>
                            <label class="form-label" for="nombre_encuesta" >Nombre para la Encuesta  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('descripcion_cuestionario') ? 'is-invalid' : '' }}" id="descrpcion_encuesta1" name="descripcion_encuesta" required >{{old('descripcion_encuesta')}}</textarea>
                                <label class="form-label" for="descrpcion_encuesta">Descripción de la Encuesta  <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" min="0" max="100" class="form-control w-100 {{ $errors->first('meta_minima_encuesta') ? 'is-invalid' : '' }}" id="meta_minima_encuesta" name="meta_minima_encuesta" value="{{old('meta_minima_encuesta')}}"  required >
                                <label class="form-label" for="meta_minima_encuesta">Meta Minima  <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" min="0" max="100" class="form-control w-100 {{ $errors->first('meta_esperada_encuesta') ? 'is-invalid' : '' }}" id="meta_esperada_encuesta" name="meta_esperada_encuesta" value="{{old('meta_esperada_encuesta')}}" required >
                                <label class="form-label" for="meta_esperada_encuesta">Meta Esperada  <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-12 mt-4 ">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" min="1" max="100" class="form-control form-control-lg w-100 {{ $errors->first('ponderacion_encuesta') ? 'is-invalid' : '' }}" id="ponderacion_indicador" value="{{old('ponderacion_encuesta')}}" name="ponderacion_encuesta" required >
                            <label class="form-label" for="ponderacion_encuesta">Ponderación de la encuesta dentro de la evaluación total  <span class="text-danger">*</span></label>
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



<div class="modal fade" id="agregar_norma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregar Norma</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('norma.store', $departamento->id)}}" method="post">
            @csrf
            <div class="row">

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" min="0" max="100" class="form-control w-100 {{ $errors->first('titulo_norma') ? 'is-invalid' : '' }} " value="{{old('titulo_norma')}}"  name="titulo_norma" required>
                            <label class="form-label" for="titulo_norma" >Titulo Norma  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('descripcion_norma') ? 'is-invalid' : '' }}" id="descripcion_norma" name="descripcion_norma" required >{{old('descripcion_norma')}}.</textarea>
                                <label class="form-label" for="descripcion_norma">Descripción de la Norma <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="col-6 mt-4 ">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" value="90" min="1" max="100" class="form-control form-control-lg w-100 {{ $errors->first('meta_minima_norma') ? 'is-invalid' : '' }}" id="meta_minima_norma" name="meta_minima_norma" required >
                            <label class="form-label" for="meta_minima_norma">Meta Minima <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>


                <div class="col-6 mt-4 ">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" min="1" max="100" value="100" class="form-control form-control-lg w-100 {{ $errors->first('meta_esperada_norma') ? 'is-invalid' : '' }}" id="meta_esperada_norma" name="meta_esperada_norma" required >
                            <label class="form-label" for="meta_esperada_norma">Meta Esperada <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>



                <div class="col-12 mt-4 ">
                    <div class="form-group mt-2">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number" min="1" value="10" max="100" class="form-control form-control-lg w-100 {{ $errors->first('ponderacion_norma') ? 'is-invalid' : '' }}" id="ponderacion_norma" name="ponderacion_norma" required ></textarea>
                            <label class="form-label" for="ponderacion_norma">Ponderación de la encuesta dentro de la evaluación total  <span class="text-danger">*</span></label>
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



<div class="modal fade" id="agregar_indicador_foraneo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-fullscreen modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregando Indicador Foraneo</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <div class="row mb-4">
          <div class="col-12">
            <div class="form-group">
              <input type="search" id="buscadorIndicadores" class="form-control form-control-lg" placeholder="Buscar por nombre del indicador o departamento...">
            </div>
          </div>
        </div>
        <form action="{{route('indicador.foraneo.store', $departamento->id)}}" id="indicador_foraneo_form" method="post">
            @csrf
            <div class="row justify-content-around" id="contenedor_indicadores">

                @forelse ($indicadores_foraneos as $indicador_foraneo)

                    <div class="col-3 m-1  tex-center p-4 item-indicador" data-nombre="{{ strtolower($indicador_foraneo->nombre) }}" data-departamento="{{ strtolower($indicador_foraneo->departamento->nombre) }}">

                        <div class="row">
                            <div class="col-12 indicador-item">
                                <input type="checkbox" 
                                    name="indicador_foraneo[]" 
                                    value="{{ $indicador_foraneo->id }}" 
                                    class="btn-check" 
                                    id="{{$indicador_foraneo->id}}" 
                                    autocomplete="off">

                                <label class="btn btn-outline-primary custom-check text-start row w-100"
                                    for="{{$indicador_foraneo->id}}">

                                    <div class="col-12 text-center mb-3 fw-bold indicador-nombre">
                                        {{ $indicador_foraneo->nombre }}
                                    </div>

                                    <div class="col-12 indicador-departamento">
                                        <i class="fa-solid fa-building"></i>
                                        Departamento:
                                        {{ $indicador_foraneo->departamento->nombre }}
                                    </div>

                                    <div class="col-12 indicador-planta">
                                        <i class="fa-solid fa-industry"></i>
                                        Planta:
                                        {{ $indicador_foraneo->departamento->planta }}
                                    </div>

                                </label>
                            </div>
                        </div>
                        
                    </div>
                @empty
                    
                @endforelse

            </div>
        </form>
       </div>
        <div class="modal-footer">
            <button form="indicador_foraneo_form" class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                <h6>Guardar</h6>
            </button>
      </div>
    </div>
  </div>
</div>













{{-- bucle que me da s modales para la gestios de usuarios --}}
{{-- bucle de los modales del usuario --}}
{{--Puros bucles de modales--}}

@forelse ($encuestas as $encuesta)
    <div class="modal fade" id="del_en{{$encuesta->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">¿Eliminar la encuesta {{$encuesta->nombre}} ?</h3>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('encuesta.delete', $encuesta->id)}}" method="POST">
                    @csrf @method('DELETE')
                    <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                        <h6>Eliminar</h6>
                    </button>
                </form>
            </div>
            {{-- <div class="modal-footer">
            </div> --}}
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_en{{$encuesta->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary py-4">
            <h3 class="text-white" id="exampleModalLabel">Editando Encuesta</h3>
            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-4">
            <form action="{{route('encuesta.edit', $encuesta->id)}}" method="post">
                @csrf @method("PATCH")
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="form-group mt-2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" class="form-control form-control-lg {{ $errors->first('nombre_encuesta_edit') ? 'is-invalid' : '' }} " id="nombre_encuesta1" value="{{$encuesta->nombre}}" name="nombre_encuesta_edit" required>
                                <label class="form-label" for="nombre_encuesta_edit" >Nombre para la Encuesta <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <div class="form-outline" data-mdb-input-init>
                                    <textarea class="form-control w-100 {{ $errors->first('descripcion_encuesta_edit') ? 'is-invalid' : '' }}" id="descrpcion_encuesta" name="descripcion_encuesta_edit" required >{{$encuesta->descripcion}}</textarea>
                                    <label class="form-label" for="descrpcion_encuesta_edit">Descripción del la Encuesta  <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" value="{{$encuesta->meta_minima}}" min="0" max="100" class="form-control w-100 {{ $errors->first('meta_minima_encuesta_edit') ? 'is-invalid' : '' }}" id="meta_minima_encuesta_edit" name="meta_minima_encuesta_edit" required >
                                    <label class="form-label" for="meta_minima_encuesta_edit">Meta Minima <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" value="{{$encuesta->meta_esperada}}" min="0" max="100" class="form-control w-100 {{ $errors->first('meta_esperada_encuesta_edit') ? 'is-invalid' : '' }}" id="meta_esperada_encuesta_edit" name="meta_esperada_encuesta_edit" required >
                                    <label class="form-label" for="meta_esperada_encuesta_edit">Meta Esperada <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" class="form-control w-100 {{ $errors->first('ponderacion_encuesta_edit') ? 'is-invalid' : '' }}" id="ponderacion_encuesta" value="{{$encuesta->ponderacion}}" name="ponderacion_encuesta_edit" required >
                                    <label class="form-label" for="ponderacion_encuesta_edit">Ponderacion del la Encuesta <span class="text-danger">*</span></label>
                                </div>
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

@empty
    
@endforelse


@forelse ($usuarios as $usuario)

<div class="modal fade" id="del_user{{$usuario->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-danger py-4">
            <h3 class="text-white" id="exampleModalLabel">¿Eliminar a {{$usuario->name}} ?</h3>
            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-4">
            <form action="{{route('eliminar.usuario', $usuario->id)}}" method="POST">
                @csrf @method('DELETE')
                <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                    <h6>Eliminar</h6>
                </button>
            </form>
        </div>
        {{-- <div class="modal-footer">
        </div> --}}
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="edit_user{{$usuario->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Editando a {{$usuario->name}}</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('editar.usuario', $usuario->id)}}"  method="post">
            @csrf @method('PATCH')

            <div class="row">

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg {{ $errors->first('nombre_usuario') ? 'is-invalid' : '' }} " id="nombre_{{$usuario->id}}" name="nombre_usuario" value="{{old('nombre_usuario', $usuario->name)}}">
                            <label class="form-label" for="nombre_usuario" >Nombre  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg {{ $errors->first('correo_usuario') ? 'is-invalid' : '' }} " id="correo_{{$usuario->id}}" name="correo_usuario" value="{{old('correo_usuario', $usuario->email)}}">
                            <label class="form-label" for="correo_usuario" >Correo <span class="text-danger">*</span> </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="password" class="form-control form-control-lg {{ $errors->first('password_usuario') ? 'is-invalid' : '' }}" id="password_{{$usuario->id}}" name="password_usuario" >
                            <label class="form-label" for="password" >Contraseña <span class="text-danger">*</span> </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="puesto" class="form-control form-control-lg {{ $errors->first('puesto_usuario') ? 'is-invalid' : '' }} " id="puesto_{{$usuario->id}}" value="{{old('puesto_usuario', $usuario->puesto)}}" name="puesto_usuario">
                            <label class="form-label" for="puesto" >Puesto  <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="col-12 ">
                    <div class="form-group mt-3">
                        <select id="departamento_{{$usuario->id}}" name="departamento" class="form-control form-control-lg {{$errors->first('departamento') ? 'is-invalid' : ''}}" data-mdb-select-init data-mdb-filter="true" 
                        data-mdb-clear-button="true">


                            <option  disabled selected>Selecciona el departamento al que pertenece  <span class="text-danger">*</span></option>

                            @forelse ($departamentos as $departamento)

                                <option value="{{ $departamento->id }}"
                                    {{ $departamento->id == $usuario->id_departamento ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>

                            @empty
                                
                            @endforelse
                        </select>
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
@empty
@endforelse


@forelse ($indicadores as $indicador)
    <div class="modal fade" id="bo{{$indicador->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h2>¿Eliminar Indicador?</h2>
                </div>
                <div class="modal-body">
                    <form action="{{route('borrar.indicador', $indicador->id)}}" method="POST">
                        @csrf @method('DELETE')
                        <h2>
                            <button class="btn btn-danger w-100 py-3">
                                Eliminar
                            </button>
                        </h2>
                    </form>
                </div>
            </div>
        </div>

    </div>
@empty
@endforelse


@forelse ($normas as $norma)

    <div class="modal fade" id="del_norm{{$norma->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger py-4">
                    <h3 class="text-white" id="exampleModalLabel">¿Eliminar {{$norma->nombre}} ?</h3>
                    <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('norma.delete', $norma->id)}}" method="POST">
                        @csrf @method('DELETE')
                        <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                            <h6>Eliminar</h6>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_norm{{$norma->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h5 class="text-white" id="exampleModalLabel">Editando {{$norma->nombre}}</h5>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('norma.update', $norma->id)}}" method="POST">
                    @csrf @method('PATCH')
                    
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg w-100{{ $errors->first('nombre_norma_edit') ? 'is-invalid' : '' }} " id="nombre_norm{{$norma->id}}" name="nombre_norma_edit" value="{{old('nombre_norma', $norma->nombre)}}" >
                            <label class="form-label" for="nombre_norm{{$norma->id}}" >Nombre  <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <select  class="form-select" name="tipo_regulacion_edit" id="">
                            <option value="legislacion">Legislación</option>
                            <option value="norma">Norma</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <textarea type="text" rows="5" class="form-control form-control-lg w-100 h-100 lh-sm{{ $errors->first('descripcion_norma_edit') ? 'is-invalid' : '' }} " id="descrip_norm_edit{{$norma->id}}" name="descripcion_norma_edit" value="{{old('descripcion_norma_edit', $norma->descripcion)}}">{{$norma->descripcion}}</textarea>
                            <label class="form-label" for="descrip_norm_edit{{$norma->id}}" >Descripción  <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="number"  class="form-control form-control-lg {{ $errors->first('ponderacion_norma_edit') ? 'is-invalid' : '' }} " id="ponderacion_norm_edit{{$norma->id}}" name="ponderacion_norma_edit" value="{{old('ponderacion_norma_edit', $norma->ponderacion)}}">
                            <label class="form-label" for="ponderacion_norm_edit{{$norma->id}}" >Ponderación <span class="text-danger">*</span> </label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number"  class="form-control form-control-lg {{ $errors->first('meta_minima_norma_edit') ? 'is-invalid' : '' }} " id="meta_minima_norm_edit{{$norma->id}}" name="meta_minima_norma_edit" value="{{old('meta_minima_norma_edit', $norma->meta_minima)}}">
                                    <label class="form-label" for="meta_minima_norm_edit{{$norma->id}}" >Meta Minima<span class="text-danger">*</span> </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number"  class="form-control form-control-lg {{ $errors->first('meta_esperada_norma_edit') ? 'is-invalid' : '' }} " id="meta_esperada_norm_edit{{$norma->id}}" name="meta_esperada_norma_edit" value="{{old('meta_esperada_norma_edit', $norma->meta_esperada)}}">
                                    <label class="form-label" for="meta_esperada_norm_edit{{$norma->id}}" >Meta Esperada<span class="text-danger">*</span> </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group mt-4">
                        <button  class="btn btn-primary w-100 btn-lg" data-mdb-ripple-init>
                            <i class="fa fa-pencil mx-2"></i>
                            Editar
                        </button>
                    </div>


                </form>
            </div>
            {{-- <div class="modal-footer">
            </div> --}}
            </div>
        </div>
    </div>
@empty
    
@endforelse











@endsection




@section('scripts')
<script>
document.getElementById('buscadorIndicadores')
    .addEventListener('keyup', function () {

    let filtro = this.value.toLowerCase();
    // Seleccionar los contenedores .col-3 en lugar de .indicador-item
    let contenedores = document.querySelectorAll('.col-3.m-1');

    contenedores.forEach(function (contenedor) {

        let nombre = contenedor.querySelector('.indicador-nombre')
                         .textContent.toLowerCase();

        let departamento = contenedor.querySelector('.indicador-departamento')
                               .textContent.toLowerCase();

        let planta = contenedor.querySelector('.indicador-planta')
                         .textContent.toLowerCase();

        if (
            nombre.includes(filtro) ||
            departamento.includes(filtro) ||
            planta.includes(filtro)
        ) {
            contenedor.style.display = "";
        } else {
            contenedor.style.display = "none";
        }

    });
});
</script>




@endsection


