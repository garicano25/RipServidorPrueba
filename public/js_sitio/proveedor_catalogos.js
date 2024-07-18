
var nombre_tabla = "";
var datatable_tipoproveedor = null;
var datatable_catalogos = null;
var catalogo = 0;

// Load CATALOGOS PROVEEDORES
$(document).ready(function() {
	mostrar_catalogo(1);
});


function mostrar_catalogo(num_catalogo)
{
    catalogo = num_catalogo;

	// activar Menu
	$("#tr_1").removeClass("active");
	$("#tr_2").removeClass("active");
	$("#tr_3").removeClass("active");
	$("#tr_4").removeClass("active");
	$("#tr_5").removeClass("active");
	// $("#tr_6").removeClass("active");

	$("#cat_1").removeClass("text-info");
	$("#cat_1").addClass("text-secondary");
	$("#cat_2").removeClass("text-info");
	$("#cat_2").addClass("text-secondary");
	$("#cat_3").removeClass("text-info");
	$("#cat_3").addClass("text-secondary");
	$("#cat_4").removeClass("text-info");
	$("#cat_4").addClass("text-secondary");
	$("#cat_5").removeClass("text-info");
	$("#cat_5").addClass("text-secondary");
	// $("#cat_6").removeClass("text-info");
	// $("#cat_6").addClass("text-secondary");

	// Renombrar nombre del campo segun el CATALOGO
   	switch (num_catalogo)
   	{
        case 1:
            $("#titulo_tabla").html('Catálogo [Tipo proveedor]');
            $("#tr_1").addClass("active");
			$("#cat_1").addClass("text-info");

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_tipoproveedor" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Nombre</th>'+
                                                '<th>Alcance</th>'+
                                                '<th width="120">Editar</th>'+
                                                '<th width="120">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            // LLenar tabla REGISTROS
            funcion_tabla_tipoproveedor(num_catalogo);
            $("#titulo_modal").html('Tipo de proveedor');
            break;
        case 2:
        	$("#nombre").attr('name', 'catServicioAcreditacion_Nombre');
            $("#activo").attr('name', 'catServicioAcreditacion_Activo');
            $("#titulo_tabla").html('Catálogo [Servicio acreditación]');
            $("#tr_2").addClass("active");
			$("#cat_2").addClass("text-info");

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_servicioacreditacion" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Nombre</th>'+
                                                '<th width="120">Editar</th>'+
                                                '<th width="120">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            // LLenar tabla REGISTROS
            $("#titulo_modal").html('Servicio acreditación');
            nombre_tabla = "tabla_servicioacreditacion";
            funcion_tabla_catalogo(num_catalogo);
            break;
        case 3:
        	$("#nombre").attr('name', 'catTipoAcreditacion_Nombre');
            $("#activo").attr('name', 'catTipoAcreditacion_Activo');
            $("#titulo_tabla").html('Catálogo [Tipo acreditación]');
            $("#tr_3").addClass("active");
			$("#cat_3").addClass("text-info");

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_tipoacreditacion" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Nombre</th>'+
                                                '<th width="120">Editar</th>'+
                                                '<th width="120">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            // LLenar tabla REGISTROS
            $("#titulo_modal").html('Tipo acreditación');
            nombre_tabla = "tabla_tipoacreditacion";
            funcion_tabla_catalogo(num_catalogo);
            break;
        case 4:
        	$("#nombre").attr('name', 'catArea_Nombre');
            $("#activo").attr('name', 'catArea_Activo');
            $("#titulo_tabla").html('Catálogo [Areas]');
            $("#tr_4").addClass("active");
			$("#cat_4").addClass("text-info");

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_areas" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Nombre</th>'+
                                                '<th width="120">Editar</th>'+
                                                '<th width="120">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            // LLenar tabla REGISTROS
            $("#titulo_modal").html('Áreas');
            nombre_tabla = "tabla_areas";
            funcion_tabla_catalogo(num_catalogo);
            break;
        case 5:
			$("#nombre").attr('name', 'cat_Signatarioestado_Nombre');
            $("#activo").attr('name', 'cat_Signatarioestado_Activo');
            $("#titulo_tabla").html('Catálogo [Estado del signatario]');
            $("#tr_5").addClass("active");
            $("#cat_5").addClass("text-info");

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_estadosignatario" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Nombre</th>'+
                                                '<th width="120">Editar</th>'+
                                                '<th width="120">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            // LLenar tabla REGISTROS
            $("#titulo_modal").html('Estado del signatario');
            nombre_tabla = "tabla_estadosignatario";
            funcion_tabla_catalogo(num_catalogo);
            break;
    }
}


$("#boton_nuevo_registro").click(function()
{
    switch (catalogo)
    {
        case 1:
            // Borrar formulario
            $('#form_tipoproveedor').each(function(){
                this.reset();
            });

            // campos hidden
            $("#catTipoproveedor_id").val(0);
            $("#CatTipoproveedor_catalogo").val(catalogo);

            // abrir modal
            $('#modal_tipoproveedor').modal({backdrop:false});
            break;
        default:
            // Borrar formulario
            $('#form_catalogo').each(function(){
                this.reset();
            });

            // Llenar campo formulario
            $("#id").val(0);
            $("#nombre").val('');
            // $("#activo").val(1);
            $("#catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo').modal({backdrop:false});
            break;
    }
});


//=================================================



function funcion_tabla_tipoproveedor(num_catalogo)
{
    // Inicializar tabla
    if (datatable_tipoproveedor != null)
    {
        datatable_tipoproveedor.destroy();
    }
    
    datatable_tipoproveedor = $('#tabla_tipoproveedor').DataTable({
        "ajax": {
            "url": "/consultacatalogo/"+num_catalogo,
            "type": "get",
            "cache": false,
            error: function (xhr, error, code)
            {
                // console.log(xhr); console.log(code);
                funcion_tabla_tipoproveedor(num_catalogo);
            },
            "data": {}
        },
        "columns": [
            // {
            //     "data": "id" 
            // },
            {
                "data": "nombre",
                "defaultContent": '-'
            },
            {
                "data": "lista_alcances",
                "defaultContent": '-'
            },
            {
                "className": 'editar',
                "orderable": false,
                "data": 'boton_editar',
                // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                "defaultContent": '-'
            },
            {
                "className": 'desactiva',
                "orderable": false,
                "data": 'CheckboxEstado',
                "defaultContent": '-'
            }
        ],
        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
        "order": [[ 0, "asc" ]],        
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


function editar_tipoproveedor()
{
    $('#tabla_tipoproveedor tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_tipoproveedor.row(tr);

        // Borrar formulario
        $('#form_tipoproveedor').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#catTipoproveedor_id").val(row.data().id);
        $("#catTipoproveedor_Nombre").val(row.data().nombre);
        $("#CatTipoproveedor_catalogo").val(1);

        // Seleccionar checkbox
        $.each( row.data().alcance, function( key, value ) 
        {
            $("#alcance_"+value.cat_tipoproveedoralcance_alcance).prop('checked', true);
        });

        // abrir modal
        $('#modal_tipoproveedor').modal({backdrop:false});
    });
}


$("#boton_guardar_tipoproveedor").click(function()
{
    // valida campos vacios
    var seleccionados = 0;
    var valida = this.form.checkValidity();
    if (valida)
    {
        $('#form_tipoproveedor input[type=checkbox]').each(function(){
            if (this.checked) {
                seleccionados += 1;
            }
        });

        // valida parametros seleccionados
        if (seleccionados>0)
        {
            // enviar datos
            $('#form_tipoproveedor').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: '/proveedorcatalogos',
                data: {},
                resetForm: false,
                success: function(dato){

                    // actualiza tabla
                    funcion_tabla_tipoproveedor(1);

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

                    // actualiza boton
                    $('#boton_guardar_tipoproveedor').html('Guardar <i class="fa fa-save"></i>');

                    // cerrar modal
                    $('#modal_tipoproveedor').modal('hide');
                },
                beforeSend: function(){
                    $('#boton_guardar_tipoproveedor').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(dato) {
                    // actualiza boton
                    $('#boton_guardar_tipoproveedor').html('Guardar <i class="fa fa-save"></i>');
                    // mensaje
                    swal({
                        title: "Error",
                        text: "Error en la acción: "+dato,
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
        else
        {
            // mensaje
            swal({
                title: "Seleccione alcance",
                text: "Debe seleccionar una o más opciones de información para este tipo de proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
            return false;
        }
    }
});


function estado_tipoproveedor(catalogo, registro, checkbox)
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
        url: "/proveedordesactivacatalogo/"+catalogo+"/"+registro+"/"+estado,
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


//=================================================


function funcion_tabla_catalogo(num_catalogo)
{
    // Inicializar tabla
    if (datatable_catalogos != null)
    {
        datatable_catalogos.destroy();
    }
    
    datatable_catalogos = $('#'+nombre_tabla).DataTable({
        "ajax": {
            "url": "/consultacatalogo/"+num_catalogo,
            "type": "get",
            "cache": false,
            error: function (xhr, error, code)
            {
                // console.log(xhr); console.log(code);
                funcion_tabla_catalogo(num_catalogo);
            },
            "data": {}
        },
        "columns": [
            // {
            //     "data": "id" 
            // },
            {
                "data": "nombre",
                "defaultContent": '-'
            },
            {
                "className": 'editar',
                "orderable": false,
                "data": 'boton_editar',
                // "defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
                "defaultContent": '-'
            },
            {
                "className": 'estado',
                "orderable": false,
                "data": 'CheckboxEstado',
                "defaultContent": '-'
            }
        ],
        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
        "order": [[ 0, "asc" ]],        
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


function editar_registro()
{
    $('#'+nombre_tabla+' tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_catalogos.row(tr);

        // Borrar formulario
        $('#form_catalogo').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#id").val(row.data().id);
        $("#nombre").val(row.data().nombre);
        // $("#activo").val(1);
        $("#catalogo").val(catalogo);

        // abrir modal
        $('#modal_catalogo').modal({backdrop:false});
    });
}


$("#boton_guardar_catalogo").click(function()
{
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catalogo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/proveedorcatalogos',
            data: {},
            resetForm: false,
            success: function(dato){

                // actualiza tabla
                funcion_tabla_catalogo(catalogo);

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

                // actualiza boton
                $('#boton_guardar_catalogo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catalogo').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_catalogo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_catalogo').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
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
        url: "/proveedordesactivacatalogo/"+catalogo+"/"+registro+"/"+estado,
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
