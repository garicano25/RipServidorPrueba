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



<style>
    #Tablarecoareasergo tbody td,
    #Tablarecoareasergo thead th {
        font-size: 15px !important;
    }

    #Tablarecocategoriasergo tbody td,
    #Tablarecocategoriasergo thead th {
        font-size: 15px !important;
    }

    #Tablarecofichasergo tbody td,
    #Tablarecofichasergo thead th {
        font-size: 15px !important;
    }
</style>


<!--

CONTENIDO RECONOCIMIENTO PSICOSOCIAL -->

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
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador','Ergónomo']))
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
                                                                                <h4 class="card-title">Mapa ubicación </h4>
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
                                                                                <h4 class="card-title">Plano instalación <br> con áreas </h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotoplano" name="inputfotoplano" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
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
                                                                                <h4 class="card-title">Foto instalación </h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotoinstalacion" name="inputfotoinstalacion" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
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
                                                                                <h4 class="card-title">Mapa de peligro <br> y riesgo ergonómico </h4>
                                                                                <div class="row">
                                                                                    <div class="col-12 clienteblock">
                                                                                        <div class="form-group">
                                                                                            <style type="text/css" media="screen">
                                                                                                .dropify-wrapper {
                                                                                                    height: 300px !important;
                                                                                                    /*tamaño estatico del campo foto*/
                                                                                                }
                                                                                            </style>
                                                                                            <input type="file" accept="image/jpeg,image/x-png,image/gif" id="inputfotomapaderiesgo" name="inputfotomapaderiesgo" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" />
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
                                                                @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))
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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))

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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))

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
                                                            @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))

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
                                                                <table class="table table-bordered table-hover stylish-table" width="100%" id="Tablarecofichasergo">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 60px!important;">No.</th>
                                                                            <th>Categoría</th>
                                                                            <th>Nombre del empleado </th>
                                                                            <th>Ficha / No empleado</th>
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
                                    <a href="#0" class="list-group-item active">Portada <i class="fa" id="menureporte_0"></i></a>
                                    <a href="#1" class="list-group-item">1.- Introducción <i class="fa" id="menureporte_1"></i></a>
                                    <a href="#2" class="list-group-item">2.- Definiciones <i class="fa" id="menureporte_2"></i></a>
                                    <a href="#3" class="list-group-item">3.- Objetivos</a>
                                    <a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa" id="menureporte_3_1"></i></a>
                                    <a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa" id="menureporte_3_2"></i></a>
                                    <a href="#4" class="list-group-item">4.- Metodología</a>
                                    <a href="#5" class="list-group-item">5.- Resultados</a>
                                    <a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa" id="menureporte_5_1"></i></a>
                                    <a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa " id="menureporte_5_2"></i></a>
                                    <a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa " id="menureporte_5_3"></i></a>

                                    <a href="#6" class="list-group-item">6.- Evaluación</a>
                                    <a href="#6_1" class="list-group-item submenu">6.1.- Preguntas NOM-036-1-STPS-2018 <i class="fa " id="menureporte_6_1"></i></a>
                                    <a href="#7" class="list-group-item">7.- Resultados fichas <i class="fa " id="menureporte_7"></i></a>
                                    <a href="#8" class="list-group-item">8.- Conclusiones <i class="fa" id="menureporte_8"></i></a>
                                    <a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa" id="menureporte_9"></i></a>
                                    <a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa" id="menureporte_10"></i></a>
                                    <a href="#10" class="list-group-item">11.- Generar informe <i class="fa" id="menureporte_11"></i></a>

                                    <!-- <a href="#6_2" class="list-group-item submenu">6.2.- Método empleado y criterio de selección <i class="fa fa-times" id="menureporte_6_2"></i></a>
                                    <a href="#6_2_1" class="list-group-item subsubmenu">6.2.1.- Índice de área <i class="fa fa-times" id="menureporte_6_2_1"></i></a>
                                    <a href="#6_2_2" class="list-group-item subsubmenu">6.2.2.- Puesto de trabajo <i class="fa fa-times" id="menureporte_6_2_2"></i></a>
                                    
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
                                    <form method="post" enctype="multipart/form-data" name="form_informe_portada" id="form_informe_portada">
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
                                                            <input type="file" accept="image/jpeg,image/x-png" id="RUTA_IMAGEN_PORTADA" name="RUTA_IMAGEN_PORTADA" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
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
                                                            <select class="custom-select form-control" id="INFORME_MES" name="INFORME_MES">
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
                                                            <select class="custom-select form-control" id="INFORME_ANIO" name="INFORME_ANIO">
                                                                <option value="" selected disabled></option>
                                                                <script>
                                                                    const currentYear = new Date().getFullYear();
                                                                    for (let i = currentYear; i >= 2017; i--) {
                                                                        document.write('<option value="' + i + '">' + i + '</option>');
                                                                    }
                                                                </script>
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
                                    <form method="post" enctype="multipart/form-data" name="form_informe_introduccion" id="form_informe_introduccion">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}

                                                    <div class="form-group">
                                                        <label>Seleccionar introducción </label>
                                                        <select class="custom-select form-control"
                                                            id="SELECT_INTRODUCCION"
                                                            name="SELECT_INTRODUCCION">

                                                            <option value=""></option>

                                                            @foreach($catintroduccion as $dato)

                                                            <option
                                                                value="{{$dato->ID_INTRODUCCION}}"
                                                                data-introduccion="{{ htmlspecialchars($dato->NOMBRE_INTRODUCCION) }}">

                                                                {{$dato->QUIEN_INTRODUCCION}}

                                                            </option>

                                                            @endforeach

                                                        </select>
                                                    </div>


                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="INFORME_INTRODUCCION" name="INFORME_INTRODUCCION" required></textarea>
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
                                            <form enctype="multipart/form-data" method="post" name="form_informe_listadefiniciones" id="form_informe_listadefiniciones">
                                                <div class="row mb-3">
                                                    <table class="table-sm" style="width: 96%; table-layout: fixed;">
                                                        <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_definiciones">
                                                            <thead>
                                                                <tr>
                                                                    <th width="130">Concepto</th>
                                                                    <th>Descripción / Fuente</th>
                                                                    <th width="80">Activo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($catdefiniciones as $dato)
                                                                <tr>
                                                                    <td>
                                                                        {{ $dato->CONCEPTO_DEFINICION }}
                                                                    </td>

                                                                    <td class="justificado">
                                                                        {{ $dato->DESCRIPCION_DEFINICION }}

                                                                        @if($dato->FUENTE_DEFINICION)
                                                                        <br>
                                                                        <span style="font-style: italic; color: gray;">
                                                                            Fuente: {{ $dato->FUENTE_DEFINICION }}
                                                                        </span>
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <div class="switch">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    class="recomendacion_checkbox"
                                                                                    name="DEFINICONES_INFORME[]"
                                                                                    value="{{ $dato->ID_DEFINICIONES }}"
                                                                                    onclick="activa_recomendacion(this);"
                                                                                    {{ $dato->ACTIVO == 1 ? '' : '' }}>
                                                                                <span class="lever switch-col-light-blue"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                </div>
                                                <div class="col-12" style="text-align: right;">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_definiciones">Guardar definiciones <i class="fa fa-save"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-2" id="3">3.- Objetivos</h4>
                                    <h4 class="card-title" id="3_1">3.1.- Objetivo general</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_informe_objetivogeneral" id="form_informe_objetivogeneral">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}
                                                    <textarea
                                                        class="form-control"
                                                        style="margin-bottom: 0px;"
                                                        rows="8"
                                                        id="INFORME_OBJETIVOGENERALES"
                                                        name="INFORME_OBJETIVOGENERALES"
                                                        required>Evaluar los factores de riesgo ergonómicos de las actividades laborales que impliquen postura, movimientos, manipulación manual de cargas y esfuerzos, mediante la observación y aplicación de métodos de carga física para la prevención de trastornos músculo esqueléticos.</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivogeneral">Guardar objetivo general <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="3_2">3.2.- Objetivos específicos</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_informe_objetivoespecifico" id="form_informe_objetivoespecifico">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}

                                                    <textarea
                                                        class="form-control"
                                                        style="margin-bottom: 0px;"
                                                        rows="12"
                                                        id="INFORME_OBJETIVOSESPECIFICOS"
                                                        name="INFORME_OBJETIVOSESPECIFICOS"
                                                        required>• Identificar los trabajos prioritarios y tareas con riesgo ergonómico a través de un reconocimiento sensorial y entrevista con los trabajadores.

• Determinar el nivel de riesgo por carga física a la cual se encuentran expuestos los trabajadores.

• Generar recomendaciones derivadas de la identificación de riesgos para mitigar la probabilidad de ocurrencia de Trastornos Músculo Esqueléticos (TME) de origen laboral.</textarea>

                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivoespecifico">Guardar objetivos específicos <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="4">4.- Metodología</h4>

                                    <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_1" id="form_reporte_metodologia_4_1">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="justificado">La metodología utilizada para la identificación del peligro y riesgo ergonómico se basa en el modelo aplicando la ISO 12995 donde se contemplan los aspectos de la ergonomía física: </p>
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset('assets/images/ergo/metodologiaergo1.png') }}"
                                                        style="max-width: 50%; height: auto;">
                                                </div>

                                                <p style="font-style: italic;">
                                                    <strong>Nota:</strong> donde sale la palabra control del es control del riesgo.
                                                </p>

                                                <ul>
                                                    <li>Ficha 1: levantamiento manual de cargas</li>
                                                    <li>Ficha 2: transporte manual de cargas</li>
                                                    <li>Ficha 3: empuje y tracción de cargas</li>
                                                    <li>Ficha 4: movimientos repetitivos de la extremidad superior</li>
                                                    <li>Ficha 5: posturas de trabajo estático</li>
                                                    <li>Ficha 6: posturas forzadas dinámicas</li>
                                                </ul>

                                                <p style="text-align: justify;">
                                                    Que para el caso de la re
                                                    pública Mexicana, está contemplada la NOM-036-1-STPS-2018 en los aspectos de la ficha 1, 2 y 3;
                                                    para lo cual se busca identificar los peligros de DESÓRDENES MUSCULOESQUELÉTICOS DE COLUMNA LUMBAR (TME),
                                                    para posteriormente estimar el riesgo y construir un mapa de estimación de riesgos por los aspectos referidos en las fichas ya mencionadas.
                                                    Posterior a esta fase se debe elaborar un plan estratégico para la prevención de los TME de origen laboral
                                                    y finalmente un plan de actuación anual.
                                                </p>

                                                <div class="text-center mt-3">
                                                    <img src="{{ asset('assets/images/ergo/metodologiaergo2.png') }}"
                                                        style="max-width: 50%; height: auto;">
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title mt-2" id="5">5.- Reconocimiento</h4>
                                    <h4 class="card-title" id="5_1">5.1.- Ubicación de la instalación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_informe_ubicacion" id="form_informe_ubicacion">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        {!! csrf_field() !!}
                                                        <textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="INFORME_UBICACIONINSTALACION" name="INFORME_UBICACIONINSTALACION" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>

                                                <input type="file" accept="image/jpeg,image/x-png" id="RUTA_IMAGEN_UBICACION" name="RUTA_IMAGEN_UBICACION" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mt-3" style="text-align: right;">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_ubicacion">Guardar ubicación <i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="card-title" id="5_2">5.2.- Descripción del proceso en la instalación</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_informe_procesoinstalacion" id="form_reporte_procesoinstalacion">
                                        <div class="row">
                                            <div class="col-12">
                                                {!! csrf_field() !!}
                                                <div class="form-group">
                                                    <label>Descripción del proceso en la instalación</label>
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="INFORME_PROCESOINSTALACION" name="INFORME_PROCESOINSTALACION" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Descripción de la actividad principal de la instalación</label>
                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="7" id="INFORME_ACTIVIDADPRINCIPAL" name="INFORME_ACTIVIDADPRINCIPAL" required></textarea>
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
                                            <p class="justificado">En este apartado se muestra la actividad principal desarrollada en cada una de las áreas, involucrando al personal/categoría
                                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_categoria">
                                                <thead>
                                                    <tr>
                                                        <th>Categoría</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table><br><br>
                                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_area">
                                                <thead>
                                                    <tr>
                                                        <th width="130">Área</th>
                                                        <th width="">Categoría</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-2" id="6">6.- Evaluación</h4>
                                    <h4 class="card-title" id="6_1">6.1.- Preguntas NOM-036-1-STPS-2018</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_preguntas-nom-036" id="form_preguntas-nom-036">
                                        <div class="row" style="display:flex; justify-content:center;">
                                            <div id="contenedorGraficas"></div>
                                        </div>
                                    </form>




                                    <h4 class="card-title" id="8">8.- Conclusiones</h4>
                                    <form method="post" enctype="multipart/form-data" name="form_informe_conclusiones" id="form_informe_conclusiones">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {!! csrf_field() !!}

                                                    <div class="form-group">
                                                        <label>Seleccionar conclusión </label>
                                                        <select class="custom-select form-control"
                                                            id="SELECT_CONCLUSION"
                                                            name="SELECT_CONCLUSION">

                                                            <option value=""></option>

                                                            @foreach($catconclusion as $dato)

                                                            <option
                                                                value="{{$dato->ID_CONCLUSION}}"
                                                                data-conclusion="{{ htmlspecialchars($dato->NOMBRE_CONCLUSION) }}">

                                                                {{$dato->QUIEN_CONCLUSION}}

                                                            </option>

                                                            @endforeach

                                                        </select>
                                                    </div>


                                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="INFORME_CONCLUSION" name="INFORME_CONCLUSION" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>
                                            </div>
                                        </div>
                                    </form>


                                    <h4 class="card-title" id="9">9.- Recomendaciones de control</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <form enctype="multipart/form-data" method="post" name="form_informe_listarecomendaciones" id="form_informe_listarecomendaciones">
                                                <div class="row mb-3">
                                                    <table class="table-sm" style="width: 96%; table-layout: fixed;">
                                                        <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_recomendaciones">
                                                            <thead>
                                                                <tr>
                                                                    <th>Descripción</th>
                                                                    <th width="80">Activo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($catrecomendaciones as $dato)
                                                                <tr>

                                                                    <td class="justificado">
                                                                        {{ $dato->DESCRIPCION_RECOMENDACIONES }}

                                                                    </td>

                                                                    <td class="text-center">
                                                                        <div class="switch">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    class="recomendacion_checkbox"
                                                                                    name="DESCRIPCION_RECOMENDACIONES[]"
                                                                                    value="{{ $dato->ID_RECOMENDACIONES }}"
                                                                                    onclick="activa_recomendacion(this);"
                                                                                    {{ $dato->ACTIVO == 1 ? '' : '' }}>
                                                                                <span class="lever switch-col-light-blue"></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                </div>
                                                <div class="col-12" style="text-align: right;">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_recomendaciones">Guardar recomendaciones <i class="fa fa-save"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <h4 class="card-title" id="10">10.- Responsables del informe</h4>
                                    <form enctype="multipart/form-data" method="post" name="form_informe_responsablesinforme" id="form_informe_responsablesinforme">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Nombre del responsable técnico</label>
                                                            <input type="text" class="form-control" id="INFORME_RESPONSABLE1" name="INFORME_RESPONSABLE1" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Cargo del responsable técnico</label>
                                                            <input type="text" class="form-control" id="INFORME_RESPONSABLE1CARGO" name="INFORME_RESPONSABLE1CARGO" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Foto documento del responsable técnico</label>
                                                            <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc1"></i>
                                                            <input type="file" accept="image/jpeg,image/x-png" id="INFORME_RESPONSABLE1DOCUMENTO" name="INFORME_RESPONSABLE1DOCUMENTO" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Nombre del administrativo prestador de servicio</label>
                                                            <input type="text" class="form-control" id="INFORME_RESPONSABLE2" name="INFORME_RESPONSABLE2" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Cargo del administrativo prestador de servicio</label>
                                                            <input type="text" class="form-control" id="INFORME_RESPONSABLE2CARGO" name="INFORME_RESPONSABLE2CARGO" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Foto documento del prestador de servicio</label>
                                                            <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc2"></i>
                                                            <input type="file" accept="image/jpeg,image/x-png" id="INFORME_RESPONSABLE2DOCUMENTO" name="INFORME_RESPONSABLE2DOCUMENTO" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" style="text-align: right;">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_responsablesinforme">Guardar responsables del informe <i class="fa fa-save"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                    <h4 class="card-title" id="11">Generar informe .docx</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                                <button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" title="Nueva revisión" id="boton_reporte_nuevarevision">
                                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión
                                                </button>
                                            </ol>

                                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_revisiones">
                                                <thead>
                                                    <tr>
                                                        <th width="40">Revisión</th>
                                                        <th width="60">Concluido</th>
                                                        <th width="180">Concluido por:</th>
                                                        <th width="60">Cancelado</th>
                                                        <th width="180">Cancelado por:</th>
                                                        <th>Estado</th>
                                                        <th width="60">Descargar</th>
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

                        <div class="col-12">
                            <div class="form-group">
                                <label>Areás *</label>
                                <select class="custom-select form-control" id="CATEGORIA_AREAS_ID" name="CATEGORIA_AREAS_ID[]" multiple>
                                </select>
                            </div>
                        </div>


                        <div class="row listadodeturno m-2"></div>


                    </div>

                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-danger botonagregarContacto" id="botonagregarhorario">Agregar turno <i class="fa  fa-clock-o"></i></button>

                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))
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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))
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



                        <input type="hidden" name="JSON_ACTIVIDADES" id="JSON_ACTIVIDADES">
                        <input type="hidden" name="JSON_FICHAS" id="JSON_FICHAS">

                        <!-- CATEGORÍA -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Categoría *</label>
                                <select class="form-control" id="CATEGORIA_ID_FICHA" name="CATEGORIA_ID_FICHA" required>
                                    <option value="">Selecciona un tipo de valor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label> Departamento *</label>
                                <select class="custom-select form-control" id="CAT_DEPARTAMENTO_FICHA" name="CAT_DEPARTAMENTO_FICHA" required style="pointer-events:none; background-color:#e9ecef;">
                                    <option value=""></option>
                                    @foreach($catdepartamento as $dato)
                                    <option value="{{$dato->id}}">{{$dato->catdepartamento_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Áreas *</label>
                                <select class="custom-select form-control" id="CAT_AREAS_FICHA" name="CAT_AREAS_FICHA[]" multiple>
                                </select>
                            </div>
                        </div>


                        <!-- NOMBRE -->
                        <div class="col-8">
                            <div class="form-group">
                                <label>Nombre del empleado *</label>
                                <input type="text" class="form-control" name="NOMBRE_EMPLEADO_FICHA" id="NOMBRE_EMPLEADO_FICHA" required>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Ficha / No empleado </label>
                                <input type="text" class="form-control" name="NO_EMPLEADO_FICHA" id="NO_EMPLEADO_FICHA">
                            </div>
                        </div>
                        <!-- SEXO -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Sexo *</label>
                                <select class="form-control" name="SEXO_EMPLEADO_FICHA" id="SEXO_EMPLEADO_FICHA" required>
                                    <option value="">Seleccionar</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- FECHA NACIMIENTO -->
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fecha de nacimiento </label>
                                <div class="input-group">
                                    <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="FECHA_NACIMIENTO" name="FECHA_NACIMIENTO">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Edad </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="EDAD_EMPLEADO_FICHA" id="EDAD_EMPLEADO_FICHA" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- PESO -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>Peso (kg) </label>
                                <input type="number" class="form-control" name="PESO_FICHA" id="PESO_FICHA" step="0.1">
                            </div>
                        </div>

                        <!-- TALLA -->
                        <div class="col-6">
                            <div class="form-group">
                                <label>Talla (cm) </label>
                                <input type="number" class="form-control" name="TALLA_FICHA" id="TALLA_FICHA">
                            </div>
                        </div>


                        <div class="col-4">
                            <div class="form-group">
                                <label>Régimen Contractual </label>

                                <select class="custom-select form-control" id="REGIMEN_CONTRACTUAL_FICHA" name="REGIMEN_CONTRACTUAL_FICHA">
                                    <option value=""></option>
                                    @foreach($catregimen as $dato)
                                    <option value="{{$dato->ID_REGIMEN_CONTRACTUAL}}">{{$dato->NOMBRE_REGIMEN_CONTRACTUAL}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Jornada </label>
                                <select class="custom-select form-control" id="JORNADA_EMPLEADO_FICHA" name="JORNADA_EMPLEADO_FICHA">
                                    <option value=""></option>
                                    @foreach($catjornada as $dato)
                                    <option value="{{$dato->ID_JORNADA}}">{{$dato->NOMBRE_JORNADA}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Turno </label>

                                <select class="custom-select form-control" id="TURNO_EMPLEADO_FICHA" name="TURNO_EMPLEADO_FICHA">
                                    <option value=""></option>
                                    @foreach($caturno as $dato)
                                    <option value="{{$dato->ID_TURNO}}">{{$dato->NOMBRE_TURNO}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>¿Cuánto tiempo lleva en la empresa?</label>
                                <input type="text" class="form-control" name="TIEMPO_EMPRESA_FICHA" id="TIEMPO_EMPRESA_FICHA">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label>Antigüedad en la categoría</label>
                                <input type="text" class="form-control" name="ANTIGUEDAD_CATEOGORIA_FICHA" id="ANTIGUEDAD_CATEOGORIA_FICHA">
                            </div>
                        </div>





                        <br><br>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-danger" onclick="agregarActividad()">
                                + Agregar Actividad
                            </button>
                        </div>

                        <div id="contenedorActividades" class="mt-2"></div>




                        <br><br>

                        <style>
                            /* ACTIVIDADES */

                            #contenedorActividades {
                                width: 100%;
                            }

                            .actividad-card {
                                width: 100%;
                                border: 1px solid #ddd;
                                border-radius: 10px;
                                padding: 15px;
                                margin-bottom: 10px;
                                background: #fff;
                            }

                            .actividad-row {
                                display: flex;
                                gap: 15px;
                                align-items: flex-start;
                            }

                            .actividad-left {
                                width: 30%;
                            }

                            .actividad-right {
                                width: 70%;
                            }

                            .btn-agregar-tarea {
                                width: 20%;
                                background: linear-gradient(90deg, #6dd6e4, #6dd6e4);
                                color: white;
                                border: none;
                            }

                            .tarea-item {
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                padding: 10px;
                                margin-top: 8px;
                                background: #f9fafb;
                            }



                            /* FICHAS */
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
                                                <select class="form-control" name="P1_CARGA_MAYOR_3KG" id="P1_CARGA_MAYOR_3KG" required>
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
                                                <select class="form-control" name="P2_FRECUENCIA_CARGA" id="P2_FRECUENCIA_CARGA" required>
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
                                                <select class="form-control" name="P3_MANIPULACION_CARGA" id="P3_MANIPULACION_CARGA" required>
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
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador','Ergónomo']))
                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_modulorecsensorial" id="boton_guardar_fichastecnicas">
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