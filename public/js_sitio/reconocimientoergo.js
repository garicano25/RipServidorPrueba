//VARIABLES GLOBALES
var tabla_recsensorial = null;
var tabla_recsensorialareas = null;
var tabla_recsensorialcategorias = null;
var tabla_control_cambios = null;
var select_areacategorias_opciones = "";
var rutaMapa = '';
var optionSelectAreas = '';
var categoriasEvaluadas = '';
var areacategorias_total = 0;
var recsensorial_perfil = 0;
var recsensorial = 0;
var normativa_id = 0;
var tabla = null;
var HIGIENE = 1;

var Tablarecocategoriasergo = null;
var Tablarecoareasergo = null;
var Tablarecofichasergo = null;
var tabla_reporte_categoria = null;
var tabla_reporte_area = null;
var tabla_reporte_revisiones  = null 

ID_CATEGORIA_ERGO = 0;
ID_AREA_ERGO = 0;
ID_FICHAS_TECNICAS = 0;

//--------------------------------------------------CARGA PRINCIPAL---------------------------------------------------------//
$(document).ready(function () {
	// mostrar tabla
	tabla_reconocimientosensorial();

	// desactivar menu agentes
	$("#tab_menu2").css('display', 'none');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');

	
	$('#inputfotomapa').dropify({
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


		$('#inputfotoplano').dropify({
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
	
	
		$('#inputfotoinstalacion').dropify({
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
	
	
		$('#inputfotomapaderiesgo').dropify({
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
	
	
	// Inicializar tooltip
	$('[data-toggle="tooltip"]').tooltip();
});


$(document).ready(function () {
	let timeout;

	$('#proyecto_folio').on('change', function (e) {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
			obtenerEstructuraProyectos($(this).val(), 1);
		}, 1500); // Esperamos 1.5 segundos para ejecutar la funcion
	});
});

//-------------------------------------------------TABS---------------------------------------------------------------------//
$('.link_menuprincipal').click(function () {
	switch (this.id) {
		case "tab_menu1":
			// Menu
			$("#tab_menu2").css('display', 'none');
			$("#tab_menu3").css('display', 'none');

			// Contenido
			$("#tab_1").css('display', 'block');
			$("#tab_2").css('display', 'none');
			$("#tab_3").css('display', 'none');

		break;
		case "tab_menu2":
			$("#tab_2").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_3").css('display', 'none');

			$("#steps_menu_tab1").click();
			break;
		
		case "tab_menu3":
			$("#tab_3").css('display', 'block');
			$("#tab_1").css('display', 'none');
			$("#tab_2").css('display', 'none');

			$("#steps_menu_tab1").click();

			cargarDatosGeneralesInformeReco();
			cargarDatosInformesRecoErgo();
			cargarDefinicionesInformeErgo();
			cargarGraficas();
			tablaReporteCategoriasErgo();
			tablaReporteAreasErgo();
			cargarRecomendacionesInformeErgo();

			  tablaVersionesRecoErgo();

			validarEdicionRecoErgo();
			


		$('#RUTA_IMAGEN_PORTADA').dropify({
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



			$('#RUTA_IMAGEN_UBICACION').dropify({
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



			$('#INFORME_RESPONSABLE1DOCUMENTO').dropify({
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

		$('#INFORME_RESPONSABLE2DOCUMENTO').dropify({
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




			

			break;
		

		default:
			break;
	}
});

$('.multisteps-form__progress-btn').click(function () {
	switch (this.id) {
        case "steps_menu_tab2":
            recsensorial = $("#recsensorial_id").val(); 
            mostrartablarecocategoriasergo();
            break;
		case "steps_menu_tab3":
			recsensorial = $("#recsensorial_id").val(); 
            mostrartablarecoareasergo();
		break;
		case "steps_menu_tab4":
			recsensorial = $("#recsensorial_id").val(); 
            mostrarTablarecofichasergo();
		break;
		default:
		break;
	}
});

//-----------------------------------------------------INPUT-----------------------------------------------------------------//
$('input[name="informe_del_cliente"]').change(function () {

	var valor = $(this).val();

	if (valor === '1') {

		cliente_lista(lista_clientes, $('#cliente_id').val())

	} else if (valor === '0') {
		///Limpiamos la informacion del cliente
		$("#empresa").val('');
		$("#rfc").val('');
		$("#representantelegal").val('');

	}
});

//-----------------------------------------------------BOTONES---------------------------------------------------------------//
$("#boton_nuevo_reconocimiento").click(function () {


	$('#inputfotomapa').attr('required', false);

	$('#titleOrganizacionLabel').fadeIn();

	$("#estructura-container").css('display', 'none');

	// mostrar TAB principal
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu3").css('display', 'none');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');
	$("#tab_menu6").css('display', 'none');


	// ocultar secciones
	$("#steps_menu_tab2").css('display', 'none');
	$("#steps_menu_tab3").css('display', 'none');
	$("#steps_menu_tab4").css('display', 'none');
	$("#steps_menu_tab5").css('display', 'none');

	// LIMPIAR TITULO DEL RECONOCIMIENTO
	$(".div_reconocimiento_folios").html('FOLIO');
	$(".div_reconocimiento_alcance").html('Reconocimiento');
	$(".div_reconocimiento_instalacion").html('INSTALACIÓN');

	// Borrar border warning
	$('#divListaAgentes').removeClass('checkbox_warning');


    // Resetear campo FOTO INSTALACION
    

    // inicializar campo FOTO mapa ubicacion
	$('#inputfotomapa').dropify({
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

	$('#inputfotomapa').val('');
	$('#inputfotomapa').dropify().data('dropify').resetPreview();
	$('#inputfotomapa').dropify().data('dropify').clearElement();


	// inicializar campo FOTO plano instalacion
	$('#inputfotoplano').dropify({
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


	
	$('#inputfotoplano').val('');
	$('#inputfotoplano').dropify().data('dropify').resetPreview();
	$('#inputfotoplano').dropify().data('dropify').clearElement();



	// inicializar campo FOTO plano instalacion
	$('#inputfotoinstalacion').dropify({
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

	$('#inputfotoinstalacion').val('');
	$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
	$('#inputfotoinstalacion').dropify().data('dropify').clearElement();


	// inicializar campo FOTO plano instalacion
	$('#inputfotomapaderiesgo').dropify({
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

	$('#inputfotomapaderiesgo').val('');
	$('#inputfotomapaderiesgo').dropify().data('dropify').resetPreview();
	$('#inputfotomapaderiesgo').dropify().data('dropify').clearElement();


	// inicializar campo FOTO mapa ubicacion
	$('#TECNICO_DOC_IMG').dropify({
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

	// inicializar campo FOTO plano instalacion
	$('#CONTRATO_DOC_IMG').dropify({
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


	$("#boton_descargarfotoinstalacion").css('display', 'none');

	// BOton evidencia fotografica quimicos
	$("#boton_nueva_fotoevidenciaquimicos").attr('disabled', false);

	// Desactivar boton descargas FOTO
	$("#boton_descargarmapaubicacion").css('display', 'none');
	$("#boton_descargarplanoinstalacion").css('display', 'none');
	$("#boton_descargarmapaderiesgo").css('display', 'none');

	// Visor quimico PDF
	$('#visor_pdfquimicos').attr('src', '/assets/images/nada.jpg');
	$("#visor_pdfquimicos").css('display', 'none');
	$("#visor_pdfquimicos_icono").css('display', 'block');

	// Borrar formulario
	$('#form_recsensorial').each(function () {
		this.reset();
	});

	// Campos Hidden
	$("#recsensorial_id").val(0);

	// Desbloquear campos alcances
	$("#recsensorial_alcancefisico").attr('disabled', false);
	$("#recsensorial_alcancequimico").attr('disabled', false);

	//Campos exclusivos para Pemex
	$('#catregion_id').attr('disabled', false);
	$('#catsubdireccion_id').attr('disabled', false);
	$('#catgerencia_id').attr('disabled', false);
	$('#catactivo_id').attr('disabled', false);



	//Ocultamos la seccion de validar quimicos ya que esa parte se rellena una vez esta finalizado el reconocimiento
	$('#finalizarQuimico').fadeOut(0)


	//Seteamos y ocultamos los valores de clientes y contratos obtenidos por el cliente
	$('#cliente_id').val('')
	$('#contrato_id').val('')
	$('#requiere_contrato').val('')
	$('#descripcion_contrato').val('')
	$('#descripcion_cliente').val('')
	$('#infoCliente').empty().css('border-style', 'none')


	// seleccionar TAB	
	$("#steps_menu_tab1").click();
	$("#tab_menu2").click();

	//Cragamos la lista de los folios de los proyectos
	cargarFoliosProyecto(0);

});

$("#boton_descargarmapaubicacion").click(function () {
	window.open('/mostrarmapa/1/' + recsensorial);
});

$("#boton_descargarplanoinstalacion").click(function () {
	window.open('/mostrarplano/1/' + recsensorial);
});

$("#boton_descargarfotoinstalacion").click(function () {
	window.open('/mostrarfotoinstalacion/1/' + recsensorial);
});


$("#boton_descargarmapaderiesgo").click(function () {
	window.open('/mostrarmapariesgoergo/1/' + recsensorial);
});


// $("#boton_guardar_recsensorial").click(function () {

// 	$('#recsensorial_ordenservicio').attr('required', false);
// 	$('#inputfotomapa').attr('required', false);
// 	$('#inputfotomapa').prop('required', false);

// 	if ($('#proyecto_folio').val() != "") {

// 		// valida campos vacios
// 		var valida = this.form.checkValidity();
// 		if (valida) {


// 				swal({
// 					title: "¡Confirme que desea guardar!",
// 					text: "Reconocimiento sensorial",
// 					type: "warning",
// 					showCancelButton: true,
// 					confirmButtonColor: "#DD6B55",
// 					confirmButtonText: "Aceptar!",
// 					cancelButtonText: "Cancelar!",
// 					closeOnConfirm: false,
// 					closeOnCancel: false
// 				}, function (isConfirm) {
// 					if (isConfirm) {
// 						// cerrar msj confirmacion
// 						swal.close();

// 						// enviar datos
// 						$('#form_recsensorial').ajaxForm({
// 							dataType: 'json',
// 							type: 'POST',
// 							url: '/reconocimientoergo',
// 							data: {
// 								opcion: 1,
// 								higiene: HIGIENE,
// 							},
// 							resetForm: false,
// 							success: function (dato) {
// 								// Campos Hidden
// 								$("#recsensorial_id").val(dato.recsensorial.id);
// 								recsensorial = dato.recsensorial.id;

// 								// ocultar menu parametro en el modulo evaluacion
// 								menu_parametros_ocultar();

	
// 								activa_stepforms();

							

// 								// actualiza tabla general RECSENSORIAL
// 								tabla_reconocimientosensorial();

// 								// mensaje
// 								swal({
// 									title: "¡El reconocimiento fue guardado exitosamente!.",
// 									text: "" + dato.msj,
// 									type: "success", // warning, error, success, info
// 									buttons: {
// 										visible: false, // true , false
// 									},
// 									timer: 4500,
// 									showConfirmButton: false
// 								});

// 								// actualiza boton
// 								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
// 								return false;
// 							},
// 							beforeSend: function () {
// 								$('#boton_guardar_recsensorial').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
// 							},
// 							error: function (dato) {
// 								// actualiza boton
// 								$('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
// 								// mensaje
// 								swal({
// 									title: "Error",
// 									text: "Error en la acción: " + dato,
// 									type: "error", // warning, error, success, info
// 									buttons: {
// 										visible: false, // true , false
// 									},
// 									timer: 1500,
// 									showConfirmButton: false
// 								});
// 								return false;
// 							}
// 						}).submit();
// 						return false;
// 					}
// 					else {
// 						// mensaje
// 						swal({
// 							title: "Cancelado",
// 							text: "Acción cancelada",
// 							type: "error", // warning, error, success, info
// 							buttons: {
// 								visible: false, // true , false
// 							},
// 							timer: 500,
// 							showConfirmButton: false
// 						});
// 					}
// 				});
// 				return false;
			
// 		}


// 	} else {
// 		// mensaje

// 		$('html, body').animate({
// 			scrollTop: $("#primeraParte").offset().top
// 		}, 1000);

// 		swal({
// 			title: "Falta seleccionar un FOLIO de proyecto",
// 			text: "Es necesario seleccionar este reconocimiento con un FOLIO de proyecto para poder continuar!.",
// 			type: "warning", // warning, error, success, info
// 			buttons: {
// 				visible: false, // true , false
// 			},
// 			timer: 2000,
// 			showConfirmButton: false
// 		});
// 		return false;
// 	}
// });



$("#boton_guardar_recsensorial").click(function () {

    $('#recsensorial_ordenservicio').attr('required', false);
    $('#inputfotomapa').prop('required', false);

    if ($('#proyecto_folio').val() != "") {

        var valida = this.form.checkValidity();

        if (valida) {

            Swal.fire({
                title: "¡Confirme que desea guardar!",
                text: "Reconocimiento sensorial",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {

                if (result.isConfirmed) {

                    var formData = new FormData($('#form_recsensorial')[0]);
                    formData.append('opcion', 1);
                    formData.append('higiene', HIGIENE);

                    $.ajax({
                        url: '/reconocimientoergo',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,

                        beforeSend: function () {
                            $('#boton_guardar_recsensorial').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },

                        success: function (dato) {

                            $("#recsensorial_id").val(dato.recsensorial.id);
                            recsensorial = dato.recsensorial.id;

                            menu_parametros_ocultar();
                            activa_stepforms();
                            tabla_reconocimientosensorial();

                            Swal.fire({
                                icon: "success",
                                title: "¡El reconocimiento fue guardado exitosamente!",
                                text: dato.msj,
                                timer: 2500,
                                showConfirmButton: false
                            });

                            $('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');
                        },

                        error: function (xhr) {

                            console.log(xhr.responseText);

                            $('#boton_guardar_recsensorial').html('Guardar <i class="fa fa-save"></i>');

                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Error en la petición"
                            });
                        }
                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Cancelado",
                        text: "Acción cancelada",
                        timer: 1000,
                        showConfirmButton: false
                    });

                }

            });

            return false;

        }

    } else {

        $('html, body').animate({
            scrollTop: $("#primeraParte").offset().top
        }, 1000);

        Swal.fire({
            icon: "warning",
            title: "Falta seleccionar un FOLIO de proyecto",
            text: "Es necesario seleccionar este reconocimiento con un FOLIO de proyecto para poder continuar!",
            timer: 2000,
            showConfirmButton: false
        });

        return false;
    }
});

//-------------------------------------------------------TABLAS---------------------------------------------------------------//
$('#tabla_reconocimiento_sensorial tbody').on('click', 'td.mostrar', function () {
	var tr = $(this).closest('tr');
	var row = tabla_recsensorial.row(tr);
	$('#form_responsables').each(function () {
		this.reset();
	});

	$('#inputfotomapa').attr('required', false);
	$('#infoCliente').empty().css('border-style', 'none');


	if (row.data().proyecto_folio == null) {

		$(".organizacional").css('display', 'block');
		$("#estructura-container").css('display', 'none');


	} else {
		$(".organizacional").css('display', 'none');

		$("#estructura-container").css('display', 'flex');

	}
	$("#steps_menu_tab2").css('display', 'block');
	$("#steps_menu_tab3").css('display', 'block');
	$("#steps_menu_tab4").css('display', 'block');
	$("#steps_menu_tab5").css('display', 'block');

	// Menu principal
	$("#tab_menu2").css('display', 'block');
	$("#tab_menu3").css('display', 'block');
	$("#tab_menu4").css('display', 'none');
	$("#tab_menu5").css('display', 'none');

	// Borrar formulario
	$('#form_recsensorial').each(function () {
		this.reset();
	});

	// Campos hidden
	recsensorial = row.data().id;
	$("#recsensorial_id").val(row.data().id);

	//Obtemos la estructura del proyecto
	obtenerEstructuraProyectos(row.data().proyecto_folio, 0);

	//Obtenemos la lista de los folios de proyectos y los asignamos
	cargarFoliosProyecto(row.data().proyecto_folio);



	var cliente = row.data().descripcion_cliente == null ? "Sin asignar cliente" : row.data().descripcion_cliente;
	var contrato = row.data().descripcion_contrato == null ? "Sin asignar contrato" : row.data().descripcion_contrato;


	// $('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
	// 						<h4>${cliente}</h4>
	// 						<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> Formalización del servicio</h2>
	// 						<h4 class="mb-2">${contrato}</h4>`).css('border-style','dotted')

	$("#empresa").val(row.data().empresa);
	$("#codigopostal").val(row.data().codigopostal);
	$("#rfc").val(row.data().rfc);
	$("#ordenservicio").val(row.data().ordenservicio);
	$("#representantelegal").val(row.data().representantelegal);
	$("#representanteseguridad").val(row.data().representanteseguridad);
	$("#direccion").val(row.data().direccion);
	$("#instalacion").val(row.data().instalacion);
	$("#coordenadas").val(row.data().coordenadas);
	$("#fechainicio").val(row.data().fechainicio);
	$("#fechafin").val(row.data().fechafin);
	$("#actividadprincipal").val(row.data().actividadprincipal);
	$("#descripcionproceso").val(row.data().descripcionproceso);
	$("#observaciones").val(row.data().observaciones);

	$("#ordentrabajo_id").val(row.data().ordentrabajo_id);
	$("#proyecto_folio").val(row.data().proyecto_folio);


	//Mostramos si la informacion del contrato es la que se le da al reconocimiento
	if (row.data().informe_del_cliente == 1) {
		$('#informe_del_cliente_si').prop('checked', true);
	} else {
		$('#informe_del_cliente_no').prop('checked', true);

	}



		///////////////// OBTENER FOTO UBICACION /////////////////
		if (row.data().fotoubicacion) {
			var archivo = row.data().fotoubicacion;
			var extension = archivo.substring(archivo.lastIndexOf("."));
			var imagenUrl = '/mostrarmapaubicacionergo/0/' + row.data().id + extension;

			rutaMapa = imagenUrl

			// INPUT FOTO UBICACION
			if ($('#inputfotomapa').data('dropify')) {
				$('#inputfotomapa').dropify().data('dropify').destroy();
				// $('.dropify-wrapper').css('height', 400);
				$('#inputfotomapa').dropify().data('dropify').settings.defaultFile = imagenUrl;
				$('#inputfotomapa').dropify().data('dropify').init();
			}
			else {
				// $('#inputfotomapa').attr('data-height', 400);
				$('#inputfotomapa').attr('data-default-file', imagenUrl);
				$('#inputfotomapa').dropify({
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

			// No requerir campo FOTO
			$('#inputfotomapa').attr('required', false);

			// Activar boton descarga
			$("#boton_descargarmapaubicacion").css('display', 'block');
		}
		else {
			$("#boton_descargarmapaubicacion").css('display', 'none');
	}
	

		// 	var imagenUrl = '';

		// if (row.data().fotoubicacion) {
		// 	var archivo = row.data().fotoubicacion;
		// 	var extension = archivo.substring(archivo.lastIndexOf("."));
		// 	imagenUrl = '/mostrarmapaubicacionergo/0/' + row.data().id + extension;

		// 	rutaMapa = imagenUrl;

		// 	$("#boton_descargarmapaubicacion").css('display', 'block');
		// } else {
		// 	$("#boton_descargarmapaubicacion").css('display', 'none');
		// }

		// var drEvent = $('#inputfotomapa').data('dropify');

		// if (drEvent) {
		// 	drEvent.destroy();
		// }

		// $('#inputfotomapa').attr('data-default-file', imagenUrl);

		// // Inicializar SIEMPRE
		// $('#inputfotomapa').dropify({
		// 	messages: {
		// 		'default': 'Arrastre la imagen aquí o haga click',
		// 		'replace': 'Arrastre la imagen o haga clic para reemplazar',
		// 		'remove': 'Quitar',
		// 		'error': 'Ooops, ha ocurrido un error.'
		// 	},
		// 	error: {
		// 		'fileSize': 'Demasiado grande ({{ value }} max).',
		// 		'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
		// 		'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
		// 		'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
		// 		'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
		// 		'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
		// 	}
		// });

		// 	$('#inputfotomapa').attr('required', false);
			


	
	///////////// OBTENER PLANO INSTALACION /////////////
	
		if (row.data().fotoplano) {
			var archivo = row.data().fotoplano;
			var extension = archivo.substring(archivo.lastIndexOf("."));
			var imagenUrl = '/mostraplanoergo/0/' + row.data().id + extension;

			rutaMapa = imagenUrl

			// INPUT FOTO UBICACION
			if ($('#inputfotoplano').data('dropify')) {
				$('#inputfotoplano').dropify().data('dropify').destroy();
				// $('.dropify-wrapper').css('height', 400);
				$('#inputfotoplano').dropify().data('dropify').settings.defaultFile = imagenUrl;
				$('#inputfotoplano').dropify().data('dropify').init();
			}
			else {
				// $('#inputfotomapa').attr('data-height', 400);
				$('#inputfotoplano').attr('data-default-file', imagenUrl);
				$('#inputfotoplano').dropify({
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

			// No requerir campo FOTO
			$('#inputfotoplano').attr('required', false);

			// Activar boton descarga
			$("#boton_descargarplanoinstalacion").css('display', 'block');
		}
		else {
			$("#boton_descargarplanoinstalacion").css('display', 'none');
	}

		// var imagenUrl = '';

		// if (row.data().fotoplano) {
		// 	var archivo = row.data().fotoplano;
		// 	var extension = archivo.substring(archivo.lastIndexOf("."));
		// 	imagenUrl = '/mostraplanoergo/0/' + row.data().id + extension;

		// 	rutaMapa = imagenUrl;

		// 	$("#boton_descargarplanoinstalacion").css('display', 'block');
		// } else {
		// 	$("#boton_descargarplanoinstalacion").css('display', 'none');
		// }

		// var drEvent = $('#inputfotoplano').data('dropify');

		// if (drEvent) {
		// 	drEvent.destroy();
		// }

		// $('#inputfotoplano').attr('data-default-file', imagenUrl);

		// // Inicializar SIEMPRE
		// $('#inputfotoplano').dropify({
		// 	messages: {
		// 		'default': 'Arrastre la imagen aquí o haga click',
		// 		'replace': 'Arrastre la imagen o haga clic para reemplazar',
		// 		'remove': 'Quitar',
		// 		'error': 'Ooops, ha ocurrido un error.'
		// 	},
		// 	error: {
		// 		'fileSize': 'Demasiado grande ({{ value }} max).',
		// 		'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
		// 		'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
		// 		'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
		// 		'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
		// 		'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
		// 	}
		// });

		// $('#inputfotoplano').attr('required', false);

	//////////// OBTENER FOTO INSTALACION ////////////
	
		if (row.data().fotoinstalacion) {
			var archivo = row.data().fotoinstalacion;
			var extension = archivo.substring(archivo.lastIndexOf("."));
			var imagenUrl = '/mostrafotoinstalacionergo/0/' + row.data().id + extension;

			rutaMapa = imagenUrl

			// INPUT FOTO UBICACION
			if ($('#inputfotoinstalacion').data('dropify')) {
				$('#inputfotoinstalacion').dropify().data('dropify').destroy();
				// $('.dropify-wrapper').css('height', 400);
				$('#inputfotoinstalacion').dropify().data('dropify').settings.defaultFile = imagenUrl;
				$('#inputfotoinstalacion').dropify().data('dropify').init();
			}
			else {
				// $('#inputfotomapa').attr('data-height', 400);
				$('#inputfotoinstalacion').attr('data-default-file', imagenUrl);
				$('#inputfotoinstalacion').dropify({
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

			// No requerir campo FOTO
			$('#inputfotoinstalacion').attr('required', false);

			// Activar boton descarga
			$("#boton_descargarfotoinstalacion").css('display', 'block');
		}
		else {
			$("#boton_descargarfotoinstalacion").css('display', 'none');
	}

		// var imagenUrl = '';

		// if (row.data().fotoinstalacion) {
		// 	var archivo = row.data().fotoinstalacion;
		// 	var extension = archivo.substring(archivo.lastIndexOf("."));
		// 	imagenUrl = '/mostrafotoinstalacionergo/0/' + row.data().id + extension;

		// 	rutaMapa = imagenUrl;

		// 	$("#boton_descargarfotoinstalacion").css('display', 'block');
		// } else {
		// 	$("#boton_descargarfotoinstalacion").css('display', 'none');
		// }

		// var drEvent = $('#inputfotoinstalacion').data('dropify');

		// if (drEvent) {
		// 	drEvent.destroy();
		// }

		// $('#inputfotoinstalacion').attr('data-default-file', imagenUrl);

		// $('#inputfotoinstalacion').dropify({
		// 	messages: {
		// 		'default': 'Arrastre la imagen aquí o haga click',
		// 		'replace': 'Arrastre la imagen o haga clic para reemplazar',
		// 		'remove': 'Quitar',
		// 		'error': 'Ooops, ha ocurrido un error.'
		// 	},
		// 	error: {
		// 		'fileSize': 'Demasiado grande ({{ value }} max).',
		// 		'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
		// 		'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
		// 		'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
		// 		'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
		// 		'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
		// 	}
		// });

		// $('#inputfotoinstalacion').attr('required', false);

	//////////// OBTENER MAPA DE RIESGO ////////////
	
		if (row.data().fotomapariesgo) {
			var archivo = row.data().fotomapariesgo;
			var extension = archivo.substring(archivo.lastIndexOf("."));
			var imagenUrl = '/mostrarmapariesgoergo/0/' + row.data().id + extension;

			rutaMapa = imagenUrl

			if ($('#inputfotomapaderiesgo').data('dropify')) {
				$('#inputfotomapaderiesgo').dropify().data('dropify').destroy();
				// $('.dropify-wrapper').css('height', 400);
				$('#inputfotomapaderiesgo').dropify().data('dropify').settings.defaultFile = imagenUrl;
				$('#inputfotomapaderiesgo').dropify().data('dropify').init();
			}
			else {
				// $('#inputfotomapa').attr('data-height', 400);
				$('#inputfotomapaderiesgo').attr('data-default-file', imagenUrl);
				$('#inputfotomapaderiesgo').dropify({
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

			// No requerir campo FOTO
			$('#inputfotomapaderiesgo').attr('required', false);

			// Activar boton descarga
			$("#boton_descargarmapaderiesgo").css('display', 'block');
		}
		else {
			$("#boton_descargarmapaderiesgo").css('display', 'none');
	}



	// var imagenUrl = '';

	// 	if (row.data().fotomapariesgo) {
	// 		var archivo = row.data().fotomapariesgo;
	// 		var extension = archivo.substring(archivo.lastIndexOf("."));
	// 		imagenUrl = '/mostrarmapariesgoergo/0/' + row.data().id + extension;

	// 		rutaMapa = imagenUrl;

	// 		$("#boton_descargarmapaderiesgo").css('display', 'block');
	// 	} else {
	// 		$("#boton_descargarmapaderiesgo").css('display', 'none');
	// 	}

	// 	var drEvent = $('#inputfotomapaderiesgo').data('dropify');

	// 	if (drEvent) {
	// 		drEvent.destroy();
	// 	}

	// 	$('#inputfotomapaderiesgo').attr('data-default-file', imagenUrl);

	// 	$('#inputfotomapaderiesgo').dropify({
	// 		messages: {
	// 			'default': 'Arrastre la imagen aquí o haga click',
	// 			'replace': 'Arrastre la imagen o haga clic para reemplazar',
	// 			'remove': 'Quitar',
	// 			'error': 'Ooops, ha ocurrido un error.'
	// 		},
	// 		error: {
	// 			'fileSize': 'Demasiado grande ({{ value }} max).',
	// 			'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
	// 			'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
	// 			'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
	// 			'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
	// 			'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
	// 		}
	// 	});

	// 	// No requerir campo FOTO
	// $('#inputfotomapaderiesgo').attr('required', false);
	



	// Perfil
	recsensorial_perfil = parseInt(row.data().perfil);
	//responsables
	$("#NOMBRE_TECNICO").val(row.data().NOMBRE_TECNICO);
	$("#NOMBRE_CONTRATO").val(row.data().NOMBRE_CONTRATO);
	$("#CARGO_TECNICO").val(row.data().CARGO_TECNICO);
	$("#CARGO_CONTRATO").val(row.data().CARGO_CONTRATO);
	
	if (row.data().TECNICO_DOC){
		var archivo = row.data().TECNICO_DOC;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/mostrartecnicodoc/0/' + row.data().id + extension;
		
		rutaMapa = imagenUrl

		// INPUT FOTO TECNICO DOC
		if ($('#TECNICO_DOC_IMG').data('dropify'))
		{
			$('#TECNICO_DOC_IMG').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#TECNICO_DOC_IMG').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#TECNICO_DOC_IMG').dropify().data('dropify').init();
		}
		else
		{
			// $('#inputfotomapa').attr('data-height', 400);
			$('#TECNICO_DOC_IMG').attr('data-default-file', imagenUrl);
			$('#TECNICO_DOC_IMG').dropify({
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
		}

		// No requerir campo FOTO
		$('#TECNICO_DOC_IMG').attr('required', false);

		// Activar boton descarga
		$("#boton_descargarTECNICO_DOC").css('display', 'block');
	}
	else
	{
		$("#boton_descargarTECNICO_DOC").css('display', 'none');
	}

	// OBTENER FOTO PLANO
	if (row.data().CONTRATO_DOC) {
		var archivo = row.data().CONTRATO_DOC;
		var extension = archivo.substring(archivo.lastIndexOf("."));
		var imagenUrl = '/mostrarcontratodoc/0/' + row.data().id + extension;

		rutaMapa = imagenUrl


		// INPUT FOTO PLANO
		if ($('#CONTRATO_DOC_IMG').data('dropify')) {
			$('#CONTRATO_DOC_IMG').dropify().data('dropify').destroy();
			// $('.dropify-wrapper').css('height', 400);
			$('#CONTRATO_DOC_IMG').dropify().data('dropify').settings.defaultFile = imagenUrl;
			$('#CONTRATO_DOC_IMG').dropify().data('dropify').init();
		}
		else {
			// $('#inputfotoplano').attr('data-height', 400);
			$('#CONTRATO_DOC_IMG').attr('data-default-file', imagenUrl);
			$('#CONTRATO_DOC_IMG').dropify({
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

		// No requerir campo FOTO
		$('#CONTRATO_DOC_IMG').attr('required', false);

		// Activar boton descarga
		$("#boton_descargarCONTRATO_DOC").css('display', 'block');
	}
	else {
		$("#boton_descargarCONTRATO_DOC").css('display', 'none');
	}


	// Colocar nombre del reconocimieto
	$(".div_reconocimiento_instalacion").html(row.data().recsensorial_instalacion);

	// seleccionar TAB
	$("#steps_menu_tab1").click();
	$("#tab_menu2").click();
});



//-----------------------------------------------------FUNCIONES------------------------------------------------------------//

function activa_stepforms() {
	$("#steps_menu_tab2").css('display', 'block');
	$("#steps_menu_tab3").css('display', 'block');
	$("#steps_menu_tab4").css('display', 'block');
	$("#steps_menu_tab5").css('display', 'block');
}

function desactiva_stepforms() {
	$("#steps_menu_tab2").css('display', 'none');
	$("#steps_menu_tab3").css('display', 'none');
	$("#steps_menu_tab4").css('display', 'none');
	$("#steps_menu_tab5").css('display', 'none');

}

function menu_parametros_ocultar() {
	//Ocultar Menu agentes, Modulo evaluacion de Fisicos completo
	$('.link_menuparametro').each(function () {
		$("#" + this.id).removeClass('active');
		$("#" + this.id).css('display', 'none');
	});

	//Ocultar las opciones select, Modulo evaluacion de Puntos de muestreos por el CLIENTE
	$('.selectagente').each(function () {
		$("#" + this.id).css('display', 'none');
	});
}

function tabla_reconocimientosensorial() {
	try {
		var ruta = "/tablareconocimientoergo";

		if (tabla_recsensorial != null) {
			// Destruir la tabla existente antes de crear una nueva
			tabla_recsensorial.destroy();

		}

		tabla_recsensorial = $('#tabla_reconocimiento_sensorial').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"error": function (xhr, error, code) {
					console.log('error en tabla_recsensorial');
				},
				"data": {}
			},
			"columns": [
				{
					"data": "numero_registro",
					"defaultContent": "-"
				},
				{
					"data": "rfc",
					"defaultContent": "-"
				},
				{
					"data": "codigopostal",
					"defaultContent": "-"
				},
				{
					"data": "proyecto_folio",
					"defaultContent": "--"
				},
				{
					"data": "instalacion"
				},
				{
					"className": 'mostrar',
					"orderable": false,
					"data": 'boton_mostrar',
					"defaultContent": '-'
				}
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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

		tabla_recsensorial.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
	} catch (exception) {
		console.error("Error en tabla_reconocimientosensorial:", exception);
	}
}

function cargarFoliosProyecto(proyecto_folio) {

	$('#proyecto_folio').html('');

	$.ajax({
		type: "GET",
		dataType: "json",
		url: "/folioproyectoergp/" + proyecto_folio,
		data: {},
		cache: false,
		success: function (dato) {
			$('#proyecto_folio').html(dato.opciones);

		}, beforeSend: function () {

			$('#proyecto_folio').html('<option value="" selected> Consultando folios de proyectos... </option>');

		},
		error: function (dato) {
			// alert('Error: '+dato.msj);
			return false;
		}
	});//Fin ajax
}

function obtenerEstructuraProyectos(FOLIO, NUEVO) {

	$('#titleOrganizacionLabel').fadeOut();

	$.ajax({
		url: '/estructuraproyectosergo/' + FOLIO,
		method: 'GET',
		beforeSend: function () {
			$('#estructura-container').empty();
		},
		success: function (response) {

			//MOSTRAMOS LA ESTRUCTURA ORGANIZACIONAL DEL PROYECTO 
			if (response.data && response.data.length > 0) {
				var container = $('#estructura-container');

				$('#estructura-container').css({ 'background': '#e8e8e8', 'padding': '10px', 'border-style': 'dotted', 'border-color': '#000', 'border-width': '1px', 'border-radius': '15px', 'display': 'flex' });

				container.append(
					'<div class="col-12 text-center">' +
					'<h3 class="mb-2" style="font-weight: bold">Estructura organizacional</h3>' +
					'</div>'
				);

				var niveles = [1, 2, 3, 4, 5];
				niveles.forEach(function (nivel) {
					var data = response.data.filter(item => item.NIVEL == nivel);
					if (data.length > 0) {
						data.forEach(function (item) {
							container.append(
								'<div class="col-6"><label style="color: black;">Nivel ' + nivel + (nivel === 1 ? ' (Considerado como el más alto)' : '') + '</label></div>' +
								'<div class="col-6"><label style="color: black;">Clasificación</label></div>' +
								'<div class="col-6"><label>' + item.NOMBRE_ETIQUETA + '</label></div>' +
								'<div class="col-6"><label>' + item.NOMBRE_OPCIONES + '</label></div>'
							);
						});
					}
				});
			} else {
				$('#estructura-container').append('<p>No se encontraron datos.</p>');
			}

			
			if(response.HIGIENE[0].HI_RECONOCIMIENTO===1){
				HIGIENE = 1;
				$('#boton_nueva_categoria').prop('disabled', true);
				$('#boton_guardar_categoria').prop('disabled', true);
				$('#boton_nueva_area').prop('disabled', true);
				$('#boton_guardar_area').prop('disabled', true);

			}else{
				HIGIENE = 0;
			}

			console.log(HIGIENE);
			//MOSTRAMOS LOS DATOS DEL PROYECTO SOLO SI ES NUEVO SI YA ESTA CARGADO MOSTRAMOS LOS DATOS GUARDADOS EN LA BASE DE DATOS
			$('#higiene').val(HIGIENE)

			if (NUEVO == 1) {

				

				$('#empresa').val(response.info[0].RAZON_SOCIAL)
				$('#rfc').val(response.info[0].RFC)
				$('#representanteseguridad').val(response.info[0].REPRESENTANTE)
				$('#direccion').val(response.info[0].DIRRECCION)
				$('#instalacion').val(response.info[0].INSTALACION)

				// Input ocultos
				var contrato = response.info[0].CONTRATO_ID == 0 ? "" : response.info[0].CONTRATO_ID
				var requiereContrato = response.info[0].CONTRATO_ID == 0 ? 0 : 1

				$('#cliente_id').val(response.info[0].CLIENTE_ID)
				$('#contrato_id').val(contrato)
				$('#requiere_contrato').val(requiereContrato)
				$('#descripcion_contrato').val(response.info[0].NOMBRE_CONTRATO)
				$('#descripcion_cliente').val(response.info[0].RAZON_SOCIAL)

				//console.log(response.info[0].CLIENTE_ID,contrato,requiereContrato,response.info[0].NOMBRE_CONTRATO,response.info[0].RAZON_SOCIAL);


				//INFORMACION CONSULTADA DE RECSENSORIAL


			
				
				$('#ordenservicio').val(response.info[0].ORDENSERVICIO)
				$('#representantelegal').val(response.info[0].REPRESENTANTE_LEGAL)
				$('#codigopostal').val(response.info[0].CODIGOPOSTAL)
				$('#coordenadas').val(response.info[0].COORDENADAS)
				$('#actividadprincipal').val(response.info[0].ACTIVIDADPRINCIPAL)
				$('#descripcionproceso').val(response.info[0].DESCRIPCIONPROCESO)
				$('#observaciones').val(response.info[0].OBSERVACION)
				$('#fechainicio').val(response.info[0].FECHAINICIO)
				$('#fechafin').val(response.info[0].FECHAFIN)




				$('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
										<h4>${response.info[0].RAZON_SOCIAL}</h4>
										<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> ${response.info[0].TIPO_SERVICIO}</h2>
										<h4 class="mb-2">${response.info[0].NOMBRE_CONTRATO}</h4>`).css('border-style', 'dotted')

				
				//FOTOS OBTENIDAS DE RECSENSORIAL
				if (response.info[0].FOTOUBICACION != null) {
					var archivo = response.info[0].FOTOUBICACION;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarmapa/0/' + response.info[0].ID + extension;
		
					rutaMapa = imagenUrl
					$("#hidden_fotomapa").val(response.info[0].ID);
					$("#hidden_fotomapa_extension").val(extension);
		
					// INPUT FOTO UBICACION
					if ($('#inputfotomapa').data('dropify')) {
						$('#inputfotomapa').dropify().data('dropify').destroy();
						// $('.dropify-wrapper').css('height', 400);
						$('#inputfotomapa').dropify().data('dropify').settings.defaultFile = imagenUrl;
						$('#inputfotomapa').dropify().data('dropify').init();
					}
					else {
						// $('#inputfotomapa').attr('data-height', 400);
						$('#inputfotomapa').attr('data-default-file', imagenUrl);
						$('#inputfotomapa').dropify({
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
		
					// No requerir campo FOTO
					$('#inputfotomapa').attr('required', false);
		
					// Activar boton descarga
					$("#boton_descargarmapaubicacion").css('display', 'block');
				}
				else {
					$("#boton_descargarmapaubicacion").css('display', 'none');
				}

				if (response.info[0].FOTOPLANO != null) {
					var archivo = response.info[0].FOTOPLANO;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarplano/0/' + response.info[0].ID + extension;
			
					rutaMapa = imagenUrl;
			
					$("#hidden_fotoplano").val(response.info[0].ID);
					$("#hidden_fotoplano_extension").val(extension);
			
					// INPUT FOTO PLANO
					if ($('#inputfotoplano').data('dropify')) {
						$('#inputfotoplano').dropify().data('dropify').destroy();
						// $('.dropify-wrapper').css('height', 400);
						$('#inputfotoplano').dropify().data('dropify').settings.defaultFile = imagenUrl;
						$('#inputfotoplano').dropify().data('dropify').init();
					}
					else {
						// $('#inputfotoplano').attr('data-height', 400);
						$('#inputfotoplano').attr('data-default-file', imagenUrl);
						$('#inputfotoplano').dropify({
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
			
					// No requerir campo FOTO
					$('#inputfotoplano').attr('required', false);
			
					// Activar boton descarga
					$("#boton_descargarplanoinstalacion").css('display', 'block');
				}
				else {
					$("#boton_descargarplanoinstalacion").css('display', 'none');
				}

			
			
				// OBTENER FOTO INSTALACION
				if (response.info[0].FOTOINSTALACION) {
					var archivo = response.info[0].FOTOINSTALACION;
					var extension = archivo.substring(archivo.lastIndexOf("."));
					var imagenUrl = '/mostrarfotoinstalacion/0/' + response.info[0].ID + extension;
			
					
					$("#hidden_fotoinstalacion").val(response.info[0].ID);
					$("#hidden_fotoinstalacion_extension").val(extension);
					// INPUT FOTO INSTALACION
					$('#inputfotoinstalacion').dropify().data('dropify').destroy();
					// $('.dropify-wrapper').css('height', 400);
					$('#inputfotoinstalacion').dropify().data('dropify').settings.defaultFile = imagenUrl;
					$('#inputfotoinstalacion').dropify().data('dropify').init();
			
					// No requerir campo FOTO
					$('#inputfotoinstalacion').attr('required', false);
			
					// Activar boton descarga
					$("#boton_descargarfotoinstalacion").css('display', 'block');
			
				} else {
			
					// Resetear campo FOTO INSTALACION
					$('#inputfotoinstalacion').val('');
					$('#inputfotoinstalacion').dropify().data('dropify').resetPreview();
					$('#inputfotoinstalacion').dropify().data('dropify').clearElement();
			
					// No requerir campo FOTO
					$('#inputfotoinstalacion').attr('required', false);
					$("#boton_descargarfotoinstalacion").css('display', 'none');
				}
			
		


				swal({
					title: "Información del proyecto consultada y asignada correctamente",
					text: "Al momento de seleccionar un FOLIO se consulta la información cargada en proyecto y se asigna a reconocimiento",
					type: "success",
					buttons: { visible: true },
					showConfirmButton: true,
					showCancelButton: false,

				});
			} else {

				$('#infoCliente').html(`<h2 class="mt-2" style="font-weight:bold"><i class="fa fa-id-badge"></i> Cliente</h2>
									<h4>${response.info[0].RAZON_SOCIAL}</h4>
									<h2 class="mt-4" style="font-weight:bold"> <i class="fa fa-file-text"></i> ${response.info[0].TIPO_SERVICIO}</h2>
									<h4 class="mb-2">${response.info[0].NOMBRE_CONTRATO}</h4>`).css('border-style', 'dotted')

			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error('Error al consultar los datos:', textStatus, errorThrown);
			$('#estructura-container').append('<p>Error al consultar los datos.</p>');
		}
	});
}

////// CATEGORIA /////////////////


$("#boton_nueva_categoria").click(function (e) {
    e.preventDefault();

    ID_CATEGORIA_ERGO = 0;

    $('#form_categoria').each(function(){
        this.reset();
    });

    $(".listadodeturno").empty(); 

    $("#modal_categoria").modal("show");

    $('#modal_categoria .modal-title').html('Nueva categoría');

	$.ajax({
        url: 'obtenerPTCategoria',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            reco_id: recsensorial
        },
        success: function (resp) {
            $('#PT_CATEGORIA').val(resp.pt);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });


	cargarareaSelect();

	$('#CAT_TIPOPUESTO').val('').trigger('change');
  	selectAreas.clear();


});

let selectAreas;

$(document).ready(function () {

    selectAreas = $('#CATEGORIA_AREAS_ID').selectize({
        placeholder: 'Seleccione una o varias opciones',
        maxItems: null,
    })[0].selectize;

});

$('#CAT_TIPOPUESTO').on('change', function () {

    let valor = $(this).val();

    if (valor == 1) {

        selectAreas.settings.maxItems = 1;

        let valores = selectAreas.getValue();

        if (!Array.isArray(valores)) {
            valores = valores ? [valores] : [];
        }

        if (valores.length > 1) {
            selectAreas.setValue([valores[0]]);
        }

    } else if (valor == 2) {

        selectAreas.settings.maxItems = null;
    }

});


document.addEventListener("DOMContentLoaded", function () {
	const botonAgregar = document.getElementById('botonagregarhorario');
	botonAgregar.addEventListener('click', agregarhorario);

	function agregarhorario() {

		var errorEnTurnos = false; // Variable para controlar si hay error en los turnos

		$(".generarturnos").each(function () {
			var horasJornada = parseInt($(this).find("input[name='recsensorialcategoria_horasjornada']").val()) || 0;
			var horasComida = parseInt($(this).find("input[name='recsensorialcategoria_horascomida']").val()) || 0;

			var horaEntrada = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horarioentrada']").val() + ":00");
			var horaSalida = new Date("2000-01-01T" + $(this).find("input[name='recsensorialcategoria_horariosalida']").val() + ":00");

			var horasTrabajadas = (horaSalida - horaEntrada) / 1000 / 60 / 60;

			if (horasJornada + horasComida !== horasTrabajadas) {
				errorEnTurnos = true;
			}
		});

		if (errorEnTurnos) {
			swal({
				title: "No disponible",
				text: "Su horario de entrada y salida no coincide con las horas asignadas.",
				type: "warning", // Puedes cambiar el tipo según tus preferencias
				buttons: {
					confirm: {
						text: "OK",
						value: true,
						visible: true,
						className: "btn btn-danger" // Puedes ajustar las clases según tu estilo
					}
				}
			});
			return; // Detiene la ejecución del guardado si hay un error en los turnos
		}
		else {

			const divhorarios = document.createElement('div');
			divhorarios.classList.add('row', 'generarturnos', 'm-2');
			divhorarios.innerHTML = `
                <div class="form-group">
                    <h3><i class="fa  fa-clock-o"></i> Agregar turno</h3>                 
                </div>

                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas jornada *</label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horasjornada"  required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario entrada *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horarioentrada"  required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario salida *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horariosalida" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas de comida </label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horascomida"  value=0>
                        </div>
                    </div>

					<div class="col-12">
					  <div class="form-group">
						<input type="text" class="form-control" name="descripcioncategoria" placeholder="Nota del turno..." >
					    </div>
				    </div>
                </div>

                <div class="col">
                    <div class="form-group" style="text-align: center;">
                        <button type="button" class="btn btn-danger botonEliminarhorarioturno">Eliminar turno <i class="fa fa-trash"></i></button>
                    </div>
                </div>
        
        `;
			const contenedor = document.querySelector('.listadodeturno');
			contenedor.appendChild(divhorarios);

			const botonEliminar = divhorarios.querySelector('.botonEliminarhorarioturno');
			botonEliminar.addEventListener('click', function () {
				contenedor.removeChild(divhorarios);
			});
		}

	}
});

$("#boton_guardar_categoria").click(function (e) {
    e.preventDefault();


    formularioValido = validarFormulario3($('#form_categoria'))

    if (formularioValido) {


		var turnosjson = [];
        $(".generarturnos ").each(function() {
			var inputurnos = {
                'recsensorialcategoria_horasjornada': $(this).find("input[name='recsensorialcategoria_horasjornada']").val(),
				'recsensorialcategoria_horarioentrada': $(this).find("input[name='recsensorialcategoria_horarioentrada']").val(),
                'recsensorialcategoria_horariosalida': $(this).find("input[name='recsensorialcategoria_horariosalida']").val(),
                'recsensorialcategoria_horascomida': $(this).find("input[name='recsensorialcategoria_horascomida']").val(),
                'descripcioncategoria': $(this).find("input[name='descripcioncategoria']").val(),
            };
            turnosjson.push(inputurnos);
        });

           
         const requestData = {
            api: 1,
            ID_CATEGORIA_ERGO: ID_CATEGORIA_ERGO,
			JSON_TURNOS: JSON.stringify(turnosjson),
			RECO_ID: recsensorial, 
		};
		


    if (ID_CATEGORIA_ERGO == 0) {
        
        alertMensajeConfirm({
            title: "¿Desea guardar la información?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_categoria')
            await ajaxAwaitFormData(requestData, 'recoergocategorias', 'form_categoria', 'boton_guardar_categoria', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                    ID_CATEGORIA_ERGO = data.categorias.ID_CATEGORIA_ERGO
                    alertMensaje('success','Información guardada correctamente', 'Esta información esta lista para usarse',null,null, 1500)
                     $('#modal_categoria').modal('hide')
                    document.getElementById('form_categoria').reset();
                    Tablarecocategoriasergo.ajax.reload()
                
            })
            
            
            
        }, 1)
        
    } else {
            alertMensajeConfirm({
            title: "¿Desea editar la información de este formulario?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_categoria')
            await ajaxAwaitFormData(requestData, 'recoergocategorias', 'form_categoria', 'boton_guardar_categoria', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                setTimeout(() => {

                    ID_CATEGORIA_ERGO = data.categorias.ID_CATEGORIA_ERGO
                    alertMensaje('success', 'Información editada correctamente', 'Información guardada')
                     $('#modal_categoria').modal('hide')
                    document.getElementById('form_categoria').reset();
                    Tablarecocategoriasergo.ajax.reload()

                }, 300);  
            })
        }, 1)
    }

} else {
    alertToast('Por favor, complete todos los campos del formulario.', 'error', 2000)

}
    
});

function mostrartablarecocategoriasergo() {
	try {
		var ruta = "/Tablarecocategoriasergo";

		if (Tablarecocategoriasergo != null) {
			Tablarecocategoriasergo.destroy();
		}

		Tablarecocategoriasergo = $('#Tablarecocategoriasergo').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"data": {
					ergoid: recsensorial 
				},
				"error": function (xhr, error, code) {
					console.log('error en Tablarecocategoriasergo');
				}
			},
			"columns": [
				{
					"data": "PT_CATEGORIA",
					"defaultContent": "-"
				},
				{
					"data": "NOMBRE_CATEGORIA_ERGO",
					"defaultContent": "-"
				},
				{
					"data": "DESCRIPCION_CATEGORIA_ERGO",
					"defaultContent": "-"
				},
				{ 
					data: 'BTN_EDITAR',
					orderable: false,
					searchable: false
				},
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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

		Tablarecocategoriasergo.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

	} catch (exception) {
		console.error("Error en Tablarecocategoriasergo:", exception);
	}
}

$('#Tablarecocategoriasergo tbody').on('click', 'td>button.editar', function () {
    var tr = $(this).closest('tr');
    var row = Tablarecocategoriasergo.row(tr);


    ID_CATEGORIA_ERGO = row.data().ID_CATEGORIA_ERGO;

    editarDatoTabla(row.data(), 'form_categoria', 'modal_categoria',1);


    $('#modal_categoria .modal-title').html(row.data().NOMBRE_CATEGORIA_ERGO);


	$(".listadodeturno").empty();
	mostrarturnos(row);

 	let valoresAreas = row.data().CATEGORIA_AREAS_ID;

    if (typeof valoresAreas === "string") {
        try { valoresAreas = JSON.parse(valoresAreas); } 
        catch (e) { valoresAreas = []; }
    }

    cargarareaSelect(valoresAreas);

});

function mostrarturnos(row) {

    const contenedor = document.querySelector('.listadodeturno');
    contenedor.innerHTML = "";

	let data = row.data().JSON_TURNOS
		;

    if (!data) return;

    try {
        data = JSON.parse(data);
    } catch (e) {
        data = [];
    }

    data.forEach((item) => {

        const fila = document.createElement('div');
        fila.classList.add('row', 'generarturnos', 'm-2');

        const  horasjornada= item.recsensorialcategoria_horasjornada ?? "";
        const horarioentrada = item.recsensorialcategoria_horarioentrada ?? "";
        const horariosalida = item.recsensorialcategoria_horariosalida ?? "";
        const horascomida = item.recsensorialcategoria_horascomida ?? "";
        const descripcionjornada = item.descripcioncategoria ?? "";


        fila.innerHTML = `
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas jornada *</label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horasjornada" value="${horasjornada}" required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario entrada *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horarioentrada"  value="${horarioentrada}"   required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horario salida *</label>
                            <div class="input-group clockpicker">
                                <input type="time" class="form-control" name="recsensorialcategoria_horariosalida"  value="${horariosalida}"  required>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label> Horas de comida </label>
                            <input type="number" class="form-control" name="recsensorialcategoria_horascomida"  value="${horascomida}"   required>
                        </div>
                    </div>

					<div class="col-12">
					  <div class="form-group">
						<input type="text" class="form-control" name="descripcioncategoria" placeholder="Nota del turno..."  value="${descripcionjornada}"  >
					    </div>
				    </div>
                </div>

                <div class="col">
                    <div class="form-group" style="text-align: center;">
                        <button type="button" class="btn btn-danger botonEliminarhorarioturno">Eliminar turno <i class="fa fa-trash"></i></button>
                    </div>
                </div>
           
        `;

        contenedor.appendChild(fila);

        // 🗑 Eliminar fila
        fila.querySelector('.botonEliminarhorarioturno')
            .addEventListener('click', function () {
                fila.remove();
            });
    });
}

function cargarareaSelect(valoresSeleccionados = []) {

    let reco_id = $("#recsensorial_id").val(); 
    let selectize = $('#CATEGORIA_AREAS_ID')[0].selectize;

    selectize.clearOptions();
    selectize.clear();

    $.ajax({
        url: '/obtenerareasergo',
        type: 'GET',
        data: { reco_id: reco_id },
        success: function (response) {

            response.data.forEach(function (item) {
                selectize.addOption({
                    value: item.ID_AREA_ERGO,
                    text: item.NOMBRE_AREA_ERGO
                });
            });

            let tipo = $('#CAT_TIPOPUESTO').val();

            if (!Array.isArray(valoresSeleccionados)) {
                valoresSeleccionados = valoresSeleccionados ? [valoresSeleccionados] : [];
            }

            if (tipo == 1 && valoresSeleccionados.length > 1) {
                valoresSeleccionados = [valoresSeleccionados[0]];
            }

            selectize.setValue(valoresSeleccionados);

            selectize.refreshOptions(false);
        }
    });
}


////// AREAS  /////////////////


$("#boton_nueva_area").click(function (e) {
    e.preventDefault();

    ID_AREA_ERGO = 0;
       
    $('#form_area').each(function(){
        this.reset();
    });

    $("#modal_area").modal("show");

    $('#modal_area .modal-title').html('Nueva área');
});

$("#boton_guardar_area").click(function (e) {
    e.preventDefault();


    formularioValido = validarFormulario3($('#form_area'))

    if (formularioValido) {

    if (ID_AREA_ERGO == 0) {
        
        alertMensajeConfirm({
            title: "¿Desea guardar la información?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_area')
            await ajaxAwaitFormData({ api: 1,RECO_ID: recsensorial, ID_AREA_ERGO: ID_AREA_ERGO }, 'recoergoareas', 'form_area', 'boton_guardar_area', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                    ID_AREA_ERGO = data.areas.ID_AREA_ERGO
                    alertMensaje('success','Información guardada correctamente', 'Esta información esta lista para usarse',null,null, 1500)
                     $('#modal_area').modal('hide')
                    document.getElementById('form_area').reset();
                    Tablarecoareasergo.ajax.reload()
                
            })
            
            
            
        }, 1)
        
    } else {
            alertMensajeConfirm({
            title: "¿Desea editar la información de este formulario?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_area')
            await ajaxAwaitFormData({ api: 1, RECO_ID: recsensorial, ID_AREA_ERGO: ID_AREA_ERGO }, 'recoergoareas', 'form_area', 'boton_guardar_area', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                setTimeout(() => {

                    ID_AREA_ERGO = data.areas.ID_AREA_ERGO
                    alertMensaje('success', 'Información editada correctamente', 'Información guardada')
                     $('#modal_area').modal('hide')
                    document.getElementById('form_area').reset();
                    Tablarecoareasergo.ajax.reload()

                }, 300);  
            })
        }, 1)
    }

} else {
    alertToast('Por favor, complete todos los campos del formulario.', 'error', 2000)

}
    
});

function mostrartablarecoareasergo() {
	try {
		var ruta = "/Tablarecoareasergo";

		if (Tablarecoareasergo != null) {
			Tablarecoareasergo.destroy();
		}

		Tablarecoareasergo = $('#Tablarecoareasergo').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"data": {
					ergoid: recsensorial 
				},
				"error": function (xhr, error, code) {
					console.log('error en Tablarecoareasergo');
				}
			},
			"columns": [
				{
					data: null,
					render: function (data, type, row, meta) {
						return meta.row + 1;
					}
				},
				{
					"data": "NOMBRE_AREA_ERGO",
					"defaultContent": "-"
				},
				{
					"data": "DESCRIPCION_AREA_ERGO",
					"defaultContent": "-"
				},
				{ 
					data: 'BTN_EDITAR',
					orderable: false,
					searchable: false
				},
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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

		Tablarecoareasergo.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

	} catch (exception) {
		console.error("Error en Tablarecoareasergo:", exception);
	}
}

$('#Tablarecoareasergo tbody').on('click', 'td>button.editar', function () {
    var tr = $(this).closest('tr');
    var row = Tablarecoareasergo.row(tr);

    ID_AREA_ERGO = row.data().ID_AREA_ERGO;

    editarDatoTabla(row.data(), 'form_area', 'modal_area',1);

    $('#modal_area .modal-title').html(row.data().NOMBRE_AREA_ERGO);

});


////// FICHAS TECNICAS

$("#boton_nueva_ficha").click(function (e) {
    e.preventDefault();

    ID_FICHAS_TECNICAS = 0;

    $('#form_fichas')[0].reset();

    cargarCategoriasSelect();

    $('[id^="ficha_"]').empty();

    $("#contenedorActividades").empty(); 
    $('#contenido2, #contenido3, #contenido4, #contenido5, #contenido6, #contenido7').hide();

   $('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION')
    .attr('style', 'display:block !important;');

    $("#modal_fichas").modal("show");

	$('#modal_fichas .modal-title').html('Nueva ficha');
	
    $('#JSON_ACTIVIDADES').val('')


	selectAreasFichas.clear();
});

$('#modal_fichas').on('hidden.bs.modal', function (e) {

     ID_FICHAS_TECNICAS = 0;

    $('#form_fichas')[0].reset();

    cargarCategoriasSelect();

    $('[id^="ficha_"]').empty();

    $("#contenedorActividades").empty(); 
    $('#contenido2, #contenido3, #contenido4, #contenido5, #contenido6, #contenido7').hide();

   $('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION')
    .attr('style', 'display:block !important;');

    $('#JSON_ACTIVIDADES').val('')


	selectAreasFichas.clear();
   

})

let selectAreasFichas;

$(document).ready(function () {

    selectAreasFichas = $('#CAT_AREAS_FICHA').selectize({
        placeholder: 'Seleccione una o varias opciones',
        maxItems: null,
    })[0].selectize;

});

function calcularEdad(fechaNacimiento) {
    if (!fechaNacimiento) return '';

    const partes = fechaNacimiento.split('-'); 

    if (partes.length !== 3) return '';

    const nacimiento = new Date(partes[0], partes[1] - 1, partes[2]);
    const hoy = new Date();

    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mesDiferencia = hoy.getMonth() - nacimiento.getMonth();

    if (mesDiferencia < 0 || (mesDiferencia === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    return edad >= 0 ? edad : '';
}

$('#FECHA_NACIMIENTO').on('change blur', function () {
    const fecha = $(this).val();
    const edad = calcularEdad(fecha);

    $('#EDAD_EMPLEADO_FICHA').val(edad);
});


function cargarCategoriasSelect(callback = null) {

    let reco_id = $("#recsensorial_id").val(); 

    $.ajax({
        url: '/getCategoriasErgo',
        type: 'GET',
        data: { reco_id: reco_id },
        success: function (response) {

            let select = $('#CATEGORIA_ID_FICHA');
            select.empty();

            select.append('<option value="">Selecciona un tipo de valor</option>');

            response.data.forEach(function (item) {

                let areas = JSON.stringify(item.CATEGORIA_AREAS_ID || []);

                select.append(`
                    <option 
                        value="${item.ID_CATEGORIA_ERGO}"
                        data-departamento="${item.CAT_DEPARTAMENTO}"
                        data-areas='${areas}'
                    >
                        ${item.NOMBRE_CATEGORIA_ERGO}
                    </option>
                `);
            });
            if (callback) callback();
        }
    });
}

$(document).on('change', '#CATEGORIA_ID_FICHA', function () {

    let selected = $(this).find(':selected');

    let departamento = selected.data('departamento');
    let areas = selected.data('areas');

    if (departamento) {
        $('#CAT_DEPARTAMENTO_FICHA').val(departamento).trigger('change');
    } else {
        $('#CAT_DEPARTAMENTO_FICHA').val('');
    }

    if (typeof areas === "string") {
        try { areas = JSON.parse(areas); } 
        catch (e) { areas = []; }
    }

    if (!Array.isArray(areas)) {
        areas = [];
    }

    cargarAreasFicha(areas);
});


function cargarAreasFicha(valoresSeleccionados = []) {

    let reco_id = $("#recsensorial_id").val(); 

    selectAreasFichas.clearOptions();
    selectAreasFichas.clear();

    $.ajax({
        url: '/obtenerareasergo',
        type: 'GET',
        data: { reco_id: reco_id },
        success: function (response) {

            response.data.forEach(function (item) {
                selectAreasFichas.addOption({
                    value: item.ID_AREA_ERGO,
                    text: item.NOMBRE_AREA_ERGO
                });
            });

            selectAreasFichas.setValue(valoresSeleccionados);

            selectAreasFichas.refreshOptions(false);
        },
        error: function () {
            console.log('Error cargando áreas');
        }
    });
}

function agregarActividad() {

    let contenedor = document.getElementById("contenedorActividades");
    let total = contenedor.querySelectorAll('.actividad-item').length + 1;

    let html = `
    <div class="actividad-item" id="actividad_${total}">
        
        <div class="actividad-card">

            <div class="d-flex justify-content-between mb-2">
                <strong>Actividad ${total}</strong>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <div class="actividad-row">

                <div class="actividad-left">
                    <input type="text" 
                        class="form-control actividad-nombre" 
                        placeholder="Nombre de la actividad">
                </div>

                <div class="actividad-right">

                    <button type="button" 
                        class="btn btn-agregar-tarea"
                        onclick="agregarTarea(${total})">
                        + Agregar tarea
                    </button>

                    <div id="tareas_${total}"></div>

                </div>

            </div>

        </div>

    </div>
    `;

    contenedor.insertAdjacentHTML('beforeend', html);
}

function eliminarActividad(btn) {

    btn.closest('.actividad-item').remove();

    reindexarActividades();
}

function reindexarActividades() {

    let actividades = document.querySelectorAll('#contenedorActividades .actividad-item');

    actividades.forEach((el, index) => {

        let num = index + 1;

        el.id = `actividad_${num}`;

        el.querySelector('strong').innerText = `Actividad ${num}`;

        let input = el.querySelector('.actividad-left input');
        input.name = `actividades[${num}][nombre]`;

        let btnTarea = el.querySelector('.btn-agregar-tarea');
        btnTarea.setAttribute('onclick', `agregarTarea(${num})`);

     
        let tareasDiv = el.querySelector('[id^="tareas_"]');
        tareasDiv.id = `tareas_${num}`;

       
        reindexarTareasActividad(tareasDiv, num);
    });
}

function agregarTarea(idActividad) {

    let contenedor = document.getElementById(`tareas_${idActividad}`);
    let total = contenedor.querySelectorAll('.tarea-item').length + 1;

    let html = `
    <div class="tarea-item">

        <div class="d-flex justify-content-between">
            <small>Tarea ${total}</small>
            <button type="button" class="btn btn-danger btn-sm"
                onclick="eliminarTarea(this)">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        <input type="text" 
            class="form-control mt-1 tarea-nombre" 
            placeholder="Nombre">

        <input type="text" 
            class="form-control mt-1 tarea-frecuencia" 
            placeholder="Frecuencia (No de veces durante la jornada)">

        <input type="text" 
            class="form-control mt-1 tarea-duracion" 
            placeholder="Duración (tiempo en Min)">

    </div>
    `;

    contenedor.insertAdjacentHTML('beforeend', html);
}

function eliminarTarea(btn) {

    let tareaItem = btn.closest('.tarea-item');
    let contenedor = tareaItem.parentElement;

    tareaItem.remove();

    let idActividad = contenedor.id.split('_')[1];

    reindexarTareasActividad(contenedor, idActividad);
}

function reindexarTareasActividad(contenedor, idActividad) {

    let tareas = contenedor.querySelectorAll('.tarea-item');

    tareas.forEach((el, index) => {

        let num = index + 1;

     
        let titulo = el.querySelector('small');
        if (titulo) titulo.innerText = `Tarea ${num}`;

        let inputs = el.querySelectorAll('input');

        inputs[0].name = `actividades[${idActividad}][tareas][${num}][nombre]`;
        inputs[1].name = `actividades[${idActividad}][tareas][${num}][frecuencia]`;
        inputs[2].name = `actividades[${idActividad}][tareas][${num}][duracion]`;
    });
}

function toggleSeccion(id) {

    let cont = $('#' + id);

    cont.slideToggle();

    if (cont.is(':visible')) {

        if (id === "contenido2") {
            $('#ficha_1_1, #ficha_1_3').empty();
            fichas["1.1"].render();
            fichas["1.3"].render();
        }

        if (id === "contenido3") {
            $('#ficha_1_2').empty();
            fichas["1.2"].render();
        }

        if (id === "contenido4") {
            $('#ficha_2_1, #ficha_2_2').empty();
            fichas["2.1"].render();
            fichas["2.2"].render();
        }

        if (id === "contenido5") {
            $('#ficha_3_1').empty();
            fichas["3.1"].render();
        }

        if (id === "contenido6") {
            $('#ficha_4_1').empty();
            fichas["4.1"].render();
        }

        if (id === "contenido7") {
            $('#ficha_4_2').empty();
            fichas["4.2"].render();
        }
    }
}


const fichas = {

"1.1": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde">
<b>FICHA 1.1.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por LEVANTAMIENTO DE CARGAS.</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr>
<td width="40"  class="texto-pregunta">a.</td>
<td class="texto-pregunta">¿Todas las cargas levantadas pesan 10 kg o menos?</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a" id="a_no" value="NO">
<label class="form-check-label" for="a_no">NO</label>
</div>
</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a" id="a_si" value="SI">
<label class="form-check-label" for="a_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">b.</td>
<td class="texto-pregunta">
¿El peso máximo de la carga está entre 3 kg y 5 kg y la frecuencia de levantamientos no excede de 5 levantamientos/minuto?<br><br>
O bien,<br><br>
¿El peso máximo de la carga es de más de 5 kg e inferior a los 10 kg y la frecuencia no excede de 1 levantamiento/minuto?
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b" id="b_no" value="NO">
<label class="form-check-label" for="b_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b" id="b_si" value="SI">
<label class="form-check-label" for="b_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">c.</td>
<td class="texto-pregunta">¿El desplazamiento vertical se realiza entre la cadera y los hombros?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c" id="c_no" value="NO">
<label class="form-check-label" for="c_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c" id="c_si" value="SI">
<label class="form-check-label" for="c_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">d.</td>
<td class="texto-pregunta">¿El tronco está erguido, sin flexión ni rotación?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="d" id="d_no" value="NO">
<label class="form-check-label" for="d_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="d" id="d_si" value="SI">
<label class="form-check-label" for="d_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">e.</td>
<td class="texto-pregunta">¿La carga se mantiene muy cerca del cuerpo (no más de 10 cm de la parte frontal del torso)?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="e" id="e_no" value="NO">
<label class="form-check-label" for="e_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="e" id="e_si" value="SI">
<label class="form-check-label" for="e_si">SI</label>
</div>
</td>
</tr>

</table>

<div class="p-3">
Si a todas las preguntas ha contestado “SI” entonces la tarea tiene un riesgo aceptable y está en la Zona Verde.
Si alguna de las respuestas es “NO”, compruebe si se trata de una tarea con un nivel de riesgo inaceptable según la ficha 1.4.
</div>

</div>
</div>
`;

        $('#ficha_1_1').html(html);
        
$('input[name="a"], input[name="b"], input[name="c"], input[name="d"], input[name="e"]')
.change(evaluarFicha_1_1);

}

    },
    
    "1.2": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde">
<b>FICHA 1.2.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por TRANSPORTE DE CARGAS.</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr>
<td width="40" class="texto-pregunta">a.</td>
<td class="texto-pregunta">
Si se requiere que una carga sea transportada manualmente a una distancia inferior o igual a 10 m, responda:<br><br>

¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 10.000 kg en 8 horas?<br>
Y<br>
¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 1.500 kg en 1 hora?<br>
Y<br>
¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 30 kg en 1 minuto?
</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a2" id="a2_no" value="NO">
<label class="form-check-label" for="a2_no">NO</label>
</div>
</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a2" id="a2_si" value="SI">
<label class="form-check-label" for="a2_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">b.</td>
<td class="texto-pregunta">
Si se requiere que una carga sea transportada manualmente a una distancia superior a 10 m, responda:<br><br>

¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 6.000 kg en 8 horas?<br>
Y<br>
¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 750 kg en 1 hora?<br>
Y<br>
¿La masa acumulada transportada manualmente (peso total de todas las cargas) es menor de 15 kg en 1 minuto?
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b2" id="b2_no" value="NO">
<label class="form-check-label" for="b2_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b2" id="b2_si" value="SI">
<label class="form-check-label" for="b2_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">c.</td>
<td class="texto-pregunta">¿El transporte de la carga se realiza sin posturas forzadas?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c2" id="c2_no" value="NO">
<label class="form-check-label" for="c2_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c2" id="c2_si" value="SI">
<label class="form-check-label" for="c2_si">SI</label>
</div>
</td>
</tr>

</table>

<div class="p-3">
Si a las preguntas “a” o “b”, y a la pregunta “c” ha contestado “SI” entonces la tarea tiene un riesgo aceptable y está en la Zona Verde.
<br><br>
Si alguna de las respuestas es “NO”, compruebe si se trata de una tarea con un nivel de riesgo inaceptable según la ficha 1.5.
</div>

</div>
</div>
`;

            $('#ficha_1_2').html(html);
            
            $('input[name="a2"], input[name="b2"], input[name="c2"]')
                
.change(evaluarFicha_1_2);
}

    },
    
"1.3": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-azul text-center">
<b>FICHA 1.3.- Aspectos adicionales a considerar</b><br>
A cada una de las preguntas de cada apartado marque una "X" en la columna SI o NO
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Condiciones ambientales de trabajo para el levantamiento o transporte manual</b></td>
<td width="80"><b></b></td>
<td width="80"><b></b></td>
</tr>

${fila13("f1","¿Hay presencia de baja o altas temperaturas?")}
${fila13("f2","¿Hay presencia de suelo resbaladizo, desigual o inestable?")}
${fila13("f3","¿Está restringida la libre circulación en el puesto de trabajo?")}

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Características de los objetos levantados o transportados</b></td>
<td><b></b></td>
<td><b></b></td>
</tr>

${fila13("f4","¿El tamaño del objeto obstaculiza la visibilidad y el movimiento?")}
${fila13("f5","¿El centro de gravedad de la carga es inestable? P.ej. líquidos o cosas que se mueven dentro del objeto.")}
${fila13("f6","¿La forma de la carga y su configuración presenta bordes afilados, superficies sobresalientes o protuberancias?")}
${fila13("f7","¿El contacto con la superficie es frío?")}
${fila13("f8","¿El contacto con la superficie es caliente?")}
${fila13("f9","¿La tarea de levantamiento o transporte manual de cargas se realiza por más de 8 horas al día?")}

</table>

<div id="resultadoFicha13" class="p-3"></div>

</div>
</div>
`;

            $('#ficha_1_3').html(html);


$('input[name^="f"]').change(evaluarFicha_1_3);

},

    },


    "1.4": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-rojo text-center">
<b>FICHA 1.4. Evaluación Rápida para identificar la presencia de condiciones inaceptables (Zona roja) por LEVANTAMIENTO DE CARGAS</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

${fila14("r1","a.","¿La distancia vertical es superior a 175 cm o está por debajo del nivel del suelo?")}
${fila14("r2","b.","¿El desplazamiento vertical es superior a 175 cm?")}
${fila14("r3","c.","¿La distancia horizontal es superior a 63 cm fuera del alcance máximo (brazo completamente estirado hacia delante)?")}
${fila14("r4","d.","¿El ángulo de asimetría es superior a 135°?")}
${fila14("r5","e.","¿Se realizan más de 15 levantamientos/min en duración corta? (La tarea  de manipulación manual no dura más de 60 min consecutivos y viene seguida de tareas ligeras para la espalda de duración miníma de 60 min)")}
${fila14("r6","f.","¿Se realizan más de 12 levantamientos/min en duración media? (La tarea de manipulación manual no dura más de 120 min consecutivos  y viene seguida de tareas ligeras para la espalda de duración miníma de 30 min )")}
${fila14("r7","g.","¿Se realizan más de 8 levantamientos/min en duración larga? (La tarea de manipulación manual que no es de duración corta ni media )")}
${fila14("r8","h.","¿La tarea se puede realizar por mujeres (entre 18 y 45 años) y la carga pesa más de 20 kg?")}
${fila14("r9","i.","¿La tarea se puede realizar por mujeres (menores de 18 y mayores de 45 años) y  la carga pesa más de 15 kg?")}
${fila14("r10","j.","¿La tarea  la realizan únicamente hombres (entre 18 y 45 años) y la carga pesa más de 25 kg?")}
${fila14("r11","k.","¿La tarea la realizan únicamente hombre (menores de 18 y mayores de 45 años) y la carga pesa más de 20 kg?")}

</table>

<div id="resultadoFicha14" class="p-3"></div>

</div>
</div>
`;

            $('#ficha_1_4').html(html);

$('input[name^="r"]').change(evaluarFicha_1_4);

}

    },

    "1.5": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-rojo text-center">
<b>FICHA 1.5. Evaluación Rápida para identificar la presencia de condiciones inaceptables (Zona roja) por TRANSPORTE DE CARGAS</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

${fila15("t1","a.","¿Se manipula una masa acumulada (peso total de todas las cargas) de más de 10.000 kg en 8 horas, en una distancia menor a 20 metros?")}

${fila15("t2","b.","¿Se manipula una masa acumulada (peso total de todas las cargas) de más de 6.000 kg en 8 horas, en una distancia igual o superior a 20 metros?")}

</table>

<div id="resultadoFicha15" class="p-3"></div>

</div>
</div>
`;

            $('#ficha_1_5').html(html);

$('input[name^="t"]').change(evaluarFicha_1_5);

}

    },
    
  
    "2.1": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde">
<b>FICHA 2.1.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por EMPUJE Y TRACCIÓN DE CARGAS.</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr>
<td width="40" class="texto-pregunta">a.</td>
<td class="texto-pregunta">
¿La fuerza requerida en el empuje o tracción es inferior a “Moderada” (en la Escala de Borg menor a 3)?<br>
<br>O<br><br>
¿La fuerza requerida en el empuje o tracción no supera los 30 N en fuerza continua (sostenida) y no supera los 100 N en los picos de fuerza?<br>
<br>O<br><br>
¿La fuerza requerida en el empuje o tracción no supera los 50 N cuando la frecuencia es menor 1 acción cada 5 minutos en una distancia de recorrido inferior a 50 m?
</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a21" id="a21_no" value="NO">
<label class="form-check-label" for="a21_no">NO</label>
</div>
</td>

<td width="100">
<div class="form-check">
<input class="form-check-input" type="radio" name="a21" id="a21_si" value="SI">
<label class="form-check-label" for="a21_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">b.</td>
<td class="texto-pregunta">¿La fuerza de empuje o tracción se aplica a una altura de agarre entre la cadera y la mitad del pecho?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b21" id="b21_no" value="NO">
<label class="form-check-label" for="b21_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="b21" id="b21_si" value="SI">
<label class="form-check-label" for="b21_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">c.</td>
<td class="texto-pregunta">¿La acción de empuje o tracción se realiza con el tronco erguido (sin torsión ni flexión)?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c21" id="c21_no" value="NO">
<label class="form-check-label" for="c21_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="c21" id="c21_si" value="SI">
<label class="form-check-label" for="c21_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">d.</td>
<td class="texto-pregunta">¿La tarea de empuje o tracción se realiza durante menos de 8 horas al día?</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="d21" id="d21_no" value="NO">
<label class="form-check-label" for="d21_no">NO</label>
</div>
</td>

<td>
<div class="form-check">
<input class="form-check-input" type="radio" name="d21" id="d21_si" value="SI">
<label class="form-check-label" for="d21_si">SI</label>
</div>
</td>
</tr>

</table>

<div class="p-3">
Si a todas las preguntas ha contestado “SI” entonces la tarea tiene un riesgo aceptable y está en la Zona Verde.
Si alguna de las respuestas es “NO”, compruebe si se trata de una tarea con un nivel de riesgo inaceptable según la ficha 2.3.
</div>

</div>
</div>
`;

$('#ficha_2_1').html(html);

$('input[name="a21"], input[name="b21"], input[name="c21"], input[name="d21"]')
.change(evaluarFicha_2_1);

}

    },
    
"2.2": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-azul text-center">
<b>FICHA 2.2.- Aspectos adicionales a considerar</b><br>
A cada una de las preguntas de cada apartado marque una "X" en la columna SI o NO
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Condiciones ambientales de trabajo</b></td>
<td width="80"></td>
<td width="80"></td>
</tr>

${fila22("f22_1","¿Las superficies de los suelos son resbaladizas, inestables, irregulares, con pendientes, o presentan fisuras, grietas o están rotas?")}
${fila22("f22_2","¿Hay restricciones o limitaciones para desplazarse?")}
${fila22("f22_3","¿Hay rampas o cuestas con mucha pendiente?")}
${fila22("f22_4","¿La temperatura ambiental no es adecuada (por frío o calor)?")}
${fila22("f22_5","¿Los espacios son confinados, insuficientes para girar, puertas estrechas, etc.?")}

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Características de los objetos a empujar / tirar</b></td>
<td></td>
<td></td>
</tr>

${fila22("f22_6","¿El objeto limita la visibilidad del trabajador u obstaculiza el movimiento?")}
${fila22("f22_7","¿El objeto carece de asas?")}
${fila22("f22_8","¿El objeto es inestable?")}
${fila22("f22_9","¿El objeto tiene características peligrosas, superficies afiladas, elementos sobresalientes, etc., que puedan dañar al trabajador?")}
${fila22("f22_10","¿Las ruedas están desgastadas, rotas o sin mantenimiento?")}
${fila22("f22_11","¿Las ruedas son inadecuadas para las condiciones de trabajo?")}

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Características de la tarea</b></td>
<td></td>
<td></td>
</tr>

${fila22("f22_12","¿La tarea de empuje o tracción se realiza por más de 8 horas al día?")}
${fila22("f22_13","¿Se deben hacer movimientos acelerados para iniciar, frenar o mover la carga?")}
${fila22("f22_14","¿La tarea requiere el uso de las manos por detrás del cuerpo para transportar la carga?")}

</table>

<div id="resultadoFicha22" class="p-3"></div>

</div>
</div>
`;

$('#ficha_2_2').html(html);

$('input[name^="f22_"]').change(evaluarFicha_2_2);

}

    },

    "2.3": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-rojo text-center">
<b>FICHA 2.3. Evaluación Rápida para identificar la presencia de condiciones inaceptables (Zona roja) por EMPUJE Y TRACCIÓN DE CARGAS</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

${fila23("r23_1","a.","¿La fuerza requerida en el empuje o tracción es “Muy intensa” o superior (Escala de Borg mayor o igual a 8)? <br>  O <br>  ¿la fuerza requerida  en el empuje  o tracción para iniciar el movimiento es 360 N o más para hombres, o de 240 N más para mujeres? <br> O  <br> ¿la fuerza requerida para  el empuje o tracción para mantener el movimiento es de 250 N o más para hombres o de 150 N o más mujeres?")}

${fila23("r23_2","b.","¿La fuerza de empuje o tracción se aplica a una altura de agarre superior a 150 cm o menor a 60 cm?")}

${fila23("r23_3","c.","¿La acción de empuje o tracción se realiza con el tronco flexionado o en torsión?")}

${fila23("r23_4","d.","¿Se realiza la tarea de empuje o tracción durante más de 8 horas al día?")}

</table>

<div id="resultadoFicha23" class="p-3"></div>

</div>
</div>
`;

$('#ficha_2_3').html(html);

$('input[name^="r23_"]').change(evaluarFicha_2_3);

}

    },
    

    "3.1": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde text-center">
<b>FICHA 3.1.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por MOVIMIENTOS REPETITIVOS DE LA EXTREMIDAD SUPERIOR.</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr>
<td width="40" class="texto-pregunta">a.</td>
<td class="texto-pregunta">
¿Las extremidades superiores están inactivas por más del 50% del tiempo total del trabajo repetitivo (se considera como tiempo de inactividad de la extremidad superior cuando el trabajador camina con las manos vacías, o lee, o hace control visual, o espera que la máquina concluya el trabajo, etc.)?
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m1" id="m1_no" value="NO">
<label class="form-check-label" for="m1_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m1" id="m1_si" value="SI">
<label class="form-check-label" for="m1_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">b.</td>
<td class="texto-pregunta">¿Ninguno de los brazos trabaja con el codo casi a la altura del hombro por más del 10% del tiempo de trabajo repetitivo?</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m2" id="m2_no" value="NO">
<label class="form-check-label" for="m2_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m2" id="m2_si" value="SI">
<label class="form-check-label" for="m2_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">c.</td>
<td class="texto-pregunta">
¿La fuerza necesaria para realizar el trabajo es menor a moderada (es ligera)?<br><br>
O bien,<br><br>
¿Si la fuerza es moderada, no supera el 25% del tiempo de trabajo repetitivo?
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m3" id="m3_no" value="NO">
<label class="form-check-label" for="m3_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m3" id="m3_si" value="SI">
<label class="form-check-label" for="m3_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">d.</td>
<td class="texto-pregunta">¿Están ausentes los picos de fuerza (más que Moderada en la Escala Borg)?</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m4" id="m4_no" value="NO">
<label class="form-check-label" for="m4_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m4" id="m4_si" value="SI">
<label class="form-check-label" for="m4_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">e.</td>
<td class="texto-pregunta">¿Hay pausas de duración al menos 8 min cada 2 horas?</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m5" id="m5_no" value="NO">
<label class="form-check-label" for="m5_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m5" id="m5_si" value="SI">
<label class="form-check-label" for="m5_si">SI</label>
</div>
</td>
</tr>

<tr>
<td class="texto-pregunta">f.</td>
<td class="texto-pregunta">¿La (s) tarea (s) de trabajo repetitivo se realiza durante menos de 8 horas al día?</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m6" id="m6_no" value="NO">
<label class="form-check-label" for="m6_no">NO</label>
</div>
</td>

<td class="text-center">
<div class="form-check">
<input class="form-check-input" type="radio" name="m6" id="m6_si" value="SI">
<label class="form-check-label" for="m6_si">SI</label>
</div>
</td>
</tr>

</table>

<div class="p-3">
Si a todas las preguntas ha contestado “SI” entonces la tarea tiene un riesgo aceptable y está en la Zona Verde.
Si alguna de las respuestas es “NO”, compruebe si se trata de una tarea con un nivel de riesgo inaceptable según la ficha 3.2.
</div>

</div>
</div>
`;

$('#ficha_3_1').html(html);

$('input[name="m1"], input[name="m2"], input[name="m3"], input[name="m4"], input[name="m5"], input[name="m6"]')
.change(evaluarFicha_3_1);

}

},

    "3.2": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-rojo text-center">
<b>FICHA 3.2. Evaluación Rápida para identificar la presencia de condiciones inaceptables (Zona roja) por MOVIMIENTOS REPETITIVOS DE LA EXTREMIDAD SUPERIOR</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

${fila32("r32_1","a.","¿Las acciones técnicas de una extremidad son tan rápidas que no es posible contarlas?")}

${fila32("r32_2","b.","¿Un brazo o ambos trabajan con el codo casi a la altura del hombro por la mitad o más del tiempo de trabajo repetitivo?")}

${fila32("r32_3","c.","¿Se realizan picos de fuerza (Fuerza intensa o más en la de escala Borg) durante el 5% o más del tiempo de trabajo repetitivo?")}

${fila32("r32_4","d.","¿Se requiere el agarre de objetos con los dedos (agarre de precisión) durante más del 80% del tiempo de trabajo repetitivo ?")}

${fila32("r32_5","e.","En turnos de 6 horas o más, ¿sólo tiene una pausa o ninguna?")}

${fila32("r32_6","f.","¿El tiempo de trabajo repetitivo es superior a 8 horas en el turno?")}

</table>

<div id="resultadoFicha32" class="p-3"></div>

</div>
</div>
`;

$('#ficha_3_2').html(html);

$('input[name^="r32_"]').change(evaluarFicha_3_2);

}

    },
    
    "4.1": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde text-center">
<b>FICHA 4.1.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por POSTURAS ESTÁTICAS FORZADAS</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Cabeza y tronco</b></td>
<td width="80"></td>
<td width="80"></td>
</tr>

${fila41("p1","a.","¿El tronco está erguido, o si está flexionado o en extensión el ángulo no supera los 20°?")}
${fila41("p2","b.","¿El cuello está recto, o si está flexionado o en extensión el ángulo no supera los 25°?")}
${fila41("p3","c.","¿La cabeza está recta, o si está inclinada lateralmente el ángulo no supera los 25°?")}

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Extremidad Superior</b></td>
<td></td>
<td></td>
</tr>

${fila41("p4","d.","¿El brazo está sin apoyo y la flexión no supera el ángulo de 20°?")}
${fila41("p5","e.","¿El brazo está con apoyo y la flexión no supera el ángulo de 60°?")}
${fila41("p6","f.","¿El codo realiza flexo-extensiones o prono-supinacinaciones no extremas (pequeñas)?")}
${fila41("p7","g.","¿La muñeca está en posición neutra o no realiza desviaciones extremas (flexión,extensión,desviación radial o ulnar)?")}

<tr class="bg-light text-center">
<td colspan="2" class="texto-pregunta"><b>Extremidad Inferior</b></td>
<td></td>
<td></td>
</tr>

${fila41("p8","h.","¿Las flexiones extremas de rodilla están ausentes?")}
${fila41("p9","i.","¿Las dorsiflexiones y flexiones plantares de tobillo extremas están ausentes?")}
${fila41("p10","j.","¿Las posturas de rodillas y cuclillas están ausentes?")}
${fila41("p11","k.","Si la postura es sentado, ¿el ángulo de rodilla está entre 90° y 135°?")}

</table>

<div id="resultadoFicha41" class="p-3"></div>

</div>
</div>
`;

$('#ficha_4_1').html(html);

$('input[name^="p"]').change(evaluarFicha_4_1);

}

    },
    "4.2": {

render: function () {

let html = `
<div class="card mt-3">

<div class="card-header header-verde text-center">
<b>FICHA 4.2.- Evaluación Rápida para Identificar la presencia de condiciones aceptables (Zona verde) por POSTURAS DINÁMICAS FORZADAS</b>
<br>
NOTA: Señale con una "X", cuando la condición verificada está presente (columna "SI") y cuando no está presente (columna "NO")
</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

${fila42("d1","a.","¿El tronco está erguido, o realiza flexiones o extensiones sin superar el ángulo de 20°?")}
${fila42("d2","b.","¿El tronco está erguido, o realiza inclinaciones laterales o torsión sin superar el ángulo de 10°?")}
${fila42("d3","c.","¿La cabeza está recta, o realiza inclinaciones laterales sin superar el ángulo de 10°?")}
${fila42("d4","d.","¿La cabeza está recta, o realiza torsión del cuello sin superar el ángulo de 45°?")}
${fila42("d5","e.","¿El cuello está recto o realiza flexiones entre 0° y 40°?")}
${fila42("d6","f.","¿Los brazos están neutros, o realizan flexión o abducción sin superar el ángulo de 20°?")}

</table>

<div id="resultadoFicha42" class="p-3"></div>

</div>
</div>
`;

$('#ficha_4_2').html(html);

$('input[name^="d"]').change(evaluarFicha_4_2);

}

},

};

///////  EVALUACION FICHAS 
function evaluarFicha_1_1() {

	let a = $('input[name="a"]:checked').val();
	let b = $('input[name="b"]:checked').val();
	let c = $('input[name="c"]:checked').val();
	let d = $('input[name="d"]:checked').val();
	let e = $('input[name="e"]:checked').val();

	if (!a || !b || !c || !d || !e) return;

	let ok = (a==="SI" && b==="SI" && c==="SI" && d==="SI" && e==="SI");

	if (!ok) {
		$('#ficha_1_4').html("");
		fichas["1.4"].render();  
	} else {
		$('#ficha_1_4').html("");
	}

}

function evaluarFicha_1_2() {

	let a = $('input[name="a2"]:checked').val();
	let b = $('input[name="b2"]:checked').val();
	let c = $('input[name="c2"]:checked').val();

	if (!a || !b || !c) return;

	let ok = ((a === "SI" || b === "SI") && c === "SI");

		if (!ok) {
		$('#ficha_1_5').html(""); 
		fichas["1.5"].render();  
	} else {
		$('#ficha_1_5').html(""); 
	}


}

function evaluarFicha_1_3() {

	let respuestas = [];

	for (let i = 1; i <= 9; i++) {
		let val = $(`input[name="f${i}"]:checked`).val();
		if (!val) return;
		respuestas.push(val);
	}

	let todasNO = respuestas.every(r => r === "NO");

	if (todasNO) {
		$('#resultadoFicha13').html(`
			<div class="alert alert-success">
			Si a todas las preguntas ha contestado “NO”, no hay presencia de factores adicionales al riesgo.
			</div>
		`);
	} else {
		$('#resultadoFicha13').html(`
			<div class="alert alert-warning">
			Si una o más respuestas son “SI”, los factores de riesgo adicionales deben ser considerados.
			</div>
		`);
	}

}

function evaluarFicha_1_4() {

	let respuestas = [];

	for (let i = 1; i <= 11; i++) {
		let val = $(`input[name="r${i}"]:checked`).val();
		if (!val) return;
		respuestas.push(val);
	}

	let algunaSI = respuestas.some(r => r === "SI");
	let todasNO = respuestas.every(r => r === "NO");

	if (algunaSI) {

		$('#resultadoFicha14').html(`
			<div class="alert alert-danger">
			Si alguna de las respuestas es “SI”, la tarea probablemente está en la <b>Zona Roja</b> y tiene un nivel de riesgo inaceptable.<br>
			Se recomienda realizar la evaluación específica del riesgo.
			</div>
		`);

	} else if (todasNO) {

		$('#resultadoFicha14').html(`
			<div class="alert alert-info">
			Si todas las respuestas son “NO”, no es posible discriminar el nivel de riesgo de forma rápida y se requiere evaluación específica.
			</div>
		`);

	}

}

function evaluarFicha_1_5() {

	let a = $('input[name="t1"]:checked').val();
	let b = $('input[name="t2"]:checked').val();

	if (!a || !b) return;

	let algunaSI = (a === "SI" || b === "SI");
	let todasNO = (a === "NO" && b === "NO");

	if (algunaSI) {

		$('#resultadoFicha15').html(`
			<div class="alert alert-danger">
			Si alguna de las respuestas es “SI”, la tarea probablemente está en la <b>Zona Roja</b> y tiene un nivel de riesgo inaceptable.<br>
			Se recomienda realizar la evaluación específica del riesgo de la tarea por manipulación manual de cargas para definir la intervención.
			</div>
		`);

	} else if (todasNO) {

		$('#resultadoFicha15').html(`
			<div class="alert alert-info">
			Si todas las respuestas son “NO”, no es posible discriminar el nivel de riesgo de forma rápida y por tanto, es necesario realizar la evaluación específica.
			</div>
		`);

	}

}

function evaluarFicha_2_1() {

	let a = $('input[name="a21"]:checked').val();
	let b = $('input[name="b21"]:checked').val();
	let c = $('input[name="c21"]:checked').val();
	let d = $('input[name="d21"]:checked').val();

	if (!a || !b || !c || !d) return;

	let ok = (a==="SI" && b==="SI" && c==="SI" && d==="SI");

	if (!ok) {
		$('#ficha_2_3').html(""); 
		fichas["2.3"].render();  
	} else {
		$('#ficha_2_3').html(""); 
	}

}

function evaluarFicha_2_2() {

	let total = $('input[name^="f22_"]:checked').length;

	if (total < 14) return;

	let haySI = false;

	$('input[name^="f22_"]:checked').each(function () {
		if ($(this).val() === "SI") {
			haySI = true;
		}
	});

	if (haySI) {
		$('#resultadoFicha22').html(`
			<div class="alert alert-warning">
			Si una o más respuestas son <b>“SI”</b>, los riesgos específicos adicionales deben ser cuidadosamente considerados para garantizar la ausencia del riesgo.
			</div>
		`);
	} else {
		$('#resultadoFicha22').html(`
			<div class="alert alert-success">
			Si a todas las preguntas ha contestado <b>“NO”</b>, no hay presencia de factores adicionales al riesgo por empuje y tracción.
			</div>
		`);
	}

}

function evaluarFicha_2_3() {

	let total = $('input[name^="r23_"]:checked').length;

	if (total < 4) return;

	let haySI = false;

	$('input[name^="r23_"]:checked').each(function () {
		if ($(this).val() === "SI") {
			haySI = true;
		}
	});

	if (haySI) {
		$('#resultadoFicha23').html(`
			<div class="alert alert-danger">
			Si alguna de las respuestas es <b>“SI”</b>, la tarea probablemente está en la <b>Zona Roja</b> y tiene un nivel de riesgo inaceptable. Se recomienda realizar la evaluación específica del riesgo.
			</div>
		`);
	} else {
		$('#resultadoFicha23').html(`
			<div class="alert alert-secondary">
			Si todas las respuestas son <b>“NO”</b>, no es posible discriminar el nivel de riesgo de forma rápida y es necesario realizar la evaluación específica.
			</div>
		`);
	}

}

function evaluarFicha_3_1() {

	let m1 = $('input[name="m1"]:checked').val();
	let m2 = $('input[name="m2"]:checked').val();
	let m3 = $('input[name="m3"]:checked').val();
	let m4 = $('input[name="m4"]:checked').val();
	let m5 = $('input[name="m5"]:checked').val();
	let m6 = $('input[name="m6"]:checked').val();

	if (!m1 || !m2 || !m3 || !m4 || !m5 || !m6) return;

	let ok = (
		m1 === "SI" &&
		m2 === "SI" &&
		m3 === "SI" &&
		m4 === "SI" &&
		m5 === "SI" &&
		m6 === "SI"
	);

	if (!ok) {
		$('#ficha_3_2').html("");
		fichas["3.2"].render();
	} else {
		$('#ficha_3_2').html("");
	}

}

function evaluarFicha_3_2() {

	let total = $('input[name^="r32_"]:checked').length;

	if (total < 6) return;

	let haySI = false;

	$('input[name^="r32_"]:checked').each(function () {
		if ($(this).val() === "SI") {
			haySI = true;
		}
	});

	if (haySI) {
		$('#resultadoFicha32').html(`
			<div class="alert alert-danger">
			Si alguna de las respuestas es <b>“SI”</b>, la tarea probablemente está en la <b>Zona Roja</b> y tiene un nivel de riesgo inaceptable.
			</div>
		`);
	} else {
		$('#resultadoFicha32').html(`
			<div class="alert alert-warning">
			Si todas las respuestas son <b>“NO”</b>, no es posible discriminar el riesgo rápidamente y se requiere evaluación específica.
			</div>
		`);
	}

}

function evaluarFicha_4_1() {

	let total = $('input[name^="p"]:checked').length;

	if (total < 11) return;

	let todasSI = true;

	$('input[name^="p"]:checked').each(function () {
		if ($(this).val() !== "SI") {
			todasSI = false;
		}
	});

	if (todasSI) {
		$('#resultadoFicha41').html(`
			<div class="alert alert-success">
			Si a todas las preguntas ha contestado <b>“SI”</b>, la tarea tiene un riesgo aceptable y está en la <b>Zona Verde</b>.
			</div>
		`);
	} else {
		$('#resultadoFicha41').html(`
			<div class="alert alert-warning">
			Si una o más respuestas son <b>“NO”</b>, se recomienda realizar la evaluación específica del riesgo por postura estática.
			</div>
		`);
	}

}

function evaluarFicha_4_2() {

	let total = $('input[name^="d"]:checked').length;

	if (total < 6) return;

	let todasSI = true;

	$('input[name^="d"]:checked').each(function () {
		if ($(this).val() !== "SI") {
			todasSI = false;
		}
	});

	if (todasSI) {
		$('#resultadoFicha42').html(`
			<div class="alert alert-success">
			Si a todas las preguntas ha contestado <b>“SI”</b>, la tarea tiene un riesgo aceptable y está en la <b>Zona Verde</b>.
			</div>
		`);
	} else {
		$('#resultadoFicha42').html(`
			<div class="alert alert-warning">
			Si una o más respuestas son <b>“NO”</b>, se recomienda realizar la evaluación específica del riesgo por postura dinámica.
			</div>
		`);
	}

}

///// FILAS ///// 
function fila13(name, texto) {
    return `
    <tr>
        <td colspan="2" class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila14(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta" >${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila15(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta">${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila22(name, texto) {
    return `
    <tr>
        <td colspan="2" class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila23(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta">${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila32(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta">${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila41(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta">${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

function fila42(name, letra, texto) {
    return `
    <tr>
        <td width="40" class="texto-pregunta">${letra}</td>
        <td class="texto-pregunta">${texto}</td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_no" value="NO">
                <label class="form-check-label" for="${name}_no">NO</label>
            </div>
        </td>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="${name}" id="${name}_si" value="SI">
                <label class="form-check-label" for="${name}_si">SI</label>
            </div>
        </td>
    </tr>
    `;
}

$(document).ready(function(){

    $('[name="P1_CARGA_MAYOR_3KG"]').on('change', function(){

        let p1 = $(this).val();

        if (p1 === "SI") {

            $('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION').attr('style', 'display:block !important;');


        } else if (p1 === "NO") {

            $('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION').attr('style', 'display:none !important;');

            $('[name="P2_FRECUENCIA_CARGA"]').val("NO");
			$('[name="P3_MANIPULACION_CARGA"]').val("NO");
			
		} else {
			
  			$('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION').attr('style', 'display:block !important;');
 			$('[name="P2_FRECUENCIA_CARGA"]').val("");
			$('[name="P3_MANIPULACION_CARGA"]').val("");
		}

    });

});

function generarJSONActividades() {

    let data = [];

    $('#contenedorActividades .actividad-item').each(function () {

        let actividad = {
            nombre: $(this).find('.actividad-nombre').val(),
            tareas: []
        };

        $(this).find('.tarea-item').each(function () {

            actividad.tareas.push({
                nombre: $(this).find('.tarea-nombre').val(),
                frecuencia: $(this).find('.tarea-frecuencia').val(),
                duracion: $(this).find('.tarea-duracion').val()
            });

        });

        data.push(actividad);
    });

    return JSON.stringify(data);
}

function generarJSONFichas() {

    let resultado = [];

    $('[id^="ficha_"]').each(function () {

        let id = $(this).attr('id'); 
        let ficha = id.replace('ficha_', '').replace('_', '.'); 

        let preguntas = [];

        $(this).find('tr').each(function () {

            let texto = $(this).find('.texto-pregunta').text().trim();
            let radio = $(this).find('input[type="radio"]:checked');

            if (radio.length > 0) {

                preguntas.push({
                    name: radio.attr('name'),
                    texto: texto,
                    respuesta: radio.val()
                });
            }

        });

        if (preguntas.length > 0) {

            resultado.push({
                ficha: ficha,
                preguntas: preguntas,
                resultado: calcularResultadoFicha(ficha, preguntas)
            });
        }

    });

    return JSON.stringify(resultado);
}

function calcularResultadoFicha(ficha, preguntas) {

    let respuestas = preguntas.map(p => p.respuesta);

    if (respuestas.includes("NO")) return "ROJO";

    return "VERDE";
}

$("#boton_guardar_fichastecnicas").click(function (e) {
    e.preventDefault();


    formularioValido = validarFormulario3($('#form_fichas'))

    if (formularioValido) {


		let json = generarJSONActividades();

		if (!$('#JSON_ACTIVIDADES').length) {
			$('#form_fichas').append('<input type="hidden" name="JSON_ACTIVIDADES" id="JSON_ACTIVIDADES">');
		}

		$('#JSON_ACTIVIDADES').val(json);

		let jsonFichas = generarJSONFichas();

		if (!$('#JSON_FICHAS').length) {
			$('#form_fichas').append('<input type="hidden" name="JSON_FICHAS" id="JSON_FICHAS">');
		}

		$('#JSON_FICHAS').val(jsonFichas);
		
    if (ID_FICHAS_TECNICAS == 0) {
        
        alertMensajeConfirm({
            title: "¿Desea guardar la información?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_fichastecnicas')
            await ajaxAwaitFormData({ api: 1,RECO_ID: recsensorial, ID_FICHAS_TECNICAS: ID_FICHAS_TECNICAS }, 'recoergofichas', 'form_fichas', 'boton_guardar_fichastecnicas', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                    ID_FICHAS_TECNICAS = data.fichas.ID_FICHAS_TECNICAS
                    alertMensaje('success','Información guardada correctamente', 'Esta información esta lista para usarse',null,null, 1500)
                     $('#modal_fichas').modal('hide')
                    document.getElementById('form_fichas').reset();
                    Tablarecofichasergo.ajax.reload()
                
            })
            
            
            
        }, 1)
        
    } else {
            alertMensajeConfirm({
            title: "¿Desea editar la información de este formulario?",
            text: "Al guardarla, se podra usar",
            icon: "question",
        },async function () { 

            await loaderbtn('boton_guardar_fichastecnicas')
            await ajaxAwaitFormData({ api: 1, RECO_ID: recsensorial, ID_FICHAS_TECNICAS: ID_FICHAS_TECNICAS }, 'recoergofichas', 'form_fichas', 'boton_guardar_fichastecnicas', { callbackAfter: true, callbackBefore: true }, () => {
        
                Swal.fire({
                    icon: 'info',
                    title: 'Espere un momento',
                    text: 'Estamos guardando la información',
                    showConfirmButton: false
                })

                $('.swal2-popup').addClass('ld ld-breath')
        
                
            }, function (data) {
                    
                setTimeout(() => {

                    ID_FICHAS_TECNICAS = data.fichas.ID_FICHAS_TECNICAS
                    alertMensaje('success', 'Información editada correctamente', 'Información guardada')
                     $('#modal_fichas').modal('hide')
                    document.getElementById('form_fichas').reset();
                    Tablarecofichasergo.ajax.reload()

                }, 300);  
            })
        }, 1)
    }

} else {
    alertToast('Por favor, complete todos los campos del formulario.', 'error', 2000)

}
    
});

function mostrarTablarecofichasergo() {
	try {
		var ruta = "/Tablarecofichasergo";

		if (Tablarecofichasergo != null) {
			Tablarecofichasergo.destroy();
		}

		Tablarecofichasergo = $('#Tablarecofichasergo').DataTable({
			"ajax": {
				"url": ruta,
				"type": "get",
				"cache": false,
				"data": {
					ergoid: recsensorial 
				},
				"error": function (xhr, error, code) {
					console.log('error en Tablarecofichasergo');
				}
			},
			"columns": [
				{
					data: null,
					render: function (data, type, row, meta) {
						return meta.row + 1;
					}
				},
				{
					"data": "NOMBRE_EMPLEADO_FICHA",
					"defaultContent": "-"
				},
				{
					"data": "NO_EMPLEADO_FICHA",
					"defaultContent": "-"
				},
				{ 
					data: 'BTN_EDITAR',
					orderable: false,
					searchable: false
				},
			],
			"lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
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

		Tablarecofichasergo.on('draw', function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

	} catch (exception) {
		console.error("Error en Tablarecofichasergo:", exception);
	}
}

function pintarActividades(json) {

    let contenedor = $('#contenedorActividades');
    contenedor.empty();

    if (!json) return;

    let data;

    if (typeof json === "string") {
        try {
            data = JSON.parse(json);
        } catch (e) {
            data = [];
        }
    } else {
        data = json;
    }

    if (!Array.isArray(data)) return;

    data.forEach((actividad, index) => {

        let num = index + 1;

        let htmlActividad = `
        <div class="actividad-item" id="actividad_${num}">
            
            <div class="actividad-card">

                <div class="d-flex justify-content-between mb-2">
                    <strong>Actividad ${num}</strong>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarActividad(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

                <div class="actividad-row">

                    <div class="actividad-left">
                        <input type="text" 
                            class="form-control actividad-nombre" 
                            value="${actividad.nombre || ''}">
                    </div>

                    <div class="actividad-right">

                        <button type="button" 
                            class="btn btn-agregar-tarea"
                            onclick="agregarTarea(${num})">
                            + Agregar tarea
                        </button>

                        <div id="tareas_${num}"></div>

                    </div>

                </div>

            </div>

        </div>
        `;

        contenedor.append(htmlActividad);

        if (Array.isArray(actividad.tareas)) {

            actividad.tareas.forEach((tarea, i) => {

                let numT = i + 1;

                let htmlTarea = `
                <div class="tarea-item">

                    <div class="d-flex justify-content-between">
                        <small>Tarea ${numT}</small>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="eliminarTarea(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                    <input type="text" 
                        class="form-control mt-1 tarea-nombre" 
                        value="${tarea.nombre || ''}">

                    <input type="text" 
                        class="form-control mt-1 tarea-frecuencia" 
                        value="${tarea.frecuencia || ''}">

                    <input type="text" 
                        class="form-control mt-1 tarea-duracion" 
                        value="${tarea.duracion || ''}">

                </div>
                `;

                $(`#tareas_${num}`).append(htmlTarea);

            });

        }

    });
}

$('#Tablarecofichasergo tbody').on('click', 'td>button.editar', function () {

    var tr = $(this).closest('tr');
    var row = Tablarecofichasergo.row(tr);

    let data = row.data();

    ID_FICHAS_TECNICAS = data.ID_FICHAS_TECNICAS;

    editarDatoTabla(data, 'form_fichas', 'modal_fichas',1);

    $('#modal_fichas .modal-title').html(data.NOMBRE_EMPLEADO_FICHA);

	
	
    let areasGuardadas = data.CAT_AREAS_FICHA;

    if (typeof areasGuardadas === "string") {
        try { areasGuardadas = JSON.parse(areasGuardadas); } 
        catch (e) { areasGuardadas = []; }
    }

    if (!Array.isArray(areasGuardadas)) {
        areasGuardadas = [];
    }

    cargarCategoriasSelect(function () {

        $('#CATEGORIA_ID_FICHA')
            .val(data.CATEGORIA_ID_FICHA)
            .trigger('change');

        setTimeout(() => {
            cargarAreasFicha(areasGuardadas);
        }, 300);

    });

    $("#contenedorActividades").empty(); 
	pintarActividades(data.JSON_ACTIVIDADES);

	setTimeout(() => {

		limpiarTodasLasFichas();

	
		if (data.P1_CARGA_MAYOR_3KG !== "SI") {
			$('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION')
				.attr('style', 'display:none !important;');
		} else {
			$('#TEXTO_MANIPULACION, #LEVANTAMIENTO_CARGA, #TRANSPORTE_CARGAS, #EMPUJE_TRACCION')
				.attr('style', 'display:block !important;');
		}

		fichas["1.1"].render();
		fichas["1.3"].render();
		fichas["1.2"].render();
		fichas["2.1"].render();
		fichas["2.2"].render();
		fichas["3.1"].render();
		fichas["4.1"].render();
		fichas["4.2"].render();

		pintarFichasBase(data.JSON_FICHAS);

		activarLogicaFichas();

		setTimeout(() => {

		pintarFichasDependientes(data.JSON_FICHAS);

		abrirSeccionesSegunJSON(data.JSON_FICHAS);

		}, 200);

	}, 300);


});

function limpiarTodasLasFichas() {

    $('[id^="ficha_"]').each(function () {
        $(this).html('');
    });

    $('[id^="resultadoFicha"]').html('');
}

function abrirSeccionesSegunJSON(json) {

    let data = typeof json === "string" ? JSON.parse(json) : json;

    let abrir2 = false;
    let abrir3 = false;
    let abrir4 = false;
    let abrir5 = false;
    let abrir6 = false;
    let abrir7 = false;

    data.forEach(f => {

        let ficha = f.ficha;

        if (["1.1","1.3","1.4"].includes(ficha)) abrir2 = true;

        if (["1.2","1.5"].includes(ficha)) abrir3 = true;

        if (ficha.startsWith("2.")) abrir4 = true;

        if (ficha.startsWith("3.")) abrir5 = true;

        if (ficha === "4.1") abrir6 = true;

        if (ficha === "4.2") abrir7 = true;

    });

    if (abrir2) $('#contenido2').show();
    if (abrir3) $('#contenido3').show();
    if (abrir4) $('#contenido4').show();
    if (abrir5) $('#contenido5').show();
    if (abrir6) $('#contenido6').show();
    if (abrir7) $('#contenido7').show();
}

function pintarFichasBase(json) {

    let data = typeof json === "string" ? JSON.parse(json) : json;

    data.forEach(f => {

        if (["1.1","1.2","2.1","3.1","4.1"].includes(f.ficha)) {

            f.preguntas.forEach(p => {
                $(`input[name="${p.name}"][value="${p.respuesta}"]`).prop('checked', true);
            });

        }

    });
}

function pintarFichasDependientes(json) {

    let data = typeof json === "string" ? JSON.parse(json) : json;

    data.forEach(f => {

        if (!["1.1","1.2","2.1","3.1","4.1"].includes(f.ficha)) {

            f.preguntas.forEach(p => {
                $(`input[name="${p.name}"][value="${p.respuesta}"]`).prop('checked', true);
            });

        }

    });
}

function activarLogicaFichas() {

    evaluarFicha_1_1();
    evaluarFicha_1_2();
    evaluarFicha_1_3();
    evaluarFicha_1_4();
    evaluarFicha_1_5();

    evaluarFicha_2_1();
    evaluarFicha_2_2();
    evaluarFicha_2_3();

    evaluarFicha_3_1();
    evaluarFicha_3_2();

    evaluarFicha_4_1();
    evaluarFicha_4_2();
}



///////////// INFORME DE RECONOCIMIENTO  /////////////






function cargarGraficas() {

    $.get('/getGraficaErgo/' + recsensorial, function (data) {
        generarGraficas(data);
    });

}



function generarGraficas(data) {

    $('#contenedorGraficas').empty();

    data.forEach(function (item, index) {

        let id = 'chart_' + index;

        $('#contenedorGraficas').append(`

            <div style="position:relative; width:500px; height:300px; margin:20px auto;">

                <div id="${id}" style="width:100%; height:100%;"></div>

            </div>

        `);

        crearGrafica(
            id,
            item.RESULTADO,
            item.NOMBRE_CATEGORIA_ERGO
        );

    });

}



function crearGrafica(id, resultado, nombreCategoria) {

    var chart = echarts.init(
        document.getElementById(id)
    );

    let valor = resultado === 'SI'
        ? 75
        : 25;

    chart.setOption({

        //-----------------------------------
        // TEXTOS SUPERIORES
        //-----------------------------------

        graphic: [

            {
                type: 'text',

                left: '22%',

                top: '12%',

                style: {

                    text: 'Sí',

                    fill: '#2C2A29',

                    fontSize: 18,

                    fontWeight: 'bold'
                }
            },

            {
                type: 'text',

                left: '72%',

                top: '12%',

                style: {

                    text: 'No',

                    fill: '#2C2A29',

                    fontSize: 18,

                    fontWeight: 'bold'
                }
            }

        ],

        //-----------------------------------
        // GRAFICA
        //-----------------------------------

        series: [

            {

                type: 'gauge',

                startAngle: 180,

                endAngle: 0,

                min: 0,

                max: 100,

                radius: '90%',

                center: ['50%', '65%'],

                //-----------------------------------
                // COLORES
                //-----------------------------------

                axisLine: {

                    lineStyle: {

                        width: 20,

                        color: [

                            [0.5, 'green'],

                            [1, 'red']
                        ]
                    }
                },

                //-----------------------------------
                // PUNTERO
                //-----------------------------------

                pointer: {

                    itemStyle: {

                        color: 'black'
                    }
                },

                //-----------------------------------
                // OCULTAR
                //-----------------------------------

                axisTick: {
                    show: false
                },

                splitLine: {
                    show: false
                },

                axisLabel: {
                    show: false
                },

                anchor: {
                    show: false
                },

                title: {
                    show: false
                },

                //-----------------------------------
                // TEXTO INFERIOR
                //-----------------------------------

                detail: {

                    formatter: nombreCategoria,

                    fontSize: 18,

                    fontWeight: 'bold',

                    offsetCenter: [0, '60%'],

                    color: '#000',

                    width: 300
                },

                //-----------------------------------
                // VALOR
                //-----------------------------------

                data: [

                    {
                        value: valor
                    }

                ]
            }
        ]
    });
}




/////////////// PORTADA  //////////////


function cargarDatosInformesRecoErgo()
{

    $.get('/obtenerDatosInformesRecoergo/' + recsensorial, function(response)
    {

        //-----------------------------------------
        // LLENAR SELECTS
        //-----------------------------------------

        $('#NIVEL1').html(response.checks);
        $('#NIVEL2').html(response.checks);
        $('#NIVEL3').html(response.checks);
        $('#NIVEL4').html(response.checks);
        $('#NIVEL5').html(response.checks);

        $('#OPCION_PORTADA1').html(response.opciones);
        $('#OPCION_PORTADA2').html(response.opciones);
        $('#OPCION_PORTADA3').html(response.opciones);
        $('#OPCION_PORTADA4').html(response.opciones);
        $('#OPCION_PORTADA5').html(response.opciones);
        $('#OPCION_PORTADA6').html(response.opciones);


        //-----------------------------------------
        // SI EXISTE INFORMACION
        //-----------------------------------------

        if(response.data != 'No se encontraron datos')
        {

            let data = response.data[0];


            //-----------------------------------------
            // ASIGNAR VALORES
            //-----------------------------------------

            $('#NIVEL1').val(data.NIVEL1).trigger('change');
            $('#NIVEL2').val(data.NIVEL2).trigger('change');
            $('#NIVEL3').val(data.NIVEL3).trigger('change');
            $('#NIVEL4').val(data.NIVEL4).trigger('change');
            $('#NIVEL5').val(data.NIVEL5).trigger('change');


            $('#OPCION_PORTADA1').val(data.OPCION_PORTADA1).trigger('change');
            $('#OPCION_PORTADA2').val(data.OPCION_PORTADA2).trigger('change');
            $('#OPCION_PORTADA3').val(data.OPCION_PORTADA3).trigger('change');
            $('#OPCION_PORTADA4').val(data.OPCION_PORTADA4).trigger('change');
            $('#OPCION_PORTADA5').val(data.OPCION_PORTADA5).trigger('change');
            $('#OPCION_PORTADA6').val(data.OPCION_PORTADA6).trigger('change');


            //-----------------------------------------
            // MES Y AÑO
            //-----------------------------------------

            $('#INFORME_MES').val(data.INFORME_MES);
            $('#INFORME_ANIO').val(data.INFORME_ANIO);



            //-----------------------------------------
            // IMAGEN PORTADA
            //-----------------------------------------

            if(data.RUTA_IMAGEN_PORTADA)
            {

                var archivo = data.RUTA_IMAGEN_PORTADA;

                var extension = archivo.substring(
                    archivo.lastIndexOf(".")
                );



                var imagenUrl =
                    '/mostrarportadarecoergo/0/' +
                    recsensorial +
                    extension;



                //-----------------------------------------
                // DROPIFY
                //-----------------------------------------

                if ($('#RUTA_IMAGEN_PORTADA').data('dropify')) {

                    $('#RUTA_IMAGEN_PORTADA')
                        .dropify()
                        .data('dropify')
                        .destroy();


                    $('#RUTA_IMAGEN_PORTADA')
                        .dropify()
                        .data('dropify')
                        .settings.defaultFile = imagenUrl;


                    $('#RUTA_IMAGEN_PORTADA')
                        .dropify()
                        .data('dropify')
                        .init();

                }
                else {

                    $('#RUTA_IMAGEN_PORTADA')
                        .attr('data-default-file', imagenUrl);


                    $('#RUTA_IMAGEN_PORTADA').dropify({

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



                //-----------------------------------------
                // NO REQUERIDO
                //-----------------------------------------

                $('#RUTA_IMAGEN_PORTADA')
                    .attr('required', false);

            }

        }

    }).fail(function(xhr)
    {

        console.log(xhr.responseText);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los datos del informe'
        });

    });

}


$("#form_informe_portada").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);

    $.ajax({
        url: '/guardarPortadaRecoErgo',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        beforeSend: function()
        {

            $("#botonguardar_reporte_portada").prop('disabled', true);

            $("#botonguardar_reporte_portada").html(
                'Guardando... <i class="fa fa-spinner fa-spin"></i>'
            );

        },

        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });


            cargarDatosInformesRecoErgo();

        },

        error: function(xhr)
        {

            console.log(xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar'
            });

        },

        complete: function()
        {

            $("#botonguardar_reporte_portada").prop('disabled', false);

            $("#botonguardar_reporte_portada").html(
                'Guardar portadas <i class="fa fa-save"></i>'
            );

        }

    });

});


/// DATOS GENERALES

function cargarDatosGeneralesInformeReco()
{

    $.get(

        '/obtenerDatosGeneralesInformeReco/' + recsensorial,

        function(response)
        {

        

            if(response.msj){

                return;
            }



            $('#SELECT_INTRODUCCION')
                .val(response.SELECT_INTRODUCCION)
                .trigger('change');

            $('#INFORME_INTRODUCCION')
                .val(response.INFORME_INTRODUCCION);

            $('#INFORME_OBJETIVOGENERALES')
                .val(response.INFORME_OBJETIVOGENERALES);

            $('#INFORME_OBJETIVOSESPECIFICOS')
                .val(response.INFORME_OBJETIVOSESPECIFICOS);

            $('#INFORME_UBICACIONINSTALACION')
                .val(response.INFORME_UBICACIONINSTALACION);

			$('#INFORME_PROCESOINSTALACION')
				.val(response.INFORME_PROCESOINSTALACION);

			$('#INFORME_ACTIVIDADPRINCIPAL')
				.val(response.INFORME_ACTIVIDADPRINCIPAL);

			$('#SELECT_CONCLUSION')
				.val(response.SELECT_CONCLUSION)
				.trigger('change');

			$('#INFORME_CONCLUSION')
				.val(response.INFORME_CONCLUSION);
			
			$('#INFORME_RESPONSABLE1')
				.val(response.INFORME_RESPONSABLE1);


			$('#INFORME_RESPONSABLE1CARGO')
				.val(response.INFORME_RESPONSABLE1CARGO);



			$('#INFORME_RESPONSABLE2')
				.val(response.INFORME_RESPONSABLE2);



		$('#INFORME_RESPONSABLE2CARGO')
			.val(response.INFORME_RESPONSABLE2CARGO);
					//-----------------------------------
            // IMAGEN UBICACION
            //-----------------------------------



			   if(response.RUTA_IMAGEN_UBICACION)
            {

                var archivo = response.RUTA_IMAGEN_UBICACION;

                var extension = archivo.substring(
                    archivo.lastIndexOf(".")
                );



                var imagenUrl =
                    '/mostrarubicacionrecoergo/0/' +
                    recsensorial +
                    extension;



                //-----------------------------------------
                // DROPIFY
                //-----------------------------------------

                if ($('#RUTA_IMAGEN_UBICACION').data('dropify')) {

                    $('#RUTA_IMAGEN_UBICACION')
                        .dropify()
                        .data('dropify')
                        .destroy();


                    $('#RUTA_IMAGEN_UBICACION')
                        .dropify()
                        .data('dropify')
                        .settings.defaultFile = imagenUrl;


                    $('#RUTA_IMAGEN_UBICACION')
                        .dropify()
                        .data('dropify')
                        .init();

                }
                else {

                    $('#RUTA_IMAGEN_UBICACION')
                        .attr('data-default-file', imagenUrl);


                    $('#RUTA_IMAGEN_UBICACION').dropify({

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



                //-----------------------------------------
                // NO REQUERIDO
                //-----------------------------------------

                $('#RUTA_IMAGEN_UBICACION')
                    .attr('required', false);

			}
			



//-----------------------------------
// RESPONSABLE 1
//-----------------------------------

$('#INFORME_RESPONSABLE1')
    .val(response.INFORME_RESPONSABLE1);


$('#INFORME_RESPONSABLE1CARGO')
    .val(response.INFORME_RESPONSABLE1CARGO);



if(response.INFORME_RESPONSABLE1DOCUMENTO)
{

    var archivo =
        response.INFORME_RESPONSABLE1DOCUMENTO;



    var extension = archivo.substring(
        archivo.lastIndexOf(".")
    );



    var imagenUrl =
        '/mostrarresponsable1recoergo/0/' +
        recsensorial +
        extension;



    //-----------------------------------------
    // DROPIFY
    //-----------------------------------------

    if ($('#INFORME_RESPONSABLE1DOCUMENTO')
        .data('dropify')) {

        $('#INFORME_RESPONSABLE1DOCUMENTO')
            .dropify()
            .data('dropify')
            .destroy();



        $('#INFORME_RESPONSABLE1DOCUMENTO')
            .dropify()
            .data('dropify')
            .settings.defaultFile = imagenUrl;



        $('#INFORME_RESPONSABLE1DOCUMENTO')
            .dropify()
            .data('dropify')
            .init();

    }
    else {

        $('#INFORME_RESPONSABLE1DOCUMENTO')
            .attr('data-default-file', imagenUrl);



        $('#INFORME_RESPONSABLE1DOCUMENTO')
            .dropify({

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



    //-----------------------------------------
    // NO REQUERIDO
    //-----------------------------------------

    $('#INFORME_RESPONSABLE1DOCUMENTO')
        .attr('required', false);



    //-----------------------------------------
    // BOTON DESCARGA
    //-----------------------------------------

    $("#boton_descargarresponsabledoc1")
        .css('display', 'block');

}
else {

    $("#boton_descargarresponsabledoc1")
        .css('display', 'none');

}



//-----------------------------------
// RESPONSABLE 2
//-----------------------------------

$('#INFORME_RESPONSABLE2')
    .val(response.INFORME_RESPONSABLE2);


$('#INFORME_RESPONSABLE2CARGO')
    .val(response.INFORME_RESPONSABLE2CARGO);



if(response.INFORME_RESPONSABLE2DOCUMENTO)
{

    var archivo =
        response.INFORME_RESPONSABLE2DOCUMENTO;



    var extension = archivo.substring(
        archivo.lastIndexOf(".")
    );



    var imagenUrl =
        '/mostrarresponsable2recoergo/0/' +
        recsensorial +
        extension;



    //-----------------------------------------
    // DROPIFY
    //-----------------------------------------

    if ($('#INFORME_RESPONSABLE2DOCUMENTO')
        .data('dropify')) {

        $('#INFORME_RESPONSABLE2DOCUMENTO')
            .dropify()
            .data('dropify')
            .destroy();



        $('#INFORME_RESPONSABLE2DOCUMENTO')
            .dropify()
            .data('dropify')
            .settings.defaultFile = imagenUrl;



        $('#INFORME_RESPONSABLE2DOCUMENTO')
            .dropify()
            .data('dropify')
            .init();

    }
    else {

        $('#INFORME_RESPONSABLE2DOCUMENTO')
            .attr('data-default-file', imagenUrl);



        $('#INFORME_RESPONSABLE2DOCUMENTO')
            .dropify({

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



    //-----------------------------------------
    // NO REQUERIDO
    //-----------------------------------------

    $('#INFORME_RESPONSABLE2DOCUMENTO')
        .attr('required', false);



    //-----------------------------------------
    // BOTON DESCARGA
    //-----------------------------------------

    $("#boton_descargarresponsabledoc2")
        .css('display', 'block');

}
else {

    $("#boton_descargarresponsabledoc2")
        .css('display', 'none');

}

        }

    );

}



/////////////// INTRODUCCION  //////////////





$('#SELECT_INTRODUCCION').on('change', function () {

    var selectedOption = $(this).find('option:selected');

    var introduccion = selectedOption.data('introduccion');

    $('#INFORME_INTRODUCCION').val(introduccion);

});



$("#form_informe_introduccion").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);



    $.ajax({

        url: '/guardarIntroduccionRecoErgo',

        type: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_introduccion")
                .prop('disabled', true);

            $("#botonguardar_reporte_introduccion")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        error: function(xhr)
        {

            console.log(xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar'
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_introduccion")
                .prop('disabled', false);

            $("#botonguardar_reporte_introduccion")
                .html(
                    'Guardar introducción <i class="fa fa-save"></i>'
                );

        }

    });

});


/////////////// DEFINICIONES  //////////////


$("#form_informe_listadefiniciones").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);



    $.ajax({

        url: '/guardarDefinicionesInformeErgo',

        type: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_definiciones")
                .prop('disabled', true);

            $("#botonguardar_reporte_definiciones")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        error: function(xhr)
        {

            console.log(xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar'
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_definiciones")
                .prop('disabled', false);

            $("#botonguardar_reporte_definiciones")
                .html(
                    'Guardar definiciones <i class="fa fa-save"></i>'
                );

        }

    });

});


function cargarDefinicionesInformeErgo()
{

    $.get(
        '/obtenerDefinicionesInformeErgo/' + recsensorial,

        function(response)
        {

           
            $('input[name="DEFINICONES_INFORME[]"]')
                .prop('checked', false);


            response.forEach(function(item)
            {

                $('input[name="DEFINICONES_INFORME[]"][value="' +
                    item.CATALOGO_DEFINICIONES_ID +
                    '"]')

                    .prop('checked', true);

            });

        }
    );

}


/////////////// OBJETIVOS GENERALES Y ESPECIFICOS   //////////////



$("#form_informe_objetivogeneral").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);



    $.ajax({

        url: '/guardarObjetivoGeneralRecoErgo',

        type: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_objetivogeneral")
                .prop('disabled', true);

            $("#botonguardar_reporte_objetivogeneral")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_objetivogeneral")
                .prop('disabled', false);

            $("#botonguardar_reporte_objetivogeneral")
                .html(
                    'Guardar objetivo general <i class="fa fa-save"></i>'
                );

        }

    });

});



$("#form_informe_objetivoespecifico").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);



    $.ajax({

        url: '/guardarObjetivoEspecificoRecoErgo',

        type: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_objetivoespecifico")
                .prop('disabled', true);

            $("#botonguardar_reporte_objetivoespecifico")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_objetivoespecifico")
                .prop('disabled', false);

            $("#botonguardar_reporte_objetivoespecifico")
                .html(
                    'Guardar objetivos específicos <i class="fa fa-save"></i>'
                );

        }

    });

});


//////// RECONOCIMIENTO //////



$("#form_informe_ubicacion").on("submit", function(e)
{
    e.preventDefault();

    let formData = new FormData(this);

    formData.append('RECO_ID', recsensorial);



    $.ajax({

        url: '/guardarUbicacionRecoErgo',

        type: 'POST',

        data: formData,

        contentType: false,

        processData: false,

        cache: false,

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_ubicacion")
                .prop('disabled', true);

            $("#botonguardar_reporte_ubicacion")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_ubicacion")
                .prop('disabled', false);

            $("#botonguardar_reporte_ubicacion")
                .html(
                    'Guardar ubicación <i class="fa fa-save"></i>'
                );

        }

    });

});

///////////////  Descripción del proceso en la instalación////////////////



$("#form_reporte_procesoinstalacion").on(
    "submit",
    function(e)
{

    e.preventDefault();



    let formData = new FormData(this);



    formData.append(
        'RECO_ID',
        recsensorial
    );



    $.ajax({

        url: '/guardarProcesoInstalacionRecoErgo',

        type: 'POST',

        data: formData,

        cache: false,

        contentType: false,

        processData: false,

        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_procesoinstalacion")
                .prop('disabled', true);


            $("#botonguardar_reporte_procesoinstalacion")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_procesoinstalacion")
                .prop('disabled', false);


            $("#botonguardar_reporte_procesoinstalacion")
                .html(
                    'Guardar proceso instalación <i class="fa fa-save"></i>'
                );

        }

    });

	});


///////////  Población ocupacionalmente expuesta



function tablaReporteCategoriasErgo()
{

    try {

        let ruta =
            "/tablaReporteCategoriasErgo";



        if(tabla_reporte_categoria != null)
        {
            tabla_reporte_categoria.destroy();
        }



        tabla_reporte_categoria =
            $('#tabla_reporte_categoria').DataTable({

            ajax: {
                url: ruta,
                type: "get",
                cache: false,
                data: {
                    ergoid: recsensorial
                },
                error: function(xhr)
                {
                    console.log(xhr.responseText);
                }
            },



            columns: [

                

                {
                    data: "NOMBRE_CATEGORIA_ERGO",
                    defaultContent: "-"
                }

            ],



            lengthMenu: [
                [20, 50, 100, -1],
                [20, 50, 100, "Todos"]
            ],



            searching: false,
            paging: false,
            ordering: false,
            info: false,
            responsive: true,



            language: {
                emptyTable:
                    "No hay datos disponibles"
            }

        });

    }
    catch(exception) {

        console.error(exception);

    }

}



function tablaReporteAreasErgo()
{

    try {

        let ruta =
            "/tablaReporteAreasErgo";



        if(tabla_reporte_area != null)
        {
            tabla_reporte_area.destroy();
        }



		tabla_reporte_area =
			$('#tabla_reporte_area').DataTable({

			ajax: {
				url: ruta,
				type: "get",
				cache: false,
				data: {
					ergoid: recsensorial
				}
			},



			columns: [

				{
					data: "AREA"
				},

				{
					data: "CATEGORIA"
				}

			],



			rowsGroup: [0],

			order: [[0, "asc"]],

			ordering: true,

			paging: false,

			searching: false,

			info: false,

			responsive: true

});

    }
    catch(exception) {

        console.error(exception);

    }

}

//// CONCLUSIONES




$('#SELECT_CONCLUSION').on('change', function () {

    var selectedOption = $(this).find('option:selected');

    var conclusion = selectedOption.data('conclusion');

    $('#INFORME_CONCLUSION').val(conclusion);

});



$("#form_informe_conclusiones").on(
    "submit",
    function(e)
{

    e.preventDefault();



    let formData = new FormData(this);



    formData.append(
        'RECO_ID',
        recsensorial
    );



    $.ajax({

        url: '/guardarConclusionRecoErgo',

        type: 'POST',

        data: formData,

        cache: false,

        contentType: false,

        processData: false,

        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_conclusion")
                .prop('disabled', true);


            $("#botonguardar_reporte_conclusion")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_conclusion")
                .prop('disabled', false);


            $("#botonguardar_reporte_conclusion")
                .html(
                    'Guardar conclusión <i class="fa fa-save"></i>'
                );

        }

    });

	});

///// RECOMENDACIONES


$("#form_informe_listarecomendaciones").on(
    "submit",
    function(e)
{

    e.preventDefault();



    let formData = new FormData(this);



    formData.append(
        'RECO_ID',
        recsensorial
    );



    $.ajax({

        url:
            '/guardarRecomendacionesInformeErgo',

        type: 'POST',

        data: formData,

        cache: false,

        contentType: false,

        processData: false,



        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_recomendaciones")
                .prop('disabled', true);


            $("#botonguardar_reporte_recomendaciones")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_recomendaciones")
                .prop('disabled', false);


            $("#botonguardar_reporte_recomendaciones")
                .html(
                    'Guardar recomendaciones <i class="fa fa-save"></i>'
                );

        }

    });

	});



	function cargarRecomendacionesInformeErgo()
{

    //---------------------------------------
    // LIMPIAR TODOS
    //---------------------------------------

    $('input[name="DESCRIPCION_RECOMENDACIONES[]"]')
        .prop('checked', false);



    $.get(

        '/obtenerRecomendacionesInformeErgo/' +
        recsensorial,

        function(response)
        {

            //---------------------------------------
            // RECORRER DATOS
            //---------------------------------------

            response.forEach(function(dato)
            {

                $('input[name="DESCRIPCION_RECOMENDACIONES[]"][value="' +
                    dato.CATALOGO_RECOMENDACIONES_ID +
                    '"]')

                .prop('checked', true);

            });

        }

    );

}


////////  responsables




$("#form_informe_responsablesinforme").on(
    "submit",
    function(e)
{

    e.preventDefault();



    let formData = new FormData(this);



    formData.append(
        'RECO_ID',
        recsensorial
    );



    $.ajax({

        url:
            '/guardarResponsablesInformeRecoErgo',

        type: 'POST',

        data: formData,

        cache: false,

        contentType: false,

        processData: false,



        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')
        },



        beforeSend: function()
        {

            $("#botonguardar_reporte_responsablesinforme")
                .prop('disabled', true);


            $("#botonguardar_reporte_responsablesinforme")
                .html(
                    'Guardando... <i class="fa fa-spinner fa-spin"></i>'
                );

        },



        success: function(response)
        {

            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: response.msj,
                timer: 2000,
                showConfirmButton: false
            });

        },



        complete: function()
        {

            $("#botonguardar_reporte_responsablesinforme")
                .prop('disabled', false);


            $("#botonguardar_reporte_responsablesinforme")
                .html(
                    'Guardar responsables del informe <i class="fa fa-save"></i>'
                );

        }

    });

	});



/////// versiones
	


function tablaVersionesRecoErgo()
{

    try {

        let ruta =
            "/tablaVersionesRecoErgo/" +
            recsensorial;



        if(tabla_reporte_revisiones != null)
        {
            tabla_reporte_revisiones
                .destroy();
        }



        tabla_reporte_revisiones =
            $('#tabla_reporte_revisiones')
            .DataTable({

            ajax: {
                url: ruta,
                type: "get",
                cache: false
            },



            columns: [

                {
                    data: "NUMERO_REVISION"
                },

                {
                    data: "FECHA_FINALIZADO"
                },

                {
                    data: "FINALIZADO_NOMBRE"
                },

                {
                    data: "CHECKBOX_CANCELADO"
                },

                {
                    data: "CANCELADO_NOMBRE"
                },

                {
                    data: "ESTADO"
                },

                {
                    data: "BOTON_DESCARGAR"
                }

            ],



            ordering: true,

            processing: true,

            responsive: true,



            language: {

                emptyTable:
                    "No hay revisiones"

            }

        });

    }
    catch(exception) {

        console.error(exception);

    }

}

$("#boton_reporte_nuevarevision")
.on("click", function()
{

    $.ajax({

        url:
            '/crearRevisionRecoErgo',

        type: 'POST',

        data: {

            RECO_ID:
                recsensorial,

            _token:
                $('meta[name="csrf-token"]')
                .attr('content')

        },



        success: function(response)
        {

            Swal.fire({

                icon: 'success',

                title: 'Correcto',

                text: response.msj

            });



            tablaVersionesRecoErgo();
			validarEdicionRecoErgo()
        }

    });

});



function cancelarRevisionRecoErgo(
    ID_VERSION_RECO_ERGO,
    checkbox
) {

    //---------------------------------------
    // SI DESMARCA
    //---------------------------------------

    if(!checkbox.checked)
    {
        return;
    }



    Swal.fire({

        title: 'Cancelar revisión',

        input: 'textarea',

        inputLabel: 'Motivo de cancelación',

        inputPlaceholder:
            'Escriba el motivo...',

        inputAttributes: {
            'required': true
        },

        showCancelButton: true,

        confirmButtonText: 'Cancelar revisión',

        cancelButtonText: 'Cerrar'

    }).then((result) => {

        //---------------------------------------
        // SI CONFIRMA
        //---------------------------------------

        if(result.isConfirmed)
        {

            $.ajax({

                url:
                    '/cancelarRevisionRecoErgo',

                type: 'POST',

                data: {

                    ID_VERSION_RECO_ERGO:
                        ID_VERSION_RECO_ERGO,

                    MOTIVO_CANCELACION:
                        result.value,

                    _token:
                        $('meta[name="csrf-token"]')
                        .attr('content')

                },



                success: function(response)
                {

                    Swal.fire({

                        icon: 'success',

                        title: 'Correcto',

                        text: response.msj

                    });



                    //---------------------------------------
                    // RECARGAR TABLA
                    //---------------------------------------

                    tablaVersionesRecoErgo();



                    //---------------------------------------
                    // VALIDAR BLOQUEOS
                    //---------------------------------------

                    validarEdicionRecoErgo();

                },



                error: function(xhr)
                {

                    console.log(xhr.responseText);

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text:
                            'No se pudo cancelar'

                    });

                }

            });

        }
        else {

            //---------------------------------------
            // DESMARCAR CHECK
            //---------------------------------------

            checkbox.checked = false;

        }

    });

}






function validarEdicionRecoErgo()
{

    $.get(

        '/validarEdicionRecoErgo/' +
        recsensorial,

        function(response)
        {

            //---------------------------------------
            // TODOS LOS BOTONES
            //---------------------------------------

            let botones = `

                #botonguardar_reporte_introduccion,
                #botonguardar_reporte_objetivogeneral,
                #botonguardar_reporte_objetivoespecifico,
                #botonguardar_reporte_ubicacion,
                #botonguardar_reporte_procesoinstalacion,
                #botonguardar_reporte_conclusion,
                #botonguardar_reporte_definiciones,
                #botonguardar_reporte_recomendaciones,
                #botonguardar_reporte_responsablesinforme,
                #boton_reporte_nuevarevision

            `;



            //---------------------------------------
            // SI NO PERMITE GUARDAR
            //---------------------------------------

            if(response.permite_guardar == 0)
            {

                //---------------------------------------
                // DESACTIVAR BOTONES
                //---------------------------------------

                $(botones)
                    .prop('disabled', true);



                //---------------------------------------
                // INPUTS
                //---------------------------------------

               $('input, textarea, select')
				.not('.checkbox_cancelado_revision')
				.prop('disabled', true);


                //---------------------------------------
                // MENSAJE
                //---------------------------------------

                Swal.fire({

                    icon: 'warning',

                    title: 'Informe finalizado',

                    text:
                        'La revisión fue finalizada y ya no puede editarse'

                });

            }
            else
            {

                //---------------------------------------
                // ACTIVAR BOTONES
                //---------------------------------------

                $(botones)
                    .prop('disabled', false);



                //---------------------------------------
                // INPUTS
                //---------------------------------------

                $('input, textarea, select')
                    .prop('disabled', false);

            }



            //---------------------------------------
            // SI ESTA CANCELADO
            //---------------------------------------

            if(response.cancelado == 1)
            {

                Swal.fire({

                    icon: 'info',

                    title: 'Revisión cancelada',

                    text:
                        'La última revisión fue cancelada, puede continuar editando'

                });

            }

        }

    );

}


// function descargarRevisionRecoErgo(
//     RECO_ID
// ) {

//     window.open(

//         '/descargarRevisionRecoErgo/' +
//         RECO_ID,

//         '_blank'

//     );

// }




function descargarRevisionRecoErgo(
    RECO_ID
) {

    //---------------------------------------
    // ARREGLO GRAFICAS
    //---------------------------------------

    let graficas = [];



    //---------------------------------------
    // RECORRER GRAFICAS
    //---------------------------------------

    $('#contenedorGraficas > div').each(function () {

        let chartDiv =
            $(this).find('[id^="chart_"]')[0];



        if (chartDiv) {

            let instancia =
                echarts.getInstanceByDom(
                    chartDiv
                );



            if (instancia) {

                graficas.push({

                    imagen:
                        instancia.getDataURL({

                            type: 'png',

                            pixelRatio: 2,

                            backgroundColor: '#FFFFFF'

                        })

                });

            }

        }

    });




    //---------------------------------------
    // FORM TEMPORAL
    //---------------------------------------

    let form = $('<form>', {

        action:
            '/descargarRevisionRecoErgo/' +
            RECO_ID,

        method:
            'POST',

        target:
            '_blank'

    });




    //---------------------------------------
    // TOKEN
    //---------------------------------------

    form.append(

        $('<input>', {

            type: 'hidden',

            name: '_token',

            value:
                $('meta[name="csrf-token"]')
                .attr('content')

        })

    );




    //---------------------------------------
    // GRAFICAS
    //---------------------------------------

    form.append(

        $('<input>', {

            type: 'hidden',

            name: 'GRAFICAS',

            value:
                JSON.stringify(graficas)

        })

    );




    //---------------------------------------
    // ENVIAR
    //---------------------------------------

    $('body').append(form);

    form.submit();

    form.remove();

}




