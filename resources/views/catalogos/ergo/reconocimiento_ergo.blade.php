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





<!-- CONTENIDO RECONOCIMIENTO PSICOSOCIAL -->

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
    <div class="col-12 mt-4">
        <div class="card">
            <!-- MENU DE TABS -->
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
                <li class="nav-item">
                    <a class="nav-link link_menuprincipal" data-toggle="tab" href="#tab_3" id="tab_menu3" role="tab">
                        <span class="hidden-sm-up"><i class="ti-pencil-alt"></i></span>
                        <span class="hidden-xs-down">Informe</span>
                    </a>
                </li>
            </ul>
            <!-- CONTENIDO DE TABS -->
            <div class="tab-content">
                <!-- LISTA DE RECONOCIMEITNOS -->
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
                                    <th width="130">RFC</th>
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
                <!-- DATOS DEL RECONOCIMIENTO -->
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
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab3">
                                                <i class="fa fa-clone"></i><br>
                                                <span>Áreas</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab2">
                                                <i class="fa fa-user"></i><br>
                                                <span>Categorías</span>
                                            </div>
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab4">
                                                <i class="fa fa-address-card"></i><br>
                                                <span>Fichas técnicas</span>
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

                                                                            <div class="col-12 mt-3 mb-3">
                                                                                <input type="hidden" class="form-control" id="recsensorial_id" name="recsensorial_id" value="0">
                                                                                <input type="hidden" class="form-control" id="tipocliente" name="tipocliente" value="1">
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
                                                                            <input type="hidden" name="higiene" id="higiene">
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
                                                                                <h4 class="card-title">Plano instalación <br> con áreas *</h4>
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
                                                                    <div class="col-12 clienteblock" id="seccion_foto_mapaderiesgo">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: -4px; margin-left: 160px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar mapa de riesgo" id="boton_descargarmapaderiesgo"></i>
                                                                                <h4 class="card-title">Mapa de peligro <br> y riesgo ergonómico *</h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotomapaderiesgo" name="inputfotomapaderiesgo" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
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

                                            <!--STEP 3-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab3">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Área instalación</h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva área" id="boton_nueva_area" style="margin-left: auto;">
                                                                    Nueva <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Área instalación </h2>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="Tablarecoareasergo">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 100px !important;">No.</th>
                                                                            <th>Área</th>
                                                                            <th>Proceso del área </th>
                                                                            <th style="width: 80px!important;">Editar</th>
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
                                            <!--STEP 2-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab2">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Categoría personal </h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva categoría" id="boton_nueva_categoria" style="margin-left: auto;">
                                                                    Nueva <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Categoría personal </h2>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="Tablarecocategoriasergo">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>P.Trabajo</th>
                                                                            <th>Categoría</th>
                                                                            <th>Descripcion</th>
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


                                            <!--STEP 4-->
                                            <div class="multisteps-form__panel" data-animation="scaleIn" id="steps_contenido_tab4">
                                                <div class="multisteps-form__content">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))

                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Fichas técnicas</h2>
                                                                <button type="button" class="btn btn-secondary waves-effect waves-light   botonnuevo_modulorecsensorial" data-toggle="tooltip" title="Nueva ficha" id="boton_nueva_ficha" style="margin-left: auto;">
                                                                    Nueva <i class="fa fa-plus"></i>
                                                                </button>
                                                            </ol>
                                                            @else
                                                            <ol class="breadcrumb m-b-10">
                                                                <h2 style="color: #ffff; margin: 0;"><i class="fa fa-user"></i> Fichas técnicas </h2>
                                                            </ol>
                                                            @endif
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="Tablarecoareasergo">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th style="width: 80px!important;">Editar</th>
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
                                                    <form enctype="multipart/form-data" method="post" name="form_responsables" id="form_responsables">
                                                        <div class="row justify-content-center align-items-center">
                                                            <div class="col-12">
                                                                {!! csrf_field() !!}
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                <ol class="breadcrumb m-b-10 text-light">
                                                                    Responsables del informe de reconocimiento sensorial
                                                                </ol>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable Técnico del informe</label>
                                                                            <input type="text" class="form-control" id="NOMBRE_TECNICO" name="NOMBRE_TECNICO" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Nombre del responsable del Contrato/Proyecto </label>
                                                                            <input type="text" class="form-control" id="NOMBRE_CONTRATO" name="NOMBRE_CONTRATO" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable Técnico del informe</label>
                                                                            <select class="custom-select form-control" id="CARGO_TECNICO" name="CARGO_TECNICO" required>
                                                                                <option value=""></option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable del Contrato/Proyecto</label>
                                                                            <select class="custom-select form-control" id="CARGO_CONTRATO" name="CARGO_CONTRATO" required>
                                                                                <option value=""></option>

                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable Técnico del informe</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="TECNICO_DOC_IMG" name="TECNICO_DOC_IMG" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Documento del responsable del Contrato/Proyecto</label>
                                                                            <style type="text/css" media="screen">
                                                                                .dropify-wrapper {
                                                                                    height: 296px !important;
                                                                                    /*tamaño estatico del campo foto*/
                                                                                }
                                                                            </style>
                                                                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="CONTRATO_DOC_IMG" name="CONTRATO_DOC_IMG" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" required>
                                                                        </div>
                                                                    </div>





                                                                </div>
                                                            </div>


                                                            <div class="col-sm-8">
                                                                <div class="form-group" style="text-align: right;">
                                                                    <button type="submit" class="btn btn-danger botonguardar_modulorecsensorial w-100 p-3" id="boton_guardar_responsables">
                                                                        Guardar responsables <i class="fa fa-save"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============= /STEPS ============= -->
                    </div>
                </div> <!-- /FIN TAB 2 -->
                <div class="tab-pane p-20" id="tab_3" role="tabpanel">

                    <style type="text/css">
                        .reporte_iluminacion {
                            font-size: 14px !important;
                            line-height: 14px;
                        }



                        /*.list-group-item
	{
		padding: 6px 4px;
		font-family: Agency FB;
		line-height: 16px;
	}

	.list-group .submenu
	{
		padding: 10px 10px 10px 20px;
	}

	.list-group .subsubmenu
	{
		padding: 10px 10px 10px 45px;
	}*/



                        .list-group-item {
                            padding: 2px 1px;
                            font-family: Agency FB;
                            /*font-family: Calibri;*/
                            font-size: 0.55vw !important;
                            line-height: 1;
                        }

                        .list-group-item.active {
                            font-size: 1.2vw !important;
                        }

                        .list-group-item i {
                            color: #fc4b6c;
                        }

                        .list-group-item:hover {
                            font-size: 1.2vw !important;
                        }

                        .list-group .submenu {
                            padding: 2px 1px 2px 8px;
                        }

                        .list-group .subsubmenu {
                            padding: 2px 1px 2px 20px;
                        }

                        .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }


                        p.justificado {
                            text-align: justify !important;
                            margin: 0px !important;
                            padding: 0px !important;
                        }


                        div.informacion_estatica {
                            font-size: 14px;
                            line-height: 14px !important;
                            text-align: justify;
                        }

                        div.informacion_estatica .imagen_formula {
                            text-align: center;
                        }

                        div.informacion_estatica b {
                            font-size: 13px;
                            font-weight: bold;
                            color: #777777;
                        }

                        .tabla_info_centrado th {
                            background: #F9F9F9;
                            border: 1px #E5E5E5 solid !important;
                            padding: 2px !important;
                            text-align: center;
                            vertical-align: middle;
                        }

                        .tabla_info_centrado td {
                            border: 1px #E5E5E5 solid !important;
                            padding: 4px !important;
                            text-align: center;
                            vertical-align: middle;
                        }

                        .tabla_info_justificado th {
                            background: #F9F9F9;
                            border: 1px #E5E5E5 solid !important;
                            padding: 2px !important;
                            text-align: center;
                            vertical-align: middle;
                        }

                        .tabla_info_justificado td {
                            border: 1px #E5E5E5 solid !important;
                            padding: 4px !important;
                            text-align: justify;
                            vertical-align: middle;
                        }
                    </style>

                    <div class="row" class="reporte_iluminacion">
                        <div class="col-xlg-2 col-lg-3 col-md-5">
                            <div class="stickyside">
                                <div class="list-group" id="top-menu">
                                    <a href="#0" class="list-group-item active">Portada <i class="fa fa-times" id="menureporte_0"></i></a>
                                    <a href="#1" class="list-group-item">1.- Introducción <i class="fa fa-times" id="menureporte_1"></i></a>
                                    <a href="#2" class="list-group-item">2.- Definiciones <i class="fa fa-times" id="menureporte_2"></i></a>
                                    <a href="#3" class="list-group-item">3.- Objetivos</a>
                                    <a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa fa-times" id="menureporte_3_1"></i></a>
                                    <a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa fa-times" id="menureporte_3_2"></i></a>
                                    <a href="#4" class="list-group-item">4.- Metodología</a>
                                    <a href="#4_1" class="list-group-item submenu">4.1.- Reconocimiento de los agentes y factores <i class="fa fa-times" id="menureporte_4_1"></i></a>
                                    <a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación de los niveles de <i class="fa fa-times" id="menureporte_4_2"></i></a>
                                    <a href="#4_2_1" class="list-group-item subsubmenu">4.2.1.- Cálculo del índice de áreas <i class="fa fa-times" id="menureporte_4_2_1"></i></a>
                                    <a href="#4_2_2" class="list-group-item subsubmenu">4.2.2.- Evaluación del factor de reflexión <i class="fa fa-times" id="menureporte_4_2_2"></i></a>
                                    <a href="#5" class="list-group-item">5.- Resultados</a>
                                    <a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
                                    <a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
                                    <a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
                                    <a href="#5_4" class="list-group-item submenu">5.4.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_4"></i></a>
                                    <!-- <a href="#6" class="list-group-item">6.- Evaluación</a>
                                    <a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
                                    <a href="#6_2" class="list-group-item submenu">6.2.- Método empleado y criterio de selección <i class="fa fa-times" id="menureporte_6_2"></i></a>
                                    <a href="#6_2_1" class="list-group-item subsubmenu">6.2.1.- Índice de área <i class="fa fa-times" id="menureporte_6_2_1"></i></a>
                                    <a href="#6_2_2" class="list-group-item subsubmenu">6.2.2.- Puesto de trabajo <i class="fa fa-times" id="menureporte_6_2_2"></i></a>
                                    <a href="#7" class="list-group-item">7.- Resultados</a>
                                    <a href="#7_1" class="list-group-item submenu">7.1.- Resultados del nivel de iluminación <i class="fa fa-times" id="menureporte_7_1"></i></a>
                                    <a href="#7_2" class="list-group-item submenu">7.2.- Resultados del nivel de reflexión <i class="fa fa-times" id="menureporte_7_2"></i></a>
                                    <a href="#7_3" class="list-group-item submenu">7.3.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_3"></i></a>
                                    <a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
                                    <a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
                                    <a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
                                    <a href="#11" class="list-group-item">11.- Anexos</a>
                                    <a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
                                    <a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de luminarias y puntos de evaluación por área <i class="fa fa-times" id="menureporte_11_2"></i></a>
                                    <a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_3"></i></a>
                                    <a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Incertidumbre de la medición <i class="fa fa-times" id="menureporte_11_4"></i></a>
                                    <a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Copia de certificados o aviso de calibración del equipo <i class="fa fa-times" id="menureporte_11_5"></i></a>
                                    <a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Informe de resultados <i class="fa fa-times" id="menureporte_11_6"></i></a>
                                    <a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_7"></i></a>
                                    <a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_8"></i></a>
                                    <a href="#12" class="list-group-item submenu">12.- Elegir anexos 7 (STPS) y 8 (EMA) <i class="fa fa-times" id="menureporte_12"></i></a>
                                    <a href="#13" class="list-group-item submenu">Generar informe <i class="fa fa-download text-success" id="menureporte_13"></i></a> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-10 col-lg-9 col-md-7">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" style="padding: 0px!important;" id="0">Portadas</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_portada" id="form_reporte_portada">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                            </div>

                                            <div class="row w-100">
                                                <div class="col-5">
                                                    <div class="col-12">
                                                        <label> Imagen Portada Exterior * </label>
                                                        <div class="form-group">
                                                            <style type="text/css" media="screen">
                                                                .dropify-wrapper {
                                                                    height: 400px !important;
                                                                    /*tamaño estatico del campo foto*/
                                                                }
                                                            </style>
                                                            <input type="file" accept="image/jpeg,image/x-png" id="PORTADA" name="PORTADA" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="col-12">
                                                        <h3 class=" mt-1 mb-4">Selecciona hasta 5 opciones (Cuerpo del Encabezado en el Informe)</h3>
                                                    </div>

                                                    <div class="col-12 mb-4">
                                                        <div class="form-group">
                                                            <label> Nivel 1 </label>
                                                            <select class="custom-select form-control" style="width: 90%;" id="NIVEL1" name="NIVEL1">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4">
                                                        <div class="form-group">
                                                            <label> Nivel 2 </label>
                                                            <select class="custom-select form-control" style="width: 90%;" id="NIVEL2" name="NIVEL2">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4">
                                                        <div class="form-group">
                                                            <label> Nivel 3 </label>
                                                            <select class="custom-select form-control" style="width: 90%;" id="NIVEL3" name="NIVEL3">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4">
                                                        <div class="form-group">
                                                            <label> Nivel 4 </label>
                                                            <select class="custom-select form-control" style="width: 90%;" id="NIVEL4" name="NIVEL4">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4">
                                                        <div class="form-group">
                                                            <label> Nivel 5 </label>
                                                            <select class="custom-select form-control" style="width: 90%;" id="NIVEL5" name="NIVEL5">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <h3 class="mx-4 mt-5 mb-4">Seleccione las opciones que desee mostrar en la Portada Interna del Informe</h3>


                                            <div class="col-1 d-none">
                                                <div class="form-group">
                                                    <label class="demo-switch-title">Mostrar</label>
                                                    <div class="switch" style="margin-top: 6px;">
                                                        <label><input type="checkbox" id="reporteiluminacion_catsubdireccion_activo" name="reporteiluminacion_catsubdireccion_activo" checked><span class="lever switch-col-light-blue"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-11 d-none">
                                                <div class="form-group">
                                                    <label>Subdirección</label>
                                                    <select class="custom-select form-control" id="reporteiluminacion_catsubdireccion_id" name="reporteiluminacion_catsubdireccion_id" disabled>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-1 d-none">
                                                <div class="form-group">
                                                    <label class="demo-switch-title">Mostrar</label>
                                                    <div class="switch" style="margin-top: 6px;">
                                                        <label><input type="checkbox" id="reporteiluminacion_catgerencia_activo" name="reporteiluminacion_catgerencia_activo" checked><span class="lever switch-col-light-blue"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-11 d-none">
                                                <div class="form-group">
                                                    <label>Gerencia</label>
                                                    <select class="custom-select form-control" id="reporteiluminacion_catgerencia_id" name="reporteiluminacion_catgerencia_id" disabled>
                                                        <option value=""></option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-1 d-none">
                                                <div class="form-group">
                                                    <label class="demo-switch-title">Mostrar</label>
                                                    <div class="switch" style="margin-top: 6px;">
                                                        <label><input type="checkbox" id="reporteiluminacion_catactivo_activo" name="reporteiluminacion_catactivo_activo" checked><span class="lever switch-col-light-blue"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-11 d-none">
                                                <div class="form-group">
                                                    <label>Activo</label>
                                                    <select class="custom-select form-control" id="reporteiluminacion_catactivo_id" name="reporteiluminacion_catactivo_id" disabled>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 d-none">
                                                <div class="form-group">
                                                    <label>Instalación</label>
                                                    <input type="text" class="form-control" id="reporteiluminacion_instalacion" name="reporteiluminacion_instalacion" onchange="instalacion_nombre(this.value);" readonly>
                                                </div>
                                            </div>
                                            <div class="col-4 d-none"></div>
                                            <div class="col-1 d-none">
                                                <div class="form-group">
                                                    <label class="demo-switch-title">Mostrar</label>
                                                    <div class="switch" style="margin-top: 6px;">
                                                        <label><input type="checkbox" id="reporteiluminacion_catregion_activo" name="reporteiluminacion_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 d-none">
                                                <div class="form-group">
                                                    <label>Región</label>
                                                    <select class="custom-select form-control" id="reporteiluminacion_catregion_id" name="reporteiluminacion_catregion_id" disabled>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row w-100 mt-4">
                                                <div class="col-8">
                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 1 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA1" name="OPCION_PORTADA1">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 2 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA2" name="OPCION_PORTADA2">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 3 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA3" name="OPCION_PORTADA3">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 4 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA4" name="OPCION_PORTADA4">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 5 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA5" name="OPCION_PORTADA5">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label> Opción 6 </label>
                                                            <select class="custom-select form-control" style="width: 80%;" id="OPCION_PORTADA6" name="OPCION_PORTADA6">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="col-12 text-center mt-4">
                                                        <div class="form-group">
                                                            <label>Mes</label>
                                                            <select class="custom-select form-control" id="reporteiluminacion_mes" name="reporteiluminacion_mes">
                                                                <option value="" selected disabled></option>
                                                                <option value="Enero">Enero</option>
                                                                <option value="Febrero">Febrero</option>
                                                                <option value="Marzo">Marzo</option>
                                                                <option value="Abril">Abril</option>
                                                                <option value="Mayo">Mayo</option>
                                                                <option value="Junio">Junio</option>
                                                                <option value="Julio">Julio</option>
                                                                <option value="Agosto">Agosto</option>
                                                                <option value="Septiembre">Septiembre</option>
                                                                <option value="Octubre">Octubre</option>
                                                                <option value="Noviembre">Noviembre</option>
                                                                <option value="Diciembre">Diciembre</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 text-center mt-4 mb-4">
                                                        <label> <b>del</b></label>
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <div class="form-group">
                                                            <label>Año</label>
                                                            <select class="custom-select form-control" id="reporteiluminacion_fecha" name="reporteiluminacion_fecha">
                                                                <option value="" selected disabled></option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_portada">Guardar portadas <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="1">1.- Introducción</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_introduccion" id="form_reporte_introduccion">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporteiluminacion_introduccion" name="reporteiluminacion_introduccion" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_introduccion">Guardar introducción <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- ======== ELIMINAR DESPUES DE SUBIR AL SERVIDOR =============-->
                                    <div class="col-12 mt-4" style="text-align: center; display: none;">
                                        <button type="submit" class="btn btn-info waves-effect waves-light" id="btn_descargar_plantilla">Descargar plantilla principal <i class="fa fa-download"></i></button>
                                    </div>
                                    <!-- ======== ELIMINAR DESPUES DE SUBIR AL SERVIDOR =============-->

                                    <h4 class="card-title" id="2">2.- Definiciones</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva definición" id="boton_reporte_nuevadefinicion">
                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva definición
                                                </button>
                                            </ol>
                                            <form enctype="multipart/form-data" method="post" name="form_reporte_listadefiniciones" id="form_reporte_listadefiniciones">
                                                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_definiciones">
                                                    <thead>
                                                        <tr>
                                                            <th width="130">Concepto</th>
                                                            <th>Descripción / Fuente</th>
                                                            <th width="60">Editar</th>
                                                            <th width="60">Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Concepto</td>
                                                            <td class="justificado">Descipción y fuente</td>
                                                            <td><button type="button" class="btn btn-warning waves-effect btn-circle"><i class="fa fa-pencil fa-2x"></i></button></td>
                                                            <td><button type="button" class="btn btn-danger waves-effect btn-circle"><i class="fa fa-trash fa-2x"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                    <h4 class="card-title" id="3">3.- Objetivos</h4>
                                    <h4 class="card-title" id="3_1">3.1.- Objetivo general</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_objetivogeneral" id="form_reporte_objetivogeneral">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_objetivogeneral" name="reporteiluminacion_objetivogeneral" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivogeneral">Guardar objetivo general <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="3_2">3.2.- Objetivos específicos</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_objetivoespecifico" id="form_reporte_objetivoespecifico">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporteiluminacion_objetivoespecifico" name="reporteiluminacion_objetivoespecifico" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivoespecifico">Guardar objetivos específicos <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4">4.- Metodología</h4>
                                    <h4 class="card-title" id="4_1">4.1.- Reconocimiento de los agentes y factores</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_1" id="form_reporte_metodologia_4_1">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_1" name="reporteiluminacion_metodologia_4_1" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4_2">4.2.- Método de evaluación de los niveles de iluminación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2" id="form_reporte_metodologia_4_2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporteiluminacion_metodologia_4_2" name="reporteiluminacion_metodologia_4_2" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4_2_1">4.2.1.- Cálculo del índice de áreas</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_1" id="form_reporte_metodologia_4_2_1">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_1" name="reporteiluminacion_metodologia_4_2_1" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="informacion_estatica">

                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_1">Guardar metodología punto 4.2.1 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4_2_2">4.2.2.- Evaluación del factor de reflexión</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_2" id="form_reporte_metodologia_4_2_2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_2" name="reporteiluminacion_metodologia_4_2_2" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_2">Guardar metodología punto 4.2.2 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4_2_4">4.2.3.- Niveles de iluminación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_4" id="form_reporte_metodologia_4_2_4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_4" name="reporteiluminacion_metodologia_4_2_4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_4">Guardar metodología punto 4.2.3 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4_2_3">4.2.4.- Niveles máximos permisibles del factor de reflexión</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_3" id="form_reporte_metodologia_4_2_3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_3" name="reporteiluminacion_metodologia_4_2_3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_3">Guardar metodología punto 4.2.4 <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="5">5.- Reconocimiento</h4>
                                    <h4 class="card-title" id="5_1">5.1.- Ubicación de la instalación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_ubicacion" id="form_reporte_ubicacion">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        {!! csrf_field() !!}
                                                        <textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reporteiluminacion_ubicacioninstalacion" name="reporteiluminacion_ubicacioninstalacion" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
                                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteiluminacionubicacionfoto" name="reporteiluminacionubicacionfoto" onchange="redimencionar_mapaubicacion();" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="text-align: right;">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_ubicacion">Guardar ubicación <i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="5_2">5.2.- Descripción del proceso en la instalación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_reporte_procesoinstalacion" id="form_reporte_procesoinstalacion">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <div class="form-group">
                                                    <label>Descripción del proceso en la instalación</label>
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporteiluminacion_procesoinstalacion" name="reporteiluminacion_procesoinstalacion" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Descripción de la actividad principal de la instalación</label>
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="7" id="reporteiluminacion_actividadprincipal" name="reporteiluminacion_actividadprincipal" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_procesoinstalacion">Guardar proceso instalación <i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="5_3">5.3.- Población ocupacionalmente expuesta</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="justificado">En este apartado se muestra la actividad principal desarrollada en cada una de las áreas, involucrando al personal/categoría adscrito que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p>
                                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva categoría" id="boton_reporte_nuevacategoria">
                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva categoría
                                                </button>
                                            </ol>

                                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área" id="boton_reporte_nuevaarea">
                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva área
                                                </button>

                                                <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Importar" id="boton_reporte_iluminacion_importar_area">
                                                    <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Importar
                                                </button>


                                            </ol>
                                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_area">
                                                <thead>
                                                    <tr>
                                                        <th width="60">No.</th>
                                                        <th width="130">Instalación</th>
                                                        <th width="130">Área</th>
                                                        <th width="">Categoría</th>
                                                        <th width="60">Total</th>
                                                        <th width="90">Pts eval. IC</th>
                                                        <th width="90">Pts eval. PT</th>
                                                        <th width="60">Editar</th>
                                                        <th width="60">Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <h4 class="card-title" id="5_4">5.4.- Actividades del personal expuesto</h4>
                                    <div class="row">

                                    </div>
                                    <h4 class="card-title" id="5_5">5.5.- Descripción del área </h4>
                                    <div class="row">
                                        <div class="col-12">

                                        </div>
                                        <div class="col-12">
                                            <div class="informacion_estatica">
                                                <br><b>N/P:</b> No Proporcionado<br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ============================================================== -->
                    <!-- MODAL-CARGANDO -->
                    <!-- ============================================================== -->
                    <div id="modal_cargando" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-sm" style="max-width: 350px!important; margin-top: 250px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="mySmallModalLabel">Cargando</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body" style="text-align: center;">
                                    <i class='fa fa-spin fa-spinner fa-5x'></i>
                                    <br><br>Por favor espere <span id="segundos_espera">0</span>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-CARGANDO -->
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
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalvisor_reporteiluminacion">Cerrar</button>
                                    {{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- VISOR-MODAL -->
                    <!-- ============================================================== -->


                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-DEFINICION -->
                    <!-- ============================================================== -->
                    <style type="text/css" media="screen">
                        #modal_reporte_definicion>.modal-dialog {
                            min-width: 900px !important;
                        }

                        #modal_reporte_definicion .modal-body .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        #modal_reporte_definicion .modal-body .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }
                    </style>
                    <div id="modal_reporte_definicion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data" name="form_modal_definicion" id="form_modal_definicion">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Definición</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <input type="hidden" class="form-control" id="reportedefiniciones_id" name="reportedefiniciones_id" value="0">
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Concepto</label>
                                                    <input type="text" class="form-control" id="reportedefiniciones_concepto" name="reportedefiniciones_concepto" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Descripción</label>
                                                    <textarea class="form-control" rows="4" id="reportedefiniciones_descripcion" name="reportedefiniciones_descripcion" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Fuente</label>
                                                    <input type="text" class="form-control" id="reportedefiniciones_fuente" name="reportedefiniciones_fuente" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modal_definicion">Cerrar</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_definicion">Guardar <i class="fa fa-save"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-DEFINICION -->
                    <!-- ============================================================== -->


                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-CATEGORIA -->
                    <!-- ============================================================== -->
                    <style type="text/css" media="screen">
                        #modal_reporte_categoria>.modal-dialog {
                            min-width: 800px !important;
                        }

                        #modal_reporte_categoria .modal-body .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        #modal_reporte_categoria .modal-body .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }
                    </style>
                    <div id="modal_reporte_categoria" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data" name="form_modal_categoria" id="form_modal_categoria">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Categoría</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <input type="hidden" class="form-control" id="reportecategoria_id" name="reportecategoria_id" value="0">
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Categoría</label>
                                                    <input type="text" class="form-control" id="reportecategoria_nombre" name="reportecategoria_nombre" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Total personal</label>
                                                    <input type="number" class="form-control" id="reportecategoria_total" name="reportecategoria_total" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Horas jornada</label>
                                                    <input type="number" class="form-control" id="reportecategoria_horas" name="reportecategoria_horas" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_categoria">Guardar <i class="fa fa-save"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-CATEGORIA -->
                    <!-- ============================================================== -->


                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-ÁREA -->
                    <!-- ============================================================== -->
                    <style type="text/css" media="screen">
                        #modal_reporte_area>.modal-dialog {
                            min-width: 90% !important;
                        }

                        #modal_reporte_area .modal-body .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        #modal_reporte_area .modal-body .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }
                    </style>
                    <div id="modal_reporte_area" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data" name="form_reporte_area" id="form_reporte_area">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Área</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <input type="hidden" class="form-control" id="reportearea_id" name="reportearea_id" value="0">
                                            </div>
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Datos Generales
                                                </ol>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>Instalación</label>
                                                    <input type="text" class="form-control" id="reportearea_instalacion" name="reportearea_instalacion" required>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>Nombre del área</label>
                                                    <input type="text" class="form-control" id="reportearea_nombre" name="reportearea_nombre" required>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>% de operacion en el área</label>
                                                    <input type="number" min="0" max="100" class="form-control" id="reporteiluminacionarea_porcientooperacion" name="reporteiluminacionarea_porcientooperacion" required>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label>No. orden del área</label>
                                                    <input type="number" class="form-control" id="reportearea_orden" name="reportearea_orden" required>
                                                </div>
                                            </div>
                                            <div class="col-12 p-2 text-center">
                                                <label class="text-danger mr-4 d-block" style="font-size: 18px;" data-toggle="tooltip" title="" data-original-title="Marque la casilla de NO si el área no fue evaluada en el reconocimiento">¿ Área evaluada en el reconocimiento ?</label>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-check mx-4">
                                                        <input class="form-check-input" type="radio" name="aplica_iluminacion" id="aplica_iluminacion_si" value="1" required="required" checked>
                                                        <label class="form-check-label" for="aplica_iluminacion_si">
                                                            Si
                                                        </label>
                                                    </div>
                                                    <div class="form-check mx-4">
                                                        <input class="form-check-input" type="radio" name="aplica_iluminacion" id="aplica_iluminacion_no" value="0" required="required">
                                                        <label class="form-check-label" for="aplica_iluminacion_no">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Descripción de las instalaciones
                                                </ol>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label><b>x</b>&nbsp;Largo del área (m) *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_largo" name="reportearea_largo" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label><b>y</b>&nbsp;Ancho del área (m) *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_ancho" name="reportearea_ancho" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label><b>h</b>&nbsp;Alto del área (m) *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_alto" name="reportearea_alto" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Puntos evaluados por IC *</label>
                                                    <input type="number" class="form-control infoAdicionalArea" id="reportearea_puntos_ic" name="reportearea_puntos_ic" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Puntos evaluados por PT *</label>
                                                    <input type="number" class="form-control infoAdicionalArea" id="reportearea_puntos_pt" name="reportearea_puntos_pt" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Criterio *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_criterio" name="reportearea_criterio" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Color de techo</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_colortecho" name="reportearea_colortecho">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Color de paredes</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_paredes" name="reportearea_paredes">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Color de piso</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_colorpiso" name="reportearea_colorpiso">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Superficie techo</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_superficietecho" name="reportearea_superficietecho" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Superficie paredes</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_superficieparedes" name="reportearea_superficieparedes" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Superficie piso</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_superficiepiso" name="reportearea_superficiepiso" required>
                                                </div>
                                            </div>
                                            {{-- --}}
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Descripción de las lámparas
                                                </ol>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Tipo de lámparas *</label>
                                                    <select class="custom-select form-control infoAdicionalArea" id="reportearea_sistemailuminacion" name="reportearea_sistemailuminacion" required>
                                                        <option value=""></option>
                                                        <option value="NA">No aplica</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Potencia de las lámparas *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_potenciaslamparas" name="reportearea_potenciaslamparas" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>N° de lámparas *</label>
                                                    <input type="number	" class="form-control infoAdicionalArea" id="reportearea_numlamparas" name="reportearea_numlamparas" required>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>(h) Altura (m) *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_alturalamparas" name="reportearea_alturalamparas" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Programa de mantenimiento *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_programamantenimiento" name="reportearea_programamantenimiento" required>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Tipo de iluminación</label>
                                                    <select class="custom-select form-control infoAdicionalArea" id="reportearea_tipoiluminacion" name="reportearea_tipoiluminacion" required>
                                                        <option value=""></option>
                                                        <option value="NA">No aplica</option>
                                                        <option value="Natural">Natural</option>
                                                        <option value="Artificial">Artificial</option>
                                                        <option value="Natural y artificial">Natural y artificial</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Descripción de trabajo que requiere iluminación localizada *</label>
                                                    <input type="text" class="form-control infoAdicionalArea" id="reportearea_descripcionilimunacion" name="reportearea_descripcionilimunacion" required>
                                                </div>
                                            </div>


                                            {{-- <div class="col-3">
							<div class="form-group">
								<label>Influencia de luz natural</label>
								<select class="custom-select form-control" id="reportearea_luznatural" name="reportearea_luznatural" required>
									<option value=""></option>
									<option value="NA">No aplica</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Iluminación localizada</label>
								<select class="custom-select form-control" id="reportearea_iluminacionlocalizada" name="reportearea_iluminacionlocalizada" required>
									<option value=""></option>
									<option value="NA">No aplica</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
						</div> --}}




                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Categorías en el área
                                                </ol>
                                                <div style="margin: -25px 0px 0px 0px!important; padding: 0px!important;">
                                                    <table class="table-hover tabla_info_centrado" width="100%" id="tabla_areacategorias">
                                                        <thead>
                                                            <tr>
                                                                <th width="60">Activo</th>
                                                                <th width="180">Categoría</th>
                                                                <th width="80">Total</th>
                                                                <th width="80">GEH</th>
                                                                <th width="180">Actividades</th>
                                                                <th width="80">Niveles Mínimos de Iluminación </th>
                                                                <th width="180">Tarea visual</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_area">Guardar <i class="fa fa-save"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-ÁREA -->
                    <!-- ============================================================== -->



                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-ÁREA -->
                    <!-- ============================================================== -->
                    <style type="text/css" media="screen">
                        #modal_reporte_iluminacionpunto>.modal-dialog {
                            min-width: 90% !important;
                        }

                        #modal_reporte_iluminacionpunto .modal-body .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        #modal_reporte_iluminacionpunto .modal-body .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }
                    </style>
                    <div id="modal_reporte_iluminacionpunto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data" name="form_reporte_iluminacionpunto" id="form_reporte_iluminacionpunto">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Área</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <input type="hidden" class="form-control" id="reporteiluminacionpunto_id" name="reporteiluminacionpunto_id" value="0">
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>No. Punto</label>
                                                    <input type="number" class="form-control" id="reporteiluminacionpuntos_nopunto" name="reporteiluminacionpuntos_nopunto" required>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="form-group">
                                                    <label>Área</label>
                                                    <select class="custom-select form-control" id="reporteiluminacionpuntos_area_id" name="reporteiluminacionpuntos_area_id" onchange="mostrar_categoriasarea(this.value, 0);" required>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="form-group">
                                                    <label>Categoría</label>
                                                    <select class="custom-select form-control" id="reporteiluminacionpuntos_categoria_id" name="reporteiluminacionpuntos_categoria_id" required>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>No. de POE</label>
                                                    <input type="number" class="form-control" id="reporteiluminacionpuntos_nopoe" name="reporteiluminacionpuntos_nopoe" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Nombre</label>
                                                    <input type="text" class="form-control" id="reporteiluminacionpuntos_nombre" name="reporteiluminacionpuntos_nombre" required>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label>Ficha</label>
                                                    <input type="text" class="form-control" id="reporteiluminacionpuntos_ficha" name="reporteiluminacionpuntos_ficha" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Ubicación</label>
                                                    <input type="text" class="form-control" id="reporteiluminacionpuntos_concepto" name="reporteiluminacionpuntos_concepto" required>

                                                    <!-- <select class="custom-select form-control" id="reporteiluminacionpuntos_concepto" name="reporteiluminacionpuntos_concepto" required>
									<option value=""></option>
									<option value="Índice de Área (IC)">Índice de Área (IC)</option>
									<option value="Puesto de Trabajo">Puesto de Trabajo</option>
								</select> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Fecha y hora de medición
                                                </ol>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Fecha evaluación</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="reporteiluminacionpuntos_fechaeval" name="reporteiluminacionpuntos_fechaeval" required>
                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #0d47a1;">Horario periodo 1</label>
                                                    {{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario1" name="reporteiluminacionpuntos_horario1" required> --}}
                                                    <input type="time" class="form-control" id="reporteiluminacionpuntos_horario1" name="reporteiluminacionpuntos_horario1" required>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #78281F;">Horario periodo 2</label>
                                                    {{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario2" name="reporteiluminacionpuntos_horario2" required> --}}
                                                    <input type="time" class="form-control" id="reporteiluminacionpuntos_horario2" name="reporteiluminacionpuntos_horario2">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #00695c;">Horario periodo 3</label>
                                                    {{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario3" name="reporteiluminacionpuntos_horario3" required> --}}
                                                    <input type="time" class="form-control" id="reporteiluminacionpuntos_horario3" name="reporteiluminacionpuntos_horario3">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Resultados
                                                </ol>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Nivel </label>
                                                    <input type="number" step="any" class="form-control limite_lux" id="reporteiluminacionpuntos_lux" name="reporteiluminacionpuntos_lux" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div style="position: absolute; margin-top: 28px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 50px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 24px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed1menor" name="reporteiluminacionpuntos_luxmed1menor" />
                                                        <label for="reporteiluminacionpuntos_luxmed1menor"><b>&nbsp;</b></label>
                                                    </div>
                                                    <div style="position: absolute; margin-top: 46px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed1mayor" name="reporteiluminacionpuntos_luxmed1mayor" />
                                                        <label for="reporteiluminacionpuntos_luxmed1mayor"><b>&nbsp;</b></label>
                                                    </div>

                                                    <label style="color: #0d47a1;">Periodo 1 NI (Lux)</label>
                                                    <div style="width: 100%; padding-left: 45px;">
                                                        <input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed1" name="reporteiluminacionpuntos_luxmed1" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div style="position: absolute; margin-top: 28px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 50px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 24px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed2menor" name="reporteiluminacionpuntos_luxmed2menor" />
                                                        <label for="reporteiluminacionpuntos_luxmed2menor"><b>&nbsp;</b></label>
                                                    </div>
                                                    <div style="position: absolute; margin-top: 46px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed2mayor" name="reporteiluminacionpuntos_luxmed2mayor" />
                                                        <label for="reporteiluminacionpuntos_luxmed2mayor"><b>&nbsp;</b></label>
                                                    </div>

                                                    <label style="color: #78281F;">Periodo 2 NI (Lux)</label>
                                                    <div style="width: 100%; padding-left: 45px;">
                                                        <input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed2" name="reporteiluminacionpuntos_luxmed2" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div style="position: absolute; margin-top: 28px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 50px;">
                                                        <span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
                                                    </div>
                                                    <div style="position: absolute; margin-top: 24px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed3menor" name="reporteiluminacionpuntos_luxmed3menor" />
                                                        <label for="reporteiluminacionpuntos_luxmed3menor"><b>&nbsp;</b></label>
                                                    </div>
                                                    <div style="position: absolute; margin-top: 46px; margin-left: 20px;">
                                                        <input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed3mayor" name="reporteiluminacionpuntos_luxmed3mayor" />
                                                        <label for="reporteiluminacionpuntos_luxmed3mayor"><b>&nbsp;</b></label>
                                                    </div>

                                                    <label style="color: #00695c;">Periodo 3 NI (Lux)</label>
                                                    <div style="width: 100%; padding-left: 45px;">
                                                        <input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed3" name="reporteiluminacionpuntos_luxmed3" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_lux">
                                                {{-- <b class="text-success"><i class="fa fa-check"></i> Dentro de norma</b> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
                                                    Resultados del nivel de reflexión
                                                </ol>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Factor de reflexión en paredes (P)</label>
                                                    <input type="number" step="any" class="form-control limite_frp" id="reporteiluminacionpuntos_frp" name="reporteiluminacionpuntos_frp" value="60" readonly>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #0d47a1;">Periodo 1 FR (P) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed1" name="reporteiluminacionpuntos_frpmed1" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #78281F;">Periodo 2 FR (P) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed2" name="reporteiluminacionpuntos_frpmed2" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #00695c;">Periodo 3 FR (P) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed3" name="reporteiluminacionpuntos_frpmed3" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
                                                </div>
                                            </div>
                                            <div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_frp">
                                                {{-- <b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b> --}}
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Factor de reflexión en plano trabajo (PT)</label>
                                                    <input type="number" step="any" class="form-control limite_frpt" id="reporteiluminacionpuntos_frpt" name="reporteiluminacionpuntos_frpt" value="50" readonly>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #0d47a1;">Periodo 1 FR (PT) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed1" name="reporteiluminacionpuntos_frptmed1" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #78281F;">Periodo 2 FR (PT) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed2" name="reporteiluminacionpuntos_frptmed2" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label style="color: #00695c;">Periodo 3 FR (PT) (%)</label>
                                                    <input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed3" name="reporteiluminacionpuntos_frptmed3" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
                                                </div>
                                            </div>
                                            <div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_frpt">
                                                {{-- <b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_iluminacionpunto">Guardar <i class="fa fa-save"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-ÁREA -->
                    <!-- ============================================================== -->


                    <!-- ============================================================== -->
                    <!-- MODAL-IMPORTAR-PUNTOS -->
                    <!-- ============================================================== -->
                    <!-- Modal Excel equipos -->
                    <div id="modal_excel_puntos" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form enctype="multipart/form-data" method="post" name="formExcelPuntos" id="formExcelPuntos">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Cargar puntos de iluminación por medio de un Excel</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            {!! csrf_field() !!}
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label> Documento Excel *</label>
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_puntos">
                                                            <i class="fa fa-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-secondary btn-file">
                                                            <span class="fileinput-new">Seleccione</span>
                                                            <span class="fileinput-exists">Cambiar</span>
                                                            <input type="file" accept=".xls,.xlsx" name="excelPuntos" id="excelPuntos" required>
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-2" id="alertaVerificacion2" style="display:none">
                                            <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el archivo Excel contenga fechas en los formatos válidos: '2024-01-01', '01-01-2024', '2024/01/01', '01/01/2024' (no se admiten fechas con texto) y que la hora de medición este en formato de 24Hrs. </p>
                                        </div>
                                        <div class="row mt-3" id="divCargaPuntos" style="display: none;">

                                            <div class="col-12 text-center">
                                                <h2>Cargando lista de puntos espere un momento...</h2>
                                            </div>
                                            <div class="col-12 text-center">
                                                <i class='fa fa-spin fa-spinner fa-5x'></i>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

                                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarExcelPuntos">
                                            Cargar puntos <i class="fa fa-upload" aria-hidden="true"></i>
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- ============================================================== -->
                    <!-- MODAL-IMPORTAR-PUNTOS -->
                    <!-- ============================================================== -->


                    <!-- ============================================================== -->
                    <!-- MODAL-IMPORTAR-AREAS -->
                    <!-- ============================================================== -->
                    <!-- Modal Excel áreas -->

                    <div id="modal_excel_areas" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form enctype="multipart/form-data" method="post" name="formExcelArea" id="formExcelArea">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title">Cargar áreas por medio de un Excel</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            {!! csrf_field() !!}
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label> Documento Excel *</label>
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_areas">
                                                            <i class="fa fa-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                        <span class="input-group-addon btn btn-secondary btn-file">
                                                            <span class="fileinput-new">Seleccione</span>
                                                            <span class="fileinput-exists">Cambiar</span>
                                                            <input type="file" accept=".xls,.xlsx" name="excelArea" id="excelArea" required>
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-2" id="alertaVerificacion1" style="display:none">
                                            <p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el archivo Excel contenga los formatos válidos </p>
                                        </div>
                                        <div class="row mt-3" id="divCargaArea" style="display: none;">

                                            <div class="col-12 text-center">
                                                <h2>Cargando lista de puntos espere un momento...</h2>
                                            </div>
                                            <div class="col-12 text-center">
                                                <i class='fa fa-spin fa-spinner fa-5x'></i>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

                                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarExcelArea">
                                            Cargar puntos <i class="fa fa-upload" aria-hidden="true"></i>
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- ============================================================== -->
                    <!-- MODAL-IMPORTAR-AREAS -->
                    <!-- ============================================================== -->

                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-CANCELACION OBSERVACION -->
                    <!-- ============================================================== -->
                    <style type="text/css" media="screen">
                        #modal_reporte_cancelacionobservacion>.modal-dialog {
                            min-width: 800px !important;
                        }

                        #modal_reporte_cancelacionobservacion .modal-body .form-group {
                            margin: 0px 0px 12px 0px !important;
                            padding: 0px !important;
                        }

                        #modal_reporte_cancelacionobservacion .modal-body .form-group label {
                            margin: 0px !important;
                            padding: 0px 0px 3px 0px !important;
                        }
                    </style>
                    <div id="modal_reporte_cancelacionobservacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data" name="form_modal_cancelacionobservacion" id="form_modal_cancelacionobservacion">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Informe revisión</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" class="form-control" id="reporterevisiones_id" name="reporterevisiones_id" value="0">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Observacion de cancelación</label>
                                                    <textarea class="form-control" rows="6" id="reporte_canceladoobservacion" name="reporte_canceladoobservacion" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncancelar_modal_cancelacionobservacion">Cerrar</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="botonguardar_modal_cancelacionobservacion">Guardar observación y cancelar revisión <i class="fa fa-save"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- MODAL-REPORTE-CANCELACION OBSERVACION -->
                    <!-- ============================================================== -->



                </div> <!-- /FIN TAB 3 -->

            </div>
        </div>
    </div>
</div>




<!-- ============================================================== -->
<!-- MODALES CATEGORIA  -->
<!-- ============================================================== -->

<div id="modal_categoria" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_categoria" id="form_categoria">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Nueva categoría</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">

                        <div class="col-4">
                            <div class="form-group">
                                <label> Departamento *</label>
                                <select class="custom-select form-control" id="CAT_DEPARTAMENTO" name="CAT_DEPARTAMENTO" required>
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
                                <input type="text" class="form-control" name="NOMBRE_CATEGORIA_ERGO" id="NOMBRE_CATEGORIA_ERGO" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label> Tipo puesto*</label>
                                <select class="custom-select form-control" id="CAT_TIPOPUESTO" name="CAT_TIPOPUESTO" required>
                                    <option value=""></option>
                                    @foreach($catmovilfijo as $dato)
                                    <option value="{{$dato->id}}">{{$dato->catmovilfijo_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>P.Trabajo*</label>
                                <input type="text" class="form-control" name="PT_CATEGORIA" id="PT_CATEGORIA" required readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripcion categoría*</label>
                                <textarea class="form-control" name="DESCRIPCION_CATEGORIA_ERGO" id="DESCRIPCION_CATEGORIA_ERGO" rows="4" required></textarea>
                            </div>
                        </div>

                        <div class="row listadodeturno m-2"></div>


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


<!-- ============================================================== -->
<!-- MODALES AREA  -->
<!-- ============================================================== -->

<div id="modal_area" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_area" id="form_area">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Nueva área</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">


                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre área *</label>
                                <input type="text" class="form-control" name="NOMBRE_AREA_ERGO" id="NOMBRE_AREA_ERGO" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Proceso del área *</label>
                                <textarea class="form-control" name="DESCRIPCION_AREA_ERGO" id="DESCRIPCION_AREA_ERGO" rows="4" required></textarea>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <div>
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
<!-- MODALES FICHAS TECNICAS  -->
<!-- ============================================================== -->

<div id="modal_fichas" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 90%!important;">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_fichas" id="form_fichas">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Nueva fichas</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">




                        <!-- CATEGORÍA -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Categoría *</label>
                                <select class="form-control" id="CATEGORIA_ID_FICHA" name="CATEGORIA_ID_FICHA" required>
                                    <option value="">Selecciona un tipo de valor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label> Departamento *</label>
                                <select class="custom-select form-control" id="CAT_DEPARTAMENTO_FICHA" name="CAT_DEPARTAMENTO_FICHA" required disabled>
                                    <option value=""></option>
                                    @foreach($catdepartamento as $dato)
                                    <option value="{{$dato->id}}">{{$dato->catdepartamento_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- NOMBRE -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nombre del empleado *</label>
                                <input type="text" class="form-control" name="NOMBRE_EMPLEADO_FICHA" id="NOMBRE_EMPLEADO_FICHA" required>
                            </div>
                        </div>

                        <!-- FECHA EVALUACIÓN -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fecha de evaluación *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_EVALUACION" name="FECHA_EVALUACION" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>

                        <!-- SEXO -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Sexo *</label>
                                <select class="form-control" name="SEXO" id="SEXO" required>
                                    <option value="">Seleccionar</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fecha de nacimiento *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_NACIMIENTO" name="FECHA_NACIMIENTO" required>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>

                        <!-- PESO -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Peso (kg) *</label>
                                <input type="number" class="form-control" name="PESO" id="PESO" step="0.1" required>
                            </div>
                        </div>

                        <!-- TALLA -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Talla (cm) *</label>
                                <input type="number" class="form-control" name="TALLA" id="TALLA" required>
                            </div>
                        </div>

                        <!-- ANTIGÜEDAD -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>¿Cuánto tiempo lleva realizando el mismo tipo de trabajo?</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" class="form-control" placeholder="Años" name="ANTIGUEDAD_ANIOS" id="ANTIGUEDAD_ANIOS">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" placeholder="Meses" name="ANTIGUEDAD_MESES" id="ANTIGUEDAD_MESES">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- HORAS SEMANA -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>En promedio, ¿cuántas horas a la semana trabaja?</label>
                                <input type="number" class="form-control" placeholder="Horas" name="HORAS_SEMANA" id="HORAS_SEMANA">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>En promedio, ¿cuántas horas a la semana trabaja?</label>
                                <input type="number" class="form-control" placeholder="Horas" name="HORAS_SEMANA" id="HORAS_SEMANA">
                            </div>
                        </div>
                        <br><br>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-danger" onclick="agregarActividad()">
                                + Agregar Actividad
                            </button>
                        </div>

                        <div id="contenedorActividades" class="row mt-2"></div>


                        <br><br>

                        <style>
                            .actividad-card {
                                border-radius: 10px;
                                border: 1px solid #dee2e6;
                                background: #fff;
                            }


                            .tarea-item {
                                background: #f8f9fa;
                                border-radius: 6px;
                            }

                            #contenedorActividades {
                                max-height: 400px;
                                overflow-y: auto;
                            }

                            .custom-container-left {
                                width: 100%;
                                max-width: 100%;
                                /* evita límite */
                                padding-left: !important;
                                padding-right: !important;
                            }

                            .no-padding {
                                padding-left: !important;
                                padding-right: !important;
                            }



                            .custom-card-header {
                                width: 100%;
                                text-align: left;
                            }

                            .custom-card-nom .card-body {
                                padding: 20px;
                            }

                            .custom-card-nom .row {
                                justify-content: flex-start;
                            }
                        </style>

                        <div class="container-fluid custom-container-left no-padding">
                            <div class="card mb-3">


                                <div class="card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido1')">
                                    <div class="text-left w-100">
                                        <b>1.NOM-036-1-STPS-2018</b><br>
                                    </div>
                                </div>



                                <div id="contenido1" style="display:block;">

                                    <div class="row m-2">


                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>
                                                    1. Durante su jornada laboral, ¿levanta, baja, manipula objetos o materiales con un peso mayor a 3 Kg?
                                                </label>
                                                <select class="form-control" name="P1_CARGA_MAYOR_3KG" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="SI">Sí</option>
                                                    <option value="NO">No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>
                                                    2. ¿Con qué frecuencia realiza actividades que involucren el manejo manual de cargas (más de una vez al día)?
                                                </label>
                                                <select class="form-control" name="P2_FRECUENCIA_CARGA" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="SI">Sí</option>
                                                    <option value="NO">No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>
                                                    3. ¿Tiene que levantar, bajar, transportar, empujar, jalar y/o estibar objetos o materiales como parte de su trabajo?
                                                </label>
                                                <select class="form-control" name="P3_MANIPULACION_CARGA" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="SI">Sí</option>
                                                    <option value="NO">No</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card mt-2" id="TEXTO_MANIPULACION" style="display: block;">
                                    <div class="card-header header-verde-res d-flex align-items-center">
                                        <i class="fa fa-info mr-2" aria-hidden="true"></i>
                                        <div class="text-center w-100">
                                            <b>Manipulación manual de cargas</b>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        Cualquier operación de transporte o sujeción de una carga mayor a 3 kg por parte de uno o varios trabajadores, como el levantamiento, la colocación, el empuje, la tracción o el desplazamiento, que por sus características ergonómicas inadecuadas entrañe riesgo, en particular dorsolumbares, para los trabajadores.
                                    </div>
                                </div>

                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido2')" id="LEVANTAMIENTO_CARGA" style="display:block !important;">
                                    <div class="text-center">
                                        <b>2.Levantamiento de cargas</b><br>
                                    </div>
                                </div>

                                <div id="contenido2" style="display:none;">
                                    <div class="card-body">

                                        <div class="container-fluid">
                                            <div id="ficha_1_1"></div>
                                            <div id="ficha_1_4"></div>
                                            <div id="ficha_1_3"></div>
                                        </div>

                                        <br><br>
                                    </div>
                                </div>


                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido3')" id="TRANSPORTE_CARGAS" style="display:block !important;">
                                    <div class="text-center">
                                        <b>3.Transporte de cargas</b><br>
                                    </div>
                                </div>


                                <div id="contenido3" style="display:none;">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div id="ficha_1_2"></div>
                                            <div id="ficha_1_5"></div>
                                        </div>

                                        <br><br>
                                    </div>
                                </div>


                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido4')" id="EMPUJE_TRACCION" style="display:block !important;">
                                    <div class="text-center">
                                        <b>4.Empuje y tracción de cargas</b><br>
                                    </div>
                                </div>


                                <div id="contenido4" style="display:none;">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div id="ficha_2_1"></div>
                                            <div id="ficha_2_3"></div>
                                            <div id="ficha_2_2"></div>
                                        </div>
                                        <br><br>
                                    </div>
                                </div>

                                <div class="card mt-5">
                                    <div class="card-header header-verde-res d-flex align-items-center">
                                        <i class="fa fa-info mr-2" aria-hidden="true"></i>
                                        <div class="text-center w-100">
                                            <b>Movimiento repetitivo</b>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        Tarea caracterizada por tener un ciclo de trabajo que se repite. Está caracterizada por la presencia de ciclos con acciones técnicas que deben ser realizadas por las extremidades superiores.
                                    </div>
                                </div>

                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido5')" style="display:block !important;">
                                    <div class="text-center">
                                        <b>5.Movimientos repetitivos de la extremidad superior</b><br>
                                    </div>
                                </div>


                                <div id="contenido5" style="display:none;">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div id="ficha_3_1"></div>
                                            <div id="ficha_3_2"></div>
                                        </div>
                                        <br><br>
                                    </div>
                                </div>

                                <div class="card mt-5">
                                    <div class="card-header header-verde-res d-flex align-items-center">
                                        <i class="fa fa-info mr-2" aria-hidden="true"></i>
                                        <div class="text-center w-100">
                                            <b>Postura estática</b>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        Posición que se realiza con una contracción muscular prolongada sin producir movimiento durante por lo menos 4 segundos de manera consecutiva.
                                    </div>
                                </div>

                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido6')" style="display:block !important;">
                                    <div class="text-center">
                                        <b>6.Posturas estáticas forzadas</b><br>
                                    </div>
                                </div>


                                <div id="contenido6" style="display:none;">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div id="ficha_4_1"></div>
                                        </div>
                                        <br><br>
                                    </div>
                                </div>

                                <div class="card mt-5">
                                    <div class="card-header header-verde-res d-flex align-items-center">
                                        <i class="fa fa-info mr-2" aria-hidden="true"></i>
                                        <div class="text-center w-100">
                                            <b>Postura dinámica</b>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        Posición corporal que se realiza con una contracción de diferentes grupos musculares y con cambios en los movimientos de las articulaciones.
                                    </div>
                                </div>

                                <div class="mt-2 card-header header-res d-flex justify-content-between align-items-center cursor-pointer"
                                    onclick="toggleSeccion('contenido7')" style="display:block !important;">
                                    <div class="text-center">
                                        <b>7.Posturas dinámicas forzadas</b><br>
                                    </div>
                                </div>


                                <div id="contenido7" style="display:none;">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div id="ficha_4_2"></div>
                                        </div>
                                        <br><br>
                                    </div>
                                </div>

                            </div>
                        </div>






                        <style>
                            #ficha_1_1,
                            #ficha_1_2,
                            #ficha_1_3,
                            #ficha_2_1,
                            #ficha_2_2,
                            #ficha_3_1,
                            #ficha_4_1,
                            #ficha_4_2 {
                                width: 100%;
                            }

                            /* .card {
                                width: 100%;
                            } */

                            .table td.texto-pregunta {
                                font-size: 17px !important;
                                font-weight: 500;
                                line-height: 1.5;
                            }

                            .ficha {
                                border: 1px solid #000;
                                font-family: Arial, sans-serif;
                            }

                            .ficha-header {
                                background: #a8d5a2;
                                padding: 10px;
                                font-weight: bold;
                                color: #000;
                                /* negro */
                            }


                            .ficha table {
                                width: 100%;
                                border-collapse: collapse;
                            }

                            .ficha td {
                                border: 1px solid #000;
                                padding: 10px;
                                color: #000;
                            }

                            .col-letra {
                                width: 40px;
                                text-align: center;
                                font-weight: bold;
                            }

                            .col-radio {
                                width: 80px;
                                text-align: center;
                            }


                            .header-verde {
                                background-color: #a8d5a2 !important;
                                color: #000 !important;
                            }

                            .header-res {
                                background-color: #007DBA !important;
                                color: #000 !important;
                            }

                            .header-verde-res {
                                background-color: #A4D65E !important;
                                color: #000 !important;

                            }

                            .header-azul {
                                background-color: #b7c7d6 !important;
                                color: #000 !important;
                            }

                            /* Rojo (Zona roja) */
                            .header-rojo {
                                background-color: #f28b82 !important;
                                color: #000 !important;
                            }

                            /* .card {
                                border-radius: 6px;
                            }

                            .card-header {
                                font-weight: bold;
                            } */

                            /* .card-body {
                                padding: 0;
                            } */

                            .table td {
                                vertical-align: middle;
                            }

                            /* .card {
                                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                            } */
                        </style>



                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Psicólogo']))
                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_area">
                            Finalizar <i class="fa fa-save"></i>
                        </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


{{-- Amcharts --}}
<link href="/assets/plugins/c3-master/c3.min.css" rel="stylesheet">
<script src="/assets/plugins/amChart/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/serial.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/plugins/responsive/responsive.min.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/plugins/export/export.js" type="text/javascript"></script>
<link href="/assets/plugins/amChart/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet" />
<script src="/assets/plugins/amChart/amcharts/pie.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/light.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/black.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/dark.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/patterns.js" type="text/javascript"></script>



<script src="/js_sitio/html2canvas.js"></script>

@endsection