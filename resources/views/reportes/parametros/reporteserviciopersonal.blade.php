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
		font-size: 14px;
		line-height: 14px !important;
		text-align: justify;
	}

	div.imagen_formula {
		text-align: center;
		border: 0px #F00 solid;
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
	<div class="col-xlg-2 col-lg-3 col-md-12">
		<div class="stickyside">
			<div class="list-group" id="top-menu">
				<a href="#0" class="list-group-item active">Portada <i class="fa fa-times" id="menureporte_0"></i></a>
				<a href="#1" class="list-group-item">1.- Introducción <i class="fa fa-times" id="menureporte_1"></i></a>
				<a href="#2" class="list-group-item">2.- Definiciones <i class="fa fa-times" id="menureporte_2"></i></a>
				<a href="#3" class="list-group-item">3.- Objetivos</a>
				<a href="#3_1" class="list-group-item submenu">3.1.- Objetivo general <i class="fa fa-times" id="menureporte_3_1"></i></a>
				<a href="#3_2" class="list-group-item submenu">3.2.- Objetivos específicos <i class="fa fa-times" id="menureporte_3_2"></i></a>
				<a href="#4" class="list-group-item">4.- Reconocimiento inicial de los factores <i class="fa fa-times" id="menureporte_4"></i></a>
				<a href="#5" class="list-group-item">5.- Metodología</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Método de evaluación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#6" class="list-group-item">6.- Reconocimiento</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#7" class="list-group-item">7.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_7"></i></a>
				<a href="#8" class="list-group-item">8.- Evaluación</a>
				<a href="#8_1" class="list-group-item submenu">8.1.- Proporcionalidad y dimensionalidad <i class="fa fa-times" id="menureporte_8_1"></i></a>
				<a href="#8_2" class="list-group-item submenu">8.2.- Condiciones físicas de higiene y funcionalidad <i class="fa fa-times" id="menureporte_8_2"></i></a>
				<a href="#8_3" class="list-group-item submenu">8.3.- Descripción de medios de proliferación de fauna nociva <i class="fa fa-times" id="menureporte_8_3"></i></a>
				<a href="#8_4" class="list-group-item submenu">8.4.- Focos de desarrollo y anidación de fauna nociva <i class="fa fa-times" id="menureporte_8_4"></i></a>
				<a href="#9" class="list-group-item">9.- Conclusiones <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Recomendaciones de control <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Responsables del informe <i class="fa fa-times" id="menureporte_11"></i></a>
				<a href="#12" class="list-group-item">12.- Anexos</a>
				<a href="#12_1" class="list-group-item submenu">12.1.- Anexo 1.- Memoria fotográfica <i class="fa fa-times" id="menureporte_12_1"></i></a>
				<a href="#12_2" class="list-group-item submenu">12.2.- Anexo 2.- Valoración de riesgo de la condición insegura detectada. <i class="fa fa-times" id="menureporte_12_2"></i></a>
				<a href="#12_3" class="list-group-item submenu">12.3.- Anexo 3.- Hojas de campo <i class="fa fa-times" id="menureporte_12_3"></i></a>
				<a href="#12_4" class="list-group-item submenu">12.4.- Anexo 4.- Plano de ubicación de las fuentes generadoras y puntos evaluados. <i class="fa fa-times" id="menureporte_12_4"></i></a>
				<a href="#12_5" class="list-group-item submenu">12.5.- Anexo 5.- Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_12_5"></i></a>
				<a href="#12_6" class="list-group-item submenu">12.6.- Anexo 6.-Incertidumbre de la medición <i class="fa fa-times" id="menureporte_12_6"></i></a>
				<a href="#12_7" class="list-group-item submenu">12.7.- Anexo 7.-Informe de resultados <i class="fa fa-times" id="menureporte_12_7"></i></a>
				<a href="#12_8" class="list-group-item submenu">12.8.- Anexo 8.- Copia de aprobación/autorización del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_12_8"></i></a>
				<a href="#12_9" class="list-group-item submenu">12.9.- Anexo 9.- Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_12_9"></i></a>
				<a href="#13" class="list-group-item" id="menu_opcion_final">Generar informe <i class="fa fa-download text-success"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xlg-10 col-lg-9 col-md-12">
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

								<div class="col-12 mb-5">
									<div class="form-group">
										<label> Nivel 5 </label>
										<select class="custom-select form-control" style="width: 90%;" id="NIVEL5" name="NIVEL5">

										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Proporcionalidad y dimensionalidad</label>
										<select class="custom-select form-control" id="reporte_alcanceinforme" name="reporte_alcanceinforme" onchange="reporte_alcance(this.value);" required>
											<option value=""></option>
											<option value="1">Si aplica</option>
											<option value="0">No aplica</option>
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
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catregion_activo" name="reporte_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-5 d-none">
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
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="1">1.- Introducción</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_introduccion" id="form_reporte_introduccion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_introduccion" name="reporte_introduccion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_introduccion">Guardar introducción <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- ======== ELIMINAR DESPUES DE SUBIR AL SERVIDOR =============-->
		<div class="col-12 mt-4 mb-4" style="text-align: center;">
			<button type="submit" class="btn btn-info waves-effect waves-light" id="btn_descargar_plantilla">Descargar plantilla principal <i class="fa fa-download"></i></button>
		</div>
		<!-- ======== ELIMINAR DESPUES DE SUBIR AL SERVIDOR =============-->


		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="2">2.- Definiciones</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva definición" id="boton_reporte_nuevadefinicion">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva definición
							</button>
						</ol>
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
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
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
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivoespecifico" name="reporte_objetivoespecifico" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_objetivoespecifico">Guardar objetivos específicos <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="4">4.- Reconocimiento inicial de los factores</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4" id="form_reporte_metodologia_4">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reporte_metodologia_4" name="reporte_metodologia_4" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4">Guardar punto 4 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="5">5.- Metodología</h4>
				<h4 class="card-title" id="5_1">5.1.- Método de evaluación</h4>
				<div class="div_proporcionalidad_dimencionalidad" style="text-align: justify;">
					<span style="color: #000; font-weight: bold;">Evaluación de la proporcionalidad y dimensionalidad</span><br><br>

					Los servicios sanitarios dentro de las instalaciones de trabajo hoy en día son de vital importancia para las necesidades de cada trabajador, lo cual son de carácter obligatorio. Pero es importante mencionar que dentro de la industria hay riesgos biológicos que están presentes, por lo cual es importante conocer y establecer la organización y cuantificación de los servicios sanitarios presentes dentro de una instalación.<br><br>
					Para la identificación de servicios se tomó en cuenta los Reglamentos de Construcción del Municipio del Centro, Estado de Tabasco, así como el Reglamento de Construcciones para el estado de Veracruz-Llave, establecen la cantidad y tipo de servicios con los que debe contar un centro de trabajo, tales como: escusados, lavabos, regaderas y en caso de ser sanitarios para hombres, los mingitorios, tomando en consideración la cantidad de trabajadores y los metros cuadrados (m²) de construcción.<br><br>
					En lo que se refiere a estos reglamentos, se aclara que no existe una normatividad aplicable a nivel nacional que proporcione bases para la evaluación de la proporcionalidad y dimensionalidad de servicios para el personal, por lo cual se consideraron los reglamentos estatales de construcción, debido a que estos son los únicos que establecen datos específicos en relación al número de baños, mingitorios, lavamanos y regaderas.<br><br>
					Con la finalidad de una mayor identificación, este método consiste en llevar acabo los siguientes pasos los cuales ayudaron a identificar el cumplimiento de este.<br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_1.jpg" width="70%" height="50%"></div><br>

					<span style="color: #000; font-weight: bold;">Paso 1. Verificar el tipo de instalación</span><br><br>

					Durante la verificación de la instalación se corroboró a qué tipo de industria pertenece, estas pueden ser de tipo Industriales, almacenes, bodegas o en su caso oficinas; con el fin de tener en cuenta un amplio panorama de la instalación, así como su tipología en cuanto a la infraestructura.<br><br>

					<span style="color: #000; font-weight: bold;">Paso 2. Identificación actividades que se realizan</span><br><br>

					Se identificó el tipo de actividad que realizan dentro de la instalación, registrando las actividades del personal correspondiente en cada una de las áreas de trabajo, además por inspección visual registraron los servicios con los que cuenta, tales como: aire acondicionado, comedor, sanitarios, etc.<br><br>

					<span style="color: #000; font-weight: bold;">Paso 3. Conteo de los trabajadores</span><br><br>

					El conteo de los trabajadores es de vital importancia, este es un factor que facilita la identificación de los servicios con que debe contar una instalación conforme al número total de los trabajadores existentes en las áreas.<br><br>
					<span style="color: #000; font-weight: bold;">Paso 4. Corroborar la superficie de la instalación</span><br><br>
					Se corroboró la superficie de la instalación cuantificando el número de servicios sanitarios con el que se cuenta, comparándolo con lo dictaminado en los reglamentos de referencia, para ello se tomó la superficie en metros cuadrados (m²) y la información de apoyo recabada con anterioridad del número total de los trabajadores existentes en las áreas que fueron seleccionadas para la elaboración del estudio.<br><br>
					<span style="color: #000; font-weight: bold;">Cuantificación de trabajadores</span><br><br>
					Para la cuantificación y estimación de estos servicios se tomó en cuenta el “Reglamento de Construcción del Municipio de Centro, Estado de Tabasco “que aplica para toda obra, ya sea de instalación pública o privada que realice una construcción dentro del territorio, el cual se basa en la cuantificación de servicios sanitarios de acuerdo al número de trabajadores y las actividades que realizan.<br><br>
					Para la identificación de servicios sanitarios en industrias como lo son bodegas, almacenes o en instalaciones donde las actividades de los trabajadores generen desaseo se aplicó la siguiente tabla en la cual hace referencia a la cantidad de trabajadores de la instalación.<br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_2.jpg" width="70%" height="50%"></div><br>

					Tomando en cuenta que el límite es de 100 trabajadores al sobrepasar esta cantidad se le sumaran dos servicios más a partir de la cantidad establecida anteriormente.<br><br>
					Cuando el centro de trabajo sean edificios u oficinas, se debe considerar que no aplica la misma cuantificación que el caso anterior, debido a que las actividades que se realizan no se encuentran dentro del listado de referencia.<br><br>
					A continuación, se muestra una tabla de los servicios con los que se debe contar para estos centros de trabajo, así como la cantidad mínima que deben cubrir.<br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_3.jpg" width="70%" height="50%"></div><br>

					Para otro tipo de instalaciones con actividades o infraestructura más específica se debe tener en cuenta los siguientes valores con lo cual se podrá determinar la cuantificación de los servicios sanitarios mínimos que esta debe cumplir, donde de igual forma es importante corroborar las actividades que estos desempeñen.<br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_4.jpg" width="70%" height="50%"></div><br>

					<span style="color: #000; font-weight: bold;">Cuantificación de servicios por superficie</span><br><br>

					Para la cuantificación y estimación de estos servicios se tomó en cuenta el “Reglamento de Construcciones del estado de Veracruz-Llave” que aplica para toda obra ya sea de instalación pública o privada que realice una construcción dentro del territorio, un método el cual se basó en la cuantificación de servicios sanitarios en relación a la superficie en m² de la instalación.<br><br>
					Este método toma en cuenta la superficie en metros cuadrados donde 300 m² es lo mínimo y como máximo 1000 m² (Artículo 141. Servicios sanitarios, Capítulo X. Edificios para comercios y oficinas).<br><br>
					Se deben corroborar las dimensiones de la instalación donde el personal lleva a cabo sus actividades, para este método se cuantifica el número de servicios por género, teniendo en cuenta un número de servicios para hombres y otro para mujeres, en el caso de las mujeres la cuantificación será contabilizada como prioridad de acuerdo con lo establecido por cada 1000 m².<br><br>
					A continuación, se muestra la tabla con los valores para la cuantificación de los servicios sanitarios conforme a los m² dentro de la instalación.<br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_5.jpg" width="70%" height="50%"></div><br>
				</div>
				<div style="text-align: justify;">
					<span style="color: #000; font-weight: bold;">Requisitos de seguridad y servicios al personal en el centro de trabajo</span><br><br>

					Se realizó un recorrido por la instalación con el fin de verificar y observar el cumplimiento requerido de acuerdo con lo establecido en la NOM-001-STPS-2008, Edificios, locales, instalaciones y áreas en los centros de trabajo-Condiciones de seguridad.<br><br>

					<span style="color: #000; background-color: #FEFB24; line-height: 10px!important;">NOTA SOFTWARE: Si durante la evaluación se mencionan procedimientos, estas mismas se enlistarán automáticamente en este apartado en la impresión del informe (word).</span><br><br>

					<span style="color: #000; font-weight: bold;">Evaluación del riesgo de seguridad</span><br><br>

					Los riesgos de trabajo y en general los riesgos inherentes a los procesos industriales, han sido estudiados a través de múltiples etapas técnicas que van desde el análisis basado en la intuición, experiencia y aplicación de métodos de observación directa hasta técnicas altamente sofisticadas.<br><br>
					Un método efectivo para la evaluación de riesgos consiste inicialmente en la identificación de la fuente del riesgo, seguidamente se determina el probable receptor del riesgo para luego estimar su dimensión (calculado con base en la probabilidad de que ocurra, el grado de exposición y las consecuencias del riesgo).<br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_6.jpg" width="70%" height="50%"></div><br>

					<span style="color: #000; font-weight: bold;">Paso 1. Identificación del riesgo</span><br><br>

					La identificación del riesgo se basó principalmente en la observación directa, análisis de datos históricos y estimaciones de acuerdo con el tipo de actividades que se desarrollan durante la operación de la instalación.<br><br>

					<span style="color: #000; font-weight: bold;">Paso 2. Determinación del receptor</span><br><br>

					El receptor del riesgo corresponde al sujeto expuesto directa o indirectamente y que es susceptible a sufrir la consecuencia del riesgo. Los principales receptores en este caso son el ser humano y el ecosistema.
					La finalidad de la determinación del agente receptor del riesgo determinó las prioridades del plan de contingencias en función de la dimensión del riesgo.<br><br>

					<span style="color: #000; font-weight: bold;">Paso 3. Estimación del grado de peligrosidad</span><br><br>

					El cálculo del grado de peligrosidad se deriva del producto de la probabilidad (P) por la exposición (E) por la consecuencia (C); de cada uno de los riesgos identificados, la misma que se expresa en la siguiente ecuación que corresponde al modelo de W. T. Fine.<br><br>

					<span style="color: #000; font-weight: bold; text-align: center!important;">GP = P x E x C</span><br><br>

					<span style="color: #000; font-weight: bold;">Probabilidad (P)</span> se entiende como la posibilidad de que el riesgo se manifieste en cualquier momento.<br><br>
					En la tabla <span style="color: #000; font-weight: bold;">probabilidad de riesgo</span> se presentan valores cuantitativos para la probabilidad con el fin de poder determinar la dimensión de los riesgos.<br><br>

					<span style="color: #000; font-weight: bold;">Probabilidad de riesgo</span><br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_7.jpg" width="70%" height="50%"></div><br>

					<span style="color: #000; font-weight: bold;">Exposición (E)</span> se entiende como el proceso mediante el cual un organismo entra en contacto con un peligro; la exposición o acceso es lo que cubre la brecha entre el peligro y el riesgo.<br><br>
					La finalidad de la determinación del agente receptor del riesgo, es establecer las prioridades del plan de contingencias en función de la dimensión del riesgo.<br><br>
					En la tabla <span style="color: #000; font-weight: bold;">exposición al riesgo</span> se presentan valores cuantitativos para cada tipo de exposición con el fin de poder determinar la dimensión de los riesgos.<br><br>

					<span style="color: #000; font-weight: bold;">Exposición al riesgo</span><br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_8.jpg" width="70%" height="50%"></div><br>

					<span style="color: #000; font-weight: bold;">Consecuencias (C)</span>, representa otro factor importante para evaluar la dimensión del riesgo, se refiere al grado de efecto sobre el receptor al manifestarse el riesgo.<br><br>
					En la tabla <span style="color: #000; font-weight: bold;">consecuencias del riesgo</span> se presentan valores cuantitativos para cada tipo de consecuencia con el fin de determinar la dimensión del riesgo.<br><br>

					<span style="color: #000; font-weight: bold;">Consecuencias del riesgo</span><br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_9.jpg" width="70%" height="50%"></div><br>

					Una vez determinados los valores cuantitativos de cada factor se procede a estimar la dimensión del riesgo basado en los valores establecidos para cada tipo de riesgo identificado.<br><br>
					El valor cuantitativo obtenido del cálculo del grado de peligrosidad con base a los valores asignados para la probabilidad, exposición y consecuencia de cada riesgo identificado determinan las prioridades de atención para evitar que dichos riesgos se manifiesten durante la fase de operación de la instalación.<br><br>
					En la tabla <span style="color: #000; font-weight: bold;">dimensión del riesgo</span> se presentan los valores de dimensión de riesgo y su interpretación para establecer prioridades de actuación<br><br>

					<span style="color: #000; font-weight: bold;">Dimensión del riesgo</span><br><br>

					<div class="imagen_formula"><img src="/assets/images/reportes/serviciopersonal/img_10.jpg" width="70%" height="50%"></div><br>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="6">6.- Reconocimiento</h4>
				<h4 class="card-title" id="6_1">6.1.- Ubicación de la instalación</h4>
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
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="7">7.- Descripción del proceso en la instalación</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_procesoinstalacion" id="form_reporte_procesoinstalacion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<label style="color: #000000;">Descripción del proceso en la instalación</label>
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_procesoinstalacion" name="reporte_procesoinstalacion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_procesoinstalacion">Guardar proceso instalación <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="8">8.- Evaluación</h4>
				<h4 class="card-title" id="8_1">8.1.- Proporcionalidad y dimensionalidad</h4>
				<div class="row div_proporcionalidad_dimencionalidad">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Evaluar / Editar" id="boton_reporte_evaluacionpyd">
								<span class="btn-label"><i class="fa fa-check-square-o"></i></span>Evaluar / Editar
							</button>
						</ol>
						<div id="div_proporcionalidad_dimensionalidad_resultados"></div>
					</div>
				</div>
				<div class="row div_proporcionalidad_dimencionalidad_NA" style="display: none;">
					<div class="col-12">No aplica.</div>
				</div>
				<h4 class="card-title" id="8_2">8.2.- Condiciones físicas de higiene y funcionalidad</h4>
				<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de evaluación" id="boton_reporte_evaluacion">
						<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de evaluación
					</button>
				</ol>
				<table class="table table-hover stylish-table tabla_reporte" width="100%" id="tabla_reporte_evaluacion">
					<thead>
						<tr>
							<th width="80">Tipo</th>
							<th width="60">No</th>
							<th width="120">Lugar</th>
							<th width="">Punto a verificar</th>
							<th width="100">Cumplimiento</th>
							<th width="60">Editar</th>
							<th width="60">Eliminar</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table><br>
				<h4 class="card-title" id="8_3">8.3.- Descripción de medios de proliferación de fauna nociva</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_punto_8_3" id="form_reporte_punto_8_3">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="10" id="reporte_punto_8_3" name="reporte_punto_8_3" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_punto_8_3">Guardar punto 8.3 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="8_4">8.4.- Focos de desarrollo y anidación de fauna nociva</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_punto_8_4" id="form_reporte_punto_8_4">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="10" id="reporte_punto_8_4" name="reporte_punto_8_4" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_punto_8_4">Guardar punto 8.4 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="9">9.- Conclusiones</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_conclusion" id="form_reporte_conclusion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="18" id="reporte_conclusion" name="reporte_conclusion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="10">10.- Recomendaciones de control</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_recomendaciones" id="form_reporte_recomendaciones">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="18" id="reporte_recomendaciones" name="reporte_recomendaciones" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_recomendaciones">Guardar recomendaciones <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="11">11.- Responsables del informe</h4>
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
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="12">12.- Anexos</h4>
				<h4 class="card-title" id="12_1">12.1.- Anexo 1.- Memoria fotográfica</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> Debido a que las evidencias fotográficas se encuentran en el capítulo 8.2 “Condiciones físicas de higiene y funcionabilidad”, se atiende la omisión en este apartado.
				</p>
				<h4 class="card-title" id="12_2">12.2.- Anexo 2.- Valoración de riesgo de la condición insegura detectada.</h4>
				<p class="justificado" id="nota_12_2">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> En las áreas de la instalación <span class="div_instalacion_nombre">INSTALACION</span> no se identificaron condiciones inseguras, por consecuencia no puede ser valorado en este apartado.
				</p>
				<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar nueva condición insegura detectada" id="boton_reporte_nuevacondicionesinseguras">
						<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva condición insegura detectada
					</button>
				</ol>
				<style type="text/css">
					#tabla_reporte_condicionesinseguras th {
						font-size: 10px !important;
						/* 							display: inline-table; */
					}

					#tabla_reporte_condicionesinseguras td {
						font-size: 10px !important;
					}
				</style>
				{{-- <table class="table table-hover tabla_info_centrado" width="100%" data-paging="false" data-info="false" id="tabla_reporte_condicionesinseguras"> --}}
				<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_condicionesinseguras">
					<thead>
						<tr>
							<th width="" rowspan="2">No</th>
							<th width="" rowspan="2">Zona o<br>Área</th>
							<th width="" rowspan="2">Actividad<br>general</th>
							<th width="" rowspan="2">Rutinario<br>(Si o No)</th>
							<th width="" colspan="2">Peligro</th>
							<th width="" rowspan="2">Efectos<br>posibles</th>
							<th width="" colspan="3">Controles existentes</th>
							<th width="" colspan="2">Evaluacion del riesgo</th>
							<th width="" colspan="2">Grado de peligrosidad</th>
							<th width="" rowspan="2">Editar</th>
							<th width="" rowspan="2">Eliminar</th>
						</tr>
						<tr>
							<th width="">Descripción</th>
							<th width="">Clasificación</th>
							<th width="">Fuente</th>
							<th width="">Medio</th>
							<th width="">Individuo</th>
							<th width="">Probabilidad</th>
							<th width="">Exposición</th>
							<th width="">Consecuencia</th>
							<th width="">GP=PxExC</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div class="imagen_formula" id="img_12_2"><img src="/assets/images/reportes/serviciopersonal/img_10.jpg" width="70%" height="50%"></div>
				<h4 class="card-title" id="12_3">12.3.- Anexo 3.- Hojas de campo</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_anexosresultados" id="form_reporte_anexosresultados">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_anexosresultados">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="70">Seleccionado</th>
										<th>Documento</th>
										<th width="160">Fecha carga</th>
										<th width="60">Mostrar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table><br><br>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexosresultados">Guardar anexos <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="12_4">12.4.- Anexo 4.- Plano de ubicación de las fuentes generadoras y puntos evaluados.</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> Debido a que en la evaluación de infraestructura para servicios al personal no se consideran fuentes generadoras y/o puntos de evaluación, se atiende la omisión en este apartado.
				</p><br>
				<form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los planos de las carpetas seleccionadas se agregaran en el informe de Infraestructura de servicios al Personal.</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_planos">
								<thead>
									<tr>
										<th width="60">Seleccionado</th>
										<th width="">Carpeta de planos</th>
										<th width="100">Total planos</th>
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
				<h4 class="card-title" id="12_5">12.5.- Anexo 5.- Equipo utilizado en la medición</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> En este estudio no se utilizaron equipos para determinar las condiciones de infraestructura; solamente se siguieron los criterios técnicos de inspección de acuerdo con la NOM-001-STPS-2008.
				</p>
				<h4 class="card-title" id="12_6">12.6.- Anexo 6.-Incertidumbre de la medición</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> La declaración de cumplimiento correspondiente a las mediciones en la evaluación de infraestructura para servicios al personal, quedan fuera del alcance de política de la estimación de incertidumbre, dada la naturaleza de resultados, en los que se identifican cualitativos y semicuantitativos.
				</p>
				<h4 class="card-title" id="12_7">12.7.- Anexo 7.-Informe de resultados</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> La declaración de cumplimiento para la evaluación de infraestructura de servicio al personal en este informe no requiere de resultados emitidos por un laboratorio.
				</p>
				<h4 class="card-title" id="12_8">12.8.- Anexo 8.- Copia de aprobación/autorización del laboratorio de ensayo ante la STPS</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> Para lo solicitado en el presente apartado, cabe mencionar que no requiere de una aprobación por parte de la STPS en las evaluaciones de infraestructura para servicios al personal.
				</p>
				<h4 class="card-title" id="12_9">12.9.- Anexo 9.- Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
				<p class="justificado">
					<b style="color: #333333; font-weight: bold;">Nota aclaratoria:</b> Para lo solicitado en el presente apartado, cabe mencionar que no requiere de una acreditación por parte de la EMA en las evaluaciones de infraestructura para servicios al personal.
				</p>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="13">Generar informe .docx + Anexos .Zip</h4>
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
			</div>
		</div>
	</div>
</div>


<!-- ============================================================== -->
<!-- MODAL-CARGANDO -->
<!-- ============================================================== -->
<div id="modal_cargando" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-sm" style="max-width: 600px!important; margin-top: 250px;">
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
<!-- MODAL-REPORTE-EVALUACION P Y D -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_evaluacionpyd>.modal-dialog {
		min-width: 900px !important;
	}

	#modal_reporte_evaluacionpyd .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_evaluacionpyd .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_evaluacionpyd" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_evaluacionpyd" id="form_modal_evaluacionpyd">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Título</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteserviciopersonalevaluacionpyd_id" name="reporteserviciopersonalevaluacionpyd_id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Área / Instalación</label>
								<input type="text" class="form-control" id="reporteserviciopersonalevaluacionpyd_areainstalacion" name="reporteserviciopersonalevaluacionpyd_areainstalacion" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Tipología</label>
								<select class="custom-select form-control" id="reporteserviciopersonalevaluacionpyd_tipologia" name="reporteserviciopersonalevaluacionpyd_tipologia" required>
									<option value=""></option>
									<option value="1">Industrias, almacenes y bodegas donde se manipule materiales y sustancias que ocasionen manifiesto desaseo</option>
									<option value="2">Oficinas</option>
									<option value="3">Otras industrias, almacenes</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Número de personas</label>
								<input type="number" min="1" class="form-control" id="reporteserviciopersonalevaluacionpyd_personas" name="reporteserviciopersonalevaluacionpyd_personas" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>M<sup>2</sup> de la instalación (<span style="color: #000;">0 = No aplica</span>)</label>
								<input type="number" step="any" min="0" class="form-control" id="reporteserviciopersonalevaluacionpyd_m2" name="reporteserviciopersonalevaluacionpyd_m2" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Escusados</label>
								<input type="number" min="0" class="form-control" id="reporteserviciopersonalevaluacionpyd_escusados" name="reporteserviciopersonalevaluacionpyd_escusados" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Lavabos</label>
								<input type="number" min="0" class="form-control" id="reporteserviciopersonalevaluacionpyd_lavabos" name="reporteserviciopersonalevaluacionpyd_lavabos" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Mingitorios</label>
								<input type="number" min="0" class="form-control" id="reporteserviciopersonalevaluacionpyd_mingitorios" name="reporteserviciopersonalevaluacionpyd_mingitorios" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Regaderas</label>
								<input type="number" min="0" class="form-control" id="reporteserviciopersonalevaluacionpyd_Regaderas" name="reporteserviciopersonalevaluacionpyd_Regaderas" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nota proporcionalidad (opcional): </label>
								<textarea class="form-control" rows="2" id="reporteserviciopersonalevaluacionpyd_notap" name="reporteserviciopersonalevaluacionpyd_notap"></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nota dimencionalidad (opcional): </label>
								<textarea class="form-control" rows="2" id="reporteserviciopersonalevaluacionpyd_notad" name="reporteserviciopersonalevaluacionpyd_notad"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_evaluacionpyd">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EVALUACION P Y D -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_evaluacion>.modal-dialog {
		min-width: 90% !important;
	}

	#modal_reporte_evaluacion .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_evaluacion .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}

	#modal_reporte_evaluacion .modal-body .form-group .boton_eliminaevidencia {
		position: absolute;
		margin-top: 6px;
		margin-left: 8px;
		z-index: 50;
		text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF;
		cursor: pointer;
		display: block;
	}

	#modal_reporte_evaluacion .modal-body .form-group .dropify-wrapper {
		max-height: 220px !important;
		/*tamaño estatico del campo foto*/
		/* border: 1px #f00 solid; */
	}
</style>
<div id="modal_reporte_evaluacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_evaluacion" id="form_modal_evaluacion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Título</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteserviciopersonalevaluacion_id" name="reporteserviciopersonalevaluacion_id" value="0">
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Número de punto</label>
								<input type="number" min="1" class="form-control" id="reporteserviciopersonalevaluacion_punto" name="reporteserviciopersonalevaluacion_punto" required>
							</div>
						</div>
						<div class="col-8">
							<div class="form-group">
								<label>Lugar a evaluar</label>
								<input type="text" class="form-control" id="reporteserviciopersonalevaluacion_lugar" name="reporteserviciopersonalevaluacion_lugar" placeholder="ejemplo: Escaleras de servicios comunes" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Cumplimiento</label>
								<select class="custom-select form-control" id="reporteserviciopersonalevaluacion_cumplimiento" name="reporteserviciopersonalevaluacion_cumplimiento" onchange="evaluacion_cumplimiento(this)" required>
									<option value=""></option>
									<option value="Si cumple">Si cumple</option>
									<option value="No cumple">No cumple</option>
									<option value="No aplica">No aplica</option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Punto a verificar</label>
								<select class="custom-select form-control" id="reporteserviciopersonalevaluacioncatalogo_id" name="reporteserviciopersonalevaluacioncatalogo_id" onchange="punto_verificar(this.value);" required>
									<option value=""></option>
									<optgroup label="0. Otro (procedimiento)">
										<option value="0">Ingresar información de forma manual</option>
									</optgroup>
									<?php $grupo = 'XXX'; ?>
									@foreach($evaluacion_catalogo AS $key => $value)
									@if($grupo != $value->reporteserviciopersonalevaluacioncatalogo_titulo)
									@if($key > 0)
									</optgroup>
									<optgroup label="{{ $value->reporteserviciopersonalevaluacioncatalogo_titulo }}">
										@else
									<optgroup label="{{ $value->reporteserviciopersonalevaluacioncatalogo_titulo }}">
										@endif

										<?php $grupo = $value->reporteserviciopersonalevaluacioncatalogo_titulo; ?>
										@endif

										<option value="{{ $value->id }}">{{ substr($value->reporteserviciopersonalevaluacioncatalogo_descripcion, 0, 140).'...' }}</option>

										@if($key == (count($evaluacion_catalogo)-1))
									</optgroup>
									@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Título (punto a verificar)</label>
								<textarea class="form-control" rows="2" id="reporteserviciopersonalevaluacion_titulo" name="reporteserviciopersonalevaluacion_titulo" placeholder="ejemplo: 7.4. Escaleras." required></textarea>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Procedimiento (opcional)</label>
								<textarea class="form-control" rows="2" id="reporteserviciopersonalevaluacion_procedimiento" name="reporteserviciopersonalevaluacion_procedimiento" placeholder="ejemplo: PO-SO-TC-0011-2020: Procedimiento Operativo, para la Identificación y Mejora de los Servicios Para el Personal en los Centros de Trabajo."></textarea>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Descripción (punto a verificar)</label>
								<textarea class="form-control" rows="10" id="reporteserviciopersonalevaluacion_descripcion" name="reporteserviciopersonalevaluacion_descripcion" required></textarea>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Evidencia 1</label>
								<i class="fa fa-trash fa-2x text-danger boton_eliminaevidencia" data-toggle="tooltip" title="Eliminar evidencia 1" id="btneval_eliminaevidencia1" onclick="elimina_evidencia($('#reporteserviciopersonalevaluacion_id').val(), 1);"></i>
								<input type="hidden" class="form-control" id="reporte_imgbase64_evidencia1" name="reporte_imgbase64_evidencia1">
								<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="220" data-show-loader="true" id="reportefileevidencia1" name="reportefileevidencia1" onchange="redimencionar_foto('reportefileevidencia1', 'reporte_imgbase64_evidencia1', 'botonguardar_modal_evaluacion');">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Evidencia 2</label>
								<i class="fa fa-trash fa-2x text-danger boton_eliminaevidencia" data-toggle="tooltip" title="Eliminar evidencia 2" id="btneval_eliminaevidencia2" onclick="elimina_evidencia($('#reporteserviciopersonalevaluacion_id').val(), 2);"></i>
								<input type="hidden" class="form-control" id="reporte_imgbase64_evidencia2" name="reporte_imgbase64_evidencia2">
								<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="220" data-show-loader="true" id="reportefileevidencia2" name="reportefileevidencia2" onchange="redimencionar_foto('reportefileevidencia2', 'reporte_imgbase64_evidencia2', 'botonguardar_modal_evaluacion');">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Observación</label>
								<textarea class="form-control" rows="3" id="reporteserviciopersonalevaluacion_observacion" name="reporteserviciopersonalevaluacion_observacion" required></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_evaluacion">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-CONDICIONES INSEGURAS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_condicionesinseguras>.modal-dialog {
		min-width: 80% !important;
	}

	#modal_reporte_condicionesinseguras .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_condicionesinseguras .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}

	#modal_reporte_condicionesinseguras .dropify-wrapper {
		width: 100%;
		max-width: 100%;
		max-height: 150px !important;
	}
</style>
<div id="modal_reporte_condicionesinseguras" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_condicioninsegura" id="form_modal_condicioninsegura">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Definición</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="condicioninsegura_id" name="condicioninsegura_id" value="0">
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Zona o Área</label>
								<select class="custom-select form-control" id="condicioninsegurareportearea_id" name="reportearea_id" required>
									<option value=""></option>
									@foreach ($areaspoe as $area)
									<option value="{{ $area->id }}">{{ $area->reportearea_nombre }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Actividad general</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_actividad" name="reporteserviciopersonalcondicioninsegura_actividad" placeholder="Ejem. Recorridos" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Peligro (descripción)</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_descripcion" name="reporteserviciopersonalcondicioninsegura_descripcion" placeholder="Ejem. Faltante de falso plafón" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Peligro (clasificación)</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_clasificacion" name="reporteserviciopersonalcondicioninsegura_clasificacion" placeholder="Ejem. Físico" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Rutinario</label>
								<select class="custom-select form-control" id="reporteserviciopersonalcondicioninsegura_rutinario" name="reporteserviciopersonalcondicioninsegura_rutinario" required>
									<option value=""></option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Efectos posibles</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_efectos" name="reporteserviciopersonalcondicioninsegura_efectos" placeholder="Ejem. Caída de objetos" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Fuente</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_fuente" name="reporteserviciopersonalcondicioninsegura_fuente" placeholder="Ejem. Deterioro" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Medio</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_medio" name="reporteserviciopersonalcondicioninsegura_medio" placeholder="Ejem. No cuenta con señalización" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Probabilidad</label>
								<input type="number" step="any" class="form-control" id="reporteserviciopersonalcondicioninsegura_probabilidad" name="reporteserviciopersonalcondicioninsegura_probabilidad" onchange="condicioninsegura_resultado()" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Exposición</label>
								<input type="number" step="any" class="form-control" id="reporteserviciopersonalcondicioninsegura_exposicion" name="reporteserviciopersonalcondicioninsegura_exposicion" onchange="condicioninsegura_resultado()" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Consecuencia</label>
								<input type="number" step="any" class="form-control" id="reporteserviciopersonalcondicioninsegura_consecuencia" name="reporteserviciopersonalcondicioninsegura_consecuencia" onchange="condicioninsegura_resultado()" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Resultado (GP=P*E*C)</label>
								<input type="text" class="form-control" id="reporteserviciopersonalcondicioninsegura_resultado" name="reporteserviciopersonalcondicioninsegura_resultado" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-3">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Evidencia fotográfica 1</label>
										<input type="hidden" class="form-control" id="condicioninsegurafoto1_base64" name="condicioninsegurafoto1_base64">
										<input type="file" class="dropify" data-height="150" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-show-loader="true" id="condicioninsegurafoto1" name="condicioninsegurafoto1" onchange="redimencionar_foto('condicioninsegurafoto1', 'condicioninsegurafoto1_base64', 'botonguardar_modal_condicioninsegura');" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Evidencia fotográfica 2 (opcional)</label>
										<input type="hidden" class="form-control" id="condicioninsegurafoto2_base64" name="condicioninsegurafoto2_base64">
										<input type="file" class="dropify" data-height="150" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-show-loader="true" id="condicioninsegurafoto2" name="condicioninsegurafoto2" onchange="redimencionar_foto('condicioninsegurafoto2', 'condicioninsegurafoto2_base64', 'botonguardar_modal_condicioninsegura');">
									</div>
								</div>
							</div>
						</div>
						<div class="col-9">
							<label style="line-height: 12px;">Individuo (Categorías)</label>
							<div id="condicioninsegura_categorias" style="height: 340px; max-height: 340px; overflow-y: auto; overflow-x: hidden; border: 1px #D5D5D5 solid; padding-left: 10px;">
								lista
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modal_condicioninsegura">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_condicioninsegura">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-CONDICIONES INSEGURAS -->
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

<script type="text/javascript">
	var proveedor_id = <?php echo json_encode($proveedor_id); ?>;
	var proyecto = <?php echo json_encode($proyecto); ?>;
	var recsensorial = <?php echo json_encode($recsensorial); ?>;
	var areaspoe = <?php echo json_encode($areaspoe); ?>;
	var categoriaspoe = <?php echo json_encode($categoriaspoe); ?>;
	var evaluacion_puntosverificarcatalogo = <?php echo json_encode($evaluacion_catalogo); ?>;
</script>
<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reporteserviciopersonal.js"></script>