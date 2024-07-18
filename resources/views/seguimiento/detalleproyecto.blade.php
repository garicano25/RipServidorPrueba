@extends('template/maestraprograma')

@section('contenido')





{{-- ========================================================================= --}}





<style type="text/css" media="screen">

    

    



    .form-controljc {

    display: block;

    width: 150px !important;

    height: 25px;

    padding: 2px !important;

    font-size: 0.8rem;

    line-height: 1.25;

    color: #495057;

    background-color: #fff;

    background-image: none;

    /*background-clip: padding-box;*/

    border: 1px solid rgba(0, 0, 0, 0.15);

    border-radius: 0.25rem;

    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;

}







    table th{

        font-size: 12px!important;

        color:#777777!important;

        font-weight: 600!important;

    }



    table td{

        font-size: 14px!important;

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



    table td.details-control {

        background: url('/assets/images/details_open.png') no-repeat center center;

        cursor: pointer;

    }

    table tr.shown{

        font-weight: 600!important;

        background: #000;

        color: #ffffff;

        text-transform: uppercase;

    }

    table tr.shown td.details-control {

        background: url('/assets/images/details_close.png') no-repeat center center;

    }



    table td.detalle-control {

        background: url('/assets/images/edit.png') no-repeat center center;

        cursor: pointer;

    }

    table td.detalle-controlResultado {

        background: url('/assets/images/edit.png') no-repeat center center;

        cursor: pointer;

    }

    table tr.detalleshown{

        font-weight: 600!important;

        background: #000;

        color: #ffffff;

        text-transform: uppercase;

    }

    table tr.shown td.detalle-control {

        background: url('/assets/images/details_close.png') no-repeat center center;

    }



    h4 b{

        color: #0BACDB;

    }



    /*Tabla asignar proveedores*/



    .round{

        width: 42px;

        height: 42px;

        padding: 0px!important;

    }



    .round i{

        margin: 0px!important;

        font-size: 16px!important;

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

    <div class="col-lg-2 col-xl-3 col-md-3">

                        <div class="card">

                            <div class="card-header bg-info">

                                <h2 class="card-title text-white"><b>{{$proyecto->proyecto_folio}}</b></h2>

                                <h4 class="card-subtitle text-white">Datos generales</h4>

                            </div>

                            <div class="card-body">

                                <div>

                                    <p><b class="text-info">Fecha Inicio: </b> {{$proyecto->proyecto_fechainicio}}</p>

                                    <p><b class="text-info">Fecha Entrega: </b> {{$proyecto->proyecto_fechafin}}</p>

                                </div>

                                @if($proyecto->prorrogas->count('id') > 0)

                                    <h4 class="card-title mt-4">Reprogramación</h4>

                                    <table class="table">

                                        <tr>

                                            <td>Inicio</td>

                                            <td>Finalizar</td>

                                            <td>Periodo</td>

                                            <td></td>

                                        </tr>

                                    @foreach($proyecto->prorrogas as $prorroga)

                                        <tr>

                                            <td>{{ $prorroga->proyectoprorrogas_fechainicio }}</td>

                                            <td>{{ $prorroga->proyectoprorrogas_fechafin }}</td>

                                            <td>{{ $prorroga->proyectoprorrogas_dias }}</td>

                                            <td><a href="/programatrabajoexcel/{{$proyecto?$proyecto->id:0}}/{{$ordentrabajo?$ordentrabajo->id:0}}" class="btn waves-effect waves-light btn-success"><i class="fa fa-calendar"></i></a></td>   

                                        </tr>

                                    @endforeach

                                    </table>

                                @endif

                                <h4 class="card-title mt-4">Descargas</h4>

                                <div class="border-bottom p-1">

                                    <a href="/programatrabajoexcel/{{$proyecto?$proyecto->id:0}}/{{$ordentrabajo?$ordentrabajo->id:0}}" class="btn waves-effect waves-light btn-info"><i class="fa fa-table"></i> Descargar programa RIP</a>

                                    {{-- <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Sharemodel" style="width: 100%">

                                         <i class="fa fa-table"></i> Descargar Ficha

                                    </button> --}}

                                </div>

                                <div class="border-bottom p-1">

                                    <a href="/programatrabajoexcelcliente/{{$proyecto?$proyecto->id:0}}/{{$ordentrabajo?$ordentrabajo->id:0}}" class="btn waves-effect waves-light btn-info"><i class="fa fa-calendar"></i> Descargar programa PEP</a>

                                </div>

                            </div>

                        </div>

                    </div>

    <div class="col-9">

        <div class="card">

            <div class="card-header bg-info">

                {!! csrf_field() !!}

                {{ method_field('PUT') }}

                <input type="hidden" id="id_proyecto" name="id_proyecto" value="{{$proyecto?$proyecto->id:0}}">

                <input type="hidden" id="id_ordentrabajo" name="id_ordentrabajo" value="{{$ordentrabajo?$ordentrabajo->id:0}}">

                <input type="hidden" id="proyecto_fechainicio" name="proyecto_fechainicio" class="form-control" value = "{{$proyecto->proyecto_fechainicio}}">

                <input type="hidden" id="proyecto_fechaentrega" name="proyecto_fechaentrega" class="form-control" value = "{{$proyecto->proyecto_fechaentrega}}">

                <h2 class="text-white card-title">Programa de actividades</h2>

                <h4 class="card-subtitle text-white">{{$proyecto->proyecto_clienteinstalacion}}</h4>

            </div>

        </div>

        <div class="card">

            <div class="card-header bg-success">

                    <h4 class="mb-0 text-white">01 Proceso de gestión</h4>

                </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12" id="BusquedaGestion">

                    </div>

                    <div class="col-4">



                    </div>

                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="table-responsive">

                            <table class="table stylish-table" width="100%" id="tabla_detalleProyectosGestion" data-field-id="{{$ordentrabajo?$ordentrabajo->id:0}}">

                                <thead>

                                    <tr>

                                        <th style="width:5%!important;">#</th>

                                        <th style="width:27%!important;">Actividad</th>

                                        <th style="width:12%!important;">Inicio Programa</th>

                                        <th style="width:12%!important;">Fin Programa</th>

                                        <th style="width:12%!important;">Inicio Real</th>

                                        <th style="width:12%!important;">Fin Real</th>

                                        <th style="width:5%!important;">Concluido</th>

                                        <th style="width:10%!important;">Estado </th>

                                        <th style="width:5%!important;"></th>

                                    </tr>

                                </thead>

                                <tbody class="font-weight">

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header bg-info">

                <h4 class="mb-0 text-white">02 Proceso de muestreo</h4>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12" id="BusquedaMuestras">

                    </div>

                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="table-responsive">

                            <table class="table stylish-table" width="100%" id="tabla_detalleProyectos" data-field-id="{{$ordentrabajo?$ordentrabajo->id:0}}">

                                <thead>

                                    <tr>

                                        <th style="width: 60px!important;"></th>

                                        <th style="width: 250px!important;">Agente / Factor</th>

                                        <th style="width: 100px!important;">Puntos a Evaluar</th>

                                        <th>Especualista/Signatarios</th>

                                        <th>Laboratorio</th>

                                        <th style="width: 130px!important;">Fecha</th>

                                        <th style="width: 130px!important;">Fecha entrega</th>

                                    </tr>

                                </thead>

                                <tbody class="font-weight"> 

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header bg-success">

                <h4 class="mb-0 text-white">03 Proceso de informes y resultados</h4>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12" id="BusquedaResultado">

                    </div>

                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="table-responsive">

            

                            <table class="table stylish-table" width="100%" id="tabla_detalleProyectosResultados" data-field-id="{{$ordentrabajo?$ordentrabajo->id:0}}">

                                <thead>

                                    <tr>

                                        <th style="width:5%!important;">#</th>

                                        <th style="width:27%!important;">Actividad</th>

                                        <th style="width:12%!important;">Inicio Programa</th>

                                        <th style="width:12%!important;">Fin Programa</th>

                                        <th style="width:12%!important;">Inicio Real</th>

                                        <th style="width:12%!important;">Fin Real</th>

                                        <th style="width:5%!important;">Concluido</th>

                                        <th style="width:10%!important;">Estado </th>

                                        <th style="width:5%!important;"></th>

                                    </tr>

                                </thead>

                                <tbody class="font-weight">

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header bg-info">

                <h4 class="mb-0 text-white">04 Proceso de cierre de proyecto</h4>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-12" id="BusquedaFinalizacion">

                    </div>

                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="table-responsive">

            

                            <table class="table stylish-table" width="100%" id="tabla_detalleProyectosFinalizacion" data-field-id="{{$ordentrabajo?$ordentrabajo->id:0}}">

                                <thead>

                                    <tr>

                                        <th style="width:5%!important;">#</th>

                                        <th style="width:27%!important;">Actividad</th>

                                        <th style="width:12%!important;">Inicio Programa</th>

                                        <th style="width:12%!important;">Fin Programa</th>

                                        <th style="width:12%!important;">Inicio Real</th>

                                        <th style="width:12%!important;">Fin Real</th>

                                        <th style="width:5%!important;">Evidencia</th>

                                        <th style="width:10%!important;">Estado </th>

                                        <th style="width:5%!important;"> </th>

                                    </tr>

                                </thead>

                                <tbody class="font-weight">

                                </tbody>

                            </table>

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

<style type="text/css" media="screen">

    #modal_visor>.modal-dialog{

        min-width: 900px !important;

    }



    iframe{

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

<!-- MODALES -->

<!-- ============================================================== -->

<style type="text/css" media="screen">

    #modal_ordenservicio .modal-body .form-group

    {

        margin: 0px 0px 12px 0px!important;

        padding: 0px!important;

    }



    #modal_ordenservicio .modal-body .form-group label

    {

        margin: 0px!important;

        padding: 0px 0px 3px 0px!important;

    }



    #visor_menu_bloqueado

    {

        width: 498px;

        height: 52px;

        background: #555555;

        position: absolute;

        z-index: 500;

        margin: 2px 0px 0px 2px;

        /*border: 1px #F00 solid;*/

        display: none;

    }



    #visor_contenido_bloqueado{

        width: 498px;

        height: 560px;

        /*background: #555555;*/

        position: absolute;

        z-index: 600;

        margin: 1px 0px 0px 1px;

        /*border: 1px #F00 solid;*/

        display: none;

    }



    #visor_ordenserviciopdf{

        width: 100%;

        height: 544px;

        /*border: 1px #F00 solid;*/

        background: #555555;

    }

</style>

<div id="modal_Actividad" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog modal-lg" style="border: 0px #f00 solid; min-width: 800px!important;">

        <div class="modal-content">

            <form enctype="multipart/form-data" method="post" name="form_Actividades" id="form_Actividades">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title">Actualizar actvidad del proyecto</h4>

                </div>

                <div class="modal-body">

                    {!! csrf_field() !!}

                    <div class="row">

                        <div class="col-12">

                            <input type="text" class="form-control" id="actividad_id" name="actividad_id" value="0">

                        </div>

                        <div class="col-3">

                            <div class="row">

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Inicio Programado *</label>

                                         <input type="text" class="form-control FechaIncioPrograma" id="programa_InicioPrograma" name="programa_InicioPrograma" required>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Fin Programado *</label>

                                        <input type="text" class="form-control FechaFinalPrograma" id="programa_FinPrograma" name="programa_FinPrograma" required>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Inicio Real *</label>

                                         <input type="text" class="form-control FechaIncioReal" id="programa_InicioReal" name="programa_InicioReal">

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Fin Real </label>

                                        <input type="text" class="form-control FechaFinalReal" id="programa_FinReal" name="programa_FinReal">

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-9">

                            <div class="row">

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Actividad </label>

                                        <input type="text" class="form-control" id="programa_Actividad" name="programa_Actividad" readonly="readonly">

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="row">

                                        <div class="col-6">

                                            <div class="form-group">

                                                <label>Evidencia PDF (Opcional)</label>

                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">

                                                    <div class="form-control" data-trigger="fileinput" id="campo_file_ordenservicio">

                                                        <i class="fa fa-file fileinput-exists"></i>

                                                        <span class="fileinput-filename"></span>

                                                    </div>

                                                    <span class="input-group-addon btn btn-secondary btn-file"> 

                                                        <span class="fileinput-new">Seleccione</span>

                                                        <span class="fileinput-exists">Cambiar</span>

                                                        <input type="file" accept="application/pdf" id="Archivos" name="Archivos">

                                                    </span>

                                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>

                                                </div>

                                            </div>

                                        </div>

                                        

                                        <div class="col-6">

                                            <center>

                                                <label>Estatus de la actividad</label>

                                                <div class="switch">

                                                    <label>

                                                        <input type="checkbox" type="checkbox" id="programa_Estatus" name="programa_Estatus" value="1">

                                                        <span class="lever switch-col-light-blue"></span>

                                                        Terminado

                                                    </label>

                                                </div>

                                            </center>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Comentarios </label>

                                        <textarea class="form-control" id="programa_Comentario" name="programa_Comentario"></textarea>

                                    </div>

                                </div>

                            </div>

                        </div>

                        

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_visorcerrar">Cerrar</button>

                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador']))

                        <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproyecto" id="boton_guardar_actividad">

                            Guardar <i class="fa fa-save"></i>

                        </button>

                    @endif

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============================================================== -->

<!-- /MODALES -->

<!-- ============================================================== -->





<!-- ============================================================== -->

<!-- VISOR-MODAL-OT -->

<!-- ============================================================== -->

<style type="text/css" media="screen">

    #modal_visor_ot>.modal-dialog{

        min-width: 1000px !important;

    }



    #visor_menu_bloqueado_ot{

        width: 594px;

        height: 52px;

        background: #555555;

        position: absolute;

        z-index: 500;

        border: 0px #FFF solid;

        /*display: none;*/

    }



    #visor_contenido_bloqueado_ot{

        width: 594px;

        height: 600px;

        /*background: #555555;*/

        position: absolute;

        z-index: 600;

        border: 0px #F00 solid;

        /*display: none;*/

    }



    #visor_documento_ot{

        width: 100%;

        height: 600px;

        border: 2px #DDDDDD solid;

        pointer-events:painted;

    }



    #modal_visor_ot .modal-body .form-group{

        margin: 0px 0px 12px 0px!important;

        padding: 0px!important;

    }



    #modal_visor_ot .modal-body .form-group label{

        margin: 0px!important;

        padding: 0px 0px 3px 0px!important;

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

                                        <textarea  class="form-control" rows="6" id="proyectoordentrabajo_observacionot" name="proyectoordentrabajo_observacionot" required></textarea>

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

                                        <textarea  class="form-control" rows="3" id="proyectoordentrabajo_canceladoobservacion" name="proyectoordentrabajo_canceladoobservacion" required></textarea>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label> Observación (revisiones) *</label>

                                        <textarea  class="form-control" rows="4" id="proyectoordentrabajo_observacionrevision" name="proyectoordentrabajo_observacionrevision" required></textarea>

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

        {{-- <form method="post" enctype="multipart/form-data" name="form_proveedores_ordencompra" id="form_proveedores_ordencompra"> --}}

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title">Lista de proveedores asignados al proyecto</h4>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">

                                <label>Proveedor *</label>

                                <select class="custom-select form-control" id="ordencompra_selectproveedor_id" name="ordencompra_selectproveedor_id" required>

                                    <option value="">&nbsp;</option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>

                    <button type="button" class="btn btn-danger botonguardar_moduloproyecto" id="boton_generar_oc_proveedor">

                        Generar OC para este proveedor <i class="fa fa-user"></i>

                    </button>

                </div>

            </div>

        {{-- </form> --}}

    </div>

</div>

<!-- ============================================================== -->

<!-- VISOR-MODAL-OC-PROVEEDORES -->

<!-- ============================================================== -->





<!-- ============================================================== -->

<!-- VISOR-MODAL-OC -->

<!-- ============================================================== -->

<style type="text/css" media="screen">

    #modal_visor_oc>.modal-dialog{

        min-width: 1000px !important;

    }



    #visor_menu_bloqueado_oc{

        width: 594px;

        height: 52px;

        background: #555555;

        position: absolute;

        z-index: 500;

        border: 0px #FFF solid;

        /*display: none;*/

    }



    #visor_contenido_bloqueado_oc{

        width: 594px;

        height: 600px;

        /*background: #555555;*/

        position: absolute;

        z-index: 600;

        border: 0px #F00 solid;

        /*display: none;*/

    }



    #visor_documento_oc{

        width: 100%;

        height: 600px;

        border: 2px #DDDDDD solid;

        pointer-events:painted;

    }



    #modal_visor_oc .modal-body .form-group{

        margin: 0px 0px 12px 0px!important;

        padding: 0px!important;

    }



    #modal_visor_oc .modal-body .form-group label{

        margin: 0px!important;

        padding: 0px 0px 3px 0px!important;

    }

</style>

<div id="modal_visor_oc" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog modal-lg" style="min-width: 1200px!important;">

        <form method="post" enctype="multipart/form-data" name="form_ordencompra" id="form_ordencompra">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title" id="nombre_documento_visor_oc"></h4>

                </div>

                <div class="modal-body"> {{-- style="background: #555555;" --}}

                    <div class="row">

                        <div class="col-6">

                            {{-- <div id="visor_menu_bloqueado_oc"></div> --}}

                            {{-- <div id="visor_contenido_bloqueado_oc"></div> --}}

                            <iframe src="/assets/images/cargando.gif" name="visor_documento_oc" id="visor_documento_oc"></iframe>

                        </div>

                        <div class="col-6">

                            <div class="row">

                                <div class="col-12">

                                    <div class="form-group">

                                        {!! csrf_field() !!}

                                        <input type="hidden" class="form-control" id="ordencompra_id" name="ordencompra_id" value="0">

                                        <input type="hidden" class="form-control" id="ordencompra_proveedor_id" name="proveedor_id" value="0">

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

                                        <textarea  class="form-control" rows="3" id="proyectoordencompra_observacionoc" name="proyectoordencompra_observacionoc"></textarea>

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

                                        <textarea  class="form-control" rows="3" id="proyectoordencompra_canceladoobservacion" name="proyectoordencompra_canceladoobservacion" required></textarea>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="form-group">

                                        <label> Observación (revisión) *</label>

                                        <textarea  class="form-control" rows="3" id="proyectoordencompra_observacionrevision" name="proyectoordencompra_observacionrevision" required></textarea>

                                    </div>

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

                            <input type="radio" class="with-gap radio-col-deep-orange" name="proyectoordencompra_tipolista" id="proyectoordencompra_tipolista_0" value="0"/>

                            <label for="proyectoordencompra_tipolista_0">Lista de proveedores</label>

                            <input type="radio" class="with-gap radio-col-deep-orange" name="proyectoordencompra_tipolista" id="proyectoordencompra_tipolista_1" value="1"/>

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

    #visor_documento_ls{

        width: 100%;

        height: 600px;

        border: 2px #DDDDDD solid;

        pointer-events: painted;

    }



    #modal_signatarioslista .modal-body .form-group{

        margin: 0px 0px 12px 0px!important;

        padding: 0px!important;

    }



    #modal_signatarioslista .modal-body .form-group label{

        margin: 0px!important;

        padding: 0px 0px 3px 0px!important;

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

                                        <textarea  class="form-control" rows="8" id="proyectosignatario_canceladoobservacion" name="proyectosignatario_canceladoobservacion" required></textarea>

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

    #visor_documento_le{

        width: 100%;

        height: 600px;

        border: 2px #DDDDDD solid;

        pointer-events: painted;

    }



    #modal_equiposlista .modal-body .form-group{

        margin: 0px 0px 12px 0px!important;

        padding: 0px!important;

    }



    #modal_equiposlista .modal-body .form-group label{

        margin: 0px!important;

        padding: 0px 0px 3px 0px!important;

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

                                        <textarea  class="form-control" rows="8" id="proyectoequipo_canceladoobservacion" name="proyectoequipo_canceladoobservacion" required></textarea>

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

                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'Externo']))

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

                                <label> Foto (del punto fuera de norma) *</label>

                                <style type="text/css" media="screen">

                                    .dropify-wrapper {

                                        height: 296px!important; /*tamaño estatico del campo foto*/

                                    }

                                </style>

                                <input type="file" class="dropify" accept="image/jpeg,image/x-png" id="inputevidenciafotofisicos" name="inputevidenciafotofisicos" data-allowed-file-extensions="jpg png JPG PNG" data-height="296" data-default-file="" onchange="redimencionar_fotoevidencia();" required>

                            </div>

                        </div>

                        <div class="col-5 divevidencia_seccion_fotosfisicos">

                            <div class="row">

                                <div class="col-12">

                                    <div class="form-group">

                                        <label>Numero del punto (fuera de norma) *</label>

                                        <input type="number" class="form-control" id="proyectoevidenciafoto_nopunto" name="proyectoevidenciafoto_nopunto" required>

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

                        <div class="col-12 divevidencia_seccion_fotosquimicos">

                            <div class="form-group">

                                <label>Nombre de la carpeta *</label>

                                <input type="text" class="form-control" id="proyectoevidenciafoto_carpeta" name="proyectoevidenciafoto_carpeta" placeholder="Ejem: Medición de químicos en el área de motocompresoras" required>

                            </div>

                        </div>

                        <div class="col-12 divevidencia_seccion_fotosquimicos">

                            <div class="form-group">

                                <label>Fotos (maximo 20) *</label>

                                <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif" placeholder="Maximo 20 fotos" id="inputevidenciafotosquimicos" name="inputevidenciafotosquimicos[]" onchange="valida_totalfotos_quimicos(this);" required>

                            </div>

                        </div>

                        <div class="col-12 divevidencia_seccion_fotosquimicos">

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

                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'Externo']))

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

                    @if (auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Psicólogo', 'Ergónomo', 'CoordinadorHI', 'Externo']))

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