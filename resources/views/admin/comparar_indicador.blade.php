@extends('plantilla')
@section('title', 'Comparar Indicador')
@section('contenido')


    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-9 pt-2">
            <h1 class="text-white league-spartan">{{$indicador->nombre}} - Comparación </h1>

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


        <div class="col-12 cl-sm-12 col-md-6 col-lg-3 text-center ">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf
                <button class="btn btn-danger fw-bold" data-mdb-ripple-init>
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

@include('admin.assets.nav')

@php
$fmt = function ($valor, $unidad = null) {
    if (is_null($valor)) return '—';
    switch ($unidad) {
        case 'pesos':      return '$ ' . number_format((float) $valor, 2);
        case 'porcentaje': return number_format((float) $valor, 2) . ' %';
        case 'dias':       return number_format((float) $valor, 2) . ' Días';
        case 'toneladas':  return number_format((float) $valor, 2) . ' Ton.';
        default:           return number_format((float) $valor, 2);
    }
};

$labels = collect(array_unique(array_merge($actual['meses'], $comparado ? $comparado['meses'] : [])))
    ->sort()
    ->values();

$valoresActual = $labels->map(fn($m) => $actual['datos_por_mes'][$m] ?? null);
$valoresComparado = $comparado
    ? $labels->map(fn($m) => $comparado['datos_por_mes'][$m] ?? null)
    : collect([]);

$semaforo = function ($valor, $indicador) {
    if (is_null($valor)) return null;
    $meta = (float) $indicador->meta_esperada;
    $varMin = $indicador->meta_minima !== null ? (float) $indicador->meta_minima : null;
    if ($indicador->variacion === 'on' && $varMin !== null) {
        $verde = $valor >= ($meta - $varMin) && $valor <= ($meta + $varMin);
    } elseif ($indicador->tipo_indicador === 'riesgo') {
        $verde = $valor < $meta;
    } else {
        $verde = $valor >= $meta;
    }
    return $verde ? 'text-success' : 'text-danger';
};
@endphp








<div class="row">
    <div class="card border-0 shadow-sm">
        <div class="card-body py-3 px-4">

            <form action="{{ route('comparar.indicador', $indicador->id) }}" method="GET" id="filtro_comparar">
                <div class="row g-3 align-items-end">

                    <div class="col-4 col-md-auto">
                        <a href="{{ route('analizar.indicador', $indicador->id) }}" class="btn btn-secondary btn-sm px-3">
                            <i class="fa-solid fa-arrow-left me-1"></i> Volver
                        </a>
                    </div>

                    <div class="col-6 col-md-auto">
                        <label class="form-label small text-muted fw-semibold mb-1">Desde</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-calendar-days text-primary"></i>
                            </span>
                            <input type="date" name="fecha_inicio"
                                value="{{ request('fecha_inicio') ?? \Carbon\Carbon::now()->startOfYear()->format('Y-m-d') }}"
                                class="form-control border-0 bg-light datepicker" onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="col-6 col-md-auto">
                        <label class="form-label small text-muted fw-semibold mb-1">Hasta</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa-solid fa-calendar-days text-danger"></i>
                            </span>
                            <input type="date" name="fecha_fin"
                                value="{{ request('fecha_fin') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                                class="form-control border-0 bg-light datepicker" onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="col-12 col-md">
                        <label class="form-label small text-muted fw-semibold mb-1">
                            <i class="fa-solid fa-scale-balanced"></i>
                            Indicador a comparar
                        </label>
                        <select class="form-select form-select-sm fw-bold" id="select_indicador" name="con" form="filtro_comparar">
                            <option value="">Selecciona un indicador...</option>
                            @foreach ($indicadores as $opcion)
                                <option value="{{ $opcion->id }}" {{ request('con') == $opcion->id ? 'selected' : '' }}>
                                    {{ $opcion->nombre }} — {{ $opcion->departamento->nombre ?? 'Sin depto.' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-auto">
                        <label class="form-label small text-muted fw-semibold mb-1">
                            <i class="fa-solid fa-chart-pie"></i>
                            Tipo de gráfica
                        </label>
                        <select class="form-select form-select-sm fw-bold" id="select_tipo" name="tipo" form="filtro_comparar" onchange="this.form.submit()">
                            <option value="line" {{ ($tipo ?? 'line') == 'line' ? 'selected' : '' }}>Línea</option>
                            <option value="bar" {{ ($tipo ?? 'line') == 'bar' ? 'selected' : '' }}>Barras</option>
                            <option value="doughnut" {{ ($tipo ?? 'line') == 'doughnut' ? 'selected' : '' }}>Dona</option>
                        </select>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<div class="row g-3 mt-1 justify-content-center">

    {{-- Gráficas comparativas --}}
    @if ($comparado && $labels->isNotEmpty())
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-1">
                    <i class="fa-solid fa-chart-column me-2 text-primary"></i>Gráficas comparativas
                    <span class="badge bg-primary text-white text-capitalize">{{ $tipo }}</span>
                </h5>
                <small class="text-muted d-block mb-3">
                    {{ $tipo == 'doughnut' ? 'Distribución mensual por indicador.' : 'Evolución mes a mes por indicador.' }}
                </small>
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm bg-light h-100">
                            <div class="card-header bg-primary bg-opacity-10 border-0">
                                <h6 class="fw-bold text-truncate mb-0 text-primary" title="{{ $actual['indicador']->nombre }}">
                                    <i class="fa-solid fa-chart-line me-1"></i>{{ $actual['indicador']->nombre }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoActual"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm bg-light h-100">
                            <div class="card-header bg-dark bg-opacity-10 border-0">
                                <h6 class="fw-bold text-truncate mb-0 text-dark" title="{{ $comparado['indicador']->nombre }}">
                                    <i class="fa-solid fa-scale-balanced me-1"></i>{{ $comparado['indicador']->nombre }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoComparado"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tablas comparativas mensuales --}}
    <div class="col-lg-6 col-sm-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold text-truncate mb-3">
                    <span class="badge bg-primary me-1">Actual</span>{{ $actual['indicador']->nombre }}
                </h6>
                <div >
                    <div class="row g-3">
                        @foreach ($labels->chunk(ceil($labels->count() / 2)) as $grupo)
                            <div class="col-6">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <tbody>
                                        @foreach ($grupo as $i => $mes)
                                            @php
                                                $clase = $semaforo($valoresActual[$i], $actual['indicador']);
                                            @endphp
                                            <tr>
                                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($mes . '-01')->translatedFormat('M Y') }}</td>
                                                <td class="text-end fw-semibold {{ $clase }}">{{ $fmt($valoresActual[$i], $actual['indicador']->unidad_medida) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold text-truncate mb-3">
                    <span class="badge bg-dark me-1">Comparado</span>{{ $comparado['indicador']->nombre }}
                </h6>
                <div style="auto;">
                    <div class="row g-3">
                        @foreach ($labels->chunk(ceil($labels->count() / 2)) as $grupo)
                            <div class="col-6">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <tbody>
                                        @foreach ($grupo as $i => $mes)
                                            @php
                                                $clase = $semaforo($valoresComparado[$i], $comparado['indicador']);
                                            @endphp
                                            <tr>
                                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($mes . '-01')->translatedFormat('M Y') }}</td>
                                                <td class="text-end fw-semibold {{ $clase }}">{{ $fmt($valoresComparado[$i], $comparado['indicador']->unidad_medida) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif (!$comparado)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-arrow-up me-1"></i>Selecciona un indicador en el filtro superior para generar la comparación.
            </div>
        </div>
    </div>
    @else
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-circle-info me-1"></i>No hay datos registrados en el periodo seleccionado para los indicadores.
            </div>
        </div>
    </div>
    @endif

</div>



@endsection

@section('scripts')
@php
    $indActualData = $actual['indicador']->only(['meta_esperada', 'meta_minima', 'tipo_indicador', 'variacion', 'unidad_medida']);
    $indComparadoData = $comparado ? $comparado['indicador']->only(['meta_esperada', 'meta_minima', 'tipo_indicador', 'variacion', 'unidad_medida']) : null;
@endphp
<script>
document.addEventListener("DOMContentLoaded", function () {

    const selectEl = document.getElementById("select_indicador");
    if (selectEl) {
        selectEl.addEventListener("change", function () {
            this.form.submit();
        });
        if (typeof Choices !== "undefined") {
            new Choices(selectEl, {
                searchEnabled: true,
                searchPlaceholderValue: "Buscar indicador...",
                itemSelectText: "",
                placeholderValue: "Selecciona un indicador",
                shouldSort: false,
            });
        }
    }

    const selectTipoEl = document.getElementById("select_tipo");
    if (selectTipoEl && typeof Choices !== "undefined") {
        new Choices(selectTipoEl, {
            searchEnabled: false,
            itemSelectText: "",
            shouldSort: false,
        });
    }

    const labels = @json($labels->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->translatedFormat('M Y'))->values());
    const valoresActual = @json($valoresActual);
    const valoresComparado = @json($valoresComparado);

    const tipoGrafica = @json($tipo);

    const indActual = @json($indActualData);
    const indComparado = @json($indComparadoData);

    const nombreActual = @json($actual['indicador']->nombre);
    const nombreComparado = @json($comparado ? $comparado['indicador']->nombre : null);

    const VERDE = "rgba(75, 192, 75, 0.8)";
    const ROJO = "rgba(255, 99, 132, 0.8)";
    const NEUTRO = "rgba(200, 200, 200, 0.3)";

    const formatearValor = function (v) {
        if (v === null || v === undefined) return "";
        return Number(v).toLocaleString("es-MX", { maximumFractionDigits: 2 });
    };

    const formatearConUnidad = function (v, unidad) {
        if (v === null || v === undefined) return "";
        const num = formatearValor(v);
        switch (unidad) {
            case "pesos":      return "$ " + num;
            case "porcentaje": return num + " %";
            case "dias":       return num + " Días";
            case "toneladas":  return num + " Ton.";
            default:           return num;
        }
    };

    const anchoTexto = function (context, texto) {
        const ctx = context.chart.ctx;
        ctx.font = "bold 20px sans-serif";
        return ctx.measureText(String(texto)).width;
    };

    const barraAlta = function (context, unidad) {
        const meta = context.chart.getDatasetMeta(context.datasetIndex);
        const bar = meta.data[context.dataIndex];
        if (!bar || bar.y === undefined) return true;

        const altoBarra = Math.abs(bar.base - bar.y);
        const texto = formatearConUnidad(context.dataset.data[context.dataIndex], unidad);
        const anchoLabel = anchoTexto(context, texto);

        return altoBarra >= anchoLabel + 8;
    };

    const colorMeta = function (valor, ind) {
        if (valor === null || valor === undefined || !ind) return NEUTRO;

        const meta = parseFloat(ind.meta_esperada);
        const varMin = parseFloat(ind.meta_minima);

        if (ind.variacion === "on" && !isNaN(varMin)) {
            const min = meta - varMin;
            const max = meta + varMin;
            return (valor >= min && valor <= max) ? VERDE : ROJO;
        }

        if (ind.tipo_indicador === "riesgo") {
            return valor < meta ? VERDE : ROJO;
        }

        return valor >= meta ? VERDE : ROJO;
    };

    const colorUltimoValor = function (valores, ind) {
        const ultimo = [...valores].reverse().find(v => v !== null && v !== undefined);
        return colorMeta(ultimo ?? null, ind);
    };

    const hacerTooltip = function (unidad) {
        return {
            callbacks: {
                label: function (ctx) {
                    const v = ctx.parsed.y;
                    if (v === null || v === undefined) return ctx.dataset.label + ": —";
                    return ctx.dataset.label + ": " + formatearConUnidad(v, unidad);
                }
            }
        };
    };

    const crearGrafica = function (canvas, nombre, valores, meta, ind) {
        if (!canvas) return null;

        const unidad = ind ? ind.unidad_medida : null;

        const pluginFechasDona = {
            id: "fechasDona",
            afterDatasetsDraw(chart) {
                const meta = chart.getDatasetMeta(0);
                if (!meta.data || !meta.data.length) return;

                const ctx = chart.ctx;
                const datos = chart.data.datasets[0].data;

                ctx.save();
                ctx.font = "bold 20px sans-serif";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";

                meta.data.forEach((arc, i) => {
                    const v = datos[i];
                    if (!v || v <= 0) return;

                    const angulo = (arc.startAngle + arc.endAngle) / 2;
                    const radio = (arc.innerRadius + arc.outerRadius) / 2;
                    const x = arc.x + Math.cos(angulo) * radio;
                    const y = arc.y + Math.sin(angulo) * radio;

                    ctx.fillStyle = "#000000";
                    ctx.fillText(labels[i], x, y - 11);
                    ctx.fillStyle = "#ffffff";
                    ctx.fillText(formatearConUnidad(v, unidad), x, y + 11);
                });

                ctx.restore();
            },
        };

        let config;

        if (tipoGrafica === "doughnut") {
            config = {
                type: "doughnut",
                data: {
                    labels,
                    datasets: [{
                        data: valores.map(v => v ?? 0),
                        backgroundColor: valores.map(v => colorMeta(v, ind)),
                        borderColor: "#ffffff",
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "top" },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ctx.label + ": " + formatearConUnidad(ctx.parsed, unidad);
                                }
                            }
                        }
                    },
                },
                plugins: [pluginFechasDona],
            };
        } else {
            const esBar = tipoGrafica === "bar";
            const colorLinea = colorUltimoValor(valores, ind);

            config = {
                type: tipoGrafica,
                data: {
                    labels,
                    datasets: [
                        {
                            label: nombre,
                            data: valores,
                            ...(esBar ? {
                                backgroundColor: valores.map(v => colorMeta(v, ind)),
                                borderColor: valores.map(v => colorMeta(v, ind).replace("0.8)", "1)")),
                                borderWidth: 1,
                            } : {
                                borderColor: colorLinea,
                                backgroundColor: colorLinea.replace("0.8)", "0.15)"),
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointBackgroundColor: colorLinea,
                            }),
                            yAxisID: "y",
                        },
                        {
                            type: "line",
                            label: "Meta",
                            data: labels.map(() => meta),
                            borderColor: esBar ? "rgba(100, 116, 139, 0.6)" : colorLinea.replace("0.8)", "0.6)"),
                            borderDash: [6, 6],
                            pointRadius: 0,
                            fill: false,
                            yAxisID: "y",
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: hacerTooltip(unidad),
                        ...(esBar ? {
                            datalabels: {
                                display: ctx => ctx.dataset.type !== 'line',
                                anchor: ctx => barraAlta(ctx, unidad) ? 'center' : 'end',
                                align: ctx => barraAlta(ctx, unidad) ? 'center' : 'end',
                                rotation: ctx => barraAlta(ctx, unidad) ? 90 : 0,
                                color: ctx => barraAlta(ctx, unidad) ? "#ffffff" : "#1e293b",
                                font: { weight: "bold", size: 20 },
                                formatter: v => formatearConUnidad(v, unidad),
                            }
                        } : {}),
                    },
                    scales: {
                        y: { beginAtZero: true },
                    },
                },
            };
        }

        if (tipoGrafica !== "doughnut") {
            config.plugins = [ChartDataLabels];
        }

        return new Chart(canvas.getContext("2d"), config);
    };

    if (window.miGraficaActual) {
        window.miGraficaActual.destroy();
    }
    if (window.miGraficaComparado) {
        window.miGraficaComparado.destroy();
    }

    const cActual = document.getElementById("graficoActual");
    const cComparado = document.getElementById("graficoComparado");
    if (!cActual && !cComparado) return;

    if (cActual) {
        const meta = parseFloat(indActual.meta_esperada) || 0;
        window.miGraficaActual = crearGrafica(cActual, nombreActual, valoresActual, meta, indActual);
    }

    if (cComparado) {
        const meta = parseFloat(indComparado ? indComparado.meta_esperada : 0) || 0;
        window.miGraficaComparado = crearGrafica(cComparado, nombreComparado, valoresComparado, meta, indComparado);
    }

});
</script>
@endsection
