@extends('plantilla')
@section('title', 'Perspectivas')
@section('contenido')
@php
    use App\Models\Indicador;
    use App\Models\Norma;
    use App\Models\Encuesta;
    use App\Models\IndicadorLleno;
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
    <div class="row bg-primary  d-flex align-items-center py-3">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2 text-white">
            <h1 class="mt-1 mb-0 league-spartan">Todos los KPI</h1>
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

    <div class="row p-3 bg-white justify-content-center">
        <div class="col-10">
            <div class="input-group input-group-lg">
                <span class="input-group-text">
                    <i class="fa fa-search"></i>
                </span>
                <input type="search"
                    id="buscador"
                    placeholder="Buscar por nombre o cumplimiento: verde, rojo"
                    class="form-control w-100">
            </div>
        </div>
    </div>



</div>




</div>





<div class="container-fluid">

    <div class="row">
        @forelse ($indicadores as $indicador)

        @php
            $semaforo = "";
            $estado = "";

            $valor = $indicador->indicadorLleno->first()?->informacion_campo;

            if($indicador->tipo_indicador == "riesgo"){

                if($indicador->meta_esperada <= $valor){
                    $semaforo = "bg-danger";
                    $estado = "rojo";
                }

                if($indicador->meta_esperada > $valor){
                    $semaforo = "bg-success";
                    $estado = "verde";
                }
            }

            if($indicador->tipo_indicador == "normal"){

                if($indicador->meta_esperada < $valor){
                    $semaforo = "bg-success";
                    $estado = "verde";
                }

                if($indicador->meta_esperada >= $valor){
                    $semaforo = "bg-danger";
                    $estado = "rojo";
                }
            }
        @endphp
                
            <div class="col-10 col-sm-10 col-md-6 col-lg-4 my-3 indicador_card" data-tipo="{{ $indicador->tipo_indicador }}"  data-estado="{{ $estado }}" data-planta="{{ $indicador->planta }}"  data-departamento="{{ $indicador->departamento->nombre }}">
                <div class="card text-white {{ $semaforo }} shadow-2-strong">
                    <a href="{{route('analizar.indicador', $indicador->id)}}" class="text-white w-100">

                    <div class="card-body">
                        <div class="row justify-content-around d-flex align-items-center">
                            <div class="col-12 ">
                                <h6 class="card-text fw-bold">{{ $indicador->departamento->nombre }}</h6>
                                <h4 class="card-text fw-bold nombre">{{$indicador->nombre}}   </h4>

                                <span class="card-title fw-bold display-6 mt-3  resultado">

                                    @if($indicador->unidad_medida === 'pesos')
                                        ${{ number_format($indicador->indicadorLleno->first()?->informacion_campo, 2) }}

                                    @elseif($indicador->unidad_medida === 'porcentaje')
                                        {{ number_format($indicador->indicadorLleno->first()?->informacion_campo, 2) }}%

                                    @elseif($indicador->unidad_medida === 'dias')
                                        {{ number_format($indicador->indicadorLleno->first()?->informacion_campo, 2) }} Días

                                    @elseif($indicador->unidad_medida === 'toneladas')
                                        {{ number_format($indicador->indicadorLleno->first()?->informacion_campo, 2) }} Ton.

                                    @else
                                        {{ number_format($indicador->indicadorLleno->first()?->informacion_campo, 2) }}
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
                                {{-- <div class="col-auto mx-2">
                                    <a href="#" class="text-white" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Ver historial" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#detall{{ $indicador->id }}">
                                        <i class="fa-solid fa-list"></i>
                                    </a>
                                </div> --}}
                            </div>
                    </div>                        
                </div>
            </div>


        @empty
            
        @endforelse

    </div>


</div>



@endsection



@section('scripts')
<script>
document.getElementById('buscador').addEventListener('input', function () {
    let filtro = this.value.toLowerCase().trim();
    let cards = document.querySelectorAll('.indicador_card');

    cards.forEach(card => {

        // texto visible
        let nombre = card.querySelector('.nombre').textContent.toLowerCase();

        // datos ocultos
        let tipo = (card.dataset.tipo || '').toLowerCase();
        let estado = (card.dataset.estado || '').toLowerCase();
        let planta = (card.dataset.planta || '').toLowerCase();
        let departamento = (card.dataset.departamento || '').toLowerCase();
        // combinamos todo lo que puede buscarse
        let contenido = `${nombre} ${tipo} ${estado} ${planta} ${departamento}`;

        // filtro
        if (contenido.includes(filtro)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
@endsection