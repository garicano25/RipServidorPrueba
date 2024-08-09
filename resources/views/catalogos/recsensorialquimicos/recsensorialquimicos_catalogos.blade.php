@extends('template/maestra')
@section('contenido')
{{-- ========================================================================= --}}

<div class="row page-titles">
	{{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Proveedores</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">Proveedores</li>
        </ol>
    </div> --}}
	<div class="col-12 align-self-center">
		<div class="d-flex justify-content-end">
			<div class="">
				{{-- <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10">
                    <i class="ti-settings text-white"></i>
                </button> --}}
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	table th {
		font-size: 12px !important;
		color: #777777 !important;
		font-weight: 500 !important;
	}

	table td {
		font-size: 12px !important;
	}

	.error {
		border: 2px solid red;
	}
</style>

<div class="row">
	<div class="col-3">
		<div class="card">
			<div class="card-body bg-secondary">
				<h4 class="text-white card-title">Catálogos</h4>
				<h6 class="card-subtitle text-white">Módulo reconocimiento sensorial químicos</h6>
			</div>
			<div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
				<div class="message-box contact-box">
					<div class="table-responsive m-t-20">
						<table class="table table-hover stylish-table" id="tabla_lista_catalogos" width="100%">
							<thead>
								<tr>
									<th>Catálogo</th>
									<th width="120">Mostrar</th>
								</tr>
							</thead>
							<tbody>
								<tr id="tr_0" class="active">
									<td>Referencias de Sustancias químicas de la NOM-010 y otros</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(0);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_0"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_1" class="active">
									<td>Hojas de seguridad</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(1);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_1"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_2" class="" style="display: none;">
									<td>Estado físico</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(2);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_2"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_3" class="" style="display: none;">
									<td>Volatilidad</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(3);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_3"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_4" class="" style="display: none;">
									<td>Vía ingreso al organismo</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(4);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_4"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_5" class="" style="display: none;">
									<td>Categoría de peligro</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(5);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_5"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_6" class="" style="display: none">
									<td>Grado de riesgo</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(6);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_6"></i>
										</a>
									</td>
								</tr>
								<!-- Hacemos uso del catalago 8 ya que el 7 fue ocupado para otra cosa -->
								<tr id="tr_8" class="">
									<td>Unidad de medida</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(8);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_8"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_9" class="">
									<td>Connotaciones</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(9);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_9"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_10" class="">
									<td>Entidades</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(10);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_10"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_11">
									<td>Conclusiones para Informes</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(11);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_11"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_12">
									<td>Descripción área</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(12);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_12"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-9">
		<div class="card">
			<div class="card-body bg-info">
				<h4 class="text-white card-title" id="titulo_tabla">Nombre catálogo</h4>
				<h6 class="card-subtitle text-white">Lista de registros</h6>
			</div>
			<div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
				<div class="message-box contact-box" id="div_tabla_catalogo"></div>
			</div>
		</div>
	</div>
</div>


<!-- ============================================================== -->
<!-- MODALES -->
<!-- ============================================================== -->
<div id="modal_catsustancia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 95% !important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catsustancia" id="form_catsustancia">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Sustancia</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="sustancia_id" name="sustancia_id" value="0">
							<input type="hidden" class="form-control" id="catsustancia_activo" name="catsustancia_activo" value="1">
							<input type="hidden" class="form-control" id="catsustacia_catalogo" name="catalogo" value="0">
						</div>
						<div class="row m-2">
							<div class="col-8">
								<div class="row">
									<div class="col-12">
										<div class="form-group mt-2">
											<label>Nombre Sustancia/Producto (mezcla) *</label>
											<input type="text" class="form-control" id="catsustancia_nombre" name="catsustancia_nombre" required>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group mt-2">
											<label>Nombre común </label>
											<input type="text" class="form-control" id="catsustancia_nombreComun" name="catsustancia_nombreComun">
										</div>
									</div>

									<div class="col-6 mt-2">
										<div class="form-group">
											<label>Nombre del fabricante *</label>
											<input type="text" class="form-control" id="catsustancia_fabricante" name="catsustancia_fabricante" required>
										</div>
									</div>

									<div class="col-6">
										<div class="form-group">
											<label>Vía ingreso al organismo (mezcla) *</label>
											<select class="custom-select form-control" id="catviaingresoorganismo_id" name="catviaingresoorganismo_id" required>
												<option value=""></option>
												@foreach($catviaingresoorganismo as $dato)
												<option value="{{$dato->id}}">{{$dato->catviaingresoorganismo_viaingreso}}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-6">
										<div class="form-group">
											<label>Estado físico (mezcla) *</label>
											<select class="custom-select form-control" id="catestadofisicosustancia_id" name="catestadofisicosustancia_id" required>
												<option value=""></option>
												@foreach($catestadofisicosustancia as $dato)
												<option value="{{$dato->id}}">{{$dato->catestadofisicosustancia_estado}}</option>
												@endforeach
											</select>
										</div>
									</div>


									<div class="col-6" id="divPuntoEbullicion">
										<div class="form-group">
											<label>Punto de ebullición </label>
											<input type="number" class="form-control" id="catsustancia_puntoEbullicion" name="catsustancia_puntoEbullicion" disabled>
										</div>
									</div>


									<div class="col-6" id="divTipoSolido" style="display: none;">
										<div class="form-group">
											<label>Tipo *</label>
											<select class="custom-select form-control" id="catsustancia_solidoTipo" name="catsustancia_solidoTipo">
												<option value=""></option>
												<option value="Polvo" title="[Volatilidad: Media] Sustancias sólidas cristalinas o granulares. Cuando son usadas, se observa producción de polvo que se disipa o deposita rápidamente sobre superficies después del uso. p.ej. jabón en polvo, entre otros.
												">Polvo</option>
												<option value="Humo" title="[Volatilidad: Alta] Sustancias en forma de pellets que no tienen tendencia a romperse. No se aprecia producción de polvo durante su empleo. p.ej. pellets de cloruro de polivinilo, escamas enceradas, entre otras.">Humo</option>
												<option value="Fibra" title="[Volatilidad: Baja] Sustancias en forma de pellets que no tienen tendencia a romperse. No se aprecia producción de polvo durante su empleo. p.ej. pellets de cloruro de polivinilo, escamas enceradas, entre otras.">Fibra</option>
											</select>
										</div>
									</div>

									<div class="col-6">
										<div class="form-group">
											<label>Volatilidad (mezcla) *</label>
											<select class="custom-select form-control" id="catvolatilidad_id" name="catvolatilidad_id" required>
												<option value=""></option>
												@foreach($catvolatilidad as $dato)
												<option value="{{$dato->id}}">{{$dato->catvolatilidad_tipo}}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-6 mt-2">
										<div class="form-group">
											<label>Hoja de seguridad (PDF)</label>
											<div class="fileinput fileinput-new input-group" data-provides="fileinput">
												<div class="form-control" data-trigger="fileinput" id="campo_file_pdf">
													<i class="fa fa-file fileinput-exists"></i>
													<span class="fileinput-filename"></span>
												</div>
												<span class="input-group-addon btn btn-secondary btn-file">
													<span class="fileinput-new">Seleccione</span>
													<span class="fileinput-exists">Cambiar</span>
													<input type="file" name="hojaseguridadpdf" id="hojaseguridadpdf"> {{-- required --}}
												</span>
												<a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
											</div>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>Temperatura de Operación (°C)</label>
											<button type="button" class="btn btn-danger text-center mb-1" style="margin-left: 35%; width: 35px; height: 35px; border-radius: 9px;" data-toggle="tooltip" title="Click para cambiar la Tem. de operación a °C una vez insertada en °F" onclick="cambiarGrados('catTemOperacion')"><i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i></button>
											<input type="number" class="form-control" id="catTemOperacion" name="catTemOperacion">
										</div>
									</div>

								</div>
							</div>

							<div class="col-4">
								<div class="row">

									<div class="col-12">
										<h3>¿Cuenta con una Categoría de Peligro para la Salud NMX-R-019-SCFI-2011?</h3>
										<div class="form-check mx-4 mt-3">
											<input class="form-check-input" type="radio" name="catTipoClasificacion" id="catTipoClasificacion_cat" value="1" checked>
											<label class="form-check-label" for="catTipoClasificacion_cat">
												Si
											</label>

											<input class="form-check-input mx-5" type="radio" name="catTipoClasificacion" id="catTipoClasificacion_grado" value="0">
											<label class="form-check-label mx-5" for="catTipoClasificacion_grado">
												No
											</label>
										</div>
									</div>

									<style type="text/css">
										.tooltip-inner {
											max-width: 320px;
											/*tooltip tamaño*/
											padding: 6px 8px;
											color: #fff;
											text-align: justify;
											background-color: #000;
											border-radius: 0.25rem;
											line-height: 16px;
										}

										#rol_lista:hover label {
											color: #000000;
											font-weight: bold;
										}
									</style>

									<div class="col-12 mt-4" id="divCategoriaSaludHoja">
										<h4 class="mx-3">Categoría de peligro a la salud (mezcla) *</h4>
										@foreach($catcategoriapeligrosalud as $dato)
										<div class="col-12" id="rol_lista_{{$dato->id}}" data-toggle="tooltip" title="{{$dato->catcategoriapeligrosalud_descripcion}}">
											<div class="form-check">
												<input class="form-check-input catHoja" type="radio" name="catcategoriapeligrosalud_id" id="opcion_{{$dato->id}}" value="{{$dato->id}}" data-riesgo_cat="{{$dato->CLASIFICACION_RIESGO_CATEGORIA}}">
												<label class=" form-check-label" for="opcion_{{$dato->id}}">
													[{{$dato->id}}] {{$dato->catcategoriapeligrosalud_codigo}}
												</label>
											</div>
										</div>
										@endforeach
									</div>

									<div class="col-12 mt-4" id="divGradoSaludHoja" style="display: none;">
										<h4 class="mx-3 mb-3">Grado de riesgo a la salud según NOM-018-STPS-2000 *</h4>
										@foreach($catgradoriesgosalud as $dato)
										<div class="col-12 mb-3">
											<div class="form-check">
												<input class="form-check-input gradoHoja" type="radio" name="catgradoriesgosalud_id" id="catgradoriesgosalud_{{$dato->id}}" value="{{$dato->id}}" data-riesgo_grado="{{$dato->CLASIFICACION_RIESGO_GRADO}}">
												<label class="form-check-label" for="catgradoriesgosalud_{{$dato->id}}">
													[Grado {{$dato->id}}] {{$dato->catgradoriesgosalud_clasificacion}}
												</label>
											</div>
										</div>
										@endforeach
									</div>


									<div class="col-12 mt-3">
										<ol class="breadcrumb mb-2 p-2" style="background-color: #94B732 !important;">
											<h3 style="color: #ffff; margin: 0;" class="mx-4 mt-1">Clasificación de Riesgo: </h3>
											<h2 style="color: #ffff; margin: 0;" class="mx-2" id="clasificacion_riesgo_text_hoja"></h2>
											<input type="hidden" class="form-control" id="catClasificacionRiesgo" name="catClasificacionRiesgo">

										</ol>
									</div>

								</div>
							</div>
						</div>


						<div class="col-12 mt-2">
							<ol class="breadcrumb mb-4">
								<h2 style="color: #ffff; margin: 0;"><i class="fa fa-flask"></i> Componentes / Subproductos de la sustancia </h2>
							</ol>

							<style>
								/* Estilos para el elemento select */
								.select2-selection__choice {
									color: #000;
								}

								.select2-container--default .select2-selection--multiple .select2-selection__choice {
									background-color: #bee24f;
								}

								.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
									background-color: #fff;
								}
							</style>

							<select class="custom-select form-control" id="sustancias_quimicias" name="sustancias_quimicias[]" multiple="multiple" style="width: 100%" required>
								@foreach($catSustanciasQuimicas as $dato)
								<option value="{{$dato->ID_SUSTANCIA_QUIMICA}}">[{{$dato->NUM_CAS}}] {{$dato->SUSTANCIA_QUIMICA}} </option>
								@endforeach
							</select>

							<h4 class="mt-3 mx-5" id="textPorcentajes" style="display: none;">Ingrese el % del Componente. El (*) se considera como un porcentaje normal</h4>
							<div class=" col-12 mt-3" id="sustancias_seleccionadas">
								<style type="text/css">
									#tablaSustanciasSeleccionadas td,
									#tablaSustanciasSeleccionadas th {
										padding: 0.5rem;
									}

									#tablaSustanciasSeleccionadas input,
									#tablaSustanciasSeleccionadas select {
										width: 100%;
									}
								</style>
								<table class="table table-bordered mt-4" style="width: 100%;" id="tablaSustanciasSeleccionadas">
									<thead>
										<tr>
											<th>Componente</th>
											<th>Tipo</th>
											<th>Operador</th>
											<th>Porcentaje</th>
											<th>Estado Fisico</th>
											<th>Forma</th>
											<th>Tem. de ebullición (°C)</th>
											<th>Volatilidad</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_sustancia">
						Guardar <i class="fa fa-save"></i>
					</button>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_catestadofisico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catestadofisico" id="form_catestadofisico">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Estado físico de las sustancia</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catestadofisico_id" name="id" value="0">
							<input type="hidden" class="form-control" id="catestadofisicosustancia_activo" name="catestadofisicosustancia_activo" value="1">
							<input type="hidden" class="form-control" id="catestadofisico_catalogo" name="catalogo" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Estado físico *</label>
								<input type="text" class="form-control" id="catestadofisicosustancia_estado" name="catestadofisicosustancia_estado" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catestadofisico">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_catvolatilidad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catvolatilidad" id="form_catvolatilidad">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Volatilidad de la sustancia</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="volatilidad_id" name="id" value="0">
							<input type="hidden" class="form-control" id="volatilidad_activo" name="catvolatilidad_activo" value="1">
							<input type="hidden" class="form-control" id="volatilidad_catalogo" name="catalogo" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Volatilidad *</label>
								<input type="text" class="form-control" id="catvolatilidad_tipo" name="catvolatilidad_tipo" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Valor ponderación *</label>
								<input type="number" class="form-control" id="catvolatilidad_ponderacion" name="catvolatilidad_ponderacion" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catvolatilidad">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_catviaingreso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catviaingreso" id="form_catviaingreso">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Vía de ingreso al organismo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catviaingreso_id" name="id" value="0">
							<input type="hidden" class="form-control" id="catviaingreso_activo" name="catviaingresoorganismo_activo" value="1">
							<input type="hidden" class="form-control" id="catviaingreso_catalogo" name="catalogo" value="0">
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Volatilidad *</label>
								<input type="text" class="form-control" id="catviaingresoorganismo_viaingreso" name="catviaingresoorganismo_viaingreso" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Ponderación *</label>
								<input type="number" class="form-control" id="catviaingresoorganismo_ponderacion" name="catviaingresoorganismo_ponderacion" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Num. POE expuesto *</label>
								<input type="text" class="form-control" id="catviaingresoorganismo_poe" name="catviaingresoorganismo_poe" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Tiempo exposición *</label>
								<input type="text" class="form-control" id="catviaingresoorganismo_horasexposicion" name="catviaingresoorganismo_horasexposicion" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catviaingreso">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_catpeligro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 900px!important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catpeligro" id="form_catpeligro">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Categoría peligro a la salud</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catpeligro_id" name="id" value="0">
							<input type="hidden" class="form-control" id="catpeligro_activo" name="catcategoriapeligrosalud_activo" value="1">
							<input type="hidden" class="form-control" id="catpeligro_catalogo" name="catalogo" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Categoría de peligro</label>
								<input type="text" class="form-control" id="campo_catpeligro_id" name="campo_catpeligro_id" value="0" readonly>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Código de peligro *</label>
								<input type="text" class="form-control" id="catcategoriapeligrosalud_codigo" name="catcategoriapeligrosalud_codigo" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Descripción </label>
								<textarea class="form-control" rows="3" id="catcategoriapeligrosalud_descripcion" name="catcategoriapeligrosalud_descripcion"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catpeligro">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_catgradoriesgo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 900px!important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catgradoriesgo" id="form_catgradoriesgo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Grado de riesgo a la salud</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catgradoriesgo_id" name="id" value="0">
							<input type="hidden" class="form-control" id="catgradoriesgo_activo" name="catgradoriesgosalud_activo" value="1">
							<input type="hidden" class="form-control" id="catgradoriesgo_catalogo" name="catalogo" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Grado de riesgo</label>
								<input type="text" class="form-control" id="campo_catgradoriesgo_id" name="campo_catgradoriesgo_id" value="0" readonly>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Descripción *</label>
								<input type="text" class="form-control" id="catgradoriesgosalud_clasificacion" name="catgradoriesgosalud_clasificacion" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Vía ingredo oral *</label>
								<input type="text" class="form-control" id="catgradoriesgosalud_viaingresooral" name="catgradoriesgosalud_viaingresooral" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Vía ingredo piel *</label>
								<input type="text" class="form-control" id="catgradoriesgosalud_viaingresopiel" name="catgradoriesgosalud_viaingresopiel" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Vía ingredo inhalación *</label>
								<input type="text" class="form-control" id="catgradoriesgosalud_viaingresoinhalacion" name="catgradoriesgosalud_viaingresoinhalacion" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Valor ponderación *</label>
								<input type="number" class="form-control" id="catgradoriesgosalud_ponderacion" name="catgradoriesgosalud_ponderacion" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catgradoriesgo">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_catSustanciaQuimica" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 90%!important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catSustanciQuimica" id="form_catSustanciQuimica">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Nueva Sustancia Química</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_SUSTANCIA_QUIMICA" name="ID_SUSTANCIA_QUIMICA" value="0">
							<input type="hidden" class="form-control" id="ACTIVO" name="ACTIVO" value="1">
							<input type="hidden" class="form-control" id="SUSTANCIA_QUIMICO_CATALAGO" name="catalogo" value="0">
						</div>
						<div class="col-12">
							<ol class="breadcrumb mb-3 p-1">
								<h2 style="color: #ffff; margin: 0;" class="mx-2"><i class="fa fa-wpforms" aria-hidden="true"></i> Datos generales de la sustancia</h2>
							</ol>
						</div>
						<div class="row">
							<div class="col-8">
								<div class="row mx-2">
									<div class="col-6">
										<div class="form-group">
											<label>Sustancia Química </label>
											<input type="text" class="form-control" id="SUSTANCIA_QUIMICA" name="SUSTANCIA_QUIMICA">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>No. CAS </label>
											<input type="text" class="form-control" id="NUM_CAS" name="NUM_CAS">
										</div>
									</div>
									<div class="col-12">
										<div class="form-group">
											<label>Alteración / Efecto a la Salud </label>
											<textarea class="form-control" rows="3" id="ALTERACION_EFECTO" name="ALTERACION_EFECTO" required></textarea>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label id="pm">PM </label>
											<input type="text" class="form-control" id="PM" name="PM">
										</div>
									</div>

									<div class="col-6">
										<div class="form-group">
											<label>Vía ingreso al organismo </label>
											<select class="custom-select form-control" id="VIA_INGRESO" name="VIA_INGRESO">
												<option value=""></option>
												@foreach($catviaingresoorganismo as $dato)
												<option value="{{$dato->id}}">{{$dato->catviaingresoorganismo_viaingreso}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-12 mt-3">
										<ol class="breadcrumb mb-2 p-2" style="background-color: #94B732 !important;">
											<h3 style="color: #ffff; margin: 0;" class="mx-4 mt-1">Clasificación de Riesgo: </h3>
											<h2 style="color: #ffff; margin: 0;" class="mx-2" id="clasificacion_riesgo_text"></h2>
											<input type="hidden" class="form-control" id="CLASIFICACION_RIESGO" name="CLASIFICACION_RIESGO">

										</ol>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="col-12">
									<h3>¿Cuenta con una Categoría de Peligro para la Salud NMX-R-019-SCFI-2011?</h3>
									<div class="form-check mx-4 mt-3">
										<input class="form-check-input" type="radio" name="TIPO_CLASIFICACION" id="TIPO_CLASIFICACION_CAT" value="1" checked>
										<label class="form-check-label" for="TIPO_CLASIFICACION_CAT">
											Si
										</label>

										<input class="form-check-input mx-5" type="radio" name="TIPO_CLASIFICACION" id="TIPO_CLASIFICACION_GRADO" value="0">
										<label class="form-check-label mx-5" for="TIPO_CLASIFICACION_GRADO">
											No
										</label>
									</div>
								</div>

								<div class="col-12 mt-4" id="divCategoriaSalud">
									<h4 class="mx-3 mb-3">Categoría de peligro a la salud *</h4>
									@foreach($catcategoriapeligrosalud as $dato)
									<div class="col-12 mb-3" id="rol_lista_{{$dato->id}}" data-toggle="tooltip" title="{{$dato->catcategoriapeligrosalud_descripcion}}">
										<div class="form-check">
											<input class="form-check-input CATEGORIA" type="radio" name="CATEGORIA_PELIGRO_ID" id="CATEGORIA_PELIGRO_{{$dato->id}}" value="{{$dato->id}}" data-riesgo_cat="{{$dato->CLASIFICACION_RIESGO_CATEGORIA}}">
											<label class="form-check-label" for="CATEGORIA_PELIGRO_{{$dato->id}}">
												[{{$dato->id}}] {{$dato->catcategoriapeligrosalud_codigo}}
											</label>
										</div>
									</div>
									@endforeach
								</div>

								<div class="col-12 mt-5" id="divGradoSalud" style="display: none;">
									<h4 class="mx-3 mb-3">Grado de riesgo a la salud según NOM-018-STPS-2000</h4>
									@foreach($catgradoriesgosalud as $dato)
									<div class="col-12 mb-3">
										<div class="form-check">
											<input class="form-check-input GRADO" type="radio" name="GRADO_RIESGO_ID" id="GRADO_RIESGO_{{$dato->id}}" value="{{$dato->id}}" data-riesgo_grado="{{$dato->CLASIFICACION_RIESGO_GRADO}}">
											<label class="form-check-label" for="GRADO_RIESGO_{{$dato->id}}">
												[Grado {{$dato->id}}] {{$dato->catgradoriesgosalud_clasificacion}}
											</label>
										</div>
									</div>
									@endforeach
								</div>
							</div>
						</div>

						<div class="col-12 tablaEntidades">
							<ol class="breadcrumb mb-2 p-2 mt-3">
								<h2 style="color: #ffff; margin: 0;" class="mx-2"><i class="fa fa-file-text-o" aria-hidden="true"></i> Datos por entidad </h2>

								@if(auth()->user()->hasRoles(['Superusuario','Administrador', 'Reconocimiento', 'Coordinador']))

								<button type="button" class="btn btn-secondary waves-effect waves-light " data-toggle="tooltip" title="Nueva entidad" id="boton_nueva_sustanciaEntidad" style="margin-left: 13px;"> Entidad <i class="fa fa-plus"></i>
								</button>

								<button type="button" class="btn btn-secondary waves-effect waves-light mr-2" data-toggle="tooltip" title="Ver información" id="boton_verInfo" style="margin-left: auto;"> Ver información <i class="fa fa-eye"></i>
								</button>

								@else
								<button type="button" class="btn btn-secondary waves-effect waves-light mr-2" data-toggle="tooltip" title="Ver información" id="boton_verInfo" style="margin-left: auto;"> Ver información <i class="fa fa-eye"></i>
								</button>
								@endif
							</ol>
						</div>
						<div class="col-12 tablaEntidades">
							<div class="table-responsive">
								<table class="table table-bordered table-hover stylish-table" width="100%" id="tabla_catSustanciasQuimicaEntidad">
									<thead>
										<tr>
											<th>Entidad</th>
											<th>Normativa</th>
											<th>Connotación</th>
											<th>PPT</th>
											<th>CT</th>
											<th>Acciones</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catSustanciaQuimica">
						Guardar <i class="fa fa-save"></i>
					</button>
					@endif

				</div>
			</form>
		</div>
	</div>
</div>




<div id="modal_catSustanciaQuimicaEntidad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 90%!important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catSustanciQuimicaEntidad" id="form_catSustanciQuimicaEntidad">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="titulo_modal_sustancia_entidad">Nueva Sustancia Química por Entidad</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_SUSTANCIA_QUIMICA_ENTIDAD" name="ID_SUSTANCIA_QUIMICA_ENTIDAD" value="0">
							<input type="hidden" class="form-control" id="SUSTANCIA_QUIMICA_ID" name="SUSTANCIA_QUIMICA_ID" value="0">
							<input type="hidden" class="form-control" id="ACTIVO" name="ACTIVO" value="1">
							<input type="hidden" class="form-control" id="SUSTANCIA_QUIMICO_CATALAGO" name="catalogo" value="7">
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Entidad *</label>
								<select class="custom-select form-control" id="ENTIDAD_ID" name="ENTIDAD_ID" required>
									<option value=""></option>
									@foreach($catEntidades as $dato)
									<option value="{{$dato->ID_ENTIDAD}}" data-descripcion="{{$dato->DESCRIPCION}}"> {{$dato->ENTIDAD}} </option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Descripción normativa:</label>
								<input type="text" class="form-control" id="DESCRIPCION_NORMATIVA" name="DESCRIPCION_NORMATIVA" readonly>
							</div>
						</div>
						<div class="row mx-1">
							<div class="col-6">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label id="ppt">VLE - PPT </label>
											<input type="text" class="form-control" id="VLE_PPT" name="VLE_PPT">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label id="ct">VLE - CT o P</label>
											<input type="text" class="form-control" id="VLE_CT_P" name="VLE_CT_P">
										</div>
									</div>
									<div class="col-12">
										<div class="form-group">
											<label> Nota </label>
											<textarea class="form-control" rows="3" id="NOTA_SUSTANCIA_ENTIDAD" name="NOTA_SUSTANCIA_ENTIDAD"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group" style="width: 660px;">
									<style>
										.selectize-control.selectize-select .selectize-input.items .item {
											background-color: #bee24f;
											/* Color de fondo para las opciones seleccionadas */
											color: #fff;
											/* Color del texto */
											border-radius: 5px;
											/* Bordes redondeados */
											padding: 5px 10px;
											/* Espaciado interno */
											margin: 2px;
											/* Margen entre las opciones */
										}

										.selectize-control.selectize-select .selectize-dropdown .option {
											padding: 10px;
											/* Espaciado interno */
											cursor: pointer;
											/* Cambia el cursor al pasar por encima */
										}

										.selectize-control.selectize-select .selectize-dropdown .option:hover {
											background-color: #f0f0f0;
											/* Color de fondo al pasar el cursor */
											color: #333;
											/* Color del texto al pasar el cursor */
										}
									</style>
									<label>Connotacion </label>
									<select class="custom-select form-control" id="CONNOTACION" name="CONNOTACIONES[]" multiple="multiple">
										<option value=""></option>

									</select>
								</div>
								<div class="form-group" style="width: 660px;" id="opciones_seleccionadas">

								</div>
							</div>
						</div>
						<div class="col-12 p-2 d-flex justify-content-start mt-2">
							<h4>¿La sustancia quimica tiene IBEs?</h4>
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="TIENE_BEIS" id="SUSTANCIA_BEIS_SI" value="1">
								<label class="form-check-label" for="SUSTANCIA_BEIS_SI">
									Si
								</label>
							</div>
							<div class="form-check mx-4">
								<input class="form-check-input" type="radio" name="TIENE_BEIS" id="SUSTANCIA_BEIS_NO" value="0" checked>
								<label class="form-check-label" for="SUSTANCIA_BEIS_NO">
									No
								</label>
							</div>

							<div class="mx-4" id="div_btn_agregarBeis" style="display: none;">
								<button type="button" class="btn btn-danger" id="botonagregarBeis">Agregar IBEs <i class="fa fa-plus-circle"></i></button>
							</div>
						</div>
						<div class="agregarBeis"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catSustanciaQuimicaEntidad">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>




<div id="modal_catUnidadMedida" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catUnidadMedida" id="form_catUnidadMedida">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Unidad de medida</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_UNIDAD_MEDIDA" name="ID_UNIDAD_MEDIDA" value="0">
							<input type="hidden" class="form-control" id="ACTIVO_UNIDAD" name="ACTIVO" value="0">
							<input type="hidden" class="form-control" id="CATALOGO_UNIDAD" name="catalogo" value="8">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Descripción *</label>
								<input type="text" class="form-control" id="DESCRIPCION_UNIDAD" name="DESCRIPCION" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Abreviatura *</label>
								<input type="text" class="form-control" id="ABREVIATURA" name="ABREVIATURA" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catUnidadMedida">
						Guardar <i class="fa fa-save"></i>
					</button>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_catConnotacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catConnotacion" id="form_catConnotacion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Nueva Connotación</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_CONNOTACION" name="ID_CONNOTACION" value="0">
							<input type="hidden" class="form-control" id="ACTIVO_CONNOTACION" name="ACTIVO" value="0">
							<input type="hidden" class="form-control" id="CATALOGO_UNIDAD" name="catalogo" value="9">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Entidad *</label>
								<select class="custom-select form-control" id="CONNOTACION_ENTIDAD" name="ENTIDAD_ID" required>
									<option value=""></option>
									@foreach($catEntidades as $dato)
									<option value="{{$dato->ID_ENTIDAD}}">{{$dato->ENTIDAD}} [{{$dato->DESCRIPCION}}]</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Abreviatura *</label>
								<input type="text" class="form-control" id="CONNOTACION_ABREVIATURA" name="ABREVIATURA" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Descripción </label>
								<textarea class="form-control" rows="4" id="CONNOTACION_DESCRIPCION" name="DESCRIPCION"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catConnotacion">
						Guardar <i class="fa fa-save"></i>
					</button>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_catEntidades" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catEntidades" id="form_catEntidades">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Nueva Entidad</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_ENTIDAD" name="ID_ENTIDAD" value="0">
							<input type="hidden" class="form-control" id="ACTIVO_ENTIDAD" name="ACTIVO" value="0">
							<input type="hidden" class="form-control" id="CATALOGO_UNIDAD" name="catalogo" value="10">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Entidad *</label>
								<input type="text" class="form-control" id="ENTIDAD_ENTIDAD" name="ENTIDAD" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Descripción normativa *</label>
								<input type="text" class="form-control" id="ENTIDAD_DESCRIPCION" name="DESCRIPCION" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catEntidades">
						Guardar <i class="fa fa-save"></i>
					</button>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_carta_entidades" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 90%!important;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="tituloCartaEntidades"></h4>
			</div>
			<style>
				.cartaEntidad {

					box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
					transition: transform 0.3s ease;

				}


				.cartaEntidad:hover {
					transform: scale(1.02);
					/* Aplica el efecto de zoom */
				}

				.cartaBei {

					box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
					transition: transform 0.3s ease;

				}

				.cartaBei:hover {
					transform: scale(1.03);
					/* Aplica el efecto de zoom */
				}
			</style>
			<div class="modal-body" id="esqueletoCarta">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<div id="modal_conclusion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog" style="min-width: 70%;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_conclusion" id="form_conclusion">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo">Conclusiones para Informes</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_CATCONCLUSION" name="ID_CATCONCLUSION" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nombre *</label>
								<input type="text" class="form-control" id="NOMBRE_CONCLUSION" name="NOMBRE" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Descripción * </label>
								<textarea class="form-control" rows="5" id="DESCRIPCION_CONCLUSION" name="DESCRIPCION"></textarea>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="CATALOGO_CONCLUSION" name="catalogo" value="11">
							<input type="hidden" class="form-control" id="ACTIVO" name="ACTIVO" value="0">

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_conclusion">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_descripcionarea" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog" style="min-width: 70%;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_descripcionarea" id="form_descripcionarea">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo">Descripciones del área</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_DESCRIPCION_AREA" name="ID_DESCRIPCION_AREA" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Descripción * </label>
								<textarea class="form-control" rows="5" id="DESCRIPCION" name="DESCRIPCION" required></textarea>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="CATALOGO_DESCRIPCION" name="catalogo" value="12">
							<input type="hidden" class="form-control" id="ACTIVO_DESCRIPCION" name="ACTIVO" value="0">

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_descripcionarea">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- /MODALES -->
<!-- ============================================================== -->





<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
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
				<h4 class="modal-title" id="visor_titulo"></h4>
			</div>
			<div class="modal-body" style="background: #555555;">
				<div class="row">
					<div class="col-12">
						<iframe src="/assets/images/cargando.gif" id="visor_documento"></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="boton_cerrar_visorpdf">Cerrar</button>
				{{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->




@endsection