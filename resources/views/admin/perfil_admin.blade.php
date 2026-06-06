@extends('plantilla')
@section('title', 'Perfil Administrador')



@section('contenido')

<div class="container-fluid sticky-top">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h3 class="text-white league-spartan">Admin:  
                <span class="text-decoration-underline"> {{ Auth::user()->nombre }} </span>
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

    <div class="row">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body py-3 px-4">

                <form action="#" method="GET" id="search_indicadores">
                </form>
                    <div class="d-flex flex-wrap align-items-end gap-3">

                        <!-- Fecha inicio -->
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Desde</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-calendar-days text-primary"></i>
                                </span>
                                <input type="date"
                                    name="fecha_inicio"
                                    form="search_indicadores"
                                    value="{{ request('fecha_inicio') ?? now()->format('Y-m-d') }}"
                                    class="form-control border-0 bg-light datepicker"
                                    onchange="this.form.submit()">
                            </div>
                        </div>

                        <!-- Fecha fin -->
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Hasta</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-calendar-days text-danger"></i>
                                </span>
                                <input type="date"
                                    name="fecha_fin"
                                    form="search_indicadores"
                                    value="{{ request('fecha_fin') ?? now()->format('Y-m-d') }}"
                                    class="form-control border-0 bg-light datepicker"
                                    onchange="this.form.submit()">
                            </div>
                        </div>

                    
                        <div>
                            <label class="form-label small text-muted fw-semibold mb-1">Buscar</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fa-solid fa-search text-dark"></i>
                                </span>
                                <input type="search"
                                    name="nombre_indicador"
                                    id="buscador"
                                    class="form-control"
                                    placeholder="Buscar ..."
                                >           
                        </div>
                    </div>

            </div>
        </div>
    </div>


</div>



{{-- elaborando el perfil del usuario --}}

<div class="container-fluid border-bottom mt-1">



    <div class="row justify-content-center">


    @forelse ($departamentos as $departamento)

    <div class="col-12 col-sm-12 col-md-4 col-lg-3 mt-2 departamento_search">
        <div class="card shadow-3 rounded-4 p-3 border" >
            <div class="card-body p-1">

                <h4 class="text-muted text-uppercase fw-bold nombre">
                    {{$departamento->nombre}}
                </h4>

                @php
                    $data = $cumplimiento[$departamento->id] ?? collect();
                @endphp

                @if ($data->isEmpty())

                    <div class="text-center py-4 text-muted">
                        <img src="{{asset('/img/iconos/empty.png')}}"
                            class="img-fluid mb-2"
                            style="max-width: 120px;">
                        <p class="mb-0 fw-semibold">
                            Sin datos de cumplimiento
                        </p>
                    </div>

                @else
                
                    <canvas id="chart{{$departamento->id}}" height="200"></canvas>
                    {{-- <small class="text-muted text-center">Solo indicadores numericos</small> --}}
                @endif

                <div class="text-center mt-4">
                    <a href="{{route('lista.indicadores.admin', $departamento->id)}}"
                    class="btn btn-primary btn-sm rounded-pill">
                        Ver todo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    @if ($data->isNotEmpty())
    <script>
    document.addEventListener("DOMContentLoaded", function () {

        const rawData = @json($cumplimiento[$departamento->id]);

    const labels = rawData.map(i => {

        let [year, month] = i.mes.split("-"); // separa YYYY-MM

        month = parseInt(month) - 1;

        if (month < 1) {
            month = 12;
            year = parseInt(year) - 1;
        }

        // volver a formato YYYY-MM
        const nuevoMes = year + "-" + String(month).padStart(2, "0");

        return mesEnEspanol(nuevoMes);
    });

        
        const values = rawData.map(i => i.cumplimiento_total);

        const MINIMO = 50;
        const MAXIMO = 100;

        const canvas = document.getElementById("chart{{$departamento->id}}");
        if (!canvas) return;

        new Chart(canvas.getContext("2d"), {

            type: "bar", // 👈 importante cuando mezclas tipos

            data: {
                labels,
                datasets: [
                    {
                        label: "Promedio de Indicadores",
                        data: values,
                        backgroundColor: (ctx) => {
                            const value = ctx.raw;
                            return value < MINIMO
                                ? "rgba(255,99,132,0.7)"
                                : "rgba(75,192,75,0.7)";
                        },
                        borderWidth: 1,
                        order: 2 // 👈 barras abajo
                    },
                    {
                        type: "line",
                        label: "Mínimo",
                        data: labels.map(() => MINIMO),
                        borderColor: "red",
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: false,
                        tension: 0,
                        order: 9 // 👈 líneas arriba
                    },
                    {
                        type: "line",
                        label: "Máximo",
                        data: labels.map(() => MAXIMO),
                        borderColor: "green",
                        borderWidth: 3,
                        pointRadius: 0,
                        fill: false,
                        tension: 0,
                        order: 9
                    }
                ]
            },

            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

    });


    </script>
    @endif

    @empty
    <div class="col-8 text-center py-5 bg-white">
        <h4>
            <i class="fa-solid fa-building"></i>
            No hay departamentos
        </h4>
    </div>
    @endforelse










    </div>


@endsection



@section('scripts')




<script>
function mesEnEspanol(yyyyMM) {
    const [year, month] = yyyyMM.split("-");
    const fecha = new Date(year, month - 1);

    return fecha.toLocaleDateString("es-MX", {
        month: "long",
        year: "numeric"
    }).replace(/^\w/, c => c.toUpperCase());
}

</script>

<script>
document.getElementById('buscador').addEventListener('input', function () {
    let filtro = this.value.toLowerCase().trim();
    let cards = document.querySelectorAll('.departamento_search');

    cards.forEach(card => {

        // texto visible
        let nombre = card.querySelector('.nombre').textContent.toLowerCase();

        // datos ocultos
        let tipo = (card.dataset.departamento || '').toLowerCase();

        // combinamos todo lo que puede buscarse
        let contenido = `${nombre}`;

        // filtro
        if (contenido.includes(filtro)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>




<script>

  const select = document.getElementById("tipo_info");
  const contenedor = document.getElementById("contenedor_input");

  select.addEventListener("change", function () {
    contenedor.innerHTML = ""; // limpiar lo anterior

    let input;
    let label;

    
    if(this.value == "number"){
        input = document.createElement("input");
        input.type = "number";
        input.classList.add("form-control", 'border');
        input.placeholder = "Ingresa un número";
        input.name = "informacion";
        input.required = true;


        label = document.createElement("label");
        label.textContent = "Ingresa un número";

    }

    if(this.value == "string"){
        input = document.createElement("input");
        input.type = "text";
        input.classList.add("form-control", "border");
        input.placeholder = "Ingresa el texo";
        input.name = "informacion";
        input.required = true;

        label = document.createElement("label");
        label.textContent = "Ingresa el texto";

    }

    if(this.value == "date"){

        input = document.createElement("input");
        input.type = "date";
        input.classList.add('form-control', 'border');
        input.placeholder = "Fecha";
        input.name = "informacion";
        input.required = true;

        label = document.createElement("label");
        label.textContent = "Selecciona una fecha";

    }

    if(this.value == "month"){

        input = document.createElement("input");
        input.type = "month";
        input.classList.add('form-control', 'border');
        input.placeholder = "Mes";
        input.name = "informacion";
        input.required = true;

        label = document.createElement("label");
        label.textContent = "Selecciona un Mes";

    }

    if(this.value == "year"){
        
        input = document.createElement('input');
        input.type = "number";
        input.classList.add('form-control', 'border');
        input.placeholder = "Ingresa un año";
        input.min = "1900";
        input.max = "2025";
        input.name = "informacion";
        input.required = true;

        label = document.createElement("label");
        label.textContent = "Ingresa un año yyyy";

    }

    if (input){

        contenedor.appendChild(label);
        contenedor.appendChild(input);
    
    } 


  });
</script>

@endsection