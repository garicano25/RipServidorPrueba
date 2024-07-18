@extends('template/maestra')
@section('contenido')


<div class="row page-titles"></div>



<div class="row">
    <div class="col-xl-4 col-lg-12">
        <div class="row">
            <div class="col-xl-12 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div style="height: 214px; text-align: center;" id="div_grafica_proyectos_periodo"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div style="height: 214px; text-align: center;" id="div_grafica_reconocimientos_periodo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div style="height: 500px; text-align: center;" id="div_grafica_proyectos_detalles"></div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center" style="font-size: 24px;">Detalles de los proyectos del periodo {{ date('Y') }}</h4>
                {{-- <h6 class="card-subtitle">Numero de reconocimientos sensoriales por periodo.</h6> --}}
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-xl-4 col-lg-12 col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div style="height: 560px; text-align: center;" id="div_grafica_proyectos_pastel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Lista de proyectos periodo ({{ date('Y') }})</h4>
                <div style="height: 546px; text-align: center; border: 0px #f00 solid; margin-top: -20px!important;" id="div_tabla_proyectos">
                    <style type="text/css">
                        .tabla_proyectos_actuales th {
                            background: #F9F9F9;
                            border: 1px #E5E5E5 solid !important;
                            padding: 2px 0px !important;
                            text-align: center;
                            vertical-align: middle !important;
                            font-size: 0.65vw !important;
                            color: #777777;
                        }

                        .tabla_proyectos_actuales td {
                            font-size: 0.65vw !important;
                            padding: 4px 2px !important;
                            text-align: center;
                            vertical-align: middle !important;
                        }
                    </style>
                    <table class="table table-hover tabla_proyectos_actuales" width="100%" id="tabla_proyectos_actuales">
                        <thead>
                            <tr>
                                <th width="50">No.</th>
                                <th width="100">Folio</th>
                                <th width="">Instalación</th>
                                <th width="100">Inicio prog.</th>
                                <th width="100">Fin prog.</th>
                                <th width="90">Prorrogas</th>
                                <th width="100">Fecha final prorroga</th>
                                <th width="100">Fecha entrega real</th>
                                <th width="80">Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection