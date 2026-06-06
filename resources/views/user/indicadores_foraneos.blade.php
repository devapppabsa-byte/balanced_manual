@extends('plantilla')
@section('title', 'Indicadores Foraneos')
@section('contenido')
<div class="container-fluid">
    <div class="row bg-primary d-flex align-items-center">

        <div class="col-8 col-sm-8 col-md-6 col-lg-9  py-4  py-4 ">
            <h2 class="text-white"> Indicadores Foraneos</h2>
            <h5 class="text-white fw-bold" id="fecha"></h5>
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
</div>  


{{-- <div class="container">
    <div class="row">
        @forelse ($indicadores_foraneos_agregados as $indicador_foraneo)
            <div class="col-auto">
                <pre>{{ $indicador_foraneo }}</pre>
            </div>
        @empty
            
        @endforelse
    </div>
</div> --}}


<div class="container-fluid">
    <div class="row jusfify-content-center">
        @forelse ($indicadores_foraneos_agregados as $indicador)
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

                    <div class="card text-white {{($cumplimiento >= ($indicador->meta_esperada - $indicador->meta_minima)&& $cumplimiento <= ($indicador->meta_esperada + $indicador->meta_minima)) ? 'bg-success' : 'bg-danger'}} shadow-2-strong">
                    
                    
                        <a href="{{ route('analizar.indicador.usuario', $indicador->id) }}" class="text-white w-100">
                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 ">
                                    <h3 class="archivo-font"> {{ $indicador->departamento->nombre }}</h3>
                                    <h4 class="card-text fw-bold">{{$indicador->nombre}}</h4>
                                    
                                        <span class="card-title fw-bold display-6 mt-3 h3">

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
                                <div class="col-auto mx-2">
                                    <a href="#" class="text-white" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Ver historial" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#detall{{ $indicador->id }}">
                                        <i class="fa-solid fa-list"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                @else




                @if ($indicador->tipo_indicador === "riesgo")

                <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                    <div class="card text-white {{($cumplimiento <= $indicador->meta_minima) ? 'bg-success' : 'bg-danger'}} shadow-2-strong">
                     <a href="{{ route('analizar.indicador.usuario', $indicador->id) }}" class="text-white w-100">

                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 ">
                                    <h3 class="archivo-font"> {{ $indicador->departamento->nombre }}</h3>
                                    <h4 class="card-text fw-bold">{{$indicador->nombre}}</h4>
                                        <span class="card-title fw-bold display-6 mt-3 h3">

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
                                <div class="col-auto mx-2">
                                    <a href="#" class="text-white" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Ver historial" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#detall{{ $indicador->id }}">
                                        <i class="fa-solid fa-list"></i>
                                    </a>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
                


                @else
                <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3">
                    <div class="card text-white {{($cumplimiento < $indicador->meta_minima) ? 'bg-danger' : 'bg-success'}} shadow-2-strong">
                         <a href="{{ route('analizar.indicador.usuario', $indicador->id) }}" class="text-white w-100">

                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 ">
                                    <h3 class="archivo-font"> {{ $indicador->departamento->nombre }}</h3>
                                    <h4 class="card-text fw-bold">{{$indicador->nombre}}</h4>
                                        <span class="card-title fw-bold display-6 mt-3 h3">

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
                                    <div class="col-auto mx-2">
                                        <a href="#" class="text-white" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Ver historial" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#detall{{ $indicador->id }}">
                                            <i class="fa-solid fa-list"></i>
                                        </a>
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
                    <a href="{{ route('analizar.indicador.usuario', $indicador->id) }}" class="text-white w-100">
                        <div class="card-body">
                            <div class="row justify-content-around d-flex align-items-center">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-7 ">
                                    <h3 class="archivo-font"> {{ $indicador->departamento->nombre }}</h3>
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

            <div class="col-12 text-center h3 mt-5 ">
                <i class="fa fa-exclamation-circle"></i>
                No hay indicadores asignados
            </div>


        @endforelse








{{-- Foreach de las encuestas --}}





    </div>






</div>

@endsection