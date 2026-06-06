@extends('plantilla')
@section('title', 'Perspectivas')
@section('contenido')
@php
    use App\Models\Indicador;
    use App\Models\IndicadorLleno;
    use App\Models\Encuesta;
    use App\Models\Norma;
    use Carbon\Carbon;
@endphp
    
<div class="container-fluid sticky-top">
    <div class="row bg-primary  d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h3 class="mt-1 mb-0 league-spartan">Perspectivas</h3>
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
            <div class="d-flex flex-wrap align-items-end gap-3">
                
                <form action="#" method="GET" class="d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="form-label small text-muted fw-semibold mb-1">Desde</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-calendar-days text-primary"></i>
                            </span>
                            <input type="date"
                                name="fecha_inicio"
                                value="{{ request('fecha_inicio') ?? '2025-01-01' }}"
                                class="form-control border-0 bg-light datepicker"
                                onchange="this.form.submit()">
                        </div>
                    </div>

                    <div>
                        <label class="form-label small text-muted fw-semibold mb-1">Hasta</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-calendar-days text-danger"></i>
                            </span>
                            <input type="date"
                                name="fecha_fin"
                                value="{{ request('fecha_fin') ?? now()->format('Y-m-d') }}"
                                class="form-control border-0 bg-light datepicker"
                                onchange="this.form.submit()">
                        </div>
                    </div>
                </form>

                <div class="ms-auto">
                    <a class="btn btn-primary" 
                       data-mdb-ripple-init 
                       data-mdb-modal-init 
                       data-mdb-target="#agregar_perspectiva">
                        <i class="fa-solid fa-plus-circle me-2"></i>
                        Agregar Perspectivas
                    </a>                   
                </div>
            </div>

            <div class="text-muted mb-0 mt-3">
                @php
                    $ponderacion_total = collect($perspectivas)->sum('ponderacion');
                @endphp
                <div class="badge {{ $ponderacion_total != 100 ? 'badge-danger' : 'badge-success' }}">
                    <i class="fa fa-exclamation-circle me-1"></i>
                    La suma de las ponderaciones de las Perspectivas es: <b>{{ $ponderacion_total }}%</b>
                </div>
            </div>
        </div>
    </div>
</div>





</div>




<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-10 col-md-9 col-lg-6 mb-4">
            <div class="card bg-success p-4 text-white text-center" id="card_cumplimiento">
                <h3>
                   <i class="fa " id="icono_card_cumplimiento"></i> Cumplimiento
                </h3>
                
                <h2 id="suma_cumplimientos"></h2>
            </div>
        </div>
    </div>
</div>


{{-- @foreach ($perspectivas as $perspectiva)
<h3>Cumplimiento {{ $perspectiva->cumplimiento }}%</h3>

<h3 class="cumplimiento_objetivo">
   Aporte {{ $perspectiva->aporte }}%
</h3>
@endforeach --}}


{{-- <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">

            
                <!-- Departamentos Grid -->
            <div class="row justify-content-center d-flex align-items-start">
                @forelse ($perspectivas as $perspectiva)
                @php
                    $total_final_cumplimiento_perspectiva = 0
                @endphp

                
                    <div class="col-12 col-sm-11 col-md-10 col-lg-6 col-xl-6">
                        <div class="card border-0 w-100 shadow-sm h-100 department-card my-4">
                            <div class="card-body  d-flex flex-column row">
                                

                                <!-- Nombre del Departamento -->
                                <div class="col-12 mb-4">
                                    <a href="{{ route('detalle.perspectiva', $perspectiva->id) }}" 
                                    class="text-decoration-none text-dark d-block p-2 rounded hover-shadow-sm">
                                        
                                        <h2 class="h1 fw-bold mb-1 department-name zalando">
                                            {{ $perspectiva->nombre }}
                                        </h2>
                                        
                                        <p class="text-muted mb-0">
                                            <span class="fw-semibold">Ponderación:</span> 
                                            {{ $perspectiva->ponderacion }}%
                                        </p>
                                    </a>
                                </div>
                                


                                <div class="row">
                                        @forelse ($perspectiva->objetivos as $objetivo)

                                            @php
                                                $total_cumplimiento_perspectiva = 0;
                                                $total_cumplimiento_objetivo = 0;
                                            @endphp

                                            <div class="col-12 mb-3">
                                                <!-- El título ahora es un verdadero encabezado con el tooltip -->
                                                <h4 class="text-truncate fw-bold mb-1" data-mdb-tooltip-init title="{{ $objetivo->nombre }}">
                                                    {{ $objetivo->nombre }}
                                                </h4>
                                                
                                                <!-- Separamos el label del valor para mejor lectura -->
                                                <div class="league-spartan text-muted">
                                                    <span class="fw-bold text-dark">Ponderación Objetivo:</span> 
                                                    <span class="fs-5">{{ $objetivo->ponderacion }}%</span>
                                                </div>
                                            </div>




                                            <div class="col-12  ">                                      
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h2 class="text-center fw-bold border-bottom pb-2">
                                                            <i class="fas fa-chart-line me-2"></i>Indicadores
                                                        </h2>
                                                    </div>
                                                </div>

                                                @php $suma_cumplimientos_indicadores = 0; @endphp

                                                @foreach ($objetivo->indicadores_perspectiva as $indicador)
                                                    @php
                                                        $indicador_lleno = IndicadorLleno::where('final', 'on')
                                                            ->where('id_indicador', $indicador->id)
                                                            ->whereBetween('fecha_periodo', [$inicio, $fin])
                                                            ->pluck('informacion_campo')
                                                            ->toArray();

                                                        $count = count($indicador_lleno);
                                                        $promedio_cumplimiento = 0;

                                                        if ($count > 0) {
                                                            $suma_datos = array_sum($indicador_lleno);
                                                            $promedio_simple = $suma_datos / $count;

                                                            if ($indicador->tipo_indicador == "normal") {
                                                                $promedio_cumplimiento = ($indicador->unidad_medida == "porcentaje") 
                                                                    ? $promedio_simple 
                                                                    : ($promedio_simple / $indicador->meta_esperada) * 100;
                                                            } else {
                                                                // Indicador inverso o especial
                                                                $promedio_cumplimiento = ($indicador->unidad_medida == 'porcentaje') 
                                                                    ? $promedio_simple 
                                                                    : ($indicador->meta_esperada / $promedio_simple) * 100;
                                                            }
                                                        }
                                                        
                                                        $aportacion_kpi = ($promedio_cumplimiento * $indicador->ponderacion_indicador) / 100;
                                                        $suma_cumplimientos_indicadores += $aportacion_kpi;
                                                    @endphp

                                                    <!-- Card de Indicador Individual -->
                                                    <div class="card shadow-0 border mb-4">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <a href="{{ route('analizar.indicador', $indicador->id) }}" target="_blank" class="h5 fw-bold text-primary mb-0">
                                                                    <i class="fas fa-external-link-alt fa-xs me-1"></i> {{ $indicador->nombre }}
                                                                </a>
                                                                <span class="badge badge-primary">Ponderación: {{ $indicador->ponderacion_indicador }}%</span>
                                                            </div>

                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <div class="p-2 text-center rounded-3 bg-light border">
                                                                        <h3 class="mb-0 fw-bold">{{ number_format($promedio_cumplimiento, 2) }}%</h3>
                                                                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Cumplimiento Promedio</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="p-2 text-center rounded-3 bg-primary text-white shadow-sm">
                                                                        <h3 class="mb-0 fw-bold">{{ number_format($aportacion_kpi, 2) }}%</h3>
                                                                        <small class="text-white-50 text-uppercase d-block" style="font-size: 10px;">vs Ponderación</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <!-- Resumen Total del Objetivo -->
                                                <div class="card bg-dark text-white shadow-lg zoom mt-4">
                                                    <div class="card-body text-center">
                                                        <h4 class="fw-bold text-uppercase small opacity-75">Total aportado por los KPI</h4>
                                                        <hr class="hr-light">
                                                        <div class="row">
                                                            <div class="col-6 border-end">
                                                                <h2 class="display-6 fw-bold">{{ number_format($suma_cumplimientos_indicadores, 2) }}%</h2>
                                                                <small class="d-block text-info">Al Objetivo</small>
                                                                @php $total_cumplimiento_objetivo += $suma_cumplimientos_indicadores; @endphp
                                                            </div>
                                                            <div class="col-6">
                                                                @php 
                                                                    $al_perspectiva = ($suma_cumplimientos_indicadores * $objetivo->ponderacion) / 100;
                                                                    $total_cumplimiento_perspectiva += $al_perspectiva;
                                                                @endphp
                                                                <h2 class="display-6 fw-bold">{{ number_format($al_perspectiva, 2) }}%</h2>
                                                                <small class="d-block text-warning">A la Perspectiva ({{ $objetivo->ponderacion }}%)</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <br>








                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <h2 class="text-center fw-bold">Encuestas</h2>
                                                    </div>
                                                </div>

                                                @php
                                                    $suma_cumplimientos_encuestas = 0;
                                                @endphp

                                                @foreach ($objetivo->encuestas_perspectiva as $encuesta)
                                                    <a href="{{route('encuesta.index', $encuesta->id)}}" target="_blank" class="h5 text-primary fw-bold">{{ $encuesta->nombre }}</a>

                                                    @php
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

                                                        /*
                                                        |--------------------------------------------------------------------------
                                                        | Promedio por semestre
                                                        |--------------------------------------------------------------------------
                                                        */
                                                        $promediosSemestre = collect();

                                                        foreach ($rangos as $rango) {
                                                            $promedio = DB::table('respuestas as r')
                                                                ->join('preguntas as p', 'r.id_pregunta', '=', 'p.id')
                                                                ->where('p.cuantificable', 1)
                                                                ->whereBetween('r.created_at', [
                                                                    $rango['inicio']->utc(),
                                                                    $rango['fin']->utc()
                                                                ])
                                                                ->avg(DB::raw('CAST(r.respuesta AS DECIMAL(10,2))'));

                                                            $promediosSemestre->push((float) ($promedio ?? 0));
                                                        }

                                                        /*
                                                        |--------------------------------------------------------------------------
                                                        | Promedio de semestres
                                                        |--------------------------------------------------------------------------
                                                        */
                                                        $informacion_encuestas = round($promediosSemestre->avg() ?? 0, 2) * 10;

                                                        $aporte_encuesta = ($informacion_encuestas * $encuesta->ponderacion_encuesta) / 100;

                                                        $suma_cumplimientos_encuestas += $aporte_encuesta;
                                                    @endphp

                                                    <div class="row text-center p-3">

                                                        <div class="col-6 p-3 bg-secondary text-white">
                                                            <h1>
                                                                {{ number_format($aporte_encuesta, 2) }} %
                                                            </h1>
                                                            <span class="fw-bold">Cumplimiento del objetivo Encuestas</span>
                                                        </div>

                                                        <div class="col-6 p-3 bg-primary text-white">
                                                            <h1 class="a">
                                                                {{ number_format(($aporte_encuesta * $objetivo->ponderacion) / 100, 2) }} %
                                                            </h1>
                                                            <span class="fw-bold">Aportado a la perspectiva por Encuestas</span>
                                                        </div>

                                                    </div>

                                                @endforeach

                                                <div class="row text-center p-3 mt-2 shadow zoom">
                                                    <div class="col-12 my-2">
                                                        <h3>Total aportado por Encuestas</h3>
                                                    </div>

                                                    <div class="col-6 border text-center p-3 bg-secondary text-white">
                                                        <h1>
                                                            {{ number_format($suma_cumplimientos_encuestas, 2) }} %
                                                            @php
                                                                $total_cumplimiento_objetivo = $total_cumplimiento_objetivo + number_format($suma_cumplimientos_encuestas, 2);
                                                            @endphp
                                                        </h1>
                                                        <span>
                                                            Aportado al Objetivo por Encuestas
                                                        </span>
                                                    </div>

                                                    <div class="col-6 border text-center p-3 bg-primary text-white">
                                                        <h1>
                                                            {{number_format(($suma_cumplimientos_encuestas * $objetivo->ponderacion) / 100, 2) }} %
                                                            @php
                                                                $total_cumplimiento_perspectiva = $total_cumplimiento_perspectiva + number_format(($suma_cumplimientos_encuestas * $objetivo->ponderacion) / 100, 2);
                                                                
                                                            @endphp
                                                        </h1>
                                                        <span>
                                                            Aportado a la Perspectiva por Encuestas
                                                        </span>
                                                    </div>
                                                </div>



                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <h2 class="text-center fw-bold">Normas</h2>
                                                    </div>
                                                </div>

                                                @php
                                                    $suma_cumplimientos_normas = 0;
                                                @endphp

                                                @foreach ($objetivo->normas_perspectiva as $norma)

                                                    <a href="{{route('apartado.norma', $norma->id)}}" target="_blank" class="h4 text-primary" >{{ $norma->nombre }}</a>

                                                    @php
                                                        $inicioMeses = $inicio->copy()->timezone('America/Mexico_City')->startOfMonth();
                                                        $finMeses = $fin->copy()->timezone('America/Mexico_City')->subSecond()->startOfMonth();

                                                        $inicioConsulta = $inicio->copy()->addMonth()->startOfMonth();
                                                        $finConsulta = $fin->copy()->addMonth()->endOfMonth();

                                                        /*
                                                        |--------------------------------------------------------------------------
                                                        | Meses dentro del rango
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
                                                        | Total de apartados de la norma
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
                                                        | Promedio mensual del rango
                                                        |--------------------------------------------------------------------------
                                                        */
                                                        $promedio_cumplimiento = round($porcentajes->avg(), 2);

                                                        $aporte_norma = ($promedio_cumplimiento * $norma->ponderacion_norma) / 100;

                                                        $suma_cumplimientos_normas += $aporte_norma;
                                                    @endphp

                                                    <div class="row text-center p-3">

                                                        <div class="col-6 p-3 bg-primary text-white">
                                                            <h4>
                                                                {{ number_format($promedio_cumplimiento, 2) }} %
                                                            </h4>
                                                            <span class="">Promedio cumplimiento normas</span>
                                                        </div>

                                                        <div class="col-6 p-3 bg-secondary text-white">
                                                            <h4 class="aportado_perspectiva">
                                                                {{ number_format(($promedio_cumplimiento * $norma->ponderacion_norma) / 100, 2) }} %
                                                            </h4>
                                                            <span class="">Promedio vs Ponderación ({{ $norma->ponderacion_norma }}%)</span>
                                                        </div>

                                                    </div>
                                                @endforeach



                                                <div class="row text-center p-3 mt-2 shadow zoom">
                                                    <div class="col-12 my-2">
                                                        <h3>Total aportado por Normas</h3>
                                                    </div>

                                                    <div class="col-6 border text-center p-3 bg-primary text-white">
                                                        <h1>
                                                            {{number_format($suma_cumplimientos_normas, 2) }} %
                                                            @php
                                                                $total_cumplimiento_objetivo = $total_cumplimiento_objetivo + number_format($suma_cumplimientos_normas, 2);
                                                            @endphp
                                                        </h1>
                                                        <span>
                                                            Aportado al Objetivo por Normas
                                                        </span>
                                                    </div>

                                                    <div class="col-6 border text-center p-3 bg-secondary text-white">
                                                        <h1>
                                                            {{number_format(($suma_cumplimientos_normas * $objetivo->ponderacion) / 100, 2) }} %
                                                            @php
                                                                $total_cumplimiento_perspectiva = $total_cumplimiento_perspectiva + number_format(($suma_cumplimientos_normas * $objetivo->ponderacion) / 100, 2);
                      
                                                            @endphp
                                                        </h1>
                                                        <span>
                                                            Aportado a la Perspectiva por Normas
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>



                                            <div class="col-12 text-center my-5 p-5 border border-4">
                                                <div class="row justify-content-center d-flex align-items-center">
                                                    <div class="col-6">
                                                        <h5 class="text-center ">Cumplimiento del objetivo: </h5>
                                                        <h3 class="text-center fw-bold cumplimieto_objetivo">  
                                                        {{ $total_cumplimiento_objetivo }}    
                                                        %</h3>
                                                    </div>
                                                    <div class="col-6 bg-light rounded-5">
                                                        <h5 class="text-center">% Aportado a la perspectiva: </h5>
                                                        <h3 class="text-center fw-bold cumplimiento_perspectiva" 
                                                        data-suma-perspectiva="{{ ($total_cumplimiento_perspectiva  * $perspectiva->ponderacion)/100}}">
                                                            {{ $total_cumplimiento_perspectiva }}%
                                                            @php
                                                                $total_final_cumplimiento_perspectiva = $total_final_cumplimiento_perspectiva + $total_cumplimiento_perspectiva;
                                                            @endphp
                                                        </h3>         
                                     
                                                    </div>
                                                </div>
                                            </div>


                                        @empty
                                            
                                        @endforelse

                                            <div class="col-12 text-center my-5 p-5 border border-4">
                                                <div class="row justify-content-center d-flex align-items-center">
                                                    <div class="col-12 bg-light rounded-5">
                                                        <h5 class="text-center">% Cumplimiento Perspectiva: </h5>
                                                        <h3 class="text-center fw-bold cumplimiento_perspectiva">
                                                            {{ ($total_final_cumplimiento_perspectiva * $perspectiva->ponderacion) / 100}}
                                                            %
                                                        </h3>         
                                     
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Botón Principal -->
                                            <div class=" flex-grow text-center mb-2 col-12">
                                                <a href="{{ route('detalle.perspectiva', $perspectiva->id) }}"
                                                    class=" btn btn-light w-100 fw-semibold">
                                                    <i class="fa-solid fa-magnifying-glass me-2"></i>                                        
                                                    Explorar Perspectiva
                                                </a>
                                            </div> 



                                            <!-- Acciones -->
                                            <div class="d-flex justify-content-end align-items-center pt-2 border-top row ">
                                                <div class="col-6 text-start fw-bold text-muted">
                                                    <small>Ponderación: {{$perspectiva->ponderacion}}%</small>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" data-mdb-tooltip-init title="Editar " data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#edit_pe{{$perspectiva->id}}">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </button>
                
                                                        <button class="btn btn-sm btn-outline-danger" data-mdb-tooltip-init title="Eliminar" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#del_per{{$perspectiva->id}}">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
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
                                <h5 class="text-muted mb-2">No se han registrado perspectivas.</h5>
                                <p class="text-muted mb-4">
                                    <small>Comienza agregando tu primer perspectiva.</small>
                                </p>
                                <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_perspectiva">
                                    <i class="fa-solid fa-plus-circle me-2"></i>
                                    Agregar Perspectiva
                                </button>
                            </div>
                        </div>  
                    @endforelse
             </div>


             
        </div>
    </div>
</div> --}}










<div class="container-fluid">
    <div class="row">
<div class="row justify-content-center d-flex align-items-start g-4">
    @forelse ($perspectivas as $perspectiva)
        @php $total_final_cumplimiento_perspectiva = 0; @endphp

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-5-strong rounded-6 h-100 mb-4 overflow-hidden">
                <!-- Cabecera de la Perspectiva -->
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <a href="{{ route('detalle.perspectiva', $perspectiva->id) }}" class="text-decoration-none">
                            <h2 class="h3 fw-bold text-dark mb-1 hover-primary transition-all">
                                {{ $perspectiva->nombre }}
                            </h2>
                            <span class="badge badge-light text-muted border">
                                <i class="fas fa-layer-group me-1"></i> Ponderación: {{ $perspectiva->ponderacion }}%
                            </span>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-link btn-floating text-muted" type="button" data-mdb-dropdown-init aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-mdb-modal-init data-mdb-target="#edit_pe{{$perspectiva->id}}"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                <li><a class="dropdown-item text-danger" href="#" data-mdb-modal-init data-mdb-target="#del_per{{$perspectiva->id}}"><i class="fas fa-trash me-2"></i>Eliminar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4">
                    @forelse ($perspectiva->objetivos as $objetivo)
                        @php
                            $total_cumplimiento_perspectiva = 0;
                            $total_cumplimiento_objetivo = 0;
                        @endphp

                        <!-- Card de Objetivo -->
                        <div class="card border shadow-0 mb-5 rounded-5 overflow-hidden">
                            <div class="card-header bg-light py-3 border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="h5 fw-bold mb-0 text-truncate" style="max-width: 70%;" data-mdb-tooltip-init title="{{ $objetivo->nombre }}">
                                        {{ $objetivo->nombre }}
                                    </h4>
                                    <span class="badge rounded-pill badge-dark px-3">Obj: {{ $objetivo->ponderacion }}%</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Sección Indicadores -->
                                <div class="mb-4">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-3 letter-spacing-1">
                                        <i class="fas fa-chart-line text-primary me-2"></i>Indicadores
                                    </h6>
                                    
                                    @php $suma_cumplimientos_indicadores = 0; @endphp
                                    @foreach ($objetivo->indicadores_perspectiva as $indicador)
                                        @php
                                            // LOGICA MANTENIDA IGUAL
                                            $indicador_lleno = IndicadorLleno::where('final', 'on')->where('id_indicador', $indicador->id)->whereBetween('fecha_periodo', [$inicio, $fin])->pluck('informacion_campo')->toArray();
                                            $count = count($indicador_lleno);
                                            $promedio_cumplimiento = 0;
                                            if ($count > 0) {
                                                $suma_datos = array_sum($indicador_lleno);
                                                $promedio_simple = $suma_datos / $count;
                                                $promedio_cumplimiento = ($indicador->tipo_indicador == "normal") 
                                                    ? (($indicador->unidad_medida == "porcentaje") ? $promedio_simple : ($promedio_simple / $indicador->meta_esperada) * 100)
                                                    : (($indicador->unidad_medida == 'porcentaje') ? $promedio_simple : ($indicador->meta_esperada / $promedio_simple) * 100);
                                            }
                                            $aportacion_kpi = ($promedio_cumplimiento * $indicador->ponderacion_indicador) / 100;
                                            $suma_cumplimientos_indicadores += $aportacion_kpi;
                                        @endphp

                                        <div class="d-flex align-items-center mb-3 p-2 border-start border-primary border-4 bg-light rounded-end shadow-sm hover-shadow-sm transition-all">
                                            <div class="flex-grow-1 ms-2">
                                                <a href="{{ route('analizar.indicador', $indicador->id) }}" target="_blank" class="fw-bold text-dark text-decoration-none small d-block">
                                                    {{ $indicador->nombre }}
                                                </a>
                                                <small class="text-muted">Pond: {{ $indicador->ponderacion_indicador }}%</small>
                                            </div>
                                            <div class="text-end px-2">
                                                <span class="d-block fw-bold text-primary">{{ number_format($promedio_cumplimiento, 1) }}%</span>
                                                <small class="text-muted" style="font-size: 9px;">APORTE: {{ number_format($aportacion_kpi, 2) }}%</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @php $total_cumplimiento_objetivo += $suma_cumplimientos_indicadores; @endphp
                                    @php $total_cumplimiento_perspectiva += ($suma_cumplimientos_indicadores * $objetivo->ponderacion) / 100; @endphp
                                </div>

                                <!-- Sección Encuestas -->
                                <div class="mb-4">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-3 letter-spacing-1">
                                        <i class="fas fa-poll text-success me-2"></i>Encuestas
                                    </h6>
                                    @php $suma_cumplimientos_encuestas = 0; @endphp
                                    @foreach ($objetivo->encuestas_perspectiva as $encuesta)
                                        @php
                                            // LOGICA MANTENIDA IGUAL
                                            $rangos = [];
                                            if ($inicio->month <= 6) { $rangos[] = ['inicio' => $inicio->copy()->startOfYear()->startOfDay(), 'fin' => $inicio->copy()->month(6)->endOfMonth()->endOfDay()]; }
                                            if ($fin->month >= 7 || $inicio->year !== $fin->year) { $rangos[] = ['inicio' => $fin->copy()->month(7)->startOfMonth()->startOfDay(), 'fin' => $fin->copy()->endOfYear()->endOfDay()]; }
                                            $promediosSemestre = collect();
                                            foreach ($rangos as $rango) {
                                                $promedio = DB::table('respuestas as r')->join('preguntas as p', 'r.id_pregunta', '=', 'p.id')->where('p.cuantificable', 1)->whereBetween('r.created_at', [$rango['inicio']->utc(), $rango['fin']->utc()])->avg(DB::raw('CAST(r.respuesta AS DECIMAL(10,2))'));
                                                $promediosSemestre->push((float) ($promedio ?? 0));
                                            }
                                            $informacion_encuestas = round($promediosSemestre->avg() ?? 0, 2) * 10;
                                            $aporte_encuesta = ($informacion_encuestas * $encuesta->ponderacion_encuesta) / 100;
                                            $suma_cumplimientos_encuestas += $aporte_encuesta;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2 p-2 border-start border-success border-4 bg-light rounded-end shadow-sm">
                                            <div class="flex-grow-1 ms-2">
                                                <a href="{{route('encuesta.index', $encuesta->id)}}" target="_blank" class="fw-bold text-dark text-decoration-none small d-block">{{ $encuesta->nombre }}</a>
                                            </div>
                                            <div class="text-end px-2">
                                                <span class="fw-bold text-success">{{ number_format($aporte_encuesta, 2) }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @php 
                                        $total_cumplimiento_objetivo += $suma_cumplimientos_encuestas;
                                        $total_cumplimiento_perspectiva += ($suma_cumplimientos_encuestas * $objetivo->ponderacion) / 100;
                                    @endphp
                                </div>

                                <!-- Sección Normas -->
                                <div class="mb-4">
                                    <h6 class="text-uppercase fw-bold text-muted small mb-3 letter-spacing-1">
                                        <i class="fas fa-certificate text-warning me-2"></i>Normas
                                    </h6>
                                    @php $suma_cumplimientos_normas = 0; @endphp
                                    @foreach ($objetivo->normas_perspectiva as $norma)
                                        @php
                                            // LOGICA MANTENIDA IGUAL
                                            $inicioMeses = $inicio->copy()->timezone('America/Mexico_City')->startOfMonth();
                                            $finMeses = $fin->copy()->timezone('America/Mexico_City')->subSecond()->startOfMonth();
                                            $inicioConsulta = $inicio->copy()->addMonth()->startOfMonth();
                                            $finConsulta = $fin->copy()->addMonth()->endOfMonth();
                                            $meses = collect(); $cursor = $inicioMeses->copy();
                                            while ($cursor <= $finMeses) { $meses->push($cursor->format('Y-m')); $cursor->addMonth(); }
                                            $total_apartados = DB::table('apartado_norma')->where('id_norma', $norma->id)->count();
                                            $porMes = DB::table('apartado_norma as an')->leftJoin('cumplimiento_norma as cn', function ($join) use ($inicioConsulta, $finConsulta) { $join->on('cn.id_apartado_norma', '=', 'an.id')->whereBetween('cn.created_at', [$inicioConsulta, $finConsulta]); })->where('an.id_norma', $norma->id)->whereNotNull('cn.created_at')->select(DB::raw("DATE_FORMAT(DATE_SUB(cn.created_at, INTERVAL 1 MONTH), '%Y-%m') as mes"), DB::raw('COUNT(DISTINCT cn.id_apartado_norma) as cumplidos'))->groupBy(DB::raw("DATE_FORMAT(DATE_SUB(cn.created_at, INTERVAL 1 MONTH), '%Y-%m')"))->pluck('cumplidos', 'mes');
                                            $porcentajes = $meses->map(function ($mes) use ($porMes, $total_apartados) { $cumplidos = $porMes[$mes] ?? 0; return ($total_apartados == 0) ? 0 : ($cumplidos / $total_apartados) * 100; });
                                            $promedio_cumplimiento_n = round($porcentajes->avg(), 2);
                                            $aporte_norma = ($promedio_cumplimiento_n * $norma->ponderacion_norma) / 100;
                                            $suma_cumplimientos_normas += $aporte_norma;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2 p-2 border-start border-warning border-4 bg-light rounded-end shadow-sm">
                                            <div class="flex-grow-1 ms-2">
                                                <a href="{{route('apartado.norma', $norma->id)}}" target="_blank" class="fw-bold text-dark text-decoration-none small d-block">{{ $norma->nombre }}</a>
                                            </div>
                                            <div class="text-end px-2">
                                                <span class="fw-bold text-warning">{{ number_format($aporte_norma, 2) }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @php 
                                        $total_cumplimiento_objetivo += $suma_cumplimientos_normas;
                                        $total_cumplimiento_perspectiva += ($suma_cumplimientos_normas * $objetivo->ponderacion) / 100;
                                    @endphp
                                </div>
                            </div>

                            <!-- Footer del Objetivo con Totales -->
                            <div class="card-footer bg-dark border-0 p-3">
                                <div class="row text-center text-white g-0">
                                    <div class="col-6 border-end border-light border-opacity-10">
                                        <small class="d-block opacity-75 small text-uppercase">Meta Obj.</small>
                                        <span class="h5 mb-0 fw-bold cumplimieto_objetivo">{{ number_format($total_cumplimiento_objetivo, 2) }}%</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="d-block opacity-75 small text-uppercase">Aportado Persp.</small>
                                        <span class="h5 mb-0 fw-bold cumplimiento_perspectiva" data-suma-perspectiva="{{ ($total_cumplimiento_perspectiva * $perspectiva->ponderacion)/100 }}">
                                            {{ number_format($total_cumplimiento_perspectiva, 2) }}%
                                        </span>
                                    </div>
                                </div>
                                @php $total_final_cumplimiento_perspectiva += $total_cumplimiento_perspectiva; @endphp
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted my-4">No hay objetivos asignados.</p>
                    @endforelse
                </div>

                <!-- Resumen Final de la Perspectiva -->
                <div class="card-footer bg-primary p-4 border-0">

                    <div class="row">

                        <div class="col-6">
                            <div class="text-center text-white">
                                <p class="mb-1 text-uppercase letter-spacing-1 small opacity-80">Aportado a la Perspectiva</p>
                                <h2 class="display-5 fw-bold mb-0">
                                    {{ number_format($total_final_cumplimiento_perspectiva, 2) }}%
                                </h2>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center text-white">
                                <p class="mb-1 text-uppercase letter-spacing-1 small opacity-80">Aportado al Cumplimiento General</p>
                                <h2 class="display-5 fw-bold mb-0">
                                    {{ number_format(($total_final_cumplimiento_perspectiva * $perspectiva->ponderacion) / 100, 2) }}%
                                </h2>
                            </div>
                        </div>


                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('detalle.perspectiva', $perspectiva->id) }}" class="btn btn-white btn-rounded w-100 shadow-0 fw-bold text-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Ver 
                        </a>
                    </div>


                </div>
            </div>
        </div>
    @empty
        <!-- Empty State Mejorado -->
        <div class="col-md-8">
            <div class="card border-0 shadow-5-strong text-center py-5 rounded-6">
                <div class="card-body">
                    <div class="bg-light d-inline-block p-4 rounded-circle mb-4">
                        <i class="fa-solid fa-folder-open text-muted fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">Sin perspectivas registradas</h3>
                    <p class="text-muted mb-4">La estrategia está esperando. Comienza por definir tu primera perspectiva de negocio.</p>
                    <button class="btn btn-primary btn-lg  px-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_perspectiva">
                        <i class="fa-solid fa-plus-circle me-2"></i>Nueva Perspectiva
                    </button>
                </div>
            </div>
        </div>
    @endforelse
</div>

<style>
    .rounded-6 { border-radius: 1.5rem !important; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .hover-shadow-sm:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05) !important; transform: translateY(-2px); }
    .transition-all { transition: all 0.3s ease; }
    .hover-primary:hover { color: #1266f1 !important; }
    .btn-white { background-color: #fff; color: #1266f1; }
    .btn-white:hover { background-color: #f8f9fa; }
</style>
    </div>
</div>









<div class="modal fade" id="agregar_perspectiva" tabindex="-1" aria-labelledby="agregarDepartamentoLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="agregarDepartamentoLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>
                    Agregar Perspectiva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{ route('perspectiva.store') }}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text" class="form-control {{ $errors->first('nombre_perspectiva') ? 'is-invalid' : '' }}" id="nombre_perspectiva" name="nombre_perspectiva" |value="{{old('nombre_perspectiva')}}"required>
                                <label class="form-label" for="nombre_perspectiva">
                                    Perspectiva
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('nombre_perspectiva'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre_perspectiva') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number" min="1" max="100" class="form-control {{ $errors->first('ponderacion') ? 'is-invalid' : '' }}" id="ponderacion" name="ponderacion" |value="{{old('ponderacion')}}"required>
                                <label class="form-label" for="ponderacion">
                                    Ponderación
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($errors->first('ponderacion'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('ponderacion') }}
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


{{-- aqui va a ir el ciclo de la edicion y eliminacion de las perspectivas --}}
@foreach ($perspectivas as $perspectiva)
    <div class="modal fade" id="del_per{{$perspectiva->id}}" tabindex="-1" aria-labelledby="eliminarDepartamentoLabel{{$perspectiva->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="eliminarDepartamentoLabel{{$perspectiva->id}}">
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
                            <strong>{{$perspectiva->nombre}}</strong>
                        </p>

                        <small class="text-muted d-block">
                            Esta acción no se puede deshacer.
                        </small>
                    </div>
                    <form action="{{route('eliminar.perspectiva', $perspectiva->id)}}" method="POST">
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




    <div class="modal fade" id="edit_pe{{$perspectiva->id}}" tabindex="-1" aria-labelledby="editarDepartamentoLabel{{$perspectiva->id}}" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="editarDepartamentoLabel{{$perspectiva->id}}">
                        <i class="fa-solid fa-edit me-2"></i>
                        Editar Perspectiva
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('edit.perspectiva', $perspectiva->id)}}" method="POST">
                        @csrf 
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"  class="form-control {{ $errors->first('nombre_departamento') ? 'is-invalid' : '' }}"  id="nombre_dep{{$perspectiva->id}}"  name="nombre_perspectiva"  value="{{old('nombre_perspectiva', $perspectiva->nombre)}}" required>
                                    <label class="form-label" for="nombre_pers{{$perspectiva->id}}">
                                        Nombre Perspectiva
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('nombre_perspectiva'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('nombre_perspectiva') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="number" min="1" max="100"  class="form-control {{ $errors->first('ponderacion_perspectiva') ? 'is-invalid' : '' }}"  id="ponderacion{{$perspectiva->id}}"  name="ponderacion_perspectiva"  value="{{old('ponderacion_perspectiva', $perspectiva->ponderacion)}}" required>
                                    <label class="form-label" for="ponderacion{{$perspectiva->id}}">
                                        Ponderación de la perspectiva
                                        <span class="text-danger">*</span>
                                    </label>
                                    @if ($errors->first('ponderacion_perspectiva'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('ponderacion_perspectiva') }}
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
document.addEventListener('DOMContentLoaded', function () {
    let suma = 0;
    const icono = document.getElementById('icono_card_cumplimiento');
    const card = document.getElementById('card_cumplimiento')

    document.querySelectorAll('[data-suma-perspectiva]').forEach(function (el) {
        suma += parseFloat(el.getAttribute('data-suma-perspectiva')) || 0;
    });

    document.getElementById('suma_cumplimientos').textContent = suma.toFixed(2)+'%';


    if(suma < 80 ){
        icono.classList.add("fa-xmark-circle");
        card.classList.add("bg-danger");
    }

    else{
        icono.classList.add("fa-check-circle");
        card.classList.add("bg-succes");
    }
});
</script>

@endsection