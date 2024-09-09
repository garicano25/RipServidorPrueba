// DATATABLES
var TablaProveedores = null;
var TablaDomicilio = null;
var TablaDocumentos = null;

var TablaAcreditaciones = null;
var TablaServicios = null;
var TablaEquipos = null;
var TablaSignatarios = null;
var TablaSignatarioDocumentos = null;
var TablaSignatarioCursos = null;
var TablaSignatarioCursosValidacion = null;
var TablaSignatarioAcreditaciones = null;
var TablaSignatarioExperiencia = null;
var TablaPrecios = null;
var TablaEquiposDocumentos = null;

var TablaVehiculos = null;
var TablaVehiculosDocumentos = null;
var TablaVehiculos_activo = 0;

// DATATABLES activo
var TablaAcreditaciones_activo = 0;
var TablaServicios_activo = 0;
var TablaEquipos_activo = 0;
var TablaSignatarios_activo = 0;
var TablaPrecios_activo = 0;
var TablaVehiculos_activo = 0;


// Variables globales
var proveedor_id = 0;
var proveedor_tipo = 0;
var proveedor_Bloqueado = 0;
var equipo_id = 0;
var vehiculo_id = 0;

var signatario_id_validacion = 0;
// Servicios
var servicios_alcances_lista = '';
var cotizacion_partida_total = 0;
var contactos = [];



var contactosDinamicosJSON = [];
var contactodomicilio = [];
var contactossucursales = [];
var total_experiencia = 0;


//======================================================================


// Load TABLA PROVEEDORES
$(document).ready(function()
{
    tabla_proveedores();
    $('#tab_info_proveedor').css("display", 'none');
    $('[data-toggle="tooltip"]').tooltip();
});



// navegar menu Tab principal
$('.nav-link').click(function()
{
    switch (this.id)
    {
        case "tab_tabla_proveedor":
            // alert('Lista de proveedores');
            $('#tab_info_proveedor').css('display', 'none');
            proveedor_id = 0
            break; 
        case "tab_info_proveedor":
            // alert('Informacion del proveedor');
            break;
        default:
            // return true;
            break;
    }
});



// MENU Step-forms (navegacion)
$('.multisteps-form__progress-btn').click(function()
{
    proveedor_id = parseInt($("#proveedor_id").val());
    proveedor_tipo = parseInt($("#cat_tipoproveedor_id").val());
    equipo_id = 0
    vehiculo_id = 0


    switch (this.id)
    { 
        case "steps_menu_tab2":
            if (TablaAcreditaciones_activo == 0)
            {
                tabla_acreditaciones(proveedor_id);
                TablaAcreditaciones_activo = 1;
            }
            break;
        case "steps_menu_tab3":
            if (TablaEquipos_activo == 0)
            {
                tabla_equipos(proveedor_id);
                TablaEquipos_activo = 1;
            }
            break;
        case "steps_menu_tab4":
            if (TablaSignatarios_activo == 0)
            {
                tabla_signatarios(proveedor_id);
                TablaSignatarios_activo = 1;
            }
            break;      
        case "steps_menu_tab5":
            if (TablaPrecios_activo == 0)
            {
                tabla_precios(proveedor_id);
                TablaPrecios_activo = 1;
            }
            break;
        case "steps_menu_tab6":
            if (TablaServicios_activo == 0)
            {
                tabla_servicios(proveedor_id);
                TablaServicios_activo = 1;
            }
            break;
        case "steps_menu_tab8":
            if (TablaVehiculos_activo == 0)
            {
                tabla_vehiculos(proveedor_id);
                TablaVehiculos_activo = 1;
            }
            break;

        default:
            // alert(this.id);
            break;
    }
});


//======================================================================



function tabla_proveedores()
{
    try 
    {
        if (TablaProveedores != null)
        {
            TablaProveedores.clear().draw();
            TablaProveedores.ajax.url("/tablaproveedor").load();
        }
        else
        {
            TablaProveedores = $('#tabla_proveedores').DataTable({
                "ajax": {
                    "url": "/tablaproveedor",
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_proveedores();
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
                        "data": "cat_tipoproveedor.catTipoproveedor_Nombre",
                        "defaultContent": '-' 
                    },
                    {
                        "data": "proveedor_RazonSocial",
                        "defaultContent": '-' 
                    },
                    {
                        "data": "proveedor_LineaNegocios",
                        "defaultContent": '-' 
                    },
                    {
                        "data": "contacto_telefono",
                        "defaultContent": '-' 
                    },
                    {
                        "className": 'Editar_proveedor',
                        "orderable": false,
                        "data": 'boton_mostrar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "desc" ]],
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
        TablaProveedores.on( 'draw', function ()
        {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    catch (exception)
    {
        tabla_proveedores();
    }
}


function inicializar_datatables_proveedor()
{
    // Activar consulta DataTable
    TablaAcreditaciones_activo = 0;
    TablaServicios_activo = 0;
    TablaEquipos_activo = 0;
    TablaSignatarios_activo = 0;
    TablaPrecios_activo = 0;
    TablaVehiculos_activo = 0;
}


// precionar boton nuevo proveedor
$(document).ready(function()
{
    $("#boton_nuevo_proveedor").click(function () {
        

      

        // mostrar TAB
        $('#tab_info_proveedor').css("display", 'block');
        

        // Ocultar menus
        $( "#steps_menu_tab2" ).css('display', 'none');
        $( "#steps_contenido_tab2" ).css('display', 'none');
        $( "#steps_menu_tab3" ).css('display', 'none');
        $( "#steps_contenido_tab3" ).css('display', 'none');

        $( "#steps_menu_tab8" ).css('display', 'none');
        $( "#steps_contenido_tab8" ).css('display', 'none');

        $( "#steps_menu_tab4" ).css('display', 'none');
        $( "#steps_contenido_tab4" ).css('display', 'none');
        $( "#steps_menu_tab5" ).css('display', 'none');
        $( "#steps_contenido_tab5" ).css('display', 'none');
        $( "#steps_menu_tab6" ).css('display', 'none');
        $("#steps_contenido_tab6").css('display', 'none');
        $( ".datosProveedor" ).css('display', 'none');
        

        // Borrar formulario
        $('#form_proveedor').each(function(){
            this.reset();
        });

        // Inicializar datatables
        inicializar_datatables_proveedor();

        // seleccionar TAB
        $( "#tab_info_proveedor" ).click();
        $("#steps_menu_tab1").click();
        $(".listadecontactoProveedor").empty();


        // actualizar campo HIDDEN
        $("#proveedor_id").val(0);
        proveedor_id = 0

    });
});
 // Guardar y Editar proveedor
 $(document).ready(function() {
    $("#boton_guardar_proveedor").click(function() {
        var valida = this.form.checkValidity();
        if (valida) {
            $(this).prop("disabled", true);
            
            var formData = new FormData($('#form_proveedor')[0]);

          

            // Recopila los datos de los contactos predeterminados
            var contactos = [];
            $(".generarlistadecontacto").each(function() {
                var contacto = {
                    'PROVEEDOR_AREA_CONTACTO': $(this).find("input[name='PROVEEDOR_AREA_CONTACTO']").val(),
                    'PROVEEDOR_NOMBRE_CONTACTO': $(this).find("input[name='PROVEEDOR_NOMBRE_CONTACTO']").val(),
                    'PROVEEDOR_CORREO_CONTACTO': $(this).find("input[name='PROVEEDOR_CORREO_CONTACTO']").val(),
                    'PROVEEDOR_CARGO_CONTACTO': $(this).find("input[name='PROVEEDOR_CARGO_CONTACTO']").val(),
                    'PROVEEDOR_CELULAR_CONTACTO': $(this).find("input[name='PROVEEDOR_CELULAR_CONTACTO']").val(),
                    'PROVEEDOR_TELEFONO_CONTACTO': $(this).find("input[name='PROVEEDOR_TELEFONO_CONTACTO']").val(),
                    'PROVEEDOR_EXTENSION_CONTACO': $(this).find("input[name='PROVEEDOR_EXTENSION_CONTACO']").val(),
                    'ACTIVO_PROVEEDOR': $(this).find("input[name='ACTIVO_PROVEEDOR']").val()
                    
                };
                contactos.push(contacto);
            });

            formData.append('CONTACTOS_JSON',JSON.stringify(contactos));


         // Continuar con el envío del formulario
            $.ajax({
                type: 'POST',
                url: "/proveedor",
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#proveedor_id").val(data.id);
                    proveedor_id = data.id;
                    tabla_domicilio(proveedor_id);
                    tabla_documento(proveedor_id);

                    activa_forms(data.cat_tipoproveedor_id, data.cat_tipoproveedor.alcance);
                    $("#tabla_proveedores").DataTable().ajax.reload();
                    $('#boton_guardar_proveedor').html('Guardar <i class="fa fa-save"></i>');
                    $("#boton_guardar_proveedor").prop("disabled", false);
                    swal({
                        title: "Correcto",
                        text: "Información guardada correctamente",
                        type: "success",
                        buttons: {
                            visible: false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $( ".datosProveedor" ).css('display', 'block');

                    $('.botonEliminarContacto').hide();


                },
                beforeSend: function() {
                    $('#boton_guardar_proveedor').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function() {
                    $('#boton_guardar_proveedor').html('Guardar <i class="fa fa-save"></i>');
                    $("#boton_guardar_proveedor").prop("disabled", false);
                    swal({
                        title: "Error",
                        text: "Error al guardar la información",
                        type: "error",
                        buttons: {
                            visible: false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});






function activa_forms(tipo_proveedor, modulos)
{
    var tipo_proveedor = parseInt(tipo_proveedor);

    // todos los forms
    $( "#steps_menu_tab1" ).css('display', 'block');
    $( "#steps_contenido_tab1" ).css('display', 'block');
    
    $( "#steps_menu_tab2" ).css('display', 'none');
    $( "#steps_contenido_tab2" ).css('display', 'none');

    $( "#steps_menu_tab3" ).css('display', 'none');
    $( "#steps_contenido_tab3" ).css('display', 'none');

    $( "#steps_menu_tab8" ).css('display', 'none');
    $( "#steps_contenido_tab8" ).css('display', 'none');

    $( "#steps_menu_tab4" ).css('display', 'none');
    $( "#steps_contenido_tab4" ).css('display', 'none');

    $( "#steps_menu_tab5" ).css('display', 'none');
    $( "#steps_contenido_tab5" ).css('display', 'none');

    $( "#steps_menu_tab6" ).css('display', 'none');
    $( "#steps_contenido_tab6" ).css('display', 'none');

    // Seleccionar checkbox parametros
    $.each( modulos, function( key, value )
    {
        // alert( key + ": " + value.cat_tipoproveedoralcance_alcance );
        $( "#steps_menu_tab"+value.cat_tipoproveedoralcance_alcance ).css('display', 'block');
        $( "#steps_contenido_tab"+value.cat_tipoproveedoralcance_alcance ).css('display', 'block');
        $( "#steps_menu_tab8" ).css('display', 'block');
        $( "#steps_contenido_tab8" ).css('display', 'block');
    });
}

//SELECCIONAR PROVEEDOR 

$(document).ready(function() {
    $('#tabla_proveedores tbody').on('click', 'td.Editar_proveedor', function() {
        var tr = $(this).closest('tr');
        var row = TablaProveedores.row(tr);




        // Borrar formulario
        $('#form_proveedor').each(function() {
            this.reset();
        });


        $( ".datosProveedor" ).css('display', 'block');


        // mostrar TAB
        $('#tab_info_proveedor').css("display", 'block');

        // activar forms segun el tipo del proveedor
        activa_forms(row.data().cat_tipoproveedor_id, row.data().cat_tipoproveedor.alcance);

        // Llenar Campos del formulario PROVEEDOR
        $("#cat_tipoproveedor_id").val(row.data().cat_tipoproveedor_id);
        $("#proveedor_RazonSocial").val(row.data().proveedor_RazonSocial);
        $("#proveedor_NombreComercial").val(row.data().proveedor_NombreComercial);
        $("#proveedor_DomicilioFiscal").val(row.data().proveedor_DomicilioFiscal);
        $("#proveedor_LineaNegocios").val(row.data().proveedor_LineaNegocios);
        $("#proveedor_GiroComercial").val(row.data().proveedor_GiroComercial);
        $("#proveedor_Rfc").val(row.data().proveedor_Rfc);
        $("#proveedor_CiudadPais").val(row.data().proveedor_CiudadPais);
        $("#proveedor_RepresentanteLegal").val(row.data().proveedor_RepresentanteLegal);
        $("#proveedor_PaginaWeb").val(row.data().proveedor_PaginaWeb);
        $("#proveedor_Eliminado").val(row.data().proveedor_Eliminado);

        // Actualizar campo HIDDEN id, form proveedor
        $("#proveedor_id").val(row.data().id);


        // Inicializar datatables
        inicializar_datatables_proveedor();

        // Consultar tablas informacion del proveedor
        tabla_domicilio(row.data().id);
        tabla_documento(row.data().id);

        // VERIFICA BLOQUEO DEL PROVEEDOR
        if (parseInt(row.data().proveedor_Bloqueado) == 0) //desbloqueado
        {
            proveedor_Bloqueado = 0;
            $("#boton_bloquear_proveedor").val(0);
            $("#boton_bloquear_proveedor").removeClass('btn-warning');
            $("#boton_bloquear_proveedor").addClass('btn-success');
            $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proveedor desbloqueado para edición');

            // Activar botones
            $(".botonguardar_moduloproveedores").html('Guardar <i class="fa fa-save"></i>');
            $(".botonguardar_moduloproveedores").attr('disabled', false);
            $(".botonnuevo_moduloproveedores").attr('disabled', false);
            $("#checkbox_estado_signatario").attr('disabled', false);

            $("#checkbox_estado_equipo").attr('disabled', false);
            
        } else {
            proveedor_Bloqueado = 1;
            $("#boton_bloquear_proveedor").val(1);
            $("#boton_bloquear_proveedor").removeClass('btn-success');
            $("#boton_bloquear_proveedor").addClass('btn-warning');
            $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proveedor bloqueado para edición');

            // Desactivar botones
            $(".botonguardar_moduloproveedores").html('Guardar <i class="fa fa-ban"></i>');
            $(".botonguardar_moduloproveedores").attr('disabled', true);
            $(".botonnuevo_moduloproveedores").attr('disabled', true);
            $("#checkbox_estado_signatario").attr('disabled', true);
            $("#checkbox_estado_equipo").attr('disabled', true);

        }

        // Colocar nombre del proveedor
        $(".div_proveedor_nombre").html(row.data().proveedor_RazonSocial);
        $(".div_proveedor_lineanegocio").html(row.data().proveedor_LineaNegocios);


        $(".listadecontactoProveedor").empty();
        obtenerDatosContactosProveedores(row)

        $('.botonEliminarContacto').hide();


        // seleccionar TAB
        $('#tab_info_proveedor').click();
        $("#steps_menu_tab1").click();




    });
});

// function obtenerDatosContactosProveedores(data) {
 

//       //$('#1pr1').prop('checked', true)
//     let row = data.data().CONTACTOS_JSON;
//     var contactos = JSON.parse(row);
    
//     // Contador para llevar el número de contacto
//     let contadorContacto = 1;

//     // Recorrer cada contacto
//     $.each(contactos, function(index, contacto) {
//         // Acceder a cada propiedad del contacto
//         var area = contacto.PROVEEDOR_AREA_CONTACTO;
//         var nombre = contacto.PROVEEDOR_NOMBRE_CONTACTO;
//         var correo = contacto.PROVEEDOR_CORREO_CONTACTO;
//         var cargo = contacto.PROVEEDOR_CARGO_CONTACTO;
//         var celular = contacto.PROVEEDOR_CELULAR_CONTACTO;
//         var telefono = contacto.PROVEEDOR_TELEFONO_CONTACTO;
//         var extension = contacto.PROVEEDOR_EXTENSION_CONTACO;
//         var activo = contacto.ACTIVO_PROVEEDOR;


//         const divContacto = document.createElement('div');
//         divContacto.classList.add('row');
//         divContacto.classList.add('generarlistadecontacto','m-2'); 
//         divContacto.innerHTML = `
//         <div class="col-lg-12 col-sm-1">
//                 <div class="form-group">
//                 <h2><i class="fa fa-user"></i> Contacto N° ${contadorContacto}</h2>
                 
//                 </div>
//             </div>
//             <div class="col-lg-4 col-sm-6">
//                 <div class="form-group">
//                     <label>Área contacto: *</label>
//                     <input type="text" class="form-control" name="PROVEEDOR_AREA_CONTACTO" value="${area}" required>
//                 </div>
//             </div>
//             <div class="col-lg-4 col-sm-6">
//                 <div class="form-group">
//                     <label>Nombre contacto: *</label>
//                     <input type="text" class="form-control" name="PROVEEDOR_NOMBRE_CONTACTO" value="${nombre}" required>
//                 </div>
//             </div>
//             <div class="col-lg-4 col-sm-6">
//                 <div class="form-group">
//                     <label>Correo contacto: *</label>
//                     <input type="text" class="form-control" name="PROVEEDOR_CORREO_CONTACTO" value="${correo}" required>
//                 </div>
//             </div>
//             <div class="col-lg-3 col-sm-6">
//                 <div class="form-group">
//                   <label> Cargo contacto: *</label>
//                   <input type="text" class="form-control" name="PROVEEDOR_CARGO_CONTACTO"  value="${cargo}" required>
//                 </div>
//             </div>
//             <div class="col-lg-3 col-sm-6">
//                 <div class="form-group">
//                     <label> Celular contacto: *</label>
//                     <input type="text" class="form-control"  name="PROVEEDOR_CELULAR_CONTACTO" value="${celular}" required>
//                 </div>
//             </div>
//             <div class="col-lg-3 col-sm-6">
//                 <div class="form-group">
//                     <label> Teléfono contacto: *</label>
//                     <input type="text" class="form-control"  name="PROVEEDOR_TELEFONO_CONTACTO" value="${telefono}" required>
//                 </div>
//             </div>
//             <div class="col-lg-3 col-sm-6">
//                 <div class="form-group">
//                     <label> Extensión : </label>
//                     <input type="text" class="form-control"  name="PROVEEDOR_EXTENSION_CONTACO" value="${extension}">
//                 </div>
//             </div>
//             <div class="col-lg-3 col-sm-6">
//                     <div class="form-group">
//                         <input type="hidden" class="form-control" id="ACTIVO_PROVEEDOR" name="ACTIVO_PROVEEDOR" value="${}">
//                     </div>
//                 </div>
//             <div class="col">
//                 <div class="form-group" style="text-align: center;">
//                     <button type="button" class="btn btn-danger botonEliminarContacto">Eliminar contacto <i class="fa fa-trash"></i></button>
//                 </div>
//             </div>
//         `;
//         const contenedor = document.querySelector('.listadecontactoProveedor');
//         contenedor.appendChild(divContacto);

//          // Incrementar el contador de contacto para el siguiente
//          contadorContacto++;

//          const botonEliminar = divContacto.querySelector('.botonEliminarContacto');
//         botonEliminar.addEventListener('click', function() {
//             contenedor.removeChild(divContacto);
//         });
        

//     });

    
// }



function obtenerDatosContactosProveedores(data) {
    let row = data.data().CONTACTOS_JSON;
    var contactos = JSON.parse(row);
    
    // Contador para llevar el número de contacto
    let contadorContacto = 1;

    // Recorrer cada contacto
    $.each(contactos, function(index, contacto) {
        // Acceder a cada propiedad del contacto
        var area = contacto.PROVEEDOR_AREA_CONTACTO;
        var nombre = contacto.PROVEEDOR_NOMBRE_CONTACTO;
        var correo = contacto.PROVEEDOR_CORREO_CONTACTO;
        var cargo = contacto.PROVEEDOR_CARGO_CONTACTO;
        var celular = contacto.PROVEEDOR_CELULAR_CONTACTO;
        var telefono = contacto.PROVEEDOR_TELEFONO_CONTACTO;
        var extension = contacto.PROVEEDOR_EXTENSION_CONTACO;
        var activo = contacto.ACTIVO_PROVEEDOR;

        const divContacto = document.createElement('div');
        divContacto.classList.add('row');
        divContacto.classList.add('generarlistadecontacto','m-2'); 
        divContacto.innerHTML = `
        <div class="col-lg-12 col-sm-1">
            <div class="form-group d-flex align-items-center">
                <h2><i class="fa fa-user"></i> Contacto N° ${contadorContacto}  &nbsp; </h2>
                <h4>Activo</h4>

                <div class="switch ms-3">
                    <label>
                    
                        <input type="checkbox" ${activo == 1 ? 'checked' : ''} data-index="${index}">
                        <span class="lever switch-col-light-blue"></span>
                    
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label>Área contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_AREA_CONTACTO" value="${area}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label>Nombre contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_NOMBRE_CONTACTO" value="${nombre}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label>Correo contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_CORREO_CONTACTO" value="${correo}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label>Cargo contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_CARGO_CONTACTO" value="${cargo}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label>Celular contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_CELULAR_CONTACTO" value="${celular}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label>Teléfono contacto: *</label>
                <input type="text" class="form-control" name="PROVEEDOR_TELEFONO_CONTACTO" value="${telefono}" ${activo == 0 ? 'disabled' : ''} required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label>Extensión : </label>
                <input type="text" class="form-control" name="PROVEEDOR_EXTENSION_CONTACO" value="${extension}" ${activo == 0 ? 'disabled' : ''}>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6" style="display: none;">
            <div class="form-group">
                <input type="hidden" class="form-control" id="ACTIVO_PROVEEDOR_${index}" name="ACTIVO_PROVEEDOR" value="${activo}">
            </div>
        </div>
        <div class="col">
            <div class="form-group" style="text-align: center;">
                <button type="button" class="btn btn-danger botonEliminarContacto">Eliminar contacto <i class="fa fa-trash"></i></button>
            </div>
        </div>
        `;
        const contenedor = document.querySelector('.listadecontactoProveedor');
        contenedor.appendChild(divContacto);

        // Incrementar el contador de contacto para el siguiente
        contadorContacto++;

        const botonEliminar = divContacto.querySelector('.botonEliminarContacto');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divContacto);
        });

        // Agregar el evento al switch
        const switchActivo = divContacto.querySelector('input[type="checkbox"]');
        switchActivo.addEventListener('change', function() {
            const inputActivo = divContacto.querySelector(`input[name="ACTIVO_PROVEEDOR"]`);
            inputActivo.value = this.checked ? 1 : 0;
            contactos[index].ACTIVO_PROVEEDOR = inputActivo.value; 
            
            // Obtener todos los inputs dentro de divContacto
            const inputs = divContacto.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.disabled = !this.checked;
            });
        });
    });
}



function obtenerDatosContactosSucursales(data) {
    // console.log(data);

    let row = data.data().contactos_sucursal;
    var contactos = JSON.parse(row);

    // Contador para llevar el número de contacto
    let contadorContacto = 1;

    // Recorrer cada contacto
    $.each(contactos, function(index, contacto) {
        // Acceder a cada propiedad del contacto
        var area = contacto.PROVEEDORSUCURSAL_AREA;
        var nombre = contacto.PROVEEDORSUCURSAL_NOMBRE;
        var correo = contacto.PROVEEDORSUCURSAL_CORREO;
        var cargo = contacto.PROVEEDORSUCURSAL_CARGO;
        var celular = contacto.PROVEEDORSUCURSAL_CONTACTO;
        var telefono = contacto.PROVEEDORSUCURSAL_TELEFONO;
        var extension = contacto.PROVEEDORSUCURSAL_EXTENSION;
        var activo = contacto.ACTIVO_SUCURSAL;


        const divContactosucursal = document.createElement('div');
        divContactosucursal.classList.add('row');
        divContactosucursal.classList.add('generarsucursales','m-2');
        divContactosucursal.innerHTML = `
            <div class="col-lg-12 col-sm-1">
                <div class="form-group">
                    <h2><i class="fa fa-user"></i> Contacto N° ${contadorContacto}</h2>
                    <h4>Activo</h4>

                <div class="switch ms-3">
                    <label>
                    
                        <input type="checkbox" ${activo == 1 ? 'checked' : ''} data-index="${index}">
                        <span class="lever switch-col-light-blue"></span>
                    
                    </label>
                </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Área contacto: *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_AREA"  value="${area}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label> Nombre Contacto *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_NOMBRE"  value="${nombre}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label> Correo Contacto *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_CORREO" value="${correo}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label> Cargo Contacto *</label>
                    <input type="text" class="form-control" name="PROVEEDORSUCURSAL_CARGO" value="${cargo}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label> Celular Contacto *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_CONTACTO"  value="${celular}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label> Teléfono *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_TELEFONO"   value="${telefono}" ${activo == 0 ? 'disabled' : ''} required>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label> Extensión *</label>
                    <input type="text" class="form-control"  name="PROVEEDORSUCURSAL_EXTENSION" value="${extension}" ${activo == 0 ? 'disabled' : ''} >
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" style="display: none;">
                <div class="form-group">
                <input type="hidden" class="form-control" id="ACTIVO_SUCURSAL_${index}" name="ACTIVO_SUCURSAL" value="${activo}">
                </div>
            </div>
            
            <div class="col-12">
                <div class="form-group" style="text-align: center;">
                    <button type="button" class="btn btn-danger botonEliminarContactoSucursal">Eliminar contacto <i class="fa fa-trash"></i></button>
                </div>
            </div>
        `;
        const contenedor = document.querySelector('.listadecontactoProveedorSucursal');
        contenedor.appendChild(divContactosucursal);

        // Incrementar el contador de contacto para el siguiente
        contadorContacto++;

        const botonEliminar = divContactosucursal.querySelector('.botonEliminarContactoSucursal');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divContactosucursal);
        });

    // Agregar el evento al switch
        const switchActivo = divContactosucursal.querySelector('input[type="checkbox"]');
        switchActivo.addEventListener('change', function() {
            const inputActivo = divContactosucursal.querySelector(`input[name="ACTIVO_SUCURSAL"]`);
            inputActivo.value = this.checked ? 1 : 0;
            contactos[index].ACTIVO_SUCURSAL = inputActivo.value; 
            
            // Obtener todos los inputs dentro de divContactosucursal
            const inputs = divContactosucursal.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.disabled = !this.checked;
            });
        });
    });
}


function bloqueo_proveedor(opcion)
{
    var valor = parseInt(opcion);
    var mensaje_1 = '';
    var mensaje_2 = '';

    if (valor == 1)
    {
        mensaje_1 = '¿Desbloquear proveedor?';
        mensaje_2 = 'Desbloquear proveedor para edición';
    }
    else
    {
        mensaje_1 = '¿Bloquear proveedor?';
        mensaje_2 = 'Bloquear proveedor para edición';
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
    }, function(isConfirm){   
        if (isConfirm)
        {
            // cerrar msj confirmacion
            swal.close();

            //Enviar datos
            $.ajax({
                type: "get",
                dataType: "json",
                url: "/proveedorbloqueo/"+$("#proveedor_id").val()+"/"+valor,
                data:{},
                cache: false,
                success:function(dato)
                {
                    // Actualiza tabla
                    tabla_proveedores();

                    // INVERTIR DISEÑO DEL BOTON [BLOQUEO DEL PROVEEDOR]
                    if (parseInt(dato.proveedor.proveedor_Bloqueado) == 0)
                    {
                        proveedor_Bloqueado = 0;
                        $("#boton_bloquear_proveedor").val(0);
                        $("#boton_bloquear_proveedor").removeClass('btn-warning');
                        $("#boton_bloquear_proveedor").addClass('btn-success');
                        $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proveedor desbloqueado para edición');

                        // Activar botones
                        $(".botonguardar_moduloproveedores").html('Guardar <i class="fa fa-save"></i>');
                        $(".botonguardar_moduloproveedores").attr('disabled', false);
                        $(".botonnuevo_moduloproveedores").attr('disabled', false);
                        $("#checkbox_estado_signatario").attr('disabled', false);
                        $("#checkbox_estado_equipo").attr('disabled', false);

                    }
                    else
                    {
                        proveedor_Bloqueado = 1;
                        $("#boton_bloquear_proveedor").val(1);
                        $("#boton_bloquear_proveedor").removeClass('btn-success');
                        $("#boton_bloquear_proveedor").addClass('btn-warning');
                        $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proveedor bloqueado para edición');

                        // Desactivar botones
                        $(".botonguardar_moduloproveedores").html('Guardar <i class="fa fa-ban"></i>');
                        $(".botonguardar_moduloproveedores").attr('disabled', true);
                        $(".botonnuevo_moduloproveedores").attr('disabled', true);
                        $("#checkbox_estado_signatario").attr('disabled', true);
                        $("#checkbox_estado_equipo").attr('disabled', true);

                    }

                    // Inicializar datatables
                    inicializar_datatables_proveedor();

                    // Actualizar tablas informacion del proveedor
                    tabla_domicilio(dato.proveedor.id);
                    tabla_documento(dato.proveedor.id);

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
                beforeSend: function()
                {
                    $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-spin fa-spinner"></i></span> Cambiando estado del proveedor');
                },
                error: function(dato)
                {
                    // VERIFICA BLOQUEO DEL PROVEEDOR
                    if (valor == 0) //desbloqueado
                    {
                        $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-unlock"></i></span> Proveedor desbloqueado para edición');
                    }
                    else
                    {
                        $("#boton_bloquear_proveedor").html('<span class="btn-label"><i class="fa fa-lock"></i></span> Proveedor bloqueado para edición');
                    }

                    // mensaje
                    swal({
                        title: "Error",
                        text: ""+dato.msj,
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
        else 
        {
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


function tabla_domicilio(id)
{
    try 
    {
        var ruta = "/tablaproveedordomicilio/"+id;

        if (TablaDomicilio != null)
        {
            TablaDomicilio.clear().draw();
            TablaDomicilio.ajax.url(ruta).load();
        }
        else
        {
            TablaDomicilio = $('#tabla_domicilios').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_domicilio(id);
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
                        "data": "proveedorDomicilio_ciudad" 
                    },
                    {
                        "data": "proveedorDomicilio_Direccion"
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>'
                    }
                ],
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]],
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        // tabla_domicilio(id);
    }
}


// precionar boton nuevo domicilio
$(document).ready(function()
{
    $("#boton_nuevo_domicilio").click(function() {
        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {
            // Borrar formulario
            $('#form_domicilio').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#domicilio_id").val(0);
            $("#domicilio_proveedor_id").val($("#proveedor_id").val());

            // Abrir modal
            $('#modal_domicilio').modal({backdrop:false});
            
            $(".listadecontactoProveedorSucursal").empty();

            
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has seleccionado o guardado los datos de un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


// Guardar y Editar domicilio
$("#boton_guardar_domicilio_sucursal").on("click", function (e) {
    
    e.preventDefault();
    var valida = this.form.checkValidity();
    if (valida) {
        var formData = new FormData($('#form_domicilio')[0]);
        $("#proveedorDomicilio_Eliminado").val(0)

     

        // Recopila los datos de los contactos predeterminados
        var contactossucursales = [];
        $(".generarsucursales").each(function() {
            var contactodomicilio = {
                'PROVEEDORSUCURSAL_AREA': $(this).find("input[name='PROVEEDORSUCURSAL_AREA']").val(),
                'PROVEEDORSUCURSAL_NOMBRE': $(this).find("input[name='PROVEEDORSUCURSAL_NOMBRE']").val(),
                'PROVEEDORSUCURSAL_CORREO': $(this).find("input[name='PROVEEDORSUCURSAL_CORREO']").val(),
                'PROVEEDORSUCURSAL_CARGO': $(this).find("input[name='PROVEEDORSUCURSAL_CARGO']").val(),
                'PROVEEDORSUCURSAL_CONTACTO': $(this).find("input[name='PROVEEDORSUCURSAL_CONTACTO']").val(),
                'PROVEEDORSUCURSAL_TELEFONO': $(this).find("input[name='PROVEEDORSUCURSAL_TELEFONO']").val(),
                'PROVEEDORSUCURSAL_EXTENSION': $(this).find("input[name='PROVEEDORSUCURSAL_EXTENSION']").val(),
                'ACTIVO_SUCURSAL': $(this).find("input[name='ACTIVO_SUCURSAL']").val()
                

            };
            contactossucursales.push(contactodomicilio);
        });

        formData.append('contactos_sucursal', JSON.stringify(contactossucursales));

        // enviar datos
        $.ajax({
            type: 'POST',
            url: "/proveedordomicilio",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                // actualizar tabla
                tabla_domicilio(data.proveedor_id);

                // actualiza boton
                $('#boton_guardar_domicilio_sucursal').html('Guardar <i class="fa fa-save"></i>');


                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success",
                    buttons: {
                        visible: false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });

                // cerrar modal
                $('#modal_domicilio').modal('hide');

               
            },
            beforeSend: function() {
                $('#boton_guardar_domicilio_sucursal').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(data) {
                // actualiza boton
                $('#boton_guardar_domicilio_sucursal').html('Guardar <i class="fa fa-save"></i>');

                // mensaje
                swal({
                    title: "Error",
                    text: "Error al guardar la información",
                    type: "error",
                    buttons: {
                        visible: false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }else{
        swal({
            title: "Complete todos los campos",
            text: "Verifique que los datos ingresados concuerden con el tipo de datos",
            type: "error", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 2000,
            showConfirmButton: false
        }); 
    }
})

// seleccionar domicilio
$(document).ready(function()
{
    $('#tabla_domicilios tbody').on('click', 'td.Editar', function()
    {
        var tr = $(this).closest('tr');
        var row = TablaDomicilio.row(tr);



        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_domicilio').each(function(){
                this.reset();
            });


            // Campos del formulario proveedor
            $("#proveedorDomicilio_ciudad").val(row.data().proveedorDomicilio_ciudad);
            $("#proveedorDomicilio_Direccion").val(row.data().proveedorDomicilio_Direccion);

            // $("#proveedorDomicilio_Contacto").val(row.data().proveedorDomicilio_Contacto);
            // $("#proveedorDomicilio_Cargo").val(row.data().proveedorDomicilio_Cargo);
            // $("#proveedorDomicilio_Telefono").val(row.data().proveedorDomicilio_Telefono);
            // $("#proveedorDomicilio_Eliminado").val(row.data().proveedorDomicilio_Eliminado);

            // actualizar campos HIDDEN
            $("#domicilio_id").val(row.data().id);
            $('#domicilio_proveedor_id').val(row.data().proveedor_id)


            // abrir modal
            $('#modal_domicilio').modal({backdrop:false});




            $(".listadecontactoProveedorSucursal").empty();
            obtenerDatosContactosSucursales(row)



            $('.botonEliminarContactoSucursal').hide();
        }
    });
});


// Eliminar domicilio
$(document).ready(function()
{
    $('#tabla_domicilios tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaDomicilio.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar sucursal?",   
                text: ""+row.data().proveedorDomicilio_Direccion,   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    // Campos del formulario proveedor
                    $("#domicilio_id").val(row.data().id);
                    $("#domicilio_proveedor_id").val(row.data().proveedor_id);
                    $("#proveedorDomicilio_Direccion").val(row.data().proveedorDomicilio_Direccion);
                    $("#proveedorDomicilio_ciudad").val(row.data().proveedorDomicilio_ciudad);
                   
                    // modificar campo eliminado
                    $('#proveedorDomicilio_Eliminado').val(1);

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar form
                    $('#form_domicilio').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/proveedordomicilio",
                        data: {},
                        resetForm: false,
                        success: function(data)
                        {
                            // actualiza DataTable
                            tabla_domicilio(data.proveedor_id);
                            // if (TablaDomicilio == null) {
                            //     tabla_domicilio(data.proveedor_id);
                            // }
                            // else{
                            //     // TablaDomicilio.destroy();
                            //     // tabla_domicilio(data.proveedor_id);
                            //     TablaDomicilio.ajax.url("/tablaproveedordomicilio/"+data.proveedor_id).load();
                            // }

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
                        beforeSend: function() {
                            //espera
                        },
                        error: function(data) {
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
                else 
                {     
                    // modificar valor del campo
                    $('#proveedorDomicilio_Eliminado').val(0);
                    
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


function tabla_documento(id)
{
    try 
    {
        var ruta = "/tablaproveedordocumento/"+id;

        if (TablaDocumentos != null)
        {
            TablaDocumentos.clear().draw();
            TablaDocumentos.ajax.url(ruta).load();
        }
        else
        {
            TablaDocumentos = $('#tabla_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_documento(id);
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
                    // {
                    //     "data": "TIPO_DOCUMENTO" 
                    // },
                    {
                        "data": "proveedorDocumento_Nombre"
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
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]],
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_documento(id);
    }
}


// precionar boton nuevo documento
$(document).ready(function() {
    $("#boton_nuevo_documento").click(function() {
        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {
            // Borrar formulario
            $('#form_documento').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#documento_id").val(0);
            $("#documento_proveedor_id").val($("#proveedor_id").val());
            $("#proveedorDocumento_Eliminado").val(0);

            // requerir campo DOCUMENTO
            $('#documento').attr('required', true);

            // Abrir modal
            $('#modal_documento').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has seleccionado o guardado los datos de un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});

// Guardar y Editar documento
$("#boton_guardar_documento").click(function() {
    var guardar = 0;

    // Verifica que no se esté procesando otra solicitud
    if ($(this).attr('disabled')) {
        return false; // Evita que se procesen múltiples solicitudes
    }

    // Valida campos vacíos
    var valida = this.form.checkValidity();
    if (valida) {
        if ($("#documento").val() != "") {
            // Tipo archivo
            var archivo = $("#documento").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // Valida tipo de archivo
            if (extension == ".pdf" || extension == ".PDF") {
                guardar = 1;
            } else {
                // Muestra mensaje de error
                swal({
                    title: "Tipo de archivo incorrecto " + extension,
                    text: "Solo se pueden cargar archivos tipo .PDF",
                    type: "warning",
                    buttons: {
                        visible: false
                    },
                    timer: 3000,
                    showConfirmButton: false
                });

                guardar = 0;
                return false;
            }
        } else {
            guardar = 1;
        }

        // Guardar
        if (guardar == 1) {
            // Desactiva el botón para evitar múltiples clics
            $(this).attr('disabled', true);

            // Enviar datos
            $('#form_documento').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/proveedordocumento",
                data: {},
                resetForm: false,
                success: function(data) {
                    // Actualizar tabla
                    tabla_documento(data.proveedor_id)

                    // Actualizar botón
                    $('#boton_guardar_documento').html('Guardar <i class="fa fa-save"></i>');
                    $('#boton_guardar_documento').removeAttr('disabled'); // Habilitar el botón nuevamente

                    // Mensaje
                    swal({
                        title: "Correcto",
                        text: "Información guardada correctamente",
                        type: "success",
                        buttons: {
                            visible: false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Cerrar modal
                    $('#modal_documento').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    // Actualizar botón
                    $('#boton_guardar_documento').html('Guardar <i class="fa fa-save"></i>');
                    $('#boton_guardar_documento').removeAttr('disabled'); // Habilitar el botón nuevamente

                    // Mensaje
                    swal({
                        title: "Error",
                        text: "Error al guardar la información",
                        type: "error",
                        buttons: {
                            visible: false
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
});



// mostrar documento
$(document).ready(function()
{
    $('#tabla_documentos tbody').on('click', 'td.Pdf', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row( tr );

        // abrir modal
        // $('#modal_visor').modal({backdrop:false, keyboard:false});
        $('#modal_visor').modal({backdrop:false});

        // Titulo del visor
        $('#nombre_documento_visor').html(row.data().proveedorDocumento_Nombre);

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/proveedorverdocumento/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/proveedorverdocumento/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proveedorverdocumento/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/proveedorverdocumento/'+row.data().id);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/proveedorverdocumento/'+row.data().id);
        }
    });
});


// Vaciar contenido del visor al cerrar modal
$(document).ready(function() {
    $("#modalvisor_boton_cerrar").click(function() {
        $('#visor_documento').attr('src', '/assets/images/cargando.gif');
    });
});


// Seleccionar documento
$(document).ready(function()
{
    // mostrar PDF
    $('#tabla_documentos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_documento').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#TIPO_DOCUMENTO").val(row.data().TIPO_DOCUMENTO);

            $("#proveedorDocumento_Nombre").val(row.data().proveedorDocumento_Nombre);


        
            // $("#documento").val(row.data().proveedorDocumento_SoportePDF);
            $("#proveedorDocumento_Eliminado").val(row.data().proveedorDocumento_Eliminado);
            $("#proveedorDocumento_Eliminado").val(row.data().proveedorDocumento_Eliminado);

            // actualizar campos HIDDEN
            $("#documento_id").val(row.data().id);
            $("#documento_proveedor_id").val(row.data().proveedor_id);
            
            // requerir campo DOCUMENTO
            $('#documento').attr('required', false);

            // abrir modal
            $('#modal_documento').modal({backdrop:false});
        }
    });
});


// Eliminar documento
$(document).ready(function()
{
    $('#tabla_documentos tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaDocumentos.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {

            //Parameter
            swal({   
                title: "¿Eliminar documento?",   
                text: ""+row.data().proveedorDocumento_Nombre,   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    // Campos del formulario proveedor
                    $("#documento_id").val(row.data().id);
                    $("#documento_proveedor_id").val(row.data().proveedor_id);
                    $("#proveedorDocumento_Nombre").val(row.data().proveedorDocumento_Nombre);
                    // $("#documento").val(row.data().proveedorDocumento_SoportePDF);
                    // modificar campo eliminado
                    $('#proveedorDocumento_Eliminado').val(1);

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar form
                    $('#form_documento').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/proveedordocumento",
                        data: {},
                        resetForm: false,
                        success: function(data)
                        {
                            // actualizar DATATABLE
                            tabla_documento(data.proveedor_id)
                            // if (TablaDocumentos == null) {
                            //     tabla_documento(data.proveedor_id);
                            // }
                            // else{
                            //     // TablaDocumentos.destroy();
                            //     // tabla_documento(data.proveedor_id);
                            //     TablaDocumentos.ajax.url("/tablaproveedordocumento/"+data.proveedor_id).load();
                            // }

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
                        beforeSend: function() {
                            //espera
                        },
                        error: function(data) {
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
                else 
                {     
                    // modificar valor del campo
                    $('#proveedorDocumento_Eliminado').val(0);
                    
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


// precionar boton nueva acreditacion
$(document).ready(function()
{
    $("#boton_nueva_acreditacion").click(function() {
        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {
            // Borrar formulario
            $('#form_acreditacion').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#acreditacion_id").val(0);
            $("#acreditacion_proveedor_id").val($("#proveedor_id").val());
            $("#acreditacion_Eliminado").val(0);

            // seleccionar TAB
            $( "#tab1_menu_acreditacion" ).click();

            // requerir campo DOCUMENTO
            $('#documentopdf').attr('required', true);

            // Abrir modal
            $('#modal_acreditacion').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});


// requerir campo EMPRESA SUBCONTRATADA
function modificar_campo(valor)
{
    if (valor == 2) {
        // requerir campo SUBCONTRATADO
        $('#acreditacion_EmpresaSub').attr('required', true);
    }
    else
    {
        $('#acreditacion_EmpresaSub').val('');
        $('#acreditacion_EmpresaSub').attr('required', false);
    }
}


function tabla_acreditaciones(id)
{
    try 
    {
        var ruta = "/tablaproveedoracreditacion/"+id;

        if (TablaAcreditaciones != null)
        {
            TablaAcreditaciones.clear().draw();
            TablaAcreditaciones.ajax.url(ruta).load();
        }
        else
        {
            TablaAcreditaciones = $('#tabla_acreditaciones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_acreditaciones(id);
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
                        "data": "servicio_tipo",
                        "defaultContent": "-"
                    },
                    {
                        "data": "entidad_numero",
                        "defaultContent": "-"
                    },
                    {
                        "data": "area",
                        "defaultContent": "-"
                    },
                    {
                        "data": 'alcance',
                        "defaultContent": "-"
                    },
                    {
                        "data": "Vigencia_texto", //acreditacion_Vigencia
                        "defaultContent": "-"
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
                    }
                ],
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "Todos"]],
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_acreditaciones();
    }
}


// mostrar documento
$(document).ready(function()
{
    $('#tabla_acreditaciones tbody').on('click', 'td.Pdf', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaAcreditaciones.row( tr );

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().catTipoAcreditacion_Nombre+', '+row.data().acreditacion_Entidad+', '+row.data().acreditacion_Numero);

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/veracreditaciondocumento/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/veracreditaciondocumento/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/veracreditaciondocumento/'+row.data().id);

        console.log(row.data().id);
        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/veracreditaciondocumento/'+row.data().id + '/'+ 0);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/veracreditaciondocumento/'+row.data().id+'/'+ 0);
        }
    });
});


// Seleccionar y editar acreditacion
$(document).ready(function()
{
    // mostrar PDF
    $('#tabla_acreditaciones tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaAcreditaciones.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_acreditacion').each(function(){
                this.reset();
            });

            // Llenar campos form
            // $("#acreditacion_Servicio").val(row.data().acreditacion_Servicio);
            // $("#acreditacion_EmpresaSub").val(row.data().acreditacion_EmpresaSub);
            $("#acreditacion_Tipo").val(row.data().acreditacion_Tipo);
            $("#acreditacion_Entidad").val(row.data().acreditacion_Entidad);
            $("#acreditacion_Numero").val(row.data().acreditacion_Numero);
            $("#cat_area_id").val(row.data().cat_area_id);
            $("#acreditacion_Expedicion").val(row.data().acreditacion_Expedicion);
            $("#acreditacion_Vigencia").val(row.data().acreditacion_Vigencia);
            // $("#campo_file_acreditacion").val(row.data().documentopdf);

            // actualizar campos HIDDEN
            $("#acreditacion_id").val(row.data().id);
            $("#acreditacion_proveedor_id").val(row.data().proveedor_id);
            $("#acreditacion_Eliminado").val(row.data().acreditacion_Eliminado);

            // requerir campo DOCUMENTO
            $('#documentopdf').attr('required', false);

            // abrir modal
            $('#modal_acreditacion').modal({backdrop:false});

            // seleccionar TAB
            $( "#tab1_menu_acreditacion" ).click();
        }
    });
});


// Eliminar acreditacion
$(document).ready(function()
{
    $('#tabla_acreditaciones tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaAcreditaciones.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            //Parameter
            swal({   
                title: "¿Eliminar "+row.data().catTipoAcreditacion_Nombre+"?",   
                text: ""+row.data().acreditacion_Entidad+", "+row.data().acreditacion_Numero,   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    //Parameter
                    swal({   
                        title: "¿Confirme eliminar "+row.data().catTipoAcreditacion_Nombre+"?",   
                        text: ""+row.data().acreditacion_Entidad+", "+row.data().acreditacion_Numero,   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Llenar campos HIDDEN
                            $("#acreditacion_id").val(row.data().id);
                            $("#acreditacion_proveedor_id").val(row.data().proveedor_id);

                            // Llenar campos form
                            $("#acreditacion_Servicio").val(row.data().acreditacion_Servicio);
                            $("#acreditacion_EmpresaSub").val(row.data().acreditacion_EmpresaSub);
                            $("#acreditacion_Tipo").val(row.data().acreditacion_Tipo);
                            $("#acreditacion_Entidad").val(row.data().acreditacion_Entidad);
                            $("#acreditacion_Numero").val(row.data().acreditacion_Numero);
                            $("#cat_area_id").val(row.data().cat_area_id);
                            $("#acreditacion_Expedicion").val(row.data().acreditacion_Expedicion);
                            $("#acreditacion_Vigencia").val(row.data().acreditacion_Vigencia);
                            // $("#campo_file_acreditacion").val(row.data().documentopdf);
                            // modificar campo eliminado
                            $('#acreditacion_Eliminado').val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_acreditacion').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedoracreditacion",
                                data: {},
                                resetForm: false,
                                success: function(data)
                                {
                                    // Actualizar DATATABLE
                                    tabla_acreditaciones(data.proveedor_id);
                                    // if (TablaAcreditaciones == null) {
                                    //     tabla_acreditaciones(data.proveedor_id);
                                    // }
                                    // else{
                                    //     // TablaAcreditaciones.destroy();
                                    //     // tabla_acreditaciones(data.proveedor_id);
                                    //     TablaAcreditaciones.ajax.url("/tablaproveedoracreditacion/"+data.proveedor_id).load();
                                    // }

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#acreditacion_Eliminado').val(0);
                            
                            // mensaje
                            swal({
                                title: "Cancelado",
                                text: "Acción cancelada",
                                type: "error", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });   
                        } 
                    });
                }
                else
                {
                    // modificar valor del campo
                    $('#acreditacion_Eliminado').val(0);

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


function acreditaciones_lista(nombre_camposelect, proveedor_id, acreditacion_id ,editando)
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/proveedoracreditacionlista/"+proveedor_id+"/"+acreditacion_id,
        data:{},
        cache: false,
        success: function (data) {
            if (data.esAcreditacion == 0){
                $("#" + nombre_camposelect).html(data.opciones);
                $("#" + nombre_camposelect).val(0);

                if (editando == 1) {
                    
                    $("#" + nombre_camposelect).prop('disabled', false);
                    $('#alcace_aprovacion_id').prop('disabled', false);
                } else {
                    $("#" + nombre_camposelect).prop('disabled', false);
                    $('#alcace_aprovacion_id').prop('disabled', false);
                }


            }else {
                $("#" + nombre_camposelect).html(data.opciones);

                if (editando == 1) {
                    
                    $("#" + nombre_camposelect).prop('disabled', false);
                    $('#alcace_aprovacion_id').prop('disabled', false);
                } else {
                    $("#" + nombre_camposelect).prop('disabled', false);
                    $('#alcace_aprovacion_id').prop('disabled', false);
                }

            }
            
        },
        error: function(data){
            // alert('Error al consultar datos del campo Area/Acreditación');
            $("#"+nombre_camposelect).html('<option value="">Error al consultar</option>');
            return false;
        }
    });//Fin ajax
}

function aprovaciones_lista(nombre_camposelect, proveedor_id, acreditacion_id, editando)
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/proveedorAprovacionlista/"+proveedor_id+"/"+acreditacion_id,
        data:{},
        cache: false,
        success:function(data){
           
             if (data.esAprovacion == 0){
                $("#" + nombre_camposelect).html(data.opciones);
                 $("#" + nombre_camposelect).val(0);
                 
                 if (editando == 1) {
                     
                     $("#" + nombre_camposelect).prop('disabled', false);
                     $('#alcanceacreditacion_id').prop('disabled', false);

                 } else {
                     
                    $("#" + nombre_camposelect).prop('disabled', false);
                     $('#alcanceacreditacion_id').prop('disabled', false);
                }

                 

            }else {
                 $("#" + nombre_camposelect).html(data.opciones);
                 
                 if (editando == 1) {      
                     $("#" + nombre_camposelect).prop('disabled', false);
                     $('#alcanceacreditacion_id').prop('disabled', false);
                 } else {
                     
                      $("#" + nombre_camposelect).prop('disabled', false);
                     $('#alcanceacreditacion_id').prop('disabled', false);
                }
                 
            }
        },
        error: function(data){
            // alert('Error al consultar datos del campo Area/Acreditación');
            $("#"+nombre_camposelect).html('<option value="">Error al consultar</option>');
            return false;
        }
    });//Fin ajax
}


//Guardar y Editar acreditacion
$("#boton_guardar_acreditacion").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#documentopdf").val() != "")
        {
            // Tipo archivo
            var archivo = $("#documentopdf").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {            
            swal({   
                title: "¿Guardar "+$("#acreditacion_Tipo option:selected").text()+"?",   
                text: "Guardar esta información en la base de datos",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Guardar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_acreditacion').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/proveedoracreditacion",
                        data: {},
                        resetForm: false,
                        success: function(data)
                        {
                            // actualizar campo HIDDEN
                            $("#acreditacion_id").val(data.id);
                            $("#acreditacion_proveedor_id").val(data.proveedor_id);
                            $("#acreditacion_Eliminado").val(data.acreditacion_Eliminado);

                            // Actualizar DATATABLE
                            tabla_acreditaciones(data.proveedor_id);

                            // // Llenar DATATABLE acreditaciones
                            // if (TablaAcreditaciones == null) {
                            //     tabla_acreditaciones(data.proveedor_id);
                            // }
                            // else{
                            //     // TablaAcreditaciones.destroy();
                            //     // tabla_acreditaciones(data.proveedor_id);
                            //     TablaAcreditaciones.ajax.url("/tablaproveedoracreditacion/"+data.proveedor_id).load();
                            // }

                            // actualiza boton
                            $('#boton_guardar_acreditacion').html('Guardar <i class="fa fa-save"></i>');

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
                            $('#modal_acreditacion').modal('hide');
                        },
                        beforeSend: function() {
                            $('#boton_guardar_acreditacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function(data) {
                            
                            // actualiza boton
                            $('#boton_guardar_acreditacion').html('Guardar <i class="fa fa-save"></i>');

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
                else 
                {     
                    // modificar valor del campo
                    $('#acreditacion_Eliminado').val(0);
                    
                    // mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
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

    }
});


//======================================================================


function tabla_servicios(proveedor_id)
{
    try 
    {
        var ruta = "/tablaacreditacionalcances/"+proveedor_id;

        if (TablaServicios != null)
        {
            TablaServicios.clear().draw();
            TablaServicios.ajax.url(ruta).load();
        }
        else
        {
            TablaServicios = $('#tabla_servicios').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_servicios(proveedor_id);
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
                        "data": "acreditacion_numero",
                        "defaultContent": "-"
                    },
                    {
                        "data": "acreditacionAlcance_tipo",
                        "defaultContent": "-"
                    },
                    {
                        "data": "agente",
                        "defaultContent": "-"
                    },
                    {
                        "data": "normas",
                        "defaultContent": "-"
                    },
                    {
                        "data": "acreditacionAlcance_Descripcion",
                        "defaultContent": "-"
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_servicios(proveedor_id);
    }
}


// precionar boton nuevo ALCANCE servicio
$(document).ready(function() {
    $("#boton_nuevo_alcance").click(function() {
        // valida si existe un proveedor seleccionado
        $('#form_alcance_acreditacion').each(function () { 
            this.reset();
        })

        if ($("#proveedor_id").val()>0)
        {
            // Opciones a mostar en el select [Tipo alcace]
            if (proveedor_tipo == 1) //TIpo proveedor [OTRO]
            {
                $("#acreditacionAlcance_tipo option").each(function(){
                    if ($(this).val() === "Consultoría" || $(this).val() === "")
                    {
                        $(this).css('display', 'block');
                    }
                    else
                    {
                        $(this).css('display', 'none');
                    }
                });
            }
            else
            {
                $("#acreditacionAlcance_tipo option").each(function(){
                    if ($(this).val() != "Consultoría" || $(this).val() === "")
                    {
                        $(this).css('display', 'block');
                    }
                    else
                    {
                        $(this).css('display', 'none');
                    }
                });
            }

            // Borrar formulario
            $('#form_alcance_acreditacion').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#alcance_id").val(0);
            $("#alcance_acreditacion_id").val($("#acreditacion_id").val());
            $("#acreditacionAlcance_Eliminado").val(0);

            // Inicializar campos selct
            $('#prueba_id').html('<option value=""></option>');
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option>');

            // Campo acreditaciones
            if (parseInt($("#cat_tipoproveedor_id").val()) == 1)
            {
                $("#alcanceacreditacion_id").html('<option value=""></option><option value="0">N/A</option>');
            }
            else
            {
                acreditaciones_lista('alcanceacreditacion_id', $("#proveedor_id").val(), 0, 0);
                aprovaciones_lista('alcace_aprovacion_id', $("#proveedor_id").val(), 0, 0)

            }

            // Abrir modal
            $('#modal_alcance').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});


function validatipoalcance(valor, camposelect, opcion_seleccionado)
{
    // Vaciar campos
    $('#prueba_id').html('<option value=""></option>');
    $('#acreditacionAlcance_agentetipo').html('<option value=""></option>');
    $('#acreditacionAlcance_agente').val('');
    $('#acreditacionAlcance_Norma').val('');
    $('#acreditacionAlcance_Procedimiento').val('');


    // var opciontipo = parseInt(valor);
    switch (valor)
    { 
        case "":
            // Ocultar campo
            $("#div_campo_agente").css('display', 'none');
            $("#acreditacionAlcance_agente").val('');
            $("#acreditacionAlcance_agente").attr('required', false);

            // Mostrar campo
            $("#div_campo_factor").css('display', 'none');
            $("#prueba_id").val('');
            $("#prueba_id").attr('required', true);
            break;
        case "Químico":
            // Ocultar campo


            $("#div_campo_factor").css('display', 'none');

            $("#prueba_id").val('');
            $("#prueba_id").attr('required', false);

            // Mostrar campo
            $("#div_campo_agente").css('display', 'block');
            $("#acreditacionAlcance_agente").selectize();
            $("#acreditacionAlcance_agente").val('');
            $("#acreditacionAlcance_agente").prop('required', true);

            // NO requerir Campo select agentetipo
            $('#acreditacionAlcance_agentetipo').prop('required', false);
            $('#acreditacionAlcance_agentetipo').prop('disabled', true);

            // Actualiza campo NORMAS
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/acreditacionalcanceagentenormas/15",
                data:{},
                cache: false,
                success: function (dato) {

                    data = dato.normas
                    normas = ''
                    procedimientos = ''
                    data.forEach(item => {
                        if (item.tipo == 'Norma') {
                            normas += item.catpruebanorma_numero + ', '
                        } else if (item.tipo == 'Procedimiento') {
                            procedimientos += item.catpruebanorma_numero + ', '   
                        }

                    });

                    $('#acreditacionAlcance_Norma').val(normas);
                    $('#acreditacionAlcance_Procedimiento').val(procedimientos);

                    // $('#acreditacionAlcance_Norma').val(dato.normas);
                },
                error: function(dato){
                    $('#acreditacionAlcance_Norma').val('');
                    return false;
                }
            });//Fin ajax
            break;
        default:
            // Ocultar campo
            $("#div_campo_agente").css('display', 'none');
            $("#acreditacionAlcance_agente").val('');
            $("#acreditacionAlcance_agente").attr('required', false);

            // Mostrar campo
            $("#div_campo_factor").css('display', 'block');
            $("#prueba_id").val('');
            $("#prueba_id").attr('required', true);

            // Actualiza campo select
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/acreditacionalcancetipoagente/"+valor+"/"+opcion_seleccionado,
                data:{},
                cache: false,
                success:function(dato){
                    $('#prueba_id').html(dato.opciones);
                },
                error: function(dato){
                    $('#prueba_id').html('<option value=""></option>');
                    return false;
                }
            });//Fin ajax


              // Actualiza campo NORMAS o Metodos
            // $.ajax({
            //     type: "GET",
            //     dataType: "json",
            //     url: "/acreditacionalcanceagentenormas/" + opcion_seleccionado,
            //     data:{},
            //     cache: false,
            //     success:function(dato){
            //     //        if (dato.esMetodo == 1) {
            //     //     $('#acreditacionAlcance_Metodo').prop('disabled', false);
            //     //     $('#acreditacionAlcance_Norma').prop('disabled', true);
            //     //     $('#acreditacionAlcance_Norma').prop('required', false);
            //     //     $('#acreditacionAlcance_Norma').val('')

            //     //     $('#acreditacionAlcance_Metodo').val(dato.normas);
                    
            //     // } else {

            //     //     $('#acreditacionAlcance_Norma').prop('disabled', false);
            //     //     $('#acreditacionAlcance_Metodo').prop('disabled', true);
            //     //     $('#acreditacionAlcance_Metodo').prop('required', false);
            //     //     $('#acreditacionAlcance_Metodo').val('')

            //     //     $('#acreditacionAlcance_Norma').val(dato.normas);
            //         // }
                    
            //         data = dato.normas
            //         normas = ''
            //         procedimientos = ''
            //         metodos = ''

            //         data.forEach(item => {
            //             if (item.tipo == 'Norma') {
            //                 normas += item.catpruebanorma_numero + ', '
            //             } else if (item.tipo == 'Procedimiento') {
            //                 procedimientos += item.catpruebanorma_numero + ', '   
            //             } else {
            //                 metodos += item.catpruebanorma_numero + ', '
            //             }

            //         });

            //         $('#acreditacionAlcance_Norma').val(normas);
            //         $('#acreditacionAlcance_Procedimiento').val(procedimientos);
            //         $('#acreditacionAlcance_Metodo').val(metodos);

            //     },
            //     error: function(dato){
            //         $('#acreditacionAlcance_Norma').val('');
            //         return false;
            //     }
            // });//Fin ajax
            break;
    }
}


function alcanceagente(select_agente)
{
    // Llenar campo nombre del agente
    $("#acreditacionAlcance_agente").val($("#"+select_agente.id+" option:selected").text());

    var agente_id = parseInt(select_agente.value);
    switch (agente_id)
    { 
        // case 1: //RUIDO
        //     // activar campo
        //     $('#acreditacionAlcance_agentetipo').attr('disabled', false);
        //     $('#acreditacionAlcance_agentetipo').attr('required', true);
        //     // llenar select tipo de alcance agente
        //     $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Sonometría">Sonometría</option><option value="Dosimetría">Dosimetría</option>');
        //     break;
        // case 2: //VIBRACIÓN
        //     // activar campo
        //     $('#acreditacionAlcance_agentetipo').attr('disabled', false);
        //     $('#acreditacionAlcance_agentetipo').attr('required', true);
        //     // llenar select tipo de alcance agente
        //     $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="VCE">VCE (Vibraciones de cuerpo entero)</option><option value="VES">VES (Vibraciones en extremidades superiores)</option>');
        //     break;
        // case 3: //TEMPERATURA
        //     // activar campo
        //     $('#acreditacionAlcance_agentetipo').attr('disabled', false);
        //     $('#acreditacionAlcance_agentetipo').attr('required', true);
        //     // llenar select tipo de alcance agente
        //     $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="TE">TE (Temperaturas elevadas)</option><option value="TA">TA (Temperaturas abatidas)</option>');
        //     break;
        case 9: //AGUA
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
            break;
        case 10: //HIELO
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
            break;
        case 11: //ALIMENTO
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Crudos">Crudos</option><option value="Cocidos">Cocidos</option>');
            break;
        case 12: //SUPERFICIES
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Vivas">Vivas</option><option value="Inertes">Inertes</option>');
            break;
        case 17: //Mapa de riesgos
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Tipo 1">Tipo 1</option><option value="Tipo 2">Tipo 2</option>');
            break;
        case 5: //Radiaciones Ionizantes
             // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Rayos X">Rayos X</option><option value="Rayos gamma">Rayos gamma</option>');
        
            break;
        case 6: // Radiaciones No Ionizantes
            // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Radiofrecuencias">Radiofrecuencias</option><option value="Microondas">Microondas</option><option value="Espectro visible">Espectro visible</option><option value="Ultravioleta (UV)">Ultravioleta (UV)</option>');
            
            break;
        case 3: //Temperatura
              // activar campo
            $('#acreditacionAlcance_agentetipo').prop('disabled', false);
            $('#acreditacionAlcance_agentetipo').prop('required', true);
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Elevadas">Elevadas</option><option value="Abatidas">Abatidas</option>');
            break;
        default:
            // llenar select tipo de alcance agente
            $('#acreditacionAlcance_agentetipo').html('<option value=""></option>');
            // DESACTIVAR campo
            $('#acreditacionAlcance_agentetipo').prop('required', false);
            $('#acreditacionAlcance_agentetipo').prop('disabled', true);
            break;
    }

    // Actualiza campo NORMAS
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/acreditacionalcanceagentenormas/"+agente_id,
        data:{},
        cache: false,
        success: function (dato) {


            // if (dato.esMetodo == 1) {
            //     $('#acreditacionAlcance_Metodo').prop('disabled', false);
            //     $('#acreditacionAlcance_Norma').prop('disabled', true);
            //     $('#acreditacionAlcance_Norma').prop('required', false);
            //     $('#acreditacionAlcance_Norma').val('')

            //     $('#acreditacionAlcance_Metodo').val(dato.normas);
                
            // } else {

            //     $('#acreditacionAlcance_Norma').prop('disabled', false);
            //     $('#acreditacionAlcance_Metodo').prop('disabled', true);
            //     $('#acreditacionAlcance_Metodo').prop('required', false);
            //     $('#acreditacionAlcance_Metodo').val('')

            //     $('#acreditacionAlcance_Norma').val(dato.normas);
            // }

            data = dato.normas
            normas = ''
            procedimientos = ''
            metodos = ''

            data.forEach(item => {
                if (item.tipo == 'Norma') {
                    normas += item.catpruebanorma_numero + ', '
                } else if (item.tipo == 'Procedimiento') {
                    procedimientos += item.catpruebanorma_numero + ', '   
                } else {
                    metodos += item.catpruebanorma_numero + ', '
                }

            });

            $('#acreditacionAlcance_Norma').val(normas);
            $('#acreditacionAlcance_Procedimiento').val(procedimientos);
            $('#acreditacionAlcance_Metodo').val(metodos);
        },
        error: function(dato){
            $('#acreditacionAlcance_Norma').val('');
            return false;
        }
    });//Fin ajax
}


function alcanceservicios_lista(nombre_camposelect, proveedor_id, alcanceservicio_id)
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/proveedoralcanceservicioslista/"+proveedor_id+"/"+alcanceservicio_id,
        data:{},
        cache: false,
        success:function(data){
            $("#"+nombre_camposelect).html(data.opciones);
        },
        error: function(data){
            // alert('Error al consultar datos del campo Area/Acreditación');
            $("#"+nombre_camposelect).html('<option value="">Error al consultar</option>');
            return false;
        }
    });//Fin ajax
}


// Seleccionar y editar alcance
$(document).ready(function()
{
    $('#tabla_servicios tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaServicios.row(tr);



        $('#form_alcance_acreditacion').each(function () {
            this.reset();
        })
        




        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_alcance_acreditacion').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#alcance_id").val(row.data().id);
            $("#alcance_acreditacion_id").val(row.data().acreditacion_id);
            $("#acreditacionAlcance_Eliminado").val(row.data().acreditacionAlcance_Eliminado);

            // Llenar campo select agente
            validatipoalcance(row.data().acreditacionAlcance_tipo, 'prueba_id', row.data().prueba_id);

            // Llenar campos form
            $("#acreditacionAlcance_tipo").val(row.data().acreditacionAlcance_tipo);
            // $("#prueba_id").val(row.data().prueba_id);
            $("#acreditacionAlcance_agente").val(row.data().acreditacionAlcance_agente);


            // $("#acreditacionAlcance_agentetipo").val(row.data().acreditacionAlcance_agentetipo);
            // $("#acreditacionAlcance_Norma").val(row.data().acreditacionAlcance_Norma);
            $("#acreditacionAlcance_Descripcion").val(row.data().acreditacionAlcance_Descripcion);
            $("#acreditacionAlcance_Observacion").val(row.data().acreditacionAlcance_Observacion);


            
            $("#acreditacionAlcance_Norma").val(row.data().acreditacionAlcance_Norma);
            $("#alcace_aprovacion_id").val(row.data().alcace_aprovacion_id);
            $("#acreditacionAlcance_Procedimiento").val(row.data().acreditacionAlcance_Procedimiento);
            $("#acreditacionAlcance_Metodo").val(row.data().acreditacionAlcance_Metodo);
            $("#requiere_aprobacion").val(row.data().requiere_aprobacion);

            // Campo Select agentetipo
            var agente_id = parseInt(row.data().prueba_id);
            switch (agente_id)
            { 
                case 9: //AGUA
                    // activar campo
                    $('#acreditacionAlcance_agentetipo').attr('disabled', false);
                    $('#acreditacionAlcance_agentetipo').attr('required', true);
                    // llenar select tipo de alcance agente
                    $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
                    break;
                case 10: //HIELO
                    // activar campo
                    $('#acreditacionAlcance_agentetipo').attr('disabled', false);
                    $('#acreditacionAlcance_agentetipo').attr('required', true);
                    // llenar select tipo de alcance agente
                    $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Microbiológico">Microbiológico</option><option value="Fisicoquímico">Fisicoquímico</option>');
                    break;
                case 11: //ALIMENTO
                    // activar campo
                    $('#acreditacionAlcance_agentetipo').attr('disabled', false);
                    $('#acreditacionAlcance_agentetipo').attr('required', true);
                    // llenar select tipo de alcance agente
                    $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Crudos">Crudos</option><option value="Cocidos">Cocidos</option>');
                    break;
                case 12: //SUPERFICIES
                    // activar campo
                    $('#acreditacionAlcance_agentetipo').attr('disabled', false);
                    $('#acreditacionAlcance_agentetipo').attr('required', true);
                    // llenar select tipo de alcance agente
                    $('#acreditacionAlcance_agentetipo').html('<option value=""></option><option value="Vivas">Vivas</option><option value="Inertes">Inertes</option>');
                    break;
                default:
                    // llenar select tipo de alcance agente
                    $('#acreditacionAlcance_agentetipo').html('<option value=""></option>');
                    // DESACTIVAR campo
                    $('#acreditacionAlcance_agentetipo').attr('required', false);
                    $('#acreditacionAlcance_agentetipo').attr('disabled', true);
                    break;
            }
            $("#acreditacionAlcance_agentetipo").val(row.data().acreditacionAlcance_agentetipo);

            // Campo acreditaciones

            if (parseInt(row.data().proveedor.cat_tipoproveedor_id) == 1)
            {
                $("#alcanceacreditacion_id").html('<option value=""></option><option value="0">N/A</option>');
            }
            else
            {   
             
                acreditaciones_lista('alcanceacreditacion_id', row.data().proveedor_id, row.data().acreditacion_id, 1);
                aprovaciones_lista('alcace_aprovacion_id', row.data().proveedor_id, row.data().alcace_aprovacion_id, 1)

            }

            // $("#alcanceacreditacion_id").val(row.data().alcanceacreditacion_id);
            

            // abrir modal
            $('#modal_alcance').modal({ backdrop: false });
            //Mostramos una alerta para que el usuario sepa que la informacion se esta cargando
            swal({   
                title: "Espere un momento",   
                text: "Estamos cargando la información...",
                type: "info",   
                showCancelButton: false,
                showConfirmButton: false,   
                timer: 4000,   
            })
        }
    });
});

//ESTAS VALIDACIONES SON PORQUE SI TIENE ACREDITACION NO TIENE APROVACION Y VISEVERSA
// $('#modal_alcance').on('shown.bs.modal', function () {
//     $('#alcace_aprovacion_id').prop('disabled', false);
//     $('#alcanceacreditacion_id').prop('disabled', false);

// })

//Validacion de la lista de acreditacion
$('#alcanceacreditacion_id').on('change', function () {
    
    valor = $(this).val();
    if (valor != '' ) {
        //Si el select de acreditaciones tiene un valor diferente a 0 desabilitamos el select de aprovaciones
        $('#alcace_aprovacion_id').val();
        $('#alcace_aprovacion_id').prop('disabled', false);
        console.log(valor + ' entre en el if')
        
    } else {
        console.log(valor + ' entre en el else')
        $('#alcace_aprovacion_id').val();
        $('#alcace_aprovacion_id').prop('disabled', false);
    }
})

//Validacion de la lista de aprovaciones
$('#alcace_aprovacion_id').on('change', function () {
    
    valor = $(this).val();
    if (valor != '') {
        $('#alcanceacreditacion_id').val();
        $('#alcanceacreditacion_id').prop('disabled', false);

    } else {
        $('#alcanceacreditacion_id').val();
        $('#alcanceacreditacion_id').prop('disabled', false);
    }
})


// Eliminar alcance
$(document).ready(function()
{
    $('#tabla_servicios tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaServicios.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            // CONFIRMAR
            swal({   
                title: "¿Eliminar alcance?",   
                text: ""+row.data().acreditacionAlcance_agente,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar alcance?",   
                        text: ""+row.data().acreditacionAlcance_agente,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Llenar campos form
                            $("#prueba_id").val(row.data().prueba_id);
                            $("#acreditacionAlcance_Norma").val(row.data().acreditacionAlcance_Norma);
                            $("#acreditacionAlcance_Metodo").val(row.data().acreditacionAlcance_Metodo);
                            $("#acreditacionAlcance_Observacion").val(row.data().acreditacionAlcance_Observacion);

                            // Llenar campos HIDDEN
                            $("#alcance_id").val(row.data().id);
                            $("#alcance_acreditacion_id").val(row.data().acreditacion_id);
                            $("#acreditacionAlcance_Eliminado").val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_alcance_acreditacion').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/acreditacionalcances",
                                data: {},
                                resetForm: false,
                                success: function(data)
                                {
                                    // actualizar DATATABLE
                                    tabla_servicios($("#proveedor_id").val());
                                    tabla_acreditaciones($("#proveedor_id").val());

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#acreditacionAlcance_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#acreditacionAlcance_Eliminado').val(0);
                    
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


//Guardar y Editar prueba
$("#boton_guardar_alcance").click(function() {
    var valida = this.form.checkValidity();
    if (valida)
    {
        swal({   
            title: "¿Guardar alcance?",   
            text: "Guardar esta información en la base de datos",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Guardar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm)
            {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_alcance_acreditacion').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: "/acreditacionalcances",
                    data: {
                        proveedor_id: $("#proveedor_id").val()
                    },
                    resetForm: false,
                    success: function(data)
                    {
                        // actualizar DATATABLE
                        tabla_servicios($("#proveedor_id").val());
                        tabla_acreditaciones($("#proveedor_id").val());

                        // actualiza boton
                        $('#boton_guardar_alcance').html('Guardar <i class="fa fa-save"></i>');

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
                        $('#modal_alcance').modal('hide');
                    },
                    beforeSend: function() {
                        $('#boton_guardar_alcance').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(data) {
                        
                        // actualiza boton
                        $('#boton_guardar_alcance').html('Guardar <i class="fa fa-save"></i>');

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
            else 
            {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
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
});


//======================================================================
// precionar boton nuevo signatario
$(document).ready(function()
{
    $("#boton_nuevo_equipo").click(function()
    {   
        equipo_id = 0
        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {

            // Mostrar Foto Mapa instalacion en el INPUT
            if ($('#foto_equipo').data('dropify'))
            {
                $('#foto_equipo').dropify().data('dropify').resetPreview();
                $('#foto_equipo').dropify().data('dropify').clearElement();
                // $('#signatariofoto').dropify().data('dropify').destroy();
                // $('#signatariofoto').dropify().data('dropify').init();
            }
            else
            {
                // $('#signatariofoto').attr('data-height', 400);
                // $('#signatariofoto').attr('data-default-file', imagenUrl);
                $('#foto_equipo').dropify({
                    messages: {
                        'default': 'Arrastre la imagen aquí o haga click',
                        'replace': 'Arrastre la imagen o haga clic para reemplazar',
                        'remove':  'Quitar',
                        'error':   'Ooops, ha ocurrido un error.'
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
            $('#foto_equipo').attr('required', false);

            // Borrar formulario
             $('#form_equipo').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#equipo_id").val(0);
            $("#equipo_proveedor_id").val($("#proveedor_id").val());
            $("#equipo_Eliminado").val(0);

            $('#requiere_calibracion1').prop('checked', false);
            $('#requiere_calibracion').val('No');
            $('.calibracion-requrida').hide();

            $("#checkbox_estado_equipo").prop('checked', false);
            $("#checkbox_estado_equipo").prop('disabled', 'disabled');


            // seleccionar TAB1
            $( "#tab1_equipo_info" ).click();

         
           // Abrir modal
            $('#modal_equipo').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});

$(document).ready(function() {
    $("#boton_nuevo_equipo_documento").click(function() {
        // valida si existe un signatario seleccionado
        if (equipo_id != 0)
        {
            // Borrar formulario
            $('#form_equipo_documento').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#equipo_doc_id").val(0);
            $("#equipo_documento_id").val($("#equipo_id").val());
            $("#equipo_documento_proveedor_id").val($("#proveedor_id").val());
            $("#EQUIPO_DOCUMENTO_ELIMINADO").val(1);

            // requerir campo DOCUMENTO
            $('#EQUIPO_PDF').attr('required', true);

            // Abrir modal
            $('#modal_equipo_documento').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un equipo",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


function tabla_equipos(id)
{
    try 
    {
        var ruta = "/tablaproveedorequipo/"+id;

        if (TablaEquipos != null)
        {
            TablaEquipos.clear().draw();
            TablaEquipos.ajax.url(ruta).load();
        }
        else
        {
            TablaEquipos = $('#tabla_equipos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_equipos(id);
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
                        "data": "descripcion",
                        "defaultContent": "-"
                    },
                    {
                        "data": "marca",
                        "defaultContent": "-"
                    },
                    {
                        "data": "modelo",
                        "defaultContent": "-"
                    },
                    {
                        "data": "serie",
                        "defaultContent": "-"
                    },
                    {
                        "data": "Vigencia_texto", //equipo_FechaCalibracion
                        "defaultContent": "-"
                    },
                    // {
                    //     "data": 'certificado_pdf',
                    //     "defaultContent": "-",
                    //     // "className": 'Pdf',
                    //     "orderable": false,
                    //     // "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>',
                    // },
                    // {
                    //     "data": 'carta_pdf',
                    //     "defaultContent": "-",
                    //     // "className": 'carta',
                    //     "orderable": false,
                    //     // "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>',
                    // },
                    {
                        "data": "EstadoActivo_texto",
                        "defaultContent": "-"
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_equipos(id);
    }
}




function equipovalida_alcanceservicio(valor)
{
    var opcion = parseInt(valor);

    if (opcion == 0 || opcion == '') // Equipo sin certificado y vigencia
    {
        // Vaciar campos
        $("#equipo_TipoCalibracion").val('');
        $("#equipo_certificadoCalibracion").val('');
        $("#equipo_FechaCalibracion").val('');
        $("#equipo_VigenciaCalibracion").val('');
        $("#campo_file_equipo").val('');

        // No requerir campos
        $("#equipo_TipoCalibracion").attr('required', false);
        $("#equipo_certificadoCalibracion").attr('required', false);
        $("#equipo_FechaCalibracion").attr('required', false);
        $("#equipo_VigenciaCalibracion").attr('required', false);
        $("#campo_file_equipo").attr('required', false);
        // $("#equipodocumentopdf").attr('required', false);

        // Desactivar campos
        $("#equipo_TipoCalibracion").attr('readonly', false);
        $("#equipo_certificadoCalibracion").attr('readonly', false);
        $("#equipo_FechaCalibracion").attr('readonly', false);
        $("#equipo_VigenciaCalibracion").attr('readonly', false);
        $("#campo_file_equipo").attr('readonly', false);
    }
    else // Equipo con certificado y vigencia
    {
        // Desactivar campos
        $("#equipo_TipoCalibracion").attr('readonly', false);
        $("#equipo_certificadoCalibracion").attr('readonly', false);
        $("#equipo_FechaCalibracion").attr('readonly', false);
        $("#equipo_VigenciaCalibracion").attr('readonly', false);
        $("#campo_file_equipo").attr('readonly', false);

        // No requerir campos
        $("#equipo_TipoCalibracion").attr('required', false);
        $("#equipo_certificadoCalibracion").attr('required', false);
        $("#equipo_FechaCalibracion").attr('required', false);
        $("#equipo_VigenciaCalibracion").attr('required', false);
        $("#campo_file_equipo").attr('required', false);
        $("#equipodocumentopdf").attr('required', false);
    }
}


// mostrar certificado PDF
$('#tabla_equipos tbody').on('click', 'td>button.certificadopdf', function ()
{
    var tr = $(this).closest('tr');
    var row = TablaEquipos.row( tr );

    // abrir modal
    $('#modal_visor').modal({backdrop:false});

    // TITULO DLE VISOR
    $('#nombre_documento_visor').html(row.data().equipo_Descripcion);

    // // Opciones Mostrar PDF
    // $('#visor_documento').attr('src', '/verequipodocumento/'+row.data().id);
    // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/verequipodocumento/'+row.data().id);
    // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumento/'+row.data().id);

    // Mostrar PDF
    if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
    {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumento/'+row.data().id+'/'+1);
    }
    else
    {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/'+row.data().id+'/'+1);
    }
});


// mostrar PDF
$('#tabla_equipos tbody').on('click', 'td>button.cartapdf', function ()
{
    var tr = $(this).closest('tr');
    var row = TablaEquipos.row( tr );

    // abrir modal
    $('#modal_visor').modal({backdrop:false});

    // TITULO DLE VISOR
    $('#nombre_documento_visor').html('Carta - '+row.data().equipo_Descripcion);

    // // Opciones Mostrar PDF
    // $('#visor_documento').attr('src', '/verequipodocumento/'+row.data().id);
    // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/verequipodocumento/'+row.data().id);
    // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumento/'+row.data().id);

    // Mostrar PDF
    if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
    {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumento/'+row.data().id+'/'+2);
    }
    else
    {
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/'+row.data().id+'/'+2);
    }
});


// Seleccionar y editar EQUIPO
$(document).ready(function()
{
    $('#tabla_equipos tbody').on('click', 'td.Editar', function ()
    {   
        var tr = $(this).closest('tr');
        var row = TablaEquipos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
             // Borrar formulario
            $('#form_equipo').each(function(){
                this.reset();
            });

            equipo_id = row.data().id

            // Llenar campos form
            $("#equipo_uso").val(row.data().equipo_uso);
            $("#equipo_Descripcion").val(row.data().equipo_Descripcion);
            $("#equipo_Marca").val(row.data().equipo_Marca);
            $("#equipo_Modelo").val(row.data().equipo_Modelo);
            $("#equipo_Serie").val(row.data().equipo_Serie);
            $("#equipo_Tipo").val(row.data().equipo_Tipo);
            $("#equipo_PesoNeto").val(row.data().equipo_PesoNeto);
            $("#equipo_CostoAprox").val(row.data().equipo_CostoAprox);
            $("#equipo_TipoCalibracion").val(row.data().equipo_TipoCalibracion);
            $("#equipo_certificadoCalibracion").val(row.data().equipo_certificadoCalibracion);
            $("#equipo_FechaCalibracion").val(row.data().equipo_FechaCalibracion);
            $("#equipo_VigenciaCalibracion").val(row.data().equipo_VigenciaCalibracion);
            $("#folio_factura").val(row.data().folio_factura);
            $("#unidad_medida").val(row.data().unidad_medida);
            $("#numero_inventario").val(row.data().numero_inventario);

            //Mostrar la foto del equipo
            // obtener extencion archivo

            $('#foto_equipo').attr('required', false);
            
            if (row.data().equipo_imagen) {

                var archivo = row.data().equipo_imagen;
                var extension = archivo.substring(archivo.lastIndexOf("."));
                // Obtener FOTO
                var imagenUrl = '/verequipofoto/' + row.data().id + extension;

                // Mostrar Foto en el INPUT
                if ($('#foto_equipo').data('dropify')) {
                    $('#foto_equipo').dropify().data('dropify').destroy();
                    // $('.dropify-wrapper').css('height', 400);
                    $('#foto_equipo').dropify().data('dropify').settings.defaultFile = imagenUrl;
                    $('#foto_equipo').dropify().data('dropify').init();
                }
                else {
                    // $('#signatariofoto').attr('data-height', 400);
                    $('#foto_equipo').attr('data-default-file', imagenUrl);
                    $('#foto_equipo').dropify({
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
                $('#foto_equipo').dropify().data('dropify').resetPreview();
                $('#foto_equipo').dropify().data('dropify').clearElement();
            }


            

            if (row.data().equipo_EstadoActivo==1)
            {
                $("#checkbox_estado_equipo").prop('checked', true);
            }
            else
            {
                $("#checkbox_estado_equipo").prop('checked', false);
            }
        
        
        
        
        
        
          if (parseInt($("#boton_bloquear_proveedor").val()) == 0)
         {
            $("#checkbox_estado_equipo").attr('disabled', false);
        }
        else
        {
            $("#checkbox_estado_equipo").attr('disabled', true);
        }

            // actualizar campos HIDDEN
            $("#equipo_id").val(row.data().id);
            $("#equipo_proveedor_id").val(row.data().proveedor_id);
            $("#equipo_Eliminado").val(row.data().equipo_Eliminado);

            // Valida si es un equipo con vigencia
            equipovalida_alcanceservicio(row.data().equipo_uso);


            var requiereCalibracion = row.data().requiere_calibracion;
            if (requiereCalibracion === 'Si') {
                $('#requiere_calibracion1').prop('checked', true);
                $('#requiere_calibracion').val('Si');
                $('.calibracion-requrida').show();
            } else {
                $('#requiere_calibracion1').prop('checked', false);
                $('#requiere_calibracion').val('No');
                $('.calibracion-requrida').hide();
            }

            
            //lLAMOS NUESTRA TABLA DE DOCUMENTOS
            tabla_equipos_documentos(row.data().id)

             // seleccionar TAB1
            $( "#tab1_equipo_info" ).click();

            // abrir modal
            $('#modal_equipo').modal({backdrop:false});
        }
    });
});

//Guardar y Editar documentos de equipos
$("#boton_guardar_equipo_documento").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#EQUIPO_PDF").val() != "")
        {
            // Tipo archivo
            var archivo = $("#EQUIPO_PDF").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_equipo_documento').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/proveedorequipo",
                data: {
                    api: 2
                },
                resetForm: false,
                success: function(data)
                {
                    // actualiza tabla
                    tabla_equipos_documentos(data.EQUIPO_ID);

                    // actualiza boton
                    $('#boton_guardar_equipo_documento').html('Guardar <i class="fa fa-save"></i>');

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
                    $('#modal_equipo_documento').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_equipo_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_equipo_documento').html('Guardar <i class="fa fa-save"></i>');

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
});


// Eliminar EQUIPO
$(document).ready(function()
{
    $('#tabla_equipos tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaEquipos.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar equipo?",   
                text: ""+row.data().equipo_Descripcion,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirma eliminar equipo?",   
                        text: ""+row.data().equipo_Descripcion,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_equipo').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            // $("#prueba_id").val(row.data().prueba_id);
                            $("#equipoacreditacionalcance_id").val(row.data().acreditacionalcance_id);
                            $("#cat_prueba_id").val(row.data().cat_prueba_id);
                            $("#equipo_Descripcion").val(row.data().equipo_Descripcion);
                            $("#equipo_Marca").val(row.data().equipo_Marca);
                            $("#equipo_Modelo").val(row.data().equipo_Modelo);
                            $("#equipo_Serie").val(row.data().equipo_Serie);
                            $("#equipo_Tipo").val(row.data().equipo_Tipo);
                            $("#unidad_medida").val(row.data().unidad_medida);
                            $("#equipo_PesoNeto").val(row.data().equipo_PesoNeto);
                            $("#equipo_CostoAprox").val(row.data().equipo_CostoAprox);
                            $("#equipo_TipoCalibracion").val(row.data().equipo_TipoCalibracion);
                            $("#equipo_certificadoCalibracion").val(row.data().equipo_certificadoCalibracion);
                            $("#equipo_FechaCalibracion").val(row.data().equipo_FechaCalibracion);
                            $("#equipo_VigenciaCalibracion").val(row.data().equipo_VigenciaCalibracion);
                            $("#campo_file_equipo").val(row.data().campo_file_equipo);

                            // actualizar campos HIDDEN
                            $("#equipo_id").val(row.data().id);
                            $("#equipo_proveedor_id").val(row.data().proveedor_id);
                            $("#equipo_Eliminado").val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_equipo').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedorequipo",
                                data: {
                                    api:1
                                },
                                resetForm: false,
                                success: function(data)
                                {
                                    // Actualizar DATATABLE
                                    tabla_equipos(data.proveedor_id);
                                    
                              
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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#equipo_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#equipo_Eliminado').val(0);
                    
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


//Guardar y Editar equipo
$("#boton_guardar_equipo").click(function () {
    
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        swal({   
            title: "¿Desea guardar este equipo?",   
            text: "Registre un nuevo equipo, para hacer uso de él.",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Guardar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm)
            {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_equipo').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: "/proveedorequipo",
                    data: {
                        api : 1
                    },
                    resetForm: false,
                    success: function(data)
                    {
                        // Actualizar DATATABLE
                        tabla_equipos(data.proveedor_id);

                        // // Llenar DATATABLE alcances
                        // if (TablaEquipos == null) {
                        //     tabla_equipos(data.proveedor_id);
                        // }
                        // else{
                        //     // TablaEquipos.destroy();
                        //     // tabla_equipos(data.proveedor_id);
                        //     TablaEquipos.ajax.url("/tablaproveedorequipo/"+data.proveedor_id).load();
                        // }

                        // actualiza boton
                        $('#boton_guardar_equipo').html('Guardar <i class="fa fa-save"></i>');

                        $("#checkbox_estado_equipo").prop('disabled', false);
                        $("#checkbox_estado_equipo").prop('checked', true);

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
                        $('#modal_equipo').modal('hide');
                    },
                    beforeSend: function() {
                        $('#boton_guardar_equipo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(data) {
                        
                        // actualiza boton
                        $('#boton_guardar_equipo').html('Guardar <i class="fa fa-save"></i>');

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
            else 
            {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
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
});

function tabla_equipos_documentos(id)
{
    try 
    {
        var ruta = "/tablaequipodocumento/"+id;

        if (TablaEquiposDocumentos != null)
        {
            TablaEquiposDocumentos.clear().draw();
            TablaEquiposDocumentos.ajax.url(ruta).load();
        }
        else
        {
            TablaEquiposDocumentos = $('#tabla_equipos_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_equipos_documentos(id);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "numero_registro" 
                    },
                    {
                        "data": "NOMBRE_DOCUMENTO"
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
                "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_equipos_documentos(id);
    }
}


 $('#tabla_equipos_documentos tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaEquiposDocumentos.row( tr );

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().NOMBRE_DOCUMENTO);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumentopdf/'+row.data().ID_EQUIPO_DOCUMENTO);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumentopdf/'+row.data().ID_EQUIPO_DOCUMENTO);
        }
 });
    
 // SELECCIONAR SIGNATARIO DOCUMENTO
$(document).ready(function()
{
    $('#tabla_equipos_documentos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaEquiposDocumentos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_equipo_documento').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#DOCUMENTO_TIPO").val(row.data().DOCUMENTO_TIPO);
            $("#NOMBRE_DOCUMENTO").val(row.data().NOMBRE_DOCUMENTO);

           

            // requerir campo DOCUMENTO
            $('#EQUIPO_PDF').attr('required', false);

            // actualizar campos HIDDEN
            $("#equipo_doc_id").val(row.data().ID_EQUIPO_DOCUMENTO);
            $("#equipo_documento_id").val(row.data().EQUIPO_ID);
          
            $("#EQUIPO_DOCUMENTO_ELIMINADO").val(1);

            // abrir modal
            $('#modal_equipo_documento').modal({backdrop:false});
        }
    });
});


// Eliminar signatario DOCUMENTO
$(document).ready(function()
{
    $('#tabla_equipos_documentos tbody').on('click', 'td.Eliminar', function() {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaEquiposDocumentos.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar documento?",   
                text: ""+row.data().NOMBRE_DOCUMENTO,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar documento?",   
                        text: ""+row.data().NOMBRE_DOCUMENTO,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_equipo_documento').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            $("#NOMBRE_DOCUMENTO").val(row.data().NOMBRE_DOCUMENTO);
                            // $("#signatariodocumentopdf").val('');

                            // actualizar campos HIDDEN
                            $("#equipo_doc_id").val(row.data().ID_EQUIPO_DOCUMENTO)
                            $("#equipo_documento_id").val(row.data().EQUIPO_ID);
                            $("#equipo_documento_proveedor_id").val(proveedor_id);
                            $("#EQUIPO_DOCUMENTO_ELIMINADO").val(0);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_equipo_documento').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedorequipo",
                                data: {
                                    api: 2
                                },
                                resetForm: false,
                                success: function(data) {

                                    // actualiza tabla
                                    tabla_equipos_documentos(data.EQUIPO_ID);

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#signatarioDocumento_Eliminado').val(1);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#signatarioDocumento_Eliminado').val(1);
                    
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


// precionar boton nuevo signatario
$(document).ready(function()
{
    $("#boton_nuevo_signatario").click(function()
    {


        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {
            // Mostrar Foto Mapa instalacion en el INPUT
            if ($('#signatariofoto').data('dropify'))
            {
                $('#signatariofoto').dropify().data('dropify').resetPreview();
                $('#signatariofoto').dropify().data('dropify').clearElement();
                // $('#signatariofoto').dropify().data('dropify').destroy();
                // $('#signatariofoto').dropify().data('dropify').init();
            }
            else
            {
                // $('#signatariofoto').attr('data-height', 400);
                // $('#signatariofoto').attr('data-default-file', imagenUrl);
                $('#signatariofoto').dropify({
                    messages: {
                        'default': 'Arrastre la imagen aquí o haga click',
                        'replace': 'Arrastre la imagen o haga clic para reemplazar',
                        'remove':  'Quitar',
                        'error':   'Ooops, ha ocurrido un error.'
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
            $('#signatariofoto').attr('required', true);

            // Borrar formulario
            $('#form_signatario').each(function(){
                this.reset();
            });

            // CheckBox estado del signatario
            $("#checkbox_estado_signatario").prop('checked', false);
            $("#checkbox_estado_signatario").prop('disabled', 'disabled');

            // actualizar campos HIDDEN
            $("#signatario_id").val(0);
            $("#signatario_proveedor_id").val($("#proveedor_id").val());
            $("#signatario_EstadoActivo").val(1);
            $("#signatario_Eliminado").val(0);

            // Inicializar DATATABLE
            // function_tabla_signatario_documentos(0);
            // tabla_signatario_cursos(0);
            // tabla_signatario_acreditaciones(0);

            // seleccionar TAB1
            $( "#tab1_signatario_doc" ).click();

            // Abrir modal
            $('#modal_signatario').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});

function tabla_signatarios(id)
{
    try 
    {
        var ruta = "/tablaproveedorsignatario/"+id;

        if (TablaSignatarios != null)
        {
            TablaSignatarios.clear().draw();
            TablaSignatarios.ajax.url(ruta).load();
        }
        else
        {
            TablaSignatarios = $('#tabla_signatarios').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_signatarios(id);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "numero_registro",
                        "defaultContent": "-" 
                    },
                    {
                        "data": "signatario_Nombre",
                        "defaultContent": "-"
                    },
                    {
                        "data": "signatario_Cargo",
                        "defaultContent": "-"
                    },
                    {
                        "data": "signatario_Telefono",
                        "defaultContent": "-"
                    },
                    {
                        "data": "EstadoActivo_texto",
                        "defaultContent": "-"
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_signatarios(id);
    }
}


//Guardar y Editar signatario
$("#boton_guardar_signatario").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_signatario').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: "/proveedorsignatario",
            data: {
                api: 1
            },
            resetForm: false,
            success: function(data)
            {
                // Actualizar DATATABLE
                tabla_signatarios(data.proveedor_id);

                // // Llenar DATATABLE signatarios
                // if (TablaSignatarios == null) {
                //     tabla_signatarios(data.proveedor_id);
                // }
                // else{
                //     // TablaSignatarios.destroy();
                //     // tabla_signatarios(data.proveedor_id);
                //     TablaSignatarios.ajax.url("/tablaproveedorsignatario/"+data.proveedor_id).load();
                // }

                // actualizar campos HIDDEN
                $("#signatario_id").val(data.id);
                $("#signatario_proveedor_id").val(data.proveedor_id);
                $("#signatario_EstadoActivo").val(data.signatario_EstadoActivo);
                $("#signatario_Eliminado").val(data.signatario_Eliminado);

                // requerir campo FOTO
                $('#signatariofoto').attr('required', false);

                // CheckBox estado del signatario
                $("#checkbox_estado_signatario").prop('disabled', false);
                $("#checkbox_estado_signatario").prop('checked', true);

                // actualiza boton
                $('#boton_guardar_signatario').html('Guardar <i class="fa fa-save"></i>');

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
                // $('#modal_equipo').modal('hide');
            },
            beforeSend: function() {
                $('#boton_guardar_signatario').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(data) {
                
                // actualiza boton
                $('#boton_guardar_signatario').html('Guardar <i class="fa fa-save"></i>');

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
});


function actualiza_estado_signatario()
{
    var mensaje = "";

    if($('#checkbox_estado_signatario').prop("checked") == true){
        $("#signatario_EstadoActivo").val(1);
        mensaje = "Signatario activo";
    }
    else
    {
        $("#signatario_EstadoActivo").val(0);
        mensaje = "Signatario Inactivo";
    }

    // enviar datos
    $('#form_signatario').ajaxForm({
        dataType: 'json',
        type: 'POST',
        url: "/proveedorsignatario",
        data: {},
        resetForm: false,
        success: function(data) {

            // Llenar DATATABLE signatarios
            if (TablaSignatarios == null) {
                tabla_signatarios(data.proveedor_id);
            }
            else{
                // TablaSignatarios.destroy();
                // tabla_signatarios(data.proveedor_id);
                TablaSignatarios.ajax.url("/tablaproveedorsignatario/"+data.proveedor_id).load();
            }

            // mensaje
            swal({
                title: "Estado modificado",
                text: ""+mensaje,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });
        },
        beforeSend: function() {
            //
        },
        error: function(data) {
            // mensaje
            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });

            return false;
        }
    }).submit();
    return false;
}


// Seleccionar SIGNATARIO
$(document).ready(function()
{
    $('#tabla_signatarios tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarios.row(tr);

        // Borrar formulario
        $('#form_signatario').each(function(){
            this.reset();
        });

        // Llenar campos form
        $("#signatario_Nombre").val(row.data().signatario_Nombre);
        $("#signatario_Cargo").val(row.data().signatario_Cargo);
        $("#signatario_Telefono").val(row.data().signatario_Telefono);
        $("#signatario_Correo").val(row.data().signatario_Correo);
        $("#signatario_Rfc").val(row.data().signatario_Rfc);
        $("#signatario_Curp").val(row.data().signatario_Curp);
        $("#signatario_Nss").val(row.data().signatario_Nss);
        $("#signatario_TipoSangre").val(row.data().signatario_TipoSangre);
        $("#signatario_Alergias").val(row.data().signatario_Alergias);
        $("#signatario_telEmergencia").val(row.data().signatario_telEmergencia);
        $("#signatario_NombreContacto").val(row.data().signatario_NombreContacto);
        $("#signatario_parentesco").val(row.data().signatario_parentesco);



        // actualizar campos HIDDEN
        $("#signatario_id").val(row.data().id);
        $("#signatario_proveedor_id").val(row.data().proveedor_id);
        $("#signatario_EstadoActivo").val(row.data().signatario_EstadoActivo);
        $("#signatario_Eliminado").val(row.data().signatario_Eliminado);

        // obtener extencion archivo
        var archivo = row.data().signatario_Foto;
        var extension = archivo.substring(archivo.lastIndexOf("."));
        // Obtener FOTO
        var imagenUrl = '/versignatariofoto/'+row.data().id+extension;

        // Mostrar Foto en el INPUT
        if ($('#signatariofoto').data('dropify'))
        {
            $('#signatariofoto').dropify().data('dropify').destroy();
            // $('.dropify-wrapper').css('height', 400);
            $('#signatariofoto').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#signatariofoto').dropify().data('dropify').init();
        }
        else
        {
            // $('#signatariofoto').attr('data-height', 400);
            $('#signatariofoto').attr('data-default-file', imagenUrl);
            $('#signatariofoto').dropify({
                messages: {
                    'default': 'Arrastre la imagen aquí o haga click',
                    'replace': 'Arrastre la imagen o haga clic para reemplazar',
                    'remove':  'Quitar',
                    'error':   'Ooops, ha ocurrido un error.'
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
        $('#signatariofoto').attr('required', false);

        // CheckBox estado del signatario
        if (row.data().signatario_EstadoActivo==1)
        {
            $("#checkbox_estado_signatario").prop('checked', true);
        }
        else
        {
            $("#checkbox_estado_signatario").prop('checked', false);
        }

        //radio de si es personal de apoyo
         if (row.data().signatario_apoyo == 1)
        {
            $("#signatario_apoyo_si").prop('checked', true);
            $("#signatario_apoyo_no").prop('checked', false);
        }
        else
        {
            $("#signatario_apoyo_si").prop('checked', false);
            $("#signatario_apoyo_no").prop('checked', true);
        }


        // SI PROVEEDOR BLOQUEADO
        if (parseInt($("#boton_bloquear_proveedor").val()) == 0)
        {
            $("#checkbox_estado_signatario").attr('disabled', false);
        }
        else
        {
            $("#checkbox_estado_signatario").attr('disabled', true);
        }

        // INICIALIZAR DATATABLES
        if (TablaSignatarioDocumentos != null)
        {
            TablaSignatarioDocumentos.clear().draw();
        }

        // ACTUALIZAR DATATABLES
        function_tabla_signatario_documentos(row.data().id);
        tabla_signatario_cursos(row.data().id);
        tabla_signatario_experiencia(row.data().id);
        tabla_signatario_acreditaciones(row.data().id);

        //Consultamos el total de experiencia total
        TotalExperiencia(row.data().id)

        // seleccionar TAB1
        $( "#tab1_signatario_doc" ).click();

        // abrir modal
        $('#modal_signatario').modal({backdrop:false});
    });
});


// Eliminar signatario
$(document).ready(function()
{
    $('#tabla_signatarios tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarios.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            // CONFIRMAR
            swal({   
                title: "¿Eliminar signatario?",   
                text: ""+row.data().signatario_Nombre,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar signatario?",   
                        text: ""+row.data().signatario_Nombre,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_signatario').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            $("#signatario_Nombre").val(row.data().signatario_Nombre);
                            $("#signatario_Cargo").val(row.data().signatario_Cargo);
                            $("#signatario_Telefono").val(row.data().signatario_Telefono);
                            $("#signatario_Correo").val(row.data().signatario_Correo);
                            $("#signatario_Rfc").val(row.data().signatario_Rfc);
                            $("#signatario_Curp").val(row.data().signatario_Curp);
                            $("#signatario_Nss").val(row.data().signatario_Nss);
                            $("#signatario_TipoSangre").val(row.data().signatario_TipoSangre);

                            // actualizar campos HIDDEN
                            $("#signatario_id").val(row.data().id);
                            $("#signatario_proveedor_id").val(row.data().proveedor_id);
                            $("#signatario_EstadoActivo").val(row.data().signatario_EstadoActivo);
                            $("#signatario_Eliminado").val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_signatario').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedorsignatario",
                                data: {},
                                resetForm: false,
                                success: function(data)
                                {
                                    // Actualizar DATATABLE
                                    tabla_signatarios(data.proveedor_id);

                                    // // Llenar DATATABLE signatarios
                                    // if (TablaSignatarios == null) {
                                    //     tabla_signatarios(data.proveedor_id);
                                    // }
                                    // else{
                                    //     // TablaSignatarios.destroy();
                                    //     // tabla_signatarios(data.proveedor_id);
                                    //     TablaSignatarios.ajax.url("/tablaproveedorsignatario/"+data.proveedor_id).load();
                                    // }

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#signatario_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#signatario_Eliminado').val(0);
                    
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


// precionar boton nuevo signatario documento
$(document).ready(function() {
    $("#boton_nuevo_signatario_documento").click(function() {
        // valida si existe un signatario seleccionado
        if ($("#signatario_id").val()>0)
        {
            // Borrar formulario
            $('#form_signatario_documento').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#signatario_doc_id").val(0);
            $("#signatario_documento_id").val($("#signatario_id").val());
            $("#signatario_documento_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioDocumento_Eliminado").val(0);

            // requerir campo DOCUMENTO
            $('#signatariodocumentopdf').attr('required', true);

            // Abrir modal
            $('#modal_signatario_documento').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un signatario",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


function function_tabla_signatario_documentos(id)
{
    try 
    {
        var ruta = "/tablasignatariodocumento/"+id;

        if (TablaSignatarioDocumentos != null)
        {
            TablaSignatarioDocumentos.clear().draw();
            TablaSignatarioDocumentos.ajax.url(ruta).load();
        }
        else
        {
            TablaSignatarioDocumentos = $('#tabla_signatario_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        function_tabla_signatario_documentos(id);
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
                        "data": "signatarioDocumento_Tipo" 
                    },
                    {
                        "data": "signatarioDocumento_Nombre"
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
                "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
                "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        function_tabla_signatario_documentos(id);
    }
}




//Guardar y Editar signatario documento
$("#boton_guardar_signatario_documento").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#signatariodocumentopdf").val() != "")
        {
            // Tipo archivo
            var archivo = $("#signatariodocumentopdf").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_signatario_documento').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/signatariodocumento",
                data: {
                    api: 1
                },
                resetForm: false,
                success: function(data)
                {
                    // actualiza tabla
                    function_tabla_signatario_documentos(data.signatario_id);

                    // actualiza boton
                    $('#boton_guardar_signatario_documento').html('Guardar <i class="fa fa-save"></i>');

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
                    $('#modal_signatario_documento').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_signatario_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_signatario_documento').html('Guardar <i class="fa fa-save"></i>');

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
});


// mostrar documento
$(document).ready(function()
{
    // mostrar PDF
    $('#tabla_signatario_documentos tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioDocumentos.row( tr );

        // vaciar visor
        // $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().signatarioDocumento_Nombre);

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/versignatariodocumentopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/versignatariodocumentopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariodocumentopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariodocumentopdf/'+row.data().id);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/versignatariodocumentopdf/'+row.data().id);
        }
    });
});


// SELECCIONAR SIGNATARIO DOCUMENTO
$(document).ready(function()
{
    $('#tabla_signatario_documentos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioDocumentos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_signatario_documento').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#signatarioDocumento_Nombre").val(row.data().signatarioDocumento_Nombre);
            $("#signatarioDocumento_Tipo").val(row.data().signatarioDocumento_Tipo);
            $("#signatarioDocumento_FechaVigencia").val(row.data().signatarioDocumento_FechaVigencia);
            $("#signatarioDocumentoEleccion").val(row.data().signatarioDocumentoEleccion);


            // $("#signatariodocumentopdf").val('');

            // requerir campo DOCUMENTO
            $('#signatariodocumentopdf').attr('required', false);

            // actualizar campos HIDDEN
            $("#signatario_doc_id").val(row.data().id);
            $("#signatario_documento_id").val(row.data().signatario_id);
            $("#signatario_documento_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioDocumento_Eliminado").val(row.data().signatarioDocumento_Eliminado);

            // abrir modal
            $('#modal_signatario_documento').modal({backdrop:false});
        }
    });
});

//Funcion para el tipo de documento 
$('#signatarioDocumento_Tipo').on('change', function (e) {
    e.preventDefault();

    opcion = $("#signatarioDocumento_Tipo").val();

    if (opcion == 'Personal') {
        
        $("#signatarioDocumentoEleccion").html('<option disabled selected>Seleccione un documento personal...</option><option value="INE">Identificación ofical (INE)</option><option value="CURP">CURP</option><option value="NACIMIENTO">Acta de nacimiento</option><option value="LICENCIA">Licencia para conducir</option><option value="OTRO">Otro</option>');

    } else { //Documentos academicos

          $("#signatarioDocumentoEleccion").html('<option disabled selected>Seleccione un documento académico...</option><option value="CONSTANCIA">Constancia de estudio</option><option value="CEDULA">Cédula profesional</option><option value="CV">Curriculum</option><option value="VALIDACION">Verificación de la Cédula profesional </option><option value="OTRO">Otro</option>');
        
    }
})

//Funcion para el documento seleccionado segun el tipo
$('#signatarioDocumentoEleccion').on('change', function () {
    

    opcion = $("#signatarioDocumentoEleccion").val();
    texto = $("#signatarioDocumentoEleccion option:selected").text()
 
    if(opcion === "OTRO") {
        
        $('#signatarioDocumento_FechaVigencia').val('').prop('disabled', true).prop('required', false)
        $('#signatarioDocumento_Nombre').val('').prop('required', true).prop('disabled', false).prop('readonly', false)
      

    }else if (opcion === 'INE' || opcion === 'LICENCIA') {

        $('#signatarioDocumento_FechaVigencia').val('').prop('required', true).prop('readonly', false).prop('disabled', false)
        $('#signatarioDocumento_Nombre').val(texto).prop('readonly', true)
        
    } else {

        $('#signatarioDocumento_FechaVigencia').val('').prop('disabled', true).prop('required', false)
        $('#signatarioDocumento_Nombre').val(texto).prop('readonly', true)        
    }
})

// Eliminar signatario DOCUMENTO
$(document).ready(function()
{
    $('#tabla_signatario_documentos tbody').on('click', 'td.Eliminar', function() {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarioDocumentos.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar documento?",   
                text: ""+row.data().signatarioDocumento_Nombre,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar documento?",   
                        text: ""+row.data().signatarioDocumento_Nombre,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_signatario_documento').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            $("#signatarioDocumento_Nombre").val(row.data().signatarioDocumento_Nombre);
                            // $("#signatariodocumentopdf").val('');

                            // actualizar campos HIDDEN
                            $("#signatario_doc_id").val(row.data().id);
                            $("#signatario_documento_id").val(row.data().signatario_id);
                            $("#signatario_documento_proveedor_id").val($("#proveedor_id").val());
                            $("#signatarioDocumento_Eliminado").val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_signatario_documento').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/signatariodocumento",
                                data: {
                                    api:1
                                },
                                resetForm: false,
                                success: function(data) {

                                    // actualiza tabla
                                    function_tabla_signatario_documentos(data.signatario_id);

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#signatarioDocumento_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#signatarioDocumento_Eliminado').val(0);
                    
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

// precionar boton nuevo signatario documento
$(document).ready(function() {
    $("#boton_nuevo_experiencia").click(function() {
        // valida si existe un signatario seleccionado
        if ($("#signatario_id").val()>0)
        {
            // Borrar formulario
            $('#form_signatario_experiencia').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#ID_EXPERIENCIA").val(0);
            $("#EXPERIENCIA_SIGNATARIO_ID").val($("#signatario_id").val());
            $("#EXPERIENCIA_PROVEEDOR_ID").val($("#proveedor_id").val());
            $("#ELIMINADO").val(0);

            // requerir campo DOCUMENTO
            $('#EXPERIENCIA_PDF').attr('required', true);

            // Abrir modal
            $('#modal_signatario_experiencia').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un signatario",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


//Guardar y Editar signatario curso
$("#boton_guardar_signatario_experiencia").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#EXPERIENCIA_PDF").val() != "")
        {
            // Tipo archivo
            var archivo = $("#EXPERIENCIA_PDF").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_signatario_experiencia').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/signatariodocumento",
                data: {
                    api:2
                },
                resetForm: false,
                success: function (data) {
                    
                    // actualiza tabla
                    tabla_signatario_experiencia(data.SIGNATARIO_ID);

                    //Actualizamos el total de experiencia
                    TotalExperiencia(data.SIGNATARIO_ID)

                    // actualiza boton
                    $('#boton_guardar_signatario_experiencia').html('Guardar <i class="fa fa-save"></i>');

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
                    $('#modal_signatario_experiencia').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_signatario_experiencia').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_signatario_experiencia').html('Guardar <i class="fa fa-save"></i>');

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
});

//Tabla de informacion de experiencia
function tabla_signatario_experiencia(id)
{
    if (TablaSignatarioExperiencia != null)
    {
        TablaSignatarioExperiencia.clear().draw();
        TablaSignatarioExperiencia.ajax.url("/tablasignatarioexperiencia/"+ id +'/' + 0).load();
    }
    else
    {
        TablaSignatarioExperiencia = $('#tabla_signatario_experiencia').DataTable({
            "ajax": {
                "url": "/tablasignatarioexperiencia/"+ id + '/'+ 0,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    tabla_signatario_experiencia(id);
                    //Actualizamos el total de experiencia
                    TotalExperiencia(id)
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ' - ' 
                },
                {
                    "data": "NOMBRE_EMPRESA",
                    "defaultContent": ' - '
                },
                {
                    "data": "CARGO",
                    "defaultContent": ' - '
                },
                {
                    "data": "TIEMPO",
                    "defaultContent": ' - '
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
            "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
            // "rowsGroup": [1], //agrupar filas
            "order": [[ 0, "desc" ]],
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
            },
        });
    }
}

//Funcion para mostrar el total de experiencia
function TotalExperiencia(id) {
    
     $.ajax({
        type: "GET",
        dataType: "json",
        url: "/tablasignatarioexperiencia/"+ id + '/'+ 1,
        data:{},
        cache: false,
        success:function(data)
        {
          
            $('#periodoExperiencia').html(data.data)
        },
        error: function(dato){
            // alert('Error: '+dato.msj);
            return false;
        }
    })
}

// mostrar documento de la experiencia
$(document).ready(function() {
    // mostrar PDF
    $('#tabla_signatario_experiencia tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioExperiencia.row( tr );

        // vaciar visor
        $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().NOMBRE_EMPRESA);

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariocursopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariodocumentoexperienciapdf/'+row.data().ID_EXPERIENCIA);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/versignatariodocumentoexperienciapdf/'+row.data().ID_EXPERIENCIA);
        }
    });
});

// Eliminar signatario experiencia
$(document).ready(function()
{
    $('#tabla_signatario_experiencia tbody').on('click', 'td.Eliminar', function() {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarioExperiencia.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar registro?",   
                text: 'Esta acción no se puede revertir',
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                      
                    $('#ID_EXPERIENCIA').val(row.data().ID_EXPERIENCIA);

                    // cerrar msj confirmacion
                    swal.close();

                   
                    // enviar form
                    $('#form_signatario_experiencia').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/signatariodocumento",
                        data: {
                            api: 2,
                            ELIMINADO: 1
                        },
                        resetForm: false,
                        success: function(data) {

                            // actualiza tabla
                            tabla_signatario_experiencia(data.SIGNATARIO_ID);

                            

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
                        beforeSend: function() {
                            //espera
                        },
                        error: function(data) {
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
                else 
                {     
                    // modificar valor del campo
                    $('#ELIMIADO').val(0);
                    $('#ID_EXPERIENCIA').val(0);

                    
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


// SELECCIONAR SIGNATARIO DOCUMENTO
$(document).ready(function()
{
    $('#tabla_signatario_experiencia tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioExperiencia.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_signatario_experiencia').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#NOMBRE_EMPRESA").val(row.data().NOMBRE_EMPRESA);
            $("#CARGO").val(row.data().CARGO);
            $("#FECHA_INICIO").val(row.data().FECHA_INICIO);
            $("#FECHA_FIN").val(row.data().FECHA_FIN);
            

            // requerir campo DOCUMENTO
            $('#EXPERIENCIA_PDF').attr('required', false);

            // actualizar campos HIDDEN
            $("#ID_EXPERIENCIA").val(row.data().ID_EXPERIENCIA);
            $("#EXPERIENCIA_SIGNATARIO_ID").val(row.data().SIGNATARIO_ID);
            $("#EXPERIENCIA_PROVEEDOR_ID").val($("#proveedor_id").val());
            $("#ELIMINADO").val(row.data().ELIMINADO);

            // abrir modal
            $('#modal_signatario_experiencia').modal({backdrop:false});
        }
    });
});


//======================================================================

// precionar boton nuevo signatario curso
$(document).ready(function() {
    $("#boton_nuevo_curso").click(function() {
        // valida si existe un signatario seleccionado
        if ($("#signatario_id").val()>0)
        {
            // Borrar formulario
            $('#form_signatario_curso').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#curso_id").val(0);
            $("#curso_signatario_id").val($("#signatario_id").val());
            $("#curso_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioCurso_Eliminado").val(0);

            // requerir campo DOCUMENTO
            $('#signatariocursopdf').attr('required', true);

            // Abrir modal
            $('#modal_signatario_curso').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un signatario",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


function tabla_signatario_cursos(id)
{
    if (TablaSignatarioCursos != null)
    {
        TablaSignatarioCursos.clear().draw();
        TablaSignatarioCursos.ajax.url("/tablasignatariocurso/"+id).load();
    }
    else
    {
        TablaSignatarioCursos = $('#tabla_signatario_cursos').DataTable({
            "ajax": {
                "url": "/tablasignatariocurso/"+id,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    tabla_signatario_cursos(id);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ' - ' 
                },
                {
                    "data": "curso",
                    "defaultContent": ' - '
                },
                {
                    "data": "expedicion",
                    "defaultContent": ' - '
                },
                {
                    "data": "Vigencia_texto",
                    "defaultContent": ' - '
                },
                {
                    "className": 'Pdf',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>'
                },
                {
                    "className": 'Validación',
                    "orderable": false,
                    "data": 'boton_validacion',
                    "defaultContent": '-'
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
            "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
            // "rowsGroup": [1], //agrupar filas
            "order": [[ 0, "desc" ]],
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


//Guardar y Editar signatario curso
$("#boton_guardar_signatario_curso").click(function (e) {
    e.preventDefault();
    var guardar = 0;

  
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#signatariocursopdf").val() != "")
        {
            // Tipo archivo
            var archivo = $("#signatariocursopdf").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_signatario_curso').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/signatariocurso",
                data: {
                    api: 1
                },
                resetForm: false,
                success: function(data) {
                    // actualiza tabla
                    tabla_signatario_cursos(data.signatario_id);

                    // actualiza boton
                    $('#boton_guardar_signatario_curso').html('Guardar <i class="fa fa-save"></i>');

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
                    $('#modal_signatario_curso').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_signatario_curso').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_signatario_curso').html('Guardar <i class="fa fa-save"></i>');

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

   
});


// mostrar documento CURSO
$(document).ready(function() {
    // mostrar PDF
    $('#tabla_signatario_cursos tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursos.row( tr );

        // vaciar visor
        $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().signatarioCurso_NombreCurso);

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariocursopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariocursopdf/'+row.data().id);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/versignatariocursopdf/'+row.data().id);
        }
    });
});


// Seleccionar y editar SIGNATARIO CURSO
$(document).ready(function()
{
    $('#tabla_signatario_cursos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_signatario_curso').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#signatarioCurso_NombreCurso").val(row.data().signatarioCurso_NombreCurso);
            $("#signatarioCurso_FechaExpedicion").val(row.data().signatarioCurso_FechaExpedicion);
            $("#signatarioCurso_FechaVigencia").val(row.data().signatarioCurso_FechaVigencia);
            $("#signatarioCurso_FolioCurso").val(row.data().signatarioCurso_FolioCurso);
            // $("#signatariocursopdf").val('');

            // actualizar campos HIDDEN
            $("#curso_id").val(row.data().id);
            $("#curso_signatario_id").val(row.data().signatario_id);
            $("#curso_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioCurso_Eliminado").val(row.data().signatarioCurso_Eliminado);

            // requerir campo DOCUMENTO
            $('#signatariocursopdf').attr('required', false);

            // Abrir modal
            $('#modal_signatario_curso').modal({backdrop:false});
        }
    });
});

// Agregar documentos de validacion
$(document).ready(function()
{
    $('#tabla_signatario_cursos tbody').on('click', 'td.Validación', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_cursos_documentos').each(function(){
                this.reset();
            });

        
            // actualizar campos HIDDEN
            $("#VALIDACION_CURSO_ID").val(row.data().id);
            $("#VALIDACION_SIGNATARIO_ID").val(row.data().signatario_id);
            $("#VALIDACION_PROVEEDOR_ID").val($("#proveedor_id").val());

            $('#nombre_curso_validacion').text('Documentos de validación del curso: ' + row.data().signatarioCurso_NombreCurso)

            // requerir campo DOCUMENTO
            $('#PDF_VALIDACION').attr('required', true);
            tabla_signatario_cursos_validacion(row.data().id, row.data().signatario_id)
            signatario_id_validacion = row.data().signatario_id

            // Abrir modal
            $('#modal_cursos_documentos').modal({backdrop:false});
        }
    });
});


//Guardar el documento de validacion del curso
$("#boton_guardar_documento_validacion_curso").click(function (e) {
    e.preventDefault();
    var guardar = 0;

  
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#PDF_VALIDACION").val() != "")
        {
            // Tipo archivo
            var archivo = $("#PDF_VALIDACION").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_cursos_documentos').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/signatariocurso",
                data: {
                    api: 2,
                    ELIMINADO: 0
                },
                resetForm: false,
                success: function(data) {
                    // actualiza tabla

                    tabla_signatario_cursos_validacion(data.CURSO_ID, signatario_id_validacion);

                    // actualiza boton
                    $('#boton_guardar_documento_validacion_curso').html('Guardar <i class="fa fa-save"></i>');

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

                    // Limpiar campos
                    $('#form_cursos_documentos').each(function(){
                        this.reset();
                    });

                },
                beforeSend: function() {
                    $('#boton_guardar_documento_validacion_curso').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_documento_validacion_curso').html('Guardar <i class="fa fa-save"></i>');

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

   
});

function tabla_signatario_cursos_validacion(id, signatario_id)
{
    if (TablaSignatarioCursosValidacion != null)
    {
        TablaSignatarioCursosValidacion.clear().draw();
        TablaSignatarioCursosValidacion.ajax.url("/tablasignatariocursovalidacion/"+ id +'/'+ signatario_id).load();
    }
    else
    {
        TablaSignatarioCursosValidacion = $('#tabla_cursosdocumentos_validacion').DataTable({
            "ajax": {
                "url": "/tablasignatariocursovalidacion/"+ id +'/'+ signatario_id,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    tabla_signatario_cursos_validacion(id, signatario_id);
                },
                "data": {}
            },
            complete: function () {
                TablaSignatarioCursosValidacion.columns.adjust().draw()

            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ' - ' 
                },
                {
                    "data": "NOMBRE_DOCUMENTO",
                    "defaultContent": ' - '
                },
              
                {
                    "className": 'Pdf',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>'
                },
                {
                    "className": 'Eliminar',
                    "orderable": false,
                    "data": 'boton_eliminar',
                    "defaultContent": '-'
                }
            ],
            "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
            // "rowsGroup": [1], //agrupar filas
            "order": [[ 0, "desc" ]],
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
// Eliminar signatario CURSO
$(document).ready(function()
{
    $('#tabla_cursosdocumentos_validacion tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursosValidacion.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar documento de validacion?",   
                text: ""+row.data().NOMBRE_DOCUMENTO,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    
                    // Borrar formulario
                    $('#form_cursos_documentos').each(function(){
                        this.reset();
                    });


                    // actualizar campos HIDDEN
                    $("#ID_DOCUMENTO_CURSO").val(row.data().ID_DOCUMENTO_CURSO);
                    $("#VALIDACION_ELIMINADO").val(1);

                    // cerrar msj confirmacion
                    swal.close();

                    // enviar form
                    $('#form_cursos_documentos').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/signatariocurso",
                        data: {
                            api: 2,
                            ELIMINADO: 1
                        },
                        resetForm: false,
                        success: function(data) {

                            console.log(data);
                            // actualiza tabla
                            tabla_signatario_cursos_validacion(data.CURSO_ID, signatario_id_validacion);

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
                        beforeSend: function() {
                            //espera
                        },
                        error: function(data) {
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
                else 
                {     
                    // modificar valor del campo
                    $('#VALIDACION_ELIMINADO').val(0);
                    
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

// mostrar documento de validacion del curso
$(document).ready(function() {
    // mostrar PDF
    $('#tabla_cursosdocumentos_validacion tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursosValidacion.row( tr );

        // vaciar visor
        $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().NOMBRE_DOCUMENTO);

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/versignatariocursopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariocursopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/versignatariocursopdfvalidacion/'+row.data().ID_DOCUMENTO_CURSO
);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/versignatariocursopdfvalidacion/'+row.data().ID_DOCUMENTO_CURSO
);
        }
    });
});




// Eliminar signatario CURSO
$(document).ready(function()
{
    $('#tabla_signatario_cursos tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarioCursos.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar curso?",   
                text: ""+row.data().signatarioCurso_NombreCurso,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Eliminar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirmar eliminar curso?",   
                        text: ""+row.data().signatarioCurso_NombreCurso,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_signatario_curso').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            $("#signatarioCurso_NombreCurso").val(row.data().signatarioCurso_NombreCurso);
                            $("#signatarioCurso_FechaExpedicion").val(row.data().signatarioCurso_FechaExpedicion);
                            $("#signatarioCurso_FechaVigencia").val(row.data().signatarioCurso_FechaVigencia);
                            // $("#signatariocursopdf").val('');

                            // actualizar campos HIDDEN
                            $("#curso_id").val(row.data().id);
                            $("#curso_signatario_id").val(row.data().signatario_id);
                            $("#curso_proveedor_id").val($("#proveedor_id").val());
                            $("#signatarioCurso_Eliminado").val(1);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_signatario_curso').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/signatariocurso",
                                data: {
                                    api: 1
                                },
                                resetForm: false,
                                success: function(data) {

                                    // actualiza tabla
                                    tabla_signatario_cursos(data.signatario_id);

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#signatarioCurso_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#signatarioCurso_Eliminado').val(0);
                    
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


// precionar boton nuevo signatario ACREDITACION
$(document).ready(function() {
    $("#boton_nueva_pruebasignatario").click(function() {
        // valida si existe un signatario seleccionado
        if ($("#signatario_id").val()>0)
        {
            // Borrar formulario
            $('#form_signatario_acreditacion').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#signatarioacreditacion_id").val(0);
            $("#acreditacion_signatario_id").val($("#signatario_id").val());
            $("#signatarioacreditacion_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioAcreditacion_Eliminado").val(0);

            // llenar campo (Factor de riesgo / Servicio)
            alcanceservicios_lista('signatarioAcreditacion_Alcance', $('#proveedor_id').val(), 0);

            // Abrir modal
            $('#modal_signatario_acreditacion').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un signatario",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});


function tabla_signatario_acreditaciones(id)
{
    if (TablaSignatarioAcreditaciones != null)
    {
        TablaSignatarioAcreditaciones.clear().draw();
        TablaSignatarioAcreditaciones.ajax.url("/tablasignatarioacreditacion/"+id).load();
    }
    else
    {
        TablaSignatarioAcreditaciones = $('#tabla_signatario_acreditaciones').DataTable({
            "ajax": {
                "url": "/tablasignatarioacreditacion/"+id,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    tabla_signatario_acreditaciones(id);
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
                    "data": "acreditacion",
                    "defaultContent": "-"
                },
                {
                    "data": "agente",
                    "defaultContent": "-"
                },
                {
                    "data": "estado",
                    "defaultContent": "-"
                },
                {
                    "data": "Vigencia_texto", //signatarioAcreditacion_Vigencia
                    "defaultContent": "N/A"
                },
                {
                    "data": "activo",
                    "defaultContent": "-"
                },
                // {
                //     "className": 'Pdf',
                //     "orderable": false,
                //     "data": null,
                //     "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>'
                // },
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
            "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
            // "rowsGroup": [1], //agrupar filas
            "order": [[ 0, "desc" ]],
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


function valida_signatarioestado(signatario_estado)
{
    var opcion = parseInt(signatario_estado);

    if (opcion == 0) //Opcion [N/A]
    {
        $('#signatarioAcreditacion_Expedicion').val('');
        $('#signatarioAcreditacion_Expedicion').attr('required', false);
        $('#signatarioAcreditacion_Expedicion').attr('disabled', true);

        $('#signatarioAcreditacion_Vigencia').val('');
        $('#signatarioAcreditacion_Vigencia').attr('required', false);
        $('#signatarioAcreditacion_Vigencia').attr('disabled', true);
    }
    else
    {
        $('#signatarioAcreditacion_Expedicion').val('');
        $('#signatarioAcreditacion_Expedicion').attr('disabled', false);
        $('#signatarioAcreditacion_Expedicion').attr('required', true);
        
        $('#signatarioAcreditacion_Vigencia').val('');
        $('#signatarioAcreditacion_Vigencia').attr('disabled', false);
        $('#signatarioAcreditacion_Vigencia').attr('required', true);
    }
}


// Seleccionar y editar SIGNATARIO ACREDITACION
$(document).ready(function()
{
    $('#tabla_signatario_acreditaciones tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaSignatarioAcreditaciones.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_signatario_acreditacion').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#signatarioacreditacion_id").val(row.data().id);
            $("#acreditacion_signatario_id").val(row.data().signatario_id);
            $("#signatarioacreditacion_proveedor_id").val($("#proveedor_id").val());
            $("#signatarioAcreditacion_Eliminado").val(row.data().signatarioAcreditacion_Eliminado);

            // Valida si [N/A]
            valida_signatarioestado(row.data().cat_signatarioestado_id);

            // Llenar campos form
            $("#cat_signatarioestado_id").val(row.data().cat_signatarioestado_id);
            $("#signatarioAcreditacion_Expedicion").val(row.data().signatarioAcreditacion_Expedicion);
            $("#signatarioAcreditacion_Vigencia").val(row.data().signatarioAcreditacion_Vigencia);
            $("#cat_signatariodisponibilidad_id").val(row.data().cat_signatariodisponibilidad_id);

            // llenar campo (Factor de riesgo / Servicio)
            alcanceservicios_lista('signatarioAcreditacion_Alcance', $('#proveedor_id').val(), row.data().signatarioAcreditacion_Alcance);

            // Abrir modal
            $('#modal_signatario_acreditacion').modal({backdrop:false});
        }
    });
});


// Eliminar signatario ACREDITACION
$(document).ready(function()
{
    $('#tabla_signatario_acreditaciones tbody').on('click', 'td.Eliminar', function()
    {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaSignatarioAcreditaciones.row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar alcance del signatario?",   
                text: '['+row.data().alcance.prueba.catPrueba_Tipo+'] '+row.data().alcance.acreditacionAlcance_agente,
                type: "warning",
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar alcance del signatario?",   
                        text: '['+row.data().alcance.prueba.catPrueba_Tipo+'] '+row.data().alcance.acreditacionAlcance_agente,
                        type: "warning",
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // cerrar msj confirmacion
                            swal.close();

                            // Enviar datos
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/signatarioacreditacioneliminar/"+row.data().id,
                                data:{},
                                cache: false,
                                success:function(dato)
                                {
                                    // Actualiza DataTable
                                    tabla_signatario_acreditaciones(row.data().signatario_id);

                                    // mensaje
                                    swal({
                                        title: "Eliminado",
                                        text: ""+dato.msj,
                                        type: "success", // warning, error, success, info
                                        buttons: {
                                            visible: false, // true , false
                                        },
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                },
                                error: function(dato)
                                {
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
                                    return false;
                                }
                            });//Fin ajax
                        }
                        else 
                        {     
                            // modificar valor del campo
                            $('#signatarioAcreditacion_Eliminado').val(0);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#signatarioAcreditacion_Eliminado').val(0);
                    
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


//Guardar y Editar signatario acreditaciones
$("#boton_guardar_signatario_acreditacion").click(function() {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_signatario_acreditacion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: "/signatarioacreditacion",
            data: {},
            resetForm: false,
            success: function(data) {
                // Llenar DATATABLE acreditaciones signatario
                if (TablaSignatarioAcreditaciones == null) {
                    tabla_signatario_acreditaciones(data.signatario_id);
                }
                else{
                    // TablaSignatarioAcreditaciones.destroy();
                    // tabla_signatario_acreditaciones(data.signatario_id);
                    TablaSignatarioAcreditaciones.ajax.url("/tablasignatarioacreditacion/"+data.signatario_id).load();
                }

                // actualiza boton
                $('#boton_guardar_signatario_acreditacion').html('Guardar <i class="fa fa-save"></i>');

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
                $('#modal_signatario_acreditacion').modal('hide');
            },
            beforeSend: function() {
                $('#boton_guardar_signatario_acreditacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(data) {
                
                // actualiza boton
                $('#boton_guardar_signatario_acreditacion').html('Guardar <i class="fa fa-save"></i>');

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
});


//======================================================================


function tabla_precios(id)
{
    try 
    {
        var ruta = "/tablaproveedorservicio/"+id;

        if (TablaPrecios != null)
        {
            TablaPrecios.clear().draw();
            TablaPrecios.ajax.url(ruta).load();
        }
        else
        {
            TablaPrecios = $('#tabla_precios').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_precios(id);
                    },
                    "data": {}
                },
                createdRow: function (row, data, dataIndex) {
                    if (data.ACTIVO_COTIZACIONPROVEEDOR == 0) {
                        $(row).css({
                         
                            // "font-style" : "italic",
                            // "color": "##e9ecef",
                            "background-color": "#e9ecef",
                            "cursor": "not-allowed",
                            "opacity": "0.9"
                            

                        });
                    
                    } 
                },
                "columns": [
                    // {
                    //     "data": "id"
                    // },
                    {
                        "data": "numero_registro",
                        "defaultContent": "-"
                    },
                    {
                        "data": "cotizacion",
                        "defaultContent": "-"
                    },
                    {
                        "data": "fecha",
                        "defaultContent": "-"
                    },
                    {
                        "data": "Vigencia_texto", //servicio_VigenciaCotizacion
                        "defaultContent": "-"
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
                        
                        "orderable": false,
                        "data": 'boton_desactivar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_precios(id);
    }
}


function servicios_consultalistaalcances(proveedor_id)
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/serviciolistaalcances/"+proveedor_id,
        data:{},
        cache: false,
        success:function(dato)
        {
            //Opciones alcances
            servicios_alcances_lista = dato.opciones;

            /*
                // Inicializar campos Select search
                // $('#select_alcances').selectize();

                // // Reinicializar input search
                // if ($('#select_alcances')[0].selectize) //Si esta inicializado
                // {
                //     $('#select_alcances')[0].selectize.destroy();
                // }

                // // Recorrer arreglo de input search y Reinicializar
                // $('.select_search').each(function(){
                //     if (this[0].selectize) //Si esta inicializado
                //     {
                //         this[0].selectize.destroy();
                //     }
                // })
            */
        },
        error: function(dato){
            servicios_alcances_lista = '<option value="">Error al consultar alcances</option>';
            return false;
        }
    });//Fin ajax
}


function servicios_consultaagentes(cotizacion_id)
{
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/serviciopartidasprecios/"+cotizacion_id,
        data:{},
        cache: false,
        success:function(dato){
            cotizacion_partida_total = parseInt(dato.total_partidas);
            $("#tabla_listapartidas tbody").html(dato.filas_partidas);
        },
        beforeSend: function() {
            $('#tabla_listapartidas tbody').html('<tr><td colspan="4"><i class="fa fa-spin fa-spinner fa-3x"></i></td></tr>');
        },
        error: function(dato){
            $("#tabla_listapartidas tbody").html('<tr><td colspan="4">Error al consultar partidas</td></tr>');
            return false;
        }
    });//Fin ajax
}


$("#boton_nuevapartida_alcance").click(function()
{
    // $("#tabla_listapartidas tbody").append('<tr>'+
    //                                             '<td style="width: 70px; padding-top: 20px;">0</td>'+
    //                                             '<td style="width: 480px;">'+
    //                                                 '<div class="form-group">'+
    //                                                     '<select class="custom-select form-control select_search" id="select_alcances" name="partida_alcance[]" required>'+
    //                                                         servicios_alcances_lista+
    //                                                     '</select>'+
    //                                                 '</div>'+
    //                                             '</td>'+
    //                                             '<td style="width: 180px;"><input type="number" step="any" class="form-control" style="margin: 0px 0px 0px 0px !important;" value="" name="precio_alcance[]" required></td>'+
    //                                             '<td style="width: 70px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
    //                                         '</tr>');

    // Incrementar contador
    cotizacion_partida_total += 1;

    // Obtenemos el numero de filas de la tabla [Descontamos 1 porque empieza de 0]
    var rows = (parseInt(document.getElementById("tabla_listapartidas").rows.length) - 1);
    $('#tabla_listapartidas > tbody > tr').eq(rows-1).before('<tr>'+
                                                                '<td style="width: 70px;">'+cotizacion_partida_total+'</td>'+
                                                                '<td style="width: 480px;">'+
                                                                    '<div class="form-group">'+
                                                                        '<select class="custom-select form-control select_search" id="partida_select_alcance_'+cotizacion_partida_total+'" name="partida_alcance[]" required>'+
                                                                            servicios_alcances_lista+
                                                                        '</select>'+
                                                                    '</div>'+
                                                                '</td>'+'<th></th>'+
                                                                '<td style="width: 180px;"><input type="number" step="any" class="form-control" style="margin: 0px 0px 0px 0px !important;" value="" name="precio_alcance[]" required></td>'+'<td><input type="hidden" name="ACTIVO_PARTIDAPROVEEDOR_ALCANCE[]" value="1" class="d-none"></input></td>'+
                                                                '<td style="width: 70px;" ><button type="button" class="btn btn-secundary btn-circle"><i class="fa fa-ban"></i></button></td>'+
                                                                '<td style="width: 70px;" class="eliminar">'+
                                                                    '<button type="button" class="btn btn-danger btn-circle boton_eliminar">'+
                                                                        '<i class="fa fa-trash"></i>'+
                                                                    '</button>'+
                                                                '</td>'+
                                                            '</tr>');
    
    // Inicializa campo tipo select search
    $('#partida_select_alcance_'+cotizacion_partida_total).selectize();
    
    // Desplazar a la primera fila de la tabla
    $('#tabla_listapartidas > tbody').scrollTop(0);
    
    // Desplazar a la última fila de la tabla
    $('#tabla_listapartidas tbody').animate({
        scrollTop: $('#tabla_listapartidas > tbody > tr:last').position().top //última fila
    }, 1000);
    

});

$("#boton_nuevapartida_adicional").click(function()
{
    // $("#tabla_listapartidas tbody").append(  '<tr>'+
    //                                             '<td style="width: 70px;">0</td>'+
    //                                             '<td style="width: 480px;">'+
    //                                                 '<input type="text" class="form-control" value="" name="partida_adicional[]" required>'+
    //                                             '</td>'+
    //                                             '<td style="width: 180px;"><input type="number" step="any" class="form-control" value="" name="precio_adicional[]" required></td>'+
    //                                             '<td style="width: 70px;" class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
    //                                         '</tr>');

    // Incrementar contador
    cotizacion_partida_total += 1;

    // Obtenemos el numero de filas de la tabla [Descontamos 1 porque empieza de 0]
    var rows = (parseInt(document.getElementById("tabla_listapartidas").rows.length) - 1);
    $('#tabla_listapartidas > tbody > tr').eq(rows-1).before('<tr>'+
                                                                '<td style="width: 70px;">'+cotizacion_partida_total+'</td>'+
                                                                '<td style="width: 480px;">'+
                                                                    '<input type="text" style="border: 1px #0060ff solid;" class="form-control" value="" name="partida_adicional[]" required>'+
                                                                '</td>'+ '<th></th>' +
                                                                '<td style="width: 180px;"><input type="number" step="any" class="form-control" value="" name="precio_adicional[]" required></td>'+
                                                                '<td><input type="hidden" name="ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[]" value="1" class="d-none"></input></td>'+
                                                                '<td style="width: 70px;"><button type="button" class="btn btn-secundary btn-circle"><i class="fa fa-ban"></i></button></td>'+
                                                                '<td style="width: 70px;" class="eliminar">'+
                                                                    '<button type="button" class="btn btn-danger btn-circle boton_eliminar">'+
                                                                        '<i class="fa fa-trash"></i>'+
                                                                    '</button>'+
                                                                '</td>'+
                                                            '</tr>');
    
    // Desplazar a la primera fila de la tabla
    $('#tabla_listapartidas > tbody').scrollTop(0);
    
    // Desplazar a la última fila de la tabla
    $('#tabla_listapartidas tbody').animate({
        scrollTop: $('#tabla_listapartidas > tbody > tr:last').position().top //última fila
    }, 1000);
    
});


// Eliminar fila partida
$('#tabla_listapartidas tbody').on('click', '.boton_eliminar', function() {
    // obtener la fila de la tabla
    var fila = $(this).closest('tr');
    
    // confirmar
    swal({   
        title: "¿Eliminar partida?",   
        text: "Eliminar partida de la lista",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Eliminar!",   
        cancelButtonText: "Cancelar!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm) {   
        if (isConfirm) {
            fila.remove(); // eliminar fila TR

            // mensaje
            swal({
                title: "Correcto",
                text: "Partida eliminada de la lista",
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1000,
                showConfirmButton: false
            });
        } else {
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
});

// Nueva cotizacion
$(document).ready(function()
{
    $("#boton_nuevo_servicio").click(function() {
        // valida si existe un signatario seleccionado
        if ($("#proveedor_id").val()>0)
        {
            // Borrar formulario
            $('#form_servicio').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#servicio_id").val(0);
            $("#servicio_proveedor_id").val($("#proveedor_id").val());
            $("#servicio_Eliminado").val(0);

            // requerir campo DOCUMENTO
            $('#servicio_SoportePDF').attr('required', true);

            // Consulta lista de alcances
            cotizacion_partida_total = 0;
            servicios_consultalistaalcances($("#proveedor_id").val());

            // Vaciar filas tabla
            $("#tabla_listapartidas tbody").html('<tr><td colspan="4" style="width: 800px; height: 160px;">&nbsp;</td></tr>');

            // Abrir modal
            $('#modal_servicio').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});


// Seleccionar y editar Cotizacion
$(document).ready(function()
{
    $('#tabla_precios tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaPrecios.row( tr );

        // Borrar formulario
        $('#form_servicio').each(function(){
            this.reset();
        });

        // Llenar campos form
        $("#servicio_numerocotizacion").val(row.data().servicio_numerocotizacion);
        $("#servicio_FechaCotizacion").val(row.data().servicio_FechaCotizacion);
        $("#servicio_VigenciaCotizacion").val(row.data().servicio_VigenciaCotizacion);
        $("#servicio_Observaciones").val(row.data().servicio_Observaciones);
        // $("#servicio_SoportePDF").val('');
        
        // actualizar campos HIDDEN
        $("#servicio_id").val(row.data().id);
        $("#servicio_proveedor_id").val(row.data().proveedor_id);
        $("#servicio_Eliminado").val(row.data().servicio_Eliminado);

        // requerir campo DOCUMENTO
        $('#servicio_SoportePDF').attr('required', false);

        // Consulta lista de alcances
        servicios_consultalistaalcances(row.data().proveedor_id);

        // mostrar lista de partidas
        servicios_consultaagentes(row.data().id);

        // Abrir modal
        $('#modal_servicio').modal({backdrop:false});
    });
});


// Eliminar Cotizacion


$(document).ready(function()
{
    $('#tabla_precios tbody').on('click', 'td>button.boton_eliminar', function ()
    {
        
        var tr = $(this).closest('tr');
        var row = TablaPrecios.row(tr);

        if (parseInt(row.data().accion_activa) > 0) {
            // confirmar
            swal({
                title: "¿Eliminar cotización?",
                text: "No. " + row.data().servicio_numerocotizacion,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    // confirmar nuevamente
                    swal({
                        title: "Confirme nuevamente ¿Eliminar cotización?",
                        text: "No. " + row.data().servicio_numerocotizacion,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Eliminar!",
                        cancelButtonText: "Cancelar!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            // Limpiar formulario
                            $('#form_servicio').each(function() {
                                this.reset();
                            });

                            // Limpiar tabla lista partidas
                            $("#tabla_listapartidas tbody").html('<tr><td colspan="4">sin datos</td></tr>');

                            // Llenar campos del formulario
                            $("#servicio_numerocotizacion").val(row.data().servicio_numerocotizacion);
                            $("#servicio_FechaCotizacion").val(row.data().servicio_FechaCotizacion);
                            $("#servicio_VigenciaCotizacion").val(row.data().servicio_VigenciaCotizacion);
                            $("#servicio_Observaciones").val(row.data().servicio_Observaciones);
                            // $("#servicio_SoportePDF").val('');
                            
                            // Actualizar campos ocultos
                            $("#servicio_id").val(row.data().id);
                            $("#servicio_proveedor_id").val(row.data().proveedor_id);
                            $("#servicio_Eliminado").val(1);

                            // Cerrar mensaje de confirmación
                            swal.close();

                            // Enviar formulario
                            $('#form_servicio').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedorservicio",
                                data: {},
                                resetForm: false,
                                success: function(dato) {
                                    // Actualizar DATATABLE
                                    tabla_precios(dato.servicio.proveedor_id);

                                    // Mensaje
                                    swal({
                                        title: "Eliminado",
                                        text: "" + dato.msj,
                                        type: "success",
                                        buttons: {
                                            visible: false
                                        },
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                },
                                error: function(dato) {
                                    // Mensaje de error
                                    swal({
                                        title: "Error",
                                        text: "" + dato.msj,
                                        type: "error",
                                        buttons: {
                                            visible: false
                                        },
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            }).submit();
                            return false;
                        } else {
                            // Modificar valor del campo
                            $('#servicio_Eliminado').val(0);
                            
                            // Mensaje
                            swal({
                                title: "Cancelado",
                                text: "Acción cancelada",
                                type: "error",
                                buttons: {
                                    visible: false
                                },
                                timer: 500,
                                showConfirmButton: false
                            });
                        }
                    });
                } else {
                    // Modificar valor del campo
                    $('#servicio_Eliminado').val(0);
                    
                    // Mensaje
                    swal({
                        title: "Cancelado",
                        text: "Acción cancelada",
                        type: "error",
                        buttons: {
                            visible: false
                        },
                        timer: 500,
                        showConfirmButton: false
                    });
                }
            });
        } else {
            swal({
                    title: "No tienes permitido realizar esta acción",
                    text: "Sin permisos",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    
                   
                })
        }
    });
});


// mostrar documento Cotizacion
$(document).ready(function()
{
    $('#tabla_precios tbody').on('click', 'td.Pdf', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaPrecios.row( tr );

        // TITUTLO DEL VISOR
        $('#nombre_documento_visor').html("Cotización No: "+row.data().servicio_numerocotizacion);

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // // Opciones Mostrar PDF
        // $('#visor_documento').attr('src', '/verserviciopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/ViewerJS/index.html/#/verserviciopdf/'+row.data().id);
        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verserviciopdf/'+row.data().id);

        // Mostrar PDF
        if (parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verserviciopdf/'+row.data().id);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verserviciopdf/'+row.data().id);
        }
    });
});


//Guardar y Editar Cotizacion
$("#boton_guardar_servicio").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#servicio_SoportePDF").val() != "")
        {
            // Tipo archivo
            var archivo = $("#servicio_SoportePDF").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // confirmar
            swal({   
                title: "¿Guardar cotización?",   
                text: "Confirme que desea guardar esta información",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Guardar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_servicio').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/proveedorservicio",
                        data: {},
                        resetForm: false,
                        success: function(dato)
                        {
                            // Actualizar DATATABLE
                            tabla_precios(dato.servicio.proveedor_id);

                            // // Llenar DATATABLE acreditaciones signatario
                            // if (TablaPrecios == null) {
                            //     tabla_precios(dato.servicio.proveedor_id);
                            // }
                            // else{
                            //     // TablaPrecios.destroy();
                            //     // tabla_precios(dato.servicio.proveedor_id);
                            //     TablaPrecios.ajax.url("/tablaproveedorservicio/"+dato.servicio.proveedor_id).load();
                            // }

                            // actualiza boton
                            $('#boton_guardar_servicio').html('Guardar <i class="fa fa-save"></i>');

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

                            // cerrar modal
                            $('#modal_servicio').modal('hide');
                        },
                        beforeSend: function() {
                            $('#boton_guardar_servicio').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function(dato) {
                            
                            // actualiza boton
                            $('#boton_guardar_servicio').html('Guardar <i class="fa fa-save"></i>');

                            // mensaje
                            swal({
                                title: "Error",
                                text: ""+dato.msj,
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
                        title: "Cancelado",
                        text: "Acción cancelada",
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
    }
});

// AGREGAR CONTACTO PROVEERDOR 
document.addEventListener("DOMContentLoaded", function() {
    const botonAgregar = document.getElementById('botonagregarContacto');
    botonAgregar.addEventListener('click', agregarContacto);

    function agregarContacto() {
        const divContacto = document.createElement('div');
        divContacto.classList.add('row', 'generarlistadecontacto','m-3');
        divContacto.innerHTML = `
       
            <div class="col-lg-12 col-sm-1">
                    <div class="form-group">
                    <h2><i class="fa fa-user-plus"></i> Agregar contacto</h2>                    
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <label>Área contacto: *</label>
                        <input type="text" class="form-control" name="PROVEEDOR_AREA_CONTACTO" required>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <label>Nombre contacto: *</label>
                        <input type="text" class="form-control" name="PROVEEDOR_NOMBRE_CONTACTO" required>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="form-group">
                        <label>Correo contacto: *</label>
                        <input type="email" class="form-control" name="PROVEEDOR_CORREO_CONTACTO" id="correo_contacto" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <label> Cargo contacto: *</label>
                        <input type="text" class="form-control" name="PROVEEDOR_CARGO_CONTACTO" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <label> Celular contacto: *</label>
                        <input type="number" class="form-control"  name="PROVEEDOR_CELULAR_CONTACTO" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <label> Teléfono contacto: *</label>
                        <input type="number" class="form-control"  name="PROVEEDOR_TELEFONO_CONTACTO" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <label> Extensión : </label>
                        <input type="number" class="form-control"  name="PROVEEDOR_EXTENSION_CONTACO">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="ACTIVO_PROVEEDOR" name="ACTIVO_PROVEEDOR" value="1">
                    </div>
                </div>

                <div class="col">
                    <div class="form-group" style="text-align: center;">
                        <button type="button" class="btn btn-danger botonEliminarContacto">Eliminar contacto <i class="fa fa-trash"></i></button>
                    </div>
                </div>
            
        `;
        const contenedor = document.querySelector('.listadecontactoProveedor');
        contenedor.appendChild(divContacto);

        const botonEliminar = divContacto.querySelector('.botonEliminarContacto');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divContacto);
        });
    }
});

//AGREGAR CONTACTO SUCURSAL

document.addEventListener("DOMContentLoaded", function() {
    const botonAgregar = document.getElementById('botonagregarContactosucursal');
    botonAgregar.addEventListener('click', agregarContactosucursal);

    function agregarContactosucursal() {
        const divContactosucursal = document.createElement('div');
        divContactosucursal.classList.add('row', 'generarsucursales','m-2');
        divContactosucursal.innerHTML = `
        <div class="col-lg-12 col-sm-1">
        <div class="form-group">
            <h2><i class="fa fa-user-plus"></i> Agregar contacto</h2>                 
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label>Área contacto: *</label>
            <input type="text" class="form-control" name="PROVEEDORSUCURSAL_AREA" required>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label> Nombre Contacto *</label>
            <input type="text" class="form-control" name="PROVEEDORSUCURSAL_NOMBRE" required>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label> Correo Contacto *</label>
            <input type="email" class="form-control" name="PROVEEDORSUCURSAL_CORREO" id="correo_contacto" required>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label> Cargo Contacto *</label>
            <input type="text" class="form-control" name="PROVEEDORSUCURSAL_CARGO" required>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label> Celular Contacto *</label>
            <input type="number" class="form-control" name="PROVEEDORSUCURSAL_CONTACTO" required>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label> Teléfono *</label>
            <input type="number" class="form-control" name="PROVEEDORSUCURSAL_TELEFONO" required>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label> Extensión </label>
            <input type="number" class="form-control" name="PROVEEDORSUCURSAL_EXTENSION">
        </div>
    </div>
     <div class="col-lg-3 col-sm-6">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="ACTIVO_SUCURSAL" name="ACTIVO_SUCURSAL" value="1">
                    </div>
                </div>
    <div class="col-12">
        <div class="form-group" style="text-align: center;">
            <button type="button" class="btn btn-danger botonEliminarContactoSucursal">Eliminar contacto <i class="fa fa-trash"></i></button>
        </div>
    </div>
    
        `;
        const contenedor = document.querySelector('.listadecontactoProveedorSucursal');
        contenedor.appendChild(divContactosucursal);

        const botonEliminar = divContactosucursal.querySelector('.botonEliminarContactoSucursal');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divContactosucursal);
        });
    }
});


$('#TIPO_DOCUMENTO').on('change', function (e) {
    e.preventDefault();

    opcion = $("#TIPO_DOCUMENTO option:selected").text()

    if (opcion == 'Otro') {

        $('#proveedorDocumento_Nombre').prop('required', true);
        $('#proveedorDocumento_Nombre').prop('readonly', false);
        $('#proveedorDocumento_Nombre').val('');



    } else {

        $('#proveedorDocumento_Nombre').prop('readonly', true);
        $('#proveedorDocumento_Nombre').val(opcion);
        
    }
})
//======================================================================

//DESACTIVAR cotización

$('#tabla_precios tbody').on('click', 'td>button.boton_desactivar', function()
{
    var tr = $(this).closest('tr');
    var row = TablaPrecios.row(tr);

  

   
    swal({   
        title: "¿Está seguro de desactivar la cotización?",   
        text: "No se podrá usar esta cotización",
        type: "info",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Desactivar!",   
        cancelButtonText: "Cancelar!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm)
        {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/cotizacionproveedorBloqueo/"+row.data().id+'/'+row.data().ACTIVO_COTIZACIONPROVEEDOR,
                data:{},
                cache: false,
                success:function(dato)
                {
                    TablaPrecios.ajax.reload()

                    // mensaje
                    swal({
                        title: "Cotización desactivada correctamente",
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else 
        {
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


//Activar la cotizacion 


$('#tabla_precios tbody').on('click', 'td>button.boton_activar', function()
{
    var tr = $(this).closest('tr');
    var row = TablaPrecios.row(tr);
 
    swal({   
        title: "¿Está seguro de activar la cotización?",   
        text: "Se podrá usar esta cotización",
        type: "info",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Activar!",   
        cancelButtonText: "Cancelar!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm)
        {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/cotizacionproveedorBloqueo/"+row.data().id+'/'+row.data().ACTIVO_COTIZACIONPROVEEDOR,
                data:{},
                cache: false,
                success:function(dato)
                {
                    TablaPrecios.ajax.reload()

                    // mensaje
                    swal({
                        title: "Cotización Activada correctamente",
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        }
        else 
        {
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


// $('#tabla_listapartidas tbody').on('click', 'td>button.boton_desactivar', function() {
//     var tr = $(this).closest('tr');

//     swal({
//         title: "¿Está seguro de desactivar la partida?",
//         text: "No se podrá usar esta partida",
//         type: "info",
//         showCancelButton: true,
//         confirmButtonColor: "#DD6B55",
//         confirmButtonText: "Desactivar!",
//         cancelButtonText: "Cancelar!",
//         closeOnConfirm: false,
//         closeOnCancel: false
//     }, function(isConfirm){
//         if (isConfirm)
//         {
//             // cerrar msj confirmacion
//             swal.close();

//             // Obtener los datos de la fila
//             var partidaId = tr.data('partida-id');
//             var activo = tr.data('activo');

//             // enviar solicitud AJAX para desactivar la partida
//             $.ajax({
//                 type: "GET",
//                 dataType: "json",
//                 url: "/partidaproveedorBloqueo/" + partidaId + "/" + activo,
//                 data: {},
//                 cache: false,
//                 success:function(dato) {
//                     // Actualizar la tabla o realizar cualquier otra acción necesaria
//                     // mensaje
//                     swal({
//                         title: "Partida desactivada correctamente",
//                         text: ""+dato.msj,
//                         type: "success", // warning, error, success, info
//                         buttons: {
//                             visible: false, // true , false
//                         },
//                         timer: 1500,
//                         showConfirmButton: false
//                     });
//                 },
//                 error: function(dato) {
//                     // alert('Error: '+dato.msj);
//                     return false;
//                 }
//             }); // Fin ajax
//         }
//         else
//         {
//             // mensaje
//             swal({
//                 title: "Cancelado",
//                 text: "Acción cancelada",
//                 type: "error", // warning, error, success, info
//                 buttons: {
//                     visible: false, // true , false
//                 },
//                 timer: 500,
//                 showConfirmButton: false
//             });
//         }
//     });
// });



//DESACTIVAR partida
$('#tabla_listapartidas tbody').on('click', 'td>button.boton_desactivar', function () {
        var boton = $(this)
        var fila = $(this).closest('tr');
       
    
        swal({   
            title: "Desactivar partida?",   
            text: "Desactivar partida de la lista",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Desactivar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm) {   

            if (isConfirm) {
                fila.css({
                    'text-decoration': 'line-through',
                    'background-color': '#ccc'
                });
                fila.find('input').prop('readonly', true);

                
                alcance = fila.find("input[name='partida_alcance[]']")
                if (alcance.length == 1) {
                    
                     fila.find("input[name='ACTIVO_PARTIDAPROVEEDOR_ALCANCE[]']").val(0);
                    

                } else {

                     fila.find("input[name='ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[]']").val(0);

                }   

               
                boton.removeClass('boton_desactivar');
                boton.addClass('boton_activar');
                 boton.find('i').removeClass('fa-lock').addClass('fa-unlock');
                    
                
                // mensaje
                swal({
                    title: "Correcto",
                    text: "Partida Desactivar",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1000,
                    showConfirmButton: false
                });
            } else {
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
        return false; // Evita que se realice cualquier acción adicional después de cambiar el valor del input
    });


//ACTIVAR PARTIDA
$('#tabla_listapartidas tbody').on('click', 'td>button.boton_activar', function () {
        var boton = $(this)
        var fila = $(this).closest('tr');
    
        swal({   
            title: "Activar partida?",   
            text: "Activar partida de la lista",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Activar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm) {   
            if (isConfirm) {
                fila.css({
                    'text-decoration': 'none',
                    'background-color': '#ffff'
                });
                           
               
                alcance = fila.find("input[name='partida_alcance[]']")
                if (alcance.length == 1) {
                    
                     fila.find("input[name='ACTIVO_PARTIDAPROVEEDOR_ALCANCE[]']").val(1);
                    

                } else {

                     fila.find("input[name='ACTIVO_PARTIDAPROVEEDOR_ADICIONAL[]']").val(1);

                }   
               
                boton.removeClass('boton_activar');
                boton.addClass('boton_desactivar');
                boton.find('i').removeClass('fa-unlock').addClass('fa-lock');
                fila.find("input[name='precio_alcance[]']").prop('readonly', false);
                
                    
                
                // mensaje
                swal({
                    title: "Correcto",
                    text: "Partida Activada",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1000,
                    showConfirmButton: false
                });
            } else {
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
        return false; // Evita que se realice cualquier acción adicional después de cambiar el valor del input
    });


    document.addEventListener('DOMContentLoaded', function() {
        const tipoDocumentoSelect = document.getElementById('DOCUMENTO_TIPO');
        const nombreDocumentoInput = document.getElementById('NOMBRE_DOCUMENTO');
        
        // Escuchar el evento de cambio en el select Tipo de documento
        tipoDocumentoSelect.addEventListener('change', function() {
            const opcionSeleccionada = this.value;
            
            // Obtener el texto de la opción seleccionada
            const textoOpcionSeleccionada = this.options[this.selectedIndex].text;
            
            // Si se selecciona la opción 'Otro', desbloquear el input y limpiar su valor
            if (opcionSeleccionada === '6') {
                nombreDocumentoInput.removeAttribute('readonly');
                nombreDocumentoInput.value = '';
            } else {
                // Si se selecciona otra opción, bloquear el input y establecer su valor como el texto de la opción seleccionada
                nombreDocumentoInput.setAttribute('readonly', true);
                nombreDocumentoInput.value = textoOpcionSeleccionada;
            }
        });
    });



    function requierecalibracion() {
        var checkbox = $('#requiere_calibracion1');
        var hiddenInput = $('#requiere_calibracion');
        var fields = $('.calibracion-requrida');
    
        if (checkbox.is(':checked')) {
            hiddenInput.val('Si');
            fields.show();
        } else {
            hiddenInput.val('No');
            fields.hide();
        }
    }
    
    $(document).ready(function() {
        // Inicializar los campos según el estado del checkbox al cargar la página
        requierecalibracion();
    });
    


    function actualiza_estado_equipo()
{
    var mensaje = "";

    if($('#checkbox_estado_equipo').prop("checked") == true){
        $("#equipo_EstadoActivo").val(1);
        mensaje = "Equipo activo";
    }
    else
    {
        $("#equipo_EstadoActivo").val(0);
        mensaje = "Equipo Inactivo";
    }


    // enviar datos
    $('#form_equipo').ajaxForm({
        dataType: 'json',
        type: 'POST',
        url: "/proveedorequipo",
         data: {
            api:1
        },
        resetForm: false,
        success: function(data) {

            // Llenar DATATABLE 
            if (TablaEquipos == null) {
                tabla_equipos(data.proveedor_id);
            }
            else{
                // TablaSignatarios.destroy();
                // tabla_signatarios(data.proveedor_id);
                TablaEquipos.ajax.url("/tablaproveedorequipo/"+data.proveedor_id).load();
            }

            // mensaje
            swal({
                title: "Estado modificado",
                text: ""+mensaje,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });
        },
        beforeSend: function() {
            //
        },
        error: function(data) {
            // mensaje
            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });

            return false;
        }
    }).submit();
    return false;
}

/// FUNCIONES PARA LOS DOCUMENTOS DE EXCEL CARGADOS PARA LOS EQUIPOS
$(document).ready(function () {

    $('#boton_cargarExcelEquipos').on('click', function (e) {
        e.preventDefault();

        $('#divCargaEquipos').css('display', 'none');
        $('#alertaVerificacion').css('display', 'none');

        $('#formExcelEquipos')[0].reset();

        $('#modal_excel_equipo').modal({backdrop:false});

    })

    $("#botonCargarExcelEquipos").click(function() {
        var guardar = 0;

        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida){
            if ($("#excelEquipos").val() != ""){
                // Tipo archivo
                var archivo = $("#excelEquipos").val();
                var extension = archivo.substring(archivo.lastIndexOf("."));

                // valida tipo de archivo
                if(extension == ".xlsx" || extension == ".XLSX"){
                    guardar = 1;
                }
                else{
                    // mensaje
                    swal({
                        title: "Tipo de archivo incorrecto "+extension,
                        text: "Solo se pueden cargar archivos tipo .xlsx",
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
            else{
                guardar = 0;
            }

            // guardar
            if (guardar == 1){
            
                swal({   
                    title: "¿Está  seguro de cargar este documento?",   
                    text: "Está acción  no se puede revertir",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Guardar!",   
                    cancelButtonText: "Cancelar!",   
                    closeOnConfirm: false,   
                    closeOnCancel: false 
                }, function (isConfirm) {   
                    
                    if (isConfirm){
                        // cerrar msj confirmacion
                        swal.close();

                        // enviar datos
                        $('#formExcelEquipos').ajaxForm({
                            dataType: 'json',
                            type: 'POST',
                            url: "/proveedorequipo",
                            data: {
                                api: 3,
                                proveedor_id: $('#proveedor_id').val()
                            },
                            success: function (dato) {
                                
                             

                                // actualiza boton
                                $('#botonCargarExcelEquipos').prop('disabled', false);
                                $('#divCargaEquipos').css('display', 'none');
                                
                                if (dato.code == 200) {

                                    //Recargamos la tabla
                                    tabla_equipos($('#proveedor_id').val())
                                    
                                    // cerrar modal
                                    $('#modal_excel_equipo').modal('hide');
    
                                    // mensaje
                                    swal({
                                        title: "Los equipos fueron cargados exitosamente",
                                        text: ""+dato.msj,
                                        type: "success", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });
                                
                                } else {

                                     swal({
                                        title: "Ocurrio un error al intentar insertar los equipos.",
                                        text: ""+dato.msj,
                                        type: "error", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });
                                }

                                
                            },
                            beforeSend: function () {

                                $('#botonCargarExcelEquipos').prop('disabled', true);
                                $('#divCargaEquipos').css('display', 'block');
                            },
                            error: function(dato) {
                                
                                // actualiza boton
                                $('#botonCargarExcelEquipos').prop('disabled', false);
                                $('#divCargaEquipos').css('display', 'none');

                                // mensaje
                                swal({
                                    title: "Error al cargar los equipos",
                                    text: ""+dato.msj,
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
                            title: "Cancelado",
                            text: "Acción cancelada",
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
        }
    });



    $('#excelEquipos').change(function() {
        if ($(this).val()) {
            
            $('#alertaVerificacion').css('display', 'block');

        } else {
            $('#alertaVerificacion').css('display', 'none');
            
        }
    });

});


/// FUNCIONES PARA LOS DOCUMENTOS DE EXCEL CARGADOS PARA LOS PERSONALES
$(document).ready(function () {

    $('#boton_cargarExcelPersonal').on('click', function (e) {
        e.preventDefault();

        $('#divCargaPersonal').css('display', 'none');
        $('#alertaVerificacion2').css('display', 'none');

        $('#formExcelPersonal')[0].reset();

        $('#modal_excel_personal').modal({backdrop:false});

    })

    $("#botonCargarExcelPersonal").click(function() {
        var guardar = 0;

        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida){
            if ($("#excelPersonal").val() != ""){
                // Tipo archivo
                var archivo = $("#excelPersonal").val();
                var extension = archivo.substring(archivo.lastIndexOf("."));

                // valida tipo de archivo
                if(extension == ".xlsx" || extension == ".XLSX"){
                    guardar = 1;
                }
                else{
                    // mensaje
                    swal({
                        title: "Tipo de archivo incorrecto "+extension,
                        text: "Solo se pueden cargar archivos tipo .xlsx",
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
            else{
                guardar = 0;
            }

            // guardar
            if (guardar == 1){
            
                swal({   
                    title: "¿Está  seguro de cargar este documento?",   
                    text: "Está acción  no se puede revertir",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Guardar!",   
                    cancelButtonText: "Cancelar!",   
                    closeOnConfirm: false,   
                    closeOnCancel: false 
                }, function (isConfirm) {   
                    
                    if (isConfirm){
                        // cerrar msj confirmacion
                        swal.close();

                        // enviar datos
                        $('#formExcelPersonal').ajaxForm({
                            dataType: 'json',
                            type: 'POST',
                            url: "/proveedorsignatario",
                            data: {
                                api: 2,
                                proveedor_id: $('#proveedor_id').val()
                            },
                            contentType: false,
                            processData: false,
                            success: function (dato) {

                                // actualizar boton
                                $('#botonCargarExcelPersonal').prop('disabled', false);
                                $('#divCargaPersonal').css('display', 'none');
                                
                                if (dato.code == 200) {
                                    
                                    // cerrar modal
                                    $('#modal_excel_personal').modal('hide');
    
                                    // mensaje
                                    swal({
                                        title: "Los personales fueron cargados exitosamente",
                                        text: ""+dato.msj,
                                        type: "success", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });

                                    //Recargamos la tabla
                                     tabla_signatarios($('#proveedor_id').val())
                                
                                } else {

                                     swal({
                                        title: "Ocurrio un error al intentar insertar los personales.",
                                        text: ""+dato.msj,
                                        type: "error", // warning, error, success, info
                                        buttons: {
                                            visible: true, // true , false
                                        },
                                        showConfirmButton: true,
                                        showCancelButton: false
                                    });
                                }

                                
                            },
                            beforeSend: function () {

                                $('#botonCargarExcelPersonal').prop('disabled', true);
                                $('#divCargaPersonal').css('display', 'block');
                            },
                            error: function(dato) {
                                
                                // actualiza boton
                                $('#botonCargarExcelPersonal').prop('disabled', false);
                                $('#divCargaPersonal').css('display', 'none');

                                // mensaje
                                swal({
                                    title: "Error al cargar los personales.",
                                    text: ""+dato.msj,
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
                            title: "Cancelado",
                            text: "Acción cancelada",
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
        }
    });



    $('#excelPersonal').change(function () {
        
        if ($(this).val()) {
            
            $('#alertaVerificacion2').css('display', 'block');

        } else {
            $('#alertaVerificacion2').css('display', 'none');
            
        }
    });

});

/////////////////// VEHICULOS //////////////////////////

$(document).ready(function()
{
    $("#boton_nuevo_vehiculo").click(function()
    {   
        vehiculo_id = 0
        // valida si existe un proveedor seleccionado
        if ($("#proveedor_id").val()>0)
        {

            // Mostrar Foto Mapa instalacion en el INPUT
            if ($('#foto_vehiculo').data('dropify'))
            {
                $('#foto_vehiculo').dropify().data('dropify').resetPreview();
                $('#foto_vehiculo').dropify().data('dropify').clearElement();
                // $('#signatariofoto').dropify().data('dropify').destroy();
                // $('#signatariofoto').dropify().data('dropify').init();
            }
            else
            {
                // $('#signatariofoto').attr('data-height', 400);
                // $('#signatariofoto').attr('data-default-file', imagenUrl);
                $('#foto_vehiculo').dropify({
                    messages: {
                        'default': 'Arrastre la imagen aquí o haga click',
                        'replace': 'Arrastre la imagen o haga clic para reemplazar',
                        'remove':  'Quitar',
                        'error':   'Ooops, ha ocurrido un error.'
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
            $('#foto_vehiculo').attr('required', false);

            // Borrar formulario
             $('#form_vehiculo').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#vehiculo_id").val(0);
            $("#vehiculo_proveedor_id").val($("#proveedor_id").val());
            $("#vehiculo_Eliminado").val(0);

         

            $("#checkbox_estado_vehiculo").prop('checked', false);
            $("#checkbox_estado_vehiculo").prop('disabled', 'disabled');


            // seleccionar TAB1
            $( "#tab8_equipo_info" ).click();

         
           // Abrir modal
            $('#modal_vehiculo').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un proveedor",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
});






function tabla_vehiculos(id)
{
    try 
    {
        var ruta = "/tablaproveedorvehiculo/"+id;

        if (TablaVehiculos != null)
        {
            TablaVehiculos.clear().draw();
            TablaVehiculos.ajax.url(ruta).load();
        }
        else
        {
            TablaVehiculos = $('#tabla_vehiculos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_vehiculos(id);
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
                        "data": "marca",
                        "defaultContent": "-"
                    },
                    {
                        "data": "modelo",
                        "defaultContent": "-"
                    },
                    {
                        "data": "serie",
                        "defaultContent": "-"
                    },
                    {
                        "data": "placa",
                        "defaultContent": "-"
                    },
                   
                    // {
                    //     "data": 'certificado_pdf',
                    //     "defaultContent": "-",
                    //     // "className": 'Pdf',
                    //     "orderable": false,
                    //     // "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>',
                    // },
                    // {
                    //     "data": 'carta_pdf',
                    //     "defaultContent": "-",
                    //     // "className": 'carta',
                    //     "orderable": false,
                    //     // "defaultContent": '<button type="button" class="btn btn-info btn-circle"><i class="fa fa-file-pdf-o"></i></button>',
                    // },
                    {
                        "data": "EstadoActivo_texto",
                        "defaultContent": "-"
                    },
                    {
                        "className": 'Editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                    }
                ],
                "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_vehiculos(id);
    }
}

// Seleccionar y editar vehículos
$(document).ready(function()
{
    $('#tabla_vehiculos tbody').on('click', 'td.Editar', function ()
    {   
        var tr = $(this).closest('tr');
        var row = TablaVehiculos.row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
             // Borrar formulario
            $('#form_vehiculo').each(function(){
                this.reset();
            });

            vehiculo_id = row.data().id

            // Llenar campos form
            $("#vehiculo_marca").val(row.data().vehiculo_marca);
            $("#vehiculo_linea").val(row.data().vehiculo_linea);
            $("#vehiculo_modelo").val(row.data().vehiculo_modelo);
            $("#vehiculo_serie").val(row.data().vehiculo_serie);
            $("#vehiculo_placa").val(row.data().vehiculo_placa);
        

            //Mostrar la foto del equipo
            // obtener extencion archivo

            $('#foto_vehiculo').attr('required', false);
            
            if (row.data().vehiculo_imagen) {

                var archivo = row.data().vehiculo_imagen;
                var extension = archivo.substring(archivo.lastIndexOf("."));
                // Obtener FOTO
                var imagenUrl = '/vervehiculofoto/' + row.data().id + extension;

                // Mostrar Foto en el INPUT
                if ($('#foto_vehiculo').data('dropify')) {
                    $('#foto_vehiculo').dropify().data('dropify').destroy();
                    // $('.dropify-wrapper').css('height', 400);
                    $('#foto_vehiculo').dropify().data('dropify').settings.defaultFile = imagenUrl;
                    $('#foto_vehiculo').dropify().data('dropify').init();
                }
                else {
                    // $('#signatariofoto').attr('data-height', 400);
                    $('#foto_vehiculo').attr('data-default-file', imagenUrl);
                    $('#foto_vehiculo').dropify({
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
                $('#foto_vehiculo').dropify().data('dropify').resetPreview();
                $('#foto_vehiculo').dropify().data('dropify').clearElement();
            }

            if (row.data().vehiculo_EstadoActivo==1)
            {
                $("#checkbox_estado_vehiculo").prop('checked', true);
            }
            else
            {
                $("#checkbox_estado_vehiculo").prop('checked', false);
            }
        
        
        
        
        
        
          if (parseInt($("#boton_bloquear_proveedor").val()) == 0)
         {
            $("#checkbox_estado_vehiculo").attr('disabled', false);
        }
        else
        {
            $("#checkbox_estado_vehiculo").attr('disabled', true);
        }

            // actualizar campos HIDDEN
            $("#vehiculo_id").val(row.data().id);
            $("#vehiculo_proveedor_id").val(row.data().proveedor_id);
            $("#vehiculo_Eliminado").val(row.data().vehiculo_Eliminado);

         
            
            //lLAMOS NUESTRA TABLA DE DOCUMENTOS
            tabla_vehiculos_documentos(row.data().id)

             // seleccionar TAB1
            $( "#tab8_equipo_info" ).click();

            // abrir modal
            $('#modal_vehiculo').modal({backdrop:false});
        }
    });
});



//Guardar y Editar vehículos
$("#boton_guardar_vehiculo").click(function () {
    
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        swal({   
            title: "¿Desea guardar este vehículo?",   
            text: "Registre un nuevo vehículo, para hacer uso de él.",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Guardar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm)
            {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_vehiculo').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: "/proveedorvehiculo",
                    data: {
                        api : 1
                    },
                    resetForm: false,
                    success: function(data)
                    {
                        // Actualizar DATATABLE
                        tabla_vehiculos(data.proveedor_id);

                        // // Llenar DATATABLE alcances
                        // if (TablaEquipos == null) {
                        //     tabla_equipos(data.proveedor_id);
                        // }
                        // else{
                        //     // TablaEquipos.destroy();
                        //     // tabla_equipos(data.proveedor_id);
                        //     TablaEquipos.ajax.url("/tablaproveedorequipo/"+data.proveedor_id).load();
                        // }

                        // actualiza boton
                        $('#boton_guardar_vehiculo').html('Guardar <i class="fa fa-save"></i>');

                        $("#checkbox_estado_vehiculo").prop('disabled', false);
                        $("#checkbox_estado_vehiculo").prop('checked', true);

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
                        $('#modal_vehiculo').modal('hide');
                    },
                    beforeSend: function() {
                        $('#boton_guardar_vehiculo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(data) {
                        
                        // actualiza boton
                        $('#boton_guardar_vehiculo').html('Guardar <i class="fa fa-save"></i>');

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
            else 
            {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
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
});


function actualiza_estado_vehiculo()
{
    var mensaje = "";

    if($('#checkbox_estado_vehiculo').prop("checked") == true){
        $("#vehiculo_EstadoActivo").val(1);
        mensaje = "vehículo activo";
    }
    else
    {
        $("#vehiculo_EstadoActivo").val(0);
        mensaje = "vehículo Inactivo";
    }


    // enviar datos
    $('#form_vehiculo').ajaxForm({
        dataType: 'json',
        type: 'POST',
        url: "/proveedorvehiculo",
         data: {
            api:1
        },
        resetForm: false,
        success: function(data) {

            // Llenar DATATABLE 
            if (TablaVehiculos == null) {
                tabla_vehiculos(data.proveedor_id);
            }
            else{
                // TablaSignatarios.destroy();
                // tabla_signatarios(data.proveedor_id);
                TablaVehiculos.ajax.url("/tablaproveedorvehiculo/"+data.proveedor_id).load();
            }

            // mensaje
            swal({
                title: "Estado modificado",
                text: ""+mensaje,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });
        },
        beforeSend: function() {
            //
        },
        error: function(data) {
            // mensaje
            swal({
                title: "Error",
                text: "Error al guardar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });

            return false;
        }
    }).submit();
    return false;

}
// Documentos vehiculos

$(document).ready(function() {
    $("#boton_nuevo_vehiculo_documento").click(function() {
        
        if (vehiculo_id != 0)
        {
            // Borrar formulario
            $('#form_vehiculo_documento').each(function(){
                this.reset();
            });

            // actualizar campos HIDDEN
            $("#vehiculo_doc_id").val(0);
            $("#vehiculo_documento_id").val($("#vehiculo_id").val());
            $("#vehiculo_documento_proveedor_id").val($("#proveedor_id").val());
            $("#VEHICULO_DOCUMENTO_ELIMINADO").val(1);

            // requerir campo DOCUMENTO
            $('#VEHICULO_PDF').attr('required', true);

            // Abrir modal
            $('#modal_vehiculo_documento').modal({backdrop:false});
        }
        else
        {
            // mensaje
            swal({
                title: "No disponible",
                text: "Aun no has guardado o seleccionado un vehiculo",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});






function tabla_vehiculos_documentos(id)
{
    try 
    {
        var ruta = "/tablavehiculodocumento/"+id;

        if (TablaVehiculosDocumentos  != null)
        {
            TablaVehiculosDocumentos .clear().draw();
            TablaVehiculosDocumentos .ajax.url(ruta).load();
        }
        else
        {
            TablaVehiculosDocumentos  = $('#tabla_vehiculos_documentos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_vehiculos_documentos(id);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "numero_registro" 
                    },
                    {
                        "data": "NOMBRE_DOCUMENTO_VEHICULOS"
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
                "lengthMenu": [[5, 10, 20, 50, -1], [5, 10, 20, 50, "Todos"]],
                // "rowsGroup": [1], //agrupar filas
                "order": [[ 0, "desc" ]],
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
    catch (exception)
    {
        tabla_vehiculos_documentos(id);
    }
}



 $('#tabla_vehiculos_documentos tbody').on('click', 'td.Pdf', function () {
        var tr = $(this).closest('tr');
        var row = TablaVehiculosDocumentos .row( tr );

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().NOMBRE_DOCUMENTO_VEHICULOS);

        // Mostrar PDF
        if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/vervehiculodocumentopdf/'+row.data().ID_VEHICULO_DOCUMENTO);
        }
        else
        {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/vervehiculodocumentopdf/'+row.data().ID_VEHICULO_DOCUMENTO);
        }
 });



    

$(document).ready(function()
{
    $('#tabla_vehiculos_documentos tbody').on('click', 'td.Editar', function ()
    {
        var tr = $(this).closest('tr');
        var row = TablaVehiculosDocumentos .row( tr );

        if (parseInt(row.data().accion_activa) > 0)
        {
            // Borrar formulario
            $('#form_vehiculo_documento').each(function(){
                this.reset();
            });

            // Llenar campos form
            $("#NOMBRE_DOCUMENTO_VEHICULOS").val(row.data().NOMBRE_DOCUMENTO_VEHICULOS);
            $("#DOCUMENTO_TIPO_VEHICULOS").val(row.data().DOCUMENTO_TIPO_VEHICULOS);

           

            // requerir campo DOCUMENTO
            $('#VEHICULO_PDF').attr('required', false);

            // actualizar campos HIDDEN
            $("#vehiculo_doc_id").val(row.data().ID_VEHICULO_DOCUMENTO);
            $("#vehiculo_documento_id").val(row.data().VEHICULO_ID);
          
            $("#VEHICULO_DOCUMENTO_ELIMINADO").val(1);

            // abrir modal
            $('#modal_vehiculo_documento').modal({backdrop:false});
        }
    });
});



$(document).ready(function()
{
    $('#tabla_vehiculos_documentos tbody').on('click', 'td.Eliminar', function() {
        // Leer fila
        var tr = $(this).closest('tr');
        var row = TablaVehiculosDocumentos .row(tr);

        if (parseInt(row.data().accion_activa) > 0)
        {
            swal({   
                title: "¿Eliminar documento?",   
                text: ""+row.data().NOMBRE_DOCUMENTO_VEHICULOS,
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Aceptar!",   
                cancelButtonText: "Cancelar!",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm)
                {
                    swal({   
                        title: "¿Confirme eliminar documento?",   
                        text: ""+row.data().NOMBRE_DOCUMENTO_VEHICULOS,
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Eliminar!",   
                        cancelButtonText: "Cancelar!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm)
                        {
                            // Borrar formulario
                            $('#form_vehiculo_documento').each(function(){
                                this.reset();
                            });

                            // Llenar campos form
                            $("#NOMBRE_DOCUMENTO_VEHICULOS").val(row.data().NOMBRE_DOCUMENTO_VEHICULOS);
                            // $("#signatariodocumentopdf").val('');

                            // actualizar campos HIDDEN
                            $("#vehiculo_doc_id").val(row.data().ID_VEHICULO_DOCUMENTO)
                            $("#vehiculo_documento_id").val(row.data().VEHICULO_ID);
                            $("#vehiculo_documento_proveedor_id").val(proveedor_id);
                            $("#VEHICULO_DOCUMENTO_ELIMINADO").val(0);

                            // cerrar msj confirmacion
                            swal.close();

                            // enviar form
                            $('#form_vehiculo_documento').ajaxForm({
                                dataType: 'json',
                                type: 'POST',
                                url: "/proveedorvehiculo",
                                data: {
                                    api: 2
                                },
                                resetForm: false,
                                success: function(data) {

                                    // actualiza tabla
                                    tabla_vehiculos_documentos(data.VEHICULO_ID);

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
                                beforeSend: function() {
                                    //espera
                                },
                                error: function(data) {
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
                        else 
                        {     
                            // modificar valor del campo
                            $('#VEHICULO_DOCUMENTO_ELIMINADO').val(1);
                            
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
                else 
                {     
                    // modificar valor del campo
                    $('#VEHICULO_DOCUMENTO_ELIMINADO').val(1);
                    
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

//Guardar y Editar documentos de equipos
$("#boton_guardar_vehiculo_documento").click(function() {
    var guardar = 0;

    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        if ($("#VEHICULO_PDF").val() != "")
        {
            // Tipo archivo
            var archivo = $("#VEHICULO_PDF").val();
            var extension = archivo.substring(archivo.lastIndexOf("."));

            // valida tipo de archivo
            if(extension == ".pdf" || extension == ".PDF")
            {
                guardar = 1;
            }
            else
            {
                // mensaje
                swal({
                    title: "Tipo de archivo incorrecto "+extension,
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
        else
        {
            guardar = 1;
        }

        // guardar
        if (guardar == 1)
        {
            // enviar datos
            $('#form_vehiculo_documento').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: "/proveedorvehiculo",
                data: {
                    api: 2
                },
                resetForm: false,
                success: function(data)
                {
                    // actualiza tabla
                    tabla_vehiculos_documentos(data.VEHICULO_ID);

                    // actualiza boton
                    $('#boton_guardar_vehiculo_documento').html('Guardar <i class="fa fa-save"></i>');

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
                    $('#modal_vehiculo_documento').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_vehiculo_documento').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(data) {
                    
                    // actualiza boton
                    $('#boton_guardar_vehiculo_documento').html('Guardar <i class="fa fa-save"></i>');

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
});



document.addEventListener('DOMContentLoaded', function() {
    const tipoDocumentoSelect = document.getElementById('DOCUMENTO_TIPO_VEHICULOS');
    const nombreDocumentoInput = document.getElementById('NOMBRE_DOCUMENTO_VEHICULOS');
    
    tipoDocumentoSelect.addEventListener('change', function() {
        const opcionSeleccionada = this.value;
        
 
        const textoOpcionSeleccionada = this.options[this.selectedIndex].text;
        
 
        if (opcionSeleccionada === '5') {
            nombreDocumentoInput.removeAttribute('readonly');
            nombreDocumentoInput.value = '';
        } else {
 
            nombreDocumentoInput.setAttribute('readonly', true);
            nombreDocumentoInput.value = textoOpcionSeleccionada;
        }
    });
});