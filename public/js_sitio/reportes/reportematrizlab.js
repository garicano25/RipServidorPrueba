
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
			createdRow: function (row, data, dataIndex) {
				$(row).addClass('fila-matrizlab').attr('data-numero-registro', data.numero_registro);
			},

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




$('#botonguardar_reporte_matriz').on('click', async function () {
    const data = $('#tabla_matrizlab').DataTable().rows().data().toArray();
    const filas = [];

    data.forEach(row => {
        const numero_registro = row.numero_registro;
        const tr = $(`.fila-matrizlab[data-numero-registro="${numero_registro}"]`);
        const medidas = [];

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
            recsensorialarea_medidas: medidas 
        };

        filas.push(fila);
    });

    if (!filas.length) {
        alertToast('No hay datos para guardar.', 'warning');
        return;
    }

    const confirmacion = await Swal.fire({
        title: '¿Desea guardar la matriz?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmacion.isConfirmed) {
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
            await Swal.fire({
                title: 'Éxito',
                text: 'Matriz guardada correctamente.',
                icon: 'success'
            });
        } else {
            await Swal.fire({
                title: 'Error',
                text: res.message || 'Error al guardar la matriz.',
                icon: 'error'
            });
        }
    } catch (err) {
        console.error(err);
        await Swal.fire({
            title: 'Error',
            text: 'Error de conexión al guardar matriz.',
            icon: 'error'
        });
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











$('#boton_reporte_matriz').on('click', function (e) {
    e.preventDefault();

    $.ajax({
        url: '/verificarmatrizlab/' + proyecto.id,
        method: 'GET',
        success: function (respuesta) {
            if (respuesta.success) {
                Swal.fire({
                    title: "¿Desea generar el reporte Excel?",
                    text: "Matriz Laboral por áreas de trabajo.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, descargar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#boton_reporte_matriz').prop('disabled', true);

                        Swal.fire({
                            title: "Generando reporte...",
                            text: "Espere un momento mientras se prepara el documento.",
                            icon: "info",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        let url = 'reporteexcelmatrizlab/' + proyecto.id;

                        $.ajax({
                            url: url,
                            method: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function (data, status, xhr) {
                                const contentType = xhr.getResponseHeader('Content-Type') || '';

                                if (contentType.includes('application/json') || contentType.includes('text/json')) {
                                    const reader = new FileReader();
                                    reader.onload = function () {
                                        try {
                                            const response = JSON.parse(reader.result);
                                            Swal.fire({
                                                title: "Atención",
                                                text: response.message || "No se puede generar el reporte.",
                                                icon: "warning"
                                            });
                                        } catch (e) {
                                            Swal.fire({
                                                title: "Error",
                                                text: "Respuesta inválida del servidor.",
                                                icon: "error"
                                            });
                                        }
                                        $('#boton_reporte_matriz').prop('disabled', false);
                                    };
                                    reader.readAsText(data);
                                    return;
                                }

                                const a = document.createElement('a');
                                const urlDescarga = window.URL.createObjectURL(data);
                                a.href = urlDescarga;
                                a.download = `Matriz_Laboral_${new Date().toISOString().slice(0, 10)}.xlsx`;
                                document.body.appendChild(a);
                                a.click();
                                a.remove();
                                window.URL.revokeObjectURL(urlDescarga);

                                Swal.fire({
                                    title: "Éxito",
                                    text: "El reporte fue generado correctamente.",
                                    icon: "success",
                                    confirmButtonText: "Cerrar"
                                });

                                $('#boton_reporte_matriz').prop('disabled', false);
                            },
                            error: function (xhr) {
                                const reader = new FileReader();
                                reader.onload = function () {
                                    try {
                                        const response = JSON.parse(reader.result);
                                        Swal.fire({
                                            title: "Atención",
                                            text: response.message || "Error desconocido del servidor.",
                                            icon: "warning"
                                        });
                                    } catch (e) {
                                        Swal.fire({
                                            title: "Error",
                                            text: "No se pudo interpretar la respuesta del servidor.",
                                            icon: "error"
                                        });
                                    }
                                    $('#boton_reporte_matriz').prop('disabled', false);
                                };

                                if (xhr.response) {
                                    reader.readAsText(xhr.response);
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: "No se recibió respuesta del servidor.",
                                        icon: "error"
                                    });
                                    $('#boton_reporte_matriz').prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Cancelado",
                            text: "No se generó el documento.",
                            icon: "info",
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        },
        error: function (xhr) {
            let mensaje = "No se pudo verificar la información.";

            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    mensaje = response.message;
                }
            } catch (e) {}

            Swal.fire({
                title: "Atención",
                text: mensaje,
                icon: "warning"
            });
        }
    });
});



