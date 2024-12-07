<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

//crear un nuevo usuario en BD
// Route::get('nuevousuario', function(){
// 	$user = new App\User;
// 	$user->empleado_id = 3;
// 	$user->name = 'Gabriel';
// 	$user->email = 'gabriel@gmail.com';
// 	$user->clave = 'gabriel';
// 	$user->password = bcrypt($user->clave);
// 	$user->save();
// 	return $user;
// });


// Route::get('/', function () {
//     return view('principal.index');
// });


// use App\Mail\sendGuiaPsico;
// use Illuminate\Support\Facades\Mail;

// Route::get('/mail', function(){

//     // return (new sendGuiaPsico("Edgar"))->render();

//     // Este objeto acepta un modelo de eleocuent, o un arreglo de email
//     // $response = Mail::to('ecano@results-in-performance.com')->queue(new sendGuiaPsico('Edgar')); 

//     $response = Mail::to('agutierrez@results-in-performance.com')->send(new sendGuiaPsico('Edgar'));

//     dump($response);
// });


use Illuminate\Support\Facades\Crypt;
//==============================================


Route::resource('inicio', 'seguridad\inicioController');

Route::get('/', ['as' => 'inicio.index', 'uses' => 'seguridad\inicioController@index']);

Route::get('/tablero', ['as' => 'inicio.index', 'uses' => 'seguridad\inicioController@tablero']);

Route::get('graficas', ['as' => 'graficas', 'uses' => 'seguridad\inicioController@graficas']);



//==============================================


Route::get('consultanotificaciones', ['as' => 'inicio.consultanotificaciones', 'uses' => 'seguridad\inicioController@consultanotificaciones']);

Route::resource('prueba', 'proyecto\pruebaController');


//==============================================

Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);

Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);

Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////USUARIOS////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('usuario', 'usuario\usuarioController');

Route::get('usuariotabla', ['as' => 'usuario.usuariotabla', 'uses' => 'usuario\usuarioController@usuariotabla']);

Route::get('usuariofoto/{usuario_id}', ['as' => 'usuariofoto', 'uses' => 'seguridad\inicioController@usuariofoto']);

Route::get('usuarioestado/{usuario_id}/{usuario_estado}', ['as' => 'usuarioestado', 'uses' => 'usuario\usuarioController@usuarioestado']);

Route::get('usuarioeliminar/{usuario_id}/{usuario_tipo}/{empleado_id}', ['as' => 'usuarioeliminar', 'uses' => 'usuario\usuarioController@usuarioeliminar']);

//==============================================


// En tu archivo de rutas (web.php)

Route::get('/opciones/{etiquetaId}', ['as' => 'cliente.obteneretiquetas', 'uses' => 'clientes\clienteController@obteneretiquetas']);

Route::get('obtenerActividadesCronograma/{ID_CONTRATO}/{ID_PROYECTO}', ['as' => 'cliente.obtenerActividadesCronograma', 'uses' => 'clientes\clienteController@obtenerActividadesCronograma']);

Route::get('eliminarActividadCronograma/{id}', ['as' => 'eliminarActividadCronograma', 'uses' => 'clientes\clienteController@eliminarActividadCronograma']);

Route::get('generarConcentradoActividades/{id_contrato}/{id_proyecto}', ['as' => 'generarConcentradoActividades', 'uses' => 'clientes\clienteController@generarConcentradoActividades']);


Route::get('/estructura-cliente/{clienteId}', ['as' => 'cliente.obtenerEstructuraCliente', 'uses' => 'clientes\clienteController@obtenerEstructuraCliente']);



Route::resource('cliente', 'clientes\clienteController');

Route::get('tablacliente', ['as' => 'cliente.tablacliente', 'uses' => 'clientes\clienteController@tablacliente']);

Route::get('tablaAnexos/{contrato_id}', ['as' => 'cliente.tablaAnexos', 'uses' => 'clientes\clienteController@tablaAnexos']);


Route::get('clientetablaconvenios/{contrato_id}', ['as' => 'cliente.clientetablaconvenios', 'uses' => 'clientes\clienteController@clientetablaconvenios']);

Route::get('clientetablacontratos/{cliente_id}', ['as' => 'cliente.clientetablacontratos', 'uses' => 'clientes\clienteController@clientetablacontratos']);

Route::get('clientetablaconvenioseliminar/{convenio_id}', ['as' => 'cliente.clientetablaconvenioseliminar', 'uses' => 'clientes\clienteController@clientetablaconvenioseliminar']);


Route::get('clientetablacontratoeliminar/{ID_CONTRATO}', ['as' => 'cliente.clientetablacontratoeliminar', 'uses' => 'clientes\clienteController@clientetablacontratoeliminar']);

Route::get('tablaclientedocumentos/{contrato_id}', ['as' => 'cliente.tablaclientedocumentos', 'uses' => 'clientes\clienteController@tablaclientedocumentos']);

Route::get('clientedocumentopdf/{documento_id}', ['as' => 'cliente.clientedocumentopdf', 'uses' => 'clientes\clienteController@clientedocumentopdf']);

Route::get('clientedocumentocierrepdf/{documento_id}', ['as' => 'cliente.clientedocumentocierrepdf', 'uses' => 'clientes\clienteController@clientedocumentocierrepdf']);

Route::get('tablaclientedocumentoscierre/{contrato_id}', ['as' => 'cliente.tablaclientedocumentoscierre', 'uses' => 'clientes\clienteController@tablaclientedocumentoscierre']);

Route::get('clientelogo/{logo_tipo}/{contrato_id}', ['as' => 'cliente.clientelogo', 'uses' => 'clientes\clienteController@clientelogo']);

Route::get('logoPlantilla/{id}', ['as' => 'cliente.logoPlantilla', 'uses' => 'clientes\clienteController@logoPlantilla']);


Route::get('clientetablapartidas/{contrato_id}/{convenio_id}', ['as' => 'cliente.clientetablapartidas', 'uses' => 'clientes\clienteController@clientetablapartidas']);

Route::get('clientepartidaeliminar/{partida_id}', ['as' => 'cliente.clientepartidaeliminar', 'uses' => 'clientes\clienteController@clientepartidaeliminar']);

Route::get('clientepartidaBloqueo/{partida_id}/{accion}', ['as' => 'cliente.clientepartidaBloqueo', 'uses' => 'clientes\clienteController@clientepartidaBloqueo']);


Route::get('clientebloqueo/{cliente_id}/{cliente_estado}', ['as' => 'cliente.clientebloqueo', 'uses' => 'clientes\clienteController@clientebloqueo']);

Route::get('finalizarContrato/{contrato_id}', ['as' => 'cliente.finalizarContrato', 'uses' => 'clientes\clienteController@finalizarContrato']);

Route::get('autorizardocumento/{contrato_id}/{nombre}', ['as' => 'cliente.autorizardocumento', 'uses' => 'clientes\clienteController@autorizardocumento']);

Route::get('tablaplantilla/', ['as' => 'cliente.tablaplantilla', 'uses' => 'clientes\clienteController@tablaplantilla']);

Route::get('listalogo/{id}', ['as' => 'listalogo', 'uses' => 'clientes\clienteController@listalogo']);

Route::get('catalogoimageneseliminar/{id}', ['as' => 'cliente.catalogoimageneseliminar', 'uses' => 'clientes\clienteController@catalogoimageneseliminar']);

Route::get('clienteanexoeliminar/{anexo_id}', ['as' => 'cliente.clienteanexoeliminar', 'uses' => 'clientes\clienteController@clienteanexoeliminar']);


//==============================================


Route::get('cotizacionproveedorBloqueo/{cotizacion_id}/{accion}', ['as' => 'catalogos.cotizacionproveedorBloqueo', 'uses' => 'catalogos\servicioController@cotizacionproveedorBloqueo']);
Route::get('partidaproveedorBloqueo/{partida_id}/{accion}', ['as' => 'catalogos.partidaproveedorBloqueo', 'uses' => 'catalogos\servicioController@partidaproveedorBloqueo']);


Route::resource('proveedor', 'catalogos\proveedorController');

Route::get('tablaproveedor', ['as' => 'proveedor.tablaproveedor', 'uses' => 'catalogos\proveedorController@tablaproveedor']);

Route::get('finalizarcaptura/{proveedor_id}', ['as' => 'proveedor.finalizarcaptura', 'uses' => 'catalogos\proveedorController@finalizarcaptura']);

Route::get('proveedorbloqueo/{proveedor_id}/{proveedor_estado}', ['as' => 'proveedor.proveedorbloqueo', 'uses' => 'catalogos\proveedorController@proveedorbloqueo']);

//==============================================

Route::resource('proveedordomicilio', 'catalogos\proveedordomicilioController');

Route::get('tablaproveedordomicilio/{proveedor_id}', ['as' => 'proveedordomicilio.tablaproveedordomicilio', 'uses' => 'catalogos\proveedordomicilioController@tablaproveedordomicilio']);

//==============================================

Route::resource('proveedordocumento', 'catalogos\proveedordocumentoController');

Route::get('tablaproveedordocumento/{proveedor_id}', ['as' => 'proveedordocumento.tablaproveedordocumento', 'uses' => 'catalogos\proveedordocumentoController@tablaproveedordocumento']);

Route::get('proveedorverdocumento/{documento_id}', ['as' => 'proveedorverdocumento', 'uses' => 'catalogos\proveedordocumentoController@mostrarpdf']);

//==============================================

Route::resource('proveedoracreditacion', 'catalogos\acreditacionController');

Route::get('tablaproveedoracreditacion/{proveedor_id}', ['as' => 'proveedoracreditacion.tablaproveedoracreditacion', 'uses' => 'catalogos\acreditacionController@tablaproveedoracreditacion']);

Route::get('veracreditaciondocumento/{acreditacion_id}/{tipo}', ['as' => 'veracreditaciondocumento', 'uses' => 'catalogos\acreditacionController@mostrarpdf']);

Route::get('proveedoracreditacionlista/{proveedor_id}/{acreditacion_id}', ['as' => 'proveedoracreditacion.proveedoracreditacionlista', 'uses' => 'catalogos\acreditacionController@proveedoracreditacionlista']);

Route::get('proveedorAprovacionlista/{proveedor_id}/{acreditacion_id}', ['as' => 'proveedoracreditacion.proveedorAprovacionlista', 'uses' => 'catalogos\acreditacionController@proveedorAprovacionlista']);


//==============================================

Route::resource('acreditacionalcances', 'catalogos\acreditacionalcanceController');

Route::get('tablaacreditacionalcances/{proveedor_id}/', ['as' => 'acreditacionalcances.tablaacreditacionalcances', 'uses' => 'catalogos\acreditacionalcanceController@tablaacreditacionalcances']);

Route::get('acreditacionalcancetipoagente/{agente_tipo}/{agente_seleccionado}', ['as' => 'acreditacionalcances.acreditacionalcancetipoagente', 'uses' => 'catalogos\acreditacionalcanceController@acreditacionalcancetipoagente']);

Route::get('obtenerDeterminantesBeis/{beis}/{determinanteSeleccionado}', ['as' => 'acreditacionalcances.obtenerDeterminantesBeis', 'uses' => 'catalogos\acreditacionalcanceController@obtenerDeterminantesBeis']);

Route::get('acreditacionalcanceagentenormas/{agente_id}', ['as' => 'acreditacionalcances.acreditacionalcanceagentenormas', 'uses' => 'catalogos\acreditacionalcanceController@acreditacionalcanceagentenormas']);

Route::get('proveedoralcanceservicioslista/{proveedor_id}/{alcanceservicio_id}', ['as' => 'acreditacionalcances.proveedoralcanceservicioslista', 'uses' => 'catalogos\acreditacionalcanceController@proveedoralcanceservicioslista']); 

//==============================================

Route::resource('proveedorequipo', 'catalogos\equipoController');

Route::get('tablaproveedorequipo/{proveedor_id}', ['as' => 'proveedorequipo.tablaproveedorequipo', 'uses' => 'catalogos\equipoController@tablaproveedorequipo']);


Route::get('verequipodocumento/{equipo_id}/{documento_tipo}', ['as' => 'verequipodocumento', 'uses' => 'catalogos\equipoController@mostrarpdf']);

Route::get('actualizacampoarea/{proveedor_id}/{acreditacion_id}', ['as' => 'proveedorequipo.actualizacampoarea', 'uses' => 'catalogos\equipoController@actualizacampoarea']);

Route::get('actualizacampoprueba/{proveedor_id}/{acreditacion_id}/{prueba_id}', ['as' => 'proveedorequipo.actualizacampoprueba', 'uses' => 'catalogos\equipoController@actualizacampoprueba']);

Route::get('verequipofoto/{id}', ['as' => 'verequipofoto', 'uses' => 'catalogos\equipoController@mostrarFotoEquipo']);

Route::get('tablaequipodocumento/{equipo_id}', ['as' => 'equipoController.tablaequipodocumento', 'uses' => 'catalogos\equipoController@tablaequipodocumento']);

Route::get('verequipodocumentopdf/{documento_id}', ['as' => 'verequipodocumentopdf', 'uses' => 'catalogos\equipoController@mostrarpdf']);

//==============================================


//vehiculos

Route::resource('proveedorvehiculo', 'catalogos\vehiculoController');

Route::get('tablaproveedorvehiculo/{proveedor_id}', ['as' => 'proveedorvehiculo.tablaproveedorvehiculo', 'uses' => 'catalogos\vehiculoController@tablaproveedorvehiculo']);

Route::get('vervehiculofoto/{id}', ['as' => 'vervehiculofoto', 'uses' => 'catalogos\vehiculoController@mostrarFotoVehiculo']);

Route::get('tablavehiculodocumento/{vehiculo_id}', ['as' => 'vehiculoController.tablavehiculodocumento', 'uses' => 'catalogos\vehiculoController@tablavehiculodocumento']);

Route::get('vervehiculodocumentopdf/{documento_id}', ['as' => 'vervehiculodocumentopdf', 'uses' => 'catalogos\vehiculoController@mostrarpdf']);


//==============================================


Route::resource('proveedorsignatario', 'catalogos\signatarioController');

Route::get('tablaproveedorsignatario/{proveedor_id}', ['as' => 'proveedorsignatario.tablaproveedorsignatario', 'uses' => 'catalogos\signatarioController@tablaproveedorsignatario']);

Route::get('versignatariofoto/{signatario_id}', ['as' => 'versignatariofoto', 'uses' => 'catalogos\signatarioController@mostrarfoto']);

//==============================================

Route::resource('signatariodocumento', 'catalogos\signatariodocumentoController');

Route::get('tablasignatariodocumento/{signatario_id}', ['as' => 'signatariodocumento.tablasignatariodocumento', 'uses' => 'catalogos\signatariodocumentoController@tablasignatariodocumento']);

Route::get('versignatariodocumentopdf/{documento_id}', ['as' => 'versignatariodocumentopdf', 'uses' => 'catalogos\signatariodocumentoController@mostrarpdf']);

Route::get('tablasignatarioexperiencia/{signatario_id}/{experiencia}', ['as' => 'signatariodocumento.tablasignatarioexperiencia', 'uses' => 'catalogos\signatariodocumentoController@tablasignatarioexperiencia']);

Route::get('versignatariodocumentoexperienciapdf/{documento_id}', ['as' => 'versignatariodocumentoexperienciapdf', 'uses' => 'catalogos\signatariodocumentoController@mostrarpdfExperiencia']);


//==============================================

Route::resource('signatariocurso', 'catalogos\signatariocursoController');

Route::get('tablasignatariocurso/{signatario_id}', ['as' => 'signatariocurso.tablasignatariocurso', 'uses' => 'catalogos\signatariocursoController@tablasignatariocurso']);

Route::get('tablasignatariocursovalidacion/{curso_id}/{signatario_id}', ['as' => 'signatariocurso.tablasignatariocursovalidacion', 'uses' => 'catalogos\signatariocursoController@tablasignatariocursovalidacion']);


Route::get('versignatariocursopdf/{curso_id}', ['as' => 'versignatariocursopdf', 'uses' => 'catalogos\signatariocursoController@mostrarpdf']);

Route::get('versignatariocursopdfvalidacion/{documento_curso_id}', ['as' => 'versignatariocursopdfvalidacion', 'uses' => 'catalogos\signatariocursoController@mostrarpdfvalidacion']);


//==============================================

Route::resource('signatarioacreditacion', 'catalogos\signatarioacreditacionController');

Route::get('tablasignatarioacreditacion/{signatario_id}', ['as' => 'signatarioacreditacion.tablasignatarioacreditacion', 'uses' => 'catalogos\signatarioacreditacionController@tablasignatarioacreditacion']);

Route::get('signatarioacreditacioneliminar/{signatarioacreditacion_id}', ['as' => 'signatarioacreditacion.signatarioacreditacioneliminar', 'uses' => 'catalogos\signatarioacreditacionController@signatarioacreditacioneliminar']);

//==============================================

Route::resource('proveedorservicio', 'catalogos\servicioController');

Route::get('tablaproveedorservicio/{proveedor_id}', ['as' => 'proveedorservicio.tablaproveedorservicio', 'uses' => 'catalogos\servicioController@tablaproveedorservicio']);

Route::get('serviciolistaalcances/{proveedor_id}', ['as' => 'proveedorservicio.serviciolistaalcances', 'uses' => 'catalogos\servicioController@serviciolistaalcances']);

Route::get('serviciopartidasprecios/{cotizacion_id}', ['as' => 'proveedorservicio.serviciopartidasprecios', 'uses' => 'catalogos\servicioController@serviciopartidasprecios']);

Route::get('verserviciopdf/{servicio_id}', ['as' => 'verserviciopdf', 'uses' => 'catalogos\servicioController@mostrarpdf']);

//==============================================

Route::resource('proveedorcatalogos', 'catalogos\proveedorcatalogosController');

Route::get('consultacatalogo/{num_catalogo}', ['as' => 'proveedorcatalogos.consultacatalogo', 'uses' => 'catalogos\proveedorcatalogosController@consultacatalogo']);

Route::get('proveedordesactivacatalogo/{catalogo}/{registro}/{estado}', ['as' => 'proveedorcatalogos.proveedordesactivacatalogo', 'uses' => 'catalogos\proveedorcatalogosController@proveedordesactivacatalogo']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////RECONOCIMIENTO SENSORIAL////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('recsensorial', 'recsensorial\recsensorialController');

Route::get('/estructura/{FOLIO}', ['as' => 'recsensorial.estructuraproyectos', 'uses' => 'recsensorial\recsensorialController@estructuraproyectos']);

Route::get('/folioproyecto/{proyecto_folio}', ['as' => 'recsensorial.folioproyecto', 'uses' => 'recsensorial\recsensorialController@folioproyecto']);



Route::get('tablarecsensorial', ['as' => 'recsensorial.tablarecsensorial', 'uses' => 'recsensorial\recsensorialController@tablarecsensorial']);

Route::get('pruebasrecsensorial/{ID}', ['as' => 'recsensorial.pruebasrecsensorial', 'uses' => 'recsensorial\recsensorialController@pruebasrecsensorial']);

Route::get('validacionAsignacionUser/{folio}', ['as' => 'recsensorial.validacionAsignacionUser', 'uses' => 'recsensorial\recsensorialController@validacionAsignacionUser']);

Route::get('sustanciasrecsensorial/{ID}', ['as' => 'recsensorial.sustanciasrecsensorial', 'uses' => 'recsensorial\recsensorialController@sustanciasrecsensorial']);

Route::get('informePortada/{id}', ['as' => 'informePortada', 'uses' => 'recsensorial\recsensorialController@informePortada']);

Route::get('TablaControlCambios/{id}', ['as' => 'TablaControlCambios', 'uses' => 'recsensorial\recsensorialController@TablaControlCambios']);

Route::get('verificarBloqueado/{ID}', ['as' => 'verificarBloqueado', 'uses' => 'recsensorial\recsensorialController@verificarBloqueado']);

Route::get('verificarRevision/{ID}', ['as' => 'verificarRevision', 'uses' => 'recsensorial\recsensorialController@verificarRevision']);

Route::post('actualizarEstadoCancelado', ['as' => 'actualizarEstadoCancelado', 'uses' => 'recsensorial\recsensorialController@actualizarEstadoCancelado']);

Route::get('verZIP/{opcion}/{id}', ['as' => 'verZIP', 'uses' => 'recsensorial\recsensorialController@verZIP']);







Route::get('mostrarmapa/{archivo_opcion}/{recsensorial_id}', ['as' => 'mostrarmapa', 'uses' => 'recsensorial\recsensorialController@mostrarmapa']);

Route::get('mostrarplano/{archivo_opcion}/{recsensorial_id}', ['as' => 'mostrarplano', 'uses' => 'recsensorial\recsensorialController@mostrarplano']);

Route::get('mostrarMapaFuentesGeneradoras/{recsensorial_id}', ['as' => 'mostrarMapaFuentesGeneradoras', 'uses' => 'recsensorial\recsensorialController@mostrarMapaFuentesGeneradoras']);


Route::get('mostrarfotoinstalacion/{archivo_opcion}/{recsensorial_id}', ['as' => 'mostrarfotoinstalacion', 'uses' => 'recsensorial\recsensorialController@mostrarfotoinstalacion']);

Route::get('pdfvalidaquimicos/{recsensorial_id}', ['as' => 'pdfvalidaquimicos', 'uses' => 'recsensorial\recsensorialController@pdfvalidaquimicos']);

Route::get('recsensorialpdfautorizado/{recsensorial_id}/{recsensorial_tipo}', ['as' => 'recsensorialpdfautorizado', 'uses' => 'recsensorial\recsensorialController@recsensorialpdfautorizado']);

Route::get('recsensorialbloqueo/{recsensorial_id}/{recsensorial_estado}', ['as' => 'recsensorial.recsensorialbloqueo', 'uses' => 'recsensorial\recsensorialController@recsensorialbloqueo']);

Route::get('recsensorialevidenciagaleria/{recsensorial_id}/{parametro_id}', ['as' => 'recsensorial.recsensorialevidenciagaleria', 'uses' => 'recsensorial\recsensorialController@recsensorialevidenciagaleria']);

Route::get('recsensorialevidenciafotomostrar/{foto_id}/{accion}', ['as' => 'recsensorial.recsensorialevidenciafotomostrar', 'uses' => 'recsensorial\recsensorialController@recsensorialevidenciafotomostrar']);

Route::get('recsensorialevidenciafotoeliminar/{foto_id}', ['as' => 'recsensorial.recsensorialevidenciafotoeliminar', 'uses' => 'recsensorial\recsensorialController@recsensorialevidenciafotoeliminar']);

Route::get('obtenerDatosInformes/{ID}', ['as' => 'recsensorial.obtenerDatosInformes', 'uses' => 'recsensorial\recsensorialController@obtenerDatosInformes']);

Route::get('obtenerTablaInforme/{ID}', ['as' => 'recsensorial.obtenerTablaInforme', 'uses' => 'recsensorial\recsensorialController@obtenerTablaInforme']);

Route::get('consultarRecomendaciones/{ID}', ['as' => 'recsensorial.consultarRecomendaciones', 'uses' => 'recsensorial\recsensorialController@consultarRecomendaciones']);

Route::get('obtenerGruposComponetes/{ID}', ['as' => 'recsensorial.obtenerGruposComponetes', 'uses' => 'recsensorial\recsensorialController@obtenerGruposComponetes']);


Route::get('obtenerComponentes/{ID}', ['as' => 'recsensorial.obtenerComponentes', 'uses' => 'recsensorial\recsensorialController@obtenerComponentes']);


Route::get('obtenerCategorias/{ID}/{AREA_ID}', ['as' => 'recsensorial.obtenerCategorias', 'uses' => 'recsensorial\recsensorialController@obtenerCategorias']);

Route::get('consultarPPTyCT/{ID}', ['as' => 'recsensorial.consultarPPTyCT', 'uses' => 'recsensorial\recsensorialController@consultarPPTyCT']);


Route::get('obtenerTablaClienteInforme/{ID}', ['as' => 'recsensorial.obtenerTablaClienteInforme', 'uses' => 'recsensorial\recsensorialController@obtenerTablaClienteInforme']);


Route::get('obtenerTablaClienteProporcionado/{ID}', ['as' => 'recsensorial.obtenerTablaClienteProporcionado', 'uses' => 'recsensorial\recsensorialController@obtenerTablaClienteProporcionado']);


Route::get('getContratosClientes/{cliente_id}/{id_contrato}', ['as' => 'recsensorial.getContratosClientes', 'uses' => 'recsensorial\recsensorialController@getContratosClientes']);

Route::get('getContratosAnexos/{id_contrato}', ['as' => 'recsensorial.getContratosAnexos', 'uses' => 'recsensorial\recsensorialController@getContratosAnexos']);

Route::get('autorizarReconocimiento/{id_reconocimiento}', ['as' => 'recsensorial.autorizarReconocimiento', 'uses' => 'recsensorial\recsensorialController@autorizarReconocimiento']);

Route::get('mostrarFotoAnexo/{id}', ['as' => 'recsensorial.mostrarFotoAnexo', 'uses' => 'recsensorial\recsensorialanexoController@mostrarFotoAnexo']);


//==============================================

Route::resource('recsensorialarea', 'recsensorial\recsensorialareaController');

Route::get('recsensorialareatabla/{recsensorial_id}', ['as' => 'recsensorialarea.recsensorialareatabla', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareatabla']);

Route::get('recsensorialareaparametros/{recsensorial_id}/{area_id}', ['as' => 'recsensorialarea.recsensorialareaparametros', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareaparametros']);

Route::get('recsensorialareacategorias/{recsensorial_id}', ['as' => 'recsensorialarea.recsensorialareacategorias', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareacategorias']);

Route::get('recsensorialareacategoriaselegidas/{area_id}', ['as' => 'recsensorialarea.recsensorialareacategoriaselegidas', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareacategoriaselegidas']);

Route::get('recsensorialconsultaareas/{recsensorial_id}/{id_seleccionado}/{quimicas}', ['as' => 'recsensorialarea.recsensorialconsultaareas', 'uses' => 'recsensorial\recsensorialareaController@recsensorialconsultaareas']);

Route::get('recsensorialareaeliminar/{area_id}', ['as' => 'recsensorialarea.recsensorialareaeliminar', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareaeliminar']);

Route::get('recsensorialpoeword/{recsensorial_id}', ['as' => 'recsensorial.recsensorialpoeword', 'uses' => 'recsensorial\recsensorialareaController@recsensorialpoeword']);

//==============================================

Route::resource('recsensorialcategoria', 'recsensorial\recsensorialcategoriaController');

Route::get('recsensorialcategoriatabla/{recsensorial_id}', ['as' => 'recsensorialcategoria.recsensorialcategoriatabla', 'uses' => 'recsensorial\recsensorialcategoriaController@recsensorialcategoriatabla']);

Route::get('recsensorialcategoriaeliminar/{categoria_id}', ['as' => 'recsensorialcategoria.recsensorialcategoriaeliminar', 'uses' => 'recsensorial\recsensorialcategoriaController@recsensorialcategoriaeliminar']);

//==============================================

Route::resource('recsensorialmaquinaria', 'recsensorial\recsensorialmaquinariaController');

Route::get('recsensorialmaquinariatabla/{recsensorial_id}', ['as' => 'recsensorialmaquinaria.recsensorialmaquinariatabla', 'uses' => 'recsensorial\recsensorialmaquinariaController@recsensorialmaquinariatabla']);

Route::get('validarComponentesMaquinaria/{id}', ['as' => 'recsensorialmaquinaria.validarComponentesMaquinaria', 'uses' => 'recsensorial\recsensorialmaquinariaController@validarComponentesMaquinaria']);

Route::get('recsensorialmaquinariaeliminar/{maquina_id}', ['as' => 'recsensorialmaquinaria.recsensorialmaquinariaeliminar', 'uses' => 'recsensorial\recsensorialmaquinariaController@recsensorialmaquinariaeliminar']);


Route::get('recsensorialmaquinariaAreasAfectan/{FUENTE_GENERADORA_ID}', ['as' => 'recsensorialmaquinaria.recsensorialmaquinariaAreasAfectan', 'uses' => 'recsensorial\recsensorialmaquinariaController@recsensorialmaquinariaAreasAfectan']);

//==============================================

Route::resource('recsensorialequipopp', 'recsensorial\recsensorialequipoppController');

Route::get('recsensorialequipopptabla/{recsensorial_id}', ['as' => 'recsensorialequipopp.recsensorialequipopptabla', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialequipopptabla']);

Route::get('recsensorialeppcatalogoruido', ['as' => 'recsensorialequipopp.recsensorialeppcatalogoruido', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialeppcatalogoruido']);

Route::get('recsensorialClaveEppruido/{PARTECUERPO_ID}', ['as' => 'recsensorialequipopp.recsensorialClaveEppruido', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialClaveEppruido']);

Route::get('recsensorialeppcatalogo', ['as' => 'recsensorialequipopp.recsensorialeppcatalogo', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialeppcatalogo']);

Route::get('recsensorialeppcategorias/{recsensorial_id}/{seleccionado_id}', ['as' => 'recsensorialequipopp.recsensorialeppcategorias', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialeppcategorias']);

Route::get('recsensorialClaveEpp/{PARTECUERPO_ID}', ['as' => 'recsensorialequipopp.recsensorialClaveEpp', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialClaveEpp']);

Route::get('recsensorialeppeditar/{recsensorial_id}/{categoria_id}', ['as' => 'recsensorialequipopp.recsensorialeppeditar', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialeppeditar']);

Route::get('recsensorialequipoppeliminar/{recsensorial_id}/{categoria_id}', ['as' => 'recsensorialequipopp.recsensorialequipoppeliminar', 'uses' => 'recsensorial\recsensorialequipoppController@recsensorialequipoppeliminar']);

//==============================================

Route::get('recsensorialresponsablefoto/{tipo_documento}/{recsensorial_id}', ['as' => 'recsensorialresponsablefoto', 'uses' => 'recsensorial\recsensorialController@recsensorialresponsablefoto']);

//==============================================

Route::resource('recsensorialanexo', 'recsensorial\recsensorialanexoController');

Route::get('recsensorialanexolista/{proveedor_id}', ['as' => 'recsensorialanexo.recsensorialanexolista', 'uses' => 'recsensorial\recsensorialanexoController@recsensorialanexolista']);

Route::get('recsensorialanexotabla/{recsensorial_id}/{tipo}', ['as' => 'recsensorialanexo.recsensorialanexotabla', 'uses' => 'recsensorial\recsensorialanexoController@recsensorialanexotabla']);

Route::get('recsensorialanexoeliminar/{recsensorialanexo_id}/{contrato_anexo_id}', ['as' => 'recsensorialanexo.recsensorialanexoeliminar', 'uses' => 'recsensorial\recsensorialanexoController@recsensorialanexoeliminar']);

//==============================================

Route::resource('recsensorialcatalogos', 'recsensorial\recsensorialcatalogosController');

Route::get('recsensorialconsultacatalogo/{num_catalogo}', ['as' => 'recsensorialcatalogos.recsensorialconsultacatalogo', 'uses' => 'recsensorial\recsensorialcatalogosController@recsensorialconsultacatalogo']);

Route::get('opcionesOrganizacion/{id_etiqueta}', ['as' => 'recsensorialcatalogos.opcionesOrganizacion', 'uses' => 'recsensorial\recsensorialcatalogosController@opcionesOrganizacion']);

Route::get('recsensorialcatalogodesactiva/{catalogo}/{registro}/{estado}', ['as' => 'recsensorialcatalogos.recsensorialcatalogodesactiva', 'uses' => 'recsensorial\recsensorialcatalogosController@recsensorialcatalogodesactiva']);

Route::get('verFormatoCampo/{opcion}/{id}', ['as' => 'verFormatoCampo', 'uses' => 'recsensorial\recsensorialcatalogosController@verFormatoCampo']);

Route::get('verFichaTecnica/{opcion}/{id}', ['as' => 'verFichaTecnica', 'uses' => 'recsensorial\recsensorialcatalogosController@verFichaTecnica']);

Route::get('verProteccionFoto/{id}', ['as' => 'verProteccionFoto', 'uses' => 'recsensorial\recsensorialcatalogosController@verProteccionFoto']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////PARAMETROS////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//RUTAS PARA LLENAR LOS CAMPOS AREAS Y CATEGORIAS DE TODOS LOS PARAMETROS

Route::get('recsensorialconsultaselectareas/{recsensorial_id}/{seleccionado_id}', ['as' => 'recsensorial.recsensorialconsultaselectareas', 'uses' => 'recsensorial\recsensorialController@recsensorialconsultaselectareas']);

Route::get('recsensorialconsultaselectcategorias/{recsensorial_id}/{seleccionado_id}', ['as' => 'recsensorial.recsensorialconsultaselectcategorias', 'uses' => 'recsensorial\recsensorialController@recsensorialconsultaselectcategorias']);

Route::get('recsensorialselectcategoriasxarea/{recsensorialarea_id}/{recsensorialcategoria_id}', ['as' => 'recsensorial.recsensorialselectcategoriasxarea', 'uses' => 'recsensorial\recsensorialController@recsensorialselectcategoriasxarea']);


//==============================================


Route::resource('parametroruido', 'recsensorial\parametroruidoController');

Route::get('parametroruidovista/{recsensorial_id}', ['as' => 'parametroruido.parametroruidovista', 'uses' => 'recsensorial\parametroruidoController@parametroruidovista']);

Route::get('parametroruidosonometriatabla/{recsensorial_id}', ['as' => 'parametroruido.parametroruidosonometriatabla', 'uses' => 'recsensorial\parametroruidoController@parametroruidosonometriatabla']);

Route::get('parametroruidosonometriaeliminar/{recsensorial_id}', ['as' => 'parametroruido.parametroruidosonometriaeliminar', 'uses' => 'recsensorial\parametroruidoController@parametroruidosonometriaeliminar']);

Route::get('recsensoriallistacategoriasxarea/{recsensorialarea_id}', ['as' => 'recsensorial.recsensoriallistacategoriasxarea', 'uses' => 'recsensorial\parametroruidoController@recsensoriallistacategoriasxarea']);

Route::get('parametroruidodosimetriatabla/{recsensorial_id}', ['as' => 'parametroruido.parametroruidodosimetriatabla', 'uses' => 'recsensorial\parametroruidoController@parametroruidodosimetriatabla']);

Route::get('parametroruidodosimetriaeliminar/{recsensorial_id}', ['as' => 'parametroruido.parametroruidodosimetriaeliminar', 'uses' => 'recsensorial\parametroruidoController@parametroruidodosimetriaeliminar']);

Route::get('parametroruidoequipotabla/{recsensorial_id}', ['as' => 'parametroruido.parametroruidoequipotabla', 'uses' => 'recsensorial\parametroruidoController@parametroruidoequipotabla']);

Route::get('parametroruidoequipoeliminar/{equipo_id}', ['as' => 'parametroruido.parametroruidoequipoeliminar', 'uses' => 'recsensorial\parametroruidoController@parametroruidoequipoeliminar']);


//==============================================


Route::resource('parametrovibracion', 'recsensorial\parametrovibracionController');

Route::get('parametrovibracionvista/{recsensorial_id}', ['as' => 'parametrovibracion.parametrovibracionvista', 'uses' => 'recsensorial\parametrovibracionController@parametrovibracionvista']);

Route::get('parametrovibraciontabla/{recsensorial_id}', ['as' => 'parametrovibracion.parametrovibraciontabla', 'uses' => 'recsensorial\parametrovibracionController@parametrovibraciontabla']);

Route::get('parametrovibracioneliminar/{recsensorial_id}', ['as' => 'parametrovibracion.parametrovibracioneliminar', 'uses' => 'recsensorial\parametrovibracionController@parametrovibracioneliminar']);


//==============================================


Route::resource('parametrotemperatura', 'recsensorial\parametrotemperaturaController');

Route::get('parametrotemperaturavista/{recsensorial_id}', ['as' => 'parametrotemperatura.parametrotemperaturavista', 'uses' => 'recsensorial\parametrotemperaturaController@parametrotemperaturavista']);

Route::get('parametrotemperaturatabla/{recsensorial_id}', ['as' => 'parametrotemperatura.parametrotemperaturatabla', 'uses' => 'recsensorial\parametrotemperaturaController@parametrotemperaturatabla']);

Route::get('parametrotemperaturaeliminar/{recsensorial_id}', ['as' => 'parametrotemperatura.parametrotemperaturaeliminar', 'uses' => 'recsensorial\parametrotemperaturaController@parametrotemperaturaeliminar']);


//==============================================

Route::resource('parametroiluminacion', 'recsensorial\parametroiluminacionController');

Route::get('parametroiluminacionvista/{recsensorial_id}', ['as' => 'parametroiluminacion.parametroiluminacionvista', 'uses' => 'recsensorial\parametroiluminacionController@parametroiluminacionvista']);

Route::get('parametroiluminaciontabla/{recsensorial_id}', ['as' => 'parametroiluminacion.parametroiluminaciontabla', 'uses' => 'recsensorial\parametroiluminacionController@parametroiluminaciontabla']);

Route::get('parametroiluminacioneliminar/{recsensorial_id}', ['as' => 'parametroiluminacion.parametroiluminacioneliminar', 'uses' => 'recsensorial\parametroiluminacionController@parametroiluminacioneliminar']);

Route::get('iluminacionlistacategoriasxarea/{recsensorialarea_id}', ['as' => 'recsensorial.iluminacionlistacategoriasxarea', 'uses' => 'recsensorial\parametroiluminacionController@iluminacionlistacategoriasxarea']);


//==============================================


Route::resource('parametroradiacionionizante', 'recsensorial\parametroradiacionionizanteController');

Route::get('parametroradiacionionizantevista/{recsensorial_id}', ['as' => 'parametroradiacionionizante.parametroradiacionionizantevista', 'uses' => 'recsensorial\parametroradiacionionizanteController@parametroradiacionionizantevista']);

Route::get('parametroradiacionionizantetabla/{recsensorial_id}', ['as' => 'parametroradiacionionizante.parametroradiacionionizantetabla', 'uses' => 'recsensorial\parametroradiacionionizanteController@parametroradiacionionizantetabla']);

Route::get('parametroradiacionionizanteeliminar/{recsensorial_id}', ['as' => 'parametroradiacionionizante.parametroradiacionionizanteeliminar', 'uses' => 'recsensorial\parametroradiacionionizanteController@parametroradiacionionizanteeliminar']);


//==============================================


Route::resource('parametroradiacionnoionizante', 'recsensorial\parametroradiacionnoionizanteController');

Route::get('parametroradiacionnoionizantevista/{recsensorial_id}', ['as' => 'parametroradiacionnoionizante.parametroradiacionnoionizantevista', 'uses' => 'recsensorial\parametroradiacionnoionizanteController@parametroradiacionnoionizantevista']);

Route::get('parametroradiacionnoionizantetabla/{recsensorial_id}', ['as' => 'parametroradiacionnoionizante.parametroradiacionnoionizantetabla', 'uses' => 'recsensorial\parametroradiacionnoionizanteController@parametroradiacionnoionizantetabla']);

Route::get('parametroradiacionnoionizanteeliminar/{recsensorial_id}', ['as' => 'parametroradiacionnoionizante.parametroradiacionnoionizanteeliminar', 'uses' => 'recsensorial\parametroradiacionnoionizanteController@parametroradiacionnoionizanteeliminar']);


//==============================================


Route::resource('parametroprecionesambientales', 'recsensorial\parametroprecionesambientalesController');

Route::get('parametroprecionesambientalesvista/{recsensorial_id}', ['as' => 'parametroprecionesambientales.parametroprecionesambientalesvista', 'uses' => 'recsensorial\parametroprecionesambientalesController@parametroprecionesambientalesvista']);

Route::get('parametroprecionesambientalestabla/{recsensorial_id}', ['as' => 'parametroprecionesambientales.parametroprecionesambientalestabla', 'uses' => 'recsensorial\parametroprecionesambientalesController@parametroprecionesambientalestabla']);

Route::get('parametroprecionesambientaleseliminar/{recsensorial_id}', ['as' => 'parametroprecionesambientales.parametroprecionesambientaleseliminar', 'uses' => 'recsensorial\parametroprecionesambientalesController@parametroprecionesambientaleseliminar']);


//==============================================


Route::resource('parametrocalidadaire', 'recsensorial\parametrocalidadaireController');

Route::get('parametrocalidadairevista/{recsensorial_id}', ['as' => 'parametrocalidadaire.parametrocalidadairevista', 'uses' => 'recsensorial\parametrocalidadaireController@parametrocalidadairevista']);

Route::get('parametrocalidadairetabla/{recsensorial_id}', ['as' => 'parametrocalidadaire.parametrocalidadairetabla', 'uses' => 'recsensorial\parametrocalidadaireController@parametrocalidadairetabla']);

Route::get('parametrocalidadaireeliminar/{recsensorial_id}', ['as' => 'parametrocalidadaire.parametrocalidadaireeliminar', 'uses' => 'recsensorial\parametrocalidadaireController@parametrocalidadaireeliminar']);


//==============================================


Route::resource('parametroagua', 'recsensorial\parametroaguaController');

Route::get('parametroaguavista/{recsensorial_id}', ['as' => 'parametroagua.parametroaguavista', 'uses' => 'recsensorial\parametroaguaController@parametroaguavista']);

Route::get('parametroaguatabla/{recsensorial_id}', ['as' => 'parametroagua.parametroaguatabla', 'uses' => 'recsensorial\parametroaguaController@parametroaguatabla']);

Route::get('parametroaguaeliminar/{recsensorial_id}', ['as' => 'parametroagua.parametroaguaeliminar', 'uses' => 'recsensorial\parametroaguaController@parametroaguaeliminar']);

Route::get('parametroaguaselectcaracteristicas/{seleccionado_id}', ['as' => 'parametroagua.parametroaguaselectcaracteristicas', 'uses' => 'recsensorial\parametroaguaController@parametroaguaselectcaracteristicas']);

Route::get('parametroaguacaracteristicas/{caracteristica_tipo}', ['as' => 'parametroagua.parametroaguacaracteristicas', 'uses' => 'recsensorial\parametroaguaController@parametroaguacaracteristicas']);


//==============================================


Route::resource('parametrohielo', 'recsensorial\parametrohieloController');

Route::get('parametrohielovista/{recsensorial_id}', ['as' => 'parametrohielo.parametrohielovista', 'uses' => 'recsensorial\parametrohieloController@parametrohielovista']);

Route::get('parametrohielotabla/{recsensorial_id}', ['as' => 'parametrohielo.parametrohielotabla', 'uses' => 'recsensorial\parametrohieloController@parametrohielotabla']);

Route::get('parametrohieloeliminar/{recsensorial_id}', ['as' => 'parametrohielo.parametrohieloeliminar', 'uses' => 'recsensorial\parametrohieloController@parametrohieloeliminar']);

Route::get('parametrohieloselectcaracteristicas/{seleccionado_id}', ['as' => 'parametrohielo.parametrohieloselectcaracteristicas', 'uses' => 'recsensorial\parametrohieloController@parametrohieloselectcaracteristicas']);

Route::get('parametrohielocaracteristicas/{caracteristica_tipo}', ['as' => 'parametrohielo.parametrohielocaracteristicas', 'uses' => 'recsensorial\parametrohieloController@parametrohielocaracteristicas']);


//==============================================


Route::resource('parametroalimento', 'recsensorial\parametroalimentoController');

Route::get('parametroalimentovista/{recsensorial_id}', ['as' => 'parametroalimento.parametroalimentovista', 'uses' => 'recsensorial\parametroalimentoController@parametroalimentovista']);

Route::get('parametroalimentotabla/{recsensorial_id}', ['as' => 'parametroalimento.parametroalimentotabla', 'uses' => 'recsensorial\parametroalimentoController@parametroalimentotabla']);

Route::get('parametroalimentoeliminar/{recsensorial_id}', ['as' => 'parametroalimento.parametroalimentoeliminar', 'uses' => 'recsensorial\parametroalimentoController@parametroalimentoeliminar']);

Route::get('parametroalimentocaracteristicas/{seleccionado_id}', ['as' => 'parametroalimento.parametroalimentocaracteristicas', 'uses' => 'recsensorial\parametroalimentoController@parametroalimentocaracteristicas']);


//==============================================


Route::resource('parametrosuperficie', 'recsensorial\parametrosuperficieController');

Route::get('parametrosuperficievista/{recsensorial_id}', ['as' => 'parametrosuperficie.parametrosuperficievista', 'uses' => 'recsensorial\parametrosuperficieController@parametrosuperficievista']);

Route::get('parametrosuperficietabla/{recsensorial_id}', ['as' => 'parametrosuperficie.parametrosuperficietabla', 'uses' => 'recsensorial\parametrosuperficieController@parametrosuperficietabla']);

Route::get('parametrosuperficieeliminar/{recsensorial_id}', ['as' => 'parametrosuperficie.parametrosuperficieeliminar', 'uses' => 'recsensorial\parametrosuperficieController@parametrosuperficieeliminar']);

Route::get('parametrosuperficiecaracteristicas/{seleccionado_id}', ['as' => 'parametrosuperficie.parametrosuperficiecaracteristicas', 'uses' => 'recsensorial\parametrosuperficieController@parametrosuperficiecaracteristicas']);


//==============================================


Route::resource('parametroergonomia', 'recsensorial\parametroergonomiaController');

Route::get('parametroergonomiavista/{recsensorial_id}', ['as' => 'parametroergonomia.parametroergonomiavista', 'uses' => 'recsensorial\parametroergonomiaController@parametroergonomiavista']);

Route::get('parametroergonomiatabla/{recsensorial_id}', ['as' => 'parametroergonomia.parametroergonomiatabla', 'uses' => 'recsensorial\parametroergonomiaController@parametroergonomiatabla']);

Route::get('ergonomiaareas/{recsensorial_id}/{parametroergonomia_id}', ['as' => 'parametroergonomia.ergonomiaareas', 'uses' => 'recsensorial\parametroergonomiaController@ergonomiaareas']);

Route::get('parametroergonomiaeliminar/{recsensorial_id}', ['as' => 'parametroergonomia.parametroergonomiaeliminar', 'uses' => 'recsensorial\parametroergonomiaController@parametroergonomiaeliminar']);


//==============================================


Route::resource('parametropsicosocial', 'recsensorial\parametropsicosocialController');

Route::get('parametropsicosocialvista/{recsensorial_id}', ['as' => 'parametropsicosocial.parametropsicosocialvista', 'uses' => 'recsensorial\parametropsicosocialController@parametropsicosocialvista']);

Route::get('parametropsicosocialtabla/{recsensorial_id}', ['as' => 'parametropsicosocial.parametropsicosocialtabla', 'uses' => 'recsensorial\parametropsicosocialController@parametropsicosocialtabla']);

Route::get('parametropsicosocialeliminar/{recsensorial_id}', ['as' => 'parametropsicosocial.parametropsicosocialeliminar', 'uses' => 'recsensorial\parametropsicosocialController@parametropsicosocialeliminar']);

Route::get('recsensorialConsultarPuntos/{recsensorial_id}', ['as' => 'parametropsicosocial.recsensorialConsultarPuntos', 'uses' => 'recsensorial\parametropsicosocialController@recsensorialConsultarPuntos']);


//==============================================


Route::resource('parametroserviciopersonal', 'recsensorial\parametroserviciopersonalController');

Route::get('parametroserviciopersonalvista/{recsensorial_id}', ['as' => 'parametroserviciopersonal.parametroserviciopersonalvista', 'uses' => 'recsensorial\parametroserviciopersonalController@parametroserviciopersonalvista']);

Route::get('parametroserviciopersonaltabla/{recsensorial_id}', ['as' => 'parametroserviciopersonal.parametroserviciopersonaltabla', 'uses' => 'recsensorial\parametroserviciopersonalController@parametroserviciopersonaltabla']);

Route::get('parametroserviciopersonaleliminar/{recsensorial_id}', ['as' => 'parametroserviciopersonal.parametroserviciopersonaleliminar', 'uses' => 'recsensorial\parametroserviciopersonalController@parametroserviciopersonaleliminar']);


//==============================================


Route::resource('parametromapariesgo', 'recsensorial\parametromapariesgoController');

Route::get('parametromapariesgovista/{recsensorial_id}', ['as' => 'parametromapariesgo.parametromapariesgovista', 'uses' => 'recsensorial\parametromapariesgoController@parametromapariesgovista']);

Route::get('parametromapariesgotabla/{recsensorial_id}', ['as' => 'parametromapariesgo.parametromapariesgotabla', 'uses' => 'recsensorial\parametromapariesgoController@parametromapariesgotabla']);

Route::get('parametromapariesgoeliminar/{recsensorial_id}', ['as' => 'parametromapariesgo.parametromapariesgoeliminar', 'uses' => 'recsensorial\parametromapariesgoController@parametromapariesgoeliminar']);


//==============================================


Route::resource('recsensorialagentescliente', 'recsensorial\recsensorialagentesclienteController');

Route::get('recsensorialagentesclientetabla/{recsensorial_id}', ['as' => 'recsensorialagentescliente.recsensorialagentesclientetabla', 'uses' => 'recsensorial\recsensorialagentesclienteController@recsensorialagentesclientetabla']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////QUIMICOS//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('recsensorialquimicosinventario', 'recsensorialquimicos\recsensorialquimicosinventarioController');

Route::get('recsensorialquimicosinventariotabla/{recsensorial_id}', ['as' => 'recsensorialquimicosinventario.recsensorialquimicosinventariotabla', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@recsensorialquimicosinventariotabla']);

Route::get('recsensorialquimicosinventarioeliminar/{recsensorial_id}/{recsensorialarea_id}/{recsensorialcategoria_id}', ['as' => 'recsensorialquimicosinventario.recsensorialquimicosinventarioeliminar', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@recsensorialquimicosinventarioeliminar']);

Route::get('recsensorialselectcategoriasxareaquimicos/{recsensorialarea_id}', ['as' => 'recsensorialquimicosinventario.recsensorialselectcategoriasxareaquimicos', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@recsensorialselectcategoriasxareaquimicos']);

Route::get('obtenerCategoriasReconomiento/{recsensorialarea_id}/{clasificacion}/{sustancia}', ['as' => 'recsensorialquimicosinventario.obtenerCategoriasReconomiento', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@obtenerCategoriasReconomiento']);

Route::get('recsensorialquimicoscatsustancias/{recsensorialarea_id}/{recsensorialcategoria_id}', ['as' => 'recsensorialquimicosinventario.recsensorialquimicoscatsustancias', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@recsensorialquimicoscatsustancias']);

Route::get('recsensorialquimicosresumen/{recsensorial_id}/{numero_tabla}', ['as' => 'recsensorialquimicosinventario.recsensorialquimicosresumen', 'uses' => 'recsensorialquimicos\recsensorialquimicosinventarioController@recsensorialquimicosresumen']);



//==============================================  CATALOGOS  ============================================== 


Route::resource('recsensorialquimicoscatalogos', 'recsensorialquimicos\recsensorialquimicoscatalogosController');

Route::get('quimicoscatestadofisico', ['as' => 'recsensorialquimicoscatalogosController.quimicoscatestadofisico', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@quimicoscatestadofisico']);

Route::get('sustanciasHojasSeguridad/{ID}', ['as' => 'recsensorialquimicoscatalogosController.sustanciasHojasSeguridad', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@sustanciasHojasSeguridad']);


Route::get('recsensorialquimicoscatalogopdf/{catsustancia_id}', ['as' => 'recsensorialquimicoscatalogos.recsensorialquimicoscatalogopdf', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@recsensorialquimicoscatalogopdf']);

Route::get('recsensorialquimicoscataloestado/{num_catalogo}/{registro_id}/{estado_checkbox}', ['as' => 'recsensorialquimicoscatalogos.recsensorialquimicoscataloestado', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@recsensorialquimicoscataloestado']);

Route::get('recsensorialquimicoscatalogostabla/{num_catalogo}', ['as' => 'recsensorialquimicoscatalogos.recsensorialquimicoscatalogostabla', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@recsensorialquimicoscatalogostabla']);

Route::get('tablasustanciasEntidad/{SUSTANCIA_QUIMICA_ID}', ['as' => 'recsensorialquimicoscatalogos.tablasustanciasEntidad', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@tablasustanciasEntidad']);

Route::get('listaMetodosSustanciasQuimicas/{SUSTANCIA_QUIMICA_ID}', ['as' => 'recsensorialquimicoscatalogos.listaMetodosSustanciasQuimicas', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@listaMetodosSustanciasQuimicas']);

Route::get('listaBeiSustanciasQuimicas/{SUSTANCIA_QUIMICA_ID}', ['as' => 'recsensorialquimicoscatalogos.listaBeiSustanciasQuimicas', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@listaBeiSustanciasQuimicas']);

Route::get('inforCartaEntidades/{ID_SUSTANCIA_QUIMICA}', ['as' => 'recsensorialquimicoscatalogos.inforCartaEntidades', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@inforCartaEntidades']);


Route::get('catSustanciaQuimicaEntidadEliminar/{ID_SUSTANCIA_QUIMICA_ENTIDAD}', ['as' => 'recsensorialquimicoscatalogos.catSustanciaQuimicaEntidadEliminar', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@catSustanciaQuimicaEntidadEliminar']);


Route::get('listaConnotaciones/{ID_ENTIDAD}', ['as' => 'listaConnotaciones', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@listaConnotaciones']);

Route::get('connotacionesSeleccionada/{ID_ENTIDAD}/{ID_SUSTANCIA_ENTIDAD}', ['as' => 'mostarConnotacionesSelccionadas', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@mostarConnotacionesSelccionadas']);

Route::get('mostarNotacionesSelccionadas/{ID_ENTIDAD}/{ID_BEI}', ['as' => 'mostarNotacionesSelccionadas', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@mostarNotacionesSelccionadas']);


Route::get('recsensorialsustanciasquimicoscatalogostabla/{num_catalogo}', ['as' => 'recsensorialquimicoscatalogos.recsensorialsustanciasquimicoscatalogostabla', 'uses' => 'recsensorialquimicos\recsensorialquimicoscatalogosController@recsensorialsustanciasquimicoscatalogostabla']);



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////RESUMEN///////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Route::resource('recsensorialresumen', 'recsensorial\recsensorialresumenController');

Route::get('recsensorialresumentabla/{recsensorial_id}', ['as' => 'recsensorialresumen.recsensorialresumentabla', 'uses' => 'recsensorial\recsensorialresumenController@recsensorialresumentabla']);

Route::get('recsensorialquimicosresumentabla/{recsensorial_id}', ['as' => 'recsensorialresumen.recsensorialquimicosresumentabla', 'uses' => 'recsensorial\recsensorialresumenController@recsensorialquimicosresumentabla']);


Route::get('recsensorialquimicosresumentabla_cliente/{recsensorial_id}', ['as' => 'recsensorialresumen.recsensorialquimicosresumentabla_cliente', 'uses' => 'recsensorial\recsensorialresumenController@recsensorialquimicosresumentabla_cliente']);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////REPORTES//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('recsensorialreporte1', 'recsensorialreportes\recsensorialreportewordController');

Route::get('recsensorialreportedescarga/{recsensorial_id}/{recsensorial_tipo}', ['as' => 'recsensorialreporte1.recsensorialreportedescarga', 'uses' => 'recsensorialreportes\recsensorialreportewordController@recsensorialreportedescarga']);


Route::get('recsensorialreporte1word/{recsensorial_id}', ['as' => 'recsensorialreporte1.recsensorialreporte1word', 'uses' => 'recsensorialreportes\recsensorialreportewordController@recsensorialreporte1word']);

Route::get('recsensorialreporte1wordcliente/{recsensorial_id}', ['as' => 'recsensorialreporte1.recsensorialreporte1wordcliente', 'uses' => 'recsensorialreportes\recsensorialreportewordController@recsensorialreporte1wordcliente']);

// Route::get('recsensorialquimicosreporte1word/{recsensorial_id}/{tipo}', ['as' => 'recsensorialreporte1.recsensorialquimicosreporte1word', 'uses' => 'recsensorialreportes\recsensorialquimicosreportewordController@recsensorialquimicosreporte1word']);

Route::get('recsensorialquimicosreporte1word/{recsensorial_id}/{tipo}/{numeroVersiones}/{numerodescarga}', ['as' => 'recsensorialreporte1.recsensorialquimicosreporte1word', 'uses' => 'recsensorialreportes\recsensorialquimicosreportewordController@recsensorialquimicosreporte1word']);

Route::get('recsensorialquimicosreporte1wordcliente/{recsensorial_id}', ['as' => 'recsensorialreporte1.recsensorialquimicosreporte1wordcliente', 'uses' => 'recsensorialreportes\recsensorialquimicosreportewordController@recsensorialquimicosreporte1wordcliente']);

Route::get('ejemploCargarDocx', ['as' => 'recsensorialreporte1.ejemploCargarDocx', 'uses' => 'recsensorialreportes\recsensorialquimicosreportewordController@ejemploCargarDocx']);




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////PROYECTOS///////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::resource('proyectos', 'proyecto\proyectoController');

Route::get('proyectotabla', ['as' => 'proyecto.proyectotabla', 'uses' => 'proyecto\proyectoController@proyectotabla']);

Route::get('proyectotablaInternos', ['as' => 'proyecto.proyectotablaInternos', 'uses' => 'proyecto\proyectoController@proyectotablaInternos']);

Route::get('proyectoUsuarios/{PROYECTO_ID}', ['as' => 'proyecto.proyectoUsuarios', 'uses' => 'proyecto\proyectoController@proyectoUsuarios']);

Route::get('actualizarEstadoUsuario/{ID}/{ACTIVO}', ['as' => 'proyecto.actualizarEstadoUsuario', 'uses' => 'proyecto\proyectoController@actualizarEstadoUsuario']);

Route::get('proyectoEstructuta/{ID_PROYECTO}', ['as' => 'proyecto.proyectoEstructuta', 'uses' => 'proyecto\proyectoController@proyectoEstructuta']);

Route::get('proyectoContactos', ['as' => 'proyecto.proyectoContactos', 'uses' => 'proyecto\proyectoController@proyectoContactos']);

Route::get('proyectoServicios/{ID_PROYECTO}', ['as' => 'proyecto.proyectoServicios', 'uses' => 'proyecto\proyectoController@proyectoServicios']);

Route::get('proyectosTotales', ['as' => 'proyecto.proyectosTotales', 'uses' => 'proyecto\proyectoController@proyectosTotales']);

Route::get('proyectoselectcliente/{cliente_id}', ['as' => 'proyecto.proyectoselectcliente', 'uses' => 'proyecto\proyectoController@proyectoselectcliente']);

Route::get('proyectocliente/{cliente_id}', ['as' => 'proyecto.proyectocliente', 'uses' => 'proyecto\proyectoController@proyectocliente']);

Route::get('proyectoselectcontrato/{contrato_id}/{tipo}', ['as' => 'proyecto.proyectoselectcontrato', 'uses' => 'proyecto\proyectoController@proyectoselectcontrato']);

Route::get('proyectocontrato/{contrato_id}', ['as' => 'proyecto.proyectocontrato', 'uses' => 'proyecto\proyectoController@proyectocontrato']);

Route::get('proyectoselectrecsensorial/{recsensorial_id}', ['as' => 'proyecto.proyectoselectrecsensorial', 'uses' => 'proyecto\proyectoController@proyectoselectrecsensorial']);

Route::get('proyectorecsensorial/{recsensorial_id}', ['as' => 'proyecto.proyectorecsensorial', 'uses' => 'proyecto\proyectoController@proyectorecsensorial']);

// Route::get('proyectoordenlistasignatariospdf/{proyecto_id}', ['as'=>'proyecto.proyectoordenlistasignatariospdf', 'uses'=>'proyecto\proyectoController@proyectoordenlistasignatariospdf']);

// Route::get('proyectoordenlistaequipospdf/{proyecto_id}', ['as'=>'proyecto.proyectoordenlistaequipospdf', 'uses'=>'proyecto\proyectoController@proyectoordenlistaequipospdf']);

Route::get('proyectoobservacionesproveedores/{proyecto_id}', ['as' => 'proyecto.proyectoobservacionesproveedores', 'uses' => 'proyecto\proyectoController@proyectoobservacionesproveedores']);

Route::get('proyectobloqueo/{proyecto_id}/{proyecto_estado}', ['as' => 'proyectos.proyectobloqueo', 'uses' => 'proyecto\proyectoController@proyectobloqueo']);

Route::get('proyectoSolicitarOS/{proyecto_id}/{valor}', ['as' => 'proyectos.proyectoSolicitarOS', 'uses' => 'proyecto\proyectoController@proyectoSolicitarOS']);

Route::get('clonarProyectoInterno/{proyecto_id}', ['as' => 'proyectos.clonarProyectoInterno', 'uses' => 'proyecto\proyectoController@clonarProyectoInterno']);

//==============================================


Route::resource('proyectoordenservicio', 'proyecto\proyectoordenservicioController');

Route::get('proyectoordenserviciotabla/{proyecto_id}', ['as' => 'proyectoordenservicio.proyectoordenserviciotabla', 'uses' => 'proyecto\proyectoordenservicioController@proyectoordenserviciotabla']);

Route::get('proyectoordenserviciopdf/{ordenservicio_id}', ['as' => 'proyectoordenservicio.proyectoordenserviciopdf', 'uses' => 'proyecto\proyectoordenservicioController@proyectoordenserviciopdf']);

Route::get('proyectoordenservicioadicionalpdf/{ordenservicioadicional_id}', ['as' => 'proyectoordenservicio.proyectoordenservicioadicionalpdf', 'uses' => 'proyecto\proyectoordenservicioController@proyectoordenservicioadicionalpdf']);

Route::get('proyectoordenservicioeliminar/{ordenservicio_id}/{tipo_documento}', ['as' => 'proyectoordenservicio.proyectoordenservicioeliminar', 'uses' => 'proyecto\proyectoordenservicioController@proyectoordenservicioeliminar']);


//==============================================


Route::resource('proyectoprorrogas', 'proyecto\proyectoprorrogasController');

Route::get('proyectoprorrogastabla/{proyecto_id}', ['as' => 'proyectoprorrogas.proyectoprorrogastabla', 'uses' => 'proyecto\proyectoprorrogasController@proyectoprorrogastabla']);

Route::get('proyectoprorrogaseliminar/{prorroga_id}', ['as' => 'proyectoprorrogas.proyectoprorrogaseliminar', 'uses' => 'proyecto\proyectoprorrogasController@proyectoprorrogaseliminar']);


//==============================================


Route::resource('proyectoproveedores', 'proyecto\proyectoproveedoresController');

Route::get('proyectoproveedorestodos', ['as' => 'proyectoproveedores.proyectoproveedorestodos', 'uses' => 'proyecto\proyectoproveedoresController@proyectoproveedorestodos']);

Route::get('proyectoproveedoralcances/{proveedor_id}', ['as' => 'proyectoproveedores.proyectoproveedoralcances', 'uses' => 'proyecto\proyectoproveedoresController@proyectoproveedoralcances']);

Route::get('proyectoproveedoreslista/{proyecto_id}/{recsensorial_id}/{recsensorial_alcancefisico}/{recsensorial_alcancequimico}', ['as' => 'proyectoproveedores.proyectoproveedoreslista', 'uses' => 'proyecto\proyectoproveedoresController@proyectoproveedoreslista']);


//==============================================


Route::resource('proyectosignatarios', 'proyecto\proyectosignatarioController');

Route::get('proyectosignatariosinventario/{proyecto_id}', ['as' => 'proyectosignatarios.proyectosignatariosinventario', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatariosinventario']);

Route::get('proyectosignatarioslistas/{proyecto_id}', ['as' => 'proyectosignatarios.proyectosignatarioslistas', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatarioslistas']);

Route::get('proyectosignatariosgenerarlistaestado/{proyecto_id}', ['as' => 'proyectosignatarios.proyectosignatariosgenerarlistaestado', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatariosgenerarlistaestado']);

Route::get('proyectosignatariosconsultaractual/{proyecto_id}', ['as' => 'proyectosignatarios.proyectosignatariosconsultaractual', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatariosconsultaractual']);

Route::get('proyectosignatariosconsultarhistorial/{proyecto_id}/{proyectosignatarios_revision}', ['as' => 'proyectosignatarios.proyectosignatariosconsultarhistorial', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatariosconsultarhistorial']);


//==============================================


Route::resource('proyectoequipos', 'proyecto\proyectoequipoController');

Route::get('proyectoequiposinventario/{proyecto_id}', ['as' => 'proyectoequipos.proyectoequiposinventario', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposinventario']);

Route::get('proyectoequiposlistas/{proyecto_id}', ['as' => 'proyectoequipos.proyectoequiposlistas', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposlistas']);

Route::get('proyectoequiposgenerarlistaestado/{proyecto_id}', ['as' => 'proyectoequipos.proyectoequiposgenerarlistaestado', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposgenerarlistaestado']);

Route::get('proyectoequiposconsultaractual/{proyecto_id}', ['as' => 'proyectoequipos.proyectoequiposconsultaractual', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposconsultaractual']);

Route::get('proyectoequiposconsultarhistorial/{proyecto_id}/{proyectoequipos_revision}', ['as' => 'proyectoequipos.proyectoequiposconsultarhistorial', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposconsultarhistorial']);


//==============================================
Route::resource('proyectovehiculos', 'proyecto\proyectoVehiculoController');

Route::get('proyectovehiculosinventario/{proyecto_id}', ['as' => 'proyectovehiculo.proyectoVehiculoController', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculosinventario']);

Route::get('proyectovehiculoslistas/{proyecto_id}', ['as' => 'proyectovehiculo.proyectovehiculoslistas', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculoslistas']);

Route::get('proyectovehiculosgenerarlistaestado/{proyecto_id}', ['as' => 'proyectovehiculo.proyectovehiculosgenerarlistaestado', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculosgenerarlistaestado']);

Route::get('proyectovehiculosconsultaractual/{proyecto_id}', ['as' => 'proyectovehiculo.proyectovehiculosconsultaractual', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculosconsultaractual']);


Route::get('proyectovehiculosconsultarhistorial/{proyecto_id}/{proyectovehiculos_revision}', ['as' => 'proyectoequipos.proyectovehiculosconsultarhistorial', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculosconsultarhistorial']);
//==============================================


Route::resource('proyectoordentrabajo', 'proyecto\proyectoordentrabajoController');

Route::get('proyectoordentrabajotabla/{proyecto_id}', ['as' => 'proyectoordentrabajo.proyectoordentrabajotabla', 'uses' => 'proyecto\proyectoordentrabajoController@proyectoordentrabajotabla']);

Route::get('proyectoordentrabajodatos/{ordentrabajo_id}', ['as' => 'proyectoordentrabajo.proyectoordentrabajodatos', 'uses' => 'proyecto\proyectoordentrabajoController@proyectoordentrabajodatos']);

Route::get('proyectoordentrabajocrear/{proyecto_id}', ['as' => 'proyectoordentrabajo.proyectoordentrabajocrear', 'uses' => 'proyecto\proyectoordentrabajoController@proyectoordentrabajocrear']);

Route::get('proyectoordentrabajoactualizar/{proyecto_id}/{ordentrabajo_id}', ['as' => 'proyectoordentrabajo.proyectoordentrabajoactualizar', 'uses' => 'proyecto\proyectoordentrabajoController@proyectoordentrabajoactualizar']);

Route::get('proyectoordentrabajoconsultar/{proyecto_id}/{ordentrabajo_id}/{ordentrabajo_descargar}', ['as' => 'proyectoordentrabajo.proyectoordentrabajoconsultar', 'uses' => 'proyecto\proyectoordentrabajoController@proyectoordentrabajoconsultar']);


//==============================================


Route::resource('proyectoordencompra', 'proyecto\proyectoordencompraController');

Route::get('proyectoordencompratabla/{proyecto_id}', ['as' => 'proyectoordencompra.proyectoordencompratabla', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompratabla']);

Route::get('proyectoordencompradatos/{ordencompra_id}', ['as' => 'proyectoordencompra.proyectoordencompradatos', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompradatos']);

Route::get('proyectoordencompraproveedores/{proyecto_id}', ['as' => 'proyectoordencompra.proyectoordencompraproveedores', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompraproveedores']);

Route::get('proyectoordencompraproveedorcotizacion/{proveedor_id}', ['as' => 'proyectoordencompra.proyectoordencompraproveedorcotizacion', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompraproveedorcotizacion']);

Route::get('proyectoordencompramostrar/{proyecto_id}/{proveedor_id}/{cotizacion_id}/{ordencompra_id}', ['as' => 'proyectoordencompra.proyectoordencompramostrar', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompramostrar']);

Route::get('proyectoordencompraactualizar/{proyecto_id}/{proveedor_id}/{cotizacion_id}/{ordencompra_id}/{ordencompra_tipolista}', ['as' => 'proyectoordencompra.proyectoordencompraactualizar', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencompraactualizar']);

Route::get('proyectoordencomprafactura/{ordencompra_id}', ['as' => 'proyectoordencompra.proyectoordencomprafactura', 'uses' => 'proyecto\proyectoordencompraController@proyectoordencomprafactura']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////PROYECTO EVIDENCIAS////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('proyectoevidencia', 'proyecto\proyectoevidenciaController');

Route::get('proyectoevidenciaparametros/{proyecto_id}', ['as' => 'proyectoevidencia.proyectoevidenciaparametros', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciaparametros']);

Route::get('proyectoevidenciadocumentos/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'proyectoevidencia.proyectoevidenciadocumentos', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciadocumentos']);

Route::get('proyectoevidenciadocumentodescargar/{documento_id}/{documento_opcion}', ['as' => 'proyectoevidencia.proyectoevidenciadocumentodescargar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciadocumentodescargar']);

Route::get('proyectoevidenciadocumentoeliminar/{documento_id}', ['as' => 'proyectoevidencia.proyectoevidenciadocumentoeliminar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciadocumentoeliminar']);

Route::get('proyectoevidenciafotos/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'proyectoevidencia.proyectoevidenciafotos', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciafotos']);

Route::get('proyectoevidenciafotomostrar/{foto_opcion}/{foto_id}', ['as' => 'proyectoevidencia.proyectoevidenciafotomostrar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciafotomostrar']);

Route::get('proyectoevidenciafotoeliminar/{foto_id}', ['as' => 'proyectoevidencia.proyectoevidenciafotoeliminar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciafotoeliminar']);

Route::get('proyectoevidenciafotoeliminarcarpeta/{proyecto_id}/{agente_nombre}/{carpeta}', ['as' => 'proyectoevidencia.proyectoevidenciafotoeliminarcarpeta', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciafotoeliminarcarpeta']);

Route::get('proyectoevidenciaplanos/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'proyectoevidencia.proyectoevidenciaplanos', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciaplanos']);

Route::get('proyectoevidenciaplanosmostrar/{foto_opcion}/{foto_id}', ['as' => 'proyectoevidencia.proyectoevidenciaplanosmostrar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciaplanosmostrar']);

Route::get('proyectoevidenciaplanoeliminar/{foto_id}', ['as' => 'proyectoevidencia.proyectoevidenciaplanoeliminar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciaplanoeliminar']);

Route::get('proyectoevidenciaplanoeliminarcarpeta/{proyecto_id}/{agente_nombre}/{carpeta}', ['as' => 'proyectoevidencia.proyectoevidenciaplanoeliminarcarpeta', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciaplanoeliminarcarpeta']);

// Route::get('proyectoevidenciaredimencionarfotos', ['as'=>'proyectoevidencia.proyectoevidenciaredimencionarfotos', 'uses'=>'proyecto\proyectoevidenciaController@proyectoevidenciaredimencionarfotos']);

Route::resource('proyectopuntosreales', 'proyecto\proyectopuntosrealesController');

Route::get('proyectopuntosrealeslista/{proyecto_id}', ['as' => 'proyectopuntosreales.proyectopuntosrealeslista', 'uses' => 'proyecto\proyectopuntosrealesController@proyectopuntosrealeslista']);

Route::get('proyectopuntosrealesreporte/{proyecto_id}', ['as' => 'proyectopuntosreales.proyectopuntosrealesreporte', 'uses' => 'proyecto\proyectopuntosrealesController@proyectopuntosrealesreporte']);

Route::get('proyectopuntosrealesactivo/{proyecto_id}', ['as' => 'proyectopuntosreales.proyectopuntosrealesactivo', 'uses' => 'proyecto\proyectopuntosrealesController@proyectopuntosrealesactivo']);

Route::get('proyectoevidenciabitacoratabla/{proyecto_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacoratabla', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacoratabla']);

Route::get('proyectoevidenciabitacorafotos/{bitacora_id}/{proyecto_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacorafotos', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacorafotos']);

Route::get('proyectoevidenciabitacorafotomostrar/{bitacorafoto_id}/{bitacorafoto_opcion}', ['as' => 'proyectoevidencia.proyectoevidenciabitacorafotomostrar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacorafotomostrar']);

Route::get('proyectoevidenciabitacorafotoeliminar/{bitacorafoto_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacorafotoeliminar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacorafotoeliminar']);

Route::post('proyectoevidenciabitacora', ['as' => 'proyectoevidencia.proyectoevidenciabitacora', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacora']);

Route::get('proyectoevidenciabitacoraeliminar/{bitacora_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacoraeliminar', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacoraeliminar']);

Route::get('proyectoevidenciabitacoraimprimir/{proyecto_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacoraimprimir', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacoraimprimir']);

Route::get('proyectoevidenciabitacoraactivo/{proyecto_id}', ['as' => 'proyectoevidencia.proyectoevidenciabitacoraactivo', 'uses' => 'proyecto\proyectoevidenciaController@proyectoevidenciabitacoraactivo']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////// PROYECTO INFORMES ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('reportes', 'reportes\reportesController');

Route::get('reporteslistaparametros/{proyecto_id}', ['as' => 'reportes.reporteslistaparametros', 'uses' => 'reportes\reportesController@reporteslistaparametros']);

Route::get('reporteslistaparametrosPsico/{proyecto_id}', ['as' => 'reportes.reporteslistaparametrosPsico', 'uses' => 'reportes\reportesController@reporteslistaparametrosPsico']);

Route::get('/servicioHI', ['as' => 'reportes.servicioHI', 'uses' => 'reportes\reportesController@servicioHI']);

Route::get('/servicioPsico', ['as' => 'reportes.servicioPsico', 'uses' => 'reportes\reportesController@servicioPsico']);

Route::get('/validacionAsignacionUserProyecto/{id}', ['as' => 'reportes.validacionAsignacionUserProyecto', 'uses' => 'reportes\reportesController@validacionAsignacionUserProyecto']);

Route::get('/estatusProyecto/{PROYECTO_ID}', ['as' => 'reportes.estatusProyecto', 'uses' => 'reportes\reportesController@estatusProyecto']);

Route::get('/finalizarPOE/{proyecto_id}/{OPCION}/{NUEVO}', ['as' => 'reportes.finalizarPOE', 'uses' => 'reportes\reportesController@finalizarPOE'])->middleware('asignacionUser:POE');

Route::get('obtenerDatosInformesProyecto/{ID}', ['as' => 'reportes.obtenerDatosInformesProyecto', 'uses' => 'reportes\reportesController@obtenerDatosInformesProyecto']);

Route::get('logoPortada/{ID}', ['as' => 'reportes.logoPortada', 'uses' => 'reportes\reportesController@logoPortada']);

Route::get('portadaInfo/{proyecto}/{agente}', ['as' => 'reportes.portadaInfo', 'uses' => 'reportes\reportesController@portadaInfo']);

Route::get('reportepoevista/{proyecto_id}', ['as' => 'reportes.reportepoevista', 'uses' => 'reportes\reportesController@reportepoevista']);

Route::get('reportepoevistapsico/{proyecto_id}', ['as' => 'reportes.reportepoevistapsico', 'uses' => 'reportes\reportesController@reportepoevistapsico']);

Route::get('reportecategoriatabla/{proyecto_id}', ['as' => 'reportes.reportecategoriatabla', 'uses' => 'reportes\reportesController@reportecategoriatabla']);

Route::get('reportecategoriaeliminar/{reportecategoria_id}', ['as' => 'reportes.reportecategoriaeliminar', 'uses' => 'reportes\reportesController@reportecategoriaeliminar']);

Route::get('reporteareacategorias/{proyecto_id}/{reportearea_id}/{recsensorialarea_id}', ['as' => 'reportes.reporteareacategorias', 'uses' => 'reportes\reportesController@reporteareacategorias']);

Route::get('reporteareatabla/{proyecto_id}', ['as' => 'reportes.reporteareatabla', 'uses' => 'reportes\reportesController@reporteareatabla']);

Route::get('reporteareaeliminar/{reportearea_id}', ['as' => 'reportes.reporteareaeliminar', 'uses' => 'reportes\reportesController@reporteareaeliminar']);

Route::get('reportepoeword/{proyecto_id}', ['as' => 'reportes.reportepoeword', 'uses' => 'reportes\reportesController@reportepoeword']);


Route::get('descargarPortadaInformes/{proyecto_id}/{tipo}', ['as' => 'reportes.descargarPortadaInformes', 'uses' => 'reportes\reportesController@descargarPortadaInformes']);


//==============================================
Route::get('reportenom035vista2/{proyecto_id}', ['as' => 'reportenom035.reportenom035vista2', 'uses' => 'reportes\reportenom0352Controller@reportenom035vista2']);

Route::get('reportenom035vista3/{proyecto_id}', ['as' => 'reportenom035.reportenom035vista3', 'uses' => 'reportes\reportenom0353Controller@reportenom035vista3']);



//==============================================
Route::resource('reporteiluminacion', 'reportes\reporteiluminacionController');

Route::get('reporteiluminacionvista/{proyecto_id}', ['as' => 'reporteiluminacion.reporteiluminacionvista', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionvista']);

Route::get('reporteiluminaciondatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciondatosgenerales', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciondatosgenerales']);

Route::get('reporteiluminaciontabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteiluminacion_id}', ['as' => 'reporteiluminacion.reporteiluminaciontabladefiniciones', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontabladefiniciones']);

Route::get('reporteiluminaciondefinicioneliminar/{definicion_id}', ['as' => 'reporteiluminacion.reporteiluminaciondefinicioneliminar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciondefinicioneliminar']);

Route::get('reporteiluminacionmapaubicacion/{reporteiluminacion_id}/{archivo_opcion}', ['as' => 'reporteiluminacion.reporteiluminacionmapaubicacion', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionmapaubicacion']);

Route::get('reporteiluminacioncategorias/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminacioncategorias', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacioncategorias']);

Route::get('reporteiluminacioncategoriaeliminar/{categoria_id}', ['as' => 'reporteiluminacion.reporteiluminacioncategoriaeliminar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacioncategoriaeliminar']);

Route::get('reporteiluminacionareas/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminacionareas', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionareas']);

Route::get('reporteiluminacionareascategorias/{proyecto_id}/{reporteiluminacion_id}/{area_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminacionareascategorias', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionareascategorias']);

Route::get('reporteiluminacionareascategoriasconsultar/{area_id}/{categoria_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminacionareascategoriasconsultar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionareascategoriasconsultar']);

Route::get('reporteiluminacionareaeliminar/{area_id}', ['as' => 'reporteiluminacion.reporteiluminacionareaeliminar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionareaeliminar']);

Route::get('reporteiluminaciontablapuntos/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminaciontablapuntos', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablapuntos']);

Route::get('reporteiluminaciontablapuntoseliminar/{punto_id}', ['as' => 'reporteiluminacion.reporteiluminaciontablapuntoseliminar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablapuntoseliminar']);

Route::get('reporteiluminaciontablaregistroseliminar/{proyecto_id}', ['as' => 'reporteiluminacion.reporteiluminaciontablaregistroseliminar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablaregistroseliminar']);

Route::get('reporteiluminaciontablaresultados/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminaciontablaresultados', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablaresultados']);

Route::get('reporteiluminaciontablamatrizexposicion/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminaciontablamatrizexposicion', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablamatrizexposicion']);

Route::get('reporteiluminaciondashboard/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminaciondashboard', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciondashboard']);

Route::get('reporteiluminaciontablarecomendaciones/{proyecto_id}/{reporteiluminacion_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciontablarecomendaciones', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablarecomendaciones']);

Route::get('reporteiluminacionresponsabledocumento/{reporteiluminacion_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reporteiluminacion.reporteiluminacionresponsabledocumento', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionresponsabledocumento']);

Route::get('reporteiluminaciontablaplanos/{proyecto_id}/{reporteiluminacion_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciontablaplanos', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablaplanos']);

Route::get('reporteiluminaciontablaequipoutilizado/{proyecto_id}/{reporteiluminacion_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciontablaequipoutilizado', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablaequipoutilizado']);

Route::get('reporteiluminaciontablainformeresultados/{proyecto_id}/{reporteiluminacion_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciontablainformeresultados', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablainformeresultados']);

Route::get('reporteiluminaciontablaanexos/{proyecto_id}/{reporteiluminacion_id}/{agente_nombre}', ['as' => 'reporteiluminacion.reporteiluminaciontablaanexos', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablaanexos']);

Route::get('reporteiluminaciontablarevisiones/{proyecto_id}', ['as' => 'reporteiluminacion.reporteiluminaciontablarevisiones', 'uses' => 'reportes\reporteiluminacionController@reporteiluminaciontablarevisiones']);

// Route::post('reporteiluminaciondashboardgraficas', ['as'=>'reporteiluminacion.reporteiluminaciondashboardgraficas', 'uses'=>'reportes\reporteiluminacionController@reporteiluminaciondashboardgraficas']);

// Route::post('reporteiluminacionnuevarevision', ['as'=>'reporteiluminacion.reporteiluminacionnuevarevision', 'uses'=>'reportes\reporteiluminacionController@reporteiluminacionnuevarevision']);

Route::get('reporteiluminacionconcluirrevision/{reporte_id}', ['as' => 'reporteiluminacion.reporteiluminacionconcluirrevision', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionconcluirrevision'])->middleware('asignacionUser:REVISION');

Route::get('reporteiluminacioncancelarrevision/{revision_id}', ['as' => 'reporteiluminacion.reporteiluminacioncancelarrevision', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacioncancelarrevision']);

// Route::get('reporteiluminacionword/{proyecto_id}/{reporteiluminacion_id}/{areas_poe}', ['as'=>'reporteiluminacion.reporteiluminacionword', 'uses'=>'reportes\reporteiluminacionwordController@reporteiluminacionword']);
Route::post('reporteiluminacionword', ['as' => 'reporteiluminacion.reporteiluminacionword', 'uses' => 'reportes\reporteiluminacionwordController@reporteiluminacionword']);

Route::get('reporteiluminacionworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reporteiluminacion.reporteiluminacionworddescargar', 'uses' => 'reportes\reporteiluminacionwordController@reporteiluminacionworddescargar']);


//==============================================


Route::resource('reporteruido', 'reportes\reporteruidoController');

Route::get('menuProteccionAuditiva/{proyecto_id}', ['as' => 'reporteruido.menuProteccionAuditiva', 'uses' => 'reportes\reporteruidoController@menuProteccionAuditiva']);

Route::get('reporteruidoequipoauditivocampos/{ID_PROTECCION}', ['as' => 'reporteruido.reporteruidoequipoauditivocampos', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoauditivocampos']);

Route::get('reporteruidovista/{proyecto_id}', ['as' => 'reporteruido.reporteruidovista', 'uses' => 'reportes\reporteruidoController@reporteruidovista']);

Route::get('generarPCA/{proyecto_id}', ['as' => 'reporteruido.generarPCA', 'uses' => 'reportes\reporteruidoController@generarPCA']);

Route::get('reporteruidodatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidodatosgenerales', 'uses' => 'reportes\reporteruidoController@reporteruidodatosgenerales']);

Route::get('guardarCampolmpe/{proyecto_id}/{id}/{valor}', ['as' => 'reporteruido.guardarCampolmpe', 'uses' => 'reportes\reporteruidoController@guardarCampolmpe']);

Route::get('consultarListaEquiposProteccion/{proyecto_id}/{valor}', ['as' => 'reporteruido.consultarListaEquiposProteccion', 'uses' => 'reportes\reporteruidoController@consultarListaEquiposProteccion']);


Route::get('reporteruidotabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reporteruido.reporteruidotabladefiniciones', 'uses' => 'reportes\reporteruidoController@reporteruidotabladefiniciones']);

Route::get('reporteruidodefinicioneliminar/{definicion_id}', ['as' => 'reporteruido.reporteruidodefinicioneliminar', 'uses' => 'reportes\reporteruidoController@reporteruidodefinicioneliminar']);

Route::get('reporteruidomapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reporteruido.reporteruidomapaubicacion', 'uses' => 'reportes\reporteruidoController@reporteruidomapaubicacion']);

Route::get('reporteruidocategorias/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidocategorias', 'uses' => 'reportes\reporteruidoController@reporteruidocategorias']);

Route::get('reporteruidocategoriaeliminar/{categoria_id}', ['as' => 'reporteruido.reporteruidocategoriaeliminar', 'uses' => 'reportes\reporteruidoController@reporteruidocategoriaeliminar']);

Route::get('reporteruidoareas/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidoareas', 'uses' => 'reportes\reporteruidoController@reporteruidoareas']);

Route::get('reporteruidoareascategoriasmaquinaria/{proyecto_id}/{reporteregistro_id}/{area_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidoareascategoriasmaquinaria', 'uses' => 'reportes\reporteruidoController@reporteruidoareascategoriasmaquinaria']);

Route::get('reporteruidoareaeliminar/{area_id}', ['as' => 'reporteruido.reporteruidoareaeliminar', 'uses' => 'reportes\reporteruidoController@reporteruidoareaeliminar']);

Route::get('reporteruidoequipoauditivotabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidoequipoauditivotabla', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoauditivotabla']);

Route::get('reporteruidoequipoauditivoatenuaciones/{equipoauditivo_id}', ['as' => 'reporteruido.reporteruidoequipoauditivoatenuaciones', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoauditivoatenuaciones']);

Route::get('reporteruidoequipoauditivocategorias/{proyecto_id}/{reporteregistro_id}/{equipoauditivo_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidoequipoauditivocategorias', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoauditivocategorias']);

Route::get('reporteruidoequipoauditivoeliminar/{equipoauditivo_id}', ['as' => 'reporteruido.reporteruidoequipoauditivoeliminar', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoauditivoeliminar']);

Route::get('reporteruidoepptabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reporteruido.reporteruidoepptabla', 'uses' => 'reportes\reporteruidoController@reporteruidoepptabla']);

Route::get('reporteruidoeppeliminar/{epp_id}', ['as' => 'reporteruido.reporteruidoeppeliminar', 'uses' => 'reportes\reporteruidoController@reporteruidoeppeliminar']);

Route::get('reporteruidoareaevaluaciontabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidoareaevaluaciontabla', 'uses' => 'reportes\reporteruidoController@reporteruidoareaevaluaciontabla']);

Route::get('reporteruidoareaevaluacioneliminar/{proyecto_id}/{reporteregistro_id}/{area_id}', ['as' => 'reporteruido.reporteruidoareaevaluacioneliminar', 'uses' => 'reportes\reporteruidoController@reporteruidoareaevaluacioneliminar']);

Route::get('reporteruidonivelsonorotabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reporteruido.reporteruidonivelsonorotabla', 'uses' => 'reportes\reporteruidoController@reporteruidonivelsonorotabla']);

Route::get('reporteruidonivelsonoroconsultapunto/{proyecto_id}/{reporteregistro_id}/{nivelsonoro_punto}', ['as' => 'reporteruido.reporteruidonivelsonoroconsultapunto', 'uses' => 'reportes\reporteruidoController@reporteruidonivelsonoroconsultapunto']);

Route::get('reporteruidonivelsonoroeliminar/{proyecto_id}/{reporteregistro_id}/{nivelsonoro_punto}', ['as' => 'reporteruido.reporteruidonivelsonoroeliminar', 'uses' => 'reportes\reporteruidoController@reporteruidonivelsonoroeliminar']);

Route::get('reporteruidopuntonertabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidopuntonertabla', 'uses' => 'reportes\reporteruidoController@reporteruidopuntonertabla']);

Route::get('reporteruidopuntonerareacategorias/{proyecto_id}/{reporteregistro_id}/{area_id}/{puntoner_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidopuntonerareacategorias', 'uses' => 'reportes\reporteruidoController@reporteruidopuntonerareacategorias']);

Route::get('reporteruidopuntonereliminar/{puntoner_id}', ['as' => 'reporteruido.reporteruidopuntonereliminar', 'uses' => 'reportes\reporteruidoController@reporteruidopuntonereliminar']);

Route::get('reporteruidodosisnertabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidodosisnertabla', 'uses' => 'reportes\reporteruidoController@reporteruidodosisnertabla']);

Route::get('reporteruidodosisnerareacategorias/{proyecto_id}/{reporteregistro_id}/{area_id}/{categoria_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidodosisnerareacategorias', 'uses' => 'reportes\reporteruidoController@reporteruidodosisnerareacategorias']);

Route::get('reporteruidodosisnereliminar/{dosisner_id}', ['as' => 'reporteruido.reporteruidodosisnereliminar', 'uses' => 'reportes\reporteruidoController@reporteruidodosisnereliminar']);

Route::get('reporteruidonivelruidoefectivotabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reporteruido.reporteruidonivelruidoefectivotabla', 'uses' => 'reportes\reporteruidoController@reporteruidonivelruidoefectivotabla']);

Route::get('reporteruidobandasoctavatabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidobandasoctavatabla', 'uses' => 'reportes\reporteruidoController@reporteruidobandasoctavatabla']);

Route::get('reporteruidomatrizexposicion/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidomatrizexposicion', 'uses' => 'reportes\reporteruidoController@reporteruidomatrizexposicion']);

Route::get('reporteruidodashboard/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteruido.reporteruidodashboard', 'uses' => 'reportes\reporteruidoController@reporteruidodashboard']);

// Route::post('reporteruidodashboardgraficas', ['as'=>'reporteruido.reporteruidodashboardgraficas', 'uses'=>'reportes\reporteruidoController@reporteruidodashboardgraficas']);

Route::get('reporteruidorecomendacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidorecomendacionestabla', 'uses' => 'reportes\reporteruidoController@reporteruidorecomendacionestabla']);

Route::get('reporteruidoresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reporteruido.reporteruidoresponsabledocumento', 'uses' => 'reportes\reporteruidoController@reporteruidoresponsabledocumento']);

Route::get('reporteruidoplanostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidoplanostabla', 'uses' => 'reportes\reporteruidoController@reporteruidoplanostabla']);

Route::get('reporteruidoequipoutilizadotabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidoequipoutilizadotabla', 'uses' => 'reportes\reporteruidoController@reporteruidoequipoutilizadotabla']);

Route::get('reporteruidoanexosresultadostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidoanexosresultadostabla', 'uses' => 'reportes\reporteruidoController@reporteruidoanexosresultadostabla']);

Route::get('reporteruidoanexosacreditacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteruido.reporteruidoanexosacreditacionestabla', 'uses' => 'reportes\reporteruidoController@reporteruidoanexosacreditacionestabla']);

Route::get('reporteruidorevisionestabla/{proyecto_id}', ['as' => 'reporteruido.reporteruidorevisionestabla', 'uses' => 'reportes\reporteruidoController@reporteruidorevisionestabla']);

Route::get('reporteruidorevisionconcluir/{reporte_id}', ['as' => 'reporteruido.reporteruidorevisionconcluir', 'uses' => 'reportes\reporteruidoController@reporteruidorevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reporteruidorevisioncancelar/{reporte_id}', ['as' => 'reporteruido.reporteruidorevisioncancelar', 'uses' => 'reportes\reporteruidoController@reporteruidorevisioncancelar']);

// Route::post('reporteruidorevisionnueva', ['as'=>'reporteruido.reporteruidorevisionnueva', 'uses'=>'reportes\reporteruidoController@reporteruidorevisionnueva']);

// Route::get('reporteruidoword/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as'=>'reporteruido.reporteruidoword', 'uses'=>'reportes\reporteruidowordController@reporteruidoword']);
Route::post('reporteruidoword', ['as' => 'reporteruido.reporteruidoword', 'uses' => 'reportes\reporteruidowordController@reporteruidoword']);

Route::get('reporteruidoworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reporteruido.reporteruidoworddescargar', 'uses' => 'reportes\reporteruidowordController@reporteruidoworddescargar']);


//==============================================


Route::resource('reportequimicos', 'reportes\reportequimicosController');

Route::get('reportequimicosvista/{proyecto_id}', ['as' => 'reportequimicos.reportequimicosvista', 'uses' => 'reportes\reportequimicosController@reportequimicosvista']);

Route::get('reportequimicosdatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosdatosgenerales', 'uses' => 'reportes\reportequimicosController@reportequimicosdatosgenerales']);

Route::get('reportequimicostabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicostabla', 'uses' => 'reportes\reportequimicosController@reportequimicostabla']);

Route::get('obtenerMetodosSustancias/{sustancia}', ['as' => 'reportequimicos.obtenerMetodosSustancias', 'uses' => 'reportes\reportequimicosController@obtenerMetodosSustancias']);

Route::get('reportequimicostabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicostabladefiniciones', 'uses' => 'reportes\reportequimicosController@reportequimicostabladefiniciones']);

Route::get('reportequimicosdefinicioneliminar/{definicion_id}', ['as' => 'reportequimicos.reportequimicosdefinicioneliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosdefinicioneliminar']);

Route::get('reportequimicosmapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reportequimicos.reportequimicosmapaubicacion', 'uses' => 'reportes\reportequimicosController@reportequimicosmapaubicacion']);

Route::get('reportequimicoscategorias/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicoscategorias', 'uses' => 'reportes\reportequimicosController@reportequimicoscategorias']);

Route::get('reportequimicoscategoriaeliminar/{categoria_id}', ['as' => 'reportequimicos.reportequimicoscategoriaeliminar', 'uses' => 'reportes\reportequimicosController@reportequimicoscategoriaeliminar']);

Route::get('reportequimicosareas/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosareas', 'uses' => 'reportes\reportequimicosController@reportequimicosareas']);

Route::get('reportequimicosareascategorias/{proyecto_id}/{reporteregistro_id}/{area_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosareascategorias', 'uses' => 'reportes\reportequimicosController@reportequimicosareascategorias']);

Route::get('reportequimicosareaeliminar/{area_id}', ['as' => 'reportequimicos.reportequimicosareaeliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosareaeliminar']);

Route::get('reportequimicosepptabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicosepptabla', 'uses' => 'reportes\reportequimicosController@reportequimicosepptabla']);

Route::get('reportequimicoseppeliminar/{epp_id}', ['as' => 'reportequimicos.reportequimicoseppeliminar', 'uses' => 'reportes\reportequimicosController@reportequimicoseppeliminar']);

Route::get('reportequimicossustanciasreconocimiento/{recsensorial_id}', ['as' => 'reportequimicos.reportequimicossustanciasreconocimiento', 'uses' => 'reportes\reportequimicosController@reportequimicossustanciasreconocimiento']);

Route::get('reportequimicosevaluaciontabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosevaluaciontabla', 'uses' => 'reportes\reportequimicosController@reportequimicosevaluaciontabla']);

Route::get('reportequimicosevaluacionareacategorias/{area_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosevaluacionareacategorias', 'uses' => 'reportes\reportequimicosController@reportequimicosevaluacionareacategorias']);

Route::get('reportequimicosevaluacioneliminar/{puntoevaluacion_id}', ['as' => 'reportequimicos.reportequimicosevaluacioneliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosevaluacioneliminar']);

Route::get('reportequimicosmetodomuestreotabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicosmetodomuestreotabla', 'uses' => 'reportes\reportequimicosController@reportequimicosmetodomuestreotabla']);

Route::get('reportequimicosmetodomuestreoeliminar/{proyecto_id}/{reporteregistro_id}/{parametro}', ['as' => 'reportequimicos.reportequimicosmetodomuestreoeliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosmetodomuestreoeliminar']);

Route::get('reportequimicosmatriztabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosmatriztabla', 'uses' => 'reportes\reportequimicosController@reportequimicosmatriztabla']);

Route::get('reportequimicosdashboard/{proyecto_id}/{reporteregistro_id}/{partida_id}/{areas_poe}', ['as' => 'reportequimicos.reportequimicosdashboard', 'uses' => 'reportes\reportequimicosController@reportequimicosdashboard']);

// Route::post('reportequimicosdashboardgraficas', ['as'=>'reportequimicos.reportequimicosdashboardgraficas', 'uses'=>'reportes\reportequimicosController@reportequimicosdashboardgraficas']);

Route::get('reportequimicosconclusionestabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicosconclusionestabla', 'uses' => 'reportes\reportequimicosController@reportequimicosconclusionestabla']);

Route::get('reportequimicosconclusioneliminar/{conclusion_id}', ['as' => 'reportequimicos.reportequimicosconclusioneliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosconclusioneliminar']);

Route::get('reportequimicosrecomendacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosrecomendacionestabla', 'uses' => 'reportes\reportequimicosController@reportequimicosrecomendacionestabla']);

Route::get('reportequimicosresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportequimicos.reportequimicosresponsabledocumento', 'uses' => 'reportes\reportequimicosController@reportequimicosresponsabledocumento']);

Route::get('reportequimicosplanostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosplanostabla', 'uses' => 'reportes\reportequimicosController@reportequimicosplanostabla']);

Route::get('reportequimicosevaluadostabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicosevaluadostabla', 'uses' => 'reportes\reportequimicosController@reportequimicosevaluadostabla']);

Route::get('reportequimicosequipoutilizadotabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosequipoutilizadotabla', 'uses' => 'reportes\reportequimicosController@reportequimicosequipoutilizadotabla']);

Route::get('reportequimicosanexosresultadostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosanexosresultadostabla', 'uses' => 'reportes\reportequimicosController@reportequimicosanexosresultadostabla']);

Route::get('reportequimicosanexosacreditacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportequimicos.reportequimicosanexosacreditacionestabla', 'uses' => 'reportes\reportequimicosController@reportequimicosanexosacreditacionestabla']);

Route::get('reportequimicosgrupostabla/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportequimicos.reportequimicosgrupostabla', 'uses' => 'reportes\reportequimicosController@reportequimicosgrupostabla']);

Route::get('reportequimicosgrupoeliminar/{proyecto_id}/{reporteregistro_id}/{partida_id}', ['as' => 'reportequimicos.reportequimicosgrupoeliminar', 'uses' => 'reportes\reportequimicosController@reportequimicosgrupoeliminar']);

Route::get('reportequimicosrevisionestabla/{proyecto_id}', ['as' => 'reportequimicos.reportequimicosrevisionestabla', 'uses' => 'reportes\reportequimicosController@reportequimicosrevisionestabla']);

Route::get('reportequimicosrevisionconcluir/{reporte_id}', ['as' => 'reportequimicos.reportequimicosrevisionconcluir', 'uses' => 'reportes\reportequimicosController@reportequimicosrevisionconcluir'])->middleware('asignacionUser:REVISION');

// Route::post('reportequimicosrevisionnueva', ['as'=>'reportequimicos.reportequimicosrevisionnueva', 'uses'=>'reportes\reportequimicosController@reportequimicosrevisionnueva']);

Route::get('reportequimicospartidashistorial/{proyecto_id}/{reporteregistro_id}/{reporterevisiones_id}', ['as' => 'reportequimicos.reportequimicospartidashistorial', 'uses' => 'reportes\reportequimicosController@reportequimicospartidashistorial']);

// Route::get('reportequimicosword/{proyecto_id}/{reporteregistro_id}/{partida_id}/{areas_poe}', ['as'=>'reportequimicos.reportequimicosword', 'uses'=>'reportes\reportequimicoswordController@reportequimicosword']);
Route::post('reportequimicosword', ['as' => 'reportequimicos.reportequimicosword', 'uses' => 'reportes\reportequimicoswordController@reportequimicosword']);

Route::get('reportequimicosworddescargar/{proyecto_id}/{revision_id}/{partida_id}/{ultima_revision}', ['as' => 'reportequimicos.reportequimicosworddescargar', 'uses' => 'reportes\reportequimicoswordController@reportequimicosworddescargar']);


//==============================================


Route::resource('reporteagua', 'reportes\reporteaguaController');

Route::get('reporteaguavista/{proyecto_id}', ['as' => 'reporteagua.reporteaguavista', 'uses' => 'reportes\reporteaguaController@reporteaguavista']);

Route::get('reporteaguadatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reporteagua.reporteaguadatosgenerales', 'uses' => 'reportes\reporteaguaController@reporteaguadatosgenerales']);

Route::get('reporteaguatabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reporteagua.reporteaguatabladefiniciones', 'uses' => 'reportes\reporteaguaController@reporteaguatabladefiniciones']);

Route::get('reporteaguadefinicioneliminar/{definicion_id}', ['as' => 'reporteagua.reporteaguadefinicioneliminar', 'uses' => 'reportes\reporteaguaController@reporteaguadefinicioneliminar']);

Route::get('reporteaguamapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reporteagua.reporteaguamapaubicacion', 'uses' => 'reportes\reporteaguaController@reporteaguamapaubicacion']);

Route::get('reporteaguacategorias/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteagua.reporteaguacategorias', 'uses' => 'reportes\reporteaguaController@reporteaguacategorias']);

Route::get('reporteaguacategoriaeliminar/{categoria_id}', ['as' => 'reporteagua.reporteaguacategoriaeliminar', 'uses' => 'reportes\reporteaguaController@reporteaguacategoriaeliminar']);

Route::get('reporteaguaareas/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteagua.reporteaguaareas', 'uses' => 'reportes\reporteaguaController@reporteaguaareas']);

Route::get('reporteaguaareaeliminar/{area_id}', ['as' => 'reporteagua.reporteaguaareaeliminar', 'uses' => 'reportes\reporteaguaController@reporteaguaareaeliminar']);

Route::get('reporteaguaevaluaciontabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteagua.reporteaguaevaluaciontabla', 'uses' => 'reportes\reporteaguaController@reporteaguaevaluaciontabla']);

Route::get('reporteaguaevaluacionparametros/{reporteaguaevaluacion_id}/{reporteaguaevaluacion_tipo}/{proveedor_id}', ['as' => 'reporteagua.reporteaguaevaluacionparametros', 'uses' => 'reportes\reporteaguaController@reporteaguaevaluacionparametros']);

Route::get('reporteaguaevaluacioncategorias/{proyecto_id}/{reporteregistro_id}/{reporteaguaevaluacion_id}/{areas_poe}', ['as' => 'reporteagua.reporteaguaevaluacioncategorias', 'uses' => 'reportes\reporteaguaController@reporteaguaevaluacioncategorias']);

Route::get('reporteaguaevaluacioneliminar/{reporteaguaevaluacion_id}', ['as' => 'reporteagua.reporteaguaevaluacioneliminar', 'uses' => 'reportes\reporteaguaController@reporteaguaevaluacioneliminar']);

Route::get('reporteaguamatriztabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteagua.reporteaguamatriztabla', 'uses' => 'reportes\reporteaguaController@reporteaguamatriztabla']);

Route::get('reporteaguadashboard/{proyecto_id}/{reporteregistro_id}/{reporte_tipo}/{areas_poe}', ['as' => 'reporteagua.reporteaguadashboard', 'uses' => 'reportes\reporteaguaController@reporteaguadashboard']);

// Route::post('reporteaguadashboardgraficas', ['as'=>'reporteagua.reporteaguadashboardgraficas', 'uses'=>'reportes\reporteaguaController@reporteaguadashboardgraficas']);

Route::get('reporteaguarecomendacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteagua.reporteaguarecomendacionestabla', 'uses' => 'reportes\reporteaguaController@reporteaguarecomendacionestabla']);

Route::get('reporteaguamaterialutilizado/{proyecto_id}/{reporteregistro_id}', ['as' => 'reporteagua.reporteaguamaterialutilizado', 'uses' => 'reportes\reporteaguaController@reporteaguamaterialutilizado']);

Route::get('reporteaguamaterialutilizadoeliminar/{materialutilizado_id}', ['as' => 'reporteagua.reporteaguamaterialutilizadoeliminar', 'uses' => 'reportes\reporteaguaController@reporteaguamaterialutilizadoeliminar']);

Route::get('reporteaguaresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reporteagua.reporteaguaresponsabledocumento', 'uses' => 'reportes\reporteaguaController@reporteaguaresponsabledocumento']);

Route::get('reporteaguaplanostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteagua.reporteaguaplanostabla', 'uses' => 'reportes\reporteaguaController@reporteaguaplanostabla']);

Route::get('reporteaguaanexosresultadostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteagua.reporteaguaanexosresultadostabla', 'uses' => 'reportes\reporteaguaController@reporteaguaanexosresultadostabla']);

Route::get('reporteaguaanexosacreditacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteagua.reporteaguaanexosacreditacionestabla', 'uses' => 'reportes\reporteaguaController@reporteaguaanexosacreditacionestabla']);

Route::get('reporteaguarevisionestabla/{proyecto_id}', ['as' => 'reporteagua.reporteaguarevisionestabla', 'uses' => 'reportes\reporteaguaController@reporteaguarevisionestabla']);

Route::get('reporteaguarevisionconcluir/{reporte_id}', ['as' => 'reporteagua.reporteaguarevisionconcluir', 'uses' => 'reportes\reporteaguaController@reporteaguarevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reporteaguarevisioncancelar/{reporte_id}', ['as' => 'reporteagua.reporteaguarevisioncancelar', 'uses' => 'reportes\reporteaguaController@reporteaguarevisioncancelar']);

Route::get('reporteaguarevisionparametros/{proyecto_id}/{reporteregistro_id}/{revision_id}/{ultimarevision_id}', ['as' => 'reporteagua.reporteaguarevisionparametros', 'uses' => 'reportes\reporteaguaController@reporteaguarevisionparametros']);

// Route::post('reporteaguarevisionnueva', ['as'=>'reporteagua.reporteaguarevisionnueva', 'uses'=>'reportes\reporteaguaController@reporteaguarevisionnueva']);

// Route::get('reporteaguaword/{proyecto_id}/{reporteregistro_id}/{informe_tipo}', ['as'=>'reporteagua.reporteaguaword', 'uses'=>'reportes\reporteaguawordController@reporteaguaword']);
Route::post('reporteaguaword', ['as' => 'reporteagua.reporteaguaword', 'uses' => 'reportes\reporteaguawordController@reporteaguaword']);

Route::get('reporteaguaworddescargar/{proyecto_id}/{revision_id}/{informe_tipo}/{ultima_revision}', ['as' => 'reporteagua.reporteaguaworddescargar', 'uses' => 'reportes\reporteaguawordController@reporteaguaworddescargar']);


//==============================================


Route::resource('reportehielo', 'reportes\reportehieloController');

Route::get('reportehielovista/{proyecto_id}', ['as' => 'reportehielo.reportehielovista', 'uses' => 'reportes\reportehieloController@reportehielovista']);

Route::get('reportehielodatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportehielo.reportehielodatosgenerales', 'uses' => 'reportes\reportehieloController@reportehielodatosgenerales']);

Route::get('reportehielotabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reportehielo.reportehielotabladefiniciones', 'uses' => 'reportes\reportehieloController@reportehielotabladefiniciones']);

Route::get('reportehielodefinicioneliminar/{definicion_id}', ['as' => 'reportehielo.reportehielodefinicioneliminar', 'uses' => 'reportes\reportehieloController@reportehielodefinicioneliminar']);

Route::get('reportehielomapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reportehielo.reportehielomapaubicacion', 'uses' => 'reportes\reportehieloController@reportehielomapaubicacion']);

Route::get('reportehielocategorias/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportehielo.reportehielocategorias', 'uses' => 'reportes\reportehieloController@reportehielocategorias']);

Route::get('reportehielocategoriaeliminar/{categoria_id}', ['as' => 'reportehielo.reportehielocategoriaeliminar', 'uses' => 'reportes\reportehieloController@reportehielocategoriaeliminar']);

Route::get('reportehieloareas/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportehielo.reportehieloareas', 'uses' => 'reportes\reportehieloController@reportehieloareas']);

Route::get('reportehieloareaeliminar/{area_id}', ['as' => 'reportehielo.reportehieloareaeliminar', 'uses' => 'reportes\reportehieloController@reportehieloareaeliminar']);

Route::get('reportehieloevaluaciontabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportehielo.reportehieloevaluaciontabla', 'uses' => 'reportes\reportehieloController@reportehieloevaluaciontabla']);

Route::get('reportehieloevaluacionparametros/{reportehieloevaluacion_id}/{reportehieloevaluacion_tipo}/{proveedor_id}', ['as' => 'reportehielo.reportehieloevaluacionparametros', 'uses' => 'reportes\reportehieloController@reportehieloevaluacionparametros']);

Route::get('reportehieloevaluacioncategorias/{proyecto_id}/{reporteregistro_id}/{reportehieloevaluacion_id}/{areas_poe}', ['as' => 'reportehielo.reportehieloevaluacioncategorias', 'uses' => 'reportes\reportehieloController@reportehieloevaluacioncategorias']);

Route::get('reportehieloevaluacioneliminar/{reportehieloevaluacion_id}', ['as' => 'reportehielo.reportehieloevaluacioneliminar', 'uses' => 'reportes\reportehieloController@reportehieloevaluacioneliminar']);

Route::get('reportehielomatriztabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reportehielo.reportehielomatriztabla', 'uses' => 'reportes\reportehieloController@reportehielomatriztabla']);

Route::get('reportehielodashboard/{proyecto_id}/{reporteregistro_id}/{reporte_tipo}/{areas_poe}', ['as' => 'reportehielo.reportehielodashboard', 'uses' => 'reportes\reportehieloController@reportehielodashboard']);

// Route::post('reportehielodashboardgraficas', ['as'=>'reportehielo.reportehielodashboardgraficas', 'uses'=>'reportes\reportehieloController@reportehielodashboardgraficas']);

Route::get('reportehielorecomendacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportehielo.reportehielorecomendacionestabla', 'uses' => 'reportes\reportehieloController@reportehielorecomendacionestabla']);

Route::get('reportehielomaterialutilizado/{proyecto_id}/{reporteregistro_id}', ['as' => 'reportehielo.reportehielomaterialutilizado', 'uses' => 'reportes\reportehieloController@reportehielomaterialutilizado']);

Route::get('reportehielomaterialutilizadoeliminar/{materialutilizado_id}', ['as' => 'reportehielo.reportehielomaterialutilizadoeliminar', 'uses' => 'reportes\reportehieloController@reportehielomaterialutilizadoeliminar']);

Route::get('reportehieloresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportehielo.reportehieloresponsabledocumento', 'uses' => 'reportes\reportehieloController@reportehieloresponsabledocumento']);

Route::get('reportehieloplanostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportehielo.reportehieloplanostabla', 'uses' => 'reportes\reportehieloController@reportehieloplanostabla']);

Route::get('reportehieloanexosresultadostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportehielo.reportehieloanexosresultadostabla', 'uses' => 'reportes\reportehieloController@reportehieloanexosresultadostabla']);

Route::get('reportehieloanexosacreditacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reportehielo.reportehieloanexosacreditacionestabla', 'uses' => 'reportes\reportehieloController@reportehieloanexosacreditacionestabla']);

Route::get('reportehielorevisionestabla/{proyecto_id}', ['as' => 'reportehielo.reportehielorevisionestabla', 'uses' => 'reportes\reportehieloController@reportehielorevisionestabla']);

Route::get('reportehielorevisionconcluir/{reporte_id}', ['as' => 'reportehielo.reportehielorevisionconcluir', 'uses' => 'reportes\reportehieloController@reportehielorevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reportehielorevisioncancelar/{reporte_id}', ['as' => 'reportehielo.reportehielorevisioncancelar', 'uses' => 'reportes\reportehieloController@reportehielorevisioncancelar']);

Route::get('reportehielorevisionparametros/{proyecto_id}/{reporteregistro_id}/{revision_id}/{ultimarevision_id}', ['as' => 'reportehielo.reportehielorevisionparametros', 'uses' => 'reportes\reportehieloController@reportehielorevisionparametros']);

// Route::post('reportehielorevisionnueva', ['as'=>'reportehielo.reportehielorevisionnueva', 'uses'=>'reportes\reportehieloController@reportehielorevisionnueva']);

// Route::get('reportehieloword/{proyecto_id}/{reporteregistro_id}/{informe_tipo}/{areas_poe}', ['as'=>'reportehielo.reportehieloword', 'uses'=>'reportes\reportehielowordController@reportehieloword']);
Route::post('reportehieloword', ['as' => 'reportehielo.reportehieloword', 'uses' => 'reportes\reportehielowordController@reportehieloword']);

Route::get('reportehieloworddescargar/{proyecto_id}/{revision_id}/{informe_tipo}/{ultima_revision}', ['as' => 'reportehielo.reportehieloworddescargar', 'uses' => 'reportes\reportehielowordController@reportehieloworddescargar']);


//==============================================


Route::resource('reporteaire', 'reportes\reporteaireController');

Route::get('reporteairevista/{proyecto_id}', ['as' => 'reporteaire.reporteairevista', 'uses' => 'reportes\reporteaireController@reporteairevista']);

Route::get('reporteairedatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reporteaire.reporteairedatosgenerales', 'uses' => 'reportes\reporteaireController@reporteairedatosgenerales']);

Route::get('reporteairetabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reporteaire.reporteairetabladefiniciones', 'uses' => 'reportes\reporteaireController@reporteairetabladefiniciones']);

Route::get('reporteairedefinicioneliminar/{definicion_id}', ['as' => 'reporteaire.reporteairedefinicioneliminar', 'uses' => 'reportes\reporteaireController@reporteairedefinicioneliminar']);

Route::get('reporteairemapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reporteaire.reporteairemapaubicacion', 'uses' => 'reportes\reporteaireController@reporteairemapaubicacion']);

Route::get('reporteairecategorias/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteaire.reporteairecategorias', 'uses' => 'reportes\reporteaireController@reporteairecategorias']);

Route::get('reporteairecategoriaeliminar/{categoria_id}', ['as' => 'reporteaire.reporteairecategoriaeliminar', 'uses' => 'reportes\reporteaireController@reporteairecategoriaeliminar']);

Route::get('reporteaireareas/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteaire.reporteaireareas', 'uses' => 'reportes\reporteaireController@reporteaireareas']);

Route::get('reporteaireareaeliminar/{area_id}', ['as' => 'reporteaire.reporteaireareaeliminar', 'uses' => 'reportes\reporteaireController@reporteaireareaeliminar']);

Route::get('obtenerCAI/{ID}', ['as' => 'reporteaire.obtenerCAI', 'uses' => 'reportes\reporteaireController@obtenerCAI']);


Route::get('reporteaireareacategorias/{proyecto_id}/{reporteregistro_id}/{area_id}/{areas_poe}', ['as' => 'reporteaire.reporteaireareacategorias', 'uses' => 'reportes\reporteaireController@reporteaireareacategorias']);

Route::get('reporteaireevaluaciontabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteaire.reporteaireevaluaciontabla', 'uses' => 'reportes\reporteaireController@reporteaireevaluaciontabla']);

Route::get('reporteaireevaluacioncategorias/{reporteaireevaluacion_id}/{reporteairearea_id}/{areas_poe}', ['as' => 'reporteaire.reporteaireevaluacioncategorias', 'uses' => 'reportes\reporteaireController@reporteaireevaluacioncategorias']);

Route::get('reporteaireevaluacioneliminar/{reporteaireevaluacion_id}', ['as' => 'reporteaire.reporteaireevaluacioneliminar', 'uses' => 'reportes\reporteaireController@reporteaireevaluacioneliminar']);

Route::get('reporteairematriztabla/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteaire.reporteairematriztabla', 'uses' => 'reportes\reporteaireController@reporteairematriztabla']);

Route::get('reporteairedashboard/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as' => 'reporteaire.reporteairedashboard', 'uses' => 'reportes\reporteaireController@reporteairedashboard']);

// Route::post('reporteairedashboardgraficas', ['as'=>'reporteaire.reporteairedashboardgraficas', 'uses'=>'reportes\reporteaireController@reporteairedashboardgraficas']);

Route::get('reporteairerecomendacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteairerecomendacionestabla', 'uses' => 'reportes\reporteaireController@reporteairerecomendacionestabla']);

Route::get('reporteaireresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reporteaire.reporteaireresponsabledocumento', 'uses' => 'reportes\reporteaireController@reporteaireresponsabledocumento']);

Route::get('reporteaireplanostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteaireplanostabla', 'uses' => 'reportes\reporteaireController@reporteaireplanostabla']);

Route::get('reporteaireequipoutilizadotabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteaireequipoutilizadotabla', 'uses' => 'reportes\reporteaireController@reporteaireequipoutilizadotabla']);

Route::get('reporteaireanexosresultadostabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteaireanexosresultadostabla', 'uses' => 'reportes\reporteaireController@reporteaireanexosresultadostabla']);

Route::get('reporteaireanexosacreditacionestabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteaireanexosacreditacionestabla', 'uses' => 'reportes\reporteaireController@reporteaireanexosacreditacionestabla']);

Route::get('reporteairenotasstpstabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteairenotasstpstabla', 'uses' => 'reportes\reporteaireController@reporteairenotasstpstabla']);

Route::get('reporteairenotasematabla/{proyecto_id}/{reporteregistro_id}/{agente_nombre}', ['as' => 'reporteaire.reporteairenotasematabla', 'uses' => 'reportes\reporteaireController@reporteairenotasematabla']);

Route::get('reporteairenotaseliminar/{reportenotas_id}', ['as' => 'reporteaire.reporteairenotaseliminar', 'uses' => 'reportes\reporteaireController@reporteairenotaseliminar']);

Route::get('reporteairerevisionestabla/{proyecto_id}', ['as' => 'reporteaire.reporteairerevisionestabla', 'uses' => 'reportes\reporteaireController@reporteairerevisionestabla']);

Route::get('reporteairerevisionconcluir/{reporte_id}', ['as' => 'reporteaire.reporteairerevisionconcluir', 'uses' => 'reportes\reporteaireController@reporteairerevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reporteairerevisioncancelar/{reporte_id}', ['as' => 'reporteaire.reporteairerevisioncancelar', 'uses' => 'reportes\reporteaireController@reporteairerevisioncancelar']);

// Route::post('reporteairerevisionnueva', ['as'=>'reporteaire.reporteairerevisionnueva', 'uses'=>'reportes\reporteaireController@reporteairerevisionnueva']);

// Route::get('reporteaireword/{proyecto_id}/{reporteregistro_id}/{areas_poe}', ['as'=>'reporteaire.reporteaireword', 'uses'=>'reportes\reporteairewordController@reporteaireword']);
Route::post('reporteaireword', ['as' => 'reporteaire.reporteaireword', 'uses' => 'reportes\reporteairewordController@reporteaireword']);

Route::get('reporteaireworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reporteaire.reporteaireworddescargar', 'uses' => 'reportes\reporteairewordController@reporteaireworddescargar']);


//==============================================


Route::resource('reportetemperatura', 'reportes\reportetemperaturaController');

Route::get('reportetemperaturavista/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturavista', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturavista']);

Route::get('reportetemperaturadatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturadatosgenerales', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturadatosgenerales']);

Route::get('reportetemperaturatabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reportetemperatura.reportetemperaturatabladefiniciones', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturatabladefiniciones']);

Route::get('reportetemperaturadefinicioneliminar/{definicion_id}', ['as' => 'reportetemperatura.reportetemperaturadefinicioneliminar', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturadefinicioneliminar']);

Route::get('reportetemperaturamapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reportetemperatura.reportetemperaturamapaubicacion', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturamapaubicacion']);

Route::get('reportetemperaturaareas/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturaareas', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaareas']);

Route::get('reportetemperaturaareacategorias/{proyecto_id}/{area_id}', ['as' => 'reportetemperatura.reportetemperaturaareacategorias', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaareacategorias']);

Route::get('reportetemperaturaevaluaciontabla/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturaevaluaciontabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaevaluaciontabla']);

Route::get('reportetemperaturaevaluacioncategorias/{reportearea_id}/{reportecategoria_id}', ['as' => 'reportetemperatura.reportetemperaturaevaluacioncategorias', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaevaluacioncategorias']);

Route::get('reportetemperaturaevaluacioneliminar/{reportetemperaturaevaluacion_id}', ['as' => 'reportetemperatura.reportetemperaturaevaluacioneliminar', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaevaluacioneliminar']);

Route::get('reportetemperaturamatriztabla/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturamatriztabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturamatriztabla']);

Route::get('reportetemperaturadashboard/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturadashboard', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturadashboard']);

Route::get('reportetemperaturarecomendacionestabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturarecomendacionestabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturarecomendacionestabla']);

Route::get('reportetemperaturaresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportetemperatura.reportetemperaturaresponsabledocumento', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaresponsabledocumento']);

Route::get('reportetemperaturaplanostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturaplanostabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaplanostabla']);

Route::get('reportetemperaturaequipoutilizadotabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturaequipoutilizadotabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaequipoutilizadotabla']);

Route::get('reportetemperaturaanexosresultadostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturaanexosresultadostabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaanexosresultadostabla']);

Route::get('reportetemperaturaanexosacreditacionestabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportetemperatura.reportetemperaturaanexosacreditacionestabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturaanexosacreditacionestabla']);

Route::get('reportetemperaturarevisionestabla/{proyecto_id}', ['as' => 'reportetemperatura.reportetemperaturarevisionestabla', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturarevisionestabla']);

Route::get('reportetemperaturarevisionconcluir/{reporte_id}', ['as' => 'reportetemperatura.reportetemperaturarevisionconcluir', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturarevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reportetemperaturarevisioncancelar/{reporte_id}', ['as' => 'reportetemperatura.reportetemperaturarevisioncancelar', 'uses' => 'reportes\reportetemperaturaController@reportetemperaturarevisioncancelar']);

Route::post('reportetemperaturaword', ['as' => 'reportetemperatura.reportetemperaturaword', 'uses' => 'reportes\reportetemperaturawordController@reportetemperaturaword']);

Route::get('reportetemperaturaworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reportetemperatura.reportetemperaturaworddescargar', 'uses' => 'reportes\reportetemperaturawordController@reportetemperaturaworddescargar']);


//==============================================


Route::resource('reportevibracion', 'reportes\reportevibracionController');

Route::get('reportevibracionvista/{proyecto_id}', ['as' => 'reportevibracion.reportevibracionvista', 'uses' => 'reportes\reportevibracionController@reportevibracionvista']);

Route::get('reportevibraciondatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibraciondatosgenerales', 'uses' => 'reportes\reportevibracionController@reportevibraciondatosgenerales']);

Route::get('reportevibraciontabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reportevibracion.reportevibraciontabladefiniciones', 'uses' => 'reportes\reportevibracionController@reportevibraciontabladefiniciones']);

Route::get('reportevibraciondefinicioneliminar/{definicion_id}', ['as' => 'reportevibracion.reportevibraciondefinicioneliminar', 'uses' => 'reportes\reportevibracionController@reportevibraciondefinicioneliminar']);

Route::get('reportevibracionmapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reportevibracion.reportevibracionmapaubicacion', 'uses' => 'reportes\reportevibracionController@reportevibracionmapaubicacion']);

Route::get('reportevibracionareas/{proyecto_id}', ['as' => 'reportevibracion.reportevibracionareas', 'uses' => 'reportes\reportevibracionController@reportevibracionareas']);

Route::get('reportevibracionareacategorias/{proyecto_id}/{area_id}', ['as' => 'reportevibracion.reportevibracionareacategorias', 'uses' => 'reportes\reportevibracionController@reportevibracionareacategorias']);

Route::get('reportevibracionevaluaciontabla/{proyecto_id}', ['as' => 'reportevibracion.reportevibracionevaluaciontabla', 'uses' => 'reportes\reportevibracionController@reportevibracionevaluaciontabla']);

Route::get('reportevibracionevaluacioncategorias/{reportearea_id}/{reportecategoria_id}', ['as' => 'reportevibracion.reportevibracionevaluacioncategorias', 'uses' => 'reportes\reportevibracionController@reportevibracionevaluacioncategorias']);

Route::get('reportevibracionevaluaciondatos/{reportevibracionevaluacion_id}', ['as' => 'reportevibracion.reportevibracionevaluaciondatos', 'uses' => 'reportes\reportevibracionController@reportevibracionevaluaciondatos']);

Route::get('reportevibracionevaluacioneliminar/{reportevibracionevaluacion_id}', ['as' => 'reportevibracion.reportevibracionevaluacioneliminar', 'uses' => 'reportes\reportevibracionController@reportevibracionevaluacioneliminar']);

Route::get('reportevibracionmatriztabla/{proyecto_id}', ['as' => 'reportevibracion.reportevibracionmatriztabla', 'uses' => 'reportes\reportevibracionController@reportevibracionmatriztabla']);

Route::get('reportevibraciondashboard/{proyecto_id}', ['as' => 'reportevibracion.reportevibraciondashboard', 'uses' => 'reportes\reportevibracionController@reportevibraciondashboard']);

Route::get('reportevibracionrecomendacionestabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibracionrecomendacionestabla', 'uses' => 'reportes\reportevibracionController@reportevibracionrecomendacionestabla']);

Route::get('reportevibracionresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportevibracion.reportevibracionresponsabledocumento', 'uses' => 'reportes\reportevibracionController@reportevibracionresponsabledocumento']);

Route::get('reportevibracionplanostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibracionplanostabla', 'uses' => 'reportes\reportevibracionController@reportevibracionplanostabla']);

Route::get('reportevibracionequipoutilizadotabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibracionequipoutilizadotabla', 'uses' => 'reportes\reportevibracionController@reportevibracionequipoutilizadotabla']);

Route::get('reportevibracionanexosresultadostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibracionanexosresultadostabla', 'uses' => 'reportes\reportevibracionController@reportevibracionanexosresultadostabla']);

Route::get('reportevibracionanexosacreditacionestabla/{proyecto_id}/{agente_nombre}', ['as' => 'reportevibracion.reportevibracionanexosacreditacionestabla', 'uses' => 'reportes\reportevibracionController@reportevibracionanexosacreditacionestabla']);

Route::get('reportevibracionrevisionestabla/{proyecto_id}', ['as' => 'reportevibracion.reportevibracionrevisionestabla', 'uses' => 'reportes\reportevibracionController@reportevibracionrevisionestabla']);

Route::get('reportevibracionrevisionconcluir/{reporte_id}', ['as' => 'reportevibracion.reportevibracionrevisionconcluir', 'uses' => 'reportes\reportevibracionController@reportevibracionrevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reportevibracionrevisioncancelar/{reporte_id}', ['as' => 'reportevibracion.reportevibracionrevisioncancelar', 'uses' => 'reportes\reportevibracionController@reportevibracionrevisioncancelar']);

Route::post('reportevibracionword', ['as' => 'reportevibracion.reportevibracionword', 'uses' => 'reportes\reportevibracionwordController@reportevibracionword']);

Route::get('reportevibracionworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reportevibracion.reportevibracionworddescargar', 'uses' => 'reportes\reportevibracionwordController@reportevibracionworddescargar']);


//==============================================


Route::resource('reporteserviciopersonal', 'reportes\reporteserviciopersonalController');

Route::get('reporteserviciopersonalvista/{proyecto_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalvista', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalvista']);

Route::get('reporteserviciopersonaldatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reporteserviciopersonal.reporteserviciopersonaldatosgenerales', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonaldatosgenerales']);

Route::get('reporteserviciopersonaltabladefiniciones/{proyecto_id}/{agente_nombre}/{reporteregistro_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonaltabladefiniciones', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonaltabladefiniciones']);

Route::get('reporteserviciopersonaldefinicioneliminar/{definicion_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonaldefinicioneliminar', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonaldefinicioneliminar']);

Route::get('reporteserviciopersonalmapaubicacion/{reporteregistro_id}/{archivo_opcion}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalmapaubicacion', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalmapaubicacion']);

Route::get('reporteserviciopersonalevaluacionpyd/{proyecto_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalevaluacionpyd', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalevaluacionpyd']);

Route::get('reporteserviciopersonalevaluaciontabla/{proyecto_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalevaluaciontabla', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalevaluaciontabla']);

Route::get('reporteserviciopersonalevaluacionevidencia/{evaluacion_id}/{evaluacion_opcion}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalevaluacionevidencia', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalevaluacionevidencia']);

Route::get('reporteserviciopersonalevaluacionevidenciaeliminar/{evaluacion_id}/{evidencia_opcion}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalevaluacionevidenciaeliminar', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalevaluacionevidenciaeliminar']);

Route::get('reporteserviciopersonalevaluacioneliminar/{evaluacion_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalevaluacioneliminar', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalevaluacioneliminar']);

Route::get('reporteserviciopersonalresponsabledocumento/{reporteregistro_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalresponsabledocumento', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalresponsabledocumento']);

Route::get('reporteserviciopersonalcondicionesinsegurastabla/{proyecto_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalcondicionesinsegurastabla', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalcondicionesinsegurastabla']);

Route::get('reporteserviciopersonalcondicionesinsegurascategorias/{proyecto_id}/{condicioninsegura_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalcondicionesinsegurascategorias', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalcondicionesinsegurascategorias']);

Route::get('reporteserviciopersonalcondicionesinsegurasfoto/{condicioninsegura_id}/{archivo_opcion}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalcondicionesinsegurasfoto', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalcondicionesinsegurasfoto']);

Route::get('reporteserviciopersonalcondicionesinseguraseliminar/{condicioninsegura_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalcondicionesinseguraseliminar', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalcondicionesinseguraseliminar']);

Route::get('reporteserviciopersonalanexosresultadostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalanexosresultadostabla', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalanexosresultadostabla']);

Route::get('reporteserviciopersonalplanostabla/{proyecto_id}/{agente_nombre}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalplanostabla', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalplanostabla']);

Route::get('reporteserviciopersonalrevisionestabla/{proyecto_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalrevisionestabla', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalrevisionestabla']);

Route::get('reporteserviciopersonalrevisionconcluir/{reporte_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalrevisionconcluir', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalrevisionconcluir'])->middleware('asignacionUser:REVISION');

Route::get('reporteserviciopersonalrevisioncancelar/{reporte_id}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalrevisioncancelar', 'uses' => 'reportes\reporteserviciopersonalController@reporteserviciopersonalrevisioncancelar']);

Route::post('reporteserviciopersonalword', ['as' => 'reporteserviciopersonal.reporteserviciopersonalword', 'uses' => 'reportes\reporteserviciopersonalwordController@reporteserviciopersonalword']);

Route::get('reporteserviciopersonalworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reporteserviciopersonal.reporteserviciopersonalworddescargar', 'uses' => 'reportes\reporteserviciopersonalwordController@reporteserviciopersonalworddescargar']);

//================================================================================================
Route::resource('reportemapaderiesgo', 'reportes\reporteMapaController');

Route::get('reportemapaderiesgovista/{proyecto_id}', ['as' => 'reportemapaderiesgo.reportemapaderiesgovista', 'uses' => 'reportes\reporteMapaController@reportemapaderiesgovista']);

//================================================================================================

Route::resource('reportebei', 'reportes\reporteBeiController');

Route::get('reportebeivista/{proyecto_id}', ['as' => 'reportebei.reportebeivista', 'uses' => 'reportes\reporteBeiController@reportebeivista']);

Route::get('reportebeitabladefiniciones/{proyecto_id}/{agente_nombre}/{reportebei_id}', ['as' => 'reportebei.reportebeitabladefiniciones', 'uses' => 'reportes\reporteBeiController@reportebeitabladefiniciones']);

Route::get('reportebeidefinicioneliminar/{definicion_id}', ['as' => 'reporteBeiController.reportebeidefinicioneliminar', 'uses' => 'reportes\reporteBeiController@reportebeidefinicioneliminar']);

Route::get('reportebeidatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportebei.reportebeidatosgenerales', 'uses' => 'reportes\reporteBeiController@reportebeidatosgenerales']);

Route::get('reportebeimapaubicacion/{reportebei_id}/{archivo_opcion}', ['as' => 'reportebei.reportebeimapaubicacion', 'uses' => 'reportes\reporteBeiController@reportebeimapaubicacion']);

Route::get('reportebeiresponsabledocumento/{reportebei_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportebei.reportebeiresponsabledocumento', 'uses' => 'reportes\reporteBeiController@reportebeiresponsabledocumento']);

Route::get('reportebeitablarevisiones/{proyecto_id}', ['as' => 'reportebei.reportebeitablarevisiones', 'uses' => 'reportes\reporteBeiController@reportebeitablarevisiones']);

Route::get('reportebeicategorias/{proyecto_id}/{reportebei_id}/{areas_poe}', ['as' => 'reportebei.reportebeicategorias', 'uses' => 'reportes\reporteBeiController@reportebeicategorias']);

Route::get('reportebeicategoriaeliminar/{categoria_id}', ['as' => 'reportebei.reportebeicategoriaseliminar', 'uses' => 'reportes\reporteBeiController@reportebeicategoriaeliminar']);


Route::get('reportebeiareas/{proyecto_id}/{reportebei_id}/{areas_poe}', ['as' => 'reportebei.reportebeiareas', 'uses' => 'reportes\reporteBeiController@reportebeiareas']);

Route::get('reportebeiareascategorias/{proyecto_id}/{reportebei_id}/{area_id}/{areas_poe}', ['as' => 'reportebei.reportebeiareascategorias', 'uses' => 'reportes\reporteBeiController@reportebeiareascategorias']);

Route::get('reporteiluminacionareascategoriasconsultar/{area_id}/{categoria_id}/{reporteiluminacion_id}/{areas_poe}', ['as' => 'reporteiluminacion.reporteiluminacionareascategoriasconsultar', 'uses' => 'reportes\reporteiluminacionController@reporteiluminacionareascategoriasconsultar']);

Route::get('reportebeitablarecomendaciones/{proyecto_id}/{reportebei_id}/{agente_nombre}', ['as' => 'reportebei.reportebeitablarecomendaciones', 'uses' => 'reportes\reporteBeiController@reportebeitablarecomendaciones']);

Route::get('reportebeitablainformeresultados/{proyecto_id}/{reportebei_id}/{agente_nombre}', ['as' => 'reportebei.reportebeitablainformeresultados', 'uses' => 'reportes\reporteBeiController@reportebeitablainformeresultados']);

Route::get('reportebeitablaequipoutilizado/{proyecto_id}/{reportebei_id}/{agente_nombre}', ['as' => 'reportebei.reportebeitablaequipoutilizado', 'uses' => 'reportes\reporteBeiController@reportebeitablaequipoutilizado']);


Route::get('reportebeiepptabla/{proyecto_id}/{reportebei_id}', ['as' => 'reportebei.reportebeiepptabla', 'uses' => 'reportes\reporteBeiController@reportebeiepptabla']);

Route::get('reportebeieppeliminar/{epp_id}', ['as' => 'reportebei.reportebeieppeliminar', 'uses' => 'reportes\reporteBeiController@reportebeieppeliminar']);

Route::get('reportebeitablapuntos/{proyecto_id}', ['as' => 'reportebei.reportebeitablapuntos', 'uses' => 'reportes\reporteBeiController@reportebeitablapuntos']);

Route::get('reportebeiconcluirrevision/{reporte_id}', ['as' => 'reportebei.reportebeiconcluirrevision', 'uses' => 'reportes\reporteBeiController@reportebeiconcluirrevision'])->middleware('asignacionUser:REVISION');

Route::get('reportebeiworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reportebei.reportebeiworddescargar', 'uses' => 'reportes\reporteBeiWordController@reportebeiworddescargar']);

Route::post('reportebeiword', ['as' => 'reportebei.reportebeiword', 'uses' => 'reportes\reporteBeiWordController@reportebeiword']);


//==============================================


Route::resource('reportealimentos', 'reportes\reportealimentosController');

Route::get('reportealimentosvista/{proyecto_id}', ['as' => 'reportealimentos.reportealimentosvista', 'uses' => 'reportes\reportealimentosController@reportealimentosvista']);

Route::get('reportealimentosdatosgenerales/{proyecto_id}/{agente_id}/{agente_nombre}', ['as' => 'reportealimentos.reportealimentosdatosgenerales', 'uses' => 'reportes\reportealimentosController@reportealimentosdatosgenerales']);

Route::get('reportealimentosmapaubicacion/{reportealimentos_id}/{archivo_opcion}', ['as' => 'reportealimentos.reportealimentosmapaubicacion', 'uses' => 'reportes\reportealimentosController@reportealimentosmapaubicacion']);

Route::get('reportealimentosresponsabledocumento/{reportealimentos_id}/{responsabledoc_tipo}/{responsabledoc_opcion}', ['as' => 'reportealimentos.reportealimentosresponsabledocumento', 'uses' => 'reportes\reportealimentosController@reportealimentosresponsabledocumento']);

Route::get('reportealimentostabladefiniciones/{proyecto_id}/{agente_nombre}/{reportealimentos_id}', ['as' => 'reportealimentos.reportealimentostabladefiniciones', 'uses' => 'reportes\reportealimentosController@reportealimentostabladefiniciones']);

Route::get('reportealimentosdefinicioneliminar/{id}', ['as' => 'reportealimentos.reportealimentosdefinicioneliminar', 'uses' => 'reportes\reportealimentosController@reportealimentosdefinicioneliminar']);

Route::get('reportealimentostablarevisiones/{id}', ['as' => 'reportealimentos.reportealimentostablarevisiones', 'uses' => 'reportes\reportealimentosController@reportealimentostablarevisiones']);

Route::get('reportealimentostablarecomendaciones/{proyecto_id}/{reportealimentos_id}/{agente_nombre}', ['as' => 'reportealimentos.reportealimentostablarecomendaciones', 'uses' => 'reportes\reportealimentosController@reportealimentostablarecomendaciones']);

Route::get('reportePuntosAlimentosTablas/{proyecto_id}/{tabla}', ['as' => 'reportealimentos.reportePuntosAlimentosTablas', 'uses' => 'reportes\reportealimentosController@reportePuntosAlimentosTablas']);

Route::get('reportealimentostablaplanos/{proyecto_id}/{reportealimentos_id}/{agente_nombre}', ['as' => 'reportealimentos.reportealimentostablaplanos', 'uses' => 'reportes\reportealimentosController@reportealimentostablaplanos']);

Route::get('reportealimentostablaanexos/{proyecto_id}/{reportealimentos_id}/{agente_nombre}', ['as' => 'reportealimentos.reportealimentostablaanexos', 'uses' => 'reportes\reportealimentosController@reportealimentostablaanexos']);

Route::get('reportealimentostablainformeresultados/{proyecto_id}/{reportealimentos_id}/{agente_nombre}', ['as' => 'reportealimentos.reportealimentostablainformeresultados', 'uses' => 'reportes\reportealimentosController@reportealimentostablainformeresultados']);

Route::get('reporteAlimentosEliminarPuntos/{tabla}/{id}', ['as' => 'reportealimentos.reporteAlimentosEliminarPuntos', 'uses' => 'reportes\reportealimentosController@reporteAlimentosEliminarPuntos']);

Route::get('reportealimentosconcluirrevision/{reporte_id}', ['as' => 'reportealimentos.reportealimentosconcluirrevision', 'uses' => 'reportes\reportealimentosController@reportealimentosconcluirrevision'])->middleware('asignacionUser:REVISION');

Route::get('reportealimentosworddescargar/{proyecto_id}/{revision_id}/{ultima_revision}', ['as' => 'reportealimentos.reportealimentosworddescargar', 'uses' => 'reportes\reportealimentosWordController@reportealimentosworddescargar']);

Route::post('reportealimentosword', ['as' => 'reportealimentos.reportealimentosword', 'uses' => 'reportes\reportealimentosWordController@reportealimentosword']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////EXTERNO////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


Route::resource('externo', 'externo\externoController');

Route::get('externotabla', ['as' => 'externo.externotabla', 'uses' => 'externo\externoController@externotabla']);

Route::get('externoimprimirlistasignatarios/{proyecto_id}', ['as' => 'externo.externoimprimirlistasignatarios', 'uses' => 'externo\externoController@externoimprimirlistasignatarios']);

Route::get('externoimprimirlistaequipos/{proyecto_id}', ['as' => 'externo.externoimprimirlistaequipos', 'uses' => 'externo\externoController@externoimprimirlistaequipos']);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////PROGRAMA SEGUIMIENTO////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



Route::resource('seguimiento', 'programa\seguimientoController');
Route::resource('programa', 'programa\programaController');
Route::get('programatrabajoexcel/{proyecto_id}/{proyectoordentrabajo_id}', ['as' => 'programa.programatrabajoexcel', 'uses' => 'programa\programaController@programatrabajoexcel']);
Route::get('programatrabajoexcelcliente/{proyecto_id}/{proyectoordentrabajo_id}', ['as' => 'programa.programatrabajoexcelcliente', 'uses' => 'programa\programaController@programatrabajoexcelcliente']);

Route::get('listadoproyectos', ['as' => 'seguimiento.listadoproyectos', 'uses' => 'programa\seguimientoController@listadoproyectos']);
Route::get('detalletablaproyecto/{fase_id}/{proyecto_id}/{id}', ['as' => 'seguimiento.detalletablaproyecto', 'uses' => 'programa\seguimientoController@detalletablaproyecto']);
Route::get('detalleprograma/{proyecto_id}/{proyectoordentrabajo_id}/{proyectoordentrabajodatos_id}', ['as' => 'seguimiento.detalleprograma', 'uses' => 'programa\seguimientoController@detalleprograma']);
Route::get('detalleprogramagestion/{proyecto_id}/{proyectoordentrabajo_id}/{fase_id}', ['as' => 'seguimiento.detalleprogramagestion', 'uses' => 'programa\seguimientoController@detalleprogramagestion']);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////Plantilla////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Route::get('/mostrar-plantilla', function () {

// Catalogo de cliente
Route::get('/banco-imagenes', function () {return view('catalogos.plantilla.plantillas');})->name('banco-imagenes');
Route::get('/clientecatalogo', function () {return view('catalogos.cliente.catalogocliente');})->name('clientecatalogo');



// ======================== MODULOS DEL RECONOCIMIENTO DE HIGIENE INDUSTRIAL =========================================

//PROGRAMA DE TRABAJO
Route::resource('programa', 'HI\programaTrabajoController');
Route::get('tablaProgramaHI', ['as' => 'HI.programaTrabajo', 'uses' => 'HI\programaTrabajoController@tablaProgramaTrabajo']);


//EJECUCION
Route::resource('ejecucion', 'HI\ejecucionController');
Route::get('ejecucionHI', ['as' => 'HI.ejecucion', 'uses' => 'HI\ejecucionController@tablaEjecucion']);


//INFORMES
Route::resource('informes', 'HI\informesrecoController');

// ======================== MODULOS DEL RECONOCIMIENTO DE PSICOSOCIAL =========================================

//RECONOCIMIENTO
Route::resource('reconocimientoPsicosocial', 'PSICO\reconocimientoPsicoController');
Route::get('/estructuraPsico/{FOLIO}', ['as' => 'reconocimientoPsico.estructuraproyectos', 'uses' => 'PSICO\reconocimientoPsicoController@estructuraproyectos']);
Route::get('/folioproyectoPsico/{proyecto_folio}', ['as' => 'reconocimientoPsico.folioproyecto', 'uses' => 'PSICO\reconocimientoPsicoController@folioproyecto']);

Route::get('mostrarplanopsico/{archivo_opcion}/{reconocimientopsico_id}', ['as' => 'mostrarplanopsico', 'uses' => 'PSICO\reconocimientoPsicoController@mostrarplanopsico']);
Route::get('mostrarfotoinstalacionpsico/{archivo_opcion}/{reconocimientopsico_id}', ['as' => 'mostrarfotoinstalacionpsico', 'uses' => 'PSICO\reconocimientoPsicoController@mostrarfotoinstalacionpsico']);
Route::get('mostrarmapapsico/{archivo_opcion}/{reconocimientopsico_id}', ['as' => 'mostrarmapapsico', 'uses' => 'PSICO\reconocimientoPsicoController@mostrarmapapsico']);
Route::get('tablareconocimientopsico', ['as' => 'reconocimientoPsico.tablareconocimientopsico', 'uses' => 'PSICO\reconocimientoPsicoController@tablareconocimientopsico']);

Route::get('mostrartecnicodoc/{archivo_opcion}/{reconocimientopsico_id}', ['as' => 'mostrartecnicodoc', 'uses' => 'PSICO\reconocimientoPsicoController@mostrartecnicodoc']);
Route::get('mostrarcontratodoc/{archivo_opcion}/{reconocimientopsico_id}', ['as' => 'mostrarcontratodoc', 'uses' => 'PSICO\reconocimientoPsicoController@mostrarcontratodoc']);

//categoria
Route::get('recopsicocategoriatabla/{reconocimientopsico_id}', ['as' => 'recopsicocategoria.recopsicocategoriatabla', 'uses' => 'PSICO\recopsicocategoriaController@recopsicocategoriatabla']);
Route::resource('recopsicocategoria', 'PSICO\recopsicocategoriaController');
//area
Route::resource('recopsicoarea', 'recsensorial\recsensorialareaController');
Route::get('recopsicoareatabla/{reconocimientopsico_id}', ['as' => 'recsensorialarea.recsensorialareatabla', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareatabla']);
//areacategorias
Route::get('recopsicoareacategorias/{reconocimientopsico_id}', ['as' => 'recsensorialarea.recsensorialareacategorias', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareacategorias']);
Route::get('recopsicoareacategoriaselegidas/{area_id}', ['as' => 'recsensorialarea.recsensorialareacategoriaselegidas', 'uses' => 'recsensorial\recsensorialareaController@recsensorialareacategoriaselegidas']);

Route::resource('recopsiconormativa', 'PSICO\recopsiconormativaController');
Route::get('/datosnormativa/{reconocimientopsico_id}', ['as' => 'recopsiconormativa.recopsiconormativadatos', 'uses' => 'PSICO\recopsiconormativaController@recopsiconormativadatos']);
Route::get('recopsicotrabajadorescargados/{reconocimientopsico_id}', ['as' => 'recopsicotrabajadores.recopsicotrabajadoresCargadosTabla', 'uses' => 'PSICO\recopsiconormativaController@recopsicotrabajadoresCargadosTabla']);


//==============PROGRAMA DE TRABAJO
Route::resource('programaPsicosocial', 'PSICO\programaTrabajoPsicoController');

Route::resource('proyectotrabajadores', 'PSICO\proyectotrabajadoresController');

Route::get('tablaProgramaPsico', ['as' => 'PSICO.programaTrabajoPsico', 'uses' => 'PSICO\programaTrabajoPsicoController@tablaProgramaTrabajoPsico']);

Route::get('proyectotrabajadoreslista/{RECPSICO_ID}/{proyecto_id}', ['as' => 'proyectotrabajadores.proyectotrabajadoreslista', 'uses' => 'PSICO\proyectotrabajadoresController@proyectotrabajadoreslista']);

Route::get('proyectotrabajadoresadicionales', ['as' => 'proyectotrabajadores.proyectotrabajadoresadicionales', 'uses' => 'PSICO\proyectotrabajadoresController@proyectotrabajadoresadicionales']);

Route::get('trabajadoresProgramaPsico/{proyecto_id}/{RECPSICO_ID}', ['as' => 'programaTrabajoPsico.trabajadoresProgramaPsico', 'uses' => 'PSICO\programaTrabajoPsicoController@trabajadoresProgramaPsico']);

Route::get('proyectosignatariosinventarioPsico/{proyecto_id}', ['as' => 'proyectosignatarios.proyectosignatariosinventarioPsico', 'uses' => 'proyecto\proyectosignatarioController@proyectosignatariosinventarioPsico']);

Route::get('proyectoequiposinventarioPsico/{proyecto_id}', ['as' => 'proyectoequipos.proyectoequiposinventarioPsico', 'uses' => 'proyecto\proyectoequipoController@proyectoequiposinventarioPsico']);

Route::get('proyectovehiculosinventarioPsico/{proyecto_id}', ['as' => 'proyectovehiculo.proyectovehiculosinventarioPsico', 'uses' => 'proyecto\proyectoVehiculoController@proyectovehiculosinventarioPsico']);

//================EJECUCION
Route::resource('ejecucionPsicosocial', 'PSICO\ejecucionPsicoController');

Route::get('ejecucionPsicoTabla', ['as' => 'PSICO.ejecucionPsico', 'uses' => 'PSICO\ejecucionPsicoController@tablaEjecucion']);

Route::get('trabajadoresOnlineEjecucionPsico/{proyecto_id}', ['as' => 'PSICO.trabajadoresOnline', 'uses' => 'PSICO\ejecucionPsicoController@tablaTrabajadoresOnline']);

Route::get('trabajadoresPresencialEjecucionPsico/{proyecto_id}', ['as' => 'PSICO.trabajadoresPresencial', 'uses' => 'PSICO\ejecucionPsicoController@tablaTrabajadoresPresencial']);

Route::get('ejecuciontrabajadoresnombres', ['as' => 'ejecucionpsico.trabajadoresNombres', 'uses' => 'PSICO\ejecucionPsicoController@trabajadoresNombres']);

Route::put('actualizarFechasOnline', ['as' => 'PSICO.actualizarFechasOnline', 'uses' => 'PSICO\ejecucionPsicoController@actualizarFechasOnline']);

Route::put('guardarCambiosTrabajador', ['as' => 'PSICO.guardarCambiosTrabajador', 'uses' => 'PSICO\ejecucionPsicoController@guardarCambiosTrabajador']);




//================INFORMES
Route::resource('informesPsicosocial', 'PSICO\informesrecoPsicoController');
Route::resource('reportenom0353', 'reportes\reportenom0353Controller');
// =================GUIAS 

// Route::get('/Guia/{id}/{guia1}/{guia2}/{guia3}', function () { return view('catalogos.psico.guias.guias');})->name('Guia');
Route::get('/Guia/{guia1}/{guia2}/{guia3}/{guia5}/{status}/{fechalimite}/{id}', function ($guia1, $guia2, $guia3, $guia5, $status, $fechalimite, $id) {
    try {

        // Desencriptamos las guías
        $decryptedGuia1 = Crypt::decrypt($guia1);
        $decryptedGuia2 = Crypt::decrypt($guia2);
        $decryptedGuia3 = Crypt::decrypt($guia3);
        $decryptedGuia5 = Crypt::decrypt($guia5);

        $decryptedstatus = Crypt::decrypt($status);
        $decryptedfechalimite = Crypt::decrypt($fechalimite);

        $id = Crypt::decrypt($id);

        // Enviamos los datos de las guias ya desencriptados para obtenerlas en nuestra vista
        return view('catalogos.psico.guias.guias', [
            'guia1' => $decryptedGuia1,
            'guia2' => $decryptedGuia2,
            'guia3' => $decryptedGuia3,
            'guia5' => $decryptedGuia5,
            'status' => $decryptedstatus,
            'fechalimite' => $decryptedfechalimite,
            'id' => $id,
        ]);
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        //Cancelamos el acceso a la ruta
        abort(403, "Acceso denegado.");
    }
})->name('Guia');

Route::post('/consultarRespuestasGuardadas', 'PSICO\guiasController@consultarRespuestasGuardadas');
Route::post('/consultarRespuestasGuiaV', 'PSICO\guiasController@consultarRespuestasGuiaV');
Route::post('/obtenerExplicaciones', 'PSICO\guiasController@obtenerExplicaciones');
Route::post('/consultarDatosTrabajador', 'PSICO\guiasController@consultarDatosTrabajador');
Route::post('/guardarFotoRecpsico', 'PSICO\guiasController@guardarFotoRecpsico');
Route::get('envioGuia/{tipo}/{idPersonal}/{idRecsensorial}', ['as' => 'PSICO.envioGuia', 'uses' => 'PSICO\ejecucionPsicoController@envioGuia']);

Route::resource('guardarGuiasPsico', 'PSICO\guiasController');

//====================================> BIBLIOTECA (CENTRO DE INFORMACION) <=================================>
Route::resource('biblioteca', 'biblioteca\bibliotecaController');
Route::get('obtenerInfoBliblioteca/{clasificacion}/{titulo}', ['as' => 'biblioteca.listaBiblioteca', 'uses' => 'biblioteca\bibliotecaController@listaBiblioteca']);
Route::get('listaBibliotecaText/{clasificacion}/{titulo}', ['as' => 'biblioteca.listaBiblioteca', 'uses' => 'biblioteca\bibliotecaController@listaBibliotecaText']);

Route::get('bibliotecapdf/{documento_id}', ['as' => 'biblioteca.bibliotecapdf', 'uses' => 'biblioteca\bibliotecaController@bibliotecapdf']);

Route::get('consultaLibro/{id}', ['as' => 'biblioteca.consultaLibro', 'uses' => 'biblioteca\bibliotecaController@consultaLibro']);

Route::get('eliminarLibro/{id}', ['as' => 'biblioteca.eliminarLibro', 'uses' => 'biblioteca\bibliotecaController@eliminarLibro']);

//CATÁLOGOS
Route::resource('recpsicocatalogos', 'PSICO\recpsicocatalogosController');
Route::get('recpsicocatalogosguia/{num_catalogo}', ['as' => 'PSICO.recpsicocatalogos', 'uses' => 'PSICO\recpsicocatalogosController@tablaCatalogoGuia']);
Route::resource('recpsicocatalogosrec', 'PSICO\recpsicocatalogosrecController');
Route::get('recpsicocatalogosinformes/{num_catalogo}', ['as' => 'PSICO.recpsicocatalogosrec', 'uses' => 'PSICO\recpsicocatalogosrecController@tablaCatalogoRec']);


Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Application cache cleared';
});
