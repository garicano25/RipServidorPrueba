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

    form label{
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
            <div class="card-body bg-info">
                <h4 class="text-white card-title"><i class="ti-user"></i> Mi perfil</h4>
                <h6 class="card-subtitle text-white">{{ auth()->user()->name }}</h6></div>
            <div class="card-body" style="border: 0px #f00 solid; min-height: 800px!important;">
                <div class="message-box contact-box">
                    <form enctype="multipart/form-data" method="post" name="form_usuarioperfil" id="form_usuarioperfil">
                        <div class="row">
                            <div class="col-12">
                                {!! csrf_field() !!}
                                <input type="hidden" class="form-control" id="usuario_id" name="usuario_id"  value="{{ auth()->user()->id }}">
                                <input type="hidden" class="form-control" id="usuario_tipo" name="usuario_tipo"  value="{{ auth()->user()->usuario_tipo }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Nombre usuario </label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Correo de acceso *</label>
                                            <input type="email" class="form-control" id="usuarioperfil_correo" name="usuarioperfil_correo" value="{{ auth()->user()->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Contraseña *</label>
                                            <input type="password" class="form-control" id="usuarioperfil_password" name="usuarioperfil_password" value="" onkeyup="verificapasswordperfil(this.value);" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Confirmar Contraseña *</label>
                                            <input type="password" class="form-control" id="usuarioperfil_password_2" name="usuarioperfil_password_2" value="" onkeyup="verificapasswordperfil(this.value);" required>
                                        </div>
                                    </div>
                                    <div class="col-12" style="text-align: center;" id="password_mensajeusuarioperfil"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label id="foto_titulo"> Foto perfil *</label>
                                            <style type="text/css" media="screen">
                                                .dropify-wrapper {
                                                    height: 325px!important; /*tamaño estatico del campo foto*/
                                                }
                                            </style>
                                            @php
                                                // $extencion = explode(".", auth()->user()->usuario_foto);
                                                // echo $extencion[1];
                                                // echo explode(".", auth()->user()->usuario_foto)[1];
                                            @endphp
                                            <input type="file" accept="image/jpeg,image/x-png" id="fotoperfil" name="fotoperfil" data-allowed-file-extensions="jpg png JPG PNG" data-height="300" data-default-file="{{route('usuariofoto', auth()->user()->id)}}.{{ explode(".", auth()->user()->usuario_foto)[1] }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn btn-danger" id="boton_guardarusuarioperfil">
                                        Guardar cambios <i class="fa fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
@endsection