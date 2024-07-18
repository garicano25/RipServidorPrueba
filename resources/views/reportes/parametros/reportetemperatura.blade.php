
<style type="text/css">
	.reporte_estructura
	{
		font-size: 14px!important;
		line-height: 14px!important;
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



	.list-group-item
	{
		padding: 2px 1px;
		font-family: Agency FB;
		/*font-family: Calibri;*/
		font-size: 0.55vw!important;
		line-height: 1;
	}

	.list-group-item.active
	{
		font-size: 1.2vw!important;
	}

	.list-group-item i
	{
		color: #fc4b6c;
	}

	.list-group-item:hover
	{
		font-size: 1.2vw!important;
	}

	.list-group .submenu
	{
		padding: 2px 1px 2px 8px;
	}

	.list-group .subsubmenu
	{
		padding: 2px 1px 2px 20px;
	}

	.card-title{
		margin: 20px 0px 10px 0px;
		color: blue;
	}

	.form-group{
        margin: 0px 0px 12px 0px!important;
        padding: 0px!important;
    }

    .form-group label{
        margin: 0px!important;
        padding: 0px 0px 3px 0px!important;
    }

	table
	{
		width: 100%;
		margin: 0px;
		font-family: inherit;
	}

	table th
	{
		padding: 1px 2px;		
		color: #777777;
	}

	table td.justificado
	{
		padding: 4px!important;
		text-align: justify!important;
	}

	p.justificado
	{
		text-align: justify!important;
		margin: 0px!important;
		padding: 0px!important;
	}

	textarea{
		text-align: justify!important;
	}

	div.informacion_estatica
	{
		font-size: 14px;
		line-height: 14px!important;
		text-align: justify;
	}

	div.imagen_formula
	{
		text-align: center;
		border: 0px #F00 solid;
	}

	div.informacion_estatica b
	{
		font-size: 13px;
		font-weight: bold;
		color: #777777;
	}

	.tabla_info_centrado th
	{
		background: #F9F9F9;
		border: 1px #E5E5E5 solid!important;
		padding: 2px!important;
		text-align: center;
		vertical-align: middle!important;
	}

	.tabla_info_centrado td
	{
		border: 1px #E5E5E5 solid!important;
		padding: 4px!important;
		text-align: center;
		vertical-align: middle!important;
	}

	.tabla_info_justificado th
	{
		background: #F9F9F9;
		border: 1px #E5E5E5 solid!important;
		padding: 2px!important;
		text-align: center;
		vertical-align: middle!important;
	}

	.tabla_info_justificado td
	{
		border: 1px #E5E5E5 solid!important;
		padding: 4px!important;
		text-align: justify;
		vertical-align: middle!important;
	}

	.tabla_reporte th
	{
		background: #F9F9F9;
		border: 1px #E5E5E5 solid!important;
		padding: 2px!important;
		text-align: center;
		vertical-align: middle!important;
	}

	.tabla_reporte td
	{
		border-bottom: 1px #E5E5E5 solid!important;
		padding: 4px!important;
		text-align: center;
		vertical-align: middle!important;
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
				<a href="#4_2" class="list-group-item submenu">4.2.- Método de evaluación para condiciones térmicas elevadas <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#4_2_1" class="list-group-item subsubmenu">4.2.1.- Límites máximos permisibles de exposición a condiciones térmicas elevadas <i class="fa fa-times" id="menureporte_4_2_1"></i></a>
				<a href="#4_3" class="list-group-item submenu">4.3.- Método de evaluación para condiciones térmicas abatidas <i class="fa fa-times" id="menureporte_4_3"></i></a>
				<a href="#4_3_1" class="list-group-item subsubmenu">4.3.1.- Límites máximos permisibles de exposición a condiciones térmicas abatidas <i class="fa fa-times" id="menureporte_4_3_1"></i></a>
				<a href="#5" class="list-group-item">5.- Reconocimiento</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
				<a href="#5_3" class="list-group-item submenu">5.3.- Población ocupacionalmente expuesta <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Actividades del personal expuesto <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Tabla de identificación de las áreas <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#6_2" class="list-group-item submenu">6.2.- Método empleado para la evaluación y criterio de selección</a>
				<a href="#6_2_1" class="list-group-item subsubmenu">6.2.1.- Condiciones térmicas elevadas <i class="fa fa-times" id="menureporte_6_2_1"></i></a>
				<a href="#6_2_2" class="list-group-item subsubmenu">6.2.2.- Condiciones térmicas abatidas <i class="fa fa-times" id="menureporte_6_2_2"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados</a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Tabla de resultados del agente temperaturas elevadas <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Tabla de resultados del agente temperaturas abatidas <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#7_3" class="list-group-item submenu">7.3.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_3"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Responsables del informe <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Anexos</a>
				<a href="#11_1" class="list-group-item submenu">11.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_11_1"></i></a>
				<a href="#11_2" class="list-group-item submenu">11.2.- Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados <i class="fa fa-times" id="menureporte_11_2"></i></a>
				<a href="#11_3" class="list-group-item submenu">11.3.- Anexo 3: Memoria de cálculo <i class="fa fa-times" id="menureporte_11_3"></i></a>
				<a href="#11_4" class="list-group-item submenu">11.4.- Anexo 4: Equipo utilizado en la medición <i class="fa fa-times" id="menureporte_11_4"></i></a>
				<a href="#11_5" class="list-group-item submenu">11.5.- Anexo 5: Copia de los certificados o aviso de calibración del equipo <i class="fa fa-times" id="menureporte_11_5"></i></a>
				<a href="#11_6" class="list-group-item submenu">11.6.- Anexo 6: Informe de resultados <i class="fa fa-times" id="menureporte_11_6"></i></a>
				<a href="#11_7" class="list-group-item submenu">11.7.- Anexo 7: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_11_7"></i></a>
				<a href="#11_8" class="list-group-item submenu">11.8.- Anexo 8: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_11_8"></i></a>
				<a href="#12" class="list-group-item">12.- Seleccionar Anexos 7 (STPS) y 8 (EMA) <i class="fa fa-times" id="menureporte_12"></i></a>
				<a href="#13" class="list-group-item submenu" id="menu_opcion_final">Generar informe <i class="fa fa-download text-success" id="menureporte_13"></i></a>
			</div>
		</div>
	</div>
	<div class="col-xlg-10 col-lg-9 col-md-7">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" style="padding: 0px!important;" id="0">Portada</h4>
					<form method="post" enctype="multipart/form-data" name="form_reporte_portada" id="form_reporte_portada">
						<div class="row">
							<div class="col-12">
								{!! csrf_field() !!}
							</div>
							<div class="col-1">
								<div class="form-group">
									<label class="demo-switch-title">Mostrar</label>
									<div class="switch" style="margin-top: 6px;">
										<label><input type="checkbox" id="reporte_catsubdireccion_activo" name="reporte_catsubdireccion_activo" checked><span class="lever switch-col-light-blue"></span></label>
									</div>
								</div>
							</div>
							<div class="col-11">
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
							<div class="col-1">
								<div class="form-group">
									<label class="demo-switch-title">Mostrar</label>


									<div class="switch" style="margin-top: 6px;">
										<label><input type="checkbox" id="reporte_catgerencia_activo" name="reporte_catgerencia_activo" checked><span class="lever switch-col-light-blue"></span></label>
									</div>
								</div>
							</div>
							<div class="col-11">
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
							<div class="col-1">
								<div class="form-group">
									<label class="demo-switch-title">Mostrar</label>
									<div class="switch" style="margin-top: 6px;">
										<label><input type="checkbox" id="reporte_catactivo_activo" name="reporte_catactivo_activo" checked><span class="lever switch-col-light-blue"></span></label>
									</div>
								</div>
							</div>
							<div class="col-11">
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
							<div class="col-12">
								<div class="form-group">
									<label>Instalación</label>
									<input type="text" class="form-control" id="reporte_instalacion" name="reporte_instalacion" onchange="instalacion_nombre(this.value);" readonly>
								</div>
							</div>
							<div class="col-4"></div>
							<div class="col-1">
								<div class="form-group">
									<label class="demo-switch-title">Mostrar</label>
									<div class="switch" style="margin-top: 6px;">
										<label><input type="checkbox" id="reporte_catregion_activo" name="reporte_catregion_activo" checked><span class="lever switch-col-light-blue"></span></label>
									</div>
								</div>
							</div>
							<div class="col-3">
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
							<div class="col-4"></div>
							<div class="col-4"></div>
							<div class="col-4">
								<div class="form-group">
									<label>Fecha</label>
									<input type="text" class="form-control" id="reporte_fecha" name="reporte_fecha" required>
								</div>
							</div>
							<div class="col-4"></div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_portada">Guardar portada <i class="fa fa-save"></i></button>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_introduccion" name="reporte_introduccion" required></textarea>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivogeneral" name="reporte_objetivogeneral" required></textarea>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivoespecifico" name="reporte_objetivoespecifico" required></textarea>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="14" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="4_2">4.2.- Método de evaluación para condiciones térmicas elevadas</h4>
					<div class="row">
						<style type="text/css">
							.texto_metodologia
							{
								/*font-size:0.7vw!important;*/
								text-align: justify;
							}

							.tabla_metodologia th
							{
								background: #F9F9F9;
								border: 1px #E5E5E5 solid!important;
								padding: 1px!important;
								/*font-size:0.7vw!important;*/
								text-align: center;
								vertical-align: middle;
							}

							.tabla_metodologia td
							{
								padding: 1px!important;
								/*font-size:0.7vw!important;*/
								text-align: center;
								border: 1px #E5E5E5 solid!important;
								vertical-align: middle;
							}

							.tabla_metodologia tr:hover td
							{
								color: #000000;
							}
						</style>
						<div class="col-12">
							<div class="texto_metodologia">
								Para la presente evaluación se consideró aplicar el método del índice de temperatura de globo bulbo húmedo (I<sub>tgbh</sub>), el cual establece la medición de la temperatura axilar del trabajador expuesto, la humedad relativa, la velocidad del aire. Una vez concluida la evaluación se procedió a determinar el régimen de trabajo, de acuerdo con lo que indica los Límites Máximos Permisibles de Exposición (LMPE) a condiciones térmicas elevadas (Tabla A1 de la NOM-015-STPS-2001)<br><br>

								Durante la evaluación, se excluyen las áreas donde no existe POE y aquéllas en las que el índice de temperatura de globo bulbo húmedo fue igual o menor al LMPE del régimen de trabajo, la evaluación consistió en medir y promediar a tres diferentes alturas la temperatura de globo bulbo húmedo, con base en el tipo de estrategia a utilizar: puestos fijos y puestos móviles.<br><br>

								Al momento de concluir las evaluaciones, los valores obtenidos fueron registrados. Se procedió a determinar el índice de la temperatura de globo bulbo húmedo por cada punto evaluado mediante las ecuaciones descritas a continuación las cuales fueron referenciadas de la NOM-015-STPS-2001: mediante la ecuación (1) si la medición se realiza en interiores o exteriores sin carga solar, y mediante la ecuación (2) si la medición se realiza en exteriores con carga solar.<br>

								<div class="imagen_formula"><img src="/assets/images/reportes/reportetem_fig_4.2_1.jpg" height="80"></div><br>


								Para obtener la temperatura de globo bulbo húmedo promedio, se debe aplicar la siguiente ecuación:<br><br>

								<div class="imagen_formula"><img src="/assets/images/reportes/reportetem_fig_4.2_2.jpg" height="60"></div><br>


								Dónde:<br><br>

								I<sub>tgbh cabeza</sub>: Es el índice de temperatura del globo bulbo húmedo, medio en la región de la cabeza.<br>
								I<sub>tgbh abdomen</sub>: Es el índice de temperatura de globo bulbo húmedo, medido en la región de abdomen.<br>
								I<sub>tgbh tobillos</sub>: Es el índice de temperatura de globo bulbo húmedo, medido en la región de los tobillos.<br><br>

								Finalmente, con el resultado de la temperatura de globo bulbo húmedo promedio, se determinó el porcentaje del tiempo de exposición del POE de acuerdo con lo establecido en la tabla 1 de Límites Máximos Permisibles de Exposición a Condiciones Térmicas Elevadas tomada de la NOM-015-STPS-2001.
							</div>
						</div>
					</div>
				<h4 class="card-title" id="4_2_1">4.2.1.- Límites máximos permisibles de exposición a condiciones térmicas elevadas</h4>
					<div class="row">
						<div class="col-12">
							<div class="texto_metodologia">En la Tabla 1 se establecen los tiempos máximos permisibles de exposición y el tiempo mínimo de recuperación para jornadas de trabajo de ocho horas en condiciones térmicas elevadas las cuales se tomaron de referencia para la elaboración de dicho estudio.</div>
							<div class="informacion_estatica"><br>
								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<td colspan="4"><br><b>TABLA 1<br>Límites máximos permisibles de exposición a condiciones térmicas elevadas</b><br><br></td>
										</tr>
										<tr>
											<th width="40%" colspan="3">Temperatura máxima en °C de I<sub>tgbh</sub></th>
											<th width="60%" rowspan="3">Porcentaje del tiempo de exposición y de no exposición</th>
										</tr>
										<tr>
											<th width="40%" colspan="3">Régimen de trabajo</th>
										</tr>
										<tr>
											<th width="13.33%">Ligero</th>
											<th width="13.33%">Moderado</th>
											<th width="13.33%">Pesado</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>30.0</td>
											<td>26.7</td>
											<td>25.0</td>
											<td>100 % de exposición</td>
										</tr>
										<tr>
											<td>30.6</td>
											<td>27.8</td>
											<td>25.9</td>
											<td>75 % de exposición<br>25 % de recuperación en cada hora</td>
										</tr>
										<tr>
											<td>31.7</td>
											<td>29.4</td>
											<td>27.8</td>
											<td>50 % de exposición<br>50 % de recuperación en cada hora</td>
										</tr>
										<tr>
											<td>32.2</td>
											<td>31.1</td>
											<td>30.0</td>
											<td>25 % de exposición<br>75 % de recuperación en cada hora</td>
										</tr>
									</tbody>
									<tfoot>
										<td colspan="4"><br><b>Tabla obtenida de la (NOM-015-STPS-2001)</b><br><br></td>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				<h4 class="card-title" id="4_3">4.3.- Método de evaluación para condiciones térmicas abatidas</h4>
					<div class="row">
						<div class="col-12">
							<div class="texto_metodologia">
								El método que se consideró aplicar para la realización de esta evaluación establece la medición y correlación de la temperatura de bulbo seco y la velocidad del aire para realizar el cálculo el cual determina el índice del viento frío.<br><br>

								Durante la evaluación se mide la temperatura axilar del POE al inicio y al final de cada ciclo, se registran los valores tomando en cuenta la estrategia de evaluación, que en dicho estudio es por puestos fijos. Una vez obtenidos los valores se determina el valor del índice de viento frío con la siguiente fórmula:<br>


								<div class="imagen_formula"><img src="/assets/images/reportes/reportetem_fig_4.3_1.jpg" height="60"></div><br>

								
								Dónde:<br><br>


								I<sub>vf inicial</sub>: es el valor promedio del índice del viento frío inicial.<br>
								I<sub>vf a la mitad</sub>: es el valor promedio del índice del viento frío a la mitad.<br>
								I<sub>vf al final</sub>: es el valor promedio del índice del viento frío final<br><br>

								Con el resultado del índice de viento frío promedio, se determinó el tiempo máximo de exposición del POE de acuerdo con lo establecido en la tabla 2 de límites máximos permisibles de exposición a condiciones térmicas abatidas tomada de la NOM-015-STPS-2001.
							</div>
						</div>
					</div>
				<h4 class="card-title" id="4_3_1">4.3.1.- Límites máximos permisibles de exposición a condiciones térmicas abatidas</h4>
					<div class="row">
						<div class="col-12">
							<div class="texto_metodologia">En la Tabla 2 se relacionan las temperaturas del índice de viento frío, tiempo de exposición máxima diaria y el tiempo de no exposición, en condiciones térmicas abatidas las cuales se tomaron de referencia para la elaboración del presente estudio.</div>
							<div class="informacion_estatica"><br>
								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<td colspan="4"><br><b>TABLA 2<br>Límites máximos permisibles de exposición a condiciones térmicas abatidas.</b><br><br></td>
										</tr>
										<tr>
											<th width="40%">Temperatura en °C</th>
											<th width="60%">Exposición Máxima Diaria</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>De 0 a -18</td>
											<td>8 horas.</td>
										</tr>
										<tr>
											<td>Menores de -18 a -34</td>
											<td>4 horas; sujeto a periodos continuos máximos de exposición de una hora; después de cada exposición, se debe tener un tiempo de no exposición al menos igual al tiempo de exposición.</td>
										</tr>
										<tr>
											<td>Menores de -34 a -57</td>
											<td>1 hora; sujeto a periodos continuos máximos de 30 minutos; después de cada exposición, se debe tener un tiempo de no exposición al menos 8 veces mayor que el tiempo de exposición.</td>
										</tr>
										<tr>
											<td>Menores de -57</td>
											<td>5 minutos.</td>
										</tr>
									</tbody>
									<tfoot>
										<td colspan="4"><br><b>Tabla obtenida de la (NOM-015-STPS-2001)</b><br><br></td>
									</tfoot>
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
										<textarea  class="form-control" style="margin-bottom: 0px;" rows="14" id="reporte_ubicacioninstalacion" name="reporte_ubicacioninstalacion" required></textarea>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_procesoinstalacion" name="reporte_procesoinstalacion" required></textarea>
								</div>
							</div>
							<div class="col-12">								
								<div class="form-group">
									<label style="color: #000000;">Descripción de la actividad principal</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_actividadprincipal" name="reporte_actividadprincipal" required></textarea>
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
							{{-- <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
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
							</table><br><br> --}}
							<p class="justificado">En este apartado se muestra la actividad principal desarrollada en la instalación, involucrando al personal / categoría adscrito en cada área que integran a la <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>.</p>
							{{-- <ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área" id="boton_reporte_nuevaarea">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva área
								</button>
							</ol> --}}
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
							<p class="justificado">A continuación, se describen las actividades realizadas en cada área con exposición a temperaturas extremas elevadas según la categoría encontrada en dicho sitio.</p>
							<div class="informacion_estatica">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_4">
									<thead>
										<tr>
											<th width="60">No.</th>
											<th width="120">Instalación</th>
											<th width="130">Área</th>
											<th width="200">Categoría</th>
											<th width="">Actividades</th>
											<th width="110">Tiempo / Ciclos</th>
											<th width="90">Puesto<br>(Fijo / Móvil)</th>
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
											<th rowspan="2" width="40">No.</th>
											<th rowspan="2" width="130">Instalación</th>
											<th rowspan="2" width="150">Área</th>
											<th rowspan="2" width="">Fuentes emisoras</th>
											<th rowspan="2" width="60">Cantidad</th>
											<th colspan="2" width="">Características del área</th>
											<th colspan="2" width="">Tipo de ventilación</th>
										</tr>
										<tr>
											<th width="80">Abierta</th>
											<th width="80">Cerrada</th>
											<th width="80">Natural</th>
											<th width="80">Artificial</th>
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
							<p class="justificado">Las condiciones de operación durante la evaluación se registran a continuación y se muestra el porcentaje de operación por cada área de trabajo de la instalación.</p>
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
				<h4 class="card-title" id="6_2">6.2.- Método empleado para la evaluación y criterio de selección</h4>
				<h4 class="card-title" id="6_2_1">6.2.1.- Condiciones térmicas elevadas</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">El método utilizado para esta evaluación, se basa en describir las actividades, así como el tiempo y ciclos de exposición que tiene el POE en cada puesto de trabajo, para poder determinando el régimen de trabajo (ligero, moderado o pesado) según la actividad que desarrolla. Estos valores se encuentran referenciados en la tabla A.1 Apéndice A de la NOM-015-STPS-2001. Con el objetivo de identificar el porcentaje del tiempo de exposición y de no exposición del trabajador.</p><br>
							
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2_1">
								<thead>
									<tr>
										<th width="70">No. puntos<br>evaluados</th>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="200">Categoría</th>
										<th width="80">Puesto</th>
										<th width="">Actividades</th>
										<th width="80">Régimen<br>de trabajo</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td><b id="total_puntosarea">0</b></td>
										<th colspan="6">Total de puntos evaluados</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="6_2_2">6.2.2.- Condiciones térmicas abatidas</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">El método de evaluación para condiciones térmicas abatidas, se basa en determinar el índice de viento frío, esto consiste en medir y correlacionar la temperatura del termómetro y la velocidad del aire en base a la tabla A2. Índice de viento frío NOM-015-STPS-2001. Con el objetivo de determinar el aislamiento para proteger el cuerpo del trabajador.<br><br>

							<b>Nota</b>: Para el presente estudio y de acuerdo con el reconocimiento sensorial realizado en las áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>, no se encontraron fuentes generadoras que propiciaran condiciones térmicas abatidas, por lo cual no se evaluó dicho parámetro.
							</p>
						</div>
					</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">En este capítulo se presentan los resultados obtenidos durante la evaluación de temperatura extrema elevadas.</p>
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de medición" id="boton_reporte_nuevopuntomedicion">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de medición
								</button>
							</ol>
							<style type="text/css">
								.tabla_evaluacion th
								{
									padding: 2px 1px!important;
									font-size:0.75vw!important;
									text-align: center;
									vertical-align: middle;
								}

								.tabla_evaluacion td
								{
									padding: 2px 1px!important;
									font-size:0.75vw!important;
									text-align: center;
									vertical-align: middle;
								}
							</style>
							<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_puntos">
								<thead>
									<tr>
										<th width="70">No. de<br>Medición</th>
										<th width="130">Instalación</th>
										<th width="150">Área</th>
										<th width="">Categoría</th>
										<th width="80">Régimen<br>de trabajo</th>
										<th width="120">% exp. / % recup.<br>en cada hora</th>
										<th width="60">Editar</th>
										<th width="60">Eliminar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="7_1">7.1.- Tabla de resultados del agente temperaturas elevadas</h4>
					<div class="row">
						<div class="col-12">
							<table class="table table-hover tabla_info_centrado tabla_evaluacion" width="100%" id="tabla_reporte_7_1">
								<thead>
									<tr>
										<th colspan="11">Tabla de resultados de evaluación en condiciones térmicas elevadas</th>
									</tr>
									<tr>
										<th rowspan="3" width="60">No. de<br>Medición</th>
										<th rowspan="3" width="130">Trabajador<br>o GEH</th>
										<th rowspan="3" width="150">Categoría</th>
										<th rowspan="3" width="130">Áreas</th>
										<th rowspan="3" width="80">Régimen de<br>trabajo</th>
										<th rowspan="3" width="">% exposición /<br>% recuperación<br>en cada hora</th>
										<th colspan="3" width="180">I<sub>tgbh</sub> promedio (°C)</th>
										<th rowspan="3" width="60">LMPE<br>(°C)</th>
										<th rowspan="3" width="80">Cumplimiento<br>normativo</th>
									</tr>
									<tr>
										<th colspan="3" width="180">Periodo</th>
									</tr>
									<tr>
										<th width="60">I</th>
										<th width="60">II</th>
										<th width="60">III</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="7_2">7.2.- Tabla de resultados del agente temperaturas abatidas</h4>
					<div class="row">
						<div class="col-12">
							<div class="texto_metodologia"><b>Nota</b>: Para el presente estudio y de acuerdo con el reconocimiento sensorial realizado en las áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b>, no se encontraron fuentes generadoras que propiciaran condiciones térmicas abatidas, por lo cual no se evaluó dicho parámetro.</div>
						</div>
					</div>
				<h4 class="card-title" id="7_3">7.3.- Matriz de exposición laboral</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">La matriz de exposición laboral contiene un concentrado de los resultados de la evaluación del agente físico temperaturas extremas elevadas.</p>

							<style type="text/css">
								#tabla_reporte_matriz th
								{
									background: #F9F9F9;
									border: 1px #E5E5E5 solid;
									padding: 1px!important;
									font-size:0.6vw!important;
									text-align: center;
									vertical-align: middle;
								}

								#tabla_reporte_matriz td
								{
									padding: 1px!important;
									font-size:0.6vw!important;
									text-align: center;
								}

								#tabla_reporte_matriz tr:hover td
								{
									color: #000000;
								}

								.rotartexto
								{
									-webkit-transform: rotate(-90deg); 
									-moz-transform: rotate(-90deg);
									-o-transform: rotate(-90deg);
									transform: rotate(-90deg);
									filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

									display:-moz-inline-stack;
									display:inline-block;
									zoom:1;
									*display:inline; 
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
											<textarea  class="form-control" style="margin-bottom: 0px;" rows="18" id="reporte_conclusion" name="reporte_conclusion" required></textarea>
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
								#tabla_dashboard
								{
									width: 100%;
									border: 3px #0BACDB solid;
								}

								#tabla_dashboard th
								{
									border: 1px #E9E9E9 solid;
									background: #0BACDB;
									color: #FFFFFF!important;
									padding: 4px;
									font-size:1.1vw!important;
									line-height: 20px;
									margin: 0px;
									text-align: center;
									vertical-align: middle;
								}

								#tabla_dashboard td
								{
									border: 1px #E9E9E9 solid;
									padding: 4px;
									line-height: 1.1;
									margin: 0px;
									vertical-align: middle;
									text-align: center;
									font-size:0.85vw!important;
									color: #555555;
								}

								#tabla_dashboard td.td_top
								{
									vertical-align: top;
								}

								#tabla_dashboard td .icono
								{
									width: 100%;
									font-size:5vw!important;
									margin: 10px 0px;
								}

								#tabla_dashboard td .texto
								{
									font-size:0.9vw!important;
									line-height: 1!important;
									font-weight: bold;
								}

								#tabla_dashboard td .numero
								{
									font-size:1.2vw!important;
									line-height: 1.2!important;
									font-weight: bold;
								}
							</style>

							<div id="div_tabla_dashboard">
								<table class="table" width="100%" id="tabla_dashboard">
									<tbody>
										<tr>
											<th colspan="6">
												<b style="font-size:1.3vw!important; font-weight: 600; color: #000000;">
													Evaluación de Condiciones Térmicas Elevadas en:<br><span class="div_instalacion_nombre">NOMBRE INSTALACION</span>
												</b>
											</th>
										</tr>
										<tr>
											<th colspan="2" width="32%">Total de puntos de medición</th>
											<th colspan="2" width="36%">Cumplimiento normativo</th>
											<th colspan="2" width="32%">Recomendaciones emitidas</th>
										</tr>
										<tr>
											<td colspan="2" height="180">
												<i class="fa fa-thermometer-half text-success" style="font-size: 6vw!important;" id="dashboard_puntos"> 0</i><br>
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
											<th colspan="3" width="50%">Categorías evaluadas (<span style="color: #FF0000; font-weight: normal;">● Criticas</span>)<br><i class="fa fa-male fa-3x"></i></th>
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
											<td colspan="6" style="font-style: italic; font-size: 11px!important;">Análisis derivado del informe de resultados de la evaluación condiciones térmicas elevadas o abatidas – condiciones de seguridad e higiene NOM-015-STPS-2001</td>
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
									#tabla_reporte_recomendaciones td.alinear_izquierda
									{
										text-align: left;
									}

									#tabla_reporte_recomendaciones td label
									{
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
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Las <span id="reporte_memoriafotografica_lista">0</span> fotos encontradas se agregaran en la impresión del informe de temperatura.</p>
						</div>
					</div>
				<h4 class="card-title" id="11_2">11.2.-	Anexo 2: Planos de ubicación de las fuentes generadoras y puntos evaluados</h4>
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
				<h4 class="card-title" id="11_3">11.3.-	Anexo 3: Memoria de cálculo</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> La memoria de cálculo debe seleccionarlo en el punto "11.6.- Anexo 6: Informe de resultados".</p>
						</div>
					</div>
				<h4 class="card-title" id="11_4">11.4.-	Anexo 4: Equipo utilizado en la medición</h4>
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
											<th width="60">Certificado</th>
											<th width="60">Aplica Carta</th>
											<th width="120">Carta PDF</th>
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
				<h4 class="card-title" id="11_5">11.5.-	Anexo 5: Copia de certificados o aviso de calibración del equipo</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Los certificados de los equipos utilizados seleccionados en el punto "11.4.- Anexo 4: Equipo utilizado en la medición" se adjuntará en la impresión del reporte en formato PDF.</p>
						</div>
					</div>
				<h4 class="card-title" id="11_6">11.6.-	Anexo 6: Informe de resultados</h4>
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
				<h4 class="card-title" id="11_7">11.7.-	Anexo 7: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 7", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
						</div>
					</div>
				<h4 class="card-title" id="11_8">11.8.-	Anexo 8: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 8", debe elegirlo en la tabla del punto 12 El cual se adjuntará en la impresión del informe en formato PDF.</p>
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
	#modal_reporte_definicion>.modal-dialog{
		min-width: 900px!important;
	}

	#modal_reporte_definicion .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_definicion .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
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
	#modal_reporte_categoria>.modal-dialog{
		min-width: 800px!important;
	}

	#modal_reporte_categoria .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_categoria .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
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
								<input type="text" class="form-control" id="reportecategoria_nombre" name="reportecategoria_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" class="form-control" id="reportecategoria_total" name="reportecategoria_total" required>
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
	#modal_reporte_area>.modal-dialog{
		min-width: 1100px!important;
	}

	#modal_reporte_area .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_area .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
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
						<div class="col-3">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" min="1" class="form-control" id="reportearea_orden" name="reportearea_orden" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reportetemperaturaarea_porcientooperacion" name="reportetemperaturaarea_porcientooperacion" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Características del área</label>
								<select class="custom-select form-control" id="reportearea_caracteristicaarea" name="reportearea_caracteristicaarea" required>
									<option value=""></option>
									<option value="Abierta">Abierta</option>
									<option value="Cerrada">Cerrada</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Tipo de ventilación</label>
								<select class="custom-select form-control" id="reportearea_tipoventilacion" name="reportearea_tipoventilacion" required>
									<option value=""></option>
									<option value="Natural">Natural</option>
									<option value="Artificial">Artificial</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Categorías en el área</ol>
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
							<div style="margin: 6px 0px 0px 0px!important; padding: 0px!important; max-height: 180dpx; overflow-y: auto; overflow-x: hidden;" id="div_tabla_areamaquinaria">
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
	#modal_reporte_puntomedicion>.modal-dialog{
		min-width: 800px!important;
	}

	#modal_reporte_puntomedicion .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_puntomedicion .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
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
							<input type="hidden" class="form-control" id="reportetemperaturaevaluacion_id" name="reportetemperaturaevaluacion_id" value="0">
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Punto de medición</label>
								<input type="number" min="1" maxlength="6" class="form-control" id="reportetemperaturaevaluacion_punto" name="reportetemperaturaevaluacion_punto" required>
							</div>
						</div>
						<div class="col-9">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="reportetemperaturaarea_id" name="reportearea_id" onchange="reportetemperaturaevaluacioncategorias(this.value, 0);" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Categoría</label>
								<select class="custom-select form-control" id="reportetemperaturacategoria_id" name="reportecategoria_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-9">
							<div class="form-group">
								<label>Nombre del trabajador</label>
								<input type="text" class="form-control" id="reportetemperaturaevaluacion_trabajador" name="reportetemperaturaevaluacion_trabajador" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Ficha</label>
								<input type="text" class="form-control" id="reportetemperaturaevaluacion_ficha" name="reportetemperaturaevaluacion_ficha" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Puesto</label>
								<select class="custom-select form-control" id="reportetemperaturaevaluacion_puesto" name="reportetemperaturaevaluacion_puesto" required>
									<option value=""></option>
									<option value="Fijo">Fijo</option>
									<option value="Móvil">Móvil</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Tiempo de exposición (minutos)</label>
								<input type="number" min="1" class="form-control" id="reportetemperaturaevaluacion_tiempo" name="reportetemperaturaevaluacion_tiempo" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Ciclos de exposición (numero)</label>
								<input type="number" min="1" class="form-control" id="reportetemperaturaevaluacion_ciclos" name="reportetemperaturaevaluacion_ciclos" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Régimen trabajo</label>
								<select class="custom-select form-control" id="reportetemperaturaevaluacion_regimen" name="reportetemperaturaevaluacion_regimen" onchange="obtener_LMPE(this.value, form_modal_puntomedicion.reportetemperaturaevaluacion_porcentaje.value);" required>
									<option value=""></option>
									<option value="1">Ligero</option>
									<option value="2">Moderado</option>
									<option value="3">Pesado</option>
								</select>
							</div>
						</div>
						<div class="col-9">
							<div class="form-group">
								<label>% exposición / % recuperación en cada hora</label>
								<select class="custom-select form-control" id="reportetemperaturaevaluacion_porcentaje" name="reportetemperaturaevaluacion_porcentaje" onchange="obtener_LMPE(form_modal_puntomedicion.reportetemperaturaevaluacion_regimen.value, this.value);" required>
									<option value=""></option>
									<option value="1">100 % de exposición</option>
									<option value="2">75 % de exposición y 25 % de recuperación en cada hora</option>
									<option value="3">50 % de exposición y 50 % de recuperación en cada hora</option>
									<option value="4">25 % de exposición y 75 % de recuperación en cada hora</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 4px; margin: 0px 0px 10px 0px; text-align: center;">Resultados I<sub>tgbh</sub> promedio (°C)</ol>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Periodo I</label>
								<input type="number" step="any" class="form-control" id="reportetemperaturaevaluacion_I" name="reportetemperaturaevaluacion_I" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Periodo II</label>
								<input type="number" step="any" class="form-control" id="reportetemperaturaevaluacion_II" name="reportetemperaturaevaluacion_II" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Periodo III</label>
								<input type="number" step="any" class="form-control" id="reportetemperaturaevaluacion_III" name="reportetemperaturaevaluacion_III" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>LMPE (°C)</label>
								<input type="number" step="any" class="form-control" id="reportetemperaturaevaluacion_LMPE" name="reportetemperaturaevaluacion_LMPE" readonly>
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
<!-- MODAL-REPORTE-CANCELACION OBSERVACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_cancelacionobservacion>.modal-dialog{
		min-width: 800px!important;
	}

	#modal_reporte_cancelacionobservacion .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_cancelacionobservacion .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
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
<script src="/js_sitio/reportes/reportetemperatura.js"></script>