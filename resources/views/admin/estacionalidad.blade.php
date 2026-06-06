@extends('plantilla')
@section('title', 'Detalle del Indicador')
@section('contenido')
@php
    use Carbon\Carbon;
    use App\Models\InformacionInputPrecargado;
    use App\Models\MetaIndicador;
@endphp
<div class="container-fluid sticky-top ">

    <div class="row bg-primary d-flex align-items-center justify-content-start">
        <div class="col-12 col-sm-12 col-md-6 col-lg-10 pt-2">
            <h2 class="text-white league-spartan">{{$indicador->nombre}}</h2>

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
                <div class="row mt-3 ">
                <form action="#" id="formFiltro" method="POST">
                    @csrf
                    <div class="col-12 mx-1 bg-white">
                        <div class="row p-3 d-flex align-items-center">
                            <div class="col-12 py-2">
                                <h5 class="fw-bold">Estacionalidad:</h5>
                            </div>

                            <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                                <select id="estacionalidad_year" name="estacionalidad_year[]" multiple>
                                    @forelse ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @empty
                                        <option value="" disabled>No hay datos</option>                                        
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                                <select id="estacionalidad_mes" name="estacionalidad_mes[]" multiple>
                                    @forelse ($meses as $mes)
                                           
                                         <option value="{{ $mes }}">{{ Carbon::create()->month($mes)->translatedFormat('F')}} 

                                    @empty
                                        <option value="" disabled>No hay datos</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 g-1">
                                <button type="submit" class="btn btn-primary w-100 btn-sm">
                                    Ver
                                </button>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-6" id="resultado_estacionalidad">

                            </div>
                        </div>

                    </div>
                </form>
            </div>
</div>