@extends('plantilla')
@section('title', 'Detalle del Indicador')
@section('contenido')
@php
    use Carbon\Carbon;
    use App\Models\InformacionInputPrecargado;
    use App\Models\MetaIndicador;
@endphp
<div class="container-fluid sticky-top ">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h1 class="text-white league-spartan">{{$indicador->nombre}}</h1>

            @if (session('success'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif

            @if (session('actualizado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('actualizado')}}
                </div>
            @endif

            @if (session('eliminado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('eliminado')}}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-white  fw-bold p-2 rounded">
                    <i class="fa fa-xmark-circle mx-2  text-danger"></i>
                        No se agrego! <br>
                    <i class="fa fa-exclamation-circle mx-2  text-danger"></i>
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

                <form action="#" method="GET" id="filtro_analisis_datos">
                    <div class="d-flex flex-wrap align-items-end gap-3">

                        <!-- Fecha inicio -->
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

                        <!-- Fecha fin -->
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

                        <div>
                    
                            <label class="form-label small text-muted fw-semibold mb-1">
                                <i class="fa fa-calendar"></i>
                                Selecciona un mes para mostrar</label>
                            <select class="form-select form-select-sm fw-bold" form="filtro_analisis_datos"  name="mostrar_mes" onchange="this.form.submit()">
                                <option value="" disabled {{ request('mostrar_mes') ? '' : 'selected' }}>
                                    Selecciona un mes.
                                </option>
                                @foreach ($fechas_seleccionar as $fecha)
                                    <option value="{{ $fecha }}" {{ request('mostrar_mes') == $fecha ? 'selected' : '' }}>
                                        {{Carbon::parse($fecha)->translatedFormat('F Y') }}
                                    </option>
                                @endforeach
                                
                            </select>
        
                        </div>

                        <div>
                    
                            <label class="form-label small text-muted fw-semibold mb-1">
                                <i class="fa fa-table"></i>
                                Selecciona un campo para graficar</label>
                            <select class="form-select form-select-sm fw-bold" form="filtro_analisis_datos" name="campos_a_graficar" onchange="this.form.submit()">
                                <option value="" disabled {{ request('campos_a_graficar') ? '' : 'selected' }}>
                                    Selecciona un campo a graficar.
                                </option>

                                @forelse ($campos_graficar as $campo)
                                    @if ($campo != 'Registro' && $campo != "comentario")
                                        <option value="{{ $campo }}"
                                            {{ request('campos_a_graficar') == $campo ? 'selected' : '' }}>
                                            {{ $campo }}
                                        </option>
                                    @endif
                                @empty
                                    <option value="">No hay datos</option>
                                @endforelse
                            </select>
        
                        </div>



                    </div>
                    {{-- <button type="submit" id="btn-trigger-form" style="display: none;"></button> --}}
                </form>

            </div>
        </div>
    </div>
</div>


{{-- Datos de los indicadores, solo el numerito final y se agregara un modal  para ver los detalles --}}

@php
$semaforo = "";

$valor = $ultimo_mes->informacion_campo;
$meta  = $indicador->meta_esperada;
$var   = $indicador->meta_minima; // tu variación


if($indicador->variacion === "on"){

    $min = $meta - $var;
    $max = $meta + $var;

    if($valor >= $min && $valor <= $max){
        $semaforo = "bg-success";
    } else {
        $semaforo = "bg-danger";
    }
}

elseif($indicador->tipo_indicador == "riesgo"){

    if($valor >= $meta){
        $semaforo = "bg-danger";
    } else {
        $semaforo = "bg-success";
    }
}


else{

    if($valor >= $meta){
        $semaforo = "bg-success";
    } else {
        $semaforo = "bg-danger";
    }
}
@endphp



<!-- Modal -->
@if ($campos_llenos != "personalizado")
<div class="modal fade" id="campos_indicador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Datos del mes de 
            {{ request('mostrar_mes') ? Carbon::parse(request('mostrar_mes'))->translatedFormat('F Y')  : Carbon::parse($ultimo_mes->fecha_periodo)->translatedFormat('F Y')   }}</h5>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
            $registro = "";
            $nombre_registro = "";
        @endphp
        @forelse ($campos_llenos as $campo_lleno)
        <div class="row p-2 justify-content-center">
            @if ($campo_lleno->nombre_campo == 'comentario')
            <div class="col-12 bg-light table-responsive ck-content">
                <i class="fa-solid fa-circle-info"></i>Información Extra
                <p>
                    {!!  $campo_lleno->informacion_campo !!}
                </p>
            </div>

            @elseif( $campo_lleno->nombre_campo == "Registro")
            @php
                $nombre_registro = $campo_lleno->nombre_campo;
                $registro = $campo_lleno->informacion_campo;
            @endphp
            @else
                @if ($campo_lleno->final == "on")
                    
                @else
                    
               
                <div class="col-10 border boder-3 p-2 ">
                    <h5>
                        {{ $campo_lleno->nombre_campo }}
                    </h5>
                    <h6>

                        @if($campo_lleno->unidad_medida === 'pesos')
                            <div class="format-number">
                                $ {{ number_format($campo_lleno->informacion_campo, 2) }}
                            </div>

                        @elseif($campo_lleno->unidad_medida === 'porcentaje')
                            <div class="format-number">
                                {{ number_format($campo_lleno->informacion_campo, 2) }} %
                            </div>

                        @elseif($campo_lleno->unidad_medida === 'dias')
                            <div class="format-number">
                                {{ number_format($campo_lleno->informacion_campo, 2) }} Días
                            </div>

                        @elseif($campo_lleno->unidad_medida === 'toneladas')
                            <div class="format-number">
                                {{ number_format($campo_lleno->informacion_campo, 2) }} Ton.
                            </div>

                        @else
                            <div class="format-number">
                                {{ round($campo_lleno->informacion_campo, 2) }}
                            </div>
                        @endif 

                    </h6>
                </div>
             @endif
            @endif

        </div>

        @empty
            
        @endforelse
      </div>
      <div class="modal-footer bg-primary text-white">
        <div class="row  w-100 d-flex align-items-center">
            <div class="col-6 text-left ">
                <small class="fw-bold">
                    <i class="fa fa-edit"></i>
                    {{ $nombre_registro }}
                </small>
                <span>
                    {{ $registro }}
                </span>
            </div>
            <div class="col-6 text-end ">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
{{-- aqui esta la tarjeta que se muestra el campo seleccionado --}}

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row justify-content-center">
                <div class="col-3  text-center my-1 ">
                    <h5 class="py-3 text-dark bg-white p-0 rounded-pill fw-bolder">
                       <i class="fa-solid fa-bullseye text-danger"></i>
                        {{ ($indicador->tipo_indicador == 'normal') ? 'Meta' : 'Limite'  }}
                        @if($indicador->unidad_medida === 'pesos')
                            $ {{ $indicador->meta_esperada }}
                        @elseif($indicador->unidad_medida === 'porcentaje')
                            {{ $indicador->meta_esperada }}%
                        @elseif($indicador->unidad_medida === 'dias')
                            {{ $indicador->meta_esperada }} Días
                        @elseif($indicador->unidad_medida === 'toneladas')
                            {{ $indicador->meta_esperada }} Ton.
                        @else
                            {{ $indicador->meta_esperada }}
                        @endif

                    </h5>
                </div>
            </div>
        </div>
        <div class="col-8 {{ $ultimo_mes->id_movimiento }}">
            <button type="button"
                    class="w-100 border-0 bg-transparent p-0"
                    data-mdb-ripple-init
                    data-mdb-modal-init
                    data-mdb-target="#campos_indicador">

                <div class="card {{ $semaforo }}">
                    <div class="card-body py-1">
                        <h4 class="text-center fw-bold text-white">
                            {{ $ultimo_mes->nombre_campo }}
                        </h4>

                        <h1 class="text-center fw-bold text-white ">
                            @if($indicador->unidad_medida === 'pesos')
                                ${{ number_format($ultimo_mes->informacion_campo, 2) }}
                            @elseif($indicador->unidad_medida === 'porcentaje')
                                {{ round($ultimo_mes->informacion_campo, 2) }}%
                            @elseif($indicador->unidad_medida === 'dias')
                                {{ round($ultimo_mes->informacion_campo, 2) }} Días
                            @elseif($indicador->unidad_medida === 'toneladas')
                                {{ round($ultimo_mes->informacion_campo, 2) }} Ton.
                            @else
                                {{ round($ultimo_mes->informacion_campo, 2) }}
                            @endif
                        </h1>

                        <h5 class="text-center fw-bold text-white text-capitalize">
                            {{ Carbon::parse($ultimo_mes->fecha_periodo)->translatedFormat('F Y') }}
                        </h5>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

@else


<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-10">
            <button type="button"
                    class="w-100 border-0 bg-transparent p-0"
                    data-mdb-ripple-init
                    data-mdb-modal-init
                    data-mdb-target="#campos_indicador">

                <div class="card bg-info">
                    <div class="card-body py-1">
                        <h4 class="text-center fw-bold text-white">
                            {{ $ultimo_mes->nombre_campo }}
                        </h4>

                        <h1 class="text-center fw-bold text-white format-number">
                            @if($ultimo_mes->unidad_medida === 'pesos')
                                ${{ number_format($ultimo_mes->informacion_campo, 2) }}
                            @elseif($ultimo_mes->unidad_medida === 'porcentaje')
                                {{ round($ultimo_mes->informacion_campo, 2) }}%
                            @elseif($ultimo_mes->unidad_medida === 'dias')
                                {{ round($ultimo_mes->informacion_campo, 2) }} Días
                            @elseif($ultimo_mes->unidad_medida === 'toneladas')
                                {{ round($ultimo_mes->informacion_campo, 2) }} Ton.
                            @else
                                {{ round($ultimo_mes->informacion_campo, 2) }}
                            @endif
                        </h1>

                        <h5 class="text-center fw-bold text-white text-capitalize">
                            {{ Carbon::parse($ultimo_mes->fecha_periodo)->translatedFormat('F Y') }}
                        </h5>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

@endif
{{-- Datos de los indicadores, solo el numerito final y se agregara un modal  para ver los detalles --}}














<div class="container-fluid">

    @if (isset($resultado['tendencia']))
   

            {{-- @if($resultado['mensaje'])
                <div class="row justify-content-center border me-1">
                    <div class="col-12 bg-white mt-3 p-3 ">
                        <div class="row g-2">
                            <div class="col-12 my-2">
                                <h5 class="fw-bold">
                                    <i class="fa-regular fa-message"></i>
                                    Mensaje:
                                </h5>
                            </div>

                            <div class="col-12">
                                <div class="card-body text-center text-white ">
                                    @if(($resultado['mensaje']))
                                    <div class="alert alert-info py-2 border-dashed border-info h-3">
                                        <i class="fa-solid fa-comment-dots"></i>
                                           <b> {{ $resultado['mensaje'] }} </b> <br> en el periodo seleccionado.
                                    </div>
                                    @endif                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif --}}




    <div class="row justify-content-center px-2">


        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
            <div class="row">

                <div class="col-12 p-1 bg-white mt-3 p-3">
        <!-- BOTÓN + GRAFICA 1 -->
                        {{--esto afecta a todos los nombre de las graficas --}}
                              @php
                                    $tipos = [
                                        "g" => "<i class='fa-solid fa-city'></i> Indicador General",
                                        "p" => "<i class='fa-solid fa-cow'></i> Pecuarios",
                                        "m" => "<i class='fa-solid fa-dog'></i> Mascotas",
                                    ];
                            @endphp

                    <h4>{{ request('campos_a_graficar') ? request('campos_a_graficar') : 'Gráfico de Barras + Linea'  }} |

                        {!!  
                            empty($indicador->planta)
                                ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                : ($tipos[strtolower($indicador->planta)] 
                                    ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                        !!}
                        
                    </h4>
                    <canvas id="grafico"></canvas>

                </div>

                <div class="col-12 p-1 bg-white mt-3 p-3">
                    <h4>{{ request('campos_a_graficar') ? request('campos_a_graficar') : 'Gráfico de Tendencia'  }} |

                        {!!  
                            empty($indicador->planta)
                                ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                : ($tipos[strtolower($indicador->planta)] 
                                    ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                        !!}
                        
                    </h4>
                    <canvas id="graficoLine"></canvas>
                </div>

                <div class="col-12 p-1 bg-white mt-3 p-3">

                    <h4>{{ request('campos_a_graficar') ? request('campos_a_graficar') : 'Gráfico de Dona'  }} |

                        {!!  
                            empty($indicador->planta)
                                ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                : ($tipos[strtolower($indicador->planta)] 
                                    ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                        !!}
                        
                    </h4>
                    <canvas id="graficoPie"></canvas>
                </div>

            </div>
        </div>


        <div class="col-12 col-sm-12 col-md-9 col-lg-4 ">


            <div class="row justify-content-center border ms-1">
                <div class="col-12 bg-white mt-3 p-3 shadow-sm">
                    <div class="row">
                        <div class="col-12 my-2">
                            <h6 class="fw-bold">
                                <i class="fa-solid fa-calendar"></i>
                                Promedios Anuales
                            </h6>
                            <span>
                            {{ request('campos_a_graficar') ? request('campos_a_graficar') : ''  }}                                
                                {!!  
                                    empty($indicador->planta)
                                        ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                        : ($tipos[strtolower($indicador->planta)] 
                                            ?? "<br> <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                !!}                                
                            </span>
                        </div>

                            @forelse ($promedios as $promedio)
                                <div class="col-12 col-sm-6 col-md-5 col-lg-4 mt-1">
                                    <div class="card shadow-sm bg-info text-white border-0 h-100 text-center">
                                        <div class="card-body  py-3">
                                            <span class="text-white d-block mb-1">
                                                <i class="fa-solid fa-calendar-days"></i>                                            
                                                Promedio Anual
        
                                            </span>

                                            <h5 class="fw-bold mb-2">
                                                @if (empty($campo_graficar) )

                                                    @if($indicador->unidad_medida === 'pesos')
                                                        $ {{ number_format($promedio->promedio, 2) }}

                                                    @elseif($indicador->unidad_medida === 'porcentaje')
                                                        {{ round($promedio->promedio, 2) }} %

                                                    @elseif($indicador->unidad_medida === 'dias')
                                                        {{ round($promedio->promedio, 2) }} Días

                                                    @elseif($indicador->unidad_medida === 'toneladas')
                                                        {{ round($promedio->promedio, 2) }} Ton.

                                                    @else
                                                        {{ round($promedio->promedio, 2) }}
                                                    @endif
                                                    
                                                @else

                                                    @if($datos_campo_graficar->unidad_medida === 'pesos')
                                                        $ {{ number_format($promedio->promedio, 2) }}

                                                    @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                                        {{ round($promedio->promedio, 2) }} %

                                                    @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                                        {{ round($promedio->promedio, 2) }} Días

                                                    @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                                        {{ round($promedio->promedio, 2) }} Ton.

                                                    @else
                                                        {{ round($promedio->promedio, 2) }}
                                                    @endif                                             
                                                                                                
                                                @endif

                                            </h5>

                                            <h5>
                                                <span class="badge bg-light text-dark badge-lg">
                                                       Año: {{ $promedio->anio }}                                                 
                                                </span>                                                 
                                            </h5>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <div class="alert alert-light border">
                                        <h5 class="mb-0 text-muted">
                                            <i class="fa fa-exclamation-circle"></i>
                                            No hay datos que mostrar
                                        </h5>
                                    </div>
                                </div>
                            @endforelse

                    </div>
                </div>
            </div>

            <div class="row  mt-1 mb-1 ms-1">
                <div class="col-12 bg-white mt-3 p-3 shadow-sm">
                    <div class="row">
                        <div class="col-12 my-2">
                            <h5 class="fw-bold">
                                <i class="fa fa-list"></i>
                                Historial:
                            </h5>
                            <span>
                            {{ request('campos_a_graficar') ? request('campos_a_graficar') : ''  }}                                
                                {!!  
                                    empty($indicador->planta)
                                        ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                        : ($tipos[strtolower($indicador->planta)] 
                                            ?? "<br> <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                !!}                                
                            </span>
                        </div>

                    
                    @forelse ($info_meses as $info_mes)
                    <div class="col-12 col-sm-5 col-md-5 col-lg-3 shadow-sm mx-1  mb-1 p-2 bg-white ">
                        <span class="fw-bold">
                            <i class="fa {{ ($loop->first ? 'fa-location-dot text-primary' : 'fa-calendar') }} "></i>
                            {{ Carbon::parse($info_mes->fecha_periodo)->translatedFormat('F Y') }} 
                        </span>
                        
                        <br>
                        
                        <span class="format-number">

                            @if (empty($campo_graficar))
                                @if($indicador->unidad_medida === 'pesos')
                                $ {{ number_format($info_mes->informacion_campo, 2) }}
                                
                                @elseif($indicador->unidad_medida === 'porcentaje')
                                {{ round($info_mes->informacion_campo, 2) }} %
                                
                                @elseif($indicador->unidad_medida === 'dias')
                                {{ round($info_mes->informacion_campo, 2) }} Días
                                
                                @elseif($indicador->unidad_medida === 'toneladas')
                                {{ round($info_mes->informacion_campo, 2) }} Ton.
                                
                                @else
                                {{ round($info_mes->informacion_campo, 2) }}
                                @endif 

                            @else


                                @if($datos_campo_graficar->unidad_medida === 'pesos')
                                    $ {{ number_format($info_mes->informacion_campo, 2) }}

                                @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                     {{ round($info_mes->informacion_campo, 2) }} %

                                @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                    {{ round($info_mes->informacion_campo, 2) }} Días

                                @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                    {{ round($info_mes->informacion_campo, 2) }} Ton.

                                @else
                                   {{ round($info_mes->informacion_campo, 2) }}
                                @endif  


                            @endif
                            
                        </span>
                        
                    </div>
                    
                    @empty
                    
                    @endforelse
                    </div>
                </div>
            </div>


            <div class="row justify-content-center border ms-1">
                <div class="col-12 bg-white mt-3 p-3 shadow-sm">
                    <div class="row g-2">
                        <div class="col-12 my-2">
                            <h5 class="fw-bold">
                                <i class="fa-solid fa-up-down"></i>
                                Mejor y Peor mes:
                            </h5>
                            <span>
                            {{ request('campos_a_graficar') ? request('campos_a_graficar') : ''  }}                                
                                {!!  
                                    empty($indicador->planta)
                                        ? "<i class='fa-solid fa-circle-exclamation'></i> Sin asignación"
                                        : ($tipos[strtolower($indicador->planta)] 
                                            ?? "<br> <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                !!}                                
                            </span>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="card bg-success">
                                <div class="card-body text-center text-white">
                                    <span class="h4 fw-bold">
                                        <i class="fa fa-calendar"></i>
                                        {{ $mejor_mes["mes"] }}  {{ $mejor_mes["anio"] }}
                                    </span> <br>
                                    <span class="h3 fw-bold">

                                        @if (empty($campo_graficar))
                                            @if($indicador->unidad_medida === 'pesos')
                                                $ {{ number_format($mejor_mes["valor"], 2) }}

                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                {{ number_format($mejor_mes["valor"], 2) }} %

                                            @elseif($indicador->unidad_medida === 'dias')
                                                {{ number_format($mejor_mes["valor"], 2) }} Días

                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                {{ number_format($mejor_mes["valor"], 2) }} Ton.

                                            @else
                                                {{ number_format($mejor_mes["valor"], 2) }}
                                            @endif                                        
                                        @else

                                            @if($datos_campo_graficar->unidad_medida === 'pesos')
                                               $ {{ number_format($mejor_mes["valor"], 2) }}

                                            @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                               {{ number_format($mejor_mes["valor"], 2) }} %

                                            @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                               {{ number_format($mejor_mes["valor"], 2) }} Días

                                            @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                                {{ number_format($mejor_mes["valor"], 2) }} Ton.

                                            @else
                                                {{ number_format($mejor_mes["valor"], 2) }}
                                            @endif                                             
                                            
                                             
                                        @endif
                                        
                                    </span>
                                </div>
                
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="card bg-danger">
                                <div class="card-body text-center text-white">
                                    <span class="h4 fw-bold">
                                        <i class="fa fa-calendar"></i>
                                        {{ $peor_mes["mes"] }}  {{ $peor_mes["anio"] }}
                                    </span> <br>
                                    <span class="h3 fw-bold">
                                        @if (empty($campo_graficar))
                                            @if($indicador->unidad_medida === 'pesos')
                                                $ {{ number_format($peor_mes["valor"], 2) }}

                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                {{ number_format($peor_mes["valor"], 2) }} %

                                            @elseif($indicador->unidad_medida === 'dias')
                                                {{ number_format($peor_mes["valor"], 2) }} Días

                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                {{ number_format($peor_mes["valor"], 2) }} Ton.

                                            @else
                                                {{ number_format($peor_mes["valor"], 2) }}
                                            @endif  

                                        @else

                                            @if($datos_campo_graficar->unidad_medida === 'pesos')
                                              $ {{ number_format($peor_mes["valor"], 2) }}

                                            @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                               {{ number_format($peor_mes["valor"], 2) }} %

                                            @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                               {{ number_format($peor_mes["valor"], 2) }} Días

                                            @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                               {{ number_format($peor_mes["valor"], 2) }} Ton.

                                            @else
                                                {{ number_format($peor_mes["valor"], 2) }}
                                            @endif   


                                        @endif

                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>




            <div class="row  mt-2 mb-4 ms-1">
                <div class="col-12 bg-white mt-1 p-3 shadow-sm">
                    <div class="row">
                        <div class="col-12 my-2">
                            <h5 class="fw-bold">
                                <i class="fa-solid fa-scale-balanced"></i>
                                Estacionalidad:
                            </h5>
                        </div>
                            <div class="col-12">
                                <div class="row">
                                    @foreach($historico as $item)
                                        @php
                                            $valor = round($item['valor'], 2);
                                            $prev = $item['valor_anterior'];
                                            $dif = $item['diferencia'];

                                        if ($indicador->tipo_indicador === "riesgo"){
                                    
                                            if(is_null($dif)){
                                                $color = 'secondary';
                                                $icon = 'fa-minus';
                                            } elseif($dif < 0){
                                                $color = 'success';
                                                $icon = 'fa-arrow-down';
                                            } elseif($dif > 0){
                                                $color = 'danger';
                                                $icon = 'fa-arrow-up';
                                            } else {
                                                $color = 'info';
                                                $icon = 'fa-equals';
                                            }
                                        }

                                        if($indicador->tipo_indicador === "normal"){

                                            if(is_null($dif)){
                                                $color = 'secondary';
                                                $icon = 'fa-minus';
                                            } elseif($dif > 0){
                                                $color = 'success';
                                                $icon = 'fa-arrow-up';
                                            } elseif($dif < 0){
                                                $color = 'danger';
                                                $icon = 'fa-arrow-down';
                                            } else {
                                                $color = 'info';
                                                $icon = 'fa-equals';
                                            }


                                        }
                                        


                                        @endphp

                                        @if (!is_null($prev))

                                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
                                                <div class="card shadow-3 border-0 h-100">

                                                    <!-- HEADER -->
                                                    <div class="card-header bg-dark text-white text-center">
                                                        <h5 class="mb-0 text-capitalize">
                                                            {{ $item['mes'] }}
                                                        </h5>
                                                        <span class="fw-bold">{{ $item['anio'] }}</span>
                                                    </div>

                                                    <!-- BODY -->
                                                    <div class="card-body text-center">

                                                        <!-- Valor actual -->
                                                        <h3 class="fw-bold format-number">

                                                            @if (empty($campo_graficar))
                                                                @if($indicador->unidad_medida === 'pesos')
                                                                    ${{ number_format($valor, 2) }}

                                                                @elseif($indicador->unidad_medida === 'porcentaje')
                                                                    {{ round($valor, 2) }}%

                                                                @elseif($indicador->unidad_medida === 'dias')
                                                                    {{ round($valor, 2) }} Días

                                                                @elseif($indicador->unidad_medida === 'toneladas')
                                                                    {{ round($valor, 2) }} Ton.

                                                                @else
                                                                    {{ round($valor, 2) }}
                                                                @endif
                                                                
                                                            @else

                                                                @if($datos_campo_graficar->unidad_medida === 'pesos')
                                                                    ${{ number_format($valor, 2) }}

                                                                @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                                                    {{ round($valor, 2) }}%

                                                                @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                                                    {{ round($valor, 2) }} Días

                                                                @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                                                    {{ round($info_mes->informacion_campo, 2) }} Ton.

                                                                @else
                                                                    {{ round($info_mes->informacion_campo, 2) }}
                                                                @endif                                  


                                                            @endif



                                                        </h3>

                                                        <!-- Valor anterior -->
                                                        @if(!is_null($prev))
                                                            <span class="cascadia-code d-block">
                                                                Año anterior {{ $item['anio']-1 }}: 
                                                                <h4>
                                                                    <div class="badge badge-secondary format-number">
                                                                        @if (empty($campo_graficar))
                                                                            @if($indicador->unidad_medida === 'pesos')
                                                                                ${{ number_format($prev, 2) }}

                                                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                                                {{ round($prev, 2) }}%

                                                                            @elseif($indicador->unidad_medida === 'dias')
                                                                                {{ round($prev, 2) }} Días

                                                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                                                {{ round($prev, 2) }} Ton.

                                                                            @else
                                                                                {{ round($prev, 2) }}
                                                                            @endif

                                                                            
                                                                        @else


                                                                            @if($datos_campo_graficar->unidad_medida === 'pesos')
                                                                                ${{ number_format($prev, 2) }}

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                                                                {{ round($prev, 2) }}%

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                                                                {{ round($prev, 2) }} Días

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                                                                {{ round($prev, 2) }} Ton.

                                                                            @else
                                                                                {{ round($prev, 2) }}
                                                                            @endif 

                                                                            
                                                                        @endif

                                                                    </div> 
                                                                </h4>
                                                            </span>
                                                        @endif

                                                        <!-- Diferencia -->
                                                        @if(!is_null($dif))

                                                            <div class="mt-2">
                                                                <span class="badge bg-{{ $color }} fs-6 format-number">
                                                                    <i class="fa-solid {{ $icon }} me-1"></i>
                                                                    
                                                                        @if (empty($campo_graficar))
                                                                        
                                                                            @if($indicador->unidad_medida === 'pesos')
                                                                                ${{ $dif > 0 ? '+ ' : '' }}{{ $dif }}

                                                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }} %

                                                                            @elseif($indicador->unidad_medida === 'dias')
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }} Días

                                                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                                                {{ $dif > 0 ? '+' : '' }}{{ $dif }} Ton.

                                                                            @else
                                                                            {{ $dif > 0 ? '+ ' : '' }}{{ $dif }}
                                                                            @endif

                                                                        @else

                                                                    
            
                                                                            @if($datos_campo_graficar->unidad_medida === 'pesos')
                                                                                $ {{ $dif > 0 ? '+ ' : '' }}{{ $dif }}

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'porcentaje')
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }} %

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'dias')
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }} Días

                                                                            @elseif($datos_campo_graficar->unidad_medida === 'toneladas')
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }} Ton.

                                                                            @else
                                                                                {{ $dif > 0 ? '+ ' : '' }}{{ $dif }}
                                                                            @endif                                                                        


                                                                         
                                                                        @endif
                                                                                                                                
                                                                </span>
                                                            </div>

                                                        @else
                                                            <span class="badge bg-secondary mt-2">
                                                                Sin histórico
                                                            </span>
                                                        @endif

                                                    </div>

                                                </div>
                                            </div>
                                            
                                        @endif

                                    @endforeach
                                </div>      
                            </div>                    
                        </div>
                    </div>
             </div>
        </div>



        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
            <div class="row justify-content-center border ms-1">

                {{-- TENDENCIA --}}
                <div class="col-12 bg-white mt-3 p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Tendencia</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalTendencia">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <span class="badge bg-info me-1">{{ $resultado['tendencia'] }}</span>
                            <span class="badge bg-dark">{{ $resultado['fuerza_tendencia'] }}</span>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Indica la dirección del indicador y qué tan confiable es la tendencia según su comportamiento.
                        </small>

                    </div>
                </div>

                {{-- CAMBIO --}}
                <div class="col-12 bg-white p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Cambio</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalCambio">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2 fw-bold">
                            {{ number_format($resultado['cambio'], 2) }}
                            <span class="text-muted">
                                ({{ number_format($resultado['cambio_porcentual'], 2) }}%)
                            </span>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Diferencia entre el valor inicial y el actual en el periodo analizado.
                        </small>

                    </div>
                </div>

                {{-- ESTADO --}}
                <div class="col-12 bg-white p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Estado actual</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalEstado">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <span class="badge {{ $resultado['cumplimiento'] == 'en meta' ? 'bg-success' : 'bg-danger' }}">
                                {{ $resultado['cumplimiento'] }}
                            </span>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Evalúa si el valor más reciente cumple con la meta establecida.
                        </small>

                    </div>
                </div>

                {{-- HISTORICO --}}
                <div class="col-12 bg-white p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Histórico</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalHistorico">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <span class="badge bg-secondary">{{ $resultado['estado_historico'] }}</span>
                            <span class="text-muted ms-1">
                                ({{ number_format($resultado['porcentaje_cumplimiento'], 0) }}%)
                            </span>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Muestra qué tan frecuentemente el indicador ha cumplido la meta en el tiempo.
                        </small>

                    </div>
                </div>

                {{-- ESTABILIDAD --}}
                {{-- <div class="col-6 col-sm-4 col-md-3 col-lg-2 bg-white p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Estabilidad</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalEstabilidad">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <span class="badge bg-warning text-dark">{{ $resultado['estabilidad'] }}</span>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Indica qué tanto varía el indicador; valores altos implican mayor fluctuación.
                        </small>

                    </div>
                </div> --}}


                {{-- PROYECCION --}}
                <div class="col-12 bg-white p-3">
                    <div class="p-2 border rounded h-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Proyección siguiente</strong>
                            <button class="btn btn-sm btn-light p-1" data-bs-toggle="modal" data-bs-target="#modalProyeccion">
                                <i class="fa fa-circle-info"></i>
                            </button>
                        </div>

                        <div class="mt-2 fw-bold">
                            @if($indicador->unidad_medida === 'pesos')
                                ${{ number_format($resultado['proyeccion_siguiente'], 2) }}

                            @elseif($indicador->unidad_medida === 'porcentaje')
                                {{ number_format($resultado['proyeccion_siguiente'], 2) }}%

                            @elseif($indicador->unidad_medida === 'dias')
                                {{ number_format($resultado['proyeccion_siguiente'], 2) }} Días

                            @elseif($indicador->unidad_medida === 'toneladas')
                                {{ number_format($resultado['proyeccion_siguiente'], 2) }} Ton.

                            @else
                                {{ number_format($resultado['proyeccion_siguiente'], 2) }}
                            @endif

                        </div>

                        <small class="text-muted d-block mt-2">
                            Estimación del próximo valor basada en la tendencia actual del indicador.
                        </small>

                    </div>
                </div>

            </div>

        </div>

        @else

        <div class="row justify-content-center">
            <div class="col-10 bg-white p-4 mt-4">
                <h1 class="text-center my-4">
                    <i class="fa fa-exclamation-circle text-danger"></i>
                    No hay suficientes datos para analizar
                </h1>
            </div>
        </div>

        @endif

    </div>
</div>


@endsection




@section('scripts')




<script>
document.addEventListener("DOMContentLoaded", function () {

    const indicador = @json($indicador);
    const datos = @json($graficar);
    const TIPO_INDICADOR = "{{ $indicador->tipo_indicador }}";
    const MODO_DINAMICO = !datos.some(d => d.final === "on" ); //|| d.referencia === "on"
    
    const mesesES = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    if (!datos || datos.length === 0) return;

    const datosFinal = MODO_DINAMICO 
        ? datos 
        : datos.filter(d => d.final === "on");

    const datosReferencia = MODO_DINAMICO 
        ? [] 
        : datos.filter(d => d.referencia === "on");


    const labels = [...new Set(
        datosFinal.map(item => {
            const fecha = new Date(item.fecha_periodo);
            return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}`;
        })
    )];



    const VARIACION_ON = "{{ $indicador->variacion }}" === "on";

    const META_MINIMA = {{ $indicador->meta_minima }};
    const META_ESPERADA = {{ $indicador->meta_esperada }};

    const VARIACION = VARIACION_ON ? META_MINIMA : null;

    const LIMITE_INFERIOR = VARIACION_ON ? META_ESPERADA - VARIACION : null;
    const LIMITE_SUPERIOR = VARIACION_ON ? META_ESPERADA + VARIACION : null;

    const UNIDAD_MEDIDA = "{{ $indicador->unidad_medida }}";
    const UNIDAD_MEDIDA_CAMPO   = "{{ $datos_campo_graficar->unidad_medida  }}"

    // ============================
    // DATASET PRINCIPAL (BARRAS)
    // ============================

    const datasetFinal = {
        type: "bar",
        label: datosFinal.length > 0 ? datosFinal[0].nombre_campo : "Cumplimiento",

        data: labels.map(label => {
            const item = datosFinal.find(d => {
                const fecha = new Date(d.fecha_periodo);
                return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}` === label;
            });
            return item ? parseFloat(item.informacion_campo) : null;
        }),

        backgroundColor: ctx => {
            const value = ctx.raw;

            if (value === null) return "rgba(200,200,200,0.3)";


            if (MODO_DINAMICO) {
                return "rgba(84, 180, 211, 1)";
            }

            if (VARIACION_ON) {
                if (value < LIMITE_INFERIOR || value > LIMITE_SUPERIOR) {
                    return "rgba(255,99,132,0.8)";
                }
                return "rgba(75,192,75,0.8)";
            }

            if (TIPO_INDICADOR === "riesgo") {
                return value < META_MINIMA
                    ? "rgba(75,192,75,0.8)"
                    : "rgba(255,99,132,0.8)";
            }

            return value < META_MINIMA
                ? "rgba(255,99,132,0.8)"
                : "rgba(75,192,75,0.8)";
        },

        borderWidth: 1,
        order: 1,

        datalabels: {
           anchor: function(context) {
        const value = context.dataset.data[context.dataIndex];
        const meta = context.chart.getDatasetMeta(context.datasetIndex);
        const bar = meta.data[context.dataIndex];
        const height = bar ? bar.height : 0;

        // Si la barra mide menos de 40px de alto, ponemos el texto arriba (afuera)
        return (height < 40) ? 'end' : 'center';
        },

        align: function(context) {
            const meta = context.chart.getDatasetMeta(context.datasetIndex);
            const bar = meta.data[context.dataIndex];
            const height = bar ? bar.height : 0;

            // Si la barra es baja, el texto va sobre ella (top)
            return (height < 40) ? 'top' : 'center';
        },

        rotation: function(context) {
            const meta = context.chart.getDatasetMeta(context.datasetIndex);
            const bar = meta.data[context.dataIndex];
            const height = bar ? bar.height : 0;

            // Solo rotamos si la barra es lo suficientemente alta para que quepa el texto
            return (height < 40) ? 0 : -90;
        },

        clamp: true,
        clip: false, // Importante: false para que el texto no desaparezca si sale de la barra

        color: function(context) {
            const meta = context.chart.getDatasetMeta(context.datasetIndex);
            const bar = meta.data[context.dataIndex];
            const height = bar ? bar.height : 0;

            // Negro si está afuera, Blanco si está adentro
            return (height < 40) ? '#000' : '#fff';
        },

        font: function(context) {
            const meta = context.chart.getDatasetMeta(context.datasetIndex);
            const bar = meta.data[context.dataIndex];
            const height = bar ? bar.height : 0;

            // FIJAMOS tamaños constantes. 
            // No bajamos de 11px nunca para que siempre sea legible.
            let size = 20; 
            if (height < 40) {
                size = 20; // Tamaño para valores pequeños o ceros
            }

            return {
                weight: 'bold',
                size: size
            };
        },

            
            formatter: function(value) {
                if (value === null) return '';

                if (MODO_DINAMICO) {

                    switch (UNIDAD_MEDIDA_CAMPO) {
                        case 'pesos':
                            return '$ ' + Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 });
                        case 'porcentaje':
                            return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' %';
                        case 'dias':
                            return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Días';
                        case 'toneladas':
                            return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + '   Ton.';
                        default:
                            return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 });
                    }

                    return Number(value).toLocaleString('es-MX');

                }

                switch (UNIDAD_MEDIDA) {
                    case 'pesos':
                        return '$ ' + Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 });
                    case 'porcentaje':
                        return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' %';
                    case 'dias':
                        return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Días';
                    case 'toneladas':
                        return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Ton.';
                    default:
                        return Number(value).toLocaleString('es-MX', { maximumFractionDigits:2 });
                }
            }
        }

    };

    // ============================
    // REFERENCIAS (LÍNEAS)
    // ============================

    const referenciasAgrupadas = {};

    datosReferencia.forEach(item => {
        const fecha = new Date(item.fecha_periodo);
        const label = `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}`;

        if (!referenciasAgrupadas[item.nombre_campo]) {
            referenciasAgrupadas[item.nombre_campo] = {};
        }

        referenciasAgrupadas[item.nombre_campo][label] =
            parseFloat(item.informacion_campo);
    });

    const datasetsReferencias = Object.keys(referenciasAgrupadas).map((nombre, index) => ({
        type: "line",
        label: nombre,
        data: labels.map(label => referenciasAgrupadas[nombre][label] ?? null),
        borderWidth: 3,
        tension: 0.1,
        fill: false,
        borderColor: `rgba(${50 + index * 60}, 120, 255, 1)`,
        spanGaps: true,
        order: 10
    }));

    // ============================
    // CANVAS
    // ============================

    const canvas = document.getElementById("grafico");
    if (!canvas) return;

    // 🔥 EVITAR DUPLICACIÓN
    if (window.miGrafica) {
        window.miGrafica.destroy();
    }

    // ============================
    // CREAR GRÁFICA
    // ============================

    window.miGrafica = new Chart(canvas.getContext("2d"), {

        data: {
            labels,
datasets: [
    datasetFinal,

    // SOLO agregar líneas si NO es dinámico
    ...(!MODO_DINAMICO ? [
        ...datasetsReferencias,

        ...(VARIACION_ON ? [
            {
                type: "line",
                label: "Meta esperada",
                data: labels.map(() => META_ESPERADA),
                borderColor: "green",
                borderWidth: 3,
                order: 10
            },
            {
                type: "line",
                label: "Variación inferior",
                data: labels.map(() => LIMITE_INFERIOR),
                borderColor: "red",
                borderWidth: 2,
                borderDash: [6, 6],
                order: 10
            },
            {
                type: "line",
                label: "Variación superior",
                data: labels.map(() => LIMITE_SUPERIOR),
                borderColor: "red",
                borderWidth: 2,
                borderDash: [6, 6],
                order: 10
            }
        ] : [
            {
                type: "line",
                label: "Meta mínima",
                data: labels.map(() => META_MINIMA),
                borderColor: "red",
                borderWidth: 2,
                borderDash: [6, 6],
                order: 10
            },
            {
                type: "line",
                label: "Meta máxima",
                data: labels.map(() => META_ESPERADA),
                borderColor: "green",
                borderWidth: 3,
                order: 10
            }
        ])
    ] : [])
]
        },

        options: {
            responsive: true,

            plugins: {
                legend: {
                    display: true,
                    labels: {
                        filter: function(item, chart) {

                            const dataset = chart.datasets[item.datasetIndex];

                            return dataset.type !== 'bar'; 
                        }
                    }
                },
                datalabels: {
                    display: context => context.dataset.type === 'bar',
                    
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },

        plugins: [ChartDataLabels]

    });

});
</script>





{{-- los otros graficos  --}}

<script>
document.addEventListener("DOMContentLoaded", function () {

    const indicador = @json($indicador);
    const datos = @json($graficar);

    const TIPO_INDICADOR = "{{ $indicador->tipo_indicador }}";
    const UNIDAD_MEDIDA = "{{ $indicador->unidad_medida }}";
    const UNIDAD_MEDIDA_CAMPO   = "{{ $datos_campo_graficar->unidad_medida }}"

    const MODO_DINAMICO = !datos.some(d => d.final === "on");

    const mesesES = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    if (!datos || datos.length === 0) return;

    // ============================
    // FILTRAR
    // ============================

    const datosFinal = MODO_DINAMICO 
        ? datos 
        : datos.filter(d => d.final === "on");

    const todosDatos = [...datosFinal];

    const labels = [...new Set(
        todosDatos.map(item => {
            const fecha = new Date(item.fecha_periodo);
            return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}`;
        })
    )];

    // ============================
    // METAS
    // ============================

    const VARIACION_ON = "{{ $indicador->variacion }}" === "on";

    const META_MINIMA = {{ $indicador->meta_minima ?? 0 }};
    const META_ESPERADA = {{ $indicador->meta_esperada ?? 0 }};

    const VARIACION = VARIACION_ON ? META_MINIMA : null;

    const LIMITE_INFERIOR = VARIACION_ON ? META_ESPERADA - VARIACION : null;
    const LIMITE_SUPERIOR = VARIACION_ON ? META_ESPERADA + VARIACION : null;

    // ============================
    // DATA
    // ============================

    const dataValores = labels.map(label => {
        const item = datosFinal.find(d => {
            const fecha = new Date(d.fecha_periodo);
            return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}` === label;
        });
        return item ? parseFloat(item.informacion_campo) : null;
    });

    const nombreCampo = datosFinal.length > 0
        ? datosFinal[0].nombre_campo
        : "Indicador";

    // ============================
    // COLOR
    // ============================

    function obtenerColor(valor) {

        if (valor === null) return "rgba(200,200,200,0.3)";

        if (MODO_DINAMICO) {
             return "rgba(84, 180, 211, 1)";
        }

        if (VARIACION_ON) {
            if (valor < LIMITE_INFERIOR || valor > LIMITE_SUPERIOR) {
                return "rgba(255,99,132,0.8)";
            }
            return "rgba(75,192,75,0.8)";
        }

        if (TIPO_INDICADOR === "riesgo") {
            return valor < META_MINIMA
                ? "rgba(75,192,75,0.8)"
                : "rgba(255,99,132,0.8)";
        }

        return valor < META_MINIMA
            ? "rgba(255,99,132,0.8)"
            : "rgba(75,192,75,0.8)";
    }

    // ============================
    // FORMATO
    // ============================

    function formatear(valor) {

        if (valor === null) return '';

        if (MODO_DINAMICO) {

            switch (UNIDAD_MEDIDA_CAMPO) {
                case 'pesos':
                    return '$ ' + Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 });
                case 'porcentaje':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' %';
                case 'dias':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Días';
                case 'toneladas':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Ton.';
                default:
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 });
            }

            return Number(valor).toLocaleString('es-MX');

        }

        else{
            
            switch (UNIDAD_MEDIDA) {
                case 'pesos':
                    return '$ ' + Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 });
                case 'porcentaje':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' %';
                case 'dias':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Días';
                case 'toneladas':
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 }) + ' Ton.';
                default:
                    return Number(valor).toLocaleString('es-MX', { maximumFractionDigits:2 });
            }

        }

    }

    const ctxLine = document.getElementById("graficoLine");

    if (!ctxLine) return;

    if (window.miGraficaLine) {
        window.miGraficaLine.destroy();
    }

    window.miGraficaLine = new Chart(ctxLine.getContext("2d"), {

        data: {
            labels,
            datasets: [
                {
                    type: "line",
                    label: nombreCampo,
                    data: dataValores,
                    borderWidth: 3,
                    tension: 0.2,
                    fill: true,
                    backgroundColor:"rgba(54, 162, 235, .15)",
                    borderColor: MODO_DINAMICO 
                        ? "rgba(84, 180, 211, 1)" 
                        : "rgba(143, 19, 176, .8)",
                    pointBackgroundColor: ctx => obtenerColor(ctx.raw),
                    pointRadius: 6
                },

                ...(!MODO_DINAMICO ? (VARIACION_ON ? [
                    {
                        type: "line",
                        label: "Meta esperada",
                        data: labels.map(() => META_ESPERADA),
                        borderColor: "green",
                        borderWidth: 3
                    },
                    {
                        type: "line",
                        label: "Variación inferior",
                        data: labels.map(() => LIMITE_INFERIOR),
                        borderColor: "red",
                        borderDash: [6,6]
                    },
                    {
                        type: "line",
                        label: "Variación superior",
                        data: labels.map(() => LIMITE_SUPERIOR),
                        borderColor: "red",
                        borderDash: [6,6]
                    }
                ] : [
                    {
                        type: "line",
                        label: "Meta mínima",
                        data: labels.map(() => META_MINIMA),
                        borderColor: "red",
                        borderDash: [6,6]
                    },
                    {
                        type: "line",
                        label: "Meta máxima",
                        data: labels.map(() => META_ESPERADA),
                        borderColor: "green"
                    }
                ]) : [])
            ]
        },

        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    display: !MODO_DINAMICO
                },
            datalabels: {


                display: (ctx) => ctx.datasetIndex === 0,

                formatter: (value, ctx) => formatear(value),

                color: '#000',

                backgroundColor: 'rgba(255,255,255,0.8)',
                borderRadius: 4,
                padding: 6,

                borderColor: 'rgba(0,0,0,0.2)',
                borderWidth: 1,

                font: { weight: 'bold', size: 14 }
            }
            }
        },

        plugins: [ChartDataLabels]
    });

});
</script>



<script>
document.addEventListener("DOMContentLoaded", function () {

    window.indicador = @json($indicador);
    window.datos = @json($graficar);

    window.TIPO_INDICADOR = "{{ $indicador->tipo_indicador }}";
    window.UNIDAD_MEDIDA = "{{ $indicador->unidad_medida }}";

    window.MODO_DINAMICO = !datos.some(d => d.final === "on");
    


    if (!datos || datos.length === 0) return;

    window.mesesES = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    window.datosFinal = MODO_DINAMICO 
        ? datos 
        : datos.filter(d => d.final === "on");

    window.labels = [...new Set(
        datosFinal.map(item => {
            const fecha = new Date(item.fecha_periodo);
            return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}`;
        })
    )];

    window.VARIACION_ON = "{{ $indicador->variacion }}" === "on";

    window.META_MINIMA = {{ $indicador->meta_minima ?? 0 }};
    window.META_ESPERADA = {{ $indicador->meta_esperada ?? 0 }};

    window.VARIACION = VARIACION_ON ? META_MINIMA : null;

    window.LIMITE_INFERIOR = VARIACION_ON ? META_ESPERADA - VARIACION : null;
    window.LIMITE_SUPERIOR = VARIACION_ON ? META_ESPERADA + VARIACION : null;

    window.dataValores = labels.map(label => {
        const item = datosFinal.find(d => {
            const fecha = new Date(d.fecha_periodo);
            return `${mesesES[fecha.getMonth()]} ${fecha.getFullYear()}` === label;
        });
        return item ? parseFloat(item.informacion_campo) : null;
    });

    window.obtenerColor = function(valor) {

        if (valor === null) return "rgba(200,200,200,0.3)";

        if (VARIACION_ON) {
            if (valor < LIMITE_INFERIOR || valor > LIMITE_SUPERIOR) {
                return "rgba(255,99,132,0.8)";
            }
            return "rgba(75,192,75,0.8)";
        }

        if (TIPO_INDICADOR === "riesgo") {
            return valor < META_MINIMA
                ? "rgba(75,192,75,0.8)"
                : "rgba(255,99,132,0.8)";
        }

        return valor < META_MINIMA
            ? "rgba(255,99,132,0.8)"
            : "rgba(75,192,75,0.8)";
    };

});
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const UNIDAD_MEDIDA_CAMPO   = "{{ $datos_campo_graficar->unidad_medida }}"
    if (!window.datos || window.datos.length === 0) return;

    let dataPie;
    let labelsPie;
    let coloresPie;

    if (MODO_DINAMICO) {

        dataPie = dataValores;
        labelsPie = labels;
        coloresPie = "rgba(84, 180, 211, 1)";

    } else {

        let dentro = 0;
        let fuera = 0;

        dataValores.forEach(v => {
            if (v === null) return;

            if (VARIACION_ON) {
                if (v < LIMITE_INFERIOR || v > LIMITE_SUPERIOR) {
                    fuera++;
                } else {
                    dentro++;
                }
            } else {
                if (TIPO_INDICADOR === "riesgo") {
                    v < META_MINIMA ? dentro++ : fuera++;
                } else {
                    v < META_MINIMA ? fuera++ : dentro++;
                }
            }
        });

        dataPie = [dentro, fuera];
        labelsPie = ["Dentro de meta", "Fuera de meta"];
        coloresPie = ["rgba(75,192,75,0.8)", "rgba(255,99,132,0.8)"];
    }

    const ctxPie = document.getElementById("graficoPie");
    if (!ctxPie) return;

    if (window.miGraficaPie) {
        window.miGraficaPie.destroy();
    }

    window.miGraficaPie = new Chart(ctxPie.getContext("2d"), {

        type: "doughnut",

        data: {
            labels: labelsPie,
            datasets: [{
                data: dataPie,
                backgroundColor: coloresPie
            }]
        },

        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: !MODO_DINAMICO
                },
                datalabels: {
                    formatter: (value, ctx) => {
                   
                        if (!MODO_DINAMICO) {
                            const total = ctx.chart._metasets[0].total;
                            const porcentaje = (value / total * 100).toFixed(1);
                            return porcentaje + "%";
                        }
                        else{
                            const label = ctx.chart.data.labels[ctx.dataIndex];
                            let valorFormateado = Number(value).toLocaleString('es-MX');
    
          
                            if (UNIDAD_MEDIDA_CAMPO === "pesos") {
                                valorFormateado = "$" + valorFormateado;
                            } else if (UNIDAD_MEDIDA_CAMPO === "porcentaje") {
                                valorFormateado = valorFormateado + "%";
                            } else if (UNIDAD_MEDIDA_CAMPO === "dias") {
                                valorFormateado = valorFormateado + " días";
                            } else if (UNIDAD_MEDIDA_CAMPO) {
                                valorFormateado = valorFormateado + " " + UNIDAD_MEDIDA_CAMPO;
                            }
                            return label + '\n' + valorFormateado;

                        }


                    },
                    color: '#000000',
                    font: { weight: 'bold', size: 12 }
                }
            }
        },

        plugins: [ChartDataLabels]
    });

});
</script>



{{-- Aqui yacen os scripts de la estacionalidad --}}





@endsection