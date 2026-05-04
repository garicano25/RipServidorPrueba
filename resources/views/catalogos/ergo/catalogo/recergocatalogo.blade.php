@extends('template/maestra')
@section('contenido')
{{-- ========================================================================= --}}


<div class="row page-titles">
    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Proveedores</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">Proveedores</li>
        </ol>
    </div> --}}
    <div class="col-12 align-self-center">
        <div class="d-flex justify-content-end">
            <div class="">
                {{-- <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10">
                    <i class="ti-settings text-white"></i>
                </button> --}}
            </div>
        </div>
    </div>
</div>



<style type="text/css">
    table th {
        font-size: 12px !important;
        color: #777777 !important;
        font-weight: 600 !important;
    }

    table td {
        font-size: 12px !important;
    }

    table td b {
        font-weight: 600 !important;
    }

    .tabla-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .tabla-epp {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        table-layout: fixed;
    }

    .tabla-epp th,
    .tabla-epp td {
        border: 2px solid black;
        padding: 10px;
        font-size: 14px;
    }

    .entrada-epp {
        width: 100%;
        max-width: 80px;
        text-align: center;
        border: 1px solid #999;
        border-radius: 8px;
        padding: 4px;
    }

    .tabla-epp tr:first-child th:first-child,
    .tabla-epp tr:first-child td:first-child {
        border-top-left-radius: 20px;
    }

    .tabla-epp tr:first-child th:last-child,
    .tabla-epp tr:first-child td:last-child {
        border-top-right-radius: 20px;
    }

    .tabla-epp tr:last-child th:first-child,
    .tabla-epp tr:last-child td:first-child {
        border-bottom-left-radius: 20px;
    }

    .tabla-epp tr:last-child th:last-child,
    .tabla-epp tr:last-child td:last-child {
        border-bottom-right-radius: 20px;
    }

    .titulo-columna {
        font-weight: bold;
        text-align: center;
        white-space: normal;
    }
</style>

<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body bg-secondary">
                <h4 class="text-white card-title">Catálogos</h4>
                <h6 class="card-subtitle text-white">Ergonomía</h6>
            </div>
            <div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
                <div class="message-box contact-box">
                    <div class="table-responsive m-t-20">
                        <table class="table table-hover stylish-table" id="tabla_lista_catalogos" width="100%">
                            <thead>
                                <tr>
                                    <th>Catálogo</th>
                                    <th width="120">Mostrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr_1" class="active">
                                    <td>Régimen Contractual</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(1);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_2">
                                    <td>Jornada</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(2);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_2"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_3">
                                    <td>Turno</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(3);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_3"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_4">
                                    <td>Introducción </td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(4);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_4"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_5">
                                    <td>Definiciones</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(5);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_5"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_6">
                                    <td>Recomendaciones</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(6);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_6"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_7">
                                    <td>Conclusión</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(7);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_7"></i>
                                        </a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-9">
        <div class="card">
            <div class="card-body bg-info">
                <h4 class="text-white card-title" id="titulo_tabla">Nombre catálogo</h4>
                <h6 class="card-subtitle text-white">Lista de registros</h6>
            </div>
            <div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
                <div class="message-box contact-box">
                    <h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">
                        <button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect"
                            data-toggle="tooltip" title="Nuevo registro" id="boton_nuevo_registro"><i
                                class="fa fa-plus"></i></button>
                    </h2>
                    <div class="table-responsive m-t-20" id="div_datatable">
                        <table class="table table-hover stylish-table" id="tabla_lista_registros" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th style="width: 90px!important;">Editar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- ============================================================== -->
<!-- MODAL Régimen Contractual-->
<!-- ============================================================== -->

<div id="modal_regimen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_regimen" id="form_regimen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Régimen Contractual</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_REGIMEN_CONTRACTUAL" name="ID_REGIMEN_CONTRACTUAL" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="1">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Régimen Contractual *</label>
                                <input type="text" class="form-control" id="NOMBRE_REGIMEN_CONTRACTUAL" name="NOMBRE_REGIMEN_CONTRACTUAL" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_regimen">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL Jornada-->
<!-- ============================================================== -->

<div id="modal_jornada" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_jornada" id="form_jornada">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Jornada</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_JORNADA" name="ID_JORNADA" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="2">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Jornada *</label>
                                <input type="text" class="form-control" id="NOMBRE_JORNADA" name="NOMBRE_JORNADA" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_jornada">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL Turno-->
<!-- ============================================================== -->

<div id="modal_turno" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_turno" id="form_turno">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Turno</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_TURNO" name="ID_TURNO" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="3">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Turno *</label>
                                <input type="text" class="form-control" id="NOMBRE_TURNO" name="NOMBRE_TURNO" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_turno">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL Introducción-->
<!-- ============================================================== -->

<div id="modal_introduccion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_introduccion" id="form_introduccion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Introducción</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_INTRODUCCION" name="ID_INTRODUCCION" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="4">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Introducción *</label>
                                <textarea class="form-control" id="NOMBRE_INTRODUCCION" name="NOMBRE_INTRODUCCION" required rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_introduccion">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- ============================================================== -->
<!-- MODAL Definiciones-->
<!-- ============================================================== -->

<div id="modal_definiciones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_definiciones" id="form_definiciones">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Definición</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_DEFINICIONES" name="ID_DEFINICIONES" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="5">
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Uso *</label>
                                <select class="custom-select form-control" id="USO_DEFINICIONES" name="USO_DEFINICIONES" required="">
                                    <option value=""></option>
                                    <option value="Reconocimiento">Reconocimiento</option>
                                    <option value="Informe">Informe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Concepto *</label>
                                <input type="text" class="form-control" id="CONCEPTO_DEFINICION" name="CONCEPTO_DEFINICION" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción *</label>
                                <textarea class="form-control" id="DESCRIPCION_DEFINICION" name="DESCRIPCION_DEFINICION" required rows="6"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Fuente *</label>
                                <input type="text" class="form-control" id="FUENTE_DEFINICION" name="FUENTE_DEFINICION" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_definiciones">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<!-- MODAL Recomendaciones-->
<!-- ============================================================== -->

<div id="modal_recomendaciones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_recomendaciones" id="form_recomendaciones">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Recomendación</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_RECOMENDACIONES" name="ID_RECOMENDACIONES" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="6">
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Uso *</label>
                                <select class="custom-select form-control" id="USO_RECOMENDACIONES" name="USO_RECOMENDACIONES" required="">
                                    <option value=""></option>
                                    <option value="Reconocimiento">Reconocimiento</option>
                                    <option value="Informe">Informe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <select class="custom-select form-control" id="TIPO_RECOMENDACIONES" name="TIPO_RECOMENDACIONES" required="">
                                    <option value=""></option>
                                    <option value="Preventiva">Preventiva</option>
                                    <option value="Correctiva">Correctiva</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción *</label>
                                <textarea class="form-control" id="DESCRIPCION_RECOMENDACIONES" name="DESCRIPCION_RECOMENDACIONES" required rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_recomendaciones">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<!-- MODAL Conclusión -->
<!-- ============================================================== -->

<div id="modal_conclusion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_conclusion" id="form_conclusion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Conclusión</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_CONCLUSION" name="ID_CONCLUSION" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="7">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Conclusión *</label>
                                <textarea class="form-control" id="NOMBRE_CONCLUSION" name="NOMBRE_CONCLUSION" required rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_conclusion">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_visor>.modal-dialog {
        min-width: 900px !important;
    }

    #visor_menu_bloqueado {
        width: 851px;
        height: 52px;
        background: #555555;
        position: absolute;
        z-index: 500;
        border: 0px #F00 solid;
    }

    #visor_contenido_bloqueado {
        width: 852px;
        height: 600px;
        /*background: #555555;*/
        position: absolute;
        z-index: 600;
        border: 0px #FFF solid;
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
                <h4 class="modal-title" id="nombre_documento_visor"></h4>
            </div>
            <div class="modal-body" style="background: #555555;">
                <div class="row">
                    <div class="col-12">
                        {{-- <div id="visor_menu_bloqueado"></div> --}}
                        {{-- <div id="visor_contenido_bloqueado"></div> --}}
                        <iframe src="/assets/images/cargando.gif" name="visor_documento" id="visor_documento" allowfullscreen webkitallowfullscreen></iframe>
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
{{-- ========================================================================= --}}




@endsection