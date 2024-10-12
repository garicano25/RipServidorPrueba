
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
    tabla_trabajadores_online();
    tabla_trabajadores_presencial();



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
	$("#tab_evidencias_ejecucion").css('display', 'none');
}


function muestra_menustab_principal()
{
	$("#tab_info_ejecucion").css('display', 'block');
	$("#tab_evidencias_ejecucion").css('display', 'block');
}
//===============================================================

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

	
	// Selecciona step form 1
	$( "#tab_info_ejecucion" ).click();
	

});
//===============================================================

function tabla_trabajadores_online()
{
	try 
	{
		if (datatable_trabajadores_online != null) {
			datatable_trabajadores_online.destroy();
		}
        datatable_trabajadores_online = $('#tabla_trabajadores_online').DataTable({
			"ajax": {
				"url": "/trabajadoresOnlineEjecucionPsico",
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
                    "data": "FECHAINICIO",
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
                    "data": "FECHAFIN",
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
                    "data": "ESTADOCORREO",
                    "defaultContent": "-"
                },
                {
                    "data": "ESTADOCUESTIONARIO",
                    "defaultContent": "-"
                },
                {
                    "orderable": false,
                    "data": 'boton_enviarCorreo',
                    "defaultContent": '-'
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
				"url": "/trabajadoresPresencialEjecucionPsico",
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
                    "defaultContent": "-"
                },
                {
                    "data": "ESTADOCUESTIONARIO",
                    "defaultContent": "-"
                },
                {
                    "orderable": false,
                    "data": 'boton_cargarPresencial',
                    "defaultContent": '-'
                }
			],
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

