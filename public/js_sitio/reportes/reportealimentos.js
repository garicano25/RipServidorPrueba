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
	// var fromTop = $(this).scrollTop() + topMenuHeight - 100;
	var fromTop = $(this).scrollTop() + topMenuHeight;

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
var reportealimentos_id = 0;
var agente_id = 11;
var agente_nombre = "Alimentos";

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

	// validarPermisosAsignados(proyecto.id) //Validacion de permisos
	datosgenerales(); // Datos generales
	portadaInfo(); // Portada


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


//================================================================
// Inicializacion de los campos de imagenes


$(document).ready(function () {
	$('#reportealimentosresponsable1documento').dropify({
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

	$('#reportealimentosresponsable2documento').dropify({
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

	$('#reportealimentosubicacionfoto').dropify({
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

//=================================================
// DATOS GENERALES


var datosgenerales_ejecusiones = 0;
function datosgenerales() {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportealimentosdatosgenerales/" + proyecto.id + "/" + agente_id + "/" + agente_nombre,
		data: {},
		cache: false,
		success: function (dato) {

			reportealimentos_id = 0;
			if (parseInt(dato.reportealimentos_id) > 0) {
				reportealimentos_id = parseInt(dato.reportealimentos_id);
				// $('#reportealimentos_id').html(reportealimentos_id);
			}

			if (parseInt(dato.reportealimentos_concluido) == 1 || parseInt(dato.reportealimentos_cancelado) == 1) {
				botoninforme_estado(1);
			}
			else {
				botoninforme_estado(0);
			}



			$('#reportealimentos_instalacion').val(dato.reportealimentos_portada.reportealimentos_instalacion);

			$('#reportealimentos_fecha').val(dato.reportealimentos_portada.reportealimentos_fecha);

			$('#reportealimentos_mes').val(dato.reportealimentos_portada.reportealimentos_mes);

			$('.div_instalacion_nombre').html(dato.reportealimentos_portada.reportealimentos_instalacion);

			$('#reportealimentos_introduccion').html(dato.reportealimentos_introduccion);
			$('#reportealimentos_objetivogeneral').html(dato.reportealimentos_objetivogeneral);
			$('#reportealimentos_objetivoespecifico').html(dato.reportealimentos_objetivoespecifico);
			$('#reportealimentos_metodologia_4_1').html(dato.reportealimentos_metodologia_4_1);
			$('#reportealimentos_metodologia_4_2').html(dato.reportealimentos_metodologia_4_2);
			$('#reportealimentos_metodologia_5_1').html(dato.reportealimentos_metodologia_5_1);
			$('#reportealimentos_metodologia_5_2').html(dato.reportealimentos_metodologia_5_2);

			$('#reportealimentos_ubicacioninstalacion').html(dato.reportealimentos_ubicacioninstalacion.ubicacion);
			if (dato.reportealimentos_ubicacioninstalacion.ubicacionfoto) {
				var archivo = dato.reportealimentos_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportealimentosmapaubicacion/' + reportealimentos_id + '/' + 0;
				$('#reportealimentosubicacionfoto').dropify().data('dropify').destroy();
				$('#reportealimentosubicacionfoto').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reportealimentosubicacionfoto').dropify().data('dropify').init();
				$('#reportealimentosubicacionfoto').attr('required', false);

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'block');
			}
			else {
				$('#reportealimentosubicacionfoto').val('');
				$('#reportealimentosubicacionfoto').attr('required', true);
				$('#reportealimentosubicacionfoto').dropify().data('dropify').resetPreview();
				$('#reportealimentosubicacionfoto').dropify().data('dropify').clearElement();

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'none');
			}

			$('#reportealimentos_procesoinstalacion').html(dato.reportealimentos_procesoinstalacion);
			$('#reportealimentos_actividadprincipal').html(dato.reportealimentos_actividadprincipal);
			$('#reportealimentos_conclusion').html(dato.reportealimentos_conclusion);

			if (dato.reportealimentos_responsablesinforme.reportealimentos_responsable1) {
				// Responsable 1
				$('#reportealimentos_responsable1').val(dato.reportealimentos_responsablesinforme.reportealimentos_responsable1);
				$('#reportealimentos_responsable1cargo').val(dato.reportealimentos_responsablesinforme.reportealimentos_responsable1cargo);
				$('#reportealimentos_responsable1documento').val('');
				$('#reportealimentosresponsable1documento').val('');

				var archivo = dato.reportealimentos_responsablesinforme.reportealimentos_responsable1documento;
				var tipo1 = dato.reportealimentos_responsablesinforme.tipo1
				var id1 = dato.reportealimentos_responsablesinforme.registro_id == 0 ? dato.reportealimentos_responsablesinforme.recsensorial_id : dato.reportealimentos_responsablesinforme.registro_id
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportealimentosresponsabledocumento/' + id1 + '/' + tipo1 + '/' + 0;
				$('#reportealimentosresponsable1documento').dropify().data('dropify').destroy();
				$('#reportealimentosresponsable1documento').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reportealimentosresponsable1documento').dropify().data('dropify').init();
				$('#reportealimentosresponsable1documento').attr('required', false);

				// Responsable 2
				$('#reportealimentos_responsable2').val(dato.reportealimentos_responsablesinforme.reportealimentos_responsable2);
				$('#reportealimentos_responsable2cargo').val(dato.reportealimentos_responsablesinforme.reportealimentos_responsable2cargo);
				$('#reportealimentos_responsable2documento').val('');
				$('#reportealimentosresponsable2documento').val('');

				var archivo = dato.reportealimentos_responsablesinforme.reportealimentos_responsable2documento;
				var tipo2 = dato.reportealimentos_responsablesinforme.tipo2
				var id2 = dato.reportealimentos_responsablesinforme.registro_id == 0 ? dato.reportealimentos_responsablesinforme.recsensorial_id : dato.reportealimentos_responsablesinforme.registro_id
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportealimentosresponsabledocumento/' + id2 + '/' + tipo2 + '/' + 0;
				$('#reportealimentosresponsable2documento').dropify().data('dropify').destroy();
				$('#reportealimentosresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl + extension;
				$('#reportealimentosresponsable2documento').dropify().data('dropify').init();
				$('#reportealimentosresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#reportealimentos_carpetadocumentoshistorial').val('reportes/proyecto/' + dato.reportealimentos_responsablesinforme.proyecto_id + '/' + agente_nombre + '/' + dato.reportealimentos_responsablesinforme.registro_id + '/responsables informe');
				if (parseInt(dato.reportealimentos_responsablesinforme.proyecto_id) == parseInt(proyecto.id)) {
					$('#boton_descargarresponsabledoc1').css('display', 'block');
					$('#boton_descargarresponsabledoc2').css('display', 'block');
					$('#reportealimentos_carpetadocumentoshistorial').val('');
				}
			}
			else {
				$('#reportealimentos_responsable1').val('');
				$('#reportealimentos_responsable1cargo').val('');
				$('#reportealimentos_responsable1documento').val('');
				$('#reportealimentosresponsable1documento').val('');
				$('#reportealimentosresponsable1documento').attr('required', true);
				$('#reportealimentosresponsable1documento').dropify().data('dropify').resetPreview();
				$('#reportealimentosresponsable1documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc1').css('display', 'none');

				$('#reportealimentos_responsable2').val('');
				$('#reportealimentos_responsable2cargo').val('');
				$('#reportealimentos_responsable2documento').val('');
				$('#reportealimentosresponsable2documento').val('');
				$('#reportealimentosresponsable2documento').attr('required', true);
				$('#reportealimentosresponsable2documento').dropify().data('dropify').resetPreview();
				$('#reportealimentosresponsable2documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc2').css('display', 'none');

				// Carpeta ubicacion documentos historial
				$('#reportealimentos_carpetadocumentoshistorial').val('');
			}

			// ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reportealimentos_portada_guardado);
			menureporte_estado("menureporte_1", dato.reportealimentos_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reportealimentos_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reportealimentos_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4_1", dato.reportealimentos_metodologia_4_1_guardado);
			menureporte_estado("menureporte_4_2", dato.reportealimentos_metodologia_4_2_guardado);
			menureporte_estado("menureporte_5_1", dato.reportealimentos_metodologia_5_1_guardado);
			menureporte_estado("menureporte_5_2", dato.reportealimentos_metodologia_5_2_guardado);
			menureporte_estado("menureporte_6_1", dato.reportealimentos_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_7", dato.reportealimentos_procesoinstalacion_guardado);
			menureporte_estado("menureporte_10", dato.reportealimentos_conclusion_guardado);
			menureporte_estado("menureporte_12", dato.reportealimentos_responsablesinforme_guardado);
			menureporte_estado("menureporte_13_3", dato.reportealimentos_memoriafotografica_guardado);
			$('#memoriafotografica_total').html(dato.reportealimentos_memoriafotografica_guardado);


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


		error: function (dato) {
			if (datosgenerales_ejecusiones == 0) {
				datosgenerales();
				datosgenerales_ejecusiones += 1;
			}

			reportealimentos_id = 0;

			$('#reportealimentos_instalacion').val('Error al cargar los datos');
			$('#reportealimentos_fecha').val('Error al cargar los datos');
			$('#reportealimentos_mes').val('Error al cargar los datos');
			$('#reportealimentos_introduccion').html('Error al cargar los datos');
			$('#reportealimentos_objetivogeneral').html('Error al cargar los datos');
			$('#reportealimentos_objetivoespecifico').html('Error al cargar los datos');
			$('#reportealimentos_metodologia_4_1').html('Error al cargar los datos');
			$('#reportealimentos_metodologia_4_2').html('Error al cargar los datos');
			$('#reportealimentos_metodologia_5_1').html('Error al cargar los datos');
			$('#reportealimentos_metodologia_5_2').html('Error al cargar los datos');
			$('#reportealimentos_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reportealimentos_procesoinstalacion').html('Error al cargar los datos');
			$('#reportealimentos_criterioseleccion').html('Error al cargar los datos');
			$('#reportealimentos_conclusion').html('Error al cargar los datos');

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
	// Bloquear botón guardar
	$('#' + boton_guardar).attr('disabled', true);
	$('#' + campo_filehidden).val('');

	var filesToUpload = document.getElementById(campo_file).files;
	var file = filesToUpload[0];

	// Crear un lector de archivos
	var reader = new FileReader();

	// Cargar archivos en el lector de archivos
	reader.readAsDataURL(file);

	// Configurar la imagen una vez que se carga en el lector de archivos
	reader.onload = function (e) {
		var img = new Image();
		img.onload = function () {
			var canvas = document.createElement("canvas");

			// Dimensiones reales
			var width = img.width;
			var height = img.height;

			// Dimensiones Nuevas
			var MAX_WIDTH = (width > 8000) ? 4000 : 1200;
			var MAX_HEIGHT = (width > 8000) ? 3000 : 900;

			// Dimensionar con respecto a la relación de aspecto
			if (width > height) {
				if (width > MAX_WIDTH) {
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			} else {
				if (height > MAX_HEIGHT) {
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}

			canvas.width = width;
			canvas.height = height;
			var ctx = canvas.getContext("2d");

			// Establecer un fondo blanco
			ctx.fillStyle = "white";
			ctx.fillRect(0, 0, canvas.width, canvas.height);

			// Dibujar la imagen redimensionada sobre el fondo blanco
			ctx.drawImage(img, 0, 0, width, height);

			// Resultado
			var dataurl = canvas.toDataURL("image/jpeg");
			$('#' + campo_filehidden).val(dataurl);

			// Desbloquear botón guardar
			$('#' + boton_guardar).attr('disabled', false);
		};

		img.src = e.target.result;
	};
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
						url: '/reportealimentos',
						data: {
							opcion: 0,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catregion_id: 0,
							catsubdireccion_id: 0,
							catgerencia_id: 0,
							catactivo_id: 0,
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_0", 1);

							tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 1,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_1", 1);

							tabla_reporte_revisiones(proyecto.id);

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
// DEFINICION

$(document).ready(function () {

	obtenerdatos()

	setTimeout(function () {
		tabla_reporte_definiciones(proyecto.id, agente_nombre, reportealimentos_id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id, agente_nombre, reportealimentos_id) {
	try {
		var ruta = "/reportealimentostabladefiniciones/" + proyecto_id + "/" + agente_nombre + "/" + reportealimentos_id;

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
							tabla_reporte_definiciones(proyecto_id, agente_nombre, reportealimentos_id);
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
		datatable_reportedefiniciones.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_definiciones(proyecto_id, agente_nombre, reportealimentos_id);
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
		$('#modal_reporte_definicion .modal-title').html(row.data().concepto);

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
									url: "/reportealimentosdefinicioneliminar/" + row.data().id,
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
						url: '/reportealimentos',
						data: {
							opcion: 2,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: $("#reportealimentos_catactivo_id").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte
							reportealimentos_id = dato.reportealimentos_id;

							// Actualizar tabla
							tabla_reporte_definiciones(proyecto.id, agente_nombre, reportealimentos_id);

							tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 3,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_3_1", 1);
							tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 4,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_3_2", 1);
							tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 5,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_4_1", 1);

							tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 6,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_4_2", 1);

							tabla_reporte_revisiones(proyecto.id);

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
// METODOLOGÍA PUNTO 5.1


$("#botonguardar_reportealimentos_5_1").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 5.1",
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
					$('#form_reporte_5_1').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reportealimentos',
						data: {
							opcion: 7,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_5_1", 1);

							tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reportealimentos_5_1').html('Guardar metodología punto 5.1 <i class="fa fa-save"></i>');
							$('#botonguardar_reportealimentos_5_1').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reportealimentos_5_1').html('Guardando metodología punto 5.1 <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reportealimentos_5_1').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reportealimentos_5_1').html('Guardar metodología punto 5.1 <i class="fa fa-save"></i>');
							$('#botonguardar_reportealimentos_5_1').attr('disabled', false);

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
// METODOLOGÍA PUNTO 5.2


$("#botonguardar_reportealimentos_5_2").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 5.2",
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
					$('#form_reporte_5_2').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reportealimentos',
						data: {
							opcion: 8,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val()
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_5_2", 1);

							tabla_reporte_revisiones(proyecto.id);

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
							$('#botonguardar_reportealimentos_5_2').html('Guardar metodología punto 5.2 <i class="fa fa-save"></i>');
							$('#botonguardar_reportealimentos_5_2').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reportealimentos_5_2').html('Guardando metodología punto 5.2 <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reportealimentos_5_2').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reportealimentos_5_2').html('Guardar metodología punto 5.2 <i class="fa fa-save"></i>');
							$('#botonguardar_reportealimentos_5_2').attr('disabled', false);

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


function redimencionar_mapaubicacion() {
	// Bloquear boton
	$('#botonguardar_reporte_ubicacion').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('reportealimentosubicacionfoto').files;
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
						url: '/reportealimentos',
						data: {
							opcion: 11,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
							ubicacionmapa: ubicacionmapa
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_6_1", 1);

							tabla_reporte_revisiones(proyecto.id);

							$('#reportealimentosubicacionfoto').val('');
							$('#reportealimentosubicacionfoto').attr('required', false);
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
	window.open('/reportealimentosmapaubicacion/' + reportealimentos_id + '/' + 1);
});


//=================================================
// PROCESO INSTALACIÓN


$("#botonguardar_reporte_procesoinstalacion").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Proceso de la instalación",
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
						url: '/reportealimentos',
						data: {
							opcion: 12,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_7", 1);

							tabla_reporte_revisiones(proyecto.id);

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
// CONCLUSION

$('#ID_CATCONCLUSION').on('change', function (e) {

	var selectedOption = $(this).find('option:selected');
	var descripcion = selectedOption.data('descripcion');

	$('#reportealimentos_conclusion').val(descripcion);


})

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
						url: '/reportealimentos',
						data: {
							opcion: 20,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_10", 1);

							tabla_reporte_revisiones(proyecto.id);

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
// RECOMENDACIONES


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_recomendaciones(proyecto.id, reportealimentos_id, agente_nombre);
	}, 6500);
});


var datatable_recomendaciones = null;
function tabla_reporte_recomendaciones(proyecto_id, reportealimentos_id, agente_nombre) {
	try {
		var ruta = "/reportealimentostablarecomendaciones/" + proyecto_id + "/" + reportealimentos_id + "/" + agente_nombre;

		if (datatable_recomendaciones != null) {
			datatable_recomendaciones.clear().draw();
			datatable_recomendaciones.ajax.url(ruta).load();
		}
		else {
			var numeroejecucion = 1;
			datatable_recomendaciones = $('#tabla_reporte_recomendaciones').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json) {
						if (parseInt(json.total) > 0) {
							menureporte_estado("menureporte_11", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code) {
						// console.log(xhr); console.log(code);
						console.log('error en datatable_recomendaciones');
						if (numeroejecucion <= 1) {
							tabla_reporte_recomendaciones(proyecto_id, reportealimentos_id, agente_nombre);
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
						"data": "checkbox",
						"defaultContent": "-"
					},
					{
						"data": "descripcion",
						"defaultContent": "-"
					}
				],
				"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// "rowsGroup": [0, 1], //agrupar filas
				"order": [[0, "ASC"]],
				"ordering": false,
				"processing": true,
				"searching": false,
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
		datatable_recomendaciones.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception) {
		tabla_reporte_recomendaciones(proyecto_id, reportealimentos_id, agente_nombre);
	}
}


$("#boton_reporte_nuevarecomendacion").click(function () {
	$("#tabla_reporte_recomendaciones tbody").append('<tr>' +
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

	var posicion = $("#tabla_reporte_recomendaciones > tbody > tr").eq((parseInt(document.getElementById("tabla_reporte_recomendaciones").rows.length) - 1) - 2).offset().top;
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


$('#tabla_reporte_recomendaciones tbody').on('click', '.eliminar', function () {
	// obtener fila tabla
	var fila = $(this);

	// confirmar
	swal({
		title: "¿Eliminar recomendación?",
		text: "de la lista de recomendaciones",
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
							url: '/reportealimentos',
							data: {
								opcion: 30,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reportealimentos_id: reportealimentos_id,
								catactivo_id: 0,
								reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte						
								reportealimentos_id = dato.reportealimentos_id;

								menureporte_estado("menureporte_11", 1);

								tabla_reporte_revisiones(proyecto.id);

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
						url: '/reportealimentos',
						data: {
							opcion: 40,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_12", 1);

							tabla_reporte_revisiones(proyecto.id);

							$('#boton_descargarresponsabledoc1').css('display', 'block');
							$('#boton_descargarresponsabledoc2').css('display', 'block');

							// Carpeta ubicacion documentos historial
							$('#reportealimentos_carpetadocumentoshistorial').val('');

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
	window.open('/reportealimentosresponsabledocumento/' + reportealimentos_id + '/' + 1 + '/' + 1);
});


$("#boton_descargarresponsabledoc2").click(function () {
	window.open('/reportealimentosresponsabledocumento/' + reportealimentos_id + '/' + 2 + '/' + 1);
});





//=================================================
// PUNTOS DE RESULTADOS


// ============== Punto 8.1 ==============
$('input[name="COLIFORME_TOTALES_ALIMENTOS"]').change(function () {
	if ($('#COLIFORME_TOTALES_ALIMENTOS_SI').is(':checked')) {
		$('.totales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_TOTALES_ALIMENTOS_NO').is(':checked')) {
		$('.totales').prop('disabled', true).prop('required', false).val('');
	}
});

$('input[name="COLIFORME_FECALES_ALIMENTOS"]').change(function () {
	if ($('#COLIFORME_FECALES_ALIMENTOS_SI').is(':checked')) {
		$('.fecales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_FECALES_ALIMENTOS_NO').is(':checked')) {
		$('.fecales').prop('disabled', true).prop('required', false).val('');
	}
});

$('#boton_reporte_nuevoalimentospunto8_1').on('click', function () {

	$('#form_reporte_punto_8_1').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#ID_PUNTO_ALIMENTOS').val(0);

	// mostrar modal
	$('#modal_reporte_punto_8_1').modal({ backdrop: false });

})


$("#botonguardar_punto_8_1").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Resultado de calidad e inocuidad de alimentos",
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
					$('#form_reporte_punto_8_1').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reportealimentos',
						data: {
							opcion: 13,
							proyecto_id: proyecto.id,
							PROYECTO_ID: proyecto.id, // Campo de tabla
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_8_1", 1);
							tabla_reporte_revisiones(proyecto.id);

					
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


							$('#modal_reporte_punto_8_1').modal('hide');


							// actualiza boton
							$('#botonguardar_punto_8_1').html('Guardar Resultado de calidad e inocuidad de alimentos <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_1').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_punto_8_1').html('Guardando Resultado de calidad e inocuidad de alimentos <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_punto_8_1').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_punto_8_1').html('Guardar Resultado de calidad e inocuidad de alimentos <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_1').attr('disabled', false);

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



// ============== Punto 8.2 ==============
$('input[name="COLIFORME_TOTALES_VIVAS"]').change(function () {
	if ($('#COLIFORME_TOTALES_VIVAS_SI').is(':checked')) {
		$('.vivas_totales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_TOTALES_VIVAS_NO').is(':checked')) {
		$('.vivas_totales').prop('disabled', true).prop('required', false).val('');
	}
});

$('input[name="COLIFORME_FECALES_VIVAS"]').change(function () {
	if ($('#COLIFORME_FECALES_VIVAS_SI').is(':checked')) {
		$('.vivas_fecales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_FECALES_VIVAS_NO').is(':checked')) {
		$('.vivas_fecales').prop('disabled', true).prop('required', false).val('');
	}
});


$('#boton_reporte_nuevoalimentospunto8_2').on('click', function () {

	$('#form_reporte_punto_8_2').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#ID_PUNTO_VIVAS').val(0);

	// mostrar modal
	$('#modal_reporte_punto_8_2').modal({ backdrop: false });

})

$("#botonguardar_punto_8_2").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Resultado, evalución de superficies vivas",
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
					$('#form_reporte_punto_8_2').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reportealimentos',
						data: {
							opcion: 14,
							proyecto_id: proyecto.id,
							PROYECTO_ID: proyecto.id, // Campo de tabla
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_8_2", 1);
							tabla_reporte_revisiones(proyecto.id);


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


							$('#modal_reporte_punto_8_2').modal('hide');


							// actualiza boton
							$('#botonguardar_punto_8_2').html('Guardar Resultado de evaluación de superficies vivas <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_2').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_punto_8_2').html('Guardando Resultado de evaluación de superficies vivas <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_punto_8_2').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_punto_8_2').html('Guardar Resultado de evaluación de superficies vivas <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_2').attr('disabled', false);

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


//  ============== Punto 8.3 ==============
$('input[name="COLIFORME_TOTALES_INERTES"]').change(function () {
	if ($('#COLIFORME_TOTALES_INERTES_SI').is(':checked')) {
		$('.inertes_totales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_TOTALES_INERTES_NO').is(':checked')) {
		$('.inertes_totales').prop('disabled', true).prop('required', false).val('');
	}
});

$('input[name="COLIFORME_FECALES_INERTES"]').change(function () {
	if ($('#COLIFORME_FECALES_INERTES_SI').is(':checked')) {
		$('.inertes_fecales').prop('disabled', false).prop('required', true).val('');

	} else if ($('#COLIFORME_FECALES_INERTES_NO').is(':checked')) {
		$('.inertes_fecales').prop('disabled', true).prop('required', false).val('');
	}
});


$('#boton_reporte_nuevoalimentospunto8_3').on('click', function () {

	$('#form_reporte_punto_8_3').each(function () {
		this.reset();
	});

	// Campos Hidden
	$('#ID_PUNTO_INERTES').val(0);

	// mostrar modal
	$('#modal_reporte_punto_8_3').modal({ backdrop: false });

})

$("#botonguardar_punto_8_3").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Resultado, evalución de superficies inertes",
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
					$('#form_reporte_punto_8_3').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reportealimentos',
						data: {
							opcion: 15,
							proyecto_id: proyecto.id,
							PROYECTO_ID: proyecto.id, // Campo de tabla
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportealimentos_id: reportealimentos_id,
							catactivo_id: 0,
							reportealimentos_instalacion: $("#reportealimentos_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportealimentos_id = dato.reportealimentos_id;

							menureporte_estado("menureporte_8_3", 1);
							tabla_reporte_revisiones(proyecto.id);


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


							$('#modal_reporte_punto_8_3').modal('hide');


							// actualiza boton
							$('#botonguardar_punto_8_3').html('Guardar Resultado de evaluación de superficies inertes <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_3').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_punto_8_3').html('Guardando Resultado de evaluación de superficies inertes <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_punto_8_3').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_punto_8_3').html('Guardar Resultado de evaluación de superficies inertes <i class="fa fa-save"></i>');
							$('#botonguardar_punto_8_3').attr('disabled', false);

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
// REVISIONES


var ultimaversion_cancelada = 0;
var ultimarevision_id = 0;


$(document).ready(function () {
	setTimeout(function () {
		tabla_reporte_revisiones(proyecto.id);
	}, 8500);
});


var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id) {
	try {
		var ruta = "/reportealimentostablarevisiones/" + proyecto_id;

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
					// console.log(index+' - '+data.reportealimentospuntos_nopunto);

					// if(data.reportealimentospuntos_nopunto == 2)
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


$("#boton_reporte_nuevarevision").click(function () {
	if (ultimaversion_cancelada == 1) {
		if (parseInt(datatable_alimentospuntos.data().count()) > 0) {
			swal({
				title: "¿Generar nueva revision?",
				text: "Informe de Iluminación",
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

					setTimeout(function () {
						var grafica_alimentos_imgbase64 = '';
						graficapastel_alimentos.export.capture({}, function () {
							this.toPNG({ quality: 1, multiplier: 6 }, function (img_base64) {
								// var image = new Image();
								// image.src = data;
								// document.getElementById("captura").appendChild(image);
								// console.log('grafica pastel alimentos');

								grafica_alimentos_imgbase64 = img_base64;
							});
						});


						var grafica_reflexion_imgbase64 = '';
						graficapastel_reflexion.export.capture({}, function () {
							this.toPNG({ quality: 1, multiplier: 6 }, function (img_base64) {
								// var image = new Image();
								// image.src = img_base64;
								// document.getElementById("captura").appendChild(image);
								// console.log('grafica pastel reflexion');

								grafica_reflexion_imgbase64 = img_base64;
							});
						});


						html2canvas(document.querySelector("#areas_cumplimiento"), { scale: 6 }).then(canvas => {
							// document.getElementById("captura").appendChild(canvas)
							var imgData = canvas.toDataURL('image/jpeg');

							setTimeout(function () {
								// Enviar datos
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/reportealimentosword",
									data: {
										_token: document.querySelector('meta[name="csrf-token"]')['content'],
										proyecto_id: proyecto.id,
										agente_id: agente_id,
										agente_nombre: agente_nombre,
										reportealimentos_id: reportealimentos_id,
										areas_poe: areas_poe,
										ultimarevision_id: ultimarevision_id,
										crear_revision: 1,
										grafica1: imgData,
										grafica_alimentos: grafica_alimentos_imgbase64,
										grafica_reflexion: grafica_reflexion_imgbase64,
									},
									cache: false,
									success: function (dato) {
										botoninforme_estado(0); //Desbloquear


										// ACTUALIZAR TABLAS
										tabla_reporte_revisiones(proyecto.id);
										tabla_reporte_definiciones(proyecto.id, agente_nombre, reportealimentos_id);


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
										// Boton
										$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
										$('#boton_reporte_nuevarevision').attr('disabled', false);


										tabla_reporte_revisiones(proyecto.id);


										// mensaje
										swal({
											title: "Error",
											text: "Al crear nueva revisión de este informe, intentelo de nuevo.",
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
						});
					}, 500);
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
				text: "En este informe aún no se ha capturado puntos de iluminación.",
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

