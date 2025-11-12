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

    #DEPARTAMENTO_MEL {
        text-align-last: center;
        /* centra el texto dentro del select */
    }

    /* Responsivo */
    @media (max-width: 768px) {
        #DEPARTAMENTO_MEL {
            width: 90% !important;
        }
    }
</style>





<div class="row reporte_estructura">
    <div class="col-12">
        <div class="row">
            <!-- Título -->
            <div class="col-12">
                <ol class="breadcrumb mb-4 d-flex justify-content-center"
                    style="padding: 6px; margin: 0px 0px 10px 0px; background: #94B732!important">
                    <h3 class="text-light m-0" style="font-weight: bold;">Matriz de Exposición Laboral</h3>
                </ol>
            </div>
            <form method="post" enctype="multipart/form-data"
                name="form_reporte_portada" id="form_reporte_portada"
                class="col-12 text-center">
                {!! csrf_field() !!}

                <div class="mb-4">
                    <label class="form-label fw-bold mb-2 d-block">
                        Seleccione el departamento
                    </label>
                    <select class="custom-select form-control mx-auto text-center"
                        id="DEPARTAMENTO_MEL" name="DEPARTAMENTO_MEL"
                        style="width: 40%; min-width: 280px; max-width: 500px;">
                    </select>
                </div>

                <div class="text-center mt-3">
                    <button type="submit"
                        class="btn btn-danger waves-effect waves-light botoninforme"
                        id="botonguardar_reporte_matriz">
                        Guardar <i class="fa fa-save"></i>
                    </button>

                    <button type="button"
                        class="btn btn-default waves-effect"
                        style="margin-left: 15px;"
                        data-toggle="tooltip" title="Generar matriz"
                        id="boton_reporte_matriz">
                        <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>
                        Generar matriz
                    </button>
                </div>
            </form>
            <div class="col-12 mt-3">
                <div style="overflow-x: auto; width: 100%;">
                    <table id="tabla_mel_draft"
                        class="table table-bordered text-center align-middle"
                        style="font-size: 13px; table-layout: fixed; min-width: 1200px;">
                        <thead>
                            <tr>
                                <th class="text-center">Contador</th>
                                <th class="text-center">Departamento</th>
                                <th class="text-center">Instalación</th>
                                <th class="text-center">Área de<br>referencia<br>en atlas<br>de riesgo</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Ficha</th>
                                <th class="text-center">Categoría</th>
                                <th class="text-center">Edad (años)</th>
                                <th class="text-center">Antigüedad General (años)</th>
                                <th class="text-center">Antigüedad en la categoría (años)</th>
                                <th class="text-center">Horario de trabajo</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Referencia (VLE)</th>
                                <th class="text-center">Resultado (Concentración medida del ambiente)</th>
                                <th class="text-center">Cumplimiento normativo</th>
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
<script src="/js_sitio/reportes/reportemeldraft.js?v=1.4"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>