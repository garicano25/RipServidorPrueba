var datatable_proyectos = null;
var tabledetalleProyectosGestion = null;
var tabledetalleProyectosResultados = null;
$(document).ready(function()
{

				var id_ordentrabajo = $('#id_ordentrabajo').val();
                var id_proyecto = $('#id_proyecto').val();
                // var faseid_ordentrabajo = $('#faseid_ordentrabajo').val();
                // console.log(id_ordentrabajo);
                tabla_gestion(id_ordentrabajo,id_proyecto);
                tabla_resultados(id_ordentrabajo,id_proyecto);
                tabla_finalizacion(id_ordentrabajo,id_proyecto);
                
                var table = $('#tabla_detalleProyectos').DataTable({
                    initComplete : function() {
                                        // $("#Busqueda").text("Busqueda");
                                        $("#tabla_detalleProyectos_filter input").removeClass("form-control");
                                        $("#tabla_detalleProyectos_filter input").detach().appendTo('#BusquedaMuestras');
                                        $("#BusquedaMuestras input").addClass("form-control bg-light border-0 small");
                                        $("#BusquedaMuestras input").attr("placeholder",'Buscar');
                                        $("#tabla_detalleProyectos_filter label").remove(); 
                                        var fechaEntrega = $("#proyecto_fechaentrega").val();
                                        var fechaInicio = $("#proyecto_fechainicio").val();
                                        $('.FechaIncioPrograma').bootstrapMaterialDatePicker({
                                            lang : 'es',
                                            // format: 'YYYY-MM-DD HH:mm',
                                            weekStart: 0,
                                            time: false
                                    });
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker({
                                            lang : 'es',
                                            // format: 'YYYY-MM-DD HH:mm',
                                            weekStart: 0,
                                            time: false
                                    });

                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);

                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker({ weekStart: 0 }).on('change', function(e, date) {
                                        $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMinDate', date);
                                    });

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker({
                                                lang : 'es',
                                                // format: 'YYYY-MM-DD HH:mm',
                                                weekStart: 0,
                                                time: false
                                        });
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker({
                                                lang : 'es',
                                                // format: 'YYYY-MM-DD HH:mm',
                                                weekStart: 0,
                                                time: false
                                        });

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                        $('.FechaIncioReal').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker({ weekStart: 0 }).on('change', function(e, date) {
                                            $('.FechaFinalReal').bootstrapMaterialDatePicker('setMinDate', date);
                                        });                                    
                                        // $('#FechaFinalReal').bootstrapMaterialDatePicker({
                                        //     format: 'YYYY-MM-DD HH:mm',
                                        //     minDate: new Date()
                                        // });
                                     },
                                "ajax": {
                                        "url": "/detalletablaproyecto/2/"+id_proyecto+"/"+id_ordentrabajo,
                                        "type": "get",
                                        "cache": false
                                        // "data": {Tabla:1}
                                    },
                            'responsive': true,
                            "searching": true,
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "columns": [
                                         {
                                            "className":      'details-control',
                                            "orderable":      false,
                                            "data":           null,
                                            "defaultContent": ''
                                        },
                                        { 
                                            "data": "proyectoordentrabajodatos_agentenombre" 
                                        },
                                        { 
                                            "className": 'align-middle text-center',
                                            "data": "proyectoordentrabajodatos_agentepuntos" 
                                        },
                                        
                                        { 
                                            "className": 'align-middle text-center',
                                            "data":           null,
                                            "defaultContent": ''
                                        },
                                        { 
                                            "className": 'align-middle',
                                            "data": 'proveedor.proveedor_NombreComercial' 
                                        },
                                        { 
                                            "className": 'align-middle',
                                            "data": 'proyectoordentrabajo.proyecto.proyecto_fechainicio'
                                        },
                                        {
                                            "className": 'align-middle text-center',
                                            "data": 'proyectoordentrabajo.proyecto.proyecto_fechaentrega'
                                        }
                                    ],
                    "language": {
                                "lengthMenu": "Mostrar _MENU_ Registros",
                                "zeroRecords": "No se encontraron registros",
                                "info": "Mostrando página _PAGE_ de _PAGES_",
                                "infoEmpty": "No se encontraron registros",
                                "infoFiltered": "(Filtrado de _MAX_ registros)",
                                "emptyTable": "No hay datos disponibles en la tabla",
                                "loadingRecords": "Cargando datos....",
                                "processing": "Procesando...",
                                "search": "Buscar",
                                "paginate": {
                                    "first": "Primera",
                                    "last": "Ultima",
                                    "next": "Siguiente",
                                    "previous": "Anterior"
                                }
                            }
                  });
                $('#tabla_detalleProyectos tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    if ( row.child.isShown() ) {
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // if (row.data().proyectoordentrabajo.length > 0) {
                            var proyecto_id = row.data().proyectoordentrabajo.proyecto.id
                            var proyectoordentrabajodatos_id = row.data().id
                            $.ajax({                                           
                                url:"/detalleprograma/"+proyecto_id+"/"+row.data().proyectoordentrabajo_id+"/"+proyectoordentrabajodatos_id,
                                type: "GET",
                                dataType:"json",
                                data:"",
                                success: function(response){
                                    swal({
                                        title: "Correcto",
                                        text: "Información cargada correctamente",
                                        type: "success", // warning, error, success, info
                                        buttons: {
                                            visible: false, // true , false
                                        },
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                   row.child( format(response) ).show();
                                   tr.addClass('shown');
                                   var fechaEntrega = $("#proyecto_fechaentrega").val();
                                        var fechaInicio = $("#proyecto_fechainicio").val();
                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker({
                                            lang : 'es',
                                            // format: 'YYYY-MM-DD HH:mm',
                                            weekStart: 0,
                                            time: false
                                    });
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker({
                                            lang : 'es',
                                            // format: 'YYYY-MM-DD HH:mm',
                                            weekStart: 0,
                                            time: false
                                    });

                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                    $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);

                                    $('.FechaIncioPrograma').bootstrapMaterialDatePicker({ weekStart: 0 }).on('change', function(e, date) {
                                        $('.FechaFinalPrograma').bootstrapMaterialDatePicker('setMinDate', date);
                                    });

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker({
                                                lang : 'es',
                                                // format: 'YYYY-MM-DD HH:mm',
                                                weekStart: 0,
                                                time: false
                                        });
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker({
                                                lang : 'es',
                                                // format: 'YYYY-MM-DD HH:mm',
                                                weekStart: 0,
                                                time: false
                                        });

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                        $('.FechaIncioReal').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker('setMinDate', fechaInicio);
                                        $('.FechaFinalReal').bootstrapMaterialDatePicker('setMaxDate', fechaEntrega);

                                        $('.FechaIncioReal').bootstrapMaterialDatePicker({ weekStart: 0 }).on('change', function(e, date) {
                                            $('.FechaFinalReal').bootstrapMaterialDatePicker('setMinDate', date);
                                        });                                    
                                },
                                error : function(){
                                    alert(' Error en el servidor !!! ');
                                },
                                beforeSend: function()
                                {
                                   swal({
                                                title: "Cargando...",
                                                text: "Por favor espere",
                                                type:"info",
                                                icon: "/assets/img/cargando.gif",
                                                button: false,
                                                closeOnClickOutside: false,
                                                closeOnEsc: false
                                              });
                                }
                             });
                    }
                } );
	           tabla_proyecto();
});



function tabla_gestion(id_ordentrabajo,id_proyecto)
{
    tabledetalleProyectosGestion = $('#tabla_detalleProyectosGestion').DataTable({
        initComplete : function() {
                            // $("#Busqueda").text("Busqueda");
                            $("#tabla_detalleProyectosGestion_filter input").removeClass("form-control");
                            $("#tabla_detalleProyectosGestion_filter input").detach().appendTo('#BusquedaGestion');
                            $("#BusquedaGestion input").addClass("form-control bg-light border-0 small");
                            $("#BusquedaGestion input").attr("placeholder",'Buscar');
                            $("#tabla_detalleProyectosGestion_filter label").remove();                
                         },
        "ajax": {
                        "url": "/detalleprogramagestion/"+id_proyecto+"/"+id_ordentrabajo+"/1",
                            "type": "get",
                            "cache": false
                            // "data": {Tabla:1}
                        },
                        'responsive': true,
                        "searching": true,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                "columns": [
                              {
                                "className": 'align-middle text-center',
                                "data": "Consecutivo",
                                
                            },
                            { 
                                "data": "programa_Actividad" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioPrograma" 
                            },
                            
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinPrograma" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioReal" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinReal" 
                            },
                            { 
                                "className": 'align-middle text-center',
                                "data": "programaEstatus"
                                
                            },
                            {
                                "className": 'align-middle text-center',
                                "data": "Semaforo"
                            },
                            {
                                "className":      'detalle-control',
                                            "orderable":      false,
                                            "data":           null,
                                            "defaultContent": ''
                                
                            }
                        ],
        "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando...",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
      });
    $('#tabla_detalleProyectosGestion tbody').on('click', 'td.detalle-control', function () {
                    var tr = $(this).closest('tr');
                    var row = tabledetalleProyectosGestion.row( tr );
                    $('#actividad_id').val(row.data().id);  
                    $('#programa_Actividad').val(row.data().programa_Actividad);  
                    $('#programa_InicioPrograma').val(row.data().programa_InicioPrograma); 
                    $('#programa_FinPrograma').val(row.data().programa_FinPrograma); 
                    $('#programa_InicioReal').val(row.data().programa_InicioReal); 
                    $('#programa_FinReal').val(row.data().programa_FinReal); 
                    $('#programa_Comentario').val(row.data().programa_Comentario);  
                    if(row.data().programa_Estatus == 1)
                    {
                        // alert(row.data().programa_Estatus);
                        $('#programa_Estatus').attr('checked',true);
                    }
                    else
                    {
                         $('#programa_Estatus').attr('checked',false);
                    }
                    $('#modal_Actividad').modal({backdrop:false});
                   console.log(row.data());
    });
}
function tabla_resultados(id_ordentrabajo,id_proyecto)
{
    tabledetalleProyectosResultados = $('#tabla_detalleProyectosResultados').DataTable({
        initComplete : function() {
                            // $("#Busqueda").text("Busqueda");
                            $("#tabla_detalleProyectosResultados_filter input").removeClass("form-control");
                            $("#tabla_detalleProyectosResultados_filter input").detach().appendTo('#BusquedaResultado');
                            $("#BusquedaResultado input").addClass("form-control bg-light border-0 small");
                            $("#BusquedaResultado input").attr("placeholder",'Buscar');
                            $("#tabla_detalleProyectosResultados_filter label").remove();                
                         },
        "ajax": {
                        "url": "/detalleprogramagestion/"+id_proyecto+"/"+id_ordentrabajo+"/3",
                            "type": "get",
                            "cache": false
                            // "data": {Tabla:1}
                        },
                        'responsive': true,
                        "searching": true,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                "columns": [
                             {
                                "className": 'align-middle text-center',
                                "data": "Consecutivo",
                                
                            },
                            { 
                                "data": "programa_Actividad" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioPrograma" 
                            },
                            
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinPrograma" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioReal" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinReal" 
                            },
                            { 
                                "className": 'align-middle text-center',
                                "data": "programaEstatus"
                                
                            },
                            {
                                "className": 'align-middle text-center',
                                "data": "Semaforo"
                            },
                            {
                                "className":      'detalle-controlResultado',
                                            "orderable":      false,
                                            "data":           null,
                                            "defaultContent": ''
                                
                            }
                        ],
        "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando...",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
      });
    $('#tabla_detalleProyectosResultados tbody').on('click', 'td.detalle-controlResultado', function () {
                    var tr = $(this).closest('tr');
                    var row = tabledetalleProyectosResultados.row( tr );

                    $('#actividad_id').val(row.data().id);  
                    $('#programa_Actividad').val(row.data().programa_Actividad);  
                    $('#programa_InicioPrograma').val(row.data().programa_InicioPrograma); 
                    $('#programa_FinPrograma').val(row.data().programa_FinPrograma); 
                    $('#programa_InicioReal').val(row.data().programa_InicioReal); 
                    $('#programa_FinReal').val(row.data().programa_FinReal); 
                    $('#programa_Comentario').val(row.data().programa_Comentario);  
                    if(row.data().programa_Estatus == 1)
                    {
                        // alert(row.data().programa_Estatus);
                        $('#programa_Estatus').attr('checked',true);
                    }
                    else
                    {
                         $('#programa_Estatus').attr('checked',false);
                    }
                    $('#modal_Actividad').modal({backdrop:false});

                   console.log(row.data());
    });
}
function tabla_finalizacion(id_ordentrabajo,id_proyecto)
{
    var tabledetalleProyectosFinalizacion = $('#tabla_detalleProyectosFinalizacion').DataTable({
        initComplete : function() {
                            // $("#Busqueda").text("Busqueda");
                            $("#tabla_detalleProyectosFinalizacion_filter input").removeClass("form-control");
                            $("#tabla_detalleProyectosFinalizacion_filter input").detach().appendTo('#BusquedaFinalizacion');
                            $("#BusquedaFinalizacion input").addClass("form-control bg-light border-0 small");
                            $("#BusquedaFinalizacion input").attr("placeholder",'Buscar');
                            $("#tabla_detalleProyectosFinalizacion_filter label").remove();                
                         },
        "ajax": {
                        "url": "/detalleprogramagestion/"+id_proyecto+"/"+id_ordentrabajo+"/4",
                            "type": "get",
                            "cache": false
                            // "data": {Tabla:1}
                        },
                        'responsive': true,
                        "searching": true,
                        "paging": false,
                        "ordering": false,
                        "info": false,
                "columns": [
                            {
                                "className": 'align-middle text-center',
                                "data": "Consecutivo",
                                
                            },
                            { 
                                "data": "programa_Actividad" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioPrograma" 
                            },
                            
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinPrograma" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_InicioReal" 
                            },
                            { 
                                "className": 'align-middle text-left',
                                "data": "programa_FinReal" 
                            },
                            { 
                                "className": 'align-middle text-center',
                                "data": "programaEstatus"
                                
                            },
                            {
                                "className": 'align-middle text-center',
                                "data": "Semaforo"
                            },
                            {
                                "className":      'detalle-controlResultado',
                                            "orderable":      false,
                                            "data":           null,
                                            "defaultContent": ''
                                
                            }
                        ],
        "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando...",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
      });
    $('#tabla_detalleProyectosFinalizacion tbody').on('click', 'td.detalle-controlResultado', function () {
                    var tr = $(this).closest('tr');
                    var row = tabledetalleProyectosFinalizacion.row( tr );

                    $('#programa_Actividad').val(row.data().programa_Actividad);  
                    $('#programa_InicioPrograma').val(row.data().programa_InicioPrograma); 
                    $('#programa_FinPrograma').val(row.data().programa_FinPrograma); 
                    $('#programa_InicioReal').val(row.data().programa_InicioReal); 
                    $('#programa_FinReal').val(row.data().programa_FinReal);    

                    $('#modal_Actividad').modal({backdrop:false});

                   console.log(row.data());
    });
}

function format ( d ) {
        var contenido = "";
                                    
            contenido =  '<table class="table-striped" cellpadding="0" cellspacing="0" border="0" style="margin:10px 0px;" class="table-bordered"> <tbody>';
            contenido += '<tr><th colspan="8"><h4>DESGLOSE DE ACTIVIDADES</h4></th></tr>';
            contenido += '<tr>';
            contenido += '<th>#</th>';
            contenido += '<th>Actividad</th>';
             contenido += '<th>Programa</th>';
            contenido += '<th>Real</th>';
            contenido += '<th>¿Concluido?</th>';
            contenido += '<th>Guardar</th>';
            contenido += '<th class="text-center align-middle">Estado</th>';
            contenido += '</tr>';
                $.each(d, function(index, val) {
                    contenido += '<tr>'+
                        '<td width="2%">'+(index+1)+'</td>'+
                        '<td width="40%"  class="align-middle">'+val.programa_Actividad+'</td>'+
                        '<td width="5%" class="align-middle">Incio<input type="text" step="any" class="form-controljc" value="'+val.programa_InicioPrograma+'" id="programa_InicioPrograma'+val.id+'" name="programa_InicioPrograma'+val.id+'">Fin<input type="text" step="any" class="form-controljc" value="'+val.programa_FinPrograma+'" id="programa_FinPrograma'+val.id+'" name="programa_FinPrograma'+val.id+'"></td>'+
                        '<td width="5%" class="align-middle">Inicio<input type="text" step="any" class="form-controljc FechaIncioReal" value="'+val.programa_InicioReal+'" id="programa_InicioReal'+val.id+'" name="programa_InicioReal'+val.id+'">Fin<input type="text" step="any" class="form-controljc FechaFinalReal" value="'+val.programa_FinReal+'" id="programa_FinReal'+val.id+'" name="programa_FinReal'+val.id+'"></td>'+
                        '<td width="5%" class="text-center align-middle"><br><input type="checkbox" id="programa_Estatus'+val.id+'" name="programa_Estatus'+val.id+'" class="material-inputs filled-in chk-col-light-blue" value="1" '+(val.programa_Estatus==1?'checked':'')+'><label for="programa_Estatus'+val.id+'"></label></td>'+
                        '<td width="8%" class="text-center align-middle">'+val.Semaforo+'</td>'+
                        '<td width="5%" class="text-center align-middle"><button type="button" class="btn btn-success waves-effect" id="boton_GuardarActividad'+val.id+'" onClick="guardarActividad('+val.id+')"><i class="fa fa-save fa-2x"></i></button></td>'+
                    '</tr>';
            });
            contenido +=' </tbody></table>';
            return contenido;

         // `d` is the original data object for the row
       
}
function guardarActividad(programa_id)
{
    if($("#programa_InicioPrograma"+programa_id).val() == "")
    {
        swal({
            title: "Correcto",
            text: "La fecha de inicio programado esta vacia",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 1500,
            showConfirmButton: false
        });
        return false;
    }
    if($("#programa_FinPrograma"+programa_id).val() == "")
    {
        swal({
            title: "Correcto",
            text: "La fecha de final programado esta vacia",
            type: "warning", // warning, error, success, info
            buttons: {
                visible: false, // true , false
            },
            timer: 1500,
            showConfirmButton: false
        });
        return false;
    }
    console.log($("input[name*='_token']").val());
    console.log($("#programa_InicioPrograma"+programa_id).val());
    console.log($("#programa_FinPrograma"+programa_id).val());
    console.log($("#programa_InicioReal"+programa_id).val());
    console.log($("#programa_FinReal"+programa_id).val());
    console.log($("#programa_Responsable"+programa_id).val());

    $.ajax({                                           
        url:"/programa/"+programa_id,
        type: "POST",
        dataType:"json",
        data:{
            programa_InicioPrograma: $("#programa_InicioPrograma"+programa_id).val(),
            programa_FinPrograma: $("#programa_FinPrograma"+programa_id).val(),
            programa_InicioReal: $("#programa_InicioReal"+programa_id).val(),
            programa_FinReal: $("#programa_FinReal"+programa_id).val(),
            programa_Estatus: $("#programa_Estatus"+programa_id).prop('checked')?1:0,
            programa_Responsable: $("#programa_Responsable"+programa_id).val(),
            _method :  'PUT',
            _token: $("input[name*='_token']").val()
        },
        success: function(response){
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
        },
        error : function(){
            swal({
                title: "Correcto",
                text: "Error en el servidor",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        beforeSend: function()
        {
           swal({
                        title: "Cargando...",
                        text: "Por favor espere",
                        type:"info",
                        icon: "/assets/img/cargando.gif",
                        button: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false
                      });
        }
     });
}


function detalleproyecto(proyecto_id)
{
	location.href = "/seguimiento/"+proyecto_id;
}

function tabla_proyecto()
{
	try 
	{
		var ruta = "/listadoproyectos";

		if (datatable_proyectos != null)
		{
			datatable_proyectos.clear().draw();
			datatable_proyectos.ajax.url(ruta).load();
		}
		else
		{
			datatable_proyectos = $('#tabla_AdministracionProyectos').DataTable({
				"ajax": {
					"url": ruta,
					"type": "get",
					"cache": false,
					error: function (xhr, error, code)
					{
						// console.log(xhr); console.log(code);
						tabla_proyecto();
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
					    "data": "proyecto_folio",
					    "defaultContent": "-"
					},
					{
					    "data": "proyecto_clienterazonsocial",
					    "defaultContent": "-"
					},
					{
					    "data": "instalacion_y_direccion",
					    "defaultContent": "-"
					},
					{
					    "data": "inicio_y_fin",
					    "defaultContent": "-"
					},
					{
					    "data": "recsensorial_folios",
					    "defaultContent": "-"
					},
					{
					    "className": 'mostrar',
					    "orderable": false,
					    "data": 'boton_mostrar',
					    "defaultContent": '-'
					}
				],
		        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
		        // "rowsGroup": [0, 1], //agrupar filas
				"order": [[ 0, "DESC" ]],
				"ordering": true,
				"processing": true,
				"language": {
					"lengthMenu": "Mostrar _MENU_ Registros",
					"zeroRecords": "No se encontraron registros",
					"info": "Página _PAGE_ de _PAGES_ (Total _MAX_ registros)",
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
		datatable_proyectos.on('draw', function ()
		{
			$('[data-toggle="tooltip"]').tooltip();
		});
	}
	catch (exception)
	{
        tabla_proyecto();
    }
}


$("#boton_guardar_actividad").click(function() {
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_Actividades').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: "/programa/"+$("#actividad_id").val(),
            data: {
                _method :  'PUT',
                _token: $("input[name*='_token']").val()
            },
            resetForm: true,
            success: function(data) {
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
                $('#modal_Actividad').modal('hide');
                tabledetalleProyectosGestion.ajax.reload();
                tabledetalleProyectosResultados.ajax.reload();

            },
            beforeSend: function() {
                swal({
                        title: "Cargando...",
                        text: "Por favor espere",
                        type:"info",
                        icon: "/assets/img/cargando.gif",
                        button: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false
                      });
            },
            error: function(data) {
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
            }
        }).submit();

        return false;
    }
});