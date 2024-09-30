
//variables globales
var proyecto_id = 0;
var recsensorial_id = 0;
var proyecto_perfil = 0;
var proyecto_bloqueado = 0;



var datatable_ejecuciones = null;


// Lista de proveedores del sistema
var proveedores_asignados = 0;
var proveedores_lista = '';
// var proveedor_alcances = '';
var proyectoInterno = 0;

// Modulo evidencia
var evidencia_agente_id = 0;
var evidencia_agente = '';

// Modulo reportes
Menureportes_contador = 0;



//===============================================================


// Load PAGINA
$(document).ready(function()
{
	oculta_menustab_principal();
	tabla_ejecucion();

    $('[data-toggle="tooltip"]').tooltip();
    

	$('#tab_tabla_ejecucion').click();

});



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


function oculta_menustab_principal()
{
	$("#tab_info_ejecucion").css('display', 'none');
}


function muestra_menustab_principal()
{
	$("#tab_info_ejecucion").css('display', 'block');
}




//===============================================================


function tabla_ejecucion()
{
	try 
	{
		var ruta = "/ejecucionHI";

		if (datatable_ejecuciones != null)
		{
			datatable_ejecuciones.clear().draw();
			datatable_ejecuciones.ajax.url(ruta).load();
		}
		else
		{
			var numeroejecucion = 1;
			datatable_ejecuciones = $('#tabla_ejecucionHI').DataTable({
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
					    "data": "RECONOCIMIENTO_VINCULADO",
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




function consulta_recsensorial(recsensorial_id, recsensorial_opcion)
{
	if (recsensorial_id) 
	{
		$.ajax({
	        type: "GET",
	        dataType: "json",
	        url: "/proyectorecsensorial/"+recsensorial_id,
	        data:{},
	        cache: false,
	        success:function(dato)
	        {
	        	// campos hidden
				// recsensorial_errorvalidacion = parseInt(dato.error);
	        	//lo dejamos en 0 ya que antes se valiva el reconocimiento y despues se asignaba a el informe pero ahora ya no porque para hacer un reconocimiento tiene que estar relacionado a un proyecto
				recsensorial_errorvalidacion = 0;
				

			

	        	// tablas resumenes
	        	$('#folio_fisicos').html(dato.recsensorial.recsensorial_foliofisico);
				$('#folio_quimicos').html(dato.recsensorial.recsensorial_folioquimico);
	        	$('#folio_quimicos_cliente').html(dato.recsensorial.recsensorial_folioquimico);
	
	            $('#tabla_resumen_fisicos tbody').html(dato.fisicos_resumen);
				$('#tabla_resumen_quimicos tbody').html(dato.quimicos_resumen);
	            $('#tabla_resumen_quimicos_clientes tbody').html(dato.quimicos_resumen_cliente);
				
	        },
			beforeSend: function()
			{
				$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
				$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
				$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></td></tr>');
			},
			error: function(dato)
			{
				recsensorial_errorvalidacion = 0;

				// tablas resumenes
				$('#folio_fisicos').html("");
				$('#folio_quimicos').html("");
	        	$('#folio_quimicos_cliente').html("");
				$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');
				$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');
				$('#tabla_resumen_quimicos_clientes tbody').html('<tr><td colspan="4" style="text-align: center;">Error al consultar los datos</td></tr>');

				return false;
			}
	    });//Fin ajax
	}
	else
	{
	    // campos hidden
	    recsensorial_errorvalidacion = 0;


	    $('#folio_fisicos').html("");
		$('#folio_quimicos').html("");
	    $('#folio_quimicos_cliente').html("");

		$('#tabla_resumen_fisicos tbody').html('<tr><td colspan="4" style="text-align: center;">Sin datos</td></tr>');
		$('#tabla_resumen_quimicos tbody').html('<tr><td colspan="4" style="text-align: center;">Sin datos</td></tr>');
		$('#tabla_proveedores_fisicos tbody').html('<tr><td colspan="7">&nbsp;</td></tr>');
		$('#tabla_proveedores_quimicos tbody').html('<tr><td colspan="7">&nbsp;</td></tr>');
	}
}



// Selecciona Proyecto
$('#tabla_ejecucionHI tbody').on('click', 'td>button.mostrar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_ejecuciones.row(tr);

	consulta_menuparametros_evidencias(row.data().ID_PROYECTO);
	
    proyecto_id = row.data().ID_PROYECTO


    //DIV NOMBRE DEL PROYECTO
    $('.div_folio_proyecto').html(row.data().FOLIO);
    $('.div_folio_reconocimiento').html(row.data().RECONOCIMIENTO_VINCULADO);

	// mostrar menu tab
	muestra_menustab_principal();

	
	// Selecciona step form 1
	$( "#tab_info_ejecucion" ).click();
	

});


//===============================================================

var signatarios_opciones = [];
var parametros_opciones = [];
var bitacora_responsable_id = 0;
var bitacora_responsable_nombre = '';


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

			// Pintar menu opciones
			$('#lista_menu_parametros_evidencia').html(dato.opciones);

			if (parseInt(dato.opciones_total) > 0)
			{
				// Mostrar botones de AGREGAR
				$('#boton_nuevo_documentoevidencia').css('display', 'block');
				$('#boton_nuevo_fotosevidencia').css('display', 'block');
				$('#boton_nuevo_planosevidencia').css('display', 'block');

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
			}
			else
			{
				// Ocultar botones de AGREGAR
				$('#boton_nuevo_documentoevidencia').css('display', 'none');
				$('#boton_nuevo_fotosevidencia').css('display', 'none');
				$('#boton_nuevo_planosevidencia').css('display', 'none');

				// Vaciar contenidos
				$('#tabla_evidenciadocumentos tbody').html('');
				$('#evidencia_galeria_fotos').html('');
				$('#evidencia_galeria_planos').html('');
			}
		},
		beforeSend: function(){
			$('#lista_menu_parametros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></li>');
		},
		error: function(dato){
			$('#lista_menu_parametros_evidencia').html('<li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">Error al cargar los parametros</li>');
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


				if (parseInt(dato.puntosreales_estado) == 0)
				{
					$('#boton_bloquear_puntosreales').removeClass('btn-success');
					$('#boton_bloquear_puntosreales').addClass('btn-warning');
					$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Puntos reales bloqueado para edición');

					$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-ban"></i>');
					$('#boton_guardar_puntosreales').attr('disabled', true);
				}
				else
				{
					$('#boton_bloquear_puntosreales').removeClass('btn-warning');
					$('#boton_bloquear_puntosreales').addClass('btn-success');
					$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');

					$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
					$('#boton_guardar_puntosreales').attr('disabled', false);
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

		
		// // Seccion planos si es agente
		// if (evidencia_agente_id > 0)
		// {
		// 	$('#tabmenu_evidencia_3').css('display', 'block'); // Mostrar menu planos
		// }
		// else // informacion general
		// {
		// 	$('#tabmenu_evidencia_3').css('display', 'none'); // Ocultar menu planos
		// }

		
		// Activar menu evidencia Documentos
		$('#tabmenu_evidencia_1').click();

		
		// Consultar documentos
		consulta_evidencia_documentos(proyecto_id, agente_id, agente_nombre);

		
		// Consultar Fotos
		consulta_evidencia_fotos(proyecto_id, agente_id, agente_nombre);

		
		// Consultar planos excepto para los de BEI
		if (evidencia_agente != 'BEI') {
			consulta_evidencia_planos(proyecto_id, agente_id, agente_nombre);
		}

		
		// Activa botones NUEVO / GUARDAR
		if (nombre_rol == "CoordinadorPsicosocial" || nombre_rol == "Psicólogo")
		{
			if (agente_nombre == "Factores psicosociales" && parseInt(proyecto_bloqueado) == 0)
			{
				$('#boton_nuevo_documentoevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_documento').css('display', 'block');

				$('#boton_nuevo_fotosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_fotos').css('display', 'block');

				$('#boton_nuevo_planosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_planos').css('display', 'block');
			}
			else
			{
				$('#boton_nuevo_documentoevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_documento').css('display', 'none');

				$('#boton_nuevo_fotosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_fotos').css('display', 'none');

				$('#boton_nuevo_planosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_planos').css('display', 'none');
			}
		}
		else
		{
			if (parseInt(proyecto_bloqueado) == 0)
			{
				$('#boton_nuevo_documentoevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_documento').css('display', 'block');

				$('#boton_nuevo_fotosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_fotos').css('display', 'block');

				$('#boton_nuevo_planosevidencia').attr('disabled', false);
				$('#boton_guardar_evidencia_planos').css('display', 'block');
			}
			else
			{
				$('#boton_nuevo_documentoevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_documento').css('display', 'none');

				$('#boton_nuevo_fotosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_fotos').css('display', 'none');

				$('#boton_nuevo_planosevidencia').attr('disabled', true);
				$('#boton_guardar_evidencia_planos').css('display', 'none');
			}
		}

		//Ocultamos los planos ya que no son necesarios para los BEI
		if (evidencia_agente == 'BEI') {
			$('#planos_ejecucion').css('display', 'none')
		} else {
			$('#planos_ejecucion').css('display', 'block')
			
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
	        text: "",
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
					beforeSend: function(){
						$('#boton_guardar_evidencia_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_documento').attr('disabled', true);
					},
					error: function(dato) {
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
		$('#boton_guardar_evidencia_documento').css('display', 'block');
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

		// ACTIVAR seccion fotos carpetas
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
		// Desactivar seccion fotos carpetas
		$(".divevidencia_seccion_fotoscarpeta").css('display', 'none');

		// No requerir campos
		$("#proyectoevidenciafoto_carpeta").attr('required', false);
		$("#inputevidenciafotosquimicos").attr('required', false);


		// ACTIVAR seccion fotos Fisicos
		$(".divevidencia_seccion_fotosfisicos").css('display', 'block');

		// CAMPOS
		$("#proyectoevidenciafoto_nopunto").attr('required', false);
		$("#proyectoevidenciafoto_descripcion").attr('required', true);
		$("#inputevidenciafotofisicos").attr('required', true);


		if (parseInt(evidencia_agente_id) == 15)
		{
			$("#fotosfisicos_campo_punto").css('display', 'none');
			$("#proyectoevidenciafoto_nopunto").attr('required', false);

			$("#fotosfisicos_campo_partida").css('display', 'none');
			$("#catreportequimicospartidas_id").attr('required', false);
		}
		else
		{
			$("#fotosfisicos_campo_partida").css('display', 'none');
			$("#catreportequimicospartidas_id").attr('required', false);

			$("#fotosfisicos_campo_punto").css('display', 'inline-block');
			$("#proyectoevidenciafoto_nopunto").attr('required', false);
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

	// Desactivar seccion fotos carpetas
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
		$("#catreportequimicospartidas_id").attr('required', false);

		$("#catreportequimicospartidas_id").val(foto_punto);
		$("#proyectoevidenciafoto_carpeta").val($('#catreportequimicospartidas_id').find(":selected").text());
	}
	else
	{
		$("#fotosfisicos_campo_partida").css('display', 'none');
		$("#catreportequimicospartidas_id").attr('required', false);

		$("#fotosfisicos_campo_punto").css('display', 'inline-block');
		$("#proyectoevidenciafoto_nopunto").attr('required', false);

		$("#proyectoevidenciafoto_nopunto").val(foto_punto);
	}

	
	// CAMPOS
	// $("#proyectoevidenciafoto_nopunto").attr('required', true);
	$("#proyectoevidenciafoto_descripcion").attr('required', true);
	$("#inputevidenciafotofisicos").attr('required', false);


	// Llenar campos
	$("#evidenciafotos_id").val(foto_id);
	$("#proyectoevidenciafoto_descripcion").val(foto_descripcion);


	// MOSTRAR FOTO
	var archivo = foto_ruta;
    var extension = archivo.substring(archivo.lastIndexOf("."));
	var imagenUrl = '/proyectoevidenciafotomostrar/0/'+foto_id;
	$('#inputevidenciafotofisicos').dropify().data('dropify').destroy();
	$('#inputevidenciafotofisicos').dropify().data('dropify').settings.defaultFile = imagenUrl+extension;
	$('#inputevidenciafotofisicos').dropify().data('dropify').init();

	// Activar boton guardar
	if (proyecto_perfil == 1) //Superusuario, Administrador
	{
		$('#boton_guardar_evidencia_fotos').css('display', 'block');
	}
	else
	{
		$('#boton_guardar_evidencia_fotos').css('display', 'none');
	}

	// mostrar modal
	$('#modal_evidencia_fotos').modal({backdrop:false});
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
	        title: "¡Confirme guardar foto (s)!",
	        text: "",
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
			timer: 5000,
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

	// ACTIVAR seccion fotos carpetas
	$(".divevidencia_seccion_fotoscarpeta").css('display', 'block');
	
	// CAMPOS
	$("#proyectoevidenciafoto_carpeta").attr('readonly', true);
	$("#inputevidenciafotosquimicos").attr('required', true);
	$("#catreportequimicospartidas_id").attr('required', false); // Campo partida quimicos

	// Activar boton guardar
    $('#boton_guardar_evidencia_fotos').css('display', 'block');

    // mostrar modal
	$('#modal_evidencia_fotos').modal({backdrop:false});
}


function opcion_nodisponible()
{
	swal({
		title: "Opción no disponible",
		text: "Solamente puede hacer cambios con la autorización del administrador",
		type: "info", // warning, error, success, info
		buttons: {
			visible: false, // true , false
		},
		timer: 2000,
		showConfirmButton: false
	});
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
		$("#planos_campo_partida").css('display', 'none');
		$("#planoscatreportequimicospartidas_id").attr('disabled', false);
		$("#planoscatreportequimicospartidas_id").attr('required', false);
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


function evidencia_plano_descargar(foto_opcion, foto_id)
{
	window.open("/proyectoevidenciaplanosmostrar/"+foto_opcion+"/"+foto_id);
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

	// if (parseInt(partida_id) > 0)
	// {
	// 	$("#planos_campo_partida").css('display', 'none');
	// 	$("#planoscatreportequimicospartidas_id").val(partida_id);
	// 	$("#planoscatreportequimicospartidas_id").attr('required', false);
	// 	$("#planoscatreportequimicospartidas_id").attr('disabled', true);
	// }
	// else
	// {
	// 	$("#planos_campo_partida").css('display', 'none');
	// 	$("#planoscatreportequimicospartidas_id").attr('required', false);
	// }


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
	        title: "¡Confirme guardar plano!",
	        text: "",
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
					beforeSend: function(){
						$('#boton_guardar_evidencia_planos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
						$('#boton_guardar_evidencia_planos').attr('disabled', true);
					},
					error: function(dato) {
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
		return false;
	}
});


//===============================================================


function verifica_puntosreales(puntos_asignados, puntos_reales, input_index)
{
	// alert(puntos_asignados+' - '+puntos_reales);
	if (parseInt(puntos_asignados) == parseInt(puntos_reales))
	{
		$('#puntorealobservacion_'+input_index).val('');
		$('#puntorealobservacion_'+input_index).attr('placeholder', '');
		$('#puntorealobservacion_'+input_index).attr('required', false);
		$('#puntorealobservacion_'+input_index).attr('readonly', true);
	}
	else
	{
		$('#puntorealobservacion_'+input_index).attr('placeholder', 'Observación requerida');
		$('#puntorealobservacion_'+input_index).attr('readonly', false);
		$('#puntorealobservacion_'+input_index).attr('required', true);
	}
}


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


$("#boton_bloquear_puntosreales").click(function()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectopuntosrealesactivo/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			// $('#boton_bloquear_puntosreales').html(dato.opciones_menu);

			if (parseInt(dato.puntosreales_estado) == 0)
			{
				$('#boton_bloquear_puntosreales').removeClass('btn-success');
				$('#boton_bloquear_puntosreales').addClass('btn-warning');
				$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Puntos reales bloqueado para edición');

				$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-ban"></i>');
				$('#boton_guardar_puntosreales').attr('disabled', true);
			}
			else
			{
				$('#boton_bloquear_puntosreales').removeClass('btn-warning');
				$('#boton_bloquear_puntosreales').addClass('btn-success');
				$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');

				$('#boton_guardar_puntosreales').html('Guardar <i class="fa fa-save"></i>');
				$('#boton_guardar_puntosreales').attr('disabled', false);
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
		},
		beforeSend: function()
		{
			$('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-spin fa-spinner""></i></span> Puntos reales actualizando estado');
		},
		error: function(dato)
		{
			// $('#boton_bloquear_puntosreales').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición');
			return false;
		}
	});//Fin ajax
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
						bitacora_responsable_id = json.bitacora_responsable_id;
						bitacora_responsable_nombre = json.bitacora_responsable_nombre;
						signatarios_opciones = json.signatarios_opciones;
						parametros_opciones = json.parametros_opciones;

						// Actualiza botones estado
						if (parseInt(json.bitacoramuestreo_estado) == 0)
						{
							$('#boton_bloquear_bitacoramuestreo').removeClass('btn-success');
							$('#boton_bloquear_bitacoramuestreo').addClass('btn-warning');
							$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-lock"></i></span> Bitácora de muestreo bloqueado para edición');

							$('#boton_nuevo_bitacoramuestreo').attr('disabled', true);
							$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);
							$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-ban"></i>');
						}
						else
						{
							$('#boton_bloquear_bitacoramuestreo').removeClass('btn-warning');
							$('#boton_bloquear_bitacoramuestreo').addClass('btn-success');
							$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Bitácora de muestreo desbloqueado para edición');

							$('#boton_nuevo_bitacoramuestreo').attr('disabled', false);
							$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);
							$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
						}


						$('#bitacora_totalpuntos').html(json.total_puntos);
						$('#tabla_bitacora_resumen tbody').html(json.bitacora_resumen);


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
						data: "dia_adjunto",
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
				rowsGroup: [0, 1, 5, 6], //agrupar filas
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


$("#boton_nuevo_bitacoramuestreo").click(function()
{
	// Borrar formulario
	$('#form_evidencia_bitacoramuestreo').each(function(){
		this.reset();
	});


	$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'inline-block');


	$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-center">No hay fotos seleccionadas</div>');


	var signatario_select_opciones = '<option value="">&nbsp;</option>';
	$.each(signatarios_opciones, function( index, value )
	{
		signatario_select_opciones += '<option value="'+value.signatario_id+'">'+value.signatario_Nombre+'</option>';
	});


	var parametros_select_opciones = '<option value="">&nbsp;</option>';
	$.each(parametros_opciones, function( index, value )
	{
		parametros_select_opciones += '<option value="'+value.agente_id+'">'+value.agente_nombre+'</option>';
	});


	$('#tabla_bitacoramuestreo_personal tbody').html('<tr>'+
															'<td width="250">'+
																'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>'+
																	signatario_select_opciones+
																'</select>'+
																'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;">'+
															'</td>'+
															'<td width="230">'+
																'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>'+
																	parametros_select_opciones+
																'</select>'+
																'<input type="hidden" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" required>'+
															'</td>'+
															'<td width="150">'+
																'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" required>'+
															'</td>'+
															'<td width="">'+
																'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>'+
															'</td>'+
															'<td width="60">'+
																'<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x" style="margin: 0px;"></i></button>'+
															'</td>'+
														'</tr>');

	$('[data-toggle="tooltip"]').tooltip();

	// Campos Hidden
    $("#bitacoramuestreo_id").val(0);
    $("#proyectoevidenciabitacora_usuario_id").val(bitacora_responsable_id);
    $("#proyectoevidenciabitacora_usuario_nombre").val(bitacora_responsable_nombre);
    $("#proyectoevidenciabitacora_fechacarga").val('PENDIENTE');

    // mostrar modal
	$('#modal_evidencia_bitacoramuestreo').modal({backdrop:false});
});


function bitacora_personal(select_signatario)
{
	if (parseInt(select_signatario.value) > 0)
	{
		$.each($('.bitacorasignatario'), function( index, value )
		{
			if (this == select_signatario)
			{
				// alert(index+' - '+this.value);
				var select =  $(this)[0]; //Select array encontrado

				for (var i = 0; i < select.length; i++) // recorrer opciones select
				{
					var option = select.options[i];
					// alert (option.text + ' ' + option.value);

					if (parseInt(select_signatario.value) == parseInt(option.value)) // Opcion elegida
					{
						// alert (option.value + ' - ' + option.text);
						$("input[name='bitacora_signatario_nombre[]']").eq(index).val(option.text); //Capturamos nombre del personal
					}
				}
			}
		});
	}
	else
	{
		$.each($('.bitacorasignatario'), function( index, value )
		{
			if (this == select_signatario)
			{
				$("input[name='bitacora_signatario_nombre[]']").eq(index).val(''); // Vaciar campo nombre personal
			}
		});
	}
}


function bitacora_parametro(select_parametro)
{
	if (parseInt(select_parametro.value) > 0)
	{
		$.each($('.bitacoraparametro'), function( index, value )
		{
			if (this == select_parametro)
			{
				if (parseInt(select_parametro.value) != 15) // Diferente de quimicos
				{
					// alert(index+' - '+this.value);
					var select =  $(this)[0]; //Select array encontrado

					for (var i = 0; i < select.length; i++) // recorrer opciones select
					{
						var option = select.options[i];
						// alert (option.text + ' ' + option.value);

						if (parseInt(select_parametro.value) == parseInt(option.value)) // Opcion elegida
						{
							// alert (option.value + ' - ' + option.text);
							$("input[name='bitacora_agente_nombre[]']").eq(index).val(option.text); //Capturamos nombre del parametro
							$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'hidden'); // Ocultar campo
						}
					}
				}
				else
				{
					// alert (option.value + ' - ' + option.text);
					$("input[name='bitacora_agente_nombre[]']").eq(index).val(''); // Vaciamos campo
					$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'text'); // Mostrar campo
				}
			}
		});
	}
	else
	{
		$.each($('.bitacoraparametro'), function( index, value )
		{
			if (this == select_parametro)
			{
				$("input[name='bitacora_agente_nombre[]']").eq(index).val(''); // Vaciamos campo
				$("input[name='bitacora_agente_nombre[]']").eq(index).attr('type', 'hidden'); // Ocultar campo
			}
		});
	}
}


$("#botonnuevo_bitacoramuestreo_signatario").click(function()
{
	var signatario_select_opciones = '<option value="">&nbsp;</option>';
	$.each(signatarios_opciones, function( index, value )
	{
		signatario_select_opciones += '<option value="'+value.signatario_id+'">'+value.signatario_Nombre+'</option>';
	});


	var parametros_select_opciones = '<option value="">&nbsp;</option>';
	$.each(parametros_opciones, function( index, value )
	{
		parametros_select_opciones += '<option value="'+value.agente_id+'">'+value.agente_nombre+'</option>';
	});


	$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>'+
															'<td width="250">'+
																'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>'+
																	signatario_select_opciones+
																'</select>'+
																'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;">'+
															'</td>'+
															'<td width="230">'+
																'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>'+
																	parametros_select_opciones+
																'</select>'+
																'<input type="hidden" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" required>'+
															'</td>'+
															'<td width="150">'+
																'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" required>'+
															'</td>'+
															'<td width="">'+
																'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>'+
															'</td>'+
															'<td width="60">'+
																'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>'+
															'</td>'+
														'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#divtabla_bitacoramuestreo_personal').animate({
		scrollTop: $('#tabla_bitacoramuestreo_personal > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$("#botonnuevo_bitacoramuestreo_adicional").click(function()
{
	$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>'+
															'<td width="250">'+
																'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" style="display: none;" required>'+
																	'<option value="">&nbsp;</option>'+
																	'<option value="0" selected>N/A</option>'+
																'</select>'+
																'<input type="text" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="" placeholder="Nombre personal adicional" required>'+
															'</td>'+
															'<td width="230">'+
																'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" style="display: none;" required>'+
																	'<option value="">&nbsp;</option>'+
																	'<option value="0" selected>N/A</option>'+
																'</select>'+
																'<input type="text" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 0px;" value="N/A" readonly>'+
															'</td>'+
															'<td width="150">'+
																'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="0" readonly>'+
															'</td>'+
															'<td width="">'+
																'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required></textarea>'+
															'</td>'+
															'<td width="60">'+
																'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>'+
															'</td>'+
														'</tr>');

	// desplazar a la ultima fila de la tabla
	$('#divtabla_bitacoramuestreo_personal').animate({
		scrollTop: $('#tabla_bitacoramuestreo_personal > tbody > tr:last').position().top //ultima fila
	}, 500);
});


$('#tabla_bitacoramuestreo_personal tbody').on('click', 'td>button.eliminar', function()
{
    // obtener fila tabla
    var fila = $(this);
    
    // confirmar
    swal({   
        title: "¿Eliminar persona?",   
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
                 text: "Persona eliminada de la lista",
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


var evidenciabitacora_fotos = [];


function bitacorafotos_redimencionar(campo_file)
{
	if (parseInt($(campo_file).get(0).files.length) > 5) //Solo 20 archivos
	{
		swal({
			title: "Solo se puede subir hasta 5 fotos",
			text: "Seleccionadas actualmente ["+$(campo_file).get(0).files.length+"]",
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
		evidenciabitacora_fotos.length = 0;

		// Mostrar mensaje de espera
		$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-danger"><i class="fa fa-spin fa-spinner"></i> Cargando foto (s), por favor espere...</div>');
		$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);

		// Iniciar recorrido de arreglo FILES
		redimencionar_foto(0);

		// Funcion redimencionar imagenes
		function redimencionar_foto(contador)
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

						// // Dimensiones Nuevas
						// if (parseInt(width) > 8000)
						// {
						// 	var MAX_WIDTH = 4000; //Ancho de la imagen
						// 	var MAX_HEIGHT = 3000; //Alto de la imagen
						// }
						// else
						// {
						// 	var MAX_WIDTH = 1200; //Ancho de la imagen
						// 	var MAX_HEIGHT = 900; //Alto de la imagen
						// }

						var MAX_WIDTH = 1200; //Ancho de la imagen
						var MAX_HEIGHT = 900; //Alto de la imagen

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
						evidenciabitacora_fotos.push(dataurl+"*"); // meter a un arreglo

						// Redimensionar la Siguiente imagen
						contador += 1;
						redimencionar_foto(contador);

						// Mensaje por imagen
						console.log("Nuevas dimensiones foto "+(contador - 1)+" ->", width, height);
					}, 100);
				}
			}
			else
			{
				// Quitar mensaje de espera
				$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-info"><i class="fa fa-check"></i> Fotos cargadas correctamente.</div>');
				$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);
			}
		}
	}
}


function bitacorafotos_galeria(bitacora_id, proyecto_id)
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciabitacorafotos/"+bitacora_id+"/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			$('#proyectoevidenciabitacora_fotosgaleria').html(dato.fotos);
			$('[data-toggle="tooltip"]').tooltip();
		},
		beforeSend: function()
		{
			$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner" style="font-size: 40px!important;"></i></div>');
		},
		error: function(dato)
		{
			$('#proyectoevidenciabitacora_fotosgaleria').html('<div class="col-12 text-danger">Error al cargar las fotos</div>');
			return false;
		}
	});//Fin ajax
}


function bitacorafoto_mostrar(foto_id)
{
	// mostrar modal
	$('#modal_bitacora_foto').modal({backdrop:false});

	$('#bitacora_visor').attr('src', '/proyectoevidenciabitacorafotomostrar/'+foto_id+"/0");
}


function bitacorafoto_descargar(foto_id)
{
	window.open("/proyectoevidenciabitacorafotomostrar/"+foto_id+"/"+1); //1 descargar
}


function bitacorafoto_eliminar(foto_id, bitacora_id)
{
	// Confirmar accion
	swal({   
        title: "¡Confirme eliminar foto!",
        text: "Evidencia bitácora",
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
        	// Confirmar accion
        	swal({   
		        title: "¡Confirme nuevamente eliminar foto!",
		        text: "Evidencia bitácora",
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
		        	// cerrar msj
					swal.close();

					// Eliminar foto
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/proyectoevidenciabitacorafotoeliminar/"+foto_id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza galeria de fotos
							bitacorafotos_galeria(bitacora_id, proyecto_id);

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


$("#modal_bitacorafoto_cerrar").click(function()
{
    $('#bitacora_visor').attr('src', '/assets/images/fondovisor.jpg');
});


$('#tabla_bitacora tbody').on('click', 'td.editar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_bitacora.row(tr);

	$('#form_evidencia_bitacoramuestreo').each(function(){
		this.reset();
	});

	
	// Boton guardar
	if (parseInt(proyecto_perfil) == 1) //Ddministradores
	{
		$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'inline-block');
	}
	else
	{
		$('#boton_guardar_evidencia_bitacoramuestreo').css('display', 'block');
	}


	// Campos Hidden
    $("#bitacoramuestreo_id").val(row.data().id);
    $("#proyectoevidenciabitacora_usuario_id").val(row.data().usuario_id);
    $("#proyectoevidenciabitacora_usuario_nombre").val(row.data().usuario_nombre);
    $("#proyectoevidenciabitacora_fechacarga").val(row.data().created_at);

	// Llenar campos
	$("#proyectoevidenciabitacora_fecha").val(row.data().proyectoevidenciabitacora_fecha);
	$('#proyectoevidenciabitacora_observacion').val(row.data().proyectoevidenciabitacora_observacion);


	// Llenar tabla
	$('#tabla_bitacoramuestreo_personal tbody').html('');


	// Recorrer tabla
	var fila = 0;
	datatable_bitacora.rows().every( function ( rowIdx, tableLoop, rowLoop )
	{
		var data = this.data();

		if (parseInt(row.data().id) == parseInt(data.id))
		{
			if (parseInt(data.signatario_id) > 0)
			{
				var signatario_select_opciones = '<option value="">&nbsp;</option>';
				$.each(signatarios_opciones, function( index, value )
				{
					if (parseInt(data.signatario_id) == parseInt(value.signatario_id))
					{
						signatario_select_opciones += '<option value="'+value.signatario_id+'" selected>'+value.signatario_Nombre+'</option>';
					}
					else
					{
						signatario_select_opciones += '<option value="'+value.signatario_id+'">'+value.signatario_Nombre+'</option>';
					}
				});


				var parametros_select_opciones = '<option value="">&nbsp;</option>';
				$.each(parametros_opciones, function( index, value )
				{
					if (parseInt(data.agente_id) == parseInt(value.agente_id))
					{
						parametros_select_opciones += '<option value="'+value.agente_id+'" selected>'+value.agente_nombre+'</option>';
					}
					else
					{
						parametros_select_opciones += '<option value="'+value.agente_id+'">'+value.agente_nombre+'</option>';
					}
				});


				var boton_eliminar = '<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>';
				if (parseInt(fila) == 0)
				{
					boton_eliminar = '<button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x" style="margin: 0px;"></i></button>';
				}


				var quimico_tipo = 'hidden';
				if (parseInt(data.agente_id) == 15)
				{
					quimico_tipo = 'text';
				}


				$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>'+
																		'<td width="250">'+
																			'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" onchange="bitacora_personal(this);" required>'+
																				signatario_select_opciones+
																			'</select>'+
																			'<input type="hidden" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="'+data.signatario_nombre+'">'+
																		'</td>'+
																		'<td width="230">'+
																			'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" onchange="bitacora_parametro(this);" required>'+
																				parametros_select_opciones+
																			'</select>'+
																			'<input type="'+quimico_tipo+'" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 6px;" placeholder="Nombre del químico" value="'+data.agente_nombre+'" required>'+
																		'</td>'+
																		'<td width="150">'+
																			'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="'+data.agente_puntos+'" required>'+
																		'</td>'+
																		'<td width="">'+
																			'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required>'+data.signatario_observacion+'</textarea>'+
																		'</td>'+
																		'<td width="60">'+
																			boton_eliminar+
																		'</td>'+
																	'</tr>');

				fila += 1;
			}
			else
			{
				$('#tabla_bitacoramuestreo_personal > tbody').append('<tr>'+
																		'<td width="250">'+
																			'<select class="custom-select form-control bitacorasignatario" name="bitacora_signatario_id[]" style="display: none;" required>'+
																				'<option value="">&nbsp;</option>'+
																				'<option value="0" selected>N/A</option>'+
																			'</select>'+
																			'<input type="text" class="form-control" name="bitacora_signatario_nombre[]" style="margin-top: 6px;" value="'+data.signatario_nombre+'" placeholder="Nombre personal adicional" required>'+
																		'</td>'+
																		'<td width="230">'+
																			'<select class="custom-select form-control bitacoraparametro" name="bitacora_agente_id[]" style="display: none;" required>'+
																				'<option value="">&nbsp;</option>'+
																				'<option value="0" selected>N/A</option>'+
																			'</select>'+
																			'<input type="text" class="form-control" name="bitacora_agente_nombre[]" style="margin-top: 0px;" value="N/A" readonly>'+
																		'</td>'+
																		'<td width="150">'+
																			'<input type="number" min="0" class="form-control" name="bitacora_agente_puntos[]" value="0" readonly>'+
																		'</td>'+
																		'<td width="">'+
																			'<textarea  class="form-control" rows="3" name="bitacora_signatario_observacion[]" required>'+data.signatario_observacion+'</textarea>'+
																		'</td>'+
																		'<td width="60">'+
																			'<button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x" style="margin: 0px;"></i></button>'+
																		'</td>'+
																	'</tr>');
			}
		}
	});


	bitacorafotos_galeria(row.data().id, proyecto_id);


	$('[data-toggle="tooltip"]').tooltip();


	// Titulo del modal
	$('#modal_evidencia_bitacoramuestreo .modal-title').html('Bitácora de muestreo');

	// mostrar modal
	$('#modal_evidencia_bitacoramuestreo').modal({backdrop:false});
});


$("#boton_guardar_evidencia_bitacoramuestreo").click(function()
{
	// valida campos vacios
	var valida = this.form.checkValidity();
	if (valida)
	{
		// Confirma guardar
		swal({   
	        title: "¡Confirme guardar!",
	        text: "Bitácora de muestreo día "+$('#proyectoevidenciabitacora_fecha').val()+" \n\n Después de guardar solo se pueden hacer cambios con la autorización del administrador.",
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
				// Confirma guardar
				swal({   
			        title: "¡Confirme nuevamente guardar!",
			        text: "Bitácora de muestreo día "+$('#proyectoevidenciabitacora_fecha').val()+" \n\n Después de guardar solo se pueden hacer cambios con la autorización del administrador.",
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
						$('#form_evidencia_bitacoramuestreo').ajaxForm({
							dataType: 'json',
							type: 'POST',
							url: '/proyectoevidenciabitacora',
							data: {
								proyecto_id: proyecto_id,
								evidenciabitacora_fotos: evidenciabitacora_fotos,
							},
							resetForm: false,
							success: function(dato)
							{
								// Actualiza tabla
								tabla_bitacora(proyecto_id);

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
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);

								// cerrar modal
								$('#modal_evidencia_bitacoramuestreo').modal('hide');
							},
							beforeSend: function(){
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', true);
							},
							error: function(dato) {
								// actualiza boton
								$('#boton_guardar_evidencia_bitacoramuestreo').html('Guardar <i class="fa fa-save"></i>');
								$('#boton_guardar_evidencia_bitacoramuestreo').attr('disabled', false);

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


$('#tabla_bitacora tbody').on('click', 'td>button.eliminar', function()
{
	var tr = $(this).closest('tr');
	var row = datatable_bitacora.row(tr);

	swal({
		title: "¡Confirme que desea eliminar!",
		text: "El día "+row.data().proyectoevidenciabitacora_fecha+" de la bitácora de muestreo.",
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
				title: "¡Por seguridad confirme nuevamente eliminar!",
				text: "El día "+row.data().proyectoevidenciabitacora_fecha+" de la bitácora de muestreo.",
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
						url: "/proyectoevidenciabitacoraeliminar/"+row.data().id,
						data:{},
						cache: false,
						success:function(dato)
						{
							// Actualiza tabla
							tabla_bitacora(proyecto_id);

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


$("#boton_imprimir_bitacoramuestreo").click(function()
{
	// Visor
	$('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proyectoevidenciabitacoraimprimir/'+proyecto_id);
	// $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proyectoevidenciabitacoraimprimir/'+proyecto_id);


    // mostrar modal
	$('#modal_visor').modal({backdrop:false});
});

// hola

$("#boton_bloquear_bitacoramuestreo").click(function()
{
	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/proyectoevidenciabitacoraactivo/"+proyecto_id,
		data:{},
		cache: false,
		success:function(dato)
		{
			// Actualizar tabla
			tabla_bitacora(proyecto_id);


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
			$('#boton_bloquear_bitacoramuestreo').html('<span class="btn-label"><i class="fa fa-spin fa-spinner""></i></span> Bitácora de muestreo actualizando estado');
		},
		error: function(dato)
		{
			return false;
		}
	});//Fin ajax
});
