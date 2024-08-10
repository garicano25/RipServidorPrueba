<!-- Popup CSS -->
<link href="/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">

{{-- ========================================================================= --}}
<div class="tab-pane active" role="tabpanel" id="tab_parametro_1">
    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
    <ol class="breadcrumb m-b-10">
        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nueva evidencia <br> fotográfica / Plano" data-html="true" id="boton_nueva_fotoevidencia">
            <span class="btn-label"><i class="fa fa-plus"></i></span>Evidencia fotográfica / Plano
        </button>
    </ol>
    @else
    <ol class="breadcrumb m-b-10">
        Evidencia fotográfica / Planos
    </ol>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body cardbody_galeria">
                    <style type="text/css">
                        #image-popups .foto_galeria:hover i {
                            opacity: 1 !important;
                            cursor: pointer;
                        }
                    </style>
                    <div class="row galeria" id="image-popups" style="height: auto; max-height: 230px; overflow-y: auto; overflow-x: none;">
                        {{--
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 foto_galeria">
                            <span style="font-size: 13px; color: #FFFFFF; text-shadow: 0 0 3px #000000, 0 0 3px #000000; position: absolute; left: 20px;">Foto Motogeneradores</span>
                            <i class="fa fa-trash text-danger" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 24px;" data-toggle="tooltip" title="Eliminar" onclick="foto_eliminar(0);"></i>
                            <i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 60px;" data-toggle="tooltip" title="Descargar" onclick="foto_descargar(0);"></i>
                            <a href="/recsensorialevidenciafotomostrar/1" data-effect="mfp-zoom-in">
                                <img class="d-block img-fluid" src="/recsensorialevidenciafotomostrar/1" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar"/>
                            </a>
                        </div>
                         --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <th style="width: 100px!important;">No.</th>
                <th>Área</th>
                <th>Categoría</th>
                <th>Personas a evaluar (puntos)</th>
                <th style="width: 100px!important;">Editar</th>
                <th style="width: 100px!important;">Eliminar</th>
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
                    <h4 class="modal-title" id="modal_titulo">Factores psicosociales</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="registro_id" name="registro_id" value="0">
                            <input type="hidden" class="form-control" id="parametro_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Área *</label>
                                <select class="custom-select form-control" id="select_area" name="recsensorialarea_id" onchange="consulta_select_categoriasxarea(this.value, 0, 'select_categoria');" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Categoría *</label>
                                <select class="custom-select form-control" id="select_categoria" name="recsensorialcategoria_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>No. personas a evaluar (puntos) *</label>
                                <input type="number" class="form-control" name="parametropsicosocial_nopersonas" id="parametropsicosocial_nopersonas" required>
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
<!-- MODAL-EVIDENCIA-FOTOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_evidencia_fotos .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_evidencia_fotos .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_evidencia_fotos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 900px!important;">
        <form method="post" enctype="multipart/form-data" name="form_evidencia_fotos" id="form_evidencia_fotos">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Fotos evidencia</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="form-group">
                                <label> Foto evidencia / Plano *</label>
                                <style type="text/css" media="screen">
                                    .dropify-wrapper {
                                        height: 292px !important;
                                        /*tamaño estatico del campo foto*/
                                    }
                                </style>
                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="inputevidenciafoto" name="inputevidenciafoto" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" onchange="redimencionar_fotoevidencia();" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Tipo de evidencia *</label>
                                        <select class="custom-select form-control" id="recsensorialevidencias_tipo" name="recsensorialevidencias_tipo" onchange="descripcion_foto()" required>
                                            <option value=""></option>
                                            <option value="1">Foto evidencia</option>
                                            <option value="2">Plano</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Área *</label>
                                        <select class="custom-select form-control" id="recsensorialevidencias_recsensorialarea_id" name="recsensorialarea_id" onchange="descripcion_foto()" required>
                                            <option value=""></option>
                                            @foreach($recsensorialareas as $dato)
                                            <option value="{{$dato->id}}">{{$dato->recsensorialarea_nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Descripción de la (foto / plano) en el reporte</label>
                                        <textarea class="form-control" rows="6" id="recsensorialevidencias_descripcion" name="recsensorialevidencias_descripcion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="display: none;" id="mensaje_cargando_fotos">
                            <p class="text-info" style="text-align: center; margin: 0px; padding: 0px;"><i class="fa fa-spin fa-spinner"></i> Cargando fotos, espere un momento por favor...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                    <button type="submit" class="btn btn-danger" id="boton_guardar_evidencia_fotos">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-FOTOS -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->
<script type="text/javascript" charset="utf-8" async defer>
    // Variables
    var recsensorial_id = ({
        {
            $recsensorial_id
        }
    } + 0);
    var tabla_parametro = null;


    // Load pagina
    $(document).ready(function() {
        funcion_tabla_parametro(recsensorial_id);
        // consulta_select_areas(recsensorial_id, 0);
        // consulta_select_categorias(recsensorial_id, 0);
        $('[data-toggle="tooltip"]').tooltip();
    });


    function consulta_select_areas(recsensorial_id, seleccionado_id) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/recsensorialconsultaareas/" + recsensorial_id + "/" + seleccionado_id + "/" + 0,
            data: {},
            cache: false,
            success: function(dato) {
                $("#select_area").html(dato.opciones);
            },
            error: function(dato) {
                // alert('Error: '+dato.msj);
                return false;
            }
        }); //Fin ajax
    }


    function consulta_select_categorias(recsensorial_id, seleccionado_id) {
        // alert('mensaje '+seleccionado_id);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/recsensorialconsultaselectcategorias/" + recsensorial_id + "/" + seleccionado_id,
            data: {},
            cache: false,
            success: function(dato) {
                $("#select_categoria").html(dato.opciones);
                $("#select_categoria_2").html(dato.opciones);
            },
            error: function(dato) {
                // alert('Error: '+dato.msj);
                return false;
            }
        }); //Fin ajax
    }


    function consulta_select_categoriasxarea(recsensorialarea_id, recsensorialcategoria_id, campo_nombre) {
        // alert('mensaje '+seleccionado_id);
        if (recsensorialarea_id) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/recsensorialselectcategoriasxarea/" + recsensorialarea_id + "/" + recsensorialcategoria_id,
                data: {},
                cache: false,
                success: function(dato) {
                    $("#" + campo_nombre).html(dato.opciones);
                },
                error: function(dato) {
                    $("#" + campo_nombre).html('<option value="">No hay datos que mostrar</option>');
                    return false;
                }
            }); //Fin ajax
        } else {
            $("#" + campo_nombre).html('<option value="">&nbsp;</option>');
        }
    }


    function funcion_tabla_parametro(recsensorial_id) {
        tabla_parametro = $('#tabla_parametro').DataTable({
            "ajax": {
                "url": "/parametropsicosocialtabla/" + recsensorial_id,
                "type": "get",
                "cache": false,
                error: function(xhr, error, code) {
                    // console.log(xhr); console.log(code);
                    // funcion_tabla_parametro(recsensorial_id);
                    tabla_parametro.ajax.url("/parametropsicosocialtabla/" + recsensorial_id).load();
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
                    "data": "recsensorialarea.recsensorialarea_nombre",
                    "defaultContent": "Sin dato"
                },
                {
                    "data": "recsensorialcategoria.recsensorialcategoria_nombrecategoria",
                    "defaultContent": "Sin dato"
                },
                {
                    "data": "parametropsicosocial_nopersonas"
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
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
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


    $("#boton_nuevo_parametro").click(function() {
        consulta_select_areas(recsensorial_id, 0);
        // consulta_select_categorias(recsensorial_id, 0);
        $("#select_categoria").html('<option value="">&nbsp;</option>');

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
                $("#parametropsicosocial_nopersonas").val(row.data().parametropsicosocial_nopersonas);
                consulta_select_areas(recsensorial_id, row.data().recsensorialarea_id);
                // consulta_select_categorias(recsensorial_id, row.data().recsensorialcategoria_id);
                consulta_select_categoriasxarea(row.data().recsensorialarea_id, row.data().recsensorialcategoria_id, 'select_categoria');

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
                url: '/parametropsicosocial',
                data: {},
                resetForm: false,
                success: function(dato) {
                    // Campos Hidden
                    $("#registro_id").val(dato.parametro.id);
                    $("#parametro_recsensorial_id").val(dato.parametro.recsensorial_id);

                    // actualiza tabla
                    tabla_parametro.destroy();
                    funcion_tabla_parametro(dato.parametro.recsensorial_id);

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
                                    url: "/parametropsicosocialeliminar/" + row.data().id,
                                    data: {},
                                    cache: false,
                                    success: function(dato) {
                                        // actualiza tabla
                                        tabla_parametro.destroy();
                                        funcion_tabla_parametro(row.data().recsensorial_id);

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



    //------------------------------------------------------------


    var parametro_id = 14; // ID en base datos
    var parametro_nombre = 'factores psicosociales';
    var foto_resizebase64 = "";


    // Load pagina
    $(document).ready(function() {
        // inicializar campo FOTO mapa ubicacion
        $('#inputevidenciafoto').dropify({
            messages: {
                'default': 'Arrastre la imagen aquí o haga click',
                'replace': 'Arrastre la imagen o haga clic para reemplazar',
                'remove': 'Quitar',
                'error': 'Ooops, ha ocurrido un error.'
            },
            error: {
                'fileSize': 'Demasiado grande (5MB max).',
                'minWidth': 'Ancho demasiado pequeño (min 100px).',
                'maxWidth': 'Ancho demasiado grande (max 1200px).',
                'minHeight': 'Alto demasiado pequeño (min 400px).',
                'maxHeight': 'Alto demasiado grande (max 800px max).',
                'imageFormat': 'Formato no permitido, sólo (.JPG y .PNG).'
            }
        });

        consulta_evidencia_fotos(recsensorial_id, parametro_id);
    });


    $("#boton_nueva_fotoevidencia").click(function() {
        // Borrar formulario
        $('#form_evidencia_fotos').each(function() {
            this.reset();
        });

        // Resetear input FOTO
        $('#inputevidenciafoto').val('');
        $('#inputevidenciafoto').dropify().data('dropify').resetPreview();
        $('#inputevidenciafoto').dropify().data('dropify').clearElement();

        // Campos Hidden
        $("#evidenciafotos_id").val(0);

        $("#recsensorialevidencias_recsensorialarea_id").val('');
        $("#recsensorialevidencias_recsensorialarea_id").attr('disabled', true)
        $("#recsensorialevidencias_descripcion").val('');
        $("#recsensorialevidencias_descripcion").attr('disabled', true)

        // Inicializar variable FOTO
        foto_resizebase64 = "";

        // mostrar modal
        $('#modal_evidencia_fotos').modal({
            backdrop: false
        });
    });


    function redimencionar_fotoevidencia() {
        // Mostrar mensaje de espera
        $('#mensaje_cargando_fotos').css('display', 'block');
        $('#boton_guardar_evidencia_fotos').attr('disabled', true);

        foto_resizebase64 = "";
        var filesToUpload = document.getElementById('inputevidenciafoto').files;
        var file = filesToUpload[0];

        // Create an image
        var img = document.createElement("img");

        // Create a file reader
        var reader = new FileReader();

        // Load files into file reader
        reader.readAsDataURL(file);

        // Set the image once loaded into file reader
        reader.onload = function(e) {
            //img.src = e.target.result;
            var img = new Image();
            img.src = this.result;

            setTimeout(function() {
                var canvas = document.createElement("canvas");
                //var canvas = $("<canvas>", {"id":"testing"})[0];
                //var ctx = canvas.getContext("2d");
                //ctx.drawImage(img, 0, 0);

                // Dimensiones reales
                var width = img.width;
                var height = img.height;

                // Dimensiones Nuevas
                if (parseInt(width) > 8000) {
                    var MAX_WIDTH = 4000; //Ancho de la imagen
                    var MAX_HEIGHT = 3000; //Alto de la imagen
                } else {
                    var MAX_WIDTH = 1200; //Ancho de la imagen
                    var MAX_HEIGHT = 900; //Alto de la imagen
                }

                // Dimensionar con respecto a la relacion de aspecto
                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, width, height);
                console.log("Nuevas dimensiones ", width, height);

                // Resultado
                var dataurl = canvas.toDataURL("image/jpeg");
                // document.getElementById('imagen_nueva').src = dataurl; //Mostrar en una imagen
                foto_resizebase64 = dataurl; //Guardar en una variable

                // Quitar mensaje de espera
                $('#mensaje_cargando_fotos').css('display', 'none');
                $('#boton_guardar_evidencia_fotos').attr('disabled', false);
            }, 100);
        }
    }


    function descripcion_foto() {
        if (parseInt($("#recsensorialevidencias_tipo").val()) > 0) {
            if (parseInt($("#recsensorialevidencias_tipo").val()) == 1) {
                $("#recsensorialevidencias_descripcion").attr('disabled', false);
                $("#recsensorialevidencias_recsensorialarea_id").attr('disabled', false);

                if (parseInt($("#recsensorialevidencias_recsensorialarea_id").val()) > 0) {
                    $("#recsensorialevidencias_descripcion").val('Evidencia de reconocimiento de ' + parametro_nombre + ' en ' + $("#recsensorialevidencias_recsensorialarea_id option:selected").text());
                } else {
                    $("#recsensorialevidencias_descripcion").val('Evidencia de reconocimiento de ' + parametro_nombre);
                }
            } else {
                $("#recsensorialevidencias_recsensorialarea_id").val('');
                $("#recsensorialevidencias_recsensorialarea_id").attr('disabled', true);

                $("#recsensorialevidencias_descripcion").val('Ubicación de puntos de ' + parametro_nombre);
                $("#recsensorialevidencias_descripcion").attr('disabled', false);
            }
        } else {
            $("#recsensorialevidencias_recsensorialarea_id").val('');
            $("#recsensorialevidencias_recsensorialarea_id").attr('disabled', true);
            $("#recsensorialevidencias_descripcion").val('');
            $("#recsensorialevidencias_descripcion").attr('disabled', true);
        }
    }


    $("#boton_guardar_evidencia_fotos").click(function() {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            // Valida envio de datos
            swal({
                title: "¡Confirme guardar " + $("#recsensorialevidencias_tipo option:selected").text() + "!",
                text: "",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Guardar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // enviar datos
                    $('#form_evidencia_fotos').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: '/recsensorial',
                        data: {
                            opcion: 4, //FOTOS
                            recsensorial_id: recsensorial_id,
                            parametro_id: parametro_id,
                            parametro_nombre: parametro_nombre,
                            foto_base64: foto_resizebase64,
                        },
                        resetForm: false,
                        success: function(dato) {
                            // Actualiza galeria de fotos
                            consulta_evidencia_fotos(recsensorial_id, parametro_id);

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
                            $('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
                            $('#boton_guardar_evidencia_fotos').attr('disabled', false);

                            // cerrar modal
                            $('#modal_evidencia_fotos').modal('hide');
                        },
                        beforeSend: function() {
                            $('#boton_guardar_evidencia_fotos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                            $('#boton_guardar_evidencia_fotos').attr('disabled', true);
                        },
                        error: function(dato) {
                            // actualiza boton
                            $('#boton_guardar_evidencia_fotos').html('Guardar <i class="fa fa-save"></i>');
                            $('#boton_guardar_evidencia_fotos').attr('disabled', false);

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
            return false;
        }
    });


    function consulta_evidencia_fotos(recsensorial_id, parametro_id) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/recsensorialevidenciagaleria/" + recsensorial_id + "/" + parametro_id,
            data: {},
            cache: false,
            success: function(dato) {
                // Vaciar contenido
                $('.galeria').html('');

                // validar si trae datos
                if (dato.galeria) {
                    // GALERIA DE FOTOS
                    $(".galeria").html(dato.galeria);
                } else {
                    $('.galeria').html('<div class="col-12" style="text-align: center;">No hay fotos que mostrar</div>');
                }

                // Inicializar tooltip
                $('[data-toggle="tooltip"]').tooltip();
            },
            beforeSend: function() {
                $('.galeria').html('<div class="col-12" style="text-align: center;"><i class="fa fa-spin fa-spinner fa-5x"></i></div>');
            },
            error: function(dato) {
                $('.galeria').html('<div class="col-12" style="text-align: center;">Error al cargar las fotos</div>');
                return false;
            }
        }); //Fin ajax
    }


    function foto_descargar(foto_id) {
        window.open("/recsensorialevidenciafotomostrar/" + foto_id + "/1");
    }


    function foto_eliminar(foto_id, tipo_nombre) {
        // Valida envio de datos
        swal({
            title: "¡Confirme eliminar " + tipo_nombre + "!",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                // Enviar datos
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "/recsensorialevidenciafotoeliminar/" + foto_id,
                    data: {},
                    cache: false,
                    success: function(dato) {
                        // Actualiza galeria de fotos
                        consulta_evidencia_fotos(recsensorial_id, parametro_id);

                        //Cerrar imagen popo
                        // $(".mfp-figure").click();

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
                        // mensaje
                        swal({
                            title: "Error",
                            text: "" + dato.msj,
                            type: "error", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 3000,
                            showConfirmButton: false
                        });
                        return false;
                    }
                }); //Fin ajax
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
        return false;
    }
</script>

<!-- Magnific popup JavaScript -->
<script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
<script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->