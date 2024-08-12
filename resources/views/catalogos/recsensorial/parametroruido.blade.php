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
                        {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 foto_galeria">
                            <span style="font-size: 13px; color: #FFFFFF; text-shadow: 0 0 3px #000000, 0 0 3px #000000; position: absolute; left: 20px;">Foto Motogeneradores</span>
                            <i class="fa fa-trash text-danger" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 24px;" data-toggle="tooltip" title="Eliminar" onclick="foto_eliminar(0);"></i>
                            <i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 60px;" data-toggle="tooltip" title="Descargar" onclick="foto_descargar(0);"></i>
                            <a href="/recsensorialevidenciafotomostrar/1/1" data-effect="mfp-zoom-in">
                                <img class="d-block img-fluid" src="/recsensorialevidenciafotomostrar/1/1" style="margin: 0px 0px 20px 0px;" data-toggle="tooltip" title="Click para mostrar"/>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
    <ol class="breadcrumb m-b-10">
        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo Equipo utilizado en la medición" data-html="true" id="boton_nuevo_equiporuido">
            <span class="btn-label"><i class="fa fa-plus"></i></span>Equipo utilizado
        </button>
    </ol>
    @else
    <ol class="breadcrumb m-b-10">
        Equipos utilizados para la medición
    </ol>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="height: auto; max-height: 230px; overflow-y: auto; overflow-x: none;">
                        <div class="col-12">
                            <table class="table table-hover stylish-table" width="100%" id="tabla_ruidoequipos">
                                <thead>
                                    <tr>
                                        <th width="60">No.</th>
                                        <th>Proveedor</th>
                                        <th>Equipo</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serie</th>
                                        <th width="120">Vigencia</th>
                                        <th width="50">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9">No hay datos que mostrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ol class="breadcrumb m-b-10">
        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo registro" id="boton_nueva_sonometria">
            <span class="btn-label"><i class="fa fa-plus"></i></span>Sonometría
        </button>
        @else
        SONOMETRÍAS
        @endif
    </ol>
    <table class="table table-hover stylish-table" width="100%" id="tabla_parametroruidosonometria">
        <thead>
            <tr>
                <th width="60">No.</th>
                <th width="120">Área</th>
                <th>Categoría</th>
                <th width="120">NSA</th>
                <th width="60">Puntos</th>
                <th width="100">Resultado</th>
                <th width="60">Editar</th>
                <th width="60">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
        </tbody>
    </table><br><br><br>
    <ol class="breadcrumb m-b-10">
        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0 && ($recsensorial->recsensorial_fisicosimprimirbloqueado + 0) == 0)
        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo registro" id="boton_nueva_dosimetria">
            <span class="btn-label"><i class="fa fa-plus"></i></span>Dosimetría
        </button>
        @else
        DOSIMETRÍAS
        @endif
    </ol>
    <table class="table table-hover stylish-table" width="100%" id="tabla_parametroruidodosimetria">
        <thead>
            <tr>
                <th style="width: 100px !important;">No.</th>
                <th>Categoría</th>
                <th>Dosis</th>
                <th style="width: 100px!important;">Editar</th>
                <th style="width: 100px!important;">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
        </tbody>
    </table><br><br><br>

</div>
{{-- ========================================================================= --}}


<!-- ============================================================== -->
<!-- MODALES -->
<!-- ============================================================== -->
<div id="modal_sonometria" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 80%!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_parametro_1" id="form_parametro_1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Sonometría</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="sonometria" name="sonometria" value="1">
                            <input type="hidden" class="form-control" id="sonometria_registro_id" name="registro_id" value="0">
                            <input type="hidden" class="form-control" id="sonometria_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Área</label>
                                <select class="custom-select form-control" id="select_area" name="recsensorialarea_id" onchange="consultalista_categoriasxarea(this.value);" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Categorías</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" id="chekbox_categorias" style="border: 0px #000 solid; max-height: 170px; overflow-x: hidden; overflow-y: auto;"></div>
                                </div>
                            </div>
                        </div>


                        <div class="col-2"></div>

                        <div class="col-12">
                            <style type="text/css">
                                #tabla_sonometria_mediciones label {
                                    font-size: 16px !important;
                                }
                            </style>
                            <table border="0" cellspacing="0" cellpadding="12" width="100%" id="tabla_sonometria_mediciones">
                                <tbody>
                                    <tr>
                                        <td width="200" rowspan="2">
                                            <label class="demo-switch-title">NSA 10 mediciones</label>
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" name="parametroruidosonometria_10mediciones" id="parametroruidosonometria_10mediciones" onchange="nsa10mediciones_estado(this)">
                                                    <span class="lever switch-col-red"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label>Med. 1</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med1" name="parametroruidosonometria_med1" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 2</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med2" name="parametroruidosonometria_med2" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 3</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med3" name="parametroruidosonometria_med3" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 4</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med4" name="parametroruidosonometria_med4" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 5</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med5" name="parametroruidosonometria_med5" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Med. 6</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med6" name="parametroruidosonometria_med6" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 7</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med7" name="parametroruidosonometria_med7" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 8</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med8" name="parametroruidosonometria_med8" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 9</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med9" name="parametroruidosonometria_med9" disabled>
                                        </td>
                                        <td>
                                            <label>Med. 10</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_med10" name="parametroruidosonometria_med10" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="200">
                                            <label class="demo-switch-title">NSA (Max & Min)</label>
                                            <div class="switch">
                                                <label>
                                                    <input type="checkbox" name="parametroruidosonometria_2mediciones" id="parametroruidosonometria_2mediciones" onchange="nsa2mediciones_estado(this)">
                                                    <span class="lever switch-col-red"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label>NSA Maximo</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_medmax" name="parametroruidosonometria_medmax" disabled>
                                        </td>
                                        <td>
                                            <label>NSA minimo</label>
                                            <input type="number" step="any" class="form-control" placeholder="dB" id="parametroruidosonometria_medmin" name="parametroruidosonometria_medmin" disabled>
                                        </td>
                                        <td colspan="3">
                                            <label> Puntos</label>
                                            <input type="number" onkeypress="return this.value.length < 4;" oninput="if(this.value.length>=4) { this.value = this.value.slice(0,4); }" class="form-control" maxlength="6" name="parametroruidosonometria_puntos" id="parametroruidosonometria_puntos" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_parametro_1">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<div id="modal_dosimetria" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 80%!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_parametro_2" id="form_parametro_2">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Dosimetría</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="dosimetria" name="dosimetria" value="1">
                            <input type="hidden" class="form-control" id="dosimetria_registro_id" name="registro_id" value="0">
                            <input type="hidden" class="form-control" id="dosimetria_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Categoría *</label>
                                <select class="custom-select form-control" id="select_categoria_2" name="recsensorialcategoria_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Dosis *</label>
                                <input type="number" class="form-control" name="parametroruidodosimetria_dosis" id="parametroruidodosimetria_dosis" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_parametro_2">
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
<!-- MODAL-EQUIPO-MEDICION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_equiporuido .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_equiporuido .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_equiporuido" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 900px!important;">
        <form method="post" enctype="multipart/form-data" name="form_equiporuido" id="form_equiporuido">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Equipo de medición</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Laboratorio</label>
                                <select class="custom-select form-control" id="equiporuidoproveedor_id" name="proveedor_id" onchange="filtra_equipos(this.value)" required>
                                    <option value=""></option>
                                    @foreach($proveedores as $proveedor)
                                    <option value="{{$proveedor->id}}">{{$proveedor->proveedor_RazonSocial}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Equipo</label>
                                <select class="custom-select form-control" id="equiporuidoequipo_id" name="equipo_id" required>
                                    <option value=""></option>
                                    {{-- @foreach($equipos as $equipo)
                                        <option value="{{$equipo->id}}">{{$equipo->equipo_Descripcion}}, {{$equipo->equipo_VigenciaCalibracion}}, {{ date('Y-m-d') }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']) && ($recsensorial->recsensorial_bloqueado + 0) == 0)
                    <button type="submit" class="btn btn-danger" id="boton_guardar_equiporuido">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-EQUIPO-MEDICION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->
<script type="text/javascript" charset="utf-8" async defer>
    // Variables
    var recsensorial = <?php echo $recsensorial_id; ?>;
    var recsensorial_id = recsensorial + 0;
    var tabla_parametroruidosonometria = null;
    var tabla_parametroruidodosimetria = null;


    // Load pagina
    $(document).ready(function() {
        funcion_tabla_parametroruidosonometria(recsensorial_id);
        funcion_tabla_parametroruidodosimetria(recsensorial_id);
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


    function consultalista_categoriasxarea(recsensorialarea_id) {
        if (recsensorialarea_id) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/recsensoriallistacategoriasxarea/" + recsensorialarea_id,
                data: {},
                cache: false,
                success: function(dato) {
                    $('#chekbox_categorias').html('');
                    $.each(dato.categorias, function(key, value) {
                        $("#chekbox_categorias").append('<div class="col-12">' +
                            '<div class="form-group">' +
                            '<div class="switch" style="float: left;">' +
                            '<label>' +
                            '<input type="checkbox" name="categoria[]" value="' + value.recsensorialcategoria_id + '" ' + value.checked + '>' +
                            '<span class="lever switch-col-light-blue"></span>' +
                            '</label>' +
                            '</div>' +
                            '<label class="demo-switch-title" style="float: left;">' + value.recsensorialcategoria_nombrecategoria + '</label>' +
                            '</div>' +
                            '</div>');
                    });
                },
                beforeSend: function() {
                    $('#chekbox_categorias').html('<i class="fa fa-spin fa-spinner fa-3x" style="margin: 0px auto;"></i>');
                },
                error: function(dato) {
                    $('#chekbox_categorias').html('');
                    return false;
                }
            }); //Fin ajax
        } else {
            $('#chekbox_categorias').html('');
        }
    }


    function funcion_tabla_parametroruidosonometria(recsensorial_id) {
        var numeroejecucion = 1;
        tabla_parametroruidosonometria = $('#tabla_parametroruidosonometria').DataTable({
            "ajax": {
                "url": "/parametroruidosonometriatabla/" + recsensorial_id,
                "type": "get",
                "cache": false,
                error: function(xhr, error, code) {
                    console.log('error en tabla_parametroruidosonometria ' + code);
                    if (numeroejecucion <= 1) {
                        tabla_parametroruidosonometria.ajax.url("/parametroruidosonometriatabla/" + recsensorial_id).load();
                        numeroejecucion += 1;
                    }
                },
                "data": {},
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
                    "data": "recsensorialarea_nombre",
                    "defaultContent": "-"
                },
                {
                    "data": "categorias",
                    "defaultContent": "-"
                },
                {
                    "data": "nsa",
                    "defaultContent": "-"
                },
                {
                    "data": "parametroruidosonometria_puntos",
                    "defaultContent": "-"
                },
                {
                    "data": "resultado",
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
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Todos"]
            ],
            // rowsGroup: [1, 2, 3], //agrupar filas
            order: [
                [0, "DESC"]
            ],
            ordering: true,
            processing: true,
            searching: true,
            paging: true,
            responsive: true,
            language: {
                lengthMenu: "Mostrar _MENU_ Registros",
                zeroRecords: "No se encontraron registros",
                info: "Página _PAGE_ de _PAGES_ (Total _TOTAL_ registros)",
                infoEmpty: "No se encontraron registros",
                infoFiltered: "(Filtrado de _MAX_ registros)",
                emptyTable: "No hay datos disponibles en la tabla",
                loadingRecords: "Cargando datos....",
                processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                search: "Buscar",
                paginate: {
                    first: "Primera",
                    last: "Ultima",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }


    function nsa10mediciones_estado(estado) {
        var disabled = true;
        var required = false;
        if (estado.checked || parseInt(estado) == 1) {
            disabled = false; // QUITAR DISABLE
            required = true; // REQUERIDO

            $('#parametroruidosonometria_10mediciones').prop("checked", true);
            nsa2mediciones_estado(0);
            $('#parametroruidosonometria_2mediciones').prop("checked", false);
        }

        $("#parametroruidosonometria_med1").val('');
        $("#parametroruidosonometria_med2").val('');
        $("#parametroruidosonometria_med3").val('');
        $("#parametroruidosonometria_med4").val('');
        $("#parametroruidosonometria_med5").val('');
        $("#parametroruidosonometria_med6").val('');
        $("#parametroruidosonometria_med7").val('');
        $("#parametroruidosonometria_med8").val('');
        $("#parametroruidosonometria_med9").val('');
        $("#parametroruidosonometria_med10").val('');

        $("#parametroruidosonometria_med1").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med2").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med3").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med4").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med5").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med6").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med7").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med8").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med9").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_med10").attr({
            'disabled': disabled,
            'required': required
        });
    }


    function nsa2mediciones_estado(estado) {
        var disabled = true;
        var required = false;
        if (estado.checked || parseInt(estado) == 1) {
            disabled = false; // QUITAR DISABLE
            required = true; // REQUERIDO

            $('#parametroruidosonometria_2mediciones').prop("checked", true);
            nsa10mediciones_estado(0);
            $('#parametroruidosonometria_10mediciones').prop("checked", false);
        }

        $("#parametroruidosonometria_medmax").val('');
        $("#parametroruidosonometria_medmin").val('');

        $("#parametroruidosonometria_medmax").attr({
            'disabled': disabled,
            'required': required
        });
        $("#parametroruidosonometria_medmin").attr({
            'disabled': disabled,
            'required': required
        });
    }


    $("#boton_nueva_sonometria").click(function() {
        $('#chekbox_categorias').html('');
        consulta_select_areas(recsensorial_id, 0);

        // Borrar formulario
        $('#form_parametro_1').each(function() {
            this.reset();
        });

        nsa2mediciones_estado(0);
        $('#parametroruidosonometria_2mediciones').prop("checked", false);

        nsa10mediciones_estado(0);
        $('#parametroruidosonometria_10mediciones').prop("checked", false);

        // // Campos Hidden
        $("#sonometria_registro_id").val(0);
        $("#sonometria_recsensorial_id").val(recsensorial_id);

        // mostrar modal
        $('#modal_sonometria').modal({
            backdrop: false
        });
    });


    // Selecciona REGISTRO
    $(document).ready(function() {
        $('#tabla_parametroruidosonometria tbody').on('click', 'td.editar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametroruidosonometria.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                $('#chekbox_categorias').html('');

                // Borrar formulario
                $('#form_parametro_1').each(function() {
                    this.reset();
                });


                if (row.data().parametroruidosonometria_med1 != null || row.data().parametroruidosonometria_medmax != null) {
                    if (row.data().parametroruidosonometria_med1) {
                        nsa10mediciones_estado(1);
                    } else {
                        nsa2mediciones_estado(1);
                    }
                } else {
                    nsa10mediciones_estado(0);
                    nsa2mediciones_estado(0);
                }


                // LLENAR CAMPOS
                $("#sonometria_registro_id").val(row.data().id);
                $("#sonometria_recsensorial_id").val(row.data().recsensorial_id);
                $("#parametroruidosonometria_puntos").val(row.data().parametroruidosonometria_puntos);

                $("#parametroruidosonometria_med1").val(row.data().parametroruidosonometria_med1);
                $("#parametroruidosonometria_med2").val(row.data().parametroruidosonometria_med2);
                $("#parametroruidosonometria_med3").val(row.data().parametroruidosonometria_med3);
                $("#parametroruidosonometria_med4").val(row.data().parametroruidosonometria_med4);
                $("#parametroruidosonometria_med5").val(row.data().parametroruidosonometria_med5);
                $("#parametroruidosonometria_med6").val(row.data().parametroruidosonometria_med6);
                $("#parametroruidosonometria_med7").val(row.data().parametroruidosonometria_med7);
                $("#parametroruidosonometria_med8").val(row.data().parametroruidosonometria_med8);
                $("#parametroruidosonometria_med9").val(row.data().parametroruidosonometria_med9);
                $("#parametroruidosonometria_med10").val(row.data().parametroruidosonometria_med10);

                $("#parametroruidosonometria_medmax").val(row.data().parametroruidosonometria_medmax);
                $("#parametroruidosonometria_medmin").val(row.data().parametroruidosonometria_medmin);


                // llenar campos
                consulta_select_areas(row.data().recsensorial_id, row.data().recsensorialarea_id);
                consultalista_categoriasxarea(row.data().recsensorialarea_id);

                // mostrar modal
                $('#modal_sonometria').modal({
                    backdrop: false
                });
            }
        });
    });


    // GUARDAR SONOMETRIA
    $("#boton_guardar_parametro_1").click(function() {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            // valida opciones categorias
            var seleccionados = 0;
            $('#form_parametro_1 input[type=checkbox]').each(function() {
                if (this.checked) {
                    seleccionados += 1;
                }
            });


            if (seleccionados > 0) {
                // enviar datos
                $('#form_parametro_1').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/parametroruido',
                    data: {
                        opcion: 1,
                    },
                    resetForm: false,
                    success: function(dato) {
                        // Campos Hidden
                        $("#sonometria_registro_id").val(dato.parametro.id);
                        $("#sonometria_recsensorial_id").val(dato.parametro.recsensorial_id);

                        // actualiza tabla
                        tabla_parametroruidosonometria.destroy();
                        funcion_tabla_parametroruidosonometria(dato.parametro.recsensorial_id);

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
                        $('#boton_guardar_parametro_1').html('Guardar <i class="fa fa-save"></i>');

                        // cerrar modal
                        $('#modal_sonometria').modal('hide');
                    },
                    beforeSend: function() {
                        $('#boton_guardar_parametro_1').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(dato) {
                        // actualiza boton
                        $('#boton_guardar_parametro_1').html('Guardar <i class="fa fa-save"></i>');
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
            } else {
                // mensaje
                swal({
                    title: "Seleccione categorías",
                    text: "Debe seleecionar al menos una categoría",
                    type: "warning", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 3000,
                    showConfirmButton: false
                });
                return false;
            }

        }
    });


    // eliminar SONOMETRIA
    $(document).ready(function() {
        $('#tabla_parametroruidosonometria tbody').on('click', 'td.eliminar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametroruidosonometria.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                swal({
                    title: "¿Eliminar registo de Sonometría?",
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
                            title: "¡Confirme nuevamente eliminar registro de Sonometría!",
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
                                    url: "/parametroruidosonometriaeliminar/" + row.data().id,
                                    data: {},
                                    cache: false,
                                    success: function(dato) {
                                        // actualiza tabla
                                        tabla_parametroruidosonometria.destroy();
                                        funcion_tabla_parametroruidosonometria(row.data().recsensorial_id);

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


    //======================================================


    $("#boton_nueva_dosimetria").click(function() {
        consulta_select_categorias(recsensorial_id, 0);

        // Borrar formulario
        $('#form_parametro_2').each(function() {
            this.reset();
        });

        // Campos Hidden
        $("#dosimetria_registro_id").val(0);
        $("#dosimetria_recsensorial_id").val(recsensorial_id);

        // mostrar modal
        $('#modal_dosimetria').modal({
            backdrop: false
        });
    });


    // Selecciona REGISTRO
    $(document).ready(function() {
        $('#tabla_parametroruidodosimetria tbody').on('click', 'td.editar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametroruidodosimetria.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                // Borrar formulario
                $('#form_parametro_2').each(function() {
                    this.reset();
                });

                // llenar campos
                $("#dosimetria_registro_id").val(row.data().id);
                $("#dosimetria_recsensorial_id").val(row.data().recsensorial_id);
                $("#parametroruidodosimetria_dosis").val(row.data().parametroruidodosimetria_dosis);
                consulta_select_categorias(recsensorial_id, row.data().recsensorialcategoria_id);

                // mostrar modal
                $('#modal_dosimetria').modal({
                    backdrop: false
                });
            }
        });
    });


    function funcion_tabla_parametroruidodosimetria(recsensorial_id) {
        tabla_parametroruidodosimetria = $('#tabla_parametroruidodosimetria').DataTable({
            "ajax": {
                "url": "/parametroruidodosimetriatabla/" + recsensorial_id,
                "type": "get",
                "cache": false,
                error: function(xhr, error, code) {
                    // console.log(xhr); console.log(code);
                    // funcion_tabla_parametroruidodosimetria(recsensorial_id);
                    tabla_parametroruidodosimetria.ajax.url("/parametroruidodosimetriatabla/" + recsensorial_id).load();
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
                    "data": "recsensorialcategoria.recsensorialcategoria_nombrecategoria",
                    "defaultContent": "Sin dato"
                },
                {
                    "data": "parametroruidodosimetria_dosis"
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
            "order": [
                [0, "desc"]
            ],
            "ordering": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros",
                "zeroRecords": "No se encontraron registros",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No se encontraron registros",
                "infoFiltered": "(Filtrado de _MAX_ registros)",
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando datos....",
                "processing": "Procesando...",
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


    // GUARDAR DOSIMETRIA
    $("#boton_guardar_parametro_2").click(function() {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            // enviar datos
            $('#form_parametro_2').ajaxForm({
                dataType: 'json',
                type: 'POST',
                url: '/parametroruido',
                data: {
                    opcion: 1,
                },
                resetForm: false,
                success: function(dato) {
                    // Campos Hidden
                    $("#dosimetria_registro_id").val(dato.parametro.id);
                    $("#dosimetria_recsensorial_id").val(dato.parametro.recsensorial_id);

                    // actualiza tabla
                    tabla_parametroruidodosimetria.destroy();
                    funcion_tabla_parametroruidodosimetria(dato.parametro.recsensorial_id);

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
                    $('#boton_guardar_parametro_2').html('Guardar <i class="fa fa-save"></i>');

                    // cerrar modal
                    $('#modal_dosimetria').modal('hide');
                },
                beforeSend: function() {
                    $('#boton_guardar_parametro_2').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                },
                error: function(dato) {
                    // actualiza boton
                    $('#boton_guardar_parametro_2').html('Guardar <i class="fa fa-save"></i>');
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


    // eliminar DOSIMETRIA
    $(document).ready(function() {
        $('#tabla_parametroruidodosimetria tbody').on('click', 'td.eliminar', function() {
            var tr = $(this).closest('tr');
            var row = tabla_parametroruidodosimetria.row(tr);

            if (parseInt(row.data().accion_activa) > 0) {
                swal({
                    title: "¿Eliminar registo de Dosimetría?",
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
                            title: "¡Confirme nuevamente eliminar registro de Dosimetría!",
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
                                    url: "/parametroruidodosimetriaeliminar/" + row.data().id,
                                    data: {},
                                    cache: false,
                                    success: function(dato) {
                                        // actualiza tabla
                                        tabla_parametroruidodosimetria.destroy();
                                        funcion_tabla_parametroruidodosimetria(row.data().recsensorial_id);

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

    var parametro_id = 1; // ID en base datos
    var parametro_nombre = 'ruido';
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


    //------------------------------------------------------------


    var equipos = <?php echo $equipos; ?>;


    // Load pagina
    $(document).ready(function() {
        tabla_ruidoequipos(recsensorial_id);
    });


    var datatable_ruidoequipos = null;

    function tabla_ruidoequipos(recsensorial_id) {
        try {
            var ruta = "/parametroruidoequipotabla/" + recsensorial_id;

           
            if (tabla_ruidoequipos != null) {
            // Destruir la tabla existente antes de crear una nueva
            tabla_ruidoequipos.destroy();
            }

                var numeroejecucion = 1;
                datatable_ruidoequipos = $('#tabla_ruidoequipos').DataTable({
                    ajax: {
                        url: ruta,
                        type: "get",
                        cache: false,
                        dataType: "json",
                        data: {},
                        dataSrc: function(json) {
                            // alert("Done! "+json.msj);
                            return json.data;
                        },
                        error: function(xhr, error, code) {
                            console.log('error en datatable_ruidoequipos ' + code);
                            if (numeroejecucion <= 1) {
                                tabla_ruidoequipos(recsensorial_id);
                                numeroejecucion += 1;
                            }
                        }
                    },
                    columns: [
                        // {
                        //     data: "id" 
                        // },
                        {
                            data: "numero_registro",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "proveedor_RazonSocial",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "equipo_Descripcion",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "equipo_Marca",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "equipo_Modelo",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "equipo_Serie",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "equipo_VigenciaCalibracion",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "boton_mostrar",
                            defaultContent: "-",
                            // className: '',
                            orderable: false,
                        },
                        {
                            data: "boton_eliminar",
                            defaultContent: "-",
                            orderable: false,
                        }
                    ],
                    lengthMenu: [
                        [20, 50, 100, -1],
                        [20, 50, 100, "Todos"]
                    ],
                    // rowsGroup: [1, 2, 3], //agrupar filas
                    order: [
                        [0, "ASC"]
                    ],
                    ordering: false,
                    processing: true,
                    searching: false,
                    paging: false,
                    responsive: true,
                    language: {
                        lengthMenu: "Mostrar MENU Registros",
                        zeroRecords: "No se encontraron registros",
                        info: "Página PAGE de PAGES (Total TOTAL registros)",
                        infoEmpty: "No se encontraron registros",
                        infoFiltered: "(Filtrado de MAX registros)",
                        emptyTable: "No hay datos disponibles en la tabla",
                        loadingRecords: "Cargando datos....",
                        processing: "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                        search: "Buscar",
                        paginate: {
                            first: "Primera",
                            last: "Ultima",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    rowCallback: function(row, data, index) {
                        // console.log(index+' - '+data.reporteiluminacionpuntos_nopunto);

                        // if(data.reporteiluminacionpuntos_nopunto == 2)
                        // {
                        //  $(row).find('td:eq(12)').css('background', 'red');
                        //  $(row).find('td:eq(12)').css('color', 'white');
                        // }

                        // $(row).find('td:eq(9)').css('color', ''+data.frpmed1_color);
                        // $(row).find('td:eq(10)').css('color', ''+data.frptmed1_color);
                        // $(row).find('td:eq(11)').css('color', ''+data.frpmed2_color);
                        // $(row).find('td:eq(12)').css('color', ''+data.frptmed2_color);
                        // $(row).find('td:eq(13)').css('color', ''+data.frpmed3_color);
                        // $(row).find('td:eq(14)').css('color', ''+data.frptmed3_color);

                        // $(row).find('td:eq(15)').css('background', ''+data.fr_resultado_color);
                        // $(row).find('td:eq(15)').css('color', '#FFFFFF');
                    },
                });
            

            // Tooltip en DataTable
            datatable_ruidoequipos.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        } catch (exception) {
            tabla_ruidoequipos(recsensorial_id);
        }
    }


    $("#boton_nuevo_equiporuido").click(function() {
        if ($("#equiporuidoequipo_id")[0].selectize) {
            $("#equiporuidoequipo_id")[0].selectize.destroy();
        }


        $("#equiporuidoequipo_id").html('<option value=""></option>');


        // Borrar formulario
        $('#form_equiporuido').each(function() {
            this.reset();
        });


        // mostrar modal
        $('#modal_equiporuido').modal({
            backdrop: false
        });
    });


    function filtra_equipos(proveedor_id) {
        if (parseInt(proveedor_id) > 0) {
            if ($("#equiporuidoequipo_id")[0].selectize) {
                $("#equiporuidoequipo_id")[0].selectize.destroy();
            } else {
                $("#equiporuidoequipo_id").html('<option value="">Filtrando, por favor espere...</option>');
            }


            var selectoption = '<option value=""></option>';
            equipos.forEach(function(value, index) {
                if (parseInt(proveedor_id) == parseInt(value.proveedor_id)) {
                    // alert(value.equipo_Descripcion);
                    selectoption += '<option value="' + value.id + '">' + value.equipo_Descripcion + ', Serie: ' + value.equipo_Serie + '</option>';
                }
            });


            $("#equiporuidoequipo_id").html(selectoption);
            $("#equiporuidoequipo_id").selectize(); //Inicializar campo tipo [select-search]
            $("#equiporuidoequipo_id").attr('required', true);
        } else {
            $("#equiporuidoequipo_id")[0].selectize.destroy();
            $("#equiporuidoequipo_id").html('<option value=""></option>');
            $("#equiporuidoequipo_id").attr('required', true);
        }
    }


    $("#boton_guardar_equiporuido").click(function() {
        // valida campos vacios
        var valida = this.form.checkValidity();
        if (valida) {
            // Valida envio de datos
            swal({
                title: "¡Confirme guardar el equipo",
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
                    $('#form_equiporuido').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: '/parametroruido',
                        data: {
                            opcion: 2, //EQUIPOS
                            recsensorial_id: recsensorial_id,
                        },
                        resetForm: false,
                        success: function(dato) {
                            // Actualiza TABLA equipos
                            tabla_ruidoequipos(recsensorial_id);

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
                            $('#boton_guardar_equiporuido').html('Guardar <i class="fa fa-save"></i>');
                            $('#boton_guardar_equiporuido').attr('disabled', false);

                            // cerrar modal
                            $('#modal_equiporuido').modal('hide');
                        },
                        beforeSend: function() {
                            $('#boton_guardar_equiporuido').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                            $('#boton_guardar_equiporuido').attr('disabled', true);
                        },
                        error: function(dato) {
                            // actualiza boton
                            $('#boton_guardar_equiporuido').html('Guardar <i class="fa fa-save"></i>');
                            $('#boton_guardar_equiporuido').attr('disabled', false);

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


    $('#tabla_ruidoequipos tbody').on('click', 'td>button.mostrar_pdf', function() {
        var tr = $(this).closest('tr');
        var row = datatable_ruidoequipos.row(tr);

        // alert(row.data().equipo_Descripcion);

        // abrir modal
        $('#modal_visor').modal({
            backdrop: false
        });

        // TITULO DLE VISOR
        $('#nombre_documento_visor').html(row.data().equipo_Descripcion);

        // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verequipodocumento/'+row.data().id);
        $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verequipodocumento/' + row.data().id);
    });


    // ELIMINAR EQUIPO DE LA LISTA
    $('#tabla_ruidoequipos tbody').on('click', 'td>button.eliminar_equipo', function() {
        var tr = $(this).closest('tr');
        var row = datatable_ruidoequipos.row(tr);

        swal({
            title: "¿Eliminar equipo?",
            text: "" + row.data().equipo_Descripcion,
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
                    url: "/parametroruidoequipoeliminar/" + row.data().id,
                    data: {},
                    cache: false,
                    success: function(dato) {
                        // Actualiza TABLA equipos
                        tabla_ruidoequipos(recsensorial_id);

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

                return false;
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

        return false;
    });
</script>

<!-- Magnific popup JavaScript -->
<script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
<script src="/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>
<!-- ============================================================== -->
<!-- SCRIPT -->
<!-- ============================================================== -->