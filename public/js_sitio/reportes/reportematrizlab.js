
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
		const ruta = "/reportematrizlabtablageneral/" + proyecto_id;

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

						return `
							<select class="form-select form-select-sm ip-select" 
									data-id="${row.numero_registro}" 
									style="min-width: 100px;">
								${opciones}
							</select>
						`;
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

						return `
							<select class="form-select form-select-sm ie-select" 
									data-id="${row.numero_registro}" 
									style="min-width: 100px;">
								${opciones}
							</select>
						`;
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
		{
				data: null,
				orderable: false,
				render: function (data, type, row) {
						const fila_id = row.numero_registro;
						const recomendaciones = row.recsensorialarea_medidas_array || [];

						if (!recomendaciones.length) return 'N/A';

						let html = `<div class="contenedor-recomendaciones" data-recomendaciones="${fila_id}">`;
						recomendaciones.forEach((r, i) => {
							const isChecked = r.seleccionado === true || r.seleccionado === 'true' ? 'checked' : '';
							html += `
								<div class="recomendacion-bloque mb-2">
									<div class="switch">
										<label>
											<input type="checkbox" class="recomendacion_checkbox" ${isChecked}>
											<span class="lever switch-col-light-blue"></span>
										</label>
									</div>
									<textarea class="form-control" rows="5" readonly>${r.descripcion}</textarea>
								</div>
							`;
						});
						html += `</div>`;
						return html;
					}

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

				$('.contenedor-recomendaciones').each(function () {
					const contenedor = $(this);
					contenedor.find('.recomendacion_checkbox').each(function () {

					});
					});
			}

		});

		// 👇 Aquí solo se actualiza el resultado, NO se redibuja ni recarga la tabla
		$(document).on('change', '.ip-select, .ie-select', function () {
			const id = $(this).data('id');
			const ip = $(`.ip-select[data-id="${id}"]`).val();
			const ie = $(`.ie-select[data-id="${id}"]`).val();

			const resultado = calcularRiesgoPrioridad(ip, ie);
			const colorTexto = resultado.color === 'red' ? 'white' : 'black';

			$(`.riesgo-resultado[data-id="${id}"]`).html(resultado.texto).css({
				'background-color': resultado.color,
				'color': colorTexto,
				'padding': '4px',
			});
		});
	} catch (error) {
		console.error("Excepción en tabla_matrizlab:", error);
	}
}


// $('#botonguardar_reporte_matriz').on('click', async function () {
//     const filas = [];

//     $('.contenedor-recomendaciones').each(function () {
//         const container = $(this);
//         const numero_registro = container.data('recomendaciones');

//         const medidas = [];

//         container.find('.recomendacion-bloque').each(function () {
//             const bloque = $(this);
//             const descripcion = bloque.find('textarea').val()?.trim() || '';
//             const checkbox = bloque.find('input[type="checkbox"]');
//             const seleccionado = checkbox.is(':checked');

//             medidas.push({ descripcion, seleccionado });
//         });

//         // Buscar la fila original desde DataTable
//         const row = $('#tabla_matrizlab').DataTable().rows().data().toArray().find(r => r.numero_registro == numero_registro);
//         if (!row) return;

//         const tr = $(`[data-recomendaciones="${numero_registro}"]`).closest('tr');

//         const fila = {
//             numero_registro,
//             area_id: row.recsensorialarea_nombre.match(/\(ID: (\d+)\)/)?.[1] || 0,
//             agente: row.agente,
//             categoria: row.categoria,
//             recsensorialarea_numerotrabajadores: row.recsensorialarea_numerotrabajadores,
//             recsensorialarea_tiempoexposicion: row.recsensorialarea_tiempoexposicion,
//             recsensorialarea_indicepeligro: tr.find('.ip-select').val(),
//             recsensorialarea_indiceexposicion: tr.find('.ie-select').val(),
//             recsensorialarea_riesgo: tr.find('.riesgo-resultado').text(),
//             recsensorialarea_lmpnmp: row.recsensorialarea_lmpnmp,
//             recsensorialarea_cumplimiento: row.recsensorialarea_cumplimiento,
//             recsensorialarea_medidas: medidas // array vacío si no hay
//         };

//         filas.push(fila);
//     });

//     if (!filas.length) {
//         alertToast('No hay datos para guardar.', 'warning');
//         return;
//     }

//     try {
//         const res = await $.ajax({
//             url: '/reportematrizlabguardar',
//             method: 'POST',
//             data: {
//                 proyecto_id: proyecto.id,
//                 filas,
//                 _token: $('meta[name="csrf-token"]').attr('content')
//             }
//         });

//         if (res.success) {
//             alertToast('Matriz guardada correctamente.', 'success');
//         } else {
//             alertToast(res.message || 'Error al guardar.', 'error');
//         }
//     } catch (err) {
//         console.error(err);
//         alertToast('Error de conexión al guardar matriz.', 'error');
//     }
// });



$('#botonguardar_reporte_matriz').on('click', async function () {
    const data = $('#tabla_matrizlab').DataTable().rows().data().toArray();
    const filas = [];

    data.forEach(row => {
        const numero_registro = row.numero_registro;
        const tr = $(`[data-recomendaciones="${numero_registro}"]`).closest('tr');

        const medidas = [];

        // Si hay contenedor de medidas en el DOM, procesarlas
        const container = $(`.contenedor-recomendaciones[data-recomendaciones="${numero_registro}"]`);
        if (container.length) {
            container.find('.recomendacion-bloque').each(function () {
                const bloque = $(this);
                const descripcion = bloque.find('textarea').val()?.trim() || '';
                const seleccionado = bloque.find('input[type="checkbox"]').is(':checked');

                medidas.push({ descripcion, seleccionado });
            });
        }

        const fila = {
            numero_registro,
            area_id: row.recsensorialarea_nombre.match(/\(ID: (\d+)\)/)?.[1] || 0,
            agente: row.agente,
            categoria: row.categoria,
            recsensorialarea_numerotrabajadores: row.recsensorialarea_numerotrabajadores,
            recsensorialarea_tiempoexposicion: row.recsensorialarea_tiempoexposicion,
            recsensorialarea_indicepeligro: tr.find('.ip-select').val() ?? '',
            recsensorialarea_indiceexposicion: tr.find('.ie-select').val() ?? '',
            recsensorialarea_riesgo: tr.find('.riesgo-resultado').text() ?? '',
            recsensorialarea_lmpnmp: row.recsensorialarea_lmpnmp,
            recsensorialarea_cumplimiento: row.recsensorialarea_cumplimiento,
            recsensorialarea_medidas: medidas // array vacío si no hay medidas
        };

        filas.push(fila);
    });

    if (!filas.length) {
        alertToast('No hay datos para guardar.', 'warning');
        return;
    }

    try {
        const res = await $.ajax({
            url: '/reportematrizlabguardar',
            method: 'POST',
            data: {
                proyecto_id: proyecto.id,
                filas,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (res.success) {
            alertToast('Matriz guardada correctamente.', 'success');
        } else {
            alertToast(res.message || 'Error al guardar.', 'error');
        }
    } catch (err) {
        console.error(err);
        alertToast('Error de conexión al guardar matriz.', 'error');
    }
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








