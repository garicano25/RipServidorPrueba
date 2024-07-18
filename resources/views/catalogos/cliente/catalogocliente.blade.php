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
		font-weight: 600 !important;
	}

	table td {
		font-size: 12px !important;
	}

	table td b {
		font-weight: 600 !important;
	}
</style>

<div class="row">
	<div class="col-4">
		<div class="card">
			<div class="card-body bg-secondary">
				<h4 class="text-white card-title">Catálogos</h4>
				<h6 class="card-subtitle text-white">Módulo Clientes</h6>
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
								{{-- <tr id="tr_10" class="active">
									<td>Agente / Factor de riesgo / Servicio</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(10);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_10"></i>
										</a>
									</td>
								</tr> --}}
								<tr id="tr_1">
									<td>Estructura organizacional</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(1);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_1"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_2" style="display: none">
									<td>Región</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(2);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_2"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_13" style="display: none">
									<td>Subdirección</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(13);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_13"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_3" style="display: none">
									<td>Gerencia</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(3);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_3"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_4" style="display: none">
									<td>Activo</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(4);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_4"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_5">
									<td>Departamento</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(5);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_5"></i>
										</a>
									</td>
								</tr>
								{{-- <tr id="tr_9">
									<td>Caracteristicas ventilación y calidad del aire</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(9);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_9"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_8">
									<td>Caracteristicas del agua</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(8);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_8"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_12">
									<td>Caracteristicas del hielo</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(12);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_12"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_7">
									<td>Características de los alimentos</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(7);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_7"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_11">
									<td>Características de las superficies</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(11);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_11"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_6">
									<td>Parte del cuerpo EPP</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(6);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_6"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_14">
									<td>Cargos para Informes</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(14);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_14"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_15">
									<td>Formatos de campo</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(15);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_15"></i>
										</a>
									</td>
								</tr> --}}
								{{-- <tr id="tr_16">
									<td>Conclusiones para Informes</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(16);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_16"></i>
										</a>
									</td>
								</tr> --}}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-8">
		<div class="card">
			<div class="card-body bg-info">
				<h4 class="text-white card-title" id="titulo_tabla">Nombre catálogo</h4>
				<h6 class="card-subtitle text-white">Lista de registros</h6>
			</div>
			<div class="card-body" style="border: 0px #f00 solid; min-height: 700px !important;">
				<div class="message-box contact-box">
					<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">
						<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" id="boton_nuevo_registro"><i class="fa fa-plus"></i></button>
					</h2>
					<div class="table-responsive m-t-20" id="div_datatable">
						<table class="table table-hover stylish-table" id="tabla_lista_registros" width="100%">
							<thead>
								<tr>
									<th>Nombre</th>
									<th style="width: 90px!important;">Editar</th>
									<th style="width: 90px!important;">Activo</th>
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
<!-- MODAL CATALOGO -->
<!-- ============================================================== -->
<div id="modal_catalogo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catalogo" id="form_catalogo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="id" name="id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nombre *</label>
								<input type="text" class="form-control" id="nombre" name="nombre" value="" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catalogo">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_cargo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_cargo" id="form_cargo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo">Cargo para Informes</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_CARGO_INFORME" name="ID_CARGO_INFORME" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Cargo *</label>
								<input type="text" class="form-control" id="CARGO" name="CARGO" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo" name="catalogo" value="14">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_cargo">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_formatos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog" style="min-width: 70%;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_formato" id="form_formato">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo">Nuevo formato de campo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_FORMATO" name="ID_FORMATO" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nombre *</label>
								<input type="text" class="form-control" id="FORMATO_NOMBRE" name="NOMBRE" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Documento PDF *</label>
								<div class="fileinput fileinput-new input-group" data-provides="fileinput">
									<div class="form-control" data-trigger="fileinput" id="campo_file_formato">
										<i class="fa fa-file fileinput-exists"></i>
										<span class="fileinput-filename"></span>
									</div>
									<span class="input-group-addon btn btn-secondary btn-file">
										<span class="fileinput-new">Seleccione</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" accept="application/pdf" name="FORMATO_PDF" id="FORMATO_PDF" required>
									</span>
									<a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">
										Quitar
									</a>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label> Descripción </label>
								<textarea class="form-control" rows="4" id="FORMATO_DESCRIPCION" name="DESCRIPCION"></textarea>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo" name="catalogo" value="15">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_formato">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- ============================================================== -->
<div id="modal_catalogo2campos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catalogo2campos" id="form_catalogo2campos">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Catálogo [Activo]</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo2campos_id" name="id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Siglas *</label>
								<input type="text" class="form-control" id="catalogo2campos_campo1" name="campo1" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Nombre *</label>
								<input type="text" class="form-control" id="catalogo2campos_campo2" name="campo2" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo2campos_catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catalogo2campos">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_agentes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 85%!important;">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_agentes" id="form_agentes">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Agente o factor de riesgo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="agente_id" name="id" value="0">
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Tipo *</label>
								<input type="text" class="form-control" id="catPrueba_Tipo" name="catPrueba_Tipo" placeholder="Ejem. Físico" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Nombre del agente o factor de riesgo *</label>
								<input type="text" class="form-control" id="catPrueba_Nombre" name="catPrueba_Nombre" placeholder="Ejem. Vibración" required>
							</div>
						</div>
						<div class="col-12">
							<ol class="breadcrumb m-b-10">
								<button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Agregar nuevo registro" id="boton_nueva_norma">
									<span class="btn-label"><i class="fa fa-plus"></i></span>Norma / Procedimiento / Método
								</button>
							</ol>
							<div class="table-responsive" style="max-height: 410px!important; border: 2px #CCCCCC solid;">
								<table class="table table-hover stylish-table" width="100%" id="tabla_lista_normas">
									<thead>
										<tr>
											<th style="width: 200px!important;">Tipo *</th>
											<th style="width: 230px!important;">Numero *</th>
											<th>Descripción *</th>
											<th style="width: 70px!important;">Eliminar</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="agente_catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_agente">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_contrato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_contrato" id="form_contrato">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo">Contrato</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="contrato_id" name="id" value="0">
						</div>
						<div class="col-4">
							<div class="form-group">
								<label>Numero contrato *</label>
								<input type="text" class="form-control" id="catcontrato_numero" name="catcontrato_numero" required>
							</div>
						</div>
						<div class="col-8">
							<div class="form-group">
								<label>Empresa *</label>
								<input type="text" class="form-control" id="catcontrato_empresa" name="catcontrato_empresa" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label> Inicio contrato *</label>
								<div class="input-group">
									<input type="text" class="form-control mydatepicker" placeholder="dd-mm-aaaa" id="catcontrato_fechainicio" name="catcontrato_fechainicio" required>
									<span class="input-group-addon"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label> Fin contrato *</label>
								<div class="input-group">
									<input type="text" class="form-control mydatepicker" placeholder="dd-mm-aaaa" id="catcontrato_fechafin" name="catcontrato_fechafin" required>
									<span class="input-group-addon"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label> Monto MXN *</label>
								<input type="number" step="any" class="form-control" id="catcontrato_montomxn" name="catcontrato_montomxn" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label> Monto USD *</label>
								<input type="number" step="any" class="form-control" id="catcontrato_montousd" name="catcontrato_montousd" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="contrato_catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_contrato">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<div id="modal_caracteristica" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_caracteristica" id="form_caracteristica">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="caracteristica_id" name="id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Tipo *</label>
								<select class="custom-select form-control" id="tipo" name="tipo" required>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Característica *</label>
								<input type="text" class="form-control" id="caracteristica" name="caracteristica" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="caracteristica_catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_caracteristica">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div id="modal_catalogo_epp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="post" enctype="multipart/form-data" name="form_catalogo_epp" id="form_catalogo_epp">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal_titulo"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="ID_PARTESCUERPO_DESCRIPCION" name="ID_PARTESCUERPO_DESCRIPCION" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Parte del cuerpo *</label>
								<select class="custom-select form-control" id="PARTECUERPO_ID" name="PARTECUERPO_ID" required>
									<option value=""></option>
									<option value="1">Cabeza</option>
									<option value="2">Oídos</option>
									<option value="3">Ojos y cara</option>
									<option value="4">Tronco</option>
									<option value="5">Extremidades superiores</option>
									<option value="6">Extremidades inferiores</option>
									<option value="7">Aparato respiratorio</option>
									<option value="8">Otros</option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label> Clave y EPP *</label>
								<input type="text" class="form-control" id="CLAVE_EPP" name="CLAVE_EPP" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Tipo de riesgo en funcion de la actividad del trabajador *</label>
								<input type="text" class="form-control" id="TIPO_RIEGO" name="TIPO_RIEGO" required>
							</div>
						</div>
						<div class="col-12">
							<input type="hidden" class="form-control" id="catalogo" name="catalogo" value="6">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catalogo_epp">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>



<div id="modal_etiqueta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="modal_titulo">Catálogo [Etiquetas]</h4>
			</div>
			<div class="modal-body">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<button class="nav-link active" id="nav-etiqueta-tab" data-toggle="tab" data-target="#nav-etiqueta" type="button" role="tab" aria-controls="nav-etiqueta" aria-selected="true">Etiqueta</button>
						<button class="nav-link" id="nav-opciones-tab" data-toggle="tab" data-target="#nav-opciones" type="button" role="tab" aria-controls="nav-opciones" aria-selected="false">Opciones</button>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-etiqueta" role="tabpanel" aria-labelledby="nav-etiqueta-tab">
						<form id="form_etiqueta" name="form_etiqueta" class="mt-4">
							{!! csrf_field() !!}
							<input type="hidden" class="form-control" id="ID_ETIQUETA" name="ID_ETIQUETA" value="0" required>

							<div class="col-12">
								<div class="form-group">
									<label> Tipo *</label>
									<select class="custom-select form-control" id="TIPO_ETIQUETA" name="TIPO_ETIQUETA" required>
										<option value=""></option>
										<option value="1">Estructura organizacional</option>
										<option value="2">Documentos</option>

									</select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Nombre etiqueta*</label>
									<input type="text" class="form-control" id="NOMBRE_ETIQUETA" name="NOMBRE_ETIQUETA" required>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Descripción</label>
									<input type="text" class="form-control" id="DESCRIPCION_ETIQUETA" name="DESCRIPCION_ETIQUETA">
								</div>
							</div>
							<div class="col-12">
								<input type="hidden" class="form-control" id="catalogo_etiqueta" name="catalogo" value="1">
							</div>
							<div class="col-12">
								<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_catalogo_etiqueta">Guardar <i class="fa fa-save"></i></button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="nav-opciones" role="tabpanel" aria-labelledby="nav-opciones-tab">
						<form id="form_opciones" class="mt-4 mb-5">
							{!! csrf_field() !!}

							<div class="col-12">
								<div class="form-group">
									<label class="mb-2">Clasificación *</label>
									<input type="text" class="form-control" id="NOMBRE_OPCIONES" name="NOMBRE_OPCIONES" required>
								</div>
							</div>
							<div class="col-12">
								<input type="hidden" class="form-control" id="catalogo_opciones" name="catalogo" value="0">
							</div>
							<div class="col-4">
								<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_etiqueta_opciones">Guardar <i class="fa fa-save"></i></button>
							</div>
						</form>

						<table id="tabla_opciones" class="table table-hover bg-white table-bordered  w-100 TableCustom">
							<thead>
								<tr>
									<th >Valor</th>
									<th style="width: 90px!important;">Activo</th>
									</tr>
								</thead>
							<tbody></tbody>
						</table>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>



<!-- ============================================================== -->
<!-- MODAL CATALOGO -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_visor>.modal-dialog {
		min-width: 900px !important;
	}

	#visor_menu_bloqueado {
		width: 851px;
		height: 52px;
		background: #555555;
		position: absolute;
		z-index: 500;
		border: 0px #F00 solid;
	}

	#visor_contenido_bloqueado {
		width: 852px;
		height: 600px;
		/*background: #555555;*/
		position: absolute;
		z-index: 600;
		border: 0px #FFF solid;
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
				<h4 class="modal-title" id="nombre_documento_visor"></h4>
			</div>
			<div class="modal-body" style="background: #555555;">
				<div class="row">
					<div class="col-12">
						{{-- <div id="visor_menu_bloqueado"></div> --}}
						{{-- <div id="visor_contenido_bloqueado"></div> --}}
						<iframe src="/assets/images/cargando.gif" name="visor_documento" id="visor_documento" allowfullscreen webkitallowfullscreen></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="modalvisor_boton_cerrar">Cerrar</button>
				{{-- <button type="button" class="btn btn-danger waves-effect waves-light">Guardar</button> --}}
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- VISOR-MODAL -->
<!-- ============================================================== -->


{{-- ========================================================================= --}}
@endsection