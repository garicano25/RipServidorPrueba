@extends('template/maestra')
@section('contenido')



<style type="text/css" media="screen">
    table th {
        font-size: 12px !important;
        color: #777777 !important;
        font-weight: 600 !important;
    }

    table td {
        font-size: 12px !important;
    }

    table td i {
        font-size: 20px !important;
        margin: -1px 0px 0px 0px;
    }

    table td b {
        font-weight: 600 !important;
    }

    form label {
        color: #999999;
    }

    table td i {
        font-size: 20px !important;
        margin: -1px 0px 0px 0px;
    }

    h4 b {
        color: #0BACDB;
    }

    /*Tabla asignar proveedores*/

    .round {
        width: 42px;
        height: 42px;
        padding: 0px !important;
    }

    .round i {
        margin: 0px !important;
        font-size: 16px !important;
    }
</style>


<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card mt-4">
            <!-- Menu tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" role="tab" id="tab_tabla_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de Ejecuciones</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_info_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Aplicación de NOM-035-STPS-2018</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_3" role="tab" id="tab_evidencias_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Evidencia fotográfica</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-cogs"></i> Ejecuciones </h2>
                        </ol>
                        <table class="table table-hover stylish-table" id="tabla_ejecucion" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Folio Proyecto</th>
                                    <th width="600">Intalación / Dirrección</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th width="60">Mostrar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_2" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body m-t-10" style="padding: 6px 10px">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-suitcase"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_proyecto">FOLIO PROYECTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Folio</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_reconocimiento">FOLIO RECONOCIMIENTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Reconocimiento</small>
                                                    </td>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-eercast"></i></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ============= STEPS ============= -->
                        <div style="min-width: 700px; width: 100% ; margin: 0px auto;">

                            <div class="row">
                                <div class="col-12">
                                    <ol class="breadcrumb m-b-10 p-t-10">
                                        <h2 style="color: #ffffff">
                                            <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad online
                                        </h2>
                                        <div style="display: flex; justify-content: flex-end;">
                                            <button type="submit" class="btn btn-light botonguardar_modalidad_online" id="botonguardar_modalidad_online" style="float: left;">
                                                Guardar cambios <i class="fa fa-save"></i>
                                            </button>
                                            <button type="submit" class="btn btn-warning botonenviar_todos_correos d-none" id="botonenviar_todos_correos" style="margin-right: 10px;">
                                                Enviar todos los correos <i class="fa fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </ol>
                                    <div class="card">
                                        <div class="card-body m-t-10" style="padding: 10px;">
                                            <h3 style="color: #9C9C9C; text-align: center;">
                                                <i class="fa fa-calendar-o" aria-hidden="true"></i> Ajustar plazo de tiempo para presentar las GUIAS online
                                            </h3>
                                            <form enctype="multipart/form-data" method="POST" name="form_actualizarFechasOnline" id="form_actualizarFechasOnline">
                                                <input type="hidden" name="_method" value="PUT">
                                                {!! csrf_field() !!}
                                                <div class="form-group m-t-30 m-r-30 m-l-30" style="display: flex; align-items: center; gap: 30px; justify-content: center;">
                                                    <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 380px;">
                                                        <label for="fechaInicio" style="margin-bottom: 5px;">Fecha de inicio:</label>
                                                        <div style="display: flex; width: 100%;">
                                                            <input type="text" id="fechaInicio" name="fechaInicio" class="form-control mydatepicker" placeholder="aaaa-mm-dd"
                                                                style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 280px;" required>
                                                            <span class="input-group-addon" style="display: flex; align-items: center; padding: 0 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-left: none; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                                                                <i class="icon-calender"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 380px;">
                                                        <label for="fechaFin" style="margin-bottom: 5px;">Fecha de finalización:</label>
                                                        <div style="display: flex; width: 100%;">
                                                            <input type="text" id="fechaFin" name="fechaFin" class="form-control mydatepicker" placeholder="aaaa-mm-dd"
                                                                style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 280px;" required>
                                                            <span class="input-group-addon" style="display: flex; align-items: center; padding: 0 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-left: none; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                                                                <i class="icon-calender"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-success botonactualizar_fechas_online" id="botonactualizar_fechas_online" style="margin-right: 10px; align-self: flex-end; margin-bottom: 5px;">
                                                        Actualizar fechas de TODOS los trabajadores <i class="fa fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--form panels-->
                            <div class="col-12" style="text-align: center;">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_trabajadores_online">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de inicio</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de fin</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Correo</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado del correo</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Enviar link del cuestionario</th>
                                                <th style="display: none;">TRABAJADOR_ID</th>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12" style="text-align: center;">
                            <hr>
                            <ol class="breadcrumb m-b-10 green-breadcrumb">
                                <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad presencial</h2>
                                <div style="display: flex; justify-content: flex-end;">
                                    <button type="submit" class="btn btn-light botonguardar_modalidad_presencial" id="botonguardar_modalidad_presencial" style="margin-right: 10px;">
                                        Guardar cambios <i class="fa fa-save"></i>
                                    </button>
                                    <button type="submit" class="btn btn-light botocargar_respuestas_trabajadores" id="botocargar_respuestas_trabajadores" style="margin-right: 10px;">
                                        Cargar respuestas de todos los trabajadores <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </ol>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body m-t-10">
                                            <h3 style="color: #9C9C9C; text-align: center;">
                                                <i class="fa fa-calendar-o" aria-hidden="true"></i> Definir fecha de aplicación presencial
                                            </h3>
                                            <form enctype="multipart/form-data" method="post" name="form_actualizarFechaPresencial" id="form_actualizarFechaPresencial">
                                                <div class="form-group m-t-20 m-r-30 m-l-30" style="display: flex; align-items: center; gap: 5px; justify-content: center;">
                                                    <div style="display: flex; flex-direction: column; align-items: flex-start; max-width: 380px;">
                                                        <label for="fechaInicio" style="margin-bottom: 5px;">Fecha de aplicación:</label>
                                                        <div style="display: flex; width: 100%;">
                                                            <input type="text" id="fechaAplicacion" name="fechaAplicacion" class="form-control mydatepicker" placeholder="aaaa-mm-dd"
                                                                style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 280px;" required>
                                                            <span class="input-group-addon" style="display: flex; align-items: center; padding: 0 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-left: none; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                                                                <i class="icon-calender"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-success botonactualizar_fechaaplicacion" id="botonactualizar_fechaaplicacion" style="margin-right: 10px; align-self: flex-end; margin-bottom: 5px;">
                                                        Actualizar fechas de aplicación para TODOS los trabajadores <i class="fa fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_trabajadores_presencial">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de aplicación</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_3" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 6px 10px">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-suitcase"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_proyecto">FOLIO PROYECTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Folio</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_reconocimiento">FOLIO RECONOCIMIENTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Reconocimiento</small>
                                                    </td>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-eercast"></i></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ============= STEPS ============= -->
                        <div style="min-width: 700px; width: 100% ; margin: 0px auto;">

                            <!--form panels-->
                            <div class="row">
                                <div class="col-12">
                                    <ol class="breadcrumb m-b-10">
                                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-info-circle" aria-hidden="true"></i> Evidencia fotogáfica</h2>
                                    </ol>

                                    <!-- DIV GENERAL -->
                                    <div class="row  mt-4">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-body bg-secondary" style="padding: 20px 20px 0px 20px;">
                                                    <h4 class="text-white card-title" style="line-height: 14px!important; margin: 0px; padding: 0px;">Informes y evidencias</h4>
                                                    <small style="color: #DDDDDD; font-size: 12px; margin: 0px; padding: 0px;">Información general</small>
                                                </div>
                                                <div class="card-body" style="border: 0px #f00 solid; min-height: 856px;">
                                                    <div class="vtabs" style="width: 100%!important;">
                                                        <ul class="nav nav-tabs tabs-vertical" role="tablist" style="border-right: none;" id="lista_menu_parametros_evidencia">
                                                            <li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">
                                                                <span class="nav-link menulista_evidencia active" style="margin: 2px 0px; padding: 8px; cursor: pointer;">
                                                                    • <b style="font-weight: bold; color: #000000;">Evidencias fotográficas</b>
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card">
                                                <div class="card-body bg-info">
                                                    <h4 class="text-white card-title" style="line-height: 18px!important; margin: 0px; padding: 0px;" id="evidencia_agente_titulo">Evidencias fotográficas</h4>
                                                </div>
                                                <!-- Tab panes -->
                                                <div id="seccion_proyectoevidencias">
                                                    <ul class="nav nav-tabs customtab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" id="tabmenu_evidencia_2" href="#tab_evidencia_2" role="tab">Fotos</a>
                                                        </li>
                                                    </ul>
                                                    <div id="image-popups">
                                                        <div class="tab-pane p-20" id="tab_evidencia_2" role="tabpanel">

                                                            <ol class="breadcrumb m-b-10">
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar fotos" id="boton_nuevo_fotosevidencia">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Foto (s)
                                                                </button>
                                                            </ol>

                                                            <style type="text/css">
                                                                #image-popups .foto_galeria:hover i {
                                                                    opacity: 1 !important;
                                                                    cursor: pointer;
                                                                }
                                                            </style>
                                                            <div class="row" id="evidencia_galeria_fotos"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!-- End Tab panes -->
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Inicio modales -->
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
                            <input type="hidden" class="form-control" id="evidenciafotos_id" name="evidenciafotos_id">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <div class="form-group">
                                <label>Nombre del trabajador*</label>
                                <select class="custom-select form-control" id="trabajador_nombre_foto" name="trabajador_nombre_foto">
                                    <option value="" disabled selected>Seleccione un trabajador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <div class="form-group">
                                <label>Fotos (máximo 2) *</label>
                                <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif" placeholder="Maximo 20 fotos" id="inputevidenciafotosquimicos" name="inputevidenciafotosquimicos[]" onchange="valida_totalfotos_quimicos(this);" required>
                            </div>
                        </div>
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <p style="text-align: justify;"><b style="color: #555555;">Nota:</b> Solo se pueden subir máximo 2 fotos por cada trabajador</p>
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
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_fotos">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-FOTOS -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- MODAL-EXCEL-RESPUESTAS -->
<!-- ============================================================== -->
<div id="modal_cargarRespuestasTrabajador" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_cargaRespuestasTrabajador" id="form_cargaRespuestasTrabajador">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Respuestas del trabajador</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="RECPSICO_ID_TRABAJADOR" name="RECPSICO_ID" value="0">
                            <input type="number" class="form-control" id="RECPSICOTRABAJADOR_ID" name="RECPSICOTRABAJADOR_ID" style="visibility: hidden;">
                            <div class="col-12" id="cargarRespuestasTrabajador_excel">
                            </div>
                            <div class="form-group">
                                <label> Cargar excel con respuestas del trabajador *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_trabajador">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept=".xls,.xlsx" name="excelRespuestasTrabajador" id="excelRespuestasTrabajador" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                            <div class="row mx-2 mb-2" id="alertaVerificacion" style="display:none">
                                <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el excel contenga las respuestas correspondientes al trabajador seleccionado</p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_cargarRespuestasTrabajadores">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EXCEL-RESPUESTAS -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- MODAL-EXCEL-RESPUESTAS-TODOS -->
<!-- ============================================================== -->
<div id="modal_cargarRespuestasTrabajadores" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_cargaRespuestasTrabajadores" id="form_cargaRespuestasTrabajadores">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Respuestas de los trabajadores</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="RECPSICO_ID_TRABAJADOR" name="RECPSICO_ID" value="0">
                            <div class="col-12" id="cargaRespuestasTrabajadores_excel">
                            </div>
                            <div class="form-group">
                                <label> Cargar excel con respuestas de los trabajadores *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_trabajadores">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept=".xls,.xlsx" name="excelRespuestasTrabajador" id="excelRespuestasTrabajadores" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                            <div class="row mx-2 mb-2" id="alertaVerificacion" style="display:none">
                                <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el excel contenga las respuestas correspondientes por cada trabajador</p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_cargarRespuestasTrabajadores">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EXCEL-RESPUESTAS-TODOS -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Fin modales -->
<!-- ============================================================== -->


@endsection