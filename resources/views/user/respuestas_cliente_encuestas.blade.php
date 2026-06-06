@extends('plantilla')
@section('title', 'Respuestas del cliente')

@section('contenido')
<div class="container-fluid">
    <div class="row bg-primary  d-flex align-items-center px-4">
        <div class="col-auto py-4 text-white">
            <h1 class="mt-1">Respuestas de la encuesta</h1>
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
            @if ($errors->any())
                <div class="text-white fw-bold bad_notifications">
                    <i class="fa fa-xmark-circle mx-2"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>
    </div>
    @include('user.assets.nav')
</div>


<div class="container-fluid py-4">
    <div class="row justify-content-center">

        <!-- RESPUESTAS -->
        <div class="col-12 col-md-10 col-lg-9 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        Respuestas de <span class="fw-bold">{{ $cliente->nombre }}</span>
                    </h4>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th style="width:70%">Pregunta</th>
                                    <th class="text-center">Calificación</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($preguntas as $pregunta)
                                    <tr>
                                        <td class="fw-semibold">
                                            {{ $pregunta->pregunta }}
                                        </td>

                                        @foreach ($pregunta->respuestas as $respuesta)
                                            <td class="text-center">
                                                @if ($pregunta->cuantificable === 1)
                                                    <span class="badge bg-success fs-6">
                                                        {{ $respuesta->respuesta }} pts
                                                    </span>
                                                    <input type="hidden"
                                                           value="{{ $respuesta->respuesta }}"
                                                           class="sumar">
                                                @else
                                                    <span class="text-muted">
                                                        {{ $respuesta->respuesta }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">
                                            <i class="fa fa-exclamation-circle text-danger fs-4"></i>
                                            <div class="mt-2 text-muted">
                                                No cuenta con respuestas.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESUMEN -->
        <div class="col-12 col-md-10 col-lg-9">
            <div class="row g-3">

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Puntuación Máxima</h6>
                            <h2 class="fw-bold text-primary" id="puntuacion_maxima">0</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Puntuación Obtenida</h6>
                            <h2 class="fw-bold text-success" id="puntuacion_obtenida">0</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body">
                            <h6 class="text-muted">Cumplimiento</h6>
                            <h2 class="fw-bold">
                                <span id="cumplimiento" class="badge bg-secondary fs-5"></span>
                            </h2>
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
    let suma = 0;
    const celdas = document.querySelectorAll('.sumar');

    const cumplimiento = document.getElementById("cumplimiento");
    const puntuacion_maxima = document.getElementById("puntuacion_maxima");
    const puntuacion_obtenida = document.getElementById("puntuacion_obtenida");

    celdas.forEach(input => {
        suma += parseFloat(input.value) || 0;
    });

    const max = celdas.length * 10;
    const porcentaje = max > 0 ? (suma / max) * 100 : 0;

    puntuacion_maxima.textContent = max;
    puntuacion_obtenida.textContent = suma;
    cumplimiento.textContent = porcentaje.toFixed(1) + " %";

    // Color dinámico
    cumplimiento.classList.remove('bg-secondary','bg-danger','bg-warning','bg-success');

    if (porcentaje < 50) {
        cumplimiento.classList.add('bg-danger');
    } else if (porcentaje < 80) {
        cumplimiento.classList.add('bg-warning','text-dark');
    } else {
        cumplimiento.classList.add('bg-success');
    }
</script>
@endsection
