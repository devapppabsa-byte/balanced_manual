@extends('plantilla')
@section('title', 'Análisis Cruzado del Indicador')
@section('contenido')
<div class="container-fluid d-flex flex-column" style="height: 100vh; padding: 0;">
    <div class="row g-0" style="height: 100%;">

        {{-- Sidebar: indicador principal + asociados --}}
        <div class="col-3 d-flex flex-column bg-white border-end shadow-s" style="max-width: 340px; height: 100%;">
            <div class="p-3 bg-primary text-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-solid fa-link me-2"></i>
                        Análisis con IA
                    </h5>
                    <a href="{{ route('analizar.indicador.usuario', $indicador->id) }}" class="btn btn-sm btn-secondary" title="Volver al detalle">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div class="small text-white-50">
                    <i class="fa-solid fa-chart-line me-1"></i>
                    Indicador principal:
                </div>
                <div class="fw-semibold">{{ $indicador->nombre }}</div>
            </div>

            <div class="flex-grow-1 p-3" style="min-height: 0; overflow-y: auto;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-bold text-uppercase">Asociados a tu departamento ({{ $asociados->count() }})</small>
                    @if ($asociados->isNotEmpty())
                        <button type="button" class="btn btn-primary btn-sm rounded-pill" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalSeleccionIndicadoresIA">
                            <i class="fa-solid fa-check-double me-1"></i>
                            Seleccionar
                        </button>
                    @endif
                </div>

                @if ($asociados->isNotEmpty())
                    <div class="text-muted small mb-2 fw-semibold">
                        <i class="fa-solid fa-circle-check me-1 text-primary"></i>
                        Seleccionados: <span id="contadorSeleccionados">{{ $asociados->count() }}</span>/{{ $asociados->count() }}
                    </div>
                @endif

                @forelse ($asociados as $item)
                    <div class="d-flex align-items-center justify-content-between border rounded-3 p-2 mb-2 bg-white">
                        <a href="{{ route('analizar.indicador.usuario', $item->id) }}" class="d-block text-decoration-none overflow-hidden" style="min-width: 0;" title="Ver {{ $item->nombre }}">
                            <div class="fw-semibold text-truncate text-dark">{{ $item->nombre }}</div>
                            <small class="text-muted">{{ $item->departamento->nombre ?? '—' }}</small>
                        </a>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <span class="badge bg-{{ $item->tipo_indicador === 'riesgo' ? 'danger' : 'success' }} bg-opacity-10 text-dark">
                                {{ $item->tipo_indicador ?? 'normal' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-link-slash mb-2" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        <p class="mb-0">No hay indicadores asociados a tu departamento.</p>
                        <small>Solo se analizan indicadores de tu departamento o los compartidos contigo.</small>
                    </div>
                @endforelse

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-bold text-uppercase">
                        <i class="fa-regular fa-clock me-1"></i>
                        Historial de tus chats
                    </small>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-mdb-ripple-init onclick="cargarHistorialChats()" title="Recargar historial">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>
                <div id="historialChatsIA">
                    <div class="text-center py-3 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small>Cargando historial...</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat con la IA casi toda la pantalla --}}
        <div class="col d-flex flex-column bg-light" style="height: 100vh; min-width: 0;">
            <div class="d-flex align-items-center justify-content-between bg-primary text-white px-4 py-2">
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-robot me-2"></i>
                    Análisis con IA
                </h5>
                <div class="d-flex align-items-center gap-2">
                    @if ($asociados->isNotEmpty())
                        <button type="button" class="btn btn-light rounded-pill px-3 py-1" data-mdb-ripple-init onclick="abrirSeleccionIndicadores()" title="Nuevo análisis">
                            <i class="fa-solid fa-calendar-days me-1"></i>
                            Nuevo análisis
                        </button>
                    @endif
                    <span class="badge bg-white text-primary rounded-pill px-3 py-1">
                        {{ $indicador->nombre }}
                    </span>
                </div>
            </div>

            <div id="chatPanelIA" class="flex-grow-1 px-4 py-3 d-flex flex-column" style="overflow-y: auto; min-height: 0;">
                <div id="contenidoPanelIA" class="my-auto">
                    <div class="text-center text-muted">
                        <i class="fa-solid fa-robot mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                        @if ($asociados->isEmpty())
                            <h5 class="mb-1">No hay indicadores asociados para analizar</h5>
                            <p>Los indicadores analizables son los de tu departamento y los compartidos contigo (aux_indicadores_foraneos).</p>
                        @else
                            <h5 class="mb-1">Análisis de los indicadores que selecciones</h5>
                            <p>Elige qué indicadores de tu departamento quieres cruzar con el principal.</p>
                            <button id="btnIniciarAnalisisIA" class="btn btn-primary rounded-pill px-5 py-3 mt-3" onclick="abrirSeleccionIndicadores()">
                                <i class="fa-solid fa-calendar-days me-2"></i>
                                Iniciar análisis con IA
                            </button>
                        @endif
                    </div>
                </div>
                <div id="loaderPanelIA" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="fw-semibold text-muted mb-0">Analizando con IA...</p>
                </div>
            </div>

            @if ($asociados->isNotEmpty())
            <div class="border-top bg-white px-4 py-3 d-flex gap-2">
                <input type="text" id="inputPreguntaIA" class="form-control form-control-lg"
                    placeholder="Pregunta algo sobre el análisis...">
                <button id="btnEnviarPregunta" class="btn btn-primary rounded-pill px-4" onclick="enviarPregunta()">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal para seleccionar qué indicadores cruzar en el análisis --}}
<div class="modal fade" id="modalSeleccionIndicadoresIA" tabindex="-1" aria-labelledby="modalSeleccionLabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h3 class="text-white">
                    <i class="fa-solid fa-link me-2"></i>
                    Selecciona los indicadores a analizar
                </h3>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="row mb-4">
                    <div class="col-md-8 col-12">
                        <input type="search" id="buscadorIndicadoresSeleccionIA" class="form-control form-control-lg" placeholder="Buscar indicador...">
                    </div>
                    <div class="col-md-4 col-12 d-flex gap-2 justify-content-md-end">
                        <button type="button" class="btn btn-outline-primary btn-lg" onclick="seleccionarTodos()">Seleccionar todos</button>
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="quitarTodos()">Quitar todos</button>
                    </div>
                </div>
                <div class="row justify-content-around" id="contenedorIndicadoresSeleccionIA">
                    @forelse ($asociados as $item)
                        <div class="col-3 m-1 p-3 item-indicador-seleccion-ia" data-nombre="{{ strtolower($item->nombre) }}">
                            <input type="checkbox"
                                name="indicadores_seleccion[]"
                                value="{{ $item->id }}"
                                class="btn-check indicador-seleccion-ia-checkbox"
                                id="sel_ia_{{ $item->id }}"
                                autocomplete="off"
                                checked>
                            <label class="btn btn-outline-primary custom-check text-start w-100 h-100"
                                for="sel_ia_{{ $item->id }}">
                                <div class="text-center fw-bold">{{ $item->nombre }}</div>
                                <div class="text-muted small">{{ $item->departamento->nombre ?? '—' }}</div>
                                <div>
                                    <span class="badge bg-{{ $item->tipo_indicador === 'riesgo' ? 'danger' : 'success' }} bg-opacity-10 text-dark">
                                        {{ $item->tipo_indicador ?? 'normal' }}
                                    </span>
                                </div>
                            </label>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">No hay indicadores asociados a tu departamento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary w-100 py-3" data-mdb-ripple-init onclick="confirmarSeleccionIA()">
                    <h6>Confirmar selección</h6>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para pedir el rango de fechas antes del análisis con IA --}}
<div class="modal fade" id="modalFechasIA" tabindex="-1" aria-labelledby="modalFechasIALabel" aria-hidden="true" data-mdb-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalFechasIALabel">
                    <i class="fa-solid fa-calendar-days me-2"></i>
                    Rango de fechas para el análisis
                </h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="errorFechasIA" class="alert alert-danger py-2 d-none">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    Selecciona ambas fechas para continuar.
                </div>
                <p class="text-muted small mb-3">
                    La IA analizará los registros del periodo indicado (hasta 60 por indicador).
                </p>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="fechaInicioIA" class="form-label small text-muted fw-semibold">Desde</label>
                        <input type="date" id="fechaInicioIA" class="form-control datepicker">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="fechaFinIA" class="form-label small text-muted fw-semibold">Hasta</label>
                        <input type="date" id="fechaFinIA" class="form-control datepicker">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-mdb-ripple-init onclick="analizarCruzadosIA()">
                    <i class="fa-solid fa-play me-1"></i>
                    Analizar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
<script>
function agregarMensaje(texto, esUsuario = false, esError = false) {
    const chat = document.getElementById('chatPanelIA');
    const loader = document.getElementById('loaderPanelIA');
    const contenido = document.getElementById('contenidoPanelIA');

    loader.style.display = 'none';
    contenido.style.display = 'none';

    const div = document.createElement('div');
    div.className = 'mb-3 ' + (esUsuario ? 'text-end' : '');

    if (esError) {
        div.innerHTML = '<div class="text-danger small p-3 rounded bg-danger bg-opacity-10">' + texto + '</div>';
    } else if (esUsuario) {
        div.innerHTML = '<div class="d-inline-block bg-primary text-white small rounded-3 px-3 py-2" style="max-width: 85%;">' + escapeHtml(texto) + '</div>';
    } else {
        div.innerHTML = '<div class="bg-white border rounded-3 shadow-sm p-3 text-dark" style="line-height: 1.6; overflow-x: auto;">' + renderMarkdown(texto) + '</div>';
    }

    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}

function renderMarkdown(md) {
    let html = marked.parse(md);
    html = html.replace(/<table>/g, '<div class="table-responsive"><table class="table table-sm table-bordered table-striped align-middle mb-2">');
    html = html.replace(/<\/table>/g, '</table></div>');
    return html;
}

function limpiarMensajesChat() {
    const chat = document.getElementById('chatPanelIA');
    if (!chat) return;
    Array.from(chat.children).forEach(child => {
        if (child.id !== 'loaderPanelIA' && child.id !== 'contenidoPanelIA') {
            child.remove();
        }
    });
}

async function streamChat(body) {
    const chat = document.getElementById('chatPanelIA');
    const loader = document.getElementById('loaderPanelIA');
    const contenido = document.getElementById('contenidoPanelIA');

    loader.style.display = 'none';
    contenido.style.display = 'none';

    const url = '{{ route("analizar.cruzados.ia.usuario", $indicador->id) }}';

    let res;
    try {
        res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'text/plain',
            },
            body: JSON.stringify(body),
        });
    } catch (e) {
        return { error: 'Error de conexión.' };
    }

    const ct = (res.headers.get('content-type') || '').toLowerCase();
    if (ct.includes('application/json')) {
        let data = {};
        try { data = await res.json(); } catch (e) {}
        return { error: data.error || 'Error al obtener la respuesta.' };
    }

    if (!res.body) {
        return { error: 'Error de conexión.' };
    }

    const div = document.createElement('div');
    div.className = 'mb-3';
    const inner = document.createElement('div');
    inner.className = 'bg-white border rounded-3 shadow-sm p-3 text-dark';
    inner.style.lineHeight = '1.6';
    inner.style.whiteSpace = 'pre-wrap';
    inner.style.wordBreak = 'break-word';
    inner.style.overflowX = 'auto';
    inner.appendChild(document.createTextNode(''));
    div.appendChild(inner);
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;

    const reader = res.body.getReader();
    const dec = new TextDecoder();
    let texto = '';

    let finalizado = false;
    const finalizar = () => {
        if (finalizado) return;
        finalizado = true;
        texto += dec.decode();
        inner.style.whiteSpace = '';
        inner.style.wordBreak = '';
        try {
            inner.innerHTML = renderMarkdown(texto);
        } catch (e) {
            div.remove();
            agregarMensaje(texto, false, true);
        }
        chat.scrollTop = chat.scrollHeight;
    };

    while (true) {
        let timeout;
        const resultado = await Promise.race([
            reader.read().then(v => ({ v })),
            new Promise(resolve => { timeout = setTimeout(() => resolve({ timeout: true }), 60000); }),
        ]).finally(() => clearTimeout(timeout));

        if (resultado.timeout) {
            reader.cancel();
            break;
        }
        const { done, value } = resultado.v;
        if (done) break;
        texto += dec.decode(value, { stream: true });
        inner.textContent = texto;
        chat.scrollTop = chat.scrollHeight;
    }

    finalizar();
    cargarHistorialChats();
    return { ok: true };
}

function cargarHistorialChats() {
    const cont = document.getElementById('historialChatsIA');
    if (!cont) return;

    cont.innerHTML = '<div class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary mb-2" role="status"><span class="visually-hidden">Cargando...</span></div><small>Cargando historial...</small></div>';

    fetch('{{ route("chats.ia.lista.usuario", $indicador->id) }}', {
        headers: { 'Accept': 'application/json' },
    })
    .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
    .then(({ ok, status, data }) => {
        if (!ok) {
            console.error('Historial de chats - status', status, data);
            cont.innerHTML = '<div class="text-center py-3 text-muted"><small>Error de servidor (' + status + ').</small></div>';
            return;
        }
        const chats = data.chats || [];
        if (chats.length === 0) {
            cont.innerHTML = '<div class="text-center py-3 text-muted"><small>Sin conversaciones aún.</small></div>';
            return;
        }
        cont.innerHTML = '';
        chats.forEach(chat => {
            const item = document.createElement('div');
            item.className = 'border rounded-3 mb-2 bg-white p-1 d-flex align-items-center gap-1';
            item.innerHTML = ''
                + '<button type="button" class="btn btn-light text-start p-2 flex-grow-1" style="min-width: 0;" onclick="cargarChat(' + chat.chat_id + ')">'
                +   '<div class="d-flex justify-content-between align-items-center">'
                +     '<small class="fw-bold text-primary">' + chat.fecha + '</small>'
                +     '<span class="badge bg-primary bg-opacity-10 text-primary flex-shrink-0">' + chat.total_mensajes + '</span>'
                +   '</div>'
                +   '<div class="text-muted small" style="white-space: normal; overflow-wrap: break-word; word-break: break-word;">' + escapeHtml(chat.preview) + '</div>'
                + '</button>'
                + '<button type="button" class="btn btn-sm text-danger flex-shrink-0" data-mdb-ripple-init title="Eliminar conversación" onclick="eliminarChat(' + chat.chat_id + ')">'
                +   '<i class="fa-solid fa-trash"></i>'
                + '</button>';
            cont.appendChild(item);
        });
    })
    .catch(err => {
        console.error('Historial de chats - fetch error', err);
        cont.innerHTML = '<div class="text-center py-3 text-muted"><small>Error al cargar historial.</small></div>';
    });
}

function cargarChat(chatId) {
    const chat = document.getElementById('chatPanelIA');
    const loader = document.getElementById('loaderPanelIA');
    const contenido = document.getElementById('contenidoPanelIA');
    if (!chat) return;

    loader.style.display = 'none';
    contenido.style.display = 'none';
    limpiarMensajesChat();
    chat.dataset.chatVisible = chatId;

    const url = "{{ route('chat.ia.mensajes.usuario', [$indicador->id, 'CHAT_ID']) }}".replace('CHAT_ID', chatId);

    fetch(url, {
        headers: { 'Accept': 'application/json' },
    })
    .then(res => res.json())
    .then(data => {
        const mensajes = data.mensajes || [];
        let visibles = 0;
        let primerUserSaltado = false;
        mensajes.forEach(m => {
            if (m.role === 'system') return;
            if (m.role === 'user') {
                if (!primerUserSaltado) {
                    primerUserSaltado = true;
                    return;
                }
                visibles++;
                agregarMensaje(m.content, true);
            } else if (m.role === 'assistant') {
                visibles++;
                agregarMensaje(m.content);
            }
        });
        if (visibles === 0) {
            agregarMensaje('Esta conversación no tiene mensajes.', false, true);
        }
    })
    .catch(() => {
        agregarMensaje('Error al cargar la conversación.', false, true);
    });
}

function eliminarChat(chatId) {
    if (!confirm('¿Eliminar esta conversación?')) return;

    const url = "{{ route('chat.ia.eliminar.usuario', [$indicador->id, 'CHAT_ID']) }}".replace('CHAT_ID', chatId);

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            cargarHistorialChats();
            const chat = document.getElementById('chatPanelIA');
            if (chat && chat.dataset.chatVisible == chatId) {
                limpiarMensajesChat();
                const contenido = document.getElementById('contenidoPanelIA');
                if (contenido) contenido.style.display = '';
            }
        } else {
            alert('Error al eliminar la conversación.');
        }
    })
    .catch(() => {
        alert('Error de conexión al eliminar.');
    });
}

let indicadoresSeleccionados = [];

function abrirSeleccionIndicadores() {
    const error = document.getElementById('errorFechasIA');
    if (error) error.classList.add('d-none');

    const modalEl = document.getElementById('modalSeleccionIndicadoresIA');
    if (typeof mdb !== 'undefined' && modalEl) {
        const modal = mdb.Modal.getInstance(modalEl) || new mdb.Modal(modalEl);
        modal.show();
    }
}

function confirmarSeleccionIA() {
    indicadoresSeleccionados = Array.from(
        document.querySelectorAll('.indicador-seleccion-ia-checkbox:checked')
    ).map(cb => parseInt(cb.value));

    const contador = document.getElementById('contadorSeleccionados');
    const total = document.querySelectorAll('.indicador-seleccion-ia-checkbox').length;
    if (contador) contador.textContent = indicadoresSeleccionados.length;

    const modalEl = document.getElementById('modalSeleccionIndicadoresIA');
    if (typeof mdb !== 'undefined' && modalEl) {
        const modal = mdb.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    abrirModalFechas();
}

function seleccionarTodos() {
    document.querySelectorAll('.indicador-seleccion-ia-checkbox').forEach(cb => cb.checked = true);
}

function quitarTodos() {
    document.querySelectorAll('.indicador-seleccion-ia-checkbox').forEach(cb => cb.checked = false);
}

function abrirModalFechas() {
    const error = document.getElementById('errorFechasIA');
    if (error) error.classList.add('d-none');

    const modalEl = document.getElementById('modalFechasIA');
    if (typeof mdb !== 'undefined' && modalEl) {
        const modal = mdb.Modal.getInstance(modalEl) || new mdb.Modal(modalEl);
        modal.show();
    }
}

function analizarCruzadosIA() {
    const fechaInicio = document.getElementById('fechaInicioIA');
    const fechaFin = document.getElementById('fechaFinIA');
    const desde = fechaInicio ? fechaInicio.value : '';
    const hasta = fechaFin ? fechaFin.value : '';

    const error = document.getElementById('errorFechasIA');
    if (!desde || !hasta) {
        if (error) error.classList.remove('d-none');
        return;
    }

    const modalEl = document.getElementById('modalFechasIA');
    if (typeof mdb !== 'undefined' && modalEl) {
        const modal = mdb.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    const inputPregunta = document.getElementById('inputPreguntaIA');
    if (inputPregunta) {
        inputPregunta.focus();
    }

    const chat = document.getElementById('chatPanelIA');
    const contenido = document.getElementById('contenidoPanelIA');

    contenido.style.display = 'none';
    limpiarMensajesChat();
    chat.dataset.chatVisible = '';

    streamChat({ fecha_inicio: desde, fecha_fin: hasta, indicadores: indicadoresSeleccionados }).then(result => {
        if (result && result.error) {
            agregarMensaje(result.error, false, true);
        }
    });
}

function enviarPregunta() {
    const input = document.getElementById('inputPreguntaIA');
    const pregunta = input.value.trim();
    if (!pregunta) return;

    input.value = '';
    agregarMensaje(pregunta, true);

    streamChat({ question: pregunta }).then(result => {
        if (result && result.error) {
            agregarMensaje(result.error, false, true);
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscadorIndicadoresSeleccionIA');
    if (buscador) {
        buscador.addEventListener('input', function () {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll('.item-indicador-seleccion-ia').forEach(item => {
                const nombre = item.getAttribute('data-nombre');
                item.style.display = nombre.includes(filtro) ? '' : 'none';
            });
        });
    }

    const input = document.getElementById('inputPreguntaIA');
    if (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarPregunta();
            }
        });
    }

    cargarHistorialChats();
});
</script>
<style>
#chatPanelIA .table th,
#chatPanelIA .table td {
    vertical-align: middle;
    white-space: normal;
}
.btn-check:checked + .custom-check {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

.btn-check:checked + .custom-check .fw-bold,
.btn-check:checked + .custom-check .text-muted {
    color: #fff !important;
}

.btn-check:checked + .custom-check .badge {
    background-color: rgba(255, 255, 255, 0.85) !important;
    color: #0d6efd !important;
}

.custom-check {
    transition: all 0.2s ease;
}

.dots::after {
    content: '';
    animation: dots 1.5s steps(3, end) infinite;
}
@keyframes dots {
    0% { content: ''; }
    33% { content: '.'; }
    66% { content: '..'; }
    100% { content: '...'; }
}
</style>
@endsection
