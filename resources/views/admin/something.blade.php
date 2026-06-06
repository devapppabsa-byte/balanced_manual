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


             