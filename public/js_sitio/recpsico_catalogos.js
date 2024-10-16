var datatable_guia3 = null;
var datatable_guia2 = null;
var datatable_guia1 = null;
var datatable_guia = null;

var catalogo = 0;

$(document).ready(function () {
    mostrar_catalogo(1);

    $('[data-toggle="tooltip"]').tooltip();
});
//------------------------------------------------------BOTONES----------------------------//
$("#boton_guardar_guia").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_catalogo_guia').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_catalogo_guia(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_guia').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#boton_guardar_guia').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_guia').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_guia').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});

//------------------------------------------------------FUNCIONES--------------------------//
function mostrar_catalogo(num_catalogo) {
    catalogo = parseInt(num_catalogo);

    // Desactivar ACTIVE menu catalogos
    $.each($('#tabla_lista_catalogos tr'), function (key, value) {
        $(this).removeClass('active');
        $("#cat_" + (key + 1)).removeClass("text-info");
        $("#cat_" + (key + 1)).addClass("text-secondary");


    });

    switch (catalogo) {
        case 1:

            // activa menu
            $("#titulo_tabla").html('Catálogo de GUIA DE REFERENCIA I: Acontecimientos Traumáticos Severos');
            $("#modal_titulo").html("Catálogo de GUIA DE REFERENCIA I");
            $("#tr_1").addClass("active");
            $("#cat_1").addClass("text-info");

            // Inicializar tabla
            if (datatable_guia != null) {
                datatable_guia.destroy();
                datatable_guia = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_guia" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Pregunta</th>' +
                '<th>Explicación</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_guia(catalogo);
        break;
        case 2:

            // activa menu
            $("#titulo_tabla").html('Catálogo de GUIA DE REFERENCIA II: Factores de Riesgo Psicosocial');
            $("#modal_titulo").html("Catálogo de GUIA DE REFERENCIA II");
            $("#tr_2").addClass("active");
            $("#cat_2").addClass("text-info");

            // Inicializar tabla
            if (datatable_guia != null) {
                datatable_guia.destroy();
                datatable_guia = null;
            }


            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_guia" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Pregunta</th>' +
                '<th>Explicación</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_guia(catalogo);
        break;
        case 3:

            // activa menu
            $("#titulo_tabla").html('Catálogo de GUIA DE REFERENCIA III: Evaluación del Entorno Organizacional');
            $("#modal_titulo").html("Catálogo de GUIA DE REFERENCIA III");
            $("#tr_3").addClass("active");
            $("#cat_3").addClass("text-info");

            // Inicializar tabla
            if (datatable_guia != null) {
                datatable_guia.destroy();
                datatable_guia = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_guia" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Pregunta</th>' +
                '<th>Explicación</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');


            tabla_catalogo_guia(catalogo);
        break;
    }
}
function editar_registro_guia() {
    $('#tabla_lista_catalogo_guia tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_guia.row(tr);
        console.log(row.data());
        $('#form_catalogo_guia').each(function () {
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_GUIAPREGUNTA").val(row.data().ID_GUIAPREGUNTA || '');
        $("#TIPOGUIA").val(row.data().TIPOGUIA || '');
        $("#PREGUNTA_NOMBRE").val(row.data().PREGUNTA_NOMBRE || '');
        $("#PREGUNTA_ID").val(row.data().PREGUNTA_ID || '');
        $("#PREGUNTA_EXPLICACION").val(row.data().PREGUNTA_EXPLICACION || '');

        $("#catalogo").val(row.data().TIPOGUIA);

        // abrir modal
        $('#modal_guia').modal({ backdrop: false });
    });
}
//--------------------------------------------------------TABLAS----------------------------//
function tabla_catalogo_guia(num_catalogo) {
    var ruta = "/recpsicocatalogosguia/" + num_catalogo;

    try {
        if (datatable_guia != null) {
            datatable_guia.clear().draw();
            datatable_guia.ajax.url(ruta).load();
        }
        else {
            datatable_guia = $('#tabla_lista_catalogo_guia').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_guia(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "COUNT",
                        "defaultContent": '-'
                    },
                    {
                        "data": "PREGUNTA_NOMBRE",
                        "defaultContent": '-'
                    },
                    {
                        "data": "PREGUNTA_EXPLICACION",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "asc"]],
                "rowsGroup": [0],
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception) {
        // alert("error en el ajax");
        tabla_catalogo_guia(num_catalogo);
    }
}