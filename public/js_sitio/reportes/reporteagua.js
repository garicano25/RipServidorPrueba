
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
	var fromTop = $(this).scrollTop() + topMenuHeight - 100;
	// var fromTop = $(this).scrollTop() + topMenuHeight;

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
var reporteregistro_id = 0;
// var proveedor_id = <?php echo json_encode($proveedor_id); ?>; // Variable declarada en el Blade
var agente_id = 9;
var agente_nombre = "Agua";
var ruta_storage_guardar = '/reporteagua';
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


	datosgenerales(); // Cargar datos
	portadaInfo() /// Portada Info


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
		url: "/reporteaguadatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			reporteregistro_id = 0;
			if (parseInt(dato.reporteregistro_id) > 0)
			{
				reporteregistro_id = parseInt(dato.reporteregistro_id);
				// $('#reporteregistro_id').html(reporteregistro_id);
			}


			if (parseInt(dato.reporte_concluido) == 1 || parseInt(dato.reporte_cancelado) == 1)
			{
				botoninforme_estado(1);
			}
			else
			{
				botoninforme_estado(0);
			}


			//Bloquear botones nuevas categorias y nuevas areas si este proyecto tiene POE general
			//-------------------------------------------------------------------------------------


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
			else
			{
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
			$('#reporte_introduccion2').html(dato.reporte_introduccion2);
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_objetivoespecifico2').html(dato.reporte_objetivoespecifico2);
			$('#reporte_metodologia_4_1').html(dato.reporte_metodologia_4_1);
			$('#reporte_metodologia_4_2').html(dato.reporte_metodologia_4_2);
			$('#reporte_metodologia_4_3').html(dato.reporte_metodologia_4_3);
			$('#reporte_metodologia_4_32').html(dato.reporte_metodologia_4_32);


			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			if (dato.reporte_ubicacioninstalacion.ubicacionfoto)
			{
				var archivo = dato.reporte_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteaguamapaubicacion/'+reporteregistro_id+'/'+0;
				$('#reporteubicacionfoto').dropify().data('dropify').destroy();
				$('#reporteubicacionfoto').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteubicacionfoto').dropify().data('dropify').init();
				$('#reporteubicacionfoto').attr('required', false);
				
				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'block');
			}
			else
			{
				$('#reporteubicacionfoto').val('');
				$('#reporteubicacionfoto').attr('required', true);
				$('#reporteubicacionfoto').dropify().data('dropify').resetPreview();
				$('#reporteubicacionfoto').dropify().data('dropify').clearElement();

				// Boton descargar mapa ubicacion
				$('#boton_descargarmapaubicacion').css('display', 'none');
			}


			$('#reporte_procesoinstalacion').html(dato.reporte_procesoinstalacion);
			$('#reporte_actividadprincipal').html(dato.reporte_actividadprincipal);
			$('#reporte_procesoelaboracion').html(dato.reporte_procesoelaboracion);
			$('#reporte_conclusion').html(dato.reporte_conclusion);
			$('#reporte_conclusion2').html(dato.reporte_conclusion2);


			if (dato.reporte_responsablesinforme.responsable1)
			{
				// Responsable 1
				$('#reporte_responsable1').val(dato.reporte_responsablesinforme.responsable1);
				$('#reporte_responsable1cargo').val(dato.reporte_responsablesinforme.responsable1cargo);
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteaguaresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+1+'/'+0;
				$('#reporteresponsable1documento').dropify().data('dropify').destroy();
				$('#reporteresponsable1documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteresponsable1documento').dropify().data('dropify').init();
				$('#reporteresponsable1documento').attr('required', false);
				
				// Responsable 2
				$('#reporte_responsable2').val(dato.reporte_responsablesinforme.responsable2);
				$('#reporte_responsable2cargo').val(dato.reporte_responsablesinforme.responsable2cargo);
				$('#reporte_responsable2_documentobase64').val('');
				$('#reporteresponsable2documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable2documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteaguaresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+2+'/'+0;
				$('#reporteresponsable2documento').dropify().data('dropify').destroy();
				$('#reporteresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteresponsable2documento').dropify().data('dropify').init();
				$('#reporteresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#responsablesinforme_carpetadocumentoshistorial').val('reportes/proyecto/'+dato.reporte_responsablesinforme.proyecto_id+'/'+agente_nombre+'/'+dato.reporte_responsablesinforme.registro_id+'/responsables informe');
				if (parseInt(dato.reporte_responsablesinforme.proyecto_id) == parseInt(proyecto.id))
				{
					$('#boton_descargarresponsabledoc1').css('display', 'block');
					$('#boton_descargarresponsabledoc2').css('display', 'block');
					$('#responsablesinforme_carpetadocumentoshistorial').val('');
				}
			}
			else
			{
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

			$('#reporte_memoriafotografica_lista').html(dato.reporte_memoriafotografica_lista);

			// // ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reporte_portada_guardado);
			menureporte_estado("menureporte_1", dato.reporte_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reporte_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reporte_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4_1", dato.reporte_metodologia_4_1_guardado);
			menureporte_estado("menureporte_4_2", dato.reporte_metodologia_4_2_guardado);
			menureporte_estado("menureporte_4_3", dato.reporte_metodologia_4_3_guardado);			
			menureporte_estado("menureporte_4_3_1", dato.reporte_metodologia_4_3_guardado);			
			menureporte_estado("menureporte_5_1", dato.reporte_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_5_2", dato.reporte_procesoinstalacion_guardado);
			menureporte_estado("menureporte_5_3", dato.reporte_procesoelaboracion_guardado);
			menureporte_estado("menureporte_5_4", 1); //Tabla riesgos biologicos
			menureporte_estado("menureporte_8", dato.reporte_conclusion_guardado);
			menureporte_estado("menureporte_11", dato.reporte_responsablesinforme_guardado);
			menureporte_estado("menureporte_12_1", dato.reporte_memoriafotografica_guardado);
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


			reporteregistro_id = 0;
			

			$('#reporte_instalacion').val('Error al cargar los datos');
			$('#reporte_fecha').val('Error al cargar los datos');
			$('#reporte_mes').val('Error al cargar los datos');

			$('#reporte_introduccion').html('Error al cargar los datos');
			$('#reporte_introduccion2').html('Error al cargar los datos');
			$('#reporte_objetivogeneral').html('Error al cargar los datos');
			$('#reporte_objetivoespecifico').html('Error al cargar los datos');
			$('#reporte_objetivoespecifico2').html('Error al cargar los datos');
			$('#reporte_metodologia_4_1').html('Error al cargar los datos');
			$('#reporte_metodologia_4_2').html('Error al cargar los datos');
			$('#reporte_metodologia_4_3').html('Error al cargar los datos');
			$('#reporte_metodologia_4_32').html('Error al cargar los datos');
			$('#reporte_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reporte_procesoinstalacion').html('Error al cargar los datos');
			$('#reporte_actividadprincipal').html('Error al cargar los datos');
			$('#reporte_procesoelaboracion').html('Error al cargar los datos');
			$('#reporte_conclusion').html('Error al cargar los datos');
			$('#reporte_conclusion2').html('Error al cargar los datos');

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

function botoninforme_estado(boton_estado)
{
	if (parseInt(boton_estado) == 1)
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
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 0,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_0", 1);

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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_1", 1);

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
// DEFINICIONES


$(document).ready(function()
{

	obtenerdatos()
	
	setTimeout(function()
	{
		tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id);
	}, 2000);
});


var datatable_reportedefiniciones = null;
function tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguatabladefiniciones/"+proyecto_id+"/"+agente_nombre+"/"+reporteregistro_id;

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
						"className": 'justificado',
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
		datatable_reportedefiniciones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id);
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
		$('#modal_reporte_definicion .modal-title').html('Definición');

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
							url: "/reporteaguadefinicioneliminar/"+row.data().id,
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						// Actualizar tabla
						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id);


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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_3_1", 1);

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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_3_2", 1);

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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_4_1", 1);

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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_4_2", 1);

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
// METODOLOGÍA PUNTO 4.3


$("#botonguardar_reporte_metodologia_4_3").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Metodológía punto 4.3",
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
				$('#form_reporte_metodologia_4_3').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 7,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val(),
						reporte_instalacion: $("#reporte_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_4_3", 1);
						menureporte_estado("menureporte_4_3_1", 1);

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
						$('#botonguardar_reporte_metodologia_4_3').html('Guardar metodología punto 4.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_3').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4_3').html('Guardando metodología punto 4.3 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4_3').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4_3').html('Guardar metodología punto 4.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4_3').attr('disabled', false);

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
	$('#reporteubicacionfoto').dropify({
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
	var filesToUpload = document.getElementById('reporteubicacionfoto').files;
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
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 8,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val(),
						reporte_instalacion: $("#reporte_instalacion").val(),
						ubicacionmapa: ubicacionmapa
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_5_1", 1);

						$('#reporteubicacionfoto').val('');
						$('#reporteubicacionfoto').attr('required', false);
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
	window.open('/reporteaguamapaubicacion/'+reporteregistro_id+'/'+1);
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
			text: "Proceso y actividad principal de la instalación",
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_5_2", 1);

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
// PROCESO INSTALACIÓN


$("#botonguardar_reporte_procesoelaboracion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Proceso de elaboración del producto terminado en la instalación",
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
				$('#form_reporte_procesoelaboracion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 10,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val(),
						reporte_instalacion: $("#reporte_instalacion").val(),
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_5_3", 1);

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
						$('#botonguardar_reporte_procesoelaboracion').html('Guardar proceso elaboración <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_procesoelaboracion').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_procesoelaboracion').html('Guardando proceso elaboración <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_procesoelaboracion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_procesoelaboracion').html('Guardar proceso elaboración <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_procesoelaboracion').attr('disabled', false);

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
		tabla_reporte_categorias(proyecto.id, reporteregistro_id);
	}, 2500);
});


var datatable_reportecategorias = null;
function tabla_reporte_categorias(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguacategorias/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

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
						// if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						// {
						// 	menureporte_estado("menureporte_5_4", 1);
						// }
						// else
						// {
						// 	menureporte_estado("menureporte_5_4", 0);
						// }

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						console.log('error en datatable_reportecategorias');
						if (numeroejecucion <= 1)
						{
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
						"data": "reporteaguacategoria_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reporteaguacategoria_total",
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
				"order": [[ 0, "DESC" ]],
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
		datatable_reportecategorias.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_categorias(proyecto_id, reporteregistro_id);
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


$('#tabla_reporte_categoria tbody').on('click', 'td>button.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);

	$('#form_modal_categoria').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reportecategoria_id').val(row.data().id);

	// Llenar campos
	$('#reporteaguacategoria_nombre').val(row.data().reporteaguacategoria_nombre);
	$('#reporteaguacategoria_total').val(row.data().reporteaguacategoria_total);

	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html('Categoría');

	// mostrar modal
	$('#modal_reporte_categoria').modal({backdrop:false});
});


$('#tabla_reporte_categoria tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reportecategorias.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "La categoría: "+row.data().reporteaguacategoria_nombre,
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
				text: "La categoría: "+row.data().reporteaguacategoria_nombre,
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
						url: "/reporteaguacategoriaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);

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
			text: "Categoría: "+$("#reporteaguacategoria_nombre").val(),
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						// Actualizar tabla
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);						

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


var selectareas = '';


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_areas(proyecto.id, reporteregistro_id);
	}, 3500);
});


var datatable_reporteareas = null;
function tabla_reporte_areas(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguaareas/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

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
							menureporte_estado("menureporte_6_1", 1);
						}
						else
						{
							menureporte_estado("menureporte_6_1", 0);
						}

						// Tablas
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
						"data": "reporteaguaarea_instalacion",
						"defaultContent": "-"
					},
					{
						"data": "area_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reporteaguaarea_porcientooperacion",
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
				"rowsGroup": [1, 3], //agrupar filas
				"order": [[ 0, "DESC" ]],
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
		datatable_reporteareas.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_areas(proyecto_id, reporteregistro_id);
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
});


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
	$('#reporteaguaarea_instalacion').val(row.data().reporteaguaarea_instalacion);
	$('#reporteaguaarea_nombre').val(row.data().reporteaguaarea_nombre);
	$('#reporteaguaarea_numorden').val(row.data().reporteaguaarea_numorden);	
	$('#reporteaguaarea_porcientooperacion').val(row.data().reporteaguaarea_porcientooperacion);


	if (areas_poe == 1)
	{
		$('#reporteaguaarea_instalacion').attr('required', false);
		$('#reporteaguaarea_instalacion').attr('disabled', true);

		$('#reporteaguaarea_nombre').attr('required', false);
		$('#reporteaguaarea_nombre').attr('disabled', true);

		$('#reporteaguaarea_numorden').attr('required', false);
		$('#reporteaguaarea_numorden').attr('disabled', true);
	}
	else
	{
		$('#reporteaguaarea_instalacion').attr('disabled', false);
		$('#reporteaguaarea_instalacion').attr('required', true);

		$('#reporteaguaarea_nombre').attr('disabled', false);
		$('#reporteaguaarea_nombre').attr('required', true);

		$('#reporteaguaarea_numorden').attr('disabled', false);
		$('#reporteaguaarea_numorden').attr('required', true);
	}


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html(row.data().reporteaguaarea_nombre);


	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});
});


$('#tabla_reporte_area tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporteareas.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El área: "+row.data().reporteaguaarea_nombre,
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
				text: "El área: "+row.data().reporteaguaarea_nombre,
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
						url: "/reporteaguaareaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_areas(proyecto.id, reporteregistro_id);

							tabla_reporte_resultados(proyecto.id, reporteregistro_id);

							tabla_reporte_matriz(proyecto.id, reporteregistro_id);

							reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");


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
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Área: "+$("#reporteaguaarea_nombre").val(),
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
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 12,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val(),
						reporte_instalacion: $("#reporte_instalacion").val(),
						areas_poe: areas_poe,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;


						// Actualizar tabla
						tabla_reporte_areas(proyecto.id, reporteregistro_id);

						tabla_reporte_resultados(proyecto.id, reporteregistro_id);

						tabla_reporte_matriz(proyecto.id, reporteregistro_id);

						reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");


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
// TABLA DE RESULTADOS


var selectcategorias = '';


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_resultados(proyecto.id, reporteregistro_id);
	}, 4500);
});


var datatable_reporte_resultados = null;
function tabla_reporte_resultados(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguaevaluaciontabla/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

		if (datatable_reporte_resultados != null)
		{
			datatable_reporte_resultados.clear().draw();
			datatable_reporte_resultados.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporte_resultados = $('#tabla_reporte_resultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_5_5", parseInt(json.total));
						menureporte_estado("menureporte_6_2", parseInt(json.total));
						menureporte_estado("menureporte_7_1", parseInt(json.total));
						menureporte_estado("menureporte_7_2", parseInt(json.total));
						menureporte_estado("menureporte_7_3", parseInt(json.total));

						
						// Tablas
						tabla_reporte_5_5(json.manejo_agua);
						tabla_reporte_6_2(json.metodo_evaluacion);
						tabla_reporte_analisisresultados(json.analisis_resultados);
						tabla_reporte_7_3(json.dispensadores_agua);


						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporte_resultados '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_resultados(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reporteaguaevaluacion_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "catparametroaguacaracteristica_tipo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacion_fecha",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaarea_instalacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacion_ubicacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacion_suministro",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "catparametroaguacaracteristica_caracteristica",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "catparametroaguacaracteristica_unidadmedida",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacionparametros_metodo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacion_totalpersonas",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacionparametros_obtenida",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "catparametroaguacaracteristica_concentracionpermisible",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteaguaevaluacionparametros_resultado",
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
				lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Todos"]],
				rowsGroup: [3, 4, 0, 14, 15, 1, 2, 5, 6, 10], //agrupar filas
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
				rowCallback: function(row, data, index)
				{
					// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);
					// $(row).find('td:eq(7)').css('background', data.resultadoner_color);

					if(data.reporteaguaevaluacionparametros_resultado == "Dentro de norma")
					{
						$(row).find('td:eq(13)').css('background', "#00FF00");
						$(row).find('td:eq(13)').css('color', '#000000');
						$(row).find('td:eq(13)').css('font-weight', 'bold');
					}
					else if(data.reporteaguaevaluacionparametros_resultado == "Fuera de norma")
					{
						$(row).find('td:eq(13)').css('background', "#FF0000");
						$(row).find('td:eq(13)').css('color', '#FFFFFF');
						$(row).find('td:eq(13)').css('font-weight', 'bold');
					}
					else
					{
						$(row).find('td:eq(13)').css('background', "#888888");
						$(row).find('td:eq(13)').css('color', '#FFFFFF');
						$(row).find('td:eq(13)').css('font-weight', 'bold');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_resultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_resultados(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_nuevopuntomedicion").click(function()
{
	$('#form_modal_puntomedicion').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#puntomedicion_id').val(0);


	// Campo select areas
	$('#puntomedicion_reporteaguaarea_id').html(selectareas);


	// Titulo del modal
	$('#modal_reporte_puntomedicion .modal-title').html('Punto de medición');


	// mostrar modal
	$('#modal_reporte_puntomedicion').modal({backdrop:false});


	// Vaciar tabla parametros
	tabla_evaluacion_parametros(null);


	// Consultar categorias
	aguaevaluacion_categorias(proyecto.id, reporteregistro_id, 0);
});


function aguaevaluacion_parametos(reporteaguaevaluacion_id, reporteaguaevaluacion_tipo, proveedor_id)
{
	if (reporteaguaevaluacion_tipo)
	{
		// Consultar categorias
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/reporteaguaevaluacionparametros/"+reporteaguaevaluacion_id+"/"+reporteaguaevaluacion_tipo+"/"+proveedor_id,
			data:{},
			cache: false,
			success:function(dato)
			{
				tabla_evaluacion_parametros(dato.parametros);
			},
			beforeSend: function()
			{
				$('#tabla_evaluacion_parametros tbody').html('<tr><td colspan="3" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function(dato)
			{
				$('#tabla_evaluacion_parametros tbody').html('<tr><td colspan="3" style="text-align: center;">Error al cargar los parámetros</td></tr>');
				return false;
			}
		});//Fin ajax
	}
	else
	{
		$('#tabla_evaluacion_parametros tbody').html('<tr><td colspan="3" style="text-align: center;">Seleccione el tipo de evaluación</td></tr>');
	}
}


var datatable_evaluacion_parametros = null;
function tabla_evaluacion_parametros(tbody)
{
	if (datatable_evaluacion_parametros != null)
	{
		datatable_evaluacion_parametros.destroy();
	}

	$('#tabla_evaluacion_parametros tbody').html(tbody);

	datatable_evaluacion_parametros = $('#tabla_evaluacion_parametros').DataTable({
		"scrollY": "220px",
        // "scrollCollapse": true,
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		// "rowsGroup": [1, 3], //agrupar filas
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


function aguaevaluacion_categorias(proyecto_id, reporteregistro_id, reporteaguaevaluacion_id)
{
	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteaguaevaluacioncategorias/"+proyecto_id+"/"+reporteregistro_id+"/"+reporteaguaevaluacion_id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			tabla_evaluacion_categorias(dato.categorias);
		},
		beforeSend: function()
		{
			$('#tabla_evaluacion_categorias tbody').html('<tr><td colspan="3" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_evaluacion_categorias tbody').html('<tr><td colspan="3" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax
}


var datatable_evaluacion_categorias = null;
function tabla_evaluacion_categorias(tbody)
{
	if (datatable_evaluacion_categorias != null)
	{
		datatable_evaluacion_categorias.destroy();
	}

	$('#tabla_evaluacion_categorias tbody').html(tbody);

	datatable_evaluacion_categorias = $('#tabla_evaluacion_categorias').DataTable({
		"scrollY": "220px",
        // "scrollCollapse": true,
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		// "rowsGroup": [1, 3], //agrupar filas
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


function activa_aguaevaluacioncategoria(checkbox, num_registro)
{
	if (checkbox.checked)
	{
		$(".aguaevaluacioncategoria_"+num_registro).attr('readonly', false);
		$(".aguaevaluacioncategoria_"+num_registro).attr('required', true);
	}
	else
	{
		$(".aguaevaluacioncategoria_"+num_registro).val('');
		$(".aguaevaluacioncategoria_"+num_registro).attr('required', false);
		$(".aguaevaluacioncategoria_"+num_registro).attr('readonly', true);
	}
}


function select_background(select)
{
	if (select.value == "Dentro de norma")
	{
		$(select).css('background-color', '#00FF00');
		$(select).css('color', '#000000');
	}
	else if (select.value == "Fuera de norma")
	{
		$(select).css('background-color', '#FF0000');
		$(select).css('color', '#FFFFFF');
	}
	else if (select.value == "No aplicable")
	{
		$(select).css('background-color', '#888888');
		$(select).css('color', '#FFFFFF');
	}
	else
	{
		$(select).css('background-color', '#FFFFFF');
		$(select).css('color', '#888888');
	}

	$(".select_alcances > option").css('background-color', '#FFFFFF');
	$(".select_alcances > option").css('color', '#888888');
}


$('#tabla_reporte_resultados tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_resultados.row(tr);


	$('#form_modal_puntomedicion').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#puntomedicion_id').val(row.data().id);


	// Campo select areas
	$('#puntomedicion_reporteaguaarea_id').html(selectareas);


	// Llenar campos
	$('#reporteaguaevaluacion_punto').val(row.data().reporteaguaevaluacion_punto);
	$('#puntomedicion_reporteaguaarea_id').val(row.data().reporteaguaarea_id);
	$('#reporteaguaevaluacion_ubicacion').val(row.data().reporteaguaevaluacion_ubicacion);
	$('#reporteaguaevaluacion_suministro').val(row.data().reporteaguaevaluacion_suministro);
	$('#reporteaguaevaluacion_tipouso').val(row.data().reporteaguaevaluacion_tipouso);
	$('#reporteaguaevaluacion_descripcionuso').val(row.data().reporteaguaevaluacion_descripcionuso);
	$('#reporteaguaevaluacion_condiciones').val(row.data().reporteaguaevaluacion_condiciones);
	$('#reporteaguaevaluacion_fecha').val(row.data().reporteaguaevaluacion_fecha);
	$('#reporteaguaevaluacion_totalpersonas').val(row.data().reporteaguaevaluacion_totalpersonas);
	$('#reporteaguaevaluacion_geo').val(row.data().reporteaguaevaluacion_geo);
	$('#reporteaguaevaluacion_foliomuestra').val(row.data().reporteaguaevaluacion_foliomuestra);
	$('#reporteaguaevaluacion_tipoevaluacion').val(row.data().reporteaguaevaluacion_tipoevaluacion);


	// Titulo del modal
	$('#modal_reporte_puntomedicion .modal-title').html('Punto de medición '+row.data().reporteaguaevaluacion_punto);


	// mostrar modal
	$('#modal_reporte_puntomedicion').modal({backdrop:false});


	// Vaciar tabla parametros	
	aguaevaluacion_parametos(row.data().id, row.data().reporteaguaevaluacion_tipoevaluacion, proveedor_id);


	// Consultar categorias
	aguaevaluacion_categorias(proyecto.id, reporteregistro_id, row.data().id);
});


$('#tabla_reporte_resultados tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_resultados.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Punto "+row.data().reporteaguaevaluacion_punto+" de medición de agua",
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
				text: "Punto "+row.data().reporteaguaevaluacion_punto+" de medición de agua",
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
						url: "/reporteaguaevaluacioneliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_resultados(proyecto.id, reporteregistro_id);

							tabla_reporte_matriz(proyecto.id, reporteregistro_id);

							reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");


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


$("#botonguardar_modal_puntomedicion").click(function()
{
	// borrar campo filtro del DATATABLE'S
	// datatable_evaluacion_parametros.search($(this).val()).draw();
	// datatable_evaluacion_categorias.search($(this).val()).draw();
	datatable_evaluacion_parametros.search("").draw();
	datatable_evaluacion_categorias.search("").draw();


	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Punto de medición de agua",
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
				$('#form_modal_puntomedicion').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar tabla
						tabla_reporte_resultados(proyecto.id, reporteregistro_id);

						tabla_reporte_matriz(proyecto.id, reporteregistro_id);

						reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");


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
						$('#botonguardar_modal_puntomedicion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_puntomedicion').attr('disabled', false);


						// cerrar modal
						$('#modal_reporte_puntomedicion').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_puntomedicion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_puntomedicion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_puntomedicion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_puntomedicion').attr('disabled', false);

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
		"rowsGroup": [1, 2], //agrupar filas
		"order": [[ 0, "DESC" ]],
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


var datatable_reporte_6_2 = null;
function tabla_reporte_6_2(tbody)
{
	if (datatable_reporte_6_2 != null)
	{
		datatable_reporte_6_2.destroy();
	}

	$('#tabla_reporte_6_2 tbody').html(tbody);

	datatable_reporte_6_2 = $('#tabla_reporte_6_2').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0, 1, 2, 4], //agrupar filas
		"order": [[ 0, "DESC" ]],
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


var datatable_reporte_analisisresultados = null;
function tabla_reporte_analisisresultados(tbody)
{
	if (datatable_reporte_analisisresultados != null)
	{
		datatable_reporte_analisisresultados.destroy();
	}


	$('#tabla_reporte_analisisresultados tbody').html(tbody);


	datatable_reporte_analisisresultados = $('#tabla_reporte_analisisresultados').DataTable({
		lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Todos"]],
		rowsGroup: [0, 1, 3], //agrupar filas
		order: [[ 0, "DESC" ]],
		ordering: false,
		searching: false,
		processing: true,
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
			// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);
			// $(row).find('td:eq(7)').css('background', data.resultadoner_color);


			if($(row).find('td:eq(6)').text() == "Dentro de norma")
			{
				$(row).find('td:eq(6)').css('background', "#00FF00");
				$(row).find('td:eq(6)').css('color', '#000000');
				$(row).find('td:eq(6)').css('font-weight', 'bold');
			}
			else if($(row).find('td:eq(6)').text() == "Fuera de norma")
			{
				$(row).find('td:eq(6)').css('background', "#FF0000");
				$(row).find('td:eq(6)').css('color', '#FFFFFF');
				$(row).find('td:eq(6)').css('font-weight', 'bold');
			}
			else
			{
				$(row).find('td:eq(6)').css('background', "#888888");
				$(row).find('td:eq(6)').css('color', '#FFFFFF');
				$(row).find('td:eq(6)').css('font-weight', 'bold');
			}
		},
	});
}


var datatable_reporte_7_3 = null;
function tabla_reporte_7_3(tbody)
{
	if (datatable_reporte_7_3 != null)
	{
		datatable_reporte_7_3.destroy();
	}


	$('#tabla_reporte_7_3 tbody').html(tbody);


	datatable_reporte_7_3 = $('#tabla_reporte_7_3').DataTable({
		lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "Todos"]],
		rowsGroup: [1, 2], //agrupar filas
		order: [[ 0, "DESC" ]],
		ordering: false,
		searching: false,
		processing: true,
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
		// rowCallback: function(row, data, index)
		// {
		// 	// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);
		// 	// $(row).find('td:eq(7)').css('background', data.resultadoner_color);

		// 	// if($(row).find('td:eq(6)').text() == "Dentro de norma")
		// 	// {
		// 	// 	$(row).find('td:eq(6)').css('background', "#00FF00");
		// 	// 	$(row).find('td:eq(6)').css('color', '#000000');
		// 	// 	$(row).find('td:eq(6)').css('font-weight', 'bold');
		// 	// }
		// 	// else
		// 	// {
		// 	// 	$(row).find('td:eq(6)').css('background', "#FF0000");
		// 	// 	$(row).find('td:eq(6)').css('color', '#FFFFFF');
		// 	// 	$(row).find('td:eq(6)').css('font-weight', 'bold');
		// 	// }
		// },
		createdRow: function(row, data, dataIndex)
		{
			// If name is "Ashton Cox"
			// if(data[1] === 'Total')
			if($(row).find('td:eq(1)').text() === 'Total')
			{
				// Update cell data
				this.api().cell($('td:eq(1)', row)).data('Total de puntos');


				// Add COLSPAN attribute
				// $(row).find('td:eq(1)').attr('colspan', 1);
				$(row).find('td:eq(1)').css('background', "#F9F9F9");
				$(row).find('td:eq(1)').css('color', '#777777');
				$(row).find('td:eq(1)').css('font-weight', 'bold');


				$(row).find('td:eq(2)').attr('colspan', 4);
				$(row).find('td:eq(2)').css('background', "#F9F9F9");
				this.api().cell($('td:eq(2)', row)).data('');


				// $(row).find('td:eq(0)').css('display', 'none');
				// $(row).find('td:eq(1)').css('display', 'none');
				// $(row).find('td:eq(2)').css('display', 'none');
				$(row).find('td:eq(3)').css('display', 'none');
				$(row).find('td:eq(4)').css('display', 'none');
				$(row).find('td:eq(5)').css('display', 'none');
			}
		}
	});
}


//=================================================
// TABLA 7.7 MATRIZ DE EXPOSICIÓN LABORAL


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_matriz(proyecto.id, reporteregistro_id);
	}, 5500);
});


var datatable_reporte_matriz = null;
function tabla_reporte_matriz(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguamatriztabla/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

		if (parseInt(proyecto.catregion_id) == 1) //REGION NORTE
		{
			if (datatable_reporte_matriz != null)
			{
				datatable_reporte_matriz.clear().draw();
				datatable_reporte_matriz.ajax.url(ruta).load();
			}
			else
			{
				$('#tabla_reporte_matriz').html('<thead>'+
													'<tr>'+
														'<th rowspan="2">Contador</th>'+
														'<th rowspan="2">Subdirección o<br>corporativo</th>'+
														'<th rowspan="2">Gerencia o<br>activo</th>'+
														'<th rowspan="2">Instalación</th>'+
														'<th rowspan="2">Área de<br>referencia<br>en atlas<br>de riesgo</th>'+
														'<th rowspan="2">Nombre</th>'+
														'<th rowspan="2">Ficha</th>'+
														'<th rowspan="2">Categoría</th>'+
														'<th rowspan="2">Número de<br>personas</th>'+
														'<th rowspan="2">Grupo de<br>exposición<br>homogénea</th>'+
														'<th colspan="3">Fisicoquímico</th>'+
														'<th colspan="3">Microbiológico</th>'+
													'</tr>'+
													'<tr>'+
														'<th>Color<br>(20 Pt/Co)</th>'+
														'<th>Olor<br>(Agradable)</th>'+
														'<th>Turbiedad<br>(5 UTN)</th>'+
														'<th>Mesofílicos<br>aerobios<br>100 UFC/mL</th>'+
														'<th>Coliformes<br>fecales<br>(ND)</th>'+
														'<th>Coliformes<br>totales<br>(ND)</th>'+
													'</tr>'+
												'</thead>'+
												'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_reporte_matriz = $('#tabla_reporte_matriz').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json)
						{
							menureporte_estado("menureporte_7_4", parseInt(json.total));

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code)
						{						
							console.log('error en datatable_reporte_matriz '+code);
							if (numeroejecucion <= 1)
							{
								tabla_reporte_matriz(proyecto_id, reporteregistro_id);
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
							data: "reporteaguaarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteaguaevaluacioncategorias_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacioncategorias_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteaguacategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacion_totalpersonas",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacion_geo",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_color",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_olor",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_turbiedad",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_totales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_fecales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_aerobios",
							defaultContent: "-",
							orderable: false,
						}
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 0, 4, 8, 9, 10, 11, 12, 13, 14, 15], //agrupar filas
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
			datatable_reporte_matriz.on('draw', function ()
			{
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
		else if (parseInt(proyecto.catregion_id) == 2) //REGION SUR
		{
			// alert('sur');

			if (datatable_reporte_matriz != null)
			{
				datatable_reporte_matriz.clear().draw();
				datatable_reporte_matriz.ajax.url(ruta).load();
			}
			else
			{

				$('#tabla_reporte_matriz').html('<thead>'+
													'<tr>'+
														'<th rowspan="2">Contador</th>'+
														'<th rowspan="2">Subdirección o<br>corporativo</th>'+
														'<th rowspan="2">Gerencia o<br>activo</th>'+
														'<th rowspan="2">Instalación</th>'+
														'<th rowspan="2">Área de referencia<br>en atlas de riesgo</th>'+
														'<th rowspan="2">Nombre</th>'+
														'<th rowspan="2">Ficha</th>'+
														'<th rowspan="2">Categoría</th>'+
														'<th colspan="3">Fisicoquímico</th>'+
														'<th colspan="3">Microbiológico</th>'+
													'</tr>'+
													'<tr>'+
														'<th>Color<br>(20 Pt/Co)</th>'+
														'<th>Olor<br>(Agradable)</th>'+
														'<th>Turbiedad<br>(5 UTN)</th>'+
														'<th>Mesofílicos<br>aerobios<br>100 UFC/mL</th>'+
														'<th>Coliformes<br>fecales<br>(ND)</th>'+
														'<th>Coliformes<br>totales<br>(ND)</th>'+
													'</tr>'+
												'</thead>'+
												'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_reporte_matriz = $('#tabla_reporte_matriz').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json)
						{
							menureporte_estado("menureporte_7_4", parseInt(json.total));

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code)
						{						
							console.log('error en datatable_reporte_matriz '+code);
							if (numeroejecucion <= 1)
							{
								tabla_reporte_matriz(proyecto_id, reporteregistro_id);
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
							data: "reporteaguaarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaarea_nombre",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteaguaevaluacioncategorias_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacioncategorias_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteaguacategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_color",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_olor",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_turbiedad",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_totales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_fecales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_aerobios",
							defaultContent: "-",
							orderable: false,
						}
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 0, 4, 8, 9, 10, 11, 12, 13], //agrupar filas
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
			datatable_reporte_matriz.on('draw', function ()
			{
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
		else  // REGION MARINA
		{
			if (datatable_reporte_matriz != null)
			{
				datatable_reporte_matriz.clear().draw();
				datatable_reporte_matriz.ajax.url(ruta).load();
			}
			else
			{
				$('#tabla_reporte_matriz').html('<thead>'+
													'<tr>'+
														'<th rowspan="2">Contador</th>'+
														'<th rowspan="2">Subdirección o<br>corporativo</th>'+
														'<th rowspan="2">Gerencia o<br>activo</th>'+
														'<th rowspan="2">Instalación</th>'+
														'<th rowspan="2">Nombre</th>'+
														'<th rowspan="2">Ficha</th>'+
														'<th rowspan="2">Categoría</th>'+
														'<th colspan="3">Fisicoquímico</th>'+
														'<th colspan="3">Microbiológico</th>'+
													'</tr>'+
													'<tr>'+
														'<th>Color<br>(20 Pt/Co)</th>'+
														'<th>Olor<br>(Agradable)</th>'+
														'<th>Turbiedad<br>(5 UTN)</th>'+
														'<th>Mesofílicos<br>aerobios<br>100 UFC/mL</th>'+
														'<th>Coliformes<br>fecales<br>(ND)</th>'+
														'<th>Coliformes<br>totales<br>(ND)</th>'+
													'</tr>'+
												'</thead>'+
												'<tbody></tbody>');

				var numeroejecucion = 1;
				datatable_reporte_matriz = $('#tabla_reporte_matriz').DataTable({
					ajax: {
						url: ruta,
						type: "get",
						cache: false,
						dataType: "json",
						data: {},
						dataSrc: function (json)
						{
							menureporte_estado("menureporte_7_4", parseInt(json.total));

							// alert("Done! "+json.msj);
							return json.data;
						},
						error: function (xhr, error, code)
						{						
							console.log('error en datatable_reporte_matriz '+code);
							if (numeroejecucion <= 1)
							{
								tabla_reporte_matriz(proyecto_id, reporteregistro_id);
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
							data: "reporteaguaarea_instalacion",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacioncategorias_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "reporteaguaevaluacioncategorias_ficha",
							defaultContent: "-",
							orderable: true,
						},
						{
							data: "reporteaguacategoria_nombre",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_color",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_olor",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "fisicoquimico_turbiedad",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_totales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_fecales",
							defaultContent: "-",
							orderable: false,
						},
						{
							data: "microbiologico_aerobios",
							defaultContent: "-",
							orderable: false,
						}
					],
					lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
					rowsGroup: [1, 2, 3, 0, 7, 9, 10, 11, 12], //agrupar filas
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
			datatable_reporte_matriz.on('draw', function ()
			{
				$('[data-toggle="tooltip"]').tooltip();
			});
		}
	}
	catch (exception)
	{
		tabla_reporte_matriz(proyecto_id, reporteregistro_id);
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_8", 1);

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
// DASHBOARD


$(document).ready(function()
{
	setTimeout(function()
	{
		reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");
	}, 6500);
});


var dashboard_ejecusiones = 0;
function reporte_dashboard(proyecto_id, reporteregistro_id, reporte_tipo)
{
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteaguadashboard/"+proyecto_id+"/"+reporteregistro_id+"/"+reporte_tipo+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			$("#dashboard_titulo").html(dato.dashboard_titulo);
			$("#dashboard_areas").html(dato.dashboard_areas);
			$("#dashboard_cumplimiento").html(dato.dashboard_cumplimiento);
			$("#dashboard_puntos_total").html(dato.dashboard_puntos_total);
			$("#dashboard_parametros").html(dato.dashboard_parametros);
			$("#dashboard_recomendaciones").html(dato.dashboard_recomendaciones);

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
		error: function(dato)
		{1
			if (dashboard_ejecusiones == 0)
			{
				reporte_dashboard(proyecto_id, reporteregistro_id, "Fisicoquímico_Microbiológico");
				dashboard_ejecusiones += 1;
			}

			return false;
		}
	});//Fin ajax
}


//=================================================
// RECOMENDACIONES


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_recomendaciones(proyecto.id, reporteregistro_id, agente_nombre);
	}, 7500);
});


var datatable_recomendaciones = null;
function tabla_reporte_recomendaciones(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteaguarecomendacionestabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

		if (datatable_recomendaciones != null)
		{
			datatable_recomendaciones.clear().draw();
			datatable_recomendaciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
		    datatable_recomendaciones = $('#tabla_reporte_recomendaciones').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_9", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_recomendaciones '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_recomendaciones(proyecto_id, reporteregistro_id, agente_nombre);
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
		datatable_recomendaciones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_recomendaciones(proyecto_id, reporteregistro_id, agente_nombre);
    }
}


$("#boton_reporte_nuevarecomendacion").click(function()
{
    $("#tabla_reporte_recomendaciones tbody").append('<tr>'+
											'<td>0</td>'+
											'<td style="text-align: center;">'+
												'<input type="checkbox" class="recomendacionadicional_checkbox" name="recomendacionadicional_checkbox[]" value="0" checked/>'+
												'<br><button type="button" class="btn btn-danger waves-effect btn-circle eliminar" data-toggle="tooltip" title="Eliminar recomendación"><i class="fa fa-trash fa-2x"></i></button>'+
											'</td>'+
											'<td style="text-align: left;">'+
												'<div class="row">'+
													'<div class="col-6">'+
														'<label>Tipo recomendación</label>'+
														'<select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>'+
															'<option value=""></option>'+
															'<option value="Preventiva">Preventiva</option>'+
															'<option value="Correctiva">Correctiva</option>'+
														'</select>'+
													'</div>'+
													'<div class="col-6">'+
														'<label>Tipo informe</label>'+
														'<select class="custom-select form-control" name="recomendacionadicional_tipoinforme[]" required>'+
															'<option value=""></option>'+
															'<option value="1">Fisicoquímico</option>'+
															'<option value="2">Microbiológico</option>'+
														'</select>'+
													'</div>'+
													'<div class="col-12">'+
														'<br>'+
														'<label>Descripción</label>'+
														'<textarea  class="form-control"style="margin-bottom: 0px;" rows="5" name="recomendacionadicional_descripcion[]" required></textarea>'+
													'</div>'+
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


$('#tabla_reporte_recomendaciones tbody').on('click', 'td>button.eliminar', function()
{
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
						url: ''+ruta_storage_guardar,
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
						success: function(dato)
						{
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							menureporte_estado("menureporte_9", 1);


							// Actualizar
							reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");


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
// EQUIPO Y MATERIAL ULILIZADO


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_10(proyecto.id, reporteregistro_id);
	}, 8000);
});


var datatable_reporte_10 = null;
function tabla_reporte_10(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reporteaguamaterialutilizado/"+proyecto_id+"/"+reporteregistro_id;

		if (datatable_reporte_10 != null)
		{
			datatable_reporte_10.clear().draw();
			datatable_reporte_10.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
		    datatable_reporte_10 = $('#tabla_reporte_10').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_10", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporte_10 '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_10(proyecto_id, reporteregistro_id);
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
						data: "reporteaguamaterial_nombre",
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
		datatable_reporte_10.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_10(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_materialutilizado").click(function()
{
	$('#form_modal_materialutilizado').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#materialutilizado_id').val(0);


	// Titulo del modal
	$('#modal_reporte_materialutilizado .modal-title').html('Nuevo material utilizado');


	// mostrar modal
	$('#modal_reporte_materialutilizado').modal({backdrop:false});
});


$('#tabla_reporte_10 tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_10.row(tr);


	$('#form_modal_materialutilizado').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#materialutilizado_id').val(row.data().id);


	// Llenar campos
	$('#reporteaguamaterial_nombre').val(row.data().reporteaguamaterial_nombre);


	// Titulo del modal
	$('#modal_reporte_materialutilizado .modal-title').html('Material utilizado');


	// mostrar modal
	$('#modal_reporte_materialutilizado').modal({backdrop:false});
});


$('#tabla_reporte_10 tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_10.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Material utilizado ("+row.data().reporteaguamaterial_nombre+")",
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
				text: "Material utilizado ("+row.data().reporteaguamaterial_nombre+")",
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
						url: "/reporteaguamaterialutilizadoeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_10(proyecto.id, reporteregistro_id);


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


$("#botonguardar_modal_materialutilizado").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Material utilizado",
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
				$('#form_modal_materialutilizado').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;


						// Actualizar tabla
						tabla_reporte_10(proyecto.id, reporteregistro_id);


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
						$('#botonguardar_modal_materialutilizado').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_materialutilizado').attr('disabled', false);


						// cerrar modal
						$('#modal_reporte_materialutilizado').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_materialutilizado').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_materialutilizado').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_materialutilizado').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_materialutilizado').attr('disabled', false);

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
// RESPONSABLES DEL INFORME


$(document).ready(function()
{
	$('#reporteresponsable1documento').dropify({
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

	$('#reporteresponsable2documento').dropify({
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_11", 1);


						$('#boton_descargarresponsabledoc1').css('display', 'block');
						$('#boton_descargarresponsabledoc2').css('display', 'block');

						// Carpeta ubicacion documentos historial
						$('#responsablesinforme_carpetadocumentoshistorial').val('');

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
	window.open('/reporteaguaresponsabledocumento/'+reporteregistro_id+'/'+1+'/'+1);
});


$("#boton_descargarresponsabledoc2").click(function()
{
	window.open('/reporteaguaresponsabledocumento/'+reporteregistro_id+'/'+2+'/'+1);
});


//=================================================
// PLANOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_planos(proyecto.id, reporteregistro_id, agente_nombre);
	}, 8500);
});


var datatable_planos = null;
function tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteaguaplanostabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
						menureporte_estado("menureporte_12_2", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_planos '+code);
						if (numeroejecucion <= 1)
						{
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
					},
					{
						data: "tipo_evaluacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "total_planos",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [2], //agrupar filas
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
		datatable_planos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre);
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						// Actualiza menu
						menureporte_estado("menureporte_12_2", dato.total);

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
						$('#botonguardar_reporte_planos').html('Guardar carpeta planos <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_planos').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_planos').html('Guardando carpeta planos <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_planos').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_planos').html('Guardar carpeta planos <i class="fa fa-save"></i>');
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
// INFORME DE RESULTADOS LABORATORIO


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_anexosresultados(proyecto.id, reporteregistro_id, agente_nombre);
	}, 9000);
});


var datatable_informeresultados = null;
function tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteaguaanexosresultadostabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

		if (datatable_informeresultados != null)
		{
			datatable_informeresultados.clear().draw();
			datatable_informeresultados.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_informeresultados = $('#tabla_reporte_anexosresultados').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// Actualiza menu
						menureporte_estado("menureporte_12_3", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_informeresultados '+code);
						if (numeroejecucion <= 1)
						{
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
						data: "agente_nombre",
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
				rowsGroup: [3], //agrupar filas
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
		datatable_informeresultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre);
    }
}


$("#botonguardar_reporte_anexosresultados").click(function()
{
	// valida campos vacios
	var seleccionados = 0;
	$('.anexoresultado_checkbox').each(function()
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
				text: "Lista de anexos de resultados",
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
					$('#form_reporte_anexosresultados').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: ''+ruta_storage_guardar,
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
						success: function(dato)
						{
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							// Actualiza menu
							menureporte_estado("menureporte_12_3", dato.total);

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
							$('#botonguardar_reporte_anexosresultados').html('Guardar anexos resultados <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_anexosresultados').attr('disabled', false);
						},
						beforeSend: function()
						{
							$('#botonguardar_reporte_anexosresultados').html('Guardando anexos resultados <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_anexosresultados').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_reporte_anexosresultados').html('Guardar anexos resultados <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_anexosresultados').attr('disabled', false);

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


$('#tabla_reporte_anexosresultados tbody').on('click', 'td.documentopdf', function()
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
// ANEXOS - ACREDITACIONES Y / O APROBACIONES


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_acreditacionaprobacion(proyecto.id, reporteregistro_id, agente_nombre);
	}, 9500);
});


var datatable_acreditacionaprobacion = null;
function tabla_reporte_acreditacionaprobacion(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteaguaanexosacreditacionestabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

		if (datatable_acreditacionaprobacion != null)
		{
			datatable_acreditacionaprobacion.clear().draw();
			datatable_acreditacionaprobacion.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_acreditacionaprobacion = $('#tabla_reporte_acreditacionaprobacion').DataTable({
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
							menureporte_estado("menureporte_12_4", 1);
							menureporte_estado("menureporte_12_5", 1);
						}

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_acreditacionaprobacion '+code);
						if (numeroejecucion <= 1)
						{
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
						data: "proveedor_NombreComercial",
						defaultContent: "-",
						orderable: true,
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
						data: "catArea_Nombre",
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
		datatable_acreditacionaprobacion.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_acreditacionaprobacion(proyecto_id, reporteregistro_id, agente_nombre);
    }
}


$('#tabla_reporte_acreditacionaprobacion tbody').on('click', 'td.certificadopdf', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_acreditacionaprobacion.row(tr);

	if (row.data().acreditacion_SoportePDF)
	{
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/'+row.data().id);

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().catTipoAcreditacion_Nombre+' '+row.data().acreditacion_Entidad+' ['+row.data().acreditacion_Numero+']');

		// Abrir modal
		$('#modal_visor').modal({backdrop:false});
	}
});


$("#botonguardar_reporte_acreditacionaprobacion").click(function()
{
	// valida campos vacios
	var seleccionados = 0;
	$('.anexoacreditacion_checkbox').each(function()
	{
		if (this.checked)
		{
			seleccionados += 1;
		}
	});


	if (seleccionados > 0)
	{
		// borrar campo filtro del DATATABLE
		// datatable_acreditacionaprobacion.search($(this).val()).draw();
		datatable_acreditacionaprobacion.search("").draw();


		// valida campos vacios
		var valida = this.form.checkValidity();
		if (valida)
		{
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
			function(isConfirm)
			{
				if (isConfirm)
				{
					// cerrar msj confirmacion
					swal.close();

					// enviar datos
					$('#form_reporte_acreditacionaprobacion').ajaxForm({
						dataType: 'json',
						type: 'POST',
						url: ''+ruta_storage_guardar,
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
						success: function(dato)
						{
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;

							// Actualiza menu
							menureporte_estado("menureporte_12_4", dato.total);
							menureporte_estado("menureporte_12_5", dato.total);

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
							$('#botonguardar_reporte_acreditacionaprobacion').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', false);
						},
						beforeSend: function()
						{
							$('#botonguardar_reporte_acreditacionaprobacion').html('Guardando anexos 7 (STPS) y 8 (EMA) <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_reporte_acreditacionaprobacion').html('Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_acreditacionaprobacion').attr('disabled', false);

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


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_revisiones(proyecto.id);
	}, 10000);
});


$(document).on('submit', function()
{
	setTimeout(function()
	{
		// alert('Guardaste algo');
		tabla_reporte_revisiones(proyecto.id);
	}, 6000);
});


var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id)
{
	try 
	{
		var ruta = "/reporteaguarevisionestabla/"+proyecto_id;

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


function reporte_concluido(reporte_id, perfil, checkbox)
{
	if (parseInt(perfil) == 1)
	{
		var pregunta_texto = '';
		

		if (checkbox.checked) // Activado
		{
			$(checkbox).prop('checked', false);

			pregunta_texto = '¿Concluir informe?';
		}
		else
		{
			$(checkbox).prop('checked', true);

			pregunta_texto = '¿Quitar concluido?';
		}


		//---------------------


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


		if ((checkbox.checked) || parseInt(pendientes) == 0)
		{
			// Confirmar
			swal({
				title: ""+pregunta_texto,
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
						url: "/reporteaguarevisionconcluir/"+reporte_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_resultados(proyecto.id, reporteregistro_id);
							tabla_reporte_10(proyecto.id, reporteregistro_id);


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
}


function reporte_cancelado(reporte_id, perfil, checkbox)
{
	if (parseInt(perfil) == 1)
	{
		$('#form_modal_cancelacionobservacion').each(function(){
			this.reset();
		});


		// Campos Hidden
		$('#reporterevisiones_id').val(reporte_id);


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
						url: ''+ruta_storage_guardar,
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
						success: function(dato)
						{
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_resultados(proyecto.id, reporteregistro_id);
							tabla_reporte_10(proyecto.id, reporteregistro_id);
							

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
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
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
					url: ''+ruta_storage_guardar,
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
					success: function(dato)
					{
						// Actualizar tablas
						botoninforme_estado(dato.estado);
						tabla_reporte_revisiones(proyecto.id);

						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_resultados(proyecto.id, reporteregistro_id);
						tabla_reporte_10(proyecto.id, reporteregistro_id);


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
	}
});


var contador_graficas = 0;
// var dashboard_graficas = [];
var dashboard_parametros = [];


$("#boton_reporte_nuevarevision").click(function()
{
	if (ultimaversion_cancelada == 1)
	{
		if (parseInt(datatable_reporte_resultados.data().count()) > 0)
		{
			swal({
				title: "¿Generar nueva revision?",
				text: "Informe de "+agente_nombre,
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


					$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Copiando revisión actual, por favor espere...');
					$('#boton_reporte_nuevarevision').attr('disabled', true);


					// Inicializar variables
					contador_graficas = 0;
					// dashboard_graficas = [];
					dashboard_parametros = [];


					// ACTUALIZA DASHBOARD
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/reporteaguarevisionparametros/"+proyecto.id+"/"+reporteregistro_id+"/"+ultimarevision_id+"/"+ultimarevision_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							dashboard_parametros = dato.parametros;
							dashboard_actualizar(proyecto.id, reporteregistro_id, dato.parametros[0]);


							// // mensaje dashboard actualizado
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
							reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");
							console.log("Error al consultar los paramtros evaluados en este informe");
							$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
							$('#boton_reporte_nuevarevision').attr('disabled', false);
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
				title: "No disponible",
				text: "Aun no se ha evaluado ningún punto para este informe de agua",
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


function dashboard_actualizar(proyecto_id, reporteregistro_id, reporte_tipo)
{
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteaguadashboard/"+proyecto_id+"/"+reporteregistro_id+"/"+reporte_tipo+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			$("#dashboard_titulo").html(dato.dashboard_titulo);
			$("#dashboard_areas").html(dato.dashboard_areas);
			$("#dashboard_cumplimiento").html(dato.dashboard_cumplimiento);
			$("#dashboard_puntos_total").html(dato.dashboard_puntos_total);
			$("#dashboard_parametros").html(dato.dashboard_parametros);
			$("#dashboard_recomendaciones").html(dato.dashboard_recomendaciones);


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


			graficas_base64();
		},
		error: function(dato)
		{
			console.log("Error al actualizar dashboard");
			return false;
		}
	});//Fin ajax
}


function graficas_base64()
{
	html2canvas(document.querySelector("#tabla_dashboard"), { scale: 6}).then(canvas =>
	{
		// document.getElementById("captura").appendChild(canvas)
		img64 = canvas.toDataURL('image/jpeg');
		// dashboard_graficas.push(img64+"*");

		setTimeout(function()
		{
			// Enviar imagenes imagenes
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/reporteaguaword",
				data:{
					_token: document.querySelector('meta[name="csrf-token"]')['content'],
					proyecto_id: proyecto.id,
					agente_id: agente_id,
					agente_nombre: agente_nombre,
					reporteregistro_id: reporteregistro_id,
					areas_poe: areas_poe,
					ultimarevision_id: ultimarevision_id,
					crear_revision: 1,
					dashboard_parametro: dashboard_parametros[parseInt(contador_graficas)],
					grafica_dashboard: img64,
				},
				cache: false,
				success:function(dato)
				{
					contador_graficas += 1;


					if (parseInt(contador_graficas) < parseInt(dashboard_parametros.length))
					{
						dashboard_actualizar(proyecto.id, reporteregistro_id, dashboard_parametros[parseInt(contador_graficas)]);
					}
					else
					{
						reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");

						// Actualizar tablas
						botoninforme_estado(1);
						tabla_reporte_revisiones(proyecto.id);

						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_resultados(proyecto.id, reporteregistro_id);
						tabla_reporte_10(proyecto.id, reporteregistro_id);


						// Boton
						$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
						$('#boton_reporte_nuevarevision').attr('disabled', false);


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
					}
				},
				error: function(dato)
				{
					// Boton
					$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
					$('#boton_reporte_nuevarevision').attr('disabled', false);


					reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");
					console.log("Error al crear revision");


					// mensaje
					swal({
						title: "Error",
						text: "Al intentar crear revisión, intentelo de nuevo.",
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


/*
function revision_crear()
{
	// Enviar imagenes imagenes
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "/reporteaguarevisionnueva",
		data:{
			_token: document.querySelector('meta[name="csrf-token"]')['content'],
			proyecto_id: proyecto.id,
			agente_id: agente_id,
			agente_nombre: agente_nombre,
			reporteregistro_id: reporteregistro_id,
			dashboard_parametros: dashboard_parametros,
			dashboard_graficas: dashboard_graficas,
		},
		cache: false,
		success:function(dato)
		{
			// Actualizar tablas
			botoninforme_estado(1);
			tabla_reporte_revisiones(proyecto.id);

			tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
			tabla_reporte_categorias(proyecto.id, reporteregistro_id);
			tabla_reporte_areas(proyecto.id, reporteregistro_id);
			tabla_reporte_resultados(proyecto.id, reporteregistro_id);
			tabla_reporte_10(proyecto.id, reporteregistro_id);


			// Boton
			$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
			$('#boton_reporte_nuevarevision').attr('disabled', false);


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
			reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");
			console.log("Error al consultar los paramtros evaluados en este informe");

			// mensaje
			swal({
				title: "Error",
				text: "Al crear copia del informe, intentelo de nuevo.",
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
*/

//=================================================
// GENERAR WORD


$('#tabla_reporte_revisiones tbody').on('click', 'td>button.botondescarga', function()
{
	var botondescarga = this;

	
	var tr = $(this).closest('tr');
	var row = datatable_reporterevisiones.row(tr);

	
	// Boton descarga
	$("#"+botondescarga.id).html('<i class="fa fa-spin fa-spinner fa-2x"></i>');


	// Limpiar form
	$('#form_modal_imprimirtipo').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#imprimirtipo_registro_id').val(reporteregistro_id);
	$('#imprimirtipo_revision_id').val(row.data().id);
	$('#imprimirtipo_ultima_revision').val(row.data().ultima_revision);


	var parametros_opciones = '<option value=""></option>';
	

	// CONSULTAR PARAMETROS DISPONIBLES [FISICOQUIMICO / MICROBIOLOGICO]
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteaguarevisionparametros/"+proyecto.id+"/"+reporteregistro_id+"/"+row.data().id+"/"+ultimarevision_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			if (parseInt(dato.parametros.length) > 0)
			{
				for (var i = 0; i < parseInt(dato.parametros.length); i++)
				{
					parametros_opciones += '<option value="'+dato.parametros[i]+'">'+dato.parametros[i]+'</option>';
				}


				$('#imprimirtipo_tipoinforme').html(parametros_opciones);
			}
			else
			{
				$('#imprimirtipo_tipoinforme').html('<option value="">Aún o se encontrarón puntos de evaluados en este informe</option>');
				
			}


			// // mensaje dashboard actualizado
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


			$("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');
		},
		error: function(dato)
		{
			console.log("Error al consultar los tipos de informes disponibles para impresión");
			$('#imprimirtipo_tipoinforme').html('<option value="">Error al consultar tipo informe</option>');			
			return false;
		}
	});//Fin ajax


	// Actualiza dashboard
	reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico"); //Actualizar dashboard a la normalidad


	// Titulo del modal
	$('#modal_reporte_imprimirtipo .modal-title').html('Descargar informe de '+agente_nombre+' revisión '+row.data().reporterevisiones_revision);


	// mostrar modal
	$('#modal_reporte_imprimirtipo').modal({backdrop:false});
});


$("#botonguardar_modal_imprimirtipo").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		$('#botonguardar_modal_imprimirtipo').html('Descargando, por favor espere <i class="fa fa-spin fa-spinner"></i>');
		$('#botonguardar_modal_imprimirtipo').attr('disabled', true);


		if (parseInt($("#imprimirtipo_ultima_revision").val()) > 0)
		{
			// ACTUALIZA DASHBOARD
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/reporteaguadashboard/"+proyecto.id+"/"+$("#imprimirtipo_registro_id").val()+"/"+$("#imprimirtipo_tipoinforme").val()+"/"+areas_poe,
				data:{},
				cache: false,
				success:function(dato)
				{
					$("#dashboard_titulo").html(dato.dashboard_titulo);
					$("#dashboard_areas").html(dato.dashboard_areas);
					$("#dashboard_cumplimiento").html(dato.dashboard_cumplimiento);
					$("#dashboard_puntos_total").html(dato.dashboard_puntos_total);
					$("#dashboard_parametros").html(dato.dashboard_parametros);
					$("#dashboard_recomendaciones").html(dato.dashboard_recomendaciones);


					// // mensaje dashboard actualizado
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


					html2canvas(document.querySelector("#tabla_dashboard"), { scale: 6}).then(canvas =>
					{
						// document.getElementById("captura").appendChild(canvas)
						var img64 = canvas.toDataURL('image/jpeg');

						// Guardar imagen
						setTimeout(function()
						{
							// Enviar datos
							$.ajax({
								type: "POST",
								dataType: "json",
								url: "/reporteaguaword",
								data:{
									_token: document.querySelector('meta[name="csrf-token"]')['content'],
									proyecto_id: proyecto.id,
									agente_id: agente_id,
									agente_nombre: agente_nombre,
									reporteregistro_id: reporteregistro_id,
									areas_poe: areas_poe,
									ultimarevision_id: ultimarevision_id,
									crear_revision: 0,
									dashboard_parametro: $("#imprimirtipo_tipoinforme").val(),
									grafica_dashboard: img64,
								},
								cache: false,
								success:function(dato)
								{
									// ventana = window.open("/reporteaguaword/"+proyecto.id+"/"+$("#imprimirtipo_registro_id").val()+"/"+$("#imprimirtipo_tipoinforme").val());
									ventana = window.open('/reporteaguaworddescargar/'+proyecto.id+"/"+$("#imprimirtipo_revision_id").val()+"/"+$("#imprimirtipo_tipoinforme").val()+"/"+$('#imprimirtipo_ultima_revision').val());


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


									// Boton
									$('#botonguardar_modal_imprimirtipo').html('Descargar <i class="fa fa-download"></i>');
									$('#botonguardar_modal_imprimirtipo').attr('disabled', false);


									// Actualizar tablas
									tabla_reporte_revisiones(proyecto.id);
									reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico"); //Actualizar dashboard a la normalidad
								},
								error: function(dato)
								{
									// Boton
									$('#botonguardar_modal_imprimirtipo').html('Descargar <i class="fa fa-download"></i>');
									$('#botonguardar_modal_imprimirtipo').attr('disabled', false);

									
									// mensaje
									swal({
										title: "Error",
										text: "Al intentar guardar las gráficas del informe, intentelo de nuevo.",
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
				},
				error: function(dato)
				{
					console.log("Error al actualizar dashboard");
					reporte_dashboard(proyecto.id, reporteregistro_id, "Fisicoquímico_Microbiológico");
					return false;
				}
			});//Fin ajax


			//-----------------------------------------------------


			// ventana = window.open("/reporteaguaword/"+proyecto.id+"/"+$("#imprimirtipo_registro_id").val()+"/"+$("#imprimirtipo_tipoinforme").val());

			// // Boton
			// $('#botonguardar_modal_imprimirtipo').html('Descargar <i class="fa fa-download"></i>');
			// $('#botonguardar_modal_imprimirtipo').attr('disabled', false);
		}
		else
		{
			ventana = window.open('/reporteaguaworddescargar/'+proyecto.id+"/"+$("#imprimirtipo_revision_id").val()+"/"+$("#imprimirtipo_tipoinforme").val()+"/"+$('#imprimirtipo_ultima_revision').val());


			setTimeout(function()
			{
				if (ventana.window)
				{
					ventana.window.close();
				}
			}, 15000);


			// Boton
			$('#botonguardar_modal_imprimirtipo').html('Descargar <i class="fa fa-download"></i>');
			$('#botonguardar_modal_imprimirtipo').attr('disabled', false);


			// Actualizar tablas
			tabla_reporte_revisiones(proyecto.id);
		}

		return false;
	}
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

			url = 'descargarPortadaInformes/' + proyecto.id + '/' + 9;
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
                    a.download = `Plantilla principal (Agua) - ${instalacion}.docx`;
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
