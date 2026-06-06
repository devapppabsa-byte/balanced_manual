@extends('plantilla')
@section('title', 'Registro de Cumplimiento Mensual')

@section('contenido')


<div class="container-fluid sticky-top">
    <div class="row bg-primary d-flex align-items-center justify-content-start ">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  py-4">
            <h5 class="text-white">
                <i class="fa-regular fa-file-lines"></i>
                {{Auth::user()->departamento->nombre}} - {{$norma->nombre}}
            </h5>
            {{-- <h4 class="text-white" id="fecha"></h4> --}}

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



<button class="btn btn-danger flotante" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico" style="z-index: 9999">
    <i class="fa-solid fa-chart-pie fa-2x "></i>
</button>

<div class="modal fade" id="grafico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Gráfica</h5>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <div class="col-12" >
                <!-- Tabs navs -->
                <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark active" id="ex3-tab-1" href="#ex3-tabs-1" role="tab" aria-controls="ex3-tabs-1" aria-selected="true">
                            <i class="fa fa-chart-simple"></i>
                            Grafico de Barras
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link fw-bold h-4 text-dark" id="ex3-tab-2" href="#ex3-tabs-2" role="tab" aria-controls="ex3-tabs-2" aria-selected="false">
                            <i class="fa fa-chart-line"></i>
                            Grafico de Linea
                        </a>
                    </li>

                </ul>
                <!-- Tabs navs -->

                <!-- Tabs content -->
                <div class="tab-content" id="ex2-content border border-4">
                    <div class="tab-pane  show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1" >
                        <div style="width: 100%; height: 400px;"  class="">
                            <canvas id="chartBar"></canvas>
                        </div> 
                    </div>
                    <div class="tab-pane " id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
                        <div style="width: 100%; height: 400px;" class="">
                            <canvas id="chartLinea"></canvas>
                        </div>
                    </div>
                    {{-- <div class="tab-pane " id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
                        <canvas id="chartDonut"></canvas>
                    </div> --}}
                </div>
                <!-- Tabs content -->

            </div>
        </div>
    </div>
  </div>
</div>


<div class="container-fluid">

<div class="row justify-content-center mt-4">
    <div class="col-11 ">

        <div class="card border-0 shadow-sm">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-4 text-center">
                <h4 class="fw-bold text-uppercase mb-2">
                    <i class="fa-regular fa-file-lines text-primary me-2"></i>
                    Apartados de {{ $norma->nombre }}
                </h4>

                <p class="text-muted mb-0" style="text-align: justify; ">
                    {{ $norma->descripcion }}
                </p>

                @if (session('eliminado'))
                    <div class="alert alert-danger alert-sm mt-3 mb-0 d-inline-block">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        {{ session('eliminado') }}
                    </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="card-body p-0">

                @if (!$apartados->isEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="ps-4" style="min-width: 220px;">
                                        <small class="text-muted fw-semibold text-uppercase">Apartado</small>
                                    </th>
                                    <th class="ps-4" style="min-width: 220px;">
                                        <small class="text-muted fw-semibold text-uppercase">Marcar</small>
                                    </th>
                                    <th class="ps-4" style="min-width: 220px;">
                                        <small class="text-muted fw-semibold text-uppercase">Evidencia (no obligatorio)</small>
                                    </th>  
                                    <th class="ps-4" style="min-width: 220px;">
                                        <small class="text-muted fw-semibold text-uppercase">Descripción (no obligatorio)</small>
                                    </th>                                    
                                    <th class="text-center pe-4" style="width: 160px;">
                                        <small class="text-muted fw-semibold text-uppercase">Acciones</small>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                            <form action="{{route('registro.actividad.cumplimiento.norma')}}" id="form_cumplimiento_normativo" method="post" enctype="multipart/form-data" >
                                @csrf
                                @foreach ($apartados as $apartado)
                                    <tr class="border-bottom">

                                        {{-- Apartado --}}
                                        <td class="ps-4 fw-semibold text-dark" style="font-size: 18px">
                                            {{ $apartado->apartado }}
                                        </td>

                                        <td class="ps-4 fw-semibold text-dark">
                                            <div class="form-check">
                                            <input class="form-check-input"
                                            name="realizada[{{ $apartado->id }}]"
                                            value="check"
                                            type="checkbox" value="" id="a{{ $apartado->id }}"/>
                                            <label class="form-check-label" for="a{{ $apartado->id }}">Realizada</label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group mt-4">
                                                <input type="file" 
                                                name="evidencias[{{ $apartado->id }}]" multiple class="form-control" accept=".jpg,.png,.pdf,.docx,.mp4" >
                                            </div>                                            
                                        </td>
                                        <td>
                                            <div class="form-group mt-4">
                                                <div class="form-outline" data-mdb-input-init>
                                                <textarea class="form-control" name="descripcion[{{ $apartado->id }}]" id="text{{ $apartado->id }}" rows="4"></textarea>
                                                <label class="form-label" for="text{{$apartado->id}}">Descripción:</label>
                                                </div>
                                            </div>                                            
                                        </td>                                        

                                        {{-- Descripción --}}


                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('ver.evidencia.cumplimiento.normativo', $apartado->id) }}"
                                                   class="btn btn-outline-primary"
                                                   title="Ver evidencias">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>

                                @endforeach
                                </form>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 text-center py-3 bg-danger">
                        <div class="row justify-content-center">
                            <div class="col-5 text-center">
                                <input type="month" name="fecha" form="form_cumplimiento_normativo" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>

                    <div class="col-12  text-center my-3">
                        <button type="submit" class="btn p-3 btn-primary w-50" form="form_cumplimiento_normativo">
                            Enviar 
                        </button>
                    </div>


                @else
                    {{-- Empty state --}}
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <img src="{{ asset('/img/iconos/emtpy.png') }}"
                                 class="img-fluid"
                                 style="max-width: 180px; opacity: .6;"
                                 alt="Sin datos">
                        </div>
                        <h6 class="text-muted mb-2">
                            No hay apartados registrados
                        </h6>
                        <p class="text-muted mb-0">
                            <small>No cuenta con datos para esta norma.</small>
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>





</div>





{{-- @forelse ($apartados as $apartado)
    <div class="modal fade" id="reg{{$apartado->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary py-4">
                    <h3 class="text-white mb-0 pb-0" id="exampleModalLabel">
                        <i class="fa-regular fa-square-check"></i>
                        Registrar Actividad
                    </h3>
                    <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">

                    <div class="form-group">
                        <div class="form-outline" data-mdb-input-init>
                            <textarea  class="form-control form-control-lg {{ $errors->first('descripcion_actividad') ? 'is-invalid' : '' }} " value="{{old("descripcion_actividad")}}"  name="descripcion_actividad" required></textarea>
                            <label class="form-label" for="descripcion_actividad" >Descripción Actividad <span class="text-danger">*</span> </label>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="bg-light my-2">
                            <i class="fa fa-exclamation-circle"></i>
                            Puedes subir PDF, imagenes, documentos de word y videos de no mas de 10 mb.
                        </label>
                        <input type="file" form="form_cumplimiento_normativo" name="evidencias[]" multiple class="form-control" accept=".jpg,.png,.pdf,.docx,.mp4" required>
                    </div>

                </div>
            </div>
        </div>
    </div>    
@empty
    
@endforelse --}}





{{-- DESDE AQUI LIMPIO LOS DATOS QUE VIENEN DEL CONTROLADOR Y LOS PASO A HTML PARA QUE JS LOS PUEDA TOMAR, SEGURO QUE SE PUEDE HACER DE UNA MEJOR MANERA PERO ASI ESTA BIEN DE MOMENTO. --}}


{{-- <div id="data-norma"
    data-user = "{{Auth::user()->name}}"
    data-correos = '@json($correos)''
    data-correo = "{{Auth::user()->email}}"
     data-norma="{{ $norma->nombre }}"
     data-departamento="{{ Auth::user()->departamento->nombre }}">
</div> --}}


@endsection


@section('scripts')


<script>
//const labels        = @json($labels);
const valores       = @json($valores);
const metaMinima    = Number(@json($metaMinima));
const metaEsperada  = Number(@json($metaEsperada));

const labelsOriginal = @json($labels);
const valoresOriginal = @json($valores);

// convertir a diccionario → { "01-24": 80, ... }
const dataMap = {};
labelsOriginal.forEach((mes, i) => {
    dataMap[mes] = valoresOriginal[i];
});

// año actual (ajústalo si manejas otro)
const anioActual = new Date().getFullYear().toString().slice(-2);
const mesActual = new Date().getMonth() + 1;

// generar los 12 meses
const labelsFull = [];
const valoresFull = [];
const estados = [];

for (let i = 1; i <= 12; i++) {
    const key = String(i).padStart(2, '0') + '-' + anioActual;
    labelsFull.push(key);

    if (dataMap[key] !== undefined) {
        valoresFull.push(dataMap[key]);
        estados.push('ok');
    } else {
        if (i >= mesActual) {
            valoresFull.push(null);
            estados.push('pendiente');
        } else {
            valoresFull.push(0);
            estados.push('sin_registro');
        }
    }
}

const meses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const labelsFormateados = labelsFull.map((fecha, i) => {
    const [mes, anio] = fecha.split('-');
    let texto = `${meses[parseInt(mes) - 1]} 20${anio}`;

    if (estados[i] === 'pendiente') texto += ' (Pendiente)';
    if (estados[i] === 'sin_registro') texto += ' (Sin registro)';

    return texto;
});

console.log(labelsFormateados);

const lineaMinima   = labelsFormateados.map(() => metaMinima);
const lineaEsperada = labelsFormateados.map(() => metaEsperada);



const ctx = document.getElementById('chartBar').getContext('2d');

new Chart(ctx, {
  type: 'bar', // 
  data: {
    labels: labelsFormateados,
    datasets: [
      {
        type: 'bar',
        label: 'Cumplimiento (%)',
        data: valoresFull,
        backgroundColor: (context) => {
          const v = context.raw;
          return v < metaMinima
            ? 'rgba(231, 76, 60, 0.7)'   // rojo
            : 'rgba(46, 204, 113, 0.7)'; // verde
        },
        borderColor: (context) => {
          const v = context.raw;
          return v < metaMinima ? '#c0392b' : '#27ae60';
        },
        borderWidth: 1,
        order: 2 //
      },
      {
        type: 'line',
        label: 'Meta mínima',
        data: lineaMinima,
        borderColor: 'red',
        borderWidth: 2,
        borderDash: [6, 6],
        pointRadius: 0,
        fill: false,
        order: 1 // 
      },
      {
        type: 'line',
        label: 'Meta esperada',
        data: lineaEsperada,
        borderColor: 'green',
        borderWidth: 2,
        borderDash: [4, 4],
        pointRadius: 0,
        fill: false,
        order: 1
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { position: 'top' },
      tooltip: {
        callbacks: {
          label: ctx => ctx.raw + '%'
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        max: 100,
        ticks: {
          callback: v => v + '%'
        }
      }
    }
  }
});


</script>



<script>
const valoresOriginalLinea = @json($valores);
const labelsOriginalLinea  = @json($labels);

const metaMinimaLinea    = Number(@json($metaMinima));
const metaEsperadaLinea  = Number(@json($metaEsperada));

const mesesLinea = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// 🔹 Convertir datos a mapa
const dataMapLinea = {};
labelsOriginalLinea.forEach((mes, i) => {
    dataMapLinea[mes] = valoresOriginalLinea[i];
});

// 🔹 Año y mes actual
const anioActualLinea = new Date().getFullYear().toString().slice(-2);
const mesActualLinea = new Date().getMonth() + 1;

// 🔹 Generar 12 meses completos
const labelsFullLinea = [];
const valoresFullLinea = [];
const estadosLinea = [];

for (let i = 1; i <= 12; i++) {
    const key = String(i).padStart(2, '0') + '-' + anioActualLinea;

    labelsFullLinea.push(key);

    if (dataMapLinea[key] !== undefined) {
        valoresFullLinea.push(dataMapLinea[key]);
        estadosLinea.push('ok');
    } else {
        if (i >= mesActualLinea) {
            valoresFullLinea.push(null);
            estadosLinea.push('pendiente');
        } else {
            valoresFullLinea.push(0);
            estadosLinea.push('sin_registro');
        }
    }
}

// 🔹 Labels bonitos
const labelsFormateadosLinea = labelsFullLinea.map((fecha, i) => {
    const [mes, anio] = fecha.split('-');

    let texto = `${mesesLinea[parseInt(mes) - 1]} 20${anio}`;

    if (estadosLinea[i] === 'pendiente') texto += ' (Pendiente)';
    if (estadosLinea[i] === 'sin_registro') texto += ' (Sin registro)';

    return texto;
});

// 🔹 Líneas meta
const lineaMinimaLinea   = labelsFullLinea.map(() => metaMinimaLinea);
const lineaEsperadaLinea = labelsFullLinea.map(() => metaEsperadaLinea);


// ================== GRÁFICA ==================

const ctxLinea = document.getElementById('chartLinea');

new Chart(ctxLinea, {
  type: 'line',
  data: {
    labels: labelsFormateadosLinea,
    datasets: [
      {
        label: 'Cumplimiento (%)',
        data: valoresFullLinea,
        borderColor: '#36a2eb',
        backgroundColor: 'rgba(54,162,235,0.15)',
        fill: true,
        tension: 0.3,
        pointRadius: 4,
        spanGaps: false,

        pointBackgroundColor: valoresFullLinea.map((v, i) => {
          const estado = estadosLinea[i];

          if (estado === 'pendiente') return '#bdc3c7';
          if (estado === 'sin_registro') return '#2c3e50';

          return v < metaMinimaLinea ? '#e74c3c' : '#2ecc71';
        })
      },
      {
        label: 'Meta mínima',
        data: lineaMinimaLinea,
        borderColor: 'red',
        borderDash: [6, 6],
        borderWidth: 2,
        pointRadius: 0,
        fill: false
      },
      {
        label: 'Meta esperada',
        data: lineaEsperadaLinea,
        borderColor: 'green',
        borderDash: [4, 4],
        borderWidth: 2,
        pointRadius: 0,
        fill: false
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        max: 100,
        ticks: {
          callback: v => v + '%'
        }
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function(ctx) {
            const i = ctx.dataIndex;
            const estado = estadosLinea[i];
            const v = ctx.raw;

            if (estado === 'pendiente') return 'Pendiente';
            if (estado === 'sin_registro') return 'Sin registro';

            return v + '%';
          }
        }
      },
      legend: {
        position: 'top'
      }
    }
  }
});
</script>




@endsection