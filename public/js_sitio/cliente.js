// DATATABLES
var TablaClientes = null;
var datatable_convenios = null;
var datatable_contratos = null;
var TablaDocumentos = null;
var TablaDocumentosCierre = null;
var datatable_partidas = null;
var datatable_partidas_convenios = null;
var datatable_anexo = null;
var eventos = null;

// Variables globales
var cliente_id = 0;
var contrato_id = 0;
var cliente_Bloqueado = 0;
var puedeCerrar = 0; //Esta variable es para darle la posibilidad de cerrar el documento
var fechaActual = new Date();
var convenio_id = 0;

//======================================================================
function convertirFecha(fecha) {

    var fecha = new Date(fecha);
    return fecha
}

// Load TABLA CLIENTES
$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    tabla_clientes();
    $('#tab_info_cliente').css("display", 'none');
    $('#tab_info_contratos').css("display", 'none');

    $('[data-toggle="tooltip"]').tooltip();

    // Inicializar campo datepicker FECHAS
    $('.mydatepicker').datepicker({
        format: 'yyyy-mm-dd', //'dd-mm-yyyy'
        weekStart: 1, //dia que inicia la semana, 1 = Lunes
        // startDate: new Date('11/17/2020'), // deshabilitar dias anteriores con fecha
        // startDate: '-3d', // deshabilitar dias anteriores del dia actual
        // endDate: '+3d', //deshabilitar dias despues del dia actual
        calendarWeeks: true,
        autoclose: false,
        todayHighlight: true, //Dia de hoy marcado en el calendario
        toggleActive: true,
        // setDate: "2020/11/25", //Fecha marcada en el caledario
        forceParse: false //mantiene la fecha del input si no se selecciona otra
    });
});


// navegar menu Tab principal
$('.nav-link').click(function () {
    switch (this.id) {
        case "tab_tabla_cliente":

            $('#tab_info_cliente').css('display', 'none');
            $('#tab_info_contratos').css('display', 'none');
            $('#divContratos').css('display', 'none');
            $('#boton_finalizar_contrato').removeClass('mostrar')
            $('#boton_finalizar_contrato').addClass('ocultar')

            cliente_id = 0;
            contrato_id = 0
            break;
        case "tab_info_cliente":

            contrato_id = 0
            $('#tab_info_contratos').css('display', 'none');
            $('#divContratos').css('display', 'none');

            if ($('#listaContratos').hasClass('ocultar')) {
                $('#listaContratos').removeClass('ocultar')
                $('#listaContratos').addClass('mostrar');
            }

            break;
        default:
            $('#divContratos').css('display', 'block');

            break;
    }
});


//======================================================================


function tabla_clientes() {
    try {
        if (TablaClientes != null) {
            TablaClientes.clear().draw();
            TablaClientes.ajax.url("/tablacliente").load();
        }
        else {
            var numeroejecucion = 1;
            TablaClientes = $('#tabla_clientes').DataTable({
                "ajax": {
                    "url": "/tablacliente",
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        // console.log(xhr); console.log(code);
                        console.log('error en datatable_clientes');
                        if (numeroejecucion <= 1) {
                            tabla_clientes();
                            numeroejecucion += 1;
                        }
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "numero_registro",
                        "defaultContent": '-'
                    },
                    {
                        "data": "cliente_RazonSocial",
                        "defaultContent": '-'
                    },
                    {
                        "data": "cliente_LineaNegocios",
                        "defaultContent": '-'
                    },
                    {
                        "data": "ciudad_pais",
                        "defaultContent": '-'
                    },
                    {
                        "data": "cliente_DomicilioFiscal",
                        "defaultContent": '-'
                    },
                    {
                        "data": "cliente_RepresentanteLegal",
                        "defaultContent": '-'
                    },
                    {
                        "data": "cliente_Rfc",
                        "defaultContent": '-'
                    },
                    // {
                    //     "data": "cliente_numerocontrato",
                    //     "defaultContent": '-' 
                    // },
                    // {
                    //     "data": "vigencia",
                    //     "defaultContent": '-' 
                    // },
                    {
                        "className": 'Editar_cliente',
                        "orderable": false,
                        "data": 'boton_mostrar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "desc"]],
                "ordering": true,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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

        // Tooltip en DataTable
        TablaClientes.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    catch (exception) {
        tabla_clientes();
    }
}


// Precionar boton nuevo cliente
$("#boton_nuevo_cliente").click(function () {
    // mostrar TAB
    $('#tab_info_cliente').css("display", 'block');

    // Datos del cliente
    $(".div_cliente_nombre").html('Nombre cliente');
    $(".div_cliente_lineanegocio").html('Línea de negocios');

    // Borrar formulario
    $('#form_cliente').each(function () {
        this.reset();
    });

    // seleccionar TAB
    $("#tab_info_cliente").click();

    //Ocultamos la lista de los contratos ya que es un cliente nuevo y no tiene contartos
    if ($('#listaContratos').hasClass('mostrar')) {
        $('#listaContratos').removeClass('mostrar')
        $('#listaContratos').addClass('ocultar');
    }

    // actualizar campo HIDDEN
    contrato_id = 0;
    cliente_id = 0
    $("#cliente_id").val(0);
    $("#cliente_plantillapiepagina").val('');
    $('#plantillalogoizquierdo').attr('required', true);
    $('#plantillalogoderecho').attr('required', true);

    // Restablecer el estado del switch y ocultar los selectores
    $('#requiere_estructuraCliente').prop('checked', false);
    $('#requiere_estructuraCliente_activo').val('No');
    $('.selector-group').hide();

    // Restablecer los selectores
    $('.etiqueta-select').val('');
    $('.opcion-select').empty().append('<option value=""></option>');



    // inicializar campos FOTOS
    if ($('#plantillalogoizquierdo').data('dropify')) {
        $('#plantillalogoizquierdo').dropify().data('dropify').resetPreview();
        $('#plantillalogoizquierdo').dropify().data('dropify').clearElement();
        // $('#plantillalogoizquierdo').dropify().data('dropify').destroy();
        // $('#plantillalogoizquierdo').dropify().data('dropify').init();
    }
    else {
        // $('#plantillalogoizquierdo').attr('data-height', 400);
        // $('#plantillalogoizquierdo').attr('data-default-file', imagenUrl);
        $('#plantillalogoizquierdo').dropify({
            messages: {
                'default': 'Arrastre la imagen aquí o haga click',
                'replace': 'Arrastre la imagen o haga clic para reemplazar',
                'remove': 'Quitar',
                'error': 'Ooops, ha ocurrido un error.'
            },
            error: {
                'fileSize': 'Demasiado grande ({{ value }} max).',
                'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
            }
        });
    }

    // inicializar campos FOTOS
    if ($('#plantillalogoderecho').data('dropify')) {
        $('#plantillalogoderecho').dropify().data('dropify').resetPreview();
        $('#plantillalogoderecho').dropify().data('dropify').clearElement();
        // $('#plantillalogoderecho').dropify().data('dropify').destroy();
        // $('#plantillalogoderecho').dropify().data('dropify').init();
    }
    else {
        // $('#plantillalogoderecho').attr('data-height', 400);
        // $('#plantillalogoderecho').attr('data-default-file', imagenUrl);
        $('#plantillalogoderecho').dropify({
            messages: {
                'default': 'Arrastre la imagen aquí o haga click',
                'replace': 'Arrastre la imagen o haga clic para reemplazar',
                'remove': 'Quitar',
                'error': 'Ooops, ha ocurrido un error.'
            },
            error: {
                'fileSize': 'Demasiado grande ({{ value }} max).',
                'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
            }
        });
    }
});


$(".input_numberformat").focus(function () {
    $(this).val($(this).val().replaceAll(",", ""));
});


function formatear(input) {
    var nombre_input = $('#' + input).attr('id')

    if ($('#' + input).val() && $.isNumeric($('#' + input).val())) {
        var cifra = $('#' + input).val().split(".");
        var vueltas = 0;
        var resultado = '';


        for (var i = cifra[0].length; i > 3; i -= 3) {
            resultado = ',' + cifra[0].substring((i - 3), i) + resultado;
            vueltas += 1;
        }


        if ((vueltas * 3) < cifra[0].length) {
            resultado = cifra[0].substring(0, (cifra[0].length - (vueltas * 3))) + resultado;
        }


        if (cifra[1]) {
            $('#' + input).val(resultado + "." + cifra[1]);
        }
        else {
            $('#' + input).val(resultado);
        }
    }
    else {
        if (nombre_input == "MONTO") {
            $('#' + input).val("");
            $('#' + input).attr('placeholder', 'Cantidad *')
        } else {
            $('#' + input).val("");
            $('#' + input).attr('placeholder', 'Solo acepta números')
        }
    }
}


//Guardar y Editar cliente
$("#boton_guardar_cliente").click(function () {
    var valida = this.form.checkValidity();
    if (valida) {
        swal({
            title: "¡Confirme que desea guardar!",
            text: "Información del cliente " + $('#cliente_RazonSocial').val(),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // Asegurarse de que el valor del switch se actualiza antes de enviar el formulario
                    requiereOrganizacional();

                    // enviar datos
                    $('#form_cliente').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 1,
                        },
                        resetForm: false,
                        success: function (data) {
                            // actulizar campo HIDDEN id
                            $("#cliente_id").val(data.id);
                            cliente_id = data.id;

                            // actualizar tabla
                            $("#tabla_clientes").DataTable().ajax.reload();

                            //Cargar la tabla de contratos
                            tabla_clientecontratos(cliente_id)

                            // actualiza boton
                            $('#boton_guardar_cliente').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Correcto",
                                text: "Información guardada correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                            if ($('#listaContratos').hasClass('ocultar')) {
                                $('#listaContratos').removeClass('ocultar')
                                $('#listaContratos').addClass('mostrar');
                            }
                        },
                        beforeSend: function () {
                            $('#boton_guardar_cliente').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function (data) {
                            // actualiza boton
                            $('#boton_guardar_cliente').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al guardar la información",
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
                } else {
                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        return false;
    }
});




$(document).ready(function () {
    $('#tabla_clientes tbody').on('click', 'td.Editar_cliente', function () {
        var tr = $(this).closest('tr');
        var row = TablaClientes.row(tr);

        // Mostrar la tabla de los contratos
        if ($('#listaContratos').hasClass('ocultar')) {
            $('#listaContratos').removeClass('ocultar');
            $('#listaContratos').addClass('mostrar');
        }

        // Borrar formulario
        $('#form_cliente').each(function () {
            this.reset();
        });



        // Resetear selectores de etiquetas y opciones
        $('.etiqueta-select').val('');
        $('.opciones-select').val('');

        // Mostrar TAB
        $('#tab_info_cliente').css("display", 'block');

        // Llenar Campos del formulario CLIENTE
        $("#cliente_RazonSocial").val(row.data().cliente_RazonSocial);
        $("#cliente_NombreComercial").val(row.data().cliente_NombreComercial);
        $("#cliente_DomicilioFiscal").val(row.data().cliente_DomicilioFiscal);
        $("#cliente_RazonSocialContrato").val(row.data().cliente_RazonSocialContrato);
        $("#cliente_LineaNegocios").val(row.data().cliente_LineaNegocios);
        $("#cliente_GiroComercial").val(row.data().cliente_GiroComercial);
        $("#cliente_Rfc").val(row.data().cliente_Rfc);
        $("#cliente_CiudadPais").val(row.data().cliente_CiudadPais);
        $("#cliente_RepresentanteLegal").val(row.data().cliente_RepresentanteLegal);
        $("#cliente_PaginaWeb").val(row.data().cliente_PaginaWeb);

        $("#region_id").val(row.data().region_id);
        $("#cliente_Pais").val(row.data().cliente_Pais);
        $("#subdireccion_id").val(row.data().subdireccion_id);
        $("#gerencia_id").val(row.data().gerencia_id);
        $("#activo_id").val(row.data().activo_id);

        $("#cliente_cp").val(row.data().cliente_cp);

        // Actualizar el estado del switch y el campo oculto
        var requiereEstructura = row.data().requiere_estructuraCliente;
        if (requiereEstructura === 'Si') {
            $('#requiere_estructuraCliente').prop('checked', true);
            $('#requiere_estructuraCliente_activo').val('Si');
            $('.selector-group').show();
        } else {
            $('#requiere_estructuraCliente').prop('checked', false);
            $('#requiere_estructuraCliente_activo').val('No');
            $('.selector-group').hide();
        }

        // Llamar a la función para cargar la estructura del cliente
        cargarEstructuraCliente(row.data().id);

        // Actualizar campo HIDDEN id, form cliente
        cliente_id = row.data().id;
        $("#cliente_id").val(row.data().id);

        // Consultar tablas informacion del cliente - Contratos
        tabla_clientecontratos(row.data().id);

        // Verifica bloqueo del cliente
        if (parseInt(row.data().cliente_Bloqueado) == 0) { // desbloqueado
            cliente_Bloqueado = 0;
            $("#boton_bloquear_cliente").val(0);
            $("#boton_bloquear_cliente").removeClass('btn-warning');
            $("#boton_bloquear_cliente").addClass('btn-success');
            $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> cliente desbloqueado para edición');

            // Activar botones
            $(".boton_modulocliente").attr('disabled', false);
            $(".boton_modulocliente").removeClass('btn-secondary');
            $(".boton_modulocliente").addClass('btn-danger');
        } else {
            cliente_Bloqueado = 1;
            $("#boton_bloquear_cliente").val(1);
            $("#boton_bloquear_cliente").removeClass('btn-success');
            $("#boton_bloquear_cliente").addClass('btn-warning');
            $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-lock"></i></span> cliente bloqueado para edición');

            // Desactivar botones
            $(".boton_modulocliente").attr('disabled', true);
            $(".boton_modulocliente").removeClass('btn-danger');
            $(".boton_modulocliente").addClass('btn-secondary');
        }

        // Colocar nombre del cliente
        $(".div_cliente_nombre").html(row.data().cliente_RazonSocial);
        $(".div_cliente_lineanegocio").html(row.data().cliente_LineaNegocios);

        // seleccionar TAB
        $("#tab_info_cliente").click();
    });
});




function bloqueo_cliente(opcion) {
    var valor = parseInt(opcion);
    var mensaje_1 = '';
    var mensaje_2 = '';

    if (valor == 1) {
        mensaje_1 = '¿Desbloquear cliente?';
        mensaje_2 = 'Desbloquear cliente para edición';
    }
    else {
        mensaje_1 = '¿Bloquear cliente?';
        mensaje_2 = 'Bloquear cliente para edición';
    }

    // CONFIRMAR
    swal({
        title: mensaje_1,
        text: mensaje_2,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            //Enviar datos
            $.ajax({
                type: "get",
                dataType: "json",
                url: "/clientebloqueo/" + $("#cliente_id").val() + "/" + valor,
                data: {},
                cache: false,
                success: function (dato) {
                    // Actualiza tabla
                    tabla_clientes();

                    // INVERTIR DISEÑO DEL BOTON [BLOQUEO DEL PROVEEDOR]
                    if (parseInt(dato.cliente.cliente_Bloqueado) == 0) {
                        cliente_Bloqueado = 0;
                        $("#boton_bloquear_cliente").val(0);
                        $("#boton_bloquear_cliente").removeClass('btn-warning');
                        $("#boton_bloquear_cliente").addClass('btn-success');
                        $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> cliente desbloqueado para edición');

                        // Activar botones
                        $(".boton_modulocliente").attr('disabled', false);
                        $(".boton_modulocliente").removeClass('btn-secondary');
                        $(".boton_modulocliente").addClass('btn-danger');
                    }
                    else {
                        cliente_Bloqueado = 1;
                        $("#boton_bloquear_cliente").val(1);
                        $("#boton_bloquear_cliente").removeClass('btn-success');
                        $("#boton_bloquear_cliente").addClass('btn-warning');
                        $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-lock"></i></span> cliente bloqueado para edición');

                        // Desactivar botones
                        $(".boton_modulocliente").attr('disabled', true);
                        $(".boton_modulocliente").removeClass('btn-danger');
                        $(".boton_modulocliente").addClass('btn-secondary');
                    }

                    // Actualizar tablas informacion del cliente
                    // tabla_documento(dato.cliente);

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
                beforeSend: function () {
                    $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cambiando estado del cliente');
                },
                error: function (dato) {
                    // VERIFICA BLOQUEO DEL cliente
                    if (valor == 0) //desbloqueado
                    {
                        $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> cliente desbloqueado para edición');
                    }
                    else {
                        $("#boton_bloquear_cliente").html('<span class="btn-label"><i class="fa fa-lock"></i></span> cliente bloqueado para edición');
                    }

                    // mensaje
                    swal({
                        title: "Error",
                        text: "" + dato.msj,
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
            return false;
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
}


//======================================================================

dataContrato = {
    contrato_id: contrato_id,
}
function tabla_clienteconvenios(contrato_id) {

    return new Promise(function (resolve, reject) {

        try {
            var ruta = "/clientetablaconvenios/" + contrato_id;

            if (datatable_convenios != null) {
                dataContrato = {
                    contrato_id: contrato_id,
                }
                datatable_convenios.clear().draw();
                datatable_convenios.ajax.url(ruta).load();
                // datatable_convenios.ajax.reload();
            }
            else {
                dataContrato = {
                    contrato_id: contrato_id,
                }
                var numeroejecucion = 1;
                datatable_convenios = $('#tabla_clienteconvenios').DataTable({
                    ajax: {
                        url: ruta,
                        type: "get",
                        cache: false,
                        dataType: "json",
                        data: function (d) {
                            return $.extend(d, dataContrato);
                        },
                        dataSrc: function (json) {
                            // alert("Done! "+json.msj);
                            return json.data;
                        },
                        error: function (xhr, error, code) {
                            console.log('error en datatable_convenios ' + code);
                            if (numeroejecucion <= 1) {
                                dataContrato = {
                                    contrato_id: contrato_id,
                                }
                                tabla_clienteconvenios(contrato_id);
                                numeroejecucion += 1;

                            }
                        }
                    },
                    columns: [
                        // {
                        //     data: "id" 
                        // },
                        {
                            data: "numero_registro",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "tipo",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "montomxn",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "montousd",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "vigencia",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "boton_editar",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "boton_eliminar",
                            defaultContent: "-",
                            orderable: false,
                        }
                    ],
                    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                    rowsGroup: [1], //agrupar filas
                    order: [[0, "ASC"]],
                    ordering: false,
                    processing: true,
                    searching: false,
                    paging: false,
                    responsive: true,
                    language: {
                        lengthMenu: "Mostrar _MENU_ Registros",
                        zeroRecords: "No se encontraron registros",
                        info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                        infoEmpty: "No se encontraron registros",
                        infoFiltered: "(Filtrado de _MAX_ registros)",
                        emptyTable: "No hay datos disponibles en la tabla",
                        loadingRecords: "Cargando datos....",
                        processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                        search: "Buscar",
                        paginate: {
                            first: "Primera",
                            last: "Ultima",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    // preDrawCallback: (setting, data) => {
                    //     // console.log({data:data, tydata: typeof(data)})

                    //     convenio_id = -1
                    // },
                    // rowCallback: function(row, data, index)
                    // {   

                    //     console.log({ data: data, tydata: typeof (data) })
                    //     convenio_id = 1;


                    // },
                });
            }

            // Tooltip en DataTable
            datatable_convenios.on('draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
                resolve(1);

            });






        }
        catch (exception) {
            dataContrato = {
                contrato_id: contrato_id,
            }
            tabla_clienteconvenios(contrato_id);

            reject(1)
        }

    });

}



$("#boton_nuevo_convenio").click(function () {
    if (puedeCerrar == 1) {
        // Borrar formulario
        $('#form_convenio').each(function () {
            this.reset();
        });

        // HIDDEN
        $("#convenio_id").val(0);

        // Abrir modal
        $('#modal_convenio').modal({ backdrop: false });
    } else {
        swal({
            title: "No es posible crear un Convenio de ampliación",
            text: "No existe un documento de cierre o aún no se ha autorizado el documento.",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 2000,
            showConfirmButton: false
        });
    }
});


//Guardar y Editar cliente
$("#boton_guardar_convenio").click(function () {
    var valida = this.form.checkValidity();
    if (valida) {
        swal({
            title: "¡Confirme que desea guardar!",
            text: "Convenio de ampliación",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_convenio').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 6,
                            CONTRATO_ID: contrato_id,
                        },
                        resetForm: false,
                        success: function (data) {
                            // actualizar tabla
                            datatable_convenios.ajax.reload();
                            datatable_contratos.ajax.reload();


                            // mensaje
                            swal({
                                title: "Correcto",
                                text: "Información guardada correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // actualiza boton
                            $('#boton_guardar_convenio').html('Guardar <i class="fa fa-save"></i>');

                            // cerrar modal
                            $('#modal_convenio').modal('hide');
                        },
                        beforeSend: function () {
                            $('#boton_guardar_convenio').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function (data) {

                            // actualiza boton
                            $('#boton_guardar_convenio').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al guardar la información",
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
                else {
                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        return false;
    }
});


$('#tabla_clienteconvenios tbody').on('click', 'td>button.editar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_convenios.row(tr);

    $('#form_convenio').each(function () {
        this.reset();
    });

    // HIDDEN
    $("#convenio_id").val(row.data().id);

    // CAMPOS
    $("#clienteconvenio_montomxn").val(row.data().clienteconvenio_montomxn);
    $("#clienteconvenio_montousd").val(row.data().clienteconvenio_montousd);
    $("#clienteconvenio_vigencia").val(row.data().clienteconvenio_vigencia);


    // abrir modal
    $('#modal_convenio').modal({ backdrop: false });
});


$('#tabla_clienteconvenios tbody').on('click', 'td>button.eliminar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_convenios.row(tr);

    swal({
        title: "¿Eliminar convenio?",
        text: "" + row.data().tipo,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientetablaconvenioseliminar/" + row.data().id,
                data: {},
                cache: false,
                success: function (dato) {
                    datatable_convenios.ajax.reload();

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: "" + dato.msj,
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , falsef
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function (dato) {
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});


//======================================================================


function tabla_documento(contrato_id) {
    try {
        var ruta = "/tablaclientedocumentos/" + contrato_id;

        if (TablaDocumentos != null) {
            TablaDocumentos.clear().draw();
            TablaDocumentos.ajax.url(ruta).load();
        }
        else {
            var numeroejecucion = 1;
            TablaDocumentos = $('#tabla_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        // console.log(xhr); console.log(code);
                        console.log('error en tabla_documento');
                        if (numeroejecucion <= 1) {
                            tabla_documento(contrato_id);
                            numeroejecucion += 1;
                        }
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "numero_registro"
                    },
                    {
                        "data": "clienteDocumento_Nombre"
                    },
                    {
                        "className": 'Pdf',
                        "orderable": false,
                        "data": null,
                        "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>'
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    },
                    {
                        "className": 'Eliminar',
                        "orderable": false,
                        "data": 'boton_eliminar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, -1], [10, 20, "Todos"]],
                "order": [[0, "desc"]],
                "ordering": true,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
        tabla_documento(contrato_id);
    }
}


// precionar boton nuevo documento
$("#boton_nuevo_documento").click(function () {
    // valida si existe un cliente  seleccionado
    if (parseInt(contrato_id) > 0) {
        // Borrar formulario
        $('#form_documento').each(function () {
            this.reset();
        });

        // actualizar campos HIDDEN
        $("#documento_id").val(0);
        // $("#documento_cliente_id").val($("#cliente_id").val());
        $("#clienteDocumento_Eliminado").val(0);

        // requerir campo DOCUMENTO
        $('#documento').attr('required', true);

        // Abrir modal
        $('#modal_documento').modal({ backdrop: false });
    }
    else {
        // mensaje
        swal({
            title: "No disponible",
            text: "Aun no has seleccionado o guardado los datos de un cliente",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});


//Guardar y Editar documento
$("#boton_guardar_documento").click(function () {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        if ($("#documento").val() != "") {
            // Tipo archivo
            var archivo = $("#documento").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if (extension == ".pdf" || extension == ".PDF") {
                guardar = 1;
            }
            else {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto " + extension,
                    text: "Solo se pueden cargar archivos tipo .PDF",
                    type: "warning", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 3000,
                    showConfirmButton: false
                });

                guardar = 0;
                return false;
            }
        }
        else {
            guardar = 1;
        }

        // guardar
        if (guardar == 1) {
            // enviar datos
            $('#form_documento').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/cliente",
                data: {
                    opcion: 2,
                    CONTRATO_ID: contrato_id,
                },
                resetForm: false,
                success: function (data) {

                    // actualizar tabla
                    tabla_documento(contrato_id);

                    // actualiza boton
                    $('#boton_guardar_documento').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: "Documento guardado correctamente",
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1200,
                        showConfirmButton: false
                    });

                    // cerrar modal
                    $('#modal_documento').modal('hide');
                },
                beforeSend: function () {
                    $('#boton_guardar_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function (data) {

                    // actualiza boton
                    $('#boton_guardar_documento').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Error",
                        text: "Error al guardar el documento",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1200,
                        showConfirmButton: false
                    });

                    return false;
                }
            }).submit();
            return false;
        }

    }
});


// mostrar documento
$(document).ready(function () {
    $('#tabla_documentos tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row(tr);

        // abrir modal
        // $('#modal_visor').modal({backdrop:false, keyboard:false});
        $('#modal_visor').modal({ backdrop: false });

        // Titulo del visor
        $('#nombre_documento_visor').html(row.data().clienteDocumento_Nombre);

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/clientedocumentopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/clientedocumentopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/clientedocumentopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(cliente_Bloqueado) == 0) {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/clientedocumentopdf/' + row.data().id);
        }
        else {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/clientedocumentopdf/' + row.data().id);
        }
    });
});


// Vaciar contenido del visor al cerrar modal
$(document).ready(function () {
    $("#modalvisor_boton_cerrar").click(function () {
        $('#visor_documento').attr('src', '/assets/images/cargando.gif');
    });
});


// Seleccionar documento
$(document).ready(function () {
    // mostrar PDF
    $('#tabla_documentos tbody').on('click', 'td.Editar', function () {
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row(tr);

        if (parseInt(row.data().accion_activa) > 0) {
            // Borrar formulario
            $('#form_documento').each(function () {
                this.reset();
            });

            // Llenar campos form
            $("#clienteDocumento_Nombre").val(row.data().clienteDocumento_Nombre);
            // $("#documento").val(row.data().clienteDocumento_SoportePDF);
            $("#clienteDocumento_Eliminado").val(row.data().clienteDocumento_Eliminado);

            // actualizar campos HIDDEN
            $("#documento_id").val(row.data().id);
            // $("#documento_cliente_id").val(row.data().cliente_id);

            // requerir campo DOCUMENTO
            $('#documento').attr('required', false);

            // abrir modal
            $('#modal_documento').modal({ backdrop: false });
        }
    });
});


// Eliminar documento
$(document).ready(function () {
    $('#tabla_documentos tbody').on('click', 'td.Eliminar', function () {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row(tr);

        if (parseInt(row.data().accion_activa) > 0) {

            //Parameter
            swal({
                title: "¿Eliminar documento?",
                text: "" + row.data().clienteDocumento_Nombre,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Eliminar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    // Campos del formulario cliente
                    $("#documento_id").val(row.data().id);
                    // $("#documento_cliente_id").val(row.data().cliente_id);
                    $("#clienteDocumento_Nombre").val(row.data().clienteDocumento_Nombre);
                    // $("#documento").val(row.data().clienteDocumento_SoportePDF);
                    // modificar campo eliminado
                    $('#clienteDocumento_Eliminado').val(1);

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar form
                    $('#form_documento').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 3,
                        },
                        resetForm: false,
                        success: function (data) {
                            // actualizar DATATABLE
                            tabla_documento(contrato_id);

                            // mensaje
                            swal({
                                title: "Eliminado",
                                text: "Registro eliminado correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        beforeSend: function () {
                            //espera
                        },
                        error: function (data) {
                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al eliminar la información",
                                type: "error", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }).submit();
                    return false;
                }
                else {
                    // modificar valor del campo
                    $('#clienteDocumento_Eliminado').val(0);

                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});


//======================================================================


// Guardar y Editar plantilla
$("#boton_guardar_plantilla").click(function () {
    // valida si existe un contrato  seleccionado
    if (parseInt(contrato_id) > 0) {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            if ($('#plantillalogoizquierdo').val() == '') {
                RUTA_PLANTILLA_IZQUIERDO = $('#banco_imagenes_izquierdo').val()

            } else {

                RUTA_PLANTILLA_IZQUIERDO = null;
            }

            if ($('#plantillalogoderecho').val() == '') {
                RUTA_PLANTILLA_DERECHO = $('#banco_imagenes_derecho').val()
            } else {
                RUTA_PLANTILLA_DERECHO = null;
            }


            // enviar datos
            $('#form_plantilla').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/cliente",
                data: {
                    opcion: 4,
                    contrato_id: contrato_id,
                    RUTA_PLANTILLA_IZQUIERDO: RUTA_PLANTILLA_IZQUIERDO,
                    RUTA_PLANTILLA_DERECHO: RUTA_PLANTILLA_DERECHO
                },
                resetForm: false,
                success: function (data) {
                    // actualiza boton
                    $('#boton_guardar_plantilla').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: "Información guardada correctamente",
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // cerrar modal
                    $('#modal_documento').modal('hide');
                },
                beforeSend: function () {
                    $('#boton_guardar_plantilla').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function (data) {

                    // actualiza boton
                    $('#boton_guardar_plantilla').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Error",
                        text: "Error al guardar la información",
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
    }
    else {
        // mensaje
        swal({
            title: "No disponible",
            text: "Aun no has seleccionado o guardado los datos de un contrato",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});


//======================================================================


function tabla_clientepartidas(contrato_id) {
    try {
        var ruta = "/clientetablapartidas/" + contrato_id + '/' + 0;

        if (datatable_partidas != null) {
            datatable_partidas.clear().draw();
            datatable_partidas.ajax.url(ruta).load();
            // datatable_partidas.ajax.reload();
        }
        else {
            var numeroejecucion = 1;
            datatable_partidas = $('#tabla_clientepartidas').DataTable({
                ajax: {
                    url: ruta,
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {},
                    dataSrc: function (json) {
                        // alert("Done! "+json.msj);
                        return json.data;
                    },
                    error: function (xhr, error, code) {
                        console.log('error en datatable_partidas ' + code);
                        if (numeroejecucion <= 1) {
                            tabla_clientepartidas(contrato_id);
                            numeroejecucion += 1;
                        }
                    }
                },
                createdRow: function (row, data, dataIndex) {
                    if (data.ACTIVO == 0) {
                        $(row).css({

                            // "font-style" : "italic",
                            // "color": "##e9ecef",
                            "background-color": "#e9ecef",
                            "cursor": "not-allowed",
                            "opacity": "0.9"


                        });

                    }
                },
                columns: [
                    // {
                    //     data: "id" 
                    // },
                    {
                        data: "numero_registro",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "clientepartidas_tipotexto",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "clientepartidas_parametro",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "clientepartidas_descripcion",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "UNIDAD_MEDIDA",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    }, {
                        data: "PRECIO",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "BOTONES",
                        defaultContent: "-",
                        orderable: false,
                    }
                ],
                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                rowsGroup: [1], //agrupar filas
                order: [[0, "ASC"]],
                ordering: false,
                processing: true,
                searching: true,
                paging: true,
                scrollCollapse: true,
                scrollY: '50vh',
                responsive: true,
                language: {
                    lengthMenu: "Mostrar _MENU_ Registros",
                    zeroRecords: "No se encontraron registros",
                    info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                    infoEmpty: "No se encontraron registros",
                    infoFiltered: "(Filtrado de _MAX_ registros)",
                    emptyTable: "No hay datos disponibles en la tabla",
                    loadingRecords: "Cargando datos....",
                    processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    search: "Buscar",
                    paginate: {
                        first: "Primera",
                        last: "Ultima",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                rowCallback: function (row, data, index) {
                    // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

                    // if(data.reporteiluminacionpuntos_nopunto == 2)
                    // {
                    //  $(row).find('td:eq(12)').css('background', 'red');
                    //  $(row).find('td:eq(12)').css('color', 'white');
                    // }

                    // $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
                    // $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
                    // $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
                    // $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
                    // $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
                    // $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

                    // $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
                    // $(row).find('td:eq(15)').css('color', '#FFFFFF');
                },
            });
        }

        // Tooltip en DataTable
        datatable_partidas.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    catch (exception) {
        tabla_clientepartidas(contrato_id);
    }
}


$("#boton_nueva_partida").click(function () {
    if (parseInt(contrato_id) > 0) // valida si existe un cliente seleccionado
    {
        $('#form_partida').each(function () {
            this.reset();
        });

        // actualizar campos HIDDEN
        $("#partida_id").val(0);
        $("#partida_cliente_id").val($("#cliente_id").val());


        // Inicializa campo select
        $("#catprueba_id").html('<option value=""></option>');

        // Abrir modal
        $('#modal_partida').modal({ backdrop: false });
    }
    else {
        // mensaje
        swal({
            title: "No disponible",
            text: "Aun no has seleccionado o guardado los datos de un contrato",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});


function activa_parametro(clientepartidas_tipo) {
    if (parseInt(clientepartidas_tipo) > 0) {
        var selectoption = '<option value=""></option>';

        if (parseInt(clientepartidas_tipo) == 1) // Reconocimiento
        {
            selectoption += '<option value="1">Físicos</option>';
            selectoption += '<option value="2">Químicos</option>';
        }
        else {
            catpruebas.forEach(function (value, index) // catpruebas obtenido en el HTML
            {
                if (parseInt(value.id) == 9 || parseInt(value.id) == 10) // Agua, Hielo
                {
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + '</option>';
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + ' Fisicoquímico</option>';
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + ' Microbiológico</option>';
                }
                else {
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + '</option>';
                }
            });
        }

        $("#catprueba_id").html(selectoption);
    }
    else {
        $("#catprueba_id").html('<option value=""></option>'); // Inicializa campo select
    }
}

function iniciarBancoImagenes() {
    var selectoption = '<option value=""></option>';


    banco_imagenes.forEach(function (value, index) {

        selectoption += '<option name="' + value.ID_PLANTILLA_IMAGEN + '" value="' + value.RUTA_IMAGEN + '" >' + value.NOMBRE_PLANTILLA + '</option>';

    });


    $("#banco_imagenes_derecho").html(selectoption);
    $("#banco_imagenes_izquierdo").html(selectoption);

}

//Esta es igual que la otra funcion solo que estas son para las partidas de los convenios
function activa_parametro_convenios(clientepartidas_tipo) {
    if (parseInt(clientepartidas_tipo) > 0) {
        var selectoption = '<option value=""></option>';

        if (parseInt(clientepartidas_tipo) == 1) // Reconocimiento
        {
            selectoption += '<option value="1">Físicos</option>';
            selectoption += '<option value="2">Químicos</option>';
        }
        else {
            catpruebas.forEach(function (value, index) // catpruebas obtenido en el HTML
            {
                if (parseInt(value.id) == 9 || parseInt(value.id) == 10) // Agua, Hielo
                {
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + '</option>';
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + ' Fisicoquímico</option>';
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + ' Microbiológico</option>';
                }
                else {
                    selectoption += '<option value="' + value.id + '">' + value.catPrueba_Nombre + '</option>';
                }
            });
        }

        $("#catprueba_id_convenio").html(selectoption);
    }
    else {
        $("#catprueba_id_convenio").html('<option value=""></option>'); // Inicializa campo select
    }
}

$("#boton_guardar_partida").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // Valida envio de datos
        swal({
            title: "¡Confirme guardar",
            text: "Partida  de " + $("#catprueba_id option:selected").text(),
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_partida').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/cliente',
                    data: {
                        opcion: 5, //PARTIDAS
                        CONTRATO_ID: contrato_id,
                        CONVENIO: 0, //INDICAMOS QUE ESTA PARTIDA NO ES DE CONVENIOS SI NO DEL CONTRATO
                        parametro_nombre: $("#catprueba_id option:selected").text(),
                    },
                    resetForm: false,
                    success: function (dato) {
                        tabla_clientepartidas(contrato_id);

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

                        // actualiza boton
                        $('#boton_guardar_partida').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_partida').attr('disabled', false);

                        // cerrar modal
                        $('#modal_partida').modal('hide');
                    },
                    beforeSend: function () {
                        $('#boton_guardar_partida').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        $('#boton_guardar_partida').attr('disabled', true);
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_guardar_partida').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_partida').attr('disabled', false);

                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
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
            else {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
        return false;
    }
});


$('#tabla_clientepartidas tbody').on('click', 'td>button.boton_editar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas.row(tr);

    activa_parametro(row.data().clientepartidas_tipo);

    $('#form_partida').each(function () {
        this.reset();
    });

    $("#partida_id").val(row.data().id);
    $("#clientepartidas_tipo").val(row.data().clientepartidas_tipo);
    // $("#catprueba_id").val(row.data().catprueba_id);

    setTimeout(function () {
        $('#catprueba_id option:contains(' + row.data().clientepartidas_nombre + ')').attr('selected', 'selected');
    }, 500);

    $("#clientepartidas_descripcion").val(row.data().clientepartidas_descripcion);
    $("#UNIDAD_MEDIDA").val(row.data().UNIDAD_MEDIDA);
    $("#PRECIO_UNITARIO").val(row.data().PRECIO_UNITARIO);



    // abrir modal
    $('#modal_partida').modal({ backdrop: false });
});


$('#tabla_clientepartidas tbody').on('click', 'td>button.boton_eliminar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas.row(tr);

    swal({
        title: "¿Eliminar partida?",
        text: "" + row.data().clientepartidas_descripcion,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientepartidaeliminar/" + row.data().id,
                data: {},
                cache: false,
                success: function (dato) {
                    tabla_clientepartidas(contrato_id);

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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});


//Desactivar la partida
$('#tabla_clientepartidas tbody').on('click', 'td>button.boton_desactivar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas.row(tr);

    swal({
        title: "¿Está seguro de desactivar la partida?",
        text: "Si existe un convenio, no se podrá usar esta partida",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Desactivar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientepartidaBloqueo/" + row.data().id + '/' + row.data().ACTIVO,
                data: {},
                cache: false,
                success: function (dato) {
                    tabla_clientepartidas(contrato_id);

                    // mensaje
                    swal({
                        title: "Partida desactivada correctamente",
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});

//Activar la partida
$('#tabla_clientepartidas tbody').on('click', 'td>button.boton_activar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas.row(tr);

    swal({
        title: "¿Está seguro de activar la partida?",
        text: "Si existe un convenio, se podrá usar esta partida",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Activar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientepartidaBloqueo/" + row.data().id + '/' + row.data().ACTIVO,
                data: {},
                cache: false,
                success: function (dato) {
                    tabla_clientepartidas(contrato_id);

                    // mensaje
                    swal({
                        title: "Partida Activada correctamente",
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});



//Boton para abrir el modal para un nuevo contrato
$("#boton_nuevo_contrato").click(function () {
    if (parseInt($("#cliente_id").val()) > 0) {
        // Borrar formulario
        $('#form_contrato').each(function () {
            this.reset();
        });

        // HIDDEN
        $("#contrato_id").val(0);

        // Abrir modal
        $('#modal_contrato').modal({ backdrop: false });

    } else {

        swal({
            title: "No disponible",
            text: "Aun no has seleccionado o guardado los datos de un cliente",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }

});

//Boton para guardar un nuevo contrato
$("#boton_guardar_contrato").on('click', function () {

    var valida = this.form.checkValidity();
    if (valida) {
        swal({
            title: "¿Está seguro de guardar este nuevo contrato?",
            text: "Numero de contrato: " + $('#NUMERO_CONTRATO').val(),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function (isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_contrato').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 7,
                            CLIENTE_ID: cliente_id,
                        },
                        resetForm: false,
                        success: function (data) {
                            // actualizar tabla

                            datatable_contratos.ajax.reload();
                            $('#documentos_contrato').css('display', 'none');

                            // mensaje
                            swal({
                                title: "Contrato guardado con éxito",
                                text: "Información guardada correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // actualiza boton
                            $('#boton_guardar_contrato').html('Guardar <i class="fa fa-save"></i>');

                            // cerrar modal
                            $('#modal_contrato').modal('hide');
                        },
                        beforeSend: function () {
                            $('#boton_guardar_contrato').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function (data) {

                            // actualiza boton
                            $('#boton_guardar_contrato').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al guardar la información",
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
                else {
                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        return false;
    }
});

//Funcion para inicializar la tabla de los contratos
function tabla_clientecontratos(cliente_id) {
    try {
        var ruta = "/clientetablacontratos/" + cliente_id;

        if (datatable_contratos != null) {
            datatable_contratos.clear().draw();
            datatable_contratos.ajax.url(ruta).load();
            // datatable_convenios.ajax.reload();
        }
        else {
            var numeroejecucion = 1;
            datatable_contratos = $('#tabla_clientecontratos').DataTable({
                ajax: {
                    url: ruta,
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {},
                    dataSrc: function (json) {
                        // alert("Done! "+json.msj);
                        return json.data;
                    },
                    error: function (xhr, error, code) {
                        console.log('error en datatable_contratos ' + code);
                        if (numeroejecucion <= 1) {
                            tabla_clientecontratos(cliente_id);
                            numeroejecucion += 1;
                        }
                    }
                },
                createdRow: function (row, data, dataIndex) {
                    if (data.CONCLUIDO == 1) {
                        $(row).css({


                            "background-color": "#E5FFEA",
                            "border": "1px solid #66CC99",
                            // "color": "#000",
                            "opacity": "0.9"


                        });

                    } else if (data.CONCLUIDO == 0 && convertirFecha(data.FECHA_FIN) < convertirFecha(fechaActual) && data.BIT_CONVENIO == 0) {
                        $(row).css({
                            "background-color": "#FFCCCC",
                            "border": "1px solid #FF0000",
                            "color": "#FF0000",
                            "opacity": "0.8"

                        });

                    } else if (data.CONCLUIDO == 0 && convertirFecha(data.FECHA_FIN) < convertirFecha(fechaActual) && data.BIT_CONVENIO == 1) {


                        $(row).css({

                            //  "background-color": "#E5FFEA",
                            //  "border": "1px solid #66CC99",
                            "color": "#FF0000"
                            //  "opacity": "0.9"


                        });
                    }
                },
                columns: [
                    // {
                    //     data: "id" 
                    // },
                    {
                        data: "NUMERO_REGISTROS",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "TIPO_SERVICIO_TEXTO",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "NUMERO_CONTRATO",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "DESCRIPCION_CONTRATO",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "TIENE_CONVENIO",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "FECHAS",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    {
                        data: "BOTONES",
                        defaultContent: "-",
                        // className: '',
                        orderable: false,
                    },
                    // {
                    //     data: "boton_eliminar",
                    //     defaultContent: "-",
                    //     orderable: false,
                    // }
                ],
                "lengthMenu": [[10, 20, -1], [10, 20, "Todos"]],
                // lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // rowsGroup: [1], //agrupar filas
                "order": [[0, "desc"]],
                "ordering": true,
                "processing": true,
                // scrollX: true,
                // scrollY: 200,
                language: {
                    lengthMenu: "Mostrar _MENU_ Registros",
                    zeroRecords: "No se encontraron registros",
                    info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                    infoEmpty: "No se encontraron registros",
                    infoFiltered: "(Filtrado de _MAX_ registros)",
                    emptyTable: "No hay datos disponibles en la tabla",
                    loadingRecords: "Cargando datos....",
                    processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    search: "Buscar",
                    paginate: {
                        first: "Primera",
                        last: "Ultima",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                rowCallback: function (row, data, index) {
                    // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

                    // if(data.reporteiluminacionpuntos_nopunto == 2)
                    // {
                    //  $(row).find('td:eq(12)').css('background', 'red');
                    //  $(row).find('td:eq(12)').css('color', 'white');
                    // }

                    // $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
                    // $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
                    // $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
                    // $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
                    // $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
                    // $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

                    // $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
                    // $(row).find('td:eq(15)').css('color', '#FFFFFF');
                },
            });
        }

        // Tooltip en DataTable
        datatable_contratos.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    catch (exception) {
        tabla_clientecontratos(cliente_id);
    }
}

//Boton para editar un contrato
$('#tabla_clientecontratos tbody').on('click', 'td>button.editar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_contratos.row(tr);

    $('#form_contrato').each(function () {
        this.reset();
    });

    // HIDDEN
    $("#CONTRATO_ID_PRINCIPAL").val(row.data().ID_CONTRATO);

    // CAMPOS
    $("#TIPO_SERVICIO").val(row.data().TIPO_SERVICIO);
    $("#NOMBRE_CONTACTO").val(row.data().NOMBRE_CONTACTO);
    $("#CARGO_CONTACTO").val(row.data().CARGO_CONTACTO);
    $("#CORREO_CONTACTO").val(row.data().CORREO_CONTACTO);
    $("#TELEFONO_CONTACTO").val(row.data().TELEFONO_CONTACTO);
    $("#CELULAR_CONTACTO").val(row.data().CELULAR_CONTACTO);
    $("#NUMERO_CONTRATO").val(row.data().NUMERO_CONTRATO);
    $("#DESCRIPCION_CONTRATO").val(row.data().DESCRIPCION_CONTRATO);
    $("#FECHA_INICIO").val(row.data().FECHA_INICIO);
    $("#FECHA_FIN").val(row.data().FECHA_FIN);
    if (row.data().MONEDA_MONTO == 'MXN') {

        $("#MONEDA_MONTOMNX").prop("checked", true);

    } else {
        $("#MONEDA_MONTOUSD").prop("checked", true);

    }
    $("#MONTO").val(row.data().MONTO_SINFORMATO);

    $("#REPRESENTANTE_LEGAL_CONTRATO").val(row.data().REPRESENTANTE_LEGAL_CONTRATO);



    // abrir modal
    $('#modal_contrato').modal({ backdrop: false });
});

//Boton para eliminar un contrato
$('#tabla_clientecontratos tbody').on('click', 'td>button.eliminar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_contratos.row(tr);

    swal({
        title: "¿Está seguro de eliminar este contrato?",
        text: "No. de contrato: " + row.data().NUMERO_CONTRATO,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientetablacontratoeliminar/" + row.data().ID_CONTRATO,
                data: {},
                cache: false,
                success: function (dato) {
                    datatable_contratos.ajax.reload();
                    $('#documentos_contrato').css('display', 'none');

                    // mensaje
                    swal({
                        title: "Contrato eliminado correctamente",
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1200,
                        showConfirmButton: false
                    });
                },
                error: function (dato) {
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});

//Boton para cargar la informacion del contrato
$('#tabla_clientecontratos tbody').on('click', 'td>button.informacion', function () {
    var tr = $(this).closest('tr');
    var row = datatable_contratos.row(tr);
    contrato_id = row.data().ID_CONTRATO;

    iniciarBancoImagenes()

    //Calculamos la duracion del contrato
    duracion = calcularDuracionContratos(row.data().FECHA_INICIO, row.data().FECHA_FIN) + ' días.'

    //Obtenemos o configuramos la informacion de la plantilla LOGOS, TITULO, ETC
    obtencionPlantilla(row.data())

    //Mostramos el boton para cerrar el contrato
    $(`#boton_finalizar_contrato`).removeClass("ocultar");
    $(`#boton_finalizar_contrato`).addClass("mostrar");

    //Modificamos los botones de las acciones en dado caso de que este concluido el contrato
    if (row.data().CONCLUIDO == 1 || cliente_Bloqueado == 1) {

        //Desactivar los botones
        if (cliente_Bloqueado == 1) {
            $('#boton_finalizar_contrato').html('<span class="btn-label"><i class="fa fa-check" aria-hidden="true"></i></span> Cerrar contrato');

        } else {
            $(`#boton_finalizar_contrato`).text("Contrato Finalizado");

        }
        $(`#boton_finalizar_contrato`).attr('disabled', true);

        $(".contrato").attr('disabled', true);
        $(".contrato").removeClass('btn-secondary');
        $(".contrato").addClass('btn-danger');


    } else {
        //Activar los bonotes
        $(".contrato").attr('disabled', false);
        $(".contrato").removeClass('btn-danger');
        $(".contrato").addClass('btn-secondary');

        $('#boton_finalizar_contrato').html('<span class="btn-label"><i class="fa fa-check" aria-hidden="true"></i></span> Cerrar contrato');
        $(`#boton_finalizar_contrato`).attr('disabled', false);
    }


    //Estilos de seleccion de contrato en la tabla
    $('#tabla_clientecontratos tbody tr').removeClass('active');
    tr.addClass('active');
    $('#documentos_contrato').css("display", 'block');

    //Infromacion de contrato
    $(".div_numero_contrato").text(row.data().NUMERO_CONTRATO);
    $(".div_descripcion_contrato").text(row.data().DESCRIPCION_CONTRATO);
    $("#contrato_fecha_inicio").text(row.data().FECHA_INICIO);
    $("#contrato_fecha_final").text(row.data().FECHA_FIN);
    $("#contrato_monto").text(row.data().MONTO);
    $("#contrato_nombre_contacto").text(row.data().NOMBRE_CONTACTO);
    $("#contrato_correo_contacto").text(row.data().CORREO_CONTACTO);
    $("#contrato_cargo").text(row.data().CARGO_CONTACTO);
    $("#contrato_telefono").text(row.data().TELEFONO_CONTACTO);
    $("#contrato_duracion").text(duracion);


    //Cargamos  todas la tablas de la informacion del contrato
    tabla_documento(contrato_id);
    tabla_clientepartidas(contrato_id);
    tabla_documento_cierre(contrato_id)
    tabla_anexos(contrato_id)

    //Despues de cargar toda la informacion del contrato la mostramos
    $('#steps_menu_tab1').click()
    $('#tab_info_contratos').css("display", 'block');
    $("#tab_info_contratos").click();


    $('html, body').animate({
        scrollTop: $('#encabezado_contrato').offset().top
    }, 'slow');

    //Cargamos el cronograma de una vez, para formar nuestros eventos
    mostrarActividadesCronograma()

});

//Modificaciones para cargar el catalago de imagenes
$('#banco_imagenes_derecho').on('change', function () {

    var archivo = $('#banco_imagenes_derecho').val();
    var ID_LOGO = $('#banco_imagenes_derecho option:selected').attr('name');
    var extension = archivo.substring(archivo.lastIndexOf("."));
    var imagenUrl = '/logoPlantilla/' + ID_LOGO + extension;

    $('#plantillalogoderecho').val('')

    if ($('#banco_imagenes_derecho').val() != '') {

        if ($('#plantillalogoderecho').data('dropify')) {



            $('#plantillalogoderecho').dropify().data('dropify').destroy();
            // $('.dropify-wrapper').css('height', 400);
            $('#plantillalogoderecho').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#plantillalogoderecho').dropify().data('dropify').init();
        }
        else {


            $('#plantillalogoderecho').attr('data-default-file', imagenUrl);
            $('#plantillalogoderecho').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });

        }
    } else {
        $('#plantillalogoderecho').dropify().data('dropify').resetPreview();
        $('#plantillalogoderecho').dropify().data('dropify').clearElement();
    }




})

$('#plantillalogoderecho').on('click', function (e) {

    if ($('#banco_imagenes_derecho').val() != '') {

        swal({
            title: "No disponible",
            text: "Tiene seleccionada una foto de la lista de logos, quite el logo seleccionado de la lista para continuar",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
        e.preventDefault();
    }

})


$('#banco_imagenes_izquierdo').on('change', function () {


    var archivo = $('#banco_imagenes_izquierdo').val();
    var ID_LOGO = $('#banco_imagenes_izquierdo option:selected').attr('name');
    var extension = archivo.substring(archivo.lastIndexOf("."));
    var imagenUrl = '/logoPlantilla/' + ID_LOGO + extension;

    $('#plantillalogoizquierdo').val('')

    if ($('#banco_imagenes_izquierdo').val() != '') {

        if ($('#plantillalogoizquierdo').data('dropify')) {
            $('#plantillalogoizquierdo').dropify().data('dropify').destroy();
            // $('.dropify-wrapper').css('height', 400);
            $('#plantillalogoizquierdo').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#plantillalogoizquierdo').dropify().data('dropify').init();
        }
        else {
            // $('#plantillalogoizquierdo').attr('data-height', 400);
            $('#plantillalogoizquierdo').attr('data-default-file', imagenUrl);
            $('#plantillalogoizquierdo').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }
    } else {
        $('#plantillalogoizquierdo').dropify().data('dropify').resetPreview();
        $('#plantillalogoizquierdo').dropify().data('dropify').clearElement();
    }


})

$('#plantillalogoizquierdo').on('click', function (e) {

    if ($('#banco_imagenes_izquierdo').val() != '') {

        swal({
            title: "No disponible",
            text: "Tiene seleccionada una foto de la lista de logos, quite el logo seleccionado de la lista para continuar",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
        e.preventDefault();
    }

})



function obtencionPlantilla(data) {

    if (data.CONTRATO_PLANTILLA_LOGOIZQUIERDO) {
        // obtener extencion archivo
        var archivo = data.CONTRATO_PLANTILLA_LOGOIZQUIERDO;
        var extension = archivo.substring(archivo.lastIndexOf("."));
        // Obtener FOTO
        var imagenUrl = '/clientelogo/1/' + data.ID_CONTRATO + extension;

        // Mostrar Foto en el INPUT
        if ($('#plantillalogoizquierdo').data('dropify')) {
            $('#plantillalogoizquierdo').dropify().data('dropify').destroy();
            // $('.dropify-wrapper').css('height', 400);
            $('#plantillalogoizquierdo').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#plantillalogoizquierdo').dropify().data('dropify').init();
        }
        else {
            // $('#plantillalogoizquierdo').attr('data-height', 400);
            $('#plantillalogoizquierdo').attr('data-default-file', imagenUrl);
            $('#plantillalogoizquierdo').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }

        // requerir campo FOTO
        $('#plantillalogoizquierdo').attr('required', false);

        //---------------------------------

        // obtener extencion archivo
        var archivo = data.CONTRATO_PLANTILLA_LOGODERECHO;
        var extension = archivo.substring(archivo.lastIndexOf("."));
        // Obtener FOTO
        var imagenUrl = '/clientelogo/2/' + data.ID_CONTRATO + extension;

        // Mostrar Foto en el INPUT
        if ($('#plantillalogoderecho').data('dropify')) {
            $('#plantillalogoderecho').dropify().data('dropify').destroy();
            // $('.dropify-wrapper').css('height', 400);
            $('#plantillalogoderecho').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#plantillalogoderecho').dropify().data('dropify').init();
        }
        else {
            // $('#plantillalogoderecho').attr('data-height', 400);
            $('#plantillalogoderecho').attr('data-default-file', imagenUrl);
            $('#plantillalogoderecho').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }

        // requerir campo FOTO
        $('#plantillalogoderecho').attr('required', false);

        //---------------------------------

        // Pie de página
        $("#cliente_plantillaempresaresponsable").val(data.CONTRATO_PLANTILLA_EMPRESARESPONSABLE);
        $("#cliente_plantillapiepagina").val(data.CONTRATO_PLANTILLA_PIEPAGINA);
    }
    else {
        // inicializar campos FOTOS
        if ($('#plantillalogoizquierdo').data('dropify')) {
            $('#plantillalogoizquierdo').dropify().data('dropify').resetPreview();
            $('#plantillalogoizquierdo').dropify().data('dropify').clearElement();
            // $('#plantillalogoizquierdo').dropify().data('dropify').destroy();
            // $('#plantillalogoizquierdo').dropify().data('dropify').init();
        }
        else {
            // $('#plantillalogoizquierdo').attr('data-height', 400);
            // $('#plantillalogoizquierdo').attr('data-default-file', imagenUrl);
            $('#plantillalogoizquierdo').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }

        // inicializar campos FOTOS
        if ($('#plantillalogoderecho').data('dropify')) {
            $('#plantillalogoderecho').dropify().data('dropify').resetPreview();
            $('#plantillalogoderecho').dropify().data('dropify').clearElement();
            // $('#plantillalogoderecho').dropify().data('dropify').destroy();
            // $('#plantillalogoderecho').dropify().data('dropify').init();
        }
        else {
            // $('#plantillalogoderecho').attr('data-height', 400);
            // $('#plantillalogoderecho').attr('data-default-file', imagenUrl);
            $('#plantillalogoderecho').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove': 'Quitar',
                    'error': 'Ooops, ha ocurrido un error.'
                },
                error: {
                    'fileSize': 'Demasiado grande ({{ value }} max).',
                    'minWidth': 'Ancho demasiado pequeño (min {{ value }}}px).',
                    'maxWidth': 'Ancho demasiado grande (max {{ value }}}px).',
                    'minHeight': 'Alto demasiado pequeño (min {{ value }}}px).',
                    'maxHeight': 'Alto demasiado grande (max {{ value }}px max).',
                    'imageFormat': 'Formato no permitido, sólo ({{ value }}).'
                }
            });
        }

        // Pie de página
        $("#cliente_plantillaempresaresponsable").val('');
        $("#cliente_plantillapiepagina").val('');
    }


    // $("#cliente_plantillaencabezado").val(data.CONTRATO_PLANTILLA_ENCABEZADO);
}


function calcularDuracionContratos(fecha1, fecha2) {


    var fecha1 = new Date(fecha1);
    var fecha2 = new Date(fecha2);

    // Calcular la diferencia en milisegundos
    var diferenciaEnMilisegundos = Math.abs(fecha2 - fecha1);

    // Convertir la diferencia de milisegundos a días
    var diasDeDiferencia = Math.ceil(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));


    return diasDeDiferencia
}

$("#boton_documento_cierre").click(function () {
    if (parseInt(contrato_id) > 0) // valida si existe un cliente seleccionado
    {
        $('#form_documento_cierre').each(function () {
            this.reset();
        });


        $("#documento_cierre_id").val(0);
        $("#ELIMINADO").val(0);


        // Abrir modal
        $('#modal_documento_cierre').modal({ backdrop: false });
    }
    else {
        // mensaje
        swal({
            title: "No disponible",
            text: "No existe un contrato seleccionado",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});


$('#boton_guardar_documento_cierre').click(function () {

    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        if ($("#documentoCierre").val() != "") {
            // Tipo archivo
            var archivo = $("#documentoCierre").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if (extension == ".pdf" || extension == ".PDF") {
                guardar = 1;
            }
            else {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto " + extension,
                    text: "Solo se pueden cargar archivos tipo .PDF",
                    type: "warning", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 3000,
                    showConfirmButton: false
                });

                guardar = 0;
                return false;
            }
        }
        else {
            guardar = 1;
        }

        // guardar
        if (guardar == 1) {
            // enviar datos
            $('#form_documento_cierre').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/cliente",
                data: {
                    opcion: 8,
                    CONTRATO_ID: contrato_id

                },
                resetForm: false,
                success: function (data) {

                    // actualizar tabla
                    tabla_documento_cierre(contrato_id);

                    // actualiza boton
                    $('#boton_guardar_documento_cierre').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: "Documento guardado correctamente",
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1200,
                        showConfirmButton: false
                    });

                    // cerrar modal
                    $('#modal_documento_cierre').modal('hide');
                },
                beforeSend: function () {
                    $('#boton_guardar_documento_cierre').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function (data) {

                    // actualiza boton
                    $('#boton_guardar_documento_cierre').html('Guardar <i class="fa fa-save"></i>');

                    // mensaje
                    swal({
                        title: "Error",
                        text: "Error al guardar el documento",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1200,
                        showConfirmButton: false
                    });

                    return false;
                }
            }).submit();
            return false;
        }

    }


})



function tabla_documento_cierre(contrato_id) {
    try {
        var ruta = "/tablaclientedocumentoscierre/" + contrato_id;
        puedeCerrar = 0 // Bloqueamos la accion de cerrar documento
        if (TablaDocumentosCierre != null) {
            TablaDocumentosCierre.clear().draw();
            TablaDocumentosCierre.ajax.url(ruta).load();
        }
        else {
            var numeroejecucion = 1;
            TablaDocumentosCierre = $('#tabla_documentos_cierre').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        // console.log(xhr); console.log(code);
                        console.log('error en tabla_documento cierre');
                        if (numeroejecucion <= 1) {
                            tabla_documento_cierre(contrato_id);
                            numeroejecucion += 1;
                        }
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "numero_registro"
                    },
                    {
                        "data": "NOMBRE"
                    },
                    {
                        "data": "JUSTIFICACION_CIERRE",
                        "defaultContent": 'NA'

                    },
                    {
                        "className": 'Autorizar',
                        "orderable": false,
                        "data": "AUTORIZAR_BTN",
                        "defaultContent": 'NA'

                    },
                    {
                        "className": 'Pdf',
                        "orderable": false,
                        "data": null,
                        "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>'
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    },
                    {
                        "className": 'Eliminar',
                        "orderable": false,
                        "data": 'boton_eliminar',
                        "defaultContent": '-'
                    }
                ],
                rowCallback: function (row, data, index) {
                    //Si tiene un documento de cierre abilitamos la opcion para cerrar el documento
                    if ((data.ID_DOCUMENTO_CIERRE != null || data.JUSTIFICACION_CIERRE != null) && data.AUTORIZADO == 1) {
                        puedeCerrar = 1
                    }

                },
                "lengthMenu": [[10, 20, -1], [10, 20, "Todos"]],
                "order": [[0, "desc"]],
                "ordering": true,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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
        tabla_documento_cierre(contrato_id);
    }
}

$('#tabla_documentos_cierre tbody').on('click', 'td.Pdf', function () {
    var tr = $(this).closest('tr');
    var row = TablaDocumentosCierre.row(tr);

    // abrir modal
    // $('#modal_visor').modal({backdrop:false, keyboard:false});
    $('#modal_visor').modal({ backdrop: false });

    // Titulo del visor
    $('#nombre_documento_visor').html(row.data().NOMBRE);


    // Mostrar PDF
    if (parseInt(row.data().perfil) == 1 && parseInt(cliente_Bloqueado) == 0) {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/clientedocumentocierrepdf/' + row.data().ID_DOCUMENTO_CIERRE);
    }
    else {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/clientedocumentocierrepdf/' + row.data().ID_DOCUMENTO_CIERRE);
    }
});


$(document).ready(function () {
    // mostrar PDF
    $('#tabla_documentos_cierre tbody').on('click', 'td.Editar', function () {
        var tr = $(this).closest('tr');
        var row = TablaDocumentosCierre.row(tr);

        if (parseInt(row.data().accion_activa) > 0) {
            // Borrar formulario
            $('#form_documento_cierre').each(function () {
                this.reset();
            });

            // Llenar campos form
            $("#NOMBRE").val(row.data().NOMBRE);
            $("#JUSTIFICACION_CIERRE").val(row.data().JUSTIFICACION_CIERRE);

            // $("#documento").val(row.data().clienteDocumento_SoportePDF);
            // $("#clienteDocumento_Eliminado").val(row.data().clienteDocumento_Eliminado);

            // actualizar campos HIDDEN
            $("#documento_cierre_id").val(row.data().ID_DOCUMENTO_CIERRE);
            // $("#documento_cliente_id").val(row.data().cliente_id);

            // requerir campo DOCUMENTO
            $('#DOCUMENTO_CIERRE').attr('required', false);

            // abrir modal
            $('#modal_documento_cierre').modal({ backdrop: false });
        }
    });
});


// Eliminar documento de cierre
$(document).ready(function () {
    $('#tabla_documentos_cierre tbody').on('click', 'td.Eliminar', function () {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaDocumentosCierre.row(tr);

        if (parseInt(row.data().accion_activa) > 0) {

            //Parameter
            swal({
                title: "¿Está seguro de eliminar este documento?",
                text: "" + row.data().NOMBRE,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Eliminar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    // Campos del formulario cliente
                    $("#documento_cierre_id").val(row.data().ID_DOCUMENTO_CIERRE);
                    // $("#documento_cliente_id").val(row.data().cliente_id);
                    $("#NOMBRE").val(row.data().NOMBRE);
                    // $("#documento").val(row.data().clienteDocumento_SoportePDF);
                    // modificar campo eliminado
                    $('#ELIMINADO').val(1);

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar form
                    $('#form_documento_cierre').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 9,
                        },
                        resetForm: false,
                        success: function (data) {
                            // actualizar DATATABLE
                            tabla_documento_cierre(contrato_id);

                            // mensaje
                            swal({
                                title: "Eliminado",
                                text: "Registro eliminado correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        beforeSend: function () {
                            //espera
                        },
                        error: function (data) {
                            // mensaje
                            swal({
                                title: "Error",
                                text: "Error al eliminar la información",
                                type: "error", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }).submit();
                    return false;
                }
                else {
                    // modificar valor del campo
                    $('#ELIMINADO').val(0);

                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});

//Autorizar documento de cierre
$(document).ready(function () {
    $('#tabla_documentos_cierre tbody').on('click', 'td.Autorizar', function () {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaDocumentosCierre.row(tr);

        if (parseInt(row.data().accion_aut) == 1) {
            //Parameter
            swal({
                title: "¿Está seguro de Autorizar este documento de cierre?",
                text: "Esta acción no se puede revertir.",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Autorizar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {

                    // cerrar msj confirmacion
                    swal.close();

                    //Confirmamos el documento de cierre
                    $.ajax({
                        url: '/autorizardocumento/' + contrato_id + '/' + row.data().NOMBRE,
                        type: 'GET',
                        dataType: 'json',
                        data: {},
                        success: function (data) {

                            // actualizar DATATABLE
                            tabla_documento_cierre(contrato_id);

                            // mensaje
                            swal({
                                title: "Autorizado",
                                text: "El documento de cierre fue autorizado correctamente",
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });

                        },
                        error: function (xhr, status, error) {

                            swal({
                                title: "Error",
                                text: "Error al autorizar el documento",
                                type: "error", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });

                    return false;
                }
                else {
                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});

$("#boton_finalizar_contrato").click(function () {
    if (parseInt(puedeCerrar) > 0) // valida si el contrato tiene cargado algun documento de cierre
    {
        swal({
            title: "¿Está seguro de cerrar este contrato? ",
            text: "¡Esta acción no se puede revertir!",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '/finalizarContrato/' + contrato_id,
                    type: 'GET',
                    dataType: 'json',
                    data: {},
                    success: function (data) {

                        $('#documentos_contrato').css('display', 'none');
                        $(`#boton_finalizar_contrato`).removeClass("mostrat");
                        $(`#boton_finalizar_contrato`).addClass("ocultar");

                        swal({
                            title: "El contrato se ha finalizado con éxito",
                            text: "Contrato finalizado",
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr, status, error) {

                        swal({
                            title: "No se ha podido finalizar el contrato",
                            text: "Intentelo nuevamente",
                            type: "warning", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
            else {

                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
    }
    else {
        // mensaje
        swal({
            title: "El contrato aún no se puede cerrar",
            text: "Cargue el documento de finalización del contrato",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});


// MENU Step-forms (navegacion) de Informacion de los contratos y de los convenios
$('.multisteps-form__progress-btn').click(async function () {
    switch (this.id) {
        case "steps_menu_tab1":
            convenio_id = 0

            break;
        case "steps_menu_tab2":


            await tabla_clienteconvenios(contrato_id);
            convenio_id = datatable_convenios.rows().data().toArray().length == 0 ? -1 : 1;
            await tabla_conveniosPartidas(contrato_id, convenio_id);

            break;
        case "steps_menu_tab3":



            break;

        default:
            // alert(this.id);
            break;
    }
});


function tabla_conveniosPartidas(contrato_id, convenio_id) {


    return new Promise(function (resolve, reject) {

        try {

            var ruta = "/clientetablapartidas/" + contrato_id + '/' + convenio_id;

            if (datatable_partidas_convenios != null) {
                datatable_partidas_convenios.clear().draw();
                datatable_partidas_convenios.ajax.url(ruta).load();
                // datatable_partidas.ajax.reload();
            }
            else {
                var numeroejecucion = 1;
                datatable_partidas_convenios = $('#tabla_clientepartidas_convenio').DataTable({
                    ajax: {
                        url: ruta,
                        type: "get",
                        cache: false,
                        dataType: "json",
                        data: {},
                        dataSrc: function (json) {
                            // alert("Done! "+json.msj);
                            return json.data;
                        },
                        error: function (xhr, error, code) {
                            console.log('error en datatable_partidas_convenios ' + code);
                            if (numeroejecucion <= 1) {
                                tabla_conveniosPartidas(contrato_id, convenio_id);
                                numeroejecucion += 1;
                            }
                        }
                    },
                    createdRow: function (row, data, dataIndex) {
                        if (data.ACTIVO == 0) {
                            $(row).css({

                                // "font-style" : "italic",
                                // "color": "##e9ecef",
                                "background-color": "#e9ecef",
                                "cursor": "not-allowed",
                                "opacity": "0.9"


                            });

                        }
                    },
                    columns: [
                        // {
                        //     data: "id" 
                        // },
                        {
                            data: "numero_registro",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "clientepartidas_tipotexto",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "clientepartidas_parametro",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "clientepartidas_descripcion",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "UNIDAD_MEDIDA",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        }, {
                            data: "PRECIO",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        }, {
                            data: "DESCUENTO",
                            defaultContent: "--",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "BOTONES",
                            defaultContent: "-",
                            orderable: false,
                        }
                    ],
                    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                    rowsGroup: [1], //agrupar filas
                    order: [[0, "ASC"]],
                    ordering: false,
                    processing: true,
                    searching: true,
                    paging: true,
                    scrollCollapse: true,
                    scrollY: '50vh',
                    responsive: true,
                    language: {
                        lengthMenu: "Mostrar _MENU_ Registros",
                        zeroRecords: "No se encontraron registros",
                        info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                        infoEmpty: "No se encontraron registros",
                        infoFiltered: "(Filtrado de _MAX_ registros)",
                        emptyTable: "No hay datos disponibles en la tabla",
                        loadingRecords: "Cargando datos....",
                        processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                        search: "Buscar",
                        paginate: {
                            first: "Primera",
                            last: "Ultima",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                });
            }

            // Tooltip en DataTable
            datatable_partidas_convenios.on('draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

            resolve(1);
        }
        catch (exception) {
            tabla_conveniosPartidas(contrato_id, convenio_id);
            reject(exception)

        }
    })

}



$("#boton_nueva_partida_convenio").click(function () {
    if (convenio_id == 1) // valida si existe un cliente seleccionado
    {
        $('#form_partida_convenio').each(function () {
            this.reset();
        });

        // actualizar campos HIDDEN
        $("#partida_convenio_id").val(0);

        // Inicializa campo select
        $("#catprueba_id_convenio").html('<option value=""></option>');

        // Abrir modal
        $('#modal_partida_convenio').modal({ backdrop: false });
    }
    else {
        // mensaje
        swal({
            title: "No disponible",
            text: "Aun no has seleccionado o guardado los datos de un convenio",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 3000,
            showConfirmButton: false
        });
    }
});

$("#boton_guardar_partida_convenio").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // Valida envio de datos
        swal({
            title: "¿Está seguro de guardar esta partida?",
            text: "Partida  de " + $("#catprueba_id_convenio option:selected").text(),
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                // cerrar msj confirmacion
                swal.close();


                // enviar datos
                $('#form_partida_convenio').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/cliente',
                    data: {
                        opcion: 5, //PARTIDAS
                        CONTRATO_ID: contrato_id,
                        CONVENIO: 1, //INDICAMOS QUE ESTA PARTIDA ES PARA LOS CONVENIOS
                        parametro_nombre: $("#catprueba_id_convenio option:selected").text(),
                    },
                    resetForm: false,
                    success: function (dato) {
                        datatable_partidas_convenios.ajax.reload();

                        // mensaje
                        swal({
                            title: "Partida guardada correctamente",
                            text: "" + dato.msj,
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_guardar_partida_convenio').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_partida_convenio').attr('disabled', false);

                        // cerrar modal
                        $('#modal_partida_convenio').modal('hide');
                    },
                    beforeSend: function () {
                        $('#boton_guardar_partida_convenio').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        $('#boton_guardar_partida_convenio').attr('disabled', true);
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_guardar_partida_convenio').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_partida_convenio').attr('disabled', false);

                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
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
            else {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
        return false;
    }
});



//======================================================================

// Boton eliminar partida
$('#tabla_clientepartidas_convenio tbody').on('click', 'td>button.boton_eliminar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas_convenios.row(tr);

    swal({
        title: "¿Eliminar Partida?",
        text: "" + row.data().clientepartidas_nombre,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientepartidaeliminar/" + row.data().id,
                data: {},
                cache: false,
                success: function (dato) {
                    datatable_partidas_convenios.ajax.reload();

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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});

////////////////////////////////////////////////////////////////

//Editar Partida
$('#tabla_clientepartidas_convenio tbody').on('click', 'td>button.boton_editar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas_convenios.row(tr);

    // console.log(row.data());

    activa_parametro_convenios(row.data().clientepartidas_tipo);

    $('#form_partida_convenio').each(function () {
        this.reset();
    });

    $("#partida_convenio_id").val(row.data().id);
    $("#clientepartidasconvenio_tipo").val(row.data().clientepartidas_tipo);
    // $("#catprueba_id").val(row.data().catprueba_id);

    setTimeout(function () {
        $('#catprueba_id_convenio option:contains(' + row.data().clientepartidas_nombre + ')').attr('selected', 'selected');
    }, 500);

    $("#clientepartidasconvenio_descripcion").val(row.data().clientepartidas_descripcion);
    $("#UNIDAD_MEDIDA_CONVENIO").val(row.data().UNIDAD_MEDIDA);
    $("#PRECIO_UNITARIO_CONVENIO").val(row.data().PRECIO_UNITARIO);
    $("#DESCUENTO").val(row.data().DESCUENTO);




    // abrir modal
    $('#modal_partida_convenio').modal({ backdrop: false });
});
////////////////////////////////


//Desactivar la partida de convenio
$('#tabla_clientepartidas_convenio tbody').on('click', 'td>button.boton_desactivar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_partidas_convenios.row(tr);

    swal({
        title: "¿Está seguro de desactivar la partida?",
        text: "Si existe un convenio, no se podrá usar esta partida",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Desactivar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clientepartidaBloqueo/" + row.data().id + '/' + row.data().ACTIVO,
                data: {},
                cache: false,
                success: function (dato) {
                    datatable_partidas_convenios.ajax.reload()

                    // mensaje
                    swal({
                        title: "Partida desactivada correctamente",
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});


$("#boton_nuevo_anexo").click(function () {

    // Borrar formulario
    $('#form_anexo').each(function () {
        this.reset();
    });

    // HIDDEN
    $("#CONTRATO_ID").val(contrato_id);

    // Abrir modal
    $('#modal_anexo').modal({ backdrop: false });

});


$("#boton_guardar_anexo").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // Valida envio de datos
        swal({
            title: "¿Está seguro de guardar este Anexo?",
            text: "" + $("#NOMBRE_ANEXO").val(),
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                // cerrar msj confirmacion
                swal.close();


                // enviar datos
                $('#form_anexo').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/cliente',
                    data: {
                        opcion: 11, //ANEXOS
                        ELIMINADO: 0
                    },
                    resetForm: false,
                    success: function (dato) {
                        datatable_anexo.ajax.reload();

                        // mensaje
                        swal({
                            title: "Anexo guardado correctamente",
                            text: "Anexo agregado",
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_guardar_anexo').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_anexo').attr('disabled', false);

                        // cerrar modal
                        $('#modal_anexo').modal('hide');
                    },
                    beforeSend: function () {
                        $('#boton_guardar_anexo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        $('#boton_guardar_anexo').attr('disabled', true);
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_guardar_anexo').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_anexo').attr('disabled', false);

                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
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
            else {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
        return false;
    }
});


function tabla_anexos(contrato_id) {
    try {
        if (datatable_anexo != null) {
            datatable_anexo.clear().draw();
            datatable_anexo.ajax.url("/tablaAnexos/" + contrato_id).load();
        }
        else {
            var numeroejecucion = 1;
            datatable_anexo = $('#tabla_anexos').DataTable({
                "ajax": {
                    "url": "/tablaAnexos/" + contrato_id,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code) {
                        // console.log(xhr); console.log(code);
                        console.log('error en datatable_clientes');
                        if (numeroejecucion <= 1) {
                            tabla_anexos(contrato_id);
                            numeroejecucion += 1;
                        }
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "numero_registro",
                        "defaultContent": '-'
                    },
                    {
                        "data": "NOMBRE_ANEXO",
                        "defaultContent": '-'
                    },
                    {
                        "data": "TIPO",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'eliminar_anexo',
                        "orderable": false,
                        "data": 'boton_eliminar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "desc"]],
                "ordering": true,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
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

        // Tooltip en DataTable
        datatable_anexo.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    catch (exception) {
        tabla_anexos(contrato_id);
    }
}

$('#tabla_anexos tbody').on('click', 'td>button.eliminar', function () {
    var tr = $(this).closest('tr');
    var row = datatable_anexo.row(tr);

    swal({
        title: "¿Eliminar Anexo?",
        text: "" + row.data().NOMBRE_ANEXO,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/clienteanexoeliminar/" + row.data().ID_CONTRATO_ANEXO,
                data: {},
                cache: false,
                success: function (dato) {
                    datatable_anexo.ajax.reload();

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: 'Anexo eliminado correctamente',
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function (dato) {
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });
        }
    });
});


function cargarEstructuraCliente(clienteId) {
    $.ajax({
        url: '/estructura-cliente/' + clienteId,
        type: 'GET',
        success: function (data) {
            data.forEach(function (estructura, index) {
                var nivel = index + 1;
                $('#ETIQUETA' + nivel + '_ID').val(estructura.etiqueta_id);
                obteneretiquetas(nivel, estructura.opcion_id);
            });
        },
        error: function () {
            console.log('Error al obtener la estructura del cliente');
        }
    });
}



function obteneretiquetas(index, selectedOption = null) {
    var etiquetaId = $('#ETIQUETA' + index + '_ID').val();
    var opciones = $('#OPCIONES' + index + '_ID');

    opciones.empty();
    opciones.append('<option value="">Consultando...</option>');

    $.ajax({
        url: '/opciones/' + etiquetaId,
        type: 'GET',
        success: function (data) {
            opciones.empty(); // Limpiar el mensaje de "Consultando..."

            opciones.append('<option value=""></option>');

            $.each(data, function (key, value) {
                var selected = (value.ID_OPCIONES_ETIQUETAS == selectedOption) ? 'selected' : '';
                opciones.append('<option value="' + value.ID_OPCIONES_ETIQUETAS + '" ' + selected + '>' + value.NOMBRE_OPCIONES + '</option>');
            });
        },
        error: function () {
            opciones.empty();
            opciones.append('<option value="">Error al consultar opciones</option>');
        }
    });
}





function requiereOrganizacional() {
    var checkbox = $('#requiere_estructuraCliente');
    var hiddenInput = $('#requiere_estructuraCliente_activo');

    if (checkbox.is(':checked')) {
        hiddenInput.val('Si');
        $('.selector-group').show();
    } else {
        hiddenInput.val('No');
        $('.selector-group').hide();
    }
}


function actualizarOpcionesEtiqueta() {
    var valoresSeleccionados = [];
    $('.etiqueta-select').each(function () {
        var valorSeleccionado = $(this).val();
        if (valorSeleccionado) {
            valoresSeleccionados.push(valorSeleccionado);
        }
    });

    $('.etiqueta-select').each(function () {
        var selectActual = $(this);
        var valorSeleccionadoActual = selectActual.val();
        selectActual.find('option').each(function () {
            var valorOpcion = $(this).val();
            if (valoresSeleccionados.includes(valorOpcion) && valorOpcion !== valorSeleccionadoActual) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
}

$(document).ready(function () {
    $('.selector-group').hide();

    $('#requiere_estructuraCliente').change(function () {
        requiereOrganizacional();
    });

    requiereOrganizacional();

    $('.etiqueta-select').change(function () {
        actualizarOpcionesEtiqueta();
    });

    actualizarOpcionesEtiqueta();
});


//======================================= CRONOGRAMA DE ACTIVIDADES ==============================================

$(document).ready(function () {
    var selectizeInstance = $('#CAT_SUSTANICAQUIMICAS').selectize({
        placeholder: 'Seleccione una sustancia química',
        allowEmptyOption: true,
    	closeAfterSelect: false,               
	});
	
	
	$("#boton_nueva_actividad").click(function () {

		// Borrar formulario
		$('#form_actividad').each(function () {
			this.reset();
		});

		$('#ID_ACTIVIDAD').val(0)
		$('#COLOR_ACTIVIDAD').val('#1E88E6')
		$('#etiqueta-div').css('background-color', '#1E88E6');

		$('#SUSTANCIA_QUIMICAS').hide();

		var selectize = selectizeInstance[0].selectize;
		selectize.clear(); 
		
		// Abrir modal
		$('#modal_actividades').modal({ backdrop: false });

	});
	
});


$(document).ready(function () {
    $('#AGENTE_ACTIVIDAD_ID').on('change', function () {
        var valor = $(this).val();

        if (valor == "15") {
            $('#SUSTANCIA_QUIMICAS').show();

            if (!$('#CAT_SUSTANICAQUIMICAS')[0].selectize) {
                $('#CAT_SUSTANICAQUIMICAS').selectize({
                    plugins: ['remove_button'],
                    placeholder: 'Seleccione una o más sustancias',
                    maxItems: null
                });
            }
        } else {
            $('#SUSTANCIA_QUIMICAS').hide();

            if ($('#CAT_SUSTANICAQUIMICAS')[0].selectize) {
                var selectize = $('#CAT_SUSTANICAQUIMICAS')[0].selectize;
                selectize.clear();
            }
        }
    });
});



$("#boton_guardar_actividad").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // Valida envio de datos
        swal({
            title: "¿Está seguro de agregar esta actividad al cronograma de trabajo?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_actividad').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/cliente',
                    data: {
                        opcion: 12, //CRONOGRAMA DE TRABAJO
                        CONTRATO_ID: contrato_id,
                        PROYECTO_ID: 0
                    },
                    resetForm: false,
                    success: function (dato) {

                        // Mostramos las actividades del cornograma
                        mostrarActividadesCronograma()

                        // mensaje
                        swal({
                            title: "Actividad guardada correctamente",
                            text: "Anexo agregado",
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_guardar_actividad').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_actividad').attr('disabled', false);

                        // cerrar modal
                        $('#modal_actividades').modal('hide');
                    },
                    beforeSend: function () {
                        $('#boton_guardar_actividad').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        $('#boton_guardar_actividad').attr('disabled', true);
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_guardar_actividad').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_guardar_actividad').attr('disabled', false);

                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
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
            else {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
        return false;
    }
});

function mostrarActividadesCronograma() {

    var html = "";
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/obtenerActividadesCronograma/" + contrato_id + "/" + 0,
        data: {},
        cache: false,
        success: function (dato) {
            var fechaActual = new Date().toISOString().split('T')[0];

            //=================== VALIDAMOS LA VALIDACION Y AUTORIZACION DEL CRONOGRAMA ================================================
            if (Array.isArray(dato.autorizado) && dato.autorizado.length > 0) {

                info = dato.autorizado[0]

                $('#ID_AUTORIZACION').val(info.ID_AUTORIZACION);
                $('#FECHA_VALIDACION_CRONOGRAMA').val(info.FECHA_VALIDACION_CRONOGRAMA);
                $('#CARGO_VALIDACION_CRONOGRAMA').val(info.CARGO_VALIDACION_CRONOGRAMA);
                $('#NOMBRE_VALIDACION_CRONOGRAMA').val(info.NOMBRE_VALIDACION_CRONOGRAMA);

                //Mostramos la informacion
                $('#divAutorizacion').removeClass('d-none').addClass('d-block');
                $('#infoValidacion').removeClass('d-none').addClass('d-block');
                $('#fechaValidacion').text(info.FECHA_VALIDACION_CRONOGRAMA);
                $('#cargoValido').text(info.CARGO_VALIDACION_CRONOGRAMA);
                $('#nombreValido').text(info.NOMBRE_VALIDACION_CRONOGRAMA);


                if (info.NOMBRE_AUTORIZACION_CRONOGRAMA == null || info.NOMBRE_AUTORIZACION_CRONOGRAMA == '') {

                    $('#infoAutorizacion').removeClass('d-block').addClass('d-none');
                    $('#FECHA_AUTORIZACION_CRONOGRAMA').val(fechaActual);
                    $('#CARGO_AUTORIZACION_CRONOGRAMA').val(rolUsuario);
                    $('#NOMBRE_AUTORIZACION_CRONOGRAMA').val(Usuario);
                    $('.btn_cronograma').css('display', 'block');


                } else {

                    $('#infoAutorizacion').removeClass('d-none').addClass('d-block');
                    $('#fechaAutorizo').text(info.FECHA_AUTORIZACION_CRONOGRAMA);
                    $('#cargoAutorizo').text(info.CARGO_AUTORIZACION_CRONOGRAMA);
                    $('#nombreAutorizo').text(info.NOMBRE_AUTORIZACION_CRONOGRAMA);

                    $('#FECHA_AUTORIZACION_CRONOGRAMA').val(info.FECHA_AUTORIZACION_CRONOGRAMA);
                    $('#CARGO_AUTORIZACION_CRONOGRAMA').val(info.CARGO_AUTORIZACION_CRONOGRAMA);
                    $('#NOMBRE_AUTORIZACION_CRONOGRAMA').val(info.NOMBRE_AUTORIZACION_CRONOGRAMA);
                    $('.btn_cronograma').css('display', 'none');

                }

                $('#boton_autorizar_cronograma').html('Autorizar <i class="fa fa-save"></i>');
                $('#boton_autorizar_cronograma_modal').html('Autorizar cronograma <i class="fa fa fa-gavel p-1"></i>');

            } else {

                $('#form_autorizado').each(function () {
                    this.reset();
                });

                $('#divAutorizacion').removeClass('d-block').addClass('d-none');
                $('#boton_autorizar_cronograma').html('Validar <i class="fa fa-save"></i>');
                $('#boton_autorizar_cronograma_modal').html('Validar cronograma <i class="fa fa fa-gavel p-1"></i>');
                $('.btn_cronograma').css('display', 'block');

                $('#ID_AUTORIZACION').val(0);
                $('#FECHA_VALIDACION_CRONOGRAMA').val(fechaActual);
                $('#CARGO_VALIDACION_CRONOGRAMA').val(rolUsuario);
                $('#NOMBRE_VALIDACION_CRONOGRAMA').val(Usuario);
            }


            //=================== CREAMOS Y CARGAMAOS TODA LA ESTRUCTURA DEL CRONOGRAMA ================================================

            $('#activity-list').html('')
            datoInfo = dato.data;

            //Generamos los eventos del cronograma
            eventos = datoInfo.map(actividad => ({
                title: actividad.DESCRIPCION_ACTIVIDAD,
                start: `${actividad.FECHA_INICIO_ACTIVIDAD}`,
                end: `${actividad.FECHA_FIN_ACTIVIDAD}`,
                backgroundColor: actividad.COLOR_ACTIVIDAD,
                borderColor: '#FFFFFF',
                extendedProps: { ID_ACTIVIDAD: actividad.ID_ACTIVIDAD } //Pasamos atributos adicionales
            }));


            //Creamos la estructura de las actividades y el cronograma
            $.each(datoInfo, function (index, actividad) {

                //Validamos el los botones de edicion  eliminacion de las actividaes
                if (Array.isArray(dato.autorizado) && dato.autorizado.length > 0) {


                    if (info.NOMBRE_AUTORIZACION_CRONOGRAMA == null) {

                        btn_editar = `<button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar actividad" onclick="editarActividad(${actividad.ID_ACTIVIDAD})"><i class="fa fa-pencil"></i></button>`
                        btn_eliminar = `<button type="button" class="btn btn-danger btn-circle boton_eliminar mx-2" data-toggle="tooltip" data-placement="top" title="Eliminar actividad" onclick="eliminarActividad(${actividad.ID_ACTIVIDAD})" ><i class="fa fa-trash"></i></button>`

                    } else {

                        btn_editar = ''
                        btn_eliminar = ''

                    }

                } else {

                    btn_editar = `<button type="button" class="btn btn-warning btn-circle boton_editar" data-toggle="tooltip" data-placement="top" title="Editar actividad" onclick="editarActividad(${actividad.ID_ACTIVIDAD})"><i class="fa fa-pencil"></i></button>`
                    btn_eliminar = `<button type="button" class="btn btn-danger btn-circle boton_eliminar mx-2" data-toggle="tooltip" data-placement="top" title="Eliminar actividad" onclick="eliminarActividad(${actividad.ID_ACTIVIDAD})" ><i class="fa fa-trash"></i></button>`
                }

                //Creamos el contenido de las actividades y el calendario
                html += `
                <div class="card actividades-card">
                    <div class="card-body">

                        <input type="hidden" class="form-control" id="ID_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.ID_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="FECHA_INICIO_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.FECHA_INICIO_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="FECHA_FIN_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.FECHA_FIN_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="DESCRIPCION_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.DESCRIPCION_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="AGENTE_ID_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.AGENTE_ACTIVIDAD_ID}">
                        <input type="hidden" class="form-control" id="PUNTOS_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.PUNTOS_ACTIVIDAD}">
                        <input type="hidden" class="form-control" id="COLOR_ACTIVIDAD_${actividad.ID_ACTIVIDAD}"  value="${actividad.COLOR_ACTIVIDAD}">

                        <input type="hidden" id="CAT_SUSTANICAQUIMICAS_${actividad.ID_ACTIVIDAD}" value='${JSON.stringify(actividad.CAT_SUSTANICAQUIMICAS ?? [])}'>

                        <h5 style="color: ${actividad.COLOR_ACTIVIDAD}"><i class="fa fa-star" aria-hidden="true"></i> ${actividad.DESCRIPCION_ACTIVIDAD}</h5>
                        <p> <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Del: ${actividad.FECHA_INICIO_ACTIVIDAD} al: ${actividad.FECHA_FIN_ACTIVIDAD}</p>
                        <div class="col-12" style="justify-content: end; display: flex;">
                            ${btn_editar}
                            ${btn_eliminar}
                        </div>
                    </div>
                </div>
                `
            });

            $('#activity-list').html(html)

            //Cargamos el calendarion una vez tenemos todos los eventos
            inicializarCalendario(eventos)
            $('#boton_autorizar_cronograma_modal').prop('disabled', false)
            $('#boton_nueva_actividad').prop('disabled', false)


        }, beforeSend: function (dato) {

            $('#activity-list').html('<div class="col-12 text-center justify-content-center" ><i class="fa fa-spin fa-spinner fa-5x"></i></div>')
            $('#calendar').html('<div class="col-12 text-center justify-content-center"><h2>Calendario de actividades</h2><i class="fa fa-spin fa-spinner fa-5x"></i></div>')
            $('#boton_autorizar_cronograma_modal').prop('disabled', true)
            $('#boton_nueva_actividad').prop('disabled', true)

        },
        error: function (dato) {
            // alert('Error: '+dato.msj);
            return false;
        }
    });//Fin ajax
}


function inicializarCalendario(eventos) {

    $('#calendar').html('')
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {

        height: 'auto',
        headerToolbar: {
            right: 'prev,next today',
            center: 'title',
            left: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'es',
        buttonText: {
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        displayEventTime: true, //Para ver la hora en cada actividad
        initialView: 'dayGridMonth',
        events: eventos,
        dateClick: function (info) { //Evento cuando le damos click a una fecha en especifico
            $(info.dayEl).css('cursor', 'pointer');
            var fechaSeleccionada = info.date;

            $('#form_actividad').each(function () {
                this.reset();
            });
            // Rellenamos los datos almacenados
            $('#ID_ACTIVIDAD').val(0)
            $('#FECHA_INICIO_ACTIVIDAD').val(fechaSeleccionada.toISOString().split('T')[0])
            $('#COLOR_ACTIVIDAD').val('#1E88E6')
            $('#etiqueta-div').css('background-color', '#1E88E6');
            // Abrir modal
            $('#modal_actividades').modal({ backdrop: false });
        },
        eventClick: function (info) { //Evento para cuando le damos click a las fechas en el calendario
            var evento = info.event;
            editarActividad(evento.extendedProps.ID_ACTIVIDAD)
        },
        eventDidMount: function (info) {
            $(info.el).css('cursor', 'pointer');
        }

    });
    calendar.render();


    $('.fc-dayGridMonth-button').css('background', '#0B3F64')
    $('.fc-timeGridWeek-button').css('background', '#0B3F64')
    $('.fc-timeGridDay-button').css('background', '#0B3F64')
    $('.fc-prev-button').css('background', '#0B3F64')
    $('.fc-next-button').css('background', '#0B3F64')
}

function eliminarActividad(ID) {
    swal({
        title: "¿Está seguro de eliminar esta actividad?",
        text: "Se eliminara del cronograma de trabajo",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/eliminarActividadCronograma/" + ID,
                data: {},
                cache: false,
                success: function (dato) {

                    // Refrescanos las actividades 
                    mostrarActividadesCronograma();


                    // mensaje
                    swal({
                        title: "Actividad eliminada correctamente",
                        text: "La lista de actividades sera recargada",
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });

                },
                error: function (dato) {
                    // alert('Error: '+dato.msj);
                    return false;
                }
            })
        }
        return false;
    });//Fin ajax
}


// function editarActividad(ID) {
//     // Borrar formulario
//     $('#form_actividad').each(function () {
//         this.reset();
//     });

//     // Rellenamos los datos almacenados
//     $('#ID_ACTIVIDAD').val($(`#ID_ACTIVIDAD_${ID}`).val())
//     $('#FECHA_INICIO_ACTIVIDAD').val($(`#FECHA_INICIO_ACTIVIDAD_${ID}`).val())
//     $('#FECHA_FIN_ACTIVIDAD').val($(`#FECHA_FIN_ACTIVIDAD_${ID}`).val())
//     $('#DESCRIPCION_ACTIVIDAD').val($(`#DESCRIPCION_ACTIVIDAD_${ID}`).val())
//     $('#AGENTE_ACTIVIDAD_ID').val($(`#AGENTE_ID_ACTIVIDAD_${ID}`).val())
//     $('#PUNTOS_ACTIVIDAD').val($(`#PUNTOS_ACTIVIDAD_${ID}`).val())

//     color = $(`#COLOR_ACTIVIDAD_${ID}`).val()
//     $('#COLOR_ACTIVIDAD').val(color)
//     $('#etiqueta-div').css('background-color', color);



//     // Abrir modal
//     $('#modal_actividades').modal({ backdrop: false });
// }


function editarActividad(ID) {
    // Borrar formulario
    $('#form_actividad').each(function () {
        this.reset();
    });

    // Rellenamos los datos almacenados
    $('#ID_ACTIVIDAD').val($(`#ID_ACTIVIDAD_${ID}`).val())
    $('#FECHA_INICIO_ACTIVIDAD').val($(`#FECHA_INICIO_ACTIVIDAD_${ID}`).val())
    $('#FECHA_FIN_ACTIVIDAD').val($(`#FECHA_FIN_ACTIVIDAD_${ID}`).val())
    $('#DESCRIPCION_ACTIVIDAD').val($(`#DESCRIPCION_ACTIVIDAD_${ID}`).val())
    $('#AGENTE_ACTIVIDAD_ID').val($(`#AGENTE_ID_ACTIVIDAD_${ID}`).val())
    $('#PUNTOS_ACTIVIDAD').val($(`#PUNTOS_ACTIVIDAD_${ID}`).val())

    color = $(`#COLOR_ACTIVIDAD_${ID}`).val()
    $('#COLOR_ACTIVIDAD').val(color)
    $('#etiqueta-div').css('background-color', color);


    let agente = $(`#AGENTE_ID_ACTIVIDAD_${ID}`).val();
    if (agente == "15") {
        $('#SUSTANCIA_QUIMICAS').show();

        if (!$('#CAT_SUSTANICAQUIMICAS')[0].selectize) {
            $('#CAT_SUSTANICAQUIMICAS').selectize({
                plugins: ['remove_button'],
                placeholder: 'Seleccione una o más sustancias',
                maxItems: null
            });
        }

        let selectize = $('#CAT_SUSTANICAQUIMICAS')[0].selectize;
        selectize.clear();

        let sustancias = $(`#CAT_SUSTANICAQUIMICAS_${ID}`).val();
        if (sustancias) {
            try {
                let valores = JSON.parse(sustancias);
                if (Array.isArray(valores)) {
                    selectize.setValue(valores);
                }
            } catch (e) {
                console.error("Error parseando sustancias", e);
            }
        }

    } else {
        $('#SUSTANCIA_QUIMICAS').hide();
        if ($('#CAT_SUSTANICAQUIMICAS')[0].selectize) {
            $('#CAT_SUSTANICAQUIMICAS')[0].selectize.clear();
        }
    }

    // Abrir modal
    $('#modal_actividades').modal({ backdrop: false });
}



document.getElementById('etiqueta-div').addEventListener('click', function () {
    var colorInput = document.getElementById('COLOR_ACTIVIDAD');

    if (colorInput.style.display === 'none') {
        colorInput.style.display = 'block';
    } else {
        colorInput.style.display = 'none';
    }
});


document.getElementById('COLOR_ACTIVIDAD').addEventListener('input', function () {
    var nuevoColor = this.value;
    document.getElementById('etiqueta-div').style.backgroundColor = nuevoColor;
});


document.addEventListener('click', function (event) {
    var colorInput = document.getElementById('COLOR_ACTIVIDAD');
    var etiquetaDiv = document.getElementById('etiqueta-div');

    if (!colorInput.contains(event.target) && !etiquetaDiv.contains(event.target)) {
        colorInput.style.display = 'none';
    }
});


$("#boton_autorizar_cronograma_modal").click(function () {

    // Abrir modal
    $('#modal_autorizacion').modal({ backdrop: false });

});


$("#boton_autorizar_cronograma").click(function () {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        // Valida envio de datos
        swal({
            title: "¿Está seguro de realizar esta acción?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Guardar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_autorizado').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/cliente',
                    data: {
                        opcion: 13, //Autoziar o Validar cronograma
                        CONTRATO_ID: contrato_id,
                        PROYECTO_ID: 0
                    },
                    resetForm: false,
                    success: function (dato) {

                        // Refrescanos las actividades cuando el cronograma sea autorizado
                        mostrarActividadesCronograma();

                        // mensaje
                        swal({
                            title: "Información guardada correctamente",
                            text: "Cronograma correctamente guardado",
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_autorizar_cronograma').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_autorizar_cronograma').attr('disabled', false);

                        // cerrar modal
                        $('#modal_autorizacion').modal('hide');
                    },
                    beforeSend: function () {
                        $('#boton_autorizar_cronograma').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        $('#boton_autorizar_cronograma').attr('disabled', true);
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_autorizar_cronograma').html('Guardar <i class="fa fa-save"></i>');
                        $('#boton_autorizar_cronograma').attr('disabled', false);

                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
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
            else {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
        return false;
    }
});



//DESCARGAR CONCENTRADO DE ACTIVIDADDES
$('#boton_descargar_cronograma').on('click', function (e) {
    e.preventDefault();

    swal({
        title: "¡Confirme para Generar Concentrado de Actividades!",
        text: "Lista de tareas a realizar.",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Descargar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
        function (isConfirm) {
            if (isConfirm) {
                // Mostrar mensaje de carga

                $('#boton_descargar_cronograma').prop('disabled', true);
                swal({
                    title: "Generando reporte...",
                    text: 'Espere un momento, el documento se esta generando...',
                    type: "info",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                url = 'generarConcentradoActividades/' + contrato_id + '/' + 0;

                $.ajax({
                    url: url,
                    method: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = `Concentrado de actividades.xlsx`;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);

                        // Cerrar mensaje de carga
                        swal.close();

                        $('#boton_descargar_cronograma').prop('disabled', false);
                    },
                    error: function () {
                        swal({
                            title: "Hubo un problema al generar el documento.",
                            text: "Intentelo de nuevo, o comuniquelo con el responsable",
                            type: "error",
                            showConfirmButton: true
                        });
                    }
                });
            } else {
                // mensaje de cancelación
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
                    type: "error",
                    buttons: {
                        visible: false,
                    },
                    timer: 500,
                    showConfirmButton: false
                });
            }
        });
    return false;
})