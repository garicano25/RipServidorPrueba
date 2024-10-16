@extends('template/maestra')
@section('contenido')
    {{-- ========================================================================= --}}

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
    </style>

    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body bg-secondary">
                    <h4 class="text-white card-title">Catálogos</h4>
                    <h6 class="card-subtitle text-white">Factor de riesgo psicosocial</h6>
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
                                        <td>GUIA DE REFERENCIA I</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(1);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_10"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_2">
                                        <td>GUIA DE REFERENCIA II</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(2);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_9"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_3">
                                        <td>GUIA DE REFERENCIA III</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(3);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_8"></i>
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

        <div class="col-8">
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
                                        <th style="width: 90px!important;">Activo</th>
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
    <!-- MODAL CATALOGO -->
    <!-- ============================================================== -->
    <div id="modal_catalogo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_catalogo" id="form_catalogo">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="id" name="id"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_catalogo">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_conclusion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="min-width: 70%;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_conclusion" id="form_conclusion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Conclusiones para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_CATCONCLUSION" name="ID_CATCONCLUSION"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" id="NOMBRE_CONCLUSION" name="NOMBRE"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Descripción * </label>
                                    <textarea class="form-control" rows="5" id="DESCRIPCION_CONCLUSION" name="DESCRIPCION"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="16">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_conclusion">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="modal_descripcionarea" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="min-width: 70%;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_descripcionarea"
                    id="form_descripcionarea">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Descripciones del área</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_DESCRIPCION_AREA"
                                    name="ID_DESCRIPCION_AREA" value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Descripción * </label>
                                    <textarea class="form-control" rows="5" id="DESCRIPCION" name="DESCRIPCION" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="17">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_descripcionarea">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_sistema" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="min-width: 70%;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_sistema" id="form_sistema">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Sistema de iluminación</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_SISTEMA_ILUMINACION"
                                    name="ID_SISTEMA_ILUMINACION" value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" id="NOMBRE" name="NOMBRE" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Descripción </label>
                                    <textarea class="form-control" rows="2" id="DESCRIPCION" name="DESCRIPCION"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="18">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_sistemailuminacion">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div id="modal_cargo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_cargo" id="form_cargo">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Cargo para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_CARGO_INFORME" name="ID_CARGO_INFORME"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Cargo *</label>
                                    <input type="text" class="form-control" id="CARGO" name="CARGO" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="14">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_cargo">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="modal_formatos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="min-width: 70%;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_formato" id="form_formato">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Nuevo formato de campo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_FORMATO" name="ID_FORMATO"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" id="FORMATO_NOMBRE" name="NOMBRE"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Documento PDF *</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput" id="campo_file_formato">
                                            <i class="fa fa-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-secondary btn-file">
                                            <span class="fileinput-new">Seleccione</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" accept="application/pdf" name="FORMATO_PDF"
                                                id="FORMATO_PDF" required>
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-secondary fileinput-exists"
                                            data-dismiss="fileinput">
                                            Quitar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> Descripción </label>
                                    <textarea class="form-control" rows="4" id="FORMATO_DESCRIPCION" name="DESCRIPCION"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="15">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_formato">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <div id="modal_catalogo2campos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_catalogo2campos"
                    id="form_catalogo2campos">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Catálogo [Activo]</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo2campos_id" name="id"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Siglas *</label>
                                    <input type="text" class="form-control" id="catalogo2campos_campo1"
                                        name="campo1" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Nombre *</label>
                                    <input type="text" class="form-control" id="catalogo2campos_campo2"
                                        name="campo2" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo2campos_catalogo" name="catalogo"
                                    value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_catalogo2campos">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <div id="modal_agentes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" style="min-width: 85%!important;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_agentes" id="form_agentes">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Agente o factor de riesgo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="agente_id" name="id"
                                    value="0">
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tipo *</label>
                                    <input type="text" class="form-control" id="catPrueba_Tipo" name="catPrueba_Tipo"
                                        placeholder="Ejem. Físico" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Nombre del agente o factor de riesgo *</label>
                                    <input type="text" class="form-control" id="catPrueba_Nombre"
                                        name="catPrueba_Nombre" placeholder="Ejem. Vibración" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <ol class="breadcrumb m-b-10">
                                    <button type="button" class="btn btn-secondary waves-effect waves-light"
                                        data-toggle="tooltip" title="Agregar nuevo registro" id="boton_nueva_norma">
                                        <span class="btn-label"><i class="fa fa-plus"></i></span>Norma / Procedimiento /
                                        Método
                                    </button>
                                </ol>
                                <div class="table-responsive"
                                    style="max-height: 410px!important; border: 2px #CCCCCC solid;">
                                    <table class="table table-hover stylish-table" width="100%"
                                        id="tabla_lista_normas">
                                        <thead>
                                            <tr>
                                                <th style="width: 200px!important;">Tipo *</th>
                                                <th style="width: 230px!important;">Numero *</th>
                                                <th>Descripción *</th>
                                                <th style="width: 70px!important;">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="agente_catalogo" name="catalogo"
                                    value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_agente">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <div id="modal_contrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_contrato" id="form_contrato">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Contrato</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="contrato_id" name="id"
                                    value="0">
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Numero contrato *</label>
                                    <input type="text" class="form-control" id="catcontrato_numero"
                                        name="catcontrato_numero" required>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label>Empresa *</label>
                                    <input type="text" class="form-control" id="catcontrato_empresa"
                                        name="catcontrato_empresa" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Inicio contrato *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control mydatepicker" placeholder="dd-mm-aaaa"
                                            id="catcontrato_fechainicio" name="catcontrato_fechainicio" required>
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Fin contrato *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control mydatepicker" placeholder="dd-mm-aaaa"
                                            id="catcontrato_fechafin" name="catcontrato_fechafin" required>
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Monto MXN *</label>
                                    <input type="number" step="any" class="form-control" id="catcontrato_montomxn"
                                        name="catcontrato_montomxn" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label> Monto USD *</label>
                                    <input type="number" step="any" class="form-control" id="catcontrato_montousd"
                                        name="catcontrato_montousd" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="contrato_catalogo" name="catalogo"
                                    value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_contrato">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <div id="modal_caracteristica" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_caracteristica" id="form_caracteristica">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="caracteristica_id" name="id"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Tipo *</label>
                                    <select class="custom-select form-control" id="tipo" name="tipo" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Característica *</label>
                                    <input type="text" class="form-control" id="caracteristica" name="caracteristica"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="caracteristica_catalogo" name="catalogo"
                                    value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_caracteristica">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal_catalogo_pa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 90%!important;">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_catalogo_pa" id="form_catalogo_pa">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Protección auditiva</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <!-- Foto del producto -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Foto</label>
                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="foto_proteccion" name="foto_proteccion" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" />
                            </div>
                        </div>

                        <!-- Información del producto -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-12">
                                    {!! csrf_field() !!}
                                    <input type="hidden" class="form-control" id="ID_PROTECCION" name="ID_PROTECCION" value="0">
                                    <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="19">
                              
                                </div>

                                <!-- Tipo y modelo -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Tipo *</label>
                                        <input type="text" class="form-control" id="TIPO" name="TIPO" placeholder="Ejemplo: Protección auditiva" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Modelo *</label>
                                        <input type="text" class="form-control" id="MODELO" name="MODELO" placeholder="Ejemplo: Modelo X" required>
                                    </div>
                                </div>

                                <!-- Marca y Cumplimiento Normativo -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marca *</label>
                                        <input type="text" class="form-control" id="MARCA" name="MARCA" placeholder="Ejemplo: Marca Y" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Cumplimiento Normativo *</label>
                                        <input type="text" class="form-control" id="CUMPLIMIENTO" name="CUMPLIMIENTO" required>
                                    </div>
                                </div>

                              
                                <!-- Ficha técnica PDF -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Ficha técnica PDF *</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput" id="campo_file_formato">
                                                <i class="fa fa-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-secondary btn-file">
                                                <span class="fileinput-new">Seleccione</span>
                                                <span class="fileinput-exists">Cambiar</span>
                                                <input type="file" accept="application/pdf" name="FICHA_PDF" id="FICHA_PDF" required>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Reducción de Ruido y Atenuación de Frecuencias -->
                    <div class="row">
                        <!-- Valor de la reducción de ruido -->
                        <div class="col-md-6">
                            <div class="border p-3">
                                <label><strong>Valor de la reducción de ruido</strong></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NRR *</label>
                                            <input type="number" class="form-control" id="NRR" name="NRR" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SNR</label>
                                            <input type="number" class="form-control" id="SNR" name="SNR" placeholder="dB">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Atenuación de frecuencias dB -->
                        <div class="col-md-6">
                            <div class="border p-3">
                                <label><strong>Atenuación de frecuencias dB</strong></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Altas (H) *</label>
                                            <input type="number" class="form-control" id="H" name="H" placeholder="dB" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Medias (M) *</label>
                                            <input type="number" class="form-control" id="M" name="M" placeholder="dB" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bajas (L) *</label>
                                            <input type="number" class="form-control" id="L" name="L" placeholder="dB" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Frecuencias y Atenuación -->
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="max-height: 410px!important; margin-top: 20px;">
                                <table class="table table-hover stylish-table" width="100%" id="div_tabla_equipoauditivo_atenuaciones">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <h4><b>Frecuencia en Hz</b></h4>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table table-hover stylish-tableo" width="100%"
                            id="tabla_frecuencia_atenuaciones">
                                    <tbody>
                                        <tr>
                                            <td><h7><b>Frecuencia en Hz</b></h7></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_1" id="FRECUENCIA_1" value="125" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_2" id="FRECUENCIA_2" value="250" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_3" id="FRECUENCIA_3" value="500" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_4" id="FRECUENCIA_4" value="1000" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_5" id="FRECUENCIA_5" value="2000" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_6" id="FRECUENCIA_6" value="3150" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_7" id="FRECUENCIA_7" value="4000" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_8" id="FRECUENCIA_8" value="6300" readonly></td>
                                            <td><input type="number" step="any" class="form-control" name="FRECUENCIA_9" id="FRECUENCIA_9" value="8000" readonly></td>
                                        </tr>
                                        <tr>
                                            <td><h7><b>Atenuación media en dB</b></h7></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_1" id="ATENUACION_1" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_2" id="ATENUACION_2" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_3" id="ATENUACION_3" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_4" id="ATENUACION_4" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_5" id="ATENUACION_5" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_6" id="ATENUACION_6" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_7" id="ATENUACION_7" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_8" id="ATENUACION_8" required></td>
                                            <td><input type="number" step="any" class="form-control" name="ATENUACION_9" id="ATENUACION_9" required></td>
                                        </tr>
                                        <tr>
                                            <td><h7><b>Desviación estándar en dB</b></h7></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_1" id="DESVIACION_1" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_2" id="DESVIACION_2" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_3" id="DESVIACION_3" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_4" id="DESVIACION_4" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_5" id="DESVIACION_5" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_6" id="DESVIACION_6" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_7" id="DESVIACION_7" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_8" id="DESVIACION_8" required></td>
                                            <td><input type="number" step="any" class="form-control" name="DESVIACION_9" id="DESVIACION_9" required></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catalogo_pa">Guardar <i class="fa fa-save"></i></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

    <!-- </div>
    </div> -->


    <div id="modal_catalogo_epp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_catalogo_epp" id="form_catalogo_epp">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_PARTESCUERPO_DESCRIPCION"
                                    name="ID_PARTESCUERPO_DESCRIPCION" value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Parte del cuerpo *</label>
                                    <select class="custom-select form-control" id="PARTECUERPO_ID"
                                        name="PARTECUERPO_ID" required>
                                        <option value=""></option>
                                        <option value="1">Cabeza</option>
                                        <option value="2">Oídos</option>
                                        <option value="3">Ojos y cara</option>
                                        <option value="4">Tronco</option>
                                        <option value="5">Extremidades superiores</option>
                                        <option value="6">Extremidades inferiores</option>
                                        <option value="7">Aparato respiratorio</option>
                                        <option value="8">Otros</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Clave y EPP *</label>
                                    <input type="text" class="form-control" id="CLAVE_EPP" name="CLAVE_EPP"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Tipo de riesgo en funcion de la actividad del trabajador *</label>
                                    <input type="text" class="form-control" id="TIPO_RIEGO" name="TIPO_RIEGO"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="6">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect"
                            data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"
                            id="boton_guardar_catalogo_epp">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- MODAL CATALOGO -->
    <!-- ============================================================== -->

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
    <div id="modal_visor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
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
                            <iframe src="/assets/images/cargando.gif" name="visor_documento" id="visor_documento"
                                allowfullscreen webkitallowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"
                        id="modalvisor_boton_cerrar">Cerrar</button>
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