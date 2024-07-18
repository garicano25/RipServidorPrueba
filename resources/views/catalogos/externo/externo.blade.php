@extends('template/maestra')
@section('contenido')


{{-- ========================================================================= --}}


<style type="text/css" media="screen">
    table th{
        font-size: 12px!important;
        color:#777777!important;
        font-weight: 600!important;
    }

    table td{
        font-size: 12px!important;
    }

    table td i{
        font-size: 20px!important;
        margin: -1px 0px 0px 0px;
    }

    table td b{
        font-weight: 600!important;
    }
    
    form label{
        color: #999999;
    }

    table td i{
        font-size: 20px!important;
        margin: -1px 0px 0px 0px;
    }

    h4 b{
        color: #0BACDB;
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
                        <span class="hidden-xs-down">Proyectos activos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" id="tab_menu2" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Información del proyecto</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_3" id="tab_menu3" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Asignar signatarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_4" id="tab_menu4" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Asignar equipos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_5" id="tab_menu5" role="tab">
                        <span class="hidden-sm-up"><i class="ti-layout-tab"></i></span>
                        <span class="hidden-xs-down">Evidencias de campo</span>
                    </a>
                </li>
            </ul>
            <!-- Tab Panels -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-user"></i></span>
                                                </td>
                                                <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;">
                                                        <a class="text-success">
                                                            @php
                                                                $proveedor = DB::select('SELECT
                                                                                            proveedor.proveedor_RazonSocial 
                                                                                        FROM
                                                                                            proveedor 
                                                                                        WHERE
                                                                                            proveedor.id = '.auth()->user()->empleado_id);

                                                                if ($proveedor[0]->proveedor_RazonSocial)
                                                                {
                                                                    echo $proveedor[0]->proveedor_RazonSocial;
                                                                }
                                                            @endphp
                                                        </a>
                                                    </h4>
                                                    <small style="color: #999999; font-size: 12px;">Proveedor</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover stylish-table" width="100%" id="tabla_proyectos">
                            <thead>
                                <tr>
                                    <th width="70">No.</th>
                                    <th width="150">Folio proyecto</th>
                                    <th width="250">Empresa donde se dará el servicio</th>
                                    <th width="">Instalación / Dirección</th>
                                    <th width="120">Inicio / Fin</th>
                                    <th width="100">Duración</th>
                                    <th width="60">Mostrar</th>
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
                                                <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                    <small style="color: #999999; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px; font-size: 1.1vw;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
                                                    <small style="color: #999999; font-size: 12px;">Instalación</small>
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
                            <div class="card">
                                <div class="card-body" style="padding: 6px 10px">
                                    <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                        <tbody>
                                            <tr>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-calendar"></i></span>
                                                </td>
                                                <td width="250" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_fecha">FECHA</a></h4>
                                                    <small style="color: #999999; font-size: 12px;" class="div_proyecto_dias">Duración del servicio</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px; font-size: 1.1vw;"><a class="text-success div_proyecto_direccion">DIRECCION</a></h4>
                                                    <small style="color: #999999; font-size: 12px;">Dirección</small>
                                                </td>
                                                <td width="40" style="text-align: center; border: none;">
                                                    <span class="btn btn-success btn-circle"><i class="fa fa-map-marker"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-outline-success">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white"><i class="fa fa-warning"></i>&nbsp;&nbsp;Agente / Factor de riesgo / Servicio (asignados)</h4></div>
                                <div class="card-body">
                                    <style type="text/css">
                                        #tabla_parametros_fisicos
                                        {
                                            width: 100%;
                                            margin: 0px;
                                            padding: 0px;
                                        }

                                        #tabla_parametros_fisicos th
                                        {
                                            background: #F9F9F9;
                                            border: 1px #E5E5E5 solid!important;
                                            padding: 2px!important;
                                            text-align: center;
                                            vertical-align: middle;
                                        }

                                        #tabla_parametros_fisicos td
                                        {
                                            border: 1px #E5E5E5 solid!important;
                                            padding: 4px!important;
                                            text-align: center;
                                            vertical-align: middle;
                                        }
                                    </style>
                                    {{-- <div class="row" id="lista_agentesfisicos" style="height: 500px; max-height: 500px; overflow-y: auto; overflow-x: none;"></div> --}}
                                    <div class="row" id="lista_agentesfisicos" style="height: 500px; max-height: 500px; overflow-y: auto; overflow-x: none;">
                                        <table id="tabla_parametros_fisicos" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Parámetro</th>
                                                    <th width="10%">Puntos</th>
                                                    <th width="60%">Alcance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>data</td>
                                                    <td>data</td>
                                                    <td>data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-outline-success">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white"><i class="fa fa-flask"></i>&nbsp;&nbsp;Agentes quimicos (asignados)</h4></div>
                                <div class="card-body">
                                    <style type="text/css">
                                        #tabla_parametros_quimicos
                                        {
                                            width: 100%;
                                            margin: 0px;
                                            padding: 0px;
                                        }

                                        #tabla_parametros_quimicos th
                                        {
                                            background: #F9F9F9;
                                            border: 1px #E5E5E5 solid!important;
                                            padding: 2px!important;
                                            text-align: center;
                                            vertical-align: middle;
                                        }

                                        #tabla_parametros_quimicos td
                                        {
                                            border: 1px #E5E5E5 solid!important;
                                            padding: 4px!important;
                                            text-align: center;
                                            vertical-align: middle;
                                        }
                                    </style>
                                    <div class="row" id="lista_agentesquimicos" style="height: 500px; max-height: 500px; overflow-y: auto; overflow-x: none;">
                                        <table id="tabla_parametros_quimicos" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Parámetro</th>
                                                    <th width="10%">Puntos</th>
                                                    <th width="60%">Alcance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>data</td>
                                                    <td>data</td>
                                                    <td>data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px; font-size: 1.1vw;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
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
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Asignar signatarios de {{ auth()->user()->name }}</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post" enctype="multipart/form-data" name="form_proyectosignatarios" id="form_proyectosignatarios">
                                                {!! csrf_field() !!}
                                                <style type="text/css" media="screen">
                                                     #tabla_proyectosignatarios td
                                                     {
                                                        padding: 12px 20px 12px 2px;
                                                        border-top: 1px #EEEEEE solid;
                                                     }
                                                </style>
                                                <div style="border: 0px #999999 solid; margin-bottom: 20px;">
                                                    <table class="display table-hover stylish-table" width="100%" id="tabla_proyectosignatarios">
                                                        <thead>
                                                            <tr>
                                                                <th width="60" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">No</th>
                                                                <th width="100" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Disponible</th>
                                                                <th width="80" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Asignado</th>
                                                                <th width="auto" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Signatario</th>
                                                                <th width="440" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Entidad / Acreditación / Vigencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="5">&nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="form-group">
                                                    <label> Observaciónes (opcional)</label>
                                                    <textarea  class="form-control" rows="5" id="proyectosignatariosobservacion" name="proyectosignatariosobservacion"></textarea>
                                                </div>
                                                <div class="form-group" style="border: 0px #000 solid; height: 35px;">
                                                    <button type="submit" class="btn waves-effect waves-light btn-danger" style="float: right;" id="boton_guardar_proyectosignatarios">
                                                        <i class="fa fa-save"></i> Guardar lista de signatarios para este proyecto
                                                    </button>
                                                    <button type="button" class="btn waves-effect waves-light btn-info" style="float: right;" id="boton_imprimir_proyectosignatarios_lista">
                                                        <i class="fa fa-print"></i> Imprimir lista de signatarios asignados
                                                    </button>
                                                    {{-- <button type="button" class="btn waves-effect waves-light btn-outline-secondary" style="float: right; margin-left: 10px;" onclick="opcion_nodisponible_lista('SIGNATARIOS');" id="boton_guardar_proyectosignatarios_boton">
                                                        <i class="fa fa-ban"></i> Guardar lista de signatarios para este proyecto
                                                    </button> --}}
                                                </div>
                                                <p style="color: #F00;"><b style="color: #000;">Nota:</b> Una vez autorizada la lista de SIGNATARIOS, solamente puede hacer cambios con la autorización del administrador.</p>
                                            </form>
                                        </div>
                                    </div>
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
                                                <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px; font-size: 1.1vw;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
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
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Asignar equipos de {{ auth()->user()->name }}</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post" enctype="multipart/form-data" name="form_proyectoequipos" id="form_proyectoequipos">
                                                {!! csrf_field() !!}
                                                <style type="text/css" media="screen">
                                                     #tabla_proyectoequipos td
                                                     {
                                                        padding: 12px 20px 12px 2px;
                                                        border-top: 1px #EEEEEE solid;
                                                     }
                                                </style>
                                                <div style="border: 0px #999999 solid; margin-bottom: 20px;">
                                                    <table class="display table-hover stylish-table" width="100%" id="tabla_proyectoequipos">
                                                        <thead>
                                                            <tr>
                                                                <th width="60" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">No</th>
                                                                <th width="120" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Disponible</th>
                                                                <th width="80" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Asignado</th>
                                                                <th style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Equipo</th>
                                                                <th width="200" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Marca</th>
                                                                <th width="140" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Modelo</th>
                                                                <th width="140" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Serie</th>
                                                                <th width="160" style="padding: 10px 0px; border-bottom: 2px #EEEEEE solid;">Vigencia calibración</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="8">&nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="form-group">
                                                    <label> Observaciónes (opcional)</label>
                                                    <textarea  class="form-control" rows="5" id="proyectoequiposobservacion" name="proyectoequiposobservacion"></textarea>
                                                </div>
                                                <div class="form-group" style="border: 0px #000 solid; height: 35px;">
                                                    <button type="submit" class="btn waves-effect waves-light btn-danger" style="float: right;" id="boton_guardar_proyectoequipos">
                                                        <i class="fa fa-save"></i> Guardar lista de equipos para este proyecto
                                                    </button>
                                                    <button type="button" class="btn waves-effect waves-light btn-info" style="float: right;" id="boton_imprimir_proyectoequipos_lista">
                                                        <i class="fa fa-print"></i> Imprimir lista de equipos asignados
                                                    </button>
                                                    {{-- <button type="button" class="btn waves-effect waves-light btn-outline-secondary" style="float: right; margin-left: 10px;" onclick="opcion_nodisponible_lista('EQUIPOS');" id="boton_guardar_proyectoequipos_boton">
                                                        <i class="fa fa-ban"></i> Guardar lista de equipos para este proyecto
                                                    </button> --}}
                                                </div>
                                                <p style="color: #F00;"><b style="color: #000;">Nota:</b> Una vez autorizada la lista de EQUIPOS, solamente puede hacer cambios con la autorización del administrador.</p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                <td width="160" style="text-align: left; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px;"><a class="text-success div_proyecto_folio">FOLIO</a></h4>
                                                    <small style="color: #AAAAAA; font-size: 12px;">Proyecto</small>
                                                </td>
                                                <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                    <h4 style="margin: 0px; font-size: 1.1vw;"><a class="text-success div_proyecto_instalacion">INSTALACIÓN</a></h4>
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
                                <div class="card-body bg-secondary">
                                    <h4 class="text-white card-title" style="line-height: 18px!important; margin: 0px; padding: 0px;">Agente / Factor de riesgo / Servicio</h4>
                                </div>
                                <div class="card-body" style="border: 0px #f00 solid; height: 856px; max-height: 856px!important; overflow-x: none; overflow-y: auto;">
                                    <!-- Nav tabs -->
                                    <div class="vtabs" style="width: 100%!important;">
                                        <ul class="nav nav-tabs tabs-vertical" role="tablist" style="border-right: none;" id="lista_menu_paramertros_evidencia">
                                            <li class="nav-item" style="border-bottom: 1px #F0F0F0 solid;">
                                                <a class="nav-link" href="#">
                                                    Nombre parametro 1
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /Nav tabs -->
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card">
                                <div class="card-body bg-info">
                                    <h4 class="text-white card-title" style="line-height: 18px!important; margin: 0px; padding: 0px;" id="evidencia_agente_titulo">Nombre parametro</h4>
                                </div>
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
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'Externo']))
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
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'Externo']))
                                                    <ol class="breadcrumb m-b-10">
                                                        <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar fotos" id="boton_nuevo_fotosevidencia">
                                                            <span class="btn-label"><i class="fa fa-plus"></i></span>Foto (s)
                                                        </button>
                                                    </ol>
                                                @endif
                                                <style type="text/css">
                                                    #image-popups .foto_galeria:hover i
                                                    {
                                                        opacity: 1!important;
                                                        cursor: pointer;
                                                    }
                                                </style>
                                                <div class="row" id="evidencia_galeria_fotos"></div>
                                            </div>
                                            <div class="tab-pane p-20" id="tab_evidencia_3" role="tabpanel">
                                                @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'Externo']))
                                                    <ol class="breadcrumb m-b-10">
                                                        <button type="button" class="btn btn-secondary waves-effect waves-light botonnuevo_moduloproyecto" data-toggle="tooltip" title="Agregar fotos" id="boton_nuevo_planosevidencia">
                                                            <span class="btn-label"><i class="fa fa-plus"></i></span>Planos
                                                        </button>
                                                    </ol>
                                                @endif
                                                <style type="text/css">
                                                    #image-popups .plano_galeria:hover i
                                                    {
                                                        opacity: 1!important;
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
                                            #tabla_proyectoevidencia_puntosreales th
                                            {
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
                                            {{-- <div class="row">
                                                <div class="col-12" style="padding: 20px; text-align: right;">
                                                    <button type="button" class="btn waves-effect waves-light btn-info" id="boton_imprimir_proyectopuntosreales">
                                                        <i class="fa fa-print"></i> Imprimir reporte
                                                    </button>
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto']))
                                                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" style="margin-left: 20px;" id="boton_guardar_puntosreales">
                                                            Guardar <i class="fa fa-save"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div> --}}
                                        </form>
                                    </div>
                                </div>
                                <!-- /Tab panes -->
                                <div class="row" id="seccion_bitacoramuestreo" style="min-height: 856px!important; padding: 20px; display: none;">
                                    <div class="col-12">
                                        <style type="text/css">
                                            #tabla_bitacora th
                                            {
                                                background: #F9F9F9;
                                                /*border: 1px #E5E5E5 solid!important;*/
                                                padding: 2px!important;
                                                text-align: center;
                                                vertical-align: middle!important;
                                                border-bottom: 2px #DDDDDD solid;
                                            }

                                            #tabla_bitacora td
                                            {
                                                padding: 4px!important;
                                                text-align: center;
                                                vertical-align: middle!important;
                                            }
                                        </style>
                                        <table class="table table-hover table-bordered" width="100%" id="tabla_bitacora">
                                            <thead>
                                                <tr>
                                                    <th width="60">Día</th>
                                                    <th width="110">Fecha</th>
                                                    <th width="200">Signatario / Parametro</th>                                                    
                                                    <th width="140">Avance (Pts. / Pers.)</th>
                                                    <th width="">Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="7">gabriel</td>
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


<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->
<div id="modal_visor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="nombre_documento_visor"></h4>
            </div>
            <div class="modal-body" style="background: #555555;">
                <div class="row">
                    <div class="col-12">
                        <iframe src="/assets/images/cargando.gif" style="width: 100%; height: 600px; border: 0px #DDDDDD solid;" name="visor_documento" id="visor_documento"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary" data-dismiss="modal" id="modalvisor_boton_cerrar">Cerrar</button>
                {{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL -->
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
                    <button type="submit" class="btn btn-danger" id="boton_guardar_evidencia_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
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
    #modal_evidencia_fotos .modal-body .form-group{
        margin: 0px 0px 12px 0px!important;
        padding: 0px!important;
    }

    #modal_evidencia_fotos .modal-body .form-group label{
        margin: 0px!important;
        padding: 0px 0px 3px 0px!important;
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
                                        height: 296px!important; /*tamaño estatico del campo foto*/
                                    }
                                </style>
                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="inputevidenciafotofisicos" name="inputevidenciafotofisicos" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" onchange="redimencionar_fotoevidencia();" required>

                                <img id="new_image_preview" src="" alt="">
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
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Descripción de la foto *</label>
                                        <textarea  class="form-control" rows="10" id="proyectoevidenciafoto_descripcion" name="proyectoevidenciafoto_descripcion" required></textarea>
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
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_fotos">
                        Guardar <i class="fa fa-save"></i>
                    </button>
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
                                    <option value=""></option>
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
    #modal_evidencia_planos .modal-body .form-group{
        margin: 0px 0px 12px 0px!important;
        padding: 0px!important;
    }

    #modal_evidencia_planos .modal-body .form-group label{
        margin: 0px!important;
        padding: 0px 0px 3px 0px!important;
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
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la carpeta *</label>
                                <input type="text" class="form-control" id="proyectoevidenciaplano_carpeta" name="proyectoevidenciaplano_carpeta" placeholder="Ejem: Planos de las fuentes generadoras de la estación de compresión..." required>
                            </div>
                        </div>
                        <div class="col-12" id="planos_campo_partida" style="display: none;">
                            <div class="form-group">
                                <label>Tipo de evaluación *</label>
                                <select class="custom-select form-control" id="planoscatreportequimicospartidas_id" name="catreportequimicospartidas_id" required>
                                    <option value=""></option>
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
                    <button type="submit" class="btn btn-danger botonguardar_moduloproyecto" id="boton_guardar_evidencia_planos">
                        Guardar <i class="fa fa-save"></i>
                    </button>
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
    #modal_evidencia_bitacoramuestreo .modal-body .form-group{
        margin: 0px 0px 12px 0px!important;
        padding: 0px!important;
    }

    #modal_evidencia_bitacoramuestreo .modal-body .form-group label{
        margin: 0px!important;
        padding: 0px 0px 3px 0px!important;
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
                                        <textarea  class="form-control" rows="5" id="proyectoevidenciabitacora_observacion" name="proyectoevidenciabitacora_observacion" required></textarea>
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
                                                #proyectoevidenciabitacora_fotosgaleria span
                                                {
                                                    cursor: pointer;
                                                }

                                                #proyectoevidenciabitacora_fotosgaleria span i
                                                {
                                                    font-size: 26px;
                                                    text-shadow: 2px 2px 4px #000000;
                                                    position: absolute;
                                                    opacity: 0;
                                                    margin-top: 12px;
                                                }

                                                #proyectoevidenciabitacora_fotosgaleria span:hover i
                                                {
                                                    opacity: 1!important;
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
                                #tabla_bitacoramuestreo_personal_1 th
                                {
                                    background: #F9F9F9;
                                    border: 1px #E5E5E5 solid!important;
                                    padding: 2px!important;
                                    text-align: center;
                                    vertical-align: middle!important;
                                }

                                #tabla_bitacoramuestreo_personal td
                                {
                                    border-bottom: 1px #E5E5E5 solid!important;
                                    padding: 4px!important;
                                    text-align: center;
                                    vertical-align: middle!important;
                                }
                            </style>
                            <table class="table table-hover" width="100%" style="margin-bottom: 0px;" id="tabla_bitacoramuestreo_personal_1">
                                <thead>
                                    <tr>
                                        <th width="251">Signatario / Personal adicional</th>
                                        <th width="230">Parametro</th>
                                        <th width="150">Avance (Pts. / Pers.)</th>
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
                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Ergónomo']))
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
    #modal_bitacora_foto>.modal-dialog
    {
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
                        <img class="d-block" id="bitacora_visor" src="/assets/images/cargando.gif" style="max-height: 500px; max-width: 767px; margin: 0px auto;"/>
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


{{-- ========================================================================= --}}
@endsection