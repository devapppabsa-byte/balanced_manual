@extends('plantilla')
@section('title', 'Perspectivas')
@section('contenido')
@php
    use App\Models\Indicador;
    use App\Models\Norma;
    use App\Models\Encuesta;
    use App\Models\IndicadorLleno;
    use Carbon\Carbon;
@endphp
<style>
/* Cuando el checkbox está seleccionado */
.btn-check:checked + .custom-check {
    background-color: #0d6efd; /* primary */
    color: #fff;
    border-color: #0d6efd;
}

/* Hover opcional más bonito */
.custom-check:hover {
    background-color: #e7f1ff;
}

/* Opcional: transición suave */
.custom-check {
    transition: all 0.2s ease;
}
</style>


<div class="container-fluid sticky-top">
    <div class="row bg-primary  d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h1 class="mt-1 mb-0 league-spartan">Objetivos</h1>
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



    <div class="row">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body py-3 px-4">

                <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">

                    <form action="#" method="GET" class="mb-0">
                        <div class="d-flex flex-wrap align-items-end gap-3">

                            <!-- Fecha inicio -->
                            <div>
                                <label class="form-label small text-muted fw-semibold mb-1">Desde</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fa-solid fa-calendar-days text-primary"></i>
                                    </span>
                                    <input
                                        type="date"
                                        name="fecha_inicio"
                                        value="{{ request('fecha_inicio') ?? '2025-01-01' }}"
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
                                    <input
                                        type="date"
                                        name="fecha_fin"
                                        value="{{ request('fecha_fin') ?? now()->format('Y-m-d') }}"
                                        class="form-control border-0 bg-light datepicker"
                                        onchange="this.form.submit()">
                                </div>
                            </div>

                            <!-- Botón -->
                            <div>
                                <a
                                    class="btn btn-primary btn-sm"
                                    data-mdb-ripple-init
                                    data-mdb-modal-init
                                    data-mdb-target="#agregar_objetivo">
                                    <i class="fa-solid fa-plus-circle me-2"></i>
                                    Agregar Objetivos
                                </a>
                            </div>

                        </div>
                    </form>

                    @php
                        $ponderacion_objetivos = [];
                        foreach ($objetivos as $objetivo) {
                            $ponderacion_objetivos[] = $objetivo->ponderacion;
                        }
                    @endphp

                    <div
                        class="badge badge-lg {{ (array_sum($ponderacion_objetivos) != 100 ? 'badge-danger' : 'badge-success') }}">
                        <i class="fa fa-exclamation-circle me-1"></i>
                        La suma de las ponderaciones de los Objetivos es:
                        <b>{{ array_sum($ponderacion_objetivos) }}%</b>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>




<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 mb-4">
            <div
                class="card bg-success text-white border-0 shadow-sm"
                id="card_cumplimiento">

                <div class="card-body text-center py-4 px-4">
                    <div class="mb-2">
                        <i
                            class="fa fs-3"
                            id="icono_card_cumplimiento"></i>
                    </div>

                    <h5 class="fw-semibold mb-2">
                        Cumplimiento
                    </h5>

                    <h2
                        class="fw-bold mb-0"
                        id="suma_cumplimientos"></h2>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
                <!-- Departamentos Grid -->
            <div class="row g-4  justify-content-center">
                @forelse ($objetivos as $objetivo)

                    @php

                        $indicadoresObjetivo = Indicador::where('id_objetivo_perspectiva', $objetivo->id)->get();
                        $encuestasObjetivo = Encuesta::where('id_objetivo_perspectiva', $objetivo->id)->get();
                        $normasObjetivo = Norma::where('id_objetivo_perspectiva', $objetivo->id)->get();


                        $suma=0;
                        $suma_ponderacion=0;

                            foreach($indicadoresObjetivo as $indicador_ponderacion){
                                $suma_ponderacion = $suma_ponderacion + $indicador_ponderacion->ponderacion_indicador;
                            }
                            foreach($encuestasObjetivo as $encuesta_ponderacion){
                                $suma_ponderacion = $suma_ponderacion + $encuesta_ponderacion->ponderacion_encuesta;
                            }
                            foreach($normasObjetivo as $norma_ponderacion){
                                $suma_ponderacion = $suma_ponderacion + $norma_ponderacion->ponderacion_norma;
                            }
                            
                        @endphp
                    

                    <div class="col-12 col-sm-12 col-md-11 col-lg-6 col-xl-6 ">
                        <div class="card border-0 shadow-sm h-100 bg-light ">
                            <div class="card-body p-4 d-flex flex-column">

                                <!-- Nombre del Departamento -->
                                <div class="row mb-1 row">
                                    <div class="col-12">
                                        <h4 class="fw-bold mb-0 department-name text-truncate" data-mdb-tooltip-init title="{{ $objetivo->nombre }}" >
                                            {{ $objetivo->nombre }}
                                        </h4>
                                        <h5 class="text-primary">
                                            Peso en la Perspectiva:  <span class="fw-bold">{{ $objetivo->ponderacion }} %</span> 
                                        </h5>
                                        <h3 class="text-dark fw-bold">
                                            Meta:  <span class="fw-bold">{{ $objetivo->meta }} %</span> 
                                        </h3>
                                        <h6 class=" badge badge-lg {{ ($suma_ponderacion === 100) ? 'badge-success' : 'badge-danger' }}">
                                            <i class="fa fa-exclamation-circle"></i>  
                                            Suma de las ponderaciones de los indicadores:  
                                            <span class="fw-bold">
                                                {{ $suma_ponderacion }} %
                                            </span> 
                                        </h6>
                                    </div>
                                    <hr>
                                    <div class="col-12 ">
                                        <span class=" bungee-regular text-muted">Indicadores:</span>
                                    </div>
                                    <div class="col-12">
                                        <div class="row justify-content-center">


                                            {{-- aqui esta el ciclo de los indicadores KPI que conforman el objetivo --}}
                                            @foreach($indicadoresObjetivo as $indicador)

                                                    <div class="col-11 my-2 p-2 m-1 rounded shadow-sm border border-2">
                                                            <!-- Nombre -->
                                                            <div class="fw-bold " style="font-size: 14px;">
                                                                
                                                                <a class="text-danger btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#ind_del{{$indicador->id}}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>

                                                                <a href="{{ route('analizar.indicador', $indicador->id) }}" target="_blank">
                                                                    {{ $indicador->nombre }} 
                                                                </a>
                                                                
                                                                @if(!is_null($indicador->ponderacion_indicador))
                                                                    <span class="text-success">
                                                                        -  Ponderacion: 
                                                                        {{ $indicador->ponderacion_indicador }}%
                                                                    </span>
                                                                @endif

                                                                <a class="text-primary mx-1" data-mdb-tooltip-init title="Agregar ponderacion al indicador" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#pon{{ $indicador->id }}">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </a>
                                                                <a href="#" class="text-danger"></a>
                                                            </div>
                                                            <hr>
                                                            <!-- Info secundaria -->
                                                            <div class="d-flex gap-3 mt-1 flex-wrap text-muted" style="font-size: 13px;">

                                                                <span>
                                                                    <i class="fa-solid fa-bullseye me-1"></i>
                                                                    Meta: {{ $indicador->meta_esperada }}
                                                                </span>
                                        
                                                                @php

                                                                    $informacion_indicadores = IndicadorLleno::where('id_indicador', $indicador->id)->where('final', 'on')->whereBetween('fecha_periodo', [$inicio, $fin])->get();
                                                                    $array_datos = [];

                                                                    foreach($informacion_indicadores as $informacion_indicador){

                                                                        array_push($array_datos, $informacion_indicador->informacion_campo);
                                                                    
                                                                    }
                                                    
                                                                @endphp
                                                            <span>

                                                                <i class="fa-solid fa-gauge"></i>
                                                                Promedio Cumplimiento: 
                                                                

                                                                @if (!empty($array_datos))
                                                                    <span class="fw-bold">
                                                                        @php
                                                                            $promedio_cumplimiento;    
                                                                        @endphp
                                                                        
                                                                        @if ($indicador->tipo_indicador == "normal")

                                                                            @if ($indicador->unidad_medida == "porcentaje")
                                                                                {{ round($promedio_cumplimiento =  array_sum($array_datos) / count($array_datos), 2 )}} %
                                                                            @else
                                                                                {{ round($promedio_cumplimiento =  array_sum($array_datos) / (count($array_datos) / $indicador->meta_esperada) * 100, 2)}} %
                                                                            @endif

                                                                        @else

                                                                            @if($indicador->unidad_medida == "porcentaje")
                                                                            
                                                                                {{ round($promedio_cumplimiento = ( array_sum($array_datos) / count($array_datos)), 2)}} %

                                                                            @else
                                                                                {{ round($promedio_cumplimiento =   $indicador->meta_esperada / (array_sum($array_datos) / count($array_datos)) * 100, 2)}} %
                                                                            @endif

                                                                        @endif
                                                                    </span>
                                                                @else

                                                                    <span class="fw-bold">0</span>
                                                                
                                                                @endif
                                                            </span>

                                                            <span>
                                                                <i class="fa-solid fa-right-left"></i>
                                                                Indicador vs Ponderación: 
                                                            </span>

                                                            @if (!empty($array_datos))
                                                                <span class="fw-bold">
                                                                    {{number_format(($promedio_cumplimiento * $indicador->ponderacion_indicador) / 100, 2)}} %
                                                                    {{-- esto es para completar la suma del cumplimiento general --}}
                                                                    @php
                                                                        $suma = $suma + ($indicador->ponderacion_indicador * $promedio_cumplimiento) / 100;
                                                                    @endphp
                                                                </span>

                                                                
                                                            @else
                                                                <span class="fw-bold">0</span>
                                                            @endif
                                                        
                                                    </div>

                                                        

                                                    </div>

                                                            
                                            {{-- modales para la ponderacion --}}
                                                    <div class="modal fade" id="pon{{ $indicador->id }}" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-primary text-white border-0 py-3">
                                                                    <span class="modal-title fw-bold" id="agregarDepartamentoLabel">
                                                                        <i class="fa-solid fa-plus-circle me-2"></i>
                                                                        Agregar Ponderación
                                                                    </span>
                                                                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <form action="{{ route('agregar.ponderacion.indicador.objetivo', $indicador->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="form-outline" data-mdb-input-init>
                                                                            <input type="text" class="form-control" value="{{ old('ponderacion_indicador', $indicador->ponderacion_indicador) }}" id="ponderacion_indicador" name="ponderacion_indicador" |value="{{old('ponderacion_indicador')}}"required>
                                                                            <label class="form-label" for="ponderacion_indicador">
                                                                                Ponderación:
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            @if ($errors->first('ponderacion_indicador'))
                                                                                <div class="invalid-feedback">
                                                                                    {{ $errors->first('ponderacion_indicador') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group mt-2">
                                                                            <button class="btn btn-primary btn-sm">
                                                                                Guardar
                                                                            </button>    
                                                                        </div> 
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            {{-- modales para la ponderacion --}}



                                                    <div class="modal fade" id="ind_del{{$indicador->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$objetivo->id}}" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-danger text-white border-0 py-3">
                                                                    <h5 class="modal-title fw-bold" id="">
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
                                                                        <h6 class="fw-semibold">¿Estás seguro de el indicador del Objetivo?</h6>
                                                                        <p class="text-muted mb-0">
                                                                            <strong>{{$indicador->nombre}}</strong>
                                                                        </p>

                                                                        <small class="text-muted d-block">
                                                                            Esta acción no se puede deshacer.
                                                                        </small>
                                                                    </div>

                                                                    <form action="{{route('indicador.objetivo.delete', [$objetivo->id, $indicador->id])}}" method="POST">
                                                                        @csrf 
                                                                        @method('PUT')
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

                                            
                                            @foreach($encuestasObjetivo as $encuesta)

                                                    <div class="col-11 my-2 p-2 m-1 rounded shadow-sm border border-2">
                                                            <!-- Nombre -->
                                                            <div class="fw-bold " style="font-size: 14px;">
                                                                
                                                                <a class="text-danger btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#enc_del{{$encuesta->id}}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                                
                                                                <a href="{{route('encuesta.index', $encuesta->id)}}" target="_blank">
                                                                    {{ $encuesta->nombre }} 
                                                                </a>
                                                                
                                                                @if(!is_null($encuesta->ponderacion_encuesta))
                                                                    <span class="text-success">
                                                                        -  Ponderacion: 
                                                                        {{ $encuesta->ponderacion_encuesta }}%
                                                                    </span>
                                                                @endif
                                                                <a class="text-primary mx-1" data-mdb-tooltip-init title="Agregar ponderacion al indicador" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#ponen{{ $encuesta->id }}">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </a>
                                                                
                                                            </div>
                                                            <hr>
                                                            <!-- Info secundaria -->
                                                            <div class="d-flex gap-3 mt-1 flex-wrap text-muted" style="font-size: 13px;">

                                                                <span>
                                                                    <i class="fa-solid fa-bullseye me-1"></i>
                                                                    Meta: {{ $encuesta->meta_esperada }}
                                                                </span>
                                                        
                                                                @php

                                                                    $informacion_encuestas = DB::table('respuestas as r')
                                                                                            ->join('preguntas as p', 'r.id_pregunta', '=', 'p.id')
                                                                                            ->where('p.cuantificable', 1)
                                                                                            ->whereBetween('r.created_at', [$inicio, $fin])
                                                                                            ->avg(DB::raw('CAST(r.respuesta AS DECIMAL(10,2))'));


                                                                                        $rangos = [];

                                                                                        if ($inicio->month <= 6) {
                                                                                            $rangos[] = [
                                                                                                'inicio' => $inicio->copy()->startOfYear()->startOfDay(),
                                                                                                'fin' => $inicio->copy()->month(6)->endOfMonth()->endOfDay(),
                                                                                            ];
                                                                                        }

                                                                                        if ($fin->month >= 7 || $inicio->year !== $fin->year) {
                                                                                            $rangos[] = [
                                                                                                'inicio' => $fin->copy()->month(7)->startOfMonth()->startOfDay(),
                                                                                                'fin' => $fin->copy()->endOfYear()->endOfDay(),
                                                                                            ];
                                                                                        }

                                                                                        $informacion_encuestas = DB::table('respuestas as r')
                                                                                            ->join('preguntas as p', 'r.id_pregunta', '=', 'p.id')
                                                                                            ->where('p.cuantificable', 1)
                                                                                            ->where(function ($query) use ($rangos) {
                                                                                                foreach ($rangos as $rango) {
                                                                                                    $query->orWhereBetween('r.created_at', [
                                                                                                        $rango['inicio']->utc(),
                                                                                                        $rango['fin']->utc()
                                                                                                    ]);
                                                                                                }
                                                                                            })
                                                                                            ->avg(DB::raw('CAST(r.respuesta AS DECIMAL(10,2))'));

                                                                                        $informacion_encuestas = round($informacion_encuestas ?? 0, 2)*10;                                                                                            
                                                                    

                                                                    //esto es por que la consulta de arriba me da el recultado en unidad y no en porcentaje.
                                                                   
                                                                    //$informacion_encuestas = $informacion_encuestas * 10;


                                                                    
                                                                    //dentro se pueden registrar encuestas, y dentro de estas encuestas se registran preguntas, las preguntas son contestadas por clientes.Para ver el resultado se toman las preguntas que tengan en el campo 'cuantificable' un 1. de todo eso necesito saber solo el cumplimiento general, osea el promedio de o que se contesto en los ultimos 6 meses.
                                                                @endphp

                                                                <span >
                                                                    <i class="fa-solid fa-gauge"></i>
                                                                    Promedio Cumplimiento: 
                                                                        <span class="fw-bold" >
                                                                            {{ round($informacion_encuestas, 2) }} %
                                                                        </span>
                                                                </span>
                                                    
                                                                <span>
                                                                    <i class="fa-solid fa-right-left"></i>
                                                                    Indicador vs Ponderación: 
                                                                </span>

                                                                <span class="fw-bold">
                                                                    {{round((($informacion_encuestas * $encuesta->ponderacion_encuesta)/100), 2)}} %
                                                                    @php
                                                                        $suma = $suma + (($informacion_encuestas * $encuesta->ponderacion_encuesta)/100);
                                                                    @endphp  
                                                                </span>
                                                        
                                                        </div>
                                                    </div>

                                            {{-- modales para la ponderacion --}}
                                                    <div class="modal fade" id="ponen{{ $encuesta->id }}" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-primary text-white border-0 py-3">
                                                                    <span class="modal-title fw-bold" id="agregarDepartamentoLabel">
                                                                        <i class="fa-solid fa-plus-circle me-2"></i>
                                                                        Agregar Ponderación
                                                                    </span>
                                                                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <form action="{{ route('agregar.ponderacion.encuesta.objetivo', $encuesta->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="form-outline" data-mdb-input-init>
                                                                            <input type="text" value="{{ old('ponderacion_encuesta', $encuesta->ponderacion_encuesta) }}" class="form-control" id="ponderacion_encuesta" name="ponderacion_encuesta" |value="{{old('ponderacion_encuesta')}}"required>
                                                                            <label class="form-label" for="ponderacion_encuesta">
                                                                                Ponderación:
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            @if ($errors->first('ponderacion_encuesta'))
                                                                                <div class="invalid-feedback">
                                                                                    {{ $errors->first('ponderacion_encuesta') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group mt-2">
                                                                            <button class="btn btn-primary btn-sm">
                                                                                Guardar
                                                                            </button>    
                                                                        </div> 
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            {{-- modales para la ponderacion --}}

                                                    <div class="modal fade" id="enc_del{{$encuesta->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$objetivo->id}}" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-danger text-white border-0 py-3">
                                                                    <h5 class="modal-title fw-bold" id="">
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
                                                                        <h6 class="fw-semibold">¿Estás seguro de eliminar la encuesta del Objetivo?</h6>
                                                                        <p class="text-muted mb-0">
                                                                            <strong>{{$encuesta->nombre}}</strong>
                                                                        </p>

                                                                        <small class="text-muted d-block">
                                                                            Esta acción no se puede deshacer.
                                                                        </small>
                                                                    </div>

                                                                    <form action="{{route('encuesta.objetivo.delete', [$objetivo->id, $encuesta->id])}}" method="POST">
                                                                        @csrf 
                                                                        @method('PUT')
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


                                            @foreach ($normasObjetivo as $norma)
                                                    <div class="col-11 my-2 p-2 m-1 rounded shadow-sm border border-2">
                                                            <!-- Nombre -->
                                                            <div class="fw-bold " style="font-size: 14px;">
                                                                
                                                                <a class="text-danger btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#nom_del{{$norma->id}}">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                                
                                                                <a href="{{route('apartado.norma', $norma->id)}}" target="_blank">
                                                                   {{ $norma->nombre }} 
                                                                </a>
                                                                
                                                                @if(!is_null($norma->ponderacion_norma))
                                                                    <span class="text-success">
                                                                        -  Ponderacion: 
                                                                        {{ $norma->ponderacion_norma }}%
                                                                    </span>
                                                                @endif
                                                                <a class="text-primary mx-1" data-mdb-tooltip-init title="Agregar ponderacion al indicador" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#ponorm{{ $norma->id }}">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </a>
                                                                <a href="#" class="text-danger"></a>
                                                            </div>
                                                            <hr>
                                                            <!-- Info secundaria -->
                                                            <div class="d-flex gap-3 mt-1 flex-wrap text-muted" style="font-size: 13px;">

                                                                <span>
                                                                    <i class="fa-solid fa-bullseye me-1"></i>
                                                                    Meta: {{ $norma->meta_esperada }}
                                                                </span>
                                                        
                                                                @php

                                                                
                                                                    // $inicioMeses = $inicio->copy()->startOfMonth();
                                                                    // $finMeses = $fin->copy()->subMonth()->startOfMonth();

                                                                    // $inicioConsulta = $inicio->copy()->addMonth()->startOfMonth();
                                                                    // $finConsulta = $fin->copy()->addMonth()->endOfMonth();

                                                                    $inicioMeses = $inicio->copy()->timezone('America/Mexico_City')->startOfMonth();
                                                                    $finMeses = $fin->copy()->timezone('America/Mexico_City')->subSecond()->startOfMonth();
                                                                    $inicioConsulta = $inicio->copy()->addMonth()->startOfMonth();
                                                                    $finConsulta = $fin->copy()->addMonth()->endOfMonth();



                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Meses visibles del rango
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $meses = collect();

                                                                    $cursor = $inicioMeses->copy();

                                                                    while ($cursor <= $finMeses) {
                                                                        $meses->push($cursor->format('Y-m'));
                                                                        $cursor->addMonth();
                                                                    }

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Total de apartados
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $total_apartados = DB::table('apartado_norma')
                                                                        ->where('id_norma', $norma->id)
                                                                        ->count();

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Cumplimiento por mes
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $porMes = DB::table('apartado_norma as an')
                                                                        ->leftJoin('cumplimiento_norma as cn', function ($join) use ($inicioConsulta, $finConsulta) {
                                                                            $join->on('cn.id_apartado_norma', '=', 'an.id')
                                                                                ->whereBetween('cn.created_at', [$inicioConsulta, $finConsulta]);
                                                                        })
                                                                        ->where('an.id_norma', $norma->id)
                                                                        ->whereNotNull('cn.created_at')
                                                                        ->select(
                                                                            DB::raw("DATE_FORMAT(DATE_SUB(cn.created_at, INTERVAL 1 MONTH), '%Y-%m') as mes"),
                                                                            DB::raw('COUNT(DISTINCT cn.id_apartado_norma) as cumplidos')
                                                                        )
                                                                        ->groupBy(DB::raw("DATE_FORMAT(DATE_SUB(cn.created_at, INTERVAL 1 MONTH), '%Y-%m')"))
                                                                        ->pluck('cumplidos', 'mes');

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Completar meses faltantes con 0
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $porcentajes = $meses->map(function ($mes) use ($porMes, $total_apartados) {
                                                                        $cumplidos = $porMes[$mes] ?? 0;

                                                                        if ($total_apartados == 0) {
                                                                            return 0;
                                                                        }

                                                                        return ($cumplidos / $total_apartados) * 100;
                                                                    });

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Promedio mensual
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $porcentaje_promedio = round($porcentajes->avg(), 2);

                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | Objeto compatible con tu código actual
                                                                    |--------------------------------------------------------------------------
                                                                    */
                                                                    $normas_cumplimiento = (object) [
                                                                        'total_apartados' => $total_apartados,
                                                                        'porcentaje_mes' => $porcentaje_promedio,
                                                                    ];
                                                                


                                                                    
                                                                    //dentro se pueden registrar encuestas, y dentro de estas encuestas se registran preguntas, las preguntas son contestadas por clientes.Para ver el resultado se toman las preguntas que tengan en el campo 'cuantificable' un 1. de todo eso necesito saber solo el cumplimiento general, osea el promedio de o que se contesto en los ultimos 6 meses.
                                                                @endphp

                                                            <span >
                                                                <i class="fa-solid fa-gauge"></i>
                                                                Promedio Cumplimiento: 
                                                                    <span class="fw-bold">
                                                                       
                                                                         {{ round($normas_cumplimiento->porcentaje_mes, 2) }} % 
                                                                    </span>
                                                            </span>
                                                  
                                                            <span>
                                                                <i class="fa-solid fa-right-left"></i>
                                                                Indicador vs Ponderación: 
                                                            </span>

                                                            <span class="fw-bold">
                                                                {{round((($normas_cumplimiento->porcentaje_mes * $norma->ponderacion_norma)/100), 2)}} %

                                                                @php
                                                                    $suma = $suma + (($normas_cumplimiento->porcentaje_mes * $norma->ponderacion_norma)/100);
                                                                @endphp                                                 
                                                            </span>

                                                            
    
                                                    </div>

                                                        

                                                    </div>

                                            {{-- modales para la ponderacion --}}
                                                    <div class="modal fade" id="ponorm{{ $norma->id }}" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-primary text-white border-0 py-3">
                                                                    <span class="modal-title fw-bold" id="agregarDepartamentoLabel">
                                                                        <i class="fa-solid fa-plus-circle me-2"></i>
                                                                        Agregar Ponderación
                                                                    </span>
                                                                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <form action="{{ route('agregar.ponderacion.norma.objetivo', $norma->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="form-outline" data-mdb-input-init>
                                                                            <input type="text" value="{{ old('ponderacion_norma', $norma->ponderacion_norma) }}" class="form-control" id="ponderacion_norma" name="ponderacion_norma" |value="{{old('ponderacion_norma')}}"required>
                                                                            <label class="form-label" for="ponderacion_norma">
                                                                                Ponderación:
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            @if ($errors->first('ponderacion_norma'))
                                                                                <div class="invalid-feedback">
                                                                                    {{ $errors->first('ponderacion_norma') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="form-group mt-2">
                                                                            <button class="btn btn-primary btn-sm">
                                                                                Guardar
                                                                            </button>    
                                                                        </div> 
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            {{-- modales para la ponderacion --}}



                                                    <div class="modal fade" id="nom_del{{$norma->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$objetivo->id}}" aria-hidden="true" data-mdb-backdrop="static">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <div class="modal-header bg-danger text-white border-0 py-3">
                                                                    <h5 class="modal-title fw-bold" id="">
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
                                                                        <h6 class="fw-semibold">¿Estás seguro de el indicador del Objetivo?</h6>
                                                                        <p class="text-muted mb-0">
                                                                            <strong>{{$norma->nombre}}</strong>
                                                                        </p>

                                                                        <small class="text-muted d-block">
                                                                            Esta acción no se puede deshacer.
                                                                        </small>
                                                                    </div>

                                                                    <form action="{{route('norma.objetivo.delete', [$objetivo->id, $norma->id])}}" method="POST">
                                                                        @csrf 
                                                                        @method('PUT')
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
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center my-4">
                                    <div class="col-4 shadow-sm mx-1 rounded p-3 text-white {{ ($suma < $objetivo->meta) ? 'bg-danger' : 'bg-success' }}">
                                        <h5 class="text-center">
                                            Porcentaje de cumplimiento del objetivo: 
                                        </h5>
                                        <h1 class="text-center fw-bold" data-suma-cumplimiento-objetivo="{{number_format($suma,2)}}">
                                            {{number_format($suma,2)}}%
                                        </h1>
                                    </div>

                                    <div class="col-4 shadow-sm mx-1 rounded p-3">
                                        <h5 class="text-center">
                                            Porcentaje aportado a la perspectiva: 
                                        </h5>
                                        <h1 class="text-center fw-bold cumplimiento_objetivo" data-suma-cumplimiento-objetivo="{{ number_format(($suma / 100) * $objetivo->ponderacion,2) }}">
                                            {{ number_format(($suma / 100) * $objetivo->ponderacion,2) }} %
                                        </h1>                                   
                                    </div>
                                </div>
                                {{-- Aqui va el porcentaje de cumplimietno total --}}


                                <!-- Acciones -->
                                <div class="d-flex justify-content-end align-items-center pt-2 border-top">
                                    <div class="btn-group" role="group">

                                        <button class="btn btn-sm btn-outline-success text-capitalize" data-mdb-tooltip-init title="Agregr Indicador" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#add_in{{$objetivo->id}}">
                                            <i class="fa-solid fa-plus-circle"></i>
                                            Agregar Indicadores
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" data-mdb-tooltip-init title="Editar " data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#edit_ob{{$objetivo->id}}">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger" data-mdb-tooltip-init title="Eliminar" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#del_ob{{$objetivo->id}}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                @empty
            <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-eye text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-2">No se han registrado objetivos.</h5>
                        <p class="text-muted mb-4">
                            <small>Comienza agregando tu primer objetivo a la perspectiva {{ $perspectiva->nombre }}.</small>
                        </p>
                        <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_objetivo">
                            <i class="fa-solid fa-plus-circle me-2"></i>
                            Agregar Objetivo
                        </button>
                    </div>
                </div>  
                @endforelse

            </div>

        </div>
    </div>
</div>




<div class="modal fade" id="agregar_objetivo" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarDepartamentoLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Objetivo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{ route('objetivo.store', $perspectiva->id) }}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" class="form-control {{ $errors->first('nombre_objetivo') ? 'is-invalid' : '' }}" id="nombre_objetivo" name="nombre_objetivo" |value="{{old('nombre_objetivo')}}"required>
                                <label class="form-label" for="nombre_objetivo">
                                    Objetivos
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_objetivo'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_objetivo') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" min="1" max="100" class="form-control {{ $errors->first('ponderacion_objetivo') ? 'is-invalid' : '' }}" id="ponderacion_objetivo" name="ponderacion_objetivo" |value="{{old('ponderacion_objetivo')}}"required>
                                <label class="form-label" for="ponderacion_objetivo">
                                    Ponderación
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('ponderacion_objetivo'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('ponderacion_objetivo') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" min="1" max="100" class="form-control {{ $errors->first('meta_objetivo') ? 'is-invalid' : '' }}" id="ponderacion_objetivo" name="meta_objetivo" |value="{{old('ponderacion_objetivo')}}"required>
                                <label class="form-label" for="meta_objetivo">
                                    Meta
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('meta_objetivo'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('meta_objetivo') }}
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
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@foreach ($objetivos as $objetivo)

    <div class="modal fade" id="add_in{{$objetivo->id}}" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-primary py-4">
                    <h3 class="text-white">
                        <i class="fa-solid fa-plus-circle me-2"></i>
                        Indicadores Disponibles
                    </h3>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body py-4">

                    <!-- (opcional) buscador -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <input type="search" id="buscadorIndicadores" class="form-control form-control-lg" placeholder="Buscar indicador...">
                        </div>
                    </div>

                    <form action="{{ route('add.indicador.objetivo', $objetivo->id) }}" method="POST" id="form{{ $objetivo->id }}">
                        @csrf

                        <div class="row justify-content-around" id="contenedor_indicadores">
                            <h3>Indicadores</h3>

                            @forelse ($indicadores as $indicador)

                                <div class="col-3 m-1 p-3 item-indicador"
                                    data-nombre="{{ strtolower($indicador->nombre) }}">

                                    <input type="checkbox"
                                        name="indicadores[]"
                                        value="{{ $indicador->id }}"
                                        class="btn-check indicador-checkbox"
                                        id="_indicador{{ $objetivo->id }}_{{ $indicador->id }}"
                                        autocomplete="off"
                                        {{ $indicador->id_objetivo_perspectiva != null ? 'disabled' : '' }}>

                                    <label class="btn btn-outline-primary custom-check text-start w-100 h-100"
                                        for="_indicador{{ $objetivo->id }}_{{ $indicador->id }}">

                                        <!-- NOMBRE -->
                                        <div class="text-center  fw-bold">
                                            {{ $indicador->nombre }}  
                                        </div>
                                        <div class="text-  fw-bold">
                                            {{ $indicador->departamento->nombre }}
                                        </div>

                                        <!-- ID -->
                                        <div class="mb-2">
                                            @php
                                            $tipos = [
                                                "g" => "<i class='fa-solid fa-city'></i> Indicador General",
                                                "p" => "<i class='fa-solid fa-cow'></i> Pecuarios",
                                                "m" => "<i class='fa-solid fa-dog'></i> Mascotas",
                                            ];
                                            @endphp

                                            {!!  
                                                empty($indicador->planta)
                                                    ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                                    : ($tipos[strtolower($indicador->planta)] 
                                                        ?? "<i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                            !!}
                                        </div>

                                        <!-- ESTADO -->
                                        <div>
                                            @if($indicador->id_objetivo_perspectiva != null)
                                                <span class="badge bg-danger w-100">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    Ya asignado
                                                </span>
                                            @else
                                                <span class="badge bg-success w-100">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                    Disponible
                                                </span>
                                            @endif
                                        </div>

                                    </label>

                                </div>

                            @empty
                                <div class="col-12 text-center">
                                    No hay indicadores
                                </div>
                            @endforelse

                                
                            <h3>Encuestas</h3>
                            @forelse ($encuestas as $encuesta)
                                   <div class="col-3 m-1 p-3 item-indicador"
                                        data-nombre="{{ strtolower($encuesta->nombre) }}">

                                        <input type="checkbox"
                                            name="encuestas[]"
                                            value="{{ $encuesta->id }}"
                                            class="btn-check"
                                            id="enc{{ $objetivo->id }}_{{ $encuesta->id }}"
                                            autocomplete="off"
                                            {{ $encuesta->id_objetivo_perspectiva != null ? 'disabled' : '' }}>

                                        <label class="btn btn-outline-primary custom-check text-start w-100 h-100"
                                            for="enc{{ $objetivo->id }}_{{ $encuesta->id }}">

                                            <!-- NOMBRE -->
                                            <div class="text-center  fw-bold">
                                                {{ $encuesta->nombre }}  
                                            </div>
                                            <div class="text-  fw-bold">
                                                {{ $encuesta->departamento->nombre }}
                                            </div>

                                            <!-- ID -->
                                            <div class="mb-2">
                                                @php
                                                $tipos = [
                                                    "g" => "<i class='fa-solid fa-city'></i> Indicador General",
                                                    "p" => "<i class='fa-solid fa-cow'></i> Pecuarios",
                                                    "m" => "<i class='fa-solid fa-dog'></i> Mascotas",
                                                ];
                                                @endphp

                                                {!!  
                                                    empty($encuesta->planta)
                                                        ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                                        : ($tipos[strtolower($encuesta->planta)] 
                                                            ?? "<i class='fa-solid fa-industry'></i> Planta {$encuesta->planta}")
                                                !!}
                                            </div>

                                            <!-- ESTADO -->
                                            <div>
                                                @if($encuesta->id_objetivo_perspectiva != null)
                                                    <span class="badge bg-danger w-100">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                        Ya asignado
                                                    </span>
                                                @else
                                                    <span class="badge bg-success w-100">
                                                        <i class="fa-regular fa-circle-check"></i>
                                                        Disponible
                                                    </span>
                                                @endif
                                            </div>

                                        </label>

                                    </div>
                            @empty
                                <div class="col-12 text-center">
                                    No hay Encuestas
                                </div>                                
                            @endforelse


                            <h3>Normas</h3>
                            @forelse ($normas as $norma)
                                   <div class="col-3 m-1 p-3 item-indicador"
                                        data-nombre="{{ strtolower($norma->nombre) }}">

                                        <input type="checkbox"
                                            name="normas[]"
                                            value="{{ $norma->id }}"
                                            class="btn-check"
                                            id="nom{{ $norma->id }}_{{ $objetivo->id }}"
                                            autocomplete="off"
                                            {{ $norma->id_objetivo_perspectiva != null ? 'disabled' : '' }}>

                                        <label class="btn btn-outline-primary custom-check text-start w-100 h-100"
                                            for="nom{{ $norma->id }}_{{ $objetivo->id }}">

                                            <!-- NOMBRE -->
                                            <div class="text-center  fw-bold">
                                                {{ $norma->nombre }}  
                                            </div>
                                            <div class="text-  fw-bold">
                                                {{ $norma->departamento->nombre }}
                                            </div>

                                            <!-- ID -->
                                            <div class="mb-2">
                                                @php
                                                $tipos = [
                                                    "g" => "<i class='fa-solid fa-city'></i> Indicador General",
                                                    "p" => "<i class='fa-solid fa-cow'></i> Pecuarios",
                                                    "m" => "<i class='fa-solid fa-dog'></i> Mascotas",
                                                ];
                                                @endphp

                                                {!!  
                                                    empty($norma->planta)
                                                        ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                                        : ($tipos[strtolower($encuesta->planta)] 
                                                            ?? "<i class='fa-solid fa-industry'></i> Planta {$encuesta->planta}")
                                                !!}
                                            </div>

                                            <!-- ESTADO -->
                                            <div>
                                                @if($norma->id_objetivo_perspectiva != null)
                                                    <span class="badge bg-danger w-100">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                        Ya asignado
                                                    </span>
                                                @else
                                                    <span class="badge bg-success w-100">
                                                        <i class="fa-regular fa-circle-check"></i>
                                                        Disponible
                                                    </span>
                                                @endif
                                            </div>

                                        </label>

                                    </div>
                            @empty
                                <div class="col-12 text-center">
                                    No hay Encuestas
                                </div>                                
                            @endforelse


                        </div>




                    </form>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button form="form{{ $objetivo->id }}" type="submit" class="btn btn-primary w-100 py-3">
                        <h6>Guardar selección</h6>
                    </button>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="del_ob{{$objetivo->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$objetivo->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarDepartamentoLabel{{$objetivo->id}}">
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
                        <h6 class="fw-semibold">¿Estás seguro de eliminar esta perspectiva?</h6>
                        <p class="text-muted mb-0">
                            <strong>{{$objetivo->nombre}}</strong>
                        </p>

                        <small class="text-muted d-block">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>

                    <form action="{{route('objetivo.delete', $objetivo->id)}}" method="POST">
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


    <div class="modal fade" id="edit_ob{{$objetivo->id}}" tabindex="-1" aria-labelledby="editarDepartamentoLabel{{$objetivo->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarDepartamentoLabel{{$objetivo->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Objetivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('objetivo.update', $objetivo->id)}}" method="POST">
                        @csrf 
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"  class="form-control {{ $errors->first('nombre_objetivo_edit') ? 'is-invalid' : '' }}"  id="nombre_dep{{$objetivo->id}}"  name="nombre_objetivo_edit"  value="{{old('nombre_objetivo_edit', $objetivo->nombre)}}" required>
                                    <label class="form-label" for="nombre_edit{{$objetivo->id}}">
                                        Nombre Perspectiva
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_objetivo_edit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_objetivo_edit') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" min="1" max="100"  class="form-control {{ $errors->first('ponderacion_objetivo_edit') ? 'is-invalid' : '' }}"  id="ponderacion{{$objetivo->id}}"  name="ponderacion_objetivo_edit"  value="{{old('ponderacion_objetivo_edit', $objetivo->ponderacion)}}" required>
                                        <label class="form-label" for="ponderacion{{$objetivo->id}}">
                                            Ponderación del Objetivo
                                            <span class="text-danger">*</span>
                                        </label>
                                        @if ($errors->first('ponderacion_objetivo_edit'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('ponderacion_objetivo_edit') }}
                                            </div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" min="1" max="100"  class="form-control {{ $errors->first('meta_objetivo_edit') ? 'is-invalid' : '' }}"  id="meta{{$objetivo->id}}"  name="meta_objetivo_edit"  value="{{old('meta_objetivo_edit', $objetivo->meta)}}" required>
                                        <label class="form-label" for="meta{{$objetivo->id}}">
                                            Meta del Objetivo
                                            <span class="text-danger">*</span>
                                        </label>
                                        @if ($errors->first('meta_objetivo_edit'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('meta_objetivo_edit') }}
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

@endsection


@section('scripts')


    
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {

        checkbox.addEventListener("change", function (e) {

            if (checkbox.disabled) {
                e.preventDefault();

                toastr.warning("Este indicador ya está ocupado y no se puede seleccionar.");

                // Revertir estado por si acaso
                checkbox.checked = false;
            }

        });

    });

});
</script>




<script>
document.getElementById('buscadorIndicadores')
.addEventListener('keyup', function () {

    
    let filtro = this.value.toLowerCase();
    let contenedores = document.querySelectorAll('.item-indicador');
   

    console.log()
    
    contenedores.forEach(function (contenedor) {

        let nombre = contenedor.dataset.nombre || '';

        

        if (nombre.includes(filtro)) {
            contenedor.style.display = "";
        } else {
            contenedor.style.display = "none";
        }

    });
});
</script>




<script>
const elementos = document.querySelectorAll('.cumplimiento_objetivo');

let suma = 0;

elementos.forEach(el => {
    // Obtener texto (ej: "85.50%")
    let texto = el.textContent;

    // Limpiar % y comas
    texto = texto.replace('%', '').replace(',', '');

    // Convertir a número
    let numero = parseFloat(texto);

    if (!isNaN(numero)) {
        suma += numero;
    }
});

// Mostrar resultado
document.getElementById('suma_cumplimientos').textContent = suma.toFixed(2) + '%';

let card_cumplimiento = document.getElementById('card_cumplimiento');
let icono_card_cumplimiento = document.getElementById('icono_card_cumplimiento');

if(suma < 80){
    card_cumplimiento.classList.add('bg-danger');
    icono_card_cumplimiento.classList.add("fa-xmark-circle");

}
else{
    card_cumplimiento.classList.add('bg-succes');
    icono_card_cumplimiento.classList.add("fa-check-circle");

}

</script>


@endsection

