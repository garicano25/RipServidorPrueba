
//variables globales
var proyecto_id = 0;
var recsensorial_id = 0;
var proyecto_perfil = 0;
var recsensorial_alcancefisico = 0;
var recsensorial_alcancequimico = 0;
var recsensorial_errorvalidacion = 0;
var proyecto_bloqueado = 0;

// Data tables
var datatable_proyectos = null;
var datatable_usuarios = null;
var datatable_proyectosInternos = null;
var datatable_proyectoordenservicios = null;

// tablas principales
var datatable_proveedores = 0;
var datatable_signatarios_activo = 0;
var datatable_signatarios = null;
var datatable_equipos_activo = 0;
var datatable_equipos = null;

// Lista de proveedores del sistema
var proveedores_asignados = 0;
var proveedores_lista = '';
// var proveedor_alcances = '';
var proyectoInterno = 0;

// Modulo evidencia
var evidencia_agente_id = 0;
var evidencia_agente = '';

// Modulo reportes
Menureportes_contador = 0;



//===============================================================


// Load PAGINA
$(document).ready(function () {
	oculta_menustab_principal();
	proyectosTotales();
	tabla_proyecto();
	tabla_proyectoInternos()


	// Inicializar campo datepicker FECHA ENTREGA PROYECTO
	$('#proyecto_fechaentrega').datepicker({
		format: 'yyyy-mm-dd', //'dd-mm-yyyy'
		weekStart: 1, //dia que inicia la semana, 1 = Lunes
		// startDate: new Date('11/17/2020'), // deshabilitar dias anteriores con fecha
		// startDate: '-3d', // deshabilitar dias anteriores del dia actual
		// endDate: '+3d', //deshabilitar dias despues del dia actual
		calendarWeeks: true,
		autoclose: false,
		todayHighlight: true, //Dia de hoy marcado en el calendario
		toggleActive: true,
		// setDate: "2020/11/25", //Fecha marcada en el caledario
		forceParse: false //mantiene la fecha del input si no se selecciona otra
	});

	// inicializar campo FOTO plano instalacion
	$('#inputevidenciafotofisicos').dropify({
		messages: {
			'default': 'Arrastre la imagen aquí o haga click',
			'replace': 'Arrastre la imagen o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error: {
			'fileSize': 'Demasiado grande ({{ value }} max).',
			'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
			'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
			'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
			'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
			'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
		}
	});

	$('[data-toggle="tooltip"]').tooltip();
	// $('#div_boton_guardar_equiposlista').attr('data-original-title','Debe autorizar para activar opción de guardar'); //cambir texto del title tooltip

	setTimeout(function () {
		consulta_listaproveedores();
	}, 1000);

	$('#tab_proyectos_cliente').click();

});



// navegar menu Tab principal
$('.nav-link').click(function () {
	switch (this.id) {
		case "tab_menu1":
			$('#tab_proyectos_cliente').click();
			$('#tab_menu1').css('display', 'block');
			$('#tab_1').css('display', 'block');
			$('#tab_menu2').css('display', 'none');
			$('#tab_2').css('display', 'none');
			$('#tab_menu3').css('display', 'none');
			$('#tab_3').css('display', 'none');
			$('#tab_menu4').css('display', 'none');
			$('#tab_4').css('display', 'none');
			break;
		case "tab_menu2":
			$('#tab_1').css('display', 'none');
			$('#tab_menu2').css('display', 'block');
			$('#tab_2').css('display', 'block');
			$('#tab_3').css('display', 'none');
			$('#tab_4').css('display', 'block');
			$('#USUARIO_ID').selectize();

			break;
		case "tab_menu3":
			$('#tab_1').css('display', 'none');
			$('#tab_2').css('display', 'none');
			$('#tab_menu3').css('display', 'block');
			$('#tab_3').css('display', 'block');
			$('#tab_4').css('display', 'none');

			consulta_menuparametros_evidencias(proyecto_id);
			$('#tabmenu_evidencia_1').click();
			$('#tab_evidencia_1').click();
			break;
		case "tab_menu4":
			$('#tab_1').css('display', 'none');
			$('#tab_2').css('display', 'none');
			$('#tab_3').css('display', 'none');
			$('#tab_menu4').css('display', 'block');
			$('#tab_4').css('display', 'block');

			if (Menureportes_contador == 0) {
				consulta_menuparametros_reportes(proyecto_id);
				Menureportes_contador = 1;
			}
			else {
				mostrar_reporte('');
				$('#select_tiporeportes').val('');
			}
			break;
		default:
			// return true;
			break;
	}
});


// navegar menu Step-Forms
$('.multisteps-form__progress-btn').click(function () {
	switch (this.id) {
		case "steps_menu_tab2":
			// consultar proveedores
			if (datatable_proveedores == 0) {
				consulta_proveedores(proyecto_id, recsensorial_id, recsensorial_alcancefisico, recsensorial_alcancequimico);
				datatable_proveedores = 1;
			}

			break;
		case "steps_menu_tab3":
			// consultar signatarios
			if (datatable_signatarios_activo == 0) {
				tabla_signatarios(proyecto_id);
				datatable_signatarios_activo = 1;
			}
			break;
		case "steps_menu_tab4":
			// consultar equipos
			if (datatable_equipos_activo == 0) {
				tabla_equipos(proyecto_id);
				datatable_equipos_activo = 1;
			}
			break;
		case "steps_menu_tab5":
			//Seccion reportes
			$("#reportetab_menu1").click();
			break;
		default:
			break;
	}
});


function oculta_menustab_principal() {
	$("#tab_menu2").css('display', 'none');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
}


function muestra_menustab_principal() {
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu4").css('display', 'block');
	//Se comentaron ya que esos modulos se cambiaron de lugar pasaron a Ejecución y Informes
}


function oculta_stepformproyecto() {
	$("#steps_menu_tab1").click();
	$("#steps_menu_tab2").css('display', 'none');
	$("#steps_menu_tab3").css('display', 'none');
	$("#steps_menu_tab4").css('display', 'none');
	$("#steps_menu_tab5").css('display', 'none');
}


function mostrar_stepformproyecto() {
	$("#steps_menu_tab1").click();
	// $("#steps_menu_tab2").css('display', 'block');
	// $("#steps_menu_tab3").css('display', 'block');
	// $("#steps_menu_tab4").css('display', 'block');
	// $("#steps_menu_tab5").css('display', 'block');
	// Se comentaron ya que estas partes pasaron a programa de trabajo
}


function inicializar_datatables() {
	datatable_proveedores = 0;
	datatable_signatarios_activo = 0;
	datatable_equipos_activo = 0;
}


//===============================================================


function tabla_proyecto() {
	try {
		var ruta = "/proyectotabla";

		if (datatable_proyectos != null) {
			datatable_proyectos.clear().draw();
			datatable_proyectos.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_proyectos = $('#tabla_proyectos').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);

						console.log('error en datatable_proyectos');
						if (numeroejecucion <= 1) {
							tabla_proyecto();
							numeroejecucion += 1;
						}
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "proyecto_folio",
						"defaultContent": "-"
					},
					{
						"data": "proyecto_clienterazonsocial",
						"defaultContent": "-"
					},
					{
						"data": "instalacion_y_direccion",
						"defaultContent": "-"
					},
					{
						"data": "inicio_y_fin",
						"defaultContent": "-"
					},
					{
						"data": "servicios",
						"defaultContent": "-"
					},
					{
						"className": 'mostrar',
						"orderable": false,
						"data": 'boton_mostrar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": true,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}

		// Tooltip en DataTable
		datatable_proyectos.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_proyecto();
	}
}



function tabla_proyectoInternos() {
	try {
		var ruta = "/proyectotablaInternos";

		if (datatable_proyectosInternos != null) {
			datatable_proyectosInternos.clear().draw();
			datatable_proyectosInternos.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_proyectosInternos = $('#tabla_proyectos_internos').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);

						console.log('error en datatable_proyectos');
						if (numeroejecucion <= 1) {
							tabla_proyectoInternos();
							numeroejecucion += 1;
						}
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "proyecto_folio",
						"defaultContent": "-"
					},
					{
						"data": "proyecto_clienterazonsocial",
						"defaultContent": "-"
					},
					{
						"data": "instalacion_y_direccion",
						"defaultContent": "-"
					},
					{
						"data": "inicio_y_fin",
						"defaultContent": "-"
					},
					{
						"data": "servicios",
						"defaultContent": "-"
					},
					{
						"className": 'mostrar',
						"orderable": false,
						"data": 'boton_mostrar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": true,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}

		// Tooltip en DataTable
		datatable_proyectosInternos.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_proyectoInternos();
	}
}


function proyectosTotales() {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectosTotales",
		data: {},
		cache: false,
		success: function (dato) {

			$('#NumProyectos').text(dato.NUM_PROYECTO)
			$('#NumProyectosInternos').text(dato.NUM_PROYECTO_INTERNOS)


		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


///Obtener clientes
function select_cliente(cliente_id) {
	// activar campo
	$('#cliente_id').prop('disabled', false);
	$('#cliente_id').html('');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoselectcliente/" + cliente_id,
		data: {},
		cache: false,
		success: function (dato) {
			$('#cliente_id').html(dato.opciones);
			$('#cliente_id').selectize(); //Activar campo tipo [Select-Search]

		}, beforeSend: function () {

			$('#cliente_id').html('<option selected>Consultando clientes...</option>');

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}

function consultar_cliente(cliente_id) {
	html = '';

	$('.ORGA').prop('disabled', true);
	$(`.NIVEL1`).css('display', 'none')
	$(`.NIVEL2`).css('display', 'none')
	$(`.NIVEL3`).css('display', 'none')
	$(`.NIVEL4`).css('display', 'none')
	$(`.NIVEL5`).css('display', 'none')
	$(`#titleOrganizacion`).css('display', 'none')


	if (cliente_id) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectocliente/" + cliente_id,
			data: {},
			cache: false,
			success: function (dato) {

				// Llenar algunos campos con datos del cliente
				$('#proyecto_clienterazonsocial').val(dato.data[0].RAZON_SOCIAL);
				$('#proyecto_clientenombrecomercial').val(dato.data[0].NOMBRE_COMERCIAL);
				$('#proyecto_clienterfc').val(dato.data[0].RFC);
				$('#proyecto_clientegiroempresa').val(dato.data[0].GIRO_COMERCIAL);


				//Asignamos valor a las etiquetas y desabilitamos la ID de las etiquetas
				$.each(dato.etiquetas, function (index, etiqueta) {

					$(`#ID_ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.ID_ETIQUETA);
					$(`#ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.NOMBRE_ETIQUETA);
					$(`#ID_ETIQUETA${etiqueta.NIVEL}`).prop('disabled', false);
					$(`#NIVEL${etiqueta.NIVEL}`).prop('disabled', false);

				});


				//Cargamos las opciones de las estructuras
				$.each(dato.estructura, function (key, options) {

					$(`#OPCIONES${key + 1}_ID`).html(options)
					$(`#OPCIONES${key + 1}_ID`).prop('disabled', false);
					$(`.NIVEL${key + 1}`).css('display', 'block')

				});

				//Mostramos el titulo de la estructura si esque existe alguna
				if (dato.estructura.length != 0 && dato.etiquetas.length != 0) {
					$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
					$('#titleOrganizacion').html('Estructura organizacional');
					$('#TIENE_ESTRUCTURA').val(1)

				} else {
					$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
					$('#titleOrganizacion').html('Cliente seleccionado sin estructura organizacional');
					$('#TIENE_ESTRUCTURA').val(0)

				}


				$('#labelRazonSocial').html('Razon social')


			}, beforeSend: function () {

				$('#labelRazonSocial').html('<i class="fa fa-spin fa-spinner"></i>Razon Social')

			},
			error: function (dato) {

				// vaciar algunos campos con datos del reconocimiento
				$('#proyecto_clienterazonsocial').val('');
				$('#proyecto_clienterfc').val('');
				$('#proyecto_clienteinstalacion').val('');
				$('#proyecto_clientedireccionservicio').val('');



				return false;
			}
		});//Fin ajax
	} else {


		// vaciar algunos campos con datos del reconocimiento
		$('#proyecto_clienterazonsocial').val('');
		$('#proyecto_clienterfc').val('');
		$('#proyecto_clienteinstalacion').val('');
		$('#proyecto_clientedireccionservicio').val('');

	}
}

function consultarIdServicio(valor) {

	if (valor == 1) {

		return 'labelServicio1'

	} else if (valor == 2) {

		return 'labelServicio2'

	} else if (valor == 3) {

		return 'labelServicio3'
	}

}

//Obtener la formalizaciones del servicio
function cambiarSelectContrato(valor) {

	//limpiamos los elementos de organizacion
	$('.ORGA').prop('disabled', true);
	$(`.NIVEL1`).css('display', 'none')
	$(`.NIVEL2`).css('display', 'none')
	$(`.NIVEL3`).css('display', 'none')
	$(`.NIVEL4`).css('display', 'none')
	$(`.NIVEL5`).css('display', 'none')
	$(`#titleOrganizacion`).css('display', 'none')

	//Rellenamos el select que muestra los servicios disponibles
	select_contrato(0, valor, consultarIdServicio(valor))

}


//Obtener contratos
function select_contrato(contrato_id, tipo, radio) {

	// activar campo
	$('#contrato_id').prop('disabled', false);
	$('#contrato_id').html('');

	if (tipo == 1) {
		texto = 'Contrato'
	} else if (tipo == 2) {
		texto = 'O.S / O.C'
	} else if (tipo == 3) {
		texto = 'Cotización aceptada'
	}

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoselectcontrato/" + contrato_id + "/" + tipo,
		data: {},
		cache: false,
		success: function (dato) {

			if ($("#contrato_id")[0].selectize) {
				var selectizeInstance = $('#contrato_id')[0].selectize;


				selectizeInstance.clear();
				selectizeInstance.clearOptions();
				selectizeInstance.enable();

				// Parsear el HTML de las opciones
				var $options = $(dato.opciones);

				// Iterar sobre cada opción y agregarla a Selectize
				$options.each(function () {
					var $option = $(this);
					var value = $option.attr('value');
					var text = $option.text();
					selectizeInstance.addOption({ value: value, text: text });
				});

				// Refrescar las opciones para que se muestren en el dropdown
				selectizeInstance.refreshOptions(false);

			} else {
				$('#contrato_id').prop('disabled', false);
				$('#contrato_id').html(dato.opciones);
				$('#contrato_id').selectize();

			}

			$('#' + radio).html(`${texto}`)

		}, beforeSend: function () {

			if ($("#contrato_id")[0].selectize) {
				var selectizeInstance = $('#contrato_id')[0].selectize;
				selectizeInstance.disable();
			} else {
				$('#contrato_id').prop('disabled', true);
			}

			$('#contrato_id').html('<option selected>Consultando información...</option>');
			$('#' + radio).html(`<i class="fa fa-spin fa-spinner"></i>${texto}`)

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}
var contrato = ''
function consultar_contrato(contrato_id) {
	if (contrato_id) {
		$('.ORGA').prop('disabled', true);
		$(`.NIVEL1`).css('display', 'none')
		$(`.NIVEL2`).css('display', 'none')
		$(`.NIVEL3`).css('display', 'none')
		$(`.NIVEL4`).css('display', 'none')
		$(`.NIVEL5`).css('display', 'none')
		$(`#titleOrganizacion`).css('display', 'none')

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectocontrato/" + contrato_id,
			data: {},
			cache: false,
			success: function (dato) {
				contrato = dato
				// Llenar algunos campos con datos del reconocimiento
				$('#proyecto_clienterazonsocial').val(dato.data[0].RAZON_SOCIAL);
				$('#proyecto_clientenombrecomercial').val(dato.data[0].NOMBRE_COMERCIAL);
				$('#proyecto_clienterfc').val(dato.data[0].RFC);
				$('#proyecto_clientegiroempresa').val(dato.data[0].GIRO_COMERCIAL);
				$('#proyecto_clientepersonadirigido').val(dato.data[0].NOMBRE_CONTACTO);
				$('#proyecto_clientepersonacontacto').val(dato.data[0].NOMBRE_CONTACTO);
				$('#proyecto_clientetelefonocontacto').val(dato.data[0].TELEFONO_CONTACTO);
				$('#proyecto_clientecelularcontacto').val(dato.data[0].CELULAR_CONTACTO);
				$('#proyecto_clientecorreocontacto').val(dato.data[0].CORREO_CONTACTO);
				$('#proyecto_clienteobjetivoservicio').val(dato.data[0].DESCRIPCION_CONTRATO);

				//Asignamos valor a las etiquetas y desabilitamos la ID de las etiquetas
				$.each(dato.etiquetas, function (index, etiqueta) {

					$(`#ID_ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.ID_ETIQUETA);
					$(`#ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.NOMBRE_ETIQUETA);
					$(`#ID_ETIQUETA${etiqueta.NIVEL}`).prop('disabled', false);
					$(`#NIVEL${etiqueta.NIVEL}`).prop('disabled', false);

				});

				//Cargamos las opciones de las estructuras
				$.each(dato.estructura, function (key, options) {

					$(`#OPCIONES${key + 1}_ID`).html(options)
					$(`#OPCIONES${key + 1}_ID`).prop('disabled', false);
					$(`.NIVEL${key + 1}`).css('display', 'block')

				});

				//Mostramos el titulo de la estructura si esque existe alguna
				if (dato.estructura.length != 0 && dato.etiquetas.length != 0) {
					$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
					$('#titleOrganizacion').html('Estructura organizacional');
					$('#TIENE_ESTRUCTURA').val(1)

				} else {
					$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
					$('#titleOrganizacion').html('Cliente seleccionado sin estructura organizacional');
					$('#TIENE_ESTRUCTURA').val(0)

				}



				$('#laberContrato').html('Contratos (disponibles)')


			}, beforeSend: function () {

				$('#laberContrato').html('<i class="fa fa-spin fa-spinner"></i>Contratos (disponibles)')

			},
			error: function (dato) {

				// vaciar algunos campos con datos del reconocimiento
				$('#proyecto_clienterazonsocial').val('');
				$('#proyecto_clienterfc').val('');
				$('#proyecto_clienteinstalacion').val('');
				$('#proyecto_clientedireccionservicio').val('');



				return false;
			}
		});//Fin ajax
	} else {


		// vaciar algunos campos con datos del reconocimiento
		$('#proyecto_clienterazonsocial').val('');
		$('#proyecto_clienterfc').val('');
		$('#proyecto_clienteinstalacion').val('');
		$('#proyecto_clientedireccionservicio').val('');

	}
}

//Obtener reconocimientos disponibles
function select_recsensorial(recsensorial_id) {
	// activar campo
	$('#recsensorial_id').prop('disabled', false);
	$('#recsensorial_id').html('');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoselectrecsensorial/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			$('#recsensorial_id').html(dato.opciones);
			$('#recsensorial_id').selectize(); //Activar campo tipo [Select-Search]

			var selectizeControl = $('#recsensorial_id').selectize()[0].selectize;
			selectizeControl.disable();

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


function consulta_recsensorial(recsensorial_id, recsensorial_opcion) {
	if (recsensorial_id) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectorecsensorial/" + recsensorial_id,
			data: {},
			cache: false,
			success: function (dato) {
				// campos hidden
				// recsensorial_errorvalidacion = parseInt(dato.error);
				//lo dejamos en 0 ya que antes se valiva el reconocimiento y despues se asignaba a el informe pero ahora ya no porque para hacer un reconocimiento tiene que estar relacionado a un proyecto
				recsensorial_errorvalidacion = 0;




				// tablas resumenes
				$('#folio_fisicos').html(dato.recsensorial.recsensorial_foliofisico);
				$('#folio_quimicos').html(dato.recsensorial.recsensorial_folioquimico);
				$('#folio_quimicos_cliente').html(dato.recsensorial.recsensorial_folioquimico);

				$('#tabla_resumen_fisicos tbody').html(dato.fisicos_resumen);
				$('#tabla_resumen_quimicos tbody').html(dato.quimicos_resumen);
				$('#tabla_resumen_quimicos_clientes tbody').html(dato.quimicos_resumen_cliente);

			},
			beforeSend: function () {
				$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
				$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
				$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function (dato) {
				recsensorial_errorvalidacion = 0;

				// tablas resumenes
				$('#folio_fisicos').html("");
				$('#folio_quimicos').html("");
				$('#folio_quimicos_cliente').html("");
				$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');
				$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');
				$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');

				return false;
			}
		});//Fin ajax
	}
	else {
		// campos hidden
		recsensorial_errorvalidacion = 0;


		$('#folio_fisicos').html("");
		$('#folio_quimicos').html("");
		$('#folio_quimicos_cliente').html("");

		$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">Sin datos</td></tr>');
		$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">Sin datos</td></tr>');
		$('#tabla_proveedores_fisicos tbody').html('<tr><td colspan="7">&nbsp;</td></tr>');
		$('#tabla_proveedores_quimicos tbody').html('<tr><td colspan="7">&nbsp;</td></tr>');
	}
}


function calculafechafin() {
	if ($('#proyecto_fechainicio').val() != "" && $('#proyecto_totaldias').val() > 0) {
		var fechainput = $('#proyecto_fechainicio').val().split("-");
		var dias = parseInt($('#proyecto_totaldias').val());

		var fecha = new Date(fechainput[0] + "/" + fechainput[1] + "/" + fechainput[2]);
		fecha.setDate(fecha.getDate() + (dias - 1)); //Sumar dias

		var day = fecha.getDate();
		var month = fecha.getMonth() + 1;
		var year = fecha.getFullYear();

		if (day < 10) {
			day = "0" + day;
		}

		if (month < 10) {
			month = "0" + month;
		}

		// Fecha fin calculado
		$('#proyecto_fechafin').val(year + "-" + month + "-" + day);

		// Obtenenemos el dato de la fecha de entrega actual
		var fechaentrega_actual = $('#proyecto_fechaentrega').val();
		var fechafin_actual = $('#proyecto_fechafin').val();

		setTimeout(function () {
			var fe_fin = fechafin_actual.split("-");
			var fe_entrega = fechaentrega_actual.split("-");

			// Crear nuevo calendario
			$('#proyecto_fechaentrega').datepicker('destroy');
			$('#proyecto_fechaentrega').datepicker({
				format: 'yyyy-mm-dd', //'dd-mm-yyyy'
				weekStart: 1, //dia que inicia la semana, 1 = Lunes
				startDate: new Date(month + '/' + day + '/' + year), // deshabilitar dias anteriores con fecha
				// startDate: '-3d', // deshabilitar dias antes del dia actual
				// endDate: '+3d', //deshabilitar dias despues del dia actual
				calendarWeeks: true,
				autoclose: false,
				// todayHighlight: true, //Dia de hoy marcado en el calendario
				toggleActive: true,
				setDate: year + '/' + month + '/' + day, //Fecha marcada en el caledario
				forceParse: false //mantiene la fecha del input si no se selecciona otra
			});

			// Modificar calendario de la fecha de entrega
			if (fechaentrega_actual) {
				if (parseInt(fe_entrega[0] + fe_entrega[1] + fe_entrega[2]) < parseInt(fe_fin[0] + fe_fin[1] + fe_fin[2])) {
					// Mover el calendario a la fecha nueva
					$('#proyecto_fechaentrega').datepicker('setDate', year + '-' + month + '-' + day);
					$('#proyecto_fechaentrega').val('');
				}
				else {
					// Mover el calendario a la fecha seleccionada
					$('#proyecto_fechaentrega').datepicker('setDate', fechaentrega_actual);
					$('#proyecto_fechaentrega').val(fechaentrega_actual);
				}
			}
			else {
				// Mover el calendario a la fecha nueva
				$('#proyecto_fechaentrega').datepicker('setDate', year + '-' + month + '-' + day);
				$('#proyecto_fechaentrega').val('');
			}
		}, 100);
	}
	else {
		$('#proyecto_fechafin').val('');
		$('#proyecto_fechaentrega').val('');

		$('#proyecto_fechaentrega').datepicker('destroy');
		$('#proyecto_fechaentrega').datepicker({
			format: 'yyyy-mm-dd', //'dd-mm-yyyy'
			weekStart: 1, //dia que inicia la semana, 1 = Lunes
			// startDate: new Date('11/17/2020'), // deshabilitar dias anteriores con fecha
			// startDate: '-1Y', // deshabilitar dias anteriores del dia actual
			// endDate: '+3d', //deshabilitar dias despues del dia actual
			calendarWeeks: true,
			autoclose: false,
			todayHighlight: true, //Dia de hoy marcado en el calendario
			toggleActive: true,
			setDate: new Date(), //Fecha marcada en el caledario
			forceParse: false //mantiene la fecha del input si no se selecciona otra
		});
	}
}


$("#boton_nuevo_proyecto").click(function () {
	// Alerta de Instrucciones
	$('.ORGA').prop('disabled', true);
	$(`.NIVEL1`).css('display', 'none')
	$(`.NIVEL2`).css('display', 'none')
	$(`.NIVEL3`).css('display', 'none')
	$(`.NIVEL4`).css('display', 'none')
	$(`.NIVEL5`).css('display', 'none')
	$(`#titleOrganizacion`).css('display', 'none')


	$('#proyecto_personaelabora').css('display', 'block').prop('disabled', false);
	$('#proyecto_contacto').css('display', 'block').prop('disabled', false);
	$('#PROYECTO_CONTACTO_SELECT').css('display', 'none').prop('disabled', true);
	$('#PERSONA_ELABORA_SELECT').css('display', 'none').prop('disabled', true);


	// swal({   
	// 	title: "Intrucciones para la creación de un nuevo proyecto",
	// 	text: "La creación de un proyecto consta de tres pasos: Primero la creación del proyecto para obtener un folio, Segundo la creación de un reconocimiento y por último la vinculación del Reconocimiento con el proyecto.",
	// 	type: "info",
	// 	showCancelButton: false,
	// 	confirmButtonColor: "#DD6B55",
	// 	confirmButtonText: "Crear proyecto!",
	// 	closeOnConfirm: false,
	// 	closeOnCancel: false
	// })
	//Regresamos los inputs y select a su estado ooriginal
	if ($("#contrato_id")[0].selectize) {
		$('#contrato_id')[0].selectize.clear();
		$('#contrato_id')[0].selectize.enable();
	} else {
		$('#contrato_id').val('');
		$('#contrato_id').prop('disabled', false);
	}
	$('#requiereContrato').val(1);

	$('#opciones_hi').fadeOut(1)
	$('#opciones_ergo').fadeOut(1)
	$('#opciones_psico').fadeOut(1)

	//Reiniciamos los radio a su estado original
	$('.servCliente').prop('disabled', false)
	$('.servCliente').each(function () {
		$(this).prop('checked', false)
	})

	if ($("#cliente_id")[0].selectize) {
		var selectizeInstance = $('#cliente_id')[0].selectize;

		selectizeInstance.clear();
		selectizeInstance.disable();
		selectizeInstance.$wrapper.fadeOut(1);

	} else {

		$('#cliente_id').val('');
		$('#cliente_id').prop('disabled', true);
		$('#cliente_id').fadeOut(1)

	} //Ocultamos el select de cliente cuando se crea por primera vez

	$('#proyecto_clienterazonsocial').css('display', 'block');


	// Borrar formulario
	$('#form_proyecto').each(function () {
		this.reset();
	});

	$('#proyectoInternoCheck').prop('disabled', false)
	$('#proyectoInternoCheck').prop('checked', false)
	$('#proyectoInterno').val(0)

	// campos hidden
	$('#proyecto_id').val(0);
	proyecto_id = 0;
	recsensorial_id = 0;
	recsensorial_alcancefisico = 0;
	recsensorial_alcancequimico = 0;
	recsensorial_errorvalidacion = 0;
	Menureportes_contador = 0;

	// inicializar tablas principales (Se quito por que todas la tablas se pasaron a programa de trabajo)
	// inicializar_datatables();

	// vaciar tabla ordenes de servicio (Se quito porque las ordenes de servicio van despues de haber guardado)
	// tabla_proyectoordenservicios(0);

	// Inicializa datatable_prorrogas
	if (datatable_prorrogas != null) {
		datatable_prorrogas.clear().draw();
	}

	// ocultar step forms
	oculta_stepformproyecto();

	//DIV NOMBRE DEL PROYECTO
	$('.div_proyecto_folio').html('FOLIO')
	$('.div_proyecto_instalacion').html('NOMBRE INSTALACIÓN')

	// oculta seccion datos proyecto
	$("#seccion_datosproyecto").css('display', 'block');
	$("#datosReconocimientos").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
	$("#encavezadovolumetria").css('display', 'none');
	$("#seccion_recsensorialresumenfisico").css('display', 'none');
	$("#seccion_recsensorialresumenquimico").css('display', 'none');


	$("#seccion_prorrogas").css('display', 'none');
	$("#seccion_ordenes_servicio").css('display', 'none');
	$("#seccion_asignacion_usuarios").css('display', 'none');


	// mostrar TAB
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu2").click();

	//Consultar los contratos
	select_contrato(0, 1, consultarIdServicio(1))

	// Consulta recsensoriales no asignados a proyectos (Se quito porque eso se hace despues de guardar el ocntrato)
	// select_recsensorial(0);

	// tablas resumenes
	$('#folio_fisicos').html("");
	$('#folio_quimicos').html("");
	$('#folio_quimicos_cliente').html("");
	$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>');
	$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>');
	$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;">No hay datos que mostrar</td></tr>');

});


// Selecciona Proyecto
$('#tabla_proyectos tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_proyectos.row(tr);

	$('#proyecto_personaelabora').css('display', 'block').prop('disabled', false);
	$('#proyecto_contacto').css('display', 'block').prop('disabled', false);
	$('#PROYECTO_CONTACTO_SELECT').css('display', 'none').prop('disabled', true);
	$('#PERSONA_ELABORA_SELECT').css('display', 'none').prop('disabled', true);
	$('#proyecto_ordenservicio').prop('disabled', false);


	// Borrar formulario
	$('#form_proyecto').each(function () {
		this.reset();
	});

	// campos hidden
	$('#proyecto_id').val(row.data().id);
	$('#proyectoInterno').val(row.data().proyectoInterno);

	proyecto_id = row.data().id;
	recsensorial_errorvalidacion = 0;
	Menureportes_contador = 0;

	//DIV NOMBRE DEL PROYECTO
	$('.div_proyecto_folio').html(row.data().proyecto_folio);
	$('.div_proyecto_instalacion').html(row.data().proyecto_clienteinstalacion);

	// inicializar tablas principales (Se quito por que todas la tablas de pasaron a progrma de trabajo)
	// inicializar_datatables();

	// mostrar menu tab
	muestra_menustab_principal();



	// Selecciona step form 1
	$("#tab_menu2").click();
	$("#steps_menu_tab1").click();

	// mostrar seccion datos proyecto
	$("#seccion_datosproyecto").css('display', 'block');
	$("#seccion_prorrogas").css('display', 'block');
	$("#seccion_recsensorialresumenfisico").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
	$("#seccion_recsensorialresumenquimico").css('display', 'block');
	$("#seccion_ordenes_servicio").css('display', 'block');
	$("#seccion_asignacion_usuarios").css('display', 'block');
	$("#datosReconocimientos").css({ 'display': 'flex', 'flex-wrap': 'wrap' });

	//SELECT CONTRATOS

	if (row.data().requiereContrato == 1) {

		$('.servCliente').prop('disabled', false)

		//Si requiere de un contrato seleccionamos el tipo de contrato o servicio
		$('#tipoServicioCliente' + row.data().tipoServicioCliente).prop('checked', true)
		$('#requiereContrato').val(1)
		$('#proyecto_clienterazonsocial').fadeIn(1)

		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.disable();
			selectizeInstance.$wrapper.fadeOut(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', true);
			$('#cliente_id').fadeOut(1)

		}


		if ($("#contrato_id")[0].selectize) {
			$("#contrato_id")[0].selectize.destroy();
		}
		$('#contrato_id').html('');

		if (row.data().contrato_id) {

			// Consulta recsensoriales no asignados a proyectos
			select_contrato(row.data().contrato_id, row.data().tipoServicioCliente, consultarIdServicio(row.data().tipoServicioCliente));

			// si ya se cerró el proyecto
			if (parseInt(row.data().proyecto_concluido) == 1) {
				// desactivar campo
				$('#contrato_id').prop('disabled', true);
			}
			else {
				$('#contrato_id').prop('disabled', false);
			}

		} else {
			select_contrato(0, 1, consultarIdServicio(1));
		}

	} else {


		$('#proyecto_clienterazonsocial').fadeOut(1)
		if ($("#contrato_id")[0].selectize) {

			$('#contrato_id')[0].selectize.disable();
			$('#contrato_id')[0].selectize.clear();
		} else {
			$('#contrato_id').prop('disabled', true);
			$('#contrato_id').val('');
		}

		$('#requiereContrato').val(0)

		$('.servCliente').prop('disabled', true)
		$('.servCliente').each(function () {
			$(this).prop('checked', false)
		})

		if ($("#cliente_id")[0].selectize) {
			$("#cliente_id")[0].selectize.destroy();
			$('#cliente_id').fadeIn(1);
		} else {
			$('#cliente_id').fadeIn(1);
		}

		$('#cliente_id').html('');
		if (row.data().cliente_id) {

			// Consulta recsensoriales no asignados a proyectos
			select_cliente(row.data().cliente_id);

			// si ya se cerró el proyecto
			if (parseInt(row.data().proyecto_concluido) == 1) {
				// desactivar campo
				$('#cliente_id').prop('disabled', true);
			}
			else {
				$('#cliente_id').prop('disabled', false);
			}

		} else {
			select_cliente(0);
		}

	}


	//Mostramos los servicios seleccionados del proyecto
	obtenerServicios(row.data().id)

	//Mostramos la estructura del proyecto
	obtenerEstructuraProyecto(row.data().id)

	// campo folio
	$('#proyecto_folio').val(row.data().proyecto_folio);
	$('#proyecto_fechacreacion').val(row.data().proyecto_fechacreacion);

	if (row.data().proyecto_razonsocial != null) {
		$('#proyecto_ordenservicio').val(row.data().proyecto_ordenservicio);
		$('#proyecto_cotizacion').val(row.data().proyecto_cotizacion);
		$('#proyecto_razonsocial').val(row.data().proyecto_razonsocial);
		$('#proyecto_rfc').val(row.data().proyecto_rfc);
		$('#proyecto_direccion').val(row.data().proyecto_direccion);
		$('#proyecto_contacto').val(row.data().proyecto_contacto);
		$('#proyecto_contactocorreo').val(row.data().proyecto_contactocorreo);
		$('#proyecto_contactotelefono').val(row.data().proyecto_contactotelefono);
		$('#proyecto_contactocelular').val(row.data().proyecto_contactocelular);
		$('#proyecto_ciudadpais').val(row.data().proyecto_ciudadpais);
		$('#proyecto_personaelabora').val(row.data().proyecto_personaelabora);
		$('#proyecto_personaaprueba').val(row.data().proyecto_personaaprueba);
		$('#proyecto_fechaelaboracion').val(row.data().proyecto_fechaelaboracion);
		$('#proyecto_clienterazonsocial').val(row.data().proyecto_clienterazonsocial);
		$('#proyecto_clientenombrecomercial').val(row.data().proyecto_clientenombrecomercial);
		$('#proyecto_clienterfc').val(row.data().proyecto_clienterfc);
		$('#proyecto_clientegiroempresa').val(row.data().proyecto_clientegiroempresa);
		$("#catregion_id").val(row.data().catregion_id);
		$("#catsubdireccion_id").val(row.data().catsubdireccion_id);
		$("#catgerencia_id").val(row.data().catgerencia_id);
		$("#catactivo_id").val(row.data().catactivo_id);
		$('#proyecto_clienteinstalacion').val(row.data().proyecto_clienteinstalacion);
		$('#proyecto_clientedireccionservicio').val(row.data().proyecto_clientedireccionservicio);
		$('#proyecto_clientepersonadirigido').val(row.data().proyecto_clientepersonadirigido);
		$('#proyecto_clientepersonacontacto').val(row.data().proyecto_clientepersonacontacto);
		$('#proyecto_clientetelefonocontacto').val(row.data().proyecto_clientetelefonocontacto);
		$('#proyecto_clientecelularcontacto').val(row.data().proyecto_clientecelularcontacto);
		$('#proyecto_clientecorreocontacto').val(row.data().proyecto_clientecorreocontacto);
		$('#proyecto_clienteobjetivoservicio').val(row.data().proyecto_clienteobjetivoservicio);
		$('#proyecto_clienteobservacion').val(row.data().proyecto_clienteobservacion);
		$('#proyecto_fechainicio').val(row.data().proyecto_fechainicio);
		$('#proyecto_fechafin').val(row.data().proyecto_fechafin);
		$('#proyecto_totaldias').val(row.data().proyecto_totaldias);
		$('#proyecto_fechaentrega').val(row.data().proyecto_fechaentrega);

		if (row.data().proyecto_fechainicio != "" && parseInt(row.data().proyecto_totaldias) > 0) {
			var fechainput = row.data().proyecto_fechainicio.split("-");
			var dias = parseInt(row.data().proyecto_totaldias);

			var fecha = new Date(fechainput[0] + "/" + fechainput[1] + "/" + fechainput[2]);
			fecha.setDate(fecha.getDate() + (dias - 1)); //Sumar dias

			var day = fecha.getDate();
			var month = fecha.getMonth() + 1;
			var year = fecha.getFullYear();

			$('#proyecto_fechaentrega').datepicker('destroy');
			$('#proyecto_fechaentrega').datepicker({
				format: 'yyyy-mm-dd', //'dd-mm-yyyy'
				weekStart: 1, //dia que inicia la semana, 1 = Lunes
				startDate: new Date(month + '/' + day + '/' + year), // deshabilitar dias anteriores con fecha
				// startDate: '-3d', // deshabilitar dias antes del dia actual
				// endDate: '+3d', //deshabilitar dias despues del dia actual
				calendarWeeks: true,
				autoclose: false,
				todayHighlight: true,
				toggleActive: true,
				forceParse: false, //mantiene la fecha del input si no se selecciona otra
				setDate: year + '-' + month + '-' + day
			});

			$('#proyecto_fechaentrega').val(row.data().proyecto_fechaentrega);
		}
	}

	//Validar solicitud de una Orden de Servicio
	if (parseInt(row.data().solicitudOS) == 0) {
		$('#solicitudOS').val(0);
		$('#solicitudOS').prop('checked', false);
		$('#boton_nueva_ordenservicio').prop('disabled', true)
		$('#boton_nueva_ordenservicioadicional').prop('disabled', true)

	}
	else {
		$('#solicitudOS').val(1);
		$('#solicitudOS').prop('checked', true);
		$('#boton_nueva_ordenservicio').prop('disabled', false)
		$('#boton_nueva_ordenservicioadicional').prop('disabled', false)
	}

	// Valida perfil
	if (parseInt(row.data().perfil) > 0) {
		proyecto_perfil = parseInt(row.data().perfil);
	}
	else {
		proyecto_perfil = 0;
	}

	// Consulta ordenes de servicio
	tabla_proyectoordenservicios(row.data().id);

	// Tabla prorrogas
	tabla_prorrogas(row.data().id);
	tabla_usuarios(row.data().id);


	// SELECT RECONOCIMIENTO SENSORIAL
	if ($("#recsensorial_id")[0].selectize) {
		$("#recsensorial_id")[0].selectize.destroy();
	}

	$('#recsensorial_id').html('');
	if (row.data().recsensorial_id) {
		// mostrar step forms
		mostrar_stepformproyecto();

		recsensorial_id = parseInt(row.data().recsensorial_id);
		recsensorial_alcancefisico = parseInt(row.data().recsensorial.recsensorial_alcancefisico);
		recsensorial_alcancequimico = parseInt(row.data().recsensorial.recsensorial_alcancequimico);


		// Consulta recsensoriales no asignados a proyectos
		select_recsensorial(row.data().recsensorial_id);

		// si ya se cerró el proyecto
		if (parseInt(row.data().proyecto_concluido) == 1) {
			// desactivar campo
			$('#recsensorial_id').prop('disabled', true);
		}
		else {
			$('#recsensorial_id').prop('disabled', false);
		}

		// mostrar datos recsensorial
		consulta_recsensorial(row.data().recsensorial_id, 1);
	}
	else {
		// Ocultar menus Step-Forms
		oculta_stepformproyecto();

		recsensorial_id = 0;
		recsensorial_alcancefisico = 0;
		recsensorial_alcancequimico = 0;

		$('#folio_fisicos').html("");
		$('#folio_quimicos').html("");
		$('#folio_quimicos_cliente').html("");
		$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="7" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');

		// Consulta recsensoriales no asignados a proyectos
		select_recsensorial(0);
	}


	// Consulta observaciones de los proveedores
	consulta_observacionesproveedores(row.data().id);


	// VERIFICA BLOQUEO DEL PROVEEDOR
	if (parseInt(row.data().proyecto_concluido) == 0) //desbloqueado
	{
		proyecto_bloqueado = 0;
		$("#boton_bloquear_proyecto").val(0);
		$("#boton_bloquear_proyecto").removeClass('btn-warning');
		$("#boton_bloquear_proyecto").addClass('btn-success');
		$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proyecto desbloqueado para edición');

		// Activar botones
		$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-save"></i>');
		$(".botonguardar_moduloproyecto").attr('disabled', false);
		// $(".botonnuevo_moduloproyecto").attr('disabled', false);        
	}
	else {
		proyecto_bloqueado = 1;
		$("#boton_bloquear_proyecto").val(1);
		$("#boton_bloquear_proyecto").removeClass('btn-success');
		$("#boton_bloquear_proyecto").addClass('btn-warning');
		$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proyecto bloqueado para edición');

		// Desactivar botones
		$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-ban"></i>');
		$(".botonguardar_moduloproyecto").attr('disabled', true);
		$(".botonnuevo_moduloproyecto").attr('disabled', true);
	}

	// Limpiar seccion modulo Reporte
	$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte</p>');

	//Cuando acceda a los datos generales del proyecto cargamos el cronograma de actividades
	mostrarActividadesCronograma()
});


// Selecciona Proyecto Interno
// Selecciona Proyecto
$('#tabla_proyectos_internos tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_proyectosInternos.row(tr);

	//Marcamos el proyecto interno
	$('#proyectoInternoCheck').prop('checked', true).prop('disabled', true);

	$('#proyecto_personaelabora').css('display', 'block').prop('disabled', false);
	$('#proyecto_contacto').css('display', 'block').prop('disabled', false);
	$('#PROYECTO_CONTACTO_SELECT').css('display', 'none').prop('disabled', true);
	$('#PERSONA_ELABORA_SELECT').css('display', 'none').prop('disabled', true);
	$('#proyecto_ordenservicio').prop('disabled', true);

	// Borrar formulario
	$('#form_proyecto').each(function () {
		this.reset();
	});

	// campos hidden
	$('#proyecto_id').val(row.data().id);
	$('#proyectoInterno').val(row.data().proyectoInterno);

	proyecto_id = row.data().id;
	recsensorial_errorvalidacion = 0;
	Menureportes_contador = 0;

	//DIV NOMBRE DEL PROYECTO
	$('.div_proyecto_folio').html(row.data().proyecto_folio);
	$('.div_proyecto_instalacion').html(row.data().proyecto_clienteinstalacion);

	// inicializar tablas principales
	inicializar_datatables();

	// mostrar menu tab
	muestra_menustab_principal();



	// Selecciona step form 1
	$("#tab_menu2").click();
	$("#steps_menu_tab1").click();

	// mostrar seccion datos proyecto
	$("#seccion_datosproyecto").css('display', 'block');
	$("#seccion_prorrogas").css('display', 'none');
	$("#seccion_recsensorialresumenfisico").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
	$("#seccion_recsensorialresumenquimico").css('display', 'block');
	$("#seccion_ordenes_servicio").css('display', 'none');
	$("#datosReconocimientos").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
	$("#seccion_asignacion_usuarios").css('display', 'block');

	//SELECT CONTRATOS

	if (row.data().requiereContrato == 1) {

		$('.servCliente').prop('disabled', false)

		//Si requiere de un contrato seleccionamos el tipo de contrato o servicio
		$('#tipoServicioCliente' + row.data().tipoServicioCliente).prop('checked', true)
		$('#requiereContrato').val(1)
		$('#proyecto_clienterazonsocial').fadeIn(1)

		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.disable();
			selectizeInstance.$wrapper.fadeOut(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', true);
			$('#cliente_id').fadeOut(1)

		}


		if ($("#contrato_id")[0].selectize) {
			$("#contrato_id")[0].selectize.destroy();
		}
		$('#contrato_id').html('');
		if (row.data().contrato_id) {

			// Consulta recsensoriales no asignados a proyectos
			select_contrato(row.data().contrato_id, row.data().tipoServicioCliente, consultarIdServicio(row.data().tipoServicioCliente));

			// si ya se cerró el proyecto
			if (parseInt(row.data().proyecto_concluido) == 1) {
				// desactivar campo
				$('#contrato_id').prop('disabled', true);
			}
			else {
				$('#contrato_id').prop('disabled', false);
			}

		} else {
			select_contrato(0, 1, consultarIdServicio(1));
		}

	} else {


		$('#proyecto_clienterazonsocial').fadeOut(1)
		if ($("#contrato_id")[0].selectize) {

			$('#contrato_id')[0].selectize.disable();
			$('#contrato_id')[0].selectize.clear();
		} else {
			$('#contrato_id').prop('disabled', true);
			$('#contrato_id').val('');
		}

		$('#requiereContrato').val(0)

		$('.servCliente').prop('disabled', true)
		$('.servCliente').each(function () {
			$(this).prop('checked', false)
		})


		if ($("#cliente_id")[0].selectize) {
			$("#cliente_id")[0].selectize.destroy();
			$('#cliente_id').fadeIn(1);
		} else {
			$('#cliente_id').fadeIn(1);
		}

		$('#cliente_id').html('');
		if (row.data().cliente_id) {

			// Consulta recsensoriales no asignados a proyectos
			select_cliente(row.data().cliente_id);

			// si ya se cerró el proyecto
			if (parseInt(row.data().proyecto_concluido) == 1) {
				// desactivar campo
				$('#cliente_id').prop('disabled', true);
			}
			else {
				$('#cliente_id').prop('disabled', false);
			}

		} else {
			select_cliente(0);
		}

	}


	//Mostramos los servicios seleccionados del proyecto
	obtenerServicios(row.data().id)

	//Mostramos la estructura del proyecto
	obtenerEstructuraProyecto(row.data().id)

	// campo folio
	$('#proyecto_folio').val(row.data().proyecto_folio);
	$('#proyecto_fechacreacion').val(row.data().proyecto_fechacreacion);

	if (row.data().proyecto_razonsocial != null) {
		$('#proyecto_ordenservicio').val(row.data().proyecto_ordenservicio);
		$('#proyecto_cotizacion').val(row.data().proyecto_cotizacion);
		$('#proyecto_razonsocial').val(row.data().proyecto_razonsocial);
		$('#proyecto_rfc').val(row.data().proyecto_rfc);
		$('#proyecto_direccion').val(row.data().proyecto_direccion);
		$('#proyecto_contacto').val(row.data().proyecto_contacto);
		$('#proyecto_contactocorreo').val(row.data().proyecto_contactocorreo);
		$('#proyecto_contactotelefono').val(row.data().proyecto_contactotelefono);
		$('#proyecto_contactocelular').val(row.data().proyecto_contactocelular);
		$('#proyecto_ciudadpais').val(row.data().proyecto_ciudadpais);
		$('#proyecto_personaelabora').val(row.data().proyecto_personaelabora);
		$('#proyecto_personaaprueba').val(row.data().proyecto_personaaprueba);
		$('#proyecto_fechaelaboracion').val(row.data().proyecto_fechaelaboracion);
		$('#proyecto_clienterazonsocial').val(row.data().proyecto_clienterazonsocial);
		$('#proyecto_clientenombrecomercial').val(row.data().proyecto_clientenombrecomercial);
		$('#proyecto_clienterfc').val(row.data().proyecto_clienterfc);
		$('#proyecto_clientegiroempresa').val(row.data().proyecto_clientegiroempresa);
		$("#catregion_id").val(row.data().catregion_id);
		$("#catsubdireccion_id").val(row.data().catsubdireccion_id);
		$("#catgerencia_id").val(row.data().catgerencia_id);
		$("#catactivo_id").val(row.data().catactivo_id);
		$('#proyecto_clienteinstalacion').val(row.data().proyecto_clienteinstalacion);
		$('#proyecto_clientedireccionservicio').val(row.data().proyecto_clientedireccionservicio);
		$('#proyecto_clientepersonadirigido').val(row.data().proyecto_clientepersonadirigido);
		$('#proyecto_clientepersonacontacto').val(row.data().proyecto_clientepersonacontacto);
		$('#proyecto_clientetelefonocontacto').val(row.data().proyecto_clientetelefonocontacto);
		$('#proyecto_clientecelularcontacto').val(row.data().proyecto_clientecelularcontacto);
		$('#proyecto_clientecorreocontacto').val(row.data().proyecto_clientecorreocontacto);
		$('#proyecto_clienteobjetivoservicio').val(row.data().proyecto_clienteobjetivoservicio);
		$('#proyecto_clienteobservacion').val(row.data().proyecto_clienteobservacion);
		$('#proyecto_fechainicio').val(row.data().proyecto_fechainicio);
		$('#proyecto_fechafin').val(row.data().proyecto_fechafin);
		$('#proyecto_totaldias').val(row.data().proyecto_totaldias);
		$('#proyecto_fechaentrega').val(row.data().proyecto_fechaentrega);

		if (row.data().proyecto_fechainicio != "" && parseInt(row.data().proyecto_totaldias) > 0) {
			var fechainput = row.data().proyecto_fechainicio.split("-");
			var dias = parseInt(row.data().proyecto_totaldias);

			var fecha = new Date(fechainput[0] + "/" + fechainput[1] + "/" + fechainput[2]);
			fecha.setDate(fecha.getDate() + (dias - 1)); //Sumar dias

			var day = fecha.getDate();
			var month = fecha.getMonth() + 1;
			var year = fecha.getFullYear();

			$('#proyecto_fechaentrega').datepicker('destroy');
			$('#proyecto_fechaentrega').datepicker({
				format: 'yyyy-mm-dd', //'dd-mm-yyyy'
				weekStart: 1, //dia que inicia la semana, 1 = Lunes
				startDate: new Date(month + '/' + day + '/' + year), // deshabilitar dias anteriores con fecha
				// startDate: '-3d', // deshabilitar dias antes del dia actual
				// endDate: '+3d', //deshabilitar dias despues del dia actual
				calendarWeeks: true,
				autoclose: false,
				todayHighlight: true,
				toggleActive: true,
				forceParse: false, //mantiene la fecha del input si no se selecciona otra
				setDate: year + '-' + month + '-' + day
			});

			$('#proyecto_fechaentrega').val(row.data().proyecto_fechaentrega);
		}
	}

	//Validar solicitud de una Orden de Servicio
	if (parseInt(row.data().solicitudOS) == 0) {
		$('#solicitudOS').prop('checked', false);
		$('#boton_nueva_ordenservicio').prop('disabled', true)
		$('#boton_nueva_ordenservicioadicional').prop('disabled', true)

	}
	else {
		$('#solicitudOS').prop('checked', true);
		$('#boton_nueva_ordenservicio').prop('disabled', false)
		$('#boton_nueva_ordenservicioadicional').prop('disabled', false)
	}

	// Valida perfil
	if (parseInt(row.data().perfil) > 0) {
		proyecto_perfil = parseInt(row.data().perfil);
	}
	else {
		proyecto_perfil = 0;
	}

	// Consulta ordenes de servicio
	tabla_proyectoordenservicios(row.data().id);

	// Tabla prorrogas
	tabla_prorrogas(row.data().id);
	tabla_usuarios(row.data().id);


	// SELECT RECONOCIMIENTO SENSORIAL
	if ($("#recsensorial_id")[0].selectize) {
		$("#recsensorial_id")[0].selectize.destroy();
	}

	$('#recsensorial_id').html('');
	if (row.data().recsensorial_id) {
		// mostrar step forms
		mostrar_stepformproyecto();

		recsensorial_id = parseInt(row.data().recsensorial_id);
		recsensorial_alcancefisico = parseInt(row.data().recsensorial.recsensorial_alcancefisico);
		recsensorial_alcancequimico = parseInt(row.data().recsensorial.recsensorial_alcancequimico);


		// Consulta recsensoriales no asignados a proyectos
		select_recsensorial(row.data().recsensorial_id);

		// si ya se cerró el proyecto
		if (parseInt(row.data().proyecto_concluido) == 1) {
			// desactivar campo
			$('#recsensorial_id').prop('disabled', true);
		}
		else {
			$('#recsensorial_id').prop('disabled', false);
		}

		// mostrar datos recsensorial
		consulta_recsensorial(row.data().recsensorial_id, 1);
	}
	else {
		// Ocultar menus Step-Forms
		oculta_stepformproyecto();

		recsensorial_id = 0;
		recsensorial_alcancefisico = 0;
		recsensorial_alcancequimico = 0;

		$('#folio_fisicos').html("");
		$('#folio_quimicos').html("");
		$('#folio_quimicos_cliente').html("");
		$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');
		$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="7" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');

		// Consulta recsensoriales no asignados a proyectos
		select_recsensorial(0);
	}


	// Consulta observaciones de los proveedores
	consulta_observacionesproveedores(row.data().id);


	// VERIFICA BLOQUEO DEL PROVEEDOR
	if (parseInt(row.data().proyecto_concluido) == 0) //desbloqueado
	{
		proyecto_bloqueado = 0;
		$("#boton_bloquear_proyecto").val(0);
		$("#boton_bloquear_proyecto").removeClass('btn-warning');
		$("#boton_bloquear_proyecto").addClass('btn-success');
		$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proyecto desbloqueado para edición');

		// Activar botones
		$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-save"></i>');
		$(".botonguardar_moduloproyecto").attr('disabled', false);
		// $(".botonnuevo_moduloproyecto").attr('disabled', false);        
	}
	else {
		proyecto_bloqueado = 1;
		$("#boton_bloquear_proyecto").val(1);
		$("#boton_bloquear_proyecto").removeClass('btn-success');
		$("#boton_bloquear_proyecto").addClass('btn-warning');
		$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proyecto bloqueado para edición');

		// Desactivar botones
		$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-ban"></i>');
		$(".botonguardar_moduloproyecto").attr('disabled', true);
		$(".botonnuevo_moduloproyecto").attr('disabled', true);
	}

	// Limpiar seccion modulo Reporte
	$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte</p>');
});



function bloqueo_proyecto(opcion) {
	var valor = parseInt(opcion);
	var mensaje_1 = '';
	var mensaje_2 = '';

	if (valor == 1) {
		mensaje_1 = '¿Desbloquear proyecto?';
		mensaje_2 = 'Desbloquear proyecto para edición';
	}
	else {
		mensaje_1 = '¿Bloquear proyecto?';
		mensaje_2 = 'Bloquear proyecto para edición';
	}

	// CONFIRMAR
	swal({
		title: mensaje_1,
		text: mensaje_2,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			//Enviar datos
			$.ajax({
				type: "get",
				dataType: "json",
				url: "/proyectobloqueo/" + $("#proyecto_id").val() + "/" + valor,
				data: {},
				cache: false,
				success: function (dato) {
					// Actualiza tabla
					tabla_proyecto();

					// DISEÑO DEL BOTON [BLOQUEO DEL RECONOCIMIENTO]
					if (parseInt(dato.proyecto.proyecto_concluido) == 0) {
						proyecto_bloqueado = 0;
						$("#boton_bloquear_proyecto").val(0);
						$("#boton_bloquear_proyecto").removeClass('btn-warning');
						$("#boton_bloquear_proyecto").addClass('btn-success');
						$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proyecto desbloqueado para edición');

						// Activar botones
						$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-save"></i>');
						$(".botonguardar_moduloproyecto").attr('disabled', false);
						$(".botonnuevo_moduloproyecto").attr('disabled', false);
					}
					else {
						proyecto_bloqueado = 1;
						$("#boton_bloquear_proyecto").val(1);
						$("#boton_bloquear_proyecto").removeClass('btn-success');
						$("#boton_bloquear_proyecto").addClass('btn-warning');
						$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proyecto bloqueado para edición');

						// Desactivar botones
						$(".botonguardar_moduloproyecto").html('Guardar <i class="fa fa-ban"></i>');
						$(".botonguardar_moduloproyecto").attr('disabled', true);
						$(".botonnuevo_moduloproyecto").attr('disabled', true);
					}

					// mensaje
					swal({
						title: "Correcto",
						text: "" + dato.msj,
						type: "success", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
				},
				beforeSend: function () {
					$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cambiando estado del proyecto');
				},
				error: function (dato) {
					// VERIFICA BLOQUEO DEL RECONOCIMIENTO
					if (valor == 0) //desbloqueado
					{
						$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proyecto desbloqueado para edición');
					}
					else {
						$("#boton_bloquear_proyecto").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proyecto bloqueado para edición');
					}

					// mensaje
					swal({
						title: "Error",
						text: "" + dato.msj,
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});

					return false;
				}
			});//Fin ajax
			return false;
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "Acción cancelada",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
}

function cambiarFolio(element) {

	if (element.checked || parseInt(element) == 1) {

		select_cliente(0)
		proyectoInterno = 1

		$('#requiereContrato').val(0)
		$('#proyectoInterno').val(1)
		$('#contrato_id')[0].selectize.disable();
		$('#contrato_id')[0].selectize.clear();
		$('.servCliente').prop('disabled', true)
		$('.servCliente').each(function () {
			$(this).prop('checked', false)
		})

		$('#proyecto_ordenservicio').prop('disabled', true);
		$('#proyecto_clienterazonsocial').fadeOut(1)

		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.enable();
			selectizeInstance.$wrapper.fadeIn(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', false);
			$('#cliente_id').fadeIn(1)

		}


	} else {

		proyectoInterno = 0
		$('#requiereContrato').val(1)
		$('#contrato_id')[0].selectize.enable();
		$('#proyectoInterno').val(0)
		$('.servCliente').prop('disabled', false)
		$('#proyecto_ordenservicio').prop('disabled', false);


		$('#proyecto_clienterazonsocial').fadeIn(1)
		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.disable();
			selectizeInstance.$wrapper.fadeOut(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', false);
			$('#cliente_id').fadeOut(1)

		}

	}
}


$("#boton_guardar_proyecto").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		if (recsensorial_errorvalidacion == 0) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Al guardar ya no podrá cambiar el reconocimiento asociado a este proyecto.",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Aceptar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_proyecto').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/proyectos',
						data: {
							api: 1
						},
						resetForm: false,
						success: function (dato) {
							// actualiza campo
							proyecto_id = parseInt(dato.proyecto.id);
							$('#proyecto_id').val(dato.proyecto.id);
							$('#proyecto_folio').val(dato.proyecto.proyecto_folio);
							$('#proyecto_fechacreacion').val(dato.proyecto.proyecto_fechacreacion);

							//DIV NOMBRE DEL PROYECTO
							$('.div_proyecto_folio').html(dato.proyecto.proyecto_folio)
							$('.div_proyecto_instalacion').html(dato.proyecto.proyecto_clienteinstalacion)

							// inicializar tablas principales (Esto se debe de cambiar de aqui a HI ya que las tablas ya no van a estar en proyecto)
							inicializar_datatables();

							//Verificamos si el proyecto guardado es Un proyecto Interno o con Cliente
							if (dato.proyecto.proyectoInterno == 1) {
								// mostrar seccion datos proyecto
								$("#seccion_prorrogas").css('display', 'none');
								$("#seccion_ordenes_servicio").css('display', 'none');
								$("#encavezadovolumetria").css('display', 'block');
								$("#seccion_recsensorialresumenfisico").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
								$("#seccion_recsensorialresumenquimico").css('display', 'block');
								$("#datosReconocimientos").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
								$("#seccion_asignacion_usuarios").css('display', 'block');

								// actualiza tabla
								tabla_proyectoInternos()

							} else {

								// mostrar seccion datos proyecto
								$("#seccion_prorrogas").css('display', 'block');
								$("#seccion_ordenes_servicio").css('display', 'block');
								$("#encavezadovolumetria").css('display', 'block');
								$("#seccion_recsensorialresumenfisico").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
								$("#seccion_recsensorialresumenquimico").css('display', 'block');
								$("#datosReconocimientos").css({ 'display': 'flex', 'flex-wrap': 'wrap' });
								$("#seccion_asignacion_usuarios").css('display', 'block');


								// actualiza tabla
								tabla_proyecto();

							}


							// Si existe Rec Sensorial
							if (dato.proyecto.recsensorial_id) {
								recsensorial_id = parseInt(dato.proyecto.recsensorial_id);
								recsensorial_alcancefisico = parseInt(dato.proyecto.recsensorial.recsensorial_alcancefisico);
								recsensorial_alcancequimico = parseInt(dato.proyecto.recsensorial.recsensorial_alcancequimico);
								recsensorial_errorvalidacion = 0;

								// Mostrar Menus Step-Forms
								mostrar_stepformproyecto();

								// Mostrar form proveedores
								consulta_proveedores(dato.proyecto.id, recsensorial_id, recsensorial_alcancefisico, recsensorial_alcancequimico);
							}
							else {
								recsensorial_id = 0;
								recsensorial_alcancefisico = 0;
								recsensorial_alcancequimico = 0;
								recsensorial_errorvalidacion = 0;

								// Vaciar tabla proveedores
								$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="7" style="text-align: center;">No se ha asignado reconocimiento sensorial</td></tr>');

								// Mostrar Menus Step-Forms
								oculta_stepformproyecto();
							}


							// mostrar menu tab
							muestra_menustab_principal();

							//Recargamos el numero de contratos
							proyectosTotales()


							// mensaje
							swal({
								title: "Proyecto guardado exitosamente",
								text: "El proyecto se ha guardado correctamente",
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});

							// actualiza boton
							$('#boton_guardar_proyecto').html('Guardar <i class="fa fa-save"></i>');
						},
						beforeSend: function () {
							$('#boton_guardar_proyecto').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function (dato) {
							// actualiza boton
							$('#boton_guardar_proyecto').html('Guardar <i class="fa fa-save"></i>');

							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					}).submit();
					return false;
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "Acción cancelada",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
				}
			});
			return false;
		}
		else {
			// mensaje
			swal({
				title: "Falta validación",
				text: "Para asignar el RECONOCIMIENTO elegido a este proyecto, necesita subir el archivo de validación de QUÍMICOS en el módulo de reconocimiento sensorial.",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 6000,
				showConfirmButton: false
			});
			return false;
		}
	}
});


//===============================================================


var ordenservicios_opciones = '<option value="">&nbsp;</option>';


function tabla_proyectoordenservicios(proyecto_id) {
	try {
		var ruta = "/proyectoordenserviciotabla/" + proyecto_id;

		if (datatable_proyectoordenservicios != null) {
			datatable_proyectoordenservicios.clear().draw();
			datatable_proyectoordenservicios.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_proyectoordenservicios = $('#tabla_ordenservicios').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					"data": {},
					dataSrc: function (json) {
						// if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						// {
						// 	menureporte_estado("menureporte_5_4", 1);
						// }
						// else
						// {
						// 	menureporte_estado("menureporte_5_4", 0);
						// }

						ordenservicios_opciones = json.ordenservicios_opciones;

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en datatable_ordenservicios');
						if (numeroejecucion <= 1) {
							tabla_proyectoordenservicios(proyecto_id);
							numeroejecucion += 1;
						}
					},
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "proyectoordenservicio_oficio",
						"defaultContent": "-"
					},
					{
						"data": "proyectoordenservicio_numero",
						"defaultContent": "-"
					},
					{
						"data": "proyectoordenservicio_cotizacion",
						"defaultContent": "-"
					},
					{
						"data": "valida_nombre_fecha",
						"defaultContent": "-"
					},
					{
						"data": "verificacion",
						"defaultContent": "-"
					},
					{
						"className": 'mostrar',
						"orderable": false,
						// "data": 'IconoBloqueo',
						"defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-eye"></i></button>'
					},
					{
						"className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}

		// Tooltip en DataTable
		datatable_proyectoordenservicios.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_proyectoordenservicios(proyecto_id);
	}
}


// Vaciar contenido del visor al cerrar modal
$("#boton_visorcerrar").click(function () {
	$('#visor_ordenserviciopdf').attr('src', '/assets/images/fondovisor.jpg');
});


//ACTIVAR BOTONES DE OS Y ACTUALIZAR EN LA BASE DE DATOS
function solicitarOS(element) {

	//Solicita una OS
	if (element.checked || parseInt(element) == 1) {
		valor = 1
	} else {
		valor = 0
	}

	//CAMBIO DE ESTADO
	$.ajax({
		type: "get",
		dataType: "json",
		url: "/proyectoSolicitarOS/" + $("#proyecto_id").val() + "/" + valor,
		data: {},
		cache: false,
		success: function (dato) {
			// Actualiza tabla
			tabla_proyecto();


			$("#boton_nueva_ordenservicio").html('<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva orden de servicio');
			$("#boton_nueva_ordenservicioadicional").html('<span class="btn-label"><i class="fa fa-plus"></i></span>Nuevo documento adicional a la OS');
			$("#boton_nueva_prorroga").html('<span class="btn-label"><i class="fa fa-plus"></i></span> Nueva prórrogas');


			if (valor == 0) {
				$("#boton_nueva_ordenservicio").prop('disabled', true);
				$("#boton_nueva_ordenservicioadicional").prop('disabled', true);
				$("#boton_nueva_prorroga").prop('disabled', true);


			} else {
				$("#boton_nueva_ordenservicio").prop('disabled', false);
				$("#boton_nueva_ordenservicioadicional").prop('disabled', false);
				$("#boton_nueva_prorroga").prop('disabled', false);

			}


		}, beforeSend: function () {

			if (valor == 0) {
				$("#boton_nueva_ordenservicio").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cancelando OS');
				$("#boton_nueva_ordenservicioadicional").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cancelando documento OS');
				$("#boton_nueva_prorroga").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cancelando prórrogas');


			} else {

				$("#boton_nueva_ordenservicio").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Solicitando OS');
				$("#boton_nueva_ordenservicioadicional").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Solicitando documento OS');
				$("#boton_nueva_prorroga").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Nueva prórrogas');

			}


		}, error: function (dato) {

			swal({
				title: "Error al solicitar la OS",
				text: "Intentelo de nuevo o reportelo con el encargado",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});

			return false;
		}
	});//Fin ajax

}

$("#boton_nueva_ordenservicio").click(function () {
	// vaciar visor
	$('#visor_ordenserviciopdf').attr('src', '/assets/images/fondovisor.jpg');

	// requerir campo PDF
	$('#orderserviciopdf').attr('required', true);

	// no requerir campo
	$('#proyectoordenservicio_fechavalidacion').attr('required', false);
	$('#proyectoordenservicio_personavalidacion').attr('required', false);
	$('#proyectoordenservicio_fechavalidacion').attr('disabled', true);
	$('#proyectoordenservicio_personavalidacion').attr('disabled', true);

	// Borrar formulario
	$('#form_ordenservicio').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#ordenservicio_id").val(0);
	$("#ordenservicio_proyecto_id").val($("#proyecto_id").val());

	// mostrar modal
	$('#modal_ordenservicio').modal({ backdrop: false });
});


function verificaestado_ordenservicio(checkbox) {
	if (checkbox.checked) {
		// alert('activo');

		// Requerir campos
		$('#proyectoordenservicio_fechavalidacion').attr('required', true);
		$('#proyectoordenservicio_personavalidacion').attr('required', true);

		$('#proyectoordenservicio_fechavalidacion').attr('disabled', false);
		$('#proyectoordenservicio_personavalidacion').attr('disabled', false);
	}
	else {
		// alert('inactivo');

		// NO Requerir campos
		$('#proyectoordenservicio_fechavalidacion').attr('required', false);
		$('#proyectoordenservicio_personavalidacion').attr('required', false);

		$('#proyectoordenservicio_fechavalidacion').val('');
		$('#proyectoordenservicio_personavalidacion').val('');

		$('#proyectoordenservicio_fechavalidacion').attr('disabled', true);
		$('#proyectoordenservicio_personavalidacion').attr('disabled', true);
	}
}

// Selecciona orden de servicio
$('#tabla_ordenservicios tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_proyectoordenservicios.row(tr);


	if (parseInt(row.data().tipo_documento) == 1) //ORDEN SERVICIO
	{
		// Borrar formulario
		$('#form_ordenservicio').each(function () {
			this.reset();
		});

		// requerir campo PDF
		$('#orderserviciopdf').attr('required', false);

		// Campos Hidden
		$("#ordenservicio_id").val(row.data().id);
		$("#ordenservicio_proyecto_id").val(row.data().proyecto_id);

		// campos form
		$("#proyectoordenservicio_oficio").val(row.data().proyectoordenservicio_oficio);
		$("#proyectoordenservicio_numero").val(row.data().proyectoordenservicio_numero);
		$("#proyectoordenservicio_cotizacion").val(row.data().proyectoordenservicio_cotizacion);
		$("#proyectoordenservicio_total").val(row.data().proyectoordenservicio_total);
		$("#proyectoordenservicio_contrato").val(row.data().proyectoordenservicio_contrato);
		$("#proyectoordenservicio_raf").val(row.data().proyectoordenservicio_raf);
		$("#proyectoordenservicio_pedido").val(row.data().proyectoordenservicio_pedido);
		$("#proyectoordenservicio_observacion").val(row.data().proyectoordenservicio_observacion);
		$("#proyectoordenservicio_fechavalidacion").val(row.data().proyectoordenservicio_fechavalidacion);
		$("#proyectoordenservicio_personavalidacion").val(row.data().proyectoordenservicio_personavalidacion);

		// checkbox
		if (parseInt(row.data().proyectoordenservicio_validado) == 1) {
			$("#proyectoordenservicio_validado").prop('checked', true);

			// Requerir campos
			$('#proyectoordenservicio_fechavalidacion').attr('required', true);
			$('#proyectoordenservicio_personavalidacion').attr('required', true);

			$('#proyectoordenservicio_fechavalidacion').attr('disabled', false);
			$('#proyectoordenservicio_personavalidacion').attr('disabled', false);
		}
		else {
			$("#proyectoordenservicio_validado").prop('checked', false);

			// NO Requerir campos
			$('#proyectoordenservicio_fechavalidacion').attr('required', false);
			$('#proyectoordenservicio_personavalidacion').attr('required', false);

			$('#proyectoordenservicio_fechavalidacion').attr('disabled', true);
			$('#proyectoordenservicio_personavalidacion').attr('disabled', true);
		}

		// mostrar modal
		$('#modal_ordenservicio').modal({ backdrop: false });

		// Mostrar PDF
		// $('#visor_ordenserviciopdf').attr('src', '/proyectoordenserviciopdf/'+row.data().id);
		// $('#visor_ordenserviciopdf').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordenserviciopdf/'+row.data().id);

		// CARGAR EN EL VISOR
		if ((parseInt(proyecto_perfil) == 1 || parseInt(proyecto_perfil) == 2) && (row.data().proyectoordenservicio_validado) == 1 && parseInt(proyecto_bloqueado) == 0) {
			// Iframe
			setTimeout(function () {
				$('#visor_ordenserviciopdf').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordenserviciopdf/' + row.data().id);
			}, 500);
		}
		else {
			// viewerpdf
			setTimeout(function () {
				$('#visor_ordenserviciopdf').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordenserviciopdf/' + row.data().id);
			}, 500);
		}
	}
	else {
		// CARGAR EN EL VISOR
		if ((parseInt(proyecto_perfil) == 1 || parseInt(proyecto_perfil) == 2) && parseInt(proyecto_bloqueado) == 0) {
			// Iframe
			setTimeout(function () {
				$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordenservicioadicionalpdf/' + row.data().id);
			}, 500);
		}
		else {
			// viewerpdf
			setTimeout(function () {
				$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordenservicioadicionalpdf/' + row.data().id);
			}, 500);
		}

		// Titulo del modal
		$('#modal_visor .modal-title').html(row.data().proyectoordenservicio_oficio);

		// mostrar modal
		$('#modal_visor').modal({ backdrop: false });
	}
});


$("#boton_guardar_ordenservicio").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Orden de servicio",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_ordenservicio').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoordenservicio',
					data: {
						opcion: 1,
					},
					resetForm: false,
					success: function (dato) {


						// actualiza tabla
						tabla_proyectoordenservicios(dato.ordenservicio.proyecto_id);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_ordenservicio').html('Guardar <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_ordenservicio').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_ordenservicio').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_ordenservicio').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$('#tabla_ordenservicios tbody').on('click', 'td.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_proyectoordenservicios.row(tr);


	var texto_eliminar = '¿Eliminar Orden de servicio?';
	var texto_eliminar_confirmación = '¡Confirme nuevamente eliminar la orden de servicio!';
	var documento_nombre = row.data().proyectoordenservicio_numero;

	if (parseInt(row.data().tipo_documento) == 2) //DOC ADICIONAL
	{
		texto_eliminar = '¿Eliminar documento adicional?';
		texto_eliminar_confirmación = '¡Confirme nuevamente eliminar documento adicional!';
		documento_nombre = row.data().proyectoordenservicio_oficio;
	}


	if (row.data().total_ordenservicios > 1 && row.data().opcion_eliminar == 1) {
		swal({
			title: "" + texto_eliminar,
			text: "" + documento_nombre,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Eliminar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				swal({
					title: "" + texto_eliminar_confirmación,
					text: "" + documento_nombre,
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Eliminar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				}, function (isConfirm) {
					if (isConfirm) {
						// cerrar msj confirmacion
						swal.close();

						// eliminar
						$.ajax({
							type: "GET",
							dataType: "json",
							url: "/proyectoordenservicioeliminar/" + row.data().id + "/" + row.data().tipo_documento,
							data: {},
							cache: false,
							success: function (dato) {
								// actualiza tabla
								tabla_proyectoordenservicios(row.data().proyecto_id);

								// mensaje
								swal({
									title: "Correcto",
									text: "" + dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});
							},
							error: function (dato) {
								// alert('Error: '+dato.msj);
								return false;
							}
						});//Fin ajax
					}
					else {
						// mensaje
						swal({
							title: "Cancelado",
							text: "Acción cancelada",
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 500,
							showConfirmButton: false
						});
					}
				});
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	}
	else {
		// mensaje
		swal({
			title: "No disponible",
			text: "Acceso no autorizado",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});
	}
});


//-------------------------------------


$("#boton_nueva_ordenservicioadicional").click(function () {
	// Borrar formulario
	$('#form_ordenservicioadicional').each(function () {
		this.reset();
	});


	// Campos Hidden
	$("#ordenservicioadicional_id").val(0);
	$("#ordenservicioadicional_proyecto_id").val($("#proyecto_id").val());


	// Llenar campos
	$("#ordenservicioadicional_proyectoordenservicio_id").html(ordenservicios_opciones);


	// mostrar modal
	$('#modal_ordenservicioadicional').modal({ backdrop: false });
});


$("#boton_guardar_ordenservicioadicional").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Documento adicional a la OS",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_ordenservicioadicional').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoordenservicio',
					data: {
						opcion: 2,
					},
					resetForm: false,
					success: function (dato) {

						// actualiza tabla
						tabla_proyectoordenservicios(dato.ordenservicio.proyecto_id);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_ordenservicioadicional').html('Guardar <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_ordenservicioadicional').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_ordenservicioadicional').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_ordenservicioadicional').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


// Modal FORM imagen cargando al cerrar
$("#modalvisor_boton_cerrar").click(function () {
	// Titulo del modal
	$('#modal_visor .modal-title').html('Visor documento');

	$('#visor_documento').attr('src', '/assets/images/cargando.gif');
});


//===============================================================


var datatable_prorrogas = null;
function tabla_prorrogas(proyecto_id) {
	try {
		var ruta = "/proyectoprorrogastabla/" + proyecto_id;

		if (datatable_prorrogas != null) {
			datatable_prorrogas.clear().draw();
			datatable_prorrogas.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_prorrogas = $('#tabla_prorrogas').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_prorrogas ' + code);
						if (numeroejecucion <= 1) {
							tabla_prorrogas(proyecto_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "numero_registro",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "proyectoprorrogas_fechainicio",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "proyectoprorrogas_fechafin",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "proyectoprorrogas_dias",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_editar",
						defaultContent: "-",
						className: 'editar',
						orderable: false,
					},
					{
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "Todos"]],
				// rowsGroup: [1, 2, 3], //agrupar filas
				order: [[0, "ASC"]],
				ordering: false,
				processing: true,
				searching: false,
				paging: false,
				language: {
					lengthMenu: "Mostrar _MENU_ Registros",
					zeroRecords: "No se encontraron registros",
					info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
					infoEmpty: "No se encontraron registros",
					infoFiltered: "(Filtrado de _MAX_ registros)",
					emptyTable: "No hay datos disponibles en la tabla",
					loadingRecords: "Cargando datos....",
					processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					search: "Buscar",
					paginate: {
						first: "Primera",
						last: "Ultima",
						next: "Siguiente",
						previous: "Anterior"
					}
				},
				rowCallback: function (row, data, index) {
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					// $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
					// $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
					// $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
					// $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
					// $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
					// $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_prorrogas.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_prorrogas(proyecto_id);
	}
}


$("#boton_nueva_prorroga").click(function () {
	// Borrar formulario
	$('#form_prorroga').each(function () {
		this.reset();
	});


	// Campos Hidden
	$("#prorroga_id").val(0);
	$("#prorroga_proyecto_id").val($("#proyecto_id").val());


	// mostrar modal
	$('#modal_prorroga').modal({ backdrop: false });
});


function prorroga_dias(inicio, fin) {
	var startDate = $('#proyectoprorrogas_fechainicio').val();
	var endDate = $('#proyectoprorrogas_fechafin').val();


	if (startDate && endDate) {
		var start = new Date(startDate);
		var end = new Date(endDate);

		var diffDate = (end - start) / (1000 * 60 * 60 * 24);
		var days = parseInt(Math.round(diffDate) + 1);


		// Llenar campos
		$('#proyectoprorrogas_dias').val(days);
	}
	else {
		$('#proyectoprorrogas_dias').val('');
		// console.log('no hay fechas');
	}
}


$('#tabla_prorrogas tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_prorrogas.row(tr);


	$('#form_prorroga').each(function () {
		this.reset();
	});


	// Campos Hidden
	$('#prorroga_id').val(row.data().id);
	$('#prorroga_proyecto_id').val(row.data().proyecto_id);


	// Llenar campos
	$('#proyectoprorrogas_fechainicio').val(row.data().proyectoprorrogas_fechainicio);
	$('#proyectoprorrogas_fechafin').val(row.data().proyectoprorrogas_fechafin);
	$('#proyectoprorrogas_dias').val(row.data().proyectoprorrogas_dias);


	// mostrar modal
	$('#modal_prorroga').modal({ backdrop: false });
});


$('#tabla_prorrogas tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_prorrogas.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Prórroga del proyecto",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				swal({
					title: "¡Por seguridad confirme nuevamente que desea eliminar!",
					text: "Prórroga del proyecto",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Eliminar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
					function (isConfirm) {
						if (isConfirm) {
							// cerrar msj confirmacion
							swal.close();

							$.ajax({
								type: "GET",
								dataType: "json",
								url: "/proyectoprorrogaseliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_prorrogas(row.data().proyecto_id);


									// mensaje
									swal({
										title: "Correcto",
										text: "" + dato.msj,
										type: "success", // warning, error, success, info
										buttons: {
											visible: false, // true , false
										},
										timer: 1500,
										showConfirmButton: false
									});
								},
								beforeSend: function () {
									// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
								},
								error: function (dato) {
									// mensaje
									swal({
										title: "Error",
										text: "" + dato.msj,
										type: "error", // warning, error, success, info
										buttons: {
											visible: false, // true , false
										},
										timer: 1500,
										showConfirmButton: false
									});

									return false;
								}
							});//Fin ajax
						}
						else {
							// mensaje
							swal({
								title: "Cancelado",
								text: "Acción cancelada",
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 500,
								showConfirmButton: false
							});
						}
					});
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


$("#boton_guardar_prorroga").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Prórroga del proyecto",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_prorroga').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoprorrogas',
					data: {},
					resetForm: false,
					success: function (dato) {
						// actualiza tabla
						tabla_prorrogas(dato.prorroga.proyecto_id);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_prorroga').html('Guardar <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_prorroga').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_prorroga').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_prorroga').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});



//===============================================================


function consulta_proveedores(proyecto_id, recsensorial_id, recsensorial_alcancefisico, recsensorial_alcancequimico) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoproveedoreslista/" + proyecto_id + "/" + recsensorial_id + "/" + recsensorial_alcancefisico + "/" + recsensorial_alcancequimico,
		data: {},
		cache: false,
		success: function (dato) {
			if (parseInt(dato.numero_registros) > 0) {
				$('#tabla_proyectoproveedores tbody').html(dato.filas);
				proveedores_asignados = parseInt(dato.numero_registros);

				// Verifica proyecto bloqueado
				if (parseInt(proyecto_bloqueado) == 1) {
					$('#boton_guardar_proyectoproveedores').html('Guardar <i class="fa fa-ban"></i>')
					$('#boton_guardar_proyectoproveedores').attr('disabled', true);
				}
				else {
					$('#boton_guardar_proyectoproveedores').html('Guardar <i class="fa fa-save"></i>')
					$('#boton_guardar_proyectoproveedores').attr('disabled', false);
				}
			}
			else {
				$('#boton_guardar_proyectoproveedores').html('Guardar <i class="fa fa-ban"></i>')
				$('#boton_guardar_proyectoproveedores').attr('disabled', true);

				$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="8" style="text-align: center;">No hay datos que mostrar</td></tr>');
				proveedores_asignados = 0;
			}
		},
		beforeSend: function () {
			$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="8" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_proyectoproveedores tbody').html('<tr><td colspan="8" style="text-align: center;">Error al consultar los datos</td></tr>');
			$('#boton_guardar_proyectoproveedores').attr('disabled', true);
			proveedores_asignados = 0;
			return false;
		}
	});//Fin ajax
}


function valida_requiere_agente_activo(agente_activo) {
	if (agente_activo.checked)// Valida estado
	{
		// $('#select_proveedor_'+agente_activo.value).attr('readonly', false);
		// $('#puntos_agente_'+agente_activo.value).attr('readonly', false);

		$('#select_proveedor_' + agente_activo.value).attr('required', true);
		$('#puntos_agente_' + agente_activo.value).attr('required', true);
	}
	else {
		$('#select_proveedor_' + agente_activo.value).val('');
		$('#puntos_agente_' + agente_activo.value).val('');

		$('#select_proveedor_' + agente_activo.value).attr('required', false);
		$('#puntos_agente_' + agente_activo.value).attr('required', false);

		// $('#select_proveedor_'+agente_activo.value).attr('readonly', true);
		// $('#puntos_agente_'+agente_activo.value).attr('readonly', true);
	}
}


function requiere_obs(agente_id, puntos_originales, puntos_actuales) {
	var puntos_originales = parseInt(puntos_originales);
	var puntos_actuales = parseInt(puntos_actuales);

	if (puntos_originales != puntos_actuales) {
		$('#agente_obs_' + agente_id).attr('readonly', false);
		$('#agente_obs_' + agente_id).attr('required', true);
	}
	else {
		$('#agente_obs_' + agente_id).val('');
		$('#agente_obs_' + agente_id).attr('required', false);
		$('#agente_obs_' + agente_id).attr('readonly', true);
	}
}


function consulta_listaproveedores() {
	setTimeout(function () {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectoproveedorestodos",
			data: {},
			cache: false,
			success: function (dato) {
				proveedores_lista = dato.select_lista_proveedores;
			},
			error: function (dato) {
				consulta_listaproveedores();
				proveedores = 0;
				return false;
			}
		});//Fin ajax
	}, 1000);
}


$("#boton_proyectoproveedornuevapartida").click(function () {
	proveedores_asignados += 1;

	$("#tabla_proyectoproveedores tbody").append('<tr>' +
		'<td>' + proveedores_asignados + '</td>' +
		'<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
		'<td>' +
		'<input type="checkbox" name="agenteadicional_activo[]" checked/>' +
		'<select class="custom-select form-control" id="adicional_' + proveedores_asignados + '_proveedor" name="proveedoradicional_id[]" required onchange="mostrar_proveedoralcances(this, ' + proveedores_asignados + ');">' +
		proveedores_lista +
		'</select>' +
		'</td>' +
		'<td colspan="3">' +
		'<select class="custom-select form-control" id="adicional_' + proveedores_asignados + '_alcance" name="agenteadicional_id[]" required onchange="llenarcampos_proveedoralcances(this, ' + proveedores_asignados + ');">' +
		'<option value="">&nbsp;</option>' +
		'</select>' +
		'<input type="hidden" class="form-control" id="adicional_' + proveedores_asignados + '_tipo" name="agenteadicional_tipo[]" value="">' +
		'<input type="hidden" class="form-control" id="adicional_' + proveedores_asignados + '_nombre" name="agenteadicional_nombre[]" value="">' +
		'</td>' +
		'<td>' +
		'<input type="number" class="form-control" name="agenteadicional_puntos[]" value="" required>' +
		'</td>' +
		'<td>' +
		'<input type="text" class="form-control" name="agenteadicional_obs[]" value="" required>' +
		'</td>' +
		'</tr>');
});


// Eliminar fila partida
$('#tabla_proyectoproveedores tbody').on('click', '.eliminar', function () {

	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar registro?",
		text: "Eliminar registro de la lista",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Eliminar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			var tr = fila.closest('tr');
			fila.closest("tr").remove(); // eliminar fila TR

			// mensaje
			swal({
				title: "Correcto",
				text: "Registro eliminado de la lista",
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1000,
				showConfirmButton: false
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
});


function mostrar_proveedoralcances(campo_select, campo_total) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoproveedoralcances/" + campo_select.value,
		data: {},
		cache: false,
		success: function (dato) {
			$("#adicional_" + campo_total + "_alcance").html(dato.select_lista_proveedoralcances);
		},
		error: function (dato) {
			$("#adicional_" + campo_total + "_alcance").html('<option value="">&nbsp;</option>')
			return false;
		}
	});//Fin ajax
}


function llenarcampos_proveedoralcances(campo_select, campo_total) {
	if (parseInt(campo_select.value) > 0) {
		$("#adicional_" + campo_total + "_nombre").val($("#" + campo_select.id + " option:selected").text());
		$("#adicional_" + campo_total + "_tipo").val(1);
	}
	else {
		$("#adicional_" + campo_total + "_nombre").val($("#" + campo_select.id + " option:selected").text());
		$("#adicional_" + campo_total + "_tipo").val(2); //sin descripcion
		$("#adicional_" + campo_total + "_analisis").val(''); //sin descripcion
	}
}


$("#boton_guardar_proyectoproveedores").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Lista de proveedores a este proyecto",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_proyectoproveedores').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoproveedores',
					data: {
						proyecto_id: $('#proyecto_id').val(),
					},
					resetForm: false,
					success: function (dato) {

						// inicializar tabla
						datatable_signatarios_activo = 0;
						datatable_equipos_activo = 0;

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_proyectoproveedores').html('Guardar <i class="fa fa-save"></i>');

						$("#boton_nueva_ordenservicio").prop('disabled', true);
						$("#boton_nueva_ordenservicioadicional").prop('disabled', true);

					},
					beforeSend: function () {
						$('#boton_guardar_proyectoproveedores').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_proyectoproveedores').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//===============================================================


function tabla_signatarios(proyecto_id) {
	try {
		var ruta = "/proyectosignatariosinventario/" + proyecto_id;

		if (datatable_signatarios != null) {
			datatable_signatarios.clear().draw();
			datatable_signatarios.ajax.url(ruta).load();
		}
		else {
			datatable_signatarios = $('#tabla_proyectosignatarios').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						tabla_signatarios(proyecto_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "proveedor_NombreComercial",
						"defaultContent": "-"
					},
					{
						"data": "disponibilidad",
						"defaultContent": "-"
					},
					{
						"data": "checkbox",
						"defaultContent": "-",
						"orderable": false
					},
					{
						"data": "signatario_Nombre",
						"defaultContent": "-"
					},
					{
						"data": "signatario_alcances",
						"defaultContent": "-",
						"orderable": false
					},
					{
						"data": "signatario_acreditaciones",
						"defaultContent": "-"
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				// "order": [[ 0, "DESC" ]],
				"ordering": false,
				"processing": true,
				"paging": false,
				"scrollY": 500,
				"scrollX": false,
				"scrollCollapse": false,
				responsive: true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}
	}
	catch (exception) {
		tabla_signatarios(proyecto_id);
	}
}


$("#boton_guardar_proyectosignatarios").click(function () {
	// borrar campo filtro del DATATABLE
	datatable_signatarios.search($(this).val()).draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.checkbox_proyectosignatarios').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});

	if (seleccionados > 0) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Lista de signatarios",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_proyectosignatarios').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectosignatarios',
					data: {
						proyecto_id: $('#proyecto_id').val(),
						opcion: 0,
					},
					resetForm: false,
					success: function (dato) {

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_proyectosignatarios').html('Guardar <i class="fa fa-save"></i>');
					},
					beforeSend: function () {
						$('#boton_guardar_proyectosignatarios').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_proyectosignatarios').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
	else {
		// mensaje
		swal({
			title: "Seleccione signatarios",
			text: "Antes de guardar debe seleccionar uno o más signatarios",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2000,
			showConfirmButton: false
		});
		return false;
	}
});


//===============================================================


function consulta_observacionesproveedores(proyecto_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoobservacionesproveedores/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			if (parseInt(dato.obs_signatarios.length) > 0) {
				// Contenido tablas
				$('#tabla_proyectosignatariosobservaciones tbody').html(dato.obs_signatarios);

				// diseño boton
				$('#boton_guardar_proyectosignatarios').attr('disabled', false);
				$('#boton_guardar_proyectosignatarios').html('Guardar <i class="fa fa-save"></i>')
			}
			else {
				// Contenido tablas
				$('#tabla_proyectosignatariosobservaciones tbody').html('<tr><td colspan="2" style="text-align: center;">No hay datos que mostrar</td></tr>');

				// diseño boton
				$('#boton_guardar_proyectosignatarios').html('Guardar <i class="fa fa-ban"></i>')
				$('#boton_guardar_proyectosignatarios').attr('disabled', true);

				// inicializar tabla
				datatable_signatarios_activo = 0;
			}

			if (parseInt(dato.obs_equipos.length) > 0) {
				// Contenido tablas
				$('#tabla_proyectoequiposobservaciones tbody').html(dato.obs_equipos);

				// diseño boton
				$('#boton_guardar_proyectoequipos').attr('disabled', false);
				$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>')
			}
			else {
				// Contenido tablas
				$('#tabla_proyectoequiposobservaciones tbody').html('<tr><td colspan="2" style="text-align: center;">No hay datos que mostrar</td></tr>');

				// diseño boton
				$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-ban"></i>')
				$('#boton_guardar_proyectoequipos').attr('disabled', true);

				// inicializar tabla
				datatable_equipos_activo = 0;
			}
		},
		// beforeSend: function(){
		// 	$('#tabla_proyectosignatarios tbody').html('<tr><td colspan="6" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		// },
		error: function (dato) {
			datatable_signatarios_activo = 0;
			datatable_equipos_activo = 0;
			$('#tabla_proyectosignatariosobservaciones tbody').html('<tr><td colspan="2" style="text-align: center;">No hay datos que mostrar</td></tr>');
			$('#tabla_proyectoequiposobservaciones tbody').html('<tr><td colspan="2" style="text-align: center;">No hay datos que mostrar</td></tr>');
			$('#boton_guardar_proyectosignatarios').attr('disabled', true);
			$('#boton_guardar_proyectoequipos').attr('disabled', true);
			return false;
		}
	});//Fin ajax
}


//===============================================================


function tabla_equipos(proyecto_id) {
	try {
		var ruta = "/proyectoequiposinventario/" + proyecto_id;

		if (datatable_equipos != null) {
			datatable_equipos.clear().draw();
			datatable_equipos.ajax.url(ruta).load();
		}
		else {
			datatable_equipos = $('#tabla_proyectoequipos').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						// datatable_equipos_activo = 0;
						// $('#tab_menu4').click();
						tabla_equipos(proyecto_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "proveedor_NombreComercial",
						"defaultContent": "-"
					},
					{
						"data": "equipo_disponible",
						"defaultContent": "-"
					},
					{
						"data": "checkbox",
						"defaultContent": "-",
						"orderable": false,
					},
					{
						"data": "equipo_Descripcion",
						"defaultContent": "-"
					},
					{
						"data": "equipo_Marca",
						"defaultContent": "-"
					},
					{
						"data": "equipo_Modelo",
						"defaultContent": "-"
					},
					{
						"data": "equipo_Serie",
						"defaultContent": "-"
					},
					{
						"data": "equipo_VigenciaCalibracion",
						"defaultContent": "-"
					}
				],
				// "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				// "order": [[ 0, "DESC" ]],
				"ordering": false,
				"processing": true,
				"paging": false,
				"scrollY": 500,
				"scrollX": false,
				"scrollCollapse": false,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}
	}
	catch (exception) {
		// tabla_equipos(proyecto_id);
	}
}


$("#boton_guardar_proyectoequipos").click(function () {
	// borrar campo filtro del DATATABLE
	datatable_equipos.search($(this).val()).draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.checkbox_proyectoequipos').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});

	if (seleccionados > 0) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Lista de equipos",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
			function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_proyectoequipos').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/proyectoequipos',
						data: {
							proyecto_id: $('#proyecto_id').val(),
							opcion: 0,
						},
						resetForm: false,
						success: function (dato) {

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});

							// actualiza boton
							$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');
						},
						beforeSend: function () {
							$('#boton_guardar_proyectoequipos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function (dato) {
							// actualiza boton
							$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');

							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					}).submit();
					return false;
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "Acción cancelada",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		return false;
	}
	else {
		// mensaje
		swal({
			title: "Seleccione equipos",
			text: "Antes de guardar debe seleccionar uno o más equipos",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2000,
			showConfirmButton: false
		});
		return false;
	}
});


//===============================================================

var datatable_equiposlista = null;
var datatable_signatarioslista = null;

// navegar menu Tab principal
$('.link_menureportes').click(function () {
	switch (this.id) {
		case "reportetab_menu1":
			tabla_ot(proyecto_id);
			break;
		default:
			// return true;
			break;
	}
});


//===============================================================


function tabla_ls(proyecto_id) {
	try {
		var ruta = "/proyectosignatarioslistas/" + proyecto_id;

		if (datatable_signatarioslista != null) {
			datatable_signatarioslista.clear().draw();
			datatable_signatarioslista.ajax.url(ruta).load();
		}
		else {
			datatable_signatarioslista = $('#tabla_listasignatarios').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						tabla_ls(proyecto_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "revision",
						"defaultContent": "-"
					},
					{
						"data": "autorizado",
						"defaultContent": "-"
					},
					{
						"data": "cancelado",
						"defaultContent": "-"
					},
					{
						"data": "estado",
						"defaultContent": "-"
					},
					{
						"className": 'mostrar',
						"orderable": false,
						"data": null,
						"defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-eye"></i></button>'
					}
				],
				"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}
	}
	catch (exception) {
		tabla_ls(proyecto_id);
	}
}


$("#boton_nueva_ls").click(function () {
	swal({
		title: "¡Confirme generar nueva lista de signatarios!",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				$('#form_signatarioslista').each(function () {
					this.reset();
				});

				// Campos Hidden
				$("#signatarioslista_id").val(0);

				// checkbox autorizar
				$("#checkbox_autorizals").attr('disabled', false);

				// Consultar estado
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "/proyectosignatariosgenerarlistaestado/" + proyecto_id,
					data: {},
					cache: false,
					success: function (dato) {
						if (parseInt(dato.lista_cancelado) == 1) {
							// campos NO requeridos
							$("#checkbox_cancelals").attr('disabled', true);
							$("#proyectosignatario_canceladoobservacion").attr('disabled', true);

							// Boton guardar
							$("#boton_guardar_signatarioslista").html('Crear y guardar <i class="fa fa-save"></i>');
							$("#boton_guardar_signatarioslista").css('display', 'none'); // tipo submit
							$("#boton_guardar_signatarioslista_2").html('Crear y guardar <i class="fa fa-ban"></i>');
							$("#boton_guardar_signatarioslista_2").css('display', 'block'); // tipo boton

							// Nombre del documento
							$("#nombre_documento_visor_ls").html(dato.lista_folioot + ' Lista de signatarios' + dato.no_revision_texto);

							// mostrar modal
							$('#modal_signatarioslista').modal({ backdrop: false });

							// Consultar lista actual
							$('#visor_documento_ls').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectosignatariosconsultaractual/' + proyecto_id);
						}
						else {
							// mensaje
							swal({
								title: "No disponible",
								text: "Para generar una nueva lista de signatarios debe cancelar primero la mas reciente.",
								type: "info", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 2000,
								showConfirmButton: false
							});
						}

						// Boton
						$('#boton_nueva_ls').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de signatarios');
					},
					beforeSend: function () {
						$('#boton_nueva_ls').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Generar nueva lista de signatarios');
					},
					error: function (dato) {
						$('#boton_nueva_ls').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de signatarios');
						return false;
					}
				});//Fin ajax
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


function activa_campoautorizacion_ls(checkbox_autorizar) {
	if (parseInt($('#signatarioslista_id').val()) == 0) {
		if (checkbox_autorizar.checked) {
			$("#boton_guardar_signatarioslista_2").css('display', 'none');// tipo boton
			$("#boton_guardar_signatarioslista").css('display', 'block');// tipo submit
		}
		else {
			$("#boton_guardar_signatarioslista").css('display', 'none');// tipo submit
			$("#boton_guardar_signatarioslista_2").css('display', 'block');// tipo boton
		}
	}
}


function activa_campocancelacion_ls(checkbox_cancelar) {
	if (checkbox_cancelar.checked) {
		$('#proyectosignatario_canceladonombre').val('');
		$("#proyectosignatario_canceladoobservacion").val('');
		$("#proyectosignatario_canceladoobservacion").attr('disabled', false);
		$("#proyectosignatario_canceladoobservacion").attr('required', true);
	}
	else {
		$('#proyectosignatario_canceladonombre').val('');
		$("#proyectosignatario_canceladoobservacion").val('');
		$("#proyectosignatario_canceladoobservacion").attr('required', false);
		$("#proyectosignatario_canceladoobservacion").attr('disabled', true);
	}
}


$('#tabla_listasignatarios tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_signatarioslista.row(tr);

	listasignatarios_mostrar(
		row.data().id
		, row.data().proyecto_id
		, row.data().proyectosignatario_revision
		, row.data().proyectosignatario_autorizado
		, row.data().proyectosignatario_autorizadonombre
		, row.data().proyectosignatario_autorizadofecha
		, row.data().proyectosignatario_cancelado
		, row.data().proyectosignatario_canceladonombre
		, row.data().proyectosignatario_canceladofecha
		, row.data().proyectosignatario_canceladoobservacion
		, row.data().proyecto_folio
		, row.data().ordentrabajo_folio
	);
});


function listasignatarios_mostrar(id, proyecto_id, proyectosignatario_revision, proyectosignatario_autorizado, proyectosignatario_autorizadonombre, proyectosignatario_autorizadofecha, proyectosignatario_cancelado, proyectosignatario_canceladonombre, proyectosignatario_canceladofecha, proyectosignatario_canceladoobservacion, proyecto_folio, ordentrabajo_folio) {
	// Borrar formulario
	$('#form_signatarioslista').each(function () {
		this.reset();
	});

	// campos hidden
	$('#signatarioslista_id').val(id);

	// checkbox cancelar
	$("#checkbox_cancelals").attr('disabled', false);

	// boton guardar
	$("#boton_guardar_signatarioslista_2").html('Guardar <i class="fa fa-ban"></i>');
	$("#boton_guardar_signatarioslista_2").css('display', 'none'); // tipo boton
	$("#boton_guardar_signatarioslista").html('Guardar <i class="fa fa-save"></i>');
	$("#boton_guardar_signatarioslista").css('display', 'block'); // tipo submit

	// valida lista autorizada
	if (parseInt(proyectosignatario_autorizado) == 1) {
		$('#checkbox_autorizals').prop('checked', true);
		$("#checkbox_autorizals").prop('disabled', true);
		$('#proyectosignatario_autorizadonombre').val(proyectosignatario_autorizadonombre + ' [' + proyectosignatario_autorizadofecha + ']');
	}
	else {
		$('#checkbox_autorizals').prop('checked', false);
		$("#checkbox_autorizals").prop('disabled', false);
		$('#proyectosignatario_autorizadonombre').val('');
	}

	// valida lista cancelada
	if (parseInt(proyectosignatario_cancelado) == 1) {
		$('#checkbox_cancelals').prop('checked', true);
		$('#proyectosignatario_canceladonombre').val(proyectosignatario_canceladonombre + ' [' + proyectosignatario_canceladofecha + ']');
		$('#proyectosignatario_canceladoobservacion').val(proyectosignatario_canceladoobservacion);
		$("#proyectosignatario_canceladoobservacion").attr('disabled', false);
		$("#proyectosignatario_canceladoobservacion").attr('required', true);
	}
	else {
		$('#checkbox_cancelals').prop('checked', false);
		$('#proyectosignatario_canceladonombre').val('');
		$('#proyectosignatario_canceladoobservacion').val('');
		$("#proyectosignatario_canceladoobservacion").attr('required', false);
		$("#proyectosignatario_canceladoobservacion").attr('disabled', true);
	}

	// Nombre del documento
	if (parseInt(proyectosignatario_revision) > 0) {
		$("#nombre_documento_visor_ls").html(ordentrabajo_folio + ' Lista de signatarios Rev-' + proyectosignatario_revision);
	}
	else {
		$("#nombre_documento_visor_ls").html(ordentrabajo_folio + ' Lista de signatarios');
	}

	// mostrar modal
	if (!$('#modal_signatarioslista').is(':visible')) //Si esta cerraro [!]
	{
		$('#modal_signatarioslista').modal({ backdrop: false });
	}

	// CARGAR EN EL VISOR
	// $('#visor_documento_le').attr('src', '/proyectosignatariosconsultarhistorial/'+proyecto_id+'/'+proyectosignatario_revision);
	if (parseInt(proyectosignatario_autorizado) == 1 && parseInt(proyectosignatario_cancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		$('#visor_documento_ls').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectosignatariosconsultarhistorial/' + proyecto_id + '/' + proyectosignatario_revision);
	}
	else {
		$('#visor_documento_ls').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectosignatariosconsultarhistorial/' + proyecto_id + '/' + proyectosignatario_revision);
	}
}


$("#boton_guardar_signatarioslista").click(function () {
	swal({
		title: "¡Confirme que desea guardar!",
		text: "Lista de signatarios",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Guardar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_signatarioslista').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectosignatarios',
					data: {
						proyecto_id: $('#proyecto_id').val(),
						opcion: 1,
					},
					resetForm: false,
					success: function (dato) {
						// Actualizar tabla
						tabla_ls(proyecto_id);

						// Mostrar lista de signatarios historial
						listasignatarios_mostrar(
							dato.proyectosignatariolista.id
							, dato.proyectosignatariolista.proyecto_id
							, dato.proyectosignatariolista.proyectosignatario_revision
							, dato.proyectosignatariolista.proyectosignatario_autorizado
							, dato.proyectosignatariolista.proyectosignatario_autorizadonombre
							, dato.proyectosignatariolista.proyectosignatario_autorizadofecha
							, dato.proyectosignatariolista.proyectosignatario_cancelado
							, dato.proyectosignatariolista.proyectosignatario_canceladonombre
							, dato.proyectosignatariolista.proyectosignatario_canceladofecha
							, dato.proyectosignatariolista.proyectosignatario_canceladoobservacion
							, dato.folios[0].proyecto_folio
							, dato.folios[0].ordentrabajo_folio
						);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_signatarioslista').html('Guardar <i class="fa fa-save"></i>');
					},
					beforeSend: function () {
						$('#boton_guardar_signatarioslista').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_signatarioslista').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


// Modal FORM imagen cargando al cerrar
$("#boton_cerrar_signatarioslista").click(function () {
	// Titulo modal
	$('#nombre_documento_visor_ls').html('Lista de signatarios');

	// Visor
	$('#visor_documento_ls').attr('src', '/assets/images/cargando.gif');
});


//===============================================================


function tabla_le(proyecto_id) {
	try {
		var ruta = "/proyectoequiposlistas/" + proyecto_id;

		if (datatable_equiposlista != null) {
			datatable_equiposlista.clear().draw();
			datatable_equiposlista.ajax.url(ruta).load();
		}
		else {
			datatable_equiposlista = $('#tabla_listaequipos').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						tabla_le(proyecto_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id" 
					// },
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "revision",
						"defaultContent": "-"
					},
					{
						"data": "autorizado",
						"defaultContent": "-"
					},
					{
						"data": "cancelado",
						"defaultContent": "-"
					},
					{
						"data": "estado",
						"defaultContent": "-"
					},
					{
						"className": 'mostrar',
						"orderable": false,
						"data": null,
						"defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-eye"></i></button>'
					}
				],
				"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando datos....",
					"processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}
	}
	catch (exception) {
		tabla_le(proyecto_id);
	}
}


$("#boton_nueva_le").click(function () {
	swal({
		title: "¡Confirme generar nueva lista de equipos!",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				$('#form_equiposlista').each(function () {
					this.reset();
				});

				// Campos Hidden
				$("#equiposlista_id").val(0);

				// checkbox autorizar
				$("#checkbox_autorizale").attr('disabled', false);

				// Consultar estado
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "/proyectoequiposgenerarlistaestado/" + proyecto_id,
					data: {},
					cache: false,
					success: function (dato) {
						if (parseInt(dato.lista_cancelado) == 1) {
							// campos NO requeridos
							$("#checkbox_cancelale").attr('disabled', true);
							$("#proyectoequipo_canceladoobservacion").attr('disabled', true);

							// Boton guardar
							$("#boton_guardar_equiposlista").html('Crear y guardar <i class="fa fa-save"></i>');
							$("#boton_guardar_equiposlista").css('display', 'none'); // tipo submit
							$("#boton_guardar_equiposlista_2").html('Crear y guardar <i class="fa fa-ban"></i>');
							$("#boton_guardar_equiposlista_2").css('display', 'block'); // tipo boton

							// Nombre del documento
							$("#nombre_documento_visor_le").html(dato.lista_folioot + ' Lista de equipos' + dato.no_revision_texto);

							// mostrar modal
							$('#modal_equiposlista').modal({ backdrop: false });

							// Consultar lista actual
							$('#visor_documento_le').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoequiposconsultaractual/' + proyecto_id);
						}
						else {
							// mensaje
							swal({
								title: "No disponible",
								text: "Para generar una nueva lista de equipos debe cancelar primero la mas reciente.",
								type: "info", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 2000,
								showConfirmButton: false
							});
						}

						// Boton
						$('#boton_nueva_le').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de equipos');
					},
					beforeSend: function () {
						$('#boton_nueva_le').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Generar nueva lista de equipos');
					},
					error: function (dato) {
						$('#boton_nueva_le').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de equipos');
						return false;
					}
				});//Fin ajax
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


function activa_campoautorizacion_le(checkbox_autorizar) {
	if (parseInt($('#equiposlista_id').val()) == 0) {
		if (checkbox_autorizar.checked) {
			$("#boton_guardar_equiposlista_2").css('display', 'none');// tipo boton
			$("#boton_guardar_equiposlista").css('display', 'block');// tipo submit
		}
		else {
			$("#boton_guardar_equiposlista").css('display', 'none');// tipo submit
			$("#boton_guardar_equiposlista_2").css('display', 'block');// tipo boton
		}
	}
}


function activa_campocancelacion_le(checkbox_cancelar) {
	if (checkbox_cancelar.checked) {
		$('#proyectoequipo_canceladonombre').val('');
		$("#proyectoequipo_canceladoobservacion").val('');
		$("#proyectoequipo_canceladoobservacion").attr('disabled', false);
		$("#proyectoequipo_canceladoobservacion").attr('required', true);
	}
	else {
		$('#proyectoequipo_canceladonombre').val('');
		$("#proyectoequipo_canceladoobservacion").val('');
		$("#proyectoequipo_canceladoobservacion").attr('required', false);
		$("#proyectoequipo_canceladoobservacion").attr('disabled', true);
	}
}


$('#tabla_listaequipos tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_equiposlista.row(tr);

	listaequipos_mostrar(
		row.data().id
		, row.data().proyecto_id
		, row.data().proyectoequipo_revision
		, row.data().proyectoequipo_autorizado
		, row.data().proyectoequipo_autorizadonombre
		, row.data().proyectoequipo_autorizadofecha
		, row.data().proyectoequipo_cancelado
		, row.data().proyectoequipo_canceladonombre
		, row.data().proyectoequipo_canceladofecha
		, row.data().proyectoequipo_canceladoobservacion
		, row.data().proyecto_folio
		, row.data().ordentrabajo_folio
	);
});


function listaequipos_mostrar(id, proyecto_id, proyectoequipo_revision, proyectoequipo_autorizado, proyectoequipo_autorizadonombre, proyectoequipo_autorizadofecha, proyectoequipo_cancelado, proyectoequipo_canceladonombre, proyectoequipo_canceladofecha, proyectoequipo_canceladoobservacion, proyecto_folio, ordentrabajo_folio) {
	// Borrar formulario
	$('#form_equiposlista').each(function () {
		this.reset();
	});

	// campos hidden
	$('#equiposlista_id').val(id);

	// checkbox cancelar
	$("#checkbox_cancelale").attr('disabled', false);

	// boton guardar
	$("#boton_guardar_equiposlista_2").html('Guardar <i class="fa fa-ban"></i>');
	$("#boton_guardar_equiposlista_2").css('display', 'none'); // tipo boton
	$("#boton_guardar_equiposlista").html('Guardar <i class="fa fa-save"></i>');
	$("#boton_guardar_equiposlista").css('display', 'block'); // tipo submit

	// valida lista autorizada
	if (parseInt(proyectoequipo_autorizado) == 1) {
		$('#checkbox_autorizale').prop('checked', true);
		$("#checkbox_autorizale").prop('disabled', true);
		$('#proyectoequipo_autorizadonombre').val(proyectoequipo_autorizadonombre + ' [' + proyectoequipo_autorizadofecha + ']');
	}
	else {
		$('#checkbox_autorizale').prop('checked', false);
		$("#checkbox_autorizale").prop('disabled', false);
		$('#proyectoequipo_autorizadonombre').val('');
	}

	// valida lista cancelada
	if (parseInt(proyectoequipo_cancelado) == 1) {
		$('#checkbox_cancelale').prop('checked', true);
		$('#proyectoequipo_canceladonombre').val(proyectoequipo_canceladonombre + ' [' + proyectoequipo_canceladofecha + ']');
		$('#proyectoequipo_canceladoobservacion').val(proyectoequipo_canceladoobservacion);
		$("#proyectoequipo_canceladoobservacion").attr('disabled', false);
		$("#proyectoequipo_canceladoobservacion").attr('required', true);
	}
	else {
		$('#checkbox_cancelale').prop('checked', false);
		$('#proyectoequipo_canceladonombre').val('');
		$('#proyectoequipo_canceladoobservacion').val('');
		$("#proyectoequipo_canceladoobservacion").attr('required', false);
		$("#proyectoequipo_canceladoobservacion").attr('disabled', true);
	}

	// Nombre del documento
	if (parseInt(proyectoequipo_revision) > 0) {
		$("#nombre_documento_visor_le").html(ordentrabajo_folio + ' Lista de equipos Rev-' + proyectoequipo_revision);
	}
	else {
		$("#nombre_documento_visor_le").html(ordentrabajo_folio + ' Lista de equipos');
	}

	// mostrar modal
	if (!$('#modal_equiposlista').is(':visible')) //Si esta cerraro [!]
	{
		$('#modal_equiposlista').modal({ backdrop: false });
	}

	// CARGAR EN EL VISOR
	// $('#visor_documento_le').attr('src', '/proyectoequiposconsultarhistorial/'+proyecto_id+'/'+proyectoequipo_revision);
	if (parseInt(proyectoequipo_autorizado) == 1 && parseInt(proyectoequipo_cancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		$('#visor_documento_le').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoequiposconsultarhistorial/' + proyecto_id + '/' + proyectoequipo_revision);
	}
	else {
		$('#visor_documento_le').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoequiposconsultarhistorial/' + proyecto_id + '/' + proyectoequipo_revision);
	}
}


$("#boton_guardar_equiposlista").click(function () {
	swal({
		title: "¡Confirme que desea guardar!",
		text: "Lista de equipos",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Guardar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_equiposlista').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoequipos',
					data: {
						proyecto_id: $('#proyecto_id').val(),
						opcion: 1,
					},
					resetForm: false,
					success: function (dato) {
						// Actualizar tabla
						tabla_le(proyecto_id);

						// Mostrar lista de equipos historial
						listaequipos_mostrar(
							dato.proyectoequipolista.id
							, dato.proyectoequipolista.proyecto_id
							, dato.proyectoequipolista.proyectoequipo_revision
							, dato.proyectoequipolista.proyectoequipo_autorizado
							, dato.proyectoequipolista.proyectoequipo_autorizadonombre
							, dato.proyectoequipolista.proyectoequipo_autorizadofecha
							, dato.proyectoequipolista.proyectoequipo_cancelado
							, dato.proyectoequipolista.proyectoequipo_canceladonombre
							, dato.proyectoequipolista.proyectoequipo_canceladofecha
							, dato.proyectoequipolista.proyectoequipo_canceladoobservacion
							, dato.folios[0].proyecto_folio
							, dato.folios[0].ordentrabajo_folio
						);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_equiposlista').html('Guardar <i class="fa fa-save"></i>');
					},
					beforeSend: function () {
						$('#boton_guardar_equiposlista').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_equiposlista').html('Guardar <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


// Modal FORM imagen cargando al cerrar
$("#boton_cerrar_equiposlista").click(function () {
	// Titulo modal
	$('#nombre_documento_visor_le').html('Lista de equipos');

	// Visor
	$('#visor_documento_le').attr('src', '/assets/images/cargando.gif');
});


//===============================================================


var datatable_ordentrabajo = null;
function tabla_ot(proyecto_id) {
	try {
		var ruta = "/proyectoordentrabajotabla/" + proyecto_id;

		if (datatable_ordentrabajo != null) {
			datatable_ordentrabajo.clear().draw();
			datatable_ordentrabajo.ajax.url(ruta).load();
			// datatable_ordentrabajo.ajax.reload();
		}
		else {
			var numeroejecucion = 1;
			datatable_ordentrabajo = $('#tabla_ordentrabajo_historial').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_ordentrabajo ' + code);
						if (numeroejecucion <= 1) {
							tabla_ot(proyecto_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "numero_registro",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "folio",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "autorizado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "cancelado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "estado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "boton_mostrar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [1], //agrupar filas
				order: [[0, "ASC"]],
				ordering: false,
				processing: true,
				searching: false,
				paging: false,
				responsive: true,
				language: {
					lengthMenu: "Mostrar _MENU_ Registros",
					zeroRecords: "No se encontraron registros",
					info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
					infoEmpty: "No se encontraron registros",
					infoFiltered: "(Filtrado de _MAX_ registros)",
					emptyTable: "No hay datos disponibles en la tabla",
					loadingRecords: "Cargando datos....",
					processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					search: "Buscar",
					paginate: {
						first: "Primera",
						last: "Ultima",
						next: "Siguiente",
						previous: "Anterior"
					}
				},
				rowCallback: function (row, data, index) {
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					//  $(row).find('td:eq(12)').css('background', 'red');
					//  $(row).find('td:eq(12)').css('color', 'white');
					// }

					// $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
					// $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
					// $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
					// $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
					// $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
					// $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_ordentrabajo.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_ot(proyecto_id);
	}
}


$("#boton_generar_ot").click(function () {
	// Campos hidden
	$("#ordentrabajo_id").val(0);

	swal({
		title: "¡Confirme generar nueva orden trabajo!",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar",
		cancelButtonText: "Cancelar",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			// Enviar datos
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/proyectoordentrabajocrear/" + proyecto_id,
				data: {},
				cache: false,
				success: function (dato) {
					// Valida si creo OT
					if (parseInt(dato.ordentrabajo_id) > 0) {
						// Mostrar OT
						mostrar_ot(proyecto_id, dato.ordentrabajo_folio, 0, dato.proyectoordentrabajo_revision, 0, null, null, null, 0, null, null, null, null);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1000,
							showConfirmButton: false
						});
					}
					else {
						// mensaje
						swal({
							title: "No se creó la orden de trabajo",
							text: "" + dato.msj,
							type: "warning", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 3000,
							showConfirmButton: false
						});
					}

					// Modificar diseño boton crear OT
					$('#boton_generar_ot').html('<span class="btn-label"><i class="fa fa-refresh"></i></span> Crear nueva orden de trabajo');
				},
				beforeSend: function () {
					$('#boton_generar_ot').html('<span class="btn-label"><i class="fa fa-spin fa-refresh"></i></span> Crear nueva orden de trabajo');
				},
				error: function (dato) {
					$('#boton_generar_ot').html('<span class="btn-label"><i class="fa fa-refresh"></i></span> Crear nueva orden de trabajo');

					// mensaje
					swal({
						title: "Error",
						text: "Intentelo de nuevo",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
					return false;
				}
			});//Fin ajax
			return false;
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
});


$('#tabla_ordentrabajo_historial tbody').on('click', 'td>button.boton_mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_ordentrabajo.row(tr);


	mostrar_ot(
		row.data().proyecto_id
		, row.data().proyectoordentrabajo_folio
		, row.data().id
		, row.data().proyectoordentrabajo_revision
		, row.data().proyectoordentrabajo_autorizado
		, row.data().proyectoordentrabajo_autorizadonombre
		, row.data().proyectoordentrabajo_autorizadofecha
		, row.data().proyectoordentrabajo_observacionot
		, row.data().proyectoordentrabajo_cancelado
		, row.data().proyectoordentrabajo_canceladonombre
		, row.data().proyectoordentrabajo_canceladofecha
		, row.data().proyectoordentrabajo_canceladoobservacion
		, row.data().proyectoordentrabajo_observacionrevision
	);
});


function mostrar_ot(proyecto_id, ordentrabajofolio, ordentrabajo_id, ordentrabajo_revision, ordentrabajoautorizado, autorizadonombre, autorizadofecha, ordentrabajo_observacionot, ordentrabajocancelado, canceladonombre, canceladofecha, canceladoobservacion, ordentrabajo_observacionrevision) {
	// Borrar formulario
	$('#form_ordentrabajo').each(function () {
		this.reset();
	});


	if (parseInt(ordentrabajo_id) > 0) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectoordentrabajodatos/" + ordentrabajo_id,
			data: {},
			cache: false,
			success: function (dato) {
				// Llenar campos observaciones
				$('#proyectoordentrabajo_observacionot').val(dato.ordentrabajo[0].proyectoordentrabajo_observacionot);
				$('#proyectoordentrabajo_canceladoobservacion').val(dato.ordentrabajo[0].proyectoordentrabajo_canceladoobservacion);
				$('#proyectoordencompra_observacionrevision').val(dato.ordentrabajo[0].proyectoordentrabajo_observacionrevision);
			},
			beforeSend: function () {
				// $('#tabla_ordentrabajo_historial tbody').html('<tr><td colspan="7" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function (dato) {
				// $('#tabla_ordentrabajo_historial tbody').html('<tr><td colspan="7" style="text-align: center;">Error al consultar los datos</td></tr>');
				return false;
			}
		});//Fin ajax

		// Campos Hidden
		$('#ordentrabajo_id').val(ordentrabajo_id);
		$('#ordentrabajo_actualizaot').val(0);

		// checkbox cancelado
		$('#checkbox_cancelaot').attr('disabled', false);

		// Texto boton guardar
		$('#boton_guardar_ot').html('Guardar cambios <i class="fa fa-save"></i>');
	}
	else {
		// Campos Hidden
		$('#ordentrabajo_id').val(0);
		$('#ordentrabajo_actualizaot').val(0);

		// checkbox cancelado
		$('#checkbox_cancelaot').attr('disabled', true);

		// Texto boton guardar
		$('#boton_guardar_ot').html('Guardar y crear OT <i class="fa fa-save"></i>');
	}


	if (parseInt(ordentrabajo_revision) > 0) {
		// Campo observacion revision
		$('#proyectoordentrabajo_observacionrevision').attr('disabled', false);
		$('#proyectoordentrabajo_observacionrevision').attr('required', true);
		// $('#proyectoordencompra_observacionrevision').val(ordentrabajo_observacionrevision);
	}
	else {
		// Campo observacion revision
		$('#proyectoordentrabajo_observacionrevision').val('');
		$('#proyectoordentrabajo_observacionrevision').attr('required', false);
		$('#proyectoordentrabajo_observacionrevision').attr('disabled', true);
	}


	// Valida autorizado
	if (parseInt(ordentrabajoautorizado) == 1) {
		$('#checkbox_autorizaot').prop('checked', true);
		$('#ordentrabajo_autorizanombre').val(autorizadonombre + ' - ' + autorizadofecha);

		// bloqueo de visor
		$('#visor_menu_bloqueado_ot').css('display', 'none');
		$('#visor_contenido_bloqueado_ot').css('display', 'none');
	}
	else {
		$('#checkbox_autorizaot').prop('checked', false);
		$('#ordentrabajo_autorizanombre').val('');

		// bloqueo de visor
		$('#visor_menu_bloqueado_ot').css('display', 'block');
		$('#visor_contenido_bloqueado_ot').css('display', 'block');
	}


	// Valida Cancelado
	if (parseInt(ordentrabajocancelado) == 1) {
		$('#checkbox_cancelaot').prop('checked', true);
		$('#ordentrabajo_cancelanombre').val(canceladonombre + ' - ' + canceladofecha);

		// Campo cancelacion observacion
		$('#proyectoordentrabajo_canceladoobservacion').attr('readonly', false);
		$('#proyectoordentrabajo_canceladoobservacion').attr('required', true);
		// $('#proyectoordentrabajo_canceladoobservacion').val(canceladoobservacion);
	}
	else {
		$('#checkbox_cancelaot').prop('checked', false);
		$('#ordentrabajo_cancelanombre').val('');

		// Campo cancelacion observacion
		$('#proyectoordentrabajo_canceladoobservacion').attr('readonly', true);
		$('#proyectoordentrabajo_canceladoobservacion').attr('required', false);
		$('#proyectoordentrabajo_canceladoobservacion').val('');
	}


	// Boton actualizar	
	if (parseInt(ordentrabajo_id) > 0 && parseInt(ordentrabajocancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		$('#boton_actualizar_ot').html('Actualizar lista <i class="fa fa-refresh"></i>');
		$('#boton_actualizar_ot').removeClass('btn-default');
		$('#boton_actualizar_ot').removeClass('btn-warning');
		$('#boton_actualizar_ot').addClass('btn-info');
		$('#boton_actualizar_ot').attr('disabled', false);
	}
	else {
		$('#boton_actualizar_ot').html('Actualizar lista <i class="fa fa-refresh"></i>');
		$('#boton_actualizar_ot').removeClass('btn-info');
		$('#boton_actualizar_ot').removeClass('btn-warning');
		$('#boton_actualizar_ot').addClass('btn-default');
		$('#boton_actualizar_ot').attr('disabled', true);
	}


	// Titulo modal
	$('#nombre_documento_visor_ot').html('Orden de trabajo <b>' + ordentrabajofolio + '</b>');


	// Abrir modal visor
	if (!$('#modal_visor_ot').is(':visible')) //Si esta cerraro [!]
	{
		$('#modal_visor_ot').modal({ backdrop: false });
	}

	// Mostrar PDF
	// $('#visor_documento_ot').attr('src', '/proyectoordentrabajoconsultar/'+proyecto_id+'/'+ordentrabajo_id+'/'+0);
	// $('#visor_documento_ot').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordentrabajoconsultar/'+proyecto_id+'/'+ordentrabajo_id+'/'+0);

	// CARGAR EN EL VISOR
	if (parseInt(proyecto_perfil) > 0 && parseInt(ordentrabajoautorizado) == 1 && parseInt(ordentrabajocancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		// Iframe
		setTimeout(function () {
			$('#visor_documento_ot').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordentrabajoconsultar/' + proyecto_id + '/' + ordentrabajo_id + '/' + 0);
		}, 500);
	}
	else {
		// viewerpdf
		setTimeout(function () {
			$('#visor_documento_ot').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordentrabajoconsultar/' + proyecto_id + '/' + ordentrabajo_id + '/' + 0);
		}, 500);
	}
}


function actualiza_datosot() {
	swal({
		title: "¡Confirme actualizar el historial de los datos de esta orden de trabajo!",
		text: "Mostrará vista previa con datos actualizados en esta orden de trabajo pero conservando el numero de folio. Al terminar precione el boton guardar cambios para reeemplazar el historial permanentemente.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar",
		cancelButtonText: "Cancelar",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			//Mostrar PDF en el visor
			$('#visor_documento_ot').attr('src', '/assets/images/cargando.gif');
			// $('#visor_documento_ot').attr('src', '/proyectoordentrabajoactualizar/'+proyecto_id+'/'+$("#ordentrabajo_id").val());

			// esperamos 2 segundos para cargar el nuevo PDF
			setTimeout(function () {
				// Campos Hidden
				$('#ordentrabajo_actualizaot').val(1);
				$('#boton_actualizar_ot').removeClass('btn-info');
				$('#boton_actualizar_ot').addClass('btn-warning');
				$('#boton_actualizar_ot').html('Lista actualizada <i class="fa fa-check"></i>');

				// Cargar PDF
				// $('#visor_documento_ot').attr('src', '/assets/plugins/ViewerJS/index.html#/proyectoordentrabajoactualizar/'+proyecto_id+'/'+$("#ordentrabajo_id").val());
				$('#visor_documento_ot').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordentrabajoactualizar/' + proyecto_id + '/' + $("#ordentrabajo_id").val());


				// mensaje
				swal({
					title: "Correcto",
					text: "Al finalizar precione el boton guardar cambios",
					type: "success", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}, 1000);
		}
		else {
			// Campos Hidden
			$('#ordentrabajo_actualizaot').val(0);

			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function activa_campocancelacion_ot(checkbox_cancelaot) {
	if (checkbox_cancelaot.checked) {
		$('#proyectoordentrabajo_canceladoobservacion').attr('readonly', false);
		$('#proyectoordentrabajo_canceladoobservacion').attr('required', true);
	}
	else {
		$('#proyectoordentrabajo_canceladoobservacion').attr('readonly', true);
		$('#proyectoordentrabajo_canceladoobservacion').attr('required', false);
		$('#proyectoordentrabajo_canceladoobservacion').val('');
	}
}


// Modal FORM imagen cargando al cerrar
$("#modalvisor_ot_boton_cerrar").click(function () {
	// Titulo modal
	$('#nombre_documento_visor_ot').html('Orden de trabajo');

	// Campos Hidden
	$('#ordentrabajo_id').val(0);
	$('#visor_documento_ot').attr('src', '/assets/images/cargando.gif');
});


$("#boton_guardar_ot").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		var textoboton = '';
		var textobotonguardando = '';
		var textoguardar = '';

		if (parseInt($("#ordentrabajo_id").val()) > 0) {
			textoboton = 'Guardar cambios <i class="fa fa-save"></i>';
			textobotonguardando = 'Guardar cambios <i class="fa fa-spin fa-spinner"></i>';

			if (parseInt($("#ordentrabajo_actualizaot").val()) == 1) {
				textoguardar = 'Modificar los datos y el historial de esta de la orden de trabajo';
			}
			else {
				textoguardar = 'Guardar cambios de la orden de trabajo';
			}
		}
		else {
			textoguardar = 'Crear nueva orden de trabajo, una vez creada esta no se pueden eliminar solo cancelar.';
			textoboton = 'Guardar y crear OT <i class="fa fa-save"></i>';
			textobotonguardando = 'Guardar y crear OT <i class="fa fa-spin fa-spinner"></i>';
		}

		// Valida envio de datos
		swal({
			title: "¡Confirme guardar!",
			text: "" + textoguardar,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				//Mostrar PDF en el visor
				$('#visor_documento_ot').attr('src', '/assets/images/cargando.gif');

				// enviar datos
				$('#form_ordentrabajo').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoordentrabajo',
					data: {
						proyecto_id: $('#proyecto_id').val()
					},
					resetForm: false,
					success: function (dato) {
						// actualiza tabla OT
						tabla_ot(proyecto_id);

						mostrar_ot(
							dato.ordentrabajo.proyecto_id
							, dato.ordentrabajo.proyectoordentrabajo_folio
							, dato.ordentrabajo.id
							, dato.ordentrabajo.proyectoordentrabajo_revision
							, dato.ordentrabajo.proyectoordentrabajo_autorizado
							, dato.ordentrabajo.proyectoordentrabajo_autorizadonombre
							, dato.ordentrabajo.proyectoordentrabajo_autorizadofecha
							, dato.ordentrabajo.proyectoordentrabajo_observacionot
							, dato.ordentrabajo.proyectoordentrabajo_cancelado
							, dato.ordentrabajo.proyectoordentrabajo_canceladonombre
							, dato.ordentrabajo.proyectoordentrabajo_canceladofecha
							, dato.ordentrabajo.proyectoordentrabajo_canceladoobservacion
							, dato.ordentrabajo.proyectoordentrabajo_observacionrevision
						);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 2000,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_ot').html(textoboton);
					},
					beforeSend: function () {
						$('#boton_guardar_ot').html(textobotonguardando);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_ot').html(textoboton);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//===============================================================

var datatable_ordencompra = null;
function tabla_oc(proyecto_id) {
	try {
		var ruta = "/proyectoordencompratabla/" + proyecto_id;

		if (datatable_ordencompra != null) {
			datatable_ordencompra.clear().draw();
			datatable_ordencompra.ajax.url(ruta).load();
			// datatable_ordencompra.ajax.reload();
		}
		else {
			var numeroejecucion = 1;
			datatable_ordencompra = $('#tabla_ordencompra_historial').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_ordencompra ' + code);
						if (numeroejecucion <= 1) {
							tabla_oc(proyecto_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "numero_registro",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "folio",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "proveedor",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "revisado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "autorizado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "facturado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "cancelado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "estado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "boton_mostrar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [1], //agrupar filas
				order: [[0, "ASC"]],
				ordering: false,
				processing: true,
				searching: false,
				paging: false,
				responsive: true,
				language: {
					lengthMenu: "Mostrar _MENU_ Registros",
					zeroRecords: "No se encontraron registros",
					info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
					infoEmpty: "No se encontraron registros",
					infoFiltered: "(Filtrado de _MAX_ registros)",
					emptyTable: "No hay datos disponibles en la tabla",
					loadingRecords: "Cargando datos....",
					processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					search: "Buscar",
					paginate: {
						first: "Primera",
						last: "Ultima",
						next: "Siguiente",
						previous: "Anterior"
					}
				},
				rowCallback: function (row, data, index) {
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					//  $(row).find('td:eq(12)').css('background', 'red');
					//  $(row).find('td:eq(12)').css('color', 'white');
					// }

					// $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
					// $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
					// $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
					// $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
					// $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
					// $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_ordencompra.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_oc(proyecto_id);
	}
}


$("#boton_proveedores_oc").click(function () {
	// Select Cotizacion
	$('#ordencompra_cotizacion_id').html('<option value="">&nbsp;</option>');
	$('#ordencompra_proveedorcotizacion_id').html('<option value="">&nbsp;</option>');


	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoordencompraproveedores/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			if (parseInt(dato.ordencompra_totalproveedores) > 0) {
				// Select proveedores
				$('#ordencompra_selectproveedor_id').html(dato.ordencompra_listaproveedores);

				// Abrir modal
				if ($('#modal_oc_proveedores').is(':visible')) //Si esta cerraro [!]
				{
					$('#modal_oc_proveedores').modal('hide');
					$('#modal_oc_proveedores').modal({ backdrop: false });
				}
				else {
					$('#modal_oc_proveedores').modal({ backdrop: false });
				}

				$('#boton_generar_oc_proveedor').html('Generar OC para este proveedor <i class="fa fa-user"></i>');
			}
			else {
				// Select proveedores
				$('#ordencompra_selectproveedor_id').html('<option value="">&nbsp;</option>');

				// mensaje
				swal({
					title: "No se puede generar orden de compra",
					text: "" + dato.msj,
					type: "warning", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 3000,
					showConfirmButton: false
				});
			}

			// Modificar diseño boton crear OT
			$('#boton_proveedores_oc').html('<span class="btn-label"><i class="fa fa-refresh"></i></span> Generar nueva orden de compra');
		},
		beforeSend: function () {
			$('#boton_proveedores_oc').html('<span class="btn-label"><i class="fa fa-spin fa-refresh"></i></span> Generar nueva orden de compra');
		},
		error: function (dato) {
			$('#boton_proveedores_oc').html('<span class="btn-label"><i class="fa fa-refresh"></i></span> Generar nueva orden de compra');

			// Select proveedores
			$('#ordencompra_selectproveedor_id').html('<option value="">&nbsp;</option>');

			// mensaje
			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});
			return false;
		}
	});//Fin ajax
	return false;
});


function proveedor_cotizacion(proveedor_id, seleccionado) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoordencompraproveedorcotizacion/" + proveedor_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Select cotizacion
			$('#ordencompra_cotizacion_id').html(dato.select_cotizacion_opciones);
			$('#ordencompra_proveedorcotizacion_id').html(dato.select_cotizacion_opciones);

			$('#ordencompra_cotizacion_id').val(seleccionado);
			$('#ordencompra_proveedorcotizacion_id').val(seleccionado);
		},
		// beforeSend: function()
		// {
		// 	$('#boton_proveedores_oc').html('<span class="btn-label"><i class="fa fa-spin fa-refresh"></i></span> Generar nueva orden de compra');
		// },
		error: function (dato) {
			// Select Cotizacion
			$('#ordencompra_cotizacion_id').html('<option value="">&nbsp;</option>');
			$('#ordencompra_proveedorcotizacion_id').html('<option value="">&nbsp;</option>');

			// mensaje
			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});
			return false;
		}
	});//Fin ajax

	return false;
}


$("#boton_generar_oc_proveedor").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		var proveedor_estado = $('#ordencompra_selectproveedor_id').val().split("*");
		var proveedor_id = parseInt(proveedor_estado[0]);
		var proveedor_ult_oc_cancelada = parseInt(proveedor_estado[1]);
		var proveedor_ult_oc_facturado = parseInt(proveedor_estado[2]);
		var proveedor_folionuevo = proveedor_estado[3];
		var ordencompra_revision = proveedor_estado[4];

		// Valida si crear OC
		if (proveedor_ult_oc_cancelada == 1 && proveedor_ult_oc_facturado == 0) //Cancelada o facturado
		{
			// cerrar modal
			// $('#modal_oc_proveedores').modal().hide();
			// $('#modal_visor_oc').removeData('bs.modal')
			$('#modal_oc_proveedores').modal('hide');
			// $('#modal_oc_proveedores').removeData('bs.modal')

			// Mostrar vista OC
			mostrar_oc(proyecto_id, proveedor_id, $('#ordencompra_cotizacion_id').val(), 0, proveedor_folionuevo, ordencompra_revision, 0, 0, null, null, 0, null, null, 0, null, null, 0, null, null, null, null, 0);
		}
		else {
			if (proveedor_ult_oc_facturado == 1) //Facturado
			{
				// mensaje
				swal({
					title: "No disponible",
					text: "La Orden de Compra más reciente de este proveedor ya fue facturada.",
					type: "info", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 4000,
					showConfirmButton: false
				});
			}
			else {
				// mensaje
				swal({
					title: "No disponible",
					text: "La Orden de Compra más reciente de este proveedor no ha sido cancelada.",
					type: "info", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 4000,
					showConfirmButton: false
				});
			}
		}

		return false;
	}
});


$('#tabla_ordencompra_historial tbody').on('click', 'td>button.boton_mostrar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_ordencompra.row(tr);

	// Mostrar OC
	mostrar_oc(
		row.data().proyecto_id
		, row.data().proveedor_id
		, row.data().servicio_id
		, row.data().id
		, row.data().proyectoordencompra_folio
		, row.data().proyectoordencompra_revision
		, row.data().proyectoordencompra_tipolista
		, row.data().proyectoordencompra_revisado
		, row.data().proyectoordencompra_revisadonombre
		, row.data().proyectoordencompra_revisadofecha
		, row.data().proyectoordencompra_autorizado
		, row.data().proyectoordencompra_autorizadonombre
		, row.data().proyectoordencompra_autorizadofecha
		, row.data().proyectoordencompra_cancelado
		, row.data().proyectoordencompra_canceladonombre
		, row.data().proyectoordencompra_canceladofecha
		, row.data().proyectoordencompra_facturado
		, row.data().proyectoordencompra_facturadonombre
		, row.data().proyectoordencompra_facturadofecha
		, row.data().proyectoordencompra_facturadomonto
		, row.data().proyectoordencompra_facturadopdf
		, row.data().activo
	);
});


function mostrar_oc(proyecto_id, proveedor_id, cotizacion_id, ordencompra_id, ordencomprafolio, ordencompra_revision, ordencompra_tipolista, ordencomprarevisado, revisadonombre, revisadofecha, ordencompraautorizado, autorizadonombre, autorizadofecha, ordencompracancelado, canceladonombre, canceladofecha, ordencomprafacturado, facturadonombre, facturadofecha, facturadomonto, facturadopdf, activo) {
	// Borrar formulario
	$('#form_ordencompra').each(function () {
		this.reset();
	});


	// Select cotizacion
	proveedor_cotizacion(proveedor_id, cotizacion_id);


	if (parseInt(ordencompra_id) > 0) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectoordencompradatos/" + ordencompra_id,
			data: {},
			cache: false,
			success: function (dato) {
				// Llenar campos observaciones
				$('#proyectoordencompra_observacionoc').val(dato.ordencompra[0].proyectoordencompra_observacionoc);
				$('#proyectoordencompra_canceladoobservacion').val(dato.ordencompra[0].proyectoordencompra_canceladoobservacion);
				$('#proyectoordencompra_observacionrevision').val(dato.ordencompra[0].proyectoordencompra_observacionrevision);
			},
			beforeSend: function () {
				// $('#tabla_ordentrabajo_historial tbody').html('<tr><td colspan="7" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function (dato) {
				// $('#tabla_ordentrabajo_historial tbody').html('<tr><td colspan="7" style="text-align: center;">Error al consultar los datos</td></tr>');
				return false;
			}
		});//Fin ajax

		// Campos Hidden
		$('#ordencompra_id').val(ordencompra_id);
		$('#ordencompra_actualizaoc').val(0);


		// checkbox autorizado
		$('#checkbox_autorizaoc').attr('disabled', false);


		// checkbox cancelado
		$('#checkbox_cancelaoc').attr('disabled', false);


		// checkbox cancelado
		$('#checkbox_facturaoc').attr('disabled', false);


		// Texto boton guardar
		$('#boton_guardar_oc').html('Guardar cambios <i class="fa fa-save"></i>');
	}
	else {
		// Campos Hidden
		$('#ordencompra_id').val(0);
		$('#ordencompra_actualizaoc').val(0);


		// checkbox revisado
		$('#checkbox_revisaoc').prop('checked', false);


		// checkbox autorizado
		$('#checkbox_autorizaoc').prop('checked', false);
		$('#checkbox_autorizaoc').attr('disabled', true);


		// checkbox cancelado
		$('#checkbox_cancelaoc').prop('checked', false);
		$('#checkbox_cancelaoc').attr('disabled', true);


		// checkbox cancelado
		$('#checkbox_facturaoc').prop('checked', false);
		$('#checkbox_facturaoc').attr('disabled', true);


		// Texto boton guardar
		$('#boton_guardar_oc').html('Crear y guardar OC <i class="fa fa-save"></i>');
	}


	// Valida version
	if (parseInt(ordencompra_revision) > 0) {
		// Campo observacion revision
		$('#proyectoordencompra_observacionrevision').attr('disabled', false);
		$('#proyectoordencompra_observacionrevision').attr('required', true);
		// $('#proyectoordencompra_observacionrevision').val(ordencompra_observacionrevision);
	}
	else {
		// Campo observacion revision
		$('#proyectoordencompra_observacionrevision').val('');
		$('#proyectoordencompra_observacionrevision').attr('required', false);
		$('#proyectoordencompra_observacionrevision').attr('disabled', true);
	}


	// Valida revisado
	if (parseInt(ordencomprarevisado) == 1) {
		$('#checkbox_revisaoc').prop('checked', true);
		$('#ordencompra_revisanombre').val(revisadonombre + ' [' + revisadofecha + ']');
	}
	else {
		$('#checkbox_revisaoc').prop('checked', false);
		$('#ordencompra_revisanombre').val('');
	}


	// Valida autorizado
	if (parseInt(ordencompraautorizado) == 1) {
		$('#checkbox_autorizaoc').prop('checked', true);
		$('#ordencompra_autorizanombre').val(autorizadonombre + ' [' + autorizadofecha + ']');
	}
	else {
		$('#checkbox_autorizaoc').prop('checked', false);
		$('#ordencompra_autorizanombre').val('');
	}


	// Valida Cancelado
	if (parseInt(ordencompracancelado) == 1) {
		$('#checkbox_cancelaoc').prop('checked', true);
		$('#proyectoordencompra_canceladonombre').val(canceladonombre + ' [' + canceladofecha + ']');

		// Campo cancelacion observacion
		$('#proyectoordencompra_canceladoobservacion').attr('readonly', false);
		$('#proyectoordencompra_canceladoobservacion').attr('required', true);
		// $('#proyectoordencompra_canceladoobservacion').val(canceladoobservacion);
	}
	else {
		$('#checkbox_cancelaoc').prop('checked', false);
		$('#proyectoordencompra_canceladonombre').val('');

		// Campo cancelacion observacion
		$('#proyectoordencompra_canceladoobservacion').attr('readonly', true);
		$('#proyectoordencompra_canceladoobservacion').attr('required', false);
		$('#proyectoordencompra_canceladoobservacion').val('');
	}


	// Valida facturacion
	if (parseInt(ordencomprafacturado) == 1) {
		$('#checkbox_facturaoc').prop('checked', true);
		$("#checkbox_factura").tooltip('enable');


		if (parseInt(activo) == 1) //Superusuario o financiero
		{
			$('#checkbox_facturaoc').prop('disabled', false);
		}
		else {
			$('#checkbox_facturaoc').prop('disabled', true);
		}


		$('#proyectoordencompra_facturadonombre').val(facturadonombre + ' [' + facturadofecha + ']');


		$('#proyectoordencompra_facturadomonto').attr('required', false);
		$('#proyectoordencompra_facturadomonto').val(facturadomonto);
		$('#proyectoordencompra_facturadomonto').attr('readonly', true);


		$('#facturadopdf').val('');
		$('#facturadopdf').attr('required', false);
		$('#facturadopdf').attr('disabled', true);


		$('#boton_mostrar_factura').attr('disabled', false);
		$('#boton_mostrar_factura').removeClass('btn-secondary');
		$('#boton_mostrar_factura').addClass('btn-success');
		$('#boton_mostrar_factura').addClass('botonverde');
		$('#boton_mostrar_factura').html('<i class="fa fa-file-pdf-o fa-3x"></i>');
	}
	else {
		$('#checkbox_facturaoc').prop('checked', false);
		$("#checkbox_factura").tooltip('disable');

		$('#proyectoordencompra_facturadonombre').val('');


		$('#proyectoordencompra_facturadomonto').attr('required', false);
		$('#proyectoordencompra_facturadomonto').val('');
		$('#proyectoordencompra_facturadomonto').attr('readonly', true);


		$('#facturadopdf').val('');
		$('#facturadopdf').attr('required', false);
		$('#facturadopdf').attr('disabled', true);


		$('#boton_mostrar_factura').removeClass('btn-success');
		$('#boton_mostrar_factura').removeClass('botonverde');
		$('#boton_mostrar_factura').addClass('btn-secondary');
		$('#boton_mostrar_factura').html('<i class="fa fa-ban fa-3x"></i>');
		$('#boton_mostrar_factura').attr('disabled', true);
	}


	// Boton actualizar
	// if (parseInt(ordencompra_id) > 0 && parseInt(ordencompracancelado) == 0 && parseInt(proyecto_bloqueado) == 0)
	if (parseInt(ordencompra_id) >= 0 && parseInt(ordencompracancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		$('#boton_actualizar_oc').html('Actualizar lista <i class="fa fa-refresh"></i>');
		$('#boton_actualizar_oc').removeClass('btn-default');
		$('#boton_actualizar_oc').removeClass('btn-warning');
		$('#boton_actualizar_oc').addClass('btn-info');
		$('#boton_actualizar_oc').attr('disabled', false);
	}
	else {
		$('#boton_actualizar_oc').html('Actualizar lista <i class="fa fa-refresh"></i>');
		$('#boton_actualizar_oc').removeClass('btn-info');
		$('#boton_actualizar_oc').removeClass('btn-warning');
		$('#boton_actualizar_oc').addClass('btn-default');
		$('#boton_actualizar_oc').attr('disabled', true);
	}


	// Llenar campos Hidden
	$('#ordencompra_proveedor_id').val(proveedor_id);
	$('#proyectoordencompra_tipolista_0').val(0);
	$('#proyectoordencompra_tipolista_1').val(1);


	if (parseInt(ordencompra_tipolista) == 0) {
		$("#proyectoordencompra_tipolista_0").prop('checked', true);
	}
	else {
		$("#proyectoordencompra_tipolista_1").prop('checked', true);
	}


	// Titulo modal
	$('#nombre_documento_visor_oc').html('Orden de compra <b>' + ordencomprafolio + '</b>');


	// Abrir modal visor
	if (!$('#modal_visor_oc').is(':visible')) //Si esta cerraro [!]
	{
		$('#modal_visor_oc').modal({ backdrop: false });
	}


	// Mostrar PDF
	// $('#visor_documento_oc').attr('src', '/proyectoordencompramostrar/'+proyecto_id+'/'+proveedor_id+'/'+ordencompra_id);
	// $('#visor_documento_oc').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordencompramostrar/'+proyecto_id+'/'+proveedor_id+'/'+ordencompra_id);


	// CARGAR EN EL VISOR
	if ((parseInt(proyecto_perfil) == 1 || parseInt(proyecto_perfil) == 3) && parseInt(ordencompraautorizado) == 1 && parseInt(ordencompracancelado) == 0 && parseInt(proyecto_bloqueado) == 0) {
		// Iframe
		setTimeout(function () {
			$('#visor_documento_oc').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordencompramostrar/' + proyecto_id + '/' + proveedor_id + '/' + cotizacion_id + '/' + ordencompra_id);
		}, 500);
	}
	else {
		// viewerpdf
		setTimeout(function () {
			$('#visor_documento_oc').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordencompramostrar/' + proyecto_id + '/' + proveedor_id + '/' + cotizacion_id + '/' + ordencompra_id);
		}, 500);
	}
}


function actualiza_datosoc() {
	swal({
		title: "¡Confirme actualizar el historial de los datos de esta orden de compra!",
		text: "Mostrará vista previa con datos actualizados en esta orden de compra pero conservando el numero de folio. Al terminar precione el boton guardar cambios para reeemplazar el historial permanentemente.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar",
		cancelButtonText: "Cancelar",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			//Mostrar PDF en el visor
			$('#visor_documento_oc').attr('src', '/assets/images/cargando.gif');
			// $('#visor_documento_oc').attr('src', '/proyectoordencompraactualizar/'+proyecto_id+'/'+$("#ordencompra_proveedor_id").val()+'/'+$("#ordencompra_id").val());

			// esperamos 2 segundos para cargar el nuevo PDF
			setTimeout(function () {
				// Campos Hidden
				$('#ordencompra_actualizaoc').val(1);
				$('#boton_actualizar_oc').removeClass('btn-info');
				$('#boton_actualizar_oc').addClass('btn-warning');
				$('#boton_actualizar_oc').html('Lista actualizada <i class="fa fa-check"></i>');

				$('#visor_documento_oc').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoordencompraactualizar/' + proyecto_id + '/' + $("#ordencompra_proveedor_id").val() + '/' + $("#ordencompra_proveedorcotizacion_id").val() + '/' + $("#ordencompra_id").val() + '/' + $("input[name='proyectoordencompra_tipolista']:checked").val());
				// $('#visor_documento_oc').attr('src', '/assets/plugins/ViewerJS/index.html#/proyectoordencompraactualizar/'+proyecto_id+'/'+$("#ordencompra_proveedor_id").val()+'/'+$("#ordencompra_id").val());

				// mensaje
				swal({
					title: "Correcto",
					text: "Al finalizar precione el boton guardar cambios",
					type: "success", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}, 2000);
		}
		else {
			// Campos Hidden
			$('#ordencompra_actualizaoc').val(0);

			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function activa_campocancelacion_oc(checkbox_cancelaoc) {
	if (checkbox_cancelaoc.checked) {
		$('#proyectoordencompra_canceladoobservacion').attr('readonly', false);
		$('#proyectoordencompra_canceladoobservacion').attr('required', true);
	}
	else {
		$('#proyectoordencompra_canceladoobservacion').attr('readonly', true);
		$('#proyectoordencompra_canceladoobservacion').attr('required', false);
		$('#proyectoordencompra_canceladoobservacion').val('');
	}
}


function activa_campofactura_oc(checkbox_facturaoc) {
	if (checkbox_facturaoc.checked) {
		$('#proyectoordencompra_facturadomonto').attr('readonly', false);
		$('#proyectoordencompra_facturadomonto').attr('required', true);


		$('#facturadopdf').attr('disabled', false);
		$('#facturadopdf').attr('required', true);
	}
	else {
		$('#checkbox_facturaoc').prop('checked', false);
		$('#proyectoordencompra_facturadonombre').val('');


		$('#proyectoordencompra_facturadomonto').val('');
		$('#proyectoordencompra_facturadomonto').attr('required', false);
		$('#proyectoordencompra_facturadomonto').attr('readonly', true);


		$('#facturadopdf').val('');
		$('#facturadopdf').attr('required', false);
		$('#facturadopdf').attr('disabled', true);


		$('#boton_mostrar_factura').removeClass('btn-success');
		$('#boton_mostrar_factura').removeClass('botonverde');
		$('#boton_mostrar_factura').addClass('btn-secondary');
		$('#boton_mostrar_factura').html('<i class="fa fa-ban fa-3x"></i>');
		$('#boton_mostrar_factura').attr('disabled', true);
	}
}


function mostrar_factura() {
	window.open(
		'/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoordencomprafactura/' + $('#ordencompra_id').val(),
		'nombreventana',
		'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=800'
	);
}


// Modal imagen cargando
$("#modalvisor_oc_boton_cerrar").click(function () {
	// $('#modal_visor_oc').removeData('bs.modal')

	// Titulo modal
	$('#nombre_documento_visor_oc').html('Orden de compra');

	// Campos Hidden
	$('#ordencompra_id').val(0);
	$('#ordencompra_proveedor_id').val(0);
	$('#visor_documento_oc').attr('src', '/assets/images/cargando.gif');
});


$("#boton_guardar_oc").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		var textoboton = '';
		var textobotonguardando = '';
		var textoguardar = '';

		if (parseInt($("#ordencompra_id").val()) > 0) {
			textoboton = 'Guardar cambios <i class="fa fa-save"></i>';
			textobotonguardando = 'Guardar cambios <i class="fa fa-spin fa-spinner"></i>';

			if (parseInt($("#ordencompra_actualizaoc").val()) == 1) {
				textoguardar = 'Modificar los datos y el historial de esta de la orden de compra';
			}
			else {
				textoguardar = 'Guardar cambios de la orden de compra';
			}
		}
		else {
			textoguardar = 'Crear nueva orden de compra, una vez creada esta no se pueden eliminar solo cancelar.';
			textoboton = 'Crear y guardar OC <i class="fa fa-save"></i>';
			textobotonguardando = 'Crear y guardar OC <i class="fa fa-spin fa-spinner"></i>';
		}

		// Valida envio de datos
		swal({
			title: "¡Confirme guardar!",
			text: "" + textoguardar,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				//Mostrar PDF en el visor
				$('#visor_documento_oc').attr('src', '/assets/images/cargando.gif');

				// enviar datos
				$('#form_ordencompra').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoordencompra',
					data: {
						proyecto_id: proyecto_id
					},
					resetForm: false,
					success: function (dato) {
						// actualiza tabla OC
						tabla_oc(proyecto_id);


						// Mostrar OC
						mostrar_oc(
							dato.ordencompra.proyecto_id
							, dato.ordencompra.proveedor_id
							, dato.ordencompra.servicio_id
							, dato.ordencompra.id
							, dato.ordencompra.proyectoordencompra_folio
							, dato.ordencompra.proyectoordencompra_revision
							, dato.ordencompra.proyectoordencompra_tipolista
							, dato.ordencompra.proyectoordencompra_revisado
							, dato.ordencompra.proyectoordencompra_revisadonombre
							, dato.ordencompra.proyectoordencompra_revisadofecha
							, dato.ordencompra.proyectoordencompra_autorizado
							, dato.ordencompra.proyectoordencompra_autorizadonombre
							, dato.ordencompra.proyectoordencompra_autorizadofecha
							, dato.ordencompra.proyectoordencompra_cancelado
							, dato.ordencompra.proyectoordencompra_canceladonombre
							, dato.ordencompra.proyectoordencompra_canceladofecha
							, dato.ordencompra.proyectoordencompra_facturado
							, dato.ordencompra.proyectoordencompra_facturadonombre
							, dato.ordencompra.proyectoordencompra_facturadofecha
							, dato.ordencompra.proyectoordencompra_facturadomonto
							, dato.ordencompra.proyectoordencompra_facturadopdf
							, dato.activo
						);


						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_oc').html(textoboton);
					},
					beforeSend: function () {
						$('#boton_guardar_oc').html(textobotonguardando);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_oc').html(textoboton);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//===============================================================

var signatarios_opciones = [];
var parametros_opciones = [];
var bitacora_responsable_id = 0;
var bitacora_responsable_nombre = '';


function consulta_menuparametros_evidencias(proyecto_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciaparametros/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Catalogo de partidas químicos
			$('#catreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);
			$('#planoscatreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);
			$('#nombrecarpetacatreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);

			// Pintar menu opciones
			$('#lista_menu_parametros_evidencia').html(dato.opciones);

			if (parseInt(dato.opciones_total) > 0) {
				// Mostrar botones de AGREGAR
				$('#boton_nuevo_documentoevidencia').css('display', 'block');
				$('#boton_nuevo_fotosevidencia').css('display', 'block');
				$('#boton_nuevo_planosevidencia').css('display', 'block');

				// Activar primer parametro menu
				$.each($('.menulista_evidencia'), function (key, value) {
					if (key == 0) {
						$(this).click();
						$(this).addClass('active');
						// alert(this.id);
					}
					else {
						$(this).removeClass('active');
					}
				});
			}
			else {
				// Ocultar botones de AGREGAR
				$('#boton_nuevo_documentoevidencia').css('display', 'none');
				$('#boton_nuevo_fotosevidencia').css('display', 'none');
				$('#boton_nuevo_planosevidencia').css('display', 'none');

				// Vaciar contenidos
				$('#tabla_evidenciadocumentos tbody').html('');
				$('#evidencia_galeria_fotos').html('');
				$('#evidencia_galeria_planos').html('');
			}
		},
		beforeSend: function () {
			$('#lista_menu_parametros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></li>');
		},
		error: function (dato) {
			$('#lista_menu_parametros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">Error al cargar los parametros</li>');
			return false;
		}
	});//Fin ajax
}


function consulta_evidencias(proyecto_id, agente_id, agente_nombre, menu, nombre_rol) {
	// Id del parametro elegido
	evidencia_agente_id = parseInt(agente_id);


	// Eliminar active de todo el menu evidencia
	if (menu != 0) {
		$('.menulista_evidencia').removeClass('active');
	}


	// Activar menu
	if (menu != 0) {
		$.each($('.menulista_evidencia'), function (key, value) {
			if (this == menu) {
				$(this).addClass('active');
			}
		});
	}


	// Nombre parametro
	if (evidencia_agente_id == 15) {
		evidencia_agente = "Químicos";
	}
	else {
		evidencia_agente = agente_nombre;
	}


	// Nombre parametro elegido
	$('#evidencia_agente_titulo').html(evidencia_agente);


	// VALIDA OPCION
	if (evidencia_agente_id == 1000) // PUNTOS REALES
	{
		// ACTIVAR SECCION
		$('#seccion_proyectoevidencias').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'none');
		$('#seccion_proyectopuntosreales').css('display', 'block');

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectopuntosrealeslista/" + proyecto_id,
			data: {},
			cache: false,
			success: function (dato) {
				$('#tabla_proyectoevidencia_puntosreales tbody').html(dato.filas);


				if (parseInt(dato.puntosreales_total) > 0) {
					$('#boton_imprimir_proyectopuntosreales').css('display', 'inline-block');
				}
				else {
					$('#boton_imprimir_proyectopuntosreales').css('display', 'none');
				}


				if (parseInt(dato.puntosreales_estado) == 0) {
					$('#boton_bloquear_puntosreales').removeClass('btn-success');
					$('#boton_bloquear_puntosreales').addClass('btn-warning');
					$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Puntos reales bloqueado para edición');

					$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-ban"></i>');
					$('#boton_guardar_puntosreales').attr('disabled', true);
				}
				else {
					$('#boton_bloquear_puntosreales').removeClass('btn-warning');
					$('#boton_bloquear_puntosreales').addClass('btn-success');
					$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');

					$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
					$('#boton_guardar_puntosreales').attr('disabled', false);
				}
			},
			beforeSend: function () {
				$('#tabla_proyectoevidencia_puntosreales tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 50px!important;"></i></td></tr>');
			},
			error: function (dato) {
				$('#tabla_proyectoevidencia_puntosreales tbody').html('<tr><td colspan="4" style="text-align: center;">Error al cargar los agentes</td></tr>');
				return false;
			}
		});//Fin ajax
	}
	else if (evidencia_agente_id == 2000) // BITACORA
	{
		// ACTIVAR SECCION
		$('#seccion_proyectoevidencias').css('display', 'none');
		$('#seccion_proyectopuntosreales').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'block');


		tabla_bitacora(proyecto_id);
	}
	else //EVIDENCIAS
	{
		// ACTIVAR SECCION
		$('#seccion_proyectopuntosreales').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'none');
		$('#seccion_proyectoevidencias').css('display', 'block');


		// // Seccion planos si es agente
		// if (evidencia_agente_id > 0)
		// {
		// 	$('#tabmenu_evidencia_3').css('display', 'block'); // Mostrar menu planos
		// }
		// else // informacion general
		// {
		// 	$('#tabmenu_evidencia_3').css('display', 'none'); // Ocultar menu planos
		// }


		// Activar menu evidencia Documentos
		$('#tabmenu_evidencia_1').click();


		// Consultar documentos
		consulta_evidencia_documentos(proyecto_id, agente_id, agente_nombre);


		// Consultar Fotos
		consulta_evidencia_fotos(proyecto_id, agente_id, agente_nombre);


		// Consultar planos
		consulta_evidencia_planos(proyecto_id, agente_id, agente_nombre);


		// Activa botones NUEVO / GUARDAR
		if (nombre_rol == "CoordinadorPsicosocial" || nombre_rol == "Psicólogo") {
			if (agente_nombre == "Factores psicosociales" && parseInt(proyecto_bloqueado) == 0) {
				$('#boton_nuevo_documentoevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_documento').css('display', 'block');

				$('#boton_nuevo_fotosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_fotos').css('display', 'block');

				$('#boton_nuevo_planosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_planos').css('display', 'block');
			}
			else {
				$('#boton_nuevo_documentoevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_documento').css('display', 'none');

				$('#boton_nuevo_fotosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_fotos').css('display', 'none');

				$('#boton_nuevo_planosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_planos').css('display', 'none');
			}
		}
		else {
			if (parseInt(proyecto_bloqueado) == 0) {
				$('#boton_nuevo_documentoevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_documento').css('display', 'block');

				$('#boton_nuevo_fotosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_fotos').css('display', 'block');

				$('#boton_nuevo_planosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_planos').css('display', 'block');
			}
			else {
				$('#boton_nuevo_documentoevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_documento').css('display', 'none');

				$('#boton_nuevo_fotosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_fotos').css('display', 'none');

				$('#boton_nuevo_planosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_planos').css('display', 'none');
			}
		}


		// Configuracion de los campos [quimicos, informacion general, Ergonomos, Psicologos]
		if (evidencia_agente_id == 0 || evidencia_agente_id == 13 || evidencia_agente_id == 14) {
			$("#evidenciadocumento").attr('accept', 'application/pdf'); // Campo FILE solo PDF
		}
		else {
			$("#evidenciadocumento").attr('accept', ''); // Campo FILE todo tipo de archivos
		}
	}
}


//===============================================================


function consulta_evidencia_documentos(proyecto_id, agente_id, agente_nombre) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciadocumentos/" + proyecto_id + "/" + agente_id + "/" + agente_nombre,
		data: {},
		cache: false,
		success: function (dato) {
			$('#tabla_evidenciadocumentos tbody').html(dato.documentos);
		},
		beforeSend: function () {
			$('#tabla_evidenciadocumentos tbody').html('<tr><td colspan="6"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_evidenciadocumentos tbody').html('<tr><td colspan="6">Error al cargar los documentos</td></tr>');
			return false;
		}
	});//Fin ajax
}


$("#boton_nuevo_documentoevidencia").click(function () {
	// Borrar formulario
	$('#form_evidencia_documento').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#evidenciadocumento_id").val(0);

	// requerir campo FILE
	$('#evidenciadocumento').attr('required', true);

	// Activar boton guardar
	$('#boton_guardar_evidencia_documento').css('display', 'block');

	// mostrar modal
	$('#modal_evidencia_documento').modal({ backdrop: false });
});


$("#boton_guardar_evidencia_documento").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme guardar documento!",
			text: "",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_evidencia_documento').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoevidencia',
					data: {
						opcion: 1, //DOCUMENTOS
						proyecto_id: proyecto_id,
						agente_id: evidencia_agente_id,
						agente_nombre: evidencia_agente,
					},
					resetForm: false,
					success: function (dato) {
						consulta_evidencia_documentos(proyecto_id, evidencia_agente_id, evidencia_agente, 0);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_evidencia_documento').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_documento').attr('disabled', false);

						// cerrar modal
						$('#modal_evidencia_documento').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_evidencia_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_documento').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_evidencia_documento').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_documento').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


function evidencia_documento_descargar(documento_id) {
	window.open("/proyectoevidenciadocumentodescargar/" + documento_id + "/" + 1); //1 descargar
}


function evidencia_documento_editar(documento_id, documento_nombre) {
	// Borrar formulario
	$('#form_evidencia_documento').each(function () {
		this.reset();
	});

	// Campos Hidden
	// $("#evidenciadocumento_tipo").val(1);
	$("#evidenciadocumento_id").val(documento_id);

	// campos visibles
	$("#proyectoevidenciadocumento_nombre").val(documento_nombre);

	// requerir campo FILE
	$('#evidenciadocumento').attr('required', false);

	// Activar boton guardar
	if (proyecto_perfil == 1) //Superusuario, Administrador
	{
		$('#boton_guardar_evidencia_documento').css('display', 'block');
	}
	else {
		$('#boton_guardar_evidencia_documento').css('display', 'none');
	}

	// mostrar modal
	$('#modal_evidencia_documento').modal({ backdrop: false });
}


function evidencia_documento_eliminar(documento_id) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar!",
		text: "documento de evidencia",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Eliminar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			// Enviar datos
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/proyectoevidenciadocumentoeliminar/" + documento_id,
				data: {},
				cache: false,
				success: function (dato) {
					// Actualiza tabla documentos
					consulta_evidencia_documentos(proyecto_id, evidencia_agente_id, evidencia_agente, 0);

					// mensaje
					swal({
						title: "Correcto",
						text: "" + dato.msj,
						type: "success", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
				},
				error: function (dato) {
					// mensaje
					swal({
						title: "Error",
						text: "" + dato.msj,
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});
					return false;
				}
			});//Fin ajax
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


//===============================================================


var foto_resize_fisicos = "";
var foto_resize_quimicos = [];
var foto_resize_planos = [];


$("#boton_nuevo_fotosevidencia").click(function () {
	// Borrar formulario
	$('#form_evidencia_fotos').each(function () {
		this.reset();
	});

	// Resetear input FOTO
	$('#inputevidenciafotofisicos').val('');
	$('#inputevidenciafotofisicos').dropify().data('dropify').resetPreview();
	$('#inputevidenciafotofisicos').dropify().data('dropify').clearElement();

	// Campos Hidden
	$("#evidenciafotos_id").val(0);

	// Inicializar variables FOTOS
	foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

	// Activar campo carpeta para los parametros [quimicos, info general, ergonomos, psicologos]
	// if (parseInt(evidencia_agente_id) == 15 || parseInt(evidencia_agente_id) == 0 || parseInt(evidencia_agente_id) == 13 || parseInt(evidencia_agente_id) == 14)
	if (parseInt(evidencia_agente_id) == 0 || parseInt(evidencia_agente_id) == 13 || parseInt(evidencia_agente_id) == 14) {
		// Desactivar seccion fotos Fisicos
		$(".divevidencia_seccion_fotosfisicos").css('display', 'none');

		// No requerir campos
		$("#proyectoevidenciafoto_nopunto").attr('required', false);
		$("#proyectoevidenciafoto_descripcion").attr('required', false);
		$("#inputevidenciafotofisicos").attr('required', false);

		// ACTIVAR seccion fotos carpetas
		$(".divevidencia_seccion_fotoscarpeta").css('display', 'block');

		// CAMPOS
		$("#proyectoevidenciafoto_carpeta").attr('readonly', false);
		$("#proyectoevidenciafoto_carpeta").attr('required', true);
		$("#inputevidenciafotosquimicos").attr('required', true);

		// Campo partida quimicos
		$("#catreportequimicospartidas_id").attr('required', false);
	}
	else {
		// Desactivar seccion fotos carpetas
		$(".divevidencia_seccion_fotoscarpeta").css('display', 'none');

		// No requerir campos
		$("#proyectoevidenciafoto_carpeta").attr('required', false);
		$("#inputevidenciafotosquimicos").attr('required', false);


		// ACTIVAR seccion fotos Fisicos
		$(".divevidencia_seccion_fotosfisicos").css('display', 'block');

		// CAMPOS
		$("#proyectoevidenciafoto_nopunto").attr('required', true);
		$("#proyectoevidenciafoto_descripcion").attr('required', true);
		$("#inputevidenciafotofisicos").attr('required', true);


		if (parseInt(evidencia_agente_id) == 15) {
			$("#fotosfisicos_campo_punto").css('display', 'none');
			$("#proyectoevidenciafoto_nopunto").attr('required', false);

			$("#fotosfisicos_campo_partida").css('display', 'none');
			$("#catreportequimicospartidas_id").attr('required', false);
		}
		else {
			$("#fotosfisicos_campo_partida").css('display', 'none');
			$("#catreportequimicospartidas_id").attr('required', false);

			$("#fotosfisicos_campo_punto").css('display', 'inline-block');
			$("#proyectoevidenciafoto_nopunto").attr('required', true);
		}
	}

	// Activar boton guardar
	$('#boton_guardar_evidencia_fotos').css('display', 'block');

	// mostrar modal
	$('#modal_evidencia_fotos').modal({ backdrop: false });
});


function evidenciafoto_carpetanombre(campo_select) {
	// alert($(campo_select).find(":selected").text());
	$("#proyectoevidenciafoto_carpeta").val($(campo_select).find(":selected").text());
}


function consulta_evidencia_fotos(proyecto_id, agente_id, agente_nombre) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciafotos/" + proyecto_id + "/" + agente_id + "/" + agente_nombre,
		data: {},
		cache: false,
		success: function (dato) {
			// Vaciar contenido
			$('#evidencia_galeria_fotos').html('');

			// validar si trae datos
			if (parseInt(dato.fotos_total) > 0) {
				// GALERIA DE FOTOS
				$("#evidencia_galeria_fotos").html(dato.fotos);
			}
			else {
				$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;">No hay fotos que mostrar</div>');
			}

			// Inicializar tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function () {
			$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
		},
		error: function (dato) {
			$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;">Error al cargar las fotos</div>');
			return false;
		}
	});//Fin ajax
}


function evidenciafoto_mostrardatos(foto_id, foto_ruta, foto_punto, foto_descripcion, agente_id) {
	// Borrar formulario
	$('#form_evidencia_fotos').each(function () {
		this.reset();
	});

	// Inicializar variables FOTOS
	foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

	// Desactivar seccion fotos carpetas
	$(".divevidencia_seccion_fotoscarpeta").css('display', 'none');

	// No requerir campos
	$("#proyectoevidenciafoto_carpeta").attr('required', false);
	$("#inputevidenciafotosquimicos").attr('required', false);

	// ACTIVAR seccion fotos Fisicos
	$(".divevidencia_seccion_fotosfisicos").css('display', 'block');


	if (parseInt(agente_id) == 15) {
		$("#fotosfisicos_campo_punto").css('display', 'none');
		$("#proyectoevidenciafoto_nopunto").attr('required', false);

		$("#fotosfisicos_campo_partida").css('display', 'inline-block');
		$("#catreportequimicospartidas_id").attr('required', false);

		$("#catreportequimicospartidas_id").val(foto_punto);
		$("#proyectoevidenciafoto_carpeta").val($('#catreportequimicospartidas_id').find(":selected").text());
	}
	else {
		$("#fotosfisicos_campo_partida").css('display', 'none');
		$("#catreportequimicospartidas_id").attr('required', false);

		$("#fotosfisicos_campo_punto").css('display', 'inline-block');
		$("#proyectoevidenciafoto_nopunto").attr('required', false);

		$("#proyectoevidenciafoto_nopunto").val(foto_punto);
	}


	// CAMPOS
	// $("#proyectoevidenciafoto_nopunto").attr('required', true);
	$("#proyectoevidenciafoto_descripcion").attr('required', true);
	$("#inputevidenciafotofisicos").attr('required', false);


	// Llenar campos
	$("#evidenciafotos_id").val(foto_id);
	$("#proyectoevidenciafoto_descripcion").val(foto_descripcion);


	// MOSTRAR FOTO
	var archivo = foto_ruta;
	var extension = archivo.substring(archivo.lastIndexOf("."));
	var imagenUrl = '/proyectoevidenciafotomostrar/0/' + foto_id;
	$('#inputevidenciafotofisicos').dropify().data('dropify').destroy();
	$('#inputevidenciafotofisicos').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
	$('#inputevidenciafotofisicos').dropify().data('dropify').init();

	// Activar boton guardar
	if (proyecto_perfil == 1) //Superusuario, Administrador
	{
		$('#boton_guardar_evidencia_fotos').css('display', 'block');
	}
	else {
		$('#boton_guardar_evidencia_fotos').css('display', 'none');
	}

	// mostrar modal
	$('#modal_evidencia_fotos').modal({ backdrop: false });
}


function evidencia_foto_descargar(foto_id, foto_opcion) {
	window.open("/proyectoevidenciafotomostrar/" + foto_id + "/" + foto_opcion);
}


function evidencia_foto_eliminar(foto_id) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar foto!",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			swal({
				title: "¡Confirme nuevamente eliminar foto!",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciafotoeliminar/" + foto_id,
						data: {},
						cache: false,
						success: function (dato) {
							// Actualiza galeria de fotos
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function (dato) {
							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					});//Fin ajax
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function eliminar_carpeta_fotos(proyecto_id, agente_nombre, carpeta) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar carpeta de fotos!",
		text: "[" + carpeta + "]",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			swal({
				title: "¡Confirme nuevamente eliminar carpeta de fotos!",
				text: "[" + carpeta + "]",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciafotoeliminarcarpeta/" + proyecto_id + "/" + agente_nombre + "/" + carpeta,
						data: {},
						cache: false,
						success: function (dato) {
							// Actualiza galeria de fotos
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function (dato) {
							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					});//Fin ajax
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function redimencionar_fotoevidencia() {
	// Mostrar mensaje de espera
	$('#mensaje_cargando_fotos').css('display', 'block');
	$('#boton_guardar_evidencia_fotos').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('inputevidenciafotofisicos').files;
	var file = filesToUpload[0];

	// Create an image
	var img = document.createElement("img");

	// Create a file reader
	var reader = new FileReader();

	// Load files into file reader
	reader.readAsDataURL(file);

	// Set the image once loaded into file reader
	reader.onload = function (e) {
		//img.src = e.target.result;
		var img = new Image();
		img.src = this.result;

		setTimeout(function () {
			var canvas = document.createElement("canvas");
			//var canvas = $("<canvas>", {"id":"testing"})[0];
			//var ctx = canvas.getContext("2d");
			//ctx.drawImage(img, 0, 0);

			// Dimensiones reales
			var width = img.width;
			var height = img.height;

			// Dimensiones Nuevas
			if (parseInt(width) > 8000) {
				var MAX_WIDTH = 4000; //Ancho de la imagen
				var MAX_HEIGHT = 3000; //Alto de la imagen
			}
			else {
				var MAX_WIDTH = 1200; //Ancho de la imagen
				var MAX_HEIGHT = 900; //Alto de la imagen
			}

			// Dimensionar con respecto a la relacion de aspecto
			if (width > height) {
				if (width > MAX_WIDTH) {
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			}
			else {
				if (height > MAX_HEIGHT) {
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}

			canvas.width = width;
			canvas.height = height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, width, height);
			console.log("Nuevas dimensiones ", width, height);

			// Resultado
			var dataurl = canvas.toDataURL("image/jpeg");
			// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
			foto_resize_fisicos = dataurl; //Guardar en una variable

			// Quitar mensaje de espera
			$('#mensaje_cargando_fotos').css('display', 'none');
			$('#boton_guardar_evidencia_fotos').attr('disabled', false);
		}, 100);
	}
}


$("#boton_guardar_evidencia_fotos").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme guardar foto (s)!",
			text: "",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_evidencia_fotos').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoevidencia',
					data: {
						opcion: 2, //FOTOS
						proyecto_id: proyecto_id,
						agente_id: evidencia_agente_id,
						agente_nombre: evidencia_agente,
						foto_resize_fisicos: foto_resize_fisicos,
						foto_resize_quimicos: foto_resize_quimicos
					},
					resetForm: false,
					success: function (dato) {
						// Actualiza galeria de fotos
						consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_fotos').attr('disabled', false);

						// cerrar modal
						$('#modal_evidencia_fotos').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_evidencia_fotos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_fotos').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_fotos').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


function valida_totalfotos_quimicos(campo_file) {
	if (parseInt($(campo_file).get(0).files.length) > 20) //Solo 20 archivos
	{
		swal({
			title: "Solo se pueden seleccionar 20 fotos",
			text: "Seleccionadas actualmente [" + $(campo_file).get(0).files.length + "], Intentelo de nuevo con maximo 20 fotos.",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 5000,
			showConfirmButton: false
		});

		// vaciar campo
		$(campo_file).val('');
	}
	else {
		// Inicializar arreglo de Fotos
		foto_resize_quimicos.length = 0;

		// Mostrar mensaje de espera
		$('#mensaje_cargando_fotos').css('display', 'block');
		$('#boton_guardar_evidencia_fotos').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		recorrer_fotos_files(0);

		// Funcion redimencionar imagenes
		function recorrer_fotos_files(contador) {
			if (contador < campo_file.files.length) {
				// Datos del archivo recorridos por un foreach
				// name = value.name.toLowerCase();
				// size = value.size;
				// type = value.type;

				// OBTENER FILE
				var file = campo_file.files[contador]; // FILE
				// var file = value; // FILE si es po un foreach
				// var filesToUpload = document.getElementById('inputevidenciafotofisicos').files; //Leer campo FILE
				// var file = filesToUpload[0];

				// Create an image
				var img = document.createElement("img");

				// Create a file reader
				var reader = new FileReader();

				// Load files into file reader
				reader.readAsDataURL(file);

				// Set the image once loaded into file reader
				reader.onload = function (e) {
					//img.src = e.target.result;
					var img = new Image();
					img.src = this.result;

					setTimeout(function () {
						var canvas = document.createElement("canvas");
						//var canvas = $("<canvas>", {"id":"testing"})[0];
						//var ctx = canvas.getContext("2d");
						//ctx.drawImage(img, 0, 0);

						// Dimensiones reales
						var width = img.width;
						var height = img.height;

						// Dimensiones Nuevas
						if (parseInt(width) > 8000) {
							var MAX_WIDTH = 4000; //Ancho de la imagen
							var MAX_HEIGHT = 3000; //Alto de la imagen
						}
						else {
							var MAX_WIDTH = 1200; //Ancho de la imagen
							var MAX_HEIGHT = 900; //Alto de la imagen
						}

						// Dimensionar con respecto a la relacion de aspecto
						if (width > height) {
							if (width > MAX_WIDTH) {
								height *= MAX_WIDTH / width;
								width = MAX_WIDTH;
							}
						}
						else {
							if (height > MAX_HEIGHT) {
								width *= MAX_HEIGHT / height;
								height = MAX_HEIGHT;
							}
						}

						canvas.width = width;
						canvas.height = height;
						var ctx = canvas.getContext("2d");
						ctx.drawImage(img, 0, 0, width, height);

						// Resultado
						var dataurl = canvas.toDataURL("image/jpeg");
						// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
						foto_resize_quimicos.push(dataurl + "*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						recorrer_fotos_files(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones ", width, height);
					}, 100);
				}
			}
			else {
				// Quitar mensaje de espera
				$('#mensaje_cargando_fotos').css('display', 'none');
				$('#boton_guardar_evidencia_fotos').attr('disabled', false);
			}
		}
	}
}


function agregar_fotos_carpeta(carpeta_nombre) {
	// Borrar formulario
	$('#form_evidencia_fotos').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#evidenciafotos_id").val(0);

	// Nombre carpeta
	$("#proyectoevidenciafoto_carpeta").val(carpeta_nombre);

	// Inicializar variables FOTOS
	foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

	// Desactivar seccion fotos Fisicos
	$(".divevidencia_seccion_fotosfisicos").css('display', 'none');

	// No requerir campos
	$("#proyectoevidenciafoto_nopunto").attr('required', false);
	$("#proyectoevidenciafoto_descripcion").attr('required', false);
	$("#inputevidenciafotofisicos").attr('required', false);

	// ACTIVAR seccion fotos carpetas
	$(".divevidencia_seccion_fotoscarpeta").css('display', 'block');

	// CAMPOS
	$("#proyectoevidenciafoto_carpeta").attr('readonly', true);
	$("#inputevidenciafotosquimicos").attr('required', true);
	$("#catreportequimicospartidas_id").attr('required', false); // Campo partida quimicos

	// Activar boton guardar
	$('#boton_guardar_evidencia_fotos').css('display', 'block');

	// mostrar modal
	$('#modal_evidencia_fotos').modal({ backdrop: false });
}


function opcion_nodisponible() {
	swal({
		title: "Opción no disponible",
		text: "Solamente puede hacer cambios con la autorización del administrador",
		type: "info", // warning, error, success, info
		buttons: {
			visible: false, // true , false
		},
		timer: 2000,
		showConfirmButton: false
	});
}


//===============================================================


function editar_nombrecarpeta(carpeta_tipo, carpeta_nombre, partida_id) {
	// Borrar formulario
	$('#form_nombrecarpeta').each(function () {
		this.reset();
	});

	// Nombre carpeta
	$("#proyectoevidencia_nombrecarpetatipo").val(carpeta_tipo);
	$("#proyectoevidencia_nombrecarpetaoriginal").val(carpeta_nombre);
	$("#proyectoevidencia_nombrecarpetarenombrar").val(carpeta_nombre);


	if (parseInt(partida_id) > 0) // es químicos
	{
		$("#nombrecarpeta_campo_partida").css('display', 'inline-block');
		$("#nombrecarpetacatreportequimicospartidas_id").val(partida_id);
		$("#nombrecarpetacatreportequimicospartidas_id").attr('required', false);
	}
	else {
		$("#nombrecarpeta_campo_partida").css('display', 'none');
		$("#nombrecarpetacatreportequimicospartidas_id").val(partida_id);
		$("#nombrecarpetacatreportequimicospartidas_id").attr('required', false);
	}


	// mostrar modal
	$('#modal_nombrecarpeta').modal({ backdrop: false });
}


$("#boton_guardar_nombrecarpeta").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme renombrar carpeta!",
			text: $("#proyectoevidencia_nombrecarpetaoriginal").val() + "\n\n por \n\n" + $("#proyectoevidencia_nombrecarpetarenombrar").val(),
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Cambiar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_nombrecarpeta').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoevidencia',
					data: {
						opcion: 4, // RENOMBRAR CARPETA
						proyecto_id: proyecto_id,
						agente_id: evidencia_agente_id,
						agente_nombre: evidencia_agente
					},
					resetForm: false,
					success: function (dato) {
						// Actualiza galeria de fotos
						if (parseInt($("#proyectoevidencia_nombrecarpetatipo").val()) == 1) {
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);
						}
						else {
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);
						}

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_nombrecarpeta').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', false);

						// cerrar modal
						$('#modal_nombrecarpeta').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_nombrecarpeta').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_nombrecarpeta').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//===============================================================


$("#boton_nuevo_planosevidencia").click(function () {
	// Borrar formulario
	$('#form_evidencia_planos').each(function () {
		this.reset();
	});

	// Inicializar variables PLANO
	foto_resize_planos.length = 0;


	// Campos Hidden
	$("#evidenciaplano_id").val(0);
	$("#proyectoevidenciaplano_carpeta").attr('readonly', false);


	if (parseInt(evidencia_agente_id) == 15) {
		$("#planos_campo_partida").css('display', 'inline-block');
		$("#planoscatreportequimicospartidas_id").attr('disabled', false);
		$("#planoscatreportequimicospartidas_id").attr('required', true);
	}
	else {
		$("#planos_campo_partida").css('display', 'none');
		$("#planoscatreportequimicospartidas_id").attr('required', false);
	}


	// Activar boton guardar
	$('#boton_guardar_evidencia_planos').css('display', 'block');


	// Titulo del modal
	$('#modal_evidencia_planos .modal-title').html('Nuevos planos evidencia');


	// mostrar modal
	$('#modal_evidencia_planos').modal({ backdrop: false });
});


function consulta_evidencia_planos(proyecto_id, agente_id, agente_nombre) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciaplanos/" + proyecto_id + "/" + agente_id + "/" + agente_nombre,
		data: {},
		cache: false,
		success: function (dato) {
			// Vaciar contenido
			$('#evidencia_galeria_planos').html('');

			// validar si trae datos
			if (parseInt(dato.planos_total) > 0) {
				// GALERIA DE FOTOS
				$("#evidencia_galeria_planos").html(dato.planos);
			}
			else {
				$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;">No hay planos que mostrar</div>');
			}

			// Inicializar tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function () {
			$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
		},
		error: function (dato) {
			$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;">Error al cargar las planos</div>');
			return false;
		}
	});//Fin ajax
}


function eliminar_carpeta_planos(proyecto_id, agente_nombre, carpeta) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar carpeta de planos!",
		text: "[" + carpeta + "]",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			swal({
				title: "¡Confirme nuevamente eliminar carpeta de planos!",
				text: "[" + carpeta + "]",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciaplanoeliminarcarpeta/" + proyecto_id + "/" + agente_nombre + "/" + carpeta,
						data: {},
						cache: false,
						success: function (dato) {
							// Actualiza galeria de planos
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function (dato) {
							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					});//Fin ajax
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function evidencia_plano_descargar(foto_opcion, foto_id) {
	window.open("/proyectoevidenciaplanosmostrar/" + foto_opcion + "/" + foto_id);
}


function evidencia_plano_eliminar(foto_id) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar plano!",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			swal({
				title: "¡Confirme nuevamente eliminar plano!",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciaplanoeliminar/" + foto_id,
						data: {},
						cache: false,
						success: function (dato) {
							// Actualiza galeria de fotos
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function (dato) {
							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					});//Fin ajax
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


function valida_totalfotos_plano(campo_file) {
	if (parseInt($(campo_file).get(0).files.length) > 20) //Solo 20 archivos
	{
		swal({
			title: "Solo se pueden seleccionar 20 fotos",
			text: "Seleccionadas actualmente [" + $(campo_file).get(0).files.length + "], Intentelo de nuevo con maximo 20 fotos.",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 4000,
			showConfirmButton: false
		});

		// vaciar campo
		$(campo_file).val('');
	}
	else {
		// Inicializar arreglo de Fotos
		foto_resize_planos.length = 0;

		// Mostrar mensaje de espera
		$('#mensaje_cargando_planos').css('display', 'block');
		$('#boton_guardar_evidencia_planos').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		recorrer_planos_files(0);

		// Funcion redimencionar imagenes
		function recorrer_planos_files(contador) {
			if (contador < campo_file.files.length) {
				// Datos del archivo recorridos por un foreach
				// name = value.name.toLowerCase();
				// size = value.size;
				// type = value.type;

				// OBTENER FILE
				var file = campo_file.files[contador]; // FILE
				// var file = value; // FILE si es po un foreach
				// var filesToUpload = document.getElementById('inputevidenciafotofisicos').files; //Leer campo FILE
				// var file = filesToUpload[0];

				// Create an image
				var img = document.createElement("img");

				// Create a file reader
				var reader = new FileReader();

				// Load files into file reader
				reader.readAsDataURL(file);

				// Set the image once loaded into file reader
				reader.onload = function (e) {
					//img.src = e.target.result;
					var img = new Image();
					img.src = this.result;

					setTimeout(function () {
						var canvas = document.createElement("canvas");
						//var canvas = $("<canvas>", {"id":"testing"})[0];
						//var ctx = canvas.getContext("2d");
						//ctx.drawImage(img, 0, 0);

						// Dimensiones reales
						var width = img.width;
						var height = img.height;

						// Dimensiones Nuevas
						if (parseInt(width) > 8000) {
							var MAX_WIDTH = 4000; //Ancho de la imagen
							var MAX_HEIGHT = 3000; //Alto de la imagen
						}
						else {
							var MAX_WIDTH = 1200; //Ancho de la imagen
							var MAX_HEIGHT = 900; //Alto de la imagen
						}

						// Dimensionar con respecto a la relacion de aspecto
						if (width > height) {
							if (width > MAX_WIDTH) {
								height *= MAX_WIDTH / width;
								width = MAX_WIDTH;
							}
						}
						else {
							if (height > MAX_HEIGHT) {
								width *= MAX_HEIGHT / height;
								height = MAX_HEIGHT;
							}
						}

						canvas.width = width;
						canvas.height = height;
						var ctx = canvas.getContext("2d");
						ctx.drawImage(img, 0, 0, width, height);

						// Resultado
						var dataurl = canvas.toDataURL("image/jpeg");
						// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
						foto_resize_planos.push(dataurl + "*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						recorrer_planos_files(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones ", width, height);
					}, 100);
				}
			}
			else {
				// Quitar mensaje de espera
				$('#mensaje_cargando_planos').css('display', 'none');
				$('#boton_guardar_evidencia_planos').attr('disabled', false);
			}
		}
	}
}


function agregar_planos_carpeta(carpeta_nombre, partida_id) {
	// Borrar formulario
	$('#form_evidencia_planos').each(function () {
		this.reset();
	});

	// Inicializar variables PLANO
	foto_resize_planos.length = 0;


	$("#planos_campo_partida").css('display', 'none');
	$("#planoscatreportequimicospartidas_id").val(partida_id);
	$("#planoscatreportequimicospartidas_id").attr('required', false);

	// if (parseInt(partida_id) > 0)
	// {
	// 	$("#planos_campo_partida").css('display', 'none');
	// 	$("#planoscatreportequimicospartidas_id").val(partida_id);
	// 	$("#planoscatreportequimicospartidas_id").attr('required', false);
	// 	$("#planoscatreportequimicospartidas_id").attr('disabled', true);
	// }
	// else
	// {
	// 	$("#planos_campo_partida").css('display', 'none');
	// 	$("#planoscatreportequimicospartidas_id").attr('required', false);
	// }


	// Campos Hidden
	$("#evidenciaplano_id").val(0);

	// Nombre carpeta
	$("#proyectoevidenciaplano_carpeta").val(carpeta_nombre);
	$("#proyectoevidenciaplano_carpeta").attr('readonly', true);


	// Activar boton guardar
	$('#boton_guardar_evidencia_planos').css('display', 'block');


	// Titulo del modal
	$('#modal_evidencia_planos .modal-title').html('Agregar más planos a la carpeta');


	// mostrar modal
	$('#modal_evidencia_planos').modal({ backdrop: false });
}


$("#boton_guardar_evidencia_planos").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme guardar plano!",
			text: "",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_evidencia_planos').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoevidencia',
					data: {
						opcion: 3, //PLANOS
						proyecto_id: proyecto_id,
						agente_id: evidencia_agente_id,
						agente_nombre: evidencia_agente,
						foto_resize_planos: foto_resize_planos,
					},
					resetForm: false,
					success: function (dato) {
						// Actualiza galeria de planos
						consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

						// mensaje
						swal({
							title: "Correcto",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_evidencia_planos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_planos').attr('disabled', false);

						// cerrar modal
						$('#modal_evidencia_planos').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_evidencia_planos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_planos').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_evidencia_planos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_planos').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//===============================================================


function verifica_puntosreales(puntos_asignados, puntos_reales, input_index) {
	// alert(puntos_asignados+' - '+puntos_reales);
	if (parseInt(puntos_asignados) == parseInt(puntos_reales)) {
		$('#puntorealobservacion_' + input_index).val('');
		$('#puntorealobservacion_' + input_index).attr('placeholder', '');
		$('#puntorealobservacion_' + input_index).attr('required', false);
		$('#puntorealobservacion_' + input_index).attr('readonly', true);
	}
	else {
		$('#puntorealobservacion_' + input_index).attr('placeholder', 'Observación requerida');
		$('#puntorealobservacion_' + input_index).attr('readonly', false);
		$('#puntorealobservacion_' + input_index).attr('required', true);
	}
}


$("#boton_guardar_puntosreales").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Puntos reales",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
			function (isConfirm) {
				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_puntosreales').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/proyectopuntosreales',
						data: {
							proyecto_id: proyecto_id
						},
						resetForm: false,
						success: function (dato) {
							if (parseInt(dato.puntosreales_total) > 0) {
								$('#boton_imprimir_proyectopuntosreales').css('display', 'inline-block');
							}
							else {
								$('#boton_imprimir_proyectopuntosreales').css('display', 'none');
							}

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});

							// actualiza boton
							$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
						},
						beforeSend: function () {
							$('#boton_guardar_puntosreales').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function (dato) {
							// actualiza boton
							$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');

							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					}).submit();
					return false;
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "Acción cancelada",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		return false;
	}
});


$("#boton_imprimir_proyectopuntosreales").click(function () {
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectopuntosrealesreporte/' + proyecto_id);
	$('#modal_visor').modal({ backdrop: false });
});


$("#boton_bloquear_puntosreales").click(function () {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectopuntosrealesactivo/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			// $('#boton_bloquear_puntosreales').html(dato.opciones_menu);

			if (parseInt(dato.puntosreales_estado) == 0) {
				$('#boton_bloquear_puntosreales').removeClass('btn-success');
				$('#boton_bloquear_puntosreales').addClass('btn-warning');
				$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Puntos reales bloqueado para edición');

				$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-ban"></i>');
				$('#boton_guardar_puntosreales').attr('disabled', true);
			}
			else {
				$('#boton_bloquear_puntosreales').removeClass('btn-warning');
				$('#boton_bloquear_puntosreales').addClass('btn-success');
				$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');

				$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
				$('#boton_guardar_puntosreales').attr('disabled', false);
			}


			// mensaje
			swal({
				title: "Correcto",
				text: "" + dato.msj,
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});
		},
		beforeSend: function () {
			$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-spin fa-spinner""></i></span> Puntos reales actualizando estado');
		},
		error: function (dato) {
			// $('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');
			return false;
		}
	});//Fin ajax
});


//===============================================================


var datatable_bitacora = null;
function tabla_bitacora(proyecto_id) {
	try {
		var ruta = "/proyectoevidenciabitacoratabla/" + proyecto_id;

		if (datatable_bitacora != null) {
			datatable_bitacora.clear().draw();
			datatable_bitacora.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_bitacora = $('#tabla_bitacora').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						bitacora_responsable_id = json.bitacora_responsable_id;
						bitacora_responsable_nombre = json.bitacora_responsable_nombre;
						signatarios_opciones = json.signatarios_opciones;
						parametros_opciones = json.parametros_opciones;

						// Actualiza botones estado
						if (parseInt(json.bitacoramuestreo_estado) == 0) {
							$('#boton_bloquear_bitacoramuestreo').removeClass('btn-success');
							$('#boton_bloquear_bitacoramuestreo').addClass('btn-warning');
							$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Bitácora de muestreo bloqueado para edición');

							$('#boton_nuevo_bitacoramuestreo').attr('disabled', true);
							$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);
							$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-ban"></i>');
						}
						else {
							$('#boton_bloquear_bitacoramuestreo').removeClass('btn-warning');
							$('#boton_bloquear_bitacoramuestreo').addClass('btn-success');
							$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Bitácora de muestreo desbloqueado para edición');

							$('#boton_nuevo_bitacoramuestreo').attr('disabled', false);
							$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);
							$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
						}


						$('#bitacora_totalpuntos').html(json.total_puntos);
						$('#tabla_bitacora_resumen tbody').html(json.bitacora_resumen);


						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_bitacora ' + code);
						if (numeroejecucion <= 1) {
							tabla_bitacora(proyecto_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "numero_registro",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "dia_adjunto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "signatarioparametro",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "agente_puntos",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "signatario_observacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_editar",
						defaultContent: "-",
						className: 'editar',
						orderable: false,
					},
					{
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [0, 1, 5, 6], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
				paging: false,
				language: {
					lengthMenu: "Mostrar _MENU_ Registros",
					zeroRecords: "No se encontraron registros",
					info: "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					infoEmpty: "No se encontraron registros",
					infoFiltered: "(Filtrado de _MAX_ registros)",
					emptyTable: "No hay datos disponibles en la tabla",
					loadingRecords: "Cargando datos....",
					processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
					search: "Buscar",
					paginate: {
						first: "Primera",
						last: "Ultima",
						next: "Siguiente",
						previous: "Anterior"
					}
				},
				rowCallback: function (row, data, index) {
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					$(row).find('td:eq(2)').css('text-align', 'left');
					$(row).find('td:eq(4)').css('text-align', 'justify');
				},
			});
		}

		// Tooltip en DataTable
		datatable_bitacora.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_bitacora(proyecto_id);
	}
}


$("#boton_nuevo_bitacoramuestreo").click(function () {
	// Borrar formulario
	$('#form_evidencia_bitacoramuestreo').each(function () {
		this.reset();
	});


	$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'inline-block');


	$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-center">No hay fotos seleccionadas</div>');


	var signatario_select_opciones = '<option value="">&nbsp;</option>';
	$.each(signatarios_opciones, function (index, value) {
		signatario_select_opciones += '<option value="' + value.signatario_id + '">' + value.signatario_Nombre + '</option>';
	});


	var parametros_select_opciones = '<option value="">&nbsp;</option>';
	$.each(parametros_opciones, function (index, value) {
		parametros_select_opciones += '<option value="' + value.agente_id + '">' + value.agente_nombre + '</option>';
	});


	$('#tabla_bitacoramuestreo_personal tbody').html('<tr>' +
		'<td width="250">' +
		'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>' +
		signatario_select_opciones +
		'</select>' +
		'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;">' +
		'</td>' +
		'<td width="230">' +
		'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>' +
		parametros_select_opciones +
		'</select>' +
		'<input type="hidden" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" required>' +
		'</td>' +
		'<td width="150">' +
		'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" required>' +
		'</td>' +
		'<td width="">' +
		'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>' +
		'</td>' +
		'<td width="60">' +
		'<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x" style="margin: 0px;"></i></button>' +
		'</td>' +
		'</tr>');

	$('[data-toggle="tooltip"]').tooltip();

	// Campos Hidden
	$("#bitacoramuestreo_id").val(0);
	$("#proyectoevidenciabitacora_usuario_id").val(bitacora_responsable_id);
	$("#proyectoevidenciabitacora_usuario_nombre").val(bitacora_responsable_nombre);
	$("#proyectoevidenciabitacora_fechacarga").val('PENDIENTE');

	// mostrar modal
	$('#modal_evidencia_bitacoramuestreo').modal({ backdrop: false });
});


function bitacora_personal(select_signatario) {
	if (parseInt(select_signatario.value) > 0) {
		$.each($('.bitacorasignatario'), function (index, value) {
			if (this == select_signatario) {
				// alert(index+' - '+this.value);
				var select = $(this)[0]; //Select array encontrado

				for (var i = 0; i < select.length; i++) // recorrer opciones select
				{
					var option = select.options[i];
					// alert (option.text + ' ' + option.value);

					if (parseInt(select_signatario.value) == parseInt(option.value)) // Opcion elegida
					{
						// alert (option.value + ' - ' + option.text);
						$("input[name='bitacora_signatario_nombre[]']").eq(index).val(option.text); //Capturamos nombre del personal
					}
				}
			}
		});
	}
	else {
		$.each($('.bitacorasignatario'), function (index, value) {
			if (this == select_signatario) {
				$("input[name='bitacora_signatario_nombre[]']").eq(index).val(''); // Vaciar campo nombre personal
			}
		});
	}
}


function bitacora_parametro(select_parametro) {
	if (parseInt(select_parametro.value) > 0) {
		$.each($('.bitacoraparametro'), function (index, value) {
			if (this == select_parametro) {
				if (parseInt(select_parametro.value) != 15) // Diferente de quimicos
				{
					// alert(index+' - '+this.value);
					var select = $(this)[0]; //Select array encontrado

					for (var i = 0; i < select.length; i++) // recorrer opciones select
					{
						var option = select.options[i];
						// alert (option.text + ' ' + option.value);

						if (parseInt(select_parametro.value) == parseInt(option.value)) // Opcion elegida
						{
							// alert (option.value + ' - ' + option.text);
							$("input[name='bitacora_agente_nombre[]']").eq(index).val(option.text); //Capturamos nombre del parametro
							$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'hidden'); // Ocultar campo
						}
					}
				}
				else {
					// alert (option.value + ' - ' + option.text);
					$("input[name='bitacora_agente_nombre[]']").eq(index).val(''); // Vaciamos campo
					$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'text'); // Mostrar campo
				}
			}
		});
	}
	else {
		$.each($('.bitacoraparametro'), function (index, value) {
			if (this == select_parametro) {
				$("input[name='bitacora_agente_nombre[]']").eq(index).val(''); // Vaciamos campo
				$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'hidden'); // Ocultar campo
			}
		});
	}
}


$("#botonnuevo_bitacoramuestreo_signatario").click(function () {
	var signatario_select_opciones = '<option value="">&nbsp;</option>';
	$.each(signatarios_opciones, function (index, value) {
		signatario_select_opciones += '<option value="' + value.signatario_id + '">' + value.signatario_Nombre + '</option>';
	});


	var parametros_select_opciones = '<option value="">&nbsp;</option>';
	$.each(parametros_opciones, function (index, value) {
		parametros_select_opciones += '<option value="' + value.agente_id + '">' + value.agente_nombre + '</option>';
	});


	$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>' +
		'<td width="250">' +
		'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>' +
		signatario_select_opciones +
		'</select>' +
		'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;">' +
		'</td>' +
		'<td width="230">' +
		'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>' +
		parametros_select_opciones +
		'</select>' +
		'<input type="hidden" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" required>' +
		'</td>' +
		'<td width="150">' +
		'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" required>' +
		'</td>' +
		'<td width="">' +
		'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>' +
		'</td>' +
		'<td width="60">' +
		'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>' +
		'</td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#divtabla_bitacoramuestreo_personal').animate({
		scrollTop: $('#tabla_bitacoramuestreo_personal > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$("#botonnuevo_bitacoramuestreo_adicional").click(function () {
	$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>' +
		'<td width="250">' +
		'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" style="display: none;" required>' +
		'<option value="">&nbsp;</option>' +
		'<option value="0" selected>N/A</option>' +
		'</select>' +
		'<input type="text" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="" placeholder="Nombre personal adicional" required>' +
		'</td>' +
		'<td width="230">' +
		'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" style="display: none;" required>' +
		'<option value="">&nbsp;</option>' +
		'<option value="0" selected>N/A</option>' +
		'</select>' +
		'<input type="text" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 0px;" value="N/A" readonly>' +
		'</td>' +
		'<td width="150">' +
		'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="0" readonly>' +
		'</td>' +
		'<td width="">' +
		'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>' +
		'</td>' +
		'<td width="60">' +
		'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>' +
		'</td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#divtabla_bitacoramuestreo_personal').animate({
		scrollTop: $('#tabla_bitacoramuestreo_personal > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_bitacoramuestreo_personal tbody').on('click', 'td>button.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar persona?",
		text: "Quitar de la lista",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Eliminar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();

			var tr = fila.closest('tr');
			fila.closest("tr").remove(); // eliminar fila TR

			// mensaje
			swal({
				title: "Correcto",
				text: "Persona eliminada de la lista",
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1000,
				showConfirmButton: false
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
});


var evidenciabitacora_fotos = [];


function bitacorafotos_redimencionar(campo_file) {
	if (parseInt($(campo_file).get(0).files.length) > 5) //Solo 20 archivos
	{
		swal({
			title: "Solo se puede subir hasta 5 fotos",
			text: "Seleccionadas actualmente [" + $(campo_file).get(0).files.length + "]",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 4000,
			showConfirmButton: false
		});

		// vaciar campo
		$(campo_file).val('');
	}
	else {
		// Inicializar arreglo de Fotos
		evidenciabitacora_fotos.length = 0;

		// Mostrar mensaje de espera
		$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-danger"><i class="fa fa-spin fa-spinner"></i> Cargando foto (s), por favor espere...</div>');
		$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		redimencionar_foto(0);

		// Funcion redimencionar imagenes
		function redimencionar_foto(contador) {
			if (contador < campo_file.files.length) {
				// Datos del archivo recorridos por un foreach
				// name = value.name.toLowerCase();
				// size = value.size;
				// type = value.type;

				// OBTENER FILE
				var file = campo_file.files[contador]; // FILE
				// var file = value; // FILE si es po un foreach
				// var filesToUpload = document.getElementById('inputevidenciafotofisicos').files; //Leer campo FILE
				// var file = filesToUpload[0];

				// Create an image
				var img = document.createElement("img");

				// Create a file reader
				var reader = new FileReader();

				// Load files into file reader
				reader.readAsDataURL(file);

				// Set the image once loaded into file reader
				reader.onload = function (e) {
					//img.src = e.target.result;
					var img = new Image();
					img.src = this.result;

					setTimeout(function () {
						var canvas = document.createElement("canvas");
						//var canvas = $("<canvas>", {"id":"testing"})[0];
						//var ctx = canvas.getContext("2d");
						//ctx.drawImage(img, 0, 0);

						// Dimensiones reales
						var width = img.width;
						var height = img.height;

						// // Dimensiones Nuevas
						// if (parseInt(width) > 8000)
						// {
						// 	var MAX_WIDTH = 4000; //Ancho de la imagen
						// 	var MAX_HEIGHT = 3000; //Alto de la imagen
						// }
						// else
						// {
						// 	var MAX_WIDTH = 1200; //Ancho de la imagen
						// 	var MAX_HEIGHT = 900; //Alto de la imagen
						// }

						var MAX_WIDTH = 1200; //Ancho de la imagen
						var MAX_HEIGHT = 900; //Alto de la imagen

						// Dimensionar con respecto a la relacion de aspecto
						if (width > height) {
							if (width > MAX_WIDTH) {
								height *= MAX_WIDTH / width;
								width = MAX_WIDTH;
							}
						}
						else {
							if (height > MAX_HEIGHT) {
								width *= MAX_HEIGHT / height;
								height = MAX_HEIGHT;
							}
						}

						canvas.width = width;
						canvas.height = height;
						var ctx = canvas.getContext("2d");
						ctx.drawImage(img, 0, 0, width, height);

						// Resultado
						var dataurl = canvas.toDataURL("image/jpeg");
						// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
						evidenciabitacora_fotos.push(dataurl + "*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						redimencionar_foto(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones foto " + (contador - 1) + " ->", width, height);
					}, 100);
				}
			}
			else {
				// Quitar mensaje de espera
				$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-info"><i class="fa fa-check"></i> Fotos cargadas correctamente.</div>');
				$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);
			}
		}
	}
}


function bitacorafotos_galeria(bitacora_id, proyecto_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciabitacorafotos/" + bitacora_id + "/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			$('#proyectoevidenciabitacora_fotosgaleria').html(dato.fotos);
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function () {
			$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></div>');
		},
		error: function (dato) {
			$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-danger">Error al cargar las fotos</div>');
			return false;
		}
	});//Fin ajax
}


function bitacorafoto_mostrar(foto_id) {
	// mostrar modal
	$('#modal_bitacora_foto').modal({ backdrop: false });

	$('#bitacora_visor').attr('src', '/proyectoevidenciabitacorafotomostrar/' + foto_id + "/0");
}


function bitacorafoto_descargar(foto_id) {
	window.open("/proyectoevidenciabitacorafotomostrar/" + foto_id + "/" + 1); //1 descargar
}


function bitacorafoto_eliminar(foto_id, bitacora_id) {
	// Confirmar accion
	swal({
		title: "¡Confirme eliminar foto!",
		text: "Evidencia bitácora",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// Confirmar accion
			swal({
				title: "¡Confirme nuevamente eliminar foto!",
				text: "Evidencia bitácora",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar msj
					swal.close();

					// Eliminar foto
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciabitacorafotoeliminar/" + foto_id,
						data: {},
						cache: false,
						success: function (dato) {
							// Actualiza galeria de fotos
							bitacorafotos_galeria(bitacora_id, proyecto_id);

							// mensaje
							swal({
								title: "Correcto",
								text: "" + dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function (dato) {
							// mensaje
							swal({
								title: "Error",
								text: "" + dato.msj,
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
							return false;
						}
					});//Fin ajax
				}
				else {
					// mensaje
					swal({
						title: "Cancelado",
						text: "",
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 500,
						showConfirmButton: false
					});
				}
			});
		}
		else {
			// mensaje
			swal({
				title: "Cancelado",
				text: "",
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
}


$("#modal_bitacorafoto_cerrar").click(function () {
	$('#bitacora_visor').attr('src', '/assets/images/fondovisor.jpg');
});


$('#tabla_bitacora tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_bitacora.row(tr);

	$('#form_evidencia_bitacoramuestreo').each(function () {
		this.reset();
	});


	// Boton guardar
	if (parseInt(proyecto_perfil) == 1) //Ddministradores
	{
		$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'inline-block');
	}
	else {
		$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'none');
	}


	// Campos Hidden
	$("#bitacoramuestreo_id").val(row.data().id);
	$("#proyectoevidenciabitacora_usuario_id").val(row.data().usuario_id);
	$("#proyectoevidenciabitacora_usuario_nombre").val(row.data().usuario_nombre);
	$("#proyectoevidenciabitacora_fechacarga").val(row.data().created_at);

	// Llenar campos
	$("#proyectoevidenciabitacora_fecha").val(row.data().proyectoevidenciabitacora_fecha);
	$('#proyectoevidenciabitacora_observacion').val(row.data().proyectoevidenciabitacora_observacion);


	// Llenar tabla
	$('#tabla_bitacoramuestreo_personal tbody').html('');


	// Recorrer tabla
	var fila = 0;
	datatable_bitacora.rows().every(function (rowIdx, tableLoop, rowLoop) {
		var data = this.data();

		if (parseInt(row.data().id) == parseInt(data.id)) {
			if (parseInt(data.signatario_id) > 0) {
				var signatario_select_opciones = '<option value="">&nbsp;</option>';
				$.each(signatarios_opciones, function (index, value) {
					if (parseInt(data.signatario_id) == parseInt(value.signatario_id)) {
						signatario_select_opciones += '<option value="' + value.signatario_id + '" selected>' + value.signatario_Nombre + '</option>';
					}
					else {
						signatario_select_opciones += '<option value="' + value.signatario_id + '">' + value.signatario_Nombre + '</option>';
					}
				});


				var parametros_select_opciones = '<option value="">&nbsp;</option>';
				$.each(parametros_opciones, function (index, value) {
					if (parseInt(data.agente_id) == parseInt(value.agente_id)) {
						parametros_select_opciones += '<option value="' + value.agente_id + '" selected>' + value.agente_nombre + '</option>';
					}
					else {
						parametros_select_opciones += '<option value="' + value.agente_id + '">' + value.agente_nombre + '</option>';
					}
				});


				var boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>';
				if (parseInt(fila) == 0) {
					boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x" style="margin: 0px;"></i></button>';
				}


				var quimico_tipo = 'hidden';
				if (parseInt(data.agente_id) == 15) {
					quimico_tipo = 'text';
				}


				$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>' +
					'<td width="250">' +
					'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>' +
					signatario_select_opciones +
					'</select>' +
					'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="' + data.signatario_nombre + '">' +
					'</td>' +
					'<td width="230">' +
					'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>' +
					parametros_select_opciones +
					'</select>' +
					'<input type="' + quimico_tipo + '" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" value="' + data.agente_nombre + '" required>' +
					'</td>' +
					'<td width="150">' +
					'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="' + data.agente_puntos + '" required>' +
					'</td>' +
					'<td width="">' +
					'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required>' + data.signatario_observacion + '</textarea>' +
					'</td>' +
					'<td width="60">' +
					boton_eliminar +
					'</td>' +
					'</tr>');

				fila += 1;
			}
			else {
				$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>' +
					'<td width="250">' +
					'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" style="display: none;" required>' +
					'<option value="">&nbsp;</option>' +
					'<option value="0" selected>N/A</option>' +
					'</select>' +
					'<input type="text" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="' + data.signatario_nombre + '" placeholder="Nombre personal adicional" required>' +
					'</td>' +
					'<td width="230">' +
					'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" style="display: none;" required>' +
					'<option value="">&nbsp;</option>' +
					'<option value="0" selected>N/A</option>' +
					'</select>' +
					'<input type="text" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 0px;" value="N/A" readonly>' +
					'</td>' +
					'<td width="150">' +
					'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="0" readonly>' +
					'</td>' +
					'<td width="">' +
					'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required>' + data.signatario_observacion + '</textarea>' +
					'</td>' +
					'<td width="60">' +
					'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>' +
					'</td>' +
					'</tr>');
			}
		}
	});


	bitacorafotos_galeria(row.data().id, proyecto_id);


	$('[data-toggle="tooltip"]').tooltip();


	// Titulo del modal
	$('#modal_evidencia_bitacoramuestreo .modal-title').html('Bitácora de muestreo');

	// mostrar modal
	$('#modal_evidencia_bitacoramuestreo').modal({ backdrop: false });
});


$("#boton_guardar_evidencia_bitacoramuestreo").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Confirma guardar
		swal({
			title: "¡Confirme guardar!",
			text: "Bitácora de muestreo día " + $('#proyectoevidenciabitacora_fecha').val() + " \n\n Después de guardar solo se pueden hacer cambios con la autorización del administrador.",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Aceptar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// Confirma guardar
				swal({
					title: "¡Confirme nuevamente guardar!",
					text: "Bitácora de muestreo día " + $('#proyectoevidenciabitacora_fecha').val() + " \n\n Después de guardar solo se pueden hacer cambios con la autorización del administrador.",
					type: "info",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Guardar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				}, function (isConfirm) {
					if (isConfirm) {
						// cerrar msj confirmacion
						swal.close();

						// enviar datos
						$('#form_evidencia_bitacoramuestreo').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoevidenciabitacora',
							data: {
								proyecto_id: proyecto_id,
								evidenciabitacora_fotos: evidenciabitacora_fotos,
							},
							resetForm: false,
							success: function (dato) {
								// Actualiza tabla
								tabla_bitacora(proyecto_id);

								// mensaje
								swal({
									title: "Correcto",
									text: "" + dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});

								// actualiza boton
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);

								// cerrar modal
								$('#modal_evidencia_bitacoramuestreo').modal('hide');
							},
							beforeSend: function () {
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);

								// mensaje
								swal({
									title: "Error",
									text: "" + dato.msj,
									type: "error", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});
								return false;
							}
						}).submit();
						return false;
					}
					else {
						// mensaje
						swal({
							title: "Cancelado",
							text: "",
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 500,
							showConfirmButton: false
						});
					}
				});
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$('#tabla_bitacora tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_bitacora.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El día " + row.data().proyectoevidenciabitacora_fecha + " de la bitácora de muestreo.",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				swal({
					title: "¡Por seguridad confirme nuevamente eliminar!",
					text: "El día " + row.data().proyectoevidenciabitacora_fecha + " de la bitácora de muestreo.",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Eliminar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
					function (isConfirm) {
						if (isConfirm) {
							// cerrar msj confirmacion
							swal.close();

							$.ajax({
								type: "GET",
								dataType: "json",
								url: "/proyectoevidenciabitacoraeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualiza tabla
									tabla_bitacora(proyecto_id);

									// mensaje
									swal({
										title: "Correcto",
										text: "" + dato.msj,
										type: "success", // warning, error, success, info
										buttons: {
											visible: false, // true , false
										},
										timer: 1500,
										showConfirmButton: false
									});
								},
								beforeSend: function () {
									// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
								},
								error: function (dato) {
									// mensaje
									swal({
										title: "Error",
										text: "" + dato.msj,
										type: "error", // warning, error, success, info
										buttons: {
											visible: false, // true , false
										},
										timer: 1500,
										showConfirmButton: false
									});

									return false;
								}
							});//Fin ajax
						}
						else {
							// mensaje
							swal({
								title: "Cancelado",
								text: "Acción cancelada",
								type: "error", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 500,
								showConfirmButton: false
							});
						}
					});
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
});


$("#boton_imprimir_bitacoramuestreo").click(function () {
	// Visor
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoevidenciabitacoraimprimir/' + proyecto_id);
	// $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoevidenciabitacoraimprimir/'+proyecto_id);


	// mostrar modal
	$('#modal_visor').modal({ backdrop: false });
});


$("#boton_bloquear_bitacoramuestreo").click(function () {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciabitacoraactivo/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Actualizar tabla
			tabla_bitacora(proyecto_id);


			// mensaje
			swal({
				title: "Correcto",
				text: "" + dato.msj,
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});
		},
		beforeSend: function () {
			$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-spin fa-spinner""></i></span> Bitácora de muestreo actualizando estado');
		},
		error: function (dato) {
			return false;
		}
	});//Fin ajax
});


//===============================================================


var total_ejecusion = 0;
function consulta_menuparametros_reportes(proyecto_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteslistaparametros/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Pintar menu opciones
			$('#select_tiporeportes').html(dato.opciones_menu);
		},
		beforeSend: function () {
			// $('#lista_menu_parametros_reportes').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></li>');
			$('#select_tiporeportes').html('<option value=""><i class="fa fa-spin fa-spinner""></i></option>');
		},
		error: function (dato) {
			if (total_ejecusion == 0) {
				consulta_menuparametros_reportes(proyecto_id);
				total_ejecusion += 1;
			}

			// $('#lista_menu_parametros_reportes').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">Error al cargar los parametros</li>');
			$('#select_tiporeportes').html('<option value="">Error al cargar la lista de reportes, actualice la página</option>');
			return false;
		}
	});//Fin ajax
}


// function mostrar_reporte(agente_nombre)
function mostrar_reporte(agente_id) {
	// Cargar página reporte
	$.ajax({
		success: function () {
			switch (parseInt(agente_id)) {
				case 0:
					$('#estructura_reporte').load('/reportepoevista/' + proyecto_id);
					break;
				case 1:
					$('#estructura_reporte').load('/reporteruidovista/' + proyecto_id);
					break;
				case 2:
					$('#estructura_reporte').load('/reportevibracionvista/' + proyecto_id);
					break;
				case 3:
					$('#estructura_reporte').load('/reportetemperaturavista/' + proyecto_id);
					break;
				case 4:
					$('#estructura_reporte').load('/reporteiluminacionvista/' + proyecto_id);
					break;
				case 5:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 6:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 7:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 8:
					$('#estructura_reporte').load('/reporteairevista/' + proyecto_id);
					break;
				case 9:
					$('#estructura_reporte').load('/reporteaguavista/' + proyecto_id);
					break;
				case 10:
					$('#estructura_reporte').load('/reportehielovista/' + proyecto_id);
					break;
				case 11:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 12:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 13:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 14:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				case 15:
					$('#estructura_reporte').load('/reportequimicosvista/' + proyecto_id);
					break;
				case 16:
					$('#estructura_reporte').load('/reporteserviciopersonalvista/' + proyecto_id);
					break;
				case 17:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Reporte no disponible</p>');
					break;
				default:
					$('#estructura_reporte').html('<p style="text-align: center; font-size: 24px;">Seleccione un tipo de reporte</p>');
					break;
			}
		},
		beforeSend: function () {
			$('#estructura_reporte').html('<div style="text-align: center; font-size: 60px;"><i class="fa fa-spin fa-spinner"></i></div>');
		},
		error: function () {
			$('#estructura_reporte').html('<div style="text-align: center;">Error al cargar parametro</div>');
			return false;
		}
	});//Fin ajax
}

// Funcion para desbloquear el select de contrato
function desbloquearContrato(checkbox) {
	$('.ORGA').prop('disabled', true);
	$(`.NIVEL1`).css('display', 'none')
	$(`.NIVEL2`).css('display', 'none')
	$(`.NIVEL3`).css('display', 'none')
	$(`.NIVEL4`).css('display', 'none')
	$(`.NIVEL5`).css('display', 'none')
	$(`#titleOrganizacion`).css('display', 'none')

	if (checkbox.checked) {

		$('#proyecto_clienterazonsocial').fadeIn(1)

		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.disable();
			selectizeInstance.$wrapper.fadeOut(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', true);
			$('#cliente_id').fadeOut(1)

		}


		if ($("#contrato_id")[0].selectize) {
			var selectizeInstance = $('#contrato_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.enable();

		} else {

			select_contrato(0, 1, consultarIdServicio(1))

			$('#contrato_id').val('');
			$('#contrato_id').prop('disabled', false);

		}





	} else {
		//Mandamos a llamar todos los clientes disponibles
		select_cliente(0)


		//Ocultamos la razon social y mostramos el select de clientes
		$('#proyecto_clienterazonsocial').fadeOut(1)

		if ($("#cliente_id")[0].selectize) {
			var selectizeInstance = $('#cliente_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.enable();
			selectizeInstance.$wrapper.fadeIn(1);

		} else {

			$('#cliente_id').val('');
			$('#cliente_id').prop('disabled', false);
			$('#cliente_id').fadeIn(1)

		}


		//Limiamos y desabilitamos el select de contratos
		if ($("#contrato_id")[0].selectize) {
			var selectizeInstance = $('#contrato_id')[0].selectize;

			selectizeInstance.clear();
			selectizeInstance.disable();

		} else {

			$('#contrato_id').val('');
			$('#contrato_id').prop('disabled', true);

		}


		//Limpiamos todos los campos
		$('#proyecto_clienterazonsocial').val('');
		$('#proyecto_clientenombrecomercial').val('');
		$('#proyecto_clienterfc').val('');
		$('#proyecto_clientegiroempresa').val('');
		$('#proyecto_clientepersonadirigido').val('');
		$('#proyecto_clientepersonacontacto').val('');
		$('#proyecto_clientetelefonocontacto').val('');
		$('#proyecto_clientecelularcontacto').val('');
		$('#proyecto_clientecorreocontacto').val('');
		$('#proyecto_clienteobjetivoservicio').val('');
	}
}


// Funcion para habilitar los diferentes tipos de servicios junto con sus opciones
function mostrarOpcionesServicios(checkbox, servicio) {
	// Verificamos si el necesita del servicio
	if (checkbox.checked) {

		switch (servicio) {
			case 1:
				$('#opciones_hi').fadeIn()

				break;
			case 2:
				$('#opciones_ergo').fadeIn()

				break;
			case 3:
				$('#opciones_psico').fadeIn()

				break;
			case 4:
				$('#opciones_seguridad').fadeIn()

				break;
			default:
				break;
		}

	} else {

		switch (servicio) {
			case 1:
				$('#opciones_hi').fadeOut()

				break;
			case 2:
				$('#opciones_ergo').fadeOut()

				break;
			case 3:
				$('#opciones_psico').fadeOut()

				break;
			case 4:
				$('#opciones_seguridad').fadeOut()

				break;
			default:
				break;
		}
	}
}

function obtenerServicios(ID_PROYECTO) {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoServicios/" + ID_PROYECTO,
		data: {},
		cache: false,
		success: function (dato) {

			var data = dato.data[0];
			$.each(data, function (key, value) {
				var checkbox = $("input[type='checkbox'][name='" + key + "']");

				if (key == "HI" && value == 1) {
					$('#opciones_hi').fadeIn()
				}

				if (key == "ERGO" && value == 1) {
					$('#opciones_ergo').fadeIn()
				}

				if (key == "PSICO" && value == 1) {
					$('#opciones_psico').fadeIn()
				}

				if (key == "SEGURIDAD" && value == 1) {
					$('#opciones_seguridad').fadeIn()
				}



				if (checkbox.length > 0) {
					if (value == 1) {
						checkbox.prop('checked', true);
					} else {
						checkbox.prop('checked', false);
					}
				}

			});

			$('#titleServicios').html('Tipos de servicios');

		},
		beforeSend: function () {
			$('#titleServicios').html('<i class="fa fa-spin fa-spinner"></i>Tipos de servicios');
			$('#opciones_psico').fadeOut(1)
			$('#opciones_ergo').fadeOut(1)
			$('#opciones_hi').fadeOut(1)

		},
		error: function (dato) {
			return false;
		}
	});//Fin ajax
}

function obtenerEstructuraProyecto(ID_PROYECTO) {


	$('.ORGA').prop('disabled', true);
	$(`.NIVEL1`).css('display', 'none')
	$(`.NIVEL2`).css('display', 'none')
	$(`.NIVEL3`).css('display', 'none')
	$(`.NIVEL4`).css('display', 'none')
	$(`.NIVEL5`).css('display', 'none')
	$(`#titleOrganizacion`).css('display', 'none')

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoEstructuta/" + ID_PROYECTO,
		data: {},
		cache: false,
		success: function (dato) {


			//Asignamos valor a las etiquetas y desabilitamos la ID de las etiquetas
			$.each(dato.etiquetas, function (index, etiqueta) {

				$(`#ID_ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.ID_ETIQUETA);
				$(`#ETIQUETA${etiqueta.NIVEL}`).val(etiqueta.NOMBRE_ETIQUETA);
				$(`#ID_ETIQUETA${etiqueta.NIVEL}`).prop('disabled', false);
				$(`#NIVEL${etiqueta.NIVEL}`).prop('disabled', false);

			});


			//Cargamos las opciones de las estructuras
			$.each(dato.estructura, function (key, options) {

				$(`#OPCIONES${key + 1}_ID`).html(options)
				$(`#OPCIONES${key + 1}_ID`).prop('disabled', false);
				$(`.NIVEL${key + 1}`).css('display', 'block')

			});

			//Mostramos el titulo de la estructura si esque existe alguna
			if (dato.estructura.length != 0 && dato.etiquetas.length != 0) {
				$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
				$('#titleOrganizacion').html('Estructura organizacional');
				$('#TIENE_ESTRUCTURA').val(1)

			} else {
				$('#titleOrganizacion').css({ 'display': 'block', 'font-weight': 'bold' });
				$('#titleOrganizacion').html('Cliente o contrato seleccionado sin estructura organizacional');
				$('#TIENE_ESTRUCTURA').val(0)

			}


		}, beforeSend: function () {



		},
		error: function (dato) {

			alert('Error al consultar la estructura del proyecto')

			return false;
		}
	});//Fin ajax


}


function obtenerContactos(checkbox) {
	if (checkbox.checked) {

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectoContactos",
			data: {},
			cache: false,
			success: function (dato) {


				$('#PROYECTO_CONTACTO_SELECT').html(dato.opciones);
				$('#PERSONA_ELABORA_SELECT').html(dato.opciones);

				$('#proyecto_contactocorreo').val('')
				$('#proyecto_contactotelefono').val('')
				$('#proyecto_contactocelular').val('')




				$('#proyecto_personaelabora').css('display', 'none').prop('disabled', true);
				$('#proyecto_contacto').css('display', 'none').prop('disabled', true);
				$('#PROYECTO_CONTACTO_SELECT').css('display', 'block').prop('disabled', false);
				$('#PERSONA_ELABORA_SELECT').css('display', 'block').prop('disabled', false);

				$('#labelContactos').html('Obtener contactos registrados de Results In Performance')

			}, beforeSend: function () {

				$('#labelContactos').html('<i class="fa fa-spin fa-spinner"></i>Obteniendo contactos registrados de Results In Performance')

			},
			error: function (dato) {

				alert('Error al consultar la estructura del proyecto')

				return false;
			}
		});


	} else {


		$('#proyecto_contactocorreo').val('vlicona@results-in-performance.com')
		$('#proyecto_contactotelefono').val('9933146412')
		$('#proyecto_contactocelular').val('9931472682')

		$('#proyecto_personaelabora').css('display', 'block').prop('disabled', false);
		$('#proyecto_contacto').css('display', 'block').prop('disabled', false);
		$('#PROYECTO_CONTACTO_SELECT').css('display', 'none').prop('disabled', true);
		$('#PERSONA_ELABORA_SELECT').css('display', 'none').prop('disabled', true);



	}
}

$('#PROYECTO_CONTACTO_SELECT').on('change', function (e) {

	var selectedOption = $(this).find('option:selected');
	var correo = selectedOption.data('correo');
	var telefono = selectedOption.data('celular');
	var celular = selectedOption.data('telefono');


	$('#proyecto_contactocorreo').val(correo)
	$('#proyecto_contactotelefono').val(telefono)
	$('#proyecto_contactocelular').val(celular)


})


function tabla_usuarios(proyecto_id) {
	try {
		var ruta = "/proyectoUsuarios/" + proyecto_id;

		if (datatable_usuarios != null) {
			datatable_usuarios.clear().draw();
			datatable_usuarios.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_usuarios = $('#tabla_usuarios_asignados').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);

						console.log('error en datatable_usuarios');
						if (numeroejecucion <= 1) {
							tabla_usuarios(proyecto_id);
							numeroejecucion += 1;
						}
					},
					"data": {}
				},
				"columns": [
					{
						"data": "count",
						"defaultContent": "-"
					},
					{
						"data": "nombre",
						"defaultContent": "-"
					},
					{
						"data": "fecha",
						"defaultContent": "-"
					},
					{
						"data": "servicios",
						"defaultContent": "-"
					},
					{
						"data": "estado",
						"defaultContent": "-"
					},

					{
						"orderable": false,
						"data": 'CheckboxEstado',
						"defaultContent": '-'
					},
					{
						"data": "boton_editar",
						"defaultContent": "-"
					},
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": true,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
					"infoEmpty": "No se encontraron registros",
					"infoFiltered": "(Filtrado de _MAX_ registros)",
					"emptyTable": "No hay datos disponibles en la tabla",
					"loadingRecords": "Cargando usuarios asignados....",
					"processing": "Cargando usuarios asignados <i class='fa fa-spin fa-spinner fa-3x'></i>",
					"search": "Buscar",
					"paginate": {
						"first": "Primera",
						"last": "Ultima",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				}
			});
		}

		// Tooltip en DataTable
		datatable_usuarios.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_usuarios(proyecto_id);
	}
}

$("#boton_asignar_usuario").click(function () {

	// Borrar formulario
	$('#form_asignacion_usuario').each(function () {
		this.reset();
	});

	$("#PROYECTO_USUARIO_ID").val($("#proyecto_id").val());
	$("#ID_PROYECTO_USUARIO").val(0);

	// mostrar modal
	$('#modal_asignacion_usuario').modal({ backdrop: false });
});

$("#boton_guardar_usuario").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {

		swal({
			title: "¡Confirme que desea asignar este usuario al proyecto!",
			text: "Realize esta ación para continuar",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Asignar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_asignacion_usuario').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectos',
					data: {
						api: 2
					},
					resetForm: false,
					success: function (dato) {
						// actualiza tabla
						// tabla_usuarios(dato.proyecto_id);
						datatable_usuarios.ajax.reload()

						// mensaje
						swal({
							title: "Asignación realizada",
							text: "" + dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_usuario').html('Asignar usuario <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_asignacion_usuario').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_usuario').html('Asingando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_usuario').html('Asignar usuario <i class="fa fa-save"></i>');

						// mensaje
						swal({
							title: "Error",
							text: dato.responseJSON,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


function cambia_estado_usuario(ID, ACTIVO) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/actualizarEstadoUsuario/" + ID + "/" + ACTIVO,
		data: {},
		cache: false,
		success: function (dato) {

			datatable_usuarios.ajax.reload()
			swal({
				title: "Ación realizada con exito",
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
				showConfirmButton: false
			});

		},
		error: function (dato) {

			alert('Error al modificar el estado del usuario')

			return false;
		}
	});
}



$('#tabla_usuarios_asignados tbody').on('click', 'td>button.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_usuarios.row(tr);

	// Borrar formulario
	$('#form_asignacion_usuario').each(function () {
		this.reset();
	});

	// llenar campos
	$("#PROYECTO_USUARIO_ID").val($("#proyecto_id").val());
	$("#ID_PROYECTO_USUARIO").val(row.data().ID_PROYECTO_USUARIO);

	if (row.data().SERVICIO_HI == 1) {
		$('#SERVICIO_HI').prop('checked', true);
	} else {
		$('#SERVICIO_HI').prop('checked', false);
	}

	if (row.data().SERVICIO_PSICO == 1) {
		$('#SERVICIO_PSICO').prop('checked', true);
	} else {
		$('#SERVICIO_PSICO').prop('checked', false);
	}

	if (row.data().SERVICIO_ERGO == 1) {
		$('#SERVICIO_ERGO').prop('checked', true);
	} else {
		$('#SERVICIO_ERGO').prop('checked', false);
	}

	var selectizeInstance = $('#USUARIO_ID')[0].selectize;  // Obtener la instancia de Selectize
	selectizeInstance.setValue(row.data().USUARIO_ID);  // Asignar el valor deseado


	// mostrar modal
	$('#modal_asignacion_usuario').modal({ backdrop: false });
});



//======================================= CRONOGRAMA DE ACTIVIDADES ==============================================

$("#boton_nueva_actividad").click(function () {

	// Borrar formulario
	$('#form_actividad').each(function () {
		this.reset();
	});

	$('#ID_ACTIVIDAD').val(0)
	$('#COLOR_ACTIVIDAD').val('#1E88E6')
	$('#etiqueta-div').css('background-color', '#1E88E6');

	// Abrir modal
	$('#modal_actividades').modal({ backdrop: false });

});


$("#boton_guardar_actividad").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¿Está seguro de agregar esta actividad al cronograma de trabajo?",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_actividad').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/cliente',
					data: {
						opcion: 12, //CRONOGRAMA DE TRABAJO
						CONTRATO_ID: 0,
						PROYECTO_ID: proyecto_id
					},
					resetForm: false,
					success: function (dato) {

						// Mostramos las actividades del cornograma
						mostrarActividadesCronograma()

						// mensaje
						swal({
							title: "Actividad guardada correctamente",
							text: "Anexo agregado",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_guardar_actividad').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_actividad').attr('disabled', false);

						// cerrar modal
						$('#modal_actividades').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_actividad').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_actividad').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_actividad').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_actividad').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});

function mostrarActividadesCronograma() {

	var html = "";
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerActividadesCronograma/" + 0 + "/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {
			var fechaActual = new Date().toISOString().split('T')[0];

			//=================== VALIDAMOS LA VALIDACION Y AUTORIZACION DEL CRONOGRAMA ================================================
			if (Array.isArray(dato.autorizado) && dato.autorizado.length > 0) {

				info = dato.autorizado[0]

				$('#ID_AUTORIZACION').val(info.ID_AUTORIZACION);
				$('#FECHA_VALIDACION_CRONOGRAMA').val(info.FECHA_VALIDACION_CRONOGRAMA);
				$('#CARGO_VALIDACION_CRONOGRAMA').val(info.CARGO_VALIDACION_CRONOGRAMA);
				$('#NOMBRE_VALIDACION_CRONOGRAMA').val(info.NOMBRE_VALIDACION_CRONOGRAMA);

				//Mostramos la informacion
				$('#divAutorizacion').removeClass('d-none').addClass('d-block');
				$('#infoValidacion').removeClass('d-none').addClass('d-block');
				$('#fechaValidacion').text(info.FECHA_VALIDACION_CRONOGRAMA);
				$('#cargoValido').text(info.CARGO_VALIDACION_CRONOGRAMA);
				$('#nombreValido').text(info.NOMBRE_VALIDACION_CRONOGRAMA);


				if (info.NOMBRE_AUTORIZACION_CRONOGRAMA == null || info.NOMBRE_AUTORIZACION_CRONOGRAMA == '') {

					$('#infoAutorizacion').removeClass('d-block').addClass('d-none');
					$('#FECHA_AUTORIZACION_CRONOGRAMA').val(fechaActual);
					$('#CARGO_AUTORIZACION_CRONOGRAMA').val(rolUsuario);
					$('#NOMBRE_AUTORIZACION_CRONOGRAMA').val(Usuario);
					$('.btn_cronograma').css('display', 'block');


				} else {

					$('#infoAutorizacion').removeClass('d-none').addClass('d-block');
					$('#fechaAutorizo').text(info.FECHA_AUTORIZACION_CRONOGRAMA);
					$('#cargoAutorizo').text(info.CARGO_AUTORIZACION_CRONOGRAMA);
					$('#nombreAutorizo').text(info.NOMBRE_AUTORIZACION_CRONOGRAMA);

					$('#FECHA_AUTORIZACION_CRONOGRAMA').val(info.FECHA_AUTORIZACION_CRONOGRAMA);
					$('#CARGO_AUTORIZACION_CRONOGRAMA').val(info.CARGO_AUTORIZACION_CRONOGRAMA);
					$('#NOMBRE_AUTORIZACION_CRONOGRAMA').val(info.NOMBRE_AUTORIZACION_CRONOGRAMA);
					$('.btn_cronograma').css('display', 'none');

				}

				$('#boton_autorizar_cronograma').html('Autorizar <i class="fa fa-save"></i>');
				$('#boton_autorizar_cronograma_modal').html('Autorizar cronograma <i class="fa fa fa-gavel p-1"></i>');

			} else {

				$('#form_autorizado').each(function () {
					this.reset();
				});

				$('#divAutorizacion').removeClass('d-block').addClass('d-none');
				$('#boton_autorizar_cronograma').html('Validar <i class="fa fa-save"></i>');
				$('#boton_autorizar_cronograma_modal').html('Validar cronograma <i class="fa fa fa-gavel p-1"></i>');
				$('.btn_cronograma').css('display', 'block');

				$('#ID_AUTORIZACION').val(0);
				$('#FECHA_VALIDACION_CRONOGRAMA').val(fechaActual);
				$('#CARGO_VALIDACION_CRONOGRAMA').val(rolUsuario);
				$('#NOMBRE_VALIDACION_CRONOGRAMA').val(Usuario);
			}


			//=================== CREAMOS Y CARGAMAOS TODA LA ESTRUCTURA DEL CRONOGRAMA ================================================

			$('#activity-list').html('')
			datoInfo = dato.data;

			//Generamos los eventos del cronograma
			eventos = datoInfo.map(actividad => ({
				title: actividad.DESCRIPCION_ACTIVIDAD,
				start: `${actividad.FECHA_INICIO_ACTIVIDAD}`,
				end: `${actividad.FECHA_FIN_ACTIVIDAD}`,
				backgroundColor: actividad.COLOR_ACTIVIDAD,
				borderColor: '#FFFFFF',
				extendedProps: { ID_ACTIVIDAD: actividad.ID_ACTIVIDAD } //Pasamos atributos adicionales
			}));


			//Creamos la estructura de las actividades y el cronograma
			$.each(datoInfo, function (index, actividad) {

				//Validamos el los botones de edicion  eliminacion de las actividaes
				if (Array.isArray(dato.autorizado) && dato.autorizado.length > 0) {


					if (info.NOMBRE_AUTORIZACION_CRONOGRAMA == null) {

						btn_editar = `<button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar actividad" onclick="editarActividad(${actividad.ID_ACTIVIDAD})"><i class="fa fa-pencil"></i></button>`
						btn_eliminar = `<button type="button" class="btn btn-danger btn-circle boton_eliminar mx-2" data-toggle="tooltip" data-placement="top" title="Eliminar actividad" onclick="eliminarActividad(${actividad.ID_ACTIVIDAD})" ><i class="fa fa-trash"></i></button>`

					} else {

						btn_editar = ''
						btn_eliminar = ''

					}

				} else {

					btn_editar = `<button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar actividad" onclick="editarActividad(${actividad.ID_ACTIVIDAD})"><i class="fa fa-pencil"></i></button>`
					btn_eliminar = `<button type="button" class="btn btn-danger btn-circle boton_eliminar mx-2" data-toggle="tooltip" data-placement="top" title="Eliminar actividad" onclick="eliminarActividad(${actividad.ID_ACTIVIDAD})" ><i class="fa fa-trash"></i></button>`
				}

				//Creamos el contenido de las actividades y el calendario
				html += `
                <div class="card actividades-card">
                    <div class="card-body">

                        <input type="hidden" class="form-control" id="ID_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.ID_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="FECHA_INICIO_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.FECHA_INICIO_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="FECHA_FIN_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.FECHA_FIN_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="DESCRIPCION_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.DESCRIPCION_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="AGENTE_ID_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.AGENTE_ACTIVIDAD_ID}">
                        <input type="hidden" class="form-control" id="PUNTOS_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.PUNTOS_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="COLOR_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.COLOR_ACTIVIDAD}">

                        <h5 style="color: ${actividad.COLOR_ACTIVIDAD}"><i class="fa fa-star" aria-hidden="true"></i> ${actividad.DESCRIPCION_ACTIVIDAD}</h5>
                        <p> <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Del: ${actividad.FECHA_INICIO_ACTIVIDAD} al: ${actividad.FECHA_FIN_ACTIVIDAD}</p>
                        <div class="col-12" style="justify-content: end; display: flex;">
                            ${btn_editar}
                            ${btn_eliminar}
                        </div>
                    </div>
                </div>
                `
			});

			$('#activity-list').html(html)

			//Cargamos el calendarion una vez tenemos todos los eventos
			inicializarCalendario(eventos)
			$('#boton_autorizar_cronograma_modal').prop('disabled', false)
			$('#boton_nueva_actividad').prop('disabled', false)


		}, beforeSend: function (dato) {

			$('#activity-list').html('<div class="col-12 text-center justify-content-center" ><i class="fa fa-spin fa-spinner fa-5x"></i></div>')
			$('#calendar').html('<div class="col-12 text-center justify-content-center"><h2>Calendario de actividades</h2><i class="fa fa-spin fa-spinner fa-5x"></i></div>')
			$('#boton_autorizar_cronograma_modal').prop('disabled', true)
			$('#boton_nueva_actividad').prop('disabled', true)

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


function inicializarCalendario(eventos) {

	$('#calendar').html('')
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {

		height: 'auto',
		headerToolbar: {
			right: 'prev,next today',
			center: 'title',
			left: 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		locale: 'es',
		buttonText: {
			month: 'Mes',
			week: 'Semana',
			day: 'Día'
		},
		displayEventTime: true,
		initialView: 'dayGridMonth',
		events: eventos,
		dateClick: function (info) { //Evento cuando le damos click a una fecha en especifico
			$(info.dayEl).css('cursor', 'pointer');
			var fechaSeleccionada = info.date;

			$('#form_actividad').each(function () {
				this.reset();
			});
			// Rellenamos los datos almacenados
			$('#ID_ACTIVIDAD').val(0)
			$('#FECHA_INICIO_ACTIVIDAD').val(fechaSeleccionada.toISOString().split('T')[0])
			$('#COLOR_ACTIVIDAD').val('#1E88E6')
			$('#etiqueta-div').css('background-color', '#1E88E6');
			// Abrir modal
			$('#modal_actividades').modal({ backdrop: false });
		},
		eventClick: function (info) { //Evento para cuando le damos click a las fechas en el calendario
			var evento = info.event;
			editarActividad(evento.extendedProps.ID_ACTIVIDAD)
		},
		eventDidMount: function (info) {
			$(info.el).css('cursor', 'pointer');
		}

	});
	calendar.render();


	$('.fc-dayGridMonth-button').css('background', '#0B3F64')
	$('.fc-timeGridWeek-button').css('background', '#0B3F64')
	$('.fc-timeGridDay-button').css('background', '#0B3F64')
	$('.fc-prev-button').css('background', '#0B3F64')
	$('.fc-next-button').css('background', '#0B3F64')
}

function eliminarActividad(ID) {
	swal({
		title: "¿Está seguro de eliminar esta actividad?",
		text: "Se eliminara del cronograma de trabajo",
		type: "info",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Eliminar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			// cerrar msj confirmacion
			swal.close();
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/eliminarActividadCronograma/" + ID,
				data: {},
				cache: false,
				success: function (dato) {

					// Refrescanos las actividades 
					mostrarActividadesCronograma();


					// mensaje
					swal({
						title: "Actividad eliminada correctamente",
						text: "La lista de actividades sera recargada",
						type: "success", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 1500,
						showConfirmButton: false
					});

				},
				error: function (dato) {
					// alert('Error: '+dato.msj);
					return false;
				}
			})
		}
		return false;
	});//Fin ajax
}


function editarActividad(ID) {
	// Borrar formulario
	$('#form_actividad').each(function () {
		this.reset();
	});

	// Rellenamos los datos almacenados
	$('#ID_ACTIVIDAD').val($(`#ID_ACTIVIDAD_${ID}`).val())
	$('#FECHA_INICIO_ACTIVIDAD').val($(`#FECHA_INICIO_ACTIVIDAD_${ID}`).val())
	$('#FECHA_FIN_ACTIVIDAD').val($(`#FECHA_FIN_ACTIVIDAD_${ID}`).val())
	$('#DESCRIPCION_ACTIVIDAD').val($(`#DESCRIPCION_ACTIVIDAD_${ID}`).val())
	$('#AGENTE_ACTIVIDAD_ID').val($(`#AGENTE_ID_ACTIVIDAD_${ID}`).val())
	$('#PUNTOS_ACTIVIDAD').val($(`#PUNTOS_ACTIVIDAD_${ID}`).val())

	color = $(`#COLOR_ACTIVIDAD_${ID}`).val()
	$('#COLOR_ACTIVIDAD').val(color)
	$('#etiqueta-div').css('background-color', color);



	// Abrir modal
	$('#modal_actividades').modal({ backdrop: false });
}

document.getElementById('etiqueta-div').addEventListener('click', function () {
	var colorInput = document.getElementById('COLOR_ACTIVIDAD');

	if (colorInput.style.display === 'none') {
		colorInput.style.display = 'block';
	} else {
		colorInput.style.display = 'none';
	}
});


document.getElementById('COLOR_ACTIVIDAD').addEventListener('input', function () {
	var nuevoColor = this.value;
	document.getElementById('etiqueta-div').style.backgroundColor = nuevoColor;
});


document.addEventListener('click', function (event) {
	var colorInput = document.getElementById('COLOR_ACTIVIDAD');
	var etiquetaDiv = document.getElementById('etiqueta-div');

	if (!colorInput.contains(event.target) && !etiquetaDiv.contains(event.target)) {
		colorInput.style.display = 'none';
	}
});


$("#boton_autorizar_cronograma_modal").click(function () {

	// Abrir modal
	$('#modal_autorizacion').modal({ backdrop: false });

});


$("#boton_autorizar_cronograma").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¿Está seguro de realizar esta acción?",
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_autorizado').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/cliente',
					data: {
						opcion: 13, //Autoziar o Validar cronograma
						CONTRATO_ID: 0,
						PROYECTO_ID: proyecto_id
					},
					resetForm: false,
					success: function (dato) {

						// Refrescanos las actividades cuando el cronograma sea autorizado
						mostrarActividadesCronograma();

						// mensaje
						swal({
							title: "Información guardada correctamente",
							text: "Cronograma correctamente guardado",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#boton_autorizar_cronograma').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_autorizar_cronograma').attr('disabled', false);

						// cerrar modal
						$('#modal_autorizacion').modal('hide');
					},
					beforeSend: function () {
						$('#boton_autorizar_cronograma').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_autorizar_cronograma').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_autorizar_cronograma').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_autorizar_cronograma').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
							text: "" + dato.msj,
							type: "error", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});
						return false;
					}
				}).submit();
				return false;
			}
			else {
				// mensaje
				swal({
					title: "Cancelado",
					text: "",
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//DESCARGAR CONCENTRADO DE ACTIVIDADDES
$('#boton_descargar_cronograma').on('click', function (e) {
	e.preventDefault();

	swal({
		title: "¡Confirme para Generar Concentrado de Actividades!",
		text: "Lista de tareas a realizar.",
		type: "info",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Descargar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
		function (isConfirm) {
			if (isConfirm) {
				// Mostrar mensaje de carga

				$('#boton_descargar_cronograma').prop('disabled', true);
				swal({
					title: "Generando reporte...",
					text: 'Espere un momento, el documento se esta generando...',
					type: "info",
					showConfirmButton: false,
					allowOutsideClick: false
				});

				url = 'generarConcentradoActividades/' + 0 + '/' + proyecto_id;

				$.ajax({
					url: url,
					method: 'GET',
					xhrFields: {
						responseType: 'blob'
					},
					success: function (data) {
						var a = document.createElement('a');
						var url = window.URL.createObjectURL(data);
						a.href = url;
						a.download = `Concentrado de actividades.xlsx`;
						document.body.append(a);
						a.click();
						a.remove();
						window.URL.revokeObjectURL(url);

						// Cerrar mensaje de carga
						swal.close();

						$('#boton_descargar_cronograma').prop('disabled', false);
					},
					error: function () {
						swal({
							title: "Hubo un problema al generar el documento.",
							text: "Intentelo de nuevo, o comuniquelo con el responsable",
							type: "error",
							showConfirmButton: true
						});
					}
				});
			} else {
				// mensaje de cancelación
				swal({
					title: "Cancelado",
					text: "Acción cancelada",
					type: "error",
					buttons: {
						visible: false,
					},
					timer: 500,
					showConfirmButton: false
				});
			}
		});
	return false;
})