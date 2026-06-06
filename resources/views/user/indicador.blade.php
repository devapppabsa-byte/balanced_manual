@extends('plantilla')
@section('title', 'LLenado de Indicadores')
@section('contenido')
@php
use App\Models\CampoCalculado;
use App\Models\CampoInvolucrado;
use App\Models\CampoPrecargado;
use App\Models\CampoVacio;
use App\Models\MetaIndicador;
use App\Models\InformacionInputVacio;
use App\Models\InformacionInputPrecargado;
use App\Models\InformacionInputCalculado;
use Carbon\Carbon;

@endphp

<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center">

        <div class="col-8 col-sm-8 col-md-6 col-lg-9 py-2 ">
            <h5 class="text-white"> {{$indicador->nombre}} </h5>
            <h6 class="text-white fw-bold" id="fecha"></h6>
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

            @if ($errors->any())
                <div class="bg-white  fw-bold p-2 rounded">
                    <i class="fa fa-xmark-circle mx-2  text-danger"></i>
                        No se agrego! <br> 
                    <i class="fa fa-exclamation-circle mx-2  text-danger"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>
  
        <div class="col-4 col-sm-4 col-md-6 col-lg-3 text-end ">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf 
                <button  class="btn btn-primary text-danger text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    @include('user.assets.nav')



    <div class="row border-bottom bg-white">
    {{-- LOGICA DEL BLOQUEO DEL LLENADO DE INDICADORES- SE BLOQUEA SI YA SE LLENO ESTE MES Y SE BLOQUEA SI NO SE HA CARGADO EL EXCEL. --}}
    



    </div>


    <div class="row">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body py-3 px-4">

                <div class="row d-flex flex-wrap align-items-end  gap-3">
                    
                    
                    <!-- Fecha inicio -->
                         <div class="col-auto">
                            <form action="#" method="GET">
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
                        <div class="col-auto">
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
                        </form>
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn btn-info btn-sm text-white w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico_indicador">
                                <i class="fa-solid fa-chart-line me-2"></i>
                                Gráfica
                            </button>                            
                        </div>









                            @php

                                    if(empty($ultima_carga_excel)){

                                        $fecha_excel = Carbon::parse("2026-01-20 12:51:28");
                                    }

                                    else{
                                        
                                        $fecha_excel = Carbon::parse($ultima_carga_excel->created_at);
                                    }

                                
                                    if(empty($ultima_carga_indicador)){
                                        $fecha_indicador = Carbon::parse("2026-01-20 12:51:28");
                                    }
                                    else{
                                        $fecha_indicador = Carbon::parse($ultima_carga_indicador->fecha_periodo)->addMonth();
                                    }


                                $carga_excel = $fecha_excel->format('Y-m') ?? '0000-00';    
                                $carga_indicador = $fecha_indicador->format('Y-m') ?? '0000-00';
                                $ahora = now()->format('Y-m') ?? '0000-00';
                            @endphp

                        
                            {{-- si la craga del excel es diferente a este mes y año o si la carga del indicador es menor o igual a ahora --}}
                            @if ($carga_excel !== $ahora  || $carga_indicador === $ahora)

                                @if ($carga_excel !== $ahora)
                                    <div class="col-auto">
                                        <button class="btn btn-primary btn-sm w-100" onclick="toastr.error('{{'El indicador aún no se puede llenar. Falta cargar información por parte del admin'}}', 'Error!')">
                                            <i class="fa fa-plus"></i>
                                            Llenar este Indicador (Aún no se carga el excel)
                                        </button>
                                    </div>
                                @endif

                                @if ($carga_indicador === $ahora)
                                    <div class="col-auto">
                                        <button class="btn btn-danger btn-sm w-100" onclick="toastr.warning('{{'Ya se registro la información de este mes '}}', 'Aviso!')">
                                            <i class="fa fa-plus"></i>
                                            Ya se lleno el indicador este mes
                                        </button>
                                    </div>
                                @endif


                            @else
                                <div class="col-auto">
                                    <button class="btn btn-primary btn-sm w-100 {{(Auth::user()->tipo_usuario != "principal") ? 'disabled' : ''  }}" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#llenado_indicadores">
                                        <i class="fa fa-plus"></i>
                                        Llenar este Indicador
                                    </button>
                                </div>
                            @endif



                    {{-- LOGICA DEL BLOQUEO DEL LLENADO DE INDICADORES- SE BLOQUEA SI YA SE LLENO ESTE MES Y SE BLOQUEA SI NO SE HA CARGADO EL EXCEL. --}}



                            <div class="col-auto">
                                <button class="btn btn-success btn-sm w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#informacion_indicador">
                                    <i class="fa fa-eye mx-1"></i>
                                    Ver la informacion que se ocupa para este indicador.
                                </button>
                            </div>


                    </div>
            </div>
        </div>
    </div>

</div>




{{-- Todo apartir de aqui esta fuera de la navBar --}}



<div  class="col-12 mx-2  shadow-sm px-5 py-3 pb-5">
            

<div class="row">

@forelse($grupos as $movimiento => $items)

    @php
        $metas = MetaIndicador::where(
            'id_movimiento_indicador_lleno',
            $items->first()->id_movimiento
        )->first();

        $meta_minima = $metas->meta_minima ?? 0;
        $meta_maxima = $metas->meta_maxima ?? 0;

        Carbon::setLocale('es');
        $fecha = Carbon::parse($items[0]->fecha_periodo);

        $mes  = ucfirst($fecha->translatedFormat('F'));
        $year = $fecha->translatedFormat('Y');
    @endphp


<div class="col-12 col-lg-4 mt-3">
    <div class="card shadow-sm border-0 h-100">
       <!-- HEADER -->
        <div class="card-header bg-info text-white text-center py-2">
            <h6 class="fw-semibold mb-0">
                <i class="fa-solid fa-calendar-days me-1"></i>
                {{ $mes }} {{ $year }}
            </h6>
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
                                    Minimo:  {{ $meta_minima }}
                                </span>
                            </div>
                            <div class="col-6">
                                @if ($meta_maxima == 0)
                                    <span class="badge bg-primary-subtle text-primary p-2 w-100">
                                        <i class="fa-solid fa-exclamation-circle"></i>
                                        Meta:  {{ $meta_maxima }}
                                    </span>
                                        @else    
                                    <span class="badge bg-danger-subtle text-danger p-2 w-100">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        Maximo:  {{ $meta_maxima }}
                                    </span>
                                @endif
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
                    
                            $min = $meta_maxima - $meta_minima;
                            $max = $meta_maxima + $meta_minima;

                            $cumple = $item->informacion_campo >= $min 
                                    && $item->informacion_campo <= $max;   

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
                    
                        <div class=" col-8 bg-  dark border border-2 rounded text-center py-3 my-4
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
                            <i class="fa fa-table"></i> Iniformación Extra
                        </button>
                    </div>

                    <div class="modal fade" id="com{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white py-2">
                                    <h6 class="modal-title">Inidcador: {!! $indicador->nombre !!}</h6>
                                    <button class="btn-close" data-mdb-dismiss="modal"></button>
                                </div>
                                <div class="modal-body small ck-content">
                                    {!! $item->informacion_campo !!}
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
                    <div class="col-6">
                        <div class="border rounded p-2 small">
                            <span class="text-muted">{{ $item->nombre_campo }}</span>
                            <div class="fw-bold format-number">
                                {{ $item->informacion_campo }}
                            </div>
                        </div>
                    </div>
                @endif



            @endforeach

                    
                <div class="col-12">

                    @php
                        //$fechaRegistro = Carbon::parse($items->first()->fecha_periodo);
                        $fechaRegistro = $items->first()->created_at;
                        $mismoMes = $fechaRegistro->isSameMonth(now());
                    @endphp

                    <button class="btn btn-danger w-20 btn-sm"  data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#e{{$items->first()->id_movimiento}}"
                         @if(!$mismoMes)" disabled onclick="alert('No se pueden eliminar registros de meses anteriores')" @endif>
                        <i class="fa fa-trash"></i> 
                    </button>


                    
                </div>

                <div class="modal fade" id="e{{$items->first()->id_movimiento}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h2>¿Eliminar Registro?</h2>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('borrar.info.indicador', $items->first()->id_movimiento)}}" method="POST">
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




            </div>
        </div>
    </div>
</div>

@empty
<div class="col-12 text-center text-muted py-5">
    <i class="fa-solid fa-circle-info"></i> Sin información disponible
</div>
@endforelse

</div>






            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="llenado_indicadores" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h3 class="text-white" id="exampleModalLabel">{{$indicador->nombre}}</h3>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Cloeesdasdse"></button>
            </div>
            <div class="modal-body py-4">

                <form id="formulario_llenado_indicadores" method="POST" action="{{route('llenado.informacion.indicadores', $indicador->id)}}" class="row gap-4 p-2 justify-content-center form-loader">
                    @csrf
                    @forelse ($campos_vacios as $campo_vacio)

                    <div class="col-11 col-sm-11 col-md-4 col-lg-3 mb-4">

                        <div class="p-3 rounded-4 shadow-sm border bg-white campos_vacios h-100">

                            {{-- Nombre del campo --}}
                            <label class="fw-semibold mb-2 text-dark">
                                {{$campo_vacio->nombre}}
                            </label>

                            {{-- Input --}}
                            <input  type="number" step="0.0001"  class="form-control form-control-sm" name="informacion_indicador[]" id="{{$campo_vacio->id_input}}" placeholder="Ingrese {{$campo_vacio->nombre}}" required min="-999999" >

                            {{-- Campos ocultos (NO se tocan) --}}
                            <input type="hidden" name="id_input[]" value="{{$campo_vacio->id}}">
                            <input type="hidden" name="tipo_input[]" value="{{$campo_vacio->tipo}}">
                            <input type="hidden" name="id_input_vacio[]" value="{{$campo_vacio->id_input}}">
                            <input type="hidden" name="nombre_input_vacio[]" value="{{$campo_vacio->nombre}}">

                            {{-- Descripción --}}
                            <div class="mt-2 p-2 bg-light rounded-3">
                                <small class="text-muted">
                                    {{$campo_vacio->descripcion}}
                                </small>
                            </div>

                        </div>

                    </div>

                    @empty
                        <div class="col-11 border border-4 p-5 text-center">
                            <h2>
                                <i class="fa fa-exclamation-circle text-danger"></i>
                                No se encontraron datos
                            </h2>
                        </div>
                    @endforelse



                    @if (!$campos_vacios->isEmpty() )
                        <div class="col-12 bg-light p-3 rounded ql-toolbar">
                            <label> <i class="fa fa-table"></i> Información extra para el Indicador: </label>

                        <div class="form-group">
                            <div id="editor_info_extra"></div>
                            <input type="hidden" name="info_extra" id="info_extra">
                        </div>

                        </div>
                </form>

                <div class="row justify-content-center bg-light  rounded p-4">
                    <div class="col-12">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="fw-bold">Descripción:</span>
                    </div>
                    <div class="col-12">
                        <span>{{$indicador->descripcion}}</span>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button  class="btn btn-primary w-100 py-3" form="formulario_llenado_indicadores" data-mdb-ripple-init>
                    <h6>Guardar</h6>
                </button>
                @endif
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



<div class="modal fade" id="informacion_indicador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog  modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h3 class="text-white" id="exampleModalLabel">{{$indicador->nombre}}</h3>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Cloeesdasdse"></button>
            </div>
            <div class="modal-body py-4 bg-light">

                <div class="row gap-4  justify-content-center ">

                    <div class="col-12 col-sm-12 border col-md-11 col-lg-10 bg-white boder  shadow shadow-sm py-2 px-5">

                        <div class="row justify-content-center">
                            <div class="col-12 text-center my-3">
                                <h4>
                                    <i class="fa fa-exclamation-circle text-primary"></i>
                                    Información para este indicador
                                </h4>
                                <hr>
                            </div>

                            {{-- aqui vamos a consultar los campos precargados --}}

                            @forelse ($campos_unidos as $campo)
                            <div class="col-11 col-sm-11 col-md-5 col-lg-3 border border-4 p-4 shadow-sm m-3">

                                <span class="fw-bold">{{$campo->nombre}}</span>
                                @php

                                    //se tiene que validar todo el desmadre por que de los campos que conformacn campos unidos hay vario que no tienen el campo informacion.
                                    if(CampoPrecargado::where('id_input', $campo->id_input)->latest()->first()){

                                        $precargado = CampoPrecargado::where('id_input', $campo->id_input)->latest()->first();

                                        $info_precargada = InformacionInputPrecargado::where('id_input_precargado', $precargado->id)->latest()->first();
                                    
                                    }
                                    else {
                                        $precargado = null;
                                        $info_precargada = null;
                                    }
                                    
                                @endphp

                                <input type="text" class="form-control"  name="{{$campo->nombre}}" value="{{($info_precargada != null  ?  $info_precargada->informacion : '' )}}" disabled>

                                <small>{{$campo->descripcion}}</small>

                            </div>                    
                            @empty
                                
                            @endforelse

                            <div class="col-12 p-3 bg-light">
                                <p><i class="fa fa-info-circle text-primary"></i> {{$indicador->descripcion}}</p>
                            </div>

                        </div>

                    </div>
                    
                </div>

            </div>
        </div>
    </div>
</div>






{{-- DATOS DEL INDICADOR PARA EL ENVIO DEL CORREO ELECTRONICO. --}}


@endsection











@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const indicador = @json($indicador);
    const datos = @json($graficar);
    const TIPO_INDICADOR = "{{ $indicador->tipo_indicador }}";

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
    const TIPO_INDICADOR = "{{ $indicador->tipo_indicador }}";
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

@endsection