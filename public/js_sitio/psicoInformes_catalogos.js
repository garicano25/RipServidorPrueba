var datatable_cargos = null;
var datatable_introducciones = null;
var datatable_definiciones = null;
var datatable_recomendaciones = null;
var datatable_conclusiones = null;
var datatable_recomendaciones_control = null;

var catalogo = 0;

$(document).ready(function () {
    mostrar_catalogo(1);

    $('[data-toggle="tooltip"]').tooltip();

    document.getElementById('DOMINIO').addEventListener('change', actualizarOpcionesNivel);
    document.getElementById('CATEGORIA').addEventListener('change', actualizarOpcionesNivelCategorias);
   
});

//------------------------------------------------------BOTONES--------------------------//
$("#boton_nuevo_registro").click(function () {
    switch (catalogo) {
        case 1:
            // Borrar formulario
            $('#form_cargo').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_CARGO_INFORME").val(0);

            // abrir modal
            $('#modal_cargo').modal({ backdrop: false });
            break;
        case 2:
            // Borrar formulario
            $('#form_definicion').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_DEFINICION_INFORME").val(0);

            // abrir modal
            $('#modal_definicion').modal({ backdrop: false });
            break;
        case 3:
            // Borrar formulario
            $('#form_recomendacion').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_RECOMENDACION_INFORME").val(0);
            actualizarOpcionesNivelCategorias();
            // abrir modal
            $('#modal_recomendacion').modal({ backdrop: false });
            break;
        case 4:
            // Borrar formulario
            $('#form_conclusion').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_CONCLUSION_INFORME").val(0);
            actualizarOpcionesNivel();
            // abrir modal
            $('#modal_conclusion').modal({ backdrop: false });
            break;
        case 5:
            // Borrar formulario
            $('#form_recomendaciones_control').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_RECOMENDACION_CONTROL_INFORME").val(0);

            // abrir modal
            $('#modal_recomendaciones_control').modal({ backdrop: false });
            break;
        default:
            // Borrar formulario
            $('#form_catalogo').each(function () {
                this.reset();
            });
            
            // campos hidden
            $("#id").val(0);
            $("#catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo').modal({ backdrop: false });
            break;
    }
});

$("#boton_guardar_cargo").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_cargo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogosrec',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualizar tabla
                tabla_catalogo_cargos(catalogo);

                // mensaje
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
                $('#boton_guardar_cargo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_cargo').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_cargo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_cargo').html('Guardar <i class="fa fa-save"></i>');
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

$("#boton_guardar_definicion").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_definicion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogosrec',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualizar tabla
                tabla_catalogo_definiciones(catalogo);

                // mensaje
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
                $('#boton_guardar_definicion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_definicion').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_definicion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_definicion').html('Guardar <i class="fa fa-save"></i>');
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

$("#boton_guardar_recomendacion").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_recomendacion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogosrec',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualizar tabla
                tabla_catalogo_recomendaciones(catalogo);

                // mensaje
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
                $('#boton_guardar_recomendacion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_recomendacion').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_recomendacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_recomendacion').html('Guardar <i class="fa fa-save"></i>');
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

$("#boton_guardar_conclusion").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_conclusion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogosrec',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualizar tabla
                tabla_catalogo_conclusiones(catalogo);

                // mensaje
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
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_conclusion').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_conclusion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');
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

$("#boton_guardar_recomendacion_control").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // enviar datos
        $('#form_recomendaciones_control').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recpsicocatalogosrec',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualizar tabla
                tabla_catalogo_recomendaciones_control(catalogo);

                // mensaje
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
                $('#boton_guardar_recomendacion_control').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_recomendaciones_control').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_recomendacion_control').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_recomendacion_control').html('Guardar <i class="fa fa-save"></i>');
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
function actualizarOpcionesNivel() {
    const dominioSelect = document.getElementById('DOMINIO');
    const nivelSelect = document.getElementById('NIVEL');
    const opciones = {
        1: [
            { value: "6", text: "Acontecimiento traumático severo" },
            { value: "7", text: "No existe acontecimiento traumático severo" },
        ],
        default: [
            { value: "1", text: "Riesgo muy alto" },
            { value: "2", text: "Riesgo alto" },
            { value: "3", text: "Riesgo medio" },
            { value: "4", text: "Riesgo bajo" },
            { value: "5", text: "Riesgo nulo" },
        ]
    };

    const selectedValue = dominioSelect.value;
    nivelSelect.innerHTML = '';

    const opcionesNuevas = opciones[selectedValue] || opciones.default;
    opcionesNuevas.forEach(opcion => {
        const opt = document.createElement('option');
        opt.value = opcion.value;
        opt.textContent = opcion.text;
        nivelSelect.appendChild(opt);
    });
}

function actualizarOpcionesNivelCategorias() {
    const categoriaSelect = document.getElementById('CATEGORIA');
    const nivelSelect = document.getElementById('NIVELRIESGO');
    const opciones = {
        1: [
            { value: "6", text: "Hay casos" },
            { value: "7", text: "Sin casos" },
        ],
        default: [
            { value: "1", text: "Riesgo muy alto" },
            { value: "2", text: "Riesgo alto" },
            { value: "3", text: "Riesgo medio" },
            { value: "4", text: "Riesgo bajo" },
            { value: "5", text: "Riesgo nulo" },
        ]
    };

    const selectedValue = categoriaSelect.value;
    nivelSelect.innerHTML = '';

    const opcionesNuevas = opciones[selectedValue] || opciones.default;
    opcionesNuevas.forEach(opcion => {
        const opt = document.createElement('option');
        opt.value = opcion.value;
        opt.textContent = opcion.text;
        nivelSelect.appendChild(opt);
    });
}

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
            $("#titulo_tabla").html('Catálogo de cargos para informes');
            $("#modal_titulo").html("Catálogo de cargos para informes");
            $("#tr_1").addClass("active");
            $("#cat_1").addClass("text-info");

            // Inicializar tabla
            if (datatable_cargos != null) {
                datatable_cargos.destroy();
                datatable_cargos = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_cargos" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Cargo</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '<th style="width: 90px!important;">Activo</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_cargos(catalogo);
            break;
        case 2:

            // activa menu
            $("#titulo_tabla").html('Catálogo de definiciones para informes');
            $("#modal_titulo").html("Catálogo de definiciones para informes");
            $("#tr_2").addClass("active");
            $("#cat_2").addClass("text-info");

            // Inicializar tabla
            if (datatable_definiciones != null) {
                datatable_definiciones.destroy();
                datatable_definiciones = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_definiciones" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Concepto</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '<th style="width: 90px!important;">Activo</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_definiciones(catalogo);
            break;
        case 3:

            // activa menu
            $("#titulo_tabla").html('Catálogo de recomendaciones para informes');
            $("#modal_titulo").html("Catálogo de recomendaciones para informes");
            $("#tr_3").addClass("active");
            $("#cat_3").addClass("text-info");

            // Inicializar tabla
            if (datatable_recomendaciones != null) {
                datatable_recomendaciones.destroy();
                datatable_recomendaciones = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_recomendaciones" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>Categoria</th>' +
                '<th>Nivel de riesgo</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '<th style="width: 90px!important;">Activo</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

                tabla_catalogo_recomendaciones(catalogo);
            break;
        case 4:

            // activa menu
            $("#titulo_tabla").html('Catálogo de conclusiones para informes');
            $("#modal_titulo").html("Catálogo de conclusiones para informes");
            $("#tr_4").addClass("active");
            $("#cat_4").addClass("text-info");

            // Inicializar tabla
            if (datatable_conclusiones != null) {
                datatable_conclusiones.destroy();
                datatable_conclusiones = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_conclusiones" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Categoria/dominio</th>' +
                '<th>Riesgo</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '<th style="width: 90px!important;">Activo</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_conclusiones(catalogo);
            break;

        case 5:

            // activa menu
            $("#titulo_tabla").html('Catálogo de recomendaciones de control para informes');
            $("#modal_titulo").html("Catálogo de recomendaciones de control para informes");
            $("#tr_5").addClass("active");
            $("#cat_5").addClass("text-info");

            // Inicializar tabla
            if (datatable_recomendaciones_control != null) {
                datatable_recomendaciones_control.destroy();
                datatable_recomendaciones_control = null;
            }


            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_recomendaciones_control" width="100%">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Recomendacion</th>' +
                '<th style="width: 90px!important;">Editar</th>' +
                '<th style="width: 90px!important;">Activo</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '</table>');

            tabla_catalogo_recomendaciones_control(catalogo);
            break;

    }
}

function editar_cargoInforme() {
    $('#tabla_lista_catalogo_cargos tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_cargos.row(tr);

        $('#form_cargo').each(function () {
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_CARGO_INFORME").val(row.data().ID_CARGO_INFORME);
        $("#CARGO").val(row.data().CARGO);
        $("#catalogo").val(1);

        // abrir modal
        $('#modal_cargo').modal({ backdrop: false });
    });
}

function editar_definicionInforme() {
    $('#tabla_lista_catalogo_definiciones tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_definiciones.row(tr);

        $('#form_definicion').each(function () {
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_DEFINICION_INFORME").val(row.data().ID_DEFINICION_INFORME);
        $("#CONCEPTO").val(row.data().CONCEPTO);
        $("#DESCRIPCION").val(row.data().DESCRIPCION);
        $("#FUENTE").val(row.data().FUENTE);
        $("#catalogo").val(2);

        // abrir modal
        $('#modal_definicion').modal({ backdrop: false });
    });
}

function editar_recomendacionInforme() {
    $('#tabla_lista_catalogo_recomendaciones tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_recomendaciones.row(tr);

        $('#form_recomendacion').each(function () {
            this.reset();
        });

        var categoria = row.data().CATEGORIA;
        var nivelriesgo = row.data().NIVELRIESGO;

        // Llenar campo formulario
        $("#ID_RECOMENDACION_INFORME").val(row.data().ID_RECOMENDACION_INFORME);

        if(categoria==="Acontecimientos traumáticos severos"){
            $("#CATEGORIA").val(1);
        }else if(categoria==="Ambiente de trabajo"){
            $("#CATEGORIA").val(2);
        }else if(categoria==="Factores propios de la actividad"){
            $("#CATEGORIA").val(3);
        }else if(categoria==="Organización del tiempo de trabajo"){
            $("#CATEGORIA").val(4);
        }else if(categoria==="Liderazgo y relaciones en el trabajo"){
            $("#CATEGORIA").val(5);
        }else if(categoria==="Entorno organizacional"){
            $("#CATEGORIA").val(6);
        }

        actualizarOpcionesNivelCategorias();

        if(nivelriesgo==="Riesgo muy alto"){
            $("#NIVELRIESGO").val(1);
        }else if(nivelriesgo==="Riesgo alto"){
            $("#NIVELRIESGO").val(2);
        }else if(nivelriesgo==="Riesgo medio"){
            $("#NIVELRIESGO").val(3);
        }else if(nivelriesgo==="Riesgo bajo"){
            $("#NIVELRIESGO").val(4);
        }else if(nivelriesgo==="Riesgo nulo"){
            $("#NIVELRIESGO").val(5);
        }else if (nivelriesgo === 'Hay casos') {
            $("#NIVELRIESGO").val(6);
        }else if (nivelriesgo === 'Sin casos') {
            $("#NIVELRIESGO").val(7);
        }


       
        $("#RECOMENDACION").val(row.data().RECOMENDACION);
        $("#catalogo").val(3);

        // abrir modal
        $('#modal_recomendacion').modal({ backdrop: false });
    });
}

function editar_conclusionInforme() {
    $('#tabla_lista_catalogo_conclusiones tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_conclusiones.row(tr);

        $('#form_conclusion').each(function () {
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_CONCLUSION_INFORME").val(row.data().ID_CONCLUSION_INFORME);
        var dominio = row.data().DOMINIO;
        var nivel = row.data().NIVEL;

        console.log(nivel);

        if (dominio == 'Acontecimientos traumáticos severos') {
            $("#DOMINIO").val(1);
        } else if (dominio == 'Ambiente de trabajo') {
            $("#DOMINIO").val(2);
        } else if (dominio == 'Factores propios de la actividad') {
            $("#DOMINIO").val(3);
        }else if (dominio == 'Organización del tiempo de trabajo') {
            $("#DOMINIO").val(4);
        }else if (dominio == 'Liderazgo y relaciones en el trabajo') {
            $("#DOMINIO").val(5);
        }else if (dominio == 'Entorno organizacional') {
            $("#DOMINIO").val(6);
        }else if (dominio == 'Condiciones del ambiente de trabajo') {
            $("#DOMINIO").val(7);
        }else if (dominio == 'Carga de trabajo') {
            $("#DOMINIO").val(8);
        }else if (dominio == 'Falta de control sobre el trabajo') {
            $("#DOMINIO").val(9);
        }else if (dominio == 'Jornada de trabajo') {
            $("#DOMINIO").val(10);
        }else if (dominio == 'Interferencia trabajo-familia') {
            $("#DOMINIO").val(11);
        }else if (dominio == 'Liderazgo') {
            $("#DOMINIO").val(12);
        }else if (dominio == 'Relaciones en el trabajo') {
            $("#DOMINIO").val(13);
        }else if (dominio == 'Violencia') {
            $("#DOMINIO").val(14);
        }else if (dominio == 'Reconocimiento del desempeño') {
            $("#DOMINIO").val(15);
        }else if (dominio == 'Insuficiente sentido de pertenencia e inestabilidad') {
            $("#DOMINIO").val(16);
        }
        actualizarOpcionesNivel(); 

        if (nivel === 'Riesgo muy alto') {
            $("#NIVEL").val(1);
        } else if (nivel === 'Riesgo alto') {
            $("#NIVEL").val(2);
        } else if (nivel === 'Riesgo medio') {
            $("#NIVEL").val(3);
        }else if (nivel === 'Riesgo bajo') {
            $("#NIVEL").val(4);
        }else if (nivel === 'Riesgo nulo') {
            $("#NIVEL").val(5);
        }else if (nivel === 'Acontecimiento traumático severo') {
            $("#NIVEL").val(6);
        }else if (nivel === 'No existe acontecimiento traumático severo') {
            $("#NIVEL").val(7);
        }
        
        $("#CONCLUSION").val(row.data().CONCLUSION);
        $("#catalogo").val(4);
        // abrir modal
        $('#modal_conclusion').modal({ backdrop: false });
    });
}

function editar_recomendacioncontrolInforme() {
    $('#tabla_lista_catalogo_recomendaciones_control tbody').on('click', 'td.editar', function () {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_recomendaciones_control.row(tr);

        $('#form_recomendaciones_control').each(function () {
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_RECOMENDACION_CONTROL_INFORME").val(row.data().ID_RECOMENDACION_CONTROL_INFORME);
        $("#RECOMENDACION_CONTROL").val(row.data().RECOMENDACION_CONTROL);
        $("#catalogo").val(5);

        // abrir modal
        $('#modal_recomendaciones_control').modal({ backdrop: false });
    });
}

//--------------------------------------------------------TABLAS----------------------------//
function tabla_catalogo_cargos(num_catalogo) {
    var ruta = "/recpsicocatalogosinformes/" + num_catalogo;

    try {
        if (datatable_cargos != null) {
            datatable_cargos.clear().draw();
            datatable_cargos.ajax.url(ruta).load();
        }
        else {
            datatable_cargos = $('#tabla_lista_catalogo_cargos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_cargos(num_catalogo);
                    },
                    "data": {}
                },
               "columns": [
                    {
                        "data": "ID_CARGO_INFORME"
                    },
                    {
                        "data": "CARGO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        "orderable": false,
                        "data": 'CheckboxEstado',
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
        tabla_catalogo_cargos(num_catalogo);
    }
}

function tabla_catalogo_recomendaciones_control(num_catalogo) {
    var ruta = "/recpsicocatalogosinformes/" + num_catalogo;

    try {
        if (datatable_recomendaciones_control != null) {
            datatable_recomendaciones_control.clear().draw();
            datatable_recomendaciones_control.ajax.url(ruta).load();
        }
        else {
            datatable_recomendaciones_control = $('#tabla_lista_catalogo_recomendaciones_control').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_recomendaciones_control(num_catalogo);
                    },
                    "data": {}
                },
               "columns": [
                    {
                        "data": "ID_RECOMENDACION_CONTROL_INFORME"
                    },
                    {
                        "data": "RECOMENDACION_CONTROL"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        "orderable": false,
                        "data": 'CheckboxEstado',
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
        tabla_catalogo_recomendaciones_control(num_catalogo);
    }
}

function tabla_catalogo_definiciones(num_catalogo) {
    var ruta = "/recpsicocatalogosinformes/" + num_catalogo;

    try {
        if (datatable_definiciones != null) {
            datatable_definiciones.clear().draw();
            datatable_definiciones.ajax.url(ruta).load();
        }
        else {
            datatable_definiciones = $('#tabla_lista_catalogo_definiciones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_definiciones(num_catalogo);
                    },
                    "data": {}
                },
               "columns": [
                    {
                        "data": "ID_DEFINICION_INFORME"
                    },
                    {
                        "data": "CONCEPTO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        "orderable": false,
                        "data": 'CheckboxEstado',
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
        tabla_catalogo_definiciones(num_catalogo);
    }
}

function tabla_catalogo_recomendaciones(num_catalogo) {
    var ruta = "/recpsicocatalogosinformes/" + num_catalogo;

    try {
        if (datatable_recomendaciones != null) {
            datatable_recomendaciones.clear().draw();
            datatable_recomendaciones.ajax.url(ruta).load();
        }
        else {
            datatable_recomendaciones = $('#tabla_lista_catalogo_recomendaciones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_recomendaciones(num_catalogo);
                    },
                    "data": {}
                },
               "columns": [
                    {
                        "data": "CATEGORIA"
                    },
                    {
                        "data": "NIVELRIESGO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "asc"]],
                "rowsGroup": [0],
                "searching": true,
                "paging": false,
                "ordering": true,
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
        tabla_catalogo_recomendaciones(num_catalogo);
    }
}

function tabla_catalogo_conclusiones(num_catalogo) {
    var ruta = "/recpsicocatalogosinformes/" + num_catalogo;

    try {
        if (datatable_conclusiones != null) {
            datatable_conclusiones.clear().draw();
            datatable_conclusiones.ajax.url(ruta).load();
        }
        else {
            datatable_conclusiones = $('#tabla_lista_catalogo_conclusiones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        tabla_catalogo_conclusiones(num_catalogo);
                    },
                    "data": {}
                },
               "columns": [
                    {
                        "data": "ID_CONCLUSION_INFORME"
                    },
                    {
                        "data": "DOMINIO"
                    },
                    {
                        "data": "NIVEL"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        "orderable": false,
                        "data": 'CheckboxEstado',
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
        tabla_catalogo_conclusiones(num_catalogo);
    }
}

function estado_registro(catalogo, registro, checkbox) {
    var estado = 0;
    if (checkbox.checked) {
        estado = 1;// activo
    }
    else {
        estado = 0;// Inactivo
    }

    // enviar datos
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/recpsicocatalogodesactiva/" + catalogo + "/" + registro + "/" + estado,
        data: {},
        cache: false,
        success: function (dato) {
            // mensaje
            swal({
                title: "Correcto",
                text: "" + dato.msj,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function (dato) {
            // checkbox estado anterior
            if (checkbox.checked) {
                $(checkbox).prop('checked', false); // volver a Inactivar
            }
            else {
                $(checkbox).prop('checked', true); // volver activar
            }

            // mensaje
            swal({
                title: "Error",
                text: "Error al modificar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
            return false;
        }
    });//Fin ajax
}
