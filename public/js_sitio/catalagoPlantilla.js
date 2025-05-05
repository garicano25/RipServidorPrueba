$(document).ready(function () { 

    datatable_tabla_plantilla = null;

    //Cargamos la tabla de los logos
    tabla_plantillas()

    $('#modal_plantilla').on('shown.bs.modal', function () {
        // $('#form_subir_imagen').trigger('reset');
        $('#RUTA_IMAGEN').dropify().data('dropify').clearElement();
    });

     // inicializar campos FOTOS
    if ($('#RUTA_IMAGEN').data('dropify'))
    {
        $('#RUTA_IMAGEN').dropify().data('dropify').resetPreview();
        $('#RUTA_IMAGEN').dropify().data('dropify').clearElement();
        // $('#plantillalogoizquierdo').dropify().data('dropify').destroy();
        // $('#plantillalogoizquierdo').dropify().data('dropify').init();
    }
    else
    {
      
        $('#RUTA_IMAGEN').dropify({
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



     $('#boton_nueva_platilla').click(function() {
        $('#modal_plantilla').modal('show'); // Muestra el modal
         $('#form_subir_imagen').trigger('reset');
    });


    $('#boton_guardar_logo').click(function() {
        var valida = this.form.checkValidity();
        if (valida) {
            swal({
                title: "¡Confirme para guardar este logo!",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();
        
                    // enviar datos
                    $('#form_subir_imagen').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: "/cliente",
                        data: {
                            opcion: 10,
                        },
                        resetForm: false,
                        success: function(data) {
                            // console.log(data);
        
                            // Cerrar modal
                            $('#modal_plantilla').modal('hide');
        
                            // Restablecer el formulario
                            $('#form_subir_imagen').trigger('reset');
        
                            // Actualizar tabla
        
                            // Restaurar el botón
                            $('#boton_guardar_logo').html('Guardar <i class="fa fa-save"></i>');
        
                            // Mostrar mensaje de éxito
                            swal({
                                title: "Logo guardado correctamente",
                                text: "Información guardada correctamente",
                                type: "success",
                                buttons: {
                                    visible: false,
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                            
                            tabla_plantillas();

                        },
                        beforeSend: function() {
                            $('#boton_guardar_logo').html('Guardando... <i class="fa fa-spin fa-spinner"></i>');
                        },
                        error: function(data) {
                            // Restaurar el botón en caso de error
                            $('#boton_guardar_logo').html('Guardar <i class="fa fa-save"></i>');
        
                            // Mostrar mensaje de error
                            swal({
                                title: "Error",
                                text: "Error al guardar la información",
                                type: "error",
                                buttons: {
                                    visible: false,
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }).submit();
        
                    return false;
                } else {
                    // mensaje
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
        }
    });
    
    })
    
function tabla_plantillas() {
    try {
        
        var ruta = "/tablaplantilla";

        if (datatable_tabla_plantilla != null) {
            datatable_tabla_plantilla.clear().draw();
            datatable_tabla_plantilla.ajax.url(ruta).load();
        } else {
            var numeroejecucion = 1;
            datatable_tabla_plantilla = $('#tabla_plantillas').DataTable({
                ajax: {
                    url: ruta,
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {},
                    dataSrc: function (json) {
                        return json.data;
                    },
                    error: function (xhr, error, code) {
                        console.log('error en datatable_tabla_plantillas ' + code);
                        if (numeroejecucion <= 1) {
                            tabla_plantillas();
                            numeroejecucion += 1;
                        }
                    }
                },
                columns: [
                    {
                        data: "num_registro",
                        defaultContent: "-",
                        orderable: false,
                    },
                    {
                        data: "NOMBRE_PLANTILLA",
                        defaultContent: "--",
                        orderable: false,
                    },
                    {
                        data: "RUTA_IMAGEN_LOGO",
                        orderable: false,
                        defaultContent: "-",
                    }
                    ,
                    
                    {
                        data: "DESCRIPCION_PLANTILLA",
                        defaultContent: "--",
                        orderable: false,
                    },
                    {
                        data: "boton_editar",
                        defaultContent: "--",
                        orderable: false,
                    },
                    {
                        data: "boton_eliminar",
                        defaultContent: "--",
                        orderable: false,
                    },
                ],
                // Resto de tu configuración
                lengthMenu: [[10, 20, 50, 100, -1], [10,20, 50, 100, "Todos"]],
                rowsGroup: [1], //agrupar filas
                order: [[ 0, "ASC" ]],
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
        datatable_tabla_plantilla.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        
    } catch (exception) {
        tabla_plantillas();
    }
}
//Eliminar logo catálogo

$('#tabla_plantillas tbody').on('click', 'td>button.boton_eliminar', function()
{
    var tr = $(this).closest('tr');
    var row = datatable_tabla_plantilla.row(tr);

    swal({   
        title: "¿Eliminar Logo?",
        text: ""+row.data().NOMBRE_PLANTILLA,
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

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/catalogoimageneseliminar/"+row.data().ID_PLANTILLA_IMAGEN,
                data:{},
                cache: false,
                success:function(dato)
                {
                    datatable_tabla_plantilla.ajax.reload();

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


$('#tabla_plantillas tbody').on('click', 'td>button.boton_editar', function (){
        var tr = $(this).closest('tr');
        var row = datatable_tabla_plantilla.row(tr);
        
    
    
    $("#ID_PLANTILLA_IMAGEN").val(row.data().ID_PLANTILLA_IMAGEN);
    $("#NOMBRE_PLANTILLA").val(row.data().NOMBRE_PLANTILLA);
    $("#DESCRIPCION_PLANTILLA").val(row.data().DESCRIPCION_PLANTILLA);
    


    if (row.data().RUTA_IMAGEN) {
        var archivo = row.data().RUTA_IMAGEN;
        var extension = archivo.substring(archivo.lastIndexOf("."));
        var imagenUrl = '/mostrarplantillafoto/' + row.data().ID_PLANTILLA_IMAGEN + extension;

        if ($('#RUTA_IMAGEN').data('dropify')) {
            $('#RUTA_IMAGEN').dropify().data('dropify').destroy();
            $('#RUTA_IMAGEN').dropify().data('dropify').settings.defaultFile = imagenUrl;
            $('#RUTA_IMAGEN').dropify().data('dropify').init();
        } else {
            $('#RUTA_IMAGEN').attr('data-default-file', imagenUrl);
            $('#RUTA_IMAGEN').dropify({
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
        $('#RUTA_IMAGEN').dropify().data('dropify').resetPreview();
        $('#RUTA_IMAGEN').dropify().data('dropify').clearElement();
    }




    



    

        // abrir modal
        $('#modal_plantilla').modal({backdrop:false});
    });



