var tabla_catsustancia = null;
var tabla_catestadofisico = null;
var tabla_catvolatilidad = null;
var tabla_catviaingreso = null;
var tabla_catpeligro = null;
var tabla_catgradoriesgo = null;
var tabla_catsustanciaQuimicas = null;
var tabla_catsustanciaQuimicasEntidad = null;
var tabla_metodosSustanciasQuimicas = null;
var tabla_beiSustanciasQuimicas = null;
var tabla_catUnidadMedida = null;
var tabla_catConnotacion = null;
var tabla_catEntidades = null;
var tabla_catConclusiones = null;
var tabla_catDescripcion = null;



// Catalogo estado fisico de los sustancias [componentes]
var catestadofisico_opciones = null;
var SUSTANCIA_QUIMICA_ID = 0
var proceso = 0
var procesoEntidad = 0
var procesoEntidades = 0

// Load CATALOGOS PROVEEDORES
$(document).ready(function() {
	mostrar_catalogo(0);
    consulta_catestadofisicosustancia();

	$('[data-toggle="tooltip"]').tooltip();

});



function mostrar_catalogo(num_catalogo)
{ 
    // activar Menu
    $("#tr_0").removeClass("active");
	$("#cat_0").removeClass("text-info");
    $("#cat_0").addClass("text-secondary");
    
	$("#tr_1").removeClass("active");
	$("#cat_1").removeClass("text-info");
	$("#cat_1").addClass("text-secondary");

	$("#cat_2").removeClass("text-info");
	$("#cat_2").addClass("text-secondary");
    $("#tr_2").removeClass("active");

    $("#cat_3").removeClass("text-info");
    $("#cat_3").addClass("text-secondary");
    $("#tr_3").removeClass("active");

    $("#cat_4").removeClass("text-info");
    $("#cat_4").addClass("text-secondary");
    $("#tr_4").removeClass("active");

    $("#cat_5").removeClass("text-info");
    $("#cat_5").addClass("text-secondary");
    $("#tr_5").removeClass("active");

    $("#cat_6").removeClass("text-info");
    $("#cat_6").addClass("text-secondary");
    $("#tr_6").removeClass("active");

    $("#cat_8").removeClass("text-info");
    $("#cat_8").addClass("text-secondary");
    $("#tr_8").removeClass("active");

    $("#cat_9").removeClass("text-info");
    $("#cat_9").addClass("text-secondary");
    $("#tr_9").removeClass("active");

    $("#cat_10").removeClass("text-info");
    $("#cat_10").addClass("text-secondary");
    $("#tr_10").removeClass("active");

    $("#cat_11").removeClass("text-info");
    $("#cat_11").addClass("text-secondary");
    $("#tr_11").removeClass("active");

    $("#cat_12").removeClass("text-info");
    $("#cat_12").addClass("text-secondary");
    $("#tr_12").removeClass("active");

    $("#cat_13").removeClass("text-info");
    $("#cat_13").addClass("text-secondary");
    $("#tr_13").removeClass("active");

    $("#div_tabla_catalogo").html('');

	// Renombrar nombre del campo segun el CATALOGO
   	switch (num_catalogo){   
        case 0:

                // activar menu
                $("#titulo_tabla").html('Catálogo [Sustancias químicas de la NOM-010-STPS-2014]');
                $("#tr_0").addClass("active");
    			$("#cat_0").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nueva_sustancia_quimica();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catsustancia_quimicas">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th style="width: 80px !important;">No</th>'+
                                                                '<th>Sustancia</th>' +
                                                                '<th>Otros nombres</th>'+
                                                                '<th>Alteración / Efecto a la Salud</th>'+
                                                                '<th>PM</th>'+
                                                                '<th>No. CAS</th>'+
                                                                '<th>Reg. Totales VLE</th>' +
                                                                '<th>Reg. Totales IBE</th>'+
                    
                                                                // '<th>VLE<br>PPT</th>'+
                                                                // '<th>VLE<br>CT o P</th>' +
                                                                // '<th>Normatividad</th>' +
                                                                // '<th>Tem. Ebullición</th>'+
                                                                '<th style="width: 80px !important;">Ver</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catsustancia_quimico(num_catalogo);
            break;
        case 1:
                  // activar menu
                $('#sustancias_quimicias').select2({multiple:true,  placeholder: 'Seleccione una o más sustancias...'})
                $("#titulo_tabla").html('Catálogo [Hojas de seguridad]');
                $("#tr_1").addClass("active");
    			$("#cat_1").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nueva_sustancia();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catsustancia">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th style="width: 80px !important;">No</th>'+
                                                                '<th>Sustancia</th>'+
                                                                '<th>Componentes</th>'+
                                                                '<th>Estado<br>Fisico.</th>'+
                                                                '<th>Volatilidad</th>'+
                                                                '<th>Vía<br>ingreso</th>'+
                                                                '<th>Clasif.<br>riesgo</th>'+
                                                                '<th style="width: 80px !important;">Hoja<br>Seguridad</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catsustancia(num_catalogo);
                break;
        case 2:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Estado físico de la sustancia]');
                $("#tr_2").addClass("active");
                $("#cat_2").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catestadofisico();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catestadofisico">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th style="width: 80px !important;">No</th>'+
                                                                '<th>Estado físico</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catestadofisico(num_catalogo);
                break;
        case 3:
        	   // activar menu
                $("#titulo_tabla").html('Catálogo [Volatilidad de la sustancia]');
                $("#tr_3").addClass("active");
                $("#cat_3").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catvolatilidad();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catvolatilidad">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th style="width: 80px !important;">No</th>'+
                                                                '<th>Volatilidad</th>'+
                                                                '<th>Ponderación</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catvolatilidad(num_catalogo);
                break;
        case 4:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Vía ingreso al organismo]');
                $("#tr_4").addClass("active");
                $("#cat_4").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catviaingreso();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catviaingreso">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th style="width: 80px !important;">No</th>'+
                                                                '<th>Vía ingreso al organismo</th>'+
                                                                '<th>Ponderación</th>'+
                                                                '<th>Num. POE expuesto</th>'+
                                                                '<th>Tiempo exposición</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catviaingreso(num_catalogo);
                break;
        case 5:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Categoría de peligro a la salud]');
                $("#tr_5").addClass("active");
                $("#cat_5").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catpeligro();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catpeligro">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>Categoria de peligro</th>'+
                                                                '<th>Código de peligro</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catpeligro(num_catalogo);
                break;
        case 6:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Grado de riesgo a la salud]');
                $("#tr_6").addClass("active");
                $("#cat_6").addClass("text-info");

                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catgradoriesgo();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catgradoriesgo">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>No</th>'+
                                                                '<th>Descripción</th>'+
                                                                '<th>Vía ingreso oral</th>'+
                                                                '<th>Vía ingreso piel</th>'+
                                                                '<th>Vía ingreso inhalación</th>'+
                                                                '<th>Ponderación</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');

                // mostrar tabla catalogo
                function_tabla_catgradoriesgo(num_catalogo);
                  break;
              
        case 8:
            // activar menu
            $("#titulo_tabla").html('Catálogo [Unidad de Medida]');
            $("#tr_8").addClass("active");
            $("#cat_8").addClass("text-info");

            // tabla encabezado
            $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catUnidadMedida();">'+
                                                    '<i class="fa fa-plus"></i>'+
                                                '</button>'+
                                            '</h2>'+
                                            '<div class="table-responsive m-t-20">'+
                                                '<table class="table table-hover stylish-table" width="100%" id="tabla_catUnidadMedida">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th style="width: 80px !important;">No</th>'+
                                                            '<th>Descripción</th>'+
                                                            '<th>Abreviatura</th>'+
                                                            '<th style="width: 80px !important;">Editar</th>'+
                                                            '<th style="width: 80px !important;">Activo</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>');

            // mostrar tabla catalogo
            function_tabla_catUnidadMedida(num_catalogo);
                  break;
         case 9:
            // activar menu
            $("#titulo_tabla").html('Catálogo [Connotaciones]');
            $("#tr_9").addClass("active");
            $("#cat_9").addClass("text-info");

            // tabla encabezado
            $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catConnotacion();">'+
                                                    '<i class="fa fa-plus"></i>'+
                                                '</button>'+
                                            '</h2>'+
                                            '<div class="table-responsive m-t-20">'+
                                                '<table class="table table-hover stylish-table" width="100%" id="tabla_catConnotacion">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>Entidad</th>'+
                                                            '<th>Abreviatura</th>'+
                                                            '<th>Descripción</th>'+
                                                            '<th style="width: 80px !important;">Editar</th>'+
                                                            '<th style="width: 80px !important;">Activo</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>');

            // mostrar tabla catalogo
            function_tabla_catConnotacion(num_catalogo);
                  break;
              
        case 10:
            // activar menu
            $("#titulo_tabla").html('Catálogo [Entidades]');
            $("#tr_10").addClass("active");
            $("#cat_10").addClass("text-info");

            // tabla encabezado
            $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catEntidades();">'+
                                                    '<i class="fa fa-plus"></i>'+
                                                '</button>'+
                                            '</h2>'+
                                            '<div class="table-responsive m-t-20">'+
                                                '<table class="table table-hover stylish-table" width="100%" id="tabla_catEntidades">'+
                                                    '<thead>'+
                                                        '<tr>'+
                                                            '<th>#</th>'+
                                                            '<th>Entidad</th>'+
                                                            '<th>Descripción normativa</th>'+
                                                            '<th style="width: 80px !important;">Editar</th>'+
                                                            '<th style="width: 80px !important;">Activo</th>'+
                                                        '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>'+
                                                    '</tbody>'+
                                                '</table>'+
                                            '</div>');

            // mostrar tabla catalogo
            function_tabla_catEntidad(num_catalogo);
            break;

            case 11:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Conclusiones para Informes]');

                $("#tr_11").addClass("active");
                $("#cat_11").addClass("text-info");
    
                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catConclusiones();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catConclusiones">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>#</th>'+
                                                                '<th>Nombre</th>'+
                                                                '<th>Descripción</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');
    
                // mostrar tabla catalogo
                function_tabla_catConclusiones(num_catalogo);
                break;


            case 12:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Descripciones Área]');

                $("#tr_12").addClass("active");
                $("#cat_12").addClass("text-info");
    
                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo registro" onclick="boton_nuevo_catDescripcionarea();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catDescripcion">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>#</th>'+
                                                                '<th>Descripción</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');
    
                // mostrar tabla catalogo
                function_tabla_catDescripcionarea(num_catalogo);
                break;
              
            case 13:
                // activar menu
                $("#titulo_tabla").html('Catálogo [Recomendaciones para informes]');

                $("#tr_13").addClass("active");
                $("#cat_13").addClass("text-info");
    
                // tabla encabezado
                $("#div_tabla_catalogo").html('<h2 class="add-ct-btn" style="border: 0px #f00 solid; margin-top: -24px;">'+
                                                    '<button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect" data-toggle="tooltip" title="Nuevo recomendación" onclick="boton_nuevo_catRecomendacion();">'+
                                                        '<i class="fa fa-plus"></i>'+
                                                    '</button>'+
                                                '</h2>'+
                                                '<div class="table-responsive m-t-20">'+
                                                    '<table class="table table-hover stylish-table" width="100%" id="tabla_catRecomendacion">'+
                                                        '<thead>'+
                                                            '<tr>'+
                                                                '<th>#</th>'+
                                                                '<th>Descripción</th>'+
                                                                '<th style="width: 80px !important;">Editar</th>'+
                                                                '<th style="width: 80px !important;">Activo</th>'+
                                                            '</tr>'+
                                                        '</thead>'+
                                                        '<tbody>'+
                                                        '</tbody>'+
                                                    '</table>'+
                                                '</div>');
    
                // mostrar tabla catalogo
                function_tabla_catRecomendacion(num_catalogo);
                break;


        
    }

    // activar tooltip
    $('[data-toggle="tooltip"]').tooltip();
}



function consulta_catestadofisicosustancia()
{
    $.ajax({
        type: "get",
        dataType: "json",
        url: "/quimicoscatestadofisico",
        data:{},
        cache: false,
        success:function(dato){
            // opciones select
            catestadofisico_opciones = dato.catestadofisico;
        },
        beforeSend: function(){
            // $('#boton_finalizar_captura').html('Finalizando <i class="fa fa-spin fa-spinner"></i>');
        },
        error: function(dato){

            // mensaje
            swal({
                title: "Error",
                text: "Error al consultar la información del catalogo estado fisico de las sustancias",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });

            consulta_catestadofisicosustancia();

            return false;
        }
    });//Fin ajax
}



// ======================================================
function function_tabla_catsustancia_quimico(num_catalogo)
{
	// Inicializar tabla
    if (tabla_catsustanciaQuimicas != null)
    {
        tabla_catsustanciaQuimicas.destroy();
        tabla_catsustanciaQuimicas = null;
    }

    try
    {
        tabla_catsustanciaQuimicas = $('#tabla_catsustancia_quimicas').DataTable({
            "ajax": {
                "url": "/recsensorialquimicoscatalogostabla/"+num_catalogo,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catsustancia_quimico(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "SUSTANCIA_QUIMICA",
                    "defaultContent": ''
                },
                {
                    "data": "OTROS_NOMBRES",
                    "defaultContent": ''
                },
                {
                    "data": "ALTERACION_EFECTO",
                    "defaultContent": ''
                },
                {
                    "data": "PM",
                    "defaultContent": ''
                },
                {
                    "data": "NUM_CAS",
                    "defaultContent": ''
                },
                {
                    "data": "total_registro",
                    "defaultContent": ''
                },
                {
                    "data": "total_registroBEIS",
                    "defaultContent": ''
                },
                {
                    "className": 'Ver',
                    "orderable": false,
                    "data": 'boton_ver',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": true,
            "paging": true,
            "ordering": true,
            "processing": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros",
                "zeroRecords": "No se encontraron registros",
                "info": "", //Página _PAGE_ de _PAGES_
                "infoEmpty": "No se encontraron registros",
                "infoFiltered": "(Filtrado de _MAX_ registros)",
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando datos....",
                "processing": "Cargando datos <i class='fa fa-spin fa-spinner fa-3x'></i>",
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catsustancia_quimico(num_catalogo);
    }
}


function function_tabla_catsustancia(num_catalogo)
{
	// Inicializar tabla
    if (tabla_catsustancia != null)
    {
        tabla_catsustancia.destroy();
        tabla_catsustancia = null;
    }

    try
    {
        tabla_catsustancia = $('#tabla_catsustancia').DataTable({
            "ajax": {
                "url": "/recsensorialquimicoscatalogostabla/"+num_catalogo,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catsustancia(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "catsustancia_nombre",
                    "defaultContent": ''
                },
                {
                    "data": "sustancias_quimicas",
                    "defaultContent": ''
                },
                {
                    "data": "catestadofisicosustancia_estado",
                    "defaultContent": ''
                },
                {
                    "data": "catvolatilidad_tipo",
                    "defaultContent": ''
                },
                {
                    "data": "catviaingresoorganismo_viaingreso",
                    "defaultContent": ''
                },
                {
                    "data": "catClasificacionRiesgo",
                    "defaultContent": ''
                },
                {
                    "className": 'Pdf',
                    "orderable": false,
                    "data": 'boton_pdf',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            "order": [[ 0, "ASC" ]],        
            "searching": true,
            "paging": true,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catsustancia(num_catalogo);
    }
}


function boton_nueva_sustancia() {

    $('#textPorcentajes').css('display', 'none');
    $('#tablaSustanciasSeleccionadas tbody').empty();
    $('#tablaSustanciasSeleccionadas').css('display', 'none');

    //Limpiar select de componetes
    $('#sustancias_quimicias').val('');
	$('#sustancias_quimicias').trigger('change');

    // Borrar formulario
    $('#form_catsustancia').each(function(){
        this.reset();
    });

    // campos hidden
    $('#sustancia_id').val(0);
    $('#catsustancia_activo').val(1);
    $('#catsustacia_catalogo').val(1); 

     $('#catClasificacionRiesgo').val('')
    $('#clasificacion_riesgo_text_hoja').text('')

    $('#divCategoriaSaludHoja').fadeIn(0)
    $('#divGradoSaludHoja').fadeOut(0)

    // tabla componentes
    // $("#tabla_sustanciacomponentes tbody").html('');



    // abrir modal
    $('#modal_catsustancia').modal({backdrop:false});
}


// $("#boton_nuevo_componente").click(function()
// {
//     // var totalfilas = ($('#tabla_sustanciacomponentes tr').length - 1); //Menos el encabezado (-1)
//     // alert(totalfilas);
//     $("#tabla_sustanciacomponentes tbody").append(  '<tr>'+
//                                                         '<td><input type="text" class="form-control" name="componente[]" value="" required></td>'+
//                                                         '<td><input type="text" class="form-control" name="cas[]" value="" required></td>'+
//                                                         '<td><input type="text" class="form-control" name="temperatura[]" value="" required></td>'+
//                                                         '<td><input type="number" step="any" class="form-control" name="porcentaje[]" value="" required></td>'+
//                                                         '<td><input type="text" class="form-control" name="peso[]" value="" required></td>'+
//                                                         '<td>'+
//                                                             '<select class="custom-select form-control" style="width: 120px;" name="estadofisico[]" required>'+
//                                                                 catestadofisico_opciones+
//                                                             '</select>'+
//                                                         '</td>'+
//                                                         '<td>'+
//                                                             '<select class="custom-select form-control" style="width: 120px;" name="volatilidad[]" required>'+
//                                                                 '<option value=""></option>'+
//                                                                 '<option value="ND">ND</option>'+
//                                                                 '<option value="Baja">Baja</option>'+
//                                                                 '<option value="Media">Media</option>'+
//                                                                 '<option value="Alta">Alta</option>'+
//                                                             '</select>'+
//                                                         '</td>'+
//                                                         '<td><input type="text" class="form-control" name="limiteppt[]" value="" required></td>'+
//                                                         '<td><input type="text" class="form-control" name="limitectop[]" value="" required></td>'+
//                                                         '<td><input type="text" class="form-control" name="connotacion[]" value="" required></td>'+
//                                                         '<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
//                                                     '</tr>');
// });


// Eliminar componente
// $(document).ready(function() {
//     $('#tabla_sustanciacomponentes tbody').on('click', '.eliminar', function() {
//         // alert(tr.index()); //index fila
//         var tr = $(this).closest('tr');
//         var columna_0 = tr.find("td:eq(0)").html(); //obtener texto de la columna 0
//         var nombre_componente = (columna_0.replace('<input type="text" class="form-control" name="componente[]" value="', '')).replace('" required="">', '');

//         if (nombre_componente == "") {
//             $(this).closest("tr").remove();// eliminar fila TR vacia
//         }
//         else
//         {
//             swal({
//                 title: "¿Eliminar componente?",
//                 text: ""+nombre_componente,
//                 type: "warning",
//                 showCancelButton: true,
//                 confirmButtonColor: "#DD6B55",
//                 confirmButtonText: "¡Eliminar!",
//                 cancelButtonText: "Cancelar",
//                 closeOnConfirm: false,
//                 closeOnCancel: false
//             }, function(isConfirm){
//                 if (isConfirm)
//                 {
//                     swal({
//                         title: "¡Confirme nuevamente eliminar componente!",
//                         text: ""+nombre_componente,
//                         type: "warning",
//                         showCancelButton: true,
//                         confirmButtonColor: "#DD6B55",
//                         confirmButtonText: "¡Eliminar!",
//                         cancelButtonText: "Cancelar",
//                         closeOnConfirm: false,
//                         closeOnCancel: false
//                     }, function(isConfirm){
//                         if (isConfirm)
//                         {
//                             // eliminar fila TR
//                             tr.remove();

//                             // mensaje
//                             swal({
//                                 title: "Eliminado",
//                                 text: "",
//                                 type: "success", // warning, error, success, info
//                                 buttons: {
//                                     visible: false, // true , false
//                                 },
//                                 timer: 1000,
//                                 showConfirmButton: false
//                             });
//                         }
//                         else
//                         {
//                             // mensaje
//                             swal({
//                                 title: "¡Cancelado!",
//                                 text: "Acción cancelada",
//                                 type: "error", // warning, error, success, info
//                                 buttons: {
//                                     visible: false, // true , false
//                                 },
//                                 timer: 1000,
//                                 showConfirmButton: false
//                             });
//                         }
//                     });
//                 }
//                 else
//                 {
//                     // mensaje
//                     swal({
//                         title: "¡Cancelado!",
//                         text: "Acción cancelada",
//                         type: "error", // warning, error, success, info
//                         buttons: {
//                             visible: false, // true , false
//                         },
//                         timer: 1000,
//                         showConfirmButton: false
//                     });
//                 }
//             });
//         }

//     });
// });

function validarErrores() {
        // Selecciona todos los inputs y selects dentro de la tabla que tengan la clase 'error'
        var elementosConError = $('#tablaSustanciasSeleccionadas .error');

        if (elementosConError.length > 0) {
            // Si hay elementos con la clase 'error', retorna true
            return true;
        } else {
            // Si no hay elementos con la clase 'error', retorna false
            return false;
        }
    }

$("#boton_guardar_sustancia").click(function(e){
    // valida campos vacios
    e.preventDefault();

    componentes = $('#sustancias_quimicias').val();
    var valida = this.form.checkValidity();

    if ($('#validarSustancias').is(':checked')) {
        // enviar datos
        $('#form_catsustancia').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {

                if (dato.code == 1) {

                    // actualiza tabla
                    tabla_catsustancia.ajax.url("/recsensorialquimicoscatalogostabla/1").load();

                    // mensaje
                    swal({
                        title: "Correcto",
                        text: "" + dato.msj,
                        type: "success", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // actualiza boton
                    $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');

                    //Limpiamos nuestro select de las sustancias
                    $('#sustancias_quimicias').val('');
                    $('#sustancias_quimicias').trigger('change');


                    // cerrar modal
                    $('#modal_catsustancia').modal('hide');

                } else {

                    swal({
                        title: "La sustancia no se pudo guardar",
                        text: "" + dato.msj,
                        type: "info", // warning, error, success, info
                        buttons: {
                            visible: false, // true , false
                        },
                        timer: 2500,
                        showConfirmButton: false
                    });

                    $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');


                }
            },
            beforeSend: function () {
                $('#boton_guardar_sustancia').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                // actualiza boton
                $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');

                // mensaje
                swal({
                    title: "Rellene todos los campos..",
                    text: "Si el error persiste comuniquelo con el responsable.",
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
    } else {
        
        if (componentes.length != 0) 
        {
            if (!validarErrores()) {
                sustanciaPorcentajes = crearArregloPorcentajeSustancia($('#sustancia_id').val())
    
                // enviar datos
                $('#form_catsustancia').ajaxForm({
                    dataType: 'json',
                    type: 'POST',
                    url: '/recsensorialquimicoscatalogos',
                    data: { porcentajes: JSON.stringify(sustanciaPorcentajes) },
                    resetForm: false,
                    success: function (dato) {
                    
                        if (dato.code == 1) {
                        
                            // actualiza tabla
                            tabla_catsustancia.ajax.url("/recsensorialquimicoscatalogostabla/1").load();
    
                            // mensaje
                            swal({
                                title: "Correcto",
                                text: "" + dato.msj,
                                type: "success", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 1500,
                                showConfirmButton: false
                            });
    
                            // actualiza boton
                            $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');
                        
                            //Limpiamos nuestro select de las sustancias
                            $('#sustancias_quimicias').val('');
                            $('#sustancias_quimicias').trigger('change');
                        
    
                            // cerrar modal
                            $('#modal_catsustancia').modal('hide');
                    
                        } else {
                        
                            swal({
                                title: "La sustancia no se pudo guardar",
                                text: "" + dato.msj,
                                type: "info", // warning, error, success, info
                                buttons: {
                                    visible: false, // true , false
                                },
                                timer: 2500,
                                showConfirmButton: false
                            });
                        
                            $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');
                        
    
                        }
                    },
                    beforeSend: function () {
                        $('#boton_guardar_sustancia').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function (dato) {
                        // actualiza boton
                        $('#boton_guardar_sustancia').html('Guardar <i class="fa fa-save"></i>');
                    
                        // mensaje
                        swal({
                            title: "Rellene todos los campos..",
                            text: "Si el error persiste comuniquelo con el responsable.",
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
                    
            } else {
                    swal({
                title: "¡Faltan campos por rellenar!",
                text: "Asegurece de rellenar todos los campos pintados de ROJO",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });
            }
        
        } else {
            // mensaje
            swal({
                title: "¡Falta componente!",
                text: "Debe seleccionar como mínimo 1 componente de la sustancia",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 2000,
                showConfirmButton: false
            });
    
            // $("#boton_nuevo_componente").click();
        }
        
    }
    
});



function mostrar_pdf()
{
    $('#tabla_catsustancia tbody').on('click', 'td.Pdf', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustancia.row(tr);
        // vaciar visor
        $('#visor_titulo').html('');
        // $('#visor_documento').attr('src', '/assets/images/cargando.gif');

        if (row.data().catsustancia_hojaseguridadpdf)
        {
            // abrir modal
            $('#modal_visor').modal({backdrop:false});

            // cargar documento en el visor
            $('#visor_titulo').html(row.data().catsustancia_nombre);
            // $('#visor_documento').attr('src', '/recsensorialquimicoscatalogopdf/'+row.data().id);

            // Mostrar PDF
            if (parseInt(row.data().perfil) == 1)
            {
                // Iframe
                $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer.html?file=/recsensorialquimicoscatalogopdf/'+row.data().id);
            }
            else
            {
                // Visor ViewerJS
                $('#visor_documento').attr('src', '/assets/plugins/viewer-pdfjs/web/viewer_read.html?file=/recsensorialquimicoscatalogopdf/'+row.data().id);
            }
        }
        else{
            // mensaje
            swal({
                title: "¡Sin archivo!",
                text: "Esta sustancia no se ha cargado la hoja de seguridad .pdf",
                type: "warning", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}


$("#boton_cerrar_visorpdf").click(function()
{
    $('#visor_documento').attr('src', '/assets/images/cargando.gif');
});


function selecciona_sustancia(){
    $('#tabla_catsustancia tbody').on('click', 'td.Editar', function () {

        $('#form_catsustancia').each(function(){
            this.reset();
        });
        
        //Limpiamos nuestro select de los componentes
        $('#sustancias_quimicias').val('');
        $('#sustancias_quimicias').trigger('change');

        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustancia.row(tr);

        var nombre = '';
        var cas = '';
        var ebullicion = '';
        var porcentaje = '';
        var peso = '';
        var volatilidad = '';
        var ppt = '';
        var ctop = '';
        var connotacion = '';

     
        // campos hidden
        $('#sustancia_id').val(row.data().id);
        $('#catsustancia_activo').val(row.data().catsustancia_activo);
        $('#catsustacia_catalogo').val(1);

        // Llenar campo formulario
        $("#catsustancia_nombre").val(row.data().catsustancia_nombre);
        $("#catsustancia_nombreComun").val(row.data().catsustancia_nombreComun);
        $("#catTemOperacion").val(row.data().catTemOperacion);
        $("#catestadofisicosustancia_id").val(row.data().catestadofisicosustancia_id);
        $("#catvolatilidad_id").val(row.data().catvolatilidad_id);
        $("#catviaingresoorganismo_id").val(row.data().catviaingresoorganismo_id);
        // $("#catcategoriapeligrosalud_id").val(row.data().catcategoriapeligrosalud_id);
        $("#catgradoriesgosalud_id").val(row.data().catgradoriesgosalud_id);
        $("#catsustancia_fabricante").val(row.data().catsustancia_fabricante);
        $("#catsustancia_puntoEbullicion").val(row.data().catsustancia_puntoEbullicion);


        if (parseInt(row.data().catestadofisicosustancia_id) == 2) { //Si es solido mostramos el tipo
        
            $('#catsustancia_puntoEbullicion').prop('disabled', true).val('')
            $('#divPuntoEbullicion').css('display', 'none')
            $('#divTipoSolido').css('display', 'block')
            $("#catsustancia_solidoTipo").val(row.data().catsustancia_solidoTipo);

            
        } else { //Si no es solido mostramos el punto de ebullicion

            $('#divPuntoEbullicion').css('display', 'block')
            $('#divTipoSolido').css('display', 'none')
            $('#catsustancia_puntoEbullicion').prop('disabled', false)
            $("#catsustancia_puntoEbullicion").val(row.data().catsustancia_puntoEbullicion);
            
                
        }

        $('#clasificacion_riesgo_text_hoja').text(row.data().catClasificacionRiesgo);
        $('#catClasificacionRiesgo').val(row.data().catClasificacionRiesgo);

 
        //mostrar las categorias
        if (row.data().catTipoClasificacion == 1) {
            
            $('#catTipoClasificacion_cat').prop('checked', true);
            $('#divCategoriaSaludHoja').fadeIn(0)
            $('#divGradoSaludHoja').fadeOut(0)
            $('#opcion_'+row.data().catcategoriapeligrosalud_id).prop('checked', true)


        } else { //Mostrar los grados
            
            $('#catTipoClasificacion_grado').prop('checked', true);
            $('#divGradoSaludHoja').fadeIn(0)
            $('#divCategoriaSaludHoja').fadeOut(0)
            $('#catgradoriesgosalud_'+row.data().catgradoriesgosalud_id).prop('checked', true)
            
        }


        //Cargamos todos los componentes relacionados con las sustancias
        if (proceso == 0) {
            mostarSustanciasQuimicas(row.data().id)
        }




        // $("#catsustancia_hojaseguridadpdf").val(row.data().catsustancia_hojaseguridadpdf);

        // COMPONENTES
        // $("#tabla_sustanciacomponentes tbody").html('');
        // $.each( row.data().catsustanciacomponente, function( key, value )
        // {
        //     if (value.catsustanciacomponente_nombre == null) {nombre = '';}else{nombre = value.catsustanciacomponente_nombre.replace("ˏ", ",");}
        //     if (value.catsustanciacomponente_cas == null) {cas = '';}else{cas = value.catsustanciacomponente_cas;}
        //     if (value.catsustanciacomponente_ebullicion == null) {ebullicion = '';}else{ebullicion = value.catsustanciacomponente_ebullicion;}
        //     if (value.catsustanciacomponente_porcentaje == null) {porcentaje = '';}else{porcentaje = value.catsustanciacomponente_porcentaje;}
        //     if (value.catsustanciacomponente_pesomolecular == null) {peso = '';}else{peso = value.catsustanciacomponente_pesomolecular;}
        //     // if (value.catsustanciacomponente_volatilidad == null) {volatilidad = '';}else{volatilidad = value.catsustanciacomponente_volatilidad;}
        //     if (value.catsustanciacomponente_exposicionppt == null) {ppt = '';}else{ppt = value.catsustanciacomponente_exposicionppt;}
        //     if (value.catsustanciacomponente_exposicionctop == null) {ctop = '';}else{ctop = value.catsustanciacomponente_exposicionctop;}
        //     if (value.catsustanciacomponente_connotacion == null) {connotacion = '';}else{connotacion = value.catsustanciacomponente_connotacion;}

        //     // select volatilidad
        //     switch (value.catsustanciacomponente_volatilidad)
        //     {
        //         case "ND":
        //             volatilidad='<option value=""></option>'+
        //                         '<option value="ND" selected>ND</option>'+
        //                         '<option value="Baja">Baja</option>'+
        //                         '<option value="Media">Media</option>'+
        //                         '<option value="Alta">Alta</option>';
        //             break; 
        //         case "Baja":
        //             volatilidad='<option value=""></option>'+
        //                         '<option value="ND">ND</option>'+
        //                         '<option value="Baja" selected>Baja</option>'+
        //                         '<option value="Media">Media</option>'+
        //                         '<option value="Alta">Alta</option>';
        //             break; 
        //         case "Media":
        //             volatilidad='<option value=""></option>'+
        //                         '<option value="ND">ND</option>'+
        //                         '<option value="Baja">Baja</option>'+
        //                         '<option value="Media" selected>Media</option>'+
        //                         '<option value="Alta">Alta</option>';
        //             break;
        //         case "Alta":
        //             volatilidad='<option value=""></option>'+
        //                         '<option value="ND">ND</option>'+
        //                         '<option value="Baja">Baja</option>'+
        //                         '<option value="Media">Media</option>'+
        //                         '<option value="Alta" selected>Alta</option>';
        //             break;
        //         default:
        //             volatilidad='<option value=""></option>'+
        //                         '<option value="ND">ND</option>'+
        //                         '<option value="Baja">Baja</option>'+
        //                         '<option value="Media">Media</option>'+
        //                         '<option value="Alta">Alta</option>';
        //             break;
        //     }

        //     // dibujar filas componentes
        //     // $("#tabla_sustanciacomponentes tbody").append(  '<tr>'+
        //     //                                                     '<td><input type="text" class="form-control" name="componente[]" value="'+nombre+'" required></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="cas[]" value="'+cas+'" required></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="temperatura[]" value="'+ebullicion+'" required></td>'+
        //     //                                                     '<td><input type="number" step="any" class="form-control" name="porcentaje[]" value="'+porcentaje+'" required></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="peso[]" value="'+peso+'" required></td>'+
        //     //                                                     '<td><select class="custom-select form-control" style="width: 120px;" id="ef_'+key+'" name="estadofisico[]" required>'+catestadofisico_opciones+'</select></td>'+
        //     //                                                     '<td><select class="custom-select form-control" style="width: 120px;" name="volatilidad[]" required>'+volatilidad+'</select></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="limiteppt[]" value="'+ppt+'" required></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="limitectop[]" value="'+ctop+'" required></td>'+
        //     //                                                     '<td><input type="text" class="form-control" name="connotacion[]" value="'+connotacion+'" required></td>'+
        //     //                                                     '<td class="eliminar"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-trash"></i></button></td>'+
        //     //                                                 '</tr>');

        //     $('#ef_'+key).val(value.catsustanciacomponente_estadofisico);
        // });

        
        // abrir modal
        $('#modal_catsustancia').modal({backdrop:false});
    });
}


$(document).on('input change', 'input[name="PORCENTAJE"], input[name="TEM_EBULLICION"], select[name="VOLATILIDAD"], select[name="ESTADO_FISICO"]', function() {
  if ($(this).val() !== '' && $(this).val() !== null) {
      $(this).removeClass('error');
  }
});

// Limpiamos la tabla de los componentes cada vez que cerramos el modal 
$('#modal_catsustancia').on('hidden.bs.modal', function (e) {
    $('#tablaSustanciasSeleccionadas tbody').empty();
})


function mostarSustanciasQuimicas(ID) {

    proceso = 1
    $('#tablaSustanciasSeleccionadas').css('display', 'block');
	$.ajax({
		type: "get",
		dataType: "json",
		url: "/sustanciasHojasSeguridad/"+ ID,
		data:{},
		cache: false,
		success: function (data) {
            
            $('#textPorcentajes').css('display', 'block');

            swal.close()
            selectedOptionsHtml = ''
			sustancias = [];
            $.each(data.opciones, function (index, valor) {
                
                sustancias.push(valor.SUSTANCIA_QUIMICA_ID);


                  selectedOptionsHtml += `
                <tr id="sustanciaPorcentaje_${valor.SUSTANCIA_QUIMICA_ID}">
                    <td>
                        <input type="hidden" class="form-control IDSustanciaQuimica text-center" style="width: 100%;" name="SUSTANCIA_QUIMICA_ID" value="${valor.SUSTANCIA_QUIMICA_ID}">
                        <label class="form-check-label" for="sustancia_${valor.SUSTANCIA_QUIMICA_ID}">[${valor.NUM_CAS}] ${valor.SUSTANCIA_QUIMICA}</label>
                    </td>
                    <td>
                        <select class="custom-select form-control" id="tipoSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="TIPO" style="width: 100%;" onchange="validarCamposRequeridos('porcentajeSustancia_${valor.SUSTANCIA_QUIMICA_ID}', 'temSustancia_${valor.SUSTANCIA_QUIMICA_ID}', this.value)" required>
                            <option value="1">Componente</option>
                            <option value="2">Subproducto</option>
                        </select>
                    </td>
                    <td>
                        <select class="custom-select form-control" id="operadorSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="OPERADOR" style="width: 100%;" >
                            <option value="*" > * </option>
                            <option value="<"> < </option>
                            <option value=">"> > </option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control porcentajeSustancias text-center" style="width: 100%;" id="porcentajeSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="PORCENTAJE" style="width: 100%;" placeholder="% Componente" min="0" value="${valor.PORCENTAJE}">
                    </td>
                    <td>
                        <select class="custom-select form-control" id="estadoSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="ESTADO_FISICO" style="width: 100%;" required onchange="cambiarFormaSustancia('formaSustancia_${valor.SUSTANCIA_QUIMICA_ID}','valatilidadSustancia_${valor.SUSTANCIA_QUIMICA_ID}' ,this.value)">
                            <option value="" selected disabled> Estado Fisico </option>
                            <option value="1"> Líquido </option>
                            <option value="2"> Sólido </option>
                            <option value="6"> Gas </option>
                            <option value="100"> ND </option>
                        </select>
                    </td>
                    <td>
                        <select class="custom-select form-control" onchange="cambiarVolatilidadSustancia('valatilidadSustancia_${valor.SUSTANCIA_QUIMICA_ID}', this.value)"  id="formaSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="FORMA_SUSTANCIA" style="width: 100%;">
                            <option value="" selected disabled> Forma </option>
                        </select>
                    </td>
                    <td style="display: flex; align-items: center;">
                            <button type="button" class="btn btn-danger text-center mb-1" style="margin-right: 10px; width: 35px; height: 35px; border-radius: 9px;margin-left: 10px;" data-toggle="tooltip" title="Click para cambiar la Tem. de ebullición a °C una vez insertada en °F" onclick="cambiarGrados('temSustancia_${valor.SUSTANCIA_QUIMICA_ID}')">
                                <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                            </button>

                        <input type="text" onkeyup="cambiarVolatilidadSustanciaTem('estadoSustancia_${valor.SUSTANCIA_QUIMICA_ID}','valatilidadSustancia_${valor.SUSTANCIA_QUIMICA_ID}' ,this.value)" class="form-control text-center" id="temSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="TEM_EBULLICION" style="width: 100%;" placeholder="Tem. de ebullición (°C)" min="0"  value="${valor.TEM_EBULLICION}">
                    </td>
                    <td>
                        <select class="custom-select form-control" id="valatilidadSustancia_${valor.SUSTANCIA_QUIMICA_ID}" name="VOLATILIDAD" style="width: 100%;" required>
                            <option value="" selected disabled> Volatilidad </option>
                            <option value="1"> Baja </option>
                            <option value="2"> Media </option>
                            <option value="3"> Alta </option>
                            <option value="100"> ND </option>
                        </select>
                    </td>
                </tr> `;

			});


            $('#tablaSustanciasSeleccionadas tbody').append(selectedOptionsHtml);

            //ESPERAMOS QUE NUESTRO DOM TERMINE DE CARGAR PARA PODER ASEIGNAR VALORES A LOS SELECT
            setTimeout(function () {
                $.each(data.opciones, function (index, valor) {
                    $(`#operadorSustancia_${valor.SUSTANCIA_QUIMICA_ID}`).val(valor.OPERADOR);
                    $(`#estadoSustancia_${valor.SUSTANCIA_QUIMICA_ID}`).val(valor.ESTADO_FISICO);
                    $(`#valatilidadSustancia_${valor.SUSTANCIA_QUIMICA_ID}`).val(valor.VOLATILIDAD);
                    $(`#tipoSustancia_${valor.SUSTANCIA_QUIMICA_ID}`).val(valor.TIPO);

                    // EJECUTAMOS LA FUNCION QUE CAMBIA LAS OPCIONES DE NUESTRO SELECT DE FORMAS
                    cambiarFormaSustancia(`formaSustancia_${valor.SUSTANCIA_QUIMICA_ID}`, `valatilidadSustancia_${valor.id}`, valor.ESTADO_FISICO)

                    console.log(valor.FORMA)
                    // ASIGNAMOS CALOR UNA VEZ CAMBIADA LAS OPCIONES
                    $(`#formaSustancia_${valor.SUSTANCIA_QUIMICA_ID}`).val(valor.FORMA);

                });


            }, 100);


			$('#sustancias_quimicias').val(sustancias);
            $('#sustancias_quimicias').trigger('change');
            
            proceso = 0



		},
        beforeSend: function () {
             swal({
                title: "Espere un momento",
                    text: "Estamos consultando los componentes ...",
                type: "info", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                showConfirmButton: false
            });
        },
		error: function(dato){
		
		}
	});
	
	
}


function cambia_estado_registro(num_catalogo, registro_id, checkbox)
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
        url: "/recsensorialquimicoscataloestado/"+num_catalogo+"/"+registro_id+"/"+estado,
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


// ======================================================


function function_tabla_catestadofisico(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catestadofisico != null)
        {
            tabla_catestadofisico.destroy();
            tabla_catestadofisico = null;
        }

        tabla_catestadofisico = $('#tabla_catestadofisico').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catestadofisico(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "catestadofisicosustancia_estado",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        function_tabla_catestadofisico(num_catalogo);
    }
}



function boton_nuevo_catestadofisico(){
    // Borrar formulario
    $('#form_catestadofisico').each(function(){
        this.reset();
    });

    // campos hidden
    $('#catestadofisico_id').val(0);
    $('#catestadofisicosustancia_activo').val(1);
    $('#catestadofisico_catalogo').val(2);

    // abrir modal
    $('#modal_catestadofisico').modal({backdrop:false});
}


function selecciona_catestadofisico()
{
    $('#tabla_catestadofisico tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catestadofisico.row(tr);

        // campos hidden
        $('#catestadofisico_id').val(row.data().id);
        $('#catestadofisicosustancia_activo').val(row.data().catsustancia_activo);
        $('#catestadofisico_catalogo').val(2);

        // campos visibles
        $("#catestadofisicosustancia_estado").val(row.data().catestadofisicosustancia_estado);

        // abrir modal
        $('#modal_catestadofisico').modal({backdrop:false});
    });
}


$("#boton_guardar_catestadofisico").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catestadofisico').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catestadofisico.ajax.url("/recsensorialquimicoscatalogostabla/2").load();

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
                $('#boton_guardar_catestadofisico').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catestadofisico').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catestadofisico').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catestadofisico').html('Guardar <i class="fa fa-save"></i>');
                
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


// ======================================================


function function_tabla_catvolatilidad(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catvolatilidad != null)
        {
            tabla_catvolatilidad.destroy();
            tabla_catvolatilidad = null;
        }

        tabla_catvolatilidad = $('#tabla_catvolatilidad').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catvolatilidad(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "catvolatilidad_tipo",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "catvolatilidad_ponderacion",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catvolatilidad(num_catalogo);
    }
}


function boton_nuevo_catvolatilidad(){
    // Borrar formulario
    $('#form_catvolatilidad').each(function(){
        this.reset();
    });

    // campos hidden
    $('#volatilidad_id').val(0);
    $('#volatilidad_activo').val(1);
    $('#volatilidad_catalogo').val(3);

    // abrir modal
    $('#modal_catvolatilidad').modal({backdrop:false});
}


function selecciona_catvolatilidad()
{
    $('#tabla_catvolatilidad tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catvolatilidad.row(tr);

        // campos hidden
        $('#volatilidad_id').val(row.data().id);
        $('#volatilidad_activo').val(row.data().catvolatilidad_activo);
        $('#catvolatilidad_ponderacion').val(row.data().catvolatilidad_ponderacion);
        $('#volatilidad_catalogo').val(3);

        // campos visibles
        $("#catvolatilidad_tipo").val(row.data().catvolatilidad_tipo);

        // abrir modal
        $('#modal_catvolatilidad').modal({backdrop:false});
    });
}


$("#boton_guardar_catvolatilidad").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catvolatilidad').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catvolatilidad.ajax.url("/recsensorialquimicoscatalogostabla/3").load();

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
                $('#boton_guardar_catvolatilidad').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catvolatilidad').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catvolatilidad').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catvolatilidad').html('Guardar <i class="fa fa-save"></i>');
                
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


// ======================================================


function function_tabla_catviaingreso(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catviaingreso != null)
        {
            tabla_catviaingreso.destroy();
            tabla_catviaingreso = null;
        }

        tabla_catviaingreso = $('#tabla_catviaingreso').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catviaingreso(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "catviaingresoorganismo_viaingreso",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "catviaingresoorganismo_ponderacion",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "catviaingresoorganismo_poe",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "catviaingresoorganismo_horasexposicion",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catviaingreso(num_catalogo);
    }
}


function boton_nuevo_catviaingreso(){
    // Borrar formulario
    $('#form_catviaingreso').each(function(){
        this.reset();
    });

    // campos hidden
    $('#catviaingreso_id').val(0);
    $('#catviaingreso_activo').val(1);
    $('#catviaingreso_catalogo').val(4);

    // abrir modal
    $('#modal_catviaingreso').modal({backdrop:false});
}


function selecciona_catviaingreso()
{
    $('#tabla_catviaingreso tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catviaingreso.row(tr);

        // campos hidden
        $('#catviaingreso_id').val(row.data().id);
        $('#catviaingreso_activo').val(row.data().catviaingresoorganismo_activo);
        $('#catviaingreso_catalogo').val(4);

        // campos visibles
        $("#catviaingresoorganismo_viaingreso").val(row.data().catviaingresoorganismo_viaingreso);
        $("#catviaingresoorganismo_ponderacion").val(row.data().catviaingresoorganismo_ponderacion);
        $("#catviaingresoorganismo_poe").val(row.data().catviaingresoorganismo_poe);
        $("#catviaingresoorganismo_horasexposicion").val(row.data().catviaingresoorganismo_horasexposicion);

        // abrir modal
        $('#modal_catviaingreso').modal({backdrop:false});
    });
}


$("#boton_guardar_catviaingreso").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catviaingreso').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catviaingreso.ajax.url("/recsensorialquimicoscatalogostabla/4").load();

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
                $('#boton_guardar_catviaingreso').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catviaingreso').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catviaingreso').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catviaingreso').html('Guardar <i class="fa fa-save"></i>');
                
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


// ======================================================


function function_tabla_catpeligro(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catpeligro != null)
        {
            tabla_catpeligro.destroy();
            tabla_catpeligro = null;
        }

        tabla_catpeligro = $('#tabla_catpeligro').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catpeligro(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                {
                    "data": "id" 
                },
                {
                    "data": "catcategoriapeligrosalud_codigo",
                    "defaultContent": '-'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        function_tabla_catpeligro(num_catalogo);
    }
}


function boton_nuevo_catpeligro(){
    // Borrar formulario
    $('#form_catpeligro').each(function(){
        this.reset();
    });

    // campos hidden
    $('#catpeligro_id').val(0);
    $('#catpeligro_activo').val(1);
    $('#catpeligro_catalogo').val(5);
    $('#campo_catpeligro_id').val('');

    // abrir modal
    $('#modal_catpeligro').modal({backdrop:false});
}


function selecciona_catpeligro()
{
    $('#tabla_catpeligro tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catpeligro.row(tr);

        // campos hidden
        $('#catpeligro_id').val(row.data().id);
        $('#catpeligro_activo').val(row.data().catcategoriapeligrosalud_activo);
        $('#catpeligro_catalogo').val(5);

        // campos visibles
        $("#campo_catpeligro_id").val(row.data().id);
        $("#catcategoriapeligrosalud_codigo").val(row.data().catcategoriapeligrosalud_codigo);
        $("#catcategoriapeligrosalud_descripcion").val(row.data().catcategoriapeligrosalud_descripcion);


        // abrir modal
        $('#modal_catpeligro').modal({backdrop:false});
    });
}


$("#boton_guardar_catpeligro").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catpeligro').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catpeligro.ajax.url("/recsensorialquimicoscatalogostabla/5").load();

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
                $('#boton_guardar_catpeligro').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catpeligro').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catpeligro').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catpeligro').html('Guardar <i class="fa fa-save"></i>');
                
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


// ======================================================


function function_tabla_catgradoriesgo(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catgradoriesgo != null)
        {
            tabla_catgradoriesgo.destroy();
            tabla_catgradoriesgo = null;
        }

        tabla_catgradoriesgo = $('#tabla_catgradoriesgo').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catgradoriesgo(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                {
                    "data": "id" 
                },
                {
                    "data": "catgradoriesgosalud_clasificacion",
                    "defaultContent": '-'
                },
                {
                    "data": "catgradoriesgosalud_viaingresooral",
                    "defaultContent": '-'
                },
                {
                    "data": "catgradoriesgosalud_viaingresopiel",
                    "defaultContent": '-'
                },
                {
                    "data": "catgradoriesgosalud_viaingresoinhalacion",
                    "defaultContent": '-'
                },
                {
                    "data": "catgradoriesgosalud_ponderacion",
                    "defaultContent": '-'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 4, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catgradoriesgo(num_catalogo);
    }
}


function boton_nuevo_catgradoriesgo(){
    // Borrar formulario
    $('#form_catgradoriesgo').each(function(){
        this.reset();
    });

    // campos hidden
    $('#catgradoriesgo_id').val(0);
    $('#catgradoriesgo_activo').val(1);
    $('#catgradoriesgo_catalogo').val(6);
    $('#campo_catgradoriesgo_id').val('');

    // abrir modal
    $('#modal_catgradoriesgo').modal({backdrop:false});
}


function selecciona_catgradoriesgo()
{
    $('#tabla_catgradoriesgo tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catgradoriesgo.row(tr);

        // campos hidden
        $('#catgradoriesgo_id').val(row.data().id);
        $('#catgradoriesgo_activo').val(row.data().catgradoriesgosalud_activo);
        $('#catgradoriesgo_catalogo').val(6);

        // campos visibles
        $("#campo_catgradoriesgo_id").val(row.data().id);
        $("#catgradoriesgosalud_clasificacion").val(row.data().catgradoriesgosalud_clasificacion);
        $("#catgradoriesgosalud_viaingresooral").val(row.data().catgradoriesgosalud_viaingresooral);
        $("#catgradoriesgosalud_viaingresopiel").val(row.data().catgradoriesgosalud_viaingresopiel);
        $("#catgradoriesgosalud_viaingresoinhalacion").val(row.data().catgradoriesgosalud_viaingresoinhalacion);
        $("#catgradoriesgosalud_ponderacion").val(row.data().catgradoriesgosalud_ponderacion);

        // abrir modal
        $('#modal_catgradoriesgo').modal({backdrop:false});
    });
}


$("#boton_guardar_catgradoriesgo").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catgradoriesgo').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catgradoriesgo.ajax.url("/recsensorialquimicoscatalogostabla/6").load();

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
                $('#boton_guardar_catgradoriesgo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catgradoriesgo').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catgradoriesgo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catgradoriesgo').html('Guardar <i class="fa fa-save"></i>');
                
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


function selecciona_sustancia_quimico()
{
    $('#tabla_catsustancia_quimicas tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustanciaQuimicas.row(tr);

        // campos hidden
        $('#ID_SUSTANCIA_QUIMICA').val(row.data().ID_SUSTANCIA_QUIMICA);
        $('#ACTIVO').val(row.data().ACTIVO);
        $('#SUSTANCIA_QUIMICO_CATALAGO').val(0);

        // campos visibles
        $("#SUSTANCIA_QUIMICA").val(row.data().SUSTANCIA_QUIMICA);
        $("#ALTERACION_EFECTO").val(row.data().ALTERACION_EFECTO);
        $("#PM").val(row.data().PM);
        $("#NUM_CAS").val(row.data().NUM_CAS);

        $("#VIA_INGRESO").val(row.data().VIA_INGRESO);

        $('#clasificacion_riesgo_text').text(row.data().CLASIFICACION_RIESGO);
        $('#CLASIFICACION_RIESGO').val(row.data().CLASIFICACION_RIESGO);

        
        //mostrar las categorias
        if (row.data().TIPO_CLASIFICACION == 1) {
            
            $('#TIPO_CLASIFICACION_CAT').prop('checked', true);
            $('#divCategoriaSalud').fadeIn(0)
            $('#divGradoSalud').fadeOut(0)
            $('#CATEGORIA_PELIGRO_'+row.data().CATEGORIA_PELIGRO_ID).prop('checked', true)


        } else { //Mostrar los grados
            
            $('#TIPO_CLASIFICACION_GRADO').prop('checked', true);
            $('#divGradoSalud').fadeIn(0)
            $('#divCategoriaSalud').fadeOut(0)
            $('#GRADO_RIESGO_'+row.data().GRADO_RIESGO_ID).prop('checked', true)
            
        }
        
        SUSTANCIA_QUIMICA_ID = row.data().ID_SUSTANCIA_QUIMICA
        
        $('.tablaEntidades').fadeIn(0)

        if (procesoEntidad == 0) {
            
            //INICIAMOS LA TABLA DE LOS DATOS POR ENTIDADES
            tablaSustanciasQuimicasEntidades(SUSTANCIA_QUIMICA_ID)
            tablaMetodosSustanciasQuimicas(SUSTANCIA_QUIMICA_ID)
            tablaBeiSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);

            
        }
         var submitButtons = $('#form_catSustanciQuimica').find('input[type="submit"], button[type="submit"]').show();

        // abrir modal
        $('#modal_catSustanciaQuimica').modal({backdrop:false});
    });
}


$("#boton_guardar_catSustanciaQuimica").click(function(){
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        
       

        // enviar datos
        $('#form_catSustanciQuimica').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catsustanciaQuimicas.ajax.url("/recsensorialquimicoscatalogostabla/0").load();

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
                $('#boton_guardar_catSustanciaQuimica').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catSustanciaQuimica').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catSustanciaQuimica').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catSustanciaQuimica').html('Guardar <i class="fa fa-save"></i>');
                
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


$('input[name="TIPO_CLASIFICACION"]').change(function() {
    
    $('#clasificacion_riesgo_text').text('')
    $('#CLASIFICACION_RIESGO').val('')

	var valor = $(this).val();
	if (valor === '1') {
        $('#divCategoriaSalud').fadeIn(2);
        $('#divGradoSalud').fadeOut(1);
        $('.GRADO').prop('checked', false);
        
    } else if (valor === '0') {	
        
        $('#divGradoSalud').fadeIn(2);
        $('#divCategoriaSalud').fadeOut(1);
        $('.CATEGORIA').prop('checked', false);

		
	}	
});


$('input[name="CATEGORIA_PELIGRO_ID"]').change(function() {
	
    valor = parseInt($(this).data("riesgo_cat"))

    $('#clasificacion_riesgo_text').text(valor)
    $('#CLASIFICACION_RIESGO').val(valor)

});

$('input[name="GRADO_RIESGO_ID"]').change(function() {
	
    valor = parseInt($(this).data("riesgo_grado"))

    $('#clasificacion_riesgo_text').text(valor)
    $('#CLASIFICACION_RIESGO').val(valor)

});


$('#ENTIDAD').on('change', function () {
    normatividad = $(this).val();

    if (normatividad == 1) {
        $('#DESCRIPCION_NORMATIVA').val('NOM-010-STPS-2014').prop('readonly', true);
        $('#DESCRIPCION_ENTIDAD').val('NOM-010').prop('readonly', true);

    } else if (normatividad == 2) {
        $('#DESCRIPCION_NORMATIVA').val('ACGIH').prop('readonly', true);
        $('#DESCRIPCION_ENTIDAD').val('ACGIH').prop('readonly', true);

    } else {
        $('#DESCRIPCION_NORMATIVA').val('').prop('readonly', false);
        $('#DESCRIPCION_ENTIDAD').val('').prop('readonly', false)
        
        
    }

})


//Validamos si las sustancias requieren de BEIs
$('input[name="TIENE_BEIS"]').change(function() {
	
	var valor = $(this).val();
	if (valor === '1') {
        $('#div_btn_agregarBeis').css('display','block');
	} else if (valor === '0') {	
        $('#div_btn_agregarBeis').css('display','none');
		
	}	
});

function boton_nueva_sustancia_quimica(){
    // Borrar formulario
    $('#form_catSustanciQuimica').each(function(){
        this.reset();
    });

    var submitButtons = $('#form_catSustanciQuimica').find('input[type="submit"], button[type="submit"]').show();

    // campos hidden
    $('#ID_SUSTANCIA_QUIMICA').val(0);
    $('#ACTIVO').val(1);
    $('#SUSTANCIA_QUIMICO_CATALAGO').val(0);

    $('#CLASIFICACION_RIESGO').val('')
    $('#clasificacion_riesgo_text').text('')

    $('#divCategoriaSalud').fadeIn(0)
    $('#divGradoSalud').fadeOut(0)

    $('.tablaEntidades').fadeOut(0)

    // abrir modal
    $('#modal_catSustanciaQuimica').modal({backdrop:false});
}




function obtenerBEIs(data) {
    
    let row = data.data().JSON_BEIS;
    var beis = JSON.parse(row);

    // Recorrer cada contacto
    $.each(beis, function(index, b) {
        // Acceder a cada propiedad del contacto
        var determinante = b.DETERMINATE;
        var tiempo_muestreo = b.TIEMPO_MUESTREO;
        var bei = b.BEI;
        var notacion = b.NOTACION;


       const divBais = document.createElement('div');
        divBais.classList.add('row', 'NuevoagregarBeis','m-2');
        divBais.innerHTML = `
        <div class="col-3">
            <div class="form-group">
                <label>Determinante: *</label>
                <input type="text" class="form-control" name="DETERMINATE" value="${determinante}" required>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label>Tiempo de muestreo: *</label>
                <input type="text" class="form-control" name="TIEMPO_MUESTREO" value="${tiempo_muestreo}" required>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label> IBE *</label>
                <input type="text" class="form-control" name="BEI" value="${bei}" required>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label> Notación *</label>
                <input type="text" class="form-control" name="NOTACION" value="${notacion}" required>
            </div>
        </div>
        <div class="col-1 mt-4">
			<button type="button" class="btn btn-danger btn-circle botonEliminarBais"> <i class="fa fa-trash"></i></button>
        </div>
        
        `;
        // const contenedor = document.querySelector('.agregarBeis');
        // contenedor.appendChild(divBais);

        const botonEliminar = divBais.querySelector('.botonEliminarBais');
        botonEliminar.addEventListener('click', function() {
            contenedor.removeChild(divBais);
        });

    });
}






$('#boton_nueva_sustanciaEntidad').on('click', function (e) {
    e.preventDefault();
    
     if ($('#CONNOTACION')[0].selectize) {
        var selectize = $('#CONNOTACION')[0].selectize;
        selectize.clear();
         selectize.enable();

    } else {
        
         $('#CONNOTACION').prop('disabled', false);
        $('#CONNOTACION').html('');
        $('#CONNOTACION').val('');
        
    }

    $('#opciones_seleccionadas').html('');
    

    $('#form_catSustanciQuimicaEntidad').each(function () {
        this.reset();
    });


    $(".agregarBeis").empty();

    // campos hidden
    $('#SUSTANCIA_QUIMICA_ID').val(SUSTANCIA_QUIMICA_ID);
    $('#ID_SUSTANCIA_QUIMICA_ENTIDAD').val(0);
    $('#ACTIVO').val(1);
    $('#SUSTANCIA_QUIMICO_CATALAGO').val(7);


    $('#titulo_modal_sustancia_entidad').html('Nueva Entidad para la sustancia: ' + $('#SUSTANCIA_QUIMICA').val());

    // abrir modal
    $('#modal_catSustanciaQuimicaEntidad').modal({backdrop:false});


})


$("#boton_guardar_catSustanciaQuimicaEntidad").click(function(){
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {
        
        var BeisSustancias = [];
        if (parseInt($('input[name=TIENE_BEIS]').val()) == 1 ){    
            $(".NuevoagregarBeis").each(function() {
                var Beis = {
                    'DETERMINATE': $(this).find("input[name='DETERMINATE']").val(),
                    'TIEMPO_MUESTREO': $(this).find("input[name='TIEMPO_MUESTREO']").val(),
                    'BEI': $(this).find("input[name='BEI']").val(),
                    'NOTACION': $(this).find("input[name='NOTACION']").val()
                };
                BeisSustancias.push(Beis);
            });
        }



        // enviar datos
        $('#form_catSustanciQuimicaEntidad').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {
                JSON_NUEVOS_BEIS: BeisSustancias && BeisSustancias.length > 0 ? JSON.stringify(BeisSustancias) : undefined,
            },
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catsustanciaQuimicasEntidad.ajax.url("/tablasustanciasEntidad/" + SUSTANCIA_QUIMICA_ID).load();
                tabla_catsustanciaQuimicas.ajax.url("/recsensorialquimicoscatalogostabla/0").load();


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
                $('#boton_guardar_catSustanciaQuimicaEntidad').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catSustanciaQuimicaEntidad').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catSustanciaQuimicaEntidad').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catSustanciaQuimicaEntidad').html('Guardar <i class="fa fa-save"></i>');
                
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


$('#boton_nueva_metodo').on('click', function (e) {
    e.preventDefault();

    $('#form_metodosSustanciasQuimicas').each(function () {
        this.reset();
    });


    // campos hidden
    $('#METODO_SUSTANCIA_QUIMICA_ID').val(SUSTANCIA_QUIMICA_ID);
    $('#ID_METODO').val(0);
    $('#ELIMINAR_METODO').val(0);
    $('#CATALOGO_METODO').val(133);


    $('#titulo_modal_metodo').html('Método de evaluación para ' + $('#SUSTANCIA_QUIMICA').val());

    // abrir modal
    $('#modal_metodosSustanciasQuimicas').modal({backdrop:false});


})


$("#boton_guardar_metodo").click(function(){
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {

        $('#form_metodosSustanciasQuimicas').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato){
                // actualiza tabla
                tabla_metodosSustanciasQuimicas.ajax.url("/listaMetodosSustanciasQuimicas/" + SUSTANCIA_QUIMICA_ID).load();

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
                $('#boton_guardar_metodo').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_metodosSustanciasQuimicas').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_metodo').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_metodo').html('Guardar <i class="fa fa-save"></i>');
                
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

$('#boton_nueva_bei').on('click', function (e) {
    e.preventDefault();

    $('#form_beiSustancias').each(function () {
        this.reset();
    });


    if ($('#NOTACION_BEI')[0].selectize) {
        var selectize = $('#NOTACION_BEI')[0].selectize;
        selectize.clear();
         selectize.enable();

    } else {
        
        $('#NOTACION_BEI').html('');
        $('#NOTACION_BEI').val('');
        
    }


    $('#opciones_seleccionadas').html('');

    $('#opciones_seleccionadas_bei').html('');


    // campos hidden
    $('#SUSTANCIA_QUIMICA_ID_BEI').val(SUSTANCIA_QUIMICA_ID);
    $('#ID_BEI').val(0);
    $('#ELIMINAR_BEI').val(0);


    $('#titulo_modal_bei_sustancia').html('Nueva BEI para la sustancia: ' + $('#SUSTANCIA_QUIMICA').val());

    // abrir modal
    $('#modal_beiSustancias').modal({backdrop:false});

})


$("#boton_guardar_beiSustancias").click(function(){
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida) {

        $('#form_beiSustancias').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato){
                // actualiza tabla
                tabla_beiSustanciasQuimicas.ajax.url("/listaBeiSustanciasQuimicas/" + SUSTANCIA_QUIMICA_ID).load();

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
                $('#boton_guardar_beiSustancias').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_beiSustancias').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_beiSustancias').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_beiSustancias').html('Guardar <i class="fa fa-save"></i>');
                
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

function tablaSustanciasQuimicasEntidades(SUSTANCIA_QUIMICA_ID) {
    
    procesoEntidad = 1
	// Inicializar tabla
    if (tabla_catsustanciaQuimicasEntidad != null)
    {
        tabla_catsustanciaQuimicasEntidad.destroy();
        tabla_catsustanciaQuimicasEntidad = null;
    }

    try
    {
        tabla_catsustanciaQuimicasEntidad = $('#tabla_catSustanciasQuimicaEntidad').DataTable({
            "ajax": {
                "url": "/tablasustanciasEntidad/"+ SUSTANCIA_QUIMICA_ID,
                "type": "get",
                "cache": false,
                "data": {},
                error: function (xhr, error, code){

                    tablaSustanciasQuimicasEntidades(SUSTANCIA_QUIMICA_ID);
                },
                complete: function () {
                        procesoEntidad = 0

                }
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ENTIDAD",
                    "defaultContent": ''
                },
                {
                    "data": "DESCRIPCION_NORMATIVA",
                    "defaultContent": ''
                },
                {
                    "data": "listaConnotaciones",
                    "defaultContent": ''
                },
                {
                    "data": "VLE_PPT",
                    "defaultContent": ''
                },
                {
                    "data": "VLE_CT_P",
                    "defaultContent": ''
                },
                {
                    "orderable": false,
                    "data": 'acciones',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros",
                "zeroRecords": "",
                "info": "", //Página _PAGE_ de _PAGES_
                "infoEmpty": "",
                "infoFiltered": "(Filtrado de _MAX_ registros)",
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando datos....",
                "processing": "Consultando entidades <i class='fa fa-spin fa-spinner fa-3x'></i>",
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        tablaSustanciasQuimicasEntidades(SUSTANCIA_QUIMICA_ID);
    }
}


function tablaMetodosSustanciasQuimicas(SUSTANCIA_QUIMICA_ID) {
    
    procesoMetodo = 1
	// Inicializar tabla
    if (tabla_metodosSustanciasQuimicas != null)
    {
        tabla_metodosSustanciasQuimicas.destroy();
        tabla_metodosSustanciasQuimicas = null;
    }

    try
    {
        tabla_metodosSustanciasQuimicas = $('#tabla_metodosSustanciasQuimicas').DataTable({
            "ajax": {
                "url": "/listaMetodosSustanciasQuimicas/"+ SUSTANCIA_QUIMICA_ID,
                "type": "get",
                "cache": false,
                "data": {},
                error: function (xhr, error, code){

                    tablaMetodosSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);
                },
                complete: function () {
                       procesoMetodo = 0

                }
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": ''
                },
                {
                    "data": "boton_editar",
                    "defaultContent": ''
                },
                {
                    "data": "boton_eliminar",
                    "defaultContent": ''
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros",
                "zeroRecords": "",
                "info": "", //Página _PAGE_ de _PAGES_
                "infoEmpty": "",
                "infoFiltered": "(Filtrado de _MAX_ registros)",
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando datos....",
                "processing": "Consultando metodos <i class='fa fa-spin fa-spinner fa-3x'></i>",
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        tablaMetodosSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);
    }
}


function tablaBeiSustanciasQuimicas(SUSTANCIA_QUIMICA_ID) {
    
    procesoMetodo = 1
	// Inicializar tabla
    if (tabla_beiSustanciasQuimicas != null)
    {
        tabla_beiSustanciasQuimicas.destroy();
        tabla_beiSustanciasQuimicas = null;
    }

    try
    {
        tabla_beiSustanciasQuimicas = $('#tabla_beiSustanciasQuimicas').DataTable({
            "ajax": {
                "url": "/listaBeiSustanciasQuimicas/"+ SUSTANCIA_QUIMICA_ID,
                "type": "get",
                "cache": false,
                "data": {},
                error: function (xhr, error, code){

                    tablaBeiSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);
                },
                complete: function () {
                       procesoMetodo = 0

                }
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ENTIDAD_NOMBRE",
                    "defaultContent": ''
                },
                {
                    "data": "DETERMINANTE",
                    "defaultContent": ''
                },
                {
                    "data": "TIEMPO_MUESTREO",
                    "defaultContent": ''
                },
                {
                    "data": "VALOR_REFERENCIA",
                    "defaultContent": ''
                },
                {
                    "data": "boton_editar",
                    "defaultContent": ''
                },
                {
                    "data": "boton_eliminar",
                    "defaultContent": ''
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": false,
            "processing": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros",
                "zeroRecords": "",
                "info": "", //Página _PAGE_ de _PAGES_
                "infoEmpty": "",
                "infoFiltered": "(Filtrado de _MAX_ registros)",
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando datos....",
                "processing": "Consultando BEIs <i class='fa fa-spin fa-spinner fa-3x'></i>",
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        tablaBeiSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);
    }
}

$('#ENTIDAD_ID').on('change', function () {
    $('#opciones_seleccionadas').html('');
    
    var valorSeleccionado = $(this).find("option:selected");
    var infoAdicional = valorSeleccionado.data("descripcion");
    $('#DESCRIPCION_NORMATIVA').val(infoAdicional);

    if ($('#CONNOTACION')[0].selectize) {
        var selectize = $('#CONNOTACION')[0].selectize;
        selectize.clear();
        selectize.clearOptions();
        selectize.enable();
    } else {
        $('#CONNOTACION').prop('disabled', false);
        $('#CONNOTACION').html('');
        $('#CONNOTACION').val('');
    }

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/listaConnotaciones/" + $(this).val(),
        data: {},
        cache: false,
        success: function (dato) {
            var opciones = dato.opciones;

            if ($('#CONNOTACION')[0].selectize) {
                var selectize = $('#CONNOTACION')[0].selectize;
                selectize.clearOptions();  // Limpia las opciones anteriores
                selectize.addOption(opciones);  // Añade las nuevas opciones
                selectize.refreshOptions(false);  // Refresca el dropdown
            } else {
                $('#CONNOTACION').selectize({
                    options: opciones,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: "Seleccione una o más connotaciones",
                    render: {
                        option: function (item, escape) {
                            return `
                                <div>
                                    ${escape(item.text)}
                                    <small>${escape(item.description)}</small>
                                </div>
                            `;
                        }
                    }
                });

                var selectize = $('#CONNOTACION')[0].selectize;

                selectize.on('change', function () {
                    actualizarDescripcionConnotacion();
                });
            }
        },
        beforeSend: function () {
            // $('#CONNOTACION').html('<option selected>Consultando datos...</option>');
        },
        error: function (dato) {
            $('#CONNOTACION').html('<option value="" disabled>Error al consultar los datos</option>');
            return false;
        }
    });

    function actualizarDescripcionConnotacion() {
        var selectize = $('#CONNOTACION')[0].selectize;
        var selectedValues = selectize.getValue();
        var selectedOptionsHtml = '';
        var numSustancias = 0;

        selectedValues.forEach(value => {
            var option = selectize.options[value];
            if (option) {
                numSustancias++;
                selectedOptionsHtml += `
                    <div id="descripcionConnotacion" class="mb-2">
                        <label class="form-check-label">${option.text} : ${option.description || ''}</label>
                    </div>
                `;
            }
        });

        $('#opciones_seleccionadas').html(selectedOptionsHtml); 
    }
});



function mostarConnotacionesSelccionadas(ID_ENTIDAD, ID_SUSTANCIA_ENTIDAD) {
    $('#opciones_seleccionadas').html('');


    if ($('#CONNOTACION')[0].selectize) {
        var selectize = $('#CONNOTACION')[0].selectize;
        selectize.clear();
    } else {

        $('#CONNOTACION').html('');
        
    }


    if (procesoEntidades == 0) {
        procesoEntidades = 1;

        $.ajax({
            type: "get",
            dataType: "json",
            url: "/connotacionesSeleccionada/" + ID_ENTIDAD + '/' + ID_SUSTANCIA_ENTIDAD,
            data: {},
            cache: false,
            success: function (data) {
                // Verifica si Selectize ya está inicializado y destruye la instancia
                if ($('#CONNOTACION')[0].selectize) {
                    $('#CONNOTACION')[0].selectize.destroy();
                }

                // Inicializa Selectize con las opciones deseadas
                $('#CONNOTACION').selectize({
                    options: data.opciones,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: "Seleccione una o más connotaciones",
                    render: {
                        option: function (item, escape) {
                            return `
                                <div>
                                    ${escape(item.text)}
                                    <small>${escape(item.description || '')}</small>
                                </div>
                            `;
                        }
                    }
                });

                // Obtener la instancia de Selectize
                var selectize = $('#CONNOTACION')[0].selectize;

                // Asignar valor a las connotaciones seleccionadas
                if (Array.isArray(data.connotacionesSeleccionadas)) {
                    selectize.setValue(data.connotacionesSeleccionadas);
                }

                // Llama a la función que muestra las descripciones de las connotaciones
                actualizarDescripcionConnotacion2();

                 // Ahora puedes utilizar selectize para manejar la selección y deselección
                var selectize = $('#CONNOTACION')[0].selectize;

                // Evento de cambio de selección
                selectize.on('change', function () {
                    actualizarDescripcionConnotacion();
                });

                function actualizarDescripcionConnotacion() {
                    var selectedValues = selectize.getValue();
                    var selectedOptionsHtml = '';
                    var numSustancias = 0;

                    selectedValues.forEach(value => {
                        var option = selectize.options[value];
                        if (option) {
                            numSustancias++;
                            selectedOptionsHtml += `
                                <div id="descripcionConnotacion" class="mb-2">
                                    <label class="form-check-label">${option.text} : ${option.description || ''} </label>
                                </div>
                            `;
                        }
                    });

                    $('#opciones_seleccionadas').html(selectedOptionsHtml);
                }

                procesoEntidades = 0;
            },
            beforeSend: function () {
                // $('#CONNOTACION').html('<option selected>Consultando datos...</option>');
            },
            error: function (dato) {
                console.error("Error al consultar los datos:", dato);
            }
        });
    }
}

function actualizarDescripcionConnotacion2() {
    var selectize = $('#CONNOTACION')[0].selectize;
    var selectedValues = selectize.getValue();
    var selectedOptionsHtml = '';

    selectedValues.forEach(value => {
        var option = selectize.options[value];
        if (option) {
            selectedOptionsHtml += `
                <div id="descripcionConnotacion" class="mb-2">
                    <label class="form-check-label">${option.text} : ${option.description || ''} </label>
                </div>
            `;
        }
    });

    $('#opciones_seleccionadas').html(selectedOptionsHtml);
}


$('#ENTIDAD_ID_BEI').on('change', function () {
    $('#opciones_seleccionadas_bei').html('');
    
    var valorSeleccionado = $(this).find("option:selected");
    var infoAdicional = valorSeleccionado.data("descripcion");


    if ($('#NOTACION_BEI')[0].selectize) {
        var selectize = $('#NOTACION_BEI')[0].selectize;
        selectize.clear();
        selectize.clearOptions();
        selectize.enable();
    } else {
        $('#NOTACION_BEI').prop('disabled', false);
        $('#NOTACION_BEI').html('');
        $('#NOTACION_BEI').val('');
    }

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/listaConnotaciones/" + $(this).val(),
        data: {},
        cache: false,
        success: function (dato) {
            var opciones = dato.opciones;

            if ($('#NOTACION_BEI')[0].selectize) {
                var selectize = $('#NOTACION_BEI')[0].selectize;
                selectize.clearOptions();  // Limpia las opciones anteriores
                selectize.addOption(opciones);  // Añade las nuevas opciones
                selectize.refreshOptions(false);  // Refresca el dropdown
            } else {
                $('#NOTACION_BEI').selectize({
                    options: opciones,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: "Seleccione una o más opciones",
                    render: {
                        option: function (item, escape) {
                            return `
                                <div>
                                    ${escape(item.text)}
                                    <small>${escape(item.description)}</small>
                                </div>
                            `;
                        }
                    }
                });

                var selectize = $('#NOTACION_BEI')[0].selectize;

                selectize.on('change', function () {
                    actualizarDescripcionNotacion();
                });
            }
        },
        beforeSend: function () {
            // $('#CONNOTACION').html('<option selected>Consultando datos...</option>');
        },
        error: function (dato) {
            $('#NOTACION_BEI').html('<option value="" disabled>Error al consultar los datos</option>');
            return false;
        }
    });

    function actualizarDescripcionNotacion() {
        var selectize = $('#NOTACION_BEI')[0].selectize;
        var selectedValues = selectize.getValue();
        var selectedOptionsHtml = '';
        var numSustancias = 0;

        selectedValues.forEach(value => {
            var option = selectize.options[value];
            if (option) {
                numSustancias++;
                selectedOptionsHtml += `
                    <div id="descripcionNotacion" class="mb-2">
                        <label class="form-check-label">${option.text} : ${option.description || ''}</label>
                    </div>
                `;
            }
        });

        $('#opciones_seleccionadas_bei').html(selectedOptionsHtml); 
    }
});


function mostarNotacionesSelccionadas(ID_ENTIDAD, ID_BEI) {
    $('#opciones_seleccionadas_bei').html('');


    if ($('#NOTACION_BEI')[0].selectize) {
        var selectize = $('#NOTACION_BEI')[0].selectize;
        selectize.clear();
    } else {

        $('#NOTACION_BEI').html('');
        
    }


    if (procesoEntidades == 0) {
        procesoEntidades = 1;

        $.ajax({
            type: "get",
            dataType: "json",
            url: "/mostarNotacionesSelccionadas/" + ID_ENTIDAD + '/' + ID_BEI,
            data: {},
            cache: false,
            success: function (data) {
                // Verifica si Selectize ya está inicializado y destruye la instancia
                if ($('#NOTACION_BEI')[0].selectize) {
                    $('#NOTACION_BEI')[0].selectize.destroy();
                }

                // Inicializa Selectize con las opciones deseadas
                $('#NOTACION_BEI').selectize({
                    options: data.opciones,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    placeholder: "Seleccione una o más opciones",
                    render: {
                        option: function (item, escape) {
                            return `
                                <div>
                                    ${escape(item.text)}
                                    <small>${escape(item.description || '')}</small>
                                </div>
                            `;
                        }
                    }
                });

                // Obtener la instancia de Selectize
                var selectize = $('#NOTACION_BEI')[0].selectize;

                // Asignar valor a las connotaciones seleccionadas
                if (Array.isArray(data.connotacionesSeleccionadas)) {
                    selectize.setValue(data.connotacionesSeleccionadas);
                }

                // Llama a la función que muestra las descripciones de las connotaciones
                actualizarDescripcionNotacion2();

                 // Ahora puedes utilizar selectize para manejar la selección y deselección
                var selectize = $('#NOTACION_BEI')[0].selectize;

                // Evento de cambio de selección
                selectize.on('change', function () {
                    actualizarDescripcionNotacion();
                });

                function actualizarDescripcionNotacion() {
                    var selectedValues = selectize.getValue();
                    var selectedOptionsHtml = '';
                    var numSustancias = 0;

                    selectedValues.forEach(value => {
                        var option = selectize.options[value];
                        if (option) {
                            numSustancias++;
                            selectedOptionsHtml += `
                                <div id="descripcionNotacion" class="mb-2">
                                    <label class="form-check-label">${option.text} : ${option.description || ''} </label>
                                </div>
                            `;
                        }
                    });

                    $('#opciones_seleccionadas_bei').html(selectedOptionsHtml);
                }

                procesoEntidades = 0;
            },
            beforeSend: function () {
                // $('#CONNOTACION').html('<option selected>Consultando datos...</option>');
            },
            error: function (dato) {
                console.error("Error al consultar los datos:", dato);
            }
        });
    }
}

function actualizarDescripcionNotacion2() {
    var selectize = $('#NOTACION_BEI')[0].selectize;
    var selectedValues = selectize.getValue();
    var selectedOptionsHtml = '';

    selectedValues.forEach(value => {
        var option = selectize.options[value];
        if (option) {
            selectedOptionsHtml += `
                <div id="descripcionNotacion" class="mb-2">
                    <label class="form-check-label">${option.text} : ${option.description || ''} </label>
                </div>
            `;
        }
    });

    $('#opciones_seleccionadas_bei').html(selectedOptionsHtml);
}



// Evento de selección
// $('#CONNOTACION')[0].selectize.on('change', function () {
//     console.log('Cambio detectado');
//     actualizarDescripcionConnotacion2();
// });






//FUNCIONES PARA PODER AGREGAR EL % DEL COMPONENTE
function mostrarPorcentajeSustancia(sus) {
    
    $('#textPorcentajes').css('display', 'block');
    $('#tablaSustanciasSeleccionadas').css('display', 'block');
    
    var selectedOptions = $('#sustancias_quimicias').select2('data');
    var selectedOptionsHtml = '';
    
    selectedOptions.forEach(option => {

        if (option.id == sus) {
            var tr = $(`#sustanciaPorcentaje_${option.id}`);
            if (!tr.length) {
                selectedOptionsHtml += `
                  
                    <tr id="sustanciaPorcentaje_${option.id}">
                        <td>
                            <input type="hidden" class="form-control IDSustanciaQuimica text-center" style="width: 100%;" name="SUSTANCIA_QUIMICA_ID" value="${option.id}">
                            <label class="form-check-label" for="sustancia_${option.id}">${option.text}</label>
                        </td>
                        <td>
                            <select class="custom-select form-control" id="tipoSustancia_${option.id}" name="TIPO" style="width: 100%;" onchange="validarCamposRequeridos('porcentajeSustancia_${option.id}', 'temSustancia_${option.id}', this.value)" required>
                                <option value="1">Componente</option>
                                <option value="2">Subproducto</option>
                            </select>
                        </td>
                        <td>
                            <select class="custom-select form-control"  id="operadorSustancia_${option.id}" name="OPERADOR" style="width: 100%;" >
                            <option value="*" selected> * </option>
                            <option value="<"> < </option>
                            <option value=">"> > </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control porcentajeSustancias text-center error" id="porcentajeSustancia_${option.id}" name="PORCENTAJE" style="width: 100%;" placeholder="% Componente" min="0" >
                        </td>
                        <td>
                            <select class="custom-select form-control error" id="estadoSustancia_${option.id}" name="ESTADO_FISICO" style="width: 100%;"  onchange="cambiarFormaSustancia('formaSustancia_${option.id}','valatilidadSustancia_${option.id}', this.value)" required>
                            <option value="" selected disabled> Estado Fisico </option>
                            <option value="1"> Líquido </option>
                            <option value="2"> Sólido </option>
                            <option value="6"> Gas </option>
                            <option value="100"> ND </option>
                            </select>
                        </td>
                        <td>
                            <select class="custom-select form-control" id="formaSustancia_${option.id}" name="FORMA_SUSTANCIA" style="width: 100%;" onchange="cambiarVolatilidadSustancia('valatilidadSustancia_${option.id}', this.value)"  required>
                                <option value="" selected disabled> Forma </option>
                            </select>
                        </td>
                        <td style="display: flex; align-items: center;">
                            <button type="button" class="btn btn-danger text-center mb-1" style="margin-right: 10px; width: 35px; height: 35px; border-radius: 9px;margin-left: 10px;" data-toggle="tooltip" title="Click para cambiar la Tem. de ebullición a °C una vez insertada en °F" onclick="cambiarGrados('temSustancia_${option.id}')">
                                <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                            </button>

                            <input type="text" onkeyup="cambiarVolatilidadSustanciaTem('estadoSustancia_${option.id}','valatilidadSustancia_${option.id}', this.value)" class="form-control text-center error" id="temSustancia_${option.id}" name="TEM_EBULLICION" style="flex: 1;" placeholder="Tem. de ebullición" min="0" >
                        </td>
                        <td>
                            <select class="custom-select form-control error" id="valatilidadSustancia_${option.id}" name="VOLATILIDAD" style="width: 100%;" required>
                            <option value="" selected disabled> Volatilidad </option>
                            <option value="1"> Baja </option>
                            <option value="2"> Media </option>
                            <option value="3"> Alta </option>
                            <option value="100"> ND </option>
                            </select>
                        </td>
                    </tr>
                    
                `;
            }
        }
     })

    // $('#sustancias_seleccionadas').append(selectedOptionsHtml);
     $('#tablaSustanciasSeleccionadas tbody').append(selectedOptionsHtml);
        
}




// Actualizar el texto cuando se selecciona una opción
$('#sustancias_quimicias').on('select2:select', function (e) {

    var selectedOption = e.params.data.id;
    mostrarPorcentajeSustancia(selectedOption);
});

// Actualizar el texto cuando se elimina una opción
// Escuchar el evento cuando se deselecciona una opción
$('#sustancias_quimicias').on('select2:unselect', function (e) {
    var deselectedOption = e.params.data.id;
    eliminarPorcentajeSustancia(deselectedOption);
});


// Función para eliminar la fila correspondiente a una sustancia
function eliminarPorcentajeSustancia(optionId) {
    // Encontrar la fila correspondiente a la opción deseleccionada y eliminarla
    var row = $(`#sustanciaPorcentaje_${optionId}`);
    if (row.length) {
        row.remove();
    }
}

//FUNCION PARA VOLVER REQUERIDOS LOS PUNTOS DE PORCENTAJE DEL COMPONENTE Y LA TEM DE EBULLICION DE ESTAS
function validarCamposRequeridos(porcentaje, tem, opcion) {

    // console.log(opcion)
    
    if (parseInt(opcion) == 1) { //Componente
        $('#' + porcentaje).addClass('error').prop('required', true).val('')
        $('#' + tem).addClass('error').prop('required', true).val('')
    
        
    } else { //Subproducto
        
        $('#' + porcentaje).removeClass('error').prop('required', false).val(0)
        $('#' + tem).removeClass('error').prop('required', false).val(0)
    }
}


function crearArregloPorcentajeSustancia(HOJA_ID) {
    var porcentajes = [];

    // Itera sobre cada fila de la tabla
    $('#tablaSustanciasSeleccionadas tbody tr').each(function() {
        var porcentaje = {
            'HOJA_SEGURIDAD_ID': HOJA_ID,
            'SUSTANCIA_QUIMICA_ID': $(this).find("input[name='SUSTANCIA_QUIMICA_ID']").val(),
            'TIPO': $(this).find("select[name='TIPO']").val(),
            'OPERADOR': $(this).find("select[name='OPERADOR']").val(),
            'PORCENTAJE': $(this).find("input[name='PORCENTAJE']").val(),
            'TEM_EBULLICION': $(this).find("input[name='TEM_EBULLICION']").val(),
            'ESTADO_FISICO': $(this).find("select[name='ESTADO_FISICO']").val(),
            'VOLATILIDAD': $(this).find("select[name='VOLATILIDAD']").val(),
            'FORMA': $(this).find("select[name='FORMA_SUSTANCIA']").val()
        };

        porcentajes.push(porcentaje);
    });

    return porcentajes;
}

 





function seleccionar_sustanciaQuimicaEntidad() {
    
    $('#tabla_catSustanciasQuimicaEntidad tbody').on('click', 'td>button.EDITAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustanciaQuimicasEntidad.row(tr);

        // campos hidden
        $('#ID_SUSTANCIA_QUIMICA_ENTIDAD').val(row.data().ID_SUSTANCIA_QUIMICA_ENTIDAD);
        $('#SUSTANCIA_QUIMICA_ID').val(row.data().SUSTANCIA_QUIMICA_ID);
        

        // campos visibles
        $("#ENTIDAD_ID").val(row.data().ENTIDAD_ID);
        $("#DESCRIPCION_NORMATIVA").val(row.data().DESCRIPCION_NORMATIVA);  
        $("#VLE_PPT").val(row.data().VLE_PPT);
        $("#VLE_CT_P").val(row.data().VLE_CT_P);
        $("#NOTA_SUSTANCIA_ENTIDAD").val(row.data().NOTA_SUSTANCIA_ENTIDAD);



        //limpiamos el div en donde estan los BEIs
        $(".agregarBeis").empty();

        //Si la sustancia tiene BEIs mandamos a llamar la funcion de que muestra los BEIs
        if (parseInt(row.data().TIENE_BEIS) == 1) {
            obtenerBEIs(row)
            $('#SUSTANCIA_BEIS_SI').prop('checked', true);
            $('#div_btn_agregarBeis').css('display','block');

        } else {
            $('#SUSTANCIA_BEIS_NO').prop('checked', true);
            $('#div_btn_agregarBeis').css('display','none');
        

        }

       
        mostarConnotacionesSelccionadas(row.data().ENTIDAD_ID, row.data().ID_SUSTANCIA_QUIMICA_ENTIDAD)

        $('#titulo_modal_sustancia_entidad').html('Editar Entidad para la sustancia: ' + $('#SUSTANCIA_QUIMICA').val());


        // abrir modal
        $('#modal_catSustanciaQuimicaEntidad').modal({backdrop:false});
    });
}


function eliminar_sustanciaQuimicaEntidad () {
    
    $('#tabla_catSustanciasQuimicaEntidad tbody').on('click', 'td>button.ELIMINAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustanciaQuimicasEntidad.row(tr);

            swal({   
        title: "¿Está seguro de eliminar este registro?",   
        text: "Normativa: "+row.data().DESCRIPCION_NORMATIVA,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Eliminar!",   
        cancelButtonText: "Cancelar!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm) {
            // cerrar msj confirmacion
            swal.close();

            // eliminar
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/catSustanciaQuimicaEntidadEliminar/"+row.data().ID_SUSTANCIA_QUIMICA_ENTIDAD,
                data:{},
                cache: false,
                success: function (dato) {
                    
                     tabla_catsustanciaQuimicasEntidad.ajax.url("/tablasustanciasEntidad/" + SUSTANCIA_QUIMICA_ID).load();

                    
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
                    // alert('Error: '+dato.msj);
                    return false;
                }
            });//Fin ajax
        
        } else {
            // mensaje
            swal({
                title: "Cancelado",
                text: "Acción cancelada",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 500,
                showConfirmButton: false
            });   
        } 
    });
       

       

       
    });
}


function editar_metodo_sustancia() {
    
    $('#tabla_metodosSustanciasQuimicas tbody').on('click', 'td>button.EDITAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_metodosSustanciasQuimicas.row(tr);

        $('#form_metodosSustanciasQuimicas').each(function () {
            this.reset();
        });


        // campos hidden
        $('#METODO_SUSTANCIA_QUIMICA_ID').val(row.data().SUSTANCIAS_QUIMICA_ID);
        $('#ID_METODO').val(row.data().ID_METODO);
        $('#ELIMINAR_METODO').val(0);
        $('#CATALOGO_METODO').val(13);
        

        // campos visibles
        $("#DESCRIPCION_METODO").val(row.data().DESCRIPCION);  


        $('#titulo_modal_metodo').html('Editar método de evaluación para ' + $('#SUSTANCIA_QUIMICA').val());
        $('#modal_metodosSustanciasQuimicas').modal({ backdrop: false });
        
    });
}


function eliminar_metodo_sustancia() {
    
    $('#tabla_metodosSustanciasQuimicas tbody').on('click', 'td>button.ELIMINAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_metodosSustanciasQuimicas.row(tr);

        
        swal({   
            title: "¿Está seguro de eliminar este registro?",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Eliminar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
            }, function (isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // campos hidden
                    $('#METODO_SUSTANCIA_QUIMICA_ID').val(row.data().SUSTANCIAS_QUIMICA_ID);
                    $('#ID_METODO').val(row.data().ID_METODO);
                    $('#ELIMINAR_METODO').val(1);
                    $('#CATALOGO_METODO').val(13);

                    // campos visibles
                    $("#DESCRIPCION_METODO").val(row.data().DESCRIPCION);  


                    $('#form_metodosSustanciasQuimicas').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: '/recsensorialquimicoscatalogos',
                        data: {},
                        resetForm: false,
                        success: function(dato){
                            // actualiza tabla
                            tabla_metodosSustanciasQuimicas.ajax.url("/listaMetodosSustanciasQuimicas/" + SUSTANCIA_QUIMICA_ID).load();

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
                        error: function(dato) {
                            
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
        })       
        
    });
}



function seleccionar_beiQuimicaEntidad() {
    
    $('#tabla_beiSustanciasQuimicas tbody').on('click', 'td>button.EDITAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_beiSustanciasQuimicas.row(tr)

        // campos hidden
        $('#SUSTANCIA_QUIMICA_ID_BEI').val(row.data().SUSTANCIA_QUIMICA_ID);
        $('#ID_BEI').val(row.data().ID_BEI);
        $('#ELIMINAR_BEI').val(0);
        

        // campos visibles
        $("#ENTIDAD_ID_BEI").val(row.data().ENTIDAD_ID);
        $("#TIEMPO_MUESTREO").val(row.data().TIEMPO_MUESTREO);
        $("#BEI_DESCRIPCION").val(row.data().BEI_DESCRIPCION);
        $("#DETERMINANTE_BEI").val(row.data().DETERMINANTE);
        $("#RECOMENDACION_BEI").val(row.data().RECOMENDACION);
        $("#UNIDAD_MEDIDA_BEI").val(row.data().UNIDAD_MEDIDA);
        $("#VALOR_REFERENCIA").val(row.data().VALOR_REFERENCIA); 


       
        mostarNotacionesSelccionadas(row.data().ENTIDAD_ID, row.data().ID_BEI)

        $('#titulo_modal_bei_sustancia').html('Editar BEI para la sustancia: ' + $('#SUSTANCIA_QUIMICA').val());


        // abrir modal
        $('#modal_beiSustancias').modal({backdrop:false});
    });
}

function eliminar_bei_sustancia() {
    
    $('#tabla_beiSustanciasQuimicas tbody').on('click', 'td>button.ELIMINAR', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_beiSustanciasQuimicas.row(tr);

        
        swal({   
            title: "¿Está seguro de eliminar este registro?",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Eliminar!",   
            cancelButtonText: "Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
            }, function (isConfirm) {
                if (isConfirm) {
                    // cerrar msj confirmacion
                    swal.close();

                    // campos hidden
                    $('#SUSTANCIA_QUIMICA_ID_BEI').val(row.data().SUSTANCIA_QUIMICA_ID);
                    $('#ID_BEI').val(row.data().ID_BEI);
                    $('#ELIMINAR_BEI').val(1);


                    $('#form_beiSustancias').ajaxForm({
                        dataType: 'json',
                        type: 'POST',
                        url: '/recsensorialquimicoscatalogos',
                        data: {},
                        resetForm: false,
                        success: function(dato){
                            // actualiza tabla
                            tabla_beiSustanciasQuimicas.ajax.url("/listaBeiSustanciasQuimicas/" + SUSTANCIA_QUIMICA_ID).load();

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
                        error: function(dato) {
                            
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
        })       
        
    });
}


function ver_sustancia_quimico(){
    
    $('#tabla_catsustancia_quimicas tbody').on('click', 'td.Ver', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catsustanciaQuimicas.row(tr);

        $('.tablaEntidades').fadeIn(0)

        // campos hidden
        $('#ID_SUSTANCIA_QUIMICA').val(row.data().ID_SUSTANCIA_QUIMICA);
        $('#ACTIVO').val(row.data().ACTIVO);
        $('#SUSTANCIA_QUIMICO_CATALAGO').val(0);

        // campos visibles
        $("#SUSTANCIA_QUIMICA").val(row.data().SUSTANCIA_QUIMICA);
        $("#ALTERACION_EFECTO").val(row.data().ALTERACION_EFECTO);
        $("#PM").val(row.data().PM);
        $("#NUM_CAS").val(row.data().NUM_CAS);
        
    
        $("#VIA_INGRESO").val(row.data().VIA_INGRESO);
        

        $('#clasificacion_riesgo_text').text(row.data().CLASIFICACION_RIESGO);
        $('#CLASIFICACION_RIESGO').val(row.data().CLASIFICACION_RIESGO);

        
        //mostrar las categorias
        if (row.data().TIPO_CLASIFICACION == 1) {
            
            $('#TIPO_CLASIFICACION_CAT').prop('checked', true);
            $('#divCategoriaSalud').fadeIn(0)
            $('#divGradoSalud').fadeOut(0)
            $('#CATEGORIA_PELIGRO_'+row.data().CATEGORIA_PELIGRO_ID).prop('checked', true)


        } else { //Mostrar los grados
            
            $('#TIPO_CLASIFICACION_GRADO').prop('checked', true);
            $('#divGradoSalud').fadeIn(0)
            $('#divCategoriaSalud').fadeOut(0)
            $('#GRADO_RIESGO_'+row.data().GRADO_RIESGO_ID).prop('checked', true)
            
        }



        
        SUSTANCIA_QUIMICA_ID = row.data().ID_SUSTANCIA_QUIMICA

        if (procesoEntidad == 0) {
            
            //INICIAMOS LA TABLA DE LOS DATOS POR ENTIDADES
            tablaSustanciasQuimicasEntidades(SUSTANCIA_QUIMICA_ID)
            tablaMetodosSustanciasQuimicas(SUSTANCIA_QUIMICA_ID)
            tablaBeiSustanciasQuimicas(SUSTANCIA_QUIMICA_ID);

            
        }
         var submitButtons = $('#form_catSustanciQuimica').find('input[type="submit"], button[type="submit"]').hide();
    
       
           
        // abrir modal
        $('#modal_catSustanciaQuimica').modal({backdrop:false});
    
    });
}

//FUNCION PARA EL TIPO DE ESTADO FISICO
$('#catestadofisicosustancia_id').on('change', function () {
    var selectedText = $(this).find('option:selected').text();

    //SOLIDOS: TODOS SON DE UNA ALTA VOLATILIDAD
    if (selectedText.toUpperCase() == 'GAS' || selectedText.toUpperCase() == 'POLVO' || selectedText.toUpperCase() == 'AEROSOL') {
        $('#catvolatilidad_id').val(3)
        $('#catsustancia_puntoEbullicion').prop('disabled', true).val('')
        $('#divPuntoEbullicion').css('display', 'block')
        $('#divTipoSolido').css('display', 'none')

    //SOLIDO: DEPENDEN DEL TIPO DE SOLIDO
    } else if (selectedText.toUpperCase() == 'SEMISOLIDO' || selectedText.toUpperCase() == 'SOLIDO' || selectedText.toUpperCase() == 'SÓLIDO') {
        $('#catvolatilidad_id').val('')
        $('#catsustancia_puntoEbullicion').prop('disabled', true).val('')
        $('#divPuntoEbullicion').css('display', 'none')
        $('#divTipoSolido').css('display', 'block')


    //LIQUIDOS: DEPENDEN DEL PUNTO DE EBULLICION
    } else if (selectedText.toUpperCase() == 'LÍQUIDO' || selectedText.toUpperCase() == 'LIQUIDO') {
        $('#divPuntoEbullicion').css('display', 'block')
        $('#divTipoSolido').css('display', 'none')
        $('#catsustancia_puntoEbullicion').prop('disabled', false)
        $('#catvolatilidad_id').val('')
        
    }

});


//FUNCION PARA EL TIPO FORMA DE UNA SUSTANCIA
function cambiarFormaSustancia(id, idVolatilidad, opcion) {
    console.log(id, opcion)
    html = ""
      //LIQUIDOS
    if (opcion == 1 ) {
        html += '<option value="Neblina"> Neblina </option><option value="Rocío"> Rocío </option>'
        $('#'+id).html(html)
        $('#' + idVolatilidad).val('').addClass('error')

    //SOLIDO
    } else if (opcion == 2) {
        html += '<option value="Polvo"> Polvo </option><option value="Humo"> Humo </option><option value="Fibra"> Fibra </option>'
        $('#' + id).html(html)
        cambiarVolatilidadSustancia(idVolatilidad, 'Polvo')

    //GAS
    } else if (opcion == 6) {
        html += '<option value="Vapor"> Vapor </option><option value="Gas"> Gas </option>'
        $('#' + id).html(html)
        $('#' + idVolatilidad).val(3).removeClass('error')

    }
}

function cambiarVolatilidadSustancia(id, opcion) {
    if (opcion == 'Polvo') {       
        $('#' + id).val(2).removeClass('error')

    } else if (opcion == 'Humo') {        
        $('#' + id).val(3).removeClass('error')

    } else if (opcion == 'Fibra') {
       
        $('#' + id).val(1).removeClass('error')
    }
}





function cambiarVolatilidadSustanciaTem(opcion, id,  valor) {
    
    var opcion = $('#' + opcion).val()
    var inputValue = parseInt(valor);

    //Realizamos la operacion solo cuando sea Liquido
    if (opcion == 1) {
        
        if (inputValue > 150) {
            $('#' + id).val(1).removeClass('error')
            
        } else if (inputValue >= 50 && inputValue <= 150) {
            $('#' + id).val(2).removeClass('error')
    
        } else if (inputValue < 50) {
            $('#' + id).val(3).removeClass('error')
    
        } else {
            $('#' + id).val('').addClass('error')
            
        }
    }
}


//FUNCION PARA EL TIPO DE  SOLIDO
$('#catsustancia_solidoTipo').on('change', function () {
    var selectedText = $(this).find('option:selected').text();

    if (selectedText.toUpperCase() == 'HUMO' ) {
        $('#catvolatilidad_id').val(3)
      
    } else if (selectedText.toUpperCase() == 'POLVO') {
        $('#catvolatilidad_id').val(2)
      
    } else if (selectedText.toUpperCase() == 'FIBRA') {
        $('#catvolatilidad_id').val(1)

    }

});

$('#catsustancia_puntoEbullicion').on('input keyup', function() {
    var inputValue = $(this).val();
    
    if (inputValue > 150) {
        $('#catvolatilidad_id').val(1)
        
        
    } else if (inputValue > 50 && inputValue <= 150) {
        $('#catvolatilidad_id').val(2)
        

    } else if (inputValue < 50) {
        $('#catvolatilidad_id').val(3)
        

    } else {
        $('#catvolatilidad_id').val('')
        
    }
});




function boton_nuevo_catEntidades(){
    // Borrar formulario
    $('#form_catEntidades').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_ENTIDAD').val(0);
    $('#ACTIVO_ENTIDAD').val(1);


    // abrir modal
    $('#modal_catEntidades').modal({backdrop:false});
}

$("#boton_guardar_catEntidades").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catEntidades').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catEntidades.ajax.url("/recsensorialquimicoscatalogostabla/10").load();

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
                $('#boton_guardar_catEntidades').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catEntidades').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catEntidades').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catEntidades').html('Guardar <i class="fa fa-save"></i>');
                
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

function selecciona_catEntidades()
{
    $('#tabla_catEntidades tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catEntidades.row(tr);

        // campos hidden
        $('#ID_ENTIDAD').val(row.data().ID_ENTIDAD);
        $('#ACTIVO_ENTIDAD').val(row.data().ACTIVO);

        $('#ENTIDAD_ENTIDAD').val(row.data().ENTIDAD);
        $('#ENTIDAD_DESCRIPCION').val(row.data().DESCRIPCION);

        
        
        
        $('#CATALOGO_UNIDAD').val(9);

        // abrir modal
        $('#modal_catEntidades').modal({backdrop:false});
    });
}


// --------------------------------

function boton_nuevo_catConnotacion(){
    // Borrar formulario
    $('#form_catConnotacion').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_CONNOTACION').val(0);
    $('#ACTIVO_CONNOTACION').val(1);


    // abrir modal
    $('#modal_catConnotacion').modal({backdrop:false});
}

// $("#boton_guardar_catConnotacion").click(function()
// {
//     // valida campos vacios
//     var valida = this.form.checkValidity();
//     if (valida)
//     {
//         // enviar datos
//         $('#form_catConnotacion').ajaxForm({
//             dataType: 'json',
//             type: 'POST',
//             url: '/recsensorialquimicoscatalogos',
//             data: {},
//             resetForm: false,
//             success: function(dato)
//             {


//                 if (dato.code == 2) {
//                        // mensaje
//                     swal({
//                         title: "Connotación repetida",
//                          text: ""+dato.msj,
//                         type: "warning", // warning, error, success, info
//                         buttons: {
//                             visible: false, // true , false
//                         },
//                         timer: 2000,
//                         showConfirmButton: false
//                     });
                    

//                 } else {
                    
//                     // actualiza tabla
//                     tabla_catConnotacion.ajax.url("/recsensorialquimicoscatalogostabla/9").load();
    
    
    
//                     // mensaje
//                     swal({
//                         title: "Correcto",
//                          text: ""+dato.msj,
//                         type: "success", // warning, error, success, info
//                         buttons: {
//                             visible: false, // true , false
//                         },
//                         timer: 1500,
//                         showConfirmButton: false
//                     });
    
                   
    
//                     // cerrar modal
//                     $('#modal_catConnotacion').modal('hide');
//                 }

//                  // actualiza boton
//                 $('#boton_guardar_catConnotacion').html('Guardar <i class="fa fa-save"></i>');
//             },
//             beforeSend: function()
//             {
//                 $('#boton_guardar_catConnotacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
//             },
//             error: function(dato)
//             {
//                 // actualiza boton
//                 $('#boton_guardar_catConnotacion').html('Guardar <i class="fa fa-save"></i>');
                
//                 // mensaje
//                 swal({
//                     title: "Error",
//                     text: "Error en la acción: "+dato,
//                     type: "error", // warning, error, success, info
//                     buttons: {
//                         visible: false, // true , false
//                     },
//                     timer: 1500,
//                     showConfirmButton: false
//                 });
//                 return false;
//             }
//         }).submit();
//         return false;
//     }
// });


$("#boton_guardar_catConnotacion").click(function () {
    var valida = this.form.checkValidity();

    if (valida) {
        $('#form_catConnotacion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function (dato) {
                // actualiza tabla
                tabla_catConnotacion.ajax.url("/recsensorialquimicoscatalogostabla/9").load();

                // mensaje de éxito
                swal({
                    title: "Correcto",
                    text: "" + dato.msj,
                    type: "success",
                    buttons: { visible: false },
                    timer: 1500,
                    showConfirmButton: false
                });

                // cerrar modal
                $('#modal_catConnotacion').modal('hide');

                // restaurar botón
                $('#boton_guardar_catConnotacion').html('Guardar <i class="fa fa-save"></i>');
            },
            beforeSend: function () {
                $('#boton_guardar_catConnotacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function (dato) {
                $('#boton_guardar_catConnotacion').html('Guardar <i class="fa fa-save"></i>');

                swal({
                    title: "Error",
                    text: "Error en la acción: " + dato,
                    type: "error",
                    buttons: { visible: false },
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }).submit();
        return false;
    }
});



function selecciona_catConnotacion()
{
    $('#tabla_catConnotacion tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catConnotacion.row(tr);

        // campos hidden
        $('#ID_CONNOTACION').val(row.data().ID_CONNOTACION);
        $('#ACTIVO_CONNOTACION').val(row.data().ACTIVO);

        $('#CONNOTACION_ENTIDAD').val(row.data().ID_ENTIDAD);
        $('#CONNOTACION_ABREVIATURA').val(row.data().ABREVIATURA);
        $('#CONNOTACION_DESCRIPCION').val(row.data().DESCRIPCION);

        
        
        
        $('#CATALOGO_UNIDAD').val(9);

        // abrir modal
        $('#modal_catConnotacion').modal({backdrop:false});
    });
}

function boton_nuevo_catUnidadMedida(){
    // Borrar formulario
    $('#form_catUnidadMedida').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_UNIDAD_MEDIDA').val(0);
    $('#ACTIVO_UNIDAD').val(1);


    // abrir modal
    $('#modal_catUnidadMedida').modal({backdrop:false});
}


$("#boton_guardar_catUnidadMedida").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_catUnidadMedida').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catUnidadMedida.ajax.url("/recsensorialquimicoscatalogostabla/8").load();

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
                $('#boton_guardar_catUnidadMedida').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_catUnidadMedida').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_catUnidadMedida').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_catUnidadMedida').html('Guardar <i class="fa fa-save"></i>');
                
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


function selecciona_catvolatilidad()
{
    $('#tabla_catUnidadMedida tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catUnidadMedida.row(tr);

        // campos hidden
        $('#ID_UNIDAD_MEDIDA').val(row.data().ID_UNIDAD_MEDIDA);
        $('#ACTIVO_UNIDAD').val(row.data().ACTIVO);
        $('#DESCRIPCION_UNIDAD').val(row.data().DESCRIPCION);
        $('#ABREVIATURA').val(row.data().ABREVIATURA);
        $('#CATALOGO_UNIDAD').val(8);

      
        // abrir modal
        $('#modal_catUnidadMedida').modal({backdrop:false});
    });
}

function function_tabla_catConnotacion(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catConnotacion != null)
        {
            tabla_catConnotacion.destroy();
            tabla_catConnotacion = null;
        }

        tabla_catConnotacion = $('#tabla_catConnotacion').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catConnotacion(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ENTIDAD",
                    "defaultContent": ''
                },
                {
                    "data": "ABREVIATURA",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "rowsGroup": [0], //agrupar filas
            "order": [[ 0, "DESC" ]],        
            "searching": true,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catConnotacion(num_catalogo);
    }
}



function function_tabla_catEntidad(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catEntidades != null)
        {
            tabla_catEntidades.destroy();
            tabla_catEntidades = null;
        }

        tabla_catEntidades = $('#tabla_catEntidades').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catEntidad(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ID_ENTIDAD",
                    "defaultContent": ''
                },
                {
                    "data": "ENTIDAD",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": true,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catEntidad(num_catalogo);
    }
}


function function_tabla_catUnidadMedida(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catUnidadMedida != null)
        {
            tabla_catUnidadMedida.destroy();
            tabla_catUnidadMedida = null;
        }

        tabla_catUnidadMedida = $('#tabla_catUnidadMedida').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catUnidadMedida(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "numero_registro",
                    "defaultContent": ''
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "ABREVIATURA",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catUnidadMedida(num_catalogo);
    }
}


$('#boton_verInfo').on('click', function (e) {
    e.preventDefault();


    $.ajax({
        type: "get",
        dataType: "json",
        url: "/inforCartaEntidades/" + $("#ID_SUSTANCIA_QUIMICA").val(),
        data: {},
        cache: false,
        success: function (dataObject) {
            
            //CERRAMOS Y CONFIGURAMOS EL TITULO DEL MODAL
            swal.close();
            $('#tituloCartaEntidades').text('Entidades de la Sustancia Quimica: ' + $('#SUSTANCIA_QUIMICA').val())
            

            //CREAMOS EL ESQUELETO DE TODA LA CARTA

            var data = dataObject.data;
            var html = ''
            var html2 = ''
                        

            $.each(data, function (index, item) {


            var notita = item.NOTA_SUSTANCIA_ENTIDAD == '' || item.NOTA_SUSTANCIA_ENTIDAD === null ? 'Sin nota' : item.NOTA_SUSTANCIA_ENTIDAD
            
            html += `
                <div class="col-12">
                    <div class="card cartaEntidad">
                        <div class="card-body" style="padding: 6px 10px">
                            <div class="row mb-2">

                                <div class="col-12 mb-2">
                                    <h3>${index+1}. ${item.DESCRIPCION_NORMATIVA} </h3>
                                </div>
                                <hr>
                                <div class="col-5">
                                    <p><b>Connotación:</b> ${item.listaConnotaciones}</p>
                                    <p><b>VLE - PPT:</b> ${item.VLE_PPT}</p>
                                    <p><b>VLE - CT o P:</b> ${item.VLE_CT_P}</p>
                                    <p><b>Nota:</b> ${notita}</p>

                                </div>

                                <div class="col-7">
                                    <h4 class="text-center">IBEs totales: </h4>
                                    <div class="row" id='divEsqueletoBeis_${index+1}'>

                                    </div>     
                                </div>
                            </div>        
                        </div>
                    </div>
                </div>
                
                `
                $('#esqueletoCarta').html(html)

            });


            $.each(data, function (index, item) {

                html2 = ''
                var jsonBeis = JSON.parse(item.JSON_BEIS);

                if (jsonBeis != null) {
                    
                    $.each(jsonBeis, function (beisIndex, beisItem) {
    
                        
                    
                        html2 += `
    
                        <div class="col-5 m-2 p-3 mx-1 text-light cartaBei" style="background: #79bcf7; border-radius: 10px;">              
                            <li><b>Determinante:</b> ${beisItem.DETERMINATE}</li>
                            <li><b>Tiempo de muestreo:</b> ${beisItem.TIEMPO_MUESTREO}</li>
                            <li><b>IBE:</b> ${beisItem.BEI}</li>
                            <li><b>Notación:</b> ${beisItem.NOTACION}</li>
                        </div>
    
                        `;
    
                        $(`#divEsqueletoBeis_${index+1}`).html(html2)
    
                        
                    });

                    
                } else {
                    
                     html2 += `
    
                        <div class="col-10 m-2 p-3 mx-1 text-light cartaBei" style="background: #f7a5a5; border-radius: 10px;">              
                            <h3>Esta entidad NO cuenta con IBEs cargados</h3>
                        </div>
    
                        `;
    
                        $(`#divEsqueletoBeis_${index+1}`).html(html2)
                }
                
            });
            
            
            //ABRIMOS EL MODAL
            $('#modal_carta_entidades').modal({ backdrop: false });
           
        },
        beforeSend: function () {
            // mensaje
            swal({
                title: "Espere un momento...",
                text: "Estamos cargando la información",
                type: "info",
                buttons: {visible: false},
                showConfirmButton: false
            });
        },

        error: function (dato) {
            

            // mensaje
            swal({
                title: "Error",
                text: "Ups... Ocurrio un error inesperado, intentelo mas tarde",
                type: "error", // warning, error, success, info
                buttons: {
                    visible: false, // true , false
                },
                timer: 1500,
                showConfirmButton: false
            });

            return false;
        }
    });


})


$('input[name="catTipoClasificacion"]').change(function() {
    
    $('#clasificacion_riesgo_text_hoja').text('')
    $('#catClasificacionRiesgo').val('')

	var valor = $(this).val();
	if (valor === '1') {
        $('#divCategoriaSaludHoja').fadeIn(2);
        $('#divGradoSaludHoja').fadeOut(1);
        $('.gradoHoja').prop('checked', false);
        
    } else if (valor === '0') {	
        
        $('#divGradoSaludHoja').fadeIn(2);
        $('#divCategoriaSaludHoja').fadeOut(1);
        $('.catHoja').prop('checked', false);

		
	}	
});


$('input[name="catcategoriapeligrosalud_id"]').change(function() {
	
    valor = parseInt($(this).data("riesgo_cat"))

    $('#clasificacion_riesgo_text_hoja').text(valor)
    $('#catClasificacionRiesgo').val(valor)

});

$('input[name="catgradoriesgosalud_id"]').change(function() {
	
    valor = parseInt($(this).data("riesgo_grado"))

    $('#clasificacion_riesgo_text_hoja').text(valor)
    $('#catClasificacionRiesgo').val(valor)

});


function cambiarGrados(ID) {

    inputValue = $('#' + ID).val()
    total = (inputValue - 32) / 1.8
    $('#' + ID).val(Number(total.toFixed(2)))
    
}


/// CATALOGO CONCLUSIONES 

function boton_nuevo_catConclusiones(){
    // Borrar formulario
    $('#form_conclusion').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_CATCONCLUSION').val(0);
    $('#ACTIVO').val(1);


    // abrir modal
    $('#modal_conclusion').modal({backdrop:false});
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
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catConclusiones.ajax.url("/recsensorialquimicoscatalogostabla/11").load();

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
                $('#boton_guardar_conclusion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_conclusion').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_conclusion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
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

function selecciona_catConclusiones()
{
    $('#tabla_catConclusiones tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catConclusiones.row(tr);

        // campos hidden
        $('#ID_CATCONCLUSION').val(row.data().ID_CATCONCLUSION);
        $('#ACTIVO').val(row.data().ACTIVO);

        $('#NOMBRE_CONCLUSION').val(row.data().NOMBRE);
        $('#DESCRIPCION_CONCLUSION').val(row.data().DESCRIPCION);

        
        
        
        $('#CATALOGO_CONCLUSION').val(11);

        // abrir modal
        $('#modal_conclusion').modal({backdrop:false});
    });
}



function function_tabla_catConclusiones(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catConclusiones != null)
        {
            tabla_catConclusiones.destroy();
            tabla_catConclusiones = null;
        }

        tabla_catConclusiones = $('#tabla_catConclusiones').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catConclusiones(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ID_CATCONCLUSION",
                    "defaultContent": ''
                },
                {
                    "data": "NOMBRE",
                    "defaultContent": 'Sin dato'
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catConclusiones(num_catalogo);
    }
}

/// CATALOGO DESCRIPCION AREA


function boton_nuevo_catDescripcionarea(){
    // Borrar formulario
    $('#form_descripcionarea').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_DESCRIPCION_AREA').val(0);
    $('#ACTIVO_DESCRIPCION').val(1);


    // abrir modal
    $('#modal_descripcionarea').modal({backdrop:false});
}

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
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catDescripcion.ajax.url("/recsensorialquimicoscatalogostabla/12").load();

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
                $('#boton_guardar_descripcionarea').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_descripcionarea').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_descripcionarea').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
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

function Seleccciona_catDescripcionarea()
{
    $('#tabla_catDescripcion tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catDescripcion.row(tr);

        // campos hidden
        $('#ID_DESCRIPCION_AREA').val(row.data().ID_DESCRIPCION_AREA);
        $('#ACTIVO_DESCRIPCION').val(row.data().ACTIVO);

        $('#NOMBRE').val(row.data().NOMBRE);
        $('#DESCRIPCION').val(row.data().DESCRIPCION);

        
        $('#CATALOGO_DESCRIPCION').val(12);

        // abrir modal
        $('#modal_descripcionarea').modal({backdrop:false});
    });
}



function function_tabla_catDescripcionarea(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catDescripcion != null)
        {
            tabla_catDescripcion.destroy();
            tabla_catDescripcion = null;
        }

        tabla_catDescripcion = $('#tabla_catDescripcion').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catDescripcionarea(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ID_DESCRIPCION_AREA",
                    "defaultContent": ''
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catDescripcionarea(num_catalogo);
    }
}


//=========================== CATALAGO DE RECOMENDACIONES =========================
var tabla_catRecomendacion = null;
function function_tabla_catRecomendacion(num_catalogo)
{
    var ruta = "/recsensorialquimicoscatalogostabla/"+num_catalogo;

    try
    {
        // Inicializar tabla
        if (tabla_catRecomendacion != null)
        {
            tabla_catRecomendacion.destroy();
            tabla_catRecomendacion = null;
        }

        tabla_catRecomendacion = $('#tabla_catRecomendacion').DataTable({
            "ajax": {
                "url": ruta,
                "type": "get",
                "cache": false,
                error: function (xhr, error, code)
                {
                    // console.log(xhr); console.log(code);
                    function_tabla_catRecomendacion(num_catalogo);
                },
                "data": {}
            },
            "columns": [
                // {
                //     "data": "id" 
                // },
                {
                    "data": "ID_RECOMENDACION",
                    "defaultContent": ''
                },
                {
                    "data": "DESCRIPCION",
                    "defaultContent": 'Sin dato'
                },
                {
                    "className": 'Editar',
                    "orderable": false,
                    "data": 'boton_editar',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                },
                {
                    // "className": 'Estado',
                    // "orderable": false,
                    "data": 'CheckboxEstado',
                    "defaultContent": '<i class="fa fa-exclamation-circle fa-3x"></i>'
                }
            ],
            // "rowsGroup": [0, 3], //agrupar filas
            "lengthMenu": [[10, 30, 50, -1], [10, 30, 50, "Todos"]],
            "order": [[ 0, "DESC" ]],        
            "searching": false,
            "paging": false,
            "ordering": true,
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
    catch (exception)
    {
        // alert('error al cargar la tabla');
        function_tabla_catRecomendacion(num_catalogo);
    }
}

function boton_nuevo_catRecomendacion (){
    // Borrar formulario
    $('#form_recomendacion').each(function(){
        this.reset();
    });

    // campos hidden
    $('#ID_RECOMENDACION').val(0);
    $('#ACTIVO_RECOMENDACION').val(1);


    // abrir modal
    $('#modal_recomendacion').modal({backdrop:false});
}

$("#boton_guardar_recomendacion").click(function()
{
    // valida campos vacios
    var valida = this.form.checkValidity();
    if (valida)
    {
        // enviar datos
        $('#form_recomendacion').ajaxForm({
            dataType: 'json',
            type: 'POST',
            url: '/recsensorialquimicoscatalogos',
            data: {},
            resetForm: false,
            success: function(dato)
            {
                // actualiza tabla
                tabla_catRecomendacion.ajax.url("/recsensorialquimicoscatalogostabla/13").load();

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
                $('#boton_guardar_recomendacion').html('Guardar <i class="fa fa-save"></i>');

                // cerrar modal
                $('#modal_recomendacion').modal('hide');
            },
            beforeSend: function()
            {
                $('#boton_guardar_recomendacion').html('Guardando <i class="fa fa-spin fa-spinner"></i>');
            },
            error: function(dato) 
            {
                // actualiza boton
                $('#boton_guardar_recomendacion').html('Guardar <i class="fa fa-save"></i>');
                
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

function seleccionarRecomendacion()
{
    $('#tabla_catRecomendacion tbody').on('click', 'td.Editar', function() {
        // console.log();
        var tr = $(this).closest('tr');
        var row = tabla_catRecomendacion.row(tr);

        // campos hidden
        $('#ID_RECOMENDACION').val(row.data().ID_RECOMENDACION);
        $('#ACTIVO_RECOMENDACION').val(row.data().ACTIVO);

        $('#DESCRIPCION_RECOMENDACION').val(row.data().DESCRIPCION);

        
        $('#CATALOGO_RECOMENDACION').val(12);

        // abrir modal
        $('#modal_recomendacion').modal({backdrop:false});
    });
}
