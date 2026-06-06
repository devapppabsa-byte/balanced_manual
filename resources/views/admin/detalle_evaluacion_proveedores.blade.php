@extends('plantilla')

@section('title', 'Detalle Evaluacion de Proveedores')

@section('contenido')

<div class="container-fluid">
    <div class="row bg-primary  d-flex align-items-center px-4">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2  text-white">
            <h1 class="mt-1">Evaluaciones de {{ $proveedor->nombre }}.</h1>
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
</div>



<div class="container-fluid ">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 mx-5 mt-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <h2 class="mb-1 fw-bold">
                                <i class="fa-solid fa-truck text-primary me-2"></i>
                                 Detalle evaluaciónes del proveedor
                            </h2>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico">
                                <i class="fa-solid fa-chart-simple me-2"></i>
                                Ver Grafico
                            </button>
                        </div>
                    </div>
                </div>
            </div>




                <div class="card border-0 shadow-sm mb-2 mt-3">
                    <div class="card-body">
                        <form action="#"  method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-4">
                                    <label for="fecha_inicio" class="form-label fw-semibold small text-muted text-uppercase">Fecha Inicio</label>
                                    <input type="date"
                                            name="fecha_inicio"
                                            value="{{request('fecha_inicio')}}"
                                            class="form-control datepicker"
                                            id="fecha_inicio">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="fecha_fin" class="form-label fw-semibold small text-muted text-uppercase">Fecha Final</label>
                                    <input type="date"
                                            name="fecha_fin"
                                            value="{{request('fecha_fin')}}"
                                            class="form-control datepicker"
                                            id="fecha_fin">
                                </div>
                                <div class="col-12 col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa-solid fa-filter me-2"></i>
                                        Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


        </div>
    </div>


<div class="row justify-content-center">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 mx-5">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">

                    @if (!$evaluaciones->isEmpty())
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="ps-4" style="min-width: 150px;">
                                        <small class="text-muted fw-semibold text-uppercase">
                                            <i class="fa fa-calendar me-1"></i> Fecha
                                        </small>
                                    </th>
                                    <th style="min-width: 200px;">
                                        <small class="text-muted fw-semibold text-uppercase">
                                            Descripción
                                        </small>
                                    </th>
                                    <th class="text-center" style="width: 160px;">
                                        <small class="text-muted fw-semibold text-uppercase">
                                            Calificación
                                        </small>
                                    </th>
                                    <th class="pe-4">
                                        <small class="text-muted fw-semibold text-uppercase">
                                            Observaciones
                                        </small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                    @endif

                    @forelse ($evaluaciones as $evaluacion)
                        @php
                            $esBaja = $evaluacion->calificacion < 80;
                        @endphp
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <span class="fw-semibold text-dark">
                                    {{ $evaluacion->fecha }}
                                </span>
                            </td>

                            <td>
                                <small class="text-muted">
                                    {{ Str::limit($evaluacion->descripcion, 80) }}
                                </small>
                            </td>

                            <td class="text-center">
                                <span class="badge fs-6 border border-2 fw-semibold
                                    {{ $esBaja
                                        ? 'bg-danger bg-opacity-10 text-danger border-danger'
                                        : 'bg-success bg-opacity-10 text-success border-success' }}">
                                    <i class="fa-solid {{ $esBaja ? 'fa-triangle-exclamation' : 'fa-check-circle' }} me-1"></i>
                                    {{ $evaluacion->calificacion }} pts
                                </span>
                            </td>

                            <td class="pe-4">
                                <small class="text-muted">
                                    {{ $evaluacion->observaciones ?? '—' }}
                                </small>
                            </td>
                        </tr>
                    @empty
                        </tbody>
                        </table>

                        <div class="p-5 text-center">
                            <div class=" d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="fa fa-exclamation-circle text-danger"></i>
                            </div>
                            <h6 class="text-muted mb-0">
                                No cuenta con evaluaciones.
                            </h6>
                        </div>
                    @endforelse

                    @if (!$evaluaciones->isEmpty())
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>





</div>










<div class="modal fade" id="grafico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-centered  modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Gráfica</h5>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <div class="col-12 pb-5 px-5 pt-2" >
                <!-- Tabs navs -->
                <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true">
                            <i class="fa-solid fa-chart-simple"></i>
                            Grafico de Barras
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false">
                            <i class="fa fa-chart-line"></i>
                            Grafico de Linea
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-3" href="#ex3-tabs-3" role="tab" aria-controls="ex3-tabs-3" aria-selected="false">
                            <i class="fa fa-circle"></i>
                            Grafico de Pie
                        </a>
                    </li>
                </ul>
                <!-- Tabs navs -->

                <!-- Tabs content -->
                <div class="tab-content" id="ex2-content">
                    <div class="tab-pane  show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1" >
                        <canvas id="barLineChart"></canvas>
                    </div>
                    <div class="tab-pane" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                        <canvas id="lineChart"></canvas>
                    </div>
                    <div class="tab-pane " id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
                <!-- Tabs content -->

            </div>
        </div>
    </div>
  </div>
</div>

@endsection




@section('scripts')

<script>
/* =============================
   DATOS DESDE LARAVEL
============================= */
const evaluaciones = @json($evaluaciones);

/* =============================
   CONSTANTES
============================= */
const mesesES = [
  'Enero','Febrero','Marzo','Abril','Mayo','Junio',
  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
];

const META = 80;

/* =============================
   AGRUPAR POR MES
============================= */
const porMes = {};

evaluaciones.forEach(e => {
    const fecha = new Date(e.created_at);
    const mes = fecha.getMonth();

    if (!porMes[mes]) porMes[mes] = [];
    porMes[mes].push(Number(e.calificacion));
});

/* =============================
   LABELS Y PROMEDIOS
============================= */
const labels = Object.keys(porMes).map(m => mesesES[m]);

const promedios = Object.values(porMes).map(arr =>
    Number((arr.reduce((a, b) => a + b, 0) / arr.length).toFixed(1))
);

/* =============================
   COLORES SEGÚN META
============================= */
const coloresPorValor = promedios.map(valor =>
    valor >= META
        ? 'rgba(75, 192, 75, 0.7)'   // verde
        : 'rgba(255, 99, 132, 0.7)'  // rojo
);

const metas = labels.map(() => META);

/* =============================
   GRÁFICA BAR + LINE
============================= */
const ctx1 = document.getElementById('barLineChart');

new Chart(ctx1, {
    data: {
        labels: labels,
        datasets: [
            {
                type: 'bar',
                label: 'Puntuación obtenida',
                data: promedios,
                backgroundColor: coloresPorValor,
                borderRadius: 5
            },
            {
                type: 'line',
                label: 'Meta esperada (80%)',
                data: metas,
                borderColor: 'red',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 5
            }
        ]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Cumplimiento del Proveedor'
            }
        },
        scales: {
            y: { beginAtZero: true, max: 100 }
        }
    }
});

/* =============================
   GRÁFICA PIE
============================= */
const ctx2 = document.getElementById('pieChart');

new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Distribución del Cumplimiento (%)',
            data: promedios,
            backgroundColor: coloresPorValor,
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Distribución del Cumplimiento por Mes'
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        const valor = context.parsed;
                        const estado = valor >= META ? 'Cumple' : 'No cumple';
                        return `${context.label}: ${valor}% ${estado}`;
                    }
                }
            }
        }
    }
});

/* =============================
   GRÁFICA LINE
============================= */
const ctx3 = document.getElementById('lineChart');

new Chart(ctx3, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Cumplimiento general (%)',
            data: promedios,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 6,
            pointBackgroundColor: coloresPorValor,
            pointBorderColor: coloresPorValor
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Evolución del Cumplimiento Mensual'
            }
        },
        scales: {
            y: { beginAtZero: true, max: 100 },
            x: { title: { display: true, text: 'Meses' } }
        }
    }
});





    </script>
@endsection