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
	<div class="col-12">
		<div class="row">
			<div class="col-12">
				@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
				<ol class="breadcrumb mb-4 d-flex justify-content-between" style="padding: 6px; margin: 0px 0px 10px 0px; background: #94B732!important">
					<h3 class="text-light" style="font-weight: bold;">POBLACIÓN OCUPACIONALMENTE EXPUESTA</h3>
					<button type="button" class="btn btn-default waves-effect botoninforme" style="margin-left: 45%;" id="btnFinalizarPoe">
						<span class="btn-label"><i class="fa fa-check-square"></i></span>Bloquear y Finalizar POE
					</button>
				</ol>
				@else
				<ol class="breadcrumb" style="padding: 11px; margin: 0px 0px 10px 0px;">
					<h3 class="text-light" style="font-weight: bold;">POBLACIÓN OCUPACIONALMENTE EXPUESTA</h3>
				</ol>
				@endif

			</div>
			<div class="col-5">
				@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
				<ol class="breadcrumb" style="padding: 6px; margin: 0px 0px 10px 0px;">
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva categoría" id="boton_reporte_nuevacategoria">
						<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva categoría
					</button>
				</ol>
				@else
				<ol class="breadcrumb" style="padding: 11px; margin: 0px 0px 10px 0px;">
					CATEGORÍAS
				</ol>
				@endif
				<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_categorias">
					<thead>
						<tr>
							<th colspan="5"><b style="font-weight: 600;">TABLA CATEGORÍAS EN LA INSTALACIÓN</b></th>
						</tr>
						<tr>
							<th width="70">No. orden</th>
							<th width="">Categoría</th>
							<th width="50">Total</th>
							<th width="60">Editar</th>
							<th width="60">Eliminar</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="col-7">
				@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
				<ol class="breadcrumb" style="padding: 6px; margin: 0px 0px 10px 0px;">
					<button type="button" class="btn btn-default waves-effect botoninforme" data-toggle="tooltip" title="Nueva área" id="boton_reporte_nuevaarea">
						<span class="btn-label"><i class="fa fa-plus"></i></span>Nueva área POE
					</button>
					<button type="button" class="btn btn-success waves-effect boton_descarga_poe" style="float: right;" data-toggle="tooltip" title="Descargar tabla POE.docx">
						<span class="btn-label"><i class="fa fa-file-word-o"></i></span>Descargar tabla POE .docx
					</button>
				</ol>
				@else
				<ol class="breadcrumb" style="padding: 6px; margin: 0px 0px 10px 0px;">
					ÁREAS
					<button type="button" class="btn btn-success waves-effect boton_descarga_poe" style="float: right;" data-toggle="tooltip" title="Descargar tabla POE.docx">
						<span class="btn-label"><i class="fa fa-file-word-o"></i></span>Descargar tabla POE .docx
					</button>
				</ol>
				@endif
				<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areas">
					<thead>
						<tr>
							<th colspan="8"><b style="font-weight: 600;">TABLA POE (POBLACIÓN OCUPACIONALMENTE EXPUESTA)</b></th>
						</tr>
						<tr>
							<th width="70">No. orden</th>
							<th width="120">Instalación</th>
							<th width="120">Área</th>
							<th width="80">Operación</th>
							<th width="">Categoría</th>
							<th width="60">Total</th>
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


<!-- ============================================================== -->
<!-- MODAL-REPORTE-CATEGORIA -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
	#modal_reporte_categoria>.modal-dialog {
		min-width: 900px !important;
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
								<input type="text" class="form-control" id="reportecategoria_nombre" name="reportecategoria_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Total personal</label>
								<input type="number" min="1" class="form-control" id="reportecategoria_total" name="reportecategoria_total" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. orden</label>
								<input type="number" min="1" class="form-control" id="reportecategoria_orden" name="reportecategoria_orden" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_categoria">Guardar <i class="fa fa-save"></i></button>
					@endif
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
			<form method="post" enctype="multipart/form-data" name="form_modal_area" id="form_modal_area">
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
							<div class="form-group">
								<label>Instalación</label>
								<input type="text" class="form-control" id="reportearea_instalacion" name="reportearea_instalacion" required>
							</div>
						</div>
						<div class="col-8">
							<div class="form-group">
								<label>Nombre del área</label>
								<input type="text" class="form-control" id="reportearea_nombre" name="reportearea_nombre" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>No. orden del área</label>
								<input type="number" min="0" class="form-control" id="reportearea_orden" name="reportearea_orden" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>% de operacion</label>
								<input type="number" step="any" min="0" max="100" class="form-control" id="reportearea_porcientooperacion" name="reportearea_porcientooperacion" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<ol class="breadcrumb text-light" style="padding: 6px; margin: 0px 0px 10px 0px; text-align: center;">Categorías en el área</ol>
							<div style="margin: -20px 0px 0px 0px!important; padding: 0px!important;">
								<table class="table table-hover tabla_info_centrado" width="100%" id="tabla_areacategorias">
									<thead>
										<tr>
											<th width="64" style="padding: 3px 0px!important;">Activo</th>
											<th width="240" style="padding: 3px 0px!important;">Categoría</th>
											<th width="100" style="padding: 3px 0px!important;">Total</th>
											<th width="100" style="padding: 3px 0px!important;">GEH</th>
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
					@if(auth()->user()->hasRoles(['Superusuario', 'Administrador', 'Coordinador']))
					<button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_modal_area">Guardar <i class="fa fa-save"></i></button>
					@endif
				</div>
			</form>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- MODAL-REPORTE-ÁREA -->
<!-- ============================================================== -->


<script type="text/javascript">
	var proyecto = <?php echo json_encode($proyecto); ?>;
	var estatus = <?php echo json_encode($estatus); ?>;
	var recsensorial = <?php echo json_encode($recsensorial); ?>;
</script>
<script src="/js_sitio/reportes/reportepoe.js?v=1.0"></script>