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


function bloqueo_reconocimiento(opcion) {

	if (rec_autorizado == 1) {

		var valor = parseInt(opcion);
		var mensaje_1 = '';
		var mensaje_2 = '';

		if (valor == 1) {
			mensaje_1 = '¿Desbloquear reconocimiento?';
			mensaje_2 = 'Desbloquear reconocimiento para edición';
		}
		else {
			mensaje_1 = '¿Bloquear reconocimiento?';
			mensaje_2 = 'Bloquear reconocimiento para edición';
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
					url: "/recsensorialbloqueo/" + $("#recsensorial_id").val() + "/" + valor,
					data: {},
					cache: false,
					success: function (dato) {
						// Actualiza tabla
						tabla_reconocimientosensorial();


					


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
					
					},
					error: function (dato) {
						// VERIFICA BLOQUEO DEL RECONOCIMIENTO
						

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

	} else {
		swal({
			title: "No disponible",
			text: "Este reconocimiento no esta autorizado, no se puede modificar",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2000,
			showConfirmButton: false
		});
	}
}







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
						"data": "agentes",
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
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo[]" value="" placeholder="Tiempo Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia[]" value="" placeholder="Frec. Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo_quimicos[]" value="0" placeholder="Tiempo Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia_quimicos[]" value="0" placeholder="Frec. Expo." required></td>' +



		'<td style="padding:2px 4px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
		'</tr>');

	areacategorias_total += 1;

	// Mostrar modal
	$('#modal_area').modal({ backdrop: false });



});



// function consulta_parametros(recsensorial_id, area_id)
// {
//     $.ajax({
//         type: "GET",
//         dataType: "json",
//         url: "/recsensorialareaparametros/"+recsensorial_id+"/"+area_id,
//         data:{},
//         cache: false,
//         success:function(dato)
//         {
//         	$('#checkbox_areaagentes').html('');
//             $.each( dato.parametros, function( key, value ) {
// 				$("#checkbox_areaagentes").append('<div class="col-12">'+
// 														'<div class="form-group">'+
// 															'<div class="switch" style="float: left;">'+
// 																'<label>'+
// 																	'<input type="checkbox" name="parametro[]" value="'+value.catprueba_id+'" '+value.checked+'>'+
// 																	'<span class="lever switch-col-light-blue"></span>'+
// 																'</label>'+
// 															'</div>'+
// 															'<label class="demo-switch-title" style="float: left;">'+value.catPrueba_Nombre+'</label>'+
// 														'</div>'+
// 													'</div>');
// 			});
//         },
//         beforeSend: function()
//         {
//             $('#checkbox_areaagentes').html('<i class="fa fa-spin fa-spinner fa-3x" style="margin: 0px auto;"></i>');
//         },
//         error: function(dato){
//         	$('#checkbox_areaagentes').html('');
//             return false;
//         }
//     });//Fin ajax
// }




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





// function consulta_categorias(recsensorial_id) {
// 	$.ajax({
// 		type: "GET",
// 		dataType: "json",
// 		url: "/recsensorialareacategorias/" + recsensorial_id,
// 		data: {},
// 		cache: false,
// 		success: function (dato) {
// 			select_areacategorias_opciones = '<option value=""></option>';
// 			$.each(dato.categorias, function (key, value) {
// 				select_areacategorias_opciones += '<option value="' + value.id + '">' + value.categoria_nombre + '</option>';
// 			});
// 		},
// 		error: function (dato) {
// 			select_areacategorias_opciones = '<option value="">No hay categorias que mostrar</option>';
// 			return false;
// 		}
// 	});//Fin ajax
// }


$("#boton_nueva_areacategoria").click(function () {
	$("#tabla_areacategorias tbody").append('<tr>' +
		'<td style="padding:2px 4px;"><select class="custom-select form-control" style="padding:0px 4px;" name="categoria[]" required>' + select_areacategorias_opciones + '</select></td>' +
		'<td style="padding:2px 4px;"><input type="text" class="form-control" style="padding:0px 4px;" name="actividad[]" value="" placeholder="Actividades en el área" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="geh[]" value="" placeholder="Ejem. 1" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="total[]" value="" placeholder="Total" required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo[]" value="" placeholder="Tiempo Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia[]" value="" placeholder="Frec. Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo_quimicos[]" value="0" placeholder="Tiempo Expo." required></td>' +
		'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia_quimicos[]" value="0" placeholder="Frec. Expo." required></td>' +
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
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo[]" value="' + tiempo + '" required></td>' +
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia[]" value="' + frecuencia + '" required></td>' +
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="tiempo_quimicos[]" value="' + tiempo_quimicos + '" required></td>' +
					'<td style="padding:2px 4px;"><input type="number" class="form-control" style="padding:0px 4px;" name="frecuencia_quimicos[]" value="' + frecuencia_quimicos + '" required></td>' +
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


// DESCARGAR TABLA POE .DOCX
$(".boton_descarga_poe").click(function () {
	// alert('imprimir');
	// Boton descarga
	$(".boton_descarga_poe").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Descargando tabla POE .docx');


	if (window.open("/recsensorialpoeword/" + recsensorial)) {
		setTimeout(function () {
			// Boton descarga
			$(".boton_descarga_poe").html('<span class="btn-label"><i class="fa fa-file-word-o"></i></span>Descargar tabla POE .docx');
		}, 5000);
	}
});


//===============================================================


$("#boton_nueva_maquina").click(function () {
	// Borrar formulario
	$('#form_maquina').each(function () {
		this.reset();
	});

	//Limpiamos el div donde se almacenan todos los select
	$(".listaAreasAfectadas").empty();


	// Campos Hidden
	$("#maquina_id").val(0);
	$("#maquina_recsensorial_id").val($("#recsensorial_id").val());

	// consulta areas
	consulta_areas('maquinaarea_id', 0, $("#recsensorial_id").val(), 0);

	$("#recsensorialmaquinaria_contenido").prop('disabled', false);

	$("#nombrecomun").prop('disabled', false).prop('required', false);

	$("#recsensorialmaquinaria_afecta").val("").change();

	// mostrar modal
	$('#modal_maquina').modal({ backdrop: false });
});


function funcion_tabla_recsensorialmaquinas(recsensorial_id) {
	try {
		var ruta = "/recsensorialmaquinariatabla/" + recsensorial_id;

		if (tabla_recsensorialmaquinas != null) {
			tabla_recsensorialmaquinas.clear().draw();
			tabla_recsensorialmaquinas.ajax.url(ruta).load();
		}
		else {
			tabla_recsensorialmaquinas = $('#tabla_recsensorialmaquinas').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_recsensorialmaquinas(recsensorial_id);
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
						"data": "recsensorialarea_nombre",
						"defaultContent": "-"
					},
					{
						"data": "nombre_completo",
						"defaultContent": "-"
					},
					{
						"data": "areasAfectan",
						"defaultContent": "-"
					},
					{
						"data": "recsensorialmaquinaria_cantidad_formateada",
						"defaultContent": "-"
					},
					{
						"className": 'editar',
						"orderable": false,
						// "data": 'IconoBloqueo',
						"defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
					},
					{
						"className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}

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
		funcion_tabla_recsensorialmaquinas(recsensorial_id)
	}
}

$('#recsensorialmaquinaria_unidadMedida').on('change', function () {

	if ($(this).val() == 7) {
		$("#recsensorialmaquinaria_contenido").prop('disabled', true);
		$("#recsensorialmaquinaria_contenido").val('');
	} else {
		$("#recsensorialmaquinaria_contenido").prop('disabled', false);
		$("#recsensorialmaquinaria_cantidad").prop('required', true);
	}
})


$("#boton_guardar_maquina").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// enviar datos
		$('#form_maquina').ajaxForm({
			dataType: 'json',
			type: 'POST',
			url: '/recsensorialmaquinaria',
			data: {},
			resetForm: false,
			success: function (dato) {
				// Campos Hidden
				$("#maquina_id").val(dato.maquina.id);
				$("#maquina_recsensorial_id").val(dato.maquina.recsensorial_id);

				// actualiza tabla
				funcion_tabla_recsensorialmaquinas(dato.maquina.recsensorial_id);

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
				$('#boton_guardar_maquina').html('Guardar <i class="fa fa-save"></i>');

				// cerrar modal
				$('#modal_maquina').modal('hide');
			},
			beforeSend: function () {
				$('#boton_guardar_maquina').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (dato) {
				// actualiza boton
				$('#boton_guardar_maquina').html('Guardar <i class="fa fa-save"></i>');
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


//Funcion para el selecto de Fuente Generadora pasa:
$('#recsensorialmaquinaria_afecta').on('change', function () {

	$('.listaAreasAfectadas').empty(); // Limpiamos el div
	$('#boton_agregar_alcance').click() // Mostramos las opciones de los alcances
	var select = $('#recsensorialmaquinaria_quimica')[0].selectize; //Mostramos el select de las sustancias quimicas 

	opcion = $(this).val();

	if (opcion == 2) { //Quimicos 

		$('#recsensorialmaquinaria_nombre').prop('required', false).css('display', 'none').val('');
		select.clear()
		select.enable()
		select.$wrapper.fadeIn(1);
		$('.AreaTipoAfecta').val('Químico').trigger('change');
		$('#boton_agregar_alcance').prop('disabled', true)

		//Desabilitamos algunas opcines
		$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', true);
		$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', true);
		$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', true);
		$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', true);
		$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', true);
		$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', false);

		$('#recsensorialmaquinaria_unidadMedida option[value="7"]').prop('disabled', true);


	} else if (opcion == 3) { //Quimicos y Fisico

		$('#recsensorialmaquinaria_nombre').prop('required', false).css('display', 'none').val('');
		select.clear()
		select.enable()
		select.$wrapper.fadeIn(1);
		$('#boton_agregar_alcance').prop('disabled', false)

		$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', false);

		$('#recsensorialmaquinaria_unidadMedida option[value="7"]').prop('disabled', true);



	} else { // Fisico

		$('#recsensorialmaquinaria_nombre').prop('required', true).css('display', 'block').val('');
		select.clear()
		select.disable()
		select.$wrapper.fadeOut(1);
		$('.AreaTipoAfecta').prop('disabled', false);
		$('#boton_agregar_alcance').prop('disabled', false)

		$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', false);
		$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', true);


		$('#recsensorialmaquinaria_unidadMedida option[value="7"]').prop('disabled', false);


	}


})



// Selecciona MAQUINARIA
$(document).ready(function () {
	$('#tabla_recsensorialmaquinas tbody').on('click', 'td.editar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialmaquinas.row(tr);
		// console.log(row.data());

		//Limpiamos el div donde se alamacenan los select
		$(".listaAreasAfectadas").empty();

		// Borrar formulario
		$('#form_maquina').each(function () {
			this.reset();
		});






		// consulta areas
		consulta_areas('maquinaarea_id', row.data().recsensorialarea_id, $("#recsensorial_id").val(), 0);

		// llenar campos
		$("#maquina_id").val(row.data().id);
		$("#maquina_recsensorial_id").val(row.data().recsensorial_id);
		$("#recsensorialmaquinaria_nombre").val(row.data().recsensorialmaquinaria_nombre);
		$("#recsensorialmaquinaria_cantidad").val(row.data().recsensorialmaquinaria_cantidad);
		$("#recsensorialmaquinaria_contenido").val(row.data().recsensorialmaquinaria_contenido);
		$("#recsensorialmaquinaria_unidadMedida").val(row.data().recsensorialmaquinaria_unidadMedida);
		$("#descripcionfuente").val(row.data().recsensorialmaquinaria_descripcionfuente);
		$("#nombrecomun").val(row.data().recsensorialmaquinaria_nombrecomun);









		var opcion = row.data().recsensorialmaquinaria_afecta
		var select = $('#recsensorialmaquinaria_quimica')[0].selectize; //Mostramos el select de las sustancias quimicas 
		$("#recsensorialmaquinaria_afecta").val(opcion);

		if (opcion == 2) { //Quimicos

			$('#recsensorialmaquinaria_nombre').prop('required', false).css('display', 'none').val('');
			select.clear()
			select.enable()
			select.$wrapper.fadeIn(1);
			select.setValue(row.data().PRODUCTO_ID);
			$('#boton_agregar_alcance').prop('disabled', true)
			$("#nombrecomun").prop('disabled', false).prop('required', false);



		} else if (opcion == 3) { //Quimicos y Fisico
			$('#recsensorialmaquinaria_nombre').prop('required', false).css('display', 'none').val('');
			select.clear()
			select.enable()
			select.$wrapper.fadeIn(1);
			select.setValue(row.data().PRODUCTO_ID);
			$('#boton_agregar_alcance').prop('disabled', false)
			$("#nombrecomun").prop('disabled', false).prop('required', false);


		} else { // Fisico

			$('#recsensorialmaquinaria_nombre').prop('required', true).css('display', 'block').val(row.data().NOMBRE_FUENTE);
			select.clear()
			select.disable()
			select.$wrapper.fadeOut(1);
			$('#boton_agregar_alcance').prop('disabled', false)
			$('#nombrecomun').prop('disabled', true);

		}



		$("#recsensorialmaquinaria_contenido").prop('disabled', false);
		if (row.data().recsensorialmaquinaria_unidadMedida == 7) {
			$("#recsensorialmaquinaria_contenido").prop('disabled', true);
		} else {
			$("#recsensorialmaquinaria_contenido").prop('disabled', false);
		}




		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/recsensorialmaquinariaAreasAfectan/" + row.data().id,
			data: {},
			cache: false,
			success: function (dato) {

				swal.close()

				data = dato.info
				data.forEach(function (elemento) {
					var divTipoAlcance = $('<div>').addClass('row generarTipoAlcance');
					divTipoAlcance.html(`
							 <div class="col-4">
								<div class="form-group">
									<label>Tipo alcance *</label>
									<select class="custom-select form-control AreaTipoAfecta" name="AreaTipoAfecta[]" required>
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="col-4" id="div_campo_factor">
								<div class="form-group">
									<label>Agente / Factor de riesgo / Servicio *</label>
									<select class="custom-select form-control AgenteFactor" name="AgenteFactor[]" required>
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label> Tipo *</label>
									<select class="custom-select form-control AgenteTipo" name="TipoAgente[]">
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="col-1 mt-4">
								<button type="button" class="btn btn-danger btn-circle botonEliminarTipoAlcance"> <i class="fa fa-trash"></i></button>
							</div>
						`);
					$('.listaAreasAfectadas').append(divTipoAlcance);


					// Manejar evento de eliminación
					$('.botonEliminarTipoAlcance', divTipoAlcance).click(function () {
						$(this).closest('.generarTipoAlcance').remove();
					});

					var selectTipoAlcance = $('.AreaTipoAfecta', divTipoAlcance);
					var selectAgenteFactor = $('.AgenteFactor', divTipoAlcance);
					var selectAgenteTipo = $('.AgenteTipo', divTipoAlcance);


					// Agregar opciones a los selects
					lista_alcances.forEach(function (opcion) {
						var optionTipoAlcance = $('<option>').text(opcion.catPrueba_Tipo).val(opcion.catPrueba_Tipo);
						if (opcion.catPrueba_Tipo === elemento.TIPO_ALCANCE) {
							optionTipoAlcance.prop('selected', true);
						}
						selectTipoAlcance.append(optionTipoAlcance);
					});

					var selectPruebaID = $(this).closest('.row').find('.AgenteFactor');
					var selectPruebaTipo = $(this).closest('.row').find('.AgenteTipo');



					MostratTiposAlcances(selectTipoAlcance.val(), selectAgenteFactor, selectAgenteTipo, elemento.PRUEBA_ID, elemento.TIPO);



				});

				//Esperamos que todo se complete de manera correcta, para poder darle nuevos estados a los select.
				setTimeout(() => {
					if (opcion == 2) { //Quimicos 

						//Desabilitamos algunas opcines
						$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', true);
						$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', true);
						$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', true);
						$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', true);
						$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', true);
						$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', false);


					} else if (opcion == 3) { //Quimicos y Fisico

						$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', false);


					} else { // Fisico

						$('.AreaTipoAfecta option[value="Alimentos"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Biológico"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Consultoría"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Factor de riesgo"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Físico"]').prop('disabled', false);
						$('.AreaTipoAfecta option[value="Químico"]').prop('disabled', true);



					}
				}, 1000);

			},
			beforeSend: function () {
				swal({
					title: "Cargando Información",
					text: "Espere un momento estamos cargando la información",
					type: "info", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					showConfirmButton: false
				});
			},
			error: function (dato) {
				// alert('Error: '+dato.msj);
				return false;
			}
		});

		// mostrar modal
		$('#modal_maquina').modal({ backdrop: false });
	});
});


// eliminar MAQUINARIA
$(document).ready(function () {
	$('#tabla_recsensorialmaquinas tbody').on('click', 'td.eliminar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialmaquinas.row(tr);

		if (parseInt(row.data().accion_activa) > 0) {
			swal({
				title: "¿Eliminar maquinaría?",
				text: "" + row.data().recsensorialmaquinaria_nombre,
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
						title: "¡Confirme nuevamente eliminar la maquinaría!",
						text: "" + row.data().recsensorialmaquinaria_nombre,
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
								url: "/recsensorialmaquinariaeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// actualiza tabla
									funcion_tabla_recsensorialmaquinas(row.data().recsensorial_id);

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
	});
});


//===============================================================


$("#boton_nueva_equipopp").click(function () {
	// Borrar formulario
	$('#form_equipopp').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#equipopp_id").val(0);
	$("#equipopp_recsensorial_id").val($("#recsensorial_id").val());

	consulta_categoria_epp($("#recsensorial_id").val(), 100);

	$("#tabla_lista_epp tbody").html('');
	$("#tabla_lista_epp tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" name="cuerpo[]" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" name="claveEpp[]" required></select></td>' +
		'<td><input type="text" class="form-control" name="tipoRiesgo[]" value="" style="height: auto;" required></td>' +
		'<td><button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button></td>' +
		'</tr>');
	// mostrar modal
	$('#modal_equipopp').modal({ backdrop: false });
});


$("#boton_nuevo_epp").click(function () {
	$("#tabla_lista_epp tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" name="cuerpo[]" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" name="claveEpp[]" required></select></td>' +
		'<td><input type="text" class="form-control" name="tipoRiesgo[]" value=""  style="height: auto;" required></td>' +
		'<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>' +
		'</tr>');
});


// Eliminar epp
$(document).ready(function () {
	$('#tabla_lista_epp tbody').on('click', '.eliminar', function () {
		// alert(tr.index()); //index fila
		var tr = $(this).closest('tr');
		$(this).closest("tr").remove();// eliminar fila TR vacia
	});
});


// Mostramos la clave y EPP en el select
$("#tabla_lista_epp tbody").on("change", ".claveyEpp", function () {


	var valorSeleccionado = $(this).find("option:selected");
	var infoAdicional = valorSeleccionado.data("descripcion");

	var fila = $(this).closest("tr");
	var inputTipoRiesgo = fila.find("input[name='tipoRiesgo[]']"); // Encontrar el input en la misma fila

	inputTipoRiesgo.val(infoAdicional);
});



$("#tabla_lista_epp tbody").on("change", ".regionAnatomica", function () {

	var valorSeleccionado = $(this).val();
	var fila = $(this).closest("tr");
	var selectClaveEppp = fila.find("select[name='claveEpp[]']"); // Encontrar el Select en la misma fila

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialClaveEpp/" + valorSeleccionado,
		data: {},
		cache: false,
		success: function (dato) {
			selectClaveEppp.html(dato.opciones);
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//


});




function funcion_tabla_recsensorialequipopp(recsensorial_id) {
	try {
		var ruta = "/recsensorialequipopptabla/" + recsensorial_id;

		if (tabla_recsensorialequipopp != null) {
			tabla_recsensorialequipopp.clear().draw();
			tabla_recsensorialequipopp.ajax.url(ruta).load();
		}
		else {
			tabla_recsensorialequipopp = $('#tabla_recsensorialequipopp').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_recsensorialequipopp(recsensorial_id);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id"
					// },
					{
						"data": "numero_registro"
					},
					{
						"data": "categoria",
						"defaultContent": "-"
					},
					{
						"data": "epp",
						"defaultContent": "-"
					},
					{
						"className": 'editar',
						"orderable": false,
						// "data": 'IconoBloqueo',
						"defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
					},
					{
						"className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}

				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"order": [[0, "desc"]],
				"ordering": true,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_",
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
		funcion_tabla_recsensorialequipopp(recsensorial_id);
	}
}


function consulta_categoria_epp(recsensorial_id, seleccionado_id) {
	// alert('mensaje');
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialeppcategorias/" + recsensorial_id + "/" + seleccionado_id,
		data: {},
		cache: false,
		success: function (dato) {
			$("#equipopp_recsensorialcategoria_id").html(dato.opciones);
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


$("#boton_guardar_equipopp").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// enviar datos
		$('#form_equipopp').ajaxForm({
			dataType: 'json',
			type: 'POST',
			url: '/recsensorialequipopp',
			data: {},
			resetForm: false,
			success: function (dato) {
				// Campos Hidden
				$("#equipopp_id").val(0);
				$("#equipopp_recsensorial_id").val(dato.recsensorial_id);

				// actualiza tabla
				funcion_tabla_recsensorialequipopp(dato.recsensorial_id);

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
				$('#boton_guardar_equipopp').html('Guardar <i class="fa fa-save"></i>');

				// cerrar modal
				$('#modal_equipopp').modal('hide');
			},
			beforeSend: function () {
				$('#boton_guardar_equipopp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (dato) {
				// actualiza boton
				$('#boton_guardar_equipopp').html('Guardar <i class="fa fa-save"></i>');
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


// Selecciona EQUIPO PP
$(document).ready(function () {
	$('#tabla_recsensorialequipopp tbody').on('click', 'td.editar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialequipopp.row(tr);

		// Borrar formulario
		$('#form_equipopp').each(function () {
			this.reset();
		});

		// llenar campos
		$("#equipopp_id").val(1);
		$("#equipopp_recsensorial_id").val(row.data().recsensorial_id);
		consulta_categoria_epp(row.data().recsensorial_id, row.data().categoria_id);

		// Consulta EPP
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/recsensorialeppeditar/" + row.data().recsensorial_id + "/" + row.data().categoria_id,
			data: {},
			cache: false,
			success: function (dato) {
				$("#tabla_lista_epp tbody").html(dato.tabla);
			},
			beforeSend: function () {
				$("#tabla_lista_epp tbody").html('<tr><td colspan="3"><i class="fa fa-spin fa-spinner fa-5x"></td></tr>');
			},
			error: function (dato) {
				// alert('Error: '+dato.msj);
				$("#tabla_lista_epp tbody").html('');
				return false;
			}
		});//Fin ajax

		// mostrar modal
		$('#modal_equipopp').modal({ backdrop: false });
	});
});


// eliminar EQUIPO PP
$(document).ready(function () {
	$('#tabla_recsensorialequipopp tbody').on('click', 'td.eliminar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialequipopp.row(tr);

		if (parseInt(row.data().accion_activa) > 0) {
			swal({
				title: "¿Eliminar equipo de protección personal?",
				text: row.data().categoria,
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
						title: "¡Confirme nuevamente eliminar el equipo de protección personal!",
						text: row.data().categoria,
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
								url: "/recsensorialequipoppeliminar/" + row.data().recsensorial_id + "/" + row.data().categoria_id,
								data: {},
								cache: false,
								success: function (dato) {
									// actualiza tabla
									funcion_tabla_recsensorialequipopp(row.data().recsensorial_id);

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
	});
});


//===============================================================


$("#boton_guardar_responsables").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// enviar datos
		$('#form_responsables').ajaxForm({
			dataType: 'json',
			type: 'POST',
			url: '/recsensorial',
			data: {
				opcion: 3,
				recsensorial_id: recsensorial,
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
				$('#boton_guardar_responsables').html('Guardar <i class="fa fa-save"></i>');
			},
			beforeSend: function () {
				$('#boton_guardar_responsables').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (dato) {
				// actualiza boton
				$('#boton_guardar_responsables').html('Guardar <i class="fa fa-save"></i>');

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


$("#boton_nuevo_anexo").click(function () {
	// Borrar formulario
	$('#form_anexo').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#anexo_recsensorial_id").val($("#recsensorial_id").val());


	// Campo tipo de informe
	if (parseInt(recsensorial_alcancefisico) > 0 && parseInt(recsensorial_alcancequimico) > 0) {
		$("#recsensorialanexo_tipo").html('<option value=""></option>' +
			'<option value="1">Físicos</option>' +
			'<option value="2">Químicos</option>');
	}
	else if (parseInt(recsensorial_alcancefisico) > 0) {
		$("#recsensorialanexo_tipo").html('<option value=""></option>' +
			'<option value="1">Físicos</option>');
	}
	else {
		$("#recsensorialanexo_tipo").html('<option value=""></option>' +
			'<option value="2">Químicos</option>');
	}


	// Campo documento
	$('#acreditacion_id').html('<option value=""></option>');


	// mostrar modal
	$('#modal_anexo').modal({ backdrop: false });
});


function anexo_proveedor(proveedor_id) {
	if (proveedor_id) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/recsensorialanexolista/" + proveedor_id,
			data: {},
			cache: false,
			success: function (dato) {
				$("#acreditacion_id").html(dato.opciones);
			},
			beforeSend: function () {
				$('#acreditacion_id').html('<option value="">Consultando...</option>');
			},
			error: function (dato) {
				// alert('Error: '+dato.msj);
				return false;
			}
		});//Fin ajax
	}
	else {
		$("#acreditacion_id").html('<option value=""></option>');
	}

}


$("#boton_guardar_anexo").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// enviar datos
		$('#form_anexo').ajaxForm({
			dataType: 'json',
			type: 'POST',
			url: '/recsensorialanexo',
			data: {
				opcion: 1,
				RECSENSORIAL_ID: $('#recsensorial_id').val()
			},
			resetForm: false,
			success: function (dato) {

				if ($('#recsensorialanexo_tipo').val() == 1) {
					// actualiza tabla
					tabla_recsensorialanexos2(dato.recsensorial_id);

				} else {

					tabla_recsensorialanexos(dato.recsensorial_id);

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
				$('#boton_guardar_anexo').html('Guardar <i class="fa fa-save"></i>');

				// cerrar modal
				$('#modal_anexo').modal('hide');
			},
			beforeSend: function () {
				$('#boton_guardar_anexo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			},
			error: function (dato) {
				// actualiza boton
				$('#boton_guardar_anexo').html('Guardar <i class="fa fa-save"></i>');
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


var datatable_recsensorialanexos = null;
function tabla_recsensorialanexos(recsensorial_id) {
	try {
		var ruta = "/recsensorialanexotabla/" + recsensorial_id + "/" + 2;

		if (datatable_recsensorialanexos != null) {
			datatable_recsensorialanexos.clear().draw();
			datatable_recsensorialanexos.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_recsensorialanexos = $('#tabla_recsensorialanexos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert(json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_recsensorialanexos ' + code);
						if (numeroejecucion <= 1) {
							tabla_recsensorialanexos(recsensorial_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "recsensorialanexo_orden",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "anexo_tipo",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "proveedor_RazonSocial",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "acreditacion_Entidad",
						defaultContent: "-",
						orderable: false,
						// className: 'checkbox_cancelado',
					},

					{
						data: "acreditacion_Vigencia",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_pdf",
						defaultContent: "-",
						orderable: false,
						// className: 'anexo_pdf',
					},
					{
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
						// className: 'elimina_anexo',
					}
				],
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				// rowsGroup: [1], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: true,
				paging: true,
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

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_recsensorialanexos.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_recsensorialanexos(recsensorial_id);
	}
}


var datatable_recsensorialanexos2 = null;
function tabla_recsensorialanexos2(recsensorial_id) {
	try {
		var ruta = "/recsensorialanexotabla/" + recsensorial_id + "/" + 1;

		if (datatable_recsensorialanexos2 != null) {
			datatable_recsensorialanexos2.clear().draw();
			datatable_recsensorialanexos2.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_recsensorialanexos2 = $('#tabla_recsensorialanexos2').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert(json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_recsensorialanexos2 ' + code);
						if (numeroejecucion <= 1) {
							tabla_recsensorialanexos2(recsensorial_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "recsensorialanexo_orden",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "anexo_tipo",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "proveedor_RazonSocial",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "acreditacion_Entidad",
						defaultContent: "-",
						orderable: false,
						// className: 'checkbox_cancelado',
					},

					{
						data: "acreditacion_Vigencia",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_pdf",
						defaultContent: "-",
						orderable: false,
						// className: 'anexo_pdf',
					},
					{
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
						// className: 'elimina_anexo',
					}
				],
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				// rowsGroup: [1], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: true,
				paging: true,
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

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_recsensorialanexos2.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_recsensorialanexos2(recsensorial_id);
	}
}




$('#tabla_recsensorialanexos tbody').on('click', 'td>button.anexo_pdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_recsensorialanexos.row(tr)

	if (row.data().TIPO === 'IMAGEN') {

		var archivo = row.data().acreditacion_SoportePDF;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		// Obtener FOTO
		var imagenUrl = '/mostrarFotoAnexo/' + row.data().contrato_anexo_id + extension;

		// Mostrar Foto en el INPUT
		if ($('#contrato_anexo_imagen').data('dropify')) {

			$('#imagen_titulo').text(row.data().proveedor_RazonSocial);

			$('#contrato_anexo_imagen').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#contrato_anexo_imagen').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#contrato_anexo_imagen').dropify().data('dropify').init();
		}
		else {
			// $('#signatariofoto').attr('data-height', 400);
			$('#imagen_titulo').text(row.data().proveedor_RazonSocial);
			$('#contrato_anexo_imagen').attr('data-default-file', imagenUrl);
			$('#contrato_anexo_imagen').dropify({
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

		$('#modal_anexo_imagen').modal({ backdrop: false });

	} else {



		if (row.data().acreditacion_id === null) {


			$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/' + row.data().contrato_anexo_id + '/' + 1);

			// Titulo modal
			$('#modal_visor .modal-title').html('Anexo: ' + row.data().proveedor_RazonSocial);

			// Abrir modal
			$('#modal_visor').modal({ backdrop: false });

		} else {

			$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/' + row.data().acreditacion_id + '/' + 0);

			// Titulo modal
			$('#modal_visor .modal-title').html('Anexo: ' + row.data().acreditacion_Entidad + ' [' + row.data().acreditacion_Numero + ']');

			// Abrir modal
			$('#modal_visor').modal({ backdrop: false });
		}
	}
});


$('#tabla_recsensorialanexos tbody').on('click', 'td>button.elimina_anexo', function () {
	var tr = $(this).closest('tr');
	var row = datatable_recsensorialanexos.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El anexo: \n" + row.data().acreditacion_Entidad + ' [' + row.data().acreditacion_Numero + ']\nde la lista.',
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

				$.ajax({
					type: "GET",
					dataType: "json",
					url: "/recsensorialanexoeliminar/" + row.data().id + "/" + row.data().contrato_anexo_id,
					data: {},
					cache: false,
					success: function (dato) {
						// Actualizar tabla
						tabla_recsensorialanexos(recsensorial);

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
						// $('#tabla_recsensorialanexos tbody').html('<tr><td colspan="8"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
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
	return false;
});



$('#tabla_recsensorialanexos2 tbody').on('click', 'td>button.anexo_pdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_recsensorialanexos2.row(tr)

	if (row.data().TIPO === 'IMAGEN') {

		var archivo = row.data().acreditacion_SoportePDF;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		// Obtener FOTO
		var imagenUrl = '/mostrarFotoAnexo/' + row.data().contrato_anexo_id + extension;

		// Mostrar Foto en el INPUT
		if ($('#contrato_anexo_imagen').data('dropify')) {

			$('#imagen_titulo').text(row.data().proveedor_RazonSocial);

			$('#contrato_anexo_imagen').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#contrato_anexo_imagen').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#contrato_anexo_imagen').dropify().data('dropify').init();
		}
		else {
			// $('#signatariofoto').attr('data-height', 400);
			$('#imagen_titulo').text(row.data().proveedor_RazonSocial);
			$('#contrato_anexo_imagen').attr('data-default-file', imagenUrl);
			$('#contrato_anexo_imagen').dropify({
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

		$('#modal_anexo_imagen').modal({ backdrop: false });

	} else {



		if (row.data().acreditacion_id === null) {


			$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/' + row.data().contrato_anexo_id + '/' + 1);

			// Titulo modal
			$('#modal_visor .modal-title').html('Anexo: ' + row.data().proveedor_RazonSocial);

			// Abrir modal
			$('#modal_visor').modal({ backdrop: false });

		} else {

			$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/' + row.data().acreditacion_id + '/' + 0);

			// Titulo modal
			$('#modal_visor .modal-title').html('Anexo: ' + row.data().acreditacion_Entidad + ' [' + row.data().acreditacion_Numero + ']');

			// Abrir modal
			$('#modal_visor').modal({ backdrop: false });
		}
	}
});


$('#tabla_recsensorialanexos2 tbody').on('click', 'td>button.elimina_anexo', function () {
	var tr = $(this).closest('tr');
	var row = datatable_recsensorialanexos2.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El anexo: \n" + row.data().acreditacion_Entidad + ' [' + row.data().acreditacion_Numero + ']\nde la lista.',
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

				$.ajax({
					type: "GET",
					dataType: "json",
					url: "/recsensorialanexoeliminar/" + row.data().id + "/" + row.data().contrato_anexo_id,
					data: {},
					cache: false,
					success: function (dato) {
						// Actualizar tabla
						tabla_recsensorialanexos2(recsensorial);

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
						// $('#tabla_recsensorialanexos tbody').html('<tr><td colspan="8"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
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
	return false;
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////PARAMETROS///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function mostrar_vista_parametro(parametro_nombre, recsensorial_id, parametro_id) {
	// alert('Rec. Sensorial: '+recsensorial_id+' - parametro: '+parametro_nombre+' Id '+parametro_id);
	var opcion = parseInt(parametro_id);
	$("#titulo_parametro").html(parametro_nombre);

	$.ajax({
		success: function () {
			switch (opcion) {
				case 1:
					$('#forms_parametro').load('/parametroruidovista/' + recsensorial_id);
					break;
				case 2:
					$('#forms_parametro').load('/parametrovibracionvista/' + recsensorial_id);
					break;
				case 3:
					$('#forms_parametro').load('/parametrotemperaturavista/' + recsensorial_id);
					break;
				case 4:
					$('#forms_parametro').load('/parametroiluminacionvista/' + recsensorial_id);
					break;
				case 5:
					$('#forms_parametro').load('/parametroradiacionionizantevista/' + recsensorial_id);
					break;
				case 6:
					$('#forms_parametro').load('/parametroradiacionnoionizantevista/' + recsensorial_id);
					break;
				case 7:
					$('#forms_parametro').load('/parametroprecionesambientalesvista/' + recsensorial_id);
					break;
				case 8:
					$('#forms_parametro').load('/parametrocalidadairevista/' + recsensorial_id);
					break;
				case 9:
					$('#forms_parametro').load('/parametroaguavista/' + recsensorial_id);
					break;
				case 10:
					$('#forms_parametro').load('/parametrohielovista/' + recsensorial_id);
					break;
				case 11:
					$('#forms_parametro').load('/parametroalimentovista/' + recsensorial_id);
					break;
				case 12:
					$('#forms_parametro').load('/parametrosuperficievista/' + recsensorial_id);
					break;
				case 13:
					$('#forms_parametro').load('/parametroergonomiavista/' + recsensorial_id);
					break;
				case 14:
					$('#forms_parametro').load('/parametropsicosocialvista/' + recsensorial_id);
					break;
				case 15:
					// Quimicos, Esta opcion no se muestra en el menú
					break;
				case 16:
					$('#forms_parametro').load('/parametroserviciopersonalvista/' + recsensorial_id);
					break;
				case 17:
					$('#forms_parametro').load('/parametromapariesgovista/' + recsensorial_id);
					break;
				default:
					break;
			}
		},
		beforeSend: function () {
			$('#forms_parametro').html('<div style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></div>');
		},
		error: function () {
			$('#forms_parametro').html('<div style="text-align: center;">Error al cargar agente</div>');
			return false;
		}
	});//Fin ajax
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////RESUMEN PARAMETROS///////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function actualiza_tabla_recsensorial_resumen(recsensorial_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialresumentabla/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			// actualiza tabla
			$('#tabla_recsensorial_resumen tbody').html(dato.tabla);
		},
		beforeSend: function () {
			$('#tabla_recsensorial_resumen tbody').html('<tr><td colspan="3" align="center"><i class="fa fa-spin fa-spinner fa-3x"></i></td></tr>');
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


function actualiza_tabla_recsensorialquimicos_resumen(recsensorial_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialquimicosresumentabla/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			// actualiza tabla
			$('#tabla_recsensorialquimicos_resumen tbody').html(dato.tabla);
		},
		beforeSend: function () {
			$('#tabla_recsensorialquimicos_resumen tbody').html('<tr><td colspan="3" align="center"><i class="fa fa-spin fa-spinner fa-3x"></i></td></tr>');
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}



function actualiza_tabla_recsensorialquimicos_resumen_cliente(recsensorial_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialquimicosresumentabla_cliente/" + recsensorial_id,
		data: {},
		cache: false,
		success: function (dato) {
			// actualiza tabla
			$('#tabla_recsensorialquimicos_resumen_cliente tbody').html(dato.tabla);
		},
		beforeSend: function () {
			$('#tabla_recsensorialquimicos_resumen_cliente tbody').html('<tr><td colspan="3" align="center"><i class="fa fa-spin fa-spinner fa-3x"></i></td></tr>');
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////QUIMICOS////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


var categorias_lista;
var select_catsustancia_opciones = '';
var select_umedida_opciones = '';
var sustancia_lista_total = 0;
var sustancia_lista_totalactual = 0;


function funcion_tabla_recsensorialquimicosinventario(recsensorial_id) {
	try {
		var ruta = "/recsensorialquimicosinventariotabla/" + recsensorial_id;

		if (tabla_recsensorialquimicos_inventario != null) {
			tabla_recsensorialquimicos_inventario.clear().draw();
			tabla_recsensorialquimicos_inventario.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			tabla_recsensorialquimicos_inventario = $('#tabla_recsensorialquimicos_inventario').DataTable({
				"ajax": {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// alert("Done! "+json.msj);

						$('#recsensorialevidenciasquimicos_recsensorialarea_id').html(json.optionselect_areasquimicos);


						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_quimicosinventario ' + code);
						if (numeroejecucion <= 1) {
							funcion_tabla_recsensorialquimicosinventario(recsensorial_id);
							numeroejecucion += 1;
						}
					}
				},
				"columns": [
					// {
					//     "data": "id"
					// },		            
					// {
					//     "data": "numero_registro"
					// },
					{
						"className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						// "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>'
					},
					{
						"className": 'editar',
						"orderable": false,
						"data": 'boton_editar',
						// "defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
					},
					{
						"data": "recsensorialarea_nombre",
						"defaultContent": " - "
					},
					{
						"data": "categoria_nombre",
						"defaultContent": " - "
					},
					{
						"data": "catsustancia_nombre",
						"defaultContent": " - "
					},
					{
						"data": "componentes",
						"defaultContent": " - "
					},
					{
						"data": "catestadofisicosustancia_estado",
						"defaultContent": " - "
					},
					{
						"data": "sustancia_cantidad",
						"defaultContent": " - "
					},
					{
						"data": "recsensorialcategoria_tiempoexpo",
						"defaultContent": " - "
					},
					{
						"data": "recsensorialcategoria_frecuenciaexpo",
						"defaultContent": " - "
					}
				],
				"order": [[2, "asc"]],
				"rowsGroup": [0, 1, 2, 3], //agrupar filas
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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
					"processing": "Actualizando <i class='fa fa-spin fa-spinner fa-3x'></i>",
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
		funcion_tabla_recsensorialquimicosinventario(recsensorial_id);
	}
}


$("#boton_nueva_sustacia").click(function () {
	// Borrar formulario
	$('#form_inventariosustancia').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#sustanciaarea_id").val(0);
	$("#sustancia_recsensorial_id").val($("#recsensorial_id").val());

	// llenar select areas
	consulta_areas('sustancia_recsensorialarea_id', 0, $("#recsensorial_id").val(), 1)

	// Vaciar seccion checkbox categorias
	// $("#checkbox_seleccionacategorias").prop('checked', false);
	// $("#divcheckbox_quimicosareacategorias").html('');

	// Mostrar catalogo sustancias
	sustancia_lista_total = 0;
	funcion_tabla_catsustancias($("#recsensorial_id").val(), 0);

	// vaciar lista de  catalogo sustancias
	$("#tabla_catsustancias > tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');

	// mostrar modal
	$('#modal_inventariosustancia').modal({ backdrop: false });
});


$('input[type=radio][name=TIPO_CLASIFICACION]').on('change', function () {
	var opcion = $(this).val();

	if (parseInt(opcion) == 1) {
		$('#CLASIFICACION_GRUPO').val('I')

	} else if (parseInt(opcion) == 2) {

		$('#CLASIFICACION_GRUPO').val('A')

	}
});


//Creamos las categorias junto con sus su informacion
$("#boton_nuevo_grupo").click(function () {

	// Borrar formulario
	$('#form_grupos').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#NUEVO_GRUPO").val(1);
	$("#GRUPO_RECSENSORIAL_ID").val($("#recsensorial_id").val());

	// vaciar lista de  catalogo sustancias
	$("#tabla_grupos> tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');

	//Realizamos la peticion para el esqueleto de la informacion
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerCategoriasReconomiento/" + $("#recsensorial_id").val() + "/" + "XXXX" + "/" + 0,
		data: {},
		cache: false,
		success: function (dato) {
			// data = dato

			//VALIDAMOS LA CLASIFICACION DE LOS GRUPOS DE EXPOSICION HOMOGENEA
			if (dato.GEH.length == 0) {

				$('#TIPO_CLASIFICACION_ROMANOS').prop('disabled', false);
				$('#TIPO_CLASIFICACION_LETRAS').prop('disabled', false);
				$('#TIPO_CLASIFICACION_ROMANOS').prop('checked', false);
				$('#TIPO_CLASIFICACION_LETRAS').prop('checked', false);



				swal({
					title: "Grupos de Exposición Homogénea",
					text: "Seleccione la clasificación de los GEH (Num. Romanos o Letras), ya que despues de guardar esto no se podra editar!",
					type: "warning", // warning, error, success, info
					buttons: {
						visible: true, // true , false
					},
					showConfirmButton: true
				});


			} else {

				$('#TIPO_CLASIFICACION_ROMANOS').prop('disabled', true);
				$('#TIPO_CLASIFICACION_LETRAS').prop('disabled', true);

				if (dato.GEH[0].OPCION == 1) {

					$('#TIPO_CLASIFICACION_ROMANOS').prop('checked', true);
					$('#TIPO_CLASIFICACION_LETRAS').prop('checked', false);
				} else {

					$('#TIPO_CLASIFICACION_LETRAS').prop('checked', true);
					$('#TIPO_CLASIFICACION_ROMANOS').prop('checked', false);

				}

				$('#CLASIFICACION_GRUPO').val(dato.CONSECUTIVO)




			}



			// Creamos lista de categorias
			var filastr_categorias = '';
			$.each(dato.categorias, function (key, value) {


				filastr_categorias += `<tr>
										<td style="width: 700px!important;">
											<div class="form-group">
												<div class="switch" style="float: left;">
													<label>
														<input type="checkbox" onchange="activarTrabajadores(this)" name="ID_RELACION[]" value="${value.ID_RELACION}" >
														<span class="lever switch-col-light-blue"></span>
													</label>
												</div>
												<label class="demo-switch-title" style="float: left; font-size:15px;">[ ${value.AREA} ] ${value.CATEGORIA}</label>
												<input type="hidden" class="form-control" name="ID_AREA_GRUPO[]" value="${value.AREA_ID}" disabled>
												<input type="hidden" class="form-control" name="ID_CATEGORIA_GRUPO[]" value="${value.CATEGORIA_ID}" disabled>
											</div>
										</td>
										<td style="width: 200px!important;">
											<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadore" name="NUM_TRABAJADORES_VIEJO[]" value="${value.PERSONAL}"  disabled>
										</td>
										<td style="width: 200px!important;">
											<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadores expuestos" onchange="validarTrabajadores(this)" name="NUM_TRABAJADORES_NUEVO[]" disabled>
										</td>
									</tr>`
			});


			html = `<tr>
				<td style="width: 100%px!important;">
					<table>
						<tr>
							<td style="width: 720px!important;">
								<label>&nbsp;</label>
								<select class="custom-select form-control" id="COMPONENTE_ID" name="COMPONENTE_ID" required>
									${dato.componentes}
								</select>
							</td>
							<td style="width: 200px!important;">
								<label>&nbsp;</label>
							</td>
							<td style="width: 200px!important;">
								<label>&nbsp;</label>
							</td>
						</tr>
						${filastr_categorias}
					</table>
				</td>
			</tr>`


			$('#tabla_grupos > tbody').html(html);
		},
		beforeSend: function () {
			$("#tabla_grupos > tbody").html('<tr><td colspan="3" style="width: 1143px; text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></td></tr>');
		},
		error: function (dato) {
			$("#tabla_grupos > tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');
			return false;
		}
	})


	// mostrar modal
	$('#modal_grupos').modal({ backdrop: false });
});


function consulta_select_categoriasxarea(recsensorialarea_id) {


	// console.log(recsensorialarea_id)
	// Inicializar datos
	sustancia_lista_total = 0;

	if (recsensorialarea_id) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/recsensorialselectcategoriasxareaquimicos/" + recsensorialarea_id,
			data: {},
			cache: false,
			success: function (dato) {
				categorias_lista = dato.categorias;

				$("#boton_nuevasustancia_inventario").attr('disabled', false);
				$("#tabla_catsustancias > tbody").html(dato.area_listasustancias);
				sustancia_lista_total = parseInt(dato.area_listasustancias_total);

				// Recorrer campos sustancias
				$.each($('.select_search_sustancia'), function (key, value) {
					$('#' + this.id).selectize(); //Inicializar campo tipo [select-search]
				});

			},
			beforeSend: function () {
				$("#tabla_catsustancias > tbody").html('<tr><td colspan="3" style="width: 1143px; text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></td></tr>');
			},
			error: function (dato) {
				$("#tabla_catsustancias > tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');
				$("#boton_nuevasustancia_inventario").attr('disabled', true);
				return false;
			}
		});//Fin ajax
	}
	else {
		$("#tabla_catsustancias > tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');
		$("#boton_nuevasustancia_inventario").attr('disabled', true);
	}
}


$("#boton_nuevasustancia_inventario").click(function () {

	// Creamos lista de categorias
	var filastr_categorias = '';
	$.each(categorias_lista, function (key, value) {


		filastr_categorias += '<tr>' +
			'<td style="width: 680px!important;">' +
			'<div class="form-group">' +
			'<div class="switch" style="float: left;">' +
			'<label>' +
			'<input type="checkbox" name="categoria[]" value="' + sustancia_lista_total + '~' + value.categoria_id + '" onchange="activa_categoria(this, ' + (sustancia_lista_total + 1) + key + ');">' +
			'<span class="lever switch-col-light-blue"></span>' +
			'</label>' +
			'</div>' +
			'<label class="demo-switch-title" style="float: left;">' + value.categoria_nombre + '</label>' +
			'</div>' +
			'</td>' +
			'<td style="width: 180px!important;">' +
			'<label>' + '<b>' + 'Exp.Minutos' + '</b>' + '</label>' +
			'<input type="number" step="any" class="form-control" placeholder="Exp. minutos" id="tiempo_' + (sustancia_lista_total + 1) + key + '" name="tiempo[]" disabled>' +
			'</td>' +
			'<td style="width: 180px!important;">' +
			'<label>' + '<b>' + 'Frecuencia exp.' + '</b>' + '</label>' +
			'<input type="number" step="any" class="form-control" placeholder="Frecuencia exp." id="frecuencia_' + (sustancia_lista_total + 1) + key + '" name="frecuencia[]" disabled>' +
			'</td>' +
			'</tr>';
	});

	// Crear fila
	var rows = (parseInt(document.getElementById("tabla_catsustancias").rows.length) - 1);
	// $("#tabla_catsustancias > tbody").append('<tr>'+
	$('#tabla_catsustancias > tbody > tr').eq(rows - 1).before('<tr>' +
		'<td style="width: 50px!important;">' +
		'0' +
		'</td>' +
		'<td style="width: 1040px!important;">' +
		'<table>' +
		'<tr>' +
		'<td style="width: 680px!important;">' +
		'<label>' + '&nbsp;' + '</label>' +

		'<select class="custom-select form-control select_search_sustancia" id="selectsearch_sustancia_' + sustancia_lista_total + '" name="sustancia_catalogo[]" required>' +
		select_catsustancia_opciones +
		'</select>' +
		'</td>' +
		'<td style="width: 180px!important;">' +
		'<label>' + '<b>' + 'Cantidad manejada' + '</b>' + '</label>' +
		'<input type="number" step="any" class="form-control" placeholder="Cantidad" name="cantidad[]" required>' +
		'</td>' +
		'<td style="width: 180px!important;">' +
		'<label>' + '<b>' + 'Unidad medida' + '</b>' + '</label>' +
		'<select class="custom-select form-control" name="umedida[]" required>' +
		select_umedida_opciones +
		'</select>' +
		'</td>' +
		'</tr>' +
		filastr_categorias +
		'</table>' +
		'</td>' +
		'<td style="padding-left: 12px;" class="eliminar">' +
		'<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>' +
		'</td>' +
		'</tr>');

	// Inicializa campo tipo select search
	$('#selectsearch_sustancia_' + sustancia_lista_total).selectize();

	// Desplazar a la primera fila de la tabla
	$('#tabla_catsustancias > tbody').scrollTop(0);

	let $input = $('#tabla_catsustancias > tbody').find('input.form-control[name="cantidad[]"]');
	let $targetInput = $input.last();

	let positionAdjustment = $targetInput.position().top + 15;

	$('#tabla_catsustancias > tbody').animate({
		scrollTop: positionAdjustment
	}, 1000);

	// Incrementar contador
	sustancia_lista_total += 1;
});


$("#boton_nueva_categoria_grupo").click(function () {

	// Crear fila
	var rows = (parseInt(document.getElementById("tabla_grupos").rows.length) - 1);
	$('#tabla_grupos > tbody > tr').eq(rows - 1).before('<tr>' +
		'<td>' +
		'0' +
		'</td>' +
		'<td style="width:750px!important">' +
		'<select class="custom-select form-control" onchange="obtenerTrabajadores(this)" name="CATEGORIA_GRUPO[]" required>' +
		categoriasEvaluadas +
		'</select>' +
		'<td style="width:100px!important">' +
		'<input type="hidden" class="form-control"  name="AREA_GRUPO[]" >' +
		'<input type="number" class="form-control"  name="NUM_TRABAJADORES_VIEJO[]" required readonly>' +
		'</td>' +
		'<td>' +
		'<input type="number"  class="form-control" onchange="validarTrabajadores(this)" name="NUM_TRABAJADORES_NUEVO[]" required>' +
		'</td>' +
		'<td class="eliminar">' +
		'<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button>' +
		'</td>' +
		'</tr>');

	// Inicializa campo tipo select search
	// $('#selectsearch_sustancia_'+sustancia_lista_total).selectize();

	// Desplazar a la primera fila de la tabla
	$('#tabla_grupos > tbody').scrollTop(0);


	let $input = $('#tabla_grupos > tbody').find('input.form-control[name="NUM_TRABAJADORES_VIEJO[]"]');
	let $targetInput = $input.last();

	let positionAdjustment = $targetInput.position().top + 15;

	$('#tabla_grupos > tbody').animate({
		scrollTop: positionAdjustment
	}, 1000);

	// Incrementar contador
	sustancia_lista_total += 1;
});


function validarTrabajadores(input) {

	const row = input.closest('tr');
	const trabajadores = row.querySelector('input[name="NUM_TRABAJADORES_VIEJO[]"]');


	if (input.value > trabajadores.value) {

		input.classList.add('error');

	} else {

		input.classList.remove('error');
	}

}

function activarTrabajadores(check) {

	const row = check.closest('tr');
	const trabajadoresViejos = row.querySelector('input[name="NUM_TRABAJADORES_VIEJO[]"]');
	const trabajadoresNuevo = row.querySelector('input[name="NUM_TRABAJADORES_NUEVO[]"]');
	const areas = row.querySelector('input[name="ID_AREA_GRUPO[]"]');
	const categorias = row.querySelector('input[name="ID_CATEGORIA_GRUPO[]"]');

	if (check.checked) {

		trabajadoresViejos.readOnly = true;
		trabajadoresViejos.disabled = false;
		trabajadoresNuevo.disabled = false;
		areas.disabled = false;
		categorias.disabled = false;

	} else {

		trabajadoresViejos.readOnly = false;
		trabajadoresViejos.disabled = true;
		trabajadoresNuevo.disabled = true;
		trabajadoresNuevo.value = ''
		trabajadoresNuevo.classList.remove('error')
		areas.disabled = true;
		categorias.disabled = true;
	}

}



function activa_categoria(checkbox, campo_categoria) {
	if (checkbox.checked) {
		$('#tiempo_' + campo_categoria).prop('disabled', false);
		$('#frecuencia_' + campo_categoria).prop('disabled', false);

		$('#tiempo_' + campo_categoria).prop('required', true).prop('readonly', true);
		$('#frecuencia_' + campo_categoria).prop('required', true).prop('readonly', true);
	}
	else {

		$('#tiempo_' + campo_categoria).prop('disabled', true).prop('readonly', false);
		$('#frecuencia_' + campo_categoria).prop('disabled', true).prop('readonly', false);
	}
}


// Eliminar fila sustancia
$('#tabla_catsustancias > tbody').on('click', '.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar sustancia?",
		text: "Eliminar sustancia de la lista",
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

			// mensaje
			swal({
				title: "Correcto",
				text: "Sustancia eliminada de la lista",
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

// Eliminar fila sustancia
$('#tabla_grupos > tbody').on('click', '.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar categoría?",
		text: "Eliminar categoría de la lista",
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

			// mensaje
			swal({
				title: "Categoria eliminada de la lista",
				text: "No se olvide de guardar, para permanecer con los cambios.",
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


// consulta sustancias catalogo
function funcion_tabla_catsustancias(recsensorial_id, recsensorialarea_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialquimicoscatsustancias/" + recsensorial_id + "/" + recsensorialarea_id,
		data: {},
		cache: false,
		success: function (dato) {
			select_catsustancia_opciones = dato.catsustancia_opciones;
			select_umedida_opciones = dato.umedida_opciones;
		},
		// beforeSend: function(){
		// 	$("#tabla_catsustancias tbody").html('<tr><td colspan="8" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></td></tr>');
		// },
		error: function (dato) {
			select_catsustancia_opciones = '<option value="">Buscar</option>';
			return false;
		}
	});//Fin ajax
}


// Selecciona AREA SUSTANCIA
$(document).ready(function () {
	$('#tabla_recsensorialquimicos_inventario tbody').on('click', 'td.editar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialquimicos_inventario.row(tr);

		if (parseInt(row.data().accion_activa) > 0) {
			// Borrar formulario
			$('#form_inventariosustancia').each(function () {
				this.reset();
			});

			// llenar campos
			$("#sustanciaarea_id").val(row.data().recsensorialarea_id);
			$("#sustancia_recsensorial_id").val(row.data().recsensorial_id);

			// llenar select areas
			consulta_areas('sustancia_recsensorialarea_id', row.data().recsensorialarea_id, row.data().recsensorial_id, 1);

			// llenar select categorias
			// $("#checkbox_seleccionacategorias").prop('checked', false);
			consulta_select_categoriasxarea(row.data().recsensorialarea_id);

			// Mostrar catalogo sustancias
			funcion_tabla_catsustancias(row.data().recsensorialarea_id, row.data().recsensorialcategoria_id);

			// mostrar modal
			$('#modal_inventariosustancia').modal({ backdrop: false });
		}
	});
});


$("#boton_guardar_sustancia").click(function () {

	var valida = this.form.checkValidity();

	if (valida) {
		// confirmar
		swal({
			title: "¿Guardar lista sustancias?",
			text: "Confirme guardar inventario de sustancias en el Área [" + $("#sustancia_recsensorialarea_id option:selected").text() + "]",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function (isConfirm) {
			if (isConfirm) {
				// cerrar cuadro de dialogo
				swal.close();

				// enviar datos
				$('#form_inventariosustancia').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorialquimicosinventario',
					data: {
						api: 1
					},
					resetForm: false,
					success: function (dato) {
						// Campos Hidden
						$("#sustancia_id").val(0);
						$("#sustancia_recsensorial_id").val(dato.recsensorial_id);

						// actualiza tabla inventario
						tabla_recsensorialquimicos_inventario.ajax.reload();

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
						$('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');

						// cerrar modal
						$('#modal_inventariosustancia').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_sustancia').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');
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


$("#boton_guardar_grupos").click(function (e) {
	e.preventDefault();

	var elementosConError = $('#tabla_grupos .error');
	if (elementosConError.length == 0) {

		var valida = this.form.checkValidity();
		if (valida) {
			// confirmar
			swal({
				title: "¿Guardar Grupo de Exposición Homogénea?",
				text: "Confirme para crear un nuevo grupo de Exposición",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Guardar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function (isConfirm) {
				if (isConfirm) {
					// cerrar cuadro de dialogo
					swal.close();

					// enviar datos
					$('#form_grupos').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/recsensorialquimicosinventario',
						data: {
							api: 2,
						},
						resetForm: false,
						success: function (dato) {
							// Campos Hidden
							$("#ID_GRUPO").val(0);
							$("#GRUPO_RECSENSORIAL_ID").val(dato.recsensorial_id);

							// actualiza tabla GEH
							tabla_quimicosresumen_3.ajax.reload();

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
							$('#boton_guardar_grupos').html('Guardar <i class="fa fa-save"></i>');

							// cerrar modal
							$('#modal_grupos').modal('hide');
						},
						beforeSend: function () {
							$('#boton_guardar_grupos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						},
						error: function (dato) {
							// actualiza boton
							$('#boton_guardar_grupos').html('Guardar <i class="fa fa-save"></i>');
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
		} else {
			swal({
				title: "Faltan datos por rellenar",
				text: "Asegurece de rellenar todos los datos requeridos ",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 2500,
				showConfirmButton: false
			});
		}
	} else {

		// mensaje
		swal({
			title: "Asegurece de que todos los valores esten correctos.",
			text: "Por favor, verifique que el No. de Trabajadores expuestos no sean mayores a los No. de trabajadores del área",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2500,
			showConfirmButton: false
		});

	}
});


// EDITAR GRUPOS DE EXPOSICION
$(document).ready(function () {
	$('#tabla_quimicosresumen_3 tbody').on('click', 'td>button.editar', function () {

		// mensaje
		swal({
			title: "¡Nota importante!",
			text: "Al editar un GEH es necesario confirmar los puntos de muestreo y POE en el Informe. ¡Esto solo aplica si los puntos de muestreo y POE ya fueron confirmados!",
			type: "info", // warning, error, success, info
			buttons: {
				visible: true, // true , false
			},
			showConfirmButton: true
		});



		var tr = $(this).closest('tr');
		var row = tabla_quimicosresumen_3.row(tr);

		// Borrar formulario
		$('#form_grupos').each(function () {
			this.reset();
		});

		// llenar campos
		$("#NUEVO_GRUPO").val(0);
		$("#GRUPO_RECSENSORIAL_ID").val(row.data().RECSENSORIAL_ID);

		$("#CLASIFICACION_GRUPO").val(row.data().CLASIFICACION)
		if (row.data().OPCION == 1) {
			$("#TIPO_CLASIFICACION_ROMANOS").prop('checked', true).prop('disabled', true);;
			$("#TIPO_CLASIFICACION_LETRAS").prop('checked', false).prop('disabled', true);;

		} else {
			$("#TIPO_CLASIFICACION_ROMANOS").prop('checked', false);
			$("#TIPO_CLASIFICACION_LETRAS").prop('checked', true);
		}


		// vaciar lista de  catalogo sustancias
		$("#tabla_grupos> tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');

		//Realizamos la peticion para el esqueleto guardado de de la informacion
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/obtenerCategoriasReconomiento/" + row.data().RECSENSORIAL_ID + "/" + row.data().CLASIFICACION + "/" + row.data().RELACION_HOJA_SUS_ID,
			data: {},
			cache: false,
			success: function (dato) {

				// Creamos lista de categorias
				var filastr_categorias = '';
				$.each(dato.categorias, function (key, value) {

					//VALIDAMOS SI LA CATEGORIA CON AREA ACTUAL ESTA SELECCIONADA
					if (value.SELECCIONADO == 1) {
						filastr_categorias += `<tr>
												<td style="width: 700px!important;">
													<div class="form-group">
														<div class="switch" style="float: left;">
															<label>
																<input type="checkbox" onchange="activarTrabajadores(this)" name="ID_RELACION[]" value="${value.ID_RELACION}" checked>
																<span class="lever switch-col-light-blue"></span>
															</label>
														</div>
														<label class="demo-switch-title" style="float: left; font-size:15px;">[ ${value.AREA} ] ${value.CATEGORIA}</label>
														<input type="hidden" class="form-control" name="ID_AREA_GRUPO[]" value="${value.AREA_ID}" >
														<input type="hidden" class="form-control" name="ID_CATEGORIA_GRUPO[]" value="${value.CATEGORIA_ID}">
													</div>
												</td>
												<td style="width: 200px!important;">
													<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadore" name="NUM_TRABAJADORES_VIEJO[]" value="${value.PERSONAL}" readonly >
												</td>
												<td style="width: 200px!important;">
													<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadores expuestos" onchange="validarTrabajadores(this)" name="NUM_TRABAJADORES_NUEVO[]" value="${value.POE}">
												</td>
											</tr>`

					} else {
						filastr_categorias += `<tr>
												<td style="width: 700px!important;">
													<div class="form-group">
														<div class="switch" style="float: left;">
															<label>
																<input type="checkbox" onchange="activarTrabajadores(this)" name="ID_RELACION[]" value="${value.ID_RELACION}" >
																<span class="lever switch-col-light-blue"></span>
															</label>
														</div>
														<label class="demo-switch-title" style="float: left; font-size:15px;">[ ${value.AREA} ] ${value.CATEGORIA}</label>
														<input type="hidden" class="form-control" name="ID_AREA_GRUPO[]" value="${value.AREA_ID}" disabled>
														<input type="hidden" class="form-control" name="ID_CATEGORIA_GRUPO[]" value="${value.CATEGORIA_ID}" disabled>
													</div>
												</td>
												<td style="width: 200px!important;">
													<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadore" name="NUM_TRABAJADORES_VIEJO[]" value="${value.PERSONAL}"  disabled>
												</td>
												<td style="width: 200px!important;">
													<input type="number" step="any" class="form-control text-center" placeholder="No. Trabajadores expuestos" onchange="validarTrabajadores(this)" name="NUM_TRABAJADORES_NUEVO[]" disabled>
												</td>
											</tr>`
					}

				});



				html = `<tr>
					<td style="width: 100%px!important;">
						<table>
							<tr>
								<td style="width: 720px!important;">
									<label>&nbsp;</label>
									<select class="custom-select form-control" id="COMPONENTE_ID" name="COMPONENTE_ID" required>
										${dato.componentes}
									</select>
								</td>
								<td style="width: 200px!important;">
									<label>&nbsp;</label>
								</td>
								<td style="width: 200px!important;">
									<label>&nbsp;</label>
								</td>
							</tr>
							${filastr_categorias}
						</table>
					</td>
				</tr>`


				$('#tabla_grupos > tbody').html(html);
			},
			beforeSend: function () {
				$("#tabla_grupos > tbody").html('<tr><td colspan="3" style="width: 1143px; text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></td></tr>');
			},
			error: function (dato) {
				$("#tabla_grupos > tbody").html('<tr><td colspan="3" style="width: 1143px; height: 160px;">&nbsp;</td></tr>');
				return false;
			}
		})


		// mostrar modal
		$('#modal_grupos').modal({ backdrop: false });



	});
});


// eliminar AREA SUSTANCIA
$(document).ready(function () {
	$('#tabla_recsensorialquimicos_inventario tbody').on('click', 'td.eliminar', function () {
		var tr = $(this).closest('tr');
		var row = tabla_recsensorialquimicos_inventario.row(tr);

		if (parseInt(row.data().accion_activa) > 0) {
			swal({
				title: "¿Eliminar sustancias del Área?",
				text: row.data().recsensorialarea_nombre,
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
						title: "¡Confirme nuevamente eliminar sustancias del Área!",
						text: row.data().recsensorialarea_nombre,
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
								url: "/recsensorialquimicosinventarioeliminar/" + row.data().recsensorial_id + "/" + row.data().recsensorialarea_id + "/" + row.data().recsensorialcategoria_id,
								data: {},
								cache: false,
								success: function (dato) {

									// actualiza tabla inventario
									tabla_recsensorialquimicos_inventario.ajax.reload();

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
	});
});


// Menu tablas resumenes Quimicos
$('.multisteps-form__progress-btn-3').click(function () {
	switch (this.id) {
		case "steps3_menu_tab2":
			funcion_tabla_quimicosresumen_1($("#recsensorial_id").val(), 1);
			break;
		case "steps3_menu_tab3": //Identificacion de los grupos de exposicion homogenea
			funcion_tabla_quimicosresumen_2($("#recsensorial_id").val(), 2);
			break;
		case "steps3_menu_tab4": //Determinacion de los grupos de exposicion homogenea
			funcion_tabla_quimicosresumen_3($("#recsensorial_id").val(), 3);
			break;
		case "steps3_menu_tab5": // Puntos de muestreo y POE
			funcion_tabla_quimicosresumen_4($("#recsensorial_id").val(), 4);
			break;
		default:
			break;
	}
});


function funcion_tabla_quimicosresumen_1(recsensorial_id, numero_tabla) {
	try {
		var ruta = "/recsensorialquimicosresumen/" + recsensorial_id + "/" + numero_tabla;

		if (tabla_quimicosresumen_1 != null) {
			tabla_quimicosresumen_1.clear().draw();
			tabla_quimicosresumen_1.ajax.url(ruta).load();
		}
		else {
			tabla_quimicosresumen_1 = $('#tabla_quimicosresumen_1').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_quimicosresumen_1(recsensorial_id, numero_tabla);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id"
					// },
					// {
					//     "data": "numero_registro",
					//     "defaultContent": "-"
					// },
					{
						"data": "AREA",
						"defaultContent": "-"
					},
					{
						"data": "PRODUCTO",
						"defaultContent": "-"
					},
					{
						"data": "COMPONENTE",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_CANTIDAD",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_CLASIFICACION",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_VOLATILIDAD",
						"defaultContent": "-"
					},
					{
						"data": "SUMA_PONDERACIONES",
						"defaultContent": "-"
					},
					{
						"data": "PRIORIDAD",
						"defaultContent": "-"
					}
				],
				"order": [[2, "asc"]],
				"rowsGroup": [0, 1], //agrupar filas
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
		funcion_tabla_quimicosresumen_1(recsensorial_id, numero_tabla);
	}
}


function funcion_tabla_quimicosresumen_2(recsensorial_id, numero_tabla) {
	try {
		var ruta = "/recsensorialquimicosresumen/" + recsensorial_id + "/" + numero_tabla;

		if (tabla_quimicosresumen_2 != null) {
			tabla_quimicosresumen_2.clear().draw();
			tabla_quimicosresumen_2.ajax.url(ruta).load();
		}
		else {
			tabla_quimicosresumen_2 = $('#tabla_quimicosresumen_2').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_quimicosresumen_2(recsensorial_id, numero_tabla);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id"
					// },
					// {
					//     "data": "numero_registro",
					//     "defaultContent": "-"
					// },
					{
						"data": "CLASIFICACION",
						"defaultContent": "-"
					},
					{
						"data": "SUSTANCIA_PRODUCTO",
						"defaultContent": "-"
					},
					{
						"data": "AREA",
						"defaultContent": "-"
					},
					{
						"data": "CATEGORIA",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_INGRESO",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_POE",
						"defaultContent": "-"
					},
					{
						"data": "PONDERACION_EXPOSICION",
						"defaultContent": "-"
					},
					{
						"data": "TOTAL",
						"defaultContent": "-"
					},
					{
						"data": "PRIORIDAD",
						"defaultContent": "-"
					},
					{
						"data": "NUM_POE",
						"defaultContent": "-"
					}
				],
				"order": [[0, "asc"]],
				"rowsGroup": [0, 1, 2], //agrupar filas
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
		funcion_tabla_quimicosresumen_2(recsensorial_id, numero_tabla);
	}
}


function funcion_tabla_quimicosresumen_3(recsensorial_id, numero_tabla) {
	try {
		var ruta = "/recsensorialquimicosresumen/" + recsensorial_id + "/" + numero_tabla;

		if (tabla_quimicosresumen_3 != null) {
			tabla_quimicosresumen_3.clear().draw();
			tabla_quimicosresumen_3.ajax.url(ruta).load();
		}
		else {
			tabla_quimicosresumen_3 = $('#tabla_quimicosresumen_3').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						funcion_tabla_quimicosresumen_3(recsensorial_id, numero_tabla);
					},
					"data": {}
				},
				"columns": [
					// {
					//     "data": "id"
					// },
					{
						"data": "boton_editar",
						"defaultContent": "-"
					},
					{
						"data": "CLASIFICACION",
						"defaultContent": "-"
					},
					{
						"data": "SUSTANCIA_PRODUCTO",
						"defaultContent": "-"
					},
					{
						"data": "AREA",
						"defaultContent": "-"
					},
					{
						"data": "CATEGORIA",
						"defaultContent": "-"
					},
					{
						"data": "POE",
						"defaultContent": "-"
					},
					{
						"data": "TIEMPO_EXPO",
						"defaultContent": "-"
					},
					{
						"data": "FRECUENCIA",
						"defaultContent": "-"
					},
					{
						"data": "TIEMPO_TOTAL",
						"defaultContent": "-"
					},
					{
						"data": "JORNADA_TRABAJO",
						"defaultContent": "-"
					},
					// {
					//     "data": "NUM_POE",
					//     "defaultContent": "-"
					// }
				],
				"order": [[1, "asc"]],
				"rowsGroup": [0, 1, 2, 3], //agrupar filas
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
		funcion_tabla_quimicosresumen_3(recsensorial_id, numero_tabla);
	}
}


function funcion_tabla_quimicosresumen_4(recsensorial_id, numero_tabla) {
	try {
		var ruta = "/recsensorialquimicosresumen/" + recsensorial_id + "/" + numero_tabla;

		if (tabla_quimicosresumen_4 != null) {
			tabla_quimicosresumen_4.clear().draw();
			tabla_quimicosresumen_4.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			tabla_quimicosresumen_4 = $('#tabla_quimicosresumen_4').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en tabla_quimicosresumen_4');
						if (numeroejecucion <= 1) {
							funcion_tabla_quimicosresumen_4(recsensorial_id, numero_tabla);
							numeroejecucion += 1;
						}
					},
					"data": {}
				},
				"columns": [
					{
						"data": "CLASIFICACION"
					},
					{
						"data": "PRODUCTO_COMPONENTE",
						"defaultContent": "-"
					},
					{
						"data": "CATEGORIA",
						"defaultContent": "-"
					},
					{
						"data": "MUESTREO_PPT",
						"defaultContent": "-"
					},
					{
						"data": "MUESTREO_CT",
						"defaultContent": "-"
					},
					{
						"data": "TOTAL_MUESTREOS",
						"defaultContent": "-"
					}
				],
				"order": [[0, "asc"]],
				"rowsGroup": [0, 1], //agrupar filas
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"ordering": false,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
		funcion_tabla_quimicosresumen_4(recsensorial_id, numero_tabla);
	}
}


//------------------------------------------------------------



var foto_resizebase64 = "";


// Load pagina
$(document).ready(function () {
	// inicializar campo FOTO mapa ubicacion
	$('#inputevidenciafotoquimicos').dropify({
		messages: {
			'default': 'Arrastre la imagen aquí o haga click',
			'replace': 'Arrastre la imagen o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error: {
			'fileSize': 'Demasiado grande (5MB max).',
			'minWidth': 'Ancho demasiado pequeño (min 100px).',
			'maxWidth': 'Ancho demasiado grande (max 1200px).',
			'minHeight': 'Alto demasiado pequeño (min 400px).',
			'maxHeight': 'Alto demasiado grande (max 800px max).',
			'imageFormat': 'Formato no permitido, sólo (.JPG y .PNG).'
		}
	});
});


$("#boton_nueva_fotoevidenciaquimicos").click(function () {
	// Borrar formulario
	$('#form_evidencia_fotosquimicos').each(function () {
		this.reset();
	});

	// Resetear input FOTO
	$('#inputevidenciafotoquimicos').val('');
	$('#inputevidenciafotoquimicos').dropify().data('dropify').resetPreview();
	$('#inputevidenciafotoquimicos').dropify().data('dropify').clearElement();

	$("#recsensorialevidenciasquimicos_recsensorialarea_id").val('');
	$("#recsensorialevidenciasquimicos_recsensorialarea_id").attr('disabled', true)
	$("#recsensorialevidenciasquimicos_descripcion").val('');
	$("#recsensorialevidenciasquimicos_descripcion").attr('disabled', true)

	// Inicializar variable FOTO
	foto_resizebase64 = "";

	// mostrar modal
	$('#modal_evidencia_fotosquimicos').modal({ backdrop: false });
});


function redimencionar_fotoevidenciaquimicos() {
	// Mostrar mensaje de espera
	$('#mensaje_cargando_fotos').css('display', 'block');
	$('#boton_guardar_evidencia_fotosquimicos').attr('disabled', true);

	foto_resizebase64 = "";
	var filesToUpload = document.getElementById('inputevidenciafotoquimicos').files;
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
			foto_resizebase64 = dataurl; //Guardar en una variable

			// Quitar mensaje de espera
			$('#mensaje_cargando_fotos').css('display', 'none');
			$('#boton_guardar_evidencia_fotosquimicos').attr('disabled', false);
		}, 100);
	}
}


function descripcion_fotoquimicos() {
	// alert(parseInt($("#recsensorialevidenciasquimicos_tipo").val()));

	if (parseInt($("#recsensorialevidenciasquimicos_tipo").val()) > 0) {
		if (parseInt($("#recsensorialevidenciasquimicos_tipo").val()) == 1) {
			$("#recsensorialevidenciasquimicos_descripcion").attr('disabled', false);
			$("#recsensorialevidenciasquimicos_recsensorialarea_id").attr('disabled', false);

			if (parseInt($("#recsensorialevidenciasquimicos_recsensorialarea_id").val()) > 0) {
				$("#recsensorialevidenciasquimicos_descripcion").val('Evidencia de reconocimiento de agentes químicos en ' + $("#recsensorialevidenciasquimicos_recsensorialarea_id option:selected").text());
			}
			else {
				$("#recsensorialevidenciasquimicos_descripcion").val('Evidencia de reconocimiento de agentes químicos');
			}
		}
		else {
			$("#recsensorialevidenciasquimicos_recsensorialarea_id").val('');
			$("#recsensorialevidenciasquimicos_recsensorialarea_id").attr('disabled', true);

			$("#recsensorialevidenciasquimicos_descripcion").val('Plano de fuentes generadoras de agentes químicos');
			$("#recsensorialevidenciasquimicos_descripcion").attr('disabled', false);
		}
	}
	else {
		$("#recsensorialevidenciasquimicos_recsensorialarea_id").val('');
		$("#recsensorialevidenciasquimicos_recsensorialarea_id").attr('disabled', true);
		$("#recsensorialevidenciasquimicos_descripcion").val('');
		$("#recsensorialevidenciasquimicos_descripcion").attr('disabled', true);
	}
}


$("#boton_guardar_evidencia_fotosquimicos").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme guardar " + $("#recsensorialevidenciasquimicos_tipo option:selected").text() + "!",
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
				$('#form_evidencia_fotosquimicos').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorial',
					data: {
						opcion: 4, //FOTOS
						recsensorial_id: $("#recsensorial_id").val(),
						parametro_id: 15,
						parametro_nombre: 'químicos',
						foto_base64: foto_resizebase64,
					},
					resetForm: false,
					success: function (dato) {
						// Actualiza galeria de fotos
						consulta_evidencia_fotosquimicos($("#recsensorial_id").val(), 15);

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
						$('#boton_guardar_evidencia_fotosquimicos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_fotosquimicos').attr('disabled', false);

						// cerrar modal
						$('#modal_evidencia_fotosquimicos').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardar_evidencia_fotosquimicos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_fotosquimicos').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_evidencia_fotosquimicos').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_evidencia_fotosquimicos').attr('disabled', false);

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


function consulta_evidencia_fotosquimicos(recsensorial, parametro_id) {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialevidenciagaleria/" + recsensorial + "/" + parametro_id,
		data: {},
		cache: false,
		success: function (dato) {
			// Vaciar contenido
			$('.galeriaquimicos').html('');

			// validar si trae datos
			if (dato.galeria) {
				// GALERIA DE FOTOS
				$(".galeriaquimicos").html(dato.galeria);
			}
			else {
				$('.galeriaquimicos').html('<div class="col-12" style="text-align: center;">No hay fotos que mostrar</div>');
			}

			// Inicializar tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function () {
			$('.galeriaquimicos').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
		},
		error: function (dato) {
			$('.galeriaquimicos').html('<div class="col-12" style="text-align: center;">Error al cargar las fotos</div>');
			return false;
		}
	});//Fin ajax
}


function foto_descargar(foto_id) {
	window.open("/recsensorialevidenciafotomostrar/" + foto_id + "/1");
}


function fotoquimicos_eliminar(foto_id, tipo_nombre) {
	// Valida envio de datos
	swal({
		title: "¡Confirme eliminar " + tipo_nombre + "!",
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
			// Enviar datos
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/recsensorialevidenciafotoeliminar/" + foto_id,
				data: {},
				cache: false,
				success: function (dato) {
					// Actualiza galeria de fotos
					consulta_evidencia_fotosquimicos($("#recsensorial_id").val(), 15);

					//Cerrar imagen popo
					// $(".mfp-figure").click();

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
						timer: 3000,
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



//AGREGAR TURNO

document.addEventListener("DOMContentLoaded", function () {
	const botonAgregar = document.getElementById('botonagregarhorario');
	botonAgregar.addEventListener('click', agregarhorario);

	function agregarhorario() {

		var errorEnTurnos = false; // Variable para controlar si hay error en los turnos

		$(".generarturnos").each(function () {
			var horasJornada = parseInt($(this).find("input[name='recsensorialcategoria_horasjornada']").val()) || 0;
			var horasComida = parseInt($(this).find("input[name='recsensorialcategoria_horascomida']").val()) || 0;

			var horaEntrada = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horarioentrada']").val() + ":00");
			var horaSalida = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horariosalida']").val() + ":00");

			var horasTrabajadas = (horaSalida - horaEntrada) / 1000 / 60 / 60;

			if (horasJornada + horasComida !== horasTrabajadas) {
				errorEnTurnos = true;
			}
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
		else {

			const divhorarios = document.createElement('div');
			divhorarios.classList.add('row', 'generarturnos', 'm-2');
			divhorarios.innerHTML = `
            <div class="col-lg-12 col-sm-1">
                <div class="form-group">
                    <h3><i class="fa  fa-clock-o"></i> Agregar turno</h3>                 
                </div>

                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas jornada *</label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horasjornada" id="recsensorialcategoria_horasjornada" required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario entrada *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horarioentrada" id="recsensorialcategoria_horarioentrada" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario salida *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horariosalida" id="recsensorialcategoria_horariosalida" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas de comida </label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horascomida" id="recsensorialcategoria_horascomida" value=0>
                        </div>
                    </div>

					<div class="col-12">
					  <div class="form-group">
						<input type="text" class="form-control" name="descripcioncategoria" id="descripcioncategoria" placeholder="Nota del turno..." >
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

			const botonEliminar = divhorarios.querySelector('.botonEliminarhorarioturno');
			botonEliminar.addEventListener('click', function () {
				contenedor.removeChild(divhorarios);
			});
		}

	}
});



$(document).ready(function () {
	$('#modal_area').on('shown.bs.modal', function () {
		$('#recsensorialarea_generacioncontaminante').selectize({
			delimiter: ",",
			persist: false,
			maxItems: null,
			create: false, // Desactivar la creación de nuevas opciones
		});

		// Obtener las opciones seleccionadas del campo y establecerlas en Selectize
		var opcionesSeleccionadas = $('#recsensorialarea_generacioncontaminante').val();
		var selectize = $('#recsensorialarea_generacioncontaminante')[0].selectize;
		selectize.setValue(opcionesSeleccionadas);
	});

	// Restablecer Selectize cuando se cierra el modal
	$('#modal_area').on('hidden.bs.modal', function () {
		var selectize = $('#recsensorialarea_generacioncontaminante')[0].selectize;
		selectize.destroy();
	});
});





$(document).ready(function () {
	// Función para establecer estilos de las opciones seleccionadas
	function setOptionStyles() {
		$('#JERARQUIACONTROL option').each(function () {
			var value = $(this).val();
			var color = '';
			// Asignar color de fondo y color de texto según el valor de la opción
			switch (value) {
				case '1':
					color = '#5285B3';
					break;
				case '2':
					color = '#85C24B';
					break;
				case '3':
					color = '#FDD10B';
					break;
				case '4':
					color = '#EF6524';
					break;
				case '5':
					color = '#EC2029';
					break;
				case '0':
					color = 'gray';
					break;
				case '6':
					color = 'rgba(0, 0, 0, 0.315)';
					break;
				default:
					color = '#ffffff';
					break;
			}
			// Establecer estilos en la opción
			$(this).css('background-color', color);
			$(this).css('color', (color === 'gray') ? 'rgb(0, 0, 0)' : 'white');
		});
	}

	// Función para actualizar el color del select
	function updateSelectColor() {
		var selectedOption = $('#JERARQUIACONTROL').find('option:selected');
		var color = selectedOption.css('background-color');
		$('#JERARQUIACONTROL').css('background-color', color);
	}

	// Llamar a la función para establecer estilos al cargar la página
	setOptionStyles();

	// Al seleccionar una opción, cambiar el estilo del select
	$('#JERARQUIACONTROL').change(function () {
		setOptionStyles(); // Llamar a la función para actualizar estilos
		updateSelectColor(); // Actualizar color del select
	});
});



function opcionesAnexoContrato(id_contrato) {
	// activar campo

	$('#contrato_anexo_id').html('');
	$('#contrato_anexo_id').val('');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/getContratosAnexos/" + id_contrato,
		data: {},
		cache: false,
		success: function (dato) {

			$('#contrato_anexo_id').html(dato.opciones);


		},
		beforeSend: function () {
			$('#contrato_anexo_id').html('<option value="">Consultando...</option>');
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}


$('#contrato_anexo_id').on('change', function () {

	var valorSeleccionado = $(this).find("option:selected");
	var val = valorSeleccionado.data("tipo");

	if (val == 'ARCHIVO') {

		$('#anexoArchivo').css('display', 'block')
		$('#anexo_archivo').prop('required', true);

		$('#anexoImagen').css('display', 'none')
		$('#anexo_imagen').prop('required', false);

		$('#anexoAcreditaciones').css('display', 'none')
		$('#acreditacion_id').prop('required', false);
		$('#proveedor_id').prop('required', false);

	} else if (val == 'IMAGEN') {

		$('#anexoImagen').css('display', 'block')
		$('#anexo_imagen').prop('required', true);

		$('#anexoArchivo').css('display', 'none')
		$('#anexo_archivo').prop('required', false);

		$('#anexoAcreditaciones').css('display', 'none')
		$('#acreditacion_id').prop('required', false);
		$('#proveedor_id').prop('required', false);

	} else if (val == 'ACREDITACION') {

		$('#anexoAcreditaciones').css('display', 'block')
		$('#acreditacion_id').prop('required', true);
		$('#proveedor_id').prop('required', true);

		$('#anexoImagen').css('display', 'none')
		$('#anexo_imagen').prop('required', false);

		$('#anexoArchivo').css('display', 'none')
		$('#anexo_archivo').prop('required', false);


	}


})

//Cada vez que se habra el modal de anexo recuperamos los anexos faltantes
$('#modal_anexo').on('shown.bs.modal', function () {
	opcionesAnexoContrato($('#contrato_id').val());
})

// SELECCIONAR DATOS PARA EL AREA SI ES  QUIMICO O FISICO 
document.getElementById('RECSENSORIAL_DATOSAREA').addEventListener('change', function () {
	var selectedOption = this.options[this.selectedIndex].text;
	document.getElementById('selectedArea').textContent = selectedOption;
});



function validatipoalcance(valor, camposelect, opcion_seleccionado) {
	// Vaciar campos
	$('#prueba_id').html('<option value=""></option>');
	$('#acreditacionAlcance_agentetipo').html('<option value=""></option>');
	$('#acreditacionAlcance_agente').val('');
	$('#acreditacionAlcance_Norma').val('');
	$('#acreditacionAlcance_Procedimiento').val('');

	switch (valor) {
		case "":
			$("#prueba_id").val('');
			$("#prueba_id").attr('required', true);
			break;
		case "Químico":
			// Ocultar campo
			$("#div_campo_factor").css('display', 'none');
			$("#prueba_id").val('');
			$("#prueba_id").attr('required', false);

			// Mostrar campo
			$("#div_campo_agente").css('display', 'block');
			$("#acreditacionAlcance_agente").selectize();
			$("#acreditacionAlcance_agente").val('');
			$("#acreditacionAlcance_agente").prop('required', true);

			// NO requerir Campo select agentetipo
			$('#acreditacionAlcance_agentetipo').prop('required', false);
			$('#acreditacionAlcance_agentetipo').prop('disabled', true);

			break;
		default:
			// Ocultar campo
			$("#prueba_id").val('');
			$("#prueba_id").attr('required', true);

			// Actualiza campo select
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/acreditacionalcancetipoagente/" + valor + "/" + opcion_seleccionado,
				data: {},
				cache: false,
				success: function (dato) {
					$('#prueba_id').html(dato.opciones);
				},
				error: function (dato) {
					$('#prueba_id').html('<option value=""></option>');
					return false;
				}
			});

			break;
	}
}



$(document).ready(function () {
	$('#boton_agregar_alcance').click(function () {
		var divTipoAlcance = $('<div>').addClass('row generarTipoAlcance');
		divTipoAlcance.html(`
            <div class="col-4">
                <div class="form-group">
                    <label>Tipo alcance *</label>
                    <select class="custom-select form-control AreaTipoAfecta" name="AreaTipoAfecta[]" required>
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="col-4" id="div_campo_factor">
                <div class="form-group">
                    <label>Agente / Factor de riesgo / Servicio *</label>
                    <select class="custom-select form-control AgenteFactor" name="AgenteFactor[]" required>
                        <option value=""></option>
                    </select>
                </div>
            </div>
			<div class="col-3">
				<div class="form-group">
					<label> Tipo *</label>
					<select class="custom-select form-control AgenteTipo" name="TipoAgente[]">
						<option value=""></option>
					</select>
				</div>
			</div>
            <div class="col-1 mt-4">
                <button type="button" class="btn btn-danger btn-circle botonEliminarTipoAlcance"> <i class="fa fa-trash"></i></button>
            </div>
        `);
		$('.listaAreasAfectadas').append(divTipoAlcance);

		$('.botonEliminarTipoAlcance', divTipoAlcance).click(function () {
			$(this).closest('.generarTipoAlcance').remove();
		});

		// Obtener los selects que acabamos de crear
		var selectTipoAlcance = $('.AreaTipoAfecta', divTipoAlcance);
		$.each(lista_alcances, function (index, opcion) {
			var option = $('<option>').text(opcion.catPrueba_Tipo).val(opcion.catPrueba_Tipo);;
			selectTipoAlcance.append(option);
		});
	});

	$(document).on('change', '.AreaTipoAfecta', function () {
		var selectPruebaID = $(this).closest('.row').find('.AgenteFactor');
		var selectPruebaTipo = $(this).closest('.row').find('.AgenteTipo');

		MostratTiposAlcances($(this).val(), selectPruebaID, selectPruebaTipo, 0, 0);
	});


	$(document).on('change', '.AgenteFactor', function () {
		var selectPruebaTipo = $(this).closest('.row').find('.AgenteTipo');
		var valor = parseInt($(this).val(), 10);
		MostratTiposAgentes(valor, selectPruebaTipo);
	});
});


function MostratTiposAgentes(valor, selectPruebaTipo) {

	selectPruebaTipo.html('<option value=""></option>');

	switch (valor) {
		case 9: //AGUA
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
			break;
		case 10: //HIELO
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
			break;
		case 11: //ALIMENTO
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Crudos">Crudos</option><option value="Cocidos">Cocidos</option>');
			break;
		case 12: //SUPERFICIES
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Vivas">Vivas</option><option value="Inertes">Inertes</option>');
			break;
		case 17: //Mapa de riesgos
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Tipo 1">Tipo 1</option><option value="Tipo 2">Tipo 2</option>');
			break;
		case 5: //Radiaciones Ionizantes
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Rayos X">Rayos X</option><option value="Rayos gamma">Rayos gamma</option>');

			break;
		case 6: // Radiaciones No Ionizantes
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Radiofrecuencias">Radiofrecuencias</option><option value="Microondas">Microondas</option><option value="Espectro visible">Espectro visible</option><option value="Ultravioleta (UV)">Ultravioleta (UV)</option>');

			break;
		case 3: //Temperatura
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Elevadas">Elevadas</option><option value="Abatidas">Abatidas</option>');
			break;
		//Para la parte de quimicos
		case 1000: //Solidos
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Polvo">Polvo</option><option value="Humo">Humo</option><option value="Fibra">Fibra</option>');
			break;
		case 2000: //Liquidos
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Rocío">Rocío</option><option value="Neblina">Neblina</option>');
			break;
		case 3000: //Gaseosos
			// activar campo
			selectPruebaTipo.prop('disabled', false);
			selectPruebaTipo.prop('required', true);
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value=""></option><option value="Vapor">Vapor</option><option value="Gas">Gas</option>');
			break;

		default:
			// llenar select tipo de alcance agente
			selectPruebaTipo.html('<option value="" disabled selected>No hay opciones disponibles</option>');
			// DESACTIVAR campo
			selectPruebaTipo.prop('required', false);
			selectPruebaTipo.prop('disabled', false);
			break;
	}
}

function MostratTiposAlcances(valor, selectPruebaID, selectPruebaTipo, opcion_seleccionado, tipo) {
	// Vaciar campos
	selectPruebaID.html('<option value=""></option>');
	selectPruebaTipo.html('<option value=""></option>');

	switch (valor) {
		case "":
			selectPruebaID.val('');
			selectPruebaID.prop('required', true);
			break;
		case "Químico":

			selectPruebaID.val('');
			selectPruebaID.prop('required', true);

			if (opcion_seleccionado != 0) {

				selectPruebaID.html('<option value=""></option><option value="1000">Sólidos</option><option value="2000">Líquidos</option><option value="3000">Gases</option>');
				selectPruebaID.val(opcion_seleccionado);
				MostratTiposAgentes(opcion_seleccionado, selectPruebaTipo)
				selectPruebaTipo.val(tipo)

			} else {

				selectPruebaID.html('<option value=""></option><option value="1000">Sólidos</option><option value="2000">Líquidos</option><option value="3000">Gases</option>');
			}

			break;
		default:
			// Ocultar campo
			selectPruebaID.val('');
			selectPruebaID.prop('required', true);

			// Actualizar campo select
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/acreditacionalcancetipoagente/" + valor + "/" + opcion_seleccionado,
				data: {},
				cache: false,
				success: function (dato) {
					selectPruebaID.html(dato.opciones);


					if (opcion_seleccionado != 0) {
						MostratTiposAgentes(opcion_seleccionado, selectPruebaTipo)
						selectPruebaTipo.val(tipo)

					}
				},
				error: function (dato) {
					selectPruebaID.html('<option value=""></option>');
					return false;
				}
			});
			break;
	}
}



$('#boton_mapa_maquina').on('click', function (e) {
	e.preventDefault();

	//Variables para el mapa de fuentes generadoras
	const canvas = document.getElementById('canvas');
	const ctx = canvas.getContext('2d');
	let image = new Image();
	let shapes = [];
	let selectedShape = null;
	let isDragging = false;
	let isResizing = false;
	let resizeDirection = null;
	let offsetX, offsetY;
	const resizeHandleSize = 8;
	let imagePath = '';

	document.getElementById('addRectangle').addEventListener('click', () => addShape('rectangle'));
	// document.getElementById('addCircle').addEventListener('click', () => addShape('circle'));
	document.getElementById('deleteShape').addEventListener('click', deleteSelectedShape);
	document.getElementById('download').addEventListener('click', downloadImage);
	canvas.addEventListener('mousedown', handleMouseDown);
	canvas.addEventListener('mousemove', handleMouseMove);
	canvas.addEventListener('mouseup', handleMouseUp);

	function drawCanvas() {
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		ctx.drawImage(image, 0, 0);
		shapes.forEach(shape => drawShape(shape));
	}

	function addShape(type) {
		const color = document.getElementById('color').value;
		const borderWidth = document.getElementById('borderWidth').value;
		const shape = {
			type: type,
			x: 50,
			y: 50,
			width: 100,
			height: 100,
			color: color,
			borderWidth: borderWidth
		};
		shapes.push(shape);
		drawCanvas();
	}

	function drawShape(shape) {
		ctx.strokeStyle = shape.color;
		ctx.lineWidth = shape.borderWidth;
		if (shape.type === 'rectangle') {
			ctx.strokeRect(shape.x, shape.y, shape.width, shape.height);
		} else if (shape.type === 'circle') {
			ctx.beginPath();
			ctx.arc(shape.x, shape.y, shape.width / 2, 0, 2 * Math.PI);
			ctx.stroke();
		}
		if (shape === selectedShape) {
			drawResizeHandle(shape);
		}
	}

	function drawResizeHandle(shape) {
		ctx.fillStyle = 'blue';
		ctx.fillRect(shape.x + shape.width - resizeHandleSize / 2, shape.y + shape.height - resizeHandleSize / 2, resizeHandleSize, resizeHandleSize);
	}

	function handleMouseDown(event) {
		const { x, y } = getMousePos(event);
		const handle = getResizeHandleAt(x, y);
		if (handle) {
			isResizing = true;
			resizeDirection = handle.direction;
			selectedShape = handle.shape;
			return;
		}
		selectedShape = getShapeAt(x, y);
		if (selectedShape) {
			isDragging = true;
			offsetX = x - selectedShape.x;
			offsetY = y - selectedShape.y;
		}
	}

	function handleMouseMove(event) {
		const { x, y } = getMousePos(event);
		if (isDragging) {
			selectedShape.x = x - offsetX;
			selectedShape.y = y - offsetY;
			drawCanvas();
		} else if (isResizing) {
			if (resizeDirection === 'corner') {
				selectedShape.width = Math.max(10, x - selectedShape.x);
				selectedShape.height = Math.max(10, y - selectedShape.y);
			}
			drawCanvas();
		} else {
			const handle = getResizeHandleAt(x, y);
			canvas.style.cursor = handle ? 'nwse-resize' : 'pointer';
		}
	}

	function handleMouseUp() {
		isDragging = false;
		isResizing = false;
	}

	function getMousePos(event) {
		const rect = canvas.getBoundingClientRect();
		return {
			x: event.clientX - rect.left,
			y: event.clientY - rect.top
		};
	}

	function getShapeAt(x, y) {
		for (let i = shapes.length - 1; i >= 0; i--) {
			if (isInsideShape(shapes[i], x, y)) {
				return shapes[i];
			}
		}
		return null;
	}

	function isInsideShape(shape, x, y) {
		if (shape.type === 'rectangle') {
			return x > shape.x && x < shape.x + shape.width && y > shape.y && y < shape.y + shape.height;
		} else if (shape.type === 'circle') {
			const dx = x - shape.x;
			const dy = y - shape.y;
			return Math.sqrt(dx * dx + dy * dy) < shape.width / 2;
		}
		return false;
	}

	function getResizeHandleAt(x, y) {
		for (let i = shapes.length - 1; i >= 0; i--) {
			const shape = shapes[i];
			const handleX = shape.x + shape.width;
			const handleY = shape.y + shape.height;
			if (x > handleX - resizeHandleSize / 2 && x < handleX + resizeHandleSize / 2 &&
				y > handleY - resizeHandleSize / 2 && y < handleY + resizeHandleSize / 2) {
				return { shape: shape, direction: 'corner' };
			}
		}
		return null;
	}

	function deleteSelectedShape() {
		if (selectedShape) {
			shapes = shapes.filter(shape => shape !== selectedShape);
			selectedShape = null;
			drawCanvas();
		}
	}

	function downloadImage() {
		const link = document.createElement('a');
		link.download = 'imagen_editada.png';
		link.href = canvas.toDataURL();
		link.click();
	}


	image.src = rutaMapa;
	image.onload = function () {
		canvas.width = image.width;
		canvas.height = image.height;
		drawCanvas();
	}

	$('#modal_mapa').modal({ backdrop: false });


});

function formatearFechas(fecha1, fecha2) {
	const meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];

	// Convertir las cadenas de texto a objetos de fecha usando UTC
	const date1 = new Date(Date.parse(fecha1 + 'T00:00:00Z'));
	const date2 = new Date(Date.parse(fecha2 + 'T00:00:00Z'));

	// Obtener el día, mes y año de cada fecha
	const dia1 = date1.getUTCDate();
	const mes1 = meses[date1.getUTCMonth()];
	const año1 = date1.getUTCFullYear();

	const dia2 = date2.getUTCDate();
	const mes2 = meses[date2.getUTCMonth()];
	const año2 = date2.getUTCFullYear();

	// Casos específicos
	if (año1 === año2) {
		if (mes1 === mes2) {
			// Caso 1: Mismo mes y año
			return `${dia1.toString().padStart(2, '0')} al ${dia2.toString().padStart(2, '0')} de ${mes1} del ${año1}`;
		} else {
			// Caso 2: Mismo año, mes diferente
			return `${dia1.toString().padStart(2, '0')} de ${mes1} al ${dia2.toString().padStart(2, '0')} de ${mes2} del ${año1}`;
		}
	} else {
		// Caso 3: Año diferente
		return `${dia1.toString().padStart(2, '0')} de ${mes1} del ${año1} al ${dia2.toString().padStart(2, '0')} de ${mes2} del ${año2}`;
	}
}


//CONFIGURACIONES DEL MODAL PARA LA EDICION DE LOS DATOS
$('#boton_editarInforme').on('click', function (e) {

	e.preventDefault();
	instalacion = $('#recsensorial_instalacion').val()
	fecha = formatearFechas($('#recsensorial_fechainicio').val(), $('#recsensorial_fechafin').val());
	select = "";

	$('#RECSENSORIAL_ID_INFORME').val($('#recsensorial_id').val())

	// Borrar formulario
	$('#form_recInforme').each(function () {
		this.reset();
	});


	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerDatosInformes/" + $('#recsensorial_id').val(),
		data: {},
		cache: false,
		success: function (dato) {

			swal.close()


			folio = $("#recsensorial_folioquimico").val()
			razon_social = $("#recsensorial_empresa").val()
			region = $("#catregion_id option:selected").text()
			subdireccion = $("#catsubdireccion_id option:selected").text()
			gerencia = $("#catgerencia_id option:selected").text()
			activo = $("#catactivo_id option:selected").text()




			$("#NIVEL1").html(dato.opciones)
			$("#NIVEL2").html(dato.opciones)
			$("#NIVEL3").html(dato.opciones)
			$("#NIVEL4").html(dato.opciones)
			$("#NIVEL5").html(dato.opciones)


			$("#OPCION_PORTADA1").html(dato.checks)
			$("#OPCION_PORTADA2").html(dato.checks)
			$("#OPCION_PORTADA3").html(dato.checks)
			$("#OPCION_PORTADA4").html(dato.checks)
			$("#OPCION_PORTADA5").html(dato.checks)
			$("#OPCION_PORTADA6").html(dato.checks)


			//DOCUMENTO SIN EDITAR ES PRIMERA VEZ QUE SE VA A DESCARGA
			if (dato.data == 'No se encontraron datos') {

				$('#PORTADA').dropify({
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

				$('#PORTADA').val('');
				$('#PORTADA').dropify().data('dropify').resetPreview();
				$('#PORTADA').dropify().data('dropify').clearElement();



				$('#INTRODUCCION').val(`La prioridad de una industria, que derivado de sus procesos maneje, transporte o almacene alguna sustancia química, es mantener la máxima seguridad sobre el personal, medio ambiente y procesos, esto con el fin de generar un mejor entorno de trabajo libre de accidentes y enfermedades laborales, además de evitar retrasos y pérdidas en la producción de la empresa.
				\nLa clasificación cualitativa y el conocimiento asociado a los riesgos producidos por el manejo, transporte o almacenamiento de sustancias químicas, no tiene trascendencia si no se difunde hacia los trabajadores expuestos; la difusión de los riesgos y la capacitación para sensibilizar al personal sobre la importancia de tomar las  medidas de seguridad para el manejo de sustancias químicas y usar el equipo de protección personal adecuado, son parte fundamental para la administración y control de los riesgos.
				\nPara el control efectivo de los riesgos producidos por el manejo, transporte o almacenamiento de sustancias químicas se necesita realizar un reconocimiento de los agentes químicos y la identificación de los grupos de exposición homogénea, estos son la parte medular de todo estudio de higiene para agentes químicos contaminantes del ambiente laboral de acuerdo con lo establecido en el capítulo 8 y 9 de la NOM-010-STPS-2014. A continuación, se presenta el informe de reconocimiento para la identificación de las sustancias o mezcla químicas utilizada en ${instalacion}, realizado los días ${fecha}.`);


				$('#METODOLOGIA').val(`El reconocimiento de las sustancias y mezclas químicas, así como la identificación de los grupos de exposición homogénea y fuentes generadoras se realiza de acuerdo con lo establecido en el capítulo 8 y 9 de la NOM-010-STPS-2014.`);


				// $('#CONCLUSION').val(`Agregar la conclusion.`);

				$('#PETICION_CLIENTE').prop('checked', false);
				$('#REQUIERE_CONCLUSION').prop('checked', false);

				$('#boton_descargarquimicosdoc').css('display', 'none')
				$('#boton_descargarquimicosdoc').removeClass('desbloqueado').addClass('bloqueado')

				$('#boton_descargarquimicosdoc_final').css('display', 'none')
				$('#boton_descargarquimicosdoc_final').removeClass('desbloqueado').addClass('bloqueado')


			} else { //DOCUMENTO YA EDITADO

				$('#boton_descargarquimicosdoc').removeClass('bloqueado').addClass('desbloqueado')
				$('#boton_descargarquimicosdoc').css('display', 'block')

				$('#boton_descargarquimicosdoc_final').removeClass('bloqueado').addClass('desbloqueado')
				$('#boton_descargarquimicosdoc_final').css('display', 'block')

				// obtener extencion archivo
				var archivo = dato.data[0].IMAGEN_PORTADA;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				// Obtener FOTO
				var imagenUrl = '/informePortada/' + dato.data[0].ID_RECURSO_INFORME + extension;

				// Mostrar Foto en el INPUT
				if ($('#PORTADA').data('dropify')) {
					$('#PORTADA').dropify().data('dropify').destroy();
					// $('.dropify-wrapper').css('height', 400);
					$('#PORTADA').dropify().data('dropify').settings.defaultFile = imagenUrl;
					$('#PORTADA').dropify().data('dropify').init();

				} else {
					// $('#plantillalogoizquierdo').attr('data-height', 400);
					$('#PORTADA').attr('data-default-file', imagenUrl);
					$('#PORTADA').dropify({
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

				$('#PORTADA').attr('required', false);


				$('#ID_RECURSO_INFORME').val(dato.data[0].ID_RECURSO_INFORME);
				$('#INTRODUCCION').val(dato.data[0].INTRODUCCION);
				$('#METODOLOGIA').val(dato.data[0].METODOLOGIA);
				$('#CONCLUSION').val(dato.data[0].CONCLUSION);
				$('#ID_CATCONCLUSION').val(dato.data[0].ID_CATCONCLUSION);



				if (dato.data[0].PETICION_CLIENTE == 1) {
					$('#PETICION_CLIENTE').prop('checked', true).trigger('change');
				} else {
					$('#PETICION_CLIENTE').prop('checked', false).trigger('change');

				}

				if (dato.data[0].REQUIERE_CONCLUSION == 1) {
					$('#REQUIERE_CONCLUSION').prop('checked', true);
				} else {
					$('#REQUIERE_CONCLUSION').prop('checked', false);

				}

				$("#NIVEL1").val(dato.data[0].NIVEL1)
				$("#NIVEL2").val(dato.data[0].NIVEL2)
				$("#NIVEL3").val(dato.data[0].NIVEL3)
				$("#NIVEL4").val(dato.data[0].NIVEL4)
				$("#NIVEL5").val(dato.data[0].NIVEL5)


				$("#OPCION_PORTADA1").val(dato.data[0].OPCION_PORTADA1)
				$("#OPCION_PORTADA2").val(dato.data[0].OPCION_PORTADA2)
				$("#OPCION_PORTADA3").val(dato.data[0].OPCION_PORTADA3)
				$("#OPCION_PORTADA4").val(dato.data[0].OPCION_PORTADA4)
				$("#OPCION_PORTADA5").val(dato.data[0].OPCION_PORTADA5)
			} $("#OPCION_PORTADA6").val(dato.data[0].OPCION_PORTADA6)


			$("#tab1_informe_info").click();
			$('#modal_datosInforme').modal({ backdrop: false });


			// mostrar modal
		},
		beforeSend: function () {

			swal({
				title: "Espere un momento...",
				text: "Estamos obteniendo los datos del Informe",
				type: "info", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				showConfirmButton: false
			});

		},
		error: function (dato) {


			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
				showConfirmButton: false
			});
			return false;
		}
	});



});




$("#boton_guardarDatosInforme").click(function () {

	// Validamos los campos requeridos
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme guardar la información !",
			text: "Una vez guardada la información los datos seran actualizados para el informe",
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
				$('#form_recInforme').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorial',
					data: { opcion: 5 },
					resetForm: false,
					success: function (dato) {


						// mensaje
						swal({
							title: "Correcto",
							text: "Informacion guardada exitosamente",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});


						$('#boton_descargarquimicosdoc').css('display', 'block')

						// actualiza boton
						$('#boton_guardarDatosInforme').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarDatosInforme').attr('disabled', false);

						// cerrar modal
						// $('#modal_datosInforme').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardarDatosInforme').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardarDatosInforme').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardarDatosInforme').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarDatosInforme').attr('disabled', false);

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
	} else {
		swal({
			title: "Faltan datos por rellenear",
			text: "Asegurece de tener todos los datos rellenados, para poder continuar",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});

	}

});


$('#tab2_informe_tabla').click(function (e) {

	$('#divTablaInforme').empty();

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerTablaInforme/" + $('#recsensorial_id').val(),
		data: {},
		cache: false,
		success: function (dato) {

			var tabla = '';
			var ppt_viejo = ''
			var ct_viejo = ''
			var ppt_nuevo = ''
			var ct_nuevo = ''
			var justificacion = ''

			$.each(dato.data, function (index, valor) {

				//HISTORIAL DE PPT Y CT
				ppt_viejo = valor.PPT == 1 ? `<input type="number" class="form-control text-center" name="PPT_VIEJO[]" id="PPT_VIEJO_${valor.SUSTANCIA_ID}" style=" border:1px solid green; font-size:20px; width:75px" min="0" value="${valor.VAL_PUNTOS_VIEJO}" readonly>` : `<input type="number" class="form-control  bloqueado" name="PPT_VIEJO[]" id="PPT_VIEJO_${valor.SUSTANCIA_ID}"  style="background:#FADBD8; border:1px solid red; font-size:20px; width:75px" readonly >`

				if (valor.PPT == 1) {

					ct_viejo = valor.CT == 1 ? `<input type="number" class="form-control text-center " name="CT_VIEJO[]" id="CT_VIEJO_${valor.SUSTANCIA_ID}" style=" border:1px solid green ; font-size:20px; width:75px" value="${valor.VAL_CT_VIEJO}" min="0" readonly>` : `<input type="number" class="form-control text-center bloqueado" value="${valor.VAL_CT_VIEJO}" name="CT_VIEJO[]" id="CT_VIEJO_${valor.SUSTANCIA_ID}"  style="background:#FADBD8; border:1px solid red ; font-size:20px; width:75px" readonly>`
				} else {

					ct_viejo = valor.CT == 1 ? `<input type="number" class="form-control text-center " name="CT_VIEJO[]" id="CT_VIEJO_${valor.SUSTANCIA_ID}" style=" border:1px solid green ; font-size:20px; width:75px" min="0" value="${valor.VAL_PUNTOS_VIEJO}" readonly>` : `<input type="number" class="form-control text-center bloqueado" value="${valor.VAL_CT_VIEJO}" name="CT_VIEJO[]" id="CT_VIEJO_${valor.SUSTANCIA_ID}"  style="background:#FADBD8; border:1px solid red ; font-size:20px; width:75px" readonly>`
				}

				//NUEVO VALORES DE PPT Y CT
				ppt_nuevo = valor.PPT == 1 ? `<input type="number" class="form-control text-center PPT" name="PPT_NUEVO[]"  style=" border:1px solid green; font-size:20px; width:75px" min="0" value="${valor.VAL_PPT_NUEVO}" required>` : `<input type="number" class="form-control  bloqueado" name="PPT_NUEVO[]"  style="background:#FADBD8; border:1px solid red; font-size:20px; width:75px" value="${valor.VAL_PPT_NUEVO}" readonly >`


				ct_nuevo = valor.CT == 1 ? `<input type="number" class="form-control text-center CT" name="CT_NUEVO[]"  style=" border:1px solid green ; font-size:20px; width:75px" min="0" value="${valor.VAL_CT_NUEVO}"  required>` : `<input type="number" class="form-control text-center bloqueado" name="CT_NUEVO[]" style="background:#FADBD8; border:1px solid red ; font-size:20px; width:75px" value="${valor.VAL_CT_NUEVO}"  readonly>`

				justificacion = valor.TIENE_JUSTIFICACION == 1 ? `<textarea row="2" class="form-control JUSTIFICACION" name="JUSTIFICACION[]"  style="height:73px">${valor.JUSTIFICACION}</textarea>` : `<textarea row="2" class="form-control JUSTIFICACION" name="JUSTIFICACION[]"  style="height:73px" readonly></textarea>`


				tabla += `
					<div class="row mb-4 padre">
						<div class="col-2">
							<p>${valor.CATEGORIA} - <span class="badge badge-success p-2" style="font-size: 15px"><i class="fa fa-clock-o"></i> Expo. ${valor.TIEMPO_EXPO} Min.</span></p>
							<input type="hidden" class="form-control" id="ID_GRUPO" name="ID_GRUPO[]" value="${valor.ID_GRUPO_EXPOSICION}">
							<input type="hidden" class="form-control" id="CATEGORIA_ID" name="CATEGORIA_ID[]" value="${valor.CATEGORIA_ID}">

						</div>
						<div class="col-3">
							<p>${valor.PRODUCTO_COMPONENTE} </p>
							<input type="hidden" class="form-control" id="PRODUCTO_ID" name="PRODUCTO_ID[]" value="${valor.PRODUCTO_ID}">
							<input type="hidden" class="form-control" id="SUSTANCIA_ID" name="SUSTANCIA_ID[]" value="${valor.SUSTANCIA_ID}">

						</div>
						
						<div class="col-2 text-center">
							<div class="row gap-0">
								<div class="col-4 d-flex">
									<div>
										<span class="mb-0">PPT</span>
										${ppt_viejo}
									</div>
								
								
									<div class="mx-2">
									
										<span class="mb-0">CT</span>
										${ct_viejo}

									</div>
									<div>
									
										<span class="mb-0">TOTAL</span>
										<input type="number" class="form-control text-center PUNTOS_VAL" min="0" name="PUNTOS_VIEJO[]" style="font-size:20px; width:75px"  value="${valor.VAL_PUNTOS_VIEJO}" readonly>
									
									</div>
								</div>
							</div>
						</div>

						<div class="col-2 text-center mx-4">
								<div class="row gap-0">
								<div class="col-4 d-flex">
									<div>
										<span class="mb-0">PPT</span>
										${ppt_nuevo}
									</div>
								
								
									<div class="mx-2">
									
										<span class="mb-0">CT</span>
										${ct_nuevo}

									</div>
									<div>
									
										<span class="mb-0">TOTAL</span>
										<input type="number" class="form-control text-center PUNTOS" min="0" name="PUNTOS_NUEVO[]" style="font-size:20px; width:75px"  value="${valor.VAL_PUNTOS_NUEVO}" readonly>
									
									</div>
								</div>
							</div>
						</div>

						<div class="col-2">
							${justificacion}
						</div>
					</div>
				`;

			});

			$('#divTablaInforme').html(tabla);



			nuevaTablaInforme = dato.nuevo

			// mostrar modal
			$('#boton_guardarTablaInformes').prop('disabled', false);
			$('#tab2_informe_tabla').html('Puntos de muestreo y POE');

		},
		beforeSend: function () {

			$('#tab2_informe_tabla').html('Puntos de muestreo y POE <i class="fa fa-spin fa-spinner"></i>');
			$('#boton_guardarTablaInformes').prop('disabled', true);

		},
		error: function (dato) {


			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
				showConfirmButton: false
			});
			return false;
		}
	});


})


$(document).ready(function () {
	$(document).on('keyup change', '.PPT, .CT', function () {
		// Obtener el elemento padre más cercano con clase '.padre'
		var parentRow = $(this).closest('.padre');

		// Obtener el sustanciaID desde el input correspondiente
		var puntosViejos = parentRow.find('input.PUNTOS_VAL').val();
		var ct_input = parentRow.find('input.CT');
		var ppt_input = parentRow.find('input.PPT');
		var puntosNuevos_input = parentRow.find('input.PUNTOS');
		var justificacion = parentRow.find('textarea.JUSTIFICACION');


		var ct = parseInt(ct_input.val()) || 0;
		var ppt = parseInt(ppt_input.val()) || 0;
		var puntosNuevos = parseInt(puntosNuevos_input.val()) || 0;

		var total = ct + ppt;

		// Comparar con los puntos viejos y realizar las acciones correspondientes
		if (total === parseInt(puntosViejos)) {
			puntosNuevos_input.removeClass('error');
			puntosNuevos_input.val(total);
			justificacion.val('');
			justificacion.prop('readonly', true).prop('required', false);

		} else if (total > parseInt(puntosViejos)) {
			puntosNuevos_input.removeClass('error');
			puntosNuevos_input.val(total);
			justificacion.prop('readonly', false).prop('required', true);
		} else {
			puntosNuevos_input.val(total);
			puntosNuevos_input.addClass('error');
			justificacion.prop('readonly', true).prop('required', false);
			justificacion.val('');
		}
	});
});





$("#boton_guardarTablaInformes").click(function () {

	if ($('#divTablaInforme').find('input.error').length > 0) {

		swal({
			title: "Upss... No es posible guardar los datos",
			text: "Al parecer la existen algunos datos que no concuerda, por favor verifique bien",
			type: "warning", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2500,
			showConfirmButton: false
		});

	} else {

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			// Valida envio de datos
			swal({
				title: "¡Confirme para guardar la tabla de Puntos de muestreo y POE!",
				text: "Una vez guardada la información los datos seran usados para la Tabla del Informe",
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
					$('#form_recTabla').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/recsensorial',
						data: {
							opcion: 6,
							RECONOCIMIENTO_ID: $('#recsensorial_id').val(),
							NUEVO: nuevaTablaInforme
						},
						resetForm: false,
						success: function (dato) {


							// mensaje
							swal({
								title: "Correcto",
								text: "Informacion guardada exitosamente",
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});




							// actualiza boton
							$('#boton_descargarquimicosdoc').removeClass('bloqueado').addClass('desbloqueado')
							$('#boton_guardarTablaInformes').html('Guardar <i class="fa fa-save"></i>');
							$('#boton_guardarTablaInformes').attr('disabled', false);

							// cerrar modal
							// $('#modal_datosInforme').modal('hide');
						},
						beforeSend: function () {
							$('#boton_guardarTablaInformes').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#boton_guardarTablaInformes').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#boton_guardarTablaInformes').html('Guardar <i class="fa fa-save"></i>');
							$('#boton_guardarTablaInformes').attr('disabled', false);

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
		} else {
			swal({
				title: "Campos incompletos",
				text: "Al parecer la existen algunos vacios, por favor verifique que todos los campos esten rellenados",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 2500,
				showConfirmButton: false
			});
		}
	}
});


//OBTENEMOS LAS OPCIONES PARA LOS SELECT DE GRUPO DE EXPOSICON Y COMPONENTES EN EL APARTADO DE PUNTOS DE MUESTREO Y POE (SOLICITADOS POR EL CLIENTE)
$('#modal_datosInforme').on('shown.bs.modal', function () {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerGruposComponetes/" + $('#recsensorial_id').val(),
		data: {},
		cache: false,
		success: function (dato) {

			//ASIGNAMOS VALORES A LAS VARIABLES PARA QUE PUEDAN SER UTILIZADAS
			optionSelectAreas = dato.opcionesaAreas
			optionSelectComponentes = dato.opcionesComponentes

			$('.AREAS').html(optionSelectAreas)
			$('.SUSTANCIAS').html(optionSelectComponentes)


			var selectize = $('.SUSTANCIAS')[0].selectize;
			if (selectize) {
				selectize.destroy();
			}

			$('.SUSTANCIAS').selectize()


			if (dato.puntos == 0) {
				$('#boton_descargarquimicosdoc').removeClass('desbloqueado').addClass('bloqueado')

			} else {
				$('#boton_descargarquimicosdoc').removeClass('bloqueado').addClass('desbloqueado')

			}


		},
		beforeSend: function () {

		},
		error: function (dato) {

			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
				showConfirmButton: false
			});
			return false;
		}
	});

})


//FUNCION PARA AGREGAR NUEVOS PUNTOS DE MUESTREO
$('#boton_agregarTablaClienteInformes').click(function (e) {
	e.preventDefault();

	var selectArea = `   <select class="custom-select form-control AREAS" name="AREA_ID[]" style="width: 100%!important" required="">
							<option value=""></option>

						</select>`

	var selecCategoria = `<select class="custom-select form-control CATEGORIAS" name="CATEGORIA_ID[]" style="width: 100%!important" required="">
							<option value=""></option>
						</select>`;


	var selecSustancia = `<select class="custom-select form-control SUSTANCIAS" name="SUSTANCIA_ID[]" required="">
							<option value=""></option>
						</select>`;

	var html = ` <div class="row mt-2 padre">

					<div  class="col-3" >
						<div class="form-group mb-1">
							${selectArea}
						</div>
					</div>
					<div  class="col-3" >
						<div class="form-group mb-1">
							${selecCategoria}
						</div>
					</div>
					<div class="col-3">
						<div class="form-group">
							${selecSustancia}
						</div>
				
					</div>

					<div class="col-1 text-center">
						<input type="number" class="form-control  bloqueado PPT_CLIENTE" name="PPT[]" style="background:#FADBD8; border:1px solid red; font-size:20px" readonly>

					</div>

					<div class="col-1 text-center">
						<input type="number" class="form-control bloqueado CT_CLIENTE" name="CT[]" style="background:#FADBD8; border:1px solid red ; font-size:20px" readonly>

					</div>
					<div class="col-1 text-center">
						<input type="number" class="form-control text-center PUNTOS_CLIENTE" min="0" name="PUNTOS[]" style="font-size:20px" readonly>

					</div>
				</div>`;


	$('#divTablaClienteInforme').append(html)

	// Selecciona los elementos recién agregados y les asigna las opciones
	var lastRow = $('#divTablaClienteInforme .padre').last();
	var lastArea = lastRow.find('.AREAS');
	var lastSustancia = lastRow.find('.SUSTANCIAS');


	lastArea.html(optionSelectAreas);
	lastSustancia.html(optionSelectComponentes);


	lastSustancia.selectize();



});


// Delegación de eventos para elementos dinámicos
$('#divTablaClienteInforme').on('change', '.AREAS', function () {
	var parentRow = $(this).closest('.padre');
	var selectCategoria = parentRow.find('.CATEGORIAS');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerCategorias/" + $('#recsensorial_id').val() + "/" + $(this).val(),
		data: {},
		cache: false,
		success: function (dato) {
			// ASIGNAMOS VALORES A LAS VARIABLES PARA QUE PUEDAN SER UTILIZADAS
			optionSelectGruposExposicion = dato.opcionesaCategorias;
			selectCategoria.html(optionSelectGruposExposicion);
		},
		beforeSend: function () {
			selectCategoria.html('<option selected>Consultando categorias...</option>');
		},
		error: function (dato) {
			selectCategoria.html('<option selected>Error al consultar categorias...</option>');
		}
	});
})

//Delegacion de eventos para elementos dinamicos
$('#divTablaClienteInforme').on('change', '.SUSTANCIAS', function () {

	var parentRow = $(this).closest('.padre');
	var ppt = parentRow.find('.PPT_CLIENTE');
	var ct = parentRow.find('.CT_CLIENTE');


	// console.log(ppt);

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/consultarPPTyCT/" + $(this).val(),
		data: {},
		cache: false,
		success: function (dato) {

			if (dato.code == 1) {

				if (dato.PPT == 1) {
					ppt.removeClass('bloqueado')
					ppt.css({ 'border': '1px solid green', 'background': '#fff' });
					ppt.prop('readonly', false)
				}

				if (dato.CT == 1) {
					ct.removeClass('bloqueado')
					ct.css({ 'border': '1px solid green', 'background': '#fff' });
					ct.prop('readonly', false)
				}

			} else {

				swal({
					title: "Componente incompleto... ",
					text: "Lo sentimos al parecer este componente no tiene cargada toda la información (CT y PPT) verifíquelo  por favor!",
					type: "warning", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 3000,
					showConfirmButton: false
				});

			}

		},
		beforeSend: function () {


		},
		error: function (dato) {



		}
	});
})




//ELIMINAR REGISTRO DE PUNTOS PROPORCIONADOS POR EL CLIENTE
$('#boton_eliminarTablaClienteInformes').click(function (e) {
	e.preventDefault();


	// Selecciona los elementos recién agregados y les asigna las opciones
	var lastRow = $('#divTablaClienteInforme .row').last();
	lastRow.remove()


});



$('#tab3_informe_tabla_cliente').click(function (e) {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerTablaClienteInforme/" + $('#recsensorial_id').val(),
		data: {},
		cache: false,
		success: function (dato) {

			if (dato.data == "Vacia") {
				nuevaTablaClienteInforme = dato.nuevo

			} else {
				//LIMPIAMOS EL DIV DONDE ESAT EL PRIMER REGISTRO
				$('#divTablaClienteInforme').empty()

				//CREAMOS ELEMENTOS SEGUN TENGAMOS EN NUESTRA BASE DE DATOS
				$.each(dato.data, function (index, valor) {

					var ppt = valor.TIENE_PPT == 1 ? `<input type="number" class="form-control text-center PPT_CLIENTE" name="PPT[]" style=" border:1px solid green; font-size:20px; width:75px" min="0" value="${valor.PPT}" readonly>` : `<input type="number" class="form-control  bloqueado" name="PPT[]"  style="background:#FADBD8; border:1px solid red; font-size:20px; width:75px" readonly >`

					var ct = valor.TIENE_CT == 1 ? `<input type="number" class="form-control text-center CT_CLIENTE" name="CT[]"  style=" border:1px solid green ; font-size:20px; width:75px" value="${valor.ct}" min="0" readonly>` : `<input type="number"  class="form-control bloqueado" name="CT[]"  style="background:#FADBD8; border:1px solid red ; font-size:20px; width:75px" readonly>`

					var selectArea = `   <select class="custom-select form-control AREAS" name="AREA_ID[]" style="width: 100%!important" required="">
							<option value=""></option>

						</select>`

					var selecCategoria = `<select class="custom-select form-control CATEGORIAS" name="CATEGORIA_ID[]" style="width: 100%!important" required="">
										<option value=""></option>
									</select>`;


					var selecSustancia = `<select class="custom-select form-control SUSTANCIAS" name="SUSTANCIA_ID[]" >
										<option value=""></option>
									</select>`;


					var html = ` <div class="row mt-2 padre">

								<div  class="col-3" >
									<div class="form-group mb-1">
										${selectArea}
									</div>
								</div>
								<div  class="col-3" >
									<div class="form-group mb-1">
										${selecCategoria}
									</div>
								</div>
								<div class="col-3">
									<div class="form-group">
										${selecSustancia}
									</div>
							
								</div>

								<div class="col-1 text-center">
									${ppt}

								</div>

								<div class="col-1 text-center">
									${ct}

								</div>
								<div class="col-1 text-center">
									<input type="number" class="form-control text-center PUNTOS_CLIENTE" min="0" name="PUNTOS[]" value="${valor.PUNTOS}" style="font-size:20px" readonly>

								</div>
							</div>`;


					$('#divTablaClienteInforme').append(html)

					// Selecciona los elementos recién agregados y les asigna las opciones
					var lastRow = $('#divTablaClienteInforme .padre').last();
					var lastArea = lastRow.find('.AREAS');
					var lastSustancia = lastRow.find('.SUSTANCIAS');
					var lastCategoria = lastRow.find('.CATEGORIAS');


					lastArea.html(optionSelectAreas);
					lastSustancia.html(optionSelectComponentes);
					lastSustancia.selectize();

					var selectizeInstance = lastSustancia.selectize()[0].selectize;

					//ASIGNAMOS VALORES
					lastArea.val(valor.AREA_ID)
					selectizeInstance.setValue(valor.SUSTANCIA_ID);


					setCategoria(valor.CATEGORIA_ID, lastCategoria, valor.AREA_ID)

				})
			}




			// HABILITAR BOTRONES
			$('#boton_guardarTablaClienteInformes').prop('disabled', false);
			$('#boton_agregarTablaClienteInformes').prop('disabled', false);
			$('#tab3_informe_tabla_cliente').html('Puntos de muestreo y POE (Solicitados por el cliente)');

		}, beforeSend: function () {

			//DESABILITAR BOTONES
			$('#tab3_informe_tabla_cliente').html('Puntos de muestreo y POE (Solicitados por el cliente) <i class="fa fa-spin fa-spinner"></i>');
			$('#boton_guardarTablaClienteInformes').prop('disabled', true);
			$('#boton_agregarTablaClienteInformes').prop('disabled', true);


		}, error: function (dato) {

			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
				showConfirmButton: false
			});
			return false;
		}
	});


})



function setCategoria(val, select, id_area) {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerCategorias/" + $('#recsensorial_id').val() + "/" + id_area,
		data: {},
		cache: false,
		success: function (dato) {

			//ASIGNAMOS VALORES A LAS VARIABLES PARA QUE PUEDAN SER UTILIZADAS

			optionSelectGruposExposicion = dato.opcionesaCategorias
			select.html(optionSelectGruposExposicion)

			select.val(val)
		},
		beforeSend: function () {
			select.html('<option selected>Consultando categorias...</option>')
		},
		error: function (dato) {

			select.html('<option selected>Eror al consultar categorias...</option>')

		}
	});
}



$('.AREAS').on('change', function () {

	var parentRow = $(this).closest('.padre');
	var selectCategoria = parentRow.find('.CATEGORIAS');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerCategorias/" + $('#recsensorial_id').val() + "/" + $(this).val(),
		data: {},
		cache: false,
		success: function (dato) {

			//ASIGNAMOS VALORES A LAS VARIABLES PARA QUE PUEDAN SER UTILIZADAS

			optionSelectGruposExposicion = dato.opcionesaCategorias
			selectCategoria.html(optionSelectGruposExposicion)

		},
		beforeSend: function () {
			selectCategoria.html('<option selected>Consultando categorias...</option>')
		},
		error: function (dato) {

			selectCategoria.html('<option selected>Eror al consultar categorias...</option>')

		}
	});

})


$('.SUSTANCIAS').on('change', function () {

	var parentRow = $(this).closest('.padre');
	var ppt = parentRow.find('.PPT_CLIENTE');
	var ct = parentRow.find('.CT_CLIENTE');


	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/consultarPPTyCT/" + $(this).val(),
		data: {},
		cache: false,
		success: function (dato) {

			if (dato.code == 1) {

				if (dato.PPT == 1) {
					ppt.removeClass('bloqueado')
					ppt.css({ 'border': '1px solid green', 'background': '#fff' });
					ppt.prop('readonly', false)
				}

				if (dato.CT == 1) {
					ct.removeClass('bloqueado')
					ct.css({ 'border': '1px solid green', 'background': '#fff' });
					ct.prop('readonly', false)


				}

			} else {

				swal({
					title: "Componente incompleto... ",
					text: "Lo sentimos al parecer este componente no tiene cargada toda la información (CT y PPT) verifíquelo  por favor!",
					type: "warning", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 3000,
					showConfirmButton: false
				});

			}



		},
		beforeSend: function () {


		},
		error: function (dato) {



		}
	});

})




$(document).ready(function () {

	$(document).on('keyup, change', '.PPT_CLIENTE, .CT_CLIENTE', function () {
		//DIV PADRES
		var parentRow = $(this).closest('.padre');


		var ppt = parentRow.find('.PPT_CLIENTE');
		var ct = parentRow.find('.CT_CLIENTE');
		var puntos = parentRow.find('.PUNTOS_CLIENTE')


		var val_ct = ct.val() || 0;
		var val_ppt = ppt.val() || 0;

		var total = parseInt(val_ct) + parseInt(val_ppt)

		//Asignamos el valor de la suma de PPT y CT
		puntos.val(total)


	});
});


$("#boton_guardarTablaClienteInformes").click(function () {

	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme para guardar la tabla de Puntos de muestreo y POE!",
			text: "Una vez guardada la información los datos seran usados para la Tabla del Informe",
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
				$('#form_recTablaCliente').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorial',
					data: {
						opcion: 7,
						RECONOCIMIENTO_ID: $('#recsensorial_id').val(),
						NUEVO: nuevaTablaClienteInforme
					},
					resetForm: false,
					success: function (dato) {


						// mensaje
						swal({
							title: "Correcto",
							text: "Informacion guardada exitosamente",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});




						// actualiza boton
						$('#boton_guardarTablaClienteInformes').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarTablaClienteInformes').attr('disabled', false);

						// cerrar modal
						// $('#modal_datosInforme').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardarTablaClienteInformes').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardarTablaClienteInformes').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardarTablaClienteInformes').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarTablaClienteInformes').attr('disabled', false);

						// mensaje
						swal({
							title: "No se pudo guardar",
							text: "Asegúrese de que todos los campos validos esten rellenados ",
							type: "warning", // warning, error, success, info
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


$('#PETICION_CLIENTE').on('change', function (e) {
	if ($(this).is(':checked')) {
		$('#tab3_informe_tabla_cliente').css('display', 'block');
	} else {
		$('#tab3_informe_tabla_cliente').css('display', 'none');

	}
})

$('#ID_CATCONCLUSION').on('change', function (e) {

	var selectedOption = $(this).find('option:selected');
	var descripcion = selectedOption.data('descripcion');

	$('#CONCLUSION').val(descripcion);


})


//GUARDAR SOLICITUD DE CONTROL DE CAMBIO
$("#boton_guardar_control_cambio").click(function () {

	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme para generar solicitud de cambio!",
			text: "Una vez guardada la información tendra que esperar una autorización",
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
				$('#form_controlCambios').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorial',
					data: {
						opcion: 8,
						RECONOCIMIENTO_ID: $('#recsensorial_id').val()
					},
					resetForm: false,
					success: function (dato) {

						if (dato.MSJ == 'SOLICITUD ABIERTA') {
							swal({
								title: "Solicitud sin autorizar",
								text: "La sentimos al parecer existe una solicitud de cambio sin Autorizar, comuniquelo con su superior",
								type: "warning", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 2500,
								showConfirmButton: false
							});

						} else {

							// mensaje
							swal({
								title: "Solicitud de cambio generada",
								text: "La solicitude del cambio a sido generada, espere a que su superior la autorize",
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});

							$('#form_controlCambios').each(function () {
								this.reset();
							});
						}


						tabla_ControlCambios()


						// actualiza boton
						$('#boton_guardar_control_cambio').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_control_cambio').attr('disabled', false);


					},
					beforeSend: function () {
						$('#boton_guardar_control_cambio').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_control_cambio').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardar_control_cambio').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_control_cambio').attr('disabled', false);

						// mensaje
						swal({
							title: "No se pudo guardar",
							text: "Asegúrese de que todos los campos validos esten rellenados ",
							type: "warning", // warning, error, success, info
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


function tabla_ControlCambios() {
	try {
		var ruta = "/TablaControlCambios/" + $('#recsensorial_id').val();

		if (tabla_control_cambios != null) {
			tabla_control_cambios.clear().draw();
			tabla_control_cambios.ajax.url(ruta).load();

		}
		else {
			var numeroejecucion = 1;
			tabla_control_cambios = $('#tabla_control_cambios').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en tabla_recsensorial');
						if (numeroejecucion <= 1) {
							tabla_ControlCambios();
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
						"data": "COUNT",
						"defaultContent": "-"
					},
					{
						"data": "REALIZADO_POR",
						"defaultContent": "-"
					},
					{
						"data": "CAMBIOS",
						"defaultContent": "-"
					},
					{
						"data": "FECHA_REALIZADO",
						"defaultContent": "-"
					},
					{
						"data": "AUTORIZADO_POR",
						"defaultContent": "-"
					},
					{
						"data": "FECHA_AUTORIZADO",
						"defaultContent": "-"
					},
					{
						"orderable": false,
						"data": 'boton_autorizar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": true,
				"processing": true,
				'responsive': true,
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
		tabla_control_cambios.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		// alert("error en el ajax");
		tabla_ControlCambios();
	}
}



$(document).ready(function () {
	$('#tabla_control_cambios tbody').on('click', 'td>button.AUTORIZAR', function () {
		var tr = $(this).closest('tr');
		var row = tabla_control_cambios.row(tr);


		swal({
			title: "¿Esta seguro de autorizar este cambio?",
			text: "" + row.data().recsensorialarea_nombre,
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
					title: "¡Confirme nuevamente eliminar área!",
					text: "" + row.data().recsensorialarea_nombre,
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
							url: "/recsensorialareaeliminar/" + row.data().id,
							data: {},
							cache: false,
							success: function (dato) {
								// actualiza tabla
								funcion_tabla_recsensorialareas(row.data().recsensorial_id);

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

	});
});



//mandamos a llamar a nuestra funcion de control de cambios
$('#tab4_control_cambios').click(function (e) {

	tabla_ControlCambios()

})






$('#boton_agregarTablapropocionadocliente').click(function (e) {
	e.preventDefault();

	var inputarea = `<input type="text" class="form-control AREAPROP" name="AREA_PROPORCIONADACLIENTE[]" id="AREA_PROPORCIONADACLIENTE" required>`;

	var inputcategoria = `<input type="text" class="form-control CATEGORIAPROP" name="CATEGORIA_PROPORCIONADACLIENTE[]" id="CATEGORIA_PROPORCIONADACLIENTE" required>`;

	var inputproducto = `<input type="text" class="form-control PRODUCTOPROP" name="PRODUCTO_PROPORCIONADACLIENTE[]" id="PRODUCTO_PROPORCIONADACLIENTE" required>`;

	var selecSustancia1 = `<select class="custom-select form-control SUSTANCIAS1" name="SUSTANCIA_ID[]" id required="">
							<option value=""></option>
						</select>`;


	var html = `<div class="row mt-2 padrepropocionadocliente">
                    <div class="col-2">
                        <div class="form-group mb-1">
                            ${inputarea}
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group mb-1">
                            ${inputcategoria}
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group mb-1">
                            ${inputproducto}
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            ${selecSustancia1}
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <input type="number" class="form-control PPTPROP" name="PPT_PROPORCIONADACLIENTE[]" id="PPT_PROPORCIONADACLIENTE" style="border:1px solid #94B732; font-size:20px">
                    </div>
                    <div class="col-1 text-center">
                        <input type="number" class="form-control CTPROP" name="CT_PROPORCIONADACLIENTE[]" id="CT_PROPORCIONADACLIENTE" style="border:1px solid #94B732; font-size:20px">
                    </div>
                    <div class="col-1 text-center">
                        <input type="number" class="form-control text-center PUNTOSPROP" name="PUNTOS_PROPORCIONADACLIENTE[]" id="PUNTOS_PROPORCIONADACLIENTE" style="font-size:20px" readonly>
                    </div>
                </div>`;

	$('#div_proporcionadocliente').append(html);




	// Selecciona los elementos recién agregados y les asigna las opciones
	var lastRow = $('#div_proporcionadocliente .padrepropocionadocliente').last();
	var lastSustancia = lastRow.find('.SUSTANCIAS1');


	lastSustancia.html(optionSelectComponentes1);


	lastSustancia.selectize();



});



//ELIMINAR REGISTRO DE PUNTOS PROPORCIONADOS POR EL CLIENTE
$('#boton_eliminarTablapropocionadocliente').click(function (e) {
	e.preventDefault();


	// Selecciona los elementos recién agregados y les asigna las opciones
	var lastRow = $('#div_proporcionadocliente .row').last();
	lastRow.remove()


});


$(document).on('input', '.PPTPROP, .CTPROP', function () {
	var $row = $(this).closest('.row');

	var pptValue = parseFloat($row.find('.PPTPROP').val()) || 0;
	var ctValue = parseFloat($row.find('.CTPROP').val()) || 0;

	var suma = pptValue + ctValue;
	$row.find('.PUNTOSPROP').val(suma);
});



$("#boton_guardarTablapropocionadocliente").click(function () {

	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		// Valida envio de datos
		swal({
			title: "¡Confirme para guardar la tabla de Puntos de muestreo proporcioandos por el cliente",
			text: "Una vez guardada la información los datos podran usarse",
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
				$('#form_proporcionadocliente').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/recsensorial',
					data: {
						opcion: 9,
						RECONOCIMIENTO_ID: $('#recsensorial_id').val(),
						NUEVO: nuevaTablaClienteInformeProporcionado
					},
					resetForm: false,
					success: function (dato) {


						// mensaje
						swal({
							title: "Correcto",
							text: "Informacion guardada exitosamente",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});




						// actualiza boton
						$('#boton_guardarTablapropocionadocliente').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarTablapropocionadocliente').attr('disabled', false);

						// cerrar modal
						// $('#modal_datosInforme').modal('hide');
					},
					beforeSend: function () {
						$('#boton_guardarTablapropocionadocliente').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardarTablapropocionadocliente').attr('disabled', true);
					},
					error: function (dato) {
						// actualiza boton
						$('#boton_guardarTablapropocionadocliente').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardarTablapropocionadocliente').attr('disabled', false);

						// mensaje
						swal({
							title: "No se pudo guardar",
							text: "Asegúrese de que todos los campos validos esten rellenados ",
							type: "warning", // warning, error, success, info
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


$('#tab_menu9').click(function (e) {
	e.preventDefault();

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerTablaClienteProporcionado/" + $('#recsensorial_id').val(),
		cache: false,
		success: function (dato) {

			if (dato.data == "proporcionado") {
				nuevaTablaClienteInformeProporcionado = dato.nuevo;
			} else {
				nuevaTablaClienteInformeProporcionado = dato.nuevo

				$('#div_proporcionadocliente').empty(); // Limpiar el div antes de agregar nuevo contenido

				$.each(dato.data, function (index, valor) {
					var inputarea = `<input type="text" class="form-control AREAPROP" name="AREA_PROPORCIONADACLIENTE[]" id="AREA_PROPORCIONADACLIENTE" value="${valor.AREA_PROPORCIONADACLIENTE}" required>`;
					var inputcategoria = `<input type="text" class="form-control CATEGORIAPROP" name="CATEGORIA_PROPORCIONADACLIENTE[]" id="CATEGORIA_PROPORCIONADACLIENTE" value="${valor.CATEGORIA_PROPORCIONADACLIENTE}" required>`;
					var inputproducto = `<input type="text" class="form-control PRODUCTOPROP" name="PRODUCTO_PROPORCIONADACLIENTE[]" id="PRODUCTO_PROPORCIONADACLIENTE" value="${valor.PRODUCTO_PROPORCIONADACLIENTE}" required>`;
					var selecSustancia1 = `<select class="custom-select form-control SUSTANCIAS1" name="SUSTANCIA_ID[]" >
											<option value="${valor.SUSTANCIA_ID}"></option>
										</select>`;

					var html = `<div class="row mt-2 padrepropocionadocliente">
						<div class="col-2"><div class="form-group mb-1">${inputarea}</div></div>
						<div class="col-2"><div class="form-group mb-1">${inputcategoria}</div></div>
						<div class="col-2"><div class="form-group mb-1">${inputproducto}</div></div>
						<div class="col-3"><div class="form-group">${selecSustancia1}</div></div>
						<div class="col-1 text-center"><input type="number" class="form-control PPTPROP" name="PPT_PROPORCIONADACLIENTE[]" id="PPT_PROPORCIONADACLIENTE" value="${valor.PPT_PROPORCIONADACLIENTE}" style="border:1px solid #94B732; font-size:20px"></div>
						<div class="col-1 text-center"><input type="number" class="form-control CTPROP" name="CT_PROPORCIONADACLIENTE[]" id="CT_PROPORCIONADACLIENTE" value="${valor.CT_PROPORCIONADACLIENTE}" style="border:1px solid #94B732; font-size:20px"></div>
						<div class="col-1 text-center"><input type="number" class="form-control text-center PUNTOSPROP" name="PUNTOS_PROPORCIONADACLIENTE[]" id="PUNTOS_PROPORCIONADACLIENTE" value="${valor.PUNTOS_PROPORCIONADACLIENTE}" style="font-size:20px" readonly></div>
					</div>`;

					$('#div_proporcionadocliente').append(html);

					var lastRow = $('#div_proporcionadocliente .padrepropocionadocliente').last();
					var lastSustancia = lastRow.find('.SUSTANCIAS1');

					console.log(lastSustancia);

					// Asignar las opciones y convertir en selectize
					lastSustancia.html(optionSelectComponentes1);
					lastSustancia.selectize();

					var selectizeInstance = lastSustancia.selectize()[0].selectize;


					// Asignar el valor guardado en la base de datos
					selectizeInstance.setValue(valor.SUSTANCIA_ID)
				});
			}

			$('#boton_guardarTablapropocionadacliente').prop('disabled', false);
			$('#boton_agregarTablapropocionadacliente').prop('disabled', false);
			$('#tab_menu9').html('Proporcionado Cliente');
		},
		beforeSend: function () {
			$('#tab_menu9').html('Proporcionado Cliente <i class="fa fa-spin fa-spinner"></i>');
			$('#boton_guardarTablapropocionadacliente').prop('disabled', true);
			$('#boton_agregarTablapropocionadacliente').prop('disabled', true);
		},
		error: function (dato) {
			swal({
				title: "Error",
				text: "" + dato.msj,
				type: "error",
				buttons: { visible: false },
				timer: 3000,
				showConfirmButton: false
			});
		}
	});
});


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

						// //FOTOS OBTENIDAS DE RECSENSORIAL
						// if (response.info[0].FOTOUBICACION != null) {
						// 	var archivo = response.info[0].FOTOUBICACION;
						// 	var extension = archivo.substring(archivo.lastIndexOf("."));
						// 	var imagenUrl = '/mostrarmapapsico/0/' + response.info[0].ID + extension;
				
						// 	rutaMapa = imagenUrl
						// 	$("#hidden_fotomapa").val(response.info[0].ID);
						// 	$("#hidden_fotomapa_extension").val(extension);
				
						// 	// INPUT FOTO UBICACION
						// 	if ($('#inputfotomapa').data('dropify')) {
						// 		$('#inputfotomapa').dropify().data('dropify').destroy();
						// 		// $('.dropify-wrapper').css('height', 400);
						// 		$('#inputfotomapa').dropify().data('dropify').settings.defaultFile = imagenUrl;
						// 		$('#inputfotomapa').dropify().data('dropify').init();
						// 	}
						// 	else {
						// 		// $('#inputfotomapa').attr('data-height', 400);
						// 		$('#inputfotomapa').attr('data-default-file', imagenUrl);
						// 		$('#inputfotomapa').dropify({
						// 			messages: {
						// 				'default': 'Arrastre la imagen aquí o haga click',
						// 				'replace': 'Arrastre la imagen o haga clic para reemplazar',
						// 				'remove': 'Quitar',
						// 				'error': 'Ooops, ha ocurrido un error.'
						// 			},
						// 			error: {
						// 				'fileSize': 'Demasiado grande ({{ value }} max).',
						// 				'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
						// 				'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
						// 				'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
						// 				'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
						// 				'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
						// 			}
						// 		});
						// 	}
				
						// 	// No requerir campo FOTO
						// 	$('#inputfotomapa').attr('required', false);
				
						// 	// Activar boton descarga
						// 	$("#boton_descargarmapaubicacion").css('display', 'block');
						// }
						// else {
						// 	$("#boton_descargarmapaubicacion").css('display', 'none');
						// }
		
						// if (response.info[0].FOTOPLANO != null) {
						// 	var archivo = response.info[0].FOTOPLANO;
						// 	var extension = archivo.substring(archivo.lastIndexOf("."));
						// 	var imagenUrl = '/mostrarplanopsico/0/' + response.info[0].ID + extension;
					
						// 	rutaMapa = imagenUrl;
					
						// 	$("#hidden_fotoplano").val(response.info[0].ID);
						// 	$("#hidden_fotoplano_extension").val(extension);
					
						// 	// INPUT FOTO PLANO
						// 	if ($('#inputfotoplano').data('dropify')) {
						// 		$('#inputfotoplano').dropify().data('dropify').destroy();
						// 		// $('.dropify-wrapper').css('height', 400);
						// 		$('#inputfotoplano').dropify().data('dropify').settings.defaultFile = imagenUrl;
						// 		$('#inputfotoplano').dropify().data('dropify').init();
						// 	}
						// 	else {
						// 		// $('#inputfotoplano').attr('data-height', 400);
						// 		$('#inputfotoplano').attr('data-default-file', imagenUrl);
						// 		$('#inputfotoplano').dropify({
						// 			messages: {
						// 				'default': 'Arrastre la imagen aquí o haga click',
						// 				'replace': 'Arrastre la imagen o haga clic para reemplazar',
						// 				'remove': 'Quitar',
						// 				'error': 'Ooops, ha ocurrido un error.'
						// 			},
						// 			error: {
						// 				'fileSize': 'Demasiado grande ({{ value }} max).',
						// 				'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
						// 				'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
						// 				'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
						// 				'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
						// 				'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
						// 			}
						// 		});
						// 	}
					
						// 	// No requerir campo FOTO
						// 	$('#inputfotoplano').attr('required', false);
					
						// 	// Activar boton descarga
						// 	$("#boton_descargarplanoinstalacion").css('display', 'block');
						// }
						// else {
						// 	$("#boton_descargarplanoinstalacion").css('display', 'none');
						// }
		
					
					
						// // OBTENER FOTO INSTALACION
						// if (response.info[0].FOTOINSTALACION) {
						// 	var archivo = response.info[0].FOTOINSTALACION;
						// 	var extension = archivo.substring(archivo.lastIndexOf("."));
						// 	var imagenUrl = '/mostrarfotoinstalacionpsico/0/' + response.info[0].ID + extension;
					
							
						// 	$("#hidden_fotoinstalacion").val(response.info[0].ID);
						// 	$("#hidden_fotoinstalacion_extension").val(extension);
						// 	// INPUT FOTO INSTALACION
						// 	$('#inputfotoinstalacion').dropify().data('dropify').destroy();
						// 	// $('.dropify-wrapper').css('height', 400);
						// 	$('#inputfotoinstalacion').dropify().data('dropify').settings.defaultFile = imagenUrl;
						// 	$('#inputfotoinstalacion').dropify().data('dropify').init();
					
						// 	// No requerir campo FOTO
						// 	$('#inputfotoinstalacion').attr('required', false);
					
						// 	// Activar boton descarga
						// 	$("#boton_descargarfotoinstalacion").css('display', 'block');
					
						// } else {
					
						// 	// Resetear campo FOTO INSTALACION
						// 	$('#inputfotoinstalacion').val('');
						// 	$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
						// 	$('#inputfotoinstalacion').dropify().data('dropify').clearElement();
					
						// 	// No requerir campo FOTO
						// 	$('#inputfotoinstalacion').attr('required', false);
						// 	$("#boton_descargarfotoinstalacion").css('display', 'none');
						// }

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


document.getElementById('JERARQUIACONTROL').addEventListener('change', function () {
	var descripcion = '';
	switch (this.value) {
		case '1':
			descripcion = 'Eliminar físicamente el peligro';
			break;
		case '2':
			descripcion = 'Remplazar el peligro';
			break;
		case '3':
			descripcion = 'Aislar a las personas del peligro';
			break;
		case '4':
			descripcion = 'Cambiar el modo de trabajo de las personas';
			break;
		case '5':
			descripcion = 'Protección del trabajador con Equipo de Protección Personal';
			break;
		default:
			descripcion = '';
			break;
	}
	document.getElementById('CONTROLESJERARQUIA_DESCRIPCION').value = descripcion;
});

//Validamos si el informe requiere conclusiones o no
function validarConclusion(check) {
	if (check.checked) {
		$('#ID_CATCONCLUSION').prop('disabled', false).val('').prop('required', true)
		$('#CONCLUSION').prop('disabled', false).val('').prop('required', true)
	} else {
		$('#ID_CATCONCLUSION').prop('disabled', true).val('').prop('required', false)
		$('#CONCLUSION').prop('disabled', true).val('').prop('required', false)
	}

}



$('#recsensorialmaquinaria_afecta').change(function () {
	var selectedValue = $(this).val();

	var $input = $('#nombrecomun');


	if (selectedValue === '1') {
		$input.prop('disabled', true);
		$input.prop('required', false);

	} else if (selectedValue === '2') {
		$input.prop('disabled', false);
		$input.prop('required', false);

	} else {
		$input.prop('disabled', false);
		$input.prop('required', false);
	}
});




$(document).ready(function () {
	var isFormChanged = false;

	// Detectar cambios en cualquier campo del formulario
	$('#form_responsables :input').on('change', function () {
		isFormChanged = true;
	});


	$('.multisteps-form__progress-btn').on('click', function (e) {
		if (isFormChanged) {

			swal({
				title: "Responsables no guardados",
				text: "Ups... Al parecer no ha guardado la información de los responsables, asegurese de guardar la información para poder ser usada en el informe",
				type: "warning",
				buttons: { visible: false },
				timer: 2000,
				showConfirmButton: false
			});
		}
	});

	// Restablecer el estado de isFormChanged cuando se haga clic en "Guardar"
	$('#boton_guardar_responsables').on('click', function () {
		isFormChanged = false; // Se resetea el estado cuando se guarda el formulario
	});
});

// function calcularResultado() {
// 	// Obtener el valor del total de empleados
// 	const totalEmpleados = document.getElementById('total-empleados').value;
// 	const opcionSeleccionada = document.getElementById('aplicable_a').value;

// 	// Variable para el resultado
// 	let resultado = 0;

// 	if (!isNaN(totalEmpleados) && totalEmpleados !== "") {
// 		if (opcionSeleccionada === "opcion1") {
// 			// Multiplicar por 5 si se selecciona "TODOS LOS EMPLEADOS"
// 			resultado = totalEmpleados * 5;
// 		} else if (opcionSeleccionada === "opcion2") {
// 			// Dividir entre 5 si se selecciona "MUESTRA DE EMPLEADOS"
// 			resultado = totalEmpleados / 5;
// 		}
// 	}

// 	// Mostrar el resultado en el contenedor
// 	document.getElementById('resultado').innerText = `Resultado: ${resultado}`;
// }
// $(document).ready(function () {
// 	document.getElementById('total_empleados').addEventListener('input', function () {
//         const totalEmpleados = parseInt(this.value);
//         const selectAplicableA = document.getElementById('aplicable_a');
        
//         // Deshabilitar todos los checkboxes por defecto
//         document.getElementById('option1').disabled = true;
//         document.getElementById('option2').disabled = true;
//         document.getElementById('option3').disabled = true;

//         if (!isNaN(totalEmpleados)) {
//             if (totalEmpleados < 16) {
//                 // Habilitar solo la opción 1
//                 document.getElementById('option1').disabled = false;
//                 document.getElementById('option2').disabled = false;
//                 document.getElementById('option3').disabled = false;

//             } else if (totalEmpleados >= 16 && totalEmpleados < 50) {
//                 // Habilitar solo la opción 2
//                 document.getElementById('option2').disabled = false;
//                 document.getElementById('option3').disabled = false;

//             } else if (totalEmpleados >= 50) {
//                 // Habilitar solo la opción 3
//                 document.getElementById('option3').disabled = false;
//             }
//         }
        
//         // Limpiar las opciones del select cada vez que se cambia el valor de empleados
//         selectAplicableA.innerHTML = '<option value="">Selecciona una opción</option>';
        
//         if (!isNaN(totalEmpleados)) {
//             if (totalEmpleados < 50) {
//                 // Si el número de empleados es menor a 50, solo permite una opción
//                 selectAplicableA.innerHTML += '<option value="opcion1">TODOS LOS EMPLEADOS</option>';
//             } else {
//                 // Si el número de empleados es 50 o más, permite seleccionar entre dos opciones
//                 selectAplicableA.innerHTML += '<option value="opcion1">TODOS LOS EMPLEADOS</option>';
//                 selectAplicableA.innerHTML += '<option value="opcion2">MUESTRA DE EMPLEADOS</option>';
//             }
//         }
//     });
// 	document.getElementById('habilitar_opcional').addEventListener('change', function () {
//         const isChecked = this.checked;
//         const camposOpcionales = document.getElementById('campos_opcionales');
        
//         // Mostrar/ocultar campos opcionales
//         camposOpcionales.style.display = isChecked ? 'block' : 'none';

//         // Habilitar/deshabilitar los selects e inputs numéricos
//         document.getElementById('tipo_valor_hombres').disabled = !isChecked;
//         document.getElementById('valor_hombres').disabled = !isChecked;
//         document.getElementById('tipo_valor_mujeres').disabled = !isChecked;
//         document.getElementById('valor_mujeres').disabled = !isChecked;
        
//         // Limpiar valores y sufijos
//         if (!isChecked) {
//             document.getElementById('valor_hombres').value = '';
//             document.getElementById('valor_mujeres').value = '';
//             document.getElementById('sufijo_hombres').innerText = ' %';
//             document.getElementById('sufijo_mujeres').innerText = ' %';
//         }
//     });

//     // Cambiar sufijo según tipo de valor seleccionado
//     document.getElementById('tipo_valor_hombres').addEventListener('change', function () {
//         const tipoValor = this.value;
//         const sufijo = document.getElementById('sufijo_hombres');
        
//         sufijo.innerText = tipoValor === 'porcentaje' ? ' %' : ' hombres';
//     });

//     document.getElementById('tipo_valor_mujeres').addEventListener('change', function () {
//         const tipoValor = this.value;
//         const sufijo = document.getElementById('sufijo_mujeres');
        
//         sufijo.innerText = tipoValor === 'porcentaje' ? ' %' : ' mujeres';
//     });
// });
function validarEmpleados() {
    const totalEmpleados = parseInt(document.getElementById('total_empleados').value);
    const selectAplicableA = document.getElementById('aplicable_a');

    // Limpiar checkboxes
    document.getElementById('habilitar_opcional').checked = false;
    document.getElementById('option1').checked = false;
    document.getElementById('option2').checked = false;
    document.getElementById('option3').checked = false;

    // Deshabilitar todos los checkboxes por defecto
    document.getElementById('option1').disabled = true;
    document.getElementById('option2').disabled = true;
    document.getElementById('option3').disabled = true;

    if (!isNaN(totalEmpleados)) {
        if (totalEmpleados < 16) {
            // Habilitar solo la opción 1
            document.getElementById('option1').disabled = false;
            document.getElementById('option2').disabled = false;
            document.getElementById('option3').disabled = false;

        } else if (totalEmpleados >= 16 && totalEmpleados < 50) {
            // Habilitar solo la opción 2
            document.getElementById('option2').disabled = false;
            document.getElementById('option3').disabled = false;

        } else if (totalEmpleados >= 50) {
            // Habilitar solo la opción 3
            document.getElementById('option3').disabled = false;
        }
    }

    // Limpiar opciones del select
    selectAplicableA.innerHTML = '<option value="">Selecciona una opción</option>';

    // Validar si el total de empleados es un número
    if (!isNaN(totalEmpleados) && totalEmpleados > 0) {
        if (totalEmpleados < 50) {
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
    document.getElementById('resultado').innerText = '';
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
    document.getElementById('resultado').innerText = `Número de trabajadores a entrevistar: ${resultado} trabajadores`;

    // Si se selecciona "MUESTRA DE TRABAJADORES", activar el checkbox opcional
    if (opcionSeleccionada === "opcion2") {
        document.getElementById('habilitar_opcional').checked = true;
		const camposOpcionales = document.getElementById('campos_opcionales');
		camposOpcionales.style.display = 'block';
    }else{
		document.getElementById('habilitar_opcional').checked = false;
		const camposOpcionales = document.getElementById('campos_opcionales');
		camposOpcionales.style.display = 'none';
	}
}

$(document).ready(function () {
    // Manejo del evento de entrada de empleados
    document.getElementById('total_empleados').addEventListener('input', validarEmpleados);

    document.getElementById('aplicable_a').addEventListener('change', calcularResultado);

    document.getElementById('habilitar_opcional').addEventListener('change', function () {
        const isChecked = this.checked;
        const camposOpcionales = document.getElementById('campos_opcionales');

        // Mostrar u ocultar campos opcionales según el estado del checkbox
        camposOpcionales.style.display = isChecked ? 'block' : 'none';

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
            clearWarnings(); // Limpiar advertencias si el checkbox está desmarcado
        }
    });

    // Validar la suma de valores de hombres y mujeres
    document.getElementById('valor_hombres').addEventListener('input', validarValores);
    document.getElementById('valor_mujeres').addEventListener('input', validarValores);

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
        } else {
            // Limpiar las clases de advertencia si la validación es correcta
            document.getElementById('valor_hombres').classList.remove('is-invalid');
            document.getElementById('valor_mujeres').classList.remove('is-invalid');
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

$("#boton_guardar_normativa").click(function (event) {
	event.preventDefault(); // Evita el comportamiento predeterminado del formulario
	// valida campos vacios

	var valida = this.form.checkValidity();
	if (valida) {
		// Recopilar datos de turnos en formato JSON
		// var turnosjs = [];
		// var errorEnTurnos = false; // Variable para controlar si hay error en los turnos
		// var sumaHorasJornada = 0

		// $(".generarturnos").each(function () {


		// 	var horasJornada = parseInt($(this).find("input[name='recsensorialcategoria_horasjornada']").val()) || 0;
		// 	var horasComida = parseInt($(this).find("input[name='recsensorialcategoria_horascomida']").val()) || 0;

		// 	sumaHorasJornada += horasJornada;

		// 	var horaEntrada = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horarioentrada']").val() + ":00");
		// 	var horaSalida = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horariosalida']").val() + ":00");

		// 	var horasTrabajadas = (horaSalida - horaEntrada) / 1000 / 60 / 60;

		// 	if (horasJornada + horasComida !== horasTrabajadas) {
		// 		errorEnTurnos = true;
		// 	}

		// 	var turno = {
		// 		'recsensorialcategoria_horasjornada': horasJornada,
		// 		'recsensorialcategoria_horarioentrada': $(this).find("input[name='recsensorialcategoria_horarioentrada']").val(),
		// 		'recsensorialcategoria_horariosalida': $(this).find("input[name='recsensorialcategoria_horariosalida']").val(),
		// 		'recsensorialcategoria_horascomida': horasComida,
		// 		'descripcioncategoria': $(this).find("input[name='descripcioncategoria']").val(),

		// 	};
		// 	turnosjs.push(turno);
		// });

		// if (errorEnTurnos) {
		// 	swal({
		// 		title: "No disponible",
		// 		text: "Su horario de entrada y salida no coincide con las horas asignadas.",
		// 		type: "warning", // Puedes cambiar el tipo según tus preferencias
		// 		buttons: {
		// 			confirm: {
		// 				text: "OK",
		// 				value: true,
		// 				visible: true,
		// 				className: "btn btn-danger" // Puedes ajustar las clases según tu estilo
		// 			}
		// 		}
		// 	});
		// 	return; // Detiene la ejecución del guardado si hay un error en los turnos
		// }

		var formData = new FormData($('#form_normativa')[0]);
		formData.append('RECPSICO_GENEROS', document.getElementById("habilitar_opcional").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAI', document.getElementById("option1").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAII', document.getElementById("option2").checked ? 1 : 0);
		formData.append('RECPSICO_GUIAIII', document.getElementById("option3").checked ? 1 : 0);
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
				// Manejar la respuesta del servidor
				// Campos Hidden
				$("#ID_RECOPSICONORMATIVA").val(dato.normativapsico.ID_RECOPSICONORMATIVA);
				$("#RECPSICO_ID").val(dato.normativapsico.RECPSICO_ID);

				// // actualiza tabla
				// funcion_tabla_recsensorialcategorias(dato.categoriapsico.RECPSICO_ID);

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





