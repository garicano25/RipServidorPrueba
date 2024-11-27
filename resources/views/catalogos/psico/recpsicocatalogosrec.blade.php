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
                    <h6 class="card-subtitle text-white">Factor de riesgo psicosocial -Informes</h6>
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
                                        <td>Cargos para Informes</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(1);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_2">
                                        <td>Introducciones para Informes</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(2);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_2"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_3">
                                        <td>Definiciones para Informes</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(3);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_4">
                                        <td>Recomendaciones para Informes</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(4);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_5">
                                        <td>Conclusiones para Informes</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(5);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_5"></i>
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
                    <h6 class="card-subtitle text-white">NOM-035-STPS-2018</h6>
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
    <!-- MODAL CATALOGO -->
    <!-- ============================================================== -->
   
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
                                    value="1">
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

    <div id="modal_introduccion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_introduccion" id="form_introduccion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Introducción para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_INTRODUCCION_INFORME" name="ID_INTRODUCCION_INFORME"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Introducción *</label>
                                    <input type="text" class="form-control" id="INTRODUCCION" name="INTRODUCCION" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="2">
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

    <div id="modal_definicion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_definicion" id="form_definicion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Definición para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_DEFINICION_INFORME" name="ID_DEFINICION_INFORME"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Concepto *</label>
                                    <input type="text" class="form-control" id="CONCEPTO" name="CONCEPTO" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <input type="text" class="form-control" id="DESCRIPCION" name="DESCRIPCION" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Fuente *</label>
                                    <input type="text" class="form-control" id="FUENTE" name="FUENTE" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_definicion">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_recomendacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_recomendacion" id="form_recomendacion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Recomendación para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_RECOMENDACION_INFORME" name="ID_RECOMENDACION_INFORME"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="NIVEL">Selecciona una opción:</label>
                                    <select class="form-control" id="NIVEL" name="NIVEL">
                                        <option value="1">Primer Nivel</option>
                                        <option value="2">Segundo Nivel</option>
                                        <option value="3">Tercer Nivel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Recomendación *</label>
                                    <input type="text" class="form-control" id="RECOMENDACION" name="RECOMENDACION" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="4">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_recomendacion">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_conclusion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_conclusion" id="form_conclusion">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo">Conclusión para Informes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_CONCLUSION_INFORME" name="ID_CONCLUSION_INFORME"
                                    value="0">
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" id="NOMBRE" name="NOMBRE" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <input type="text" class="form-control" id="CONCLUSION" name="CONCLUSION" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo"
                                    value="5">
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

    {{-- ========================================================================= --}}
@endsection