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