
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
	var fromTop = $(this).scrollTop() + topMenuHeight - 0;
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

var reporteregistro_id = 0;
var agente_id = 16;
var agente_nombre = "Infraestructura para Servicios al Personal";
var ruta_storage_guardar = '/reporteserviciopersonal';
// var proyecto = <?php echo json_encode($proyecto); ?>; // Variable declarada en el Blade
// var recsensorial = <?php echo json_encode($recsensorial); ?>; // Variable declarada en el Blade
// var proveedor_id = <?php echo json_encode($proveedor_id); ?>; // Variable declarada en el Blade


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
		url: "/reporteserviciopersonaldatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			if (parseInt(dato.reporte_concluido) == 1 || parseInt(dato.reporte_cancelado) == 1)
			{
				botoninforme_estado(1);
			}
			else
			{
				botoninforme_estado(0);
			}


			if (parseInt(dato.reporteregistro_id) > 0)
			{
				reporteregistro_id = parseInt(dato.reporteregistro_id);
				// $('#reporteregistro_id').html(reporteregistro_id);
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
			$('#reporte_alcanceinforme').val(dato.reporte_portada.reporte_alcanceinforme);
			$('.div_instalacion_nombre').html(dato.reporte_portada.reporte_instalacion);


			reporte_alcance(dato.reporte_portada.reporte_alcanceinforme);


			$('#reporte_introduccion').html(dato.reporte_introduccion);
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_metodologia_4').html(dato.reporte_metodologia_4);


			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			if (dato.reporte_ubicacioninstalacion.ubicacionfoto)
			{
				var archivo = dato.reporte_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reporteserviciopersonalmapaubicacion/'+reporteregistro_id+'/'+0;
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
			$('#reporte_punto_8_3').html(dato.reporte_punto_8_3);
			$('#reporte_punto_8_4').html(dato.reporte_punto_8_4);
			$('#reporte_conclusion').html(dato.reporte_conclusion);
			$('#reporte_recomendaciones').html(dato.reporte_recomendaciones);


			if (dato.reporte_responsablesinforme.responsable1)
			{
				// Responsable 1
				$('#reporte_responsable1').val(dato.reporte_responsablesinforme.responsable1);
				$('#reporte_responsable1cargo').val(dato.reporte_responsablesinforme.responsable1cargo);
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportevibracionresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+1+'/'+0;
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
				var imagenUrl = '/reportevibracionresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+2+'/'+0;
				$('#reporteresponsable2documento').dropify().data('dropify').destroy();
				$('#reporteresponsable2documento').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
				$('#reporteresponsable2documento').dropify().data('dropify').init();
				$('#reporteresponsable2documento').attr('required', false);

				// Botones de descarga
				$('#boton_descargarresponsabledoc1').css('display', 'none');
				$('#boton_descargarresponsabledoc2').css('display', 'none');
				$('#responsablesinforme_carpetadocumentoshistorial').val('reportes/proyecto/'+dato.reporte_responsablesinforme.proyecto_id+'/'+agente_nombre+'/'+dato.reporte_responsablesinforme.registro_id+'/responsables informe');
				if (parseInt(dato.reporte_responsablesinforme_guardado) == 1)
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


			// ACTUALIZAR MENU INDICE, SI CADA PUNTO YA HA SIDO GUARDADO
			menureporte_estado("menureporte_0", dato.reporte_portada_guardado);
			menureporte_estado("menureporte_1", dato.reporte_introduccion_guardado);
			menureporte_estado("menureporte_2", 1); // Definiciones
			menureporte_estado("menureporte_3_1", dato.reporte_objetivogeneral_guardado);
			menureporte_estado("menureporte_3_2", dato.reporte_objetivoespecifico_guardado);
			menureporte_estado("menureporte_4", dato.reporte_metodologia_4_guardado);
			menureporte_estado("menureporte_5_1", 1);
			menureporte_estado("menureporte_6_1", dato.reporte_ubicacioninstalacion_guardado);
			menureporte_estado("menureporte_7", dato.reporte_procesoinstalacion_guardado);
			menureporte_estado("menureporte_8_3", dato.reporte_punto_8_3_guardado);
			menureporte_estado("menureporte_8_4", dato.reporte_punto_8_4_guardado);
			menureporte_estado("menureporte_9", dato.reporte_conclusion_guardado);
			menureporte_estado("menureporte_10", dato.reporte_recomendaciones_guardado);
			menureporte_estado("menureporte_11", dato.reporte_responsablesinforme_guardado);
			menureporte_estado("menureporte_12_1", 1);
			menureporte_estado("menureporte_12_2", 1);
			menureporte_estado("menureporte_12_4", 1);
			menureporte_estado("menureporte_12_5", 1);
			menureporte_estado("menureporte_12_6", 1);
			menureporte_estado("menureporte_12_7", 1);
			menureporte_estado("menureporte_12_8", 1);
			menureporte_estado("menureporte_12_9", 1);
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
			

			$('#reporte_instalacion').val('Error al cargar los datos');
			$('#reporte_fecha').val('Error al cargar los datos');
			$('#reporte_introduccion').html('Error al cargar los datos');
			$('#reporte_objetivogeneral').html('Error al cargar los datos');
			$('#reporte_objetivoespecifico').html('Error al cargar los datos');
			$('#reporte_metodologia_4').html('Error al cargar los datos');
			$('#reporte_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reporte_procesoinstalacion').html('Error al cargar los datos');
			$('#reporte_punto_8_3').html('Error al cargar los datos');
			$('#reporte_punto_8_4').html('Error al cargar los datos');
			$('#reporte_conclusion').html('Error al cargar los datos');
			$('#reporte_recomendaciones').html('Error al cargar los datos');


			return false;
		}
	});//Fin ajax
}


function reporte_alcance(alcance)
{
	if (alcance)
	{
		if (parseInt(alcance) == 1)
		{
			$('.div_proporcionalidad_dimencionalidad').css('display', 'block');
			$('.div_proporcionalidad_dimencionalidad_NA').css('display', 'none');

			evaluacion_pyd(proyecto.id);
		}
		else
		{
			$('.div_proporcionalidad_dimencionalidad').css('display', 'none');
			$('.div_proporcionalidad_dimencionalidad_NA').css('display', 'inline-block');

			menureporte_estado("menureporte_8_1", 1);
		}
	}
	else
	{
		$('.div_proporcionalidad_dimencionalidad').css('display', 'block');
		$('.div_proporcionalidad_dimencionalidad_NA').css('display', 'none');

		menureporte_estado("menureporte_8_1", 0);
	}
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
		var ruta = "/reporteserviciopersonaltabladefiniciones/"+proyecto_id+"/"+agente_nombre+"/"+reporteregistro_id;

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
				"lengthMenu": [[10, 30, 60, -1], [10, 30, 60, "Todos"]],
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
							url: "/reporteserviciopersonaldefinicioneliminar/"+row.data().id,
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
// METODOLOGÍA PUNTO 4


$("#botonguardar_reporte_metodologia_4").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Punto 4",
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
				$('#form_reporte_metodologia_4').ajaxForm({
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

						menureporte_estado("menureporte_4", 1);

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
						$('#botonguardar_reporte_metodologia_4').html('Guardar punto 4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_metodologia_4').html('Guardando punto 4 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_metodologia_4').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_metodologia_4').html('Guardar punto 4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_metodologia_4').attr('disabled', false);

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
						opcion: 6,
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

						menureporte_estado("menureporte_6_1", 1);

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
	window.open('/reporteserviciopersonalmapaubicacion/'+reporteregistro_id+'/'+1);
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
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 7,
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

						menureporte_estado("menureporte_7", 1);

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
// EVALUACIÓN PROPORCIONALIDAD Y DIMENCIONALIDAD


var pyd = [];
var intentos = 0;


function evaluacion_pyd(proyecto_id)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteserviciopersonalevaluacionpyd/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			pyd = dato.evaluacion_pyd;


			$('#div_proporcionalidad_dimensionalidad_resultados').html(dato.tablas_pyd);


			if (pyd.reporteserviciopersonalevaluacionpyd_areainstalacion)
			{
				menureporte_estado("menureporte_8_1", 1);
			}
			else
			{
				menureporte_estado("menureporte_8_1", 0);
			}
		},
		beforeSend: function()
		{},
		error: function(dato)
		{
			if (intentos == 0)
			{
				evaluacion_pyd(proyecto_id)
				intentos += 1;
			}
			
			return false;
		}
	});//Fin ajax
}


$("#boton_reporte_evaluacionpyd").click(function()
{
	if (pyd.reporteserviciopersonalevaluacionpyd_areainstalacion)
	{
		// Campos Hidden
		$('#reporteserviciopersonalevaluacionpyd_id').val(pyd.id);

		$('#reporteserviciopersonalevaluacionpyd_areainstalacion').val(pyd.reporteserviciopersonalevaluacionpyd_areainstalacion);
		$('#reporteserviciopersonalevaluacionpyd_tipologia').val(pyd.reporteserviciopersonalevaluacionpyd_tipologia);
		$('#reporteserviciopersonalevaluacionpyd_personas').val(pyd.reporteserviciopersonalevaluacionpyd_personas);
		$('#reporteserviciopersonalevaluacionpyd_m2').val(pyd.reporteserviciopersonalevaluacionpyd_m2);
		$('#reporteserviciopersonalevaluacionpyd_escusados').val(pyd.reporteserviciopersonalevaluacionpyd_escusados);
		$('#reporteserviciopersonalevaluacionpyd_lavabos').val(pyd.reporteserviciopersonalevaluacionpyd_lavabos);
		$('#reporteserviciopersonalevaluacionpyd_mingitorios').val(pyd.reporteserviciopersonalevaluacionpyd_mingitorios);
		$('#reporteserviciopersonalevaluacionpyd_Regaderas').val(pyd.reporteserviciopersonalevaluacionpyd_Regaderas);
		$('#reporteserviciopersonalevaluacionpyd_notap').val(pyd.reporteserviciopersonalevaluacionpyd_notap);
		$('#reporteserviciopersonalevaluacionpyd_notad').val(pyd.reporteserviciopersonalevaluacionpyd_notad);
	}
	else
	{
		$('#form_modal_evaluacionpyd').each(function(){
			this.reset();
		});


		// Campos Hidden
		$('#reporteserviciopersonalevaluacionpyd_id').val(0);

		$('#reporteserviciopersonalevaluacionpyd_areainstalacion').val(proyecto.proyecto_clienteinstalacion);
	}


	// Titulo del modal
	$('#modal_reporte_evaluacionpyd .modal-title').html('Evaluación de Proporcionalidad y Dimensionalidad');

	// mostrar modal
	$('#modal_reporte_evaluacionpyd').modal({backdrop:false});
});


$("#botonguardar_modal_evaluacionpyd").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Evaluación de Proporcionalidad y Dimensionalidad",
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
				$('#form_modal_evaluacionpyd').ajaxForm({
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
						reporte_instalacion: $("#reporte_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						evaluacion_pyd(proyecto.id);

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
						$('#botonguardar_modal_evaluacionpyd').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_evaluacionpyd').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_evaluacionpyd').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_evaluacionpyd').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_evaluacionpyd').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_evaluacionpyd').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_evaluacionpyd').attr('disabled', false);

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
// EVALUACIÓN PUNTOS


$(document).ready(function()
{
	$('#reportefileevidencia1').dropify({
		messages:
		{
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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

	$('#reportefileevidencia2').dropify({
		messages:
		{
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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

	setTimeout(function()
	{
		tabla_reporte_evaluacion(proyecto.id);
	}, 4000);
});


var datatable_evaluacion = null;
function tabla_reporte_evaluacion(proyecto_id)
{
	try 
	{
		var ruta = "/reporteserviciopersonalevaluaciontabla/"+proyecto_id;

		if (datatable_evaluacion != null)
		{
			datatable_evaluacion.clear().draw();
			datatable_evaluacion.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_evaluacion = $('#tabla_reporte_evaluacion').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// Tablas
						menureporte_estado("menureporte_8_2", parseInt(json.data.length));

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_evaluacion '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_evaluacion(proyecto_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "tipo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalevaluacion_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalevaluacion_lugar",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "descripcion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalevaluacion_cumplimiento",
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
				rowsGroup: [0], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
				paging: true,
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
				rowCallback: function(row, data, index)
				{
					// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					$(row).find('td:eq(3)').css('text-align', 'justify');

					if(data.reporteserviciopersonalevaluacion_cumplimiento == "Si cumple")
					{
						$(row).find('td:eq(4)').css('background', "#00FF00");
						$(row).find('td:eq(4)').css('color', '#000000');
						$(row).find('td:eq(4)').css('font-weight', 'bold');
					}
					else if(data.reporteserviciopersonalevaluacion_cumplimiento == "No cumple")
					{
						$(row).find('td:eq(4)').css('background', "#FF0000");
						$(row).find('td:eq(4)').css('color', '#FFFFFF');
						$(row).find('td:eq(4)').css('font-weight', 'bold');
					}
					else
					{
						$(row).find('td:eq(4)').css('background', "#DDDDDD");
						$(row).find('td:eq(4)').css('color', '#000000');
						$(row).find('td:eq(4)').css('font-weight', 'bold');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_evaluacion.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_evaluacion(proyecto_id);
    }
}


$("#boton_reporte_evaluacion").click(function()
{
	$('#form_modal_evaluacion').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteserviciopersonalevaluacion_id').val(0);

	$('#reporteserviciopersonalevaluacion_cumplimiento').css('color', '#888888');

	// Campos fotos
	$('#reportefileevidencia1').dropify().data('dropify').resetPreview();
	$('#reportefileevidencia1').dropify().data('dropify').clearElement();
	$('#reportefileevidencia2').dropify().data('dropify').resetPreview();
	$('#reportefileevidencia2').dropify().data('dropify').clearElement();

	$('#btneval_eliminaevidencia1').css('display', 'none');
	$('#btneval_eliminaevidencia2').css('display', 'none');

	// Titulo del modal
	$('#modal_reporte_evaluacion .modal-title').html('Evaluación de las instalaciones y servicios');

	// mostrar modal
	$('#modal_reporte_evaluacion').modal({backdrop:false});
});


function evaluacion_cumplimiento(select)
{
	if(select.value)
	{
		if(select.value == "Si cumple")
		{
			$(select).css('color', '#22C466');
		}
		else if (select.value == "No cumple")
		{
			$(select).css('color', '#FF0000');
		}
		else
		{
			$(select).css('color', '#555555');
		}
	}
	else
	{
		$(select).css('color', '#888888');
	}
}


function punto_verificar(puntoverificar_id)
{
	if (puntoverificar_id)
	{
		if (parseInt(puntoverificar_id) > 0)
		{
			// Buscar el index en el array JSON
			var index = evaluacion_puntosverificarcatalogo.findIndex(obj => obj.id==puntoverificar_id);
			// alert(index);

			$('#reporteserviciopersonalevaluacion_titulo').val(evaluacion_puntosverificarcatalogo[index].reporteserviciopersonalevaluacioncatalogo_titulo);
			$('#reporteserviciopersonalevaluacion_descripcion').val(evaluacion_puntosverificarcatalogo[index].reporteserviciopersonalevaluacioncatalogo_descripcion);
		}
		else
		{
			$('#reporteserviciopersonalevaluacion_titulo').val('');
			$('#reporteserviciopersonalevaluacion_descripcion').val('');
		}
	}
	else
	{
		$('#reporteserviciopersonalevaluacion_titulo').val('');
		$('#reporteserviciopersonalevaluacion_descripcion').val('');
	}
}


$('#tabla_reporte_evaluacion tbody').on('click', 'td>button.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_evaluacion.row(tr);

	$('#form_modal_evaluacion').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteserviciopersonalevaluacion_id').val(row.data().id);

	// Llenar campos
	$('#reporteserviciopersonalevaluacion_punto').val(row.data().reporteserviciopersonalevaluacion_punto);
	$('#reporteserviciopersonalevaluacion_lugar').val(row.data().reporteserviciopersonalevaluacion_lugar);
	$('#reporteserviciopersonalevaluacioncatalogo_id').val(row.data().reporteserviciopersonalevaluacioncatalogo_id);
	$('#reporteserviciopersonalevaluacion_titulo').val(row.data().reporteserviciopersonalevaluacion_titulo);
	$('#reporteserviciopersonalevaluacion_procedimiento').val(row.data().reporteserviciopersonalevaluacion_procedimiento);
	$('#reporteserviciopersonalevaluacion_descripcion').val(row.data().reporteserviciopersonalevaluacion_descripcion);
	$('#reporteserviciopersonalevaluacion_observacion').val(row.data().reporteserviciopersonalevaluacion_observacion);
	$('#reporteserviciopersonalevaluacion_cumplimiento').val(row.data().reporteserviciopersonalevaluacion_cumplimiento);

	if(row.data().reporteserviciopersonalevaluacion_cumplimiento == "Si cumple")
	{
		$('#reporteserviciopersonalevaluacion_cumplimiento').css('color', '#22C466');
	}
	else if(row.data().reporteserviciopersonalevaluacion_cumplimiento == "No cumple")
	{
		$('#reporteserviciopersonalevaluacion_cumplimiento').css('color', '#FF0000');
	}
	else
	{
		$('#reporteserviciopersonalevaluacion_cumplimiento').css('color', '#555555');
	}


	if (row.data().reporteserviciopersonalevaluacion_evidencia1)
	{
		var archivo = row.data().reporteserviciopersonalevaluacion_evidencia1;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/reporteserviciopersonalevaluacionevidencia/'+row.data().id+'/'+1;
		$('#reportefileevidencia1').dropify().data('dropify').destroy();
		$('#reportefileevidencia1').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
		$('#reportefileevidencia1').dropify().data('dropify').init();
		// $('#reportefileevidencia1').attr('required', false);
		
		// BOTON ELIMINAR
		$('#btneval_eliminaevidencia1').css('display', 'block');
	}
	else
	{
		// $('#reportefileevidencia1').attr('required', true);
		$('#reportefileevidencia1').dropify().data('dropify').resetPreview();
		$('#reportefileevidencia1').dropify().data('dropify').clearElement();

		// BOTON ELIMINAR
		$('#btneval_eliminaevidencia1').css('display', 'none');
	}


	if (row.data().reporteserviciopersonalevaluacion_evidencia2)
	{
		var archivo = row.data().reporteserviciopersonalevaluacion_evidencia2;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/reporteserviciopersonalevaluacionevidencia/'+row.data().id+'/'+2;
		$('#reportefileevidencia2').dropify().data('dropify').destroy();
		$('#reportefileevidencia2').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
		$('#reportefileevidencia2').dropify().data('dropify').init();
		// $('#reportefileevidencia2').attr('required', false);
		
		// BOTON ELIMINAR
		$('#btneval_eliminaevidencia2').css('display', 'block');
	}
	else
	{
		// $('#reportefileevidencia2').attr('required', true);
		$('#reportefileevidencia2').dropify().data('dropify').resetPreview();
		$('#reportefileevidencia2').dropify().data('dropify').clearElement();

		// BOTON ELIMINAR
		$('#btneval_eliminaevidencia2').css('display', 'none');
	}


	// Titulo del modal
	$('#modal_reporte_evaluacion .modal-title').html('Evaluación de las instalaciones y servicios');

	// mostrar modal
	$('#modal_reporte_evaluacion').modal({backdrop:false});
});


function elimina_evidencia(evaluacion_id, evidencia_opcion)
{
	// alert(evaluacion_id+' - '+evidencia_opcion);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Evidencia: "+evidencia_opcion,
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
				text: "Evidencia: "+evidencia_opcion,
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
						url: "/reporteserviciopersonalevaluacionevidenciaeliminar/"+evaluacion_id+'/'+evidencia_opcion,
						data:{},
						cache: false,
						success:function(dato)
						{
							if (parseInt(evidencia_opcion) == 1)
							{
								$('#reportefileevidencia1').val('');
								$('#reportefileevidencia1').dropify().data('dropify').resetPreview();
								$('#reportefileevidencia1').dropify().data('dropify').clearElement();

								$('#btneval_eliminaevidencia1').css('display', 'none');
							}
							else
							{
								$('#reportefileevidencia2').val('');
								$('#reportefileevidencia2').dropify().data('dropify').resetPreview();
								$('#reportefileevidencia2').dropify().data('dropify').clearElement();

								$('#btneval_eliminaevidencia2').css('display', 'none');
							}

							datatable_evaluacion.ajax.reload();

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


$('#tabla_reporte_evaluacion tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_evaluacion.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Evaluación de las instalaciones y servicios en:\n"+row.data().reporteserviciopersonalevaluacion_lugar,
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
				text: "Evaluación de las instalaciones y servicios en:\n"+row.data().reporteserviciopersonalevaluacion_lugar,
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
						url: "/reporteserviciopersonalevaluacioneliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							datatable_evaluacion.ajax.reload();

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


$("#botonguardar_modal_evaluacion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Evaluación de las instalaciones y servicios en:\n"+$("#reporteserviciopersonalevaluacion_lugar").val(),
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
				$('#form_modal_evaluacion').ajaxForm({
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
						reporte_instalacion: $("#reporte_instalacion").val()
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte
						reporteregistro_id = dato.reporteregistro_id;

						datatable_evaluacion.ajax.reload();

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
						$('#botonguardar_modal_evaluacion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_evaluacion').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_evaluacion').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_evaluacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_evaluacion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_evaluacion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_evaluacion').attr('disabled', false);

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
// PUNTO 8.3


$("#botonguardar_reporte_punto_8_3").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Descripción de medios de proliferación de fauna nociva",
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
				$('#form_reporte_punto_8_3').ajaxForm({
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

						menureporte_estado("menureporte_8_3", 1);

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
						$('#botonguardar_reporte_punto_8_3').html('Guardar punto 8.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_punto_8_3').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_punto_8_3').html('Guardando punto 8.3 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_punto_8_3').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_punto_8_3').html('Guardar punto 8.3 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_punto_8_3').attr('disabled', false);

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
// PUNTO 8.4


$("#botonguardar_reporte_punto_8_4").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Focos de desarrollo y anidación de fauna nociva",
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
				$('#form_reporte_punto_8_4').ajaxForm({
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

						menureporte_estado("menureporte_8_4", 1);

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
						$('#botonguardar_reporte_punto_8_4').html('Guardar punto 8.4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_punto_8_4').attr('disabled', false);
					},
					beforeSend: function()
					{
						$('#botonguardar_reporte_punto_8_4').html('Guardando punto 8.4 <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_reporte_punto_8_4').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_reporte_punto_8_4').html('Guardar punto 8.4 <i class="fa fa-save"></i>');
						$('#botonguardar_reporte_punto_8_4').attr('disabled', false);

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
						opcion: 12,
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
// RECOMENDACIONES


$("#botonguardar_reporte_recomendaciones").click(function()
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
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;

						menureporte_estado("menureporte_10", 1);

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
});


//=================================================
// RESPONSABLES DEL INFORME


$(document).ready(function()
{
	$('#reporteresponsable1documento').dropify({
		messages:
		{
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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

						menureporte_estado("menureporte_11", 1);


						$('#boton_descargarresponsabledoc1').css('display', 'block');
						$('#boton_descargarresponsabledoc2').css('display', 'block');


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
	window.open('/reporteserviciopersonalresponsabledocumento/'+reporteregistro_id+'/'+1+'/'+1);
});


$("#boton_descargarresponsabledoc2").click(function()
{
	window.open('/reporteserviciopersonalresponsabledocumento/'+reporteregistro_id+'/'+2+'/'+1);
});


//=================================================
// CONDICIONES INSEGURAS


$(document).ready(function()
{
	$('#condicioninsegurafoto1').dropify({
		messages:
		{
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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

	$('#condicioninsegurafoto2').dropify({
		messages:
		{
			'default': 'Arrastre la foto aquí o haga click',
			'replace': 'Arrastre la foto o haga clic para reemplazar',
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


	setTimeout(function()
	{
		tabla_reporte_condicionesinseguras(proyecto.id);
	}, 5000);
});


var datatable_reporte_condicionesinseguras = null;
function tabla_reporte_condicionesinseguras(proyecto_id)
{
	try 
	{
		var ruta = "/reporteserviciopersonalcondicionesinsegurastabla/"+proyecto_id;

		if (datatable_reporte_condicionesinseguras != null)
		{
			datatable_reporte_condicionesinseguras.clear().draw();
			datatable_reporte_condicionesinseguras.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporte_condicionesinseguras = $('#tabla_reporte_condicionesinseguras').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						if (parseInt(json.data.length) > 0)
						{
							$('#nota_12_2').css('display', 'none');
							$('#tabla_reporte_condicionesinseguras').css('display', 'inline-table');
							$('#img_12_2').css('display', 'block');
						}
						else
						{
							$('#tabla_reporte_condicionesinseguras').css('display', 'none');
							$('#img_12_2').css('display', 'none');
							$('#nota_12_2').css('display', 'block');
						}

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporte_condicionesinseguras '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_condicionesinseguras(proyecto_id)
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
						orderable: false,
					},
					{
						data: "reportearea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_actividad",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_rutinario",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_descripcion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_clasificacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_efectos",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_fuente",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_medio",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "categorias",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_probabilidad",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_exposicion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteserviciopersonalcondicioninsegura_consecuencia",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "resultado",
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
				lengthMenu: [[10, 20, 100, -1], [10, 20, 100, "Todos"]],
				// rowsGroup: [1, 2, 0], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
				paging: false,
				responsive: true,
				info: false,
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
					$(row).find('td:eq(13)').css('background', data.resultado_color);

					if((parseInt(data.resultado) >= 70 && parseInt(data.resultado) < 200) || (parseInt(data.resultado) < 20))
					{
						$(row).find('td:eq(13)').css('color', '#000000');
						$(row).find('td:eq(13)').css('font-weight', 'bold');
					}
					else
					{
						$(row).find('td:eq(13)').css('color', '#FFFFFF');
						$(row).find('td:eq(13)').css('font-weight', 'bold');
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_condicionesinseguras.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_condicionesinseguras(proyecto_id);
    }
}

$("#boton_reporte_nuevacondicionesinseguras").click(function()
{
	$('#form_modal_condicioninsegura').each(function(){
		this.reset();
	});

	$('#condicioninsegurafoto1').val('');
	$('#condicioninsegurafoto1').dropify().data('dropify').resetPreview();
	$('#condicioninsegurafoto1').dropify().data('dropify').clearElement();
	$('#condicioninsegurafoto1').attr('required', true);

	$('#condicioninsegurafoto2').val('');
	$('#condicioninsegurafoto2').dropify().data('dropify').resetPreview();
	$('#condicioninsegurafoto2').dropify().data('dropify').clearElement();

	// Campos Hidden
	$('#condicioninsegura_id').val(0);

	if (parseInt(categoriaspoe.length) > 0)
	{
		var categorias = '';
		$.each(categoriaspoe, function(index, val)
		{
			categorias += '<div class="switch" style="border: 0px #000 solid;">'+
								'<label style="margin-right: 6px;">'+
									'<input type="checkbox" class="checkbox_categoria" name="checkbox_categoria_id[]" value="'+val.id+'"/>'+
									'<span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>'+
								'</label>'+
								'<label>'+val.reportecategoria_nombre+'</label>'+
							'</div>';
		});

		$('#condicioninsegura_categorias').html(categorias);
	}
	else
	{
		$('#condicioninsegura_categorias').html('No hay categorias que mostrar del POE');
	}

	// Titulo del modal
	$('#modal_reporte_condicionesinseguras .modal-title').html('Nueva condición insegura');

	// mostrar modal
	$('#modal_reporte_condicionesinseguras').modal({backdrop:false});
});


function condicioninsegura_resultado()
{
	var probabilidad = 0;
	var exposicion = 0;
	var consecuencia = 0;

	if ($("#reporteserviciopersonalcondicioninsegura_probabilidad").val())
	{
		probabilidad = parseFloat($("#reporteserviciopersonalcondicioninsegura_probabilidad").val());
	}

	if ($("#reporteserviciopersonalcondicioninsegura_exposicion").val())
	{
		exposicion = parseFloat($("#reporteserviciopersonalcondicioninsegura_exposicion").val());
	}

	if ($("#reporteserviciopersonalcondicioninsegura_consecuencia").val())
	{
		consecuencia = parseFloat($("#reporteserviciopersonalcondicioninsegura_consecuencia").val());
	}

	var resultado = (probabilidad * exposicion * consecuencia);

	if (Number.isInteger(resultado))
	{
		$("#reporteserviciopersonalcondicioninsegura_resultado").val(resultado);
	}
	else
	{
		$("#reporteserviciopersonalcondicioninsegura_resultado").val(resultado.toFixed(1));
	}
}


$("#botonguardar_modal_condicioninsegura").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		var checkbox_seleccionados = 0;

		$('#condicioninsegura_categorias .checkbox_categoria').each(function(index, element)
		{
			if(element.checked)
			{
				checkbox_seleccionados += 1;
			} 
		});

		if (parseInt(checkbox_seleccionados) > 0)
		{
			swal({
				title: "¡Confirme que desea guardar!",
				text: "Condición insegura",
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
					$('#form_modal_condicioninsegura').ajaxForm({
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

							tabla_reporte_condicionesinseguras(proyecto.id);

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
							$('#botonguardar_modal_condicioninsegura').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_condicioninsegura').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_condicionesinseguras').modal('hide');
						},
						beforeSend: function()
						{
							$('#botonguardar_modal_condicioninsegura').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_condicioninsegura').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_modal_condicioninsegura').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_condicioninsegura').attr('disabled', false);

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
		else
		{
			// mensaje
			swal({
				title: "Pendiente",
				text: 'Falta seleccionar las categorías',
				type: "info", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				timer: 2000,
				showConfirmButton: false
			});

			return false;
		}
	}
});


$('#tabla_reporte_condicionesinseguras tbody').on('click', 'td>button.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_condicionesinseguras.row(tr);

	$('#form_modal_condicioninsegura').each(function(){
		this.reset();
	});

	$('#condicioninsegurafoto1').dropify().data('dropify').resetPreview();
	$('#condicioninsegurafoto1').dropify().data('dropify').clearElement();

	$('#condicioninsegurafoto2').dropify().data('dropify').resetPreview();
	$('#condicioninsegurafoto2').dropify().data('dropify').clearElement();

	// Campos Hidden
	$('#condicioninsegura_id').val(row.data().id);

	// Llenar campos
	$('#condicioninsegurareportearea_id').val(row.data().reportearea_id);
	$('#reporteserviciopersonalcondicioninsegura_actividad').val(row.data().reporteserviciopersonalcondicioninsegura_actividad);
	$('#reporteserviciopersonalcondicioninsegura_descripcion').val(row.data().reporteserviciopersonalcondicioninsegura_descripcion);
	$('#reporteserviciopersonalcondicioninsegura_clasificacion').val(row.data().reporteserviciopersonalcondicioninsegura_clasificacion);
	$('#reporteserviciopersonalcondicioninsegura_rutinario').val(row.data().reporteserviciopersonalcondicioninsegura_rutinario);
	$('#reporteserviciopersonalcondicioninsegura_efectos').val(row.data().reporteserviciopersonalcondicioninsegura_efectos);
	$('#reporteserviciopersonalcondicioninsegura_fuente').val(row.data().reporteserviciopersonalcondicioninsegura_fuente);
	$('#reporteserviciopersonalcondicioninsegura_medio').val(row.data().reporteserviciopersonalcondicioninsegura_medio);
	$('#reporteserviciopersonalcondicioninsegura_probabilidad').val(row.data().reporteserviciopersonalcondicioninsegura_probabilidad);
	$('#reporteserviciopersonalcondicioninsegura_exposicion').val(row.data().reporteserviciopersonalcondicioninsegura_exposicion);
	$('#reporteserviciopersonalcondicioninsegura_consecuencia').val(row.data().reporteserviciopersonalcondicioninsegura_consecuencia);

	condicioninsegura_resultado();

	if (row.data().reporteserviciopersonalcondicioninsegura_foto1)
	{
		var archivo = row.data().reporteserviciopersonalcondicioninsegura_foto1;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/reporteserviciopersonalcondicionesinsegurasfoto/'+row.data().id+'/'+1; // Foto 1
		$('#condicioninsegurafoto1').dropify().data('dropify').destroy();
		$('#condicioninsegurafoto1').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
		$('#condicioninsegurafoto1').dropify().data('dropify').init();

		$('#condicioninsegurafoto1').attr('required', false);
	}

	if (row.data().reporteserviciopersonalcondicioninsegura_foto2)
	{
		var archivo = row.data().reporteserviciopersonalcondicioninsegura_foto2;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/reporteserviciopersonalcondicionesinsegurasfoto/'+row.data().id+'/'+2; // Foto 2
		$('#condicioninsegurafoto2').dropify().data('dropify').destroy();
		$('#condicioninsegurafoto2').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
		$('#condicioninsegurafoto2').dropify().data('dropify').init();
	}

	// CATEGORIAS
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteserviciopersonalcondicionesinsegurascategorias/"+proyecto.id+"/"+row.data().id,
		data:{},
		cache: false,
		success:function(dato)
		{
			if (parseInt(dato.condicioninsegura_categorias.length) > 0)
			{
				var categorias = '';
				$.each(dato.condicioninsegura_categorias, function(index, val)
				{
					categorias += '<div class="switch" style="border: 0px #000 solid;">'+
										'<label style="margin-right: 6px;">'+
											'<input type="checkbox" class="checkbox_categoria" name="checkbox_categoria_id[]" value="'+val.id+'" '+val.checked+'/>'+
											'<span class="lever switch-col-light-blue" style="padding: 0px; margin: 0px;"></span>'+
										'</label>'+
										'<label>'+val.reportecategoria_nombre+'</label>'+
									'</div>';
				});

				$('#condicioninsegura_categorias').html(categorias);
			}
			else
			{
				$('#condicioninsegura_categorias').html('No hay categorias que mostrar del POE');
			}
		},
		beforeSend: function()
		{
			$('#condicioninsegura_categorias').html('<i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i>');
		},
		error: function(dato)
		{
			console.log('Error al consultar las categorías de las condiciones inseguras '+dato);
			return false;
		}
	});//Fin ajax

	// Titulo del modal
	$('#modal_reporte_condicionesinseguras .modal-title').html('Nueva condición insegura');

	// mostrar modal
	$('#modal_reporte_condicionesinseguras').modal({backdrop:false});
});


$('#tabla_reporte_condicionesinseguras tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_condicionesinseguras.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Condición insegura",
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
				text: "Condición insegura",
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
						url: '/reporteserviciopersonalcondicionesinseguraseliminar/'+row.data().id,
						data:{
							opcion: 20,
							proyecto_id: proyecto.id,
							agente_id: agente_id,
							agente_nombre: agente_nombre,
							reporteregistro_id: reporteregistro_id,
							catactivo_id: $("#reporte_catactivo_id").val(),
							reporte_instalacion: $("#reporte_instalacion").val(),
						},
						cache: false,
						success:function(dato)
						{
							tabla_reporte_condicionesinseguras(proyecto.id);

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
// ANEXOS

$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_anexosresultados(proyecto.id, agente_nombre);
	}, 8500);
});


var datatable_informeresultados = null;
function tabla_reporte_anexosresultados(proyecto_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteserviciopersonalanexosresultadostabla/"+proyecto_id+"/"+agente_nombre;

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
							tabla_reporte_anexosresultados(proyecto_id, agente_nombre);
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
		datatable_informeresultados.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_anexosresultados(proyecto_id, agente_nombre);
    }
}


$("#botonguardar_reporte_anexosresultados").click(function()
{
	// borrar campo filtro del DATATABLE
	// datatable_informeresultados.search($(this).val()).draw();
	datatable_informeresultados.search("").draw();


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
					$('#form_reporte_anexosresultados').ajaxForm({
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
							$('#botonguardar_reporte_anexosresultados').html('Guardar anexos <i class="fa fa-save"></i>');
							$('#botonguardar_reporte_anexosresultados').attr('disabled', false);
						},
						beforeSend: function()
						{
							$('#botonguardar_reporte_anexosresultados').html('Guardando anexos <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_reporte_anexosresultados').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_reporte_anexosresultados').html('Guardar anexos <i class="fa fa-save"></i>');
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
// PLANOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_planos(proyecto.id, agente_nombre);
	}, 6500);
});


var datatable_planos = null;
function tabla_reporte_planos(proyecto_id, agente_nombre)
{
	try 
	{
		var ruta = "/reporteserviciopersonalplanostabla/"+proyecto_id+"/"+agente_nombre;

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
						// menureporte_estado("menureporte_12_4", json.total);

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_planos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_planos(proyecto_id, agente_nombre);
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
		tabla_reporte_planos(proyecto_id, agente_nombre);
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

						// Actualiza menu
						menureporte_estado("menureporte_12_4", dato.total);

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
// REVISIONES


var ultimaversion_cancelada = 0;
var ultimarevision_id = 0;


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_revisiones(proyecto.id);
	}, 1000);
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
		var ruta = "/reporteserviciopersonalrevisionestabla/"+proyecto_id;

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
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
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
						url: "/reporteserviciopersonalrevisionconcluir/"+reporte_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tablas
							tabla_reporte_revisiones(proyecto.id);
							botoninforme_estado(dato.estado);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_evaluacion(proyecto.id);
							tabla_reporte_condicionesinseguras(proyecto.id);


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
							// Actualizar tablas
							tabla_reporte_revisiones(proyecto.id);
							botoninforme_estado(dato.estado);

							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_evaluacion(proyecto.id);
							tabla_reporte_condicionesinseguras(proyecto.id);
							

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
						// Actualizar tablas
						tabla_reporte_revisiones(proyecto.id);
						botoninforme_estado(dato.estado);

						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
						tabla_reporte_evaluacion(proyecto.id);
						tabla_reporte_condicionesinseguras(proyecto.id);


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


$("#boton_reporte_nuevarevision").click(function()
{
	if (ultimaversion_cancelada == 1)
	{
		if (parseInt(datatable_evaluacion.data().count()) > 0)
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


					setTimeout(function()
					{
						$.ajax({
							type: "POST",
							dataType: "json",
							url: "/reporteserviciopersonalword",
							data:{
								_token: document.querySelector('meta[name="csrf-token"]')['content'],
								proyecto_id: proyecto.id,
								agente_id: agente_id,
								agente_nombre: agente_nombre,
								reporteregistro_id: reporteregistro_id,
								ultimarevision_id: ultimarevision_id,
								crear_revision: 1,
							},
							cache: false,
							success:function(dato)
							{
								// Actualizar tablas
								tabla_reporte_revisiones(proyecto.id);
								botoninforme_estado(dato.estado);

								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
								tabla_reporte_evaluacion(proyecto.id);
								tabla_reporte_condicionesinseguras(proyecto.id);

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
								console.log("Error al crear nueva revisión");

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
				text: "En este informe aún no se ha realizado la evaluación.",
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


// //=================================================
// // GENERAR WORD


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
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/reporteserviciopersonalword",
				data:{
					_token: document.querySelector('meta[name="csrf-token"]')['content'],
					proyecto_id: proyecto.id,
					agente_id: agente_id,
					agente_nombre: agente_nombre,
					reporteregistro_id: reporteregistro_id,
					ultimarevision_id: ultimarevision_id,
					crear_revision: 0,
				},
				cache: false,
				success:function(dato)
				{
					ventana = window.open('/reporteserviciopersonalworddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


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
						text: "Al intentar generar el informe\n\n"+dato.msj,
						type: "error", // warning, error, success, info
						buttons: {
							visible: false, // true , false
						},
						timer: 2500,
						showConfirmButton: false
					});
					return false;
				}
			});//Fin ajax
		}
		else
		{
			ventana = window.open('/reporteserviciopersonalworddescargar/'+proyecto.id+"/"+row.data().id+"/"+ultimarevision_id);


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
		}
	}, 500);
});