
//=================================================
// LOAD PAGINA

var opcion = 0
var ruta_storage_guardar = '/reportes';

//=================================================
// TABLA MEL DRAFT
//=================================================

var reporteregistro_id = 0; 
var areas_poe = 1;

$(document).ready(function() {
	setTimeout(function() {
		tabla_mel_draft(proyecto.id, reporteregistro_id, areas_poe);
	}, 100);
});

var datatable_reporte_mel = null;

function tabla_mel_draft(proyecto_id, reporteregistro_id, areas_poe) {
	try {
		// Construir la ruta dinámica
		var ruta = "/tablameldraft/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

		// Si la tabla ya existe, destruirla antes de volver a crearla
		if ($.fn.DataTable.isDataTable('#tabla_mel_draft')) {
			$('#tabla_mel_draft').DataTable().clear().destroy();
		}

		// Inicializar DataTable
		datatable_reporte_mel = $('#tabla_mel_draft').DataTable({
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
				{ data: "reportequimicosarea_instalacion", defaultContent: "-", orderable: false },
				{ data: "reportequimicosarea_nombre", defaultContent: "-", orderable: true },
				{ data: "reportequimicosevaluacion_nombre", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_ficha", defaultContent: "-", orderable: true },
                { data: "reportequimicoscategoria_nombre", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_anios", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_antiguedadgeneral", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_antiguedadcategoria", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_horariotrabajo", defaultContent: "-", orderable: false },
				{ data: "parametros", defaultContent: "-", orderable: false }
			],
			lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            rowsGroup: [1], 
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
		console.error("Excepción en tabla_mel_draft:", error);
	}
}





                                           


