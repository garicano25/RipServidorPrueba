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

    .ocultar {
        display: none;
    }

    .mostrar {
        display: block;
    }
</style>

<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->

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
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" role="tab" id="tab_tabla_cliente">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de clientes</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_info_cliente">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Datos del cliente</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_3" role="tab" id="tab_info_contratos">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Datos del servicio</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Info lista clientes -->
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-address-book-o"></i> Clientes </h2>
                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Nuevo cliente" id="boton_nuevo_cliente" style="margin-left: auto;">
                                Cliente <i class="fa fa-plus p-1"></i>
                            </button>
                        </ol>
                        @endif
                        <table class="table table-hover stylish-table" id="tabla_clientes" width="100%">
                            <thead>
                                <tr>
                                    <th width="80">No.</th>
                                    <th>Razón social</th>
                                    <th>Línea de negocios</th>
                                    <th>Ciudad / País</th>
                                    <th>Dirección</th>
                                    <th>Representante legal</th>
                                    <th>RFC</th>
                                    <!-- <th>Vigencia</th> -->
                                    <th width="80">Mostrar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- Info Lista de contratos -->
                <div class="tab-pane p-20" id="tab_2" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 6px 10px" id="encabezado_contrato">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-address-card-o"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_cliente_nombre">Nombre</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Cliente</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_cliente_lineanegocio">Línea de negocios</a></h4>
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

                        <ol class="breadcrumb m-b-10">

                            <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-id-badge" aria-hidden="true"></i> Información del cliente </h2>

                        </ol>
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <form enctype="multipart/form-data" method="post" name="form_cliente" id="form_cliente">
                                    <div class="row">
                                        <div class="col-12">
                                            {{-- {!! method_field('PUT') !!} --}}
                                            {!! csrf_field() !!}
                                            <input type="hidden" class="form-control" id="cliente_id" name="cliente_id" value="0">
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Razón social *</label>
                                                <input type="text" class="form-control" id="cliente_RazonSocial" name="cliente_RazonSocial" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Nombre comercial *</label>
                                                <input type="text" class="form-control" id="cliente_NombreComercial" name="cliente_NombreComercial" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Línea de negocios</label>
                                                <input type="text" class="form-control" id="cliente_LineaNegocios" name="cliente_LineaNegocios">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <label> Razón social para firma del contrato (En caso de que aplique)</label>
                                                <input type="text" class="form-control" id="cliente_RazonSocialContrato" name="cliente_RazonSocialContrato">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> RFC *</label>
                                                <input type="text" class="form-control" id="cliente_Rfc" name="cliente_Rfc" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Código Postal *</label>
                                                <input type="number" class="form-control" id="cliente_cp" name="cliente_cp" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Giro comercial</label>
                                                <input type="text" class="form-control" id="cliente_GiroComercial" name="cliente_GiroComercial">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Domicilio fiscal *</label>
                                                <input type="text" class="form-control" id="cliente_DomicilioFiscal" name="cliente_DomicilioFiscal" required>
                                            </div>
                                        </div>


                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Ciudad *</label>
                                                <input type="text" class="form-control" id="cliente_CiudadPais" name="cliente_CiudadPais" placeholder="Eje: Villahermosa." required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> País *</label>
                                                <input type="text" class="form-control" id="cliente_Pais" name="cliente_Pais" placeholder="Eje: México." required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Página web</label>
                                                <input type="text" class="form-control" id="cliente_PaginaWeb" name="cliente_PaginaWeb">
                                            </div>
                                        </div>

                                        <div class="col-lg-8 col-sm-6">
                                            <div class="form-group">
                                                <label> Representante legal</label>
                                                <input type="text" class="form-control" id="cliente_RepresentanteLegal" name="cliente_RepresentanteLegal" required>
                                            </div>
                                        </div>

                                        {{-- <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Region *</label>
                                                <select class="custom-select form-control" id="region_id" name="region_id" required>
                                                    <option value=""></option>
                                                    @foreach($catregion as $dato)
                                                    <option value="{{$dato->id}}">{{$dato->catregion_nombre}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                            </div> --}}
                            <!-- <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Instalacion</label>
                                                <input type="text" class="form-control" id="cliente_instalacion" name="cliente_instalacion">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label> Departamento</label>
                                                <input type="text" class="form-control" id="cliente_departamento" name="cliente_departamento">
                                            </div>
                                        </div> -->
                            {{-- <div class="col-12">
                                            <div class="form-group">
                                                <label> Subdireccion *</label>
                                                <select class="custom-select form-control" id="subdireccion_id" name="subdireccion_id" required>
                                                    <option value=""></option>
                                                    @foreach($catsubdireccion as $dato)
                                                    <option value="{{$dato->id}}">{{$dato->catsubdireccion_siglas}} - {{$dato->catsubdireccion_nombre}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label> Gerencia *</label>
                            <select class="custom-select form-control" id="gerencia_id" name="gerencia_id" required>
                                <option value=""></option>
                                @foreach($catgerencia as $dato)
                                <option value="{{$dato->id}}">{{$dato->catgerencia_siglas}} - {{$dato->catgerencia_nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label> Activo *</label>
                            <select class="custom-select form-control" id="activo_id" name="activo_id" required>
                                <option value=""></option>
                                @foreach($catactivo as $dato)
                                <option value="{{$dato->id}}">{{$dato->catactivo_siglas}} - {{$dato->catactivo_nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-12 d-flex align-items-center mb-3">
                        <h3 class="me-2" style="color: #0B3F64!important;">Estructura organizacional </h3>
                        <div class="switch mx-4">
                            <label>
                                No
                                <input type="checkbox" id="requiere_estructuraCliente" name="requiere_estructuraCliente_switch" onchange="requiereOrganizacional()">
                                <span class="lever switch-col-light-blue" id="checkbox_solicitudOS"></span>
                                Si
                            </label>
                        </div>
                    </div>
                    <!-- Campo oculto para guardar el valor "Si" o "No" -->
                    <input type="hidden" id="requiere_estructuraCliente_activo" name="requiere_estructuraCliente" value="No">

                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Nivel 1 (Considerado como el más alto) </label>
                            <input type="hidden" name="NIVEL[]" value="1">

                            <select class="custom-select form-control etiqueta-select" id="ETIQUETA1_ID" name="ETIQUETA_ID[]" onchange="obteneretiquetas(1)">
                                <option value=""></option>
                                @foreach($etiqueta as $dato)
                                <option value="{{ $dato->ID_ETIQUETA }}">{{ $dato->NOMBRE_ETIQUETA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Clasificación </label>
                            <select class="custom-select form-control opciones-select" id="OPCIONES1_ID" name="OPCIONES_ID[]">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Nivel 2 </label>
                            <input type="hidden" name="NIVEL[]" value="2">

                            <select class="custom-select form-control etiqueta-select" id="ETIQUETA2_ID" name="ETIQUETA_ID[]" onchange="obteneretiquetas(2)">
                                <option value=""></option>
                                @foreach($etiqueta as $dato)
                                <option value="{{ $dato->ID_ETIQUETA }}">{{ $dato->NOMBRE_ETIQUETA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Clasificación </label>
                            <select class="custom-select form-control opciones-select" id="OPCIONES2_ID" name="OPCIONES_ID[]">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Nivel 3 </label>
                            <input type="hidden" name="NIVEL[]" value="3">

                            <select class="custom-select form-control etiqueta-select" id="ETIQUETA3_ID" name="ETIQUETA_ID[]" onchange="obteneretiquetas(3)">
                                <option value=""></option>
                                @foreach($etiqueta as $dato)
                                <option value="{{ $dato->ID_ETIQUETA }}">{{ $dato->NOMBRE_ETIQUETA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Clasificación</label>
                            <select class="custom-select form-control opciones-select" id="OPCIONES3_ID" name="OPCIONES_ID[]">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Nivel 4 </label>
                            <input type="hidden" name="NIVEL[]" value="4">

                            <select class="custom-select form-control etiqueta-select " id="ETIQUETA4_ID" name="ETIQUETA_ID[]" onchange="obteneretiquetas(4)">
                                <option value=""></option>
                                @foreach($etiqueta as $dato)
                                <option value="{{ $dato->ID_ETIQUETA }}">{{ $dato->NOMBRE_ETIQUETA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Clasificación </label>
                            <select class="custom-select form-control opciones-select" id="OPCIONES4_ID" name="OPCIONES_ID[]">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Nivel 5 </label>
                            <input type="hidden" name="NIVEL[]" value="5">

                            <select class="custom-select form-control etiqueta-select" id="ETIQUETA5_ID" name="ETIQUETA_ID[]" onchange="obteneretiquetas(5)">
                                <option value=""></option>
                                @foreach($etiqueta as $dato)
                                <option value="{{ $dato->ID_ETIQUETA }}">{{ $dato->NOMBRE_ETIQUETA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 selector-group">
                        <div class="form-group">
                            <label>Clasificación </label>
                            <select class="custom-select form-control opciones-select" id="OPCIONES5_ID" name="OPCIONES_ID[]">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-6">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                        <div class="form-group" style="text-align: left;">
                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="tooltip" title="Click para cambiar estado" id="boton_bloquear_cliente" value="0" onclick="bloqueo_cliente(this.value);">
                                <span class="btn-label"><i class="fa fa-unlock"></i></span> Cliente desbloqueado para edición
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="col-6">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                        <div class="form-group" style="text-align: right;">
                            <button type="submit" class="btn btn-danger boton_modulocliente" id="boton_guardar_cliente">
                                Guardar <i class="fa fa-save"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                </form>
            </div>
        </div>


        @if(auth()->user()->hasRoles(['Superusuario','Administrador']))
        <ol class="breadcrumb m-b-10">
            <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-handshake-o" aria-hidden="true"></i> Servicios </h2>
            <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente" style="margin-left:auto" id="boton_nuevo_contrato">
                Servicios <i class="fa fa-plus p-1"></i>
            </button>
        </ol>
        <div class="row mt-3 listaContratos mostrar" id="listaContratos">
            <!-- Tabla de la lista de los contrartos -->
            <div class="col-12">
                <!-- <ol class="breadcrumb m-b-10">
                                    <button type="button" class="btn btn-block btn-outline-secondary boton_modulocliente" style="width: auto;" id="boton_nuevo_contrato">
                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Contrato - PO
                                    </button>
                                </ol> -->
                <!-- <br> -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover stylish-table" width="100%" id="tabla_clientecontratos">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Tipo de servicios</th>
                                        <th>No. de contrato - PO</th>
                                        <th width="500">Objeto del servicio</th>
                                        <th>Convenios</th>
                                        <th>Fecha inicio <br> Fecha fin</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif
    </div>
</div>
<!-- Infor contratos -->
<div class="tab-pane p-20" id="tab_3" role="tabpanel">
    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;" id="divContratos">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" style="padding: 6px 10px">
                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                            <tbody>
                                <tr>
                                    <td width="40" style="text-align: center; border: none;">
                                        <span class="btn btn-success btn-circle"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                    </td>
                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                        <h4 style="margin: 0px;"><a class="text-success div_descripcion_contrato">Objeto del servicio</a></h4>
                                        <small style="color: #AAAAAA; font-size: 12px;">Objeto del servicio</small>
                                    </td>
                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                        <h4 style="margin: 0px;"><a class="text-success div_numero_contrato">Número de servicio</a></h4>
                                        <small style="color: #AAAAAA; font-size: 12px;">Número de servicio</small>
                                    </td>
                                    <td width="40" style="text-align: center; border: none;">
                                        <span class="btn btn-success btn-circle"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <ol class="breadcrumb m-b-10" style="background-color:#ffff; color :#0c3f64;  border: #0c3f64 2px solid;">
                    <!-- Informacion Basica del contrato -->
                    <div class="row">
                        <div class="col-4">
                            <p><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Fecha Inicio: <span id="contrato_fecha_inicio" style="color: #009efb;"></span></p>

                        </div>
                        <div class="col-4">
                            <p><i class="fa fa-calendar-times-o" aria-hidden="true"></i>Fecha vigencia: <span id="contrato_fecha_final" style="color: #009efb;"></span></p>

                        </div>
                        <div class="col-4">
                            <p><i class="fa fa-usd" aria-hidden="true"></i>Monto: <span id="contrato_monto" style="color: #009efb;"></span> </p>

                        </div>
                        <div class="col-6">
                            <p><i class="fa fa-user-o" aria-hidden="true"></i>Nombre contacto: <span id="contrato_nombre_contacto" style="color: #009efb;"></span> </p>

                        </div>
                        <div class="col-6">
                            <p><i class="fa fa-envelope-o" aria-hidden="true"></i>Correo contacto: <span id="contrato_correo_contacto" style="color: #009efb;"></span> </p>

                        </div>
                        <div class="col-4">
                            <p><i class="fa fa-phone-square" aria-hidden="true"></i>Telefono: <span id="contrato_telefono" style="color: #009efb;"></span> </p>

                        </div>
                        <div class="col-4">
                            <p><i class="fa fa-users" aria-hidden="true"></i>Cargo: <span id="contrato_cargo" style="color: #009efb;"></span> </p>
                        </div>
                        <div class="col-4">
                            <p><i class="fa fa-clock-o" aria-hidden="true"></i>Duración del servicio: <span id="contrato_duracion" style="color: #009efb;"></span> </p>
                        </div>
                    </div>
                </ol>
            </div>
        </div>
        <div style="min-width: 700px; width: 100% ;margin: 0px auto;">
            <!--multisteps-form-->
            <div class="multisteps-form">
                <div class="row">
                    <div class="col-12">
                        <div class="multisteps-form__progress">
                            <div class="multisteps-form__progress-btn js-active" id="steps_menu_tab1">
                                <i class="fa fa-briefcase"></i><br>
                                <span>Datos generales</span>
                            </div>
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                            <div class="multisteps-form__progress-btn" id="steps_menu_tab2">
                                <i class="fa fa-file-text-o"></i><br>
                                <span>Convenios</span>
                            </div>
                            <div class="multisteps-form__progress-btn" id="steps_menu_tab3">
                                <i class="fa fa-calendar"></i><br>
                                <span>Cronograma de trabajo</span>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row mt-3 infoContratos" id="infoContratos">
                    <div class="col-12" style="display: none;" id="documentos_contrato">
                        <div class="multisteps-form__form">
                            <!--STEP 1 INFORMACION DEL CONTRATO-->
                            <div class="multisteps-form__panel js-active" data-animation="scaleIn" id="steps_contenido_tab1">
                                <div class="multisteps-form__content">
                                    <div class="row">
                                        <div class="col-12">

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-files-o" aria-hidden="true"></i> Documentación del servicio</h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_nuevo_documento">
                                                    Documento <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>

                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table" width="100%" id="tabla_documentos">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 50px!important;">No.</th>
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
                                            @endif

                                            <!-- Plantillas del contrato -->
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-word-o" aria-hidden="true"></i> Configuración de la plantilla para los reportes </h2>

                                            </ol>
                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <form enctype="multipart/form-data" method="post" name="form_plantilla" id="form_plantilla">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                {{-- {!! method_field('PUT') !!} --}}
                                                                {!! csrf_field() !!}
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <script type="text/javascript">
                                                                    var banco_imagenes = <?php echo $banco_img; ?>;
                                                                </script>
                                                                <div class="form-group">
                                                                    <label>Logo izquierdo encabezado</label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" id="banco_imagenes_izquierdo">
                                                                            <option selected>Seleccione un Logo precargado</option>
                                                                        </select>
                                                                    </div>
                                                                    <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="plantillalogoizquierdo" name="plantillalogoizquierdo" data-allowed-file-extensions="jpg png JPG PNG" data-height="164" data-default-file="">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-4 col-sm-12 d-none">
                                                                                <div class="form-group">
                                                                                    <label class="mb-4" style="text-align: center!important;">Título encabezado (Portada)</label>
                                                                                    <textarea class="form-control mt-5" style="text-align: center!important;" rows="8" id="cliente_plantillaencabezado" name="CONTRATO_PLANTILLA_ENCABEZADO" placeholder="Ejemplo: Pemex Exploración y Producción Subdirección de Seguridad, Salud en el Trabajo y Protección Ambiental" required></textarea>
                                                                                </div>
                                                                            </div> -->
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Logo derecho encabezado</label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" id="banco_imagenes_derecho">
                                                                            <option selected>Seleccione un Logo precargado</option>
                                                                        </select>
                                                                    </div>
                                                                    <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="plantillalogoderecho" name="plantillalogoderecho" data-allowed-file-extensions="jpg png JPG PNG" data-height="164" data-default-file="">
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label>Empresa responsable del informe</label>
                                                                    <input type="text" class="form-control" id="cliente_plantillaempresaresponsable" name="CONTRATO_PLANTILLA_EMPRESARESPONSABLE" placeholder="Ejemp: Results In Performance SA. de CV." required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Pie de página</label>
                                                                    <textarea class="form-control" style="text-align: center!important;" rows="6" id="cliente_plantillapiepagina" name="CONTRATO_PLANTILLA_PIEPAGINA" required></textarea>
                                                                    <div style="text-align: center!important; border: 0px #000 solid;">Folio: XXXXX-XX-XXX</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger boton_modulocliente contrato" id="boton_guardar_plantilla">
                                                                        Guardar <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Anexos para los informes de Reconocimientos -->
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-file-text"></i> Anexos para informes </h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_nuevo_anexo">
                                                    Anexo <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>

                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table" width="100%" id="tabla_anexos">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 50px!important;">No.</th>
                                                                    <th>Nombre</th>
                                                                    <th>Tipo de documento</th>
                                                                    <th style="width: 70px!important;">Eliminar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <!-- Partidas del contrato -->
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-list-alt" aria-hidden="true"></i> Partidas del servicio </h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_nueva_partida">
                                                    Partidas <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>
                                            @else
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-list-alt" aria-hidden="true"></i> Partidas del servicio </h2>
                                            </ol>
                                            @endif
                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table table-bordered" width="100%" id="tabla_clientepartidas">
                                                            <thead>
                                                                <tr>
                                                                    <th width="60">No.</th>
                                                                    <th width="150">Tipo</th>
                                                                    <th width="200">Agente / Parámetro</th>
                                                                    <th width="400">Descripción partida</th>
                                                                    <th>Unidad de medida</th>
                                                                    <th>Precio unitario</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>



                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <!-- Documentos de cierre -->

                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-book" aria-hidden="true"></i> Documento de cierre </h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_documento_cierre">
                                                    Documento <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>
                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table" width="100%" id="tabla_documentos_cierre">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 50px!important;">No.</th>
                                                                    <th>Documento</th>
                                                                    <th>Justificación de cierre </th>
                                                                    <th>Autorizar</th>
                                                                    <th style="width: 50px!important;">PDF</th>
                                                                    <th style="width: 50px!important;">Editar</th>
                                                                    <th style="width: 50px!important;">Eliminar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                            <!--STEP 2 INFORMACION DE LOS CONVENIOS-->
                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab2">
                                <div class="multisteps-form__content">
                                    <div class="row">
                                        <div class="col-12">
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <!-- Convenios de ampliacion del contrato -->
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-handshake-o" aria-hidden="true"></i> Convenio de ampliación </h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_nuevo_convenio">
                                                    Convenio <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>
                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table table-bordered" width="100%" id="tabla_clienteconvenios">
                                                            <thead>
                                                                <tr>
                                                                    <th width="60">No.</th>
                                                                    <th width="">Tipo</th>
                                                                    <th width="200">Monto MXN</th>
                                                                    <th width="200">Monto USD</th>
                                                                    <th width="200">Vigencia</th>
                                                                    <th width="60">Editar</th>
                                                                    <th width="60">Eliminar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                                            <!-- Partidas del contrato -->
                                            <ol class="breadcrumb m-b-10">
                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-list-alt" aria-hidden="true"></i> Partidas del servicio (Para convenios) </h2>

                                                <button type="button" class="btn btn-secondary waves-effect waves-light boton_modulocliente contrato" style="margin-left: auto;" id="boton_nueva_partida_convenio">
                                                    Partidas <i class="fa fa-plus p-1"></i>
                                                </button>
                                            </ol>
                                            @else
                                            <ol class="breadcrumb m-b-10">
                                                Partidas del servicio (Para convenios)
                                            </ol>
                                            @endif
                                            <br>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stylish-table table-bordered" width="100%" id="tabla_clientepartidas_convenio">
                                                            <thead>
                                                                <tr>
                                                                    <th width="60">No.</th>
                                                                    <th width="150">Tipo</th>
                                                                    <th width="200">Agente / Parámetro</th>
                                                                    <th width="600">Descripción partida</th>
                                                                    <th>Unidad de medida</th>
                                                                    <th>Precio unitario</th>
                                                                    <th>Descuento</th>
                                                                    <th>Acciones</th>
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

                            <!--STEP 3 CRONOGRAMA DE TRABAJO-->
                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab3">
                                <div class="multisteps-form__content">
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_INICIO_ACTIVIDAD" name="FECHA_INICIO_ACTIVIDAD" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>Fecha Fin *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_FIN_ACTIVIDAD" name="FECHA_FIN_ACTIVIDAD" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
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


<!-- Modal convenios -->
<div id="modal_convenio" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_convenio" id="form_convenio">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Convenio de ampliación de Monto y/o Plazo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="convenio_id" name="convenio_id" value="0">
                        </div>

                        <div class="col-6 mostrar" id="montomxn">
                            <div class="form-group">
                                <label>Monto MXN</label>
                                <input type="number" step="any" class="form-control" name="clienteconvenio_montomxn" id="clienteconvenio_montomxn">
                            </div>
                        </div>
                        <div class="col-6 mostrar" id="montousd">
                            <div class="form-group">
                                <label>Monto USD</label>
                                <input type="number" step="any" class="form-control" name="clienteconvenio_montousd" id="clienteconvenio_montousd">
                            </div>
                        </div>
                        <div class="col-6 mostrar" id="alcanze">
                            <div class="form-group">
                                <label>Alcance</label>
                                <input type="number" step="any" class="form-control" name="clienteconvenio_alcanze" id="clienteconvenio_alcanze">
                            </div>
                        </div>
                        <div class="col-6 mostrar" id="vigencia">
                            <div class="form-group">
                                <label>Vigencia</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="clienteconvenio_vigencia" name="clienteconvenio_vigencia">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_convenio">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal convenios -->


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
                            <!-- <input type="hidden" class="form-control" id="documento_cliente_id" name="cliente_id" value="0"> -->
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento *</label>
                                <input type="text" class="form-control" name="clienteDocumento_Nombre" id="clienteDocumento_Nombre" required>
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
                            <input type="hidden" class="form-control" id="clienteDocumento_Eliminado" name="clienteDocumento_Eliminado" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_documento">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal documentos -->


<!-- Modal anexos -->
<div id="modal_anexo" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_anexo" id="form_anexo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Anexos para informes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="CONTRATO_ID" name="CONTRATO_ID" value="0">
                            <input type="hidden" class="form-control" id="ID_CONTRATO_ANEXO" name="ID_CONTRATO_ANEXO" value="0">

                            <!-- <input type="hidden" class="form-control" id="documento_cliente_id" name="cliente_id" value="0"> -->
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del anexo *</label>
                                <input type="text" class="form-control" name="NOMBRE_ANEXO" id="NOMBRE_ANEXO" required>
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex justify-content-start">
                            <h4>Tipo de documento</h4>
                            <div class="form-check mx-4">
                                <input class="form-check-input" type="radio" name="TIPO" id="anexo_archivo" value="ARCHIVO">
                                <label class="form-check-label" for="anexo_archivo">
                                    <i class="fa fa-file-pdf-o fa-3x" aria-hidden="true"></i>
                                    Archivo
                                </label>
                            </div>
                            <div class="form-check mx-4">
                                <input class="form-check-input" type="radio" name="TIPO" id="anexo_img" value="IMAGEN">
                                <label class="form-check-label" for="anexo_img">
                                    <i class="fa fa-file-image-o fa-3x" aria-hidden="true"></i>
                                    Imagen
                                </label>
                            </div>
                            <div class="form-check mx-4">
                                <input class="form-check-input" type="radio" name="TIPO" id="anexo_acreditacion" value="ACREDITACION">
                                <label class="form-check-label" for="anexo_acreditacion">
                                    <i class="fa fa-file-text-o fa-3x" aria-hidden="true"></i>
                                    Acreditación
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_anexo">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal anexos -->

<!-- Modal partidas -->
<div id="modal_partida" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_partida" id="form_partida">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Partida para informes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="partida_id" name="partida_id" value="0">
                            <script type="text/javascript">
                                var catpruebas = <?php echo $catpruebas; ?>;
                            </script>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo partida*</label>
                                <select class="form-control" name="clientepartidas_tipo" id="clientepartidas_tipo" required onchange="activa_parametro(this.value);">
                                    <option value=""></option>
                                    <option value="1">Reconocimiento</option>
                                    <option value="2">Informe de resultados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Agente / Parámetro*</label>
                                <select class="form-control" name="catprueba_id" id="catprueba_id" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Unidad de medida*</label>
                                <input type="text" class="form-control" name="UNIDAD_MEDIDA" id="UNIDAD_MEDIDA" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Precio unitario*</label>
                                <input type="number" class="form-control" name="PRECIO_UNITARIO" id="PRECIO_UNITARIO" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción de la partida en el informe*</label>
                                <textarea class="form-control" style="text-align: center;" rows="4" name="clientepartidas_descripcion" id="clientepartidas_descripcion" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_partida">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal partidas -->

<!-- Modal contratos -->
<div id="modal_contrato" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_contrato" id="form_contrato">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Información del Servicios</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="CONTRATO_ID_PRINCIPAL" name="CONTRATO_ID" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de servicio *</label>
                                <select class="form-control" name="TIPO_SERVICIO" id="TIPO_SERVICIO" required>
                                    <option value=""></option>
                                    <option value="1">Contrato</option>
                                    <option value="2">O.S / O.C</option>
                                    <option value="3">Cotización aceptada</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Nombre del contacto *</label>
                                <input type="text" step="any" class="form-control" name="NOMBRE_CONTACTO" id="NOMBRE_CONTACTO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Cargo contacto *</label>
                                <input type="text" step="any" class="form-control" name="CARGO_CONTACTO" id="CARGO_CONTACTO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Correo contacto * </label>
                                <input type="text" step="any" class="form-control" name="CORREO_CONTACTO" id="CORREO_CONTACTO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Teléfono contacto (Ext.) * </label>
                                <input type="text" step="any" class="form-control" name="TELEFONO_CONTACTO" id="TELEFONO_CONTACTO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Celular contacto *</label>
                                <input type="text" step="any" class="form-control" name="CELULAR_CONTACTO" id="CELULAR_CONTACTO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Número de contrato o O.S/O.C </label>
                                <input type="text" step="any" class="form-control" name="NUMERO_CONTRATO" id="NUMERO_CONTRATO">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Objeto del servicio *</label>
                                <textarea class="form-control" rows="3" id="DESCRIPCION_CONTRATO" name="DESCRIPCION_CONTRATO" required=""></textarea>

                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label> Fecha de inicio *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_INICIO" name="FECHA_INICIO" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label> Fecha de fin *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_FIN" name="FECHA_FIN" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))

                        <div class="col-lg-2 col-sm-6 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="MONEDA_MONTO" id="MONEDA_MONTOMNX" value="MXN" checked>
                                <label class="form-check-label" for="MONEDA_MONTOMNX">
                                    Monto MXN
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="MONEDA_MONTO" id="MONEDA_MONTOUSD" value="USD">
                                <label class="form-check-label" for="MONEDA_MONTOUSD">
                                    Monto USD
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-6 mt-3">
                            <div class="form-group">
                                <input type="text" class="form-control input_numberformat" style="width: 100%;" id="MONTO" name="MONTO" onfocusout="formatear(this.id);" placeholder="Cantidad *" required>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente" id="boton_guardar_contrato">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal contratos -->


<!-- Modal documento de cierre -->
<div id="modal_documento_cierre" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_documento_cierre" id="form_documento_cierre">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos de cierre</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="documento_cierre_id" name="documento_cierre_id" value="0">
                            <!-- <input type="hidden" class="form-control" id="documento_cliente_id" name="cliente_id" value="0"> -->
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre del documento </label>
                                <input type="text" class="form-control" name="NOMBRE" id="NOMBRE">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Documento PDF </label>
                                <!-- <input type="file" id="input-file-now" class="dropify" required/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="campo_file_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="DOCUMENTO_CIERRE" id="documentoCierre">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
                                        Quitar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Justificación de cierre</label>
                                <input type="text" class="form-control" name="JUSTIFICACION_CIERRE" id="JUSTIFICACION_CIERRE">
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ELIMINADO" name="ELIMINADO" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_documento_cierre">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal documentos de cierre -->

<!-- Modal partidas para convenios -->
<div id="modal_partida_convenio" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_partida_convenio" id="form_partida_convenio">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Partida para informes (Convenios)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="partida_convenio_id" name="partida_id" value="0">
                            <script type="text/javascript">
                                var catpruebas = <?php echo $catpruebas; ?>;
                            </script>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo partida*</label>
                                <select class="form-control" name="clientepartidas_tipo" id="clientepartidasconvenio_tipo" required onchange=" activa_parametro_convenios(this.value);">
                                    <option value=""></option>
                                    <option value="1">Reconocimiento</option>
                                    <option value="2">Informe de resultados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Agente / Parámetro*</label>
                                <select class="form-control" name="catprueba_id" id="catprueba_id_convenio" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Unidad de medida*</label>
                                <input type="text" class="form-control" name="UNIDAD_MEDIDA" id="UNIDAD_MEDIDA_CONVENIO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Precio unitario*</label>
                                <input type="text" class="form-control" name="PRECIO_UNITARIO" id="PRECIO_UNITARIO_CONVENIO" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Descuento en %</label>
                                <input type="text" class="form-control" name="DESCUENTO" id="DESCUENTO">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción de la partida en el informe*</label>
                                <textarea class="form-control" style="text-align: center;" rows="4" name="clientepartidas_descripcion" id="clientepartidasconvenio_descripcion" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light boton_modulocliente contrato" id="boton_guardar_partida_convenio">Guardar <i class="fa fa-save"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.Modal partidas para convenios-->

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
@php
$rolUsuario = auth()->user()->roles->first() ? auth()->user()->roles->first()->rol_Nombre : null;
$empleado = auth()->user()->empleado;
$nombreCompleto = $empleado ? $empleado->empleado_nombre . ' ' . $empleado->empleado_apellidopaterno . ' ' . $empleado->empleado_apellidomaterno : '';
@endphp

<script>
    var rolUsuario = @json($rolUsuario);
    var Usuario = @json($nombreCompleto);
</script>

@endsection