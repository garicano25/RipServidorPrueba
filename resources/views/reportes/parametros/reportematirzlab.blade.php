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



<div class="row reporte_estructura">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <ol class="breadcrumb mb-4 d-flex justify-content-center" style="padding: 6px; margin: 0px 0px 10px 0px; background: #94B732!important">
                    <h3 class="text-light m-0" style="font-weight: bold;">MATRIZ DE RIESGO A LA SALUD</h3>
                </ol>
            </div>

            <div class="col-12">
                <table id="tabla_matrizlab" class="table table-bordered text-center align-middle" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Área de Trabajo Evaluada</th>
                            <th>Tipo de agente/Factor de Riesgo</th>
                            <th>No. de Trabajadores</th>
                            <th>Categorías</th>
                            <th>Tiempo de Exposición (minutos)</th>
                            <th>Índice de Peligro (IP)</th>
                            <th>Índice de Exposición (IE)</th>
                            <th>Riesgo / Prioridad de atención</th>
                            <th>Nivel Máximo<br>LMP-NMP</th>
                            <th>Cumplimiento<br>Normativo</th>
                            <th>Medidas de Control</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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




<script type="text/javascript">
    var proyecto = <?php echo json_encode($proyecto); ?>;
    var estatus = <?php echo json_encode($estatus); ?>;
    var recsensorial = <?php echo json_encode($recsensorial); ?>;
</script>
<script src="/js_sitio/reportes/reportematrizlab.js"></script>