@extends('plantilla')
@section('title', 'Configuraciones')

@section('contenido')

<style>
    .toggle-label {
        font-size: 1rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid sticky-top">

    <div class="row bg-primary d-flex align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2 text-white">
            <h3 class="mt-1 league-spartan mb-0">Configuraciones</h3>
            <span>Control global del llenado de indicadores</span>

            @if (session('success'))
                <div class="text-white fw-bold">
                    <i class="fa fa-check-circle mx-2"></i>
                    {{session('success')}}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-white fw-bold p-2 rounded">
                    <i class="fa fa-exclamation-circle mx-2 text-danger"></i>
                    {{$errors->first()}}
                </div>
            @endif
        </div>

        <div class="col-12 cl-sm-12 col-md-6 col-lg-2 text-center">
            <form action="{{route('cerrar.session')}}" method="POST">
                @csrf
                <button class="btn btn-primary text-white fw-bold">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    @include('admin.assets.nav')

</div>

<div class="container-fluid mt-4">

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body py-4 px-4">

                    <h4 class="mb-1 fw-bold">
                        <i class="fa-solid fa-ban text-primary me-2"></i>
                        Bloqueo de llenado de indicadores
                    </h4>
                    <p class="text-muted mb-4">
                        Cuando esté activo, los usuarios no podrán llenar el indicador del mes en ningún indicador del sistema.
                    </p>

                    <form action="{{route('configuraciones.llenado.update')}}" method="POST">
                        @csrf

                        <div class="form-check form-switch form-switch-lg d-flex align-items-center justify-content-between mb-4">
                            <label class="form-check-label toggle-label" for="bloqueo_llenado">
                                <i class="fa-solid fa-lock me-2 text-muted"></i>
                                Bloquear llenado de indicadores
                            </label>
                            <input class="form-check-input ms-3"
                                type="checkbox"
                                role="switch"
                                id="bloqueo_llenado"
                                name="bloqueo_llenado"
                                value="1"
                                {{ ($bloqueo === '1') ? 'checked' : '' }}>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            Guardar
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection