
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
				{ data: "reportequimicosevaluacion_ficha", defaultContent: "-", orderable: true },
                { data: "nombre_agente", defaultContent: "-", orderable: true },
				{ data: "recomendaciones", defaultContent: "-", orderable: true },
			],
			lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            rowsGroup: [1,2], 
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





                                           




$('#botonguardar_reporte_matriz').on('click', async function (e) {
    e.preventDefault();

    const departamento = $('#DEPARTAMENTO_MEL').val();
    const proyecto_id = proyecto.id; 

    if (!departamento) {
        await Swal.fire({
            title: 'Advertencia',
            text: 'Debe seleccionar un departamento antes de guardar.',
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    const confirmacion = await Swal.fire({
        title: '¿Desea guardar el departamento?',
        text: 'Se guardará la selección para este proyecto.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmacion.isConfirmed) return;

    try {
        const res = await $.ajax({
            url: '/guardarmeldraft',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                proyecto_id: proyecto_id,
                DEPARTAMENTO_MEL: departamento
            },
            beforeSend: function () {
                $('#botonguardar_reporte_matriz')
                    .prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
            }
        });

        if (res.success) {
            await Swal.fire({
                title: 'Éxito',
                text: res.message || 'Departamento guardado correctamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });

            if ($.fn.DataTable.isDataTable('#tabla_matrizreco')) {
                $('#tabla_matrizreco').DataTable().ajax.reload(null, false);
            }

        } else {
            await Swal.fire({
                title: 'Error',
                text: res.message || 'Error al guardar el departamento.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    } catch (err) {
        console.error(err);
        await Swal.fire({
            title: 'Error',
            text: 'Error de conexión al guardar el departamento.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    } finally {
        $('#botonguardar_reporte_matriz')
            .prop('disabled', false)
            .html('Guardar <i class="fa fa-save"></i>');
    }
});





$(document).on('click', '#btnExportarExcel', function (e) {
    e.preventDefault();

    $.ajax({
        url: '/verificarmeldraft/' + proyecto.id,
        method: 'GET',
        success: function (respuesta) {
            if (respuesta.success) {
                Swal.fire({
                    title: "¿Desea generar la Matriz de Exposición Laboral?",
                    text: "Se exportará el archivo Excel con los datos actuales del proyecto.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, descargar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#btnExportarExcel').prop('disabled', true);

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

                        let url = '/exportarMeldraft/' + proyecto.id;

                        $.ajax({
                            url: url,
                            method: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function (data, status, xhr) {
                                const contentType = xhr.getResponseHeader('Content-Type') || '';

                                // Si devuelve JSON (error), lo mostramos con SweetAlert
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
                                        $('#btnExportarExcel').prop('disabled', false);
                                    };
                                    reader.readAsText(data);
                                    return;
                                }

                                // ✅ Si todo va bien: descargar archivo directamente
                                const a = document.createElement('a');
                                const urlDescarga = window.URL.createObjectURL(data);
                                a.href = urlDescarga;
                                a.download = `Matriz de Exposición Laboral.xlsx`;
                                document.body.appendChild(a);
                                a.click();
                                a.remove();
                                window.URL.revokeObjectURL(urlDescarga);

                                Swal.fire({
                                    title: "Éxito",
                                    text: "El archivo 'Matriz de Exposición Laboral.xlsx' se descargó correctamente.",
                                    icon: "success",
                                    confirmButtonText: "Cerrar"
                                });

                                $('#btnExportarExcel').prop('disabled', false);
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
                                    $('#btnExportarExcel').prop('disabled', false);
                                };

                                if (xhr.response) {
                                    reader.readAsText(xhr.response);
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: "No se recibió respuesta del servidor.",
                                        icon: "error"
                                    });
                                    $('#btnExportarExcel').prop('disabled', false);
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
