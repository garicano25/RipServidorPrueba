{{-- ========================================================================= --}}
<div class="tab-pane active" role="tabpanel" id="tab_parametro_1">
    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
    <ol class="breadcrumb m-b-10">
        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo registro" id="boton_nuevo_parametro">
            <span class="btn-label"><i class="fa fa-plus"></i></span>Nuevo registro
        </button>
    </ol>
    @endif
    <table class="table table-hover stylish-table" width="100%" id="tabla_parametro">
        <thead>
            <tr>
                <th style="width: 60px!important;">No.</th>
                <th>Cantidad de Tipo 1</th>
                <th>Cantidad de Tipo 2</th>
                <th style="width: 80px!important;">Editar</th>
                <th style="width: 80px!important;">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
{{-- ========================================================================= --}}


<!-- ============================================================== -->
<!-- MODALES -->
<!-- ============================================================== -->
<div id="modal_parametro" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_parametro" id="form_parametro">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Mapa de riesgos</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="registro_id" name="registro_id" value="0">
                            <input type="hidden" class="form-control" id="parametro_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Mapa de riesgos Tipo 1 (60 X 90) *</label>
                                <input type="number" class="form-control" name="parametromapariesgo_tipo1" id="parametromapariesgo_tipo1" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Mapa de riesgos Tipo 2 (90 X 120) *</label>
                                <input type="number" class="form-control" name="parametromapariesgo_tipo2" id="parametromapariesgo_tipo2" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_parametro">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- /MODALES -->
<!-- ============================================================== -->



<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->
<script type="text/javascript" charset="utf-8" async defer>
    // Variables
    var recsensorial = <?php echo $recsensorial_id; ?>;
    var recsensorial_id = recsensorial + 0;
    var tabla_parametro = null;


    // Load pagina
    $(document).ready(function() {
        funcion_tabla_parametro(recsensorial_id);
        validarPermisosAsignados($('#proyecto_folio').val())

        $('[data-toggle="tooltip"]').tooltip();
    });


    function funcion_tabla_parametro(recsensorial_id) {
        tabla_parametro = $('#tabla_parametro').DataTable({
            "ajax": {
                "url": "/parametromapariesgotabla/" + recsensorial_id,
                "type": "get",
                "cache": false,
                error: function(xhr, error, code) {
                    // console.log(xhr); console.log(code);
                    // funcion_tabla_parametro(recsensorial_id);
                    tabla_parametro.ajax.url("/parametromapariesgotabla/" + recsensorial_id).load();
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
                    "data": "parametromapariesgo_tipo1",
                    "defaultContent": "-"
                },
                {
                    "data": "parametromapariesgo_tipo2",
                    "defaultContent": "-"
                },
                {
                    "className": 'editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '-'
                },
                {
                    "className": 'eliminar',
                    "orderable": false,
                    "data": 'boton_eliminar',
                    "defaultContent": '-'
                }

            ],
            "lengthMenu": [
                [5, 10, 30, -1],
                [5, 10, 30, "Todos"]
            ],
            // "rowsGroup": [0, 1], //agrupar filas
            "order": [
                [0, "DESC"]
            ],
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
                "processing": "Procesando <i class='fa fa-spin fa-spinner'></i>",
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

    //Funcion para la validacion de permisos asignados en proyectos
    function validarPermisosAsignados(proyecto_folio) {

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/validacionAsignacionUser/" + proyecto_folio,
            data: {},
            cache: false,
            success: function(dato) {

                if (dato.permisos == 1) {

                    $('input[type="submit"], button[type="submit"]').fadeIn(0);

                } else {

                    $('input[type="submit"], button[type="submit"]').fadeOut(0);

                }

            },
            beforeSend: function() {},
            error: function(dato) {
                // alert('Error: '+dato.msj);
                alert('Los permisos no han sido cargado')

                return false;
            }
        }); //Fin ajax
    }
    
    $("#boton_nuevo_parametro").click(function() {
        // Borrar formulario
        $('#form_parametro').each(function() {
            this.reset();
        });

        // // Campos Hidden
        $("#registro_id").val(0);
        $("#parametro_recsensorial_id").val(recsensorial_id);

        // mostrar modal
        $('#modal_parametro').modal({
            backdrop: false
        });
    });


    // Selecciona REGISTRO
    $(document).ready(function() {
        $('#tabla_parametro tbody').on('click', 'td.editar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametro.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                // Borrar formulario
                $('#form_parametro').each(function() {
                    this.reset();
                });

                // llenar campos
                $("#registro_id").val(row.data().id);
                $("#parametro_recsensorial_id").val(row.data().recsensorial_id);
                $("#parametromapariesgo_tipo1").val(row.data().parametromapariesgo_tipo1);
                $("#parametromapariesgo_tipo2").val(row.data().parametromapariesgo_tipo2);

                // mostrar modal
                $('#modal_parametro').modal({
                    backdrop: false
                });
            }
        });
    });


    // GUARDAR REGISTRO
    $("#boton_guardar_parametro").click(function() {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            // enviar datos
            $('#form_parametro').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: '/parametromapariesgo',
                data: {},
                resetForm: false,
                success: function(dato) {
                    // actualiza tabla
                    tabla_parametro.destroy();
                    funcion_tabla_parametro(recsensorial_id);

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
                    $('#boton_guardar_parametro').html('Guardar <i class="fa fa-save"></i>');

                    // cerrar modal
                    $('#modal_parametro').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_parametro').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(dato) {
                    // actualiza boton
                    $('#boton_guardar_parametro').html('Guardar <i class="fa fa-save"></i>');
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


    // eliminar REGISTRO
    $(document).ready(function() {
        $('#tabla_parametro tbody').on('click', 'td.eliminar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametro.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                swal({
                    title: "¿Eliminar registo?",
                    text: "Registro: " + row.data().numero_registro,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Eliminar!",
                    cancelButtonText: "Cancelar!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: "¡Confirme nuevamente que desea eliminar el registro!",
                            text: "Registro: " + row.data().numero_registro,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Eliminar!",
                            cancelButtonText: "Cancelar!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }, function(isConfirm) {
                            if (isConfirm) {
                                // cerrar msj confirmacion
                                swal.close();

                                // eliminar
                                $.ajax({
                                    type: "GET",
                                    dataType: "json",
                                    url: "/parametromapariesgoeliminar/" + row.data().id,
                                    data: {},
                                    cache: false,
                                    success: function(dato) {
                                        // actualiza tabla
                                        tabla_parametro.destroy();
                                        funcion_tabla_parametro(recsensorial_id);

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
                                    error: function(dato) {
                                        // alert('Error: '+dato.msj);
                                        return false;
                                    }
                                }); //Fin ajax
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
            }
        });
    });
</script>
<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->