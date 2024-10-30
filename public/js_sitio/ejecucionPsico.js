//variables globales
var proyecto_id = 0;
var RECPSICO_ID = 0;

var datatable_ejecuciones = null;
var datatable_trabajadores_presencial = null;
var datatable_trabajadores_online = null;


//CARGA INCIIAL
$(document).ready(function()
{
	oculta_menustab_principal();
	tabla_ejecucion();
   
    $('[data-toggle="tooltip"]').tooltip();
	$('#tab_tabla_ejecucion').click();

});

//======================================NAV==================================//
// navegar menu Tab principal
$('.nav-link').click(function()
{
	switch (this.id)
	{
		case "tab_tabla_ejecucion":
            $('#tab_info_ejecucion').css('display', 'none');
            
            break;
        
		case "tab_menu2":
			
            
			break;
		default:
			// return true;
        	break;
	}
});

//=======================================TABLAS==================================================//
$('#tabla_ejecucion tbody').on('click', 'td>button.mostrar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_ejecuciones.row(tr);
	
    proyecto_id = row.data().ID_PROYECTO


    //DIV NOMBRE DEL PROYECTO
    $('.div_folio_proyecto').html(row.data().FOLIO);
    $('.div_folio_reconocimiento').html(row.data().RECONOCIMIENTO_VINCULADO);

	// mostrar menu tab
	muestra_menustab_principal();
	tabla_trabajadores_online();
    tabla_trabajadores_presencial();

	// Selecciona step form 1
	$( "#tab_info_ejecucion" ).click();
	

});

//======================================BOTONES===================================================//
$("#boton_nuevo_fotosevidencia").click(function()
{
	$('#modal_evidencia_fotos').modal({ backdrop: false });
	cargarTrabajadoresNombres('trabajador_nombre_foto');
});

$("#botocargar_respuestas_trabajadores").click(function()
{
	$('#modal_cargarRespuestasTrabajadores').modal({ backdrop: false });
});

$("#botonactualizar_fechas_online").click(function()
{
	var valida = this.form.checkValidity();
	if (valida) {
		swal({   
			title: "¡Confirme que desea actualizar las fechas!",
			text: "Actualizar fechas de TODOS los trabajadores",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Actualizar!",
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
				let datosEnviar = [];

				let table = $('#tabla_trabajadores_online').DataTable();

				let allData = table.rows().data();

				allData.each(function(row) {
					let trabajadorNombre = row.TRABAJADOR_NOMBRE; 
					let fechaInicio = row.FECHAINICIO;
					let fechaFin = row.FECHAFIN;
					let estadoCorreo = row.TRABAJADOR_ESTADOCORREO;
					let estadoContestados = row.TRABAJADOR_ESTADOCONTESTADO;
					let trabajadorId = row.TRABAJADOR_ID;

					datosEnviar.push({
						trabajadorNombre: trabajadorNombre,
						trabajadorId: trabajadorId,
						fechaInicio: fechaInicio,
						fechaFin: fechaFin,
						estadoCorreo: estadoCorreo,
						estadoContestados: estadoContestados
					});
				});

				
				$('#form_actualizarFechasOnline').ajaxForm({
					dataType: 'json',
					type: 'PUT',
					url: '/actualizarFechasOnline',
					data: {
						proyecto_id: proyecto_id,
						opcion: 0,
						datos: JSON.stringify(datosEnviar)
					},
					resetForm: false,
					success: function(dato){

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
						
					
						tabla_trabajadores_online();
						// actualiza boton
						$('#botonactualizar_fechas_online').html('Guardar <i class="fa fa-save"></i>');
					},
					beforeSend: function(){
						$('#botonactualizar_fechas_online').html('Guardando <i class="fa fa-spin fa-spinner"></i>');

					},
					error: function(dato) {
						// actualiza boton
						$('#botonactualizar_fechas_online').html('Guardar <i class="fa fa-save"></i>');
						console.log(JSON.stringify(datosEnviar));
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

$("#botonactualizar_fechaaplicacion").click(function()
{
	var valida = this.form.checkValidity();
	if (valida) {
		swal({   
			title: "¡Confirme que desea actualizar!",
			text: "Actualizar fecha de aplicación de TODOS los trabajadores de la modalidad presencial",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Actualizar!",
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
				// $('#form_proyectoequipos').ajaxForm({
				// 	dataType: 'json',
				// 	type: 'POST',
				// 	url: '/proyectoequipos',
				// 	data: {
				// 		proyecto_id: proyecto_id,
				// 		opcion: 0,
				// 	},
				// 	resetForm: false,
				// 	success: function(dato){

				// 		// mensaje
				// 		swal({
				// 			title: "Correcto",
				// 			text: ""+dato.msj,
				// 			type: "success", // warning, error, success, info
				// 			buttons: {
				// 				visible: false, // true , false
				// 			},
				// 			timer: 1500,
				// 			showConfirmButton: false
				// 		});

				// 		// actualiza boton
				// 		$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');
				// 	},
				// 	beforeSend: function(){
				// 		$('#boton_guardar_proyectoequipos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
				// 	},
				// 	error: function(dato) {
				// 		// actualiza boton
				// 		$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');

				// 		// mensaje
				// 		swal({
				// 			title: "Error",
				// 			text: ""+dato.msj,
				// 			type: "error", // warning, error, success, info
				// 			buttons: {
				// 				visible: false, // true , false
				// 			},
				// 			timer: 1500,
				// 			showConfirmButton: false
				// 		});
				// 		return false;
				// 	}
				// }).submit();
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

$("#botonenviar_todos_correos").click(function()
{
	swal({   
		title: "¡Confirme que desea enviar correos!",
		text: "Enviar correos a todos los trabajadores",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#db6f02",
		confirmButtonText: "Enviar!",
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
			// $('#form_proyectoequipos').ajaxForm({
			// 	dataType: 'json',
			// 	type: 'POST',
			// 	url: '/proyectoequipos',
			// 	data: {
			// 		proyecto_id: proyecto_id,
			// 		opcion: 0,
			// 	},
			// 	resetForm: false,
			// 	success: function(dato){

			// 		// mensaje
			// 		swal({
			// 			title: "Correcto",
			// 			text: ""+dato.msj,
			// 			type: "success", // warning, error, success, info
			// 			buttons: {
			// 				visible: false, // true , false
			// 			},
			// 			timer: 1500,
			// 			showConfirmButton: false
			// 		});

			// 		// actualiza boton
			// 		$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');
			// 	},
			// 	beforeSend: function(){
			// 		$('#boton_guardar_proyectoequipos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
			// 	},
			// 	error: function(dato) {
			// 		// actualiza boton
			// 		$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');

			// 		// mensaje
			// 		swal({
			// 			title: "Error",
			// 			text: ""+dato.msj,
			// 			type: "error", // warning, error, success, info
			// 			buttons: {
			// 				visible: false, // true , false
			// 			},
			// 			timer: 1500,
			// 			showConfirmButton: false
			// 		});
			// 		return false;
			// 	}
			// }).submit();
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

$("#botonguardar_modalidad_online").click(function()
{
	swal({   
		title: "¡Confirme que desea guardar!",
		text: "Guardar cambios de tabla trabajadores modalidad online",
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

			$('#form_proyectoequipos').ajaxForm({
				dataType: 'json',
				type: 'POST',
				url: '/proyectoequipos',
				data: {
					proyecto_id: proyecto_id,
					opcion: 0,
				},
				resetForm: false,
				success: function(dato){

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
					$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');
				},
				beforeSend: function(){
					$('#boton_guardar_proyectoequipos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
				},
				error: function(dato) {
					// actualiza boton
					$('#boton_guardar_proyectoequipos').html('Guardar <i class="fa fa-save"></i>');

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

//======================================FUNCIONES=================================================//

function oculta_menustab_principal()
{
	$("#tab_info_ejecucion").css('display', 'none');
	$("#tab_evidencias_ejecucion").css('display', 'none');
}

function muestra_menustab_principal()
{
	$("#tab_info_ejecucion").css('display', 'block');
	$("#tab_evidencias_ejecucion").css('display', 'block');
}

function tabla_ejecucion()
{
	try 
	{
		var ruta = "/ejecucionPsicoTabla";

		if (datatable_ejecuciones != null)
		{
			datatable_ejecuciones.clear().draw();
			datatable_ejecuciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_ejecuciones = $('#tabla_ejecucion').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						
						console.log('error en datatable_ejecuciones');
						if (numeroejecucion <= 1)
						{
							tabla_ejecucion();
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
					    "data": "FOLIO",
					    "defaultContent": "-"
					},
					{
					    "data": "instalacion_y_direccion",
					    "defaultContent": "-"
					},
					{
					    "data": "FECHA_INICIO",
					    "defaultContent": "-"
					},
					{
					    "data": "FECHA_FIN",
					    "defaultContent": "-"
					},
					{
					    "orderable": false,
					    "data": 'boton_mostrar',
					    "defaultContent": '-'
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

		// Tooltip en DataTable
		datatable_ejecuciones.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
        tabla_ejecucion()
    }
}

function tabla_trabajadores_online()
{
	try 
	{
		if (datatable_trabajadores_online != null) {
			datatable_trabajadores_online.destroy();
			//datatable_trabajadores_online.clear().destroy();
			//datatable_trabajadores_online.ajax.url(ruta).load();
		}
        datatable_trabajadores_online = $('#tabla_trabajadores_online').DataTable({
			"ajax": {
				"url": "/trabajadoresOnlineEjecucionPsico/"+proyecto_id,
				"type": "get",
				"cache": false,
				"error": function (xhr, error, code) {
					console.log('error en tabla_trabajadores_online');
				},
				"data": {}
			},
			"columns": [
				{
                    "data": "COUNT",
                    "defaultContent": "-"
                },
                {
                    "data": "TRABAJADOR_NOMBRE",
                    "defaultContent": "-",
					"render": function(data, type, row) {
						if (type === 'display') {
							return '<div data-trabajador-nombre="' + data + '">' + data + '</div>';
						}
						return data;
					}
                },
                {
                    "data": "FECHAINICIO",
                    "defaultContent": "-",
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return '<div class="input-group">' +
                                '<input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" name="FECHAINICIO[]" value="' + data + '">' +
                                '<span class="input-group-addon"><i class="icon-calender"></i></span>' +
                                '</div>';
                        }
                        return data;
                    }
                },
                {
                    "data": "FECHAFIN",
                    "defaultContent": "-",
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return '<div class="input-group">' +
                                '<input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" name="FECHAFIN[]" value="' + data + '">' +
                                '<span class="input-group-addon"><i class="icon-calender"></i></span>' +
                                '</div>';
                        }
                        return data;
                    }
                },
                {
                    "data": "TRABAJADOR_ESTADOCORREO",
                    "defaultContent": "-",
                },
                {
                    "data": "TRABAJADOR_ESTADOCONTESTADO",
                    "defaultContent": "-"
                },
                {
                    "orderable": false,
                    "data": 'boton_enviarCorreo',
                    "defaultContent": '-'
                },
				{
					"data": "TRABAJADOR_ID", 
					"visible": false,
					"defaultContent": "-",
					"render": function(data, type, row) {
						if (type === 'display') {
							return '<input type="hidden" name="TRABAJADOR_ID[]" value="' + data + '">';
						}
						return data;
					}
				}
			],
            "drawCallback": function(settings) {
                $('.mydatepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
            },
			"lengthMenu": [[5, 10, 50, -1], [5, 50, 100, "Todos"]],
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

        datatable_trabajadores_online.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
		
	}
	catch (exception)
	{
        console.error("Error en tabla_trabajadores_psico:", exception);
    }
}

function tabla_trabajadores_presencial()
{
	try 
	{
		if (datatable_trabajadores_presencial != null) {
			datatable_trabajadores_presencial.destroy();
		}
        datatable_trabajadores_presencial = $('#tabla_trabajadores_presencial').DataTable({
			"ajax": {
				"url": "/trabajadoresPresencialEjecucionPsico/"+proyecto_id,
				"type": "get",
				"cache": false,
				"error": function (xhr, error, code) {
					console.log('error en tabla_trabajadores_online');
				},
				"data": {}
			},
			"columns": [
				{
                    "data": "COUNT",
                    "defaultContent": "-"
                },
                {
                    "data": "NOMBRE",
                    "defaultContent": "-"
                },
				{
                    "data": "FECHAAPLICACION",
                    "defaultContent": "-",
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return '<div class="input-group">' +
                                '<input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" value="' + data + '">' +
                                '<span class="input-group-addon"><i class="icon-calender"></i></span>' +
                                '</div>';
                        }
                        return data;
                    }
                },
                {
                    "data": "ESTADOCUESTIONARIO",
                    "defaultContent": "-"
                }
			],
			"drawCallback": function(settings) {
                $('.mydatepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
            },
			"lengthMenu": [[5, 10, 50, -1], [20, 50, 100, "Todos"]],
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

        datatable_trabajadores_presencial.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

		
	}
	catch (exception)
	{
        console.error("Error en tabla_trabajadores_presencial:", exception);
    }
}

function cargarTrabajadoresNombres(elementId) {
    const selectElement = document.getElementById(elementId);
    if (!selectElement) {
        console.error('Error en consultar los datos');
        return;
    }

    selectElement.innerHTML = '';

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ejecuciontrabajadoresnombres", 
        data: {}, 
        cache: false,
        success: function(data) {
            
            data.forEach(trabajador => {
                const option = document.createElement('option');
                option.value = trabajador.ID_RECOPSICOTRABAJADOR; 
                option.textContent = trabajador.RECPSICOTRABAJADOR_NOMBRE; 
                selectElement.appendChild(option);
            });

            $(selectElement).selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Seleccione un trabajador'
            });
        },
        beforeSend: function() {
            selectElement.innerHTML = '<option value="" disabled selected>Consultando trabajadores...</option>';
        },
        error: function(error) {
            console.error('Error:', error.responseText);
        }
    });
}
	
function enviarCorreo(trabajadorId, idRecsensorial) {
	//envio único
    let tipo = 0;

    let url = `/envioGuia/${tipo}/${trabajadorId}/${idRecsensorial}`;

    $.ajax({
        url: url, 
        method: 'GET', 
        success: function(response) {
            if (response.msj) {
				swal({
					title: "¡Envío exitoso!",
					text: ""+response.msj,
					type: "success", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
				tabla_trabajadores_online();
            } else {
                swal({
					title: "Error",
					text: ""+response.msj,
					type: "error", // warning, error, success, info
					buttons: {
						visible: false, // true , false
					},
					timer: 1500,
					showConfirmButton: false
				});
            }

        },
		beforeSend: function(){
			swal({
				title: "Espere un momento...",
				text: "Enviando correo",
				type: "info", // warning, error, success, info
				buttons: {
					visible: false, // true , false
				},
				showConfirmButton: false
			});
		},
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
			swal({
				title: "Error",
				text: ""+response.msj,
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







