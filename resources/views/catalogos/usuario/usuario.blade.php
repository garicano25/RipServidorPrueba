@extends('template/maestra')
@section('contenido')
{{-- ========================================================================= --}}

<style type="text/css" media="screen">
    table th {
        font-size: 12px !important;
        color: #777777 !important;
        font-weight: 600 !important;
    }

    table td {
        font-size: 12px !important;
    }

    form label {
        color: #999999;
    }
</style>

<div class="row page-titles">
    <div class="col-12 align-self-center">
        <div class="d-flex justify-content-end"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-secondary">
                <h4 class="text-white card-title">Usuarios del sistema</h4>
                <h6 class="card-subtitle text-white">Módulo usuarios</h6>
            </div>
            <div class="card-body" style="border: 0px #f00 solid; min-height: 992px!important;">
                <div class="message-box contact-box">
                    <ol class="breadcrumb m-b-10">
                        <button type="button" class="btn btn-secondary waves-effect waves-light" data-toggle="tooltip" title="Crear nuevo usuario" id="boton_nuevousuario">
                            <span class="btn-label"><i class="fa fa-plus"></i></span> Nuevo usuario
                        </button>
                    </ol>
                    <div class="table-responsive m-t-20">
                        <table class="table table-hover stylish-table" width="100%" id="tabla_usuarios">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="80">Foto</th>
                                    <th>Nombre / Cargo</th>
                                    <th width="280">Correo / Télefono</th>
                                    <th width="140">Tipo usuario</th>
                                    <th width="180">Perfil de accesos</th>
                                    <th width="80">Activo</th>
                                    <th width="70">Editar</th>
                                    <th width="70">Eliminar</th>
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
<!-- MODAL-USUARIO -->
<!-- ============================================================== -->
<style type="text/css" media="screen">
    #modal_usuario .modal-body .form-group {
        margin: 0px 0px 12px 0px !important;
        padding: 0px !important;
    }

    #modal_usuario .modal-body .form-group label {
        margin: 0px !important;
        padding: 0px 0px 3px 0px !important;
    }
</style>
<div id="modal_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="min-width: 1100px!important;">
        <form method="post" enctype="multipart/form-data" name="form_usuario" id="form_usuario">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        {!! csrf_field() !!}
                                        <div class="col-12">
                                            <input type="hidden" class="form-control" id="usuario_id" name="usuario_id" value="0">
                                            <input type="hidden" class="form-control" id="usuario_admin" name="usuario_admin" value="1">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Tipo *</label>
                                                <select class="custom-select form-control" id="usuario_tipo" name="usuario_tipo" onchange="tipousuario(this.value);" required>
                                                    <option value=""></option>
                                                    <option value="1">Usuario empleado</option>
                                                    <option value="2">Proveedor externo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 campo_dato_proveedor">
                                            <div class="form-group">
                                                <label>Proveedor *</label>
                                                <select class="custom-select form-control" id="proveedor_id" name="proveedor_id" onchange="select_proveedor(this.value, this.text);" required>
                                                    <option value=""></option>
                                                    @foreach($proveedores as $proveedor)
                                                    <option value="{{$proveedor->id}}">{{$proveedor->proveedor_NombreComercial}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 campo_dato_proveedor">
                                            <div class="form-group">
                                                <label id="tipousuario_nombrecampo">Nombre (proveedor) *</label>
                                                <input type="text" class="form-control" id="proveedor_nombre" name="proveedor_nombre" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 campo_dato_proveedor">
                                            <div class="form-group">
                                                <label>Correo de acceso *</label>
                                                <input type="email" class="form-control" id="proveedor_correo" name="proveedor_correo" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 campo_dato_empleado">
                                            <div class="form-group" id="campo_empleado_nombre">
                                                <label>Nombre (s) *</label>
                                                <input type="text" class="form-control" id="empleado_nombre" name="empleado_nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-12 campo_dato_empleado">
                                            <div class="form-group" id="campo_empleado_apellidopaterno">
                                                <label>Apellido paterno *</label>
                                                <input type="text" class="form-control" id="empleado_apellidopaterno" name="empleado_apellidopaterno" required>
                                            </div>
                                        </div>
                                        <div class="col-12 campo_dato_empleado">
                                            <div class="form-group" id="campo_empleado_apellidomaterno">
                                                <label>Apellido materno *</label>
                                                <input type="text" class="form-control" id="empleado_apellidomaterno" name="empleado_apellidomaterno" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label id="foto_titulo"> Foto usuario *</label>
                                                <style type="text/css" media="screen">
                                                    .dropify-wrapper {
                                                        height: 270px !important;
                                                        /*tamaño estatico del campo foto*/
                                                    }
                                                </style>
                                                <input type="file" accept="image/jpeg,image/x-png" id="fotousuario" name="fotousuario" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 campo_dato_empleado">
                                    <div class="form-group">
                                        <label>Dirección *</label>
                                        <input type="text" class="form-control" id="empleado_direccion" name="empleado_direccion" required>
                                    </div>
                                </div>
                                <div class="col-6 campo_dato_empleado">
                                    <div class="form-group">
                                        <label>Cargo *</label>
                                        <input type="text" class="form-control" id="empleado_cargo" name="empleado_cargo" required>
                                    </div>
                                </div>
                                <div class="col-6 campo_dato_empleado">
                                    <div class="form-group">
                                        <label>Teléfono *</label>
                                        <input type="number" class="form-control" id="empleado_telefono" name="empleado_telefono" required>
                                    </div>
                                </div>
                                <div class="col-6 campo_dato_empleado">
                                    <div class="form-group">
                                        <label>Fecha de nacimiento *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control mydatepicker" placeholder="aaaa-mm-dd" id="empleado_fechanacimiento" name="empleado_fechanacimiento" required>
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 campo_dato_empleado">
                                    <div class="form-group">
                                        <label>Correo de acceso *</label>
                                        <input type="email" class="form-control" id="empleado_correo" name="empleado_correo" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Contraseña *</label>
                                        <input type="password" class="form-control" id="password" name="password" onkeyup="verificapassword(this.value);" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Confirmar Contraseña *</label>
                                        <input type="password" class="form-control" id="password_2" name="password_2" onkeyup="verificapassword(this.value);" required>
                                    </div>
                                </div>
                                <div class="col-12" style="text-align: center;" id="password_mensaje"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <label>Modulos de acceso [roles] *</label>
                                    <div class="card">
                                        <div class="card-body" style="height: 572px; overflow-x: hidden; overflow-y: auto;">
                                            <style type="text/css">
                                                .tooltip-inner {
                                                    max-width: 320px;
                                                    /*tooltip tamaño*/
                                                    padding: 6px 8px;
                                                    color: #fff;
                                                    text-align: justify;
                                                    background-color: #000;
                                                    border-radius: 0.25rem;
                                                    line-height: 16px;
                                                }

                                                #rol_lista:hover label {
                                                    color: #000000;
                                                    font-weight: bold;
                                                }
                                            </style>
                                            <div class="row">
                                                @foreach($roles as $rol)
                                                @if (auth()->user()->hasRoles(['Superusuario']) && $rol->rol_Nombre == "Superusuario")
                                                <div class="col-12" id="rol_lista_{{$rol->id}}" data-toggle="tooltip" title="<li>{{ str_replace("*", ".</li><br><li>", $rol->rol_Descripcion) }}.</li>" data-html="true">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_rol" name="rol[]" id="rol_{{$rol->id}}" value="{{$rol->id}}">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;">{{$rol->rol_Nombre}}</label>
                                                </div>
                                                @endif


                                                @if ($rol->rol_Nombre != "Superusuario")
                                                <div class="col-12" id="rol_lista_{{$rol->id}}" data-toggle="tooltip" title="<li>{{ str_replace("*", ".</li><br><li>", $rol->rol_Descripcion) }}.</li>" data-html="true">
                                                    <div class="switch" style="float: left;">
                                                        <label>
                                                            <input type="checkbox" class="checkbox_rol" name="rol[]" id="rol_{{$rol->id}}" value="{{$rol->id}}">
                                                            <span class="lever switch-col-light-blue"></span>
                                                        </label>
                                                    </div>
                                                    <label class="demo-switch-title" style="float: left;">{{$rol->rol_Nombre}}</label>
                                                </div>
                                                @endif
                                                @endforeach
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
                    <button type="submit" class="btn btn-danger" id="boton_guardarusuario">
                        Guardar <i class="fa fa-save"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ============================================================== -->
<!-- MODAL-USUARIO -->
<!-- ============================================================== -->


<script type="text/javascript">
    var permiso = <?php echo json_encode($permiso); ?>;
    // alert(permiso);
</script>


{{-- ========================================================================= --}}
@endsection