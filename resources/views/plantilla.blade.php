<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=0.80">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=groups" /> 
    {{-- <link rel="shortcut icon" href="https://i.pinimg.com/564x/3b/78/79/3b7879b2c286e7ee72530064a37bf8a6.jpg" type="image/x-icon"> --}}
    <link rel="stylesheet" href="{{asset('css/mdb.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill-better-table@1.2.10/dist/quill-better-table.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>

        body{
            margin: 0px;
            padding: 0px;
            cursor: 
        }

        .input.activo {
            border: 2px solid blue;
            background-color: #e0f0ff;
        }

        /* Loader styles */
        .form-loader {
            position: relative;
        }

        .form-loader.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .form-loader.loading button[type="submit"] {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }

        .form-loader.loading button[type="submit"]:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top: 2px solid transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>

</head>
<body>
    {{-- <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-12">
                <span style="font-size: 10px;"><b>MetricHub </b> by: <a href="https://github.com/resendiz1" target="_blank" class="text-dark fw-bold">Arturo Resendiz López</a> with <i class="fa fa-code text-dark fw-bold"></i> </span>
            </div>
        </div>
    </div> --}}
    @yield('contenido')



    {{-- <footer class="container-fluid  fixed-bottom">
        <div class="row bg-primary p-1 d-flex align-items-center">
            <div class="col-12 cascadia-code text-center text-white">
                With <i class="fa fa-gear "></i> by: <a href="https://github.com/resendiz1" class="text-white">Arturo Resendiz López</a>
            </div>
        </div>
    </footer> --}}
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/mdb.umd.min.js')}}"></script> 
    <script src="{{asset('js/chart.js')}}"></script>
    <script src="{{asset('js/interact.min.js')}}"></script>
    <script src="{{asset('js/draggable.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-better-table@1.2.10/dist/quill-better-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    form.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
        }
    });

});

</script>
    



    <script>
            toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "2500"
        };
    </script>


    <script>
        flatpickr(".datepicker", {
            locale: "es",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
        });
    </script>

    @yield('scripts')


    <script>
    // Global form loader functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add loader class to all forms
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.classList.add('form-loader');
            
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    form.classList.add('loading');
                }
            });
        });
    });
    </script>






<script>
document.addEventListener("DOMContentLoaded", function () {
if(document.getElementById('editor_queja')){
    let editorInstance;

ClassicEditor
  .create(document.querySelector("#editor_queja"), {
    toolbar: [
      "bold",
      "italic",
      "insertTable",
      "undo",
      "redo"
    ],
    table: {
      contentToolbar: [
        "tableColumn",
        "tableRow",
        "mergeTableCells"
      ]
    }
    // No incluyas "link" en la toolbar
  })
  .then(editor => {
    editor.model.document.on("change:data", () => {
      document.querySelector("#queja").value = editor.getData();
    });
  })
  .catch(error => console.error(error));

}

});



</script>






<script>

if(document.getElementById('info_extra')){
document.addEventListener("DOMContentLoaded", function () {

    let editorInstance;

ClassicEditor
  .create(document.querySelector("#editor_info_extra"), {
    toolbar: [
      "bold",
      "italic",
      "insertTable",
      "undo",
      "redo"
    ],
    table: {
      contentToolbar: [
        "tableColumn",
        "tableRow",
        "mergeTableCells"
      ]
    }
    // No incluyas "link" en la toolbar
  })
  .then(editor => {
    editor.model.document.on("change:data", () => {
      document.querySelector("#info_extra").value = editor.getData();
    });
  })
  .catch(error => console.error(error));


});

}
</script>
    {{-- Libreria de texto enriquecido --}}

    

    




    <script>
        if(document.getElementById("fecha")){
            let mostrar_fecha = document.getElementById("fecha");
            let fecha = new Date();
            mostrar_fecha.innerHTML = " <i class='fa fa-calendar'></i>  " + fecha.toLocaleDateString("es-Es", {month: 'long'}) +" "+ fecha.getFullYear();
        }    
    </script>

    {{-- notificaciones de todo --}}

        @if (session("error_input"))
            <script>
                toastr.error('{{session("error_input")}}', 'Error!')  
            </script>        
        @endif


        @if (session("error"))
            <script>
                toastr.error('{{session("error")}}', 'Error!');
            </script>
        @endif

        @if (session('deleted'))
            <script>
                toastr.error('{{session("deleted")}}', 'Eliminado!');
            </script>
        @endif


        @if (session('success'))
            <script>
                toastr.success('{{session("success")}}', 'Exito!');
            </script>
        @endif

        @if (session('actualizado'))
            <script>
                toastr.success('{{session("actualizado")}}', 'Exito!');
            </script>
        @endif

        @if (session('eliminado'))
            <script>
                toastr.warning('{{session("eliminado")}}', 'Exito!');
            </script>
        @endif

        @if (session('editado'))
            <script>
                toastr.success('{{session("editado")}}', 'Exito!');
            </script>
        @endif

        @if (session('eliminado_user'))
            <script>
                toastr.warning('{{session("eliminado_user")}}', 'Exito!');
            </script>
        @endif


        @if ($errors->any())
            <script>
                toastr.error('{{$errors->first()}}', 'Error!')
            </script>

        @endif


    {{-- notificaciones de todo --}}




    {{-- Esto hace que el tab-panel se regrese al lugar donde lo dejaste despues de cargar la pagina --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabLinks = document.querySelectorAll('[data-mdb-tab-init]');
        const tabContent = document.getElementById('ex1-content');

        const savedTab = localStorage.getItem('activeTab');

        if (savedTab) {
            const tabTrigger = document.querySelector(`[href="${savedTab}"]`);
            if (tabTrigger) {
                // Primero activamos la tab visualmente
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                tabTrigger.classList.add('active');
                
                // Activamos el contenido correspondiente
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                const targetPane = document.querySelector(savedTab);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            }
        }

        // Mostrar contenido una vez listo
        tabContent.classList.remove('d-none');

        tabLinks.forEach(tab => {
            tab.addEventListener('shown.mdb.tab', e => {
                localStorage.setItem('activeTab', e.target.getAttribute('href'));
            });
        });
    });
    </script>




<!-- PARA QUE SE MUESTRE PRTIERO LA PARTE DE ABAJO LOS INDICADORES -->
    <script>
        if(document.querySelector('.indicador-container')){

            document.querySelectorAll('.indicador-container').forEach(contenedor => {
            contenedor.scrollTop = contenedor.scrollHeight;
            });

        }

    </script>




{{-- FORMAEANDO LOS NUMEROS QUE SE MUESTRAN --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.format-number').forEach(el => {

        let texto = el.textContent.trim();

        // Detectar signo
        let signo = texto.includes('+') ? '+ ' : '';

        // Detectar unidad
        let unidad = '';
        if (texto.includes('$')) unidad = '$';
        else if (texto.includes('%')) unidad = '%';
        else if (texto.toLowerCase().includes('días')) unidad = ' Días';
        else if (texto.toLowerCase().includes('ton')) unidad = ' Ton.';

        // Extraer número (solo dígitos y punto)
        let numero = texto.replace(/[^0-9.-]/g, '');

        if (!isNaN(numero) && numero !== '') {
            let formateado = Number(numero).toLocaleString('es-MX', {
                maximumFractionDigits: 2
            });

            // Reconstruir
            if (unidad === '$') {
                el.textContent = signo + '$' + formateado;
            } else {
                el.textContent = signo + formateado + unidad;
            }
        }

    });
});
</script>



{{-- loader para los formularios que estan separados del boton submit --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('form.form-loader').forEach(form => {

        form.addEventListener('submit', (e) => {

            // Evita doble ejecución
            if (form.classList.contains('loading')) return;

            form.classList.add('loading');

            // Botón que disparó el submit (soporta form="")
            const btn = e.submitter;
            if (!btn) return;

            btn.disabled = true;

        });

    });

});
</script>






<script>
document.addEventListener("DOMContentLoaded", function () {

    const scrollPos = localStorage.getItem("scrollPos");
    if (scrollPos !== null) {
        window.scrollTo(0, parseInt(scrollPos));
        localStorage.removeItem("scrollPos");
    }

    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function () {
            localStorage.setItem("scrollPos", window.scrollY);
        });
    });

});
</script>

</body>
</html>