<style type="text/css">
    .reporte_alimentos {
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

<div class="row" class="reporte_alimentos">
    <div class="col-xlg-2 col-lg-3 col-md-5">
        <div class="stickyside">
            <div class="list-group" id="top-menu">
                <a href="#0" class="list-group-item active">Portada <i class="fa fa-times" id="menureporte_0"></i></a>
                <a href="#1" class="list-group-item">1.- Introducción <i class="fa fa-times" id="menureporte_1"></i></a>
                <a href="#2" class="list-group-item">2.- Definiciones <i class="fa fa-times" id="menureporte_2"></i></a>
                <a href="#3" class="list-group-item">3.- Objetivos</a>
                <a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa fa-times" id="menureporte_3_1"></i></a>
                <a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa fa-times" id="menureporte_3_2"></i></a>
                <a href="#4" class="list-group-item">4.- Metodología para evaluación en alimentos</a>
                <a href="#4_1" class="list-group-item submenu">4.1.- Reconocimiento <i class="fa fa-times" id="menureporte_4_1"></i></a>
                <a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación <i class="fa fa-times" id="menureporte_4_2"></i></a>
                <a href="#5" class="list-group-item">5.- Metodología para análisis de superficies inertes y superficies para el manejo de alimentos</a>
                <a href="#5_1" class="list-group-item submenu">5.1.- Reconocimiento <i class="fa fa-times" id="menureporte_5_1"></i></a>
                <a href="#5_2" class="list-group-item submenu">5.2.- Método de evaluación <i class="fa fa-times" id="menureporte_5_2"></i></a>
                <a href="#6" class="list-group-item">6.- Reconocimiento</a>
                <a href="#6_1" class="list-group-item submenu">6.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_6_1"></i></a>
                <a href="#7" class="list-group-item">7.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_7"></i></a>
                <a href="#8" class="list-group-item">8.- Resultados </a>

                <!-- Adicionales -->
                <a href="#8_1" class="list-group-item submenu">8.1.- Resultados, calidad e inocuidad de alimentos <i class="fa fa-times" id="menureporte_8_1"></i></a>
                <a href="#8_2" class="list-group-item submenu">8.2.- Resultados, evaluación de superficies vivas <i class="fa fa-times" id="menureporte_8_2"></i></a>
                <a href="#8_3" class="list-group-item submenu">8.3.- Resultados, evaluación de superficies inertes <i class="fa fa-times" id="menureporte_8_3"></i></a>
                <!-- Adicionales -->


                <a href="#9" class="list-group-item">9.- Análisis de los resultados </a>

                <!-- Adicionales -->
                <a href="#9_1" class="list-group-item submenu">9.1.- Calidad e inocuidad de alimentos <i class="fa fa-times" id="menureporte_9_1"></i></a>
                <a href="#9_2" class="list-group-item submenu">9.2.- Superficies vivas <i class="fa fa-times" id="menureporte_9_2"></i></a>
                <a href="#9_3" class="list-group-item submenu">9.3.- Superficies inertes <i class="fa fa-times" id="menureporte_9_3"></i></a>
                <!-- Adicionales -->

                <a href="#10" class="list-group-item">10.- Conclusiones <i class="fa fa-times" id="menureporte_10"></i></a>
                <a href="#11" class="list-group-item">11.- Recomendaciones de control <i class="fa fa-times" id="menureporte_11"></i></a>
                <a href="#12" class="list-group-item">12.- Responsables del informe <i class="fa fa-times" id="menureporte_12"></i></a>
                <a href="#13" class="list-group-item">13.- Anexos</a>
                <a href="#13_1" class="list-group-item submenu">13.1.- Anexo 1: Resultados de laboratorio <i style="color: #64bd44;" class="fa fa-check" id="menureporte_13_1"></i></a>
                <a href="#13_2" class="list-group-item submenu">13.2.- Anexo 2: Información de los contaminantes evaluados <i style="color:#64bd44" class="fa fa-check" id="menureporte_13_2"></i></a>
                <a href="#13_3" class="list-group-item submenu">13.3.- Anexo 3: Memoria fotográfica <i class="fa fa-times" id="menureporte_13_3"></i></a>
                <a href="#13_4" class="list-group-item submenu">13.4.- Anexo 4: Copia de acreditación del laboratorio de ensayo ante la ema <i class="fa fa-times" id="menureporte_13_4"></i></a>
                <a href="#13_5" class="list-group-item submenu">13.5.- Anexo 5: Planos de ubicación de los puntos de muestreo <i class="fa fa-times" id="menureporte_13_5"></i></a>
                <a href="#14" class="list-group-item submenu">Generar informe <i class="fa fa-download text-success" id="menureporte_14"></i></a>
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
                                        <select class="custom-select form-control" id="reportealimentos_mes" name="reportealimentos_mes">
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
                                        <select class="custom-select form-control" id="reportealimentos_fecha" name="reportealimentos_fecha">
                                            <option value="" selected disabled></option>
                                            <script>
                                                $(document).ready(function() {
                                                    const currentYear = new Date().getFullYear();
                                                    for (let year = currentYear; year >= 2017; year--) {
                                                        $('#reportealimentos_fecha').append(new Option(year, year));
                                                    }
                                                });
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
                <form method="post" enctype="multipart/form-data" name="form_reporte_introduccion" id="form_reporte_introduccion">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! csrf_field() !!}
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reportealimentos_introduccion" name="reportealimentos_introduccion" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_introduccion">Guardar introducción <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>

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
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reportealimentos_objetivogeneral" name="reportealimentos_objetivogeneral" required></textarea>
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
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reportealimentos_objetivoespecifico" name="reportealimentos_objetivoespecifico" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivoespecifico">Guardar objetivos específicos <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>
                <h4 class="card-title" id="4">4.- Metodología</h4>
                <h4 class="card-title" id="4_1">4.1.- Reconocimiento</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_1" id="form_reporte_metodologia_4_1">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! csrf_field() !!}
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reportealimentos_metodologia_4_1" name="reportealimentos_metodologia_4_1" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>
                <h4 class="card-title" id="4_2">4.2.- Método de evaluación</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2" id="form_reporte_metodologia_4_2">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! csrf_field() !!}
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reportealimentos_metodologia_4_2" name="reportealimentos_metodologia_4_2" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>

                <h4 class="card-title" id="5">5.- Metodología para análisis de superficies inertes y superficies para el
                    manejo de alimentos
                </h4>
                <h4 class="card-title" id="5_1">5.1.- Reconocimiento</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_5_1" id="form_reporte_5_1">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! csrf_field() !!}
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reportealimentos_metodologia_5_1" name="reportealimentos_metodologia_5_1" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reportealimentos_5_1">Guardar reconocimiento punto 5.1 <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </form>

                <h4 class="card-title" id="5_2">5.2.- Método de evaluación</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_5_2" id="form_reporte_5_2">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Método de evaluación</label>
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reportealimentos_metodologia_5_2" name="reportealimentos_metodologia_5_2" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reportealimentos_5_2">Guardar Método de evaluación punto 5.2 <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

                <h4 class="card-title" id="6">6.- Reconocimiento</h4>
                <h4 class="card-title" id="6_1">6.1.- Ubicación de la instalación</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_ubicacion" id="form_reporte_ubicacion">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    {!! csrf_field() !!}
                                    <textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reportealimentos_ubicacioninstalacion" name="reportealimentos_ubicacioninstalacion" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
                            <input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reportealimentosubicacionfoto" name="reportealimentosubicacionfoto" onchange="redimencionar_mapaubicacion();" required>
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

                <h4 class="card-title" id="7">7.- Descripción del proceso en la instalación</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_procesoinstalacion" id="form_reporte_procesoinstalacion">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Descripción del proceso en la instalación</label>
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reportealimentos_procesoinstalacion" name="reportealimentos_procesoinstalacion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Descripción de la actividad principal de la instalación</label>
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="7" id="reportealimentos_actividadprincipal" name="reportealimentos_actividadprincipal" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_procesoinstalacion">Guardar proceso instalación <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>


                <h4 class="card-title" id="8">8.- Resultados</h4>
                <h4 class="card-title" id="8_1">8.1.- Resultados de calidad e inocuidad de alimentos</h4>
                <span>A continuación, se presentan de manera detallada los resultados obtenidos en la evaluación de la calidad e inocuidad de alimentos respecto a los parámetros microbiólogicos y organolépticos por punto de muestreo:</span>
                <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Resultados, calidad e inocuidad de alimentos" id="boton_reporte_nuevoalimentospunto8_1">
                        <span class="btn-label"><i class="fa fa-plus"></i></span>Resultado
                    </button>
                </ol>
                <table class="table-hover tabla_info_centrado mb-1" width="100%" id="tabla_resultado_8_1">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Área</th>
                            <th width="">Fecha de medición</th>
                            <th width="80">Parametro</th>
                            <th width="80">Método de análisis</th>
                            <th width="60">Ubicación</th>
                            <th width="60">No. de trabajadores expuestos</th>
                            <th width="60">Concentración obtenida</th>
                            <th width="60">Concentración permisible</th>
                            <th width="60">Cumplimiento normativo</th>
                            <th width="60">Editar</th>
                            <th width="60">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
                <table class="table-hover tabla_info_centrado mt-1" width="100%" id="tabla_resultado_8_1_1">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Área</th>
                            <th width="80">Fecha de medición</th>
                            <th width="100">Parametro</th>
                            <th width="80">Unidades</th>
                            <th width="120">Método de análisis</th>
                            <th width="150">Ubicación</th>
                            <th width="60">No. de trabajadores expuestos</th>
                            <th width="100">Concentración obtenida</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <span><b>Nota aclaratoria:</b> No existe una norma espécifica para la comparación de las concentraciones obtenidas de los parámetros físicos y organolépticos de alimentos, por lo tanto, no se puede determinar un cumplimiento normativo.</span>

                <h4 class="card-title mt-5" id="8_2">8.2.- Resultados, evalución de superficies vivas</h4>
                <span>A continuación, se presentan de manera detallada los resultados obtenidos en la evaluación de superficies vivas respecto a los parámetros microbiológicos por punto de muestreo:</span>
                <ol class="breadcrumb mt-1" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Resultados, evalución de superficies vivas" id="boton_reporte_nuevoalimentospunto8_2">
                        <span class="btn-label"><i class="fa fa-plus"></i></span> Resultado
                    </button>
                </ol>
                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_resultado_8_2">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Area</th>
                            <th width="">Fecha de medición</th>
                            <th width="80">Parametro</th>
                            <th width="80">Método de análisis</th>
                            <th width="80">Ubicación</th>
                            <th width="80">No. de trabajadores expuestos</th>
                            <th width="80">Concentración obtenida</th>
                            <th width="80">Concentración permisible</th>
                            <th width="80">Cumplimiento normativo</th>
                            <th width="60">Editar</th>
                            <th width="60">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <h4 class="card-title mt-5" id="8_3">8.3.- Resultados, evalución de superficies inertes</h4>
                <span>A continuación, se presentan de manera detallada a los resultados obtenidos en la evaluación de la calidad de superficies inertes respecto a los parámetros microbiológicos por punto de muestreo:</span>
                <ol class="breadcrumb mt-1" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Resultados, evalución de superficies inertes" id="boton_reporte_nuevoalimentospunto8_3">
                        <span class="btn-label"><i class="fa fa-plus"></i></span> Resultado
                    </button>
                </ol>
                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_resultado_8_3">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Area</th>
                            <th width="">Fecha de medición</th>
                            <th width="80">Parametro</th>
                            <th width="80">Método de análisis</th>
                            <th width="80">Ubicación</th>
                            <th width="80">No. de trabajadores expuestos</th>
                            <th width="80">Concentración obtenida</th>
                            <th width="80">Concentración permisible</th>
                            <th width="80">Cumplimiento normativo</th>
                            <th width="60">Editar</th>
                            <th width="60">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <h4 class="card-title mt-5" id="9">9.- Análisis de los resultados</h4>
                <h4 class="card-title" id="9_1">9.1.- Calidad e inocuidad de alimentos</h4>
                <span>Durante la evaluación se obtuvieron los siguientes resultados de los parámetros microbiológicos:</span>
                <!-- <ol class="breadcrumb mt-2" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Calidad e inocuidad de alimentos" id="boton_reporte_nuevoalimentospunto9_1">
                        <span class="btn-label"><i class="fa fa-plus"></i></span> Resultado
                    </button>
                </ol> -->
                <table class="table-hover tabla_info_centrado mb-5" width="100%" id="tabla_resultado_9_1">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Párametro</th>
                            <th width="150">Ubicación</th>
                            <th width="90">Concentración obtenida</th>
                            <th width="90">Concentración permisible</th>
                            <th width="150">Cumplimiento normativo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>


                <span>Durante la evaluación se obtuvieron los siguientes resultados de los parámetros físicos y arganolépticos:</span>
                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_resultado_9_1_1">
                    <thead>
                        <tr>
                            <th width="60">Punto</th>
                            <th width="150">Parámetro</th>
                            <th width="150">Ubicación</th>
                            <th width="90">Concentración obtenida</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <span><b>Nota aclaratoria:</b> No existe una norma espécifica para la comparación de las concentraciones obtenidas de los parámetros físicos y organolépticos de alimentos, por lo tanto, no se puede determinar un cumplimiento normativo.</span>

                <h4 class="card-title mt-5" id="9_2">9.2.- Superficies vivas</h4>
                <span>Durante la evalución se obtuviueron los siguientes resultados de los parámetros microbiológicos:</span>
                <!-- <ol class="breadcrumb mt-2" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Superficies vivas" id="boton_reporte_nuevoalimentospunto9_2">
                        <span class="btn-label"><i class="fa fa-plus"></i></span> Resultado
                    </button>
                </ol> -->
                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_resultado_9_2">
                    <thead>
                        <tr>
                            <th width="60">Punto de medición</th>
                            <th width="150">Parámetro</th>
                            <th width="150">Ubicación</th>
                            <th width="80">Concentración obtenida</th>
                            <th width="80">Concentración permisible</th>
                            <th width="130">Cumplimiento normativo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <h4 class="card-title mt-5" id="9_3">9.3.- Superficies inertes</h4>
                <span>Durante la evaluación se obtuvieron los siguientes resultados de los parámetros microbiológicos:</span>
                <!-- <ol class="breadcrumb mt-2" style="padding: 6px; margin: 10px 0px;">
                    <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Superficies inertes" id="boton_reporte_nuevoalimentospunto9_3">
                        <span class="btn-label"><i class="fa fa-plus"></i></span>Resultado
                    </button>
                </ol> -->
                <table class="table-hover tabla_info_centrado" width="100%" id="tabla_resultado_9_3">
                    <thead>
                        <tr>
                            <th width="60">Punto de medición</th>
                            <th width="150">Parámetro</th>
                            <th width="150">Ubicación</th>
                            <th width="80">Concentración obtenida</th>
                            <th width="80">Concentración permisible</th>
                            <th width="130">Cumplimiento normativo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <h4 class="card-title mt-4" id="10">10.- Conclusiones</h4>
                <select class="custom-select form-control mb-1" style="width: 100%;" id="ID_CATCONCLUSION">
                    <option value="">&nbsp;</option>
                    @foreach($catConclusiones as $dato)
                    <option value="{{$dato->ID_CATCONCLUSION}}" data-descripcion="{{$dato->DESCRIPCION}}">{{$dato->NOMBRE}}</option>
                    @endforeach
                </select>

                <form method="post" enctype="multipart/form-data" name="form_reporte_conclusion" id="form_reporte_conclusion">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reportealimentos_conclusion" name="reportealimentos_conclusion" required></textarea>
                            </div>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

                <h4 class="card-title" id="11">11.- Recomendaciones de control</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_recomendaciones" id="form_reporte_recomendaciones">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                        <div class="col-12">
                            <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                                <button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar nueva recomendación" id="boton_reporte_nuevarecomendacion">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>Nueva recomendación
                                </button>
                            </ol>
                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_recomendaciones">
                                <thead>
                                    <tr>
                                        <th width="60">No.</th>
                                        <th width="60">Activo</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_recomendaciones">Guardar recomendaciones <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

                <h4 class="card-title" id="12">12.- Responsables del informe</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_responsablesinforme" id="form_reporte_responsablesinforme">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="reportealimentos_carpetadocumentoshistorial" name="reportealimentos_carpetadocumentoshistorial">
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Nombre del responsable técnico</label>
                                        <input type="text" class="form-control" id="reportealimentos_responsable1" name="reportealimentos_responsable1" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Cargo del responsable técnico</label>
                                        <input type="text" class="form-control" id="reportealimentos_responsable1cargo" name="reportealimentos_responsable1cargo" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Foto documento del responsable técnico</label>
                                        <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc1"></i>
                                        <input type="hidden" class="form-control" id="reportealimentos_responsable1documento" name="reportealimentos_responsable1documento">
                                        <input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reportealimentosresponsable1documento" name="reportealimentosresponsable1documento" onchange="redimencionar_foto('reportealimentosresponsable1documento', 'reportealimentos_responsable1documento', 'botonguardar_reporte_responsablesinforme');" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Nombre del administrativo prestador de servicio</label>
                                        <input type="text" class="form-control" id="reportealimentos_responsable2" name="reportealimentos_responsable2" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Cargo del administrativo prestador de servicio</label>
                                        <input type="text" class="form-control" id="reportealimentos_responsable2cargo" name="reportealimentos_responsable2cargo" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Foto documento del prestador de servicio</label>
                                        <i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc2"></i>
                                        <input type="hidden" class="form-control" id="reportealimentos_responsable2documento" name="reportealimentos_responsable2documento">
                                        <input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reportealimentosresponsable2documento" name="reportealimentosresponsable2documento" onchange="redimencionar_foto('reportealimentosresponsable2documento', 'reportealimentos_responsable2documento', 'botonguardar_reporte_responsablesinforme');" required>
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

                <h4 class="card-title" id="13">13.- Anexos</h4>
                <h4 class="card-title" id="13_1">13.1.- Anexo 1: Resultados de laboratorio</h4>
                <p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los resultados de laboratorio seran agregados de manera manual.</p>

                <h4 class="card-title mt-4" id="13_2">13.2.- Anexo 2: Información de los contaminantes evaluados</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_informeresultados" id="form_reporte_informeresultados">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                        <div class="col-12">
                            <p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p>
                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_informeresultados">
                                <thead>
                                    <tr>
                                        <th width="60">No.</th>
                                        <th width="70">Seleccionado</th>
                                        <th>Documento</th>
                                        <th width="60">Tipo</th>
                                        <th width="160">Fecha</th>
                                        <th width="60">Mostrar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table><br><br>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_informeresultados">Guardar anexo Información de los contaminantes evaluados <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <h4 class="card-title" id="13_3">13.3.- Anexo 3: Memoria fotográfica</h4>
                <div class="row">
                    <div class="col-12" style="padding-top: 10px;">
                        <p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Se encontraron <span id="memoriafotografica_total">0</span> fotos de los puntos de alimentos que se agregaran al informe.</p>
                    </div>
                </div>

                <h4 class="card-title mt-2" id="13_4">13.4.- Anexo 4: : Copia de acreditación del laboratorio de ensayo ante la ema.</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_anexos" id="form_reporte_anexos">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                        <div class="col-12">
                            <p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p>
                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_anexos">
                                <thead>
                                    <tr>
                                        <th width="60">No.</th>
                                        <th width="60">Seleccionado</th>
                                        <th width="100">Tipo</th>
                                        <th>Entidad</th>
                                        <th width="200">Numero</th>
                                        <th>Área</th>
                                        <th width="160">Vigencia</th>
                                        <th width="60">Certificado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table><br><br>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexos">Guardar anexos 4 <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <h4 class="card-title" id="13_5">13.5.- Anexo 5: Planos de ubicación de los puntos de muestreo.</h4>
                <form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                        </div>
                        <div class="col-12">
                            <p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Solo los planos de las carpetas elegidas aparecerán en el informe de Alimentos.</p>
                            <table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_planos">
                                <thead>
                                    <tr>
                                        <th width="60">Seleccionado</th>
                                        <th>Carpeta</th>
                                        <th width="120">Total planos</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_planos">Guardar planos <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </div>
                </form>


                <h4 class="card-title" id="14">Generar informe .docx + Anexos .Zip</h4>
                <div class="row">
                    <div class="col-12">
                        @if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Coordinador']))
                        <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
                            <button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" title="Nueva revisión" id="boton_reporte_nuevarevision">
                                <span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión
                            </button>
                        </ol>
                        @endif
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
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalvisor_reportealimentos">Cerrar</button>
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
<!-- MODAL-PUNTO-8.1 -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_reporte_punto_8_1>.modal-dialog {
        min-width: 90% !important;
    }

    #modal_reporte_punto_8_1 .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_reporte_punto_8_1 .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_reporte_punto_8_1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_reporte_punto_8_1" id="form_reporte_punto_8_1">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Resultado de calidad e inocuidad de alimentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_PUNTO_ALIMENTOS" name="ID_PUNTO_ALIMENTOS" value="0">
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label>Punto de medición</label>
                                <input type="number" class="form-control" id="PUNTO_MEDICION_ALIMENTOS" name="PUNTO_MEDICION_ALIMENTOS" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Área</label>
                                <input type="text" class="form-control" id="AREA_ALIMENTOS" name="AREA_ALIMENTOS" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Ubicación</label>
                                <input type="text" class="form-control" id="UBICACION_ALIMENTOS" name="UBICACION_ALIMENTOS" required>
                            </div>
                        </div>
                        <hr>
                        <!-- Datos para los resultados de Coliformes Totales -->
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Totales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_ALIMENTOS" id="COLIFORME_TOTALES_ALIMENTOS_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_TOTALES_ALIMENTOS_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_ALIMENTOS" id="COLIFORME_TOTALES_ALIMENTOS_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_TOTALES_ALIMENTOS_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker totales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_ALIMENTOS_TOTALES" name="FECHA_MEDICION_ALIMENTOS_TOTALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control totales" id="UNIDAD_ALIMENTOS_TOTALES" name="UNIDAD_ALIMENTOS_TOTALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control totales" id="METODO_ALIMENTOS_TOTALES" name="METODO_ALIMENTOS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control totales" id="TRABAJADORES_ALIMENTOS_TOTALES" name="TRABAJADORES_ALIMENTOS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control totales" id="CONCENTRACION_ALIMENTOS_TOTALES" name="CONCENTRACION_ALIMENTOS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control totales" id="CONCENTRACION_PERMISIBLE_TOTALES" name="CONCENTRACION_PERMISIBLE_TOTALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos para los resultados de Coliformes Fecales -->
                        <div class="col-12  mt-4 mb-4">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Fecales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_ALIMENTOS" id="COLIFORME_FECALES_ALIMENTOS_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_FECALES_ALIMENTOS_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_ALIMENTOS" id="COLIFORME_FECALES_ALIMENTOS_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_FECALES_ALIMENTOS_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker fecales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_ALIMENTOS_FECALES" name="FECHA_MEDICION_ALIMENTOS_FECALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control fecales" id="UNIDAD_ALIMENTOS_FECALES" name="UNIDAD_ALIMENTOS_FECALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control fecales" id="METODO_ALIMENTOS_FECALES" name="METODO_ALIMENTOS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control fecales" id="TRABAJADORES_ALIMENTOS_FECALES" name="TRABAJADORES_ALIMENTOS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control fecales" id="CONCENTRACION_ALIMENTOS_FECALES" name="CONCENTRACION_ALIMENTOS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control fecales" id="CONCENTRACION_PERMISIBLE_FECALES" name="CONCENTRACION_PERMISIBLE_FECALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Resultado de color -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Párametro </label>
                                <input type="text" class="form-control " id="PARAMETRO_COLOR" name="PARAMETRO_COLOR" value="Color" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Unidades *</label>
                                <select class="custom-select form-control " id="UNIDAD_COLOR" name="UNIDAD_COLOR" required>
                                    <option value=""></option>
                                    <option value="NA">NA</option>
                                    <option value="UFC/g">UFC/g</option>
                                    <option value="NMP/g">NMP/g</option>
                                    <option value="Pt/Co">Pt/Co</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Método de análisis </label>
                                <input type="text" class="form-control " id="METODO_COLOR" name="METODO_COLOR" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Concentración obtenida</label>
                                <input type="text" class="form-control " id="CONCENTRACION_COLOR" name="CONCENTRACION_COLOR" required>
                            </div>
                        </div>

                        <!-- Resultado de Olor -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Párametro </label>
                                <input type="text" class="form-control " id="PARAMETRO_OLOR" name="PARAMETRO_OLOR" value="Olor" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Unidades *</label>
                                <select class="custom-select form-control " id="UNIDAD_OLOR" name="UNIDAD_OLOR" required>
                                    <option value=""></option>
                                    <option value="NA">NA</option>
                                    <option value="UFC/g">UFC/g</option>
                                    <option value="NMP/g">NMP/g</option>
                                    <option value="Pt/Co">Pt/Co</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Método de análisis </label>
                                <input type="text" class="form-control " id="METODO_OLOR" name="METODO_OLOR" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Concentración obtenida</label>
                                <input type="text" class="form-control " id="CONCENTRACION_OLOR" name="CONCENTRACION_OLOR" required>
                            </div>
                        </div>

                        <!-- Resultado de Sabor -->
                        <div class="col-3">
                            <div class="form-group">
                                <label>Párametro </label>
                                <input type="text" class="form-control " id="PARAMETRO_SABOR" name="PARAMETRO_SABOR" value="Sabor" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Unidades *</label>
                                <select class="custom-select form-control " id="UNIDAD_SABOR" name="UNIDAD_SABOR" required>
                                    <option value=""></option>
                                    <option value="NA">NA</option>
                                    <option value="UFC/g">UFC/g</option>
                                    <option value="NMP/g">NMP/g</option>
                                    <option value="Pt/Co">Pt/Co</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Método de análisis </label>
                                <input type="text" class="form-control " id="METODO_SABOR" name="METODO_SABOR" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Concentración obtenida</label>
                                <input type="text" class="form-control " id="CONCENTRACION_SABOR" name="CONCENTRACION_SABOR" required>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_punto_8_1">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL-PUNTO-8.1 -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-PUNTO-8.2 -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_reporte_punto_8_2>.modal-dialog {
        min-width: 90% !important;
    }

    #modal_reporte_punto_8_2 .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_reporte_punto_8_2 .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_reporte_punto_8_2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_reporte_punto_8_2" id="form_reporte_punto_8_2">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Resultado de evaluación de superficies vivas</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_PUNTO_VIVAS" name="ID_PUNTO_VIVAS" value="0">
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label>Punto de medición</label>
                                <input type="number" class="form-control" id="PUNTO_MEDICION_VIVAS" name="PUNTO_MEDICION_VIVAS" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Área</label>
                                <input type="text" class="form-control" id="AREA_VIVAS" name="AREA_VIVAS" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Ubicación</label>
                                <input type="text" class="form-control" id="UBICACION_VIVAS" name="UBICACION_VIVAS" required>
                            </div>
                        </div>
                        <hr>
                        <!-- Datos para los resultados de Coliformes Totales -->
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Totales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_VIVAS" id="COLIFORME_TOTALES_VIVAS_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_TOTALES_VIVAS_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_VIVAS" id="COLIFORME_TOTALES_VIVAS_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_TOTALES_VIVAS_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker vivas_totales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_VIVAS_TOTALES" name="FECHA_MEDICION_VIVAS_TOTALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control vivas_totales" id="UNIDAD_VIVAS_TOTALES" name="UNIDAD_VIVAS_TOTALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                    <option value="UFC/sup.manos">UFC/sup.manos</option>
                                                    <option value="NMP/sup.manos">NMP/sup.manos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control vivas_totales" id="METODO_VIVAS_TOTALES" name="METODO_VIVAS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control vivas_totales" id="TRABAJADORES_VIVAS_TOTALES" name="TRABAJADORES_VIVAS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control vivas_totales" id="CONCENTRACION_VIVAS_TOTALES" name="CONCENTRACION_VIVAS_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control vivas_totales" id="CONCENTRACION_PERMISIBLE_TOTALES" name="CONCENTRACION_PERMISIBLE_TOTALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos para los resultados de Coliformes Fecales -->
                        <div class="col-12  mt-5">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Fecales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_VIVAS" id="COLIFORME_FECALES_VIVAS_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_FECALES_VIVAS_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_VIVAS" id="COLIFORME_FECALES_VIVAS_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_FECALES_VIVAS_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker vivas_fecales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_VIVAS_FECALES" name="FECHA_MEDICION_VIVAS_FECALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control vivas_fecales" id="UNIDAD_VIVAS_FECALES" name="UNIDAD_VIVAS_FECALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                    <option value="UFC/sup.manos">UFC/sup.manos</option>
                                                    <option value="NMP/sup.manos">NMP/sup.manos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control vivas_fecales" id="METODO_VIVAS_FECALES" name="METODO_VIVAS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control vivas_fecales" id="TRABAJADORES_VIVAS_FECALES" name="TRABAJADORES_VIVAS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control vivas_fecales" id="CONCENTRACION_VIVAS_FECALES" name="CONCENTRACION_VIVAS_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control vivas_fecales" id="CONCENTRACION_PERMISIBLE_FECALES" name="CONCENTRACION_PERMISIBLE_FECALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_punto_8_2">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL-PUNTO-8.2 -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-PUNTO-8.3 -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_reporte_punto_8_3>.modal-dialog {
        min-width: 90% !important;
    }

    #modal_reporte_punto_8_3 .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_reporte_punto_8_3 .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_reporte_punto_8_3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_reporte_punto_8_3" id="form_reporte_punto_8_3">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Resultado de evaluación de superficies inertes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}
                            <input type="hidden" class="form-control" id="ID_PUNTO_INERTES" name="ID_PUNTO_INERTES" value="0">
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label>Punto de medición</label>
                                <input type="number" class="form-control" id="PUNTO_MEDICION_INERTES" name="PUNTO_MEDICION_INERTES" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Área</label>
                                <input type="text" class="form-control" id="AREA_INERTES" name="AREA_INERTES" required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Ubicación</label>
                                <input type="text" class="form-control" id="UBICACION_INERTES" name="UBICACION_INERTES" required>
                            </div>
                        </div>
                        <hr>
                        <!-- Datos para los resultados de Coliformes Totales -->
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Totales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_INERTES" id="COLIFORME_TOTALES_INERTES_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_TOTALES_INERTES_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_TOTALES_INERTES" id="COLIFORME_TOTALES_INERTES_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_TOTALES_INERTES_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker inertes_totales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_INERTES_TOTALES" name="FECHA_MEDICION_INERTES_TOTALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control inertes_totales" id="UNIDAD_INERTES_TOTALES" name="UNIDAD_INERTES_TOTALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                    <option value="UFC/sup.manos">UFC/sup.manos</option>
                                                    <option value="NMP/sup.manos">NMP/sup.manos</option>
                                                    <option value="UFC/100 cm2">UFC/100 cm2</option>
                                                    <option value="NMP/100 cm2">NMP/100 cm2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control inertes_totales" id="METODO_INERTES_TOTALES" name="METODO_INERTES_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control inertes_totales" id="TRABAJADORES_INERTES_TOTALES" name="TRABAJADORES_INERTES_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control inertes_totales" id="CONCENTRACION_INERTES_TOTALES" name="CONCENTRACION_INERTES_TOTALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control inertes_totales" id="CONCENTRACION_INERTES_TOTALES_PERMISIBLE" name="CONCENTRACION_PERMISIBLE_TOTALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos para los resultados de Coliformes Fecales -->
                        <div class="col-12  mt-5">
                            <div class="row">
                                <div class="col-2 p-2 mt-4 text-center">
                                    <label class="text-info d-block" style="font-size: 18px;" data-toggle="tooltip" title="">Coliformes Fecales</label>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_INERTES" id="COLIFORME_FECALES_INERTES_SI" value="1" required="required" checked>
                                            <label class="form-check-label" for="COLIFORME_FECALES_INERTES_SI">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="radio" name="COLIFORME_FECALES_INERTES" id="COLIFORME_FECALES_INERTES_NO" value="0" required="required">
                                            <label class="form-check-label" for="COLIFORME_FECALES_INERTES_NO">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Fecha de medición *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mydatepicker inertes_fecales" placeholder="aaaa-mm-dd" id="FECHA_MEDICION_INERTES_FECALES" name="FECHA_MEDICION_INERTES_FECALES" required>
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Unidades *</label>
                                                <select class="custom-select form-control inertes_fecales" id="UNIDAD_INERTES_FECALES" name="UNIDAD_INERTES_FECALES" required>
                                                    <option value=""></option>
                                                    <option value="NA">NA</option>
                                                    <option value="UFC/g">UFC/g</option>
                                                    <option value="NMP/g">NMP/g</option>
                                                    <option value="Pt/Co">Pt/Co</option>
                                                    <option value="UFC/sup.manos">UFC/sup.manos</option>
                                                    <option value="NMP/sup.manos">NMP/sup.manos</option>
                                                    <option value="UFC/100 cm2">UFC/100 cm2</option>
                                                    <option value="NMP/100 cm2">NMP/100 cm2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Método de análisis </label>
                                                <input type="text" class="form-control inertes_fecales" id="METODO_INERTES_FECALES" name="METODO_INERTES_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>No. de trabajadores expuestos</label>
                                                <input type="number" class="form-control inertes_fecales" id="TRABAJADORES_INERTES_FECALES" name="TRABAJADORES_INERTES_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración obtenida </label>
                                                <input type="text" class="form-control inertes_fecales" id="CONCENTRACION_INERTES_FECALES" name="CONCENTRACION_INERTES_FECALES" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Concentración permisible </label>
                                                <input type="text" class="form-control inertes_fecales" id="CONCENTRACION_INERTES_FECALES_PERMISIBLE" name="CONCENTRACION_PERMISIBLE_FECALES" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_punto_8_3">Guardar <i class="fa fa-save"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL-PUNTO-8.3 -->
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
                                <input type="number" min="0" max="100" class="form-control" id="reportealimentosarea_porcientooperacion" name="reportealimentosarea_porcientooperacion" required>
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
                                    <input class="form-check-input" type="radio" name="aplica_alimentos" id="aplica_alimentos_si" value="1" required="required" checked>
                                    <label class="form-check-label" for="aplica_alimentos_si">
                                        Si
                                    </label>
                                </div>
                                <div class="form-check mx-4">
                                    <input class="form-check-input" type="radio" name="aplica_alimentos" id="aplica_alimentos_no" value="0" required="required">
                                    <label class="form-check-label" for="aplica_alimentos_no">
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
                                <select class="custom-select form-control infoAdicionalArea" id="reportearea_sistemaalimentos" name="reportearea_sistemaalimentos" required>
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
                                <select class="custom-select form-control infoAdicionalArea" id="reportearea_tipoalimentos" name="reportearea_tipoalimentos" required>
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
								<select class="custom-select form-control" id="reportearea_alimentoslocalizada" name="reportearea_alimentoslocalizada" required>
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

<script type="text/javascript">
    var proyecto = <?php echo json_encode($proyecto); ?>;
    var recsensorial = <?php echo json_encode($recsensorial); ?>;
    var categorias_poe = <?php echo json_encode($categorias_poe); ?>;
    var areas_poe = <?php echo json_encode($areas_poe); ?>;
</script>

<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reportealimentos.js?v=1.0"></script>