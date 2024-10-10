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
var reportebei_id = 0;
var agente_id = 22;
var agente_nombre = "BEI";
var opciones_catepp = "";

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

$(document).ready(function()
{
	// Modal cargando
	$('#modal_cargando .modal-title').html('Cargando informe de '+agente_nombre); // Titulo modal
	$('#modal_cargando').modal(); // Abrir modal
	updateClock(); // Ejecutar tiempo de espera

	validarPermisosAsignados(proyecto.id) //Validacion de permisos
	datosgenerales(); // Datos generales
	portadaInfo(); // Portada
	consulta_categoria_epp() //Equipo de proteccion EPP

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


// Inicializamos los campos de las fotos con la libreria
$(document).ready(function()
{
	$('#reportebeiresponsable1documento').dropify({
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

	$('#reportebeiresponsable2documento').dropify({
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
    $('#reportebeiubicacionfoto').dropify({
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

		}, beforeSend: function () {},
		error: function (dato) {
			// alert('Error: '+dato.msj);
            alert('Los permisos no han sido cargado')

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
    reader.onload = function(e) {
        var img = new Image();
        img.onload = function() {
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

var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id){
	try 
	{
		var ruta = "/reportebeitablarevisiones/"+proyecto_id;

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

function obtenerdatos() {
	

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
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
            swal('Error', 'No se pudieron obtener los datos del informe', 'error');
        }
    });
}



//=================================================
// DATOS GENERALES


var datosgenerales_ejecusiones = 0;
function datosgenerales()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportebeidatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
		
			
			reportebei_id = 0;
			if (parseInt(dato.reportebei_id) > 0)
			{
				reportebei_id = parseInt(dato.reportebei_id);
				// $('#reportebei_id').html(reportebei_id);
			}

			if (parseInt(dato.reportebei_concluido) == 1 || parseInt(dato.reportebei_cancelado) == 1)
			{
				botoninforme_estado(1);
			}
			else
			{
				botoninforme_estado(0);
			}


			if (parseInt(dato.recsensorial_tipocliente) == 1) //1= pemex, 0 =  cliente
			{
				$('#reportebei_catsubdireccion_activo').prop('checked', dato.reportebei_portada.reportebei_catsubdireccion_activo);
				$('#reportebei_catsubdireccion_id').val(dato.reportebei_portada.catsubdireccion_id);
				$('#reportebei_catgerencia_activo').prop('checked', dato.reportebei_portada.reportebei_catgerencia_activo);
				$('#reportebei_catgerencia_id').val(dato.reportebei_portada.catgerencia_id);
				$('#reportebei_catactivo_activo').prop('checked', dato.reportebei_portada.reportebei_catactivo_activo);
				$('#reportebei_catactivo_id').val(dato.reportebei_portada.catactivo_id);
				$('#reportebei_catregion_activo').prop('checked', dato.reportebei_portada.reportebei_catregion_activo);
				$('#reportebei_catregion_id').val(dato.reportebei_portada.catregion_id);
			}
			else
			{
				$('#reportebei_catsubdireccion_id').val('');
				$('#reportebei_catgerencia_id').val('');
				$('#reportebei_catactivo_id').val('');
				$('#reportebei_catregion_id').val('');

				$('#reportebei_catsubdireccion_activo').prop('checked', 0);
				$('#reportebei_catgerencia_activo').prop('checked', 0);
				$('#reportebei_catactivo_activo').prop('checked', 0);
				$('#reportebei_catregion_activo').prop('checked', 0);
			}
			
			
			$('#reportebei_instalacion').val(dato.reportebei_portada.reportebei_instalacion);

			$('#reportebei_fecha').val(dato.reportebei_portada.reportebei_fecha);

			// $('#reporte_fecha').val(dato.reporte_portada.reporte_fecha);


			$('#reportebei_mes').val(dato.reportebei_portada.reportebei_mes);

            $('.div_instalacion_nombre').html(dato.reportebei_portada.reportebei_instalacion);
            
			$('#reportebei_introduccion').html(dato.reportebei_introduccion);
			$('#reportebei_objetivogeneral').html(dato.reportebei_objetivogeneral);
			$('#reportebei_objetivoespecifico').html(dato.reportebei_objetivoespecifico);
			$('#reportebei_metodologia_4_1').html(dato.reportebei_metodologia_4_1);
			$('#reportebei_metodologia_4_2').html(dato.reportebei_metodologia_4_2);
			$('#reportebei_metodologia_4_2_1').html(dato.reportebei_metodologia_4_2_1);
			$('#reportebei_metodologia_4_2_2').html(dato.reportebei_metodologia_4_2_2);
			$('#reportebei_metodologia_4_2_3').html(dato.reportebei_metodologia_4_2_3);
			$('#reportebei_metodologia_4_2_4').html(dato.reportebei_metodologia_4_2_4);

			$('#reportebei_ubicacioninstalacion').html(dato.reportebei_ubicacioninstalacion.ubicacion);
			if (dato.reportebei_ubicacioninstalacion.ubicacionfoto)
			{
				var archivo = dato.reportebei_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportebeimapaubicacion/'+reportebei_id+'/'+0;
				$('#reportebeiubicacionfoto').dropify().data('dropify').destroy();
				$('#reportebeiubicacionfoto').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reportebeiubicacionfoto').dropify().data('dropify').init();
				$('#reportebeiubicacionfoto').attr('required', false);
				
				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'block');
			}
			else
			{
				$('#reportebeiubicacionfoto').val('');
				$('#reportebeiubicacionfoto').attr('required', true);
				$('#reportebeiubicacionfoto').dropify().data('dropify').resetPreview();
				$('#reportebeiubicacionfoto').dropify().data('dropify').clearElement();

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'none');
			}

			$('#reportebei_procesoinstalacion').html(dato.reportebei_procesoinstalacion);
			$('#reportebei_actividadprincipal').html(dato.reportebei_actividadprincipal);
			$('#reportebei_criterioseleccion').html(dato.reportebei_criterioseleccion);
			$('#reportebei_conclusion').html(dato.reportebei_conclusion);

			if (dato.reportebei_responsablesinforme.reportebei_responsable1)
			{
				// Responsable 1
				$('#reportebei_responsable1').val(dato.reportebei_responsablesinforme.reportebei_responsable1);
				$('#reportebei_responsable1cargo').val(dato.reportebei_responsablesinforme.reportebei_responsable1cargo);
				$('#reportebei_responsable1documento').val('');
				$('#reportebeiresponsable1documento').val('');

				var archivo = dato.reportebei_responsablesinforme.reportebei_responsable1documento;
				var tipo1 = dato.reportebei_responsablesinforme.tipo1
				var id1 = dato.reportebei_responsablesinforme.registro_id == 0 ? dato.reportebei_responsablesinforme.recsensorial_id : dato.reportebei_responsablesinforme.registro_id 
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportebeiresponsabledocumento/'+ id1 +'/'+ tipo1 +'/'+0;
				$('#reportebeiresponsable1documento').dropify().data('dropify').destroy();
				$('#reportebeiresponsable1documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reportebeiresponsable1documento').dropify().data('dropify').init();
				$('#reportebeiresponsable1documento').attr('required', false);
				
				// Responsable 2
				$('#reportebei_responsable2').val(dato.reportebei_responsablesinforme.reportebei_responsable2);
				$('#reportebei_responsable2cargo').val(dato.reportebei_responsablesinforme.reportebei_responsable2cargo);
				$('#reportebei_responsable2documento').val('');
				$('#reportebeiresponsable2documento').val('');

				var archivo = dato.reportebei_responsablesinforme.reportebei_responsable2documento;
				var tipo2 = dato.reportebei_responsablesinforme.tipo2
				var id2 = dato.reportebei_responsablesinforme.registro_id == 0 ? dato.reportebei_responsablesinforme.recsensorial_id : dato.reportebei_responsablesinforme.registro_id 
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportebeiresponsabledocumento/'+ id2 +'/'+ tipo2 +'/'+0;
				$('#reportebeiresponsable2documento').dropify().data('dropify').destroy();
				$('#reportebeiresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reportebeiresponsable2documento').dropify().data('dropify').init();
				$('#reportebeiresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#reportebei_carpetadocumentoshistorial').val('reportes/proyecto/'+dato.reportebei_responsablesinforme.proyecto_id+'/'+agente_nombre+'/'+dato.reportebei_responsablesinforme.registro_id+'/responsables informe');
				if (parseInt(dato.reportebei_responsablesinforme.proyecto_id) == parseInt(proyecto.id))
				{
					$('#boton_descargarresponsabledoc1').css('display', 'block');
					$('#boton_descargarresponsabledoc2').css('display', 'block');
					$('#reportebei_carpetadocumentoshistorial').val('');
				}
			}
			else
			{
				$('#reportebei_responsable1').val('');
				$('#reportebei_responsable1cargo').val('');
				$('#reportebei_responsable1documento').val('');
				$('#reportebeiresponsable1documento').val('');
				$('#reportebeiresponsable1documento').attr('required', true);
				$('#reportebeiresponsable1documento').dropify().data('dropify').resetPreview();
				$('#reportebeiresponsable1documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				
				$('#reportebei_responsable2').val('');
				$('#reportebei_responsable2cargo').val('');
				$('#reportebei_responsable2documento').val('');
				$('#reportebeiresponsable2documento').val('');
				$('#reportebeiresponsable2documento').attr('required', true);
				$('#reportebeiresponsable2documento').dropify().data('dropify').resetPreview();
				$('#reportebeiresponsable2documento').dropify().data('dropify').clearElement();
				$('#boton_descargarresponsabledoc2').css('display', 'none');

				// Carpeta ubicacion documentos historial
				$('#reportebei_carpetadocumentoshistorial').val('');
			}

			// ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reportebei_portada_guardado);
			menureporte_estado("menureporte_1", dato.reportebei_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reportebei_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reportebei_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4_1", dato.reportebei_metodologia_4_1_guardado);
			menureporte_estado("menureporte_4_2", dato.reportebei_metodologia_4_2_guardado);
			
			menureporte_estado("menureporte_5_1", dato.reportebei_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_5_2", dato.reportebei_procesoinstalacion_guardado);
			menureporte_estado("menureporte_6_1", dato.reportebei_criterioseleccion_guardado);
			menureporte_estado("menureporte_8", dato.reportebei_conclusion_guardado);
			menureporte_estado("menureporte_10", dato.reportebei_responsablesinforme_guardado);
			menureporte_estado("menureporte_11_1", dato.reportebei_memoriafotografica_guardado);
			$('#memoriafotografica_total').html(dato.reportebei_memoriafotografica_guardado);
			

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
	
		error: function(dato)
		{
			if (datosgenerales_ejecusiones == 0)
			{
				datosgenerales();
				datosgenerales_ejecusiones += 1;
			}

			reportebei_id = 0;
			
			$('#reportebei_instalacion').val('Error al cargar los datos');
			$('#reportebei_fecha').val('Error al cargar los datos');
			$('#reportebei_mes').val('Error al cargar los datos');
			$('#reportebei_introduccion').html('Error al cargar los datos');
			$('#reportebei_objetivogeneral').html('Error al cargar los datos');
			$('#reportebei_objetivoespecifico').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_1').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_2').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_2_1').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_2_2').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_2_3').html('Error al cargar los datos');
			$('#reportebei_metodologia_4_2_4').html('Error al cargar los datos');
			$('#reportebei_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reportebei_procesoinstalacion').html('Error al cargar los datos');
			$('#reportebei_criterioseleccion').html('Error al cargar los datos');
			$('#reportebei_conclusion').html('Error al cargar los datos');

			return false;
		}
	});//Fin ajax
}

//=================================================
// DEFINICION

$(document).ready(function()
{

	obtenerdatos()
	
	setTimeout(function()
	{
		tabla_reporte_definiciones(proyecto.id, agente_nombre, reportebei_id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id, agente_nombre, reportebei_id)
{
	try 
	{
		var ruta = "/reportebeitabladefiniciones/"+proyecto_id+"/"+agente_nombre+"/"+reportebei_id;

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
								tabla_reporte_definiciones(proyecto_id, agente_nombre, reportebei_id);
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
		tabla_reporte_definiciones(proyecto_id, agente_nombre, reportebei_id);
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
							url: "/reportebeidefinicioneliminar/"+row.data().id,
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
					url: '/reportebei',
					data: {
						opcion: 2,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reportebei_id = dato.reportebei_id;

						// Actualizar tabla
						tabla_reporte_definiciones(proyecto.id, agente_nombre, reportebei_id);

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
					url: '/reportebei',
					data: {
						opcion: 0,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						catregion_id: $("#reportebei_catregion_id").val(),
						catsubdireccion_id: $("#reportebei_catsubdireccion_id").val(),
						catgerencia_id: $("#reportebei_catgerencia_id").val(),
						catactivo_id: $("#reportebei_catactivo_id").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reportebei_id = dato.reportebei_id;

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
					url: '/reportebei',
					data: {
						opcion: 1,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
					url: '/reportebei',
					data: {
						opcion: 3,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
					url: '/reportebei',
					data: {
						opcion: 4,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
					url: '/reportebei',
					data: {
						opcion: 5,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
					url: '/reportebei',
					data: {
						opcion: 6,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
// UBICACION


var ubicacionmapa = '';

function redimencionar_mapaubicacion()
{
	// Bloquear boton
	$('#botonguardar_reporte_ubicacion').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('reportebeiubicacionfoto').files;
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
					url: '/reportebei',
					data: {
						opcion: 11,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						ubicacionmapa: ubicacionmapa
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

						menureporte_estado("menureporte_5_1", 1);

						tabla_reporte_revisiones(proyecto.id);

						$('#reportebeiubicacionfoto').val('');
						$('#reportebeiubicacionfoto').attr('required', false);
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
	window.open('/reportebeimapaubicacion/'+reportebei_id+'/'+1);
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
					url: '/reportebei',
					data: {
						opcion: 12,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
		tabla_reporte_categorias(proyecto.id, reportebei_id);
	}, 5500);
});


var datatable_reportecategorias = null;
function tabla_reporte_categorias(proyecto_id, reportebei_id)
{
	try 
	{
		var ruta = "/reportebeicategorias/"+proyecto_id+"/"+reportebei_id+"/"+areas_poe;

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

						return json.data;
					},
					error: function (xhr, error, code)
					{
						console.log('error en datatable_reportecategorias');
						if (numeroejecucion <= 1)
						{
							tabla_reporte_categorias(proyecto_id, reportebei_id);
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
		tabla_reporte_categorias(proyecto_id, reportebei_id);
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
		text: "La categoría: "+row.data().reportebeicategoria_nombre,
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
				text: "La categoría: "+row.data().reportebeicategoria_nombre,
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
						url: "/reportebeicategoriaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_categorias(proyecto.id, reportebei_id);
							tabla_reporte_areas(proyecto.id, reportebei_id);
							

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
					url: '/reportebei',
					data: {
						opcion: 13,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						categorias_poe: categorias_poe
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

						tabla_reporte_revisiones(proyecto.id);

						// Actualizar tabla
						tabla_reporte_categorias(proyecto.id, reportebei_id);
						tabla_reporte_areas(proyecto.id, reportebei_id);

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

$(document).ready(function()
{
	setTimeout(function()
	{
		console.log(reportebei_id)
		tabla_reporte_areas(proyecto.id, reportebei_id);
	}, 5000);
});


var datatable_reporteareas = null;
function tabla_reporte_areas(proyecto_id, reportebei_id)
{
	try 
	{
		var ruta = "/reportebeiareas/"+proyecto_id+"/"+reportebei_id+"/"+ 1;

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
						if (parseInt(json.data.length) != 0 && parseInt(json.total_singuardar) == 0)
						{
							menureporte_estado("menureporte_5_5", 1);
							menureporte_estado("menureporte_6_1", 1);
							
						} else {
							menureporte_estado("menureporte_5_5", 0);
							menureporte_estado("menureporte_6_1", 0);
						
						}


						if (parseInt(areas_poe) == 1)
						{
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						// Tablas
						tabla_reporte_5_5(json.tabla_5_5);
						tabla_reporte_6_1(json.tabla_6_1);
					


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
							tabla_reporte_areas(proyecto_id, reportebei_id)
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
						"defaultContent": "NA"
					},
					{
						"data": "reportearea_instalacion",
						"defaultContent": "NA"
					},
					{
						"data": "reportearea_nombre",
						"defaultContent": "NA"
					},
					{
						"data": "reportecategoria_nombre_texto",
						"defaultContent": "NA",
					},
					{
						"data": "reporteareacategoria_total",
						"defaultContent": "NA"
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
				"rowsGroup": [1, 0, 2, 5, 6], //agrupar filas
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
		tabla_reporte_areas(proyecto_id, reportebei_id);
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
	$('#reportebeiarea_porcientooperacion').val(row.data().reportebeiarea_porcientooperacion);
	
	//Validamos el check de que si aplica o no aplica
	if (row.data().aplica_bei == 1) {
		$('#aplica_bei_si').prop('checked', true);
		$('#aplica_bei_no').prop('checked', false);

	} else {

		$('#aplica_bei_si').prop('checked', false);
		$('#aplica_bei_no').prop('checked', true);
		
	}

	//Validamos el area poe para ver si aplica o no aplica
	if (areas_poe == 1)
	{
		$('#reportearea_instalacion').attr('required', false);
		$('#reportearea_instalacion').attr('disabled', true);

		$('#reportearea_nombre').attr('required', false);
		$('#reportearea_nombre').attr('disabled', true);

		$('#reportearea_orden').attr('required', false);
		$('#reportearea_orden').attr('disabled', true);

		
	}
	else
	{
		$('#reportearea_instalacion').attr('disabled', false);
		$('#reportearea_instalacion').attr('required', true);

		$('#reportearea_nombre').attr('disabled', false);
		$('#reportearea_nombre').attr('required', true);

		$('#reportearea_orden').attr('disabled', false);
		$('#reportearea_orden').attr('required', true);

		
	}


	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportebeiareascategorias/"+proyecto.id+"/"+reportebei_id+"/"+row.data().id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			
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
						url: "/reportebeiareaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_areas(proyecto.id, reportebei_id);
						


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

function validarCampos() {
	//Validar categorias seleccionadas
	const checkboxes = document.querySelectorAll('#tabla_areacategorias input[type="checkbox"]');
	const algunoMarcado = Array.from(checkboxes).some(checkbox => checkbox.checked);
	const aplica = document.getElementById('aplica_bei_no');

	if (aplica.checked) {

		$('.infoAdicionalArea').prop('required', false);
		return true;

	} else {

		$('.infoAdicionalArea').prop('required', true);
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

		return true;

	}
}

$("#botonguardar_modal_area").click(function (e) {

	datatable_areacategoria.search("").draw();
	e.preventDefault();

	if (validarCampos()) {

		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida) {
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Área: " + $("#reportearea_nombre").val(),
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
							url: '/reportebei',
							data: {
								opcion: 14,
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reportebei_id: reportebei_id,
								areas_poe: 1,
							},
							resetForm: false,
							success: function (dato) {
								// Actualizar ID reporte						
								reportebei_id = dato.reportebei_id;

								tabla_reporte_revisiones(proyecto.id);

								// Actualizar tabla
								tabla_reporte_areas(proyecto.id, reportebei_id);
						

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
		} else {
			swal({
				title: "Por favor asegurece de rellenar todos los campos",
				text: "",
				type: "info", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 2500,
				showConfirmButton: false
			});

		}

	}
});

//================================================================
// TABLA AREAS CATEGORIAS

var datatable_areacategoria = null;
function tabla_areacategorias(tbody) {
	if (datatable_areacategoria != null) {
		datatable_areacategoria.destroy();
	}

	$('#tabla_areacategorias tbody').html(tbody);

	datatable_areacategoria = $('#tabla_areacategorias').DataTable({
		"scrollY": "185px",
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

//================================================================
// TABLA AREAS ADICIONALES DE INFORMACION


var datatable_reporte_5_5 = null;
function tabla_reporte_5_5(tbody) {
	if (datatable_reporte_5_5 != null) {
		datatable_reporte_5_5.destroy();
	}

	$('#tabla_reporte_5_5 tbody').html(tbody);

	datatable_reporte_5_5 = $('#tabla_reporte_5_5').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [1], //agrupar filas
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

//============================================
// EQUIPO DE PROTECCION PERSONAL (EPP)

$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_epp(proyecto.id, reportebei_id);
	}, 7000);
});


var datatable_epp = null;
function tabla_reporte_epp(proyecto_id, reportebei_id)
{
	try 
	{
		var ruta = "/reportebeiepptabla/"+proyecto_id+"/"+reportebei_id;

		if (datatable_epp != null)
		{
			datatable_epp.clear().draw();
			datatable_epp.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_epp = $('#tabla_reporte_epp').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_5_4", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_epp '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_epp(proyecto_id, reportebei_id)
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
						data: "reportebeiepp_partecuerpo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportebeiepp_equipo",
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
		datatable_epp.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_epp(proyecto_id, reportebei_id);
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


	$("#tabla_lista_epp_bei tbody").html('');
	$("#tabla_lista_epp_bei tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" id="reportebeiepp_partecuerpo" name="reportebeiepp_partecuerpo" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" id="reportebeiepp_equipo" name="reportebeiepp_equipo" required></select></td>' +
		'</tr>');
	$('#modal_reporte_epp').modal({ backdrop: false });
});


$("#tabla_lista_epp_bei tbody").on("change", ".regionAnatomica", function () {

	var valorSeleccionado = $(this).val();
	var fila = $(this).closest("tr");
	var selectClaveEppp = fila.find("select[name='reportebeiepp_equipo']"); // Encontrar el Select en la misma fila

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

	$("#tabla_lista_epp_bei tbody").html('');
	$("#tabla_lista_epp_bei tbody").append('<tr>' +
		'<td style="width:250px"><select class="custom-select form-control regionAnatomica" id="reportebeiepp_partecuerpo" name="reportebeiepp_partecuerpo" required>' + opciones_catepp + '</select></td>' +
		'<td style="width:400px"><select class="custom-select form-control claveyEpp" id="reportebeiepp_equipo" name="reportebeiepp_equipo" required></select></td>' +
		'</tr>');

	// Llenar campos
	// Campos Hidden
	$('#reporteepp_id').val(row.data().id);


	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/recsensorialClaveEppruido/" + row.data().reportebeiepp_partecuerpo,
		data: {},
		cache: false,
		success: function (dato) {
			$('#reportebeiepp_equipo').html(dato.opciones);
			setTimeout(() => {
				$('#reportebeiepp_partecuerpo').val(row.data().reportebeiepp_partecuerpo);
				$('#reportebeiepp_equipo').val(row.data().reportebeiepp_equipo);

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
		text: "E.P.P.: " + row.data().reportebeiepp_partecuerpo,
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
					text: "E.P.P.: " + row.data().reportebeiepp_partecuerpo,
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
								url: "/reportebeieppeliminar/" + row.data().id,
								data: {},
								cache: false,
								success: function (dato) {
									// Actualizar tabla
									tabla_reporte_epp(proyecto.id, reportebei_id);

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
						url: '/reportebei',
						data: {
							opcion: 15,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportebei_id: reportebei_id,
							reporte_instalacion: $("#reporte_instalacion").val(),

						},
						resetForm: false,
						success: function (dato) {
							// Actualizar ID reporte						
							reportebei_id = dato.reportebei_id;

							tabla_reporte_epp(proyecto.id, reportebei_id);

							// Actualizar tabla
							// tabla_reporte_areas(proyecto.id, reportebei_id);

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
					url: '/reportebei',
					data: {
						opcion: 20,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						reportebei_instalacion: $("#reportebei_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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


//=================================================
// REPONSABLES

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
					url: '/reportebei',
					data: {
						opcion: 40,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						reportebei_instalacion: $("#reportebei_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

						menureporte_estado("menureporte_10", 1);

						tabla_reporte_revisiones(proyecto.id);

						$('#boton_descargarresponsabledoc1').css('display', 'block');
						$('#boton_descargarresponsabledoc2').css('display', 'block');

						// Carpeta ubicacion documentos historial
						$('#reportebei_carpetadocumentoshistorial').val('');

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
	window.open('/reportebeiresponsabledocumento/'+reportebei_id+'/'+1+'/'+1);
});


$("#boton_descargarresponsabledoc2").click(function()
{
	window.open('/reportebeiresponsabledocumento/'+reportebei_id+'/'+2+'/'+1);
});


//=================================================
// RECOMENDACIONES


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_recomendaciones(proyecto.id, reportebei_id, agente_nombre);
	}, 6500);
});


var datatable_recomendaciones = null;
function tabla_reporte_recomendaciones(proyecto_id, reportebei_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportebeitablarecomendaciones/"+proyecto_id+"/"+reportebei_id+"/"+agente_nombre;

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
							tabla_reporte_recomendaciones(proyecto_id, reportebei_id, agente_nombre);
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
		tabla_reporte_recomendaciones(proyecto_id, reportebei_id, agente_nombre);
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
						url: '/reportebei',
						data: {
							opcion: 30,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportebei_id: reportebei_id,
							reportebei_instalacion: $("#reportebei_instalacion").val(),
						},
						resetForm: false,
						success: function(dato)
						{
							// Actualizar ID reporte						
							reportebei_id = dato.reportebei_id;

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
// INFORME DE RESULTADOS LABORATORIO
	

$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_informeresultados(proyecto.id, reportebei_id, agente_nombre);
	}, 10500);
});


var datatable_informeresultados = null;
function tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportebeitablainformeresultados/"+proyecto_id+"/"+reportebei_id+"/"+agente_nombre;

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
							tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre);
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
		tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre);
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
					url: '/reportebei',
					data: {
						opcion: 55,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						catactivo_id: $("#reportebei_catactivo_id").val(),
						reportebei_instalacion: $("#reportebei_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

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
// INFORME DE RESULTADOS LABORATORIO
	

$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_informeresultados(proyecto.id, reportebei_id, agente_nombre);
	}, 10500);
});


var datatable_informeresultados = null;
function tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportebeitablainformeresultados/"+proyecto_id+"/"+reportebei_id+"/"+agente_nombre;

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
						menureporte_estado("menureporte_11_3", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_informeresultados '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre);
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
		tabla_reporte_informeresultados(proyecto_id, reportebei_id, agente_nombre);
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
					url: '/reportebei',
					data: {
						opcion: 55,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reportebei_id: reportebei_id,
						reportebei_instalacion: $("#reportebei_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reportebei_id = dato.reportebei_id;

						// Actualiza menu
						menureporte_estado("menureporte_11_3", dato.total);

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
// EQUIPO UTILIZADO


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_equipoutilizado(proyecto.id, reportebei_id, agente_nombre);
	},5500);
});


var datatable_equipoutilizado = null;
function tabla_reporte_equipoutilizado(proyecto_id, reportebei_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportebeitablaequipoutilizado/"+proyecto_id+"/"+reportebei_id+"/"+agente_nombre;

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
							menureporte_estado("menureporte_11_2", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_equipoutilizado '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_equipoutilizado(proyecto_id, reportebei_id, agente_nombre);
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
		tabla_reporte_equipoutilizado(proyecto_id, reportebei_id, agente_nombre);
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


$("#botoncerrar_modalvisor_reportebei").click(function()
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
	$('.reportebei_equipoutilizadocheckbox').each(function()
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
						url: '/reportebei',
						data: {
							opcion: 50,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reportebei_id: reportebei_id,
							reportebei_instalacion: $("#reportebei_instalacion").val(),
						},
						resetForm: false,
						success: function(dato)
						{
							// Actualizar ID reporte							
							reportebei_id = dato.reportebei_id;

							menureporte_estado("menureporte_11_2", 1);


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