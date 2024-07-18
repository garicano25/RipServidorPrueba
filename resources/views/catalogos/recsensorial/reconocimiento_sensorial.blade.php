@extends('template/maestra')
@section('contenido')
{{-- ========================================================================= --}}


<div class="row page-titles">
    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Reconocimiento sensorial</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">Reconocimiento sensorial</li>
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


<style type="text/css" media="screen">
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

    form label {
        color: #999999;
    }

    form #visor_mapa {
        width: 100%;
        height: 320px;
        border: 1px #DDDDDD solid;
    }

    .checkbox_warning {
        border: 2px #F00 solid;
    }
</style>



<style>
    /* Estilo para el select */
    #JERARQUIACONTROL {
        background-color: white;
        /* Por defecto, el fondo es blanco */
        color: rgb(0, 0, 0);
        /* Por defecto, el texto es negro */
    }

    .bloqueado:hover {
        cursor: not-allowed;
    }

    .error {
        border: 2px solid red;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Menu tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link link_menuprincipal active" data-toggle="tab" href="#tab_1" id="tab_menu1" role="tab">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de reconocimientos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_2" id="tab_menu2" role="tab">
                        <span class="hidden-sm-up"><i class="ti-pencil-alt"></i></span>
                        <span class="hidden-xs-down">Datos del reconocimiento</span>
                    </a>
                </li>
                <li class="nav-item clienteblock">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_3" id="tab_menu3" role="tab">
                        <span class="hidden-sm-up"><i class="ti-stats-up"></i></span>
                        <span class="hidden-xs-down">Evaluar Rec. sensorial</span>
                    </a>
                </li>
                {{-- <li class="nav-item clienteblock">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_6" id="tab_menu6" role="tab">
                        <span class="hidden-sm-up"><i class="ti-stats-up"></i></span>
                        <span class="hidden-xs-down">Puntos por el cliente (<b id="texto_puntos_cliente">xxx</b>)</span>
                    </a>
                </li> --}}
                <li class="nav-item clienteblock">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_4" id="tab_menu4" role="tab">
                        <span class="hidden-sm-up"><i class="ti-bookmark-alt"></i></span>
                        <span class="hidden-xs-down">Evaluar Rec. químicos</span>
                    </a>
                </li>
                <li class="nav-item clienteblock">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_5" id="tab_menu5" role="tab">
                        <span class="hidden-sm-up"><i class="ti-bookmark-alt"></i></span>
                        <span class="hidden-xs-down">Resultados</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_9" id="tab_menu9" role="tab">
                        <span class="hidden-sm-up"><i class="ti-bookmark-alt"></i></span>
                        <span class="hidden-xs-down">Proporcionado por el cliente</span>
                    </a>
                </li>
            </ul>
            <!-- Tab Panels -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI']))
                    <ol class="breadcrumb m-b-10">
                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Lista de Reconocimientos </h2>
                        <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente" data-toggle="tooltip" title="Nuevo reconocimiento sensorial" style="margin-left:auto" id="boton_nuevo_recsensorial">
                            Reconocimiento <i class="fa fa-plus p-1"></i>
                        </button>
                    </ol>
                    @endif
                    <div class="table-responsive">
                        {{-- <div class="row">
                            <div class="col-12" id="tabla_reconocimiento_sensorial_campobuscar"></div>
                        </div> --}}
                        <table class="table table-hover stylish-table" width="100%" id="tabla_reconocimiento_sensorial">
                            <thead>
                                <tr>
                                    <th width="60">No.</th>
                                    <th width="100">Alcance</th>
                                    <th width="130">Folios</th>
                                    <th width="110">Cliente / Contrato</th>
                                    <!-- <th width="110">Región</th> -->
                                    <th>Folio Proyecto</th>
                                    <!-- <th>Gerencia / Activo</th> -->
                                    <th>Instalación</th>
                                    <th width="70">Mostrar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_folios">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;" class="div_reconocimiento_alcance">Reconocimiento</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Instalación</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-industry"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <!-- ============= STEPS ============= -->
                        <div style="min-width: 700px; width: 100% margin: 0px auto;">
                            <!--multisteps-form-->
                            <div class="multisteps-form">
                                <!--progress bar-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__progress">
                                            <div class="multisteps-form__progress-btn js-active" id="steps_menu_tab1">
                                                <i class="fa fa-file-text-o"></i><br>
                                                <span>Datos generales</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab2">
                                                <i class="fa fa-user"></i><br>
                                                <span>Categorías</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab3">
                                                <i class="fa fa-clone"></i><br>
                                                <span>Áreas</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab4">
                                                <i class="fa fa fa-industry"></i><br>
                                                <span>F. generadoras</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab5">
                                                <i class="fa fa-handshake-o"></i><br>
                                                <span>E. P. P.</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab6">
                                                <i class="fa fa-address-card"></i><br>
                                                <span>Responsables</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab7">
                                                <i class="fa fa-file-text-o"></i><br>
                                                <span>Anexos</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--form panels-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__form">
                                            <!--STEP 1-->
                                            <div class="multisteps-form__panel js-active" data-animation="scaleIn" id="steps_contenido_tab1">
                                                <div class="multisteps-form__content">
                                                    <form name="form_recsensorial" id="form_recsensorial" enctype="multipart/form-data" method="post">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <style type="text/css">
                                                                                .tooltip-inner {
                                                                                    max-width: 320px;
                                                                                    /*tooltip tamaño*/
                                                                                    padding: 6px 8px;
                                                                                    color: #fff;
                                                                                    text-align: justify;
                                                                                    background-color: #000;
                                                                                    border-radius: 0.25rem;
                                                                                    line-height: 16px;
                                                                                }

                                                                                #rol_lista:hover label {
                                                                                    color: #000000;
                                                                                    font-weight: bold;
                                                                                }
                                                                            </style>
                                                                            <div class="col-6 text-center" id="primeraParte" data-toggle="tooltip" title="¡Asegúrese de vincular primero el Reconocimiento con un folio de la lista de proyectos!">
                                                                                <div class="form-group">
                                                                                    <label style="font-weight: bold;font-size: 20px;">Relación a Proyecto (Folio) *</label>
                                                                                    <select class="custom-select form-control" id="proyecto_folio" name="proyecto_folio" required>
                                                                                        <option value="">&nbsp;</option>
                                                                                    </select>
                                                                                </div>


                                                                            </div>
                                                                            <div class="col-6 text-center">
                                                                                <div class="form-group" data-toggle="tooltip" title="No disponible por el momento.">
                                                                                    <label style="font-weight: bold;font-size: 20px;"> Relación a OT (Orden de trabajo)</label>
                                                                                    <select class="custom-select form-control" id="ordentrabajo_id" name="ordentrabajo_id">
                                                                                        <option value="1" disabled></option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">Datos generales</h4>
                                                                        <h6 class="card-subtitle text-white m-b-0 op-5">&nbsp;</h6>
                                                                        <div class="row">
                                                                            {!! csrf_field() !!}
                                                                            <div class="col-12">
                                                                                <input type="hidden" class="form-control" id="recsensorial_id" name="recsensorial_id" value="0">
                                                                                <input type="hidden" class="form-control" id="recsensorial_tipocliente" value="1">

                                                                            </div>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label> ¿Reconocimiento Sensorial? *</label>
                                                                                    <select class="custom-select form-control" id="recsensorial_alcancefisico" name="recsensorial_alcancefisico" onchange="valida_alcance_recfisicos(this.value);" required>
                                                                                        <option value=""></option>
                                                                                        <option value="1">Rec. en sitio</option>
                                                                                        <option value="2">Rec. de físicos (Puntos proporcionados por el cliente)</option>
                                                                                        <option value="3">Ambos (Rec. Sitio / Físicos)</option>
                                                                                        <option value="0">N/A</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label> ¿Reconocimiento de químicos? *</label>
                                                                                    <select class="custom-select form-control" id="recsensorial_alcancequimico" name="recsensorial_alcancequimico" onchange="valida_alcance_recquimicos(this.value);" required>
                                                                                        <option selected disabled>Seleccione una opción</option>
                                                                                        <option value="1">Rec. de químicos completo</option>
                                                                                        <option value="2">Rec. de químicos (Puntos proporcionados por el cliente)</option>
                                                                                        <option value="3">Rec. de químicos por un tercero</option>
                                                                                        <option value="0">N/A</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 mb-3" id="divListaAgentes">
                                                                                <p>
                                                                                <div class="row">
                                                                                    <div class="col-6">
                                                                                        <button class="btn btn-info" id="mostrarListaAgentes" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2" disabled>Agente / factor de riesgo / servicio, a evaluar (Sensorial)*</button>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <button class="btn btn-info" id="mostrarSelectAgentes" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" disabled>Sustancias Químicas (Químicos)*</button>

                                                                                    </div>

                                                                                </div>
                                                                                </p>
                                                                                <div class="row">
                                                                                    <div class="col-6" id="agentesSitios">
                                                                                        <div class="collapse multi-collapse" id="multiCollapseExample1">
                                                                                            <div class="card card-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-12 justify-content-center">
                                                                                                        <h4 style="float: left;" id="textSitio">Rec. en sitio</h4>
                                                                                                    </div>
                                                                                                    @foreach($catprueba as $dato)
                                                                                                    <div class="col-12 mb-3 " id="agente_{{$dato->id}}_sitio">
                                                                                                        <div class="form-check checkbox_agentes_div">
                                                                                                            <input class="form-check-input checkbox_agentes agentes_sitio" type="radio" name="parametro_{{$dato->id}}[]" id="chekbox_parametro_{{$dato->id}}_sitio" value="{{$dato->id}}">
                                                                                                            <label class="form-check-label" for="chekbox_parametro_{{$dato->id}}_sitio">
                                                                                                                {{$dato->catPrueba_Nombre}}
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6" id="agentesFisico">
                                                                                        <div class="collapse multi-collapse" id="multiCollapseExample2">
                                                                                            <div class="card card-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-12 justify-content-center">
                                                                                                        <h4 style="float: left;" id="textFisico">Rec. de físicos</h4>
                                                                                                    </div>
                                                                                                    @foreach($catprueba as $dato)
                                                                                                    <div class="col-12 " id="agente_{{$dato->id}}_fisico">
                                                                                                        <div class="form-check checkbox_agentes_div">
                                                                                                            <input class="form-check-input checkbox_agentes agentes_fisico" type="radio" name="parametro_{{$dato->id}}[]" id="chekbox_parametro_{{$dato->id}}_fisico" value="{{$dato->id}}">
                                                                                                            <label class="form-check-label" for="chekbox_parametro_{{$dato->id}}_fisico">
                                                                                                                {{$dato->catPrueba_Nombre}}
                                                                                                            </label>
                                                                                                            <input type="number" class="form-control cantidad" id="cantidad_{{$dato->id}}" style="width: 31%; height: 5px!important;" placeholder="Cantidad" min="0">

                                                                                                        </div>
                                                                                                    </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <style>
                                                                                        /* Estilos para el elemento select */
                                                                                        .select2-selection__choice {
                                                                                            color: #000;
                                                                                        }

                                                                                        .select2-container--default .select2-selection--multiple .select2-selection__choice {
                                                                                            background-color: #bee24f;
                                                                                        }

                                                                                        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                                                                                            background-color: #fff;
                                                                                        }
                                                                                    </style>
                                                                                    <div class="col-12">
                                                                                        <div class="collapse" id="collapseExample">
                                                                                            <div class="card card-body">
                                                                                                <div class="form-group">
                                                                                                    <label>Sustancias Químicas *</label>
                                                                                                    <select class="custom-select form-control" id="sustancias_quimicias" name="sustancias_quimicias" multiple="multiple" style="width: 100%">
                                                                                                        <!-- <option value="">&nbsp;</option> -->
                                                                                                        @foreach($catSustanciasQuimicas as $dato)
                                                                                                        <option value="{{$dato->ID_SUSTANCIA_QUIMICA}}">{{$dato->SUSTANCIA_QUIMICA}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div id="opciones_seleccionadas"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label> Folio rec. sensorial</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_foliofisico" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label> Folio rec. químico</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_folioquimico" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 mt-3 mb-3"></div>
                                                                            <div class="col-12 text-center" id="infoCliente">
                                                                                <!-- <style>
                                                                                    /* Estilos para el elemento select */
                                                                                    .custom-select {

                                                                                        padding: 8px;
                                                                                        /* Espacio interior */
                                                                                        border: 1px solid #ccc;
                                                                                        /* Borde */
                                                                                        border-radius: 4px;
                                                                                        /* Borde redondeado */
                                                                                    }

                                                                                    /* Estilo para las opciones cuando el mouse pasa sobre ellas */
                                                                                    .select2-results__option:hover {
                                                                                        color: #000;
                                                                                        /* Color de las letras */
                                                                                        font-size: 1.2em;
                                                                                        /* Tamaño de fuente más grande */
                                                                                        transition: all 0.3s ease-in-out;
                                                                                        /* Efecto de transición suave */
                                                                                    }

                                                                                    .select2-results__option[aria-disabled=true] {
                                                                                        opacity: 0.5;
                                                                                        /* Opacidad reducida para el texto de la opción deshabilitada */
                                                                                        color: red !important;
                                                                                        cursor: not-allowed;
                                                                                    }

                                                                                    .select2-container .select2-selection--single {
                                                                                        height: 40px;
                                                                                        /* Define la altura deseada */
                                                                                    }
                                                                                </style> -->

                                                                            </div>
                                                                            <!-- Datos del cliente Obtenidos del proyecto -->
                                                                            <input type="hidden" name="cliente_id" id="cliente_id">
                                                                            <input type="hidden" name="requiere_contrato" id="requiere_contrato">
                                                                            <input type="hidden" name="contrato_id" id="contrato_id">
                                                                            <input type="hidden" name="descripcion_cliente" id="descripcion_cliente">
                                                                            <input type="hidden" name="descripcion_contrato" id="descripcion_contrato">



                                                                            <!-- Guardamos la data de los clientes para poder usarla en JS -->
                                                                            <script type="text/javascript">
                                                                                var lista_clientes = <?php echo $cliente; ?>;
                                                                            </script>

                                                                            <!-- Datos de Informe obtenido por el cliente -->
                                                                            <div class="col-12 mt-5">
                                                                                <div id="accordion">
                                                                                    <div class="card">
                                                                                        <div class="card-header" id="headingOne">
                                                                                            <h5 class="mb-0">
                                                                                                <button class="btn btn-link" id="accordionButton" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" disabled>
                                                                                                    Informe del reconocimiento proporcionado por el cliente
                                                                                                </button>
                                                                                            </h5>
                                                                                        </div>
                                                                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-6">
                                                                                                        <div class="form-group">
                                                                                                            <label>Fecha de elaboración*</label>
                                                                                                            <div class="input-group">
                                                                                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="recsensorial_fechaelaboracion" name="recsensorial_fechaelaboracion" required>
                                                                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="col-6">
                                                                                                        <div class="form-group">
                                                                                                            <label>Persona o entidad que lo elaboró*</label>
                                                                                                            <input type="text" class="form-control" id="recsensorial_personaelaboro" name="recsensorial_personaelaboro" required>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="col-12">
                                                                                                        <div class="form-group">
                                                                                                            <label>Informe de reconocimiento *</label>
                                                                                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                                                                <div class="form-control" data-trigger="fileinput" id="documentoclienteDiv">
                                                                                                                    <i class="fa fa-file fileinput-exists"></i>
                                                                                                                    <span class="fileinput-filename"></span>
                                                                                                                </div>
                                                                                                                <span class="input-group-addon btn btn-secondary btn-file">
                                                                                                                    <span class="fileinput-new">Seleccione</span>
                                                                                                                    <span class="fileinput-exists">Cambiar</span>
                                                                                                                    <input type="file" accept="application/pdf" name="documentocliente" id="documentocliente" required>
                                                                                                                </span>
                                                                                                                <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                                                                                                    Quitar
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>

                                                                                                <div class="row">
                                                                                                    <div class="col-6">
                                                                                                        <div class="form-group">
                                                                                                            <label>Fecha de validacion*</label>
                                                                                                            <div class="input-group">
                                                                                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="recsensorial_fechavalidacion" name="recsensorial_fechavalidacion" required>
                                                                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="col-6">
                                                                                                        <div class="form-group">
                                                                                                            <label>Entidad que valido*</label>
                                                                                                            <input type="text" class="form-control" id="recsensorial_personavalido" name="recsensorial_personavalido" required>
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div class="col-12">
                                                                                                        <div class="form-group">
                                                                                                            <label>Documento de Validación *</label>
                                                                                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                                                                <div class="form-control" data-trigger="fileinput" id="documentoclientevalidacionDiv">
                                                                                                                    <i class="fa fa-file fileinput-exists"></i>
                                                                                                                    <span class="fileinput-filename"></span>
                                                                                                                </div>
                                                                                                                <span class="input-group-addon btn btn-secondary btn-file">
                                                                                                                    <span class="fileinput-new">Seleccione</span>
                                                                                                                    <span class="fileinput-exists">Cambiar</span>
                                                                                                                    <input type="file" accept="application/pdf" name="documentoclientevalidacion" id="documentoclientevalidacion" required>
                                                                                                                </span>
                                                                                                                <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                                                                                                    Quitar
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Datos del cliente -->
                                                                            <div class="col-12 mt-3 clienteblock"></div>

                                                                            <div class="col-12 " style="display: none;">
                                                                                <div class="form-group">
                                                                                    <label> Orden de trabajo / Licitacion </label>
                                                                                    <input type="text" class="form-control" id="recsensorial_ordenTrabajoLicitacion" name="recsensorial_ordenTrabajoLicitacion" placeholder="Eje: RES-OT24-XXX ó N/A" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 text-center organizacional" id="titleOrganizacionLabel">
                                                                                <h3 class="mb-2" style="font-weight: bold">La estructura organizacional depende del proyecto</h3>
                                                                            </div>


                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <div id="estructura-container" class="row mx-0">
                                                                                        <!-- Aquí se insertarán los datos -->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 mt-2 p-2 d-flex justify-content-start  clienteblock">
                                                                                <h3 class="clienteblock">¿Este informe es para el cliente seleccionado?</h3>
                                                                                <div class="form-check mx-4 clienteblock">
                                                                                    <input class="form-check-input" type="radio" name="informe_del_cliente" id="informe_del_cliente_si" value="1" checked>
                                                                                    <label class="form-check-label" for="informe_del_cliente_si">
                                                                                        Si
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check mx-4 clienteblock">
                                                                                    <input class="form-check-input" type="radio" name="informe_del_cliente" id="informe_del_cliente_no" value="0">
                                                                                    <label class="form-check-label" for="informe_del_cliente_no">
                                                                                        No
                                                                                    </label>
                                                                                </div>
                                                                            </div>


                                                                            <!--   <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Region *</label>
                                                                                    <select class="custom-select form-control" id="catregion_id" name="catregion_id" required>
                                                                                        <option value=""></option>
                                                                                        @foreach($catregion as $dato)
                                                                                        <option value="{{$dato->id}}">{{$dato->catregion_nombre}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group ">
                                                                                    <label> Subdireccion *</label>
                                                                                    <select class="custom-select form-control" id="catsubdireccion_id" name="catsubdireccion_id" required>
                                                                                        <option value=""></option>
                                                                                        @foreach($catsubdireccion as $dato)
                                                                                        <option value="{{$dato->id}}">{{$dato->catsubdireccion_siglas}} - {{$dato->catsubdireccion_nombre}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Gerencia *</label>
                                                                                    <select class="custom-select form-control" id="catgerencia_id" name="catgerencia_id" required>
                                                                                        <option value=""></option>
                                                                                        @foreach($catgerencia as $dato)
                                                                                        <option value="{{$dato->id}}">{{$dato->catgerencia_siglas}} - {{$dato->catgerencia_nombre}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Activo *</label>
                                                                                    <select class="custom-select form-control" id="catactivo_id" name="catactivo_id" required>
                                                                                        <option value=""></option>
                                                                                        @foreach($catactivo as $dato)
                                                                                        <option value="{{$dato->id}}">{{$dato->catactivo_siglas}} - {{$dato->catactivo_nombre}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div> -->
                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Empresa *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_empresa" name="recsensorial_empresa" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> R.F.C. *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_rfc" name="recsensorial_rfc" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Orden servicio </label>
                                                                                    <input type="text" class="form-control" id="recsensorial_ordenservicio" name="recsensorial_ordenservicio" placeholder="Eje: CEN-004-2022 ó N/A">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Repr. legal *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_representantelegal" name="recsensorial_representantelegal" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Repr. Seg. Industrial *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_representanteseguridad" name="recsensorial_representanteseguridad" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Instalación *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_instalacion" name="recsensorial_instalacion" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Dirección de la instalación *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_direccion" name="recsensorial_direccion" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Código postal *</label>
                                                                                    <input type="number" class="form-control" id="recsensorial_codigopostal" name="recsensorial_codigopostal" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Coordenadas *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_coordenadas" name="recsensorial_coordenadas" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Actividad principal *</label>
                                                                                    <textarea class="form-control" rows="4" id="recsensorial_actividadprincipal" name="recsensorial_actividadprincipal" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Descripción del proceso en la instalación *</label>
                                                                                    <textarea class="form-control" rows="4" id="recsensorial_descripcionproceso" name="recsensorial_descripcionproceso" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Observación sobre el horario del personal *</label>
                                                                                    <textarea class="form-control" rows="3" id="recsensorial_obscategorias" name="recsensorial_obscategorias" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Fecha inicio del reconocimiento *</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="recsensorial_fechainicio" name="recsensorial_fechainicio" required>
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Fecha fin del reconocimiento *</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="recsensorial_fechafin" name="recsensorial_fechafin" required>
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 " id="requiere_fisicos">
                                                                                <div class="col-lg-12 col-sm-1" id="tituloPersonaElabora" style="display: none;">
                                                                                    <h3><i class="fa fa-users"></i> Persona que elabora / proporciona los datos del reconocimiento sensorial *</h3>
                                                                                </div>
                                                                                <!-- <div class="form-group">
                                                                                    <label> Persona que elabora / proporciona los datos del reconocimiento sensorial *</label>
                                                                                    <input type="text" class="form-control" id="recsensorial_elabora1" name="recsensorial_elabora1" required>
                                                                                </div> -->

                                                                                <button type="button" class="btn btn-danger botonagregarElaborador mb-3" id="botonagregarElaborador">Agregar persona que elabora <i class="fa fa-user-circle"></i></button>

                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 clienteblock">
                                                                <div class="row">
                                                                    <div class="col-12" id="seccion_foto_ubicacion">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: -4px; margin-left: 160px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
                                                                                <h4 class="card-title">Mapa ubicación *</h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotomapa" name="inputfotomapa" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 clienteblock" id="seccion_foto_plano">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: -4px; margin-left: 160px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar plano instalación" id="boton_descargarplanoinstalacion"></i>
                                                                                <h4 class="card-title">Plano instalación *</h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotoplano" name="inputfotoplano" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 clienteblock" id="seccion_foto_instalacion">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: -4px; margin-left: 160px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto instalación" id="boton_descargarfotoinstalacion"></i>
                                                                                <h4 class="card-title">Foto instalación *</h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotoinstalacion" name="inputfotoinstalacion" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>
                                                        {{-- <div class="row" style="display: none;" id="seccion_quimicos"> --}}
                                                        <div class="row" id="finalizarQuimico">
                                                            <div class="col-8 seccion_quimicos">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">Datos para reconocimiento de químicos</h4>
                                                                        <h6 class="card-subtitle text-white m-b-0 op-5">&nbsp;</h6>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label> Persona que elabora / proporciona los datos del reconocimiento de químicos </label>
                                                                                    <input type="text" class="form-control" id="recsensorial_elabora2" name="recsensorial_elabora2">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="text-danger">¿Rec. químico validado? </label>
                                                                                <div class="switch">
                                                                                    <label>
                                                                                        No<input type="checkbox" id="recsensorial_quimicovalidado" name="recsensorial_quimicovalidado" onchange="quimicovalido(this);">
                                                                                        <span class="lever switch-col-light-blue" id="checkbox_validaquimicos"></span>Si
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                <div class="form-group">
                                                                                    <label> Persona que valida </label>
                                                                                    <input type="text" class="form-control" id="recsensorial_quimicopersonavalida" name="recsensorial_quimicopersonavalida">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <div class="form-group">
                                                                                    <label> Fecha validación </label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="recsensorial_quimicofechavalidacion" name="recsensorial_quimicofechavalidacion">
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                <div class="form-group">
                                                                                    <label> Nuevo PDF validación </label>
                                                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                                        <div class="form-control" data-trigger="fileinput" id="campo_file_quimicos">
                                                                                            <i class="fa fa-file fileinput-exists"></i>
                                                                                            <span class="fileinput-filename"></span>
                                                                                        </div>
                                                                                        <span class="input-group-addon btn btn-secondary btn-file">
                                                                                            <span class="fileinput-new">Seleccione</span>
                                                                                            <span class="fileinput-exists">Cambiar</span>
                                                                                            <input type="file" accept="application/pdf" name="pdfvalidaquimicos" id="pdfvalidaquimicos">
                                                                                        </span>
                                                                                        <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 seccion_quimicos">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">PDF químico validación</h4>
                                                                        <h6 class="card-subtitle text-white m-b-0 op-5">&nbsp;</h6>
                                                                        <div class="row">
                                                                            <div class="col-12" style="height: 286px; max-height: 286px; text-align: center;">
                                                                                <iframe src="/assets/images/nada.jpg" id="visor_pdfquimicos" style="width: 100%; height: 280px;">No disponible</iframe>
                                                                                <i class="fa fa-file-excel-o" id="visor_pdfquimicos_icono" style="font-size: 260px;"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Botones de envio y desactivacion -->
                                                        <div class="row">
                                                            <div class="col-4">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                                <div class="form-group" style="text-align: left;">
                                                                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_reconocimiento" value="0" onclick="bloqueo_reconocimiento(this.value);">
                                                                        <span class="btn-label"><i class="fa fa-unlock"></i></span> Reconocimento actualmente desbloqueado para edición
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-4">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="button" class="btn btn-success" style="background-color: #5FB404!important;" id="boton_autorizar_recsensorial">
                                                                        Autorizar reconocimiento <i class="fa fa-gavel" aria-hidden="true"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-4">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" id="boton_guardar_recsensorial">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab2">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Categoría personal </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva categoría" id="boton_nueva_categoria" style="margin-left: auto;">
                                                                    Categoría Personal <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialcategorias">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 100px !important;">No.</th> --}}
                                                                            <th style="width: 150px!important;">Departamento</th>
                                                                            <th>Categoría</th>
                                                                            <th style="width: 100px!important;">Tipo</th>
                                                                            <th style="width: 100px!important;">Editar</th>
                                                                            <th style="width: 100px!important;">Eliminar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="8">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab3">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva área" id="boton_nueva_area">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Área instalación
                                                                </button>
                                                                <button type="button" class="btn btn-success waves-effect boton_descarga_poe" style="float: right;" data-toggle="tooltip" title="Descargar tabla POE.docx">
                                                                    <span class="btn-label"><i class="fa fa-file-word-o"></i></span>Descargar tabla POE .docx
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialareas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            <th>Área</th>
                                                                            <th>Factores de riesgos</th>
                                                                            <th>Categorías</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                            <th style="width: 80px!important;">Eliminar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="5">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab4">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa fa-industry"></i> Fuentes generadoras </h2>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Mapa fuentes generadoras" id="boton_mapa_maquina" style="margin-left: 20px; display: none">
                                                                    Mapa <i class="fa fa-map"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva maquinaria" id="boton_nueva_maquina" style="margin-left: auto;">
                                                                    Fuente generadora <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialmaquinas">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 100px !important;">No.</th> --}}
                                                                            <th>Área</th>
                                                                            <th>Fuente generadora</th>
                                                                            <th>Riesgos provocados</th>
                                                                            <th style="width: 110px!important;">Cantidad</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                            <th style="width: 80px!important;">Eliminar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="5">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 5-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab5">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-handshake-o"></i> E.P.P </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva equipo de protección personal" id="boton_nueva_equipopp" style="margin-left: auto;">
                                                                    E.P.P <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialequipopp">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 80px!important;">No.</th>
                                                                            <th>Categoría</th>
                                                                            <th>Equipo de protección personal</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                            <th style="width: 80px!important;">Eliminar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="5">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 6-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab6">
                                                <div class="multisteps-form__content">
                                                    <form enctype="multipart/form-data" method="post" name="form_responsables" id="form_responsables">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                {{-- {!! method_field('PUT') !!} --}}
                                                                {!! csrf_field() !!}
                                                            </div>
                                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                                <ol class="breadcrumb m-b-10 text-light">
                                                                    Responsables del informe de reconocimiento sensorial
                                                                </ol>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable Técnico del informe</label>
                                                                            <input type="text" class="form-control" id="recsensorial_repfisicos1nombre" name="recsensorial_repfisicos1nombre" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable Técnico del informe</label>
                                                                            <select class="custom-select form-control" id="recsensorial_repfisicos1cargo" name="recsensorial_repfisicos1cargo" required>
                                                                                <option value=""></option>
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable Técnico del informe</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="fisicosresponsabletecnico" name="fisicosresponsabletecnico" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable del Contrato/Proyecto </label>
                                                                            <input type="text" class="form-control" id="recsensorial_repfisicos2nombre" name="recsensorial_repfisicos2nombre" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable del Contrato/Proyecto</label>
                                                                            <select class="custom-select form-control" id="recsensorial_repfisicos2cargo" name="recsensorial_repfisicos2cargo" required>
                                                                                <option value=""></option>
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable del Contrato/Proyecto</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="fisicosresponsableadministrativo" name="fisicosresponsableadministrativo" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                                <ol class="breadcrumb m-b-10 text-light">
                                                                    Responsables del informe de reconocimiento de químicos.
                                                                </ol>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable Técnico del informe</label>
                                                                            <input type="text" class="form-control" id="recsensorial_repquimicos1nombre" name="recsensorial_repquimicos1nombre" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable Técnico del informe</label>
                                                                            <select class="custom-select form-control" id="recsensorial_repquimicos1cargo" name="recsensorial_repquimicos1cargo" required>
                                                                                <option value=""></option>
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable Técnico del informe</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="quimicosresponsabletecnico" name="quimicosresponsabletecnico" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable del Contrato/Proyecto</label>
                                                                            <input type="text" class="form-control" id="recsensorial_repquimicos2nombre" name="recsensorial_repquimicos2nombre" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable del Contrato/Proyecto</label>
                                                                            <select class="custom-select form-control" id="recsensorial_repquimicos2cargo" name="recsensorial_repquimicos2cargo" required>
                                                                                <option value=""></option>
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable del Contrato/Proyecto</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="quimicosresponsableadministrativo" name="quimicosresponsableadministrativo" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" id="boton_guardar_responsables">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!--STEP 7-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab7">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar anexo al reconocimiento" id="boton_nuevo_anexo">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Anexo
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialanexos">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 120px!important;">Tipo informe</th>
                                                                            <th>Laboratorio o Nombre del anexo</th>
                                                                            <th style="width: 80px!important;">No.</th>
                                                                            <th>Entidad</th>
                                                                            <th>Numero</th>
                                                                            <th style="width: 120px!important;">Vigencia</th>
                                                                            <th style="width: 80px!important;">Mostrar</th>
                                                                            <th style="width: 80px!important;">Eliminar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="8">&nbsp;</td>
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
                        <!-- ============= /STEPS ============= -->
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_3" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_folios">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;" class="div_reconocimiento_alcance">Reconocimiento</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Instalación</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-industry"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body bg-secondary">
                                    <h4 class="text-white card-title">Agente / Factor de riesgo / Servicio</h4>
                                    <h6 class="card-subtitle text-white">Reconocimiento sensorial</h6>
                                </div>
                                <div class="card-body" style="border: 0px #f00 solid;">
                                    <div class="message-box contact-box">
                                        <!-- Nav tabs -->
                                        <div class="vtabs" style="width: 100% !important">
                                            <ul class="nav nav-tabs tabs-vertical" role="tablist" style="border-right: none;">
                                                @foreach($catprueba as $dato)
                                                <li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">
                                                    <a class="nav-link link_menuparametro" data-toggle="tab" role="tab" href="#" id="menutab_parametro_{{$dato->id}}" onclick="mostrar_vista_parametro('{{$dato->catPrueba_Nombre}}', form_recsensorial.recsensorial_id.value, {{$dato->id}});">
                                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                                        <span class="hidden-xs-down">{{$dato->catPrueba_Nombre}}</span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- /Nav tabs -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card">
                                <div class="card-body bg-info">
                                    <h4 class="text-white card-title" id="titulo_parametro">Parámetro</h4>
                                    <h6 class="card-subtitle text-white">Puntos a evaluar</h6>
                                </div>
                                <div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
                                    <div class="message-box contact-box">
                                        <!-- Tab panes -->
                                        <div class="tab-content" id="forms_parametro">
                                        </div>
                                        <!-- /Tab panes -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_6" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_folios">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;" class="div_reconocimiento_alcance">Reconocimiento</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Instalación</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-industry"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card" id="tablaTipoInstalacion">
                                <div class="card-body">
                                    <h4 class="card-title">Tipo de instalación</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <style type="text/css" media="screen">
                                                .tabla_tipoinstalacion th {
                                                    padding: 0px !important;
                                                    height: 32px !important;
                                                    vertical-align: middle !important;
                                                    text-align: center !important;
                                                    font-size: 12px !important;
                                                }

                                                .tabla_tipoinstalacion td {
                                                    width: 20% !important;
                                                    padding: 0px !important;
                                                    height: 40px !important;
                                                    vertical-align: middle !important;
                                                    text-align: left !important;
                                                    /*font-size: 12px!important;*/
                                                }

                                                .tabla_tipoinstalacion .round {
                                                    width: 32px;
                                                    height: 32px;
                                                    padding: 0px !important;
                                                    margin: 0px;
                                                }

                                                .tabla_tipoinstalacion .round i {
                                                    /*margin: 0px!important;*/
                                                    position: absolute;
                                                    margin: 10px 0px 0px -9px;
                                                    font-style: normal;
                                                    font-size: 12px !important;
                                                    font-weight: 600 !important;
                                                    line-height: 12px !important;
                                                }

                                                .tabla_tipoinstalacion h3 {
                                                    font-size: 12px !important;
                                                    font-weight: 700px;
                                                    line-height: 16px;
                                                }

                                                .tabla_tipoinstalacion h5 {
                                                    font-size: 12px !important;
                                                    font-weight: 700px;
                                                    line-height: 16px;
                                                }
                                            </style>
                                            <table class="table table-bordered tabla_tipoinstalacion tabla_tipoinstalacionpuntos" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="5">Agente / Factor de riesgo / Servicio</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #0080FF; float: left;"><i>XC</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra chica</h3>
                                                                <h5 class="text-muted m-b-0">1-20 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #5FB404; float: left;"><i>CH</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Chica</h3>
                                                                <h5 class="text-muted m-b-0">21-40 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FFD700; float: left;"><i>MD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Mediana</h3>
                                                                <h5 class="text-muted m-b-0">41-80 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FF8000; float: left;"><i>GD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Grande</h3>
                                                                <h5 class="text-muted m-b-0">81-150 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;" class="instalacion_xg">
                                                            <div class="round" style="background-color: #DF0101; float: left;"><i>XG</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra grande</h3>
                                                                <h5 class="text-muted m-b-0">151-250 puntos</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-bordered tabla_tipoinstalacion" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="5">Infraestructura para servicios al personal</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #0080FF; float: left;"><i>XC</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra chica</h3>
                                                                <h5 class="text-muted m-b-0">2-4 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #5FB404; float: left;"><i>CH</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Chica</h3>
                                                                <h5 class="text-muted m-b-0">5-10 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FFD700; float: left;"><i>MD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Mediana</h3>
                                                                <h5 class="text-muted m-b-0">11-30 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FF8000; float: left;"><i>GD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Grande</h3>
                                                                <h5 class="text-muted m-b-0">31-49 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #DF0101; float: left;"><i>XG</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra grande</h3>
                                                                <h5 class="text-muted m-b-0">50-70 personas</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Puntos de muestreos proporcionados por el cliente</h4>
                                    <form enctype="multipart/form-data" method="post" name="form_agentesclientelista" id="form_agentesclientelista"> {{-- action="{{route('recsensorialagentescliente')}}" --}}
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="col-12">
                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI']))
                                                <ol class="breadcrumb m-b-10">
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_modulorecsensorial" style="width: auto;" id="boton_nuevo_agentescliente">
                                                        {{-- <button type="button" class="btn btn-block btn-outline-secondary" style="width: auto;" id="boton_nuevo_agentescliente"> --}}
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Agente / Factor de riesgo / Servicio
                                                    </button>
                                                </ol>
                                                @endif
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_agentesclientes" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 240px!important;">Agente / Factor de riesgo / Servicio</th>
                                                                <th style="width: 70px!important;">Total</th>
                                                                <th style="width: 120px!important;">Tipo instalación</th>
                                                                <th>Análisis</th>
                                                                <th>Observación</th>
                                                                <th style="width: 60px!important;">Editar</th>
                                                                <th style="width: 60px!important;">Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                            <div class="col-12">
                                                <div class="form-group" style="text-align: right;">
                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" id="botonguardar_agentescliente">
                                                        {{-- <button type="submit" class="btn btn-danger" id="botonguardar_agentescliente"> --}}
                                                        Guardar (<span id="total_agentecliente">0</span>) registros <i class="fa fa-save"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_4" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_folios">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;" class="div_reconocimiento_alcance">Reconocimiento</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Instalación</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-industry"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <ol class="breadcrumb m-b-10">
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nueva evidencia <br> fotográfica / Plano" data-html="true" id="boton_nueva_fotoevidenciaquimicos">
                            <span class="btn-label"><i class="fa fa-plus"></i></span>Evidencia fotográfica / Planos
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
                                <div class="card-body cardbody_galeriaquimicos">
                                    <style type="text/css">
                                        #image-popups .foto_galeria:hover i {
                                            opacity: 1 !important;
                                            cursor: pointer;
                                        }
                                    </style>
                                    <div class="row galeriaquimicos" id="image-popups" style="height: auto; max-height: 140px; overflow-y: auto; overflow-x: none;">
                                        {{--
                                        <div class="col-1 foto_galeria">
                                            <span style="font-family: 'Arial Narrow'; font-size: 0.85vw; color: #FFFFFF; text-shadow: 0 0 3px #000000, 0 0 3px #000000; position: absolute; left: 16px;">Motogeneradores...</span>
                                            <i class="fa fa-trash text-danger" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 24px;" data-toggle="tooltip" title="Eliminar" onclick="foto_eliminar(0);"></i>
                                            <i class="fa fa-download text-success" style="font-size: 26px; text-shadow: 2px 2px 4px #000000; opacity: 0; position: absolute; top: 60px;" data-toggle="tooltip" title="Descargar" onclick="foto_descargar(0);"></i>
                                            <a href="/recsensorialevidenciafotomostrar/1/1" data-effect="mfp-zoom-in">
                                                <img class="d-block img-fluid" src="/recsensorialevidenciafotomostrar/1/1" style="margin: 0px;" data-toggle="tooltip" title="Click para mostrar foto"/>
                                            </a>
                                        </div>
                                         --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <!-- ============= STEPS ============= -->
                        <div style="min-width: 700px; width: 100% margin: 0px auto;">
                            <!--multisteps-form-->
                            <div class="multisteps-form-3">
                                <!--progress bar-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__progress-3" style="padding-top: 30px;">
                                            <div class="multisteps-form__progress-btn-3 js-active" id="steps3_menu_tab1">
                                                <i class="fa fa-file-text-o"></i><br>
                                                <span>Inventario de sustancias</span>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab2">
                                                <i class="fa fa-flask"></i><br>
                                                <span>Prioridad de muestreo sustancias</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab3">
                                                <i class="fa fa-filter"></i><br>
                                                <span>Determinación de los GEH</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab4">
                                                <i class="fa fa fa-users"></i><br>
                                                <span>Grupos de exposición homogénea</span>
                                            </div>
                                            @endif
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab5">
                                                <i class="fa fa-list-ol"></i><br>
                                                <span>Puntos de muestreo y POE</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--form panels-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__form-3">
                                            <!--STEP 1-->
                                            <div class="multisteps-form__panel-3 js-active" data-animation="scaleIn" id="steps3_contenido_tab1">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar inventario de sustancia" id="boton_nueva_sustacia">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Inventario de sustancias por área
                                                                </button>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_recsensorialquimicos_inventario">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 80px!important;">Eliminar</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                            <th>Áreas</th>
                                                                            <th>Categorías</th>
                                                                            <th>Sustancias</th>
                                                                            <th>Componentes</th>
                                                                            <th style="width: 120px!important;">Est. físico</th>
                                                                            <th style="width: 120px!important;">Cantidad</th>
                                                                            <th style="width: 140px!important;">Tiempo exp.</th>
                                                                            <th style="width: 140px!important;">Frecuencia</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="12">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab2">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">Determinación de la prioridad de muestreo de las sustancias químicas.</ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_1">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 80px!important;">No</th> --}}
                                                                            <th>Área</th>
                                                                            <th>Sustancia<br>química</th>
                                                                            <th style="width: 160px!important;">Ponderación<br>Cantidad manejada</th>
                                                                            <th style="width: 170px!important;">Ponderación<br>Clasificación de riesgo</th>
                                                                            <th style="width: 160px!important;">Ponderación<br>Volatilidad sustancia</th>
                                                                            <th style="width: 100px!important;">Suma total<br>ponderación</th>
                                                                            <th style="width: 120px!important;">Prioridad de<br>muestreo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="9">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab3">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">Determinación de los grupos de exposición homogénea.</ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_2">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 80px!important;">No</th> --}}
                                                                            <th>Área</th>
                                                                            <th>Grupo de expo. homogénea</th>
                                                                            <th>Sustancia</th>
                                                                            <th style="width: 130px!important;">Vía de ingreso<br>al organismo</th>
                                                                            <th style="width: 130px!important;">Numero de<br>POE expuesto</th>
                                                                            <th style="width: 130px!important;">Tiempo de<br>Exposición</th>
                                                                            <th style="width: 100px!important;">Suma total<br>ponderación</th>
                                                                            <th style="width: 120px!important;">Prioridad de<br>muestreo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="9">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab4">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">Grupos de exposición homogénea.</ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_3">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 80px!important;">No</th> --}}
                                                                            <th>Área</th>
                                                                            <th>Grupo de expo.<br>homogénea</th>
                                                                            <th>Sustancia<br>química</th>
                                                                            <th style="width: 120px!important;">Numero de<br>trabajadores</th>
                                                                            <th style="width: 160px!important;">Tiempo de<br>Exposición (minutos)</th>
                                                                            <th style="width: 160px!important;">Frecuencia de<br>Exposición (jornada)</th>
                                                                            <th style="width: 160px!important;">Tiempo total<br>Exposición (minutos)</th>
                                                                            <th style="width: 130px!important;">Jornada de<br>trabajo (horas)</th>
                                                                            <th style="width: 120px!important;">Prioridad de<br>muestreo</th>
                                                                            <th style="width: 120px!important;">Num. POE a<br>Considerar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="11">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!--STEP 5-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab6">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">Personal ocupacionalmente expuesto a considerar para el muestreo.</ol>
                                                            <div class="table-responsive">
                                                                <style type="text/css">
                                                                    #tabla_quimicosresumen_4 th {
                                                                        border: 1px #F1F1F1 solid;
                                                                        padding: 4px 6px !important;
                                                                        text-align: center;
                                                                        vertical-align: middle;
                                                                    }
                                                                </style>
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_4">
                                                                    <thead>
                                                                        <tr>
                                                                            <th rowspan="2" style="vertical-align: middle!important;">Grupo de expo. homogénea</th>
                                                                            <th width="250" rowspan="2" style="vertical-align: middle!important;">Agente químico</th>
                                                                            <th width="360" colspan="2">Número de puntos por POE / punto a considerar</th>
                                                                            <th width="180" rowspan="2" style="vertical-align: middle!important;">Total muestras</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th width="180">Promedio Ponderado de Tiempo (<b>PPT</b>)</th>
                                                                            <th width="180">Corto Tiempo<br>(<b>CT</b>)</th>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============= /STEPS ============= -->
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_5" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_folios">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;" class="div_reconocimiento_alcance">Reconocimiento</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_reconocimiento_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Instalación</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-industry"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card" id="tablaTipoInstalacion1">
                                <div class="card-body">
                                    <h4 class="card-title">Tipo de instalación</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered tabla_tipoinstalacion tabla_tipoinstalacionpuntos" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="5">Agente / Factor de riesgo / Servicio</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #0080FF; float: left;"><i>XC</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra chica</h3>
                                                                <h5 class="text-muted m-b-0">1-20 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #5FB404; float: left;"><i>CH</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Chica</h3>
                                                                <h5 class="text-muted m-b-0">21-40 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FFD700; float: left;"><i>MD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Mediana</h3>
                                                                <h5 class="text-muted m-b-0">41-80 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FF8000; float: left;"><i>GD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Grande</h3>
                                                                <h5 class="text-muted m-b-0">81-150 puntos</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;" class="instalacion_xg">
                                                            <div class="round" style="background-color: #DF0101; float: left;"><i>XG</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra grande</h3>
                                                                <h5 class="text-muted m-b-0">151-250 puntos</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-bordered tabla_tipoinstalacion" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="5">Infraestructura para servicios al personal</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #0080FF; float: left;"><i>XC</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra chica</h3>
                                                                <h5 class="text-muted m-b-0">2-4 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #5FB404; float: left;"><i>CH</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Chica</h3>
                                                                <h5 class="text-muted m-b-0">5-10 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FFD700; float: left;"><i>MD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Mediana</h3>
                                                                <h5 class="text-muted m-b-0">11-30 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #FF8000; float: left;"><i>GD</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Grande</h3>
                                                                <h5 class="text-muted m-b-0">31-49 personas</h5>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%!important;">
                                                            <div class="round" style="background-color: #DF0101; float: left;"><i>XG</i></div>
                                                            <div class="m-l-10 align-self-center" style="float: left;">
                                                                <h3 class="m-b-0 font-light">Extra grande</h3>
                                                                <h5 class="text-muted m-b-0">50-70 personas</h5>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" id="seccion_resumen_fisicos">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'CoordinadorHI']))
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post" enctype="multipart/form-data" name="form_reconocimientofisicos_pdf" id="form_reconocimientofisicos_pdf">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                {!! csrf_field() !!}
                                                                <label style="font-size: 16px;">Rec. físicos autorizado PDF *</label>
                                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                    <div class="form-control" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>
                                                                        <span class="fileinput-filename"></span>
                                                                    </div>
                                                                    <span class="input-group-addon btn btn-secondary btn-file">
                                                                        <span class="fileinput-new">Seleccione</span>
                                                                        <span class="fileinput-exists">Cambiar</span>
                                                                        <input type="file" accept="application/pdf" id="reconocimientofisicospdf" name="reconocimientofisicospdf" required>
                                                                    </span>
                                                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                                                </div>
                                                            </td>
                                                            <td width="110" style="text-align: right; vertical-align: bottom;">
                                                                {{-- <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" style="height: 38px;" id="boton_guardar_reconocimientofisicospdf"> --}}
                                                                <button type="submit" class="btn btn-danger" style="height: 38px;" id="boton_guardar_reconocimientofisicospdf">
                                                                    Guardar <i class="fa fa-cloud-upload"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        Resumen rec. sensorial
                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'CoordinadorHI', 'ApoyoTecnico']))
                                        {{-- <button type="button" class="btn btn-info" style="float: right; background: #000!important; margin-left: 20px;" data-toggle="tooltip" title="Generar informe de reconocimiento sensorial .doc" onclick="reporte1(form_recsensorial.recsensorial_id.value);">
                                                Generar&nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                                            </button> --}}

                                        <button type="button" class="btn btn-info" style="float: right; background: #000!important; margin-left: 20px; display: none;" data-toggle="tooltip" title="Descargar informe de reconocimiento sensorial .doc" id="boton_descargarfisicosdoc" onclick="reporte(form_recsensorial.recsensorial_id.value, 1, this);">
                                            Descargar word&nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-info" style="float: right; background: #000!important; display: none;" data-toggle="tooltip" title="Descargar informe de reconocimiento sensorial autorizado .pdf" id="boton_descargarfisicospdf">
                                            Descargar pdf&nbsp;&nbsp;<i class="fa fa-file-pdf-o fa-1x"></i>
                                        </button>
                                    </h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-hover stylish-table" width="100%" id="tabla_recsensorial_resumen">
                                                <thead>
                                                    <tr>
                                                        <th>Factores de riesgos / Servicios</th>
                                                        <th style="width:80px!important;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" id="seccion_resumen_quimicos">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post" enctype="multipart/form-data" name="form_reconocimientoquimicos_pdf" id="form_reconocimientoquimicos_pdf">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                {!! csrf_field() !!}
                                                                <label style="font-size: 16px;">Rec. químicos autorizado PDF *</label>
                                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                    <div class="form-control" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>
                                                                        <span class="fileinput-filename"></span>
                                                                    </div>
                                                                    <span class="input-group-addon btn btn-secondary btn-file">
                                                                        <span class="fileinput-new">Seleccione</span>
                                                                        <span class="fileinput-exists">Cambiar</span>
                                                                        <input type="file" accept="application/pdf" id="reconocimientoquimicospdf" name="reconocimientoquimicospdf" required>
                                                                    </span>
                                                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                                                </div>
                                                            </td>
                                                            <td width="110" style="text-align: right; vertical-align: bottom;">
                                                                {{-- <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" style="height: 38px;" id="boton_guardar_reconocimientoquimicospdf"> --}}
                                                                <button type="submit" class="btn btn-danger" style="height: 38px;" id="boton_guardar_reconocimientoquimicospdf">
                                                                    Guardar <i class="fa fa-cloud-upload"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        Resumen rec. químicos

                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                                        <button type="button" class="btn btn-info" style="float: right; background: #000!important; margin-left: 20px;" data-toggle="tooltip" title="Configurar informe sensorial de químicos .doc" id="boton_editarInforme">
                                            Configurar informe (word)&nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-info" style="float: right; background: #000!important; display: none;" data-toggle="tooltip" title="Descargar informe de reconocimiento químicos autorizado .pdf" id="boton_descargarquimicospdf">
                                            Descargar pdf&nbsp;&nbsp;<i class="fa fa-file-pdf-o fa-1x"></i>
                                        </button>

                                    </h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive" id="tabla_resumen_puntos">
                                                <table class="table table-hover stylish-table" width="100%" id="tabla_recsensorialquimicos_resumen">
                                                    <thead>
                                                        <tr>
                                                            <th>Agentes</th>
                                                            <th style="width:80px!important;">Total</th>
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
                    </div>
                </div>
                <!-- ============= /tab_9 ============= -->


                <div class="tab-pane p-9" id="tab_9" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>

                                                <td style="border: none;">
                                                    <div class="tab-pane" id="tabla_proporcionado_clientes" role="tab_9">
                                                        <div class="card-body">
                                                            <form enctype="multipart/form-data" method="post" name="form_proporcionadocliente" id="form_proporcionadocliente">
                                                                {!! csrf_field() !!}
                                                                <div class="row">
                                                                    <div class="col-2 text-center">
                                                                        <h4>Área</h4>
                                                                    </div>
                                                                    <div class="col-2 text-center">
                                                                        <h4>Grupo de exposición</h4>
                                                                    </div>
                                                                    <div class="col-2 text-center">
                                                                        <h4>Producto</h4>
                                                                    </div>
                                                                    <div class="col-3 text-center">
                                                                        <h4>Componente a evaluar</h4>
                                                                    </div>
                                                                    <div class="col-1 text-center">
                                                                        <h4>PPT</h4>
                                                                    </div>
                                                                    <div class="col-1 text-center">
                                                                        <h4>CT</h4>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <h4>Puntos totales</h4>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div id="div_proporcionadocliente">
                                                                    <div class="row padrepropocionadocliente">
                                                                        <div class="col-2">
                                                                            <div class="form-group mb-1">
                                                                                <input type="text" class="form-control AREAPROP" name="AREA_PROPORCIONADACLIENTE[]" id="AREA_PROPORCIONADACLIENTE">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-2">
                                                                            <div class="form-group mb-1">
                                                                                <input type="text" class="form-control CATEGORIAPROP" name="CATEGORIA_PROPORCIONADACLIENTE[]" id="CATEGORIA_PROPORCIONADACLIENTE">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-2 ">
                                                                            <div class="form-group mb-1">
                                                                                <input type="text" class="form-control PRODUCTOPROP" name="PRODUCTO_PROPORCIONADACLIENTE[]" id="PRODUCTO_PROPORCIONADACLIENTE">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-3">
                                                                            <div class="form-group">
                                                                                <select class="custom-select form-control SUSTANCIAS1" name="SUSTANCIA_ID[]">
                                                                                    <option selected value=""></option>

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-1 text-center">
                                                                            <input type="number" class="form-control  PPTPROP" name="PPT_PROPORCIONADACLIENTE[]" id="PPT_PROPORCIONADACLIENTE" style="border:1px solid #94B732; font-size:20px" required>
                                                                        </div>
                                                                        <div class="col-1 text-center">
                                                                            <input type="number" class="form-control CTPROP" name="CT_PROPORCIONADACLIENTE[]" id="CT_PROPORCIONADACLIENTE" style="border:1px solid  #94B732;; font-size:20px" required>
                                                                        </div>
                                                                        <div class="col-1 text-center">
                                                                            <input type="number" class="form-control text-center PUNTOSPROP" name="PUNTOS_PROPORCIONADACLIENTE[]" id="PUNTOS_PROPORCIONADACLIENTE" style="font-size:20px" readonly required>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-center align-content-center">
                                                                    <button type="button" class="btn btn-info mt-4 mx-1" style="background: #FC4B6C!important; width: 5%;" data-toggle="tooltip" title="Eliminar registro" id="boton_eliminarTablapropocionadocliente">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info mt-4" style="background: #94B732!important; width: 10%;" data-toggle="tooltip" title="Agregar punto de muestreo" id="boton_agregarTablapropocionadocliente">
                                                                        Agregar <i class="fa fa-plus"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info mt-4 mx-4" style="background: #FC4B6C!important; width: 30%;" data-toggle="tooltip" title="Guardar Puntos proporcionado por el cliente" id="boton_guardarTablapropocionadocliente">
                                                                        Guardar <i class="fa fa-cloud-upload"></i>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
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


<!-- ============================================================== -->
<!-- MODALES RECONOCIMIENTO SENSORIAL -->
<!-- ============================================================== -->
<div id="modal_categoria" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_categoria" id="form_categoria">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Categoría del personal</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="categoria_id" name="categoria_id" value="0">
                            <input type="hidden" class="form-control" id="categoria_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Departamento *</label>
                                <select class="custom-select form-control" id="catdepartamento_id" name="catdepartamento_id" required>
                                    <option value=""></option>
                                    @foreach($catdepartamento as $dato)
                                    <option value="{{$dato->id}}">{{$dato->catdepartamento_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label> Nombre categoría *</label>
                                <input type="text" class="form-control" name="recsensorialcategoria_nombrecategoria" id="recsensorialcategoria_nombrecategoria" placeholder="ejem. Técnico de laboratorio electrónico" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Tipo puesto*</label>
                                <select class="custom-select form-control" id="catmovilfijo_id" name="catmovilfijo_id" required>
                                    <option value=""></option>
                                    @foreach($catmovilfijo as $dato)
                                    <option value="{{$dato->id}}">{{$dato->catmovilfijo_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row listadodeturno"></div>

                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-danger botonagregarContacto" id="botonagregarhorario">Agregar turno <i class="fa  fa-clock-o"></i></button>
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_categoria">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>



<div id="modal_datosInforme" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 99%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Configuración de datos para el informe</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1_info" id="tab1_informe_info" role="tab">Datos generales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2_tabla" role="tab" id="tab2_informe_tabla">Puntos de muestreo y POE</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" style="display: none;" href="#tab3_tabla_cliente" role="tab" id="tab3_informe_tabla_cliente">Puntos de muestreo adicionales solicitados por el cliente</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab4_cambios" role="tab" id="tab4_control_cambios" style="display: none">Control de cambios</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- tab 1 -->
                                <div class="tab-pane active" id="tab1_info" role="tabpanel">
                                    <div class="card-body">
                                        <form enctype="multipart/form-data" method="post" name="form_recInforme" id="form_recInforme">
                                            {!! csrf_field() !!}
                                            <div class="row">

                                                <div class="col-12">
                                                    <input type="hidden" class="form-control" id="ID_RECURSO_INFORME" name="ID_RECURSO_INFORME" value="0">
                                                    <input type="hidden" class="form-control" id="RECSENSORIAL_ID_INFORME" name="RECSENSORIAL_ID" value="0">
                                                </div>

                                                <div class="col-4">
                                                    <label> Imagen Portada * </label>
                                                    <div class="form-group">
                                                        <style type="text/css" media="screen">
                                                            .dropify-wrapper {
                                                                height: 500px !important;
                                                                /*tamaño estatico del campo foto*/
                                                            }
                                                        </style>
                                                        <input type="file" accept="image/jpeg,image/x-png" id="PORTADA" name="PORTADA" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                    </div>
                                                </div>


                                                <div class="col-8">
                                                    <div class="form-group">
                                                        <label> Introducción * </label>
                                                        <textarea class="form-control" rows="14" id="INTRODUCCION" name="INTRODUCCION"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Metodología * </label>
                                                        <textarea class="form-control" rows="3" id="METODOLOGIA" name="METODOLOGIA"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Conclusiones * </label>
                                                        <select class="custom-select form-control mb-1" style="width: 100%;" id="ID_CATCONCLUSION" name="ID_CATCONCLUSION">
                                                            <option value="">&nbsp;</option>
                                                            @foreach($catConclusiones as $dato)
                                                            <option value="{{$dato->ID_CATCONCLUSION}}" data-descripcion="{{$dato->DESCRIPCION}}">{{$dato->NOMBRE}}</option>
                                                            @endforeach
                                                        </select>
                                                        <textarea class="form-control" rows="8" id="CONCLUSION" name="CONCLUSION" readonly></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 mx-2">
                                                    <label class="text-danger">¿El cliente requiere puntos de muestreo adicionales? </label>
                                                    <div class="switch">
                                                        <label>
                                                            No<input type="checkbox" id="PETICION_CLIENTE" name="PETICION_CLIENTE" value="1">
                                                            <span class="lever switch-col-light-blue" id="checkbox_PETICIONCLIENTE"></span>Si
                                                        </label>
                                                    </div>
                                                </div>

                                                <h3 class="mx-4 mt-3">Seleccione las opciones que desee mostrar en la Portada del Informe (Instalación)</h3>
                                                <div class="row mx-1" id="opcionesPortada">

                                                </div>

                                                <!-- <div class="col-12">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_puntos" name="REGION" id="chekbox_puntos_region" value="1">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;" id="puntoRegion"></label>
                                                </div>

                                                <div class="col-12">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_puntos" name="SUBDIRRECCION" id="chekbox_puntos_subdirreccion" value="1">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;" id="puntoSubdirreccion"></label>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_puntos" name="GERENCIA" id="chekbox_puntos_gerencia" value="1">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;" id="puntoGerencia"></label>
                                                </div>


                                                <div class="col-12">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_puntos" name="ACTIVO" id="chekbox_puntos_activo" value="1">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;" id="puntoActivo"></label>
                                                </div>


                                                <div class="col-12">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_puntos" name="INSTALACION" id="chekbox_puntos_instalacion" value="1">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;" id="puntoInstalacion"></label>
                                                </div> -->

                                                <style>
                                                    .legend {
                                                        display: none;
                                                        color: blue;
                                                        margin-left: 10px;
                                                    }

                                                    .checked .legend {
                                                        display: inline;
                                                    }
                                                </style>

                                                <div class="col-12">
                                                    <h3 class=" mt-4 mb-2">Selecciona hasta 5 opciones (Cuerpo del Encabezado en el Informe)</h3>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 1 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL1" name="NIVEL1" >

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 2 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL2" name="NIVEL2" >

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 3 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL3" name="NIVEL3" >

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 4 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL4" name="NIVEL4" >

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 5 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL5" name="NIVEL5" >

                                                        </select>
                                                    </div>
                                                </div>


                                                <button type="button" class="btn btn-info mx-4 mt-4" style="background: #FC4B6C!important;" data-toggle="tooltip" title="Guardar datos del Informe" id="boton_guardarDatosInforme">
                                                    Guardar <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!--tab 2 -->
                                <div class="tab-pane" id="tab2_tabla" role="tabpanel">
                                    <div class="card-body">
                                        <form enctype="multipart/form-data" method="post" name="form_recTabla" id="form_recTabla">
                                            {!! csrf_field() !!}
                                            <div class="row">

                                                <div class="col-2">
                                                    <h4>Grupo de exposición homogéneas</h4>
                                                </div>
                                                <div class="col-3">
                                                    <h4>Componente de la mezcla (Sustancia química y/o producto)</h4>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <h4>Puntos a muestrear por reconocimiento.</h4>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <h4>Puntos totales a muestrear</h4>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <h4>Justificación</h4>
                                                </div>

                                            </div>
                                            <hr>
                                            <div id="divTablaInforme"></div>

                                            <div class="d-flex justify-content-center align-content-center">
                                                <button type="button" class="btn btn-info mt-4" style="background: #FC4B6C!important; width: 30%;" data-toggle="tooltip" title="Guardar Puntos de muestreo y POE" id="boton_guardarTablaInformes">
                                                    Guardar <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <!--tab 3 -->
                                <div class="tab-pane" id="tab3_tabla_cliente" role="tabpanel">
                                    <div class="card-body">
                                        <form enctype="multipart/form-data" method="post" name="form_recTablaCliente" id="form_recTablaCliente">
                                            {!! csrf_field() !!}
                                            <div class="row">

                                                <div class="col-3">
                                                    <h4>Área</h4>
                                                </div>
                                                <div class="col-3">
                                                    <h4>Grupo de exposición</h4>
                                                </div>
                                                <div class="col-3">
                                                    <h4>Componente a evaluar</h4>
                                                </div>
                                                <div class="col-1 text-center">
                                                    <h4>PPT</h4>
                                                </div>
                                                <div class="col-1 text-center">
                                                    <h4>CT</h4>
                                                </div>
                                                <div class="col-1">
                                                    <h4>Puntos totales</h4>
                                                </div>

                                            </div>
                                            <hr>
                                            <div id="divTablaClienteInforme">
                                                <div class="row padre">
                                                    <div class="col-3">
                                                        <div class="form-group mb-1">
                                                            <select class="custom-select form-control AREAS" name="AREA_ID[]" style="width: 100%!important" required="">
                                                                <option value=""></option>

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group mb-1">
                                                            <select class="custom-select form-control CATEGORIAS" name="CATEGORIA_ID[]" style="width: 100%!important" required="">
                                                                <option value=""></option>

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select class="custom-select form-control SUSTANCIAS" name="SUSTANCIA_ID[]" required="">
                                                                <option value=""></option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-1 text-center">
                                                        <input type="number" class="form-control  bloqueado PPT_CLIENTE" name="PPT[]" style="background:#FADBD8; border:1px solid red; font-size:20px" readonly>

                                                    </div>

                                                    <div class="col-1 text-center">
                                                        <input type="number" class="form-control bloqueado CT_CLIENTE" name="CT[]" style="background:#FADBD8; border:1px solid red ; font-size:20px" readonly>

                                                    </div>



                                                    <div class="col-1 text-center">
                                                        <input type="number" class="form-control text-center PUNTOS_CLIENTE" min="0" name="PUNTOS[]" style="font-size:20px" readonly>

                                                    </div>

                                                </div>

                                            </div>




                                            <div class="d-flex justify-content-center align-content-center">
                                                <button type="button" class="btn btn-info mt-4 mx-1" style="background: #FC4B6C!important; width: 5%;" data-toggle="tooltip" title="Eliminar registro" id="boton_eliminarTablaClienteInformes">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <button type="button" class="btn btn-info mt-4" style="background: #94B732!important; width: 10%;" data-toggle="tooltip" title="Agregar punto de muestreo" id="boton_agregarTablaClienteInformes">
                                                    Agregar <i class="fa fa-plus"></i>
                                                </button>

                                                <button type="button" class="btn btn-info mt-4 mx-4" style="background: #FC4B6C!important; width: 30%;" data-toggle="tooltip" title="Guardar Puntos de muestreo y POE" id="boton_guardarTablaClienteInformes">
                                                    Guardar <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <!--tab 4 -->
                                <div class="tab-pane" id="tab4_cambios" role="tabpanel">
                                    <div class="card-body">
                                        @if(auth()->user()->hasRoles(['Superusuario','Reconocimiento', 'ApoyoTecnico']))

                                        <form enctype="multipart/form-data" method="post" name="form_controlCambios" id="form_controlCambios">

                                            {!! csrf_field() !!}
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="hidden" class="form-control" id="ID_CONTROL_CAMBIO" name="ID_CONTROL_CAMBIO" value="0">

                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label> Realizado por </label>
                                                                <input type="text" class="form-control" name="NOMBRE_REALIZADO" id="NOMBRE_REALIZADO" value="{{ Auth::user()->name }}" readonly>

                                                                <input type="hidden" class="form-control" name="REALIZADO_ID" id="REALIZADO_ID" value="{{ Auth::user()->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label> Tipo de cambio*</label>
                                                                <select class="custom-select form-control" id="TIPO_CAMBIO" name="TIPO_CAMBIO" required>
                                                                    <option value=""></option>
                                                                    <option value="Razones Internas">Razones Internas</option>
                                                                    <option value="Solicitud del cliente">Solicitud del cliente</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" rows="2" id="DESCRIPCION_CAMBIO" name="DESCRIPCION_CAMBIO" placeholder="Cambios realizados"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial w-100" style="margin-top: 32px;" id="boton_guardar_control_cambio">
                                                        Solicitar cambio <i class="fa fa-save"></i>
                                                    </button>

                                                </div>
                                            </div>
                                        </form>
                                        @endif



                                        <div class="table-responsive" style="max-height: 410px!important;">
                                            <table class="table table-hover stylish-table mt-1" width="100%" id="tabla_control_cambios">
                                                <thead>
                                                    <tr>
                                                        <th>Versión</th>
                                                        <th>Realizado por</th>
                                                        <th>Cambio(s)</th>
                                                        <th>Fecha</th>
                                                        <th>Autorizado por</th>
                                                        <th>Fecha</th>
                                                        <th>Autorizar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                        <button type="button" class="btn btn-info mt-5" style="background: #1E88E6!important; float: right; display: none;" data-toggle="tooltip" title="Descargar informe sensorial de químicos .doc" id="boton_descargarquimicosdoc" onclick="reporte(form_recsensorial.recsensorial_id.value, 2, this);">
                                            Descargar &nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: space-between;">


                <!-- <button type="button" class="btn btn-info" style="float: right; background: #1E88E6!important; display: none;" data-toggle="tooltip" title="Descargar informe de reconocimiento químicos autorizado .pdf" id="boton_descargarquimicospdf">
                    Descargar pdf&nbsp;&nbsp;<i class="fa fa-file-pdf-o fa-1x"></i>
                </button> -->

                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                <button type="button" class="btn btn-info mt-5" style="background: #1E88E6!important; float: right; display: block;" data-toggle="tooltip" title="Descargar informe sensorial de químicos .doc" id="boton_descargarquimicosdoc" onclick="reporte(form_recsensorial.recsensorial_id.value, 2, this);">
                    Descargar &nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                </button>
                @endif

                <button type="button" class="btn btn-default waves-effect" style="float: left;" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_area .form-group {
        padding: 0px !important;
        margin: 0px 0px 8px 0px !important;
    }

    #modal_area .form-group label {
        padding: 0px !important;
        margin: 0px !important;
    }
</style>
<div id="modal_area" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 90%!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_area" id="form_area">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Área de la instalación</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="area_id" name="area_id" value="0">
                            <input type="hidden" class="form-control" id="area_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Nombre del área *</label>
                                        <input type="text" class="form-control" name="recsensorialarea_nombre" id="recsensorialarea_nombre" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Proceso del área * </label>
                                        <textarea class="form-control" rows="3" id="RECSENSORIALAREA_PROCESO" name="RECSENSORIALAREA_PROCESO"></textarea>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label> Condición del lugar *</label>
                                        <select class="custom-select form-control" id="recsensorialarea_condicion" name="recsensorialarea_condicion" required>
                                            <option value=""></option>
                                            <option value="Abierto">Abierto</option>
                                            <option value="Cerrado">Cerrado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> Características del proceso *</label>
                                        <select class="custom-select form-control" id="recsensorialarea_caracteristica" name="recsensorialarea_caracteristica" required>
                                            <option value=""></option>
                                            <option value="Continuo">Continuo</option>
                                            <option value="Intermitente">Intermitente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> Sistema de extracción de aire *</label>
                                        <select class="custom-select form-control" id="recsensorialarea_extraccionaire" name="recsensorialarea_extraccionaire" required>
                                            <option value=""></option>
                                            <option value="General">General</option>
                                            <option value="Localizado">Localizado</option>
                                            <option value="Ninguno">Ninguno</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label> Sistema de inyección de aire *</label>
                                        <select class="custom-select form-control" id="recsensorialarea_inyeccionaire" name="recsensorialarea_inyeccionaire" required>
                                            <option value=""></option>
                                            <option value="General">General</option>
                                            <option value="Localizado">Localizado</option>
                                            <option value="Ninguno">Ninguno</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Los Datos del Área son para *</label>
                                        <select class="custom-select form-control" id="RECSENSORIAL_DATOSAREA" name="RECSENSORIAL_DATOSAREA" required>
                                            <option selected disabled>Seleccione una opción</option>
                                            <option value="1">Químico</option>
                                            <option value="2">Físico</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12">
                                    <label style="margin: 0px; padding: 0px;">Agentes o factores de riesgos en el área *</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row" id="checkbox_areaagentes" style="border: 0px #000 solid; height: 270px; max-height: 270px; overflow-x: hidden; overflow-y: auto;">
                                                Agentes a evaluar
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Operativo HI']))
                            <ol class="breadcrumb m-b-10">
                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar categoría" id="boton_nueva_areacategoria">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Categoría en el área
                                </button>
                            </ol>
                            @endif
                            <div class="table-responsive" style="margin: 0px 0px 20px 0px; max-height: 220px; overflow-x: hidden; overflow-y: auto;">
                                <table class="table table-bordered table-hover stylish-table" style="margin: 0px;" width="100%" id="tabla_areacategorias">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Actividades</th>
                                            <th style="width: 120px!important;">GEH</th>
                                            <th style="width: 120px!important;">Total personas</th>
                                            <th style="width: 140px!important;">Tiempo Expo(Min).</th>
                                            <th style="width: 120px!important;">Frec. Expo.</th>
                                            <th style="width: 70px!important;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-warning" style="text-align: center; line-height: 18px; margin: 0px; padding: 0px;">Datos del área para <span id="selectedArea"></span></h4>
                            <hr>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Generación del contaminante en el área *</label>
                                <select class="custom-select form-control" id="recsensorialarea_generacioncontaminante" name="recsensorialarea_generacioncontaminante[]" required multiple>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Combustión">Combustión</option>
                                    <option value="Aumento de temperatura">Aumento de temperatura</option>
                                    <option value="Disminución de temperatura">Disminución de temperatura</option>
                                    <option value="Aumento de presión">Aumento de presión</option>
                                    <option value="Disminución de presión">Disminución de presión</option>
                                    <option value="Generación de humedad">Generación de humedad</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <!-- <script>
                                    // Establecer opciones seleccionadas en el select
                                    var opcionesSeleccionadas = {
                                        !!json_encode($dato['opciones_seleccionadas']) !!
                                    };
                                    $('#recsensorialarea_generacioncontaminante').val(opcionesSeleccionadas);
                                </script> -->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Jerarquía de control *</label>
                                <select class="custom-select form-control" id="JERARQUIACONTROL" name="JERARQUIACONTROL" required>
                                    <option selected disabled style="background-color: #ffffff; color: rgb(0, 0, 0)">Seleccione una opción</option>
                                    <option value="1" style=" color: white">Eliminación</option>
                                    <option value="2" style=" color: white">Sustitución</option>
                                    <option value="3" style=" color: white">Controles ingeniería</option>
                                    <option value="4" style=" color: white">Controles administrativos</option>
                                    <option value="5" style=" color: white">EPP</option>
                                    <option value="0" style=" color: rgb(0, 0, 0)">Otro</option>
                                    <option value="6" style=" color: rgb(0, 0, 0)">N/A</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6 ">
                            <div class="form-group">
                                <label> Descripción de la jerarquía de control </label>
                                <input type="text" class="form-control" name="CONTROLESJERARQUIA_DESCRIPCION" id="CONTROLESJERARQUIA_DESCRIPCION" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label> Descripción del área*</label>
                                <select class="custom-select form-control" id="DESCRIPCION_AREA" name="DESCRIPCION_AREA">
                                    <option value=""></option>
                                    @foreach($descripciones as $dato)
                                    <option value="{{$dato->ID_DESCRIPCION_AREA}}">{{ $dato->DESCRIPCION }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_area">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<div id="modal_maquina" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 90%!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_maquina" id="form_maquina">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Fuente generadora</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="maquina_id" name="maquina_id" value="0">
                            <input type="hidden" class="form-control" id="maquina_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Área *</label>
                                <select class="custom-select form-control" id="maquinaarea_id" name="recsensorialarea_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-12">
                            <div class="form-group">
                                <label> Agente / factor de riesgo *</label>
                                <select class="custom-select form-control" id="recsensorialmaquinaria_afecta" name="recsensorialmaquinaria_afecta" required>
                                    <option value=""></option>
                                    <option value="1">Factores físicos</option>
                                    <option value="2">Factores químicos</option>
                                    <option value="3">Factores físicos y químicos</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-4">
                            <div class="form-group">
                                <label> Nombre de la fuente generadora *</label>
                                <input type="text" class="form-control" name="recsensorialmaquinaria_nombre" id="recsensorialmaquinaria_nombre" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label> Cantidad de fuente generadora*</label>
                                <input type="number" class="form-control" name="recsensorialmaquinaria_cantidad" id="recsensorialmaquinaria_cantidad" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label> Contenido*</label>
                                <input type="number" class="form-control" name="recsensorialmaquinaria_contenido" id="recsensorialmaquinaria_contenido" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label> Unidad de medida *</label>
                                <select class="custom-select form-control" id="recsensorialmaquinaria_unidadMedida" name="recsensorialmaquinaria_unidadMedida" required>
                                    <option value=""></option>
                                    <option value="MM">Milímetros (mm)</option>
                                    <option value="CM">Centímetros (cm)</option>
                                    <option value="M">Metros (m)</option>
                                    <option value="KM">Kilómetros (km)</option>
                                    <option value="MG">Miligramos (mg)</option>
                                    <option value="G">Gramos (g)</option>
                                    <option value="KG">Kilogramos (kg)</option>
                                    <option value="OZ">Onzas (oz)</option>
                                    <option value="LB">Libras (lb)</option>
                                    <option value="L">Litros (L)</option>
                                    <option value="ML">Mililitros (ml)</option>
                                    <option value="GAL">Galones (gal)</option>
                                    <option value="FT">Pies (ft)</option>
                                    <option value="IN">Pulgadas (in)</option>
                                    <option value="PZ">Piezas (pz)</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Guardamos los tipos en una variable -->
                    <script type="text/javascript">
                        var lista_alcances = <?php echo $tipopruebas; ?>;
                    </script>

                    <div class="listaAreasAfectadas"></div>


                </div>
                <div class="modal-footer" style="display: flex; justify-content: flex-start;">
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="button" class="btn btn-danger waves-effect waves-light" id="boton_agregar_alcance">
                        Agregar Alcance <i class="fa fa-plus"></i>
                    </button>
                    @endif
                    <div style="flex-grow: 1;"></div>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_maquina">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>



<div id="modal_mapa" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 95%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Mapa de fuentes generadoras</h4>
            </div>
            <div class="modal-body">
                <style>
                    canvas {
                        border: 1px solid black;
                        cursor: pointer;
                    }

                    .controls {
                        margin-bottom: 10px;
                    }
                </style>
                <div class="controls">
                    <input type="color" id="color" class="btn btn-circle" data-toggle="tooltip" title="Color del punto" value="#e23232">
                    <label for="borderWidth">Grosor del borde: </label>
                    <input type="range" id="borderWidth" min="1" max="10" value="3">
                    <button id="addRectangle" data-toggle="tooltip" title="Agregar punto" class="btn btn-circle btn-success mx-2"><i class="fa fa-map-marker"></i></button>
                    <!-- <button id="addCircle" class="btn btn-circle btn-success">Agregar Círculo</button> -->
                    <button id="deleteShape" data-toggle="tooltip" class="btn btn-danger btn-circle mx-2" title="Eliminar punto"><i class="fa fa-trash"></i></button>
                    <button id="download" data-toggle="tooltip" class="btn btn-circle btn-info" title="Descargar Imagen"><i class="fa fa-download"></i></button>
                </div>
                <canvas id="canvas"></canvas>


            </div>
            <div class="modal-footer" style="display: flex; justify-content: flex-start;">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                <button type="button" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_mapa">
                    Guardar <i class="fa fa-save"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<div id="modal_equipopp" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 80% !important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_equipopp" id="form_equipopp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Equipo de protección personal</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="equipopp_id" name="equipopp_id" value="0">
                            <input type="hidden" class="form-control" id="equipopp_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Categoría *</label>
                                <select class="custom-select form-control" id="equipopp_recsensorialcategoria_id" name="recsensorialcategoria_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                            <ol class="breadcrumb m-b-10">
                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nuevo E.P.P" id="boton_nuevo_epp">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>E.P.P
                                </button>
                            </ol>
                            @endif
                            <div class="table-responsive" style="max-height: 410px!important;">
                                <table class="table table-hover stylish-table" width="100%" id="tabla_lista_epp">
                                    <thead>
                                        <tr>
                                            <th style="max-width: 48%!important;">Región anatómica *</th>
                                            <th style="max-width: 48%!important;">Clave y EPP *</th>
                                            <th style="max-width: 48%!important;">Tipo de riesgo *</th>
                                            <th style="max-width: 4%!important;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_equipopp">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<div id="modal_anexo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 70% !important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_anexo" id="form_anexo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Anexo para impresión del reconcimiento</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="anexo_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Anexo para el informe de *</label>
                                <select class="custom-select form-control" id="recsensorialanexo_tipo" name="recsensorialanexo_tipo" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Anexos requeridos por el contrato *</label>
                                <select class="custom-select form-control" id="contrato_anexo_id" name="contrato_anexo_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="anexoAcreditaciones" style="display: none;">
                        <div class="col-12">
                            <div class="form-group">
                                <label> Laboratorio *</label>
                                <select class="custom-select form-control" id="proveedor_id" name="proveedor_id" onchange="anexo_proveedor(this.value)">
                                    <option value=""></option>
                                    @foreach($proveedor as $dato)
                                    <option value="{{$dato->id}}">{{ $dato->proveedor_RazonSocial }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Anexo (Acreditación / Aprobación) *</label>
                                <select class="custom-select form-control" style="text-transform: capitalize;" id="acreditacion_id" name="acreditacion_id">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="anexoArchivo" style="display: none;">
                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte del Anexo </label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_anexo">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="anexo_archivo" id="anexo_archivo">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="anexoImagen" style="display: none;">
                        <div class="col-12">
                            <label> Soporte del Anexo </label>
                            <div class="form-group">
                                <style type="text/css" media="screen">
                                    .dropify-wrapper {
                                        height: 300px !important;
                                        /*tamaño estatico del campo foto*/
                                    }
                                </style>
                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="anexo_imagen" name="anexo_imagen" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_anexo">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


<div id="modal_anexo_imagen" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="imagen_titulo"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label> Soporte del Anexo </label>
                        <div class="form-group">
                            <style type="text/css" media="screen">
                                .dropify-wrapper {
                                    height: 300px !important;
                                    /*tamaño estatico del campo foto*/
                                }
                            </style>
                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="contrato_anexo_imagen" name="contrato_anexo_imagen" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- /MODALES RECONOCIMIENTO SENSORIAL -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODALES QUÍMICOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #div_tabla_catsustancia {
        max-height: 300px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    #tabla_catsustancias th {
        /*border: 1px #DDD solid;*/
        border-bottom: 3px #999999 solid;
    }

    #tabla_catsustancias td {
        text-align: center;
        /*border: 1px #DDD solid;*/
        /*padding: 6px 12px 6px 0px!important;*/
        padding: 6px 0px;
    }

    #tabla_catsustancias td table td {
        text-align: center;
        border: 0px #DDD solid;
        /*padding: 6px 12px 6px 0px!important;*/
        padding: 3px 0px 3px 12px;
    }

    #tabla_catsustancias td table tr:hover td {
        background: #D8D8D8;
    }
</style>
<div id="modal_inventariosustancia" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    {{-- <div class="modal-dialog modal-lg" style="border: 0px #f00 solid; min-width: 90%!important;"> --}}
    <div class="modal-dialog modal-lg" style="min-width: 1200px!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_inventariosustancia" id="form_inventariosustancia">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Inventario de sustancias químicas por área</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="sustanciaarea_id" name="sustanciaarea_id" value="0">
                            <input type="hidden" class="form-control" id="sustancia_recsensorial_id" name="recsensorial_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group" style="padding: 0px; margin-bottom: 10px;">
                                <label>Área *</label>
                                <select class="custom-select form-control" id="sustancia_recsensorialarea_id" name="recsensorialarea_id" required onchange="consulta_select_categoriasxarea(this.value);">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                        <div class="col-12">
                            <ol class="breadcrumb m-b-10">
                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar sustancia a la lista" id="boton_nuevasustancia_inventario" disabled>
                                    <span class="btn-label"><i class="fa fa-plus"></i></span> Sustancia
                                </button>
                            </ol>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover stylish-table" width="100%" id="tabla_catsustancias">
                                <thead style="display: block; width: 100%!important;">
                                    <tr>
                                        <th style="width: 52px!important;">No.</th>
                                        <th style="width: 680px!important;">Sustancia</th>
                                        <th style="width: 180px!important;">Cantidad manejada</th>
                                        <th style="width: 180px!important;">Uni. medida</th>
                                        <th style="width: auto!important;">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody style="display: block; width: 100%; height: 300px; max-height: 300px; overflow-y: auto; overflow-x: hidden;"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_sustancia">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- /MODALES QUÍMICOS -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-FOTOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_evidencia_fotosquimicos .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_evidencia_fotosquimicos .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_evidencia_fotosquimicos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 900px!important;">
        <form method="post" enctype="multipart/form-data" name="form_evidencia_fotosquimicos" id="form_evidencia_fotosquimicos">
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
                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="inputevidenciafotoquimicos" name="inputevidenciafoto" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" onchange="redimencionar_fotoevidenciaquimicos();" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Tipo de evidencia *</label>
                                        <select class="custom-select form-control" id="recsensorialevidenciasquimicos_tipo" name="recsensorialevidencias_tipo" onchange="descripcion_fotoquimicos()" required>
                                            <option value=""></option>
                                            <option value="1">Foto evidencia</option>
                                            <option value="2">Plano</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Área *</label>
                                        <select class="custom-select form-control" id="recsensorialevidenciasquimicos_recsensorialarea_id" name="recsensorialarea_id" onchange="descripcion_fotoquimicos()" required>
                                            <option value=""></option>
                                            {{-- @foreach($recsensorialareas as $dato)
                                                <option value="{{$dato->id}}">{{$dato->recsensorialarea_nombre}}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Descripción de la (foto / plano) en el reporte</label>
                                        <textarea class="form-control" rows="6" id="recsensorialevidenciasquimicos_descripcion" name="recsensorialevidencias_descripcion" required></textarea>
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-danger" id="boton_guardar_evidencia_fotosquimicos">
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
<!-- MODAL AGENTE CLIENTE -->
<!-- ============================================================== -->
<div id="modal_agentescliente" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 86%;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_agentescliente" id="form_agentescliente">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Agente / Factor de riesgo / Servicio</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="agentescliente_accion" name="agentescliente_accion" value="0">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Agente / Factor de riesgo / Servicio *</label>
                                <select class="custom-select form-control" id="agentescliente_agenteid" name="agentescliente_agenteid" onchange="valida_tipoanalisis(this.value);" required>
                                    <option value=""></option>
                                    @foreach($catprueba as $agente)
                                    @if($agente->id != 15)
                                    <option class="selectagente" id="selectagente_{{$agente->id}}" value="{{$agente->id}}">{{$agente->catPrueba_Nombre}}</option>
                                    @endif
                                    @endforeach
                                    <option class="selectagente" id="selectagente_15" value="15">Agente químico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4" id="div_campo_tipoagente">
                            <div class="form-group">
                                <label> Tipo análisis *</label>
                                <select class="custom-select form-control" id="agentescliente_tipo" onchange="agrega_tipoanalisis(this);" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4" id="div_campo_nombreagente" style="display: none;">
                            <div class="form-group">
                                <label> Nombre agente químico *</label>
                                <input type="text" class="form-control" id="agentescliente_nombre" name="agentescliente_nombre">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label style="letter-spacing: -1px;">Pts. de muestreos / Cantidad / Num. personas *</label>
                                <input type="number" class="form-control" id="agentescliente_puntos" name="agentescliente_puntos" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Alcance del análisis (opcional)</label>
                                <input type="text" class="form-control" id="agentescliente_analisis" name="agentescliente_analisis" placeholder="Ejem. Color, Olor y sabor, Turbiedad...">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Observación (opcional) </label>
                                <textarea class="form-control" rows="2" id="agentescliente_observacion" name="agentescliente_observacion"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI']))
                    <button type="submit" class="btn btn-info waves-effect waves-light botonguardar_modulorecsensorial" id="botonagregar_agentescliente">
                        Agregar a la lista <i class="fa fa-check"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- /MODAL AGENTE CLIENTE -->
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

{{-- ========================================================================= --}}
@endsection