@extends('template/maestra')
@section('contenido')


<style>
    /* Reset básico */


    /* Contenedor del libro */
    .book-container {
        perspective: 1500px;
    }

    .book {
        position: relative;
        width: 280px;
        height: 300px;
        border-radius: 5px 0 0 5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transform: rotateY(-20deg);
        transform-style: preserve-3d;
        transition: transform 0.6s ease;
    }

    .book:hover {
        transform: rotateY(0deg);
    }

    /* Portada del libro */
    .cover {
        padding: 20px;
        height: 100%;
        border-radius: 5px 0 0 5px;
        z-index: 2;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        backface-visibility: hidden;
    }

    .book-title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .book-description {
        font-size: 14px;
        color: #34495e;
        margin-bottom: 20px;
    }

    .view-button {
        display: inline-block;
        background-color: #3498db;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .view-button:hover {
        background-color: #2980b9;
    }

    /* Páginas del libro */
    .pages {
        position: absolute;
        top: 0;
        right: 0;
        width: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        z-index: 1;
        transform: translateZ(-20px);
    }

    .page {
        background: linear-gradient(90deg, #fdfdfd, #e6e6e6);
        width: 100%;
        height: 18%;
        border-radius: 0 5px 5px 0;
        box-shadow: inset -1px 0px 8px rgba(0, 0, 0, 0.9);
    }

    /* Lado del libro */
    .side {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 100%;
        background: #e6e6e6;
        box-shadow: inset -1px 0px 5px rgba(0, 0, 0, 0.9);
        transform: translateZ(-10px);
    }
</style>

<!-- Esqueleto de la vista -->
<div class="row mt-4">
    <div class="col-12">
        <ol class="breadcrumb m-b-10">
            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))

            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-book" aria-hidden="true"></i> Centro de información</h2>

            <button type="button" class="btn btn-secondary waves-effect waves-light" style="margin-left: auto;" id="boton_nueva_doc">
                Información <i class="fa fa-plus p-1"></i>
            </button>
            @else
            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-book" aria-hidden="true"></i> Centro de información</h2>
            @endif
        </ol>
    </div>

    <!-- Lista de libros -->
    <div id="listaInformacion" class="row mx-2 mt-4 justify-content-between text-center w-100"></div>

    <!-- Loader -->
    <div class="row mt-3 w-100" id="divLoader" style="display: none;">
        <div class="col-12 text-center">
            <h2>Cargando lista...</h2>
        </div>
        <div class="col-12 text-center">
            <i class='fa fa-spin fa-spinner fa-5x'></i>
        </div>

    </div>
</div>


<!-- Modal para la creacion de nuevas informaciones -->
<div id="modal_informacion" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" name="form_subir_informacion" id="form_subir_informacion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Nueva informacion </h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Titulo *</label>
                                <input type="text" class="form-control" style="text-align: center;" name="TITULO" id="TITULO" required>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" style="text-align: center;" rows="4" name="DESCRIPCION" id="DESCRIPCION"></textarea>
                            </div>

                            <div class="col-12 d-flex align-items-center mb-3">
                                <h3 class="me-2" style="color: #0B3F64!important;"> Tipo de información </h3>
                                <div class="switch mx-4">
                                    <label>
                                        Documento
                                        <input type="checkbox" onchange="seleccionarInformacion(this)">
                                        <span class="lever switch-col-light-blue"></span>
                                        Link
                                    </label>
                                </div>
                            </div>

                            <div class="col-12" id="documento">
                                <div class="form-group">
                                    <label> Documento PDF *</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput" id="campo_file_documento">
                                            <i class="fa fa-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-secondary btn-file">
                                            <span class="fileinput-new">Seleccione</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" accept="application/pdf" name="DOCUMENTO" id="RUTA_DOCUMENTO">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                            Quitar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" id="enlace" style="display: none;">
                                <div class="form-group">
                                    <label> Link del enlace *</label>
                                    <input type="text" class="form-control" name="RUTA_LINK" id="RUTA_LINK">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light " id="boton_guardar_informacion">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.Modal para plantillas -->

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

@endsection