@extends('plantilla')
@section('title', 'Inidcador')


@section('contenido')

<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">

            <h3 class="mt-1 league-spartan mb-0">
                {{$indicador->nombre}}
            </h3>
            <span>
                Departamento de {{$indicador->departamento->nombre}}
            </span>
            @if (session('success'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif
            @if (session('error_input'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('error_input')}}
                </div>
            @endif
            @if (session('editado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('editado')}}
                </div>
            @endif

            @if (session('deleted'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('deleted')}}
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
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap py-3 px-4">

                <h4 class="mb-0 fw-bold">
                    <i class="fa-solid fa-clipboard-check text-primary me-2"></i>
                    Campos del Indicador
                </h4>

                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <span class="badge bg-info rounded-pill px-3 py-2">
                        {{ $campo_referencia->count() }} Referencias
                    </span>
                </div>

            </div>
        </div>
    </div>


</div>


<div class="container-fluid">

<!-- FAB CONTENEDOR -->
<div class="fab-container">

  <!-- BOTÓN PRINCIPAL -->
  @if (count($campo_final))

    <button class="fab-main zoom"   onclick="toastr.error('Ya se cerro este indicador', '¡Atención!')">
        <i class="fa fa-plus"></i>
    </button>
      
  @else
     <button class="fab-main zoom" id="fabToggle" >
        <i class="fa fa-plus"></i>
    </button>
  @endif






  <!-- BOTONES HIJOS -->
  <div class="fab-actions">

    <button class="fab-btn bg-secondary"
      data-mdb-modal-init data-mdb-target="#modalCampos">
      <i class="fa fa-plus-circle"></i>
      <span>Campos Vacíos</span>
    </button>

    <button class="fab-btn bg-dark"
      data-mdb-modal-init data-mdb-target="#modalCamposPrecargados">
      <i class="fa fa-database"></i>
      <span>Precargados</span>
    </button>

    <button class="fab-btn bg-primary"
      data-mdb-modal-init data-mdb-target="#modalPromediarCampos">
      <i class="fa fa-scale-balanced"></i>
      <span>Promedio</span>
    </button>

    <button class="fab-btn bg-info"
      data-mdb-modal-init data-mdb-target="#modalDividirCampos">
      <i class="fa fa-divide"></i>
      <span>División</span>
    </button>

    <button class="fab-btn bg-success"
      data-mdb-modal-init data-mdb-target="#modalSumarCampos">
      <i class="fa fa-plus"></i>
      <span>Suma</span>
    </button>

    <button class="fab-btn bg-warning"
      data-mdb-modal-init data-mdb-target="#modalRestarCampos">
      <i class="fa fa-minus"></i>
      <span>Resta</span>
    </button>

    <button class="fab-btn bg-danger"
      data-mdb-modal-init data-mdb-target="#modalMultiplicarCampos">
      <i class="fa fa-xmark"></i>
      <span>Multiplicación</span>
    </button>

    <button class="fab-btn bg-purple"
      data-mdb-modal-init data-mdb-target="#modalPorcentajeCampos">
      <i class="fa fa-percent"></i>
      <span>Porcentaje</span>
    </button>

  </div>
</div>


</div>



<div class="container-fluid mt-4">


    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">

                    @if(!$campos_vacios->isEmpty())

                        <h5 class="fw-bold mb-3">Campos Vacíos</h5>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">

                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th>#</th>
                                        <th>Campo</th>
                                        <th>Descripción</th>
                                        <th>Unidad Medida</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($campos_vacios as $campo)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $campo->id }}</span></td>

                                        <td class="fw-semibold">{{ $campo->nombre }}</td>

                                        <td class="text-muted">
                                            @if($campo->descripcion)
                                                <span data-mdb-tooltip-init title="{{ $campo->descripcion }}">
                                                    {{ Str::limit(strip_tags($campo->descripcion), 80) }}
                                                </span>
                                            @else
                                                <i class="fa fa-info-circle text-primary"></i> Sin descripción
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $campo->unidad_medida ?? 'Sin unidad de medida' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-danger btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#delvac{{ $campo->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <button class="btn btn-outline-warning btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#edivac{{ $campo->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('/img/iconos/empty.png') }}" style="max-width:180px;" class="img-fluid mb-3">

                            <h5 class="fw-bold">
                                <i class="fa fa-circle-exclamation text-danger me-1"></i>
                                No hay campos vacíos disponibles
                            </h5>

                            <p class="text-muted mb-0">
                                Aún no se han agregado campos a este indicador.
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">

                    @if(!$campos_precargados->isEmpty())

                        <h5 class="fw-bold mb-3">Campos Precargados</h5>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">

                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th>#</th>
                                        <th>Campo</th>
                                        <th>Descripción</th>
                                        <th>Unidad Medida</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($campos_precargados as $campo)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $campo->id }}</span></td>

                                        <td class="fw-semibold">{{ $campo->nombre }}</td>

                                        <td class="text-muted">
                                            @if($campo->descripcion)
                                                <span data-mdb-tooltip-init title="{{ $campo->descripcion }}">
                                                    {{ Str::limit(strip_tags($campo->descripcion), 80) }}
                                                </span>
                                            @else
                                                <i class="fa fa-info-circle text-primary"></i> Sin descripción
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $campo->unidad_medida ?? 'Sin unidad de medida' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-danger btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#delpre{{ $campo->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <button class="btn btn-outline-warning btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#edipre{{ $campo->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('/img/iconos/empty.png') }}" style="max-width:180px;" class="img-fluid mb-3">

                            <h5 class="fw-bold">
                                <i class="fa fa-circle-exclamation text-danger me-1"></i>
                                No hay campos precargados disponibles
                            </h5>

                            <p class="text-muted mb-0">
                                Aún no se han agregado campos a este indicador.
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">

                    @if(!$campos_calculados->isEmpty())

                        <h5 class="fw-bold mb-3">Campos Calculados</h5>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover">

                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th>#</th>
                                        <th>Campo</th>
                                        <th>Descripción</th>
                                        <th>Unidad Medida</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($campos_calculados as $campo)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $campo->id }}</span></td>

                                        <td class="fw-semibold">{{ $campo->nombre }}</td>

                                        <td class="text-muted">
                                            @if($campo->descripcion)
                                                <span data-mdb-tooltip-init title="{{ $campo->descripcion }}">
                                                    {{ Str::limit(strip_tags($campo->descripcion), 80) }}
                                                </span>
                                            @else
                                                <i class="fa fa-info-circle text-primary"></i> Sin descripción
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $campo->unidad_medida ?? 'Sin unidad de medida' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-outline-danger btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#delcal{{ $campo->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <button class="btn btn-outline-warning btn-sm"
                                                        data-mdb-modal-init
                                                        data-mdb-target="#edical{{ $campo->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('/img/iconos/empty.png') }}" style="max-width:180px;" class="img-fluid mb-3">

                            <h5 class="fw-bold">
                                <i class="fa fa-circle-exclamation text-danger me-1"></i>
                                No hay campos calculados disponibles
                            </h5>

                            <p class="text-muted mb-0">
                                Aún no se han agregado campos a este indicador.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>


</div>








{{-- AQUI VAN LOS CICLOS PARA OS MODALES DE LOS CAMP
OS --}}

@foreach ($campos_calculados as $campo_calculado)
    <div class="modal fade" id="delcal{{$campo_calculado->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">¿Eliminar a {{$campo_calculado->nombre}} ?</h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('eliminar.campo', [$campo_calculado->id, 'calculado'])}}" method="POST">
                    @csrf @method('DELETE')
                    
                    <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                        <h6>Eliminar</h6>
                    </button>
                </form>

            </div>
            <div class="modal-footer">
            </div> 
            </div>
        </div>
    </div> 
    

    <div class="modal fade" id="edical{{$campo_calculado->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h4 class="text-white" id="exampleModalLabel">Editando unidad de medida de: <u> {{$campo_calculado->nombre}}</u> </h4>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('editar.campo', [$campo_calculado->id, 'calculado'])}}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                id="unidad_medida{{$campo_calculado->id}}" 
                                required>
    
                            <option value="" disabled {{ old('unidad_medida', $campo_calculado->unidad_medida) ? '' : 'selected' }}>
                                Selecciona la Unidad de Medida
                            </option>
    
                            <option value="dias" 
                                {{ old('unidad_medida', $campo_calculado->unidad_medida) == 'dias' ? 'selected' : '' }}>
                                Días
                            </option>
    
                            <option value="toneladas" 
                                {{ old('unidad_medida', $campo_calculado->unidad_medida) == 'toneladas' ? 'selected' : '' }}>
                                Toneladas
                            </option>
    
                            <option value="porcentaje" 
                                {{ old('unidad_medida', $campo_calculado->unidad_medida) == 'porcentaje' ? 'selected' : '' }}>
                                % Porcentaje
                            </option>
    
                            <option value="pesos" 
                                {{ old('unidad_medida', $campo_calculado->unidad_medida) == 'pesos' ? 'selected' : '' }}>
                                $ Pesos
                            </option>
                            <option value="unidad" 
                                {{ old('unidad_medida', $campo_calculado->unidad_medida) == 'unidad' ? 'selected' : '' }}>
                                Unidad
                            </option>
    
                        </select>
                    </div>
                    
                    <div class="form-group mt-3">
                        <button  class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                            Editar
                        </button>
                    </div>
                </form>

            </div>

            </div>
        </div>
    </div>

    
@endforeach







@foreach ($campos_precargados as $campo_precargado)
    <div class="modal fade" id="delpre{{$campo_precargado->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">¿Eliminar a {{$campo_precargado->nombre}} ?</h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('eliminar.campo', [$campo_precargado->id, 'precargado'])}}" method="POST">
                    @csrf @method('DELETE')
                    
                    <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                        <h6>Eliminar</h6>
                    </button>
                </form>

            </div>
            <div class="modal-footer">
            </div> 
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="edipre{{$campo_precargado->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h4 class="text-white" id="exampleModalLabel">Editando unidad de medida de: <u> {{$campo_precargado->nombre}}</u> </h4>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('editar.campo', [$campo_precargado->id, 'precargado'])}}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                id="unidad_medida{{$campo_precargado->id}}" 
                                required>
    
                            <option value="" disabled {{ old('unidad_medida', $campo_precargado->unidad_medida) ? '' : 'selected' }}>
                                Selecciona la Unidad de Medida
                            </option>
    
                            <option value="dias" 
                                {{ old('unidad_medida', $campo_precargado->unidad_medida) == 'dias' ? 'selected' : '' }}>
                                Días
                            </option>
    
                            <option value="toneladas" 
                                {{ old('unidad_medida', $campo_precargado->unidad_medida) == 'toneladas' ? 'selected' : '' }}>
                                Toneladas
                            </option>
    
                            <option value="porcentaje" 
                                {{ old('unidad_medida', $campo_precargado->unidad_medida) == 'porcentaje' ? 'selected' : '' }}>
                                % Porcentaje
                            </option>
    
                            <option value="pesos" 
                                {{ old('unidad_medida', $campo_precargado->unidad_medida) == 'pesos' ? 'selected' : '' }}>
                                $ Pesos
                            </option>
                            <option value="unidad" 
                                {{ old('unidad_medida', $campo_precargado->unidad_medida) == 'unidad' ? 'selected' : '' }}>
                                Unidad
                            </option>
    
                        </select>
                    </div>
                    
                    <div class="form-group mt-3">
                        <button  class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                            Editar
                        </button>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
            </div> 
            </div>
        </div>
    </div>




@endforeach



@foreach ($campos_vacios as $campo_vacio)
    <div class="modal fade" id="delvac{{$campo_vacio->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">¿Eliminar a {{$campo_vacio->nombre}} ?</h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('eliminar.campo', [$campo_vacio->id, 'vacio'])}}" method="POST">
                    @csrf @method('DELETE')
                    
                    <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                        <h6>Eliminar</h6>
                    </button>
                </form>

            </div>
            <div class="modal-footer">
            </div> 
            </div>
        </div>
    </div> 
    
    

    <div class="modal fade" id="edivac{{$campo_vacio->id}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h4 class="text-white" id="exampleModalLabel">Editando unidad de medida de: <u> {{$campo_vacio->nombre}}</u> </h4>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form action="{{route('editar.campo', [$campo_vacio->id, 'vacio'])}}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                id="""" 
                                required>
    
                            <option value="" disabled {{ old('unidad_medida', $campo_vacio->unidad_medida) ? '' : 'selected' }}>
                                Selecciona la Unidad de Medida
                            </option>
    
                            <option value="dias" 
                                {{ old('unidad_medida', $campo_vacio->unidad_medida) == 'dias' ? 'selected' : '' }}>
                                Días
                            </option>
    
                            <option value="toneladas" 
                                {{ old('unidad_medida', $campo_vacio->unidad_medida) == 'toneladas' ? 'selected' : '' }}>
                                Toneladas
                            </option>
    
                            <option value="porcentaje" 
                                {{ old('unidad_medida', $campo_vacio->unidad_medida) == 'porcentaje' ? 'selected' : '' }}>
                                % Porcentaje
                            </option>
    
                            <option value="pesos" 
                                {{ old('unidad_medida', $campo_vacio->unidad_medida) == 'pesos' ? 'selected' : '' }}>
                                $ Pesos
                            </option>
                            <option value="unidad" 
                                {{ old('unidad_medida', $campo_vacio->unidad_medida) == 'unidad' ? 'selected' : '' }}>
                                Unidad
                            </option>
    
                        </select>
                    </div>
                    
                    <div class="form-group mt-3">
                        <button  class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                            Editar
                        </button>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
            </div> 
            </div>
        </div>
    </div>




@endforeach




















{{-- modales de los campos calculados --}}
<div class="modal fade" id="modalPromediarCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h3 class="text-white" id="exampleModalLabel">
                <i class="fa-solid fa-gauge-simple"></i>
                   Selecciona los datos que se van a promediar.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body p-2">
                <div class="row pb-5 px-2 bg-light border m-3 no-drop"  id="calculados_container_promedio" ondrop="dropPromedio(event)" ondragover="allowDropPromedio(event)">
                    <div class="col-12 no-drop m-2">
                        <h6 class"no-drop">Campos Disponibles</h6>
                    </div>

                    @forelse ($campos_unidos as $campo_unido)

                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 p-3 border no-drop bg-white" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartPromedio(event)">
                            <label class="no-drop">{{ $campo_unido->nombre }}</label>
                            <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_promedio[]" value="{{$campo_unido->id_input}}">
                        </div>

                    @empty
                        
                    @endforelse
                </div>

                <hr>

                <div class="form-group mt-5 mx-4 no-drop">
                    <h4>Datos Nuevo Campo</h4>

        
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_promedio" class="form-control form-control-lg no-drop w-100 {{ $errors->first('nombre') ? 'is-invalid' : '' }}" form="promedio_container" name="nombre" required>
                        <label class="form-label" for="nombre_campo_promedio" >Nombre nuevo campo </label>
                    </div>
                    <div class="form-group my-3">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"   
                                form="promedio_container"
                                required>
    
                            <option value="" disabled >
                                Selecciona la Unidad de Medida
                            </option>

                            <option value="" disabled selected>Selecciona un tipo de dato</option>
    
                            <option value="dias">
                                Días
                            </option>
    
                            <option value="toneladas" >
                            
                                Toneladas
                            </option>
    
                            <option value="porcentaje">
                                % Porcentaje
                            </option>
    
                            <option value="pesos">
                                $ Pesos
                            </option>
                            <option value="unidad">
                                Unidad
                            </option>
    
                        </select>
                    </div>

                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_promedio" name="descripcion" form="promedio_container" required></textarea>
                        <label class="form-label" for="descripcion_promedio">Descripción del campo</label>
                    </div>
                    
                </div>


                <form action="{{route("input.promedio.guardar", $indicador->id)}}"  autocomplete="off" method="POST" class="row  m-3  destino  bg-light  pb-5 p-3" ondrop="dropPromedio(event)" ondragover="allowDropPromedio(event)" id="promedio_container">
                    @csrf
                    <h6 class="no-drop" id="letrero_promedio" style="z-index: 1"> Arrastra los campos a promediar </h6>
                </form>



                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        <div class="form-check form-switch mt-3">
                            <input   class="form-check-input" form="promedio_container" type="checkbox" name="referencia" id="referencia_promedio">
                            <label class="form-check-label fw-bold text-primary ms-2" for="referencia_promedio">
                                Referencia
                            </label>
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input   class="form-check-input" form="promedio_container" type="checkbox" name="resultado_final" id="resultado_final_promedio">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_promedio">
                                Para Graficar
                            </label>
                        </div>

                        <button  class="btn btn-primary" form="promedio_container" > {{-- id="crear_campo_promedio" --}}
                            Crear Campo Promedio
                        </button>
                        {{-- <a  href="#" class="btn btn-secondary" id="vista_previa_button">
                            Vista Previa
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalMultiplicarCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger py-4">
                <h3 class="text-white" id="exampleModalLabel">
                    <i class="fa fa-xmark-circle"></i>
                   Selecciona los datos que se van a multiplicar.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body p-2">

                <div class="row pb-5 px-2 bg-light border m-3 no-drop"  ondrop="dropMultiplicacion(event)" ondragover="allowDropMultiplicacion(event)">
                    <div class="col-12 no-drop my-2">
                        <h4 class="no-drop">Campos Disponibles</h4>
                    </div>

                    @forelse ($campos_unidos as $campo_unido)
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 p-3 border no-drop bg-white m-1" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartMultiplicacion(event)">
                            <label class="no-drop">{{ $campo_unido->nombre }}</label>
                            <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_multiplicado[]" value="{{$campo_unido->id_input}}">
                        </div>
                    @empty
                        
                    @endforelse
                </div>

                <hr>

                <div class="form-group mt-5 mx-4 no-drop">

                    <h4>Datos Nuevo Campo</h4>
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_multiplicacion" class="form-control form-control-lg no-drop {{ $errors->first('nombre_campo_multiplicacion') ? 'is-invalid' : '' }}" form="multiplicacion_container" name="nombre_campo_multiplicacion">
                        <label class="form-label" for="nombre_campo_multiplicacion" >Nombre nuevo campo </label>
                    </div>

                    <div class="form-group my-3">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                id="""" 
                                form="multiplicacion_container"
                                required>
    
                            <option value="" disabled >
                                Selecciona la Unidad de Medida
                            </option>

                            <option value="" disabled selected>Selecciona un tipo de dato</option>
    
                            <option value="dias">
                                Días
                            </option>
    
                            <option value="toneladas" >
                            
                                Toneladas
                            </option>
    
                            <option value="porcentaje">
                                % Porcentaje
                            </option>
    
                            <option value="pesos">
                                $ Pesos
                            </option>
                            <option value="unidad">
                                Unidad
                            </option>
    
                        </select>
                    </div>


                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_multiplicacion" name="descripcion" form="multiplicacion_container" id=""></textarea>
                        <label class="form-label" for="descripcion_multiplicacion">Descripción del campo</label>
                    </div>


                </div>


                <form action="{{route('input.multiplicacion.guardar', $indicador->id)}}" id="multiplicacion_container" autocomplete="off" method="POST" class="row justify-content-center m-3  destino  bg-light  pb-5" ondrop="dropMultiplicacion(event)" ondragover="allowDropMultiplicacion(event)">
                    @csrf
                    <h6 class="no-drop" id="letrero_multiplicacion" style="z-index: 1"> Arrastra los campos a multiplicar </h6>
                    <br class="no-drop">
                </form>

                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_multiplicacion">
                            <input   class="form-check-input" form="multiplicacion_container" type="checkbox" name="resultado_final" id="resultado_final_multiplicacion">
                                Campo Final
                            </label>
                        </div>
                
                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_referencia">
                            <input   class="form-check-input" form="multiplicacion_container" type="checkbox" name="referencia" id="resultado_final_referencia">
                                Referencia
                            </label>
                        </div>


                        <button  class="btn btn-danger"  form="multiplicacion_container"> {{-- id="crear_campo_promedio" --}}
                            <i class="fa fa-save"></i>
                            Crear Campo de Multiplicación
                        </button>
                        {{-- <a  href="#" class="btn btn-secondary" id="vista_previa_button">
                            Vista Previa
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalSumarCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success py-4">
                <h3 class="text-white" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                   Selecciona los datos que se van a sumar.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body p-2">

                <div class="row pb-5 px-2 bg-light border m-3"  id="calculados_container_suma" ondrop="dropSuma(event)" ondragover="allowDropSuma(event)">
                    <div class="col-12 no-drop m-2">
                        <h6 class="no-drop">Campos Disponibles</h6>
                    </div>

                    @forelse ($campos_unidos as $campo_unido)
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 p-3 border no-drop bg-white" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartSuma(event)">
                            <label class="no-drop">{{ $campo_unido->nombre }}</label>
                            <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_suma[]" value="{{$campo_unido->id_input}}">
                        </div>
                    @empty
                        
                    @endforelse
                </div>


                <div class="form-group mt-5 mx-4 no-drop">
                    <h4>Datos Nuevo Campo</h4>

        
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_suma" class="form-control form-control-lg no-drop w-100 {{ $errors->first('nombre_campo_suma') ? 'is-invalid' : '' }}" form="suma_container" name="nombre_campo_suma">
                        <label class="form-label" for="nombre_campo_suma" >Nombre nuevo campo </label>
                    </div>
                    
                    <div class="form-group my-3">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"   
                                form="suma_container"
                                required>
    
                            <option value="" disabled >
                                Selecciona la Unidad de Medida
                            </option>

                            <option value="" disabled selected>Selecciona un tipo de dato</option>
    
                            <option value="dias">
                                Días
                            </option>
    
                            <option value="toneladas" >
                            
                                Toneladas
                            </option>
    
                            <option value="porcentaje">
                                % Porcentaje
                            </option>
    
                            <option value="pesos">
                                $ Pesos
                            </option>
                            <option value="unidad">
                                Unidad
                            </option>
    
                        </select>
                    </div>

                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_suma" name="descripcion" form="suma_container"></textarea>
                        <label class="form-label" for="descripcion_suma">Descripción del campo</label>
                    </div>
                    
                </div>

                <form action="{{route('input.suma.guardar', $indicador->id)}}" autocomplete="off" id="suma_container" method="POST" class="row  m-3  destino  bg-light  pb-5 border-dashed" ondrop="dropSuma(event)" ondragover="allowDropSuma(event)" id="suma_container">
                    @csrf
                    <h6 class="no-drop" id="letrero_suma" style="z-index: 1"> Arrastra los campos a sumar. </h6>
                </form>


                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        {{-- <div class="form-check mt-2">
                            <input class="form-check-input form-check-input-lg" form="suma_container" type="checkbox" name="resultado_final" id="resultado_final" />
                            <label class="form-check-label text-danger fw-bold" for="resultado_final">Campo Final</label>
                        </div> --}}

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_suma">
                            <input   class="form-check-input" form="suma_container" type="checkbox" name="resultado_final" id="resultado_final_suma">
                                Campo Final
                            </label>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="referencia_suma">
                            <input   class="form-check-input" form="suma_container" type="checkbox" name="referencia" id="referencia_suma">
                                Referencia
                            </label>
                        </div>

                        <button  class="btn btn-success" form="suma_container" > {{-- id="crear_campo_promedio" --}}
                            Crear Campo de Suma
                        </button>
                        {{-- <a  href="#" class="btn btn-secondary" id="vista_previa_button">
                            Vista Previa
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalDividirCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info py-4">
                <h3 class="text-white" id="exampleModalLabel">
                   Selecciona los datos que se van a Dividir.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body p-2">

                <div class="row pb-5 px-2 bg-light border m-3 justify-content-center"  id="calculados_container">
                    
                <div class="col-12 no-drop m-2">
                    <h6 class="no-drop">Campos Disponibles</h6>
                </div>



                @forelse ($campos_unidos as $campo_unido)
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 py-4 px-3 m-1 border no-drop bg-white" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartDivision(event)">
                        <label class="no-drop">{{ $campo_unido->nombre }}</label>
                        <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_division[]" value="{{$campo_unido->id_input}}">
                    </div>
                @empty
                    
                @endforelse


                </div>

                <div class="form-group mt-5 mx-4 no-drop">
                    <h4>Datos Nuevo Campo</h4>

        
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_division" class="form-control form-control-lg no-drop w-100 {{ $errors->first('nombre_campo_division') ? 'is-invalid' : '' }}" form="division_container" name="nombre_campo_division">
                        <label class="form-label" for="nombre_campo_division" >Nombre nuevo campo </label>
                    </div>
                    <div class="form-group my-3">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"   
                                form="division_container"
                                required>
    
                            <option value="" disabled >
                                Selecciona la Unidad de Medida
                            </option>

                            <option value="" disabled selected>Selecciona un tipo de dato</option>
    
                            <option value="dias">
                                Días
                            </option>
    
                            <option value="toneladas" >
                            
                                Toneladas
                            </option>
    
                            <option value="porcentaje">
                                % Porcentaje
                            </option>
    
                            <option value="pesos">
                                $ Pesos
                            </option>
                            <option value="unidad">
                                Unidad
                            </option>
    
                        </select>
                    </div>

                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_division" name="descripcion" form="division_container"></textarea>
                        <label class="form-label" for="descripcion_division">Descripción del campo</label>
                    </div>
                    
                </div>

                <form action="{{route('input.division.guardar', $indicador->id)}}" method="POST" class="row m-3 justify-content-center " id="division_container">
                    @csrf

                    <h6 class="my-0 no-drop">Divisor ( El número de personas o grupos entre los que repartes (ej. tú y 3 amigos, o sea 4). )</h6>
                    
                    <div class="col-12 bg-light border pb-5 mb-3 text-center" ondrop="dropDivision(event)" ondragover="allowDropDivision(event)" id="divisor_container">
                    </div>
                    
                    <h6 class="my-0 no-drop">Dividendo (La cantidad total que tienes para repartir (ej. 12 donas))</h6>
                    <div class="col-12 bg-light border pb-5 mb-3 text-center" ondrop="dropDivision(event)" ondragover="allowDropDivision(event)" id="dividendo_container">
                    </div>
                    
                </form>


                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        {{-- <div class="form-check mt-2">
                            <input class="form-check-input form-check-input-lg" form="division_container" type="checkbox" name="resultado_final" id="resultado_final" />
                            <label class="form-check-label text-danger fw-bold" for="resultado_final">Campo Final</label>
                        </div> --}}


                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_division">
                            <input   class="form-check-input" form="division_container" type="checkbox" name="resultado_final" id="resultado_final_division">
                                Campo Final
                            </label>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="referencia_division">
                            <input   class="form-check-input" form="division_container" type="checkbox" name="referencia" id="referencia_division">
                               Referencia
                            </label>
                        </div>


                        <button  class="btn btn-info" form="division_container">
                            Crear Campo Promedio
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalRestarCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning py-4">
                <h3 class="text-white" id="exampleModalLabel">
                    <i class="fa fa-minus-circle"></i>
                   Selecciona los datos que se van a restar.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body p-2">

                <div class="row pb-5 px-2 bg-light border m-3" >
                    <div class="col-12 no-drop m-2">
                        <h6 class="no-drop">Campos Disponibles</h6>
                    </div>

                    @forelse ($campos_unidos as $campo_unido)
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 p-3 border no-drop bg-white my-1" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartResta(event)">
                            <label class="no-drop">{{ $campo_unido->nombre }}</label>
                            <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_resta[]" value="{{$campo_unido->id_input}}">
                        </div>
                    @empty
                        
                    @endforelse
                   
                </div>

                <div class="form-group mt-5 mx-4 no-drop">
                    <h4>Datos Nuevo Campo</h4>

        
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_resta" class="form-control form-control-lg no-drop w-100 {{ $errors->first('nombre_campo_resta') ? 'is-invalid' : '' }}" form="resta_container" name="nombre_campo_resta">
                        <label class="form-label" for="nombre_campo_resta" >Nombre nuevo campo </label>
                    </div>

                    <div class="form-group my-3">
                        <select name="unidad_medida" 
                                class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                id="""" 
                                form="resta_container"
                                required>
    
                            <option value="" disabled >
                                Selecciona la Unidad de Medida
                            </option>

                            <option value="" disabled selected>Selecciona un tipo de dato</option>
    
                            <option value="dias">
                                Días
                            </option>
    
                            <option value="toneladas" >
                            
                                Toneladas
                            </option>
    
                            <option value="porcentaje">
                                % Porcentaje
                            </option>
    
                            <option value="pesos">
                                $ Pesos
                            </option>
                            <option value="unidad">
                                Unidad
                            </option>
    
                        </select>
                    </div>


                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_resta" name="descripcion" form="resta_container"></textarea>
                        <label class="form-label" for="resta_division">Descripción del campo</label>
                    </div>
                    
                </div>

                <form action="{{route('input.resta.guardar', $indicador->id)}}" method="POST" id="resta_container" class="row m-3 justify-content-around">
                    @csrf
                    
                    <h6 class="my-0">Minuendo (El minuendo es el número inicial del cual se va a restar)</h6>
                    <div class="row mx-2 bg-light pb-5 border" id="minuendo_container" ondrop="dropResta(event)" ondragover="allowDropResta(event)" id="minuendo_container">
                    </div>
                    
                    <h6 class="my-0">Sustraendo (El sustraendo es el número que se quita de ese minuendo)</h6>
                    <div class="row mx-2 bg-light pb-5 mt-1 border" id="sustraendo_container" ondrop="dropResta(event)" ondragover="allowDropResta(event)" id="sustraendo_container">
                    </div>
                    
                </form>


                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        {{-- <div class="form-check mt-2">
                            <input form="resta_container" class="form-check-input form-check-input-lg" type="checkbox" name="resultado_final" id="resultado_final" />
                            <label class="form-check-label text-danger fw-bold" for="resultado_final">Campo Final</label>
                        </div> --}}


                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_resta">
                            <input   class="form-check-input" form="resta_container" type="checkbox" name="resultado_final" id="resultado_final_resta">
                                Campo Final
                            </label>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="referencia_resta">
                            <input   class="form-check-input" form="resta_container" type="checkbox" name="referencia" id="referencia_resta">
                                Referencia
                            </label>
                        </div>


                        <button form="resta_container"  class="btn btn-warning" >
                            Crear Campo Promedio
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalPorcentajeCampos" tabindex="-1"  aria-labelledby="sdsad" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-purple py-4">
                <h3 class="text-white" id="exampleModalLabel">
                <i class="fa-solid fa-gauge-simple"></i>
                   Selecciona los datos que se van a comparar para sacar el porcentaje.
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body p-2">

                <div class="row pb-5 px-2 bg-light border m-3 no-drop"  id="calculados_container_porcentaje" ondrop="dropPorcentaje(event)" ondragover="allowDropPorcentaje(event)">
                    <div class="col-12 no-drop m-2">
                        <h6 class"no-drop">Campos Disponibles</h6>
                    </div>

                    @forelse ($campos_unidos as $campo_unido)

                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 p-3 border no-drop bg-white" draggable="true" id="{{$campo_unido->id_input}}" ondragstart="dragStartPorcentaje(event)">
                            <label class="no-drop">{{ $campo_unido->nombre }}</label>
                            <input class="form-control form-control-sm no-drop" placeholder="{{ $campo_unido->nombre }}" id="{{$campo_unido->id_input}}" disabled type="{{ $campo_unido->tipo_dato }}"><input type="hidden" name="input_porcentaje[]" value="{{$campo_unido->id_input}}">
                        </div>

                    @empty
                        
                    @endforelse
                </div>
            

                <div class="form-group mt-5 mx-4 no-drop">
                    <h4>Datos Nuevo Campo</h4>

                    
        
                    <div class="form-outline no-drop" data-mdb-input-init>
                        <input type="text" id="nombre_campo_porcentaje" class="form-control form-control-lg no-drop w-100 {{ $errors->first('nombre_campo_porcentaje') ? 'is-invalid' : '' }}" form="porcentaje_container" name="nombre" required>
                        <label class="form-label" for="nombre_campo_porcentaje" >Nombre nuevo campo </label>
                    </div>


                    <div class="form-outline no-drop mt-3" data-mdb-input-init>
                        <textarea class="w-100 form-control" id="descripcion_porcentaje" name="descripcion" form="porcentaje_container" required></textarea>
                        <label class="form-label" for="descripcion_porcentaje">Descripción del campo</label>
                    </div>
                    
                </div>
                


                <form action="{{route('input.porcentaje.guardar', $indicador->id)}}" method="POST" id="porcentaje_container" class="row m-3 justify-content-around">
                    @csrf
                    
                   
                    <h6 class="my-0">Total (Cantidad sobre la que se va a comparar)</h6>                    
                    <div class="row mx-2 bg-light p-3 pb-5 border" ondrop="dropPorcentaje(event)" ondragover="allowDropPorcentaje(event)" id="parte_container">
                    </div>
                    
                    <h6 class="my-0">Parte (Cantidad a comparar)</h6>
                    <div class="row mx-2 bg-light p-3 pb-5 mt-1 border" ondrop="dropPorcentaje(event)" ondragover="allowDropPorcentaje(event)" id="total_container">
                    </div>
                    
                </form>


                <div class="modal-footer">
                    <div class="btn-group shadow-0 gap-3 d-flex align-item-center">

                        {{-- <div class="form-check mt-2">
                            <input class="form-check-input form-check-input-lg"  type="checkbox" name="resultado_final" id="resultado_final" form="porcentaje_container"/>
                            <label class="form-check-label text-danger fw-bold" for="resultado_final">Resultado Final</label>
                        </div> --}}

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="resultado_final_porcentaje">
                            <input   class="form-check-input" form="porcentaje_container" type="checkbox" name="resultado_final" id="resultado_final_porcentaje">
                                Campo Final
                            </label>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <label class="form-check-label fw-bold text-primary ms-2" for="referencia_porcentaje">
                            <input   class="form-check-input" form="porcentaje_container" type="checkbox" name="referencia" id="referencia_porcentaje">
                                Referencia
                            </label>
                        </div>



                        <button  class=" text-white bg-purple" form="porcentaje_container" > {{-- id="crear_campo_promedio" --}}
                            Crear Campo Porcentaje
                        </button>
                        {{-- <a  href="#" class="btn btn-secondary" id="vista_previa_button">
                            Vista Previa
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




{{-- modales de los campos calculados --}}















<div class="modal fade" id="modalCamposPrecargados" tabindex="-1"  aria-labelledby="exampleModalLaaabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-dark  py-4">
            <h3 class="text-white" id="exampleModalLabel">Selecciona un campo para agregar </h3>
            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-2">

            <form action="{{route('agregar.campo.precargado', $indicador->id)}}" method="POST">
                @csrf
                <div class="form-group">

                    <select name="campo_precargado" class="form-control" id="">
                        <option value="" disabled selected>
                            Selecciona un campo precargado
                        </option>
                        @foreach ($informacion_foranea as $informacion)
                        
                            <option 
                            value="
                                {{$informacion->id_input}}|{{$informacion->id}}|{{$informacion->nombre}}|{{$informacion->descripcion}}">                   
                                {{$informacion->nombre}}
                            </option>

                        @endforeach
                    </select>

                </div>

                <div class="form-group mt-3">
                    <button class="btn btn-dark">
                        Agregar campo
                    </button>
                </div>
            </form>


        </div>
        {{-- <div class="modal-footer">
        </div> --}}
        </div>
    </div>
</div>


<div class="modal fade" id="modalCampos" tabindex="-1"  aria-labelledby="exampleModdalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary  py-4">
            <h3 class="text-white" id="exampleModalLabel">Agregar campos vacios </h3>
            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-2">

            <form action="{{route('agregar.campo.vacio', $indicador->id)}}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-12 ">
                        <div class="form-group mt-3">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" class="form-control form-control-lg w-100" id="nombre_campo_vacio" name="nombre_campo_vacio" required>
                                <label class="form-label" for="nombre_campo_vacio" >Nombre <span class="text-danger">*</span> </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 my-2">
                        <div class="form-group">
                            <select name="unidad_medida" 
                                    class="form-select form-select-lg w-100 {{ $errors->first('unidad_medida') ? 'is-invalid' : '' }}"  
                                    id="" 
                                    required>
        
                                <option value="" disabled }>
                                    Selecciona la Unidad de Medida
                                </option>

                                <option value="" disabled selected>Selecciona un tipo de dato</option>
        
                                <option value="dias">
                                    Días
                                </option>
        
                                <option value="toneladas" >
                                
                                    Toneladas
                                </option>
        
                                <option value="porcentaje">
                                    % Porcentaje
                                </option>
        
                                <option value="pesos">
                                    $ Pesos
                                </option>
                                <option value="unidad">
                                    Unidad
                                </option>
        
                            </select>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group mt-2">
                            <div class="form-outline" data-mdb-input-init>
                                {{-- <input type="text" class="form-control form-control-lg w-100" id="nombre_campo_vacio" name="nombre_campo_vacio" required> --}}
                                <textarea name="descripcion" class="form-control w-100 " id="descripcion"></textarea>
                                <label class="form-label" for="descripcion" >Descripción </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                                Insertar Campo
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
        </div>
    </div>
</div>





{{-- @forelse ($campos_unidos as $campo)

<div class="modal fade" id="del{{$campo->id_input}}" tabindex="-1"  aria-labelledby="exampleMddodalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-danger py-4">
            <h3 class="text-white" id="exampleModalLabel">¿Eliminar a {{$campo->nombre}} ?</h3>
            <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-4">

            <form action="{{route('eliminar.campo', $campo->id)}}" method="POST">
                @csrf @method('DELETE')

                <div class="form-group">
                    <input type="hidden" name="id_input" value="{{$campo->id_input}}" >
                    <input type="hidden" name="campo_vacio" value="{{$campo->tipo}}">
                    <input type="hidden" name="campo_precargado" value="{{$campo->id_input_foraneo}}">
                    <input type="hidden" name="campo_calculado" value="{{$campo->operacion}}">
                </div>


                <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                    <h6>Eliminar</h6>
                </button>

            </form>

        </div>
        {{-- <div class="modal-footer">
        </div> 
        </div>
    </div>
</div>

@empty

@endforelse --}}


@endsection





@section('scripts')

{{-- Me mamecon esto, iba a importar todo el JS en u archivo, pero resulta que estoy mandando una variable de backend a este archivo de blade,
asi que puse el aterrizado de la variable arriba del codigo del promedio. --}}
<script>
    const campos_para_operaciones = @json($campos_unidos);
    //const calculados_container = document.getElementById("calculados_container_promedio");
</script>

{{-- <script src="{{asset('js/drop.js')}}"></script> --}}
<script src="{{asset('js/drop_promedio.js')}}" ></script>
<script src="{{asset('js/drop_division.js')}}"></script>
<script src="{{asset('js/drop_resta.js')}}"></script>
<script src="{{asset('js/drop_suma.js')}}"></script>
<script src="{{asset('js/drop_porcentaje.js')}}"></script>
<script src="{{asset('js/drop_multiplicacion.js')}}" ></script>


<script>
  const fab = document.getElementById('fabToggle');
  const container = document.querySelector('.fab-container');

  fab.addEventListener('click', () => {
    container.classList.toggle('active');
  });
</script>


@endsection
