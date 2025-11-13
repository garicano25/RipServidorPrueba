
//=================================================
// LOAD PAGINA

var opcion = 0
var ruta_storage_guardar = '/reportes';

//=================================================
// TABLA MEL DRAFT
//=================================================

var reporteregistro_id = 0; 
var areas_poe = 1;

$(document).ready(function () {
	
	
	setTimeout(function () {
		tabla_matrizreco(proyecto.id, reporteregistro_id, areas_poe);
	}, 100);
});

var datatable_reporte_melreco = null;

function tabla_matrizreco(proyecto_id, reporteregistro_id, areas_poe) {
	try {
		var ruta = "/matrizrecomendaciones/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		if ($.fn.DataTable.isDataTable('#tabla_matrizreco')) {
			$('#tabla_matrizreco').DataTable().clear().destroy();
		}

		datatable_reporte_melreco = $('#tabla_matrizreco').DataTable({
			ajax: {
				url: ruta,
				type: "get",
				cache: false,
				dataType: "json",
				dataSrc: function (json) {
					if (!json || !json.data) {
						console.warn("No se recibieron datos válidos desde el backend.");
						return [];
					}
					return json.data;
				},
				error: function (xhr, error, thrown) {
					console.error("Error al cargar DataTable:", error, thrown, xhr.responseText);
				}
			},
			columns: [
				{ data: "numero_registro", defaultContent: "-", orderable: false },
				{ data: "DEPARTAMENTO_MEL", defaultContent: "-", orderable: false },
				{ data: "reportequimicosarea_instalacion", defaultContent: "-", orderable: false },
				{ data: "reportequimicosarea_nombre", defaultContent: "-", orderable: true },
				{ data: "reportequimicoscategoria_nombre", defaultContent: "-", orderable: false },
                { data: "nombre_agente", defaultContent: "-", orderable: true },
				{ data: "recomendaciones", defaultContent: "-", orderable: true },
			],
			lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            rowsGroup: [1,2,3], 
            order: [[ 0, "ASC" ]],
			ordering: false,
			processing: true,
			searching: true,
			paging: true,
			lengthMenu: [[30, 50, 100, -1], [30, 50, 100, "Todos"]],
			language: {
				lengthMenu: "Mostrar _MENU_ Registros",
				zeroRecords: "No se encontraron registros",
				info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
				infoEmpty: "No se encontraron registros",
				infoFiltered: "(Filtrado de _MAX_ registros)",
				emptyTable: "No hay datos disponibles en la tabla",
				loadingRecords: "Cargando datos...",
				processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
				search: "Buscar",
				paginate: {
					first: "Primera",
					last: "Última",
					next: "Siguiente",
					previous: "Anterior"
				}
			},
			drawCallback: function () {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
	} 
	catch (error) {
		console.error("Excepción en tabla_matrizreco:", error);
	}
}





                                           




$('#btn_guardar_recomendaciones').on('click', function() {
    const dataGuardar = [];

    $('#tabla_matrizreco tbody tr').each(function() {
        const row = datatable_reporte_melreco.row(this).data();
        const area_id = row.area_id;           
        const categoria_id = row.categoria_id;

        const recomendaciones = [];
        $(this).find('.recomendacion_checkbox').each(function() {
            recomendaciones.push({
                id: $(this).data('id'),
                seleccionado: $(this).is(':checked')
            });
        });

        dataGuardar.push({
            area_id,
            categoria_id,
            recomendaciones
        });
    });

    $.ajax({
        url: '/guardarMatrizRecomendaciones',
        type: 'POST',
        data: {
            proyecto_id: proyecto.id,
            reporteregistro_id: reporteregistro_id,
            data: dataGuardar
        },
        success: function(res) {
            if (res.success) {
                Swal.fire('Éxito', res.mensaje, 'success');
            } else {
                Swal.fire('Error', res.mensaje, 'error');
            }
        },
        error: function(err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo guardar las recomendaciones', 'error');
        }
    });
});
