@extends('plantilla')
@section('title', 'Perfil del usuario')
@section('contenido')
@php
    use App\Models\IndicadorLleno;
    use App\Models\CampoForaneoInformacion;
@endphp
<div class="container-fluid sticky-top ">
    <div class="row bg-primary d-flex align-items-center justify-content-start ">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-3">
            <h5 class="text-white">Balance General de {{Auth::user()->departamento->nombre}}</h5>
            {{-- <h6 class="text-white fw-bold" id="fecha"></h6> --}}
            <span class="cascadia-code text-white">Bienvenido(a):  {{ Auth::user()->name }} - {{ Auth::user()->puesto }}</span>
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

    @include('user.assets.nav')

</div>




<div class="container-fluid border-bottom pt-2 bg-white shadow shadow-sm ">
     <div class="row">
        <div class="col-12 ">
            <h6>Indicadores de {{Auth::user()->departamento->nombre}}</h6>
        </div>
     </div>

     {{-- esta es la row de llos indicadores --}}
    <div class="row">
      
      @if ($ponderacion == 100)
       
        {{--
        Aqui YACEN los ShortCuts de los antiguos ibndicadores
        @forelse ($indicadores_list as $indicador)
        @php

                $ultima_carga_indicador = IndicadorLleno::where('id_indicador', $indicador->id)->latest()->first();  
                $carga_indicador = $ultima_carga_indicador?->created_at?->format('Y-m') ?? '0000-00';
                $ahora = now()->format('Y-m');
        @endphp     

        @if ($carga_indicador === $ahora)

            @if ($carga_indicador === $ahora)
              <div class="col-auto m-2">
                
                <a href="{{route('show.indicador.user', $indicador->id)}}" class="btn btn-primary btn-sm">
                  <i class="fa fa-check"></i>  {{$indicador->nombre}}
                </a>
              </div>
            @endif


        @else
          <div class="col-auto m-2">
            <a href="{{route('show.indicador.user', $indicador->id)}}" class="btn btn-outline-primary btn-sm">
              {{$indicador->nombre}}
            </a>
          </div>
        @endif

        @empty
          <li>No hay datos </li>
        @endforelse --}}









        @forelse ($indicadores_list as $indicador)
        @php

                $ultima_carga_indicador = IndicadorLleno::where('id_indicador', $indicador->id)->latest()->first();  
                $carga_indicador = $ultima_carga_indicador?->created_at?->format('Y-m') ?? '0000-00';
                $ahora = now()->format('Y-m');
        @endphp     

        @if ($carga_indicador === $ahora)



          @if ($carga_indicador === $ahora)
            <div class="col-auto m-2">
              
              <a href="{{route('show.indicador.robusto.user', $indicador->id)}}" class="btn btn-primary btn-sm">
                <i class="fa fa-check"></i>  {{$indicador->nombre}}
              </a>
            </div>
          @endif


        @else
          <div class="col-auto m-2">
            <a href="{{route('show.indicador.robusto.user', $indicador->id)}}" class="btn btn-outline-primary btn-sm">
              {{$indicador->nombre}}
            </a>
          </div>
        @endif


        @empty
          <li>No hay datos </li>
        @endforelse





      @else

          <div class="alert alert-danger border border-2 border-danger">
            <i class="fa fa-info-circle"></i>
            La sumatoria de la ponderación no da 100%. Actualmente da <b>  {{$ponderacion}}%</b>
          </div>

      @endif
    </div>
   {{-- esta es la row de llos indicadores --}}




</div>


<div class="container-fluid py-2"><div class="row">
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-11 col-lg-7  shadow   bg-white py-4" >

        <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-mdb-tab-init href="#tab-barras" role="tab">
              <i class="fa-solid fa-chart-column me-2"></i>Barras
            </a>
          </li>
          {{-- <li class="nav-item">
            <a class="nav-link" data-mdb-tab-init href="#tab-linea" role="tab">
              <i class="fa-solid fa-chart-line me-2"></i>Línea
            </a>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link" data-mdb-tab-init href="#tab-pie" role="tab">
              <i class="fa-solid fa-chart-pie me-2"></i>Pie
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-mdb-tab-init href="#tab-donut" role="tab">
              <i class="fa-solid fa-chart-donut me-2"></i>Donut
            </a>
          </li>
        </ul>

        <!-- Tabs content -->
        <div class="tab-content">

          <div class="tab-pane fade show active" id="tab-barras">
            <canvas id="chartBar" height="120"></canvas>
          </div>

          <div class="tab-pane fade" id="tab-linea">
            <canvas id="chartLine" height="120"></canvas>
          </div>

          <div class="tab-pane fade" id="tab-pie" >
            <div class="p-5 text-center row justify-content-center" style="max-height: 700px">
                <canvas id="chartPie" height="120"></canvas>
            </div>
          
        </div>

          <div class="tab-pane fade" id="tab-donut">
            <div class="p-5 text-center row justify-content-center" style="max-height: 700px">
                <canvas id="chartDonut" height="120"></canvas>
            </div>
          </div>

        </div>
      </div>

        </div>
    </div>
</div>






@endsection



@section('scripts')

<script>

    const cumplimientoData = @json($cumplimiento_general);

    const labels = cumplimientoData.map(item => {
        const [year, month] = item.mes.split('-');

        // month - 1 porque JS empieza en 0
        const fecha = new Date(Number(year), Number(month) - 2, 1);

        const formatted = new Intl.DateTimeFormat('es-MX', {
            month: 'long',
            year: 'numeric'
        }).format(fecha);

        return formatted.charAt(0).toUpperCase() + formatted.slice(1);
    });

    const dataValues = cumplimientoData.map(item => item.total);


</script>



<script>
document.addEventListener('DOMContentLoaded', () => {

  // BARRAS
  new Chart(document.getElementById('chartBar'), {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Promedio de Resultados',
        data: dataValues,
        backgroundColor: '#e8e8e8'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, max: 100 }
      }
    }
  });



  // LINEA
new Chart(document.getElementById('chartLine'), {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Tendencia',
      data: dataValues,
      borderColor: '#e8e8e8',
      fill: false,
      tension: 0.3
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        min: 0,
        max: 100,
        ticks: {
          stepSize: 10
        }
      }
    },
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  }
});





  // PIE
  new Chart(document.getElementById('chartPie'), {
    type: 'pie',
    data: {
      labels,
      datasets: [{
        data: dataValues,
        backgroundColor: [
        '#c9c9c9', // primary dark
        '#e8e8e8', // primary
        '#f2eded', // primary light
        '#a8a5a5'  // primary softer
        ]


      }]
    },
    options:{
        responsive:true,
        maininAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
  });



  // DONUT
  new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data: dataValues,
      backgroundColor: [
              '#c9c9c9', // primary dark
              '#e8e8e8', // primary
              '#f2eded', // primary light
              '#a8a5a5'  // primary softer
              ]
      }]
    },
    options:{
        responsive:true,
        maininAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
  });

});
</script>

@endsection