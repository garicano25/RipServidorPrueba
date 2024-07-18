var datatable_caracteristicas = null;
var datatable_catalogo = null;
var datatable_catalogo2campos = null;
var datatable_agentes = null;
var datatable_contratos = null;
var datatable_epp = null;
var datatable_cargos = null;
var datatable_formatos = null;
var datatable_conclusiones = null;
var datatable_descripcion = null;

var catalogo = 0;

// Load CATALOGOS PROVEEDORES
$(document).ready(function()
{
	mostrar_catalogo(10);

    // Inicializar tooltip
    $('[data-toggle="tooltip"]').tooltip();
});


function mostrar_catalogo(num_catalogo)
{
    catalogo = parseInt(num_catalogo);

    // Desactivar ACTIVE menu catalogos
    $.each( $('#tabla_lista_catalogos tr'), function( key, value )
    {
        $(this).removeClass('active');
        $("#cat_"+(key+1)).removeClass("text-info");
        $("#cat_" + (key + 1)).addClass("text-secondary");

        
    });
    
   	switch (catalogo)
   	{
        case 1:
            // nombre de los campos
            $("#nombre").attr('name', 'catcontrato_numero');
            $("#activo").attr('name', 'catcontrato_activo');
            
            // activar menu
            $("#titulo_tabla").html('Catálogo [Contrato]');
            $("#modal_titulo").html("Catálogo [Contrato]");
            $("#tr_1").addClass("active");
			$("#cat_1").addClass("text-info");

            // Inicializar tabla
            if(datatable_contratos != null)
            {
                datatable_contratos.destroy();
                datatable_contratos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_contratos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Contrato / Empresa</th>'+
                                                '<th>Inicio / Fin</th>'+
                                                '<th>Montos</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_contratos(catalogo);
            break;
        case 2:
            // nombre de los campos
        	$("#nombre").attr('name', 'catregion_nombre');
            $("#activo").attr('name', 'catregion_activo');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Región]');
            $("#modal_titulo").html("Catálogo [Región]");
            $("#tr_2").addClass("active");
			$("#cat_2").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo != null)
            {
                datatable_catalogo.destroy();
                datatable_catalogo = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Región</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo(catalogo);
            break;
        case 3:
            // campos
        	$("#catalogo2campos_campo1").attr('name', 'catgerencia_siglas');
            $("#catalogo2campos_campo2").attr('name', 'catgerencia_nombre');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Gerencia]');
            $("#modal_catalogo2campos .modal-title").html("Catálogo [Gerencia]");
            $("#tr_3").addClass("active");
			$("#cat_3").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo2campos != null)
            {
                datatable_catalogo2campos.destroy();
                datatable_catalogo2campos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_catalogo2campos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th width="130">Siglas</th>'+
                                                '<th>Gerencia</th>'+
                                                '<th width="90">Editar</th>'+
                                                '<th width="90">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo2campos(catalogo);
            break;
        case 4:
            // campos
            $("#catalogo2campos_campo1").attr('name', 'catactivo_siglas');
            $("#catalogo2campos_campo2").attr('name', 'catactivo_nombre');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Activo]');
            $("#modal_catalogo2campos .modal-title").html("Catálogo [Activo]");
            $("#tr_4").addClass("active");
            $("#cat_4").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo2campos != null)
            {
                datatable_catalogo2campos.destroy();
                datatable_catalogo2campos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_catalogo2campos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th width="130">Siglas</th>'+
                                                '<th>Activo</th>'+
                                                '<th width="90">Editar</th>'+
                                                '<th width="90">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo2campos(catalogo);
            break;
        case 5:
            // campos
			$("#nombre").attr('name', 'catdepartamento_nombre');
			$("#activo").attr('name', 'catdepartamento_activo');

            // activa menu
			$("#titulo_tabla").html('Catálogo [Departamento]');
            $("#modal_titulo").html("Catálogo [Departamento]");
			$("#tr_5").addClass("active");
			$("#cat_5").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo != null)
            {
                datatable_catalogo.destroy();
                datatable_catalogo = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Departamento</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo(catalogo);
            break;
        case 6:
            // campos
            $("#nombre").attr('name', 'catpartecuerpo_nombre');
            $("#activo").attr('name', 'catpartecuerpo_activo');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Parte del cuerpo EPP]');
            $("#modal_titulo").html("Catálogo [Parte del cuerpo EPP]");
            $("#tr_6").addClass("active");
            $("#cat_6").addClass("text-info");

            // Inicializar tabla
            if(datatable_epp != null)
            {
                datatable_epp.destroy();
                datatable_epp = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo_epp" width="100%">'+
                                        '<thead>'+
                                            '<tr>' +
                                                '<th>Parte del cuerpo</th>' +
                                                '<th>Clave y EPP</th>'+
                                                '<th>Tipo de riesgo en funcion de la actividad</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo_epp(catalogo);
            break;
        case 7:
            // campos
            $("#nombre").attr('name', 'catparametroalimentocaracteristica_caracteristica');
            $("#activo").attr('name', 'catparametroalimentocaracteristica_activo');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Características de los alimentos]');
            $("#modal_titulo").html("Catálogo [Características de los alimentos]");
            $("#tr_7").addClass("active");
            $("#cat_7").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo != null)
            {
                datatable_catalogo.destroy();
                datatable_catalogo = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Características de los alimentos</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo(catalogo);
            break;
        case 8:
            // campos
            $("#tipo").attr('name', 'catparametroaguacaracteristica_tipo');
            $("#tipo").html('<option value=""></option>'+
                            '<option value="Microbiológico">Microbiológico</option>'+
                            '<option value="Fisicoquímico">Fisicoquímico</option>');

            $("#caracteristica").attr('name', 'catparametroaguacaracteristica_caracteristica');

            // menu
            $("#titulo_tabla").html('Catálogo [Características del agua]');
            $("#modal_titulo").html("Catálogo [Características del agua]");
            $("#tr_8").addClass("active");
            $("#cat_8").addClass("text-info");

            // Inicializar tabla
            if(datatable_caracteristicas != null)
            {
                datatable_caracteristicas.destroy();
                datatable_caracteristicas = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-bordered table-hover" id="tabla_lista_caracteristica" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Tipo</th>'+
                                                '<th>Característica</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');
            
            tabla_caracteristica(catalogo);
            break;
        case 9:
            // campos
            $("#nombre").attr('name', 'catparametrocalidadairecaracteristica_caracteristica');
            $("#activo").attr('name', 'catparametrocalidadairecaracteristica_activo');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Caracteristicas ventilación y calidad del aire]');
            $("#modal_titulo").html("Catálogo [Caracteristicas ventilación y calidad del aire]");
            $("#tr_9").addClass("active");
            $("#cat_9").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo != null)
            {
                datatable_catalogo.destroy();
                datatable_catalogo = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Caracteristicas ventilación y calidad del aire</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo(catalogo);
            break;
        case 10:
            // Inicializar tabla
            if(datatable_agentes != null)
            {
                datatable_agentes.destroy();
                datatable_agentes = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_agentes" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Tipo</th>'+
                                                '<th>Agente / Factor de riesgo / Servicio</th>'+
                                                '<th id="titulo_columna_tabla">Normas y/o procedimientos</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            $("#titulo_tabla").html('Catálogo [Agente / Factor de riesgo / Servicio]');
            $("#modal_titulo").html("Catálogo [Agente / Factor de riesgo / Servicio]");
            $("#tr_10").addClass("active");
            $("#cat_10").addClass("text-info");
            tabla_agentes(catalogo);
            break;
        case 11:
            // campos
            $("#nombre").attr('name', 'catparametrosuperficiecaracteristica_caracteristica');
            $("#activo").attr('name', 'catparametrosuperficiecaracteristica_activo');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Características de las superficies]');
            $("#modal_titulo").html("Catálogo [Características de las superficies]");
            $("#tr_11").addClass("active");
            $("#cat_11").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo != null)
            {
                datatable_catalogo.destroy();
                datatable_catalogo = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_catalogo" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Características de las superficies</th>'+
                                                '<th style="width: 70px!important;">Editar</th>'+
                                                '<th style="width: 70px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo(catalogo);
            break;
        case 12:
            // campos
            $("#tipo").attr('name', 'catparametrohielocaracteristica_tipo');
            $("#tipo").html('<option value=""></option>'+
                            '<option value="Microbiológico">Microbiológico</option>'+
                            '<option value="Fisicoquímico">Fisicoquímico</option>');

            $("#caracteristica").attr('name', 'catparametrohielocaracteristica_caracteristica');

            // menu
            $("#titulo_tabla").html('Catálogo [Características del hielo]');
            $("#modal_titulo").html("Catálogo [Características del hielo]");
            $("#tr_12").addClass("active");
            $("#cat_12").addClass("text-info");

            // Inicializar tabla
            if(datatable_caracteristicas != null)
            {
                datatable_caracteristicas.destroy();
                datatable_caracteristicas = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-bordered table-hover" id="tabla_lista_caracteristica" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Tipo</th>'+
                                                '<th>Característica</th>'+
                                                '<th style="width: 70px!important;">Editar</th>'+
                                                '<th style="width: 70px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');
            
            tabla_caracteristica(catalogo);
            break;
        case 13:
            // campos
            $("#catalogo2campos_campo1").attr('name', 'catsubdireccion_siglas');
            $("#catalogo2campos_campo2").attr('name', 'catsubdireccion_nombre');

            // activa menu
            $("#titulo_tabla").html('Catálogo [Subdirección]');
            $("#modal_catalogo2campos .modal-title").html("Catálogo [Subdirección]");
            $("#tr_13").addClass("active");
            $("#cat_13").addClass("text-info");

            // Inicializar tabla
            if(datatable_catalogo2campos != null)
            {
                datatable_catalogo2campos.destroy();
                datatable_catalogo2campos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_catalogo2campos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th width="130">Siglas</th>'+
                                                '<th>Subdirección</th>'+
                                                '<th width="90">Editar</th>'+
                                                '<th width="90">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_catalogo2campos(catalogo);
            break;
        case 14:
            // activa menu
            $("#titulo_tabla").html('Catálogo [Cargos para Informes]');
            $("#tr_14").addClass("active");
			$("#cat_14").addClass("text-info");

            // Inicializar tabla
            if(datatable_cargos != null)
            {
                datatable_cargos.destroy();
                datatable_cargos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_cargos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Cargo</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_cargo(catalogo);
                  break;
         case 15:
            // activa menu
            $("#titulo_tabla").html('Catálogo [Formatos de campo]');
            $("#tr_15").addClass("active");
			$("#cat_15").addClass("text-info");

            // Inicializar tabla
            if(datatable_formatos != null)
            {
                datatable_formatos.destroy();
                datatable_formatos = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_formatos" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Nombre</th>' +
                                                '<th>Descripción</th>'+
                                                '<th style="width: 90px!important;">Descargar</th>'+
                                                '<th style="width: 90px!important;">Ver</th>' +
                                                '<th style="width: 90px!important;">Editar</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_formatos(catalogo);
            break;
        case 16:
            // activa menu
            $("#titulo_tabla").html('Catálogo [Conclusiones para Informes]');
            $("#tr_16").addClass("active");
			$("#cat_16").addClass("text-info");

            // Inicializar tabla
            if(datatable_conclusiones != null)
            {
                datatable_conclusiones.destroy();
                datatable_conclusiones = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_conclusiones" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Nombre</th>' +
                                                '<th>Descripción</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_conclusiones(catalogo);
        break;

        case 17:
            // activa menu
            $("#titulo_tabla").html('Catálogo [Descripciones Área]');
            $("#tr_17").addClass("active");
			$("#cat_17").addClass("text-info");

            // Inicializar tabla
            if(datatable_descripcion != null)
            {
                datatable_descripcion.destroy();
                datatable_descripcion = null;
            }

            // diseño tabla
            $("#div_datatable").html('<table class="table table-hover stylish-table" id="tabla_lista_descripcion" width="100%">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Descripción</th>'+
                                                '<th style="width: 90px!important;">Editar</th>'+
                                                '<th style="width: 90px!important;">Activo</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody></tbody>'+
                                    '</table>');

            tabla_descripcion(catalogo);
        break;
    }
}


$("#boton_nuevo_registro").click(function()
{
    switch (catalogo)
    {
        case 1:
            // Borrar formulario
            $('#form_contrato').each(function(){
                this.reset();
            });

            // campos hidden
            $("#contrato_id").val(0);
            $("#contrato_catalogo").val(catalogo);

            // abrir modal
            $('#modal_contrato').modal({backdrop:false});
            break;
        case 3:
            // Borrar formulario
            $('#form_catalogo2campos').each(function(){
                this.reset();
            });

            // campos hidden
            $("#catalogo2campos_id").val(0);
            $("#catalogo2campos_catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo2campos').modal({backdrop:false});
            break;
        case 4:
            // Borrar formulario
            $('#form_catalogo2campos').each(function(){
                this.reset();
            });

            // campos hidden
            $("#catalogo2campos_id").val(0);
            $("#catalogo2campos_catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo2campos').modal({backdrop:false});
            break;
        case 6:
            // Borrar formulario
            $('#form_catalogo_epp').each(function(){
                this.reset();
            });

            // campos hidden
            $("#ID_PARTESCUERPO_DESCRIPCION").val(0);
            $("#catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo_epp').modal({backdrop:false});
            break;
        case 8:
            // Borrar formulario
            $('#form_caracteristica').each(function(){
                this.reset();
            });

            // campos hidden
            $("#caracteristica_id").val(0);
            $("#caracteristica_catalogo").val(catalogo);

            // abrir modal
            $('#modal_caracteristica').modal({backdrop:false});
            break;
        case 10:
            // Borrar formulario
            $('#form_agentes').each(function(){
                this.reset();
            });

            // campos hidden
            $("#agente_id").val(0);
            $("#agente_catalogo").val(catalogo);

            // Tabla lista de normas y procedimeintos en el MODAL
            $("#tabla_lista_normas tbody").html('');
            $("#tabla_lista_normas tbody").append('<tr>'+
                                                    '<td><select class="custom-select form-control" name="tipo[]" required><option value=""></option><option value="Norma">Norma</option><option value="Procedimiento">Procedimiento</option><option value="Metodo"> Método</option></select></td> '+
                                                    '<td><input type="text" class="form-control" name="numero[]" required></td>'+
                                                    '<td><input type="text" class="form-control" name="descripcion[]" required></td>'+
                                                    '<td><button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button></td>'+
                                                    '</tr>');

            // abrir modal
            $('#modal_agentes').modal({backdrop:false});
            break;
        case 12:
            // Borrar formulario
            $('#form_caracteristica').each(function(){
                this.reset();
            });

            // campos hidden
            $("#caracteristica_id").val(0);
            $("#caracteristica_catalogo").val(catalogo);

            // abrir modal
            $('#modal_caracteristica').modal({backdrop:false});
            break;
        case 13:
            // Borrar formulario
            $('#form_catalogo2campos').each(function(){
                this.reset();
            });

            // campos hidden
            $("#catalogo2campos_id").val(0);
            $("#catalogo2campos_catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo2campos').modal({backdrop:false});
            break;
         case 14:
            // Borrar formulario
            $('#form_cargo').each(function(){
                this.reset();
            });

            // campos hidden
            $("#ID_CARGO_INFORME").val(0);

            // abrir modal
            $('#modal_cargo').modal({backdrop:false});
            break;
         case 15:
            // Borrar formulario
            $('#form_formato').each(function(){
                this.reset();
            });

            // campos hidden
            $("#ID_FORMATO").val(0);

            // abrir modal
            $('#modal_formatos').modal({backdrop:false});
            break;
        case 16:
            // Borrar formulario
            $('#form_conclusion').each(function () {
                this.reset();
            });

            // campos hidden
            $("#ID_CATCONCLUSION").val(0);

            // abrir modal
            $('#modal_conclusion').modal({ backdrop: false });
            break;

            case 17:
                // Borrar formulario
                $('#form_descripcionarea').each(function () {
                    this.reset();
                });
    
                // campos hidden
                $("#ID_DESCRIPCION_AREA").val(0);
    
                // abrir modal
                $('#modal_descripcionarea').modal({ backdrop: false });
                break;
        default:
            // Borrar formulario
            $('#form_catalogo').each(function(){
                this.reset();
            });

            // campos hidden
            $("#id").val(0);
            $("#catalogo").val(catalogo);

            // abrir modal
            $('#modal_catalogo').modal({backdrop:false});
            break;
    }
});


//=======================================
function tabla_cargo(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_cargos != null)
        {
            datatable_cargos.clear().draw();
            datatable_cargos.ajax.url(ruta).load();
        }
        else
        {
            datatable_cargos = $('#tabla_lista_cargos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_cargo(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "ID_CARGO_INFORME" 
                    },
                    {
                        "data": "CARGO"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_cargo(num_catalogo);
    }    
}


function tabla_formatos(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_formatos != null)
        {
            datatable_formatos.clear().draw();
            datatable_formatos.ajax.url(ruta).load();
        }
        else
        {
            datatable_formatos = $('#tabla_lista_formatos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_formatos(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "ID_FORMATO" 
                    },
                    {
                        "data": "NOMBRE"
                    },
                    {
                        "data": "DESCRIPCION"
                    },
                      {
                        "className": 'descargar',
                        "orderable": false,
                        "data": 'boton_descargar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    },
                    {
                        "className": 'ver',
                        "orderable": false,
                        "data": 'boton_ver',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    }
                   
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_formatos(num_catalogo);
    }    
}

function tabla_conclusiones(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_conclusiones != null)
        {
            datatable_conclusiones.clear().draw();
            datatable_conclusiones.ajax.url(ruta).load();
        }
        else
        {
            datatable_conclusiones = $('#tabla_lista_conclusiones').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_conclusiones(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "ID_CATCONCLUSION" 
                    },
                    {
                        "data": "NOMBRE"
                    },
                    {
                        "data": "DESCRIPCION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_conclusiones(num_catalogo);
    }    
}



function tabla_descripcion(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_descripcion != null)
        {
            datatable_descripcion.clear().draw();
            datatable_descripcion.ajax.url(ruta).load();
        }
        else
        {
            datatable_descripcion = $('#tabla_lista_descripcion').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_descripcion(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    {
                        "data": "ID_DESCRIPCION_AREA" 
                    },
                    {
                        "data": "DESCRIPCION"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_descripcion(num_catalogo);
    }    
}



function tabla_catalogo(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_catalogo != null)
        {
            datatable_catalogo.clear().draw();
            datatable_catalogo.ajax.url(ruta).load();
        }
        else
        {
            datatable_catalogo = $('#tabla_lista_catalogo').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_catalogo(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "nombre"
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_catalogo(num_catalogo);
    }    
}


function editar_registro()
{
    $('#tabla_lista_catalogo tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_catalogo.row(tr);

        $('#form_catalogo').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#id").val(row.data().id);
        $("#nombre").val(row.data().nombre);
        $("#catalogo").val(catalogo);

        // abrir modal
        $('#modal_catalogo').modal({backdrop:false});
    });
}


function editar_registro_epp()
{
    $('#tabla_lista_catalogo_epp tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_epp.row(tr);

        $('#form_catalogo_epp').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_PARTESCUERPO_DESCRIPCION").val(row.data().ID_PARTESCUERPO_DESCRIPCION);
        $("#PARTECUERPO_ID").val(row.data().PARTECUERPO_ID);
        $("#CLAVE_EPP").val(row.data().CLAVE_EPP);
        $("#TIPO_RIEGO").val(row.data().TIPO_RIEGO);

        $("#catalogo").val(6);

        // abrir modal
        $('#modal_catalogo_epp').modal({backdrop:false});
    });
}

function estado_registro(catalogo, registro, checkbox)
{
    var estado = 0;
    if (checkbox.checked) {
        estado = 1;// activo
    }
    else{
        estado = 0;// Inactivo
    }

    // enviar datos
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/recsensorialcatalogodesactiva/"+catalogo+"/"+registro+"/"+estado,
        data:{},
        cache: false,
        success:function(dato){
            // mensaje
            swal({
                title: "Correcto",
                text: ""+dato.msj,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(dato){
            // checkbox estado anterior
            if (checkbox.checked){
                $(checkbox).prop('checked', false); // volver a Inactivar
            }
            else{
                $(checkbox).prop('checked', true); // volver activar
            }

            // mensaje
            swal({
                title: "Error",
                text: "Error al modificar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
            return false;
        }
    });//Fin ajax
}


$("#boton_guardar_conclusion").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_conclusion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_conclusiones(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_conclusion').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_conclusion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});



$("#boton_guardar_descripcionarea").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_descripcionarea').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_descripcion(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_descripcionarea').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_descripcionarea').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_descripcionarea').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_descripcionarea').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});



$("#boton_guardar_cargo").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_cargo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_cargo(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_cargo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_cargo').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_cargo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_cargo').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});

function editar_cargoInforme()
{
    $('#tabla_lista_cargos tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_cargos.row(tr);

        $('#form_cargo').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_CARGO_INFORME").val(row.data().ID_CARGO_INFORME);
        $("#CARGO").val(row.data().CARGO);
        $("#catalogo").val(14);

        // abrir modal
        $('#modal_cargo').modal({backdrop:false});
    });
}

function editar_catConclusion()
{
    $('#tabla_lista_conclusiones tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_conclusiones.row(tr);

        $('#form_conclusion').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_CATCONCLUSION").val(row.data().ID_CATCONCLUSION);
        $("#NOMBRE_CONCLUSION").val(row.data().NOMBRE);
        $("#DESCRIPCION_CONCLUSION").val(row.data().DESCRIPCION);

        $("#catalogo").val(16);

        // abrir modal
        $('#modal_conclusion').modal({backdrop:false});
    });
}


function editar_catdescripcion()
{
    $('#tabla_lista_descripcion tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_descripcion.row(tr);

        $('#form_descripcionarea').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#ID_DESCRIPCION_AREA").val(row.data().ID_DESCRIPCION_AREA);
        $("#DESCRIPCION").val(row.data().DESCRIPCION);

        $("#catalogo").val(17);

        // abrir modal
        $('#modal_descripcionarea').modal({backdrop:false});
    });
}

$("#boton_guardar_catalogo").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catalogo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_catalogo(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_catalogo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catalogo').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_catalogo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_catalogo').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});


$("#boton_guardar_catalogo_epp").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catalogo_epp').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_catalogo_epp(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_catalogo_epp').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catalogo_epp').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_catalogo_epp').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_catalogo_epp').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});

//=======================================


function tabla_catalogo2campos(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_catalogo2campos != null)
        {
            datatable_catalogo2campos.clear().draw();
            datatable_catalogo2campos.ajax.url(ruta).load();
        }
        else
        {
            datatable_catalogo2campos = $('#tabla_catalogo2campos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_catalogo2campos(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "siglas",
                        "defaultContent": '-'
                    },
                    {
                        "data": "nombre",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                // "rowsGroup": [0], //agrupar filas
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_catalogo2campos(num_catalogo);
    }    
}


function editar_catalogo2campos()
{
    $('#tabla_catalogo2campos tbody').on('click', 'td.editar', function()
    {
        var tr = $(this).closest('tr');
        var row = datatable_catalogo2campos.row(tr);

        $('#form_catalogo2campos').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#catalogo2campos_id").val(row.data().id);
        $("#catalogo2campos_campo1").val(row.data().siglas);
        $("#catalogo2campos_campo2").val(row.data().nombre);
        $("#catalogo2campos_catalogo").val(catalogo);

        // abrir modal
        $('#modal_catalogo2campos').modal({backdrop:false});
    });
}


$("#boton_guardar_catalogo2campos").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catalogo2campos').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_catalogo2campos(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: ""+dato.msj,
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_catalogo2campos').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catalogo2campos').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catalogo2campos').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato)
            {
                // actualiza boton
                $('#boton_guardar_catalogo2campos').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: ""+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});


//=======================================


function tabla_agentes(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_agentes != null)
        {
            datatable_agentes.clear().draw();
            datatable_agentes.ajax.url(ruta).load();
        }
        else
        {
            datatable_agentes = $('#tabla_lista_agentes').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_agentes(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "tipo_agente"
                    },
                    {
                        "data": "nombre"
                    },
                    {
                        "data": "normas",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_agentes(num_catalogo);
    }    
}


$("#boton_nueva_norma").click(function()
{
    $("#tabla_lista_normas tbody").append('<tr>'+
                                                '<td><select class="custom-select form-control" name="tipo[]" required><option value=""></option><option value="Norma">Norma</option><option value="Procedimiento">Procedimiento</option><option value="Metodo"> Método</option></select></td>'+
                                                '<td><input type="text" class="form-control" name="numero[]" required></td>'+
                                                '<td><input type="text" class="form-control" name="descripcion[]" required></td>'+
                                                '<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
                                            '</tr>');
});


// Eliminar fila
$('#tabla_lista_normas tbody').on('click', '.eliminar', function() {
    // alert(tr.index()); //index fila
    var tr = $(this).closest('tr');
    $(this).closest("tr").remove();// eliminar fila TR vacia
});


// Selecciona AGENTE
function editar_agente()
{
    $('#tabla_lista_agentes tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_agentes.row(tr);
        var numero = 0;
        var opciones = "";

        // Borrar formulario
        $('#form_agentes').each(function(){
            this.reset();
        });

        // campos hidden
        $("#agente_id").val(row.data().id);
        $("#agente_catalogo").val(catalogo);

        // llenar campos
        $("#catPrueba_Tipo").val(row.data().catPrueba_Tipo);
        $("#catPrueba_Nombre").val(row.data().catPrueba_Nombre);

        // vaciar lista de normas
        $("#tabla_lista_normas tbody").html('');

        // Seleccionar checkbox
        $.each( row.data().pruebanorma, function( key, value ) 
        {
            // seleccionar tipo de norma
            if (value.catpruebanorma_tipo == "Norma")
            {
                opciones = '<option value=""></option><option value="Norma" selected>Norma</option><option value="Procedimiento">Procedimiento</option><option value="Metodo"> Método</option>';
            }
            else if(value.catpruebanorma_tipo == "Procedimiento")
            {
                opciones = '<option value=""></option><option value="Norma">Norma</option><option value="Procedimiento" selected>Procedimiento</option><option value="Metodo"> Método</option>';
            }
            else{
                opciones = '<option value=""></option><option value="Norma">Norma</option><option value="Procedimiento" >Procedimiento</option><option value="Metodo" selected> Método</option>';
            }

            // pintar campos
            if (numero == 0)
            {
                $("#tabla_lista_normas tbody").append('<tr>'+
                                                            '<td><select class="custom-select form-control" name="tipo[]" required>'+opciones+'</select></td>'+
                                                            '<td><input type="text" class="form-control" name="numero[]" value="'+value.catpruebanorma_numero+'" required></td>'+
                                                            '<td><input type="text" class="form-control" name="descripcion[]" value="'+value.catpruebanorma_descripcion+'" required></td>'+
                                                            '<td><button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button></td>'+
                                                        '</tr>');
            }
            else
            {
                $("#tabla_lista_normas tbody").append('<tr>'+
                                                            '<td><select class="custom-select form-control" name="tipo[]" required>'+opciones+'</select></td>'+
                                                            '<td><input type="text" class="form-control" name="numero[]" value="'+value.catpruebanorma_numero+'" required></td>'+
                                                            '<td><input type="text" class="form-control" name="descripcion[]" value="'+value.catpruebanorma_descripcion+'" required></td>'+
                                                            '<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
                                                        '</tr>');
            }

            numero += 1;
        });

        // mostrar modal
        $('#modal_agentes').modal({backdrop:false});
    });
}


$("#boton_guardar_agente").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        swal({   
            title: "¡Confirme que desea guardar!",
            text: "Agente: "+$("#catPrueba_Nombre").val(),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm)
            {
                // cerrar msj confirmacion
                swal.close();

                // enviar datos
                $('#form_agentes').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/recsensorialcatalogos',
                    data: {},
                    resetForm: false,
                    success: function(dato)
                    {
                        // actualiza tabla
                        tabla_agentes(catalogo);

                        // mensaje
                        swal({
                            title: "Correcto",
                            text: ""+dato.msj,
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_guardar_agente').html('Guardar <i class="fa fa-save"></i>');

                        // cerrar modal
                        $('#modal_agentes').modal('hide');
                    },
                    beforeSend: function(){
                        $('#boton_guardar_agente').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(dato) {
                        // actualiza boton
                        $('#boton_guardar_agente').html('Guardar <i class="fa fa-save"></i>');
                        // mensaje
                        swal({
                            title: "Error",
                            text: ""+dato.msj,
                            type: "error", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });
                        return false;
                    }
                }).submit();
                return false;
            }
            else 
            {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });   
            }
        });
        return false;
    }
});


function estado_agente(catalogo, registro, checkbox)
{
    var estado = 0;
    if (checkbox.checked) {
        estado = 1;// activo
    }
    else{
        estado = 0;// Inactivo
    }

    // enviar datos
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/recsensorialcatalogodesactiva/"+catalogo+"/"+registro+"/"+estado,
        data:{},
        cache: false,
        success:function(dato){
            // mensaje
            swal({
                title: "Correcto",
                text: ""+dato.msj,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(dato){
            // checkbox estado anterior
            if (checkbox.checked){
                $(checkbox).prop('checked', false); // volver a Inactivar
            }
            else{
                $(checkbox).prop('checked', true); // volver activar
            }

            // mensaje
            swal({
                title: "Error",
                text: "Error al modificar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
            return false;
        }
    });//Fin ajax
}


//=======================================

function tabla_catalogo_epp(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+6;

    try
    {
        if (datatable_epp != null)
        {
            datatable_epp.clear().draw();
            datatable_epp.ajax.url(ruta).load();
        }
        else
        {
            datatable_epp = $('#tabla_lista_catalogo_epp').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_catalogo_epp(6);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "COUNT",
                    //     "defaultContent": '-'
                    // },
                    {
                        "data": "PARTECUERPO",
                        "defaultContent": '-'
                    },
                    {
                        "data": "CLAVE_EPP",
                        "defaultContent": '-'
                    },
                    {
                        "data": "TIPO_RIEGO",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[0, "asc"]],      
                "rowsGroup": [0], 
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_catalogo_epp(6);
    }    
}



function tabla_contratos(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_contratos != null)
        {
            datatable_contratos.clear().draw();
            datatable_contratos.ajax.url(ruta).load();
        }
        else
        {
            datatable_contratos = $('#tabla_lista_contratos').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_contratos(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "contratoempresa",
                        "defaultContent": '-'
                    },
                    {
                        "data": "iniciofin",
                        "defaultContent": '-'
                    },
                    {
                        "data": "montos",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_contratos(num_catalogo);
    }    
}


// Selecciona CONTRATO
function editar_contrato()
{
    $('#tabla_lista_contratos tbody').on('click', 'td.editar', function() {
        var tr = $(this).closest('tr');
        var row = datatable_contratos.row(tr);

        // Borrar formulario
        $('#form_contrato').each(function(){
            this.reset();
        });

        // campos hidden
        $("#contrato_id").val(row.data().id);
        $("#contrato_catalogo").val(catalogo);

        // llenar campos
        $("#catcontrato_numero").val(row.data().catcontrato_numero);
        $("#catcontrato_empresa").val(row.data().catcontrato_empresa);
        $("#catcontrato_fechainicio").val(row.data().catcontrato_fechainicio);
        $("#catcontrato_fechafin").val(row.data().catcontrato_fechafin);
        $("#catcontrato_montomxn").val(row.data().catcontrato_montomxn);
        $("#catcontrato_montousd").val(row.data().catcontrato_montousd);

        // mostrar modal
        $('#modal_contrato').modal({backdrop:false});
    });
}


$("#boton_guardar_contrato").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        swal({   
            title: "¡Confirme que desea guardar contrato!",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            cancelButtonText: "Cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm)
            {
                // cerrar msj confirmacion
                swal.close();
                
                // enviar datos
                $('#form_contrato').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/recsensorialcatalogos',
                    data: {},
                    resetForm: false,
                    success: function(dato)
                    {
                        // actualiza tabla
                        tabla_contratos(catalogo);

                        // mensaje
                        swal({
                            title: "Correcto",
                            text: ""+dato.msj,
                            type: "success", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // actualiza boton
                        $('#boton_guardar_contrato').html('Guardar <i class="fa fa-save"></i>');

                        // cerrar modal
                        $('#modal_contrato').modal('hide');
                    },
                    beforeSend: function(){
                        $('#boton_guardar_contrato').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function(dato) {
                        // actualiza boton
                        $('#boton_guardar_contrato').html('Guardar <i class="fa fa-save"></i>');
                        // mensaje
                        swal({
                            title: "Error",
                            text: ""+dato.msj,
                            type: "error", // warning, error, success, info
                            buttons: {
                                visible: false, // true , false
                            },
                            timer: 1500,
                            showConfirmButton: false
                        });
                        return false;
                    }
                }).submit();
                return false;
            }
            else 
            {
                // mensaje
                swal({
                    title: "Cancelado",
                    text: "Acción cancelada",
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });   
            }
        });
        return false;
    }
});


function estado_contrato(catalogo, registro, checkbox)
{
    var estado = 0;
    if (checkbox.checked) {
        estado = 1;// activo
    }
    else{
        estado = 0;// Inactivo
    }

    // enviar datos
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/recsensorialcatalogodesactiva/"+catalogo+"/"+registro+"/"+estado,
        data:{},
        cache: false,
        success:function(dato){
            // mensaje
            swal({
                title: "Correcto",
                text: ""+dato.msj,
                type: "success", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(dato){
            // checkbox estado anterior
            if (checkbox.checked){
                $(checkbox).prop('checked', false); // volver a Inactivar
            }
            else{
                $(checkbox).prop('checked', true); // volver activar
            }

            // mensaje
            swal({
                title: "Error",
                text: "Error al modificar la información",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
            return false;
        }
    });//Fin ajax
}


//=======================================


function tabla_caracteristica(num_catalogo)
{
    var ruta = "/recsensorialconsultacatalogo/"+num_catalogo;

    try
    {
        if (datatable_caracteristicas != null)
        {
            datatable_caracteristicas.clear().draw();
            datatable_caracteristicas.ajax.url(ruta).load();
        }
        else
        {
            datatable_caracteristicas = $('#tabla_lista_caracteristica').DataTable({
                "ajax": {
                    "url": ruta,
                    "type": "get",
                    "cache": false,
                    error: function (xhr, error, code)
                    {
                        // console.log(xhr); console.log(code);
                        tabla_caracteristica(num_catalogo);
                    },
                    "data": {}
                },
                "columns": [
                    // {
                    //     "data": "id" 
                    // },
                    {
                        "data": "tipo",
                        "defaultContent": '-'
                    },
                    {
                        "data": "caracteristica",
                        "defaultContent": '-'
                    },
                    {
                        "className": 'editar',
                        "orderable": false,
                        "data": 'boton_editar',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-pencil"></i></button>'
                    },
                    {
                        // "className": 'Desactivar',
                        "orderable": false,
                        "data": 'CheckboxEstado',
                        "defaultContent": '-'
                        // "defaultContent": '<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-lock"></i></button>'
                    }
                ],
                "rowsGroup": [0], //agrupar filas
                "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
                "order": [[ 0, "asc" ]],        
                "searching": false,
                "paging": false,
                "ordering": false,
                "processing": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No se encontraron registros",
                    "info": "", //Página _PAGE_ de _PAGES_
                    "infoEmpty": "No se encontraron registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "loadingRecords": "Cargando datos....",
                    "processing": "Procesando <i class='fa fa-spin fa-spinner fa-3x'></i>",
                    "search": "Buscar",
                    "paginate": {
                        "first": "Primera",
                        "last": "Ultima",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }
    }
    catch (exception)
    {
        // alert("error en el ajax");
        tabla_caracteristica(num_catalogo);
    }    
}


function editar_caracteristica()
{
    $('#tabla_lista_caracteristica tbody').on('click', 'td.editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = datatable_caracteristicas.row(tr);

        $('#form_caracteristica').each(function(){
            this.reset();
        });

        // Llenar campo formulario
        $("#caracteristica_id").val(row.data().id);
        $("#tipo").val(row.data().tipo);
        $("#caracteristica").val(row.data().caracteristica);
        $("#caracteristica").val(row.data().caracteristica);
        $("#caracteristica_catalogo").val(catalogo);

        // abrir modal
        $('#modal_caracteristica').modal({backdrop:false});
    });
}


$("#boton_guardar_caracteristica").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_caracteristica').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_caracteristica(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_caracteristica').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_caracteristica').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_caracteristica').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_caracteristica').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});



$("#boton_guardar_formato").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_formato').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialcatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualizar tabla
                tabla_formatos(catalogo);

                // mensaje
                swal({
                    title: "Correcto",
                    text: "Información guardada correctamente",
                    type: "success", 
                    buttons: {visible: false},
                    timer: 2000,
                    showConfirmButton: false
                });

                // actualiza boton
                $('#boton_guardar_formato').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_formatos').modal('hide');
            },
            beforeSend: function(){
                $('#boton_guardar_formato').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) {
                // actualiza boton
                $('#boton_guardar_formato').html('Guardar <i class="fa fa-save"></i>');
                // mensaje
                swal({
                    title: "Error",
                    text: "Error en la acción: "+dato,
                    type: "error", // warning, error, success, info
                    buttons: {
                        visible: false, // true , false
                    },
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        }).submit();
        return false;
    }
});


function verFormatoCampo(){
    // mostrar PDF
    $('#tabla_lista_formatos').on('click', 'td.ver', function () {

    
        var tr = $(this).closest('tr');
        var row = datatable_formatos.row(tr);

        // console.log(row);
        // vaciar visor
        // $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        // abrir modal
        $('#modal_visor').modal({backdrop:false});

        // TITULO DEL VISOR
        $('#nombre_documento_visor').html(row.data().NOMBRE);

        

        // Mostrar PDF
        // if (parseInt(row.data().perfil) == 1 && parseInt(proveedor_Bloqueado) == 0)
        // {
            // $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/verFormatoCampo/'+row.data().ID_FORMATO);
        // }
        // else
        // {
            $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/verFormatoCampo/0/'+row.data().ID_FORMATO);
        // }
    });
};

function descargarFormatoCampo(){
    // mostrar PDF
    $('#tabla_lista_formatos').on('click', 'td.descargar', function () {

    
        var tr = $(this).closest('tr');
        var row = datatable_formatos.row(tr);

    	window.open('/verFormatoCampo/1/'+row.data().ID_FORMATO);

        
    });
};

function editarFormatoCampo(){
    // mostrar PDF
    $('#tabla_lista_formatos').on('click', 'td.editar', function () {

        $('#form_formato').each(function(){
            this.reset();
        });
    
        var tr = $(this).closest('tr');
        var row = datatable_formatos.row(tr);

        $('#FORMATO_DESCRIPCION').val(row.data().DESCRIPCION)
        $('#FORMATO_NOMBRE').val(row.data().NOMBRE)
    	$('#ID_FORMATO').val(row.data().ID_FORMATO)
        
        $('#FORMATO_PDF').prop('required', false)
        $('#modal_formatos').modal({backdrop:false});

        
    });
};