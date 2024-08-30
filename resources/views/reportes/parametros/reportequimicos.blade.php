<style type="text/css">
	.reporte_estructura {
		font-size: 14px !important;
		line-height: 14px !important;
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
		font-size: 16px;
		line-height: 20px !important;
		text-align: justify;
	}

	div.imagen_formula {
		text-align: center;
		border: 0px #F00 solid;
	}

	div.informacion_estatica b {
		font-size: 14px;
		font-weight: bold;
		color: #777777;
	}

	div.informacion_estatica p {
		text-align: justify;
	}

	.tabla_info_centrado th {
		background: #F9F9F9;
		border: 1px #E5E5E5 solid !important;
		padding: 2px !important;
		text-align: center;
		vertical-align: middle !important;
	}

	.tabla_info_centrado td {
		border: 1px #E5E5E5 solid !important;
		padding: 4px !important;
		text-align: center;
		vertical-align: middle !important;
	}

	.tabla_info_justificado th {
		background: #F9F9F9;
		border: 1px #E5E5E5 solid !important;
		padding: 2px !important;
		text-align: center;
		vertical-align: middle !important;
	}

	.tabla_info_justificado td {
		border: 1px #E5E5E5 solid !important;
		padding: 4px !important;
		text-align: justify;
		vertical-align: middle !important;
	}

	.tabla_reporte th {
		background: #F9F9F9;
		border: 1px #E5E5E5 solid !important;
		padding: 2px !important;
		text-align: center;
		vertical-align: middle !important;
	}

	.tabla_reporte td {
		border-bottom: 1px #E5E5E5 solid !important;
		padding: 4px !important;
		text-align: center;
		vertical-align: middle !important;
	}
</style>


<div class="row" class="reporte_estructura">
	<div class="col-xlg-2 col-lg-3 col-md-5">
		<div class="stickyside">
			<div class="list-group" id="top-menu">
				<a href="#-2" class="list-group-item active">Químicos del proyecto</i></a>
				<!-- <a href="#-1" class="list-group-item">Partidas para impresión de los informes <i class="fa fa-times" id="menureporte-1"></i></a> -->
				<a href="#0" class="list-group-item">Portada <i class="fa fa-times" id="menureporte_0"></i></a>
				<a href="#1" class="list-group-item">1.- Introducción <i class="fa fa-times" id="menureporte_1"></i></a>
				<a href="#2" class="list-group-item">2.- Definiciones <i class="fa fa-times" id="menureporte_2"></i></a>
				<a href="#3" class="list-group-item">3.- Objetivos</a>
				<a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa fa-times" id="menureporte_3_1"></i></a>
				<a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa fa-times" id="menureporte_3_2"></i></a>
				<a href="#4" class="list-group-item">4.- Metodología</a>
				<a href="#4_1" class="list-group-item submenu">4.1.- Reconocimiento de los agentes y factores <i class="fa fa-times" id="menureporte_4_1"></i></a>
				<a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#5" class="list-group-item">5.- Reconocimiento</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
				<a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Equipo de Protección Personal (EPP) <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#5_6" class="list-group-item submenu">5.6.- Tabla de identificación de fuentes generadoras <i class="fa fa-times" id="menureporte_5_6"></i></a>
				<a href="#5_7" class="list-group-item submenu">5.7.- Determinación de la prioridad de la(s) sustancia(s) química(s) o mezclas por muestrear <i class="fa fa-times" id="menureporte_5_7"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados <i class="fa fa-times" id="menureporte_7"></i></a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Método de muestreo <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Datos de los contaminantes evaluados <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Incertidumbre de la medición <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Certificados de calibración del equipo <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Informe de resultados <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_8"></i></a>
				<a href="#11_9" class="list-group-item submenu">11.9.- Anexo 9: Registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_9"></i></a>
				<a href="#12_1" class="list-group-item">12.1.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</a>
				<a href="#13" class="list-group-item submenu" id="menu_opcion_final">Generar informe <i class="fa fa-download text-success" id="menureporte_13"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xlg-10 col-lg-9 col-md-7">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" style="padding: 0px!important;" id="-2">Químicos del proyecto</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva partida" id="boton_reporte_nuevogrupoquimico">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Asignar laboratorio
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_gruposquimicos">
							<thead>
								<tr>
									<th width="">Laboratorio</th>
									<th width="180">Parametros</th>
									<th width="180">Cantidad</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
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
									<label><input type="checkbox" id="reporte_catsubdireccion_activo" name="reporte_catsubdireccion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Subdirección</label>
								<select class="custom-select form-control" id="reporte_catsubdireccion_id" name="reporte_catsubdireccion_id" disabled>
									<option value=""></option>
									@foreach($catsubdireccion as $subdireccion)
									<option value="{{$subdireccion->id}}">{{$subdireccion->catsubdireccion_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catgerencia_activo" name="reporte_catgerencia_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Gerencia</label>
								<select class="custom-select form-control" id="reporte_catgerencia_id" name="reporte_catgerencia_id" disabled>
									<option value=""></option>
									@foreach($catgerencia as $gerencia)
									<option value="{{$gerencia->id}}">{{$gerencia->catgerencia_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catactivo_activo" name="reporte_catactivo_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Activo</label>
								<select class="custom-select form-control" id="reporte_catactivo_id" name="reporte_catactivo_id" disabled>
									<option value=""></option>
									@foreach($catactivo as $activo)
									<option value="{{$activo->id}}">{{$activo->catactivo_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12 d-none">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporte_instalacion" name="reporte_instalacion" onchange="instalacion_nombre(this.value);" readonly>
							</div>
						</div>
						<div class="col-4 d-none"></div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catregion_activo" name="reporte_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-3 d-none">
							<div class="form-group">
								<label>Región</label>
								<select class="custom-select form-control" id="reporte_catregion_id" name="reporte_catregion_id" disabled>
									<option value=""></option>
									@foreach($catregion as $region)
									<option value="{{$region->id}}">{{$region->catregion_nombre}}</option>
									@endforeach
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
										<select class="custom-select form-control" id="reporte_mes" name="reporte_mes">
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
										<select class="custom-select form-control" id="reporte_fecha" name="reporte_fecha">
											<option value="" selected disabled></option>
											<script>
												$(document).ready(function() {
													const currentYear = new Date().getFullYear();
													for (let year = currentYear; year >= 2017; year--) {
														$('#reporte_fecha').append(new Option(year, year));
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_introduccion" name="reporte_introduccion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_introduccion">Guardar introducción <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<!-- ======== ELIMINAR DESPUES DE SUBIR AL SERVIDOR =============-->
				<div class="col-12 mt-4" style="text-align: center;">
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
								<tbody></tbody>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivogeneral" name="reporte_objetivogeneral" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporte_objetivoespecifico" name="reporte_objetivoespecifico" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="6" id="reporte_metodologia_4_2" name="reporte_metodologia_4_2" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<b>Promedio Ponderado en Tiempo</b><br><br>
								<p>a) Muestra continúa en el período completo: se toma una sola muestra, sin interrupciones, que abarque la jornada de trabajo o al menos 7/8 de la misma;</p>
								<p>b) Muestras consecutivas en el período completo: se interrumpe el muestreo momentáneamente varias veces, pero el tiempo total del muestreo debe ser igual al período de la jornada de trabajo o al menos 7/8 de la misma;</p>
								<p>c) Muestras consecutivas en un período parcial: se toman varias muestras durante la jornada de trabajo en la cual hay exposición del personal ocupacionalmente expuesto al contaminante;</p>

								<b>Corto Tiempo y Pico</b><br><br>
								<p>d) Muestras corto tiempo y pico: se deberán tomar muestras, sin interrupciones, en un período de 15 minutos.</p>
								<p>En la Gráfica 1 se ejemplifican de modo esquemático los tipos de muestras que se pueden tomar en una jornada de trabajo.</p>

								<div class="imagen_formula"><img src="/assets/images/reportes/reportequimicos_figura_4_2_1.jpg" height="280"></div>
								<div class="imagen_formula">Gráfica obtenida de la NOM-010-STPS-2014</div><br>

								<p>Por otra parte, la determinación analítica de los agentes químicos contaminantes del ambiente laboral se efectuó por un laboratorio autorizado, es decir, acreditado en la técnica analítica correspondiente ante la entidad mexicana de acreditación (ema) y aprobado en el procedimiento o método de muestreo y determinación analítica. Cabe mencionar, que para los análisis que aparecen referenciados en este estudio, así como el nombre del signatario que realizó el muestreo de agentes químicos aparece dentro de dicha acreditación, misma que se encuentra disponible para consulta.</p><br>

								<p>Continuando con la determinación de los agentes químicos contaminantes, durante la evaluación de los agentes químicos contaminantes se priorizan las sustancias que la suma de los valores de ponderación sea Muy Alta, Alta y Moderada como se muestra en la Tabla 11 (NOM-010-STPS-2014) para definir los tiempos de exposición al POE o grupos Homogéneos que se encuentran laborando en las áreas.</p>

								<div class="imagen_formula"><b>Tabla 11<br>Prioridad de muestreo de las sustancias químicas</b></div>
								<table class="table tabla_info_centrado" width="100%">
									<thead>
										<tr>
											<th width="50%">Suma de valores de ponderación</th>
											<th width="50%">Prioridad de muestreo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>De 3 a 4</td>
											<td>Muy baja</td>
										</tr>
										<tr>
											<td>De 5 a 7</td>
											<td>Baja</td>
										</tr>
										<tr>
											<td>De 8 a 9</td>
											<td>Moderada</td>
										</tr>
										<tr>
											<td>De 10 a 11</td>
											<td>Alta</td>
										</tr>
										<tr>
											<td>De 12 o más</td>
											<td>Muy alta</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-010-STPS-2014</td>
										</tr>
									</tfoot>
								</table>

								<p>Finalmente, para analizar los resultados de los agentes químicos contaminantes, se compara las concentraciones medidas en el ambiente laboral (CMA) con los valores límite de exposición (VLE) que se encuentra en la norma de referencia y para calcular la concentración ponderada en tiempo (CMA-PPT) se utiliza la siguiente ecuación:</p>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportequimicos_figura_4_2_2.jpg" height="50"></div>

								<p>Donde:<br><br>
									<b>CMAi</b>, es la concentración i ésima del contaminante en el ambiente laboral durante un tiempo determinado, siempre en mg/m3 o en ppm.<br><br>
									<b>t¡</b>, es el tiempo i ésimo, utilizado en cada toma de muestra, siempre en la misma unidad de tiempo.<br><br>
									Cuando la jornada laboral del personal ocupacionalmente expuesto sea diferente de 8 horas diarias, en el intervalo de 6 a 12 horas se deberá calcular el factor de corrección FC día, con la ecuación siguiente:
								</p>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportequimicos_figura_4_2_3.jpg" height="50"></div>

								<p>Donde:<br><br>
									<b>Fc <sub>día</sub></b>, es el factor de corrección por día.<br><br>
									<b>hd</b>, es la duración de la jornada de trabajo en horas.<br><br>
									El valor límite de exposición corregido (VLE <sub>corregido</sub>), contra el cual será comparado la concentración medida en el ambiente laboral (CMA), se calculará con la ecuación siguiente:
								</p>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportequimicos_figura_4_2_4.jpg" height="40"></div>
							</div>
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
									<textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reporte_ubicacioninstalacion" name="reporte_ubicacioninstalacion" required></textarea>
								</div>
							</div>
						</div>
						<div class="col-6">
							<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
							<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteubicacionfoto" name="reporteubicacionfoto" onchange="redimencionar_mapaubicacion();" required>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_procesoinstalacion" name="reporte_procesoinstalacion" required></textarea>
							</div>
							<div class="form-group">
								<label>Descripción de la actividad principal de la instalación</label>
								<textarea class="form-control" style="margin-bottom: 0px;" rows="7" id="reporte_actividadprincipal" name="reporte_actividadprincipal" required></textarea>
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
						<p class="justificado">En este apartado se muestra la actividad desarrollada en la instalación, involucrando al personal/categoría adscrito en cada área que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva categoría" id="boton_reporte_nuevacategoria">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva categoría
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_categoria">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th>Categoría</th>
									<th width="80">Total</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área" id="boton_reporte_nuevaarea">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva área
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_area">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="150">Área</th>
									<th width="">Categoría</th>
									<th width="60">Total</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="5_4">5.4.- Equipo de Protección Personal (EPP)</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describe el EPP utilizado por el personal considerado durante las evaluaciones de agentes químicos en las áreas de <b class="div_instalacion_nombre" style="color: #000000;">Instalación</b>.</p><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva equipo de protección" id="boton_reporte_nuevoepp">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Equipo de protección
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_epp">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th>Parte del cuerpo</th>
									<th>Equipo de protección personal<br>básico proporcionado</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="5_5">5.5.- Actividades del personal expuesto</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describen las actividades realizadas en cada área con exposición a ruido, según la categoría encontrada en dicho sitio.</p><br>
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_5">
								<thead>
									<tr>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="280">Categoría</th>
										<th width="">Descripción de las actividades</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="5_6">5.6.- Tabla de identificación de fuentes generadoras</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describen las fuentes de contaminantes en cada una de las áreas evaluadas, así como sus características de área y los puestos de trabajo presentes.</p><br>
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_6">
								<thead>
									<tr>
										<th width="130" rowspan="2">Instalación</th>
										<th width="150" rowspan="2">Área</th>
										<th width="150" rowspan="2">Fuentes generadoras</th>
										<th width="" rowspan="2">Generación del contaminante</th>
										<th width="200" rowspan="2">Puesto de trabajo</th>
										<th width="120" colspan="2">Características del área</th>
									</tr>
									<tr>
										<th width="60">Abierta</th>
										<th width="60">Cerrada</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="5_7">5.7.- Determinación de la prioridad de la(s) sustancia(s) química(s) o mezclas por muestrear</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se presenta la información de las sustancias químicas para la determinación de la prioridad de muestreo, tomando en cuenta los valores de ponderación de las sustancias químicas y por último estableciendo la prioridad del muestreo de las sustancias químicas, conforme a la tabla 9 de la NOM-010-STPS-2014 con el fin de considerar para el muestreo las sustancias químicas con prioridad muy alta, alta y moderada:</p><br>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_7">
							<thead>
								<tr>
									<th width="150" rowspan="2">Sustancia<br>química</th>
									<th width="" rowspan="2">Componentes<br>a evaluar</th>
									<th width="300" colspan="3">Valor de ponderación</th>
									<th width="100" rowspan="2">TOTAL<br>(Suma ponderación)</th>
									<th width="100" rowspan="2">Prioridad<br>de muestreo</th>
								</tr>
								<tr>
									<th width="100">Cantidad<br>manejada</th>
									<th width="100">Clasificación<br>de riesgo</th>
									<th width="100">Volatilidad</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="6">6.- Evaluación</h4>
				<h4 class="card-title" id="6_1">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">Instalación</b> se presentan por porcentaje en la siguiente tabla:</p><br>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_1">
							<thead>
								<tr>
									<th width="150">Instalación</th>
									<th width="">Área de trabajo</th>
									<th width="200">Porcentaje de operación</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">En el siguiente apartado se presentan los resultados obtenidos en cada punto evaluado, así como el área, categoría, concentración medida del ambiente, valor límite de exposición, periodo de muestreo y evaluación; además se muestra el cumplimiento normativo.</p><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de evaluación" id="boton_reporte_puntoevalucion">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de evaluación
							</button>
						</ol>
						<style type="text/css">
							#tabla_reporte_7 th {
								font-size: 0.65vw !important;
							}

							#tabla_reporte_7 td {
								font-size: 0.65vw !important;
							}
						</style>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7">
							<thead>
								<tr>
									<th width="70">Punto de<br>evaluación</th>
									<th width="120">Área</th>
									<th width="">Categoría</th>
									<th width="">Parámetro</th>
									<th width="">Método</th>
									<th width="80">Concentración<br>medida del ambiente</th>
									<th width="80">Valor límite<br>de exposición</th>
									<th width="80">Límite superior<br>de confianza</th>
									<th width="80">Periodo de<br>muestreo y<br>evaluación</th>
									<th width="80">Cumplimiento<br>normativo</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_1">7.1.- Método de muestreo</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describe el procedimiento o método utilizado durante la evaluación.</p><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo Parámetro" id="boton_reporte_metodomuestreo">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Parámetro (método de muestreo)
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_1">
							<thead>
								<tr>
									<th width="150" rowspan="2">Parámetro</th>
									<th width="" rowspan="2">Procedimiento o método</th>
									<th width="100" rowspan="2">Puntos evaluados</th>
									<th width="" colspan="2">Datos del muestreo</th>
									<th width="60" rowspan="2">Editar</th>
									<th width="60" rowspan="2">Eliminar</th>
								</tr>
								<tr>
									<th width="150">Tipo de Muestra</th>
									<th width="150">Flujo de muestreo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_2">7.2.- Matriz de exposición laboral</h4>
				<div class="row">
					<div class="col-12">
						<style type="text/css">
							#tabla_reporte_matriz th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.65vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_matriz td {
								padding: 1px !important;
								font-size: 0.65vw !important;
								text-align: center;
							}

							#tabla_reporte_matriz tr:hover td {
								color: #000000;
							}

							.rotartexto {
								-webkit-transform: rotate(-90deg);
								-moz-transform: rotate(-90deg);
								-o-transform: rotate(-90deg);
								transform: rotate(-90deg);
								filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

								display: -moz-inline-stack;
								display: inline-block;
								zoom: 1;
								*display: inline;
							}
						</style>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_matriz">
							<thead>
								<tr>
									<th width="60">Contador</th>
									<!-- <th>Subdirección o<br>corporativo</th> -->
									<!-- <th>Gerencia o<br>activo</th> -->
									<th>Instalación</th>
									<th>Área de<br>referencia<br>en atlas<br>de riesgo</th>
									<th>Nombre</th>
									<th width="70">Ficha</th>
									<th>Categoría</th>
									<th width="60">Número de<br>personas</th>
									<th width="80">Grupo de<br>exposición<br>homogénea</th>
									<th width="100">Agentes químicos<br>evaluados</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="8">8.- Conclusiones</h4>
				<div class="form-group mb-4">
					<form action="" id="form_modal_conclusion" name="form_modal_conclusion">
						{!! csrf_field() !!}

						<input type="hidden" name="catreportequimicospartidas_id" value="0">
						<input type="hidden" class="form-control" id="reporteconclusion_id" name="reporteconclusion_id" value="0">
						<textarea class="form-control" style="margin-bottom: 0px;" rows="15" id="reporte_conclusion" name="reportequimicosconclusion_conclusion" required></textarea>
						<button type="submit" style="float: right;" class="btn btn-danger waves-effect waves-light" id="botonguardar_modal_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>

					</form>
				</div>
				<!-- <div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar nueva conclusión" id="boton_reporte_nuevaconclusion">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva conclusión
								</button>
							</ol>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_conclusiones">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="200">Partida</th>
										<th>Descripción</th>
										<th width="60">Editar</th>
										<th width="60">Eliminar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div> -->
				<div class="row mt-5">
					<div class="col-12">
						<p class="justificado">A continuación, se plasman los resultados en la siguiente figura:</p><br>
						<style type="text/css">
							#tabla_dashboard {
								border: 3px #0BACDB solid;
							}

							#tabla_dashboard th {
								border: 1px #E9E9E9 solid;
								background: #0BACDB;
								color: #000000 !important;
								padding: 4px;
								line-height: 16px;
								margin: 0px;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_dashboard td {
								border: 1px #E9E9E9 solid;
								padding: 4px;
								line-height: 1.1;
								margin: 0px;
								vertical-align: middle;
								text-align: center;
								font-size: 0.8vw !important;
							}

							#tabla_dashboard td.td_top {
								vertical-align: top;
							}

							#tabla_dashboard td .icono {
								width: 100%;
								font-size: 5vw !important;
								margin: 10px 0px;
							}

							#tabla_dashboard td .texto {
								font-size: 0.9vw !important;
								line-height: 1 !important;
								font-weight: bold;
							}

							#tabla_dashboard td .numero {
								font-size: 1.2vw !important;
								line-height: 1.2 !important;
								font-weight: bold;
							}
						</style>
						<table class="table" width="100%" id="tabla_dashboard">
							<tbody>
								<tr>
									<th colspan="4" style="font-size: 18px!important;"><span id="dashboard_titulo">TITULO</span></th>
								</tr>
								<tr>
									<th colspan="3">Nivel de cumplimiento por agente</th>
									<th>Categorías evaluadas</th>
								</tr>
								<tr>
									<td height="240" colspan="3">
										<div id="parametros_cumplimiento"></div>
									</td>
									<td>
										<i class="fa fa-users text-info" style="font-size: 60px!important;"></i><br><br>
										<div id="dashboard_categorias"></div>
									</td>
								</tr>
								<tr>
									<th colspan="3">Distribución de puntos de medición</th>
									<th>Total de recomendaciones</th>
								</tr>
								<tr>
									<td height="240" width="150">
										<b>Total puntos de medición</b><br>
										<i class="fa fa-warning icono" style="color: #8ee66b;" id="total_evaluacion"><br>0</i>
									</td>
									<td width="">
										<div id="dashboard_areas"></div>
									</td>
									<td width="150">
										<b>Total de puntos en medición ambiental</b><br>
										<i class="fa fa-search text-info" style="font-size: 50px!important;" id="total_evaluacionambiental">0</i><br><br>
										<b>Total de puntos en medición personal</b><br>
										<i class="fa fa-search text-warning" style="font-size: 50px!important;" id="total_evaluacionpersonal">0</i>
									</td>
									<td width="260">
										<i class="fa fa-list-alt text-secondary" style="font-size: 80px!important;" id="dashboard_recomendaciones_total"> 0</i><br><br>
									</td>
								</tr>
								<tr>
									<td colspan="4">"Agentes químicos contaminantes del ambiente laboral - Reconocimiento, evaluación y control" (NOM-010-STPS-2014).</td>
								</tr>
							</tbody>
						</table>
						{{-- <div id="captura" style="height: 800px; width: 100%; border: 1px #000 solid;">graficas</div><br> --}}
						{{-- <button type="button" class="btn btn-success waves-effect waves-light" id="botonguardar_generargraficas">Guardar gráficas <i class="fa fa-chart"></i></button> --}}
					</div>
				</div>
				<h4 class="card-title" id="9">9.- Recomendaciones de control</h4>
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
							<style type="text/css">
								#tabla_reporte_recomendaciones td.alinear_izquierda {
									text-align: left;
								}

								#tabla_reporte_recomendaciones td label {
									font-size: 14px;
									color: #000000;
								}
							</style>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_recomendaciones">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="70">Activo</th>
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
				<h4 class="card-title" id="10">10.- Responsables del informe</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_responsablesinforme" id="form_reporte_responsablesinforme">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="responsablesinforme_carpetadocumentoshistorial" name="responsablesinforme_carpetadocumentoshistorial">
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Nombre del responsable técnico</label>
										<input type="text" class="form-control" id="reporte_responsable1" name="reporte_responsable1" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Cargo del responsable técnico</label>
										<input type="text" class="form-control" id="reporte_responsable1cargo" name="reporte_responsable1cargo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Foto documento del responsable técnico</label>
										<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc1"></i>
										<input type="hidden" class="form-control" id="reporte_responsable1_documentobase64" name="reporte_responsable1_documentobase64">
										<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteresponsable1documento" name="reporteresponsable1documento" onchange="redimencionar_foto('reporteresponsable1documento', 'reporte_responsable1_documentobase64', 'botonguardar_reporte_responsablesinforme');" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Nombre del administrativo prestador de servicio</label>
										<input type="text" class="form-control" id="reporte_responsable2" name="reporte_responsable2" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Cargo del administrativo prestador de servicio</label>
										<input type="text" class="form-control" id="reporte_responsable2cargo" name="reporte_responsable2cargo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Foto documento del prestador de servicio</label>
										<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc2"></i>
										<input type="hidden" class="form-control" id="reporte_responsable2_documentobase64" name="reporte_responsable2_documentobase64">
										<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteresponsable2documento" name="reporteresponsable2documento" onchange="redimencionar_foto('reporteresponsable2documento', 'reporte_responsable2_documentobase64', 'botonguardar_reporte_responsablesinforme');" required>
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
				<h4 class="card-title" id="11">11.- Anexos</h4>
				<h4 class="card-title" id="11_1">11.1.- Anexo 1: Memoria fotográfica</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Se encontraron <b id="memoriafotografica_total" style="color: #000;">0</b> fotos de todos los agentes químicos evaluados la cual se agregarán a la impresión del informe segun la partida correspondiente.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_2">11.2.- Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los planos de las carpetas seleccionadas se agregaran en el informe de químicos.</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_planos">
								<thead>
									<tr>
										<th width="60">Seleccionado</th>
										<th width="">Carpeta</th>
										<!-- <th width="">Partida</th> -->
										<th width="120">Total planos</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_planos">Guardar carpeta planos <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_3">11.3.- Anexo 3: Datos de los contaminantes evaluados</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_11_3">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>No. de CAS</th>
									<th>Peso molecular<br>gr/mol</th>
									<th>Vías de ingreso<br>al organismo</th>
									<th>Clasificación de riesgo<br>a la salud</th>
									<th>Valor Límite de<br>Exposición (VLE)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="11_4">11.4.- Anexo 4: Equipo utilizado en la medición</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_equipoutilizado" id="form_reporte_equipoutilizado">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_equipoutilizado">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="60">Seleccionado</th>
										<th>Equipo</th>
										<th width="200">Marca / Modelo / Serie</th>
										<th width="160">Vigencia</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<br><b>Nota *:</b> La calibración tiene una extensión en el tiempo de vigencia avalada mediante una carta emitida por el laboratorio acreditado misma que se encuentra disponible para consulta en el anexo 5.<br>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_equipoutilizado">Guardar equipo utilizado <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_5">11.5.- Anexo 5: Incertidumbre de la medición</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> La incertidumbre de cada una de los muestreos realizados se encuentran disponibles para consulta dentro del informe de resultados (Anexo 7) emitido por el laboratorio aprobado ya que dicho informe no puede ser alterado o modificado en su contenido.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_6">11.6.- Anexo 6: Certificados de calibración del equipo</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El certificado del equipo utilizado seleccionado en el punto 11.4 “Anexo 4: Equipo utilizado en la medición” se adjuntará en la impresión del reporte en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_7">11.7.- Anexo 7: Informe de resultados</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_anexosresultados" id="form_reporte_anexosresultados">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_anexosresultados">
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
							</table>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexosresultados">Guardar anexos resultados <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_8">11.8.- Anexo 8: Aprobación del laboratorio de ensayo ante la STPS</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario aprobado en muestreo de agentes químicos el cual aparece dentro del registro de aprobación ante la Secretaría del Trabajo y Previsión Social (STPS). Además, para el presente apartado cabe mencionar que los métodos de carácter analítico para evaluar el cumplimiento de la NOM-010-STPS-2014 son realizados por el laboratorio autorizado para el análisis que aparece referenciado en el informe de resultados y se encuentra para consulta en dicha aprobación.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 8", debe elegirlo en la tabla del punto 12.1 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_9">11.9.- Anexo 9: Registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario acreditado en muestreo de agentes químicos el cual aparece dentro de la acreditación del laboratorio ante la entidad mexicana de acreditación (ema). Además, para el presente apartado cabe mencionar que los métodos de carácter analítico, son realizados por el laboratorio autorizado para el análisis que aparece referenciado en el informe de resultados y se encuentra para consulta en dicha acreditación.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 9", debe elegirlo en la tabla del punto 12.1 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="12_1">12.1.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_acreditacionaprobacion" id="form_reporte_acreditacionaprobacion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_acreditacionaprobacion">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="60">Seleccionado</th>
										<th width="100">Proveedor</th>
										<th width="100">Tipo</th>
										<th>Entidad</th>
										<th width="200">Numero</th>
										<th>Área</th>
										<th width="160">Vigencia</th>
										<th width="60">Certificado</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_acreditacionaprobacion">Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="13">Generar informe .docx + Anexos .Zip</h4>
				<div class="row">
					<div class="col-12">
						@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Proyecto', 'Coordinador']))
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" title="Nueva revisión" id="boton_reporte_nuevarevision">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión
							</button>
						</ol>
						@endif
						<table class="table table-hover tabla_reporte" width="100%" id="tabla_reporte_revisiones">
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
						{{-- <div id="captura" style="width: 100%; border: 1px #000 solid;">graficas</div><br> --}}
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
	<div class="modal-dialog modal-sm" style="max-width: 300px!important; margin-top: 250px;">
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
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalvisor_reporte">Cerrar</button>
				{{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-QUIMICOS GRUPOS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_grupoquimicos>.modal-dialog {
		/*min-width: 800px!important;*/
	}

	#modal_reporte_grupoquimicos .modal-body .form-group {
		margin: 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_grupoquimicos .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}

	#modal_reporte_grupoquimicos .modal-body .form-group .form-control {
		margin: 0px 0px 14px 0px !important;
	}
</style>
<div id="modal_reporte_grupoquimicos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_grupoquimicos" id="form_modal_grupoquimicos">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Titulo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-12">
									{!! csrf_field() !!}
									<input type="hidden" class="form-control" id="grupoquimicos_id" name="grupoquimicos_id" value="0">
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Laboratorio responsable *</label>
										<select class="custom-select form-control" id="metodomuestreo_proveedor_id" name="proveedor_id" required>
											<option value=""></option>
										</select>
									</div>
								</div>
								<!-- <div class="col-12">
									<div class="form-group">
										<label>Partida</label>
										<select class="custom-select form-control" id="catreportequimicospartidas_id" name="catreportequimicospartidas_id" required>
											<option value=""></option>
										</select>
									</div>
								</div> -->
							</div>
						</div>
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Parámetros evaluados</ol>
						</div>
						<div class="col-12">
							<div class="row" id="quimicos_lista"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_grupoquimicos">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-QUIMICOS GRUPOS -->
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
						<div class="col-8">
							<div class="form-group">
								<label>Categoría</label>
								<input type="text" class="form-control" id="reportequimicoscategoria_nombre" name="reportequimicoscategoria_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" class="form-control" id="reportequimicoscategoria_total" name="reportequimicoscategoria_total" required>
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
		min-width: 1000px !important;
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
						<div class="col-6">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reportequimicosarea_instalacion" name="reportequimicosarea_instalacion" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reportequimicosarea_nombre" name="reportequimicosarea_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" class="form-control" id="reportequimicosarea_numorden" name="reportequimicosarea_numorden" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reportequimicosarea_porcientooperacion" name="reportequimicosarea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Caract. del área</label>
								<select class="custom-select form-control" id="reportequimicosarea_caracteristica" name="reportequimicosarea_caracteristica" required>
									<option value="">&nbsp;</option>
									<option value="1">Abierta</option>
									<option value="0">Cerrada</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Fuente generadora</label>
								<input type="text" class="form-control" id="reportequimicosarea_maquinaria" name="reportequimicosarea_maquinaria" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Generación del contaminante</label>
								<input type="text" class="form-control" id="reportequimicosarea_contaminante" name="reportequimicosarea_contaminante" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Categorías en el área</ol>
							<div style="margin: -25px 0px 0px 0px!important; padding: 0px!important;">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areacategorias">
									<thead>
										<tr>
											<th width="60" style="padding: 3px 0px!important;">Activo</th>
											<th width="240" style="padding: 3px 0px!important;">Categoría</th>
											<th width="120" style="padding: 3px 0px!important;">Total</th>
											<th width="" style="padding: 3px 0px!important;">Actividades</th>
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
						<div class="col-12">
							<div class="form-group">
								<label>Parte del cuerpo</label>
								<input type="text" class="form-control" id="reportequimicosepp_partecuerpo" name="reportequimicosepp_partecuerpo" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Equipo de protección personal básico proporcionado</label>
								<input type="text" class="form-control" id="reportequimicosepp_equipo" name="reportequimicosepp_equipo" required>
							</div>
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
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_puntoevaluacion>.modal-dialog {
		min-width: 1500px !important;
	}

	#modal_reporte_puntoevaluacion .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_puntoevaluacion .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_puntoevaluacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_puntoevaluacion" id="form_modal_puntoevaluacion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Punto de medición NER</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="puntoevaluacion_id" name="puntoevaluacion_id" value="0">
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Punto de evaluación</label>
								<input type="number" min="1" class="form-control" id="reportequimicosevaluacion_punto" name="reportequimicosevaluacion_punto" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reportequimicosevaluacion_areaid" name="reportequimicosarea_id" onchange="evaluacion_areacategorias(this.value);" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-5">
							<div class="form-group">
								<label>Categoría</label>
								<select class="custom-select form-control" id="reportequimicosevaluacion_categoriaid" name="reportequimicoscategoria_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Nombre</label>
								<input type="text" class="form-control" id="reportequimicosevaluacion_nombre" name="reportequimicosevaluacion_nombre" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Ficha</label>
								<input type="text" class="form-control" id="reportequimicosevaluacion_ficha" name="reportequimicosevaluacion_ficha" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Grupo exp. homogénea</label>
								<input type="number" min="0" class="form-control" id="reportequimicosevaluacion_geo" name="reportequimicosevaluacion_geo" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Personas en el área</label>
								<input type="number" min="0" class="form-control" id="reportequimicosevaluacion_total" name="reportequimicosevaluacion_total" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar parámetro evaluado" id="boton_evaluacion_nuevoparametro">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Parámetro
								</button>
							</ol>
							<div style="max-height: 420px; overflow-x: hidden; overflow-y: auto;" id="div_tabla_evaluacion_parametros">
								<table class="table table-hover tabla_info_centrado" style="margin-bottom: 0px;" width="100%" id="tabla_evaluacion_parametros">
									<thead>
										<tr>
											<th width="250">Parámetro</th>
											<th width="">Método</th>
											<th width="">Unidad de <br> medida</th>
											<th width="130">Concentración<br>medida del<br>ambiente</th>
											<th width="130">Valor límite<br>de exposición</th>
											<th width="130">Límite superior<br>de confianza</th>
											<th width="180">Periodo de<br>muestreo y<br>evaluación</th>
											<th width="60">Eliminar</th>
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
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_puntoevaluacion">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-METODO DE MUESTREO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_metodomuestreo>.modal-dialog {
		min-width: 800px !important;
	}

	#modal_reporte_metodomuestreo .modal-body .form-group {
		margin: 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_metodomuestreo .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}

	#modal_reporte_metodomuestreo .modal-body .form-group .form-control {
		margin: 0px 0px 14px 0px !important;
	}
</style>
<div id="modal_reporte_metodomuestreo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_metodomuestreo" id="form_modal_metodomuestreo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Dosis de determinación NER al personal</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-8">
							<div class="row">
								<div class="col-12">
									{!! csrf_field() !!}
									<input type="hidden" class="form-control" id="metodomuestreo_id" name="metodomuestreo_id" value="0">
								</div>
								<div class="col-8">
									<div class="form-group">
										<label>Parámetro</label>
										<select class="custom-select form-control" id="reportequimicosmetodomuestreo_parametro" name="reportequimicosmetodomuestreo_parametro" required>
											<option value=""></option>
										</select>
									</div>
								</div>
								<div class="col-4">
									<div class="form-group">
										<label>Puntos evaluados</label>
										<input type="number" min="1" class="form-control" id="reportequimicosmetodomuestreo_puntos" name="reportequimicosmetodomuestreo_puntos" onchange="metodomuestreo_flujos(this.value)" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Procedimiento o método</label>
										<textarea class="form-control" rows="2" id="reportequimicosmetodomuestreo_metodo" name="reportequimicosmetodomuestreo_metodo" required></textarea>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Tipo de muestra</label>
										<input type="text" class="form-control" id="reportequimicosmetodomuestreo_tipo" name="reportequimicosmetodomuestreo_tipo" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-4">
							<div style="height: 250px; border: 1px #DDDDDD solid; overflow-x: hidden; overflow-y: auto;" id="div_tabla_metodomuestreo_flujo">
								<table class="table table-hover tabla_info_centrado" style="margin-bottom: 0px;" width="100%" id="tabla_metodomuestreo_flujo">
									<thead>
										<tr>
											<th width="60">Punto</th>
											<th width="">Flujo de muestreo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td><input type="number" step="any" min="0" class="form-control" name="reportequimicosmetodomuestreo_flujo[]" required></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_metodomuestreo">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-METODO DE MUESTREO -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-CANCELACION OBSERVACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_conclusion>.modal-dialog {
		min-width: 900px !important;
	}

	#modal_reporte_conclusion .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_conclusion .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<!-- <div id="modal_reporte_conclusion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_conclusion" id="form_modal_conclusion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Conslusión</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteconclusion_id" name="reporteconclusion_id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Partida</label>
								<select class="custom-select form-control" id="reporteconclusion_catreportequimicospartidas_id" name="catreportequimicospartidas_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Conclusion</label>
								<textarea class="form-control" rows="15" id="reportequimicosconclusion_conclusion" name="reportequimicosconclusion_conclusion" required></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncancelar_modal_conclusion">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="botonguardar_modal_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div> -->
<!-- ============================================================== -->
<!-- MODAL-REPORTE-CANCELACION OBSERVACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-IMPRIMIR PARTIDA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_imprimirpartida>.modal-dialog {
		min-width: 800px !important;
	}

	#modal_reporte_imprimirpartida .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_imprimirpartida .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_imprimirpartida" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_imprimirpartida" id="form_modal_imprimirpartida">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Descargar informe de químicos</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="imprimirpartida_registro_id" name="imprimirpartida_registro_id" value="0">
							<input type="hidden" class="form-control" id="imprimirpartida_revision_id" name="imprimirpartida_revision_id" value="0">
							<input type="hidden" class="form-control" id="imprimirpartida_ultima_revision" name="imprimirpartida_ultima_revision" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Partida</label>
								<select class="custom-select form-control" id="imprimirpartida_partida_id" name="imprimirpartida_partida_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncancelar_modal_imprimirpartida">Cerrar</button>
					<button type="submit" class="btn btn-info waves-effect waves-light" id="botonguardar_modal_imprimirpartida">Descargar <i class="fa fa-download"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-IMPRIMIR PARTIDA -->
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
<link href="/assets/plugins/amChart/amcharts/plugins/export/export.css" type="text/css" media="all"  rel="stylesheet"/>
<script src="/assets/plugins/amChart/amcharts/pie.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/light.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/black.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/dark.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="/assets/plugins/amChart/amcharts/themes/patterns.js" type="text/javascript"></script> --}}
<script type="text/javascript">
	var proyecto = <?php echo json_encode($proyecto); ?>;
	var recsensorial = <?php echo json_encode($recsensorial); ?>;
	var categorias_poe = <?php echo json_encode($categorias_poe); ?>;
	var areas_poe = <?php echo json_encode($areas_poe); ?>;
</script>
<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reportequimicos.js?v=4.0"></script>