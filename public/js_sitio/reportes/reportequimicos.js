

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
	var fromTop = $(this).scrollTop() + topMenuHeight + 0;
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
var agente_id = 15;
var agente_nombre = "Químicos";
var ruta_storage_guardar = '/reportequimicos';
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
	portadaInfo() // Info de la portada

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
		url: "/reportequimicosdatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			reporteregistro_id = 0;
			if (parseInt(dato.reporteregistro_id) > 0)
			{
				reporteregistro_id = parseInt(dato.reporteregistro_id);
				$('#reporteregistro_id').html(reporteregistro_id);
			}


			if (parseInt(dato.reporte_concluido) == 1 || parseInt(dato.reporte_cancelado) == 1)
			{
				botoninforme_estado(1);
			}
			else
			{
				botoninforme_estado(0);
			}


			// Tabla lista de químicos del proyecto
			tabla_reporte_quimicos(proyecto.id, reporteregistro_id);


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
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_metodologia_4_1').html(dato.reporte_metodologia_4_1);
			$('#reporte_metodologia_4_2').html(dato.reporte_metodologia_4_2);

			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			if (dato.reporte_ubicacioninstalacion.ubicacionfoto)
			{
				var archivo = dato.reporte_ubicacioninstalacion.ubicacionfoto;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportequimicosmapaubicacion/'+reporteregistro_id+'/'+0;
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

			if (dato.reporte_responsablesinforme.responsable1)
			{
				// Responsable 1
				$('#reporte_responsable1').val(dato.reporte_responsablesinforme.responsable1);
				$('#reporte_responsable1cargo').val(dato.reporte_responsablesinforme.responsable1cargo);
				$('#reporte_responsable1_documentobase64').val('');
				$('#reporteresponsable1documento').val('');
				var archivo = dato.reporte_responsablesinforme.responsable1documento;
				var extension = archivo.substring(archivo.lastIndexOf("."));
				var imagenUrl = '/reportequimicosresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+1+'/'+0;
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
				var imagenUrl = '/reportequimicosresponsabledocumento/'+dato.reporte_responsablesinforme.registro_id+'/'+2+'/'+0;
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
			menureporte_estado("menureporte_5_7", 1); // Prioridad de muestreo de las sustancias químicas
			menureporte_estado("menureporte_10", dato.reporte_responsablesinforme_guardado);
			menureporte_estado("menureporte_11_1", dato.reporte_memoriafotografica_guardado);
			menureporte_estado("menureporte_11_5", 1); //Incertidumbre de la medición


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

function datosgenerales_actualizar()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosdatosgenerales/"+proyecto.id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			$('#reporte_introduccion').html(dato.reporte_introduccion);
			$('#reporte_objetivogeneral').html(dato.reporte_objetivogeneral);
			$('#reporte_objetivoespecifico').html(dato.reporte_objetivoespecifico);
			$('#reporte_metodologia_4_1').html(dato.reporte_metodologia_4_1);
			$('#reporte_metodologia_4_2').html(dato.reporte_metodologia_4_2);
			$('#reporte_ubicacioninstalacion').html(dato.reporte_ubicacioninstalacion.ubicacion);
			$('#reporte_procesoinstalacion').html(dato.reporte_procesoinstalacion);
			$('#reporte_actividadprincipal').html(dato.reporte_actividadprincipal);
		},
		// beforeSend: function()
		// {
		// 	// $('#tabla_reporte_definiciones tbody').html('<tr><td colspan="5"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		// },
		error: function(dato)
		{
			$('#reporte_introduccion').html('Error al cargar los datos');
			$('#reporte_objetivogeneral').html('Error al cargar los datos');
			$('#reporte_objetivoespecifico').html('Error al cargar los datos');
			$('#reporte_metodologia_4_1').html('Error al cargar los datos');
			$('#reporte_metodologia_4_2').html('Error al cargar los datos');
			$('#reporte_ubicacioninstalacion').html('Error al cargar los datos');
			$('#reporte_procesoinstalacion').html('Error al cargar los datos');			

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


//=================================================
// LISTA DE QUIMICOS A EVALUAR


// $(document).ready(function()
// {
// 	setTimeout(function()
// 	{
// 		tabla_reporte_quimicos(proyecto.id, reporteregistro_id);
// 	}, 2000);
// });


var datatable_quimicos = null;
function tabla_reporte_quimicos(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicostabla/"+proyecto_id+"/"+reporteregistro_id;

		if (datatable_quimicos != null)
		{
			datatable_quimicos.clear().draw();
			datatable_quimicos.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_quimicos = $('#tabla_reporte_quimicos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						//alert(json.msj);

						// quimicos_lista = json.quimicos_lista;

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_quimicos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_quimicos(proyecto_id);
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
						data: "reportequimicosproyecto_parametro",
						defaultContent: "-",
						orderable: false,
					},
					// {
					// 	data: "boton_editar",
					// 	defaultContent: "-",
					// 	className: 'editar',
					// 	orderable: false,
					// },
					// {
					// 	data: "boton_eliminar",
					// 	defaultContent: "-",
					// 	orderable: false,
					// }
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				//rowsGroup: [0, 1, 5, 6], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: true,
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
				rowCallback: function(row, data, index)
				{
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					// $(row).find('td:eq(1)').css('text-align', 'justify');
				},
			});
		}

		// // Tooltip en DataTable
		// datatable_quimicos.on('draw', function ()
		// {
		// 	$('[data-toggle="tooltip"]').tooltip();
		// });
	}
	catch (exception)
	{
		tabla_reporte_quimicos(proyecto_id);
	}
}


//=================================================
// PARTIDAS IMPRESIÓN DE QUÍMICOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
	}, 1000);
});


var proveedores_opciones = '<option value=""></option>';
var catpartidasquimicos = '<option value=""></option>';
var catpartidasquimicos_utilizadas = '<option value=""></option>';
var parametros_checkbox = '';
var quimicos_lista = [];


var datatable_grupoquimicos = null;
function tabla_reporte_gruposquimicos(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosgrupostabla/"+proyecto_id+"/"+reporteregistro_id;

		if (datatable_grupoquimicos != null)
		{
			datatable_grupoquimicos.clear().draw();
			datatable_grupoquimicos.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_grupoquimicos = $('#tabla_reporte_gruposquimicos').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						catpartidasquimicos = json.catpartidasquimicos;
						catpartidasquimicos_utilizadas = json.catpartidasquimicos_utilizadas;
						proveedores_opciones = json.proveedores_opciones;
						parametros_checkbox = json.parametros_checkbox;
						quimicos_lista = json.quimicos_lista;

						menureporte_estado("menureporte-1", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_grupoquimicos '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_gruposquimicos(proyecto_id, reporteregistro_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					// {
					// 	data: "catreportequimicospartidas_numero",
					// 	defaultContent: "-",
					// 	orderable: false,
					// },
					{
						data: "clientepartidas_descripcion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosproyecto_parametro",
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
				rowsGroup: [0, 2, 3], //agrupar filas
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
		datatable_grupoquimicos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_gruposquimicos(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_nuevogrupoquimico").click(function()
{
	$('#form_modal_grupoquimicos').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#grupoquimicos_id').val(0);


	//Llenar campos
	$('#metodomuestreo_proveedor_id').html(proveedores_opciones);
	$('#catreportequimicospartidas_id').html(catpartidasquimicos);
	$('#quimicos_lista').html(parametros_checkbox);


	// Titulo del modal
	$('#modal_reporte_grupoquimicos .modal-title').html('Partida informe de químicos');


	// mostrar modal
	$('#modal_reporte_grupoquimicos').modal({backdrop:false});
});


$('#tabla_reporte_gruposquimicos tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_grupoquimicos.row(tr);


	$('#form_modal_grupoquimicos').each(function(){
		this.reset();
	});


	//Llenar campos
	$('#metodomuestreo_proveedor_id').html(proveedores_opciones);
	$('#catreportequimicospartidas_id').html(catpartidasquimicos);
	$('#quimicos_lista').html(parametros_checkbox);


	// Llenar campos
	$('#metodomuestreo_proveedor_id').val(row.data().proveedor_id);
	$('#catreportequimicospartidas_id').val(row.data().catreportequimicospartidas_id);


	// Recorrer tabla
	datatable_grupoquimicos.rows().every( function ( rowIdx, tableLoop, rowLoop )
	{
		var data = this.data();

		if (parseInt(row.data().catreportequimicospartidas_id) == parseInt(data.catreportequimicospartidas_id))
		{
			$('#parametro_'+data.reportequimicosproyecto_id).prop('checked', true);
		}
	});


	// Titulo del modal
	$('#modal_reporte_grupoquimicos .modal-title').html('Partida informe de químicos');


	// mostrar modal
	$('#modal_reporte_grupoquimicos').modal({backdrop:false});
});


$('#tabla_reporte_gruposquimicos tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_grupoquimicos.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Partida\n\n"+row.data().catreportequimicospartidas_numero+".- "+row.data().catreportequimicospartidas_descripcion,
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
				text: "Partida\n\n"+row.data().catreportequimicospartidas_numero+".- "+row.data().catreportequimicospartidas_descripcion,
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
						url: "/reportequimicosgrupoeliminar/"+row.data().proyecto_id+"/"+row.data().registro_id+"/"+row.data().catreportequimicospartidas_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);


							datosgenerales_actualizar();
							tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);
							tabla_reporte_recomendaciones(proyecto.id, reporteregistro_id, agente_nombre);


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


$("#botonguardar_modal_grupoquimicos").click(function()
{
	// valida campos vacios
	var seleccionados = 0;
	$('.checkbox_parametro').each(function()
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
				text: "Partida de químicos",
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
					$('#form_modal_grupoquimicos').ajaxForm({
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
							// Actualizar ID reporte
							reporteregistro_id = dato.reporteregistro_id;


							// Actualizar tabla
							tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);


							datosgenerales_actualizar();
							tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);
							tabla_reporte_recomendaciones(proyecto.id, reporteregistro_id, agente_nombre);


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
							$('#botonguardar_modal_grupoquimicos').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_grupoquimicos').attr('disabled', false);

							// cerrar modal
							$('#modal_reporte_grupoquimicos').modal('hide');
						},
						beforeSend: function()
						{
							$('#botonguardar_modal_grupoquimicos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							$('#botonguardar_modal_grupoquimicos').attr('disabled', true);
						},
						error: function(dato)
						{
							// actualiza boton
							$('#botonguardar_modal_grupoquimicos').html('Guardar <i class="fa fa-save"></i>');
							$('#botonguardar_modal_grupoquimicos').attr('disabled', false);

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
			title: "Seleccione parametro (s)",
			text: "Antes de guardar debe seleccionar uno o más parametros",
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
		var ruta = "/reportequimicostabladefiniciones/"+proyecto_id+"/"+agente_nombre+"/"+reporteregistro_id;

		if (datatable_reportedefiniciones != null)
		{
			datatable_reportedefiniciones.clear().draw();
			datatable_reportedefiniciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reportedefiniciones = $('#tabla_reporte_definiciones').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						//alert(json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reportedefiniciones '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_definiciones(proyecto_id, agente_nombre, reporteregistro_id);
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "concepto",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "descripcion_fuente",
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
				//rowsGroup: [0, 1, 5, 6], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: true,
				paging: true,
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
				rowCallback: function(row, data, index)
				{
					// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

					// if(data.reporteiluminacionpuntos_nopunto == 2)
					// {
					// 	$(row).find('td:eq(12)').css('background', 'red');
					// 	$(row).find('td:eq(12)').css('color', 'white');
					// }

					$(row).find('td:eq(1)').css('text-align', 'justify');
				},
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
							url: "/reporteruidodefinicioneliminar/"+row.data().id,
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
	window.open('/reportequimicosmapaubicacion/'+reporteregistro_id+'/'+1);
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
						opcion: 8,
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

						// tabla_reporte_revisiones(proyecto.id);

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
		tabla_reporte_categorias(proyecto.id, reporteregistro_id);
	}, 2500);
});


var datatable_reportecategorias = null;
function tabla_reporte_categorias(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicoscategorias/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

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


						// if (parseInt(json.data.length) > 0 && parseInt(json.total_singuardar) == 0)
						// {
						// 	menureporte_estado("menureporte_5_3", 1);
						// }
						// else
						// {
						// 	menureporte_estado("menureporte_5_3", 0);
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
						"data": "reportequimicoscategoria_nombre",
						"defaultContent": "-"
					},
					{
						"data": "reportequimicoscategoria_total",
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
	$('#reportequimicoscategoria_nombre').val(row.data().reportequimicoscategoria_nombre);
	$('#reportequimicoscategoria_total').val(row.data().reportequimicoscategoria_total);


	if (areas_poe == 1)
	{
		$('#reportequimicoscategoria_nombre').attr('required', false);
		$('#reportequimicoscategoria_nombre').attr('disabled', true);

		$('#reportequimicoscategoria_total').attr('required', false);
		$('#reportequimicoscategoria_total').attr('disabled', true);
	}
	else
	{
		$('#reportequimicoscategoria_nombre').attr('disabled', false);
		$('#reportequimicoscategoria_nombre').attr('required', true);

		$('#reportequimicoscategoria_total').attr('disabled', false);
		$('#reportequimicoscategoria_total').attr('required', true);
	}


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
		text: "La categoría: "+row.data().reportequimicoscategoria_nombre,
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
				text: "La categoría: "+row.data().reportequimicoscategoria_nombre,
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
						url: "/reportequimicoscategoriaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);

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
			text: "Categoría: "+$("#reportequimicoscategoria_nombre").val(),
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


						// Actualizar tablas
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_7(proyecto_id, reporteregistro_id)
						tabla_reporte_7_1(proyecto_id, reporteregistro_id)
						tabla_reporte_matriz(proyecto_id, reporteregistro_id)
						reporte_dashboard(proyecto_id, reporteregistro_id, 0)


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
	}, 3000);
});


var datatable_reporteareas = null;
function tabla_reporte_areas(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosareas/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

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
							menureporte_estado("menureporte_5_3", 1);
							menureporte_estado("menureporte_5_5", 1);
							menureporte_estado("menureporte_5_6", 1);
							menureporte_estado("menureporte_6_1", 1);
						}
						else
						{
							menureporte_estado("menureporte_5_3", 0);
							menureporte_estado("menureporte_5_5", 0);
							menureporte_estado("menureporte_5_6", 0);
							menureporte_estado("menureporte_6_1", 0);
						}


						if (parseInt(areas_poe) == 1)
						{
							$("#boton_reporte_nuevacategoria").attr('disabled', true);
							$("#boton_reporte_nuevaarea").attr('disabled', true);
						}


						// Tablas
						tabla_reporte_5_5(json.tabla_5_5);
						tabla_reporte_5_6(json.tabla_5_6);
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
						"data": "reportequimicosarea_instalacion",
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
						"data": "reportequimicosareacategoria_total",
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

	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosareascategorias/"+proyecto.id+"/"+reporteregistro_id+"/0/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function()
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="4" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html('Nueva área');

	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});
});


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


var datatable_areacategoria = null;
function tabla_areacategorias(tbody)
{
	if (datatable_areacategoria != null)
	{
		datatable_areacategoria.destroy();
	}

	$('#tabla_areacategorias tbody').html(tbody);

	datatable_areacategoria = $('#tabla_areacategorias').DataTable({
		"scrollY": "200px",
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
	$('#reportequimicosarea_instalacion').val(row.data().reportequimicosarea_instalacion);
	$('#reportequimicosarea_nombre').val(row.data().reportequimicosarea_nombre);
	$('#reportequimicosarea_numorden').val(row.data().reportequimicosarea_numorden);
	$('#reportequimicosarea_porcientooperacion').val(row.data().reportequimicosarea_porcientooperacion);
	$('#reportequimicosarea_maquinaria').val(row.data().reportequimicosarea_maquinaria);
	$('#reportequimicosarea_contaminante').val(row.data().reportequimicosarea_contaminante);
	$('#reportequimicosarea_caracteristica').val(row.data().reportequimicosarea_caracteristica);


	if (areas_poe == 1)
	{
		$('#reportequimicosarea_instalacion').attr('disabled', false);
		$('#reportequimicosarea_instalacion').attr('readonly', true);

		$('#reportequimicosarea_nombre').attr('disabled', false);
		$('#reportequimicosarea_nombre').attr('readonly', true);

		$('#reportequimicosarea_numorden').attr('disabled', false);
		$('#reportequimicosarea_numorden').attr('readonly', true);

		// $('#reportequimicosarea_porcientooperacion').attr('disabled', false);
		// $('#reportequimicosarea_porcientooperacion').attr('readonly', true);
	}
	else
	{
		$('#reportequimicosarea_instalacion').attr('readonly', false);
		$('#reportequimicosarea_instalacion').attr('disabled', true);

		$('#reportequimicosarea_nombre').attr('readonly', false);
		$('#reportequimicosarea_nombre').attr('disabled', true);

		$('#reportequimicosarea_numorden').attr('readonly', false);
		$('#reportequimicosarea_numorden').attr('disabled', true);

		// $('#reportequimicosarea_porcientooperacion').attr('readonly', false);
		// $('#reportequimicosarea_porcientooperacion').attr('disabled', true);
	}


	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosareascategorias/"+proyecto.id+"/"+reporteregistro_id+"/"+row.data().id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function()
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="4" style="text-align: center;">Error al cargar las categorías</td></tr>');			
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
		text: "El área: "+row.data().reportequimicosarea_nombre,
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
				text: "El área: "+row.data().reportequimicosarea_nombre,
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
						url: "/reportequimicosareaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
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
			text: "Área: "+$("#reportequimicosarea_nombre").val(),
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
						opcion: 10,
						proyecto_id: proyecto.id,
						agente_id: agente_id,
						agente_nombre: agente_nombre,
						reporteregistro_id: reporteregistro_id,
						catactivo_id: $("#reporte_catactivo_id").val(),
						reporte_instalacion: $("#reporte_instalacion").val(),
						areas_poe: areas_poe
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar ID reporte						
						reporteregistro_id = dato.reporteregistro_id;


						// Actualizar tablas
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_7(proyecto_id, reporteregistro_id)
						tabla_reporte_7_1(proyecto_id, reporteregistro_id)
						tabla_reporte_matriz(proyecto_id, reporteregistro_id)
						reporte_dashboard(proyecto_id, reporteregistro_id, 0)


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
		"rowsGroup": [0, 1], //agrupar filas
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


var datatable_reporte_5_6 = null;
function tabla_reporte_5_6(tbody)
{
	if (datatable_reporte_5_6 != null)
	{
		datatable_reporte_5_6.destroy();
	}

	$('#tabla_reporte_5_6 tbody').html(tbody);

	datatable_reporte_5_6 = $('#tabla_reporte_5_6').DataTable({
		"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		"rowsGroup": [0, 1, 2, 3, 5, 6], //agrupar filas
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
		"rowsGroup": [0, 2, 1], //agrupar filas
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
// EQUIPO PROTECCION PERSONAL


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_epp(proyecto.id, reporteregistro_id);
	}, 3500);
});


var datatable_epp = null;
function tabla_reporte_epp(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosepptabla/"+proyecto_id+"/"+reporteregistro_id;

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
						data: "reportequimicosepp_partecuerpo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosepp_equipo",
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
		datatable_epp.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_epp(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_nuevoepp").click(function()
{
	$('#form_modal_epp').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteepp_id').val(0);

	// Titulo del modal
	$('#modal_reporte_epp .modal-title').html('Nueva equipo de protección personal');

	// mostrar modal
	$('#modal_reporte_epp').modal({backdrop:false});
});


$('#tabla_reporte_epp tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_epp.row(tr);

	$('#form_modal_epp').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteepp_id').val(row.data().id);

	// Llenar campos
	$('#reportequimicosepp_partecuerpo').val(row.data().reportequimicosepp_partecuerpo);
	$('#reportequimicosepp_equipo').val(row.data().reportequimicosepp_equipo);

	// Titulo del modal
	$('#modal_reporte_epp .modal-title').html('Equipo de protección personal');

	// mostrar modal
	$('#modal_reporte_epp').modal({backdrop:false});
});


$('#tabla_reporte_epp tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_epp.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "E.P.P.: "+row.data().reportequimicosepp_partecuerpo,
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
				text: "E.P.P.: "+row.data().reportequimicosepp_partecuerpo,
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
						url: "/reportequimicoseppeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_epp(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_epp").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
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
		function(isConfirm)
		{
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();

				// enviar datos
				$('#form_modal_epp').ajaxForm({
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

						tabla_reporte_epp(proyecto.id, reporteregistro_id);

						// Actualizar tabla
						// tabla_reporte_areas(proyecto.id, reporteregistro_id);

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
						$('#botonguardar_modal_epp').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_epp').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_epp').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_epp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_epp').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_epp').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_epp').attr('disabled', false);

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
// PRIORIDAD DE MUESTREO DE LAS SUSTANCIAS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_5_7(proyecto.recsensorial_id);
	}, 4000);
});


var datatable_sustacias = null;
function tabla_reporte_5_7(recsensorial_id)
{
	try 
	{
		var ruta = "/reportequimicossustanciasreconocimiento/"+recsensorial_id;

		if (datatable_sustacias != null)
		{
			datatable_sustacias.clear().draw();
			datatable_sustacias.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_sustacias = $('#tabla_reporte_5_7').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// menureporte_estado("menureporte_5_7", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_sustacias '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_5_7(recsensorial_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "sustancia_nombre",
						defaultContent: "-",
						// className: '',
						orderable: false,
					},
					{
						data: "catsustanciacomponente_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "ponderacion_cantidad",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "ponderacion_riesgo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "ponderacion_volatilidad",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "TOTAL",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "PRIORIDAD",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [0, 2, 3, 4, 5, 6], //agrupar filas
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

					if(data.COLOR == "#FF0000")
					{
						$(row).find('td:eq(6)').css('color', '#FFFFFF');
					}
					else
					{
						$(row).find('td:eq(6)').css('color', '#000000');
					}

					$(row).find('td:eq(6)').css('font-weight', 'bold');
					$(row).find('td:eq(6)').css('background', data.COLOR);
				},
			});
		}

		// Tooltip en DataTable
		datatable_sustacias.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_5_7(recsensorial_id);
    }
}


//=================================================
// TABLA RESULTADOS DE EVALUACION


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_7(proyecto.id, reporteregistro_id);
	}, 4500);
});


var datatable_reporte_7 = null;
function tabla_reporte_7(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosevaluaciontabla/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

		if (datatable_reporte_7 != null)
		{
			datatable_reporte_7.clear().draw();
			datatable_reporte_7.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporte_7 = $('#tabla_reporte_7').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_7", parseInt(json.total));
						menureporte_estado("menureporte_7_2", parseInt(json.total));

						// Sustancias evaluadas
						menureporte_estado("menureporte_11_3", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporte_7 '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_7(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reportequimicosevaluacion_punto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosarea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicoscategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacionparametro_parametro",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacionparametro_metodo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "concentracion_texto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "valorlimite",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "limitesuperior",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacionparametro_periodo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "resultado_texto",
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
				rowsGroup: [0, 1, 2, 10, 11], //agrupar filas
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
					// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);
					$(row).find('td:eq(9)').css('background', data.resultado_color);
					$(row).find('td:eq(9)').css('font-weight', 'bold');

					if(data.resultado_texto == "Dentro de norma")
					{
						$(row).find('td:eq(9)').css('color', '#000000');
					}
					else
					{
						$(row).find('td:eq(9)').css('color', '#FFFFFF');						
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_7.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_7(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_puntoevalucion").click(function()
{
	$('#form_modal_puntoevaluacion').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#puntoevaluacion_id').val(0);

	// Parametros
	var quimicos_lista_opciones = '';
	$.each( quimicos_lista, function( key, value )
	{
		quimicos_lista_opciones += '<option value="'+value+'">'+value+'</option>';
	});

	// Tabla parametros
	$('#tabla_evaluacion_parametros tbody').html('<tr>'+
														'<td>'+
															'<select class="custom-select form-control" name="reportequimicosevaluacionparametro_parametro[]" required>'+
																'<option value=""></option>'+
																quimicos_lista_opciones+
															'</select>'+
														'</td>'+
														'<td>'+
															'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_metodo[]" required></textarea>'+
														'</td>'+
														'<td>'+
															'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_concentracion[]" value="" required>'+
														'</td>'+
														'<td>'+
															'<input type="number" step="any" class="form-control" name="reportequimicosevaluacionparametro_valorlimite[]" value="" required>'+
														'</td>'+
														'<td>'+
															'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_limitesuperior[]" value="" required>'+
														'</td>'+
														'<td>'+
															'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_periodo[]" required></textarea>'+
														'</td>'+
														'<td>'+
															'<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>'+
														'</td>'+
													'</tr>');

	$('[data-toggle="tooltip"]').tooltip();

	// Campo select areas
	$('#reportequimicosevaluacion_areaid').html(selectareas);

	// Campo select categorias
	$('#reportequimicosevaluacion_categoriaid').html('<option value=""></option>');

	// Titulo del modal
	$('#modal_reporte_puntoevaluacion .modal-title').html('Punto de evaluación');

	// mostrar modal
	$('#modal_reporte_puntoevaluacion').modal({backdrop:false});
});


function evaluacion_areacategorias(area_id)
{
	if (parseInt(area_id) > 0)
	{
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/reportequimicosevaluacionareacategorias/"+area_id+"/"+reporteregistro_id+"/"+areas_poe,
			data:{},
			cache: false,
			success:function(dato)
			{				
				$('#reportequimicosevaluacion_categoriaid').html(dato.categorias_opciones);				
			},
			beforeSend: function()
			{
				$('#reportequimicosevaluacion_categoriaid').html('<option value=""></option>');
			},
			error: function(dato)
			{
				$('#reportequimicosevaluacion_categoriaid').html('<option value="">Error al consultar las categorías</option>');
				return false;
			}
		});//Fin ajax
	}
	else
	{
		$('#reportequimicosevaluacion_categoriaid').html('<option value=""></option>');
	}
}


$("#boton_evaluacion_nuevoparametro").click(function() // Agregar fila TABLA
{
	// Parametros
	var quimicos_lista_opciones = '';
	$.each( quimicos_lista, function( key, value )
	{
		quimicos_lista_opciones += '<option value="'+value+'">'+value+'</option>';
	});


	$('#tabla_evaluacion_parametros > tbody').append('<tr>'+
															'<td>'+
																'<select class="custom-select form-control" name="reportequimicosevaluacionparametro_parametro[]" required>'+
																	'<option value=""></option>'+
																	quimicos_lista_opciones+
																'</select>'+
															'</td>'+
															'<td>'+
																'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_metodo[]" required></textarea>'+
															'</td>'+
															'<td>'+
																'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_concentracion[]" value="" required>'+
															'</td>'+
															'<td>'+
																'<input type="number" step="any" class="form-control" name="reportequimicosevaluacionparametro_valorlimite[]" value="" required>'+
															'</td>'+
															'<td>'+
																'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_limitesuperior[]" value="" required>'+
															'</td>'+
															'<td>'+
																'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_periodo[]" required></textarea>'+
															'</td>'+
															'<td>'+
																'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>'+
															'</td>'+
														'</tr>');

    // desplazar a la ultima fila de la tabla
    $('#div_tabla_evaluacion_parametros').animate({
    	scrollTop: $('#tabla_evaluacion_parametros > tbody > tr:last').position().top //ultima fila
    }, 500);
});


$('#tabla_evaluacion_parametros tbody').on('click', 'td>button.eliminar', function() // Eliminar fila categoría
{
    // obtener fila tabla
    var fila = $(this);
    
    // confirmar
    swal({   
        title: "¿Eliminar parámetro?",   
        text: "Quitar de la lista",   
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

            var tr = fila.closest('tr');
            fila.closest("tr").remove(); // eliminar fila TR

            // mensaje
            swal({
                title: "Correcto",
                 text: "Parámetro eliminado de la lista",
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1000,
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
});


$('#tabla_reporte_7 tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7.row(tr);


	$('#form_modal_puntoevaluacion').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#puntoevaluacion_id').val(row.data().id);


	// Campo select areas
	$('#reportequimicosevaluacion_areaid').html(selectareas);


	// Campo select Categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosevaluacionareacategorias/"+row.data().reportequimicosarea_id+"/"+reporteregistro_id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{				
			$('#reportequimicosevaluacion_categoriaid').html(dato.categorias_opciones);
			$('#reportequimicosevaluacion_categoriaid').val(row.data().reportequimicoscategoria_id);
		},
		beforeSend: function()
		{
			$('#reportequimicosevaluacion_categoriaid').html('<option value=""></option>');
		},
		error: function(dato)
		{
			$('#reportequimicosevaluacion_categoriaid').html('<option value="">Error al consultar las categorías</option>');
			return false;
		}
	});//Fin ajax


	// Llenar campos
	$('#reportequimicosevaluacion_punto').val(row.data().reportequimicosevaluacion_punto);
	$('#reportequimicosevaluacion_areaid').val(row.data().reportequimicosarea_id);
	$('#reportequimicosevaluacion_nombre').val(row.data().reportequimicosevaluacion_nombre);
	$('#reportequimicosevaluacion_ficha').val(row.data().reportequimicosevaluacion_ficha);
	$('#reportequimicosevaluacion_geo').val(row.data().reportequimicosevaluacion_geo);
	$('#reportequimicosevaluacion_total').val(row.data().reportequimicosevaluacion_total);


	// Recorrer tabla
	var contador = 0;
	$('#tabla_evaluacion_parametros > tbody').html('');
	datatable_reporte_7.rows().every( function ( rowIdx, tableLoop, rowLoop )
	{
		var data = this.data();

		if (parseInt(row.data().id) == parseInt(data.id))
		{
			// Parametros
			var quimicos_lista_opciones = '';
			$.each( quimicos_lista, function( key, value )
			{
				if (data.reportequimicosevaluacionparametro_parametro == value)
				{
					quimicos_lista_opciones += '<option value="'+value+'" selected>'+value+'</option>';
				}
				else
				{
					quimicos_lista_opciones += '<option value="'+value+'">'+value+'</option>';
				}
			});


			var boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button>';
			if (contador == 0)
			{
				boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button>';
			}


			$('#tabla_evaluacion_parametros > tbody').append('<tr>'+
																	'<td>'+
																		'<select class="custom-select form-control" name="reportequimicosevaluacionparametro_parametro[]" required>'+
																			'<option value=""></option>'+
																			quimicos_lista_opciones+
																		'</select>'+
																	'</td>'+
																	'<td>'+
																		'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_metodo[]" required>'+data.reportequimicosevaluacionparametro_metodo+'</textarea>'+
																	'</td>'+
																	'<td>'+
																		'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_concentracion[]" value="'+data.concentracion_texto+'" required>'+
																	'</td>'+
																	'<td>'+
																		'<input type="number" step="any" class="form-control" name="reportequimicosevaluacionparametro_valorlimite[]" value="'+data.valorlimite+'" required>'+
																	'</td>'+
																	'<td>'+
																		'<input type="text" class="form-control" name="reportequimicosevaluacionparametro_limitesuperior[]" value="'+data.limitesuperior+'" required>'+
																	'</td>'+
																	'<td>'+
																		'<textarea class="form-control" rows="2" name="reportequimicosevaluacionparametro_periodo[]" required>'+data.reportequimicosevaluacionparametro_periodo+'</textarea>'+
																	'</td>'+
																	'<td>'+
																		boton_eliminar+
																	'</td>'+
																'</tr>');


			contador += 1;
		}
	});


	// Titulo del modal
	$('#modal_reporte_puntoevaluacion .modal-title').html('Punto de evaluación');


	// mostrar modal
	$('#modal_reporte_puntoevaluacion').modal({backdrop:false});
});


$('#tabla_reporte_7 tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Punto ["+row.data().reportequimicosevaluacion_punto+"] de la evaluación de químicos",
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
				text: "Punto ["+row.data().reportequimicosevaluacion_punto+"] de la evaluación de químicos",
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
						url: "/reportequimicosevaluacioneliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_7(proyecto.id, reporteregistro_id);
							tabla_reporte_matriz(proyecto.id, reporteregistro_id);
							tabla_reporte_quimicosevaluados(proyecto.id, reporteregistro_id);
							reporte_dashboard(proyecto.id, reporteregistro_id, 0);

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


$("#botonguardar_modal_puntoevaluacion").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Punto de evaluación",
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
				$('#form_modal_puntoevaluacion').ajaxForm({
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

						tabla_reporte_7(proyecto.id, reporteregistro_id);
						tabla_reporte_matriz(proyecto.id, reporteregistro_id);
						tabla_reporte_quimicosevaluados(proyecto.id, reporteregistro_id);
						reporte_dashboard(proyecto.id, reporteregistro_id, 0);

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
						$('#botonguardar_modal_puntoevaluacion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_puntoevaluacion').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_puntoevaluacion').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_puntoevaluacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_puntoevaluacion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_puntoevaluacion').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_puntoevaluacion').attr('disabled', false);

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
// METODO DE MUESTREO


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_7_1(proyecto.id, reporteregistro_id);
	}, 5000);
});


var datatable_reporte_7_1 = null;
function tabla_reporte_7_1(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosmetodomuestreotabla/"+proyecto_id+"/"+reporteregistro_id;

		if (datatable_reporte_7_1 != null)
		{
			datatable_reporte_7_1.clear().draw();
			datatable_reporte_7_1.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_reporte_7_1 = $('#tabla_reporte_7_1').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						menureporte_estado("menureporte_7_1", parseInt(json.total));

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_reporte_7_1 '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_7_1(proyecto_id, reporteregistro_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reportequimicosmetodomuestreo_parametro",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosmetodomuestreo_metodo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosmetodomuestreo_puntos",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosmetodomuestreo_tipo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "flujos",
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
				// rowsGroup: [0, 1, 2, 10, 11], //agrupar filas
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
					// // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);
					$(row).find('td:eq(9)').css('background', data.resultado_color);
					$(row).find('td:eq(9)').css('font-weight', 'bold');

					if(data.resultado_texto == "Dentro de norma")
					{
						$(row).find('td:eq(9)').css('color', '#000000');
					}
					else
					{
						$(row).find('td:eq(9)').css('color', '#FFFFFF');						
					}
				},
			});
		}

		// Tooltip en DataTable
		datatable_reporte_7_1.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_7_1(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_metodomuestreo").click(function()
{
	$('#form_modal_metodomuestreo').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#metodomuestreo_id').val(0);

	// Parametros
	var quimicos_lista_opciones = '<option value=""></option>';
	$.each( quimicos_lista, function( key, value )
	{
		quimicos_lista_opciones += '<option value="'+value+'">'+value+'</option>';
	});

	// Campo select parametros
	$('#reportequimicosmetodomuestreo_parametro').html(quimicos_lista_opciones);


	// Tabla flujo de muestreo
	$('#tabla_metodomuestreo_flujo tbody').html('<tr>'+
													'<td colspan="2">Especificar puntos evaluados</td>'+
												'</tr>');

	// Titulo del modal
	$('#modal_reporte_metodomuestreo .modal-title').html('Método de muestreo');

	// mostrar modal
	$('#modal_reporte_metodomuestreo').modal({backdrop:false});
});


function metodomuestreo_flujos(puntosevaluados_total)
{
	if (parseInt(puntosevaluados_total) > 0)
	{
		$('#tabla_metodomuestreo_flujo tbody').html('');


		for (var i = 0; i < parseInt(puntosevaluados_total); i++)
		{
			$('#tabla_metodomuestreo_flujo > tbody').append('<tr>'+
																'<td>'+(i+1)+'</td>'+
																'<td><input type="number" step="any" min="0" class="form-control" name="reportequimicosmetodomuestreo_flujo[]" required></td>'+
															'</tr>');
		}
	}
	else
	{		
		$('#tabla_metodomuestreo_flujo tbody').html('<tr>'+
														'<td colspan="2">Puntos evaluados debe ser > 0</td>'+
													'</tr>');
	}
}


$('#tabla_reporte_7_1 tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_1.row(tr);


	$('#form_modal_metodomuestreo').each(function(){
		this.reset();
	});


	// Campos Hidden
	// $('#puntoevaluacion_id').val(row.data().id);


	// Parametros
	var quimicos_lista_opciones = '<option value=""></option>';
	$.each( quimicos_lista, function( key, value )
	{
		quimicos_lista_opciones += '<option value="'+value+'">'+value+'</option>';
	});

	// Campo select parametros
	$('#reportequimicosmetodomuestreo_parametro').html(quimicos_lista_opciones);


	// Llenar campos
	$('#reportequimicosmetodomuestreo_parametro').val(row.data().reportequimicosmetodomuestreo_parametro);
	$('#reportequimicosmetodomuestreo_puntos').val(row.data().reportequimicosmetodomuestreo_puntos);
	$('#reportequimicosmetodomuestreo_metodo').val(row.data().reportequimicosmetodomuestreo_metodo);
	$('#reportequimicosmetodomuestreo_tipo').val(row.data().reportequimicosmetodomuestreo_tipo);


	// Tabla flujo de muestreo
	$('#tabla_metodomuestreo_flujo tbody').html('');


	// Taba flujos
	var flujos = row.data().reportequimicosmetodomuestreo_flujo.split(',');
	for (var i=0; i < flujos.length; i++)
	{
		// alert(flujos[i]);
		$('#tabla_metodomuestreo_flujo > tbody').append('<tr>'+
															'<td>'+(i+1)+'</td>'+
															'<td><input type="number" step="any" min="0" class="form-control" name="reportequimicosmetodomuestreo_flujo[]" value="'+flujos[i]+'" required></td>'+
														'</tr>');
	}


	// Titulo del modal
	$('#modal_reporte_metodomuestreo .modal-title').html('Método de muestreo');


	// mostrar modal
	$('#modal_reporte_metodomuestreo').modal({backdrop:false});
});


$('#tabla_reporte_7_1 tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_reporte_7_1.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Método de muestreo de ["+row.data().reportequimicosmetodomuestreo_parametro+"]",
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
				text: "Método de muestreo de ["+row.data().reportequimicosmetodomuestreo_parametro+"]",
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
						url: "/reportequimicosmetodomuestreoeliminar/"+row.data().proyecto_id+"/"+row.data().registro_id+"/"+row.data().reportequimicosmetodomuestreo_parametro,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_7_1(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_metodomuestreo").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Método de muestreo de "+$("#reportequimicosmetodomuestreo_parametro option:selected").text(),
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
				$('#form_modal_metodomuestreo').ajaxForm({
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

						// Actualizar tabla
						tabla_reporte_7_1(proyecto.id, reporteregistro_id);

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
						$('#botonguardar_modal_metodomuestreo').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_metodomuestreo').attr('disabled', false);

						// cerrar modal
						$('#modal_reporte_metodomuestreo').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_metodomuestreo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_metodomuestreo').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_metodomuestreo').html('Guardar <i class="fa fa-save"></i>');
						$('#botonguardar_modal_metodomuestreo').attr('disabled', false);

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
// MATRIZ DE EXPOSICIÓN LABORAL


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
		var ruta = "/reportequimicosmatriztabla/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;


		if (datatable_reporte_matriz != null)
		{
			datatable_reporte_matriz.clear().draw();
			datatable_reporte_matriz.ajax.url(ruta).load();
		}
		else
		{
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
						menureporte_estado("menureporte_7_7", parseInt(json.total));

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
						data: "reportequimicosarea_instalacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosarea_nombre",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "reportequimicosevaluacion_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacion_ficha",
						defaultContent: "-",
						orderable: true,
					},
					{
						data: "reportequimicoscategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacion_total",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportequimicosevaluacion_geo",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "parametros",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [1, 2, 3, 4, 10, 0], //agrupar filas
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
	catch (exception)
	{
		tabla_reporte_matriz(proyecto_id, reporteregistro_id);
    }
}


//=================================================
// CONCLUSION


var conclusion_catalogo = '';


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);
	}, 6000);
});


var datatable_conclusiones = null;
function tabla_reporte_conclusiones(proyecto_id, reporteregistro_id)
{
	try 
	{
		var ruta = "/reportequimicosconclusionestabla/"+proyecto_id+"/"+reporteregistro_id;

		if (datatable_conclusiones != null)
		{
			datatable_conclusiones.clear().draw();
			datatable_conclusiones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
		    datatable_conclusiones = $('#tabla_reporte_conclusiones').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						conclusion_catalogo = json.conclusion_catalogo;

						menureporte_estado("menureporte_8", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en tabla_reporte_conclusiones '+code);
						if (numeroejecucion <= 1)
						{
							tabla_reporte_conclusiones(proyecto_id, reporteregistro_id);
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
						data: "partida",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "conclusion",
						defaultContent: "-",
						className: 'justificado',
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
				},
			});
		}

		// Tooltip en DataTable
		datatable_conclusiones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_reporte_conclusiones(proyecto_id, reporteregistro_id);
    }
}


$("#boton_reporte_nuevaconclusion").click(function()
{
	$('#form_modal_conclusion').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reporteconclusion_id').val(0);

	// Llenar campos select partidas quimicos
	$('#reporteconclusion_catreportequimicospartidas_id').html(catpartidasquimicos_utilizadas);


	// Llenar conclusion catalogo
	$('#reportequimicosconclusion_conclusion').val(conclusion_catalogo);
	

	// Titulo del modal
	$('#modal_reporte_conclusion .modal-title').html('Nueva conclusión');

	// mostrar modal
	$('#modal_reporte_conclusion').modal({backdrop:false});
});


$('#tabla_reporte_conclusiones tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_conclusiones.row(tr);


	$('#form_modal_conclusion').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#reporteconclusion_id').val(row.data().id);


	// Llenar campo select partidas quimicos
	$('#reporteconclusion_catreportequimicospartidas_id').html(catpartidasquimicos_utilizadas);


	// Llenar campos
	$('#reporteconclusion_catreportequimicospartidas_id').val(row.data().catreportequimicospartidas_id);
	$('#reportequimicosconclusion_conclusion').val(row.data().conclusion);


	// Titulo del modal
	$('#modal_reporte_conclusion .modal-title').html('Conclusión');

	// mostrar modal
	$('#modal_reporte_conclusion').modal({backdrop:false});
});


$('#tabla_reporte_conclusiones tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_conclusiones.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Conclusión de la partida:\n"+row.data().partida,
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
				text: "Conclusión de la partida:\n"+row.data().partida,
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
						url: "/reportequimicosconclusioneliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);

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


$("#botonguardar_modal_conclusion").click(function()
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
				$('#form_modal_conclusion').ajaxForm({
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


						// Actualizar tabla
						tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);


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
						$('#botonguardar_modal_conclusion').html('Guardar conclusión <i class="fa fa-save"></i>');
						$('#botonguardar_modal_conclusion').attr('disabled', false);


						// cerrar modal
						$('#modal_reporte_conclusion').modal('hide');
					},
					beforeSend: function()
					{
						$('#botonguardar_modal_conclusion').html('Guardando conclusión <i class="fa fa-spin fa-spinner"></i>');
						$('#botonguardar_modal_conclusion').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#botonguardar_modal_conclusion').html('Guardar conclusión <i class="fa fa-save"></i>');
						$('#botonguardar_modal_conclusion').attr('disabled', false);

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
		reporte_dashboard(proyecto.id, reporteregistro_id, 0);
	}, 6500);
});


var dashboard_ejecusiones = 0;
function reporte_dashboard(proyecto_id, reporteregistro_id, partida_id)
{
	// Enviar datos
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosdashboard/"+proyecto_id+"/"+reporteregistro_id+"/"+partida_id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			$("#dashboard_titulo").html(dato.dashboard_titulo);
			$("#parametros_cumplimiento").html(dato.parametros_cumplimiento);
			$("#dashboard_areas").html(dato.dashboard_areas);
			$("#dashboard_categorias").html(dato.dashboard_categorias);
			$("#total_evaluacion").html(dato.total_evaluacion);
			$("#total_evaluacionambiental").html(dato.total_evaluacionambiental);
			$("#total_evaluacionpersonal").html(dato.total_evaluacionpersonal);
			$("#dashboard_recomendaciones_total").html(dato.dashboard_recomendaciones_total);

			// grafica_dashboard_resultados(dato.serie_grafico);

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
				reporte_dashboard(proyecto_id, reporteregistro_id, partida_id);
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
	}, 7000);
});


var datatable_recomendaciones = null;
function tabla_reporte_recomendaciones(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportequimicosrecomendacionestabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
						console.log('error en tabla_reporte_recomendaciones '+code);
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
																		'<label>Tipo</label>'+
																		'<select class="custom-select form-control" name="recomendacionadicional_tipo[]" required>'+
																			'<option value=""></option>'+
																			'<option value="Preventiva">Preventiva</option>'+
																			'<option value="Correctiva">Correctiva</option>'+
																		'</select>'+
																	'</div>'+
																	'<div class="col-6">'+
																		'<label>Partida informe</label>'+
																		'<select class="custom-select form-control" name="recomendacionadicional_quimicopartida[]" required>'+
																			catpartidasquimicos_utilizadas+
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

							reporte_dashboard(proyecto.id, reporteregistro_id, 0);

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
	$('#reporteresponsable1documento').dropify({
		messages:
		{
			'default': 'Arrastre el documento aquí o haga click',
			'replace': 'Arrastre el documento o haga clic para reemplazar',
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
			'default': 'Arrastre el documento aquí o haga click',
			'replace': 'Arrastre el documento o haga clic para reemplazar',
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

						menureporte_estado("menureporte_10", 1);

						// tabla_reporte_revisiones(proyecto.id);

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


$("#boton_descargarresponsabledoc1").click(function()
{
	window.open('/reportequimicosresponsabledocumento/'+reporteregistro_id+'/'+1+'/'+1);
});


$("#boton_descargarresponsabledoc2").click(function()
{
	window.open('/reportequimicosresponsabledocumento/'+reporteregistro_id+'/'+2+'/'+1);
});


//=================================================
// PLANOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_planos(proyecto.id, reporteregistro_id, agente_nombre);
	}, 7500);
});


var datatable_planos = null;
function tabla_reporte_planos(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportequimicosplanostabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
						data: "partida",
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
						menureporte_estado("menureporte_11_2", dato.total);

						// tabla_reporte_revisiones(proyecto.id);

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
// QUIMICOS EVALUADOS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_quimicosevaluados(proyecto.id, reporteregistro_id);
	}, 8000);
});


function tabla_reporte_quimicosevaluados(proyecto_id, reporteregistro_id)
{	
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosevaluadostabla/"+proyecto.id+"/"+reporteregistro_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			$('#tabla_reporte_11_3 tbody').html(dato.tabla_reporte_11_3);

			// Actualiza menu
			// menureporte_estado("menureporte_11_3", dato.total);
		},
		beforeSend: function()
		{
			$('#tabla_reporte_11_3 tbody').html('<tr><td colspan="8" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			// Actualiza menu
			// menureporte_estado("menureporte_11_3", 0);

			$('#tabla_reporte_11_3 tbody').html('<tr><td colspan="8" style="text-align: center;">Error al consultar los químicos</td></tr>');			
			return false;
		}
	});//Fin ajax
}


//=================================================
// EQUIPO UTILIZADO


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_reporte_equipoutilizado(proyecto.id, reporteregistro_id, agente_nombre);
	}, 8500);
});


var datatable_equipoutilizado = null;
function tabla_reporte_equipoutilizado(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportequimicosequipoutilizadotabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
						menureporte_estado("menureporte_11_4", parseInt(json.total));
						menureporte_estado("menureporte_11_6", parseInt(json.total));

						// alert("Done! "+json.msj);
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_equipoutilizado '+code);
						if (numeroejecucion <= 1)
						{
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
					}
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
		tabla_reporte_equipoutilizado(proyecto_id, reporteregistro_id, agente_nombre);
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


$("#botoncerrar_modalvisor_reporte").click(function()
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
	$('.equipoutilizado_checkbox').each(function()
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

							menureporte_estado("menureporte_11_4", 1);
							menureporte_estado("menureporte_11_6", 1);

							datatable_equipoutilizado.ajax.reload();

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
		tabla_reporte_anexosresultados(proyecto.id, reporteregistro_id, agente_nombre);
	}, 9000);
});


var datatable_informeresultados = null;
function tabla_reporte_anexosresultados(proyecto_id, reporteregistro_id, agente_nombre)
{
	try 
	{
		var ruta = "/reportequimicosanexosresultadostabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
						// menureporte_estado("menureporte_11_3", json.total);
						menureporte_estado("menureporte_11_5", json.total);
						menureporte_estado("menureporte_11_7", json.total);

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
							// menureporte_estado("menureporte_11_3", dato.total);
							menureporte_estado("menureporte_11_5", dato.total);
							menureporte_estado("menureporte_11_7", dato.total);

							// tabla_reporte_revisiones(proyecto.id);

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
		var ruta = "/reportequimicosanexosacreditacionestabla/"+proyecto_id+"/"+reporteregistro_id+"/"+agente_nombre;

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
							menureporte_estado("menureporte_11_8", 1);
							menureporte_estado("menureporte_11_9", 1);
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
		$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/'+row.data().id + '/' + 0);

		// Titulo modal
		$('#modal_visor .modal-title').html(row.data().acreditacion_Tipo+' '+row.data().acreditacion_Entidad+' ['+row.data().acreditacion_Numero+']');

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
							menureporte_estado("menureporte_11_8", dato.total);
							menureporte_estado("menureporte_11_9", dato.total);

							// tabla_reporte_revisiones(proyecto.id);

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
	}, 3000);
});


$(document).on('submit', function()
{
	setTimeout(function()
	{
		// alert('Guardaste algo');
		tabla_reporte_revisiones(proyecto.id);
		// alert('Presionaste un boton submit');
	}, 10000);
});


var datatable_reporterevisiones = null;
function tabla_reporte_revisiones(proyecto_id)
{
	try 
	{
		var ruta = "/reportequimicosrevisionestabla/"+proyecto_id;

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


function reporte_concluido(reporte_id, perfil, checkbox)
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
							url: "/reportequimicosrevisionconcluir/"+reporte_id,
							data:{},
							cache: false,
							success:function(dato)
							{
								// Actualizar tablas
								botoninforme_estado(dato.estado);
								tabla_reporte_revisiones(proyecto.id);

								tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
								tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
								tabla_reporte_categorias(proyecto.id, reporteregistro_id);
								tabla_reporte_areas(proyecto.id, reporteregistro_id);
								tabla_reporte_epp(proyecto.id, reporteregistro_id);
								tabla_reporte_7(proyecto.id, reporteregistro_id);
								tabla_reporte_7_1(proyecto.id, reporteregistro_id);
								tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);


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
						url: "/reportequimicosrevisionconcluir/"+reporte_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_epp(proyecto.id, reporteregistro_id);
							tabla_reporte_7(proyecto.id, reporteregistro_id);
							tabla_reporte_7_1(proyecto.id, reporteregistro_id);
							tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);


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
			return false;
		}	
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
							opcion: 22,
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


							// Actualizar tablas
							botoninforme_estado(dato.estado);
							tabla_reporte_revisiones(proyecto.id);

							tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
							tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
							tabla_reporte_categorias(proyecto.id, reporteregistro_id);
							tabla_reporte_areas(proyecto.id, reporteregistro_id);
							tabla_reporte_epp(proyecto.id, reporteregistro_id);
							tabla_reporte_7(proyecto.id, reporteregistro_id);
							tabla_reporte_7_1(proyecto.id, reporteregistro_id);
							tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);

							
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
						opcion: 22,
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


						// Actualizar tablas
						botoninforme_estado(dato.estado);
						tabla_reporte_revisiones(proyecto.id);

						tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_epp(proyecto.id, reporteregistro_id);
						tabla_reporte_7(proyecto.id, reporteregistro_id);
						tabla_reporte_7_1(proyecto.id, reporteregistro_id);
						tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);

						
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
var dashboard_graficas = [];
var dashboard_partidas = [];


$("#boton_reporte_nuevarevision").click(function()
{
	if (ultimaversion_cancelada == 1)
	{
		if (parseInt(datatable_grupoquimicos.data().count()) > 0)
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


					var partida = 0;
					contador_graficas = 0;
					dashboard_graficas = [];
					dashboard_partidas = [];

					// Recorrer tabla grupos de partidas
					datatable_grupoquimicos.rows().every( function ( rowIdx, tableLoop, rowLoop )
					{
						var data = this.data();

						if (parseInt(partida) != parseInt(data.catreportequimicospartidas_id))
						{
							// alert(data.proyecto_id+' - '+data.registro_id+' - '+data.catreportequimicospartidas_id+' - '+data.catreportequimicospartidas_numero+'.- '+data.catreportequimicospartidas_descripcion);						
							partida = parseInt(data.catreportequimicospartidas_id);
							dashboard_partidas.push(partida);
						}
					});


					if (parseInt(dashboard_partidas.length) == 0)
					{
						dashboard_partidas.push(0);
					}


					dashboard_actualizar(proyecto.id, reporteregistro_id, dashboard_partidas[0]);
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
				text: "Aun no se ha creado la lista de partidas para generar los informes de este proyecto.",
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
			timer: 3000,
			showConfirmButton: false
		});
	}
});


function dashboard_actualizar(proyecto_id, reporteregistro_id, partida_id)
{
	// ACTUALIZA DASHBOARD
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicosdashboard/"+proyecto_id+"/"+reporteregistro_id+"/"+partida_id+"/"+areas_poe,
		data:{},
		cache: false,
		success:function(dato)
		{
			$("#dashboard_titulo").html(dato.dashboard_titulo);
			$("#parametros_cumplimiento").html(dato.parametros_cumplimiento);
			$("#dashboard_areas").html(dato.dashboard_areas);
			$("#dashboard_categorias").html(dato.dashboard_categorias);
			$("#total_evaluacion").html(dato.total_evaluacion);
			$("#total_evaluacionambiental").html(dato.total_evaluacionambiental);
			$("#total_evaluacionpersonal").html(dato.total_evaluacionpersonal);
			$("#dashboard_recomendaciones_total").html(dato.dashboard_recomendaciones_total);


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

			crear_informe();
		},
		error: function(dato)
		{
			// dashboard_actualizar(proyecto_id, reporteregistro_id, partida_id);
			console.log("Error al actualizar dashboard");
			return false;
		}
	});//Fin ajax
}


function crear_informe()
{
	html2canvas(document.querySelector("#tabla_dashboard"), { scale: 6}).then(canvas =>
	{
		// document.getElementById("captura").appendChild(canvas)
		img64 = canvas.toDataURL('image/jpeg');
		dashboard_graficas.push(img64+"*");


		setTimeout(function()
		{
			// Enviar datos
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "/reportequimicosword",
				data:{
					_token: document.querySelector('meta[name="csrf-token"]')['content'],
					proyecto_id: proyecto.id,
					agente_id: agente_id,
					agente_nombre: agente_nombre,
					reporteregistro_id: reporteregistro_id,
					partida_id: dashboard_partidas[parseInt(contador_graficas)],
					areas_poe: areas_poe,
					ultimarevision_id: ultimarevision_id,
					crear_revision: 1,
					grafica_dashboard: img64,
				},
				cache: false,
				success:function(dato)
				{
					// ventana = window.open('/reportequimicosworddescargar/'+proyecto.id+"/"+$("#imprimirpartida_revision_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+$('#imprimirpartida_ultima_revision').val());


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


					if (parseInt((contador_graficas + 1)) < parseInt(dashboard_partidas.length))
					{
						contador_graficas += 1;
						dashboard_actualizar(proyecto.id, reporteregistro_id, dashboard_partidas[parseInt(contador_graficas)]);
					}
					else
					{
						reporte_dashboard(proyecto_id, reporteregistro_id, 0);


						// Actualizar tablas
						tabla_reporte_revisiones(proyecto.id);
						tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);
						tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
						tabla_reporte_categorias(proyecto.id, reporteregistro_id);
						tabla_reporte_areas(proyecto.id, reporteregistro_id);
						tabla_reporte_epp(proyecto.id, reporteregistro_id);
						tabla_reporte_7(proyecto.id, reporteregistro_id);
						tabla_reporte_7_1(proyecto.id, reporteregistro_id);
						tabla_reporte_conclusiones(proyecto.id, reporteregistro_id);


						// desplazar a la ultima fila de la tabla
						setTimeout(function()
						{
							$('html, body').animate({
								scrollTop: $('#tabla_reporte_revisiones').offset().top //ultima fila
							}, 1000);
						}, 2000);


						// Boton
						$('#boton_reporte_nuevarevision').html('<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión');
						$('#boton_reporte_nuevarevision').attr('disabled', false);


						// mensaje dashboard actualizado
						swal({
							title: "Correcto",
							text: "Nueva revisión creada correctamente",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 1500,
							showConfirmButton: false
						});

						
						return false;
					}
				},
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
			
		}, 500);
	});
}


/*
function revision_crear()
{
	// Enviar imagenes imagenes
	setTimeout(function()
	{
		// Enviar datos
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "/reportequimicosrevisionnueva",
			data:{
				_token: document.querySelector('meta[name="csrf-token"]')['content'],
				proyecto_id: proyecto.id,
				agente_id: agente_id,
				agente_nombre: agente_nombre,
				reporteregistro_id: reporteregistro_id,
				dashboard_partidas: dashboard_partidas,
				dashboard_graficas: dashboard_graficas,
			},
			cache: false,
			success:function(dato)
			{
				// Actualizar ID reporte
				reporteregistro_id = dato.reporteregistro_id;

				
				// Actualizar tablas
				tabla_reporte_revisiones(proyecto.id);
				reporte_dashboard(proyecto.id, reporteregistro_id, 0);

				tabla_reporte_definiciones(proyecto.id, agente_nombre, reporteregistro_id)
				tabla_reporte_categorias(proyecto.id, reporteregistro_id);
				tabla_reporte_areas(proyecto.id, reporteregistro_id);
				tabla_reporte_epp(proyecto.id, reporteregistro_id);
				tabla_reporte_7(proyecto.id, reporteregistro_id);
				tabla_reporte_7_1(proyecto.id, reporteregistro_id);
				tabla_reporte_gruposquimicos(proyecto.id, reporteregistro_id);


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
	}, 500);
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
	$('#form_modal_imprimirpartida').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#imprimirpartida_registro_id').val(reporteregistro_id);
	$('#imprimirpartida_revision_id').val(row.data().id);
	$('#imprimirpartida_ultima_revision').val(row.data().ultima_revision);


	// CONSULTA PARTIDAS
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reportequimicospartidashistorial/"+proyecto.id+"/"+reporteregistro_id+"/"+row.data().id,
		data:{},
		cache: false,
		success:function(dato)
		{
			$('#imprimirpartida_partida_id').html(dato.partidas_opciones);


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


			$("#"+botondescarga.id).html('<i class="fa fa-download fa-2x"></i>');
		},
		error: function(dato)
		{
			// dashboard_actualizar(proyecto_id, reporteregistro_id, partida_id);
			console.log("Error al CONSULTAR PARTIDAS HISTORIAL");
			$('#imprimirpartida_partida_id').html('<option value="">Error al consultar partidas</option>');
			return false;
		}
	});//Fin ajax


	// Titulo del modal
	$('#modal_reporte_imprimirpartida .modal-title').html('Descargar informe de químicos revisión '+row.data().reporterevisiones_revision);


	// mostrar modal
	$('#modal_reporte_imprimirpartida').modal({backdrop:false});
});


$("#botonguardar_modal_imprimirpartida").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// alert(proyecto.id+' - '+$("#imprimirpartida_registro_id").val()+' - '+$("#imprimirpartida_partida_id").val());

		$('#botonguardar_modal_imprimirpartida').html('Descargando, por favor espere <i class="fa fa-spin fa-spinner"></i>');
		$('#botonguardar_modal_imprimirpartida').attr('disabled', true);


		if (parseInt($("#imprimirpartida_ultima_revision").val()) > 0)
		{
			// ACTUALIZA DASHBOARD
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/reportequimicosdashboard/"+proyecto.id+"/"+$("#imprimirpartida_registro_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+areas_poe,
				data:{},
				cache: false,
				success:function(dato)
				{
					$("#dashboard_titulo").html(dato.dashboard_titulo);
					$("#parametros_cumplimiento").html(dato.parametros_cumplimiento);
					$("#dashboard_areas").html(dato.dashboard_areas);
					$("#dashboard_categorias").html(dato.dashboard_categorias);
					$("#total_evaluacion").html(dato.total_evaluacion);
					$("#total_evaluacionambiental").html(dato.total_evaluacionambiental);
					$("#total_evaluacionpersonal").html(dato.total_evaluacionpersonal);
					$("#dashboard_recomendaciones_total").html(dato.dashboard_recomendaciones_total);					


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
								// type: "POST",
								// dataType: "json",
								// url: "/reportequimicosdashboardgraficas",
								// data:{
								// 	_token: document.querySelector('meta[name="csrf-token"]')['content'],
								// 	proyecto_id: proyecto.id,
								// 	agente_id: agente_id,
								// 	agente_nombre: agente_nombre,
								// 	reporteregistro_id: reporteregistro_id,
								// 	partida_id: $("#imprimirpartida_partida_id").val(),
								// 	grafica_dashboard: img64,
								// },
								type: "POST",
								dataType: "json",
								url: "/reportequimicosword",
								data:{
									_token: document.querySelector('meta[name="csrf-token"]')['content'],
									proyecto_id: proyecto.id,
									agente_id: agente_id,
									agente_nombre: agente_nombre,
									reporteregistro_id: reporteregistro_id,
									partida_id: $("#imprimirpartida_partida_id").val(),
									areas_poe: areas_poe,
									ultimarevision_id: ultimarevision_id,
									crear_revision: 0,
									grafica_dashboard: img64,
								},
								cache: false,
								success:function(dato)
								{
									// ventana = window.open("/reportequimicosword/"+proyecto.id+"/"+$("#imprimirpartida_registro_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+areas_poe);
									ventana = window.open('/reportequimicosworddescargar/'+proyecto.id+"/"+$("#imprimirpartida_revision_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+$('#imprimirpartida_ultima_revision').val());


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
									$('#botonguardar_modal_imprimirpartida').html('Descargar <i class="fa fa-download"></i>');
									$('#botonguardar_modal_imprimirpartida').attr('disabled', false);


									// Actualizar tablas
									tabla_reporte_revisiones(proyecto.id);
									reporte_dashboard(proyecto.id, reporteregistro_id, 0); //Actualizar dashboard a la normalidad
								},
								error: function(dato)
								{
									$('#botonguardar_modal_imprimirpartida').html('Descargar <i class="fa fa-download"></i>');
									$('#botonguardar_modal_imprimirpartida').attr('disabled', false);


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
						}, 500);
					});
				},
				error: function(dato)
				{
					reporte_dashboard(proyecto.id, reporteregistro_id, 0); //Actualizar dashboard a la normalidad
					return false;
				}
			});//Fin ajax
		}
		else
		{
			// ventana = window.open("/reportequimicosword/"+proyecto.id+"/"+$("#imprimirpartida_registro_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+areas_poe);
			ventana = window.open('/reportequimicosworddescargar/'+proyecto.id+"/"+$("#imprimirpartida_revision_id").val()+"/"+$("#imprimirpartida_partida_id").val()+"/"+$('#imprimirpartida_ultima_revision').val());


			setTimeout(function()
			{
				// if (ventana.window)
				if (ventana)
				{
					ventana.window.close();
				}
			}, 15000);


			// Boton
			$('#botonguardar_modal_imprimirpartida').html('Descargar <i class="fa fa-download"></i>');
			$('#botonguardar_modal_imprimirpartida').attr('disabled', false);


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

			url = 'descargarPortadaInformes/' + proyecto.id + '/' + 15;
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
                    a.download = `Plantilla principal (Químico) - ${instalacion}.docx`;
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
