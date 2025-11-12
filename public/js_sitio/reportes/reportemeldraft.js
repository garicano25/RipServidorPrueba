
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
	
	obtenerdatos()
	
	setTimeout(function () {
		tabla_mel_draft(proyecto.id, reporteregistro_id, areas_poe);
	}, 100);
});

var datatable_reporte_mel = null;

function tabla_mel_draft(proyecto_id, reporteregistro_id, areas_poe) {
	try {
		var ruta = "/tablameldraft/" + proyecto_id + "/" + reporteregistro_id + "/" + areas_poe;

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
				{ data: "reportequimicosevaluacion_nombre", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_ficha", defaultContent: "-", orderable: true },
                { data: "reportequimicoscategoria_nombre", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_anios", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_antiguedadgeneral", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_antiguedadcategoria", defaultContent: "-", orderable: false },
				{ data: "reportequimicosevaluacion_horariotrabajo", defaultContent: "-", orderable: false },
				{ data: "tipo", defaultContent: "-", orderable: false },
				{ data: "referencia_vle", defaultContent: "-", orderable: false },
				{ data: "resultado_concentracion", defaultContent: "-", orderable: false },
				{ data: "cumplimiento", defaultContent: "-", orderable: false }

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
		console.error("Excepción en tabla_mel_draft:", error);
	}
}



function obtenerdatos() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/obtenermeldraft/" + proyecto.id,
        cache: false,
        success: function (dato) {
            $("#DEPARTAMENTO_MEL").html(dato.opciones);

            if (dato.departamento_guardado) {
                $("#DEPARTAMENTO_MEL").val(dato.departamento_guardado);
            } else {
                $("#DEPARTAMENTO_MEL").val("");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error: " + error);
            swal("Error", "No se pudieron obtener los datos del informe", "error");
        }
    });
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

            // 🔄 Solo recarga la tabla ya existente
            if ($.fn.DataTable.isDataTable('#tabla_mel_draft')) {
                $('#tabla_mel_draft').DataTable().ajax.reload(null, false);
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


