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
        vertical-align: middle;
    }

    form label {
        color: #999999;
    }

    .ocultar {
        display: none;
    }

    .mostrar {
        display: block;
    }
</style>


<div class="row mt-5">
    <div class="col-12">
        <ol class="breadcrumb m-b-10">
            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-picture-o" aria-hidden="true"></i>
                Catálagos de imagenes para plantillas</h2>

            <button type="button" class="btn btn-secondary waves-effect waves-light boton_moduloplantilla platilla" style="margin-left: auto;" id="boton_nueva_platilla">
                Catálogos <i class="fa fa-plus p-1"></i>
            </button>
            @else
            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-picture-o" aria-hidden="true"></i>
                Catálagos de imagenes para plantillas</h2>
            @endif
        </ol>
        <br>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover stylish-table table-bordered" width="100%" id="tabla_plantillas">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="15%">Nombre</th>
                                <th width="20%">Imagen</th>
                                <th width="40%">Descripción</th>
                                <th width="10%">Editar</th>
                                <th width="10%">Eliminar</th>

                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para plantillas -->
<div id="modal_plantilla" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" name="form_subir_imagen" id="form_subir_imagen">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Catálagos de imagenes </h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_PLANTILLA_IMAGEN" name="ID_PLANTILLA_IMAGEN" value="0">
                            <div class="form-group">
                                <label>Nombre de la empresa*</label>
                                <input type="text" class="form-control" name="NOMBRE_PLANTILLA" id="NOMBRE_PLANTILLA" required>
                            </div>
                            <!-- Campo para subir imágenes -->
                            <div class="form-group">
                                <label>Logo*</label>
                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="logo" name="logo" data-allowed-file-extensions="jpg png JPG PNG" data-height="164" data-default-file="" required>
                            </div>

                            <br>
                            <div class="form-group">
                                <label>Descripción de la imagen</label>
                                <textarea class="form-control" style="text-align: center;" rows="4" name="DESCRIPCION_PLANTILLA" id="DESCRIPCION_PLANTILLA"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light " id="boton_guardar_logo">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.Modal para plantillas -->

@endsection