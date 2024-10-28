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
                        <span class="hidden-xs-down">Información general</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-cogs"></i> Ejecuciones </h2>
                        </ol>
                        <table class="table table-hover stylish-table" id="tabla_ejecucionHI" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Folio Proyecto</th>
                                    <th width="600">Intalación / Dirrección</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Reconocimiento vinculado</th>
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
                                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-info-circle" aria-hidden="true"></i> Información general</h2>
                                    </ol>

                                    <!-- DIV GENERAL -->
                                    <div class="row  mt-4">
                                        <div class="col-4">
                                            <div class="card">
                                                <div class="card-body bg-secondary" style="padding: 20px 20px 0px 20px;">
                                                    <h4 class="text-white card-title" style="line-height: 14px!important; margin: 0px; padding: 0px;">Informes y evidencias</h4>
                                                    <small style="color: #DDDDDD; font-size: 12px; margin: 0px; padding: 0px;">Información general / Agentes / Factores / Servicios</small>
                                                </div>
                                                <div class="card-body" style="border: 0px #f00 solid; min-height: 856px;">
                                                    <div class="vtabs" style="width: 100%!important;">
                                                        <ul class="nav nav-tabs tabs-vertical" role="tablist" style="border-right: none;" id="lista_menu_parametros_evidencia">
                                                            <li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">
                                                                <a class="nav-link menulista_evidencia" href="#">
                                                                    Nombre parametro 1
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card">
                                                <div class="card-body bg-info">
                                                    <h4 class="text-white card-title" style="line-height: 18px!important; margin: 0px; padding: 0px;" id="evidencia_agente_titulo">Nombre parametro</h4>
                                                </div>
                                                <!-- Tab panes -->
                                                <div id="seccion_proyectoevidencias">
                                                    <ul class="nav nav-tabs customtab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" id="tabmenu_evidencia_1" href="#tab_evidencia_1" role="tab">Documentos</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" id="tabmenu_evidencia_2" href="#tab_evidencia_2" role="tab">Fotos</a>
                                                        </li>
                                                        <li class="nav-item" id="planos_ejecucion">
                                                            <a class="nav-link" data-toggle="tab" id="tabmenu_evidencia_3" href="#tab_evidencia_3" role="tab">Planos</a>
                                                        </li>
                                                    </ul>
                                                    <div id="image-popups">
                                                        <div class="tab-content" style="height: 800px; max-height: 800px!important; overflow-x: none; overflow-y: auto;">
                                                            <div class="tab-pane p-20 active" id="tab_evidencia_1" role="tabpanel">
                                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto','Coordinador']))
                                                                <ol class="breadcrumb m-b-10">
                                                                    <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Nuevo documento" id="boton_nuevo_documentoevidencia">
                                                                        <span class="btn-label"><i class="fa fa-plus"></i></span>Documento
                                                                    </button>
                                                                </ol>
                                                                @endif
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_evidenciadocumentos">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 60px!important;">No</th>
                                                                                <th>Nombre</th>
                                                                                <th style="width: 150px!important;">Fecha</th>
                                                                                <th style="width: 70px!important;">Descargar</th>
                                                                                <th style="width: 70px!important;">Editar</th>
                                                                                <th style="width: 70px!important;">Eliminar</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane p-20" id="tab_evidencia_2" role="tabpanel">
                                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                                <ol class="breadcrumb m-b-10">
                                                                    <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar fotos" id="boton_nuevo_fotosevidencia">
                                                                        <span class="btn-label"><i class="fa fa-plus"></i></span>Foto (s)
                                                                    </button>
                                                                </ol>
                                                                @endif
                                                                <style type="text/css">
                                                                    #image-popups .foto_galeria:hover i {
                                                                        opacity: 1 !important;
                                                                        cursor: pointer;
                                                                    }
                                                                </style>
                                                                <div class="row" id="evidencia_galeria_fotos"></div>
                                                            </div>
                                                            <div class="tab-pane p-20" id="tab_evidencia_3" role="tabpanel">
                                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                                <ol class="breadcrumb m-b-10">
                                                                    <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar fotos" id="boton_nuevo_planosevidencia">
                                                                        <span class="btn-label"><i class="fa fa-plus"></i></span>Planos
                                                                    </button>
                                                                </ol>
                                                                @endif
                                                                <style type="text/css">
                                                                    #image-popups .plano_galeria:hover i {
                                                                        opacity: 1 !important;
                                                                        cursor: pointer;
                                                                    }
                                                                </style>
                                                                <div class="row" id="evidencia_galeria_planos"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /Tab panes -->
                                                <div class="row" id="seccion_proyectopuntosreales" style="min-height: 856px!important; padding: 20px; display: none;">
                                                    <div class="col-12">
                                                        <style type="text/css">
                                                            #tabla_proyectoevidencia_puntosreales th {
                                                                height: 10px;
                                                                padding: 1px 14px 10px 14px;
                                                                line-height: 12px;
                                                                vertical-align: middle;
                                                            }
                                                        </style>
                                                        <form enctype="multipart/form-data" method="post" name="form_puntosreales" id="form_puntosreales">
                                                            {!! csrf_field() !!}
                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_proyectoevidencia_puntosreales">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="220" style="border-bottom: 2px #DDDDDD solid;">Agente</th>
                                                                        <th width="80" style="border-bottom: 2px #DDDDDD solid; text-align: center;">Puntos<br>asignados</th>
                                                                        <th width="100" style="border-bottom: 2px #DDDDDD solid; text-align: center;">Puntos<br>reales</th>
                                                                        <th style="border-bottom: 2px #DDDDDD solid;">Observación (opcional)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                            <div class="row">
                                                                <div class="col-12" style="padding: 20px; text-align: right;">
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                                    <button type="button" class="btn btn-success waves-effect waves-light" style="margin-right: 20px;" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_puntosreales">
                                                                        <span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición
                                                                    </button>
                                                                    @endif
                                                                    <button type="button" class="btn waves-effect waves-light btn-info" style="display: none;" id="boton_imprimir_proyectopuntosreales">
                                                                        <i class="fa fa-print"></i> Imprimir reporte
                                                                    </button>
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'CoordinadorHI']))
                                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" style="margin-left: 20px;" id="boton_guardar_puntosreales">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /Tab panes -->
                                                <div class="row" id="seccion_bitacoramuestreo" style="min-height: 856px!important; padding: 20px; display: none;">
                                                    <div class="col-12">
                                                        <style type="text/css">
                                                            #tabla_bitacora th {
                                                                background: #F9F9F9;
                                                                /*border: 1px #E5E5E5 solid!important;*/
                                                                padding: 2px !important;
                                                                text-align: center;
                                                                vertical-align: middle !important;
                                                                border-bottom: 2px #DDDDDD solid;
                                                            }

                                                            #tabla_bitacora td {
                                                                padding: 4px !important;
                                                                text-align: center;
                                                                vertical-align: middle !important;
                                                            }

                                                            #tabla_bitacora td span {
                                                                font-size: 16px;
                                                                color: #000000;
                                                            }
                                                        </style>
                                                        @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'ApoyoTecnico']))
                                                        <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                            <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar día" id="boton_nuevo_bitacoramuestreo">
                                                                <span class="btn-label"><i class="fa fa-plus"></i></span>Día de muestreo
                                                            </button>
                                                        </ol>
                                                        @endif
                                                        <table class="table table-hover table-bordered" width="100%" id="tabla_bitacora">
                                                            <thead>
                                                                <tr>
                                                                    <th width="60">Día</th>
                                                                    <th width="110">Fecha</th>
                                                                    <th width="200">Personal / Parametro</th>
                                                                    <th width="140">Avance<br>(Ptos / Pers. / Categ.)</th>
                                                                    <th width="">Observación</th>
                                                                    <th width="50">Editar</th>
                                                                    <th width="50">Eliminar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7">datos</td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="3" style="background: #DDDDDD; color: #000000; font-weight: bold;">Total</td>
                                                                    <td><span id="bitacora_totalpuntos">0</span></td>
                                                                    <td colspan="3"></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <div class="col-12" style="padding: 20px; text-align: right;">
                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                        <button type="button" class="btn btn-success waves-effect waves-light" style="margin-right: 20px;" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_bitacoramuestreo">
                                                            <span class="btn-label"><i class="fa fa-unlock"></i></span> Bitácora de muestreo desbloqueado para edición
                                                        </button>
                                                        <button type="button" class="btn waves-effect waves-light btn-info" id="boton_imprimir_bitacoramuestreo">
                                                            <i class="fa fa-print"></i> Imprimir reporte
                                                        </button>
                                                        @endif
                                                    </div>
                                                    <div class="col-12" style="padding: 20px; text-align: left;">
                                                        <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                            Tabla resumen (total de puntos por agente)
                                                        </ol>
                                                        <style type="text/css">
                                                            #tabla_bitacora_resumen th {
                                                                background: #F9F9F9;
                                                                /*border: 1px #E5E5E5 solid!important;*/
                                                                padding: 2px !important;
                                                                text-align: left;
                                                                vertical-align: middle !important;
                                                                border-bottom: 2px #DDDDDD solid;
                                                            }

                                                            #tabla_bitacora_resumen td {
                                                                padding: 4px !important;
                                                                text-align: left;
                                                                vertical-align: middle !important;
                                                            }

                                                            #tabla_bitacora_resumen td span {
                                                                font-size: 16px;
                                                                color: #000000;
                                                            }
                                                        </style>
                                                        <table class="table table-hover table-bordered" width="100%" id="tabla_bitacora_resumen">
                                                            <thead>
                                                                <tr>
                                                                    <th width="">Párametro</th>
                                                                    <th width="150">Total puntos</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2">No hay datos que mostrar</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
<!-- VISOR-MODAL -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_visor>.modal-dialog {
        min-width: 900px !important;
    }

    iframe {
        width: 100%;
        height: 600px;
        border: 0px #fff solid;
    }
</style>
<div id="modal_visor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Documento</h4>
            </div>
            <div class="modal-body" style="background: #555555;">
                <div class="row">
                    <div class="col-12">
                        <iframe src="/assets/images/cargando.gif" name="visor_documento" id="visor_documento" style=""></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="modalvisor_boton_cerrar">Cerrar</button>
                {{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-DOCUMENTOS -->
<!-- ============================================================== -->
<div id="modal_evidencia_documento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form method="post" enctype="multipart/form-data" name="form_evidencia_documento" id="form_evidencia_documento">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documento evidencia</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" id="evidenciadocumento_id" name="evidenciadocumento_id">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" id="proyectoevidenciadocumento_nombre" name="proyectoevidenciadocumento_nombre" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_evidenciadocumento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        {{-- <input type="file" accept="application/pdf" id="evidenciadocumento" name="evidenciadocumento" required> --}}
                                        <input type="file" id="evidenciadocumento" name="evidenciadocumento" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-DOCUMENTOS -->
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
                        <div class="col-7 divevidencia_seccion_fotosfisicos">
                            <div class="form-group">
                                <label> Foto (evidencia) *</label>
                                <style type="text/css" media="screen">
                                    .dropify-wrapper {
                                        height: 296px !important;
                                        /*tamaño estatico del campo foto*/
                                    }
                                </style>
                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="inputevidenciafotofisicos" name="inputevidenciafotofisicos" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" onchange="redimencionar_fotoevidencia();" required>
                            </div>
                        </div>
                        <div class="col-5 divevidencia_seccion_fotosfisicos">
                            <div class="row">
                                <div class="col-12" id="fotosfisicos_campo_punto">
                                    <div class="form-group">
                                        <label>Numero del punto (opcional) </label>
                                        <input type="number" class="form-control" id="proyectoevidenciafoto_nopunto" name="proyectoevidenciafoto_nopunto">
                                    </div>
                                </div>
                                <div class="col-12" id="fotosfisicos_campo_partida" style="display: none;">
                                    <div class="form-group">
                                        <label>Tipo de evaluación *</label>
                                        <select class="custom-select form-control" id="catreportequimicospartidas_id" name="catreportequimicospartidas_id" required onchange="evidenciafoto_carpetanombre(this);">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Descripción de la foto *</label>
                                        <textarea class="form-control" rows="10" id="proyectoevidenciafoto_descripcion" name="proyectoevidenciafoto_descripcion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <div class="form-group">
                                <label>Nombre de la carpeta *</label>
                                <input type="text" class="form-control" id="proyectoevidenciafoto_carpeta" name="proyectoevidenciafoto_carpeta" placeholder="Ejem: Fotos de evidencia" required>
                            </div>
                        </div>
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <div class="form-group">
                                <label>Fotos (maximo 20) *</label>
                                <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif" placeholder="Maximo 20 fotos" id="inputevidenciafotosquimicos" name="inputevidenciafotosquimicos[]" onchange="valida_totalfotos_quimicos(this);" required>
                            </div>
                        </div>
                        <div class="col-12 divevidencia_seccion_fotoscarpeta">
                            <p style="text-align: justify;"><b style="color: #555555;">Nota:</b> Solo se pueden subir máximo 20 fotos por cada acción, si desea subir más fotos seleccione la opción "agregar mas fotos a esta carpeta" despues de haber guardado las primeras 20.</p>
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_fotos">
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
<!-- MODAL NOMBRE CARPETA -->
<!-- ============================================================== -->
<div id="modal_nombrecarpeta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_nombrecarpeta" id="form_nombrecarpeta">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cambiar nombre a la carpeta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la carpeta *</label>
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" id="proyectoevidencia_nombrecarpetatipo" name="proyectoevidencia_nombrecarpetatipo">
                                <input type="hidden" class="form-control" id="proyectoevidencia_nombrecarpetaoriginal" name="proyectoevidencia_nombrecarpetaoriginal">
                                <input type="text" class="form-control" id="proyectoevidencia_nombrecarpetarenombrar" name="proyectoevidencia_nombrecarpetarenombrar" required>
                            </div>
                        </div>
                        <div class="col-12" id="nombrecarpeta_campo_partida">
                            <div class="form-group">
                                <label>Tipo de evaluación *</label>
                                <select class="custom-select form-control" id="nombrecarpetacatreportequimicospartidas_id" name="catreportequimicospartidas_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_nombrecarpeta">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL NOMBRE CARPETA -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-PLANOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_evidencia_planos .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_evidencia_planos .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_evidencia_planos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 900px!important;">
        <form method="post" enctype="multipart/form-data" name="form_evidencia_planos" id="form_evidencia_planos">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Planos evidencia</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="evidenciaplano_id" name="evidenciaplano_id">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="planos_campo_carpeta">
                            <div class="form-group">
                                <label>Nombre de la carpeta *</label>
                                <input type="text" class="form-control" id="proyectoevidenciaplano_carpeta" name="proyectoevidenciaplano_carpeta" placeholder="Ejem: Planos de las fuentes generadoras de la estación de compresión..." required>
                            </div>
                        </div>
                        <div class="col-12" id="planos_campo_partida" style="display: none;">
                            <div class="form-group">
                                <label>Tipo de evaluación *</label>
                                <select class="custom-select form-control" id="planoscatreportequimicospartidas_id" name="catreportequimicospartidas_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Planos (maximo 20) *</label>
                                <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif" placeholder="Maximo 20 planos" id="inputevidenciaplanos" name="inputevidenciaplanos[]" onchange="valida_totalfotos_plano(this);" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <p style="text-align: justify;"><b style="color: #555555;">Nota:</b> Solo se pueden subir máximo 20 planos por cada acción, si desea subir más planos seleccione la opción "agregar mas planos a esta carpeta" despues de haber guardado los primeros 20.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="display: none;" id="mensaje_cargando_planos">
                            <p class="text-info" style="text-align: center; margin: 0px; padding: 0px;"><i class="fa fa-spin fa-spinner"></i> Cargando planos, espere un momento por favor...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_planos">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-PLANOS -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-BITACORA DE MUESTREO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_evidencia_bitacoramuestreo .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_evidencia_bitacoramuestreo .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_evidencia_bitacoramuestreo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 1100px!important;">
        <form method="post" enctype="multipart/form-data" name="form_evidencia_bitacoramuestreo" id="form_evidencia_bitacoramuestreo">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Bitácora de muestreo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="bitacoramuestreo_id" name="bitacoramuestreo_id" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Fecha del día</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control mydatepicker" id="proyectoevidenciabitacora_fecha" name="proyectoevidenciabitacora_fecha" placeholder="aaaa-mm-dd" required>
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Responsable</label>
                                        <input type="hidden" class="form-control" id="proyectoevidenciabitacora_usuario_id" name="usuario_id" value="0">
                                        <input type="text" class="form-control" id="proyectoevidenciabitacora_usuario_nombre" name="usuario_nombre" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Fecha y hora de carga</label>
                                        <input type="text" class="form-control" id="proyectoevidenciabitacora_fechacarga" name="proyectoevidenciabitacora_fechacarga" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Observación</label>
                                        <textarea class="form-control" rows="5" id="proyectoevidenciabitacora_observacion" name="proyectoevidenciabitacora_observacion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Foto(s) de evidencia (Opcional)</label>
                                        <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif" id="proyectoevidenciabitacorafotos" name="proyectoevidenciabitacorafotos" onchange="bitacorafotos_redimencionar(this);">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card" style="margin: 20px 0px 0px 0px;">
                                        <div class="card-body" style="padding: 8px; height: 116px; max-height: 116px; overflow-x: hidden; overflow-y: auto;">
                                            <style type="text/css">
                                                #proyectoevidenciabitacora_fotosgaleria span {
                                                    cursor: pointer;
                                                }

                                                #proyectoevidenciabitacora_fotosgaleria span i {
                                                    font-size: 26px;
                                                    text-shadow: 2px 2px 4px #000000;
                                                    position: absolute;
                                                    opacity: 0;
                                                    margin-top: 12px;
                                                }

                                                #proyectoevidenciabitacora_fotosgaleria span:hover i {
                                                    opacity: 1 !important;
                                                    cursor: pointer;
                                                }
                                            </style>
                                            <div class="row" id="proyectoevidenciabitacora_fotosgaleria">
                                                <div class="col-12">
                                                    Fotos
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px; text-align: center;">
                                <button type="button" class="btn btn-default waves-effect waves-light botonnuevo_moduloproyecto" style="height: 26px; padding: 3px 8px; float: left;" data-toggle="tooltip" title="Agregar signatario" id="botonnuevo_bitacoramuestreo_signatario">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Signatario
                                </button>
                                Personal involucrado
                                <button type="button" class="btn btn-default waves-effect waves-light botonnuevo_moduloproyecto" style="height: 26px; padding: 3px 8px; float: right;" data-toggle="tooltip" title="Agregar adicional" id="botonnuevo_bitacoramuestreo_adicional">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Adicional
                                </button>
                            </ol>
                            <style type="text/css">
                                #tabla_bitacoramuestreo_personal_1 th {
                                    background: #F9F9F9;
                                    border: 1px #E5E5E5 solid !important;
                                    padding: 2px !important;
                                    text-align: center;
                                    vertical-align: middle !important;
                                }

                                #tabla_bitacoramuestreo_personal td {
                                    border-bottom: 1px #E5E5E5 solid !important;
                                    padding: 4px !important;
                                    text-align: center;
                                    vertical-align: middle !important;
                                }
                            </style>
                            <table class="table table-hover" width="100%" style="margin-bottom: 0px;" id="tabla_bitacoramuestreo_personal_1">
                                <thead>
                                    <tr>
                                        <th width="251">Signatario / Personal adicional</th>
                                        <th width="230">Parametro</th>
                                        <th width="150">Avance<br>(Ptos / Pers. / Categ.)</th>
                                        <th width="">Observación</th>
                                        <th width="70">Eliminar</th>
                                    </tr>
                                </thead>
                            </table>
                            <div style="height: 200px; max-height: 200px; overflow-x: none; overflow-y: auto; border: 1px #DDDDDD solid;" id="divtabla_bitacoramuestreo_personal">
                                <table class="table table-hover" width="100%" id="tabla_bitacoramuestreo_personal">
                                    <tbody>
                                        <tr>
                                            <td colspan="6">data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_bitacoramuestreo">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-BITACORA DE MUESTREO -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- mMODAL - FOTO - BITACORA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_bitacora_foto>.modal-dialog {
        width: 800px !important;
    }
</style>
<div id="modal_bitacora_foto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="nombre_documento_visor"></h4>
            </div>
            <div class="modal-body" style="background: #555555;">
                <div class="row">
                    <div class="col-12 text-center">
                        <img class="d-block" id="bitacora_visor" src="/assets/images/cargando.gif" style="max-height: 500px; max-width: 767px; margin: 0px auto;" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="modal_bitacorafoto_cerrar">Cerrar</button>
                {{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- mMODAL - FOTO - BITACORA -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- Fin modales -->
<!-- ============================================================== -->


@endsection