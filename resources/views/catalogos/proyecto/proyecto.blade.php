@extends('template/maestra')
@section('contenido')


{{-- ========================================================================= --}}


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


<div class="row page-titles">
    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Proyecto</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">No. XXXXXX</li>
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


<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" id="tab_menu1" role="tab">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de proyectos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" id="tab_menu2" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Datos del proyecto</span>
                    </a>
                </li>

                <!-- <li class="nav-item" style="display: none;">
                    <a class="nav-link" data-toggle="tab" href="#tab_3" id="tab_menu3" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Informes y evidencias</span>
                    </a>
                </li> -->

                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_4" id="tab_menu4" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Cronograma de trabajo</span>
                    </a>
                </li>
                @endif
            </ul>
            <!-- Tab Panels -->
            <div class="tab-content">
                <!-- Tab 1 -->
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                    <ol class="breadcrumb m-b-10 mb-4">

                        <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-briefcase" aria-hidden="true"></i> Proyectos </h2>
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo proyecto" style="margin-left:auto" id="boton_nuevo_proyecto">
                            Proyecto <i class="fa fa-plus p-1"></i>
                        </button>
                    </ol>
                    @endif

                    <!-- Tipos de proyectos -->
                    <nav>
                        <div class="nav nav-tabs" role="tablist">
                            <a class="nav-item nav-link" id="tab_proyectos_cliente" data-toggle="tab" href="#nav_proyectos_clientes" role="tab" aria-selected="true">Proyectos <span class="badge badge-danger" id="NumProyectos">#</span></a>
                            <a class="nav-item nav-link" id="tab_proyecto_interno" data-toggle="tab" href="#nav_proyectos_internos" role="tab" aria-selected="false">Proyectos Internos <span class="badge badge-warning" id="NumProyectosInternos">#</span></a>

                        </div>
                    </nav>
                    <div class="tab-content">
                        <!-- Proyectos con relacion a clientes -->
                        <div class="tab-pane fade show" id="nav_proyectos_clientes" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover stylish-table" width="100%" id="tabla_proyectos">
                                    <thead>
                                        <tr>
                                            <th width="60">No</th>
                                            <th width="130">Folio</th>
                                            <th width="220">Empresa</th>
                                            <th width="">Instalación / Dirección</th>
                                            <th width="130">Inicio / Fin</th>
                                            <th width="150">Servicios</th>
                                            <th width="60">Mostrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Proyectos Internos (Volumentria) -->
                        <div class="tab-pane fade" id="nav_proyectos_internos" role="tabpanel">

                            <div class="table-responsive">
                                <table class="table table-hover stylish-table" width="100%" id="tabla_proyectos_internos">
                                    <thead>
                                        <tr>
                                            <th width="60">No</th>
                                            <th width="130">Folio</th>
                                            <th width="220">Empresa</th>
                                            <th width="">Instalación / Dirección</th>
                                            <th width="130">Inicio / Fin</th>
                                            <th width="180">Servicios</th>
                                            <th width="60">Mostrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Tab 2 -->
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
                                                <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
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
                    <!-- ============= STEPS FORM ============= -->
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div style="min-width: 700px; width: 100% ;margin: 0px auto;">
                            <!--multisteps-form-->
                            <div class="multisteps-form">
                                <!--progress bar-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__progress" style="padding-top: 0px;">
                                            <div class="multisteps-form__progress-btn js-active" id="steps_menu_tab1">
                                                <i class="fa fa-file-text-o"></i><br>
                                                <span>Datos del proyecto</span>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'CoordinadorHI']))
                                            <div class="multisteps-form__progress-btn" style="display: none;" id="steps_menu_tab2">
                                                <i class="fa fa-user"></i><br>
                                                <span>Proveedores</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" style="display: none;" id="steps_menu_tab3">
                                                <i class="fa fa-address-card-o"></i><br>
                                                <span>Signatarios</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" style="display: none;" id="steps_menu_tab4">
                                                <i class="fa fa-desktop"></i><br>
                                                <span>Equipos</span>
                                            </div>
                                            @endif
                                            <div class="multisteps-form__progress-btn" style="display: none;" id="steps_menu_tab5">
                                                <i class="fa fa-print"></i><br>
                                                <span>Reportes</span>
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
                                                    <!-- Seccion de datos generales -->
                                                    <div class="row" id="seccion_datosproyecto">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">

                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <h4 class="card-title">Datos del proyecto</h4>
                                                                        </div>
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

                                                                            .rol_lista:hover label {
                                                                                color: #000000;
                                                                                font-weight: bold;
                                                                            }

                                                                            .servicios {
                                                                                color: #93B633;
                                                                                font-weight: bold;
                                                                                font-size: 17px;
                                                                            }
                                                                        </style>
                                                                        <div class="col-10 d-flex align-items-center">
                                                                            <label class="text-danger me-2">¿El proyecto es Interno? </label>
                                                                            <div class="switch mx-4 rol_lista" data-toggle="tooltip" title="Al aceptar esta opción el proyecto tendrá un folio de Proyectos Internos. Ejem: RES-PI-XX-XXX">
                                                                                <label>
                                                                                    No<input type="checkbox" id="proyectoInternoCheck" value="1" onchange="cambiarFolio(this)">
                                                                                    <span class=" lever switch-col-light-blue"></span>Si
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <hr>
                                                                    <form method="post" enctype="multipart/form-data" name="form_proyecto" id="form_proyecto">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <ol class="breadcrumb m-b-10">
                                                                                    <p style="color: #fff;">Datos de la empresa encargada del proyecto</p>
                                                                                </ol>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                {!! csrf_field() !!}
                                                                                <input type="hidden" class="form-control" id="proyecto_id" name="proyecto_id">
                                                                                <input type="hidden" class="form-control" id="proyectoInterno" name="proyectoInterno" value="0">
                                                                                <input type="hidden" id="requiereContrato" name="requiereContrato" value="1">


                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Proyecto No. </label>
                                                                                    <input type="text" class="form-control" id="proyecto_folio" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Fecha de creación* </label>
                                                                                    <input type="text" class="form-control" id="proyecto_fechacreacion" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> No orden servicio </label>
                                                                                    <input type="text" class="form-control" id="proyecto_ordenservicio" name="proyecto_ordenservicio">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> No cotización </label>
                                                                                    <input type="text" class="form-control" id="proyecto_cotizacion" name="proyecto_cotizacion">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Razón social * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_razonsocial" name="proyecto_razonsocial" value="Results In Performance S.A. de C.V." required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> RFC * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_rfc" name="proyecto_rfc" value="RIP1706223K9" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label> Dirección * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_direccion" name="proyecto_direccion" value="Carmen Cadena de Buendía No. 128, Col. Nueva Villahermosa." required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 mb-2 mt-1 d-flex align-items-center">
                                                                                <label class="me-2" style="font-weight: bold;" id="labelContactos">Obtener contactos registrados de Results In Performance</label>
                                                                                <div class="switch mx-4">
                                                                                    <label>
                                                                                        No<input type="checkbox" onchange="obtenerContactos(this);">
                                                                                        <span class="lever switch-col-light-blue"></span>Si
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Nombre del Contacto * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_contacto" name="proyecto_contacto" value="Virginia Licona Andrade">

                                                                                    <select class="custom-select form-control opcion-select" id="PROYECTO_CONTACTO_SELECT" name="proyecto_contacto" style="display: none;" disabled>
                                                                                        <option value=""></option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Correo contacto * </label>
                                                                                    <input type="email" class="form-control" id="proyecto_contactocorreo" name="proyecto_contactocorreo" value="vlicona@results-in-performance.com" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Teléfono contacto (Ext.) * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_contactotelefono" name="proyecto_contactotelefono" value="9933146412" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Celular contacto * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_contactocelular" name="proyecto_contactocelular" value="9931472682" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Ciudad / País * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_ciudadpais" name="proyecto_ciudadpais" value="Villahermosa, México" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Persona que elabora (PO) * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_personaelabora" name="proyecto_personaelabora" value="María Isabel Aguilar Alvarez">

                                                                                    <select class="custom-select form-control opcion-select" id="PERSONA_ELABORA_SELECT" name="proyecto_personaelabora" style="display: none;" disabled>
                                                                                        <option value=""></option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Persona que aprueba (PJ) * </label>
                                                                                    <input type="text" class="form-control" id="proyecto_personaaprueba" name="proyecto_personaaprueba" value="Leonardo Cuellar Chala" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <div class="form-group">
                                                                                    <label> Fecha elaboración *</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="proyecto_fechaelaboracion" name="proyecto_fechaelaboracion" required>
                                                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 mt-2">
                                                                                <div id="datosReconocimientos" class="row">
                                                                                    <div class="col-12">
                                                                                        <ol class="breadcrumb m-b-10">
                                                                                            <p style="color: #fff;">Datos del servicio</p>
                                                                                        </ol>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css">
                                                                                                .selectize-control.single .selectize-input {
                                                                                                    border: 2px #93B633 solid;
                                                                                                }

                                                                                                .selectize-dropdown-content {
                                                                                                    max-height: 220px;
                                                                                                }

                                                                                                .selectize-control.single .selectize-input .item {
                                                                                                    color: #93B633 !important;
                                                                                                }
                                                                                            </style>
                                                                                            <h3 class="clienteblock text-center">Formalización del servicio por:</h3>
                                                                                            <div class="col-12 mt-2 p-2 d-flex justify-content-center  clienteblock">
                                                                                                <div class="form-check mx-4 clienteblock">
                                                                                                    <input class="form-check-input servCliente" type="radio" name="tipoServicioCliente" id="tipoServicioCliente1" value="1" onchange="cambiarSelectContrato(1)" checked>
                                                                                                    <label class="form-check-label" for="tipoServicioCliente1" id="labelServicio1">
                                                                                                        Contrato
                                                                                                    </label>
                                                                                                </div>
                                                                                                <div class="form-check mx-4 clienteblock">
                                                                                                    <input class="form-check-input servCliente" type="radio" name="tipoServicioCliente" id="tipoServicioCliente2" value="2" onchange="cambiarSelectContrato(2)">
                                                                                                    <label class="form-check-label" for="tipoServicioCliente2" id="labelServicio2">
                                                                                                        O.S / O.C
                                                                                                    </label>
                                                                                                </div>
                                                                                                <div class="form-check mx-4 clienteblock">
                                                                                                    <input class="form-check-input servCliente" type="radio" name="tipoServicioCliente" id="tipoServicioCliente3" value="3" onchange="cambiarSelectContrato(3)">
                                                                                                    <label class="form-check-label" for="tipoServicioCliente3" id="labelServicio3">
                                                                                                        Cotización aceptada
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- <div class="d-flex align-items-center mb-2 mt-2 d-none">
                                                                                                <div class="switch mx-1">
                                                                                                    <label>
                                                                                                        No<input type="checkbox" id="requiereContrato" name="requiereContrato" value="1" onchange="desbloquearContrato(this)" checked>
                                                                                                        <span class=" lever switch-col-light"></span>Si
                                                                                                    </label>
                                                                                                </div>
                                                                                                <label style="color: #93B633; font-weight: 600;" class="mx-3" id="laberContrato">Contratos (disponibles)</label>
                                                                                            </div> -->

                                                                                            <select class="custom-select form-control botonnuevo_moduloproyecto" style="border: 2px #93B633 solid;" id="contrato_id" name="contrato_id" onchange="consultar_contrato(this.value);">
                                                                                                <option value="">&nbsp;</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label id="labelRazonSocial"> Razón social * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clienterazonsocial" name="proyecto_clienterazonsocial">

                                                                                            <select class="custom-select form-control" style="border: 2px #93B633 solid;" id="cliente_id" name="cliente_id" onchange="consultar_cliente(this.value);">
                                                                                                <option value="">&nbsp;</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Nombre comercial * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientenombrecomercial" name="proyecto_clientenombrecomercial">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> RFC * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clienterfc" name="proyecto_clienterfc">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Giro de la empresa * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientegiroempresa" name="proyecto_clientegiroempresa">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="row mb-3" id="estructuraOrganizacional">
                                                                                            <div class="col-12 text-center">
                                                                                                <h3 class="mb-2" style="display: none;" id="titleOrganizacion">Estructura organizacional</h3>
                                                                                                <input type="hidden" class="form-control" id="TIENE_ESTRUCTURA" name="TIENE_ESTRUCTURA" value="0">
                                                                                            </div>

                                                                                            <div class="col-6 selector-group NIVEL1" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Nivel 1 (Considerado como el más alto) </label>
                                                                                                    <input type="text" class="form-control" id="ETIQUETA1" readonly>
                                                                                                    <input type="hidden" class="form-control ORGA" id="ID_ETIQUETA1" name="ETIQUETA_ID[]">
                                                                                                    <input type="hidden" class="form-control ORGA" id="NIVEL1" name="NIVEL[]" value="1">


                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-6 selector-group NIVEL1" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Clasificación </label>
                                                                                                    <select class="custom-select form-control opcion-select ORGA" id="OPCIONES1_ID" name="OPCION_ID[]">
                                                                                                        <option value=""></option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-6 selector-group NIVEL2" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Nivel 2 </label>
                                                                                                    <input type="text" class="form-control" id="ETIQUETA2" readonly>
                                                                                                    <input type="hidden" class="form-control ORGA" id="ID_ETIQUETA2" name="ETIQUETA_ID[]">
                                                                                                    <input type="hidden" class="form-control ORGA" id="NIVEL2" name="NIVEL[]" value="2">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class=" col-6 selector-group NIVEL2" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Clasificación </label>
                                                                                                    <select class="custom-select form-control opcion-select ORGA" id="OPCIONES2_ID" name="OPCION_ID[]">
                                                                                                        <option value=""></option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-6 selector-group NIVEL3" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Nivel 3 </label>
                                                                                                    <input type="text" class="form-control" id="ETIQUETA3" readonly>
                                                                                                    <input type="hidden" class="form-control ORGA" id="ID_ETIQUETA3" name="ETIQUETA_ID[]">
                                                                                                    <input type="hidden" class="form-control ORGA" id="NIVEL3" name="NIVEL[]" value="3">

                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-6 selector-group NIVEL3" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Clasificación</label>
                                                                                                    <select class="custom-select form-control opcion-select ORGA" id="OPCIONES3_ID" name="OPCION_ID[]">
                                                                                                        <option value=""></option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-6 selector-group NIVEL4" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Nivel 4 </label>
                                                                                                    <input type="text" class="form-control" id="ETIQUETA4" readonly>
                                                                                                    <input type="hidden" class="form-control ORGA" id="ID_ETIQUETA4" name="ETIQUETA_ID[]">
                                                                                                    <input type="hidden" class="form-control ORGA" id="NIVEL4" name="NIVEL[]" value="4">


                                                                                                </div>
                                                                                            </div>
                                                                                            <div class=" col-6 selector-group NIVEL4" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Clasificación </label>
                                                                                                    <select class="custom-select form-control opcion-select ORGA" id="OPCIONES4_ID" name="OPCION_ID[]">
                                                                                                        <option value=""></option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-6 selector-group NIVEL5" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Nivel 5 </label>
                                                                                                    <input type="text" class="form-control" id="ETIQUETA5" readonly>
                                                                                                    <input type="hidden" class="form-control ORGA" id="ID_ETIQUETA5" name="ETIQUETA_ID[]">
                                                                                                    <input type="hidden" class="form-control ORGA" id="NIVEL5" name="NIVEL[]" value="5">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class=" col-6 selector-group NIVEL5" style="display: none;">
                                                                                                <div class="form-group">
                                                                                                    <label>Clasificación </label>
                                                                                                    <select class="custom-select form-control opcion-select ORGA" id="OPCIONES5_ID" name="OPCION_ID[]">
                                                                                                        <option value=""></option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Region *</label>
                                                                                            <select class="custom-select form-control" id="catregion_id" name="catregion_id">
                                                                                                <option value=""></option>
                                                                                                @foreach($catregion as $dato)
                                                                                                <option value="{{$dato->id}}">{{$dato->catregion_nombre}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Subdireccion *</label>
                                                                                            <select class="custom-select form-control" id="catsubdireccion_id" name="catsubdireccion_id">
                                                                                                <option value=""></option>
                                                                                                @foreach($catsubdireccion as $dato)
                                                                                                <option value="{{$dato->id}}">{{$dato->catsubdireccion_siglas}} - {{$dato->catsubdireccion_nombre}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Gerencia *</label>
                                                                                            <select class="custom-select form-control" id="catgerencia_id" name="catgerencia_id">
                                                                                                <option value=""></option>
                                                                                                @foreach($catgerencia as $dato)
                                                                                                <option value="{{$dato->id}}">{{$dato->catgerencia_siglas}} - {{$dato->catgerencia_nombre}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Activo *</label>
                                                                                            <select class="custom-select form-control" id="catactivo_id" name="catactivo_id">
                                                                                                <option value=""></option>
                                                                                                @foreach($catactivo as $dato)
                                                                                                <option value="{{$dato->id}}">{{$dato->catactivo_siglas}} - {{$dato->catactivo_nombre}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div> -->
                                                                                    <div class="col-6">
                                                                                        <div class="form-group">
                                                                                            <label> Nombre de la instalación * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clienteinstalacion" name="proyecto_clienteinstalacion">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <div class="form-group">
                                                                                            <label> Dirección del servicio * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientedireccionservicio" name="proyecto_clientedireccionservicio">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> A quien se dirige el informe * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientepersonadirigido" name="proyecto_clientepersonadirigido">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Nombre del contacto * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientepersonacontacto" name="proyecto_clientepersonacontacto">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-2">
                                                                                        <div class="form-group">
                                                                                            <label> Tel. contacto (Ext.) * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientetelefonocontacto" name="proyecto_clientetelefonocontacto">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-2">
                                                                                        <div class="form-group">
                                                                                            <label> Celular contacto * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_clientecelularcontacto" name="proyecto_clientecelularcontacto">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-2">
                                                                                        <div class="form-group">
                                                                                            <label> Correo contacto * </label>
                                                                                            <input type="email" class="form-control" id="proyecto_clientecorreocontacto" name="proyecto_clientecorreocontacto">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label> Descripción del servicio *</label>
                                                                                            <textarea class="form-control" rows="6" id="proyecto_clienteobjetivoservicio" name="proyecto_clienteobjetivoservicio"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label> Observaciones *</label>
                                                                                            <textarea class="form-control" rows="6" id="proyecto_clienteobservacion" name="proyecto_clienteobservacion"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="servicios" class="row mx-3 mb-3 p-1" style="border: 1px solid #0B3F64; border-radius: 10px; width:100%">

                                                                                        <div class="col-12 text-center mb-3 mt-2">
                                                                                            <h3 class="mb-2" id="titleServicios">Tipos de servicios</h3>
                                                                                        </div>
                                                                                        <!-- Servicio de Higione Industria -->
                                                                                        <div class="col-4 mb-3">
                                                                                            <div class="col-12 d-flex align-items-center">
                                                                                                <label class="me-2 servicios">Servicio de Higiene Industrial </label>
                                                                                                <div class="switch mx-4 rol_lista" data-toggle="tooltip" title="Módulo de Higiene Industrial">
                                                                                                    <label>
                                                                                                        No<input type="checkbox" id="proyectoServicioHi" name="HI" value="1" onchange="mostrarOpcionesServicios(this, 1)">
                                                                                                        <span class=" lever switch-col-light-blue"></span>Si
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- Opciones del Servicio -->
                                                                                            <div id="opciones_hi" class="mt-1">
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Programa de trabajo </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="hiPrograma" name="HI_PROGRAMA" value="1">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Reconocimiento </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="hiReconocimiento" name="HI_RECONOCIMIENTO" value="1">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Ejecución (on-site) </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="hiEjecucion" name="HI_EJECUCION" value="1">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Informes </li>
                                                                                                    <div class="switch mx-4">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="hiInformes" name="HI_INFORME" value="1">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Servicio ergonomico -->
                                                                                        <div class="col-4 mb-3">
                                                                                            <div class="col-12 d-flex align-items-center">
                                                                                                <label class="me-2 servicios">Servicio de FR. Ergonómico</label>
                                                                                                <div class="switch mx-4 rol_lista" data-toggle="tooltip" title="Módulo de FR. Ergonómico.">
                                                                                                    <label>
                                                                                                        No<input type="checkbox" id="proyectoServicioErgo" name="ERGO" value="1" onchange="mostrarOpcionesServicios(this, 2)">
                                                                                                        <span class=" lever switch-col-light-blue"></span>Si
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Opciones del Servicio -->
                                                                                            <div id="opciones_ergo" class="mt-1" style="display: none;">
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Programa de trabajo </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="ergoPrograma" value="1" name="ERGO_PROGRAMA">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Reconocimiento </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="ergoReconocimiento" value="1" name="ERGO_RECONOCIMIETO">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Ejecución (on-site) </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="ergoEjecucion" value="1" name="ERGO_EJECUCION">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Informes </li>
                                                                                                    <div class="switch mx-4">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="ergoInformes" value="1" name="ERGO_INFORME">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                        <!-- Servicio Psico -->
                                                                                        <div class="col-4 mb-3">
                                                                                            <div class="col-12 d-flex align-items-center">
                                                                                                <label class="me-2 servicios">Servicio de FR. Psicosocial </label>
                                                                                                <div class="switch mx-4 rol_lista" data-toggle="tooltip" title="Módulo de FR. Psicosocial.">
                                                                                                    <label>
                                                                                                        No<input type="checkbox" id="proyectoServicioPsico" value="1" name="PSICO" onchange="mostrarOpcionesServicios(this, 3)">
                                                                                                        <span class=" lever switch-col-light-blue"></span>Si
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- Opciones del Servicio -->
                                                                                            <div id="opciones_psico" class="mt-1" style="display: none;">
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Programa de trabajo </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="psicoPrograma" value="1" name="PSICO_PROGRAMA">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Reconocimiento </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="psicoReconocimiento" value="1" name="PSICO_RECONOCIMIENTO">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Ejecución (on-site) </li>
                                                                                                    <div class="switch mx-4 ">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="psicoEjecucion" value="1" name="PSICO_EJECUCION">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-12 mx-3 d-flex align-items-center">
                                                                                                    <li class="me-2">Informes </li>
                                                                                                    <div class="switch mx-4">
                                                                                                        <label>
                                                                                                            No<input type="checkbox" id="psicoInformes" value="1" name="PSICO_INFORME">
                                                                                                            <span class=" lever switch-col-light-blue"></span>Si
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Fecha inicio del proyecto *</label>
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="proyecto_fechainicio" name="proyecto_fechainicio" onchange="calculafechafin();">
                                                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Duración del proyecto (días) * </label>
                                                                                            <input type="number" class="form-control" id="proyecto_totaldias" name="proyecto_totaldias" onchange="calculafechafin();">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Fecha de fin del proyecto * </label>
                                                                                            <input type="text" class="form-control" id="proyecto_fechafin" name="proyecto_fechafin" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <div class="form-group">
                                                                                            <label> Fecha entrega del proyecto *</label>
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" placeholder="aaaa-mm-dd" id="proyecto_fechaentrega" name="proyecto_fechaentrega">
                                                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                                                <div class="form-group" style="text-align: left;">
                                                                                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_proyecto" value="0" onclick="bloqueo_proyecto(this.value);">
                                                                                        <span class="btn-label"><i class="fa fa-unlock"></i></span> Proyecto desbloqueado para edición
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-6">
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
                                                                                <div class="form-group" style="text-align: right;">
                                                                                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_proyecto">
                                                                                        Guardar <i class="fa fa-save"></i>
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Seccion de Asignacion de usuarios -->
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                    <div class="row" id="seccion_asignacion_usuarios">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <h4 class="card-title mx-3">Asignación de usuarios al proyecto</h4>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <ol class="breadcrumb m-b-10" style="padding: 6px;">
                                                                                <button type="button" class="btn btn-default waves-effect botonnuevo_moduloproyecto" style="float: left" data-toggle="tooltip" title="Asignar un nuevo usuario" id="boton_asignar_usuario">
                                                                                    <span class="btn-label"><i class="fa fa-user-plus"></i></span> Asignar usuario
                                                                                </button>

                                                                            </ol>
                                                                            <div class="table-responsive">
                                                                                <style type="text/css">
                                                                                    #tabla_ordenservicios td {
                                                                                        padding-top: 2px !important;
                                                                                        padding-bottom: 2px !important;
                                                                                    }
                                                                                </style>
                                                                                <table class="table table-hover stylish-table" width="100%" id="tabla_usuarios_asignados">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th style="width: 80px!important;">No</th>
                                                                                            <th style="width: 200px!important;">Nombre</th>
                                                                                            <th style="width: 200px!important;">Fecha de asignación</th>
                                                                                            <th style="width: 200px!important;">Servicios asignados</th>
                                                                                            <th style="width: 200px!important;">Estado</th>
                                                                                            <th style="width: 200px!important;">Acción</th>
                                                                                            <th style="width: 200px!important;">Editar</th>
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
                                                    @endif
                                                    <!-- Seccion de Ordenes de servicio -->
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                    <div class="row" id="seccion_ordenes_servicio">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <h4 class="card-title">Orden(es) de servicios</h4>
                                                                        </div>
                                                                        <div class="col-10 d-flex align-items-center">
                                                                            <label class="text-danger me-2">¿Cuenta con una OS o contrato? </label>
                                                                            <div class="switch mx-4">
                                                                                <label>
                                                                                    No<input type="checkbox" id="solicitudOS" name="solicitudOS" onchange="solicitarOS(this);">
                                                                                    <span class="lever switch-col-light-blue" id="checkbox_solicitudOS"></span>Si
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <ol class="breadcrumb m-b-10" style="padding: 6px;">
                                                                                <button type="button" class="btn btn-default waves-effect botonnuevo_moduloproyecto" style="float: left" data-toggle="tooltip" title="Nuevo orden de servicio" id="boton_nueva_ordenservicio" disabled>
                                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva orden de servicio
                                                                                </button>

                                                                                <button type="button" class="btn btn-default waves-effect botonnuevo_moduloproyecto" style="float: right;" data-toggle="tooltip" title="Nuevo documento adicional para OS" id="boton_nueva_ordenservicioadicional" disabled>
                                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nuevo documento adicional a la OS
                                                                                </button>
                                                                            </ol>
                                                                            <div class="table-responsive">
                                                                                <style type="text/css">
                                                                                    #tabla_ordenservicios td {
                                                                                        padding-top: 2px !important;
                                                                                        padding-bottom: 2px !important;
                                                                                    }
                                                                                </style>
                                                                                <table class="table table-hover stylish-table" width="100%" id="tabla_ordenservicios">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th style="width: 80px!important;">No</th>
                                                                                            <th style="width: 200px!important;">No. Oficio / Doc. adicional</th>
                                                                                            <th style="width: 200px!important;">No. Orden</th>
                                                                                            <th style="width: 200px!important;">No. Cotización</th>
                                                                                            <th>Persona que verificó</th>
                                                                                            <th style="width: 80px!important;">Verificada</th>
                                                                                            <th style="width: 80px!important;">Mostrar</th>
                                                                                            <th style="width: 80px!important;">Eliminar</th>
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
                                                    @endif
                                                    <!-- Seccion de prorrogas -->
                                                    <div class="row" id="seccion_prorrogas">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Prórrogas</h4>
                                                                    <hr>
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                                    <ol class="breadcrumb m-b-10" style="padding: 6px;">
                                                                        <button type="button" class="btn btn-default waves-effect botonnuevo_moduloproyecto" data-toggle="tooltip" title="Nueva prórroga" id="boton_nueva_prorroga" disabled>
                                                                            <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva prórroga
                                                                        </button>
                                                                    </ol>
                                                                    @endif
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_prorrogas">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="50">No</th>
                                                                                <th width="">Fecha inicio</th>
                                                                                <th width="">Fecha fin</th>
                                                                                <th width="">Total días</th>
                                                                                <th width="60">Editar</th>
                                                                                <th width="60">Eliminar</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="6">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Encabezado de volumentria -->
                                                    <div class="row mx-2">
                                                        <ol class="breadcrumb m-b-10 mb-4 w-100" id="encavezadovolumetria">
                                                            <h3 style="color: #ffff; margin: 0;"> <i class="fa fa-cubes" aria-hidden="true"></i> Volumetria </h3>
                                                        </ol>
                                                    </div>
                                                    <!-- Seccion de Volumentria -->
                                                    <div class="row" id="seccion_recsensorialresumenfisico">
                                                        <div class="col-12 mx-2">
                                                            <div class="form-group">
                                                                <style type="text/css">
                                                                    .selectize-control.single .selectize-input {
                                                                        border: 2px #007bff solid;
                                                                    }

                                                                    .selectize-dropdown-content {
                                                                        max-height: 220px;
                                                                    }

                                                                    .selectize-control.single .selectize-input .item {
                                                                        color: #007bff !important;
                                                                    }
                                                                </style>
                                                                <label style="color: #007bff; font-weight: 600;"> Reconocimiento sensorial vinculado</label>
                                                                <select class="custom-select form-control botonnuevo_moduloproyecto" style="border: 2px #007bff solid;" id="recsensorial_id" name="recsensorial_id" onchange="consulta_recsensorial(this.value, 1);" readonly>
                                                                    <option value="">&nbsp;</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Resumen reconocimiento sensorial <b id="folio_fisicos">&nbsp;</b></h4>
                                                                    <hr>
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_resumen_fisicos">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px!important;">No</th>
                                                                                <th>Factor de riesgo / Servicio</th>
                                                                                <th style="width: 80px!important;">Puntos</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6" id="seccion_recsensorialresumenquimico">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Resumen reconocimiento químicos <b id="folio_quimicos">&nbsp;</b></h4>
                                                                    <hr>
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_resumen_quimicos">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px!important;">No</th>
                                                                                <th>Agente</th>
                                                                                <th style="width: 80px!important;">Puntos</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <h4 class="card-title">Resumen reconocimiento químicos solicitados por el cliente <b id="folio_quimicos_cliente">&nbsp;</b></h4>
                                                                    <hr>
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_resumen_quimicos_clientes">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px!important;">No</th>
                                                                                <th>Agente</th>
                                                                                <th style="width: 80px!important;">Puntos</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'CoordinadorHI']))
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab2">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <form method="post" enctype="multipart/form-data" name="form_proyectoproveedores" id="form_proyectoproveedores">
                                                                {!! csrf_field() !!}
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">Asignar proveedores</h4>
                                                                        <hr>
                                                                        <table class="table table-hover stylish-table" width="100%" id="tabla_proyectoproveedores">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width: 50px!important;">No</th>
                                                                                    <th style="width: 80px!important;">Seleccionado</th>
                                                                                    <th style="width: 260px!important;">Proveedor</th>
                                                                                    <th style="width: 260px!important;">Agente / Factor de riesgo / Servicio</th>
                                                                                    <th style="width: 80px!important;">Total</th>
                                                                                    <!-- <th style="width: 130px!important;">Tipo instalación</th> -->
                                                                                    <th style="width: 100px!important;">Total actual</th>
                                                                                    <th>Observación</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="7">&nbsp;</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                        <hr>
                                                                        <div class="form-group">
                                                                            <button type="button" style="float: left;" class="btn btn-warning waves-effect waves-light botonnuevo_moduloproyecto" id="boton_proyectoproveedornuevapartida">
                                                                                <span class="btn-label"><i class="fa fa-plus"></i></span> Agregar adicional
                                                                            </button>
                                                                            <button type="submit" style="float: right;" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" id="boton_guardar_proyectoproveedores">
                                                                                Guardar <i class="fa fa-save"></i>
                                                                            </button>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab3">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Lista de signatarios asignados al proyecto</h4>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <form method="post" enctype="multipart/form-data" name="form_proyectosignatarios" id="form_proyectosignatarios">
                                                                                {!! csrf_field() !!}
                                                                                <style type="text/css" media="screen">
                                                                                    #tabla_proyectosignatarios td {
                                                                                        padding: 12px 20px 12px 2px;
                                                                                        border-top: 1px #EEEEEE solid;
                                                                                    }
                                                                                </style>
                                                                                <div style="border: 0px #999999 solid; margin-bottom: 20px;">
                                                                                    <table class="display table-hover stylish-table" width="100%" id="tabla_proyectosignatarios">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th width="60" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">No</th>
                                                                                                <th width="160" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Proveedor</th>
                                                                                                <th width="110" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Disponible</th>
                                                                                                <th width="80" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Asignado</th>
                                                                                                <th width="220" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Signatario</th>
                                                                                                <th width="auto" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Alcances</th>
                                                                                                <th width="300" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Entidad / Acreditación / Vigencia</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td colspan="7">&nbsp;</td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                                <div class="form-group" style="text-align: right;">
                                                                                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_proyectosignatarios">
                                                                                        Guardar <i class="fa fa-save"></i>
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Observaciones</h4>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_proyectosignatariosobservaciones">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="320">Proveedor</th>
                                                                                        <th>Observaciones</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>&nbsp;</td>
                                                                                        <td>&nbsp;</td>
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
                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab4">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Lista de equipos asignados al proyecto</h4>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <form method="post" enctype="multipart/form-data" name="form_proyectoequipos" id="form_proyectoequipos">
                                                                                <style type="text/css" media="screen">
                                                                                    #tabla_proyectoequipos td {
                                                                                        padding: 12px 20px 12px 2px;
                                                                                        border-top: 1px #EEEEEE solid;
                                                                                    }
                                                                                </style>
                                                                                <div style="border: 0px #999999 solid; margin-bottom: 20px;">
                                                                                    {!! csrf_field() !!}
                                                                                    <table class="display table-hover stylish-table" width="100%" id="tabla_proyectoequipos">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th width="60" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">No</th>
                                                                                                <th width="160" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Proveedor</th>
                                                                                                <th width="110" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Disponible</th>
                                                                                                <th width="80" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Asignado</th>
                                                                                                <th width="auto" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Equipo</th>
                                                                                                <th width="180" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Marca</th>
                                                                                                <th width="140" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Modelo</th>
                                                                                                <th width="140" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Serie</th>
                                                                                                <th width="160" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Vigencia calibración</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td colspan="9">&nbsp;</td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                                <div class="form-group" style="text-align: right;">
                                                                                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_proyectoequipos">
                                                                                        Guardar <i class="fa fa-save"></i>
                                                                                    </button>
                                                                                </div>
                                                                                @endif
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <h4 class="card-title">Observaciones</h4>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_proyectoequiposobservaciones">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="320">Proveedor</th>
                                                                                        <th>Observaciones</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>&nbsp;</td>
                                                                                        <td>&nbsp;</td>
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
                                            @endif
                                            <!--STEP 5-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab5">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <!-- Tab Menus -->
                                                                <ul class="nav nav-tabs customtab" role="tablist">
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Compras', 'Staff', 'Psicólogo', 'Ergónomo', 'CoordinadorPsicosocial', 'CoordinadorErgonómico', 'CoordinadorRN', 'CoordinadorRS', 'CoordinadorRM', 'CoordinadorHI', 'ApoyoTecnico']))
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes active" data-toggle="tab" id="reportetab_menu1" role="tab" href="#reportetab_1">
                                                                            <span class="hidden-xs-down">Orden de trabajo</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras']))
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes" data-toggle="tab" id="reportetab_menu2" role="tab" href="#reportetab_2">
                                                                            <span class="hidden-xs-down">Orden de compra</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes" data-toggle="tab" id="reportetab_menu3" role="tab" href="#reportetab_3">
                                                                            <span class="hidden-xs-down">Lista de signatarios</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes" data-toggle="tab" id="reportetab_menu4" role="tab" href="#reportetab_4">
                                                                            <span class="hidden-xs-down">Lista de equipos</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                                <!-- Tab Panels -->
                                                                <div class="tab-content">
                                                                    <div class="tab-pane p-20 active" id="reportetab_1" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                        <ol class="breadcrumb m-b-10">
                                                                            <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Generar nueva" id="boton_generar_ot">
                                                                                <span class="btn-label"><i class="fa fa-refresh"></i></span> Generar nueva orden de trabajo
                                                                            </button>
                                                                        </ol>
                                                                        @endif
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_ordentrabajo_historial">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="width: 50px!important;">No.</th>
                                                                                        <th>Orden de trabajo</th>
                                                                                        <th style="width: 200px!important;">Autorizado por:</th>
                                                                                        <th style="width: 200px!important;">Cancelado por:</th>
                                                                                        <th style="width: 90px!important;">Estado</th>
                                                                                        <th style="width: 70px!important;">Mostrar</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane p-20" id="reportetab_2" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras']))
                                                                        <ol class="breadcrumb m-b-10">
                                                                            <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Generar nueva" id="boton_proveedores_oc">
                                                                                <span class="btn-label"><i class="fa fa-refresh"></i></span> Generar nueva orden de compra
                                                                            </button>
                                                                        </ol>
                                                                        @endif
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_ordencompra_historial">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="50">No.</th>
                                                                                        <th width="180">Orden de compra</th>
                                                                                        <th>Proveedor</th>
                                                                                        <th width="200">Solicitado por:</th>
                                                                                        <th width="200">Autorizado por:</th>
                                                                                        <th width="200">Facturado por:</th>
                                                                                        <th width="200">Cancelado por:</th>
                                                                                        <th width="100">Estado</th>
                                                                                        <th width="60">Mostrar</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane p-20" id="reportetab_3" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                        <ol class="breadcrumb m-b-10">
                                                                            <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Generar nueva" id="boton_nueva_ls">
                                                                                <span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de signatarios
                                                                            </button>
                                                                        </ol>
                                                                        @endif
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_listasignatarios">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="width: 50px!important;">No.</th>
                                                                                        <th>Revisión</th>
                                                                                        <th style="width: 300px!important;">Autorizado por:</th>
                                                                                        <th style="width: 300px!important;">Cancelado por:</th>
                                                                                        <th style="width: 90px!important;">Estado</th>
                                                                                        <th style="width: 70px!important;">Mostrar</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane p-20" id="reportetab_4" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                                        <ol class="breadcrumb m-b-10">
                                                                            <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Generar nueva" id="boton_nueva_le">
                                                                                <span class="btn-label"><i class="fa fa-plus"></i></span>Generar nueva lista de equipos
                                                                            </button>
                                                                        </ol>
                                                                        @endif
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover stylish-table" width="100%" id="tabla_listaequipos">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th style="width: 50px!important;">No.</th>
                                                                                        <th>Revisión</th>
                                                                                        <th style="width: 300px!important;">Autorizado por:</th>
                                                                                        <th style="width: 300px!important;">Cancelado por:</th>
                                                                                        <th style="width: 90px!important;">Estado</th>
                                                                                        <th style="width: 70px!important;">Mostrar</th>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============= /STEPS FORM ============= -->
                </div>
                <!-- Tab 3 -->
                <div class="tab-pane p-20" id="tab_3" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o"></i></span>
                                                    </td>
                                                    <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
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
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" id="tabmenu_evidencia_3" href="#tab_evidencia_3" role="tab">Planos</a>
                                        </li>
                                    </ul>
                                    <div id="image-popups">
                                        <div class="tab-content" style="height: 800px; max-height: 800px!important; overflow-x: none; overflow-y: auto;">
                                            <div class="tab-pane p-20 active" id="tab_evidencia_1" role="tabpanel">
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                    <button type="button" class="btn btn-success waves-effect waves-light" style="margin-right: 20px;" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_puntosreales">
                                                        <span class="btn-label"><i class="fa fa-unlock"></i></span> Puntos reales desbloqueado para edición
                                                    </button>
                                                    @endif
                                                    <button type="button" class="btn waves-effect waves-light btn-info" style="display: none;" id="boton_imprimir_proyectopuntosreales">
                                                        <i class="fa fa-print"></i> Imprimir reporte
                                                    </button>
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'CoordinadorHI']))
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
                                        @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'ApoyoTecnico']))
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
                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
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
                <!-- Tab 4 -->
                <div class="tab-pane p-20" id="tab_4" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                            <!-- Cronograma de trabajo -->
                            <ol class="breadcrumb">
                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-calendar" aria-hidden="true"></i> Cronograma de trabajo </h2>

                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: 20px;" id="boton_nueva_actividad">
                                    Nueva Actividad <i class="fa fa-calendar-plus-o p-1"></i>
                                </button>

                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;background: #94B732;color: #fff;border: 1px solid #fff;" id="boton_autorizar_cronograma_modal">
                                    Validar o Autorizar cronograma <i class="fa fa fa-gavel p-1"></i>
                                </button>
                            </ol>
                            <div class="row mt-3 mb-1">
                                <div class="col-12 d-none" id="divAutorizacion">
                                    <div class="card">
                                        <div class="car-body">
                                            <div class="row p-2">
                                                <div class="col-12 d-none" id="infoValidacion">
                                                    <div class="row mx-2">
                                                        <div class="col-4">
                                                            <span style="color:#0B3F64">Fecha de validación : </span><span id="fechaValidacion"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <span style="color:#0B3F64">Cargo de quien valido : </span><span id="cargoValido"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <span style="color:#0B3F64">Nombre de quien valido : </span><span id="nombreValido"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-none" id="infoAutorizacion">
                                                    <div class="row mx-2">
                                                        <div class="col-4">
                                                            <span style="color: #94B732;">Fecha de autorización : </span><span id="fechaAutorizo"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <span style="color: #94B732;">Cargo de quien autorizo : </span><span id="cargoAutorizo"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <span style="color: #94B732;">Nombre de quien autorizo : </span><span id="nombreAutorizo"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="card" style="max-height: 625px; overflow-y: auto;">
                                        <div class="card-body">
                                            <h2 class="text-center">Actividades</h2>
                                            <style>
                                                .actividades-card {
                                                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                                                }

                                                .actividades-card:hover {
                                                    transform: scale(1.03);
                                                    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
                                                }
                                            </style>

                                            <div id="activity-list">

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="card" style="height: 96%;">
                                        <div class="card-body">
                                            <div id='calendar'>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal autorizacion del cronograma -->
<div id="modal_autorizacion" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_autorizado" id="form_autorizado">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Validación y autorización del cronograma de trabajo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_AUTORIZACION" name="ID_AUTORIZACION" value="0">
                        </div>

                        <div class="col-6" id="div-validacion">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Fecha de validación *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_VALIDACION_CRONOGRAMA" name="FECHA_VALIDACION_CRONOGRAMA" readonly>
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Cargo de quien valida</label>
                                    <input type="text" class="form-control" name="CARGO_VALIDACION_CRONOGRAMA" id="CARGO_VALIDACION_CRONOGRAMA" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre de quien valida</label>
                                    <input type="text" class="form-control" name="NOMBRE_VALIDACION_CRONOGRAMA" id="NOMBRE_VALIDACION_CRONOGRAMA" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="col-6" id="div-autorizacion">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Fecha de autorización *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_AUTORIZACION_CRONOGRAMA" name="FECHA_AUTORIZACION_CRONOGRAMA" readonly>
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Cargo de quien autoriza</label>
                                    <input type="text" class="form-control" name="CARGO_AUTORIZACION_CRONOGRAMA" id="CARGO_AUTORIZACION_CRONOGRAMA" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nombre de quien autoriza</label>
                                    <input type="text" class="form-control" name="NOMBRE_AUTORIZACION_CRONOGRAMA" id="NOMBRE_AUTORIZACION_CRONOGRAMA" readonly>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente btn_cronograma" id="boton_autorizar_cronograma"> Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal autorizacion del cronograma -->



<!-- Modal actividades del cronograma -->
<div id="modal_actividades" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_actividad" id="form_actividad">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Crear nueva actividad</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_ACTIVIDAD" name="ID_ACTIVIDAD" value="0">
                        </div>


                        <div class="col-6">
                            <div class="form-group">
                                <label>Fecha Inicio *</label>
                                <input type="datetime-local" class="form-control" id="FECHA_INICIO_ACTIVIDAD" name="FECHA_INICIO_ACTIVIDAD" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>Fecha Fin *</label>
                                <input type="datetime-local" class="form-control" id="FECHA_FIN_ACTIVIDAD" name="FECHA_FIN_ACTIVIDAD" required>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción de la actividad *</label>
                                <textarea class="form-control" style="text-align: center;" rows="4" name="DESCRIPCION_ACTIVIDAD" id="DESCRIPCION_ACTIVIDAD" required></textarea>
                            </div>
                        </div>

                        <div class="col-6">
                            <label>Agente</label>
                            <select class="form-control" name="AGENTE_ACTIVIDAD_ID" id="AGENTE_ACTIVIDAD_ID">
                                <option value=""></option>
                                @foreach($catpruebas as $dato)
                                <option value="{{$dato->id}}">[{{$dato->catPrueba_Tipo}}] {{$dato->catPrueba_Nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>Puntos</label>
                                <input type="number" class="form-control" name="PUNTOS_ACTIVIDAD" id="PUNTOS_ACTIVIDAD">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group" style="position: relative;">
                                <label>Color etiqueta</label>
                                <input type="color" class="form-control" name="COLOR_ACTIVIDAD" id="COLOR_ACTIVIDAD" value="#1E88E6" style="border-radius: 20px; padding: 5px; width: 100px; height: 40px; position: absolute; top: 100%; left: 0; display: none;">
                            </div>
                            <div id="etiqueta-div" style="border-radius: 20px; padding: 10px; cursor: pointer; text-align:center">
                                <h5 style="color: #ffffff;"><i class="fa fa-star" aria-hidden="true"></i> Etiqueta de actividad </h5>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente btn_cronograma" id="boton_guardar_actividad">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal actividades del cronograma -->

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
<!-- ORDEN SERVICIO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_ordenservicio .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_ordenservicio .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }

    #visor_menu_bloqueado {
        width: 498px;
        height: 52px;
        background: #555555;
        position: absolute;
        z-index: 500;
        margin: 2px 0px 0px 2px;
        /*border: 1px #F00 solid;*/
        display: none;
    }

    #visor_contenido_bloqueado {
        width: 498px;
        height: 560px;
        /*background: #555555;*/
        position: absolute;
        z-index: 600;
        margin: 1px 0px 0px 1px;
        /*border: 1px #F00 solid;*/
        display: none;
    }

    #visor_ordenserviciopdf {
        width: 100%;
        height: 544px;
        /*border: 1px #F00 solid;*/
        background: #555555;
    }
</style>
<div id="modal_ordenservicio" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="border: 0px #f00 solid; min-width: 1200px!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_ordenservicio" id="form_ordenservicio">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Orden de Servicio</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ordenservicio_id" name="ordenservicio_id" value="0">
                            <input type="hidden" class="form-control" id="ordenservicio_proyecto_id" name="proyecto_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>No. oficio *</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_oficio" name="proyectoordenservicio_oficio" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>No. orden *</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_numero" name="proyectoordenservicio_numero" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>No cotización *</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_cotizacion" name="proyectoordenservicio_cotizacion" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Total MXN (Sin iva) *</label>
                                        <input type="number" step="any" class="form-control" id="proyectoordenservicio_total" name="proyectoordenservicio_total" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>No. contrato</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_contrato" name="proyectoordenservicio_contrato">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>RAF</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_raf" name="proyectoordenservicio_raf">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>No. pedido</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_pedido" name="proyectoordenservicio_pedido">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Observacion </label>
                                        <textarea class="form-control" rows="3" id="proyectoordenservicio_observacion" name="proyectoordenservicio_observacion"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Documento PDF *</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput" id="campo_file_ordenservicio">
                                                <i class="fa fa-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-secondary btn-file">
                                                <span class="fileinput-new">Seleccione</span>
                                                <span class="fileinput-exists">Cambiar</span>
                                                <input type="file" accept="application/pdf" id="orderserviciopdf" name="orderserviciopdf" required>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="text-danger">¿OS verificada? *</label>
                                    <div class="switch">
                                        <label>
                                            No<input type="checkbox" id="proyectoordenservicio_validado" name="proyectoordenservicio_validado" onchange="verificaestado_ordenservicio(this);"><span class="lever switch-col-light-blue"></span>Si
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Fecha verificación</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="proyectoordenservicio_fechavalidacion" name="proyectoordenservicio_fechavalidacion">
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Nombre de la persona que verifica</label>
                                        <input type="text" class="form-control" id="proyectoordenservicio_personavalidacion" name="proyectoordenservicio_personavalidacion">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label> Documento PDF </label>
                            {{-- <div id="visor_menu_bloqueado"></div>
                            <div id="visor_contenido_bloqueado"></div> --}}
                            <iframe src="/assets/images/fondovisor.jpg" id="visor_ordenserviciopdf"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_visorcerrar">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" id="boton_guardar_ordenservicio">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- /ORDEN SERVICIO -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- DOCUMENTO ADICIONAL ORDEN DE SERVICIO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    iframe {
        width: 100%;
        height: 600px;
        border: 0px #fff solid;
    }
</style>
<div id="modal_ordenservicioadicional" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_ordenservicioadicional" id="form_ordenservicioadicional">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documento adicional a la OS</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            {{-- <input type="hidden" class="form-control" id="ordenservicioadicional_id" name="ordenservicioadicional_id" required> --}}
                            <input type="hidden" class="form-control" id="ordenservicioadicional_proyecto_id" name="proyecto_id" required>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Orden de servicio al que pertenece *</label>
                                <select class="custom-select form-control" id="ordenservicioadicional_proyectoordenservicio_id" name="proyectoordenservicio_id" required>
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre del documento adicional *</label>
                                <input type="text" class="form-control" id="proyectoordenservicioadicional_nombre" name="proyectoordenservicioadicional_nombre" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Documento PDF *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_ordenservicioadicional">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" id="proyectoordenservicioadicionalpdf" name="proyectoordenservicioadicionalpdf" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_visorcerrar_adicional">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" id="boton_guardar_ordenservicioadicional">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- DOCUMENTO ADICIONAL ORDEN DE SERVICIO -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL PRORROGA -->
<!-- ============================================================== -->

<div id="modal_prorroga" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_prorroga" id="form_prorroga">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Prórroga del proyecto</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="prorroga_id" name="prorroga_id" required>
                            <input type="hidden" class="form-control" id="prorroga_proyecto_id" name="proyecto_id" required>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fecha de inicio *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="proyectoprorrogas_fechainicio" name="proyectoprorrogas_fechainicio" required onchange="prorroga_dias();">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fecha de fin *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="proyectoprorrogas_fechafin" name="proyectoprorrogas_fechafin" required onchange="prorroga_dias();">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Total días *</label>
                                <input type="number" class="form-control" id="proyectoprorrogas_dias" name="proyectoprorrogas_dias" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'proyecto']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" id="boton_guardar_prorroga">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL PRORROGA -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- MODAL ASIGNACION DE USUARIOS -->
<!-- ============================================================== -->

<div id="modal_asignacion_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_asignacion_usuario" id="form_asignacion_usuario">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Nuevo usuario asignado al proyecto</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="PROYECTO_USUARIO_ID" name="PROYECTO_ID">
                            <input type="hidden" class="form-control" id="ID_PROYECTO_USUARIO" name="ID_PROYECTO_USUARIO" value="0">
                        </div>
                        <div class="col-12">
                            <h4 class="text-center text-danger">
                                <i class="fa fa-info-circle"></i> Al asignar un usuario al proyecto este podra Ver, Eliminar, Editar y Crear información de los servicios asignados al Proyecto.
                            </h4>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="form-group">
                                <label><b>Usuario*</b></label>
                                <select class="form-control" id="USUARIO_ID" name="USUARIO_ID" required>
                                    <option value=""></option>
                                    @foreach($usuarios as $dato)
                                    <option value="{{$dato->id}}">{{$dato->NOMBRE}} - ({{$dato->CORREO}}) </option>
                                    @endforeach
                                </select>
                                <span class="text-info"><i class="fa fa-info"></i> No es necesario agregar a los usuarios Administradores o Coordinadores, estos tienen acceso total a la Información.</span>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <h4 class="text-center">
                                Asignación de servicios
                            </h4>
                        </div>
                        <div class="col-6 mb-3 text-center">
                            <label class="text-success">Servicios de Higiene Industrial *</label>
                            <div class="switch">
                                <label>
                                    Denegado<input type="checkbox" id="SERVICIO_HI" name="SERVICIO_HI" value="1"><span class="lever switch-col-light-blue"></span>Asignado
                                </label>
                            </div>
                        </div>
                        <div class="col-6 mb-3 text-center">
                            <label class="text-success">Servicios de FR. de Psicosocial *</label>
                            <div class="switch">
                                <label>
                                    Denegado<input type="checkbox" id="SERVICIO_PSICO" name="SERVICIO_PSICO" value="1"><span class="lever switch-col-light-blue"></span>Asignado
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mb-3 mt-3 text-center">
                            <label class="text-success">Servicios de FR. de Ergonomía *</label>
                            <div class="switch">
                                <label>
                                    Denegado<input type="checkbox" id="SERVICIO_ERGO" name="SERVICIO_ERGO" value="1"><span class="lever switch-col-light-blue"></span>Asignado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_usuario">
                        Asignar usuario <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL ASIGNACION DE USUARIOS -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- VISOR-MODAL-OT -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_visor_ot>.modal-dialog {
        min-width: 1000px !important;
    }

    #visor_menu_bloqueado_ot {
        width: 594px;
        height: 52px;
        background: #555555;
        position: absolute;
        z-index: 500;
        border: 0px #FFF solid;
        /*display: none;*/
    }

    #visor_contenido_bloqueado_ot {
        width: 594px;
        height: 600px;
        /*background: #555555;*/
        position: absolute;
        z-index: 600;
        border: 0px #F00 solid;
        /*display: none;*/
    }

    #visor_documento_ot {
        width: 100%;
        height: 600px;
        border: 2px #DDDDDD solid;
        pointer-events: painted;
    }

    #modal_visor_ot .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_visor_ot .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_visor_ot" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 1200px!important;">
        <form method="post" enctype="multipart/form-data" name="form_ordentrabajo" id="form_ordentrabajo">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="nombre_documento_visor_ot"></h4>
                </div>
                <div class="modal-body"> {{-- style="background: #555555;" --}}
                    <div class="row">
                        <div class="col-6">
                            {{-- <div id="visor_menu_bloqueado_ot"></div> --}}
                            {{-- <div id="visor_contenido_bloqueado_ot"></div> --}}
                            <iframe src="/assets/images/cargando.gif" name="visor_documento_ot" id="visor_documento_ot" style=""></iframe>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control" id="ordentrabajo_id" name="ordentrabajo_id" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Firmar de autorizado?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_autorizaot" name="checkbox_autorizaot"><span class="lever switch-col-light-blue"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién autoriza (Firma digital)</label>
                                        <input type="text" class="form-control" id="ordentrabajo_autorizanombre" name="ordentrabajo_autorizanombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (Orden trabajo) *</label>
                                        <textarea class="form-control" rows="6" id="proyectoordentrabajo_observacionot" name="proyectoordentrabajo_observacionot" required></textarea>
                                    </div>
                                </div>

                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿OT cancelada?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_cancelaot" name="checkbox_cancelaot" onclick="activa_campocancelacion_ot(this);"><span class="lever switch-col-red"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién cancela</label>
                                        <input type="text" class="form-control" id="ordentrabajo_cancelanombre" name="ordentrabajo_cancelanombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (cancelación) *</label>
                                        <textarea class="form-control" rows="3" id="proyectoordentrabajo_canceladoobservacion" name="proyectoordentrabajo_canceladoobservacion" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (revisiones) *</label>
                                        <textarea class="form-control" rows="4" id="proyectoordentrabajo_observacionrevision" name="proyectoordentrabajo_observacionrevision" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: left!important;">
                    <div style="border: 0px #F00 solid; height: 35px; width: 100%; float: left; text-align: left;">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                        <input type="hidden" class="form-control" id="ordentrabajo_actualizaot" name="ordentrabajo_actualizaot" value="0">
                        <button type="button" class="btn btn-inf botonnuevo_moduloproyecto" data-toggle="tooltip" title="Actualizar los datos de la OT y conservar folio" id="boton_actualizar_ot" onclick="actualiza_datosot();">
                            Actualizar <i class="fa fa-refresh"></i>
                        </button>
                        @endif
                    </div>
                    <div style="border: 0px #F00 solid; height: 35px; width: 100%; float: right; text-align: right;">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="modalvisor_ot_boton_cerrar">
                            Cerrar
                        </button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                        &nbsp;
                        <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_ot">
                            Guardar cambios <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL-OT -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- VISOR-MODAL-OC-PROVEEDORES -->
<!-- ============================================================== -->
<div id="modal_oc_proveedores" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form method="post" enctype="multipart/form-data" name="form_proveedores_ordencompra" id="form_proveedores_ordencompra">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Lista de proveedores asignados al proyecto</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Proveedor</label>
                                <select class="custom-select form-control" id="ordencompra_selectproveedor_id" name="ordencompra_selectproveedor_id" required onchange="proveedor_cotizacion(this.value, 0);">
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Cotización</label>
                                <select class="custom-select form-control" id="ordencompra_cotizacion_id" name="cotizacion_id" required>
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_generar_oc_proveedor">
                        Generar OC para este proveedor <i class="fa fa-user"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL-OC-PROVEEDORES -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- VISOR-MODAL-OC -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_visor_oc>.modal-dialog {
        min-width: 1300px !important;
    }

    #visor_menu_bloqueado_oc {
        width: 594px;
        height: 52px;
        background: #555555;
        position: absolute;
        z-index: 500;
        border: 0px #FFF solid;
        /*display: none;*/
    }

    #visor_contenido_bloqueado_oc {
        width: 594px;
        height: 600px;
        /*background: #555555;*/
        position: absolute;
        z-index: 600;
        border: 0px #F00 solid;
        /*display: none;*/
    }

    #visor_documento_oc {
        width: 100%;
        height: 682px;
        border: 2px #DDDDDD solid;
        pointer-events: painted;
    }

    #modal_visor_oc .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_visor_oc .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }

    .botonverde {
        background-color: #8BC34A !important;
    }
</style>
<div id="modal_visor_oc" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-gl">
        <form method="post" enctype="multipart/form-data" name="form_ordencompra" id="form_ordencompra">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="nombre_documento_visor_oc"></h4>
                </div>
                <div class="modal-body"> {{-- style="background: #555555;" --}}
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Cotización</label>
                                <select class="custom-select form-control" id="ordencompra_proveedorcotizacion_id" name="cotizacion_id" required>
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                            <iframe src="/assets/images/cargando.gif" name="visor_documento_oc" id="visor_documento_oc"></iframe>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control" id="ordencompra_id" name="ordencompra_id" value="0">
                                        <input type="hidden" class="form-control" id="ordencompra_proveedor_id" name="proveedor_id" value="0">
                                        {{-- <input type="hidden" class="form-control" id="ordencompra_cotizacion_id" name="cotizacion_id" value="0"> --}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Firmar de solicitado?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_revisaoc" name="checkbox_revisaoc"><span class="lever switch-col-light-blue"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién solicita (Firma digital)</label>
                                        <input type="text" class="form-control" id="ordencompra_revisanombre" name="ordencompra_revisanombre" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Firmar de autorizado?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_autorizaoc" name="checkbox_autorizaoc"><span class="lever switch-col-light-blue"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién autoriza (Firma digital)</label>
                                        <input type="text" class="form-control" id="ordencompra_autorizanombre" name="ordencompra_autorizanombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (Orden compra)</label>
                                        <textarea class="form-control" rows="3" id="proyectoordencompra_observacionoc" name="proyectoordencompra_observacionoc"></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿OC cancelada?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_cancelaoc" name="checkbox_cancelaoc" onclick="activa_campocancelacion_oc(this);"><span class="lever switch-col-red"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién cancela</label>
                                        <input type="text" class="form-control" id="proyectoordencompra_canceladonombre" name="proyectoordencompra_canceladonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (cancelación) *</label>
                                        <textarea class="form-control" rows="3" id="proyectoordencompra_canceladoobservacion" name="proyectoordencompra_canceladoobservacion" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (revisión) *</label>
                                        <textarea class="form-control" rows="3" id="proyectoordencompra_observacionrevision" name="proyectoordencompra_observacionrevision" required></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                    <div class="form-group" data-toggle="tooltip" title="Quitar facturado, solo Superusuario y Financiero" id="checkbox_factura">
                                        <label class="demo-switch-title">¿OC Facturada?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_facturaoc" name="checkbox_facturaoc" onclick="activa_campofactura_oc(this);"><span class="lever switch-col-light-green"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién valida la factura</label>
                                        <input type="text" class="form-control" id="proyectoordencompra_facturadonombre" name="proyectoordencompra_facturadonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Monto total factura *</label>
                                        <input type="number" step="any" class="form-control" id="proyectoordencompra_facturadomonto" name="proyectoordencompra_facturadomonto" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Factura PDF *</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput" id="campo_file_factura">
                                                <i class="fa fa-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-secondary btn-file">
                                                <span class="fileinput-new">Seleccione</span>
                                                <span class="fileinput-exists">Cambiar</span>
                                                <input type="file" accept="application/pdf" id="facturadopdf" name="facturadopdf" required>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-secondary" style="margin-top: 8px;" data-toggle="tooltip" title="Mostrar factura" id="boton_mostrar_factura" onclick="mostrar_factura();">
                                        <i class="fa fa-ban fa-3x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div style="border: 0px #F00 solid; height: 35px; width: 100%; float: left; text-align: left;">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras']))
                        <input type="hidden" class="form-control" id="ordencompra_actualizaoc" name="ordencompra_actualizaoc" value="0">
                        <button type="button" class="btn btn-info botonnuevo_moduloproyecto" data-toggle="tooltip" title="Actualizar los datos de la OP y conservar folio" id="boton_actualizar_oc" onclick="actualiza_datosoc();">
                            Actualizar <i class="fa fa-refresh"></i>
                        </button>
                        <input type="radio" class="with-gap radio-col-deep-orange" name="proyectoordencompra_tipolista" id="proyectoordencompra_tipolista_0" value="0" />
                        <label for="proyectoordencompra_tipolista_0">Lista de proveedores</label>
                        <input type="radio" class="with-gap radio-col-deep-orange" name="proyectoordencompra_tipolista" id="proyectoordencompra_tipolista_1" value="1" />
                        <label for="proyectoordencompra_tipolista_1">Lista de puntos reales</label>
                        @endif
                    </div>
                    <div style="border: 0px #F00 solid; height: 35px; width: 100%; float: right; text-align: right;">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="modalvisor_oc_boton_cerrar">
                            Cerrar
                        </button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras']))
                        &nbsp;
                        <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_oc">
                            Guardar cambios <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL-OC -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- VISOR-MODAL-SIGNATARIOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #visor_documento_ls {
        width: 100%;
        height: 600px;
        border: 2px #DDDDDD solid;
        pointer-events: painted;
    }

    #modal_signatarioslista .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_signatarioslista .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_signatarioslista" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 1200px!important;">
        <form method="post" enctype="multipart/form-data" name="form_signatarioslista" id="form_signatarioslista">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="nombre_documento_visor_ls"></h4>
                </div>
                <div class="modal-body"> {{-- style="background: #555555;" --}}
                    <div class="row">
                        <div class="col-6">
                            <iframe src="/assets/images/cargando.gif" name="visor_documento_ls" id="visor_documento_ls" style=""></iframe>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control" id="signatarioslista_id" name="signatarioslista_id" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Firmar de autorizado?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_autorizals" name="checkbox_autorizals" onclick="activa_campoautorizacion_ls(this);"><span class="lever switch-col-light-blue"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién autoriza (Firma digital)</label>
                                        <input type="text" class="form-control" id="proyectosignatario_autorizadonombre" name="proyectosignatario_autorizadonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Lista cancelada?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_cancelals" name="checkbox_cancelals" onclick="activa_campocancelacion_ls(this);"><span class="lever switch-col-red"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién cancela</label>
                                        <input type="text" class="form-control" id="proyectosignatario_canceladonombre" name="proyectosignatario_canceladonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (cancelación) *</label>
                                        <textarea class="form-control" rows="8" id="proyectosignatario_canceladoobservacion" name="proyectosignatario_canceladoobservacion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: right!important;">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_cerrar_signatarioslista">
                        Cerrar
                    </button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                    &nbsp;
                    <button type="submit" class="btn btn-danger waves-effect botonguardar_moduloproyecto" id="boton_guardar_signatarioslista">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    <button type="button" class="btn btn-default waves-effect botonguardar_moduloproyecto" data-toggle="tooltip" title="Debe autorizar para activar opción de guardar" id="boton_guardar_signatarioslista_2">
                        Crear y guardar <i class="fa fa-ban"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL-SIGNATARIOS -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- VISOR-MODAL-EQUIPOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #visor_documento_le {
        width: 100%;
        height: 600px;
        border: 2px #DDDDDD solid;
        pointer-events: painted;
    }

    #modal_equiposlista .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_equiposlista .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_equiposlista" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 1200px!important;">
        <form method="post" enctype="multipart/form-data" name="form_equiposlista" id="form_equiposlista">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="nombre_documento_visor_le"></h4>
                </div>
                <div class="modal-body"> {{-- style="background: #555555;" --}}
                    <div class="row">
                        <div class="col-6">
                            <iframe src="/assets/images/cargando.gif" name="visor_documento_le" id="visor_documento_le" style=""></iframe>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {!! csrf_field() !!}
                                        <input type="hidden" class="form-control" id="equiposlista_id" name="equiposlista_id" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Firmar de autorizado?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_autorizale" name="checkbox_autorizale" onclick="activa_campoautorizacion_le(this);"><span class="lever switch-col-light-blue"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién autoriza (Firma digital)</label>
                                        <input type="text" class="form-control" id="proyectoequipo_autorizadonombre" name="proyectoequipo_autorizadonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                    <div class="form-group">
                                        <label class="demo-switch-title">¿Lista cancelada?</label>
                                        <div class="switch">
                                            <label>No<input type="checkbox" id="checkbox_cancelale" name="checkbox_cancelale" onclick="activa_campocancelacion_le(this);"><span class="lever switch-col-red"></span>Si</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="demo-switch-title">No disponible</label>
                                        <div class="switch">
                                            <label><input type="checkbox" disabled><span class="lever switch-col-secondary"></span></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Nombre y fecha de quién cancela</label>
                                        <input type="text" class="form-control" id="proyectoequipo_canceladonombre" name="proyectoequipo_canceladonombre" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label> Observación (cancelación) *</label>
                                        <textarea class="form-control" rows="8" id="proyectoequipo_canceladoobservacion" name="proyectoequipo_canceladoobservacion" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: right!important;">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_cerrar_equiposlista">
                        Cerrar
                    </button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                    &nbsp;
                    <button type="submit" class="btn btn-danger waves-effect botonguardar_moduloproyecto" id="boton_guardar_equiposlista">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    <button type="button" class="btn btn-default waves-effect botonguardar_moduloproyecto" data-toggle="tooltip" title="Debe autorizar para activar opción de guardar" id="boton_guardar_equiposlista_2">
                        Crear y guardar <i class="fa fa-ban"></i>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL-EQUIPOS -->
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                                        <label>Numero del punto (fuera de norma) *</label>
                                        <input type="number" class="form-control" id="proyectoevidenciafoto_nopunto" name="proyectoevidenciafoto_nopunto" required>
                                    </div>
                                </div>
                                <div class="col-12" id="fotosfisicos_campo_partida" style="display: none;">
                                    <div class="form-group">
                                        <label>Tipo de evaluación *</label>
                                        <select class="custom-select form-control" id="catreportequimicospartidas_id" name="catreportequimicospartidas_id" required onchange="evidenciafoto_carpetanombre(this);">
                                            <option value="0" selected></option>
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                                    <option value="0" selected></option>

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
                                    <option value="0" selected></option>
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'ApoyoTecnico', 'Cadista', 'Reportes', 'Externo']))
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'ApoyoTecnico']))
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
@php
$rolUsuario = auth()->user()->roles->first() ? auth()->user()->roles->first()->rol_Nombre : null;
$empleado = auth()->user()->empleado;
$nombreCompleto = $empleado ? $empleado->empleado_nombre . ' ' . $empleado->empleado_apellidopaterno . ' ' . $empleado->empleado_apellidomaterno : '';
@endphp

<script>
    var rolUsuario = @json($rolUsuario);
    var Usuario = @json($nombreCompleto);
</script>

{{-- ========================================================================= --}}
@endsection