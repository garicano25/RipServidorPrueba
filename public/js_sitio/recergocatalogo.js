var datatable_caracteristicas = null;
var datatable_catalogo = null;
var datatable_catalogo2campos = null;
var datatable_agentes = null;
var datatable_organizacion = null;
var datatable_opciones = null;
var datatable_epp = null;
var datatable_cargos = null;
var datatable_formatos = null;
var datatable_conclusiones = null;


///// DECLARAR LAS DATETABLE GLOBALES

var datatable_regimencontractual = null;

var datatable_jornada = null;
var datatable_turno = null;
var datatable_introduccion = null;
var datatable_definiciones = null;
var datatable_recomendaciones = null;
var datatable_conclusion = null;


var catalogo = 0;

// Load CATALOGOS PROVEEDORES
$(document).ready(function()
{
	mostrar_catalogo(1);

    // Inicializar tooltip
    $('[data-toggle="tooltip"]').tooltip();
});


function mostrar_catalogo(num_catalogo)
{
    catalogo = parseInt(num_catalogo);

    // Desactivar ACTIVE menu catalogos
    $.each( $('#tabla_lista_catalogos tr'), function( key, value )
    {
        $(this).removeClass('active');
        $("#cat_"+(key+1)).removeClass("text-info");
        $("#cat_"+(key+1)).addClass("text-secondary");
    });
    
   	switch (catalogo){

        case 1:
            // activar menu
            $("#titulo_tabla").html('Catálogo [Régimen Contractual]');
            $("#tr_1").addClass("active");
			$("#cat_1").addClass("text-info");

            // Inicializar tabla
            if(datatable_regimencontractual != null)
            {
                datatable_regimencontractual.destroy();
                datatable_regimencontractual = null;
            }

            // diseño tabla
              $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_regimencontractual" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Nombre régimen</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_regimen_contractual(catalogo);
            break;
        case 2:
           
            // activa menu
            $("#titulo_tabla").html('Catálogo [Jornada]');
            $("#tr_2").addClass("active");
			$("#cat_2").addClass("text-info");

            // Inicializar tabla
            if(datatable_jornada != null)
            {
                datatable_jornada.destroy();
                datatable_jornada = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_jornada" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Jornada</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_jornada(catalogo);
            break;
        case 3:

            // activa menu
            $("#titulo_tabla").html('Catálogo [Turno]');
            $("#tr_3").addClass("active");
			$("#cat_3").addClass("text-info");

            // Inicializar tabla
            if(datatable_turno != null)
            {
                datatable_turno.destroy();
                datatable_turno = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_turno" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Turno</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');
                  tabla_turno(catalogo);
        
            break;
        case 4:
        
            // activa menu
            $("#titulo_tabla").html('Catálogo [Introducción]');
            $("#tr_4").addClass("active");
            $("#cat_4").addClass("text-info");

            // Inicializar tabla
            if(datatable_introduccion != null)
            {
                datatable_introduccion.destroy();
                datatable_introduccion = null;
            }

            // diseño tabla
              $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_introduccion" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Introducción</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_introduccion(catalogo);
            break;
        case 5:
            
            // activa menu
			$("#titulo_tabla").html('Catálogo [Definiciones]');
			$("#tr_5").addClass("active");
			$("#cat_5").addClass("text-info");

            // Inicializar tabla
            if(datatable_definiciones != null)
            {
                datatable_definiciones.destroy();
                datatable_definiciones = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_definiciones" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Uso</th>' +
                                                '<th>Concepto</th>' +
                                                '<th>Descripción</th>' +
                                                '<th>Fuente</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_definiciones(catalogo);
            break;
        case 6:
            
            // activa menu
            $("#titulo_tabla").html('Catálogo [Recomendaciones]');
            $("#tr_6").addClass("active");
            $("#cat_6").addClass("text-info");

            // Inicializar tabla
            if(datatable_recomendaciones != null)
            {
                datatable_recomendaciones.destroy();
                datatable_recomendaciones = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_recomendaciones" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Uso</th>' +
                                                '<th>Tipo</th>' +
                                                '<th>Descripción</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

             tabla_recomendaciones(catalogo);
            break;
        case 7:
            // campos


            // activa menu
            $("#titulo_tabla").html('Catálogo [Conclusión]');
            $("#tr_7").addClass("active");
            $("#cat_7").addClass("text-info");

            // Inicializar tabla
            if(datatable_conclusion != null)
            {
                datatable_conclusion.destroy();
                datatable_conclusion = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_conclusion" width="100%">'+
                                         '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>' +
                                                '<th>Conclusión</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_conclusion(catalogo);
            break;      

    }
}


$("#boton_nuevo_registro").click(function()
{
    switch (catalogo)
    {
        case 1:
            
                $('#form_regimen').each(function(){
                    this.reset();
                });
    
                $("#ID_REGIMEN_CONTRACTUAL").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_regimen').modal({backdrop:false});
            
            break;
        case 2:
                $('#form_jornada').each(function(){
                    this.reset();
                });
    
                $("#ID_JORNADA").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_jornada').modal({backdrop:false});
           break;
        case 3:
                $('#form_turno').each(function(){
                    this.reset();
                });
    
                $("#ID_TURNO").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_turno').modal({backdrop:false});
            break;
        
        case 4:
                $('#form_introduccion').each(function(){
                    this.reset();
                });
    
                $("#ID_INTRODUCCION").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_introduccion').modal({backdrop:false});
            break;
        case 5:
                $('#form_definiciones').each(function(){
                    this.reset();
                });
    
                $("#ID_DEFINICIONES").val(0);
                 $("#catalogo").val(catalogo);
            
                $('#modal_definiciones').modal({ backdrop: false });
            
          
            break;
        case 6:
                $('#form_recomendaciones').each(function(){
                    this.reset();
                });
    
                $("#ID_RECOMENDACIONES").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_recomendaciones').modal({ backdrop: false });
            
            break;
        case 7:
                $('#form_conclusion').each(function(){
                    this.reset();
                });
    
                $("#ID_CONCLUSION").val(0);
                $("#catalogo").val(catalogo);
    
                $('#modal_conclusion').modal({backdrop:false});
            break;
        
        default:
            // Borrar formulario
            $('#form_catalogo').each(function(){
                this.reset();
            });

            // campos hidden
            $("#id").val(0);
            $("#catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo').modal({backdrop:false});
            break;
    }
});






//=======================================
// CATALOGO Régimen Contractual
//=======================================

$("#boton_guardar_regimen").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_regimen').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_regimen_contractual(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_regimen').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_regimen').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_regimen').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_regimen').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_regimen_contractual(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_regimencontractual != null)
        {
            datatable_regimencontractual.clear().draw();
            datatable_regimencontractual.ajax.url(ruta).load();
        }
        else
        {
            datatable_regimencontractual = $('#tabla_lista_regimencontractual').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_regimen_contractual(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_REGIMEN_CONTRACTUAL"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_regimen_contractual(num_catalogo);
    }    
}

function editar_cat_regioncontractual()
{
    $('#tabla_lista_regimencontractual tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_regimencontractual.row(tr);

        $('#form_regimen').each(function(){
            this.reset();
        });

        $("#ID_REGIMEN_CONTRACTUAL").val(row.data().ID_REGIMEN_CONTRACTUAL);
        $("#NOMBRE_REGIMEN_CONTRACTUAL").val(row.data().NOMBRE_REGIMEN_CONTRACTUAL);
        $("#catalogo").val(catalogo);

        $('#modal_regimen').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO Jornada
//=======================================

$("#boton_guardar_jornada").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_jornada').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_jornada(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_jornada').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_jornada').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_jornada').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_jornada').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_jornada(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_jornada != null)
        {
            datatable_jornada.clear().draw();
            datatable_jornada.ajax.url(ruta).load();
        }
        else
        {
            datatable_jornada = $('#tabla_lista_jornada').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_jornada(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_JORNADA"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_jornada(num_catalogo);
    }    
}

function editar_cat_jornada()
{
    $('#tabla_lista_jornada tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_jornada.row(tr);

        $('#form_jornada').each(function(){
            this.reset();
        });

        $("#ID_JORNADA").val(row.data().ID_JORNADA);
        $("#NOMBRE_JORNADA").val(row.data().NOMBRE_JORNADA);
        $("#catalogo").val(catalogo);

        $('#modal_jornada').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO Turno
//=======================================

$("#boton_guardar_turno").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_turno').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_turno(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_turno').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_turno').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_turno').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_turno').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_turno(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_turno != null)
        {
            datatable_turno.clear().draw();
            datatable_turno.ajax.url(ruta).load();
        }
        else
        {
            datatable_turno = $('#tabla_lista_turno').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_turno(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_TURNO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_turno(num_catalogo);
    }    
}

function editar_cat_turno()
{
    $('#tabla_lista_turno tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_turno.row(tr);

        $('#form_turno').each(function(){
            this.reset();
        });

        $("#ID_TURNO").val(row.data().ID_TURNO);
        $("#NOMBRE_TURNO").val(row.data().NOMBRE_TURNO);
        $("#catalogo").val(catalogo);

        $('#modal_turno').modal({backdrop:false});
    });
}


//=======================================
// CATALOGO Introducción
//=======================================

$("#boton_guardar_introduccion").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_introduccion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_introduccion(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_introduccion').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_introduccion').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_introduccion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_introduccion').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_introduccion(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_introduccion != null)
        {
            datatable_introduccion.clear().draw();
            datatable_introduccion.ajax.url(ruta).load();
        }
        else
        {
            datatable_introduccion = $('#tabla_lista_introduccion').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_introduccion(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_INTRODUCCION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_introduccion(num_catalogo);
    }    
}

function editar_cat_introduccion()
{
    $('#tabla_lista_introduccion tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_introduccion.row(tr);

        $('#form_introduccion').each(function(){
            this.reset();
        });

        $("#ID_INTRODUCCION").val(row.data().ID_INTRODUCCION);
        $("#NOMBRE_INTRODUCCION").val(row.data().NOMBRE_INTRODUCCION);
        $("#catalogo").val(catalogo);

        $('#modal_introduccion').modal({backdrop:false});
    });
}



//=======================================
// CATALOGO Definiciones
//=======================================

$("#boton_guardar_definiciones").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_definiciones').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_definiciones(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_definiciones').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_definiciones').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_definiciones').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_definiciones').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_definiciones(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_definiciones != null)
        {
            datatable_definiciones.clear().draw();
            datatable_definiciones.ajax.url(ruta).load();
        }
        else
        {
            datatable_definiciones = $('#tabla_lista_definiciones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_definiciones(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "USO_DEFINICIONES"
                    },
                    {
                        "data": "CONCEPTO_DEFINICION"
                    },
                     {
                        "data": "DESCRIPCION_DEFINICION"
                    },
                     {
                        "data": "FUENTE_DEFINICION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_definiciones(num_catalogo);
    }    
}

function editar_cat_definiciones()
{
    $('#tabla_lista_definiciones tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_definiciones.row(tr);

        $('#form_definiciones').each(function(){
            this.reset();
        });

        $("#ID_DEFINICIONES").val(row.data().ID_DEFINICIONES);

        $("#CONCEPTO_DEFINICION").val(row.data().CONCEPTO_DEFINICION);
        $("#DESCRIPCION_DEFINICION").val(row.data().DESCRIPCION_DEFINICION);
        $("#FUENTE_DEFINICION").val(row.data().FUENTE_DEFINICION);
        $("#USO_DEFINICIONES").val(row.data().USO_DEFINICIONES);

        
        $("#catalogo").val(catalogo);

        $('#modal_definiciones').modal({backdrop:false});
    });
}

//=======================================
// CATALOGO Recomendaciones
//=======================================

$("#boton_guardar_recomendaciones").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_recomendaciones').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_recomendaciones(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_recomendaciones').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_recomendaciones').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_recomendaciones').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_recomendaciones').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_recomendaciones(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_recomendaciones != null)
        {
            datatable_recomendaciones.clear().draw();
            datatable_recomendaciones.ajax.url(ruta).load();
        }
        else
        {
            datatable_recomendaciones = $('#tabla_lista_recomendaciones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_recomendaciones(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                     {
                        "data": "USO_RECOMENDACIONES"
                    },
                    {
                        "data": "TIPO_RECOMENDACIONES"
                    },
                     {
                        "data": "DESCRIPCION_RECOMENDACIONES"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_recomendaciones(num_catalogo);
    }    
}

function editar_cat_recomendaciones()
{
    $('#tabla_lista_recomendaciones tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_recomendaciones.row(tr);

        $('#form_recomendaciones').each(function(){
            this.reset();
        });

        $("#ID_RECOMENDACIONES").val(row.data().ID_RECOMENDACIONES);
        $("#TIPO_RECOMENDACIONES").val(row.data().TIPO_RECOMENDACIONES);
        $("#DESCRIPCION_RECOMENDACIONES").val(row.data().DESCRIPCION_RECOMENDACIONES);
        $("#USO_RECOMENDACIONES").val(row.data().USO_RECOMENDACIONES);

        
        $("#catalogo").val(catalogo);

        $('#modal_recomendaciones').modal({backdrop:false});
    });
}





//=======================================
// CATALOGO Conclusión
//=======================================

$("#boton_guardar_conclusion").click(function () {

    var valida = this.form.checkValidity();
    if (valida) {
        $('#form_conclusion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recergocatalogo',
            data: {},
            resetForm: false,
            success: function (dato) {
                tabla_conclusion(catalogo);

                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {
                        visible: false, 
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');

                $('#modal_conclusion').modal('hide');
            },
            beforeSend: function () {
                $('#boton_guardar_conclusion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error", 
                    buttons: {
                        visible: false, 
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

function tabla_conclusion(num_catalogo)
{
    var ruta = "/ergoconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_conclusion != null)
        {
            datatable_conclusion.clear().draw();
            datatable_conclusion.ajax.url(ruta).load();
        }
        else
        {
            datatable_conclusion = $('#tabla_lista_conclusion').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_conclusion(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        "data": "NOMBRE_CONCLUSION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Estado',
                        // "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": true,
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
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_conclusion(num_catalogo);
    }    
}

function editar_cat_conclusion()
{
    $('#tabla_lista_conclusion tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_conclusion.row(tr);

        $('#form_conclusion').each(function(){
            this.reset();
        });

        $("#ID_CONCLUSION").val(row.data().ID_CONCLUSION);
        $("#NOMBRE_CONCLUSION").val(row.data().NOMBRE_CONCLUSION);
        $("#catalogo").val(catalogo);

        $('#modal_conclusion').modal({backdrop:false});
    });
}
//=======================================
// FUNCION GLOBAL ACTIVAR/DESACTIVAR 
//=======================================

function estado_registro(catalogo, registro, checkbox)
{
    var estado = 0;
    if (checkbox.checked) {
        estado = 1;// activo
    }
    else{
        estado = 0;// Inactivo
    }

    // enviar datos
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/ergocatalogodesactiva/"+catalogo+"/"+registro+"/"+estado,
        data:{},
        cache: false,
        success:function(dato){
            // mensaje
            swal({
                title: "Correcto",
                text: ""+dato.msj,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(dato){
            // checkbox estado anterior
            if (checkbox.checked){
                $(checkbox).prop('checked', false); // volver a Inactivar
            }
            else{
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


