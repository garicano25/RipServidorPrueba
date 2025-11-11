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
                    <h3 class="text-light m-0" style="font-weight: bold;">Matriz de Exposición Laboral</h3>
                </ol>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-danger waves-effect waves-light botoninforme" id="botonguardar_reporte_matriz">
                    Guardar matriz <i class="fa fa-save"></i>
                </button>

                <button type="button" class="btn btn-default waves-effect" style="margin-left: 15px;" data-toggle="tooltip" title="Generar matriz" id="boton_reporte_matriz">
                    <span class="btn-label"><i class="fa fa-file-excel-o"></i></span> Generar matriz
                </button>
            </div>

            <div class="col-12">
                <div style="overflow-x: auto; width: 100%;">
                    <table id="tabla_mel_draft" class="table table-bordered text-center align-middle" style="font-size: 13px; table-layout: fixed; min-width: 1200px;">
                        <thead>
                            <tr>
                                <th width="60">Contador</th>
                                <th>Instalación</th>
                                <th>Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                <th>Nombre</th>
                                <th width="70">Ficha</th>
                                <th>Categoría</th>
                                <th width="60">Número de<br>personas</th>
                                <th width="80">Grupo de<br>exposición<br>homogénea</th>
                                <th width="100">Agentes químicos<br>evaluados</th>
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
<script src="/js_sitio/reportes/reportemeldraft.js?v=1.0"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>