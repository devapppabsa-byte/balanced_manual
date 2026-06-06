@extends('plantilla')
@section('title', 'Perfil Cliente')
@section('contenido')
@php
    use App\Models\ClienteEncuesta;
@endphp

<div class="container-fluid">
    <div class="row bg-primary  d-flex align-items-center ">
        <div class="col-9 col-sm-9 col-md-8 col-lg-10 pt-2 text-white">
            <h3 class="mt-1 mb-0 text-uppercase">
                {{ Auth::guard("cliente")->user()->nombre}}
            </h3>
            <span>Bienvenido a tus encuestas</span>
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
            @if (session('contestado'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('contestado')}}
                </div>
            @endif

            @if(session('contestada'))
                <div class="text-white fw-bold ">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('contestada')}}
                </div>
            @endif

            @if ($errors->any())
                <div class="text-white fw-bold bad_notifications">
                    <i class="fa fa-xmark-circle mx-2"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        <div class="col-3 col-sm-3 col-md-4 col-lg-2 text-center ">
            <form action="{{route('cerrar.session.cliente')}}" method="POST">
                @csrf 
                <button  class="btn  btn-sm text-danger text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>

    </div>

    @include('client.assets.nav')

    <div class="row bg-white shadow-sm border-bottom">
        <div class="col-12 col-sm-12 col-md-4 col-lg-auto m-1 p-2">
            <a class="btn btn-danger btn-sm w-100 px-3 py-1" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#sugerencia">
                <i class="fa-solid fa-comments"></i>
                Queja o sugerencia
            </a>
        </div>
    </div>

</div>






<div class="container-fluid mt-3">
    <div class="row justify-content-around">

        <div class="col-12 col-sm-12 col-md-8 col-lg-6 mt-2">
            <div class="table-responsive  ">
                @if (count($encuestas) != 0)
                <table class="table  table-responsive  border shadow-sm table-hover">
                        <thead class="bree-serif-regular table-primary">
                            <tr>
                                <th class="text-gray">Titulo Encuesta</th>
                                <th>Estado</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                    <tbody>
                    
                @endif

                    @forelse ($encuestas as $encuesta)
                            @php //Esta logica es engorrosa pero la necesito de momento para diferenciar las encuestas //contestadas de las no contestadas
                                $existe = ClienteEncuesta::where('id_cliente', Auth::guard('cliente')->user()->id)
                                ->where('id_encuesta', $encuesta->id)
                                ->exists();
                            @endphp 
                        @if ($existe)
                                <tr class="table-light">
                                    <td class="fw-bold">
                                        <a href="{{route('index.encuesta.contestada', $encuesta->id)}}" class="text-dark">
                                            <i class="fa fa-check-circle text-success me-3" ></i>
                                            {{$encuesta->nombre}}
                                        </a>
                                    </td>
                                    <td class="fw-bold">
                                        <i class="fa fa-check-circle text-success mx-2"></i>
                                        Contestada
                                    </td>
                                    <td>
                                    <a class="btn btn-success btn-sm " href="{{route('index.encuesta.contestada', $encuesta->id)}}">
                                            <i class="fa fa-eye "></i>            
                                        </a>
                                    </td>
                                </tr>

                            @else
                                <tr>
                                    <td class="fw-bold">
                                        <a href="{{route('index.encuesta', $encuesta->id)}}" class="text-dark">
                                            <i class="fa fa-exclamation-circle text-dark me-3" ></i>
                                            {{$encuesta->nombre}}
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fa fa-exclamation-circle text-danger"></i>
                                        Aún no es contestada
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm " href="{{route('index.encuesta', $encuesta->id)}}">
                                            <i class="fa fa-eye "></i>            
                                        </a>
                                    </td>
                                </tr>
                            @endif
   
                    @empty
                        <div class="col-12 p-5 text-center p-5 border bg-white">
                            <div class="row">
        
                                <div class="col-12">
                                    <i class="fa fa-exclamation-circle text-danger"></i>
                                    No hay encuestas aún.
                                    <img src="{{asset('/img/empty.gif')}}" class="img-fluid" alt="">
                                </div>
                            
                            </div>
                        </div>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-11 col-sm-11 col-md-8 col-lg-5 bg-white rounded-5 shadow border p-2 mt-2">

            <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true">
                    <i class="fa fa-chart-simple"></i>  
                    Barras
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false">
                    <i class="fa fa-chart-line"></i> 
                    Lineas
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-3" href="#ex3-tabs-3" role="tab" aria-controls="ex3-tabs-3" aria-selected="false">
                    <i class="fa fa-circle"></i>  
                    Pie
                    </a>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex2-content">
                <div class="tab-pane  show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1" >
                    <canvas class="" id="grafico"></canvas>
                </div>
                <div class="tab-pane" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                    <canvas  class="" id="chartLinea"></canvas>
                </div>
                <div class="tab-pane " id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
                    <canvas  class="" id="chartPie"></canvas>
                </div>
            </div>
            <!-- Tabs content -->

        </div>
    </div>
</div>


@endsection





@section('scripts')

<script>
const labels = @json($labels);
const data = @json($data);
const minimo = @json($minimo);
const maximo = @json($maximo);

const ctx = document.getElementById('grafico').getContext('2d');

new Chart(ctx, {
    data: {
        labels: labels,
        datasets: [
        {
            type: "bar",
            label: "Satisfacción",
            data: data,

            backgroundColor: function(context) {
                const value = context.raw;
                return value < 5
                    ? "rgba(255, 99, 132, 0.7)"   // rojo
                    : "rgba(75, 192, 75, 0.7)";  // verde
            },
            borderColor: function(context) {
                const value = context.raw;
                return value < 5 ? "red" : "green";
            },
            borderWidth: 1
        },
        {
            type: "line",
            label: "Mínimo",
            data: minimo,
            borderColor: "red",
            borderWidth: 2,
            fill: false
        },
        {
            type: "line",
            label: "Máximo",
            data: maximo,
            borderColor: "green",
            borderWidth: 2,
            fill: false
        }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: "top" }
        },
        scales: {
            y: {
                beginAtZero: true,
                min: 0,
                max: 10
            }
        }
    }
});
</script>









{{-- Grafico de Pie --}}



<canvas id="chartLinea"></canvas>

<script>


const ctx2 = document.getElementById('chartLinea');

new Chart(ctx2, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Satisfacción',
        data: data,
        borderColor: '#36a2eb',
        backgroundColor: 'rgba(54,162,235,0.2)',
        fill: true,
        tension: 0.3
      },
      {
        label: 'Mínimo',
        data: minimo,
        borderColor: 'red',
        borderDash: [5, 5],
        fill: false
      },
      {
        label: 'Máximo',
        data: maximo,
        borderColor: 'green',
        borderDash: [5, 5],
        fill: false
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        min: 0,
        max: 10
      }
    }
  }
});
</script>







{{-- grafico de burbuja --}}





<canvas id="chartPie"></canvas>

<script>
const ctxPie = document.getElementById('chartPie');

new Chart(ctxPie, {
  type: 'pie',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Satisfacción por mes',
        data: data,
        backgroundColor: [
          '#ffce56',
          '#4bc0c0',
          '#9966ff',
          '#ff9f40',
          '#fb6384',
          '#36a2eb',
        ]
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      tooltip: {
        callbacks: {
          label: function(context) {
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const value = context.raw;
            const percent = ((value / total) * 100).toFixed(1);
            return `${context.label}: ${value} (${percent}%)`;
          }
        }
      }
    }
  }
});
</script>




@endsection