
//=================================================
// MENU INDICE


$(".stickyside").stick_in_parent({
	offset_top: 150 // Margin Top del menu
});


$('.stickyside a').click(function () {
	// $('.list-group-item').removeClass('active');
	// $(this).addClass('active');

	$('html, body').animate({
		scrollTop: $($(this).attr('href')).offset().top - 150 // Margin TOP del DIV al que hace referencia el menu
	}, 1200);
	return false;
});


// This is auto select left sidebar
var lastId,
	topMenu = $(".stickyside");
topMenuHeight = topMenu.outerHeight();


// All list items
menuItems = topMenu.find("a");


// Anchors corresponding to menu items
scrollItems = menuItems.map(function () {
	var item = $($(this).attr("href"));
	if (item.length) {
		return item;
	}
});


// Menu al mover el scroll
$(window).scroll(function () {
	// Get container scroll position
	var fromTop = $(this).scrollTop() + topMenuHeight - 100;
	// var fromTop = $(this).scrollTop() + topMenuHeight;

	// Get id of current scroll item
	var cur = scrollItems.map(function () {
		if ($(this).offset().top < fromTop)
			return this;
	});

	// Get the id of the current element
	cur = cur[cur.length - 1];
	var id = cur && cur.length ? cur[0].id : "";

	if (lastId !== id) {
		lastId = id;
		// Set/remove active class
		menuItems.removeClass("active").filter("[href='#" + id + "']").addClass("active");
	}
});


//=================================================
// LOAD PAGINA


var meses = ["VACIO", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
var reporteregistro_id = 0;
var agente_id = 1;
var agente_nombre = "Ruido";
var ruta_storage_guardar = '/reporteruido';
// var proyecto = <?php echo json_encode($proyecto); ?>; // Variable declarada en el Blade
// var recsensorial = <?php echo json_encode($recsensorial); ?>; // Variable declarada en el Blade


// Activar Tooltip
$('[data-toggle="tooltip"]').tooltip();


var tiempoespera = 10; // Segundos
function updateClock() {
	if (parseInt(tiempoespera) > 0) {
		$('#segundos_espera').html((tiempoespera - 1));

		tiempoespera = (parseInt(tiempoespera) - 1);

		setTimeout(function () {
			updateClock();
		}, 1000);
	}
	else {
		$('#modal_cargando').modal('hide');
	}
}


$(document).ready(function () {
	// Modal cargando
	$('#modal_cargando .modal-title').html('Cargando informe de ' + agente_nombre); // Titulo modal
	$('#modal_cargando').modal(); // Abrir modal
	updateClock(); // Ejecutar tiempo de espera

	datosgenerales(); // Cargar datos
	portadaInfo()  // Portada info

	// Inicializar campos datepicker
	jQuery('.mydatepicker').datepicker({
		format: 'yyyy-mm-dd', //'dd-mm-yyyy'
		weekStart: 1, //dia que inicia la semana, 1 = Lunes
		// startDate: new Date('11/17/2020'), // deshabilitar dias anteriores con fecha
		// startDate: '-3d', // deshabilitar dias anteriores del dia actual
		// endDate: '+3d', //deshabilitar dias despues del dia actual
		calendarWeeks: true,
		autoclose: true,
		todayHighlight: true, //Dia de hoy marcado en el calendario
		toggleActive: true,
		// setDate: new Date('11/17/2020'), // "2020/11/25", //Fecha marcada en el caledario
		forceParse: false, //mantiene la fecha del input si no se selecciona otra
		showOnFocus: true
	});

	// Si selecciona un campo tipo datepicker
	$('.mydatepicker').on('click', function () {
		$(this).datepicker('setDate', $(this).val());// Mostrar fecha del input y marcar en el calendario
	});
});


//=================================================
// DATOS GENERALES


var datosgenerales_ejecusiones = 0;
function datosgenerales() {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidodatosgenerales/" + proyecto.id + "/" + agente_id + "/" + agente_nombre,
		data: {},
		cache: false,
		success: function (dato) {
			reporteregistro_id = 0;
			if (parseInt(dato.reporteregistro_id) > 0) {
				reporteregistro_id = parseInt(dato.reporteregistro_id);
				// $('#reporteregistro_id').html(reporteregistro_id);
			}

			if (parseInt(dato.reporte_concluido) == 1 || parseInt(dato.reporte_cancelado) == 1) {
				botoninforme_estado(1);
			}
			else {
				botoninforme_estado(0);
			}


			if (parseInt(dato.recsensorial_tipocliente) == 1) //1= pemex, 0 =  cliente
			{
				$('#reporte_catsubdireccion_activo').prop('checked', dato.reporte_portada.reporte_catsubdireccion_activo);
				$('#reporte_catsubdireccion_id').val(dato.reporte_portada.catsubdireccion_id);
				$('#reporte_catgerencia_activo').prop('checked', dato.reporte_portada.reporte_catgerencia_activo);
				$('#reporte_catgerencia_id').val(dato.reporte_portada.catgerencia_id);
				$('#reporte_catactivo_activo').prop('checked', dato.reporte_portada.reporte_catactivo_activo);
				$('#reporte_catactivo_id').val(dato.reporte_portada.catactivo_id);
				$('#reporte_catregion_activo').prop('checked', dato.reporte_portada.reporte_catregion_activo);
				$('#reporte_catregion_id').val(dato.reporte_portada.catregion_id);
			}
			else {
				$('#reporte_catsubdireccion_id').val('');
				$('#reporte_catgerencia_id').val('');
				$('#reporte_catactivo_id').val('');
				$('#reporte_catregion_id').val('');

				$('#reporte_catsubdireccion_activo').prop('checked', 0);
				$('#reporte_catgerencia_activo').prop('checked', 0);
				$('#reporte_catactivo_activo').prop('checked', 0);
				$('#reporte_catregion_activo').prop('checked', 0);
			}

			$('#reporte_instalacion').val(dato.reporte_portada.reporte_instalacion);
			$('#reporte_fecha').val(dato.reporte_portada.reporte_fecha);

			$('#reporte_mes').val(dato.reporte_portada.reporte_mes);

			$('#reporteruido_lmpe').val(dato.reporteruido_lmpe);

			$('.div_instalacion_nombre').html(dato.reporte_portada.reporte_instalacion);

			$('#reporte_introduccion').html(dato.reporte_introduccion);
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_metodologia_4_1').html(dato.reporte_metodologia_4_1);
			$('#reporte_metodologia_4_2').html(dato.reporte_metodologia_4_2);

			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			if (dato.reporte_ubicacioninstalacion.ubicacionfoto) {
				var archivo = dato.reporte_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteruidomapaubicacion/' + reporteregistro_id + '/' + 0;
				$('#reporteubicacionfoto').dropify().data('dropify').destroy();
				$('#reporteubicacionfoto').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reporteubicacionfoto').dropify().data('dropify').init();
				$('#reporteubicacionfoto').attr('required', false);

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'block');
			}
			else {
				$('#reporteubicacionfoto').val('');
				$('#reporteubicacionfoto').attr('required', true);
				$('#reporteubicacionfoto').dropify().data('dropify').resetPreview();
				$('#reporteubicacionfoto').dropify().data('dropify').clearElement();

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'none');
			}

			$('#reporte_procesoinstalacion').html(dato.reporte_procesoinstalacion);
			$('#reporte_actividadprincipal').html(dato.reporte_actividadprincipal);
			$('#reporte_metodoevaluacion').html(dato.reporte_metodoevaluacion);
			$('#reporte_conclusion').html(dato.reporte_conclusion);

			if (dato.reporte_responsablesinforme.responsable1) {
				// Responsable 1
				$('#reporte_responsable1').val(dato.reporte_responsablesinforme.responsable1);
				$('#reporte_responsable1cargo').val(dato.reporte_responsablesinforme.responsable1cargo);
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteruidoresponsabledocumento/' + dato.reporte_responsablesinforme.registro_id + '/' + 1 + '/' + 0;
				$('#reporteresponsable1documento').dropify().data('dropify').destroy();
				$('#reporteresponsable1documento').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reporteresponsable1documento').dropify().data('dropify').init();
				$('#reporteresponsable1documento').attr('required', false);

				// Responsable 2
				$('#reporte_responsable2').val(dato.reporte_responsablesinforme.responsable2);
				$('#reporte_responsable2cargo').val(dato.reporte_responsablesinforme.responsable2cargo);
				$('#reporte_responsable2_documentobase64').val('');
				$('#reporteresponsable2documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable2documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteruidoresponsabledocumento/' + dato.reporte_responsablesinforme.registro_id + '/' + 2 + '/' + 0;
				$('#reporteresponsable2documento').dropify().data('dropify').destroy();
				$('#reporteresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reporteresponsable2documento').dropify().data('dropify').init();
				$('#reporteresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#responsablesinforme_carpetadocumentoshistorial').val('reportes/proyecto/' + dato.reporte_responsablesinforme.proyecto_id + '/' + agente_nombre + '/' + dato.reporte_responsablesinforme.registro_id + '/responsables informe');
				if (parseInt(dato.reporte_responsablesinforme.proyecto_id) == parseInt(proyecto.id)) {
					$('#boton_descargarresponsabledoc1').css('display', 'block');
					$('#boton_descargarresponsabledoc2').css('display', 'block');
					$('#responsablesinforme_carpetadocumentoshistorial').val('');
				}
			}
			else {
				$('#reporte_responsable1').val('');
				$('#reporte_responsable1cargo').val('');
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				$('#reporteresponsable1documento').attr('required', true);
				$('#reporteresponsable1documento').dropify().data('dropify').resetPreview();
				$('#reporteresponsable1documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc1').css('display', 'none');

				$('#reporte_responsable2').val('');
				$('#reporte_responsable2cargo').val('');
				$('#reporte_responsable2_documentobase64').val('');
				$('#reporteresponsable2documento').val('');
				$('#reporteresponsable2documento').attr('required', true);
				$('#reporteresponsable2documento').dropify().data('dropify').resetPreview();
				$('#reporteresponsable2documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc2').css('display', 'none');

				// Carpeta ubicacion documentos historial
				$('#responsablesinforme_carpetadocumentoshistorial').val('');
			}

			$('#memoriafotografica_total').html(dato.reporte_memoriafotografica_guardado);

			// ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reporte_portada_guardado);
			menureporte_estado("menureporte_1", dato.reporte_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reporte_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reporte_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4_1", dato.reporte_metodologia_4_1_guardado);
			menureporte_estado("menureporte_4_2", dato.reporte_metodologia_4_2_guardado);
			menureporte_estado("menureporte_5_1", dato.reporte_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_5_2", dato.reporte_procesoinstalacion_guardado);
			menureporte_estado("menureporte_6_3", dato.reporte_metodoevaluacion_guardado);
			menureporte_estado("menureporte_8", dato.reporte_conclusion_guardado);
			menureporte_estado("menureporte_10", dato.reporte_responsablesinforme_guardado);
			menureporte_estado("menureporte_11_1", dato.reporte_memoriafotografica_guardado);


			//Bloquear botones nuevas categorias y nuevas areas si este proyecto tiene POE general
			//-------------------------------------------------------------------------------------

			// if (categorias_poe == 1)
			// {
			// 	setTimeout(function()
			// 	{
			// 		$("#boton_reporte_nuevacategoria").attr('disabled', true);
			// 	}, 5000);
			// }


			if (areas_poe == 1) {
				setTimeout(function () {
					$("#boton_reporte_nuevacategoria").attr('disabled', true);
					$("#boton_reporte_nuevaarea").attr('disabled', true);
				}, 5000);
			}


			if (areas_poe == 1) {
				setTimeout(function () {
					$("#boton_reporte_nuevacategoria").attr('disabled', true);
					$("#boton_reporte_nuevaarea").attr('disabled', true);
				}, 10000);
			}
		},
		// beforeSend: function()
		// {
		// 	// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		// },
		error: function (dato) {
			if (datosgenerales_ejecusiones == 0) {
				datosgenerales();
				datosgenerales_ejecusiones += 1;
			}

			reporte_id = 0;

			$('#reporte_instalacion').val('Error al cargar los datos');
			$('#reporte_fecha').val('Error al cargar los datos');
			$('#reporte_mes').val('Error al cargar los datos');

			$('#reporte_introduccion').html('Error al cargar los datos');
			$('#reporte_objetivogeneral').html('Error al cargar los datos');
			$('#reporte_objetivoespecifico').html('Error al cargar los datos');
			$('#reporte_metodologia_4_1').html('Error al cargar los datos');
			$('#reporte_metodologia_4_2').html('Error al cargar los datos');
			$('#reporte_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reporte_procesoinstalacion').html('Error al cargar los datos');
			$('#reporteruido_metodoevaluacion').html('Error al cargar los datos');
			$('#reporte_conclusion').html('Error al cargar los datos');

			return false;
		}
	});//Fin ajax
}


var portada = ''
function portadaInfo() {
	$.ajax({
		url: 'portadaInfo/' + proyecto.id + '/' + agente_id,
		type: 'GET',
		dataType: 'json',
		data: {},
		cache: false,
		success: function (dato) {

			portada = dato.data[0]

		},
		error: function (dato) {
			console.log('Error al cargar los datos');
		}
	})

}


function menureporte_estado(menu_nombre, menu_estado) {
	if (parseInt(menu_estado) > 0) {
		$('#' + menu_nombre).css('color', '#64bd44'); // Verde

		$('#' + menu_nombre).removeClass('fa fa-times');
		$('#' + menu_nombre).addClass('fa fa-check');
	}
	else {
		$('#' + menu_nombre).css('color', '#fc4b6c'); // Rojo

		$('#' + menu_nombre).removeClass('fa fa-check');
		$('#' + menu_nombre).addClass('fa fa-times');
	}
}


function instalacion_nombre(reporte_instalacion) {
	$('.div_instalacion_nombre').html(reporte_instalacion);
}


function redimencionar_foto(campo_file, campo_filehidden, boton_guardar) {
	// Bloquear boton guardar
	$('#' + boton_guardar).attr('disabled', true);
	$('#' + campo_filehidden).val('');

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById(campo_file).files;
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
			// ubicacionmapa = dataurl; //Guardar en una variable

			// Resultado
			$('#' + campo_filehidden).val(dataurl);

			// Desbloquear boton guardar
			$('#' + boton_guardar).attr('disabled', false);
		}, 100);
	}
}


//=================================================
// PORTADA


$("#botonguardar_reporte_portada").click(function () {
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme guardar la portada!",
			text: "",
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
					$('#form_reporte_portada').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 0,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_0", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_portada').html('Guardar portada <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_portada').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_portada').html('Guardando portada <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_portada').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_portada').html('Guardar portada <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_portada').attr('disabled', false);

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


//=================================================
// INTRODUCCION


$("#botonguardar_reporte_introduccion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Introducción",
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
					$('#form_reporte_introduccion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 1,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_1", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_introduccion').html('Guardar introducción <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_introduccion').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_introduccion').html('Guardando introducción <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_introduccion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_introduccion').html('Guardar introducción <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_introduccion').attr('disabled', false);

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


//=================================================
// DEFINICIONES


$(document).ready(function () {
	obtenerdatos()

	setTimeout(function () {
		tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id) {
	try {
		var ruta = "/reporteruidotabladefiniciones/" + proyecto_id + "/" + agente_nombre + "/" + reporteregistro_id;

		if (datatable_reportedefiniciones != null) {
			datatable_reportedefiniciones.clear().draw();
			datatable_reportedefiniciones.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reportedefiniciones = $('#tabla_reporte_definiciones').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reportedefiniciones');
						if (numeroejecucion <= 1) {
							tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id);
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
						"data": "concepto",
						"defaultContent": "-"
					},
					{
						"data": "descripcion_fuente",
						"defaultContent": "-"
					},
					{
						"className": 'editar',
						"orderable": false,
						"data": 'boton_editar',
						"defaultContent": '-'
					}
					,
					{
						// "className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"paging": false,
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

		// Tooltip en DataTable
		datatable_reportedefiniciones.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id);
	}
}


$("#boton_reporte_nuevadefinicion").click(function () {
	$('#form_modal_definicion').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reportedefiniciones_id').val(0);

	// Titulo del modal
	$('#modal_reporte_definicion .modal-title').html('Nueva definición');

	// mostrar modal
	$('#modal_reporte_definicion').modal({ backdrop: false });
});


$('#tabla_reporte_definiciones tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reportedefiniciones.row(tr);

	if (parseInt(row.data().catactivo_id) >= 0) {
		$('#form_modal_definicion').each(function () {
			this.reset();
		});

		// Campos Hidden
		$('#reportedefiniciones_id').val(row.data().id);

		// Llenar campos
		$('#reportedefiniciones_concepto').val(row.data().concepto);
		$('#reportedefiniciones_descripcion').val(row.data().descripcion);
		$('#reportedefiniciones_fuente').val(row.data().fuente);

		// Titulo del modal
		$('#modal_reporte_definicion .modal-title').html('Definición');

		// mostrar modal
		$('#modal_reporte_definicion').modal({ backdrop: false });
	}
});


$('#tabla_reporte_definiciones tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reportedefiniciones.row(tr);

	if (parseInt(row.data().catactivo_id) >= 0) {
		swal({
			title: "¡Confirme que desea eliminar!",
			text: "La definición: " + row.data().concepto,
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
						text: "La definición: " + row.data().concepto,
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
									url: "/reporteruidodefinicioneliminar/" + row.data().id,
									data: {},
									cache: false,
									success: function (dato) {
										// Actualizar tabla
										tabla_reporte_definiciones(proyecto.id, agente_nombre);

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
	}
});


$("#botonguardar_modal_definicion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Definición: " + $('#reportedefiniciones_concepto').val(),
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
					$('#form_modal_definicion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 2,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							// Actualizar tabla
							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_modal_definicion').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_definicion').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_definicion').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_definicion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_definicion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_definicion').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_definicion').attr('disabled', false);

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


//=================================================
// OBJETIVO GENERAL


$("#botonguardar_reporte_objetivogeneral").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Objetivo general",
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
					$('#form_reporte_objetivogeneral').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 3,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_3_1", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_objetivogeneral').html('Guardar objetivo general <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_objetivogeneral').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_objetivogeneral').html('Guardando objetivo general <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_objetivogeneral').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_objetivogeneral').html('Guardar objetivo general <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_objetivogeneral').attr('disabled', false);

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


//=================================================
// OBJETIVOS ESPECIFICOS


$("#botonguardar_reporte_objetivoespecifico").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Objetivos específicos",
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
					$('#form_reporte_objetivoespecifico').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 4,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_3_2", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_objetivoespecifico').html('Guardar objetivos específicos <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_objetivoespecifico').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_objetivoespecifico').html('Guardando objetivos específicos <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_objetivoespecifico').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_objetivoespecifico').html('Guardar objetivos específicos <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_objetivoespecifico').attr('disabled', false);

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


//=================================================
// METODOLOGÍA PUNTO 4.1


$("#botonguardar_reporte_metodologia_4_1").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.1",
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
					$('#form_reporte_metodologia_4_1').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 5,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_4_1", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_metodologia_4_1').html('Guardar metodología punto 4.1 <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodologia_4_1').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_metodologia_4_1').html('Guardando metodología punto 4.1 <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_metodologia_4_1').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_metodologia_4_1').html('Guardar metodología punto 4.1 <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodologia_4_1').attr('disabled', false);

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


//=================================================
// METODOLOGÍA PUNTO 4.2


$("#botonguardar_reporte_metodologia_4_2").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.2",
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
					$('#form_reporte_metodologia_4_2').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 6,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_4_2", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_metodologia_4_2').html('Guardar metodología punto 4.2 <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodologia_4_2').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_metodologia_4_2').html('Guardando metodología punto 4.2 <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_metodologia_4_2').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_metodologia_4_2').html('Guardar metodología punto 4.2 <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodologia_4_2').attr('disabled', false);

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


//=================================================
// UBICACION


var ubicacionmapa = '';


$(document).ready(function () {
	$('#reporteubicacionfoto').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error:
		{
			'fileSize': 'Archivo demasiado grande.',
			'minWidth': 'Ancho demasiado pequeño.',
			'maxWidth': 'Ancho demasiado grande.',
			'minHeight': 'Alto demasiado pequeño.',
			'maxHeight': 'Alto demasiado grande.',
			'imageFormat': 'Formato no permitido, sólo (JPG, PNG).'
		}
	});
});


function redimencionar_mapaubicacion() {
	// Bloquear boton
	$('#botonguardar_reporte_ubicacion').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('reporteubicacionfoto').files;
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
			ubicacionmapa = dataurl; //Guardar en una variable

			// Desbloquear boton
			$('#botonguardar_reporte_ubicacion').attr('disabled', false);
		}, 100);
	}
}


$("#botonguardar_reporte_ubicacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Datos de la ubicación",
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
					$('#form_reporte_ubicacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 7,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
							ubicacionmapa: ubicacionmapa
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_5_1", 1);

							// tabla_reporte_revisiones(proyecto.id);

							$('#reporteubicacionfoto').val('');
							$('#reporteubicacionfoto').attr('required', false);
							$('#boton_descargarmapaubicacion').css('display', 'block');

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
							$('#botonguardar_reporte_ubicacion').html('Guardar ubicación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_ubicacion').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_ubicacion').html('Guardando ubicación <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_ubicacion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_ubicacion').html('Guardar ubicación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_ubicacion').attr('disabled', false);

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


$("#boton_descargarmapaubicacion").click(function () {
	window.open('/reporteruidomapaubicacion/' + reporteregistro_id + '/' + 1);
});


//=================================================
// PROCESO INSTALACIÓN


$("#botonguardar_reporte_procesoinstalacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Proceso y actividad principal de la instalación",
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
					$('#form_reporte_procesoinstalacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 8,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_5_2", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_procesoinstalacion').html('Guardar proceso instalación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_procesoinstalacion').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_procesoinstalacion').html('Guardando proceso instalación <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_procesoinstalacion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_procesoinstalacion').html('Guardar proceso instalación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_procesoinstalacion').attr('disabled', false);

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


//=================================================
// CATEGORIAS


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_categorias(proyecto.id, reporteregistro_id);
	}, 2500);
});


var datatable_reportecategorias = null;
function tabla_reporte_categorias(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidocategorias/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reportecategorias != null) {
			datatable_reportecategorias.clear().draw();
			datatable_reportecategorias.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reportecategorias = $('#tabla_reporte_categoria').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						// {
						// 	menureporte_estado("menureporte_5_4", 1);
						// }
						// else
						// {
						// 	menureporte_estado("menureporte_5_4", 0);
						// }


						if (parseInt(areas_poe) == 1) {
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reportecategorias');
						if (numeroejecucion <= 1) {
							tabla_reporte_categorias(proyecto_id, reporteregistro_id);
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
						"data": "reporteruidocategoria_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reporteruidocategoria_total",
						"defaultContent": "-"
					},
					{
						// "className": 'editar',
						"orderable": false,
						"data": 'boton_editar',
						"defaultContent": '-'
					},
					{
						// "className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"paging": true,
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

		// Tooltip en DataTable
		datatable_reportecategorias.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_categorias(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_nuevacategoria").click(function () {
	$('#form_modal_categoria').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reportecategoria_id').val(0);

	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html('Nueva categoría');

	// mostrar modal
	$('#modal_reporte_categoria').modal({ backdrop: false });
});


$('#tabla_reporte_categoria tbody').on('click', 'td>button.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);


	$('#form_modal_categoria').each(function () {
		this.reset();
	});


	// Campos Hidden
	$('#reportecategoria_id').val(row.data().id);

	// Llenar campos
	$('#reporteruidocategoria_nombre').val(row.data().reporteruidocategoria_nombre);
	$('#reporteruidocategoria_total').val(row.data().reporteruidocategoria_total);


	if (areas_poe == 1) {
		$('#reporteruidocategoria_nombre').attr('required', false);
		$('#reporteruidocategoria_nombre').attr('disabled', true);

		$('#reporteruidocategoria_total').attr('required', false);
		$('#reporteruidocategoria_total').attr('disabled', true);
	}
	else {
		$('#reporteruidocategoria_nombre').attr('disabled', false);
		$('#reporteruidocategoria_nombre').attr('required', true);

		$('#reporteruidocategoria_total').attr('disabled', false);
		$('#reporteruidocategoria_total').attr('required', true);
	}


	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html('Categoría');


	// mostrar modal
	$('#modal_reporte_categoria').modal({ backdrop: false });
});


$('#tabla_reporte_categoria tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "La categoría: " + row.data().reporteruidocategoria_nombre,
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
					text: "La categoría: " + row.data().reporteruidocategoria_nombre,
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
								url: "/reporteruidocategoriaeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_categorias(proyecto.id, reporteregistro_id);

									// Tablas datos del reporte
									// tabla_reporte_areas(proyecto.id, reporteregistro_id);
									// tabla_reporte_5_4(proyecto.id, reporteregistro_id);
									// tabla_reporte_5_5(proyecto.id, reporteregistro_id);
									// tabla_reporte_6_1(proyecto.id, reporteregistro_id);
									// tabla_reporte_6_2_1(proyecto.id, reporteregistro_id);
									// tabla_reporte_6_2_2(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_categoria").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Categoría: " + $("#reporteruidocategoria_nombre").val(),
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
					$('#form_modal_categoria').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 9,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							// tabla_reporte_revisiones(proyecto.id);

							// Actualizar tabla
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_categoria').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_categoria').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_categoria').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_categoria').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_categoria').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_categoria').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_categoria').attr('disabled', false);

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


//=================================================
// AREAS


var selectareas = '';


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_areas(proyecto.id, reporteregistro_id);
	}, 6000);
});


var datatable_reporteareas = null;
function tabla_reporte_areas(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidoareas/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reporteareas != null) {
			datatable_reporteareas.clear().draw();
			datatable_reporteareas.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporteareas = $('#tabla_reporte_area').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0) {
							menureporte_estado("menureporte_5_3", 1);
							menureporte_estado("menureporte_5_4", 1);
							menureporte_estado("menureporte_5_5", 1);
							menureporte_estado("menureporte_5_8", 1);
							menureporte_estado("menureporte_6_1", 1);
						}
						else {
							menureporte_estado("menureporte_5_3", 0);
							menureporte_estado("menureporte_5_4", 0);
							menureporte_estado("menureporte_5_5", 0);
							menureporte_estado("menureporte_5_8", 0);
							menureporte_estado("menureporte_6_1", 0);
						}


						if (parseInt(areas_poe) == 1) {
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						// Tablas
						tabla_reporte_5_3(json.tabla_5_3);
						tabla_reporte_5_5(json.tabla_5_5);
						tabla_reporte_5_8_UNO(json.tabla_5_8_UNO);
						tabla_reporte_5_8_DOS(json.tabla_5_8_DOS);
						tabla_reporte_6_1(json.tabla_6_1);


						// Actualizar select areas
						selectareas = json.selectareasoption;


						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reporteareas');
						if (numeroejecucion <= 1) {
							tabla_reporte_areas(proyecto_id, reporteregistro_id)
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
						"data": "reporteruidoarea_instalacion",
						"defaultContent": "-"
					},
					{
						"data": "area_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reportecategoria_nombre_texto",
						"defaultContent": "-"
					},
					{
						"className": 'editar',
						"orderable": false,
						"data": 'boton_editar',
						"defaultContent": '-'
					},
					{
						// "className": 'eliminar',
						"orderable": false,
						"data": 'boton_eliminar',
						"defaultContent": '-'
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				"rowsGroup": [1, 0, 2, 4, 5], //agrupar filas
				"order": [[0, "DESC"]],
				"ordering": false,
				"processing": true,
				"paging": true,
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

		// Tooltip en DataTable
		datatable_reporteareas.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_areas(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_nuevaarea").click(function () {
	$('#form_reporte_area').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reportearea_id').val(0);


	// Tabla fuentes generadoras
	$('#tabla_areamaquinaria tbody').html('<tr>' +
		'<td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" required></td>' +
		'<td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" required></td>' +
		'<td><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button></td>' +
		'</tr>');

	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidoareascategoriasmaquinaria/" + proyecto.id + "/" + reporteregistro_id + "/" + 0,
		data: {},
		cache: false,
		success: function (dato) {
			// $('#tabla_areamaquinaria tbody').html(dato.areamaquinaria);
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function () {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="3" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="3" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html('Nueva área');

	// mostrar modal
	$('#modal_reporte_area').modal({ backdrop: false });
});


function activa_areacategoria(checkbox, num_registro) {
	if (checkbox.checked) {
		$(".areacategoria_" + num_registro).attr('readonly', false);
		$(".areacategoria_" + num_registro).attr('required', true);
	}
	else {
		$(".areacategoria_" + num_registro).val('');
		$(".areacategoria_" + num_registro).attr('required', false);
		$(".areacategoria_" + num_registro).attr('readonly', true);
	}
}


var datatable_areacategoria = null;
function tabla_areacategorias(tbody) {
	if (datatable_areacategoria != null) {
		datatable_areacategoria.destroy();
	}

	$('#tabla_areacategorias tbody').html(tbody);

	datatable_areacategoria = $('#tabla_areacategorias').DataTable({
		"scrollY": "190px",
		"scrollCollapse": true,
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		// "rowsGroup": [0], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": true,
		"processing": true,
		"paging": false,
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


$("#botonnueva_areamaquina").click(function () {
	$('#tabla_areamaquinaria > tbody').append('<tr>' +
		'<td><input type="text" class="form-control" name="reporteruidoareamaquinaria_nombre[]" required></td>' +
		'<td><input type="number" min="1" class="form-control" name="reporteruidoareamaquinaria_cantidad[]" required></td>' +
		'<td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#div_tabla_areamaquinaria').animate({
		scrollTop: $('#tabla_areamaquinaria > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_areamaquinaria tbody').on('click', 'td>button.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar maquinaria?",
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
				text: "Maquinaria eliminada de la lista",
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


$('#tabla_reporte_area tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporteareas.row(tr);

	$('#form_reporte_area').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reportearea_id').val(row.data().id);

	// Llenar campos
	$('#reporteruidoarea_instalacion').val(row.data().reporteruidoarea_instalacion);
	$('#reporteruidoarea_nombre').val(row.data().reporteruidoarea_nombre);
	$('#reporteruidoarea_numorden').val(row.data().reporteruidoarea_numorden);
	$('#reporteruidoarea_proceso').val(row.data().reporteruidoarea_proceso);
	$('#reporteruidoarea_porcientooperacion').val(row.data().reporteruidoarea_porcientooperacion);
	$('#reporteruidoarea_tiporuido').val(row.data().reporteruidoarea_tiporuido);
	$('#reporteruidoarea_evaluacion').val(row.data().reporteruidoarea_evaluacion);
	$('#reporteruidoarea_LNI_1').val(row.data().reporteruidoarea_LNI_1);
	$('#reporteruidoarea_LNI_2').val(row.data().reporteruidoarea_LNI_2);
	$('#reporteruidoarea_LNI_3').val(row.data().reporteruidoarea_LNI_3);
	$('#reporteruidoarea_LNI_4').val(row.data().reporteruidoarea_LNI_4);
	$('#reporteruidoarea_LNI_5').val(row.data().reporteruidoarea_LNI_5);
	$('#reporteruidoarea_LNI_6').val(row.data().reporteruidoarea_LNI_6);
	$('#reporteruidoarea_LNI_7').val(row.data().reporteruidoarea_LNI_7);
	$('#reporteruidoarea_LNI_8').val(row.data().reporteruidoarea_LNI_8);
	$('#reporteruidoarea_LNI_9').val(row.data().reporteruidoarea_LNI_9);
	$('#reporteruidoarea_LNI_10').val(row.data().reporteruidoarea_LNI_10);



	if (areas_poe == 1) {
		$('#reporteruidoarea_instalacion').attr('required', false);
		$('#reporteruidoarea_instalacion').attr('disabled', true);

		$('#reporteruidoarea_nombre').attr('required', false);
		$('#reporteruidoarea_nombre').attr('disabled', true);

		$('#reporteruidoarea_numorden').attr('required', false);
		$('#reporteruidoarea_numorden').attr('disabled', true);

		// $('#reporteruidoarea_porcientooperacion').attr('required', false);
		// $('#reporteruidoarea_porcientooperacion').attr('disabled', true);
	}
	else {
		$('#reporteruidoarea_instalacion').attr('disabled', false);
		$('#reporteruidoarea_instalacion').attr('required', true);

		$('#reporteruidoarea_nombre').attr('disabled', false);
		$('#reporteruidoarea_nombre').attr('required', true);

		$('#reporteruidoarea_numorden').attr('disabled', false);
		$('#reporteruidoarea_numorden').attr('required', true);

		// $('#reporteruidoarea_porcientooperacion').attr('disabled', false);
		// $('#reporteruidoarea_porcientooperacion').attr('required', true);
	}



	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidoareascategoriasmaquinaria/" + proyecto.id + "/" + reporteregistro_id + "/" + row.data().id + "/" + areas_poe,
		data: {},
		cache: false,
		success: function (dato) {
			$('#tabla_areamaquinaria tbody').html(dato.areamaquinaria);
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function () {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="2" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			$('#tabla_areamaquinaria tbody').html('<tr><td colspan="3" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_areacategorias tbody').html('<tr><td colspan="2" style="text-align: center;">Error al cargar las categorías</td></tr>');
			$('#tabla_areamaquinaria tbody').html('<tr><td colspan="3" style="text-align: center;">Error al cargar las fuentes generadoras</td></tr>');
			return false;
		}
	});//Fin ajax


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html(row.data().reportearea_nombre);


	// mostrar modal
	$('#modal_reporte_area').modal({ backdrop: false });
});


$('#tabla_reporte_area tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporteareas.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El área: " + row.data().reporteruidoarea_nombre,
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
					text: "El área: " + row.data().reporteruidoarea_nombre,
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
								url: "/reporteruidoareaeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_areas(proyecto.id, reporteregistro_id);


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


$("#botonguardar_modal_area").click(function () {
	// borrar campo filtro del DATATABLE
	// datatable_areacategoria.search($(this).val()).draw();
	datatable_areacategoria.search("").draw();


	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Área: " + $("#reporteruidoarea_nombre").val(),
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
					$('#form_reporte_area').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 10,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
							areas_poe: areas_poe,
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							// Actualizar tabla
							tabla_reporte_areas(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_area').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_area').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_area').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_area').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_area').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_area').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_area').attr('disabled', false);

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


var datatable_reporte_5_3 = null;
function tabla_reporte_5_3(tbody) {
	if (datatable_reporte_5_3 != null) {
		datatable_reporte_5_3.destroy();
	}

	$('#tabla_reporte_5_3 tbody').html(tbody);

	datatable_reporte_5_3 = $('#tabla_reporte_5_3').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


var datatable_reporte_5_5 = null;
function tabla_reporte_5_5(tbody) {
	if (datatable_reporte_5_5 != null) {
		datatable_reporte_5_5.destroy();
	}

	$('#tabla_reporte_5_5 tbody').html(tbody);

	datatable_reporte_5_5 = $('#tabla_reporte_5_5').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0, 1], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


var datatable_reporte_5_8_UNO = null;
function tabla_reporte_5_8_UNO(tbody) {
	if (datatable_reporte_5_8_UNO != null) {
		datatable_reporte_5_8_UNO.destroy();
	}

	$('#tabla_reporte_5_8_UNO tbody').html(tbody);

	datatable_reporte_5_8_UNO = $('#tabla_reporte_5_8_UNO').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


var datatable_reporte_5_8_DOS = null;
function tabla_reporte_5_8_DOS(tbody) {
	if (datatable_reporte_5_8_DOS != null) {
		datatable_reporte_5_8_DOS.destroy();
	}

	$('#tabla_reporte_5_8_DOS tbody').html(tbody);

	datatable_reporte_5_8_DOS = $('#tabla_reporte_5_8_DOS').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0, 1], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


var datatable_reporte_6_1 = null;
function tabla_reporte_6_1(tbody) {
	if (datatable_reporte_6_1 != null) {
		datatable_reporte_6_1.destroy();
	}

	$('#tabla_reporte_6_1 tbody').html(tbody);

	datatable_reporte_6_1 = $('#tabla_reporte_6_1').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1, 3], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


//=================================================
// EQUIPO AUDITIVO


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
	}, 7000);
});


var datatable_reporte_5_6 = null;
function tabla_reporte_5_6(tbody) {
	if (datatable_reporte_5_6 != null) {
		datatable_reporte_5_6.destroy();
	}

	$('#tabla_reporte_5_6 tbody').html(tbody);

	datatable_reporte_5_6 = $('#tabla_reporte_5_6').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0, 1, 2, 3, 4], //agrupar filas
		"order": [[0, "DESC"]],
		"ordering": false,
		"searching": false,
		"processing": true,
		"paging": false,
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


var equipoauditivo_ejecuciones = 0;
function tabla_reporte_5_6_equipoauditivo(proyecto_id, reporteregistro_id) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidoequipoauditivotabla/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe,
		data: {},
		cache: false,
		success: function (dato) {
			menureporte_estado("menureporte_5_6", parseInt(dato.equipoauditivo_lista.length));
			menureporte_estado("menureporte_7_4", parseInt(dato.equipoauditivo_lista.length));

			$("#div_tablas_equiposautivos").html(dato.equiposauditivos_tablas);

			tabla_reporte_5_6(dato.tabla_5_6);

			// $("#equiposauditivos_datos").html(dato.equiposauditivos_datos);
		},
		error: function (dato) {
			if (equipoauditivo_ejecuciones == 0) {
				equipoauditivo_ejecuciones += 1;
				tabla_reporte_5_6_equipoauditivo(proyecto_id, reporteregistro_id);
			}

			$("#div_tablas_equiposautivos").html('<table class="table table-hover tabla_info_centrado" width="100%">' +
				'<thead' +
				'<tr' +
				'<th rowspan="2" width="200">Equipo</th' +
				'<th rowspan="2" width="80">NRR</th' +
				'<th>Atenuación por bandas de octava</th' +
				'</tr' +
				'<tr' +
				'<th>0</th' +
				'</tr' +
				'</thead' +
				'<tbody' +
				'<tr' +
				'<td colspan="3">Error al cargar los datos</th>' +
				'</tr' +
				'</tbody' +
				'</table>');

			// $("#equiposauditivos_datos").html('<p class="justificado">Error al consultar los equipos auditivos</p>');
		}
	});//Fin ajax
}


$("#boton_reporte_nuevoequipoauditivo").click(function () {
	$('#form_reporte_equipoauditivo').each(function () {
		this.reset();
	});

	$('#tabla_equipoauditivo_atenuaciones tbody').html('<tr>' +
		'<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaNRR[]" value="" required></td>' +
		'<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaatenuacion[]" value="" required></td>' +
		'<td width="10%"><button type="button" class="btn btn-default waves-effect btn-circle"><i class="fa fa-ban fa-2x"></i></button></td>' +
		'</tr>');

	// categorias
	equipoauditivo_categorias(proyecto_id, reporteregistro_id, 0);

	// Campos Hidden
	$('#reporteequipoauditivo_id').val(0);

	// Titulo del modal
	$('#modal_reporte_equipoauditivo .modal-title').html('Equipo auditivo');

	// mostrar modal
	$('#modal_reporte_equipoauditivo').modal({ backdrop: false });
});


$("#boton_equipoauditivo_nuevaatenuacion").click(function () {
	$('#tabla_equipoauditivo_atenuaciones > tbody').append('<tr>' +
		'<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaNRR[]" required></td>' +
		'<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivoatenuacion_bandaatenuacion[]" required></td>' +
		'<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#div_tabla_equipoauditivo_atenuaciones').animate({
		scrollTop: $('#tabla_equipoauditivo_atenuaciones > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_equipoauditivo_atenuaciones tbody').on('click', 'td>button.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar registro?",
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


function equipoauditivo_atenuaciones(equipoauditivo_id) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidoequipoauditivoatenuaciones/" + equipoauditivo_id,
		data: {},
		cache: false,
		success: function (dato) {
			$('#tabla_equipoauditivo_atenuaciones tbody').html(dato.atenuaciones);
		},
		beforeSend: function () {
			$('#tabla_equipoauditivo_atenuaciones tbody').html('<tr><td colspan="3"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_equipoauditivo_atenuaciones tbody').html('<tr><td colspan="3">Error al consultar los datos</td></tr>');
		}
	});//Fin ajax
}


function equipoauditivo_categorias(proyecto_id, reporteregistro_id, equipoauditivo_id) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidoequipoauditivocategorias/" + proyecto_id + "/" + reporteregistro_id + "/" + equipoauditivo_id + "/" + areas_poe,
		data: {},
		cache: false,
		success: function (dato) {
			$('#reporteequipoauditivo_categoriaslista').html(dato.equipoauditivocategorias_lista);
		},
		beforeSend: function () {
			$('#reporteequipoauditivo_categoriaslista').html('<i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i>');
		},
		error: function (dato) {
			$('#reporteequipoauditivo_categoriaslista').html('Error al consultar las categorías');
		}
	});//Fin ajax
}


function equipoauditivo_editar(proyecto_id, reporteregistro_id, equipoauditivo_id, equipoauditivo_tipo, equipoauditivo_marca, equipoauditivo_modelo, equipoauditivo_nrr) {
	$('#form_reporte_equipoauditivo').each(function () {
		this.reset();
	});

	$('#tabla_equipoauditivo_atenuaciones tbody').html('');

	// Campos Hidden
	$('#reporteequipoauditivo_id').val(equipoauditivo_id);

	// Llenar campos
	$('#reporteruidoequipoauditivo_tipo').val(equipoauditivo_tipo);
	$('#reporteruidoequipoauditivo_marca').val(equipoauditivo_marca);
	$('#reporteruidoequipoauditivo_modelo').val(equipoauditivo_modelo);
	$('#reporteruidoequipoauditivo_NRR').val(equipoauditivo_nrr);

	// Atenuaciones
	equipoauditivo_atenuaciones(equipoauditivo_id);

	// categorias
	equipoauditivo_categorias(proyecto_id, reporteregistro_id, equipoauditivo_id);

	// Titulo del modal
	$('#modal_reporte_equipoauditivo .modal-title').html('Equipo auditivo');

	// mostrar modal
	$('#modal_reporte_equipoauditivo').modal({ backdrop: false });
}


function equipoauditivo_eliminar(equipoauditivo_id, equipoauditivo_tipo) {
	swal({
		title: "¡Confirme que desea eliminar!",
		text: "" + equipoauditivo_tipo,
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
					text: "" + equipoauditivo_tipo,
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

							// alert(equipoauditivo_id);

							$.ajax({
								type: "GET",
								dataType: "json",
								url: "/reporteruidoequipoauditivoeliminar/" + equipoauditivo_id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);

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
}


$("#botonguardar_modal_equipoauditivo").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Equipo auditivo",
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
					$('#form_reporte_equipoauditivo').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 11,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);

							// tabla_reporte_7_5(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_equipoauditivo').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_equipoauditivo').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_equipoauditivo').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_equipoauditivo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_equipoauditivo').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_equipoauditivo').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_equipoauditivo').attr('disabled', false);

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


//=================================================
// EQUIPO PROTECCION PERSONAL


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_epp(proyecto.id, reporteregistro_id);
	}, 7000);
});


var datatable_epp = null;
function tabla_reporte_epp(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidoepptabla/" + proyecto_id + "/" + reporteregistro_id;

		if (datatable_epp != null) {
			datatable_epp.clear().draw();
			datatable_epp.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_epp = $('#tabla_reporte_epp').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_5_7", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_epp ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_epp(proyecto_id, reporteregistro_id)
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
						data: "reporteruidoepp_partecuerpo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoepp_equipo",
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
				// rowsGroup: [1, 2, 3], //agrupar filas
				// order: [[ 0, "ASC" ]],
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

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_epp.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_epp(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_nuevoepp").click(function () {
	$('#form_modal_epp').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reporteepp_id').val(0);

	// Titulo del modal
	$('#modal_reporte_epp .modal-title').html('Nueva equipo de protección personal');

	// mostrar modal
	$('#modal_reporte_epp').modal({ backdrop: false });
});


$('#tabla_reporte_epp tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_epp.row(tr);

	$('#form_modal_epp').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reporteepp_id').val(row.data().id);

	// Llenar campos
	$('#reporteruidoepp_partecuerpo').val(row.data().reporteruidoepp_partecuerpo);
	$('#reporteruidoepp_equipo').val(row.data().reporteruidoepp_equipo);

	// Titulo del modal
	$('#modal_reporte_epp .modal-title').html(row.data().reportearea_nombre);

	// mostrar modal
	$('#modal_reporte_epp').modal({ backdrop: false });
});


$('#tabla_reporte_epp tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_epp.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "E.P.P.: " + row.data().reporteruidoepp_partecuerpo,
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
					text: "E.P.P.: " + row.data().reporteruidoepp_partecuerpo,
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
								url: "/reporteruidoeppeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_epp(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_epp").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Equipo de protección personal",
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
					$('#form_modal_epp').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 12,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							tabla_reporte_epp(proyecto.id, reporteregistro_id);

							// Actualizar tabla
							// tabla_reporte_areas(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_epp').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_epp').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_epp').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_epp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_epp').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_epp').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_epp').attr('disabled', false);

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


//=================================================
// AREAS PUNTOS DE EVALUACION


var ubicaciones_opciones = '<option value=""></option>';


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_6_2(proyecto.id, reporteregistro_id);
	}, 6000);
});


var datatable_reporte_6_2 = null;
function tabla_reporte_6_2(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidoareaevaluaciontabla/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reporte_6_2 != null) {
			datatable_reporte_6_2.clear().draw();
			datatable_reporte_6_2.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporte_6_2 = $('#tabla_reporte_6_2').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						ubicaciones_opciones = json.ubicaciones_opciones;

						$('#areaevaluacion_totalpuntos').html(json.areaevaluacion_totalpuntos);

						menureporte_estado("menureporte_6_2", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_reporte_6_2 ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_6_2(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteruidoarea_instalacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "nomedicion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoareaevaluacion_ubicacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoareaevaluacion_noevaluaciones",
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
				rowsGroup: [0, 1, 4, 5, 6], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
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
		datatable_reporte_6_2.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_6_2(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_areaevaluacion").click(function () {
	$('#form_modal_areaevaluacion').each(function () {
		this.reset();
	});

	// Campos Hidden
	// $('#reporteareaevaluacion_id').val(0);

	// Campo select areas
	$('#reporteruidoarea_id').html(selectareas);

	// Tabla ubicaciones
	$('#tabla_areaevaluacion_ubicaciones > tbody').html('<tr>' +
		'<td width="30%">' +
		'<input type="number" class="form-control" style="width: 90px; float: left;" name="reporteruidoareaevaluacion_nomedicion1[]" required>' +
		'AL' +
		'<input type="number" class="form-control" style="width: 90px; float: right;" name="reporteruidoareaevaluacion_nomedicion2[]" required>' +
		'</td>' +
		'<td width="60%"><input type="text" class="form-control" name="reporteruidoareaevaluacion_ubicacion[]" required></td>' +
		'<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
		'</tr>');

	// Titulo del modal
	$('#modal_reporte_areaevaluacion .modal-title').html('Nueva área y puntos de evaluación');

	// mostrar modal
	$('#modal_reporte_areaevaluacion').modal({ backdrop: false });
});


$("#boton_areaevaluacion_nuevaubicacion").click(function () {
	$('#tabla_areaevaluacion_ubicaciones > tbody').append('<tr>' +
		'<td width="30%">' +
		'<input type="number" class="form-control" style="width: 90px; float: left;" name="reporteruidoareaevaluacion_nomedicion1[]" required>' +
		'AL' +
		'<input type="number" class="form-control" style="width: 90px; float: right;" name="reporteruidoareaevaluacion_nomedicion2[]" required>' +
		'</td>' +
		'<td width="60%"><input type="text" class="form-control" name="reporteruidoareaevaluacion_ubicacion[]" required></td>' +
		'<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#div_tabla_areaevaluacion_ubicaciones').animate({
		scrollTop: $('#tabla_areaevaluacion_ubicaciones > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_areaevaluacion_ubicaciones tbody').on('click', '.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar registro?",
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


$('#tabla_reporte_6_2 tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_6_2.row(tr);

	$('#form_modal_areaevaluacion').each(function () {
		this.reset();
	});

	// Campos Hidden
	// $('#reporteareaevaluacion_id').val(row.data().id);

	// Campo select areas
	$('#reporteruidoarea_id').html(selectareas);

	// Tabla ubicaciones
	$('#tabla_areaevaluacion_ubicaciones > tbody').html('');

	// Llenar campos
	$('#reporteruidoarea_id').val(row.data().reporteruidoarea_id);
	$('#reporteruidoareaevaluacion_noevaluaciones').val(row.data().reporteruidoareaevaluacion_noevaluaciones);

	// Recorrer tabla
	datatable_reporte_6_2.rows().every(function (rowIdx, tableLoop, rowLoop) {
		var data = this.data();

		if (parseInt(row.data().reporteruidoarea_id) == parseInt(data.reporteruidoarea_id)) {
			$('#tabla_areaevaluacion_ubicaciones > tbody').append('<tr>' +
				'<td width="30%">' +
				'<input type="number" class="form-control" style="width: 90px; float: left;" name="reporteruidoareaevaluacion_nomedicion1[]" value="' + data.reporteruidoareaevaluacion_nomedicion1 + '" required>' +
				'AL' +
				'<input type="number" class="form-control" style="width: 90px; float: right;" name="reporteruidoareaevaluacion_nomedicion2[]" value="' + data.reporteruidoareaevaluacion_nomedicion2 + '" required>' +
				'</td>' +
				'<td width="60%"><input type="text" class="form-control" name="reporteruidoareaevaluacion_ubicacion[]" value="' + data.reporteruidoareaevaluacion_ubicacion + '" required></td>' +
				'<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
				'</tr>');
		}
	});

	// Titulo del modal
	$('#modal_reporte_areaevaluacion .modal-title').html('Área y puntos de evaluación');

	// mostrar modal
	$('#modal_reporte_areaevaluacion').modal({ backdrop: false });
});


$('#tabla_reporte_6_2 tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_6_2.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "" + row.data().reporteruidoarea_nombre + " y puntos de evaluación",
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
					text: "" + row.data().reporteruidoarea_nombre + " y puntos de evaluación",
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
								url: "/reporteruidoareaevaluacioneliminar/" + proyecto.id + "/" + reporteregistro_id + "/" + row.data().reporteruidoarea_id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_6_2(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_areaevaluacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Área y puntos de evaluación",
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
					$('#form_modal_areaevaluacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 13,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							// Actualizar tabla
							tabla_reporte_6_2(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_areaevaluacion').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_areaevaluacion').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_areaevaluacion').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_areaevaluacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_areaevaluacion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_areaevaluacion').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_areaevaluacion').attr('disabled', false);

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


//=================================================
// METODO DE EVALUACION


$("#botonguardar_reporte_metodoevaluacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Proceso y actividad principal de la instalación",
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
					$('#form_reporte_metodoevaluacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 14,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_6_3", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_metodoevaluacion').html('Guardar método de evaluación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodoevaluacion').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_metodoevaluacion').html('Guardando método de evaluación <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_metodoevaluacion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_metodoevaluacion').html('Guardar método de evaluación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_metodoevaluacion').attr('disabled', false);

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


//=================================================
// NIVEL SONORO CONTINUO


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_7_1(proyecto.id, reporteregistro_id);
	}, 7000);
});


var tabla_reporte_7_1_ejecuciones = 0;
var datatable_reporte_7_1 = null;
function tabla_reporte_7_1(proyecto_id, reporteregistro_id) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidonivelsonorotabla/" + proyecto_id + "/" + reporteregistro_id,
		data: {},
		cache: false,
		success: function (dato) {
			menureporte_estado("menureporte_7_1", parseInt(dato.total));

			if (parseInt(dato.total) > 0) {
				if (datatable_reporte_7_1 != null) {
					datatable_reporte_7_1.destroy();
				}

				$('#tabla_reporte_7_1').html(dato.nivelsonoro_tabla);

				datatable_reporte_7_1 = $('#tabla_reporte_7_1').DataTable({
					"lengthMenu": [[50, 80, 100, -1], [50, 80, 100, "Todos"]],
					"rowsGroup": [0, 1, 7, 8, 9], //agrupar filas
					"order": [[0, "DESC"]],
					"ordering": false,
					"searching": false,
					"processing": true,
					"paging": true,
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
			else {
				$('#tabla_reporte_7_1').html(dato.nivelsonoro_tabla);
			}

			tabla_reporte_7_1_ejecuciones = 0;
		},
		error: function (dato) {
			if (tabla_reporte_7_1_ejecuciones == 0) {
				tabla_reporte_7_1_ejecuciones += 1;
				tabla_reporte_7_1(proyecto_id, reporteregistro_id);
			}

			if (datatable_reporte_7_1 != null) {
				datatable_reporte_7_1.destroy();
			}

			$("#tabla_reporte_7_1").html('<thead>' +
				'<tr>' +
				'<th width="100">No. Medición</th>' +
				'<th>Ubicación</th>' +
				'<th width="100">Periodo 1</th>' +
				'<th width="100">Periodo 2</th>' +
				'<th width="100">NSCE<sub>A, Ti</sub><br>Promedio</th>' +
				'</tr>' +
				'</thead>' +
				'<tbody>' +
				'<tr>' +
				'<td colspan="5">Error al consultar los datos</td>' +
				'</tr>' +
				'</tbody>');
		}
	});//Fin ajax
}


$("#boton_reporte_nuevonivelsonoro").click(function () {
	$('#form_modal_nivelsonoro').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#reportenivelsonoro_punto').val(0);

	// Inicializar Tabla
	$('#tabla_nivelsonoro > tbody').html('');

	// campos de solo lectura
	$('#reporteruidonivelsonoro_totalperiodos').attr('readonly', false);
	$('#reporteruidonivelsonoro_totalresultados').attr('readonly', false);
	$('#boton_totalnivelsonoro').attr('disabled', false);

	// Campo ubicaciones
	$('#reporteruidonivelsonoro_ubicacion').html(ubicaciones_opciones);

	// Titulo del modal
	$('#modal_reporte_nivelsonoro .modal-title').html('Punto de medición de nivel sonoro continuo');

	// mostrar modal
	$('#modal_reporte_nivelsonoro').modal({ backdrop: false });
});


$("#boton_totalnivelsonoro").click(function () {
	if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) > 0 && parseInt($('#reporteruidonivelsonoro_totalresultados').val()) > 0) {
		$('#tabla_nivelsonoro > tbody').html('');

		var periodo1_estado = 'disabled';
		var periodo2_estado = 'disabled';
		var periodo3_estado = 'disabled';
		var periodo4_estado = 'disabled';
		var periodo5_estado = 'disabled';

		if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) >= 1) { periodo1_estado = 'required'; }
		if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) >= 2) { periodo2_estado = 'required'; }
		if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) >= 3) { periodo3_estado = 'required'; }
		if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) >= 4) { periodo4_estado = 'required'; }
		if (parseInt($('#reporteruidonivelsonoro_totalperiodos').val()) >= 5) { periodo5_estado = 'required'; }

		for (var i = 0; i < parseInt($('#reporteruidonivelsonoro_totalresultados').val()); i++) {
			$('#tabla_nivelsonoro > tbody').append('<tr>' +
				'<td width="60">' + (i + 1) + '</td>' +
				'<td><input type="number" step="any" min="0" class="form-control nivel_sonoro_campo" name="reporteruidonivelsonoro_periodo1[]" value="" ' + periodo1_estado + '></td>' +
				'<td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo2[]" value="" ' + periodo2_estado + '></td>' +
				'<td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo3[]" value="" ' + periodo3_estado + '></td>' +
				'<td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo4[]" value="" ' + periodo4_estado + '></td>' +
				'<td><input type="number" step="any" min="0" class="form-control" name="reporteruidonivelsonoro_periodo5[]" value="" ' + periodo5_estado + '></td>' +
				'</tr>');
		}
	}
	else {
		alert('El campo "Total periodos" y "Total resultados" debe contener un valor mayor a 0');

		if ($('#reporteruidonivelsonoro_totalresultados').val() == "") {
			$('#reporteruidonivelsonoro_totalresultados').focus();
		}

		if ($('#reporteruidonivelsonoro_totalperiodos').val() == "") {
			$('#reporteruidonivelsonoro_totalperiodos').focus();
		}
	}
});


function nivelsonoro_editar(proyecto_id, reporteregistro_id, nivelsonoro_punto) {
	$('#form_modal_nivelsonoro').each(function () {
		this.reset();
	});

	// Campo ubicaciones
	$('#reporteruidonivelsonoro_ubicacion').html(ubicaciones_opciones);

	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidonivelsonoroconsultapunto/" + proyecto_id + "/" + reporteregistro_id + "/" + nivelsonoro_punto,
		data: {},
		cache: false,
		success: function (dato) {
			// Campos Hidden
			$('#reportenivelsonoro_punto').val(dato.nivelsonororesultados[0]);

			// Llenar campos
			$('#reporteruidonivelsonoro_punto').val(dato.nivelsonororesultados[0]);
			$('#reporteruidonivelsonoro_ubicacion').val(dato.nivelsonororesultados[1]);
			$('#reporteruidonivelsonoro_promedio').val(dato.nivelsonororesultados[2]);
			$('#reporteruidonivelsonoro_totalperiodos').val(dato.nivelsonororesultados[3]);
			$('#reporteruidonivelsonoro_totalresultados').val(dato.nivelsonororesultados[4]);

			// campos de solo lectura
			$('#reporteruidonivelsonoro_totalperiodos').attr('readonly', true);
			$('#reporteruidonivelsonoro_totalresultados').attr('readonly', true);
			$('#boton_totalnivelsonoro').attr('disabled', true);

			// Tabla resultados
			$('#tabla_nivelsonoro > tbody').html(dato.nivelsonororesultados_tablafilas);
		},
		beforeSend: function () {
			$('#tabla_nivelsonoro > tbody').html('<tr><td colspan="6"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function (dato) {
			$('#tabla_nivelsonoro > tbody').html('<tr><td colspan="6">Error al consultar los datos</td></tr>');
		}
	});//Fin ajax

	// Titulo del modal
	$('#modal_reporte_nivelsonoro .modal-title').html('Punto de medición de nivel sonoro continuo');

	// mostrar modal
	$('#modal_reporte_nivelsonoro').modal({ backdrop: false });
}


function nivelsonoro_eliminar(proyecto_id, reporteregistro_id, nivelsonoro_punto) {
	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Punto [" + nivelsonoro_punto + "] de medición de nivel sonoro continuo",
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
					text: "Punto [" + nivelsonoro_punto + "] de medición de nivel sonoro continuo",
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
								url: "/reporteruidonivelsonoroeliminar/" + proyecto_id + "/" + reporteregistro_id + "/" + nivelsonoro_punto,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_7_1(proyecto_id, reporteregistro_id);

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
}


$("#botonguardar_modal_nivelsonoro").click(function () {
	// valida campos vacios
	var total_campos = 0;
	$('.nivel_sonoro_campo').each(function () {
		total_campos += 1;
	});


	if (total_campos > 0) {
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Punto de medición y resultados del nivel sonoro continuo",
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
						$('#form_modal_nivelsonoro').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 15,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte						
								reporteregistro_id = dato.reporteregistro_id;

								tabla_reporte_7_1(proyecto.id, reporteregistro_id);

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
								$('#botonguardar_modal_nivelsonoro').html('Guardar <i class="fa fa-save"></i>');
								$('#botonguardar_modal_nivelsonoro').attr('disabled', false);

								// cerrar modal
								$('#modal_reporte_nivelsonoro').modal('hide');
							},
							beforeSend: function () {
								$('#botonguardar_modal_nivelsonoro').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#botonguardar_modal_nivelsonoro').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#botonguardar_modal_nivelsonoro').html('Guardar <i class="fa fa-save"></i>');
								$('#botonguardar_modal_nivelsonoro').attr('disabled', false);

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
	}
	else {
		$('#boton_totalnivelsonoro').click();
		return false;
	}
});


//=================================================
// TABLA DE RESULTADOS PUNTOS del NER


var selectcategorias = '';


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_7_2(proyecto.id, reporteregistro_id);
	}, 7500);
});


var datatable_reporte_7_2 = null;
function tabla_reporte_7_2(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidopuntonertabla/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reporte_7_2 != null) {
			datatable_reporte_7_2.clear().draw();
			datatable_reporte_7_2.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporte_7_2 = $('#tabla_reporte_7_2').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_7_2", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_reporte_7_2 ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_7_2(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteruidopuntoner_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_ubicacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_identificacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_ner",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_lmpe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_tmpe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "resultadoner_texto",
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
				rowsGroup: [1, 2], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
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
					$(row).find('td:eq(7)').css('background', data.resultadoner_color);

					if (data.resultadoner == 1) {
						$(row).find('td:eq(7)').css('color', '#000000');
						$(row).find('td:eq(7)').css('font-weight', 'bold');
					}
					else {
						$(row).find('td:eq(7)').css('color', '#FFFFFF');
						$(row).find('td:eq(7)').css('font-weight', 'bold');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_7_2.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_7_2(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_nuevopuntoner").click(function (e) {

	valor = $('#reporteruido_lmpe').val();

	if (valor == '') {

		e.preventDefault()
		swal({
			title: "No existe un LMPA dB(A) para evaluar.",
			text: "Por favor ingrese un valor en el campo de LMPA dB(A) para evaluar",
			type: "info",
			showConfirmButton: true
		});

	} else {

		$('#form_modal_puntoner').each(function () {
			this.reset();
		});

		// Campos Hidden
		$('#puntoner_id').val(0);

		// LLenar campos
		$('#reporteruidopuntoner_lmpe').val(valor);

		// Campo ubicaciones
		$('#reporteruidopuntoner_ubicacion').html(ubicaciones_opciones);

		// Campo select areas
		$('#reporteruidopuntoner_areaid').html(selectareas);
		mostrar_categoriasarea_puntoner(0, 0);

		// Campo select categorias
		$('#puntoner_categorias').html('');

		// Titulo del modal
		$('#modal_reporte_puntoner .modal-title').html('Punto de resultado de la determinación del NER');

		// mostrar modal
		$('#modal_reporte_puntoner').modal({ backdrop: false });

	}

});


function mostrar_categoriasarea_puntoner(area_id, puntoner_id) {
	if (parseInt(area_id) > 0) {
		$('#boton_puntoner_nuevacategoria').attr('disabled', false);

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/reporteruidopuntonerareacategorias/" + proyecto.id + "/" + reporteregistro_id + "/" + area_id + "/" + puntoner_id + "/" + areas_poe,
			data: {},
			cache: false,
			success: function (dato) {
				selectcategorias = dato.selectareacategorias_opciones;
				$('#tabla_puntoner_areacategorias > tbody').html(dato.categorias_puntoner);
				$('[data-toggle="tooltip"]').tooltip();
			},
			beforeSend: function () {
				$('#tabla_puntoner_areacategorias > tbody').html('<tr><td colspan="6"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function (dato) {
				selectcategorias = '<option value=""></option>';
				$('#tabla_puntoner_areacategorias > tbody').html('<tr><td colspan="6">Error al consultar las categorías, intentelo de nuevo.</td></tr>');
				return false;
			}
		});//Fin ajax
	}
	else {
		$('#boton_puntoner_nuevacategoria').attr('disabled', true);
		$('#tabla_puntoner_areacategorias > tbody').html('');
	}
}


$("#boton_puntoner_nuevacategoria").click(function () // Agregar fila categoria
{
	$('#tabla_puntoner_areacategorias > tbody').append('<tr>' +
		'<td width="319">' +
		'<select class="custom-select form-control" name="reporteruidocategoria_id[]" required>' +
		selectcategorias +
		'</select>' +
		'</td>' +
		'<td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_total[]" value="" required></td>' +
		'<td width="100"><input type="number" min="1" class="form-control" name="reporteruidopuntonercategorias_geo[]" value="" required></td>' +
		'<td width="120"><input type="text" class="form-control" name="reporteruidopuntonercategorias_ficha[]" value="" required></td>' +
		'<td><input type="text" class="form-control" name="reporteruidopuntonercategorias_nombre[]" value="" required></td>' +
		'<td width="60"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>' +
		'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#divtabla_puntoner_areacategorias').animate({
		scrollTop: $('#tabla_puntoner_areacategorias > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_puntoner_areacategorias tbody').on('click', 'td>button.eliminar', function () // Eliminar fila categoría
{
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar registro?",
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


$('#tabla_reporte_7_2 tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_2.row(tr);

	$('#form_modal_puntoner').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#puntoner_id').val(row.data().id);

	// Campo ubicaciones
	$('#reporteruidopuntoner_ubicacion').html(ubicaciones_opciones);

	// Campo select areas
	$('#reporteruidopuntoner_areaid').html(selectareas);

	// Llenar campos
	$('#reporteruidopuntoner_areaid').val(row.data().reporteruidoarea_id);
	mostrar_categoriasarea_puntoner(row.data().reporteruidoarea_id, row.data().id);
	$('#reporteruidopuntoner_ubicacion').val(row.data().reporteruidopuntoner_ubicacion);
	$('#reporteruidopuntoner_identificacion').val(row.data().reporteruidopuntoner_identificacion);
	$('#reporteruidopuntoner_punto').val(row.data().reporteruidopuntoner_punto);
	$('#reporteruidopuntoner_tmpe').val(row.data().reporteruidopuntoner_tmpe);
	$('#reporteruidopuntoner_ner').val(row.data().reporteruidopuntoner_ner);
	$('#reporteruidopuntoner_lmpe').val(row.data().reporteruidopuntoner_lmpe);

	// Titulo del modal
	$('#modal_reporte_puntoner .modal-title').html('Punto de resultado de la determinación del NER');

	// mostrar modal
	$('#modal_reporte_puntoner').modal({ backdrop: false });
});


$('#tabla_reporte_7_2 tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_2.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Punto [" + row.data().reporteruidopuntoner_punto + "] de resultado de la determinación del NER",
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
					text: "Punto [" + row.data().reporteruidopuntoner_punto + "] de resultado de la determinación del NER",
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
								url: "/reporteruidopuntonereliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_7_2(proyecto.id, reporteregistro_id);

									// tabla_reporte_7_5(proyecto.id, reporteregistro_id);

									tabla_reporte_7_6(proyecto.id, reporteregistro_id);

									tabla_reporte_7_7(proyecto.id, reporteregistro_id); // Matriz de exposicion

									reporte_dashboard(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_puntoner").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Punto de resultado de la determinación del NER",
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
					$('#form_modal_puntoner').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 16,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							tabla_reporte_7_2(proyecto.id, reporteregistro_id);

							// tabla_reporte_7_5(proyecto.id, reporteregistro_id);

							tabla_reporte_7_6(proyecto.id, reporteregistro_id);

							tabla_reporte_7_7(proyecto.id, reporteregistro_id); // Matriz de exposicion

							reporte_dashboard(proyecto.id, reporteregistro_id);


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
							$('#botonguardar_modal_puntoner').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_puntoner').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_puntoner').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_puntoner').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_puntoner').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_puntoner').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_puntoner').attr('disabled', false);

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


//=================================================
// TABLA DE RESULTADOS DOSIS del NER


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_7_3(proyecto.id, reporteregistro_id);
	}, 6000);
});


var datatable_reporte_7_3 = null;
function tabla_reporte_7_3(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidodosisnertabla/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reporte_7_3 != null) {
			datatable_reporte_7_3.clear().draw();
			datatable_reporte_7_3.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporte_7_3 = $('#tabla_reporte_7_3').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_7_3", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_reporte_7_3 ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_7_3(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteruidodosisner_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidocategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidodosisner_dosis",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidodosisner_ner",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidodosisner_lmpe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidodosisner_tmpe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "resultadoner_texto",
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
				rowsGroup: [1, 2], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
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
					$(row).find('td:eq(7)').css('background', data.resultadoner_color);

					if (data.resultadoner == 1) {
						$(row).find('td:eq(7)').css('color', '#000000');
						$(row).find('td:eq(7)').css('font-weight', 'bold');
					}
					else {
						$(row).find('td:eq(7)').css('color', '#FFFFFF');
						$(row).find('td:eq(7)').css('font-weight', 'bold');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_7_3.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_7_3(proyecto_id, reporteregistro_id);
	}
}


$("#boton_reporte_nuevadosisner").click(function (e) {

	valor = $('#reporteruido_lmpe').val();

	if (valor == '') {

		e.preventDefault()
		swal({
			title: "No existe un LMPA dB(A) para evaluar.",
			text: "Por favor ingrese un valor en el campo de LMPA dB(A) para evaluar",
			type: "info",
			showConfirmButton: true
		});
	} else {

		$('#form_modal_dosisner').each(function () {
			this.reset();
		});

		// Campos Hidden
		$('#dosisner_id').val(0);

		// LLenar campos
		$('#reporteruidodosisner_lmpe').val(valor);

		// Campo select areas
		$('#reporteruidodosisner_areaid').html(selectareas);

		// Campo select categorias
		$('#reporteruidodosisner_categoriaid').html('<option value=""></option>');

		// Titulo del modal
		$('#modal_reporte_dosisner .modal-title').html('Dosis de determinación del NER al personal');

		// mostrar modal
		$('#modal_reporte_dosisner').modal({ backdrop: false });
	}
});


function mostrar_categoriasarea(area_id, categoria_id, campo_nombre) {
	if (parseInt(area_id) > 0) {
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/reporteruidodosisnerareacategorias/" + proyecto.id + "/" + reporteregistro_id + "/" + area_id + "/" + categoria_id + "/" + areas_poe,
			data: {},
			cache: false,
			success: function (dato) {
				$('#' + campo_nombre).attr('disabled', false);
				$('#' + campo_nombre).html(dato.categoriasoption);
			},
			beforeSend: function () {
				$('#' + campo_nombre).html('<option value=""></option>');
				$('#' + campo_nombre).attr('disabled', true);
			},
			error: function (dato) {
				$('#' + campo_nombre).attr('disabled', false);
				$('#' + campo_nombre).html('<option value="">Error al consultar, intentelo de nuevo</option>');
				return false;
			}
		});//Fin ajax
	}
	else {
		$('#' + campo_nombre).html('<option value=""></option>');
	}
}


$('#tabla_reporte_7_3 tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_3.row(tr);

	$('#form_modal_dosisner').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#dosisner_id').val(row.data().id);

	// Campo select areas
	$('#reporteruidodosisner_areaid').html(selectareas);

	// Llenar campos
	$('#reporteruidodosisner_areaid').val(row.data().reporteruidoarea_id);
	// $('#reporteruidodosisner_categoriaid').val(row.data().reporteruidocategoria_id);
	mostrar_categoriasarea(row.data().reporteruidoarea_id, row.data().reporteruidocategoria_id, 'reporteruidodosisner_categoriaid');
	$('#reporteruidodosisner_punto').val(row.data().reporteruidodosisner_punto);
	$('#reporteruidodosisner_dosis').val(row.data().reporteruidodosisner_dosis);
	$('#reporteruidodosisner_ner').val(row.data().reporteruidodosisner_ner);
	$('#reporteruidodosisner_lmpe').val(row.data().reporteruidodosisner_lmpe);
	$('#reporteruidodosisner_tmpe').val(row.data().reporteruidodosisner_tmpe);

	// Titulo del modal
	$('#modal_reporte_dosisner .modal-title').html('Dosis de determinación del NER al personal');

	// mostrar modal
	$('#modal_reporte_dosisner').modal({ backdrop: false });
});


$('#tabla_reporte_7_3 tbody').on('click', 'td>button.eliminar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_3.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Dosis [" + row.data().reporteruidodosisner_punto + "] de determinación del NER al personal",
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
					text: "Dosis [" + row.data().reporteruidodosisner_punto + "] de determinación del NER al personal",
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
								url: "/reporteruidodosisnereliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_7_3(proyecto.id, reporteregistro_id);

									tabla_reporte_7_7(proyecto.id, reporteregistro_id); // Matriz de exposicion

									reporte_dashboard(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_dosisner").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Dosis de determinación del NER al personal",
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
					$('#form_modal_dosisner').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 17,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							tabla_reporte_7_3(proyecto.id, reporteregistro_id);

							tabla_reporte_7_7(proyecto.id, reporteregistro_id); // Matriz de exposicion

							reporte_dashboard(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_dosisner').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_dosisner').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_dosisner').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_dosisner').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_dosisner').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_dosisner').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_dosisner').attr('disabled', false);

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


//=================================================
// TABLA RESULTADOS DEL NIVEL DE RUIDO EFCTIVO (NRE)

/*
$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_7_5(proyecto.id, reporteregistro_id);
	}, 2000);
});


var tabla_reporte_7_5_ejecuciones = 0;
var datatable_reporte_7_5 = null;
function tabla_reporte_7_5(proyecto_id, reporteregistro_id)
{
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidonivelruidoefectivotabla/"+proyecto_id+"/"+reporteregistro_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			menureporte_estado("menureporte_7_5", parseInt(dato.total));

			if (parseInt(dato.total) > 0)
			{
				if (datatable_reporte_7_5 != null)
				{
					datatable_reporte_7_5.destroy();
				}

				$('#tabla_reporte_7_5').html(dato.tabla_7_5);

				datatable_reporte_7_5 = $('#tabla_reporte_7_5').DataTable({
					"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					"rowsGroup": [1, 0, 3], //agrupar filas
					// "rowsGroup": dato.columnas, //agrupar filas
					"order": [[ 0, "DESC" ]],
					"ordering": false,
					"searching": false,
					"processing": true,
					"paging": true,
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
			else
			{
				$('#tabla_reporte_7_5').html(dato.tabla_7_5);
			}

			tabla_reporte_7_5_ejecuciones = 0;
		},
		error: function(dato)
		{
			if (tabla_reporte_7_5_ejecuciones == 0)
			{
				tabla_reporte_7_5_ejecuciones += 1;
				tabla_reporte_7_5(proyecto_id, reporteregistro_id);
			}

			if (datatable_reporte_7_5 != null)
			{
				datatable_reporte_7_5.destroy();
			}

			$("#tabla_reporte_7_5").html('<thead>'+
												'<tr>'+
													'<th width="70">No.<br>medición</th>'+
													'<th>Área</th>'+
													'<th>Puesto</th>'+
													'<th width="70">NER<br>dB(A)</th>'+
													'<th width="70">NRE<br>dB(A)</th>'+
												'</tr>'+
											'</thead>'+
											'<tbody>'+
												'<tr>'+
													'<td colspan="5">Error al consultar los datos</td>'+
												'</tr>'+
											'</tbody>');
		}
	});//Fin ajax
}
*/

//=================================================
// TABLA 7.6 RESULTADOS DEL NIVEL DE RUIDO EFECTIVO (NRE) CON MODELO POR BANDAS DE OCTAVA


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_7_6(proyecto.id, reporteregistro_id);
	}, 6500);
});


var datatable_reporte_7_6 = null;
function tabla_reporte_7_6(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidobandasoctavatabla/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if (datatable_reporte_7_6 != null) {
			datatable_reporte_7_6.clear().draw();
			datatable_reporte_7_6.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporte_7_6 = $('#tabla_reporte_7_6').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_7_6", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_reporte_7_6 ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_7_6(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteruidopuntoner_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidoarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_ubicacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_identificacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntonerfrecuencias_frecuencia",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntonerfrecuencias_nivel",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_ner",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteruidopuntoner_RdB",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "resultado",
						defaultContent: "-",
						orderable: false,
					},
					{
						// data: "boton_editar",
						defaultContent: '<button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button>',
						className: 'editar',
						orderable: false,
					}
				],
				lengthMenu: [[18, 54, 90, -1], [18, 54, 90, "Todos"]],
				rowsGroup: [0, 1, 2, 3, 6, 7, 8, 9], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
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
					$(row).find('td:eq(8)').css('font-weight', 'bold');

					// if(data.resultadoner == 1)
					// {
					// 	$(row).find('td:eq(7)').css('color', '#000000');
					// 	$(row).find('td:eq(7)').css('font-weight', 'bold');
					// }
					// else
					// {
					// 	$(row).find('td:eq(7)').css('color', '#FFFFFF');
					// 	$(row).find('td:eq(7)').css('font-weight', 'bold');
					// }
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_7_6.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_7_6(proyecto_id, reporteregistro_id);
	}
}


$('#tabla_reporte_7_6 tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_6.row(tr);

	$('#form_modal_bandasoctava').each(function () {
		this.reset();
	});

	// // Campos Hidden
	$('#reporteruidopuntoner_id').val(row.data().id);

	// Llenar campos
	$('#reporteruidobandaoctava_punto').val(row.data().reporteruidopuntoner_punto);
	$('#reporteruidobandaoctava_area').val(row.data().reporteruidoarea_nombre);
	$('#reporteruidobandaoctava_ubicacion').val(row.data().reporteruidopuntoner_ubicacion);
	$('#reporteruidobandaoctava_identificacion').val(row.data().reporteruidopuntoner_identificacion);
	$('#reporteruidobandaoctava_ner').val(row.data().reporteruidopuntoner_ner);
	$('#reporteruidobandaoctava_RdB').val(row.data().reporteruidopuntoner_RdB);

	// Vaciar tabla frecuencias
	$('#tabla_bandasoctava_frecuencias > tbody').html('');

	// Recorrer tabla
	datatable_reporte_7_6.rows().every(function (rowIdx, tableLoop, rowLoop) {
		var data = this.data();

		if (parseInt(row.data().id) == parseInt(data.id)) {
			$('#tabla_bandasoctava_frecuencias > tbody').append('<tr>' +
				'<td>' +
				'<input type="text" class="form-control" name="reporteruidopuntonerfrecuencias_frecuencia[]" value="' + data.reporteruidopuntonerfrecuencias_frecuencia + '" readonly>' +
				'</td>' +
				'<td>' +
				'<input type="number" step="any" class="form-control" name="reporteruidopuntonerfrecuencias_nivel[]" value="' + data.reporteruidopuntonerfrecuencias_nivel + '" required>' +
				'</td>' +
				'</tr>');
		}
	});

	// // Titulo del modal
	$('#modal_reporte_bandasoctava .modal-title').html('Medición [' + row.data().reporteruidopuntoner_punto + '] del Nivel de Ruido Efectivo (NRE) con modelo por bandas de octava');

	// // mostrar modal
	$('#modal_reporte_bandasoctava').modal({ backdrop: false });
});


$("#botonguardar_modal_bandasoctava").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Ruido Efectivo (NRE) con modelo por bandas de octava",
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
					$('#form_modal_bandasoctava').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 18,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							tabla_reporte_7_6(proyecto.id, reporteregistro_id);

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
							$('#botonguardar_modal_bandasoctava').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_bandasoctava').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_bandasoctava').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_bandasoctava').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_bandasoctava').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_bandasoctava').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_bandasoctava').attr('disabled', false);

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


//=================================================
// TABLA 7.7 MATRIZ DE EXPOSICIÓN LABORAL


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_7_7(proyecto.id, reporteregistro_id);
	}, 7000);
});


var datatable_reporte_7_7 = null;
function tabla_reporte_7_7(proyecto_id, reporteregistro_id) {
	try {
		var ruta = "/reporteruidomatrizexposicion/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;


		if (parseInt(proyecto.catregion_id) == 1) //REGION NORTE
		{
			if (datatable_reporte_7_7 != null) {
				datatable_reporte_7_7.clear().draw();
				datatable_reporte_7_7.ajax.url(ruta).load();
			}
			else {
				$('#tabla_reporte_7_7').html('<thead>' +
					'<tr>' +
					'<th width="60">Contador</th>' +
					'<th width="120">Subdirección o<br>corporativo</th>' +
					'<th width="120">Gerencia o<br>activo</th>' +
					'<th width="120">Instalación</th>' +
					'<th width="120">Área de<br>referencia<br>en atlas<br>de riesgo</th>' +
					'<th width="">Nombre</th>' +
					'<th width="60">Ficha</th>' +
					'<th width="">Categoría</th>' +
					'<th width="60">Número de<br>personas</th>' +
					'<th width="60">Grupo de<br>exposición<br>homogénea</th>' +
					'<th width="60">Ruido<br>dB (A)</th>' +
					'<th width="60">Dosimetría</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_reporte_7_7 = $('#tabla_reporte_7_7').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json) {
							menureporte_estado("menureporte_7_7", parseInt(json.total));

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code) {
							console.log('error en datatable_reporte_7_7 ' + code);
							if (numeroejecucion <= 1) {
								tabla_reporte_7_7(proyecto_id, reporteregistro_id);
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
							data: "catsubdireccion_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "gerencia_activo",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidoarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidoarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteruidopuntonercategorias_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidopuntonercategorias_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteruidocategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidopuntonercategorias_total",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidopuntonercategorias_geo",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidopuntoner_ner",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "dosimentria",
							defaultContent: "-",
							orderable: false,
						}
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 4, 10, 11, 0], //agrupar filas
					order: [[0, "ASC"]],
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
			datatable_reporte_7_7.on('draw', function () {
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
		else {
			// alert('sur');

			if (datatable_reporte_7_7 != null) {
				datatable_reporte_7_7.clear().draw();
				datatable_reporte_7_7.ajax.url(ruta).load();
			}
			else {

				$('#tabla_reporte_7_7').html('<thead>' +
					'<tr>' +
					'<th width="60">Contador</th>' +
					'<th width="120">Subdirección o<br>corporativo</th>' +
					'<th width="120">Gerencia o<br>activo</th>' +
					'<th width="120">Instalación</th>' +
					'<th width="120">Área de referencia<br>en atlas de riesgo</th>' +
					'<th width="">Nombre</th>' +
					'<th width="50">Ficha</th>' +
					'<th width="">Categoría</th>' +
					'<th width="90">Ruido dB (A)<br>(medido/LMEP)</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_reporte_7_7 = $('#tabla_reporte_7_7').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json) {
							menureporte_estado("menureporte_7_7", parseInt(json.total));

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code) {
							console.log('error en datatable_reporte_7_7 ' + code);
							if (numeroejecucion <= 1) {
								tabla_reporte_7_7(proyecto_id, reporteregistro_id);
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
							data: "catsubdireccion_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "gerencia_activo",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidoarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidoarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteruidopuntonercategorias_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteruidopuntonercategorias_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteruidocategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "resultado",
							defaultContent: "-",
							orderable: false,
						},
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 4, 8, 0], //agrupar filas
					order: [[0, "ASC"]],
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
			datatable_reporte_7_7.on('draw', function () {
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
	}
	catch (exception) {
		tabla_reporte_7_7(proyecto_id, reporteregistro_id);
	}
}


//=================================================
// CONCLUSION


$("#botonguardar_reporte_conclusion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Conclusión",
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
					$('#form_reporte_conclusion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 19,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_8", 1);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_conclusion').html('Guardar conclusión <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_conclusion').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_conclusion').html('Guardando conclusión <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_conclusion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_conclusion').html('Guardar conclusión <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_conclusion').attr('disabled', false);

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


//=================================================
// DASHBOARD


$(document).ready(function () {
	setTimeout(function () {
		reporte_dashboard(proyecto.id, reporteregistro_id);
	}, 7500);
});


var dashboard_ejecusiones = 0;
function reporte_dashboard(proyecto_id, reporteregistro_id) {
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteruidodashboard/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe,
		data: {},
		cache: false,
		success: function (dato) {
			$("#dashboard_areas").html(dato.dashboard_areas);
			$("#dashboard_categorias").html(dato.dashboard_categorias);
			$("#dashboard_equipos").html(dato.dashboard_equipos);
			$("#dashboard_total_evaluacion").html(dato.dashboard_total_evaluacion);
			$("#dashboard_sonometria_total_dentronorma").html(dato.dashboard_sonometria_total_dentronorma);
			$("#dashboard_sonometria_total_fueranorma").html(dato.dashboard_sonometria_total_fueranorma);
			$("#dashboard_recomendaciones_total").html(dato.dashboard_recomendaciones_total);

			grafica_dashboard_resultados(dato.serie_grafico);

			// // mensaje
			// swal({
			// 	title: "Correcto",
			// 	text: ""+dato.msj,
			// 	type: "success", // warning, error, success, info
			// 	buttons: {
			// 		visible: false, // true , false
			// 	},
			// 	timer: 1500,
			// 	showConfirmButton: false
			// });

			dashboard_ejecusiones = 0;
		},
		error: function (dato) {
			1
			if (dashboard_ejecusiones == 0) {
				reporte_dashboard(proyecto_id, reporteregistro_id);
				dashboard_ejecusiones += 1;
			}

			return false;
		}
	});//Fin ajax
}


var graficapastel_resultados = null;
function grafica_dashboard_resultados(serie_grafico) {
	graficapastel_resultados = AmCharts.makeChart("grafica_resultados",
		{
			"type": "pie",
			"startDuration": 1,
			"theme": "light",
			"addClassNames": true,
			"autoMargins": false,
			"marginTop": 0,
			"marginBottom": 0,
			"marginLeft": 0,
			"marginRight": 0,
			"radius": "40%",
			"innerRadius": "10%", // Grosor Dona
			"fontSize": 14,
			"defs": {
				"filter": [{
					"id": "shadow",
					"width": "200%",
					"height": "200%",
					"feOffset": {
						"result": "offOut",
						"in": "SourceAlpha",
						"dx": 0,
						"dy": 0
					},
					"feGaussianBlur": {
						"result": "blurOut",
						"in": "offOut",
						"stdDeviation": 5
					},
					"feBlend": {
						"in": "SourceGraphic",
						"in2": "blurOut",
						"mode": "normal"
					}
				}]
			},
			"legend": {
				'enabled': false,
				"position": "bottom",
				"marginRight": 0,
				"marginLeft": 0,
				"autoMargins": false,
				"valueText": "[[description]]" //"[[description]] [[value]]"
			},
			"export": {
				"enabled": true,
				'position': 'top-right'
			},
			"valueField": "total",
			"titleField": "titulo",
			// "labelText": "[[title]]<br>$[[value]]<br>([[percents]]%)",
			"labelText": "[[value]]<br>([[percents]]%)",
			"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
			"labelRadius": -30,
			"colors": ["#00FF00", "#FF0000"], //color de la series
			"dataProvider": serie_grafico,
			// "dataProvider": [
			// 	{
			// 		"titulo": "Dentro de norma",
			// 		"total": 2				
			// 	},
			// 	{
			// 		"titulo": "Fuera de norma",
			// 		"total": 1
			// 	}
			// ],
		});
}


//=================================================
// RECOMENDACIONES


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_9(proyecto.id, reporteregistro_id, agente_nombre);
	}, 8000);
});


var datatable_9 = null;
function tabla_reporte_9(proyecto_id, reporteregistro_id, agente_nombre) {
	try {
		var ruta = "/reporteruidorecomendacionestabla/" + proyecto_id + "/" + reporteregistro_id + "/" + agente_nombre;

		if (datatable_9 != null) {
			datatable_9.clear().draw();
			datatable_9.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_9 = $('#tabla_reporte_9').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_9", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_9 ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_9(proyecto_id, reporteregistro_id, agente_nombre);
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
						data: "checkbox",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "descripcion",
						defaultContent: "-",
						className: 'alinear_izquierda',
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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
		datatable_9.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_9(proyecto_id, reporteregistro_id, agente_nombre);
	}
}


$("#boton_reporte_nuevarecomendacion").click(function () {
	$("#tabla_reporte_9 tbody").append('<tr>' +
		'<td>0</td>' +
		'<td style="text-align: center;">' +
		'<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>' +
		'<br><button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-2x"></i></button>' +
		'</td>' +
		'<td>' +
		'<div class="form-group">' +
		'<label>Tipo</label>' +
		'<select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>' +
		'<option value=""></option>' +
		'<option value="Preventiva">Preventiva</option>' +
		'<option value="Correctiva">Correctiva</option>' +
		'</select>' +
		'</div>' +
		'<div class="form-group">' +
		'<label>Descripción</label>' +
		'<textarea  class="form-control" style="margin-bottom: 0px;" rows="5" name="recomendacionadicional_descripcion[]" required></textarea>' +
		'</div>' +
		'</td>' +
		'</tr>');

	var posicion = $("#tabla_reporte_9 > tbody > tr").eq((parseInt(document.getElementById("tabla_reporte_9").rows.length) - 1) - 2).offset().top;
	$('html, body').animate({
		scrollTop: posicion
	}, 1000);

	$('[data-toggle="tooltip"]').tooltip();
});


function activa_recomendacion(checkbox) {
	if (checkbox.checked) {
		$('#recomendacion_descripcion_' + checkbox.value).attr('readonly', false);
		$('#recomendacion_descripcion_' + checkbox.value).attr('required', true);
	}
	else {
		$('#recomendacion_descripcion_' + checkbox.value).attr('required', false);
		$('#recomendacion_descripcion_' + checkbox.value).attr('readonly', true);
	}
}


$('#tabla_reporte_9 tbody').on('click', 'td>button.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar recomendación?",
		text: "Quitar de la lista de recomendaciones",
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
			// swal.close();

			var tr = fila.closest('tr');
			fila.closest("tr").remove(); // eliminar fila TR

			// mensaje
			swal({
				title: "Correcto",
				text: "Recomendación eliminada de la lista",
				type: "success", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 1500,
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
	return false;
});


$("#botonguardar_reporte_recomendaciones").click(function () {
	// borrar campo filtro del DATATABLE
	// datatable_recomendaciones.search("").draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.recomendacion_checkbox').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});

	$('.recomendacionadicional_checkbox').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});


	if (seleccionados > 0) {
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Recomendaciones",
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
						$('#form_reporte_recomendaciones').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 20,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte
								reporteregistro_id = dato.reporteregistro_id;

								menureporte_estado("menureporte_9", 1);

								// tabla_reporte_revisiones(proyecto.id);

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
								$('#botonguardar_reporte_recomendaciones').html('Guardar recomendaciones <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_recomendaciones').attr('disabled', false);
							},
							beforeSend: function () {
								$('#botonguardar_reporte_recomendaciones').html('Guardando recomendaciones <i class="fa fa-spin fa-spinner"></i>');
								$('#botonguardar_reporte_recomendaciones').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#botonguardar_reporte_recomendaciones').html('Guardar recomendaciones <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_recomendaciones').attr('disabled', false);

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
	}
	else {
		// mensaje
		swal({
			title: "Seleccione recomendaciones",
			text: "Antes de guardar debe seleccionar uno o más recomendaciones",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});
		return false;
	}
});


//=================================================
// RESPONSABLES DEL INFORME


$(document).ready(function () {
	$('#reporteresponsable1documento').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error:
		{
			'fileSize': 'Archivo demasiado grande.',
			'minWidth': 'Ancho demasiado pequeño.',
			'maxWidth': 'Ancho demasiado grande.',
			'minHeight': 'Alto demasiado pequeño.',
			'maxHeight': 'Alto demasiado grande.',
			'imageFormat': 'Formato no permitido, sólo (JPG, PNG).'
		}
	});

	$('#reporteresponsable2documento').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error:
		{
			'fileSize': 'Archivo demasiado grande.',
			'minWidth': 'Ancho demasiado pequeño.',
			'maxWidth': 'Ancho demasiado grande.',
			'minHeight': 'Alto demasiado pequeño.',
			'maxHeight': 'Alto demasiado grande.',
			'imageFormat': 'Formato no permitido, sólo (JPG, PNG).'
		}
	});

	$('#PORTADA').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove': 'Quitar',
			'error': 'Ooops, ha ocurrido un error.'
		},
		error:
		{
			'fileSize': 'Archivo demasiado grande.',
			'minWidth': 'Ancho demasiado pequeño.',
			'maxWidth': 'Ancho demasiado grande.',
			'minHeight': 'Alto demasiado pequeño.',
			'maxHeight': 'Alto demasiado grande.',
			'imageFormat': 'Formato no permitido, sólo (JPG, PNG).'
		}
	});
});


$("#botonguardar_reporte_responsablesinforme").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Responsables del informe",
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
					$('#form_reporte_responsablesinforme').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 21,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_10", 1);

							// tabla_reporte_revisiones(proyecto.id);

							$('#boton_descargarresponsabledoc1').css('display', 'block');
							$('#boton_descargarresponsabledoc2').css('display', 'block');

							// Carpeta ubicacion documentos historial
							$('#responsablesinforme_carpetadocumentoshistorial').val('');

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
							$('#botonguardar_reporte_responsablesinforme').html('Guardar responsables del informe <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_responsablesinforme').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_responsablesinforme').html('Guardando responsables del informe <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_responsablesinforme').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_responsablesinforme').html('Guardar responsables del informe <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_responsablesinforme').attr('disabled', false);

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


$("#boton_descargarresponsabledoc1").click(function () {
	window.open('/reporteruidoresponsabledocumento/' + reporteregistro_id + '/' + 1 + '/' + 1);
});


$("#boton_descargarresponsabledoc2").click(function () {
	window.open('/reporteruidoresponsabledocumento/' + reporteregistro_id + '/' + 2 + '/' + 1);
});


//=================================================
// PLANOS


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_planos(proyecto.id, reporteregistro_id, agente_nombre);
	}, 8500);
});


var datatable_planos = null;
function tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre) {
	try {
		var ruta = "/reporteruidoplanostabla/" + proyecto_id + "/" + reporteregistro_id + "/" + agente_nombre;

		if (datatable_planos != null) {
			datatable_planos.clear().draw();
			datatable_planos.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_planos = $('#tabla_reporte_planos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// Actualiza menu
						menureporte_estado("menureporte_11_2", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_planos ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					// {
					// 	data: "numero_registro",
					// 	defaultContent: "-"
					// },
					{
						data: "checkbox",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "proyectoevidenciaplano_carpeta",
						defaultContent: "-",
						orderable: false,
					}
					,
					{
						data: "total_planos",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [0, 1], //agrupar filas
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
		datatable_planos.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre);
	}
}


$("#botonguardar_reporte_planos").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Carpeta planos seleccionados",
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
					$('#form_reporte_planos').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 22,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							// Actualiza menu
							menureporte_estado("menureporte_11_2", dato.total);

							// tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reporte_planos').html('Guardar carpeta planos <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_planos').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_planos').html('Guardando carpeta planos <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_planos').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_planos').html('Guardar carpeta planos <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_planos').attr('disabled', false);

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


//=================================================
// EQUIPO UTILIZADO


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_equipoutilizado(proyecto.id, reporteregistro_id, agente_nombre);
	}, 9000);
});


var datatable_equipoutilizado = null;
function tabla_reporte_equipoutilizado(proyecto_id, reporteregistro_id, agente_nombre) {
	try {
		var ruta = "/reporteruidoequipoutilizadotabla/" + proyecto_id + "/" + reporteregistro_id + "/" + agente_nombre;

		if (datatable_equipoutilizado != null) {
			datatable_equipoutilizado.clear().draw();
			datatable_equipoutilizado.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_equipoutilizado = $('#tabla_reporte_equipoutilizado').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						menureporte_estado("menureporte_11_5", parseInt(json.total));
						menureporte_estado("menureporte_11_6", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_equipoutilizado ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_equipoutilizado(proyecto_id, reporteregistro_id, agente_nombre);
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
						defaultContent: "-"
					},
					{
						data: "checkbox",
						defaultContent: "-"
					},
					{
						data: "equipo",
						defaultContent: "-"
					},
					{
						data: "marca_modelo_serie",
						defaultContent: "-"
					},
					{
						data: "vigencia",
						defaultContent: "-"
					},
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [0, 1], //agrupar filas
				order: [[0, "ASC"]],
				ordering: false,
				processing: true,
				searching: true,
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
				}
			});
		}

		// Tooltip en DataTable
		datatable_equipoutilizado.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_equipoutilizado(proyecto_id, reporteregistro_id, agente_nombre);
	}
}


function activa_checkboxcarta(checkbox, equipo_id) {
	if (checkbox.checked) {
		$('#equipoutilizado_checkboxcarta_' + equipo_id).attr('disabled', false);
	}
	else {
		$('#equipoutilizado_checkboxcarta_' + equipo_id).prop("checked", false);
		$('#equipoutilizado_checkboxcarta_' + equipo_id).attr('disabled', true);
	}
}


$('#tabla_reporte_equipoutilizado tbody').on('click', 'td.cartapdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_equipoutilizado.row(tr);

	if (row.data().equipo_cartaPDF) {
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/' + row.data().equipo_id + '/' + 2);

		// Titulo modal
		$('#modal_visor .modal-title').html('Carta ' + row.data().equipo_Descripcion + ' [' + row.data().equipo_Serie + ']');

		// Abrir modal
		$('#modal_visor').modal({ backdrop: false });
	}
});


$('#tabla_reporte_equipoutilizado tbody').on('click', 'td.certificadopdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_equipoutilizado.row(tr);

	if (row.data().equipo_CertificadoPDF) {
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/' + row.data().equipo_id + '/' + 1);

		// Titulo modal
		$('#modal_visor .modal-title').html('Certificado de calibración ' + row.data().equipo_Descripcion + ' [' + row.data().equipo_Serie + ']');

		// Abrir modal
		$('#modal_visor').modal({ backdrop: false });
	}
});


$("#botoncerrar_modalvisor_reporte").click(function () {
	// Titulo modal
	$('#modal_visor .modal-title').html('Documento');

	// Visor
	$('#visor_documento').attr('src', '/assets/images/cargando.gif');
});


$("#botonguardar_reporte_equipoutilizado").click(function () {
	// borrar campo filtro del DATATABLE
	// datatable_equipoutilizado.search($(this).val()).draw();
	datatable_equipoutilizado.search("").draw();


	// valida campos vacios
	var seleccionados = 0;
	$('.equipoutilizado_checkbox').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});


	if (seleccionados > 0) {
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Equipo (s) utilizado (s)",
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
						$('#form_reporte_equipoutilizado').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 23,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte
								reporteregistro_id = dato.reporteregistro_id;

								menureporte_estado("menureporte_11_5", 1);
								menureporte_estado("menureporte_11_6", 1);

								// datatable_equipoutilizado.ajax.reload();

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
								$('#botonguardar_reporte_equipoutilizado').html('Guardar equipo utilizado <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_equipoutilizado').attr('disabled', false);
							},
							beforeSend: function () {
								$('#botonguardar_reporte_equipoutilizado').html('Guardando equipo utilizado <i class="fa fa-spin fa-spinner"></i>');
								$('#botonguardar_reporte_equipoutilizado').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#botonguardar_reporte_equipoutilizado').html('Guardar equipo utilizado <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_equipoutilizado').attr('disabled', false);

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
	}
	else {
		// mensaje
		swal({
			title: "Seleccione equipo (s)",
			text: "Antes de guardar debe seleccionar uno o más equipos",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});
		return false;
	}
});


//=================================================
// INFORME DE RESULTADOS LABORATORIO


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_anexosresultados(proyecto.id, reporteregistro_id, agente_nombre);
	}, 9500);
});


var datatable_informeresultados = null;
function tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre) {
	try {
		var ruta = "/reporteruidoanexosresultadostabla/" + proyecto_id + "/" + reporteregistro_id + "/" + agente_nombre;

		if (datatable_informeresultados != null) {
			datatable_informeresultados.clear().draw();
			datatable_informeresultados.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_informeresultados = $('#tabla_reporte_anexosresultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						// Actualiza menu
						menureporte_estado("menureporte_11_3", json.total);
						menureporte_estado("menureporte_11_4", json.total);
						menureporte_estado("menureporte_11_7", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_informeresultados ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre);
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
						defaultContent: "-"
					},
					{
						data: "checkbox",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "proyectoevidenciadocumento_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "proyectoevidenciadocumento_extension",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "created_at",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "documento",
						defaultContent: "-",
						orderable: false,
						className: 'documentopdf',
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [0, 1], //agrupar filas
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
				}
			});
		}

		// Tooltip en DataTable
		datatable_informeresultados.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre);
	}
}


$("#botonguardar_reporte_anexosresultados").click(function () {
	// valida campos vacios
	var seleccionados = 0;
	$('.anexoresultado_checkbox').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});


	if (seleccionados > 0) {
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Lista de anexos de resultados",
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
						$('#form_reporte_anexosresultados').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 24,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte
								reporteregistro_id = dato.reporteregistro_id;

								// Actualiza menu
								menureporte_estado("menureporte_11_3", dato.total);
								menureporte_estado("menureporte_11_4", dato.total);
								menureporte_estado("menureporte_11_7", dato.total);

								// tabla_reporte_revisiones(proyecto.id);

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
								$('#botonguardar_reporte_anexosresultados').html('Guardar anexos resultados <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_anexosresultados').attr('disabled', false);
							},
							beforeSend: function () {
								$('#botonguardar_reporte_anexosresultados').html('Guardando anexos resultados <i class="fa fa-spin fa-spinner"></i>');
								$('#botonguardar_reporte_anexosresultados').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#botonguardar_reporte_anexosresultados').html('Guardar anexos resultados <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_anexosresultados').attr('disabled', false);

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
	}
	else {
		// mensaje
		swal({
			title: "Seleccione anexos (s)",
			text: "Antes de guardar debe seleccionar uno o más anexos",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});
		return false;
	}
});


$('#tabla_reporte_anexosresultados tbody').on('click', 'td.documentopdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_informeresultados.row(tr);

	if (row.data().proyectoevidenciadocumento_extension == ".pdf" || row.data().proyectoevidenciadocumento_extension == ".PDF") {
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoevidenciadocumentodescargar/' + row.data().id + '/' + 0); // 0 mostrar

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().proyectoevidenciadocumento_nombre);

		// Abrir modal
		$('#modal_visor').modal({ backdrop: false });
	}
	else {
		window.open("/proyectoevidenciadocumentodescargar/" + row.data().id + "/" + 1); //1 descargar
	}
});


//=================================================
// ANEXOS - ACREDITACIONES Y / O APROBACIONES


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_acreditacionaprobacion(proyecto.id, reporteregistro_id, agente_nombre);
	}, 10000);
});


var datatable_acreditacionaprobacion = null;
function tabla_reporte_acreditacionaprobacion(proyecto_id, reporteregistro_id, agente_nombre) {
	try {
		var ruta = "/reporteruidoanexosacreditacionestabla/" + proyecto_id + "/" + reporteregistro_id + "/" + agente_nombre;

		if (datatable_acreditacionaprobacion != null) {
			datatable_acreditacionaprobacion.clear().draw();
			datatable_acreditacionaprobacion.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_acreditacionaprobacion = $('#tabla_reporte_acreditacionaprobacion').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						if (parseInt(json.total) > 0) {
							menureporte_estado("menureporte_11_8", 1);
							menureporte_estado("menureporte_11_9", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_acreditacionaprobacion ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_acreditacionaprobacion(proyecto_id);
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
						orderable: true,
					},
					{
						data: "checkbox",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "tipo",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "entidad",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "numero",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "area",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "vigencia",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "certificado",
						defaultContent: "-",
						orderable: false,
						className: 'certificadopdf',
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [1, 2, 3], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: true,
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

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_acreditacionaprobacion.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_acreditacionaprobacion(proyecto_id, reporteregistro_id, agente_nombre);
	}
}


$('#tabla_reporte_acreditacionaprobacion tbody').on('click', 'td.certificadopdf', function () {
	var tr = $(this).closest('tr');
	var row = datatable_acreditacionaprobacion.row(tr);

	if (row.data().acreditacion_SoportePDF) {
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/' + row.data().id + '/' + 0);

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().catTipoAcreditacion_Nombre + ' ' + row.data().acreditacion_Entidad + ' [' + row.data().acreditacion_Numero + ']');

		// Abrir modal
		$('#modal_visor').modal({ backdrop: false });
	}
});


$("#botonguardar_reporte_acreditacionaprobacion").click(function () {
	// valida campos vacios
	var seleccionados = 0;
	$('.anexoacreditacion_checkbox').each(function () {
		if (this.checked) {
			seleccionados += 1;
		}
	});


	if (seleccionados > 0) {
		// borrar campo filtro del DATATABLE
		// datatable_acreditacionaprobacion.search($(this).val()).draw();
		datatable_acreditacionaprobacion.search("").draw();

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Lista de anexos acreditaciones y/o aprobaciones",
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
						$('#form_reporte_acreditacionaprobacion').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 25,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte
								reporteregistro_id = dato.reporteregistro_id;

								// Actualiza menu
								menureporte_estado("menureporte_11_8", dato.total);
								menureporte_estado("menureporte_11_9", dato.total);

								// tabla_reporte_revisiones(proyecto.id);

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
								$('#botonguardar_reporte_acreditacionaprobacion').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', false);
							},
							beforeSend: function () {
								$('#botonguardar_reporte_acreditacionaprobacion').html('Guardando anexos 7 (STPS) y 8 (EMA) <i class="fa fa-spin fa-spinner"></i>');
								$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								$('#botonguardar_reporte_acreditacionaprobacion').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
								$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', false);

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
	}
	else {
		// mensaje
		swal({
			title: "Seleccione anexos (s)",
			text: "Antes de guardar debe seleccionar uno o más anexos",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 1500,
			showConfirmButton: false
		});
		return false;
	}
});


//=================================================
// REVISIONES


var ultimaversion_cancelada = 0;
var ultimarevision_id = 0;


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_revisiones(proyecto.id);
	}, 11000);
});


$(document).on('submit', function () {
	setTimeout(function () {
		// alert('Guardaste algo');
		tabla_reporte_revisiones(proyecto.id);
	}, 6000);
});


var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id) {
	try {
		var ruta = "/reporteruidorevisionestabla/" + proyecto_id;

		if (datatable_reporterevisiones != null) {
			datatable_reporterevisiones.clear().draw();
			datatable_reporterevisiones.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_reporterevisiones = $('#tabla_reporte_revisiones').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						if (parseInt(json.total) > 0) {
							$("#boton_reporte_nuevarevision").attr('disabled', false);
						}
						else {
							$("#boton_reporte_nuevarevision").attr('disabled', true);
						}


						ultimarevision_id = parseInt(json.ultimarevision_id);
						ultimaversion_cancelada = parseInt(json.ultimaversion_cancelada);
						botoninforme_estado(json.ultimaversion_estado);


						if (areas_poe == 1) {
							setTimeout(function () {
								$("#boton_reporte_nuevacategoria").attr('disabled', true);
								$("#boton_reporte_nuevaarea").attr('disabled', true);
							}, 5000);
						}


						return json.data;
					},
					error: function (xhr, error, code) {
						console.log('error en datatable_reporterevisiones ' + code);
						if (numeroejecucion <= 1) {
							tabla_reporte_revisiones(proyecto_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporterevisiones_revision",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "checkbox_concluido",
						defaultContent: "-",
						orderable: false,
						// className: 'checkbox_concluido',
					},
					{
						data: "nombre_concluido",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "checkbox_cancelado",
						defaultContent: "-",
						orderable: false,
						// className: 'checkbox_cancelado',
					},
					{
						data: "nombre_cancelado",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "estado_texto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_descargar",
						defaultContent: "-",
						orderable: false,
						// className: 'descargainforme',
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [1, 2, 3], //agrupar filas
				// order: [[ 0, "ASC" ]],
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

					// $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
					// $(row).find('td:eq(15)').css('color', '#FFFFFF');
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporterevisiones.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_revisiones(proyecto_id);
	}
}


function botoninforme_estado(boton_estado) {
	if (parseInt(boton_estado) > 0) {
		$(".botoninforme").attr('disabled', true);

		$(".botoninforme>i").removeClass('fa-save');
		$(".botoninforme>i").addClass('fa-ban');
	}
	else {
		$(".botoninforme").attr('disabled', false);

		$(".botoninforme>i").removeClass('fa-ban');
		$(".botoninforme>i").addClass('fa-save');
	}
}


function reporte_concluido(reporte_id, perfil, checkbox) {
	if (parseInt(perfil) == 1) {
		if (checkbox.checked) // Activado
		{
			$(checkbox).prop('checked', false);


			var pendientes = 0;
			var pendientes_puntos = '';
			$('#top-menu .fa-times').each(function () {
				pendientes += 1;

				var texto = this.id;
				texto = texto.replace("menureporte_", "");
				texto = texto.replace("_", ".");
				pendientes_puntos += '\nPunto: ' + texto;
			});


			if (parseInt(pendientes) == 0) {
				// Confirmar
				swal({
					title: "¿Concluir informe?",
					text: "Ultima revisión del informe de " + agente_nombre,
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


							// Enviar datos
							$.ajax({
								type: "GET",
								dataType: "json",
								url: "/reporteruidorevisionconcluir/" + reporte_id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tablas
									botoninforme_estado(dato.estado);
									tabla_reporte_revisiones(proyecto.id);

									tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
									tabla_reporte_categorias(proyecto.id, reporteregistro_id);
									tabla_reporte_areas(proyecto.id, reporteregistro_id);
									tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
									tabla_reporte_epp(proyecto.id, reporteregistro_id);
									tabla_reporte_6_2(proyecto.id, reporteregistro_id);
									tabla_reporte_7_1(proyecto.id, reporteregistro_id);
									tabla_reporte_7_2(proyecto.id, reporteregistro_id);
									tabla_reporte_7_3(proyecto.id, reporteregistro_id);


									// desplazar a la ultima fila de la tabla
									setTimeout(function () {
										$('html, body').animate({
											scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
										}, 1000);
									}, 2000);


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
									tabla_reporte_revisiones(proyecto.id);

									// mensaje
									swal({
										title: "Correcto",
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
					title: "No disponible",
					text: 'Esta revisión del informe aún no ha sido completada, se encontraron ' + pendientes + ' punto(s) pendiente(s) en todo el contenido por guardar.\n' + pendientes_puntos,
					type: "info", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 6000,
					showConfirmButton: false
				});
			}

			return false;
		}
		else {
			$(checkbox).prop('checked', true);

			// Confirmar
			swal({
				title: "¿Quitar concluido?",
				text: "Ultima revisión del informe de " + agente_nombre,
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


						// Enviar datos
						$.ajax({
							type: "GET",
							dataType: "json",
							url: "/reporteruidorevisionconcluir/" + reporte_id,
							data: {},
							cache: false,
							success: function (dato) {
								// Actualizar tablas
								botoninforme_estado(dato.estado);
								tabla_reporte_revisiones(proyecto.id);

								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
								tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
								tabla_reporte_epp(proyecto.id, reporteregistro_id);
								tabla_reporte_6_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_1(proyecto.id, reporteregistro_id);
								tabla_reporte_7_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_3(proyecto.id, reporteregistro_id);


								// desplazar a la ultima fila de la tabla
								setTimeout(function () {
									$('html, body').animate({
										scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
									}, 1000);
								}, 2000);


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
								tabla_reporte_revisiones(proyecto.id);

								// mensaje
								swal({
									title: "Correcto",
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
		}
	}
}


function reporte_cancelado(reporte_id, perfil, checkbox) {
	if (parseInt(perfil) == 1) {
		$('#form_modal_cancelacionobservacion').each(function () {
			this.reset();
		});


		// Campos Hidden
		$('#reporterevisiones_id').val(reporte_id);


		if (checkbox.checked) // Activado
		{
			$(checkbox).prop('checked', false);
			// alert('Cancelado '+checkbox.checked);

			// mostrar modal observacion
			$('#modal_reporte_cancelacionobservacion').modal({ backdrop: false, keyboard: false });
		}
		else {
			$(checkbox).prop('checked', true);
			// alert('Descancelado '+checkbox.checked);


			// Confirmar
			swal({
				title: "¿Quitar cancelación?",
				text: "Ultima revisión del informe de " + agente_nombre,
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


						// enviar datos
						$('#form_modal_cancelacionobservacion').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '' + ruta_storage_guardar,
							data: {
								opcion: 26,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								catactivo_id: $("#reporte_catactivo_id").val(),
								reporte_instalacion: $("#reporte_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar tablas
								botoninforme_estado(dato.estado);
								tabla_reporte_revisiones(proyecto.id);

								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
								tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
								tabla_reporte_epp(proyecto.id, reporteregistro_id);
								tabla_reporte_6_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_1(proyecto.id, reporteregistro_id);
								tabla_reporte_7_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_3(proyecto.id, reporteregistro_id);


								// desplazar a la ultima fila de la tabla
								setTimeout(function () {
									$('html, body').animate({
										scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
									}, 1000);
								}, 2000);


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
								// $('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
								// $('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

								// cerrar modal
								// $('#modal_reporte_cancelacionobservacion').modal('hide');
							},
							beforeSend: function () {
								// $('#botonguardar_modal_cancelacionobservacion').html('Guardando observación y cancelar revisión <i class="fa fa-spin fa-spinner"></i>');
								// $('#botonguardar_modal_cancelacionobservacion').attr('disabled', true);
							},
							error: function (dato) {
								// actualiza boton
								// $('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
								// $('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

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
	}
}


$("#botonguardar_modal_cancelacionobservacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea cancelar!",
			text: "Ultima revisión del informe de " + agente_nombre,
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

					// enviar datos
					$('#form_modal_cancelacionobservacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '' + ruta_storage_guardar,
						data: {
							opcion: 26,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
							tabla_reporte_epp(proyecto.id, reporteregistro_id);
							tabla_reporte_6_2(proyecto.id, reporteregistro_id);
							tabla_reporte_7_1(proyecto.id, reporteregistro_id);
							tabla_reporte_7_2(proyecto.id, reporteregistro_id);
							tabla_reporte_7_3(proyecto.id, reporteregistro_id);


							// desplazar a la ultima fila de la tabla
							setTimeout(function () {
								$('html, body').animate({
									scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
								}, 1000);
							}, 2000);


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
							$('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
							$('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_cancelacionobservacion').modal('hide');
						},
						beforeSend: function () {
							$('#botonguardar_modal_cancelacionobservacion').html('Guardando observación y cancelar revisión <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_cancelacionobservacion').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
							$('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

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


$("#boton_reporte_nuevarevision").click(function () {
	if (ultimaversion_cancelada == 1) {
		if (parseInt(datatable_reporte_7_2.data().count()) > 0) {
			swal({
				title: "¿Generar nueva revision?",
				text: "Informe de Ruido",
				type: "info",
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

					$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Copiando revisión, por favor espere...');
					$('#boton_reporte_nuevarevision').attr('disabled', true);


					var grafica_imgbase64 = '';
					graficapastel_resultados.export.capture({}, function () {
						this.toPNG({ quality: 1, multiplier: 6 }, function (img_base64) {
							// var image = new Image();
							// image.src = img_base64;
							// document.getElementById("captura").appendChild(image);
							// console.log('grafica pastel iluminacion');

							grafica_imgbase64 = img_base64;
						});
					});


					setTimeout(function () {
						// Enviar datos
						$.ajax({
							type: "POST",
							dataType: "json",
							url: "/reporteruidoword",
							data: {
								_token: document.querySelector('meta[name="csrf-token"]')['content'],
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								areas_poe: areas_poe,
								ultimarevision_id: ultimarevision_id,
								crear_revision: 1,
								grafica_dashboard: grafica_imgbase64,
							},
							cache: false,
							success: function (dato) {
								// Actualizar tablas
								botoninforme_estado(dato.estado);
								tabla_reporte_revisiones(proyecto.id);

								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
								tabla_reporte_5_6_equipoauditivo(proyecto.id, reporteregistro_id);
								tabla_reporte_epp(proyecto.id, reporteregistro_id);
								tabla_reporte_6_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_1(proyecto.id, reporteregistro_id);
								tabla_reporte_7_2(proyecto.id, reporteregistro_id);
								tabla_reporte_7_3(proyecto.id, reporteregistro_id);


								// desplazar a la ultima fila de la tabla
								setTimeout(function () {
									$('html, body').animate({
										scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
									}, 1000);
								}, 2000);


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


								$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
								$('#boton_reporte_nuevarevision').attr('disabled', false);
							},
							// beforeSend: function()
							// {
							// 	$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Copiando revisión, por favor espere...');
							// 	$('#boton_reporte_nuevarevision').attr('disabled', true);
							// },
							error: function (dato) {
								botoninforme_estado(1); //Bloquear

								tabla_reporte_revisiones(proyecto.id);

								$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
								$('#boton_reporte_nuevarevision').attr('disabled', false);

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
					}, 1000);
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
				title: "No disponible",
				text: "En este informe aún no se han capturado los resultados.",
				type: "info", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 3000,
				showConfirmButton: false
			});
		}
	}
	else {
		// mensaje
		swal({
			title: "No disponible",
			text: "Para generar una nueva revisión para este informe, debe cancelar la revisión mas reciente.",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 2000,
			showConfirmButton: false
		});
	}
});


//=================================================
// GENERAR WORD


$('#tabla_reporte_revisiones tbody').on('click', 'td>button.botondescarga', function () {
	var botondescarga = this;


	var tr = $(this).closest('tr');
	var row = datatable_reporterevisiones.row(tr);


	// Boton descarga
	$("#" + botondescarga.id).html('<i class="fa fa-spin fa-spinner fa-2x"></i>');


	setTimeout(function () {
		if (parseInt(row.data().id) == parseInt(ultimarevision_id)) {
			var grafica_imgbase64 = '';
			graficapastel_resultados.export.capture({}, function () {
				this.toPNG({ quality: 1, multiplier: 6 }, function (img_base64) {
					// var image = new Image();
					// image.src = img_base64;
					// document.getElementById("captura").appendChild(image);
					// console.log('grafica pastel iluminacion');

					grafica_imgbase64 = img_base64;
				});
			});


			setTimeout(function () {
				// Enviar datos
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "/reporteruidoword",
					data: {
						_token: document.querySelector('meta[name="csrf-token"]')['content'],
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						areas_poe: areas_poe,
						ultimarevision_id: ultimarevision_id,
						crear_revision: 0,
						grafica_dashboard: grafica_imgbase64,
					},
					cache: false,
					success: function (dato) {
						ventana = window.open('/reporteruidoworddescargar/' + proyecto.id + "/" + row.data().id + "/" + ultimarevision_id);


						// // Boton descarga
						// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


						setTimeout(function () {
							tabla_reporte_revisiones(proyecto.id);
						}, 6000);


						setTimeout(function () {
							if (ventana.window) {
								ventana.window.close();
							}
						}, 15000);


						// // mensaje
						// swal({
						// 	title: "Correcto",
						// 	text: ""+dato.msj,
						// 	type: "success", // warning, error, success, info
						// 	buttons: {
						// 		visible: false, // true , false
						// 	},
						// 	timer: 1500,
						// 	showConfirmButton: false
						// });
					},
					error: function (dato) {
						// mensaje
						swal({
							title: "Error",
							text: "Al intentar crear el informe, intentelo de nuevo.",
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
			}, 1000);
		}
		else {
			ventana = window.open('/reporteruidoworddescargar/' + proyecto.id + "/" + row.data().id + "/" + ultimarevision_id);


			// // Boton descarga
			// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


			setTimeout(function () {
				tabla_reporte_revisiones(proyecto.id);
			}, 6000);


			setTimeout(function () {
				if (ventana.window) {
					ventana.window.close();
				}
			}, 15000);


			// // mensaje
			// swal({
			// 	title: "Correcto",
			// 	text: ""+dato.msj,
			// 	type: "success", // warning, error, success, info
			// 	buttons: {
			// 		visible: false, // true , false
			// 	},
			// 	timer: 1500,
			// 	showConfirmButton: false
			// });
		}
	}, 500);
});




$('#btn_descargar_plantilla').on('click', function (e) {
	e.preventDefault();

	swal({
		title: "¡Confirme descargar!",
		text: "Plantilla principal del Informe.",
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

				url = 'descargarPortadaInformes/' + proyecto.id + '/' + 1;
				instalacion = $('#reporte_instalacion').val();

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
						a.download = `Plantilla principal (Ruido) - ${instalacion}.docx`;
						document.body.append(a);
						a.click();
						a.remove();
						window.URL.revokeObjectURL(url);

						// Cerrar mensaje de carga
						swal.close();

						$('#btn_descargar_plantilla').prop('disabled', true);
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


function obtenerdatos() {

	// $('#PROYECTO_ID_INFORME').val($('#proyecto_id').val());

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/obtenerDatosInformesProyecto/" + proyecto.id,
		data: {},
		cache: false,
		success: function (dato) {


			$("#NIVEL1").html(dato.opciones);
			$("#NIVEL2").html(dato.opciones);
			$("#NIVEL3").html(dato.opciones);
			$("#NIVEL4").html(dato.opciones);
			$("#NIVEL5").html(dato.opciones);

			$("#OPCION_PORTADA1").html(dato.checks);
			$("#OPCION_PORTADA2").html(dato.checks);
			$("#OPCION_PORTADA3").html(dato.checks);
			$("#OPCION_PORTADA4").html(dato.checks);
			$("#OPCION_PORTADA5").html(dato.checks);
			$("#OPCION_PORTADA6").html(dato.checks);


			setTimeout(() => {
				$("#NIVEL1").val(portada.NIVEL1);
				$("#NIVEL2").val(portada.NIVEL2);
				$("#NIVEL3").val(portada.NIVEL3);
				$("#NIVEL4").val(portada.NIVEL4);
				$("#NIVEL5").val(portada.NIVEL5);


				$("#OPCION_PORTADA1").val(portada.OPCION_PORTADA1);
				$("#OPCION_PORTADA2").val(portada.OPCION_PORTADA2);
				$("#OPCION_PORTADA3").val(portada.OPCION_PORTADA3);
				$("#OPCION_PORTADA4").val(portada.OPCION_PORTADA4);
				$("#OPCION_PORTADA5").val(portada.OPCION_PORTADA5);
				$("#OPCION_PORTADA6").val(portada.OPCION_PORTADA6);

				if (portada.RUTA_IMAGEN_PORTADA) {

					var archivo = portada.RUTA_IMAGEN_PORTADA;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/logoPortada/' + portada.ID_RECURSO_INFORME + extension;

					$('#PORTADA').val('')
					if ($('#PORTADA').data('dropify')) {

						$('#PORTADA').dropify().data('dropify').destroy();
						// $('.dropify-wrapper').css('height', 400);
						$('#PORTADA').dropify().data('dropify').settings.defaultFile = imagenUrl;
						$('#PORTADA').dropify().data('dropify').init();
					}
					else {


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
				}

			}, 1000);
		},
		error: function (xhr, status, error) {
			console.log('Error: ' + error);
			swal('Error', 'No se pudieron obtener los datos del informe', 'error');
		}
	});
}

// CAMPO DE LMPE 


$('#reporteruido_lmpe').on('change', function () {

	if ($(this).val() != '') {

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/guardarCampolmpe/" + proyecto.id + "/" + reporteregistro_id + "/" + $('#reporteruido_lmpe').val(),
			data: {},
			cache: false,
			success: function (dato) {
			},

			error: function (xhr, status, error) {
				console.log('Error: ' + error);
				swal('Error', 'Datos no cargados LMPE', 'error');
			}
		});
	}

});

///FUNCION BOTONES DE INSERCION POR MEDIO DE EXCEL
function abrirModalPuntos(tipo) {

	valor = $('#reporteruido_lmpe').val();
	if (tipo == 1) {
		$('#formExcelPuntos')[0].reset();

		// Campos Hidden
		$('#tipoArchivo').val(tipo);

		$('#divCargarPuntos').css('display', 'none');
		$('#alertaVerificacion').css('display', 'none');


		$('#modal_excel_puntos').modal({ backdrop: false });

	} else {

		if (valor == '') {

			swal({
				title: "No existe un LMPA dB(A) para evaluar.",
				text: "Por favor ingrese un valor en el campo de LMPA dB(A) para evaluar",
				type: "info",
				showConfirmButton: true
			});

		} else {

			$('#formExcelPuntos')[0].reset();

			// Campos Hidden
			$('#tipoArchivo').val(tipo);

			$('#divCargarPuntos').css('display', 'none');
			$('#alertaVerificacion').css('display', 'none');


			$('#modal_excel_puntos').modal({ backdrop: false });

		}

	}

}


$('#excelPuntos').change(function () {

	if ($(this).val()) {

		$('#alertaVerificacion').css('display', 'block');

	} else {
		$('#alertaVerificacion').css('display', 'none');

	}
});


$("#botonCargarPuntos").click(function () {
	var guardar = 0;
	var tipo = $('#tipoArchivo').val();

	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		if ($("#excelPersonal").val() != "") {
			// Tipo archivo
			var archivo = $("#excelPuntos").val();
			var extension = archivo.substring(archivo.lastIndexOf("."));

			// valida tipo de archivo
			if (extension == ".xlsx" || extension == ".XLSX") {
				guardar = 1;
			}
			else {
				// mensaje
				swal({
					title: "Tipo de archivo incorrecto " + extension,
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
		}
		else {
			guardar = 0;
		}

		// guardar
		if (guardar == 1) {

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

				if (isConfirm) {
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#formExcelPuntos').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: "/reporteruido",
						data: {
							opcion: 1000,
							registro_id: reporteregistro_id,
							proyecto_id: proyecto.id
						},
						contentType: false,
						processData: false,
						success: function (dato) {

							// actualizar boton
							$('#botonCargarPuntos').prop('disabled', false);
							$('#divCargarPuntos').css('display', 'none');

							if (dato.code == 200) {

								// cerrar modal
								$('#modal_excel_puntos').modal('hide');

								// mensaje
								swal({
									title: "Los datos fueron importados exitosamente",
									text: "" + dato.msj,
									type: "success",
									buttons: { visible: true },
									showConfirmButton: true,
									showCancelButton: false
								});

								setTimeout(() => {

									//Recar	gamos las tabla correspondientes
									if (tipo == 1) { //Punto 7.1
										tabla_reporte_7_1(proyecto.id, reporteregistro_id);

									} else if (tipo == 2) { //Punto 7.2

										tabla_reporte_7_2(proyecto.id, reporteregistro_id);

									} else if (tipo == 3) { //Punto 7.3

										tabla_reporte_7_3(proyecto.id, reporteregistro_id);

									}

								}, 2000);


							} else {

								swal({
									title: "Ocurrio un error al intentar importar los datos.",
									// text: ""+dato.msj,
									type: "error", // warning, error, success, info
									buttons: { visible: true },
									showConfirmButton: true,
									showCancelButton: false
								});
							}


						},
						beforeSend: function () {

							$('#botonCargarPuntos').prop('disabled', true);
							$('#divCargarPuntos').css('display', 'block');
						},
						error: function (dato) {

							// actualiza boton
							$('#botonCargarPuntos').prop('disabled', false);
							$('#divCargarPuntos').css('display', 'none');

							// mensaje
							swal({
								title: "Error al cargar los datos.",
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
	}
});






