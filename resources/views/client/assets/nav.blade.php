<div class="row py-2" style="background-color: #5476ac">
    
    <div class="col-auto mx-1 zoom_link {{ request()->routeIs('perfil.cliente') ? 'link_selected' : '' }}">
        <a href="{{route('perfil.cliente')}}" class="text-white text-decoration-none fw-bold ">
            <i class="fa-solid fa-home"></i>
            Inicio
        </a>
    </div>

    {{-- <div class="col-auto mx-1 zoom_link {{ request()->routeIs('seguimiento.quejas.cliente') ? 'link_selected' : '' }}">
        <a href="{{route('seguimiento.quejas.cliente')}}" class="text-white text-decoration-none fw-bold ">
            <i class="fa-solid fa-clipboard-list"></i>
            Seguimiento a quejas o sugerencias
        </a>
    </div> --}}

    <div class="col-auto mx-1 zoom_link {{ request()->routeIs('lista.quejas.clientes.*') ? 'link_selected' : '' }}">
        <a href="{{route('lista.quejas.clientes.clientes')}}" class="text-white text-decoration-none fw-bold ">
            <i class="fa-solid fa-clipboard-list"></i>
            Lista de quejas y/o sugerencias
        </a>
    </div>


</div>




<div class="modal fade" id="sugerencia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h3 class="text-white" id="exampleModalLabel">
            <i class="fa fa-comment"></i>
            Dejanos tu mensaje
        </h3>
        <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <form action="{{route('queja.cliente')}}" enctype="multipart/form-data" method="post" id="formularioQueja">
                @csrf
                <div class="row">

                    <div class="col-12">

                        <div class="form-group mb-2">
                            <label for="titulo">Titulo de la queja</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" class="">
                        </div>

                        <div class="form-group">
                            <div id="editor_queja"></div>
                            <input type="hidden" name="queja" id="queja">
                        </div>

                        
                    </div>
                    <div class="col-12 mt-4">
                        <input type="file" accept=".jpeg, .jpg, .pdf, .mp4" name="evidencia[]" class="form-control" multiple>
                    </div>

                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-start">
            <button form="formularioQueja"  class="btn btn-primary btn-sm" data-mdb-ripple-init>
                Enviar     
            </button>

      </div>
    </div>
  </div>
</div>