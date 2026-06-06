@extends('plantilla')
@section('title', 'Detalle del Indicador')
@section('contenido')
@php
    use Carbon\Carbon;
    use App\Models\InformacionInputPrecargado;
    use App\Models\MetaIndicador;
@endphp
<style>
    .accordion{
        padding-top: 0.25rem;   /* menos alto */
        padding-bottom: 0.25rem;
        padding-left: 0.75rem;  /* opcional, ajusta horizontal */
        padding-right: 0.75rem;
        margin: 0rem;
        font-size: 0.9rem;
    }
</style>


<div class="container-fluid sticky-top ">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h2 class="text-white league-spartan">{{$indicador->nombre}}</h2>

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

                <form action="#" method="GET">
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
                                    value="{{ request('fecha_inicio') ?? now()->format('Y-m-d') }}"
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

                        <!-- Botón gráfica -->
                        <div class="ms-auto">

                            <a href="{{ route('analizar.indicador', $indicador->id) }}" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm text-white"  >
                                <i class="fa-solid fa-magnifying-glass"></i>
                                 Ver detalles y grafica
                            </a>                        
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


</div>






<!-- BOTONES FLOTANTES -->
<div class="scroll-buttons">
    <button id="btnUp" class="btn btn-primary  shadow">
        <i class="fa-solid fa-arrow-up pl-3"></i>
    </button>

    <button id="btnDown" class="btn btn-primary  shadow mt-2">
        <i class="fa-solid fa-arrow-down pl-3"></i>
    </button>
</div>





<div class="container-fluid">

@if (count($campos_llenos) != 0)
<div class="row bg-white">
    <div class="accordion p-0 m-0 bg-white" id="accordionExample">
        <div class="">
            <div class="accordion-header text-start " id="headingTwo">
                <a data-mdb-collapse-init class="fw-bold  collapsed m-2" type="button" data-mdb-target="#info_precargada" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fa-solid fa-file-excel"></i>
                    Información cargada desde Excel.
                </a>
            </div>
            <div id="info_precargada" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row gap-4  justify-content-start d-flex align-items-center">


                        @forelse ($campos_llenos as $campo_lleno)
                            <div class="col-5 col-sm-5 col-md-5 col-lg-auto  text-center  bg-white   pt-2 rounded shadow-sm">
                                <h5 class="fw-bold">{{$campo_lleno->nombre}}</h5>


                                <h5  class="">
                                    @php
                                        $last_info = InformacionInputPrecargado::where('id_input_precargado', $campo_lleno->id)->latest()->first();
                                        $meses = ["0","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                                    @endphp
                                    {{$last_info->informacion}}
                                </h5>
                                <p>{{$meses[$last_info->mes]}} {{$last_info->year}}</p>
                                <small>{{$campo_lleno->descripcion}}</small>
                            </div>
                        @empty
                            <div class="col-12 border border-4 p-5 text-center">
                                <h2>
                                    <i class="fa fa-exclamation-circle text-danger"></i>
                                    No se encontraron datos
                                </h2>
                            </div>
                        @endforelse


                    </div>
                </div>
            </div>
        </div>
    </div>

</div> 
@endif


<div class=" row justify-content-center pb-5 m border-bottom d-flex align-items-center mt-1">
<div  class="col-12 mx-2 px-5 py-3 pb-5 ">
            
<div class="row ">

@forelse($grupos as $movimiento => $items)

@php

    $metas = MetaIndicador::where(
        'id_movimiento_indicador_lleno',
        $items->first()->id_movimiento
    )->first();

    //ouse los dos en la meta maxima para no ir a mover en la base de datos. 
    $meta_minima = $metas->meta_maxima ?? 0;
    $meta_maxima = $metas->meta_maxima ?? 0;

    Carbon::setLocale('es');
    $fecha = Carbon::parse($items[0]->fecha_periodo);

    $mes  = ucfirst($fecha->translatedFormat('F'));
    $year = $fecha->translatedFormat('Y');
@endphp


<div class="col-12 mb-3 mt-1 item-scroll">
    <div class="card shadow-sm border-0">

        <!-- HEADER -->
        <div class="card-header bg-info text-white text-center py-3">
            <h4 class="fw-semibold mb-0">
                <i class="fa-solid fa-chart-line me-1"></i>
                {{ $mes }} {{ $year }}
            </h4>
        </div>

        <div class="card-body">
            <!-- KPI PRINCIPALES -->
            <div class="row justify-content-center">
              
                @foreach($items as $item)
                {{-- ================= KPI GRANDE ================= --}}
                @if($item->final === 'on')

                @php

                    if($indicador->tipo_indicador === "riesgo"){

                        if($item->informacion_campo < $meta_maxima){
                            $semaforizacion = 'text-success';
                            $icono = '<i class="fa-solid fa-2x text-success fa-check-circle"></i>';
                        }

                        if($item->informacion_campo >= $meta_maxima){
                            $semaforizacion = 'text-danger';
                            $icono = '<i class="fa-solid fa-2x text-danger fa-triangle-exclamation"></i>';
                        }
                    

                        //este es un comodin, si el indicador de menor es mejor es menor o igual a cero se va a cumplimiento muajajajajaja
                        if($item->informacion_campo <= 0){
                            $semaforizacion = 'text-success';
                            $icono = '<i class="fa-solid fa-2x text-success fa-check-circle"></i>';
                        }                  

                    
                    }


                    if($indicador->tipo_indicador === "normal"){

                        $porcentaje = ($item->informacion_campo / $meta_maxima) * 100; 

                        if($item->informacion_campo < $meta_maxima){
                            $semaforizacion = 'text-danger';
                            $icono = '<i class="fa-solid fa-2x text-danger fa-triangle-exclamation"></i>';             
                        }

                        else{ 
                            $semaforizacion = 'text-success';
                            $icono = '<i class="fa-solid fa-2x fa-circle-check text-success"></i>';
                        }
                    
                    }

                            // if($indicador->variacion === "on"){
                            //     if ($meta_minima == 0 && $meta_maxima == 0) {
                            //         $cumple = true;
                            //     } else {
                            //         $min = $meta_maxima - $meta_minima;
                            //         $max = $meta_maxima + $meta_minima;
                            //         $cumple = $item->informacion_campo >= $min 
                            //             && $item->informacion_campo <= $max;
                            //     }
                            // } else {
                            //     $cumple = $item->informacion_campo >= $meta_minima; 
                            // }

                            

                        @endphp

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">

                            <div class="row">
                                @if ($indicador->unidad_medida === "porcentaje")
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm h-100 text-center 
                                            {{ $semaforizacion }}">
                                            <div class="card-body shadow-0">

                                                <div class="row">

                                                    <div class="col-12 mb-2">
                                                        {!!  $icono !!}
                                                    </div>
                                                    <div class="col-12">
                                                        <h4 class="text-dark">{{ $item->nombre_campo }}</h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <h2 class="fw-bold format-number">
                                                            @if($indicador->unidad_medida === 'pesos')
                                                                ${{ round($item->informacion_campo,2) }}
                                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                                {{ round($item->informacion_campo,2) }}%
                                                            @elseif($indicador->unidad_medida === 'dias')
                                                                {{ round($item->informacion_campo,2) }} Días
                                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                                {{ round($item->informacion_campo,2) }} Ton.
                                                            @else
                                                                {{ round($item->informacion_campo,2) }}
                                                            @endif
                                                        </h2>
                                                    </div>
                                                </div>
                                                <!-- METAS -->
                                                <div class="col-12 d-flex justify-content-center flex-wrap gap-3">

                                                    @if ($indicador->tipo_indicador == "riesgo")
                                                        <span class="badge bg-info-subtle text-info p-2">
                                                            Limite: {{ $meta_maxima }}
                                                        </span>
                                                    @endif

                                                    @if ($indicador->tipo_indicador == "normal")
                                                        <span class="badge bg-danger-subtle text-danger p-2">
                                                            Aceptable: {{ $meta_minima }}
                                                        </span>
                                                        <span class="badge bg-success-subtle text-success p-2">
                                                            Esperada: {{ $meta_maxima }}
                                                        </span>
                                                    @endif
                                                </div>                                    
                                            </div>
                                        </div>
                                    </div>                                
                                @else 
                                {{-- Aqui va el cumplimiento en la unidad de medida que esta y en porcentaje de acuerdo a la meta dada.                                --}}
                                    <div class="col-6">
                                        <div class="card border-0 shadow-sm h-100 text-center 
                                            {{ $semaforizacion }}">
                                            <div class="card-body shadow-0">

                                                <div class="row">

                                                    <div class="col-12 mb-2">
                                                        {!!  $icono !!}
                                                    </div>
                                                    <div class="col-12">
                                                        <h4 class="text-dark">{{ $item->nombre_campo }}</h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <h2 class="fw-bold format-number">
                                                            @if($indicador->unidad_medida === 'pesos')
                                                                ${{ round($item->informacion_campo,2) }}
                                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                                {{ round($item->informacion_campo,2) }}%
                                                            @elseif($indicador->unidad_medida === 'dias')
                                                                {{ round($item->informacion_campo,2) }} Días
                                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                                {{ round($item->informacion_campo,2) }} Ton.
                                                            @else
                                                                {{ round($item->informacion_campo,2) }}
                                                            @endif
                                                        </h2>
                                                    </div>
                                                </div>
                                                <!-- METAS -->
                                                <div class="col-12 d-flex justify-content-center flex-wrap gap-3">

                                                    @if ($indicador->tipo_indicador == "riesgo")
                                                        <span class="badge bg-info-subtle text-info p-2">
                                                            Limite: {{ $meta_maxima }}
                                                        </span>
                                                    @endif

                                                    @if ($indicador->tipo_indicador == "normal")
                                                        <span class="badge bg-danger-subtle text-danger p-2">
                                                            Aceptable: {{ $meta_minima }}
                                                        </span>
                                                        <span class="badge bg-success-subtle text-success p-2">
                                                            Esperada: {{ $meta_maxima }}
                                                        </span>
                                                    @endif
                                                </div>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-0 shadow-sm h-100 text-center 
                                            {{ $semaforizacion }}">
                                            <div class="card-body shadow-0">

                                                <div class="row">

                                                    <div class="col-12 mb-2">
                                                        {!!  $icono !!}
                                                    </div>
                                                    <div class="col-12">
                                                        <h5>% de Cumplimiento de:</h5>
                                                        <h4 class="text-dark">{{ $item->nombre_campo }}</h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <h2 class="fw-bold format-number">
                                                            @if ($indicador->tipo_indicador === "riesgo")
                                                                {{ round(($meta_maxima / $item->informacion_campo) * 100, 2) }}%
                                                            @endif
                                                            @if ($indicador->tipo_indicador === "normal")
                                                                {{ round(($item->informacion_campo / $meta_maxima)  * 100, 2) }}%
                                                            @endif
                                                        </h2>
                                                    </div>
                                                </div>
                                                <!-- METAS -->
                                                <div class="col-12 d-flex justify-content-center flex-wrap gap-3">

                                                    @if ($indicador->tipo_indicador == "riesgo")
                                                        <span class="badge bg-info-subtle text-info p-2">
                                                            Limite: {{ $meta_maxima }}
                                                        </span>
                                                    @endif

                                                    @if ($indicador->tipo_indicador == "normal")
                                                        <span class="badge bg-danger-subtle text-danger p-2">
                                                            Aceptable: {{ $meta_minima }}
                                                        </span>
                                                        <span class="badge bg-success-subtle text-success p-2">
                                                            Esperada: {{ $meta_maxima }}
                                                        </span>
                                                    @endif
                                                </div>                                    
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>


                    @endif

                @endforeach

            </div>

            <!-- KPI SECUNDARIOS -->
            <div class="row mt-2 justify-content-center">

                @foreach($items as $item)

                    @if(is_null($item->final))
                        <div class="col-12 col-md-3 mb-1">
                            <div class="border p-1  h-100 h6 p-3">
                                <small class=" h5">{{ $item->nombre_campo }}</small>
                                <div class="fw-bold  h3 mt-1 format-number">
                                    {{ $item->informacion_campo }}
                                </div>
                            </div>
                        </div>
                    @endif

                @endforeach

            </div>

            <!-- REGISTROS -->
            <div class="mt-3">

                @foreach($items as $item)

                    @if($item->final === 'registro')
                        <div class="bg-light border rounded p-2 mb-2 small">
                            <div class="fw-semibold">{{ $item->nombre_campo }}</div>
                            <div class="text-muted">
                                {{ $item->informacion_campo }} ·
                                {{ $item->created_at->translatedFormat('d M Y H:i') }}
                            </div>
                        </div>
                    @endif

                @endforeach

            </div>

            <!-- COMENTARIOS -->
            <div class="mt-3 text-center">

                @foreach($items as $item)

                    @if($item->final === 'comentario')

                        <button class="btn btn-outline-secondary btn-sm m-1"
                                data-mdb-modal-init
                                data-mdb-target="#com{{ $item->id }}">
                            <i class="fa fa-table"></i>
                            Información Extra
                        </button>

                        <div class="modal fade" id="com{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h4 class="modal-title">{{ $indicador->nombre }}</h4>
                                        <button class="btn-close" data-mdb-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body small ck-content table-responsive">
                                        <h4>{!! $item->informacion_campo !!}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif

                @endforeach

            </div>

        </div>
    </div>
</div>


{{-- <div class="col-12 col-lg-4 mt-3">
    <div class="card shadow-sm border-0 h-100">
        <!-- HEADER -->
        <div class="card-header bg-info text-white text-center py-2">
            <h5 class="fw-semibold mb-0 p-1">
                <i class="fa-solid fa-calendar-days me-1"></i>
                {{ $mes }} {{ $year }}
            </h5>
        </div>

        <div class="card-body py-3">

            <!-- METAS -->
                @if ($indicador->tipo_indicador == "riesgo")

                    @if ($indicador->variacion == "on")

                        <div class="row p-0">
                            <div class="col-6">
                                <span class="badge bg-success-subtle text-success p-2 w-100">
                                    <i class="fa-solid fa-arrow-up"></i>
                                    Variación:  {{ $meta_minima }}
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="badge bg-danger-subtle text-danger p-2 w-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Meta:  {{ $meta_maxima }}
                                </span>
                            </div>
                        </div>   

                    @else
                        
                        <div class="row p-0">
                            <div class="col-6">
                                <span class="badge bg-success-subtle text-success p-2 w-100">
                                    <i class="fa-solid fa-arrow-up"></i>
                                    Maximo:  {{ $meta_minima }}
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="badge bg-danger-subtle text-danger p-2 w-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Exceso:  {{ $meta_maxima }}
                                </span>
                            </div>
                        </div>
                    @endif

                    
                @else


                    @if ($indicador->variacion == "on")

                        <div class="row p-0">
                            <div class="col-6">
                                <span class="badge bg-success-subtle text-success p-2 w-100">
                                    <i class="fa-solid fa-arrow-up"></i>
                                    Variación:  {{ $meta_minima }}
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="badge bg-danger-subtle text-danger p-2 w-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    Meta:  {{ $meta_maxima }}
                                </span>
                            </div>
                        </div>   

                    @else

                    

                    <div class="row p-0">
                        <div class="col-6">
                            <span class="badge bg-danger-subtle text-danger p-2 w-100">
                                <i class="fa-solid fa-arrow-down"></i>
                                Mínima: {{ $meta_minima }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-success-subtle text-success p-2 w-100">
                                <i class="fa-solid fa-arrow-up"></i>
                                Máxima: {{ $meta_maxima }}
                            </span>
                        </div>
                    </div>

                    @endif



                @endif

            <hr class="my-2">

            <!-- ITEMS -->
            <div class="row g-2 justify-content-center">

            @foreach($items as $item)

                <!-- RESULTADO FINAL -->
                @if($item->final === 'on')

                    @php 

                        if($indicador->variacion === "on"){


                    
                            if ($meta_minima == 0 && $meta_maxima == 0) {
                                $cumple = true; //aqui se cambia el sentido de este pequeño algoritmo.
                            } else {
                                $min = $meta_maxima - $meta_minima;
                                $max = $meta_maxima + $meta_minima;

                                $cumple = $item->informacion_campo >= $min 
                                    && $item->informacion_campo <= $max;
                            }

                        }
                        else{

                            $cumple = $item->informacion_campo >= $meta_minima; 
                        
                        }
                    @endphp




                    @if ($indicador->tipo_indicador == "riesgo")

                        <div class=" col-8 bg-  dark border border-2 rounded text-center py-3 my-4
                            {{ $cumple ? 'border-danger' : 'border-success' }}">
                            
                            <h6 class="fw-bold mb-1">
                                <i class="fa-solid {{ $cumple ? 'fa-circle-xmark text-danger' : 'fa-circle-check text-success' }}"></i>
                                {{ $item->nombre_campo }}
                            </h6>

                            <h4 class="fw-bold mb-0">

                                <span class="card-title fw-bold display-6 mt-3 h3">

                                    @if($indicador->unidad_medida === 'pesos')
                                        ${{ $item->informacion_campo }}

                                    @elseif($indicador->unidad_medida === 'porcentaje')
                                            {{ $item->informacion_campo }}%

                                    @elseif($indicador->unidad_medida === 'dias')
                                            {{ $item->informacion_campo }} Días

                                    @elseif($indicador->unidad_medida === 'toneladas')
                                            {{ $item->informacion_campo }} Ton.

                                    @else
                                            {{ $item->informacion_campo }}
                                    @endif

                                </span>
                            </h4>

                        </div>    

                    @else
                    
                        <div class=" col-8   dark border border-2 rounded text-center py-3 my-4
                            {{ $cumple ? 'border-success' : 'border-danger' }}">
                            <h6 class="fw-bold mb-1">
                                <i class="fa-solid {{ $cumple ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger' }}"></i>
                                {{ $item->nombre_campo }}
                            </h6>

                            <h4 class="fw-bold mb-0">

                                <span class="card-title fw-bold display-6 mt-3 h3">

                                    @if($indicador->unidad_medida === 'pesos')
                                        ${{ $item->informacion_campo }}

                                    @elseif($indicador->unidad_medida === 'porcentaje')
                                            {{ $item->informacion_campo }}%

                                    @elseif($indicador->unidad_medida === 'dias')
                                            {{ $item->informacion_campo }} Días

                                    @elseif($indicador->unidad_medida === 'toneladas')
                                            {{ $item->informacion_campo }} Ton.

                                    @else
                                            {{ $item->informacion_campo }}
                                    @endif

                                </span>
                            </h4>
                        </div>

                    @endif

                @endif

                <!-- COMENTARIO -->
                @if($item->final === 'comentario')
                    <div class="col-12">
                        <button class="btn btn-outline-secondary btn-sm py-1 px-2"
                                data-mdb-modal-init
                                data-mdb-target="#com{{ $item->id }}">
                            <i class="fa fa-table"></i> Información Extra
                        </button>
                    </div>

                    <div class="modal fade" id="com{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white ">
                                    <h4 class="modal-title">{{ $indicador->nombre }}</h4>
                                    <button class="btn-close" data-mdb-dismiss="modal"></button>
                                </div>
                                <div class="modal-body small ck-content table-responsive">
                                   <h4> {!!  $item->informacion_campo !!} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- REGISTRO -->
                @if($item->final === 'registro')
                    <div class="col-12">
                        <div class="bg-light border rounded p-2 small">
                            <div class="fw-semibold">{{ $item->nombre_campo }}</div>
                            <div class="text-muted">
                                {{ $item->informacion_campo }} ·
                                {{ $item->created_at->translatedFormat('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- NORMAL -->
                @if(is_null($item->final))
                    <div class="col-6 card-click">
                        <div class="border rounded p-2 small">
                            <span class="text-muted h5">{{ $item->nombre_campo }}</span>
                            <div class="fw-bold format-number h4">
                                {{ $item->informacion_campo }}
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach

            </div>
        </div>
    </div>
</div> --}}






@empty
<div class="col-12 text-center text-muted py-5 bg-white h2">
    <i class="fa-solid fa-circle-info"></i> Sin información disponible
</div>
@endforelse

</div>


        </div>

    </div>
</div>



<div class="modal fade" id="grafico_indicador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog  modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary py-3">
                <h4 class="text-white" id="exampleModalLabel">{{$indicador->nombre}}</h4>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Cloeesdasdse"></button>
            </div>
            <div class="modal-body  row justify-content-center" >

                    <div class="col-12 py-3">
                        <div class="row g-3">
                            
                            @forelse ($promedios as $promedio)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="card shadow-sm bg-info text-white border-0 h-100 text-center">
                                        <div class="card-body  py-3">
                                            <small class="text-white d-block mb-1">
                                                <i class="fa-solid fa-calendar-days"></i>                                            
                                                Promedio Anual
                                            </small>

                                            <h5 class="fw-bold mb-2">
                                                @if($indicador->unidad_medida === 'pesos')
                                                    ${{ number_format($promedio->promedio, 2) }}

                                                @elseif($indicador->unidad_medida === 'porcentaje')
                                                    {{ round($promedio->promedio, 2) }}%

                                                @elseif($indicador->unidad_medida === 'dias')
                                                    {{ round($promedio->promedio, 2) }} Días

                                                @elseif($indicador->unidad_medida === 'toneladas')
                                                    {{ round($promedio->promedio, 2) }} Ton.

                                                @else
                                                    {{ round($promedio->promedio, 2) }}
                                                @endif

                                            </h5>

                                            <span class="badge bg-light text-dark">
                                                Año: {{ $promedio->anio }}
                                            </span>
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

                        <div class="row g-3 my-1 justify-content-start">
                            
                            @forelse ($info_meses as $info_mes)

                                <div class="col-auto shadow-sm mx-1 text-center border-bottom mb-1 p-2">
                                    <span class="fw-bold">
                                        <i class="fa {{ ($loop->last ? 'fa-location-dot text-primary' : 'fa-calendar') }} "></i>
                                        {{ Carbon::parse($info_mes->fecha_periodo)->translatedFormat('F Y') }} 
                                    </span>
                                    
                                    <br>

                                    <span class="h5">
                                        @if($indicador->unidad_medida === 'pesos')
                                            ${{ number_format($info_mes->informacion_campo, 2) }}

                                        @elseif($indicador->unidad_medida === 'porcentaje')
                                            {{ round($info_mes->informacion_campo, 2) }}%

                                        @elseif($indicador->unidad_medida === 'dias')
                                            {{ round($info_mes->informacion_campo, 2) }} Días

                                        @elseif($indicador->unidad_medida === 'toneladas')
                                            {{ round($info_mes->informacion_campo, 2) }} Ton.

                                        @else
                                            {{ round($info_mes->informacion_campo, 2) }}
                                        @endif 
                                      
                                    </span>

                                </div>

                            @empty
                                
                            @endforelse
                        </div>


                    </div>


                    <div class="row">
                        @if ($indicador->tipo_indicador  === "riesgo")
                            <div class="col-12 text-center text-danger">
                                <h5 class="fw-bold ">
                                    <i class="fa-solid fa-circle-exclamation"></i>         
                                    Limite:            
                                        @if($indicador->unidad_medida === 'pesos')
                                            ${{ $indicador->meta_esperada }}

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
                        @endif
                        @if($indicador->tipo_indicador === "normal")
                            <div class="col-12 text-center">
                                <h5>
                                    <i class="fa fa-circle-exclamation text-primary"></i>
                                    Minimo Esperado:
                                        @if($indicador->unidad_medida === 'pesos')
                                            ${{ $indicador->meta_minima }}

                                        @elseif($indicador->unidad_medida === 'porcentaje')
                                            {{ $indicador->meta_minima }}%

                                        @elseif($indicador->unidad_medida === 'dias')
                                           {{ $indicador->meta_minima }} Días

                                        @elseif($indicador->unidad_medida === 'toneladas')
                                            {{ $indicador->meta_minima }} Ton.

                                        @else
                                            {{ $indicador->meta_minima }}
                                        @endif  - 
                                    Meta
                                        @if($indicador->unidad_medida === 'pesos')
                                            ${{ $indicador->meta_esperada }}

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
                        @endif
                    </div>



                    <div class="row" >
                        <!-- Tabs content -->
                        <div class="tab-content" id="ex2-content">

                            <div class="tab-pane  show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1" >
                                <div class="col-12 d-flex justify-content-center chart-container w-100" style="height: 500px" >
                                    <canvas class="" id="grafico"></canvas>
                                </div>
                            </div>

                            <div class="tab-pane  p-5" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                                <div class="col-12 d-flex justify-content-center chart-container w-100" style="height: 500px" >
                                    <canvas id="graficoPie"></canvas>
                                </div>
                            </div>

                            <div class="tab-pane " id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3" >
                                <div class="col-12 d-flex justify-content-center chart-container w-100" style="height: 500px" >
                                    <canvas id="graficoLine"></canvas>
                                </div>
                            </div>

                        </div>
                        <!-- Tabs content -->

                        <!-- Tabs navs -->
                        <ul class="nav nav-tabs nav-justified mb-3 " id="ex1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a data-mdb-tab-init class="nav-link fw-bold display-2 text-dark active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true">
                                    <i class="fa fa-chart-simple"></i>
                                    Gráfico Barras
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false">
                                    <i class="fa fa-chart-pie"></i>
                                    Gráfico Pie
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-3" href="#ex3-tabs-3" role="tab" aria-controls="ex3-tabs-3" aria-selected="false">
                                    <i class="fa fa-circle"></i>
                                    Gráfico Lineas
                                </a>
                            </li>
                        </ul>
                        <!-- Tabs navs -->


                </div>

            </div>
        </div>
    </div>
</div>







</div>




@endsection





@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const indicador = @json($indicador);
    const datos = @json($graficar);
    const TIPO_INDICADOR = "{{ $tipo_indicador }}";

    const mesesES = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    if (!datos || datos.length === 0) return;

    const datosFinal = datos.filter(d => d.final === "on");
    const datosReferencia = datos.filter(d => d.referencia === "on");

    const labels = [...new Set(
        [...datosFinal, ...datosReferencia].map(item => {
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
            anchor: 'end',
            align: 'top',
            color: '#000',
            font: function(context) {
                const total = context.chart.data.labels.length;
                let size = 14;
                if (total > 6) size = 12;
                if (total > 10) size = 10;
                if (total > 15) size = 8;

                return {
                    weight: 'bold',
                    size: size
                };
            },
            formatter: function(value) {
                if (value === null) return '';

                switch (UNIDAD_MEDIDA) {
                    case 'pesos': return '$' + value.toFixed(2);
                    case 'porcentaje': return value.toFixed(2) + '%';
                    case 'dias': return value.toFixed(2) + ' Días';
                    case 'toneladas': return value.toFixed(2) + ' Ton.';
                    default: return value.toFixed(2);
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

                            return dataset.type !== 'bar'; // 👈 oculta solo barras
                        }
                    }
                },
                datalabels: {
                    display: context => context.dataset.type === 'bar'
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

    const datos = @json($graficar);
    const TIPO_INDICADOR = "{{ $tipo_indicador }}";
    const UNIDAD_MEDIDA = "{{ $indicador->unidad_medida }}";

    const mesesES = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    if (!datos || datos.length === 0) return;

    // ============================
    // FILTRAR DATOS
    // ============================

    const datosFinal = datos.filter(d => d.final === "on");
    const datosReferencia = datos.filter(d => d.referencia === "on");

    const todosDatos = [...datosFinal, ...datosReferencia];

    // ============================
    // LABELS (MES + AÑO ORDENADO)
    // ============================

    const fechasUnicas = [...new Set(
        todosDatos.map(item => item.fecha_periodo)
    )].sort((a, b) => new Date(a) - new Date(b));

    const labels = fechasUnicas.map(fechaStr => {
        const fecha = new Date(fechaStr);
        const mes = mesesES[fecha.getMonth()];
        const year = fecha.getFullYear();
        return `${mes} ${year}`;
    });

    // ============================
    // METAS + VARIACIÓN
    // ============================

    const VARIACION_ON = "{{ $indicador->variacion }}" === "on";

    const META_MINIMA = {{ $indicador->meta_minima ?? 0 }};
    const META_ESPERADA = {{ $indicador->meta_esperada ?? 0 }};

    const VARIACION = VARIACION_ON ? META_MINIMA : null;

    const LIMITE_INFERIOR = VARIACION_ON ? META_ESPERADA - VARIACION : null;
    const LIMITE_SUPERIOR = VARIACION_ON ? META_ESPERADA + VARIACION : null;

    // ============================
    // DATA FINAL
    // ============================

    const dataValores = fechasUnicas.map(fecha => {
        const item = datosFinal.find(d => d.fecha_periodo === fecha);
        return item ? parseFloat(item.informacion_campo) : null;
    });

    const nombreCampo = datosFinal.length > 0
        ? datosFinal[0].nombre_campo
        : "Indicador";

    // ============================
    // FUNCIÓN GLOBAL DE COLOR
    // ============================

    function obtenerColor(valor) {

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
    }

    // ============================
    // 📈 GRÁFICA DE LÍNEA
    // ============================

    const ctxLine = document.getElementById("graficoLine");

    if (ctxLine) {

        new Chart(ctxLine.getContext("2d"), {

            type: "line",

            data: {
                labels: labels,
                datasets: [{
                    label: nombreCampo,
                    data: dataValores,
                    borderColor: "rgba(54,162,235,1)",
                    backgroundColor: "rgba(54,162,235,0.1)",
                    tension: 0,
                    fill: true,
                    pointBackgroundColor: ctx => obtenerColor(ctx.raw),
                    pointRadius: 6
                }]
            },

            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'center',
                        color: '#000',
                        font: function(context) {

                            const total = context.chart.data.labels.length;

                            let size = 17;

                            if (total > 6) size = 15;
                            if (total > 10) size = 12;
                            if (total > 15) size = 10;
                            if (total > 20) size = 8;

                            return {
                                weight: 'bold',
                                size: size
                            };
                        },
                        formatter: function(value) {

                            if (value === null) return '';

                            switch (UNIDAD_MEDIDA) {

                                case 'pesos':
                                    return '$' + value.toFixed(2);

                                case 'porcentaje':
                                    return value.toFixed(2) + '%';

                                case 'dias':
                                    return value.toFixed(2) + ' Días';

                                case 'toneladas':
                                    return value.toFixed(2) + ' Ton.';

                                default:
                                    return value.toFixed(2);
                            }
                        }
                    }
                }
            },

            plugins: [ChartDataLabels]

        });

    }

    // ============================
    // 🥧 GRÁFICA DOUGHNUT
    // ============================

    const ctxPie = document.getElementById("graficoPie");

    if (ctxPie) {

        new Chart(ctxPie.getContext("2d"), {

            type: "doughnut",

            data: {
                labels: labels,
                datasets: [{
                    label: nombreCampo,
                    data: dataValores,
                    backgroundColor: dataValores.map(v => obtenerColor(v)),
                    borderWidth: 1
                }]
            },

            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display:false
                    },
                    datalabels: {
                        color: '#fff',
                        font: function(context) {

                            const total = context.chart.data.labels.length;

                            let size = 15;

                            if (total > 6) size = 15;
                            if (total > 10) size = 10;
                            if (total > 15) size = 8;
                            if (total > 20) size = 8;

                            return {
                                weight: 'bold',
                                size: size
                            };
                        },
                        formatter: function(value, context) {

                            if (value === null) return '';

                            const label = context.chart.data.labels[context.dataIndex];

                            let valorFormateado = '';

                            switch (UNIDAD_MEDIDA) {

                                case 'pesos':
                                    valorFormateado = '$' + value.toFixed(2);
                                    break;

                                case 'porcentaje':
                                    valorFormateado = value.toFixed(2) + '%';
                                    break;

                                case 'dias':
                                    valorFormateado = value.toFixed(2) + ' Días';
                                    break;

                                case 'toneladas':
                                    valorFormateado = value.toFixed(2) + ' Ton.';
                                    break;

                                default:
                                    valorFormateado = value.toFixed(2);
                            }

                            return label + '\n' + valorFormateado;
                        },

                    }
                }
            },

            plugins: [ChartDataLabels]

        });

    }

});
</script>








<script>
document.addEventListener("DOMContentLoaded", function () {

    const items = document.querySelectorAll('.item-scroll');
    let currentIndex = 0;

    function scrollToItem(index) {
        if (index >= 0 && index < items.length) {

            const offset = 200;

            const elementPosition = items[index].getBoundingClientRect().top + window.scrollY;

            window.scrollTo({
                top: elementPosition - offset,
                behavior: 'smooth'
            });

            currentIndex = index;
        }
    }

    document.getElementById('btnDown').addEventListener('click', function () {
        if (currentIndex < items.length - 1) {
            scrollToItem(currentIndex + 1);
        }
    });

    document.getElementById('btnUp').addEventListener('click', function () {
        if (currentIndex > 0) {
            scrollToItem(currentIndex - 1);
        }
    });

    // 👇 DETECTAR SCROLL DEL USUARIO (la magia)
    window.addEventListener('scroll', () => {

        let closestIndex = 0;
        let closestDistance = Infinity;

        items.forEach((item, index) => {
            const rect = item.getBoundingClientRect();

            const distance = Math.abs(rect.top - 120); // offset visual

            if (distance < closestDistance) {
                closestDistance = distance;
                closestIndex = index;
            }
        });

        currentIndex = closestIndex;
    });

});
</script>






{{--  --}}




@endsection