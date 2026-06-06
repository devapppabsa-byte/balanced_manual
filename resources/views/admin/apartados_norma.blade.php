@extends('plantilla')
@section('title', 'Apartados de la Norma')

@section('contenido')

<button class="btn btn-danger flotante" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#grafico">
    <i class="fa-solid fa-chart-pie fa-2x "></i>
</button>


<div class="container-fluid">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10  pt-2">
            <h3 class="text-white league-spartan">
                {{$norma->nombre}}
            </h3>

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
    <div class="row  border-bottom mb-5 bg-white">
        <div class="col-12 col-sm-12 col-md-4 col-lg-auto my-1">
            <button class="btn btn-sm btn-secondary w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#agregar_apartado_norma">
                <i class="fa fa-plus-circle"></i>
                Agregar Apartado Norma
            </button>
        </div>
    </div>
</div>


<div class="container my-4">
    <div class="card border-0 shadow-sm rounded-4">

        <!-- Header -->
        <div class="card-header bg-white border-0 text-center px-4 pt-4">
            <h3 class="fw-bold mb-2">
                Apartados de la norma
                <span class="text-primary">{{ $norma->nombre }}</span>
            </h3>

            <p class="text-muted mx-auto" >
                {{ $norma->descripcion }}
            </p>
        </div>

        <!-- Body -->
        <div class="card-body px-4 pb-4">

            @if (!$apartados->isEmpty())
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

            @forelse ($apartados as $apartado)
                <tr>
                    <td class="fw-semibold">
                        <a href="{{ route('ver.evidencia.cumplimiento.normativo.admin', $apartado->id) }}"
                           class="text-decoration-none text-dark">
                            {{ $apartado->apartado }}
                        </a>
                    </td>

                    <td class="text-muted">
                        {{ Str::limit($apartado->descripcion, 100) }}
                    </td>

                    <td class="text-center">
                        <button class="btn btn-outline-danger btn-sm mx-1"
                                data-mdb-ripple-init
                                data-mdb-modal-init
                                data-mdb-target="#del_ap{{ $apartado->id }}"
                                title="Eliminar">
                            <i class="fa fa-trash"></i>
                        </button>

                        <button class="btn btn-outline-primary btn-sm"
                                data-mdb-ripple-init
                                data-mdb-modal-init
                                data-mdb-target="#edit_ap{{ $apartado->id }}"
                                title="Editar">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <!-- Empty state -->
                <tr>
                    <td colspan="3">
                        <div class="text-center py-5">
                            <img src="{{ asset('/img/iconos/empty.png') }}" class="img-fluid mb-3" style="max-width: 180px;">
                            <h5 class="fw-bold">
                                <i class="fa fa-circle-exclamation text-danger me-1"></i>
                                No hay apartados registrados
                            </h5>
                            <p class="text-muted mb-3">
                                Aún no existen apartados para esta norma, puedes agregar uno nuevo.
                            </p>

                            <button class="btn btn-secondary btn-sm"
                                    data-mdb-ripple-init
                                    data-mdb-modal-init
                                    data-mdb-target="#agregar_apartado_norma">
                                <i class="fa fa-plus me-1"></i>
                                Agregar apartado
                            </button>
                        </div>
                    </td>
                </tr>
            @endforelse

            @if (!$apartados->isEmpty())
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>





<div class="modal fade" id="agregar_apartado_norma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary py-4">
        <h3 class="text-white" id="exampleModalLabel">Agregar Apartado</h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form action="{{route('apartado.norma.store', $norma->id)}}" method="post">
            @csrf
            <div class="row">

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" min="0" max="100" value="{{old('titulo_apartado_norma')}}" class="form-control w-100 {{ $errors->first('titulo_apartado_norma') ? 'is-invalid' : '' }} " name="titulo_apartado_norma" required>
                            <label class="form-label" for="titulo_apartado_norma" >Titulo Apartado Norma</label>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <div class="form-outline" data-mdb-input-init>
                                <textarea class="form-control w-100 {{ $errors->first('descripcion_norma') ? 'is-invalid' : '' }}" id="descripcion_apartado_norma" name="descripcion_apartado_norma" >{{old('descripcion_norma')}}.</textarea>
                                <label class="form-label" for="descripcion_apartado_norma">Descripción del apartado</label>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <div class="modal-footer">
            <button  class="btn btn-primary w-100 py-3" data-mdb-ripple-init>
                <h6>Guardar</h6>
            </button>
        </form>

      </div>
    </div>
  </div>
</div>





<!-- Modal -->
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




@forelse ($apartados as $apartado)

    <div class="modal fade" id="del_ap{{$apartado->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger py-4">
                    <h3 class="text-white" id="exampleModalLabel">¿Eliminar {{$apartado->nombre}} ?</h3>
                    <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="{{route('delete.apartado.norma', $apartado->id)}}" method="POST">
                        @csrf @method('DELETE')
                        <button  class="btn btn-danger w-100 py-3" data-mdb-ripple-init>
                            <h6>Eliminar</h6>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_ap{{$apartado->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h5 class="text-white" id="exampleModalLabel">Editar  {{$apartado->apartado}}</h5>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{route('edit.apartado.norma', $apartado->id)}}" method="POST">
                    @csrf @method('PATCH')
                    
                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="text" class="form-control form-control-lg w-100{{ $errors->first('nombre_apartado_edit') ? 'is-invalid' : '' }} " id="nombre_apartado{{$apartado->id}}" name="nombre_apartado_edit" value="{{old('nombre_apartado_edit', $apartado->apartado)}}">
                            <label class="form-label" for="nombre_apartado_edit{{$apartado->id}}" >Editando apartado </label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="form-outline" data-mdb-input-init>
                            <textarea type="text" class="form-control form-control-lg w-100{{ $errors->first('descripcion_apartado_edit') ? 'is-invalid' : '' }} " id="descripcion_apartado{{$apartado->id}}" name="descripcion_apartado_edit" value="{{old('descripcion_apartado_edit', $apartado->apartado)}}">{{$apartado->descripcion}}</textarea>
                            <label class="form-label" for="descripcion_apartado_edit{{$apartado->id}}" >Editando apartado </label>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button  class="btn btn-primary w-100 btn-lg" data-mdb-ripple-init>
                            <i class="fa fa-pencil mx-2"></i>
                            Editar
                        </button>
                    </div>


                </form>
            </div>
            {{-- <div class="modal-footer">
            </div> --}}
            </div>
        </div>
    </div>
@empty
    
@endforelse



@endsection




@section('scripts')

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

@endsection