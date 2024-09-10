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
				<a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#4_3" class="list-group-item submenu">4.3.- Límites máximos permisibles de exposición a vibraciones <i class="fa fa-times" id="menureporte_4_3"></i></a>
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
				<a href="#7_1" class="list-group-item submenu">7.1.- Tabla de resultados <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Registro de la evaluación de la exposición a los niveles de vibración <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Memoria de cálculo de los NEV cuando se evalúe exposición sin usar instrumentos de lectura directa <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Copia de los certificados o aviso de calibración del equipo <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Informe de resultados <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_8"></i></a>
				<a href="#11_9" class="list-group-item submenu">11.9.- Anexo 9: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_9"></i></a>
				<a href="#12" class="list-group-item">12.- Seleccionar Anexos 8 (STPS) y 9 (EMA) <i class="fa fa-times" id="menureporte_12"></i></a>
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

								<div class="col-12 ">
									<div class="form-group">
										<label>Alcance del informe</label>
										<select class="custom-select form-control" id="reporte_alcanceinforme" name="reporte_alcanceinforme" onchange="reporte_alcance(this.value);" required>
											<option value=""></option>
											<option value="1">Cuerpo entero</option>
											<option value="2">Extremidades superiores</option>
											<option value="3">Cuerpo entero y Extremidades superiores</option>
										</select>
									</div>
								</div>
							</div>
						</div>


						<h3 class="mx-4 mt-5 mb-4">Seleccione las opciones que desee mostrar en la Portada Interna del Informe</h3>
						<div class="col-1" style="display: none;">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catsubdireccion_activo" name="reporte_catsubdireccion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11" style="display: none;">
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
						<div class="col-1" style="display: none;">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>


								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catgerencia_activo" name="reporte_catgerencia_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11" style="display: none;">
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
						<div class="col-1" style="display: none;">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catactivo_activo" name="reporte_catactivo_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11" style="display: none;">
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
						<div class="col-12" style="display: none;">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporte_instalacion" name="reporte_instalacion" onchange="instalacion_nombre(this.value);" readonly>
							</div>
						</div>

						<div class="col-1" style="display: none;">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catregion_activo" name="reporte_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-5" style="display: none;">
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2">4.2.- Método de evaluación</h4>
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
							/*font-size:0.7vw!important;*/
							text-align: center;
							vertical-align: middle;
						}

						.tabla_metodologia td {
							padding: 1px !important;
							/*font-size:0.7vw!important;*/
							text-align: center;
							border: 1px #E5E5E5 solid !important;
							vertical-align: middle;
						}

						.tabla_metodologia tr:hover td {
							color: #000000;
						}
					</style>
					<div class="col-12">
						<div class="texto_metodologia">
							<div class="info_cuerpoentero">
								Para la evaluación de vibraciones se realizó en cada ciclo de exposición la medición correspondiente al POE usando los procedimientos de evaluación para cuerpo entero.<br><br>

								Para la evaluación en cuerpo entero en cada punto de medición, se localizan tres ejes ortogonales de acuerdo con la figura 1 (NOM-024-STPS-2001), en los que se realizan las mediciones continuas de la aceleración y se registran al menos durante un minuto en cada una de las bandas de tercios de octava.<br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.2_1.jpg" height="500"></div>
								<b style="text-align: center!important; margin: 0px auto!important;">Figura 1.</b> Direcciones de incidencia de las vibraciones sobre el cuerpo humano (NOM-024-STPS-2001).<br><br>


								ax, ay, az. son las direcciones de la aceleración en los ejes x, y, z.<br>
								eje x es la dirección de espalda a pecho.<br>
								eje y es la dirección de lado derecho a izquierdo.<br>
								eje z es la dirección de los pies o parte inferior, a la cabeza.<br><br>

								La evaluación se realizó de acuerdo con el procedimiento establecido en el punto 8.3.2.1. Procedimiento de evaluación de vibraciones para cuerpo entero de la NOM-024-STPS-2001 “Vibraciones-Condiciones de seguridad e higiene en los centros de trabajo”.<br><br>
							</div>

							<div class="info_extremidades">
								Para la evaluación en extremidades superiores (mano-brazo) en cada punto de medición, se localizan tres ejes ortogonales, cercanos al punto de contacto de las vibraciones con la mano, de acuerdo con lo mostrado por los sistemas de coordenadas biodinámicas y basicéntricas de la figura 2, en los que se realizan las mediciones continuas de la aceleración y se registran al menos durante un minuto, en cada una de las bandas de tercios de octava.<br><br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.2_2.jpg" height="300"></div>
								<b style="text-align: center!important; margin: 0px auto!important;">Figura 2.</b> Sistemas biodinámico y basicéntrico de coordenadas (NOM-024-STPS-2001).<br><br>


								Se realiza un análisis espectral en bandas de tercios de octava (de 8 a 1600 Hz) por cada eje y se calculó el componente direccional de la aceleración ponderada conforme a la siguiente ecuación:<br><br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.2_3.jpg" height="100"></div><br>

								Dónde:<br><br>

								<b>ak:</b> es el componente direccional de la aceleración ponderada;<br>
								<b>T:</b> es la duración de la exposición diaria;<br>
								<b>Kj:</b> es la iésima frecuencia ponderada, valor cuadrático medio de la componente de la aceleración con duración Ti.<br><br>
							</div>

							<div class="info_adicional">
								En <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b> se realizó la metodología de cuerpo entero y de acuerdo al reconocimiento inicial realizado, para las mediciones de extremidades superiores (mano-brazo) no se encontraron puestos de trabajo que tengan este tipo de exposición, por lo que esta metodología no será aplicada.
							</div>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="4_3">4.3.- Límites máximos permisibles de exposición a vibraciones</h4>
				<div class="row">
					<div class="col-12">
						<div class="texto_metodologia">
							<div class="info_cuerpoentero">
								<b>En cuerpo entero.</b><br><br>

								Cuando se conoce la frecuencia de un mecanismo que genera vibración y se relaciona con la aceleración en m/s<sup>2</sup> ya sea en el eje de aceleración longitudinal a<sub>z</sub>, o en los ejes de aceleración transversal a<sub>x</sub> y a<sub>y</sub>, se obtiene el tiempo de exposición que puede variar de un minuto a veinticuatro horas. Los límites de exposición a vibraciones en el eje longitudinal a<sub>z</sub> y en los ejes transversales a<sub>x</sub> y a<sub>y</sub>, se establecen en las Tablas 1 y 2 de la norma, respectivamente.<br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.3_1.jpg" height="640"></div><br>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.3_2.jpg" height="400"></div><br>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.3_3.jpg" height="640"></div><br>
								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.3_4.jpg" height="450"></div><br>
							</div>

							<div class="info_extremidades">
								<b>En extremidades superiores</b><br><br>

								Dependiendo del tiempo de exposición, se establecen los valores permitidos de aceleración ponderada (que se deben calcular según se establece en los Apartados 8.3.2.2.1 al 8.3.2.2.6, y en la Tabla 3).<br><br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportevibracion_fig_4.3_5.jpg" height="250"></div><br>

								<b>(*) Nota</b>: Comúnmente, uno de los ejes de vibración domina sobre los dos restantes. Si uno o más ejes de vibración sobrepasan la exposición total diaria, se han sobrepasado los valores de los límites máximos de exposición.
							</div>
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
						<p class="justificado">En este apartado se muestra la actividad principal desarrollada en la instalación, involucrando al personal / categoría adscrito en cada área que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>.</p>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_area">
							<thead>
								<tr>
									<th width="50">No.</th>
									<th width="130">Instalación</th>
									<th width="150">Área</th>
									<th width="">Categoría</th>
									<th width="60">Total</th>
									<th width="60">Editar</th>
									{{-- <th width="60">Eliminar</th> --}}
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="5_4">5.4.- Actividades del personal expuesto</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describen las actividades realizadas en cada área con exposición a vibraciones según la categoría encontrada en dicho sitio.</p>
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
						<p class="justificado">Para esta sección se han registrado las áreas, fuentes generadoras de vibraciones, cantidad de equipo y/o herramienta y tipo de exposición que representa.</p>
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_5">
								<thead>
									<tr>
										<th width="40">No.</th>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="">Fuente generadora</th>
										<th width="70">Cantidad</th>
										<th width="130">Tipo de exposición</th>
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
						<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación se presentan por porcentaje en la siguiente tabla:</p>
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
						<p class="justificado">En este apartado se muestra la identificación de áreas, categoría a evaluar, método de evaluación y número de puntos evaluados.</p>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="150">Área</th>
									<th width="">Categoría</th>
									<th width="130">Método</th>
									<th width="120">Puntos por área</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th colspan="5">Total de puntos evaluados</th>
									<td id="total_puntosmedicion" style="font-weight: bold; color: #777777;">0</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">En este capítulo se presentan los resultados obtenidos durante la evaluación de vibraciones en tipo de evaluación cuerpo entero por punto de medición.</p>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de medición" id="boton_reporte_nuevopuntomedicion">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de medición
							</button>

							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Importar" id="boton_reporte_vibracion_importar_resultados">
								<span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Importar
							</button>

						</ol>
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
						<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_puntos">
							<thead>
								<tr>
									<th width="60">No. de<br>Medición</th>
									<th width="120">Instalación</th>
									<th width="130">Área</th>
									<th width="130">Punto de<br>evaluación</th>
									<th width="">Categoría</th>
									<th width="80">Tipo<br>evaluación</th>
									<th width="80">Numero<br>de mediciones</th>
									<th width="80">Tiempo<br>Exposición</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_1">7.1.- Tabla de resultados</h4>
				<div class="row">
					<div class="col-12">
						<style type="text/css">
							.tabla_resultados th {
								padding: 2px 1px !important;
								font-size: 0.55vw !important;
								text-align: center;
								vertical-align: middle;
							}

							.tabla_resultados td {
								padding: 2px 1px !important;
								font-size: 0.55vw !important;
								text-align: center;
								vertical-align: middle;
							}
						</style>
						<table class="table table-hover tabla_info_centrado tabla_resultados" width="100%" id="tabla_reporte_7_1">
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_2">7.2.- Matriz de exposición laboral</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico vibraciones en cuerpo entero, así como información del área física y de la plantilla laboral de la instalación en cuestión.</p>

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
						</table>
					</div>
				</div>
				<h4 class="card-title" id="8">8.- Conclusiones</h4>
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
								font-size: 1.1vw !important;
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
								font-size: 0.85vw !important;
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
										<th colspan="6">
											<b style="font-size:1.3vw!important; font-weight: 600; color: #000000;">
												Evaluación de Vibraciones en:<br><span class="div_instalacion_nombre">NOMBRE INSTALACION</span>
											</b>
										</th>
									</tr>
									<tr>
										<th colspan="2" width="32%">Número de puntos de medición</th>
										<th colspan="2" width="36%">Cumplimiento normativo</th>
										<th colspan="2" width="32%">Recomendaciones emitidas</th>
									</tr>
									<tr>
										<td colspan="2" height="180">
											<i class="fa fa-podcast text-success" style="font-size: 6vw!important;" id="dashboard_puntos"> 0</i><br>
										</td>
										<td colspan="2">
											<i class="fa fa-line-chart text-info" style="font-size: 6vw!important;" id="dashboard_cumplimiento">0%</i><br>
										</td>
										<td colspan="2">
											<i class="fa fa-pencil-square-o text-warning" style="font-size: 6.5vw!important;" id="dashboard_recomendaciones"> 0</i><br>
										</td>
									</tr>
									<tr>
										<th colspan="3" width="50%">Distribución de puntos de evaluación<br><i class="fa fa-braille fa-3x"></i></th>
										<th colspan="3" width="50%">Categorías evaluadas (<span style="color: #FF0000; font-weight: normal;">● Críticas</span>)<br><i class="fa fa-male fa-3x"></i></th>
									</tr>
									<tr>
										<td colspan="3" height="230">
											<span id="dashboard_distribucionpuntos">Distribución</span>
										</td>
										<td colspan="3">
											<span id="dashboard_categoriasevaluadas">Categorías</span>
										</td>
									</tr>
									<tr>
										<td colspan="6" style="font-style: italic; font-size: 11px!important;">Análisis derivado del informe de resultados de la evaluación de vibraciones - condiciones de seguridad e higiene en los centros de trabajo (NOM-024-STPS-2001).</td>
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
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Las <span id="reporte_memoriafotografica_lista">0</span> fotos encontradas se agregaran en la impresión del informe de vibración.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_2">11.2.- Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los planos de las carpetas seleccionadas se agregaran en el informe de temperatura.</p><br>
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
				<h4 class="card-title" id="11_3">11.3.- Anexo 3: Registro de la evaluación de la exposición a los niveles de vibración</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Registro de la evaluación de la exposición a los niveles de vibración debe seleccionarlo en el punto "11.7.- Anexo 7: Informe de resultados".</p>
					</div>
				</div>
				<h4 class="card-title" id="11_4">11.4.- Anexo 4: Memoria de cálculo de los NEV cuando se evalúe exposición sin usar instrumentos de lectura directa</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> La Memoria de cálculo de los NEV cuando se evalúe exposición sin usar instrumentos de lectura directa debe seleccionarlo en el punto "11.7.- Anexo 7: Informe de resultados".</p>
					</div>
				</div>
				<h4 class="card-title" id="11_5">11.5.- Anexo 5: Equipo utilizado en la medición</h4>
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_equipoutilizado">
									Guardar equipo utilizado <i class="fa fa-save"></i>
								</button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_6">11.6.- Anexo 6: Copia de certificados o aviso de calibración del equipo</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los certificados de los equipos utilizados seleccionados en el punto "11.5.- Anexo 5: Equipo utilizado en la medición" se adjuntará en la impresión del reporte en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_7">11.7.- Anexo 7: Informe de resultados</h4>
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexosresultados">Guardar anexos resultados <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_8">11.8.- Anexo 8: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 8", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_9">11.9.- Anexo 9: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 9", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="12">12.- Seleccionar Anexos 7 (STPS) y 7 (EMA)</h4>
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
<!-- MODAL-REPORTE-ÁREA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_area>.modal-dialog {
		min-width: 1100px !important;
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
								<input type="text" class="form-control" id="reportearea_instalacion" name="reportearea_instalacion" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reportearea_nombre" name="reportearea_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" min="1" class="form-control" id="reportearea_orden" name="reportearea_orden" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reportevibracionarea_porcientooperacion" name="reportevibracionarea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Tipo de exposición</label>
								<select class="custom-select form-control" id="reportearea_tipoexposicion" name="reportearea_tipoexposicion" required>
									<option value=""></option>
									<option value="NA">No aplica</option>
									<option value="Cuerpo entero">Cuerpo entero</option>
									<option value="Extremidades superiores">Extremidades superiores</option>
									<option value="Cuerpo entero y extremidades superiores">Cuerpo entero y extremidades superiores</option>
								</select>
							</div>
						</div>
					</div>


					<div class="col-12 p-2 text-center">
						<label class="text-danger mr-4 d-block" style="font-size: 18px;" data-toggle="tooltip" title="" data-original-title="Marque la casilla de NO si el área no fue evaluada en el reconocimiento">¿ Área evaluada en el reconocimiento ?</label>
						<div class="d-flex justify-content-center">
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="aplica_vibracion" id="aplica_vibracion_si" value="1" required="required" checked>
								<label class="form-check-label" for="aplica_vibracion_si">
									Si
								</label>
							</div>
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="aplica_vibracion" id="aplica_vibracion_no" value="0" required="required">
								<label class="form-check-label" for="aplica_vibracion_no">
									No
								</label>
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
											<th width="100" style="padding: 3px 0px!important;">Total</th>
											<th width="100" style="padding: 3px 0px!important;">GEH</th>
											<th width="" style="padding: 3px 0px!important;">Actividades</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div><br>

							<ol class="breadcrumb" style="padding: 6px; margin: 0px 0px 6px 0px; text-align: left;">
								<button type="button" class="btn btn-default waves-effect waves-light" style="height: 26px; padding: 3px 8px;" data-toggle="tooltip" title="Agregar fuente generadora" id="botonnueva_areamaquina">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Fuente generadora en el área
								</button>
							</ol>
							<div style="margin: 6px 0px 0px 0px!important; padding: 0px!important; max-height: 180px; overflow-y: auto; overflow-x: hidden;" id="div_tabla_areamaquinaria">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areamaquinaria">
									<thead>
										<tr>
											<th width="" style="padding: 3px 0px!important;">Fuentes emisoras</th>
											<th width="80" style="padding: 3px 0px!important;">Cantidad</th>
											<th width="60" style="padding: 3px 0px!important;">Eliminar</th>
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
							<input type="hidden" class="form-control" id="reportevibracionevaluacion_id" name="reportevibracionevaluacion_id" value="0">
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reportevibracionarea_id" name="reportearea_id" onchange="reportevibracionevaluacioncategorias(this.value, 0);" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Punto de evaluación (Ubicación)</label>
								<input type="text" class="form-control" id="reportevibracionevaluacion_puntoevaluacion" name="reportevibracionevaluacion_puntoevaluacion" placeholder="Ej. Cobertizo de bombas" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Punto de medición</label>
								<input type="number" min="1" maxlength="6" class="form-control" id="reportevibracionevaluacion_punto" name="reportevibracionevaluacion_punto" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Categoría</label>
								<select class="custom-select form-control" id="reportevibracioncategoria_id" name="reportecategoria_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Nombre del trabajador</label>
								<input type="text" class="form-control" id="reportevibracionevaluacion_nombre" name="reportevibracionevaluacion_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Ficha</label>
								<input type="text" class="form-control" id="reportevibracionevaluacion_ficha" name="reportevibracionevaluacion_ficha" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Criterio de evaluación</label>
								<select class="custom-select form-control" id="reportevibracionevaluacion_tipoevaluacion" name="reportevibracionevaluacion_tipoevaluacion" onchange="tipo_evaluacion(this.value);" required>
									<option value=""></option>
									<option value="1">Límites por NOM-024-STPS-2001</option>
									<option value="2">Límites por interpolación</option>
									<option value="3">Método ISO</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label id="label_campo1">Tiempo de exposición</label>

								<select class="custom-select form-control" style="display: inline-block;" id="reportevibracionevaluacion_tiempoexposicion" name="reportevibracionevaluacion_tiempoexposicion" onchange="tiempo_exposicion(this.value);" required>
									<option value=""></option>
									<option value="1 min">1 min</option>
									<option value="16 min">16 min</option>
									<option value="25 min">25 min</option>
									<option value="1 h">1 h</option>
									<option value="2.5 h">2.5 h</option>
									<option value="4 h">4 h</option>
									<option value="8 h">8 h</option>
									<option value="16 h">16 h</option>
									<option value="24 h">24 h</option>
								</select>

								<input type="text" class="form-control" style="display: none;" id="reportevibracionevaluacion_promedio" name="reportevibracionevaluacion_promedio" placeholder="Ej. 44min, 10 h, etc..." onchange="tiempo_exposicion2(this.value, form_modal_puntomedicion.reportevibracionevaluacion_tipoevaluacion.value)" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label id="label_campo2">Numero de mediciones por ejes</label>

								<select class="custom-select form-control" style="display: inline-block;" id="reportevibracionevaluacion_numeromediciones" name="reportevibracionevaluacion_numeromediciones" onchange="numero_mediciones(this.value);" required>
									<option value=""></option>
									<option value="1">1</option>
									<option value="3">3</option>
								</select>

								<input type="text" class="form-control" style="display: none;" id="reportevibracionevaluacion_valormaximo" name="reportevibracionevaluacion_valormaximo" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Fecha de medición</label>
								<div class="input-group">
									<input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="reportevibracionevaluacion_fecha" name="reportevibracionevaluacion_fecha" required>
									<span class="input-group-addon"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 4px; margin: 0px 0px 10px 0px; text-align: center;">Resultados del punto de medición</ol>
							<style type="text/css">
								#tabla_evaluacion_puntodatos th {
									padding: 2px 1px !important;
									font-size: 0.7vw !important;
									text-align: center !important;
									vertical-align: middle !important;
									border: 1px #DDDDDD solid !important;
									background: #F5F5F5;
								}

								#tabla_evaluacion_puntodatos td {
									padding: 2px 1px !important;
									font-size: 0.7vw !important;
									text-align: center !important;
									vertical-align: middle !important;
									border: 1px #DDDDDD solid !important;
								}

								#tabla_evaluacion_puntodatos td input.form-control {
									min-height: 18px !important;
									padding: 2px !important;
									font-size: 12px !important;
									margin: 0px !important;
								}
							</style>
							<table class="table table-hover" id="tabla_evaluacion_puntodatos" width="100%">
								<thead>
									<tr>
										<th>Frecuencia central de tercio de octava (Hz)</th>
										<th colspan="3">Medición de aceleración<br>longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>)</th>
										<th>Límite de aceleración longitudinal en (a<sub>z</sub>) (m/s<sup>2</sup>) para<br><span class="punto_tiempoexposicion">tiempo</span> de exposición</th>
										<th colspan="3">Medición de aceleración<br>transversal en (a<sub>x</sub>) (m/s<sup>2</sup>)</th>
										<th colspan="3">Medición de aceleración<br>transversal (a<sub>y</sub>) (m/s<sup>2</sup>)</th>
										<th>Límite de aceleración transversal en<br>(a<sub>x</sub>, a<sub>y</sub>) (m/s<sup>2</sup>) para<br><span class="punto_tiempoexposicion">tiempo</span> de exposición</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="1.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="1.25" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="1.60" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="2.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="2.50" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="3.15" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="4.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="5.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="6.30" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="8.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="10.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="12.50" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="16.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="20.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="25.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="31.50" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="40.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="50.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="63.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
									<tr>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_frecuencia[]" value="80.00" readonly></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_az3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_azlimite[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ax3[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay1[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay2[]" value="" required></td>
										<td width="8.33%"><input type="number" step="any" min="0" class="form-control" name="reportevibracionevaluaciondatos_ay3[]" value="" required></td>
										<td width="8.33%"><input type="text" class="form-control" style="border: 1px #999999 solid;" name="reportevibracionevaluaciondatos_axylimite[]" value="" required></td>
									</tr>
								</tbody>
							</table>
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
<!-- MODAL-IMPORTAR-RESULTADOS -->
<!-- ============================================================== -->

<div id="modal_excel_resultados" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post" name="formExcelResultado" id="formExcelResultado">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Cargar resultados por medio de un Excel</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<div class="form-group">
								<label> Documento Excel *</label>
								<div class="fileinput fileinput-new input-group" data-provides="fileinput">
									<div class="form-control" data-trigger="fileinput" id="input_file_excel_documento_resultado">
										<i class="fa fa-file fileinput-exists"></i>
										<span class="fileinput-filename"></span>
									</div>
									<span class="input-group-addon btn btn-secondary btn-file">
										<span class="fileinput-new">Seleccione</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" accept=".xls,.xlsx" name="excelResultado" id="excelResultado" required>
									</span>
									<a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mx-2" id="alertaVerificacion1" style="display:none">
						<p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el archivo Excel contenga los formatos válidos </p>
					</div>
					<div class="row mt-3" id="divCargaResultados" style="display: none;">

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

					<button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarExcelResultados">
						Cargar puntos <i class="fa fa-upload" aria-hidden="true"></i>
					</button>

				</div>
			</form>
		</div>
	</div>
</div>



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
</script>
<script src="/js_sitio/html2canvas.js"></script>
<script src="/js_sitio/reportes/reportevibracion.js?v=5.0"></script>