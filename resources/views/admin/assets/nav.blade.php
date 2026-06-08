
<style>
.admin-nav {
    background-color: #5476ac;
    overflow-x: auto;
    white-space: nowrap;
}

/* Links */
.admin-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
}

/* Hover */
.admin-link:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    color: #fff;
}

/* Activo */
.admin-link.active {
    background-color: rgba(255, 255, 255, 0.25);
    box-shadow: inset 0 -2px 0 #fff;
}

/* Iconos */
.admin-link i {
    font-size: 14px;
}

/* 📱 Móvil: solo iconos */
@media (max-width: 768px) {
    .admin-link span {
        display: none;
    }

    .admin-link {
        padding: 10px;
        justify-content: center;
    }
}

</style>

 <div class="row py-2 admin-nav sticky-top">
    
    <div class="col-auto">
        <a href="{{ route('perfil.admin') }}"
           class="admin-link {{ request()->routeIs('perfil.admin') ? 'active' : '' }}">
            <i class="fa fa-home"></i>
            <span>Inicio</span>
        </a>
    </div>


    <div class="col-auto">
        <a href="{{ route('revision.indicadores') }}"
           class="admin-link {{ request()->routeIs('revision.indicadores') ? 'active' : '' }}">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Buscador Indicadores</span>
        </a>
    </div>


    <div class="col-auto">
        <a href="{{ route('departamentos.show.admin') }}"
           class="admin-link {{ request()->routeIs('departamentos.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>Departamentos</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('usuarios.show.admin') }}"
           class="admin-link {{ request()->routeIs('usuarios.show.admin') ? 'active' : '' }}">
            <i class="fa fa-users"></i>
            <span>Usuarios</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('clientes.show.admin') }}"
           class="admin-link {{ request()->routeIs('clientes.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-users-viewfinder"></i>
            <span>Clientes</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('encuestas.show.admin') }}"
           class="admin-link {{ request()->routeIs('encuestas.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Encuestas</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('lista.quejas.cliente') }}"
           class="admin-link {{ request()->routeIs('lista.quejas.cliente') ? 'active' : '' }}">
            <i class="fa-solid fa-comment"></i>
            <span>Quejas</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('proveedores.show.admin') }}"
           class="admin-link {{ request()->routeIs('proveedores.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Proveedores</span>
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('informacion.foranea.show.admin') }}"
           class="admin-link {{ request()->routeIs('informacion.foranea.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span>Información</span>
        </a>
    </div>


    <div class="col-auto">
        <a href="{{ route('perspectivas.show') }}" class="admin-link {{ request()->routeIs('perspectivas.show') ? 'active' : '' }}">
            <i class="fa-solid fa-magnifying-glass"></i>            
            <span>Perspectivas</span>
        </a>
    </div>



    <div class="col-auto">
        <a href="{{ route('logs.show.admin') }}"
        class="admin-link {{ request()->routeIs('logs.show.admin') ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i>
            <span>Logs</span>
        </a>
    </div>
            





</div>
