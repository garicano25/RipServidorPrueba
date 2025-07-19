
//=================================================
// LOAD PAGINA

var opcion = 0
var ruta_storage_guardar = '/reportes';


$(document).ready(function()
{
	setTimeout(function()
	{
		tabla_matrizlab(proyecto.id);
	}, 100);
});



var datatable_matrizlab;

function tabla_matrizlab(proyecto_id) {
	try {
		var ruta = "/reportematrizlabtablageneral/" + proyecto_id;

		if ($.fn.DataTable.isDataTable('#tabla_matrizlab')) {
			$('#tabla_matrizlab').DataTable().clear().destroy();
		}

		datatable_matrizlab = $('#tabla_matrizlab').DataTable({
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
				{ data: "numero_visible", defaultContent: "-", orderable: false },
				{ data: "recsensorialarea_nombre", defaultContent: "-", orderable: false },
				{ data: "agente", defaultContent: "-", orderable: false },
				{ data: "recsensorialarea_numerotrabajadores", defaultContent: "-", orderable: false },
				{ data: "categoria", defaultContent: "-", orderable: false },
				{ data: "recsensorialarea_tiempoexposicion", defaultContent: "-", orderable: false },

				{
					data: null,
					orderable: false,
					render: function (data, type, row) {
						if (!row.mostrar_select) return '';
						let opciones = `<option value="">--</option>`;
						for (let i = 1; i <= 5; i++) {
							const selected = (i == row.recsensorialarea_indicepeligro) ? 'selected' : '';
							opciones += `<option value="${i}" ${selected}>${i}</option>`;
						}
						return `<select class="ip-select" data-id="${row.numero_registro}">${opciones}</select>`;
					}
				},

				{
					data: null,
					orderable: false,
					render: function (data, type, row) {
						if (!row.mostrar_select) return '';
						const letras = ['A', 'B', 'C', 'D', 'E'];
						let opciones = `<option value="">--</option>`;
						letras.forEach(letra => {
							const selected = (letra == row.recsensorialarea_indiceexposicion) ? 'selected' : '';
							opciones += `<option value="${letra}" ${selected}>${letra}</option>`;
						});
						return `<select class="ie-select" data-id="${row.numero_registro}">${opciones}</select>`;
					}
				},

				{
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="riesgo-resultado" data-id="${row.numero_registro}"></div>`;
                        }
                    },


				{ data: "recsensorialarea_lmpnmp", defaultContent: "-", orderable: false },
				{ data: "recsensorialarea_cumplimiento", defaultContent: "-", orderable: false },
				{ data: "recsensorialarea_medidas", defaultContent: "-", orderable: false }
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
			drawCallback: function () {
                    $('[data-toggle="tooltip"]').tooltip();

                    $('.riesgo-resultado').each(function () {
                        const id = $(this).data('id');
                        const ip = $(`.ip-select[data-id="${id}"]`).val();
                        const ie = $(`.ie-select[data-id="${id}"]`).val();

                        const resultado = calcularRiesgoPrioridad(ip, ie);
                        const colorTexto = resultado.color === 'red' ? 'white' : 'black';

                        $(this).html(resultado.texto);
                        $(this).css({
                            'background-color': resultado.color,
                            'color': colorTexto,
                            'padding': '4px',
                        });
                    });
                }

		});

		$(document).on('change', '.ip-select, .ie-select', function () {
			datatable_matrizlab.draw(false);
		});
	} catch (error) {
		console.error("Excepción en tabla_matrizlab:", error);
	}
}


$(document).on('change', '.ip-select, .ie-select', function () {
	let id = $(this).data('id');
	let valor = $(this).val();
	let tipo = $(this).hasClass('ip-select') ? 'IP' : 'IE';

	console.log(`Cambio en ${tipo} para fila ${id}: ${valor}`);
	datatable_matrizlab.draw(false);
});

function calcularRiesgoPrioridad(ip, ie) {
	if (!ip || !ie) return { texto: '-', color: 'white' };

	const clave = `${ip}${ie.toUpperCase()}`;
	const mapa = {
		'1A': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'1B': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'1C': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'1D': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'1E': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },

		'2A': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'2B': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'2C': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'2D': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },
		'2E': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },

		'3A': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'3B': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'3C': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },
		'3D': { texto: 'Muy alto / Primera prioridad', color: 'red' },
		'3E': { texto: 'Muy alto / Primera prioridad', color: 'red' },

		'4A': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'4B': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'4C': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },
		'4D': { texto: 'Muy alto / Primera prioridad', color: 'red' },
		'4E': { texto: 'Muy alto / Primera prioridad', color: 'red' },

		'5A': { texto: 'Bajo riesgo / Vigilancia para mejora continua', color: 'white' },
		'5B': { texto: 'Riesgo medio / Tercera prioridad', color: 'green' },
		'5C': { texto: 'Alto riesgo / Segunda prioridad', color: 'yellow' },
		'5D': { texto: 'Muy alto / Primera prioridad', color: 'red' },
		'5E': { texto: 'Muy alto / Primera prioridad', color: 'red' },
	};

	return mapa[clave] || { texto: '-', color: 'white' };
}




