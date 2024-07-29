<style type="text/css">
	.reporte_iluminacion {
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

<div class="row" class="reporte_iluminacion">
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
				<a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación de los niveles de iluminación <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#4_2_1" class="list-group-item subsubmenu">4.2.1.- Cálculo del índice de áreas <i class="fa fa-times" id="menureporte_4_2_1"></i></a>
				<a href="#4_2_2" class="list-group-item subsubmenu">4.2.2.- Evaluación del factor de reflexión <i class="fa fa-times" id="menureporte_4_2_2"></i></a>
				<a href="#4_2_4" class="list-group-item subsubmenu">4.2.3.- Niveles de iluminación <i class="fa fa-times" id="menureporte_4_2_4"></i></a>
				<a href="#4_2_3" class="list-group-item subsubmenu">4.2.4.- Niveles máximos permisibles del factor de reflexión <i class="fa fa-times" id="menureporte_4_2_3"></i></a>
				<a href="#5" class="list-group-item">5.- Reconocimiento</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
				<a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Descripción del área iluminada y su sistema de iluminación <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#6_2" class="list-group-item submenu">6.2.- Método empleado y criterio de selección <i class="fa fa-times" id="menureporte_6_2"></i></a>
				<a href="#6_2_1" class="list-group-item subsubmenu">6.2.1.- Índice de área <i class="fa fa-times" id="menureporte_6_2_1"></i></a>
				<a href="#6_2_2" class="list-group-item subsubmenu">6.2.2.- Puesto de trabajo <i class="fa fa-times" id="menureporte_6_2_2"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados</a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Resultados del nivel de iluminación <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Resultados del nivel de reflexión <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#7_3" class="list-group-item submenu">7.3.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_3"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de luminarias y puntos de evaluación por área <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Incertidumbre de la medición <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Copia de certificados o aviso de calibración del equipo <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Informe de resultados <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_8"></i></a>
				<a href="#12" class="list-group-item submenu">12.- Elegir anexos 7 (STPS) y 8 (EMA) <i class="fa fa-times" id="menureporte_12"></i></a>
				<a href="#13" class="list-group-item submenu">Generar informe <i class="fa fa-download text-success" id="menureporte_13"></i></a>
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
									<label><input type="checkbox" id="reporteiluminacion_catsubdireccion_activo" name="reporteiluminacion_catsubdireccion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Subdirección</label>
								<select class="custom-select form-control" id="reporteiluminacion_catsubdireccion_id" name="reporteiluminacion_catsubdireccion_id" disabled>
									<option value=""></option>
									@foreach($catsubdireccion as $subdireccion)
									{{-- <option value="{{$subdireccion->id}}">{{$subdireccion->catsubdireccion_siglas}} - {{$subdireccion->catsubdireccion_nombre}}</option> --}}
									<option value="{{$subdireccion->id}}">{{$subdireccion->catsubdireccion_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporteiluminacion_catgerencia_activo" name="reporteiluminacion_catgerencia_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Gerencia</label>
								<select class="custom-select form-control" id="reporteiluminacion_catgerencia_id" name="reporteiluminacion_catgerencia_id" disabled>
									<option value=""></option>
									@foreach($catgerencia as $gerencia)
									{{-- <option value="{{$gerencia->id}}">{{$gerencia->catgerencia_siglas}} - {{$gerencia->catgerencia_nombre}}</option> --}}
									<option value="{{$gerencia->id}}">{{$gerencia->catgerencia_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporteiluminacion_catactivo_activo" name="reporteiluminacion_catactivo_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-11 d-none">
							<div class="form-group">
								<label>Activo</label>
								<select class="custom-select form-control" id="reporteiluminacion_catactivo_id" name="reporteiluminacion_catactivo_id" disabled>
									<option value=""></option>
									@foreach($catactivo as $activo)
									{{-- <option value="{{$activo->id}}">{{$activo->catactivo_siglas}} - {{$activo->catactivo_nombre}}</option> --}}
									<option value="{{$activo->id}}">{{$activo->catactivo_nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12 d-none">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporteiluminacion_instalacion" name="reporteiluminacion_instalacion" onchange="instalacion_nombre(this.value);" readonly>
							</div>
						</div>
						<div class="col-4 d-none"></div>
						<div class="col-1 d-none">
							<div class="form-group">
								<label class="demo-switch-title">Mostrar</label>
								<div class="switch" style="margin-top: 6px;">
									<label><input type="checkbox" id="reporteiluminacion_catregion_activo" name="reporteiluminacion_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
								</div>
							</div>
						</div>
						<div class="col-3 d-none">
							<div class="form-group">
								<label>Región</label>
								<select class="custom-select form-control" id="reporteiluminacion_catregion_id" name="reporteiluminacion_catregion_id" disabled>
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
										<select class="custom-select form-control" id="reporteiluminacion_mes" name="reporteiluminacion_mes">
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
										<select class="custom-select form-control" id="reporteiluminacion_fecha" name="reporteiluminacion_fecha">
											<option value="" selected disabled></option>
											<script>
												$(document).ready(function() {
													const currentYear = new Date().getFullYear();
													for (let year = currentYear; year >= 2017; year--) {
														$('#reporteiluminacion_fecha').append(new Option(year, year));
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporteiluminacion_introduccion" name="reporteiluminacion_introduccion" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_objetivogeneral" name="reporteiluminacion_objetivogeneral" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporteiluminacion_objetivoespecifico" name="reporteiluminacion_objetivoespecifico" required></textarea>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_1" name="reporteiluminacion_metodologia_4_1" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2">4.2.- Método de evaluación de los niveles de iluminación</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2" id="form_reporte_metodologia_4_2">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporteiluminacion_metodologia_4_2" name="reporteiluminacion_metodologia_4_2" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2_1">4.2.1.- Cálculo del índice de áreas</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_1" id="form_reporte_metodologia_4_2_1">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_1" name="reporteiluminacion_metodologia_4_2_1" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<div class="imagen_formula"><img src="/assets/images/reportes/reporteiluminacion_figura_4.2.1.jpg" height="60"></div>
								<br><b>Ecuación 1.</b> Índice de área. Tomada de la NOM-025-STPS-2008.<br><br>
								Dónde:<br><br>
								<b>IC</b> = índice de área.<br>
								<b>x, y</b> = dimensiones del área (largo y ancho), en metros.<br>
								<b>h</b> = altura de la luminaria respecto al plano de trabajo, en metros.
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_1">Guardar metodología punto 4.2.1 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2_2">4.2.2.- Evaluación del factor de reflexión</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_2" id="form_reporte_metodologia_4_2_2">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_2" name="reporteiluminacion_metodologia_4_2_2" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<div class="imagen_formula"><img src="/assets/images/reportes/reporteiluminacion_figura_4.2.2.jpg" height="60"></div>
								<br><b>Ecuación 2.</b> Factor de reflexión. Tomada de la NOM-025-STPS-2008.<br><br>
								Dónde:<br><br>
								<b>Kƒ:</b> Factor de reflexión.<br>
								<b>E1:</b> Nivel de Iluminación reflejada.<br>
								<b>E2:</b> Nivel de iluminación incidente.
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_2">Guardar metodología punto 4.2.2 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2_4">4.2.3.- Niveles de iluminación</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_4" id="form_reporte_metodologia_4_2_4">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_4" name="reporteiluminacion_metodologia_4_2_4" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<div class="imagen_formula">Tabla 1<br>Niveles de Iluminación</div><br>
								<table class="table tabla_info_justificado" width="100%">
									<thead>
										<tr>
											<th width="45%">Tarea visual del puesto de trabajo</th>
											<th width="45%">Área de trabajo</th>
											<th width="10%">Niveles Mínimos de Iluminación (luxes)</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>En exteriores: distinguir el área de tránsito, desplazarse caminando, vigilancia, movimiento de vehículos.</td>
											<td>Exteriores generales: patios y estacionamientos.</td>
											<td style="text-align: center;">20</td>
										</tr>
										<tr>
											<td>En interiores: distinguir el área de tránsito, desplazarse caminando. Vigilancia, movimiento de vehículos.</td>
											<td>Interiores generales: almacenes de poco movimiento, pasillos, escaleras, estacionamientos cubiertos, labores en minas subterráneas, iluminación de emergencia.</td>
											<td style="text-align: center;">50</td>
										</tr>
										<tr>
											<td>En interiores.</td>
											<td>Áreas de circulación y pasillos; salas de espera; salas de descanso; cuartos de almacén; plataformas; cuartos de calderas.</td>
											<td style="text-align: center;">100</td>
										</tr>
										<tr>
											<td>Requerimiento visual simple: inspección visual, recuento de piezas, trabajo en banco y máquina.</td>
											<td>Servicios al personal: almacenaje rudo, recepción y despacho, casetas de vigilancia, cuartos de compresores y pailera.</td>
											<td style="text-align: center;">200</td>
										</tr>
										<tr>
											<td>Distinción moderada de detalles: ensamble simple, trabajo medio en banco y máquina, inspección simple, empaque y trabajos de oficina.</td>
											<td>Talleres: áreas de empaque y ensamble, aulas y oficinas.</td>
											<td style="text-align: center;">300</td>
										</tr>
										<tr>
											<td>Distinción clara de detalles: maquinado y acabados delicados, ensamble de inspección moderadamente difícil, captura y procesamiento de información, manejo de instrumentos y equipo de laboratorio.</td>
											<td>Talleres de precisión: salas de cómputo, áreas de dibujo, laboratorios.</td>
											<td style="text-align: center;">500</td>
										</tr>
										<tr>
											<td>Distinción fina de detalles: maquinado de precisión, ensamble e inspección de trabajos delicados, manejo de instrumentos y equipo de precisión, manejo de piezas pequeñas.</td>
											<td>Talleres de alta precisión: de pintura y acabado de superficies y laboratorios de control de calidad.</td>
											<td style="text-align: center;">750</td>
										</tr>
										<tr>
											<td>Alta exactitud en la distinción de detalles: ensamble, proceso e inspección de piezas pequeñas y complejas, acabado con pulidos finos.</td>
											<td>Proceso: ensamble e inspección de piezas complejas y acabados con pulidos finos.</td>
											<td style="text-align: center;">1000</td>
										</tr>
										<tr>
											<td>Alto grado de especialización en la distinción de detalles.</td>
											<td>Proceso de gran exactitud. Ejecución de tareas visuales: de bajo contraste y tamaño muy pequeño por periodos prolongados; exactas y muy prolongadas; y muy extremadamente bajo contraste y bajo pequeño tamaño.</td>
											<td style="text-align: center;">2000</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_4">Guardar metodología punto 4.2.3 <i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="4_2_3">4.2.4.- Niveles máximos permisibles del factor de reflexión</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2_3" id="form_reporte_metodologia_4_2_3">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								{!! csrf_field() !!}
								<textarea class="form-control" style="margin-bottom: 0px;" rows="8" id="reporteiluminacion_metodologia_4_2_3" name="reporteiluminacion_metodologia_4_2_3" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="informacion_estatica">
								<div class="imagen_formula">Tabla 2<br>Niveles Máximos Permisibles del Factor de Reflexión</div><br>
								<table class="table tabla_info_centrado" width="100%">
									<thead>
										<tr>
											<th width="40%">Concepto</th>
											<th width="50%">Niveles máximos permisibles de reflexión K<sub>f</sub></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Paredes</td>
											<td>60 %</td>
										</tr>
										<tr>
											<td>Plano de trabajo</td>
											<td>50 %</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2_3">Guardar metodología punto 4.2.4 <i class="fa fa-save"></i></button>
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
									<textarea class="form-control" style="margin-bottom: 0px;" rows="14" id="reporteiluminacion_ubicacioninstalacion" name="reporteiluminacion_ubicacioninstalacion" required></textarea>
								</div>
							</div>
						</div>
						<div class="col-6">
							<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: block;" data-toggle="tooltip" title="Descargar mapa ubicación" id="boton_descargarmapaubicacion"></i>
							<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteiluminacionubicacionfoto" name="reporteiluminacionubicacionfoto" onchange="redimencionar_mapaubicacion();" required>
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
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporteiluminacion_procesoinstalacion" name="reporteiluminacion_procesoinstalacion" required></textarea>
							</div>
							<div class="form-group">
								<label>Descripción de la actividad principal de la instalación</label>
								<textarea class="form-control" style="margin-bottom: 0px;" rows="7" id="reporteiluminacion_actividadprincipal" name="reporteiluminacion_actividadprincipal" required></textarea>
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
						<p class="justificado">En este apartado se muestra la actividad principal desarrollada en cada una de las áreas, involucrando al personal/categoría adscrito que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>:</p>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva categoría" id="boton_reporte_nuevacategoria">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva categoría
							</button>
						</ol>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_categoria">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th>Categoría</th>
									<th width="80">Total</th>
									<th width="100">Jornada</th>
									<th width="60">Editar</th>
									<th width="60">Eliminar</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table><br><br>
						<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
							<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área" id="boton_reporte_nuevaarea">
								<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva área
							</button>
						</ol>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_area">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="130">Área</th>
									<th width="">Categoría</th>
									<th width="60">Total</th>
									<th width="90">Pts eval. IC</th>
									<th width="90">Pts eval. PT</th>
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
						<p class="justificado">En este apartado se muestran las actividades desarrolladas por cada categoría durante su jornada laboral en su respectiva área de trabajo, así como una breve visión de las condiciones respecto a la existencia o no de la influencia de luz natural e iluminación localizada.</p>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_4">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="150">Área</th>
									<th>Categoría</th>
									<th width="200">Descripción de las actividades<br>que desarrolla</th>
									<th width="120">luz natural</th>
									<th width="120">Ilumi. localizada</th>
									<th width="80">Jornada</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="5_5">5.5.- Descripción del área iluminada y su sistema de iluminación</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Para esta sección se han registrado las áreas, índice de iluminación y número de zonas a evaluar, considerando la influencia de luz natural en el sitio y el sistema de iluminación con el que se cuenta en la instalación.</p>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_5">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="">Área</th>
									<th width="80">Color de<br>superficie</th>
									<th width="80">Tipo de<br>superficie</th>
									<th width="80">luz<br>natural</th>
									<th width="200">Sistema de iluminación</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
						<div class="informacion_estatica">
							<br><b>N/P:</b> No Proporcionado<br>
						</div>
					</div>
				</div>
				<h4 class="card-title" id="6">6.- Evaluación</h4>
				<h4 class="card-title" id="6_1">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación se presentan por porcentaje en la siguiente tabla:</p>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_1">
							<thead>
								<tr>
									<th width="60">No.</th>
									<th width="130">Instalación</th>
									<th width="">Área</th>
									<th width="180">Porcentaje de operación</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="6_2">6.2.- Método empleado y criterio de selección</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_criterioseleccion" id="form_reporte_criterioseleccion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="12" id="reporteiluminacion_criterioseleccion" name="reporteiluminacion_criterioseleccion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_criterioseleccion">Guardar criterio de selección <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="6_2_1">6.2.1.- Índice de área</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado" id="indicearea_descripcion_1" style="display: none;">El método utilizado para esta evaluación se basa en dividir el área de trabajo en zonas del mismo tamaño, en consideración a lo establecido en la tabla “relación entre el índice de área y el número de zonas de medición” NOM-025-STPS-2008. Con el objetivo de describir el entorno ambiental de la iluminación de una forma confiable.</p>
						<p class="justificado" id="indicearea_descripcion_2" style="display: none;">Para el presente estudio de iluminación realizado en <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b> se empleó el criterio de selección: Puesto de Trabajo, por lo que no se incluye información en relación al método de índice de área dentro de este apartado.</p>
						<style type="text/css">
							#tabla_reporte_6_2_1 th {
								padding: 2px 1px;
								text-align: center;
								border: 1px #E5E5E5 solid;
							}

							#tabla_reporte_6_2_1 th h3 {
								padding: 2px 1px;
								line-height: 22px;
								margin: 0px;
							}
						</style>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2_1">
							<thead>
								<tr>
									<th colspan="8">
										<h3>Total de puntos evaluados por índice de área (<span id="total_ic">0</span>)</h3>
									</th>
								</tr>
								<tr>
									<th width="80">Puntos<br>evaluados</th>
									<th width="130">Instalación</th>
									<th width="130">Área</th>
									<th>Categoría</th>
									<th width="200">Actividades</th>
									<th width="60">IC</th>
									<th width="80">zonas a<br>evaluar<br>N.M.Z.E</th>
									<th width="80">zonas a<br>evaluar<br>N.Z.C.P.L</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
						<div class="informacion_estatica">
							<br><b>N/P:</b> No Proporcionado
							<br><b>IC:</b> Índice de Área
							<br><b>N.M.Z.E:</b> Número Mínimo de Zonas a Evaluar
							<br><b>N.Z.C.P.L:</b> Número de Zonas a Considerar Por la Limitación
						</div>
					</div>
				</div>
				<h4 class="card-title" id="6_2_2">6.2.2.- Puesto de trabajo</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado" id="puestotrabajo_descripcion_1" style="display: none;">El método de evaluación definido por puesto de trabajo se basa en realizar al menos una medición en cada plano de trabajo donde el trabajador realice sus actividades laborales. A continuación, se presentan:</p>
						<p class="justificado" id="puestotrabajo_descripcion_2" style="display: none;">Para el presente estudio de iluminación realizado en <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b> se empleó el criterio de selección: Índice de área, por lo que no se incluye información en relación al método de Puesto de Trabajo dentro de este apartado.</p>
						<style type="text/css">
							#tabla_reporte_6_2_2 th {
								padding: 2px 1px;
								text-align: center;
								border: 1px #E5E5E5 solid;
							}

							#tabla_reporte_6_2_2 th h3 {
								padding: 2px 1px;
								line-height: 22px;
								margin: 0px;
							}
						</style>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2_2">
							<thead>
								<tr>
									<th colspan="6">
										<h3>Total de puntos evaluados por puesto de trabajo (<span id="total_pt">0</span>)</h3>
									</th>
								</tr>
								<tr>
									<th width="80">Puntos<br>evaluados</th>
									<th width="130">Instalación</th>
									<th width="130">Área</th>
									<th width="">Categoría</th>
									<th width="200">Actividades</th>
									<th width="200">Tarea visual</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de iluminación" id="boton_reporte_nuevoiluminacionpunto">
						<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de iluminación
					</button>
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Importar puntos de iluminación" id="boton_reporte_iluminacion_importar">
						<span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Importar
					</button>
				</ol>
				<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_iluminacionpuntos">
					<thead>
						<tr>
							<th width="60">Punto</th>
							<th width="150">Instalación</th>
							<th width="150">Área</th>
							<th width="">Categoría</th>
							<th width="80">Concepto</th>
							<th width="60">Editar</th>
							<th width="60">Eliminar</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<h4 class="card-title" id="7_1">7.1.- Resultados del nivel de iluminación</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">En seguida se presentan los resultados obtenidos durante la evaluación de niveles de iluminación por número de medición, hora, ubicación, concepto, número de personal ocupacionalmente expuesto, categoría, nivel de iluminación mínimo requerido, nivel de iluminación en cada medición y su cumplimiento normativo.</p>
						<style type="text/css">
							#tabla_reporte_iluminacionresultados th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_iluminacionresultados td {
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
							}

							#tabla_reporte_iluminacionresultados tr:hover td {
								color: #000000;
							}
						</style>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_iluminacionresultados">
							<thead>
								<tr>
									<th rowspan="2" width="60">No.<br>Medición</th>
									<th colspan="3" width="120">Periodos de medición</th>
									<th rowspan="2" width="130">Ubicación</th>
									<th rowspan="2" width="80">Concepto</th>
									<th rowspan="2" width="50">No.<br>POE</th>
									<th rowspan="2" width="">Categoría</th>
									<th rowspan="2" width="50">NIMR (Lux)</th>
									<th colspan="3" width="180">Resultados mediciones</th>
									<th rowspan="2" width="90">Cumplimiento<br>normativo</th>
								</tr>
								<tr>
									<th>I</th>
									<th>II</th>
									<th>III</th>
									<th>Per. I<br>NI (Lux)</th>
									<th>Per. II<br>NI (Lux)</th>
									<th>Per. III<br>NI (Lux)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
						<div class="informacion_estatica">
							<br><b>NR:</b> No Registrado (Durante la medición no se encontró el personal que realiza actividades)
							<br><b>POE:</b> Personal Ocupacionalmente Expuesto
							<br><b>NIMR:</b> Nivel de Iluminación Mínimo Requerido
							<br><b>NI:</b> Nivel de Iluminación
							<br><b>Lux:</b> Unidad de medida, Luxes
						</div>
					</div>
				</div>
				<h4 class="card-title" id="7_2">7.2.- Resultados del nivel de reflexión</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">Por otro lado, se muestran los niveles de reflexión obtenidos durante la evaluación considerando el número de medición, hora, ubicación, número de personal ocupacionalmente expuesto, categoría, niveles máximos permisibles de reflexión, Kf, factor de reflexión en paredes y plano de trabajo, y su cumplimiento normativo.</p>
						<style type="text/css">
							#tabla_reporte_reflexionresultados th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_reflexionresultados td {
								padding: 1px !important;
								font-size: 0.7vw !important;
								text-align: center;
							}

							#tabla_reporte_reflexionresultados tr:hover td {
								color: #000000;
							}
						</style>
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_reflexionresultados">
							<thead>
								<tr>
									<th rowspan="3" width="60">No.<br>Medición</th>
									<th rowspan="2" colspan="3" width="120">Periodos de<br>medición</th>
									<th rowspan="3" width="130">Ubicación</th>
									<th rowspan="3" width="50">No.<br>POE</th>
									<th rowspan="3">Categoría</th>
									<th rowspan="2" colspan="2">NMPR, K<sub>f</sub> (%)</th>
									<th colspan="6">Resultados de las mediciones<br>con factor de corrección</th>
									<th rowspan="3" width="90">Cumplimiento<br>normativo</th>
								</tr>
								<tr>
									<th colspan="2">Per. I<br>FR (%)</th>
									<th colspan="2">Per. II<br>FR (%)</th>
									<th colspan="2">Per. III<br>FR (%)</th>
								</tr>
								<tr>
									<th>I</th>
									<th>II</th>
									<th>III</th>
									<th>(P)</th>
									<th>(PT)</th>
									<th>(P)</th>
									<th>(PT)</th>
									<th>(P)</th>
									<th>(PT)</th>
									<th>(P)</th>
									<th>(PT)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="col-12">
						<div class="informacion_estatica">
							<br><b>NR:</b> No Registrado (Durante la medición no se encontró el personal que realiza actividades)
							<br><b>N/A:</b> No Aplica
							<br><b>POE:</b> Personal Ocupacionalmente Expuesto
							<br><b>NMPR, K:</b> Nivel Máximo Permisible de Reflexión, Kf
							<br><b>P:</b> Paredes
							<br><b>PT:</b> Plano de Trabajo
							<br><b>FR:</b> factor de reflexión
						</div>
					</div>
				</div>
				<h4 class="card-title" id="7_3">7.3.- Matriz de exposición laboral</h4>
				<div class="row">
					<div class="col-12">
						<p class="justificado">La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico iluminación, así como información del área física y de la plantilla laboral de la instalación en cuestión.</p><br>
						<style type="text/css">
							#tabla_reporte_matrizexposicion th {
								background: #F9F9F9;
								border: 1px #E5E5E5 solid;
								padding: 1px !important;
								font-size: 0.6vw !important;
								text-align: center;
								vertical-align: middle;
							}

							#tabla_reporte_matrizexposicion td {
								padding: 1px !important;
								font-size: 0.6vw !important;
								text-align: center;
							}

							#tabla_reporte_matrizexposicion tr:hover td {
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
						<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_matrizexposicion">
							{{-- <thead>
									<tr>
										<th rowspan="3"><span class="rotartexto">Contador</span></th>
										<th rowspan="3">Subdirección o<br>corporativo</th>
										<th rowspan="3">Gerencia o<br>activo</th>
										<th rowspan="3">Instalación</th>
										<th rowspan="3">Área de<br>referencia<br>en atlas<br>de riesgo</th>
										<th rowspan="3">Nombre</th>
										<th rowspan="3">Ficha</th>
										<th rowspan="3">Categoría</th>
										<th rowspan="3">Número<br>de<br>personas<br>en el área</th>
										<th rowspan="3">Grupo de<br>exposición<br>homogénea</th>
										<th rowspan="3">Niveles<br>mínimos de<br>iluminación<br>(lux)</th>
										<th colspan="3">Nivel de<br>iluminación<br>(E2)(lux)</th>
										<th colspan="3">Niveles<br>máximos<br>permisibles<br>de reflexión<br>plano de<br>trabajo (50%)</th>
										<th colspan="3">Niveles<br>máximos<br>permisibles<br>de reflexión<br>paredes (60%)</th>
									</tr>
									<tr>
										<th colspan="3">Periodo</th>
										<th colspan="3">Periodo</th>
										<th colspan="3">Periodo</th>
									</tr>
									<tr>
										<th>I</th>
										<th>II</th>
										<th>III</th>
										<th>I</th>
										<th>II</th>
										<th>III</th>
										<th>I</th>
										<th>II</th>
										<th>III</th>
									</tr>
								</thead>
								<tbody></tbody> --}}
						</table>
					</div>
					<div class="col-12">
						<div class="informacion_estatica">
							<br><b>NR:</b> No Registrado (Durante la medición no se encontró el personal que realiza actividades)
						</div>
					</div>
				</div>
				<h4 class="card-title" id="8">8.- Conclusiones</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_conclusion" id="form_reporte_conclusion">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<div class="form-group">
								<textarea class="form-control" style="margin-bottom: 0px;" rows="20" id="reporteiluminacion_conclusion" name="reporteiluminacion_conclusion" required></textarea>
							</div>
						</div>
						<div class="col-12" style="text-align: right;">
							<div class="form-group">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_conclusion">Guardar conclusión <i class="fa fa-save"></i></button>
							</div>
						</div>
						<div class="col-12">
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
								}

								#tabla_dashboard td.td_top {
									vertical-align: top;
								}

								#tabla_dashboard td .icono {
									width: 100%;
									font-size: 3vw !important;
									margin: 10px 0px;
								}

								#tabla_dashboard td .texto {
									font-size: 0.8vw !important;
									line-height: 0.5 !important;
								}

								#tabla_dashboard td .numero {
									font-size: 1vw !important;
									font-weight: bold;
									margin-bottom: 10px;
								}
							</style>
							<table class="table" width="100%" id="tabla_dashboard">
								<tbody>
									<tr>
										<th colspan="5" style="font-size: 18px!important;">Agente condiciones de iluminación en <span class="div_instalacion_nombre">NOMBRE INSTALACION</span>.</th>
									</tr>
									<tr>
										<th colspan="3">Nivel de cumplimiento por área</th>
										<th>Categoría crítica</th>
										<th>Cumplimiento normativo en nivel de iluminación</th>
									</tr>
									<tr>
										<td colspan="3" class="td_top">
											<div class="row" id="areas_cumplimiento">
											</div>
										</td>
										<td class="td_top">
											{{-- <i class="fa fa-users text-info icono"></i> --}}
											<span class="texto" id="categorias_criticas">Nivel de iluminación:</span><br>
											<span class="texto">Nivel de iluminación: <br><span id="nivel_iluminacion">0</span></span>
										</td>
										<td>
											<div id="grafica_iluminacion" style="height: 200px; width: 200px; border: 0px #000000 solid; margin: 0px auto;"></div>
											<span style="color: #8ee66b;">■</span> Dentro de norma<br>
											<span style="color: #fc4b6c;">■</span> Fuera de norma
										</td>
									</tr>
									<tr>
										<th colspan="4">Distribución de puntos evaluados</th>
										<th>Cumplimiento normativo en nivel de reflexión</th>
									</tr>
									<tr>
										<td width="20%">
											<i class="fa fa-search text-info icono"></i>
											<span class="texto">Total de puntos<br>por medición</span><br>
											<span class="numero" id="total_iluminacion">0</span>
										</td>
										<td width="20%">
											<span class="text-secondary" style="font-size:1.5vw!important;">
												Nivel de<br>iluminación
											</span>
										</td>
										<td width="20%">
											<i class="fa fa-warning icono" style="color: #8ee66b;"></i>
											<span class="texto">Dentro de<br>norma</span><br>
											<span class="numero" id="total_iluminacion_dentronorma">0</span>
										</td>
										<td width="20%">
											<i class="fa fa-warning icono" style="color: #fc4b6c;"></i>
											<span class="texto">Fuera de<br>norma</span><br>
											<span class="numero" id="total_iluminacion_fueranorma">0</span>
										</td>
										<td rowspan="2">
											<div id="grafica_reflexion" style="height: 200px; width: 200px; border: 0px #000000 solid; margin: 0px auto;"></div>
											<span style="color: #8ee66b;">■</span> Dentro de norma<br>
											<span style="color: #fc4b6c;">■</span> Fuera de norma
										</td>
									</tr>
									<tr>
										<td>
											<i class="fa fa-list-alt text-info icono"></i>
											<span class="texto">Total de<br>Recomendaciones</span><br>
											<span class="numero" id="recomendaciones_total">5</span>
										</td>
										<td>
											<span class="text-secondary" style="font-size:1.5vw!important;">
												Nivel de<br>reflexión
											</span>
										</td>
										<td>
											<i class="fa fa-warning icono" style="color: #8ee66b;"></i>
											<span class="texto">Dentro de<br>norma</span><br>
											<span class="numero" id="total_reflexion_dentronorma">0</span>
										</td>
										<td>
											<i class="fa fa-warning icono" style="color: #fc4b6c;"></i>
											<span class="texto">Fuera de<br>norma</span><br>
											<span class="numero" id="total_reflexion_fueranorma">0</span>
										</td>
									</tr>
									<tr>
										<th colspan="5">Análisis derivado del informe de resultados de la evaluación condiciones de iluminación den los centros de trabajo (NOM-025-STPS-2008).</th>
									</tr>
								</tbody>
							</table>
							{{-- <div id="captura" style="height: 800px; width: 100%; border: 1px #000 solid;">graficas</div><br> --}}
							{{-- <button type="button" class="btn btn-success waves-effect waves-light" id="botonguardar_generargraficas">Guardar gráficas <i class="fa fa-chart"></i></button> --}}
						</div>
					</div>
				</form>
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
				<h4 class="card-title" id="10">10.- Responsables del informe</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_responsablesinforme" id="form_reporte_responsablesinforme">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteiluminacion_carpetadocumentoshistorial" name="reporteiluminacion_carpetadocumentoshistorial">
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Nombre del responsable técnico</label>
										<input type="text" class="form-control" id="reporteiluminacion_responsable1" name="reporteiluminacion_responsable1" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Cargo del responsable técnico</label>
										<input type="text" class="form-control" id="reporteiluminacion_responsable1cargo" name="reporteiluminacion_responsable1cargo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Foto documento del responsable técnico</label>
										<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc1"></i>
										<input type="hidden" class="form-control" id="reporteiluminacion_responsable1documento" name="reporteiluminacion_responsable1documento">
										<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteiluminacionresponsable1documento" name="reporteiluminacionresponsable1documento" onchange="redimencionar_foto('reporteiluminacionresponsable1documento', 'reporteiluminacion_responsable1documento', 'botonguardar_reporte_responsablesinforme');" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Nombre del administrativo prestador de servicio</label>
										<input type="text" class="form-control" id="reporteiluminacion_responsable2" name="reporteiluminacion_responsable2" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Cargo del administrativo prestador de servicio</label>
										<input type="text" class="form-control" id="reporteiluminacion_responsable2cargo" name="reporteiluminacion_responsable2cargo" required>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label>Foto documento del prestador de servicio</label>
										<i class="fa fa-download fa-2x text-success" style="position: absolute; margin-top: 6px; margin-left: 8px; z-index: 50; text-shadow: 1px 1px 0 #FFFFFF, 1px -1px 0 #FFFFFF, -1px 1px 0 #FFFFFF, -1px -1px 0 #FFFFFF, 1px 0px 0 #FFFFFF, 0px 1px 0 #FFFFFF, -1px 0px 0 #FFFFFF, 0px -1px 0 #FFFFFF; cursor: pointer; display: none;" data-toggle="tooltip" title="Descargar foto documento" id="boton_descargarresponsabledoc2"></i>
										<input type="hidden" class="form-control" id="reporteiluminacion_responsable2documento" name="reporteiluminacion_responsable2documento">
										<input type="file" class="dropify" accept="image/jpeg,image/x-png" data-allowed-file-extensions="jpg png JPG PNG" data-height="280" id="reporteiluminacionresponsable2documento" name="reporteiluminacionresponsable2documento" onchange="redimencionar_foto('reporteiluminacionresponsable2documento', 'reporteiluminacion_responsable2documento', 'botonguardar_reporte_responsablesinforme');" required>
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
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Se encontraron <span id="memoriafotografica_total">0</span> fotos de los puntos de iluminación que se agregaran al informe.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_2">11.2.- Anexo 2: Planos de ubicación de luminarias y puntos de evaluación por área</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_planos" id="form_reporte_planos">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Solo los planos de las carpetas elegidas aparecerán en el informe de iluminación.</p>
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
				<h4 class="card-title" id="11_3">11.3.- Anexo 3: Equipo utilizado en la medición</h4>
				<form method="post" enctype="multipart/form-data" name="form_reporte_equipoutilizado" id="form_reporte_equipoutilizado">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
						</div>
						<div class="col-12">
							<table class="table-hover tabla_info_centrado" width="100%" id="tabla_reporte_equipoutilizado">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="60">Seleccionado</th>
										<th>Equipo</th>
										<th width="200">Marca / Modelo / Serie</th>
										<th width="160">Vigencia</th>
										<!-- <th width="60">Certificado</th> -->
										<!-- <th width="60">Aplica Carta</th> -->
										<!-- <th width="120">Carta PDF</th> -->
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
				<h4 class="card-title" id="11_4">11.4.- Anexo 4: Incertidumbre de la medición</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">La incertidumbre de la medición, se encuentra disponible para consulta dentro del informe de resultados (Anexo 6) emitido por el laboratorio aprobado ya que dicho informe no puede ser alterado o modificado en su contenido.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_5">11.5.- Anexo 5: Copia de certificados o aviso de calibración del equipo</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El certificado del equipo utilizado seleccionado en el punto 11.3 “Anexo 3: Equipo utilizado en la medición” se adjuntará en la impresión del reporte en formato PDF.</p>
					</div>
				</div>
				<h4 class="card-title" id="11_6">11.6.- Anexo 6: Informe de resultados</h4>
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_informeresultados">Guardar anexo informe de resultados <i class="fa fa-save"></i></button>
							</div>
						</div>
					</div>
				</form>
				<h4 class="card-title" id="11_7">11.7.- Anexo 7: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario aprobado para llevar a cabo la evaluación de conformidad con la NOM-025-STPS-2008, condiciones de iluminación en los centros de trabajo, mismo que aparece dentro del registro de aprobación ante la Secretaría del Trabajo y Previsión Social (STPS).</p>
					</div>
				</div>
				<h4 class="card-title" id="11_8">11.8.- Anexo 8: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
				<div class="row">
					<div class="col-12" style="padding-top: 10px;">
						<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
						<p class="justificado">El muestreo se realizó por un signatario acreditado en la NOM-025-STPS-2008, condiciones de iluminación en los centros de trabajo, el cual aparece dentro de la acreditación del laboratorio ante la entidad mexicana de acreditación (ema).</p>
					</div>
				</div>
				<h4 class="card-title" id="12">12.- Elegir anexos 7 (STPS) y 8 (EMA)</h4>
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
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_anexos">Guardar anexos 7 (STPS) y 8 (EMA) <i class="fa fa-save"></i></button>
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
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="botoncerrar_modalvisor_reporteiluminacion">Cerrar</button>
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
						<div class="col-6">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reportearea_instalacion" name="reportearea_instalacion" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reportearea_nombre" name="reportearea_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. orden del área</label>
								<input type="number" class="form-control" id="reportearea_orden" name="reportearea_orden" required>
							</div>
						</div>
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
								Descripción de las instalaciones
							</ol>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label><b>x</b>&nbsp;Largo del área (m) *</label>
								<input type="number" step="any" class="form-control" id="reportearea_largo" name="reportearea_largo" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label><b>y</b>&nbsp;Ancho del área (m) *</label>
								<input type="number" step="any" class="form-control" id="reportearea_ancho" name="reportearea_ancho" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label><b>h</b>&nbsp;Alto del área (m) *</label>
								<input type="number" step="any" class="form-control" id="reportearea_alto" name="reportearea_alto" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>% de operacion en el área</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reporteiluminacionarea_porcientooperacion" name="reporteiluminacionarea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Puntos evaluados por IC *</label>
								<input type="number" class="form-control" id="reportearea_puntos_ic" name="reportearea_puntos_ic" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Puntos evaluados por PT *</label>
								<input type="number" class="form-control" id="reportearea_puntos_pt" name="reportearea_puntos_pt" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Criterio *</label>
								<input type="text" class="form-control" id="reportearea_criterio" name="reportearea_criterio" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Color de techo</label>
								<input type="text" class="form-control" id="reportearea_colortecho" name="reportearea_colortecho">
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Color de paredes</label>
								<input type="text" class="form-control" id="reportearea_paredes" name="reportearea_paredes">
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Color de piso</label>
								<input type="text" class="form-control" id="reportearea_colorpiso" name="reportearea_colorpiso">
							</div>
						</div>

						<div class="col-4">
							<div class="form-group">
								<label>Superficie techo</label>
								<input type="text" class="form-control" id="reportearea_superficietecho" name="reportearea_superficietecho" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Superficie paredes</label>
								<input type="text" class="form-control" id="reportearea_superficieparedes" name="reportearea_superficieparedes" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Superficie piso</label>
								<input type="text" class="form-control" id="reportearea_superficiepiso" name="reportearea_superficiepiso" required>
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
								<select class="custom-select form-control" id="reportearea_sistemailuminacion" name="reportearea_sistemailuminacion" required>
									<option value=""></option>
									@foreach($sistemas as $dato)
									<option value="{{$dato->NOMBRE}}">{{ $dato->NOMBRE }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Potencia de las lámparas *</label>
								<input type="text" class="form-control" id="reportearea_potenciaslamparas" name="reportearea_potenciaslamparas" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>N° de lámparas *</label>
								<input type="number	" class="form-control" id="reportearea_numlamparas" name="reportearea_numlamparas" required>
							</div>
						</div>

						<div class="col-4">
							<div class="form-group">
								<label>(h) Altura (m) *</label>
								<input type="number" class="form-control" id="reportearea_alturalamparas" name="reportearea_alturalamparas" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Programa de mantenimiento *</label>
								<input type="text" class="form-control" id="reportearea_programamantenimiento" name="reportearea_programamantenimiento" required>
							</div>
						</div>

						<div class="col-4">
							<div class="form-group">
								<label>Tipo de iluminación</label>
								<select class="custom-select form-control" id="reportearea_tipoiluminacion" name="reportearea_tipoiluminacion" required>
									<option value=""></option>
									<option value="1">Natural</option>
									<option value="2">Artificial</option>
									<option value="3">Natural y artificial</option>
								</select>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label>Descripción de trabajo que requiere iluminación localizada *</label>
								<input type="text" class="form-control" id="reportearea_descripcionilimunacion" name="reportearea_descripcionilimunacion" required>
							</div>
						</div>


						{{-- <div class="col-3">
							<div class="form-group">
								<label>Influencia de luz natural</label>
								<select class="custom-select form-control" id="reportearea_luznatural" name="reportearea_luznatural" required>
									<option value=""></option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Iluminación localizada</label>
								<select class="custom-select form-control" id="reportearea_iluminacionlocalizada" name="reportearea_iluminacionlocalizada" required>
									<option value=""></option>
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
<!-- MODAL-REPORTE-ÁREA -->
<!-- ============================================================== -->



<!-- ============================================================== -->
<!-- MODAL-REPORTE-ÁREA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_iluminacionpunto>.modal-dialog {
		min-width: 90% !important;
	}

	#modal_reporte_iluminacionpunto .modal-body .form-group {
		margin: 0px 0px 12px 0px !important;
		padding: 0px !important;
	}

	#modal_reporte_iluminacionpunto .modal-body .form-group label {
		margin: 0px !important;
		padding: 0px 0px 3px 0px !important;
	}
</style>
<div id="modal_reporte_iluminacionpunto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_reporte_iluminacionpunto" id="form_reporte_iluminacionpunto">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Área</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="reporteiluminacionpunto_id" name="reporteiluminacionpunto_id" value="0">
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. Punto</label>
								<input type="number" class="form-control" id="reporteiluminacionpuntos_nopunto" name="reporteiluminacionpuntos_nopunto" required>
							</div>
						</div>
						<div class="col-5">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reporteiluminacionpuntos_area_id" name="reporteiluminacionpuntos_area_id" onchange="mostrar_categoriasarea(this.value, 0);" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-5">
							<div class="form-group">
								<label>Categoría</label>
								<select class="custom-select form-control" id="reporteiluminacionpuntos_categoria_id" name="reporteiluminacionpuntos_categoria_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. de POE</label>
								<input type="number" class="form-control" id="reporteiluminacionpuntos_nopoe" name="reporteiluminacionpuntos_nopoe" required>
							</div>
						</div>
						<div class="col-5">
							<div class="form-group">
								<label>Nombre</label>
								<input type="text" class="form-control" id="reporteiluminacionpuntos_nombre" name="reporteiluminacionpuntos_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Ficha</label>
								<input type="text" class="form-control" id="reporteiluminacionpuntos_ficha" name="reporteiluminacionpuntos_ficha" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Concepto</label>
								<select class="custom-select form-control" id="reporteiluminacionpuntos_concepto" name="reporteiluminacionpuntos_concepto" required>
									<option value=""></option>
									<option value="Índice de Área (IC)">Índice de Área (IC)</option>
									<option value="Puesto de Trabajo">Puesto de Trabajo</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
								Fecha y hora de medición
							</ol>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Fecha evaluación</label>
								<div class="input-group">
									<input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="reporteiluminacionpuntos_fechaeval" name="reporteiluminacionpuntos_fechaeval" required>
									<span class="input-group-addon"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #0d47a1;">Horario periodo 1</label>
								{{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario1" name="reporteiluminacionpuntos_horario1" required> --}}
								<input type="time" class="form-control" id="reporteiluminacionpuntos_horario1" name="reporteiluminacionpuntos_horario1" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #78281F;">Horario periodo 2</label>
								{{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario2" name="reporteiluminacionpuntos_horario2" required> --}}
								<input type="time" class="form-control" id="reporteiluminacionpuntos_horario2" name="reporteiluminacionpuntos_horario2">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #00695c;">Horario periodo 3</label>
								{{-- <input type="text" class="form-control" placeholder="hh:mm" id="reporteiluminacionpuntos_horario3" name="reporteiluminacionpuntos_horario3" required> --}}
								<input type="time" class="form-control" id="reporteiluminacionpuntos_horario3" name="reporteiluminacionpuntos_horario3">
							</div>
						</div>
						<div class="col-2">
							&nbsp;
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
								Resultados de niveles de iluminación
							</ol>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Nivel de iluminación mínimo requerido (Lux)</label>
								<input type="number" step="any" class="form-control limite_lux" id="reporteiluminacionpuntos_lux" name="reporteiluminacionpuntos_lux" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<div style="position: absolute; margin-top: 28px;">
									<span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
								</div>
								<div style="position: absolute; margin-top: 50px;">
									<span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
								</div>
								<div style="position: absolute; margin-top: 24px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed1menor" name="reporteiluminacionpuntos_luxmed1menor" />
									<label for="reporteiluminacionpuntos_luxmed1menor"><b>&nbsp;</b></label>
								</div>
								<div style="position: absolute; margin-top: 46px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed1mayor" name="reporteiluminacionpuntos_luxmed1mayor" />
									<label for="reporteiluminacionpuntos_luxmed1mayor"><b>&nbsp;</b></label>
								</div>

								<label style="color: #0d47a1;">Periodo 1 NI (Lux)</label>
								<div style="width: 100%; padding-left: 45px;">
									<input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed1" name="reporteiluminacionpuntos_luxmed1" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
								</div>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<div style="position: absolute; margin-top: 28px;">
									<span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
								</div>
								<div style="position: absolute; margin-top: 50px;">
									<span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
								</div>
								<div style="position: absolute; margin-top: 24px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed2menor" name="reporteiluminacionpuntos_luxmed2menor" />
									<label for="reporteiluminacionpuntos_luxmed2menor"><b>&nbsp;</b></label>
								</div>
								<div style="position: absolute; margin-top: 46px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed2mayor" name="reporteiluminacionpuntos_luxmed2mayor" />
									<label for="reporteiluminacionpuntos_luxmed2mayor"><b>&nbsp;</b></label>
								</div>

								<label style="color: #78281F;">Periodo 2 NI (Lux)</label>
								<div style="width: 100%; padding-left: 45px;">
									<input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed2" name="reporteiluminacionpuntos_luxmed2" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
								</div>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<div style="position: absolute; margin-top: 28px;">
									<span style="font-size: 28px; line-height: 12px;">&#60;</span> {{-- < --}}
								</div>
								<div style="position: absolute; margin-top: 50px;">
									<span style="font-size: 28px; line-height: 12px;">&#62;</span> {{-- > --}}
								</div>
								<div style="position: absolute; margin-top: 24px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed3menor" name="reporteiluminacionpuntos_luxmed3menor" />
									<label for="reporteiluminacionpuntos_luxmed3menor"><b>&nbsp;</b></label>
								</div>
								<div style="position: absolute; margin-top: 46px; margin-left: 20px;">
									<input type="checkbox" class="filled-in chk-col-brown" id="reporteiluminacionpuntos_luxmed3mayor" name="reporteiluminacionpuntos_luxmed3mayor" />
									<label for="reporteiluminacionpuntos_luxmed3mayor"><b>&nbsp;</b></label>
								</div>

								<label style="color: #00695c;">Periodo 3 NI (Lux)</label>
								<div style="width: 100%; padding-left: 45px;">
									<input type="number" step="any" class="form-control resultado_lux" id="reporteiluminacionpuntos_luxmed3" name="reporteiluminacionpuntos_luxmed3" required onchange="calcula_resultado_iluminacion('limite_lux', 'resultado_lux', 'N/A (NIMR)');">
								</div>
							</div>
						</div>
						<div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_lux">
							{{-- <b class="text-success"><i class="fa fa-check"></i> Dentro de norma</b> --}}
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">
								Resultados del nivel de reflexión
							</ol>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Factor de reflexión en paredes (P)</label>
								<input type="number" step="any" class="form-control limite_frp" id="reporteiluminacionpuntos_frp" name="reporteiluminacionpuntos_frp" value="60" readonly>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #0d47a1;">Periodo 1 FR (P) (%)</label>
								<input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed1" name="reporteiluminacionpuntos_frpmed1" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #78281F;">Periodo 2 FR (P) (%)</label>
								<input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed2" name="reporteiluminacionpuntos_frpmed2" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #00695c;">Periodo 3 FR (P) (%)</label>
								<input type="number" step="any" class="form-control resultado_frp" id="reporteiluminacionpuntos_frpmed3" name="reporteiluminacionpuntos_frpmed3" required onchange="calcula_resultado_reflexion('limite_frp', 'resultado_frp', 'N/A (FR-P)');">
							</div>
						</div>
						<div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_frp">
							{{-- <b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b> --}}
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Factor de reflexión en plano trabajo (PT)</label>
								<input type="number" step="any" class="form-control limite_frpt" id="reporteiluminacionpuntos_frpt" name="reporteiluminacionpuntos_frpt" value="50" readonly>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #0d47a1;">Periodo 1 FR (PT) (%)</label>
								<input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed1" name="reporteiluminacionpuntos_frptmed1" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #78281F;">Periodo 2 FR (PT) (%)</label>
								<input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed2" name="reporteiluminacionpuntos_frptmed2" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label style="color: #00695c;">Periodo 3 FR (PT) (%)</label>
								<input type="number" step="any" class="form-control resultado_frpt" id="reporteiluminacionpuntos_frptmed3" name="reporteiluminacionpuntos_frptmed3" required onchange="calcula_resultado_reflexion('limite_frpt', 'resultado_frpt', 'N/A (FR-PT)');">
							</div>
						</div>
						<div class="col-2 align-middle" style="text-align: right; padding-top: 30px;" id="resultado_frpt">
							{{-- <b class="text-danger"><i class="fa fa-ban"></i> Fuera de norma</b> --}}
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_iluminacionpunto">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-ÁREA -->
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
<script src="/js_sitio/reportes/reporteiluminacion.js"></script>