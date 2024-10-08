
// variables globales
var proyecto_id = 0;
var proyecto_bloqueado = 0;
var datatable_proyectos = null;
var datatable_signatarios_activo = 0;
var datatable_signatarios = null;
var datatable_equipos_activo = 0;
var datatable_equipos = null;

// Modulo evidencia
var evidencia_agente_id = 0;
var evidencia_agente = '';

// Load PAGINA
$(document).ready(function()
{
	// Ocultar menu
	$("#tab_menu2").css('display', 'none');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');

	tabla_proyecto();

	// inicializar campo FOTO plano instalacion
	$('#inputevidenciafotofisicos').dropify({
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

	$('[data-toggle="tooltip"]').tooltip();
});


// navegar menu Tab principal
$('.nav-link').click(function()
{
	switch (this.id)
	{
		case "tab_menu1":
			$("#tab_menu2").css('display', 'none');
			$("#tab_menu3").css('display', 'none');
			$("#tab_menu4").css('display', 'none');
			$("#tab_menu5").css('display', 'none');

			$("#tab_1").css('display', 'block');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			break;
		case "tab_menu2":
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'block');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');
			break;
		case "tab_menu3":
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'block');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'none');

			// consultar signatarios
			if (datatable_signatarios_activo == 0)
			{
				tabla_signatarios(proyecto_id);
				datatable_signatarios_activo = 1;
			}
			break;
		case "tab_menu4":
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'block');
			$("#tab_5").css('display', 'none');

			// consultar equipos
			if (datatable_equipos_activo == 0)
			{
				tabla_equipos(proyecto_id);
				datatable_equipos_activo = 1;
			}
			break;
		case "tab_menu5":
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');
			$("#tab_4").css('display', 'none');
			$("#tab_5").css('display', 'block');

			consulta_menuparametros_evidencias(proyecto_id);
			$('#tabmenu_evidencia_1').click();
			$('#tab_evidencia_1').click();
			break;
		default:
			// sin accion
        	break;
	}
});


function tabla_proyecto()
{
	try 
	{
		var ruta = "/externotabla";

		if (datatable_proyectos != null)
		{
			datatable_proyectos.ajax.url(ruta).load();
		}
		else
		{
			datatable_proyectos = $('#tabla_proyectos').DataTable({
		        "ajax": {
		            "url": ruta,
		            "type": "get",
		            "cache": false,
		            error: function (xhr, error, code)
		            {
		                // console.log(xhr); console.log(code);
		                // alert('error al consultar proyectos');
		                // location.href = '/externo';
		                tabla_proyecto();
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
		                "data": "proyecto_folio",
		                "defaultContent": "-"
		            },
		            {
		                "data": "proyecto_clienterazonsocial",
		                "defaultContent": "-",
		                "orderable": false,
		            },
		            {
		                "data": "instalacion_y_direccion",
		                "defaultContent": "-",
		                "orderable": false,
		            },
		            {
		                "data": "inicio_y_fin",
		                "defaultContent": "-",
		                "orderable": false,
		            },
		            {
		                "data": "duracion",
		                "defaultContent": "-"
		            },
		            {
		                "className": 'mostrar',
		                "orderable": false,
		                // "data": 'IconoBloqueo',
		                "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-eye"></i></button>'
		            }
		        ],
		        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		        // "rowsGroup": [0, 1], //agrupar filas
		        "order": [[ 0, "DESC" ]],
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
	catch (exception)
	{
        // location.href = '/externo';
        tabla_proyecto();
    }
}


// Selecciona Proyecto
$('#tabla_proyectos tbody').on('click', 'td.mostrar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_proyectos.row(tr);

	// asignar numero de proyecto
	proyecto_id = parseInt(row.data().proyecto_id);

	// Activa menu informacion general
	$("#tab_menu2").css('display', 'block');
	$("#tab_2").css('display', 'block');
	$("#tab_menu2").click();

	// mostrar todo el menu
	$("#tab_menu3").css('display', 'block');
	$("#tab_menu4").css('display', 'block');
	$("#tab_menu5").css('display', 'block');

	//DIV DATOS DEL PROYECTO
    $('.div_proyecto_folio').html(row.data().proyecto_folio);
    $('.div_proyecto_instalacion').html(row.data().proyecto_clienteinstalacion);
    $('.div_proyecto_fecha').html(row.data().proyecto_fechainicio+' <span style="color: #999999;">al</span> '+row.data().proyecto_fechafin);
    $('.div_proyecto_dias').html('Duración del servicio ('+row.data().proyecto_totaldias+' días)');
    $('.div_proyecto_direccion').html(row.data().proyecto_clientedireccionservicio);

	// LISTA DE SERVICIOS ASIGNAOS
	$('#tabla_parametros_fisicos tbody').html(row.data().agentesfisicos);
	$('#lista_agentesquimicos tbody').html(row.data().agentesquimicos);

	// Consulta lista de signatarios
	datatable_signatarios_activo = 0;
	$('#proyectosignatariosobservacion').html(row.data().observacionsignatarios);

	// Consulta lista de equipos
	datatable_equipos_activo = 0;
	$('#proyectoequiposobservacion').html(row.data().observacionequipos);

	// ACTIVAR BOTON GUARDAR SIGNATARIOS
	if (parseInt(row.data().disponibilidadsignatarios) == 0)
	{
		$('#boton_guardar_proyectosignatarios').css('display', 'none');
		// $('#boton_guardar_proyectosignatarios_boton').css('display', 'block');
		$('#boton_imprimir_proyectosignatarios_lista').css('display', 'block');
	}
	else
	{
		$('#boton_imprimir_proyectosignatarios_lista').css('display', 'none');
		// $('#boton_guardar_proyectosignatarios_boton').css('display', 'none');
		$('#boton_guardar_proyectosignatarios').css('display', 'block');
	}

	// ACTIVAR BOTON GUARDAR EQUIPOS
	if (parseInt(row.data().disponibilidadequipos) == 0)
	{
		$('#boton_guardar_proyectoequipos').css('display', 'none');
		// $('#boton_guardar_proyectoequipos_boton').css('display', 'block');
		$('#boton_imprimir_proyectoequipos_lista').css('display', 'block');
	}
	else
	{
		$('#boton_imprimir_proyectoequipos_lista').css('display', 'none');
		// $('#boton_guardar_proyectoequipos_boton').css('display', 'none');
		$('#boton_guardar_proyectoequipos').css('display', 'block');
	}
});


//======================================================


function tabla_signatarios(proyecto_id)
{
    try 
	{
		var ruta = "/proyectosignatariosinventario/"+proyecto_id;

		if (datatable_signatarios != null)
		{
			datatable_signatarios.clear().draw();
			datatable_signatarios.ajax.url(ruta).load();
		}
		else
		{
			datatable_signatarios = $('#tabla_proyectosignatarios').DataTable({
		        "ajax": {
		            "url": ruta,
		            "type": "get",
		            "cache": false,
		            error: function (xhr, error, code)
		            {
		                // console.log(xhr); console.log(code);
		                // datatable_signatarios_activo = 0;
		                // $('#tab_menu3').click();
		                tabla_signatarios(proyecto_id);
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
		                "data": "disponibilidad",
		                "defaultContent": "-"
		            },
		            {
		                "data": "checkbox",
		                "defaultContent": "-",
		                "orderable": false
		            },
		            {
		                "data": "signatario_Nombre",
		                "defaultContent": "-"
		            },
		            {
		                "data": "signatario_acreditaciones",
		                "defaultContent": "-"
		            }
		        ],
		        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		        // "rowsGroup": [0, 1], //agrupar filas
		        // "order": [[ 0, "DESC" ]],
		        "ordering": true,
		        "processing": true,
		        "paging": false,
		        "paging": false,
		        "scrollY": 500,
		        "scrollX": false,
		        "scrollCollapse": false,
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
	catch (exception)
	{
        // datatable_signatarios_activo = 0;
        // $('#tab_menu3').click();
        tabla_signatarios(proyecto_id);
    }
}


$("#boton_guardar_proyectosignatarios").click(function()
{
	// borrar campo filtro del DATATABLE
	datatable_signatarios.search($(this).val()).draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.checkbox_proyectosignatarios').each(function(){
        if (this.checked) {
            seleccionados += 1;
        }
    });

	if (seleccionados > 0)
	{
		swal({   
            title: "¡Confirme guardar!",
            text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm)
            {
            	swal({   
		            title: "¡Confirme nuevamente guardar!",
		            text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
		            type: "warning",
		            showCancelButton: true,
		            confirmButtonColor: "#DD6B55",
		            confirmButtonText: "Guardar!",
		            cancelButtonText: "Cancelar!",
		            closeOnConfirm: false,
		            closeOnCancel: false
		        }, function(isConfirm){
		            if (isConfirm)
		            {
		            	// cerrar msj confirmacion
						swal.close();

		            	// enviar datos
						$('#form_proyectosignatarios').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectosignatarios',
							data: {
								proyecto_id: proyecto_id,
								opcion: 0, // Asignar signatarios
							},
							resetForm: false,
							success: function(dato)
							{
								// ACTIVAR BOTON GUARDAR SIGNATARIOS
								if (parseInt(dato.disponibilidadsignatarios) == 0)
								{
									$('#boton_guardar_proyectosignatarios').css('display', 'none');
									// $('#boton_guardar_proyectosignatarios_boton').css('display', 'block');
									$('#boton_imprimir_proyectosignatarios_lista').css('display', 'block');
								}
								else
								{
									$('#boton_imprimir_proyectosignatarios_lista').css('display', 'none');
									// $('#boton_guardar_proyectosignatarios_boton').css('display', 'none');
									$('#boton_guardar_proyectosignatarios').css('display', 'block');
								}

								// Actualizar tabla
								tabla_proyecto();

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
								$('#boton_guardar_proyectosignatarios').html('<i class="fa fa-save"></i> Guardar lista');
							},
							beforeSend: function()
							{
								$('#boton_guardar_proyectosignatarios').html('<i class="fa fa-spin fa-spinner"></i> Guardando lista');
							},
							error: function(dato)
							{
								// actualiza boton
								$('#boton_guardar_proyectosignatarios').html('<i class="fa fa-save"></i> Guardar lista');

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
					title: "Cancelado",
					text: "Acción cancelada",
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
			title: "Seleccione signatarios",
			text: "Antes de guardar debe seleccionar uno o más signatarios",
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


$("#boton_imprimir_proyectosignatarios_lista").click(function()
{
	// Cargar reporte
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/externoimprimirlistasignatarios/'+proyecto_id);

	// mostrar modal
	$('#modal_visor').modal({backdrop:false});
});


//===============================================================


$("#modalvisor_boton_cerrar").click(function()
{
	// Cargar reporte
	$('#visor_documento').attr('src', '/assets/images/cargando.gif');
});


function opcion_nodisponible_lista(lista_nombre)
{
	swal({
		title: "Opción no disponible",
		text: "Esta lista de "+lista_nombre+" ha sido autorizada, solamente puede hacer cambios con la autorización del administrador",
		type: "info", // warning, error, success, info
		buttons: {
			visible: false, // true , false
		},
		timer: 2500,
		showConfirmButton: false
	});
}


//===============================================================



function tabla_equipos(proyecto_id)
{

	try 
	{
		var ruta = "/proyectoequiposinventario/"+proyecto_id;

		if (datatable_equipos != null)
		{
			datatable_equipos.clear().draw();
			datatable_equipos.ajax.url(ruta).load();
		}
		else
		{
			datatable_equipos = $('#tabla_proyectoequipos').DataTable({
		        "ajax": {
		            "url": ruta,
		            "type": "get",
		            "cache": false,
		            error: function (xhr, error, code)
		            {
		                // console.log(xhr); console.log(code);
		                // datatable_equipos_activo = 0;
		                // $('#tab_menu4').click();
		                tabla_equipos(proyecto_id);
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
		                "data": "equipo_disponible",
		                "defaultContent": "-"
		            },
		            {
		                "data": "checkbox",
		                "defaultContent": "-",
		                "orderable": false,
		            },
		            {
		                "data": "equipo_Descripcion",
		                "defaultContent": "-"
		            },
		            {
		                "data": "equipo_Marca",
		                "defaultContent": "-"
		            },
		            {
		                "data": "equipo_Modelo",
		                "defaultContent": "-"
		            },
		            {
		                "data": "equipo_Serie",
		                "defaultContent": "-"
		            },
		            {
		                "data": "equipo_VigenciaCalibracion",
		                "defaultContent": "-"
		            }
		        ],
		        // "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		        // "rowsGroup": [0, 1], //agrupar filas
		        // "order": [[ 0, "DESC" ]],
		        "ordering": true,
		        "processing": true,
		        "paging": false,
		        "scrollY": 500,
		        "scrollX": false,
		        "scrollCollapse": false,
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
	catch (exception)
	{
        // datatable_equipos_activo = 0;
        // $('#tab_menu4').click();
        tabla_equipos(proyecto_id);
    }
}


$("#boton_guardar_proyectoequipos").click(function()
{
	// borrar campo filtro del DATATABLE
	datatable_equipos.search($(this).val()).draw();

	// valida campos vacios
	var seleccionados = 0;
	$('.checkbox_proyectoequipos').each(function(){
        if (this.checked) {
            seleccionados += 1;
        }
    });

	if (seleccionados > 0)
	{
		swal({   
            title: "¡Confirme guardar!",
            text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm)
            {
            	swal({   
		            title: "¡Confirme nuevamente guardar!",
		            text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
		            type: "warning",
		            showCancelButton: true,
		            confirmButtonColor: "#DD6B55",
		            confirmButtonText: "Guardar!",
		            cancelButtonText: "Cancelar!",
		            closeOnConfirm: false,
		            closeOnCancel: false
		        }, function(isConfirm){
		            if (isConfirm)
		            {
		            	// cerrar msj confirmacion
						swal.close();

		            	// enviar datos
						$('#form_proyectoequipos').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoequipos',
							data: {
								proyecto_id: proyecto_id,
								opcion: 0, // Asignar equipos
							},
							resetForm: false,
							success: function(dato)
							{
								// ACTIVAR BOTON GUARDAR EQUIPOS
								if (parseInt(dato.disponibilidadequipos) == 0)
								{
									$('#boton_guardar_proyectoequipos').css('display', 'none');
									// $('#boton_guardar_proyectoequipos_boton').css('display', 'block');
									$('#boton_imprimir_proyectoequipos_lista').css('display', 'block');
								}
								else
								{
									$('#boton_imprimir_proyectoequipos_lista').css('display', 'none');
									// $('#boton_guardar_proyectoequipos_boton').css('display', 'none');
									$('#boton_guardar_proyectoequipos').css('display', 'block');
								}

								// Actualizar tabla
								tabla_proyecto();

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
								$('#boton_guardar_proyectoequipos').html('<i class="fa fa-save"></i> Guardar lista');
							},
							beforeSend: function()
							{
								$('#boton_guardar_proyectoequipos').html('<i class="fa fa-spin fa-spinner"></i> Guardando lista');
							},
							error: function(dato)
							{
								// actualiza boton
								$('#boton_guardar_proyectoequipos').html('<i class="fa fa-save"></i> Guardar lista');

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
					title: "Cancelado",
					text: "Acción cancelada",
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
			title: "Seleccione equipos",
			text: "Antes de guardar debe seleccionar uno o más equipos",
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


$("#boton_imprimir_proyectoequipos_lista").click(function()
{
	// Cargar reporte
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/externoimprimirlistaequipos/'+proyecto_id);

	// mostrar modal
	$('#modal_visor').modal({backdrop:false});
});


//===============================================================


function consulta_menuparametros_evidencias(proyecto_id)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciaparametros/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			// Catalogo de partidas químicos
			$('#catreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);
			$('#planoscatreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);
			$('#nombrecarpetacatreportequimicospartidas_id').html(dato.quimicoscatpartidas_opciones);


			$('#lista_menu_paramertros_evidencia').html(dato.opciones);


			// Activar primer parametro menu
			$.each( $('.menulista_evidencia'), function( key, value )
			{
				if (key == 0)
				{
					$(this).click();
					$(this).addClass('active');
					// alert(this.id);
				}
				else
				{
					$(this).removeClass('active');
				}
			});
		},
		beforeSend: function(){
			$('#lista_menu_paramertros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></li>');
		},
		error: function(dato){
			$('#lista_menu_paramertros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">Error al cargar los parametros</li>');
			return false;
		}
	});//Fin ajax
}


function consulta_evidencias(proyecto_id, agente_id, agente_nombre, menu, nombre_rol)
{
	// Id del parametro elegido
	evidencia_agente_id = parseInt(agente_id);

	// Eliminar active de todo el menu evidencia
	if (menu != 0)
	{
		$('.menulista_evidencia').removeClass('active');
	}

	// Activar menu
	if (menu != 0)
	{
		$.each( $('.menulista_evidencia'), function( key, value )
		{
			if (this == menu)
			{
				$(this).addClass('active');
			}
		});
	}

	// Nombre parametro
	if (evidencia_agente_id == 15)
	{
		evidencia_agente = "Químicos";
	}
	else
	{
		evidencia_agente = agente_nombre;
	}

	// Nombre parametro elegido
	$('#evidencia_agente_titulo').html(evidencia_agente);

	// VALIDA OPCION
	if (evidencia_agente_id == 1000) // PUNTOS REALES
	{
		// ACTIVAR SECCION
		$('#seccion_proyectoevidencias').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'none');
		$('#seccion_proyectopuntosreales').css('display', 'block');

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "/proyectopuntosrealeslista/"+proyecto_id,
			data:{},
			cache: false,
			success:function(dato)
			{
				$('#tabla_proyectoevidencia_puntosreales tbody').html(dato.filas);

				if (parseInt(dato.puntosreales_total) > 0)
				{
					$('#boton_imprimir_proyectopuntosreales').css('display', 'inline-block');
				}
				else
				{
					$('#boton_imprimir_proyectopuntosreales').css('display', 'none');
				}
			},
			beforeSend: function()
			{
				$('#tabla_proyectoevidencia_puntosreales tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 50px!important;"></i></td></tr>');
			},
			error: function(dato)
			{
				$('#tabla_proyectoevidencia_puntosreales tbody').html('<tr><td colspan="4" style="text-align: center;">Error al cargar los agentes</td></tr>');
				return false;
			}
		});//Fin ajax
	}
	else if (evidencia_agente_id == 2000) // BITACORA
	{
		// ACTIVAR SECCION
		$('#seccion_proyectoevidencias').css('display', 'none');
		$('#seccion_proyectopuntosreales').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'block');

		tabla_bitacora(proyecto_id);
	}
	else //EVIDENCIAS
	{
		// ACTIVAR SECCION
		$('#seccion_proyectopuntosreales').css('display', 'none');
		$('#seccion_bitacoramuestreo').css('display', 'none');
		$('#seccion_proyectoevidencias').css('display', 'block');

		// Seccion fotos si es agente
		if (evidencia_agente_id > 0)
		{
			$('#tabmenu_evidencia_2').css('display', 'block'); // Mostrar menu Fotos
			// $('#tabmenu_evidencia_3').css('display', 'block'); // Mostrar menu planos
		}
		else // informacion general
		{
			$('#tabmenu_evidencia_2').css('display', 'none'); // Ocultar menu Fotos
			// $('#tabmenu_evidencia_3').css('display', 'none'); // Ocultar menu planos
		}

		// Activar menu evidencia Documentos
		$('#tabmenu_evidencia_1').click();

		// Consultar documentos
		consulta_evidencia_documentos(proyecto_id, agente_id, agente_nombre);

		// Consultar Fotos
		consulta_evidencia_fotos(proyecto_id, agente_id, agente_nombre);

		// Consultar planos
		consulta_evidencia_planos(proyecto_id, agente_id, agente_nombre);

		// Activa botones NUEVO / GUARDAR
		if (agente_nombre == "Información general")
		{
			$('#boton_nuevo_documentoevidencia').attr('disabled', true);
			$('#boton_guardar_evidencia_documento').css('display', 'none');

			$('#boton_nuevo_fotosevidencia').attr('disabled', true);
			$('#boton_guardar_evidencia_fotos').css('display', 'none');

			$('#boton_nuevo_planosevidencia').attr('disabled', true);
			$('#boton_guardar_evidencia_planos').css('display', 'none');
		}
		else
		{
			$('#boton_nuevo_documentoevidencia').attr('disabled', false);
			$('#boton_guardar_evidencia_documento').css('display', 'block');

			$('#boton_nuevo_fotosevidencia').attr('disabled', false);
			$('#boton_guardar_evidencia_fotos').css('display', 'block');

			$('#boton_nuevo_planosevidencia').attr('disabled', false);
			$('#boton_guardar_evidencia_planos').css('display', 'block');
		}


		// Configuracion de los campos [quimicos, informacion general, Ergonomos, Psicologos]
		if (evidencia_agente_id == 0 || evidencia_agente_id == 13 || evidencia_agente_id == 14)
		{
			$("#evidenciadocumento").attr('accept', 'application/pdf'); // Campo FILE solo PDF
		}
		else
		{
			$("#evidenciadocumento").attr('accept', ''); // Campo FILE todo tipo de archivos
		}
	}
}


//===============================================================


$("#boton_guardar_puntosreales").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "Puntos reales",
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
				$('#form_puntosreales').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectopuntosreales',
					data: {
						proyecto_id: proyecto_id
					},
					resetForm: false,
					success: function(dato)
					{
						if (parseInt(dato.puntosreales_total) > 0)
						{
							$('#boton_imprimir_proyectopuntosreales').css('display', 'inline-block');
						}
						else
						{
							$('#boton_imprimir_proyectopuntosreales').css('display', 'none');
						}

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
						$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
					},
					beforeSend: function()
					{
						$('#boton_guardar_puntosreales').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
					},
					error: function(dato)
					{
						// actualiza boton
						$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');

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


$("#boton_imprimir_proyectopuntosreales").click(function()
{
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectopuntosrealesreporte/'+proyecto_id);
	$('#modal_visor').modal({backdrop:false});
});


//===============================================================


var datatable_bitacora = null;
function tabla_bitacora(proyecto_id)
{
	try 
	{
		var ruta = "/proyectoevidenciabitacoratabla/"+proyecto_id;

		if (datatable_bitacora != null)
		{
			datatable_bitacora.clear().draw();
			datatable_bitacora.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_bitacora = $('#tabla_bitacora').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_bitacora '+code);
						if (numeroejecucion <= 1)
						{
							tabla_bitacora(proyecto_id)
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
						data: "proyectoevidenciabitacora_fecha",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "signatarioparametro",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "agente_puntos",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "signatario_observacion",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
				rowsGroup: [0, 1], //agrupar filas
				// order: [[ 0, "ASC" ]],
				ordering: false,
				processing: true,
				searching: false,
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

					$(row).find('td:eq(2)').css('text-align', 'left');
					$(row).find('td:eq(4)').css('text-align', 'justify');
				},
			});
		}

		// Tooltip en DataTable
		datatable_bitacora.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_bitacora(proyecto_id);
    }
}


//===============================================================


function consulta_evidencia_documentos(proyecto_id, agente_id, agente_nombre)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciadocumentos/"+proyecto_id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			$('#tabla_evidenciadocumentos tbody').html(dato.documentos);
		},
		beforeSend: function(){
			$('#tabla_evidenciadocumentos tbody').html('<tr><td colspan="6"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato){
			$('#tabla_evidenciadocumentos tbody').html('<tr><td colspan="6">Error al cargar los documentos</td></tr>');
			return false;
		}
	});//Fin ajax
}


$("#boton_nuevo_documentoevidencia").click(function()
{
	// Borrar formulario
	$('#form_evidencia_documento').each(function(){
		this.reset();
	});

	// Campos Hidden
    $("#evidenciadocumento_id").val(0);

    // requerir campo FILE
	$('#evidenciadocumento').attr('required', true);

	// Activar boton guardar
    $('#boton_guardar_evidencia_documento').css('display', 'block');

    // mostrar modal
	$('#modal_evidencia_documento').modal({backdrop:false});
});


$("#boton_guardar_evidencia_documento").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// Valida envio de datos
		swal({   
	        title: "¡Confirme guardar documento!",
	        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
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
	        	swal({   
			        title: "¡Confirme nuevamente guardar documento!",
			        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Guardar!",
			        cancelButtonText: "Cancelar!",
			        closeOnConfirm: false,
			        closeOnCancel: false
			    }, function(isConfirm){
			        if (isConfirm)
			        {
			        	// cerrar msj confirmacion
						swal.close();

			        	// enviar datos
						$('#form_evidencia_documento').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoevidencia',
							data: {
								opcion: 1, //DOCUMENTOS
								proyecto_id: proyecto_id,
								agente_id: evidencia_agente_id,
								agente_nombre: evidencia_agente,
							},
							resetForm: false,
							success: function(dato)
							{
								consulta_evidencia_documentos(proyecto_id, evidencia_agente_id, evidencia_agente, 0);

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
								$('#boton_guardar_evidencia_documento').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_documento').attr('disabled', false);

								// cerrar modal
								$('#modal_evidencia_documento').modal('hide');
							},
							beforeSend: function()
							{
								$('#boton_guardar_evidencia_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#boton_guardar_evidencia_documento').attr('disabled', true);
							},
							error: function(dato)
							{
								// actualiza boton
								$('#boton_guardar_evidencia_documento').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_documento').attr('disabled', false);

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
	}
});


function evidencia_documento_descargar(documento_id)
{
	window.open("/proyectoevidenciadocumentodescargar/"+documento_id+"/"+1); //1 descargar
}


function evidencia_documento_editar(documento_id, documento_nombre)
{
	// Borrar formulario
	$('#form_evidencia_documento').each(function(){
		this.reset();
	});

	// Campos Hidden
    // $("#evidenciadocumento_tipo").val(1);
    $("#evidenciadocumento_id").val(documento_id);

    // campos visibles
    $("#proyectoevidenciadocumento_nombre").val(documento_nombre);

    // requerir campo FILE
	$('#evidenciadocumento').attr('required', false);

	// Activar boton guardar
	if (proyecto_perfil == 1) //Superusuario, Administrador
	{
		$('#boton_guardar_evidencia_documento').css('display', 'block');
	}
	else
	{
		$('#boton_guardar_evidencia_documento').css('display', 'none');
	}

	// mostrar modal
	$('#modal_evidencia_documento').modal({backdrop:false});
}


function evidencia_documento_eliminar(documento_id)
{
	// Valida envio de datos
	swal({   
        title: "¡Confirme eliminar!",
        text: "documento de evidencia",
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
				url: "/proyectoevidenciadocumentoeliminar/"+documento_id,
				data:{},
				cache: false,
				success:function(dato)
				{
					// Actualiza tabla documentos
					consulta_evidencia_documentos(proyecto_id, evidencia_agente_id, evidencia_agente, 0);

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
	return false;
}


//===============================================================


var foto_resize_fisicos = "";
var foto_resize_quimicos = [];
var foto_resize_planos = [];


$("#boton_nuevo_fotosevidencia").click(function()
{
	// Borrar formulario
	$('#form_evidencia_fotos').each(function(){
		this.reset();
	});

	// Resetear input FOTO
	$('#inputevidenciafotofisicos').val('');
	$('#inputevidenciafotofisicos').dropify().data('dropify').resetPreview();
	$('#inputevidenciafotofisicos').dropify().data('dropify').clearElement();

	// Campos Hidden
    $("#evidenciafotos_id").val(0);

    // Inicializar variables FOTOS
    foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

    // Activar campo carpeta para los parametros [quimicos, info general, ergonomos, psicologos]
	// if (parseInt(evidencia_agente_id) == 15 || parseInt(evidencia_agente_id) == 0 || parseInt(evidencia_agente_id) == 13 || parseInt(evidencia_agente_id) == 14)
	if (parseInt(evidencia_agente_id) == 0 || parseInt(evidencia_agente_id) == 13 || parseInt(evidencia_agente_id) == 14)
	{
		// Desactivar seccion fotos Fisicos
		$(".divevidencia_seccion_fotosfisicos").css('display', 'none');

		// No requerir campos
		$("#proyectoevidenciafoto_nopunto").attr('required', false);
		$("#proyectoevidenciafoto_descripcion").attr('required', false);
		$("#inputevidenciafotofisicos").attr('required', false);

		// ACTIVAR seccion fotos carpeta
		$(".divevidencia_seccion_fotoscarpeta").css('display', 'block');

		// CAMPOS
		$("#proyectoevidenciafoto_carpeta").attr('readonly', false);
		$("#proyectoevidenciafoto_carpeta").attr('required', true);
		$("#inputevidenciafotosquimicos").attr('required', true);

		// Campo partida quimicos
		$("#catreportequimicospartidas_id").attr('required', false);
	}
	else
	{
		// Desactivar seccion fotos carpeta
		$(".divevidencia_seccion_fotoscarpeta").css('display', 'none');

		// No requerir campos
		$("#proyectoevidenciafoto_carpeta").attr('required', false);
		$("#inputevidenciafotosquimicos").attr('required', false);


		// ACTIVAR seccion fotos Fisicos
		$(".divevidencia_seccion_fotosfisicos").css('display', 'block');

		// CAMPOS
		$("#proyectoevidenciafoto_nopunto").attr('required', true);
		$("#proyectoevidenciafoto_descripcion").attr('required', true);
		$("#inputevidenciafotofisicos").attr('required', true);

		if (parseInt(evidencia_agente_id) == 15)
		{
			$("#fotosfisicos_campo_punto").css('display', 'none');
			$("#proyectoevidenciafoto_nopunto").attr('required', false);

			$("#fotosfisicos_campo_partida").css('display', 'inline-block');
			$("#catreportequimicospartidas_id").attr('required', true);
		}
		else
		{
			$("#fotosfisicos_campo_partida").css('display', 'none');
			$("#catreportequimicospartidas_id").attr('required', false);

			$("#fotosfisicos_campo_punto").css('display', 'inline-block');
			$("#proyectoevidenciafoto_nopunto").attr('required', true);
		}
	}

	// Activar boton guardar
    $('#boton_guardar_evidencia_fotos').css('display', 'block');

    // mostrar modal
	$('#modal_evidencia_fotos').modal({backdrop:false});
});


function evidenciafoto_carpetanombre(campo_select)
{
	// alert($(campo_select).find(":selected").text());
	$("#proyectoevidenciafoto_carpeta").val($(campo_select).find(":selected").text());
}


function consulta_evidencia_fotos(proyecto_id, agente_id, agente_nombre)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciafotos/"+proyecto_id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			// Vaciar contenido
			$('#evidencia_galeria_fotos').html('');

			// validar si trae datos
			if (parseInt(dato.fotos_total) > 0)
			{
				// GALERIA DE FOTOS
				$("#evidencia_galeria_fotos").html(dato.fotos);
			}
			else
			{
				$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;">No hay fotos que mostrar</div>');
			}

			// Inicializar tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function(){
			$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
		},
		error: function(dato){			
			$('#evidencia_galeria_fotos').html('<div class="col-12" style="text-align: center;">Error al cargar las fotos</div>');
			return false;
		}
	});//Fin ajax
}


function evidenciafoto_mostrardatos(foto_id, foto_ruta, foto_punto, foto_descripcion, agente_id)
{
	// Borrar formulario
	$('#form_evidencia_fotos').each(function(){
		this.reset();
	});

	// Inicializar variables FOTOS
    foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

	// Desactivar seccion fotos carpeta
	$(".divevidencia_seccion_fotoscarpeta").css('display', 'none');

	// No requerir campos
	$("#proyectoevidenciafoto_carpeta").attr('required', false);
	$("#inputevidenciafotosquimicos").attr('required', false);

	// ACTIVAR seccion fotos Fisicos
	$(".divevidencia_seccion_fotosfisicos").css('display', 'block');


	if (parseInt(agente_id) == 15)
	{
		$("#fotosfisicos_campo_punto").css('display', 'none');
		$("#proyectoevidenciafoto_nopunto").attr('required', false);

		$("#fotosfisicos_campo_partida").css('display', 'inline-block');
		$("#catreportequimicospartidas_id").attr('required', true);

		$("#catreportequimicospartidas_id").val(foto_punto);
		$("#proyectoevidenciafoto_carpeta").val($('#catreportequimicospartidas_id').find(":selected").text());
	}
	else
	{
		$("#fotosfisicos_campo_partida").css('display', 'none');
		$("#catreportequimicospartidas_id").attr('required', false);

		$("#fotosfisicos_campo_punto").css('display', 'inline-block');
		$("#proyectoevidenciafoto_nopunto").attr('required', true);

		$("#proyectoevidenciafoto_nopunto").val(foto_punto);
	}

	
	// CAMPOS
	// $("#proyectoevidenciafoto_nopunto").attr('required', true);
	$("#proyectoevidenciafoto_descripcion").attr('required', true);
	$("#inputevidenciafotofisicos").attr('required', false);

	// Llenar campos
	$("#evidenciafotos_id").val(foto_id);
	// $("#proyectoevidenciafoto_nopunto").val(foto_punto);
	$("#proyectoevidenciafoto_descripcion").val(foto_descripcion);

	// MOSTRAR FOTO
	var archivo = foto_ruta;
    var extension = archivo.substring(archivo.lastIndexOf("."));
	var imagenUrl = '/proyectoevidenciafotomostrar/0/'+foto_id;
	$('#inputevidenciafotofisicos').dropify().data('dropify').destroy();
	$('#inputevidenciafotofisicos').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
	$('#inputevidenciafotofisicos').dropify().data('dropify').init();

	// mostrar modal
	$('#modal_evidencia_fotos').modal({backdrop:false});

	// Ocultar boton guardar
	$("#boton_guardar_evidencia_fotos").css('display', 'none');
}


function evidencia_foto_descargar(foto_id, foto_opcion)
{
	window.open("/proyectoevidenciafotomostrar/"+foto_id+"/"+foto_opcion);
}


function evidencia_foto_eliminar(foto_id)
{
	// Valida envio de datos
	swal({   
        title: "¡Confirme eliminar foto!",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm)
        {
        	swal({   
		        title: "¡Confirme nuevamente eliminar foto!",
		        text: "",
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
						url: "/proyectoevidenciafotoeliminar/"+foto_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza galeria de fotos
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

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
}


function eliminar_carpeta_fotos(proyecto_id, agente_nombre, carpeta)
{
	// Valida envio de datos
	swal({   
        title: "¡Confirme eliminar carpeta de fotos!",
        text: "["+carpeta+"]",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm)
        {
        	swal({   
		        title: "¡Confirme nuevamente eliminar carpeta de fotos!",
		        text: "["+carpeta+"]",
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
						url: "/proyectoevidenciafotoeliminarcarpeta/"+proyecto_id+"/"+agente_nombre+"/"+carpeta,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza galeria de fotos
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

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
}


function redimencionar_fotoevidencia()
{
	// Mostrar mensaje de espera
	$('#mensaje_cargando_fotos').css('display', 'block');
	$('#boton_guardar_evidencia_fotos').attr('disabled', true);

	foto_resize_fisicos = "";
	var filesToUpload = document.getElementById('inputevidenciafotofisicos').files;
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
			foto_resize_fisicos = dataurl; //Guardar en una variable

			// Quitar mensaje de espera
			$('#mensaje_cargando_fotos').css('display', 'none');
			$('#boton_guardar_evidencia_fotos').attr('disabled', false);
		}, 100);
	}
}


$("#boton_guardar_evidencia_fotos").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// Valida envio de datos
		swal({   
	        title: "¡Confirme guardar fotos (s)!",
	        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
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
	        	swal({   
			        title: "¡Confirme nuevamente guardar fotos (s)!",
			        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Guardar!",
			        cancelButtonText: "Cancelar!",
			        closeOnConfirm: false,
			        closeOnCancel: false
			    }, function(isConfirm){
			        if (isConfirm)
			        {
			        	// cerrar msj confirmacion
						swal.close();

			        	// enviar datos
						$('#form_evidencia_fotos').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoevidencia',
							data: {
								opcion: 2, //FOTOS
								proyecto_id: proyecto_id,
								agente_id: evidencia_agente_id,
								agente_nombre: evidencia_agente,
								foto_resize_fisicos: foto_resize_fisicos,
								foto_resize_quimicos: foto_resize_quimicos
							},
							resetForm: false,
							success: function(dato)
							{
								// Actualiza galeria de fotos
								consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);

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
								$('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_fotos').attr('disabled', false);

								// cerrar modal
								$('#modal_evidencia_fotos').modal('hide');
							},
							beforeSend: function()
							{
								$('#boton_guardar_evidencia_fotos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#boton_guardar_evidencia_fotos').attr('disabled', true);
							},
							error: function(dato)
							{
								// actualiza boton
								$('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_fotos').attr('disabled', false);

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
	}
});


function valida_totalfotos_quimicos(campo_file)
{
	if (parseInt($(campo_file).get(0).files.length) > 20) //Solo 20 archivos
	{
		swal({
			title: "Solo se pueden seleccionar 20 fotos",
			text: "Seleccionadas actualmente ["+$(campo_file).get(0).files.length+"], Intentelo de nuevo con maximo 20 fotos.",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 4000,
			showConfirmButton: false
		});

		// vaciar campo
		$(campo_file).val('');
	}
	else
	{
		// Inicializar arreglo de Fotos
		foto_resize_quimicos.length = 0;

		// Mostrar mensaje de espera
		$('#mensaje_cargando_fotos').css('display', 'block');
		$('#boton_guardar_evidencia_fotos').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		recorrer_fotos_files(0);

		// Funcion redimencionar imagenes
		function recorrer_fotos_files(contador)
		{
			if (contador < campo_file.files.length)
			{
				// Datos del archivo recorridos por un foreach
				// name = value.name.toLowerCase();
				// size = value.size;
				// type = value.type;

				// OBTENER FILE
				var file = campo_file.files[contador]; // FILE
				// var file = value; // FILE si es po un foreach
				// var filesToUpload = document.getElementById('inputevidenciafotofisicos').files; //Leer campo FILE
				// var file = filesToUpload[0];

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

						// Resultado
						var dataurl = canvas.toDataURL("image/jpeg");
						// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
						foto_resize_quimicos.push(dataurl+"*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						recorrer_fotos_files(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones ", width, height);
					}, 100);
				}
			}
			else
			{
				// Quitar mensaje de espera
				$('#mensaje_cargando_fotos').css('display', 'none');
				$('#boton_guardar_evidencia_fotos').attr('disabled', false);
			}
		}
	}
}


function agregar_fotos_carpeta(carpeta_nombre)
{
	// Borrar formulario
	$('#form_evidencia_fotos').each(function(){
		this.reset();
	});

	// Campos Hidden
    $("#evidenciafotos_id").val(0);

    // Nombre carpeta
    $("#proyectoevidenciafoto_carpeta").val(carpeta_nombre);

    // Inicializar variables FOTOS
    foto_resize_fisicos = "";
	foto_resize_quimicos.length = 0;

    // Desactivar seccion fotos Fisicos
	$(".divevidencia_seccion_fotosfisicos").css('display', 'none');

	// No requerir campos
	$("#proyectoevidenciafoto_nopunto").attr('required', false);
	$("#proyectoevidenciafoto_descripcion").attr('required', false);
	$("#inputevidenciafotofisicos").attr('required', false);

	// ACTIVAR seccion fotos carpeta
	$(".divevidencia_seccion_fotoscarpeta").css('display', 'block');

	// CAMPOS
	$("#proyectoevidenciafoto_carpeta").attr('readonly', true);
	$("#inputevidenciafotosquimicos").attr('required', true);

	// Activar boton guardar
    $('#boton_guardar_evidencia_fotos').css('display', 'block');

    // mostrar modal
	$('#modal_evidencia_fotos').modal({backdrop:false});
}

//===============================================================


function editar_nombrecarpeta(carpeta_tipo, carpeta_nombre, partida_id)
{
	// Borrar formulario
	$('#form_nombrecarpeta').each(function(){
		this.reset();
	});

	// Nombre carpeta
	$("#proyectoevidencia_nombrecarpetatipo").val(carpeta_tipo);
	$("#proyectoevidencia_nombrecarpetaoriginal").val(carpeta_nombre);
	$("#proyectoevidencia_nombrecarpetarenombrar").val(carpeta_nombre);

	if (parseInt(partida_id) > 0) // es químicos
	{
		$("#nombrecarpeta_campo_partida").css('display', 'inline-block');
		$("#nombrecarpetacatreportequimicospartidas_id").val(partida_id);
		$("#nombrecarpetacatreportequimicospartidas_id").attr('required', true);
	}
	else
	{
		$("#nombrecarpeta_campo_partida").css('display', 'none');
		$("#nombrecarpetacatreportequimicospartidas_id").val(partida_id);
		$("#nombrecarpetacatreportequimicospartidas_id").attr('required', false);
	}


	// mostrar modal
	$('#modal_nombrecarpeta').modal({backdrop:false});
}


$("#boton_guardar_nombrecarpeta").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// Valida envio de datos
		swal({   
			title: "¡Confirme renombrar carpeta!",
			text: $("#proyectoevidencia_nombrecarpetaoriginal").val()+"\n\n por \n\n"+$("#proyectoevidencia_nombrecarpetarenombrar").val(),
			type: "info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Cambiar!",
			cancelButtonText: "Cancelar!",
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm){
			if (isConfirm)
			{
				// cerrar msj confirmacion
				swal.close();
				
				// enviar datos
				$('#form_nombrecarpeta').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: '/proyectoevidencia',
					data: {
						opcion: 4, // RENOMBRAR CARPETA
						proyecto_id: proyecto_id,
						agente_id: evidencia_agente_id,
						agente_nombre: evidencia_agente
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualiza galeria de fotos
						if (parseInt($("#proyectoevidencia_nombrecarpetatipo").val()) == 1)
						{
							consulta_evidencia_fotos(proyecto_id, evidencia_agente_id, evidencia_agente);
						}
						else
						{
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);
						}
							
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
						$('#boton_guardar_nombrecarpeta').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', false);

						// cerrar modal
						$('#modal_nombrecarpeta').modal('hide');
					},
					beforeSend: function()
					{
						$('#boton_guardar_nombrecarpeta').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', true);
					},
					error: function(dato)
					{
						// actualiza boton
						$('#boton_guardar_nombrecarpeta').html('Guardar <i class="fa fa-save"></i>');
						$('#boton_guardar_nombrecarpeta').attr('disabled', false);

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


//===============================================================


$("#boton_nuevo_planosevidencia").click(function()
{
	// Borrar formulario
	$('#form_evidencia_planos').each(function(){
		this.reset();
	});

	// Inicializar variables PLANO
	foto_resize_planos.length = 0;

	// Campos Hidden
    $("#evidenciaplano_id").val(0);
    $("#proyectoevidenciaplano_carpeta").attr('readonly', false);


    if (parseInt(evidencia_agente_id) == 15)
	{
		$("#planos_campo_partida").css('display', 'inline-block');
		$("#planoscatreportequimicospartidas_id").attr('disabled', false);
		$("#planoscatreportequimicospartidas_id").attr('required', true);
	}
	else
	{
		$("#planos_campo_partida").css('display', 'none');
		$("#planoscatreportequimicospartidas_id").attr('required', false);
	}


    // Activar boton guardar
    $('#boton_guardar_evidencia_planos').css('display', 'block');


    // Titulo del modal
	$('#modal_evidencia_planos .modal-title').html('Nuevos planos evidencia');


    // mostrar modal
	$('#modal_evidencia_planos').modal({backdrop:false});
});


function consulta_evidencia_planos(proyecto_id, agente_id, agente_nombre)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciaplanos/"+proyecto_id+"/"+agente_id+"/"+agente_nombre,
		data:{},
		cache: false,
		success:function(dato)
		{
			// Vaciar contenido
			$('#evidencia_galeria_planos').html('');

			// validar si trae datos
			if (parseInt(dato.planos_total) > 0)
			{
				// GALERIA DE FOTOS
				$("#evidencia_galeria_planos").html(dato.planos);
			}
			else
			{
				$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;">No hay planos que mostrar</div>');
			}

			// Inicializar tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function(){
			$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
		},
		error: function(dato){			
			$('#evidencia_galeria_planos').html('<div class="col-12" style="text-align: center;">Error al cargar las planos</div>');
			return false;
		}
	});//Fin ajax
}


function evidencia_plano_descargar(foto_opcion, foto_id)
{
	window.open("/proyectoevidenciaplanosmostrar/"+foto_opcion+"/"+foto_id);
}


function eliminar_carpeta_planos(proyecto_id, agente_nombre, carpeta)
{
	// Valida envio de datos
	swal({   
        title: "¡Confirme eliminar carpeta de planos!",
        text: "["+carpeta+"]",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm)
        {
        	swal({   
		        title: "¡Confirme nuevamente eliminar carpeta de planos!",
		        text: "["+carpeta+"]",
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
						url: "/proyectoevidenciaplanoeliminarcarpeta/"+proyecto_id+"/"+agente_nombre+"/"+carpeta,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza galeria de planos
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

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
}


function evidencia_plano_eliminar(foto_id)
{
	// Valida envio de datos
	swal({   
        title: "¡Confirme eliminar plano!",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm)
        {
        	swal({   
		        title: "¡Confirme nuevamente eliminar plano!",
		        text: "",
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
						url: "/proyectoevidenciaplanoeliminar/"+foto_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza galeria de fotos
							consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

							//Cerrar imagen popo
							$(".mfp-figure").click();

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
}


function valida_totalfotos_plano(campo_file)
{
	if (parseInt($(campo_file).get(0).files.length) > 20) //Solo 20 archivos
	{
		swal({
			title: "Solo se pueden seleccionar 20 fotos",
			text: "Seleccionadas actualmente ["+$(campo_file).get(0).files.length+"], Intentelo de nuevo con maximo 20 fotos.",
			type: "info", // warning, error, success, info
			buttons: {
				visible: false, // true , false
			},
			timer: 4000,
			showConfirmButton: false
		});

		// vaciar campo
		$(campo_file).val('');
	}
	else
	{
		// Inicializar arreglo de Fotos
		foto_resize_planos.length = 0;

		// Mostrar mensaje de espera
		$('#mensaje_cargando_planos').css('display', 'block');
		$('#boton_guardar_evidencia_planos').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		recorrer_planos_files(0);

		// Funcion redimencionar imagenes
		function recorrer_planos_files(contador)
		{
			if (contador < campo_file.files.length)
			{
				// Datos del archivo recorridos por un foreach
				// name = value.name.toLowerCase();
				// size = value.size;
				// type = value.type;

				// OBTENER FILE
				var file = campo_file.files[contador]; // FILE
				// var file = value; // FILE si es po un foreach
				// var filesToUpload = document.getElementById('inputevidenciafotofisicos').files; //Leer campo FILE
				// var file = filesToUpload[0];

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

						// Resultado
						var dataurl = canvas.toDataURL("image/jpeg");
						// document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
						foto_resize_planos.push(dataurl+"*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						recorrer_planos_files(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones ", width, height);
					}, 100);
				}
			}
			else
			{
				// Quitar mensaje de espera
				$('#mensaje_cargando_planos').css('display', 'none');
				$('#boton_guardar_evidencia_planos').attr('disabled', false);
			}
		}
	}
}


function agregar_planos_carpeta(carpeta_nombre, partida_id)
{
	// Borrar formulario
	$('#form_evidencia_planos').each(function(){
		this.reset();
	});

	// Inicializar variables PLANO
	foto_resize_planos.length = 0;


	$("#planos_campo_partida").css('display', 'none');
	$("#planoscatreportequimicospartidas_id").val(partida_id);
	$("#planoscatreportequimicospartidas_id").attr('required', false);


	// Campos Hidden
    $("#evidenciaplano_id").val(0);

    // Nombre carpeta
    $("#proyectoevidenciaplano_carpeta").val(carpeta_nombre);
    $("#proyectoevidenciaplano_carpeta").attr('readonly', true);

    // Activar boton guardar
    $('#boton_guardar_evidencia_planos').css('display', 'block');

    // Titulo del modal
	$('#modal_evidencia_planos .modal-title').html('Agregar más planos a la carpeta');

    // mostrar modal
	$('#modal_evidencia_planos').modal({backdrop:false});
}


$("#boton_guardar_evidencia_planos").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// Valida envio de datos
		swal({   
	        title: "¡Confirme guardar plano (s)!",
	        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
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
	        	swal({   
			        title: "¡Confirme nuevamente guardar plano (s)!",
			        text: "Una vez guardada la información solamente puede hacer cambios con la autorización del administrador",
			        type: "info",
			        showCancelButton: true,
			        confirmButtonColor: "#DD6B55",
			        confirmButtonText: "Guardar!",
			        cancelButtonText: "Cancelar!",
			        closeOnConfirm: false,
			        closeOnCancel: false
			    }, function(isConfirm){
			        if (isConfirm)
			        {
			        	// cerrar msj confirmacion
						swal.close();

						// enviar datos
						$('#form_evidencia_planos').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoevidencia',
							data: {
								opcion: 3, //PLANOS
								proyecto_id: proyecto_id,
								agente_id: evidencia_agente_id,
								agente_nombre: evidencia_agente,
								foto_resize_planos: foto_resize_planos,
							},
							resetForm: false,
							success: function(dato)
							{
								// Actualiza galeria de planos
								consulta_evidencia_planos(proyecto_id, evidencia_agente_id, evidencia_agente);

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
								$('#boton_guardar_evidencia_planos').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_planos').attr('disabled', false);

								// cerrar modal
								$('#modal_evidencia_planos').modal('hide');
							},
							beforeSend: function()
							{
								$('#boton_guardar_evidencia_planos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#boton_guardar_evidencia_planos').attr('disabled', true);
							},
							error: function(dato)
							{
								// actualiza boton
								$('#boton_guardar_evidencia_planos').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_planos').attr('disabled', false);

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
	}
});


//===============================================================


function opcion_nodisponible()
{
	swal({
		title: "Opción no disponible",
		text: "Solamente puede hacer cambios con la autorización del administrador",
		type: "info", // warning, error, success, info
		buttons: {
			visible: false, // true , false
		},
		timer: 2500,
		showConfirmButton: false
	});
}
