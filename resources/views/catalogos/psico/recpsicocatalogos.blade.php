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
                                                    id="cat_1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_2">
                                        <td>GUIA DE REFERENCIA II</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(2);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_2"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr id="tr_3">
                                        <td>GUIA DE REFERENCIA III</td>
                                        <td>
                                            <a href="#" onclick="mostrar_catalogo(3);">
                                                <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                    id="cat_3"></i>
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
    <div id="modal_guia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="min-width: 70%;">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data" name="form_catalogo_guia" id="form_catalogo_guia">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="modal_titulo"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! csrf_field() !!}
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="ID_GUIAPREGUNTA" name="ID_GUIAPREGUNTA" value="0">
                                <input type="hidden" class="form-control" id="TIPOGUIA" name="TIPOGUIA" value="1">
                                <input type="hidden" class="form-control" id="PREGUNTA_ID" name="PREGUNTA_ID" value="1">

                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Pregunta *</label>
                                    <input type="text" class="form-control" id="PREGUNTA_NOMBRE" name="PREGUNTA_NOMBRE" required readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label> Explicación *</label>
                                    <textarea class="form-control" rows="2" id="PREGUNTA_EXPLICACION" name="PREGUNTA_EXPLICACION"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_guia">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ========================================================================= --}}
@endsection