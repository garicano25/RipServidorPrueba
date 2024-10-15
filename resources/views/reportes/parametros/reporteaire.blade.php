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
				<a href="#4_2" class="list-group-item submenu">4.2.- Evaluación de los agentes y factores <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#5" class="list-group-item">5.- Reconocimiento</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
				<a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Tabla de identificación de las áreas <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#6_2" class="list-group-item submenu">6.2.- Método de evaluación <i class="fa fa-times" id="menureporte_6_2"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados</a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Tabla de resultados de bioaerosoles <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Tabla de resultados de temperatura del aire <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#7_3" class="list-group-item submenu">7.3.- Tabla de resultados de velocidad del aire <i class="fa fa-times" id="menureporte_7_3"></i></a>
				<a href="#7_4" class="list-group-item submenu">7.4.- Resultados de humedad relativa <i class="fa fa-times" id="menureporte_7_4"></i></a>
				<a href="#7_5" class="list-group-item submenu">7.5.- Tabla de resultados del Monóxido de Carbono (CO) <i class="fa fa-times" id="menureporte_7_5"></i></a>
				<a href="#7_6" class="list-group-item submenu">7.6.- Tabla de resultados del Dióxido de Carbono (CO<sub>2</sub>) <i class="fa fa-times" id="menureporte_7_6"></i></a>
				<a href="#7_7" class="list-group-item submenu">7.7.- Tabla de resultados del Dióxido de azufre (SO<sub>2</sub>)<i class="fa fa-times" id="menureporte_7_7"></i></a>
				<a href="#7_8" class="list-group-item submenu">7.8.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_8"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de los puntos de muestreo <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Informe de resultados <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Copia de certificados o aviso de calibración del equipo <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#12" class="list-group-item">12.- Seleccionar Anexos 6 (STPS) y 7 (EMA) <i class="fa fa-times" id="menureporte_12"></i></a>
				<a href="#13" class="list-group-item submenu" id="menu_opcion_final">Generar informe <i class="fa fa-download text-success" id="menureporte_13"></i></a>
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
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								{{-- <label style="color: #000000;">Introducción</label> --}}
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
								{{-- <label style="color: #000000;">Objetivos específicos</label> --}}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivoespecifico" name="reporte_objetivoespecifico" required></textarea>
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
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								{{-- <label style="color: #000000;">Reconocimiento de los agentes y factores</label> --}}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2">4.2.- Evaluación de los agentes y factores</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2" id="form_reporte_metodologia_4_2">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<div class="form-group">
								{{-- <label style="color: #000000;">Método de Muestreo</label> --}}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_metodologia_4_2" name="reporte_metodologia_4_2" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<div class="row">
					<style type="text/css">
						.texto_metodologia {
							/*font-size:0.7vw!important;*/
							text-align: justify;
						}

						.tabla_metodologia th {
							background: #F9F9F9;
							border: 1px #E5E5E5 solid !important;
							padding: 1px !important;
							font-size: 0.7vw !important;
							text-align: center;
							vertical-align: middle;
						}

						.tabla_metodologia td {
							padding: 1px !important;
							font-size: 0.7vw !important;
							text-align: center;
							border: 1px #E5E5E5 solid !important;
							vertical-align: middle;
						}

						.tabla_metodologia tr:hover td {
							color: #000000;
						}
					</style>
					<div class="col-12">
						<div class="texto_metodologia">Los valores considerados como límites permisibles son los siguientes:</div>
						<div class="informacion_estatica"><br>
							<table class="table tabla_metodologia" width="100%">
								<thead>
									<tr>
										<td colspan="3"><br><b>Tabla 1.- Límites permisibles</b><br><br></td>
									</tr>
									<tr>
										<th width="40%">Característica</th>
										<th width="20%">Límite permisible</th>
										<th width="40%">Normatividad de referencia</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Temperatura del aire</td>
										<td>22-24.5°C</td>
										<td rowspan="3" style="text-align: justify!important;">NOM-001-STPS-2008, Edificios, locales, instalaciones y áreas en los centros de trabajo - Condiciones de seguridad.</td>
									</tr>
									<tr>
										<td>Humedad relativa</td>
										<td>20-60%</td>
										{{-- <td style="text-align: justify!important;">NOM-001-STPS-2008, Edificios, locales, instalaciones y áreas en los centros de trabajo - Condiciones de seguridad.</td> --}}
									</tr>
									<tr>
										<td>Velocidad del aire</td>
										<td>0.15-0.25 m/s</td>
										{{-- <td style="text-align: justify!important;">NOM-001-STPS-2008, Edificios, locales, instalaciones y áreas en los centros de trabajo - Condiciones de seguridad.</td> --}}
									</tr>
									<tr>
										<td>Concentración de monóxido de carbono (CO)</td>
										<td>25 ppm</td>
										<td rowspan="3" style="text-align: justify!important;">NOM-010-STPS-2014, Agentes químicos contaminantes del ambiente laboral - Reconocimiento, evaluación y control.</td>
									</tr>
									<tr>
										<td>Concentración de dióxido de carbono (CO₂)</td>
										<td>5000 ppm</td>
										{{-- <td style="text-align: justify!important;">NOM-010-STPS-2014, Agentes químicos contaminantes del ambiente laboral - Reconocimiento, evaluación y control.</td> --}}
									</tr>
									<tr>
										<td>Concentración de dióxido de azufre (SO₂)</td>
										<td>0.25 ppm</td>
										{{-- <td style="text-align: justify!important;">NOM-010-STPS-2014, Agentes químicos contaminantes del ambiente laboral - Reconocimiento, evaluación y control.</td> --}}
									</tr>
									<tr>
										<td>Cuenta de microorganismos Coliformes Totales en placa (CT)</td>
										<td>500 UFC</td>
										<td rowspan="3" style="text-align: justify!important;">Referencias publicadas por la EPA, INSST y OMS</td>
									</tr>
									<tr>
										<td>Cuenta Total de Mesofílicos Aerobios (CTMA)</td>
										<td>500 UFC</td>
										{{-- <td style="text-align: justify!important;">Referencias publicadas por la EPA, INSST y OMS</td> --}}
									</tr>
									<tr>
										<td>Hongos y Levaduras</td>
										<td>500 UFC</td>
										{{-- <td style="text-align: justify!important;">Referencias publicadas por la EPA, INSST y OMS</td> --}}
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
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
						</div>
						<div class="col-12">
							<div class="form-group">
								<label style="color: #000000;">Descripción del proceso en la instalación</label>
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_procesoinstalacion" name="reporte_procesoinstalacion" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label style="color: #000000;">Descripción de la actividad principal</label>
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_actividadprincipal" name="reporte_actividadprincipal" required></textarea>
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
						</table>
						<br><br>
						<p class="justificado">En este apartado se muestra la actividad desarrollada en la instalación, involucrando al personal/categoría adscrito en cada área que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p><br>
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
					<div class="col-12">
						<p class="justificado">En este apartado se muestran las actividades desarrolladas por cada categoría durante su jornada laboral en su respectiva área de trabajo.</p>
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_4">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="250">Categoría</th>
										<th width="">Actividades</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="5_5">5.5.- Tabla de identificación de las áreas</h4>
				<div class="row">
					<div class="col-12">
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_5">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="">Sistema de ventilación /<br>Tipo de aire acondicionado</th>
										<th width="">Características</th>
										<th width="80">Cantidad</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="6">6.- Evaluación</h4>
				<h4 class="card-title" id="6_1">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>, se presentan por porcentaje en la siguiente tabla:</p>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_1">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="">Áreas de trabajo</th>
									<th width="100">Porcentaje de<br>operación</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="6_2">6.2.- Método de evaluación</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se presentan los métodos de evaluación para cada parámetro establecido por cada punto de medición en el centro de trabajo.</p><br>

						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2">
							<thead>
								<tr>
									<th width="40%">Parámetro</th>
									<th width="30%">Método</th>
									<th width="30%">Límite permisible</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Cuenta de microorganismos Coliformes Totales en placa (CT)</td>
									<td>NOM-113-SSA-1994</td>
									<td>500 UFC</td>
								</tr>
								<tr>
									<td>Cuenta Total de Mesofílicos Aerobios (CTMA)</td>
									<td>NOM-092-SSA1-1994</td>
									<td>500 UFC</td>
								</tr>
								<tr>
									<td>Levaduras</td>
									<td>NOM-111-SSA1-1994</td>
									<td>500 UFC</td>
								</tr>
								<tr>
									<td>Levaduras</td>
									<td>NOM-111-SSA1-1994</td>
									<td>500 UFC</td>
								</tr>
								<tr>
									<td>Temperatura del aire</td>
									<td>Sin Método</td>
									<td>22-24.5°C</td>
								</tr>
								<tr>
									<td>Humedad relativa</td>
									<td>Sin Método</td>
									<td>20-60%</td>
								</tr>
								<tr>
									<td>Velocidad del aire</td>
									<td>Sin Método</td>
									<td>0.15-0.25 m/s</td>
								</tr>
								<tr>
									<td>Concentración de monóxido de carbono (CO)</td>
									<td>NIOSH 6604</td>
									<td>25 ppm</td>
								</tr>
								<tr>
									<td>Concentración de dióxido de carbono (CO₂)</td>
									<td>AL-38-CO2</td>
									<td>5000 ppm</td>
								</tr>
								<tr>
									<td>Concentración de dióxido de azufre (SO₂)</td>
									<td>NIOSH-6004 1994
									</td>
									<td>0.25 ppm</td>
								</tr>
							</tbody>
						</table>

						<p class="justificado"><b>Nota</b>: El método de muestreo referenciado que se utiliza para la realización del estudio es NIOSH 0800 sin embargo, los análisis de cada uno de los parámetros se realizan bajo la metodología de las siguientes normas: Coliformes totales en placa (NOM-113-SSA-1994), bacterias mesofílicas (NOM-092-SSA1-1994), Levaduras y mohos (NOM-111-SSA1-1994) de las cuales se encuentran los signatarios acreditados para las pruebas analíticas en cada uno de los anexos de este informe.</p><br>

						<p class="justificado">A continuación, se describen los niveles de referencia aceptables de microorganismos en el aire y el polvo de ambientes de interior no industriales, bajo la referencia publicadas por la EPA, INSST y OMS:</p><br>

						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2">
							<thead>
								<tr>
									<th width="33.33%" rowspan="2">Categoría de contaminación</th>
									<th width="33.33%" colspan="2">UFC<sup>a</sup> por metro de aire</th>
									<th width="33.33%" rowspan="2">Hongos como UFC/g de polvos</th>
								</tr>
								<tr>
									<th width="16.66%">Bacterias</th>
									<th width="16.66%">Hongos</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Muy baja</td>
									<td>&lt;50</td>
									<td>&lt;25</td>
									<td>&lt;10.000</td>
								</tr>
								<tr>
									<td>Baja</td>
									<td>&lt;100</td>
									<td>&lt;100</td>
									<td>&lt;20.000</td>
								</tr>
								<tr>
									<td>Intermedia</td>
									<td>&lt;500</td>
									<td>&lt;500</td>
									<td>&lt;50.000</td>
								</tr>
								<tr>
									<td>Alta</td>
									<td>&lt;2.000</td>
									<td>&lt;2.000</td>
									<td>&lt;120.000</td>
								</tr>
								<tr>
									<td>Muy alta</td>
									<td>&gt;2.000</td>
									<td>&gt;2.000</td>
									<td>&gt;120.000</td>
								</tr>
							</tbody>
						</table>

					</div>
				</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de medición" id="boton_reporte_nuevopuntomedicion">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de medición
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_puntos">
							<thead>
								<tr>
									<th width="60">No. de<br>Medición</th>
									<th width="20%">Instalación</th>
									<th width="25%">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_1">7.1.- Tabla de resultados de bioaerosoles</h4>
				<div class="row">
					<div class="col-12">
						<style type="text/css">
							.tabla_evaluacion th {
								padding: 2px 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}

							.tabla_evaluacion td {
								padding: 2px 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}
						</style>
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_1">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="60">Total<br>puntos</th>
									<th width="">Parámetro</th>
									<th width="140">Método</th>
									<th width="80">Unidad</th>
									<th width="80">Límite<br>permisible</th>
									<th width="80">Resultado</th>
									<th width="100">Cumplimiento<br>normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_2">7.2.- Tabla de resultados de temperatura del aire</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_2">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">Límite permisible<br>en °C</th>
									<th width="100">Resultado<br>en °C</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_3">7.3.- Tabla de resultados de velocidad del aire</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_3">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">Límite permisible<br>en m/s</th>
									<th width="100">Resultado<br>en m/s</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table><br>
						<p class="justificado"><b>Nota:</b> La evaluación de velocidad de aire es referida al caudal del aire y movimiento, ya que dentro de las áreas se utilizan equipos Mini Split y estos equipos no cuentan con recambio de aire ni extracción-o succión.</p>
					</div>
				</div>
				<h4 class="card-title" id="7_4">7.4.- Resultados de humedad relativa</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_4">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">(Nivel de referencia)<br>en %</th>
									<th width="100">Resultado<br>en %</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_5">7.5.- Tabla de resultados del Monóxido de Carbono (CO)</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_5">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">Límite permisible<br>en ppm</th>
									<th width="100">Resultado<br>en ppm</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_6">7.6.- Tabla de resultados del Dióxido de Carbono (CO<sub>2</sub>)</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_6">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">Límite permisible<br>en ppm</th>
									<th width="100">Resultado<br>en ppm</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_7">7.7.- Tabla de resultados del Dióxido de azufre (SO<sub>2</sub>)</h4>
				<div class="row">
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_7">
							<thead>
								<tr>
									<th width="70">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="150">Área</th>
									<th width="">Ubicación</th>
									<th width="60">Total<br>puntos</th>
									<th width="100">Límite permisible<br>en ppm</th>
									<th width="100">Resultado<br>en ppm</th>
									<th width="100">Cumplimiento<br>Normativo</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_8">7.7.- Matriz de exposición laboral</h4>
				<div class="row">
					<div class="col-12">
						<style type="text/css">
							#tabla_reporte_matriz th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.5vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_matriz td {
								padding: 1px !important;
								font-size: 0.5vw !important;
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
							{{-- <thead>
									<tr>
										<th rowspan="2">Contador</th>
										<th rowspan="2">Subdirección o<br>corporativo</th>
										<th rowspan="2">Gerencia o<br>activo</th>
										<th rowspan="2">Instalación</th>
										<th rowspan="2">Área de<br>referencia<br>en atlas<br>de riesgo</th>
										<th rowspan="2">Nombre</th>
										<th rowspan="2">Ficha</th>
										<th rowspan="2">Categoría</th>
										<th rowspan="2">Número de<br>personas</th>
										<th rowspan="2">Grupo de<br>exposición<br>homogénea</th>
										<th rowspan="2">Resultados</th>
									</tr>
								</thead>
								<tbody></tbody> --}}
						</table>
					</div>
				</div>
				<h4 class="card-title" id="8">8.- Conclusiones</h4>
				<select class="custom-select form-control mb-1" style="width: 100%;" id="ID_CATCONCLUSION">
					<option value="">&nbsp;</option>
					@foreach($catConclusiones as $dato)
					<option value="{{$dato->ID_CATCONCLUSION}}" data-descripcion="{{$dato->DESCRIPCION}}">{{$dato->NOMBRE}}</option>
					@endforeach
				</select>
				<div class="row">
					<div class="col-12">
						<form method="post" enctype="multipart/form-data" name="form_reporte_conclusion" id="form_reporte_conclusion">
							<div class="row">
								<div class="col-12">
									{!! csrf_field() !!}
								</div>
								<div class="col-12">
									<div class="form-group">
										{{-- <label style="color: #000000;">Conclusiones</label> --}}
										<textarea class="form-control" style="margin-bottom: 0px;" rows="13" id="reporte_conclusion" name="reporte_conclusion" required></textarea>
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
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se plasman los resultados en la siguiente figura:</p><br>

						<style type="text/css">
							#tabla_dashboard {
								width: 100%;
								border: 3px #0BACDB solid;
							}

							#tabla_dashboard th {
								border: 1px #E9E9E9 solid;
								background: #0BACDB;
								color: #FFFFFF !important;
								padding: 4px;
								font-size: 0.9vw !important;
								line-height: 20px;
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
								font-size: 0.75vw !important;
								color: #555555;
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

						<div id="div_tabla_dashboard">
							<table class="table" width="100%" id="tabla_dashboard">
								<tbody>
									<tr>
										<th colspan="3">
											<b style="font-size:1.1vw!important; font-weight: 600; color: #000000;">
												Evaluación de la ventilación y calidad del aire interior en:<br><span class="div_instalacion_nombre">NOMBRE INSTALACION</span>
											</b>
										</th>
									</tr>
									<tr>
										<th width="66.66%" colspan="2">Cumplimiento normativo por parametro</th>
										<th width="33.33%">Total de Puntos evaluados</th>
									</tr>
									<tr>
										<td width="66.66%" colspan="2" rowspan="3">
											<span id="dashboard_parametros">parametros</span>
										</td>
										<td width="33.33%">
											<i class="fa fa-search text-success" style="font-size: 70px!important;" id="dashboard_puntos">0</i><br>
										</td>
									</tr>
									<tr>
										<th width="33.33%">Recomendaciones emitidas</th>
									</tr>
									<tr>
										<td width="33.33%">
											<i class="fa fa-pencil-square-o text-info" style="font-size: 70px!important;" id="dashboard_recomendaciones">0</i><br>
										</td>
									</tr>
									<tr>
										<th width="33.33%">Áreas criticas en Temperatura</th>
										<th width="33.33%">Áreas criticas en Velocidad</th>
										<th width="33.33%">Áreas criticas en Humedad relativa</th>
									</tr>
									<tr>
										<td width="33.33%" height="120">
											<span id="dashboard_temperatura">dato</span>
										</td>
										<td width="33.33%">
											<span id="dashboard_velocidad">dato</span>
										</td>
										<td width="33.33%">
											<span id="dashboard_humedad">dato</span>
										</td>
									</tr>
									<tr>
										<th width="33.33%">Áreas criticas en Monóxido de carbono (CO)</th>
										<th width="33.33%">Áreas criticas en Dióxido de carbono (CO<sub>2</sub>)</th>
										<th width="33.33%">
											<span>Áreas criticas en Bioaerosoles</span><br>
											<span style="font-size:0.7vw!important;">(Coliformes totales, Mesofílicos aerobios, Hongos, Levaduras)</span>
										</th>
									</tr>
									<tr>
										<td width="33.33%" height="120">
											<span id="dashboard_co">dato</span>
										</td>
										<td width="33.33%">
											<span id="dashboard_co2">dato</span>
										</td>
										<td width="33.33%">
											<span id="dashboard_bioaerosoles">dato</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
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
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Las <span id="reporte_memoriafotografica_lista">0</span> fotos encontradas se agregaran en la impresión del informe de aire</p>
					</div>
				</div>
				<h4 class="card-title" id="11_2">11.2.- Anexo 2: Planos de ubicación de los puntos de muestreo</h4>
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
				<h4 class="card-title" id="11_3">11.3.- Anexo 3: Equipo utilizado en la medición</h4>
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
				<h4 class="card-title" id="11_4">11.4.- Anexo 4: Informe de resultados del laboratorio</h4>
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
										<th width="160">Fecha carga</th>
										<th width="60">Mostrar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table><br><br>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexosresultados">Guardar anexos resultados <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_5">11.5.- Anexo 5: Copia de certificados o aviso de calibración del equipo</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los certificados de los equipos utilizados seleccionados en el punto 11.3 “Anexo 3: Equipo utilizado en la medición” se adjuntará en la impresión del reporte en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_6">11.6.- Anexo 6: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 6", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme boton_nuevanota" data-toggle="tooltip" title="Nueva nota aclaratoria" value="1">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva nota aclaratoria (stps)
							</button>
						</ol>
					</div>
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_notas_stps">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="">Nota aclaratoria STPS</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="11_7">11.7.- Anexo 7: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 7", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme boton_nuevanota" data-toggle="tooltip" title="Nueva nota aclaratoria" value="2">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva nota aclaratoria (ema)
							</button>
						</ol>
					</div>
					<div class="col-12">
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_notas_ema">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="">Nota aclaratoria EMA</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="12">12.- Seleccionar Anexos 6 (STPS) y 7 (EMA)</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_acreditacionaprobacion" id="form_reporte_acreditacionaprobacion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los arhivos seleccionados se adjuntarán en la impresión del reporte en formato PDF.</p>
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
							</table><br><br>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_acreditacionaprobacion">Guardar anexos 6 (STPS) y 7 (EMA) <i class="fa fa-save"></i></button>
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
						{{-- <div id="captura" style="width: 100%; border: 1px #000 solid;"></div><br> --}}
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
	<div class="modal-dialog modal-sm" style="max-width: 500px!important; margin-top: 250px;">
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
								<input type="text" class="form-control" id="reporteairecategoria_nombre" name="reporteairecategoria_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" class="form-control" id="reporteairecategoria_total" name="reporteairecategoria_total" required>
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
						<div class="col-9">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporteairearea_instalacion" name="reporteairearea_instalacion" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reporteairearea_porcientooperacion" name="reporteairearea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-9">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reporteairearea_nombre" name="reporteairearea_nombre" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" min="1" class="form-control" id="reporteairearea_numorden" name="reporteairearea_numorden" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Sistema de ventilación / Tipo de aire acondicionado</label>
								<input type="text" class="form-control" id="reporteairearea_ventilacionsistema" name="reporteairearea_ventilacionsistema" placeholder="Ej. Mini Split" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Características</label>
								<input type="text" class="form-control" id="reporteairearea_ventilacioncaracteristica" name="reporteairearea_ventilacioncaracteristica" placeholder="Ej. 110 v" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Cantidad</label>
								<input type="number" class="form-control" id="reporteairearea_ventilacioncantidad" name="reporteairearea_ventilacioncantidad" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Categorías en el área</ol>
							<div style="margin: -25px 0px 0px 0px!important; padding: 0px!important;">
								{{-- <style type="text/css">
									#tabla_areacategorias td
									{
										text-align: left;
										color: #777777;
										font-weight: bold;
									}
								</style> --}}
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
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_puntomedicion>.modal-dialog {
		min-width: 1200px !important;
	}

	#modal_reporte_puntomedicion .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_puntomedicion .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_puntomedicion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_puntomedicion" id="form_modal_puntomedicion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Titulo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteaireevaluacion_id" name="reporteaireevaluacion_id" value="0">
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Punto de medición</label>
								<input type="number" min="1" maxlength="6" class="form-control" id="reporteaireevaluacion_punto" name="reporteaireevaluacion_punto" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reporteaireevaluacion_area_id" name="reporteairearea_id" required onchange="aireevaluacion_categorias(form_modal_puntomedicion.reporteaireevaluacion_id.value, this.value);">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Ubicación</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_ubicacion" name="reporteaireevaluacion_ubicacion" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 4px; margin: 0px 0px 10px 0px; text-align: center;">Resultados</ol>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Coliformes totales (UFC)</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_ct" name="reporteaireevaluacion_ct" placeholder="500" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Mesofílicos Aerobios (UFC)</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_ctma" name="reporteaireevaluacion_ctma" placeholder="500" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Hongos (UFC)</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_hongos" name="reporteaireevaluacion_hongos" placeholder="500" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Levaduras (UFC)</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_levaduras" name="reporteaireevaluacion_levaduras" placeholder="500" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Temperatura del aire(°C)</label>
								<input type="number" step="any" min="0" class="form-control" id="reporteaireevaluacion_temperatura" name="reporteaireevaluacion_temperatura" placeholder="22 - 24.5" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Velocidad del aire(m/s)</label>
								<input type="number" step="any" min="0" class="form-control" id="reporteaireevaluacion_velocidad" name="reporteaireevaluacion_velocidad" placeholder="" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Límite Velocidad (m/s)</label>
								<select class="custom-select form-control" id="reporteaireevaluacion_velocidadlimite" name="reporteaireevaluacion_velocidadlimite" required>
									<option value=""></option>
									<option value="0.15">0.15</option>
									<option value="0.25">0.25</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Humedad del aire(%)</label>
								<input type="number" step="any" min="0" class="form-control" id="reporteaireevaluacion_humedad" name="reporteaireevaluacion_humedad" placeholder="20 - 60" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label style="font-size: 14px;">Monóxido de C. (CO) ppm</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_co" name="reporteaireevaluacion_co" placeholder="25" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label style="font-size: 14px;">Dióxido de C. (CO₂) ppm</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_co2" name="reporteaireevaluacion_co2" placeholder="5000" required>
							</div>
						</div>

						<div class="col-3">
							<div class="form-group">
								<label style="font-size: 14px;">Dióxido de azufre (SO₂) ppm</label>
								<input type="text" class="form-control" id="reporteaireevaluacion_so2" name="reporteaireevaluacion_so2" placeholder="0.25" required>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 4px; margin: 0px 0px 10px 0px; text-align: center;">Categorías expuestas</ol>
						</div>
						<div class="col-12">
							<div style="margin-top: -8px;">
								<table class="table table-hover tabla_info_centrado" style="margin-bottom: 0px;" width="100%" id="tabla_evaluacion_categorias">
									<thead>
										<tr>
											<th width="400">Categoría</th>
											<th width="300">Nombre</th>
											<th width="120">Ficha</th>
											<th width="100">GEO</th>
										</tr>
									</thead>
									<tbody>
										{{-- <tr>
											<td>
												<div class="switch" style="float: left;">
													<label>
														<input type="checkbox" class="checkbox_alcance" name="xxxxxxxxxxxxxx[]" value="0">
														<span class="lever switch-col-light-blue"></span>
													</label>
												</div>
												<label class="demo-switch-title" style="float: left;">Operador Especialista en Plantas Turbocompresores</label>
												<input type="hidden" class="form-control" name="reporteairecategoria_id[]" value="0" required>
												<b style="color: #0BACDB;">Operador Especialista en Plantas Turbocompresores</b>
											</td>
											<td>
												<input type="text" class="form-control" name="reporteaireevaluacioncategorias_nombre[]" required>
											</td>
											<td>
												<input type="text" maxlength="30" class="form-control" name="reporteaireevaluacioncategorias_ficha[]" required>
											</td>
											<td>
												<input type="number" min="0" maxlength="4" class="form-control" name="reporteaireevaluacioncategorias_geo[]" required>
											</td>
										</tr> --}}
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_puntomedicion">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EVALUACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-NOTAS ACLARATORIAS -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_notas>.modal-dialog {
		/*min-width: 1000px!important;*/
	}

	#modal_reporte_notas .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_notas .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_notas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_notas" id="form_modal_notas">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Nota aclaratoria</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reportenotas_id" name="reportenotas_id" value="0">
							<input type="hidden" class="form-control" id="reportenotas_tipo" name="reportenotas_tipo" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Descripción</label>
								<textarea class="form-control" rows="6" id="reportenotas_descripcion" name="reportenotas_descripcion" required></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_notas">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-NOTAS ACLARATORIAS -->
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
{{-- <link href="/assets/plugins/c3-master/c3.min.css" rel="stylesheet"> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/amcharts.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/serial.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/plugins/responsive/responsive.min.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/plugins/export/export.js" type="text/javascript"></script> --}}
{{-- <link href="/assets/plugins/amChart/amcharts/plugins/export/export.css" type="text/css" media="all"  rel="stylesheet"/> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/pie.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/themes/light.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/themes/black.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/themes/dark.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/themes/chalk.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/plugins/amChart/amcharts/themes/patterns.js" type="text/javascript"></script> --}}
<script type="text/javascript">
	var proveedor_id = <?php echo json_encode($proveedor_id); ?>;
	var proyecto = <?php echo json_encode($proyecto); ?>;
	var recsensorial = <?php echo json_encode($recsensorial); ?>;
	var categorias_poe = <?php echo json_encode($categorias_poe); ?>;
	var areas_poe = <?php echo json_encode($areas_poe); ?>;
</script>
<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reporteaire.js?v=5.0"></script>