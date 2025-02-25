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
    <div class="col-12">
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
                                            <div class="multisteps-form__progress-btn" id="steps_menu_tab5">
                                                <i class="fa fa-address-card"></i><br>
                                                <span>Responsables</span>
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
                                                                                    <input type="hidden" class="form-control" id="ID_RECOPSICONORMATIVA" name="ID_RECOPSICONORMATIVA" value="0">
                                                                                    <input type="number" class="form-control" id="total_empleados" name="RECPSICO_TOTALTRABAJADORES" required>
                                                                                    <input type="hidden" class="form-control" name="RECPSICO_TOTALHOMBRESSELECCION" id="RECPSICO_TOTALHOMBRESSELECCION" value="0">
                                                                                    <input type="hidden" class="form-control" name="RECPSICO_TOTALMUJERESSELECCION" id="RECPSICO_TOTALMUJERESSELECCION" value="0">
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

                                                                            <div id="divEditarGuia4" class="col-12" style="text-align: center; display: none;">
                                                                                <button type="button" class="btn btn-success me-2 waves-effect waves-light boton_editarGuiaV" style="margin: 25px;" data-toggle="tooltip" title="Cargar trabajadores" id="boton_editarGuiaV">
                                                                                    <span class="btn-label"><i class="fa fa-pencil-square-o"></i></span>Editar GUIA DE REFERENCIA V
                                                                                </button>
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
                                                                            <div class="form-group" style="text-align: center;">
                                                                                <button type="submit" class="btn btn-danger w-50 botonguardar_modulorecsensorial" id="boton_guardar_normativa">
                                                                                    Guardar datos de Normativa <i class="fa fa-save"></i>
                                                                                </button>
                                                                            </div>
                                                                            <hr>
                                                                        </div>
                                                                        &ensp;
                                                                        <div class="col-12" style="text-align: center;">
                                                                            <label class="col-12" style="font-weight: bold;font-size: 20px;">Carga de trabajadores del centro de trabajo</label>
                                                                            <!-- <h6 class="col-12 card-subtitle text-white m-b-0 op-5">&nbsp;</h6> -->
                                                                            <button type="button" class="btn btn-success me-2 waves-effect waves-light botonnuevo_modulorecsensorial" style="margin: 25px;" data-toggle="tooltip" title="Cargar trabajadores" id="boton_carga_trabajadores" onclick="abrirTrabajadoresExcel()">
                                                                                <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Cargar trabajadores y/o muestrear
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
                                                        </div>
                                                    </div>
                                                </form>
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
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label> Cargo del responsable del Contrato/Proyecto</label>
                                                                            <select class="custom-select form-control" id="CARGO_CONTRATO" name="CARGO_CONTRATO" required>
                                                                                <option value=""></option>
                                                                                @foreach($cargos as $dato)
                                                                                <option value="{{$dato->CARGO}}">{{$dato->CARGO}}</option>
                                                                                @endforeach
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
                                                                                                                <label style="font-size: 16px;">Cargar excel con el total de trabajadores del centro de trabajo*</label>
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
                                                                                                            <td>
                                                                                                                {!! csrf_field() !!}
                                                                                                                <label style="font-size: 16px;">Cargar excel con la muestra de trabajadores proporcionada por centro de trabajo*</label>
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
<div id="modal_editarGuiaV" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_editarGuiaV" id="form_editarGuiaV">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Guia de referencia V</h4>
                </div>
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="RECPSICO_ID_GUIAV" name="RECPSICO_ID" value="0">
                            <input type="hidden" class="form-control" id="ID_GUIAV" name="ID_GUIAV" value="0">
                                <div class="form-group">
                                    <label>Seleccionar las preguntas que desea aplicar de la Guia de referencia V *</label>
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta1" name="pregunta1" checked readonly>
                                        <label class="custom-control-label" for="pregunta1">Sexo</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta2" name="pregunta2" checked>
                                        <label class="custom-control-label" for="pregunta2">Edad en años</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta3" name="pregunta3" checked>
                                        <label class="custom-control-label" for="pregunta3">Estado civil</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta4" name="pregunta4" checked>
                                        <label class="custom-control-label" for="pregunta4">Nivel de estudios</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta5" name="pregunta5" checked readonly>
                                        <label class="custom-control-label" for="pregunta5">Ocupacion/profesión/puesto</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta6" name="pregunta6" checked readonly>
                                        <label class="custom-control-label" for="pregunta6">Departamento/Sección/Área</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;"> 
                                        <input type="checkbox" class="custom-control-input" id="pregunta7" name="pregunta7" checked>
                                        <label class="custom-control-label" for="pregunta7">Tipo de puesto</label>
                                    </div>
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta8" name="pregunta8" checked>
                                        <label class="custom-control-label" for="pregunta8">Tipo de contratación</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta9" name="pregunta9" checked>
                                        <label class="custom-control-label" for="pregunta9">Tipo de personal</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta10" name="pregunta10" checked>
                                        <label class="custom-control-label" for="pregunta10">Tipo de jornada de trabajo</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta11" name="pregunta11" checked>
                                        <label class="custom-control-label" for="pregunta11">Realiza rotación de turnos</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta12" name="pregunta12" checked>
                                        <label class="custom-control-label" for="pregunta12">Tiempo en el puesto actual</label>
                                    </div> 
                                    <div class="custom-control custom-checkbox" style="display: block;">
                                        <input type="checkbox" class="custom-control-input" id="pregunta13" name="pregunta13" checked>
                                        <label class="custom-control-label" for="pregunta13">Tiempo de experiencia laboral</label>
                                    </div> 
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">   
                    <div>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guadarGuiaV">
                            Guardar <i class="fa fa-save"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- ============================================================== -->
<!-- /MODALES RECONOCIMIENTO PSICOSOCIAL -->
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
<!-- /MODALES RECONOCIMIENTO PSICOSOCIAL -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- MODAL-EVIDENCIA-FOTOS -->
<!-- ============================================================== -->
<!-- <style type="text/css" media="screen">
    #modal_evidencia_fotosquimicos .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_evidencia_fotosquimicos .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style> -->

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