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

    .green-breadcrumb {
    /* background-color: #8bd249; 
    color: white; 
    padding: 5px 10px; 
    border-radius: 5px;  */
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    list-style: none;
    background-color: rgb(139, 210, 73);
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    }
    .blue-breadcrumb {
    /* background-color: #8bd249; 
    color: white; 
    padding: 5px 10px; 
    border-radius: 5px;  */
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    list-style: none;
    background-color: rgb(0, 152, 199);
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Psicólogo']))
                    <ol class="breadcrumb m-b-10">
                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Lista de Reconocimientos </h2>
                        <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente" data-toggle="tooltip" title="Nuevo reconocimiento sensorial" style="margin-left:auto" id="boton_nuevo_reconocimiento">
                            Reconocimiento <i class="fa fa-plus p-1"></i>
                        </button>
                    </ol>
                    @else
                    <ol class="breadcrumb m-b-10">
                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Lista de Reconocimientos </h2>
                    </ol>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover stylish-table" width="100%" id="tabla_reconocimiento_sensorial">
                            <thead>
                                <tr>
                                    <th width="60">No.</th>
                                    <th width="100">Alcance</th>
                                    <th width="130">Folios</th>
                                    <th width="110">Cliente / Contrato</th>
                                    <th>Folio Proyecto</th>
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
                                                <i class="fa fa-address-card"></i><br>
                                                <span>Normativa</span>
                                            </div>
                                            <!-- <div class="multisteps-form__progress-btn" id="steps_menu_tab5">
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
                                            </div> -->
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
                                                                    
                                                                            <div class="col-12 mt-3 mb-3">
                                                                            <input type="hidden" class="form-control" id="recsensorial_id" name="recsensorial_id" value="0">
                                                                            <input type="hidden" class="form-control" id="recsensorial_tipocliente" value="1">
                                                                            </div>
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
                                                                               
                                                                            
                                                                            </script>

                                                                            <!-- Datos de Informe obtenido por el cliente -->
                                                                            <!-- <div class="col-12 mt-5">
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
                                                                            </div> -->

                                                                            <!-- Datos del cliente -->
                                                                            <div class="col-12 mt-3 clienteblock"></div>

                                                                            <div class="col-12 " style="display: none;">
                                                                                <div class="form-group">
                                                                                    <label> Orden de trabajo / Licitacion </label>
                                                                                    <input type="text" class="form-control" id="ordenTrabajoLicitacion" name="ordenTrabajoLicitacion" placeholder="Eje: RES-OT24-XXX ó N/A" readonly>
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

                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Empresa *</label>
                                                                                    <input type="text" class="form-control" id="empresa" name="empresa" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> R.F.C. *</label>
                                                                                    <input type="text" class="form-control" id="rfc" name="rfc" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Orden servicio </label>
                                                                                    <input type="text" class="form-control" id="ordenservicio" name="ordenservicio" placeholder="Eje: CEN-004-2022 ó N/A">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Repr. legal *</label>
                                                                                    <input type="text" class="form-control" id="representantelegal" name="representantelegal" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Repr. Seg. Industrial *</label>
                                                                                    <input type="text" class="form-control" id="representanteseguridad" name="representanteseguridad" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Instalación *</label>
                                                                                    <input type="text" class="form-control" id="instalacion" name="instalacion" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Dirección de la instalación *</label>
                                                                                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Código postal *</label>
                                                                                    <input type="number" class="form-control" id="codigopostal" name="codigopostal" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label>Coordenadas *</label>

                                                                                    <input type="text" class="form-control" id="coordenadas" name="coordenadas" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Actividad principal *</label>
                                                                                    <textarea class="form-control" rows="4" id="actividadprincipal" name="actividadprincipal" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Descripción del proceso en la instalación *</label>
                                                                                    <textarea class="form-control" rows="4" id="descripcionproceso" name="descripcionproceso" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Observación sobre el horario del personal *</label>
                                                                                    <textarea class="form-control" rows="3" id="observaciones" name="observaciones" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Fecha inicio del reconocimiento *</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="fechainicio" name="fechainicio" required>
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12 clienteblock">
                                                                                <div class="form-group">
                                                                                    <label> Fecha fin del reconocimiento *</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="fechafin" name="fechafin" required>
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
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
                                                                                            <input type="hidden" id="hidden_fotomapa" name="hidden_fotomapa" value="">
                                                                                            <input type="hidden" id="hidden_fotomapa_extension" name="hidden_fotomapa_extension" value="">
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
                                                                                            <input type="hidden" id="hidden_fotoplano" name="hidden_fotoplano" value="">
                                                                                            <input type="hidden" id="hidden_fotoplano_extension" name="hidden_fotoplano_extension" value="">
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
                                                                                            <input type="hidden" id="hidden_fotoinstalacion" name="hidden_fotoinstalacion" value="">
                                                                                            <input type="hidden" id="hidden_fotoinstalacion_extension" name="hidden_fotoinstalacion_extension" value="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>
                                                        <!-- Botones de envio y desactivacion -->
                                                        <div class="row">
                                                            
                                                            <div class="col-12">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Categoría personal </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva categoría" id="boton_nueva_categoria" style="margin-left: auto;">
                                                                    Categoría Personal <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Categoría personal </h2>
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
                                                                            <!-- <th style="width: 100px!important;">Eliminar</th> -->
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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva área" id="boton_nueva_area">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Área instalación
                                                                </button>

                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Área instalación </h2>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialareas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            <th>Área</th>
                                                                            <th>Categorías</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                            <!-- <th style="width: 80px!important;">Eliminar</th> -->
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
                                                    <form name="form_normativa" id="form_normativa" enctype="multipart/form-data" method="post">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body" >
                                                                        <div class="col-12" style="text-align: center;">
                                                                            <label style="font-weight: bold;font-size: 20px;">Normativa (Criterio según número de trabajadores)</label>
                                                                            <h6 class="card-subtitle text-white m-b-0 op-5">&nbsp;</h6>
                                                                        </div>
                                                                        <div class="row">
                                                                            {!! csrf_field() !!}

                                                                            <div class="col-3 mt-3 mb-3">
                                                                                <div class="form-group">
                                                                                    <label>Total de trabajadores *</label>
                                                                                    <input type="hidden" class="form-control" id="RECPSICO_ID_NORMATIVA" name="RECPSICO_ID" value="0">
                                                                                    <input type="number" class="form-control" id="total_empleados" name="RECPSICO_TOTALTRABAJADORES" required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-5 mt-3 mb-3">
                                                                                <label>Seleccione los criterios para aplicar la NOM-035-STPS-2018:</label>
                                                                                <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="optionA" name="optionA" >
                                                                                        <label class="custom-control-label" for="optionA">Seleccionar guias adicionales: </label>
                                                                                    </div>
                                                                                <div class="form-group">
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="option1" name="option1" >
                                                                                        <label class="custom-control-label" for="option1">GUIA DE REFERENCIA I (Acontecimientos Traumáticos Severos)</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="option2" name="option2" >
                                                                                        <label class="custom-control-label" for="option2">GUIA DE REFERENCIA II (Factores de Riesgo Psicosocial)</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="option3" name="option3" >
                                                                                        <label class="custom-control-label" for="option3">GUIA DE REFERENCIA III (Evaluación del Entorno Organizacional)</label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-checkbox">
                                                                                        <input type="checkbox" class="custom-control-input" id="option4" name="option4" >
                                                                                        <label class="custom-control-label" for="option4">GUIA DE REFERENCIA V (Datos del trabajador)</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-4 mt-3 mb-3">
                                                                                <label>Aplicable a:</label>
                                                                                <div class="form-group">
                                                                                    <select class="form-control" id="aplicable_a" name="RECPSICO_TIPOAPLICACION" required disabled>
                                                                                        <option value="">Selecciona una opción</option>
                                                                                    </select>
                                                                                    <span id="error_aplicable_a" style="color: red; display: none;">Por favor selecciona una opción válida</span>
                                                                                </div>
                                                                            </div>


                                        
                                                                            <div class="col-12 mt-3 mb-3">
                                                                                <div class="custom-control custom-checkbox">
                                                                                    <input type="checkbox" class="custom-control-input" id="habilitar_opcional" name="habilitar_opcional">
                                                                                    <label class="custom-control-label" for="habilitar_opcional">Registrar porcentaje/cantidad de hombres y mujeres</label>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 mt-3 mb-3" id="campos_opcionales" style="display: none;">
                                                                                <h5>Campos opcionales</h5>
                                                                                <div class="row">
                                                                                    <!-- Select para el tipo de valor que se ingresa para hombres -->
                                                                                    <div class="col-4 mt-3 mb-3">
                                                                                        <label>Tipo de valor:</label>
                                                                                        <select class="form-control" id="tipo_valor_hombres" name="tipo_valor_hombres">
                                                                                            <option value="">Selecciona un tipo de valor</option>
                                                                                            <option value="cantidad">Cantidad</option>
                                                                                            <option value="porcentaje">Porcentaje</option>
                                                                                        </select>
                                                                                    </div>

                                                                                    <!-- Input numérico para hombres -->
                                                                                    <div class="col-4 mt-3 mb-3">
                                                                                        <label>Hombres (cantidad/porcentaje):</label>
                                                                                        <div class="input-group">
                                                                                            <input type="number" class="form-control" id="valor_hombres" name="RECPSICO_TOTALHOMBRESTRABAJO" placeholder="Ingresa valor de hombres" >
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text" id="sufijo_hombres"> %</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Input numérico para mujeres -->
                                                                                    <div class="col-4 mt-3 mb-3">
                                                                                        <label>Mujeres (cantidad/porcentaje):</label>
                                                                                        <div class="input-group">
                                                                                            <input type="number" class="form-control" id="valor_mujeres" name="RECPSICO_TOTALMUJERESTRABAJO" placeholder="Ingresa valor de mujeres" >
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text" id="sufijo_mujeres"> %</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                            </div>      
                                                                            

                                                                      
                                                                        </div>
                                                                        <div class="col-12 mt-6 mb-3 align-items-center" id="resultados_trabajadores" style="margin: 20px auto;">
                                                                            <div id="totaltrabajadores_container" lass="col-12 mt-6 mb-3 text-center" style="border-style: dotted; padding: 10px; width: 100%; display: none;" >
                                                                                <div class="form-group">
                                                                                    <div id="resultado-container" class="row mx-0 text-center align-items-center" style="display: flex; justify-content: center; align-items: center;">
                                                                                        <h2 id="" class="mt-2" style="font-weight:bold">
                                                                                        <i class="fa fa-id-badge">
                                                                                        </i>
                                                                                        Número de trabajadores a entrevistar: 
                                                                                        </h2>
                                                                                        <input type="number" class="form-control" id="RECPSICO_TOTALAPLICACION" name="RECPSICO_TOTALAPLICACION" value="" min="" required style="max-width: 100px; text-align: center; margin: 0 10px; font-size: 1.2rem; font-weight:bold">
                                                                                        <h2 id="" class="mt-2" style="font-weight:bold">
                                                                                        <i class="fa fa-id-badge">
                                                                                        </i>
                                                                                        trabajadores.
                                                                                        </h2>
                                                                                    </div>
                                                                                    <div id="generos_container" class="col-12 text-center" style="display: none;">
                                                                                        <h2 id="" class="mt-4" style="font-weight:bold">
                                                                                        <i class="fa fa-id-badge">
                                                                                        </i>
                                                                                        Trabajadores por género: 
                                                                                        </h2>
                                                                                        <h3 class="mb-2" id="porcentajes" >
                                                                                        <h3 class="mb-2" id="seleccion" >
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12" style="text-align: center;">
                                                                            <label class="col-12" style="font-weight: bold;font-size: 20px;">Carga de trabajadores del centro de trabajo</label>
                                                                            <!-- <h6 class="col-12 card-subtitle text-white m-b-0 op-5">&nbsp;</h6> -->
                                                                            <button type="button" class="btn btn-success me-2 waves-effect waves-light botonnuevo_modulorecsensorial" style="margin: 25px;" data-toggle="tooltip" title="Cargar trabajadores" id="boton_carga_trabajadores" onclick="abrirTrabajadoresExcel()">
                                                                                <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Cargar trabajadores y/o muestrear
                                                                            </button>
                                                                            <button type="button" class="btn btn-primary waves-effect waves-light botonnuevo_modulorecsensorial" style="margin: 25px;" data-toggle="tooltip" title="Cargar trabajadores" id="boton_carga_muestra">
                                                                                    <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Cargar muestra de trabajadores
                                                                            </button>
                                                                        </div>

                                                                        <div class="col-12" style="text-align: center;">
                                                                            <div>
                                                                                <ol class="breadcrumb m-b-10 blue-breadcrumb">
                                                                                    <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Lista de trabajadores totales cargados</h2>
                                                                                </ol>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_trabajadores_cargados">
                                                                                        <thead>
                                                                                            <tr>
                                                                                            <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                                                            <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                                                            <th class="sorting_disabled text-center" rowspan="1" colspan="1">Muestra</th>
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

                                                            <div class="col-12">
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial" id="boton_guardar_normativa">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!--STEP 5-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab5">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-handshake-o"></i> E.P.P </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva equipo de protección personal" id="boton_nueva_equipopp" style="margin-left: auto;">
                                                                    E.P.P <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-handshake-o"></i> E.P.P </h2>
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
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial w-100 p-3" id="boton_guardar_responsables">
                                                                        Guardar responsables <i class="fa fa-save"></i>
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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-text-o"></i> Anexos para Físicos y Químicos </h2>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar anexo al reconocimiento" style="margin-left: auto;" id="boton_nuevo_anexo">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Anexo
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-text-o"></i> Anexos para Físicos y Químicos </h2>
                                                            </ol>
                                                            @endif
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialanexos">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 80px!important;">No.</th>
                                                                            <th style="width: 120px!important;">Tipo informe</th>
                                                                            <th>Laboratorio o Nombre del anexo</th>
                                                                            <th>Entidad</th>
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
                                                        <div class="col-6">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_recsensorialanexos2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 80px!important;">No.</th>
                                                                            <th style="width: 120px!important;">Tipo informe</th>
                                                                            <th>Laboratorio o Nombre del anexo</th>
                                                                            <th>Entidad</th>
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
                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Psicólogo']))
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
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab2">
                                                <i class="fa fa-flask"></i><br>
                                                <span>Determinación de la prioridad de muestreo de las sustancias químicas (Valor de ponderación)</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab4">
                                                <i class="fa fa fa-users"></i><br>
                                                <span>Identificación de los grupos de exposición homogénea</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn-3" id="steps3_menu_tab3">
                                                <i class="fa fa-filter"></i><br>
                                                <span>Determinación de los grupos de exposición homogénea</span>
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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                                                                            <th>Sustancia química y/o producto</th>
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
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab2">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10 text-light ">Determinación de la prioridad de muestreo de las sustancias químicas.</ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_1">
                                                                    <thead>
                                                                        <tr>
                                                                            {{-- <th style="width: 80px!important;">No</th> --}}
                                                                            <th>Área</th>
                                                                            <th>Sustancia química y/o<br>producto</th>
                                                                            <th>Componentes de la<br>mezcla</th>
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
                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab4">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10 text-light">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Agregar nuevo grupo" id="boton_nuevo_grupo">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Grupo de exposición homogénea
                                                                </button>

                                                                @endif
                                                            </ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Editar</th>
                                                                            <th>Clasificación <br> GEH</th>
                                                                            <th>Componente<br> (Sustancia química y/o producto)</th>
                                                                            <th>Área</th>
                                                                            <th>Grupo de expo.<br>homogénea</th>
                                                                            <th style="width: 120px!important;">Numero de<br>trabajadores</th>
                                                                            <th style="width: 160px!important;">Tiempo de<br>Exposición (minutos)</th>
                                                                            <th style="width: 160px!important;">Frecuencia de<br>Exposición (jornada)</th>
                                                                            <th style="width: 160px!important;">Tiempo total<br>Exposición (minutos)</th>
                                                                            <th style="width: 130px!important;">Jornada de<br>trabajo (horas)</th>
                                                                            <!-- <th style="width: 120px!important;">Prioridad de<br>muestreo</th> -->
                                                                            <!-- <th style="width: 120px!important;">Num. POE a<br>Considerar</th> -->
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
                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel-3" data-animation="scaleIn" id="steps3_contenido_tab3">
                                                <div class="multisteps-form__content-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10 text-light">Determinación de los grupos de exposición homogénea.</ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" width="100%" id="tabla_quimicosresumen_2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Clasificación GEH</th>
                                                                            <th>Componente <br> (Sustancia química y/o producto)</th>
                                                                            <th>Área/Zona</th>
                                                                            <th>Grupo de expo. homogénea</th>
                                                                            <th style="width: 130px!important;">Vía de ingreso<br>al organismo</th>
                                                                            <th style="width: 130px!important;">Numero<br>de POE</th>
                                                                            <th style="width: 130px!important;">Tiempo de<br>Exposición</th>
                                                                            <th style="width: 100px!important;">Suma total<br>ponderación</th>
                                                                            <th style="width: 120px!important;">Prioridad de<br>muestreo</th>
                                                                            <th style="width: 120px!important;">Num. POE a<br>Considerar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="10">&nbsp;</td>
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
                                                            <ol class="breadcrumb m-b-10 text-light">Personal ocupacionalmente expuesto a considerar para el muestreo.</ol>
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
                                                                            <!-- Nueva columna al principio -->
                                                                            <th rowspan="2" style="vertical-align: middle!important;">Clasificación GEH</th>
                                                                            <th width="250" rowspan="2" style="vertical-align: middle!important;">Componente de la mezcla <br> (Sustancia química y/o producto)</th>
                                                                            <th rowspan="2" style="vertical-align: middle!important;">Grupo de expo. homogénea</th>
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

                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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

                                <div class="card-body">
                                    <h4 class="card-title">
                                        Resumen de sustancias químicas adicionales solicitadas por el cliente
                                    </h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive" id="tabla_resumen_puntos_cliente">
                                                <table class="table table-hover stylish-table" width="100%" id="tabla_recsensorialquimicos_resumen_cliente">
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
<div id="modal_cargarTrabajadores" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_cargaTrabajadores" id="form_cargaTrabajadores">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cargar trabajadores del centro de trabajo</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="RECPSICO_ID_TRABAJADORES" name="RECPSICO_ID" value="0">
                            <input type="number" class="form-control" id="RECPSICO_APLICACION" name="RECPSICO_APLICACION" style="visibility: hidden;">
                            <div class="col-12" id="cargarTrabajadores_excel">
                        </div>
                                        <div class="form-group">
                                            <label> Cargar excel de trabajadores totales del centro de trabajo *</label>
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_trabajadores">
                                                    <i class="fa fa-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-secondary btn-file">
                                                    <span class="fileinput-new">Seleccione</span>
                                                    <span class="fileinput-exists">Cambiar</span>
                                                    <input type="file" accept=".xls,.xlsx" name="excelTrabajadores" id="excelTrabajadores" required>
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                            </div>
                                        </div>
                                        <div class="row mx-2 mb-2" id="alertaVerificacion" style="display:none">
                                            <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el nombre de las Áreas y/o categorías a insertar corresponda con las que están cargadas en el Software. </p>
                                        </div> 
                                                                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="RECPSICOTRABAJADOR_MUESTRA" name="RECPSICOTRABAJADOR_MUESTRA">
                    <label class="custom-control-label" for="RECPSICOTRABAJADOR_MUESTRA">Obtener muestra </label>
                </div>    
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_cargarTrabajadores">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<div id="modal_cargarMuestra" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_cargaMuestra" id="form_cargaMuestra">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cargar muestra de trabajadores del centro de trabajo</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_RECOPSICOCATEGORIA" name="ID_RECOPSICOCATEGORIA" value="0">
                            <input type="hidden" class="form-control" id="RECPSICO_ID" name="RECPSICO_ID" value="0">
                        </div>
                        <div class="col-12" id="cargarTrabajadores_excel">
                                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador', 'CoordinadorHI']))
                                                                            
                                                                                            <form method="post" enctype="multipart/form-data" name="form_reconocimientofisicos_pdf" id="form_reconocimientofisicos_pdf">
                                                                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                {!! csrf_field() !!}
                                                                                                                <label style="font-size: 16px;">Cargar excel con la muestra de trabajadores del centro de trabajo*</label>
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
                                                                                                            <td >
                                                                                                               
                                                                                                            </td>
                                                                        
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </form>
                                                                    
                                                                            @endif
                                                                        </div>

                                                                        

                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
    
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_cargarMuestra">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


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
                            <input type="hidden" class="form-control" id="ID_RECOPSICOCATEGORIA" name="ID_RECOPSICOCATEGORIA" value="0">
                            <input type="hidden" class="form-control" id="RECPSICO_ID" name="RECPSICO_ID" value="0">
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
                                <input type="text" class="form-control" name="RECPSICO_NOMBRECATEGORIA" id="RECPSICO_NOMBRECATEGORIA" placeholder="ejem. Técnico de laboratorio electrónico" required>
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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                                                        <!-- <label> Conclusiones</label> -->

                                                        <div class="switch">
                                                            <label>
                                                                <input type="checkbox" id="REQUIERE_CONCLUSION" name="REQUIERE_CONCLUSION" value="1" checked onchange="validarConclusion(this)">
                                                                <span class="lever switch-col-light-blue" id="CHECK_CONCLUSION"></span> Conclusion
                                                            </label>
                                                        </div>

                                                        <select class="custom-select form-control mb-1" style="width: 100%;" id="ID_CATCONCLUSION" name="ID_CATCONCLUSION">
                                                            <option value="">&nbsp;</option>
                                                           
                                                        </select>
                                                        <textarea class="form-control" rows="8" id="CONCLUSION" name="CONCLUSION" readonly></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 mx-2">
                                                    <label class="text-danger">¿El cliente desea muestrear sustancias químicas adicionales ? </label>
                                                    <div class="switch">
                                                        <label>
                                                            No<input type="checkbox" id="PETICION_CLIENTE" name="PETICION_CLIENTE" value="1">
                                                            <span class="lever switch-col-light-blue" id="checkbox_PETICIONCLIENTE"></span>Si
                                                        </label>
                                                    </div>
                                                </div>

                                                <h3 class="mx-4 mt-3">Seleccione las opciones que desee mostrar en la Portada del Informe (Instalación)</h3>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 1 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA1" name="OPCION_PORTADA1">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 2 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA2" name="OPCION_PORTADA2">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 3 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA3" name="OPCION_PORTADA3">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 4 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA4" name="OPCION_PORTADA4">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 5 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA5" name="OPCION_PORTADA5">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Opción 6 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="OPCION_PORTADA6" name="OPCION_PORTADA6">
                                                        </select>
                                                    </div>
                                                </div>



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
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL1" name="NIVEL1">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 2 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL2" name="NIVEL2">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 3 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL3" name="NIVEL3">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 4 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL4" name="NIVEL4">

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label> Nivel 5 </label>
                                                        <select class="custom-select form-control" style="width: 50%;" id="NIVEL5" name="NIVEL5">

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
                                        <!-- DESCOMENTAR CUANDO SE HAGA LO DE CONTROL DE CAMBIOS -->
                                        <!--  @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                        <button type="button" class="btn btn-info mt-1" style="background: #1E88E6!important; float: right; display: none;" data-toggle="tooltip" title="Descargar informe sensorial de químicos .doc" id="boton_descargarquimicosdoc" onclick="reporte(form_recsensorial.recsensorial_id.value, 2, this);">
                                            Descargar &nbsp;&nbsp;<i class="fa fa-file-word-o fa-1x"></i>
                                        </button>
                                        @endif -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: end;">


                <!-- <button type="button" class="btn btn-info" style="float: right; background: #1E88E6!important; display: none;" data-toggle="tooltip" title="Descargar informe de reconocimiento químicos autorizado .pdf" id="boton_descargarquimicospdf">
                    Descargar pdf&nbsp;&nbsp;<i class="fa fa-file-pdf-o fa-1x"></i>
                </button> -->

                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                <button type="button" class="btn btn-info mt-1" style="background: #1E88E6!important; float: left; display: block;" data-toggle="tooltip" title="Descargar informe sensorial de químicos .doc" id="boton_descargarquimicosdoc" onclick="reporte(form_recsensorial.recsensorial_id.value, 2, this, 1);">
                    Descargar previa &nbsp;&nbsp;<i class="fa fa-eye fa-1x"></i>
                </button>

                <button type="button" class="btn btn-info mt-1" style="background: #94B732!important; float: left; display: block;" data-toggle="tooltip" title="Descargar informe sensorial de químicos y anexos .zip" id="boton_descargarquimicosdoc_final" onclick="reporte(form_recsensorial.recsensorial_id.value, 2, this, 2);">
                    Descargar final &nbsp;&nbsp;<i class="fa fa-file-archive-o fa-1x"></i>
                </button>
                @endif

                <button type="button" class="btn btn-default waves-effect mx-3" style="float: left;" data-dismiss="modal">Cerrar</button>
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
                        <div class="col-12">
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
                            </div>
                    <div class="row">
                        <div class="col-12">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Psicólogo']))
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
    
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_area">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
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
                        <div class="col-6">
                            <div class="form-group">
                                <label> Tipo *</label>
                                <select class="custom-select form-control" id="recsensorialmaquinaria_afecta" name="recsensorialmaquinaria_afecta" required>
                                    <option value=""></option>
                                    <option value="1">Físicos</option>
                                    <option value="2">Químicos</option>
                                    <option value="3">Físicos y Químicos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Descripción de la fuente generadora *</label>
                                <input type="text" class="form-control" id="descripcionfuente" name="recsensorialmaquinaria_descripcionfuente">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label> Nombre común</label>
                                <input type="text" class="form-control" id="nombrecomun" name="recsensorialmaquinaria_nombrecomun">
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label> Nombre de la fuente generadora *</label>
                                <input type="text" class="form-control" name="recsensorialmaquinaria_nombre" id="recsensorialmaquinaria_nombre" required>
                                <select class="custom-select form-control" style="display: none;" id="recsensorialmaquinaria_quimica" name="recsensorialmaquinaria_quimica">
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label> Cantidad *</label>
                                <input type="number" class="form-control" name="recsensorialmaquinaria_cantidad" id="recsensorialmaquinaria_cantidad" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label> Contenido*</label>
                                <input type="number" class="form-control" name="recsensorialmaquinaria_contenido" id="recsensorialmaquinaria_contenido" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label> Unidad de medida *</label>
                                <select class="custom-select form-control" id="recsensorialmaquinaria_unidadMedida" name="recsensorialmaquinaria_unidadMedida" required>
                                    <option value="">&nbsp;</option>
                                    <option value="1">Mililitros (mm)</option>
                                    <option value="2">Litros (L)</option>
                                    <option value="3">Metros cúbicos </option>
                                    <option value="4">Gramos (g)</option>
                                    <option value="5">Kilos (Kl)</option>
                                    <option value="6">Toneladas (T)</option>
                                    <option value="7">Piezas (pz)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Guardamos los tipos en una variable -->
                    <script type="text/javascript">
                       
                    </script>

                    <div class="listaAreasAfectadas"></div>


                </div>
                <div class="modal-footer" style="display: flex; justify-content: flex-start;">
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                    <button type="button" class="btn btn-danger waves-effect waves-light" id="boton_agregar_alcance">
                        Agregar Alcance <i class="fa fa-plus"></i>
                    </button>
                    @endif
                    <div style="flex-grow: 1;"></div>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                        <div class="col-12">
                            <div class="form-group">
                                <label> Numero de orden*</label>
                                <input type="number" class="form-control text-center" id="recsensorialanexo_orden" name="recsensorialanexo_orden" required>
                            </div>
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
                        <div class="col-4" data-toggle="tooltip" title="Seleccione esta opción si el anexo a agregar son las Hojas de seguridad">
                            <label class="text-danger">Hojas de seguridad </label>
                            <div class="switch">
                                <label>
                                    No<input type="checkbox" id="hojas_seguridad" name="hojas_seguridad" value="1" onchange="validarHojaSeguridad(this);">
                                    <span class="lever switch-col-light-blue" id="checkbox_hojaSeguridad"></span>Si
                                </label>
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
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