// var opcion = parseInt(valor);

// tablas
var tabla_recsensorial = null;
var tabla_recsensorialareas = null;
var tabla_recsensorialcategorias = null;
var datatable_recsensorialanexos = null;
var tabla_control_cambios = null;


// fisicos
var agente_id = 0;
var recsensorial = 0;
var recsensorial_perfil = 0;
var recsensorial_bloqueado = 0;

// modulo AREAS
var select_areacategorias_opciones = "";
var areacategorias_total = 0;

// modulo EPP
var opciones_catepp = "";

// Modulo Puntos por el CLIENTE
var total_agentecliente = 0;
var fila_agentecliente = null;
var rec_autorizado = 0;
var rutaMapa = ''
var nuevaTablaInforme = 0
var nuevaTablaClienteInforme = 0
var nuevaTablaClienteInformeProporcionado = 0;
var optionSelectGruposExposicion = ''
var optionSelectComponentes = ''
var optionSelectComponentes1 = ''
var optionSelectAreas = ''



// Load PAGINA
$(document).ready(function () {
	// mostrar tabla
	tabla_reconocimientosensorial();

	// desactivar menu agentes
	$("#tab_menu2").css('display', 'none');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');
	$("#tab_menu6").css('display', 'none');
	$("#tab_menu9").css('display', 'none');


	// inicializar campo FOTO mapa ubicacion
	$('#inputfotomapa').dropify({
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

	// inicializar campo FOTO plano instalacion
	$('#inputfotoplano').dropify({
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

	// inicializar campo FOTO plano instalacion
	$('#inputfotoinstalacion').dropify({
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

	// Inicializar tooltip
	$('[data-toggle="tooltip"]').tooltip();
});


// validar navegacion menu tab 1
$('.link_menuprincipal').click(function () {
	switch (this.id) {
		case "tab_menu1":
			// Menu
			$("#tab_menu2").css('display', 'none');
			$("#tab_menu3").css('display', 'none');
			$("#tab_menu4").css('display', 'none');
			$("#tab_menu5").css('display', 'none');
			$("#tab_menu6").css('display', 'none');
			$("#tab_menu9").css('display', 'none');

			// Contenido
			$("#tab_1").css('display', 'block');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_6").css('display', 'none');
			$("#tab_9").css('display', 'none');

			break;
		case "tab_menu2":
			$("#tab_2").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_6").css('display', 'none');
			$("#tab_9").css('display', 'none');

			// SELECT DE CLIENTES
			// if ($("#cliente_id")[0].selectize)
			// {
			// 	$("#cliente_id")[0].selectize.destroy();
			// }
			// $('#cliente_id').select2({ placeholder: 'Seleccione un cliente...' }); //Activar campo tipo [Select-Search]
			$('#sustancias_quimicias').select2({ multiple: true }); //Activar campo tipo [Select-Search]
			$('#recsensorialmaquinaria_quimica').selectize();
			selectized = $('#recsensorialmaquinaria_quimica')[0].selectize;
			selectized.$wrapper.fadeOut(1);

			$("#steps_menu_tab1").click();

			break;
		case "tab_menu3":
			$("#tab_3").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_6").css('display', 'none');
			$("#tab_9").css('display', 'none');

			// quitar activo
			$('.link_menuparametro').each(function () {
				$(this.id).removeClass('active');
			});

			// activar 1er agente
			$("#menutab_parametro_" + agente_id).click();
			break;
		case "tab_menu4":
			$("#tab_4").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_6").css('display', 'none');
			$("#tab_9").css('display', 'none');

			$("#steps3_menu_tab1").click();

			funcion_tabla_recsensorialquimicosinventario($("#recsensorial_id").val());
			consulta_evidencia_fotosquimicos(parseInt($("#recsensorial_id").val()), 15);
			break;
		case "tab_menu5":
			$("#tab_5").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_6").css('display', 'none');
			$("#tab_9").css('display', 'none');

			if (recsensorial_alcancefisico > 0) {
				actualiza_tabla_recsensorial_resumen($("#recsensorial_id").val());
			}

			if (recsensorial_alcancequimico > 0) {
				actualiza_tabla_recsensorialquimicos_resumen($("#recsensorial_id").val());
				actualiza_tabla_recsensorialquimicos_resumen_cliente($("#recsensorial_id").val());
			}
			break;
		case "tab_menu6":
			$("#tab_6").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_9").css('display', 'none');

			tabla_agentescliente($("#recsensorial_id").val());
			break;

		case "tab_menu9":
			$("#tab_9").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			$("#tab_6").css('display', 'none');

			$('#SUSTANCIA_PROPORCIONADACLIENTE').select2({ placeholder: 'Seleccione un sustancia...' }); //Activar campo tipo [Select-Search]

			break;


		default:
			break;
	}
});




// validar navegacion menu tab 2 (step wizard)
$('.multisteps-form__progress-btn').click(function () {
	switch (this.id) {
		case "steps_menu_tab2":
			funcion_tabla_recsensorialcategorias($("#recsensorial_id").val());
			break;
		case "steps_menu_tab3":
			funcion_tabla_recsensorialareas($("#recsensorial_id").val());

			// Consulta todas las categorias
			consulta_categorias($("#recsensorial_id").val());
			break;
		case "steps_menu_tab4":
			funcion_tabla_recsensorialmaquinas($("#recsensorial_id").val());
			$('#form_normativa').each(function () {
				this.reset();
			});
			break;
		case "steps_menu_tab5":
			funcion_tabla_recsensorialequipopp($("#recsensorial_id").val());

			// catalogo EPP
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/recsensorialeppcatalogo",
				data: {},
				cache: false,
				success: function (dato) {
					opciones_catepp = dato.opciones;
				},
				error: function (dato) {
					// alert('Error: '+dato.msj);
					return false;
				}
			});//Fin ajax
			break;
		case "steps_menu_tab6":

			break;
		case "steps_menu_tab7":
			tabla_recsensorialanexos($("#recsensorial_id").val());
			tabla_recsensorialanexos2($("#recsensorial_id").val());

			break;
		default:
			// alert(Tab);
			break;
	}
});


function menu_parametros_ocultar() {
	//Ocultar Menu agentes, Modulo evaluacion de Fisicos completo
	$('.link_menuparametro').each(function () {
		$("#" + this.id).removeClass('active');
		$("#" + this.id).css('display', 'none');
	});

	//Ocultar las opciones select, Modulo evaluacion de Puntos de muestreos por el CLIENTE
	$('.selectagente').each(function () {
		$("#" + this.id).css('display', 'none');
	});
}

//===============================================================

var tabla_recsensorial = null;

function tabla_reconocimientosensorial() {
	try {
		var ruta = "/tablareconocimientopsico";

		if (tabla_recsensorial != null) {
			// Destruir la tabla existente antes de crear una nueva
			tabla_recsensorial.destroy();

		}

		tabla_recsensorial = $('#tabla_reconocimiento_sensorial').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"error": function (xhr, error, code) {
					console.log('error en tabla_recsensorial');
				},
				"data": {}
			},
			"columns": [
				{
					"data": "numero_registro",
					"defaultContent": "-"
				},
				{
					"data": "id",
					"defaultContent": "-"
				},
				{
					"data": "rfc",
					"defaultContent": "-"
				},
				{
					"data": "codigopostal",
					"defaultContent": "-"
				},
				{
					"data": "proyecto_folio",
					"defaultContent": "--"
				},
				{
					"data": "instalacion"
				},
				{
					"className": 'mostrar',
					"orderable": false,
					"data": 'boton_mostrar',
					"defaultContent": '-'
				}
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
			"order": [[0, "DESC"]],
			"ordering": true,
			"processing": true,
			"responsive": true,
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
					"last": "Última",
					"next": "Siguiente",
					"previous": "Anterior"
				}
			}
		});

		tabla_recsensorial.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	} catch (exception) {
		console.error("Error en tabla_reconocimientosensorial:", exception);
	}
}


$("#boton_nuevo_reconocimiento").click(function () {


	$('#inputfotomapa').attr('required', false);

	$('#titleOrganizacionLabel').fadeIn();

	$("#estructura-container").css('display', 'none');

	// mostrar TAB principal
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');
	$("#tab_menu6").css('display', 'none');

	// ocultar secciones
	$("#steps_menu_tab2").css('display', 'none');
	$("#steps_menu_tab3").css('display', 'none');
	$("#steps_menu_tab4").css('display', 'none');
	$("#steps_menu_tab5").css('display', 'none');
	$("#steps_menu_tab6").css('display', 'none');
	$("#steps_menu_tab7").css('display', 'none');

	// LIMPIAR TITULO DEL RECONOCIMIENTO
	$(".div_reconocimiento_folios").html('FOLIO');
	$(".div_reconocimiento_alcance").html('Reconocimiento');
	$(".div_reconocimiento_instalacion").html('INSTALACIÓN');

	// Borrar border warning
	$('#divListaAgentes').removeClass('checkbox_warning');


	// Resetear campo FOTO INSTALACION
	$('#inputfotoinstalacion').val('');
	$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
	$('#inputfotoinstalacion').dropify().data('dropify').clearElement();
	$('#inputfotoinstalacion').attr('required', true);
	$("#boton_descargarfotoinstalacion").css('display', 'none');

	// BOton evidencia fotografica quimicos
	$("#boton_nueva_fotoevidenciaquimicos").attr('disabled', false);

	// Desactivar boton descargas FOTO
	$("#boton_descargarmapaubicacion").css('display', 'none');
	$("#boton_descargarplanoinstalacion").css('display', 'none');

	// Visor quimico PDF
	$('#visor_pdfquimicos').attr('src', '/assets/images/nada.jpg');
	$("#visor_pdfquimicos").css('display', 'none');
	$("#visor_pdfquimicos_icono").css('display', 'block');

	// Borrar formulario
	$('#form_recsensorial').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#recsensorial_id").val(0);

	// Desbloquear campos alcances
	$("#recsensorial_alcancefisico").attr('disabled', false);
	$("#recsensorial_alcancequimico").attr('disabled', false);

	//Campos exclusivos para Pemex
	$('#catregion_id').attr('disabled', false);
	$('#catsubdireccion_id').attr('disabled', false);
	$('#catgerencia_id').attr('disabled', false);
	$('#catactivo_id').attr('disabled', false);



	//Ocultamos la seccion de validar quimicos ya que esa parte se rellena una vez esta finalizado el reconocimiento
	$('#finalizarQuimico').fadeOut(0)


	//Seteamos y ocultamos los valores de clientes y contratos obtenidos por el cliente
	$('#cliente_id').val('')
	$('#contrato_id').val('')
	$('#requiere_contrato').val('')
	$('#descripcion_contrato').val('')
	$('#descripcion_cliente').val('')
	$('#infoCliente').empty().css('border-style', 'none')


	// seleccionar TAB	
	$("#steps_menu_tab1").click();
	$("#tab_menu2").click();

	//Cragamos la lista de los folios de los proyectos
	cargarFoliosProyecto(0);

});


function activa_stepforms() {
	$("#steps_menu_tab2").css('display', 'block');
	$("#steps_menu_tab3").css('display', 'block');
	$("#steps_menu_tab4").css('display', 'block');
	$("#steps_menu_tab5").css('display', 'block');
	$("#steps_menu_tab6").css('display', 'block');
	$("#steps_menu_tab7").css('display', 'block');
}


function desactiva_stepforms() {
	$("#steps_menu_tab2").css('display', 'none');
	$("#steps_menu_tab3").css('display', 'none');
	$("#steps_menu_tab4").css('display', 'none');
	$("#steps_menu_tab5").css('display', 'none');
	$("#steps_menu_tab6").css('display', 'none');
	$("#steps_menu_tab7").css('display', 'none');
}


$('input[name="informe_del_cliente"]').change(function () {

	var valor = $(this).val();

	if (valor === '1') {

		cliente_lista(lista_clientes, $('#cliente_id').val())

	} else if (valor === '0') {
		///Limpiamos la informacion del cliente
		$("#recsensorial_empresa").val('');
		$("#recsensorial_rfc").val('');
		$("#recsensorial_representantelegal").val('');

	}
});

// Selecciona RECONOCIMIENTO
$('#tabla_reconocimiento_sensorial tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = tabla_recsensorial.row(tr);

	$('#inputfotomapa').attr('required', false);
	$('#infoCliente').empty().css('border-style', 'none');


	if (row.data().proyecto_folio == null) {

		$(".organizacional").css('display', 'block');
		$("#estructura-container").css('display', 'none');


	} else {
		$(".organizacional").css('display', 'none');

		$("#estructura-container").css('display', 'flex');

	}
	$("#steps_menu_tab2").css('display', 'block');
	$("#steps_menu_tab3").css('display', 'block');
	$("#steps_menu_tab4").css('display', 'block');
	$("#steps_menu_tab5").css('display', 'block');
	$("#steps_menu_tab6").css('display', 'block');
	$("#steps_menu_tab7").css('display', 'block');

	// Menu principal
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');
	$("#tab_menu6").css('display', 'none');
	$("#tab_menu9").css('display', 'none');

	// Borrar formulario
	$('#form_recsensorial').each(function () {
		this.reset();
	});

	// Campos hidden
	recsensorial = row.data().id;
	$("#recsensorial_id").val(row.data().id);

	//Obtemos la estructura del proyecto
	obtenerEstructuraProyectos(row.data().proyecto_folio, 0);

	//Obtenemos la lista de los folios de proyectos y los asignamos
	cargarFoliosProyecto(row.data().proyecto_folio);



	var cliente = row.data().descripcion_cliente == null ? "Sin asignar cliente" : row.data().descripcion_cliente;
	var contrato = row.data().descripcion_contrato == null ? "Sin asignar contrato" : row.data().descripcion_contrato;


	// $('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
	// 						<h4>${cliente}</h4>
	// 						<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> Formalización del servicio</h2>
	// 						<h4 class="mb-2">${contrato}</h4>`).css('border-style','dotted')

	$("#empresa").val(row.data().empresa);
	$("#codigopostal").val(row.data().codigopostal);
	$("#rfc").val(row.data().rfc);
	$("#ordenservicio").val(row.data().ordenservicio);
	$("#representantelegal").val(row.data().representantelegal);
	$("#representanteseguridad").val(row.data().representanteseguridad);
	$("#direccion").val(row.data().direccion);
	$("#instalacion").val(row.data().instalacion);
	$("#coordenadas").val(row.data().coordenadas);
	$("#fechainicio").val(row.data().fechainicio);
	$("#fechafin").val(row.data().fechafin);
	$("#actividadprincipal").val(row.data().actividadprincipal);
	$("#descripcionproceso").val(row.data().descripcionproceso);
	$("#observaciones").val(row.data().observaciones);

	$("#ordentrabajo_id").val(row.data().ordentrabajo_id);
	$("#proyecto_folio").val(row.data().proyecto_folio);

	//Mostramos si la informacion del contrato es la que se le da al reconocimiento
	if (row.data().informe_del_cliente == 1) {
		$('#informe_del_cliente_si').prop('checked', true);
	} else {
		$('#informe_del_cliente_no').prop('checked', true);

	}

	if (row.data().fotoubicacion){
		var archivo = row.data().fotoubicacion;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/mostrarmapapsico/0/' + row.data().id + extension;
		
		rutaMapa = imagenUrl

		// INPUT FOTO UBICACION
		if ($('#inputfotomapa').data('dropify'))
		{
			$('#inputfotomapa').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#inputfotomapa').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#inputfotomapa').dropify().data('dropify').init();
		}
		else
		{
			// $('#inputfotomapa').attr('data-height', 400);
			$('#inputfotomapa').attr('data-default-file', imagenUrl);
			$('#inputfotomapa').dropify({
				messages: {
					'default': 'Arrastre la imagen aquí o haga click',
					'replace': 'Arrastre la imagen o haga clic para reemplazar',
					'remove':  'Quitar',
					'error':   'Ooops, ha ocurrido un error.'
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
		}

		// No requerir campo FOTO
		$('#inputfotomapa').attr('required', false);

		// Activar boton descarga
		$("#boton_descargarmapaubicacion").css('display', 'block');
	}
	else
	{
		$("#boton_descargarmapaubicacion").css('display', 'none');
	}

	// OBTENER FOTO PLANO
	if (row.data().fotoplano) {
		var archivo = row.data().fotoplano;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/mostrarplanopsico/0/' + row.data().id + extension;

		rutaMapa = imagenUrl


		// INPUT FOTO PLANO
		if ($('#inputfotoplano').data('dropify')) {
			$('#inputfotoplano').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#inputfotoplano').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#inputfotoplano').dropify().data('dropify').init();
		}
		else {
			// $('#inputfotoplano').attr('data-height', 400);
			$('#inputfotoplano').attr('data-default-file', imagenUrl);
			$('#inputfotoplano').dropify({
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
		}

		// No requerir campo FOTO
		$('#inputfotoplano').attr('required', false);

		// Activar boton descarga
		$("#boton_descargarplanoinstalacion").css('display', 'block');
	}
	else {
		$("#boton_descargarplanoinstalacion").css('display', 'none');
	}

	// OBTENER FOTO INSTALACION
	if (row.data().fotoinstalacion)
		{
			var archivo = row.data().fotoinstalacion;
			var extension = archivo.substring(archivo.lastIndexOf("."));
			var imagenUrl = '/mostrarfotoinstalacionpsico/0/'+row.data().id + extension;
	
			// INPUT FOTO INSTALACION
			$('#inputfotoinstalacion').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#inputfotoinstalacion').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#inputfotoinstalacion').dropify().data('dropify').init();
	
			// No requerir campo FOTO
			$('#inputfotoinstalacion').attr('required', false);
	
			// Activar boton descarga
			$("#boton_descargarfotoinstalacion").css('display', 'block');
		
		} else {
	
			// Resetear campo FOTO INSTALACION
			$('#inputfotoinstalacion').val('');
			$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
			$('#inputfotoinstalacion').dropify().data('dropify').clearElement();
	
			// No requerir campo FOTO
			$('#inputfotoinstalacion').attr('required', false);
			$("#boton_descargarfotoinstalacion").css('display', 'none');
		}


	// Perfil
	recsensorial_perfil = parseInt(row.data().perfil);


	// Colocar nombre del reconocimieto
	$(".div_reconocimiento_instalacion").html(row.data().recsensorial_instalacion);
	if (parseInt(row.data().recsensorial_alcancefisico) > 0 && parseInt(row.data().recsensorial_alcancequimico) > 0) {
		$(".div_reconocimiento_folios").html(row.data().recsensorial_foliofisico + ' <span style="color: #999999;">y</span> ' + row.data().recsensorial_folioquimico);
		$(".div_reconocimiento_alcance").html('Reconocimiento de Físicos y Químicos');
	}
	else if (parseInt(row.data().recsensorial_alcancefisico) > 0) {
		$(".div_reconocimiento_folios").html(row.data().recsensorial_foliofisico);
		$(".div_reconocimiento_alcance").html('Reconocimiento de Físicos');
	}
	else {
		$(".div_reconocimiento_folios").html(row.data().recsensorial_folioquimico);
		$(".div_reconocimiento_alcance").html('Reconocimiento de Químicos');
	}





	//=======================================


	// seleccionar TAB
	$("#steps_menu_tab1").click();
	$("#tab_menu2").click();
});








$("#boton_descargarmapaubicacion").click(function () {
	window.open('/mostrarmapa/1/' + recsensorial);
});


$("#boton_descargarplanoinstalacion").click(function () {
	window.open('/mostrarplano/1/' + recsensorial);
});


$("#boton_descargarfotoinstalacion").click(function () {
	window.open('/mostrarfotoinstalacion/1/' + recsensorial);
});



//Version actualizada de la descarga del Informe de reconocimientos
function reporte(recsensorial_id, recsensorial_tipo, boton, tipo) {

	tipodoc = parseInt(tipo)

	if ($('#boton_descargarquimicosdoc').hasClass('desbloqueado')) {

		var tipo = 'físicos';
		var nombreInstalacion = $('#recsensorial_instalacion').val();

		if (parseInt(recsensorial_tipo) === 2) {
			tipo = 'químicos';
		}

		swal({
			title: "¡Confirme imprimir!",
			text: "Reconocimiento de " + tipo + "\n\nSolo se puede descargar una vez, la siguiente debe ser con autorización del administrador",
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
					swal({
						title: "Generando documento",
						text: 'Espere un momento, el documento se esta documento se esta generando...',
						type: "info",
						showConfirmButton: false,
						allowOutsideClick: false
					});

					var url = "";
					if (parseInt(recsensorial_tipo) === 1) {
						if (parseInt($("#recsensorial_tipocliente").val()) === 0) { // 0 = Cliente, 1 = Pemex
							url = "/recsensorialreporte1wordcliente/" + recsensorial_id + "/" + tipodoc;
						} else {
							url = "/recsensorialreporte1word/" + recsensorial_id + "/" + tipodoc;
						}
					} else {
						if (parseInt($("#recsensorial_tipocliente").val()) === 0) { // 0 = Cliente, 1 = Pemex
							url = "/recsensorialquimicosreporte1wordcliente/" + recsensorial_id + "/" + tipodoc;
						} else {
							url = "/recsensorialquimicosreporte1word/" + recsensorial_id + "/" + tipodoc;
						}
					}


					if (tipodoc == 1) {
						ext = '.docx'
					} else {
						ext = '.zip'
					}

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
							a.download = `Informe - Reconocimiento de Químicos - ${nombreInstalacion}${ext}`;
							document.body.append(a);
							a.click();
							a.remove();
							window.URL.revokeObjectURL(url);

							// Cerrar mensaje de carga
							swal.close();

							if (parseInt(recsensorial_tipo) !== 1 && parseInt($("#recsensorial_tipocliente").val()) === 1) {
								//Recargamos la tabla de reconocimiento para que se ajusten los cambios de que el reconocimiento ha sido finalizado
								tabla_recsensorial.ajax.url('/tablarecsensorial').load();
								//Mostramos la seccion para poder validar el reconocimiento
								$('#finalizarQuimico').fadeIn(0);
							}
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

	} else {

		swal({
			title: "Informe no disponible",
			text: "Es necesario guardar los datos de la portada y confirmar puntos de muestreo y POE ",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2500,
			showConfirmButton: false
		});

	}


}

function validarHojaSeguridad(check) {

	if (check.checked) {
		$('#anexo_archivo').prop('required', false).prop('disabled', true).val('');

	} else {

		$('#anexo_archivo').prop('required', true).prop('disabled', false);

	}

}





// Vaciar contenido del visor al cerrar modal

$("#modalvisor_boton_cerrar").click(function () {
	$('#visor_documento').attr('src', '/assets/images/cargando.gif');
});



$("#boton_guardar_recsensorial").click(function () {

	$('#recsensorial_ordenservicio').attr('required', false);
	$('#inputfotomapa').attr('required', false);
	$('#inputfotomapa').prop('required', false);

	if ($('#proyecto_folio').val() != "") {

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {


				swal({
					title: "¡Confirme que desea guardar!",
					text: "Reconocimiento sensorial",
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
						$('#form_recsensorial').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/reconocimientoPsicosocial',
							data: {
								opcion: 1,
							},
							resetForm: false,
							success: function (dato) {
								// Campos Hidden
								$("#recsensorial_id").val(dato.recsensorial.id);
								recsensorial = dato.recsensorial.id;

								// ocultar menu parametro en el modulo evaluacion
								menu_parametros_ocultar();

		
								activa_stepforms();

							

								// actualiza tabla general RECSENSORIAL
								tabla_reconocimientosensorial();

								// mensaje
								swal({
									title: "¡El reconocimiento fue guardado exitosamente!.",
									text: "" + dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 4500,
									showConfirmButton: false
								});

								// actualiza boton
								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
								return false;
							},
							beforeSend: function () {
								$('#boton_guardar_recsensorial').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							},
							error: function (dato) {
								// actualiza boton
								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
								// mensaje
								swal({
									title: "Error",
									text: "Error en la acción: " + dato,
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


	} else {
		// mensaje

		$('html, body').animate({
			scrollTop: $("#primeraParte").offset().top
		}, 1000);

		swal({
			title: "Falta seleccionar un FOLIO de proyecto",
			text: "Es necesario seleccionar este reconocimiento con un FOLIO de proyecto para poder continuar!.",
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


function tabla_agentescliente(recsensorial_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialagentesclientetabla/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			if (parseInt(dato.total_agentescliente) > 0) {
				// Vaciar filas
				$('#tabla_agentesclientes tbody').html('');


				// Pintar filas agentes cliente
				$.each(dato.tabla, function (key, value) {
					// Tipo de instalacion
					var puntos = parseInt(value.agentescliente_puntos);
					var tipo_instalacion = "";

					if (parseInt(value.agentescliente_agenteid) != 16) {
						switch (true) {
							case ((puntos > 150) && (parseInt($('#recsensorial_tipocliente').val()) == 1)): // tipo cliente (1 = pemex, 0 = cliente)
								tipo_instalacion = "Extra grande";
								break;
							case (puntos > 80):
								tipo_instalacion = "Grande";
								break;
							case (puntos > 40):
								tipo_instalacion = "Mediana";
								break;
							case (puntos > 20):
								tipo_instalacion = "Chica";
								break;
							default:
								tipo_instalacion = "Extra chica";
								break;
						}
					}
					else  //16 servicio al personal
					{
						switch (true) {
							case (puntos > 49):
								tipo_instalacion = "Extra grande";
								break;
							case (puntos > 30):
								tipo_instalacion = "Grande";
								break;
							case (puntos > 10):
								tipo_instalacion = "Mediana";
								break;
							case (puntos > 4):
								tipo_instalacion = "Chica";
								break;
							case (puntos > 1):
								tipo_instalacion = "Extra Chica";
								break;
							default:
								tipo_instalacion = "N/A";
								break;
						}
					}

					$("#tabla_agentesclientes tbody").prepend('<tr>' +
						'<td>' +
						'<textarea class="form-control textarea_agentedatos" style="display: none;" name="lista_agentedatos[]">' +
						value.agentescliente_agenteid +
						'ˏ' + value.agentescliente_nombre +
						'ˏ' + value.agentescliente_tipoanalisis +
						'ˏ' + value.agentescliente_puntos +
						'ˏ' + value.agentescliente_analisis +
						'ˏ' + value.agentescliente_observacion +
						'</textarea>' +
						'<input type="hidden" class="form-control" name="lista_agenteid[]" value="' + value.agentescliente_agenteid + '">' +
						'<input type="hidden" class="form-control" name="lista_agentenombre[]" value="' + value.agentescliente_nombre + '">' +
						'<input type="hidden" class="form-control" name="lista_agentetipo[]" value="' + value.agentescliente_tipoanalisis + '">' +
						value.agentescliente_nombre +
						'</td>' +
						'<td>' +
						'<input type="hidden" class="form-control" name="lista_agentepuntos[]" value="' + value.agentescliente_puntos + '">' +
						'<div class="round" style="background-color: #999999; font-size: 16px;"><i>' + value.agentescliente_puntos + '</i></div>' +
						'</td>' +
						'<td>' + tipo_instalacion + '</td>' +
						'<td style="text-align: justify;">' +
						'<input type="hidden" class="form-control" name="lista_agenteanalisis[]" value="' + value.agentescliente_analisis + '">' +
						value.agentescliente_analisis +
						'</td>' +
						'<td style="text-align: justify;">' +
						'<input type="hidden" class="form-control" name="lista_agenteobservacion[]" value="' + value.agentescliente_observacion + '">' +
						value.agentescliente_observacion +
						'</td>' +
						'<td class="editar"><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button></td>' +
						'<td class="eliminar" title="' + value.agentescliente_nombre + '"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
						'</tr>');
				});


				// Contador agentes clientes
				total_agentecliente = parseInt(dato.total_agentescliente);
				$('#total_agentecliente').html(total_agentecliente);


				// // Activar boton guardar
				// $("#botonguardar_agentescliente").attr('disabled', false);
			}
			else {
				$('#tabla_agentesclientes tbody').html('<tr><td colspan="7" align="center">No hay datos que mostrar</td></tr>');


				// Contador agentes clientes
				total_agentecliente = 0;
				$('#total_agentecliente').html(total_agentecliente);


				// // Desactivar boton guardar
				// $("#botonguardar_agentescliente").attr('disabled', true);
			}


			if (parseInt(dato.total_agentescliente) > 0 && parseInt(dato.recsensorial_bloqueado) == 0) {
				// Activar boton guardar
				$("#botonguardar_agentescliente").attr('disabled', false);
			}
			else {
				// Desactivar boton guardar
				$("#botonguardar_agentescliente").attr('disabled', true);
			}


			// actualiza tabla
			// $('#tabla_agentesclientes tbody').html(dato.tabla);
		},
		beforeSend: function () {
			$('#tabla_agentesclientes tbody').html('<tr><td colspan="7" align="center"><i class="fa fa-spin fa-spinner fa-3x"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_agentesclientes tbody').html('<tr><td colspan="7" align="center">Error al consultar la información</td></tr>');

			// Contador agentes clientes
			total_agentecliente = 0;
			$('#total_agentecliente').html(total_agentecliente);

			// Desactivar boton guardar
			$("#botonguardar_agentescliente").attr('disabled', true);
			return false;
		}
	});//Fin ajax
}


$("#boton_nuevo_agentescliente").click(function () {
	// Inicializamos Select TIPO
	$('#agentescliente_tipo').html('<option value=""></option>');

	// Diseño campos
	$('#div_campo_tipoagente').css('display', 'block');
	$('#div_campo_nombreagente').css('display', 'none');

	// Activar campos
	// $('#agentescliente_analisis').attr('readonly', false);
	$('#agentescliente_tipo').attr('disabled', false);
	$('#agentescliente_tipo').attr('required', true);
	$('#agentescliente_tipo').html('<option value=""></option>');

	// Borrar formulario
	$('#form_agentescliente').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#agentescliente_accion').val(0);

	// mostrar modal
	$('#modal_agentescliente').modal({ backdrop: false });
});


function valida_tipoanalisis(select_agente) {
	// Llenar campo nombre del agente
	$("#agentescliente_nombre").val($("#agentescliente_agenteid option:selected").text());
	$('#agentescliente_nombre').attr('required', false);

	// Mostrar campo TIPO
	$('#div_campo_tipoagente').css('display', 'block');
	$('#div_campo_nombreagente').css('display', 'none');

	// Activar campo TIPO ANALISIS
	$('#agentescliente_analisis').val('');
	$('#agentescliente_analisis').attr('placeholder', 'Ejem. Color, Olor y sabor, Turbiedad...');

	var agente_id = parseInt(select_agente);
	switch (agente_id) {
		case 9: //AGUA
			// activar campo
			$('#agentescliente_tipo').attr('disabled', false);
			$('#agentescliente_tipo').attr('required', true);

			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
			break;
		case 10: //HIELO
			// activar campo
			$('#agentescliente_tipo').attr('disabled', false);
			$('#agentescliente_tipo').attr('required', true);

			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
			break;
		case 11: //ALIMENTOS
			// activar campo
			$('#agentescliente_tipo').attr('disabled', false);
			$('#agentescliente_tipo').attr('required', true);

			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option><option value="Crudos">Crudos</option><option value="Cocidos">Cocidos</option>');
			break;
		case 12: //ALIMENTOS
			// activar campo
			$('#agentescliente_tipo').attr('disabled', false);
			$('#agentescliente_tipo').attr('required', true);

			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option><option value="Vivas">Vivas</option><option value="Inertes">Inertes</option>');
			break;
		case 15: //QUIMICOS
			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option>');

			// DESACTIVAR campo
			$('#agentescliente_tipo').attr('required', false);
			$('#agentescliente_tipo').attr('disabled', true);

			// Diseño campos
			$('#div_campo_tipoagente').css('display', 'none');
			$('#div_campo_nombreagente').css('display', 'block');

			// Campo nombre agente
			$('#agentescliente_nombre').val('');
			$('#agentescliente_nombre').attr('required', true);

			// Desactivar campo TIPO ANALISIS
			$('#agentescliente_analisis').attr('placeholder', 'Ejem. Análisis y/o blanco de Xileno');
			break;
		case 17: //Mapa de riesgos
			// activar campo
			$('#agentescliente_tipo').attr('disabled', false);
			$('#agentescliente_tipo').attr('required', true);

			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option><option value="Tipo 1">Tipo 1</option><option value="Tipo 2">Tipo 2</option>');
			break;
		default:
			// llenar select tipo de alcance agente
			$('#agentescliente_tipo').html('<option value=""></option>');

			// DESACTIVAR campo
			$('#agentescliente_tipo').attr('required', false);
			$('#agentescliente_tipo').attr('disabled', true);
			break;
	}
}


function agrega_tipoanalisis(select_analisis) {
	$("#agentescliente_nombre").val($("#agentescliente_agenteid option:selected").text() + " (" + $("#" + select_analisis.id + " option:selected").text() + ")");
}


$("#botonagregar_agentescliente").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		if (total_agentecliente == 0) {
			// Vaciar filas
			$('#tabla_agentesclientes tbody').html('');
		}

		// Tipo de instalacion
		var puntos = parseInt($("#agentescliente_puntos").val());
		var tipo_instalacion = "";

		if (parseInt($("#agentescliente_agenteid").val()) != 16) {
			switch (true) {
				case (puntos > 150):
					tipo_instalacion = "Extra grande";
					break;
				case (puntos > 80):
					tipo_instalacion = "Grande";
					break;
				case (puntos > 40):
					tipo_instalacion = "Mediana";
					break;
				case (puntos > 20):
					tipo_instalacion = "Chica";
					break;
				default:
					tipo_instalacion = "Extra chica";
					break;
			}
		}
		else  //16 servicio al personal
		{
			switch (true) {
				case (puntos > 49):
					tipo_instalacion = "Extra grande";
					break;
				case (puntos > 30):
					tipo_instalacion = "Grande";
					break;
				case (puntos > 10):
					tipo_instalacion = "Mediana";
					break;
				case (puntos > 4):
					tipo_instalacion = "Chica";
					break;
				case (puntos > 1):
					tipo_instalacion = "Extra Chica";
					break;
				default:
					tipo_instalacion = "N/A";
					break;
			}
		}

		// Eliminamos agente repetido
		if (parseInt($('#agentescliente_accion').val()) == 1) {
			// obtener fila tabla
			// fila_agentecliente = $(this);
			var tr = fila_agentecliente.closest('tr');
			fila_agentecliente.closest("tr").remove(); // eliminar fila TR

			// Contador agentes clientes
			total_agentecliente -= 1;
		}

		// Agregamos fila del agente a la tabla
		$("#tabla_agentesclientes tbody").prepend('<tr>' +
			'<td>' +
			'<textarea class="form-control textarea_agentedatos" style="display: none;" name="lista_agentedatos[]">' +
			$("#agentescliente_agenteid").val() +
			'ˏ' + $("#agentescliente_nombre").val() +
			'ˏ' + $("#agentescliente_tipo").val() +
			'ˏ' + $("#agentescliente_puntos").val() +
			'ˏ' + $("#agentescliente_analisis").val() +
			'ˏ' + $("#agentescliente_observacion").val() +
			'</textarea>' +
			'<input type="hidden" class="form-control" name="lista_agenteid[]" value="' + $("#agentescliente_agenteid").val() + '">' +
			'<input type="hidden" class="form-control" name="lista_agentenombre[]" value="' + $("#agentescliente_nombre").val() + '">' +
			'<input type="hidden" class="form-control" name="lista_agentetipo[]" value="' + $("#agentescliente_tipo").val() + '">' +
			$("#agentescliente_nombre").val() +
			'</td>' +
			'<td>' +
			'<input type="hidden" class="form-control" name="lista_agentepuntos[]" value="' + $("#agentescliente_puntos").val() + '">' +
			'<div class="round" style="background-color: #999999; font-size: 16px;"><i>' + $("#agentescliente_puntos").val() + '</i></div>' +
			'</td>' +
			'<td>' + tipo_instalacion + '</td>' +
			'<td style="text-align: justify;">' +
			'<input type="hidden" class="form-control" name="lista_agenteanalisis[]" value="' + $("#agentescliente_analisis").val() + '">' +
			$("#agentescliente_analisis").val() +
			'</td>' +
			'<td style="text-align: justify;">' +
			'<input type="hidden" class="form-control" name="lista_agenteobservacion[]" value="' + $("#agentescliente_observacion").val() + '">' +
			$("#agentescliente_observacion").val() +
			'</td>' +
			'<td class="editar"><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button></td>' +
			'<td class="eliminar" title="' + $("#agentescliente_nombre").val() + '"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
			'</tr>');

		// Contador agentes clientes
		total_agentecliente += 1;
		$('#total_agentecliente').html(total_agentecliente);

		// Activar bonton guardar
		$("#botonguardar_agentescliente").attr('disabled', false);

		// cerrar modal
		$('#modal_agentescliente').modal('hide');

		// mensaje
		swal({
			title: "Agregado a la lista",
			text: "Presione Guardar al terminar",
			type: "success", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 3000,
			showConfirmButton: false
		});

		return false;
	}
});


// Editar Agente cliente de la lista
$('#tabla_agentesclientes tbody').on('click', '.editar', function () {
	// obtener fila tabla
	fila_agentecliente = $(this);
	var tr = fila_agentecliente.closest('tr');
	// alert(tr.index());
	// fila.closest("tr").remove(); // eliminar fila TR

	// Borrar formulario
	$('#form_agentescliente').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#agentescliente_accion').val(1);

	// Recorremos las filas de la tabla buscando el regsitro seleccionado
	$('.textarea_agentedatos').each(function (index, value) {
		// alert(index+' -> '+this.value);
		if (tr.index() === index) {
			// Obtenemos datos
			var datosagente = this.value.split('ˏ');

			// Modificar campo TIPO
			valida_tipoanalisis((datosagente[0]));

			// Llenar campos
			$('#agentescliente_agenteid').val(datosagente[0]);
			$('#agentescliente_nombre').val(datosagente[1]);
			$('#agentescliente_tipo').val(datosagente[2]);
			$('#agentescliente_puntos').val(datosagente[3]);
			$('#agentescliente_analisis').val(datosagente[4]);
			$('#agentescliente_observacion').val(datosagente[5]);
		}
	});

	// mostrar modal
	$('#modal_agentescliente').modal({ backdrop: false });
});


// Eliminar Agente cliente de la lista
$('#tabla_agentesclientes tbody').on('click', '.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar de la lista?",
		text: "" + this.title,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Eliminar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {
			var tr = fila.closest('tr');
			fila.closest("tr").remove(); // eliminar fila TR

			// Contador agentes clientes
			total_agentecliente -= 1;
			$('#total_agentecliente').html(total_agentecliente);

			if (total_agentecliente == 0) {
				// Desactivar bonton guardar
				$("#botonguardar_agentescliente").attr('disabled', true);
			}

			// mensaje
			swal({
				title: "Registro eliminado",
				text: "Presione Guardar al terminar",
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
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


$("#botonguardar_agentescliente").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// enviar datos
		$('#form_agentesclientelista').ajaxForm({
			dataType: 'json',
			type: 'POST',
			url: '/recsensorialagentescliente',
			data: {
				recsensorial_id: $('#recsensorial_id').val(),
			},
			resetForm: false,
			success: function (dato) {
				// actualiza boton
				$('#botonguardar_agentescliente').html('Guardar (<span id="total_agentecliente">' + total_agentecliente + '</span>) registros');

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
				$('#botonguardar_agentescliente').html('Guardando (' + total_agentecliente + ') registros <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (dato) {
				// actualiza boton
				$('#botonguardar_agentescliente').html('Guardar (<span id="total_agentecliente">' + total_agentecliente + '</span>) registros');

				// mensaje
				swal({
					title: "Error",
					text: "Error en la acción: " + dato,
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
});


//===============================================================


$("#boton_nueva_categoria").click(function () {
	// Borrar formulario
	$('#form_categoria').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#ID_RECOPSICOCATEGORIA").val(0);
	$("#RECPSICO_ID").val($("#recsensorial_id").val());

	$(".listadodeturno").empty();

	// mostrar modal
	$('#modal_categoria').modal({ backdrop: false });
});


function funcion_tabla_recsensorialcategorias(recsensorial_id) {
	try {
		var ruta = "/recopsicocategoriatabla/" + recsensorial_id;

		if (tabla_recsensorialcategorias != null) {
			tabla_recsensorialcategorias.clear().draw();
			tabla_recsensorialcategorias.ajax.url(ruta).load();
		}
		else {
			tabla_recsensorialcategorias = $('#tabla_recsensorialcategorias').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_recsensorialcategorias(recsensorial_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id"
					// },
					// {
					//     "data": "numero_registro"
					// },
					{
						"data": "catdepartamento.catdepartamento_nombre",
						"defaultContent": "Sin dato"
					},
					{
						"data": "RECPSICO_NOMBRECATEGORIA"
					},
					{
						"data": "catmovilfijo.catmovilfijo_nombre",
						"defaultContent": "Sin dato"
					},
					{
						"className": 'editar',
						"orderable": false,
						// "data": 'IconoBloqueo',
						"defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
					},
					// {
					//     "orderable": false,
					//     "data": 'boton_eliminar',
					//     "defaultContent": '-'
					// }

				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"rowsGroup": [0], //agrupar filas
				"order": [[1, "asc"]],
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
	}
	catch (exception) {
		// alert("error en el ajax");
		funcion_tabla_recsensorialcategorias(recsensorial_id);
	}
}


$("#boton_guardar_categoria").click(function (event) {
	event.preventDefault(); // Evita el comportamiento predeterminado del formulario
	// valida campos vacios

	var valida = this.form.checkValidity();
	if (valida) {
		// Recopilar datos de turnos en formato JSON
		var turnosjs = [];
		var errorEnTurnos = false; // Variable para controlar si hay error en los turnos
		var sumaHorasJornada = 0

		$(".generarturnos").each(function () {


			var horasJornada = parseInt($(this).find("input[name='recsensorialcategoria_horasjornada']").val()) || 0;
			var horasComida = parseInt($(this).find("input[name='recsensorialcategoria_horascomida']").val()) || 0;

			sumaHorasJornada += horasJornada;

			var horaEntrada = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horarioentrada']").val() + ":00");
			var horaSalida = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horariosalida']").val() + ":00");

			var horasTrabajadas = (horaSalida - horaEntrada) / 1000 / 60 / 60;

			if (horasJornada + horasComida !== horasTrabajadas) {
				errorEnTurnos = true;
			}

			var turno = {
				'recsensorialcategoria_horasjornada': horasJornada,
				'recsensorialcategoria_horarioentrada': $(this).find("input[name='recsensorialcategoria_horarioentrada']").val(),
				'recsensorialcategoria_horariosalida': $(this).find("input[name='recsensorialcategoria_horariosalida']").val(),
				'recsensorialcategoria_horascomida': horasComida,
				'descripcioncategoria': $(this).find("input[name='descripcioncategoria']").val(),

			};
			turnosjs.push(turno);
		});

		if (errorEnTurnos) {
			swal({
				title: "No disponible",
				text: "Su horario de entrada y salida no coincide con las horas asignadas.",
				type: "warning", // Puedes cambiar el tipo según tus preferencias
				buttons: {
					confirm: {
						text: "OK",
						value: true,
						visible: true,
						className: "btn btn-danger" // Puedes ajustar las clases según tu estilo
					}
				}
			});
			return; // Detiene la ejecución del guardado si hay un error en los turnos
		}

		var formData = new FormData($('#form_categoria')[0]);
		formData.append('JSON_TURNOS', JSON.stringify(turnosjs));
		formData.append('SUMAHORASJORNADA', sumaHorasJornada);


		// Enviar datos
		$.ajax({
			type: 'POST',
			url: "/recopsicocategoria",
			data: formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function (dato) {
				// Manejar la respuesta del servidor
				// Campos Hidden
				$("#ID_RECOPSICOCATEGORIA").val(dato.categoriapsico.ID_RECOPSICOCATEGORIA);
				$("#RECPSICO_ID").val(dato.categoriapsico.RECPSICO_ID);

				// actualiza tabla
				funcion_tabla_recsensorialcategorias(dato.categoriapsico.RECPSICO_ID);

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
				$('#boton_guardar_categoria').html('Guardar <i class="fa fa-save"></i>');

				// cerrar modal
				$('#modal_categoria').modal('hide');

				$(".listadodeturno").empty();

			},
			beforeSend: function () {
				$('#boton_guardar_categoria').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (error) {
				// Manejar errores de la solicitud
				// actualiza boton
				$('#boton_guardar_categoria').html('Guardar <i class="fa fa-save"></i>');
				// mensaje
				swal({
					title: "Error",
					text: "Error en la acción: " + error.responseText, // Acceder al mensaje de error
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
	}
});



//OBTENER DATOS DE LAS CATEGORIAS 


function obtenerdatoscategorias(data) {


	//$('#1pr1').prop('checked', true)
	let row = data.data().JSON_TURNOS;
	var categorias = JSON.parse(row);

	// Contador para llevar el número de contacto
	let contadorContacto = 1;

	// Recorrer cada contacto
	$.each(categorias, function (index, contacto) {
		// Acceder a cada propiedad del contacto
		var horas = contacto.recsensorialcategoria_horasjornada;
		var entrada = contacto.recsensorialcategoria_horarioentrada;
		var salida = contacto.recsensorialcategoria_horariosalida;
		var comida = contacto.recsensorialcategoria_horascomida;
		var descripcion = contacto.descripcioncategoria


		const divhorarios = document.createElement('div');
		divhorarios.classList.add('row', 'generarturnos', 'm-2');
		divhorarios.innerHTML = `
			 <div class="col-lg-12 col-sm-1">
				  <div class="form-group">
					  <h3><i class="fa  fa-clock-o"></i> Turno ${contadorContacto}</h3>                 
				  </div>

				   <div class="row">

					  <div class="col-3">
						  <div class="form-group">
							  <label> Horas jornada *</label>
							  <input type="number" class="form-control" name="recsensorialcategoria_horasjornada" id="recsensorialcategoria_horasjornada"  value="${horas}"required>
						  </div>
					  </div>
					  <div class="col-3">
						  <div class="form-group">
							  <label> Horario entrada *</label>
							  <div class="input-group clockpicker">
								  <input type="time" class="form-control" name="recsensorialcategoria_horarioentrada" id="recsensorialcategoria_horarioentrada"   value="${entrada}" required>
							  </div>
						  </div>
					  </div>
					  <div class="col-3">
						  <div class="form-group">
							  <label> Horario salida *</label>
							  <div class="input-group clockpicker">
								  <input type="time" class="form-control" name="recsensorialcategoria_horariosalida" id="recsensorialcategoria_horariosalida"  value="${salida}" required>
							  </div>
						  </div>
					  </div>
					  <div class="col-3">
						  <div class="form-group">
							  <label> Horas de comida </label>
							  <input type="number" class="form-control" name="recsensorialcategoria_horascomida" id="recsensorialcategoria_horascomida" value="${comida}">
						  </div>
					  </div>
					    <div class="col-12">
							<div class="form-group">
								<label> Nota del turno</label>
								<input type="text" class="form-control" name="descripcioncategoria" id="descripcioncategoria" placeholder="Nota del turno..." value="${descripcion}">
							</div>
				       </div>
                 </div>
			      <div class="col">
					  <div class="form-group" style="text-align: center;">
						  <button type="button" class="btn btn-danger botonEliminarhorarioturno">Eliminar turno <i class="fa fa-trash"></i></button>
					  </div>
				  </div>
			  </div>

				  `;
		const contenedor = document.querySelector('.listadodeturno');
		contenedor.appendChild(divhorarios);
		// Incrementar el contador de contacto para el siguiente


		contadorContacto++;

		const botonEliminar = divhorarios.querySelector('.botonEliminarhorarioturno');
		botonEliminar.addEventListener('click', function () {
			contenedor.removeChild(divhorarios);
		});


	});


}


// Selecciona CATEGORIA
$(document).ready(function () {
	$('#tabla_recsensorialcategorias tbody').on('click', 'td.editar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialcategorias.row(tr);

		// Borrar formulario
		$('#form_categoria').each(function () {
			this.reset();
		});

		// llenar campos
		$("#ID_RECOPSICOCATEGORIA").val(row.data().ID_RECOPSICOCATEGORIA);
		$("#RECPSICO_ID").val(row.data().RECPSICO_ID);
		$("#catdepartamento_id").val(row.data().catdepartamento_id);
		$("#catmovilfijo_id").val(row.data().catmovilfijo_id);
		$("#RECPSICO_NOMBRECATEGORIA").val(row.data().RECPSICO_NOMBRECATEGORIA);


		// mostrar modal
		$('#modal_categoria').modal({ backdrop: false });

		$(".listadodeturno").empty();
		obtenerdatoscategorias(row)


	});
});







//===============================================================


function funcion_tabla_recsensorialareas(recsensorial_id) {
	try {
		var ruta = "/recsensorialareatabla/" + recsensorial_id;

		if (tabla_recsensorialareas != null) {
			tabla_recsensorialareas.clear().draw();
			tabla_recsensorialareas.ajax.url(ruta).load();
		}
		else {
			tabla_recsensorialareas = $('#tabla_recsensorialareas').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_recsensorialareas(recsensorial_id);
					},
					"data": {}
				},
				"columns": [
					{
						"data": "numero_registro",
						"defaultContent": "-"
					},
					{
						"data": "recsensorialarea_nombre",
						"defaultContent": "-"
					},
					{
						"data": "categorias",
						"defaultContent": "-"
					},
					{
						"className": 'editar',
						"orderable": false,
						"render": function (data, type, row) {
							return '<button type="button" class="btn btn-warning btn-circle editar" data-options="' + row.recensorialarea_generacioncontaminante + '"><i class="fa fa-pencil"></i></button>';
						}
					},
					// {
					// 	"orderable": false,
					// 	"data": 'boton_eliminar',
					// 	"defaultContent": '-'
					// }


				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[2, "asc"]],
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
		// alert("error en el ajax");
		funcion_tabla_recsensorialareas(recsensorial_id);
	}
}



// Al hacer clic en el botón para agregar un nuevo elemento
$("#boton_nueva_area").click(function () {
	// Restablecer formulario
	$('#form_area').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#area_id").val(0);
	$("#area_recsensorial_id").val($("#recsensorial_id").val());

	// Consulta parametros
	$("#inputs_checkbox_agentes").html('');
	consulta_parametros($('#recsensorial_id').val(), 0);

	// Limpiar opciones seleccionadas del campo recsensorialarea_generacioncontaminante
	$("#recsensorialarea_generacioncontaminante").val(null);

	// Inicializar tablas categorías
	$('#tabla_areacategorias tbody').html('');
	$("#tabla_areacategorias tbody").append('<tr>' +
		'<td style="padding:2px 4px;"><select class="custom-select form-control" style="padding:0px 4px;" name="categoria[]" required>' + select_areacategorias_opciones + '</select></td>' +
		'<td style="padding:2px 4px;"><input type="text" class="form-control" style="padding:0px 4px;" name="actividad[]" value="" placeholder="Actividades en el área" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="geh[]" value="" placeholder="Ejem. II" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="total[]" value="" placeholder="Total" required></td>' +
		'<td style="padding:2px 4px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
		'</tr>');

	areacategorias_total += 1;

	// Mostrar modal
	$('#modal_area').modal({ backdrop: false });



});

//excel trabajadores
///FUNCION BOTONES DE INSERCION POR MEDIO DE EXCEL
function abrirTrabajadoresExcel() {

	$('#form_cargaTrabajadores').each(function () {
		this.reset();
	});
	
		//$('#divCargarTrabajadores').css('display', 'none');
		$('#alertaVerificacion').css('display', 'none');
		$('#modal_cargarTrabajadores').modal({backdrop:false});
		
}


 $('#excelTrabajadores').change(function () {
        
	if ($(this).val()) {
		
		$('#alertaVerificacion').css('display', 'block');

	} else {
		$('#alertaVerificacion').css('display', 'none');
		
	}
});

$(document).ready(function() {

	var tabla = $('#tabla_trabajadores_cargados').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "columns": [
            { "data": "numero"},
            { "data": "nombre"},
            { "data": "muestra",
            	"render": function(data, type, row) {
                if (data == 0) {
                    return '<i class="fa fa-times-circle fa-2x text-danger"></i>'; 
                } else if (data == 1) {
                    return '<i class="fa fa-check-circle fa-2x text-success"></i>'; 
                } else {
                    return data;
				}
			}
		}
        ],
        "columnDefs": [
            { "orderable": false, "targets": [0, 1, 2] }
        ]
    });

    function cargarTrabajadores() {
       // var recpsico_id = $('#RECPSICO_ID_TRABAJADORES').val(); 
        var recpsico_id = 3; 
        
        $.ajax({
            url: '/recopsicotrabajadorescargados/' + 3,
            type: 'GET',
            data: { recpsico_id: recpsico_id },
            dataType: 'json',
            success: function(data) {

				tabla.clear();
                
                $.each(data, function(index, trabajador) {
                    tabla.row.add({
                        "numero": index + 1,
                        "nombre": trabajador.RECPSICOTRABAJADOR_NOMBRE,
                        "muestra": trabajador.RECPSICOTRABAJADOR_MUESTRA
                    });
                });
                
                tabla.draw();
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar trabajadores:", error);
                alert("Error al cargar la lista de trabajadores");
            }
        });
    }

	$("#boton_cargarTrabajadores").click(function() {
		var guardar = 0;
		//var muestra = $('#tipoArchivo').val();
		$("#RECPSICO_ID_TRABAJADORES").val($("#recsensorial_id").val());
		$("#RECPSICO_APLICACION").val($("#RECPSICO_TOTALAPLICACION").val());

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida){

			var formData = new FormData($('#form_cargaTrabajadores')[0]);
			formData.append('RECPSICOTRABAJADOR_MUESTRA', document.getElementById("RECPSICOTRABAJADOR_MUESTRA").checked ? 1 : 0);
			var RECPSICOTRABAJADOR_MUESTRA = document.getElementById("RECPSICOTRABAJADOR_MUESTRA").checked ? 1 : 0;
			formData.append('RECPSICO_APLICACION', parseInt(document.getElementById('RECPSICO_APLICACION').value));
			var RECPSICO_APLICACION = document.getElementById('RECPSICO_APLICACION').value;
			// var totalEmpleadosMuestra = parseInt(document.getElementById('RECPSICO_TOTALAPLICACION').value);
			// if (isNaN(totalEmpleadosMuestra)) {
			// 	totalEmpleadosMuestra = 1; 
			// } 
				// Tipo archivo
				var archivo = $("#excelTrabajadores").val();
				var extension = archivo.substring(archivo.lastIndexOf("."));

				// valida tipo de archivo
				if(extension == ".xlsx" || extension == ".XLSX"){
					guardar = 1;
				}
				else{
					// mensaje
					swal({
						title: "Tipo de archivo incorrecto "+extension,
						text: "Solo se pueden cargar archivos tipo .xlsx",
						type: "warning", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 3000,
						showConfirmButton: false
					});

					guardar = 0;

					return false;
				}
			
			

			// guardar
			if (guardar == 1){
			
				swal({   
					title: "¿Está  seguro de cargar este documento?",   
					text: "Está acción  no se puede revertir",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Guardar!",   
					cancelButtonText: "Cancelar!",   
					closeOnConfirm: false,   
					closeOnCancel: false 
				}, function (isConfirm) {   
					
					if (isConfirm){
						// cerrar msj confirmacion
						swal.close();

						// enviar datos
						$('#form_cargaTrabajadores').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: "/recopsiconormativa",
							data: {
								opcion: 1000,
								'RECPSICOTRABAJADOR_MUESTRA': RECPSICOTRABAJADOR_MUESTRA,
								'RECPSICO_APLICACION': RECPSICO_APLICACION,
							},
							contentType: false,
							processData: false,
							success: function (dato) {

								// actualizar boton
								
								
								if (dato.code == 200) {

									cargarTrabajadores();
									// cerrar modal
									$('#modal_cargarTrabajadores').modal('hide');

									// mensaje
									swal({
										title: "Los datos fueron importados exitosamente",
										text: ""+dato.msj,
										type: "success", 
										buttons: {visible: true},
										showConfirmButton: true,
										showCancelButton: false
									});

									
									

								
								} else {

									swal({
										title: "Ocurrio un error al intentar importar los datos.",
										// text: ""+dato.msj,
										type: "error", // warning, error, success, info
										buttons: {visible: true},
										showConfirmButton: true,
										showCancelButton: false
									});
								}
								$('#boton_cargarTrabajadores').html('Guardar <i class="fa fa-save"></i>');
								
							},
							beforeSend: function () {
								$('#boton_cargarTrabajadores').html('Guardando <i class="fa fa-spin fa-spinner"></i>');							
							},
							error: function(dato) {
								$('#boton_cargarTrabajadores').html('Guardar <i class="fa fa-save"></i>');		

								console.log(""+dato.msj);
								// mensaje
								swal({
									title: "Error al cargar los datos.",
									text: ""+dato.msj,
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
					else 
					{
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
		}
	});
});
$("#boton_carga_muestra").click(function () {
	// Restablecer formulario
	$('#form_cargaMuestra').each(function () {
		this.reset();
	});

	// // Campos Hidden
	// $("#area_id").val(0);
	// $("#area_recsensorial_id").val($("#recsensorial_id").val());
	// Mostrar modal
	$('#modal_cargarMuestra').modal({ backdrop: false });



});


function consulta_parametros(recsensorial_id, area_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialareaparametros/" + recsensorial_id + "/" + area_id,
		data: {},
		cache: false,
		success: function (dato) {
			$('#checkbox_areaagentes').html('');
			$.each(dato.parametros, function (key, value) {
				let switchElement =
					'<div class="col-12">' +
					'<div class="form-group">' +
					'<div class="switch" style="float: left;">' +
					'<label>' +
					'<input type="checkbox" name="parametro[]" value="' + value.catprueba_id + '" ' + value.checked + '>' +
					'<span class="lever switch-col-light-blue"></span>' +
					'</label>' +
					'</div>' +
					'<label class="demo-switch-title" style="float: left;">' + value.catPrueba_Nombre + '</label>' +
					'</div>' +
					'</div>';

				$("#checkbox_areaagentes").append(switchElement);

				$('input[name="parametro[]"][value="' + value.catprueba_id + '"]').change(function () {
					if ($(this).is(':checked') && value.catprueba_id == 15) {
						$('input[name="tiempo_quimicos[]"]').val('');
						$('input[name="frecuencia_quimicos[]"]').val('');
					} else if
						(!$(this).is(':checked') && value.catprueba_id == 15) {
						$('input[name="tiempo_quimicos[]"]').val('0');
						$('input[name="frecuencia_quimicos[]"]').val('0');
					}
				});
			});
		},
		beforeSend: function () {
			$('#checkbox_areaagentes').html('<i class="fa fa-spin fa-spinner fa-3x" style="margin: 0px auto;"></i>');
		},
		error: function (dato) {
			$('#checkbox_areaagentes').html('');
			return false;
		}
	}); // Fin ajax
}





function consulta_categorias(recsensorial_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialareacategorias/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			select_areacategorias_opciones = '<option value=""></option>';
			$.each(dato.categorias, function (key, value) {
				select_areacategorias_opciones += '<option value="' + value.id + '">' + value.categoria_nombre + '</option>';
			});
		},
		error: function (dato) {
			select_areacategorias_opciones = '<option value="">No hay categorias que mostrar</option>';
			return false;
		}
	});//Fin ajax
}


$("#boton_nueva_areacategoria").click(function () {
	$("#tabla_areacategorias tbody").append('<tr>' +
		'<td style="padding:2px 4px;"><select class="custom-select form-control" style="padding:0px 4px;" name="categoria[]" required>' + select_areacategorias_opciones + '</select></td>' +
		'<td style="padding:2px 4px;"><input type="text" class="form-control" style="padding:0px 4px;" name="actividad[]" value="" placeholder="Actividades en el área" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="geh[]" value="" placeholder="Ejem. 1" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="total[]" value="" placeholder="Total" required></td>' +
		'<td style="padding:2px 4px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
		'</tr>');

	areacategorias_total += 1;
});



function consulta_areascategorias(area_id) {
	var actividad = '';
	var geh = '';
	var total = '';
	var tiempo = '';
	var frecuencia = '';
	var tiempo_quimicos = '';
	var frecuencia_quimicos = '';



	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialareacategoriaselegidas/" + area_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Dibujar fila categorias
			$('#tabla_areacategorias tbody').html('');

			$.each(dato.categoriaselegidas, function (key, value) {
				if (value.recsensorialareacategorias_actividad == null) { actividad = ''; } else { actividad = value.recsensorialareacategorias_actividad; }
				if (value.recsensorialareacategorias_geh == null) { geh = ''; } else { geh = value.recsensorialareacategorias_geh; }
				if (value.recsensorialareacategorias_total == null) { total = ''; } else { total = value.recsensorialareacategorias_total; }
				if (value.recsensorialareacategorias_tiempoexpo == null) { tiempo = ''; } else { tiempo = value.recsensorialareacategorias_tiempoexpo; }
				if (value.recsensorialareacategorias_frecuenciaexpo == null) { frecuencia = ''; } else { frecuencia = value.recsensorialareacategorias_frecuenciaexpo; }
				if (value.tiempoexpo_quimico == null) { tiempo_quimicos = ''; } else { tiempo_quimicos = value.tiempoexpo_quimico; }
				if (value.frecuenciaexpo_quimico == null) { frecuencia_quimicos = ''; } else { frecuencia_quimicos = value.frecuenciaexpo_quimico; }




				$("#tabla_areacategorias tbody").append('<tr>' +
					'<td style="padding:2px 4px;"><select class="custom-select form-control" style="padding:0px 4px;" id="categoria_' + key + '" name="categoria[]" required>' + select_areacategorias_opciones + '</select></td>' +
					'<td style="padding:2px 4px;"><input type="text" class="form-control" style="padding:0px 4px;" name="actividad[]" value="' + actividad + '" required></td>' +
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="geh[]" value="' + geh + '" required></td>' +
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="total[]" value="' + total + '" required></td>' +
					'<td style="padding:2px 4px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
					'</tr>');

				// seleccionar categoria del select
				$('#categoria_' + key).val(value.recsensorialcategoria_id);

				// Actualiza contador categorias por area
				areacategorias_total += 1;
			});
		},
		beforeSend: function () {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7"><i class="fa fa-spin fa-spinner fa-4x" style="margin: 0px auto;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7">&nbsp;</td></tr>');
			return false;
		}
	});//Fin ajax
}


// Selecciona AREA
$(document).ready(function () {
	// Función para actualizar el texto del área seleccionada
	function actualizarTextoAreaSeleccionada() {
		var selectedOption = $("#RECSENSORIAL_DATOSAREA option:selected").text();
		$("#selectedArea").text(selectedOption);
	}

	// Al cambiar la selección en el select RECSENSORIAL_DATOSAREA
	$("#RECSENSORIAL_DATOSAREA").change(function () {
		// Actualizar el texto del área seleccionada
		actualizarTextoAreaSeleccionada();
	});

	$('#tabla_recsensorialareas tbody').on('click', 'td.editar', function () {
		// Restablecer formulario
		$('#form_area').each(function () {
			this.reset();
		});

		// Obtener datos de la fila
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialareas.row(tr);

		// Llenar campos con los datos de la fila
		$("#area_id").val(row.data().id);
		$("#area_recsensorial_id").val(row.data().recsensorial_id);
		$("#recsensorialarea_nombre").val(row.data().recsensorialarea_nombre);
		$("#RECSENSORIALAREA_PROCESO").val(row.data().RECSENSORIALAREA_PROCESO);
		$("#recsensorialarea_condicion").val(row.data().recsensorialarea_condicion);
		$("#recsensorialarea_caracteristica").val(row.data().recsensorialarea_caracteristica);
		$("#recsensorialarea_extraccionaire").val(row.data().recsensorialarea_extraccionaire);
		$("#recsensorialarea_inyeccionaire").val(row.data().recsensorialarea_inyeccionaire);
		$("#RECSENSORIAL_DATOSAREA").val(row.data().RECSENSORIAL_DATOSAREA);
		$("#recsensorialarea_controlestecnicos").val(row.data().recsensorialarea_controlestecnicos);
		$("#recsensorialarea_controlesadministrativos").val(row.data().recsensorialarea_controlesadministrativos);
		$("#JERARQUIACONTROL").val(row.data().JERARQUIACONTROL);
		$("#CONTROLESJERARQUIA_DESCRIPCION").val(row.data().CONTROLESJERARQUIA_DESCRIPCION);
		$("#DESCRIPCION_AREA").val(row.data().DESCRIPCION_AREA);

		// Actualizar valor del select RECSENSORIAL_DATOSAREA
		$('#RECSENSORIAL_DATOSAREA').val(row.data().RECSENSORIAL_DATOSAREA);

		actualizarTextoAreaSeleccionada();


		// Consultar parámetros
		consulta_parametros($('#recsensorial_id').val(), row.data().id);

		// Consultar categorías
		//consulta_areascategorias(row.data().id);


		// Mostrar modal
		$('#modal_area').modal({ backdrop: false });

		// Mostrar opciones seleccionadas del campo recsensorialarea_generacioncontaminante
		var opcionesSeleccionadas = row.data().recsensorialarea_generacioncontaminante;
		var selectElement = $("#recsensorialarea_generacioncontaminante");

		if (opcionesSeleccionadas) {
			// Convertir la cadena de opciones separadas por comas en un array
			var opcionesArray = opcionesSeleccionadas.split(',');

			// Establecer las opciones seleccionadas en el elemento <select>
			selectElement.val(opcionesArray);
		}


	});
});




function consulta_areas(nombre_campo, id_seleccionado, recsensorial_id, quimicas) {
	// alert('mensaje');
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialconsultaareas/" + recsensorial_id + "/" + id_seleccionado + "/" + quimicas,
		data: {},
		cache: false,
		success: function (dato) {
			$("#" + nombre_campo).html(dato.opciones);
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}

var categoriasEvaluadas = ''
function consulta_categorias_evaluadas(recsensorial_id) {

	categoriasEvaluadas = '';
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerCategoriasEvaluadas/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {

			categoriasEvaluadas = dato.opciones;

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}



$(document).ready(function () {
	// Función para restablecer los estilos del select al guardar un nuevo elemento
	function resetSelectStyles() {
		$('#JERARQUIACONTROL').css('background-color', 'white'); // Color de fondo blanco
		$('#JERARQUIACONTROL').css('color', 'black'); // Color del texto negro
		$('#JERARQUIACONTROL').val(''); // Restablecer el valor seleccionado
	}

	// Función para restablecer el select de datos de área al guardar un nuevo elemento
	function resetDatosAreaSelect() {
		$('#RECSENSORIAL_DATOSAREA').val(''); // Restablecer el valor seleccionado
		$('#selectedArea').text(''); // Limpiar el texto del área seleccionada
	}

	$("#boton_guardar_area").click(function () {
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			var seleccionados = 0;

			$('#checkbox_areaagentes input[type=checkbox]').each(function () {
				if (this.checked) {
					seleccionados += 1;
				}
			});

			// valida parametros seleccionados
			if (seleccionados > 0 && areacategorias_total > 0) {

				// Obtener opciones seleccionadas en un array
				var opcionesSeleccionadas = $('#recsensorialarea_generacioncontaminante').val();

				// enviar datos
				$('#form_area').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorialarea',
					data: {
						recsensorialarea_generacioncontaminante: opcionesSeleccionadas // Agregar opciones seleccionadas
					},
					resetForm: false,
					success: function (dato) {
						// Campos Hidden
						$("#area_id").val(dato.area.id);
						$("#area_recsensorial_id").val(dato.area.recsensorial_id);

						// actualiza tabla
						funcion_tabla_recsensorialareas(dato.area.recsensorial_id);

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
						$('#boton_guardar_area').html('Guardar <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_area').modal('hide');

						// Restablecer estilos del select al guardar un nuevo elemento
						resetSelectStyles();

						// Restablecer select de datos de área al guardar un nuevo elemento
						resetDatosAreaSelect();
					},
					beforeSend: function () {
						$('#boton_guardar_area').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_area').html('Guardar <i class="fa fa-save"></i>');
						// mensaje
						swal({
							title: "Error",
							text: "Error en la acción: " + dato,
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
			} else {
				// mensaje
				swal({
					title: "Seleccione opciones",
					text: "Debe Seleccionar al menos un factor de riesgo y una o mas categorías",
					type: "warning", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 2500,
					showConfirmButton: false
				});
				return false;
			}
		}
	});

	// Al cerrar el modal
	$('#modal_area').on('hidden.bs.modal', function () {
		// Restablecer estilos del select al cerrar el modal
		resetSelectStyles();

		// Restablecer select de datos de área al cerrar el modal
		resetDatosAreaSelect();
	});
});

//------------------------------------------------------------

//FUNCION PARA OBTENER LA LISTA DE LOS FOLIOS DE LOS PORYECTOS QUE NO ESTAN VINCULADO CON NINIGUN RECONOCIMIETO Y QUE TIENEN EL SERVICO DE HI
function cargarFoliosProyecto(proyecto_folio) {

	$('#proyecto_folio').html('');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/folioproyectoPsico/" + proyecto_folio,
		data: {},
		cache: false,
		success: function (dato) {
			$('#proyecto_folio').html(dato.opciones);

		}, beforeSend: function () {

			$('#proyecto_folio').html('<option value="" selected> Consultando folios de proyectos... </option>');

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}

//FUNCION PARA OBTENER LA ESTRUCTURA DE LOS PROYECTOS
function obtenerEstructuraProyectos(FOLIO, NUEVO) {

	$('#titleOrganizacionLabel').fadeOut();

	$.ajax({
		url: '/estructuraPsico/' + FOLIO,
		method: 'GET',
		beforeSend: function () {
			$('#estructura-container').empty();
		},
		success: function (response) {

			//MOSTRAMOS LA ESTRUCTURA ORGANIZACIONAL DEL PROYECTO 
			if (response.data && response.data.length > 0) {
				var container = $('#estructura-container');

				$('#estructura-container').css({ 'background': '#e8e8e8', 'padding': '10px', 'border-style': 'dotted', 'border-color': '#000', 'border-width': '1px', 'border-radius': '15px', 'display': 'flex' });

				container.append(
					'<div class="col-12 text-center">' +
					'<h3 class="mb-2" style="font-weight: bold">Estructura organizacional</h3>' +
					'</div>'
				);

				var niveles = [1, 2, 3, 4, 5];
				niveles.forEach(function (nivel) {
					var data = response.data.filter(item => item.NIVEL == nivel);
					if (data.length > 0) {
						data.forEach(function (item) {
							container.append(
								'<div class="col-6"><label style="color: black;">Nivel ' + nivel + (nivel === 1 ? ' (Considerado como el más alto)' : '') + '</label></div>' +
								'<div class="col-6"><label style="color: black;">Clasificación</label></div>' +
								'<div class="col-6"><label>' + item.NOMBRE_ETIQUETA + '</label></div>' +
								'<div class="col-6"><label>' + item.NOMBRE_OPCIONES + '</label></div>'
							);
						});
					}
				});
			} else {
				$('#estructura-container').append('<p>No se encontraron datos.</p>');
			}

			
			if(response.HIGIENE[0].HI_RECONOCIMIENTO===1){
				$('#boton_nueva_categoria').prop('disabled', true);
				$('#boton_guardar_categoria').prop('disabled', true);
				$('#boton_nueva_area').prop('disabled', true);
				$('#boton_guardar_area').prop('disabled', true);

			}
			//MOSTRAMOS LOS DATOS DEL PROYECTO SOLO SI ES NUEVO SI YA ESTA CARGADO MOSTRAMOS LOS DATOS GUARDADOS EN LA BASE DE DATOS
			if (NUEVO == 1) {

				

				$('#empresa').val(response.info[0].RAZON_SOCIAL)
				$('#rfc').val(response.info[0].RFC)
				$('#representanteseguridad').val(response.info[0].REPRESENTANTE)
				$('#direccion').val(response.info[0].DIRRECCION)
				$('#instalacion').val(response.info[0].INSTALACION)

				// Input ocultos
				var contrato = response.info[0].CONTRATO_ID == 0 ? "" : response.info[0].CONTRATO_ID
				var requiereContrato = response.info[0].CONTRATO_ID == 0 ? 0 : 1


				$('#cliente_id').val(response.info[0].CLIENTE_ID)
				$('#contrato_id').val(contrato)
				$('#requiere_contrato').val(requiereContrato)
				$('#descripcion_contrato').val(response.info[0].NOMBRE_CONTRATO)
				$('#descripcion_cliente').val(response.info[0].RAZON_SOCIAL)


				//INFORMACION CONSULTADA DE RECSENSORIAL
				$('#ordenservicio').val(response.info[0].ORDENSERVICIO)
				$('#representantelegal').val(response.info[0].REPRESENTANTE_LEGAL)
				$('#codigopostal').val(response.info[0].CODIGOPOSTAL)
				$('#coordenadas').val(response.info[0].COORDENADAS)
				$('#actividadprincipal').val(response.info[0].ACTIVIDADPRINCIPAL)
				$('#descripcionproceso').val(response.info[0].DESCRIPCIONPROCESO)
				$('#observaciones').val(response.info[0].OBSERVACION)
				$('#fechainicio').val(response.info[0].FECHAINICIO)
				$('#fechafin').val(response.info[0].FECHAFIN)




				$('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
										<h4>${response.info[0].RAZON_SOCIAL}</h4>
										<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> ${response.info[0].TIPO_SERVICIO}</h2>
										<h4 class="mb-2">${response.info[0].NOMBRE_CONTRATO}</h4>`).css('border-style', 'dotted')

				
				//FOTOS OBTENIDAS DE RECSENSORIAL
				if (response.info[0].FOTOUBICACION != null) {
					var archivo = response.info[0].FOTOUBICACION;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarmapa/0/' + response.info[0].ID + extension;
		
					rutaMapa = imagenUrl
					$("#hidden_fotomapa").val(response.info[0].ID);
					$("#hidden_fotomapa_extension").val(extension);
		
					// INPUT FOTO UBICACION
					if ($('#inputfotomapa').data('dropify')) {
						$('#inputfotomapa').dropify().data('dropify').destroy();
						// $('.dropify-wrapper').css('height', 400);
						$('#inputfotomapa').dropify().data('dropify').settings.defaultFile = imagenUrl;
						$('#inputfotomapa').dropify().data('dropify').init();
					}
					else {
						// $('#inputfotomapa').attr('data-height', 400);
						$('#inputfotomapa').attr('data-default-file', imagenUrl);
						$('#inputfotomapa').dropify({
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
					}
		
					// No requerir campo FOTO
					$('#inputfotomapa').attr('required', false);
		
					// Activar boton descarga
					$("#boton_descargarmapaubicacion").css('display', 'block');
				}
				else {
					$("#boton_descargarmapaubicacion").css('display', 'none');
				}

				if (response.info[0].FOTOPLANO != null) {
					var archivo = response.info[0].FOTOPLANO;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarplano/0/' + response.info[0].ID + extension;
			
					rutaMapa = imagenUrl;
			
					$("#hidden_fotoplano").val(response.info[0].ID);
					$("#hidden_fotoplano_extension").val(extension);
			
					// INPUT FOTO PLANO
					if ($('#inputfotoplano').data('dropify')) {
						$('#inputfotoplano').dropify().data('dropify').destroy();
						// $('.dropify-wrapper').css('height', 400);
						$('#inputfotoplano').dropify().data('dropify').settings.defaultFile = imagenUrl;
						$('#inputfotoplano').dropify().data('dropify').init();
					}
					else {
						// $('#inputfotoplano').attr('data-height', 400);
						$('#inputfotoplano').attr('data-default-file', imagenUrl);
						$('#inputfotoplano').dropify({
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
					}
			
					// No requerir campo FOTO
					$('#inputfotoplano').attr('required', false);
			
					// Activar boton descarga
					$("#boton_descargarplanoinstalacion").css('display', 'block');
				}
				else {
					$("#boton_descargarplanoinstalacion").css('display', 'none');
				}

			
			
				// OBTENER FOTO INSTALACION
				if (response.info[0].FOTOINSTALACION) {
					var archivo = response.info[0].FOTOINSTALACION;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarfotoinstalacion/0/' + response.info[0].ID + extension;
			
					
					$("#hidden_fotoinstalacion").val(response.info[0].ID);
					$("#hidden_fotoinstalacion_extension").val(extension);
					// INPUT FOTO INSTALACION
					$('#inputfotoinstalacion').dropify().data('dropify').destroy();
					// $('.dropify-wrapper').css('height', 400);
					$('#inputfotoinstalacion').dropify().data('dropify').settings.defaultFile = imagenUrl;
					$('#inputfotoinstalacion').dropify().data('dropify').init();
			
					// No requerir campo FOTO
					$('#inputfotoinstalacion').attr('required', false);
			
					// Activar boton descarga
					$("#boton_descargarfotoinstalacion").css('display', 'block');
			
				} else {
			
					// Resetear campo FOTO INSTALACION
					$('#inputfotoinstalacion').val('');
					$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
					$('#inputfotoinstalacion').dropify().data('dropify').clearElement();
			
					// No requerir campo FOTO
					$('#inputfotoinstalacion').attr('required', false);
					$("#boton_descargarfotoinstalacion").css('display', 'none');
				}
			
		


				swal({
					title: "Información del proyecto consultada y asignada correctamente",
					text: "Al momento de seleccionar un FOLIO se consulta la información cargada en proyecto y se asigna a reconocimiento",
					type: "success",
					buttons: { visible: true },
					showConfirmButton: true,
					showCancelButton: false,

				});
			} else {

				$('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
									<h4>${response.info[0].RAZON_SOCIAL}</h4>
									<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> ${response.info[0].TIPO_SERVICIO}</h2>
									<h4 class="mb-2">${response.info[0].NOMBRE_CONTRATO}</h4>`).css('border-style', 'dotted')

			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al consultar los datos:', textStatus, errorThrown);
			$('#estructura-container').append('<p>Error al consultar los datos.</p>');
		}
	});
}

//FUNCION PARA CONSULTAR DATOS CARGADOS EN EL PROYECTO SELECCIONADO Y PODER MODIFICAR EL RECONOCIMIENTO
$(document).ready(function () {
	let timeout;

	$('#proyecto_folio').on('change', function (e) {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
			obtenerEstructuraProyectos($(this).val(), 1);
		}, 1500); // Esperamos 1.5 segundos para ejecutar la funcion
	});
});


$(document).ready(function () {

	document.getElementById('habilitar_opcional').checked = false;
	document.getElementById('boton_carga_trabajadores').disabled = true;
	document.getElementById('boton_carga_muestra').disabled = true;

	document.getElementById('RECPSICOTRABAJADOR_MUESTRA').checked = false;
	document.getElementById('RECPSICOTRABAJADOR_MUESTRA').disabled = true;

    document.getElementById('optionA').checked = false;

    document.getElementById('option1').checked = false;
    document.getElementById('option2').checked = false;
    document.getElementById('option3').checked = false;
    document.getElementById('option4').checked = false;

	document.getElementById('optionA').disabled = true;
    document.getElementById('option1').disabled = true;
    document.getElementById('option2').disabled = true;
    document.getElementById('option3').disabled = true;
    document.getElementById('option4').disabled = true;

    // Manejo del evento de entrada de empleados
    document.getElementById('total_empleados').addEventListener('input', validarEmpleados);

    document.getElementById('aplicable_a').addEventListener('change', calcularResultado);

    document.getElementById('habilitar_opcional').addEventListener('change', function () {
        const isChecked = this.checked;
        const camposOpcionales = document.getElementById('campos_opcionales');

        // Mostrar u ocultar campos opcionales según el estado del checkbox
        camposOpcionales.style.display = isChecked ? 'block' : 'none';
		document.getElementById('boton_carga_trabajadores').disabled = true;

        // Habilitar/deshabilitar los selects e inputs numéricos
        document.getElementById('tipo_valor_hombres').disabled = !isChecked;
        document.getElementById('valor_hombres').disabled = !isChecked;
        document.getElementById('valor_mujeres').disabled = !isChecked;

        // Limpiar valores y sufijos
        if (!isChecked) {
            document.getElementById('valor_hombres').value = '';
            document.getElementById('valor_mujeres').value = '';
            document.getElementById('sufijo_hombres').innerText = ' %';
            document.getElementById('sufijo_mujeres').innerText = ' %';
			document.getElementById('porcentajes').innerText = '';
            clearWarnings(); // Limpiar advertencias si el checkbox está desmarcado
        }
    });

	document.getElementById('optionA').addEventListener('change', function () {
        const isChecked = this.checked;
        const camposOpcionales = document.getElementById('campos_opcionales');

		const totalEmpleados = parseInt(document.getElementById('total_empleados').value);
      


		document.getElementById('option2').disabled = !isChecked;
		document.getElementById('option3').disabled = !isChecked;
		document.getElementById('option4').disabled = !isChecked;

        
		if (!isNaN(totalEmpleados)) {
			if (totalEmpleados < 16) {
				document.getElementById('option2').disabled = !isChecked;
				document.getElementById('option3').disabled = !isChecked;
				document.getElementById('option4').disabled = !isChecked;

	
			} else if (totalEmpleados >= 16 && totalEmpleados <= 50) {
				document.getElementById('option2').disabled = !isChecked;
				document.getElementById('option3').disabled = !isChecked;
				document.getElementById('option4').disabled = !isChecked;

	
			} else if (totalEmpleados > 50) {
				document.getElementById('option2').disabled = !isChecked;
				document.getElementById('option3').disabled = !isChecked;
				document.getElementById('option4').disabled = !isChecked;

	
			}
		}
    });

	document.getElementById('option2').addEventListener('change', function () {
		if (this.checked) {
			document.getElementById('option3').checked = false;
		}
	});
	
	document.getElementById('option3').addEventListener('change', function () {
		if (this.checked) {
			document.getElementById('option2').checked = false;
		}
	});

	document.getElementById('option1').addEventListener('change', function () {
		if (this.checked) {
			this.disabled = true; // Una vez seleccionado, no se podrá desmarcar ni cambiar
		}
	});

	document.getElementById('RECPSICO_TOTALAPLICACION').addEventListener('keydown', function(event) {
		if (event.key === 'ArrowDown') {
			event.preventDefault(); // Evita que el valor disminuya con la tecla de flecha abajo
		}
	});
	  // Validar la suma de valores de hombres y mujeres
	  document.getElementById('valor_hombres').addEventListener('input', validarValores);
	  document.getElementById('valor_mujeres').addEventListener('input', validarValores);
	  document.getElementById('RECPSICO_TOTALAPLICACION').addEventListener('input', validarValoresMuestra);
	  
	let timeout;

	$('#RECPSICO_TOTALAPLICACION').on('input', function (e) {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
		const minValue = parseInt(this.getAttribute('min'));
			if (parseInt(this.value) < minValue) {
			this.value = minValue;
			validarValoresMuestra();
			}
		}, 1500);

		timeout = setTimeout(() => {
			const maxValue = parseInt(this.getAttribute('max'));
				if (parseInt(this.value) > maxValue) {
				this.value = maxValue;
				validarValoresMuestra();
				}
		}, 1500);

		
		
		// Esperamos 1.5 segundos para ejecutar la funcion
	});
  

    // Cambiar sufijo según tipo de valor seleccionado para hombres
    document.getElementById('tipo_valor_hombres').addEventListener('change', function () {
        const tipoValor = this.value;
        const sufijo = document.getElementById('sufijo_hombres');
        sufijo.innerText = tipoValor === 'porcentaje' ? ' %' : ' hombres';
    });

    // Cambiar sufijo según tipo de valor seleccionado para mujeres
    document.getElementById('tipo_valor_hombres').addEventListener('change', function () {
        const tipoValor = this.value;
        const sufijo = document.getElementById('sufijo_mujeres');
        sufijo.innerText = tipoValor === 'porcentaje' ? ' %' : ' mujeres';
    });
});

function validarValores() {
    const totalEmpleados = parseInt(document.getElementById('total_empleados').value);
    const valorHombres = parseInt(document.getElementById('valor_hombres').value) || 0;
    const valorMujeres = parseInt(document.getElementById('valor_mujeres').value) || 0;

    // Verificar si se seleccionó "Cantidad"
    const tipoValorHombres = document.getElementById('tipo_valor_hombres').value;

    // Limpiar advertencias
    clearWarnings();

    if (tipoValorHombres === "cantidad") {
        const suma = valorHombres + valorMujeres;
        if (suma !== totalEmpleados) {
            // Mostrar advertencia si la suma no coincide
            document.getElementById('valor_hombres').classList.add('is-invalid');
            document.getElementById('valor_mujeres').classList.add('is-invalid');
            showWarning("Los valores no coinciden con la cantidad total de empleados.");
			document.getElementById('boton_carga_trabajadores').disabled = true;
        } else {
            // Limpiar las clases de advertencia si la validación es correcta
            document.getElementById('valor_hombres').classList.remove('is-invalid');
            document.getElementById('valor_mujeres').classList.remove('is-invalid');
			const opcionSeleccionada = document.getElementById('aplicable_a').value;
			 // Mostrar resultado en el contenedor
			 const porcentajeHombres = Math.round((valorHombres * 100)/totalEmpleados);
			 const porcentajeMujeres = Math.round((valorMujeres * 100)/totalEmpleados);
			 document.getElementById('porcentajes').innerText = `Hay ${porcentajeHombres}% de hombres y ${porcentajeMujeres}% de mujeres en el centro de trabajo`;
			 

			 
			 if(opcionSeleccionada === "opcion2"){
				let resultado = 0;
			
				if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
					
						let a = 0.9604;
						let b = 0.0025;
						let c = totalEmpleados - 1;
						let numerador = a * totalEmpleados;
						let denominador = (b * c) + a;
			
						// Redondeo al entero más próximo
						resultado = Math.round(numerador / denominador);


						const seleccionHombres = Math.round(resultado * (porcentajeHombres / 100));
						const seleccionMujeres = Math.round(resultado * (porcentajeMujeres / 100));
						document.getElementById('seleccion').innerText = `Se seleccionaran de forma aleatoria ${seleccionHombres} hombres y ${seleccionMujeres} mujeres en el centro de trabajo para las entrevistas`;

				}		
			 }else{

			 }
			 const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'block';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
			resultadosContainer.style.display = 'block';
        }
    }

	if (tipoValorHombres === "porcentaje") {
        const suma = valorHombres + valorMujeres;
        if (suma !== 100) {
            // Mostrar advertencia si la suma no coincide
            document.getElementById('valor_hombres').classList.add('is-invalid');
            document.getElementById('valor_mujeres').classList.add('is-invalid');
            showWarning("Los porcentajes no coinciden con la cantidad total de empleados.");
			document.getElementById('boton_carga_trabajadores').disabled = true;
        } else {
            // Limpiar las clases de advertencia si la validación es correcta
            document.getElementById('valor_hombres').classList.remove('is-invalid');
            document.getElementById('valor_mujeres').classList.remove('is-invalid');
			const opcionSeleccionada = document.getElementById('aplicable_a').value;

			 // Mostrar resultado en el contenedor
			 const porcentajeHombres = valorHombres;
			 const porcentajeMujeres = valorMujeres;
			 document.getElementById('porcentajes').innerText = `Hay ${porcentajeHombres}% de hombres y ${porcentajeMujeres}% de mujeres en el centro de trabajo`;

			 if(opcionSeleccionada === "opcion2"){

				if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
					
					let a = 0.9604;
					let b = 0.0025;
					let c = totalEmpleados - 1;
					let numerador = a * totalEmpleados;
					let denominador = (b * c) + a;
		
					// Redondeo al entero más próximo
					resultado = Math.round(numerador / denominador);
					const seleccionHombres = Math.round(resultado * (porcentajeHombres / 100));
					const seleccionMujeres = Math.round(resultado * (porcentajeMujeres / 100));
					document.getElementById('seleccion').innerText = `Se seleccionaran de forma aleatoria ${seleccionHombres} hombres y ${seleccionMujeres} mujeres en el centro de trabajo para las entrevistas`;
					

				}
			 }else{

			 }

			 const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'block';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
				resultadosContainer.style.display = 'block';
        }
    }
}

function validarValoresMuestra() {
    const totalEmpleados = parseInt(document.getElementById('total_empleados').value);
    const valorHombres = parseInt(document.getElementById('valor_hombres').value) || 0;
    const valorMujeres = parseInt(document.getElementById('valor_mujeres').value) || 0;

	const totalEmpleadosMuestra = parseInt(document.getElementById('RECPSICO_TOTALAPLICACION').value);

    // Verificar si se seleccionó "Cantidad"
    const tipoValorHombres = document.getElementById('tipo_valor_hombres').value;

    // Limpiar advertencias
    clearWarnings();

    if (tipoValorHombres === "cantidad") {
        const suma = valorHombres + valorMujeres;
        if (suma !== totalEmpleados) {
            // Mostrar advertencia si la suma no coincide
            document.getElementById('valor_hombres').classList.add('is-invalid');
            document.getElementById('valor_mujeres').classList.add('is-invalid');
            showWarning("Los valores no coinciden con la cantidad total de empleados.");
			document.getElementById('boton_carga_trabajadores').disabled = true;
        } else {
            // Limpiar las clases de advertencia si la validación es correcta
            document.getElementById('valor_hombres').classList.remove('is-invalid');
            document.getElementById('valor_mujeres').classList.remove('is-invalid');
			const opcionSeleccionada = document.getElementById('aplicable_a').value;
			 // Mostrar resultado en el contenedor
			 const porcentajeHombres = Math.round((valorHombres * 100)/totalEmpleados);
			 const porcentajeMujeres = Math.round((valorMujeres * 100)/totalEmpleados);
			 document.getElementById('porcentajes').innerText = `Hay ${porcentajeHombres}% de hombres y ${porcentajeMujeres}% de mujeres en el centro de trabajo`;

			 
			 if(opcionSeleccionada === "opcion2"){
				let resultado = 0;
			
				if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
					
						let a = 0.9604;
						let b = 0.0025;
						let c = totalEmpleados - 1;
						let numerador = a * totalEmpleados;
						let denominador = (b * c) + a;
			
						// Redondeo al entero más próximo
						resultado = Math.round(numerador / denominador);


						const seleccionHombres = Math.round(totalEmpleadosMuestra * (porcentajeHombres / 100));
						const seleccionMujeres = Math.round(totalEmpleadosMuestra * (porcentajeMujeres / 100));
						document.getElementById('seleccion').innerText = `Se seleccionaran de forma aleatoria ${seleccionHombres} hombres y ${seleccionMujeres} mujeres en el centro de trabajo para las entrevistas`;


				}		
			 }else{

			 }
			 const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'block';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
			resultadosContainer.style.display = 'block';
        }
    }

	if (tipoValorHombres === "porcentaje") {
        const suma = valorHombres + valorMujeres;
        if (suma !== 100) {
            // Mostrar advertencia si la suma no coincide
            document.getElementById('valor_hombres').classList.add('is-invalid');
            document.getElementById('valor_mujeres').classList.add('is-invalid');
            showWarning("Los porcentajes no coinciden con la cantidad total de empleados.");
			document.getElementById('boton_carga_trabajadores').disabled = true;
        } else {
            // Limpiar las clases de advertencia si la validación es correcta
            document.getElementById('valor_hombres').classList.remove('is-invalid');
            document.getElementById('valor_mujeres').classList.remove('is-invalid');
			const opcionSeleccionada = document.getElementById('aplicable_a').value;

			 // Mostrar resultado en el contenedor
			 const porcentajeHombres = valorHombres;
			 const porcentajeMujeres = valorMujeres;
			 document.getElementById('porcentajes').innerText = `Hay ${porcentajeHombres}% de hombres y ${porcentajeMujeres}% de mujeres en el centro de trabajo`;

			 if(opcionSeleccionada === "opcion2"){

				if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
					
					let a = 0.9604;
					let b = 0.0025;
					let c = totalEmpleados - 1;
					let numerador = a * totalEmpleados;
					let denominador = (b * c) + a;
		
					// Redondeo al entero más próximo
					resultado = Math.round(numerador / denominador);
					const seleccionHombres = Math.round(totalEmpleadosMuestra * (porcentajeHombres / 100));
					const seleccionMujeres = Math.round(totalEmpleadosMuestra * (porcentajeMujeres / 100));
					document.getElementById('seleccion').innerText = `Se seleccionaran de forma aleatoria ${seleccionHombres} hombres y ${seleccionMujeres} mujeres en el centro de trabajo para las entrevistas`;
					

				}
			 }else{

			 }

			 const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'block';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
			resultadosContainer.style.display = 'block';
        }
    }
}


function clearWarnings() {
    document.getElementById('valor_hombres').classList.remove('is-invalid');
    document.getElementById('valor_mujeres').classList.remove('is-invalid');
    // Limpiar cualquier mensaje de advertencia
    const warningElement = document.getElementById('warning-message');
    if (warningElement) {
        warningElement.remove();
    }
}

function showWarning(message) {
    const warningMessage = document.createElement('div');
    warningMessage.id = 'warning-message';
    warningMessage.style.color = 'red';
    warningMessage.innerText = message;
    document.getElementById('campos_opcionales').appendChild(warningMessage);
}

function updateCheckboxValue(checkbox) {
    const value = checkbox.checked ? 1 : 0;
    console.log(`Checkbox ${checkbox.id} value: ${value}`);
    // Aquí puedes enviar el valor a tu backend o procesarlo como necesites
}

function validarEmpleados() {
    const totalEmpleados = parseInt(document.getElementById('total_empleados').value);
    const selectAplicableA = document.getElementById('aplicable_a');
	document.getElementById('boton_carga_muestra').disabled = true;
	document.getElementById('RECPSICOTRABAJADOR_MUESTRA').disabled = true;
	document.getElementById('boton_carga_trabajadores').disabled = true;

    document.getElementById('habilitar_opcional').checked = false;


    document.getElementById('optionA').checked = false;

    document.getElementById('option1').checked = false;
    document.getElementById('option2').checked = false;
    document.getElementById('option3').checked = false;
    document.getElementById('option4').checked = false;


	document.getElementById('optionA').disabled = true;
    document.getElementById('option1').disabled = true;
    document.getElementById('option2').disabled = true;
    document.getElementById('option3').disabled = true;
    document.getElementById('option4').disabled = true;


	const camposOpcionales = document.getElementById('campos_opcionales');
    camposOpcionales.style.display = 'none';
	const generosContainer = document.getElementById('generos_container');
	generosContainer.style.display = 'none';
	const resultadosContainer = document.getElementById('resultados_trabajadores');
	resultadosContainer.style.display = 'none';
		document.getElementById('valor_hombres').value = '';
		document.getElementById('valor_mujeres').value = '';
		document.getElementById('sufijo_hombres').innerText = ' %';
		document.getElementById('sufijo_mujeres').innerText = ' %';
		document.getElementById('porcentajes').innerText = '';
		document.getElementById('valor_hombres').value = '';
		document.getElementById('tipo_valor_hombres').selectedIndex = 0;
        document.getElementById('seleccion').innerText = '';

    if (!isNaN(totalEmpleados)) {
        if (totalEmpleados < 16) {
			document.getElementById('optionA').disabled = false;
            document.getElementById('option1').disabled = true;
			document.getElementById('option1').checked = true;

        } else if (totalEmpleados >= 16 && totalEmpleados <= 19) {
			document.getElementById('optionA').disabled = false;
            document.getElementById('option1').disabled = true;
			document.getElementById('option1').checked = true;
			//document.getElementById('option1').disabled = false;
            document.getElementById('option2').disabled = false;
			document.getElementById('option2').checked = true;


        } else if (totalEmpleados > 19) {
            document.getElementById('option1').disabled = true;
			document.getElementById('option1').checked = true;
			//document.getElementById('option1').disabled = false; 
          	//document.getElementById('option2').disabled = false;
			  document.getElementById('option3').disabled = false;
			  document.getElementById('option3').checked = true;

        }
    }

    selectAplicableA.innerHTML = '<option value="">Selecciona una opción</option>';

    if (!isNaN(totalEmpleados) && totalEmpleados > 0) {
        if (totalEmpleados <= 19) {
            selectAplicableA.innerHTML += '<option value="opcion1">TODOS LOS TRABAJADORES</option>';
        } else {
            selectAplicableA.innerHTML += '<option value="opcion1">TODOS LOS TRABAJADORES</option>';
            selectAplicableA.innerHTML += '<option value="opcion2">MUESTRA DE TRABAJADORES</option>';
        }
        // Habilitar el select de opciones
        selectAplicableA.disabled = false;
    } else {
        // Deshabilitar el select si no hay un total válido
        selectAplicableA.disabled = true;
    }

    // Limpiar resultado
    document.getElementById('resultado').innerText = ' ';
}

function calcularResultado() {
    const totalEmpleados = document.getElementById('total_empleados').value;
    const opcionSeleccionada = document.getElementById('aplicable_a').value;

    let resultado = 0;

    if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
        if (opcionSeleccionada === "opcion1") {
            resultado = totalEmpleados;

        } else if (opcionSeleccionada === "opcion2") {
            let a = 0.9604;
            let b = 0.0025;
            let c = totalEmpleados - 1;
            let numerador = a * totalEmpleados;
            let denominador = (b * c) + a;
            // Redondeo al entero más próximo
            resultado = Math.round(numerador / denominador);
        }
    }

    // Mostrar resultado en el contenedor
    document.getElementById('RECPSICO_TOTALAPLICACION').value = `${resultado}`;
	document.getElementById('RECPSICO_TOTALAPLICACION').setAttribute('min', `${resultado}`);
	document.getElementById('RECPSICO_TOTALAPLICACION').setAttribute('max', `${totalEmpleados}`);
	const totalTrabajadoresContainer = document.getElementById('totaltrabajadores_container');
	totalTrabajadoresContainer.style.display = 'block';857

    // Si se selecciona "MUESTRA DE TRABAJADORES", activar el checkbox opcional
    if (opcionSeleccionada === "opcion2") {
        document.getElementById('habilitar_opcional').checked = true;
		document.getElementById('boton_carga_muestra').disabled = false;
		document.getElementById('RECPSICOTRABAJADOR_MUESTRA').disabled = false;
		
		const camposOpcionales = document.getElementById('campos_opcionales');
		camposOpcionales.style.display = 'block';
		const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'none';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
			resultadosContainer.style.display = 'none';

		document.getElementById('valor_hombres').value = '';
            document.getElementById('valor_mujeres').value = '';
            document.getElementById('sufijo_hombres').innerText = ' %';
            document.getElementById('sufijo_mujeres').innerText = ' %';
			document.getElementById('porcentajes').innerText = '';
			document.getElementById('seleccion').innerText = '';
    }else{
		document.getElementById('habilitar_opcional').checked = false;
		document.getElementById('boton_carga_muestra').disabled = true;
		document.getElementById('RECPSICOTRABAJADOR_MUESTRA').disabled = true;
		const camposOpcionales = document.getElementById('campos_opcionales');
		camposOpcionales.style.display = 'none';
		const generosContainer = document.getElementById('generos_container');
			 generosContainer.style.display = 'none';
			 const resultadosContainer = document.getElementById('resultados_trabajadores');
	resultadosContainer.style.display = 'none';
		document.getElementById('valor_hombres').value = '';
            document.getElementById('valor_mujeres').value = '';
            document.getElementById('sufijo_hombres').innerText = ' %';
            document.getElementById('sufijo_mujeres').innerText = ' %';
			document.getElementById('porcentajes').innerText = '';
			document.getElementById('seleccion').innerText = '';
	}
}

$("#boton_guardar_normativa").click(function (event) {
	event.preventDefault(); // Evita el comportamiento predeterminado del formulario
	// valida campos vacios

	$("#RECPSICO_ID_NORMATIVA").val($("#recsensorial_id").val());

	var valida = this.form.checkValidity();
	if (valida) {

		var formData = new FormData($('#form_normativa')[0]);
		formData.append('RECPSICO_GENEROS', document.getElementById("habilitar_opcional").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAI', document.getElementById("option1").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAII', document.getElementById("option2").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAIII', document.getElementById("option3").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAV', document.getElementById("option4").checked ? 1 : 0);

		// formData.append('JSON_TURNOS', JSON.stringify(turnosjs));
		//formData.append('SUMAHORASJORNADA', sumaHorasJornada);


		// Enviar datos
		$.ajax({
			type: 'POST',
			url: "/recopsiconormativa",
			data: formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function (dato) {

				$("#ID_RECOPSICONORMATIVA").val(dato.normativapsico.ID_RECOPSICONORMATIVA);
				
				//$("#RECPSICO_ID").val(dato.normativapsico.RECPSICO_ID);

				// mensaje
				swal({
					title: "Correcto",
					text: "" + dato.msj,
					type: "success", 
					buttons: {
						visible: false, 
					},
					timer: 1500,
					showConfirmButton: false
				});

				$('#boton_guardar_normativa').html('Guardar <i class="fa fa-save"></i>');
				document.getElementById('boton_carga_trabajadores').disabled = false;


			},
			beforeSend: function () {
				$('#boton_guardar_normativa').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (error) {
				$('#boton_guardar_normativa').html('Guardar <i class="fa fa-save"></i>');
				swal({
					title: "Error",
					text: "Error en la acción: " + error.responseText, 
					type: "error", 
					buttons: {
						visible: false, 
					},
					timer: 1500,
					showConfirmButton: false
				});
			}
		});
	}
});












