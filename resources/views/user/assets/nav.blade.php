<div class="row py-2" style="background-color: #6782AE ">

    @if (Auth::user()->tipo_usuario !== 'principal')
        <div class="col-12 text-center bg-outline-primary">
            <h6 class="mt-2 text-white">
                <i class="fa fa-book-open"></i>
                Perfil de solo lectura
            </h6>
        </div>
    @endif

    <div class="col-auto zoom_link {{ request()->routeIs('perfil.usuario') ? 'link_selected' : '' }}">
        <a href="{{route("perfil.usuario")}}" class="btn btn-transparent text-white text-decoration-none fw-bold">
            <i class="fa fa-chart-pie mx-1"></i>
            Indicadores
        </a>
    </div>

    <div class="col-auto zoom_link {{ request()->routeIs('evaluaciones.show.user') ? 'link_selected' : '' }}">
        <a href="{{route("evaluaciones.show.user")}}" class="btn btn-transparent text-white text-decoration-none fw-bold">
            <i class="fa-solid fa-user-check mx-1"></i>
            Evaluar Proveedor
        </a>
    </div>

    <div class="col-auto  zoom_link {{ request()->routeIs('cumplimiento.normativo.user') ? 'link_selected' : '' }}">
        <a href="{{route("cumplimiento.normativo.user")}}" class="btn btn-transparent text-white text-decoration-none fw-bold ">
            <i class="fa-solid fa-list-check mx-1"></i>
            Cumplimiento Normativo 
        </a>
    </div>

    <div class="col-auto  zoom_link {{ request()->routeIs('indicadores.foraneos.user') ? 'link_selected' : '' }}">
        <a href="{{route("indicadores.foraneos.user")}}" class="btn btn-transparent text-white text-decoration-none fw-bold ">
            <i class="fa-solid fa-arrows-down-to-people mx-1"></i>
            Indicadores de otras Areas. 
        </a>
    </div>




    {{-- Esto e mostrara solo para ventas... --}}
    @if (Auth::user()->departamento->nombre == "Ventas")
        {{-- ponerla solo visible para atencio al clientes --}}
        <div class="col-auto  zoom_link {{ request()->routeIs('encuesta.clientes.user') ? 'link_selected' : '' }}">
            <a href="{{route("encuesta.clientes.user")}}" class="btn btn-transparent text-white text-decoration-none fw-bold ">
                <i class="fa-solid fa-users mx-1"></i>
                Resultados Encuestas a Clientes 
            </a>
        </div>

        <div class="col-auto  zoom_link {{ request()->routeIs('ver.encuestas.user') ? 'link_selected' : '' }}">
            <a href="{{route("ver.encuestas.user")}}" class="btn btn-transparent text-white text-decoration-none fw-bold ">
                <i class="fa-solid fa-list-check"></i>
                Rellenar Encuestas de los Clientes 
            </a>
        </div>

    @endif
    {{-- Esto e mostrara solo para ventas... --}}



</div>
