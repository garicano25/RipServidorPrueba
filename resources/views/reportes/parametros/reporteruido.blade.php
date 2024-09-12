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
				<a href="#5_3" class="list-group-item submenu">5.3.- Descripción de los procesos que generen ruido <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#5_6" class="list-group-item submenu">5.6.- Equipo de Protección Personal Auditiva (EPPA) <i class="fa fa-times" id="menureporte_5_6"></i></a>
				<a href="#5_7" class="list-group-item submenu">5.7.- Equipo de protección personal básico y específico suministrado por la empresa al personal <i class="fa fa-times" id="menureporte_5_7"></i></a>
				<a href="#5_8" class="list-group-item submenu">5.8.- NS <sub>A</sub> instantáneo para identificar las áreas y fuentes emisoras <i class="fa fa-times" id="menureporte_5_8"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#6_2" class="list-group-item submenu">6.2.- Determinación de las áreas y puntos de evaluación <i class="fa fa-times" id="menureporte_6_2"></i></a>
				<a href="#6_3" class="list-group-item submenu">6.3.- Selección del método o métodos empleados para la evaluación de la exposición a ruido <i class="fa fa-times" id="menureporte_6_3"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados</a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Tabla de resultados del Nivel Sonoro Continuo Equivalente “A” (NSCE<sub>A, T</sub>) por punto de medición <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Tabla de resultados de la determinación del NER <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#7_3" class="list-group-item submenu">7.3.- Determinación del NER, porcentaje de dosis de la evaluación personal (del o los trabajadores) <i class="fa fa-times" id="menureporte_7_3"></i></a>
				{{--
				<a href="#7_4" class="list-group-item submenu">7.4.- Determinación del factor de reducción (R) del equipo de protección personal auditivo <i class="fa fa-times" id="menureporte_7_4"></i></a>
				<a href="#7_5" class="list-group-item submenu">7.5.- Resultados del Nivel de Ruido Efectivo (NRE) por modelo con mediciones de ruido en dB (A) <i class="fa fa-times" id="menureporte_7_5"></i></a>
				--}}
				<a href="#7_6" class="list-group-item submenu">7.4.- Resultados del Nivel de Ruido Efectivo (NRE) con modelo por bandas de octava <i class="fa fa-times" id="menureporte_7_6"></i></a>
				<a href="#7_7" class="list-group-item submenu">7.5.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_7"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de fuentes generadoras y puntos evaluados <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Memoria de cálculo y gráficas del NS<sub>A</sub> o bien, del NSCE<sub>A, T</sub> y NER según método utilizado <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Memoria de cálculo y gráfica del Nivel de Presión Acústica NPA en bandas de octava <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Copia de certificados o avisos de calibración del equipo <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Informe de resultados <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_8"></i></a>
				<a href="#11_9" class="list-group-item submenu">11.9.- Anexo 9: Copia del registro ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_9"></i></a>
				<a href="#12_1" class="list-group-item">12.1.- Seleccionar Anexos 3 (Memo. cal.) y 4 (Memo. cal.) y 7 (Info. resultados)</a>
				<a href="#12_2" class="list-group-item">12.2.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</a>
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
						<hr>
						<!-- <h4 style="padding: 0px!important;" id="0">Portada Exterior</h4> -->
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


								<div class="col-12">
									<input type="hidden" class="form-control" id="ID_RECURSO_INFORME" name="ID_RECURSO_INFORME" value="0">
									<input type="hidden" class="form-control" id="PROYECTO_ID_INFORME" name="PROYECTO_ID" value="0">
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



						<!-- <h4 style="padding: 0px!important;" id="0">Portada Interior</h4> -->
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
						<div class="col-4" style="display: none;"></div>
						<div class="col-1" style="display: none;">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporte_catregion_activo" name="reporte_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-3" style="display: none;">
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
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
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporte_metodologia_4_2" name="reporte_metodologia_4_2" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<div class="imagen_formula">Tabla 1<br>Límites Máximos Permisibles de Exposición (LMPE)</div><br>
								<table class="table tabla_info_centrado" width="100%">
									<thead>
										<tr>
											<th width="40%">NER</th>
											<th width="50%">TMPE</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>90 dB(A)</td>
											<td>8 HORAS</td>
										</tr>
										<tr>
											<td>93 dB(A)</td>
											<td>4 HORAS</td>
										</tr>
										<tr>
											<td>96 dB(A)</td>
											<td>2 HORAS</td>
										</tr>
										<tr>
											<td>99 dB(A)</td>
											<td>1 HORA</td>
										</tr>
										<tr>
											<td>102 dB(A)</td>
											<td>30 MINUTOS</td>
										</tr>
										<tr>
											<td>105 dB(A)</td>
											<td>15 MINUTOS</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-011-STPS-2001</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
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
							<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-reporteresponsable1documentoex: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
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
				<h4 class="card-title" id="5_3">5.3.- Descripción de los procesos que generen ruido</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describe el proceso de trabajo que genera ruido en cada una de las áreas evaluadas:</p><br>
						<table class="table tabla_info_centrado table-hover" width="100%" id="tabla_reporte_5_3">
							<thead>
								<tr>
									<th width="150">Instalación</th>
									<th width="200">Áreas de trabajo</th>
									<th width="">Proceso</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="5_4">5.4.- Población ocupacionalmente expuesta</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">En este apartado se muestra la actividad principal desarrollada en la instalación, involucrando al personal/categoría adscrito en cada área que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p><br>
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
									<th>Categoría</th>
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
										<th width="150">Instalación</th>
										<th width="200">Área</th>
										<th width="200">Categoría</th>
										<th width="">Actividades</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="5_6">5.6.- Equipo de Protección Personal Auditiva (EPPA)</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">En la siguiente tabla se describe el Equipo de Protección Personal Auditiva (EPPA) utilizado en cada una de las categorías pertenecientes a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p><br>
						<div class="informacion_estatica">
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_6">
								<thead>
									<tr>
										<th width="">Categoría</th>
										<th width="20%">Tipo</th>
										<th width="15%">Marca</th>
										<th width="15%">Modelo</th>
										<th width="8%">NRR</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo equipo auditivo" id="boton_reporte_nuevoequipoauditivo">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Equipo auditivo
							</button>
						</ol>
						<div id="div_tablas_equiposautivos"></div>
						{{-- <table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_6_equipoauditivo">
							</table> --}}
					</div>
				</div>
				<h4 class="card-title" id="5_7">5.7.- Equipo de protección personal básico y específico suministrado por la empresa al personal</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Equipo de protección general utilizado por los trabajadores de la instalación:</p><br>
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
				<h4 class="card-title" id="5_8">5.8.- NS <sub>A</sub> instantáneo para identificar las áreas y fuentes emisoras</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Se realizó un recorrido con un sonómetro o equipo de medición de ruido para conocer los valores instantáneos (NSA) emitidos por las fuentes generadores en todas las áreas donde los trabajadores realicen actividades, con el fin de establecer las zonas consideradas a evaluar, así como la característica del tipo de ruido generado.</p><br>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_8_UNO">
							<thead>
								<tr>
									<th width="130" rowspan="2">Instalación</th>
									<th width="" rowspan="2">Área</th>
									<th colspan="2">Lecturas del nivel instantáneo NS<sub>A</sub> en dB<sub>A</sub></th>
									<th width="120" rowspan="2">Tipo de<br>ruido</th>
									<th width="120" rowspan="2">Evaluación</th>
								</tr>
								<tr>
									<th width="150">Mínimo</th>
									<th width="150">Máximo</th>
									{{-- <th>3</th>
										<th>4</th>
										<th>5</th>
										<th>6</th>
										<th>7</th>
										<th>8</th>
										<th>9</th>
										<th>10</th> --}}
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<br>
						<p class="justificado">Además, durante el reconocimiento se identificaron las siguientes fuentes generadoras de ruido:</p><br>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_8_DOS">
							<thead>
								<tr>
									<th width="130">Instalación</th>
									<th width="300">Áreas de trabajo</th>
									<th>Máquina del área</th>
									<th width="90">Cantidad</th>
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
						<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>, se presentan por porcentaje en la siguiente tabla:</p><br>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_1">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="150">Instalación</th>
									<th width="">Áreas de trabajo</th>
									<th width="200">Porcentaje de operación</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="6_2">6.2.- Determinación de las áreas y puntos de evaluación</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Para la evaluación de ruido al que se expone el personal dentro de las áreas operativas, se ubicaron los puntos de medición en las zonas donde el nivel instantáneo NSA sea igual o mayor a los 80 dB, de acuerdo con lo establecido en la metodología descrita en la NOM-011-STPS-2001.<br><br>No se realizaron evaluaciones donde el nivel instantáneo descrito en el reconocimiento fue menor a los 80 dB, debido a que se consideran las áreas de acuerdo al Nivel instantáneo establecido en la NOM-011-STPS-2001.</p><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área y puntos de evaluación" id="boton_reporte_areaevaluacion">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Área y puntos de evaluación
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2">
							<thead>
								<tr>
									<th width="130">Instalación</th>
									<th width="220">Área</th>
									<th width="100">No. de<br>medición</th>
									<th>Ubicación</th>
									<th width="100">No. de<br>evaluaciones<br>por área</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th colspan="4">Total de puntos evaluados</th>
									<td><b id="areaevaluacion_totalpuntos">0</b></td>
									<td colspan="2"></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="6_3">6.3.- Selección del método o métodos empleados para la evaluación de la exposición a ruido</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodoevaluacion" id="form_reporte_metodoevaluacion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_metodoevaluacion" name="reporte_metodoevaluacion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodoevaluacion">Guardar método de evaluación <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<h4 class="card-title" id="7_1">7.1.- Tabla de resultados del Nivel Sonoro Continuo Equivalente “A” (NSCE<sub>A, T</sub>) por punto de medición</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de medición de nivel sonoro continuo" id="boton_reporte_nuevonivelsonoro">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de medición de nivel sonoro continuo
							</button>
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Importar puntos de medición de nivel sonoro continuo" id="boton_importar_puntos_71" onclick="abrirModalPuntos(1)">
								<span class="btn-label"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> Importar
							</button>
						</ol>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_1">
							<thead>
								<tr>
									<th width="100">No. Medición</th>
									<th>Ubicación</th>
									<th width="100">Periodo 1</th>
									<th width="100">Periodo 2</th>
									<th width="100">NSCE<sub>A, Ti</sub><br>Promedio</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5">No hay datos que mostrar</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_2">7.2.- Tabla de resultados de la determinación del NER</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme LMPE" data-toggle="tooltip" title="Nuevo punto de determinación del NER " id="boton_reporte_nuevopuntoner">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de determinación del NER
							</button>
							<button type="button" class="btn btn-default waves-effect botoninforme LMPE" data-toggle="tooltip" title="Importar puntos de determinación del NER" id="boton_importar_puntos_72" onclick="abrirModalPuntos(2)">
								<span class="btn-label"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> Importar
							</button>
							<input type="number" class="form-control w-25 text-center" min="1" placeholder="Agrege el LMPE dB(A)" id="reporteruido_lmpe" name="reporteruido_lmpe">
						</ol>
						<style type="text/css">
							#tabla_reporte_7_2 th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_7_2 td {
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
							}

							#tabla_reporte_7_2 tr:hover td {
								color: #000000;
							}
						</style>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_2">
							<thead>
								<tr>
									<th width="70">No.<br>Medición</th>
									<th width="">Área</th>
									<th width="130">Ubicación</th>
									<th width="130">Identificación</th>
									<th width="60">NER<br>dB(A)</th>
									<th width="60">LMPE<br>dB(A)</th>
									<th width="90">TMPE<br>Horas</th>
									<th width="110">Cumplimiento<br>normativo</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_3">7.3.- Determinación del NER, porcentaje de dosis de la evaluación personal (del o los trabajadores)</h4>
				<div class="row">
					<div class="col-12">
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme LMPE" data-toggle="tooltip" title="Nueva dosis de determinación del NER al personal" id="boton_reporte_nuevadosisner">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Dosis de determinación del NER al personal
							</button>
							<button type="button" class="btn btn-default waves-effect botoninforme LMPE" data-toggle="tooltip" title="Importar dosis de determinación del NER al personal" id="boton_importar_puntos_73" onclick="abrirModalPuntos(3)">
								<span class="btn-label"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span> Importar
							</button>
						</ol>
						<style type="text/css">
							#tabla_reporte_7_3 th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_7_3 td {
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
							}

							#tabla_reporte_7_3 tr:hover td {
								color: #000000;
							}
						</style>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_3">
							<thead>
								<tr>
									<th width="60">No.<br>Medición</th>
									<th width="150">Área</th>
									<th width="">Categoría</th>
									<th width="60">% Dosis</th>
									<th width="60">NER<br>dB(A)</th>
									<th width="60">LMPE<br>dB(A)</th>
									<th width="90">TMPE<br>Horas</th>
									<th width="110">Cumplimiento<br>normativo</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				{{--
				<h4 class="card-title" id="7_4">7.4.- Determinación del factor de reducción (R) del equipo de protección personal auditivo</h4>
					<p class="justificado">Cuando se use un equipo de protección personal auditiva, el factor de reducción R se calcula con la siguiente ecuación:</p><br>
					<div class="imagen_formula">
						<img src="/assets/images/reportes/reporteruido_figura_7.4.jpg" height="60">
					</div><br>
					<p class="justificado">Donde:<br>NRR: Es el factor de nivel de reducción a ruido establecido por el fabricante.</p><br>
					<div id="equiposauditivos_datos"></div>
				<h4 class="card-title" id="7_5">7.5.- Resultados del Nivel de Ruido Efectivo (NRE) por modelo con mediciones de ruido en dB (A)</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">A continuación, se describe la determinación del Factores de Reducción del Equipo de Protección Personal Auditivo para cada punto y el Nivel de Ruido Efectivo (NRE):</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_5">
								<thead>
									<tr>
										<th width="70">No.<br>medición</th>
										<th>Área</th>
										<th>Puesto</th>
										<th width="70">NER<br>dB(A)</th>
										<th width="70">NRE<br>dB(A)</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div> 
				--}}
				<h4 class="card-title" id="7_6">7.4.- Resultados del Nivel de Ruido Efectivo (NRE) con modelo por bandas de octava</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">A continuación, se describe la determinación del Factores de Reducción del Equipo de Protección Personal Auditivo para cada punto y el Nivel de Ruido Efectivo (NRE):</p>
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_6">
							<thead>
								<tr>
									<th width="60">No.<br>medición</th>
									<th width="130">Área</th>
									<th width="">Ubicación</th>
									<th width="">Identificación</th>
									<th width="80">Frecuencia<br>en Hz</th>
									<th width="80">Nivel de<br>Presión<br>Acústica<br>Promedio (dB)</th>
									<th width="60">NER<br>dB(A)</th>
									<th width="60">R<br>dB (A)</th>
									<th width="60">NRE<br>dB (A)</th>
									<th width="60">Editar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7_7">7.5.- Matriz de exposición laboral</h4>
				<div class="row">
					<div class="col-12">
						<style type="text/css">
							#tabla_reporte_7_7 th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.6vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_7_7 td {
								padding: 1px !important;
								font-size: 0.6vw !important;
								text-align: center;
							}

							#tabla_reporte_7_7 tr:hover td {
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
						<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_7">
							{{-- <thead>
									<tr>
										<th width="60">No.<br>medición</th>
										<th>Área</th>
										<th>Ubicación</th>
										<th>Identificación</th>
										<th width="80">Frecuencia<br>en Hz</th>
										<th width="80">Nivel de<br>Presión<br>Acústica<br>Promedio (dB)</th>
										<th width="60">NER<br>dB(A)</th>
										<th width="60">R<br>dB (A)</th>
										<th width="60">NRE<br>dB (A)</th>
										<th width="60">Editar</th>
									</tr>
								</thead>
								<tbody></tbody> --}}
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
									<div class="form-group">
										<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_conclusion" name="reporte_conclusion" required></textarea>
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
							#tabla_dashboard th {
								border: 1px #E9E9E9 solid;
								background: #0C3F64;
								color: #FFFFFF !important;
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
								font-size: 0.7vw !important;
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
									<th colspan="4" style="font-size: 18px!important;">Evaluación de ruido en <span class="div_instalacion_nombre">NOMBRE INSTALACION</span>.</th>
								</tr>
								<tr>
									<th colspan="2">Áreas evaluadas en medición ambiental</th>
									<th width="400">Categorías evaluadas en medición personal</th>
									<td width="250" rowspan="2">
										<div style="border: 0px #F00 solid;">
											<span style="position: absolute; margin-top: 60px; margin-left: 55px; font-size: 18px; font-weight: bold;" id="dashboard_total_evaluacion">
												{{-- 20 puntos<br>Sonometría<br><br>3 puntos<br>Dosimetría --}}
											</span>
											<img src="/assets/images/reportes/dashboard_ruido1.jpg" height="200">
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="height: 200px; text-align: left;">
										<span id="dashboard_areas">
											{{-- * Motobomba y Servicio Auxiliares<br>
												* Aguas Congénitas <br>
												* Turbocompresoras<br> --}}
										</span>
									</td>
									<td style="text-align: left;">
										{{-- <i class="fa fa-users text-info" style="font-size: 60px!important;"></i><br><br> --}}
										<span id="dashboard_categorias">
											{{-- * Bombero “A” Medidor en Batería de Separación<br>
												* Ayudante Especialista Diversos Oficios<br>
												* Operario Especialista Eléctrico<br> --}}
										</span>
									</td>
								</tr>
								<tr>
									<th colspan="2">Puntos de evaluación (medición ambiental)</th>
									<th>Puntos de evaluación (medición personal)</th>
									<td width="250" rowspan="2">
										<span class="texto">Equipo de protección<br>personal auditiva:</span><br><br>
										<span id="dashboard_equipos">
											* Bombero “A” Medidor en Batería de Separación<br>
											* Ayudante Especialista Diversos Oficios<br>
											* Operario Especialista Eléctrico<br>
										</span><br><br>
										<i class="fa fa-pencil-square-o text-info" style="font-size: 60px!important;"></i><br>
										<span class="texto">Recomendaciones<br>emitidas:</span><br>
										<span class="numero" id="dashboard_recomendaciones_total">0</span>
									</td>
								</tr>
								<tr>
									<td style="height: 280px;">
										<i class="fa fa-warning icono" style="color: #8ee66b;"></i>
										<span class="texto">Dentro de norma</span><br>
										<span class="numero" id="dashboard_sonometria_total_dentronorma">0</span>
									</td>
									<td>
										<i class="fa fa-warning icono" style="color: #fc4b6c;"></i>
										<span class="texto">Fuera de norma</span><br>
										<span class="numero" id="dashboard_sonometria_total_fueranorma">0</span>
									</td>
									<td>
										<div id="grafica_resultados" style="height: 200px; width: 200px; border: 0px #000000 solid; margin: 0px auto;"></div>
										<span style="color: #8ee66b;">■</span> Dentro de norma<br>
										<span style="color: #fc4b6c;">■</span> Fuera de norma
									</td>
								</tr>
								<tr>
									<th colspan="4">Análisis derivado del informe de resultados de ruido "Condiciones de seguridad e higiene en los centros de trabajo donde se genere ruido (NOM-011-STPS-2001)".</th>
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
								#tabla_reporte_9 td.alinear_izquierda {
									text-align: left;
								}
							</style>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_9">
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
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Se encontraron <span id="memoriafotografica_total">0</span> fotos de los puntos evaluados que se agregaran al informe de ruido.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_2">11.2.- Anexo 2: Planos de fuentes generadoras y puntos evaluados</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Solo los planos de las carpetas elegidas aparecerán en el informe de ruido.</p><br>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_planos">
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_planos">Guardar carpeta planos <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_3">11.3.- Anexo 3: Memoria de cálculo y gráficas del NS<sub>A</sub> o bien, del NSCE<sub>A, T</sub> y NER según método utilizado</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 3", debe elegirlo en la tabla del punto 12.1 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">La memoria de cálculo y gráficas del NS<sub>A</sub> o bien, del NSCE<sub>A, T</sub> y NER de acuerdo al método empleado en el presente estudio, se encuentra disponible para consulta dentro del informe de resultados (Anexo 7) emitido por el laboratorio aprobado ya que dicho informe no puede ser alterado o modificado en su contenido.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_4">11.4.- Anexo 4: Memoria de cálculo y gráfica del Nivel de Presión Acústica NPA en bandas de octava</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 4", debe elegirlo en la tabla del punto 12.1 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">La memoria de cálculo y gráfica del Nivel de Presión Acústica NPA en bandas de octava, se encuentra disponible para consulta dentro del informe de resultados (Anexo 7) emitido por el laboratorio aprobado ya que dicho informe no puede ser alterado o modificado en su contenido.</p>
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_equipoutilizado">Guardar equipo utilizado <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_6">11.6.- Anexo 6: Copia de certificados o avisos de calibración del equipo</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El certificado del equipo utilizado seleccionado en el punto 11.6 “Anexo 6: Equipo utilizado en la medición” se adjuntará en la impresión del reporte en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_7">11.7.- Anexo 7: Informe de resultados</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 7", debe elegirlo en la tabla del punto 12.1 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_8">11.8.- Anexo 8: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 8", debe elegirlo en la tabla del punto 12.2 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario aprobado para llevar a cabo la evaluación de conformidad con la NOM-011-STPS-2001, condiciones de seguridad e higiene en los centros de trabajo donde se genere ruido, mismo que aparece dentro del registro de aprobación ante la Secretaría del Trabajo y Previsión Social (STPS).</p>
					</div>
				</div>
				<h4 class="card-title" id="11_9">11.9.- Anexo 9: Copia del registro ante la entidad mexicana de acreditación (ema)</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 9", debe elegirlo en la tabla del punto 12.2 El cual se adjuntará en la impresión del informe en formato PDF.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario acreditado en la NOM-011-STPS-2001, condiciones de seguridad e higiene en los centros de trabajo donde se genere ruido, el cual aparece dentro de la acreditación del laboratorio ante la entidad mexicana de acreditación (ema).</p>
					</div>
				</div>
				<h4 class="card-title" id="12_1">12.1.- Seleccionar Anexos 3 (Memo. cal.) y 4 (Memo. cal.) y 7 (Info. resultados)</h4>
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
				<h4 class="card-title" id="12_2">12.2.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</h4>
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
						<div style="padding: 8px;margin: 10px 0px;display: flex;justify-content: space-between;background: #0098C7;border-radius: 10px;">
							<div>
								<button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" title="Nueva revisión" id="boton_reporte_nuevarevision">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Crear nueva revisión
								</button>

							</div>
							<div>
								<button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" title="Generar Programa de Conservación de la Audición" id="boton_reporte_pca">
									<span class="btn-label"><i class="fa fa-file-excel-o"></i></span> Generar PCA
								</button>
							</div>
						</div>
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
								<input type="text" class="form-control" id="reporteruidocategoria_nombre" name="reporteruidocategoria_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" class="form-control" id="reporteruidocategoria_total" name="reporteruidocategoria_total" required>
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
						<div class="col-4">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporteruidoarea_instalacion" name="reporteruidoarea_instalacion" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reporteruidoarea_nombre" name="reporteruidoarea_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" class="form-control" id="reporteruidoarea_numorden" name="reporteruidoarea_numorden" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>% de operacion*</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reporteruidoarea_porcientooperacion" name="reporteruidoarea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Proceso*</label>
								<input type="text" class="form-control" id="reporteruidoarea_proceso" name="reporteruidoarea_proceso" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Tipo de ruido*</label>
								<!-- <input type="text" class="form-control" id="reporteruidoarea_tiporuido" name="reporteruidoarea_tiporuido" required> -->
								<select class="custom-select form-control" id="reporteruidoarea_tiporuido" name="reporteruidoarea_tiporuido" required>
									<option value=""></option>
									<option value="NA">No aplica</option>
									<option value="Inestable">Inestable</option>
									<option value="Impulsivo">Impulsivo</option>
									<option value="Estable">Estable</option>
									<option value="Estable/Inestable">Estable / Inestable</option>
									<option value="Estable/Impulsivo">Estable / Impulsivo</option>
									<option value="Inestable/Estable">Inestable / Estable</option>
									<option value="Inestable/Impulsivo">Inestable / Impulsivo</option>
									<option value="Impulsivo/Estable">Impulsivo / Estable</option>
									<option value="Impulsivo/Inestable">Impulsivo / Inestable</option>

								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Evaluación*</label>
								<!-- <input type="text" class="form-control" id="reporteruidoarea_evaluacion" name="reporteruidoarea_evaluacion" required>-->
								<select class="custom-select form-control" id="reporteruidoarea_evaluacion" name="reporteruidoarea_evaluacion" required>
									<option value=""></option>
									<option value="NA">No aplica</option>
									<option value="GPS">GPS</option>
									<option value="PAE">PAE</option>
									<option value="PFT">PFT</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Lecturas del nivel instantáneo NS<sub>A</sub> en dB<sub>A</sub> (Mínimo)</label>
								<input type="text" class="form-control" id="reporteruidoarea_LNI_1" name="reporteruidoarea_LNI_1" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Lecturas del nivel instantáneo NS<sub>A</sub> en dB<sub>A</sub> (Máximo)</label>
								<input type="text" class="form-control" id="reporteruidoarea_LNI_2" name="reporteruidoarea_LNI_2" required>
							</div>
						</div>
					</div>
					{{-- <div class="row">
						<div class="col-12">
							<table class="table table-hover stylish-table tabla_info_centrado" width="100%">
								<thead>
									<tr>
										<th colspan="10">Lecturas del nivel instantáneo NS<sub>A</sub> en dB<sub>A</sub></th>
									</tr>
									<tr>
										<th width="10%">1</th>
										<th width="10%">2</th>
										<th width="10%">3</th>
										<th width="10%">4</th>
										<th width="10%">5</th>
										<th width="10%">6</th>
										<th width="10%">7</th>
										<th width="10%">8</th>
										<th width="10%">9</th>
										<th width="10%">10</th>
									</tr>
									<tr>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_1" name="reporteruidoarea_LNI_1" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_2" name="reporteruidoarea_LNI_2" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_3" name="reporteruidoarea_LNI_3" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_4" name="reporteruidoarea_LNI_4" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_5" name="reporteruidoarea_LNI_5" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_6" name="reporteruidoarea_LNI_6" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_7" name="reporteruidoarea_LNI_7" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_8" name="reporteruidoarea_LNI_8" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_9" name="reporteruidoarea_LNI_9" required></td>
										<td><input type="number" step="any" class="form-control" id="reporteruidoarea_LNI_10" name="reporteruidoarea_LNI_10" required></td>
									</tr>
								</thead>
							</table>
						</div>
					</div> --}}


					<div class="col-12 p-2 text-center">
						<label class="text-danger mr-4 d-block" style="font-size: 18px;" data-toggle="tooltip" title="" data-original-title="Marque la casilla de NO si el área no fue evaluada en el reconocimiento">¿ Área evaluada en el reconocimiento ?</label>
						<div class="d-flex justify-content-center">
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="aplica_ruido" id="aplica_ruido_si" value="1" required="required" checked>
								<label class="form-check-label" for="aplica_ruido_si">
									Si
								</label>
							</div>
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="aplica_ruido" id="aplica_ruido_no" value="0" required="required">
								<label class="form-check-label" for="aplica_ruido_no">
									No
								</label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-6">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Categorías en el área</ol>
							<div style="margin: -25px 0px 0px 0px!important; padding: 0px!important;">
								<style type="text/css">
									#tabla_areacategorias td {
										text-align: left;
										color: #777777;
										font-weight: bold;
									}
								</style>
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areacategorias">
									<thead>
										<tr>
											<th style="padding: 3px 0px!important;">Activo</th>
											<th style="padding: 3px 0px!important;">Categoría / Actividades</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="col-6">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 6px 0px; text-align: center;">Fuentes generadoras</ol>
							<button type="button" class="btn btn-default waves-effect waves-light" style="height: 26px; padding: 3px 8px;" data-toggle="tooltip" title="Agregar fuente generadora" id="botonnueva_areamaquina">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Fuente generadora
							</button>
							<div style="margin: 6px 0px 0px 0px!important; padding: 0px!important; max-height: 232px; overflow-y: auto; overflow-x: hidden;" id="div_tabla_areamaquinaria">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areamaquinaria">
									<thead>
										<tr>
											<th width="" style="padding: 3px 0px!important;">Fuente generadora</th>
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
<!-- MODAL-REPORTE-EQUIPO AUDITIVO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_equipoauditivo>.modal-dialog {
		min-width: 1100px !important;
	}

	#modal_reporte_equipoauditivo .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_equipoauditivo .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_equipoauditivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_reporte_equipoauditivo" id="form_reporte_equipoauditivo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Equipo auditivo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteequipoauditivo_id" name="reporteequipoauditivo_id" value="0">
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
									<label>Seleccione un equipo auditivo o rellene los siguientes datos manualmente</label>
									<select class="custom-select form-control" id="select_proteccionAuditiva" onchange="mostrar_proteccionauditiva(this.value);">
                                        <option value="">Seleccione</option>
                                    </select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Tipo</label>
										<input type="text" class="form-control" id="reporteruidoequipoauditivo_tipo" name="reporteruidoequipoauditivo_tipo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Marca</label>
										<input type="text" class="form-control" id="reporteruidoequipoauditivo_marca" name="reporteruidoequipoauditivo_marca" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Modelo</label>
										<input type="text" class="form-control" id="reporteruidoequipoauditivo_modelo" name="reporteruidoequipoauditivo_modelo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>NRR (dB)</label>
										<input type="number" step="any" class="form-control" id="reporteruidoequipoauditivo_NRR" name="reporteruidoequipoauditivo_NRR" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<ol class="breadcrumb" style="padding: 2px; margin: 0px 0px 6px 0px;">
										<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva atenuación" id="boton_equipoauditivo_nuevaatenuacion">
											<span class="btn-label"><i class="fa fa-plus"></i></span>Atenuación
										</button>
									</ol>
									<div style="width: 100%; height: 250px; max-height: 250px; overflow-y: auto; overflow-x: hidden; border: 0px #F00 solid;" id="div_tabla_equipoauditivo_atenuaciones">
										<table class="table table-hover stylish-table tabla_info_centrado" style="margin-bottom: 0px;" width="100%">
											<thead>
												<tr>
													<th colspan="3">Atenuación por bandas de octava</th>
												</tr>
												<tr>
													<th width="40%">Hz</th>
													<th width="40%">Atenuación</th>
													<th width="10%">Elimina</th>
												</tr>
											</thead>
										</table>
										<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_equipoauditivo_atenuaciones">
											<tbody>
												<tr>
													<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivo_bandaNRR[]" required></td>
													<td width="40%"><input type="number" step="any" class="form-control" name="reporteruidoequipoauditivo_bandaatenuacion[]" required></td>
													<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 2px; margin: 0px 0px 6px 0px; text-align: center;">Categorías que utilizan este equipo auditivo</ol>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-body" style="margin: 0px!important; padding: 6px 10px!important; max-height: 120px; overflow-x: hidden; overflow-y: auto;">
									<div class="row" id="reporteequipoauditivo_categoriaslista"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_equipoauditivo">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-EQUIPO AUDITIVO -->
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
                                <table class="table table-hover stylish-table" width="100%" id="tabla_lista_epp_ruido">
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
<!-- MODAL-REPORTE-AREA EVALUACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_areaevaluacion>.modal-dialog {
		min-width: 800px !important;
	}

	#modal_reporte_areaevaluacion .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_areaevaluacion .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_areaevaluacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_areaevaluacion" id="form_modal_areaevaluacion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Titulo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							{{-- <input type="hidden" class="form-control" id="reporteareaevaluacion_id" name="reporteareaevaluacion_id" value="0"> --}}
						</div>
						<div class="col-8">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reporteruidoarea_id" name="reporteruidoarea_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>No. evaluaciones por área</label>
								<input type="number" class="form-control" id="reporteruidoareaevaluacion_noevaluaciones" name="reporteruidoareaevaluacion_noevaluaciones" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 2px; margin: 0px 0px 6px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva Ubicación" id="boton_areaevaluacion_nuevaubicacion">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Ubicación
								</button>
							</ol>
							<div style="width: 100%; height: 250px; max-height: 250px; overflow-y: auto; overflow-x: hidden; border: 0px #F00 solid;" id="div_tabla_areaevaluacion_ubicaciones">
								<table class="table table-hover stylish-table tabla_info_centrado" style="margin-bottom: 0px;" width="100%">
									<thead>
										<tr>
											<th width="30%">No. de mediciónes</th>
											<th width="60%">Ubicación</th>
											<th width="10%">Elimina</th>
										</tr>
									</thead>
								</table>
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areaevaluacion_ubicaciones">
									<tbody>
										<tr>
											<td width="30%" style="vertical-align: middle!important;">
												<input type="number" class="form-control" style="width: 90px; float: left;" name="reporteruidoareaevaluacion_nomedicion1[]" required>
												AL
												<input type="number" class="form-control" style="width: 90px; float: right;" name="reporteruidoareaevaluacion_nomedicion2[]" required>
											</td>
											<td width="60%"><input type="text" class="form-control" name="reporteruidoareaevaluacion_ubicacion[]" required></td>
											<td width="10%"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_areaevaluacion">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-AREA EVALUACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADOS NIVEL SONORO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_nivelsonoro>.modal-dialog {
		min-width: 800px !important;
	}

	#modal_reporte_nivelsonoro .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_nivelsonoro .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_nivelsonoro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_nivelsonoro" id="form_modal_nivelsonoro">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Punto de medición de nivel sonoro continuo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reportenivelsonoro_punto" name="reportenivelsonoro_punto" value="0">
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>No. de Medición</label>
								<input type="number" class="form-control" min="1" id="reporteruidonivelsonoro_punto" name="reporteruidonivelsonoro_punto" required>
								<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Ubicación</label>
								{{-- <input type="text" class="form-control" id="reporteruidonivelsonoro_ubicacion" name="reporteruidonivelsonoro_ubicacion" required> --}}
								<select class="custom-select form-control" id="reporteruidonivelsonoro_ubicacion" name="reporteruidonivelsonoro_ubicacion" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>NSCE<sub>A, Ti</sub> Promedio</label>
								<input type="number" step="any" min="1" class="form-control" id="reporteruidonivelsonoro_promedio" name="reporteruidonivelsonoro_promedio" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 2px; margin: 0px 0px 6px 0px; text-align: center;">Resultados del nivel sonoro continuo</ol>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Total periodos</label>
								<input type="number" class="form-control" min="1" max="5" id="reporteruidonivelsonoro_totalperiodos" name="reporteruidonivelsonoro_totalperiodos" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Total resultados</label>
								<input type="number" class="form-control" min="1" max="100" id="reporteruidonivelsonoro_totalresultados" name="reporteruidonivelsonoro_totalresultados" required>
							</div>
						</div>
						<div class="col-3">
							<br>
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar lista de campos" id="boton_totalnivelsonoro">
								<span class="btn-label"><i class="fa fa-list"></i></span>Agregar campos
							</button>
						</div>
						<div class="col-3"></div>
						<div class="col-12">
							<div style="width: 100%; height: 250px; max-height: 250px; overflow-y: auto; overflow-x: hidden; border: 0px #F00 solid;" id="div_tabla_nivelsonoro">
								<table class="table table-hover stylish-table tabla_info_centrado" style="margin-bottom: 0px;" width="100%">
									<thead>
										<tr>
											<th width="60">No.</th>
											<th>Periodo 1</th>
											<th>Periodo 2</th>
											<th>Periodo 3</th>
											<th>Periodo 4</th>
											<th>Periodo 5</th>
										</tr>
									</thead>
								</table>
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_nivelsonoro">
									<tbody>
										<tr>
											<td width="60">1</td>
											<td><input type="number" step="any" min="1" max="100" class="form-control" name="reporteruidonivelsonoro_periodo1[]" required></td>
											<td><input type="number" step="any" min="1" max="100" class="form-control" name="reporteruidonivelsonoro_periodo2[]" required></td>
											<td><input type="number" step="any" min="1" max="100" class="form-control" name="reporteruidonivelsonoro_periodo3[]" required></td>
											<td><input type="number" step="any" min="1" max="100" class="form-control" name="reporteruidonivelsonoro_periodo4[]" required></td>
											<td><input type="number" step="any" min="1" max="100" class="form-control" name="reporteruidonivelsonoro_periodo5[]" required></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_nivelsonoro">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADOS NIVEL SONORO -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO PUNTO NER -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_puntoner>.modal-dialog {
		min-width: 1000px !important;
	}

	#modal_reporte_puntoner .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_puntoner .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_puntoner" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_puntoner" id="form_modal_puntoner">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Punto de medición NER</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="puntoner_id" name="puntoner_id" value="0">
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>No. de medición</label>
								<input type="number" class="form-control" min="1" id="reporteruidopuntoner_punto" name="reporteruidopuntoner_punto" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>TMPE horas</label>
								<input type="text" class="form-control" id="reporteruidopuntoner_tmpe" name="reporteruidopuntoner_tmpe" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>NER dB(A)</label>
								<input type="number" step="any" class="form-control" id="reporteruidopuntoner_ner" name="reporteruidopuntoner_ner" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>LMPE dB(A)</label>
								<input type="number" step="any" class="form-control" id="reporteruidopuntoner_lmpe" name="reporteruidopuntoner_lmpe" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reporteruidopuntoner_areaid" name="reporteruidoarea_id" onchange="mostrar_categoriasarea_puntoner(this.value, form_modal_puntoner.puntoner_id.value);" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Ubicación</label>
								{{-- <input type="text" class="form-control" id="reporteruidopuntoner_ubicacion" name="reporteruidopuntoner_ubicacion" required> --}}
								<select class="custom-select form-control" id="reporteruidopuntoner_ubicacion" name="reporteruidopuntoner_ubicacion" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Identificación</label>
								<input type="text" class="form-control" id="reporteruidopuntoner_identificacion" name="reporteruidopuntoner_identificacion" required>
							</div>
						</div>
					</div>
					<!-- NO SE HACE USO DE LAS CATEGORIAS PARA ESTE CAMPO -->

					<!-- <div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Agregar categoría evaluada" id="boton_puntoner_nuevacategoria">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Categoría evaluada
								</button>
							</ol>
							<table class="table table-hover stylish-table tabla_info_centrado" style="margin-bottom: 0px;" width="100%">
								<thead>
									<tr>
										<th width="320">Categoría</th>
										<th width="100">Total</th>
										<th width="100">GEO</th>
										<th width="120">Ficha</th>
										<th>Nombre</th>
										<th width="61">Eliminar</th>
									</tr>
								</thead>
							</table>
							<div style="max-height: 225px; overflow-x: none; overflow-y: auto; border: 1px #DDDDDD solid;" id="divtabla_puntoner_areacategorias">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_puntoner_areacategorias">
									<tbody>
										{{-- <tr>
											<td width="319"><input type="text" class="form-control"></td>
											<td width="100"><input type="number" class="form-control"></td>
											<td width="100"><input type="number" class="form-control"></td>
											<td width="120"><input type="text" class="form-control"></td>
											<td><input type="text" class="form-control"></td>
											<td width="60"><button type="button" class="btn btn-default waves-effect btn-circle" data-toggle="tooltip" title="No disponible"><i class="fa fa-ban fa-2x"></i></button></td>
										</tr>
										<tr>
											<td width="319"><input type="text" class="form-control"></td>
											<td width="100"><input type="number" class="form-control"></td>
											<td width="100"><input type="number" class="form-control"></td>
											<td width="120"><input type="text" class="form-control"></td>
											<td><input type="text" class="form-control"></td>
											<td width="60"><button type="button" class="btn btn-danger waves-effect btn-circle eliminar"><i class="fa fa-trash fa-2x"></i></button></td>
										</tr> --}}
									</tbody>
								</table>
							</div>
						</div>
					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_puntoner">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO PUNTO NER -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO DOSIS NER -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_dosisner>.modal-dialog {
		min-width: 1000px !important;
	}

	#modal_reporte_dosisner .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_dosisner .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_dosisner" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_dosisner" id="form_modal_dosisner">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Dosis de determinación NER al personal</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="dosisner_id" name="dosisner_id" value="0">
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. de medición *</label>
								<input type="number" class="form-control" min="1" id="reporteruidodosisner_punto" name="reporteruidodosisner_punto" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>% dosis *</label>
								<input type="number" step="any" class="form-control" id="reporteruidodosisner_dosis" name="reporteruidodosisner_dosis" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>NER dB(A) *</label>
								<input type="number" step="any" class="form-control" id="reporteruidodosisner_ner" name="reporteruidodosisner_ner" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>LMPE dB(A) *</label>
								<input type="number" step="any" class="form-control" id="reporteruidodosisner_lmpe" name="reporteruidodosisner_lmpe" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>TMPE horas *</label>
								<input type="text" class="form-control" id="reporteruidodosisner_tmpe" name="reporteruidodosisner_tmpe" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Área *</label>
								<select class="custom-select form-control" id="reporteruidodosisner_areaid" name="reporteruidoarea_id" onchange="mostrar_categoriasarea(this.value, 0, 'reporteruidodosisner_categoriaid');" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Categoría *</label>
								<select class="custom-select form-control" id="reporteruidodosisner_categoriaid" name="reporteruidocategoria_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Nombre *</label>
								<input type="text" class="form-control" id="reporteruidodosisner_nombre" name="reporteruidodosisner_nombre" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Equipo utilizado *</label>
								<select class="custom-select form-control" id="reporteruidodosisner_equipo" name="reporteruidodosisner_equipo" required>
									<option value=""></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_dosisner">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO DOSIS NER -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-BANDAS DE OCTAVA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_bandasoctava>.modal-dialog {
		min-width: 800px !important;
	}

	#modal_reporte_bandasoctava .modal-body .form-group {
		margin: 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_bandasoctava .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}

	#modal_reporte_bandasoctava .modal-body .form-group .form-control {
		margin: 0px 0px 14px 0px !important;
	}

	#tabla_bandasoctava_frecuencias td input.form-control {
		padding: 3px !important;
		min-height: 22px !important;
		font-size: 14px !important;
		line-height: 12px !important;
	}
</style>
<div id="modal_reporte_bandasoctava" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_bandasoctava" id="form_modal_bandasoctava">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Dosis de determinación NER al personal</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-7">
							<div class="row">
								<div class="col-12">
									{!! csrf_field() !!}
									<input type="hidden" class="form-control" id="reporteruidopuntoner_id" name="reporteruidopuntoner_id" value="0">
								</div>
								<div class="col-4">
									<div class="form-group">
										<label>No. de medición</label>
										<input type="number" class="form-control" id="reporteruidobandaoctava_punto" readonly>
									</div>
								</div>
								<div class="col-8">
									<div class="form-group">
										<label>Área</label>
										<input type="text" class="form-control" id="reporteruidobandaoctava_area" readonly>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Ubicación</label>
										<input type="text" class="form-control" id="reporteruidobandaoctava_ubicacion" readonly>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Identificación</label>
										<input type="text" class="form-control" id="reporteruidobandaoctava_identificacion" readonly>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Equipo auditivo</label>
										<select class="custom-select form-control" id="reporteruidobandaoctava_equipo" name="reporteruidobandaoctava_equipo" required>
											<option value=""></option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>NER dB(A)</label>
										<input type="number" class="form-control" id="reporteruidobandaoctava_ner" readonly>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>R dB (A)</label>
										<input type="number" step="any" class="form-control" id="reporteruidobandaoctava_RdB" name="reporteruidobandaoctava_RdB" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>NRE dB (A) </label>
										<input type="number" step="any" class="form-control" id="reporteruidobandaoctava_NRE" name="reporteruidobandaoctava_NRE" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-5">
							<table class="table table-hover tabla_info_centrado" style="margin-bottom: 0px;" width="100%" id="tabla_bandasoctava_frecuencias">
								<thead>
									<tr>
										<th width="40%">Frecuencia<br>en Hz</th>
										<th width="60%">Nivel de Presión Acústica<br>Promedio (dB)</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_bandasoctava">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-BANDAS DE OCTAVA -->
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


<!-- ============================================================== -->
<!-- MODAL CARGA DE RESULTADOS POR EXCEL-->
<!-- ============================================================== -->
<div id="modal_excel_puntos" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post" name="formExcelPuntos" id="formExcelPuntos">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Importar resultador por medio de Excel</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" name="tipoArchivo" id="tipoArchivo" value="0">
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
					<div class="row mx-2" id="alertaVerificacion" style="display:none">
						<p class="text-danger"><i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, asegúrese de que el nombre de las Áreas y/o categorías a insertar corresponda con las que están cargadas en el Software. </p>
					</div>
					<div class="row mt-3" id="divCargarPuntos" style="display: none;">

						<div class="col-12 text-center">
							<h2>Cargando datos del EXCEL espere un momento...</h2>
						</div>
						<div class="col-12 text-center">
							<i class='fa fa-spin fa-spinner fa-5x'></i>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="botonCargarPuntos">
						Importar datos <i class="fa fa-upload" aria-hidden="true"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL PUNTO 7.1 CARGA DE PUNTOS POR EXCEL -->
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
{{-- <script src="/js_sitio/html2canvas.js"></script> --}}
<script src="/js_sitio/reportes/reporteruido.js?v=5.0"></script>