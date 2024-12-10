<style type="text/css">
    .reporte_bei {
        font-size: 14px !important;
        line-height: 14px;
    }

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



    .card-title {
        margin: 20px 0px 10px 0px;
        color: blue;
    }

    .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }

    table {
        width: 100%;
        margin: 0px;
        font-family: inherit;
    }

    table th {
        padding: 1px 2px;
        color: #777777;
    }

    table td.justificado {
        padding: 4px !important;
        text-align: justify !important;
    }

    p.justificado {
        text-align: justify !important;
        margin: 0px !important;
        padding: 0px !important;
    }

    textarea {
        text-align: justify !important;
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

<div class="row" class="reporte_bei">
    {{-- <div class="col-xlg-2 col-lg-3 col-md-5">
        <div class="stickyside">
            <div class="list-group" id="top-menu">
                <a href="#0" class="list-group-item active">Portadas <i class="fa fa-times" id="menureporte_0"></i></a>
                <a href="#1" class="list-group-item">1.- Introducción <i class="fa fa-times" id="menureporte_1"></i></a>
                <a href="#2" class="list-group-item">2.- Definiciones <i class="fa fa-times" id="menureporte_2"></i></a>
                <a href="#3" class="list-group-item">3.- Objetivos</a>
                <a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa fa-times" id="menureporte_3_1"></i></a>
                <a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa fa-times" id="menureporte_3_2"></i></a>
                <a href="#4" class="list-group-item">4.- Metodología</a>
                <a href="#4_1" class="list-group-item submenu">4.1.- Reconocimiento y evaluacion de los agentes químicos <i class="fa fa-times" id="menureporte_4_1"></i></a>
                <a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación <i class="fa fa-times" id="menureporte_4_2"></i></a>

                <a href="#5" class="list-group-item">5.- Reconocimiento</a>
                <a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
                <a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
                <a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
                <a href="#5_5" class="list-group-item submenu">5.4.- Equipo de Proteción Personal (EPP) <i class="fa fa-times" id="menureporte_5_4"></i></a>
                <a href="#5_4" class="list-group-item submenu">5.5.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_5"></i></a>
                <a href="#6" class="list-group-item">6.- Evaluación</a>
                <a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
                <a href="#7" class="list-group-item">7.- Resultados</a>
                <a href="#7_1" class="list-group-item submenu">7.1.- Resultados <i class="fa fa-times" id="menureporte_7_1"></i></a>
                <a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
                <a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
                <a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
                <a href="#11" class="list-group-item">11.- Anexos</a>
                <a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
                <a href="#11_3" class="list-group-item submenu">11.2.- Anexo 2: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_2"></i></a>
                <a href="#11_6" class="list-group-item submenu">11.3.- Anexo 3: Informe de resultados <i class="fa fa-times" id="menureporte_11_3"></i></a>
                <a href="#12" class="list-group-item submenu">Generar informe <i class="fa fa-download text-success" id="menureporte_12"></i></a>
            </div>
        </div>
    </div> --}}


    <div class="col-xlg-12 col-lg-9 col-md-7">
        <div class="card">
            <div class="card-body">

                <div class=" mt-4 text-center">
                    <h3 class="card-title mb-5" style="padding: 0px!important;" id="0_1">Seleccione el tipo de mapa de riesgos</h3>
                    <form method="post" enctype="multipart/form-data" name="form_tipomapa" id="form_tipomapa">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                        <input type="hidden" id="ID_TIPO_MAPA" name="ID_TIPO_MAPA" value="0">
    
                        <div class="checkbox-container" style="display: flex; justify-content: center; flex-wrap: wrap;">
                            <div style="margin-right: 20px;">
                                <input type="checkbox" id="circular" name="circular">
                                <label for="circular">Circular</label>
                            </div>
                            <div style="margin-right: 20px;">
                                <input type="checkbox" id="matriz" name="matriz">
                                <label for="matriz">Matriz</label>
                            </div>
                            <div style="margin-right: 20px;">
                                <input type="checkbox" id="matcir" name="matcir">
                                <label for="matcir">Circular y Matriz</label>
                            </div>
                          
                        </div>
                        <div class="col-12" style="text-align: center; margin-top: 20px;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light " id="botonguardar_reporte_evaluaraire">
                                Guardar <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </form>
                </div>



                <h4 class="card-title" id="5_4"></h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                            <h3 style="color: #ffffff; margin: 0;">&nbsp;Evidencia</h3> 
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva evidencia" id="boton_nueva_evidencia">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva evidencia
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_evidencia">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="80">Fecha evidencia</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
                                    <th width="60">Pdf</th>

								</tr>
							</thead>
							<tbody></tbody>
						</table><br>
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
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalvisor_reportebei">Cerrar</button>
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
                                <input type="number" min="0" max="100" class="form-control" id="reportebeiarea_porcientooperacion" name="reportebeiarea_porcientooperacion" value="100" required>
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
                                    <input class="form-check-input" type="radio" name="aplica_bei" id="aplica_bei_si" value="1" required="required" checked>
                                    <label class="form-check-label" for="aplica_bei_si">
                                        Si
                                    </label>
                                </div>
                                <div class="form-check mx-4">
                                    <input class="form-check-input" type="radio" name="aplica_bei" id="aplica_bei_no" value="0" required="required">
                                    <label class="form-check-label" for="aplica_bei_no">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>

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
    #modal_reporte_beipunto>.modal-dialog {
        min-width: 90% !important;
    }

    #modal_reporte_beipunto .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_reporte_beipunto .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_reporte_beipunto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_reporte_beipunto" id="form_reporte_beipunto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Resultado BEI</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_BEI_INFORME" name="ID_BEI_INFORME" value="0">
                        </div>
                        <div class="col-12 text-center">
                            <h3 id="nombre_determinante">Determinante</h3>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>No. Punto</label>
                                <input type="number" class="form-control" id="NUM_PUNTO_BEI" name="NUM_PUNTO_BEI" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Área *</label>
                                <select class="custom-select form-control" id="AREA_ID_BEI" name="AREA_ID" onchange="consultarAreasCategoriasRecsensorial(this.value, 0);" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Categoría *</label>
                                <select class="custom-select form-control" id="CATEGORIA_ID_BEI" name="CATEGORIA_ID" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" class="form-control" id="NOMBRE_BEI" name="NOMBRE_BEI" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Genero *</label>
                                <select class="custom-select form-control" id="GENERO_BEI" name="GENERO_BEI" required>
                                    <option value=""></option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Ficha*</label>
                                <input type="text" class="form-control" id="FICHA_BEI" name="FICHA_BEI" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Edad *</label>
                                <input type="text" class="form-control" id="EDAD_BEI" name="EDAD_BEI" required readonly>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Antigüedad Laboral *</label>
                                <input type="text" class="form-control" id="ANTIGUEDAD_BEI" name="ANTIGUEDAD_BEI" required readonly>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Numero de muestra *</label>
                                <input type="text" class="form-control" id="MUESTRA_BEI" name="MUESTRA_BEI" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Unidad de medida *</label>
                                <input type="text" class="form-control" id="UNIDAD_MEDIDA_BEI" name="UNIDAD_MEDIDA_BEI">

                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Resultado *</label>
                                <input type="text" class="form-control" id="RESULTADO_BEI" name="RESULTADO_BEI" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Valor de referencia *</label>
                                <input type="text" class="form-control" id="REFERENCIA_BEI" name="REFERENCIA_BEI" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_beipunto">Guardar <i class="fa fa-save"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-ÁREA -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- MODAL-REPORTE-EQUIPO DE PROTECCION PERSONAL -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_reporte_epp>.modal-dialog {
        min-width: 800px !important;
    }

    #modal_reporte_epp .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_reporte_epp .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_reporte_epp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_modal_epp" id="form_modal_epp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Titulo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="reporteepp_id" name="reporteepp_id" value="0">
                        </div>
                        <div class="table-responsive" style="max-height: 410px!important;">
                            <table class="table table-hover stylish-table" width="100%" id="tabla_lista_epp_bei">
                                <thead>
                                    <tr>
                                        <th style="max-width: 48%!important;">Parte del cuerpo *</th>
                                        <th style="max-width: 48%!important;">Equipo de protección personal básico proporcionado *</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_epp">Guardar <i class="fa fa-save"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EQUIPO DE PROTECCION PERSONAL -->
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


{{-- Amcharts --}}
{{-- <link href="/assets/plugins/c3-master/c3.min.css" rel="stylesheet">
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
<script src="/assets/plugins/amChart/amcharts/themes/patterns.js" type="text/javascript"></script> --}}

<script type="text/javascript">
    var proyecto = <?php echo json_encode($proyecto); ?>;
    var recsensorial = <?php echo json_encode($recsensorial); ?>;
  
</script>

<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reportemapa.js?v=1.0"></script>