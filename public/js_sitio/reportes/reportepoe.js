
//=================================================
// LOAD PAGINA

var opcion = 0
var ruta_storage_guardar = '/reportes';
// var proyecto = <?php echo json_encode($proyecto); ?>; // Variable declarada en el Blade
// var recsensorial = <?php echo json_encode($recsensorial); ?>; // Variable declarada en el Blade


$(document).ready(function()
{
	$('[data-toggle="tooltip"]').tooltip(); // Activar Tooltips
	
	//Validamos el estatus del PORYECTO
	if (estatus.length != 0) {

		if (estatus[0].POE_FINALIZADO == 1) {
			opcion = 0;
			$('#boton_reporte_nuevacategoria').prop('disabled', true);
			$('#boton_reporte_nuevaarea').prop('disabled', true);
			$('#btnFinalizarPoe').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Activar POE')

			
		} else {
			opcion = 1;
			$('#boton_reporte_nuevacategoria').prop('disabled', false);
			$('#boton_reporte_nuevaarea').prop('disabled', false);
			$('#btnFinalizarPoe').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Bloquear POE')

		}

	} else {
		opcion = 1;
		$('#btnFinalizarPoe').html('<span class="btn-label"><i class="fa fa-check-square"></i></span>Bloquear y Finalizar POE')
		$('#boton_reporte_nuevacategoria').prop('disabled', false);
		$('#boton_reporte_nuevaarea').prop('disabled', false);

	}
});


//=================================================
// TABLA CATAEGORIAS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_categorias(proyecto.id);
	}, 100);
});


var datatable_categorias = null;
function tabla_categorias(proyecto_id)
{
	try 
	{
		var ruta = "/reportecategoriatabla/"+proyecto_id;

		if (datatable_categorias != null)
		{
			datatable_categorias.clear().draw();
			datatable_categorias.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_categorias = $('#tabla_categorias').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// alert(json.total);

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_categorias '+code);
						if (numeroejecucion <= 1)
						{
							tabla_categorias(proyecto_id)
							numeroejecucion += 1;
						}
					}
				},
				columns: [
					// {
					//     data: "id" 
					// },
					{
						data: "reportecategoria_orden",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportecategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportecategoria_total",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_editar",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "boton_eliminar",
						defaultContent: "-",
						orderable: false,
					}
				],
				lengthMenu: [[30, 50, 100, -1], [30, 50, 100, "Todos"]],
				// rowsGroup: [1, 2, 0], //agrupar filas
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
				// rowCallback: function(row, data, index)
				// {
				// 	// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

				// 	if(data.id > 0)
				// 	{
				// 		$(row).find('td:eq(1)').css('background', "#00FF00");
				// 		$(row).find('td:eq(1)').css('color', '#000000');
				// 		$(row).find('td:eq(1)').css('font-weight', 'bold');
				// 	}
				// 	else
				// 	{
				// 		$(row).find('td:eq(1)').css('background', "#FF0000");
				// 		$(row).find('td:eq(1)').css('color', '#FFFFFF');
				// 		$(row).find('td:eq(1)').css('font-weight', 'bold');
				// 	}
				// },
			});
		}

		// Tooltip en DataTable
		datatable_categorias.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_categorias(proyecto_id);
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


$('#tabla_categorias tbody').on('click', 'td>button.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_categorias.row(tr);

	$('#form_modal_categoria').each(function(){
		this.reset();
	});

	// Campos Hidden
	$('#reportecategoria_id').val(row.data().id);


	// Llenar campos
	$('#reportecategoria_nombre').val(row.data().reportecategoria_nombre);
	$('#reportecategoria_total').val(row.data().reportecategoria_total);
	$('#reportecategoria_orden').val(row.data().reportecategoria_orden);


	// Titulo del modal
	$('#modal_reporte_categoria .modal-title').html('Categoría');


	// mostrar modal
	$('#modal_reporte_categoria').modal({backdrop:false});
});


$('#tabla_categorias tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_categorias.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Categoría: "+row.data().reportecategoria_nombre,
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
				text: "Categoría: "+row.data().reportecategoria_nombre,
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
						url: "/reportecategoriaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_categorias(proyecto.id);
							tabla_areas(proyecto.id);


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
			text: "La categoría: "+$("#reportecategoria_nombre").val(),
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
						opcion: 1,
						proyecto_id: proyecto.id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar tabla
						tabla_categorias(proyecto.id);
						tabla_areas(proyecto.id);


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
// TABLA AREAS


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_areas(proyecto.id);
	}, 500);
});


var datatable_areas = null;
function tabla_areas(proyecto_id)
{
	try 
	{
		var ruta = "/reporteareatabla/"+proyecto_id;

		if (datatable_areas != null)
		{
			datatable_areas.clear().draw();
			datatable_areas.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_areas = $('#tabla_areas').DataTable({
				ajax: {
					url: ruta,
					type: "get",
					cache: false,
					dataType: "json",
					data: {},
					dataSrc: function (json)
					{
						// alert(json.total);

						return json.data;
					},
					error: function (xhr, error, code)
					{						
						console.log('error en datatable_areas '+code);
						if (numeroejecucion <= 1)
						{
							tabla_areas(proyecto_id)
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
						data: "reportearea_instalacion",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportearea_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportearea_porcientooperacion_texto",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reportecategoria_nombre",
						defaultContent: "-",
						orderable: false,
					},
					{
						data: "reporteareacategoria_total",
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
				lengthMenu: [[30, 50, 100, -1], [30, 50, 100, "Todos"]],
				rowsGroup: [1, 2, 0, 3, 6, 7], //agrupar filas
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
				// rowCallback: function(row, data, index)
				// {
				// 	// console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

				// 	if(data.id > 0)
				// 	{
				// 		$(row).find('td:eq(1)').css('background', "#00FF00");
				// 		$(row).find('td:eq(1)').css('color', '#000000');
				// 		$(row).find('td:eq(1)').css('font-weight', 'bold');
				// 	}
				// 	else
				// 	{
				// 		$(row).find('td:eq(1)').css('background', "#FF0000");
				// 		$(row).find('td:eq(1)').css('color', '#FFFFFF');
				// 		$(row).find('td:eq(1)').css('font-weight', 'bold');
				// 	}
				// },
			});
		}

		// Tooltip en DataTable
		datatable_areas.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
		tabla_areas(proyecto_id);
    }
}


$("#boton_reporte_nuevaarea").click(function()
{
	$('#form_modal_area').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#reportearea_id').val(0);


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html('Nueva área');


	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});


	// Categorías
	area_categorias(proyecto_id, 0);
});


$('#tabla_areas tbody').on('click', 'td>button.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_areas.row(tr);


	$('#form_modal_area').each(function(){
		this.reset();
	});


	// Campos Hidden
	$('#reportearea_id').val(row.data().id);


	// Llenar campos
	$('#reportearea_instalacion').val(row.data().reportearea_instalacion);
	$('#reportearea_porcientooperacion').val(row.data().reportearea_porcientooperacion);
	$('#reportearea_nombre').val(row.data().reportearea_nombre);
	$('#reportearea_orden').val(row.data().reportearea_orden);


	// Titulo del modal
	$('#modal_reporte_area .modal-title').html('Área');


	// mostrar modal
	$('#modal_reporte_area').modal({backdrop:false});


	// Categorías
	area_categorias(proyecto.id, row.data().id, row.data().recsensorialarea_id);
});


$('#tabla_areas tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_areas.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "Área: "+row.data().reportearea_nombre,
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
				text: "Área: "+row.data().reportearea_nombre,
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
						url: "/reporteareaeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_areas(proyecto.id);


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
	// borrar campo filtro del DATATABLE'S
	datatable_areacategorias.search($(this).val()).draw();


	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		swal({
			title: "¡Confirme que desea guardar!",
			text: "El área: "+$("#reportearea_nombre").val(),
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


				// actualiza boton
				$('#botonguardar_modal_area').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
				$('#botonguardar_modal_area').attr('disabled', true);


				// array categorias
				setTimeout(function()
				{
					var checkbox_reportecategoria_id = [];
					$('input[name="checkbox_reportecategoria_id[]"]').each(function(i, v)
					{
						if($(this).prop('checked') == true)
						{
							// alert($(this).val()+' - '+$('input[name*="reporteareacategoria_total"]').eq(i).val()+' - '+$('input[name*="reporteareacategoria_geh"]').eq(i).val()+' - '+$('textarea[name*="reporteareacategoria_actividades"]').eq(i).val());

							var registro = {
								reportecategoria_id: $(this).val()
								, reporteareacategoria_total: $('input[name*="reporteareacategoria_total"]').eq(i).val()
								, reporteareacategoria_geh: $('input[name*="reporteareacategoria_geh"]').eq(i).val()
								, reporteareacategoria_actividades: $('textarea[name*="reporteareacategoria_actividades"]').eq(i).val()
							};

							checkbox_reportecategoria_id.push(registro);
						}
					});


					// // Recorrer array nuevo
					// $.each( checkbox_reportecategoria_id, function( key, value )
					// {
					// 	alert(value.reportecategoria_id+' - '+value.reporteareacategoria_actividades);
					// });


					// enviar datos
					$.ajax({
						type: "POST",
						dataType: "json",
						url: ''+ruta_storage_guardar,
						data: {
							_token: document.querySelector('meta[name="csrf-token"]')['content'],
							opcion: 2,
							proyecto_id: proyecto.id,
							reportearea_id: $('#form_modal_area #reportearea_id').val(),
							reportearea_instalacion: $('#form_modal_area #reportearea_instalacion').val(),
							reportearea_nombre: $('#form_modal_area #reportearea_nombre').val(),
							reportearea_orden: $('#form_modal_area #reportearea_orden').val(),
							reportearea_porcientooperacion: $('#form_modal_area #reportearea_porcientooperacion').val(),
							checkbox_reportecategoria_id: checkbox_reportecategoria_id
						},
						cache: false,
						success:function(dato)
						{
							// Actualizar tabla
							tabla_areas(proyecto.id);


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
							// actualiza boton
							// $('#botonguardar_modal_area').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
							// $('#botonguardar_modal_area').attr('disabled', true);
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
					});//Fin ajax
				}, 200);


				
				/*
				// enviar datos
				$('#form_modal_area').ajaxForm({
					dataType: 'json',
					type: 'POST',
					url: ''+ruta_storage_guardar,
					data: {
						opcion: 2,
						proyecto_id: proyecto.id,
					},
					resetForm: false,
					success: function(dato)
					{
						// Actualizar tabla
						tabla_areas(proyecto.id);


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
				*/


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


function area_categorias(proyecto_id, reportearea_id, $recsensorialarea_id)
{
	// Consultar categorias
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/reporteareacategorias/"+proyecto_id+"/"+reportearea_id + "/" + $recsensorialarea_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			tabla_areacategorias(dato.areacategorias);
		},
		beforeSend: function()
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="5" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
		},
		error: function(dato)
		{
			$('#tabla_areacategorias tbody').html('<tr><td colspan="5" style="text-align: center;">Error al cargar las categorías</td></tr>');
			return false;
		}
	});//Fin ajax
}


var datatable_areacategorias = null;
function tabla_areacategorias(tbody)
{
	if (datatable_areacategorias != null)
	{
		datatable_areacategorias.destroy();
	}

	$('#tabla_areacategorias tbody').html(tbody);

	datatable_areacategorias = $('#tabla_areacategorias').DataTable({
		"scrollY": "224px",
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


//=================================================
// DESCARGAR TABLA POE .DOCX


$(".boton_descarga_poe").click(function()
{
	// Boton descarga
	$(".boton_descarga_poe").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span>Descargando tabla POE .docx');


	if (window.open("/reportepoeword/"+proyecto.id))
	{
		setTimeout(function()
		{
			// Boton descarga
			$(".boton_descarga_poe").html('<span class="btn-label"><i class="fa fa-file-word-o"></i></span>Descargar tabla POE .docx');
		}, 5000);
	}
});


$('#btnFinalizarPoe').on('click', function (e) {
	e.preventDefault()

	if (estatus.length == 0) {
		nuevo = 1
		title = "¡Confirme para finalizar la tabla de POE!"
		text = "Una vez finaliza la tabla de POE esta se bloqueará, para poder generar los reportes."
		btn = "Finalizar!"

	} else {

		nuevo = 0
		title = "¡Confirme para realizar esta acción!"
		text = "Es necesario confirmar para realizar esta acción."
		btn = "Continuar!"

	}

	swal({
		title: title,
		text: text,
		type: "info",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: btn,
		cancelButtonText: "Cancelar!",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function (isConfirm) {
		if (isConfirm) {

			// cerrar msj confirmacion
			swal.close();

			// Finalizar la tabla de POE
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "/finalizarPOE/" + proyecto.id +'/' + opcion + '/' + nuevo,
				data: {},
				cache: false,
				success: function (dato) {

					

					//Si es primera vez que se guarda el POE actualizamos todo resetear las variables
					if (nuevo == 1) {

						$('#select_tiporeportes').prop('disabled', false)

						
						swal({
							title: "Bloqueado y finalizado exitosamente",
							text: "La lista de reportes ha sido activada.",
							type: "success", // warning, error, success, info
							buttons: {
								visible: false, // true , false
							},
							timer: 2000,
							showConfirmButton: false
						});
						
						//Caramos la vista de POE
						$.ajax({
							success: function () {
								
								$('#estructura_reporte').load('/reportepoevista/' + proyecto.id);
								
							},
							beforeSend: function()
							{
								$('#estructura_reporte').html('<div style="text-align: center; font-size: 60px;"><i class="fa fa-spin fa-spinner"></i></div>');
							},
							error: function()
							{
								$('#estructura_reporte').html('<div style="text-align: center;">Error al cargar parametro</div>');
								return false;
							}
						});

						
					} else {
						
						if (opcion == 1) {

							$('#select_tiporeportes').prop('disabled', false)


							swal({
								title: "POE bloqueada existosamente",
								text: "La lista de reportes ha sido activada.",
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 2000,
								showConfirmButton: false
							});
							opcion = 0;
							$('#boton_reporte_nuevacategoria').prop('disabled', true)
							$('#boton_reporte_nuevaarea').prop('disabled', true)
							$('#btnFinalizarPoe').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Activar POE').prop('disabled', false);
							
						} else {

							$('#select_tiporeportes').prop('disabled', true)

							
							swal({
								title: "POE activada existosamente",
								text: "La lista de reportes ha sido desactivada.",
								type: "success", // warning, error, success, info
								buttons: {
									visible: false, // true , false
								},
								timer: 2000,
								showConfirmButton: false
							});
							opcion = 1;
							$('#btnFinalizarPoe').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Bloquear POE').prop('disabled', false)
							$('#boton_reporte_nuevacategoria').prop('disabled', false)
							$('#boton_reporte_nuevaarea').prop('disabled', false)


	
						}
	
						//Recargamos la tablas para bloquear los botonoes
						tabla_categorias(proyecto.id);
						tabla_areas(proyecto.id);
	
						

					}
					


				},	
				beforeSend: function() {
					$('#btnFinalizarPoe').prop('disabled', true);

					//Mostramos el estutus dependiendo la accion
					if (nuevo == 1) {
						
						$('#btnFinalizarPoe').html('Finalizando POE <i class="fa fa-spin fa-spinner"></i>');
					} else {

						if (opcion == 1) {

							$('#btnFinalizarPoe').html('Bloqueando POE <i class="fa fa-spin fa-spinner"></i>');
							
						} else {
							$('#btnFinalizarPoe').html('Activando POE <i class="fa fa-spin fa-spinner"></i>');
							
						}
						
					}


				},
				error: function(dato) {
					console.error('Error:', dato.msj);
					return false;
				}
			});
			return false;
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

})