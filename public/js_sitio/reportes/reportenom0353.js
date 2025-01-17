// modulo EPP
var opciones_catepp = "";
var ambientechart = null;
var chartPngs = {};
var edadeschart = null;
var escolaridadchart = null;
var estadocivilchart = null;
var regimenchart = null;
var experienciachart = null;
var generoschart = null;
var categoriaschart = null;
var dominioschart = null;
var acontecimientoschart = null;
var calificacionchart = null;



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
var agente_id = 353;
var agente_nombre = "NOM0353";
var ruta_storage_guardar = '/reportenom0353';



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
	$('#modal_cargando .modal-title').html('Cargando zorme de ' + agente_nombre); // Titulo modal
	$('#modal_cargando').modal(); // Abrir modal
	updateClock(); // Ejecutar tiempo de espera

	validarPermisosAsignados(proyecto.id) //Validacion de permisos
	datosgenerales(); // Cargar datos
	portadaInfo();
	consulta_categoria_epp();  // cargar partes del cuerpo epp

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

	document.getElementById('botonguardar_reporte_procesoinstalacion').addEventListener('click', function (e) {
		// Copiar el contenido HTML a los inputs ocultos
		document.getElementById('reporte_procesoinstalacion').value = document.getElementById('editableProcesoInstalacion').innerHTML;
		document.getElementById('reporte_actividadprincipal').value = document.getElementById('editableActividadPrincipal').innerHTML;
	});
});

document.addEventListener('DOMContentLoaded', () => {

});
//=================================================
// DATOS GENERALES


var datosgenerales_ejecusiones = 0;
function datosgenerales() {
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportenom0353datosgenerales/" + proyecto.id + "/" + agente_id + "/" + agente_nombre,
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


			if (parseInt(dato.tipocliente) == 1) //1= pemex, 0 =  cliente
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

			$('.div_instalacion_nombre').html(dato.reporte_portada.reporte_instalacion);

			$('#reporte_introduccion').html(dato.reporte_introduccion);
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_metodologia_4_1').html(dato.reporte_metodologia_4_1);

			$('#reporte_acontecimientos_conclusiones').html(dato.reporte_acontecimientos_conclusiones);
			$('#reporte_ambiente_conclusiones').html(dato.reporte_ambiente_conclusiones);
			$('#reporte_condiciones_conclusiones').html(dato.reporte_condiciones_conclusiones);
			$('#reporte_factores_conclusiones').html(dato.reporte_factores_conclusiones);
			$('#reporte_carga_conclusiones').html(dato.reporte_carga_conclusiones);
			$('#reporte_falta_conclusiones').html(dato.reporte_falta_conclusiones);
			$('#reporte_organizacion_conclusiones').html(dato.reporte_organizacion_conclusiones);
			$('#reporte_jornada_conclusiones').html(dato.reporte_jornada_conclusiones);
			$('#reporte_interferencia_conclusiones').html(dato.reporte_interferencia_conclusiones);
			$('#reporte_liderazgorelaciones_conclusiones').html(dato.reporte_liderazgorelaciones_conclusiones);
			$('#reporte_liderazgo_conclusiones').html(dato.reporte_liderazgo_conclusiones);
			$('#reporte_relaciones_conclusiones').html(dato.reporte_relaciones_conclusiones);
			$('#reporte_violencia_conclusiones').html(dato.reporte_violencia_conclusiones);
			$('#reporte_entorno_conclusiones').html(dato.reporte_entorno_conclusiones);
			$('#reporte_reconocimiento_conclusiones').html(dato.reporte_reconocimiento_conclusiones);
			$('#reporte_insuficiente_conclusiones').html(dato.reporte_insuficiente_conclusiones);

			$('#reporte_acontecimientos_recomendaciones').html(dato.reporte_acontecimientos_recomendaciones);
			$('#reporte_ambiente_recomendaciones').html(dato.reporte_ambiente_recomendaciones);
			$('#reporte_factores_recomendaciones').html(dato.reporte_factores_recomendaciones);
			$('#reporte_organizacion_recomendaciones').html(dato.reporte_organizacion_recomendaciones);
			$('#reporte_liderazgorelaciones_recomendaciones').html(dato.reporte_liderazgorelaciones_recomendaciones);
			$('#reporte_entorno_recomendaciones').html(dato.reporte_entorno_recomendaciones);



			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			if (dato.reporte_ubicacioninstalacion.ubicacionfoto) {
				var archivo = dato.reporte_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportenom0353mapaubicacion/' + reporteregistro_id + '/' + 0;
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

			$('#editableProcesoInstalacion').html(dato.reporte_procesoinstalacion);
			$('#editableActividadPrincipal').html(dato.reporte_actividadprincipal);
			$('#reporte_descripcionmetodo').html(dato.reporte_metodoevaluacion);

			if (dato.reporte_responsablesinforme.responsable1) {
				// Responsable 1
				$('#reporte_responsable1').val(dato.reporte_responsablesinforme.responsable1);
				$('#reporte_responsable1cargo').val(dato.reporte_responsablesinforme.responsable1cargo);
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportenom0353responsabledocumento/' + dato.reporte_responsablesinforme.registro_id + '/' + 1 + '/' + 0;
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
				var imagenUrl = '/reportenom0353responsabledocumento/' + dato.reporte_responsablesinforme.registro_id + '/' + 2 + '/' + 0;
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
			menureporte_estado("menureporte_5_1", dato.reporte_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_5_2", dato.reporte_procesoinstalacion_guardado);
			menureporte_estado("menureporte_5_3", 1); //descripcion de actividades del personal expuesto
			menureporte_estado("menureporte_7_1", dato.reporte_metodoevaluacion_guardado);
			menureporte_estado("menureporte_7_2", 1); //dashboard
			menureporte_estado("menureporte_9_2", 1); //grafica guia 1
			menureporte_estado("menureporte_9_3", 1); //grafica guia 1
			menureporte_estado("menureporte_9_4_1", 1); // grafica 1 guia 3
			menureporte_estado("menureporte_9_4_2", 1); // grafica 2 guia 3
			menureporte_estado("menureporte_9_4_3", 1); // grafica 3 guia 3
			menureporte_estado("menureporte_9_4_4", 1); // grafica 4 guia 3
			menureporte_estado("menureporte_9_4_5", 1); // grafica 5 guia 3
			menureporte_estado("menureporte_10_1", dato.reporte_conclusion_guardado);
			menureporte_estado("menureporte_11_1", dato.reporte_recomendacion_guardado);
			menureporte_estado("menureporte_12", dato.reporte_responsablesinforme_guardado);
			menureporte_estado("menureporte_13_1", 1);//anexos
			menureporte_estado("menureporte_13_2", 1);//anexos
			menureporte_estado("menureporte_13_3", 1);//anexos





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
			$('#reporte_ubicacioninstalacion').html('Error al cargar los datos');
			$('#editableTextarea').html('Error al cargar los datos');
			$('#editableActividadPrincipal').html('Error al cargar los datos');
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
							agente_id: 353,
							agente_nombre: 'NOM0353',
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
		tabla_reporte_definiciones(proyecto.id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id) {
	try {
		var ruta = "/reportenom0353tabladefiniciones/" + proyecto_id;

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
							tabla_reporte_definiciones(proyecto_id);
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
						"data": "CONCEPTO",
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
		tabla_reporte_definiciones(proyecto_id);
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
										tabla_reporte_definiciones(proyecto.id);

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
							tabla_reporte_definiciones(proyecto.id);

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

	document.getElementById('reporte_procesoinstalacion').value = document.getElementById('editableProcesoInstalacion').innerHTML;
	document.getElementById('reporte_actividadprincipal').value = document.getElementById('editableActividadPrincipal').innerHTML;
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
var selectequipos = '';


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
							// menureporte_estado("menureporte_5_3", 0);
							// menureporte_estado("menureporte_5_4", 0);
							// menureporte_estado("menureporte_5_5", 0);
							// menureporte_estado("menureporte_5_8", 0);
							// menureporte_estado("menureporte_6_1", 0);
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
						selectequipos = json.selectequiposoption;


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
		'<td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>' +
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
		'<td><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-1x"></i></button></td>' +
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

	//Validamos el check de que si aplica o no aplica
	if (row.data().aplica_ruido == 1) {
		$('#aplica_ruido_si').prop('checked', true);
		$('#aplica_ruido_no').prop('checked', false);

	} else {

		$('#aplica_ruido_si').prop('checked', false);
		$('#aplica_ruido_no').prop('checked', true);

	}



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


function validarCampos() {
	//Validar categorias seleccionadas
	const checkboxes = document.querySelectorAll('#tabla_areacategorias input[type="checkbox"]');
	const algunoMarcado = Array.from(checkboxes).some(checkbox => checkbox.checked);
	const aplica = document.getElementById('aplica_ruido_no');

	if (aplica.checked) {

		return true;

	} else {

		if (!algunoMarcado) {
			swal({
				title: "Por favor no se olvide de seleccionar al menos una categoria.",
				text: "Realize esta acción para poder continuar",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: true, // true , false
				},
				showConfirmButton: true
			})
			return false;
		}


		const textInputs = document.querySelectorAll('#tabla_areamaquinaria input[name="reporteruidoareamaquinaria_nombre[]"]');
		const algunoLleno = Array.from(textInputs).some(input => input.value.trim() !== "");

		if (!algunoLleno) {
			swal({
				title: "Por favor no se olvide de agregar al menos una fuentes generadoras.",
				text: "Realize estas acciones para poder continuar",
				type: "warning", // warning, error, success, info
				buttons: {
					visible: true, // true , false
				},
				showConfirmButton: true
			})
			return false;
		}

		return true;

	}
}


$("#botonguardar_modal_area").click(function (e) {

	datatable_areacategoria.search("").draw();
	e.preventDefault();


	if (validarCampos()) {

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
						//menureporte_estado("menureporte_5_7", parseInt(json.total));
						menureporte_estado("menureporte_5_7", 1);


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

	//consulta_categoria_epp();

	$("#tabla_lista_epp_ruido tbody").html('');
	$("#tabla_lista_epp_ruido tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" id="reporteruidoepp_partecuerpo" name="reporteruidoepp_partecuerpo" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" id="reporteruidoepp_equipo" name="reporteruidoepp_equipo" required></select></td>' +
		'</tr>');
	$('#modal_reporte_epp').modal({ backdrop: false });
});

$("#tabla_lista_epp_ruido tbody").on("change", ".regionAnatomica", function () {

	var valorSeleccionado = $(this).val();
	var fila = $(this).closest("tr");
	var selectClaveEppp = fila.find("select[name='reporteruidoepp_equipo']"); // Encontrar el Select en la misma fila

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialClaveEppruido/" + valorSeleccionado,
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

function consulta_categoria_epp() {
	// alert('mensaje');
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialeppcatalogoruido",
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
}

$('#tabla_reporte_epp tbody').on('click', 'td.editar', function () {
	var tr = $(this).closest('tr');
	var row = datatable_epp.row(tr);

	$('#form_modal_epp').each(function () {
		this.reset();
	});
	// Titulo del modal
	$('#modal_reporte_epp .modal-title').html('Equipo de protección personal');

	consulta_categoria_epp()

	$("#tabla_lista_epp_ruido tbody").html('');
	$("#tabla_lista_epp_ruido tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" id="reporteruidoepp_partecuerpo" name="reporteruidoepp_partecuerpo" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" id="reporteruidoepp_equipo" name="reporteruidoepp_equipo" required></select></td>' +
		'</tr>');

	// Llenar campos
	// Campos Hidden
	$('#reporteepp_id').val(row.data().id);


	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialClaveEppruido/" + row.data().reporteruidoepp_partecuerpo,
		data: {},
		cache: false,
		success: function (dato) {
			$('#reporteruidoepp_equipo').html(dato.opciones);
			setTimeout(() => {
				$('#reporteruidoepp_partecuerpo').val(row.data().reporteruidoepp_partecuerpo);
				$('#reporteruidoepp_equipo').val(row.data().reporteruidoepp_equipo);

			}, 500);
		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//



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
							console.log($('#form_modal_epp').serializeArray());
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

						//menureporte_estado("menureporte_6_2", parseInt(json.total));
						menureporte_estado("menureporte_6_2", 1);


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


$("#botonguardar_reporte_descripcionmetodo").click(function () {
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida) {
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Método de aplicación",
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
					$('#form_reporte_descripcionmetodo').ajaxForm({
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

							menureporte_estado("menureporte_7_1", 1);

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
							$('#botonguardar_reporte_descripcionmetodo').html('Guardar método de evaluación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_descripcionmetodo').attr('disabled', false);
						},
						beforeSend: function () {
							$('#botonguardar_reporte_descripcionmetodo').html('Guardando método de evaluación <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_descripcionmetodo').attr('disabled', true);
						},
						error: function (dato) {
							// actualiza boton
							$('#botonguardar_reporte_descripcionmetodo').html('Guardar método de evaluación <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_descripcionmetodo').attr('disabled', false);

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

	$('#reporte_conclusion').val(descripcion);


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

							menureporte_estado("menureporte_10_1", 1);

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
			$("#dashboard_sonometria_total_niveldeaccion").html(dato.dashboard_sonometria_total_niveldeaccion);
			$("#dashboard_sonometria_total_fueranorma").html(dato.dashboard_sonometria_total_fueranorma);
			$("#dashboard_recomendaciones_total").html(dato.dashboard_recomendaciones_total);

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
						//menureporte_estado("menureporte_9", parseInt(json.total));
						menureporte_estado("menureporte_9", 1);


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

							menureporte_estado("menureporte_11_1", 1);

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

							menureporte_estado("menureporte_12", 1);

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
	window.open('/reportenom0353responsabledocumento/' + reporteregistro_id + '/' + 1 + '/' + 1);
});


$("#boton_descargarresponsabledoc2").click(function () {
	window.open('/reportenom0353responsabledocumento/' + reporteregistro_id + '/' + 2 + '/' + 1);
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
						// menureporte_estado("menureporte_11_3", json.total);
						// menureporte_estado("menureporte_11_4", json.total);
						// menureporte_estado("menureporte_11_7", json.total);

						menureporte_estado("menureporte_11_3", 1);
						menureporte_estado("menureporte_11_4", 1);
						menureporte_estado("menureporte_11_7", 1);
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
								// menureporte_estado("menureporte_11_3", dato.total);
								// menureporte_estado("menureporte_11_4", dato.total);
								// menureporte_estado("menureporte_11_7", dato.total);

								menureporte_estado("menureporte_11_3", 1);
								menureporte_estado("menureporte_11_4", 1);
								menureporte_estado("menureporte_11_7", 1);

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
								// menureporte_estado("menureporte_11_8", dato.total);
								// menureporte_estado("menureporte_11_9", dato.total);
								menureporte_estado("menureporte_11_8", 1);
								menureporte_estado("menureporte_11_9", 1);

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

									tabla_reporte_definiciones(proyecto.id)
									tabla_reporte_categorias(proyecto.id, reporteregistro_id);
									tabla_reporte_areas(proyecto.id, reporteregistro_id);
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
										title: "No se pudo realizar esta acción",
										text: dato.responseJSON,
										type: "warning", // warning, error, success, info
										buttons: {
											visible: false, // true , false
										},
										timer: 2000,
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

								tabla_reporte_definiciones(proyecto.id);
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
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

								tabla_reporte_definiciones(proyecto.id);
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
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
							catactivo_id: 1,
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						resetForm: false,
						success: function (dato) {
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id);
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
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
		swal({
			title: "¿Generar nueva revision?",
			text: "Informe de NOM035",
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
					// Enviar datos
					$.ajax({
						type: "POST",
						dataType: "json",
						url: "/reportenom0353word",
						data: {
							_token: document.querySelector('meta[name="csrf-token"]')['content'],
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							areas_poe: areas_poe,
							ultimarevision_id: ultimarevision_id,
							crear_revision: 1,

						},
						cache: false,
						success: function (dato) {
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id);
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
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


// $('#tabla_reporte_revisiones tbody').on('click', 'td>button.botondescarga', function()
// {
// 	var botondescarga = this;


// 	var tr = $(this).closest('tr');
// 	var row = datatable_reporterevisiones.row(tr);


// 	// Boton descarga
// 	$("#"+botondescarga.id).html('<i class="fa fa-spin fa-spinner fa-2x"></i>');


// 	setTimeout(function()
// 	{
// 		if (parseInt(row.data().id) == parseInt(ultimarevision_id))
// 		{

// 			setTimeout(function()
// 			{
// 				// Enviar datos
// 				$.ajax({
// 					type: "POST",
// 					dataType: "json",
// 					url: "/reportenom0353word",
// 					data:{
// 						_token: document.querySelector('meta[name="csrf-token"]')['content'],
// 						proyecto_id: proyecto.id,
// 						agente_id: agente_id,
// 						agente_nombre: agente_nombre,
// 						reporteregistro_id: reporteregistro_id,
// 						areas_poe: areas_poe,
// 						ultimarevision_id: ultimarevision_id,
// 						crear_revision: 0,
// 						ambientegrafico: grafica_imgbase64,
// 					},
// 					cache: false,
// 					success:function(dato)
// 					{
// 						ventana = window.open('/reportenom0353worddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


// 						// // Boton descarga
// 						// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


// 						setTimeout(function()
// 						{
// 							tabla_reporte_revisiones(proyecto.id);
// 						}, 6000);


// 						setTimeout(function()
// 						{
// 							if (ventana.window)
// 							{
// 								ventana.window.close();
// 							}
// 						}, 15000);


// 						// // mensaje
// 						// swal({
// 						// 	title: "Correcto",
// 						// 	text: ""+dato.msj,
// 						// 	type: "success", // warning, error, success, info
// 						// 	buttons: {
// 						// 		visible: false, // true , false
// 						// 	},
// 						// 	timer: 1500,
// 						// 	showConfirmButton: false
// 						// });
// 					},
// 					error: function(dato)
// 					{
// 						// mensaje
// 						swal({
// 							title: "Error",
// 							text: "Al intentar crear el informe, intentelo de nuevo.",
// 							type: "error", // warning, error, success, info
// 							buttons: {
// 								visible: false, // true , false
// 							},
// 							timer: 1500,
// 							showConfirmButton: false
// 						});
// 						return false;
// 					}
// 				});//Fin ajax
// 			}, 1000);
// 		}
// 		else
// 		{
// 			ventana = window.open('/reportenom0353worddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


// 			// // Boton descarga
// 			// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


// 			setTimeout(function()
// 			{
// 				tabla_reporte_revisiones(proyecto.id);
// 			}, 6000);


// 			setTimeout(function()
// 			{
// 				if (ventana.window)
// 				{
// 					ventana.window.close();
// 				}
// 			}, 15000);


// 			// // mensaje
// 			// swal({
// 			// 	title: "Correcto",
// 			// 	text: ""+dato.msj,
// 			// 	type: "success", // warning, error, success, info
// 			// 	buttons: {
// 			// 		visible: false, // true , false
// 			// 	},
// 			// 	timer: 1500,
// 			// 	showConfirmButton: false
// 			// });
// 		}
// 	}, 500);
// });


$('#tabla_reporte_revisiones tbody').on('click', 'td>button.botondescarga', function () {
	var botondescarga = this;

	var tr = $(this).closest('tr');
	var row = datatable_reporterevisiones.row(tr);

	// Cambiar el estado del botón a cargando
	$("#" + botondescarga.id).html('<i class="fa fa-spin fa-spinner fa-2x"></i>');

	setTimeout(function () {
		if (parseInt(row.data().id) === parseInt(ultimarevision_id)) {
			// Crear el objeto de exportación
			var ambienteChartImage = chartPngs['ambienteChart'] || '';
			var factoresChartImage = chartPngs['factoresChart'] || '';
			var organizacionChartImage = chartPngs['organizacionChart'] || '';
			var liderazgoChartImage = chartPngs['liderazgoChart'] || '';
			var entornoChartImage = chartPngs['entornoChart'] || '';

			const div = document.getElementById('cardgeneros');
			if (div) {
				console.log('ubico el div');

				html2canvas(div).then((canvas) => {
					const imagenBase64 = canvas.toDataURL('image/png');
					// Guardamos la imagen en una variable global
					// window.capturaGeneros = imagenBase64;
					// console.log('Captura completada. Imagen almacenada en "window.capturaGeneros"');
					// console.log(window.capturaGeneros); // Opcional: Imprime la imagen en consola



					$.ajax({
						type: "POST",
						dataType: "json",
						url: "/reportenom0353word",
						data: {
							_token: document.querySelector('meta[name="csrf-token"]')['content'],
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							areas_poe: areas_poe,
							ultimarevision_id: ultimarevision_id,
							crear_revision: 0,
							ambienteChart: ambienteChartImage,  // Imagen en base64
							factoresChart: factoresChartImage, // Imagen en base64
							organizacionChart: organizacionChartImage, // Imagen en base64
							liderazgoChart: liderazgoChartImage, // Imagen en base64
							entornoChart: entornoChartImage, // Imagen en base64
							edadChart: edadeschart['imgURI'],
							regimenChart: regimenchart['imgURI'], // Imagen en base64
							estadocivilChart: estadocivilchart['imgURI'], // Imagen en base64
							escolaridadChart: escolaridadchart['imgURI'], // Imagen en base64
							experienciaChart: experienciachart['imgURI'], // Imagen en base64
							generosChart: imagenBase64,
							categoriasChart: categoriaschart,
							dominiosChart: dominioschart,
							acontecimientosChart: acontecimientoschart,
							calificacionChart: calificacionchart
							// Imagen en base64
						},
						cache: false,
						success: function (dato) {
							// Abrir la ventana para descargar el archivo
							var ventana = window.open('/reportenom0353worddescargar/' + proyecto.id + "/" + row.data().id + "/" + ultimarevision_id);

							// Actualizar la tabla después de un tiempo
							setTimeout(function () {
								tabla_reporte_revisiones(proyecto.id);
							}, 6000);

							setTimeout(function () {
								if (ventana && ventana.window) {
									ventana.window.close();
								}
							}, 15000);
						},
						error: function (dato) {
							// Mostrar mensaje de error
							swal({
								title: "Error",
								text: "Al intentar crear el informe, inténtelo de nuevo.",
								type: "error",
								buttons: { visible: false },
								timer: 1500,
								showConfirmButton: false
							});
						}
					});
				}).catch((error) => {
					console.error('Error al capturar el div:', error);
				});


			} else {
				console.error('El elemento con id "cardgeneros" no existe.');
			}
		} else {
			// En caso de que no sea la última revisión
			var ventana = window.open('/reportenom0353worddescargar/' + proyecto.id + "/" + row.data().id + "/" + ultimarevision_id);

			// Actualizar tabla después de un tiempo
			setTimeout(function () {
				tabla_reporte_revisiones(proyecto.id);
			}, 6000);

			setTimeout(function () {
				if (ventana && ventana.window) {
					ventana.window.close();
				}
			}, 15000);
		}
	}, 500);
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

// CAMPO DE LMPE 





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
										tabla_reporte_7_6(proyecto.id, reporteregistro_id);


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

function validarPermisosAsignados(proyecto_id) {

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/validacionAsignacionUserProyecto/" + proyecto_id,
		data: {},
		cache: false,
		success: function (dato) {

			if (dato.permisos == 1) {

				$('input[type="submit"], button[type="submit"]').fadeIn(0);

			} else {

				$('input[type="submit"], button[type="submit"]').fadeOut(0);

			}

		}, beforeSend: function () { },
		error: function (dato) {
			// alert('Error: '+dato.msj);
			alert('Los permisos no han sido cargado')

			return false;
		}
	});//Fin ajax
}


am5.ready(function () {
	function createChart(containerId, titleText, subtitleText, data, chartName) {
		// Crear root
		var root = am5.Root.new(containerId);
		root.setThemes([am5themes_Animated.new(root)]);

		// Crear el gráfico
		var chart = root.container.children.push(am5xy.XYChart.new(root, {
			panX: false,
			panY: false,
			wheelX: "none",
			wheelY: "none",
			layout: root.verticalLayout
		}));

		// Títulos
		// chart.children.unshift(
		// 	am5.Label.new(root, {
		// 		text: subtitleText,
		// 		fontSize: 20,
		// 		textAlign: "center",
		// 		x: am5.p50,
		// 		centerX: am5.p50,
		// 		marginTop: 10,
		// 	})
		// );
		chart.children.unshift(
			am5.Label.new(root, {
				text: titleText,
				fontSize: 18,
				fontWeight: "bold",
				textAlign: "center",
				x: am5.p50,
				centerX: am5.p50
			})
		);

		// Leyenda
		var legend = chart.children.push(
			am5.Legend.new(root, {
				centerX: am5.p50,
				x: am5.p50
			})
		);
		legend.labels.template.set("fontSize", 20);

		// Ejes
		var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
			categoryField: "category",
			renderer: am5xy.AxisRendererY.new(root, {
				inversed: true,
				cellStartLocation: 0,
				cellEndLocation: 0.9,
				minGridDistance: 0,
				// opposite: true
			}),
			tooltip: am5.Tooltip.new(root, {})
		}));

		yAxis.get("renderer").grid.template.set("forceHidden", true);

		yAxis.get("renderer").labels.template.setAll({
            fontSize: 18,
            fontWeight: "70",
            centerY: am5.p50,
            centerX: am5.p0,
            textAlign: "center",
            inside: true,  // Poner las etiquetas dentro del área del gráfico
            rotation: 0,
            paddingTop: -100  // Ajustar la posición vertical de las etiquetas
        }); 

		yAxis.data.setAll(data);

		var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
			renderer: am5xy.AxisRendererX.new(root, {
				minGridDistance: 50
			}),
			min: 0,
			strictMinMax: true,  // Esto fuerza a que solo se muestre hasta el valor máximo real
			maxDeviation: 0
		}));

		xAxis.get("renderer").grid.template.set("forceHidden", true);

		// Formatear etiquetas de categorías
		yAxis.get("renderer").labels.template.adapters.add("text", function (text, target) {
			if (target.dataItem) {
				let category = target.dataItem.get("category");
				if (category.startsWith("g1")) {
					return "[bold]" + category.split("-")[1] + "[/]";
				}
				if (category.startsWith("g3")) {
					//target.set("x", target.get("x") + 27); // Mover la categoría a la derecha
					return category.split("-")[1];  // No hace falta modificar el texto, solo moverlo
				}
				return "[bold]" + category.split("-")[1] + "[/]";
			}
			return text;
		});

		// Calcular porcentajes y agregar al dataset
		function calculatePercentages(data) {
			return data.map(item => {
				const total = (item.s1 || 0) + (item.s2 || 0) + (item.s3 || 0) + (item.s4 || 0) + (item.s5 || 0);
				if (total > 0) {
					return {
						...item,
						percentage_s1: ((item.s1 || 0) / total * 100).toFixed(1),
						percentage_s2: ((item.s2 || 0) / total * 100).toFixed(1),
						percentage_s3: ((item.s3 || 0) / total * 100).toFixed(1),
						percentage_s4: ((item.s4 || 0) / total * 100).toFixed(1),
						percentage_s5: ((item.s5 || 0) / total * 100).toFixed(1),
					};
				}
				return { ...item, percentage_s1: 0, percentage_s2: 0, percentage_s3: 0, percentage_s4: 0, percentage_s5: 0 };
			});
		}

		const processedData = calculatePercentages(data);

		// Series
		function makeSeries(name, fieldName, percentageFieldName, color) {
			var series = chart.series.push(am5xy.ColumnSeries.new(root, {
				name: name,
				xAxis: xAxis,
				yAxis: yAxis,
				stacked: true,
				valueXField: fieldName,
				categoryYField: "category",
				stroke: color,
				fill: color
			}));

			series.columns.template.setAll({
				tooltipText: "{name}, {categoryY}: {valueX} ({percentage}%)",
				width: am5.percent(100),
				tooltipY: 0
			});

			series.bullets.push(function () {
				return am5.Bullet.new(root, {
					locationX: 0.5,
					locationY: 0.5,
					sprite: am5.Label.new(root, {
						text: "{valueX} ({percentage}%)",
						centerX: am5.p50,
						centerY: am5.p50,
						populateText: true,
						fontSize: "18px",
						fill: am5.color(0x000000),
						fontWeight: "bold"
					})
				});
			});

			series.data.setAll(
				processedData.map(item => ({
					...item,
					percentage: item[percentageFieldName],
				}))
			);
			series.appear();
			legend.data.push(series);
		}

		// Crear series
		makeSeries("Muy alto", "s1", "percentage_s1", am5.color(0xFF0000));
		makeSeries("Alto", "s2", "percentage_s2", am5.color(0xF7AA32));
		makeSeries("Medio", "s3", "percentage_s3", am5.color(0xFFFF00));
		makeSeries("Bajo", "s4", "percentage_s4", am5.color(0x00B050));
		makeSeries("Nulo", "s5", "percentage_s5", am5.color(0x00B0F0));


		chart.appear(1000, 100).then(() => {
			setTimeout(() => {
				if (typeof am5plugins_exporting !== 'undefined') {
					console.log('Plugin de exportación dispo');

					var exporting = am5plugins_exporting.Exporting.new(root, {
						menu: am5plugins_exporting.ExportingMenu.new(root, {}),
						dpi: 300, // Ajusta el DPI para mejorar la calidad de la imagen exportada
						// También puedes ajustar el tamaño de la imagen, si lo deseas
						maxWidth: 2000, // Ancho máximo en píxeles
						maxHeight: 2000,
					});
					console.log('si creo el exporting');

					exporting.export("png").then(function (data) {
						chartPngs[chartName] = data;
						console.log(chartName + " exportado exitosamente");
					}).catch(error => console.error('Error al exportar:', error));
				} else {
					console.log('Plugin de exportación no disponible');
				}
			}, 1000); // Aumenté el timeout
		});
	}




	// Crear gráficos
	createChart(
		"ambienteChart",
		"Categoría\n Ambiente de trabajo \n\n",
		"(Nivel de riesgo/NOM-035-STPS-2018)\n\n",
		[{
			category: "g1-Ambiente de trabajo",
			s1: 2, s2: 3, s3: 1, s4: 3, s5: 1
		}, {
			category: "g3-\n\n\nDominios:"
		}, {
			category: "g2-Condiciones del ambiente de trabajo",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		}],
		'ambienteChart'
	);

	createChart(
		"factoresChart",
		"Categoría\n Factores propios de la actividad \n\n",
		"(Nivel de riesgo/NOM-035-STPS-2018)\n\n",
		[{
			category: "g1-Factores propios de la actividad",
			s1: 2, s2: 3, s3: 1, s4: 3, s5: 1
		}, {
			category: "g3-\n\n\nDominios:"
		}, {
			category: "g2-Carga de trabajo",
			s1: 5, s2: 1, s3: 2, s4: 1, s5: 1
		}, {
			category: "g2-Falta de control sobre el trabajo",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		}],
		'factoresChart'
	);

	createChart(
		"organizacionChart",
		"Categoría\n Organización del tiempo de trabajo \n\n",
		"(Nivel de riesgo/NOM-035-STPS-2018)\n\n",
		[{
			category: "g1-Organización del tiempo de trabajo",
			s1: 2, s2: 3, s3: 1, s4: 3, s5: 1
		}, {
			category: "g3-\n\n\nDominios:"
		}, {
			category: "g2-Jornada de trabajo",
			s1: 5, s2: 1, s3: 2, s4: 1, s5: 1
		},  {
			category: "g2-Interferencia trabajo/familia",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		}],
		'organizacionChart'
	);

	createChart(
		"liderazgoChart",
		"Categoría\n Liderazgo y relaciones en el trabajo \n\n\n",
		"(Nivel de riesgo/NOM-035-STPS-2018)\n\n",
		[{
			category: "g1-Liderazgo y relaciones en el trabajo",
			s1: 2, s2: 3, s3: 1, s4: 3, s5: 1
		}, {
			category: "g3-\n\n\nDominios:"
		},
		{
			category: "g2-Liderazgo",
			s1: 5, s2: 1, s3: 2, s4: 1, s5: 1
		},
		 {
			category: "g2-Relaciones en el trabajo",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		},
		{
			category: "g2-Violencia",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		}],
		'liderazgoChart'
	);

	createChart(
		"entornoChart",
		"Categoría\n Entorno organizacional \n\n",
		"(Nivel de riesgo/NOM-035-STPS-2018)\n\n",
		[{
			category: "g1-Entorno organizacional",
			s1: 2, s2: 3, s3: 1, s4: 3, s5: 1
		}, {
			category: "g3-\n\n\nDominios:"
		}, {
			category: "g2-Reconocimiento del desempeño",
			s1: 5, s2: 1, s3: 2, s4: 1, s5: 1
		},
		 {
			category: "g2-Insuficiente sentido de pertenencia e inestabilidad",
			s1: 3, s2: 2, s3: 2, s4: 2, s5: 1
		}],
		'entornoChart'
	);
	// dashboard

	var guia1_root = am5.Root.new("guia1Chart");

	// Establecer temas
	guia1_root.setThemes([
		am5themes_Animated.new(guia1_root)
	]);
	
	// Crear gráfico
	var guia1_chart = guia1_root.container.children.push(am5percent.PieChart.new(guia1_root, {
		startAngle: 180,
		endAngle: 360,
		layout: guia1_root.verticalLayout,
		innerRadius: am5.percent(50),
		paddingBottom: 0
	}));
	
	// Definir los colores primero
	const colors2 = {
		"Requiere valoración clinica": 0xFF0000,
		"No requiere valoración clinica": 0x00B0F0
	};
	
	// Crear leyenda con texto más grande y grueso
	let legend3 = guia1_chart.children.push(
		am5.Legend.new(guia1_root, {
			centerX: am5.percent(50),
			x: am5.percent(50),
			layout: guia1_root.verticalLayout,
			fontSize: 17,
			fontWeight: "bold",
			marginTop: -10,  // Reducir el margen superior
			dy: -10 // Mover la leyenda hacia arriba
		})
	);
	
	// Crear serie
	var guia1_series = guia1_chart.series.push(am5percent.PieSeries.new(guia1_root, {
		startAngle: 180,
		endAngle: 360,
		valueField: "value",
		categoryField: "category",
		alignLabels: false
	}));
	
	// Configurar etiquetas
	guia1_series.labels.template.setAll({
		fontSize: 17,
		fontWeight: "bold",
		text: "{value}"
	});
	
	// Configurar los colores y estilos de la leyenda
	legend3.labels.template.setAll({
		fontSize: 17,
		fontWeight: "bold"
	});
	
	legend3.valueLabels.template.setAll({
		fontSize: 17,
		fontWeight: "bold"
	});
	
	legend3.markers.template.setAll({
		width: 20,
		height: 20
	});
	
	// Estilo de las rebanadas
	guia1_series.slices.template.setAll({
		cornerRadius: 5,
		stroke: am5.color(0xFFFFFF)
	});
	
	guia1_series.slices.template.adapters.add("fill", function(fill, target) {
		var category = target.dataItem.get("category");
		return am5.color(colors2[category] || fill);
	});
	
	// Establecer datos
	guia1_series.data.setAll([
		{ value: 2, category: "Requiere valoración clinica" },
		{ value: 69, category: "No requiere valoración clinica" },
	]);
	
	// Conectar la leyenda con la serie
	legend3.data.setAll(guia1_series.dataItems);
	
	// Animación
	guia1_series.appear(1000, 100).then(() => {
		setTimeout(() => {
			if (typeof am5plugins_exporting !== 'undefined') {
				var exporting = am5plugins_exporting.Exporting.new(guia1_root, {
					menu: am5plugins_exporting.ExportingMenu.new(guia1_root, {}),
					dpi: 300,
					maxWidth: 2000,
					maxHeight: 2000,
				});
	
				exporting.export("png").then(function (data) {
					acontecimientoschart = data;
					console.log("guia1_chart exportado exitosamente");
				}).catch(error => console.error('Error al exportar:', error));
			} else {
				console.log('Plugin de exportación no disponible');
			}
		}, 1000);
	});
	

// 	// Crear un nuevo objeto root para el gráfico de régimen
// 	var rootGraficoGuia1 = am5.Root.new("guia1Chart"); // Cambié root a rootGraficoRegimen

// 	// Establecer el tema para el gráfico
// 	rootGraficoGuia1.setThemes([
// 		am5themes_Animated.new(rootGraficoGuia1)
// 	]);

// 	// Crear el gráfico de tipo Pie para el régimen
// 	var graficoguia1 = rootGraficoGuia1.container.children.push(am5percent.PieChart.new(rootGraficoGuia1, {
// 		layout: rootGraficoGuia1.verticalLayout,
// 		innerRadius: am5.percent(50)
// 	}));

// 	// Crear la serie de tipo Pie para el gráfico de régimen
// 	var seriegrafica1 = graficoguia1.series.push(am5percent.PieSeries.new(rootGraficoGuia1, {
// 		valueField: "valorgrafica1",  // Personalizado
// 		categoryField: "categoriagrafica1",  // Personalizado
// 		alignLabels: false
// 	}));

// 	// Configuración de las etiquetas en formato circular
// 	seriegrafica1.labels.template.setAll({
// 		textType: "circular",
// 		centerX: 0,
// 		centerY: 0
// 	});
// 	seriegrafica1.slices.template.setAll({
// 		tooltipText: "{categoryField}: {valueField}%", // Personaliza el texto del tooltip
// 		stroke: am5.color(0xffffff), // Color del borde
// 		strokeWidth: 2 // Grosor del borde
// 	});

// 	// Cambiar colores de las secciones
// 	seriegrafica1.get("colors").set("colors", [
// 		am5.color(0xff0000), // Rojo
// 		am5.color(0x0098c7)  // Verde
// 	]);
// 	// Establecer los datos para el gráfico de régimen (por ejemplo, plantas, sindicalizados, etc.)
// 	seriegrafica1.data.setAll([
// 		{ valorgrafica1: 2, categoriagrafica1: "Requiere valoración clinica" },
// 		{ valorgrafica1: 69, categoriagrafica1: "No requiere valoración clinica" },
// 	]);

// 	// Crear la leyenda para el gráfico de régimen
// 	var leyendagrafico1 = graficoguia1.children.push(am5.Legend.new(rootGraficoGuia1, {
// 		centerX: am5.percent(50),
// 		x: am5.percent(50),
// 		marginTop: 15,
// 		marginBottom: 15,
// 	}));
// 	leyendagrafico1.labels.template.setAll({
// 		fontSize: 14, // Tamaño de letra
// 		fontWeight: "bold", // Peso de la fuente
// 		fill: am5.color(0x333333) // Color de la letra
// 	});

// 	leyendagrafico1.markers.template.setAll({
// 		width: 20, // Ancho del marcador
// 		height: 20 // Alto del marcador
// 	});
// 	leyendagrafico1.data.setAll(seriegrafica1.dataItems);

// 	seriegrafica1.appear(1000, 100).then(() => {
// 		setTimeout(() => {
// 			if (typeof am5plugins_exporting !== 'undefined') {
// 				console.log('Plugin de exportación dispo');

// 				var exporting = am5plugins_exporting.Exporting.new(rootGraficoGuia1, {
// 					menu: am5plugins_exporting.ExportingMenu.new(rootGraficoGuia1, {}),
// 					dpi: 300, // Ajusta el DPI para mejorar la calidad de la imagen exportada
// 					// También puedes ajustar el tamaño de la imagen, si lo deseas
// 					maxWidth: 2000, // Ancho máximo en píxeles
// 					maxHeight: 2000,
// 				});
// 				console.log('si creo el exporting');

// 				exporting.export("png").then(function (data) {
// 					acontecimientoschart = data;
// 					console.log("acontecimientochart exportado exitosamente");
// 				}).catch(error => console.error('Error al exportar:', error));
// 			} else {
// 				console.log('Plugin de exportación no disponible');
// 			}
// 		}, 1000); // Aumenté el timeout
// 	});

});
var rootConsolidadoChart2 = am5.Root.new("consolidadoChart2");

// Crear tema personalizado
const customThemeConsolidado2 = am5.Theme.new(rootConsolidadoChart2);
customThemeConsolidado2.rule("Label").set("fontSize", 20);
customThemeConsolidado2.rule("Grid").set("strokeOpacity", 0); // Ocultar las líneas de porcentaje

// Definir los estilos para los ejes dentro del tema
customThemeConsolidado2.rule("AxisRenderer").setAll({
	background: am5.Rectangle.new(rootConsolidadoChart2, {
		fill: am5.color(0x000000),
		fillOpacity: 0.7
	})
});

// Establecer temas
rootConsolidadoChart2.setThemes([am5themes_Animated.new(rootConsolidadoChart2), customThemeConsolidado2]);

// Datos (con valores fijos para las 5 series)
var dataConsolidado2 = [
		{ "category": "Condiciones en el \nambiente de trabajo", "Nulo": 0.10, "Bajo": 0.15, "Medio": 0.20, "Alto": 0.25, "Muy alto": 0.30 },
		{ "category": "Falta de control \nsobre el trabajo", "Nulo": 0.35, "Bajo": 0.18, "Medio": 0.22, "Alto": 0.15, "Muy alto": 0.10 },
		{ "category": "Carga de trabajo", "Nulo": 0.09, "Bajo": 0.36, "Medio": 0.21, "Alto": 0.26, "Muy alto": 0.08 },
		{ "category": "Jornada de\n trabajo", "Nulo": 0.14, "Bajo": 0.19, "Medio": 0.23, "Alto": 0.27, "Muy alto": 0.17 },
		{ "category": "Interferencia en la\n relacion\n trabajo-familia", "Nulo": 0.16, "Bajo": 0.20, "Medio": 0.24, "Alto": 0.29, "Muy alto": 0.11 },
		{ "category": "Liderazgo", "Nulo": 0.11, "Bajo": 0.16, "Medio": 0.19, "Alto": 0.24, "Muy alto": 0.30 },
		{ "category": "Relaciones en el trabajo", "Nulo": 0.14, "Bajo": 0.17, "Medio": 0.22, "Alto": 0.27, "Muy alto": 0.20 },
		{ "category": "Violencia", "Nulo": 0.25, "Bajo": 0.10, "Medio": 0.20, "Alto": 0.40, "Muy alto": 0.05 }
	];

var colorSetConsolidado2 = am5.ColorSet.new(rootConsolidadoChart2, {});

// Modificar formato de números
rootConsolidadoChart2.numberFormatter.set("numberFormat", "#%");

// Crear gráfico
var chartConsolidado2 = rootConsolidadoChart2.container.children.push(am5radar.RadarChart.new(rootConsolidadoChart2, {
	panX: false,
	panY: false,
	wheelX: "none", // Removed zoom functionality
    wheelY: "none",
	innerRadius: am5.percent(10),
	radius: am5.percent(85)
}));

// Crear ejes
var categoryAxisRendererConsolidado2 = am5radar.AxisRendererCircular.new(rootConsolidadoChart2, {
	innerRadius: am5.percent(10)
});
var categoryAxisConsolidado2 = chartConsolidado2.xAxes.push(am5xy.CategoryAxis.new(rootConsolidadoChart2, {
	categoryField: "category",
	renderer: categoryAxisRendererConsolidado2
}));

categoryAxisRendererConsolidado2.labels.template.setAll({
	fill: am5.color(0x000000),
	fontSize: 24,
	fontWeight: "bold",
	paddingLeft: 5,
	paddingRight: 5,
	paddingTop: 2,
	paddingBottom: 2,
	radius: 5,
	centerX: am5.p50,
    centerY: am5.p50,
	 textAlign: "center"
});

categoryAxisConsolidado2.data.setAll(dataConsolidado2);

// Crear eje de valor
var valueAxisConsolidado2 = chartConsolidado2.yAxes.push(am5xy.ValueAxis.new(rootConsolidadoChart2, {
	renderer: am5radar.AxisRendererRadial.new(rootConsolidadoChart2, {}),
	min: 0,
	max: 1,
	strictMinMax: true,
	extraMax: 0.1
}));

valueAxisConsolidado2.get("renderer").labels.template.setAll({
	visible: false
});

// Crear series apiladas
var seriesNamesConsolidado2 = ["Nulo", "Bajo", "Medio", "Alto", "Muy alto"];
var seriesColorsConsolidado2 = [
	am5.color(0x00B0F0),
	am5.color(0x00B050),
	am5.color(0xFFFF00),
	am5.color(0xF7AA32),
	am5.color(0xFF0000)
];

seriesNamesConsolidado2.forEach((seriesName, index) => {
	var series = chartConsolidado2.series.push(am5radar.RadarColumnSeries.new(rootConsolidadoChart2, {
		stacked: true,
		name: seriesName,
		xAxis: categoryAxisConsolidado2,
		yAxis: valueAxisConsolidado2,
		valueYField: seriesName,
		categoryXField: "category"
	}));

	series.columns.template.setAll({
		tooltipText: "{name}: {valueY.formatNumber('#.##%')}",
		cornerRadius: 0,
		strokeOpacity: 1, // Changed from 0 to 1 to show borders
        stroke: am5.color(0x000000), // Added black border
        strokeWidth: 0.5, // Added border width
		fill: seriesColorsConsolidado2[index],
		width: am5.percent(100)
	});

	// Agregar etiquetas con porcentajes
	series.bullets.push(function () {
		return am5.Bullet.new(rootConsolidadoChart2, {
			sprite: am5.Label.new(rootConsolidadoChart2, {
				text: "{valueY.formatNumber('#.##%')}",
				populateText: true,
				centerX: am5.p50,
				centerY: am5.p50,
				fill: am5.color(0x000000),
				fontWeight: "bold"
			})
		});
	});

	// Asignar datos
	series.data.setAll(dataConsolidado2);
});

// Añadir un título al gráfico
// var title = chartConsolidado2.children.unshift(am5.Label.new(rootConsolidadoChart2, {
// 	text: "FACTORES PSICOSOCIALES NOM-035-STPS-2028\n Dominios",
// 	fontSize: 14,
// 	fontWeight: "bold",
// 	textAlign: "center",
// 	x: am5.p50,
// 	centerX: am5.p50,
// 	y: 0,
// 	paddingBottom: 1
// }));

// Crear la leyenda
var legendConsolidado2 = chartConsolidado2.children.push(am5.Legend.new(rootConsolidadoChart2, {
	centerX: am5.p50,
	x: am5.p50,
	y: am5.p100,
	layout: rootConsolidadoChart2.horizontalLayout,
	marginTop: 1
}));

// Vincular colores y nombres de las series a la leyenda
seriesNamesConsolidado2.forEach((seriesName, index) => {
	var series = chartConsolidado2.series.getIndex(index);
	series.legendSettings = {
		labelText: `Factor ${seriesName}`,
		fill: seriesColorsConsolidado2[index]
	};
});

// Agregar leyenda con los datos correctos
legendConsolidado2.data.setAll(chartConsolidado2.series.values);

chartConsolidado2.appear(1000, 100).then(() => {
		setTimeout(() => {
			if (typeof am5plugins_exporting !== 'undefined') {
				console.log('Plugin de exportación dispo');
	
				var exporting = am5plugins_exporting.Exporting.new(rootConsolidadoChart2, {
					menu: am5plugins_exporting.ExportingMenu.new(rootConsolidadoChart2, {}),
					dpi: 300, // Ajusta el DPI para mejorar la calidad de la imagen exportada
					// También puedes ajustar el tamaño de la imagen, si lo deseas
					maxWidth: 2000, // Ancho máximo en píxeles
					maxHeight: 2000,
				});
				console.log('si creo el exporting');
	
				exporting.export("png").then(function (data) {
					dominioschart = data;
					console.log("consolidado1 categorias exportado exitosamente");
				}).catch(error => console.error('Error al exportar:', error));
			} else {
				console.log('Plugin de exportación no disponible');
			}
		}, 1000); // Aumenté el timeout
	});

var calificaciones_root = am5.Root.new("calificacionChart");

// Establecer temas
calificaciones_root.setThemes([
    am5themes_Animated.new(calificaciones_root)
]);

// Crear gráfico
var calificaciones_chart = calificaciones_root.container.children.push(am5percent.PieChart.new(calificaciones_root, {
    startAngle: 180,
    endAngle: 360,
    layout: calificaciones_root.verticalLayout,
    innerRadius: am5.percent(50),
	paddingBottom: 0 
}));

// Definir los colores primero
const colors = {
    "Muy Alto": 0xFF0000,
    "Alto": 0xF7AA32,
    "Medio": 0xFFFF00,
    "Bajo": 0x00B050,
    "Nulo": 0x00B0F0
};

// Crear leyenda con texto más grande y grueso
let legend2 = calificaciones_chart.children.push(
    am5.Legend.new(calificaciones_root, {
        centerX: am5.percent(50),
        x: am5.percent(50),
        layout: calificaciones_root.verticalLayout,
        fontSize: 12,
        fontWeight: "bold",
		marginTop: -10,  // Reducir el margen superior
        dy: -10 // Mover la leyenda hacia arriba
    })
);

// Crear serie
var calificaciones_series = calificaciones_chart.series.push(am5percent.PieSeries.new(calificaciones_root, {
    startAngle: 180,
    endAngle: 360,
    valueField: "value",
    categoryField: "category",
    alignLabels: false
}));

// Configurar etiquetas
calificaciones_series.labels.template.setAll({
    fontSize: 12,
    fontWeight: "bold",
    text: "{value}"
});

// Configurar los colores y estilos de la leyenda
legend2.labels.template.setAll({
    fontSize: 12,
    fontWeight: "bold"
});

legend2.valueLabels.template.setAll({
    fontSize: 12,
    fontWeight: "bold"
});

legend2.markers.template.setAll({
    width: 20,
    height: 20
});

// Estilo de las rebanadas
calificaciones_series.slices.template.setAll({
    cornerRadius: 5,
    stroke: am5.color(0xFFFFFF)
});

calificaciones_series.slices.template.adapters.add("fill", function(fill, target) {
    var category = target.dataItem.get("category");
    return am5.color(colors[category] || fill);
});

// Establecer datos
calificaciones_series.data.setAll([
    { value: 1, category: "Muy Alto" },
    { value: 31, category: "Alto" },
    { value: 75, category: "Medio" },
    { value: 89, category: "Bajo" },
    { value: 55, category: "Nulo" }
]);

// Conectar la leyenda con la serie
legend2.data.setAll(calificaciones_series.dataItems);

// Animación
calificaciones_series.appear(1000, 100).then(() => {
    setTimeout(() => {
        if (typeof am5plugins_exporting !== 'undefined') {
            var exporting = am5plugins_exporting.Exporting.new(calificaciones_root, {
                menu: am5plugins_exporting.ExportingMenu.new(calificaciones_root, {}),
                dpi: 300,
                maxWidth: 2000,
                maxHeight: 2000,
            });

            exporting.export("png").then(function (data) {
                calificacionchart = data;
                console.log("calificacion chart exportado exitosamente");
            }).catch(error => console.error('Error al exportar:', error));
        } else {
            console.log('Plugin de exportación no disponible');
        }
    }, 1000);
});



var rootConsolidadoChart1 = am5.Root.new("consolidadoChart");

// Crear tema personalizado
const customThemeConsolidado1 = am5.Theme.new(rootConsolidadoChart1);
customThemeConsolidado1.rule("Label").set("fontSize", 17);
customThemeConsolidado1.rule("Grid").set("strokeOpacity", 0); // Ocultar las líneas de porcentaje

// Definir los estilos para los ejes dentro del tema
customThemeConsolidado1.rule("AxisRenderer").setAll({
	background: am5.Rectangle.new(rootConsolidadoChart1, {
		fill: am5.color(0x000000),
		fillOpacity: 0.7
	})
});

// Establecer temas
rootConsolidadoChart1.setThemes([am5themes_Animated.new(rootConsolidadoChart1), customThemeConsolidado1]);

// Datos (con valores fijos para las 5 series)
var dataConsolidado1 = [
	{ "category": "Ambiente de\n trabajo", "Nulo": 0.35, "Bajo": 0.18, "Medio": 0.22, "Alto": 0.15, "Muy alto": 0.10 },
	{ "category": "Factores Propios\n de la actividad", "Nulo": 0.09, "Bajo": 0.36, "Medio": 0.21, "Alto": 0.26, "Muy alto": 0.08 },
	{ "category": "Organización del \ntiempo de trabajo", "Nulo": 0.14, "Bajo": 0.19, "Medio": 0.23, "Alto": 0.27, "Muy alto": 0.17 },
	{ "category": "Liderazgo y relaciones\n en el trabajo", "Nulo": 0.16, "Bajo": 0.20, "Medio": 0.24, "Alto": 0.29, "Muy alto": 0.11 },
	{ "category": "Entorno organizacional", "Nulo": 0.11, "Bajo": 0.16, "Medio": 0.19, "Alto": 0.24, "Muy alto": 0.30 },
];

var colorSetConsolidado1 = am5.ColorSet.new(rootConsolidadoChart1, {});

// Modificar formato de números
rootConsolidadoChart1.numberFormatter.set("numberFormat", "#%");

// Crear gráfico
var chartConsolidado1 = rootConsolidadoChart1.container.children.push(am5radar.RadarChart.new(rootConsolidadoChart1, {
	panX: false,
	panY: false,
	wheelX: "none", // Removed zoom functionality
    wheelY: "none",
	innerRadius: am5.percent(10),
	radius: am5.percent(85)
}));

chartConsolidado1.zoomOutButton.set("forceHidden", true);

// Crear ejes
var categoryAxisRendererConsolidado1 = am5radar.AxisRendererCircular.new(rootConsolidadoChart1, {
	innerRadius: am5.percent(10)
});


var categoryAxisConsolidado1 = chartConsolidado1.xAxes.push(am5xy.CategoryAxis.new(rootConsolidadoChart1, {
	categoryField: "category",
	renderer: categoryAxisRendererConsolidado1
}));

categoryAxisRendererConsolidado1.labels.template.setAll({
	fill: am5.color(0x000000),
	fontSize: 20,
	fontWeight: "bold",
	paddingLeft: 5,
	paddingRight: 5,
	paddingTop: 2,
	paddingBottom: 2
});

categoryAxisConsolidado1.data.setAll(dataConsolidado1);

// Crear eje de valor
var valueAxisConsolidado1 = chartConsolidado1.yAxes.push(am5xy.ValueAxis.new(rootConsolidadoChart1, {
	renderer: am5radar.AxisRendererRadial.new(rootConsolidadoChart1, {}),
	min: 0,
	max: 1,
	strictMinMax: true,
	extraMax: 0.1
}));

valueAxisConsolidado1.get("renderer").labels.template.setAll({
	visible: false
});

// Crear series apiladas
var seriesNamesConsolidado1 = ["Nulo", "Bajo", "Medio", "Alto", "Muy alto"];
var seriesColorsConsolidado1 = [
	am5.color(0x00B0F0),
	am5.color(0x00B050),
	am5.color(0xFFFF00),
	am5.color(0xF7AA32),
	am5.color(0xFF0000)
];

seriesNamesConsolidado1.forEach((seriesName, index) => {
	var series = chartConsolidado1.series.push(am5radar.RadarColumnSeries.new(rootConsolidadoChart1, {
		stacked: true,
		name: seriesName,
		xAxis: categoryAxisConsolidado1,
		yAxis: valueAxisConsolidado1,
		valueYField: seriesName,
		categoryXField: "category"
	}));

	series.columns.template.setAll({
		tooltipText: "{name}: {valueY.formatNumber('#.##%')}",
		cornerRadius: 0,
		strokeOpacity: 1, // Changed from 0 to 1 to show borders
        stroke: am5.color(0x000000), // Added black border
        strokeWidth: 0.5, // Added border width
		fill: seriesColorsConsolidado1[index],
		width: am5.percent(100)
	});

	// Agregar etiquetas con porcentajes
	series.bullets.push(function () {
		return am5.Bullet.new(rootConsolidadoChart1, {
			sprite: am5.Label.new(rootConsolidadoChart1, {
				text: "{valueY.formatNumber('#.##%')}",
				populateText: true,
				centerX: am5.p50,
				centerY: am5.p50,
				fill: am5.color(0x000000),
				fontWeight: "bold"
			})
		});
	});

	// Asignar datos
	series.data.setAll(dataConsolidado1);
});

// Añadir un título al gráfico
// var title = chartConsolidado1.children.unshift(am5.Label.new(rootConsolidadoChart1, {
// 	text: "FACTORES PSICOSOCIALES NOM-035-STPS-2028\n Categorias",
// 	fontSize: 14,
// 	fontWeight: "bold",
// 	textAlign: "center",
// 	x: am5.p50,
// 	centerX: am5.p50,
// 	y: 0,
// 	paddingBottom: 1
// }));

// Crear la leyenda
var legendConsolidado1 = chartConsolidado1.children.push(am5.Legend.new(rootConsolidadoChart1, {
	centerX: am5.p50, // Centrar horizontalmente
	x: am5.p50,
	y: am5.p100, // Colocarla en la parte inferior del gráfico
	layout: rootConsolidadoChart1.horizontalLayout, // Cambiar a disposición horizontal
	marginTop: 3
}));

// Vincular colores y nombres de las series a la leyenda
seriesNamesConsolidado1.forEach((seriesName, index) => {
	var series = chartConsolidado1.series.getIndex(index); // Obtener la serie actual
	series.legendSettings = {
		labelText: `Factor ${seriesName}`, // Nombre que aparecerá en la leyenda
		fill: seriesColorsConsolidado1[index] // Color de la serie en la leyenda
	};
});

// Agregar leyenda con los datos correctos
legendConsolidado1.data.setAll(chartConsolidado1.series.values);

chartConsolidado1.appear(1000, 100).then(() => {
	setTimeout(() => {
		if (typeof am5plugins_exporting !== 'undefined') {
			var exporting = am5plugins_exporting.Exporting.new(rootConsolidadoChart1, {
				menu: am5plugins_exporting.ExportingMenu.new(rootConsolidadoChart1, {}),
				dpi: 300, // Ajusta el DPI para mejorar la calidad de la imagen exportada
				// También puedes ajustar el tamaño de la imagen, si lo deseas
				maxWidth: 2000, // Ancho máximo en píxeles
				maxHeight: 2000,
			});
			exporting.export("png").then(function (data) {
				categoriaschart = data;
				console.log("dominios chart consolidado exportado exitosamente");
			}).catch(error => console.error('Error al exportar:', error));
		} else {
			console.log('Plugin de exportación no disponible');
		}
	}, 1000); // Aumenté el timeout
});

const edadData = [
	{ categoria: "Menos de 18 años", total: 10, color: "#98c11d" },
	{ categoria: "18 a 24 años", total: 9, color: "#2c6e49" },
	{ categoria: "25 a 34 años", total: 6, color: "#154b75" },
	{ categoria: "35 a 44 años", total: 5, color: "#0098c7" },
	{ categoria: "45 a 54 años", total: 4, color: "#171738" },
	{ categoria: "55 a 64 años", total: 3, color: "#6F4F98" },
	{ categoria: "65 años o más", total: 1, color: "#9A33B2" }
];

// Extraer categorías, valores y colores
const categoriasEdad = edadData.map(item => item.categoria);
const valoresEdad = edadData.map(item => item.total);
const coloresEdad = edadData.map(item => item.color);

// Configuración del gráfico con Distributed Columns
const optionsEdades = {
	series: [{
		data: valoresEdad
	}],
	chart: {
		type: 'bar',
		height: 220
	},
	colors: coloresEdad, // Colores personalizados para cada barra
	plotOptions: {
		bar: {
			columnWidth: '65%',
			distributed: true,
			borderRadius: 3
		}
	},
	dataLabels: {
		enabled: true,
		style: {
			fontSize: '18px',
			fontWeight: 'bold',
			colors: ['#ffffff']
		},
		dropShadow: {
			enabled: true,
			left: 0,
			top: -2,
			opacity: 0.5
		},
		formatter: function (val) {
			return val; // Mostrar el valor absoluto en la barra
		}
	},
	legend: {
		show: false // Ocultar la leyenda
	},
	xaxis: {
		categories: categoriasEdad, // Categorías en el eje X
		labels: {
			style: {
				colors: coloresEdad,
				fontSize: '0px',
			}
		}
	}
};

// Crear y renderizar el gráfico de Rango de Edades
const chartEdades = new ApexCharts(document.querySelector("#grafica_edad"), optionsEdades);
chartEdades.render().then(() => {
	setTimeout(function () {
		chartEdades.dataURI().then(uri => {
			//console.log(uri);
			edadeschart = uri;
		});
	}, 4000);
});

const estadoCivilData = [
	{ categoria: "Soltero(a)", total: 11, color: "#98c11d" },
	{ categoria: "Casado(a)", total: 54, color: "#2c6e49" },
	{ categoria: "Divorciado(a)", total: 10, color: "#154b75" },
	{ categoria: "Viudo(a)", total: 2, color: "#0098c7" }
];

// Extraer categorías, valores y colores
const categoriasEstadoCivil = estadoCivilData.map(item => item.categoria);
const valoresEstadoCivil = estadoCivilData.map(item => item.total);
const coloresEstadoCivil = estadoCivilData.map(item => item.color);

// Configuración del gráfico con Distributed Columns
const optionsEstadoCivil = {
	series: [{
		data: valoresEstadoCivil
	}],
	chart: {
		type: 'bar',
		height: 220,
		events: {
			click: function (chart, w, e) {
				// Acción al hacer clic (si es necesario)
			}
		}
	},
	colors: coloresEstadoCivil, // Colores personalizados para cada barra
	plotOptions: {
		bar: {
			columnWidth: '65%',
			distributed: true,
			borderRadius: 3,
			dataLabels: {
				position: 'top' // Coloca los números arriba de las barras
			}
		}
	},
	dataLabels: {
		enabled: true, // Mostrar valores en las barras
		offsetY: -20,
		style: {
			fontSize: '15px',
			fontWeight: 'bold',
			colors: ['#000']
		},
		formatter: function (val) {
			return val; // Mostrar el valor absoluto en la barra
		}
	},
	legend: {
		show: false // Ocultar la leyenda
	},
	xaxis: {
		categories: categoriasEstadoCivil, // Categorías en el eje X
		labels: {
			style: {
				colors: coloresEstadoCivil,
				fontSize: '0px',
			}
		}
	}
};

// Crear y renderizar el gráfico de Estado Civil
const chartEstadoCivil = new ApexCharts(document.querySelector("#grafica_estadocivil"), optionsEstadoCivil);
chartEstadoCivil.render().then(() => {
	setTimeout(function () {
		chartEstadoCivil.dataURI().then(uri => {
			//console.log(uri);
			estadocivilchart = uri;
		});
	}, 4000);
});

const datosEscolaridad = [
	{ categoria: "Primaria", valor: 2 },
	{ categoria: "Secundaria", valor: 60 },
	{ categoria: "Preparatoria", valor: 9 },
	{ categoria: "Licenciatura", valor: 0 },
	{ categoria: "Especialidad", valor: 0 },
	{ categoria: "Maestría", valor: 0 },
	{ categoria: "Doctorado", valor: 0 },
	{ categoria: "Postdoctorado", valor: 0 },
];

const datosFiltrados = datosEscolaridad.filter(item => item.valor > 0);

// Extraer categorías y valores de los datos filtrados
const categorias = datosFiltrados.map(item => item.categoria);
const valores = datosFiltrados.map(item => item.valor);
// Extraer categorías y valores
//   const categorias = datosEscolaridad.map(item => item.categoria);
//   const valores = datosEscolaridad.map(item => item.valor);

// Configuración de la gráfica
const options = {
	chart: {
		type: 'bar',
		height: 240,
		toolbar: {
			show: false // Ocultar herramientas de zoom y exportación
		}
	},
	plotOptions: {
		bar: {
			horizontal: true, // Barras horizontales
			borderRadius: 4, // Bordes redondeados
			barHeight: '70%', // Ajustar altura de las barras
		}
	},
	dataLabels: {
		enabled: true, // Mostrar etiquetas con valores
		style: {
			fontSize: '15px',
			fontWeight: 'bold',
			colors: ['#ffffff']
		},
		dropShadow: {
			enabled: true,
			left: 2,
			top: 2,
			opacity: 0.5
		},
	},
	xaxis: {
		categories: categorias, // Categorías en el eje Y
		labels: {
			style: {
				fontSize: '12px',
				fontWeight: 'bold',
			}
		}
	},
	yaxis: {
		labels: {
			style: {
				fontSize: '12px',
				fontWeight: 'bold',
			}
		}
	},
	series: [
		{
			name: "Escolaridad",
			data: valores, // Valores de las categorías
		}
	],
	colors: [
		"#98c11d",
	],
	tooltip: {
		theme: 'dark', // Tema oscuro para el tooltip
	}
};

// Renderizar la gráfica
const chart = new ApexCharts(document.querySelector("#grafica_escolaridad"), options);
chart.render().then(() => {
	setTimeout(function () {
		chart.dataURI().then(uri => {
			//console.log(uri);
			escolaridadchart = uri;
		});
	}, 4000);
});

// Crear un nuevo objeto root para el gráfico de régimen
const regimenData = [
	{ categoria: "Planta", valor: 53, color: "#98c11d" },
	{ categoria: "Sindicalizado", valor: 12, color: "#2c6e49" },
	{ categoria: "NA", valor: 6, color: "#154b75" },
	{ categoria: "Otros", valor: 5, color: "#0098c7" }
];

// Extraer valores y colores
const valoresRegimen = regimenData.map(item => item.valor);
const coloresRegimen = regimenData.map(item => item.color);
const categoriasRegimen = regimenData.map(item => item.categoria);

// Configuración del gráfico donut
const optionsRegimen = {
	series: valoresRegimen, // Valores para cada sección
	chart: {
		type: 'donut', // Tipo de gráfico donut
		height: 240,
	},
	colors: coloresRegimen, // Colores personalizados
	labels: categoriasRegimen, // Etiquetas para cada sección
	dataLabels: {
		enabled: true,
		style: {
			fontSize: '15px',
			fontWeight: 'bold',
		},
		formatter: function (val, opts) {
			return opts.w.config.series[opts.seriesIndex]; // Muestra el valor original
		}
	},
	plotOptions: {
		pie: {
			donut: {
				size: '40%', // Reducir el tamaño del hueco para líneas más gruesas
			}
		}
	},
	legend: {
		show: false, // Mostrar la leyenda
		position: 'right',
		fontSize: '10px',
		itemMargin: { // Reducir el espacio entre los elementos de la leyenda
			horizontal: 2, // Espacio horizontal entre elementos
			vertical: 4 // Espacio vertical entre elementos
		}
	},
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 170 // Ajustar el tamaño en pantallas pequeñas
			},
			legend: {
				position: 'bottom' // Colocar la leyenda abajo en pantallas pequeñas
			}
		}
	}]
};

// Crear y renderizar el gráfico
var chartRegimen = new ApexCharts(document.querySelector("#grafica_regimen"), optionsRegimen);
chartRegimen.render().then(() => {
	setTimeout(function () {
		chartRegimen.dataURI().then(uri => {
			//console.log(uri);
			regimenchart = uri;
		});
	}, 4000);
});

const experienciaData = [
	{ rango: "Menos de 6 meses", valor: 10, color: "#98c11d" },
	{ rango: "6 meses a 1 año", valor: 20, color: "#2c6e49" },
	{ rango: "1 a 4 años", valor: 12, color: "#154b75" },
	{ rango: "5 a 9 años", valor: 8, color: "#0098c7" },
	{ rango: "10 a 14 años", valor: 12, color: "#9A33B2" },
	{ rango: "15 a 19 años", valor: 5, color: "#6F4F98" },
	{ rango: "20 a 24 años", valor: 4, color: "#4C7F97" },
	{ rango: "25 años o más", valor: 4, color: "#21D19F" }
];

// Extraer valores y colores
const valoresExperiencia = experienciaData.map(item => item.valor);
const coloresExperiencia = experienciaData.map(item => item.color);
const rangosExperiencia = experienciaData.map(item => item.rango);

// Configuración del gráfico de tipo pie
const optionsExperiencia = {
	series: valoresExperiencia, // Valores para cada sección
	chart: {
		type: 'pie',
		height: 240, // Ancho del gráfico
		toolbar: {
			show: false
		},
		animations: {
			enabled: false // Deshabilitar animaciones para la exportación
		},
	},
	labels: rangosExperiencia, // Etiquetas para cada sección
	colors: coloresExperiencia, // Colores personalizados
	dataLabels: {
		enabled: true,
		style: {
			fontSize: '16px', // Tamaño de texto para los números
			fontWeight: 'bold', // Peso de la fuente
			colors: ['#FFFFFF'] // Color del texto (puedes personalizarlo)
		},
		formatter: function (val, opts) {
			return opts.w.config.series[opts.seriesIndex]; // Muestra el valor original
		}
	},
	legend: {
		show: false, // Mostrar la leyenda
		position: 'left',
		fontSize: '15px',
		itemMargin: { // Reducir el espacio entre los elementos de la leyenda
			horizontal: 2, // Espacio horizontal entre elementos
			vertical: 2 // Espacio vertical entre elementos
		}
	},
	responsive: [{
		breakpoint: 480,
		options: {
			chart: {
				width: 120 // Ajustar el tamaño en pantallas pequeñas
			},
			legend: {
				position: 'bottom',
				fontSize: '15px' // Colocar la leyenda abajo en pantallas pequeñas
			}
		}
	}]
};

// Crear y renderizar el gráfico
var chartExperiencia = new ApexCharts(document.querySelector("#grafica_experiencia"), optionsExperiencia);
chartExperiencia.render().then(() => {
	setTimeout(function () {
		chartExperiencia.dataURI().then(uri => {
			//console.log(uri);
			experienciachart = uri;
		});
	}, 4000);
});

