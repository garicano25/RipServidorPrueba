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
    }

    form label {
        color: #999999;
    }
</style>

<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-12 align-self-center">
        <div class="d-flex justify-content-end">
            <div class="">
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Menu tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" role="tab" id="tab_programa_trabajo">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Programa de trabajo</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_logistica_servicio">
                        <span class="hidden-sm-up"><i class="fa fa-puzzle-piece"></i></span>
                        <span class="hidden-xs-down">Logística del servicio</span></a>
                </li>
                <li class="nav-item" style="display: none;">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_cronograma">
                        <span class="hidden-sm-up"><i class="fa fa-calendar"></i></span>
                        <span class="hidden-xs-down">Cronograma de actividades</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Tab 1 -->
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="mdi mdi-calendar-clock"></i> Programa de trabajo. </h2>
                        </ol>
                        <table class="table table-hover stylish-table" id="tabla_programa_trabajo" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Folio Proyecto</th>
                                    <th width="600">Intalación / Dirrección</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Reconocimiento vinculado</th>
                                    <th width="60">Mostrar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
                                                    <h4 style="margin: 0px;"><a class="text-success div_programa_proyecto">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_programa_reconocimiento">FOLIO RECONOCIMIENTO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Reconocimiento</small>
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
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Operativo HI']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab2">
                                                <i class="fa fa-user"></i><br>
                                                <span>Proveedores</span>
                                            </div>
                                            @endif

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI','Compras']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab3">
                                                <i class="fa fa-address-card-o"></i><br>
                                                <span>Signatarios</span>
                                            </div>
                                            @endif

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI','Compras']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab4">
                                                <i class="fa fa-desktop"></i><br>
                                                <span>Equipos</span>
                                            </div>
                                            @endif

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab5">
                                                <i class="fa fa-print"></i><br>
                                                <span>Ordenes</span>
                                            </div>
                                            @endif

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Almacén','Operativo HI','Compras']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab6">
                                                <i class="fa fa-list"></i><br>
                                                <span>Lista de permisos</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--form panels-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__form">
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Operativo HI']))
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
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI' ]))
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes active" data-toggle="tab" id="reportetab_menu1" role="tab" href="#reportetab_1">
                                                                            <span class="hidden-xs-down">Orden de trabajo</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén']))
                                                                    <li class="nav-item">
                                                                        <a class="nav-link link_menureportes" data-toggle="tab" id="reportetab_menu2" role="tab" href="#reportetab_2">
                                                                            <span class="hidden-xs-down">Orden de compra</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif

                                                                </ul>
                                                                <!-- Tab Panels -->
                                                                <div class="tab-content">
                                                                    <div class="tab-pane p-20 active" id="reportetab_1" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI','Compras']))
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
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Compras']))
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

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab6">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <!-- Tab Menus -->
                                                                <ul class="nav nav-tabs customtab" role="tablist">


                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Compras','Almacén','Operativo HI']))

                                                                    <li class="nav-item">
                                                                        <a class="nav-link " data-toggle="tab" id="reportetab_menu3" role="tab" href="#reportetab_3">
                                                                            <span class="hidden-xs-down">Lista de signatarios</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Compras','Coordinador','Almacén','Operativo HI']))
                                                                    <li class="nav-item">
                                                                        <a class="nav-link " data-toggle="tab" id="reportetab_menu4" role="tab" href="#reportetab_4">
                                                                            <span class="hidden-xs-down">Lista de equipos</span>
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                                <!-- Tab Panels -->
                                                                <div class="tab-content">

                                                                    <div class="tab-pane p-20" id="reportetab_3" role="tabpanel">
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI','Compras']))
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
                                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Operativo HI','Compras']))
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

                </div>
            </div>
            <!-- End Tab panes -->
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->




<!-- ============================================================== -->
<!-- MODALES -->
<!-- ============================================================== -->


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
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
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