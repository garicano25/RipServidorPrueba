
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
				<a href="#4_2" class="list-group-item submenu">4.2.- Método de Muestreo <i class="fa fa-times" id="menureporte_4_2"></i></a>
				<a href="#4_3" class="list-group-item submenu">4.3.- Método de evaluación <i class="fa fa-times" id="menureporte_4_3"></i></a>
				<a href="#4_3_1" class="list-group-item subsubmenu">4.3.1.- Límites permisibles de calidad del agua para uso y consumo humano <i class="fa fa-times" id="menureporte_4_3_1"></i></a>
				<a href="#5" class="list-group-item">5.- Reconocimiento</a>
				<a href="#5_1" class="list-group-item submenu">5.1.- Ubicación de la instalación <i class="fa fa-times" id="menureporte_5_1"></i></a>
				<a href="#5_2" class="list-group-item submenu">5.2.- Descripción del proceso en la instalación <i class="fa fa-times" id="menureporte_5_2"></i></a>
				<a href="#5_3" class="list-group-item submenu">5.3.- Descripción del proceso de elaboración del producto terminado en la instalación <i class="fa fa-times" id="menureporte_5_3"></i></a>
				<a href="#5_4" class="list-group-item submenu">5.4.- Descripción de los riesgos biológicos <i class="fa fa-times" id="menureporte_5_4"></i></a>
				<a href="#5_5" class="list-group-item submenu">5.5.- Descripción del manejo del agua en la instalación <i class="fa fa-times" id="menureporte_5_5"></i></a>
				<a href="#6" class="list-group-item">6.- Evaluación</a>
				<a href="#6_1" class="list-group-item submenu">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje) <i class="fa fa-times" id="menureporte_6_1"></i></a>
				<a href="#6_2" class="list-group-item submenu">6.2.- Método de evaluación <i class="fa fa-times" id="menureporte_6_2"></i></a>
				<a href="#7" class="list-group-item">7.- Resultados</a>
				<a href="#7_1" class="list-group-item submenu">7.1.- Tabla de resultados <i class="fa fa-times" id="menureporte_7_1"></i></a>
				<a href="#7_2" class="list-group-item submenu">7.2.- Análisis de los resultados <i class="fa fa-times" id="menureporte_7_2"></i></a>
				<a href="#7_3" class="list-group-item submenu">7.3.- Análisis del manejo, recipientes y dispensadores de agua en la instalación <i class="fa fa-times" id="menureporte_7_3"></i></a>
				<a href="#7_4" class="list-group-item submenu">7.4.- Matriz de exposición laboral <i class="fa fa-times" id="menureporte_7_4"></i></a>
				<a href="#8" class="list-group-item">8.- Conclusiones <i class="fa fa-times" id="menureporte_8"></i></a>
				<a href="#9" class="list-group-item">9.- Recomendaciones de control <i class="fa fa-times" id="menureporte_9"></i></a>
				<a href="#10" class="list-group-item">10.- Equipo y material utilizado <i class="fa fa-times" id="menureporte_10"></i></a>
				<a href="#11" class="list-group-item">11.- Responsables del informe <i class="fa fa-times" id="menureporte_11"></i></a>
				<a href="#12" class="list-group-item">12.- Anexos</a>
				<a href="#12_1" class="list-group-item submenu">12.1.- Anexo 1: Memoria fotográfica <i class="fa fa-times" id="menureporte_12_1"></i></a>
				<a href="#12_2" class="list-group-item submenu">12.2.- Anexo 2: Planos de ubicación de los puntos de muestreo <i class="fa fa-times" id="menureporte_12_2"></i></a>
				<a href="#12_3" class="list-group-item submenu">12.3.- Anexo 3: Informe de resultados del laboratorio <i class="fa fa-times" id="menureporte_12_3"></i></a>
				<a href="#12_4" class="list-group-item submenu">12.4.- Anexo 4: Copia de aprobación del laboratorio de ensayo ante la STPS <i class="fa fa-times" id="menureporte_12_4"></i></a>
				<a href="#12_5" class="list-group-item submenu">12.5.- Anexo 5: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema) <i class="fa fa-times" id="menureporte_12_5"></i></a>
				<a href="#13" class="list-group-item">13.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</a>
				<a href="#14" class="list-group-item submenu" id="menu_opcion_final">Generar informe <i class="fa fa-download text-success" id="menureporte_14"></i></a>
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
									<label style="color: #000000;">Fisicoquímico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_introduccion" name="reporte_introduccion" required></textarea>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label style="color: #000000;">Microbiológico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_introduccion2" name="reporte_introduccion2" required></textarea>
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
									<label style="color: #000000;">Fisicoquímico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivoespecifico" name="reporte_objetivoespecifico" required></textarea>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label style="color: #000000;">Microbiológico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_objetivoespecifico2" name="reporte_objetivoespecifico2" required></textarea>
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
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_metodologia_4_1" name="reporte_metodologia_4_1" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_1">Guardar metodología punto 4.1 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="4_2">4.2.- Método de Muestreo</h4>
					<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_2" id="form_reporte_metodologia_4_2">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									{!! csrf_field() !!}
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="18" id="reporte_metodologia_4_2" name="reporte_metodologia_4_2" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_2">Guardar metodología punto 4.2 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="4_3">4.3.- Método de evaluación</h4>
					<form method="post" enctype="multipart/form-data" name="form_reporte_metodologia_4_3" id="form_reporte_metodologia_4_3">
						<div class="row">
							<div class="col-12">
								{!! csrf_field() !!}
							</div>
							<div class="col-12">
								<div class="form-group">
									<label style="color: #000000;">Fisicoquímico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_metodologia_4_3" name="reporte_metodologia_4_3" required></textarea>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label style="color: #000000;">Microbiológico</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_metodologia_4_32" name="reporte_metodologia_4_32" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_metodologia_4_3">Guardar metodología punto 4.3 <i class="fa fa-save"></i></button>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="4_3_1">4.3.1.- Límites permisibles de calidad del agua para uso y consumo humano</h4>
					<div class="row">
						<style type="text/css">
							.texto_metodologia
							{
								font-size:0.7vw!important;
								text-align: justify;
							}

							.tabla_metodologia th
							{
								background: #F9F9F9;
								border: 1px #E5E5E5 solid!important;
								padding: 1px!important;
								font-size:0.7vw!important;
								text-align: center;
								vertical-align: middle;
							}

							.tabla_metodologia td
							{
								padding: 1px!important;
								font-size:0.7vw!important;
								text-align: center;
								border: 1px #E5E5E5 solid!important;
							}

							.tabla_metodologia tr:hover td
							{
								color: #000000;
							}
						</style>
						<div class="col-6">
							<label style="color: #000000;">Fisicoquímico</label>
							<div class="informacion_estatica"><br>
								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<th width="50%" colspan="2">Tabla 2<br>Límites Permisibles de Características Físicas y Organolépticas</th>
										</tr>
										<tr>
											<th width="50%">Característica</th>
											<th width="50%">Límite permisible mg/L</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Color</td>
											<td style="text-align: justify!important;">20 unidades de color verdadero en la escala de platino-cobalto.</td>
										</tr>
										<tr>
											<td>Olor y Sabor</td>
											<td style="text-align: justify!important;">Agradable (se aceptarán aquellos que sean tolerables para la mayoría de los consumidores, siempre que no sean resultados de condiciones objetables desde el punto de vista biológico o químico).</td>
										</tr>
										<tr>
											<td>Turbiedad</td>
											<td style="text-align: justify!important;">5 unidades de turbiedad nefelométricas (UTN) o su equivalente en otro método.</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-127-SSA1-1994.</td>
										</tr>
									</tfoot>
								</table>
								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<th width="50%" colspan="2">Tabla 3<br>Límites permisibles de características químicas</th>
										</tr>
										<tr>
											<th width="50%">Característica</th>
											<th width="50%">Límite permisible mg/L</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Aluminio</td>
											<td>0,20</td>
										</tr>
										<tr>
											<td>Arsénico (Nota 2)</td>
											<td>0,05</td>
										</tr>
										<tr>
											<td>Bario</td>
											<td>0,70</td>
										</tr>
										<tr>
											<td>Cadmio</td>
											<td>0,005</td>
										</tr>
										<tr>
											<td>Cianuros (como CN-)</td>
											<td>0,07</td>
										</tr>
										<tr>
											<td>Cloro residual libre</td>
											<td>0,2-1,50</td>
										</tr>
										<tr>
											<td>Cloruros (como Cl-)</td>
											<td>250,00</td>
										</tr>
										<tr>
											<td>Cobre</td>
											<td>2,00</td>
										</tr>
										<tr>
											<td>Cromo total</td>
											<td>0,05</td>
										</tr>
										<tr>
											<td>Dureza total (como CaCO3)</td>
											<td>500,00</td>
										</tr>
										<tr>
											<td>Fenoles o compuestos fenólicos</td>
											<td>0,3</td>
										</tr>
										<tr>
											<td>Fierro</td>
											<td>0,30</td>
										</tr>
										<tr>
											<td>Fluoruros (como F-)</td>
											<td>1,50</td>
										</tr>
										<tr>
											<td>Hidrocarburos aromáticos en microgramos/l:</td>
											<td>-</td>
										</tr>
										<tr>
											<td>Benceno</td>
											<td>10,00</td>
										</tr>
										<tr>
											<td>Etilbenceno</td>
											<td>300,00</td>
										</tr>
										<tr>
											<td>Tolueno</td>
											<td>700,00</td>
										</tr>
										<tr>
											<td>Xileno (tres isómeros)</td>
											<td>500,00</td>
										</tr>
										<tr>
											<td>Manganeso</td>
											<td>0,15</td>
										</tr>
										<tr>
											<td>Mercurio</td>
											<td>0,001</td>
										</tr>
										<tr>
											<td>Nitratos (como N)</td>
											<td>10,00</td>
										</tr>
										<tr>
											<td>Nitritos (como N)</td>
											<td>1,00</td>
										</tr>
										<tr>
											<td>Nitrógeno amoniacal (como N)</td>
											<td>0,50</td>
										</tr>
										<tr>
											<td>pH (potencial de hidrógeno) en unidades de pH</td>
											<td>6,5-8,5</td>
										</tr>
										<tr>
											<td>Plaguicidas en microgramos/l:</td>
											<td>-</td>
										</tr>
										<tr>
											<td>Aldrín y dieldrín (separados o combinados)</td>
											<td>0,03</td>
										</tr>
										<tr>
											<td>Clordano (total de isómeros)</td>
											<td>0,20</td>
										</tr>
										<tr>
											<td>DDT (total de isómeros)</td>
											<td>1,00</td>
										</tr>
										<tr>
											<td>Gamma-HCH (lindano)</td>
											<td>2,00</td>
										</tr>
										<tr>
											<td>Hexaclorobenceno</td>
											<td>1,00</td>
										</tr>
										<tr>
											<td>Heptacloro y epóxido de heptacloro</td>
											<td>0,03</td>
										</tr>
										<tr>
											<td>Metoxicloro</td>
											<td>20,00</td>
										</tr>
										<tr>
											<td>2,4 – D</td>
											<td>30,00</td>
										</tr>
										<tr>
											<td>Plomo</td>
											<td>0,01</td>
										</tr>
										<tr>
											<td>Sodio</td>
											<td>200,00</td>
										</tr>
										<tr>
											<td>Sólidos disueltos totales</td>
											<td>1000,00</td>
										</tr>
										<tr>
											<td>Sulfatos (como SO4=)</td>
											<td>400,00</td>
										</tr>
										<tr>
											<td>Sustancias activas al azul de metileno (SAAM)</td>
											<td>0,50</td>
										</tr>
										<tr>
											<td>Trihalometanos totales</td>
											<td>0,2-0,5</td>
										</tr>
										<tr>
											<td>Zinc</td>
											<td>5,00</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-127-SSA1-1994.</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="col-6">
							<label style="color: #000000;">Microbiológico</label>
							<div class="informacion_estatica"><br>
								<div class="texto_metodologia">El contenido de organismos resultante del examen de una muestra simple de agua, debe ajustarse a lo establecido en las siguientes tablas.</div><br>
								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<th width="50%" colspan="2">Tabla 1<br>Límites permisibles de calidad del agua</th>
										</tr>
										<tr>
											<th width="50%">Característica</th>
											<th width="50%">Límite permisible</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Organismos coliformes totales</td>
											<td>Ausencia o no detectables</td>
										</tr>
										<tr>
											<td>Organismos coliformes fecales</td>
											<td>Ausencia o no detectables</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-127-SSA1-1994.</td>
										</tr>
									</tfoot>
								</table>

								<table class="table tabla_metodologia" width="100%">
									<thead>
										<tr>
											<th width="50%" colspan="2">Especificación sanitaria microbiológica</th>
										</tr>
										<tr>
											<th width="50%">Característica</th>
											<th width="50%">Límite permisible</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Mesofílicos aerobios</td>
											<td>100 UFC/100 mL</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2">Tabla obtenida de la NOM-041-SSA1-1993</td>
										</tr>
									</tfoot>
								</table>
								<div class="texto_metodologia">Los resultados de los exámenes bacteriológicos se deben reportar en unidades de NMP/100 ml (número más probable por 100 ml), si se utiliza la técnica del número más probable o UFC/100 ml (unidades formadoras de colonias por 100 ml), si se utiliza la técnica de filtración por membrana.</div>
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
								<div class="form-group">
									<label>Descripción del proceso en la instalación</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="20" id="reporte_procesoinstalacion" name="reporte_procesoinstalacion" required></textarea>
								</div>
								<div class="form-group">
									<label>Descripción de la actividad principal de la instalación</label>
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="7" id="reporte_actividadprincipal" name="reporte_actividadprincipal" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<div class="form-group">
									<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_procesoinstalacion">Guardar proceso instalación <i class="fa fa-save"></i></button>
								</div>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="5_3">5.3.- Descripción del proceso de elaboración del producto terminado en la instalación</h4>
					<form method="post" enctype="multipart/form-data" name="form_reporte_procesoelaboracion" id="form_reporte_procesoelaboracion">
						<div class="row">
							<div class="col-12">
								{!! csrf_field() !!}
							</div>
							<div class="col-12">
								<div class="form-group">
									<textarea  class="form-control" style="margin-bottom: 0px;" rows="8" id="reporte_procesoelaboracion" name="reporte_procesoelaboracion" required></textarea>
								</div>
							</div>
							<div class="col-12" style="text-align: right;">
								<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_procesoelaboracion">Guardar proceso elaboración <i class="fa fa-save"></i></button>
							</div>
						</div>
					</form>
				<h4 class="card-title" id="5_4">5.4.- Descripción de los riesgos biológicos</h4>
					<div class="row">
						<div class="col-12">	
							<p class="justificado">A continuación, se muestran los riesgos biológicos (enfermedades) que pueden provocar cada uno de los microorganismos enlistados:</p><br>
							<table class="table tabla_info_centrado" width="100%">
								<thead>
									<tr>
										<td width="50%" colspan="2"><b>Principales microorganismos transmitidos por el agua</b></td>
									</tr>
									<tr>
										<th width="50%">Microorganismo</th>
										<th width="50%">Enfermedad</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th colspan="2">Protozoos</th>
									</tr>
									<tr>
										<td>Giardia lamblia</td>
										<td>Giardiasis</td>
									</tr>
									<tr>
										<td>Entamoeba hystolitica</td>
										<td>Disentería amebiana</td>
									</tr>
									<tr>
										<td>Balantidium coli</td>
										<td>Balantidiosis</td>
									</tr>
									<tr>
										<td>Crytosporidium parvum</td>
										<td>Criptospordiasis</td>
									</tr>
									<tr>
										<td>Cyclospora cayetanensis</td>
										<td>Trastornos intestinales</td>
									</tr>
									<tr>
										<td>Microsporidia</td>
										<td>Diarrea</td>
									</tr>
									<tr>
										<th colspan="2">Bacterias</th>
									</tr>
									<tr>
										<td>Salmonella Typhi</td>
										<td>Fiebre tifoidea</td>
									</tr>
									<tr>
										<td>Salmonella spp.</td>
										<td>Salmonelosis</td>
									</tr>
									<tr>
										<td>Shigella spp.</td>
										<td>Shigellosis</td>
									</tr>
									<tr>
										<td>Campylobacter jejuni</td>
										<td>Gastroenteritis</td>
									</tr>
									<tr>
										<td>Helicobacter pylori</td>
										<td>Gastroenteritis, úlcera gástrica</td>
									</tr>
									<tr>
										<td>Escherichia coli</td>
										<td>Gastroenteritis</td>
									</tr>
									<tr>
										<td>Vibrio cholerae</td>
										<td>Cólera</td>
									</tr>
									<tr>
										<td>Legionella pneumophila</td>
										<td>Legionelosis / Fiebre de Pontiac</td>
									</tr>
									<tr>
										<td>Yersinia enterocolitica</td>
										<td>Yersiniosis</td>
									</tr>
									<tr>
										<td>Leptospira spp.</td>
										<td>Leptospirosis</td>
									</tr>
									<tr>
										<th colspan="2">Virus</th>
									</tr>
									<tr>
										<td>Virus de la hepatitis A y E</td>
										<td>Hepatitis infecciosa</td>
									</tr>
									<tr>
										<td>Rotavirus</td>
										<td>Gastroenteritis</td>
									</tr>
									<tr>
										<td>Enterovirus</td>
										<td>Gastroenteritis, meningitis</td>
									</tr>
									<tr>
										<td>Parvovirus</td>
										<td>Gastroenteritis</td>
									</tr>
									<tr>
										<td>Adenovirus</td>
										<td>Infecciones respiratorias, gastroenteritis</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"><b>Tabla obtenida de González González, María Isabel, & Chiroles Rubalcaba, Sergio. (2011).</b></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="5_5">5.5.- Descripción del manejo del agua en la instalación</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">Durante el recorrido dentro de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b> se hizo un reconocimiento con el fin de saber la descripción del manejo del agua. El cual se presenta en la siguiente tabla:</p>
							<div class="informacion_estatica">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_5_5">
									<thead>
										<tr>
											<th width="80">Punto de<br>medición</th>
											<th width="150">Instalación</th>
											<th width="180">Área</th>
											<th width="200">Tipo<br>(Para uso / consumo humano)</th>
											<th width="">Descripción</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				<h4 class="card-title" id="6">6.- Evaluación</h4>
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
										<th width="180">Instalación</th>
										<th width="">Área</th>
										<th width="120">Porcentaje<br>operación</th>
										<th width="60">Editar</th>
										<th width="60">Eliminar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="6_1">6.1.- Condiciones de operación durante la evaluación (representado en porcentaje)</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">Las condiciones de operación que se encontraron en las diversas áreas de la instalación <b class="div_instalacion_nombre" style="color: #000000;">NOMBRE INSTALACION</b> se presentan por porcentaje en la siguiente tabla:</p>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_1">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th width="180">Instalación</th>
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
							<p class="justificado">En la siguiente tabla se indican las áreas con sus respectivos parámetros, métodos y sus límites permisibles:</p>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_6_2">
								<thead>
									<tr>
										<th width="150">Instalación</th>
										<th width="200">Área</th>
										<th width="180">Parámetro</th>
										<th width="">Método</th>
										<th width="120">Límite permisible</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<p class="justificado">Nota: La toma de muestras se realizó por un signatario acreditado en muestreo de agua el cual aparece dentro de esta acreditación bajo este carácter. Además, para el presente apartado cabe mencionar que los métodos de carácter analítico, son realizados por el signatario autorizado para el análisis de laboratorio que aparece referenciado en el informe de resultados y se encuentra para consulta en el registro del laboratorio ante la entidad mexicana de acreditación (ema).</p>
						</div>
					</div>
				<h4 class="card-title" id="7">7.- Resultados</h4>
				<h4 class="card-title" id="7_1">7.1.- Tabla de resultados</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">En la siguiente tabla se indican las áreas con sus respectivos parámetros, métodos y sus límites permisibles:</p>
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo punto de medición" id="boton_reporte_nuevopuntomedicion">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Punto de medición
								</button>
							</ol>
							<style type="text/css">
								#tabla_reporte_resultados th
								{
									background: #F9F9F9;
									border: 1px #E5E5E5 solid;
									padding: 1px!important;
									font-size:0.5vw!important;
									text-align: center;
									vertical-align: middle;
								}

								#tabla_reporte_resultados td
								{
									padding: 1px!important;
									font-size:0.5vw!important;
									text-align: center;
								}

								#tabla_reporte_resultados tr:hover td
								{
									color: #000000;
								}
							</style>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_resultados">
								<thead>
									<tr>
										<th>Punto de<br>Medición</th>
										<th>Tipo<br>evaluación</th>
										<th>Fecha</th>
										<th>Instalación</th>
										<th>Área</th>
										<th>Ubicación</th>
										<th>Suministro</th>
										<th>Parámetro</th>
										<th>Unidades</th>
										<th>Método de<br>análisis</th>
										<th>No.<br>trabajadores</th>
										<th>Concentración<br>obtenida</th>
										<th>Concentración<br>permisible</th>
										<th>Cumplimiento<br>Normativo</th>
										<th width="60">Editar</th>
										<th width="60">Eliminar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="7_2">7.2.- Análisis de los resultados</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">Durante la evaluación se obtuvieron los siguientes resultados:</p>
							<style type="text/css">
								#tabla_reporte_analisisresultados th
								{
									background: #F9F9F9;
									border: 1px #E5E5E5 solid;
									padding: 1px!important;
									font-size:0.6vw!important;
									text-align: center;
									vertical-align: middle;
								}

								#tabla_reporte_analisisresultados td
								{
									padding: 1px!important;
									font-size:0.6vw!important;
									text-align: center;
								}

								#tabla_reporte_analisisresultados tr:hover td
								{
									color: #000000;
								}
							</style>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_analisisresultados">
								<thead>
									<tr>
										<th width="70">Punto de<br>Medición</th>
										<th width="110">Tipo<br>evaluación</th>
										<th width="170">Parámetro</th>
										<th width="">Ubicación</th>
										<th width="120">Concentración<br>obtenida</th>
										<th width="120">Concentración<br>permisible</th>
										<th width="110">Cumplimiento<br>Normativo</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				<h4 class="card-title" id="7_3">7.3.- Análisis del manejo, recipientes y dispensadores de agua en la instalación</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">En la siguiente tabla se presenta las áreas, punto de localización, equipo de suministro (recipientes y/o dispensadores) así como una descripción de las condiciones del medio por punto de muestreo y el número de puntos evaluados:</p>
							<div class="informacion_estatica">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_7_3">
									<thead>
										<tr>
											<th width="80">Punto de<br>medición</th>
											<th width="150">Instalación</th>
											<th width="180">Área</th>
											<th width="150">Punto de<br>localización</th>
											<th width="150">Equipo de<br>suministro</th>
											<th width="">Descripción de las<br>condiciones del medio</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				<h4 class="card-title" id="7_4">7.4.- Matriz de exposición laboral</h4>
					<div class="row">
						<div class="col-12">
							<p class="justificado">A continuación, se muestra un concentrado de los valores obtenidos en los diversos parámetros evaluados para determinar la calidad del agua respecto a los agentes biológicos:</p>
							<style type="text/css">
								#tabla_reporte_matriz th
								{
									background: #F9F9F9;
									border: 1px #E5E5E5 solid;
									padding: 1px!important;
									font-size:0.55vw!important;
									text-align: center;
									vertical-align: middle;
								}

								#tabla_reporte_matriz td
								{
									padding: 1px!important;
									font-size:0.55vw!important;
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
										{!! csrf_field() !!}
										<div class="form-group">
											<label style="color: #000000;">Fisicoquímico</label>
											<textarea  class="form-control" style="margin-bottom: 0px;" rows="16" id="reporte_conclusion" name="reporte_conclusion" required></textarea>
										</div>
									</div>
									<div class="col-12">
										{!! csrf_field() !!}
										<div class="form-group">
											<label style="color: #000000;">Microbiológico</label>
											<textarea  class="form-control" style="margin-bottom: 0px;" rows="16" id="reporte_conclusion2" name="reporte_conclusion2" required></textarea>
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
									border: 3px #0BACDB solid;
								}

								#tabla_dashboard th
								{
									border: 1px #E9E9E9 solid;
									background: #0BACDB;
									color: #FFFFFF!important;
									padding: 4px;
									font-size:1vw!important;
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
									font-size:0.9vw!important;
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
											<th colspan="4">
												<b style="font-size:1.1vw!important; font-weight: 600; color: #000000;">
													Evaluación de Calidad de Agua (<span id="dashboard_titulo">tipo</span>) para Uso y Consumo Humano en:<br><span class="div_instalacion_nombre">NOMBRE INSTALACION</span>.
												</b>
											</th>
										</tr>
										<tr>
											<th width="30%" colspan="2">Áreas evaluadas</th>
											<th width="70%" colspan="2">Cumplimiento normativo en calidad del agua</th>
										</tr>
										<tr>
											<td width="30%" colspan="2" style="height: 240px;">
												<span id="dashboard_areas">areas</span>
											</td>
											<td width="70%" colspan="2">
												<span id="dashboard_cumplimiento">gráfica</span>
											</td>
										</tr>
										<tr>
											<th width="25%">Recomendaciones emitidas</th>
											<th width="50%" colspan="2">Total de Puntos evaluados</th>
											<th width="25%">Parámetros analizados</th>
										</tr>
										<tr>
											<td width="25%">
												<i class="fa fa-pencil-square-o text-info" style="font-size: 90px!important;" id="dashboard_recomendaciones">0</i><br>
												<span class="texto">Recomendaciones</span>
											</td>
											<td width="50%" colspan="2">
												<div style="border: 0px #000 solid; width: 232px; margin: 0px auto;">
													<div style="position: absolute; width: 232px; margin-top: 155px; text-align: center; border: 0px #F39C12 solid;">
														<span style="color: #FFFFFF; font-size: 40px;" id="dashboard_puntos_total">0</span>
													</div>
													<img src="/assets/images/reportes/dashboard_agua1.png" height="240">
												</div>
											</td>
											<td width="25%">
												<span id="dashboard_parametros">parametros</span>
											</td>
										</tr>
										<tr>
											<td colspan="4">Análisis derivado del resultado del informe de laboratorio</td>
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
				<h4 class="card-title" id="10">10.- Equipo y material utilizado</h4>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb" style="padding: 6px; margin: 10px 0px;">
								<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nuevo material utilizado" id="boton_reporte_materialutilizado">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Material utilizado
								</button>
							</ol>
							<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_reporte_10">
								<thead>
									<tr>
										<th width="60">No.</th>
										<th>Equipo y material utilizado para evaluar agua</th>
										<th width="60">Editar</th>
										<th width="60">Eliminar</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
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
				<h4 class="card-title" id="12">12.- Anexos</h4>
				<h4 class="card-title" id="12_1">12.1.- Anexo 1: Memoria fotográfica</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> Las fotos encontradas se agregaran en la impresión del informe de agua.<br><br><span id="reporte_memoriafotografica_lista">● Lista</span></p>
						</div>
					</div>
				<h4 class="card-title" id="12_2">12.2.-	Anexo 2: Planos de ubicación de los puntos de muestreo</h4>
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
											<th width="160">Tipo evaluación</th>
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
				<h4 class="card-title" id="12_3">12.3.-	Anexo 3: Informe de resultados del laboratorio</h4>
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
											<th width="100">Tipo evaluación</th>
											<th width="160">Fecha carga</th>
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
				<h4 class="card-title" id="12_4">11.4.-	Anexo 4: Copia de aprobación del laboratorio de ensayo ante la STPS</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 4", debe elegirlo en la tabla del punto 13 El cual se adjuntará en la impresión del informe en formato PDF.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
							<p class="justificado">Para lo solicitado en el presente apartado cabe mencionar que la aprobación del laboratorio de ensayo por parte de la STPS no aplica para las evaluaciones de calidad del agua, debido a que la normatividad pertenece a otro sector y/o dependencia gubernamental (Secretaría de Salud).</p>
						</div>
					</div>
				<h4 class="card-title" id="12_5">11.5.-	Anexo 5: Copia del registro del laboratorio ante la entidad mexicana de acreditación (ema)</h4>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<p class="justificado"><b style="color: #333333; font-weight: bold;">Nota del software:</b> El Anexo "Anexo 5", debe elegirlo en la tabla del punto 13 El cual se adjuntará en la impresión del informe en formato PDF.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12" style="padding-top: 10px;">
							<b style="color: #333333; font-weight: bold;">Nota aclaratoria</b><br>
							<p class="justificado">La toma de muestras se realizó por un signatario acreditado en muestreo de agua el cual aparece dentro de esta acreditación bajo este carácter. Además, para el presente apartado cabe mencionar que los métodos de carácter analítico, son realizados por el signatario autorizado para el análisis de laboratorio que aparece referenciado en el informe de resultados y se encuentra para consulta en el registro del laboratorio ante la entidad mexicana de acreditación (ema).</p>
						</div>
					</div>
				<h4 class="card-title" id="13">13.- Seleccionar Anexos 8 (STPS) y 9 (EMA)</h4>
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
								<input type="text" class="form-control" id="reporteaguacategoria_nombre" name="reporteaguacategoria_nombre" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" class="form-control" id="reporteaguacategoria_total" name="reporteaguacategoria_total" required>
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
		/*min-width: 90%!important;*/
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
						<div class="col-9">
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reporteaguaarea_instalacion" name="reporteaguaarea_instalacion" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Área No. orden</label>
								<input type="number" class="form-control" id="reporteaguaarea_numorden" name="reporteaguaarea_numorden" required>
							</div>
						</div>
						<div class="col-9">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reporteaguaarea_nombre" name="reporteaguaarea_nombre" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reporteaguaarea_porcientooperacion" name="reporteaguaarea_porcientooperacion" required>
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
<!-- MODAL-REPORTE-AREA EVALUACION -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_puntomedicion>.modal-dialog{
		min-width: 1200px!important;
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
							<input type="hidden" class="form-control" id="puntomedicion_id" name="puntomedicion_id" value="0">
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. Punto medición</label>
								<input type="number" min="1" class="form-control" id="reporteaguaevaluacion_punto" name="reporteaguaevaluacion_punto" required>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Área</label>
								<select class="custom-select form-control" id="puntomedicion_reporteaguaarea_id" name="reporteaguaarea_id" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Ubicación</label>
								<input type="text" class="form-control" id="reporteaguaevaluacion_ubicacion" name="reporteaguaevaluacion_ubicacion" placeholder="Ej.: Comedor" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Suministro</label>
								<input type="text" class="form-control" id="reporteaguaevaluacion_suministro" name="reporteaguaevaluacion_suministro" placeholder="Ej.: Grifo de lavamanos" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Tipo uso</label>
								<select class="custom-select form-control" id="reporteaguaevaluacion_tipouso" name="reporteaguaevaluacion_tipouso" required>
									<option value=""></option>
									<option value="Uso humano">Uso humano</option>
									<option value="Consumo humano">Consumo humano</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Descripción de uso</label>
								<input type="text" class="form-control" id="reporteaguaevaluacion_descripcionuso" name="reporteaguaevaluacion_descripcionuso" placeholder="Ej.: Utilizada para higiene del personal" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Condiciones del medio</label>
								<input type="text" class="form-control" id="reporteaguaevaluacion_condiciones" name="reporteaguaevaluacion_condiciones" placeholder="Ej.: Se encontró limpia el área donde se realizó el muestreo" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Fecha de medición</label>
								<div class="input-group">
									<input type="text" class="form-control mydatepicker" id="reporteaguaevaluacion_fecha" name="reporteaguaevaluacion_fecha" placeholder="aaaa-mm-dd" required>
									<span class="input-group-addon"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Total trabajadores</label>
								<input type="number" min="0" class="form-control" id="reporteaguaevaluacion_totalpersonas" name="reporteaguaevaluacion_totalpersonas" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Grupo exp. homo.</label>
								<input type="number" min="0" class="form-control" id="reporteaguaevaluacion_geo" name="reporteaguaevaluacion_geo" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Folio de la muestra de agua</label>
								<input type="text" class="form-control" id="reporteaguaevaluacion_foliomuestra" name="reporteaguaevaluacion_foliomuestra" required>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label>Tipo evaluación</label>
								<select class="custom-select form-control" id="reporteaguaevaluacion_tipoevaluacion" name="reporteaguaevaluacion_tipoevaluacion" required onchange="aguaevaluacion_parametos(form_modal_puntomedicion.puntomedicion_id.value, this.value, proveedor_id);">
									<option value=""></option>
									<option value="Fisicoquímico">Fisicoquímico</option>
									<option value="Microbiológico">Microbiológico</option>
									<option value="Fisicoquímico_Microbiológico">Fisicoquímico y Microbiológico</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<style type="text/css">
							.tabla_evaluacion td
							{
								vertical-align: bottom!important;
							}

							.tabla_evaluacion td label
							{
								color: #000000!important;
								margin: 0px!important;
								padding: 0px!important;
							}

							.tabla_evaluacion tbody tr td .form-control
							{
								margin: 2px 0px 2px 0px!important;
								padding: 4px!important;
								height: 26px!important;
								min-height: 26px!important;
								font-size: 12px!important;
								line-height: 12px!important;
							}

							.tabla_evaluacion tbody tr td select
							{
								margin: 0px!important;
								padding: 4px!important;
								height: 26px!important;
								min-height: 26px!important;
								font-size: 12px!important;
								line-height: 12px!important;
							}
						</style>
						<div class="col-6">
							<div style="margin-top: -20px;">
								<table class="table table-hover tabla_info_centrado tabla_evaluacion" style="margin-bottom: 0px;" width="100%" id="tabla_evaluacion_parametros">
									<thead>
										<tr>
											<th colspan="3" style="background: #999999; color: #FFFFFF!important;">Parámetros evaluados</th>
										</tr>
										<tr>
											<th width="33.33%">Parámetro / Resultado</th>
											<th width="33.33%">Método análisis</th>
											<th width="33.33%">Concentración obtenida</th>
										</tr>
									</thead>
									<tbody>
										{{-- <tr>
											<td>
												<label>Coliformes Totales</label>
												<select class="custom-select form-control select_alcances" style="height: 26px;" name="xxxxxxxxx[]" onchange="select_background(this);" required>
													<option value="">Seleccione resultado</option>
													<option value="Dentro de norma">Dentro de norma</option>
													<option value="Fuera de norma">Fuera de norma</option>
												</select>
											</td>
											<td>
												<input type="text" class="form-control" placeholder="Unidades" name="xxxxxxxxx[]" required>
												<input type="text" class="form-control" placeholder="Método análisis" name="xxxxxxxxx[]" required>
											</td>
											<td>
												<input type="text" class="form-control" placeholder="Concentración obtenida" name="xxxxxxxxx[]" required>
												<input type="text" class="form-control" placeholder="Concentración permisible" name="xxxxxxxxx[]" required>
											</td>
										</tr> --}}
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-6">
							<div style="margin-top: -20px;">
								<table class="table table-hover tabla_info_centrado tabla_evaluacion" style="margin-bottom: 0px;" width="100%" id="tabla_evaluacion_categorias">
									<thead>
										<tr>
											<th colspan="3" style="background: #999999; color: #FFFFFF!important;">Categorías expuestas</th>
										</tr>
										<tr>
											<th width="">Categoría</th>
											<th width="180">Nombre</th>
											<th width="80">Ficha</th>
										</tr>
									</thead>
									<tbody>
										{{-- <tr>
											<td>
												<div class="switch" style="float: left;">
													<label>
														<input type="checkbox" class="checkbox_alcance" name="aguaevaluacioncategoria_id[]" value="0">
														<span class="lever switch-col-light-blue"></span>
													</label>
												</div>
												<label class="demo-switch-title" style="float: left;">Operador Especialista en Plantas Turbocompresores</label>
											</td>
											<td>
												<input type="text" class="form-control" placeholder="nombre" name="reporteaguaevaluacioncategoria_nombre[]" required>
											</td>
											<td>
												<input type="text" class="form-control" placeholder="ficha" name="reporteaguaevaluacioncategoria_ficha[]" required>
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
<!-- MODAL-REPORTE-AREA EVALUACION -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO PUNTO NER -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_materialutilizado>.modal-dialog{
		/*min-width: 1000px!important;*/
	}

	#modal_reporte_materialutilizado .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_materialutilizado .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
	}
</style>
<div id="modal_reporte_materialutilizado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_materialutilizado" id="form_modal_materialutilizado">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Punto de medición NER</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="materialutilizado_id" name="materialutilizado_id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Material</label>
								<input type="text" class="form-control" id="reporteaguamaterial_nombre" name="reporteaguamaterial_nombre" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_materialutilizado">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-RESULTADO PUNTO NER -->
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


<!-- ============================================================== -->
<!-- MODAL-REPORTE-IMPRIMIR PARTIDA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_imprimirtipo>.modal-dialog{
		min-width: 800px!important;
	}

	#modal_reporte_imprimirtipo .modal-body .form-group{
		margin: 0px 0px 12px 0px!important;
		padding: 0px!important;
	}

	#modal_reporte_imprimirtipo .modal-body .form-group label{
		margin: 0px!important;
		padding: 0px 0px 3px 0px!important;
	}
</style>
<div id="modal_reporte_imprimirtipo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_modal_imprimirtipo" id="form_modal_imprimirtipo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Descargar informe de químicos</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="imprimirtipo_registro_id" name="imprimirtipo_registro_id" value="0">
							<input type="hidden" class="form-control" id="imprimirtipo_revision_id" name="imprimirtipo_revision_id" value="0">
							<input type="hidden" class="form-control" id="imprimirtipo_ultima_revision" name="imprimirtipo_ultima_revision" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Tipo</label>
								<select class="custom-select form-control" id="imprimirtipo_tipoinforme" name="imprimirtipo_tipoinforme" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						{{-- 
						<div class="col-12">
							<div class="form-group">
								<label>Partida (Título del informe)</label>
								<select class="custom-select form-control" id="imprimirtipo_partidainforme" name="imprimirtipo_partidainforme" required>
									<option value=""></option>
									@foreach($clientepartidas as $partida)
										@if(strlen($partida->clientepartidas_descripcion) > 140)
											<option value="{{ $partida->id }}">{{ substr($partida->clientepartidas_descripcion, 0, 47).'...'.substr($partida->clientepartidas_descripcion, -70) }}</option>
										@else
											<option value="{{ $partida->id }}">{{ $partida->clientepartidas_descripcion }}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>
						 --}}
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-info waves-effect waves-light" id="botonguardar_modal_imprimirtipo">Descargar <i class="fa fa-download"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-IMPRIMIR PARTIDA -->
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
<script src="/js_sitio/reportes/reporteagua.js"></script>