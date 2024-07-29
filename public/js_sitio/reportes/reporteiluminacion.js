//=================================================
// MENU INDICE


$(".stickyside").stick_in_parent({
	offset_top: 150 // Margin Top del menu
});


$('.stickyside a').click(function()
{
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
scrollItems = menuItems.map(function()
{
	var item = $($(this).attr("href"));
	if (item.length) {
		return item;
	}
});


// Menu al mover el scroll
$(window).scroll(function()
{
	// Get container scroll position
	// var fromTop = $(this).scrollTop() + topMenuHeight - 100;
	var fromTop = $(this).scrollTop() + topMenuHeight;

	// Get id of current scroll item
	var cur = scrollItems.map(function() {
		if ($(this).offset().top < fromTop)
		return this;
	});

	// Get the id of the current element
	cur = cur[cur.length - 1];
	var id = cur && cur.length ? cur[0].id : "";

	if (lastId !== id)
	{
		lastId = id;
		// Set/remove active class
		menuItems.removeClass("active").filter("[href='#" + id + "']").addClass("active");
	}
});


//=================================================
// LOAD PAGINA
var meses = ["VACIO", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
var reporteiluminacion_id = 0;
var agente_id = 4;
var agente_nombre = "Iluminación";
// var proyecto = <?php echo json_encode($proyecto); ?>; // Variable declarada en el Blade
// var recsensorial = <?php echo json_encode($recsensorial); ?>; // Variable declarada en el Blade

// Activar Tooltip
$('[data-toggle="tooltip"]').tooltip();


var tiempoespera = 10; // Segundos
function updateClock()
{
	if (parseInt(tiempoespera) > 0)
	{
		$('#segundos_espera').html((tiempoespera - 1));

		tiempoespera = (parseInt(tiempoespera) - 1);

		setTimeout(function()
		{
			updateClock();
		}, 1000);
	}
	else
	{
		$('#modal_cargando').modal('hide');
	}
}


$(document).ready(function()
{
	// Modal cargando
	$('#modal_cargando .modal-title').html('Cargando informe de '+agente_nombre); // Titulo modal
	$('#modal_cargando').modal(); // Abrir modal
	updateClock(); // Ejecutar tiempo de espera

	datosgenerales(); // Datos generales

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
    $('.mydatepicker').on('click', function()
    {
        $(this).datepicker('setDate', $(this).val());// Mostrar fecha del input y marcar en el calendario
    });
});
 

//=================================================
// DATOS GENERALES


var datosgenerales_ejecusiones = 0;
function datosgenerales()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteiluminaciondatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			reporteiluminacion_id = 0;
			if (parseInt(dato.reporteiluminacion_id) > 0)
			{
				reporteiluminacion_id = parseInt(dato.reporteiluminacion_id);
				// $('#reporteiluminacion_id').html(reporteiluminacion_id);
			}

			if (parseInt(dato.reporteiluminacion_concluido) == 1 || parseInt(dato.reporteiluminacion_cancelado) == 1)
			{
				botoninforme_estado(1);
			}
			else
			{
				botoninforme_estado(0);
			}


			if (parseInt(dato.recsensorial_tipocliente) == 1) //1= pemex, 0 =  cliente
			{
				$('#reporteiluminacion_catsubdireccion_activo').prop('checked', dato.reporteiluminacion_portada.reporteiluminacion_catsubdireccion_activo);
				$('#reporteiluminacion_catsubdireccion_id').val(dato.reporteiluminacion_portada.catsubdireccion_id);
				$('#reporteiluminacion_catgerencia_activo').prop('checked', dato.reporteiluminacion_portada.reporteiluminacion_catgerencia_activo);
				$('#reporteiluminacion_catgerencia_id').val(dato.reporteiluminacion_portada.catgerencia_id);
				$('#reporteiluminacion_catactivo_activo').prop('checked', dato.reporteiluminacion_portada.reporteiluminacion_catactivo_activo);
				$('#reporteiluminacion_catactivo_id').val(dato.reporteiluminacion_portada.catactivo_id);
				$('#reporteiluminacion_catregion_activo').prop('checked', dato.reporteiluminacion_portada.reporteiluminacion_catregion_activo);
				$('#reporteiluminacion_catregion_id').val(dato.reporteiluminacion_portada.catregion_id);
			}
			else
			{
				$('#reporteiluminacion_catsubdireccion_id').val('');
				$('#reporteiluminacion_catgerencia_id').val('');
				$('#reporteiluminacion_catactivo_id').val('');
				$('#reporteiluminacion_catregion_id').val('');

				$('#reporteiluminacion_catsubdireccion_activo').prop('checked', 0);
				$('#reporteiluminacion_catgerencia_activo').prop('checked', 0);
				$('#reporteiluminacion_catactivo_activo').prop('checked', 0);
				$('#reporteiluminacion_catregion_activo').prop('checked', 0);
			}
			
			
			$('#reporteiluminacion_instalacion').val(dato.reporteiluminacion_portada.reporteiluminacion_instalacion);

			$('#reporteiluminacion_fecha').val(dato.reporteiluminacion_portada.reporteiluminacion_fecha);

			// $('#reporte_fecha').val(dato.reporte_portada.reporte_fecha);


			$('#reporteiluminacion_mes').val(dato.reporteiluminacion_portada.reporteiluminacion_mes);

			$('.div_instalacion_nombre').html(dato.reporteiluminacion_portada.reporteiluminacion_instalacion);

			$('#reporteiluminacion_introduccion').html(dato.reporteiluminacion_introduccion);
			$('#reporteiluminacion_objetivogeneral').html(dato.reporteiluminacion_objetivogeneral);
			$('#reporteiluminacion_objetivoespecifico').html(dato.reporteiluminacion_objetivoespecifico);
			$('#reporteiluminacion_metodologia_4_1').html(dato.reporteiluminacion_metodologia_4_1);
			$('#reporteiluminacion_metodologia_4_2').html(dato.reporteiluminacion_metodologia_4_2);
			$('#reporteiluminacion_metodologia_4_2_1').html(dato.reporteiluminacion_metodologia_4_2_1);
			$('#reporteiluminacion_metodologia_4_2_2').html(dato.reporteiluminacion_metodologia_4_2_2);
			$('#reporteiluminacion_metodologia_4_2_3').html(dato.reporteiluminacion_metodologia_4_2_3);
			$('#reporteiluminacion_metodologia_4_2_4').html(dato.reporteiluminacion_metodologia_4_2_4);

			$('#reporteiluminacion_ubicacioninstalacion').html(dato.reporteiluminacion_ubicacioninstalacion.ubicacion);
			if (dato.reporteiluminacion_ubicacioninstalacion.ubicacionfoto)
			{
				var archivo = dato.reporteiluminacion_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteiluminacionmapaubicacion/'+reporteiluminacion_id+'/'+0;
				$('#reporteiluminacionubicacionfoto').dropify().data('dropify').destroy();
				$('#reporteiluminacionubicacionfoto').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteiluminacionubicacionfoto').dropify().data('dropify').init();
				$('#reporteiluminacionubicacionfoto').attr('required', false);
				
				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'block');
			}
			else
			{
				$('#reporteiluminacionubicacionfoto').val('');
				$('#reporteiluminacionubicacionfoto').attr('required', true);
				$('#reporteiluminacionubicacionfoto').dropify().data('dropify').resetPreview();
				$('#reporteiluminacionubicacionfoto').dropify().data('dropify').clearElement();

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'none');
			}

			$('#reporteiluminacion_procesoinstalacion').html(dato.reporteiluminacion_procesoinstalacion);
			$('#reporteiluminacion_actividadprincipal').html(dato.reporteiluminacion_actividadprincipal);
			$('#reporteiluminacion_criterioseleccion').html(dato.reporteiluminacion_criterioseleccion);
			$('#reporteiluminacion_conclusion').html(dato.reporteiluminacion_conclusion);

			if (dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable1)
			{
				// Responsable 1
				$('#reporteiluminacion_responsable1').val(dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable1);
				$('#reporteiluminacion_responsable1cargo').val(dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable1cargo);
				$('#reporteiluminacion_responsable1documento').val('');
				$('#reporteiluminacionresponsable1documento').val('');
				var archivo = dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteiluminacionresponsabledocumento/'+dato.reporteiluminacion_responsablesinforme.registro_id+'/'+1+'/'+0;
				$('#reporteiluminacionresponsable1documento').dropify().data('dropify').destroy();
				$('#reporteiluminacionresponsable1documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteiluminacionresponsable1documento').dropify().data('dropify').init();
				$('#reporteiluminacionresponsable1documento').attr('required', false);
				
				// Responsable 2
				$('#reporteiluminacion_responsable2').val(dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable2);
				$('#reporteiluminacion_responsable2cargo').val(dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable2cargo);
				$('#reporteiluminacion_responsable2documento').val('');
				$('#reporteiluminacionresponsable2documento').val('');
				var archivo = dato.reporteiluminacion_responsablesinforme.reporteiluminacion_responsable2documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteiluminacionresponsabledocumento/'+dato.reporteiluminacion_responsablesinforme.registro_id+'/'+2+'/'+0;
				$('#reporteiluminacionresponsable2documento').dropify().data('dropify').destroy();
				$('#reporteiluminacionresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteiluminacionresponsable2documento').dropify().data('dropify').init();
				$('#reporteiluminacionresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#reporteiluminacion_carpetadocumentoshistorial').val('reportes/proyecto/'+dato.reporteiluminacion_responsablesinforme.proyecto_id+'/'+agente_nombre+'/'+dato.reporteiluminacion_responsablesinforme.registro_id+'/responsables informe');
				if (parseInt(dato.reporteiluminacion_responsablesinforme.proyecto_id) == parseInt(proyecto.id))
				{
					$('#boton_descargarresponsabledoc1').css('display', 'block');
					$('#boton_descargarresponsabledoc2').css('display', 'block');
					$('#reporteiluminacion_carpetadocumentoshistorial').val('');
				}
			}
			else
			{
				$('#reporteiluminacion_responsable1').val('');
				$('#reporteiluminacion_responsable1cargo').val('');
				$('#reporteiluminacion_responsable1documento').val('');
				$('#reporteiluminacionresponsable1documento').val('');
				$('#reporteiluminacionresponsable1documento').attr('required', true);
				$('#reporteiluminacionresponsable1documento').dropify().data('dropify').resetPreview();
				$('#reporteiluminacionresponsable1documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				
				$('#reporteiluminacion_responsable2').val('');
				$('#reporteiluminacion_responsable2cargo').val('');
				$('#reporteiluminacion_responsable2documento').val('');
				$('#reporteiluminacionresponsable2documento').val('');
				$('#reporteiluminacionresponsable2documento').attr('required', true);
				$('#reporteiluminacionresponsable2documento').dropify().data('dropify').resetPreview();
				$('#reporteiluminacionresponsable2documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc2').css('display', 'none');

				// Carpeta ubicacion documentos historial
				$('#reporteiluminacion_carpetadocumentoshistorial').val('');
			}

			// ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reporteiluminacion_portada_guardado);
			menureporte_estado("menureporte_1", dato.reporteiluminacion_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reporteiluminacion_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reporteiluminacion_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4_1", dato.reporteiluminacion_metodologia_4_1_guardado);
			menureporte_estado("menureporte_4_2", dato.reporteiluminacion_metodologia_4_2_guardado);
			menureporte_estado("menureporte_4_2_1", dato.reporteiluminacion_metodologia_4_2_1_guardado);
			menureporte_estado("menureporte_4_2_2", dato.reporteiluminacion_metodologia_4_2_2_guardado);
			menureporte_estado("menureporte_4_2_3", dato.reporteiluminacion_metodologia_4_2_3_guardado);
			menureporte_estado("menureporte_4_2_4", dato.reporteiluminacion_metodologia_4_2_4_guardado);
			menureporte_estado("menureporte_5_1", dato.reporteiluminacion_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_5_2", dato.reporteiluminacion_procesoinstalacion_guardado);
			menureporte_estado("menureporte_6_2", dato.reporteiluminacion_criterioseleccion_guardado);
			menureporte_estado("menureporte_8", dato.reporteiluminacion_conclusion_guardado);
			menureporte_estado("menureporte_10", dato.reporteiluminacion_responsablesinforme_guardado);
			menureporte_estado("menureporte_11_1", dato.reporteiluminacion_memoriafotografica_guardado);
			$('#memoriafotografica_total').html(dato.reporteiluminacion_memoriafotografica_guardado);
			menureporte_estado("menureporte_11_4", 1); // Incertidumbre de la medición


			//Bloquear botones nuevas categorias y nuevas areas si este proyecto tiene POE general
			//-------------------------------------------------------------------------------------

			// if (categorias_poe == 1)
			// {
			// 	setTimeout(function()
			// 	{
			// 		$("#boton_reporte_nuevacategoria").attr('disabled', true);
			// 	}, 5000);
			// }


			if (areas_poe == 1)
			{
				setTimeout(function()
				{
					$("#boton_reporte_nuevacategoria").attr('disabled', true);
					$("#boton_reporte_nuevaarea").attr('disabled', true);
				}, 5000);
			}


			if (areas_poe == 1)
			{
				setTimeout(function()
				{
					$("#boton_reporte_nuevacategoria").attr('disabled', true);
					$("#boton_reporte_nuevaarea").attr('disabled', true);
				}, 10000);
			}
		},
		// beforeSend: function()
		// {
		// 	// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		// },
		error: function(dato)
		{
			if (datosgenerales_ejecusiones == 0)
			{
				datosgenerales();
				datosgenerales_ejecusiones += 1;
			}

			reporteiluminacion_id = 0;
			
			$('#reporteiluminacion_instalacion').val('Error al cargar los datos');
			$('#reporteiluminacion_fecha').val('Error al cargar los datos');
			$('#reporteiluminacion_mes').val('Error al cargar los datos');
			$('#reporteiluminacion_introduccion').html('Error al cargar los datos');
			$('#reporteiluminacion_objetivogeneral').html('Error al cargar los datos');
			$('#reporteiluminacion_objetivoespecifico').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_1').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_2').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_2_1').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_2_2').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_2_3').html('Error al cargar los datos');
			$('#reporteiluminacion_metodologia_4_2_4').html('Error al cargar los datos');
			$('#reporteiluminacion_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reporteiluminacion_procesoinstalacion').html('Error al cargar los datos');
			$('#reporteiluminacion_criterioseleccion').html('Error al cargar los datos');
			$('#reporteiluminacion_conclusion').html('Error al cargar los datos');

			return false;
		}
	});//Fin ajax
}


function menureporte_estado(menu_nombre, menu_estado)
{
	if (parseInt(menu_estado) > 0)
	{
		$('#'+menu_nombre).css('color', '#64bd44'); // Verde

		$('#'+menu_nombre).removeClass('fa fa-times');
		$('#'+menu_nombre).addClass('fa fa-check');
	}
	else
	{
		$('#'+menu_nombre).css('color', '#fc4b6c'); // Rojo

		$('#'+menu_nombre).removeClass('fa fa-check');
		$('#'+menu_nombre).addClass('fa fa-times');
	}
}


function instalacion_nombre(reporte_instalacion)
{
	$('.div_instalacion_nombre').html(reporte_instalacion);
}


function redimencionar_foto(campo_file, campo_filehidden, boton_guardar)
{
	// Bloquear boton guardar
	$('#'+boton_guardar).attr('disabled', true);
	$('#'+campo_filehidden).val('');

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
	reader.onload = function(e)
	{
	    //img.src = e.target.result;
		var img = new Image();
		img.src = this.result;
		
	    setTimeout(function()
	    {
			var canvas = document.createElement("canvas");
			//var canvas = $("<canvas>", {"id":"testing"})[0];
			//var ctx = canvas.getContext("2d");
			//ctx.drawImage(img, 0, 0);

			// Dimensiones reales
			var width = img.width;
			var height = img.height;

			// Dimensiones Nuevas
			if (parseInt(width) > 8000)
			{
				var MAX_WIDTH = 4000; //Ancho de la imagen
				var MAX_HEIGHT = 3000; //Alto de la imagen
			}
			else
			{
				var MAX_WIDTH = 1200; //Ancho de la imagen
				var MAX_HEIGHT = 900; //Alto de la imagen
			}

			// Dimensionar con respecto a la relacion de aspecto
			if (width > height)
			{
				if (width > MAX_WIDTH)
				{
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			}
			else
			{
				if (height > MAX_HEIGHT)
				{
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}

			canvas.width = width;
			canvas.height = height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, width, height);
			console.log("Nuevas dimensiones ",width, height);

			// Resultado
			var dataurl = canvas.toDataURL("image/jpeg");
			// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
			// ubicacionmapa = dataurl; //Guardar en una variable

			// Resultado
			$('#'+campo_filehidden).val(dataurl);

			// Desbloquear boton guardar
			$('#'+boton_guardar).attr('disabled', false);
		}, 100);
	}
}


//=================================================
// PORTADA


$("#botonguardar_reporte_portada").click(function()
{
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_portada').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 0,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catregion_id: $("#reporteiluminacion_catregion_id").val(),
						catsubdireccion_id: $("#reporteiluminacion_catsubdireccion_id").val(),
						catgerencia_id: $("#reporteiluminacion_catgerencia_id").val(),
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_0", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_portada').html('Guardando portada <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_portada').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_portada').html('Guardar portada <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_portada').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$("#botonguardar_reporte_introduccion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_introduccion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 1,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_introduccion').html('Guardando introducción <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_introduccion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_introduccion').html('Guardar introducción <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_introduccion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$(document).ready(function()
{

	obtenerdatos()
	
	setTimeout(function()
	{
		tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontabladefiniciones/"+proyecto_id+"/"+agente_nombre+"/"+reporteiluminacion_id;

		if (datatable_reportedefiniciones != null)
		{
			datatable_reportedefiniciones.clear().draw();
			datatable_reportedefiniciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reportedefiniciones = $('#tabla_reporte_definiciones').DataTable({
				"ajax": {
						"url": ruta,
						"type": "get",
						"cache": false,
						error: function (xhr, error, code)
						{
							// console.log(xhr); console.log(code);
							console.log('error en datatable_reportedefiniciones');
							if (numeroejecucion <= 1)
							{
								tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteiluminacion_id);
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
				"order": [[ 0, "DESC" ]],
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
		datatable_reportedefiniciones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteiluminacion_id);
    }
}


$("#boton_reporte_nuevadefinicion").click(function()
{
	$('#form_modal_definicion').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reportedefiniciones_id').val(0);

	// Titulo del modal
	$('#modal_reporte_definicion .modal-title').html('Nueva definición');

	// mostrar modal
	$('#modal_reporte_definicion').modal({backdrop:false});
});


$('#tabla_reporte_definiciones tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportedefiniciones.row(tr);

	if (parseInt(row.data().catactivo_id) >= 0)
	{
		$('#form_modal_definicion').each(function(){
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
		$('#modal_reporte_definicion').modal({backdrop:false});
	}
});


$('#tabla_reporte_definiciones tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportedefiniciones.row(tr);

	if (parseInt(row.data().catactivo_id) >= 0)
	{
		swal({
			title: "¡Confirme que desea eliminar!",
			text: "La definición: "+row.data().concepto,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Aceptar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				swal({
					title: "¡Por seguridad confirme nuevamente que desea eliminar!",
					text: "La definición: "+row.data().concepto,
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Eliminar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm)
				{
					if (isConfirm)
					{
						// cerrar msj confirmacion
						swal.close();

						$.ajax({
							type: "GET",
							dataType: "json",
							url: "/reporteiluminaciondefinicioneliminar/"+row.data().id,
							data:{},
							cache: false,
							success:function(dato)
							{
								// Actualizar tabla
								tabla_reporte_definiciones(proyecto.id, agente_nombre);

								// mensaje
								swal({
									title: "Correcto",
									text: ""+dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});
							},
							beforeSend: function()
							{
								// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
							},
							error: function(dato)
							{
								// mensaje
								swal({
									title: "Error",
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
						});//Fin ajax
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
							timer: 500,
							showConfirmButton: false
						});
					}
				});
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$("#botonguardar_modal_definicion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Definición: "+$('#reportedefiniciones_concepto').val(),
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_modal_definicion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 2,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteiluminacion_id = dato.reporteiluminacion_id;

						// Actualizar tabla
						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_modal_definicion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_definicion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_definicion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_definicion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$("#botonguardar_reporte_objetivogeneral").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_objetivogeneral').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 3,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_3_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_objetivogeneral').html('Guardando objetivo general <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_objetivogeneral').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_objetivogeneral').html('Guardar objetivo general <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_objetivogeneral').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$("#botonguardar_reporte_objetivoespecifico").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_objetivoespecifico').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 4,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_3_2", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_objetivoespecifico').html('Guardando objetivos específicos <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_objetivoespecifico').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_objetivoespecifico').html('Guardar objetivos específicos <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_objetivoespecifico').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$("#botonguardar_reporte_metodologia_4_1").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_1').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 5,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_1').html('Guardando metodología punto 4.1 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_1').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_1').html('Guardar metodología punto 4.1 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_1').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$("#botonguardar_reporte_metodologia_4_2").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_2').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 6,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_2", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_2').html('Guardando metodología punto 4.2 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_2').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2').html('Guardar metodología punto 4.2 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//=================================================
// METODOLOGÍA PUNTO 4.2.1


$("#botonguardar_reporte_metodologia_4_2_1").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.2.1",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_2_1').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 7,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_2_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_1').html('Guardar metodología punto 4.2.1 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_1').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_2_1').html('Guardando metodología punto 4.2.1 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_2_1').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_1').html('Guardar metodología punto 4.2.1 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_1').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//=================================================
// METODOLOGÍA PUNTO 4.2.2


$("#botonguardar_reporte_metodologia_4_2_2").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.2.2",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_2_2').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 8,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_2_2", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_2').html('Guardar metodología punto 4.2.2 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_2').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_2_2').html('Guardando metodología punto 4.2.2 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_2_2').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_2').html('Guardar metodología punto 4.2.2 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_2').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//=================================================
// METODOLOGÍA PUNTO 4.2.3


$("#botonguardar_reporte_metodologia_4_2_3").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.2.3",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_2_3').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 9,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_2_3", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_3').html('Guardar metodología punto 4.2.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_3').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_2_3').html('Guardando metodología punto 4.2.3 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_2_3').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_3').html('Guardar metodología punto 4.2.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_3').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//=================================================
// METODOLOGÍA PUNTO 4.2.4


$("#botonguardar_reporte_metodologia_4_2_4").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.2.4",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_metodologia_4_2_4').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 10,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_4_2_4", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_4').html('Guardar metodología punto 4.2.4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_4').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_2_4').html('Guardando metodología punto 4.2.4 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_2_4').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_2_4').html('Guardar metodología punto 4.2.4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_2_4').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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

$(document).ready(function()
{
	$('#reporteiluminacionubicacionfoto').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove':  'Quitar',
			'error':   'Ooops, ha ocurrido un error.'
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


function redimencionar_mapaubicacion()
{
	// Bloquear boton
	$('#botonguardar_reporte_ubicacion').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('reporteiluminacionubicacionfoto').files;
	var file = filesToUpload[0];

	// Create an image
	var img = document.createElement("img");

	// Create a file reader
	var reader = new FileReader();

	// Load files into file reader
	reader.readAsDataURL(file);

	// Set the image once loaded into file reader
	reader.onload = function(e)
	{
	    //img.src = e.target.result;
		var img = new Image();
		img.src = this.result;
		
	    setTimeout(function()
	    {
			var canvas = document.createElement("canvas");
			//var canvas = $("<canvas>", {"id":"testing"})[0];
			//var ctx = canvas.getContext("2d");
			//ctx.drawImage(img, 0, 0);

			// Dimensiones reales
			var width = img.width;
			var height = img.height;

			// Dimensiones Nuevas
			if (parseInt(width) > 8000)
			{
				var MAX_WIDTH = 4000; //Ancho de la imagen
				var MAX_HEIGHT = 3000; //Alto de la imagen
			}
			else
			{
				var MAX_WIDTH = 1200; //Ancho de la imagen
				var MAX_HEIGHT = 900; //Alto de la imagen
			}

			// Dimensionar con respecto a la relacion de aspecto
			if (width > height)
			{
				if (width > MAX_WIDTH)
				{
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				}
			}
			else
			{
				if (height > MAX_HEIGHT)
				{
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				}
			}

			canvas.width = width;
			canvas.height = height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, width, height);
			console.log("Nuevas dimensiones ",width, height);

			// Resultado
			var dataurl = canvas.toDataURL("image/jpeg");
			// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
			ubicacionmapa = dataurl; //Guardar en una variable

			// Desbloquear boton
			$('#botonguardar_reporte_ubicacion').attr('disabled', false);
		}, 100);
	}
}


$("#botonguardar_reporte_ubicacion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_ubicacion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 11,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						ubicacionmapa: ubicacionmapa
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_5_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						$('#reporteiluminacionubicacionfoto').val('');
						$('#reporteiluminacionubicacionfoto').attr('required', false);
						$('#boton_descargarmapaubicacion').css('display', 'block');

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_ubicacion').html('Guardando ubicación <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_ubicacion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_ubicacion').html('Guardar ubicación <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_ubicacion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$("#boton_descargarmapaubicacion").click(function()
{
	window.open('/reporteiluminacionmapaubicacion/'+reporteiluminacion_id+'/'+1);
});


//=================================================
// PROCESO INSTALACIÓN


$("#botonguardar_reporte_procesoinstalacion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_procesoinstalacion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 12,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_5_2", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_procesoinstalacion').html('Guardando proceso instalación <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_procesoinstalacion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_procesoinstalacion').html('Guardar proceso instalación <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_procesoinstalacion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
	}, 2500);
});


var datatable_reportecategorias = null;
function tabla_reporte_categorias(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminacioncategorias/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (datatable_reportecategorias != null)
		{
			datatable_reportecategorias.clear().draw();
			datatable_reportecategorias.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reportecategorias = $('#tabla_reporte_categoria').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(areas_poe) == 1)
						{
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						{
							menureporte_estado("menureporte_5_3", 1);
						}
						else
						{
							menureporte_estado("menureporte_5_3", 0);
						}


						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reportecategorias');
						if (numeroejecucion <= 1)
						{
							tabla_reporte_categorias(proyecto_id, reporteiluminacion_id);
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
						"data": "reportecategoria_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reportecategoria_total",
						"defaultContent": "-"
					},
					{
						"data": "categoria_horas",
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
				"order": [[ 0, "DESC" ]],
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
		datatable_reportecategorias.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_categorias(proyecto_id, reporteiluminacion_id);
    }
}


$("#boton_reporte_nuevacategoria").click(function()
{
	$('#form_modal_categoria').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#reportecategoria_id').val(0);


	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html('Nueva categoría');


	// mostrar modal
	$('#modal_reporte_categoria').modal({backdrop:false});
});


$('#tabla_reporte_categoria tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);

	$('#form_modal_categoria').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reportecategoria_id').val(row.data().id);

	// Llenar campos
	$('#reportecategoria_nombre').val(row.data().reportecategoria_nombre);
	$('#reportecategoria_total').val(row.data().reportecategoria_total);
	$('#reportecategoria_horas').val(row.data().reportecategoria_horas);


	if (areas_poe == 1)
	{
		$('#reportecategoria_nombre').attr('required', false);
		$('#reportecategoria_nombre').attr('disabled', true);

		$('#reportecategoria_total').attr('required', false);
		$('#reportecategoria_total').attr('disabled', true);
	}
	else
	{
		$('#reportecategoria_nombre').attr('disabled', false);
		$('#reportecategoria_nombre').attr('required', true);

		$('#reportecategoria_total').attr('disabled', false);
		$('#reportecategoria_total').attr('required', true);
	}


	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html(row.data().reportecategoria_nombre);

	// mostrar modal
	$('#modal_reporte_categoria').modal({backdrop:false});
});


$('#tabla_reporte_categoria tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "La categoría: "+row.data().reporteiluminacioncategoria_nombre,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm)
	{
		if (isConfirm)
		{
			swal({
				title: "¡Por seguridad confirme nuevamente que desea eliminar!",
				text: "La categoría: "+row.data().reporteiluminacioncategoria_nombre,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/reporteiluminacioncategoriaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
							tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
							reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);

							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						beforeSend: function()
						{
							// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
						},
						error: function(dato)
						{
							// mensaje
							swal({
								title: "Error",
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
					});//Fin ajax
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
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
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
});


$("#botonguardar_modal_categoria").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Categoría: "+$("#reportecategoria_nombre").val(),
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_modal_categoria').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 13,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						categorias_poe: categorias_poe
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						tabla_reporte_revisiones(proyecto.id);

						// Actualizar tabla
						tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
						tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
						tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
						tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
						reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_modal_categoria').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_categoria').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_categoria').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_categoria').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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

// var areas_poe = 0;
var selectareas = '';


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
	}, 3000);
});


var datatable_reporteareas = null;
function tabla_reporte_areas(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminacionareas/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (datatable_reporteareas != null)
		{
			datatable_reporteareas.clear().draw();
			datatable_reporteareas.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporteareas = $('#tabla_reporte_area').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						{
							menureporte_estado("menureporte_5_4", 1);
							menureporte_estado("menureporte_5_5", 1);
							menureporte_estado("menureporte_6_1", 1);
							menureporte_estado("menureporte_6_2_1", 1);
							menureporte_estado("menureporte_6_2_2", 1);
						}


						if (parseInt(areas_poe) == 1)
						{
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						// Tablas
						tabla_reporte_5_4(json.tabla_5_4);
						tabla_reporte_5_5(json.tabla_5_5);
						tabla_reporte_6_1(json.tabla_6_1);
						tabla_reporte_6_2_1(json.tabla_6_2_1);
						tabla_reporte_6_2_2(json.tabla_6_2_2);


						$('#total_ic').html(json.total_ic);

						if (parseInt(json.total_ic) > 0)
						{
							$("#indicearea_descripcion_2").css('display', 'none');
							$("#indicearea_descripcion_1").css('display', 'block');
						}
						else
						{
							$("#indicearea_descripcion_1").css('display', 'none');
							$("#indicearea_descripcion_2").css('display', 'block');
						}


						$('#total_pt').html(json.total_pt);

						if (parseInt(json.total_pt) > 0)
						{
							$("#puestotrabajo_descripcion_2").css('display', 'none');
							$("#puestotrabajo_descripcion_1").css('display', 'block');
						}
						else
						{
							$("#puestotrabajo_descripcion_1").css('display', 'none');
							$("#puestotrabajo_descripcion_2").css('display', 'block');
						}


						// Actualizar select areas
						selectareas = json.selectareasoption;


						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reporteareas');
						if (numeroejecucion <= 1)
						{
							tabla_reporte_areas(proyecto_id, reporteiluminacion_id)
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
						"data": "reportearea_instalacion",
						"defaultContent": "-"
					},
					{
						"data": "reportearea_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reportecategoria_nombre_texto",
						"defaultContent": "-",
					},
					{
						"data": "reporteareacategoria_total",
						"defaultContent": "-"
					},
					{
						"data": "reportearea_puntos_ic",
						"defaultContent": "-"
					},
					{
						"data": "reportearea_puntos_pt",
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
				"rowsGroup": [1, 0, 2, 5, 6, 7, 8], //agrupar filas
				"order": [[ 0, "DESC" ]],
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
		datatable_reporteareas.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_areas(proyecto_id, reporteiluminacion_id);
    }
}


$("#boton_reporte_nuevaarea").click(function()
{
	$('#form_reporte_area').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reportearea_id').val(0);


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html('Nueva área');


	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});


	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteiluminacionareascategorias/"+proyecto.id+"/"+reporteiluminacion_id+"/"+0+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			// $('#tabla_areacategorias tbody').html(dato.areacategorias);
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function()
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax
});


var datatable_areacategoria = null;
function tabla_areacategorias(tbody)
{
	if (datatable_areacategoria != null)
	{
		datatable_areacategoria.destroy();
	}

	$('#tabla_areacategorias tbody').html(tbody);

	datatable_areacategoria = $('#tabla_areacategorias').DataTable({
		"scrollY": "185px",
        "scrollCollapse": true,
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		// "rowsGroup": [0], //agrupar filas
		"order": [[ 0, "DESC" ]],
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


function activa_areacategoria(checkbox, num_registro)
{
	if (checkbox.checked)
	{
		$(".areacategoria_"+num_registro).attr('readonly', false);
		$(".areacategoria_"+num_registro).attr('required', true);
	}
	else
	{
		$(".areacategoria_"+num_registro).val('');
		$(".areacategoria_"+num_registro).attr('required', false);
		$(".areacategoria_"+num_registro).attr('readonly', true);
	}
}


$('#tabla_reporte_area tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporteareas.row(tr);


	$('#form_reporte_area').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#reportearea_id').val(row.data().id);


	// Llenar campos
	$('#reportearea_instalacion').val(row.data().reportearea_instalacion);
	$('#reportearea_nombre').val(row.data().reportearea_nombre);
	$('#reportearea_orden').val(row.data().reportearea_orden);
	$('#reportearea_puntos_ic').val(row.data().reportearea_puntos_ic);
	$('#reportearea_puntos_pt').val(row.data().reportearea_puntos_pt);
	$('#reportearea_sistemailuminacion').val(row.data().reportearea_sistemailuminacion);
	$('#reportearea_luznatural').val(row.data().reportearea_luznatural);
	$('#reportearea_iluminacionlocalizada').val(row.data().reportearea_iluminacionlocalizada);
	$('#reporteiluminacionarea_porcientooperacion').val(row.data().reporteiluminacionarea_porcientooperacion);
	$('#reportearea_colorsuperficie').val(row.data().reportearea_colorsuperficie);
	$('#reportearea_tiposuperficie').val(row.data().reportearea_tiposuperficie);
	$('#reportearea_largo').val(row.data().reportearea_largo);
	$('#reportearea_ancho').val(row.data().reportearea_ancho);
	$('#reportearea_alto').val(row.data().reportearea_alto);

	$('#reportearea_criterio').val(row.data().reportearea_criterio);
	$('#reportearea_colortecho').val(row.data().reportearea_colortecho);
	$('#reportearea_paredes').val(row.data().reportearea_paredes);
	$('#reportearea_colorpiso').val(row.data().reportearea_colorpiso);
	$('#reportearea_superficietecho').val(row.data().reportearea_superficietecho);
	$('#reportearea_superficieparedes').val(row.data().reportearea_superficieparedes);
	$('#reportearea_superficiepiso').val(row.data().reportearea_superficiepiso);
	$('#reportearea_potenciaslamparas').val(row.data().reportearea_potenciaslamparas);
	$('#reportearea_numlamparas').val(row.data().reportearea_numlamparas);
	$('#reportearea_alturalamparas').val(row.data().reportearea_alturalamparas);
	$('#reportearea_programamantenimiento').val(row.data().reportearea_programamantenimiento);
	$('#reportearea_tipoiluminacion').val(row.data().reportearea_tipoiluminacion);
	$('#reportearea_descripcionilimunacion').val(row.data().reportearea_descripcionilimunacion);




	if (areas_poe == 1)
	{
		$('#reportearea_instalacion').attr('required', false);
		$('#reportearea_instalacion').attr('disabled', true);

		$('#reportearea_nombre').attr('required', false);
		$('#reportearea_nombre').attr('disabled', true);

		$('#reportearea_orden').attr('required', false);
		$('#reportearea_orden').attr('disabled', true);

		// $('#reporteiluminacionarea_porcientooperacion').attr('required', false);
		// $('#reporteiluminacionarea_porcientooperacion').attr('disabled', true);
	}
	else
	{
		$('#reportearea_instalacion').attr('disabled', false);
		$('#reportearea_instalacion').attr('required', true);

		$('#reportearea_nombre').attr('disabled', false);
		$('#reportearea_nombre').attr('required', true);

		$('#reportearea_orden').attr('disabled', false);
		$('#reportearea_orden').attr('required', true);

		// $('#reporteiluminacionarea_porcientooperacion').attr('disabled', false);
		// $('#reporteiluminacionarea_porcientooperacion').attr('required', true);
	}


	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteiluminacionareascategorias/"+proyecto.id+"/"+reporteiluminacion_id+"/"+row.data().id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			// $('#tabla_areacategorias tbody').html(dato.areacategorias);
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function()
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="7" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html(row.data().reportearea_nombre);


	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});
});


$('#tabla_reporte_area tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporteareas.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El área: "+row.data().reportarea_nombre,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm)
	{
		if (isConfirm)
		{
			swal({
				title: "¡Por seguridad confirme nuevamente que desea eliminar!",
				text: "El área: "+row.data().reportarea_nombre,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/reporteiluminacionareaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
							reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);


							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						beforeSend: function()
						{
							// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
						},
						error: function(dato)
						{
							// mensaje
							swal({
								title: "Error",
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
					});//Fin ajax
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
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
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
});


$("#botonguardar_modal_area").click(function()
{
	// borrar campo filtro del DATATABLE
	// datatable_areacategoria.search($(this).val()).draw();
	datatable_areacategoria.search("").draw();


	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Área: "+$("#reportearea_nombre").val(),
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_area').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 14,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						areas_poe: areas_poe,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						tabla_reporte_revisiones(proyecto.id);

						// Actualizar tabla
						tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
						tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
						tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
						reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_modal_area').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_area').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_area').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_area').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


var datatable_reporte_5_4 = null;
function tabla_reporte_5_4(tbody)
{
	if (datatable_reporte_5_4 != null)
	{
		datatable_reporte_5_4.destroy();
	}

	$('#tabla_reporte_5_4 tbody').html(tbody);

	datatable_reporte_5_4 = $('#tabla_reporte_5_4').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1, 0, 2, 5, 6], //agrupar filas
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


var datatable_reporte_5_5 = null;
function tabla_reporte_5_5(tbody)
{
	if (datatable_reporte_5_5 != null)
	{
		datatable_reporte_5_5.destroy();
	}

	$('#tabla_reporte_5_5 tbody').html(tbody);

	datatable_reporte_5_5 = $('#tabla_reporte_5_5').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1], //agrupar filas
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


var datatable_reporte_6_1 = null;
function tabla_reporte_6_1(tbody)
{
	if (datatable_reporte_6_1 != null)
	{
		datatable_reporte_6_1.destroy();
	}

	$('#tabla_reporte_6_1 tbody').html(tbody);

	datatable_reporte_6_1 = $('#tabla_reporte_6_1').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1, 3], //agrupar filas
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


var datatable_reporte_6_2_1 = null;
function tabla_reporte_6_2_1(tbody)
{
	if (datatable_reporte_6_2_1 != null)
	{
		datatable_reporte_6_2_1.destroy();
	}

	$('#tabla_reporte_6_2_1 tbody').html(tbody);

	datatable_reporte_6_2_1 = $('#tabla_reporte_6_2_1').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1, 2, 0, 5, 6, 7], //agrupar filas
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


var datatable_reporte_6_2_2 = null;
function tabla_reporte_6_2_2(tbody)
{
	if (datatable_reporte_6_2_2 != null)
	{
		datatable_reporte_6_2_2.destroy();
	}

	$('#tabla_reporte_6_2_2 tbody').html(tbody);

	datatable_reporte_6_2_2 = $('#tabla_reporte_6_2_2').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1, 2, 0], //agrupar filas
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


//=================================================
// CRITERIO DE SELECCION


$("#botonguardar_reporte_criterioseleccion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Método empleado y criterio de selección",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_criterioseleccion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 15,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_6_2", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_criterioseleccion').html('Guardar criterio de selección <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_criterioseleccion').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_criterioseleccion').html('Guardando criterio de selección <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_criterioseleccion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_criterioseleccion').html('Guardar criterio de selección <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_criterioseleccion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


//=================================================
// PUNTOS DE EVALUACION ILUMINACION


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
		tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
		tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
		tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
		reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);
	}, 4000);
});


var datatable_iluminacionpuntos = null;
function tabla_reporte_iluminacionpuntos(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontablapuntos/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (datatable_iluminacionpuntos != null)
		{
			datatable_iluminacionpuntos.clear().draw();
			datatable_iluminacionpuntos.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_iluminacionpuntos = $('#tabla_reporte_iluminacionpuntos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.total) > 0)
						{
							menureporte_estado("menureporte_7_1", 1);
							menureporte_estado("menureporte_7_2", 1);
							menureporte_estado("menureporte_7_3", 1);
						}
						else
						{
							menureporte_estado("menureporte_7_1", 0);
							menureporte_estado("menureporte_7_2", 0);
							menureporte_estado("menureporte_7_3", 0);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_iluminacionpuntos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_iluminacionpuntos(proyecto_id, reporteiluminacion_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteiluminacionpuntos_nopunto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionarea_instalacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionarea_nombre",
						defaultContent: "-",
						orderable: false,
					}
					,
					{
						data: "reporteiluminacioncategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_concepto",
						defaultContent: "-",
						orderable: false,
					},
					{
						className: 'editar',
						data: "boton_editar",
						defaultContent: "-",
						orderable: false,
					},
					{
						// className: 'eliminar',
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [1, 2, 0], //agrupar filas
				order: [[ 0, "ASC" ]],
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
				}
			});
		}

		// Tooltip en DataTable
		datatable_iluminacionpuntos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_iluminacionpuntos(proyecto_id, reporteiluminacion_id);
    }
}


$("#boton_reporte_nuevoiluminacionpunto").click(function()
{
	$('#form_reporte_iluminacionpunto').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteiluminacionpunto_id').val(0);

	// Llenar campo area select
	$('#reporteiluminacionpuntos_area_id').html('<option value=""></option>'+selectareas)

	// Llenar campo categoria select
	$('#reporteiluminacionpuntos_categoria_id').html('<option value=""></option>');

	// Titulo del modal
	$('#modal_reporte_iluminacionpunto .modal-title').html('Nuevo punto de iluminación');

	// mostrar modal
	$('#modal_reporte_iluminacionpunto').modal({backdrop:false});
});


function mostrar_categoriasarea(area_id, categoria_id)
{
	if (parseInt(area_id) > 0)
	{
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/reporteiluminacionareascategoriasconsultar/"+area_id+"/"+categoria_id+"/"+reporteiluminacion_id+"/"+areas_poe,
			data:{},
			cache: false,
			success:function(dato)
			{
				$('#reporteiluminacionpuntos_categoria_id').attr('disabled', false);
				$('#reporteiluminacionpuntos_categoria_id').html(dato.categoriasoption);
			},
			beforeSend: function()
			{
				$('#reporteiluminacionpuntos_categoria_id').html('<option value=""></option>');
				$('#reporteiluminacionpuntos_categoria_id').attr('disabled', true);
			},
			error: function(dato)
			{
				$('#reporteiluminacionpuntos_categoria_id').attr('disabled', false);
				$('#reporteiluminacionpuntos_categoria_id').html('<option value=""></option>');
				return false;
			}
		});//Fin ajax
	}
	else
	{
		$('#reporteiluminacionpuntos_categoria_id').html('<option value=""></option>');
	}
}


function calcula_resultado_iluminacion(campo_limite, campos_resultados, msj_noaplica)
{
	var nocumple = 0;
	var campovalores = 0;

	if ($('.'+campo_limite).val() != "" && parseInt($('.'+campo_limite).val()) > 0)
	{
		$('.'+campos_resultados).each(function()
		{
			if (this.value == "" || parseInt(this.value) < 0 || (parseInt(this.value) > 0 && parseInt(this.value) < parseInt($('.'+campo_limite).val())))
			{
				nocumple += 1;
			}

			if (this.value != "" && parseInt(this.value) > 0)
			{
				campovalores += parseInt(this.value);
			}
		});

		if (nocumple == 0 && campovalores > 0)
		{
			$('#'+campos_resultados).html('<b class="text-success"><i class="fa fa-check"></i> Dentro de norma</b>');
		}
		else
		{
			$('#'+campos_resultados).html('<b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b>');
		}
	}
	else
	{
		$('#'+campos_resultados).html('<b class="text-secondary">'+msj_noaplica+'</b>');
	}
}


function calcula_resultado_reflexion(campo_limite, campos_resultados, msj_noaplica)
{
	var nocumple = 0;
	var campovalores = 0;

	$('.'+campos_resultados).each(function()
	{
		if (this.value == "" || parseInt(this.value) < 0 || (parseInt(this.value) > 0 && parseInt(this.value) > parseInt($('.'+campo_limite).val())))
		{
			nocumple += 1;
		}

		if (this.value != "" && parseInt(this.value) > 0)
		{
			campovalores += parseInt(this.value);
		}
	});

	if (nocumple == 0)
	{
		if (campovalores > 0)
		{
			$('#'+campos_resultados).html('<b class="text-success"><i class="fa fa-check"></i> Dentro de norma</b>');
		}
		else
		{
			$('#'+campos_resultados).html('<b class="text-secondary">'+msj_noaplica+'</b>');
		}
	}
	else
	{
		$('#'+campos_resultados).html('<b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b>');
	}
}


$('#tabla_reporte_iluminacionpuntos tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_iluminacionpuntos.row(tr);

	$('#form_reporte_iluminacionpunto').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteiluminacionpunto_id').val(row.data().id);

	// Llenar campos
    $('#reporteiluminacionpuntos_nopunto').val(row.data().reporteiluminacionpuntos_nopunto);
    $('#reporteiluminacionpuntos_area_id').html('<option value=""></option>'+selectareas)
    $('#reporteiluminacionpuntos_area_id').val(row.data().reporteiluminacionpuntos_area_id);
    // $('#reporteiluminacionpuntos_categoria_id').val(row.data().reporteiluminacionpuntos_categoria_id);
    mostrar_categoriasarea(row.data().reporteiluminacionpuntos_area_id, row.data().reporteiluminacionpuntos_categoria_id);
    $('#reporteiluminacionpuntos_nopoe').val(row.data().reporteiluminacionpuntos_nopoe);
    $('#reporteiluminacionpuntos_nombre').val(row.data().reporteiluminacionpuntos_nombre);
    $('#reporteiluminacionpuntos_ficha').val(row.data().reporteiluminacionpuntos_ficha);
    $('#reporteiluminacionpuntos_concepto').val(row.data().reporteiluminacionpuntos_concepto);
    $('#reporteiluminacionpuntos_fechaeval').val(row.data().reporteiluminacionpuntos_fechaeval);
    $('#reporteiluminacionpuntos_horario1').val(row.data().reporteiluminacionpuntos_horario1);
    $('#reporteiluminacionpuntos_horario2').val(row.data().reporteiluminacionpuntos_horario2);
    $('#reporteiluminacionpuntos_horario3').val(row.data().reporteiluminacionpuntos_horario3);
    $('#reporteiluminacionpuntos_lux').val(row.data().reporteiluminacionpuntos_lux);
    $('#reporteiluminacionpuntos_luxmed1').val(row.data().reporteiluminacionpuntos_luxmed1);
    $('#reporteiluminacionpuntos_luxmed2').val(row.data().reporteiluminacionpuntos_luxmed2);
    $('#reporteiluminacionpuntos_luxmed3').val(row.data().reporteiluminacionpuntos_luxmed3);
    $('#reporteiluminacionpuntos_luxmed1menor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed1menor));
    $('#reporteiluminacionpuntos_luxmed2menor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed2menor));
    $('#reporteiluminacionpuntos_luxmed3menor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed3menor));
    
    $('#reporteiluminacionpuntos_luxmed1mayor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed1mayor));
    $('#reporteiluminacionpuntos_luxmed2mayor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed2mayor));
    $('#reporteiluminacionpuntos_luxmed3mayor').prop('checked', parseInt(row.data().reporteiluminacionpuntos_luxmed3mayor));
    $('#reporteiluminacionpuntos_frp').val(row.data().reporteiluminacionpuntos_frp);
    $('#reporteiluminacionpuntos_frpmed1').val(row.data().reporteiluminacionpuntos_frpmed1);
    $('#reporteiluminacionpuntos_frpmed2').val(row.data().reporteiluminacionpuntos_frpmed2);
    $('#reporteiluminacionpuntos_frpmed3').val(row.data().reporteiluminacionpuntos_frpmed3);
    $('#reporteiluminacionpuntos_frpt').val(row.data().reporteiluminacionpuntos_frpt);
    $('#reporteiluminacionpuntos_frptmed1').val(row.data().reporteiluminacionpuntos_frptmed1);
    $('#reporteiluminacionpuntos_frptmed2').val(row.data().reporteiluminacionpuntos_frptmed2);
    $('#reporteiluminacionpuntos_frptmed3').val(row.data().reporteiluminacionpuntos_frptmed3);

    // Mostrar resultados medicion
    calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');
    calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');
    calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');

	// Titulo del modal
	$('#modal_reporte_iluminacionpunto .modal-title').html('Punto de iluminación '+row.data().reporteiluminacionpuntos_nopunto);

	// mostrar modal
	$('#modal_reporte_iluminacionpunto').modal({backdrop:false});
});


$('#tabla_reporte_iluminacionpuntos tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_iluminacionpuntos.row(tr);

	// Valida envio de datos
	swal({   
		title: "¡Confirme eliminar!",
		text: "Punto de iluminación "+row.data().reporteiluminacionpuntos_nopunto,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm)
	{
		if (isConfirm)
		{
			swal({
				title: "¡Confirme nuevamente eliminar!",
				text: "Punto de iluminación "+row.data().reporteiluminacionpuntos_nopunto,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Eliminar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function(isConfirm){
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/reporteiluminaciontablapuntoseliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tablas
							tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
							tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
							reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);

							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function(dato)
						{
							// mensaje
							swal({
								title: "Error",
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
					});//Fin ajax
				}
				else 
				{
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
		else 
		{
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


$("#botonguardar_modal_iluminacionpunto").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Punto de iluminación "+$('#reporteiluminacionpuntos_nopunto').val(),
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_iluminacionpunto').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 16,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteiluminacion_id = dato.reporteiluminacion_id;

						tabla_reporte_revisiones(proyecto.id);

						// Actualizar tabla
						tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
						tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
						tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
						reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);
						
						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_modal_iluminacionpunto').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_iluminacionpunto').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_iluminacionpunto').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_iluminacionpunto').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_iluminacionpunto').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_iluminacionpunto').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_iluminacionpunto').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


var datatable_iluminacionresultados = null;
function tabla_reporte_iluminacionresultados(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontablaresultados/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (datatable_iluminacionresultados != null)
		{
			datatable_iluminacionresultados.clear().draw();
			datatable_iluminacionresultados.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_iluminacionresultados = $('#tabla_reporte_iluminacionresultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// if (parseInt(json.total) > 0)
						// {
						// 	alert("Total! "+json.total);
						// }

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_iluminacionresultados '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_iluminacionresultados(proyecto_id, reporteiluminacion_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteiluminacionpuntos_nopunto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario1",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario2",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario3",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_concepto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_nopoe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacioncategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "lux",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "luxmed1",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "luxmed2",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "luxmed3",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "lux_resultado",
						defaultContent: "-",
						// className: '',
						orderable: true,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [4, 5, 0], //agrupar filas
				ordering: false,
				// order: [[ 0, "DESC" ]],
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
				rowCallback: function(row, data, index)
				{
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					$(row).find('td:eq(9)').css('color', ''+data.luxmed1_color);
					$(row).find('td:eq(10)').css('color', ''+data.luxmed2_color);
					$(row).find('td:eq(11)').css('color', ''+data.luxmed3_color);

					$(row).find('td:eq(12)').css('background', ''+data.lux_resultado_color);
					

					if(data.lux_resultado == "Fuera de norma") // Color texto
					{
						
						$(row).find('td:eq(12)').css('color', '#FFFFFF');
					}
					else
					{
						$(row).find('td:eq(12)').css('color', '#000000');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_iluminacionresultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_iluminacionresultados(proyecto_id, reporteiluminacion_id);
    }
}


var datatable_reflexionresultados = null;
function tabla_reporte_reflexionresultados(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontablaresultados/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (datatable_reflexionresultados != null)
		{
			datatable_reflexionresultados.clear().draw();
			datatable_reflexionresultados.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reflexionresultados = $('#tabla_reporte_reflexionresultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// if (parseInt(json.total) > 0)
						// {
						// 	alert("Total! "+json.total);
						// }

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reflexionresultados '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_reflexionresultados(proyecto_id, reporteiluminacion_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteiluminacionpuntos_nopunto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario1",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario2",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_horario3",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacionpuntos_nopoe",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteiluminacioncategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frp",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frpt",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frpmed1",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frptmed1",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frpmed2",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frptmed2",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frpmed3",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "frptmed3",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "fr_resultado",
						defaultContent: "-",
						// className: '',
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [4, 0], //agrupar filas
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
				rowCallback: function(row, data, index)
				{
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					$(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
					$(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
					$(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
					$(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
					$(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
					$(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

					$(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);

					if(data.fr_resultado == "Fuera de norma") // Color texto
					{
						
						$(row).find('td:eq(15)').css('color', '#FFFFFF');
					}
					else
					{
						$(row).find('td:eq(15)').css('color', '#000000');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reflexionresultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_reflexionresultados(proyecto_id, reporteiluminacion_id);
    }
}


var datatable_matrizexposicion = null;
function tabla_reporte_matrizexposicion(proyecto_id, reporteiluminacion_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontablamatrizexposicion/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe;

		if (parseInt(proyecto.catregion_id) == 1) //REGION NORTE
		{
			if (datatable_matrizexposicion != null)
			{
				datatable_matrizexposicion.clear().draw();
				datatable_matrizexposicion.ajax.url(ruta).load();
			}
			else
			{
				$('#tabla_reporte_matrizexposicion').html('<thead>'+
																'<tr>'+
																	'<th rowspan="3"><span class="rotartexto">Contador</span></th>'+
																	'<th rowspan="3">Subdirección o<br>corporativo</th>'+
																	'<th rowspan="3">Gerencia o<br>activo</th>'+
																	'<th rowspan="3">Instalación</th>'+
																	'<th rowspan="3">Área de<br>referencia<br>en atlas<br>de riesgo</th>'+
																	'<th rowspan="3">Nombre</th>'+
																	'<th rowspan="3">Ficha</th>'+
																	'<th rowspan="3">Categoría</th>'+
																	'<th rowspan="3">Número<br>de<br>personas<br>en el área</th>'+
																	'<th rowspan="3">Grupo de<br>exposición<br>homogénea</th>'+
																	'<th rowspan="3">Niveles<br>mínimos de<br>iluminación<br>(lux)</th>'+
																	'<th colspan="3">Nivel de<br>iluminación<br>(E2)(lux)</th>'+
																	'<th colspan="3">Niveles<br>máximos<br>permisibles<br>de reflexión<br>plano de<br>trabajo (50%)</th>'+
																	'<th colspan="3">Niveles<br>máximos<br>permisibles<br>de reflexión<br>paredes (60%)</th>'+
																'</tr>'+
																'<tr>'+
																	'<th colspan="3">Periodo</th>'+
																	'<th colspan="3">Periodo</th>'+
																	'<th colspan="3">Periodo</th>'+
																'</tr>'+
																'<tr>'+
																	'<th>I</th>'+
																	'<th>II</th>'+
																	'<th>III</th>'+
																	'<th>I</th>'+
																	'<th>II</th>'+
																	'<th>III</th>'+
																	'<th>I</th>'+
																	'<th>II</th>'+
																	'<th>III</th>'+
																'</tr>'+
															'</thead>'+
															'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_matrizexposicion = $('#tabla_reporte_matrizexposicion').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json)
						{
							// if (parseInt(json.total) > 0)
							// {
							// 	alert("Total! "+json.total);
							// }

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code)
						{						
							console.log('error en datatable_matrizexposicion '+code);
							if (numeroejecucion <= 1)
							{
								tabla_reporte_matrizexposicion(proyecto_id, reporteiluminacion_id);
								numeroejecucion += 1;
							}
						}
					},
					columns: [
						// {
						//     data: "id" 
						// },
						{
							data: "reporteiluminacionpuntos_nopunto",
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
							data: "reporteiluminacionarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteiluminacionpuntos_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionpuntos_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteiluminacioncategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionareacategoria_total",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionareacategoria_geo",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "lux",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "luxmed1",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "luxmed2",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "luxmed3",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frptmed1",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frptmed2",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frptmed3",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frpmed1",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frpmed2",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "frpmed3",
							defaultContent: "-",
							orderable: false,
						}
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 4, 0], //agrupar filas
					order: [[ 0, "ASC" ]],
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
					rowCallback: function(row, data, index)
					{
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
			datatable_matrizexposicion.on('draw', function ()
			{
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
		else
		{
			// alert('sur');

			if (datatable_matrizexposicion != null)
			{
				datatable_matrizexposicion.clear().draw();
				datatable_matrizexposicion.ajax.url(ruta).load();
			}
			else
			{

				$('#tabla_reporte_matrizexposicion').html('<thead>'+
																'<tr>'+
																	'<th><span class="rotartexto">Contador</span></th>'+
																	'<th>Subdirección o<br>corporativo</th>'+
																	'<th>Gerencia o<br>activo</th>'+
																	'<th>Instalación</th>'+
																	'<th>Área de referencia<br>en atlas de riesgo</th>'+
																	'<th>Nombre</th>'+
																	'<th>Ficha</th>'+
																	'<th>Categoría</th>'+
																	'<th width="130">Iluminación<br>Lux (E 2c) / NMI</th>'+
																'</tr>'+
															'</thead>'+
															'<tbody></tbody>');


				var numeroejecucion = 1;
				datatable_matrizexposicion = $('#tabla_reporte_matrizexposicion').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json)
						{
							// if (parseInt(json.total) > 0)
							// {
							// 	alert("Total! "+json.total);
							// }

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code)
						{						
							console.log('error en datatable_matrizexposicion '+code);
							if (numeroejecucion <= 1)
							{
								tabla_reporte_matrizexposicion(proyecto_id, reporteiluminacion_id);
								numeroejecucion += 1;
							}
						}
					},
					columns: [
						// {
						//     data: "id" 
						// },
						{
							data: "reporteiluminacionpuntos_nopunto",
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
							data: "reporteiluminacionarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteiluminacionpuntos_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteiluminacionpuntos_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteiluminacioncategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "iluminacion_resultado",
							defaultContent: "-",
							orderable: false,
						},
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 4, 0], //agrupar filas
					order: [[ 0, "ASC" ]],
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
					rowCallback: function(row, data, index)
					{
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
			datatable_matrizexposicion.on('draw', function ()
			{
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
	}
	catch (exception)
	{
		tabla_reporte_matrizexposicion(proyecto_id, reporteiluminacion_id);
    }
}


//=================================================
// CONCLUSION


$("#botonguardar_reporte_conclusion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_conclusion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 20,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_8", 1);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_conclusion').html('Guardando conclusión <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_conclusion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_conclusion').html('Guardar conclusión <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_conclusion').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


var dashboard_ejecusiones = 0;
function reporteiluminacion_dashboard(proyecto_id, reporteiluminacion_id)
{
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteiluminaciondashboard/"+proyecto_id+"/"+reporteiluminacion_id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			$("#areas_cumplimiento").html(dato.areas_cumplimiento);
			$("#categorias_criticas").html(dato.categorias_criticas);
			$("#nivel_iluminacion").html(dato.datos.nivel_iluminacion);
			$("#total_iluminacion").html(dato.datos.total_iluminacion);
			$("#recomendaciones_total").html(dato.datos.recomendaciones_total);
			$("#total_iluminacion_dentronorma").html(dato.datos.total_iluminacion_dentronorma);
			$("#total_iluminacion_fueranorma").html(dato.datos.total_iluminacion_fueranorma);
			$("#total_reflexion_dentronorma").html(dato.datos.total_reflexion_dentronorma);
			$("#total_reflexion_fueranorma").html(dato.datos.total_reflexion_fueranorma);

			grafico_iluminacion(dato.iluminacion_datos);
			grafico_reflexion(dato.reflexion_datos);

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
		error: function(dato)
		{
			if (dashboard_ejecusiones == 0)
			{
				reporteiluminacion_dashboard(proyecto_id, reporteiluminacion_id);
				dashboard_ejecusiones += 1;
			}

			return false;
		}
	});//Fin ajax
}


var graficapastel_iluminacion = null;
function grafico_iluminacion(serie_grafico)
{
	graficapastel_iluminacion = AmCharts.makeChart("grafica_iluminacion", {
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
		"fontSize": 11,
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
		"legend":{
			'enabled': false,
			"position":"bottom",
			"marginRight":0,
			"marginLeft":0,
			"autoMargins":false,
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
		"colors": ["#8ee66b", "#fc4b6c"], //color de la series
		"dataProvider": serie_grafico,
		// "dataProvider": [
		// 	{
		// 		"titulo": "Dentro de norma",
		// 		"total": 21				
		// 	},
		// 	{
		// 		"titulo": "Fuera de norma",
		// 		"total": 44
		// 	}
		// ],
	});

	// graficapastel_iluminacion.addListener("init", handleInit);

	// graficapastel_iluminacion.addListener("rollOverSlice", function(e) {
	// 	handleRollOver(e);
	// });

	// function handleInit(){
	// 	graficapastel_iluminacion.legend.addListener("rollOverItem", handleRollOver);
	// }

	// function handleRollOver(e){
	// 	var wedge = e.dataItem.wedge.node;
	// 	wedge.parentNode.appendChild(wedge);
	// }





	// setTimeout(function()
	// {
	// 	graficapastel_iluminacion.export.capture({}, function ()
	// 	{
	// 	    this.toPNG({}, function (data)
	// 	    {
	// 			var image = new Image();
	// 			image.src = data;
	// 			// document.body.appendChild(image);
	// 			document.getElementById("captura").appendChild(image);
	// 			console.log('grafica pastel iluminacion');
	// 	    });
	// 	}); 
	// }, 3000);







	// setTimeout(function()
	// {
	// 	html2canvas(document.querySelector("#areas_cumplimiento"), { scale: 6}).then(canvas =>
	// 	{
	// 	    document.getElementById("captura").appendChild(canvas)
	// 	    // var imgData = canvas.toDataURL('image/jpeg');
	// 	});

	// }, 5000);







	// chart["export"].capture({}, function ()
	// {
	//     this.toPNG({}, function (base64)
	//     {
	// 		$.ajax({
	// 			url: '/reporteiluminaciondashboardgraficas',
	// 			type: 'POST',
	// 			data: { base64: base64},
	// 			success: function (data)
	// 			{
	// 				if (data != "")
	// 				{
	// 					alert("file saved.")
	// 				}
	// 				else
	// 				{
	// 					alert("file not saved.")
	// 				}
	// 			},
	// 			error: function (r, s, e)
	// 			{
	// 				alert("Unexpected error:" + e);
	// 				console.log(r);
	// 				console.log(s);
	// 				console.log(e);
	// 			}
	// 		});
	//     });
	// }); 
}


var graficapastel_reflexion = null;
function grafico_reflexion(serie_grafico)
{
	graficapastel_reflexion = AmCharts.makeChart("grafica_reflexion", {
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
		"fontSize": 11,
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
		"legend":{
			'enabled': false,
			"position":"bottom",
			"marginRight":0,
			"marginLeft":0,
			"autoMargins":false,
			"valueText": "[[description]]" //"[[description]] [[value]]"
		},"export": {
			"enabled": true,
			'position': 'top-right'
		},
		"valueField": "total",
		"titleField": "titulo",
		// "labelText": "[[title]]<br>$[[value]]<br>([[percents]]%)",
		"labelText": "[[value]]<br>([[percents]]%)",
		"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
		"labelRadius": -30,
		"colors": ["#8ee66b", "#fc4b6c"], //color de la series
		"dataProvider": serie_grafico,
		// "dataProvider": [
		// 	{
		// 		"titulo": "Dentro de norma",
		// 		"total": 40				
		// 	},
		// 	{
		// 		"titulo": "Fuera de norma",
		// 		"total": 65
		// 	}
		// ],
	});

	// graficapastel_reflexion.addListener("init", handleInit);

	// graficapastel_reflexion.addListener("rollOverSlice", function(e) {
	// 	handleRollOver(e);
	// });

	// function handleInit(){
	// 	graficapastel_reflexion.legend.addListener("rollOverItem", handleRollOver);
	// }

	// function handleRollOver(e){
	// 	var wedge = e.dataItem.wedge.node;
	// 	wedge.parentNode.appendChild(wedge);
	// }


	// setTimeout(function()
	// {
	// 	graficapastel_reflexion.export.capture({}, function ()
	// 	{
	// 	    this.toPNG({}, function (data)
	// 	    {
	// 			var image = new Image();
	// 			image.src = data;
	// 			// document.body.appendChild(image);
	// 			document.getElementById("captura").appendChild(image);
	// 			console.log('grafica pastel reflexion');
	// 	    });
	// 	}); 
	// }, 4000);
}


$("#botonguardar_generargraficas").click(function()
{
	var grafica_iluminacion_imgbase64 = '';
	graficapastel_iluminacion.export.capture({}, function ()
	{
		this.toPNG({}, function (img_base64)
		{
			// var image = new Image();
			// image.src = data;
			// document.getElementById("captura").appendChild(image);
			// console.log('grafica pastel iluminacion');

			grafica_iluminacion_imgbase64 = img_base64;
		});
	});


	var grafica_reflexion_imgbase64 = '';
	graficapastel_reflexion.export.capture({}, function ()
	{
		this.toPNG({}, function (img_base64)
		{
			// var image = new Image();
			// image.src = img_base64;
			// document.getElementById("captura").appendChild(image);
			// console.log('grafica pastel reflexion');

			grafica_reflexion_imgbase64 = img_base64;
		});
	}); 


	html2canvas(document.querySelector("#areas_cumplimiento"), { scale: 6}).then(canvas =>
	{
		// document.getElementById("captura").appendChild(canvas)
		var imgData = canvas.toDataURL('image/jpeg');

		// Enviar datos
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "/reporteiluminaciondashboardgraficas",
			data:{
				_token: document.querySelector('meta[name="csrf-token"]')['content'],
				reporteiluminacion_id: reporteiluminacion_id,
				grafica1: imgData,
				grafica_iluminacion: grafica_iluminacion_imgbase64,
				grafica_reflexion: grafica_reflexion_imgbase64,
			},
			cache: false,
			success:function(dato)
			{
				// mensaje
				swal({
					title: "Correcto",
					text: ""+dato.msj,
					type: "success", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
			},
			error: function(dato)
			{
				alert('Error');
				return false;
			}
		});//Fin ajax
	});
});



//=================================================
// RECOMENDACIONES


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_recomendaciones(proyecto.id, reporteiluminacion_id, agente_nombre);
	}, 4500);
});


var datatable_recomendaciones = null;
function tabla_reporte_recomendaciones(proyecto_id, reporteiluminacion_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteiluminaciontablarecomendaciones/"+proyecto_id+"/"+reporteiluminacion_id+"/"+agente_nombre;

		if (datatable_recomendaciones != null)
		{
			datatable_recomendaciones.clear().draw();
			datatable_recomendaciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_recomendaciones = $('#tabla_reporte_recomendaciones').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.total) > 0)
						{
							menureporte_estado("menureporte_9", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						console.log('error en datatable_recomendaciones');
						if (numeroejecucion <= 1)
						{
							tabla_reporte_recomendaciones(proyecto_id, reporteiluminacion_id, agente_nombre);
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
				"order": [[ 0, "ASC" ]],
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
		datatable_recomendaciones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_recomendaciones(proyecto_id, reporteiluminacion_id, agente_nombre);
    }
}


$("#boton_reporte_nuevarecomendacion").click(function()
{
    $("#tabla_reporte_recomendaciones tbody").append( '<tr>'+
															'<td>0</td>'+
															'<td style="text-align: center;">'+
																'<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>'+
																'<br><button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-2x"></i></button>'+
															'</td>'+
															'<td>'+
																'<div class="form-group">'+
																	'<label>Tipo</label>'+
																	'<select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>'+
																		'<option value=""></option>'+
																		'<option value="Preventiva">Preventiva</option>'+
																		'<option value="Correctiva">Correctiva</option>'+
																	'</select>'+
																'</div>'+
																'<div class="form-group">'+
																	'<label>Descripción</label>'+
																	'<textarea  class="form-control" style="margin-bottom: 0px;" rows="5" name="recomendacionadicional_descripcion[]" required></textarea>'+
																'</div>'+
															'</td>'+
														'</tr>');

    var posicion = $("#tabla_reporte_recomendaciones > tbody > tr").eq((parseInt(document.getElementById("tabla_reporte_recomendaciones").rows.length) - 1) - 2).offset().top;
    $('html, body').animate({
        scrollTop: posicion
    }, 1000);

    $('[data-toggle="tooltip"]').tooltip();
});


function activa_recomendacion(checkbox)
{
	if (checkbox.checked)
	{
		$('#recomendacion_descripcion_'+checkbox.value).attr('readonly', false);
		$('#recomendacion_descripcion_'+checkbox.value).attr('required', true);
	}
	else
	{
		$('#recomendacion_descripcion_'+checkbox.value).attr('required', false);
		$('#recomendacion_descripcion_'+checkbox.value).attr('readonly', true);
	}
}


$('#tabla_reporte_recomendaciones tbody').on('click', '.eliminar', function()
{
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
    }, function(isConfirm){   
        if (isConfirm)
        {
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
        else 
        {
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


$("#botonguardar_reporte_recomendaciones").click(function()
{
	// borrar campo filtro del DATATABLE
	// datatable_recomendaciones.search("").draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.recomendacion_checkbox').each(function()
	{
		if (this.checked)
		{
			seleccionados += 1;
		}
	});

	$('.recomendacionadicional_checkbox').each(function()
	{
		if (this.checked)
		{
			seleccionados += 1;
		}
	});


	if (seleccionados > 0)
	{
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida)
		{
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
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_reporte_recomendaciones').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reporteiluminacion',
						data: {
							opcion: 30,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteiluminacion_id: reporteiluminacion_id,
							catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
							reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						},
						resetForm: false,
						success: function(dato)
						{
							// Actualizar ID reporte						
							reporteiluminacion_id = dato.reporteiluminacion_id;

							menureporte_estado("menureporte_9", 1);

							tabla_reporte_revisiones(proyecto.id);

							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
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
						beforeSend: function()
						{
							$('#botonguardar_reporte_recomendaciones').html('Guardando recomendaciones <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_recomendaciones').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_reporte_recomendaciones').html('Guardar recomendaciones <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_recomendaciones').attr('disabled', false);

							// mensaje
							swal({
								title: "Error",
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
			return false;
		}
	}
	else
	{
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


$(document).ready(function()
{
	$('#reporteiluminacionresponsable1documento').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove':  'Quitar',
			'error':   'Ooops, ha ocurrido un error.'
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

	$('#reporteiluminacionresponsable2documento').dropify({
		messages:
		{
			'default': 'Arrastre el mapa aquí o haga click',
			'replace': 'Arrastre el mapa o haga clic para reemplazar',
			'remove':  'Quitar',
			'error':   'Ooops, ha ocurrido un error.'
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
			'remove':  'Quitar',
			'error':   'Ooops, ha ocurrido un error.'
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


$("#botonguardar_reporte_responsablesinforme").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_responsablesinforme').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 40,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						menureporte_estado("menureporte_10", 1);

						tabla_reporte_revisiones(proyecto.id);

						$('#boton_descargarresponsabledoc1').css('display', 'block');
						$('#boton_descargarresponsabledoc2').css('display', 'block');

						// Carpeta ubicacion documentos historial
						$('#reporteiluminacion_carpetadocumentoshistorial').val('');

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
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
					beforeSend: function()
					{
						$('#botonguardar_reporte_responsablesinforme').html('Guardando responsables del informe <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_responsablesinforme').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_responsablesinforme').html('Guardar responsables del informe <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_responsablesinforme').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$("#boton_descargarresponsabledoc1").click(function()
{
	window.open('/reporteiluminacionresponsabledocumento/'+reporteiluminacion_id+'/'+1+'/'+1);
});


$("#boton_descargarresponsabledoc2").click(function()
{
	window.open('/reporteiluminacionresponsabledocumento/'+reporteiluminacion_id+'/'+2+'/'+1);
});


//=================================================
// PLANOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_planos(proyecto.id, reporteiluminacion_id, agente_nombre);
	}, 5000);
});


var datatable_planos = null;
function tabla_reporte_planos(proyecto_id, reporteiluminacion_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteiluminaciontablaplanos/"+proyecto_id+"/"+reporteiluminacion_id+"/"+agente_nombre;

		if (datatable_planos != null)
		{
			datatable_planos.clear().draw();
			datatable_planos.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_planos = $('#tabla_reporte_planos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// Actualiza menu
						menureporte_estado("menureporte_11_2", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_planos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_planos(proyecto_id, reporteiluminacion_id, agente_nombre);
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
				order: [[ 0, "ASC" ]],
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
		datatable_planos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_planos(proyecto_id, reporteiluminacion_id, agente_nombre);
    }
}


$("#botonguardar_reporte_planos").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_planos').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 45,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						// Actualiza menu
						menureporte_estado("menureporte_11_2", dato.total);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_planos').html('Guardar carpeta planos seleccionados <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_planos').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_planos').html('Guardando carpeta planos seleccionados <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_planos').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_planos').html('Guardar carpeta planos seleccionados <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_planos').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_equipoutilizado(proyecto.id, reporteiluminacion_id, agente_nombre);
	},5500);
});


var datatable_equipoutilizado = null;
function tabla_reporte_equipoutilizado(proyecto_id, reporteiluminacion_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteiluminaciontablaequipoutilizado/"+proyecto_id+"/"+reporteiluminacion_id+"/"+agente_nombre;

		if (datatable_equipoutilizado != null)
		{
			datatable_equipoutilizado.clear().draw();
			datatable_equipoutilizado.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_equipoutilizado = $('#tabla_reporte_equipoutilizado').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.total) > 0)
						{
							menureporte_estado("menureporte_11_3", 1);
							menureporte_estado("menureporte_11_5", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_equipoutilizado '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_equipoutilizado(proyecto_id, reporteiluminacion_id, agente_nombre);
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
					// {
					// 	className: 'certificadopdf',
					// 	orderable: false,
					// 	data: "certificado",
					// 	defaultContent: "-"
					// },
					// {
					// 	data: "checkbox_carta",
					// 	defaultContent: "-",
					// 	orderable: false,
					// },
					// {
					// 	className: 'cartapdf',
					// 	data: "carta",
					// 	defaultContent: "-",
					// 	orderable: false,
					// },
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				// rowsGroup: [0, 1], //agrupar filas
				order: [[ 0, "ASC" ]],
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
		datatable_equipoutilizado.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_equipoutilizado(proyecto_id, reporteiluminacion_id, agente_nombre);
    }
}


function activa_checkboxcarta(checkbox, equipo_id)
{
	if (checkbox.checked)
	{
		$('#equipoutilizado_checkboxcarta_'+equipo_id).attr('disabled', false);
	}
	else
	{
		$('#equipoutilizado_checkboxcarta_'+equipo_id).prop("checked", false);
		$('#equipoutilizado_checkboxcarta_'+equipo_id).attr('disabled', true);
	}
}


$('#tabla_reporte_equipoutilizado tbody').on('click', 'td.cartapdf', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_equipoutilizado.row(tr);

	if (row.data().equipo_cartaPDF)
	{
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/'+row.data().equipo_id+'/'+2);

		// Titulo modal
		$('#modal_visor .modal-title').html('Carta '+row.data().equipo_Descripcion+' ['+row.data().equipo_Serie+']');

		// Abrir modal
		$('#modal_visor').modal({backdrop:false});
	}
});



$('#tabla_reporte_equipoutilizado tbody').on('click', 'td.certificadopdf', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_equipoutilizado.row(tr);

	if (row.data().equipo_CertificadoPDF)
	{
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/'+row.data().equipo_id+'/'+1);

		// Titulo modal
		$('#modal_visor .modal-title').html('Certificado de calibración '+row.data().equipo_Descripcion+' ['+row.data().equipo_Serie+']');

		// Abrir modal
		$('#modal_visor').modal({backdrop:false});
	}
});


$("#botoncerrar_modalvisor_reporteiluminacion").click(function()
{
	// Titulo modal
    $('#modal_visor .modal-title').html('Documento');

	// Visor
	$('#visor_documento').attr('src', '/assets/images/cargando.gif');
});


$("#botonguardar_reporte_equipoutilizado").click(function()
{
	// borrar campo filtro del DATATABLE
	// datatable_equipoutilizado.search($(this).val()).draw();
	datatable_equipoutilizado.search("").draw();


	// valida campos vacios
	var seleccionados = 0;
	$('.reporteiluminacion_equipoutilizadocheckbox').each(function()
	{
		if (this.checked)
		{
			seleccionados += 1;
		}
	});


	if (seleccionados > 0)
	{
		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida)
		{
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
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_reporte_equipoutilizado').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reporteiluminacion',
						data: {
							opcion: 50,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteiluminacion_id: reporteiluminacion_id,
							catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
							reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						},
						resetForm: false,
						success: function(dato)
						{
							// Actualizar ID reporte							
							reporteiluminacion_id = dato.reporteiluminacion_id;

							menureporte_estado("menureporte_11_3", 1);
							menureporte_estado("menureporte_11_5", 1);

							// datatable_equipoutilizado.ajax.reload();

							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
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
						beforeSend: function()
						{
							$('#botonguardar_reporte_equipoutilizado').html('Guardando equipo utilizado <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_equipoutilizado').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_reporte_equipoutilizado').html('Guardar equipo utilizado <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_equipoutilizado').attr('disabled', false);

							// mensaje
							swal({
								title: "Error",
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
			return false;
		}
	}
	else
	{
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


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_informeresultados(proyecto.id, reporteiluminacion_id, agente_nombre);
	}, 6000);
});


var datatable_informeresultados = null;
function tabla_reporte_informeresultados(proyecto_id, reporteiluminacion_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteiluminaciontablainformeresultados/"+proyecto_id+"/"+reporteiluminacion_id+"/"+agente_nombre;

		if (datatable_informeresultados != null)
		{
			datatable_informeresultados.clear().draw();
			datatable_informeresultados.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_informeresultados = $('#tabla_reporte_informeresultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// Actualiza menu
						menureporte_estado("menureporte_11_6", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_informeresultados '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_informeresultados(proyecto_id, reporteiluminacion_id, agente_nombre);
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
				order: [[ 0, "ASC" ]],
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
				}
			});
		}

		// Tooltip en DataTable
		datatable_informeresultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_informeresultados(proyecto_id, reporteiluminacion_id, agente_nombre);
    }
}


$("#botonguardar_reporte_informeresultados").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Informe de resultados",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_informeresultados').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 55,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteiluminacion_id = dato.reporteiluminacion_id;

						// Actualiza menu
						menureporte_estado("menureporte_11_6", dato.total);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_informeresultados').html('Guardar anexo informe de resultados <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_informeresultados').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_informeresultados').html('Guardando anexo informe de resultados <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_informeresultados').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_informeresultados').html('Guardar anexo informe de resultados <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_informeresultados').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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
					timer: 500,
					showConfirmButton: false
				});
			}
		});
		return false;
	}
});


$('#tabla_reporte_informeresultados tbody').on('click', 'td.documentopdf', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_informeresultados.row(tr);

	if (row.data().proyectoevidenciadocumento_extension == ".pdf" || row.data().proyectoevidenciadocumento_extension == ".PDF")
	{
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoevidenciadocumentodescargar/'+row.data().id+'/'+0); // 0 mostrar

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().proyectoevidenciadocumento_nombre);

		// Abrir modal
		$('#modal_visor').modal({backdrop:false});
	}
	else
	{
		window.open("/proyectoevidenciadocumentodescargar/"+row.data().id+"/"+1); //1 descargar
	}
});


//=================================================
// ANEXOS PDF


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_anexos(proyecto.id, reporteiluminacion_id, agente_nombre);
	}, 6500);
});


var datatable_anexos = null;
function tabla_reporte_anexos(proyecto_id, reporteiluminacion_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteiluminaciontablaanexos/"+proyecto_id+"/"+reporteiluminacion_id+"/"+agente_nombre;

		if (datatable_anexos != null)
		{
			datatable_anexos.clear().draw();
			datatable_anexos.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_anexos = $('#tabla_reporte_anexos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.total) > 0)
						{
							menureporte_estado("menureporte_11_7", 1);
							menureporte_estado("menureporte_11_8", 1);
							menureporte_estado("menureporte_12", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_anexos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_anexos(proyecto_id);
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
				rowCallback: function(row, data, index)
				{
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
		datatable_anexos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_anexos(proyecto_id, reporteiluminacion_id, agente_nombre);
    }
}


$('#tabla_reporte_anexos tbody').on('click', 'td.certificadopdf', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_anexos.row(tr);

	if (row.data().acreditacion_SoportePDF)
	{
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/'+row.data().id);

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().catTipoAcreditacion_Nombre+' '+row.data().acreditacion_Entidad+' ['+row.data().acreditacion_Numero+']');

		// Abrir modal
		$('#modal_visor').modal({backdrop:false});
	}
});


$("#botonguardar_reporte_anexos").click(function()
{
	// borrar campo filtro del DATATABLE
	// datatable_anexos.search($(this).val()).draw();
	datatable_anexos.search("").draw();


	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Anexos",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Guardar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_reporte_anexos').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/reporteiluminacion',
					data: {
						opcion: 60,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteiluminacion_id: reporteiluminacion_id,
						catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
						reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteiluminacion_id = dato.reporteiluminacion_id;

						// Actualiza menu
						menureporte_estado("menureporte_11_7", dato.total);
						menureporte_estado("menureporte_11_8", dato.total);
						menureporte_estado("menureporte_12", dato.total);

						tabla_reporte_revisiones(proyecto.id);

						// mensaje
						swal({
							title: "Correcto",
							text: ""+dato.msj,
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						// actualiza boton
						$('#botonguardar_reporte_anexos').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_anexos').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_anexos').html('Guardando anexos 7 (STPS) y 8 (EMA) <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_anexos').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_anexos').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_anexos').attr('disabled', false);

						// mensaje
						swal({
							title: "Error",
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


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_revisiones(proyecto.id);
	}, 8500);
});


var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id)
{
	try 
	{
		var ruta = "/reporteiluminaciontablarevisiones/"+proyecto_id;

		if (datatable_reporterevisiones != null)
		{
			datatable_reporterevisiones.clear().draw();
			datatable_reporterevisiones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporterevisiones = $('#tabla_reporte_revisiones').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.total) > 0)
						{
							$("#boton_reporte_nuevarevision").attr('disabled', false);
						}
						else
						{
							$("#boton_reporte_nuevarevision").attr('disabled', true);
						}


						ultimarevision_id = parseInt(json.ultimarevision_id);
						ultimaversion_cancelada = parseInt(json.ultimaversion_cancelada);
						botoninforme_estado(json.ultimaversion_estado);


						if (areas_poe == 1)
						{
							setTimeout(function()
							{
								$("#boton_reporte_nuevacategoria").attr('disabled', true);
								$("#boton_reporte_nuevaarea").attr('disabled', true);
							}, 5000);
						}


						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporterevisiones '+code);
						if (numeroejecucion <= 1)
						{
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
				rowCallback: function(row, data, index)
				{
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
		datatable_reporterevisiones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_revisiones(proyecto_id);
    }
}


$("#boton_reporte_nuevarevision").click(function()
{
	if (ultimaversion_cancelada == 1)
	{
		if (parseInt(datatable_iluminacionpuntos.data().count()) > 0)
		{
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
			}, function(isConfirm){
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Copiando revisión, por favor espere...');
					$('#boton_reporte_nuevarevision').attr('disabled', true);

					setTimeout(function()
					{
						var grafica_iluminacion_imgbase64 = '';
						graficapastel_iluminacion.export.capture({}, function ()
						{
							this.toPNG({quality: 1, multiplier: 6}, function (img_base64)
							{
								// var image = new Image();
								// image.src = data;
								// document.getElementById("captura").appendChild(image);
								// console.log('grafica pastel iluminacion');

								grafica_iluminacion_imgbase64 = img_base64;
							});
						});


						var grafica_reflexion_imgbase64 = '';
						graficapastel_reflexion.export.capture({}, function ()
						{
							this.toPNG({quality: 1, multiplier: 6}, function (img_base64)
							{
								// var image = new Image();
								// image.src = img_base64;
								// document.getElementById("captura").appendChild(image);
								// console.log('grafica pastel reflexion');

								grafica_reflexion_imgbase64 = img_base64;
							});
						});


						html2canvas(document.querySelector("#areas_cumplimiento"), { scale: 6}).then(canvas =>
						{
							// document.getElementById("captura").appendChild(canvas)
							var imgData = canvas.toDataURL('image/jpeg');

							setTimeout(function()
							{
								// Enviar datos
								$.ajax({
									type: "POST",
									dataType: "json",
									url: "/reporteiluminacionword",
									data:{
										_token: document.querySelector('meta[name="csrf-token"]')['content'],
										proyecto_id: proyecto.id,
										agente_id: agente_id,
										agente_nombre: agente_nombre,
										reporteiluminacion_id: reporteiluminacion_id,
										areas_poe: areas_poe,
										ultimarevision_id: ultimarevision_id,
										crear_revision: 1,
										grafica1: imgData,
										grafica_iluminacion: grafica_iluminacion_imgbase64,
										grafica_reflexion: grafica_reflexion_imgbase64,
									},
									cache: false,
									success:function(dato)
									{
										botoninforme_estado(0); //Desbloquear


										// ACTUALIZAR TABLAS
										tabla_reporte_revisiones(proyecto.id);
										tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
										tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
										tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
										tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
										tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
										tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
										tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
										reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);


										// desplazar a la ultima fila de la tabla
										setTimeout(function()
										{
											$('html, body').animate({
												scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
											}, 1000);
										}, 2000);


										// mensaje
										swal({
											title: "Correcto",
											text: ""+dato.msj,
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
									error: function(dato)
									{
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
				else 
				{
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
		else
		{
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
	else
	{
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


function botoninforme_estado(boton_estado)
{
	if (parseInt(boton_estado) > 0)
	{
		$(".botoninforme").attr('disabled', true);

		$(".botoninforme>i").removeClass('fa-save');
		$(".botoninforme>i").addClass('fa-ban');
	}
	else
	{
		$(".botoninforme").attr('disabled', false);

		$(".botoninforme>i").removeClass('fa-ban');
		$(".botoninforme>i").addClass('fa-save');
	}
}


function reporte_concluido(revision_id, perfil, checkbox)
{
	if (parseInt(perfil) == 1)
	{
		if (checkbox.checked) // Activado
		{
			$(checkbox).prop('checked', false);


			var pendientes = 0;
			var pendientes_puntos = '';
			$('#top-menu .fa-times').each(function()
			{
				pendientes += 1;

				var texto = this.id;
				texto = texto.replace("menureporte_", "");
				texto = texto.replace("_", ".");
				pendientes_puntos += '\nPunto: '+texto;
			});


			if (parseInt(pendientes) == 0)
			{
				// Confirmar
				swal({
					title: "¿Concluir informe?",
					text: "Ultima revisión del informe de "+agente_nombre,
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Aceptar!",
					cancelButtonText: "Cancelar!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm)
				{
					if (isConfirm)
					{
						// cerrar msj confirmacion
						swal.close();

						
						// Enviar datos
						$.ajax({
							type: "GET",
							dataType: "json",
							url: "/reporteiluminacionconcluirrevision/"+revision_id,
							data:{},
							cache: false,
							success:function(dato)
							{
								// Actualizar tablas
								tabla_reporte_revisiones(proyecto.id);
								botoninforme_estado(dato.estado);

								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
								tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
								tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
								tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);


								// desplazar a la ultima fila de la tabla
								setTimeout(function()
								{
									$('html, body').animate({
										scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
									}, 1000);
								}, 2000);


								// mensaje
								swal({
									title: "Correcto",
									text: ""+dato.msj,
									type: "success", // warning, error, success, info
									buttons: {
										visible: false, // true , false
									},
									timer: 1500,
									showConfirmButton: false
								});
							},
							error: function(dato)
							{
								tabla_reporte_revisiones(proyecto.id);

								// mensaje
								swal({
									title: "Correcto",
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
						});//Fin ajax
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
							timer: 500,
							showConfirmButton: false
						});
					}
				});
			}
			else
			{
				// mensaje
				swal({
					title: "No disponible",
					text: 'Esta revisión del informe aún no ha sido completada, se encontraron '+pendientes+' punto(s) pendiente(s) en todo el contenido por guardar.\n'+pendientes_puntos,
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
		else
		{
			$(checkbox).prop('checked', true);

			// Confirmar
			swal({
				title: "¿Quitar concluido?",
				text: "Ultima revisión del informe de "+agente_nombre,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Aceptar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					
					// Enviar datos
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/reporteiluminacionconcluirrevision/"+revision_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// desplazar a la ultima fila de la tabla
							setTimeout(function()
							{
								$('html, body').animate({
									scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
								}, 1000);
							}, 2000);


							// Actualizar tablas
							tabla_reporte_revisiones(proyecto.id);
							botoninforme_estado(dato.estado);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
							tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
							tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);


							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 1500,
								showConfirmButton: false
							});
						},
						error: function(dato)
						{
							tabla_reporte_revisiones(proyecto.id);

							// mensaje
							swal({
								title: "Correcto",
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
					});//Fin ajax
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
			return false;
		}	
	}
}


function reporte_cancelado(revision_id, perfil, checkbox)
{
	if (parseInt(perfil) == 1)
	{
		$('#form_modal_cancelacionobservacion').each(function(){
			this.reset();
		});


		// Campos Hidden
		$('#reporterevisiones_id').val(revision_id);


		if (checkbox.checked) // Activado
		{
			$(checkbox).prop('checked', false);
			// alert('Cancelado '+checkbox.checked);

			// mostrar modal observacion
			$('#modal_reporte_cancelacionobservacion').modal({backdrop:false, keyboard:false});
		}
		else
		{
			$(checkbox).prop('checked', true);
			// alert('Descancelado '+checkbox.checked);


			// Confirmar
			swal({
				title: "¿Quitar cancelación?",
				text: "Ultima revisión del informe de "+agente_nombre,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Aceptar!",
				cancelButtonText: "Cancelar!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					
					// enviar datos
					$('#form_modal_cancelacionobservacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: '/reporteiluminacion',
						data: {
							opcion: 70,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteiluminacion_id: reporteiluminacion_id,
							catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
							reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
						},
						resetForm: false,
						success: function(dato)
						{
							// Actualizar ID reporte
							reporteiluminacion_id = dato.reporteiluminacion_id;


							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
							tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
							tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
							tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);

							
							// desplazar a la ultima fila de la tabla
							setTimeout(function()
							{
								$('html, body').animate({
									scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
								}, 1000);
							}, 2000);


							// mensaje
							swal({
								title: "Correcto",
								text: ""+dato.msj,
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
						beforeSend: function()
						{
							// $('#botonguardar_modal_cancelacionobservacion').html('Guardando observación y cancelar revisión <i class="fa fa-spin fa-spinner"></i>');
							// $('#botonguardar_modal_cancelacionobservacion').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							// $('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
							// $('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

							// mensaje
							swal({
								title: "Error",
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
						timer: 500,
						showConfirmButton: false
					});
				}
			});
			return false;
		}
	}
}


$("#botonguardar_modal_cancelacionobservacion").click(function()
{
	swal({
		title: "¡Confirme que desea cancelar!",
		text: "Ultima revisión del informe de "+agente_nombre,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Aceptar!",
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm)
	{
		if (isConfirm)
		{
			// cerrar msj confirmacion
			swal.close();

			// enviar datos
			$('#form_modal_cancelacionobservacion').ajaxForm({
				dataType: 'json',
				type: 'POST',
				url: '/reporteiluminacion',
				data: {
					opcion: 70,
					proyecto_id: proyecto.id,
					agente_id: agente_id,
					agente_nombre: agente_nombre,
					reporteiluminacion_id: reporteiluminacion_id,
					catactivo_id: $("#reporteiluminacion_catactivo_id").val(),
					reporteiluminacion_instalacion: $("#reporteiluminacion_instalacion").val(),
				},
				resetForm: false,
				success: function(dato)
				{
					// Actualizar ID reporte
					reporteiluminacion_id = dato.reporteiluminacion_id;

					// Actualizar tablas
					botoninforme_estado(dato.estado);
					tabla_reporte_revisiones(proyecto.id);

					tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteiluminacion_id);
					tabla_reporte_categorias(proyecto.id, reporteiluminacion_id);
					tabla_reporte_areas(proyecto.id, reporteiluminacion_id);
					tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);

					
					// desplazar a la ultima fila de la tabla
					setTimeout(function()
					{
						$('html, body').animate({
							scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
						}, 1000);
					}, 2000);


					// mensaje
					swal({
						title: "Correcto",
						text: ""+dato.msj,
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
				beforeSend: function()
				{
					$('#botonguardar_modal_cancelacionobservacion').html('Guardando observación y cancelar revisión <i class="fa fa-spin fa-spinner"></i>');
					$('#botonguardar_modal_cancelacionobservacion').attr('disabled', true);
				},
				error: function(dato)
				{
					// actualiza boton
					$('#botonguardar_modal_cancelacionobservacion').html('Guardar observación y cancelar revisión <i class="fa fa-save"></i>');
					$('#botonguardar_modal_cancelacionobservacion').attr('disabled', false);

					// mensaje
					swal({
						title: "Error",
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
				timer: 500,
				showConfirmButton: false
			});
		}
	});
	return false;
});


//=================================================
// GENERAR WORD


$('#tabla_reporte_revisiones tbody').on('click', 'td>button.botondescarga', function()
{
	var botondescarga = this;


	var tr = $(this).closest('tr');
	var row = datatable_reporterevisiones.row(tr);


	// Boton descarga
	$("#"+botondescarga.id).html('<i class="fa fa-spin fa-spinner fa-2x"></i>');


	setTimeout(function()
	{
		if (parseInt(row.data().id) == parseInt(ultimarevision_id))
		{
			var grafica_iluminacion_imgbase64 = '';
			graficapastel_iluminacion.export.capture({}, function ()
			{
				this.toPNG({quality: 1, multiplier: 6}, function (img_base64)
				{
					// var image = new Image();
					// image.src = data;
					// document.getElementById("captura").appendChild(image);
					// console.log('grafica pastel iluminacion');

					grafica_iluminacion_imgbase64 = img_base64;
				});
			});



			var grafica_reflexion_imgbase64 = '';
			graficapastel_reflexion.export.capture({}, function ()
			{
				this.toPNG({quality: 1, multiplier: 6}, function (img_base64)
				{
					// var image = new Image();
					// image.src = img_base64;
					// document.getElementById("captura").appendChild(image);
					// console.log('grafica pastel reflexion');

					grafica_reflexion_imgbase64 = img_base64;
				});
			});


			html2canvas(document.querySelector("#areas_cumplimiento"), { scale: 6}).then(canvas =>
			{
				// document.getElementById("captura").appendChild(canvas)
				var imgData = canvas.toDataURL('image/jpeg');

				setTimeout(function()
				{
					// Enviar datos
					$.ajax({
						type: "POST",
						dataType: "json",
						url: "/reporteiluminacionword",
						data:{
							_token: document.querySelector('meta[name="csrf-token"]')['content'],
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteiluminacion_id: reporteiluminacion_id,
							areas_poe: areas_poe,
							ultimarevision_id: ultimarevision_id,
							crear_revision: 0,
							grafica1: imgData,
							grafica_iluminacion: grafica_iluminacion_imgbase64,
							grafica_reflexion: grafica_reflexion_imgbase64,
						},
						cache: false,
						success:function(dato)
						{
							ventana = window.open('/reporteiluminacionworddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


							// // Boton descarga
							// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


							setTimeout(function()
							{
								tabla_reporte_revisiones(proyecto.id);
							}, 6000);


							setTimeout(function()
							{
								if (ventana.window)
								{
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
						error: function(dato)
						{
							// mensaje
							swal({
								title: "Error",
								text: "Al intentar crear informe, intentelo de nuevo.\n"+dato,
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
		}
		else
		{
			ventana = window.open('/reporteiluminacionworddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


			// // Boton descarga
			// $("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');


			setTimeout(function()
			{
				tabla_reporte_revisiones(proyecto.id);
			}, 6000);


			setTimeout(function()
			{
				if (ventana.window)
				{
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

//FUNCION PARA CARGAR PUNTOS POR MEDIO DE UN EXCEL
$(document).ready(function () {

    $('#boton_reporte_iluminacion_importar').on('click', function (e) {
        e.preventDefault();

        $('#divCargaPuntos').css('display', 'none');
        $('#alertaVerificacion2').css('display', 'none');

        $('#formExcelPuntos')[0].reset();

        $('#modal_excel_puntos').modal({backdrop:false});

    })

    $("#botonCargarExcelPuntos").click(function() {
        var guardar = 0;

        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida){
            if ($("#excelPuntos").val() != ""){
                // Tipo archivo
                var archivo = $("#excelPuntos").val();
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
            }
            else{
                guardar = 0;
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
                        $('#formExcelPuntos').ajaxForm({
                            dataType: 'json',
                            type: 'POST',
                            url: "/reporteiluminacion",
                            data: {
								opcion: 8000,
								registro_id: reporteiluminacion_id,
								proyecto_id: proyecto.id,

                                
                            },
                            contentType: false,
                            processData: false,
                            success: function (dato) {

                                // actualizar boton
                                $('#botonCargarExcelPuntos').prop('disabled', false);
                                $('#divCargaPuntos').css('display', 'none');
                                
                                if (dato.code == 200) {
                                    
                                    // cerrar modal
                                    $('#modal_excel_puntos').modal('hide');
    
                                    // mensaje
                                    swal({
                                        title: "Los puntos fueron cargados exitosamente",
                                        text: ""+dato.msj,
                                        type: "success", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });

                                    //Recargamos la tabla
                                   	menureporte_estado("menureporte_0", 1);

									tabla_reporte_revisiones(proyecto.id);
									tabla_reporte_iluminacionpuntos(proyecto.id, reporteiluminacion_id);
									tabla_reporte_iluminacionresultados(proyecto.id, reporteiluminacion_id);
									tabla_reporte_reflexionresultados(proyecto.id, reporteiluminacion_id);
									tabla_reporte_matrizexposicion(proyecto.id, reporteiluminacion_id);
									reporteiluminacion_dashboard(proyecto.id, reporteiluminacion_id);
                                
                                } else {

                                     swal({
                                        title: "Ocurrio un error al intentar insertar los puntos.",
                                        text: ""+dato.msj,
                                        type: "error", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
									 });
									
                                }

                                
                            },
                            beforeSend: function () {

                                $('#botonCargarExcelPuntos').prop('disabled', true);
                                $('#divCargaPuntos').css('display', 'block');
                            },
                            error: function(dato) {
                                
                                // actualiza boton
                                $('#botonCargarExcelPuntos').prop('disabled', false);
                                $('#divCargaPuntos').css('display', 'none');

                                // mensaje
                                swal({
                                    title: "Error al cargar los puntos.",
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



    $('#excelPuntos').change(function () {
        
        if ($(this).val()) {
            
            $('#alertaVerificacion2').css('display', 'block');

        } else {
            $('#alertaVerificacion2').css('display', 'none');
            
        }
    });

});




document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.custom-select').forEach(function(select) {
		
        select.addEventListener('change', function() {
            var numero = this.id.split('_')[2]; 
            var descripcion = '';
            switch (this.value) {
				case '1':
				descripcion = 'En exteriores: distinguir el área de tránsito, desplazarse caminando, vigilancia, movimiento de vehículos.';
				break;
			case '2':
				descripcion = 'En interiores: distinguir el área de tránsito, desplazarse caminando. Vigilancia, movimiento de vehículos.';
				break;
			case '3':
				descripcion = 'En interiores.';
				break;
			case '4':
				descripcion = 'Requerimiento visual simple: inspección visual, recuento de piezas, trabajo en banco y máquina.';
				break;
			case '5':
			descripcion = 'Distinción moderada de detalles: ensamble simple, trabajo medio en banco y máquina, inspección simple, empaque y trabajos de oficina.';
				break;
			case '6':
				descripcion = 'Distinción clara de detalles: maquinado y acabados delicados, ensamble de inspección moderadamente difícil, captura y procesamiento de información, manejo de instrumentos y equipo de laboratorio.';
				break;
			case '7':
				descripcion = 'Distinción fina de detalles: maquinado de precisión, ensamble e inspección de trabajos delicados, manejo de instrumentos y equipo de precisión, manejo de piezas pequeñas.';
				break;
			case '8':
				descripcion = 'Alta exactitud en la distinción de detalles: ensamble, proceso e inspección de piezas pequeñas y complejas, acabado con pulidos finos.';
				break;
			case '9':
				descripcion = 'Alto grado de especialización en la distinción de detalles.';
				break;
			default:
				descripcion = '';
				break;
            }
            var textareaId = 'textarea_tareavisual_' + numero;
            var textarea = document.getElementById(textareaId);
            if (textarea) {
                textarea.value = descripcion;
            } else {
                console.log('No se encontró el textarea con ID:', textareaId);
            }
        });
    });
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
    function(isConfirm) {
        if (isConfirm) {
            // Mostrar mensaje de carga
            swal({
                title: "Generando documento",
                text: 'Espere un momento, el documento se esta documento se esta generando...',
                type: "info",
                showConfirmButton: false,
                allowOutsideClick: false
            });

			url = 'descargarPortadaInformes/' + proyecto.id + '/' + 4;
			instalacion = $('#reporte_instalacion').val();

            $.ajax({
                url: url,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = `Plantilla principal (Iluminación) - ${instalacion}.docx`;
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    // Cerrar mensaje de carga
                    swal.close();

                    $('#btn_descargar_plantilla').prop('disabled', true);
                },
                error: function() {
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
        success: function(dato) {

        
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
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
            swal('Error', 'No se pudieron obtener los datos del informe', 'error');
        }
    });
}
