<?php
use App\Http\Controllers\apartadoNormaController;
use App\Http\Controllers\perspectivaController;
use App\Http\Controllers\quejasController;
use App\Http\Controllers\normaController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\campoPrecargadoController;
use App\Http\Controllers\encuestaController;
use App\Http\Controllers\campoVacioController;
use App\Http\Controllers\cuestionarioController;
use App\Http\Controllers\departamentoController;
use App\Http\Controllers\indicadorController;
use App\Http\Controllers\indicadoresLlenosController;
use App\Http\Controllers\informacionForaneaController;
use App\Http\Controllers\evaluacionProveedorController;
use App\Http\Controllers\CamposForaneosImportController;
use App\Http\Controllers\userController;
use App\Models\CampoPrecargado;
use App\Models\Indicador;
use Illuminate\Support\Facades\Route;




//Usuarios
Route::get('/', [userController::class, 'index'])->name('login');
Route::post('/login_user', [userController::class, 'login_user'])->name('login.user');
Route::get('/perfil_usuario', [userController::class, 'perfil_user'])->name('perfil.usuario')->middleware('auth');

//Rutas del llenado de los indicadores
Route::get('/perfil_usuario/indicador/{indicador}', [indicadorController::class, 'show_indicador_user'])->name('show.indicador.user')->middleware('auth');
Route::get('/perfil_usuario/indicador_robusto/{indicador}', [indicadorController::class, 'show_indicador_robusto_user'])->name('show.indicador.robusto.user')->middleware('auth');



Route::get('/perfil_usuario/cumplimiento_normativo/', [normaController::class, 'cumplimiento_normativo_user'])->name('cumplimiento.normativo.user')->middleware('auth');

Route::get('/perfil_usuario/cumplimiento_normativo/registro_cumplimiento_normativo/{norma}', [apartadoNormaController::class, 'registro_cumplimiento_normativa_index'])->name('registro.cumplimiento.normativa.index')->middleware('auth');

Route::post('/perfil_usuario/cumplimiento_normativo/registro_cumplimiento_normativo/resgistro_actividad/', [apartadoNormaController::class, 'registro_actividad_cumplimiento_norma'])->name('registro.actividad.cumplimiento.norma')->middleware('auth');

Route::get('/perfil_usuario/cumplimiento_normativo/registro_cumplimiento_normativo/registro_actividad/evidencias/{apartado}', [apartadoNormaController::class, 'ver_evidencia_cumplimiento_normativo'])->name('ver.evidencia.cumplimiento.normativo')->middleware('auth');


// esta es la ruta del las evaluaciondes de los clientes
Route::get('/perfil_usuario/encuestas_clientes/', [userController::class, 'encuesta_clientes_user'])->name('encuesta.clientes.user')->middleware('auth');

Route::get('/perfil_usuario/encuestas_clientes/encuesta/{encuesta}', [userController::class, 'encuesta_index_user'])->name('encuesta.index.user')->middleware('auth');

// Route::get('/perfil_usuario/cuencuestas_clientes/respuestas/{encuesta}', [userController::class, 'show_respuestas_usuario'])->name('show.respuestas.usuario');

Route::get('/perfil_usuario/encuestas/respuestas_clientes/{cliente}/{encuesta}', [userController::class, 'show_respuestas_usuario'])->name('show.respuestas.usuario')->middleware('auth');


// aqui van a estar las rutas para el fucking llenado de indicadores
Route::post('/perfil_usuario/indicador/llenado_de_informacion/{indicador}', [indicadorController::class, 'llenado_informacion_indicadores'])->name('llenado.informacion.indicadores')->middleware('auth');




Route::post('/perfil_usuario/agregar_evauacion/{user}', [userController::class, 'evaluacion_servicio_store'])->name('evaluacion.servicio.store')->middleware('auth');

Route::get('/perfil_usuario/evaluaciones_proveedores', [evaluacionProveedorController::class, 'evaluaciones_show_user'])->name('evaluaciones.show.user')->middleware('auth');
// aqui van a estar las rutas para el fucking llenado de indicadores





//Administradores
Route::get('/admin_login', [adminController::class, 'login'])->name('admin.login.index');

Route::post('/admin_login/ingreso_perfil_admin', [adminController::class, 'ingreso_admin'])->name('admin.login.entrar');


Route::get('/perfil_admin', [adminController::class, 'perfil_admin'])->name('perfil.admin')->middleware('auth:admin');


Route::post('/perfil_admin/agregar_cliente', [adminController::class, 'agregar_cliente'])->name('agregar.cliente');


Route::delete('/perfil_admin/eliminar_cliente/{cliente}', [adminController::class, 'eliminar_cliente'])->name('eliminar.cliente')->middleware('auth:admin');


Route::patch('/perfil_admin/editar_cliente/{cliente}', [adminController::class, 'editar_cliente'])->name('editar.cliente')->middleware('auth:admin');


Route::post('/perfil_admin/agregar_usuario', [adminController::class, 'agregar_usuario'])->name('agregar.usuario')->middleware('auth:admin');

Route::post('/perfil_admin/agregar_departamento', [adminController::class, 'agregar_departamento'])->name('agregar.departamento')->middleware('auth:admin');


//Rutas para ver el panel y agregar indicadores
Route::get('/perfil_admin/agregar_indicadores/{departamento}', [indicadorController::class, 'agregar_indicadores_index'])->name('agregar.indicadores.index')->middleware('auth:admin');



Route::post('/perfil_admin/agregar_indicadores/agregar_indicador/{departamento}', [indicadorController::class, 'agregar_indicadores_store'])->name('agregar.indicadores.store')->middleware('auth:admin');





Route::patch('/perfil_admin/agregar_indicadores/editar_usuario/{usuario}', [userController::class, 'editar_usuario'])->name('editar.usuario')->middleware('auth:admin');




//RUTA QUE ELIMINA EL DEPARTAMENTO
Route::delete('/perfil_admin/eliminar_departamento/{departamento}', [departamentoController::class, 'eliminar_departamento'])->name('eliminar.departamento')->middleware('auth:admin');

Route::patch('/perfil_admin/actualiza_departamento/{departamento}', [departamentoController::class, 'actuliza_departamento'])->name('actualizar.departamento')->middleware('auth:admin');

Route::delete('/perfil_admin/elimina_usuario/{usuario}', [userController::class, 'eliminar_usuario'])->name('eliminar.usuario')->middleware('auth:admin');



//Rutas de los indicadores
Route::get('/perfil_admin/agregar_indicadores/indicador/{indicador}', [indicadorController::class, 'indicador_index'])->name('indicador.index')->middleware('auth:admin');

Route::delete('/perfil_admin/agregar_indicadores/{indicador}', [indicadorController::class, 'borrar_indicador'])->name('borrar.indicador')->middleware('auth:admin');

Route::patch('/perfil_admin/agregar_indicadores/editando_indicador/{indicador}', [indicadorController::class, 'indicador_edit'])->name('indicador.edit')->middleware('auth:admin');



//Rutas que agregan los campos de los indicadores
Route::post('/perfil_admin/agregar_indicadores/indicador/agregar_campo_vacio/{indicador}', [campoVacioController::class, 'agregar_campo_vacio'])->name('agregar.campo.vacio')->middleware('auth:admin');

//Agregando campos precargados
Route::post('/perfil_admin/agregar_indicadores/indicador/agregar_campo_precargado/{indicador}', [campoPrecargadoController::class, 'agregar_campo_precargado'])->name('agregar.campo.precargado')->middleware('auth:admin');


//Agregando la informacion foranea
Route::post('/perfil_admin/agregar_info_foranea', [informacionForaneaController::class, 'agregar_informacion_foranea'])->name('agregar.info.foranea')->middleware('auth:admin');



//CREAR INPUT_PRECARGADO que sera llenado con la informacion mes a mes...
Route::post('/perfil_admin/informaion_foranea/agregar_campo_precargado', [campoPrecargadoController::class, 'crear_campo_precargado'])->name('crear_campo_precargado')->middleware('auth:admin');


///PRUEBAS CON EL ARCHIVO DE EXCEL

Route::post('/perfil_admin/informacion_foranea/cargando_excel', [CamposForaneosImportController::class, 'importar'])->name('importar_excel')->middleware('auth:admin');

//PRUEBAS CON EL ARCHIVO DE EXCEL

//Eliminando campos la ctm
Route::delete('/perfil_admin/agregar_indicadores/indicador/campo_borrado/{campo}/{tipo_campo}', [indicadorController::class, 'borrar_campo'])->name('eliminar.campo')->middleware('auth:admin');

Route::put('/perfil_admin/agregar_indicadores/indicador/campo_editado/{campo}/{tipo_campo}', [indicadorController::class, 'editar_campo'])->name('editar.campo')->middleware('auth:admin');

//Creando el campo promedio
Route::post("/perfil_admin/agregar_indicadores/indicador/crear_campo_promedio/{indicador}", [indicadorController::class, "input_promedio_guardar"])->name("input.promedio.guardar")->middleware('auth:admin');


//Creando el campo de multiplicacion
Route::post("/perfil_admin/agregar_indicadores/indicador/crear_campo_multiplicacion/{indicador}", [indicadorController::class, "input_multiplicacion_guardar"])->name('input.multiplicacion.guardar')->middleware('auth:admin');

//Creando el campo de la suam
Route::post('/perfil_admin/agregar_indicadores/indicador/crear_campo_suma/{indicador}', [indicadorController::class, "input_suma_guardar"])->name('input.suma.guardar')->middleware('auth:admin');

Route::post('/perfil_admin/agregar_indicadores/indicador/crear_campo_division/{indicador}', [indicadorController::class, 'input_division_guardar'])->name('input.division.guardar')->middleware('auth:admin');


Route::post('/perfil_admin/agregar_indicadores/indicador/crear_campo_resta/{indicador}', [indicadorController::class, 'input_resta_guardar'])->name('input.resta.guardar')->middleware('auth:admin');

Route::post('/perfil_admin/agregar_indicadores/indicador/crear_campo_porcentaje/{indicador}', [indicadorController::class, 'input_porcentaje_guardar'])->name('input.porcentaje.guardar')->middleware('auth:admin');

Route::post('/perfil_admin', [userController::class, 'cerrar_session'])->name('cerrar.session')->middleware('auth:admin');

//Aui van las rutas de las encuestas para los clientes
Route::post('/perfil_admin/agregar_indicadores/agregar_encuesta/{departamento}', [encuestaController::class, 'encuesta_store'])->name('encuesta.store')->middleware('auth:admin');

Route::post('/perfil_admin/encuestas/', [encuestaController::class, 'encuesta_store_two'])->name('encuesta.store.two')->middleware('auth:admin');

Route::delete('/perfil_admin/agregar_indicadores/eliminar_encuesta/{encuesta}', [encuestaController::class, 'encuesta_delete'])->name('encuesta.delete')->middleware('auth:admin');

Route::patch('/perfil_admin/agregar_indicadores/editar_encuesta/{encuesta}', [encuestaController::class, 'encuesta_edit'])->name('encuesta.edit')->middleware('auth:admin');

Route::post('/perfil_admin/agregar_indicadores/encuesta/agregar_pregunta/{encuesta}', [encuestaController::class, 'pregunta_store'])->name('pregunta.store')->middleware('auth:admin');

Route::delete('/perfil_admin/agregar_indicadores/encuesta/eliminar_pregunta/{pregunta}', [encuestaController::class, 'pregunta_delete'])->name('pregunta.delete')->middleware('auth:admin');

//Reacomodando los HTML
Route::get('/perfil_admin/departamentos', [departamentoController::class, "departamentos_show_admin"])->name('departamentos.show.admin')->middleware('auth:admin');

Route::get('/perfil_admin/clientes', [clienteController::class, 'clientes_show_admin'])->name('clientes.show.admin')->middleware('auth:admin');

Route::get('/perfil_admin/usuarios', [userController::class, 'usuarios_show_admin'])->name("usuarios.show.admin")->middleware('auth:admin');

Route::get('/perfil_admin/encuestas', [encuestaController::class, 'encuestas_show_admin'])->name('encuestas.show.admin')->middleware('auth:admin');

Route::get('/perfil_admin/encuestas/preguntas/{encuesta}', [encuestaController::class, 'encuesta_index'])->name('encuesta.index')->middleware('auth:admin');

Route::get('/perfil_admin/reclamaciones', [quejasController::class, 'index_quejas'])->name('lista.quejas.cliente')->middleware('auth:admin');

Route::get('/perfil_admin/proveedores', [proveedorController::class, 'proveedores_show_admin'])->name('proveedores.show.admin')->middleware('auth:admin');


Route::delete('/perfil_admin/proveedores/eliminar/{proveedor}', [proveedorController::class, 'proveedor_delete']  )->name('proveedor.delete')->middleware('auth:admin');


Route::get('/perfil_admin/proveedores/detalle_evaluacion/{proveedor}', [evaluacionProveedorController::class, 'detalle_evaluacion_proveedor'])->name('detalle.evaluacion.proveedor')->middleware('auth:admin');

Route::get('/perfil_admin/informacion_foranea', [informacionForaneaController::class, 'informacion_foranea_show_admin'])->name('informacion.foranea.show.admin')->middleware('auth:admin');

Route::get('/perfil_admin/encuestas/respuestas_clientes/{cliente}/{encuesta}', [clienteController::class, 'show_respuestas'])->name('show.respuestas')->middleware('auth:admin');

Route::get('perfil_admin/lista_indicadores/encuesta/{encuesta}', [encuestaController::class, 'encuesta_llena_show_admin'] )->name('encuesta.llena.show.admin')->middleware('auth:admin');

Route::get('/perfil_admin/normas/', [normaController::class, 'cumplimiento_norma_show_admin'])->name('cumplimiento.norma.show.admin')->middleware('auth:admin');

Route::post('/perfil_admin/normas/agregar/{departamento}', [normaController::class, 'norma_store'])->name('norma.store')->middleware('auth:admin');

Route::delete('/perfil_admin/normas/borrar/{norma}', [normaController::class, 'norma_delete'])->name('norma.delete')->middleware('auth:admin');

Route::patch('/perfil_admin/normas/editar/{norma}', [normaController::class, 'norma_update'])->name('norma.update')->middleware('auth:admin');

Route::get('/perfil_admin/normas/apartado/{norma}', [normaController::class, 'apartado_norma'])->name('apartado.norma')->middleware('auth:admin');

Route::post('/perfil_admin/normas/apartado/agregar_apartado/{norma}', [apartadoNormaController::class, 'apartado_norma_store'])->name('apartado.norma.store')->middleware('auth:admin');

Route::delete('/perfil_admin/normas/apartado/eliminar_apartado/{apartado}', [apartadoNormaController::class, 'delete_apartado_norma'])->name('delete.apartado.norma')->middleware('auth:admin');

Route::patch('/perfil_admin/normas/apartado/editar_apartado/{apartado}', [apartadoNormaController::class, 'edit_apartado_norma'])->name('edit.apartado.norma')->middleware('auth:admin');

Route::get('/perfil_admin/normas/apartado_admin/{apartado}', [apartadoNormaController::class, 'ver_evidencia_cumplimiento_normativo_admin'])->name('ver.evidencia.cumplimiento.normativo.admin')->middleware('auth:admin');


//rutas que muestran los indicadores de cada departamento
Route::get('/perfil_admin/lista_indicadores/{departamento}', [indicadorController::class, 'lista_indicadores_admin'])->name('lista.indicadores.admin')->middleware('auth:admin');


Route::get('/perfil_admin/lista_indicadores/detalle_indicador/{indicador}', [indicadorController::class, 'indicador_lleno_show_admin'])->name('indicador.lleno.show.admin')->middleware('auth:admin');

//Rutas del seguimiento de las quejas desde el perifl del admin
Route::get('/perfil_admin/quejas/seguimiento_quejas/{queja}',[quejasController::class, 'seguimiento_quejas_admin'])->name('seguimiento_quejas.admin')->middleware('auth:admin');

//rutas de las evaluaciones de los proveedores
Route::post('/perfil_admin/proveedores/', [proveedorController::class, 'proveedor_store'])->name('proveedor.store')->middleware('auth:admin');


Route::get('/perfil_admin/logs', [adminController::class, 'logs'])->name('logs.show.admin')->middleware("auth:admin");
Route::delete('/perfil_admin/logs/borrar', [adminController::class, 'logs_delete_all'])->name('logs.delete.all')->middleware("auth:admin");
Route::delete('/perfil_admin/logs/borrar/{log}', [adminController::class, 'logs_delete_one'])->name('logs.delete.one')->middleware("auth:admin");






//aqui van a ir las rutas para eso de las perspectivas
Route::get('/perfil_admin/perspectivas', [perspectivaController::class, 'perspectivas_show'])->name('perspectivas.show')->middleware('auth:admin');
Route::post('/perfil_admin/perspectiva/store', [perspectivaController::class, 'perspectiva_store'])->name('perspectiva.store');
Route::delete('/perfil_admin/perspectiva/delete/{perspectiva}', [perspectivaController::class, 'perspectiva_delete'])->name('eliminar.perspectiva');
Route::patch('/perfil_admin/perspectiva/edit/{perspectiva}', [perspectivaController::class, 'edit_perspectiva'])->name('edit.perspectiva');
Route::get('/perfil_admin/perspectiva/objetivo/{perspectiva}', [perspectivaController::class, 'detalle_perspectiva'])->name('detalle.perspectiva')->middleware('auth:admin');


Route::post('/perfil_admin/perspectiva/objetivo/post/{perspectiva}', [perspectivaController::class, 'objetivo_store'])->name('objetivo.store');

Route::delete('/perfil_admin/perspectiva/objetivo/delete_objetivo/{objetivo}', [perspectivaController::class, 'objetivo_delete'])->name('objetivo.delete');


Route::put('/perfil_admin/perspectiva/objetivo/delete_indicador_objetivo/{objetivo}/{indicador}', [perspectivaController::class, 'indicador_objetivo_delete'])->name('indicador.objetivo.delete');

Route::put('/perfil_admin/perspectiva/objetivo/delete_encuesta_objetivo/{objetivo}/{encuesta}', [perspectivaController::class, 'encuesta_objetivo_delete'])->name('encuesta.objetivo.delete');


Route::put('/perfil_admin/perspectiva/objetivo/delete_norma_objetivo/{objetivo}/{norma}', [perspectivaController::class, 'norma_objetivo_delete'])->name('norma.objetivo.delete');


Route::patch('/perfil_admin/perspectiva/objetivo/update/{objetivo}', [perspectivaController::class, 'objetivo_update'])->name('objetivo.update');
Route::post('/perfil_admin/perspectiva/objetivo/agregar_indicador_a_objetivo/{objetivo}', [perspectivaController::class, 'add_indicador_objetivo'])->name('add.indicador.objetivo');

Route::post('/perfil_admin/perspectiva/objetivo/agregar_ponderacion_indicador/{indicador}', [perspectivaController::class, 'agregar_ponderacion_indicador_objetivo'])->name('agregar.ponderacion.indicador.objetivo');

Route::post('/perfil_admin/perspectiva/objetivo/agregar_ponderacion_encuesta/{encuesta}', [perspectivaController::class, 'agregar_ponderacion_encuesta_objetivo'])->name('agregar.ponderacion.encuesta.objetivo');

Route::post('/perfil_admin/perspectiva_objetivo/agregar_ponderacion_norma/{norma}', [perspectivaController::class, 'agregar_ponderacion_norma_objetivo'])->name('agregar.ponderacion.norma.objetivo');


//aqui van a ir las rutas para eso de las perspectivas





//agregando indicadores solo para lectura a otros departamentos
Route::post('/perfil_admin/agregar_indicadores_forneos/{departamento}', [indicadorController::class, 'indicador_foraneo_store'])->name('indicador.foraneo.store')->middleware('auth:admin');
Route::delete('/perfil_admin/eliminar_indicador_foraneo/{departamento}/{indicador}', [indicadorController::class, 'eliminar_indicador_foraneo'])->name('eliminar.indicador.foraneo')->middleware('auth:admin');

//ruta del perifl del usuario para poder ver los indicadores foraneos que se le agregaron
Route::get('/perfil_usuario/indicadores_foraneos', [indicadorController::class, 'indicadores_foraneos_user'])->name('indicadores.foraneos.user')->middleware('auth');

Route::get('/perfil_usuario_indicadores_foraneos/indicador_foraneo/{indicador}', [indicadorController::class, 'indicador_lleno_show_user_foraneo'])->name('indicador.lleno.show.user.foraneo')->middleware('auth');







Route::get('/login_cliente',[clienteController::class, 'login'])->name('login.cliente');

Route::post('/ingreso_cliente', [clienteController::class, 'index_cliente'])->name('index.cliente');

Route::get('/perfil_cliente', [clienteController::class, 'perfil_cliente'])->name('perfil.cliente')->middleware('auth:cliente');

//Para contestar los cuestionarios
Route::get('/perfil_cliente/cuestionario/{encuesta}', [clienteController::class, 'index_encuesta'])->name("index.encuesta")->middleware('auth:cliente');

Route::post('/perfil_cliente/cuestionario/contestando/{encuesta}', [clienteController::class, 'contestar_encuesta'])->name("contestar.encuesta")->middleware('auth:cliente');

Route::get('/perfil_cliente/cuestionario/contestado/{encuesta}', [clienteController::class, 'index_encuesta_contestada'])->name('index.encuesta.contestada')->middleware('auth:cliente');

Route::post('/perfil_cliente/queja', [clienteController::class, 'queja_cliente'])->name('queja.cliente')->middleware('auth:cliente');

Route::get('/perfil_cliente/lista_quejas', [clienteController::class, 'lista_quejas_clientes'])->name('lista.quejas.clientes.clientes')->middleware('auth:cliente');

Route::get('/perfil_cliente/seguimientos/{queja}', [clienteController::class, 'seguimiento_quejas_cliente'])->name('seguimiento.quejas.cliente')->middleware('auth:cliente');

Route::post('/perfil_cliente/seguimientos/comentando/{queja}', [clienteController::class, 'comentario_user_reclamo'])->name('comentario.user.reclamo');



Route::post('/perfil_cliente', [userController::class, 'cerrar_session_cliente'])->name('cerrar.session.cliente');





//Ruta para el eliminado de la info del indicador
Route::delete('perfil_usuario/indicador/eliminar_info/{id}', [indicadorController::class, 'borrar_info_indicador'])->name('borrar.info.indicador')->middleware('auth');


//Ruta para mostrar la visualizacion de las encuestas
Route::get('perfil_usuario/encuestas_clientes_user', [encuestaController::class, 'ver_encuestas_user'])->name('ver.encuestas.user')->middleware('auth');
Route::get('perfil_usuario/encuestas_clientes_user/contestar_encuesta/{encuesta}', [encuestaController::class, 'encuesta_contestar_user'])->name('encuesta.contestar.user')->middleware('auth');
Route::post('perfil_usuario/encuestas_clientes_user/contestando/{encuesta}', [clienteController::class, 'contestando_encuesta_user'] )->name('contestando.encuesta.user')->middleware('auth');








//escudriñando la informacion que se da en el indicador
Route::get('perfil_admin/lista_indicadores/escudriñando_indicador/{indicador}', [indicadorController::class, 'analizar_indicador'])->name('analizar.indicador')->middleware("auth:admin");

Route::get('perfil_admin/lista_indicadores/estacionalidad_indicador/{indicador}', [indicadorController::class, 'estacionalidad_show'])->name('estacionalidad.show');



//Rutas de el escudriño de datos pero ahora desde la vista de los usuarios
Route::get('perfil_usuario/analizando_datos/{indicador}', [indicadorController::class, 'analizar_indicador_usuario'])->name('analizar.indicador.usuario');



//Rutas que me llevan a los KPI con las fechas que se dan en el panle de las perspectivas







//filtrado de indicadores general

Route::get('perfil_usuario/indicadores_revision/', [indicadorController::class, 'indicadores_revision'])->name('revision.indicadores');