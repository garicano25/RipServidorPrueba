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
    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Proveedores</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">Proveedores</li>
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
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" role="tab" id="tab_tabla_proveedor">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de proveedores</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_info_proveedor">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Datos del proveedor</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="mdi mdi-contacts"></i> Proveedores </h2>
                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo proveedor" id="boton_nuevo_proveedor" style="margin-left: auto;">
                                Proveedor <i class="fa fa-plus"></i>
                                <!--<span class="btn-label"><i class="fa fa-plus"></i></span>Proveedor -->
                            </button>
                        </ol>
                        @endif
                        <table class="table table-hover stylish-table" id="tabla_proveedores" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tipo</th>
                                    <th>Razón social</th>
                                    <th>Línea de negocios</th>
                                    <th>Contacto / Teléfono</th>
                                    <th width="60">Mostrar</th>
                                    {{-- <th width="80">Desbloquear</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_2" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 6px 10px">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-address-card-o"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_proveedor_nombre">NOMBRE</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Proveedor</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_proveedor_lineanegocio">LÍNEA NEGOCIO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Línea negocio</small>
                                                    </td>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-suitcase"></i></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- ============= STEPS ============= -->
                        <div style="min-width: 700px; width: 100% ; margin: 0px auto;">
                            <!--multisteps-form-->
                            <div class="multisteps-form">
                                <!--progress bar-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="multisteps-form__progress">
                                            <div class="multisteps-form__progress-btn js-active" id="steps_menu_tab1">
                                                <i class="fa fa-briefcase"></i><br>
                                                <span>Datos generales</span>
                                            </div>
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab2">
                                                <i class="fa fa-file-text-o"></i><br>
                                                <span>Acreditaciones</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab6">
                                                <i class="fa fa-list-ol"></i><br>
                                                <span>Alcances</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab3">
                                                <i class="fa fa-desktop"></i><br>
                                                <span>Equipos</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab8">
                                                <i class="fa fa-car"></i><br>
                                                <span>Vehículos</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab4">
                                                <i class="fa fa fa-vcard-o"></i><br>
                                                <span>Personal</span>
                                            </div>
                                            @endif
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab5">
                                                <i class="fa fa-dollar"></i><br>
                                                <span>Precios</span>
                                            </div>
                                            @endif
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
                                                    <ol class="breadcrumb m-b-10">

                                                        <h2 style="color: #ffff; margin: 0;"> <i class="mdi mdi-contacts" aria-hidden="true"></i> Información del proveedor</h2>

                                                    </ol>
                                                    <form enctype="multipart/form-data" method="post" name="form_proveedor" id="form_proveedor">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                {{-- {!! method_field('PUT') !!} --}}
                                                                {!! csrf_field() !!}
                                                                <input type="hidden" class="form-control" id="proveedor_id" name="proveedor_id" value="0">
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Tipo proveedor *</label>
                                                                    <select class="custom-select form-control" id="cat_tipoproveedor_id" name="cat_tipoproveedor_id" required>
                                                                        <option value=""></option>
                                                                        @foreach($cat_tipoproveedor as $tipoproveedor)
                                                                        <option value="{{$tipoproveedor->id}}">{{$tipoproveedor->catTipoproveedor_Nombre}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Razón social *</label>
                                                                    <input type="text" class="form-control" id="proveedor_RazonSocial" name="proveedor_RazonSocial" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Nombre comercial *</label>
                                                                    <input type="text" class="form-control" id="proveedor_NombreComercial" name="proveedor_NombreComercial" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Domicilio fiscal *</label>
                                                                    <input type="text" class="form-control" id="proveedor_DomicilioFiscal" name="proveedor_DomicilioFiscal" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Línea de negocios</label>
                                                                    <input type="text" class="form-control" id="proveedor_LineaNegocios" name="proveedor_LineaNegocios">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Giro comercial</label>
                                                                    <input type="text" class="form-control" id="proveedor_GiroComercial" name="proveedor_GiroComercial">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> RFC *</label>
                                                                    <input type="text" class="form-control" id="proveedor_Rfc" name="proveedor_Rfc" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Ciudad / País *</label>
                                                                    <input type="text" class="form-control" id="proveedor_CiudadPais" name="proveedor_CiudadPais" placeholder="Eje: Villahermosa, México." required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Representante legal</label>
                                                                    <input type="text" class="form-control" id="proveedor_RepresentanteLegal" name="proveedor_RepresentanteLegal" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-6">
                                                                <div class="form-group">
                                                                    <label> Página web</label>
                                                                    <input type="text" class="form-control" id="proveedor_PaginaWeb" name="proveedor_PaginaWeb">
                                                                </div>
                                                            </div>


                                                        </div>


                                                        <div class="row listadecontactoProveedor"></div>


                                                        <div class="row">
                                                            <div class="col">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                                <div class="form-group" style="text-align: center;">
                                                                    <button type="button" class="btn btn-danger botonagregarContacto" id="botonagregarContacto">
                                                                        Agregar contacto <i class="fa fa-phone"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            <div class="col">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                                <div class="form-group" style="text-align: center;">
                                                                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_proveedor" value="0" onclick="bloqueo_proveedor(this.value);">
                                                                        <span class="btn-label"><i class="fa fa-unlock"></i></span> Proveedor desbloqueado para edición
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            <div class="col">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                                <div class="form-group" style="text-align: center;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_moduloproveedores" id="boton_guardar_proveedor">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>


                                                    </form>


                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras']))
                                                    <br>
                                                    <div class="datosProveedor">
                                                        <ol class="breadcrumb m-b-10">

                                                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-text"></i> Documentación </h2>
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                            <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_moduloproveedores" data-toggle="tooltip" id="boton_nuevo_documento" style="margin-left: auto;">
                                                                Documentación <i class="fa fa-plus"></i>
                                                            </button>
                                                            @endif
                                                        </ol>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_documentos">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px!important;">No.</th>
                                                                                <!-- <th>Tipo documento</th> -->

                                                                                <th>Documento</th>
                                                                                <th style="width: 70px!important;">PDF</th>
                                                                                <th style="width: 70px!important;">Editar</th>
                                                                                <th style="width: 70px!important;">Eliminar</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <BR>
                                                        @endif
                                                        {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}

                                                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI']))
                                                        <ol class="breadcrumb m-b-10">
                                                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-building"></i> Sucursales </h2>
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                            <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_moduloproveedores" data-toggle="tooltip" id="boton_nuevo_domicilio" style="margin-left: auto;">
                                                                Sucursales <i class="fa fa-plus"></i>
                                                            </button>
                                                            @endif
                                                        </ol>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover stylish-table" width="100%" id="tabla_domicilios">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 50px!important;">No.</th>
                                                                                <th>Ciudad</th>
                                                                                <th>Domicilio sucursal</th>
                                                                                <th style="width: 70px!important;">Editar</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Compras','Almacén','Operativo HI']))
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab2">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-book"></i> Acreditaciones y aprobaciones </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Nuevo" id="boton_nueva_acreditacion" style="margin-left: auto;">
                                                                    Acreditación/Aprobación <i class="fa fa-plus p-1"></i>
                                                                </button>
                                                                @else
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-book"></i>Acreditaciones y aprobaciones </h2>
                                                                @endif
                                                            </ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_acreditaciones" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 50px!important;">No.</th>
                                                                            <th style="width: 140px!important;">Tipo</th>
                                                                            <th>Entidad / Numero</th>
                                                                            <th style="width: 160px!important;">Área</th>
                                                                            <th style="width: 300px!important;">Alcance</th>
                                                                            <th style="width: 160px!important;">Vigencia</th>
                                                                            <th style="width: 70px!important;">Pdf</th>
                                                                            <th style="width: 70px!important;">Editar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <!--STEP 6-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab6">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">

                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-list-alt"></i> Alcances </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Nuevo" id="boton_nuevo_alcance" style="margin-left: auto;">
                                                                    Agente / Factor de riesgo / Servicio <i class="fa fa-plus p-1"></i>
                                                                </button>
                                                                @else
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-list-alt"></i>Alcances </h2>
                                                                @endif
                                                            </ol>

                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_servicios" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 50px!important;">No.</th>
                                                                            <th>Acreditación / Aprobación</th>
                                                                            <th style="width: 120px!important;">Tipo</th>
                                                                            <th style="width: 120px!important;">Alcance</th>
                                                                            <th style="width: 260px!important;">Norma (s) y/o Método (s)</th>
                                                                            <th style="width: 260px!important;">Descripción</th>
                                                                            <th style="width: 70px!important;">Editar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab3">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-cubes"></i> Equipos </h2>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Cargar equipos por medio de un archivo Excel" id="boton_cargarExcelEquipos" style="margin-left: 77%;">
                                                                    Importar <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Nuevo equipo" id="boton_nuevo_equipo" style="margin-left: auto;">
                                                                    Equipo <i class="fa fa-plus p-1"></i>
                                                                </button>
                                                                @else
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-cubes"></i> Equipos </h2>
                                                                @endif
                                                            </ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_equipos" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            {{-- <th>Factor de riesgo / Servicio</th> --}}
                                                                            <th>Equipo</th>
                                                                            <th>Marca</th>
                                                                            <th>Modelo</th>
                                                                            <th>Serie</th>
                                                                            <th>Vigencia Calib.</th>
                                                                            <!-- <th style="width: 80px!important;">PDF</th>
                                                                            <th style="width: 80px!important;">Carta</th> -->
                                                                            <th style="width: 120px!important;">Estado</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <!-- STEP 8 -->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab8">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-car"></i> Vehículos </h2>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores d-none" data-toggle="tooltip" title="Cargar Vehículos por medio de un archivo Excel" id="boton_cargarExcelVehiculos" style="margin-left: 75%;">
                                                                    Importar <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Nuevo Vehículos" id="boton_nuevo_vehiculo" style="margin-left: auto;">
                                                                    Vehículo <i class="fa fa-plus p-1"></i>
                                                                </button>
                                                                @else
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-car"></i> Vehículos </h2>
                                                                @endif
                                                            </ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_vehiculos" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            {{-- <th>Factor de riesgo / Servicio</th> --}}
                                                                            <th>Marca</th>
                                                                            <th>Modelo</th>
                                                                            <th>Serie</th>
                                                                            <th>Placa</th>
                                                                            <!-- <th style="width: 80px!important;">PDF</th>
                                                                            <th style="width: 80px!important;">Carta</th> -->
                                                                            <th style="width: 120px!important;">Estado</th>
                                                                            <th style="width: 80px!important;">Editar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>


                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab4">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-users"></i> Personal </h2>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Cargar personales por medio de un archivo Excel" id="boton_cargarExcelPersonal" style="margin-left: 75%;">
                                                                    Importar <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" data-toggle="tooltip" title="Nuevo personal" id="boton_nuevo_signatario" style="margin-left: auto;">
                                                                    Personal <i class="fa fa-plus p-1"></i>
                                                                </button>
                                                                @else
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-users"></i> Personal </h2>
                                                                @endif
                                                            </ol>

                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_signatarios" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 50px!important;">No.</th>
                                                                            <th>Nombre</th>
                                                                            <th style="width: 240px!important;">Cargo</th>
                                                                            <th style="width: 120px!important;">Telefono</th>
                                                                            <th style="width: 120px!important;">Estado</th>
                                                                            <th style="width: 70px!important;">Editar</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-secondary ml-auto js-btn-next">
                                                            Siguiente <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            @endif
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                            <!--STEP 5-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab5">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">

                                                            <ol class="breadcrumb m-b-10">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-text"></i> Cotización </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_moduloproveedores" data-toggle="tooltip" id="boton_nuevo_servicio" style="margin-left: auto;">
                                                                    Cotización <i class="fa fa-plus"></i>
                                                                </button>
                                                                @else
                                                                Cotización
                                                                @endif
                                                            </ol>
                                                            <div class="table-responsive">
                                                                <table class="table table-hover stylish-table" id="tabla_precios" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 50px!important;">No.</th>
                                                                            <th>No. cotización</th>
                                                                            <th>Fecha</th>
                                                                            <th>Vigencia</th>
                                                                            <th class="text-nowrap" style="width: 70px!important;">Pdf</th>
                                                                            <th class="text-nowrap" style="width: 70px!important;">Editar</th>
                                                                            <th class="text-nowrap" style="width: 70px!important;">Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="button-row d-flex mt-4">
                                                        <button type="button" class="btn btn-secondary js-btn-prev" title="Prev">
                                                            <i class="fa fa-arrow-left"></i> Anterior
                                                        </button>
                                                        <button class="btn btn-danger ml-auto" title="Send" type="button" id="boton_finalizar_captura">
                                                            Finalizar captura <i class="fa fa-check-square-o"></i>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============= /STEPS ============= -->
                    </div>
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
<!-- Modal domicilios comerciales -->
<div id="modal_domicilio" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 86%">
        <div class="modal-content">
            <form id="form_domicilio" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Sucursal</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="domicilio_id" name="domicilio_id" value="0">
                            <input type="hidden" class="form-control" id="domicilio_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Ciudad *</label>
                                <input type="text" class="form-control" id="proveedorDomicilio_ciudad" name="proveedorDomicilio_ciudad" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Dirección *</label>
                                <input type="text" class="form-control" id="proveedorDomicilio_Direccion" name="proveedorDomicilio_Direccion" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="hidden" class="form-control" id="proveedorDomicilio_Eliminado" name="proveedorDomicilio_Eliminado" value="0">
                        </div>
                    </div>

                    <div class="row listadecontactoProveedorSucursal"></div>

                </div>

                <div class="modal-footer" style="display: flex; justify-content: flex-start;">
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="button" class="btn btn-danger botonagregarContacto" id="botonagregarContactosucursal">Agregar contacto <i class="fa fa-phone"></i></button>
                    @endif
                    <div style="flex-grow: 1;"></div>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_domicilio_sucursal">Guardar <i class="fa fa-save"></i></button>
                    @endif
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                </div>


            </form>
        </div>
    </div>
</div>
<!-- /.Modal domicilios comerciales -->

<!-- Modal documentos -->
<div id="modal_documento" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_documento" id="form_documento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos administrativos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="documento_id" name="documento_id" value="0">
                            <input type="hidden" class="form-control" id="documento_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Tipo de documento: *</label>
                                <select class="custom-select form-control" id="TIPO_DOCUMENTO" name="TIPO_DOCUMENTO" required>
                                    <option selected disabled>Seleccione una opción...</option>
                                    <option value="1">Repse</option>
                                    <option value="2">Constancia de Situación Fiscal</option>
                                    <option value="0">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" name="proveedorDocumento_Nombre" id="proveedorDocumento_Nombre" required>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify" required/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="documento" id="documento" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                        Quitar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="proveedorDocumento_Eliminado" name="proveedorDocumento_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_documento">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal documentos -->

<!-- Modal acreditaciones -->
<div id="modal_acreditacion" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 80%;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_acreditacion" id="form_acreditacion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Acreditación / Aprobación</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="acreditacion_id" name="acreditacion_id" value="0">
                            <input type="hidden" class="form-control" id="acreditacion_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label>Servicio *</label>
                                <select class="custom-select form-control" id="acreditacion_Servicio" name="acreditacion_Servicio" onchange="modificar_campo(this.value);" required>
                                    <option value=""></option>
                                    @foreach($serviciosacreditacion as $servicio)
                                    <option value="{{$servicio->id}}">{{$servicio->catServicioAcreditacion_Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label> Empresa subcontratada </label>
                                <input type="text" class="form-control" id="acreditacion_EmpresaSub" name="acreditacion_EmpresaSub">
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <select class="custom-select form-control" id="acreditacion_Tipo" name="acreditacion_Tipo" required>
                                    <option value=""></option>
                                    @foreach($tipoacreditacion as $tipo)
                                    <option value="{{$tipo->id}}">{{$tipo->catTipoAcreditacion_Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Entidad *</label>
                                <input type="text" class="form-control" id="acreditacion_Entidad" name="acreditacion_Entidad" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Numero *</label>
                                <input type="text" class="form-control" id="acreditacion_Numero" name="acreditacion_Numero" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Área *</label>
                                <select class="custom-select form-control" id="cat_area_id" name="cat_area_id" required>
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                    <option value="{{$area->id}}">{{$area->catArea_Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Expedición *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="acreditacion_Expedicion" name="acreditacion_Expedicion" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Vigencia *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="acreditacion_Vigencia" name="acreditacion_Vigencia" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify" required/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_acreditacion">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="documentopdf" id="documentopdf" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                        Quitar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="acreditacion_Eliminado" name="acreditacion_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_acreditacion">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal acreditaciones -->

<!-- Modal acreditaciones alcances -->
<div id="modal_alcance" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg " style="min-width: 80%;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_alcance_acreditacion" id="form_alcance_acreditacion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alcance (Agente / Factor de riesgo / Servicio)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="alcance_id" name="alcance_id" value="0">
                            {{-- <input type="hidden" class="form-control" id="alcance_acreditacion_id" name="acreditacion_id" value="0"> --}}
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tipo alcance *</label>
                                <select class="custom-select form-control" id="acreditacionAlcance_tipo" name="acreditacionAlcance_tipo" onchange="validatipoalcance(this.value, 0);" required>
                                    <option value=""></option>
                                    @foreach($tipopruebas as $tipo)
                                    <option value="{{$tipo->catPrueba_Tipo}}">{{$tipo->catPrueba_Tipo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="div_campo_factor">
                            <div class="form-group">
                                <label>Agente / Factor de riesgo / Servicio *</label>
                                <select class="custom-select form-control" id="prueba_id" name="prueba_id" onchange="alcanceagente(this);" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="div_campo_agente" style="display: none;">
                            <div class="form-group">
                                <label> Agente químico *</label>
                                <!-- <input type="text" class="form-control" id="acreditacionAlcance_agente" name="acreditacionAlcance_agente"> -->

                                <select class="custom-select form-control" id="acreditacionAlcance_agente" name="acreditacionAlcance_agente">
                                    <option value=""></option>
                                    @foreach($sustanciasQuimicas as $sustancia)
                                    <option value="{{$sustancia->SUSTANCIA_QUIMICA}}">{{$sustancia->SUSTANCIA_QUIMICA}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label> Tipo *</label>
                                <select class="custom-select form-control" id="acreditacionAlcance_agentetipo" name="acreditacionAlcance_agentetipo">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Acreditación al que pertenece este alcance *</label>
                                <select class="custom-select form-control" id="alcanceacreditacion_id" name="acreditacion">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 p-3 d-flex justify-content-start">
                            <p>¿Requiere Aprobación?</p>
                            <div class="form-check mx-4">
                                <input class="form-check-input" type="radio" name="requiere_aprobacion" id="requiere_aprobacion_si" value="1" checked>
                                <label class="form-check-label" for="requiere_aprobacion_si">
                                    Si
                                </label>
                            </div>
                            <div class="form-check mx-4">
                                <input class="form-check-input" type="radio" name="requiere_aprobacion" id="requiere_aprobacion_no" value="0">
                                <label class="form-check-label" for="requiere_aprobacion_no">
                                    No
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Aprobación al que pertenece este alcance </label>
                                <select class="custom-select form-control" id="alcace_aprovacion_id" name="aprovacion">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> Norma (s) *</label>
                                <input type="text" class="form-control" id="acreditacionAlcance_Norma" name="Norma" placeholder="Norma 1, Norma 2, Metodo 1, Metodo 2, Etc...">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> Procedimiento (s) </label>
                                <input type="text" class="form-control" id="acreditacionAlcance_Procedimiento" name="Procedimiento" placeholder="Procedimiento 1, Procedimiento 2, Etc...">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Método (s) </label>
                                <input type="text" class="form-control" id="acreditacionAlcance_Metodo" name="Metodo" placeholder="Método 1, Método 2, Etc...">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> Descripción *</label>
                                <input type="text" class="form-control" id="acreditacionAlcance_Descripcion" name="acreditacionAlcance_Descripcion" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> Observación </label>
                                <textarea class="form-control" rows="2" id="acreditacionAlcance_Observacion" name="acreditacionAlcance_Observacion"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="acreditacionAlcance_Eliminado" name="acreditacionAlcance_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_alcance">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal acreditaciones alcances -->

<!-- Modal equipos -->
<div id="modal_equipo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 86%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Equipo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1_equipo" id="tab1_equipo_info" role="tab">Información del equipo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2_equipo" role="tab">Documentos</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- tab 1 -->
                                <div class="tab-pane active" id="tab1_equipo" role="tabpanel">
                                    <div class="card-body">
                                        <style type="text/css" media="screen">
                                            .dropify-wrapper {
                                                height: 300px !important;
                                                /*tamaño estatico del campo foto*/
                                            }
                                        </style>
                                        <form enctype="multipart/form-data" method="post" name="form_equipo" id="form_equipo">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label> Foto del equipo </label>
                                                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="foto_equipo" name="foto_equipo" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" />
                                                            </div>
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))

                                                            <center>
                                                                <label> Estado del equipo </label>
                                                                <div class="switch">
                                                                    <label>
                                                                        Inactivo
                                                                        <input type="checkbox" id="checkbox_estado_equipo" checked onclick="actualiza_estado_equipo();">
                                                                        <span class="lever switch-col-light-blue"></span>
                                                                        Activo
                                                                    </label>
                                                                </div>
                                                            </center>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" class="form-control" id="equipo_id" name="equipo_id" value="0">
                                                            <input type="hidden" class="form-control" id="equipo_proveedor_id" name="proveedor_id" value="0">
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="form-group">
                                                                <label> Nombre del equipo*</label>
                                                                <input type="text" class="form-control" id="equipo_Descripcion" name="equipo_Descripcion" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Uso del equipo *</label>
                                                                <select class="custom-select form-control" id="equipo_uso" name="equipo_uso" onchange="equipovalida_alcanceservicio(this.value);" required>
                                                                    <option value=""></option>
                                                                    <option value="1">Medición</option>
                                                                    <option value="2">Muestreo</option>
                                                                    <option value="3">Comunicación</option>
                                                                    <option value="4">Computo</option>
                                                                    <option value="0">Otro</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Marca *</label>
                                                                <input type="text" class="form-control" id="equipo_Marca" name="equipo_Marca" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Modelo *</label>
                                                                <input type="text" class="form-control" id="equipo_Modelo" name="equipo_Modelo" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Serie *</label>
                                                                <input type="text" class="form-control" id="equipo_Serie" name="equipo_Serie" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>No. Inventario </label>
                                                                <input type="text" class="form-control" id="numero_inventario" name="numero_inventario">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Tipo *</label>
                                                                <input type="text" class="form-control" id="equipo_Tipo" name="equipo_Tipo" required>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Peso neto </label>
                                                                <input type="Number" step="any" class="form-control" id="equipo_PesoNeto" name="equipo_PesoNeto">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Unidad de medida </label>
                                                                <select class="custom-select form-control" id="unidad_medida" name="unidad_medida">
                                                                    <option value=""></option>
                                                                    <option value="1">Kilogramo (Kg)</option>
                                                                    <option value="2">Gramos (G)</option>
                                                                    <option value="3">Libras (Lb)</option>
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Costo aproximado (MXN)</label>
                                                                <input type="Number" step="any" class="form-control" id="equipo_CostoAprox" name="equipo_CostoAprox">
                                                            </div>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="form-group">
                                                                <label> Folio de la factura</label>
                                                                <input type="text" class="form-control" id="folio_factura" name="folio_factura">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 d-flex align-items-center mb-3">
                                                            <h4 class="me-2" style="color: #0B3F64!important;">¿Requiere calibración? </h4>
                                                            <div class="switch mx-4">
                                                                <label>
                                                                    No
                                                                    <input type="checkbox" id="requiere_calibracion1" name="requiere_calibracion_switch" onchange="requierecalibracion()">
                                                                    <span class="lever switch-col-light-blue" id="checkbox_solicitudOS"></span>
                                                                    Si
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 calibracion-requrida">
                                                            <div class="form-group">
                                                                <label>Tipo calibración</label>
                                                                <input type="text" class="form-control" id="equipo_TipoCalibracion" name="equipo_TipoCalibracion">
                                                            </div>
                                                        </div>
                                                        <div class="col-6 calibracion-requrida">
                                                            <div class="form-group">
                                                                <label>Fecha calibración</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="equipo_FechaCalibracion" name="equipo_FechaCalibracion">
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 calibracion-requrida">
                                                            <div class="form-group">
                                                                <label>Vigencia calibración</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="equipo_VigenciaCalibracion" name="equipo_VigenciaCalibracion">
                                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="requiere_calibracion" name="requiere_calibracion" value="No">


                                                        <div class="col-12">
                                                            <input type="hidden" class="form-control" id="equipo_EstadoActivo" name="equipo_EstadoActivo" value="1">

                                                            <input type="hidden" class="form-control" id="equipo_Eliminado" name="equipo_Eliminado" value="0">
                                                        </div>

                                                        <div class="col-12" style="text-align: right;">
                                                            <div class="form-group">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                                <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_equipo">
                                                                    Guardar <i class="fa fa-save"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!--tab 2 -->
                                <div class="tab-pane" id="tab2_equipo" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nuevo_equipo_documento">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Documento
                                                    </button>
                                                    @else
                                                    <h2 style="color: #ffff; margin: 0;"> Documento </h2>
                                                    @endif
                                                </ol><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_equipos_documentos" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="50">No.</th>
                                                                <th width="800">Documento</th>
                                                                <th width="70">Pdf</th>
                                                                <th width="70">Editar</th>

                                                                <th width="70">Eliminar</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>
<!-- /.Modal equipos -->






<!-- Modal Vehículos -->
<div id="modal_vehiculo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 86%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Equipo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab8_equipo" id="tab8_equipo_info" role="tab">Información del Vehículo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab9_equipo" role="tab">Documentos</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- tab 1 -->
                                <div class="tab-pane active" id="tab8_equipo" role="tabpanel">
                                    <div class="card-body">
                                        <style type="text/css" media="screen">
                                            .dropify-wrapper {
                                                height: 300px !important;
                                                /*tamaño estatico del campo foto*/
                                            }
                                        </style>
                                        <form enctype="multipart/form-data" method="post" name="form_vehiculo" id="form_vehiculo">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label> Foto del Vehículo </label>
                                                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="foto_vehiculo" name="foto_vehiculo" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" />
                                                            </div>
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))

                                                            <center>
                                                                <label> Estado del Vehículo </label>
                                                                <div class="switch">
                                                                    <label>
                                                                        Inactivo
                                                                        <input type="checkbox" id="checkbox_estado_vehiculo" checked onclick="actualiza_estado_vehiculo();">
                                                                        <span class="lever switch-col-light-blue"></span>
                                                                        Activo
                                                                    </label>
                                                                </div>
                                                            </center>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" class="form-control" id="vehiculo_id" name="vehiculo_id" value="0">
                                                            <input type="hidden" class="form-control" id="vehiculo_proveedor_id" name="proveedor_id" value="0">
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="form-group">
                                                                <label> Marca del Vehículo*</label>
                                                                <input type="text" class="form-control" id="vehiculo_marca" name="vehiculo_marca" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Línea del Vehículo*</label>
                                                                <input type="text" class="form-control" id="vehiculo_linea" name="vehiculo_linea" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Modelo *</label>
                                                                <input type="text" class="form-control" id="vehiculo_modelo" name="vehiculo_modelo" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Serie *</label>
                                                                <input type="text" class="form-control" id="vehiculo_serie" name="vehiculo_serie" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label> Placa *</label>
                                                                <input type="text" class="form-control" id="vehiculo_placa" name="vehiculo_placa" required>
                                                            </div>
                                                        </div>


                                                        <div class="col-12">
                                                            <input type="hidden" class="form-control" id="vehiculo_EstadoActivo" name="vehiculo_EstadoActivo" value="1">

                                                            <input type="hidden" class="form-control" id="vehiculo_Eliminado" name="vehiculo_Eliminado" value="0">
                                                        </div>

                                                        <div class="col-12" style="text-align: right;">
                                                            <div class="form-group">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                                <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_vehiculo">
                                                                    Guardar <i class="fa fa-save"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!--tab 2 -->
                                <div class="tab-pane" id="tab9_equipo" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nuevo_vehiculo_documento">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Documento
                                                    </button>
                                                    @else
                                                    <h2 style="color: #ffff; margin: 0;"> Documento </h2>
                                                    @endif
                                                </ol><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_vehiculos_documentos" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="50">No.</th>
                                                                <th width="800">Documento</th>
                                                                <th width="70">Pdf</th>
                                                                <th width="70">Editar</th>

                                                                <th width="70">Eliminar</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>
<!-- /.Modal Vehículos -->




<!-- Modal signatarios -->
<div id="modal_signatario" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Personal</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1_signatario" id="tab1_signatario_doc" role="tab">Información</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2_signatario" role="tab">Documentos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab3_signatario" role="tab">Cursos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab5_signatario" role="tab">Experiencia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab4_signatario" id="tab_signatario" role="tab">Alcances</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- tab 1 -->
                                <div class="tab-pane active" id="tab1_signatario" role="tabpanel">
                                    <div class="card-body">
                                        <style type="text/css" media="screen">
                                            .dropify-wrapper {
                                                height: 240px !important;
                                                /*tamaño estatico del campo foto*/
                                            }
                                        </style>
                                        <form enctype="multipart/form-data" method="post" name="form_signatario" id="form_signatario">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label> Foto *</label>
                                                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="signatariofoto" name="signatariofoto" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" required />
                                                            </div>
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                            <center>
                                                                <label> Estado del signatario </label>
                                                                <div class="switch">
                                                                    <label>
                                                                        Inactivo
                                                                        <input type="checkbox" id="checkbox_estado_signatario" checked onclick="actualiza_estado_signatario();">
                                                                        <span class="lever switch-col-light-blue"></span>
                                                                        Activo
                                                                    </label>
                                                                </div>
                                                            </center>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" class="form-control" id="signatario_id" name="signatario_id" value="0">
                                                            <input type="hidden" class="form-control" id="signatario_proveedor_id" name="proveedor_id" value="0">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Nombre completo *</label>
                                                                <input type="text" class="form-control" id="signatario_Nombre" name="signatario_Nombre" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Cargo *</label>
                                                                <input type="text" class="form-control" id="signatario_Cargo" name="signatario_Cargo" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Teléfono *</label>
                                                                <input type="text" class="form-control" id="signatario_Telefono" name="signatario_Telefono" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Correo </label>
                                                                <input type="email" class="form-control" id="signatario_Correo" name="signatario_Correo">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> RFC *</label>
                                                                <input type="text" class="form-control" id="signatario_Rfc" name="signatario_Rfc" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> CURP *</label>
                                                                <input type="text" class="form-control" id="signatario_Curp" name="signatario_Curp" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> NSS *</label>
                                                                <input type="text" class="form-control" id="signatario_Nss" name="signatario_Nss" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 p-3 d-flex justify-content-start">
                                                            <p>¿Es personal de apoyo?</p>
                                                            <div class="form-check mx-4">
                                                                <input class="form-check-input" type="radio" name="signatario_apoyo" id="signatario_apoyo_si" value="1" checked>
                                                                <label class="form-check-label" for="signatario_apoyo_si">
                                                                    Si
                                                                </label>
                                                            </div>
                                                            <div class="form-check mx-4">
                                                                <input class="form-check-input" type="radio" name="signatario_apoyo" id="signatario_apoyo_no" value="0">
                                                                <label class="form-check-label" for="signatario_apoyo_no">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> Tipo de sangre *</label>
                                                                <input type="text" class="form-control" id="signatario_TipoSangre" name="signatario_TipoSangre" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> Alergias *</label>
                                                                <input type="text" class="form-control" id="signatario_Alergias" name="signatario_Alergias" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label> Telefono de emergencia *</label>
                                                                <input type="text" class="form-control" id="signatario_telEmergencia" name="signatario_telEmergencia" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Nombre del contacto de emergencia *</label>
                                                                <input type="text" class="form-control" id="signatario_NombreContacto" name="signatario_NombreContacto" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label> Parentesco *</label>
                                                                <input type="text" class="form-control" id="signatario_parentesco" name="signatario_parentesco" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <input type="hidden" class="form-control" id="signatario_EstadoActivo" name="signatario_EstadoActivo" value="1">
                                                            <input type="hidden" class="form-control" id="signatario_Eliminado" name="signatario_Eliminado" value="0">
                                                        </div>
                                                        <div class="col-12" style="text-align: right;">
                                                            <div class="form-group">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                                <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_signatario">
                                                                    Guardar <i class="fa fa-save"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!--tab 2 -->
                                <div class="tab-pane" id="tab2_signatario" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nuevo_signatario_documento">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Documento
                                                    </button>
                                                    @else
                                                    <h2 style="color: #ffff; margin: 0;"> Documento </h2>

                                                    @endif
                                                </ol><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_signatario_documentos" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="50">No.</th>
                                                                <th width="300">Tipo</th>
                                                                <th width="500">Documento</th>
                                                                <th width="70">Pdf</th>
                                                                <th width="70">Editar</th>
                                                                <th width="70">Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--tab 3 -->
                                <div class="tab-pane" id="tab3_signatario" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10" style="display: flex; justify-content: space-between; align-items: center;">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))

                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nuevo_curso">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Curso
                                                    </button>
                                                    @else
                                                    Cursos
                                                    @endif
                                                </ol>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_signatario_cursos" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 80px!important;">No.</th>
                                                                <th>Curso</th>
                                                                <th>Expedición</th>
                                                                <th>Vigencia</th>
                                                                <th style="width: 80px!important;">Pdf</th>
                                                                <th style="width: 80px!important;">Doc.Validación</th>
                                                                <th style="width: 80px!important;">Editar</th>
                                                                <th style="width: 80px!important;">Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- tab 5 -->
                                <div class="tab-pane" id="tab5_signatario" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nuevo_experiencia">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Experiencia
                                                    </button>
                                                    @else
                                                    Experiencias
                                                    @endif
                                                </ol><br>
                                                <div class="col-12 mb-3" id="periodoExperiencia">
                                                    <!-- <h3 id="periodoExperiencia"></h3> -->
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_signatario_experiencia" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 80px!important;">No.</th>
                                                                <th>Empresa</th>
                                                                <th>Cargo</th>
                                                                <th>Fecha inicio / Fecha fin</th>
                                                                <th style="width: 50px!important;">Pdf</th>
                                                                <th style="width: 50px!important;">Editar</th>
                                                                <th style="width: 50px!important;">Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--tab 4 -->
                                <div class="tab-pane" id="tab4_signatario" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary botonnuevo_moduloproveedores" style="width: auto;" id="boton_nueva_pruebasignatario">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Alcances (Agente / Factor de riesgo / Servicio)
                                                    </button>
                                                    @else
                                                    Alcances (Agente / Factor de riesgo / Servicio)
                                                    @endif
                                                </ol><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_signatario_acreditaciones" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 60px!important;">No.</th>
                                                                <th>Acreditación / Aprobación</th>
                                                                <th>Agente / Factor de riesgo / Servicio</th>
                                                                <th>Estado</th>
                                                                <th>Vigencia</th>
                                                                <th>Disponibilidad</th>
                                                                <th style="width: 70px!important;">Editar</th>
                                                                <th style="width: 70px!important;">Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
            </div>
        </div>
    </div>
</div>
<!-- /.Modal signatarios -->

<!-- Modal servicios -->
<style type="text/css" media="screen">
    #modal_servicio .form-group {
        margin: 0px 0px 6px 0px;
        padding: 0px;
    }

    #tabla_listapartidas th {
        border: 0px #F00 solid !important;
    }

    #tabla_listapartidas td {
        padding: 4px 0px 4px 12px;
        text-align: left;
        vertical-align: middle !important;
        /*border: 0px #CCC solid!important;*/
    }

    #tabla_listapartidas td select .cal {
        color: #F00;
    }
</style>
<div id="modal_servicio" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_servicio" id="form_servicio">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cotización</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="servicio_id" name="servicio_id" value="0">
                            <input type="hidden" class="form-control" id="servicio_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> No. cotización *</label>
                                <input type="text" class="form-control" id="servicio_numerocotizacion" name="servicio_numerocotizacion" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Fecha *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="servicio_FechaCotizacion" name="servicio_FechaCotizacion" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Vigencia *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="servicio_VigenciaCotizacion" name="servicio_VigenciaCotizacion" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Cotización PDF *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_servicio">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" id="servicio_SoportePDF" name="serviciopdf" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Observación *</label>
                                <textarea class="form-control" rows="2" id="servicio_Observaciones" name="servicio_Observaciones" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="servicio_Eliminado" name="servicio_Eliminado" value="0">
                        </div>
                        <div class="col-12">
                            <ol class="breadcrumb m-b-10">
                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" style="float: left;" data-toggle="tooltip" title="Agregar partida alcance" id="boton_nuevapartida_alcance">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span> Partida alcance
                                </button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproveedores" style="float: right;" data-toggle="tooltip" title="Agregar partida adicional" id="boton_nuevapartida_adicional">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span> <b class="text-info" style="font-weight: normal!important;">Partida adicional</b>
                                </button>
                            </ol>
                            <div class="table-responsive" style="border: 2px #DDDDDD solid;" id="div_tabla_listapartidas">
                                <table class="table table-hover stylish-table" width="100%" id="tabla_listapartidas">
                                    <thead style="display: block; border-bottom: 2px #DDDDDD solid;">
                                        <tr>
                                            <th style="width: 60px!important;">Partida</th>
                                            <th style="width: 500px!important;">Agente / Factor riesgo / Servicio</th>
                                            <th style="width: 250px!important; text-align:left">Precio unitario *</th>
                                            <th style="width: 150px!important;">Desactivar</th>
                                            <th style="width: 25px!important;">Eliminar</th>
                                        </tr>

                                    </thead>
                                    <tbody style="display: block; height: 250px!important; max-height: 250px!important; overflow-y: auto;"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalcotizacion">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras']))
                    <button type="button" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_servicio">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal servicios -->

<!-- Modal Excel equipos -->
<div id="modal_excel_equipo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="formExcelEquipos" id="formExcelEquipos">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Carga de Equipos por medio de un Excel</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento Excel *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_equipo">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept=".xls,.xlsx" name="excelEquipos" id="excelEquipos" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-2" id="alertaVerificacion" style="display:none">
                        <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el archivo Excel contenga fechas en los formatos válidos: '2024-01-01', '01-01-2024', '2024/01/01', '01/01/2024' (no se admiten fechas con texto). Además, verifique que los campos "Requiere calibración" y "Requiere Imagen" estén correctamente completados y que las imágenes no se encuentren dentro de las celdas. </p>
                    </div>
                    <div class="row mt-3" id="divCargaEquipos" style="display: none;">

                        <div class="col-12 text-center">
                            <h2>Cargando equipo espere un momento...</h2>
                        </div>
                        <div class="col-12 text-center">
                            <i class='fa fa-spin fa-spinner fa-5x'></i>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarExcelEquipos">
                        Cargar equipos <i class="fa fa-upload" aria-hidden="true"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal Excel de equipos -->


<!-- Modal Excel equipos -->
<div id="modal_excel_personal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="formExcelPersonal" id="formExcelPersonal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Carga de Personal por medio de un Excel</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento Excel *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_personal">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept=".xls,.xlsx" name="excelPersonales" id="excelPersonal" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-2" id="alertaVerificacion2" style="display:none">
                        <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que las columnas "Personal de apoyo" y "Requiere foto" estén correctamente completadas y que las imágenes no se encuentren dentro de las celdas. </p>
                    </div>
                    <div class="row mt-3" id="divCargaPersonal" style="display: none;">

                        <div class="col-12 text-center">
                            <h2>Cargando lista de personal espere un momento...</h2>
                        </div>
                        <div class="col-12 text-center">
                            <i class='fa fa-spin fa-spinner fa-5x'></i>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarExcelPersonal">
                        Cargar personal <i class="fa fa-upload" aria-hidden="true"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal Excel de equipos -->

<!-- ============================================================== -->
<!-- MODALES -->
<!-- ============================================================== -->




<!-- ============================================================== -->
<!-- SUB-MODALES -->
<!-- ============================================================== -->

<!-- Modal signatarios documentos -->
<div id="modal_signatario_documento" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_signatario_documento" id="form_signatario_documento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="signatario_doc_id" name="signatario_doc_id" value="0">
                            <input type="hidden" class="form-control" id="signatario_documento_id" name="signatario_id" value="0">
                            <input type="hidden" class="form-control" id="signatario_documento_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo de documento *</label>
                                <select class="custom-select form-control" id="signatarioDocumento_Tipo" name="signatarioDocumento_Tipo" required>
                                    <option disabled selected>Seleccione un tipo...</option>
                                    <option value="Personal">Personal</option>
                                    <option value="Academico">Académico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Documento *</label>
                                <select class="custom-select form-control" id="signatarioDocumentoEleccion" name="signatarioDocumentoEleccion" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" id="signatarioDocumento_Nombre" name="signatarioDocumento_Nombre" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Fecha vigencia *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="signatarioDocumento_FechaVigencia" name="signatarioDocumento_FechaVigencia" required disabled>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_signatario_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="signatariodocumentopdf" id="signatariodocumentopdf" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="signatarioDocumento_Eliminado" name="signatarioDocumento_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario','Administrador','Compras','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_signatario_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal signatarios documentos -->

<!-- Modal signatarios cursos -->
<div id="modal_signatario_curso" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_signatario_curso" id="form_signatario_curso">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Curso</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="curso_id" name="curso_id" value="0">
                            <input type="hidden" class="form-control" id="curso_signatario_id" name="signatario_id" value="0">
                            <input type="hidden" class="form-control" id="curso_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del curso *</label>
                                <input type="text" class="form-control" id="signatarioCurso_NombreCurso" name="signatarioCurso_NombreCurso" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Fecha expedición *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="signatarioCurso_FechaExpedicion" name="signatarioCurso_FechaExpedicion" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Fecha vigencia </label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="signatarioCurso_FechaVigencia" name="signatarioCurso_FechaVigencia">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Folio *</label>
                                <input type="text" class="form-control" id="signatarioCurso_FolioCurso" name="signatarioCurso_FolioCurso" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_curso_signatario">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" id="signatariocursopdf" name="signatariocursopdf" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="signatarioCurso_Eliminado" name="signatarioCurso_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_signatario_curso">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal signatarios cursos -->

<!-- Modal signatarios experiencia -->
<div id="modal_signatario_experiencia" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_signatario_experiencia" id="form_signatario_experiencia">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Experiencia</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_EXPERIENCIA" name="ID_EXPERIENCIA" value="0">
                            <input type="hidden" class="form-control" id="EXPERIENCIA_SIGNATARIO_ID" name="SIGNATARIO_ID" value="0">
                            <input type="hidden" class="form-control" id="EXPERIENCIA_PROVEEDOR_ID" name="PROVEEDOR_ID" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Empresa en la trabajo*</label>
                                <input type="text" class="form-control" id="NOMBRE_EMPRESA" name="NOMBRE_EMPRESA" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Cargo que tuvo*</label>
                                <input type="text" class="form-control" id="CARGO" name="CARGO" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Fecha de Incio *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_INICIO" name="FECHA_INICIO" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Fecha de Fin *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_FIN" name="FECHA_FIN" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_experiencia_signatario">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" id="EXPERIENCIA_PDF" name="PDF" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ELIMINADO" name="ELIMINADO" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_signatario_experiencia">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal signatarios experiencia -->


<!-- Modal signatarios acreditaciones -->
<div id="modal_signatario_acreditacion" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_signatario_acreditacion" id="form_signatario_acreditacion">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alcance signatario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="signatarioacreditacion_id" name="signatarioacreditacion_id" value="0">
                            <input type="hidden" class="form-control" id="acreditacion_signatario_id" name="signatario_id" value="0">
                            <input type="hidden" class="form-control" id="signatarioacreditacion_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo alcance *</label>
                                <select class="custom-select form-control" id="acreditacionAlcance_tipo" name="acreditacionAlcance_tipo" onchange="validatipoalcance(this.value, 0);" required>
                                    <option value=""></option>
                                    @foreach($tipopruebas as $tipo)
                                    <option value="{{$tipo->catPrueba_Tipo}}">{{$tipo->catPrueba_Tipo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="div_campo_factor">
                            <div class="form-group">
                                <label>Agente / Factor de riesgo / Servicio *</label>
                                <select class="custom-select form-control" id="prueba_id" name="prueba_id" onchange=" (this);" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="div_campo_agente" style="display: none;">
                            <div class="form-group">
                                <label> Agente químico *</label>
                                <!-- <input type="text" class="form-control" id="acreditacionAlcance_agente" name="acreditacionAlcance_agente"> -->

                                <select class="custom-select form-control" id="acreditacionAlcance_agente" name="acreditacionAlcance_agente">
                                    <option value=""></option>
                                    @foreach($sustanciasQuimicas as $sustancia)
                                    <option value="{{$sustancia->SUSTANCIA_QUIMICA}}">{{$sustancia->SUSTANCIA_QUIMICA}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label>Factor de riesgo / Servicio *</label>
                                <select class="custom-select form-control" id="signatarioAcreditacion_Alcance" name="signatarioAcreditacion_Alcance" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Estado *</label>
                                <select class="custom-select form-control" id="cat_signatarioestado_id" name="cat_signatarioestado_id" onchange="valida_signatarioestado(this.value);" required>
                                    <option value=""></option>
                                    @foreach($signatarioestado as $value)
                                    <option value="{{$value->id}}">{{$value->cat_Signatarioestado_Nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Expedición *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="signatarioAcreditacion_Expedicion" name="signatarioAcreditacion_Expedicion" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Vigencia *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="signatarioAcreditacion_Vigencia" name="signatarioAcreditacion_Vigencia" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label> Disponibilidad *</label>
                            <select class="custom-select form-control" id="cat_signatariodisponibilidad_id" name="cat_signatariodisponibilidad_id" required>
                                <option value=""></option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="signatarioAcreditacion_Eliminado" name="signatarioAcreditacion_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_signatario_acreditacion">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal signatarios acreditaciones -->


<!-- Modal equipos documentos -->
<div id="modal_equipo_documento" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_equipo_documento" id="form_equipo_documento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}

                            <input type="hidden" class="form-control" id="equipo_doc_id" name="ID_EQUIPO_DOCUMENTO" value="0">
                            <input type="hidden" class="form-control" id="equipo_documento_id" name="EQUIPO_ID" value="0">
                            <input type="hidden" class="form-control" id="equipo_documento_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de documento *</label>
                                <select class="custom-select form-control" id="DOCUMENTO_TIPO" name="DOCUMENTO_TIPO" required>
                                    <option disabled selected>Seleccione un tipo</option>
                                    <option value="1">Factura</option>
                                    <option value="2">Ficha técnica</option>
                                    <option value="3">Validación de la factura</option>
                                    <option value="4">Certificado de calibración</option>
                                    <option value="5">Carta de calibración</option>
                                    <option value="6">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" id="NOMBRE_DOCUMENTO" name="NOMBRE_DOCUMENTO" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_equipo_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="EQUIPO_PDF" id="EQUIPO_PDF" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="EQUIPO_DOCUMENTO_ELIMINADO" name="ACTIVO" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_equipo_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal equipos documentos -->



<!-- Modal Vehículos documentos -->
<div id="modal_vehiculo_documento" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_vehiculo_documento" id="form_vehiculo_documento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}

                            <input type="hidden" class="form-control" id="vehiculo_doc_id" name="ID_VEHICULO_DOCUMENTO" value="0">
                            <input type="hidden" class="form-control" id="vehiculo_documento_id" name="VEHICULO_ID" value="0">
                            <input type="hidden" class="form-control" id="vehiculo_documento_proveedor_id" name="proveedor_id" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de documento *</label>
                                <select class="custom-select form-control" id="DOCUMENTO_TIPO_VEHICULOS" name="DOCUMENTO_TIPO_VEHICULOS" required>
                                    <option disabled selected>Seleccione un tipo</option>
                                    <option value="1">Tarjeta de circulación</option>
                                    <option value="2">Póliza de seguro</option>
                                    <option value="3">Verificación vehicular</option>
                                    <option value="4">Factura</option>
                                    <option value="5">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" id="NOMBRE_DOCUMENTO_VEHICULOS" name="NOMBRE_DOCUMENTO_VEHICULOS" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_vehiculo_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="VEHICULO_PDF" id="VEHICULO_PDF" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="VEHICULO_DOCUMENTO_ELIMINADO" name="ACTIVO" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Almacén']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_vehiculo_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal Vehículos  documentos -->




<!-- Modal de documentos de validacion de cursos -->
<div id="modal_cursos_documentos" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_cursos_documentos" id="form_cursos_documentos">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="nombre_curso_validacion">Documentos de validación del curso</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_DOCUMENTO_CURSO" name="ID_DOCUMENTO_CURSO" value="0">
                            <input type="hidden" class="form-control" id="VALIDACION_CURSO_ID" name="CURSO_ID" value="0">
                            <input type="hidden" class="form-control" id="VALIDACION_SIGNATARIO_ID" name="SIGNATARIO_ID" value="0">
                            <input type="hidden" class="form-control" id="VALIDACION_PROVEEDOR_ID" name="PROVEEDOR_ID" value="0">


                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Nombre del documento*</label>
                                <input type="text" class="form-control" id="NOMBRE_DOCUMENTO_VALIDACION" name="NOMBRE_DOCUMENTO" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_validacion_curso">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" id="PDF_VALIDACION" name="PDF_VALIDACION" required>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="VALIDACION_ELIMINADO" name="ELIMINADO" value="0">
                        </div>
                        <div class="col-12">
                            <div class="table-responsive" #DDDDDD solid;" id="div_tabla_documentos_validacion">
                                <table class="table table-hover stylish-table" width="100%" id="tabla_cursosdocumentos_validacion">
                                    <thead style="display: block; border-bottom:  solid;">
                                        <tr>
                                            <th style="width: 60px!important;">No</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalcotizacion">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Compras','Almacén']))
                    <button type="button" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_documento_validacion_curso">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de documentosd de validacion de cursos -->

<!-- ============================================================== -->
<!-- SUB-MODALES -->
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