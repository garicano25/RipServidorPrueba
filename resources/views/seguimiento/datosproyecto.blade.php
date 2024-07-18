@extends('template/maestra')
@section('contenido')


{{-- ========================================================================= --}}


<style type="text/css" media="screen">
    table th{
        font-size: 12px!important;
        color:#777777!important;
        font-weight: 600!important;
    }

    table td{
        font-size: 12px!important;
    }

    table td i{
        font-size: 20px!important;
        margin: -1px 0px 0px 0px;
    }

    table td b{
        font-weight: 600!important;
    }
    
    form label{
        color: #999999;
    }

    table td i{
        font-size: 20px!important;
        margin: -1px 0px 0px 0px;
    }

    h4 b{
        color: #0BACDB;
    }

    /*Tabla asignar proveedores*/

    .round{
        width: 42px;
        height: 42px;
        padding: 0px!important;
    }

    .round i{
        margin: 0px!important;
        font-size: 16px!important;
    }
</style>


<div class="row page-titles">
    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor">Proyecto</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Lista</a></li>
            <li class="breadcrumb-item active">No. XXXXXX</li>
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


<div class="row">
    <div class="col-lg-2 col-xl-3 col-md-3">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h2 class="card-title text-white"><b>{{$proyecto->proyecto_folio}}</b></h2>
                                <h4 class="card-subtitle text-white">Datos generales</h4>
                            </div>
                            <div class="card-body">
                                
                                <div>
                                    <p><b class="text-info">Fecha Inicio: </b> {{$proyecto->proyecto_fechainicio}}</p>
                                    <p><b class="text-info">Fecha Entrega: </b> {{$proyecto->proyecto_fechainicio}}</p>
                                </div>


                                @if($proyecto->prorrogas->count('id') > 0)
                                    <h4 class="card-title mt-4">Reprogramación</h4>
                                @endif
                                <div class="list-group">
                                @foreach($proyecto->prorrogas as $prorroga)

                                
                                
                                    <a href="javascript:void(0)" class="list-group-item">
                                         Inicio: [{{ $prorroga->proyectoprorrogas_fechainicio }}]  <br>Entrega: [{{ $prorroga->proyectoprorrogas_fechafin }}] <br>Duracion: [{{ $prorroga->proyectoprorrogas_dias }}]
                                    </a>
                                
                                @endforeach
                                </div>
                                <h4 class="card-title mt-4">Descargas</h4>
                                
                                <div class="border-bottom p-1">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Sharemodel" style="width: 100%">
                                         <i class="fa fa-table"></i> Descargar Ficha
                                    </button>
                                </div>
                                <div class="border-bottom p-1">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Sharemodel" style="width: 100%">
                                         <i class="fa fa-calendar"></i> Descargar programa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
    <div class="col-lg-9">
        <div class="card">
                <div class="card-header bg-info">
                        {!! csrf_field() !!}
                        {{ method_field('PUT') }}
                        <input type="hidden" id="id_proyecto" name="id_proyecto" value="{{$proyecto?$proyecto->id:0}}">
                        <input type="hidden" id="id_ordentrabajo" name="id_ordentrabajo" value="{{$ordentrabajo?$ordentrabajo->id:0}}">
                        <input type="hidden" id="proyecto_fechainicio" name="proyecto_fechainicio" class="form-control" value = "{{$proyecto->proyecto_fechainicio}}">
                        <input type="hidden" id="proyecto_fechaentrega" name="proyecto_fechaentrega" class="form-control" value = "{{$proyecto->proyecto_fechaentrega}}">
                    <h2 class="text-white card-title">Programa de actividades</h2>
                    <h4 class="card-subtitle text-white">{{$proyecto->proyecto_clienteinstalacion}}</h4>
                </div>
        </div>
        <div class="card">
            <div class="card-header bg-success">
                
                        <h4 class="mb-0 text-white">01 Proceso de gestión</h4>
                    
                    
                </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12" id="BusquedaGestion">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
            
                            <table class="table stylish-table" width="100%" id="tabla_detalleProyectosGestion" data-field-id="{{$ordentrabajo?$ordentrabajo->id:0}}">
                                <thead>
                                    <tr>
                                        <th style="width:2%!important;">#</th>
                                        <th style="width:35%!important;">Actividad</th>
                                        <th style="width:5%!important;">Programa</th>
                                        <th style="width:5%!important;">Real</th>
                                        <th style="width:35%!important;">Responsable</th>
                                        <th style="width:5%!important;">Concluido</th>
                                        <th style="width: 8%!important;">Estado </th>
                                        <th style="width: 5%!important;">Guardar</th>
                                    </tr>
                                </thead>
                                <tbody class="font-weight">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection