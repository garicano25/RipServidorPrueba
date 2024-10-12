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


<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card mt-4">
            <!-- Menu tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_1" role="tab" id="tab_tabla_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-list"></i></span>
                        <span class="hidden-xs-down">Lista de Ejecuciones</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_2" role="tab" id="tab_info_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Aplicación de NOM-035-STPS-2018</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_3" role="tab" id="tab_evidencias_ejecucion">
                        <span class="hidden-sm-up"><i class="ti-archive"></i></span>
                        <span class="hidden-xs-down">Evidencia fotográfica</span></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane p-20 active" id="tab_1" role="tabpanel">
                    <div class="table-responsive">
                        <ol class="breadcrumb m-b-10">
                            <h2 style="color: #ffff; margin: 0;"><i class="fa fa-cogs"></i> Ejecuciones </h2>
                        </ol>
                        <table class="table table-hover stylish-table" id="tabla_ejecucion" width="100%">
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
                <div class="tab-pane p-20" id="tab_2" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body m-t-10" style="padding: 6px 10px">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-suitcase"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_proyecto">FOLIO PROYECTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Folio</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_reconocimiento">FOLIO RECONOCIMIENTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Reconocimiento</small>
                                                    </td>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-eercast"></i></span>
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

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body m-t-10" style="padding: 10px;">
                                        <h3 style="color: #9C9C9C; text-align: center;">
                                            <i class="fa fa-calendar-o" aria-hidden="true"></i> Ajustar plazo de tiempo para presentar las GUIAS
                                        </h3>                                        
                                        <div class="form-group m-t-30 m-r-30 m-l-30" style="display: flex; align-items: center; gap: 5px;">
                                            <div class="input-group">
                                                <label style="margin-right: 5px;">Fecha de inicio:</label>
                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" 
                                                    style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 250px;">
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                            <div class="input-group">
                                                <label style="margin-right: 5px;">Fecha de fin:</label>
                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" 
                                                    style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 250px;">
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>

                                            <button type="submit" class="btn btn-success botonguardar_modalidad_online" id="botonguardar_modalidad_online" style="margin-right: 10px;">
                                                Actualizar fechas de TODOS los trabajadores  <i class="fa fa-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!--form panels-->
                            <div class="col-12" style="text-align: center;">
                                <ol class="breadcrumb m-b-10 p-t-10">
                                    <h2 style="color: #ffffff">
                                        <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad online
                                    </h2>
                                    <div style="display: flex; justify-content: flex-end;">
                                        <button type="submit" class="btn btn-light botonguardar_modalidad_online" id="botonguardar_modalidad_online" style="margin-right: 10px;">
                                            Guardar cambios  <i class="fa fa-save"></i>
                                        </button>
                                    </div>
                                </ol>
                                


                                <div class="table-responsive">
                                    <table class="table table-hover stylish-table" width="100%" id="tabla_trabajadores_online">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de inicio</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de fin</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado del correo</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Enviar link del cuestionario</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>  
                        &nbsp 
                        <div class="col-12" style="text-align: center;">
                            <div>
                                <ol class="breadcrumb m-b-10 green-breadcrumb">
                                    <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad presencial</h2>
                                </ol>
                                <div class="table-responsive">
                                    <table class="table table-hover stylish-table" width="100%" id="tabla_trabajadores_presencial">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de aplicación</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Carga de cuestionarios</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>   
                        
                    </div>
                </div>
                <div class="tab-pane p-20" id="tab_3" role="tabpanel">
                    <div class="card wizard-content" style="border: none; box-shadow: 0 0 0;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body m-t-10" style="padding: 6px 10px">
                                        <table class="table" style="border: 0px #000 solid; margin: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-suitcase"></i></span>
                                                    </td>
                                                    <td width="auto" style="text-align: left; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_proyecto">FOLIO PROYECTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Folio</small>
                                                    </td>
                                                    <td width="auto" style="text-align: right; border: none; vertical-align: middle;">
                                                        <h4 style="margin: 0px;"><a class="text-success div_folio_reconocimiento">FOLIO RECONOCIMIENTO</a></h4>
                                                        <small style="color: #AAAAAA; font-size: 12px;">Reconocimiento</small>
                                                    </td>
                                                    <td width="40" style="text-align: center; border: none;">
                                                        <span class="btn btn-success btn-circle"><i class="fa fa-eercast"></i></span>
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

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body m-t-10" style="padding: 10px;">
                                        <h3 style="color: #9C9C9C; text-align: center;">
                                            <i class="fa fa-calendar-o" aria-hidden="true"></i> Evidencias fotográficas
                                        </h3>                                        
                                        <div class="form-group m-t-30 m-r-30 m-l-30" style="display: flex; align-items: center; gap: 5px;">
                                            <div class="input-group">
                                                <label style="margin-right: 5px;">Fecha de inicio:</label>
                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" 
                                                    style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 250px;">
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                            <div class="input-group">
                                                <label style="margin-right: 5px;">Fecha de fin:</label>
                                                <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" 
                                                    style="border-top-left-radius: 5px; border-bottom-left-radius: 5px; border-right: none; max-width: 250px;">
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>

                                            <button type="submit" class="btn btn-success botonguardar_modalidad_online" id="botonguardar_modalidad_online" style="margin-right: 10px;">
                                                Actualizar fechas de TODOS los trabajadores  <i class="fa fa-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!--form panels-->
                            <div class="col-12" style="text-align: center;">
                                <ol class="breadcrumb m-b-10 p-t-10">
                                    <h2 style="color: #ffffff">
                                        <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad online
                                    </h2>
                                    <div style="display: flex; justify-content: flex-end;">
                                        <button type="submit" class="btn btn-light botonguardar_modalidad_online" id="botonguardar_modalidad_online" style="margin-right: 10px;">
                                            Guardar cambios  <i class="fa fa-save"></i>
                                        </button>
                                    </div>
                                </ol>
                                


                                <div class="table-responsive">
                                    <table class="table table-hover stylish-table" width="100%" id="tabla_trabajadores_online">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de inicio</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de fin</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado del correo</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Enviar link del cuestionario</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>  
                        &nbsp 
                        <div class="col-12" style="text-align: center;">
                            <div>
                                <ol class="breadcrumb m-b-10 green-breadcrumb">
                                    <h2 style="color: #ffff; margin: 0;"> <i class="fa fa-braille" aria-hidden="true"></i> Trabajadores modalidad presencial</h2>
                                </ol>
                                <div class="table-responsive">
                                    <table class="table table-hover stylish-table" width="100%" id="tabla_trabajadores_presencial">
                                        <thead>
                                            <tr>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">No. Orden</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Nombre completo del trabajador</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Fecha de aplicación</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Estado de cuestionario</th>
                                                <th class="sorting_disabled text-center" rowspan="1" colspan="1">Carga de cuestionarios</th>
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
        <div>
            <!-- End Tab panes -->
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Inicio modales -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- Fin modales -->
<!-- ============================================================== -->


@endsection