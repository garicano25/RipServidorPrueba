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
</style>

<div class="row">
	<div class="col-4">
		<div class="card">
			<div class="card-body bg-secondary">
				<h4 class="text-white card-title">Catálogos</h4>
				<h6 class="card-subtitle text-white">Módulo de proveedores</h6>
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
								<tr id="tr_1" class="active">
									<td>Tipo de proveedor</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(1);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_1"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_2" class="">
									<td>Servicio acreditación</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(2);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_2"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_3" class="">
									<td>Tipo acreditación</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(3);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_3"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_4" class="">
									<td>Áreas</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(4);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_4"></i>
										</a>
									</td>
								</tr>
								<tr id="tr_5" class="">
									<td>Estado del signatario</td>
									<td>
										<a href="#" onclick="mostrar_catalogo(5);">
											<i class="fa fa-chevron-circle-right fa-3x text-secondary" id="cat_5"></i>
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
						{{-- <table class="table table-hover stylish-table" id="datatable_registros" width="100%">
	                        <thead>
	                            <tr>
	                                <th>Nombre</th>
	                                <th width="120">Editar</th>
	                                <th width="120">Activo</th>
	                            </tr>
	                        </thead>
	                        <tbody></tbody>
	                    </table> --}}
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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post" name="form_catalogo" id="form_catalogo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="titulo_modal"></h4>
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
							{{-- <input type="hidden" class="form-control" id="activo" name="activo" value="0"> --}}
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

<!-- ============================================================== -->

<div id="modal_tipoproveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" style="min-width: 80%!important;">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post" name="form_tipoproveedor" id="form_tipoproveedor">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Tipo de proveedor</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						{!! csrf_field() !!}
						<div class="col-12">
							<input type="hidden" class="form-control" id="catTipoproveedor_id" name="id" value="0">
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Nombre tipo de proveedor *</label>
								<input type="text" class="form-control" id="catTipoproveedor_Nombre" name="catTipoproveedor_Nombre" required>
							</div>
						</div>
						<div class="col-12">
							<ol class="breadcrumb m-b-10">Información que necesita cargar en este tipo de proveedor</ol>
						</div>
						<div class="col-4" style="text-align: center;">
							<label class="demo-switch-title">Acreditaciones / Aprobaciones</label>
							<div class="switch">
								<label>
									<input type="checkbox" name="alcance[]" id="alcance_2" value="2">
									<span class="lever switch-col-light-blue"></span>
								</label>
							</div>
						</div>
						<div class="col-4" style="text-align: center;">
							<label class="demo-switch-title">Alcances (Factores de riesgos / Servicios)</label>
							<div class="switch">
								<label>
									<input type="checkbox" name="alcance[]" id="alcance_6" value="6">
									<span class="lever switch-col-light-blue"></span>
								</label>
							</div>
						</div>
						<div class="col-4" style="text-align: center;">
							<label class="demo-switch-title">Equipos</label>
							<div class="switch">
								<label>
									<input type="checkbox" name="alcance[]" id="alcance_3" value="3">
									<span class="lever switch-col-light-blue"></span>
								</label>
							</div>
						</div>
						<div class="col-4" style="text-align: center;">
							<label class="demo-switch-title">Signatarios</label>
							<div class="switch">
								<label>
									<input type="checkbox" name="alcance[]" id="alcance_4" value="4">
									<span class="lever switch-col-light-blue"></span>
								</label>
							</div>
						</div>
						<div class="col-4" style="text-align: center;">
							<label class="demo-switch-title">Precios $</label>
							<div class="switch">
								<label>
									<input type="checkbox" name="alcance[]" id="alcance_5" value="5">
									<span class="lever switch-col-light-blue"></span>
								</label>
							</div>
						</div>
						<div class="col-12">
							{{-- <input type="hidden" class="form-control" id="catTipoproveedor_Activo" name="catTipoproveedor_Activo" value="0"> --}}
							<input type="hidden" class="form-control" id="CatTipoproveedor_catalogo" name="catalogo" value="0">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_tipoproveedor">
						Guardar <i class="fa fa-save"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL CATALOGO -->
<!-- ============================================================== -->

{{-- ========================================================================= --}}
@endsection