<style type="text/css">
    .reporte_estructura {
        font-size: 14px !important;
        line-height: 14px !important;
    }






    #tabla_matrizlab select {
        min-width: 100px;
        padding: 4px 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: white;
        appearance: auto;
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
            <div class="col-12" style="text-align: center;">
                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_matriz">Guardar matriz <i class="fa fa-save"></i></button>
            </div>
            <div class="col-12">
                <div style="overflow-x: auto; width: 100%;">
                    <table id="tabla_matrizlab" class="table table-bordered text-center align-middle" style="font-size: 13px; table-layout: fixed; min-width: 1200px;">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 56px;" class="text-center">No.</th>
                                <th style="min-width: 170px;" class="text-center">Área de Trabajo Evaluada</th>
                                <th style="min-width: 213px;" class="text-center">Tipo de agente/Factor de Riesgo</th>
                                <th style="min-width: 213px;" class="text-center">No. de Trabajadores</th>
                                <th style="min-width: 213px;" class="text-center">Categorías</th>
                                <th style="min-width: 213px;" class="text-center">Tiempo de Exposición (minutos)</th>
                                <th style="min-width: 213px;" class="text-center">Índice de Peligro (IP)</th>
                                <th style="min-width: 213px;" class="text-center">Índice de Exposición (IE)</th>
                                <th style="min-width: 213px;" class="text-center">Riesgo / Prioridad de atención</th>
                                <th style="min-width: 213px;" class="text-center">Nivel Máximo<br>LMP-NMP</th>
                                <th style="min-width: 213px;" class="text-center">Cumplimiento<br>Normativo</th>
                                <th style="min-width: 652px;" class="text-center">Medidas de Control</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    var proyecto = <?php echo json_encode($proyecto); ?>;
    var estatus = <?php echo json_encode($estatus); ?>;
    var recsensorial = <?php echo json_encode($recsensorial); ?>;
</script>
<script src="/js_sitio/reportes/reportematrizlab.js?v=1.3"></script>