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

    /* Contenedor general */
    .tabla-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    /* Tabla principal */
    .tabla-epp {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        table-layout: fixed;
        /* 🔥 Esto obliga a que TODAS las columnas tengan el mismo ancho */
    }

    /* Celdas */
    .tabla-epp th,
    .tabla-epp td {
        border: 2px solid black;
        padding: 10px;
        font-size: 14px;
    }

    /* Inputs */
    .entrada-epp {
        width: 100%;
        max-width: 80px;
        text-align: center;
        border: 1px solid #999;
        border-radius: 8px;
        padding: 4px;
    }

    /* 🔥 BORDES REDONDEADOS EN TODA LA TABLA */
    .tabla-epp tr:first-child th:first-child,
    .tabla-epp tr:first-child td:first-child {
        border-top-left-radius: 20px;
    }

    .tabla-epp tr:first-child th:last-child,
    .tabla-epp tr:first-child td:last-child {
        border-top-right-radius: 20px;
    }

    .tabla-epp tr:last-child th:first-child,
    .tabla-epp tr:last-child td:first-child {
        border-bottom-left-radius: 20px;
    }

    .tabla-epp tr:last-child th:last-child,
    .tabla-epp tr:last-child td:last-child {
        border-bottom-right-radius: 20px;
    }

    /* Ajustar título de columna */
    .titulo-columna {
        font-weight: bold;
        text-align: center;
        white-space: normal;
    }
</style>

<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body bg-secondary">
                <h4 class="text-white card-title">Catálogos</h4>
                <h6 class="card-subtitle text-white">Seguridad Industrial</h6>
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
                                    <td>EPP - NOM-017</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(1);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_2">
                                    <td>Región Anatómica</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(2);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_2"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_3">
                                    <td>Clave y EPP </td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(3);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_3"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_4">
                                    <td>Nombres de las marcas </td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(4);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_4"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_11">
                                    <td>Entidades</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(11);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_11"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_5">
                                    <td>Norma/estándar nacionales</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(5);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_5"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_6">
                                    <td>Norma/estándar internacionales</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(6);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_6"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_7">
                                    <td>Tallas</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(7);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_7"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_8">
                                    <td>Clasificación del Riesgo</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(8);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_8"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="tr_9">
                                    <td>Tipo de uso</td>
                                    <td>
                                        <a href="#" onclick="mostrar_catalogo(9);">
                                            <i class="fa fa-chevron-circle-right fa-3x text-secondary"
                                                id="cat_9"></i>
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
                <div class="message-box contact-box">
                    <h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">
                        <button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect"
                            data-toggle="tooltip" title="Nuevo registro" id="boton_nuevo_registro"><i
                                class="fa fa-plus"></i></button>
                    </h2>
                    <div class="table-responsive m-t-20" id="div_datatable">
                        <table class="table table-hover stylish-table" id="tabla_lista_registros" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th style="width: 90px!important;">Editar</th>
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
<!-- MODAL CATALOGO EPP-->
<!-- ============================================================== -->

<div id="modal_epp" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 86%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">EPP</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1_equipo" id="tab1_epp_info" role="tab">Información del EPP</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2_documentos" id="tab2_documentos_epp" role="tab">Documentos</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- tab 1 -->
                                <div class="tab-pane active" id="tab1_equipo" role="tabpanel">
                                    <div class="card-body">
                                        <style type="text/css" media="screen">
                                            .dropify-wrapper {
                                                height: 300px !important;
                                            }
                                        </style>
                                        <form enctype="multipart/form-data" method="post" name="form_epp" id="form_epp">
                                            <div class="row">
                                                <div class="col-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label> Foto del epp </label>
                                                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="FOTO_EPP" name="FOTO_EPP" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-10">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" class="form-control" id="ID_CAT_EPP" name="ID_CAT_EPP" value="0">
                                                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="1">

                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Región Anatómica *</label>
                                                                <select class="custom-select form-control" id="REGION_ANATOMICA_EPP" name="REGION_ANATOMICA_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    @foreach ($catregionanatomica as $region)
                                                                    <option value="{{ $region->ID_REGION_ANATOMICA }}">
                                                                        {{ $region->NOMBRE_REGION }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Clave y EPP *</label>
                                                                <select class="custom-select form-control" id="CLAVEYEPP_EPP" name="CLAVEYEPP_EPP" required>
                                                                    <option value="" selected>Seleccione una opción</option>

                                                                    @foreach ($catclaveyepp as $item)
                                                                    <option
                                                                        value="{{ $item->ID_CLAVE_EPP }}"
                                                                        data-region="{{ $item->REGION_ANATOMICA_ID }}"
                                                                        data-tipo="{{ $item->TIPO_RIESGO }}">
                                                                        {{ $item->CLAVE }}) {{ $item->EPP }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label> Tipo de Riesgos en Función de la Actividad del Trabajador *</label>
                                                                <textarea class="form-control" id="TIPO_RIESGO_EPP" name="TIPO_RIESGO_EPP" rows="3" readonly></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Nombre del EPP*</label>
                                                                <input type="text" class="form-control" id="NOMBRE_EPP" name="NOMBRE_EPP" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Marca *</label>
                                                                <select class="custom-select form-control" id="MARCA_EPP" name="MARCA_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    @foreach ($catmarcas as $marca)
                                                                    <option value="{{ $marca->ID_MARCAS_EPP }}">
                                                                        {{ $marca->NOMBRE_MARCA }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label> Modelo *</label>
                                                                <input type="text" class="form-control" id="MODELO_EPP" name="MODELO_EPP" required>
                                                            </div>
                                                        </div>

                                                        <!-- FRECUENCIA (tabla fija) -->
                                                        <div class="col-12 mb-3" id="TABLA_FRECUENCIA" style="display:none;">
                                                            <div class="tabla-wrapper">
                                                                <table class="tabla-epp">
                                                                    <tr>
                                                                        <th class="titulo-columna">Frecuencia<br>en Hz</th>
                                                                        <th class="text-center">125</th>
                                                                        <th class="text-center">250</th>
                                                                        <th class="text-center">500</th>
                                                                        <th class="text-center">1000</th>
                                                                        <th class="text-center">2000</th>
                                                                        <th class="text-center">3150</th>
                                                                        <th class="text-center">4000</th>
                                                                        <th class="text-center">6300</th>
                                                                        <th class="text-center">8000</th>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <!-- ATENUACIÓN (CON INPUTS) -->
                                                        <div class="col-12 mb-3" id="TABLA_ATENUACION" style="display:none;">
                                                            <div class="tabla-wrapper">
                                                                <table class="tabla-epp">
                                                                    <tr>
                                                                        <th class="titulo-columna">ATENUACIÓN<br>MEDIA EN dB</th>
                                                                        <td><input type="text" name="ATENUACION_125" id="ATENUACION_125" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_250" id="ATENUACION_250" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_500" id="ATENUACION_500" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_1000" id="ATENUACION_1000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_2000" id="ATENUACION_2000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_3150" id="ATENUACION_3150" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_4000" id="ATENUACION_4000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_6300" id="ATENUACION_6300" class="entrada-epp"></td>
                                                                        <td><input type="text" name="ATENUACION_8000" id="ATENUACION_8000" class="entrada-epp"></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <!-- DESVIACIÓN (CON INPUTS) -->
                                                        <div class="col-12 mb-3" id="TABLA_DESVIACION" style="display:none;">
                                                            <div class="tabla-wrapper">
                                                                <table class="tabla-epp">
                                                                    <tr>
                                                                        <th class="titulo-columna">DESVIACIÓN<br>ESTÁNDAR EN dB</th>
                                                                        <td><input type="text" name="DESVIACION_125" id="DESVIACION_125" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_250" id="DESVIACION_250" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_500" id="DESVIACION_500" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_1000" id="DESVIACION_1000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_2000" id="DESVIACION_2000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_3150" id="DESVIACION_3150" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_4000" id="DESVIACION_4000" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_6300" id="DESVIACION_6300" class="entrada-epp"></td>
                                                                        <td><input type="text" name="DESVIACION_8000" id="DESVIACION_8000" class="entrada-epp"></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>


                                                        <div id="DIV_ATENUACION_RUIDO" style="display:none;" class="col-12 mb-4">

                                                            <div style="
                                                                border: 2px solid #000; 
                                                                border-radius: 25px; 
                                                                padding: 20px;
                                                                margin-bottom: 20px;
                                                                display: flex;
                                                                flex-direction: column;
                                                                align-items: center;
                                                                text-align: center;">

                                                                <h5 style="font-weight: bold; margin-bottom: 15px;">
                                                                    Atenuación de frecuencias Altas (H) – Medias (M) – Bajas (L)
                                                                </h5>

                                                                <div class="row w-100">

                                                                    <div class="col-4 text-center">
                                                                        <label style="font-weight:bold;">H</label>
                                                                        <input type="text"
                                                                            id="ATENUACION_H"
                                                                            name="ATENUACION_H"
                                                                            class="form-control mt-2"
                                                                            placeholder="Ej. 36">
                                                                    </div>

                                                                    <div class="col-4 text-center">
                                                                        <label style="font-weight:bold;">M</label>
                                                                        <input type="text"
                                                                            id="ATENUACION_M"
                                                                            name="ATENUACION_M"
                                                                            class="form-control mt-2"
                                                                            placeholder="Ej. 34">
                                                                    </div>

                                                                    <div class="col-4 text-center">
                                                                     
                                                                    <label style="font-weight:bold;">L</label>
                                                                        <input type="text"
                                                                            id="ATENUACION_L"
                                                                            name="ATENUACION_L"
                                                                            class="form-control mt-2"
                                                                            placeholder="Ej. 33">
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div style="
                                                                border: 2px solid #000; 
                                                                border-radius: 25px; 
                                                                padding: 20px;
                                                                text-align: center;">

                                                                <h5 style="font-weight: bold; margin-bottom: 15px;">
                                                                    Valor de la reducción del ruido
                                                                </h5>

                                                                <div class="row w-100">

                                                                    <div class="col-6 text-center">
                                                                        <label style="font-weight:bold;">SNR</label>
                                                                        <input type="text"
                                                                            id="VALOR_SNR"
                                                                            name="VALOR_SNR"
                                                                            class="form-control mt-2"
                                                                            placeholder="Ej. 21">
                                                                    </div>

                                                                    <div class="col-6 text-center">
                                                                        <label style="font-weight:bold;">NRR</label>
                                                                        <input type="text"
                                                                            id="VALOR_NRR"
                                                                            name="VALOR_NRR"
                                                                            class="form-control mt-2"
                                                                            placeholder="Ej. 26">
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>



                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Cumple norma/estándar nacionales *</label>
                                                                <select class="custom-select form-control" id="CUMPLE_NORMA_NACIONALES" name="CUMPLE_NORMA_NACIONALES">
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Cumple norma/estándar internacionales *</label>
                                                                <select class="custom-select form-control" id="CUMPLE_NORMA_INTERNACIONALES" name="CUMPLE_NORMA_INTERNACIONALES">
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-12" id="NOM_NACIONALES_BOTON" style="display: none;">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarnomnacionales">
                                                                    Agregar norma/estándar nacionales<i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>


                                                        <div class="col-12" id="NOM_NACIONALES_DIV" style="display: none;">
                                                            <div class="listanomnacionales">
                                                            </div>
                                                        </div>





                                                        <div class="col-12" id="NOM_INTERNACIONALES_BOTON" style="display: none;">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarnominternacionales">
                                                                    Agregar norma/estándar internacionales<i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12" id="NOM_INTERNACIONALES_DIV" style="display: none;">
                                                            <div class="listanominternacionales">
                                                            </div>
                                                        </div>


                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarcertificacionesadicionales">
                                                                    Certificaciones adicionales <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="certificacionesadicionales">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Tipo y grado de protección que ofrecen </label>
                                                                <input type="text" class="form-control" id="TIPO_GRADO_EPP" name="TIPO_GRADO_EPP">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarcaracteristicasespecificas">
                                                                    Características específicas <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="listacaracteristicasespecificas">
                                                            </div>
                                                        </div>

                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label>Se fabrica con tallas *</label>
                                                                <select class="custom-select form-control" id="FABRICATALLAS_EPP" name="FABRICATALLAS_EPP">
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_TALLAS_EPP" class="col-9" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-5">
                                                                    <div class="form-group">
                                                                        <label>Tallas *</label>
                                                                        <select class="custom-select form-control" id="TALLAS_EPP" name="TALLAS_EPP[]" multiple>
                                                                            @foreach ($cattallas as $tallasepp)
                                                                            <option value="{{ $tallasepp->ID_TALLA }}">
                                                                                {{ $tallasepp->NOMBRE_TALLA }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-7">
                                                                    <div class="form-group">
                                                                        <label>Recomendaciones para seleccionar la talla del EPP </label>
                                                                        <input type="text" class="form-control" id="RECOMENDACIONES_TALLAS_EPP" name="RECOMENDACIONES_TALLAS_EPP">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Se adapta a trabajadores con discapacidad *</label>
                                                                <select class="custom-select form-control" id="TRABAJADORES_DISCAPACIDAD_EPP" name="TRABAJADORES_DISCAPACIDAD_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_DISCAPACIODAD" class="col-12" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label>Especifique de que forma </label>
                                                                        <input type="text" class="form-control" id="ESPECIFIQUE_FORMA_EPP" name="ESPECIFIQUE_FORMA_EPP">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Clasificación del Riesgo *</label>
                                                                <select class="custom-select form-control" id="CLASIFICACION_RIESGO_EPP" name="CLASIFICACION_RIESGO_EPP[]" multiple>
                                                                    @foreach ($catclasificacion as $clasificacionriesgo)
                                                                    <option value="{{ $clasificacionriesgo->ID_CLASIFICACION_RIESGO }}">
                                                                        {{ $clasificacionriesgo->CLASIFICACION_RIESGO }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>¿Cuál? </label>
                                                                <input type="text" class="form-control" id="CUAL_CLASIFICACION_EPP" name="CUAL_CLASIFICACION_EPP">
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Tipo de uso *</label>
                                                                <select class="custom-select form-control" id="TIPO_USO_EPP" name="TIPO_USO_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    @foreach ($cattipouso as $tipouso)
                                                                    <option value="{{ $tipouso->ID_TIPO_USO }}">
                                                                        {{ $tipouso->TIPO_USO }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarmaterialesutilizados">
                                                                    Materiales utilizados por el fabricante <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="materialesutilizadosfabricante">
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Parte del cuerpo expuesta *</label>
                                                                <input type="text" class="form-control" id="PARTE_EXPUESTA_EPP" name="PARTE_EXPUESTA_EPP" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="form-group">
                                                                <label>Recomendaciones de uso y manejo (Antes, Durante y Después) *</label>
                                                                <input type="text" class="form-control" id="RECOMENDACIONES_USO_EPP" name="RECOMENDACIONES_USO_EPP" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarestriccionesuso">
                                                                    Restricciones de uso <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="restriccionesdeuso">
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Requiere prueba de ajuste *</label>
                                                                <select class="custom-select form-control" id="REQUIERE_AJUSTE_EPP" name="REQUIERE_AJUSTE_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_REQUIERE_PRUEBA" class="col-8" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label>Especifique cuál</label>
                                                                        <input type="text" class="form-control" id="ESPECIFIQUE_AJUSTE_EPP" name="ESPECIFIQUE_AJUSTE_EPP">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-5">
                                                            <div class="form-group">
                                                                <label>Se puede utilizar en caso de emergencias *</label>
                                                                <select class="custom-select form-control" id="UTILIZAR_EMERGENCIA_EPP" name="UTILIZAR_EMERGENCIA_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-7">
                                                            <div class="form-group">
                                                                <label>Especifique *</label>
                                                                <input type="text" class="form-control" id="ESPECIFIQUE_EMERGENCIA_EPP" name="ESPECIFIQUE_EMERGENCIA_EPP" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Características de compatibilidad con otros EPPs</label>
                                                                <input type="text" class="form-control" id="COMPATIBILIDAD_EPPS" name="COMPATIBILIDAD_EPPS" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Características de incompatibilidad con otros EPPs</label>
                                                                <input type="text" class="form-control" id="INCOMPATIBILIDAD_EPPS" name="INCOMPATIBILIDAD_EPPS" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Requiere inspecciones internas *</label>
                                                                <select class="custom-select form-control" id="INSPECCION_INTERNA_EPP" name="INSPECCION_INTERNA_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_INSPECCION_INTERNA" class="col-8" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Frecuencia *</label>
                                                                        <input type="text" class="form-control" id="FRECUENCIA_INTERNA_EPP" name="FRECUENCIA_INTERNA_EPP">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Responsable *</label>
                                                                        <input type="text" class="form-control" id="RESPONSABLE_INTERNA_EPP" name="RESPONSABLE_INTERNA_EPP">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label>Requiere inspecciones externa (tercera parte) *</label>
                                                                <select class="custom-select form-control" id="INSPECCION_EXTERNA_EPP" name="INSPECCION_EXTERNA_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_INSPECCION_EXTERNA" class="col-8" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Frecuencia *</label>
                                                                        <input type="text" class="form-control" id="FRECUENCIA_EXTERNA_EPP" name="FRECUENCIA_EXTERNA_EPP">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label>Responsable *</label>
                                                                        <input type="text" class="form-control" id="RESPONSABLE_EXTERNA_EPP" name="RESPONSABLE_EXTERNA_EPP">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Aplica algún procedimiento de descontaminación *</label>
                                                                <select class="custom-select form-control" id="PROCEDIMIENTO_DESCONTAMINACION_EPP" name="PROCEDIMIENTO_DESCONTAMINACION_EPP" required>
                                                                    <option value selected="">Seleccione una opción</option>
                                                                    <option value="1">Sí</option>
                                                                    <option value="2">No</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="DIV_DESCONTAMINACION" class="col-6" style="display:none;">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label>Especifique Cuál *</label>
                                                                        <input type="text" class="form-control" id="DESCONTAMINACION_ESPECIFIQUE_EPP" name="DESCONTAMINACION_ESPECIFIQUE_EPP">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Vida útil recomendada por el fabricante *</label>
                                                                <input type="text" class="form-control" id="VIDA_UTIL_EPP" name="VIDA_UTIL_EPP" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Criterios para desechar o cambiar el EPP *</label>
                                                                <input type="text" class="form-control" id="CRITERIOS_DESECHAR_EPP" name="CRITERIOS_DESECHAR_EPP" required>
                                                            </div>
                                                        </div>


                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarecomendacionalmacenamiento">
                                                                    Recomendaciones para el almacenamiento <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="recomendacionalmacenamiento">
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarecomendacionlimpieza">
                                                                    Recomendaciones para la limpieza <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="recomendacionlimpieza">
                                                            </div>
                                                        </div>


                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-danger " id="botonagregarecomendaciondisposicionfinal">
                                                                    Recomendaciones para la disposición final <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="recomendaciondisposicionfinal">
                                                            </div>
                                                        </div>

                                                        <div class="col-12" style="text-align: right;">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-danger waves-effect waves-light botonguardar_moduloproveedores" id="boton_guardar_epp">
                                                                    Guardar <i class="fa fa-save"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!--tab 2 -->
                                <div class="tab-pane" id="tab2_documentos" role="tabpanel" style="display: none;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <ol class="breadcrumb m-b-10">
                                                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                                                    <button type="button" class="btn btn-block btn-outline-secondary" style="width: auto;" id="boton_nuevo_epp_documento">
                                                        <span class="btn-label"><i class="fa fa-plus"></i></span> Documento
                                                    </button>
                                                    @else
                                                    <h2 style="color: #ffff; margin: 0;"> Documento </h2>
                                                    @endif
                                                </ol><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover stylish-table" id="tabla_epp_documentos" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="50">No.</th>
                                                                <th width="800">Tipo de documento</th>
                                                                <th width="800">Documento</th>
                                                                <th width="70">Pdf</th>
                                                                <th width="70">Editar</th>
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
                        </div>
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
<!-- MODAL CATALOGO REGIÓN ANATÓMICA -->
<!-- ============================================================== -->

<div id="modal_regionanatomica" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_region_anatomica" id="form_region_anatomica">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Región Anatómica</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_REGION_ANATOMICA" name="ID_REGION_ANATOMICA" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la región anatómica *</label>
                                <input type="text" class="form-control" id="NOMBRE_REGION" name="NOMBRE_REGION" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="2">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_regionanatomica">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL CATALOGO CLAVE Y EPP-->
<!-- ============================================================== -->

<div id="modal_claveyepp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_claveyepp" id="form_claveyepp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Clave y EPP</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_CLAVE_EPP" name="ID_CLAVE_EPP" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Región anatómica *</label>
                                <select class="form-control" id="REGION_ANATOMICA_ID" name="REGION_ANATOMICA_ID" required>
                                    <option value="">Seleccione</option>

                                    @foreach ($catregionanatomica as $cat)
                                    <option value="{{ $cat->ID_REGION_ANATOMICA }}">
                                        {{ $cat->NOMBRE_REGION }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Clave *</label>
                                        <input type="text" class="form-control" id="CLAVE" name="CLAVE" required>
                                    </div>
                                </div>

                                <div class="col-9">
                                    <div class="form-group">
                                        <label>EPP *</label>
                                        <input type="text" class="form-control" id="EPP" name="EPP" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de riesgo en función de la actividad de la persona trabajadora *</label>
                                <textarea class="form-control" id="TIPO_RIESGO" name="TIPO_RIESGO" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nota</label>
                                <textarea class="form-control" id="NOTA_CLAVE" name="NOTA_CLAVE" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="3">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_claveyepp">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL CATALOGO MARCAS-->
<!-- ============================================================== -->

<div id="modal_marcas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_marcas" id="form_marcas">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Marca</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_MARCAS_EPP" name="ID_MARCAS_EPP" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la marca *</label>
                                <input type="text" class="form-control" id="NOMBRE_MARCA" name="NOMBRE_MARCA" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_marca">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL CATALOGO NORMAS NACIONALES-->
<!-- ============================================================== -->

<div id="modal_normasnacionales" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_normas_nacionales" id="form_normas_nacionales">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Norma/estándar nacional</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_NORMAS_NACIONALES" name="ID_NORMAS_NACIONALES" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Entidades *</label>
                                <select class="custom-select form-control" id="ENTIDAD_NACIONALES" name="ENTIDAD_NACIONALES" required>
                                    <option value selected="">Seleccione una opción</option>
                                    @foreach ($catentidades as $entidades)
                                    <option value="{{ $entidades->ID_ENTIDAD_EPP }}">
                                        {{ $entidades->NOMBRE_ENTIDAD }} - {{ $entidades->ENTIDAD_DESCRIPCION }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la norma/estándar *</label>
                                <input type="text" class="form-control" id="NOMBRE_NORMA_NACIONALES" name="NOMBRE_NORMA_NACIONALES" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción *</label>
                                <input type="text" class="form-control" id="DESCRIPCION_NORMA_NACIONALES" name="DESCRIPCION_NORMA_NACIONALES" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger " id="botonagregarnotasnacionales">
                                    Agregar nota <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="notasnomnacionales">
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger " id="botonagregarapartadonacionales">
                                    Apartado específico <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="apartadonomnacionales">
                            </div>
                        </div>


                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_normasnacionales">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL CATALOGO NORMAS INTERNACIONALES-->
<!-- ============================================================== -->

<div id="modal_normasinternacionales" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_normas_internacionales" id="form_normas_internacionales">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Norma/estándar internacionales</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_NORMAS_INTERNACIONALES" name="ID_NORMAS_INTERNACIONALES" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Entidades *</label>
                                <select class="custom-select form-control" id="ENTIDAD_INTERNACIONALES" name="ENTIDAD_INTERNACIONALES" required>
                                    <option value selected="">Seleccione una opción</option>
                                    @foreach ($catentidades as $entidades)
                                    <option value="{{ $entidades->ID_ENTIDAD_EPP }}">
                                        {{ $entidades->NOMBRE_ENTIDAD }} - {{ $entidades->ENTIDAD_DESCRIPCION }}
                                    </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de la norma/estándar *</label>
                                <input type="text" class="form-control" id="NOMBRE_NORMA_INTERNACIONALES" name="NOMBRE_NORMA_INTERNACIONALES" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción *</label>
                                <input type="text" class="form-control" id="DESCRIPCION_NORMA_INTERNACIONALES" name="DESCRIPCION_NORMA_INTERNACIONALES" required>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger " id="botonagregarnotasinternacionales">
                                    Agregar nota <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="notasnominternacional">
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger " id="botonagregarapartadointernacionales">
                                    Apartado específico <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="apartadonominternacional">
                            </div>
                        </div>




                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="6">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_normasinternacionales">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL TALLAS -->
<!-- ============================================================== -->

<div id="modal_tallas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_tallas" id="form_tallas">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Talla</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_TALLA" name="ID_TALLA" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Talla *</label>
                                <input type="text" class="form-control" id="NOMBRE_TALLA" name="NOMBRE_TALLA" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="7">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_tallas">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL CLASIFICACION DE RIESGO -->
<!-- ============================================================== -->

<div id="modal_clasificacionriesgo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_clasificacionriesgo" id="form_clasificacionriesgo">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Clasificación del riesgo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_CLASIFICACION_RIESGO" name="ID_CLASIFICACION_RIESGO" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Clasificación del riesgo *</label>
                                <input type="text" class="form-control" id="CLASIFICACION_RIESGO" name="CLASIFICACION_RIESGO" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="8">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_clasificacionriesgo">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL TIPO DE USO  -->
<!-- ============================================================== -->

<div id="modal_tipouso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_tipouso" id="form_tipouso">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modal_titulo">Tipo de uso</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_TIPO_USO" name="ID_TIPO_USO" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de uso *</label>
                                <input type="text" class="form-control" id="TIPO_USO" name="TIPO_USO" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="9">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_tipouso">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL ENTIDADES-->
<!-- ============================================================== -->

<div id="modal_entidades" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" name="form_entidades" id="form_entidades">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Entidad</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! csrf_field() !!}
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="ID_ENTIDAD_EPP" name="ID_ENTIDAD_EPP" value="0">
                            <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="11">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Entidad *</label>
                                <input type="text" class="form-control" id="NOMBRE_ENTIDAD" name="NOMBRE_ENTIDAD" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descripción normativa *</label>
                                <input type="text" class="form-control" id="ENTIDAD_DESCRIPCION" name="ENTIDAD_DESCRIPCION" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" id="boton_guardar_entidades">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL DOCUMENTOS HE IMAGENES -->
<!-- ============================================================== -->

<div id="modal_epp_documento" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form enctype="multipart/form-data" method="post" name="form_epp_documento" id="form_epp_documento">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Documentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {!! csrf_field() !!}

                            <input type="hidden" class="form-control" id="ID_EPP_DOCUMENTO" name="ID_EPP_DOCUMENTO" value="0">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tipo de documento *</label>
                                <select class="custom-select form-control" id="DOCUMENTO_TIPO" name="DOCUMENTO_TIPO" required>
                                    <option value=""></option>
                                    <option value="1">Documento</option>
                                    <option value="2">Imagen</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Nombre *</label>
                                <input type="text" class="form-control" id="NOMBRE_DOCUMENTO" name="NOMBRE_DOCUMENTO" required>
                            </div>
                        </div>



                        <div class="col-12" id="IMAGEN_DOCUMENTOS" style="display: none;">
                            <div class="form-group">
                                <label> Foto </label>
                                <input type="file" accept="image/jpeg,image/x-png,image/gif" id="FOTO_DOCUMENTO" name="FOTO_DOCUMENTO" data-allowed-file-extensions="jpg png JPG PNG" data-height="240" data-default-file="" />
                            </div>
                        </div>


                        <div class="col-12" id="PDF_DOCUMENTOS" style="display: none;">

                            <div class="form-group">
                                <label> Soporte PDF *</label>
                                <!-- <input type="file" id="input-file-now" class="dropify"/> -->
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput" id="input_file_equipo_documento">
                                        <i class="fa fa-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-secondary btn-file">
                                        <span class="fileinput-new">Seleccione</span>
                                        <span class="fileinput-exists">Cambiar</span>
                                        <input type="file" accept="application/pdf" name="EPP_PDF" id="EPP_PDF">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-secondary fileinput-exists" data-dismiss="fileinput">Quitar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <input type="hidden" class="form-control" id="catalogo" name="catalogo" value="10">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    @if(auth()->user()->hasRoles(['Superusuario', 'Administrador','Coordinador']))
                    <button type="submit" class="btn btn-danger waves-effect waves-light " id="boton_guardar_epp_documento">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


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



<script>
    window.catnormasnacionales = @json($catnormasnacionales);
    window.catnormasinternacionales = @json($catnormasinternacionales);
</script>



@endsection