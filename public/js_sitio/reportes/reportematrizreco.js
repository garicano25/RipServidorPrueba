
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





                                           



$('#btn_guardar_recomendaciones').on('click', async function (e) {
    e.preventDefault();

    const proyecto_id = proyecto.id; 
    const dataGuardar = [];

    // 🔹 Recolectar las recomendaciones desde la tabla
    $('#tabla_matrizreco tbody tr').each(function () {
        const row = datatable_reporte_melreco.row(this).data();
        if (!row) return;

        const area_id = row.area_id;
        const categoria_id = row.categoria_id;

        const recomendaciones = [];
        $(this).find('.recomendacion_checkbox').each(function () {
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

    // Validar datos
    if (dataGuardar.length === 0) {
        await Swal.fire({
            title: 'Advertencia',
            text: 'No hay recomendaciones seleccionadas para guardar.',
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Confirmación con SweetAlert2
    const confirmacion = await Swal.fire({
        title: '¿Desea guardar las recomendaciones?',
        text: 'Se almacenarán las selecciones realizadas en cada área y categoría.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const res = await $.ajax({
            url: '/guardarMatrizRecomendaciones',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                proyecto_id: proyecto_id,
                data: dataGuardar
            },
            beforeSend: function () {
                $('#btn_guardar_recomendaciones')
                    .prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            }
        });

        if (res.success) {
            await Swal.fire({
                title: 'Éxito',
                text: res.mensaje || 'Recomendaciones guardadas correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });

            if ($.fn.DataTable.isDataTable('#tabla_matrizreco')) {
                $('#tabla_matrizreco').DataTable().ajax.reload(null, false);
            }

        } else {
            await Swal.fire({
                title: 'Error',
                text: res.mensaje || 'Ocurrió un problema al guardar las recomendaciones.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }

    } catch (err) {
        console.error(err);
        await Swal.fire({
            title: 'Error',
            text: 'Error de conexión al guardar las recomendaciones.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    } finally {
        $('#btn_guardar_recomendaciones')
            .prop('disabled', false)
            .html('Guardar <i class="fa fa-save"></i>');
    }
});




$(document).on('click', '#btnexportarmelrecomendaciones', function (e) {
    e.preventDefault();

    $.ajax({
        url: '/verificarmatrizrecomendaciones/' + proyecto.id,
        method: 'GET',
        success: function (respuesta) {
            if (respuesta.success) {
                Swal.fire({
                    title: "¿Desea generar la Matriz de Recomendaciones?",
                    text: "Se exportará el archivo Excel con los datos actuales.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, descargar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $('#btnexportarmelrecomendaciones').prop('disabled', true);

                    Swal.fire({
                        title: "Generando reporte...",
                        text: "Espere un momento mientras se prepara el documento.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const url = '/exportarMatrizRecomendaciones/' + proyecto.id + '/' + reporteregistro_id;

                    $.ajax({
                        url: url,
                        method: 'GET',
                        xhrFields: { responseType: 'blob' },
                        success: function (data, status, xhr) {
                            const contentType = xhr.getResponseHeader('Content-Type') || '';

                            if (contentType.includes('application/json')) {
                                const reader = new FileReader();
                                reader.onload = function () {
                                    try {
                                        const response = JSON.parse(reader.result);
                                        Swal.fire({
                                            title: "Atención",
                                            text: response.message || "No se puede generar el reporte.",
                                            icon: "warning"
                                        });
                                    } catch {
                                        Swal.fire({
                                            title: "Error",
                                            text: "Respuesta inválida del servidor.",
                                            icon: "error"
                                        });
                                    }
                                    $('#btnexportarmelrecomendaciones').prop('disabled', false);
                                };
                                reader.readAsText(data);
                                return;
                            }

                            let nombreArchivo = "Matriz_Recomendaciones.xlsx";
                            const disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('filename=') !== -1) {
                                nombreArchivo = disposition
                                    .split('filename=')[1]
                                    .replace(/['"]/g, '')
                                    .trim();
                            }

                            const a = document.createElement('a');
                            const urlDescarga = window.URL.createObjectURL(data);
                            a.href = urlDescarga;
                            a.download = nombreArchivo;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(urlDescarga);

                            Swal.fire({
                                title: "Éxito",
                                text: `El archivo '${nombreArchivo}' se descargó correctamente.`,
                                icon: "success",
                                confirmButtonText: "Cerrar"
                            });

                            $('#btnexportarmelrecomendaciones').prop('disabled', false);
                        },
                        error: function () {
                            Swal.fire({
                                title: "Error",
                                text: "No se pudo generar el reporte.",
                                icon: "error"
                            });
                            $('#btnexportarmelrecomendaciones').prop('disabled', false);
                        }
                    });
                });
            } else {
                Swal.fire({
                    title: "Atención",
                    text: respuesta.message || "No se puede verificar la matriz.",
                    icon: "warning"
                });
            }
        },
        error: function () {
            Swal.fire({
                title: "Error",
                text: "No se pudo verificar la matriz.",
                icon: "error"
            });
        }
    });
});
