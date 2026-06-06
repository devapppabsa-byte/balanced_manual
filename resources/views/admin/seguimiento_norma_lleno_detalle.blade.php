@extends('plantilla')
@section('title', 'Detalle del Indicador')
@section('contenido')  

<div class="container-fluid sticky-top">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-4">
            <h1 class="text-white">Detalle del</h1>

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
</div>





<div class="container-fluid">

<div class="row bg-white">
    <div class="accordion p-0 m-0 bg-white" id="accordionExample">
        <div class="">
            <div class="accordion-header text-end border" id="headingTwo">
                <a data-mdb-collapse-init class="fw-bold  collapsed m-2" type="button" data-mdb-target="#info_precargada" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fa-solid fa-circle-arrow-down"></i>
                    Información precargada por el administrador
                </a>
            </div>
            <div id="info_precargada" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row gap-4  justify-content-start d-flex align-items-center">
                        @forelse ($campos_llenos as $campo_lleno)
                        <div class="col-2 p-2 text-center  bg-white mb-4 border">
                            <h5 class="fw-bold">{{$campo_lleno->nombre}}</h5>
                            <h5  class="lh-1">{{$campo_lleno->informacion_precargada}}</h5>
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





    <div class="row  pb-5  mt-2 d-flex align-items-center justify-content-around">
        

        <div class="col-11 col-sm-10 col-md-9 col-lg-5 mt-5 shadow p-5 bg-white" >
            <div class="col-auto ">
                <h5 class="my-2">
                    <i class="fa-solid fa-chart-simple"></i>
                    Seguimiento del Indicador de Ventas
                </h5>
                <div class="table-responsive p-0 border shadow-sm ">
                <table class="table">
                    <thead class="table-primary">
                    <tr>
                        <th scope="col">Mes</th>
                        <th scope="col">Toneladas Presupuestadas</th>
                        <th scope="col">Toneladas Producidas</th>
                        <th scope="col">% Cumplimiento</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <th scope="row">Julio</th>
                        <td>4000</td>
                        <td>3950</td>
                        <td class="text-success fw-bold">98.75%</td>
                    </tr>                    

                    <tr>
                        <th scope="row">Agosto</th>
                        <td>4000</td>
                        <td>2000</td>
                        <td class="text-danger fw-bold">50%</td>
                    </tr>
                    <tr>
                        <th scope="row">Septiembre</th>
                        <td>4000</td>
                        <td>3950</td>
                        <td class="text-success fw-bold">98.75%</td>
                    </tr>
                    <tr>
                        <th scope="row">Octubre</th>
                        <td>4000</td>
                        <td>3950</td>
                        <td class="text-success fw-bold">98.75%</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>








        <div class="col-11 col-sm-10 col-md-9 col-lg-5 mt-5 shadow px-5 pb-5 pt-4 bg-white" >
            <!-- Tabs navs -->
            <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true">
                        Grafico de Barras
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false">
                        Grafico de Pie
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-3" href="#ex3-tabs-3" role="tab" aria-controls="ex3-tabs-3" aria-selected="false">
                        Grafico de Burbuja
                    </a>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex2-content">
                <div class="tab-pane  show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1" >
                    <canvas class="w-100 h-100" id="grafico"></canvas>
                </div>
                <div class="tab-pane  p-5" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                   <div class="row justify-content-center">
                        <div class="col-8 text-center">
                            <canvas id="pieChart"></canvas>
                        </div>
                   </div>
                </div>
                <div class="tab-pane " id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
                    <canvas id="bubbleChart"></canvas>
                </div>
            </div>
            <!-- Tabs content -->

        </div>

    </div>
</div>


@endsection





@section('scripts')

<script>
    const ctx = document.getElementById('grafico').getContext('2d');

    new Chart(ctx, {
    data: {
        labels: ["Enero", "Febrero", "Marzo", "Abril"],
        datasets: [
        {
            type: "bar",  // Barras
            label: "Ventas",
            data: [30, 50, 40, 60],
            backgroundColor: "rgba(75, 192, 192, 0.5)"
        },
        {
            type: "line", // Línea sobrepuesta
            label: "Minimo",
            data: [50, 50, 50, 50],
            borderColor: "red",
            borderWidth: 2,
            fill: false
        },
        {
            type: "line", // Línea sobrepuesta
            label: "Maximo",
            data: [100, 100, 100, 100],
            borderColor: "blue",
            borderWidth: 2,
            fill: false
        }
        ]
    },
    options: {
        responsive: true,
        plugins: {
        legend: { position: "top" }
        }
    }
    });
</script>








{{-- Grafico de Pie --}}

<script>

const ctxPie = document.getElementById('pieChart').getContext('2d');

new Chart(ctxPie, {
  type: 'pie',
  data: {
    labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
    datasets: [{
      label: 'Ventas',
      data: [30, 50, 35, 60],
      backgroundColor: [
        'rgba(255, 99, 132, 0.6)',
        'rgba(54, 162, 235, 0.6)',
        'rgba(255, 206, 86, 0.6)',
        'rgba(75, 192, 192, 0.6)'
      ],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      title: {
        display: true,
        text: 'Gráfico de Pie - Ventas'
      }
    }
  }
});
</script>





{{-- grafico de burbuja --}}


<script>
const ctxBubble = document.getElementById('bubbleChart').getContext('2d');

new Chart(ctxBubble, {
  type: 'bubble',
  data: {
    datasets: [{
      label: 'Ventas',
      data: [
        { x: 1, y: 30, r: 10 },   // Enero
        { x: 2, y: 50, r: 15 },   // Febrero
        { x: 3, y: 35, r: 12 },   // Marzo
        { x: 4, y: 60, r: 18 }    // Abril
      ],
      backgroundColor: 'rgba(75, 192, 192, 0.6)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 2
    }]
  },
  options: {
    scales: {
      x: { title: { display: true, text: 'Mes' } },
      y: { title: { display: true, text: 'Ventas' }, beginAtZero: true }
    },
    plugins: {
      title: {
        display: true,
        text: 'Gráfico de Burbuja - Ventas'
      },
      legend: { position: 'bottom' }
    }
  }
});
</script>

@endsection