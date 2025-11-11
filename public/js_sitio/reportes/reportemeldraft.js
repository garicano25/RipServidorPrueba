
//=================================================
// LOAD PAGINA

var opcion = 0
var ruta_storage_guardar = '/reportes';

var reporteregistro_id = 0;
var areas_poe = 1

$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_mel_draft(proyecto.id, reporteregistro_id);
	}, 100);
});



var datatable_reporte_mel = null;



function tabla_mel_draft(proyecto_id, reporteregistro_id)
{
	try {
		var ruta = "/tablameldraft/"+proyecto_id+"/"+reporteregistro_id+"/"+areas_poe;

		if ($.fn.DataTable.isDataTable('#tabla_mel_draft')) {
			$('#tabla_mel_draft').DataTable().clear().destroy();
		}

		datatable_reporte_mel = $('#tabla_mel_draft').DataTable({
			ajax: {
				url: ruta,
				type: "get",
				cache: false,
				dataType: "json",
				dataSrc: function (json) {
					return json.data;
				},
				error: function (xhr, error, thrown) {
					console.error("Error al cargar DataTable:", error);
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

			order: [[0, "asc"]],
			rowsGroup: [0, 1],
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
		});
	} catch (error) {
		console.error("Excepción en tabla_mel_draft:", error);
	}
}





                                           


