@extends('plantilla')
@section('title', 'Lista de Indicadores del departamento')
@section('contenido')
@php
    use Carbon\Carbon;
    use App\Models\MetaIndicador;
    use App\Models\IndicadorLleno;
@endphp
<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h1 class="mt-1 mb-0">
                {{$departamento->nombre}}
            </h1>

            <h3>
                Lista de indicadores de {{$departamento->nombre}}
            </h3>

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

    
    {{-- <div class="row">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body py-3 px-4 text-center">

                <form action="#" method="GET">
                    <div class="d-flex flex-wrap align-items-end gap-3">
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Selecciona una fecha:</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-calendar-days text-primary"></i>
                                </span>

                                <select class="form-select" onchange="this.form.submit()" name="fecha_select">
                                    <option value="" disabled selected>
                                        Selecciona una Fecha
                                    </option>
                                    @foreach ($fechas_seleccionar as $fecha)
                                        <option value="{{ $fecha }}" {{ $fecha == request('fecha_select') ? 'selected' : '' }}>
                                            {{ Carbon::parse($fecha)->locale('es')->translatedFormat('F \d\e Y') }}
                                        </option>                                        
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}


</div>


<div class="container-fluid">
    <div class="row jusfify-content-center">
        
        @forelse ($indicadores as $indicador)

            @php
                $contador = 0;
                $suma = 0;

                $resultado = [];
            @endphp

            @foreach($indicador->indicadorLleno as $indicador_lleno)

                @if ($indicador_lleno->final == 'on')

                    @php
                        $contador++;
                        $suma = $suma + $indicador_lleno->informacion_campo;
                        array_push($resultado,$indicador_lleno->informacion_campo ) 
                    @endphp

                @endif

            @endforeach
                


            @if ($contador > 0)
            @php
                $cumplimiento = end($resultado);
            @endphp



                 @if ($indicador->variacion === 'on')
                    
                <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">

                    <div class="card text-white {{($cumplimiento >= ($indicador->meta_esperada - $indicador->meta_minima)&& $cumplimiento <= ($indicador->meta_esperada + $indicador->meta_minima)) ? 'bg-success' : 'bg-danger'}} shadow-2-strong" data-tipo="indicador"
                    data-nombre="{{ $indicador->nombre }}"
                    data-valor="{{ $cumplimiento }}"
                    data-meta-min="{{ $indicador->meta_minima }}"
                    data-meta-max="{{ $indicador->meta_esperada }}"
                    data-clase="{{ $indicador->variacion === 'on' ? 'variacion' : ($indicador->tipo_indicador === 'riesgo' ? 'riesgo' : 'normal') }}">
                    
                    
                        <a href="{{route('indicador.lleno.show.admin', $indicador->id)}}" class="text-white w-100">
                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 ">
                                    <h4 class="card-text fw-bold nombre">{{$indicador->nombre}}</h4>
                                    
                                        <span class="card-title fw-bold display-6 mt-3 h3 resultado">

                                            @if($indicador->unidad_medida === 'pesos')
                                                ${{ number_format($cumplimiento, 2) }}

                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                {{ round($cumplimiento, 2) }}%

                                            @elseif($indicador->unidad_medida === 'dias')
                                                {{ round($cumplimiento, 2) }} Días

                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                {{ round($cumplimiento, 2) }} Ton.

                                            @else
                                                {{ round($cumplimiento, 2) }}
                                            @endif

                                        </span>

                                        <input type="hidden" class="meta_minima" value="{{ $indicador->meta_minima }}">
                                        <input type="hidden" class="meta_maxima" value="{{ $indicador->meta_esperada }}" >
                                        <input type="hidden" class="resultado_obtenido" value="{{ $cumplimiento }}">
                                        <input type="hidden" class="tipo_indicado" value="variacion"> 
                                                                            
                                </div>

                                <div class="col-12 my-2">
                                    <div class="row justify-content-around">
                                        <div class="col-auto">
                                            <span>
                                                <i class="fa fa-arrow-up"></i>
                                                Meta: {{ $indicador->meta_esperada }}
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-up-down"></i>
                                            <span>Variacion: {{ $indicador->meta_minima }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                        <div class="card-footer p-2">
                            <div class="row  d-flex justify-content-between align-items-center">
                                <div class="col-auto h4">
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
                                                ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                @else




                @if ($indicador->tipo_indicador === "riesgo")

                <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                    <div class="card text-white {{($cumplimiento < $indicador->meta_minima) ? 'bg-success' : 'bg-danger'}} shadow-2-strong" data-tipo="indicador" data-nombre="{{ $indicador->nombre }}"
                    data-valor="{{ $cumplimiento }}"
                    data-meta-min="{{ $indicador->meta_minima }}"
                    data-meta-max="{{ $indicador->meta_esperada }}"
                    data-clase="riesgo">
                        <a href="{{route('indicador.lleno.show.admin', $indicador->id)}}" class="text-white w-100">

                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 ">
                                    <h4 class="card-text fw-bold nombre">{{$indicador->nombre}}</h4>
                                        <span class="card-title fw-bold display-6 mt-3 h3 resultado">

                                            @if($indicador->unidad_medida === 'pesos')
                                                ${{ number_format($cumplimiento, 2) }}

                                            @elseif($indicador->unidad_medida === 'porcentaje')
                                                {{ round($cumplimiento, 2) }}%

                                            @elseif($indicador->unidad_medida === 'dias')
                                                {{ round($cumplimiento, 2) }} Días

                                            @elseif($indicador->unidad_medida === 'toneladas')
                                                {{ round($cumplimiento, 2) }} Ton.

                                            @else
                                                {{ round($cumplimiento, 2) }}
                                            @endif
                                        </span>

                                        <input type="hidden" class="meta_minima" value="{{ $indicador->meta_minima }}">
                                        <input type="hidden" class="resultado_obtenido" value="{{ $cumplimiento }}">
                                        <input type="hidden" class="tipo_indicado" value="riesgo">                                


                                </div>

                            </div>

                            <div class="row justify-content-around my-2">
                                <div class="col-auto text-center">
                                    <span>
                                        <i class="fa fa-arrow-up"></i>
                                        Metas: {{ $indicador->meta_esperada }}
                                    </span>
                                </div>
                                <div class="col-auto text-center">
                                    <span class="mx-5">
                                        <i class="fa-solid fa-circle-down"></i>
                                        Limite: {{ $indicador->meta_minima }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </a>
                        <div class="card-footer p-2">
                                <div class="row  d-flex justify-content-between align-items-center">
                                    <div class="col-auto h4">
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
                                                    ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                        !!}
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                


                @else
                    <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                        <div class="card text-white {{($cumplimiento <= $indicador->meta_minima) ? 'bg-danger' : 'bg-success'}} shadow-2-strong"
                        data-tipo="indicador"
                        data-nombre="{{ $indicador->nombre }}"
                        data-valor="{{ number_format($cumplimiento ?? 0, 2) }}"
                        data-meta-min="{{ $indicador->meta_minima }}"
                        data-meta-max="{{ $indicador->meta_esperada }}"
                        data-clase="normal">
                            <a href="{{route('indicador.lleno.show.admin', $indicador->id)}}" class="text-white w-100">
                            <div class="card-body">
                                <div class="row justify-content-around d-flex align-items-center">
                                    <div class="col-12 ">
                                        <h4 class="card-text fw-bold nombre">{{$indicador->nombre}}                               </h4>
                                            <span class="card-title fw-bold display-6 mt-3 h3 resultado">

                                                @if($indicador->unidad_medida === 'pesos')
                                                    ${{ number_format($cumplimiento, 2) }}

                                                @elseif($indicador->unidad_medida === 'porcentaje')
                                                    {{ round($cumplimiento, 2) }}%

                                                @elseif($indicador->unidad_medida === 'dias')
                                                    {{ round($cumplimiento, 2) }} Días

                                                @elseif($indicador->unidad_medida === 'toneladas')
                                                    {{ round($cumplimiento, 2) }} Ton.

                                                @else
                                                    {{ round($cumplimiento, 2) }}
                                                @endif

                                            </span>

                                    <input type="hidden" class="meta_minima" value="{{ $indicador->meta_minima }}">
                                    <input type="hidden" class="resultado_obtenido" value="{{ number_format($cumplimiento, 2) }}">
                                    <input type="hidden" class="tipo_indicado" value="normal">                                

                                    </div>

                                </div>

                                <div class="row justify-content-around my-2">
                                    <div class="col-auto text-center">
                                        <span>
                                            <i class="fa fa-arrow-up"></i>
                                            Meta: {{ $indicador->meta_esperada }}
                                        </span>
                                    </div>
                                    <div class="col-auto text-center">
                                        <span class="mx-5">
                                            <i class="fa-solid fa-circle-down"></i>
                                            Min.: {{ $indicador->meta_minima }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            </a>
                            <div class="card-footer ">
                                    <div class="row justify-content-between ">
                                        <div class="col-auto h4">
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
                                                        ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                                            !!}
                                        </div>
                                    </div>
                            </div>                        
                        </div>
                    </div>
                @endif

  
                @endif

                @else

                    <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                        <div class="card text-white bg-dark shadow-2-strong">
                            <a href="{{route('indicador.lleno.show.admin', $indicador->id)}}" class="text-white w-100">
                            <div class="card-body">
                                <div class="row justify-content-around d-flex align-items-center">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-7 ">
                                        <h5 class="card-title fw-bold  x">
                                            Sin registros aún.
                                        </h5>
                                        <p class="card-text fw-bold">{{$indicador->nombre}}</p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 p-0 m-0">
                                        <i class="fas fa-chart-line fa-3x"></i>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                    
                @endif 


        @empty

        @endforelse






        @forelse ($normas as $norma)
            <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                <div class="card text-white {{($norma->meta_minima > $norma->porcentaje_mes) ? 'bg-danger' : 'bg-success'}} shadow-2-strong" data-tipo="norma" data-nombre="{{ $norma->nombre }}"
                    data-valor="{{ number_format($norma->porcentaje_mes, 2) }}" data-meta-min="{{ $norma->meta_minima }}">
                    <a href="{{route('apartado.norma', $norma->id)}}" class="text-white w-100">
                    <div class="card-body">
                        <div class="row justify-content-around d-flex align-items-center">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-7 ">
                                <span class="text-capitalize fw-bold" >{{ $norma->tipo_regulacion }}</span>
                                <h2 class="card-title fw-bold display-6 resultado">{{round($norma->porcentaje_mes, 2)}}%</h2>
                                <p class="card-text fw-bold nombre">{{$norma->nombre}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 p-0 m-0">
                                <i class="fas fa-chart-line fa-3x"></i>
                                <input type="hidden" class="meta_minima" value="{{ $norma->meta_minima }}">
                                <input type="hidden" class="resultado_obtenido" value="{{ $norma->porcentaje_mes }}">
                                <input type="hidden" class="tipo_indicado" value="normal">

                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2">
                        <div class="row  d-flex justify-content-between align-items-center">
                            <div class="col-12">
                            <h4 class="">
                                {{ $norma->planta }}
                            </h4>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        @empty

        @endforelse 




        @forelse ($encuestas as $encuesta)

            <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                <div class="card text-white {{($encuesta->porcentaje_cumplimiento < $encuesta->meta_minima) ? 'bg-danger' : 'bg-success'}} shadow-2-strong" data-tipo="encuesta" data-nombre="{{ $encuesta->nombre }}" data-valor="{{ $encuesta->porcentaje_cumplimiento }}" data-meta-min="{{ $encuesta->meta_minima }}">
                    <a href="{{route('encuesta.index', $encuesta->id)}}" class="text-white w-100">
                    <div class="card-body">
                        <div class="row justify-content-around d-flex align-items-center">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-7 ">
                                <h2 class="card-title fw-bold display-6 x resultado">{{$encuesta->porcentaje_cumplimiento}}%</h2>
                                <p class="card-text fw-bold nombre">{{$encuesta->nombre}}</p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 p-0 m-0">
                                <i class="fas fa-chart-line fa-3x"></i>
                                <input type="hidden" class="meta_minima" value="{{ $encuesta->meta_minima }}">
                                <input type="hidden" class="resultado_obtenido" value="{{ $encuesta->porcentaje_cumplimiento }}">
                                <input type="hidden" class="tipo_indicado" value="normal">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-2">
                            <div class="row  d-flex justify-content-between align-items-center">
                                <div class="col-auto">
                                        Ver Detalles
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </div>
                    </div>
                    </a>
                </div>
            </div>

        @empty

        @endforelse


    </div>


    @if ($encuestas->isEmpty()  &&  $indicadores->isEmpty() )
        <div class="row mt-5 justify-content-center">
            <div class="col-9 p-5 text-center bg-white shadow shadow-sm border">
                <h4>
                    <i class="fa fa-exclamation-circle text-danger"></i>
                    No se encontro Información.
                </h4>
            </div>
        </div>
    @endif

</div>


{{-- Modales del detalle historico de los indocadores --}}
@foreach ($indicadores as $indicador)

<div class="modal fade" id="detall{{ $indicador->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary py-4">
            <h4 class="text-white" id="exampleModalLabel">
                <i class="fa fa-calendar mx-1"></i>
                Historial de {{ $indicador->nombre }} -

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

                        @php
                            $promedios = IndicadorLleno::select(
                                DB::raw('YEAR(fecha_periodo) as anio'),
                                DB::raw('AVG(informacion_campo) as promedio')
                            )
                            ->where('final', 'on')
                            ->where('id_indicador', $indicador->id)
                            ->groupBy(DB::raw('YEAR(fecha_periodo)'))
                            ->orderBy('anio')
                            ->get();
                        @endphp  



            </h4>
            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body ">
  
                
                <div class="row justify-content-center mb-2">
                    @forelse ($promedios as $promedio)
                        <div class="col-auto p-3 border border-3 border-dark rounded m-2 text-center">
                            <h5>Promedio Anual</h5>
                            <div class="row">
                                <div class="col-12">
                                        <span class="card-title fw-bold  mt-3 h3">

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

                                        </span>
                                </div>
                                <div class="col-12">
                                    <span class="fw-bold">Año: {{ $promedio->anio }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        
                    @endforelse
                </div>

                <div class="row justify-content-center px-4">
                    <div class="list-group shadow-sm rounded-4">
                        {{-- aqui dentro estan las tarjetitas que muestran el istorial de los meses llenados. --}}
                        @forelse ($indicador->indicadorLleno->reverse() as $indicador_lleno)

                            @if ($indicador_lleno->final === 'on')

               
                                    @php
                                    
                                        $resultado = $indicador_lleno->informacion_campo;
                                        $metas_indicador = MetaIndicador::where('id_movimiento_indicador_lleno', $indicador_lleno->id_movimiento)->first();
                                        
                                        $semaforizacion = "";
                                        $icono = "";
                                        $texto_meta_minimo = "";
                                        $texto_meta_maxima = "";
                                        $meta_maxima = $metas_indicador->meta_maxima;
                                        $meta_minima = $metas_indicador->meta_minima;
                                        $id_movimiento = $indicador_lleno->id_movimiento;
                                        
                                        
                                        if($indicador->tipo_indicador === "riesgo"){
                                            $texto_meta_minimo = "Aceptable";


                                            if($resultado <= $meta_minima){
                                           
                                                $semaforizacion = 'text-success';
                                                $icono = '<i class="fa-solid fa-circle-check text-success"></i>';
                                           
                                            }
                                            else{ 

                                                $semaforizacion = 'text-danger';
                                                $icono = '<i class="fa-solid text-danger fa-triangle-exclamation"></i>';
                                            
                                             }
                                        
                                        }

                                        
                                        if($indicador->tipo_indicador === "normal"){
                                            $texto_meta_minimo = "Meta Minima";

                                            if($resultado < $meta_minima){
                                                $semaforizacion = 'text-danger';
                                                $icono = '<i class="fa-solid text-danger fa-triangle-exclamation"></i>';

                                            }
                                            else{        
                                                $semaforizacion = 'text-success';
                                                $icono = '<i class="fa-solid fa-circle-check text-success"></i>';

                                            }                                        
                                        }



                                        if($indicador->variacion === "on"){
                                            $variacion = $meta_minima; // aquí se usa como margen
                                            $limite_inferior = $meta_maxima - $variacion;
                                            $limite_superior = $meta_maxima + $variacion;
                                            $texto_meta_minimo = "Variación";

                                            if($resultado >= $limite_inferior && $resultado <= $limite_superior){
                                                $semaforizacion = 'text-success'; // dentro del rango
                                                $icono = '<i class="fa-solid fa-circle-check text-success"></i>';

                                            } else {
                                                $semaforizacion = 'text-danger'; // fuera del rango
                                                $icono = '<i class="fa-solid text-danger fa-triangle-exclamation"></i>';
                                                
                                            }
                                        }
                                    @endphp


                                    <a href="#" class="list-group-item list-group-item-action p-3 ">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold">{{ $indicador_lleno->nombre_campo }}</h6>


                                            <span class="card-title {{ $semaforizacion }} fw-bold">
                                                {!! $icono !!}
                                                @if($indicador->unidad_medida === 'pesos')
                                                    $ {{ $indicador_lleno->informacion_campo }}

                                                @elseif($indicador->unidad_medida === 'porcentaje')
                                                    {{ $indicador_lleno->informacion_campo }}%

                                                @elseif($indicador->unidad_medida === 'dias')
                                                    {{ $indicador_lleno->informacion_campo }} Días

                                                @elseif($indicador->unidad_medida === 'toneladas')
                                                    {{ $indicador_lleno->informacion_campo }} Ton.

                                                @else
                                                    {{ $indicador_lleno->informacion_campo }}
                                                @endif

                                            </span>


                                        </div>
                                        <p class="mb-1 h4">
                                            
                                            <i class="fa fa-calendar"></i>
                                            {{Carbon::parse($indicador_lleno->fecha_periodo)->translatedFormat('F Y')}}.
                                        
                                        </p>
                                        <p class="fw-bold">
                                            <i class="fa-solid fa-chart-line"></i>
                                            <span>
                                                Meta: {{ $meta_maxima }}
                                            </span>
                                                -
                                            <span>
                                                {{ $texto_meta_minimo }}: {{ $meta_minima }}
                                            </span>
                                        </p>
        
                              
                                    </a>

                            @endif
                        @empty
                            
                        @endforelse
    



                        {{-- aqui dentro estan las tarjetitas que muestran el istorial de los meses llenados. --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer text-start h4">
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
                            ?? " <i class='fa-solid fa-industry'></i> Planta {$indicador->planta}")
                !!}

            </div>
        </div>
    </div>
</div>

@endforeach



<button class="btn btn-dark flotante" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico" style="z-index: 9999">
    <i class="fa-solid fa-chart-column"></i>
    Gráfica general
</button>

<div class="modal fade" id="grafico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="modal-title">
                {{ $departamento->nombre }} | Planta: {{ $departamento->planta }}
            </h5>

            <div class="d-flex align-items-center gap-2">
                <select id="sizeModal" class="form-select form-select-sm">
                    <option value="modal-xl">XL</option>
                    <option value="modal-fullscreen" selected>Fullscreen</option>
                </select>

                <button type="button" class="btn-close" data-mdb-dismiss="modal"></button>
            </div>

        </div>
        <div class="modal-body py-0">

            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true" >Indicadores</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link " id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false" >Normas</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link " id="ex3-tab-3" href="#ex3-tabs-3" role="tab" aria-controls="ex3-tabs-2" aria-selected="false" >Encuestas</a>
                    </li>
                    </ul>
                    <!-- Tabs navs -->

                    <!-- Tabs content -->
                    <div class="tab-content" id="ex2-content">

                        <div class="tab-pane fade show active " id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                            <div class="row justify-content-center">
                                
                                <div class="col-12 col-sm-12 col-md-9 col-lg-7 col-xl-7 text-center m-3 ">
                                    <h5 class="fw-bold">Promedio del Mes</h5>
                                    <h2 id="promIndicadores" class="fw-bold"></h2>
                                </div>
                                
                                <div class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-9 ">
                                    <canvas id="chartIndicadores"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-9 col-lg-7 col-xl-7 text-center m-3 ">
                                    <h5 class="fw-bold">Promedio del Mes</h5>
                                    <h3 id="promNormas"></h3>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-9 ">
                                    <canvas id="chartNormas"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-2">
                            <div class="row justify-content-center">
                                <div class="col-11 col-sm-11 col-md-9 col-lg-7 col-xl-7 text-center m-3 ">
                                    <h3 class="fw-bold" id="promEncuestas"></h3> 
                                </div>
                                <div class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-9 ">
                                    <canvas id="chartEncuestas"></canvas>
                                </div>
                            </div>
                        </div>                        

                        </div>
                    <!-- Tabs content -->
                </div>
            </div>
            <div id="mensajeVacio" class="text-center fw-bold my-3 text-danger" style="display:none;">
                 No se encontró información para graficar
            </div>
  
        </div>
    </div>
  </div>
</div>


@endsection


@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {

    const indicadores = [];
    const normas = [];
    const encuestas = [];

    // 🔹 Leer datos desde las cards
    document.querySelectorAll('.card[data-tipo]').forEach(card => {
        const tipo = (card.dataset.tipo || '').toLowerCase().trim();
        const item = {
            nombre: card.dataset.nombre,
            valor: parseFloat(card.dataset.valor) || 0,
            meta_min: parseFloat(card.dataset.metaMin),
            meta_max: parseFloat(card.dataset.metaMax),
            clase: card.dataset.clase
        };

        if (tipo === 'indicador') indicadores.push(item);
        if (tipo === 'norma') normas.push(item);
        if (tipo === 'encuesta') encuestas.push(item);
    });

    function truncarTexto(texto, limite = 22) {
        if (!texto) return '';
        return texto.length > limite ? texto.substring(0, limite) + '...' : texto;
    }

    function getColor(item) {
        if (item.clase === 'variacion') {
            return (item.valor >= (item.meta_max - item.meta_min) &&
                    item.valor <= (item.meta_max + item.meta_min))
                ? 'rgba(25, 135, 84, 0.8)'
                : 'rgba(220, 53, 69, 0.8)';
        }
        if (item.clase === 'riesgo') {
            return (item.valor < item.meta_min)
                ? 'rgba(25, 135, 84, 0.8)'
                : 'rgba(220, 53, 69, 0.8)';
        }
        return (item.valor <= item.meta_min)
            ? 'rgba(220, 53, 69, 0.8)'
            : 'rgba(25, 135, 84, 0.8)';
    }

    // Configuración base para DataLabels (Evita repetición)
    const baseDataLabels = {
        display: true, // Forzar visualización siempre
        anchor: function(context) {
            const bar = context.chart.getDatasetMeta(context.datasetIndex).data[context.dataIndex];
            return (bar && bar.height < 50) ? 'end' : 'center';
        },
        align: function(context) {
            const bar = context.chart.getDatasetMeta(context.datasetIndex).data[context.dataIndex];
            return (bar && bar.height < 50) ? 'top' : 'center';
        },
        rotation: function(context) {
            const bar = context.chart.getDatasetMeta(context.datasetIndex).data[context.dataIndex];
            return (bar && bar.height < 50) ? 0 : -90;
        },
        color: function(context) {
            const value = context.dataset.data[context.dataIndex];
            const bar = context.chart.getDatasetMeta(context.datasetIndex).data[context.dataIndex];
            // Si el valor es 0 o la barra es muy baja, texto negro para que se vea
            return (value === 0 || (bar && bar.height < 50)) ? '#000' : '#fff';
        },
        font: { weight: 'bold', size: 18 },
        formatter: (value) => value + '%',
        clamp: true,
        clip: false
    };

    // =========================
    // INDICADORES
    // =========================
    new Chart(document.getElementById('chartIndicadores'), {
        type: 'bar',
        data: {
            labels: indicadores.map(i => truncarTexto(i.nombre)),
            datasets: [{
                data: indicadores.map(i => i.valor),
                backgroundColor: indicadores.map(i => getColor(i))
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 16, weight: 'bold' } } }
            },
            plugins: {
                legend: { display: false },
                datalabels: baseDataLabels
            }
        }
    });

    // =========================
    // NORMAS
    // =========================
    new Chart(document.getElementById('chartNormas'), {
        type: 'bar',
        data: {
            labels: normas.map(n => truncarTexto(n.nombre)),
            datasets: [{
                data: normas.map(n => n.valor),
                backgroundColor: normas.map(n => (n.valor < n.meta_min) ? 'rgba(220, 53, 69, 0.8)' : 'rgba(25, 135, 84, 0.8)')
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 16, weight: 'bold' } } }
            },
            plugins: {
                legend: { display: false },
                datalabels: baseDataLabels
            }
        }
    });

    // =========================
    // ENCUESTAS
    // =========================
    const fullLabelsEncuestas = encuestas.map(e => e.nombre);
    new Chart(document.getElementById('chartEncuestas'), {
        type: 'bar',
        data: {
            labels: encuestas.map(e => truncarTexto(e.nombre)),
            datasets: [{
                data: encuestas.map(e => e.valor),
                backgroundColor: encuestas.map(e => (e.valor < e.meta_min) ? 'rgba(220, 53, 69, 0.8)' : 'rgba(25, 135, 84, 0.8)')
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: {
                x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: 16, weight: 'bold' } } }
            },
            plugins: {
                legend: { display: false },
                datalabels: baseDataLabels,
                tooltip: {
                    callbacks: {
                        title: (context) => fullLabelsEncuestas[context[0].dataIndex]
                    }
                }
            }
        }
    });



    function calcularPromedio(lista) {
        if (lista.length === 0) return 0;

        const suma = lista.reduce((acc, item) => acc + (item.valor || 0), 0);
        return suma / lista.length;
    }

    const promedioIndicadores = calcularPromedio(indicadores);
    const promedioNormas = calcularPromedio(normas);
    const promedioEncuestas = calcularPromedio(encuestas);

    document.getElementById('promIndicadores').innerText = promedioIndicadores.toFixed(2) + '%';
    document.getElementById('promNormas').innerText = promedioNormas.toFixed(2) + '%';
    document.getElementById('promEncuestas').innerText = promedioEncuestas.toFixed(2) + '%';
    });



</script>

<script>

document.getElementById("sizeModal").addEventListener("change", function () {

    const modalDialog = document.querySelector("#grafico .modal-dialog");

    // quitamos clases previas
    modalDialog.classList.remove("modal-xl", "modal-fullscreen");

    // agregamos la seleccionada
    if (this.value) {
        modalDialog.classList.add(this.value);
    }

});

</script>
@endsection
